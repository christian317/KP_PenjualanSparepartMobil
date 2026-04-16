@extends('layouts.app')

@section('content')
    <div class="col px-4 pt-4 pb-5 bg-light min-vh-100">

        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.produk.index') }}"
                    class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Tambah Jenis Mobil Baru</h4>
                    <p class="text-muted mb-0 small">Master data kendaraan untuk kecocokan sparepart</p>
                </div>
            </div>
        </div>
        
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-car-front me-2 text-danger"></i>Informasi Kendaraan
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.produk.jenis_mobil.store') }}">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Merek (Contoh: Honda)</label>
                                    <input name="merk_mobil" type="text" class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Model (Contoh: Brio RS)</label>
                                    <input name="nama_mobil" type="text" class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Tahun Kendaraan (Opsional)</label>
                                    <input name="tahun_kendaraan" type="text" class="form-control rounded-3 py-2" placeholder="Contoh: 2018-2023">
                                    <small class="text-muted" style="font-size: 11px;">Bisa dikosongkan jika sparepart cocok untuk semua tahun.</small>
                                </div>
                            </div>
                            <div class="d-grid gap-2" style="margin-top: 20px">
                                <button type="submit" class="btn py-2 fw-bold rounded-3 shadow-sm border-0 text-white"
                                    style="background-color: #3c3cff;">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Mobil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card border-0 shadow-sm rounded-3 mt-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="bi bi-list-ul me-2 text-danger"></i>Daftar Jenis Mobil</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-secondary small">
                                <tr>
                                    <th class="ps-3">MEREK</th>
                                    <th>NAMA MODEL</th>
                                    <th>TAHUN</th>
                                    <th class="text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @forelse ($jenis_mobil as $item)
                                    <tr>
                                        <td class="ps-3 fw-semibold text-dark">{{ $item->merk_mobil }}</td>
                                        <td class="text-muted">{{ $item->nama_mobil }}</td>
                                        <td class="text-muted">{{ $item->tahun_kendaraan ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="/admin/produk/jenis-mobil/edit/{{ $item->id }}" class="btn btn-sm btn-info bg-opacity-10 border-0 text-info px-2">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="/admin/produk/jenis-mobil/delete/{{ $item->id }}" class="btn btn-sm btn-danger bg-opacity-10 border-0 text-danger px-2" onclick="return confirm('Hapus data mobil ini?');">
                                                    <i class="bi bi-trash3"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data jenis mobil.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection