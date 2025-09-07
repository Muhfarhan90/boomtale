@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $order->invoice_number)

@push('styles')
    <style>
        .item-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.5em 0.75em;
        }

        /* Countdown Timer Styles */
        .countdown-container {
            background: linear-gradient(135deg, #fff3cd 0%, #fef3bd 100%);
            border: 2px solid #ffc107;
            border-radius: 12px;
            padding: 1rem;
            margin: 1rem 0;
            animation: pulse-warning 2s infinite;
        }

        .countdown-container.danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #dc3545;
            animation: pulse-danger 1s infinite;
        }

        .countdown-container.expired {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            border-color: #6c757d;
            animation: none;
        }

        .countdown-display {
            font-family: 'Courier New', monospace;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            margin: 0.5rem 0;
        }

        .countdown-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }

        .time-unit {
            display: inline-block;
            margin: 0 0.5rem;
            text-align: center;
        }

        .time-number {
            display: block;
            font-size: 1.8rem;
            font-weight: bold;
            line-height: 1;
        }

        .time-text {
            display: block;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.25rem;
        }

        @keyframes pulse-warning {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
            50% { transform: scale(1.02); box-shadow: 0 0 0 8px rgba(255, 193, 7, 0.1); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
        }

        @keyframes pulse-danger {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(220, 53, 69, 0.1); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
        }

        .payment-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .payment-section {
                padding: 1rem;
            }

            .countdown-display {
                font-size: 1.2rem;
            }
            .time-number {
                font-size: 1.4rem;
            }
            .time-text {
                font-size: 0.6rem;
            }
            .time-unit {
                margin: 0 0.3rem;
            }

            .status-badge {
                font-size: 0.8rem;
                padding: 0.4em 0.6em;
            }

            font-size: 0.9rem;

            .item-thumbnail {
                width: 50px;
                height: 50px;
            }

        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1 class="h3 mb-0">Detail Pesanan</h1>
                <p class="text-muted mb-0">#{{ $order->invoice_number }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                <div>Tanggal Pesanan :
                    <span class="text-muted" data-utc-time="{{ $order->created_at->toIsoString() }}"></span>
                </div>
                <div>
                    <span class="badge rounded-pill status-badge bg-primary">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Countdown Timer Section - Moved to top and more prominent -->
                @if ($order->expired_at && $order->status === 'waiting_payment')
                    <div class="countdown-container" id="countdown-container">
                        <div class="text-center">
                            <h5 class="mb-2">
                                <i class="fas fa-clock me-2"></i>
                                Batas Waktu Pembayaran
                            </h5>
                            <div class="countdown-display" id="countdown-display">
                                <div class="time-unit">
                                    <span class="time-number" id="hours">00</span>
                                    <span class="time-text">Jam</span>
                                </div>
                                <div class="time-unit">
                                    <span class="time-number" id="minutes">00</span>
                                    <span class="time-text">Menit</span>
                                </div>
                                <div class="time-unit">
                                    <span class="time-number" id="seconds">00</span>
                                    <span class="time-text">Detik</span>
                                </div>
                            </div>
                            <div class="countdown-label">
                                <small class="text-muted">
                                    Expired pada: {{ $order->expired_at->format('d M Y, H:i') }}
                                </small>
                            </div>
                            <div id="countdown-message" class="mt-2"></div>
                        </div>
                    </div>
                @endif

                <div class="row g-4">
                    <!-- Order Items -->
                    <div class="col-lg-8">
                        <h5 class="mb-3">Produk Pesanan</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Produk</th>
                                        <th scope="col" class="text-center">Kuantitas</th>
                                        <th scope="col" class="text-end">Harga Satuan</th>
                                        <th scope="col" class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0; @endphp
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $item->product->featured_image ? Storage::url($item->product->featured_image) : 'https://via.placeholder.com/150' }}"
                                                        alt="{{ $item->product->name }}" class="item-thumbnail me-3">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">{{ $item->quantity }}</td>
                                            <td class="text-end align-middle">Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="text-end align-middle fw-bold">Rp
                                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                        @php $subtotal += $item->price * $item->quantity; @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="col-lg-4">
                        <div class="card bg-light border">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Ringkasan Pembayaran</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        Subtotal
                                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        Biaya Admin
                                        <span>Rp 0</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent fw-bold fs-5">
                                        Total
                                        <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </li>
                                </ul>

                                @if ($order->status == 'waiting_payment' && $order->transaction && $order->transaction->snap_token)
                                    <div class="payment-section">
                                        <div class="d-grid">
                                            <button id="pay-button" class="btn btn-success btn-lg">
                                                <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                            </button>
                                        </div>
                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                Pembayaran aman dengan Midtrans
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card bg-light border mt-3">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Detail Pembayaran</h5>
                                <p class="mb-1"><strong class="d-inline-block" style="width: 120px;">Metode</strong>:
                                    {{ ucwords(str_replace('_', ' ', $order->transaction->payment_type ?? '-')) }}</p>
                                <p class="mb-1"><strong class="d-inline-block" style="width: 120px;">Status</strong>:
                                    {{ ucwords($order->transaction->status ?? '-') }}</p>
                                <p class="mb-0"><strong class="d-inline-block" style="width: 120px;">ID Transaksi</strong>: 
                                    <span class="text-break">{{ $order->transaction->transaction_id ?? 'Belum tersedia' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@if ($order->status == 'waiting_payment' && $order->transaction && $order->transaction->snap_token)
    @push('scripts')
        <!-- Midtrans Snap.js -->
        <script src="https://app.{{ config('midtrans.isProduction') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                // Payment Handler
                document.getElementById('pay-button').addEventListener('click', function() {
                    window.snap.pay('{{ $order->transaction->snap_token }}', {
                        onSuccess: function(result) {
                            console.log('Payment Success:', result);
                            Swal.fire({
                                icon: 'success',
                                title: 'Pembayaran Berhasil!',
                                text: 'Halaman akan dimuat ulang.',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        },
                        onPending: function(result) {
                            console.log('Payment Pending:', result);
                            Swal.fire({
                                icon: 'info',
                                title: 'Menunggu Pembayaran',
                                text: 'Selesaikan pembayaran Anda.',
                            });
                        },
                        onError: function(result) {
                            console.error('Payment Error:', result);
                            Swal.fire('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran.', 'error');
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                        }
                    });
                });

                // Enhanced Countdown Timer
                @if ($order->expired_at && $order->status === 'waiting_payment')
                const expiredAt = new Date('{{ $order->expired_at->toISOString() }}').getTime();
                const countdownContainer = $('#countdown-container');
                const hoursElement = $('#hours');
                const minutesElement = $('#minutes');
                const secondsElement = $('#seconds');
                const messageElement = $('#countdown-message');
                const payButton = $('#pay-button');

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = expiredAt - now;

                    if (distance < 0) {
                        // Expired
                        hoursElement.text('00');
                        minutesElement.text('00');
                        secondsElement.text('00');
                        
                        countdownContainer.removeClass('danger').addClass('expired');
                        messageElement.html(`
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Waktu pembayaran telah habis!</strong><br>
                                <small>Silakan lakukan pemesanan ulang.</small>
                            </div>
                        `);
                        
                        payButton.prop('disabled', true)
                                 .removeClass('btn-success')
                                 .addClass('btn-secondary')
                                 .html('<i class="fas fa-times me-2"></i>Pembayaran Expired');
                        
                        return;
                    }

                    // Calculate time units
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Update display
                    hoursElement.text(String(hours).padStart(2, '0'));
                    minutesElement.text(String(minutes).padStart(2, '0'));
                    secondsElement.text(String(seconds).padStart(2, '0'));

                    // Change style based on remaining time
                    if (distance < 1800000) { // Less than 30 minutes
                        countdownContainer.addClass('danger');
                        if (distance < 300000) { // Less than 5 minutes
                            messageElement.html(`
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Segera lakukan pembayaran!</strong><br>
                                    <small>Waktu hampir habis.</small>
                                </div>
                            `);
                        } else {
                            messageElement.html(`
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Segera selesaikan pembayaran Anda.</small>
                                </div>
                            `);
                        }
                    } else {
                        countdownContainer.removeClass('danger');
                        messageElement.html(`
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <small>Masih banyak waktu untuk menyelesaikan pembayaran.</small>
                            </div>
                        `);
                    }
                }

                // Initial update and set interval
                updateCountdown();
                const countdownInterval = setInterval(updateCountdown, 1000);

                // Clean up interval when page is unloaded
                $(window).on('beforeunload', function() {
                    clearInterval(countdownInterval);
                });
                @endif
            });
        </script>
    @endpush
@endif