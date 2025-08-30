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

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                    id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="6">{{ old('description', $product->description) }}</textarea>
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
                                                value="digital"
                                                {{ old('type', $product->type) == 'digital' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="typeDigital">Digital</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="type" id="typePhysical"
                                                value="physical"
                                                {{ old('type', $product->type) == 'physical' ? 'checked' : '' }}>
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
                                            id="price" name="price" value="{{ old('price', $product->price) }}"
                                            required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Digital Product Fields -->
                            <div id="digitalFields" class="mb-3">
                                <label for="digital_file" class="form-label">File Digital</label>
                                @if ($product->digital_file_path)
                                    <div class="current-file mb-2">
                                        <i class="fas fa-file-archive me-2"></i>
                                        File saat ini: {{ basename($product->digital_file_path) }}
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('digital_file') is-invalid @enderror"
                                    id="digital_file" name="digital_file">
                                <div class="form-text">Upload file baru untuk menggantikan yang lama (Maks: 100MB).</div>
                                @error('digital_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="digitalFileName" class="file-name"></div>
                            </div>

                            <!-- Physical Product Fields -->
                            <div id="physicalFields" class="mb-3">
                                <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                    id="stock" name="stock" value="{{ old('stock', $product->stock) }}">
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
                                    <label class="form-check-label" for="is_active">Aktifkan Produk</label>
                                </div>
                            </div>
                            <div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                        value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Jadikan Unggulan</label>
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
                                <label for="featured_image" class="form-label">Gambar Utama</label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                    id="featured_image" name="featured_image" accept="image/*">
                                <div class="form-text">Upload gambar baru untuk menggantikan yang lama.</div>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="image-preview-container" id="featuredImagePreviewContainer">
                                    @if ($product->featured_image)
                                        <div class="image-preview"><img
                                                src="{{ Storage::url($product->featured_image) }}" alt="Current Image">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div>
                                <label for="gallery_images" class="form-label">Galeri Gambar</label>
                                <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                                    id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                                <div class="form-text">Upload gambar baru untuk ditambahkan ke galeri.</div>
                                @error('gallery_images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="gallery-preview-container" id="galleryImagesPreviewContainer">
                                    @if ($product->gallery_images)
                                        @foreach ($product->gallery_images as $image)
                                            <div class="gallery-preview-item"><img src="{{ Storage::url($image) }}"
                                                    alt="Gallery Image"></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-boomtale btn-lg">
                            <i class="fas fa-sync-alt me-2"></i>Update Produk
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
                featuredImagePreviewContainer.innerHTML = ''; // Clear current/old preview
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        featuredImagePreviewContainer.innerHTML =
                            `<div class="image-preview"><img src="${e.target.result}" alt="New Preview"></div>`;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });

            // Gallery Images Preview (adds to existing)
            const galleryImagesInput = document.getElementById('gallery_images');
            const galleryImagesPreviewContainer = document.getElementById('galleryImagesPreviewContainer');
            galleryImagesInput.addEventListener('change', function() {
                // Do not clear existing images, just add new previews
                if (this.files) {
                    Array.from(this.files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = document.createElement('div');
                            previewItem.className = 'gallery-preview-item';
                            previewItem.innerHTML =
                                `<img src="${e.target.result}" alt="New Gallery Preview">`;
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
                    digitalFileName.textContent = `File baru: ${this.files[0].name}`;
                } else {
                    digitalFileName.textContent = '';
                }
            });
        });
    </script>
@endpush
