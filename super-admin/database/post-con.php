<?php
include("db_connection.php");

header('Content-Type: application/json');
   $input = json_decode(file_get_contents('php://input'), true);
   if (!$input || !isset($input['achievement_id'], $input['achievement_approve'])) {
       echo json_encode(['success' => false, 'message' => 'Invalid input']);
       exit;
   }
   $achievement_id = intval($input['achievement_id']);
   $achievement_approve = intval($input['achievement_approve']);


$allowedStatuses = [0, 1, 3];

if ($achievement_id > 0 && in_array($achievement_approve, $allowedStatuses, true)) {
    $stmt = $mysqli->prepare("UPDATE achievements SET achievement_approve = ? WHERE achievement_id = ?");
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . $mysqli->error]);
        exit;
    }
    
    $stmt->bind_param("ii", $achievement_approve, $achievement_id);
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No rows updated']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Execute failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}


?>