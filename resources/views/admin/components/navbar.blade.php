<nav class="admin-navbar">
    <div class="navbar-container">
        <!-- Left Side -->
        <div class="navbar-left">
            <button class="navbar-toggle d-lg-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <div class="page-title-section">
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <small class="page-subtitle">@yield('page-subtitle', 'Kelola platform digital Boomtale')</small>
            </div>
        </div>

        <!-- Right Side -->
        <div class="navbar-right">
            <!-- Notifications Button -->
            <button class="navbar-btn" type="button" onclick="alert('Coming Soon')">
                <i class="fas fa-bell"></i>
                <span class="badge">3</span>
            </button>

            <!-- User Profile Dropdown -->
            <div class="dropdown">
                <button class="navbar-profile" type="button" data-bs-toggle="dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=C5A572&color=fff&size=128"
                        alt="User Avatar" class="profile-img">
                    <span class="profile-name d-none d-md-inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="dropdown-header">{{ auth()->user()->email ?? 'admin@boomtale.com' }}</li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="#" onclick="alert('Coming Soon')">
                            <i class="fas fa-user me-2"></i>Profile Saya
                        </a></li>
                    <li><a class="dropdown-item" href="#" onclick="alert('Coming Soon')">
                            <i class="fas fa-cog me-2"></i>Pengaturan
                        </a></li>
                    <li><a class="dropdown-item" href="{{ route('user.products.index') }}" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Lihat Website
                        </a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger logout-btn">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
