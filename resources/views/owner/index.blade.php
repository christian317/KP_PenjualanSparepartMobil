@extends('layouts.app')

@section('title', 'Dashboard Owner')

@section('content')
    <div class="flex-grow-1 p-4" style="background-color: #f8f9fa; min-height: 100vh;">
        
        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-grid-1x2-fill me-2 text-primary"></i>Dashboard
                    </div>
                    <div class="text-muted small">Selamat datang kembali! Berikut adalah ringkasan operasional CV Jaya Abadi hari ini.</div>
                </div>
                <div class="d-none d-md-flex flex-column align-items-end me-2">
                    <div class="fw-bold small text-dark">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                    <div class="text-muted d-flex align-items-center" style="font-size: 11px;">
                        <span class="spinner-grow spinner-grow-sm text-success me-2" style="width: 8px; height: 8px;" role="status"></span>
                        Sistem Pemantauan Aktif
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK UTAMA --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-danger border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">PERLU APPROVAL</div>
                                <div class="fw-bold fs-3 text-dark">{{ $statApproval ?? 0 }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Kontrabon baru</div>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-shield-exclamation text-danger fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-warning border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">ANTREAN PO</div>
                                <div class="fw-bold fs-3 text-dark">{{ $statPO ?? 0 }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Menunggu barang</div>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-box-seam text-warning fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DIGANTI MENJADI ANTREAN REFUND --}}
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-secondary border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">ANTREAN REFUND</div>
                                <div class="fw-bold fs-3 text-dark">{{ $statRefund ?? 0 }}</div>
                                <div class="text-secondary small" style="font-size: 11px;">Pengembalian dana</div>
                            </div>
                            <div class="bg-secondary bg-opacity-10 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-arrow-return-left text-secondary fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-info border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">STOK KRITIS</div>
                                <div class="fw-bold fs-3 text-info">{{ $statStokKritis ?? 0 }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Butuh restock</div>
                            </div>
                            <div class="bg-info bg-opacity-10 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-exclamation-triangle text-info fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- KOLOM KIRI: TABEL APPROVAL, PO, DAN REFUND --}}
            <div class="col-lg-7">
                
                {{-- TABEL 1: PERLU APPROVAL (STATUS 5) --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 pt-4 px-4">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-shield-exclamation me-2 text-danger"></i>Perlu Approval Kontrabon
                        </span>
                        <a href="{{ route('owner.kontrabon.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="font-size: 12px;">Lihat Rekap</a>
                    </div>

                    <div class="table-responsive px-2">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold" style="font-size: 11px;">NO. PESANAN</th>
                                    <th class="text-muted fw-semibold" style="font-size: 11px;">PELANGGAN</th>
                                    <th class="text-muted fw-semibold text-center" style="font-size: 11px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesananApproval ?? [] as $pa)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold small text-dark">{{ $pa->nomor }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ \Carbon\Carbon::parse($pa->tanggal)->diffForHumans() }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-dark">{{ $pa->UserPelanggan->nama ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ $pa->UserPelanggan->nama_toko ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('owner.kontrabon.index') }}" class="btn btn-sm btn-primary fw-semibold px-3 py-1 shadow-sm" style="font-size:11px;">Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-check2-circle fs-3 text-success opacity-50 d-block mb-2"></i>
                                            <span class="small">Tidak ada antrean approval.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination Approval --}}
                    @if($pesananApproval->total() > 5)
                        <div class="card-footer bg-white py-2 border-top border-light d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">Halaman {{ $pesananApproval->currentPage() }} dari {{ $pesananApproval->lastPage() }}</span>
                            <div class="m-0" style="transform: scale(0.85); transform-origin: right center;">
                                {{ $pesananApproval->appends(request()->except('approval_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- TABEL 2: ANTREAN PO (STATUS 6) --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 pt-4 px-4">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-box-seam-fill me-2 text-warning"></i>Antrean Pre-Order
                        </span>
                        <a href="{{ route('owner.preorder.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="font-size: 12px;">Kelola PO</a>
                    </div>

                    <div class="table-responsive px-2">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold" style="font-size: 11px;">NO. PESANAN</th>
                                    <th class="text-muted fw-semibold" style="font-size: 11px;">PELANGGAN</th>
                                    <th class="text-muted fw-semibold text-center" style="font-size: 11px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesananPO ?? [] as $po)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold small text-dark">{{ $po->nomor }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ \Carbon\Carbon::parse($po->tanggal)->diffForHumans() }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-dark">{{ $po->UserPelanggan->nama ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ $po->UserPelanggan->nama_toko ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('owner.preorder.index') }}" class="btn btn-sm btn-primary text-white fw-semibold px-3 py-1 shadow-sm" style="font-size:11px;">Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-clipboard-check fs-3 text-success opacity-50 d-block mb-2"></i>
                                            <span class="small">Tidak ada pesanan tertunda karena PO.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination PO --}}
                    @if($pesananPO->total() > 5)
                        <div class="card-footer bg-white py-2 border-top border-light d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">Halaman {{ $pesananPO->currentPage() }} dari {{ $pesananPO->lastPage() }}</span>
                            <div class="m-0" style="transform: scale(0.85); transform-origin: right center;">
                                {{ $pesananPO->appends(request()->except('po_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- TABEL 3: ANTREAN REFUND DANA (STATUS 4) --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 pt-4 px-4">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-arrow-return-left me-2 text-secondary"></i>Antrean Refund Dana
                        </span>
                        <a href="{{ route('owner.transaksi.index', ['tab' => '0']) }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="font-size: 12px;">Lihat Transaksi</a>
                    </div>

                    <div class="table-responsive px-2">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold" style="font-size: 11px;">NO. PESANAN</th>
                                    <th class="text-muted fw-semibold" style="font-size: 11px;">PELANGGAN</th>
                                    <th class="text-muted fw-semibold text-center" style="font-size: 11px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesananRefund ?? [] as $pr)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold small text-dark">{{ $pr->nomor }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ \Carbon\Carbon::parse($pr->tanggal)->diffForHumans() }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small text-dark">{{ $pr->UserPelanggan->nama ?? '-' }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ $pr->UserPelanggan->nama_toko ?? '-' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('owner.riwayat_pesanan.index', ['status' => '4']) }}" class="btn btn-sm btn-primary text-white fw-semibold px-3 py-1 shadow-sm" style="font-size:11px;">Review</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-check2-circle fs-3 text-success opacity-50 d-block mb-2"></i>
                                            <span class="small">Tidak ada antrean pengembalian dana.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination Refund --}}
                    @if($pesananRefund->total() > 5)
                        <div class="card-footer bg-white py-2 border-top border-light d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">Halaman {{ $pesananRefund->currentPage() }} dari {{ $pesananRefund->lastPage() }}</span>
                            <div class="m-0" style="transform: scale(0.85); transform-origin: right center;">
                                {{ $pesananRefund->appends(request()->except('refund_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- KOLOM KANAN: PERINGATAN STOK MENIPIS --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 pt-4 px-4">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-exclamation-triangle-fill me-2 text-info"></i>Peringatan Stok
                        </span>
                        <a href="{{ route('owner.monitoring_produk.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="font-size: 12px;">Katalog</a>
                    </div>
                    
                    <div class="table-responsive px-2">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold" style="font-size: 11px;">PRODUK & SKU</th>
                                    <th class="text-center text-muted fw-semibold" style="font-size: 11px;">SISA</th>
                                    <th class="text-center text-muted fw-semibold" style="font-size: 11px;">STATUS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produkKritis ?? [] as $pk)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold small text-dark">{{ $pk->nama }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ $pk->id }}</div>
                                        </td>
                                        <td class="text-center">
                                            <div class="fw-bold fs-6 {{ $pk->stok == 0 ? 'text-danger' : 'text-warning' }}">{{ $pk->stok }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if ($pk->stok == 0)
                                                <span class="badge bg-danger">Habis</span>
                                            @else
                                                <span class="badge bg-warning text-dark border border-warning">Menipis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">
                                            <i class="bi bi-box-seam fs-3 opacity-50 d-block mb-2"></i>
                                            <span class="small">Kondisi stok di gudang aman.</span>
                                        </td>
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