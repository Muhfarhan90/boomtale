{{-- filepath: d:\FREELANCE\boomtale\resources\views\user\orders\index.blade.php --}}

@extends('layouts.app')

@section('title', 'Riwayat Pesanan - Boomtale')

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat Pesanan</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">
                        <i class="fas fa-list-alt me-2 text-primary"></i>
                        Riwayat Pesanan
                    </h2>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#filterModal">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                        <a href="{{ route('user.products.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-shopping-bag me-1"></i>Belanja Lagi
                        </a>
                    </div>
                </div>

                <!-- Filter Bar -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body py-3">
                        <form method="GET" class="row g-3 align-items-center">
                            <div class="col-md-3">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="waiting_payment"
                                        {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran
                                    </option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                        Diproses</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                    </option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                        Selesai</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                        Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nomor invoice..." value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($orders->count() > 0)
                    <!-- Orders List -->
                    <div class="orders-list">
                        @foreach ($orders as $order)
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-header bg-white border-bottom">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <strong class="me-3">{{ $order->invoice_number }}</strong>
                                                <span class="badge bg-{{ $order->status_badge_color }} me-2">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                                @if ($order->is_expired && in_array($order->status, ['pending', 'waiting_payment']))
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-clock me-1"></i>Expired
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-calendar me-1"></i>
                                                <span data-utc-time="{{ $order->created_at->toIsoString() }}"></span>
                                            </small>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <div class="fw-bold text-primary h6 mb-1">{{ $order->formatted_total }}</div>
                                            <small class="text-muted">{{ $order->items_count }} item</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Order Items Preview -->
                                    <div class="order-items mb-3">
                                        @foreach ($order->orderItems as $item)
                                            <div
                                                class="d-flex align-items-center mb-2 pb-2 @if (!$loop->last) border-bottom @endif">
                                                <div class="product-thumbnail me-3">
                                                    @if ($item->product && $item->product->featured_image)
                                                        <img src="{{ Storage::url($item->product->featured_image) }}"
                                                            class="rounded"
                                                            style="width: 50px; height: 50px; object-fit: cover;"
                                                            alt="{{ $item->product_name }}">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium">{{ $item->product_name }}</div>
                                                    <small class="text-muted">
                                                        {{ $item->quantity }}x {{ $item->formatted_price }}
                                                    </small>
                                                </div>
                                                {{-- MODIFIKASI: Tambahkan tombol review di sini --}}
                                                @if ($order->status === 'completed')
                                                    <div class="text-end ms-2">
                                                        @if ($item->review)
                                                            <a href="#"
                                                                class="btn btn-outline-secondary btn-sm disabled"
                                                                aria-disabled="true">
                                                                <i class="fas fa-check-circle me-1"></i>Direview
                                                            </a>
                                                        @else
                                                            <a href="{{ route('user.reviews.create', $item) }}"
                                                                class="btn btn-outline-warning btn-sm">
                                                                <i class="fas fa-star me-1"></i>Beri Review
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Order Actions -->
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('user.orders.show', $order) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>

                                            @if ($order->can_be_cancelled)
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="cancelOrder({{ $order->id }})">
                                                    <i class="fas fa-times me-1"></i>Batal
                                                </button>
                                            @endif

                                            {{-- @if ($order->status === 'completed')
                                                <button type="button" class="btn btn-outline-success btn-sm"
                                                    onclick="reorder({{ $order->id }})">
                                                    <i class="fas fa-redo me-1"></i>Pesan Lagi
                                                </button>
                                            @endif --}}
                                        </div>

                                        <div class="order-actions">
                                            @if ($order->can_be_paid)
                                                <form action="{{ route('user.orders.show', $order) }}">
                                                    <button class="btn btn-success btn-sm" type="submit">
                                                        <i class="fas fa-credit-card me-1"></i>Lanjutkan Pembayaran
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($order->status === 'completed')
                                                @php
                                                    $hasDigitalProducts =
                                                        $order->orderItems->where('product.type', 'digital')->count() >
                                                        0;
                                                @endphp
                                                @if ($hasDigitalProducts)
                                                    <a href="{{ route('user.user-products.index') }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>Ke Produk Saya
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Order Timeline -->
                                    @if ($order->status !== 'pending')
                                        <div class="order-timeline mt-3 pt-3 border-top">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Status terakhir: {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                @if ($order->expired_at && $order->status === 'waiting_payment')
                                                    <span class="ms-2">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Batas waktu:
                                                        <span class="countdown-timer fw-bold text-warning"
                                                            data-expired="{{ $order->expired_at->toISOString() }}">
                                                            Loading...
                                                        </span>
                                                    </span>
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->withQueryString()->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="empty-orders-illustration mb-4">
                            <i class="fas fa-shopping-cart fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum Ada Pesanan</h4>
                            <p class="text-muted mb-4">
                                Anda belum pernah melakukan pemesanan.
                                Ayo mulai berbelanja sekarang!
                            </p>
                            <div class="d-grid gap-2 d-md-block">
                                <a href="{{ route('user.products.index') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fas fa-filter me-2"></i>Filter Pesanan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="waiting_payment"
                                    {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    Diproses</option>
                                {{-- <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                </option> --}}
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="search" class="form-label">Cari Invoice</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Masukkan nomor invoice" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Downloads Modal -->
    <div class="modal fade" id="downloadsModal" tabindex="-1" aria-labelledby="downloadsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="downloadsModalLabel">
                        <i class="fas fa-download me-2"></i>Download Produk Digital
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="downloadsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelOrderModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Pembatalan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan pesanan ini?</p>
                    <p class="text-muted"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelOrder">
                        <i class="fas fa-times me-2"></i>Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-thumbnail img {
            transition: transform 0.3s ease;
        }

        .product-thumbnail:hover img {
            transform: scale(1.05);
        }

        .order-timeline {
            position: relative;
        }

        .empty-orders-illustration i {
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
            h2 {
                font-size: 1.5rem !important;
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

            .card-header {
                padding: 0.75rem !important;
            }

            .card-body {
                padding: 0.75rem !important;
            }

            .btn-sm {
                font-size: 0.8rem !important;
                padding: 0.375rem 0.5rem !important;
            }

            .form-select-sm {
                font-size: 0.85rem !important;
            }

            .badge {
                font-size: 0.7rem !important;
            }

            .product-thumbnail img,
            .product-thumbnail div {
                width: 40px !important;
                height: 40px !important;
            }

            .fw-medium {
                font-size: 0.9rem !important;
            }

            .breadcrumb {
                font-size: 0.8rem;
            }

            .modal-body {
                font-size: 0.9rem;
            }

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
            .container {
                padding-left: 10px !important;
                padding-right: 10px !important;
            }

            h2 {
                font-size: 1.3rem !important;
            }

            .card-header .row>div {
                margin-bottom: 0.5rem;
            }

            .btn-group .btn {
                font-size: 0.75rem !important;
                padding: 0.3rem 0.4rem !important;
            }

            .order-actions .btn {
                font-size: 0.75rem !important;
                padding: 0.3rem 0.4rem !important;
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

                setTimeout(() => {
                    $('.alert').fadeOut();
                    setTimeout(() => {
                        $('.alert').remove();
                    }, 300);
                }, 3000);
            }

            // PERBAIKAN: Function untuk cancel order
            window.cancelOrder = function(orderId) {
                if (!confirm('Yakin ingin membatalkan pesanan ini?')) return;

                $.ajax({
                    url: '/orders/' + orderId + '/cancel', // Pastikan URL sesuai dengan route
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Gunakan meta token
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification(response.message, 'success');
                            setTimeout(function() {
                                location.reload();
                            }, 1200);
                        } else {
                            showNotification(response.message, 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', xhr.responseText);
                        let errorMessage = 'Gagal membatalkan pesanan';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showNotification(errorMessage, 'danger');
                    }
                });
            }
        });

        // Countdown Timer untuk semua order
        function updateCountdowns() {
            $('.countdown-timer').each(function() {
                const element = $(this);
                const expiredAt = new Date(element.data('expired')).getTime();
                const now = new Date().getTime();
                const distance = expiredAt - now;

                if (distance < 0) {
                    element.html('EXPIRED').removeClass('text-warning').addClass('text-danger');
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                element.html(
                    String(hours).padStart(2, '0') + ':' +
                    String(minutes).padStart(2, '0') + ':' +
                    String(seconds).padStart(2, '0')
                );
            });
        }

        if ($('.countdown-timer').length > 0) {
            updateCountdowns();
            setInterval(updateCountdowns, 1000);
        }
    </script>
@endpush
