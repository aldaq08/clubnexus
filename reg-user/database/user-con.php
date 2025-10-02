<?php
session_start();

// Assign the returned PDO instance to $pdo
$pdo = require 'database/db_connection.php';
$user_photo = 'src/userprofile/';

if (!isset($_SESSION['user_id'])) {
    $username = '';
    $user_photo = ''; // default photo path
} else {
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, user_photo FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if ($user) {
        $username = htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ? $user['middle_name'] . ' ' : '') . $user['last_name']);
        $user_photo = !empty($user['user_photo']) ? htmlspecialchars($user['user_photo']) : 'alt-photo-profile.png';
    } else {
        $username = 'Guest';
        $user_photo = 'alt-photo-profile.png';
    }
}
?>
