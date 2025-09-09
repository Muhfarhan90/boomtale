@extends('admin.layouts.app')

@section('page-title', 'Manajemen Produk')

@section('content')
    <div class="container-fluid">
        <!-- Page Header & Filters -->
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md-auto mb-3 mb-md-0">
                <a href="{{ route('admin.products.create') }}" class="btn btn-boomtale">
                    <i class="fas fa-plus me-2"></i>Tambah Produk
                </a>
            </div>
            <div class="col-12 col-md">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 align-items-center">
                    <div class="col">
                        <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <select name="category" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="type" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="digital" {{ request('type') == 'digital' ? 'selected' : '' }}>Digital</option>
                            <option value="physical" {{ request('type') == 'physical' ? 'selected' : '' }}>Fisik</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-boomtale">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary"
                            title="Reset Filter">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="80">Gambar</th>
                                <th>Produk</th>
                                <th>Tipe</th>
                                <th>Kategori</th>
                                <th>Harga Asli</th>
                                <th>Harga Diskon</th>
                                <th class="text-center">Terjual</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if ($product->featured_image)
                                            <img src="{{ Storage::url($product->featured_image) }}"
                                                alt="{{ $product->name }}" class="rounded"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-dark fw-bold text-decoration-none">{{ $product->name }}</a>
                                        @if ($product->is_featured)
                                            <span class="badge bg-info-soft text-info ms-1">
                                                <i class="fas fa-star"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($product->isDigital())
                                            <span class="badge bg-success-soft text-success">Digital</span>
                                        @else
                                            <span class="badge bg-warning-soft text-warning">Fisik</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $product->category->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-boomtale">{{ $product->formatted_price }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-boomtale">{{ $product->formatted_discount_price }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">{{ $product->user_products_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($product->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.products.show', $product) }}"
                                                class="btn btn-outline-info btn-sm" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product) }}"
                                                class="btn btn-outline-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"
                                                    data-confirm-delete="Yakin ingin menghapus produk '{{ $product->name }}'?">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="text-center py-5">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">Belum Ada Produk</h5>
                                            <p class="text-muted mb-4">Mulai dengan menambahkan produk pertama Anda.</p>
                                            <a href="{{ route('admin.products.create') }}" class="btn btn-boomtale">
                                                <i class="fas fa-plus me-2"></i>Tambah Produk
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($products->hasPages() || $products->count() > 0)
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $products->firstItem() }} sampai {{ $products->lastItem() }} dari
                        {{ $products->total() }} hasil
                    </div>
                    <div>
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

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
@endsection
