/**
 * Toast notification system
 */

function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    
    if (!container) {
        console.warn('Toast container not found');
        return;
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;

    // Icon mapping
    const icons = {
        success: '✓',
        error: '✕',
        warning: '⚠',
        info: 'ℹ'
    };

    const icon = icons[type] || icons.info;

    toast.innerHTML = `
        <div class="toast-content">
            <span class="toast-icon">${icon}</span>
            <div class="toast-message">${escapeHtml(message)}</div>
        </div>
        <button class="toast-close" onclick="this.parentElement.remove()">×</button>
    `;

    container.appendChild(toast);

    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            toast.classList.add('removing');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, duration);
    }

    return toast;
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

/**
 * Show success toast
 */
function showSuccess(message, duration = 5000) {
    return showToast(message, 'success', duration);
}

/**
 * Show error toast
 */
function showError(message, duration = 5000) {
    return showToast(message, 'error', duration);
}

/**
 * Show warning toast
 */
function showWarning(message, duration = 5000) {
    return showToast(message, 'warning', duration);
}

/**
 * Show info toast
 */
function showInfo(message, duration = 5000) {
    return showToast(message, 'info', duration);
}
