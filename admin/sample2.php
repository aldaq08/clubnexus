<?php
// Database connection settings
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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming $org_id is provided, for example by GET
$org_id = isset($_GET['org_id']) ? intval($_GET['org_id']) : 0;

if ($org_id <= 0) {
    die("Invalid organization ID.");
}

// SQL to get members grouped by status for the specific organization
$sql = "SELECT membership_id, first_name, middle_name, last_name, photo, status 
        FROM memberships 
        WHERE org_id =  
        ORDER BY status, last_name, first_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $org_id);
$stmt->execute();
$result = $stmt->get_result();

$members = [
    'approve' => [],
    'pending' => [],
    'denied' => []
];

// Categorize members by status
while ($row = $result->fetch_assoc()) {
    $status = strtolower($row['status']);
    if ($status === 'approve' || $status === 'approved') { 
        $members['approve'][] = $row;
    } elseif ($status === 'pending') {
        $members['pending'][] = $row;
    } elseif ($status === 'denied') {
        $members['denied'][] = $row;
    }
}

// Function to render members block
function renderMembersSection($title, $membersArray) {
    echo "<h2>" . htmlspecialchars($title) . "</h2>";
    echo '<div style="display:flex; flex-wrap:wrap; gap:20px;">';
    foreach ($membersArray as $member) {
        $fullName = htmlspecialchars($member['first_name'] . ' ' . $member['middle_name'] . ' ' . $member['last_name']);
        $photoPath = htmlspecialchars($member['photo']); // Assuming path or file name accessible via web
        echo '
            <div style="text-align:center; width: 120px;">
                <img src="' . $photoPath . '" alt="' . $fullName . '" style="width:100px; height:100px; border-radius:50%; object-fit:cover;"/>
                <div><b>' . $fullName . '</b></div>
                <div>Member</div>
            </div>
        ';
    }
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Membership Status</title>
    <style>
        .button-group {
            margin-bottom: 20px;
        }
        .button-group button {
            margin-right: 10px;
            padding: 10px 15px;
            cursor: pointer;
        }
        .members-section {
            display: none;
        }
        .members-section.active {
            display: block;
        }
    </style>
    <script>
        function showSection(section) {
            const sections = document.querySelectorAll(".members-section");
            sections.forEach(s => s.classList.remove("active"));
            document.getElementById(section).classList.add("active");
        }
        window.onload = function() {
            showSection('applying'); // Show Applying members by default
        }
    </script>
</head>
<body>

<h1>Membership Status</h1>

<div class="button-group">
    <button onclick="showSection('approve')">Approved Members</button>
    <button onclick="showSection('applying')">Applying Members</button>
    <button onclick="showSection('denied')">Denied</button>
</div>

<div id="approve" class="members-section">
    <?php renderMembersSection('Approved Members', $members['approve']); ?>
</div>

<div id="applying" class="members-section">
    <?php renderMembersSection('Applying Members', $members['pending']); ?>
</div>

<div id="denied" class="members-section">
    <?php renderMembersSection('Denied Members', $members['denied']); ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
