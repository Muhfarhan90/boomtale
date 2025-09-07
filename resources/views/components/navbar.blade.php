{{-- filepath: d:\FREELANCE\boomtale\resources\views\components\navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="user-navbar-brand fw-bold" href="{{ route('user.home') }}">
            <i class="fas fa-rocket me-2" style="color: #c9a877"></i>
            BOOMTALE
        </a>

        <!-- Mobile Cart & User Menu (Pojok Kanan Atas) -->
        <div class="d-lg-none d-flex align-items-center user-mobile-nav">
            @auth
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                <!-- Mobile Cart Icon -->
                <a href="{{ route('user.cart.index') }}" class="user-mobile-link position-relative me-3">
                    <i class="fas fa-shopping-cart fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger user-cart-count-mobile"
                        style="font-size: 0.65rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
                </a>

                <!-- Mobile User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-link user-mobile-link dropdown-toggle p-0 border-0" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow user-dropdown-menu">
                        <li class="px-3 py-2 border-bottom">
                            <small class="text-muted">{{ Str::limit(auth()->user()->name, 20) }}</small>
                        </li>
                        <li><a class="user-dropdown-item py-2" href="{{ route('user.profile.index') }}">
                                <i class="fas fa-user me-2 text-primary"></i>Profile
                            </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="user-dropdown-item text-danger py-2">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <!-- Mobile Guest Menu -->
                <div class="dropdown">
                    <button class="btn btn-link user-mobile-link dropdown-toggle p-0 border-0" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bars fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow user-dropdown-menu">
                        <li><a class="user-dropdown-item py-2" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2 text-primary"></i>Login
                            </a></li>
                        <li><a class="user-dropdown-item py-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2 text-success"></i>Daftar
                            </a></li>
                    </ul>
                </div>
            @endauth
        </div>

        <!-- Desktop Navigation -->
        <div class="d-none d-lg-flex user-navbar" id="navbarNav">
            <!-- Left Menu -->
            <ul class="user-navbar-nav me-auto ms-lg-4">
                <li class="user-nav-item">
                    <a class="user-nav-link px-3 {{ request()->routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}">
                        Beranda
                    </a>
                </li>
                <li class="user-nav-item">
                    <a class="user-nav-link px-3 {{ request()->routeIs('user.products.index') ? 'active' : '' }}" href="{{ route('user.products.index') }}">
                        Produk
                    </a>
                </li>
            </ul>

            <!-- Right Menu -->
            <ul class="user-navbar-nav ms-auto">
                @auth
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                    @endphp
                    <!-- Desktop Cart -->
                    <li class="user-nav-item me-2">
                        <a class="user-nav-link position-relative px-3" href="{{ route('user.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger user-cart-count"
                                style="font-size: 0.7rem; {{ $cartCount > 0 ? '' : 'display:none;' }}">{{ $cartCount }}</span>
                            <span class="d-lg-none ms-2">Keranjang</span>
                        </a>
                    </li>

                    <!-- Desktop User Dropdown -->
                    <li class="user-nav-item dropdown">
                        <a class="user-nav-link dropdown-toggle px-3" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ Str::limit(auth()->user()->name, 15) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow user-dropdown-menu">
                            <li><a class="user-dropdown-item py-2" href="{{ route('user.profile.index') }}">
                                    <i class="fas fa-user me-2 text-primary"></i>Profile
                                </a></li>
                            <li><a class="user-dropdown-item py-2" href="{{ route('user.user-products.index') }}">
                                    <i class="fas fa-box me-2 text-secondary"></i>Produk Saya
                                </a></li>
                            <li><a class="user-dropdown-item py-2" href="{{ route('user.orders.index') }}">
                                    <i class="fas fa-shopping-bag me-2 text-success"></i>Pesanan Saya
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="user-dropdown-item text-danger py-2">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="user-nav-item me-2">
                        <a class="user-nav-link px-3" href="{{ route('login') }}">
                            Login
                        </a>
                    </li>
                    <li class="user-nav-item">
                        <a class="user-nav-link btn btn-boomtale text-white px-4 py-2 rounded-pill"
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
<nav class="user-bottom-nav d-lg-none">
    <div class="user-bottom-nav-container">
        <div class="user-bottom-nav-item">
            <a href="{{ route('user.home') }}"
                class="user-bottom-nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Beranda</span>
            </a>
        </div>
        <div class="user-bottom-nav-item">
            <a href="{{ route('user.products.index') }}"
                class="user-bottom-nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span>Produk</span>
            </a>
        </div>
        @auth
            <div class="user-bottom-nav-item">
                <a href="{{ route('user.user-products.index') }}"
                    class="user-bottom-nav-link {{ request()->routeIs('user.user-products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Produk Saya</span>
                </a>
            </div>
            <div class="user-bottom-nav-item">
                <a href="{{ route('user.orders.index') }}"
                    class="user-bottom-nav-link {{ request()->routeIs('user.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Pesanan</span>
                </a>
            </div>
        @else
            <div class="user-bottom-nav-item">
                <a href="{{ route('login') }}" class="user-bottom-nav-link">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            </div>
            <div class="user-bottom-nav-item">
                <a href="{{ route('register') }}" class="user-bottom-nav-link">
                    <i class="fas fa-user-plus"></i>
                    <span>Daftar</span>
                </a>
            </div>
        @endauth
    </div>
</nav>

<script>
    // IMPROVED SCRIPT WITH REAL-TIME CART UPDATE
    $(document).ready(function() {
        @auth
        updateCartCount();
    @endauth

    // Auto-close dropdowns when clicking menu items
    $('.user-dropdown-item').click(function() {
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
                    $('.user-cart-count').text(count).show();
                } else {
                    $('.user-cart-count').hide();
                }

                // Update mobile cart count
                if (count > 0) {
                    $('.user-cart-count-mobile').text(count).show();
                } else {
                    $('.user-cart-count-mobile').hide();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error updating cart count:', error);
                $('.user-cart-count').hide();
                $('.user-cart-count-mobile').hide();
            }
        });
    @endauth
    }

    window.updateCartCount = updateCartCount;
</script>
