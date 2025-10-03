<?php
session_start();

$pdo = require __DIR__ . "/db_connection.php";

if (isset($_POST['submit'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $selected_user_type = $_POST['user-type'] ?? '';

    // Create a unique session fingerprint
    $session_fingerprint = md5(
        ($_SERVER['HTTP_USER_AGENT'] ?? '') . 
        ($_SERVER['REMOTE_ADDR'] ?? '') . 
        $selected_user_type
    );

    // Prepare and execute query to find user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user) {
        // Verify password hash
        if (password_verify($password, $user['user_password'])) {
            
            // Check if selected user type matches any of the user_type columns
            $user_types = [
                $user['user_type1'] ?? null,
                $user['user_type2'] ?? null,
                $user['user_type3'] ?? null,
            ];

            if (in_array($selected_user_type, $user_types, true)) {
                
                // Check admin approval status for admin users
                if ($selected_user_type === 'admin') {
                    // Query organizations table to check approval status
                    $org_sql = "SELECT is_approved, org_id FROM organizations WHERE org_admin_id = ?";
                    $org_stmt = $pdo->prepare($org_sql);
                    $org_stmt->execute([$user['user_id']]);
                    $organization = $org_stmt->fetch();

                    if (!$organization) {
                        $error_message = "Organization doesn't exist.";
                        header("Location: ../login_form.php?error=" . urlencode($error_message));
                        exit();
                    }

                    // Check if organization is approved (is_approve should be 1 for approved)
                    if ($organization['is_approved'] == 0 || $organization['is_approved'] == 2) {
                        $error_message = "The organization is not approved yet!";
                        header("Location: ../login_form.php?error=" . urlencode($error_message));
                        exit();
                    }
                    
                    // Store organization data in session
                    $_SESSION['org_id'] = $organization['org_id'];
                    $_SESSION['is_approved'] = $organization['is_approved'];
                }

                // Store session data with fingerprint
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_type'] = $selected_user_type;
                $_SESSION['session_fingerprint'] = $session_fingerprint;
                $_SESSION['login_time'] = time();
                $_SESSION['email'] = $email;

                // Redirect based on selected user type
                if ($selected_user_type === 'reg-user') {
                    header("Location: ../home.php");
                    exit();
                } elseif ($selected_user_type === 'admin') {
                    header("Location: ../../admin/admin-homepage.php");
                    exit();
                } elseif ($selected_user_type === 'super-admin') {
                    header("Location: ../../super-admin/superadmin-page.php");
                    exit();
                } else {
                    $error_message = "Login failed. Invalid User Type!";
                    header("Location: ../login_form.php?error=" . urlencode($error_message));
                    exit();
                }
            } else {
                // User selected a user type that does not match any stored user_type columns
                $error_message = "Login failed. User type does not match.";
                header("Location: ../login_form.php?error=" . urlencode($error_message));
                exit();
            }
        } else {
            $error_message = "Login failed. Invalid email or password!";
            header("Location: ../login_form.php?error=" . urlencode($error_message));
            exit();
        }
    } else {
        $error_message = "Organization doesn't exist.";
        header("Location: ../login_form.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    header("Location: ../login_form.php");
    exit();
}
?>