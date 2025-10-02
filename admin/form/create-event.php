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
$user_id = $_SESSION['user_id'];  // Not inserted into DB, but used for auth
$message = "";

$org_logo_url = "";
$org_name = "";
if (!empty($_SESSION['org_id'])) {
    $org_id = $_SESSION['org_id'];
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
    $event_name = trim($_POST['event_name'] ?? '');
    $event_description = trim($_POST['event_description'] ?? '');
    $event_date = trim($_POST['event_date'] ?? '');
    $event_time = trim($_POST['event_time'] ?? '');
    $event_location = trim($_POST['event_location'] ?? '');

    // Validation for required fields
    $errors = [];
    if (empty($event_name)) {
        $errors[] = "Event Name cannot be empty.";
    }
    if (empty($event_date)) {
        $errors[] = "Event Date cannot be empty.";
    }
    if (empty($event_time)) {
        $errors[] = "Event Time cannot be empty.";
    }
    if (empty($event_location)) {
        $errors[] = "Event Venue/Location cannot be empty.";
    }
    if (empty($_FILES['approval_letter']['name'])) {
        $errors[] = "Approval Letter is required.";
    }

    if (!empty($errors)) {
        $message = implode("<br>", $errors);
    } else {
        // Handle Event Poster upload (optional)
        $poster_filename = null;
        $poster_dir = __DIR__ . '/src/event/posters/';
        if (!is_dir($poster_dir)) {
            mkdir($poster_dir, 0755, true);
        }
        if (!empty($_FILES['event_poster']['name'])) {
            $fileName = basename($_FILES['event_poster']['name']);
            $fileTmpPath = $_FILES['event_poster']['tmp_name'];
            $fileSize = $_FILES['event_poster']['size'];
            $fileError = $_FILES['event_poster']['error'];

            // Sanitize filename
            $fileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $fileName);
            $targetFilePath = $poster_dir . $fileName;

            if ($fileError === UPLOAD_ERR_OK) {
                if ($fileSize <= 5 * 1024 * 1024) { // 5MB max
                    $allowed_poster_types = ['image/png', 'image/jpeg', 'image/gif'];
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime_type = finfo_file($finfo, $fileTmpPath);
                    finfo_close($finfo);
                    if (in_array($mime_type, $allowed_poster_types)) {
                        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                            $poster_filename = $fileName;
                        } else {
                            $errors[] = "Failed to upload Event Poster.";
                        }
                    } else {
                        $errors[] = "Event Poster must be PNG, JPG, or GIF.";
                    }
                } else {
                    $errors[] = "Event Poster too large (max 5MB).";
                }
            } else {
                $errors[] = "Error uploading Event Poster.";
            }
        }

        // Handle Approval Letter upload (required)
        $letter_filename = null;
        $letter_dir = __DIR__ . '/src/event/approval-letters/';
        if (!is_dir($letter_dir)) {
            mkdir($letter_dir, 0755, true);
        }
        $fileName = basename($_FILES['approval_letter']['name']);
        $fileTmpPath = $_FILES['approval_letter']['tmp_name'];
        $fileSize = $_FILES['approval_letter']['size'];
        $fileError = $_FILES['approval_letter']['error'];

        // Sanitize filename
        $fileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $fileName);
        $targetFilePath = $letter_dir . $fileName;

        if ($fileError === UPLOAD_ERR_OK) {
            if ($fileSize <= 5 * 1024 * 1024) { // 5MB max
                $allowed_letter_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $fileTmpPath);
                finfo_close($finfo);
                if (in_array($mime_type, $allowed_letter_types)) {
                    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                        $letter_filename = $fileName;
                    } else {
                        $errors[] = "Failed to upload Approval Letter.";
                    }
                } else {
                    $errors[] = "Approval Letter must be PDF or DOC.";
                }
            } else {
                $errors[] = "Approval Letter too large (max 5MB).";
            }
        } else {
            $errors[] = "Error uploading Approval Letter.";
        }

        if (empty($errors)) {
            // Prepare DB insert (handle optional description as NULL if empty)
            $event_desc_escaped = !empty($event_description) ? $mysqli->real_escape_string($event_description) : null;
            $poster_escaped = $poster_filename ? $mysqli->real_escape_string($poster_filename) : null;
            $letter_escaped = $mysqli->real_escape_string($letter_filename);

            $stmt = $mysqli->prepare("INSERT INTO events (org_id, user_id, event_name, event_description, event_date, event_time, event_location, event_poster, approval_letter, is_approve ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '2')");
            if ($stmt) {
                $stmt->bind_param("iisssssss", $org_id, $user_id, $event_name, $event_desc_escaped, $event_date, $event_time, $event_location, $poster_escaped, $letter_escaped);
                if ($stmt->execute()) {
                    $message = "Event created successfully.";
                    if ($poster_filename) {
                        $message .= " <br> Poster uploaded: " . $poster_filename;
                    }
                    $message .= "<br> Approval letter uploaded: " . $letter_filename;
                    $_POST = [];  // Clear form
                } else {
                    $message = "Database insert failed: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Database prepare failed: " . $mysqli->error;
            }
        } else {
            $message = implode("<br>", $errors);
        }
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Event | <?= htmlspecialchars($org_name) ?></title>
  <link rel="icon" type="image/c-icon" href="../src/clubnexusicon.ico">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600&family=Outfit:wght@400;500&display=swap"
    rel="stylesheet"
  />
  <style>
    /* Base styles */
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
    .form-date,
    .form-time {
      width: 100%;
      padding: 12px 16px;
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.15s ease;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-date,
    .form-time {
      background-color: white;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-date:focus,
    .form-time:focus {
      outline: none;
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-textarea {
      min-height: 120px;
      resize: vertical;
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

    .file-preview-text {
      padding: 8px;
      font-size: 14px;
      color: #374151;
      text-align: center;
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
          <img src="../../reg-user/src/org-logo/<?= htmlspecialchars($org_logo_url) ?>" alt="Organization Logo" class="org-logo" />
        </div>
      <?php endif; ?>

      <?php if ($org_name): ?>
        <div class="org-name">
          <?= htmlspecialchars($org_name) ?>
        </div>
        <hr style="margin: 16px 0; border: none; border-top: 1px solid #d1d5db;" />
      <?php endif; ?>

      <h1 class="card-title">Create Event</h1>

      <?php if (!empty($message)): ?>
        <div class="<?= strpos($message, 'successfully') !== false ? 'success-message' : 'error-message' ?>" style="display: block;">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <form id="eventsForm" method="POST" enctype="multipart/form-data" novalidate>
        <!-- Event Name -->
        <div class="form-group">
          <label for="event_name" class="form-label">Event Name <span style="color: #dc2626;">*</span></label>
          <input
            type="text"
            id="event_name"
            name="event_name"
            class="form-input"
            placeholder="Enter event name"
            required
            value="<?= htmlspecialchars($_POST['event_name'] ?? '') ?>"
          />
          <div id="eventNameError" class="error-message">Event Name cannot be empty.</div>
        </div>

        <!-- Event Description (Optional) -->
        <div class="form-group">
          <label for="event_description" class="form-label">Event Description</label>
          <textarea
            id="event_description"
            name="event_description"
            class="form-textarea"
            placeholder="Enter event description (optional)"
          ><?= htmlspecialchars($_POST['event_description'] ?? '') ?></textarea>
          <div id="eventDescriptionError" class="error-message">Event Description is optional.</div>
        </div>

        <!-- Event Date -->
        <div class="form-group">
          <label for="event_date" class="form-label">Event Date <span style="color: #dc2626;">*</span></label>
          <input
            type="date"
            id="event_date"
            name="event_date"
            class="form-date"
            required
            value="<?= htmlspecialchars($_POST['event_date'] ?? '') ?>"
          />
          <div id="eventDateError" class="error-message">Event Date cannot be empty.</div>
        </div>

        <!-- Event Time -->
        <div class="form-group">
          <label for="event_time" class="form-label">Event Time <span style="color: #dc2626;">*</span></label>
          <input
            type="time"
            id="event_time"
            name="event_time"
            class="form-time"
            required
            value="<?= htmlspecialchars($_POST['event_time'] ?? '') ?>"
          />
          <div id="eventTimeError" class="error-message">Event Time cannot be empty.</div>
        </div>

        <!-- Event Venue/Location -->
        <div class="form-group">
          <label for="event_location" class="form-label">Event Venue/Location <span style="color: #dc2626;">*</span></label>
          <input
            type="text"
            id="event_location"
            name="event_location"
            class="form-input"
            placeholder="Enter event venue or location"
            required
            value="<?= htmlspecialchars($_POST['event_location'] ?? '') ?>"
          />
          <div id="eventLocationError" class="error-message">Event Venue/Location cannot be empty.</div>
        </div>

        <!-- Event Poster (Optional) -->
        <div class="form-group">
          <label class="form-label">Event Poster (Optional)</label>
          <div class="file-input-container">
            <input
              type="file"
              id="event_poster"
              name="event_poster"
              class="file-input"
              accept="image/png,image/jpeg,image/gif"
            />
            <label for="event_poster" class="file-input-label">
              <span class="file-input-icon">🖼️</span>
              <span class="file-input-text">Click to upload event poster</span>
              <span class="file-input-hint">PNG, JPG, GIF up to 5MB (optional)</span>
            </label>
          </div>
          <div id="posterError" class="error-message">Please select a valid image for the poster.</div>
          <div id="posterPreviewContainer" class="image-preview-container"></div>
        </div>

        <!-- Approval Letter (Required) -->
        <div class="form-group">
          <label class="form-label">Approval Letter (File Attachment) <span style="color: #dc2626;">*</span></label>
          <div class="file-input-container">
            <input
              type="file"
              id="approval_letter"
              name="approval_letter"
              class="file-input"
              accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
              required
            />
            <label for="approval_letter" class="file-input-label">
              <span class="file-input-icon">📄</span>
              <span class="file-input-text">Click to upload approval letter</span>
              <span class="file-input-hint">PDF, DOC up to 5MB (required)</span>
            </label>
          </div>
          <div id="letterError" class="error-message">Approval Letter is required and must be a valid file.</div>
          <div id="letterPreviewContainer" class="image-preview-container"></div>
        </div>

        <button type="submit" id="submitBtn" class="submit-btn">Create Event</button>
      </form>
    </div>
  </div>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("eventsForm");
    const eventNameInput = document.getElementById("event_name");
    const eventDescriptionInput = document.getElementById("event_description");
    const eventDateInput = document.getElementById("event_date");
    const eventTimeInput = document.getElementById("event_time");
    const eventLocationInput = document.getElementById("event_location");
    const posterInput = document.getElementById("event_poster");
    const letterInput = document.getElementById("approval_letter");
    const eventNameError = document.getElementById("eventNameError");
    const eventDescriptionError = document.getElementById("eventDescriptionError");
    const eventDateError = document.getElementById("eventDateError");
    const eventTimeError = document.getElementById("eventTimeError");
    const eventLocationError = document.getElementById("eventLocationError");
    const posterError = document.getElementById("posterError");
    const letterError = document.getElementById("letterError");
    const posterLabel = document.querySelector('label[for="event_poster"]');
    const letterLabel = document.querySelector('label[for="approval_letter"]');
    const posterPreviewContainer = document.getElementById("posterPreviewContainer");
    const letterPreviewContainer = document.getElementById("letterPreviewContainer");

    const originalPosterLabelHTML = posterLabel.innerHTML;
    const originalLetterLabelHTML = letterLabel.innerHTML;

    let selectedPosterFile = null;
    let selectedLetterFile = null;

    function showError(element, message) {
      element.textContent = message;
      element.style.display = "block";
    }

    function hideError(element) {
      element.style.display = "none";
    }

    function renderPosterPreview() {
      posterPreviewContainer.innerHTML = "";
      if (!selectedPosterFile) {
        posterPreviewContainer.style.display = "none";
        posterLabel.innerHTML = originalPosterLabelHTML;
        return;
      }
      posterPreviewContainer.style.display = "flex";

      const wrapper = document.createElement("div");
      wrapper.classList.add("image-preview-wrapper");

      // Image preview for poster
      const img = document.createElement("img");
      img.classList.add("image-preview");
      img.alt = "Event Poster Preview";

      const reader = new FileReader();
      reader.onload = (e) => {
        img.src = e.target.result;
      };
      reader.readAsDataURL(selectedPosterFile);

      wrapper.appendChild(img);

      const removeBtn = document.createElement("button");
      removeBtn.type = "button";
      removeBtn.classList.add("remove-image-btn");
      removeBtn.innerHTML = "&times;";
      removeBtn.title = "Remove poster";
      removeBtn.addEventListener("click", () => {
        selectedPosterFile = null;
        posterInput.value = "";  // Clear input
        updatePosterLabel();
        renderPosterPreview();
        hideError(posterError);
      });

      wrapper.appendChild(removeBtn);
      posterPreviewContainer.appendChild(wrapper);

      // Update label text with filename
      updatePosterLabel();
    }

    function updatePosterLabel() {
      if (selectedPosterFile) {
        posterLabel.innerHTML = `
          <span class="file-input-icon">✅</span>
          <span class="file-input-text">Selected: ${selectedPosterFile.name}</span>
          <span class="file-input-hint">Click to change poster</span>
        `;
      } else {
        posterLabel.innerHTML = originalPosterLabelHTML;
      }
    }

    function renderLetterPreview() {
      letterPreviewContainer.innerHTML = "";
      if (!selectedLetterFile) {
        letterPreviewContainer.style.display = "none";
        letterLabel.innerHTML = originalLetterLabelHTML;
        return;
      }
      letterPreviewContainer.style.display = "flex";

      const wrapper = document.createElement("div");
      wrapper.classList.add("image-preview-wrapper");

      // Text preview for letter (filename)
      const fileNameDiv = document.createElement("div");
      fileNameDiv.classList.add("file-preview-text");
      fileNameDiv.textContent = selectedLetterFile.name;
      wrapper.appendChild(fileNameDiv);

      const removeBtn = document.createElement("button");
      removeBtn.type = "button";
      removeBtn.classList.add("remove-image-btn");
      removeBtn.innerHTML = "&times;";
      removeBtn.title = "Remove letter";
      removeBtn.addEventListener("click", () => {
        selectedLetterFile = null;
        letterInput.value = "";  // Clear input
        updateLetterLabel();
        renderLetterPreview();
        hideError(letterError);
      });

      wrapper.appendChild(removeBtn);
      letterPreviewContainer.appendChild(wrapper);

      // Update label text with filename
      updateLetterLabel();
    }

    function updateLetterLabel() {
      if (selectedLetterFile) {
        letterLabel.innerHTML = `
          <span class="file-input-icon">✅</span>
          <span class="file-input-text">Selected: ${selectedLetterFile.name}</span>
          <span class="file-input-hint">Click to change approval letter</span>
        `;
      } else {
        letterLabel.innerHTML = originalLetterLabelHTML;
      }
    }

    // Poster file change handler
    posterInput.addEventListener("change", function (e) {
      const file = e.target.files[0];  // Single file
      if (file) {
        // Validate size (5MB)
        if (file.size > 5 * 1024 * 1024) {
          showError(posterError, "Poster must be less than 5MB");
          e.target.value = "";  // Clear invalid file
          return;
        }
        // Validate type (images only)
        const allowedPosterTypes = ["image/png", "image/jpeg", "image/gif"];
        if (!allowedPosterTypes.includes(file.type)) {
          showError(posterError, "Poster must be PNG, JPG, or GIF");
          e.target.value = "";
          return;
        }
        selectedPosterFile = file;
        hideError(posterError);
        renderPosterPreview();
      } else {
        selectedPosterFile = null;
        renderPosterPreview();
      }
    });

    // Approval Letter file change handler
    letterInput.addEventListener("change", function (e) {
      const file = e.target.files[0];  // Single file
      if (file) {
        // Validate size (5MB)
        if (file.size > 5 * 1024 * 1024) {
          showError(letterError, "Approval Letter must be less than 5MB");
          e.target.value = "";  // Clear invalid file
          return;
        }
        // Validate type (PDF, DOC)
        const allowedLetterTypes = [
          "application/pdf",
          "application/msword",
          "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ];
        if (!allowedLetterTypes.includes(file.type)) {
          showError(letterError, "Approval Letter must be PDF or DOC");
          e.target.value = "";
          return;
        }
        selectedLetterFile = file;
        hideError(letterError);
        renderLetterPreview();
      } else {
        selectedLetterFile = null;
        renderLetterPreview();
      }
    });

    // Real-time validation for text/date/time inputs (optional, for UX)
    eventNameInput.addEventListener("blur", function () {
      if (!eventNameInput.value.trim()) {
        showError(eventNameError, "Event Name cannot be empty.");
      } else {
        hideError(eventNameError);
      }
    });

    eventDateInput.addEventListener("blur", function () {
      const today = new Date().toISOString().split('T')[0];
      if (!eventDateInput.value) {
        showError(eventDateError, "Event Date cannot be empty.");
      } else if (eventDateInput.value < today) {
        showError(eventDateError, "Event Date must be today or in the future.");
      } else {
        hideError(eventDateError);
      }
    });

    eventTimeInput.addEventListener("blur", function () {
      if (!eventTimeInput.value) {
        showError(eventTimeError, "Event Time cannot be empty.");
      } else {
        hideError(eventTimeError);
      }
    });

    eventLocationInput.addEventListener("blur", function () {
      if (!eventLocationInput.value.trim()) {
        showError(eventLocationError, "Event Venue/Location cannot be empty.");
      } else {
        hideError(eventLocationError);
      }
    });

    // Form submit handler
    form.addEventListener("submit", function (e) {
      let isValid = true;

      // Validate Event Name
      if (!eventNameInput.value.trim()) {
        showError(eventNameError, "Event Name cannot be empty.");
        isValid = false;
      } else {
        hideError(eventNameError);
      }

      // Description is optional - no validation

      // Validate Event Date
      const today = new Date().toISOString().split('T')[0];
      if (!eventDateInput.value) {
        showError(eventDateError, "Event Date cannot be empty.");
        isValid = false;
      } else if (eventDateInput.value < today) {
        showError(eventDateError, "Event Date must be today or in the future.");
        isValid = false;
      } else {
        hideError(eventDateError);
      }

      // Validate Event Time
      if (!eventTimeInput.value) {
        showError(eventTimeError, "Event Time cannot be empty.");
        isValid = false;
      } else {
        hideError(eventTimeError);
      }

      // Validate Event Location
      if (!eventLocationInput.value.trim()) {
        showError(eventLocationError, "Event Venue/Location cannot be empty.");
        isValid = false;
      } else {
        hideError(eventLocationError);
      }

      // Validate Poster (optional) - just check if selected and valid
      if (selectedPosterFile) {
        hideError(posterError);
      } else {
        // Optional, so no error
      }

      // Validate Approval Letter (required)
      if (!selectedLetterFile) {
        showError(letterError, "Approval Letter is required.");
        isValid = false;
      } else {
        hideError(letterError);
      }

      if (!isValid) {
        e.preventDefault();
        // Scroll to first error for UX
        const firstError = document.querySelector(".error-message[style*='block']");
        if (firstError) {
          firstError.scrollIntoView({ behavior: "smooth", block: "center" });
        }
      }
    });
  });
</script>

</body>
</html>
