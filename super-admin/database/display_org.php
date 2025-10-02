<?php
// Include your database connection
include('../database/profile-con.php');

$orgName = '';
$orgLogo = '';
$orgCover = '';
$statusText = '';

if (isset($_GET['id'])) {
    $orgId = intval($_GET['id']);
    
    // Use PDO with prepared statements
    $sql = "SELECT * FROM organizations WHERE org_id = :orgId LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['orgId' => $orgId]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($record) {
        $orgName = htmlspecialchars($record['org_name']);
        $orgLogo = !empty($record['org_logo']) ? htmlspecialchars($record['org_logo']) : '';
        $orgCover = !empty($record['org_cover']) ? htmlspecialchars($record['org_cover']) : '';
        $statusText = $record['is_active'] ? 'Active' : 'Inactive';
    } else {
        $orgName = 'Organization not found';
    }
} else {
    $orgName = 'No Organization selected';
}
?>