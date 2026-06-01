{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> --}}

<div class="sidebar-container">
    <!-- Sidebar Header with Profile -->
    <div class="sidebar-header">
        <div class="sidebar-profile">
            <div class="sidebar-avatar">
                <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
            <div class="sidebar-profile-info">
                <div class="sidebar-profile-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-profile-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
    </div>

    <!-- Sidebar Search -->
    <input class="sidebar-search" type="text" placeholder="Search menu..." onkeyup="filterSidebarMenuItems(this.value)">
    
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        @php
            $menus = getMenus();
            
            // check active url
            $currentPath = trim(request()->path(), '/');
            
            $toPath = function ($url) {
                return trim(parse_url(url($url), PHP_URL_PATH), '/');
            };
            
            $isActiveUrl = function ($url) use ($currentPath, $toPath) {
                return $toPath($url) === $currentPath;
            };
            
            // check submenu active
            $menuHasActive = function ($menu) use (&$menuHasActive, $isActiveUrl){
                if (!empty($menu['url']) && $isActiveUrl($menu['url'])) {
                    return true;
                }

                if (!empty($menu['children']) && is_array($menu['children'])) {
                    foreach ($menu['children'] as $child) {
                        if ($menuHasActive($child)) {
                            return true;
                        }
                    }
                }
                return false;
            };
        @endphp

        @foreach ($menus as $menu)
            @php
                $hasChildren = !empty($menu['children']) && is_array($menu['children']) && count($menu['children']) > 0;
                $isActive = $menuHasActive($menu);
                $menuName = $menu['name'] ?? 'Menu';
                $menuIcon = $menu['icon'] ?? 'fas fa-link';
                $menuUrl = $menu['url'] ?? '#';
                $submenuId = 'menu_' . ($menu['id'] ?? 0);
            @endphp

            @if ($hasChildren)
                <div class="sidebar-menu-group">
                    <button type="button" class="sidebar-menu-toggle {{ $isActive ? 'active' : '' }}" onclick="toggleSidebarMenu(this)">
                        <i class="{{ $menuIcon }}"></i>
                        <span>{{ $menuName }}</span>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <div class="sidebar-submenu {{ $isActive ? 'active' : '' }}">
                        @foreach ($menu['children'] as $child)
                            @php
                                $childUrl = $child['url'] ?? '#';
                                $childName = $child['name'] ?? 'Menu';
                                $childIcon = $child['icon'] ?? 'fas fa-link';
                                $childIsActive = $isActiveUrl($childUrl);
                            @endphp
                            <a href="{{ url($childUrl) }}" class="sidebar-submenu-item {{ $childIsActive ? 'active' : '' }}" data-label="{{ strtolower($childName) }}">
                                <i class="{{ $childIcon }}"></i>
                                <span>{{ $childName }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <a href="{{ url($menuUrl) }}" class="sidebar-menu-item {{ $isActive ? 'active' : '' }}" data-label="{{ strtolower($menuName) }}">
                    <i class="{{ $menuIcon }}"></i>
                    <span>{{ $menuName }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
            @csrf
            <button type="submit" class="sidebar-logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

{{-- <script src="{{ asset('assets/js/jquery.js') }}"></script> --}}
<script>
    function filterSidebarMenuItems(searchTerm) {
        searchTerm = searchTerm.toLowerCase();
        const nav = document.querySelector('.sidebar-nav');
        const menuGroups = nav.querySelectorAll('.sidebar-menu-group, .sidebar-menu-item');
        
        menuGroups.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = '';
                // If it's a group, make sure submenu is visible if parent is visible
                if (item.classList.contains('sidebar-menu-group')) {
                    const submenu = item.querySelector('.sidebar-submenu');
                    if (submenu) {
                        submenu.classList.add('active');
                    }
                    const toggle = item.querySelector('.sidebar-menu-toggle');
                    if (toggle) {
                        toggle.classList.add('active');
                    }
                }
            } else {
                item.style.display = 'none';
            }
        });
    }

    function toggleSidebarMenu(btn) {
        const submenu = btn.nextElementSibling;
        submenu.classList.toggle('active');
        btn.classList.toggle('active');
    }
</script>