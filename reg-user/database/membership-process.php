<?php
header('Content-Type: application/json');

include("../database/db_connection.php");

// Debug: Check what data is being received
error_log("POST data: " . print_r($_POST, true));
error_log("FILES data: " . print_r($_FILES, true));

// Get form data with proper validation
$org_id = isset($_POST['org_id']) ? intval($_POST['org_id']) : 0;
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
$middleName = isset($_POST['middleName']) ? trim($_POST['middleName']) : '';
$lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
$course = isset($_POST['course']) ? trim($_POST['course']) : '';
$studentDescription = isset($_POST['student_description']) ? trim($_POST['student_description']) : '';

// Validate required fields
if (empty($firstName) || empty($lastName) || $org_id <= 0 || $user_id <= 0 || empty($course)) {
    echo json_encode([
        'success' => false, 
        'message' => 'Please fill all required fields including course',
        'debug' => [
            'org_id' => $org_id,
            'user_id' => $user_id,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'course' => $course,
            'student_description' => $studentDescription
        ]
    ]);
    exit;
}

// Validate course value against allowed options
$allowed_courses = ['bsit', 'bsed', 'beed', 'bshm', 'bsoa', 'bsa'];
if (!in_array($course, $allowed_courses)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid course selected',
        'debug' => ['course' => $course]
    ]);
    exit;
}

// Handle file uploads
$upload_dir = '../src/membership/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$file_names = []; // Store only filenames instead of full paths

foreach (['photo', 'entry1', 'entry2', 'rf'] as $file_field) {
    if (isset($_FILES[$file_field]) && $_FILES[$file_field]['error'] === UPLOAD_ERR_OK) {
        $file_ext = pathinfo($_FILES[$file_field]['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '_' . $file_field . '.' . $file_ext;
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES[$file_field]['tmp_name'], $file_path)) {
            $file_names[$file_field] = $file_name; // Store only the filename
        }
    }
}

try {
    // Insert into database - storing only filenames and course
    $stmt = $pdo->prepare("INSERT INTO memberships (user_id, org_id, first_name, middle_name, last_name, course, student_description, photo, entry1, entry2, rf, status, application_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
    
    $stmt->execute([
        $user_id, 
        $org_id, 
        $firstName, 
        $middleName, 
        $lastName,
        $course,
        $studentDescription,              
        $file_names['photo'] ?? null, 
        $file_names['entry1'] ?? null, 
        $file_names['entry2'] ?? null, 
        $file_names['rf'] ?? null
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Application submitted successfully!']);
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error saving application. Please try again.']);
}
?>
