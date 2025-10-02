<?php
// Database connection setup - place this at the very top before any queries
$host = 'localhost';
$dbname = 'clubnexus_db';
$username = 'root';
$password = '';
try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  echo "<p>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
  exit;
}

require_once("database/post-con.php");

// Fetch organizations
$orgSql = "SELECT org_id, org_name, org_logo FROM organizations ORDER BY org_name ASC";
$orgStmt = $pdo->prepare($orgSql);
$orgStmt->execute();
$organizations = $orgStmt->fetchAll();

// Fetch posts with org_id included
$postSql = "
    SELECT
        a.achievement_id,
        a.achievement_description,
        a.achievement_approve,
        a.created_at,
        a.achievement_files,
        o.org_name,
        o.org_logo,
        o.org_id
    FROM
        achievements AS a
    INNER JOIN
        organizations AS o ON a.org_id = o.org_id
    WHERE
        a.achievement_approve = 1
    ORDER BY a.created_at DESC
";
$postStmt = $pdo->prepare($postSql);
$postStmt->execute();
$posts = $postStmt->fetchAll();

// Helper function to decode images JSON


?>