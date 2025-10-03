<?php
include('db_connection.php');

header('Content-Type: application/json');

// Read raw JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['announcement_id'], $input['announcement_approve'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input: announcement_id or announcement_approve']);
    exit;
}

$announcement_id = intval($input['announcement_id']);
$announcement_approve = intval($input['announcement_approve']);

// Validate inputs
if ($announcement_id <= 0 || !in_array($announcement_approve, [0,1,3], true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid announcement ID or approval status']);
    exit;
}

// Proceed with update - include updated_at field
$stmt = $mysqli->prepare("UPDATE announcements SET announcement_approve = ?, updated_at = NOW() WHERE announcement_id = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
    exit;
}

$stmt->bind_param("ii", $announcement_approve, $announcement_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No rows updated. Check if announcement_id exists.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
}

$stmt->close();
?>