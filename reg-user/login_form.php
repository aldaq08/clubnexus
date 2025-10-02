<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection parameters
include('database/db_connection.php');
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Assume form fields 'username' and 'password'
        $inputUsername = $_POST['email'] ?? '';
        $inputPassword = $_POST['password'] ?? '';
        // Query to find org_admin by username
        $stmt = $pdo->prepare("SELECT org_admin_id, password_hash FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $inputUsername]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($inputPassword, $user['password_hash'])) {
            // Login successful, set session
            $_SESSION['org_admin_id'] = $user['org_admin_id'];
            // Redirect to dashboard or wherever
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Club Nexus | Login</title>
    <link rel="stylesheet" href="styles/login_css.css" />
    <link rel="stylesheet" href="styles/email_error.css" />
    <link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Outfit", sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-section">
            <div class="logo-section">
                <img src="src/web_logo.png" alt="web logo" class="web-logo" />
            </div>
            <h1>Connecting for a Better Community</h1>

            <form name="form" action="database/login_con.php" method="post">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required />

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />

                <label>User Type</label>
                <select id="user-type" name="user-type" required>
                    <option style="color: rgb(152, 151, 151);" value="" disabled selected>Select user type</option>
                    <option value="reg-user">Regular User</option>
                    <option value="admin">Admin</option>
                    <option value="super-admin">Super Admin</option>
                </select>

                <?php
                if (isset($_GET['error'])) {
                    echo '<p style="color: red; margin-top: 10px;">' . htmlspecialchars($_GET['error']) . '</p>';
                }
                ?>

                <div class="button">
                    <button type="submit" class="login-button" name="submit">LOGIN</button>
                </div>
            </form>

            <p class="footer-text">
                Not registered yet? <a href="signup_form.php">Sign Up</a>
            </p>
        </div>
    </div>
</body>
</html>
