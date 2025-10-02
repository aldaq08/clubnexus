<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require("db_connection.php");
$org_id = null;
$org_name = "Not logged in.";
$org_logo = "src/default_logo.png";
$user_id = $_SESSION['user_id'] ?? null;
$message = '';

if ($user_id) {
    try {
        $pdo = new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8mb4", $user_name, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT org_id, org_name, org_logo FROM organizations WHERE org_admin_id = :user_id LIMIT 1");
        $stmt->execute(['user_id' => $user_id]);
        $org = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($org) {
            $org_id = $org['org_id'];
            $org_name = htmlspecialchars($org['org_name']);
            $org_logo = !empty($org['org_logo']) ? htmlspecialchars($org['org_logo']) : "src/default_logo.png";

            $_SESSION['org_id'] = $org_id;
            $_SESSION['user_id'] = $user_id;
        } else {
            $org_name = "Organization not found.";
        }
    } catch (PDOException $e) {
        $org_name = "Database error: " . htmlspecialchars($e->getMessage());
    }
} else {
    $org_name = "Not logged in.";
}
?>
