@extends('admin.layouts.app')

@section('page-title', 'Edit Produk')

@push('styles')
    <style>
        .image-preview-container,
        .gallery-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-preview,
        .gallery-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .image-preview img,
        .gallery-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
        }

        .file-name {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        .current-file {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .progress-container {
            display: none;
            margin-top: 1rem;
        }

        /* TAMBAHAN: Styling untuk error validation */
        .file-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .file-success {
            color: #198754;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .preview-item-error {
            border: 2px solid #dc3545 !important;
            opacity: 0.7;
        }

        .preview-item-success {
            border: 2px solid #198754 !important;
        }

        .preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(220, 53, 69, 0.8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            text-align: center;
            border-radius: 0.375rem;
        }

        .preview-overlay.success {
            background: rgba(25, 135, 84, 0.8);
        }

        /* Price comparison styling */
        .price-comparison {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .price-display {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .original-price {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 1rem;
        }

        .discount-price {
            color: #198754;
            font-weight: bold;
            font-size: 1.25rem;
        }

        .discount-percentage {
            background: #dc3545;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: bold;
        }

        .gallery-item {
            position: relative;
            width: 120px;
            height: 120px;
            display: inline-block;
            margin: 0.5rem;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
        }

        .gallery-item .remove-gallery-item {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            padding: 0;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
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
                <li class="breadcrumb-item active">Edit Produk</li>
            </ol>
        </nav>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Product Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                Informasi Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $product->name) }}"
                                    placeholder="Contoh: E-book Panduan Laravel" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="6" placeholder="Jelaskan tentang produk Anda...">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Product Data -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-cogs text-success me-2"></i>
                                Data Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Product Type -->
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label class="form-label">Tipe Produk <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input" type="radio" name="type" id="typeDigital"
                                                value="digital" {{ old('type', $product->type) == 'digital' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="typeDigital">
                                                <i class="fas fa-cloud-download-alt text-primary me-2"></i>
                                                Digital
                                            </label>
                                        </div>
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input" type="radio" name="type" id="typePhysical"
                                                value="physical" {{ old('type', $product->type) == 'physical' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="typePhysical">
                                                <i class="fas fa-box text-warning me-2"></i>
                                                Fisik
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing Section -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">
                                        <i class="fas fa-tag text-secondary me-1"></i>
                                        Harga Normal <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                            id="price" name="price" value="{{ old('price', $product->price) }}"
                                            placeholder="100000" required min="0">
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Harga sebelum diskon (jika ada)</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="discount_price" class="form-label">
                                        <i class="fas fa-percentage text-success me-1"></i>
                                        Harga Jual <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                            class="form-control @error('discount_price') is-invalid @enderror"
                                            id="discount_price" name="discount_price"
                                            value="{{ old('discount_price', $product->discount_price ?? $product->price) }}"
                                            placeholder="75000" required min="0">
                                        @error('discount_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text">Harga yang akan dibayar customer</div>
                                </div>
                            </div>

                            <!-- Price Comparison Preview -->
                            <div class="price-comparison" id="priceComparison" style="display: none;">
                                <h6 class="mb-2">
                                    <i class="fas fa-calculator text-info me-2"></i>
                                    Preview Harga:
                                </h6>
                                <div class="price-display">
                                    <span class="original-price" id="originalPriceDisplay">Rp 0</span>
                                    <span class="discount-price" id="discountPriceDisplay">Rp 0</span>
                                    <span class="discount-percentage" id="discountPercentageDisplay">0% OFF</span>
                                </div>
                                <small class="text-muted">Customer akan melihat harga yang dicoret dan diskon</small>
                            </div>

                            <!-- Digital Product Fields -->
                            <div id="digitalFields" class="mt-4">
                                <hr>
                                <h6 class="mb-3">
                                    <i class="fas fa-file-download text-primary me-2"></i>
                                    File Digital
                                </h6>
                                <div class="mb-3">
                                    <label for="digital_file" class="form-label">Upload File</label>
                                    @if ($product->digital_file_path)
                                        <div class="current-file mb-2">
                                            <i class="fas fa-file-archive me-2"></i>
                                            File saat ini: {{ basename($product->digital_file_path) }}
                                        </div>
                                    @endif
                                    <input type="file" class="form-control @error('digital_file') is-invalid @enderror"
                                        id="digital_file" name="digital_file">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Format yang didukung: PDF atau MP4 (Maksimal: 1GB). Upload file baru untuk menggantikan yang lama.
                                    </div>
                                    @error('digital_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="digitalFileName" class="file-name"></div>
                                    <div id="digitalFileError" class="file-error" style="display: none;"></div>
                                    <div id="digitalFileSuccess" class="file-success" style="display: none;"></div>

                                    <div class="progress-container" id="progressContainer">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-boomtale"
                                                id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                                aria-valuemin="0" aria-valuemax="100">0%</div>
                                        </div>
                                    </div>
                                    <div id="uploadStatus" class="form-text mt-1"></div>
                                </div>
                            </div>

                            <!-- Physical Product Fields -->
                            <div id="physicalFields" class="mt-4" style="display: none;">
                                <hr>
                                <h6 class="mb-3">
                                    <i class="fas fa-warehouse text-warning me-2"></i>
                                    Inventori Produk
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="stock" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                            id="stock" name="stock" value="{{ old('stock', $product->stock ?? 0) }}"
                                            placeholder="0" min="0">
                                        <div class="form-text">Kosongkan atau isi 0 jika stok unlimited</div>
                                        @error('stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Status Stok</label>
                                        <div class="form-control-plaintext">
                                            <span class="badge bg-success" id="stockStatus">Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Organization -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-sitemap text-info me-2"></i>
                                Organisasi
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Kategori <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-eye text-success me-1"></i>
                                        Aktifkan Produk
                                    </label>
                                </div>
                                <div class="form-text">Produk aktif akan dapat dilihat dan dibeli oleh pelanggan.</div>
                            </div>

                            <div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <i class="fas fa-star text-warning me-1"></i>
                                        Jadikan Unggulan
                                    </label>
                                </div>
                                <div class="form-text">Produk unggulan akan ditampilkan di halaman utama.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">
                                <i class="fas fa-images text-purple me-2"></i>
                                Gambar Produk
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">
                                    <i class="fas fa-image me-1"></i>
                                    Gambar Utama
                                </label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                    id="featured_image" name="featured_image" accept="image/*">
                                <div class="form-text">Ukuran maksimal: 5MB. Format: JPG, PNG, GIF. Upload gambar baru untuk menggantikan yang lama.</div>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="featuredImageError" class="file-error" style="display: none;"></div>
                                <div id="featuredImageSuccess" class="file-success" style="display: none;"></div>
                                <div class="image-preview-container" id="featuredImagePreviewContainer">
                                    @if ($product->featured_image)
                                        <div class="image-preview">
                                            <img src="{{ Storage::url($product->featured_image) }}" alt="Current Image">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <hr>

                            <div>
                                <label for="gallery_images" class="form-label">
                                    <i class="fas fa-photo-video me-1"></i>
                                    Galeri Gambar
                                </label>
                                <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                                    id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                <div class="form-text">Ukuran maksimal: 20MB total. Format: JPG, PNG, GIF. Upload gambar baru untuk ditambahkan ke galeri.</div>
                                @error('gallery_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="galleryImagesError" class="file-error" style="display: none;"></div>
                                <div id="galleryImagesSuccess" class="file-success" style="display: none;"></div>

                                <!-- Existing Gallery Images -->
                                <div id="existingGalleryContainer">
                                    @if ($product->gallery_images && count($product->gallery_images) > 0)
                                        <div class="mt-3">
                                            <label class="form-label">Gambar Galeri Saat Ini:</label>
                                            <div class="gallery-preview-container">
                                                @foreach ($product->gallery_images as $index => $imagePath)
                                                    <div class="gallery-item" id="existing-gallery-{{ $index }}">
                                                        <img src="{{ Storage::url($imagePath) }}" alt="Gallery Image">
                                                        <input type="hidden" name="existing_gallery_images[]" value="{{ $imagePath }}">
                                                        <button type="button" class="btn btn-danger btn-sm remove-gallery-item"
                                                                onclick="removeExistingGalleryItem({{ $index }})">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- New Gallery Images Preview -->
                                <div class="gallery-preview-container" id="galleryImagesPreviewContainer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-boomtale btn-lg" id="submitButton">
                            <i class="fas fa-sync-alt me-2"></i>Update Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File size limits (dalam bytes)
            const MAX_IMAGE_SIZE = 5 * 1024 * 1024; // 5MB
            const MAX_DIGITAL_FILE_SIZE = 1000 * 1024 * 1024; // 1000MB

            // Allowed file types
            const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            const ALLOWED_DIGITAL_TYPES = ['application/pdf', 'application/zip', 'video/mp4',
                'application/epub+zip'
            ];

            // Price calculation elements
            const priceInput = document.getElementById('price');
            const discountPriceInput = document.getElementById('discount_price');
            const priceComparison = document.getElementById('priceComparison');
            const originalPriceDisplay = document.getElementById('originalPriceDisplay');
            const discountPriceDisplay = document.getElementById('discountPriceDisplay');
            const discountPercentageDisplay = document.getElementById('discountPercentageDisplay');

            // Utility functions
            function formatCurrency(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function updatePriceComparison() {
                const price = parseFloat(priceInput.value) || 0;
                const discountPrice = parseFloat(discountPriceInput.value) || 0;

                if (price > 0 && discountPrice > 0) {
                    priceComparison.style.display = 'block';
                    originalPriceDisplay.textContent = formatCurrency(price);
                    discountPriceDisplay.textContent = formatCurrency(discountPrice);

                    if (price > discountPrice) {
                        const discountPercentage = Math.round(((price - discountPrice) / price) * 100);
                        discountPercentageDisplay.textContent = discountPercentage + '% OFF';
                        discountPercentageDisplay.style.display = 'inline-block';
                    } else {
                        discountPercentageDisplay.style.display = 'none';
                    }
                } else {
                    priceComparison.style.display = 'none';
                }
            }

            // Price calculation listeners
            priceInput.addEventListener('input', updatePriceComparison);
            discountPriceInput.addEventListener('input', updatePriceComparison);

            // Initial price comparison display
            updatePriceComparison();

            // Stock status update
            const stockInput = document.getElementById('stock');
            const stockStatus = document.getElementById('stockStatus');

            function updateStockStatus() {
                const stock = parseInt(stockInput.value) || 0;
                if (stock > 0) {
                    stockStatus.textContent = `Tersedia (${stock} unit)`;
                    stockStatus.className = 'badge bg-success';
                } else {
                    stockStatus.textContent = 'Stok Kosong';
                    stockStatus.className = 'badge bg-warning';
                }
            }

            if (stockInput) {
                stockInput.addEventListener('input', updateStockStatus);
                updateStockStatus();
            }

            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.style.display = 'block';
                }
            }

            function hideError(elementId) {
                const errorElement = document.getElementById(elementId);
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }

            function showSuccess(elementId, message) {
                const successElement = document.getElementById(elementId);
                if (successElement) {
                    successElement.textContent = message;
                    successElement.style.display = 'block';
                }
            }

            function hideSuccess(elementId) {
                const successElement = document.getElementById(elementId);
                if (successElement) {
                    successElement.style.display = 'none';
                }
            }

            function validateImageFile(file, errorId, successId) {
                hideError(errorId);
                hideSuccess(successId);

                // Check file type
                if (!ALLOWED_IMAGE_TYPES.includes(file.type)) {
                    showError(errorId, 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                    return false;
                }

                // Check file size
                if (file.size > MAX_IMAGE_SIZE) {
                    showError(errorId, `Ukuran file terlalu besar (${formatFileSize(file.size)}). Maksimal 5MB.`);
                    return false;
                }

                showSuccess(successId, `File valid: ${file.name} (${formatFileSize(file.size)})`);
                return true;
            }

            function validateDigitalFile(file, errorId, successId) {
                hideError(errorId);
                hideSuccess(successId);

                // Check file size
                if (file.size > MAX_DIGITAL_FILE_SIZE) {
                    showError(errorId, `Ukuran file terlalu besar (${formatFileSize(file.size)}). Maksimal 1GB.`);
                    return false;
                }

                // Check file type based on extension (more reliable for digital files)
                const fileName = file.name.toLowerCase();
                const allowedExtensions = ['.pdf', '.zip', '.mp4', '.epub'];
                const hasValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));

                if (!hasValidExtension) {
                    showError(errorId, 'Format file tidak didukung. Gunakan PDF, ZIP, MP4, atau EPUB.');
                    return false;
                }

                showSuccess(successId, `File valid: ${file.name} (${formatFileSize(file.size)})`);
                return true;
            }

            // Product type toggle logic
            const typeDigital = document.getElementById('typeDigital');
            const typePhysical = document.getElementById('typePhysical');
            const digitalFields = document.getElementById('digitalFields');
            const physicalFields = document.getElementById('physicalFields');

            function toggleProductTypeFields() {
                if (typeDigital.checked) {
                    digitalFields.style.display = 'block';
                    physicalFields.style.display = 'none';
                } else {
                    digitalFields.style.display = 'none';
                    physicalFields.style.display = 'block';
                }
            }

            typeDigital.addEventListener('change', toggleProductTypeFields);
            typePhysical.addEventListener('change', toggleProductTypeFields);
            // Initial check
            toggleProductTypeFields();

            // Featured Image validation and preview
            const featuredImageInput = document.getElementById('featured_image');
            const featuredImagePreviewContainer = document.getElementById('featuredImagePreviewContainer');

            featuredImageInput.addEventListener('change', function() {
                // Clear only new preview, keep existing image visible until successful upload
                const newPreviews = featuredImagePreviewContainer.querySelectorAll('.new-preview');
                newPreviews.forEach(preview => preview.remove());

                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const isValid = validateImageFile(file, 'featuredImageError', 'featuredImageSuccess');

                    if (isValid) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.className = 'image-preview preview-item-success new-preview';
                            previewDiv.innerHTML = `<img src="${e.target.result}" alt="New Preview">`;
                            featuredImagePreviewContainer.appendChild(previewDiv);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        // Clear the input if invalid
                        this.value = '';
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'image-preview preview-item-error new-preview';
                        errorDiv.innerHTML = `
                            <div class="preview-overlay">
                                <i class="fas fa-exclamation-triangle"></i><br>File Invalid
                            </div>
                        `;
                        featuredImagePreviewContainer.appendChild(errorDiv);
                    }
                }
            });

            // Gallery Images validation and preview
            const galleryImagesInput = document.getElementById('gallery_images');
            const galleryImagesPreviewContainer = document.getElementById('galleryImagesPreviewContainer');

            galleryImagesInput.addEventListener('change', function() {
                galleryImagesPreviewContainer.innerHTML = '';
                hideError('galleryImagesError');
                hideSuccess('galleryImagesSuccess');

                if (this.files && this.files.length > 0) {
                    let validFiles = 0;
                    let invalidFiles = 0;
                    const totalFiles = this.files.length;

                    Array.from(this.files).forEach((file, index) => {
                        const isValid = ALLOWED_IMAGE_TYPES.includes(file.type) && file.size <= MAX_IMAGE_SIZE;

                        if (isValid) {
                            validFiles++;
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewItem = document.createElement('div');
                                previewItem.className = 'gallery-preview-item preview-item-success';
                                previewItem.innerHTML = `<img src="${e.target.result}" alt="New Gallery Preview">`;
                                galleryImagesPreviewContainer.appendChild(previewItem);
                            }
                            reader.readAsDataURL(file);
                        } else {
                            invalidFiles++;
                            const previewItem = document.createElement('div');
                            previewItem.className = 'gallery-preview-item preview-item-error';
                            previewItem.innerHTML = `
                                <div class="preview-overlay">
                                    <i class="fas fa-exclamation-triangle"></i><br>
                                    ${file.size > MAX_IMAGE_SIZE ? 'Terlalu Besar' : 'Format Invalid'}
                                </div>
                            `;
                            galleryImagesPreviewContainer.appendChild(previewItem);
                        }
                    });

                    // Show summary
                    if (invalidFiles > 0) {
                        showError('galleryImagesError',
                            `${invalidFiles} dari ${totalFiles} file tidak valid. File yang tidak valid tidak akan diupload.`
                        );
                    }

                    if (validFiles > 0) {
                        showSuccess('galleryImagesSuccess', `${validFiles} file baru siap untuk diupload.`);
                    }
                }
            });

            // Digital file validation and display
            const digitalFileInput = document.getElementById('digital_file');
            const digitalFileName = document.getElementById('digitalFileName');

            digitalFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const isValid = validateDigitalFile(file, 'digitalFileError', 'digitalFileSuccess');

                    if (isValid) {
                        digitalFileName.textContent = `File baru dipilih: ${file.name}`;
                        digitalFileName.style.color = '#198754';
                    } else {
                        // Clear the input if invalid
                        this.value = '';
                        digitalFileName.textContent = '';
                    }
                } else {
                    digitalFileName.textContent = '';
                    hideError('digitalFileError');
                    hideSuccess('digitalFileSuccess');
                }
            });

            // Form submission with validation
            const productForm = document.getElementById('productForm');
            const submitButton = document.getElementById('submitButton');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const uploadStatus = document.getElementById('uploadStatus');

            if (productForm) {
                productForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Final validation before submit
                    let hasErrors = false;

                    // Validate featured image if selected
                    const featuredImage = featuredImageInput.files[0];
                    if (featuredImage && !validateImageFile(featuredImage, 'featuredImageError', 'featuredImageSuccess')) {
                        hasErrors = true;
                    }

                    // Validate gallery images if selected
                    if (galleryImagesInput.files.length > 0) {
                        Array.from(galleryImagesInput.files).forEach(file => {
                            if (!validateImageFile(file, 'galleryImagesError', 'galleryImagesSuccess')) {
                                hasErrors = true;
                            }
                        });
                    }

                    // Validate digital file if digital product and file selected
                    if (typeDigital.checked && digitalFileInput.files.length > 0) {
                        const digitalFile = digitalFileInput.files[0];
                        if (!validateDigitalFile(digitalFile, 'digitalFileError', 'digitalFileSuccess')) {
                            hasErrors = true;
                        }
                    }

                    if (hasErrors) {
                        alert('Terdapat file yang tidak valid. Silakan perbaiki terlebih dahulu.');
                        return;
                    }

                    // Proceed with upload
                    if (progressContainer) {
                        progressContainer.style.display = 'block';
                        progressBar.style.width = '0%';
                        progressBar.textContent = '0%';
                        progressBar.classList.remove('bg-success');
                        progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
                        uploadStatus.textContent = 'Mempersiapkan update...';
                    }

                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="ms-2">Mengupdate...</span>
                    `;

                    const formData = new FormData(productForm);

                    const config = {
                        onUploadProgress: function(progressEvent) {
                            if (progressBar && uploadStatus) {
                                const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                                progressBar.style.width = percentCompleted + '%';
                                progressBar.textContent = percentCompleted + '%';
                                uploadStatus.textContent = `Mengupload... (${formatFileSize(progressEvent.loaded)} / ${formatFileSize(progressEvent.total)})`;

                                if (percentCompleted === 100) {
                                    uploadStatus.textContent = 'Upload selesai. Memproses update di server...';
                                    submitButton.innerHTML = `
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="ms-2">Memproses...</span>
                                    `;
                                }
                            }
                        }
                    };

                    axios.post(productForm.action, formData, config)
                        .then(function(response) {
                            if (uploadStatus) {
                                uploadStatus.textContent = 'Produk berhasil diupdate! Mengalihkan...';
                            }
                            if (progressBar) {
                                progressBar.classList.remove('progress-bar-striped', 'progress-bar-animated');
                                progressBar.classList.add('bg-success');
                            }
                            window.location.href = "{{ route('admin.products.index') }}";
                        })
                        .catch(function(error) {
                            if (progressContainer) {
                                progressContainer.style.display = 'none';
                            }
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="fas fa-sync-alt me-2"></i>Update Produk';

                            if (error.response && error.response.status === 422) {
                                if (uploadStatus) {
                                    uploadStatus.textContent = 'Gagal! Terdapat kesalahan pada input Anda.';
                                }
                                let errorMessages = 'Validasi gagal:\n';
                                for (const key in error.response.data.errors) {
                                    errorMessages += `- ${error.response.data.errors[key][0]}\n`;
                                }
                                alert(errorMessages);
                            } else {
                                if (uploadStatus) {
                                    uploadStatus.textContent = 'Terjadi kesalahan saat memproses update.';
                                }
                                alert('Error: ' + (error.response ? error.response.data.message : error.message));
                            }
                        });
                });
            }

            // Global function for removing existing gallery items
            window.removeExistingGalleryItem = function(index) {
                const galleryItem = document.getElementById(`existing-gallery-${index}`);
                if (galleryItem) {
                    galleryItem.remove();
                }
            };
        });
    </script>
@endpush
