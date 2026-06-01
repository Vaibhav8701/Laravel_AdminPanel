@extends('layout.app')

@section('title', 'Change Password')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/profile-password.css') }}">
@endsection

@section('content')
    <div class="page-container">
        <!-- Header -->
        <div class="page-header">
            <h1><i class="fas fa-lock"></i> Change Password</h1>
        </div>

        <div class="profile-card">
            <div class="profile-card-body">
                <div class="form-info">
                    <i class="fas fa-info-circle"></i> Enter your current password and a new password to change it.
                </div>

                <form id="changePasswordForm" action="{{ route('password.update') }}" method="post">
                    @csrf

                    <!-- Current Password -->
                    <div class="form-group">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-key"></i> Current Password
                        </label>
                        <input 
                            type="password" 
                            id="current_password" 
                            name="current_password" 
                            class="form-control @error('current_password') is-invalid @enderror"
                            placeholder="Enter your current password"
                            required
                        >
                        @error('current_password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-lock"></i> New Password
                        </label>
                        <input 
                            type="password" 
                            id="new_password" 
                            name="new_password" 
                            class="form-control @error('new_password') is-invalid @enderror"
                            placeholder="Enter your new password"
                            required
                        >
                        @error('new_password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="new_password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i> Confirm New Password
                        </label>
                        <input 
                            type="password" 
                            id="new_password_confirmation" 
                            name="new_password_confirmation" 
                            class="form-control"
                            placeholder="Confirm your new password"
                            required
                        >
                    </div>

                    <!-- Buttons -->
                    <div class="btn-group">
                        <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
