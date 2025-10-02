<?php
header('Content-Type: application/json');
include("db_connection.php"); // Include your database connection

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate basic input (org_id is always required)
if (!isset($input['org_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required field: org_id.']);
    exit;
}

$orgId = intval($input['org_id']);

// Validate org_id
if ($orgId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid organization ID.']);
    exit;
}

// Determine action: Default to updating is_approved if not specified or invalid
$action = isset($input['action']) ? $input['action'] : 'update_approved';

if ($action === 'update_active') {
    // Handle Active/Inactive Update
    if (!isset($input['is_active'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: is_active for active status update.']);
        exit;
    }

    $isActive = intval($input['is_active']);

    // Validate is_active value
    if (!in_array($isActive, [0, 1])) {
        echo json_encode(['success' => false, 'message' => 'Invalid active status. Must be 0 (Inactive) or 1 (Active).']);
        exit;
    }

    // Optional: Ensure the organization is approved (is_approved=1) before allowing active/inactive changes
    // You can remove this check if active/inactive applies to other statuses
    $checkStmt = $mysqli->prepare("SELECT is_approved FROM organizations WHERE org_id = ?");
    if (!$checkStmt) {
        error_log("Prepare failed: " . $mysqli->error);
        echo json_encode(['success' => false, 'message' => 'Database prepare error.']);
        exit;
    }
    $checkStmt->bind_param("i", $orgId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (intval($row['is_approved']) !== 1) {
            echo json_encode(['success' => false, 'message' => 'Active status can only be updated for approved organizations.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Organization not found.']);
        exit;
    }
    $checkStmt->close();

    // Prepare and execute update query for is_active
    $stmt = $mysqli->prepare("UPDATE organizations SET is_active = ? WHERE org_id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        echo json_encode(['success' => false, 'message' => 'Database prepare error.']);
        exit;
    }

    $stmt->bind_param("ii", $isActive, $orgId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Organization active status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No organization found with the given ID or no changes made.']);
        }
    } else {
        error_log("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }

    $stmt->close();
} else {
    // Handle Approval Status Update (Original Logic)
    if (!isset($input['is_approved'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required field: is_approved for approval status update.']);
        exit;
    }

    $isApproved = intval($input['is_approved']);

    // Validate is_approved value
    if (!in_array($isApproved, [0, 1, 2])) {
        echo json_encode(['success' => false, 'message' => 'Invalid approval status. Must be 0 (Denied), 1 (Approved), or 2 (Applying).']);
        exit;
    }

    // Prepare and execute update query for is_approved
    $stmt = $mysqli->prepare("UPDATE organizations SET is_approved = ? WHERE org_id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $mysqli->error);
        echo json_encode(['success' => false, 'message' => 'Database prepare error.']);
        exit;
    }

    $stmt->bind_param("ii", $isApproved, $orgId);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Organization approval status updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No organization found with the given ID or no changes made.']);
        }
    } else {
        error_log("Execute failed: " . $stmt->error);
        echo json_encode(['success' => false, 'message' => 'Database update failed.']);
    }

    $stmt->close();
}

$mysqli->close();
?>
