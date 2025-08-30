{{-- filepath: d:\FREELANCE\boomtale\resources\views\admin\categories\index.blade.php --}}
@extends('admin.layouts.app')

@section('page-title', 'Kelola Kategori')
@section('page-subtitle', 'Manajemen kategori produk')

@section('content')
    <div class="container-fluid">
        <!-- Header Actions -->
        <div class="row mb-4">
            <div class="col-md-6">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-boomtale">
                    <i class="fas fa-plus me-2"></i>Tambah Kategori
                </a>
            </div>
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari kategori..."
                        value="{{ request('search') }}">
                    <select name="status" class="form-select" style="width: auto;">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                        </option>
                    </select>
                    <button type="submit" class="btn btn-outline-boomtale">
                        <i class="fas fa-search"></i>
                    </button>
                    <div class="col-auto">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary"
                            title="Reset Filter">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Produk</th>
                                <th class="text-center">Status</th>
                                <th>Dibuat</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($category->icon)
                                                <i class="{{ $category->icon }} fa-lg me-3 text-boomtale"
                                                    style="width: 20px;"></i>
                                            @else
                                                <i class="fas fa-tag fa-lg me-3 text-muted" style="width: 20px;"></i>
                                            @endif
                                            <div>
                                                <a href="{{ route('admin.categories.edit', $category) }}"
                                                    class="text-dark fw-bold text-decoration-none">{{ $category->name }}</a>
                                                @if ($category->description)
                                                    <div class="text-muted small">
                                                        {{ Str::limit($category->description, 50) }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $category->products_count ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($category->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.categories.show', $category) }}"
                                                class="btn btn-outline-info btn-sm" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                                class="btn btn-outline-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if (($category->products_count ?? 0) == 0)
                                                <form method="POST"
                                                    action="{{ route('admin.categories.destroy', $category) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Hapus"
                                                        data-confirm-delete="Yakin ingin menghapus kategori '{{ $category->name }}'?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                        <div class="text-muted">Belum ada kategori</div>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-boomtale mt-2">
                                            <i class="fas fa-plus me-2"></i>Tambah Kategori Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($categories->hasPages() || $categories->count() > 0)
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $categories->firstItem() }} sampai {{ $categories->lastItem() }} dari
                        {{ $categories->total() }} hasil
                    </div>
                    <div>
                        {{ $categories->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation for single delete
            document.querySelectorAll('[data-confirm-delete]').forEach(button => {
                button.addEventListener('click', function(e) {
                    const message = this.getAttribute('data-confirm-delete');
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
