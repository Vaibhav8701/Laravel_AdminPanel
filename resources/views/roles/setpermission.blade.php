@extends('layout.app')

@section('title', 'Set Role Permissions')

@section('styles')

@endsection
    <link rel="stylesheet" href="{{ asset('assets/css/roles/setpermissions.css') }}">
@section('content')
@if (!has_permission('roles.setpermission'))
    <script>window.location.href = "{{ route('access.denied') }}";</script>
@else
<div class="page-container">

    {{-- Flash Messages
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <i class="fas fa-lock-open"></i>
            <h2>Set Permissions for {{ $role->name }}</h2>
        </div>
        <a href="{{ route('roles.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Roles
        </a>
    </div>

    <div class="data-card">
        <div class="card-body">
            <form method="POST" action="{{ route('roles.savepermissions', $role->id) }}" id="permissionForm">
                @csrf

                <div class="search-box">
                    <input type="text" id="searchBox" placeholder="Search modules or permissions..." onkeyup="filterPermissions()">
                </div>

                <div class="permission-container">
                    @if(!empty($moduleHierarchy))
                        @foreach($moduleHierarchy as $parent)
                            <div class="parent-module" data-module="{{ strtolower($parent['name']) }}">
                                {{-- Parent Checkbox --}}
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="parent_{{ $parent['id'] }}" onchange="toggleParent({{ $parent['id'] }})">
                                    <label class="form-check-label" for="parent_{{ $parent['id'] }}">
                                        <h5>{{ $parent['name'] }}</h5>
                                    </label>
                                </div>

                                @if(!empty($parent['permissions']) || !empty($parent['children']))
                                    <div style="margin-left: 24px;">
                                        {{-- Parent Permissions --}}
                                        @foreach($parent['permissions'] ?? [] as $perm)
                                            <div class="form-check permission-item" data-permission="{{ strtolower($perm['permission_name']) }}">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm['id'] }}" id="perm_{{ $perm['id'] }}" data-parent="{{ $parent['id'] }}" {{ in_array($perm['id'], $assignedPermissions ?? []) ? 'checked' : '' }} onchange="updateParent({{ $parent['id'] }})">
                                                <label class="form-check-label" for="perm_{{ $perm['id'] }}">
                                                    {{ $perm['permission_name'] }}
                                                </label>
                                            </div>
                                        @endforeach

                                        {{-- Child Modules --}}
                                        @foreach($parent['children'] ?? [] as $child)
                                            <div class="child-module" data-module="{{ strtolower($child['name']) }}">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="child_{{ $child['id'] }}" data-parent="{{ $parent['id'] }}" onchange="toggleChild({{ $child['id'] }}, {{ $parent['id'] }})">
                                                    <label class="form-check-label" for="child_{{ $child['id'] }}">
                                                        <strong>{{ $child['name'] }}</strong>
                                                    </label>
                                                </div>

                                                @foreach($child['permissions'] ?? [] as $perm)
                                                    <div class="form-check nested-permission" data-permission="{{ strtolower($perm['permission_name']) }}">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm['id'] }}" id="perm_{{ $perm['id'] }}" data-parent="{{ $parent['id'] }}" data-child="{{ $child['id'] }}" {{ in_array($perm['id'], $assignedPermissions ?? []) ? 'checked' : '' }} onchange="updateChild({{ $child['id'] }}, {{ $parent['id'] }})">
                                                        <label class="form-check-label" for="perm_{{ $perm['id'] }}">
                                                            {{ $perm['permission_name'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-lock"></i>
                            <p>No permissions available.</p>
                        </div>
                    @endif
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>

    // Toggle parent module and all children
    function toggleParent(parentId) {
        const checkbox = document.getElementById(`parent_${parentId}`);
        const checked = checkbox.checked;

        // Toggle all direct permissions
        document.querySelectorAll(`input[data-parent="${parentId}"]:not([data-child])`).forEach(cb => {
            cb.checked = checked;
        });

        // Toggle all child modules
        document.querySelectorAll(`input[data-parent="${parentId}"][id^="child_"]`).forEach(childCb => {
            childCb.checked = checked;
            const childId = childCb.id.replace('child_', '');
            document.querySelectorAll(`input[data-child="${childId}"]`).forEach(cb => {
                cb.checked = checked;
            });
        });
    }

    // Toggle child module permissions
    function toggleChild(childId, parentId) {
        const checkbox = document.getElementById(`child_${childId}`);
        const checked = checkbox.checked;

        document.querySelectorAll(`input[data-child="${childId}"]`).forEach(cb => {
            cb.checked = checked;
        });

        updateParent(parentId);
    }

    // Update child checkbox state
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

    // Update parent checkbox state
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[id^="child_"]').forEach(childCb => {
            const childId = childCb.id.replace('child_', '');
            const parentId = childCb.getAttribute('data-parent');
            const allPerms = document.querySelectorAll(`input[data-child="${childId}"]`);
            if (allPerms.length > 0) {
                const checkedPerms = document.querySelectorAll(`input[data-child="${childId}"]:checked`);
                if (checkedPerms.length > 0 && checkedPerms.length < allPerms.length) {
                    childCb.indeterminate = true;
                } else if (checkedPerms.length === allPerms.length) {
                    childCb.checked = true;
                }
            }
        });

        document.querySelectorAll('[id^="parent_"]').forEach(parentCb => {
            const parentId = parentCb.id.replace('parent_', '');
            updateParent(parentId);
        });
    });

    // Filter search
    function filterPermissions() {
        const search = document.getElementById('searchBox').value.toLowerCase();

        document.querySelectorAll('.parent-module').forEach(parent => {
            const parentName = parent.getAttribute('data-module');
            let parentVisible = parentName.includes(search);
            let hasVisible = false;

            parent.querySelectorAll('.child-module, .permission-item').forEach(item => {
                const name = item.getAttribute('data-module') || item.getAttribute('data-permission');
                if (search === '' || name.includes(search) || parentVisible) {
                    item.style.display = 'block';
                    hasVisible = true;
                } else {
                    item.style.display = 'none';
                }
            });

            parent.style.display = (search === '' || parentVisible || hasVisible) ? 'block' : 'none';
        });
    }
</script>

@endif
@endsection