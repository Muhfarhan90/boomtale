@extends('layouts.app')

@section('title', 'Produk Digital - Boomtale')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="mb-3 d-none d-md-block">All Products</h2>
                <h5 class="mb-3 d-md-none">Digital Products</h5>

                <!-- Search Bar -->
                <div class="row mb-4">
                    <div class="col-12 col-md-8 col-lg-6">
                        <x-search-bar :action="route('user.products.index')" :value="request('search')">
                            @if (request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if (request('sort'))
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                            @endif
                        </x-search-bar>
                    </div>
                </div>

                <!-- Active Filters -->
                @if (request()->hasAny(['search', 'category', 'sort']))
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="d-flex flex-wrap align-items-center">
                                <span class="text-muted me-2 mb-2" style="font-size: 0.9rem;">Filter active:</span>

                                @if (request('search'))
                                    <span class="filter-badge">
                                        <i class="fas fa-search me-1"></i>
                                        "{{ request('search') }}"
                                        <span class="remove-filter" onclick="removeFilter('search')">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </span>
                                @endif

                                @if (request('category'))
                                    @php
                                        $selectedCategory = $categories->find(request('category'));
                                    @endphp
                                    @if ($selectedCategory)
                                        <span class="filter-badge">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $selectedCategory->name }}
                                            <span class="remove-filter" onclick="removeFilter('category')">
                                                <i class="fas fa-times"></i>
                                            </span>
                                        </span>
                                    @endif
                                @endif

                                @if (request('sort'))
                                    @php
                                        $sortLabels = [
                                            'newest' => 'Terbaru',
                                            'price_low' => 'Termurah',
                                            'price_high' => 'Termahal',
                                        ];
                                    @endphp
                                    <span class="filter-badge">
                                        <i class="fas fa-sort me-1"></i>
                                        {{ $sortLabels[request('sort')] ?? request('sort') }}
                                        <span class="remove-filter" onclick="removeFilter('sort')">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </span>
                                @endif

                                <a href="{{ route('user.products.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
                                    <i class="fas fa-times me-1"></i>Delete All
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Filter Bar - Condensed for Mobile -->
                <div class="row g-2 mb-4">
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-md" id="categoryFilter">
                            <option value="">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <select class="form-select form-select-md" id="sortFilter">
                            <option value="">Sorting</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest Price
                            </option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest Price
                            </option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        </select>
                    </div>
                    {{-- <div class="col-6 col-md-3 d-none d-md-block">
                        <button class="btn btn-outline-secondary btn-sm w-100" id="resetFilter">
                            <i class="fas fa-undo me-1"></i>Reset
                        </button>
                    </div> --}}
                    <div class="col-6 col-md-6">
                        <div class="text-muted text-end" style="font-size: 0.85rem; padding: 0.375rem 0.5rem;">
                            {{ $products->total() }} products found
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row" id="productsGrid">
            @forelse($products as $product)
                <div class="col-6 col-md-4 col-lg-3 mb-3">
                    <x-product-card :product="$product" />
                </div>
            @empty
                <x-empty-state icon="fas fa-search" title="Product not found"
                    message="Try using different keywords or removing existing filters." :actionUrl="route('user.products.index')"
                    actionText="See All Products" />
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
            // Search functionality
            $('#searchInput').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    $(this).closest('form').submit();
                }
            });

            // Filter functionality
            $('#categoryFilter, #sortFilter').change(function() {
                applyFilters();
            });

            $('#resetFilter').click(function() {
                window.location.href = '{{ route('user.products.index') }}';
            });

            function applyFilters() {
                const params = new URLSearchParams();

                const category = $('#categoryFilter').val();
                const sort = $('#sortFilter').val();
                const search = '{{ request('search') }}';

                if (category) params.append('category', category);
                if (sort) params.append('sort', sort);
                if (search) params.append('search', search);

                window.location.href = '{{ route('user.products.index') }}?' + params.toString();
            }

            // Remove specific filter
            window.removeFilter = function(filterType) {
                const params = new URLSearchParams(window.location.search);
                params.delete(filterType);

                const newUrl = '{{ route('user.products.index') }}' +
                    (params.toString() ? '?' + params.toString() : '');
                window.location.href = newUrl;
            };

            // Add to Cart with improved feedback
            $('.btn-add-cart').click(function() {
                const productId = $(this).data('product-id');
                const button = $(this);
                const originalText = button.html();

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');

                $.post('{{ route('user.cart.add') }}', {
                        product_id: productId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    })
                    .done(function(response) {
                        if (response.success) {
                            button.html('<i class="fas fa-check me-1"></i>Success')
                                .removeClass('btn-boomtale')
                                .addClass('btn-success');

                            // Show toast notification
                            showToast('Product Success added to cart!', 'success');

                            setTimeout(() => {
                                button.prop('disabled', false)
                                    .html(originalText)
                                    .removeClass('btn-success')
                                    .addClass('btn-boomtale');
                            }, 2000);
                        } else {
                            showToast(response.message, 'error');
                            button.prop('disabled', false).html(originalText);
                        }
                    })
                    .fail(function(xhr) {
                        const response = xhr.responseJSON;
                        showToast(response?.message || 'Failed to add to cart', 'error');
                        button.prop('disabled', false).html(originalText);
                    });
            });

            // Toast notification function
            function showToast(message, type = 'success') {
                const toastClass = type === 'success' ? 'bg-success' : 'bg-danger';
                const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';

                const toast = `
                    <div class="toast align-items-center text-white ${toastClass} border-0 position-fixed"
                         style="top: 20px; right: 20px; z-index: 9999;" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-${icon} me-2"></i>${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                    data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;

                $('body').append(toast);
                $('.toast').last().toast({
                    delay: 3000
                }).toast('show');

                // Remove toast element after it's hidden
                $('.toast').last().on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // Image loading error handling
            $('img.card-img-top').on('error', function() {
                $(this).hide();
                $(this).next('.image-placeholder').removeClass('d-none').addClass('d-flex');
            });
        });
    </script>
@endpush
