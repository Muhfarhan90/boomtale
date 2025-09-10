{{-- filepath: d:\FREELANCE\boomtale\resources\views\home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home - Boomtale')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section text-center text-lg-start">
        <div class="container">
            <div class="row align-items-center pt-4">
                <div class="col-lg-6">
                    {{-- PERBAIKAN: Ukuran font dibuat responsif --}}
                    <h1 class="fs-2 fw-bold mb-3">Welcome to Boomtale</h1>
                    <p class="lead mb-4">Discover various high-quality digital products for your needs.</p>
                    <a href="{{ route('user.products.index') }}" class="btn btn-boomtale btn-sm">
                        <i class="fas fa-play me-2"></i>Start Exploring
                    </a>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="fas fa-rocket fa-8x" style="color: #c9a877;"></i>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-4">
        <!-- Search Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="">
                        <form action="{{ route('user.products.index') }}" method="GET">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search for products...">
                                <button class="btn btn-boomtale" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- DIHAPUS: About Us Banner Component -->

        <!-- DIHAPUS: Features Section -->

        <!-- Latest Products -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    {{-- PERBAIKAN: Ukuran font dibuat lebih kecil untuk mobile --}}
                    <h2 class="h3 mb-0">Latest Products</h2>
                    <a href="{{ route('user.products.index') }}" class="btn btn-outline-boomtale btn-sm">View All</a>
                </div>
                <div class="row g-3">
                    @forelse ($latestProducts as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <x-product-card :product="$product" />
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">There are no products available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="row mb-5">
            <div class="col-12">
                {{-- PERBAIKAN: Ukuran font dibuat lebih kecil untuk mobile --}}
                <h2 class="h3 text-center mb-3">Explore Categories</h2>
                <div class="row g-3">
                    @forelse ($categories as $category)
                        <div class="col-6 col-md-3">
                            <a href="{{ route('user.products.index', ['category' => $category->id]) }}"
                                class="text-decoration-none">
                                <div class="card text-center feature-card h-100">
                                    <div class="card-body">
                                        <i class="fas fa-folder fa-2x text-boomtale mb-2"></i>
                                        <h6 class="mb-0">{{ $category->name }}</h6>
                                        <small class="text-muted">{{ $category->products_count }} products</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">There are no categories available yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @php
            // Ambil data setting untuk footer
            $footerSettings = \App\Models\Setting::whereIn('setting_key', [
                'site_email',
                'site_phone',
                'site_phone',
                'contact_address',
                'social_facebook',
                'social_instagram',
                'social_twitter',
                'social_youtube',
                'site_name',
                'site_description',
            ])->pluck('setting_value', 'setting_key');
        @endphp
    </div>

    {{-- Footer Information Settings - DIPERCANTIK --}}
    <footer class="elegant-footer">
        <div class="footer-gradient">
            <div class="container">
                <div class="row align-items-center py-5">
                    <!-- Company Info -->
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="footer-brand mb-3">
                            <img src="{{ asset('logo_boomtale.png') }}" alt="Boomtale Logo" class="footer-logo">
                            <p class="footer-tagline">
                                {{ $footerSettings['site_description'] ?? 'Leading Digital Platform' }}</p>
                        </div>

                        <div class="contact-info">
                            @if (!empty($footerSettings['contact_address']))
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <span class="contact-text">{{ $footerSettings['contact_address'] }}</span>
                                </div>
                            @endif

                            @if (!empty($footerSettings['site_email']))
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <a href="mailto:{{ $footerSettings['site_email'] }}" class="contact-link">
                                        {{ $footerSettings['site_email'] }}
                                    </a>
                                </div>
                            @endif

                            @if (!empty($footerSettings['site_phone']))
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fab fa-whatsapp"></i>
                                    </div>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $footerSettings['site_phone']) }}"
                                        target="_blank" class="contact-link">
                                        {{ $footerSettings['site_phone'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Social Media & Copyright -->
                    <div class="col-lg-6 text-lg-end">
                        <div class="social-section mb-4">
                            <h5 class="social-title mb-3">Follow me</h5>
                            <div class="social-links">
                                @if (!empty($footerSettings['social_facebook']))
                                    <a href="{{ $footerSettings['social_facebook'] }}" target="_blank"
                                        class="social-link facebook">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                @endif
                                @if (!empty($footerSettings['social_instagram']))
                                    <a href="{{ $footerSettings['social_instagram'] }}" target="_blank"
                                        class="social-link instagram">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                @endif
                                @if (!empty($footerSettings['social_twitter']))
                                    <a href="{{ $footerSettings['social_twitter'] }}" target="_blank"
                                        class="social-link twitter">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                @endif
                                @if (!empty($footerSettings['social_youtube']))
                                    <a href="{{ $footerSettings['social_youtube'] }}" target="_blank"
                                        class="social-link youtube">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="copyright">
                            <div class="copyright-text">
                                <p class="mb-1">© {{ date('Y') }} {{ $footerSettings['site_name'] ?? 'Boomtale' }}
                                </p>
                                <p class="mb-0">All rights reserved</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Wave -->
        <div class="footer-wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                    opacity=".25"></path>
                <path
                    d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                    opacity=".5"></path>
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z">
                </path>
            </svg>
        </div>
    </footer>
@endsection

@push('styles')
    <style>
        .elegant-footer {
            position: relative;
            margin-top: 3rem;
            background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%);
            color: #f8f9fa;
            overflow: hidden;
        }

        .footer-gradient {
            position: relative;
            z-index: 2;
        }

        .footer-brand {
            position: relative;
        }

        .footer-logo-container {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .footer-logo {
            width: 300px;
            height: 100px;
            object-fit: contain;
            margin-right: 1rem;
            filter: brightness(1.2) drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.3));
        }

        .footer-title {
            color: #C5A572;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .footer-tagline {
            color: #adb5bd;
            font-style: italic;
            margin-bottom: 0;
            font-size: 1rem;
        }

        .contact-info {
            margin-top: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            transition: transform 0.3s ease;
        }

        .contact-item:hover {
            transform: translateX(5px);
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #C5A572, #B8986A);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            box-shadow: 0 4px 8px rgba(197, 165, 114, 0.3);
        }

        .contact-icon i {
            color: white;
            font-size: 1rem;
        }

        .contact-text {
            color: #f8f9fa;
            line-height: 1.4;
        }

        .contact-link {
            color: #C5A572;
            text-decoration: none;
            transition: all 0.3s ease;
            line-height: 1.4;
        }

        .contact-link:hover {
            color: #fff;
            text-shadow: 0 0 8px rgba(197, 165, 114, 0.8);
        }

        .social-section {
            text-align: center;
        }

        .social-title {
            color: #C5A572;
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .social-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .social-link:hover::before {
            left: 100%;
        }

        .social-link i {
            font-size: 1.25rem;
            z-index: 1;
        }

        .social-link.facebook {
            background: linear-gradient(135deg, #3b5998, #2d4373);
            color: white;
        }

        .social-link.instagram {
            background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
        }

        .social-link.twitter {
            background: linear-gradient(135deg, #1da1f2, #0d8bd9);
            color: white;
        }

        .social-link.youtube {
            background: linear-gradient(135deg, #ff0000, #cc0000);
            color: white;
        }

        .social-link:hover {
            transform: translateY(-3px) scale(1.1);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .copyright {
            margin-top: 2rem;
            text-align: center;
            border-top: 1px solid rgba(197, 165, 114, 0.3);
            padding-top: 1.5rem;
        }

        .copyright-text p {
            color: #adb5bd;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .footer-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .footer-wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 60px;
            fill: #C5A572;
            opacity: 0.3;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .social-section {
                text-align: center;
                margin-bottom: 2rem;
            }

            .copyright {
                text-align: center;
            }

            .footer-title {
                font-size: 1.75rem;
                text-align: center;
            }

            .contact-info {
                text-align: center;
            }

            .contact-item {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .footer-title {
                font-size: 1.5rem;
            }

            .social-links {
                gap: 0.75rem;
            }

            .social-link {
                width: 45px;
                height: 45px;
            }

            .social-link i {
                font-size: 1.1rem;
            }

            .contact-icon {
                width: 35px;
                height: 35px;
            }

            .contact-icon i {
                font-size: 0.9rem;
            }
        }

        /* Animation untuk page load */
        .elegant-footer {
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hover effect untuk seluruh footer */
        .elegant-footer:hover .footer-wave svg {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Add to Cart buttons
            document.querySelectorAll('.btn-add-cart').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const productId = this.getAttribute('data-product-id');
                    const originalText = this.innerHTML;

                    // Disable button and show loading
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';

                    // Send AJAX request
                    fetch('{{ route('user.cart.add') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showToast('Product successfully add to cart!',
                                    'success');

                                // Update cart badge if exists
                                updateCartBadge();

                                // Change button to "Added"
                                this.innerHTML = '<i class="fas fa-check me-1"></i>Added';
                                this.classList.remove('btn-boomtale');
                                this.classList.add('btn-success');

                                // Reset button after 2 seconds
                                setTimeout(() => {
                                    this.innerHTML = originalText;
                                    this.classList.remove('btn-success');
                                    this.classList.add('btn-boomtale');
                                    this.disabled = false;
                                }, 2000);

                            } else {
                                throw new Error(data.message ||
                                    'Failed to add to cart');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast(error.message || 'An error occurred!', 'error');

                            // Reset button
                            this.innerHTML = originalText;
                            this.disabled = false;
                        });
                });
            });
        });

        // Function to show toast notification
        function showToast(message, type = 'info') {
            // Remove existing toasts
            document.querySelectorAll('.toast-notification').forEach(toast => toast.remove());

            const toastTypes = {
                'success': 'bg-success',
                'error': 'bg-danger',
                'warning': 'bg-warning',
                'info': 'bg-info'
            };

            const toast = document.createElement('div');
            toast.className = `toast-notification alert ${toastTypes[type] || toastTypes.info} text-white position-fixed`;
            toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease-out;
        `;

            toast.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle'} me-2"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close btn-close-white" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;

            document.body.appendChild(toast);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.style.animation = 'slideOutRight 0.3s ease-in';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }

        // Function to update cart badge
        function updateCartBadge() {
            fetch('{{ route('user.cart.count') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.querySelector('.cart-badge');
                    if (badge && data.count !== undefined) {
                        badge.textContent = data.count;
                        badge.style.display = data.count > 0 ? 'flex' : 'none';

                        // Add pulse animation
                        badge.style.animation = 'pulse 0.5s ease-in-out';
                        setTimeout(() => {
                            badge.style.animation = '';
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error('Error updating cart badge:', error);
                });
        }
    </script>

    <style>
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.2);
            }

            100% {
                transform: scale(1);
            }
        }

        .btn-add-cart:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>
@endpush
