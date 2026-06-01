@extends('layout.app')

@section('title', 'Modules')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/permission-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/module.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/module-mvcpopup.css') }}"> --}}

@endsection

@section('content')
    @if (!has_permission('modules.index'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else
    <div class="page-container">
        <!-- Header -->
        <div class="page-header">
            <h1><i class="fas fa-cube"></i> Modules Management</h1>
        </div>

        <div class="data-card">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th>Name</th>
                            <th>Parent ID</th>
                            @if (has_permission('modules.permissions'))
                            <th>Permission</th>
                            @endif
                            <th>Status</th>
                            {{-- <th>MVC</th> --}}
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($modules as $module)
                            <tr>
                                <td>{{ $module->id }}</td>
                                <td>{{ $module->name }}</td>

                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $module->parent_id == 0 ? '0' : $module->parent_id }}
                                    </span>
                                </td>

                                
                                @if (has_permission('modules.permissions'))
                                <td>
                                    <button class="btn btn-sm btn-permission"
                                        onclick="openPermissionModal({{ $module->id }}, '{{ $module->name }}')">
                                        <i class="fas fa-lock"></i> Permission
                                    </button>
                                </td>
                                @endif

                                <td>
                                    @if ($module->is_active == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>

                                {{-- <td>
                                    <button type="button" class="btn btn-sm btn-mvc"
                                        onclick="mvcConfirm({{ $module->id }}, '{{ $module->name }}')">
                                        <i class="fas fa-cog"></i> MVC
                                    </button>
                                </td> --}}

                                <td>
                                    <small style="color: var(--muted);">
                                        {{ $module->created_at ? $module->created_at->format('Y-m-d H:i') : '-' }}
                                    </small>
                                </td>

                                <td>
                                    <small style="color: var(--muted);">
                                        {{ $module->updated_at ? $module->updated_at->format('Y-m-d H:i') : '-' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align: center; color: var(--muted); padding: 40px 20px;">
                                    <i class="fas fa-box"
                                        style="font-size: 48px; opacity: 0.3; margin-bottom: 16px; display: block;"></i>
                                    <p>No modules found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Permission Modal --}}
    <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">
                        <i class="fas fa-lock"></i> Add Permissions
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="currentModuleId">
                    <input type="hidden" id="currentModuleName">
                    <div id="permissionsList">
                        <!-- Dynamic permission rows will be added here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="addMorePermission()">
                        <i class="fas fa-plus"></i> Add More
                    </button>
                    <button type="button" class="btn btn-success" onclick="savePermissions()">
                        <i class="fas fa-check"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')

    <!-- jQuery (Required by Bootstrap and other scripts) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 5 (Required for modal) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>

    <script>
        window.APP_BASE_URL = "{{ url('/') }}";
        window.CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

    <script src="{{ asset('assets/js/module-permissions.js') }}"></script>
    {{--
    <script src="{{ asset('assets/js/module-mvc.js') }}"></script> --}}

    {{-- Global Popup
    <div id="popupOverlay" class="d-none">
        <div id="popupBox">
            <div id="popupIcon">!</div>

            <h2 id="popupTitle">Are you sure?</h2>
            <p id="popupText">Do you want to create MVC?</p>

            <div id="popupButtons">
                <button id="btnYes">Yes, Select Table</button>
                <button id="btnNo">No, cancel!</button>
            </div>

            <div id="selectTableContainer" class="d-none" style="text-align:center; margin-top:12px;">
                <h2 style="font-size:18px; margin-bottom:8px;"></h2>
                <p id="selectTableText"></p>
                <select id="mvcTableSelect" style="width:70%; padding:8px; margin-bottom:12px;"></select>
                <div>
                    <button id="btnCreate" class="btn btn-primary btn-sm">Create</button>
                    <button id="btncancel" class="btn btn-secondary btn-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection