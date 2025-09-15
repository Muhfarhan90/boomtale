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
        /* Existing styles... */
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

        /* Enhanced Price Section */
        .price-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px solid #e9ecef;
            position: relative;
            overflow: hidden;
        }

        .price-section::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #c9a877, #e6d4a7, #c9a877);
            border-radius: 15px;
            z-index: -1;
        }

        .price-main {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .price-current {
            font-size: 1.8rem;
            font-weight: bold;
            color: #28a745;
        }

        .price-original {
            font-size: 1.2rem;
            text-decoration: line-through;
            color: #6c757d;
        }

        .discount-badge {
            background: linear-gradient(45deg, #dc3545, #e85368);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .savings-text {
            color: #28a745;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Enhanced Button Styles */
        .btn-add-to-cart {
            background: linear-gradient(135deg, #c9a877 0%, #e6d4a7 100%);
            border: none;
            padding: 0.875rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(201, 168, 119, 0.3);
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(201, 168, 119, 0.4);
            color: #fff;
        }

        .btn-add-to-cart::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-add-to-cart:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login-prompt {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            padding: 0.875rem 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-radius: 25px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            color: #fff;
        }

        .btn-login-prompt:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(0, 123, 255, 0.4);
            color: #fff;
        }

        /* Rest of existing styles... */
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

        /* Login Prompt Styles */
        .login-prompt {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px solid #2196f3;
            border-radius: 15px;
            padding: 1.5rem;
        }

        .login-prompt .btn {
            margin: 0.25rem;
        }

        /* Price Comparison Animation */
        .price-comparison-enter {
            animation: slideInUp 0.5s ease-out;
        }

        @keyframes slideInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Mobile Responsive Enhancements */
        @media (max-width: 768px) {
            .price-section {
                padding: 1rem;
                margin-bottom: 1rem;
            }

            .price-current {
                font-size: 1.4rem;
            }

            .price-original {
                font-size: 1rem;
            }

            .btn-add-to-cart,
            .btn-login-prompt {
                width: 100%;
                margin-bottom: 0.5rem;
                font-size: 0.9rem;
                padding: 0.75rem 1.5rem;
            }

            .login-prompt {
                padding: 1rem;
            }

            .discount-badge {
                font-size: 0.75rem;
                padding: 0.2rem 0.5rem;
            }

            /* Enhanced mobile typography */
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
        }

        @media (max-width: 576px) {
            .price-current {
                font-size: 1.2rem;
            }

            .price-original {
                font-size: 0.9rem;
            }

            .price-main {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .discount-badge {
                align-self: flex-start;
            }
        }

        /* Loading Animation */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Pulse effect for CTA */
        .cta-pulse {
            animation: ctaPulse 2s infinite;
        }

        @keyframes ctaPulse {
            0% {
                box-shadow: 0 4px 15px rgba(201, 168, 119, 0.3);
            }

            50% {
                box-shadow: 0 6px 25px rgba(201, 168, 119, 0.6);
            }

            100% {
                box-shadow: 0 4px 15px rgba(201, 168, 119, 0.3);
            }
        }

        .share-buttons {
            display: flex;
            gap: 12px;
            /* Jarak antar ikon */
            align-items: center;
        }

        .share-buttons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            /* Membuatnya menjadi lingkaran */
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .share-buttons a:hover {
            transform: translateY(-3px) scale(1.1);
            /* Efek mengangkat saat disentuh */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        /* Additional existing styles would go here... */
        /* [Include all your existing CSS from the original file] */
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}"
                        class="text-decoration-none">{{ __('messages.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.products.index') }}"
                        class="text-decoration-none">{{ __('messages.products') }}</a></li>
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
                                <i class="fas fa-star me-1"></i>{{ __('messages.featured') }}
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
                                    <span class="visually-hidden">{{ __('messages.previous') }}</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon bg-dark rounded-circle p-3"
                                        aria-hidden="true"></span>
                                    <span class="visually-hidden">{{ __('messages.next') }}</span>
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
                                <p class="text-muted">{{ __('messages.no_image_available') }}</p>
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
                                        <span class="visually-hidden">{{ __('messages.previous') }}</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#modalCarousel"
                                        data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">{{ __('messages.next') }}</span>
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
                            {{ $product->total_reviews }} {{ __('messages.reviews') }}</span>
                    </div>
                </div>

                <!-- Enhanced Price Section -->
                <div class="price-section price-comparison-enter">
                    @php
                        $hasDiscount = $product->discount_price && $product->discount_price < $product->price;
                        $currentPrice = $hasDiscount ? $product->discount_price : $product->price;
                        $originalPrice = $product->price;
                        $discountPercentage = $hasDiscount
                            ? round((($originalPrice - $currentPrice) / $originalPrice) * 100)
                            : 0;
                        $savings = $hasDiscount ? $originalPrice - $currentPrice : 0;
                    @endphp

                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-grow-1">
                            <div class="price-main">
                                @if ($hasDiscount)
                                    <span class="price-original">{{ $product->formatted_price }}</span>
                                    <span class="price-discount">{{ $product->formatted_discount_price }}</span>
                                    <span class="discount-badge">
                                        {{ $discountPercentage }}% OFF
                                    </span>
                                @else
                                    <span class="price-current">{{ $product->formatted_price }}</span>
                                @endif
                            </div>
                            @if ($hasDiscount)
                                <div class="savings-text">
                                    <i class="fas fa-tags me-1"></i>
                                    {{ __('messages.you_save') }}: {{ $product->formatted_savings }}
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">{{ __('messages.product_type') }}</small>
                            <span class="badge bg-info">{{ ucfirst($product->type ?? 'Digital') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Product Description -->
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">{{ __('messages.description') }}</h5>
                    <div class="text-muted">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <!-- Quantity & Actions -->
                <div class="mb-4">
                    <div class="row g-3">
                        <!-- Quantity Selector -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('messages.quantity') }}</label>
                            <div class="quantity-selector">
                                <input type="number" id="quantity" value="1" min="1" max="10"
                                    readonly>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold">{{ __('messages.actions') }}</label>
                            <div class="d-grid gap-2">
                                @auth
                                    <button class="btn btn-add-to-cart text-white cta-pulse" id="addToCart"
                                        data-product-id="{{ $product->id }}">
                                        <i class="fas fa-shopping-cart me-2"></i>{{ __('messages.add_to_cart') }}
                                    </button>
                                @else
                                    <!-- Login Prompt Section -->
                                    <div class="login-prompt">
                                        <div class="text-center mb-3">
                                            <i class="fas fa-lock text-primary fa-2x mb-2"></i>
                                            <h6 class="fw-bold text-primary">{{ __('messages.login_to_purchase') }}</h6>
                                            <p class="text-muted mb-3">{{ __('messages.login_purchase_description') }}</p>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button class="btn btn-add-to-cart text-white" id="loginPromptCart"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-cart me-2"></i>{{ __('messages.add_to_cart') }}
                                            </button>

                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <a href="{{ route('auth.google') }}" class="btn btn-danger w-100">
                                                        <i
                                                            class="fab fa-google me-2"></i>{{ __('messages.login_with_google') }}
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="{{ route('login') }}" class="btn btn-primary w-100">
                                                        <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="text-center mt-2">
                                                <small class="text-muted">
                                                    {{ __('messages.dont_have_account') }}
                                                    <a href="{{ route('register') }}"
                                                        class="text-decoration-none">{{ __('messages.register_here') }}</a>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="mb-4">
                    <h6 class="fw-bold mb-2">{{ __('messages.share') }}</h6>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                            target="_blank" style="background: #3b5998;" title="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->name) }}"
                            target="_blank" style="background: #1da1f2;" title="Share on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . request()->url()) }}"
                            target="_blank" style="background: #25d366;" title="Share on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                            target="_blank" style="background: #0077b5;" title="Share on LinkedIn">
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
                        <i class="fas fa-align-left me-2"></i>{{ __('messages.description_detail') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                        type="button" role="tab">
                        <i class="fas fa-star me-2"></i>{{ __('messages.reviews') }} ({{ $product->total_reviews }})
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
                                    <h5 class="card-title mb-3">{{ __('messages.product_details') }}</h5>
                                    <div class="content">
                                        {!! nl2br(e($product->long_description ?? $product->description)) !!}
                                    </div>

                                    @if ($product->features)
                                        <h6 class="mt-4 mb-3">{{ __('messages.main_features') }}:</h6>
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
                                                {{ $product->total_reviews }} {{ __('messages.reviews') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h6 class="card-title mb-3">{{ __('messages.rating_distribution') }}</h6>
                                            @if ($product->total_reviews > 0)
                                                @foreach ($product->rating_distribution as $star => $data)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <span class="me-2">{{ $star }} ⭐</span>
                                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                            <div class="progress-bar bg-warning"
                                                                style="width: {{ ($data / $product->total_reviews) * 100 }}%">
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">{{ $data }}</small>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted text-center">{{ __('messages.no_reviews_yet') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info untuk Review -->
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>{{ __('messages.how_to_review') }}:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>{{ __('messages.how_to_review_1') }}</li>
                                    <li>{{ __('messages.how_to_review_2') }}</li>
                                    <li>{{ __('messages.how_to_review_3') }}</li>
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
                                                            <i
                                                                class="fas fa-check-circle me-1"></i>{{ __('messages.verified_purchase') }}
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
                                        <h5 class="text-muted">{{ __('messages.no_reviews_yet') }}</h5>
                                        <p class="text-muted">{{ __('messages.be_first_reviewer') }}</p>
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
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="related-products mt-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">{{ __('messages.related_products') }}</h3>
                    <p class="text-muted">{{ __('messages.other_products_you_might_like') }}</p>
                </div>

                <div class="row">
                    @foreach ($relatedProducts as $related)
                        <div class="col-6 col-md-3 mb-4">
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
        const i18n = {
            adding: "{{ __('messages.adding') }}",
            added_successfully: "{{ __('messages.added_successfully') }}",
            add_to_cart: "{{ __('messages.add_to_cart') }}",
            login_required: "{{ __('messages.login_required') }}",
            redirecting_to_login: "{{ __('messages.redirecting_to_login') }}",
            products_added_to_cart: "{{ __('messages.products_added_to_cart') }}",
            failed_to_add_to_cart: "{{ __('messages.failed_to_add_to_cart') }}",
            error_occurred: "{{ __('messages.error_occurred') }}",
            previous: "{{ __('messages.previous') }}",
            next: "{{ __('messages.next') }}"
        };

        $(document).ready(function() {
            // Add to Cart (Authenticated Users)
            $('#addToCart').click(function() {
                handleAddToCart($(this));
            });

            // Login Prompt Cart (Guest Users)
            $('#loginPromptCart').click(function() {
                const button = $(this);

                button.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>' + i18n.redirecting_to_login
                );

                // Show login modal or redirect to login
                setTimeout(() => {
                    window.location.href =
                        '{{ route('login', ['redirect' => request()->url()]) }}';
                }, 1000);
            });

            function handleAddToCart(button) {
                const productId = button.data('product-id');
                const quantity = $('#quantity').val();

                button.prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin me-2"></i>' + i18n.adding
                );

                $.post('{{ route('user.cart.add') }}', {
                        product_id: productId,
                        quantity: quantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(response) {
                        if (response.success) {
                            button.html('<i class="fas fa-check me-2"></i>' + i18n.added_successfully);

                            // Update cart count
                            if (typeof window.updateCartCount === 'function') {
                                window.updateCartCount();
                            }

                            // Show success notification
                            showNotification(i18n.products_added_to_cart, 'success');

                            setTimeout(() => {
                                button.prop('disabled', false).html(
                                    '<i class="fas fa-shopping-cart me-2"></i>' + i18n.add_to_cart
                                );
                            }, 2000);
                        } else {
                            showNotification(response.message || i18n.failed_to_add_to_cart, 'error');
                            resetButton(button);
                        }
                    })
                    .fail(function(xhr) {
                        let errorMessage = i18n.error_occurred;

                        if (xhr.status === 401) {
                            errorMessage = i18n.login_required;
                            setTimeout(() => {
                                window.location.href =
                                    '{{ route('login', ['redirect' => request()->url()]) }}';
                            }, 1500);
                        }

                        showNotification(errorMessage, 'error');
                        resetButton(button);
                    });
            }

            function resetButton(button) {
                button.prop('disabled', false).html(
                    '<i class="fas fa-shopping-cart me-2"></i>' + i18n.add_to_cart
                );
            }

            // Enhanced notification function
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                const notification = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px; max-width: 400px;" role="alert">
                        <i class="fas ${icon} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                $('body').append(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut(() => {
                        $('.alert').remove();
                    });
                }, 5000);
            }

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

            // Price animation on load
            $('.price-section').addClass('price-comparison-enter');
        });
    </script>
@endpush
