@extends('layouts.app')


@section('content')
    <div class="flex-grow-1 p-4" style="background-color: #f8f9fa; min-height: 100vh;">
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-box-seam me-2" style="color: #dc3545;"></i>Dashboard
                    </div>
                    <div class="text-muted small">selamat datang </div>
                </div>
                <div class="d-none d-md-flex flex-column align-items-end me-2">
                    <div class="fw-bold small">{{ date('l, d M Y') }}</div>
                    <div class="text-muted" style="font-size: 11px;">
                        <i class="bi bi-circle-fill text-success me-1" style="font-size: 7px;"></i>Sistem Aktif
                    </div>
                </div>
            </div>
        </div>


        <div id="page-gudang" class="page">
            <div class="admin-layout">
                <div class="row g-3 mb-4">

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-warning border-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted fw-semibold mb-1" style="font-size:11px;">PERLU DIPROSES
                                        </div>
                                        <div class="fw-bold fs-2 text-dark">8</div>
                                        <div class="text-muted small">Pesanan masuk</div>
                                    </div>
                                    <div class="bg-warning bg-opacity-25 rounded-3 p-2">
                                        <i class="bi bi-hourglass-split text-warning fs-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-info border-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted fw-semibold mb-1" style="font-size:11px;">SEDANG DIKIRIM
                                        </div>
                                        <div class="fw-bold fs-2 text-dark">4</div>
                                        <div class="text-muted small">Dalam pengiriman</div>
                                    </div>
                                    <div class="bg-info bg-opacity-25 rounded-3 p-2">
                                        <i class="bi bi-truck text-info fs-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-success border-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted fw-semibold mb-1" style="font-size:11px;">SELESAI HARI INI
                                        </div>
                                        <div class="fw-bold fs-2 text-dark">12</div>
                                        <div class="text-muted small">Pesanan selesai</div>
                                    </div>
                                    <div class="bg-success bg-opacity-25 rounded-3 p-2">
                                        <i class="bi bi-bag-check text-success fs-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-danger border-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-muted fw-semibold mb-1" style="font-size:11px;">STOK MENIPIS</div>
                                        <div class="fw-bold fs-2 text-danger">3</div>
                                        <div class="text-muted small">Produk &lt; 10 stok</div>
                                    </div>
                                    <div class="bg-danger bg-opacity-25 rounded-3 p-2">
                                        <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Orders Table -->
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <span class="fw-semibold">
                            <i class="bi bi-list-ul me-2 text-danger"></i>Pesanan Aktif
                        </span>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" style="width:160px;">
                                <option>Semua Status</option>
                                <option>Dikonfirmasi</option>
                                <option>Dipersiapkan</option>
                                <option>Sedang Dikirim</option>
                            </select>
                            <input type="date" class="form-control form-control-sm" style="width:145px;"
                                value="2024-01-12">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold small">No. Pesanan</th>
                                    <th class="text-muted fw-semibold small">Pelanggan</th>
                                    <th class="text-muted fw-semibold small">Item</th>
                                    <th class="text-muted fw-semibold small">Total</th>
                                    <th class="text-muted fw-semibold small">Bayar</th>
                                    <th class="text-muted fw-semibold small">Status</th>
                                    <th class="text-muted fw-semibold small">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small">SPK20240112001</div>
                                        <div class="text-muted" style="font-size:11px;">12/01/24 09:15</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">Budi Santoso</div>
                                        <div class="text-muted" style="font-size:11px;">Bengkel Maju Jaya</div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">3 item</span></td>
                                    <td class="fw-bold text-danger small">Rp 1.295.000</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">Cash</span></td>
                                    <td><span class="badge bg-primary bg-opacity-25 text-primary">Dikonfirmasi</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light border"
                                                onclick="showToast('Detail pesanan dibuka.')">
                                                <i class="bi bi-eye text-primary"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light border">
                                                <i class="bi bi-printer text-warning"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning btn-sm text-white fw-semibold"
                                                onclick="showToast('Status diubah: Dipersiapkan ✓')"
                                                style="font-size:12px;">
                                                Proses
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small">SPK20240112002</div>
                                        <div class="text-muted" style="font-size:11px;">12/01/24 10:30</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">Andi Wijaya</div>
                                        <div class="text-muted" style="font-size:11px;">Bengkel Andi Motor</div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">1 item</span></td>
                                    <td class="fw-bold text-danger small">Rp 850.000</td>
                                    <td><span class="badge bg-primary bg-opacity-25 text-primary">Kontrabón</span></td>
                                    <td><span class="badge bg-warning bg-opacity-25 text-warning">Dipersiapkan</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light border"><i
                                                    class="bi bi-eye text-primary"></i></button>
                                            <button class="btn btn-sm btn-light border"><i
                                                    class="bi bi-printer text-warning"></i></button>
                                            <button class="btn btn-sm btn-primary fw-semibold"
                                                onclick="showToast('Status diubah: Sedang Dikirim 🚚')"
                                                style="font-size:12px;">
                                                Kirim
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small">SPK20240112003</div>
                                        <div class="text-muted" style="font-size:11px;">12/01/24 11:00</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">Siti Rahma</div>
                                        <div class="text-muted" style="font-size:11px;">Bengkel Siti Auto</div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">5 item</span></td>
                                    <td class="fw-bold text-danger small">Rp 3.450.000</td>
                                    <td><span class="badge bg-success bg-opacity-25 text-success">Cash</span></td>
                                    <td><span class="badge bg-info bg-opacity-25 text-info">Dikirim</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light border"><i
                                                    class="bi bi-eye text-primary"></i></button>
                                            <button class="btn btn-sm btn-light border"><i
                                                    class="bi bi-printer text-warning"></i></button>
                                            <button class="btn btn-sm btn-success fw-semibold" disabled
                                                style="font-size:12px;">Selesai</button>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small">SPK20240111045</div>
                                        <div class="text-muted" style="font-size:11px;">11/01/24 14:20</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">Hendra Kusuma</div>
                                        <div class="text-muted" style="font-size:11px;">Bengkel HK Jaya</div>
                                    </td>
                                    <td><span class="badge bg-light text-secondary border">2 item</span></td>
                                    <td class="fw-bold text-danger small">Rp 725.000</td>
                                    <td><span class="badge bg-primary bg-opacity-25 text-primary">Kontrabón</span></td>
                                    <td><span class="badge bg-danger bg-opacity-25 text-danger">Refund</span></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-light border"><i
                                                    class="bi bi-eye text-primary"></i></button>
                                            <button class="btn btn-sm btn-danger fw-semibold"
                                                onclick="showToast('Detail refund dibuka.')"
                                                style="font-size:12px;">Review</button>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-white d-flex justify-content-between align-items-center py-2">
                        <small class="text-muted">Menampilkan 4 dari 24 pesanan</small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled"><a class="page-link">«</a></li>
                                <li class="page-item active"><a class="page-link bg-danger border-danger">1</a></li>
                                <li class="page-item"><a class="page-link">2</a></li>
                                <li class="page-item"><a class="page-link">3</a></li>
                                <li class="page-item"><a class="page-link">»</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Low Stock Warning -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div
                        class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center py-3">
                        <span class="fw-semibold">
                            <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Peringatan Stok Menipis
                        </span>
                        <button class="btn btn-danger btn-sm">Kelola Produk</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold small">Produk</th>
                                    <th class="text-muted fw-semibold small">SKU</th>
                                    <th class="text-muted fw-semibold small">Kategori</th>
                                    <th class="text-muted fw-semibold small">Stok Tersisa</th>
                                    <th class="text-muted fw-semibold small">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 fw-semibold small">Gasket Head Cylinder</td>
                                    <td class="text-muted small">GSK-HC-001</td>
                                    <td><span class="badge bg-light text-secondary border">Mesin</span></td>
                                    <td><span class="badge bg-danger">3 pcs</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success btn-sm fw-semibold"
                                            onclick="showToast('Formulir update stok dibuka.')" style="font-size:12px;">
                                            Update Stok
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 fw-semibold small">Piston Set Honda Jazz</td>
                                    <td class="text-muted small">HND-PST-001</td>
                                    <td><span class="badge bg-light text-secondary border">Mesin</span></td>
                                    <td><span class="badge bg-danger">0 set</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success fw-semibold"
                                            onclick="showToast('Formulir update stok dibuka.')" style="font-size:12px;">
                                            Update Stok
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-3 fw-semibold small">Alternator Toyota Avanza</td>
                                    <td class="text-muted small">ALT-AVZ-001</td>
                                    <td><span class="badge bg-light text-secondary border">Kelistrikan</span></td>
                                    <td><span class="badge bg-warning text-dark">7 pcs</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success fw-semibold"
                                            onclick="showToast('Formulir update stok dibuka.')" style="font-size:12px;">
                                            Update Stok
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div><!-- end main content -->
        </div><!-- end d-flex -->
    </div>
@endsection
