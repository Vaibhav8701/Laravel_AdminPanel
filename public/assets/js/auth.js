/**
 * Authentication Module
 * Handles authentication-related functionality like login, register, password reset
 */

/**
 * Handle login form submission
 * Validates form and submits credentials
 */
function handleLogin(e) {
    e.preventDefault();
    
    const form = e.target;
    const emailInput = form.querySelector('input[name="email"]');
    const passwordInput = form.querySelector('input[name="password"]');
    
    if (!emailInput || !passwordInput) {
        showToast('Form fields are missing. Please refresh the page.', 'error');
        return;
    }
    
    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();
    const rememberMe = form.querySelector('input[name="remember"]')?.checked || false;

    // Basic validation
    if (!email || !password) {
        showToast('Please enter email and password.', 'error');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showToast('Please enter a valid email address.', 'error');
        return;
    }

    // Submit form normally (Laravel will handle the rest)
    form.submit();
}

/**
 * Handle registration form submission
 * Validates form fields and submits registration
 */
function handleRegister(e) {
    e.preventDefault();
    
    const form = e.target;
    const name = form.querySelector('input[name="name"]')?.value.trim() || '';
    const email = form.querySelector('input[name="email"]')?.value.trim() || '';
    const password = form.querySelector('input[name="password"]')?.value || '';
    const passwordConfirm = form.querySelector('input[name="password_confirmation"]')?.value || '';

    console.log('Register form submit triggered');
    console.log('Validating:', { name: !!name, email: !!email, password: !!password, passwordConfirm: !!passwordConfirm });

    // Validation
    if (!name || !email || !password || !passwordConfirm) {
        showToast('Please fill in all fields.', 'error');
        console.warn('Validation failed: empty fields');
        return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showToast('Please enter a valid email address.', 'error');
        console.warn('Validation failed: invalid email');
        return;
    }

    // Password strength validation
    if (password.length < 8) {
        showToast('Password must be at least 8 characters long.', 'error');
        console.warn('Validation failed: password too short');
        return;
    }

    // Check password strength (at least 1 uppercase, 1 lowercase, 1 number)
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    if (!hasUpperCase || !hasLowerCase || !hasNumber) {
        showToast('Password must contain uppercase, lowercase, and number.', 'error');
        console.warn('Validation failed: weak password', { hasUpperCase, hasLowerCase, hasNumber });
        return;
    }

    // Password match validation
    if (password !== passwordConfirm) {
        showToast('Passwords do not match.', 'error');
        console.warn('Validation failed: passwords do not match');
        return;
    }

    // All validation passed, submit form
    console.log('All validation passed, submitting form');
    form.submit();
}

/**
 * Handle password reset request
 * Sends email to reset password
 */
function handlePasswordReset(e) {
    e.preventDefault();
    
    const form = e.target;
    const email = form.querySelector('input[type="email"]')?.value.trim() || '';

    // Validation
    if (!email) {
        showToast('Please enter your email address.', 'error');
        return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        showToast('Please enter a valid email address.', 'error');
        return;
    }

    // Submit form
    form.submit();
}

/**
 * Handle password update in reset form
 * Validates password change submission
 */
function handlePasswordUpdate(e) {
    e.preventDefault();
    
    const form = e.target;
    const password = form.querySelector('input[name="password"]')?.value || '';
    const passwordConfirm = form.querySelector('input[name="password_confirmation"]')?.value || '';

    // Validation
    if (!password || !passwordConfirm) {
        showToast('Please enter password and confirmation.', 'error');
        return;
    }

    // Password strength validation
    if (password.length < 8) {
        showToast('Password must be at least 8 characters long.', 'error');
        return;
    }

    // Check password strength
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    
    if (!hasUpperCase || !hasLowerCase || !hasNumber) {
        showToast('Password must contain uppercase, lowercase, and number.', 'error');
        return;
    }

    // Check matching
    if (password !== passwordConfirm) {
        showToast('Passwords do not match.', 'error');
        return;
    }

    // Submit form
    form.submit();
}

/**
 * Enable/disable password visibility toggle
 * Shows or hides password text
 */
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    const type = input.type === 'password' ? 'text' : 'password';
    input.type = type;

    // Update toggle button icon if it exists
    const wrapper = input.closest('.password-input-wrapper');
    if (wrapper) {
        const toggleBtn = wrapper.querySelector('.password-toggle-btn');
        if (toggleBtn) {
            const icon = toggleBtn.querySelector('.eye-icon');
            if (icon) {
                icon.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
            }
        }
    }
}

/**
 * Setup password visibility toggles
 * Initializes all password visibility buttons
 * Skips inputs that already have toggle buttons in the wrapper
 */
function setupPasswordToggles() {
    const inputs = document.querySelectorAll('input[type="password"][data-toggle]');
    
    inputs.forEach(input => {
        // Check if toggle button already exists in wrapper
        const wrapper = input.closest('.password-input-wrapper');
        if (wrapper && wrapper.querySelector('.password-toggle-btn')) {
            // Button already exists, skip
            return;
        }
        
        // Create toggle button for inputs without wrapper
        if (!wrapper) {
            const toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'password-toggle';
            toggle.textContent = '👁️';
            toggle.style.cssText = 'border:none;background:none;cursor:pointer;margin-left:-30px;padding-right:8px;';
            
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                input.type = input.type === 'password' ? 'text' : 'password';
                toggle.textContent = input.type === 'password' ? '👁️' : '👁️‍🗨️';
            });

            input.parentElement.appendChild(toggle);
        }
    });
}

/**
 * Check if user is already logged in (for redirect logic)
 * 
 * @returns {boolean} - True if user is logged in
 */
function isUserLoggedIn() {
    const metaToken = document.querySelector('meta[name="csrf-token"]');
    return !!metaToken;
}

/**
 * Perform logout action
 * Submits logout form if available
 */
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        const form = document.querySelector('form[action*="logout"]');
        if (form) {
            form.submit();
        } else {
            console.error('Logout form not found');
        }
    }
}

/**
 * Show toast notification
 * 
 * @param {string} message - The message to display
 * @param {string} type - The type of notification (success, error, info)
 */
function showToast(message, type = 'info') {
    if (window.toast && typeof window.toast === 'function') {
        window.toast(message, type);
    } else {
        console.log(`[${type.toUpperCase()}] ${message}`);
    }
}

/**
 * Display form errors from server response
 * 
 * @param {object} errors - Object with field names as keys and error messages as values
 */
function displayFormErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('.form-error').forEach(el => {
        el.classList.remove('form-error');
    });

    // Display new errors
    Object.keys(errors).forEach(fieldName => {
        const input = document.querySelector(`input[name="${fieldName}"], textarea[name="${fieldName}"], select[name="${fieldName}"]`);
        if (input) {
            input.classList.add('form-error');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'form-error-message';
            errorDiv.textContent = errors[fieldName][0];
            input.parentElement.appendChild(errorDiv);
        }
    });
}

/**
 * Initialize authentication module
 * Sets up event listeners and handlers
 */
function initializeAuthModule() {
    // Setup password visibility toggles
    setupPasswordToggles();
    
    // Note: Form submission handlers are attached via onsubmit attributes in HTML
    // to avoid conflicts with multiple event listeners
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', initializeAuthModule);
