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
                <div>
                    <span class="text-muted">Tanggal Pesanan: {{ $order->created_at->format('d F Y, H:i') }}</span>
                </div>
                <div>
                    {{-- Anda bisa menambahkan accessor 'status_badge_color' di model Order untuk warna dinamis --}}
                    <span class="badge rounded-pill status-badge bg-primary">
                        {{ ucwords(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>
            <div class="card-body p-4">
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
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        Subtotal
                                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                                        Pajak (11%)
                                        <span>Rp {{ number_format($order->total_amount - $subtotal, 0, ',', '.') }}</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent fw-bold fs-5">
                                        Total
                                        <span class="text-primary">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </li>
                                </ul>

                                @if ($order->status == 'waiting_payment' && $order->transaction && $order->transaction->snap_token)
                                    <div class="d-grid mt-4">
                                        <button id="pay-button" class="btn btn-primary">
                                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                        </button>
                                    </div>
                                    <p class="text-muted text-center small mt-2">
                                        Batas waktu pembayaran: {{ $order->expired_at->format('d F Y, H:i') }}
                                    </p>
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
                                <p class="mb-0"><strong class="d-inline-block" style="width: 120px;">ID
                                        Transaksi</strong>: <span
                                        class="text-break">{{ $order->transaction->transaction_id ?? 'Belum tersedia' }}</span>
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
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script type="text/javascript">
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
                        Swal.fire('Pembayaran Gagal', 'Terjadi kesalahan saat memproses pembayaran.',
                            'error');
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                    }
                });
            });
        </script>
    @endpush
@endif
