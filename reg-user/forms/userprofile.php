<?php
session_start();
$notificationStatus = $_SESSION['status'] ?? null;
$notificationMessage = $_SESSION['message'] ?? null;

// Clear session notification after reading
unset($_SESSION['status'], $_SESSION['message']);


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login_form.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Connect to database
$pdo = require __DIR__ . '..\..\database\db_connection.php';

// Fetch user data
$stmt = $pdo->prepare("SELECT first_name, middle_name, last_name, gender, email, user_photo, messenger, phPhone FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not found, handle error or logout
    header("Location: ../login_form.php");
    exit();
}

$profilePhoto = '';
if (!empty($user['user_photo'])) {
    $userPhoto = $user['user_photo'];
    // If user_photo is just a filename, prepend folder path
    if (strpos($userPhoto, 'src/') === false && strpos($userPhoto, 'http') !== 0) {
        $profilePhoto = '' . $userPhoto;
    } else {
        $profilePhoto = $userPhoto;
    }
} else {
    $profilePhoto = 'alt-photo-profile.png'; // fallback image
}

// Debug: display user_photo string
echo '<!-- user_photo from DB: ' . htmlspecialchars($user['user_photo']) . ' -->';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico">
    <title>User Profile | <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']); ?></title>
    <style>
        :root {
            --primary: #0075A3;
            --primary-light: #0485b7;
            --secondary: #f3f4f6;
            --text: #111827;
            --text-light: #6b7280;
            --background: #ffffff;
            --border: #e5e7eb;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Outfit", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
            color: var(--text);
            padding: 2rem;
            min-height: 100vh;
        }

        .profile-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: var(--background);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .profile-header {
            background-color: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .profile-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            background-color: #ddd;
            margin-bottom: 1rem;
            cursor: pointer;
        }

        .edit-picture-btn {
            position: absolute;
            bottom: -60px;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            color: var(--primary);
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: all 0.2s ease;
        }

        .edit-picture-btn:hover {
            background-color: var(--primary-light);
            color: white;
        }

        .profile-body {
            padding: 4rem 2rem 2rem;
        }

        .profile-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary);
            border-bottom: 2px solid var(--border);
            padding-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-light);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-control[readonly] {
            background-color: var(--secondary);
            cursor: pointer;
        }

        .edit-toggle {
            position: absolute;
            right: 0;
            top: 0;
            background: none;
            border: none;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .edit-toggle:hover {
            background-color: rgba(79, 70, 229, 0.1);
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
        }

        .btn-secondary {
            background-color: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .btn-secondary:hover {
            background-color: rgba(79, 70, 229, 0.1);
        }

        .hidden {
            display: none;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        .error .form-control {
            border-color: #dc2626;
        }

        .password-toggle {
            position: absolute;
            right: 50px;
            top: 0;
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .password-toggle svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #0075A3;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateX(150%);
            transition: transform 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification-icon {
            font-size: 1.5rem;
        }

        .back-home-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            background-color: white;
            color: var(--primary);
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: var(--shadow);
            transition: background-color 0.2s ease;
            user-select: none;
        }

        .back-home-btn:hover {
            background-color: var(--primary-light);
            color: white;
        }


        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            
            .profile-body {
                padding: 3rem 1rem 1rem;
            }
        }
    </style>
</head>
<body>
<form id="profileForm" method="POST" action="../database/update_profile.php" enctype="multipart/form-data">
    <div class="profile-container">
        <div class="profile-header">
            <img src="../src/userprofile/<?php echo $profilePhoto; ?>" class="profile-image" id="profileImage" alt="Profile Picture" />
            <h1 id="usernameDisplay"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['middle_name'] . ' ' . $user['last_name']); ?></h1>
            <button id="backHomeBtn" class="back-home-btn" aria-label="Back to Home">Home</button>
            <button class="edit-picture-btn" id="editPictureBtn">CHANGE PHOTO</button>
            <input type="file" id="fileInput" name="user_photo" accept="image/*" class="hidden" />
        </div>

        <div class="profile-body">
            <div class="profile-section">
                <h2 class="section-title">Basic Information</h2>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <button type="button" class="edit-toggle" data-field="firstName">Edit</button>
                    <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name</label>
                    <button type="button" class="edit-toggle" data-field="middleName">Edit</button>
                    <input type="text" id="middleName" name="middleName" class="form-control" value="<?php echo htmlspecialchars($user['middle_name']); ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <button type="button" class="edit-toggle" data-field="lastName">Edit</button>
                    <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <button type="button" class="edit-toggle" data-field="gender">Edit</button>
                    <select id="gender" name="gender" class="form-control" disabled>
                        <option value="" disabled>Select your gender.</option>
                        <option value="male" <?php if ($user['gender'] === 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if ($user['gender'] === 'female') echo 'selected'; ?>>Female</option>
                        <option value="other" <?php if ($user['gender'] === 'other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="profile-section">
                <h2 class="section-title">Email</h2>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <button type="button" class="edit-toggle" data-field="email">Edit</button>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="password">Change Password (minimum 6 characters)</label>
                    <button type="button" class="edit-toggle" data-field="password">Edit</button>
                    <button class="password-toggle" id="togglePassword" type="button" aria-label="Toggle password visibility">
                         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" readonly minlength="6" />
                    <div id="passwordError" class="error-message">Password must be at least 8 characters</div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <button type="button" class="edit-toggle" data-field="confirmPassword">Edit</button>
                    <button class="password-toggle" id="toggleConfirmPassword" type="button" aria-label="Toggle confirm password visibility">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                    <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="••••••••" readonly />
                    <div id="confirmError" class="error-message">Passwords don't match</div>
                </div>
            </div>

            <div class="profile-section">
                <h2 class="section-title">Contact Information</h2>
                <div class="form-group">
                    <label for="messenger">Facebook Messenger URL <small>(Optional)</small></label>
                    <button type="button" class="edit-toggle" data-field="messenger">Edit</button>
                    <input type="url" id="messenger" name="messenger" class="form-control" value="<?php echo htmlspecialchars($user['messenger']); ?>" readonly />
                </div>
                <div class="form-group">
                    <label for="phPhone">Philippine Phone Number <small>(Optional)</small></label>
                    <button class="edit-toggle" data-field="phPhone">Edit</button>
                    <input type="tel" id="phPhone" name="phPhone" class="form-control" value="<?php echo htmlspecialchars($user['phPhone']); ?>" readonly />
                </div>
            </div>

            <div class="action-buttons">
                <button type ="submit" class="btn btn-primary" id="saveBtn">Save Changes</button>
            </div>
        </div>
    </div>

        <div class="notification" id="notification" style="display:none;">
            <div class="notification-icon" id="notificationIcon">✓</div>
            <div id="notificationMessage"></div>
        </div>

</form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const editButtons = document.querySelectorAll('.edit-toggle');
            const saveBtn = document.getElementById('saveBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const fileInput = document.getElementById('fileInput');
            const profileImage = document.getElementById('profileImage');
            const editPictureBtn = document.getElementById('editPictureBtn');
            const profileForm = document.getElementById('profileForm');
            const backHomeBtn = document.getElementById('backHomeBtn');

            // Fields to track
            const fields = ['firstName', 'middleName', 'lastName', 'gender', 'email', 'password', 'confirmPassword', 'messenger', 'phPhone'];

            // Store original values on page load
            const originalValues = {};
            fields.forEach(field => {
                const el = document.getElementById(field);
                if (el) {
                    originalValues[field] = el.value;
                }
            });

            // Toggle edit mode for fields (button click and double-click on input)
            fields.forEach(fieldId => {
                const inputField = document.getElementById(fieldId);
                const editButton = document.querySelector(`.edit-toggle[data-field="${fieldId}"]`);

                if (!inputField || !editButton) return;

                // Button click handler
                editButton.addEventListener('click', function(e) {
                    e.preventDefault(); // prevent form submission if button is inside form
                    toggleEdit(inputField, this);
                });

                // Double-click handler for input field
                inputField.addEventListener('dblclick', function() {
                    if (inputField.readOnly) {
                        toggleEdit(inputField, editButton);
                    }
                });
            });

            function toggleEdit(inputField, button) {
                inputField.readOnly = !inputField.readOnly;

                if (!inputField.readOnly) {
                    inputField.focus();
                } else {
                    // Optional: when toggling back to readonly, you can keep current value or reset to original
                    // Here we keep current value (do nothing)
                }

                button.textContent = inputField.readOnly ? 'Edit' : 'Cancel';
            }

            // Password validation function
            function validatePasswords() {
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirmPassword');
                const passwordError = document.getElementById('passwordError');
                const confirmError = document.getElementById('confirmError');
                let isValid = true;

                // Reset error states
                passwordError.style.display = 'none';
                confirmError.style.display = 'none';
                password.classList.remove('error');
                confirmPassword.classList.remove('error');

                // Validate password length if password field is not empty
                if (password.value && password.value.length < 6) {
                    passwordError.style.display = 'block';
                    password.classList.add('error');
                    isValid = false;
                }

                // Validate password match if password or confirmPassword is not empty
                if (password.value !== confirmPassword.value) {
                    confirmError.style.display = 'block';
                    confirmPassword.classList.add('error');
                    isValid = false;
                }

                return isValid;
            }

            // Form submit handler
            profileForm.addEventListener('submit', function(e) {
                if (!validatePasswords()) {
                    e.preventDefault(); // prevent form submission if invalid
                    return false;
                }
                // On successful submit, originalValues will be updated after page reload
                // Optionally, you can show a loading indicator here
            });

            // Cancel button resets fields to original values and disables editing
            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();

                fields.forEach(field => {
                    const inputField = document.getElementById(field);
                    if (inputField) {
                        inputField.value = originalValues[field] || '';
                        inputField.readOnly = true;
                    }
                });

                // Reset all edit buttons text to "Edit"
                document.querySelectorAll('.edit-toggle').forEach(btn => {
                    btn.textContent = 'Edit';
                });

                // Hide password errors if visible
                document.getElementById('passwordError').style.display = 'none';
                document.getElementById('confirmError').style.display = 'none';

                // Show notification for discard
                const notification = document.getElementById('notification');
                const notificationMsgDiv = document.getElementById('notificationMessage');
                const notificationIcon = document.getElementById('notificationIcon');

                notificationMsgDiv.textContent = 'Changes discarded.';
                notification.style.background = '#6366f1'; // purple
                notificationIcon.textContent = 'ℹ';

                notification.style.display = 'flex';
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.style.display = 'none';
                        notificationMsgDiv.textContent = '';
                    }, 300);
                }, 3000);
            });

            // Profile picture change button triggers file input click
            editPictureBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.click();
            });

            // Preview selected profile picture
            fileInput.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(event) {
                        profileImage.src = event.target.result;
                    };

                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Back to home button click
            backHomeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '../home.php'; // Adjust path as needed
            });

            // Update displayed username when name fields change
            ['firstName', 'middleName', 'lastName'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', updateDisplayName);
                }
            });

            function updateDisplayName() {
                const firstName = document.getElementById('firstName').value.trim();
                const middleName = document.getElementById('middleName').value.trim();
                const lastName = document.getElementById('lastName').value.trim();

                let fullName = firstName;
                if (middleName) fullName += ' ' + middleName;
                if (lastName) fullName += ' ' + lastName;

                document.getElementById('usernameDisplay').textContent = fullName;
            }

            // Real-time password validation
            document.getElementById('password').addEventListener('input', validatePasswords);
            document.getElementById('confirmPassword').addEventListener('input', validatePasswords);

            // Password visibility toggles
            document.getElementById('togglePassword').addEventListener('click', function() {
                togglePasswordVisibility('password', this);
            });

            document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
                togglePasswordVisibility('confirmPassword', this);
            });

            function togglePasswordVisibility(inputId, toggleBtn) {
                const input = document.getElementById(inputId);
                if (!input) return;

                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;

                toggleBtn.innerHTML = type === 'password' ? `
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                ` : `
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M17.94 17.94A10 10 0 0 1 12 20c-7 0-11-8-11-8a18 18 0 0 1 5-5.88"/>
                        <path d="M1 1l22 22"/>
                        <path d="M15.7 15.58a5 5 0 0 1-6.91-6.91"/>
                    </svg>
                `;
            }

            // --- Notification from PHP session ---

            const notificationStatus = <?php echo json_encode($notificationStatus); ?>;
            const notificationMessage = <?php echo json_encode($notificationMessage); ?>;
            const notification = document.getElementById('notification');
            const notificationMsgDiv = document.getElementById('notificationMessage');
            const notificationIcon = document.getElementById('notificationIcon');

            if (notificationStatus && notificationMessage) {
                notificationMsgDiv.textContent = notificationMessage;

                if (notificationStatus === 'success') {
                    notification.style.background = '#0075A3'; // blue
                    notificationIcon.textContent = '✓';
                } else if (notificationStatus === 'error') {
                    notification.style.background = '#dc2626'; // red
                    notificationIcon.textContent = '✗';
                } else {
                    notification.style.background = '#6366f1'; // default purple
                    notificationIcon.textContent = 'ℹ';
                }

                notification.style.display = 'flex';
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        notification.style.display = 'none';
                        notificationMsgDiv.textContent = '';
                    }, 300);
                }, 3000);
            }
        });

        // Prevent backHomeBtn default if added outside DOMContentLoaded
        document.getElementById('backHomeBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default button behavior if any
            window.history.back();
        });

        function toggleEdit(inputField, button) {
            if (inputField.tagName.toLowerCase() === 'select') {
                // Toggle disabled attribute for select
                if (inputField.disabled) {
                    inputField.disabled = false;
                    inputField.focus();
                    button.textContent = 'Cancel';
                } else {
                    inputField.disabled = true;
                    button.textContent = 'Edit';
                }
            } else {
                // For input fields
                inputField.readOnly = !inputField.readOnly;

                if (!inputField.readOnly) {
                    inputField.focus();
                    button.textContent = 'Cancel';
                } else {
                    button.textContent = 'Edit';
                }
            }
        }


    </script>
</body>
</html>

