<?php
session_start();

include("../database/db_connection.php");

// Connect to DB
$mysqli = new mysqli($server_name, $user_name, $password, $db_name);
if ($mysqli->connect_error) {
    die("Connection error: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Check session for org_id and user_id
if (empty($_SESSION['org_id']) || empty($_SESSION['user_id'])) {
    die("Unauthorized: Missing organization or user session.");
}

$org_id = $_SESSION['org_id'];
$user_id = $_SESSION['user_id'];
$message = "";

$org_logo_url = "";
$org_name = "";
if (!empty($_SESSION['org_id'])) {
    $stmtOrg = $mysqli->prepare("SELECT org_logo, org_name FROM organizations WHERE org_id = ?");
    if ($stmtOrg) {
        $stmtOrg->bind_param("i", $org_id);
        $stmtOrg->execute();
        $stmtOrg->bind_result($logo, $name);
        if ($stmtOrg->fetch()) {
            $org_logo_url = $logo;
            $org_name = $name;
        }
        $stmtOrg->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize form data
    $user_id_form = intval(trim($_POST['user_id'] ?? '0'));
    $fname = trim($_POST['fname'] ?? '');
    $mname = trim($_POST['mname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $position = trim($_POST['position'] ?? 'Member'); // Default to 'Member' if not provided

    // File handling for member photo and RF
    $uploadDir = __DIR__ . '/../../reg-user/src/membership/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $photoFilename = '';
    $rfFilename = '';
    $errors = [];
    $targetPathPhoto = '';
    $targetPathRf = '';

    // Handle photo upload (single file)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoFile = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($photoFile['type'], $allowedTypes) && $photoFile['size'] <= 5 * 1024 * 1024) { // 5MB max
            $ext = pathinfo($photoFile['name'], PATHINFO_EXTENSION);
            $photoFilename = 'photo_' . $org_id . '_' . $user_id_form . '_' . time() . '.' . strtolower($ext); // Unique filename
            $targetPathPhoto = $uploadDir . $photoFilename;
            if (move_uploaded_file($photoFile['tmp_name'], $targetPathPhoto)) {
                // Success - filename stored in DB
            } else {
                $errors[] = "Failed to upload photo.";
            }
        } else {
            $errors[] = "Invalid photo: Must be JPEG, PNG, or GIF (max 5MB).";
        }
    } else if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "Photo upload error.";
    }

    // Handle RF file upload (single file, any type but with size limit)
    if (isset($_FILES['rf']) && $_FILES['rf']['error'] === UPLOAD_ERR_OK) {
        $rfFile = $_FILES['rf'];
        if ($rfFile['size'] <= 5 * 1024 * 1024) { // 5MB max
            $ext = pathinfo($rfFile['name'], PATHINFO_EXTENSION);
            $rfFilename = 'rf_' . $org_id . '_' . $user_id_form . '_' . time() . '.' . strtolower($ext); // Unique filename
            $targetPathRf = $uploadDir . $rfFilename;
            if (move_uploaded_file($rfFile['tmp_name'], $targetPathRf)) {
                // Success - filename stored in DB
            } else {
                $errors[] = "Failed to upload RF file.";
            }
        } else {
            $errors[] = "RF file too large (max 5MB).";
        }
    } else if ($_FILES['rf']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "RF file upload error.";
    }

    // Server-side validation
    if ($user_id_form <= 0) $errors[] = "Student ID is required and must be a positive integer.";
    if (empty($fname)) $errors[] = "First Name is required.";
    if (empty($lname)) $errors[] = "Last Name is required.";
    if (empty($course)) $errors[] = "Course is required.";
    if (empty($position)) $errors[] = "Position is required.";
    if (empty($photoFilename)) $errors[] = "Formal Photo is required.";
    if (empty($rfFilename)) $errors[] = "RF file is required.";

    if (empty($errors)) {
        // Insert into membership table
        $escaped_fname = $mysqli->real_escape_string($fname);
        $escaped_mname = $mysqli->real_escape_string($mname);
        $escaped_lname = $mysqli->real_escape_string($lname);
        $escaped_course = $mysqli->real_escape_string($course);
        $escaped_position = $mysqli->real_escape_string($position);
        $escaped_photo = $mysqli->real_escape_string($photoFilename);
        $escaped_rf = $mysqli->real_escape_string($rfFilename);

        $stmt = $mysqli->prepare("INSERT INTO memberships (org_id, user_id, first_name, middle_name, last_name, course, member_position, photo, rf,status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'approve')");
        if ($stmt) {
            $stmt->bind_param("iisssssss", $org_id, $user_id_form, $escaped_fname, $escaped_mname, $escaped_lname, $escaped_course, $escaped_position, $escaped_photo, $escaped_rf);
            if ($stmt->execute()) {
                $message = "Member added successfully! Photo uploaded: " . $photoFilename . ", RF: " . $rfFilename;
                $_POST = []; // Clear form
            } else {
                $message = "Database insert failed: " . $stmt->error;
                // If insert failed, delete uploaded files
                if (!empty($photoFilename) && file_exists($targetPathPhoto)) unlink($targetPathPhoto);
                if (!empty($rfFilename) && file_exists($targetPathRf)) unlink($targetPathRf);
            }
            $stmt->close();
        } else {
            $message = "Database prepare failed: " . $mysqli->error;
            // If prepare failed, delete uploaded files
            if (!empty($photoFilename) && file_exists($targetPathPhoto)) unlink($targetPathPhoto);
            if (!empty($rfFilename) && file_exists($targetPathRf)) unlink($targetPathRf);
        }
    } else {
        $message = implode("<br>", $errors);
        // If validation failed, delete uploaded files
        if (!empty($photoFilename) && file_exists($targetPathPhoto)) unlink($targetPathPhoto);
        if (!empty($rfFilename) && file_exists($targetPathRf)) unlink($targetPathRf);
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Member | <?= htmlspecialchars($org_name) ?></title>
  <link rel="icon" type="image/x-icon" href="../src/clubnexusicon.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&family=Outfit:wght@400;500&display=swap"
    rel="stylesheet"
  />
  <style>
    /* Base styles (adapted from announcement code) */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      line-height: 1.6;
      color: #404040;
      background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
      padding: 20px;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      width: 100%;
      max-width: 680px;
      margin: 0 auto;
    }

    .card {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      padding: 32px;
    }

    .org-logo-container {
      text-align: center;
      margin-bottom: 12px;
    }

    .org-logo {
      max-height: 80px;
      max-width: 180px;
      object-fit: contain;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .org-name {
      text-align: center;
      font-family: "Fredoka", cursive, sans-serif;
      font-size: 22px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 24px;
    }

    .divider {
      margin: 16px 0;
      border: none;
      border-top: 1px solid #d1d5db;
    }

    .card-title {
      font-family: "Fredoka", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      font-size: 28px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 24px;
      text-align: center;
      letter-spacing: -0.5px;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 24px;
    }

    .form-label {
      display: block;
      font-weight: 500;
      margin-bottom: 8px;
      color: #374151;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-input,
    .form-textarea,
    select {
      width: 100%;
      padding: 12px 16px;
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.15s ease;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-input:focus,
    .form-textarea:focus,
    select:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
      min-height: 120px;
      resize: vertical;
    }

    select {
      background-color: white;
      cursor: pointer;
    }

    .file-input-container {
      position: relative;
      margin-bottom: 16px;
    }

    .file-input {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    .file-input-label {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 24px;
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      background-color: #f9fafb;
      text-align: center;
      cursor: pointer;
      transition: all 0.2s ease;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .file-input-label:hover {
      border-color: #3b82f6;
      background-color: #f0f7ff;
    }

    .file-input-icon {
      font-size: 32px;
      color: #9ca3af;
      margin-bottom: 8px;
    }

    .file-input-label:hover .file-input-icon {
      color: #3b82f6;
    }

    .file-input-text {
      color: #6b7280;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .file-input-hint {
      font-size: 14px;
      color: #9ca3af;
      margin-top: 4px;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .file-preview-container {
      margin-top: 16px;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }

    .file-preview-wrapper {
      position: relative;
      width: 120px;
      height: 120px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      background-color: #f9fafb;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 8px;
      text-align: center;
    }

    .image-preview {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
      display: block;
    }

    .file-preview-text {
      font-size: 12px;
      color: #374151;
      word-break: break-word;
      max-width: 100%;
      text-align: center;
    }

    .remove-file-btn {
      position: absolute;
      top: 4px;
      right: 4px;
      background-color: #fee2e2;
      color: #b91c1c;
      border: none;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      cursor: pointer;
      font-size: 16px;
      line-height: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: background-color 0.2s ease;
    }

    .remove-file-btn:hover {
      background-color: #fecaca;
    }

    .submit-btn {
      width: 100%;
      padding: 12px 20px;
      background-color: #3b82f6;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s ease;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .submit-btn:hover {
      background-color: #2563eb;
    }

    .submit-btn:disabled {
      background-color: #9ca3af;
      cursor: not-allowed;
    }

    .error-message {
      color: #dc2626;
      font-size: 14px;
      margin-top: 4px;
      display: none;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .success-message {
      background-color: #dcfce7;
      color: #166534;
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 24px;
      text-align: center;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .required {
      color: #dc2626;
    }

    /* Responsive */
    @media (max-width: 640px) {
      .card {
        padding: 24px;
      }

      .card-title {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">

      <?php if ($org_logo_url): ?>
        <div class="org-logo-container">
          <img src="../../reg-user/src/org-logo/<?=htmlspecialchars($org_logo_url) ?>" alt="Organization Logo" class="org-logo" />
        </div>
      <?php endif; ?>

      <?php if ($org_name): ?>
        <div class="org-name">
          <?= htmlspecialchars($org_name) ?>
        </div>
        <hr style="margin: 16px 0; border: none; border-top: 1px solid #d1d5db;" />
      <?php endif; ?>

      <h1 class="card-title">Add Member</h1>

      <?php if (!empty($message)): ?>
        <div class="<?= strpos($message, 'successfully') !== false ? 'success-message' : 'error-message' ?>" style="display:block;">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <form id="memberForm" method="POST" enctype="multipart/form-data" novalidate>
        <div class="form-group">
          <label for="user_id" class="form-label">Student ID <span class="required">*</span></label>
          <input
            type="number"
            id="user_id"
            name="user_id"
            class="form-input"
            min="1"
            step="1"
            placeholder="Enter Student ID"
            required
            value="<?= htmlspecialchars($_POST['user_id'] ?? '') ?>"
          />
          <div id="userIdError" class="error-message">Student ID is required and must be a positive integer.</div>
        </div>

        <div class="form-group">
          <label for="fname" class="form-label">First Name <span class="required">*</span></label>
          <input
            type="text"
            id="fname"
            name="fname"
            class="form-input"
            placeholder="Enter first name"
            required
            value="<?= htmlspecialchars($_POST['fname'] ?? '') ?>"
          />
          <div id="fnameError" class="error-message">First Name is required.</div>
        </div>

        <div class="form-group">
          <label for="mname" class="form-label">Middle Name (Optional)</label>
          <input
            type="text"
            id="mname"
            name="mname"
            class="form-input"
            placeholder="Enter middle name (if any)"
            value="<?= htmlspecialchars($_POST['mname'] ?? '') ?>"
          />
          <div id="mnameError" class="error-message">Invalid middle name.</div>
        </div>

        <div class="form-group">
          <label for="lname" class="form-label">Last Name <span class="required">*</span></label>
          <input
            type="text"
            id="lname"
            name="lname"
            class="form-input"
            placeholder="Enter last name"
            required
            value="<?= htmlspecialchars($_POST['lname'] ?? '') ?>"
          />
          <div id="lnameError" class="error-message">Last Name is required.</div>
        </div>

        <div class="form-group">
          <label for="course" class="form-label">Course <span class="required">*</span></label>
          <input
            type="text"
            id="course"
            name="course"
            class="form-input"
            placeholder="Enter course (e.g., BS Computer Science)"
            required
            value="<?= htmlspecialchars($_POST['course'] ?? '') ?>"
          />
          <div id="courseError" class="error-message">Course is required.</div>
        </div>

        <div class="form-group">
          <label for="position" class="form-label">Position <span class="required">*</span></label>
          <input
            type="text"
            id="position"
            name="position"
            class="form-input"
            placeholder="e.g., Member, Officer"
            required
            value="<?= htmlspecialchars($_POST['position'] ?? 'Member') ?>"
          />
          <div id="positionError" class="error-message">Position is required.</div>
        </div>

        <div class="form-group">
          <label for="photo" class="form-label">Formal Photo <span class="required">*</span></label>
          <div class="file-input-container">
            <input
              type="file"
              id="photo"
              name="photo"
              class="file-input"
              accept="image/jpeg,image/png,image/gif"
              required
            />
            <label for="photo" class="file-input-label">
              <span class="file-input-icon">📷</span>
              <span class="file-input-text">Click to upload formal photo</span>
              <span class="file-input-hint">JPEG, PNG, GIF up to 5MB</span>
            </label>
          </div>
          <div id="photoError" class="error-message">Please select a valid formal photo.</div>

          <div id="photoPreviewContainer" class="file-preview-container"></div>
        </div>

        <div class="form-group">
          <label for="rf" class="form-label">RF File <span class="required">*</span></label>
          <div class="file-input-container">
            <input
              type="file"
              id="rf"
              name="rf"
              class="file-input"
              required
            />
            <label for="rf" class="file-input-label">
              <span class="file-input-icon">📄</span>
              <span class="file-input-text">Click to upload RF file</span>
              <span class="file-input-hint">Any file up to 5MB</span>
            </label>
          </div>
          <div id="rfError" class="error-message">Please select a valid RF file.</div>

          <div id="rfPreviewContainer" class="file-preview-container"></div>
        </div>

        <button type="submit" id="submitBtn" class="submit-btn">Add Member</button>
      </form>
    </div>
  </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("memberForm");
        const userIdInput = document.getElementById("user_id");
        const fnameInput = document.getElementById("fname");
        const mnameInput = document.getElementById("mname");
        const lnameInput = document.getElementById("lname");
        const courseInput = document.getElementById("course");
        const positionInput = document.getElementById("position");
        const photoInput = document.getElementById("photo");
        const rfInput = document.getElementById("rf");
        const userIdError = document.getElementById("userIdError");
        const fnameError = document.getElementById("fnameError");
        const mnameError = document.getElementById("mnameError");
        const lnameError = document.getElementById("lnameError");
        const courseError = document.getElementById("courseError");
        const positionError = document.getElementById("positionError");
        const photoError = document.getElementById("photoError");
        const rfError = document.getElementById("rfError");
        const photoLabel = document.querySelector('label[for="photo"]');
        const rfLabel = document.querySelector('label[for="rf"]');
        const photoPreviewContainer = document.getElementById("photoPreviewContainer");
        const rfPreviewContainer = document.getElementById("rfPreviewContainer");

        const originalPhotoLabelHTML = photoLabel.innerHTML;
        const originalRfLabelHTML = rfLabel.innerHTML;
        let selectedPhotoFile = null;
        let selectedRfFile = null;

        // Helper functions
        function showError(element, message) {
            element.textContent = message;
            element.style.display = "block";
        }

        function hideError(element) {
            element.style.display = "none";
        }

        // Basic name validation (letters, spaces, hyphens; optional for mname)
        function validateName(name, isRequired = false) {
            if (isRequired && !name.trim()) return false;
            if (!name.trim()) return true; // Optional empty is OK
            const nameRegex = /^[a-zA-Z\s\-']+$/;
            return nameRegex.test(name.trim());
        }

        // Course validation (similar to name, but allows more characters if needed)
        function validateCourse(course) {
            if (!course.trim()) return false;
            const courseRegex = /^[a-zA-Z\s\-.,()]+$/; // Allows common course name characters
            return courseRegex.test(course.trim());
        }

        // Student ID validation (FIXED: Removed space in function name)
        function validateUser Id(id) {
            const num = parseInt(id);
            return num > 0 && !isNaN(num);
        }

        // Photo validation
        function validatePhoto() {
            if (!selectedPhotoFile) {
                showError(photoError, "Formal Photo is required");
                return false;
            }
            if (selectedPhotoFile.size > 5 * 1024 * 1024) {
                showError(photoError, "Photo must be less than 5MB");
                return false;
            }
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(selectedPhotoFile.type)) {
                showError(photoError, "Please select a valid photo type (JPEG, PNG, GIF)");
                return false;
            }
            hideError(photoError);
            return true;
        }

        // RF validation
        function validateRf() {
            if (!selectedRfFile) {
                showError(rfError, "RF File is required");
                return false;
            }
            if (selectedRfFile.size > 5 * 1024 * 1024) {
                showError(rfError, "RF file must be less than 5MB");
                return false;
            }
            hideError(rfError);
            return true;
        }

        // Render photo preview
        function renderPhotoPreview() {
            photoPreviewContainer.innerHTML = "";
            if (!selectedPhotoFile) {
                photoPreviewContainer.style.display = "none";
                photoLabel.innerHTML = originalPhotoLabelHTML;
                return;
            }
            photoPreviewContainer.style.display = "flex";

            const wrapper = document.createElement("div");
            wrapper.classList.add("file-preview-wrapper");

            if (selectedPhotoFile.type.startsWith("image/")) {
                const img = document.createElement("img");
                img.classList.add("image-preview");
                img.alt = "Photo preview";

                const reader = new FileReader();
                reader.onload = (e) => {
                    if (e.target.result) {
                        img.src = e.target.result;
                    } else {
                        console.error("Failed to load image preview");
                        // Fallback to file name if image load fails
                        const fallbackDiv = document.createElement("div");
                        fallbackDiv.classList.add("file-preview-text");
                        fallbackDiv.textContent = selectedPhotoFile.name;
                        wrapper.appendChild(fallbackDiv);
                    }
                };
                reader.onerror = () => {
                    console.error("FileReader error for photo preview");
                    // Fallback
                    const fallbackDiv = document.createElement("div");
                    fallbackDiv.classList.add("file-preview-text");
                    fallbackDiv.textContent = selectedPhotoFile.name;
                    wrapper.appendChild(fallbackDiv);
                };
                reader.readAsDataURL(selectedPhotoFile);

                wrapper.appendChild(img);
            } else {
                // Fallback (though we only allow images)
                const fileNameDiv = document.createElement("div");
                fileNameDiv.classList.add("file-preview-text");
                fileNameDiv.textContent = selectedPhotoFile.name;
                wrapper.appendChild(fileNameDiv);
            }

            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.classList.add("remove-file-btn");
            removeBtn.innerHTML = "&times;";
            removeBtn.title = "Remove photo";
            removeBtn.addEventListener("click", () => {
                selectedPhotoFile = null;
                updatePhotoFileInput();
                renderPhotoPreview();
                showError(photoError, "Formal Photo is required"); // Show required error after removal
            });

            wrapper.appendChild(removeBtn);
            photoPreviewContainer.appendChild(wrapper);

            // Update label with filename
            photoLabel.innerHTML = `
                <span class="file-input-icon">📷</span>
                <span class="file-input-text">Selected: ${selectedPhotoFile.name}</span>
                <span class="file-input-hint">Click to change (JPEG, PNG, GIF up to 5MB)</span>
            `;
        }

        // Render RF preview (file name only)
        function renderRfPreview() {
            rfPreviewContainer.innerHTML = "";
            if (!selectedRfFile) {
                rfPreviewContainer.style.display = "none";
                rfLabel.innerHTML = originalRfLabelHTML;
                return;
            }
            rfPreviewContainer.style.display = "flex";

            const wrapper = document.createElement("div");
            wrapper.classList.add("file-preview-wrapper");

            const fileIcon = document.createElement("span");
            fileIcon.classList.add("file-input-icon");
            fileIcon.textContent = "📄";
            wrapper.appendChild(fileIcon);

            const fileNameDiv = document.createElement("div");
            fileNameDiv.classList.add("file-preview-text");
            fileNameDiv.textContent = selectedRfFile.name;
            wrapper.appendChild(fileNameDiv);

            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.classList.add("remove-file-btn");
            removeBtn.innerHTML = "&times;";
            removeBtn.title = "Remove RF file";
            removeBtn.addEventListener("click", () => {
                selectedRfFile = null;
                updateRfFileInput();
                renderRfPreview();
                showError(rfError, "RF File is required"); // Show required error after removal
            });

            wrapper.appendChild(removeBtn);
            rfPreviewContainer.appendChild(wrapper);

            // Update label with filename
            rfLabel.innerHTML = `
                <span class="file-input-icon">📄</span>
                <span class="file-input-text">Selected: ${selectedRfFile.name}</span>
                <span class="file-input-hint">Click to change (Any file up to 5MB)</span>
            `;
        }

        // Update hidden file input for photo
        function updatePhotoFileInput() {
            const dataTransfer = new DataTransfer();
            if (selectedPhotoFile) {
                dataTransfer.items.add(selectedPhotoFile);
            }
            photoInput.files = dataTransfer.files;
        }

        // Update hidden file input for RF
        function updateRfFileInput() {
            const dataTransfer = new DataTransfer();
            if (selectedRfFile) {
                dataTransfer.items.add(selectedRfFile);
            }
            rfInput.files = dataTransfer.files;
        }

        // Photo input change event
        photoInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) {
                selectedPhotoFile = null;
                renderPhotoPreview();
                return;
            }

            // Validate immediately
            if (file.size > 5 * 1024 * 1024) {
                showError(photoError, "Photo must be less than 5MB");
                selectedPhotoFile = null;
                updatePhotoFileInput();
                e.target.value = ''; // Clear input
                return;
            }

            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(file.type)) {
                showError(photoError, "Please select a valid photo type (JPEG, PNG, GIF)");
                selectedPhotoFile = null;
                updatePhotoFileInput();
                e.target.value = ''; // Clear input
                return;
            }

            hideError(photoError);
            selectedPhotoFile = file;
            updatePhotoFileInput();
            renderPhotoPreview();
        });

        // RF input change event
        rfInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) {
                selectedRfFile = null;
                renderRfPreview();
                return;
            }

            // Validate immediately
            if (file.size > 5 * 1024 * 1024) {
                showError(rfError, "RF file must be less than 5MB");
                selectedRfFile = null;
                updateRfFileInput();
                e.target.value = ''; // Clear input
                return;
            }

            hideError(rfError);
            selectedRfFile = file;
            updateRfFileInput();
            renderRfPreview();
        });

        // Real-time validation for text inputs (hide errors as user types)
        const requiredTextInputs = [fnameInput, lnameInput, courseInput, positionInput];
        requiredTextInputs.forEach(input => {
            input.addEventListener("input", function () {
                const error = this.parentNode.querySelector(".error-message");
                if (this.value.trim()) {
                    hideError(error);
                }
            });
        });

        userIdInput.addEventListener("input", function () {
            const error = userIdError;
            if (validateUser Id(this.value)) {  // FIXED: No space in function name
                hideError(error);
            }
        });

        mnameInput.addEventListener("input", function () {
            const error = mnameError;
            if (validateName(this.value, false)) {
                hideError(error);
            }
        });

        // Form submission validation
        form.addEventListener("submit", function (e) {
            let isValid = true;

            // Reset all errors first
            const allErrors = form.querySelectorAll(".error-message");
            allErrors.forEach(error => hideError(error));

            // Validate Student ID (FIXED: No space in function name)
            if (!validateUser Id(userIdInput.value)) {
                showError(userIdError, "Student ID is required and must be a positive integer.");
                isValid = false;
            }

            // Validate names
            if (!validateName(fnameInput.value, true)) {
                showError(fnameError, "Please enter a valid First Name (letters only)");
                isValid = false;
            }

            if (!validateName(mnameInput.value, false)) {
                showError(mnameError, "Please enter a valid Middle Name (letters only, optional)");
                isValid = false;
            }

            if (!validateName(lnameInput.value, true)) {
                showError(lnameError, "Please enter a valid Last Name (letters only)");
                isValid = false;
            }

            // Validate course
            if (!validateCourse(courseInput.value)) {
                showError(courseError, "Please enter a valid Course");
                isValid = false;
            }

            // Validate position
            if (!validateName(positionInput.value, true)) {
                showError(positionError, "Please enter a valid Position (letters only)");
                isValid = false;
            }

            // Validate files
            if (!validatePhoto()) {
                isValid = false;
            }

            if (!validateRf()) {
                isValid = false;
            }

            // If invalid, prevent submission and scroll to first error
            if (!isValid) {
                e.preventDefault();
                const firstError = form.querySelector(".error-message[style*='block']");
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.parentNode.querySelector('input, select').focus(); // Focus on the field
                }
                alert('Please fix the errors above before submitting.'); // Optional alert
            }
        });
    });
</script>

</body>
</html>