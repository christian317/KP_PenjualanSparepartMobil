@extends('layouts.app')

@section('title', 'Kelola User')
@section('page', 'Kelola User')

@section('content')
    <div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-people me-2" style="color: #dc3545;"></i>Manajemen User
                    </div>
                    <div class="text-muted small">Kelola data pelanggan, toko, dan hak akses sistem</div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('keuangan.user.create') }}"
                        class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-person-plus me-1"></i> Tambah User Baru
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
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
                                <i class="bi bi-person fs-5" style="color: #1565c0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
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
            <div class="col-6 col-md-3">
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

        <form action="{{ route('keuangan.user.index') }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-3 d-flex flex-wrap gap-2 align-items-center">
            <div class="input-group input-group-sm " style="max-width: 300px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                    placeholder="Cari nama, email, toko…">
            </div>

            <select name="tipe" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Semua User</option>
                <option value="pelanggan" {{ request('tipe') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                <option value="admin" {{ request('tipe') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <a href="{{ route('keuangan.user.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-x"></i> Reset
            </a>
            <div class="ms-auto text-muted small">
                Menampilkan <b class="text-dark">{{ $users->firstItem() ?? 0 }}</b> - <b
                    class="text-dark">{{ $users->lastItem() ?? 0 }}</b> dari {{ $users->total() }} user
            </div>
        </form>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NAMA & TOKO</th>
                            <th class="py-3 border-0">KONTAK</th>
                            <th class="py-3 border-0">ALAMAT</th>
                            <th class="py-3 border-0">STATUS BENGKEL</th>
                            <th class="py-3 border-0">STATUS BENGKEL</th>
                            <th class="py-3 border-0 text-center">STATUS</th>
                            <th class="py-3 border-0 text-center">AKSI</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="ps-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center fw-bold me-3"
                                            style="width: 38px; height: 38px; font-size: 14px;">
                                            {{ substr($user->nama, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold mb-0" style="font-size: 13.5px;">{{ $user->nama }}</div>
                                            <div class="text-muted" style="font-size: 11px;">{{ $user->nama_toko }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div style="font-size: 13px;">
                                        <i class="bi bi-envelope me-1"></i> {{ $user->email }}
                                    </div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        <i class="bi bi-whatsapp me-1"></i> {{ $user->telepon }}
                                    </div>
                                </td>

                                <td>
                                    <div class="text-truncate text-muted" style="max-width: 200px; font-size: 12px;"
                                        title="{{ $user->alamat }}">
                                        {{ $user->alamat }}
                                    </div>
                                </td>

                                <td>
                                    <div class="text-truncate text-muted" style="max-width: 200px; font-size: 12px;"
                                        title="{{ $user->alamat }}">
                                        {{ $user->alamat }}
                                    </div>
                                </td>

                                <td>
                                    @if ($user->status_bengkel == '0')
                                        <span
                                            class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Reguler</span>
                                    @elseif ($user->status_bengkel == '1')
                                        <span
                                            class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Mitra</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($user->status == '1')
                                        <span
                                            class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25">Aktif</span>
                                    @elseif ($user->status == '0')
                                        <span
                                            class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25">Nonaktif</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group btn-group-sm gap-1">
                                        <a href="{{ route('keuangan.user.edit', $user->id) }}"
                                            class="btn btn-light text-primary border-0 rounded-2 p-1 px-2"
                                            title="Edit User">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button class="btn btn-light text-danger border-0 rounded-2 p-1 px-2"
                                            title="Hapus User" onclick="return confirm('Hapus user ini?')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div
                class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">Halaman {{ $users->currentPage() }} dari
                    {{ $users->lastPage() }}</span>
                <nav>
                    {{ $users->links('pagination::bootstrap-4') }}
                </nav>
            </div>
        </div>
    </div>
@endsection
