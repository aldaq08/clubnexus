<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include your DB connection/config
    require_once __DIR__ . "/database/org-admin.php"; // adjust path if needed

    // Get POST data
    $membershipId = isset($_POST['membership_id']) ? intval($_POST['membership_id']) : 0;
    $newStatus = isset($_POST['status']) ? $_POST['status'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($membershipId <= 0) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid membership ID']);
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8mb4", $user_name, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($action === 'delete') {
            // Delete the member record
            $stmt = $pdo->prepare("DELETE FROM memberships WHERE membership_id = :id");
            $stmt->execute([':id' => $membershipId]);

            echo json_encode(['success' => true, 'message' => 'Member deleted']);
            exit;
        }

        // Validate status for update
        if (!in_array($newStatus, ['approve', 'pending', 'denied'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            exit;
        }

        // Update status
        $stmt = $pdo->prepare("UPDATE memberships SET status = :status WHERE membership_id = :id");
        $stmt->execute([':status' => $newStatus, ':id' => $membershipId]);

        echo json_encode(['success' => true, 'message' => 'Status updated']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
