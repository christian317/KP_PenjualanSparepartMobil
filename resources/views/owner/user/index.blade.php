@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-people me-2" style="color: #1565c0;"></i>Manajemen User
                    </div>
                    <div class="text-muted small">Kelola data pelanggan, toko, dan hak akses sistem</div>
                </div>
                <div>
                    <a href="{{ route('owner.user.create') }}"
                        class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Tambah User Baru
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #1565c0 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Total User</div>
                                <div class="h3 fw-bold mb-0">{{ $totalUser }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Terdaftar di sistem</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e3f2fd; width: 40px; height: 40px;">
                                <i class="bi bi-people fs-5" style="color: #1565c0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #2d6a4f !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">User Aktif</div>
                                <div class="h3 fw-bold mb-0 text-success">{{ $userAktif }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Bisa bertransaksi</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e8f5e9; width: 40px; height: 40px;">
                                <i class="bi bi-person-check fs-5" style="color: #2d6a4f;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #dc3545 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">User Nonaktif</div>
                                <div class="h3 fw-bold mb-0 text-danger">{{ $userNonaktif }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Akses diputus</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #ffebee; width: 40px; height: 40px;">
                                <i class="bi bi-person-x fs-5" style="color: #dc3545;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH --}}
        <form action="{{ route('owner.user.index') }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-4 d-flex flex-wrap gap-2 align-items-center border">

            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                    placeholder="Cari nama, email, toko…">
            </div>

            <div class="d-flex align-items-center gap-2">
                <select name="tipe" class="form-select form-select-sm" style="min-width: 140px;">
                    <option value="">Semua Tipe</option>
                    <option value="pelanggan" {{ request('tipe') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                    <option value="admin" {{ request('tipe') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>

                <select name="status" class="form-select form-select-sm" style="min-width: 140px;">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>

            <a href="{{ route('owner.user.index') }}"
                class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>

            <div class="ms-auto text-muted small">
                Menampilkan <b class="text-dark">{{ $users->firstItem() ?? 0 }}</b> sampai <b
                    class="text-dark">{{ $users->lastItem() ?? 0 }}</b> dari {{ $users->total() }} user
            </div>
        </form>

        {{-- TABLE DATA --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NAMA & TOKO</th>
                            <th class="py-3 border-0">KONTAK</th>
                            <th class="py-3 border-0">ALAMAT</th>
                            <th class="py-3 border-0">TIPE USER</th>
                            <th class="py-3 border-0 text-center">STATUS</th>
                            <th class="py-3 border-0 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="ps-3 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3"
                                            style="width: 38px; height: 38px; font-size: 14px;">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0" style="font-size: 13.5px;">
                                                {{ $user->nama }}</div>
                                            <div class="text-muted" style="font-size: 11px;">
                                                {{ $user->nama_toko ?? 'Personal/Internal' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark" style="font-size: 12.5px;">
                                        <i class="bi bi-envelope text-muted me-1"></i> {{ $user->email }}
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 11px;">
                                        <i class="bi bi-telephone text-muted me-1"></i> {{ $user->telepon ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-truncate text-muted" style="max-width: 200px; font-size: 12.5px;"
                                        title="{{ $user->alamat }}">
                                        {{ $user->alamat ?? '-' }}
                                    </div>
                                </td>
                                <td>
                                    @if ($user->status_mitra == '0')
                                        <span
                                            class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Reguler</span>
                                    @elseif ($user->status_mitra == '1')
                                        <span
                                            class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Mitra</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-dark bg-opacity-10 text-dark border border-dark border-opacity-25 px-2 py-1">Admin/Internal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($user->status == '1' )
                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><i
                                                class="bi bi-check-circle me-1"></i>Aktif</span>
                                    @elseif($user->status =='0')
                                        <span
                                            class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1"><i
                                                class="bi bi-x-circle me-1"></i>Nonaktif</span>
                                    @else
                                        <span
                                            class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i
                                                class="bi bi-dash-circle me-1"></i>Admin</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm gap-1">
                                        <a href="{{ route('owner.user.edit', [$user->id, 'type' => $user->tipe_user]) }}"
                                            class="btn btn-light text-primary border-0 rounded-2 p-1 px-2"
                                            title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        {{-- <form action="{{ route('owner.user.destroy', $user->id) }}" method="POST" class="d-inline m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light text-danger border-0 rounded-2 p-1 px-2"
                                                title="Hapus User" onclick="return confirm('Hapus user ini?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form> --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-2 opacity-50 d-block mb-2"></i>
                                    Belum ada data user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION YANG SUDAH DIPERBAIKI --}}
            <div
                class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">
                    Halaman {{ $users->currentPage() }} dari {{ $users->lastPage() }}
                </span>

                <div class="m-0">
                    @if ($users->hasPages())
                        {{ $users->links('pagination::bootstrap-5') }}
                    @else
                        <ul class="pagination mb-0">
                            <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
