<!-- Success Alert -->
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show alert-boomtale" role="alert" data-auto-dismiss="5000">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">Berhasil!</div>
                <div class="alert-message">{{ session('success') }}</div>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Error Alert -->
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show alert-boomtale" role="alert" data-auto-dismiss="7000">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">Gagal!</div>
                <div class="alert-message">{{ session('error') }}</div>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Warning Alert -->
@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show alert-boomtale" role="alert" data-auto-dismiss="6000">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">Peringatan!</div>
                <div class="alert-message">{{ session('warning') }}</div>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Info Alert -->
@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show alert-boomtale" role="alert" data-auto-dismiss="5000">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">Informasi</div>
                <div class="alert-message">{{ session('info') }}</div>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Validation Errors Alert -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show alert-boomtale" role="alert" data-auto-dismiss="10000">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">Terdapat kesalahan!</div>
                <div class="alert-message">
                    <p class="mb-2">Silakan periksa data yang Anda masukkan:</p>
                    <ul class="alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Success with Action Alert (Optional) -->
@if (session('success_with_action'))
    <div class="alert alert-success alert-dismissible fade show alert-boomtale alert-with-action" role="alert">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-body">
                <div class="alert-title">Berhasil!</div>
                <div class="alert-message">{{ session('success_with_action')['message'] }}</div>
            </div>
            <div class="alert-actions">
                @if (isset(session('success_with_action')['action_url']))
                    <a href="{{ session('success_with_action')['action_url'] }}" class="btn btn-sm btn-outline-success">
                        {{ session('success_with_action')['action_text'] ?? 'Lihat' }}
                    </a>
                @endif
            </div>
            <button type="button" class="alert-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif

<!-- Notification Toasts Container -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11;">
    <!-- Toasts will be dynamically inserted here -->
</div>
