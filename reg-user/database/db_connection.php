<?php
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
    $pdo = new PDO($dsn, $user_name, $password, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

return $pdo;
?>