@extends('admin.layouts.app')

@section('page-title', 'Manajemen Pesanan')

@section('content')
    <div class="container-fluid">
        <!-- Page Header & Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-4 col-lg-3">
                            <label for="search" class="form-label">Cari Pesanan</label>
                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Invoice atau nama pelanggan..." value="{{ request('search') }}">
                        </div>
                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai
                                </option>
                                <option value="waiting_payment"
                                    {{ request('status') == 'waiting_payment' ? 'selected' : '' }}>Menunggu Pembayaran
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    Dibatalkan
                                </option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" id="start_date" class="form-control"
                                value="{{ request('start_date') }}">
                        </div>
                        <div class="col-12 col-md-4 col-lg-2">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" id="end_date" class="form-control"
                                value="{{ request('end_date') }}">
                        </div>
                        <div class="col-12 col-md-4 col-lg-3 d-flex">
                            <button type="submit" class="btn btn-boomtale w-100 me-2">
                                <i class="fas fa-search me-1"></i> Terapkan Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"
                                title="Reset Filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th class="text-center">Item</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="100">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="text-dark fw-bold text-decoration-none">{{ $order->invoice_number }}</a>
                                    </td>
                                    <td>
                                        <div>{{ $order->user->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $order->user->email ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span data-utc-time="{{ $order->created_at->toISOString() }}"></span>
                                        <!-- $order->created_at->format('d M Y, H:i') -->
                                    </td>
                                    <td>
                                        <span class="fw-bold text-boomtale">{{ $order->total_amount }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $order->items_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($order->status == 'completed')
                                            <span class="badge bg-success-soft text-success">Selesai</span>
                                        @elseif($order->status == 'waiting_payment')
                                            <span class="badge bg-warning-soft text-warning">Menunggu</span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="badge bg-danger-soft text-danger">Dibatalkan</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-soft text-secondary">{{ ucwords($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="btn btn-outline-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="text-center py-5">
                                            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum Ada Pesanan</h5>
                                            <p class="text-muted mb-0">Tidak ada pesanan yang cocok dengan filter Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($orders->hasPages())
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $orders->firstItem() }} sampai {{ $orders->lastItem() }} dari
                        {{ $orders->total() }} hasil
                    </div>
                    <div>
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
