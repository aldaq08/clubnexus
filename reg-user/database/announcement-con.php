<?php
// Include your database connection

require_once("database/post-con.php");

$orgSql = "SELECT org_id, org_name, org_logo FROM organizations ORDER BY org_name ASC";
$orgStmt = $pdo->prepare($orgSql);
$orgStmt->execute();
$organizations = $orgStmt->fetchAll();

$sql = "
    SELECT 
        a.announcement_id,
        a.org_id,
        a.user_id,
        a.announcement_title,
        a.announcement_text,
        a.announcement_file,
        a.announcement_approve,
        a.created_at,
        a.updated_at,
        o.org_logo,
        o.org_name
    FROM announcements a
    LEFT JOIN organizations o ON a.org_id = o.org_id
    WHERE a.announcement_approve = 1
    ORDER BY a.created_at DESC
";

// Prepare and execute the statement
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Fetch all approved announcements with organization info
$announcements = $stmt->fetchAll();

// Now you can use $announcements in your HTML/PHP to display the data
?>
