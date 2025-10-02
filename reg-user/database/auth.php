<?php
session_start();

// Manual PHPMailer includes
require_once 'lib/PHPMailer/src/PHPMailer.php';
require_once 'lib/PHPMailer/src/SMTP.php';
require_once 'lib/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Enable detailed error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

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
        error_log("Database Connection failed: " . $e->getMessage());
        return null;
    }
}

// Set JSON header
header('Content-Type: application/json');

// Only proceed if POST and action is 'send_code'
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['action']) || $_POST['action'] !== 'send_code') {
    error_log("Invalid request method or action");
    echo json_encode(['success' => false, 'error' => 'invalid_request', 'message' => 'Invalid request']);
    exit();
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
error_log("Attempting to send verification code to: " . $email);

// Basic validation
if (empty($email) || strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("Email validation failed for: " . $email);
    echo json_encode(['success' => false, 'error' => 'invalid_email', 'message' => 'Invalid email address']);
    exit();
}

// Server-side domain validation
if (!preg_match('/@isufst\.edu\.ph$/i', $email)) {
    error_log("Invalid domain for email: " . $email);
    echo json_encode(['success' => false, 'error' => 'invalid_domain', 'message' => 'Please use an ISUFST email address (@isufst.edu.ph)']);
    exit();
}

// Check if email already exists
try {
    $pdo = getDBConnection();
    if (!$pdo) {
        throw new Exception('Database connection failed');
    }
    
    $stmt = $pdo->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        error_log("Duplicate email attempt: " . $email);
        echo json_encode([
            'success' => false, 
            'error' => 'duplicate_email', 
            'message' => 'Email already registered. Please use a different email address.'
        ]);
        exit();
    }
} catch (Exception $e) {
    error_log("DB check failed for email $email: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'db_error', 'message' => 'Database check failed. Try again.']);
    exit();
}

// Generate 6-digit code
$code = sprintf("%06d", mt_rand(1, 999999));
error_log("Generated verification code for $email: $code");

// Store in session
$_SESSION['verification_code'] = $code;
$_SESSION['verification_email'] = $email;
$_SESSION['code_timestamp'] = time();

// Send email using PHPMailer
$mail = new PHPMailer(true);

try {
    // SMTP Configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'clubnexusa@gmail.com';
    $mail->Password   = 'llsg lcop mwll mrvu';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    // Debugging
    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->Debugoutput = function($str, $level) {
        error_log("PHPMailer Debug: $str");
    };

    // Email content
    $mail->setFrom('clubnexusa@gmail.com', 'ISUFST ClubNexus');
    $mail->addAddress($email);
    $mail->addReplyTo('clubnexusa@gmail.com', 'ClubNexus Support');
    $mail->isHTML(false);
    $mail->Subject = 'Your Verification Code for Club Nexus Signup';
    $mail->Body    = "Hello!\n\nYour 6-digit verification code is: $code\n\nThis code expires in 10 minutes.\n\nIf you didn't request this, please ignore this email.\n\nBest regards,\nClubNexus Team";

    // Test if we can send
    error_log("Attempting to send email to: " . $email);
    
    if ($mail->send()) {
        error_log("Email sent successfully to: " . $email);
        $_SESSION['code_sent'] = true;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Verification code sent to your email!',
            'email' => $email
        ]);
        exit();
    } else {
        error_log("PHPMailer send() returned false for: " . $email);
        throw new Exception('Send method returned false');
    }

} catch (Exception $e) {
    $error_msg = "PHPMailer Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage();
    error_log($error_msg);
    
    // Clear session on failure
    unset($_SESSION['verification_code'], $_SESSION['verification_email'], $_SESSION['code_timestamp']);
    
    echo json_encode([
        'success' => false, 
        'error' => 'email_failed', 
        'message' => 'Failed to send verification code. Please try again later.'
    ]);
    exit();
}
?>
