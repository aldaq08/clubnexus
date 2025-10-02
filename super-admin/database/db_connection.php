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

// Add these variables for PDO compatibility if needed
$host = $server_name;
$dbname = $db_name;
$username = $user_name;
?>