@extends('layout.app')

@section('title', 'Manage User Permissions')

@section('styles')
<style>
    :root {
        --primary: #0f766e;
        --primary-hover: #115e59;
        --text: #0f172a;
        --muted: #64748b;
        --line: #e2e8f0;
        --success: #16a34a;
        --warning: #ea8c55;
        --danger: #dc2626;
    }

    .page-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 24px;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
        color: white;
        padding: 28px 28px;
        border-radius: 12px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header h2 {
        margin: 0;
        font-size: 26px;
        font-weight: 700;
    }

    .page-header i {
        font-size: 28px;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
        text-decoration: none;
        font-size: 14px;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-2px);
    }

    .data-card {
        background: white;
        border: 1px solid var(--line);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .card-body {
        padding: 28px;
    }

    .search-box {
        margin-bottom: 28px;
    }

    .search-box input {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid var(--line);
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
    }

    .permission-container {
        max-width: 100%;
    }

    .parent-module {
        margin-bottom: 28px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid var(--primary);
    }

    .form-check {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        margin: 0;
        cursor: pointer;
        accent-color: var(--primary);
        border: 1px solid var(--line);
        border-radius: 4px;
    }

    .form-check-input:checked {
        background: var(--primary);
        border-color: var(--primary);
    }

    .form-check-label {
        margin: 0;
        cursor: pointer;
        font-size: 14px;
        user-select: none;
    }

    .form-check-label h5 {
        margin: 0;
        display: inline;
        font-weight: 700;
        color: var(--text);
    }

    .permission-item {
        font-size: 13px;
        color: var(--muted);
    }

    .child-module {
        margin-left: 24px;
        margin-bottom: 16px;
        padding: 12px 16px;
        background: white;
        border-left: 3px solid var(--primary-hover);
        border-radius: 4px;
    }

    .child-module .form-check-label strong {
        color: var(--text);
    }

    .nested-permission {
        margin-left: 34px;
        font-size: 13px;
    }

    .button-group {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid var(--line);
    }

    .btn {
        padding: 12px 28px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .btn-primary:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .alert {
        border-radius: 8px;
        border: none;
        padding: 12px 16px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-left: 4px solid var(--success);
    }

    .alert-danger {
        background: #fef2f2;
        color: #7f1d1d;
        border-left: 4px solid var(--danger);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--muted);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        color: var(--line);
        display: block;
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 12px;
        }

        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 20px;
        }

        .page-header-left {
            flex-direction: column;
        }

        .card-body {
            padding: 16px;
        }

        .child-module {
            margin-left: 16px;
        }

        .nested-permission {
            margin-left: 26px;
        }

        .button-group {
            flex-direction: column-reverse;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
    @if (!has_permission('users.permissions'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else

<div class="page-container">

    {{-- Flash Messages --}}
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
    @endif

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-header-left">
            <i class="fas fa-shield-alt"></i>
            <h2>Manage Permissions for {{ $user->name }}</h2>
        </div>
        <a href="{{ route('users.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="data-card">
        <div class="card-body">
            <form method="post" action="{{ route('users.savePermissions', $user->id) }}" id="permissionForm">
                @csrf

                <div class="search-box">
                    <input type="text" id="searchBox" placeholder="Search for modules or permissions..." onkeyup="filterPermissions()">
                </div>

                <div class="permission-container">

                    @if(!empty($moduleHierarchy))
                        @foreach($moduleHierarchy as $parent)
                            <div class="mb-4 parent-module" data-module="{{ strtolower($parent['name']) }}">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="parent_{{ $parent['id'] }}" onchange="toggleParent({{ $parent['id'] }})">
                                    <label class="form-check-label" for="parent_{{ $parent['id'] }}">
                                        <h5 class="fw-bold text-dark d-inline mb-0">{{ $parent['name'] }}</h5>
                                    </label>
                                </div>

                                @if(!empty($parent['permissions']) || !empty($parent['children']))
                                    <div class="ms-4 ps-3 border-start border-2 border-dark">

                                        @if(!empty($parent['permissions']))
                                            @foreach($parent['permissions'] as $perm)
                                                <div class="form-check mb-2 permission-item" data-permission="{{ strtolower($perm['permission_name']) }}">
                                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm['id'] }}" id="perm_{{ $perm['id'] }}" data-parent="{{ $parent['id'] }}" {{ in_array($perm['id'], $assignedPermissions ?? []) ? 'checked' : '' }} onchange="updateParent({{ $parent['id'] }})">
                                                    <label class="form-check-label" for="perm_{{ $perm['id'] }}">{{ $perm['permission_name'] }}</label>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if(!empty($parent['children']))
                                            @foreach($parent['children'] as $child)
                                                <div class="mb-3 ms-3 ps-3 border-start border-2 border-secondary child-module" data-module="{{ strtolower($child['name']) }}">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="child_{{ $child['id'] }}" data-parent="{{ $parent['id'] }}" onchange="toggleChild({{ $child['id'] }}, {{ $parent['id'] }})">
                                                        <label class="form-check-label" for="child_{{ $child['id'] }}"><strong>{{ $child['name'] }}</strong></label>
                                                    </div>

                                                    @if(!empty($child['permissions']))
                                                        @foreach($child['permissions'] as $perm)
                                                            <div class="form-check mb-2 permission-item" data-permission="{{ strtolower($perm['permission_name']) }}">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm['id'] }}" id="perm_{{ $perm['id'] }}" data-parent="{{ $parent['id'] }}" data-child="{{ $child['id'] }}" {{ in_array($perm['id'], $assignedPermissions ?? []) ? 'checked' : '' }} onchange="updateChild({{ $child['id'] }}, {{ $parent['id'] }})">
                                                                <label class="form-check-label" for="perm_{{ $perm['id'] }}">{{ $perm['permission_name'] }}</label>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                </div>
                                            @endforeach
                                        @endif

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
    @endif

@endsection

@section('scripts')
<script src="{{ asset('assets/js/user-permissions.js') }}"></script>
@endsection
