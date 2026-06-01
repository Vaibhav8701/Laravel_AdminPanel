<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign Up</title>
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/toast/css/toast.css') }}">
    <style>
        :root {
            --primary: #0f766e;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
        }
    </style>
</head>

<body>
    <div id="toastContainer" class="toast-container"></div>

    <div class="auth-container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title">Create Account</h1>
                    <p class="auth-subtitle">Sign up to get started</p>
                </div>

                <form action="{{ route('signup') }}" method="POST" onsubmit="handleRegister(event)">
                    @csrf

                    <div class="auth-form-group">
                        <label for="name">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                            autofocus>
                        {{-- @error('name')<div class="auth-field-error-text">{{ $message }}</div>@enderror --}}
                    </div>

                    <div class="auth-form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autocomplete="email">
                        {{-- @error('email')<div class="auth-field-error-text">{{ $message }}</div>@enderror --}}
                    </div>

                    <div class="auth-form-group">
                        <label for="password">Password</label>
                        <div class="password-input-wrapper">
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                data-toggle>
                            <button type="button" class="password-toggle-btn"
                                onclick="togglePasswordVisibility('password')" tabindex="-1">
                                <span class="eye-icon">👁️</span>
                            </button>
                        </div>
                        {{-- @error('password')<div class="auth-field-error-text">{{ $message }}</div>@enderror --}}
                    </div>

                    <div class="auth-form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="password-input-wrapper">
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password" data-toggle>
                            <button type="button" class="password-toggle-btn"
                                onclick="togglePasswordVisibility('password_confirmation')" tabindex="-1">
                                <span class="eye-icon">👁️</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="auth-submit-btn">Create Account</button>
                </form>

                <div class="auth-footer">
                    <p class="auth-footer-text">
                        Already have an account?
                        <a href="{{ route('login') }}" class="auth-footer-link">Sign In</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/auth.js') }}"></script>
    <script src="{{ asset('assets/toast/js/toast.js') }}"></script>
    <script>
        // Show server-side validation errors and session messages as toasts
        document.addEventListener('DOMContentLoaded', function() {
            // Show session success message
            @if (session('success'))
                showToast('{{ session('success') }}', 'success');
            @endif

            // Show session error message
            @if (session('error'))
                showToast('{{ session('error') }}', 'error');
            @endif

            // Show validation errors
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast('{{ $error }}', 'error');
                @endforeach
            @endif
        });
    </script>
</body>

</html>
