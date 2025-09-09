{{-- resources/views/user/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Boomtale - Digital Products')</title>

    {{-- Poppins Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    {{-- Icon Browser --}}
    <link rel="shortcut icon" href="{{ asset('logo_boomtale.png') }}">
    {{-- Vite CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --boomtale-primary: #C9a877;
            --boomtale-primary-dark: #B8986A;
            --boomtale-secondary: #2C2C2C;
            --boomtale-light: #F8F6F3;
        }

        body {
            font-family: 'Poppins', sans-serif !important;
            background: linear-gradient(135deg, #f5f7fa 0%, #ffffff 100%);
            margin: 0;
            padding: 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--boomtale-secondary) !important;
        }

        .btn-boomtale {
            background: var(--boomtale-primary);
            border-color: var(--boomtale-primary);
            color: white;
        }

        .btn-boomtale:hover {
            background: var(--boomtale-primary-dark);
            border-color: var(--boomtale-primary-dark);
            color: white;
        }

        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    @stack('styles')
</head>

<body>
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    {{-- <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Boomtale</h5>
                    <p>Platform digital untuk buku dan video pembelajaran berkualitas tinggi.</p>
                </div>
                <div class="col-md-3">
                    <h6>Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('admin.dashboard') }}"
                                class="text-light text-decoration-none">Produk</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Tentang Kami</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Hubungi Kami</h6>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@boomtale.com</p>
                    <p><i class="fab fa-whatsapp me-2"></i>+62 812 3456 7890</p>
                </div>
            </div>
        </div>
    </footer> --}}

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @stack('scripts')
</body>

</html>
