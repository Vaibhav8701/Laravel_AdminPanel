<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    <!-- Global CSS Assets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forms.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/topbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/toast/css/toast.css') }}">

    @yield('styles')

</head>
<body>
<div class="toast-container" id="toastContainer"></div>

<div class="layout">
    <aside class="sidebar-wrap">
        @include('layout.sidebar')
    </aside>

    <div class="app">
        <header class="topbar-wrap">
            @include('layout.topbar')
        </header>

        <main class="main">
            @yield('content')
        </main>
    </div>
</div>

<!-- Global JS Assets -->
<script src="{{ asset('assets/js/forms.js') }}"></script>
<script src="{{ asset('assets/toast/js/toast.js') }}"></script>

<!-- Global Toast Notifications -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Display session success message
        @if (session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        // Display session error message
        @if (session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        // Display validation errors
        @if ($errors->any())
            @foreach($errors->all() as $error)
                showToast('{{ $error }}', 'error');
            @endforeach
        @endif
    });
</script>

@yield('scripts')
</body>
</html>