<?php
$server_name = "localhost";
$user_name = "root";  
$password = "";
$db_name = "clubnexus_db";

// For MySQLi (existing)
$mysqli = new mysqli($server_name, $user_name, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection error: " . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');

// Add PDO connection
try {
    $conn = new PDO("mysql:host=$server_name;dbname=$db_name", $user_name, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}
?>