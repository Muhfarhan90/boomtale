{{-- filepath: d:\FREELANCE\boomtale\resources\views\admin\components\sidebar.blade.php --}}
<div class="admin-sidebar">
    <!-- Header Logo -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-img">
                <i class="fas fa-rocket"></i>
            </div>
            <div class="logo-text">BOOMTALE</div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}"
                    class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                    <i class="fas fa-box nav-icon"></i>
                    <span class="nav-text">Produk</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}"
                    class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <i class="fas fa-tags nav-icon"></i>
                    <span class="nav-text">Kategori</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}"
                    class="nav-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart nav-icon"></i>
                    <span class="nav-text">Pesanan</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}"
                    class="nav-link
                    {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users nav-icon"></i>
                    <span class="nav-text">Pengguna</span>
                </a>
            </li>

            {{-- <li class="nav-item">
                <a href="#" class="nav-link" onclick="showComingSoon('Laporan')">
                    <i class="fas fa-chart-bar nav-icon"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </li> --}}

            <li class="nav-item">
                <a href="{{route('admin.settings.index')}}" class="nav-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="fas fa-cog nav-icon"></i>
                    <span class="nav-text">Pengaturan</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'Admin') }}&background=C5A572&color=fff&size=128"
                    alt="User Avatar" class="avatar-img">
            </div>
            <div class="user-details">
                <div class="user-name">{{ auth()->user()->name ?? 'Super Admin' }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role ?? 'admin') }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>
