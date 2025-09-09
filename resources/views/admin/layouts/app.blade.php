{{-- filepath: d:\FREELANCE\boomtale\resources\views\admin\layouts\app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Dashboard') - Boomtale Admin</title>

    {{-- Poppins Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- Icon Browser --}}
    <link rel="shortcut icon" href="{{ asset('logo_boomtale.png') }}">
    <!-- Admin CSS dari resources -->
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
    </style>

    @stack('styles')


</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content Area -->
        <div class="main-content-area">
            <!-- Navbar -->
            @include('admin.components.navbar')

            <!-- Content -->
            <main class="content-wrapper">
                <!-- Alerts -->
                @include('admin.components.alerts')

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
