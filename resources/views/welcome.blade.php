{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="display-4 mb-4">Selamat Datang di <span style="color: var(--primary-color)">BOOMTALE</span></h1>
                <p class="lead">Platform digital terbaik untuk ebook dan video pembelajaran berkualitas tinggi.</p>

                @auth
                    <div class="alert alert-success d-inline-block">
                        <i class="fas fa-check-circle"></i>
                        Selamat datang kembali, {{ auth()->user()->name }}!
                    </div>
                @else
                    <div class="mt-4">
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-rocket"></i> Mulai Sekarang
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-book"></i> Lihat Produk
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
