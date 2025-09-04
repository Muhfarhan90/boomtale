@extends('layouts.app')

@section('title', $product->name . ' - Boomtale')

@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image"
        content="{{ $product->featured_image ? Storage::url($product->featured_image) : asset('images/default-product.jpg') }}">
    <meta property="og:url" content="{{ request()->url() }}">
@endsection

@push('styles')
    <style>
        .product-image {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .product-gallery {
            position: relative;
        }

        .product-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            z-index: 10;
        }

        .price-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e9ecef;
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, var(--bs-primary) 0%, #6f42c1 100%);
            border: none;
            padding: 0.875rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(var(--bs-primary-rgb), 0.3);
        }

        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(var(--bs-primary-rgb), 0.4);
        }

        .product-specs {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
        }

        .spec-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .spec-item:last-child {
            border-bottom: none;
        }

        .related-products .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .related-products .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .review-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--bs-primary);
        }

        .rating-stars {
            color: #ffc107;
        }

        .rating-input .rating-star {
            font-size: 1.5rem;
            color: #ffc107;
            cursor: pointer;
            margin-right: 0.25rem;
            transition: transform 0.2s ease;
        }

        .rating-input .rating-star:hover {
            transform: scale(1.1);
        }

        .review-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--bs-primary);
            transition: transform 0.2s ease;
        }

        .review-card:hover {
            transform: translateX(5px);
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            color: #6c757d;
        }

        .zoom-container {
            position: relative;
            overflow: hidden;
            cursor: zoom-in;
        }

        .product-tabs .nav-tabs {
            border: none;
        }

        .product-tabs .nav-tabs .nav-link {
            border: 2px solid transparent;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            margin-right: 0.5rem;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .product-tabs .nav-tabs .nav-link.active {
            background: var(--bs-primary);
            color: white;
            border-color: var(--bs-primary);
        }

        .share-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 0.5rem;
            text-decoration: none;
            color: white;
            transition: transform 0.3s ease;
        }

        .share-buttons a:hover {
            transform: translateY(-2px);
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            border: 2px solid #e9ecef;
            border-radius: 25px;
            overflow: hidden;
            width: fit-content;
        }

        .quantity-selector button {
            border: none;
            background: white;
            padding: 0.5rem 1rem;
            color: var(--bs-primary);
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .quantity-selector button:hover {
            background: var(--bs-primary);
            color: white;
        }

        .quantity-selector input {
            border: none;
            text-align: center;
            width: 60px;
            padding: 0.5rem;
            background: #f8f9fa;
        }

        /* Carousel Styles */
        .product-gallery .carousel {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 2rem;
            height: 2rem;
        }

        .carousel-indicators {
            bottom: 10px;
        }

        .carousel-indicators [data-bs-target] {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 3px;
        }

        /* Thumbnail Styles */
        .thumbnail-container {
            overflow: hidden;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .thumbnail-container:hover {
            border-color: var(--bs-primary);
            transform: scale(1.05);
        }

        .thumbnail-container.active {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 2px rgba(var(--bs-primary-rgb), 0.3);
        }

        .thumbnail-image {
            transition: transform 0.3s ease;
        }

        .thumbnail-container:hover .thumbnail-image {
            transform: scale(1.1);
        }

        /* Modal Styles */
        #imageModal .modal-content {
            background: rgba(0, 0, 0, 0.9) !important;
        }

        #imageModal .carousel-control-prev,
        #imageModal .carousel-control-next {
            width: 5%;
        }

        /* Zoom Effect */
        .zoom-container {
            position: relative;
            overflow: hidden;
        }

        .zoom-container:hover .product-image {
            transform: scale(1.05);
        }

        /* Loading Animation for Images */
        .product-image {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .product-image.loading {
            opacity: 0.7;
        }

        /* Mobile Responsive - Optimized Font Sizes */
        @media (max-width: 768px) {
            .product-image {
                margin-bottom: 1.5rem;
            }

            /* Optimized typography for mobile */
            h1 {
                font-size: 1.5rem !important;
            }

            h2 {
                font-size: 1.4rem !important;
            }

            h3 {
                font-size: 1.3rem !important;
            }

            h4 {
                font-size: 1.2rem !important;
            }

            h5 {
                font-size: 1.1rem !important;
            }

            h6 {
                font-size: 0.95rem !important;
            }

            .h2 {
                font-size: 1.4rem !important;
            }

            .h3 {
                font-size: 1.3rem !important;
            }

            .h5 {
                font-size: 1.1rem !important;
            }

            /* Price section mobile */
            .price-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .price-section .h3 {
                font-size: 1.2rem !important;
            }

            /* Button optimizations */
            .btn-add-to-cart {
                width: 100%;
                margin-bottom: 0.5rem;
                font-size: 0.9rem !important;
                padding: 0.75rem 1.5rem !important;
            }

            .btn-lg {
                font-size: 0.95rem !important;
                padding: 0.75rem 1.5rem !important;
            }

            .btn {
                font-size: 0.85rem !important;
            }

            /* Product specifications mobile */
            .product-specs {
                padding: 1rem;
            }

            .spec-item {
                font-size: 0.9rem;
                padding: 0.5rem 0;
            }

            /* Carousel optimizations */
            .carousel-control-prev,
            .carousel-control-next {
                width: 8%;
            }

            .carousel-control-prev-icon,
            .carousel-control-next-icon {
                width: 1.5rem;
                height: 1.5rem;
            }

            .thumbnail-container {
                margin-bottom: 0.5rem;
            }

            /* Tab navigation mobile */
            .product-tabs .nav-tabs .nav-link {
                font-size: 0.85rem !important;
                padding: 0.5rem 1rem !important;
                margin-right: 0.25rem;
            }

            /* Tab content mobile */
            .tab-content {
                font-size: 0.9rem;
            }

            .tab-content .card-title {
                font-size: 1rem !important;
            }

            /* Review section mobile */
            .review-card {
                padding: 1rem;
                font-size: 0.9rem;
            }

            .review-card h6 {
                font-size: 0.9rem !important;
            }

            /* Breadcrumb mobile */
            .breadcrumb {
                font-size: 0.8rem;
            }

            /* Share buttons mobile */
            .share-buttons a {
                width: 35px;
                height: 35px;
                margin-right: 0.3rem;
            }

            /* Product badge mobile */
            .product-badge .badge {
                font-size: 0.75rem !important;
            }

            /* Rating stars mobile */
            .rating-stars {
                font-size: 0.9rem;
            }

            /* Alert mobile */
            .alert {
                font-size: 0.9rem;
            }

            /* Card body mobile */
            .card-body {
                padding: 1rem !important;
            }

            /* Related products mobile */
            .related-products .card-title {
                font-size: 0.9rem !important;
            }

            .related-products .card-body {
                padding: 0.75rem !important;
            }

            /* Quantity selector mobile */
            .quantity-selector {
                width: 100%;
                justify-content: center;
            }

            .quantity-selector button {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }

            .quantity-selector input {
                width: 50px;
                font-size: 0.9rem;
            }

            /* Progress bar mobile */
            .progress {
                height: 6px !important;
            }

            /* Modal mobile */
            .modal-body {
                font-size: 0.9rem;
            }

            .modal-title {
                font-size: 1.1rem !important;
            }

            /* Notification mobile */
            .alert.position-fixed {
                min-width: 280px !important;
                font-size: 0.85rem !important;
                top: 10px !important;
                right: 10px !important;
                left: 10px !important;
                margin: 0 auto;
            }
        }

        @media (max-width: 576px) {

            /* Extra small mobile adjustments */
            .container {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            /* Further reduce font sizes */
            h1 {
                font-size: 1.3rem !important;
            }

            h2,
            .h2 {
                font-size: 1.2rem !important;
            }

            h3,
            .h3 {
                font-size: 1.1rem !important;
            }

            h5,
            .h5 {
                font-size: 1rem !important;
            }

            h6,
            .h6 {
                font-size: 0.9rem !important;
            }

            /* Product image smaller */
            .product-image {
                height: 250px !important;
            }

            .thumbnail-image {
                height: 50px !important;
            }

            /* Price section smaller */
            .price-section .h3 {
                font-size: 1.1rem !important;
            }

            /* Button smaller */
            .btn-add-to-cart {
                font-size: 0.85rem !important;
                padding: 0.6rem 1.2rem !important;
            }

            .btn {
                font-size: 0.8rem !important;
                padding: 0.4rem 0.8rem !important;
            }

            /* Tab navigation smaller */
            .product-tabs .nav-tabs .nav-link {
                font-size: 0.8rem !important;
                padding: 0.4rem 0.8rem !important;
            }

            /* Content smaller */
            .tab-content {
                font-size: 0.85rem;
            }

            /* Specs smaller */
            .spec-item {
                font-size: 0.85rem;
            }

            /* Review smaller */
            .review-card {
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            /* Related products smaller */
            .related-products .card-title {
                font-size: 0.85rem !important;
            }

            /* Share buttons smaller */
            .share-buttons a {
                width: 30px;
                height: 30px;
                font-size: 0.8rem;
            }

            /* Badge smaller */
            .badge {
                font-size: 0.7rem !important;
            }

            /* Rating distribution smaller */
            .progress {
                height: 4px !important;
            }
        }

        /* Loading states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Custom scrollbar for mobile */
        @media (max-width: 768px) {
            ::-webkit-scrollbar {
                width: 4px;
            }

            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            ::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 2px;
            }
        }

        /* Improved touch targets for mobile */
        @media (max-width: 768px) {
            .btn {
                min-height: 38px;
                min-width: 38px;
            }

            .form-control {
                font-size: 16px;
                /* Prevents zoom on iOS */
            }

            .thumbnail-container {
                min-height: 38px;
            }

            .carousel-control-prev,
            .carousel-control-next {
                min-width: 38px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}" class="text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.products.index') }}"
                        class="text-decoration-none">Produk</a></li>
                @if ($product->category)
                    <li class="breadcrumb-item"><a
                            href="{{ route('user.products.index', ['category' => $product->category->id]) }}"
                            class="text-decoration-none">{{ $product->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($product->name, 30) }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-gallery">
                    <!-- Main Product Badge -->
                    @if ($product->is_featured)
                        <div class="product-badge">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>Featured
                            </span>
                        </div>
                    @endif

                    @php
                        // Gabungkan featured_image dan gallery_images
                        $allImages = [];

                        // Tambahkan featured_image sebagai gambar pertama
                        if ($product->featured_image) {
                            $allImages[] = [
                                'path' => $product->featured_image,
                                'type' => 'featured',
                            ];
                        }

                        // Tambahkan gallery_images
                        if ($product->gallery_images && is_array($product->gallery_images)) {
                            foreach ($product->gallery_images as $galleryImage) {
                                $allImages[] = [
                                    'path' => $galleryImage,
                                    'type' => 'gallery',
                                ];
                            }
                        }
                    @endphp

                    @if (count($allImages) > 0)
                        <!-- Main Carousel -->
                        <div id="productCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach ($allImages as $index => $image)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="zoom-container">
                                            <img src="{{ Storage::url($image['path']) }}"
                                                class="d-block w-100 product-image"
                                                alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                                style="height: 400px; object-fit: cover; cursor: zoom-in;"
                                                onclick="openImageModal({{ $index }})">
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Carousel Controls (only show if more than 1 image) -->
                            @if (count($allImages) > 1)
                                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"
                                        aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3"
                                        aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>

                                <!-- Carousel Indicators -->
                                <div class="carousel-indicators">
                                    @foreach ($allImages as $index => $image)
                                        <button type="button" data-bs-target="#productCarousel"
                                            data-bs-slide-to="{{ $index }}"
                                            class="{{ $index === 0 ? 'active' : '' }}"
                                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Thumbnail Gallery -->
                        @if (count($allImages) > 1)
                            <div class="row g-2">
                                @foreach ($allImages as $index => $image)
                                    <div class="col-3">
                                        <div class="thumbnail-container position-relative"
                                            onclick="goToSlide({{ $index }})" style="cursor: pointer;">
                                            <img src="{{ Storage::url($image['path']) }}"
                                                class="img-fluid thumbnail-image w-100" alt="Thumbnail {{ $index + 1 }}"
                                                style="height: 80px; object-fit: cover; border-radius: 8px; transition: all 0.3s ease;">

                                            @if ($image['type'] === 'featured')
                                                <span class="badge bg-primary position-absolute top-0 start-0 m-1"
                                                    style="font-size: 0.6rem;">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <!-- Default Image if no images available -->
                        <div class="product-image w-100 bg-light d-flex align-items-center justify-content-center"
                            style="height: 400px;">
                            <div class="text-center">
                                <i class="fas fa-image fa-4x text-muted mb-2"></i>
                                <p class="text-muted">No Image Available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Image Modal for Full View -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-header border-0">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <div id="modalCarousel" class="carousel slide" data-bs-ride="false">
                                <div class="carousel-inner">
                                    @foreach ($allImages as $index => $image)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <img src="{{ Storage::url($image['path']) }}" class="d-block w-100"
                                                alt="{{ $product->name }} - Image {{ $index + 1 }}"
                                                style="max-height: 80vh; object-fit: contain;">
                                        </div>
                                    @endforeach
                                </div>

                                @if (count($allImages) > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#modalCarousel"
                                        data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <!-- Product Title & Category -->
                <div class="mb-3">
                    @if ($product->category)
                        <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                    @endif
                    <h1 class="h2 fw-bold mb-2">{{ $product->name }}</h1>

                    <!-- Rating & Reviews -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="rating-stars me-2">
                            {!! $product->stars_html !!}
                        </div>
                        <span class="text-muted">({{ $product->formatted_average_rating }}) •
                            {{ $product->total_reviews }} ulasan</span>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="price-section">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="h3 fw-bold text-primary mb-0">{{ $product->formatted_price }}</span>
                            @if ($product->original_price && $product->original_price > $product->price)
                                <span class="text-decoration-line-through text-muted ms-2">
                                    Rp {{ number_format($product->original_price, 0, ',', '.') }}
                                </span>
                                <span class="badge bg-danger ms-2">
                                    {{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                                    OFF
                                </span>
                            @endif
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Tipe Produk</small>
                            <span class="badge bg-info">{{ ucfirst($product->type ?? 'Digital') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Deskripsi Produk</h5>
                    <div class="text-muted">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Quantity & Actions -->
                <div class="mb-4">
                    @auth
                        <div class="row g-3">
                            <!-- Quantity Selector -->
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Kuantitas</label>
                                <div class="quantity-selector">
                                    <button type="button" id="decreaseQty">-</button>
                                    <input type="number" id="quantity" value="1" min="1" max="10"
                                        readonly>
                                    <button type="button" id="increaseQty">+</button>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Aksi</label>
                                <div class="d-grid gap-2 d-md-flex">
                                    <button class="btn btn-add-to-cart text-white flex-fill" id="addToCart"
                                        data-product-id="{{ $product->id }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang
                                    </button>
                                    <button class="btn btn-outline-primary flex-fill" id="buyNow">
                                        <i class="fas fa-bolt me-2"></i>Beli Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <a href="{{ route('login') }}" class="alert-link">Masuk</a> atau
                            <a href="{{ route('register') }}" class="alert-link">Daftar</a> untuk membeli produk ini.
                        </div>
                    @endauth
                </div>

                <!-- Product Specifications -->
                <div class="product-specs mb-4">
                    <h5 class="fw-bold mb-3">Spesifikasi</h5>
                    <div class="spec-item">
                        <span class="text-muted">Format</span>
                        <span class="fw-bold">{{ strtoupper($product->type ?? 'Digital') }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="text-muted">Ukuran File</span>
                        <span class="fw-bold">{{ $product->file_size ?? 'Tidak Diketahui' }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="text-muted">Bahasa</span>
                        <span class="fw-bold">{{ $product->language ?? 'Indonesia' }}</span>
                    </div>
                    <div class="spec-item">
                        <span class="text-muted">Tersedia Sejak</span>
                        <span class="fw-bold">{{ $product->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">Bagikan</h6>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank" style="background: #3b5998;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}"
                            target="_blank" style="background: #1da1f2;">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}"
                            target="_blank" style="background: #25d366;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                            target="_blank" style="background: #0077b5;">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="product-tabs mt-5">
            <ul class="nav nav-tabs justify-content-center" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                        data-bs-target="#description" type="button" role="tab">
                        <i class="fas fa-align-left me-2"></i>Deskripsi Detail
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button" role="tab">
                        <i class="fas fa-star me-2"></i>Ulasan ({{ $product->total_reviews }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="download-tab" data-bs-toggle="tab" data-bs-target="#download"
                        type="button" role="tab">
                        <i class="fas fa-download me-2"></i>Download Info
                    </button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="productTabsContent">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">Detail Produk</h5>
                                    <div class="content">
                                        {!! nl2br(e($product->long_description ?? $product->description)) !!}
                                    </div>

                                    @if ($product->features)
                                        <h6 class="mt-4 mb-3">Fitur Utama:</h6>
                                        <ul class="list-unstyled">
                                            @foreach (explode("\n", $product->features) as $feature)
                                                @if (trim($feature))
                                                    <li class="mb-2">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        {{ trim($feature) }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <!-- Review Summary -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body text-center p-4">
                                            <h2 class="text-primary mb-2" id="averageRating">
                                                {{ $product->formatted_average_rating }}</h2>
                                            <div class="rating-stars mb-2" id="averageStars">
                                                {!! $product->stars_html !!}
                                            </div>
                                            <p class="text-muted mb-0" id="totalReviewsText">
                                                {{ $product->total_reviews }} ulasan</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h6 class="card-title mb-3">Distribusi Rating</h6>
                                            @if ($product->total_reviews > 0)
                                                {{-- Loop melalui distribusi rating --}}
                                                @foreach ($product->rating_distribution as $star => $data)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="me-2">{{ $star }} ⭐</span>
                                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                            {{-- PERBAIKAN: Hitung persentase di sini --}}
                                                            <div class="progress-bar bg-warning"
                                                                style="width: {{ ($data / $product->total_reviews) * 100 }}%">
                                                            </div>
                                                        </div>
                                                        {{-- PERBAIKAN: Gunakan $data langsung sebagai jumlah --}}
                                                        <small class="text-muted">{{ $data }}</small>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted text-center">Belum ada rating untuk produk ini</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info untuk Review -->
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Cara memberikan review:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>Beli produk ini</li>
                                    <li>Setelah pembelian selesai, buka halaman <strong>Pesanan Saya</strong></li>
                                    <li>Klik tombol <strong>"Beri Review"</strong> pada produk yang sudah dibeli</li>
                                </ol>
                            </div>

                            <!-- Reviews List (Read Only) -->
                            <div id="reviewsList">
                                @forelse($reviews as $review)
                                    <div class="review-card" data-review-id="{{ $review->id }}">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-shrink-0">
                                                <div class="bg-{{ ['primary', 'success', 'info', 'warning', 'secondary'][rand(0, 4)] }} rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px;">
                                                    <span
                                                        class="text-white fw-bold">{{ strtoupper(substr($review->user->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                        <div class="rating-stars small mb-1">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }} text-warning"></i>
                                                            @endfor
                                                        </div>
                                                        <span class="badge bg-success small">
                                                            <i class="fas fa-check-circle me-1"></i>Verified Purchase
                                                        </span>
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if ($review->comment)
                                                    <p class="mt-2 mb-0">{{ $review->comment }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada review</h5>
                                        <p class="text-muted">Jadilah yang pertama memberikan review dengan membeli produk
                                            ini!</p>
                                    </div>
                                @endforelse

                                <!-- Pagination -->
                                @if ($reviews->hasPages())
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $reviews->appends(['reviews_page' => $reviews->currentPage()])->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download Info Tab -->
                <div class="tab-pane fade" id="download" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">Informasi Download</h5>

                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Link download akan tersedia setelah pembayaran berhasil dikonfirmasi.
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="spec-item">
                                                <span class="text-muted">Format File</span>
                                                <span class="fw-bold">{{ strtoupper($product->type ?? 'PDF') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="spec-item">
                                                <span class="text-muted">Ukuran</span>
                                                <span class="fw-bold">{{ $product->file_size ?? '~5 MB' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="spec-item">
                                                <span class="text-muted">Sistem Operasi</span>
                                                <span class="fw-bold">Semua Platform</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="spec-item">
                                                <span class="text-muted">Masa Berlaku Link</span>
                                                <span class="fw-bold">30 Hari</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h6 class="fw-bold">Petunjuk Download:</h6>
                                        <ol class="text-muted">
                                            <li>Selesaikan pembayaran</li>
                                            <li>Cek email untuk link download</li>
                                            <li>Klik link dan download file</li>
                                            <li>Ekstrak file jika diperlukan</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="related-products mt-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Produk Terkait</h3>
                    <p class="text-muted">Produk lain yang mungkin Anda sukai</p>
                </div>

                <div class="row">
                    @foreach ($relatedProducts as $related)
                        <div class="col-6 col-md-3 mb-4">
                            {{-- Gunakan komponen product-card --}}
                            <x-product-card :product="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Quantity controls
            $('#increaseQty').click(function() {
                let qty = parseInt($('#quantity').val());
                if (qty < 10) {
                    $('#quantity').val(qty + 1);
                }
            });

            $('#decreaseQty').click(function() {
                let qty = parseInt($('#quantity').val());
                if (qty > 1) {
                    $('#quantity').val(qty - 1);
                }
            });

            // Add to Cart
            $('#addToCart').click(function() {
                const productId = $(this).data('product-id');
                const quantity = $('#quantity').val();
                const button = $(this);

                button.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>Menambahkan...');

                $.post('{{ route('user.cart.add') }}', {
                        product_id: productId,
                        quantity: quantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(response) {
                        if (response.success) {
                            button.html('<i class="fas fa-check me-2"></i>Berhasil Ditambahkan');

                            // Update cart count
                            if (typeof window.updateCartCount === 'function') {
                                window.updateCartCount();
                            }

                            // Show success notification
                            showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');

                            setTimeout(() => {
                                button.prop('disabled', false).html(
                                    '<i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang'
                                );
                            }, 2000);
                        } else {
                            showNotification(response.message || 'Gagal menambahkan ke keranjang',
                                'error');
                            button.prop('disabled', false).html(
                                '<i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang');
                        }
                    })
                    .fail(function() {
                        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        button.prop('disabled', false).html(
                            '<i class="fas fa-shopping-cart me-2"></i>Tambah ke Keranjang');
                    });
            });

            // Buy Now
            $('#buyNow').click(function() {
                const productId = '{{ $product->id }}';
                const quantity = $('#quantity').val();

                // Add to cart first, then redirect to checkout
                $.post('{{ route('user.cart.add') }}', {
                        product_id: productId,
                        quantity: quantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(response) {
                        if (response.success) {
                            window.location.href = '{{ route('user.cart.index') }}';
                        } else {
                            showNotification(response.message || 'Gagal memproses pembelian', 'error');
                        }
                    })
                    .fail(function() {
                        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                    });
            });

            // Simple notification function
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const notification = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                $('body').append(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 5000);
            }

            // Image zoom effect (simple)
            $('.zoom-container').hover(function() {
                $(this).find('img').css('transform', 'scale(1.1)');
            }, function() {
                $(this).find('img').css('transform', 'scale(1)');
            });

            // Carousel functionality
            window.goToSlide = function(index) {
                $('#productCarousel').carousel(index);
                $('.thumbnail-container').removeClass('active');
                $('.thumbnail-container').eq(index).addClass('active');
            };

            window.openImageModal = function(index) {
                $('#imageModal').modal('show');
                $('#modalCarousel').carousel(index);
            };

            $('#productCarousel').on('slide.bs.carousel', function(e) {
                $('.thumbnail-container').removeClass('active');
                $('.thumbnail-container').eq(e.to).addClass('active');
            });

            $('.thumbnail-container').first().addClass('active');
        });
    </script>
@endpush
