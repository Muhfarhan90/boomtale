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
            /* Sembunyikan secara default */
            margin-top: 1rem;
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
                                <div class="form-text">Upload file ZIP, PDF,MP4 atau EPUB (Maks: 300MB).</div>
                                @error('digital_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="digitalFileName" class="file-name"></div>
                                {{-- Tambahkan Progress Bar di sini --}}
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
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="image-preview-container" id="featuredImagePreviewContainer"></div>
                            </div>
                            <hr>
                            <div>
                                <label for="gallery_images" class="form-label">Galeri Gambar</label>
                                <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                                    id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                @error('gallery_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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

            // Featured Image Preview
            const featuredImageInput = document.getElementById('featured_image');
            const featuredImagePreviewContainer = document.getElementById('featuredImagePreviewContainer');
            featuredImageInput.addEventListener('change', function() {
                featuredImagePreviewContainer.innerHTML = '';
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        featuredImagePreviewContainer.innerHTML =
                            `<div class="image-preview"><img src="${e.target.result}" alt="Preview"></div>`;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Gallery Images Preview
            const galleryImagesInput = document.getElementById('gallery_images');
            const galleryImagesPreviewContainer = document.getElementById('galleryImagesPreviewContainer');
            galleryImagesInput.addEventListener('change', function() {
                galleryImagesPreviewContainer.innerHTML = '';
                if (this.files) {
                    Array.from(this.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'gallery-preview-item';
                            previewItem.innerHTML =
                                `<img src="${e.target.result}" alt="Gallery Preview">`;
                            galleryImagesPreviewContainer.appendChild(previewItem);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Digital file name display
            const digitalFileInput = document.getElementById('digital_file');
            const digitalFileName = document.getElementById('digitalFileName');
            digitalFileInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    digitalFileName.textContent = `File dipilih: ${this.files[0].name}`;
                } else {
                    digitalFileName.textContent = '';
                }
            });

            // --- AJAX UPLOAD LOGIC ---
            const productForm = document.getElementById('productForm');
            const submitButton = document.getElementById('submitButton');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const uploadStatus = document.getElementById('uploadStatus');

            // Pastikan elemen ditemukan sebelum menambahkan event listener
            if (productForm) {
                productForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Mencegah form submit standar

                    // Reset dan tampilkan progress bar
                    progressContainer.style.display = 'block';
                    progressBar.style.width = '0%';
                    progressBar.textContent = '0%';
                    progressBar.classList.remove('bg-success');
                    progressBar.classList.add('progress-bar-striped', 'progress-bar-animated');
                    uploadStatus.textContent = 'Mempersiapkan upload...';

                    // Nonaktifkan tombol
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
                                `Mengupload... (${(progressEvent.loaded / 1024 / 1024).toFixed(2)} MB / ${(progressEvent.total / 1024 / 1024).toFixed(2)} MB)`;

                            // 3. UBAH LOGIKA SETELAH UPLOAD SELESAI
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
                            // Server telah selesai memproses dan merespons
                            uploadStatus.textContent = 'Produk berhasil disimpan! Mengalihkan...';
                            progressBar.classList.remove('progress-bar-striped',
                                'progress-bar-animated');
                            progressBar.classList.add('bg-success');

                            // Redirect ke halaman index
                            window.location.href = "{{ route('admin.products.index') }}";
                        })
                        .catch(function(error) {
                            // Jika terjadi error
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
