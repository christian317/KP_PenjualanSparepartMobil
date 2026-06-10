@extends('layouts.app')

@section('title', 'Kelola Refund Dana')

@section('content')
<div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">

    {{-- HEADER --}}
    <div class="sticky-top py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="h4 fw-bold mb-1 d-flex align-items-center">
                    <i class="bi bi-arrow-return-left me-2 text-danger"></i>Kelola Pengajuan Refund Dana
                </div>
                <div class="text-muted small">
                    Tinjau pengajuan pembatalan transaksi dari pelanggan. Setujui dengan melampirkan bukti transfer pembatalan atau tolak pengajuan.
                </div>
            </div>
        </div>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#f57f17!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Menunggu Review</div>
                            <div class="h3 fw-bold text-warning mb-0">{{ $statMenunggu }}</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #fff3e0; width: 40px; height: 40px;">
                            <i class="bi bi-hourglass-split fs-5" style="color: #f57f17;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#198754!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Refund Disetujui</div>
                            <div class="h3 fw-bold text-success mb-0">{{ $statDisetujui }}</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e8f5e9; width: 40px; height: 40px;">
                            <i class="bi bi-check2-all fs-5" style="color: #198754;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#dc3545!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Pengajuan Ditolak</div>
                            <div class="h3 fw-bold text-danger mb-0">{{ $statDitolak }}</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #ffebee; width: 40px; height: 40px;">
                            <i class="bi bi-x-circle fs-5" style="color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#1565c0!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Total Pengajuan</div>
                            <div class="h3 fw-bold text-primary mb-0">{{ $statTotal }}</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e3f2fd; width: 40px; height: 40px;">
                            <i class="bi bi-files fs-5" style="color: #1565c0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <form action="{{ url()->current() }}" method="GET" class="bg-white p-3 rounded-3 shadow-sm mb-4 d-flex flex-wrap gap-2 align-items-center border">
        
        <input type="hidden" name="status" value="{{ request('status') }}">

        <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0" placeholder="Cari No Pesanan / Pelanggan...">
        </div>

        <input type="date" name="tanggal" class="form-control form-control-sm w-auto text-muted" value="{{ request('tanggal') }}" title="Pilih Tanggal Spesifik">
        
        <div class="d-flex align-items-center gap-2">
            <select name="bulan" class="form-select form-select-sm" style="max-width: 130px;">
                <option value="">Semua Bulan</option>
                @php
                    $bulanSekarang = request('bulan');
                    $namaBulan = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                @endphp
                @foreach ($namaBulan as $val => $name)
                    <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <select name="tahun" class="form-select form-select-sm" style="max-width: 110px;">
                <option value="">Semua Tahun</option>
                @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1"><i class="bi bi-funnel-fill"></i> Filter</button>
        <a href="{{ url()->current() }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1"><i class="bi bi-arrow-clockwise"></i> Reset</a>

        <div class="ms-auto text-muted small">
            Menampilkan <b class="text-dark">{{ $pesanan->firstItem() ?? 0 }}</b> sampai <b class="text-dark">{{ $pesanan->lastItem() ?? 0 }}</b> dari {{ $pesanan->total() }} data
        </div>
    </form>

    {{-- NAVIGATION TABS STATUS REFUND --}}
    <ul class="nav nav-pills mb-3 bg-white p-2 rounded-3 shadow-sm border overflow-auto flex-nowrap" style="white-space: nowrap;">
        <li class="nav-item">
            <a class="nav-link {{ request('status') === null || request('status') === '' ? 'active fw-bold' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['status' => '', 'page' => 1]) }}">
                <i class="bi bi-grid-fill me-1"></i> Semua Pengajuan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') === '0' ? 'active fw-bold' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['status' => '0', 'page' => 1]) }}">
                <i class="bi bi-hourglass-split me-1"></i> Menunggu Review
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') === '1' ? 'active fw-bold' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['status' => '1', 'page' => 1]) }}">
                <i class="bi bi-check-circle-fill me-1"></i> Selesai (Disetujui)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') === '2' ? 'active fw-bold' : 'text-muted' }}" href="{{ request()->fullUrlWithQuery(['status' => '2', 'page' => 1]) }}">
                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
            </a>
        </li>
    </ul>

    {{-- TABEL DATA UTAMA --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr class="text-muted small">
                        <th class="ps-3 py-3 border-0">NOMOR PESANAN</th>
                        <th class="py-3 border-0">PELANGGAN</th>
                        <th class="py-3 border-0">ALASAN PEMBATALAN</th>
                        <th class="py-3 border-0 text-end">TOTAL NILAI</th>
                        <th class="py-3 border-0 text-center" style="width: 180px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesanan as $p)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold small text-dark"">{{ $p->nomor }}</div>
                                <div class="text-muted" style="font-size: 11px;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }}
                                </div>
                            </td>

                            <td>
                                <div class="fw-semibold small text-dark">{{ $p->UserPelanggan ? $p->UserPelanggan->nama : 'User Terhapus' }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $p->UserPelanggan ? $p->UserPelanggan->nama_toko : '-' }}</div>
                            </td>

                            <td>
                                <div class="small text-dark text-truncate" style="max-width: 250px;" title="{{ $p->refund?->alasan_pembatalan }}">
                                    {{ $p->refund?->alasan_pembatalan ?? '-' }}
                                </div>
                            </td>

                            <td class="text-end fw-bold text-danger pe-4" style="font-size: 13px;">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </td>

                            <td class="text-center pe-3">
                                <div class="d-flex flex-column gap-1 align-items-center">
                                    {{-- Tombol Lihat Detail Pesanan --}}
                                    <button type="button" class="btn btn-light btn-sm border text-primary fw-semibold shadow-sm" style="width: 130px;" data-bs-toggle="modal" data-bs-target="#modalDetailPesanan{{ $p->nomor }}">
                                        <i class="bi bi-eye me-1"></i> Detail
                                    </button>

                                    {{-- Tombol Aksi Sesuai Status Refund --}}
                                    @if($p->refund?->status === 0)
                                        <button type="button" class="btn btn-danger btn-sm rounded-2 shadow-sm fw-semibold" style="width: 130px;" data-bs-toggle="modal" data-bs-target="#modalProsesRefund{{ $p->nomor }}">
                                            <i class="bi bi-shield-exclamation me-1"></i> Proses Periksa
                                        </button>
                                    @elseif($p->refund?->status === 1)
                                        <button type="button" class="btn btn-success btn-sm rounded-2 shadow-sm fw-semibold" style="width: 130px;" data-bs-toggle="modal" data-bs-target="#modalDetailRefund{{ $p->nomor }}">
                                            <i class="bi bi-receipt me-1"></i> Lihat Bukti
                                        </button>
                                    @elseif($p->refund?->status === 2)
                                        <button type="button" class="btn btn-secondary btn-sm rounded-2 shadow-sm fw-semibold" style="width: 130px;" data-bs-toggle="modal" data-bs-target="#modalDetailRefund{{ $p->nomor }}">
                                            <i class="bi bi-card-text me-1"></i> Lihat Alasan
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-arrow-return-left fs-2 opacity-25 d-block mb-2"></i>
                                Tidak ada pengajuan refund dana pada kriteria ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION INTERFACE --}}
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
{{-- 1. MODAL DETAIL PESANAN --}}
{{-- ====================================================== --}}
@foreach ($pesanan as $p)
    <div class="modal fade" id="modalDetailPesanan{{ $p->nomor }}" tabindex="-1" aria-labelledby="modalDetailPesananLabel{{ $p->nomor }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-light border-bottom-0 pb-3">
                    <div>
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalDetailPesananLabel{{ $p->nomor }}">
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

                </div>
                <div class="modal-footer border-top-0 bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- ====================================================== --}}
{{-- 2. MODAL PROSES INTERAKTIF (SETUJU / TOLAK) --}}
{{-- ====================================================== --}}
@foreach ($pesanan as $p)
    @if($p->refund && $p->refund->status === 0)
        <div class="modal fade" id="modalProsesRefund{{ $p->nomor }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-light border-bottom-0 pb-3">
                        <div>
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-arrow-return-left text-danger"></i> Tinjau Permohonan Refund
                            </h5>
                            <div class="text-muted small mt-1"">{{ $p->nomor }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    
                    <div class="modal-body p-0">
                        <ul class="nav nav-tabs nav-justified bg-light border-bottom" id="refundTab{{ $p->nomor }}" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold text-success py-2.5" id="approve-tab-{{ $p->nomor }}" data-bs-toggle="tab" data-bs-target="#approve-{{ $p->nomor }}" type="button" role="tab">Setujui Refund</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold text-danger py-2.5" id="reject-tab-{{ $p->nomor }}" data-bs-toggle="tab" data-bs-target="#reject-{{ $p->nomor }}" type="button" role="tab">Tolak Refund</button>
                            </li>
                        </ul>

                        <div class="tab-content p-4" id="refundTabContent{{ $p->nomor }}">
                            
                            {{-- ACARA SETUJU --}}
                            <div class="tab-pane fade show active" id="approve-{{ $p->nomor }}" role="tabpanel">
                                <form action="{{ route('owner.refund.approve', $p->nomor) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="bg-light p-3 rounded-3 mb-3 border">
                                        <div class="text-muted small mb-1">Nominal yang harus ditransfer ke pelanggan:</div>
                                        <h4 class="fw-bold text-success mb-3">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</h4>

                                        <div class="row g-2 small text-dark">
                                            <div class="col-4 text-muted">Bank Tujuan</div>
                                            <div class="col-8 fw-semibold">: {{ $p->refund->nama_bank }}</div>
                                            <div class="col-4 text-muted">No. Rekening</div>
                                            <div class="col-8 fw-semibold"">: {{ $p->refund->nomor_rekening }}</div>
                                            <div class="col-4 text-muted">Atas Nama</div>
                                            <div class="col-8 fw-semibold">: {{ $p->refund->atas_nama }}</div>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold small text-muted">Upload Bukti Transfer Dana <span class="text-danger">*</span></label>
                                        <input class="form-control form-control-sm" type="file" name="bukti_transfer" accept=".jpg,.jpeg,.png" required>
                                        <div class="form-text text-muted" style="font-size: 11px;">Maksimal file gambar 2MB (JPG/PNG).</div>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 fw-bold rounded-3 py-2" onclick="return confirm('Apakah Anda yakin sudah mentransfer dana dan ingin menyetujui refund ini?');">
                                        <i class="bi bi-check-circle me-1"></i> Transfer Selesai & ACC
                                    </button>
                                </form>
                            </div>

                            {{-- ACARA TOLAK --}}
                            <div class="tab-pane fade" id="reject-{{ $p->nomor }}" role="tabpanel">
                                <form action="{{ route('owner.refund.tolak', $p->nomor) }}" method="POST">
                                    @csrf
                                    <div class="alert alert-warning border-0 small mb-3">
                                        <i class="bi bi-info-circle-fill me-1"></i> Menolak pengajuan akan mengembalikan alur kerja pesanan ini ke status <b>"Diproses Gudang"</b> untuk dipersiapkan lagi oleh admin gudang.
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold small text-muted">Alasan Penolakan Pengajuan <span class="text-danger">*</span></label>
                                        <textarea class="form-control text-sm" name="alasan_penolakan" rows="3" placeholder="Berikan penjelasan mengapa pengajuan refund dibatalkan/ditolak..." required></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-danger w-100 fw-bold rounded-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i> Tolak & Teruskan ke Gudang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- ====================================================== --}}
{{-- 3. MODAL HISTORI READ-ONLY (UNTUK YANG SELESAI / DITOLAK) --}}
{{-- ====================================================== --}}
@foreach ($pesanan as $p)
    @if($p->refund && $p->refund->status !== 0)
        <div class="modal fade" id="modalDetailRefund{{ $p->nomor }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header bg-light border-bottom-0 pb-3">
                        <div>
                            <h5 class="modal-title fw-bold text-dark"><i class="bi bi-info-circle me-2"></i>Histori Keputusan Refund</h5>
                            <div class="text-muted small mt-1"">{{ $p->nomor }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4 pt-2">
                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <div class="text-muted small mb-1">Nilai Pengajuan:</div>
                            <h5 class="fw-bold text-dark">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</h5>
                            <hr class="my-2 border-secondary border-opacity-10">
                            <div class="small mb-1"><span class="text-muted">Alasan User:</span> <span class="text-dark fw-medium">{{ $p->refund->alasan_pembatalan }}</span></div>
                        </div>

                        @if($p->refund->status === 1)
                            <div class="alert alert-success border-0 small">
                                <h6 class="fw-bold mb-1"><i class="bi bi-check-circle-fill me-1"></i>Refund Telah Disetujui</h6>
                                <p class="mb-2">Dana telah dikembalikan ke rekening bank pemohon berikut. Stok produk otomatis dipulihkan kembali ke katalog gudang.</p>
                                <div class=" p-2 border bg-white rounded text-dark" style="font-size:12px;">
                                    Tujuan: {{ $p->refund->nama_bank }} - {{ $p->refund->nomor_rekening }} <br>
                                    a.n : {{ $p->refund->atas_nama }}
                                </div>
                                <div class="mt-3">
                                    <div class="text-muted mb-1 small fw-bold">Bukti Transfer Owner:</div>
                                    @if($p->refund->bukti_transfer && $p->refund->bukti_transfer != '-')
                                        {{-- Tombol Download menggantikan tag img --}}
                                        <a href="{{ asset('storage/refund/' . $p->refund->bukti_transfer) }}" 
                                        download="{{ $p->refund->bukti_transfer }}"
                                        class="btn btn-sm btn-outline-primary fw-semibold px-3 py-2 shadow-sm" 
                                        style="font-size: 12px;">
                                        <i class="bi bi-download me-2"></i> Unduh Bukti Transfer
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </div>
                            </div>
                        @elseif($p->refund->status === 2)
                            <div class="alert alert-danger border-0 small">
                                <h6 class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i>Refund Ditolak</h6>
                                <p class="mb-0">Pengajuan ini ditolak oleh Owner. Transaksi dialihkan kembali ke halaman kerja Admin Gudang untuk dilanjutkan pengirimannya.</p>
                            </div>
                            
                            {{-- Mengekstrak catatan penolakan dari string catatan pesanan --}}
                            @if(str_contains($p->catatan, '[Refund Ditolak]:'))
                                <div class="p-3 bg-light border rounded rounded-3 small">
                                    <div class="fw-bold text-danger-emphasis mb-1">Catatan Penolakan Owner:</div>
                                    <div class="text-dark">{{ Str::after($p->catatan, '[Refund Ditolak]: ') }}</div>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="modal-footer border-top-0 bg-light py-2">
                        <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

{{-- TOAST ALERTS NOTIFICATION --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    @if (Session::has('toast_success'))
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('toastMsg').textContent = "{{ Session::get('toast_success') }}";
            new bootstrap.Toast(document.getElementById('liveToast')).show();
        });
    @endif
</script>
@endsection