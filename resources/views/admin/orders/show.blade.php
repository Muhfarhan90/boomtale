@extends('admin.layouts.app')

@section('page-title', 'Detail Pesanan #' . $order->invoice_number)

@section('content')
    <div class="container-fluid">
        <!-- Header dengan tombol kembali -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Pesanan
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informasi Pesanan -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-invoice me-2"></i>Informasi Pesanan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Invoice Number</label>
                                    <p class="mb-0 fw-bold">{{ $order->invoice_number }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Tanggal Pesanan</label>
                                    <p class="mb-0">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Status Pesanan</label>
                                    <div>
                                        @if ($order->status == 'completed')
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                            </span>
                                        @elseif($order->status == 'waiting_payment')
                                            <span class="badge bg-warning fs-6 px-3 py-2">
                                                <i class="fas fa-clock me-1"></i>Menunggu Pembayaran
                                            </span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger fs-6 px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-secondary fs-6 px-3 py-2">{{ ucwords($order->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Total Pembayaran</label>
                                    <p class="mb-0 h4 text-boomtale fw-bold">Rp
                                        {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                                </div>
                                @if ($order->expired_at)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">Expired At</label>
                                        <p class="mb-0">{{ $order->expired_at->format('d F Y, H:i') }} WIB</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Item Pesanan -->
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Item Pesanan</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($order->orderItems && $order->orderItems->count() > 0)
                                        @foreach ($order->orderItems as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($item->product && $item->product->featured_image)
                                                            <img src="{{ Storage::url($item->product->featured_image) }}"
                                                                alt="{{ $item->product_name }}" class="me-3 rounded"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="me-3 bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                            @if ($item->product)
                                                                <small class="text-muted">ID:
                                                                    {{ $item->product->id }}</small>
                                                            @else
                                                                <small class="text-danger">Produk dihapus</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td class="text-end fw-bold">
                                                    {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Tidak ada item dalam pesanan
                                                ini</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th class="text-end h5 text-boomtale">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Informasi -->
            <div class="col-lg-4">
                <!-- Informasi Pelanggan -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Informasi Pelanggan
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($order->user)
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Nama</label>
                                <p class="mb-0">{{ $order->user->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Email</label>
                                <p class="mb-0">{{ $order->user->email }}</p>
                            </div>
                            @if ($order->user->phone_number)
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Telepon</label>
                                    <p class="mb-0">{{ $order->user->phone_number }}</p>
                                </div>
                            @endif
                            <div class="mb-0">
                                <label class="form-label fw-bold text-muted">User ID</label>
                                <p class="mb-0">#{{ $order->user->id }}</p>
                            </div>
                        @else
                            <p class="text-muted mb-0">Data pelanggan tidak tersedia</p>
                        @endif
                    </div>
                </div>

                <!-- Informasi Pembayaran -->
                @if ($order->transaction)
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-credit-card me-2"></i>Informasi Pembayaran
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Status Transaksi</label>
                                <p class="mb-0">
                                    @if ($order->transaction->status == 'capture' || $order->transaction->status == 'settlement')
                                        <span class="badge bg-success">Sukses</span>
                                    @elseif($order->transaction->status == 'pending')
                                        <span class="badge bg-warning">Menunggu Pembayaran</span>
                                    @elseif(in_array($order->transaction->status, ['deny', 'cancel', 'expire']))
                                        <span class="badge bg-danger">Gagal</span>
                                    @else
                                        <span
                                            class="badge bg-secondary">{{ ucwords($order->transaction->status ?? 'N/A') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">Metode Pembayaran</label>
                                <p class="mb-0">
                                    {{ ucwords(str_replace('_', ' ', $order->transaction->payment_type ?? 'N/A')) }}</p>
                            </div>
                            @if ($order->transaction->transaction_id)
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">Transaction ID</label>
                                    <p class="mb-0 text-break small">{{ $order->transaction->transaction_id }}</p>
                                </div>
                            @endif
                            <div class="mb-0">
                                <label class="form-label fw-bold text-muted">Gross Amount</label>
                                <p class="mb-0 fw-bold">Rp
                                    {{ number_format($order->transaction->gross_amount, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                            <p class="text-muted mb-0">Data transaksi tidak tersedia</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .card-title {
            color: #495057;
        }

        .badge {
            font-weight: 500;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }
    </style>
@endpush
