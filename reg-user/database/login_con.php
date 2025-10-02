<?php
session_start();

$pdo = require __DIR__ . "/db_connection.php";

if (isset($_POST['submit'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $selected_user_type = $_POST['user-type'] ?? '';

    // Prepare and execute query to find user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user) {
        // Verify password hash
        if (password_verify($password, $user['user_password'])) {
            $_SESSION['user_id'] = $user['user_id'];

            // Check if selected user type matches any of the user_type columns
            $user_types = [
                $user['user_type1'] ?? null,
                $user['user_type2'] ?? null,
                $user['user_type3'] ?? null,
            ];

            if (in_array($selected_user_type, $user_types, true)) {
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
        $error_message = "Login failed. User not found!";
        header("Location: ../login_form.php?error=" . urlencode($error_message));
        exit();
    }
} else {
    header("Location: ../login_form.php");
    exit();
}
?>
