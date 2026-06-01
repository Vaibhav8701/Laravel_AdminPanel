@extends('layout.app')

@section('title', 'Edit User')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f766e;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
        }

        .form-container {
            max-width: 600px;
            margin: 24px auto;
            padding: 0 24px;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary) 0%, #115e59 100%);
            padding: 28px;
            border-radius: 12px;
            margin-bottom: 28px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            color: white;
        }

        .form-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--line);
            padding: 32px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text);
            font-size: 14px;
        }

        .form-label .required {
            color: #dc2626;
            margin-left: 2px;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            transition: all 0.3s;
            background-color: white;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
            background-color: white;
        }

        .form-control::placeholder {
            color: var(--muted);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%230f766e' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }

        .button-group {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 32px;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            box-shadow: 0 2px 4px rgba(15, 118, 110, 0.15);
        }

        .btn-primary:hover {
            background: #115e59;
            box-shadow: 0 6px 16px rgba(15, 118, 110, 0.3);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: var(--text);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-danger i {
            font-size: 18px;
        }

        .form-text {
            display: block;
            margin-top: 6px;
            font-size: 13px;
            color: var(--muted);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 0 16px;
            }

            .form-card {
                padding: 20px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    @if (!has_permission('users.edit'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else
    <div class="form-container">
        <!-- Header -->
        <div class="form-header">
            <h1>
                <i class="fas fa-user-edit"></i> Edit User
            </h1>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form id="updateForm" action="{{ route('users.update', $user->id) }}" method="post">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        Name
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" placeholder="Enter user name" required>
                    @error('name')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Email
                        <span class="required">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" placeholder="Enter email address" required>
                    @error('email')
                        <small style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Phone
                        <span class="required">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="form-control" placeholder="Enter phone number">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Role
                        <span class="required">*</span>
                    </label>
                    <select name="role_id" class="form-select" required>
                        <option value="">Select a role</option>
                        @if(!empty($roles))
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ ($currentRoleId == $role->id) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Password
                        <span style="color: #999;">(Optional)</span>
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Enter new password">
                    <small class="form-text">Leave blank to keep the existing password</small>
                </div>
            </form>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" form="updateForm" class="btn btn-primary">
                <i class="fas fa-check"></i> Update User
            </button>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="{{ asset('assets/js/edit-user.js') }}"></script>
@endsection