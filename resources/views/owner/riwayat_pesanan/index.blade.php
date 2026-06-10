@extends('layouts.app')

@section('title', 'Riwayat Pesanan')

@section('content')
    <div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Riwayat Pesanan
                    </div>
                    <div class="text-muted small">
                        Pantau seluruh riwayat transaksi pelanggan dari awal hingga akhir.
                    </div>
                </div>
            </div>
        </div>

        {{-- STAT CARD --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color:#1565c0!important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Total Transaksi</div>
                                <div class="h3 fw-bold text-primary mb-0">{{ $statTotal }}</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e3f2fd; width: 40px; height: 40px;">
                                <i class="bi bi-receipt fs-5" style="color: #1565c0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color:#198754!important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Selesai</div>
                                <div class="h3 fw-bold text-success mb-0">{{ $statSelesai }}</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e8f5e9; width: 40px; height: 40px;">
                                <i class="bi bi-check2-circle fs-5" style="color: #198754;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color:#f57f17!important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Sedang Aktif</div>
                                <div class="h3 fw-bold text-warning mb-0">{{ $statAktif }}</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #fff3e0; width: 40px; height: 40px;">
                                <i class="bi bi-hourglass-split fs-5" style="color: #f57f17;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color:#dc3545!important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Dibatalkan</div>
                                <div class="h3 fw-bold text-danger mb-0">{{ $statBatal }}</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #ffebee; width: 40px; height: 40px;">
                                <i class="bi bi-x-octagon fs-5" style="color: #dc3545;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER PENCARIAN --}}
        <form action="{{ url()->current() }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-4 mt-2 d-flex flex-wrap gap-2 align-items-center border">

            <input type="hidden" name="status" value="{{ request('status') }}">

            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                    placeholder="Cari No Pesanan / Pelanggan...">
            </div>

            <input type="date" name="tanggal" class="form-control form-control-sm w-auto text-muted"
                value="{{ request('tanggal') }}" title="Pilih Tanggal">
            
            <div class="d-flex align-items-center gap-2">
                <select name="bulan" class="form-select form-select-sm" style="max-width: 120px;">
                    <option value="">Semua Bulan</option>
                    @php
                        $bulanSekarang = request('bulan');
                        $namaBulan = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                        ];
                    @endphp
                    @foreach ($namaBulan as $val => $name)
                        <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="form-select form-select-sm" style="max-width: 120px;">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>

                <select name="metode" class="form-select form-select-sm" style="max-width: 140px;">
                    <option value="">Semua Metode</option>
                    <option value="0" {{ request('metode') === '0' ? 'selected' : '' }}>Cash</option>
                    <option value="1" {{ request('metode') == '1' ? 'selected' : '' }}>Kontrabon</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>

            <a href="{{ url()->current() }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>

            <div class="ms-auto text-muted small">
                Menampilkan <b class="text-dark">{{ $pesanan->firstItem() ?? 0 }}</b> sampai <b class="text-dark">{{ $pesanan->lastItem() ?? 0 }}</b> dari {{ $pesanan->total() }} data
            </div>
        </form>

        {{-- ======================================================== --}}
        {{-- NAVIGASI TABS STATUS PESANAN (TABS 4 & 5 DIHAPUS) --}}
        {{-- ======================================================== --}}
        <ul class="nav nav-pills mb-3 bg-white p-2 rounded-3 shadow-sm border overflow-auto flex-nowrap"
            style="white-space: nowrap;">
            <li class="nav-item">
                <a class="nav-link {{ request('status') === null || request('status') === '' ? 'active fw-bold' : 'text-muted' }}"
                    href="{{ request()->fullUrlWithQuery(['status' => '', 'page' => 1]) }}">
                    <i class="bi bi-grid-fill me-1"></i> Semua Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === '0' ? 'active fw-bold' : 'text-muted' }}"
                    href="{{ request()->fullUrlWithQuery(['status' => '0', 'page' => 1]) }}">
                    <i class="bi bi-box-seam me-1"></i> Diproses Gudang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === '1' ? 'active fw-bold' : 'text-muted' }}"
                    href="{{ request()->fullUrlWithQuery(['status' => '1', 'page' => 1]) }}">
                    <i class="bi bi-truck me-1"></i> Dikirim
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === '2' ? 'active fw-bold' : 'text-muted' }}"
                    href="{{ request()->fullUrlWithQuery(['status' => '2', 'page' => 1]) }}">
                    <i class="bi bi-check2-circle me-1"></i> Selesai
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === '3' ? 'active fw-bold' : 'text-muted' }}"
                    href="{{ request()->fullUrlWithQuery(['status' => '3', 'page' => 1]) }}">
                    <i class="bi bi-x-octagon me-1"></i> Dibatalkan
                </a>
            </li>
        </ul>

        {{-- ======================================================== --}}
        {{-- DAFTAR TABEL PESANAN --}}
        {{-- ======================================================== --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NOMOR PESANAN</th>
                            <th class="py-3 border-0">PELANGGAN</th>
                            <th class="py-3 border-0 text-center">STATUS PESANAN</th>
                            <th class="py-3 border-0 text-center">METODE PEMBAYARAN</th>
                            <th class="py-3 border-0 text-center" style="width: 150px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold small text-dark">{{ $p->nomor }}</div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }} WIB
                                    </div>
                                </td>

                                <td>
                                    <div class="fw-semibold small">
                                        {{ $p->UserPelanggan ? $p->UserPelanggan->nama : 'User Terhapus' }}</div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        <i class="bi bi-shop me-1"></i>{{ $p->UserPelanggan ? $p->UserPelanggan->nama_toko : '-' }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if ($p->status == 0)
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-2 py-1">Diproses Gudang</span>
                                    @elseif ($p->status == 1)
                                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-50 px-2 py-1">Sedang Dikirim</span>
                                    @elseif ($p->status == 2)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-50 px-2 py-1">Pesanan Selesai</span>
                                    @elseif ($p->status == 3)
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-50 px-2 py-1">Dibatalkan</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($p->metode_pembayaran == 0)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="bi bi-cash me-1"></i>Cash</span>
                                        @if ($p->status_pembayaran == 1)
                                            <div class="text-success mt-1 fw-bold" style="font-size: 10px;">
                                                <i class="bi bi-check-circle-fill me-1"></i>LUNAS
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-journal-text me-1"></i>Kontrabon</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="d-flex flex-column gap-1 align-items-center">
                                        <button type="button"
                                            class="btn btn-light btn-sm border text-primary rounded-2 shadow-sm"
                                            style="width: 120px;" data-bs-toggle="modal"
                                            data-bs-target="#modalDetail{{ $p->nomor }}"
                                            title="Lihat Detail Rincian">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-x fs-2 opacity-50 d-block mb-2"></i>
                                    Tidak ada riwayat pesanan pada status / filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TEMPLATE PAGINATION SERAGAM --}}
            <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">Halaman {{ $pesanan->currentPage() }} dari {{ $pesanan->lastPage() }}</span>
                <div class="m-0">
                    @if ($pesanan->hasPages())
                        {{ $pesanan->links('pagination::bootstrap-5') }}
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

    {{-- ====================================================== --}}
    {{-- MODAL DETAIL PESANAN DIRENDER DI LUAR TABEL --}}
    {{-- ====================================================== --}}
    @foreach ($pesanan as $p)
        <div class="modal fade" id="modalDetail{{ $p->nomor }}" tabindex="-1"
            aria-labelledby="modalDetailLabel{{ $p->nomor }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg rounded-4">

                    <div class="modal-header bg-light border-bottom-0 pb-3">
                        <div>
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2"
                                id="modalDetailLabel{{ $p->nomor }}">
                                <i class="bi bi-receipt-cutoff text-primary"></i> Detail Pesanan
                            </h5>
                            <div class="text-muted small mt-1">{{ $p->nomor }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4 pt-2">
                        <div class="row g-4 mb-4">
                            {{-- Info Pelanggan --}}
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">INFORMASI PELANGGAN</h6>
                                    <div class="mb-2"><i class="bi bi-person text-muted me-2"></i><span class="fw-semibold">{{ $p->UserPelanggan ? $p->UserPelanggan->nama : '-' }}</span></div>
                                    <div class="mb-2"><i class="bi bi-shop text-muted me-2"></i>{{ $p->UserPelanggan ? $p->UserPelanggan->nama_toko : '-' }}</div>
                                    <div class="mb-2"><i class="bi bi-telephone text-muted me-2"></i>{{ $p->UserPelanggan ? $p->UserPelanggan->telepon : '-' }}</div>
                                    <div><i class="bi bi-envelope text-muted me-2"></i>{{ $p->UserPelanggan ? $p->UserPelanggan->email : '-' }}</div>
                                </div>
                            </div>

                            {{-- Info Transaksi --}}
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">INFORMASI TRANSAKSI</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Waktu Pesan</span>
                                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><i class="bi bi-wallet2 me-2"></i>Metode Pembayaran</span>
                                        <span class="fw-semibold">{{ $p->metode_pembayaran == 0 ? 'Cash / Transfer' : 'Kontrabon' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="bi bi-check-circle me-2"></i>Status Pembayaran</span>
                                        @if ($p->status_pembayaran == 1)
                                            <span class="text-success fw-bold">LUNAS</span>
                                        @else
                                            <span class="text-warning fw-bold">BELUM LUNAS</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Daftar Barang --}}
                        <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;"><i class="bi bi-box-seam me-2"></i>RINCIAN BARANG</h6>
                        <div class="table-responsive border rounded-3 overflow-hidden mb-3">
                            <table class="table table-borderless table-striped align-middle mb-0 text-sm">
                                <thead class="bg-light border-bottom">
                                    <tr class="text-muted" style="font-size: 12px;">
                                        <th class="ps-3 py-2">PRODUK</th>
                                        <th class="py-2 text-center">QTY</th>
                                        <th class="py-2 text-end">HARGA</th>
                                        <th class="pe-3 py-2 text-end">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($p->items as $item)
                                        <tr>
                                            <td class="ps-3 py-2">
                                                <div class="fw-semibold text-dark">
                                                    {{ $item->produk ? $item->produk->nama : 'Produk Tidak Ditemukan' }}
                                                </div>
                                                <div class="text-muted" style="font-size: 10px;">SKU: {{ $item->produk_id }}</div>
                                            </td>
                                            <td class="py-2 text-center">{{ $item->jumlah }}</td>
                                            <td class="py-2 text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td class="pe-3 py-2 text-end fw-bold">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3 pe-3">
                            <div class="d-flex align-items-center gap-5 border-top pt-2" style="min-width: 320px;">
                                <div class="fw-semibold">TOTAL KESELURUHAN :</div>
                                <div class="fw-bold text-end">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        @if ($p->catatan)
                            <div class="mt-4 p-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3">
                                <div class="fw-bold text-warning-emphasis mb-1" style="font-size: 12px;">
                                    <i class="bi bi-chat-left-text me-2"></i>CATATAN PEMBELI:
                                </div>
                                <div class="text-dark small">{{ $p->catatan }}</div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection