<?php
    header('Content-Type: application/json');

    $conn = include('db_connection.php');

    $searchTerm = $_GET['search'] ?? '';

    if (strlen($searchTerm) < 1) {
        echo json_encode([]);
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT org_id, org_name, org_logo FROM organizations WHERE org_name LIKE :searchTerm LIMIT 20");
        $stmt->execute(['searchTerm' => "%$searchTerm%"]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($results);
    } catch (Exception $e) {
        echo json_encode([]);
    }
?>