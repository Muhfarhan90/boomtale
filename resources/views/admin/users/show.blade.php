{{-- filepath: d:\FREELANCE\boomtale\resources\views\admin\users\show.blade.php --}}
@extends('admin.layouts.app')

@section('page-title', 'Detail Pengguna')

@push('styles')
    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Pengguna</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- User Profile Sidebar -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body">
                        @if ($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}"
                                class="profile-avatar rounded-circle mb-3">
                        @else
                            <div class="avatar avatar-xxl mb-3">
                                <span class="avatar-initial rounded-circle bg-boomtale-soft text-boomtale"
                                    style="font-size: 3rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-3">{{ $user->email }}</p>

                        <div>
                            @if ($user->role === 'admin')
                                <span class="badge bg-boomtale-soft text-boomtale fs-6 me-1">Admin</span>
                            @else
                                <span class="badge bg-info-soft text-info fs-6 me-1">User</span>
                            @endif

                            @if ($user->is_active)
                                <span class="badge bg-success-soft text-success fs-6">Aktif</span>
                            @else
                                <span class="badge bg-secondary-soft text-secondary fs-6">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-muted small">
                        Terdaftar sejak: {{ $user->created_at->format('d F Y') }}
                    </div>
                </div>
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Pengguna
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- User Details & Activity -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Aktivitas & Detail</h5>
                    </div>
                    <div class="card-body">
                        <h6 class="mb-3">Produk yang Dimiliki ({{ $user->products->count() }})</h6>
                        @if ($user->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Kategori</th>
                                            <th>Tanggal Beli</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user->products as $product)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.products.edit', $product) }}"
                                                        class="text-dark text-decoration-none fw-bold">
                                                        {{ $product->name }}
                                                    </a>
                                                </td>
                                                <td>{{ $product->category->name ?? '-' }}</td>
                                                <td>{{ $product->pivot->created_at->format('d M Y, H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <p>Pengguna ini belum memiliki produk.</p>
                            </div>
                        @endif

                        <hr>

                        <h6 class="mb-3">Informasi Kontak</h6>
                        <dl class="row">
                            <dt class="col-sm-3">Nama Lengkap</dt>
                            <dd class="col-sm-9">{{ $user->name }}</dd>

                            <dt class="col-sm-3">Alamat Email</dt>
                            <dd class="col-sm-9">{{ $user->email }}</dd>

                            <dt class="col-sm-3">Nomor Telepon</dt>
                            <dd class="col-sm-9">{{ $user->phone_number ?? 'Tidak diisi' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
