@props(['product'])

@push('styles')
    <style>
        .product-card .rating-stars {
            color: #ffc107;
            /* Warna kuning untuk bintang */
            font-size: 0.85rem;
        }
    </style>
@endpush

<div class="card h-100 product-card shadow-md border-0">
    <!-- Product Image -->
    <a href="{{ route('user.products.show', $product) }}">
        @if ($product->featured_image)
            <img src="{{ Storage::url($product->featured_image) }}" class="card-img-top" alt="{{ $product->name }}"
                style="height: 160px; object-fit: cover;"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="image-placeholder d-none align-items-center justify-content-center" style="height: 160px;">
                <i class="fas fa-image fa-2x text-muted"></i>
            </div>
        @else
            <div class="image-placeholder d-flex align-items-center justify-content-center" style="height: 160px;">
                <i class="fas fa-{{ $product->type === 'digital' ? 'download' : 'box' }} fa-2x text-muted"></i>
            </div>
        @endif
    </a>

    <div class="card-body d-flex flex-column p-2 p-md-3">
        <!-- Category Badge -->
        @if ($product->category)
            <span class="badge bg-secondary text-white mb-2 d-inline" style="font-size: 0.7rem;">
                {{ $product->category->name }}
            </span>
        @endif

        <!-- Product Title -->
        <h6 class="card-title" style="font-size: 0.9rem; line-height: 1.3;">
            <a href="{{ route('user.products.show', $product) }}" class="text-dark text-decoration-none">
                {{ Str::limit($product->name, 40) }}
            </a>
        </h6>

        <!-- Rating -->
        @if ($product->reviews_count > 0)
            <div class="rating-stars">
                @php
                    $rating = $product->reviews_avg_rating ?? 0;
                    $fullStars = floor($rating);
                    $hasHalfStar = $rating - $fullStars >= 0.5;
                @endphp
                @for ($i = 0; $i < $fullStars; $i++)
                    <i class="fas fa-star"></i>
                @endfor
                @if ($hasHalfStar)
                    <i class="fas fa-star-half-alt"></i>
                @endif
                @for ($i = 0; $i < 5 - $fullStars - ($hasHalfStar ? 1 : 0); $i++)
                    <i class="far fa-star"></i>
                @endfor
                <span class="rating-text">({{ number_format($rating, 1) }})</span>
            </div>
        @else
            <div class="rating-stars">
                @for ($i = 0; $i < 5; $i++)
                    <i class="far fa-star"></i>
                @endfor
                <br/>
                <span class="rating-text">Belum ada ulasan</span>
            </div>
        @endif

        <div class="mt-auto">
            <!-- Price -->
            <div class="price mb-2">
                <div class="d-flex align-items-center flex-wrap">
                    <span class="fw-bold text-boomtale me-2" style="font-size: 0.9rem;">
                        {{ $product->formatted_price }}
                    </span>
                    @if ($product->isDigital())
                        <span class="badge bg-info" style="font-size: 0.6rem;">
                            <i class="fas fa-download me-1"></i>Digital
                        </span>
                    @endif
                </div>
                {{-- JUMLAH TERJUAL --}}
                @if ($product)
                    <div class="sales-count mt-1">
                        <i class="fas fa-fire-alt text-danger"></i>
                        <span>{{ $product->orders_count ?? 0 }} terjual</span>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-1 d-md-flex">
                <a href="{{ route('user.products.show', $product) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-eye me-1"></i>
                    <span class="d-md-inline">Detail</span>
                </a>
                <button class="btn btn-boomtale btn-sm btn-add-cart" data-product-id="{{ $product->id }}">
                    <i class="fas fa-cart-plus me-1"></i>
                    <span class="d-md-inline">Cart</span>
                </button>
            </div>
        </div>
    </div>
</div>
