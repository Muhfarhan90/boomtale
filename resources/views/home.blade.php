@extends('layouts.app')

@section('title', 'Beranda - Boomtale')

@section('content')
    <!-- Hero Section -->
    <!-- Hero Section -->
    <section class="hero-section text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    {{-- PERBAIKAN: Ukuran font dibuat responsif --}}
                    <h1 class="display-5 fw-bold mb-3">Selamat Datang di Boomtale</h1>
                    <p class="lead mb-4">Temukan berbagai produk digital berkualitas tinggi untuk kebutuhan Anda.</p>
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

    <div class="container py-4">
        <!-- Search Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('user.products.index') }}" method="GET">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Cari produk...">
                                <button class="btn btn-boomtale" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- DIHAPUS: About Us Banner Component -->

        <!-- DIHAPUS: Features Section -->

        <!-- Latest Products -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- PERBAIKAN: Ukuran font dibuat lebih kecil untuk mobile --}}
                    <h2 class="h3 mb-0">Produk Terbaru</h2>
                    <a href="{{ route('user.products.index') }}" class="btn btn-outline-boomtale btn-sm">Lihat Semua</a>
                </div>
                <div class="row g-3">
                    @forelse ($latestProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada produk yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="row mb-5">
            <div class="col-12">
                {{-- PERBAIKAN: Ukuran font dibuat lebih kecil untuk mobile --}}
                <h2 class="h3 text-center mb-3">Jelajahi Kategori</h2>
                <div class="row g-3">
                    @forelse ($categories as $category)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('user.products.index', ['category' => $category->id]) }}"
                                class="text-decoration-none">
                                <div class="card text-center feature-card h-100">
                                    <div class="card-body">
                                        <i class="fas fa-folder fa-2x text-boomtale mb-2"></i>
                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->products_count }} produk</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Belum ada kategori yang tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
