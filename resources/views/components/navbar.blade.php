<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('user.home') }}">
            <i class="fas fa-rocket me-2 text-warning"></i>
            BOOMTALE
        </a>

        <!-- Mobile Cart & User Menu (Pojok Kanan Atas) -->
        <div class="d-lg-none d-flex align-items-center">
            @auth
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                <!-- Mobile Cart Icon -->
                <a href="{{ route('user.cart.index') }}" class="nav-link position-relative me-3">
                    <i class="fas fa-shopping-cart fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count-mobile"
                        style="font-size: 0.65rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
                </a>

                <!-- Mobile User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link nav-link dropdown-toggle p-0 border-0" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted">{{ Str::limit(auth()->user()->name, 20) }}</small>
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('user.profile.index') }}">
                                <i class="fas fa-user me-2 text-primary"></i>Profile
                            </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger py-2">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <!-- Mobile Guest Menu -->
                <div class="dropdown">
                    <button class="btn btn-link nav-link dropdown-toggle p-0 border-0" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item py-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2 text-primary"></i>Login
                            </a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2 text-success"></i>Daftar
                            </a></li>
                    </ul>
                </div>
            @endauth
        </div>

        <!-- Desktop Navigation -->
        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
            <!-- Left Menu -->
            <ul class="navbar-nav me-auto ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('user.home') }}">
                        Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('user.products.index') }}">
                        Produk
                    </a>
                </li>
            </ul>

            <!-- Right Menu -->
            <ul class="navbar-nav ms-auto">
                @auth
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                    @endphp
                    <!-- Desktop Cart -->
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative px-3" href="{{ route('user.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count"
                                style="font-size: 0.7rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
                            <span class="d-lg-none ms-2">Keranjang</span>
                        </a>
                    </li>

                    <!-- Desktop User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Str::limit(auth()->user()->name, 15) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item py-2" href="{{ route('user.profile.index') }}">
                                    <i class="fas fa-user me-2 text-primary"></i>Profile
                                </a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.user-products.index') }}">
                                    <i class="fas fa-box me-2 text-secondary"></i>Produk Saya
                                </a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.orders.index') }}">
                                    <i class="fas fa-shopping-bag me-2 text-success"></i>Pesanan Saya
                                </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger py-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item me-2">
                        <a class="nav-link px-3" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-boomtale text-white px-4 py-2 rounded-pill"
                            href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Bottom Navigation - 4 Menu Utama -->
<nav class="bottom-nav d-lg-none">
    <div class="bottom-nav-container">
        <div class="nav-item">
            <a href="{{ route('user.home') }}" class="nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('user.products.index') }}"
                class="nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Produk</span>
            </a>
        </div>
        @auth
            <div class="nav-item">
                <a href="{{ route('user.user-products.index') }}"
                    class="nav-link {{ request()->routeIs('user.user-products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Produk Saya</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.orders.index') }}"
                    class="nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Pesanan</span>
                </a>
            </div>
        @else
            <div class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('register') }}" class="nav-link">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar</span>
                </a>
            </div>
        @endauth
    </div>
</nav>

<style>
    /* IMPROVED STYLES */
    .cart-count, .cart-count-mobile {
        min-width: 18px !important;
        height: 18px !important;
        line-height: 18px !important;
        text-align: center !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        font-size: 0.65rem !important;
        font-weight: bold !important;
        border-radius: 50% !important;
    }

    .navbar-nav .nav-link {
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }

    /* Mobile Top Navigation Improvements */
    .d-lg-none .nav-link {
        color: #6c757d;
        transition: all 0.2s ease;
        padding: 8px;
        border-radius: 8px;
    }

    .d-lg-none .nav-link:hover {
        color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }

    .d-lg-none .dropdown-menu {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        margin-top: 8px;
    }

    .d-lg-none .dropdown-item {
        border-radius: 8px;
        margin: 2px 8px;
        transition: all 0.2s ease;
    }

    .d-lg-none .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(4px);
    }

    /* Mobile Bottom Navigation - 4 ITEMS */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid #dee2e6;
        z-index: 1000;
        padding: 8px 0;
        box-shadow: 0 -2px 15px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .bottom-nav-container {
        display: flex;
        width: 100%;
        justify-content: space-between; /* Changed back for 4 items */
        align-items: center;
        padding: 0 8px;
        margin: 0;
    }

    .bottom-nav .nav-item {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        margin: 0;
        padding: 0 4px; /* Reduced padding for 4 items */
    }

    .bottom-nav .nav-link {
        color: #6c757d;
        text-decoration: none;
        font-size: 12px; /* Smaller font for 4 items */
        padding: 8px 6px; /* Adjusted padding */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
        width: 100%;
        text-align: center;
        min-height: 55px;
        border-radius: 12px;
        background-color: transparent;
    }

    .bottom-nav .nav-link.active {
        color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(var(--bs-primary-rgb), 0.3);
    }

    .bottom-nav .nav-link:hover:not(.active) {
        color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.05);
        transform: translateY(-1px);
    }

    .bottom-nav .nav-link i {
        font-size: 18px; /* Slightly smaller icons */
        margin-bottom: 4px;
        line-height: 1;
    }

    .bottom-nav .nav-link span {
        font-size: 10px; /* Smaller text for better fit */
        font-weight: 500;
        line-height: 1;
        margin-top: 2px;
        white-space: nowrap; /* Prevent text wrapping */
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    @media (max-width: 991.98px) {
        body {
            padding-bottom: 75px; /* Adjusted for smaller bottom nav */
        }

        .bottom-nav {
            display: block;
        }
    }

    @media (min-width: 992px) {
        .bottom-nav {
            display: none;
        }
    }

    /* Enhanced dropdown styles */
    .dropdown-menu {
        border-radius: 12px;
        border: none;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        margin-top: 8px;
    }

    .dropdown-item {
        border-radius: 8px;
        margin: 2px 8px;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
        transform: translateX(4px);
    }

    /* Brand improvements */
    .navbar-brand {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .navbar-brand:hover {
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }

    /* Responsive adjustments for very small screens */
    @media (max-width: 360px) {
        .bottom-nav .nav-link span {
            font-size: 9px;
        }

        .bottom-nav .nav-link i {
            font-size: 16px;
        }

        .bottom-nav-container {
            padding: 0 4px;
        }

        .bottom-nav .nav-item {
            padding: 0 2px;
        }
    }
</style>

<script>
    // IMPROVED SCRIPT WITH REAL-TIME CART UPDATE
    $(document).ready(function() {
        @auth
            updateCartCount();
        @endauth

        // Auto-close dropdowns when clicking menu items
        $('.dropdown-item').click(function() {
            $(this).closest('.dropdown-menu').dropdown('hide');
        });
    });

    function updateCartCount() {
        @auth
            $.ajax({
                url: '{{ route('user.cart.count') }}',
                method: 'GET',
                success: function(response) {
                    const count = response.count || 0;

                    // Update desktop cart count
                    if (count > 0) {
                        $('.cart-count').text(count).show();
                    } else {
                        $('.cart-count').hide();
                    }

                    // Update mobile cart count
                    if (count > 0) {
                        $('.cart-count-mobile').text(count).show();
                    } else {
                        $('.cart-count-mobile').hide();
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error updating cart count:', error);
                    $('.cart-count').hide();
                    $('.cart-count-mobile').hide();
                }
            });
        @endauth
    }

    window.updateCartCount = updateCartCount;
</script>
