
@extends('layout.app')

@section('title', 'Edit Role')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/roles/edit.css') }}">
    
@endsection

@section('content')
@if (!has_permission('roles.edit'))
    <script>window.location.href = "{{ route('access.denied') }}";</script>
@else
<div class="form-container">

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

    <div class="form-card">
        <div class="form-header">
            <i class="fas fa-crown"></i>
            <h2>Edit Role</h2>
        </div>
        <div class="form-card-body">

            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label class="form-label">
                        Name <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $role->name) }}"
                           placeholder="Enter role name"
                           required>
                    @error('name')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="4"
                              placeholder="Enter role description">{{ old('description', $role->description) }}</textarea>
                    @error('description')
                        <small style="color: var(--danger);">{{ $message }}</small>
                    @enderror
                </div>

                <div class="button-group">
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Role
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>
@endif
@endsection
