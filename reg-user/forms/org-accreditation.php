<?php
    session_start();
    include('../database/db_connection.php');
    $logoUploadDir = '../src/org-logo/';
    $attachmentUploadDir = '../src/accreditation/';

    if (!file_exists($logoUploadDir)) {
        mkdir($logoUploadDir, 0777, true); 
    }

    if (!file_exists($attachmentUploadDir)) {
        mkdir($attachmentUploadDir, 0777, true);
    }

    try {
        $pdo = new PDO("mysql:host=$server_name;dbname=$db_name;charset=utf8", $user_name, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    // Initialize variables for the form and admin data
    $adminName = '';
    $adminEmail = '';
    $adminPhone = '';
    $submitted = false;
    $successMessage = '';
    $errorMessage = '';

    // Check if user is logged in (requires $_SESSION['user_id'] to be set via login system)
    if (!isset($_SESSION['user_id'])) {
        $errorMessage = 'Please log in to access this form. <a href="../login_form.php">Login here</a>.';
    } else {
        $userId = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, email, phPhone FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Combine first and last name for display
            $adminName = trim(($user['first_name'] ?? '') . ' ' .($user['middle_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
            $adminEmail = $user['email'] ?? '';
            $adminPhone = $user['phPhone'] ?? '';
        } else {
            $errorMessage = 'User  not found in database. Please contact support.';
        }
    }

    // Handle form submission (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
        $submitted = true;
        
        // Sanitize and retrieve form data (using trim and htmlspecialchars for security)
        $orgName = isset($_POST['orgName']) ? trim($_POST['orgName']) : '';
        $orgDescription = isset($_POST['orgDescription']) ? trim($_POST['orgDescription']) : '';
        $mission = isset($_POST['mission']) ? trim($_POST['mission']) : '';
        $vision = isset($_POST['vision']) ? trim($_POST['vision']) : '';
        $location = isset($_POST['location']) ? trim($_POST['location']) : '';
        $category = isset($_POST['category']) ? trim($_POST['category']) : '';
        $contactNumber = isset($_POST['contactNumber']) ? trim($_POST['contactNumber']) : '';
        $orgEmail = isset($_POST['email']) ? trim($_POST['email']) : '';
        
        // File handling variables
        $logoFilename = '';
        $attachmentFilename = '';
        $logoUploaded = false;
        $attachmentUploaded = false;
        $errors = []; 
        
        if (isset($_FILES['orgLogo']) && $_FILES['orgLogo']['error'] === UPLOAD_ERR_OK) {
            $logoFile = $_FILES['orgLogo'];
            $allowedLogoTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($logoFile['type'], $allowedLogoTypes) && $logoFile['size'] <= 5 * 1024 * 1024) { // 5MB limit
                $logoExt = pathinfo($logoFile['name'], PATHINFO_EXTENSION);
                $logoFilename = 'logo_' . $userId . '_' . time() . '.' . strtolower($logoExt);  // Unique filename
                $fullLogoPath = $logoUploadDir . $logoFilename;  // Full path for upload
                if (move_uploaded_file($logoFile['tmp_name'], $fullLogoPath)) {
                    $logoUploaded = true;
                    // Note: Store only $logoFilename in DB; reconstruct as $logoUploadDir . $logoFilename when displaying
                } else {
                    $errors[] = 'Failed to upload logo file to ' . $logoUploadDir;
                }
            } else {
                $errors[] = 'Invalid logo file: Must be JPEG, PNG, or GIF (max 5MB).';
            }
        }
        

        if (isset($_FILES['accreditationAttachment']) && $_FILES['accreditationAttachment']['error'] === UPLOAD_ERR_OK) {
            $attachmentFile = $_FILES['accreditationAttachment'];
            $allowedAttachmentTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            if (in_array($attachmentFile['type'], $allowedAttachmentTypes) && $attachmentFile['size'] <= 10 * 1024 * 1024) { // 10MB limit
                $attachmentExt = pathinfo($attachmentFile['name'], PATHINFO_EXTENSION);
                $attachmentFilename = 'attachment_' . $userId . '_' . time() . '.' . strtolower($attachmentExt);  // Unique filename
                $fullAttachmentPath = $attachmentUploadDir . $attachmentFilename;  // Full path for upload
                if (move_uploaded_file($attachmentFile['tmp_name'], $fullAttachmentPath)) {
                    $attachmentUploaded = true;
                    // Note: Store only $attachmentFilename in DB; reconstruct as $attachmentUploadDir . $attachmentFilename when displaying
                } else {
                    $errors[] = 'Failed to upload attachment file to ' . $attachmentUploadDir;
                }
            } else {
                $errors[] = 'Invalid attachment file: Must be PDF, DOC, or DOCX (max 10MB).';
            }
        }
        
        // Validation for required fields (based on organizations table structure)
        if (empty($orgName)) $errors[] = 'Organization Name is required.';
        if (empty($mission)) $errors[] = 'Mission is required.';
        if (empty($vision)) $errors[] = 'Vision is required.';
        if (empty($location)) $errors[] = 'Location of Office is required.';
        if (empty($category)) $errors[] = 'Category is required.';
        if (!$logoUploaded || empty($logoFilename)) $errors[] = 'Organization Logo is required.';
        if (!$attachmentUploaded || empty($attachmentFilename)) $errors[] = 'Accreditation Attachment is required.';
        
        // Additional validation for email and phone if provided
        if (!empty($orgEmail) && !filter_var($orgEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid Organization Email format.';
        }
        if (!empty($contactNumber) && !preg_match('/^[\+]?[1-9][\d]{0,15}$/', $contactNumber)) {
            $errors[] = 'Invalid Contact Number format.';
        }
        
        if (empty($errors)) {
            try {
                // Start transaction to ensure both operations complete successfully
                $pdo->beginTransaction();
                
                // Insert into organizations table
                $insertStmt = $pdo->prepare("
                    INSERT INTO organizations (
                        org_name, org_description, org_mission, org_vision, org_location, category, 
                        org_logo, org_files, accreditation_attachment, org_contact_email, admin_contactnum, 
                        org_admin_id, is_approved, is_active
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 2, 1)
                ");
                $insertStmt->execute([
                    htmlspecialchars($orgName, ENT_QUOTES, 'UTF-8'),  // Escape for DB storage
                    !empty($orgDescription) ? htmlspecialchars($orgDescription, ENT_QUOTES, 'UTF-8') : null,
                    htmlspecialchars($mission, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($vision, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($location, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($category, ENT_QUOTES, 'UTF-8'),
                    $logoFilename ?: null, 
                    $attachmentFilename,  
                    $attachmentFilename,
                    !empty($orgEmail) ? htmlspecialchars($orgEmail, ENT_QUOTES, 'UTF-8') : null,
                    !empty($contactNumber) ? htmlspecialchars($contactNumber, ENT_QUOTES, 'UTF-8') : null,
                    $userId
                ]);
                
                // NEW: Update user_type2 to 'admin' in users table
                $updateUserStmt = $pdo->prepare("UPDATE users SET user_type2 = 'admin' WHERE user_id = ?");
                $updateUserStmt->execute([$userId]);
                
                // Commit both operations
                $pdo->commit();
                
                $successMessage = 'Organization accreditation request submitted successfully! It is now pending approval. Your account has been upgraded to admin.';
                $_POST = array();  // Clear form data after success (prevents resubmission on refresh)
                
            } catch (PDOException $e) {
                // Rollback both operations if any fails
                $pdo->rollBack();
                $errorMessage = 'Database error occurred while submitting the form: ' . $e->getMessage();
                error_log("Form submission error: " . $e->getMessage());  // Log for debugging (check server logs)
            }
        } else {
            $errorMessage = 'Validation errors: <br>' . implode('<br>', $errors);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Accreditation Form</title>
    <link rel="icon" type="image/x-icon" href="../src/clubnexusicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        h2 {
            color: #0c5460;
            margin-bottom: 15px;
            font-weight: 600;
            text-align: center;
        }
        
        h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 500;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus, textarea:focus, select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        select {
            background-color: white;
        }
        
        .file-input {
            padding: 0;
            border: none;
        }
        
        .file-input::-webkit-file-upload-button {
            background: #e9ecef;
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        
        .file-input::-webkit-file-upload-button:hover {
            background: #dee2e6;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        .required {
            color: #e74c3c;
        }
        
        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .success-message, .error-message {
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            display: <?php echo !empty($successMessage) ? 'block' : 'none'; ?>;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            display: <?php echo !empty($errorMessage) ? 'block' : 'none'; ?>;
        }
        
        /* Admin Display Container */
        .admin-container {
            background-color: #e8f4fd;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            display: <?php echo !empty($adminName) ? 'block' : 'none'; ?>;
        }
        
        .admin-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .admin-info label {
            font-weight: 500;
            color: #495057;
        }
        
        .admin-info span {
            font-weight: 400;
            color: #212529;
        }
        
        .admin-fields {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .admin-fields input {
            background-color: #e9ecef;
            border-color: #ced4da;
        }
        
        .file-info {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
            
            input, textarea, select, button {
                font-size: 14px;
            }
            
            .admin-info {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">

        <h1>Organization Accreditation Form</h1>
        
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($successMessage)): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($successMessage); ?>
                <?php if (isset($logoUploaded) && $logoUploaded || isset($attachmentUploaded) && $attachmentUploaded): ?>
                    <p style="font-size: 14px; color: #155724; margin-top: 10px;">
                        Files uploaded successfully!
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($adminName)): ?>
            <!-- Admin Display Container (fetched from DB) -->
            <div class="admin-container">
                <h2>Logged-in Admin Information</h2>
                <div class="admin-info">
                    <label><strong>Admin Name:</strong></label>
                    <span><?php echo htmlspecialchars($adminName); ?></span>
                </div>
                <div class="admin-info">
                    <label><strong>Admin Email:</strong></label>
                    <span><?php echo htmlspecialchars($adminEmail); ?></span>
                </div>
                <?php if (!empty($adminPhone)): ?>
                    <div class="admin-info">
                        <label><strong>Admin Phone:</strong></label>
                        <span><?php echo htmlspecialchars($adminPhone); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Prefill Admin Fields (read-only for logged-in user) -->
            <div class="admin-fields">
                <h3>Admin Details (Auto-filled from your profile)</h3>
                <div class="form-group">
                    <label for="adminName"><strong>Admin Name</strong></label>
                    <input type="text" id="adminName" name="adminName" value="<?php echo htmlspecialchars($adminName); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="adminEmail"><strong>Admin Email</strong></label>
                    <input type="email" id="adminEmail" name="adminEmail" value="<?php echo htmlspecialchars($adminEmail); ?>" readonly>
                </div>
                <?php if (!empty($adminPhone)): ?>
                    <div class="form-group">
                        <label for="adminPhone">Admin Phone</label>
                        <input type="tel" id="adminPhone" name="adminPhone" value="<?php echo htmlspecialchars($adminPhone); ?>" readonly>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['user_id'])): ?>
            <form id="accreditationForm" method="POST" enctype="multipart/form-data">
                <!-- Organization Basic Information -->
                <div class="form-group">
                    <label for="orgName">Organization Name <span class="required">*</span></label>
                    <input type="text" id="orgName" name="orgName" required 
                           value="<?php echo isset($_POST['orgName']) ? htmlspecialchars($_POST['orgName']) : ''; ?>"
                           placeholder="Enter the full name of your organization">
                    <div class="error" id="orgNameError">Please enter your organization name</div>
                </div>
                
                <div class="form-group">
                    <label for="category">Category <span class="required">*</span></label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="student-government" <?php echo (isset($_POST['category']) && $_POST['category'] === 'student-government') ? 'selected' : ''; ?>>Student Government</option>
                        <option value="sports" <?php echo (isset($_POST['category']) && $_POST['category'] === 'sports') ? 'selected' : ''; ?>>Sports</option>
                        <option value="educational" <?php echo (isset($_POST['category']) && $_POST['category'] === 'educational') ? 'selected' : ''; ?>>Educational</option>
                        <option value="culture" <?php echo (isset($_POST['category']) && $_POST['category'] === 'culture') ? 'selected' : ''; ?>>Culture</option>
                        <option value="other" <?php echo (isset($_POST['category']) && $_POST['category'] === 'other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                    <div class="error" id="categoryError">Please select a category</div>
                </div>
                
                <div class="form-group">
                    <label for="orgLogo">Organization Logo <span class="required">*</span></label>
                    <input type="file" id="orgLogo" name="orgLogo" accept="image/jpeg,image/png,image/gif" class="file-input" required>
                    <div class="file-info">Accepted formats: JPEG and PNG (Max: 5MB)</div>
                    <div class="error" id="orgLogoError">Please select a valid image file</div>
                </div>
                
                <div class="form-group">
                    <label for="orgDescription">Organization Description</label>
                    <textarea id="orgDescription" name="orgDescription" 
                              placeholder="Describe your organization's purpose, activities, and goals"><?php echo isset($_POST['orgDescription']) ? htmlspecialchars($_POST['orgDescription']) : ''; ?></textarea>
                    <div class="error" id="orgDescriptionError">Please provide a description of your organization</div>
                </div>
                
                <!-- Mission and Vision -->
                <div class="form-group">
                    <label for="mission">Mission Statement <span class="required">*</span></label>
                    <textarea id="mission" name="mission" required
                              placeholder="What is your organization's purpose and core values?"><?php echo isset($_POST['mission']) ? htmlspecialchars($_POST['mission']) : ''; ?></textarea>
                    <div class="error" id="missionError">Please provide your organization's mission statement</div>
                </div>
                
                <div class="form-group">
                    <label for="vision">Vision Statement <span class="required">*</span></label>
                    <textarea id="vision" name="vision" required
                              placeholder="What is your organization's long-term vision and aspirations?"><?php echo isset($_POST['vision']) ? htmlspecialchars($_POST['vision']) : ''; ?></textarea>
                    <div class="error" id="visionError">Please provide your organization's vision statement</div>
                </div>
                
                <!-- Contact Information -->
                <div class="form-group">
                    <label for="location">Location of Office <span class="required">*</span></label>
                    <input type="text" id="location" name="location" required
                           value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>"
                           placeholder="Building name, room number, or specific location">
                    <div class="error" id="locationError">Please enter your office location</div>
                </div>
                
                <div class="form-group">
                    <label for="contactNumber">Contact Number</label>
                    <input type="tel" id="contactNumber" name="contactNumber" 
                           value="<?php echo isset($_POST['contactNumber']) ? htmlspecialchars($_POST['contactNumber']) : ''; ?>"
                           placeholder="Phone number for organization inquiries (e.g., +639123456780)">
                    <div class="error" id="contactNumberError">Please enter a valid phone number</div>
                </div>
                
                <div class="form-group">
                    <label for="email">Organization Email Address</label>
                    <input type="email" id="email" name="email" 
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           placeholder="Official email address for the organization (e.g., email@isufst.edu.ph)">
                    <div class="error" id="emailError">Please enter a valid email address</div>
                </div>
                
                <!-- Accreditation Documents -->
                <div class="form-group">
                    <label for="accreditationAttachment">Accreditation Attachment <span class="required">*</span></label>
                    <input type="file" id="accreditationAttachment" name="accreditationAttachment" 
                           accept=".pdf,.doc,.docx" class="file-input" required>
                    <div class="file-info">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</div>
                    <div class="error" id="accreditationAttachmentError">Please select a valid document</div>
                </div>
                
                <button type="submit">Submit Accreditation Request</button>
            </form>
            
        <?php else: ?>
            <div class="error-message">
                You must be logged in to submit this form. <a href="../login_form.php">Login here</a>.
            </div>
        <?php endif; ?>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('accreditationForm');
        if (!form) return;  // Exit if form doesn't exist (e.g., not logged in)
        
        // Validate file inputs (logo and attachment)
        function validateFile(input, errorElement, allowedTypes, maxSizeMB, isRequired = false) {
            if (input.files.length === 0) {
                if (isRequired) {
                    errorElement.textContent = 'This field is required';
                    errorElement.style.display = 'block';
                    return false;
                }
                errorElement.style.display = 'none';
                return true;
            }
            
            const file = input.files[0];
            const fileType = file.type;
            const fileSizeMB = file.size / (1024 * 1024);  // Convert bytes to MB
            
            if (!allowedTypes.includes(fileType)) {
                errorElement.textContent = 'Please select a valid file type (see accepted formats below)';
                errorElement.style.display = 'block';
                return false;
            }
            
            if (fileSizeMB > maxSizeMB) {
                errorElement.textContent = `File size must be less than ${maxSizeMB}MB`;
                errorElement.style.display = 'block';
                return false;
            }
            
            errorElement.style.display = 'none';
            return true;
        }
        
        // Validate phone number format (allows international, e.g., +1-555-123-4567)
        function validatePhoneNumber(phone) {
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$/;  // Basic international phone regex (digits + optional +)
            return phoneRegex.test(phone.replace(/[\s\-\(\)]/g, ''));  // Remove spaces, dashes, parentheses
        }
        
        // Validate email format
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;  // Basic email regex
            return emailRegex.test(email);
        }
        
        // Validate text field (required or optional with format check)
        function validateTextField(input, errorElement, isRequired = false, validator = null) {
            const value = input.value.trim();
            if (isRequired && !value) {
                errorElement.textContent = 'This field is required';
                errorElement.style.display = 'block';
                return false;
            }
            
            if (!isRequired && !value) {
                errorElement.style.display = 'none';
                return true;
            }
            
            // If value exists and validator is provided, check format
            if (validator && !validator(value)) {
                errorElement.textContent = 'Invalid format (e.g., for email or phone)';
                errorElement.style.display = 'block';
                return false;
            }
            
            errorElement.style.display = 'none';
            return true;
        }
        
        // Real-time validation on input events (for text fields)
        form.addEventListener('input', function(e) {
            const target = e.target;
            
            switch(target.id) {
                case 'orgName':
                    validateTextField(target, document.getElementById('orgNameError'), true);
                    break;
                case 'mission':
                    validateTextField(target, document.getElementById('missionError'), true);
                    break;
                case 'vision':
                    validateTextField(target, document.getElementById('visionError'), true);
                    break;
                case 'location':
                    validateTextField(target, document.getElementById('locationError'), true);
                    break;
                case 'contactNumber':
                    validateTextField(target, document.getElementById('contactNumberError'), false, validatePhoneNumber);
                    break;
                case 'email':
                    validateTextField(target, document.getElementById('emailError'), false, validateEmail);
                    break;
                case 'orgDescription':
                    // Optional field - no validation needed, but hide error if shown
                    document.getElementById('orgDescriptionError').style.display = 'none';
                    break;
            }
        });
        
        // Validation on change events (for select and file inputs)
        form.addEventListener('change', function(e) {
            const target = e.target;
            
            if (target.id === 'category') {
                const categoryError = document.getElementById('categoryError');
                if (target.value === '') {
                    categoryError.textContent = 'Please select a category';
                    categoryError.style.display = 'block';
                } else {
                    categoryError.style.display = 'none';
                }
            }
            
            if (target.id === 'orgLogo') {
                validateFile(target, document.getElementById('orgLogoError'), 
                             ['image/jpeg', 'image/png', 'image/gif'], 5, true);
            }
            
            if (target.id === 'accreditationAttachment') {
                validateFile(target, document.getElementById('accreditationAttachmentError'), 
                             ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], 
                             10, true);
            }
        });
        
        // Final validation on form submission
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset all errors first
            const allErrors = form.querySelectorAll('.error');
            allErrors.forEach(error => error.style.display = 'none');
            
            // Validate required text fields
            const orgName = document.getElementById('orgName');
            const orgNameError = document.getElementById('orgNameError');
            if (!validateTextField(orgName, orgNameError, true)) {
                isValid = false;
            }
            
            const mission = document.getElementById('mission');
            const missionError = document.getElementById('missionError');
            if (!validateTextField(mission, missionError, true)) {
                isValid = false;
            }
            
            const vision = document.getElementById('vision');
            const visionError = document.getElementById('visionError');
            if (!validateTextField(vision, visionError, true)) {
                isValid = false;
            }
            
            const location = document.getElementById('location');
            const locationError = document.getElementById('locationError');
            if (!validateTextField(location, locationError, true)) {
                isValid = false;
            }
            
            // Validate category select
            const category = document.getElementById('category');
            const categoryError = document.getElementById('categoryError');
            if (category.value === '') {
                categoryError.textContent = 'Please select a category';
                categoryError.style.display = 'block';
                isValid = false;
            }
            
            // Validate required files
            const orgLogo = document.getElementById('orgLogo');
            const orgLogoError = document.getElementById('orgLogoError');
            if (!validateFile(orgLogo, orgLogoError, ['image/jpeg', 'image/png', 'image/gif'], 5, true)) {
                isValid = false;
            }
            
            const accreditationAttachment = document.getElementById('accreditationAttachment');
            const accreditationAttachmentError = document.getElementById('accreditationAttachmentError');
            if (!validateFile(accreditationAttachment, accreditationAttachmentError, 
                              ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'], 
                              10, true)) {
                isValid = false;
            }
            
            // Validate optional fields (format only if provided)
            const contactNumber = document.getElementById('contactNumber');
            const contactNumberError = document.getElementById('contactNumberError');
            if (!validateTextField(contactNumber, contactNumberError, false, validatePhoneNumber)) {
                isValid = false;
            }
            
            const email = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            if (!validateTextField(email, emailError, false, validateEmail)) {
                isValid = false;
            }
            
            // If form is invalid, prevent submission and scroll to first error
            if (!isValid) {
                e.preventDefault();
                const firstError = form.querySelector('.error:not([style*="none"])');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.parentElement.querySelector('input, textarea, select').focus();  // Focus on the field
                }
                alert('Please fix the errors above before submitting.');  // Optional alert for emphasis
            }
        });
    });
</script>
</body>
</html>

