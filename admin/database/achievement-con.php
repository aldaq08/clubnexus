<?php
                    require("db_connection.php");

                    $userId = $_SESSION['user_id'] ?? null;
                    if (!$userId) {
                        echo "Please log in to view your achievements.";
                        exit;
                    }

                    // Get org IDs for user
                    $query = "SELECT a.*, o.org_logo, o.org_name 
                                FROM achievements a 
                                LEFT JOIN organizations o ON a.org_id = o.org_id 
                                WHERE a.user_id = ?
                                ORDER BY a.created_at DESC";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $userOrgIds = [];
                    while ($row = $result->fetch_assoc()) {
                        $userOrgIds[] = $row['org_id'];
                    }
                    $stmt->close();

                    if (empty($userOrgIds)) {
                        echo "No organizations found for your account.";
                        exit;
                    }

                    // Fetch achievements for user's orgs
                    $placeholders = implode(',', array_fill(0, count($userOrgIds), '?'));
                    $types = str_repeat('i', count($userOrgIds));

                    $query = "SELECT a.*, o.org_logo, o.org_name 
                            FROM achievements a 
                            LEFT JOIN organizations o ON a.org_id = o.org_id 
                            WHERE a.org_id IN ($placeholders)
                            ORDER BY a.created_at DESC";

                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param($types, ...$userOrgIds);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $posts = [];
                    while ($row = $result->fetch_assoc()) {
                        $posts[] = $row;
                    }
                    $stmt->close();
            ?>