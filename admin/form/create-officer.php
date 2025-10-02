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
    $ac_year = trim($_POST['ac_year'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $mname = trim($_POST['mname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $position = trim($_POST['position'] ?? '');

    // File handling for officer photo
    $uploadDir = __DIR__ . '/../src/officers-photos/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $photoFilename = '';
    $errors = [];

    // Handle photo upload (single file)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoFile = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($photoFile['type'], $allowedTypes) && $photoFile['size'] <= 5 * 1024 * 1024) { // 5MB max
            $ext = pathinfo($photoFile['name'], PATHINFO_EXTENSION);
            $photoFilename = 'photo_' . $org_id . '_' . time() . '.' . strtolower($ext); // Unique filename
            $targetPath = $uploadDir . $photoFilename;
            if (move_uploaded_file($photoFile['tmp_name'], $targetPath)) {
                // Success - filename stored in DB
            } else {
                $errors[] = "Failed to upload photo.";
            }
        } else {
            $errors[] = "Invalid photo: Must be JPEG or PNG (max 5MB).";
        }
    } else if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors[] = "Photo upload error.";
    }

    // Server-side validation
    if (empty($ac_year)) $errors[] = "Academic Year is required.";
    if (empty($fname)) $errors[] = "First Name is required.";
    if (empty($lname)) $errors[] = "Last Name is required.";
    if (!in_array($gender, ['m', 'f'])) $errors[] = "Please select a valid gender.";
    if (empty($position)) $errors[] = "Position is required.";
    if (empty($photoFilename)) $errors[] = "Officer photo is required.";

    if (empty($errors)) {
        // Insert into organization_officers
        $escaped_fname = $mysqli->real_escape_string($fname);
        $escaped_mname = $mysqli->real_escape_string($mname);
        $escaped_lname = $mysqli->real_escape_string($lname);
        $escaped_gender = $mysqli->real_escape_string($gender);
        $escaped_position = $mysqli->real_escape_string($position);
        $escaped_ac_year = $mysqli->real_escape_string($ac_year);
        $escaped_photo = $mysqli->real_escape_string($photoFilename);

        $stmt = $mysqli->prepare("INSERT INTO organization_officers (org_id, officer_fname, officer_mname, officer_lname, gender, officer_photo, position, ac_year) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("isssssss", $org_id, $escaped_fname, $escaped_mname, $escaped_lname, $escaped_gender, $escaped_photo, $escaped_position, $escaped_ac_year);
            if ($stmt->execute()) {
                $message = "Officer added successfully! Photo uploaded: " . $photoFilename;
                $_POST = []; // Clear form
            } else {
                $message = "Database insert failed: " . $stmt->error;
                // If insert failed, delete uploaded photo
                if (file_exists($targetPath)) unlink($targetPath);
            }
            $stmt->close();
        } else {
            $message = "Database prepare failed: " . $mysqli->error;
            if (file_exists($targetPath)) unlink($targetPath);
        }
    } else {
        $message = implode("<br>", $errors);
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Add Officer | <?= htmlspecialchars($org_name) ?></title>
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

    .image-preview-container {
      margin-top: 16px;
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
    }

    .image-preview-wrapper {
      position: relative;
      width: 120px;
      height: 120px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      background-color: #f9fafb;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .image-preview {
      max-width: 100%;
      max-height: 100%;
      object-fit: cover;
      display: block;
    }

    .remove-image-btn {
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

    .remove-image-btn:hover {
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

      <h1 class="card-title">Add Officer</h1>

      <?php if (!empty($message)): ?>
        <div class="<?= strpos($message, 'successfully') !== false ? 'success-message' : 'error-message' ?>" style="display:block;">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <form id="officerForm" method="POST" enctype="multipart/form-data" novalidate>
        <div class="form-group">
          <label for="ac_year" class="form-label">Academic Year <span class="required">*</span></label>
          <input
            type="text"
            id="ac_year"
            name="ac_year"
            class="form-input"
            placeholder="e.g., 2023-2024"
            required
            value="<?= htmlspecialchars($_POST['ac_year'] ?? '') ?>"
          />
          <div id="acYearError" class="error-message">Academic Year is required</div>
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
          <div id="fnameError" class="error-message">First Name is required</div>
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
          <div id="mnameError" class="error-message">Invalid middle name</div>
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
          <div id="lnameError" class="error-message">Last Name is required</div>
        </div>

        <div class="form-group">
          <label for="gender" class="form-label">Gender <span class="required">*</span></label>
          <select id="gender" name="gender" class="form-input" required>
            <option value="">Select Gender</option>
            <option value="m" <?= (isset($_POST['gender']) && $_POST['gender'] === 'm') ? 'selected' : '' ?>>Male</option>
            <option value="f" <?= (isset($_POST['gender']) && $_POST['gender'] === 'f') ? 'selected' : '' ?>>Female</option>
          </select>
          <div id="genderError" class="error-message">Please select a gender</div>
        </div>

        <div class="form-group">
          <label for="position" class="form-label">Position <span class="required">*</span></label>
          <input
            type="text"
            id="position"
            name="position"
            class="form-input"
            placeholder="e.g., President, Vice President"
            required
            value="<?= htmlspecialchars($_POST['position'] ?? '') ?>"
          />
          <div id="positionError" class="error-message">Position is required</div>
        </div>

        <div class="form-group">
          <label for="photo" class="form-label">Officer's Formal Photo <span class="required">*</span></label>
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
              <span class="file-input-text">Click to upload officer's photo</span>
              <span class="file-input-hint">JPEG, PNG, GIF up to 5MB</span>
            </label>
          </div>
          <div id="photoError" class="error-message">Please select a valid photo</div>

          <div id="imagePreviewContainer" class="image-preview-container"></div>
        </div>

        <button type="submit" id="submitBtn" class="submit-btn">Add Officer</button>
      </form>
    </div>
  </div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("officerForm");
        const acYearInput = document.getElementById("ac_year");
        const fnameInput = document.getElementById("fname");
        const mnameInput = document.getElementById("mname");
        const lnameInput = document.getElementById("lname");
        const genderSelect = document.getElementById("gender");
        const positionInput = document.getElementById("position");
        const photoInput = document.getElementById("photo");
        const acYearError = document.getElementById("acYearError");
        const fnameError = document.getElementById("fnameError");
        const mnameError = document.getElementById("mnameError");
        const lnameError = document.getElementById("lnameError");
        const genderError = document.getElementById("genderError");
        const positionError = document.getElementById("positionError");
        const photoError = document.getElementById("photoError");
        const fileInputLabel = document.querySelector(".file-input-label");
        const imagePreviewContainer = document.getElementById("imagePreviewContainer");

        const originalLabelHTML = fileInputLabel.innerHTML;
        let selectedFile = null;

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

        // Academic year basic format check (e.g., "2023-2024")
        function validateAcYear(year) {
            if (!year.trim()) return false;
            const acYearRegex = /^\d{4}-\d{4}$/;
            return acYearRegex.test(year.trim());
        }

        // Photo validation
        function validatePhoto() {
            if (!selectedFile) {
                showError(photoError, "Officer's photo is required");
                return false;
            }
            if (selectedFile.size > 5 * 1024 * 1024) {
                showError(photoError, "Photo must be less than 5MB");
                return false;
            }
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(selectedFile.type)) {
                showError(photoError, "Please select a valid photo type (JPEG, PNG, GIF)");
                return false;
            }
            hideError(photoError);
            return true;
        }

        // Render photo preview
        function renderPreview() {
            imagePreviewContainer.innerHTML = "";
            if (!selectedFile) {
                imagePreviewContainer.style.display = "none";
                fileInputLabel.innerHTML = originalLabelHTML;
                return;
            }
            imagePreviewContainer.style.display = "flex";

            const wrapper = document.createElement("div");
            wrapper.classList.add("image-preview-wrapper");

            if (selectedFile.type.startsWith("image/")) {
                const img = document.createElement("img");
                img.classList.add("image-preview");
                img.alt = "Officer photo preview";

                const reader = new FileReader();
                reader.onload = (e) => {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(selectedFile);

                wrapper.appendChild(img);
            } else {
                // Fallback for non-image (though we validate only images)
                const fileNameDiv = document.createElement("div");
                fileNameDiv.textContent = selectedFile.name;
                fileNameDiv.style.padding = "8px";
                fileNameDiv.style.fontSize = "14px";
                fileNameDiv.style.color = "#374151";
                fileNameDiv.style.textAlign = "center";
                wrapper.appendChild(fileNameDiv);
            }

            const removeBtn = document.createElement("button");
            removeBtn.type = "button";
            removeBtn.classList.add("remove-image-btn");
            removeBtn.innerHTML = "&times;";
            removeBtn.title = "Remove photo";
            removeBtn.addEventListener("click", () => {
                selectedFile = null;
                updateFileInput();
                renderPreview();
                showError(photoError, "Officer's photo is required"); // Show required error after removal
            });

            wrapper.appendChild(removeBtn);
            imagePreviewContainer.appendChild(wrapper);

            // Update label with filename
            fileInputLabel.innerHTML = `
                <span class="file-input-icon">📷</span>
                <span class="file-input-text">Selected: ${selectedFile.name}</span>
                <span class="file-input-hint">Click to change (JPEG, PNG, GIF up to 5MB)</span>
            `;
        }

        // Update hidden file input
        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            if (selectedFile) {
                dataTransfer.items.add(selectedFile);
            }
            photoInput.files = dataTransfer.files;
        }

        // Photo input change event
        photoInput.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (!file) {
                selectedFile = null;
                renderPreview();
                return;
            }

            // Validate immediately
            if (file.size > 5 * 1024 * 1024) {
                showError(photoError, "Photo must be less than 5MB");
                selectedFile = null;
                updateFileInput();
                e.target.value = ''; // Clear input
                return;
            }

            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(file.type)) {
                showError(photoError, "Please select a valid photo type (JPEG, PNG, GIF)");
                selectedFile = null;
                updateFileInput();
                e.target.value = ''; // Clear input
                return;
            }

            hideError(photoError);
            selectedFile = file;
            updateFileInput();
            renderPreview();
        });

        // Real-time validation for text inputs (hide errors as user types)
        const requiredInputs = [acYearInput, fnameInput, lnameInput, positionInput];
        requiredInputs.forEach(input => {
            input.addEventListener("input", function () {
                const error = this.parentNode.querySelector(".error-message");
                if (this.value.trim()) {
                    hideError(error);
                }
            });
        });

        mnameInput.addEventListener("input", function () {
            const error = mnameError;
            if (validateName(this.value, false)) {
                hideError(error);
            }
        });

        // Gender select change
        genderSelect.addEventListener("change", function () {
            const error = genderError;
            if (this.value === 'm' || this.value === 'f') {
                hideError(error);
            } else {
                showError(error, "Please select Male or Female");
            }
        });

        // Form submission validation
        form.addEventListener("submit", function (e) {
            let isValid = true;

            // Reset all errors first
            const allErrors = form.querySelectorAll(".error-message");
            allErrors.forEach(error => hideError(error));

            // Validate required text fields
            if (!acYearInput.value.trim() || !validateAcYear(acYearInput.value)) {
                showError(acYearError, "Please enter a valid Academic Year (e.g., 2023-2024)");
                isValid = false;
            }

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

            if (genderSelect.value !== 'm' && genderSelect.value !== 'f') {
                showError(genderError, "Please select Male or Female");
                isValid = false;
            }

            if (!positionInput.value.trim() || !validateName(positionInput.value, true)) {
                showError(positionError, "Please enter a valid Position (letters only)");
                isValid = false;
            }

            // Validate photo
            if (!validatePhoto()) {
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