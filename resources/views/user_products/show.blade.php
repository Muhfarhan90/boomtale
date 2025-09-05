{{-- resources/views/user_products/show.blade.php --}}
@extends('layouts.app')

@section('title', $userProduct->product->name . ' - Produk Saya')

@section('content')
    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.user-products.index') }}">Produk Saya</a></li>
                <li class="breadcrumb-item active">{{ $userProduct->product->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Content Area -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-play-circle me-2"></i>
                            {{ $userProduct->product->name }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($userProduct->product->digital_file_path)
                            @php
                                $extension = pathinfo($userProduct->product->digital_file_path, PATHINFO_EXTENSION);
                            @endphp

                            @if ($extension === 'mp4')
                                <!-- Video Player -->
                                <video controls class="w-100" style="max-height: 500px;">
                                    <source src="{{ route('user.user-products.stream', $userProduct->id) }}" type="video/mp4">
                                    Browser Anda tidak mendukung video HTML5.
                                </video>
                            @elseif($extension === 'pdf')
                                <!-- PDF Viewer -->
                                <iframe src="{{ route('user.user-products.stream', $userProduct->id) }}" class="w-100"
                                    style="height: 600px; border: none;">
                                    Browser Anda tidak mendukung PDF viewer.
                                    <a href="{{ route('user.user-products.download', $userProduct->id) }}">Download PDF</a>
                                </iframe>
                            @else
                                <!-- Other files - show download option -->
                                <div class="text-center py-5">
                                    <i class="fas fa-file-archive fa-4x text-muted mb-3"></i>
                                    <h5>{{ $userProduct->product->name }}</h5>
                                    <p class="text-muted">File: {{ basename($userProduct->product->digital_file_path) }}</p>
                                    <a href="{{ route('user.user-products.download', $userProduct->id) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-download me-2"></i>Download File
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                <h5>File Tidak Tersedia</h5>
                                <p class="text-muted">Hubungi admin untuk bantuan.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Product Info -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Produk</h6>
                    </div>
                    <div class="card-body">
                        @if ($userProduct->product->featured_image)
                            <img src="{{ Storage::url($userProduct->product->featured_image) }}"
                                class="img-fluid rounded mb-3" alt="{{ $userProduct->product->name }}">
                        @endif

                        <h6>{{ $userProduct->product->name }}</h6>
                        <p class="text-muted small">{{ $userProduct->product->description }}</p>

                        <hr>

                        <div class="small">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Kategori:</span>
                                <span>{{ $userProduct->product->category->name ?? '-' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Dibeli:</span>
                                <span>{{ $userProduct->purchased_at->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Harga Beli:</span>
                                <span>Rp {{ number_format($userProduct->purchase_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($userProduct->product->digital_file_path)
                                <a href="{{ route('user.user-products.download', $userProduct->id) }}"
                                    class="btn btn-success">
                                    <i class="fas fa-download me-2"></i>Download File
                                </a>
                            @endif
                            <a href="{{ route('user.user-products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Debug Info (hanya untuk development) -->
                @if (config('app.debug'))
                    <div class="card border-warning mt-3">
                        <div class="card-header bg-warning text-dark">
                            <small>Debug Info</small>
                        </div>
                        <div class="card-body">
                            <small>
                                <strong>Type:</strong> {{ $debugInfo['type'] ?? 'N/A' }}<br>
                                <strong>File Path:</strong> {{ $debugInfo['digital_file_path'] ?? 'N/A' }}<br>
                                <strong>File Exists:</strong> {{ $debugInfo['file_exists'] ? 'Yes' : 'No' }}<br>
                            </small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
