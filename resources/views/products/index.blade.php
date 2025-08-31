@extends('layouts.app')

@section('title', 'Produk Digital - Boomtale')

@push('styles')
    <style>
        /* General Mobile Optimizations */
        @media (max-width: 768px) {

            /* Header optimizations */
            h2 {
                font-size: 1.5rem !important;
            }

            h5 {
                font-size: 1.1rem !important;
            }

            /* Filter bar optimizations */
            .form-select-sm {
                font-size: 0.85rem !important;
                padding: 0.375rem 0.75rem !important;
            }

            .btn-sm {
                font-size: 0.8rem !important;
                padding: 0.375rem 0.75rem !important;
            }

            /* Product card optimizations */
            .product-card .card-body {
                padding: 0.75rem !important;
            }

            .product-card .card-title {
                font-size: 0.9rem !important;
                line-height: 1.3;
            }

            .product-card .badge {
                font-size: 0.7rem !important;
            }

            .product-card .price {
                font-size: 0.9rem !important;
            }

            .product-card .btn {
                font-size: 0.8rem !important;
                padding: 0.375rem 0.5rem !important;
            }

            .product-card img {
                height: 140px !important;
            }

            /* Empty state optimizations */
            .fa-box-open {
                font-size: 2.5rem !important;
            }

            .text-center h5 {
                font-size: 1.1rem !important;
            }

            .text-center p {
                font-size: 0.9rem !important;
            }
        }

        /* Extra Small Mobile (≤ 576px) */
        @media (max-width: 575.98px) {

            /* Container padding */
            .container {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            /* Header further reduced */
            h5 {
                font-size: 1rem !important;
            }

            /* Filter bar compact */
            .form-select-sm {
                font-size: 0.8rem !important;
                padding: 0.3rem 0.6rem !important;
            }

            .btn-sm {
                font-size: 0.75rem !important;
                padding: 0.3rem 0.6rem !important;
            }

            /* Product card more compact */
            .product-card .card-body {
                padding: 0.5rem !important;
            }

            .product-card .card-title {
                font-size: 0.85rem !important;
                line-height: 1.2;
                margin-bottom: 0.5rem !important;
            }

            .product-card .badge {
                font-size: 0.65rem !important;
                padding: 0.2rem 0.4rem !important;
            }

            .product-card .price {
                font-size: 0.85rem !important;
                margin-bottom: 0.5rem !important;
            }

            .product-card .btn {
                font-size: 0.75rem !important;
                padding: 0.3rem 0.4rem !important;
            }

            .product-card img {
                height: 120px !important;
            }

            /* Grid adjustments for very small screens */
            .col-6 {
                padding-left: 5px !important;
                padding-right: 5px !important;
            }

            .mb-3 {
                margin-bottom: 0.75rem !important;
            }

            /* Empty state smaller */
            .fa-box-open {
                font-size: 2rem !important;
            }

            .text-center h5 {
                font-size: 1rem !important;
            }

            .text-center p {
                font-size: 0.85rem !important;
            }

            /* Pagination smaller */
            .pagination {
                font-size: 0.8rem !important;
            }

            .page-link {
                padding: 0.3rem 0.6rem !important;
            }
        }

        /* Loading states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Improved touch targets for mobile */
        @media (max-width: 768px) {
            .btn {
                min-height: 36px;
                min-width: 36px;
            }

            .form-select {
                min-height: 36px;
            }

            .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }
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

        /* Product card hover effects */
        .product-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--bs-primary, #0d6efd);
        }

        /* Image loading state */
        .card-img-top {
            transition: opacity 0.3s ease;
        }

        .card-img-top.loading {
            opacity: 0.7;
        }

        /* Button states */
        .btn-add-cart:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        /* Badge improvements */
        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Price highlighting */
        .text-boomtale {
            color: var(--bs-primary, #0d6efd) !important;
            font-weight: 600;
        }

        /* Filter section improvements */
        .form-select:focus {
            border-color: var(--bs-primary, #0d6efd);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Grid responsiveness improvements */
        @media (max-width: 480px) {
            .col-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 360px) {

            /* For very small devices */
            .product-card .card-title {
                font-size: 0.8rem !important;
            }

            .product-card .price {
                font-size: 0.8rem !important;
            }

            .product-card .btn {
                font-size: 0.7rem !important;
                padding: 0.25rem 0.3rem !important;
            }

            .product-card img {
                height: 100px !important;
            }
        }

        /* Loading animation for add to cart */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .fa-spin {
            animation: spin 1s linear infinite;
        }

        /* Success state animation */
        .btn-success {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        /* Notification styles for mobile */
        @media (max-width: 768px) {
            .alert {
                font-size: 0.85rem !important;
                padding: 0.5rem 0.75rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">


        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3 d-none d-md-block">Semua Produk</h2>
                <h5 class="mb-3 d-md-none">Produk Digital</h5>


                <!-- Filter Bar - Condensed for Mobile -->
                <div class="row g-2 mb-4">

                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-sm" id="categoryFilter">
                            <option value="">Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-sm" id="sortFilter">
                            <option value="">Urutkan</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Termurah
                            </option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Termahal
                            </option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 d-none d-md-block">
                        <button class="btn btn-outline-secondary btn-sm w-100" id="resetFilter">
                            <i class="fas fa-undo me-1"></i>Reset
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsGrid">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <div class="card h-100 product-card">
                        @if ($product->featured_image)
                            <img src="{{ Storage::url($product->featured_image) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 160px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 160px;">
                                <i class="fas fa-{{ $product->isDigital() ? 'book' : 'play-circle' }} fa-2x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column p-2 p-md-3">
                            @if ($product->category)
                                <span class="badge bg-secondary text-white mb-2 d-none d-md-inline"
                                    style="font-size: 0.7rem;">
                                    {{ $product->category->name }}
                                </span>
                            @endif

                            <h6 class="card-title" style="font-size: 0.9rem; line-height: 1.3;">
                                <a href="{{ route('user.products.show', $product) }}"
                                    class="text-dark text-decoration-none">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </h6>

                            <div class="mt-auto">
                                <div class="price mb-2">
                                    <span class="fw-bold text-boomtale" style="font-size: 0.9rem;">
                                        {{ $product->formatted_price }}
                                    </span>
                                </div>

                                <div class="d-grid gap-1 d-md-flex">
                                    <a href="{{ route('user.products.show', $product) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        <span class="d-md-inline">Detail</span>
                                    </a>
                                    @auth
                                        <button class="btn btn-boomtale btn-sm btn-add-cart w-100"
                                            data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus me-1"></i>
                                            <span class="d-none d-md-inline">Keranjang</span>
                                            <span class="d-md-none">Beli</span>
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-boomtale btn-sm w-100">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada produk</h5>
                    <p class="text-muted">Produk akan segera hadir!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Filter functionality
            $('#categoryFilter, #typeFilter, #sortFilter').change(function() {
                applyFilters();
            });

            $('#resetFilter').click(function() {
                window.location.href = '{{ route('user.products.index') }}';
            });

            function applyFilters() {
                const params = new URLSearchParams();

                const category = $('#categoryFilter').val();
                const type = $('#typeFilter').val();
                const sort = $('#sortFilter').val();
                const search = '{{ request('search') }}';

                if (category) params.append('category', category);
                if (type) params.append('type', type);
                if (sort) params.append('sort', sort);
                if (search) params.append('search', search);

                window.location.href = '{{ route('user.products.index') }}?' + params.toString();
            }

            // Add to Cart
            $('.btn-add-cart').click(function() {
                const productId = $(this).data('product-id');
                const button = $(this);

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

                $.post('{{ route('user.cart.add') }}', {
                        product_id: productId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(response) {
                        if (response.success) {
                            button.html('<i class="fas fa-check me-1"></i>Berhasil').removeClass(
                                'btn-boomtale').addClass('btn-success');

                            setTimeout(() => {
                                button.prop('disabled', false).html(
                                        '<i class="fas fa-cart-plus me-1"></i><span class="d-md-none">Beli</span><span class="d-none d-md-inline">Keranjang</span>'
                                    )
                                    .removeClass('btn-success').addClass('btn-boomtale');
                            }, 2000);
                        } else {
                            alert(response.message);
                            button.prop('disabled', false).html(
                                '<i class="fas fa-cart-plus me-1"></i><span class="d-md-none">Beli</span><span class="d-none d-md-inline">Keranjang</span>'
                            );
                        }
                    })
                    .fail(function() {
                        alert('Gagal menambahkan ke keranjang');
                        button.prop('disabled', false).html(
                            '<i class="fas fa-cart-plus me-1"></i><span class="d-md-none">Beli</span><span class="d-none d-md-inline">Keranjang</span>'
                        );
                    });
            });
        });
    </script>
@endpush
