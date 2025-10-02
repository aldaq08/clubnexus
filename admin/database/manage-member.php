<?php
session_start();
header('Content-Type: application/json');

include("database/org-admin.php"); // <-- Add this line to define DB credentials

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$memberId = intval($input['memberId'] ?? 0);

if ($memberId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid member ID']);
    exit;
}

try {
    $pdo = new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8mb4", $user_name, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE memberships SET status = 'approve' WHERE membership_id = :id");
        $stmt->execute(['id' => $memberId]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'disapprove') {
        $stmt = $pdo->prepare("UPDATE memberships SET status = 'denied' WHERE membership_id = :id");
        $stmt->execute(['id' => $memberId]);
        echo json_encode(['success' => true]);
    } elseif ($action === 'remove') {
        $stmt = $pdo->prepare("DELETE FROM memberships WHERE membership_id = :id");
        $stmt->execute(['id' => $memberId]);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
