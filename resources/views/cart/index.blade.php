@extends('layouts.app')

@section('title', 'Keranjang Belanja - Boomtale')

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>
                        Keranjang Belanja
                    </h2>
                    @if ($cartItems->count() > 0)
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearCart()">
                            <i class="fas fa-trash me-1"></i>Kosongkan Keranjang
                        </button>
                    @endif
                </div>

                @if ($cartItems->count() > 0)
                    <div class="row">
                        <!-- Cart Items -->
                        <div class="cart-item border-bottom p-4" data-cart-id="{{ $item->id }}">
                            <div class="row align-items-center">
                                <div class="col-md-2 col-sm-3 col-4">
                                    <div class="product-image-wrapper text-center">
                                        @if ($item->product->featured_image)
                                            <img src="{{ Storage::url($item->product->featured_image) }}"
                                                class="img-fluid rounded" alt="{{ $item->product->name }}"
                                                style="height: 80px; width: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="height: 80px; width: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4 col-sm-9 col-8">
                                    <div class="product-info">
                                        <h6 class="product-name mb-1">
                                            <a href="{{ route('user.products.show', $item->product) }}"
                                                class="text-decoration-none text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted d-block">
                                            <i
                                                class="fas fa-tag me-1"></i>{{ $item->product->category->name ?? 'Uncategorized' }}
                                        </small>
                                        @if ($item->product->type === 'digital')
                                            <div class="mt-1">
                                                <span class="badge bg-info">
                                                    <i class="fas fa-download me-1"></i>Digital
                                                </span>
                                            </div>
                                        @endif
                                        <!-- Show price on mobile here -->
                                        <div class="d-md-none mt-2">
                                            <span class="fw-bold text-primary">
                                                {{ $item->product->formatted_price }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 d-none d-md-block">
                                    <div class="product-price text-center">
                                        <span class="fw-bold text-primary">
                                            {{ $item->product->formatted_price }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-8 col-12">
                                    <div class="quantity-controls d-flex align-items-center justify-content-center">
                                        @if ($item->product->type !== 'digital')
                                            <div class="input-group" style="width: 120px;">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number"
                                                    class="form-control form-control-sm text-center quantity-input"
                                                    value="{{ $item->quantity }}" min="1" max="10"
                                                    data-cart-id="{{ $item->id }}"
                                                    onchange="updateQuantity({{ $item->id }}, this.value)">
                                                <button class="btn btn-outline-secondary btn-sm" type="button"
                                                    onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    {{ $item->quantity >= 10 ? 'disabled' : '' }}>
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">Qty: 1</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-4 col-12">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                            onclick="removeItem({{ $item->id }})" title="Hapus dari keranjang">
                                            <i class="fas fa-trash"></i>
                                            <span class="d-sm-none ms-1">Hapus</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Subtotal for this item -->
                            <div class="row mt-2">
                                <div class="col-12 text-center text-md-end">
                                    <small class="text-muted">Subtotal: </small>
                                    <span class="fw-bold item-subtotal">
                                        Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calculator me-2"></i>Ringkasan Pesanan
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="order-summary">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal ({{ $cartItems->count() }} item)</span>
                                            <span id="cart-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Biaya Admin</span>
                                            <span class="text-muted">Gratis</span>
                                        </div>

                                        <hr>

                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-bold">Total</span>
                                            <span class="fw-bold text-primary h5" id="cart-total">
                                                Rp {{ number_format($total, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('user.checkout.index') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                                            </a>
                                            <a href="{{ route('user.products.index') }}" class="btn btn-outline-secondary">
                                                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Promo Section -->
                                <div class="card-footer bg-light">
                                    <div class="promo-section">
                                        <h6 class="mb-2">
                                            <i class="fas fa-tag me-2 text-warning"></i>Kode Promo
                                        </h6>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Masukkan kode promo"
                                                id="promoCode">
                                            <button class="btn btn-outline-primary" type="button"
                                                onclick="applyPromo()">
                                                Terapkan
                                            </button>
                                        </div>
                                        <small class="text-muted mt-1 d-block">
                                            <i class="fas fa-info-circle me-1"></i>Kode promo akan diterapkan saat checkout
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Info -->
                            <div class="card border-0 shadow-sm mt-3">
                                <div class="card-body text-center">
                                    <div class="security-badges">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                                <small class="d-block">Pembayaran Aman</small>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-truck fa-2x text-primary mb-2"></i>
                                                <small class="d-block">Pengiriman Cepat</small>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-headset fa-2x text-info mb-2"></i>
                                                <small class="d-block">Support 24/7</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Empty Cart -->
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="text-center py-5">
                                <div class="empty-cart-illustration mb-4">
                                    <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                                    <h3 class="text-muted">Keranjang Belanja Kosong</h3>
                                    <p class="text-muted mb-4">
                                        Sepertinya Anda belum menambahkan produk apapun ke keranjang belanja.
                                        Ayo mulai berbelanja sekarang!
                                    </p>
                                    <div class="d-grid gap-2 d-md-block">
                                        <a href="{{ route('user.products.index') }}" class="btn btn-primary btn-lg">
                                            <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                                        </a>
                                        <a href="{{ route('user.home') }}" class="btn btn-outline-secondary btn-lg">
                                            <i class="fas fa-home me-2"></i>Kembali ke Home
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Remove Item Modal -->
    <div class="modal fade" id="removeItemModal" tabindex="-1" aria-labelledby="removeItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeItemModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk ini dari keranjang?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmRemove">
                        <i class="fas fa-trash me-2"></i>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Clear Cart Modal -->
    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clearCartModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Kosongkan Keranjang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengosongkan semua produk dari keranjang?</p>
                    <p class="text-muted"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmClearCart()">
                        <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
        }

        .product-image-wrapper img {
            transition: transform 0.3s ease;
        }

        .product-image-wrapper:hover img {
            transform: scale(1.05);
        }

        .quantity-input {
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .sticky-top {
            z-index: 1020;
        }

        .security-badges i {
            transition: transform 0.3s ease;
        }

        .security-badges i:hover {
            transform: scale(1.1);
        }

        .empty-cart-illustration i {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {

            /* Reduce font sizes for mobile */
            h2 {
                font-size: 1.5rem !important;
            }

            h5 {
                font-size: 1.1rem !important;
            }

            h6 {
                font-size: 0.95rem !important;
            }

            .card-header h5 {
                font-size: 1rem !important;
            }

            /* Compact layout for cart items */
            .cart-item {
                padding: 1rem !important;
            }

            .cart-item .row>div {
                margin-bottom: 0.75rem;
            }

            /* Mobile-friendly product info */
            .product-name {
                font-size: 0.9rem !important;
                line-height: 1.3;
            }

            .product-price {
                font-size: 0.95rem !important;
            }

            /* Center quantity controls */
            .quantity-controls {
                justify-content: center;
            }

            .quantity-controls .input-group {
                width: 100px !important;
            }

            /* Smaller buttons for mobile */
            .btn-sm {
                font-size: 0.75rem !important;
                padding: 0.25rem 0.5rem !important;
            }

            /* Order summary adjustments */
            .sticky-top {
                position: relative !important;
                top: auto !important;
            }

            .order-summary {
                font-size: 0.9rem;
            }

            .order-summary .fw-bold {
                font-size: 1rem !important;
            }

            /* Compact security badges */
            .security-badges small {
                font-size: 0.7rem !important;
            }

            .security-badges i {
                font-size: 1.2rem !important;
            }

            /* Mobile breadcrumb */
            .breadcrumb {
                font-size: 0.8rem;
            }

            /* Mobile modal adjustments */
            .modal-body {
                font-size: 0.9rem;
            }

            /* Compact notifications for mobile */
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

            h2 {
                font-size: 1.3rem !important;
            }

            .cart-item {
                padding: 0.75rem !important;
            }

            .product-image-wrapper img,
            .product-image-wrapper div {
                height: 60px !important;
                width: 60px !important;
            }

            .product-name {
                font-size: 0.85rem !important;
            }

            .product-price {
                font-size: 0.9rem !important;
            }

            .btn-lg {
                font-size: 0.95rem !important;
                padding: 0.5rem 1rem !important;
            }

            /* Stack cart item content vertically on very small screens */
            .cart-item .col-md-2,
            .cart-item .col-md-4,
            .cart-item .col-md-3,
            .cart-item .col-md-1 {
                flex: 0 0 100%;
                max-width: 100%;
                text-align: center;
                margin-bottom: 0.5rem;
            }

            .cart-item .col-md-2 {
                text-align: center;
            }

            .cart-item .col-md-4 {
                text-align: left;
            }

            .item-subtotal {
                font-size: 0.9rem !important;
            }

            /* Promo section mobile */
            .promo-section h6 {
                font-size: 0.95rem !important;
            }

            .promo-section small {
                font-size: 0.75rem !important;
            }
        }

        /* Loading states */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Custom scrollbar for mobile */
        @media (max-width: 768px) {
            .card-body {
                max-height: none;
            }

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

            .quantity-input {
                min-height: 38px;
            }

            .form-control {
                font-size: 16px;
                /* Prevents zoom on iOS */
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Notification function
            function showNotification(message, type = 'success') {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';

                const notification = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="fas fa-${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

                $('body').append(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 5000);
            }

            // Update cart totals
            function updateCartTotals() {
                let total = 0;
                $('.cart-item').each(function() {
                    const quantity = $(this).find('.quantity-input').val();
                    const priceText = $(this).find('.product-price').text();
                    const price = priceText.replace(/[^\d]/g, '');
                    const subtotal = parseInt(price) * parseInt(quantity);

                    $(this).find('.item-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
                    total += subtotal;
                });

                $('#cart-subtotal').text('Rp ' + total.toLocaleString('id-ID'));
                $('#cart-total').text('Rp ' + total.toLocaleString('id-ID'));
            }

            // Update quantity function
            window.updateQuantity = function(cartId, newQuantity) {
                if (newQuantity < 1 || newQuantity > 10) {
                    showNotification('Kuantitas harus antara 1-10', 'error');
                    return;
                }

                const cartItem = $(`.cart-item[data-cart-id="${cartId}"]`);
                const buttons = cartItem.find('button');
                buttons.prop('disabled', true);

                $.ajax({
                    url: `/user/cart/${cartId}`, // Fix: Direct URL construction
                    method: 'PUT',
                    data: {
                        quantity: newQuantity,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            cartItem.find('.quantity-input').val(newQuantity);
                            updateCartTotals();
                            showNotification(response.message);

                            // Update cart count in header
                            if (typeof window.updateCartCount === 'function') {
                                window.updateCartCount();
                            }
                        } else {
                            showNotification(response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        const response = xhr.responseJSON;
                        showNotification(response?.message || 'Gagal memperbarui kuantitas',
                            'error');
                    },
                    complete: function() {
                        buttons.prop('disabled', false);
                    }
                });
            };

            // Remove item function
            let itemToRemove = null;

            window.removeItem = function(cartId) {
                itemToRemove = cartId;
                $('#removeItemModal').modal('show');
            };

            $('#confirmRemove').click(function() {
                if (itemToRemove) {
                    $.ajax({
                        url: `/user/cart/${itemToRemove}`, // Fix: Direct URL construction
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(`.cart-item[data-cart-id="${itemToRemove}"]`).fadeOut(300,
                                function() {
                                    $(this).remove();
                                    updateCartTotals();

                                    // Check if cart is empty
                                    if ($('.cart-item').length === 0) {
                                        location.reload();
                                    }
                                });

                            showNotification('Produk berhasil dihapus dari keranjang');
                            $('#removeItemModal').modal('hide');

                            // Update cart count in header
                            if (typeof window.updateCartCount === 'function') {
                                window.updateCartCount();
                            }
                        },
                        error: function() {
                            showNotification('Gagal menghapus produk', 'error');
                            $('#removeItemModal').modal('hide');
                        }
                    });
                }
            });

            // Clear cart function
            window.clearCart = function() {
                $('#clearCartModal').modal('show');
            };

            window.confirmClearCart = function() {
                $.ajax({
                    url: '/user/cart', // Fix: Direct URL construction
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        showNotification('Keranjang berhasil dikosongkan');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function() {
                        showNotification('Gagal mengosongkan keranjang', 'error');
                    },
                    complete: function() {
                        $('#clearCartModal').modal('hide');
                    }
                });
            };

            // Apply promo function
            window.applyPromo = function() {
                const promoCode = $('#promoCode').val().trim();

                if (!promoCode) {
                    showNotification('Masukkan kode promo terlebih dahulu', 'error');
                    return;
                }

                showNotification('Kode promo akan diterapkan saat checkout', 'info');
            };

            // Quantity input validation with debounce
            let updateTimeout;
            $('.quantity-input').on('input', function() {
                const value = parseInt($(this).val());
                const cartId = $(this).data('cart-id');

                clearTimeout(updateTimeout);

                if (value >= 1 && value <= 10) {
                    updateTimeout = setTimeout(() => {
                        updateQuantity(cartId, value);
                    }, 800);
                }
            });

            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush
