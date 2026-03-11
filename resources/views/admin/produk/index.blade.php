@extends('layouts.app')

@section('title','Kelola Produk')
@section('page','Kelola Produk')

@section('content')
<div class=" p-4" style="background-color: #f8f9fa; min-height: 100vh;">

    <div class="sticky-top py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="h4 fw-bold mb-1 d-flex align-items-center">
                    <i class="bi bi-box-seam me-2" style="color: #dc3545;"></i>Produk & Stok
                </div>
                <div class="text-muted small">Kelola katalog produk dan stok sparepart</div>
            </div>
            <a href="{{ route('admin.produk.create') }}" class="btn btn-danger px-4 py-2 fw-semibold rounded-3 shadow-sm" >
                <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color: #1565c0 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Total Produk</div>
                            <div class="h3 fw-bold mb-0">47</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">15 kategori</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e3f2fd; width: 40px; height: 40px;">
                            <i class="bi bi-box-seam fs-5" style="color: #1565c0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color: #2d6a4f !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Produk Aktif</div>
                            <div class="h3 fw-bold mb-0">44</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">Tampil di katalog</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e8f5e9; width: 40px; height: 40px;">
                            <i class="bi bi-check-circle fs-5" style="color: #2d6a4f;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color: #f57c00 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Stok Menipis</div>
                            <div class="h3 fw-bold mb-0 text-warning">6</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">Stok &lt; 10</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #fff3e0; width: 40px; height: 40px;">
                            <i class="bi bi-exclamation-triangle fs-5" style="color: #f57c00;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color: #dc3545 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Stok Habis</div>
                            <div class="h3 fw-bold mb-0 text-danger">2</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">Perlu restock</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #ffebee; width: 40px; height: 40px;">
                            <i class="bi bi-x-circle fs-5" style="color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-3 rounded-3 shadow-sm mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="input-group input-group-sm " style="max-width: 300px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input class="form-control bg-light border-start-0" placeholder="Cari produk, SKU, merek…">
        </div>
        <select class="form-select form-select-sm w-auto">
            <option>Semua Kategori</option>
            <option>Mesin</option>
            <option>Rem & Kopling</option>
        </select>
        <select class="form-select form-select-sm w-auto">
            <option>Semua Status</option>
            <option>Aktif</option>
            <option>Nonaktif</option>
        </select>
        <select class="form-select form-select-sm w-auto">
            <option>Semua Stok</option>
            <option>Stok OK</option>
            <option>Stok Menipis</option>
        </select>
        <button class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1"><i class="bi bi-x"></i> Reset</button>
        <div class="ms-auto text-muted small">Menampilkan <b class="text-dark">15</b> dari 47 produk</div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small">
                        <th class="ps-3 py-3 border-0">FOTO</th>
                        <th class="py-3 border-0">NAMA PRODUK</th>
                        <th class="py-3 border-0">SKU</th>
                        <th class="py-3 border-0">KATEGORI</th>
                        <th class="py-3 border-0 text-end">HARGA</th>
                        <th class="py-3 border-0 text-center">STOK</th>
                        <th class="py-3 border-0 text-center">STATUS</th>
                        <th class="py-3 border-0 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="ps-3">
                            <div class="bg-light rounded-2 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; font-size: 20px;">⚙️</div>
                        </td>
                        <td>
                            <div class="fw-bold mb-0" style="font-size: 13.5px;">Busi Iridium NGK BKR6EIX</div>
                            <div class="text-muted" style="font-size: 11px;">NGK</div>
                        </td>
                        <td><code class="text-secondary fw-bold" style="font-size: 11px;">NGK-IRI-001</code></td>
                        <td><span class="badge bg-light text-secondary border border-secondary-subtle fw-normal">Mesin</span></td>
                        <td class="text-end fw-bold text-danger" style="font-size: 13.5px;">Rp 85.000</td>
                        <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-semibold">150 pcs</span></td>
                        <td class="text-center"><span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Aktif</span></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm gap-1">
                                <button class="btn btn-light text-primary border-0 rounded-2 p-1 px-2" title="Edit"><i class="bi bi-pencil"></i></button>
                                <button class="btn btn-light text-warning border-0 rounded-2 p-1 px-2" title="Kelola Stok"><i class="bi bi-archive"></i></button>
                                <button class="btn btn-light text-danger border-0 rounded-2 p-1 px-2" title="Hapus"><i class="bi bi-trash3"></i></button>
                            </div>
                        </td>
                    </tr>

                    <tr class="bg-warning bg-opacity-10" style="background-color: #fffbf5 !important;">
                        <td class="ps-3">
                            <div class="bg-light rounded-2 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; font-size: 20px;">🔩</div>
                        </td>
                        <td>
                            <div class="fw-bold mb-0" style="font-size: 13.5px;">Gasket Head Cylinder Fel-Pro</div>
                            <div class="text-muted" style="font-size: 11px;">Fel-Pro</div>
                        </td>
                        <td><code class="text-secondary fw-bold" style="font-size: 11px;">GSK-HC-001</code></td>
                        <td><span class="badge bg-light text-secondary border border-secondary-subtle fw-normal">Mesin</span></td>
                        <td class="text-end fw-bold text-danger" style="font-size: 13.5px;">Rp 450.000</td>
                        <td class="text-center"><span class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning border-opacity-50 px-2 py-1 fw-bold">3 pcs ⚠️</span></td>
                        <td class="text-center"><span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Aktif</span></td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm gap-1">
                                <button class="btn btn-primary btn-sm px-2"><i class="bi bi-plus-circle me-1"></i>Stok</button>
                                <button class="btn btn-light text-danger border-0 rounded-2 p-1 px-2"><i class="bi bi-trash3"></i></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size: 12px;">Halaman 1 dari 4 · 47 produk total</span>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link border-0 bg-light rounded-start-pill px-3" href="#">«</a></li>
                    <li class="page-item active"><a class="page-link border-0 px-3" href="#">1</a></li>
                    <li class="page-item"><a class="page-link border-0 px-3" href="#">2</a></li>
                    <li class="page-item"><a class="page-link border-0 bg-light rounded-end-pill px-3" href="#">»</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection