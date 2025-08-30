{{-- resources/views/user/layouts/navbar.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-rocket me-2 text-warning"></i>
            BOOMTALE
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Menu -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Produk</a>
                </li>
            </ul>

            <!-- Search Form -->
            <form class="d-flex me-3" method="GET" action="{{ route('admin.dashboard') }}">
                <div class="input-group">
                    <input class="form-control" type="search" name="search" placeholder="Cari produk..."
                        value="{{ request('search') }}" style="width: 250px;">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <!-- Right Menu -->
            <ul class="navbar-nav">
                @auth
                    <!-- Cart -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('user.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge cart-count">0</span>
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>Pesanan Saya
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-boomtale text-white ms-2 px-3" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<script>
    // Update cart count on page load
    $(document).ready(function() {
        updateCartCount();
    });

    function updateCartCount() {
        @auth
        $.get('{{ route('admin.dashboard') }}')
            .done(function(response) {
                $('.cart-count').text(response.count);
                if (response.count > 0) {
                    $('.cart-count').show();
                } else {
                    $('.cart-count').hide();
                }
            });
    @endauth
    }
</script>
