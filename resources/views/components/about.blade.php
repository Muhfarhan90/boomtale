{{-- filepath: d:\FREELANCE\boomtale\resources\views\components\about-banner.blade.php --}}
<section class="about-banner py-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Image Gallery -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="position-relative">
                    <!-- Main Image Carousel -->
                    <div id="aboutCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
                        <div class="carousel-inner rounded-4 shadow-lg">
                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=350&fit=crop"
                                    class="d-block w-100" alt="Seminar BoomTale"
                                    style="height: 350px; object-fit: cover;">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/about/seminar-2.jpg') }}" class="d-block w-100"
                                    alt="Workshop Penulisan" style="height: 350px; object-fit: cover;">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/about/seminar-3.jpg') }}" class="d-block w-100"
                                    alt="Acara BoomTale" style="height: 350px; object-fit: cover;">
                            </div>
                        </div>

                        <!-- Carousel Indicators -->
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="0"
                                class="active"></button>
                            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#aboutCarousel" data-bs-slide-to="2"></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <h2 class="display-5 fw-bold text-center text-lg-start mb-4" style="color: #D4AF37;">
                        ABOUT US
                    </h2>

                    <p class="lead text-dark mb-4" style="text-align: justify; line-height: 1.8;">
                        BoomTale adalah platform penerbitan yang membantu siapa saja untuk menerbitkan buku dan eBook
                        secara legal, mudah, dan hemat biaya. Kami hadir untuk menjembatani penulis pemula hingga
                        profesional agar karyanya bisa dikenal luas — lengkap dengan ISBN dan hak cipta.
                    </p>

                    <!-- Features -->
                    <div class="row g-3 mt-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-dollar-sign fa-lg"
                                        style="color: #D4AF37; background: rgba(212, 175, 55, 0.1); padding: 12px; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #D4AF37;">Penerbitan Murah</h6>
                                    <small class="text-muted">Biaya terjangkau untuk semua kalangan</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-certificate fa-lg"
                                        style="color: #D4AF37; background: rgba(212, 175, 55, 0.1); padding: 12px; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #D4AF37;">ISBN & Hak Cipta</h6>
                                    <small class="text-muted">Legalitas lengkap untuk karya Anda</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex align-items-center mb-3">
                                <div class="feature-icon me-3">
                                    <i class="fas fa-book-open fa-lg"
                                        style="color: #D4AF37; background: rgba(212, 175, 55, 0.1); padding: 12px; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold" style="color: #D4AF37;">eBook Siap Jual & Baca</h6>
                                    <small class="text-muted">Format digital yang dapat langsung dipasarkan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-4">
                        <a href="{{ route('user.products.index') }}" class="btn btn-lg px-4 py-2"
                            style="background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%); color: white; border: none; border-radius: 25px;">
                            <i class="fas fa-rocket me-2"></i>Mulai Menerbitkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .about-banner {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        position: relative;
        overflow: hidden;
    }

    .about-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .carousel-indicators [data-bs-target] {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(212, 175, 55, 0.5);
        border: 2px solid #D4AF37;
    }

    .carousel-indicators .active {
        background-color: #D4AF37;
    }

    @media (max-width: 768px) {
        .about-banner {
            padding: 3rem 0;
        }

        .feature-icon {
            width: 40px !important;
            height: 40px !important;
        }

        .feature-icon i {
            font-size: 0.9rem !important;
            padding: 10px !important;
        }
    }
</style>
