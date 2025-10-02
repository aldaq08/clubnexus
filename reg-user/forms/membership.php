<?php
include("../database/db_connection.php");

// Get organization ID from URL parameter
$org_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// DEBUG: Check if org_id is being received
echo "<!-- DEBUG: org_id = $org_id -->";

// Get user_id from session or URL
session_start();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : (isset($_GET['user_id']) ? intval($_GET['user_id']) : 0);

// Fetch organization details
$org_name = "";
$org_logo = "";

if ($org_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT org_name, org_logo FROM organizations WHERE org_id = ?");
        $stmt->execute([$org_id]);
        $org_data = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        echo "<!-- DEBUG: org_data = " . print_r($org_data, true) . " -->";
        
        if ($org_data && is_array($org_data)) { 
            $org_name = $org_data['org_name'] ?? '';
            $org_logo = $org_data['org_logo'] ?? 'default-logo.png'; 
            
            echo "<!-- DEBUG: org_name = $org_name -->";
            echo "<!-- DEBUG: org_logo = $org_logo -->";
        } else {
            echo "<!-- DEBUG: No organization found with ID $org_id or invalid data -->";
            $org_name = 'Unknown Organization';
            $org_logo = 'default-logo.png';
        }
    } catch (PDOException $e) {
        echo "<!-- DEBUG: Database error: " . $e->getMessage() . " -->";
        $org_name = 'Error Loading Organization';
        $org_logo = 'default-logo.png';
    }
} else {
    echo "<!-- DEBUG: org_id is 0 or not set -->";
    $org_name = 'Invalid Organization';
    $org_logo = 'default-logo.png';
}

$user_data = null;
if ($user_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT first_name, middle_name, last_name FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data && is_array($user_data)) {
            $user_data['user_id'] = $user_id;
        }
    } catch (PDOException $e) {
        echo "<!-- DEBUG: User database error: " . $e->getMessage() . " -->";
    }
}

$is_member = false;
if ($user_id > 0 && $org_id > 0 && $user_data) {
    try {
        $stmt = $pdo->prepare("SELECT membership_id FROM memberships WHERE user_id = ? AND org_id = ? LIMIT 1");
        $stmt->execute([$user_id, $org_id]);
        if ($stmt->fetch()) {
            $is_member = true;
            echo "<!-- DEBUG: User $user_id is already a member of org $org_id -->";
        }
    } catch (PDOException $e) {
        echo "<!-- DEBUG: Membership check error: " . $e->getMessage() . " -->";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Membership Form - <?php echo htmlspecialchars($org_name); ?></title>
    <!-- Import Outfit font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet" />
    <link rel="icon" type="image/c-icon" href="../src/clubnexusicon.ico">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', Arial, sans-serif;
            background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 600px;
            width: 100%;
            margin: 2rem;
            transition: transform 0.3s ease;
        }
        .form-container:hover {
            transform: translateY(-5px);
        }
        .org-header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #0075A3;
        }
        .org-logo-large {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0075A3;
            margin: 0 auto 1rem auto;
            display: block;
        }
        .org-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0075A3;
            margin: 0;
        }
        .user-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #0075A3;
        }
        .user-info p {
            margin: 0.5rem 0;
            color: #495057;
        }
        .user-info strong {
            color: #0075A3;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .grid-item {
            flex: 1 1 calc(50% - 0.5rem);
        }
        @media (max-width: 768px) {
            .grid-item {
                flex: 1 1 100%;
            }
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 1rem;
            color: #555;
            font-weight: 600;
        }
        input[type="text"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-family: 'Outfit', Arial, sans-serif;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: 'Outfit', Arial, sans-serif;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
        }
        .file-input {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .file-input input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .file-input label {
            display: block;
            background: #f7fafc;
            border: 2px dashed #cbd5e0;
            padding: 0.75rem;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            font-size: 1rem;
            color: #999;
            font-family: 'Outfit', Arial, sans-serif;
            font-weight: 600;
        }
        .file-input label:hover {
            background: #edf2f7;
            border-color: #a0aec0;
            color: #666;
        }
        .uploaded-file {
            display: none;
            font-size: 0.875rem;
            color: #666;
            margin-top: 0.5rem;
            font-family: 'Outfit', Arial, sans-serif;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background: #0075A3;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-family: 'Outfit', Arial, sans-serif;
            font-weight: 700;
        }
        button:hover {
            background: rgb(3, 137, 255);
        }
        button:active {
            transform: translateY(1px);
        }
        #successMessage {
            text-align: center;
            color: #22c55e;
            font-weight: 700;
            margin-top: 1rem;
            display: none;
            font-family: 'Outfit', Arial, sans-serif;
        }
        .required {
            color: red;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: none;
        }
        .membership-message {
            text-align: center;
            background: #d4edda;
            color: #155724;
            padding: 1.5rem;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            margin-bottom: 1rem;
            font-family: 'Outfit', Arial, sans-serif;
            font-weight: 600;
        }            
    </style>
</head>
<body>
    <div class="form-container">
        <div class="org-header">
            <img src="../src/org-logo/<?php echo htmlspecialchars($org_logo); ?>" alt="<?php echo htmlspecialchars($org_name); ?> Logo" class="org-logo-large" onerror="this.src='../src/default-logo.png';">
            <h1 class="org-name"><?php echo htmlspecialchars($org_name); ?></h1>
        </div>
        <?php if ($user_data): ?>
        <div class="user-info">
            <h3>Applicant Information</h3>
            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($user_data['user_id']); ?></p>
            <p><strong>Name:</strong> <?php 
                $full_name = trim(($user_data['first_name'] ?? '') . ' ' . ($user_data['middle_name'] ?? '') . ' ' . ($user_data['last_name'] ?? ''));
                echo htmlspecialchars($full_name);
            ?></p>
            <p><strong>Applying to:</strong> <?php echo htmlspecialchars($org_name); ?></p>
        </div>
        <?php endif; ?>

        <?php if ($is_member): ?>
            <!-- Show message if already a member (prevents form display and duplication) -->
            <div class="membership-message">
                <h2>You are already a member!</h2>
                <p>You are currently applied or already a member of <strong><?php echo htmlspecialchars($org_name); ?></strong>. Duplicate memberships are not allowed.</p>
                <p>If you believe this is an error, please contact the organization administrator.</p>
            </div>
        <?php else: ?>
            <!-- Show form only if not already a member -->
            <h2>Membership Application Form</h2>
            <form id="membershipForm" enctype="multipart/form-data" action="../database/membership-process.php" method="POST">
                <!-- Hidden fields -->
                <input type="hidden" name="org_id" value="<?php echo $org_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                
                <div class="grid">
                    <div class="grid-item">
                        <div class="form-group">
                            <label for="firstName">First Name <span class="required">*</span></label>
                            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user_data['first_name'] ?? ''); ?>" required />
                            <div class="error-message" id="firstNameError"></div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-group">
                            <label for="middleName">Middle Name</label>
                            <input type="text" id="middleName" name="middleName" value="<?php echo htmlspecialchars($user_data['middle_name'] ?? ''); ?>" />
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-group">
                            <label for="lastName">Last Name <span class="required">*</span></label>
                            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user_data['last_name'] ?? ''); ?>" required />
                            <div class="error-message" id="lastNameError"></div>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="form-group">
                            <label for="course">Course <span class="required">*</span></label>
                            <select id="course" name="course" required>
                                <option value="" disabled selected>Select your course</option>
                                <option value="bsit">Bachelor of Science in Information Technology</option>
                                <option value="bsed">Bachelor of Science in Secondary Education</option>
                                <option value="beed">Bachelor of Elementary Education</option>
                                <option value="bshm">Bachelor of Science in Hospitality Management</option>
                                <option value="bsoa">Bachelor of Science in Office Administration</option>
                                <option value="bsa">Bachelor of Science in Agriculture</option>
                            </select>
                            <div class="error-message" id="courseError"></div>
                        </div>
                    </div>
                    
                    <div class="grid-item">
                        <div class="form-group">
                            <label for="userId">Student ID <span class="required">*</span></label>
                            <input type="text" id="userId" name="userId" value="<?php echo htmlspecialchars($user_data['user_id'] ?? ''); ?>" required />
                            <div class="error-message" id="userIdError"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="width: 100%;">
                    <label for="student_description">Tell me about yourself (Not required)</label>
                    <textarea id="student_description" name="student_description" rows="4" placeholder="Write something about yourself...(Minimum of 500 words)" style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 6px; font-family: 'Outfit', Arial, sans-serif; font-size: 1rem; resize: vertical;"></textarea>
                </div>


                <div class="form-group">
                    <label for="photo">Photo (Formal) <span class="required">*</span></label>
                    <div class="file-input">
                        <input type="file" id="photo" name="photo" accept="image/*" required />
                        <label for="photo" id="photoLabel">Click to upload Photo (Image files only)</label>
                        <div class="uploaded-file" id="photoUploaded">No file chosen</div>
                        <div class="error-message" id="photoError"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="entry1">Entry 1</label>
                    <div class="file-input">
                        <input type="file" id="entry1" name="entry1" />
                        <label for="entry1" id="entry1Label">Click to upload Entry 1</label>
                        <div class="uploaded-file" id="entry1Uploaded">No file chosen</div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="entry2">Entry 2</label>
                    <div class="file-input">
                        <input type="file" id="entry2" name="entry2" />
                        <label for="entry2" id="entry2Label">Click to upload Entry 2</label>
                        <div class="uploaded-file" id="entry2Uploaded">No file chosen</div>
                    </div>
                </div>

                <!-- RF FIELD MADE REQUIRED -->
                <div class="form-group">
                    <label for="rf">RF (Registration Form) <span class="required">*</span></label>
                    <div class="file-input">
                        <input type="file" id="rf" name="rf" required />
                        <label for="rf" id="rfLabel">Click to upload RF (Required)</label>
                        <div class="uploaded-file" id="rfUploaded">No file chosen</div>
                        <div class="error-message" id="rfError"></div>
                    </div>
                </div>

                <button type="submit">Submit Membership Application</button>
            </form>

            <div id="successMessage">
                Application submitted successfully! Thank you.
            </div>
        <?php endif; ?>
    </div>

<script>
    // Handle file input changes to show selected file names
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach((input) => {
        input.addEventListener('change', function (e) {
            const file = e.target.files[0];
            const labelId = input.id + 'Label';
            const uploadedId = input.id + 'Uploaded';
            const errorId = input.id + 'Error';

            const label = document.getElementById(labelId);
            const uploadedDiv = document.getElementById(uploadedId);
            const errorDiv = document.getElementById(errorId);

            if (file) {
                label.textContent = `Selected: ${file.name}`;
                uploadedDiv.textContent = `File: ${file.name} (${(file.size / 1024).toFixed(2)} KB)`;
                uploadedDiv.style.display = 'block';
                
                // Clear any previous errors
                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
                
                // Validate file type for photo and RF
                if (input.id === 'photo' || input.id === 'rf') {
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
                    if (!validTypes.includes(file.type)) {
                        const errorMsg = input.id === 'photo' 
                            ? 'Please upload an image file (JPEG, PNG, GIF, WEBP) or PDF' 
                            : 'Please upload an image file (JPEG, PNG, GIF, WEBP) or PDF for RF';
                        
                        if (errorDiv) {
                            errorDiv.textContent = errorMsg;
                            errorDiv.style.display = 'block';
                        }
                        input.value = '';
                        
                        const originalText = input.id === 'photo'
                            ? 'Click to upload Photo (Image files or PDF only)'
                            : 'Click to upload RF (Required - Image files or PDF only)';
                        label.textContent = originalText;
                        uploadedDiv.style.display = 'none';
                    }
                }
            } else {
                const originalText =
                    input.id === 'photo'
                        ? 'Click to upload Photo (Image files or PDF only)'
                        : input.id === 'rf'
                        ? 'Click to upload RF (Required - Image files or PDF only)'
                        : `Click to upload ${input.id.charAt(0).toUpperCase() + input.id.slice(1)}`;
                label.textContent = originalText;
                uploadedDiv.style.display = 'none';
            }
        });
    });

    // Handle form submission
    document.getElementById('membershipForm').addEventListener('submit', function (e) {
        e.preventDefault();
        
        console.log("Form submission started"); // DEBUG
        
        // Reset error messages
        document.querySelectorAll('.error-message').forEach(el => {
            el.style.display = 'none';
        });
        
        // Basic validation
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const course = document.getElementById('course').value.trim();
        const userId = document.getElementById('userId').value.trim();
        const photo = document.getElementById('photo').files[0];
        const rf = document.getElementById('rf').files[0]; // RF is now required
        
        let isValid = true;
        
        if (!firstName) {
            document.getElementById('firstNameError').textContent = 'First name is required';
            document.getElementById('firstNameError').style.display = 'block';
            isValid = false;
        }
        
        if (!lastName) {
            document.getElementById('lastNameError').textContent = 'Last name is required';
            document.getElementById('lastNameError').style.display = 'block';
            isValid = false;
        }
        
        if (!course) {
            document.getElementById('courseError').textContent = 'Please select a course';
            document.getElementById('courseError').style.display = 'block';
            isValid = false;
        }
        
        if (!userId) {
            document.getElementById('userIdError').textContent = 'User ID is required';
            document.getElementById('userIdError').style.display = 'block';
            isValid = false;
        }
        
        if (!photo) {
            document.getElementById('photoError').textContent = 'Photo is required';
            document.getElementById('photoError').style.display = 'block';
            isValid = false;
        }
        
        // RF VALIDATION - NOW REQUIRED
        if (!rf) {
            document.getElementById('rfError').textContent = 'RF (Registration Form) is required';
            document.getElementById('rfError').style.display = 'block';
            isValid = false;
        }
        
        if (!isValid) {
            console.log("Form validation failed"); // DEBUG
            return;
        }
        
        const formData = new FormData(this);
        
        console.log("FormData contents:");
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }
        
        const orgIdInput = document.querySelector('input[name="org_id"]');
        const userIdInput = document.querySelector('input[name="user_id"]');
        console.log("Hidden fields - org_id:", orgIdInput ? orgIdInput.value : 'NOT FOUND');
        console.log("Hidden fields - user_id:", userIdInput ? userIdInput.value : 'NOT FOUND');
        
        fetch('../database/membership-process.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log("Response received, status:", response.status); // DEBUG
            return response.json();
        })
        .then(data => {
            console.log("Response data:", data); // DEBUG
            
            if (data.success) {
                // Show success message
                const successMessage = document.getElementById('successMessage');
                successMessage.style.display = 'block';
                successMessage.style.color = '#22c55e';
                successMessage.textContent = data.message || 'Application submitted successfully! Thank you.';
                
                // Reset form
                this.reset();
                document.querySelectorAll('.uploaded-file').forEach(el => {
                    el.style.display = 'none';
                });
                document.querySelectorAll('.file-input label').forEach((label, index) => {
                    if (index === 0) {
                        label.textContent = 'Click to upload Photo (Image files or PDF only)';
                    } else if (label.htmlFor === 'rf') {
                        label.textContent = 'Click to upload RF (Required - Image files or PDF only)';
                    } else {
                        label.textContent = `Click to upload ${label.htmlFor.charAt(0).toUpperCase() + label.htmlFor.slice(1)}`;
                    }
                });
                
                // Hide after 5 seconds
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 5000);
            } else {
                // Show more detailed error message if available
                if (data.debug) {
                    console.error("Debug info:", data.debug);
                    alert(data.message + '\n\nDebug info: ' + JSON.stringify(data.debug));
                } else {
                    alert(data.message || 'Error submitting application. Please try again.');
                }
            }
        })
        .catch(error => {
            console.error('Fetch Error:', error);
            alert('Network error submitting application. Please check console for details.');
        });
    });
</script>
</body>
</html>
