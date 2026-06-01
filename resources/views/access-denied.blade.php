@extends('layout.app')

@section('title', 'Access Denied')

@section('styles')
<style>
    .access-denied-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        height: calc(100vh - 150px);
        padding: 20px;
    }
    .access-denied-card {
        background: #fee2e2;
        border: 1px solid #fecaca;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05), 0 2px 10px rgba(0, 0, 0, 0.02);
        padding: 50px 40px;
        max-width: 500px;
        width: 100%;
        text-align: center;
        animation: fadeIn 0.4s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .access-denied-icon {
        width: 80px;
        height: 80px;
        background: #ffffff;
        color: #ef4444;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin: 0 auto 24px;
        box-shadow: 0 4px 6px rgba(220, 38, 38, 0.1);
    }
    .access-denied-title {
        color: #7f1d1d;
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 12px;
    }
    .access-denied-message {
        color: #991b1b;
        font-size: 16px;
        line-height: 1.6;
        margin: 0 0 30px;
    }
    .access-denied-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #ef4444;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        border: none;
    }
    .access-denied-btn:hover {
        background: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="page-container">
    <div class="access-denied-wrapper">
        <div class="access-denied-card">
            <div class="access-denied-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h2 class="access-denied-title">Access Denied</h2>
            <p class="access-denied-message">
                You don't have the necessary permissions to view this page or perform this action. If you believe this is an error, please contact the administrator.
            </p>
            <a href="{{ route('Dashboard') }}" class="access-denied-btn">
                <i class="fas fa-home"></i> Return to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection