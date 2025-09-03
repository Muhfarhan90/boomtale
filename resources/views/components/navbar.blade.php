<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="{{ route('user.home') }}">
            <i class="fas fa-rocket me-2 text-warning"></i>
            BOOMTALE
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Menu with proper spacing -->
            <ul class="navbar-nav me-auto ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('user.home') }}">
                        <i class="fas fa-home me-1 d-lg-none"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('user.products.index') }}">
                        <i class="fas fa-th-large me-1 d-lg-none"></i>Produk
                    </a>
                </li>
            </ul>

            <!-- Right Menu with proper spacing -->
            <ul class="navbar-nav ms-auto">
                @auth

                    @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                    @endphp
                    <!-- Cart -->
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative px-3" href="{{ route('user.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count"
                                style="font-size: 0.7rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
                            <span class="d-lg-none ms-2">Keranjang</span>
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Str::limit(auth()->user()->name, 15) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}">
                                    <i class="fas fa-user me-2 text-primary"></i>Profile
                                </a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.orders.index') }}">
                                    <i class="fas fa-shopping-bag me-2 text-success"></i>Pesanan Saya
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
                            <i class="fas fa-sign-in-alt me-1 d-lg-none"></i>Login
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

<!-- Mobile Bottom Navigation -->
<nav class="bottom-nav d-md-none">
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
        <div class="nav-item">
            <a href="{{ route('user.cart.index') }}"
                class="nav-link {{ request()->routeIs('user.cart.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>Keranjang</span>
                @auth
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                    @endphp
                    <span class="bottom-nav-badge cart-count-mobile"
                        style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
                @endauth
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('user.orders.index') }}"
                class="nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>
                <span>Pesanan</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('user.profile') }}"
                class="nav-link {{ request()->routeIs('user.profile*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
        </div>
    </div>
</nav>

<style>
    /* Cart badge styling */
    .cart-count {
        min-width: 20px !important;
        height: 20px !important;
        line-height: 20px !important;
        text-align: center !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        font-size: 0.65rem !important;
        font-weight: bold !important;
        border-radius: 50% !important;
    }

    /* Navbar spacing improvements */
    .navbar-nav .nav-link {
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 8px;
    }

    /* Mobile Bottom Navigation */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-top: 1px solid #dee2e6;
        z-index: 1000;
        padding: 8px 0;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .bottom-nav-container {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
        padding: 0 12px;
        margin: 0;
    }

    .bottom-nav .nav-item {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        margin: 0;
        padding: 0 4px;
    }

    .bottom-nav .nav-link {
        color: #6c757d;
        text-decoration: none;
        font-size: 11px;
        padding: 6px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        position: relative;
        width: 100%;
        text-align: center;
        min-height: 50px;
        border-radius: 8px;
    }

    .bottom-nav .nav-link.active,
    .bottom-nav .nav-link:hover {
        color: var(--bs-primary);
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }

    .bottom-nav .nav-link i {
        font-size: 16px;
        margin-bottom: 2px;
        line-height: 1;
    }

    .bottom-nav .nav-link span {
        font-size: 10px;
        font-weight: 500;
        line-height: 1;
        margin-top: 2px;
    }

    /* Bottom nav badge for mobile - PERBAIKAN UNTUK CENTERING */
    .bottom-nav-badge {
        position: absolute !important;
        top: 2px !important;
        right: 8px !important;
        background: #dc3545 !important;
        color: white !important;
        border-radius: 50% !important;
        width: 16px !important;
        height: 16px !important;
        font-size: 9px !important;
        line-height: 16px !important;
        text-align: center !important;
        font-weight: bold !important;
        z-index: 10 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
    }

    /* Add bottom padding to body on mobile to account for bottom nav */
    @media (max-width: 767.98px) {
        body {
            padding-bottom: 70px;
        }

        .bottom-nav {
            display: block;
        }

        /* Hide hamburger menu on mobile */
        .navbar-toggler {
            display: none !important;
        }

        /* Hide navbar menu items on mobile */
        .navbar-collapse {
            display: none !important;
        }
    }

    @media (min-width: 768px) {
        .bottom-nav {
            display: none;
        }
    }

    /* Responsive font sizes */
    @media (max-width: 360px) {
        .bottom-nav .nav-link {
            font-size: 10px;
            padding: 4px 6px;
        }

        .bottom-nav .nav-link i {
            font-size: 14px;
        }

        .bottom-nav .nav-link span {
            font-size: 9px;
        }

        .bottom-nav-container {
            padding: 0 8px;
        }

        /* Sesuaikan ukuran badge untuk layar kecil */
        .bottom-nav-badge {
            width: 14px !important;
            height: 14px !important;
            font-size: 8px !important;
            line-height: 14px !important;
        }

        .cart-count {
            min-width: 18px !important;
            height: 18px !important;
            line-height: 18px !important;
            font-size: 0.6rem !important;
        }
    }

    @media (min-width: 361px) and (max-width: 414px) {
        .bottom-nav .nav-link {
            font-size: 11px;
        }

        .bottom-nav .nav-link i {
            font-size: 16px;
        }

        .bottom-nav .nav-link span {
            font-size: 10px;
        }
    }

    /* Mobile menu improvements for hamburger (when shown) */
    @media (max-width: 991.98px) and (min-width: 768px) {
        .navbar-collapse {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
        }

        .navbar-nav .nav-item {
            margin: 0.25rem 0;
        }

        .navbar-nav .nav-link {
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: none;
            background: #f8f9fa;
            margin-top: 0.5rem;
        }
    }

    /* Desktop spacing */
    @media (min-width: 992px) {
        .navbar-expand-lg .navbar-nav .nav-link {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    /* Brand styling */
    .navbar-brand {
        font-size: 1.5rem;
        letter-spacing: 1px;
    }

    /* Dropdown improvements */
    .dropdown-menu {
        border: 1px solid rgba(0, 0, 0, .1);
        border-radius: 12px;
        padding: 0.5rem 0;
        margin-top: 0.5rem;
    }

    .dropdown-item {
        border-radius: 8px;
        margin: 0 0.5rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: rgba(0, 0, 0, 0.05);
        transform: translateX(2px);
    }
</style>

<script>
    // Update cart count on page load
    $(document).ready(function() {
        @auth
        updateCartCount();
    @endauth

    // Close mobile menu when clicking on a link
    $('.navbar-nav .nav-link').click(function() {
        if ($(window).width() < 992) {
            $('.navbar-collapse').collapse('hide');
        }
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

    // Auto-hide mobile menu when clicking outside
    $(document).click(function(event) {
        if (!$(event.target).closest('.navbar').length) {
            $('.navbar-collapse').collapse('hide');
        }
    });

    // Make updateCartCount available globally
    window.updateCartCount = updateCartCount;
</script>
