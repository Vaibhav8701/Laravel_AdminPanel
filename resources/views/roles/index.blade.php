@extends('layout.app')

@section('title', 'Roles')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/roles/index.css') }}">
@endsection

@section('content')
    @if (!has_permission('roles.index'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else
        <div class="page-container">
            <!-- Header -->
            <div class="page-header">
                <h1><i class="fas fa-crown"></i> Roles Management</h1>
                @if (has_permission('roles.create'))
                    <a href="{{ route('roles.create') }}" class="create-btn">
                        <i class="fas fa-plus"></i> Create Role
                    </a>
                @endif
            </div>

            <div class="data-card">
                <div class="table-wrapper">

                    @if($roles->count())
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Permission</th>
                                    <th style="width: 200px; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $index => $role)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td>
                                            <a href="{{ route('roles.setpermission', $role->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-lock"></i> Access
                                            </a>
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="actions-cell">
                                                @if (has_permission('roles.edit'))
                                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary"
                                                        title="Edit role">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                @endif
                                                @if (has_permission('roles.delete'))
                                                    <a href="{{ route('roles.delete', $role->id) }}"
                                                        onclick="return confirm('Are you sure you want to delete this role?');"
                                                        class="btn btn-sm btn-danger" title="Delete role">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <p>No roles found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection