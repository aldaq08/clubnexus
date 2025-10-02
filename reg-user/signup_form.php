<?php
session_start();

// Get status from both URL and session
$status = $_GET['status'] ?? '';
$message1 = $_SESSION['message1'] ?? $_GET['success_msg'] ?? '';
$message2 = $_SESSION['message2'] ?? $_GET['error'] ?? '';

$signupEmail = $_SESSION['verification_email'] ?? '';

// Clear session messages after displaying them
unset($_SESSION['message1'], $_SESSION['message2']);

// Handle verification code sent message
if (isset($_GET['sent']) && $_GET['sent'] == 1) {
    echo "<script>alert('Verification code sent to your email!');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up Form</title>
    <link rel="stylesheet" href="styles/signup.css" />
    <link rel="icon" type="image/c-icon" href="src/clubnexusicon.ico">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(135deg, #2d85db, #ffde59, #0075a3);
            color: #333;
            font-family: Arial, sans-serif;
            padding: 2%;
        }

        .logo-section, .logo-section img {
            max-height: 70px;
            text-align: center;
        }

        #verify {
            width: 100%;
            margin-top: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .error-message, .error-email, .error-vcode {
            color: red;
            display: none;
            margin-top: 5px;
            font-size: 0.9em;
        }

        .invalid, .error {
            border-color: red !important;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .popup-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .popup {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .popup-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        .popup-content {
            margin-top: 10px;
        }

        .completed {
            text-align: center;
            padding: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            margin: 20px 0;
            max-width: 500px;
        }

        .completed.hidden {
            display: none;
        }

        .hompage {
            background: #0075a3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .hompage a {
            color: white;
            text-decoration: none;
        }

        .form-control {
            margin-bottom: 15px;
        }

        .form-control label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control input, .form-control select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .step {
            display: none;
        }

        .step.current {
            display: block;
        }

        .progress-container {
            position: relative;
            margin: 20px 0;
        }

        .progress {
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.3s ease;
        }

        .progress-container ol {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: space-between;
            margin: 10px 0 0 0;
        }

        .progress-container li {
            flex: 1;
            text-align: center;
            position: relative;
            color: #999;
        }

        .progress-container li.current {
            color: #0075a3;
            font-weight: bold;
        }

        .progress-container li.done {
            color: #0075a3;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .controls button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background: #0075a3;
            color: white;
        }

        .controls button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .controls button.hidden {
            display: none;
        }

        .steps-container {
            overflow: hidden;
            transition: height 0.3s ease;
        }

        .hompage {
            background: #0075a3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin-top: 10px;
        }

        .hompage:hover {
            background: #005a82;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <form class="form-wizard" action="database/signup_con.php" method="post" enctype="multipart/form-data">

        <div class="logo-section">
            <img src="src/web_logo.png" alt="web logo" class="web-logo">
        </div>
        
        <!-- Success/Error Message Section - Only show when there's a status -->
        <?php if ($status === 'success' || $status === 'error'): ?>
        <div class="completed">
            <?php if ($status === "success"): ?>
                <h2><?php echo htmlspecialchars($message1 ?: 'Registration complete!'); ?></h2>
                <p>You are now registered!</p>
                <a href="login_form.php" class="hompage">Back to Login page</a>
            <?php elseif ($status === "error"): ?>
                <h2><?php echo htmlspecialchars($message2 ?: 'An error occurred.'); ?></h2>
                <p>Check the information and Sign Up again!</p>
                <a href="signup_form.php" style="color: #0075a3; text-decoration: none;">Try Again</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Main Form - Only show when there's NO status -->
        <?php if (!$status): ?>
        <h1>Sign Up</h1>

        <div class="progress-container">
            <div class="progress"></div>
            <ol>
                <li class="current">Email</li>
                <li>Password</li>
                <li>User Info</li>
                <li>Verification</li>
            </ol>
        </div>

        <div class="steps-container">
            <!-- Step 1: Email -->
            <div class="step current">
                <h3>Email</h3>
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Enter a valid ISUFST email address." />
                    <p class="error-email" id="email-error">Please use your official ISUFST email address.</p>
                </div>
            </div>

            <!-- Popup Overlay for Invalid Email -->
            <div class="popup-overlay" id="popup-overlay">
                <div class="popup">
                    <div class="popup-header">
                        <h2>Invalid Email Address</h2>
                        <button class="popup-close" id="popup-close">&times;</button>
                    </div>
                    <div class="popup-content">
                        The email address you entered is not a valid ISUFST email. Please use an ISUFST email address <strong>@isufst.edu.ph</strong> to continue. If none, contact the admin.
                    </div>
                </div>
            </div>

            <!-- Step 2: Password -->
            <div class="step" id="password-form">
                <h3>Password</h3>
                <div class="form-control">
                    <label for="password">Password</label>
                    <input type="password" minlength="6" id="password" name="user_password" required />
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" minlength="6" id="confirm-password" name="confirm-password" required />
                </div>
                <div id="password-error" class="error-message">
                    Passwords do not match. Please try again.
                </div>
            </div>

            <!-- Step 3: User Information -->
            <div class="step">
                <h3>User Information</h3>
                <div class="form-control">
                    <label for="user_id">Student ID</label>
                    <input type="text" id="user_id" name="user_id" required placeholder="Don't put dashes. Numbers only." pattern="\d+" title="Numbers only, no dashes." />
                    
                    <label for="user_photo">User Photo</label>
                    <input type="file" id="user_photo" name="user_photo" accept="image/*" />
                    
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" required />
                    
                    <label for="mname">Middle Name ()</label>
                    <input type="text" id="mname" name="mname" />
                    
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" required />
                    
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected>Select Gender</option>
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                    </select>
                    
                    <label for="course">Course</label>
                    <select id="course" name="course" required>
                        <option value="" disabled selected>Select your course</option>
                        <option value="bsit">Bachelor of Science in Information Technology</option>
                        <option value="bsa">Bachelor of Science in Agriculture</option>
                        <option value="bsed">Bachelor of Secondary Education</option>
                        <option value="beed">Bachelor of Elementary Education</option>
                        <option value="bshm">Bachelor of Science in Hospitality Management</option>
                        <option value="bsoa">Bachelor of Science in Office Administration</option>
                    </select>
                </div>
            </div>

            <!-- Step 4: Verification -->
            <div class="step">
                <h3>Verification</h3>
                <p>A verification code will be sent to: <b id="verification-email"><?php echo htmlspecialchars($signupEmail); ?></b></p>
                <div class="form-control">
                    <label for="vcode">Enter Verification Code (6 digits)</label>
                    <input type="text" id="vcode" name="vcode" maxlength="6" placeholder="Enter 6-digit code" required pattern="\d{6}" title="Enter exactly 6 digits." />
                    <p class="error-vcode" id="vcode-error">Please enter a valid 6-digit verification code.</p>
                </div>
                <div id="verify">
                    <button type="button" id="send-btn">Send Code</button>
                </div>
            </div>
        </div>

        <div class="controls">
            <button type="button" class="prev-btn hidden">Prev</button>
            <button type="button" class="next-btn" id="next-button">Next</button>
            <button type="submit" class="submit-btn hidden">Submit</button>
        </div>
        <?php endif; ?>

    </form>

    <?php if (!$status): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Global selectors
        const form = document.querySelector(".form-wizard");
        const progress = form.querySelector(".progress");
        const stepsContainer = form.querySelector(".steps-container");
        const steps = form.querySelectorAll(".step");
        const stepIndicators = form.querySelectorAll(".progress-container li");
        const prevButton = document.querySelector(".prev-btn");
        const nextButton = document.querySelector(".next-btn");
        const submitButton = document.querySelector(".submit-btn");
        const emailInput = document.getElementById('email');
        const vcodeInput = document.getElementById('vcode');
        const sendBtn = document.getElementById('send-btn');
        const verificationEmailElement = document.getElementById('verification-email');

        // Set CSS variable for steps
        document.documentElement.style.setProperty("--steps", stepIndicators.length);

        let currentStep = 0;
        let codeSent = false;
        let isEmailValid = false;

        // Email validation function
        function validateEmail(email) {
            const re = /^[a-zA-Z0-9._-]+@isufst\.edu\.ph$/;
            return re.test(String(email).toLowerCase());
        }

        // Update progress bar and step visibility
        const updateProgress = () => {
            // Update progress bar
            let width = steps.length > 1 ? currentStep / (steps.length - 1) : 1;
            progress.style.transform = `scaleX(${width})`;
            
            // Update step indicators
            stepIndicators.forEach((indicator, index) => {
                indicator.classList.toggle("current", currentStep === index);
                indicator.classList.toggle("done", currentStep > index);
            });

            // Update step visibility
            steps.forEach((step, index) => {
                if (index === currentStep) {
                    step.style.display = 'block';
                    step.classList.add('current');
                } else {
                    step.style.display = 'none';
                    step.classList.remove('current');
                }
            });

            // Update container height
            if (steps[currentStep]) {
                stepsContainer.style.height = steps[currentStep].offsetHeight + "px";
            }

            updateButtons();
        };

        // Update button states
        const updateButtons = () => {
            // Show/hide buttons based on current step
            prevButton.classList.toggle('hidden', currentStep === 0);
            nextButton.classList.toggle('hidden', currentStep >= steps.length - 1);
            submitButton.classList.toggle('hidden', currentStep < steps.length - 1);

            // Special handling for email step
            if (currentStep === 0) {
                const emailValue = emailInput.value.trim();
                const isEmailValidNow = emailValue && validateEmail(emailValue);
                nextButton.disabled = !isEmailValidNow;
            }
            // Special handling for verification step (last step)
            else if (currentStep === steps.length - 1) {
                if (vcodeInput) {
                    const isCodeValid = /^\d{6}$/.test(vcodeInput.value);
                    const shouldDisable = !codeSent || !isCodeValid;
                    submitButton.disabled = shouldDisable;
                    
                    if (shouldDisable) {
                        submitButton.textContent = codeSent ? 'Enter Valid Code First' : 'Send Code First';
                    } else {
                        submitButton.textContent = 'Register';
                    }
                }
            } else {
                // For other steps, enable next button
                nextButton.disabled = false;
                nextButton.textContent = 'Next';
            }
        };

        // Check if current step is valid
        const isValidStep = () => {
            // For email step (step 0), check if email is valid
            if (currentStep === 0) {
                const emailValue = emailInput.value.trim();
                const isEmailStepValid = emailValue && validateEmail(emailValue);
                
                if (!isEmailStepValid) {
                    if (emailValue && !validateEmail(emailValue)) {
                        // Show popup for invalid email
                        showPopup();
                    } else {
                        // Show normal HTML5 validation for empty field
                        emailInput.reportValidity();
                    }
                    return false;
                }
                isEmailValid = true;
            }
            
            // For other steps, use normal HTML5 validation
            const fields = steps[currentStep].querySelectorAll("input[required], select[required]");
            return Array.from(fields).every((field) => {
                if (!field.checkValidity()) {
                    field.reportValidity();
                    field.focus();
                    return false;
                }
                return true;
            });
        };

        // Email validation and popup
        const emailError = document.getElementById('email-error');
        const popupOverlay = document.getElementById('popup-overlay');
        const popupClose = document.getElementById('popup-close');

        function showPopup() {
            popupOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            // Prevent any navigation while popup is open
            nextButton.disabled = true;
        }

        function hidePopup(e) {
            if (e) e.preventDefault();
            popupOverlay.classList.remove('active');
            document.body.style.overflow = '';
            // Re-enable next button only if email is valid
            const emailValue = emailInput.value.trim();
            isEmailValid = emailValue && validateEmail(emailValue);
            nextButton.disabled = !isEmailValid;
            // Refocus on email input so user can fix it
            emailInput.focus();
        }

        if (emailInput && emailError && popupOverlay && popupClose) {
            // Real-time email validation
            emailInput.addEventListener('input', function() {
                const emailValue = this.value.trim();
                const isValid = validateEmail(emailValue);
                
                if (emailValue && !isValid) {
                    this.classList.add('invalid');
                    emailError.style.display = 'block';
                    isEmailValid = false;
                } else {
                    this.classList.remove('invalid');
                    emailError.style.display = 'none';
                    isEmailValid = isValid;
                }
                updateVerificationEmail();
                updateButtons(); // Update button states in real-time
            });

            // Validate on blur
            emailInput.addEventListener('blur', function() {
                const emailValue = this.value.trim();
                if (emailValue && !validateEmail(emailValue)) {
                    this.classList.add('invalid');
                    emailError.style.display = 'block';
                    isEmailValid = false;
                    showPopup();
                }
            });

            popupClose.addEventListener('click', hidePopup);
            popupOverlay.addEventListener('click', (e) => {
                if (e.target === popupOverlay) hidePopup(e);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && popupOverlay.classList.contains('active')) {
                    hidePopup(e);
                }
            });
        }

        // Navigation
        prevButton.addEventListener("click", (e) => {
            e.preventDefault();
            if (currentStep > 0) {
                currentStep--;
                updateProgress();
            }
        });

        nextButton.addEventListener("click", (e) => {
            e.preventDefault();
            
            // Special handling for email step
            if (currentStep === 0) {
                const emailValue = emailInput.value.trim();
                
                // If email is empty, show HTML5 validation
                if (!emailValue) {
                    emailInput.reportValidity();
                    emailInput.focus();
                    return;
                }
                
                // If email is invalid, show popup and prevent progression
                if (!validateEmail(emailValue)) {
                    emailInput.classList.add('invalid');
                    emailError.style.display = 'block';
                    isEmailValid = false;
                    showPopup();
                    return;
                }
                
                // Email is valid, proceed
                isEmailValid = true;
            }
            
            // For all steps, check general validity
            if (!isValidStep()) return;
            
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateProgress();
            }
        });

        // Focus-based navigation with email validation
        const inputs = form.querySelectorAll("input, select, textarea");
        inputs.forEach((input) => {
            input.addEventListener("focus", (e) => {
                const focusedElement = e.target;
                const focusedStep = [...steps].findIndex((step) => step.contains(focusedElement));
                
                if (focusedStep !== -1 && focusedStep !== currentStep) {
                    // Don't allow navigation away from email step if email is invalid
                    if (currentStep === 0 && !isEmailValid) {
                        e.preventDefault();
                        emailInput.focus();
                        return;
                    }
                    
                    if (!isValidStep()) return;
                    currentStep = focusedStep;
                    updateProgress();
                }
            });
        });

        // Password match validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirm-password');
        const passwordError = document.getElementById('password-error');

        if (passwordInput && confirmPasswordInput && passwordError) {
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const isMismatch = confirmPassword && password !== confirmPassword;

                if (isMismatch) {
                    passwordError.style.display = 'block';
                    passwordInput.classList.add('error');
                    confirmPasswordInput.classList.add('error');
                } else {
                    passwordError.style.display = 'none';
                    passwordInput.classList.remove('error');
                    confirmPasswordInput.classList.remove('error');
                }
            }

            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            passwordInput.addEventListener('input', checkPasswordMatch);
        }

        // Update verification email display live
        function updateVerificationEmail() {
            if (verificationEmailElement && emailInput) {
                verificationEmailElement.textContent = emailInput.value || 'your email';
            }
        }
        if (emailInput) {
            emailInput.addEventListener('input', updateVerificationEmail);
            updateVerificationEmail(); // Initial call
        }

        // Vcode input validation
        if (vcodeInput) {
            const vcodeError = document.getElementById('vcode-error');
            vcodeInput.addEventListener('input', function() {
                const value = this.value.replace(/\D/g, ''); // Force digits only
                this.value = value;
                const isValid = /^\d{6}$/.test(value);
                if (!isValid && value.length > 0) {
                    this.classList.add('invalid');
                    if (vcodeError) vcodeError.style.display = 'block';
                } else {
                    this.classList.remove('invalid');
                    if (vcodeError) vcodeError.style.display = 'none';
                }
                updateButtons(); // Re-check button states when code is entered
            });
        }

        // Send code button: AJAX to auth.php
        // Send code button: AJAX to auth.php
        if (sendBtn && emailInput) {
            sendBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const email = emailInput.value.trim();
                if (!email) {
                    alert('Please enter your email first.');
                    emailInput.focus();
                    return;
                }

                if (!validateEmail(email)) {
                    alert('Please enter a valid ISUFST email address (@isufst.edu.ph).');
                    showPopup();
                    return;
                }

                // Disable button during request
                const originalText = sendBtn.textContent;
                sendBtn.disabled = true;
                sendBtn.textContent = 'Sending...';

                // AJAX fetch to auth.php
                fetch('database/auth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'action=send_code&email=' + encodeURIComponent(email)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("Response data:", data); // Debug log
                    
                    if (data.success) {
                        codeSent = true;
                        sendBtn.textContent = 'Code Sent! (Resend)';
                        alert('✓ ' + data.message);
                        updateButtons();
                        
                        // Debug: If we have a debug code, show it
                        if (data.debug) {
                            console.log("Debug info:", data.debug);
                        }
                    } else {
                        let errorMessage = data.message || 'Failed to send code.';
                        
                        // Specific error handling
                        if (data.error === 'duplicate_email') {
                            errorMessage = 'This email is already registered. Please use a different email.';
                            emailInput.value = '';
                            emailInput.focus();
                        } else if (data.error === 'invalid_domain') {
                            errorMessage = 'Please use an ISUFST email address (@isufst.edu.ph).';
                            showPopup();
                        }
                        
                        alert('✗ ' + errorMessage);
                        
                        // Debug info
                        if (data.debug) {
                            console.error("Error details:", data.debug);
                        }
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                    alert('✗ Network error. Please check your connection and try again.');
                })
                .finally(() => {
                    sendBtn.disabled = false;
                    if (!codeSent) sendBtn.textContent = originalText;
                });
            });
        }
        // Form submission
        form.addEventListener('submit', (e) => {
            // For the verification step, we want to allow form submission
            if (currentStep === steps.length - 1) {
                // Check if verification is valid
                if (!codeSent) {
                    e.preventDefault();
                    alert('Please send the verification code first.');
                    return false;
                }
                if (!vcodeInput || !/^\d{6}$/.test(vcodeInput.value)) {
                    e.preventDefault();
                    alert('Please enter a valid 6-digit verification code.');
                    vcodeInput.focus();
                    return false;
                }
                // All good - allow form submission
                return true;
            } else {
                // For other steps, prevent submission
                e.preventDefault();
                alert('Please complete all steps before submitting.');
                return false;
            }
        });

        // Initialize
        updateProgress();
    });
</script>
    <?php endif; ?>
</body>
</html>