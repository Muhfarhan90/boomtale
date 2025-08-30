@extends('admin.layouts.app')

@section('title', 'Profil')

@section('page-title', 'Profil Admin')

@section('content')
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}" alt="Profile"
                                class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ auth()->user()->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ auth()->user()->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Telepon:</strong></td>
                                    <td>{{ auth()->user()->phone_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge badge-warning">{{ ucfirst(auth()->user()->role) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Bergabung:</strong></td>
                                    <td>{{ auth()->user()->created_at->format('d F Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Profil
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-key"></i> Ubah Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aktivitas Terakhir</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <i class="fas fa-sign-in-alt bg-success"></i>
                            <div class="timeline-content">
                                <h6>Login Terakhir</h6>
                                <p class="text-muted">{{ now()->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <i class="fas fa-user-plus bg-info"></i>
                            <div class="timeline-content">
                                <h6>Akun Dibuat</h6>
                                <p class="text-muted">{{ auth()->user()->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding: 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 50px;
            margin-bottom: 20px;
        }

        .timeline-item i {
            position: absolute;
            left: 0;
            top: 0;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .timeline-content h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .timeline-content p {
            margin-bottom: 0;
            font-size: 0.9rem;
        }
    </style>
@endpush
