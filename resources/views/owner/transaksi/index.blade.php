@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page', 'Riwayat Transaksi')

@section('content')
    <div class="p-4" style="background-color:#f8f9fa; min-height:100vh;">

        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color:#f8f9fa; z-index:1020; margin-top:-1.5rem; padding-top:1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-wallet-fill me-2 text-success"></i> Riwayat Transaksi Lunas
                    </div>
                    <div class="text-muted small">
                        Pantau seluruh histori pembayaran masuk dari Cash maupun pelunasan Kontrabon.
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK UANG MASUK (DESAIN SERAGAM) --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#198754!important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-secondary small fw-semibold mb-1">TOTAL PENDAPATAN CASH</div>
                                <div class="h3 fw-bold text-success mb-0">Rp {{ number_format($statTotalCash, 0, ',', '.') }}</div>
                                <div class="text-muted mt-1" style="font-size:11px;">Pembayaran lunas langsung</div>
                            </div>
                            <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #e8f5e9; width: 55px; height: 55px;">
                                <i class="bi bi-cash-coin fs-4" style="color: #198754;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#1565c0!important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-secondary small fw-semibold mb-1">TOTAL CICILAN (MIDTRANS)</div>
                                <div class="h3 fw-bold text-primary mb-0">Rp {{ number_format($statTotalCicilan, 0, ',', '.') }}</div>
                                <div class="text-muted mt-1" style="font-size:11px;">Pembayaran cicilan kontrabon</div>
                            </div>
                            <div class="rounded-3 p-3 d-flex align-items-center justify-content-center" style="background-color: #e3f2fd; width: 55px; height: 55px;">
                                <i class="bi bi-bank fs-4" style="color: #1565c0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM FILTER & SEARCH --}}
        <form action="{{ route('owner.transaksi.index') }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-4 mt-2 d-flex flex-wrap gap-2 align-items-center border">
            
            <input type="hidden" name="tab" value="{{ $tab }}">

            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                    placeholder="Cari pesanan, pelanggan...">
            </div>

            <div class="d-flex align-items-center gap-2">
                <input type="date" name="tanggal" class="form-control form-control-sm text-muted" value="{{ request('tanggal') }}" title="Pilih Tanggal Spesifik">
                
                <select name="bulan" class="form-select form-select-sm text-muted" style="max-width: 130px;">
                    <option value="">Semua Bulan</option>
                    @php
                        $bulanSekarang = request()->has('bulan') ? request('bulan') : date('m');
                        $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                    @endphp
                    @foreach ($namaBulan as $val => $name)
                        <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="form-select form-select-sm text-muted" style="max-width: 100px;">
                    <option value="">Semua Tahun</option>
                    @php $tahunSekarang = request()->has('tahun') ? request('tahun') : date('Y'); @endphp
                    @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $tahunSekarang == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1">
                <i class="bi bi-funnel-fill"></i> Filter
            </button>

            <a href="{{ route('owner.transaksi.index', ['tab' => $tab]) }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>

            <div class="ms-auto text-muted small">
                Menampilkan <b class="text-dark">{{ $data->firstItem() ?? 0 }}</b> sampai <b class="text-dark">{{ $data->lastItem() ?? 0 }}</b> dari {{ $data->total() }} data
            </div>
        </form>

        {{-- NAVIGATION TABS --}}
        <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm d-inline-flex border flex-nowrap overflow-auto" style="white-space: nowrap;">
            <li class="nav-item">
                <a class="nav-link fw-semibold px-4 {{ $tab == '0' ? 'active bg-success' : 'text-muted' }}"
                    href="{{ route('owner.transaksi.index', ['tab' => '0', 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}">
                    <i class="bi bi-receipt me-1"></i> Pembayaran Cash
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold px-4 {{ $tab == '1' ? 'active bg-primary' : 'text-muted' }}"
                    href="{{ route('owner.transaksi.index', ['tab' => '1', 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}">
                    <i class="bi bi-journal-check me-1"></i> Historis Kontrabon Lunas
                </a>
            </li>
        </ul>

        {{-- ======================================================== --}}
        {{-- KONTEN TAB 0: CASH --}}
        {{-- ======================================================== --}}
        @if ($tab == '0')
            <h5 class="fw-bold mb-3 mt-2"><i class="bi bi-cash-stack me-2 text-secondary"></i>Daftar Transaksi Cash</h5>
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr class="text-muted small">
                                <th class="ps-4 py-3 border-0">INVOICE</th>
                                <th class="py-3 border-0">PELANGGAN</th>
                                <th class="py-3 border-0 text-center">STATUS PESANAN</th>
                                <th class="py-3 border-0 text-center">STATUS PEMBAYARAN</th>
                                <th class="py-3 border-0 text-center pe-4" style="width: 150px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            @if($item->pesanan_status != 5)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-dark">{{ $item->nomor_pesanan }}</div>
                                        <div class="text-muted" style="font-size:11px;">Trx ID: {{ $item->pesanan_id_midtrans }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">{{ $item->nama }}</div>
                                        <div class="text-muted" style="font-size:11px;"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->pesanan_status == 0)
                                            <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-2 py-1">Sedang Diproses</span>
                                        @elseif ($item->pesanan_status == 1)
                                            <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-50 px-2 py-1">Dikirim</span>
                                        @elseif ($item->pesanan_status == 2)
                                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-50 px-2 py-1">Selesai</span>
                                        @elseif ($item->pesanan_status == 3)
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-50 px-2 py-1">Dibatalkan</span>
                                        @elseif($item->pesanan_status == 4)
                                            <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-50 px-2 py-1">Pengembalian Dana</span>
                                        @elseif($item->pesanan_status == 6)
                                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-50 px-2 py-1">Pre-order</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status == 1)
                                            <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Lunas</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="bi bi-clock me-1"></i>Menunggu</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-light btn-sm border text-success px-3 py-1 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCash{{ $item->id }}" title="Lihat Detail Rincian">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cash-coin fs-2 opacity-50 d-block mb-2"></i>
                                        Belum ada transaksi pembayaran cash lunas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- PAGINATION CASH --}}
                <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size: 12px;">Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}</span>
                    <div class="m-0">
                        @if ($data->hasPages())
                            {{ $data->links('pagination::bootstrap-5') }}
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
        @endif

        {{-- ======================================================== --}}
        {{-- KONTEN TAB 1: CICILAN KONTRABON --}}
        {{-- ======================================================== --}}
        @if ($tab == '1')
            <h5 class="fw-bold mb-3 mt-2"><i class="bi bi-journal-check me-2 text-secondary"></i>Daftar Pelunasan Kontrabon</h5>
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr class="text-muted small">
                                <th class="ps-4 py-3 border-0">NOMOR KONTRABON</th>
                                <th class="py-3 border-0">PELANGGAN</th>
                                <th class="py-3 border-0">TAGIHAN & PELUNASAN</th>
                                <th class="py-3 border-0 text-center">TANGGAL LUNAS</th>
                                <th class="py-3 border-0 text-center">STATUS</th>
                                <th class="py-3 border-0 text-center pe-4" style="width: 150px;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $p)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-dark">{{ $p->nomor_kontrabon }}</div>
                                        <div class="text-muted" style="font-size:11px;">Mulai: {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">{{ $p->nama }}</div>
                                        <div class="text-muted" style="font-size:11px;"><i class="bi bi-shop me-1"></i>{{ $p->nama_toko }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between mb-1" style="font-size:11px;">
                                            <span class="text-muted">Total: Rp {{ number_format($p->total_tagihan,0,',','.') }}</span>
                                            <span class="fw-bold text-success">Lunas</span>
                                        </div>
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="fw-semibold small text-dark">
                                            {{ \Carbon\Carbon::parse($p->tanggal_pelunasan)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                            <i class="bi bi-check-circle-fill me-1"></i>Lunas Selesai
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-light btn-sm border text-primary px-3 py-1 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalKontrabon{{ $p->nomor_kontrabon }}">
                                            Histori <i class="bi bi-chevron-right ms-1" style="font-size: 10px;"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-journal-x fs-2 opacity-50 d-block mb-2"></i>
                                        Belum ada histori kontrabon lunas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION KONTRABON --}}
                <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size: 12px;">Halaman {{ $data->currentPage() }} dari {{ $data->lastPage() }}</span>
                    <div class="m-0">
                        @if ($data->hasPages())
                            {{ $data->links('pagination::bootstrap-5') }}
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
        @endif

    </div>

    {{-- ====================================================== --}}
    {{-- MODAL CASH (DESAIN STRUK BELANJA) --}}
    {{-- ====================================================== --}}
    @if ($tab == '0')
        @foreach ($data as $tc)
            <div class="modal fade" id="modalCash{{ $tc->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 450px;">
                    <div class="modal-content border-0 shadow-lg rounded-4" style="background-color: #f1f3f5;">
                        <div class="modal-header border-bottom-0 pb-2">
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-receipt text-success"></i> E-Receipt
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        
                        <div class="modal-body p-4 pt-0">
                            <div class="bg-white p-4 rounded-3 shadow-sm border position-relative overflow-hidden">
                                @if ($tc->status == 1)
                                    <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); font-size: 65px; font-weight: 900; color: rgba(25, 135, 84, 0.08); z-index: 0; pointer-events: none; border: 8px solid rgba(25, 135, 84, 0.08); padding: 10px 20px; border-radius: 15px;">
                                        LUNAS
                                    </div>
                                @endif

                                <div class="text-center mb-3 position-relative" style="z-index: 1;">
                                    <div class="fs-4 fw-bolder text-dark mb-1">CV. JAYA ABADI</div>
                                    <div class="text-muted" style="font-size: 11px;">Struk Bukti Pembayaran Digital</div>
                                </div>

                                <hr style="border-top: 2px dashed #ccc; opacity: 1;" class="my-3">

                                {{-- INFO PELANGGAN & TRX --}}
                                <div class="small mb-3 position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">No. Invoice:</span>
                                        <span class="fw-bold text-dark">{{ $tc->nomor_pesanan }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Tanggal:</span>
                                        <span class="text-dark">{{ \Carbon\Carbon::parse($tc->tanggal)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-muted">Pelanggan:</span>
                                        <span class="text-dark fw-semibold">{{ $tc->nama ?? '-' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Kasir/Sistem:</span>
                                        <span class="text-dark">Midtrans (Auto)</span>
                                    </div>
                                </div>

                                <hr style="border-top: 2px dashed #ccc; opacity: 1;" class="my-3">

                                {{-- RINCIAN BARANG SUSUN BAWAH --}}
                                <div class="position-relative" style="z-index: 1;">
                                    <div class="fw-bold text-dark small mb-3">RINCIAN BARANG:</div>
                                    @if (isset($tc->items) && count($tc->items) > 0)
                                        @foreach ($tc->items as $item)
                                            <div class="mb-3">
                                                <div class="fw-semibold text-dark small">{{ $item->nama }}</div>
                                                <div class="d-flex justify-content-between mt-1" style="font-size: 12px;">
                                                    <span class="text-muted">{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                    <span class="fw-bold text-dark">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="text-center text-muted small py-2">Tidak ada rincian barang</div>
                                    @endif
                                </div>

                                <hr style="border-top: 2px dashed #ccc; opacity: 1;" class="my-3">

                                {{-- GRAND TOTAL & STATUS --}}
                                <div class="position-relative" style="z-index: 1;">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="fw-bold text-muted small">TOTAL BELANJA</span>
                                        <span class="fw-bolder fs-5 text-success">Rp {{ number_format($tc->total_belanja ?? $tc->nominal_pembayaran, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    <div class="bg-light p-3 rounded-3 border">
                                        <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 12px;">
                                            <span class="text-muted">Metode Bayar:</span>
                                            <span class="fw-semibold text-dark">Cash (Midtrans)</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2" style="font-size: 12px;">
                                            <span class="text-muted">Trx ID:</span>
                                            <span class="font-monospace text-dark">{{ $tc->pesanan_id_midtrans }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center" style="font-size: 12px;">
                                            <span class="text-muted">Status:</span>
                                            @if ($tc->status == 1)
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>LUNAS</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="bi bi-clock me-1"></i>MENUNGGU</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- FOOTER STRUK --}}
                                <div class="text-center mt-4 position-relative" style="z-index: 1;">
                                    <div class="small fw-semibold text-dark">Terima Kasih</div>
                                    <div class="text-muted mt-1" style="font-size: 10px;">Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer border-top-0 pb-4 pt-1 justify-content-center">
                            <button type="button" class="btn btn-secondary fw-semibold rounded-3 px-5 shadow-sm" data-bs-dismiss="modal">Tutup Struk</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ====================================================== --}}
    {{-- MODAL KONTRABON (DESAIN SERAGAM) --}}
    {{-- ====================================================== --}}
    @if ($tab == '1')
        @foreach ($data as $p)
            <div class="modal fade" id="modalKontrabon{{ $p->nomor_kontrabon }}" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        
                        <div class="modal-header border-bottom-0 bg-light pb-3 px-4">
                            <div>
                                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2 mb-0">
                                    <i class="bi bi-journal-check text-primary"></i> Historis Pelunasan Kontrabon
                                </h5>
                                <div class="text-muted small mt-1">{{ $p->nomor_kontrabon }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-4 bg-white pt-2">
                            <div class="row g-4 mb-4">
                                {{-- KIRI: INFO PELANGGAN --}}
                                <div class="col-md-5">
                                    <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                        <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">INFORMASI PELANGGAN</h6>
                                        <div class="fw-bold text-dark fs-5 mb-1">{{ $p->nama }}</div>
                                        <div class="text-secondary small mb-3"><i class="bi bi-shop me-1"></i>{{ $p->nama_toko }}</div>
                                        <div class="d-flex justify-content-between small text-muted border-top pt-2">
                                            <span>Status Akhir:</span>
                                            <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Selesai (Lunas)</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- KANAN: STATUS PEMBAYARAN --}}
                                <div class="col-md-7">
                                    <h6 class="fw-bold text-dark mb-2" style="font-size: 13px;">REKAP KEUANGAN</h6>
                                    <div class="row g-3 h-100">
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center bg-light border border-secondary border-opacity-25">
                                                <div class="text-muted small fw-semibold mb-1">Total Tagihan Awal</div>
                                                <div class="fw-bold text-dark fs-5">Rp {{ number_format($p->total_tagihan,0,',','.') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center bg-success bg-opacity-10 border border-success border-opacity-25">
                                                <div class="text-success small fw-semibold mb-1">Telah Dibayar (Lunas)</div>
                                                <div class="fw-bold text-success fs-5">Rp {{ number_format($p->sudah_dibayar,0,',','.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                {{-- TABEL RINCIAN PESANAN (PER-PESANAN) --}}
                                <div class="col-lg-6">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;"><i class="bi bi-box-seam me-2"></i>RINCIAN PESANAN TERGABUNG</h6>
                                    <div class="accordion accordion-flush border rounded-3 overflow-hidden shadow-sm" id="accPesanan-{{ $p->nomor_kontrabon }}">
                                        @if(isset($p->pesanan_list) && count($p->pesanan_list) > 0)
                                            @foreach ($p->pesanan_list as $idx => $psn)
                                                @if($psn->status != 3)
                                                    <div class="accordion-item border-bottom">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button collapsed py-3 px-3 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#col-{{ $p->nomor_kontrabon }}-{{ $idx }}">
                                                                <div class="w-100 d-flex justify-content-between align-items-center pe-3">
                                                                    <div>
                                                                        <div class="fw-bold text-dark small">{{ $psn->nomor }}</div>
                                                                        <div class="text-muted" style="font-size:10px;"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($psn->tanggal)->format('d M Y') }}</div>
                                                                    </div>
                                                                    <div class="fw-bold text-dark small">Rp {{ number_format($psn->subtotal, 0, ',', '.') }}</div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="col-{{ $p->nomor_kontrabon }}-{{ $idx }}" class="accordion-collapse collapse" data-bs-parent="#accPesanan-{{ $p->nomor_kontrabon }}">
                                                            <div class="accordion-body p-0">
                                                                <table class="table table-sm table-borderless table-striped mb-0">
                                                                    <tbody>
                                                                        @foreach($psn->items as $item)
                                                                        <tr class="border-bottom border-light">
                                                                            <td class="ps-3 py-2 small">
                                                                                <div class="fw-semibold text-dark">{{ $item->nama }}</div>
                                                                                <div class="text-muted mt-1" style="font-size:10px;">SKU: {{ $item->produk_id }}</div>
                                                                            </td>
                                                                            <td class="text-center py-2 small align-middle">{{ $item->jumlah }} x</td>
                                                                            <td class="text-end pe-3 py-2 small fw-bold align-middle">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="p-4 text-center small text-muted">Detail pesanan tidak ditemukan.</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- TABEL HISTORI MIDTRANS --}}
                                <div class="col-lg-6">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;"><i class="bi bi-clock-history me-2"></i>HISTORI PEMBAYARAN CICILAN</h6>
                                    <div class="table-responsive border rounded-3 overflow-hidden shadow-sm">
                                        <table class="table table-hover table-borderless table-striped align-middle mb-0">
                                            <thead class="bg-light border-bottom">
                                                <tr class="small text-muted" style="font-size: 12px;">
                                                    <th class="ps-3 py-2 border-0">Waktu Pembayaran</th>
                                                    <th class="py-2 border-0">Trx ID Midtrans</th>
                                                    <th class="text-end pe-3 py-2 border-0">Nominal Disetor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($p->histori_cicilan) && count($p->histori_cicilan) > 0)
                                                    @foreach($p->histori_cicilan as $cicilan)
                                                    <tr class="border-bottom border-light">
                                                        <td class="ps-3 py-2">
                                                            <div class="small fw-semibold text-dark">{{ \Carbon\Carbon::parse($cicilan->updated_at)->format('d M Y') }}</div>
                                                            <div class="text-muted" style="font-size:10px;">{{ \Carbon\Carbon::parse($cicilan->updated_at)->format('H:i') }} WIB</div>
                                                        </td>
                                                        <td class="small text-muted py-2">{{ $cicilan->pesanan_id_midtrans }}</td>
                                                        <td class="text-end pe-3 fw-bold text-success py-2">+ Rp {{ number_format($cicilan->nominal_pembayaran,0,',','.') }}</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                    <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada histori pembayaran.</td></tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer border-top p-3 bg-light justify-content-end">
                            <button type="button" class="btn btn-secondary fw-semibold rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection