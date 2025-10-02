    <?php
    // Start session and include org-admin.php to get org info and PDO connection details
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    require("database/org-admin.php"); // This sets $org_id, $org_name, $org_logo, and has DB credentials

    // Check org_id from org-admin.php session or GET param fallback
    if (!$org_id) {
        $org_id = isset($_GET['org_id']) ? intval($_GET['org_id']) : 0;
    }

    if ($org_id <= 0) {
        die("Invalid organization ID.");
    }

    try {
        // Create PDO connection (reuse credentials from org-admin.php)
        $pdo = new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8mb4", $user_name, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute query to get members by org_id
        $stmt = $pdo->prepare("SELECT membership_id, first_name, middle_name, last_name, student_description, photo, entry1, entry2, entry3, rf, course, status 
                        FROM memberships 
                        WHERE org_id = :org_id 
                        ORDER BY status, last_name, first_name");

        $stmt->execute(['org_id' => $org_id]);

        $members = [
            'approve' => [],
            'pending' => [],
            'denied' => []
        ];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = strtolower($row['status']);
            if ($status === 'approve' || $status === 'approved') {
                $members['approve'][] = $row;
            } elseif ($status === 'pending') {
                $members['pending'][] = $row;
            } elseif ($status === 'denied') {
                $members['denied'][] = $row;
            }
        }
    } catch (PDOException $e) {
        die("Database error: " . htmlspecialchars($e->getMessage()));
    }

    // Colors for avatar backgrounds by status
    $statusColors = [
        'approve' => '#a9cce3',   // Light blue
        'pending' => '#3498db',   // Blue
        'denied' => '#839192'     // Gray-green
    ];

    // Render members function
    function renderMembersSection($membersArray, $status) {
        global $statusColors;

        if (empty($membersArray)) {
            echo '<div style="text-align:center; margin-top:20px;">
                    <img src="../src/no-membership.png" alt="" style="max-width:200px; height: auto;"/>
                    <p>No members found!</p>
                </div>';
            return;
        }

        echo '<div class="org-chart-wrapper">';
        foreach ($membersArray as $member) {
            $fullName = htmlspecialchars($member['first_name']);
            if (!empty($member['middle_name'])) {
                $fullName .= ' ' . htmlspecialchars($member['middle_name']);
            }
            $fullName .= ' ' . htmlspecialchars($member['last_name']);

            $photo = !empty($member['photo']) ? htmlspecialchars($member['photo']) : 'src/default_avatar.png';

            $courseCode = strtolower($member['course'] ?? '');
            $files = [
                'Entry 1' => $member['entry1'],
                'Entry 2' => $member['entry2'],
                'Entry 3' => $member['entry3'],
                'RF' => $member['rf'] ?? null
            ];
            $files = array_filter($files);
            $filesJson = htmlspecialchars(json_encode($files), ENT_QUOTES, 'UTF-8');
            $membershipId = (int)$member['membership_id'];

            // Escape student_description for attribute (use ENT_QUOTES)
            $studentDescription = htmlspecialchars($member['student_description'] ?? '', ENT_QUOTES);

            echo '
                <div class="officer">
                    <div style="cursor:pointer;" 
                        data-files=\'' . $filesJson . '\' 
                        data-name="' . $fullName . '" 
                        data-course="' . htmlspecialchars($courseCode) . '"
                        data-photo="' . $photo . '"
                        data-id="' . $membershipId . '"
                        data-status="' . $status . '"
                        data-description="' . $studentDescription . '"
                        onclick="showFilesModal(this)">
                        <img src="../reg-user/src/membership/' . $photo . '" alt="' . $fullName . '" />
                    </div>
                    <strong>' . $fullName . '</strong>
                    <small>Member</small>
            ';

            echo '</div>';
        }
        echo '</div>';
    }

    ?>