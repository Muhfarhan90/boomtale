{{-- resources/views/user_products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Produk Saya - Boomtale')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">
                    <i class="fas fa-book me-2 text-primary"></i>
                    Produk Saya
                </h2>
                <p class="text-muted">Koleksi produk digital yang sudah Anda beli</p>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row">
            @forelse($userProducts as $userProduct)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Product Image -->
                        <div class="position-relative">
                            @if ($userProduct->product->featured_image)
                                <img src="{{ Storage::url($userProduct->product->featured_image) }}" class="card-img-top"
                                    alt="{{ $userProduct->product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="fas fa-file-alt fa-3x text-muted"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Dimiliki
                                </span>
                            </div>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Category -->
                            @if ($userProduct->product->category)
                                <small class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>{{ $userProduct->product->category->name }}
                                </small>
                            @endif

                            <!-- Product Name -->
                            <h6 class="card-title">{{ $userProduct->product->name }}</h6>

                            <!-- Purchase Info -->
                            <div class="mb-3">
                                <small class="text-muted d-block">
                                    <i class="fas fa-calendar me-1"></i>
                                    Dibeli: {{ $userProduct->purchased_at->format('d M Y') }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-money-bill me-1"></i>
                                    Harga: Rp {{ number_format($userProduct->purchase_price, 0, ',', '.') }}
                                </small>
                            </div>

                            <!-- Actions -->
                            <div class="mt-auto">
                                <div class="d-grid gap-2">
                                    <!-- View/Read Button -->
                                    <a href="{{ route('user.user-products.show', $userProduct->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>
                                        @if (pathinfo($userProduct->product->digital_file_path, PATHINFO_EXTENSION) === 'mp4')
                                            Tonton Video
                                        @else
                                            Baca/Lihat
                                        @endif
                                    </a>

                                    <!-- Download Button -->
                                    @if ($userProduct->product->digital_file_path)
                                        <a href="{{ route('user.user-products.download', $userProduct->id) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Produk</h5>
                        <p class="text-muted">Anda belum memiliki produk digital. Mulai berbelanja sekarang!</p>
                        <a href="{{ route('user.products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Mulai Belanja
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
