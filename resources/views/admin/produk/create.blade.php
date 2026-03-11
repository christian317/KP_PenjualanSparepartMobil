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
                    <h4 class="fw-bold mb-0 text-dark">Tambah Produk Baru</h4>
                    <p class="text-muted mb-0 small">Isi data produk sparepart dengan lengkap</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-info-circle me-2 text-danger"></i>Informasi Produk
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold small text-secondary">Nama Produk *</label>
                                <input class="form-control rounded-3 py-2" placeholder="Contoh: Busi Iridium NGK BKR6EIX"
                                    value="Timing Belt Toyota Innova">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">SKU *</label>
                                <input class="form-control rounded-3 py-2" placeholder="Contoh: NGK-IRI-001"
                                    value="TYT-TB-002">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Kategori *</label>
                                <select class="form-select rounded-3 py-2">
                                    <option>-- Pilih Kategori --</option>
                                    <option selected>Mesin</option>
                                    <option>Rem & Kopling</option>
                                    <option>Suspensi</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Merek / Brand</label>
                                <input class="form-control rounded-3 py-2" placeholder="Contoh: NGK, Brembo"
                                    value="Toyota Genuine">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Harga Jual (Rp) *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">Rp</span>
                                    <input class="form-control rounded-end-3 py-2" type="number" value="320000">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Stok Awal *</label>
                                <div class="input-group">
                                    <input class="form-control py-2" type="number" value="45">
                                    <span class="input-group-text bg-light rounded-end-3">unit</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">Satuan *</label>
                                <select class="form-select rounded-3 py-2">
                                    <option selected>set</option>
                                    <option>pcs</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-secondary">Deskripsi Produk</label>
                                <textarea class="form-control rounded-3" rows="3">Timing belt original Toyota untuk berbagai tipe Innova dan Avanza. Garansi keaslian produk.</textarea>
                            </div>
                            <div class="col-12 pt-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input border-secondary" type="checkbox" checked
                                        style="transform: scale(1.2);">
                                    <label class="form-check-label ms-2 fw-medium">Produk Aktif (tampil di katalog)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 mb-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-image me-2 text-danger"></i>Foto Produk</div>
                    </div>
                    <div class="card-body">
                        <div class="border-2 border-dashed rounded-3 p-4 text-center bg-light cursor-pointer"
                            style="border-style: dashed !important;">
                            <i class="bi bi-cloud-upload text-muted display-6"></i>
                            <div class="fw-bold small text-dark mt-2">Klik untuk upload foto</div>
                            <div class="text-muted" style="font-size: 11px;">PNG, JPG, WEBP · Maks 2MB</div>
                        </div>
                        <div class="mt-3 p-2 rounded-3 d-flex align-items-center gap-2 bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                            style="font-size:12px;">
                            <i class="bi bi-check-circle-fill"></i> timing-belt-toyota.jpg
                            <button class="btn btn-sm p-0 border-0 ms-auto text-danger"><i class="bi bi-x-lg"></i></button>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-eye me-2 text-danger"></i>Preview</div>
                    </div>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                        <i class="bi bi-gear-wide-connected text-secondary opacity-25" style="font-size: 50px;"></i>
                    </div>
                    <div class="card-body p-3">
                        <div class="badge bg-light text-secondary border mb-1" style="font-size: 10px;">MESIN</div>
                        <div class="fw-bold text-dark mb-1">Timing Belt Toyota Innova</div>
                        <div class="text-muted mb-2" style="font-size: 11px;">Toyota Genuine · TYT-TB-002</div>
                        <div class="fw-bold text-danger">Rp 320.000 <small class="text-muted fw-normal">/set</small></div>
                        <div class="mt-2 text-muted small border-top pt-2"><i class="bi bi-box2 me-1"></i>Stok: 45 set
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-danger py-2 fw-bold rounded-3 shadow-sm border-0"
                        style="background-color: #dc3545;">
                        <i class="bi bi-check-circle me-2"></i>Simpan Produk
                    </button>
                    <button onclick="showPage('gudang')"
                        class="btn btn-light py-2 fw-semibold rounded-3 text-secondary border">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3 mt-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="bi bi-box-seam me-2 text-danger"></i>Daftar Produk</span>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i
                                class="bi bi-search"></i></span>
                        <input class="form-control border-start-0 ps-0" placeholder="Cari produk...">
                    </div>
                    <select class="form-select form-select-sm" style="width: 140px;">
                        <option>Semua Kategori</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-secondary small">
                        <tr>
                            <th class="ps-3">FOTO</th>
                            <th>NAMA PRODUK</th>
                            <th>SKU</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th>STOK</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <tr>
                            <td class="ps-3">
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border"
                                    style="width: 44px; height: 44px; font-size: 20px;">⚙️</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">Busi Iridium NGK</div>
                                <div class="text-muted" style="font-size: 11px;">NGK</div>
                            </td>
                            <td class="text-muted">NGK-IRI-001</td>
                            <td><span class="badge bg-light text-dark border fw-normal">Mesin</span></td>
                            <td class="fw-bold text-danger">Rp 85.000</td>
                            <td><span class="badge bg-success bg-opacity-75">150 pcs</span></td>
                            <td><span
                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 fw-normal">Aktif</span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-info bg-opacity-10 border-0 text-info px-2"><i
                                            class="bi bi-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger bg-opacity-10 border-0 text-danger px-2"><i
                                            class="bi bi-trash3"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
