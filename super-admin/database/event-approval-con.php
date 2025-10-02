<?php
session_start();
include("db_connection.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$event_id = $input['event_id'] ?? null;
$action = $input['action'] ?? null;

if (!$event_id || !$action) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

// Validate action
if (!in_array($action, ['approve', 'deny'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
    exit;
}

// Determine new status
$new_status = $action === 'approve' ? 1 : 0;

try {
    // Update event status - only need event_id and new status
    $stmt = $mysqli->prepare("UPDATE events SET is_approve = ? WHERE event_id = ?");
    $stmt->bind_param("ii", $new_status, $event_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update event status']);
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

$mysqli->close();
?>