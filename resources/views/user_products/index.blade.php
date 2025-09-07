@extends('layouts.app')

@section('title', 'Produk Saya - Boomtale')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Produk Saya</li>
                    </ol>
                </nav>

                <h2 class="mb-3">
                    <i class="fas fa-book me-2 text-primary"></i>
                    Produk Saya
                </h2>
                <p class="text-muted">Koleksi produk digital yang sudah Anda beli</p>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Products Grid -->
        <div class="row">
            @forelse($userProducts as $userProduct)
                @php
                    $product = $userProduct->product;
                    $fileExtension = $product->digital_file_path
                        ? strtolower(pathinfo($product->digital_file_path, PATHINFO_EXTENSION))
                        : '';
                    $fileExists = $product->digital_file_path ? Storage::exists($product->digital_file_path) : false;
                    $isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg', 'avi', 'mov']);
                    $isPdf = $fileExtension === 'pdf';
                    $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'svg']);
                @endphp

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <!-- Product Image -->
                        <div class="position-relative">
                            @if ($product->featured_image)
                                <img src="{{ Storage::url($product->featured_image) }}" class="card-img-top"
                                    alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    @if ($isVideo)
                                        <i class="fas fa-play-circle fa-3x text-primary"></i>
                                    @elseif($isPdf)
                                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    @elseif($isImage)
                                        <i class="fas fa-image fa-3x text-info"></i>
                                    @else
                                        <i class="fas fa-file-alt fa-3x text-muted"></i>
                                    @endif
                                </div>
                            @endif

                            <!-- Status Badges -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Dimiliki
                                    </span>
                                    @if ($fileExists)
                                        <span class="badge bg-info">
                                            <i class="fas fa-file me-1"></i>{{ strtoupper($fileExtension) }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- File Type Icon Overlay -->
                            @if ($isVideo)
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <i class="fas fa-play-circle fa-3x text-white"
                                        style="text-shadow: 0 0 10px rgba(0,0,0,0.5);"></i>
                                </div>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Category -->
                            @if ($product->category)
                                <small class="text-muted mb-2">
                                    <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
                                </small>
                            @endif

                            <!-- Product Name -->
                            <h6 class="card-title">{{ $product->name }}</h6>

                            <!-- File Info -->
                            @if ($fileExists)
                                <small class="text-success mb-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    File tersedia ({{ strtoupper($fileExtension) }})
                                </small>
                            @else
                                <small class="text-danger mb-2">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    File tidak ditemukan
                                </small>
                            @endif

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
                                @if ($fileExists)
                                    <div class="d-grid gap-2">
                                        <!-- PERBAIKAN: Semua file type ke show page untuk consistent UX -->
                                        @if ($isPdf)
                                            <!-- PDF: Buka di show page untuk reading inline -->
                                            <a href="{{ route('user.user-products.show', $userProduct->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-book-open me-1"></i>
                                                Baca Ebook
                                            </a>
                                        @elseif($isVideo)
                                            <!-- Video: Buka di show page -->
                                            <a href="{{ route('user.user-products.show', $userProduct->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-play me-1"></i>
                                                Tonton Video
                                            </a>
                                        @else
                                            <!-- File lain: Lihat detail -->
                                            <a href="{{ route('user.user-products.show', $userProduct->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Lihat Detail
                                            </a>
                                        @endif

                                        <!-- PERBAIKAN: Download sederhana tanpa JavaScript -->
                                        {{-- <a href="{{ route('user.user-products.download', $userProduct->id) }}"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download me-1"></i>
                                            Download {{ strtoupper($fileExtension) }}
                                        </a> --}}
                                    </div>
                                @else
                                    <div class="d-grid">
                                        <button class="btn btn-outline-danger btn-sm" disabled>
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            File Tidak Tersedia
                                        </button>
                                        <small class="text-muted mt-1 text-center">Hubungi admin untuk bantuan</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <x-empty-state icon="fas fa-shopping-bag" title="Belum Ada Produk Digital"
                        message="Anda belum memiliki produk digital. Mulai berbelanja sekarang!" :actionUrl="route('user.products.index')"
                        actionText="Mulai Belanja" />
                </div>
            @endforelse
        </div>
    </div>

    <!-- Download Modal untuk Progress -->
    <div class="modal fade" id="downloadModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h6 class="mb-2">Mempersiapkan Download</h6>
                    <p class="mb-0 text-muted" id="download-filename">Mohon tunggu...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .download-btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .download-btn:hover {
            background-color: var(--bs-success) !important;
            border-color: var(--bs-success) !important;
            color: white !important;
            transform: translateY(-1px);
        }

        .download-btn:active {
            transform: translateY(0);
        }

        .download-btn.downloading {
            pointer-events: none;
        }

        .download-btn .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        /* Badge animations */
        .badge {
            animation: fadeInScale 0.3s ease;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Card hover effects */
        .card-img-top {
            transition: transform 0.3s ease;
        }

        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }

        /* Responsive improvements */
        @media (max-width: 768px) {
            .card-title {
                font-size: 1rem;
            }

            .btn-sm {
                font-size: 0.8rem;
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Simple notification function
            function showNotification(message, type = 'success') {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';

                const notification = `
                    <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                        <i class="fas fa-${icon} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                $('body').append(notification);
                setTimeout(() => {
                    $('.alert').fadeOut(300, function() {
                        $(this).remove();
                    });
                }, 4000);
            }

            // Show success message if any
            @if (session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif
        });
    </script>
@endpush
