@extends('layout.app')

@section('title', 'My Profile')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0f766e;
            --text: #0f172a;
            --muted: #64748b;
            --line: #e2e8f0;
        }
        
        .page-container { max-width: 800px; margin: 0 auto; padding: 24px 0; }
        .page-header {
            background: linear-gradient(135deg, var(--primary) 0%, #115e59 100%);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 28px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            color: white;
        }
        .page-header h1 { margin: 0; font-size: 28px; font-weight: 700; }
        .profile-card { background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border: 1px solid var(--line); }
        .profile-card-body { padding: 28px; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text); font-size: 14px; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid var(--line); border-radius: 8px; font-size: 14px; transition: all 0.3s; font-family: inherit; }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1); }
        .form-control:disabled { background: #f8fafc; color: var(--muted); }
        .btn-group { display: flex; gap: 12px; justify-content: flex-end; margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--line); }
        .btn { padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #115e59; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(15, 118, 110, 0.3); }
        .btn-secondary { background: var(--muted); color: white; }
        .btn-secondary:hover { background: #475569; transform: translateY(-2px); }
    </style>
@endsection

@section('content')
    @if (!has_permission('profile.edit'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else
    <div class="page-container">
        <!-- Header -->
        <div class="page-header">
            <h1><i class="fas fa-user-circle"></i> My Profile</h1>
        </div>

        <div class="profile-card">
            <div class="profile-card-body">
                <form id="profileForm" action="{{ route('profile.update', $user->id) }}" method="post">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Name <span style="color: #dc2626;">*</span></label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ $user->email }}"
                            readonly
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone <span style="color: #dc2626;">*</span></label>
                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $user->phone) }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password (Optional)</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Leave blank to keep old password"
                        >
                    </div>
                </form>

                <div class="btn-group">
                    <a href="{{ route('Dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" form="profileForm" class="btn btn-primary">Update Profile</button>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection
