@extends('layouts.app')

@section('title', $userProduct->product->name . ' - Produk Saya')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.user-products.index') }}">Produk Saya</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($userProduct->product->name, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        @php
            $product = $userProduct->product;
            $fileExtension = $product->digital_file_path
                ? strtolower(pathinfo($product->digital_file_path, PATHINFO_EXTENSION))
                : '';
            $isVideo = in_array($fileExtension, ['mp4', 'webm', 'ogg']);
            $isPdf = $fileExtension === 'pdf';
        @endphp

        <div class="row">
            <!-- Content Area -->
            <div class="col-lg-8 order-2 order-lg-1">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            @if ($isPdf)
                                <i class="fas fa-book-open me-2"></i>
                            @elseif($isVideo)
                                <i class="fas fa-play-circle me-2"></i>
                            @else
                                <i class="fas fa-file me-2"></i>
                            @endif
                            {{ $userProduct->product->name }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if ($debugInfo['file_exists'])
                            @if ($isPdf)
                                <!-- PDF Viewer - Iframe Only -->
                                <div class="pdf-viewer-container">
                                    <iframe id="pdf-iframe"
                                        src="{{ route('user.user-products.stream', $userProduct->id) }}#toolbar=1&navpanes=0&scrollbar=1&view=FitH"
                                        width="100%" height="100%" style="border: none;" title="{{ $product->name }}"
                                        loading="lazy">
                                        <!-- Fallback content jika iframe gagal -->
                                        <div class="text-center py-5">
                                            <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                            <h5>Tidak dapat memuat PDF</h5>
                                            <p class="text-muted">Browser tidak mendukung PDF viewer</p>
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('user.user-products.stream', $userProduct->id) }}"
                                                    target="_blank" class="btn btn-primary">
                                                    <i class="fas fa-external-link-alt me-2"></i>Buka di Tab Baru
                                                </a>
                                                {{-- <button type="button" class="btn btn-outline-primary download-btn"
                                                    onclick="downloadFile('{{ route('user.user-products.download', $userProduct->id) }}', '{{ $product->name }}')">
                                                    <i class="fas fa-download me-2"></i>Download PDF
                                                </button> --}}
                                            </div>
                                        </div>
                                    </iframe>
                                </div>
                            @elseif ($isVideo)
                                <!-- Video Player -->
                                <div class="video-wrapper">
                                    <video controls class="w-100" preload="metadata"
                                        style="max-height: 70vh; height: auto;"
                                        poster="{{ $product->featured_image ? Storage::url($product->featured_image) : '' }}">
                                        <source src="{{ route('user.user-products.stream', $userProduct->id) }}"
                                            type="{{ $debugInfo['mime_type'] ?? 'video/mp4' }}">
                                        <div class="text-center py-5">
                                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                            <h5>Browser tidak mendukung video player</h5>
                                            <p class="text-muted">Silakan download file untuk menonton</p>
                                            <button type="button" class="btn btn-primary download-btn"
                                                onclick="downloadFile('{{ route('user.user-products.download', $userProduct->id) }}', '{{ $product->name }}')">
                                                <i class="fas fa-download me-2"></i>Download Video
                                            </button>
                                        </div>
                                    </video>
                                </div>
                            @else
                                <!-- Other File Types -->
                                <div class="text-center py-5">
                                    <i class="fas fa-file fa-4x text-muted mb-3"></i>
                                    <h5>File {{ strtoupper($fileExtension) }}</h5>
                                    <p class="text-muted">Preview tidak tersedia untuk tipe file ini</p>
                                    <button type="button" class="btn btn-primary download-btn"
                                        onclick="downloadFile('{{ route('user.user-products.download', $userProduct->id) }}', '{{ $product->name }}')">
                                        <i class="fas fa-download me-1"></i>Download File
                                    </button>
                                </div>
                            @endif
                        @else
                            <!-- File Not Found -->
                            <div class="text-center py-5">
                                <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                                <h5>File Tidak Ditemukan</h5>
                                <p class="text-muted">File digital tidak tersedia. Silakan hubungi admin.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 order-1 order-lg-2">
                <!-- Product Info -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-1"></i>Informasi Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($product->featured_image)
                            <div class="mb-3">
                                <img src="{{ Storage::url($product->featured_image) }}" class="img-fluid rounded w-100"
                                    alt="{{ $product->name }}" style="max-height: 200px; object-fit: cover;">
                            </div>
                        @endif

                        <h6 class="fw-bold">{{ $product->name }}</h6>

                        @if ($product->description)
                            <p class="text-muted small mb-3">{{ Str::limit($product->description, 150) }}</p>
                        @endif

                        <hr>

                        <div class="small">
                            @if ($product->category)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Kategori:</span>
                                    <span class="fw-medium">{{ $product->category->name }}</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Dibeli:</span>
                                <span class="fw-medium">{{ $userProduct->purchased_at->format('d M Y') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Harga Beli:</span>
                                <span class="fw-bold text-success">Rp
                                    {{ number_format($userProduct->purchase_price, 0, ',', '.') }}</span>
                            </div>
                            @if ($debugInfo['file_exists'])
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Format:</span>
                                    <span class="fw-medium">{{ strtoupper($debugInfo['extension']) }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted">Ukuran:</span>
                                    <span class="fw-medium">{{ $debugInfo['file_size_formatted'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @if ($debugInfo['file_exists'])
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-tools me-1"></i>Aksi
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <!-- Download Button -->
                                {{-- <button type="button" class="btn btn-outline-primary download-btn"
                                    onclick="downloadFile('{{ route('user.user-products.download', $userProduct->id) }}', '{{ $product->name }}')">
                                    <i class="fas fa-download me-1"></i>
                                    Download {{ strtoupper($fileExtension) }}
                                </button> --}}

                                @if ($isPdf)
                                    <button class="btn btn-outline-secondary" onclick="openInNewTab()">
                                        <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                                    </button>
                                    {{-- <button class="btn btn-outline-secondary d-md-none" onclick="toggleFullscreen()">
                                        <i class="fas fa-expand me-1"></i>Mode Fullscreen
                                    </button> --}}
                                @endif

                                <a href="{{ route('user.user-products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Set document title saat PDF dimuat
        function setPdfTitle() {
            const iframe = document.getElementById('pdf-iframe');
            if (iframe) {
                iframe.onload = function() {
                    try {
                        // Coba akses document iframe untuk set title
                        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                        if (iframeDoc) {
                            iframeDoc.title = '{{ $userProduct->product->name }}';
                        }
                    } catch (e) {
                        // Cross-origin restriction, ini normal untuk PDF
                        console.log('Cannot access iframe document (normal for PDF)');
                    }
                };
            }
        }

        // Open PDF in new tab dengan title yang benar
        function openInNewTab() {
            const url = '{{ route('user.user-products.stream', $userProduct->id) }}';
            const newTab = window.open(url, '_blank');

            // Set title untuk tab baru (jika memungkinkan)
            if (newTab) {
                newTab.onload = function() {
                    try {
                        newTab.document.title = '{{ $userProduct->product->name }}';
                    } catch (e) {
                        console.log('Cannot set title for new tab');
                    }
                };
            }
        }

        // Panggil saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            setPdfTitle();
        });

        // Rest of functions remain the same...
        function toggleFullscreen() {
            const pdfContainer = document.querySelector('.pdf-viewer-container');

            if (pdfContainer) {
                if (!document.fullscreenElement) {
                    pdfContainer.requestFullscreen().then(() => {
                        pdfContainer.classList.add('fullscreen-active');
                        showToast('Mode fullscreen diaktifkan', 'success');
                    }).catch(err => {
                        showToast('Fullscreen tidak didukung', 'error');
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        pdfContainer.classList.remove('fullscreen-active');
                        showToast('Mode fullscreen dinonaktifkan', 'info');
                    });
                }
            }
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className =
                `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <span>${message}</span>
                    <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 3000);
        }

        function downloadFile(url, filename) {
            const btn = document.querySelector('.download-btn');
            const originalText = btn.innerHTML;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Preparing...';
            btn.disabled = true;

            fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename + '.{{ $fileExtension }}';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                    showToast('Download berhasil!', 'success');
                })
                .catch(error => {
                    console.error('Download failed:', error);
                    window.open(url, '_blank');
                    showToast('Download error, membuka tab baru...', 'error');
                })
                .finally(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }
    </script>
@endpush


@push('styles')
    <style>
        /* Responsive PDF Container */
        .pdf-viewer-container {
            height: 60vh;
            min-height: 400px;
            width: 100%;
            position: relative;
        }

        @media (min-width: 768px) {
            .pdf-viewer-container {
                height: 70vh;
                min-height: 500px;
            }
        }

        @media (min-width: 992px) {
            .pdf-viewer-container {
                height: 600px;
            }
        }

        /* PDF iframe responsiveness */
        .pdf-viewer-container iframe {
            transition: height 0.3s ease;
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Video responsive */
        .video-wrapper {
            position: relative;
            width: 100%;
        }

        .video-wrapper video {
            max-height: 60vh;
            width: 100%;
            height: auto;
        }

        @media (min-width: 768px) {
            .video-wrapper video {
                max-height: 70vh;
            }
        }

        /* Fullscreen styles */
        .pdf-viewer-container.fullscreen-active {
            background: white;
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .pdf-viewer-container:fullscreen iframe {
            width: 100vw !important;
            height: 100vh !important;
        }

        /* Mobile optimizations */
        @media (max-width: 767px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .card-header h5 {
                font-size: 1rem;
            }
        }

        /* Button states */
        .download-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Toast animations */
        .alert.position-fixed {
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Breadcrumb responsive */
        @media (max-width: 576px) {
            .breadcrumb-item {
                font-size: 0.875rem;
            }
        }

        /* Card header responsive */
        @media (max-width: 576px) {
            .card-header {
                padding: 1rem 0.75rem;
            }

            .card-header h5 {
                margin-bottom: 0;
                width: 100%;
            }
        }
    </style>
@endpush
