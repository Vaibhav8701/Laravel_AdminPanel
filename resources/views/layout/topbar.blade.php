<div class="topbar-content">
    <div class="topbar-left">
        <h2 class="topbar-title">@yield('title', 'Dashboard')</h2>
    </div>

    <div class="topbar-right">
        <div class="topbar-actions">
            <button class="topbar-icon-btn" title="Messages">
                <i class="fas fa-envelope"></i>
            </button>
            <button class="topbar-icon-btn" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="topbar-badge">3</span>
            </button>

            <div class="topbar-divider"></div>

            <div class="topbar-user-menu">
                <button class="topbar-user-btn" onclick="toggleUserMenu(this)">
                    <div class="topbar-user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="topbar-user-info">
                        <div class="topbar-user-name">{{ auth()->user()->name }}</div>
                        <div class="topbar-user-role">Administrator</div>
                    </div>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="topbar-user-dropdown">
                    <a href="{{ route('profile.edit') }}" class="topbar-dropdown-item">
                        <i class="fas fa-user"></i> Profile
                    </a>
                    <a href="#" class="topbar-dropdown-item">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <a href="{{ route('password.change') }}" class="topbar-dropdown-item">
                        <i class="fas fa-lock"></i> Change Password
                    </a>
                    <div class="topbar-dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="topbar-dropdown-item topbar-dropdown-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleUserMenu(btn) {
        const dropdown = btn.nextElementSibling;
        dropdown.classList.toggle('active');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const userMenus = document.querySelectorAll('.topbar-user-menu');
        userMenus.forEach(menu => {
            if (!menu.contains(event.target)) {
                menu.querySelector('.topbar-user-dropdown').classList.remove('active');
            }
        });
    });
</script>










