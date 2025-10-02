<?php
session_start();
error_log("Verification session check:");
error_log("Entered code: " . ($_POST['vcode'] ?? 'NULL'));
error_log("Stored code: " . ($_SESSION['verification_code'] ?? 'NULL'));
error_log("Session email: " . ($_SESSION['verification_email'] ?? 'NULL'));
error_log("Timestamp: " . ($_SESSION['code_timestamp'] ?? 'NULL'));
// INLINE DB CONNECTION FUNCTION
function getDBConnection() {
    $server_name = "localhost";
    $user_name = "root";
    $password = "";
    $db_name = "clubnexus_db";
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$server_name;dbname=$db_name;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user_name, $password, $options);
    } catch (PDOException $e) {
        error_log("Connection failed in signup_con.php: " . $e->getMessage());
        throw $e;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Invalid request method.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Step 1: Verify the verification code MATCHES what was sent
$enteredCode = trim($_POST['vcode'] ?? '');
$storedCode = $_SESSION['verification_code'] ?? '';
$sessionEmail = $_SESSION['verification_email'] ?? '';
$timestamp = $_SESSION['code_timestamp'] ?? 0;

error_log("Verification attempt - Entered: $enteredCode, Stored: $storedCode, Email: $sessionEmail");

// Check if code is valid, matches, not expired (10 minutes = 600 seconds), and email matches
if (empty($enteredCode) || 
    empty($storedCode) ||
    $enteredCode !== $storedCode || 
    (time() - $timestamp > 600) || 
    empty($sessionEmail)) {
    
    // Log the failed attempt
    error_log("Verification FAILED for email: $sessionEmail - Code mismatch or expired");
    
    // Invalid code: Clear session and show error
    unset($_SESSION['verification_code'], $_SESSION['verification_email'], $_SESSION['code_timestamp'], $_SESSION['code_sent']);
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Invalid or expired verification code. Please request a new one and try again.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Additional server-side email validation
if (!preg_match('/@isufst\.edu\.ph$/i', $sessionEmail)) {
    unset($_SESSION['verification_code'], $_SESSION['verification_email'], $_SESSION['code_timestamp'], $_SESSION['code_sent']);
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Invalid email domain. Please use an @isufst.edu.ph address.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Code is valid and matches: Clear verification session and proceed
unset($_SESSION['verification_code'], $_SESSION['verification_email'], $_SESSION['code_timestamp'], $_SESSION['code_sent']);

// Now process the form data (sanitize and validate)
$signupEmail = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['user_password'] ?? '');
$confirmPassword = trim($_POST['confirm-password'] ?? '');
$userId = trim($_POST['user_id'] ?? '');
$userPhoto = $_FILES['user_photo'] ?? null;
$fname = trim($_POST['fname'] ?? '');
$mname = trim($_POST['mname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$gender = $_POST['gender'] ?? '';
$course = $_POST['course'] ?? '';

// Verify that the email in the form matches the email we sent the code to
if ($signupEmail !== $sessionEmail) {
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Email mismatch. Please use the same email that received the verification code.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Basic validations
if ($password !== $confirmPassword || strlen($password) < 6) {
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Passwords do not match or are too short (minimum 6 characters).";
    header("Location: ../signup_form.php?status=error");
    exit();
}

if (empty($signupEmail) || empty($userId) || empty($fname) || empty($lname) || empty($gender) || empty($course)) {
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "All required fields must be filled.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

if (!preg_match('/^\d+$/', $userId)) {
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Invalid Student ID (numbers only, no dashes).";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Get DB connection
$pdo = null;
try {
    $pdo = getDBConnection();
} catch (Exception $e) {
    error_log("DB Connection failed in signup_con.php: " . $e->getMessage());
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Database connection failed. Please try again later.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Check duplicates
try {
    // Check user_id
    $check_stmt = $pdo->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $check_stmt->execute([$userId]);
    if ($check_stmt->rowCount() > 0) {
        $_SESSION['status'] = "error";
        $_SESSION['message2'] = "Student ID already exists. Please choose a different ID.";
        header("Location: ../signup_form.php?status=error");
        exit();
    }

    // Check email
    $email_check_stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
    $email_check_stmt->execute([$signupEmail]);
    if ($email_check_stmt->rowCount() > 0) {
        $_SESSION['status'] = "error";
        $_SESSION['message2'] = "Email already registered. Please use a different email.";
        header("Location: ../signup_form.php?status=error");
        exit();
    }
} catch (PDOException $e) {
    error_log("Duplicate check failed: " . $e->getMessage());
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Database check failed. Please try again.";
    header("Location: ../signup_form.php?status=error");
    exit();
}

// Handle file upload
$uploadDir = __DIR__ . '/../../src/userprofile/' . DIRECTORY_SEPARATOR;
$userPhotoPath = null;

if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        error_log("Failed to create upload directory: $uploadDir");
    }
}

if (isset($userPhoto) && $userPhoto['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $userPhoto['tmp_name'];
    $fileName = basename($userPhoto['name']);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024;
    if (!in_array($fileExt, $allowedTypes) || $userPhoto['size'] > $maxSize) {
        $_SESSION['status'] = "error";
        $_SESSION['message2'] = "Invalid photo file (JPG/PNG only, max 2MB).";
        header("Location: ../signup_form.php?status=error");
        exit();
    }

    $fileNameSanitized = preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($fileName, PATHINFO_FILENAME));
    $newFileName = $fileNameSanitized . '_' . uniqid() . '.' . $fileExt;
    $destPath = $uploadDir . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $userPhotoPath = $newFileName;
    } else {
        error_log("File upload failed to: $destPath");
        $_SESSION['status'] = "error";
        $_SESSION['message2'] = "Error uploading photo. Please try again.";
        header("Location: ../signup_form.php?status=error");
        exit();
    }
}

// Insert into DB
try {
    $sql = "INSERT INTO users (user_id, email, user_password, first_name, middle_name, last_name, user_photo, gender, course, user_type1) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'reg-user')";

    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        throw new PDOException("Prepare failed: " . implode(":", $pdo->errorInfo()));
    }

    $params = [
        $userId,
        $signupEmail,
        $password_hash,
        $fname,
        $mname ?: null,
        $lname,
        $userPhotoPath,
        $gender,
        $course
    ];

    if ($stmt->execute($params)) {
        // Log successful registration
        error_log("SUCCESS: User registered - ID: $userId, Email: $signupEmail");
        
        $_SESSION['status'] = "success";
        $_SESSION['message1'] = "Registration complete! Your account has been created and verified.";
        header("Location: ../signup_form.php?status=success");
        exit();
    } else {
        throw new PDOException("Execute failed: " . implode(":", $stmt->errorInfo()));
    }
} catch (PDOException $e) {
    error_log("Insert failed in signup_con.php: " . $e->getMessage());
    $_SESSION['status'] = "error";
    $_SESSION['message2'] = "Failed to create account. Please check your information and try again.";
    header("Location: ../signup_form.php?status=error");
    exit();
}
?>