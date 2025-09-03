@extends('admin.layouts.app')

@section('page-title', 'Detail Produk: ' . $product->name)

@push('styles')
    <style>
        .gallery-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .gallery-item img,
        .featured-image-container img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 0.25rem;
            display: block;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .info-value {
            margin-bottom: 1.5rem;
        }

        .description-text {
            white-space: pre-wrap;
            /* To respect newlines */
            line-height: 1.6;
            font-size: 0.95rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Product Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $product->name }}</h5>
                        @if ($product->is_featured)
                            <span class="badge bg-info-soft text-info">
                                <i class="fas fa-star me-1"></i> Unggulan
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <span class="info-label">Deskripsi</span>
                        <div class="info-value description-text">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>

                <!-- Product Data -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Data Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <span class="info-label">Tipe Produk</span>
                                <div class="info-value">
                                    @if ($product->isDigital())
                                        <span class="badge bg-success-soft text-success fs-6">
                                            <i class="fas fa-download me-1"></i> Digital
                                        </span>
                                    @else
                                        <span class="badge bg-warning-soft text-warning fs-6">
                                            <i class="fas fa-box me-1"></i> Fisik
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <span class="info-label">Harga</span>
                                <div class="info-value fw-bold text-boomtale fs-5">
                                    {{ $product->formatted_price }}
                                </div>
                            </div>
                            @if ($product->isPhysical())
                                <div class="col-md-4">
                                    <span class="info-label">Stok</span>
                                    <div class="info-value fs-5">
                                        {{ $product->stock ?? 0 }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($product->isDigital() && $product->digital_file_path)
                            <hr>
                            <span class="info-label">File Digital</span>
                            <div class="info-value d-flex align-items-center justify-content-between p-3 bg-light rounded">
                                <div>
                                    <i class="fas fa-file-archive me-2 text-muted"></i>
                                    <span>{{ basename($product->digital_file_path) }}</span>
                                </div>
                                <a href="{{ route('admin.products.download', $product) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Aksi</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-boomtale">
                                <i class="fas fa-edit me-2"></i>Edit Produk
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Organization -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Organisasi</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="info-label">Kategori</span>
                            <div class="info-value">
                                {{ $product->category->name ?? '-' }}
                            </div>
                        </div>
                        <div>
                            <span class="info-label">Status</span>
                            <div class="info-value">
                                @if ($product->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Gambar Produk</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="info-label">Gambar Utama</span>
                            <div class="featured-image-container mt-2">
                                @if ($product->featured_image)
                                    <img src="{{ Storage::url($product->featured_image) }}" alt="Featured Image">
                                @else
                                    <p class="text-muted small">Tidak ada gambar utama.</p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div>
                            <span class="info-label">Galeri Gambar</span>
                            @if ($product->gallery_images && count($product->gallery_images) > 0)
                                <div class="gallery-container">
                                    @foreach ($product->gallery_images as $imagePath)
                                        <div class="gallery-item">
                                            <a href="{{ Storage::url($imagePath) }}" data-bs-toggle="tooltip"
                                                title="Lihat Gambar" target="_blank">
                                                <img src="{{ Storage::url($imagePath) }}" alt="Gallery Image">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small">Tidak ada gambar di galeri.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush
