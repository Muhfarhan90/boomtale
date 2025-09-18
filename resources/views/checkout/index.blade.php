{{-- filepath: d:\FREELANCE\boomtale\resources\views\checkout\index.blade.php --}}
@extends('layouts.app')

@section('title', 'Checkout - Boomtale')

@push('styles')
    <style>
        .order-summary-card {
            position: sticky;
            top: 100px;
        }

        .item-list .item {
            border-bottom: 1px solid #eee;
        }

        .item-list .item:last-child {
            border-bottom: none;
        }

        .item-thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="display-5">Checkout</h1>
                <p class="text-muted">Complete your order by filling in the details below.</p>
            </div>
        </div>

        <form id="checkout-form">
            @csrf
            <div class="row g-5">
                <!-- Left Column: Billing & Items -->
                <div class="col-lg-7">
                    <!-- Billing Details -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0">
                                <i class="fas fa-user-circle text-boomtale me-2"></i>
                                Shipping Details
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                    value="{{ auth()->user()->name }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    value="{{ auth()->user()->email }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number <span
                                        class="text-danger">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                    value="{{ auth()->user()->phone_number }}" placeholder="Example: 081234567890" required>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0">
                                <i class="fas fa-shopping-bag text-boomtale me-2"></i>
                                Order Items ({{ $cartItems->count() }})
                            </h4>
                        </div>
                        <div class="card-body p-2 p-md-3 item-list">
                            @foreach ($cartItems as $item)
                                <div class="d-flex align-items-center p-2 item">
                                    <img src="{{ $item->product->featured_image ? Storage::url($item->product->featured_image) : 'https://via.placeholder.com/150' }}"
                                        alt="{{ $item->product->name }}" class="item-thumbnail me-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity ?? 1 }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-boomtale">Rp
                                            {{ number_format($item->product->discount_price * ($item->quantity ?? 1), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 order-summary-card">
                        <div class="card-header bg-boomtale text-white py-3">
                            <h4 class="mb-0">
                                <i class="fas fa-receipt me-2"></i>
                                Order Summary
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Subtotal
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    Admin Fee
                                    <span class="text-success">Free</span>
                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center px-0 bg-light fw-bold fs-5">
                                    Total Payment
                                    <span class="text-boomtale">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                            <div class="d-grid mt-4">
                                <button type="submit" id="pay-button" class="btn btn-boomtale btn-lg">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Pay Now
                                </button>
                            </div>
                            <div class="text-center mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i> Secure transaction with Midtrans
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <!-- Midtrans Snap.js -->
    <script src="https://app.{{ config('midtrans.isProduction') ? '' : 'sandbox.' }}midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        document.getElementById('checkout-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const payButton = document.getElementById('pay-button');
            const originalButtonText = payButton.innerHTML;
            payButton.disabled = true;
            payButton.innerHTML =
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...`;

            const formData = new FormData(this);

            fetch('{{ route('user.checkout.process') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if (data.error) {
                        throw new Error(data.error);
                    }

                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                console.log('Payment Success:', result);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Payment Successful!',
                                    text: 'You will be redirected to My Products page.',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('user.user-products.index') }}';
                                });
                            },
                            onPending: function(result) {
                                console.log('Payment Pending:', result);
                                Swal.fire({
                                    icon: 'info',
                                    title: 'Waiting for Payment',
                                    text: 'Complete your payment. This page will redirect to the order details.',
                                    timer: 3000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '/orders/' + data
                                        .order_id;
                                });
                            },
                            onError: function(result) {
                                console.error('Payment Error:', result);
                                Swal.fire('Payment Failed',
                                    'An error occurred while processing the payment.', 'error');
                                payButton.disabled = false;
                                payButton.innerHTML = originalButtonText;
                            },
                            onClose: function() {
                                console.log('Payment popup closed');

                                // Tampilkan popup peringatan terlebih dahulu
                                Swal.fire({
                                    title: 'Payment Cancelled',
                                    text: 'You closed the payment window. You can continue the payment later from the My Orders page.',
                                    icon: 'warning',
                                    timer: 4000, // Beri waktu 4 detik untuk membaca
                                    showConfirmButton: false
                                }).then(() => {
                                    // Setelah popup tertutup (otomatis setelah 4 detik), redirect pengguna
                                    window.location.href =
                                        "{{ route('user.orders.index') }}";
                                });

                                // Reset tombol bayar
                                payButton.disabled = false;
                                payButton.innerHTML = originalButtonText;
                            }
                        });
                    } else {
                        throw new Error('Failed to get payment token from server.');
                    }
                })
                .catch(error => {
                    console.error('Checkout Error:', error);
                    Swal.fire('Oops...', error.message || 'Something went wrong. Please try again.', 'error');
                    payButton.disabled = false;
                    payButton.innerHTML = originalButtonText;
                });
        });
    </script>
@endpush
