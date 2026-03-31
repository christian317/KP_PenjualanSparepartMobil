@extends('layouts.app')

@section('content')
<div class="col px-4 pt-4 pb-5 bg-light min-vh-100">

    <div class="sticky-top py-3 mb-4"
        style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.user.index') }}"
                class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Tambah User Baru</h4>
                <p class="text-muted mb-0 small">Daftarkan akun pelanggan atau admin baru ke sistem</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf

        {{-- PILIH TIPE USER --}}
        <div class="mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <label class="fw-semibold small text-secondary">Pilih Tipe User</label>
                    <select name="tipe_user" id="tipe_user" class="form-select rounded-3 mt-2">
                        <option value="pelanggan">Pelanggan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-person-circle me-2 text-danger"></i>Informasi Akun & Profil
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">

                            {{-- NAMA --}}
                            <div class="col-md-12   ">
                                <label class="form-label fw-semibold small text-secondary">Nama Lengkap</label>
                                <input name="nama" type="text" class="form-control rounded-3 py-2" required>
                            </div>

                            {{-- ROLE ADMIN --}}
                            <div class="col-md-5" id="role_admin">
                                <label class="form-label fw-semibold small text-secondary">Role / Akses</label>
                                <select name="role_id" class="form-select rounded-3 py-2">
                                    <option value="">-- Pilih Role --</option>
                                    <option value="1">Admin Gudang</option>
                                    <option value="2">Admin Keuangan</option>
                                </select>
                            </div>

                            {{-- NAMA TOKO --}}
                            <div class="col-md-12" id="field_toko">
                                <label class="form-label fw-semibold small text-secondary">Nama Toko / Bengkel</label>
                                <input name="nama_toko" type="text" class="form-control rounded-3 py-2">
                            </div>

                            {{-- EMAIL --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Alamat Email</label>
                                <input name="email" type="email" class="form-control rounded-3 py-2" required>
                            </div>

                            {{-- TELEPON --}}
                            <div class="col-md-6" id="field_telepon">
                                <label class="form-label fw-semibold small text-secondary">No. Telepon / WhatsApp</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted">+62</span>
                                    <input name="telepon" type="number" class="form-control rounded-end-3 py-2">
                                </div>
                            </div>

                            {{-- PASSWORD --}}
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small text-secondary">Password</label>
                                <input name="password" type="password" class="form-control rounded-3 py-2" required>
                                <div class="form-text small" style="font-size: 10px;">Minimal 8 karakter</div>
                            </div>

                            {{-- ALAMAT --}}
                            <div class="col-12" id="field_alamat">
                                <label class="form-label fw-semibold small text-secondary">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control rounded-3" rows="3"></textarea>
                            </div>

                            {{-- STATUS BENGKEL --}}
                            <div class="col-md-6" id="field_bengkel">
                                <label class="form-label fw-semibold small text-secondary">Status Bengkel</label>
                                <select name="status_bengkel" class="form-select rounded-3">
                                    <option value="0">Reguler</option>
                                    <option value="1">Mitra</option>
                                </select>
                            </div>

                            {{-- STATUS --}}
                            <div class="col-12 pt-2" id="field_status">
                                <input type="hidden" name="status" value="0">
                                <div class="form-check form-switch">
                                    <input name="status" class="form-check-input border-secondary"
                                        type="checkbox" value="1" checked style="transform: scale(1.2);">
                                    <label class="form-check-label ms-2 fw-medium">User Aktif (Dapat Login)</label>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 bg-danger text-white">
                    <div class="card-body p-4">
                        <h5 class="fw-bold"><i class="bi bi-lightbulb me-2"></i>Informasi</h5>
                        <p class="small mb-0 opacity-75">
                            Pastikan email yang didaftarkan aktif. Kata sandi akan digunakan pengguna untuk masuk ke sistem.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="col-md-8">
                    <div class="alert alert-danger border-0 shadow-sm rounded-3 p-3 mb-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- BUTTON --}}
            <div class="col-md-8">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger py-2 fw-bold rounded-3 shadow-sm border-0">
                        <i class="bi bi-person-check me-2"></i>Simpan User Baru
                    </button>
                    <a href="{{ route('admin.user.index') }}"
                        class="btn btn-light py-2 fw-semibold rounded-3 text-secondary border">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT --}}
<script>
    const tipeUser = document.getElementById('tipe_user');

    function toggleForm() {
        let isAdmin = tipeUser.value === 'admin';

        document.getElementById('field_toko').style.display = isAdmin ? 'none' : 'block';
        document.getElementById('field_telepon').style.display = isAdmin ? 'none' : 'block';
        document.getElementById('field_alamat').style.display = isAdmin ? 'none' : 'block';
        document.getElementById('field_bengkel').style.display = isAdmin ? 'none' : 'block';
        document.getElementById('field_status').style.display = isAdmin ? 'none' : 'block';

        document.getElementById('role_admin').style.display = isAdmin ? 'block' : 'none';
    }

    tipeUser.addEventListener('change', toggleForm);
    window.onload = toggleForm;
</script>
@endsection