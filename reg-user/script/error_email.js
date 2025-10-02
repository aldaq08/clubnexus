// Email validation code that matches your HTML structure
document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    const emailErrorPopup = document.getElementById('emailErrorPopup');
    const emailErrorMessage = document.getElementById('emailErrorMessage');
    const emailErrorClose = document.getElementById('emailErrorClose');
    const form = document.querySelector('form');
    const popupBackground = document.getElementById('popupBackground');
    
    // Ensure popup is hidden on page load
    hideErrorPopup();
    
    // Form submission handler
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        validateEmail();
    });
    
    // Close button handler (X button)
    emailErrorClose.addEventListener('click', () => {
        hideErrorPopup();
    });
    
    // Click background to close
    popupBackground.addEventListener('click', () => {
        hideErrorPopup();
    });
    
    // ESC key to close popup
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isPopupVisible()) {
            hideErrorPopup();
        }
    });
    
    function validateEmail() {
        const email = emailInput.value.trim();
        
        // Clear any existing errors first
        hideErrorPopup();
        
        // Handle empty email - but don't show error for empty on page load
        if (!email) {
            // Only show error if user actually tried to submit
            showErrorPopup('Email address is required.');
            return false;
        }
        
        // Validate email format first
        if (!isValidEmailFormat(email)) {
            showErrorPopup('Please enter a valid email format.');
            return false;
        }
        
        // Validate ISUFST domain
        if (!isValidISUFSTEmail(email)) {
            showErrorPopup('Please enter a valid ISUFST email address.\nIf none contact your admin.');
            return false;
        }
        
        // If all validations pass, proceed with login
        console.log('Email validation passed, proceeding with login...');
        // Add your login logic here
        // form.submit(); // Uncomment when ready to actually submit
        return true;
    }
    
    function isValidEmailFormat(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidISUFSTEmail(email) {
        return email.toLowerCase().endsWith('@isufst.edu.ph');
    }
    
    function showErrorPopup(message) {
        emailErrorMessage.textContent = message;
        emailErrorPopup.style.display = 'block';
        popupBackground.style.display = 'block';

        setTimeout(hideErrorPopup, 2500);
        
    }
    
    function hideErrorPopup() {
        emailErrorPopup.style.display = 'none';
        popupBackground.style.display = 'none';
        // Clear the message when hiding
        emailErrorMessage.textContent = '';
    }
    
    function isPopupVisible() {
        return emailErrorPopup.style.display === 'block';
    }
});

// Alternative: If you want to add a background overlay, update your HTML like this:
/*
Update your HTML popup section to this:

<div id="popupBackground" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;"></div>

<div id="emailErrorPopup" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; position: relative;">
    <button id="emailErrorClose" style="position: absolute; top: 10px; right: 10px; background: none; border: none; font-size: 18px; cursor: pointer; color: #666; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background-color 0.2s;">&times;</button>
    <p id="emailErrorMessage" style="margin: 0; padding-right: 30px;"></p>
</div>

Then use this enhanced version:

document.addEventListener('DOMContentLoaded', () => {
    const emailInput = document.getElementById('email');
    const emailErrorPopup = document.getElementById('emailErrorPopup');
    const emailErrorMessage = document.getElementById('emailErrorMessage');
    const emailErrorClose = document.getElementById('emailErrorClose');
    const form = document.querySelector('form');
    const popupBackground = document.getElementById('popupBackground');
    
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        validateEmail();
    });
    
    emailErrorClose.addEventListener('click', () => {
        hideErrorPopup();
    });
    
    popupBackground.addEventListener('click', () => {
        hideErrorPopup();
    });
    
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && isPopupVisible()) {
            hideErrorPopup();
        }
    });
    
    function validateEmail() {
        const email = emailInput.value.trim();
        
        hideErrorPopup();
        
        if (!email) {
            showErrorPopup('Email address is required.');
            return false;
        }
        
        if (!isValidEmailFormat(email)) {
            showErrorPopup('Please enter a valid email format.');
            return false;
        }
        
        if (!isValidISUFSTEmail(email)) {
            showErrorPopup('Please enter a valid ISUFST email address (@isufst.edu.ph).');
            return false;
        }
        
        console.log('Email validation passed, proceeding with login...');
        return true;
    }
    
    function isValidEmailFormat(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function isValidISUFSTEmail(email) {
        return email.toLowerCase().endsWith('@isufst.edu.ph');
    }
    
    function showErrorPopup(message) {
        emailErrorMessage.textContent = message;
        emailErrorPopup.style.display = 'block';
        popupBackground.style.display = 'block';
        emailErrorClose.focus();
    }
    
    function hideErrorPopup() {
        emailErrorPopup.style.display = 'none';
        popupBackground.style.display = 'none';
    }
    
    function isPopupVisible() {
        return emailErrorPopup.style.display === 'block';
    }
});
*/