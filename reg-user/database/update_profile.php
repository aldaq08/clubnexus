<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: login_form.php");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $pdo = require __DIR__ . '\db_connection.php';

    $firstName = $_POST['firstName'] ?? '';
    $middleName = $_POST['middleName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $messenger = $_POST['messenger'] ?? '';
    $phPhone = $_POST['phPhone'] ?? '';


    $password = $_POST['password'] ?? '';
    $hashedPassword = null;
    if ($password && strlen($password) >= 6) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    $userPhotoPath = null;
    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../src/userprofile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileTmpPath = $_FILES['user_photo']['tmp_name'];
        $fileName = basename($_FILES['user_photo']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileNameSanitized = preg_replace("/[^a-zA-Z0-9_-]/", "", pathinfo($fileName, PATHINFO_FILENAME));
        $newFileName = $fileNameSanitized . '_' . uniqid() . '.' . $fileExt;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $userPhotoPath = $newFileName;
        }
    }

    $sql = "UPDATE users SET first_name = ?, middle_name = ?, last_name = ?, gender = ?, email = ?, messenger = ?, phPhone = ?";
    $params = [$firstName, $middleName, $lastName, $gender, $email, $messenger, $phPhone];

    if ($userPhotoPath) {
        $sql .= ", user_photo = ?";
        $params[] = $userPhotoPath;
    }

    if ($hashedPassword) {
        $sql .= ", user_password = ?";
        $params[] = $hashedPassword;
    }

    $sql .= " WHERE user_id = ?";
    $params[] = $userId;

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute($params)) {
        $_SESSION['status'] = "success";
        $_SESSION['message'] = "Profile updated successfully!";
    } else {
        $_SESSION['status'] = "error";
        $_SESSION['message'] = "Failed to update profile.";
    }

    header("Location: ../forms/userprofile.php");
    exit();
?>
