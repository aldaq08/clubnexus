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
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    $uploadDir = __DIR__ . '/src/announcement/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (empty($title)) {
        $message = "Title cannot be empty.";
    } elseif (empty($description)) {
        $message = "Description cannot be empty.";
    } else {
        $uploadedFiles = [];
        $errors = [];

        if (!empty($_FILES['files']['name'][0])) {
            $fileCount = count($_FILES['files']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $fileName = basename($_FILES['files']['name'][$i]);
                $fileTmpPath = $_FILES['files']['tmp_name'][$i];
                $fileSize = $_FILES['files']['size'][$i];
                $fileError = $_FILES['files']['error'][$i];

                // Sanitize filename
                $fileName = preg_replace("/[^a-zA-Z0-9_\.-]/", "_", $fileName);
                $targetFilePath = $uploadDir . $fileName;

                if ($fileError === UPLOAD_ERR_OK) {
                    if ($fileSize <= 50 * 1024 * 1024) { // 50MB max
                        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                            $uploadedFiles[] = $fileName;
                        } else {
                            $errors[] = "Failed to upload file: $fileName";
                        }
                    } else {
                        $errors[] = "File too large (max 50MB): $fileName";
                    }
                } else {
                    $errors[] = "Error uploading file: $fileName";
                }
            }
        }

        if (empty($errors)) {
            $announcement_title = $mysqli->real_escape_string($title);
            $announcement_text = $mysqli->real_escape_string($description);
            $announcement_files = !empty($uploadedFiles) ? $mysqli->real_escape_string(implode(',', $uploadedFiles)) : null;
            $announcement_approve = 3; // default approval status

            $stmt = $mysqli->prepare("INSERT INTO announcements (org_id, user_id, announcement_title, announcement_text, announcement_file, announcement_approve) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("iisssi", $org_id, $user_id, $announcement_title, $announcement_text, $announcement_files, $announcement_approve);
                if ($stmt->execute()) {
                    $message = "Announcement created successfully.";
                    if ($uploadedFiles) {
                        $message .= " Uploaded files: " . implode(", ", $uploadedFiles);
                    }
                    $_POST = [];
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
  <title>Create Announcement | <?= htmlspecialchars($org_name) ?></title>
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
    .form-textarea {
      width: 100%;
      padding: 12px 16px;
      border: 2px dashed #d1d5db;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.15s ease;
      font-family: "Outfit", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    .form-input:focus,
    .form-textarea:focus {
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
          <img src="../../reg-user/src/org-logo/<?=htmlspecialchars($org_logo_url) ?>" alt="Organization Logo" class="org-logo" />
        </div>
      <?php endif; ?>

      <?php if ($org_name): ?>
        <div class="org-name">
          <?= htmlspecialchars($org_name) ?>
        </div>
        <hr style="margin: 16px 0; border: none; border-top: 1px solid #d1d5db;" />
        
      <?php endif; ?>

      <h1 class="card-title">Create Announcement</h1>
     

      <?php if (!empty($message)): ?>
        <div class="<?= strpos($message, 'successfully') !== false ? 'success-message' : 'error-message' ?>" style="display:block;">
          <?= $message ?>
        </div>
      <?php endif; ?>

      <form id="announcementForm" method="POST" enctype="multipart/form-data" novalidate>
        <div class="form-group">
          <label for="title" class="form-label">Announcement Title</label>
          <input
            type="text"
            id="title"
            name="title"
            class="form-input"
            placeholder="Enter announcement title"
            required
            value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
          />
          <div id="titleError" class="error-message">Please provide a title</div>
        </div>

        <div class="form-group">
          <label for="description" class="form-label">Announcement Description</label>
          <textarea
            id="description"
            name="description"
            class="form-textarea"
            placeholder="Enter announcement description"
            required
          ><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
          <div id="descriptionError" class="error-message">Please provide a description</div>
        </div>

        <div class="form-group">
          <label class="form-label">Attach Files or Images</label>
          <div class="file-input-container">
            <input
              type="file"
              id="files"
              name="files[]"
              class="file-input"
              accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"
              multiple
            />
            <label for="files" class="file-input-label">
              <span class="file-input-icon">📁</span>
              <span class="file-input-text">Click to upload files or images</span>
              <span class="file-input-hint">PNG, JPG, GIF, PDF, DOC up to 5MB each</span>
            </label>
          </div>
          <div id="imageError" class="error-message">Please select valid files</div>

          <div id="imagePreviewContainer" class="image-preview-container"></div>
        </div>

        <button type="submit" id="submitBtn" class="submit-btn">Create Announcement</button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const form = document.getElementById("announcementForm");
      const titleInput = document.getElementById("title");
      const descriptionInput = document.getElementById("description");
      const imageInput = document.getElementById("files");
      const titleError = document.getElementById("titleError");
      const descriptionError = document.getElementById("descriptionError");
      const imageError = document.getElementById("imageError");
      const fileInputLabel = document.querySelector(".file-input-label");
      const imagePreviewContainer = document.getElementById("imagePreviewContainer");

      const originalLabelHTML = fileInputLabel.innerHTML;
      let selectedFiles = [];

      function showError(element, message) {
        element.textContent = message;
        element.style.display = "block";
      }

      function hideError(element) {
        element.style.display = "none";
      }

      function renderPreviews() {
        imagePreviewContainer.innerHTML = "";
        if (selectedFiles.length === 0) {
          imagePreviewContainer.style.display = "none";
          fileInputLabel.innerHTML = originalLabelHTML;
          return;
        }
        imagePreviewContainer.style.display = "flex";

        selectedFiles.forEach((file, index) => {
          const wrapper = document.createElement("div");
          wrapper.classList.add("image-preview-wrapper");

          // Only preview images, for other files show icon + filename
          if (file.type.startsWith("image/")) {
            const img = document.createElement("img");
            img.classList.add("image-preview");
            img.alt = "Announcement preview";

            const reader = new FileReader();
            reader.onload = (e) => {
              img.src = e.target.result;
            };
            reader.readAsDataURL(file);

            wrapper.appendChild(img);
          } else {
            // Non-image file preview: show filename text
            const fileNameDiv = document.createElement("div");
            fileNameDiv.textContent = file.name;
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
          removeBtn.title = "Remove file";
          removeBtn.addEventListener("click", () => {
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreviews();
          });

          wrapper.appendChild(removeBtn);
          imagePreviewContainer.appendChild(wrapper);
        });

        // Update label text with filenames
        const fileNames = selectedFiles.map(f => f.name).join(", ");
        fileInputLabel.textContent = fileNames;
      }

      function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
      }

      imageInput.addEventListener("change", function (e) {
        const files = Array.from(e.target.files);

        for (const file of files) {
          if (file.size > 5 * 1024 * 1024) {
            showError(imageError, "Each file must be less than 5MB");
            return;
          }
          // Accept images and common document types
          const allowedTypes = [
            "image/png",
            "image/jpeg",
            "image/gif",
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
          ];
          if (!allowedTypes.includes(file.type)) {
            showError(imageError, "Please select valid file types (images, PDF, DOC)");
            return;
          }
        }

        hideError(imageError);

        files.forEach(file => {
          const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
          if (!exists) {
            selectedFiles.push(file);
          }
        });

        updateFileInput();
        renderPreviews();
      });

      form.addEventListener("submit", function (e) {
        let isValid = true;

        if (!titleInput.value.trim()) {
          showError(titleError, "Please provide a title");
          isValid = false;
        } else {
          hideError(titleError);
        }

        if (!descriptionInput.value.trim()) {
          showError(descriptionError, "Please provide a description");
          isValid = false;
        } else {
          hideError(descriptionError);
        }

        hideError(imageError);

        if (!isValid) {
          e.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
