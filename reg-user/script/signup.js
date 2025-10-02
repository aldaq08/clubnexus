const form = document.querySelector(".form-wizard");
const progress = form.querySelector(".progress");
const stepsContainer = form.querySelector(".steps-container");
const steps = form.querySelectorAll(".step");
const stepIndicators = form.querySelectorAll(".progress-container li");
const prevButton = form.querySelector(".prev-btn");
const nextButton = form.querySelector(".next-btn");
const submitButton = form.querySelector(".submit-btn");

document.documentElement.style.setProperty("--steps", stepIndicators.length);

let currentStep = 0;

const updateProgress = () => {
  let width = currentStep / (steps.length - 1);
  progress.style.transform = `scaleX(${width})`;

  stepsContainer.style.height = steps[currentStep].offsetHeight + "px";

  stepIndicators.forEach((indicator, index) => {
    indicator.classList.toggle("current", currentStep === index);
    indicator.classList.toggle("done", currentStep > index);
  });

  steps.forEach((step, index) => {
    const percentage = document.documentElement.dir === "rtl" ? 100 : -100;
    step.style.transform = `translateX(${currentStep * percentage}%)`;
    step.classList.toggle("current", currentStep === index);
  });

  updateButtons();
};



const updateButtons = () => {
  prevButton.hidden = currentStep === 0;
  nextButton.hidden = currentStep >= steps.length - 1;
  submitButton.hidden = !nextButton.hidden;
};

const isValidStep = () => {
  const fields = steps[currentStep].querySelectorAll("input, textarea");
  return [...fields].every((field) => field.reportValidity());
};

//* event listeners

const inputs = form.querySelectorAll("input, textarea");
inputs.forEach((input) =>
  input.addEventListener("focus", (e) => {
    const focusedElement = e.target;

    // get the step where the focused element belongs
    const focusedStep = [...steps].findIndex((step) =>
      step.contains(focusedElement)
    );

    if (focusedStep !== -1 && focusedStep !== currentStep) {
      if (!isValidStep()) return;

      currentStep = focusedStep;
      updateProgress();
    }

    stepsContainer.scrollTop = 0;
    stepsContainer.scrollLeft = 0;
  })
);





prevButton.addEventListener("click", (e) => {
  e.preventDefault(); // prevent form submission

  if (currentStep > 0) {
    currentStep--;
    updateProgress();
  }
});

nextButton.addEventListener("click", (e) => {
  e.preventDefault(); // prevent form submission

  if (!isValidStep()) return;

  if (currentStep < steps.length - 1) {
    currentStep++;
    updateProgress();
  }

});

updateProgress();

//POP UP ERROR
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



//PASSWORD VERFIFICATION
// Ensure the IDs are unique
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirm-password');
            const errorElement = document.getElementById('password-error');
            const nextButton4 = document.getElementById('next-button');
            const emailInput = document.getElementById('email');
            const verificationEmailElement = document.getElementById('verificationEmail'); // Corrected ID

            /*const form = document.querySelector('form'); // Or the specific form selector
            const completedDiv = document.querySelector('.completed');

            form.addEventListener('submit', function() {
                completedDiv.hidden = false; // Set hidden to false to show the div
            });*/

            emailInput.addEventListener('input', function() {
                verificationEmailElement.textContent = this.value; // Set the text content of the element
            });

            // Trigger the input event on page load to display the initial value
            if (emailInput.value) {
                verificationEmailElement.textContent = emailInput.value;
            }
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

        document.addEventListener('DOMContentLoaded', () => {
          const emailInput = document.getElementById('email');
          const verificationStep = document.querySelector('.step:last-child p b');

          function updateVerificationEmail() {
            if (emailInput && verificationStep) {
              verificationStep.textContent = emailInput.value;
            }
          }

          // Update email display when user types or moves to next step
          emailInput.addEventListener('input', updateVerificationEmail);

          // Also update on page load in case of pre-filled value
          updateVerificationEmail();
        });
