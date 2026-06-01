@extends('layout.app')

@section('title', 'Create Role')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/roles/create.css') }}">
@endsection

@section('content')
    @if (!has_permission('roles.create'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else
    <div class="form-container">
        <!-- Header -->
        <div class="form-header">
            <h1>
                <i class="fas fa-crown-plus"></i> Create New Role
            </h1>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        Role Name
                        <span class="required">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}"
                           placeholder="Enter role name"
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              placeholder="Enter role description (optional)">{{ old('description') }}</textarea>
                    
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" form="createForm" class="btn btn-primary">
                <i class="fas fa-check"></i> Create Role
            </button>
        </div>
    </div>

    <script>
        // Add form ID for button click
        document.querySelector('form').id = 'createForm';
    </script>
    @endif
@endsection
