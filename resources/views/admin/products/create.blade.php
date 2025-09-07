@extends('admin.layouts.app')

@section('page-title', 'Tambah Produk Baru')

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
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active">Tambah Produk</li>
            </ol>
        </nav>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Product Information -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Informasi Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Produk <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Contoh: E-book Panduan Laravel" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="6" placeholder="Jelaskan tentang produk Anda...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tipe Produk <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="typeDigital"
                                                value="digital" {{ old('type', 'digital') == 'digital' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="typeDigital">Digital</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="typePhysical"
                                                value="physical" {{ old('type') == 'physical' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="typePhysical">Fisik</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Harga <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                            id="price" name="price" value="{{ old('price') }}" placeholder="50000"
                                            required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Digital Product Fields -->
                            <div id="digitalFields" class="mb-3">
                                <label for="digital_file" class="form-label">File Digital <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('digital_file') is-invalid @enderror"
                                    id="digital_file" name="digital_file">
                                <div class="form-text">Upload file PDF atau MP4 (Maks: 300MB).</div>
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

                            <!-- Physical Product Fields -->
                            <div id="physicalFields" class="mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    id="stock" name="stock" value="{{ old('stock', 0) }}"
                                    placeholder="Jumlah stok tersedia">
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Organization -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Organisasi</h5>
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
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktifkan Produk</label>
                                </div>
                                <div class="form-text">Produk aktif akan dapat dilihat dan dibeli oleh pelanggan.</div>
                            </div>
                            <div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Jadikan Unggulan</label>
                                </div>
                                <div class="form-text">Produk unggulan akan ditampilkan di halaman utama.</div>
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
                                <label for="featured_image" class="form-label">Gambar Utama</label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                    id="featured_image" name="featured_image" accept="image/*">
                                <div class="form-text">Ukuran maksimal: 5MB. Format: JPG atau PNG</div>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="featuredImageError" class="file-error" style="display: none;"></div>
                                <div id="featuredImageSuccess" class="file-success" style="display: none;"></div>
                                <div class="image-preview-container" id="featuredImagePreviewContainer"></div>
                            </div>
                            <hr>
                            <div>
                                <label for="gallery_images" class="form-label">Galeri Gambar</label>
                                <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                                    id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                <div class="form-text">Ukuran maksimal: 20MB. Format: JPG atau PNG</div>
                                @error('gallery_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="galleryImagesError" class="file-error" style="display: none;"></div>
                                <div id="galleryImagesSuccess" class="file-success" style="display: none;"></div>
                                <div class="gallery-preview-container" id="galleryImagesPreviewContainer"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-boomtale btn-lg" id="submitButton">
                            <i class="fas fa-save me-2"></i>Simpan Produk
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            Batal
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
            const MAX_DIGITAL_FILE_SIZE = 300 * 1024 * 1024; // 300MB

            // Allowed file types
            const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            const ALLOWED_DIGITAL_TYPES = ['application/pdf', 'application/zip', 'video/mp4',
                'application/epub+zip'];

            // Utility functions
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }

            function hideError(elementId) {
                const errorElement = document.getElementById(elementId);
                errorElement.style.display = 'none';
            }

            function showSuccess(elementId, message) {
                const successElement = document.getElementById(elementId);
                successElement.textContent = message;
                successElement.style.display = 'block';
            }

            function hideSuccess(elementId) {
                const successElement = document.getElementById(elementId);
                successElement.style.display = 'none';
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
                    showError(errorId, `Ukuran file terlalu besar (${formatFileSize(file.size)}). Maksimal 2MB.`);
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
                    showError(errorId, `Ukuran file terlalu besar (${formatFileSize(file.size)}). Maksimal 300MB.`);
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
            toggleProductTypeFields();

            // Featured Image validation and preview
            const featuredImageInput = document.getElementById('featured_image');
            const featuredImagePreviewContainer = document.getElementById('featuredImagePreviewContainer');

            featuredImageInput.addEventListener('change', function() {
                featuredImagePreviewContainer.innerHTML = '';

                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const isValid = validateImageFile(file, 'featuredImageError', 'featuredImageSuccess');

                    if (isValid) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            featuredImagePreviewContainer.innerHTML = `
                                <div class="image-preview preview-item-success">
                                    <img src="${e.target.result}" alt="Preview">
                                </div>
                            `;
                        }
                        reader.readAsDataURL(file);
                    } else {
                        // Clear the input if invalid
                        this.value = '';
                        featuredImagePreviewContainer.innerHTML = `
                            <div class="image-preview preview-item-error">
                                <div class="preview-overlay">
                                    <i class="fas fa-exclamation-triangle"></i><br>File Invalid
                                </div>
                            </div>
                        `;
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
                        const isValid = ALLOWED_IMAGE_TYPES.includes(file.type) && file.size <=
                            MAX_IMAGE_SIZE;

                        if (isValid) {
                            validFiles++;
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                const previewItem = document.createElement('div');
                                previewItem.className =
                                    'gallery-preview-item preview-item-success';
                                previewItem.innerHTML =
                                    `<img src="${e.target.result}" alt="Gallery Preview">`;
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
                        showSuccess('galleryImagesSuccess', `${validFiles} file siap untuk diupload.`);
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
                        digitalFileName.textContent = `File dipilih: ${file.name}`;
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
                    if (featuredImage && !validateImageFile(featuredImage, 'featuredImageError',
                            'featuredImageSuccess')) {
                        hasErrors = true;
                    }

                    // Validate gallery images if selected
                    if (galleryImagesInput.files.length > 0) {
                        Array.from(galleryImagesInput.files).forEach(file => {
                            if (!validateImageFile(file, 'galleryImagesError',
                                    'galleryImagesSuccess')) {
                                hasErrors = true;
                            }
                        });
                    }

                    // Validate digital file if digital product
                    if (typeDigital.checked) {
                        const digitalFile = digitalFileInput.files[0];
                        if (digitalFile && !validateDigitalFile(digitalFile, 'digitalFileError',
                                'digitalFileSuccess')) {
                            hasErrors = true;
                        }
                    }

                    if (hasErrors) {
                        alert('Terdapat file yang tidak valid. Silakan perbaiki terlebih dahulu.');
                        return;
                    }

                    // Proceed with upload
                    progressContainer.style.display = 'block';
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                    progressBar.classList.remove('bg-success');
                    progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
                    uploadStatus.textContent = 'Mempersiapkan upload...';

                    submitButton.disabled = true;
                    submitButton.innerHTML = `
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="ms-2">Mengupload...</span>
                    `;

                    const formData = new FormData(productForm);

                    const config = {
                        onUploadProgress: function(progressEvent) {
                            const percentCompleted = Math.round((progressEvent.loaded * 100) /
                                progressEvent.total);
                            progressBar.style.width = percentCompleted + '%';
                            progressBar.textContent = percentCompleted + '%';
                            uploadStatus.textContent =
                                `Mengupload... (${formatFileSize(progressEvent.loaded)} / ${formatFileSize(progressEvent.total)})`;

                            if (percentCompleted === 100) {
                                uploadStatus.textContent =
                                    'Upload selesai. Memproses file di server...';
                                submitButton.innerHTML = `
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="ms-2">Memproses...</span>
                                `;
                            }
                        }
                    };

                    axios.post(productForm.action, formData, config)
                        .then(function(response) {
                            uploadStatus.textContent = 'Produk berhasil disimpan! Mengalihkan...';
                            progressBar.classList.remove('progress-bar-striped',
                                'progress-bar-animated');
                            progressBar.classList.add('bg-success');
                            window.location.href = "{{ route('admin.products.index') }}";
                        })
                        .catch(function(error) {
                            progressContainer.style.display = 'none';
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="fas fa-save me-2"></i>Simpan Produk';

                            if (error.response && error.response.status === 422) {
                                uploadStatus.textContent = 'Gagal! Terdapat kesalahan pada input Anda.';
                                let errorMessages = 'Validasi gagal:\n';
                                for (const key in error.response.data.errors) {
                                    errorMessages += `- ${error.response.data.errors[key][0]}\n`;
                                }
                                alert(errorMessages);
                            } else {
                                uploadStatus.textContent = 'Terjadi kesalahan saat memproses file.';
                                alert('Error: ' + (error.response ? error.response.data.message : error
                                    .message));
                            }
                        });
                });
            }
        });
    </script>
@endpush
