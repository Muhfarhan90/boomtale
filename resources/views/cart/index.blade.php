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
                        <div class="col-lg-8">
                            @foreach ($cartItems as $item)
                                <div class="cart-item border-bottom p-3 mb-3" data-cart-id="{{ $item->id }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 col-3">
                                            <div class="product-image-wrapper text-center">
                                                @if ($item->product->featured_image)
                                                    <img src="{{ Storage::url($item->product->featured_image) }}"
                                                        class="img-fluid rounded" alt="{{ $item->product->name }}"
                                                        style="height: 70px; width: 70px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                        style="height: 70px; width: 70px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-4">
                                            <div class="product-info">
                                                <h6 class="product-name mb-1 fs-6">
                                                    <a href="{{ route('user.products.show', $item->product) }}"
                                                        class="text-decoration-none text-dark">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted d-block mb-1">
                                                    <i
                                                        class="fas fa-tag me-1"></i>{{ $item->product->category->name ?? 'Uncategorized' }}
                                                </small>
                                                @if ($item->product->type === 'digital')
                                                    <span class="badge bg-info badge-sm">
                                                        <i class="fas fa-download me-1"></i>Digital
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-5 col-5">
                                            <div class="d-flex justify-content-end align-items-center gap-3">
                                                <div class="product-price">
                                                    <span class="fw-bold text-primary fs-6">
                                                        {{ $item->product->formatted_price }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="removeItem({{ $item->id }})"
                                                        title="Hapus dari keranjang">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="d-none d-lg-inline ms-1">Hapus</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0 fs-6">
                                        <i class="fas fa-calculator me-2"></i>Ringkasan Pesanan
                                    </h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="order-summary">
                                        <!-- Item details -->
                                        <div class="mb-3">
                                            @foreach ($cartItems as $item)
                                                <div
                                                    class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom border-light">
                                                    <div class="flex-grow-1 me-2">
                                                        <div class="fw-medium text-truncate fs-7" style="max-width: 150px;"
                                                            title="{{ $item->product->name }}">
                                                            {{ Str::limit($item->product->name, 20) }}
                                                        </div>
                                                        @if ($item->product->type === 'digital')
                                                            <small class="text-muted">Digital Product</small>
                                                        @endif
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="fw-bold text-primary fs-7">
                                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <hr>

                                        <!-- Total calculation -->
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fs-7">Subtotal ({{ $cartItems->count() }} produk)</span>
                                            <span id="cart-subtotal" class="fs-7">Rp
                                                {{ number_format($total, 0, ',', '.') }}</span>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fs-7">Biaya Admin</span>
                                            <span class="text-success fs-7">Gratis</span>
                                        </div>

                                        {{-- <div class="d-flex justify-content-between mb-2">
                                            <span class="fs-7">Pajak (PPN 11%)</span>
                                            <span id="cart-tax" class="fs-7">Rp
                                                {{ number_format($total * 0.11, 0, ',', '.') }}</span>
                                        </div> --}}

                                        <hr class="border-2">

                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="fw-bold fs-6">Total Pembayaran</span>
                                            <span class="fw-bold text-primary fs-5" id="cart-total">
                                                Rp {{ number_format($total, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <a href="{{ route('user.checkout.index') }}" class="btn btn-primary">
                                                <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                                            </a>
                                            <a href="{{ route('user.products.index') }}"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                                            </a>
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
                                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">Keranjang Belanja Kosong</h4>
                                    <p class="text-muted mb-4 fs-6">
                                        Sepertinya Anda belum menambahkan produk apapun ke keranjang belanja.
                                        Ayo mulai berbelanja sekarang!
                                    </p>
                                    <div class="d-grid gap-2 d-md-block">
                                        <a href="{{ route('user.products.index') }}" class="btn btn-primary">
                                            <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                                        </a>
                                        <a href="{{ route('user.home') }}" class="btn btn-outline-secondary">
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
                    <h5 class="modal-title fs-6" id="removeItemModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-6">Apakah Anda yakin ingin menghapus produk ini dari keranjang?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmRemove">
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
                    <h5 class="modal-title fs-6" id="clearCartModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Kosongkan Keranjang
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-6">Apakah Anda yakin ingin mengosongkan semua produk dari keranjang?</p>
                    <p class="text-muted"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmClearCart()">
                        <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Custom font sizes */
        .fs-7 {
            font-size: 0.875rem !important;
        }

        .cart-item {
            transition: all 0.3s ease;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #f0f0f0;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-image-wrapper img {
            transition: transform 0.3s ease;
            border-radius: 6px;
        }

        .product-image-wrapper:hover img {
            transform: scale(1.05);
        }

        .sticky-top {
            z-index: 1020;
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
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            border-radius: 0 !important;
        }

        .btn {
            border-radius: 6px;
        }

        .badge-sm {
            font-size: 0.7rem;
            padding: 0.25em 0.5em;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            h2 {
                font-size: 1.3rem;
            }

            .cart-item {
                padding: 0.75rem;
                margin-bottom: 0.75rem;
            }

            .product-image-wrapper img,
            .product-image-wrapper div {
                height: 50px;
                width: 50px;
            }

            .sticky-top {
                position: relative !important;
                top: auto !important;
                margin-top: 1.5rem;
            }

            .card-body {
                padding: 1rem;
            }

            .fs-6 {
                font-size: 0.9rem !important;
            }

            .fs-7 {
                font-size: 0.8rem !important;
            }

            .product-name {
                font-size: 0.85rem !important;
            }

            .btn {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }

            .btn-sm {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }
        }

        @media (max-width: 576px) {
            h2 {
                font-size: 1.2rem;
            }

            .cart-item {
                padding: 0.5rem;
            }

            .product-image-wrapper img,
            .product-image-wrapper div {
                height: 45px;
                width: 45px;
            }

            .fs-5 {
                font-size: 1rem !important;
            }

            .fs-6 {
                font-size: 0.85rem !important;
            }

            .fs-7 {
                font-size: 0.75rem !important;
            }

            .product-name {
                font-size: 0.8rem !important;
            }
        }

        .border-light {
            border-color: #e9ecef !important;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
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

                setTimeout(() => {
                    $('.alert').fadeOut();
                }, 5000);
            }

            let itemToRemove = null;

            window.removeItem = function(cartId) {
                itemToRemove = cartId;
                $('#removeItemModal').modal('show');
            };

            $('#confirmRemove').click(function() {
                if (itemToRemove) {
                    $.ajax({
                        url: `/cart/${itemToRemove}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(`.cart-item[data-cart-id="${itemToRemove}"]`).fadeOut(300,
                                function() {
                                    $(this).remove();

                                    if ($('.cart-item').length === 0) {
                                        location.reload();
                                    }
                                });

                            showNotification('Produk berhasil dihapus dari keranjang');
                            $('#removeItemModal').modal('hide');

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

            window.clearCart = function() {
                $('#clearCartModal').modal('show');
            };

            window.confirmClearCart = function() {
                $.ajax({
                    url: '/cart',
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
        });
    </script>
@endpush
