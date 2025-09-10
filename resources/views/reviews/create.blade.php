@extends('layouts.app')

@section('title', 'Write Review for ' . $orderItem->product->name)

@push('styles')
    <style>
        .product-review-card img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Star Rating CSS */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            /* Important for hover effect */
            justify-content: flex-end;
            font-size: 2.5rem;
            color: #ddd;
            cursor: pointer;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            transition: color 0.2s;
        }

        /* Hover effect */
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffc107;
            /* Star color on hover */
        }

        /* Checked effect */
        .star-rating input[type="radio"]:checked~label {
            color: #ffc107;
            /* Star color when selected */
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-4">
                    <h1 class="h3">Write Your Review</h1>
                    <p class="text-muted">Share your opinion about the product you purchased.</p>
                </div>

                @if (session('info'))
                    <div class="alert alert-info">
                        {{ session('info') }}
                    </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <!-- Product Info -->
                        <div class="d-flex align-items-center mb-4 pb-3 border-bottom product-review-card">
                            <img src="{{ $orderItem->product->featured_image ? Storage::url($orderItem->product->featured_image) : 'https://via.placeholder.com/150' }}"
                                alt="{{ $orderItem->product->name }}" class="me-3">
                            <div>
                                <h5 class="card-title mb-0">{{ $orderItem->product->name }}</h5>
                                <small class="text-muted">Purchased on
                                    {{ $orderItem->created_at->format('F d, Y') }}</small>
                            </div>
                        </div>

                        <form action="{{ route('user.reviews.store', $orderItem) }}" method="POST">
                            @csrf

                            <!-- Star Rating Input -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Your Rating <span class="text-danger">*</span></label>
                                <div class="star-rating">
                                    <input type="radio" id="star5" name="rating" value="5"
                                        {{ old('rating') == 5 ? 'checked' : '' }} required><label for="star5"
                                        title="5 stars">&#9733;</label>
                                    <input type="radio" id="star4" name="rating" value="4"
                                        {{ old('rating') == 4 ? 'checked' : '' }}><label for="star4"
                                        title="4 stars">&#9733;</label>
                                    <input type="radio" id="star3" name="rating" value="3"
                                        {{ old('rating') == 3 ? 'checked' : '' }}><label for="star3"
                                        title="3 stars">&#9733;</label>
                                    <input type="radio" id="star2" name="rating" value="2"
                                        {{ old('rating') == 2 ? 'checked' : '' }}><label for="star2"
                                        title="2 stars">&#9733;</label>
                                    <input type="radio" id="star1" name="rating" value="1"
                                        {{ old('rating') == 1 ? 'checked' : '' }}><label for="star1"
                                        title="1 star">&#9733;</label>
                                </div>
                                @error('rating')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comment Input -->
                            <div class="mb-3">
                                <label for="comment" class="form-label fw-bold">Your Comment (Optional)</label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="5"
                                    placeholder="Write your experience using this product...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Review
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
