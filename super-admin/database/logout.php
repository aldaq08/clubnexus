<?php
// logout.php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
session_write_close(); // Clean up

// Optional: Clear any cookies if needed
// setcookie('session_name', '', time() - 3600, '/'); // Adjust as needed

// Redirect to login page
header('Location: ../../reg-user/login_form.php'); // Replace with your login page path
exit;
?>
