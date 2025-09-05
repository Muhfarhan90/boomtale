@extends('admin.layouts.app')

@section('page-title', 'Pengaturan Umum')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Pengaturan Umum</h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form action="{{ route('admin.settings.update.general') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Nama Aplikasi</label>
                                <input type="text" class="form-control" id="site_name" name="site_name"
                                    value="{{ $settings['site_name'] }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="site_email" class="form-label">Email Sistem</label>
                                <input type="email" class="form-control" id="site_email" name="site_email"
                                    value="{{ $settings['site_email'] }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="site_phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="site_phone" name="site_phone"
                                    value="{{ $settings['site_phone'] }}">
                            </div>
                            <div class="mb-3">
                                <label for="site_description" class="form-label">Deskripsi Aplikasi</label>
                                <textarea class="form-control" id="site_description" name="site_description" rows="3" required>{{ $settings['site_description'] }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-boomtale">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
