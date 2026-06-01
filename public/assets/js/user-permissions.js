// Toggle a parent module and all related child/permission checkboxes.
function toggleParent(parentId) {
    const checkbox = document.getElementById(`parent_${parentId}`);
    const checked = checkbox.checked;

    document.querySelectorAll(`input[data-parent="${parentId}"]:not([data-child])`).forEach(cb => {
        cb.checked = checked;
    });

    document.querySelectorAll(`input[data-parent="${parentId}"][id^="child_"]`).forEach(childCb => {
        childCb.checked = checked;
        childCb.indeterminate = false;

        const childId = childCb.id.replace("child_", "");
        document.querySelectorAll(`input[data-child="${childId}"]`).forEach(cb => {
            cb.checked = checked;
        });
    });
}

function toggleChild(childId, parentId) {
    const checkbox = document.getElementById(`child_${childId}`);
    const checked = checkbox.checked;

    document.querySelectorAll(`input[data-child="${childId}"]`).forEach(cb => {
        cb.checked = checked;
    });

    updateParent(parentId);
}

function updateChild(childId, parentId) {
    const childCheckbox = document.getElementById(`child_${childId}`);
    const allPerms = document.querySelectorAll(`input[data-child="${childId}"]`);
    const checkedPerms = document.querySelectorAll(`input[data-child="${childId}"]:checked`);

    if (checkedPerms.length === 0) {
        childCheckbox.checked = false;
        childCheckbox.indeterminate = false;
    } else if (checkedPerms.length === allPerms.length) {
        childCheckbox.checked = true;
        childCheckbox.indeterminate = false;
    } else {
        childCheckbox.checked = false;
        childCheckbox.indeterminate = true;
    }

    updateParent(parentId);
}

function updateParent(parentId) {
    const parentCheckbox = document.getElementById(`parent_${parentId}`);
    const allItems = document.querySelectorAll(`input[data-parent="${parentId}"]`);
    const checkedItems = document.querySelectorAll(`input[data-parent="${parentId}"]:checked`);

    if (checkedItems.length === 0) {
        parentCheckbox.checked = false;
        parentCheckbox.indeterminate = false;
    } else if (checkedItems.length === allItems.length) {
        parentCheckbox.checked = true;
        parentCheckbox.indeterminate = false;
    } else {
        parentCheckbox.checked = false;
        parentCheckbox.indeterminate = true;
    }
}

function filterPermissions() {
    const search = document.getElementById("searchBox").value.toLowerCase();

    document.querySelectorAll(".parent-module").forEach(parent => {
        const parentName = parent.getAttribute("data-module");
        let parentVisible = parentName.includes(search);
        let hasVisible = false;

        parent.querySelectorAll(".child-module, .permission-item").forEach(item => {
            const name = item.getAttribute("data-module") || item.getAttribute("data-permission");
            if (search === "" || name.includes(search) || parentVisible) {
                item.style.display = "block";
                hasVisible = true;
            } else {
                item.style.display = "none";
            }
        });

        parent.style.display = (search === "" || parentVisible || hasVisible) ? "block" : "none";
    });
}

// Initialize checkbox tree state to reflect already-assigned permissions.
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[id^="child_"]').forEach(childCb => {
        const childId = childCb.id.replace("child_", "");
        const parentId = childCb.getAttribute("data-parent");
        const allPerms = document.querySelectorAll(`input[data-child="${childId}"]`);

        if (allPerms.length > 0) {
            const checkedPerms = document.querySelectorAll(`input[data-child="${childId}"]:checked`);
            if (checkedPerms.length > 0 && checkedPerms.length < allPerms.length) {
                childCb.indeterminate = true;
            } else if (checkedPerms.length === allPerms.length) {
                childCb.checked = true;
            }
        }

        if (parentId) {
            updateParent(parentId);
        }
    });

    document.querySelectorAll('[id^="parent_"]').forEach(parentCb => {
        const parentId = parentCb.id.replace("parent_", "");
        updateParent(parentId);
    });
});
