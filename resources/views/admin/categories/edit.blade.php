@extends('admin.layouts.app')

@section('page-title', 'Edit Kategori')
@section('page-subtitle', 'Edit kategori: ' . $category->name)

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active">Edit Kategori</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                <!-- Main Form -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Edit Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.categories.update', $category) }}" method="POST" id="categoryForm">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Nama Kategori <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $category->name) }}"
                                    placeholder="Masukkan nama kategori" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            {{-- <div class="mb-3">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3" placeholder="Deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <!-- Icon -->
                            {{-- <div class="mb-3">
                                <label for="icon" class="form-label">Icon</label>
                                <select class="form-select @error('icon') is-invalid @enderror" id="icon"
                                    name="icon">
                                    <option value="fas fa-folder"
                                        {{ old('icon', $category->icon) == 'fas fa-folder' ? 'selected' : '' }}>📁 Folder
                                    </option>
                                    <option value="fas fa-book"
                                        {{ old('icon', $category->icon) == 'fas fa-book' ? 'selected' : '' }}>📚 Book
                                    </option>
                                    <option value="fas fa-video"
                                        {{ old('icon', $category->icon) == 'fas fa-video' ? 'selected' : '' }}>📹 Video
                                    </option>
                                    <option value="fas fa-graduation-cap"
                                        {{ old('icon', $category->icon) == 'fas fa-graduation-cap' ? 'selected' : '' }}>🎓
                                        Course</option>
                                    <option value="fas fa-music"
                                        {{ old('icon', $category->icon) == 'fas fa-music' ? 'selected' : '' }}>🎵 Audio
                                    </option>
                                    <option value="fas fa-image"
                                        {{ old('icon', $category->icon) == 'fas fa-image' ? 'selected' : '' }}>🖼️ Image
                                    </option>
                                    <option value="fas fa-code"
                                        {{ old('icon', $category->icon) == 'fas fa-code' ? 'selected' : '' }}>💻 Code
                                    </option>
                                </select>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <!-- Status -->
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif
                                    </label>
                                </div>
                                <div class="form-text">Kategori aktif akan ditampilkan di halaman publik</div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Kategori
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-2"></i>Lihat
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Update preview
            function updatePreview() {
                const name = $('#name').val() || '{{ $category->name }}';
                const description = $('#description').val() || 'Tidak ada deskripsi';
                const icon = $('#icon').val() || 'fas fa-folder';
                const isActive = $('#is_active').is(':checked');

                $('#previewName').text(name);
                $('#previewDescription').text(description);
                $('#previewIcon').attr('class', icon + ' fa-3x text-secondary');
                $('#previewStatus').removeClass('bg-success bg-secondary')
                    .addClass(isActive ? 'bg-success' : 'bg-secondary')
                    .text(isActive ? 'Aktif' : 'Tidak Aktif');
            }

            // Event listeners
            $('#name, #description, #icon, #is_active').on('input change', updatePreview);

            // Initial preview update
            updatePreview();
        });
    </script>
@endpush
