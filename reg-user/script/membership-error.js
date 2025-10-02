// Ensure the IDs are unique
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm-password');
            const errorElement = document.getElementById('password-error');
            const nextButton4 = document.getElementById('next-button');

            // Function to check password match and update button state
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                // Only show error if both fields are filled
                if (confirmPassword !== "" && password !== confirmPassword) {
                    errorElement.style.display = 'block';
                    passwordInput.classList.add('error');
                    confirmPasswordInput.classList.add('error');
                    nextButton4.disabled = true; // Disable the Next button
                } else {
                    errorElement.style.display = 'none';
                    passwordInput.classList.remove('error');
                    confirmPasswordInput.classList.remove('error');
                    nextButton4.disabled = false; // Enable the Next button
                }
            }

            // Event listener for form submission
            document.getElementById('password-form').addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (password !== confirmPassword) {
                    e.preventDefault(); // Prevent form submission
                    errorElement.style.display = 'block';
                    passwordInput.classList.add('error');
                    confirmPasswordInput.classList.add('error');
                } else {
                    errorElement.style.display = 'none';
                    passwordInput.classList.remove('error');
                    confirmPasswordInput.classList.remove('error');
                }
            });

            // Real-time validation on input change
            confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            passwordInput.addEventListener('input', checkPasswordMatch); // Also check when the password input changes
        });


document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const popupOverlay = document.getElementById('popup-overlay');
    const popupClose = document.getElementById('popup-close');
    const popupOk = document.getElementById('popup-ok');
            
            // Validation function
    function validateEmail(email) {
        const re = /^[a-zA-Z0-9._-]+@isufst\.edu\.ph$/;
        return re.test(String(email).toLowerCase());
    }
            
            // Input event for real-time validation
    emailInput.addEventListener('input', function() {
         if (this.value && !validateEmail(this.value)) {
        this.classList.add('invalid');
        emailError.style.display = 'block';
            } else {
            this.classList.remove('invalid');
            emailError.style.display = 'none';
            }
     });
            
            // Validation on blur
    emailInput.addEventListener('blur', function() {
        if (this.value && !validateEmail(this.value)) {
            this.classList.add('invalid');
            emailError.style.display = 'block';
            showPopup();
            }
    });
            
            // Show popup function

    function showPopup() {
    popupOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
    }

    function hidePopup(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    popupOverlay.classList.remove('active');
    document.body.style.overflow = ''; // Re-enable scrolling
    emailInput.focus();
    }
            
            // Event listeners for popup
        popupClose.addEventListener('click', hidePopup, false);
        popupOverlay.addEventListener('click', hidePopup, false);
            
            // Close when clicking outside popup
    popupOverlay.addEventListener('click', function(e) {
        if (e.target === this) {
        hidePopup();
        }

    });

    document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && popupOverlay.classList.contains('active')) {
        hidePopup(e);
    }
    
    });
});