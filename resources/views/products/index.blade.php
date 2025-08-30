{{-- resources/views/user/products/index.blade.php --}}
@extends('user.layouts.app')

@section('title', 'Produk Digital - Boomtale')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3">Semua Produk</h2>

                <!-- Filter Bar -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="typeFilter">
                            <option value="">Semua Tipe</option>
                            <option value="ebook" {{ request('type') == 'ebook' ? 'selected' : '' }}>eBook</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortFilter">
                            <option value="">Urutkan</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah
                            </option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga
                                Tertinggi</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary w-100" id="resetFilter">
                            <i class="fas fa-undo me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsGrid">
            @forelse($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 product-card">
                        @if ($product->cover_image)
                            <img src="{{ Storage::url($product->cover_image) }}" class="card-img-top"
                                alt="{{ $product->title }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 200px;">
                                <i
                                    class="fas fa-{{ $product->type == 'ebook' ? 'book' : 'play-circle' }} fa-3x text-muted"></i>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary">{{ ucfirst($product->type) }}</span>
                                @if ($product->category)
                                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                @endif
                            </div>

                            <h6 class="card-title">{{ Str::limit($product->title, 50) }}</h6>
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($product->description, 80) }}
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="price">
                                        <span class="h6 text-primary mb-0">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <a href="{{ route('user.products.show', $product) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    @auth
                                        <button class="btn btn-boomtale btn-sm btn-add-cart"
                                            data-product-id="{{ $product->id }}">
                                            <i class="fas fa-cart-plus me-1"></i>Keranjang
                                        </button>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-boomtale btn-sm">
                                            <i class="fas fa-sign-in-alt me-1"></i>Login
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada produk</h5>
                    <p class="text-muted">Produk akan segera hadir!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Filter functionality
            $('#categoryFilter, #typeFilter, #sortFilter').change(function() {
                applyFilters();
            });

            $('#resetFilter').click(function() {
                window.location.href = '{{ route('user.products.index') }}';
            });

            function applyFilters() {
                const params = new URLSearchParams();

                const category = $('#categoryFilter').val();
                const type = $('#typeFilter').val();
                const sort = $('#sortFilter').val();
                const search = '{{ request('search') }}';

                if (category) params.append('category', category);
                if (type) params.append('type', type);
                if (sort) params.append('sort', sort);
                if (search) params.append('search', search);

                window.location.href = '{{ route('user.products.index') }}?' + params.toString();
            }

            // Add to Cart
            $('.btn-add-cart').click(function() {
                const productId = $(this).data('product-id');
                const button = $(this);

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

                $.post('{{ route('user.cart.add') }}', {
                        product_id: productId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(response) {
                        if (response.success) {
                            button.html('<i class="fas fa-check me-1"></i>Ditambahkan').removeClass(
                                'btn-boomtale').addClass('btn-success');
                            updateCartCount();

                            setTimeout(() => {
                                button.prop('disabled', false).html(
                                        '<i class="fas fa-cart-plus me-1"></i>Keranjang')
                                    .removeClass('btn-success').addClass('btn-boomtale');
                            }, 2000);
                        } else {
                            alert(response.message);
                            button.prop('disabled', false).html(
                                '<i class="fas fa-cart-plus me-1"></i>Keranjang');
                        }
                    })
                    .fail(function() {
                        alert('Gagal menambahkan ke keranjang');
                        button.prop('disabled', false).html(
                            '<i class="fas fa-cart-plus me-1"></i>Keranjang');
                    });
            });
        });
    </script>
@endpush
