
let rowCount = 0;
let moduleName = '';
const APP_BASE = (typeof window !== 'undefined' && window.APP_BASE_URL) ? window.APP_BASE_URL : window.location.origin;
const CSRF_TOKEN = (typeof window !== 'undefined' && window.CSRF_TOKEN) ? window.CSRF_TOKEN : '';


function openPermissionModal(moduleId, name) {
    moduleName = name;
    document.getElementById('currentModuleId').value = moduleId;
    document.getElementById('currentModuleName').value = name;
    
    // Clear previous rows
    document.getElementById('permissionsList').innerHTML = '';
    rowCount = 0;

    // open modal immediately
    let modal = new bootstrap.Modal(document.getElementById('permissionModal'));
    modal.show();

    // fetch existing permissions; fallback to defaults
    $.ajax({
        url: APP_BASE + '/modules/permissions/get',
        type: 'GET',
        data: { module_id: moduleId },
        success: function (response) {
            if (response && response.success && Array.isArray(response.permissions) && response.permissions.length) {
                response.permissions.forEach(function (p) { addRow(p.permission_name); });
            } else {
                addRow(name + '-create');
                addRow(name + '-index');
                addRow(name + '-edit');
                addRow(name + '-delete');
            }
        },
        error: function () {
            addRow(name + '-create');
            addRow(name + '-index');
            addRow(name + '-edit');
            addRow(name + '-delete');
        }
    });

}

// Add a new permission row
function addRow(permissionName = '') {
    const rowId = 'row_' + rowCount++;
    const html = `
        <div class="permission-row mb-3" id="${rowId}">
            <div class="row">
                <div class="col-4">
                    <label class="fw-bold">Permission Name</label>
                </div>
                <div class="col-5">
                    <input type="text" class="form-control permission-input" name="permissions[]" value="${permissionName}" placeholder="${moduleName}-action">
                </div>
                <div class="col-3">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteRow('${rowId}')">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    `;
    document.getElementById('permissionsList').innerHTML += html;
}


function addMorePermission() {
    addRow('');
}


function deleteRow(rowId) {
    document.getElementById(rowId).remove();
}


function savePermissions() {

    const moduleId = document.getElementById('currentModuleId').value;
    const inputs = document.querySelectorAll('#permissionsList .permission-input');
    let permissions = [];
    

    inputs.forEach(input => {
        if (input.value.trim() !== '') {
            permissions.push(input.value.trim());
        }
    });

    if (permissions.length === 0) {
        showToast('Please add at least one permission', 'warning', 3000);
        return;
    }
    console.log(moduleId);
    console.log(permissions);

    
    // AJAX request
    $.ajax({
        url: APP_BASE + '/modules/permissions/save',
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        },
        data: {
            module_id: moduleId,
            permissions: permissions,
            _token: CSRF_TOKEN
        },
    
        success: function (response) {
            if (response.success) {
                showToast('Permissions saved successfully!', 'success', 3000);
                setTimeout(() => {
                    bootstrap.Modal.getInstance(
                        document.getElementById('permissionModal')
                    ).hide();
                }, 1000);
            } else {
                showToast(response.message || 'Error saving permissions', 'error', 3000);
            }
        },
        error: function () {
            showToast('Server error. Please try again.', 'error', 3000);
        }
    });
}

// Toast notification helper
function showToast(message, type = 'info', duration = 3000) {
    // Check if global toast system exists (from toast.js)
    if (window.toastSystem && typeof window.toastSystem.show === 'function') {
        window.toastSystem.show(message, type, duration);
    } else if (window.showGlobalToast && typeof window.showGlobalToast === 'function') {
        window.showGlobalToast(message, type, duration);
    } else {
        // Fallback to console and alert
        console.log(`[${type.toUpperCase()}] ${message}`);
        alert(message);
    }
}
