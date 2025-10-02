<?php

include("db_connection.php");

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);
} catch (PDOException $e) {
  echo "<p>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
  exit;
}

// Fetch organizations (if needed)
$orgSql = "SELECT org_id, org_name, org_logo FROM organizations ORDER BY org_name ASC";
$orgStmt = $pdo->prepare($orgSql);
$orgStmt->execute();
$organizations = $orgStmt->fetchAll();

// Fetch posts with org_id included and all approval statuses 0,1,3
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
        a.achievement_approve IN (0,1,3)
    ORDER BY a.created_at DESC
";
$postStmt = $pdo->prepare($postSql);
$postStmt->execute();
$posts = $postStmt->fetchAll();

function getImagesArray($imagesField) {
  if (empty($imagesField)) return [];
  $images = json_decode($imagesField, true);
  if (json_last_error() === JSON_ERROR_NONE && is_array($images)) {
    return $images;
  }
  $urls = array_filter(array_map('trim', explode(',', $imagesField)));
  if (count($urls) === 1 && !empty($urls[0])) {
    return [$urls[0]];
  }
  return $urls;
}  
?>