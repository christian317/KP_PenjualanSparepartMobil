@extends('layouts.app')

@section('title', 'Dashboard Admin Gudang')

@section('content')
    <div class="flex-grow-1 p-4" style="background-color: #f8f9fa; min-height: 100vh;">
        
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-box-seam me-2" style="color: #dc3545;"></i>Dashboard Gudang
                    </div>
                    <div class="text-muted small">Selamat datang! Kelola pesanan masuk dan pantau stok barang.</div>
                </div>
                <div class="d-none d-md-flex flex-column align-items-end me-2">
                    <div class="fw-bold small text-dark">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                    <div class="text-muted d-flex align-items-center" style="font-size: 11px;">
                        <span class="spinner-grow spinner-grow-sm text-success me-2" style="width: 8px; height: 8px;" role="status"></span>
                        Sistem Aktif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-warning border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">PERLU DIPROSES</div>
                                <div class="fw-bold fs-3 text-dark">{{ $statDiproses }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Pesanan masuk</div>
                            </div>
                            <div class="bg-warning bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-hourglass-split text-warning fs-5"></i>
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
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">SEDANG DIKIRIM</div>
                                <div class="fw-bold fs-3 text-dark">{{ $statDikirim }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Dalam pengiriman</div>
                            </div>
                            <div class="bg-info bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-truck text-info fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-success border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">SELESAI BULAN INI</div>
                                <div class="fw-bold fs-3 text-dark">{{ $statSelesai }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Pesanan selesai</div>
                            </div>
                            <div class="bg-success bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-bag-check text-success fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-3 h-100 border-start border-danger border-4">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted fw-semibold mb-1" style="font-size:11px;">STOK MENIPIS</div>
                                <div class="fw-bold fs-3 text-danger">{{ $statStokKritis }}</div>
                                <div class="text-muted small" style="font-size: 11px;">Batas minimum</div>
                            </div>
                            <div class="bg-danger bg-opacity-25 rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-exclamation-triangle text-danger fs-5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0 pt-4 px-4">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-list-ul me-2 text-danger"></i>Pesanan Aktif
                        </span>
                        <a href="{{ route('admin.pesanan.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="font-size: 12px;">Lihat Semua</a>
                    </div>

                    <div class="table-responsive px-2">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold small" style="font-size: 11px;">NO. PESANAN</th>
                                    <th class="text-muted fw-semibold small" style="font-size: 11px;">PELANGGAN</th>
                                    <th class="text-muted fw-semibold small text-center" style="font-size: 11px;">STATUS</th>
                                    <th class="text-muted fw-semibold small text-center" style="font-size: 11px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesananAktif as $psn)
                                    @if ($psn->status == 0)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold small text-dark">{{ $psn->nomor }}</div>
                                                <div class="text-muted" style="font-size:10px;">{{ \Carbon\Carbon::parse($psn->tanggal)->format('d/m/y H:i') }}</div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold small text-dark">{{ $psn->UserPelanggan->nama ?? '-' }}</div>
                                                <div class="text-muted" style="font-size:10px;">{{ $psn->items->count() }} item</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning bg-opacity-25 text-warning text-dark">Diproses</span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.pesanan.index', $psn->nomor) }}" class="btn btn-sm btn-primary fw-semibold px-3 py-1 shadow-sm">view</a>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-clipboard-check fs-3 text-success opacity-50 d-block mb-2"></i>
                                            <span class="small">Tidak ada pesanan aktif saat ini.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($pesananAktif->total() > 5)
                        <div class="card-footer bg-white py-2 border-top border-light d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">Halaman {{ $pesananAktif->currentPage() }} dari {{ $pesananAktif->lastPage() }}</span>
                            <div class="m-0" style="transform: scale(0.85); transform-origin: right center;">
                                {{ $pesananAktif->appends(request()->except('pesanan_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center py-3 border-bottom-0 pt-4 px-4">
                        <span class="fw-bold text-dark fs-5">
                            <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>Peringatan Stok
                        </span>
                        <a href="{{ route('admin.produk.index') }}" class="btn btn-sm btn-light border text-primary fw-semibold" style="font-size: 12px;">Kelola Produk</a>
                    </div>
                    
                    <div class="table-responsive px-2">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3 text-muted fw-semibold small" style="font-size: 11px;">PRODUK & SKU</th>  
                                    <th class="text-center text-muted fw-semibold small" style="font-size: 11px;">STOK MINIMUM</th>
                                    <th class="text-center text-muted fw-semibold small" style="font-size: 11px;">STOK</th>
                                    <th class="text-center text-muted fw-semibold small" style="font-size: 11px;">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($produkKritis as $pk)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-semibold small text-dark">{{ $pk->nama }}</div>
                                            <div class="text-muted" style="font-size:10px;">{{ $pk->id }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary text-dark">{{ $pk->min_stok }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($pk->stok == 0)
                                                <span class="badge bg-danger">Habis</span>
                                            @else
                                                <span class="badge bg-warning text-dark border border-warning">{{ $pk->stok }} pcs</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.pembelian.create', $pk->id) }}" class="btn btn-sm btn-primary fw-semibold px-3 py-1 shadow-sm" style="font-size:11px;">Update</a>
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

                    @if($produkKritis->total() > 5)
                        <div class="card-footer bg-white py-2 border-top border-light d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 11px;">Halaman {{ $produkKritis->currentPage() }} dari {{ $produkKritis->lastPage() }}</span>
                            <div class="m-0" style="transform: scale(0.85); transform-origin: right center;">
                                {{ $produkKritis->appends(request()->except('stok_page'))->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        @if (Session::has('toast_success'))
            document.addEventListener("DOMContentLoaded", function() {
                showToast("{{ Session::get('toast_success') }}");
            });
        @endif
    </script>
@endsection