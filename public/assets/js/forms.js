/**
 * Forms Utilities Module
 * Provides shared form handling functions across the application
 */

/**
 * Validate a form field based on type and value
 * 
 * @param {HTMLElement} field - The form field element to validate
 * @returns {object} - { isValid: boolean, message: string }
 */
function validateField(field) {
    const type = field.type;
    const value = field.value.trim();
    const required = field.hasAttribute('required');
    const minLength = field.getAttribute('minlength');
    const pattern = field.getAttribute('pattern');

    // Check required
    if (required && !value) {
        return {
            isValid: false,
            message: 'This field is required.'
        };
    }

    // Check minimum length
    if (minLength && value.length < parseInt(minLength)) {
        return {
            isValid: false,
            message: `Minimum ${minLength} characters required.`
        };
    }

    // Type-specific validations
    if (type === 'email') {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (value && !emailRegex.test(value)) {
            return {
                isValid: false,
                message: 'Please enter a valid email address.'
            };
        }
    }

    if (type === 'password') {
        if (value && value.length < 8) {
            return {
                isValid: false,
                message: 'Password must be at least 8 characters.'
            };
        }
    }

    if (type === 'url') {
        try {
            new URL(value);
        } catch (e) {
            return {
                isValid: false,
                message: 'Please enter a valid URL.'
            };
        }
    }

    // Pattern validation
    if (pattern && value) {
        const regex = new RegExp(`^${pattern}$`);
        if (!regex.test(value)) {
            return {
                isValid: false,
                message: 'Please enter a valid value.'
            };
        }
    }

    return {
        isValid: true,
        message: ''
    };
}

/**
 * Display error message for a field
 * 
 * @param {HTMLElement} field - The form field element
 * @param {string} message - The error message to display
 */
function showFieldError(field, message) {
    field.classList.add('form-error');
    
    // Remove existing error message
    const existingError = field.parentElement.querySelector('.form-feedback');
    if (existingError) {
        existingError.remove();
    }

    // Add new error message
    const errorEl = document.createElement('div');
    errorEl.className = 'form-feedback';
    errorEl.textContent = message;
    field.parentElement.appendChild(errorEl);
}

/**
 * Clear error message for a field
 * 
 * @param {HTMLElement} field - The form field element
 */
function clearFieldError(field) {
    field.classList.remove('form-error');
    
    const errorEl = field.parentElement.querySelector('.form-feedback');
    if (errorEl) {
        errorEl.remove();
    }
}

/**
 * Validate entire form
 * 
 * @param {HTMLFormElement} form - The form element to validate
 * @returns {boolean} - True if form is valid, false otherwise
 */
function validateForm(form) {
    const fields = form.querySelectorAll('[required], [type="email"], [type="password"], [type="url"]');
    let isValid = true;

    fields.forEach(field => {
        const validation = validateField(field);
        if (!validation.isValid) {
            showFieldError(field, validation.message);
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });

    return isValid;
}

/**
 * Reset form to initial state
 * 
 * @param {HTMLFormElement} form - The form element to reset
 */
function resetFormErrors(form) {
    const fields = form.querySelectorAll('.form-error');
    fields.forEach(field => {
        clearFieldError(field);
    });
}

/**
 * Add real-time validation to form fields
 * 
 * @param {HTMLFormElement} form - The form element
 */
function enableRealTimeValidation(form) {
    const fields = form.querySelectorAll('input, textarea, select');
    
    fields.forEach(field => {
        // Validate on blur
        field.addEventListener('blur', function() {
            const validation = validateField(this);
            if (!validation.isValid) {
                showFieldError(this, validation.message);
            } else {
                clearFieldError(this);
            }
        });

        // Clear error on input
        field.addEventListener('input', function() {
            if (this.classList.contains('form-error')) {
                clearFieldError(this);
            }
        });
    });
}

/**
 * Submit form via AJAX
 * 
 * @param {HTMLFormElement} form - The form to submit
 * @param {function} onSuccess - Callback function on successful submission
 * @param {function} onError - Callback function on failed submission
 */
function submitFormAjax(form, onSuccess, onError) {
    // Validate form first
    if (!validateForm(form)) {
        console.log('Form validation failed');
        return;
    }

    const showLoader = function() {
        const loader = document.getElementById('form-loading');
        if (loader) loader.classList.add('show');
    };

    const hideLoader = function() {
        const loader = document.getElementById('form-loading');
        if (loader) loader.classList.remove('show');
    };

    showLoader();

    const formData = new FormData(form);
    const method = form.method.toUpperCase();
    const url = form.action;

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Form submission failed');
            });
        }
        return response.json();
    })
    .then(data => {
        hideLoader();
        if (onSuccess) onSuccess(data);
        showToast('Form submitted successfully.', 'success');
    })
    .catch(error => {
        hideLoader();
        console.error('Form submission error:', error);
        if (onError) {
            onError(error);
        } else {
            showToast(error.message || 'An error occurred during form submission.', 'error');
        }
    });
}

/**
 * Format date input value
 * 
 * @param {string} dateString - The date string to format
 * @param {string} format - The format pattern (e.g., 'YYYY-MM-DD', 'DD/MM/YYYY')
 * @returns {string} - The formatted date
 */
function formatDate(dateString, format = 'YYYY-MM-DD') {
    const date = new Date(dateString);
    
    if (isNaN(date.getTime())) {
        return '';
    }

    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return format
        .replace('YYYY', year)
        .replace('MM', month)
        .replace('DD', day)
        .replace('HH', hours)
        .replace('mm', minutes);
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
 * Initialize form utilities for all forms on the page
 */
function initializeFormUtilities() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        enableRealTimeValidation(form);
        
        form.addEventListener('submit', function(e) {
            // If form has AJAX submit flag, prevent default
            if (this.classList.contains('ajax-submit')) {
                e.preventDefault();
                submitFormAjax(this);
            } else {
                // Standard validation
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            }
        });
    });
}

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', initializeFormUtilities);
