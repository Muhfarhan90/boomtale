@extends('layouts.app')

@section('title', 'Profile Saya')

@push('styles')
    <style>
        .profile-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            color: white;
            text-align: center;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating .form-control:disabled {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }

        .btn-save {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="profile-card">
                    <!-- Profile Header -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <h4 class="mb-2">{{ auth()->user()->name }}</h4>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-envelope me-1"></i>
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <!-- Profile Form -->
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('user.profile.update') }}" method="POST" id="profileForm">
                            @csrf
                            @method('PUT')

                            <!-- Nama -->
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                    placeholder="Nama Lengkap" required>
                                <label for="name">
                                    <i class="fas fa-user me-1"></i>Nama Lengkap
                                </label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email (Read Only) -->
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email"
                                    value="{{ auth()->user()->email }}" placeholder="Email" disabled>
                                <label for="email">
                                    <i class="fas fa-envelope me-1"></i>Email
                                </label>
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Email tidak dapat diubah untuk keamanan akun
                                </div>
                            </div>

                            <!-- Nomor Telepon -->
                            <div class="form-floating">
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror"
                                    id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                    placeholder="Nomor Telepon">
                                <label for="phone_number">
                                    <i class="fas fa-phone me-1"></i>Nomor Telepon
                                </label>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Contoh: 08123456789 atau +6281234567890
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-save text-white">
                                    <i class="fas fa-save me-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Form validation
            $('#profileForm').on('submit', function(e) {
                const name = $('#name').val().trim();
                const phone = $('#phone_number').val().trim();

                // Validate name
                if (name.length < 2) {
                    e.preventDefault();
                    showNotification('Nama harus minimal 2 karakter', 'danger');
                    $('#name').focus();
                    return false;
                }

                // Validate phone if filled
                if (phone && !isValidPhone(phone)) {
                    e.preventDefault();
                    showNotification('Format nomor telepon tidak valid', 'danger');
                    $('#phone_number').focus();
                    return false;
                }
            });

            // Phone number validation
            function isValidPhone(phone) {
                // Remove spaces and dashes
                const cleanPhone = phone.replace(/[\s-]/g, '');
                // Indonesian phone regex
                const phoneRegex = /^(\+62|62|0)[0-9]{9,13}$/;
                return phoneRegex.test(cleanPhone);
            }

            // Format phone input on typing
            $('#phone_number').on('input', function() {
                let value = $(this).val();
                // Remove non-numeric characters except + at the beginning
                value = value.replace(/[^\d+]/g, '');
                // Ensure + is only at the beginning
                if (value.indexOf('+') > 0) {
                    value = value.replace(/\+/g, '');
                }
                $(this).val(value);
            });

            // Notification function
            function showNotification(message, type = 'success') {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';

                const notification = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed"
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                <i class="fas fa-${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

                $('body').append(notification);

                // Auto hide after 4 seconds
                setTimeout(() => {
                    $('.alert').fadeOut();
                    setTimeout(() => {
                        $('.alert').remove();
                    }, 300);
                }, 4000);
            }
        });
    </script>
@endpush
