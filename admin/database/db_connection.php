<?php
    $server_name = 'localhost';
    $user_name = "root";
    $password = '';
    $db_name = 'clubnexus_db';

    $conn = new mysqli(
        hostname: $server_name,
        username: $user_name,
        password: $password,
        database: $db_name
    );

    if ($conn->connect_error) {
        die("Connection error: " . $conn->connect_error);
    }
    $conn->set_charset('utf8mb4');
?>
