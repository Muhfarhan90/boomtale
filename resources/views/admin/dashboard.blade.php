{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-users shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Users</div>
                            <div class="stat-number">{{ number_format($totalUsers) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-products shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Produk</div>
                            <div class="stat-number">{{ number_format($totalProducts) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-orders shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Pesanan</div>
                            <div class="stat-number">{{ number_format($totalOrders) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card stat-revenue shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="stat-label">Total Revenue</div>
                            <div class="stat-number">Rp {{ number_format($totalRevenue) }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Message -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="welcome-title">
                            <i class="fas fa-check-circle me-2"></i>
                            Selamat Datang, {{ auth()->user()->name }}!
                        </h4>
                        <p class="welcome-text mb-0">
                            Anda berhasil masuk ke dashboard admin Boomtale.
                            <span class="badge bg-white text-dark ms-2">{{ ucfirst(auth()->user()->role) }}</span>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="welcome-info">
                            <small>Email: {{ auth()->user()->email }}</small><br>
                            <small>Login: {{ now()->format('d M Y, H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-lg-8 mb-4">
            <div class="card boomtale-card shadow-sm">
                <div class="card-header boomtale-header">
                    <h6 class="card-title">
                        <i class="fas fa-list-alt me-2"></i>
                        Pesanan Terbaru
                    </h6>
                    <a href="#" class="btn btn-sm btn-boomtale">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Invoice</th>
                                    <th class="border-0">Customer</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->invoice_number ?? 'INV-' . rand(100, 999) }}</td>
                                        <td>{{ $order->user_name ?? 'Demo User' }}</td>
                                        <td class="text-success fw-semibold">Rp
                                            {{ number_format($order->total_amount ?? 0) }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ ($order->status ?? 'pending') === 'paid' ? 'success' : 'warning' }}">
                                                {{ ucfirst($order->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="text-muted">
                                            {{ isset($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
                                            Belum ada pesanan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Products -->
        <div class="col-lg-4 mb-4">
            <div class="card boomtale-card shadow-sm">
                <div class="card-header boomtale-header">
                    <h6 class="card-title">
                        <i class="fas fa-star me-2"></i>
                        Produk Populer
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($popularProducts as $product)
                        <div class="product-item">
                            <img src="{{ $product->thumbnail ?? asset('images/no-image.png') }}"
                                alt="{{ $product->name }}" class="product-image">
                            <div class="product-info">
                                <h6 class="product-name">{{ \Illuminate\Support\Str::limit($product->name, 20) }}</h6>
                                <small class="text-muted">{{ $product->order_items_count }} terjual</small>
                            </div>
                            <div class="product-badge">
                                <span class="badge bg-light text-dark">{{ $product->order_items_count }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-box fa-2x mb-2 d-block"></i>
                            Belum ada data produk
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card boomtale-card shadow-sm">
                <div class="card-header boomtale-header">
                    <h6 class="card-title">
                        <i class="fas fa-bolt me-2"></i>
                        Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-action btn-action-users">
                                <i class="fas fa-users mb-2"></i>
                                <span>Kelola Users</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-action btn-action-products">
                                <i class="fas fa-box mb-2"></i>
                                <span>Kelola Produk</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-action btn-action-orders">
                                <i class="fas fa-shopping-cart mb-2"></i>
                                <span>Lihat Pesanan</span>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="#" class="btn btn-action btn-action-settings">
                                <i class="fas fa-cog mb-2"></i>
                                <span>Pengaturan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        :root {
            --boomtale-primary: #C5A572;
            --boomtale-primary-dark: #B8986A;
            --boomtale-secondary: #2C2C2C;
            --boomtale-light: #F8F6F3;
            --boomtale-gradient: linear-gradient(135deg, #C5A572 0%, #B8986A 100%);
        }

        /* Statistics Cards */
        .stat-card {
            border: none;
            border-radius: 12px;
            background: white;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(197, 165, 114, 0.2) !important;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--boomtale-gradient);
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--boomtale-secondary);
            margin-bottom: 0;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: var(--boomtale-gradient);
            color: white;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: var(--boomtale-gradient);
            color: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(197, 165, 114, 0.3);
        }

        .welcome-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .welcome-text {
            opacity: 0.9;
        }

        .welcome-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 0.75rem;
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        /* Boomtale Cards */
        .boomtale-card {
            border: none;
            border-radius: 12px;
            background: white;
        }

        .boomtale-header {
            background: var(--boomtale-light);
            border-bottom: 1px solid #e9ecef;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--boomtale-secondary);
            margin: 0;
        }

        .btn-boomtale {
            background: var(--boomtale-gradient);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.375rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-boomtale:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(197, 165, 114, 0.3);
        }

        /* Product Items */
        .product-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 45px;
            height: 45px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 1rem;
            border: 2px solid var(--boomtale-light);
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--boomtale-secondary);
            margin-bottom: 0.25rem;
        }

        .product-badge {
            margin-left: auto;
        }

        /* Quick Actions */
        .btn-action {
            width: 100%;
            padding: 1.5rem 1rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            background: white;
            color: var(--boomtale-secondary);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-action:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-action-users:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }

        .btn-action-products:hover {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-color: #f093fb;
        }

        .btn-action-orders:hover {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-color: #4facfe;
        }

        .btn-action-settings:hover {
            background: var(--boomtale-gradient);
            border-color: var(--boomtale-primary);
        }

        .btn-action i {
            font-size: 1.5rem;
            display: block;
        }

        .btn-action span {
            font-size: 0.875rem;
        }

        /* Table Improvements */
        .table> :not(caption)>*>* {
            padding: 0.875rem 0.75rem;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-hover>tbody>tr:hover>* {
            background-color: var(--boomtale-light);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-banner {
                text-align: center;
                padding: 1.25rem;
            }

            .boomtale-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }
        }

        /* Animation */
        .stat-card,
        .boomtale-card,
        .welcome-banner {
            animation: fadeInUp 0.5s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush
