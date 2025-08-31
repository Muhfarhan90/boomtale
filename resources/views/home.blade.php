@extends('layouts.app')

@section('title', 'Beranda - Boomtale')

@push('styles')
    <style>
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #6f42c1 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }

        .feature-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .product-slider {
            overflow-x: auto;
            scroll-behavior: smooth;
        }

        .product-slider::-webkit-scrollbar {
            display: none;
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
        }

        .bottom-nav .nav-item {
            flex: 1;
            text-align: center;
        }

        .bottom-nav .nav-link {
            color: #6c757d;
            text-decoration: none;
            font-size: 12px;
            padding: 8px 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: color 0.2s;
        }

        .bottom-nav .nav-link.active,
        .bottom-nav .nav-link:hover {
            color: var(--bs-primary);
        }

        .bottom-nav .nav-link i {
            font-size: 18px;
            margin-bottom: 4px;
        }

        @media (max-width: 767.98px) {
            body {
                padding-bottom: 70px;
            }

            .bottom-nav {
                display: flex;
            }

            .navbar-toggler {
                display: none !important;
            }

            .navbar-collapse {
                display: none !important;
            }

            .hero-section {
                padding: 2rem 0;
                margin-bottom: 2rem;
            }
        }

        @media (min-width: 768px) {
            .bottom-nav {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-3">Selamat Datang di Boomtale</h1>
                    <p class="lead mb-4">Temukan berbagai produk digital berkualitas tinggi untuk kebutuhan pembelajaran dan
                        hiburan Anda.</p>
                    <a href="{{ route('user.products.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-play me-2"></i>Mulai Jelajahi
                    </a>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="fas fa-rocket fa-8x opacity-25"></i>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- Search Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('user.products.index') }}" method="GET">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Cari produk yang Anda inginkan...">
                                <button class="btn btn-boomtale" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Banner Component -->
    <x-about />

    <div class="container">
        <!-- Features Section -->
        <div class="row mb-5 mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Mengapa Memilih Boomtale?</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-download fa-3x text-boomtale mb-3"></i>
                                <h5>Download Instan</h5>
                                <p class="text-muted">Dapatkan akses langsung ke produk digital Anda setelah pembelian</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-shield-alt fa-3x text-boomtale mb-3"></i>
                                <h5>Aman & Terpercaya</h5>
                                <p class="text-muted">Transaksi aman dengan sistem keamanan berlapis dan terpercaya</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card feature-card h-100 text-center">
                            <div class="card-body">
                                <i class="fas fa-headset fa-3x text-boomtale mb-3"></i>
                                <h5>Support 24/7</h5>
                                <p class="text-muted">Tim customer service siap membantu Anda kapan saja</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Products -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Produk Terbaru</h2>
                    <a href="{{ route('user.products.index') }}" class="btn btn-outline-boomtale">Lihat Semua</a>
                </div>

                <div class="row">
                    @foreach ($latestProducts as $product)
                        <div class="col-6 col-md-3 mb-4">
                            <div class="card h-100">
                                @if ($product->featured_image)
                                    <img src="{{ Storage::url($product->featured_image) }}" class="card-img-top"
                                        alt="{{ $product->name }}" style="height: 160px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 160px;">
                                        <i
                                            class="fas fa-{{ $product->isDigital() ? 'book' : 'play-circle' }} fa-2x text-muted"></i>
                                    </div>
                                @endif

                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">
                                        <a href="{{ route('user.products.show', $product) }}"
                                            class="text-dark text-decoration-none">
                                            {{ Str::limit($product->name, 30) }}
                                        </a>
                                    </h6>
                                    <div class="mt-auto">
                                        <div class="price mb-2">
                                            <span class="fw-bold text-boomtale">{{ $product->formatted_price }}</span>
                                        </div>
                                        <a href="{{ route('user.products.show', $product) }}"
                                            class="btn btn-outline-boomtale btn-sm w-100">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Jelajahi Kategori</h2>
                <div class="row g-3">
                    @foreach ($categories as $category)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('user.products.index', ['category' => $category->id]) }}"
                                class="text-decoration-none">
                                <div class="card text-center feature-card">
                                    <div class="card-body">
                                        <i class="fas fa-folder fa-2x text-boomtale mb-2"></i>
                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->products_count }} produk</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <nav class="bottom-nav d-md-none">
        <div class="d-flex">
            <div class="nav-item">
                <a href="{{ route('user.home') }}" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span>Beranda</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.products.index') }}" class="nav-link">
                    <i class="fas fa-th-large"></i>
                    <span>Produk</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.cart.index') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Keranjang</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.products.index') }}" class="nav-link">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Pesanan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('user.products.index') }}" class="nav-link">
                    <i class="fas fa-user"></i>
                    <span>Profil</span>
                </a>
            </div>
        </div>
    </nav>
@endsection
