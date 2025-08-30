@extends('admin.layouts.app')

@section('page-title', 'Manajemen Pengguna')

@section('content')
    <div class="container-fluid">
        <!-- Page Header & Filters -->
        <div class="row mb-4 align-items-center">
            <div class="col-12 col-md-auto mb-3 mb-md-0">
                <a href="{{ route('admin.users.create') }}" class="btn btn-boomtale">
                    <i class="fas fa-plus me-2"></i>Tambah Pengguna
                </a>
            </div>
            <div class="col-12 col-md">
                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
                    <div class="col">
                        <div class="col">
                            <input type="text" name="search" class="form-control" placeholder="Cari produk..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-auto">
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-outline-boomtale" title="Cari & Filter">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th class="text-center">Role</th>
                                <th class="text-center">Status</th>
                                <th>Terdaftar</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <span
                                                    class="avatar-initial rounded-circle bg-secondary-soft text-secondary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                    class="text-dark fw-bold text-decoration-none">{{ $user->name }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone_number ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-boomtale-soft text-boomtale">Admin</span>
                                        @else
                                            <span class="badge bg-info-soft text-info">User</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($user->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                                class="btn btn-outline-info btn-sm" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="btn btn-outline-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        title="Hapus"
                                                        data-confirm-delete="Yakin ingin menghapus pengguna '{{ $user->name }}'?">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum Ada Pengguna</h5>
                                        <p class="text-muted mb-4">Mulai dengan menambahkan pengguna pertama Anda.</p>
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-boomtale">
                                            <i class="fas fa-plus me-2"></i>Tambah Pengguna
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($users->hasPages() || $users->count() > 0)
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }} dari {{ $users->total() }}
                        hasil
                    </div>
                    <div>
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation for single delete
            document.querySelectorAll('[data-confirm-delete]').forEach(button => {
                button.addEventListener('click', function(e) {
                    const message = this.getAttribute('data-confirm-delete');
                    if (!confirm(message)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
