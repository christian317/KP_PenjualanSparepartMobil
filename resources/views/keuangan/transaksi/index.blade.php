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
                        <i class="bi bi-wallet-fill me-2 text-success"></i> Riwayat Transaksi & Uang Masuk
                    </div>
                    <div class="text-muted small">
                        Pantau seluruh aliran dana masuk dari pembayaran Cash maupun setoran Cicilan Kontrabon.
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK UANG MASUK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4"
                    style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small fw-semibold mb-1 opacity-75">TOTAL PENDAPATAN CASH</div>
                            <div class="h2 fw-bold mb-0">Rp {{ number_format($statTotalCash, 0, ',', '.') }}</div>
                        </div>
                        <i class="bi bi-cash-coin opacity-50" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4"
                    style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color: white;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="small fw-semibold mb-1 opacity-75">TOTAL CICILAN MASUK (MIDTRANS)</div>
                            <div class="h2 fw-bold mb-0">Rp {{ number_format($statTotalCicilan, 0, ',', '.') }}</div>
                        </div>
                        <i class="bi bi-bank opacity-50" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM FILTER & SEARCH --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-3">
                <form action="{{ route('keuangan.transaksi.index') }}" method="GET" class="row g-2 align-items-center">
                    <input type="hidden" name="tab" value="{{ $tab }}">

                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1">Cari Pesanan / Pelanggan</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Pilih Tanggal (Opsional)</label>
                        <input type="date" name="tanggal" class="form-control form-control-sm"
                            value="{{ request('tanggal') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Bulan</label>
                        <select name="bulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @php
                                $bulanSekarang = request()->has('bulan') ? request('bulan') : date('m');
                                $namaBulan = [
                                    '01' => 'Januari',
                                    '02' => 'Februari',
                                    '03' => 'Maret',
                                    '04' => 'April',
                                    '05' => 'Mei',
                                    '06' => 'Juni',
                                    '07' => 'Juli',
                                    '08' => 'Agustus',
                                    '09' => 'September',
                                    '10' => 'Oktober',
                                    '11' => 'November',
                                    '12' => 'Desember',
                                ];
                            @endphp
                            @foreach ($namaBulan as $val => $name)
                                <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua Tahun</option>
                            @php
                                $tahunSekarang = request()->has('tahun') ? request('tahun') : date('Y');
                            @endphp
                            @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                                <option value="{{ $y }}" {{ $tahunSekarang == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                            <i class="bi bi-funnel-fill me-1"></i> Terapkan
                        </button>
                        <a href="{{ route('keuangan.transaksi.index', ['tab' => $tab]) }}"
                            class="btn btn-light border btn-sm w-100 fw-semibold text-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
                <div class="mt-2 text-muted" style="font-size: 11px;">
                    * <i>Secara default memunculkan data bulan ini. Jika input <b>Tanggal</b> diisi, maka filter Bulan dan
                        Tahun akan diabaikan.</i>
                </div>
            </div>
        </div>

        {{-- NAVIGATION TABS --}}
        <ul class="nav nav-pills mb-4 bg-white p-2 rounded-3 shadow-sm d-inline-flex border">
            <li class="nav-item">
                <a class="nav-link fw-semibold px-4 {{ $tab == 'cash' ? 'active bg-success' : 'text-muted' }}"
                    href="{{ route('keuangan.transaksi.index', ['tab' => 'cash', 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}">
                    <i class="bi bi-receipt me-1"></i> Pembayaran Lunas (Cash)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold px-4 {{ $tab == 'kontrabon' ? 'active bg-primary' : 'text-muted' }}"
                    href="{{ route('keuangan.transaksi.index', ['tab' => 'kontrabon', 'bulan' => request('bulan'), 'tahun' => request('tahun')]) }}">
                    <i class="bi bi-list-check me-1"></i> Cicilan Kontrabon Masuk
                </a>
            </li>
        </ul>

        {{-- KONTEN TAB: CASH --}}
        @if ($tab == 'cash')
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr class="text-muted small">
                                <th class="ps-4 py-3 border-0">ID TRANSAKSI & PESANAN</th>
                                <th class="py-3 border-0">PELANGGAN</th>
                                <th class="py-3 border-0 fw-bold text-dark">NOMINAL DIBAYAR</th>
                                <th class="py-3 border-0 text-center">STATUS MIDTRANS</th>
                                <th class="py-3 border-0 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-dark">{{ $item->nomor_pesanan }}</div>
                                        <div class="text-muted" style="font-size:11px;">Trx ID:
                                            {{ $item->pesanan_id_midtrans }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small">{{ $item->nama }}</div>
                                        <div class="text-muted" style="font-size:11px;">Tgl Pesan:
                                            {{ \Carbon\Carbon::parse($item->tanggal_pemesanan)->format('d M Y') }}</div>
                                    </td>
                                    <td class="fw-bold text-success small">
                                        Rp {{ number_format($item->nominal_pembayaran, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if ($item->status == 1)
                                            <span class="badge bg-success bg-opacity-25 text-success"><i
                                                    class="bi bi-check-circle me-1"></i>Selesai / Lunas</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-25 text-secondary"><i
                                                    class="bi bi-clock me-1"></i>Menunggu Bayar</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-light btn-sm border text-success fw-semibold"
                                            data-bs-toggle="modal" data-bs-target="#modalCash{{ $item->nomor_pesanan }}">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi pembayaran
                                        cash.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white py-3 border-0">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-end mb-0">
                            @if ($data->onFirstPage())
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                            @else   
                                <li class="page-item">
                                    <a class="page-link" href="{{ $data->previousPageUrl() }}"
                                        rel="prev">Previous</a>
                                </li>
                            @endif

                            @foreach ($data->links()->elements as $element)
                                @if (is_string($element))
                                    <li class="page-item disabled" aria-disabled="true"><span
                                            class="page-link">{{ $element }}</span></li>
                                @endif

                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $data->currentPage())
                                            <li class="page-item active" aria-current="page"><span
                                                    class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach

                            @if ($data->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        @endif

        {{-- KONTEN TAB: CICILAN KONTRABON --}}
        @if ($tab == 'kontrabon')
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr class="text-muted small">
                                <th class="ps-4 py-3 border-0">REFERENSI PESANAN</th>
                                <th class="py-3 border-0">PELANGGAN</th>
                                <th class="py-3 border-0 fw-bold text-dark">TAGIHAN & STATUS</th>
                                <th class="py-3 border-0 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold small text-primary">{{ $item->nomor_pesanan }}</div>
                                        <div class="text-muted" style="font-size:11px;">Tgl Pesan:
                                            {{ \Carbon\Carbon::parse($item->tanggal_pemesanan)->format('d M Y') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold small text-dark">{{ $item->nama }}</div>
                                        <div class="text-muted" style="font-size:11px;">{{ $item->nama_toko }}</div>
                                    </td>
                                    <td>
                                        <div class="small fw-semibold text-dark mb-1">Rp
                                            {{ number_format($item->total_tagihan, 0, ',', '.') }}</div>
                                        <div class="progress" style="height: 5px; max-width: 150px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $item->persentase_bayar }}%"></div>
                                        </div>
                                        <div class="text-muted mt-1" style="font-size:10px;">Sisa: Rp
                                            {{ number_format($item->sisa_tagihan, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-light btn-sm border text-primary fw-semibold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalKontrabon{{ $item->nomor_pesanan }}">
                                            <i class="bi bi-list-check"></i> Histori Cicilan
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data piutang
                                        berjalan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- AREA PAGINATION CUSTOM UNTUK KONTRABON --}}
                <div class="card-footer bg-white py-3 border-0">
                    @if ($data->hasPages())
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-end mb-0">
                                {{-- Tombol Previous --}}
                                @if ($data->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1"
                                            aria-disabled="true">Previous</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $data->previousPageUrl() }}"
                                            rel="prev">Previous</a>
                                    </li>
                                @endif

                                {{-- Looping Angka Halaman --}}
                                @foreach ($data->links()->elements as $element)
                                    @if (is_string($element))
                                        <li class="page-item disabled" aria-disabled="true"><span
                                                class="page-link">{{ $element }}</span></li>
                                    @endif

                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $data->currentPage())
                                                <li class="page-item active" aria-current="page"><span
                                                        class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Tombol Next --}}
                                @if ($data->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next">Next</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    @endif
                </div>
            </div>
        @endif

    </div>

    {{-- MODAL LOOP CASH --}}
    @if ($tab == 'cash')
        @foreach ($data as $tc)
            <div class="modal fade" id="modalCash{{ $tc->nomor_pesanan }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header border-bottom bg-light">
                            <h5 class="modal-title fw-bold">Detail Transaksi Cash</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <div class="text-muted small mb-1">Nomor Invoice</div>
                                    <div class="fw-bold fs-5 text-dark">{{ $tc->nomor_pesanan }}</div>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <div class="text-muted small mb-1">Status Pembayaran</div>
                                    @if ($tc->status == 1)
                                        <div class="badge bg-success fs-6"><i class="bi bi-check-circle me-2"></i>Berhasil
                                            Dilunasi</div>
                                    @else
                                        <div class="badge bg-secondary fs-6"><i class="bi bi-clock me-2"></i>Belum Dibayar
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <h6 class="fw-bold mb-2">Rincian Pembelian:</h6>
                            <div class="table-responsive border rounded-3">
                                <table class="table table-sm align-middle mb-0">
                                    <thead class="bg-light text-muted small">
                                        <tr>
                                            <th class="ps-3 py-2">Produk</th>
                                            <th class="text-center py-2">Qty</th>
                                            <th class="text-end pe-3 py-2">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($tc->items) && count($tc->items) > 0)
                                            @foreach ($tc->items as $item)
                                                <tr>
                                                    <td class="ps-3 small">{{ $item->nama_produk }}</td>
                                                    <td class="text-center small">{{ $item->jumlah }}</td>
                                                    <td class="text-end small pe-3 fw-semibold">Rp
                                                        {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-center small py-2">Data produk tidak
                                                    ditemukan.</td>
                                            </tr>
                                        @endif
                                        <tr class="bg-light">
                                            <td colspan="2" class="text-end fw-bold">TOTAL DIBAYAR :</td>
                                            <td class="text-end pe-3 fw-bold text-success fs-6">Rp
                                                {{ number_format($tc->total_belanja ?? $tc->nominal_pembayaran, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- MODAL LOOP KONTRABON --}}
    @if ($tab == 'kontrabon')
        @foreach ($data as $p)
            <div class="modal fade" id="modalKontrabon{{ $p->nomor_pesanan }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header border-bottom bg-light">
                            <div>
                                <h5 class="modal-title fw-bold mb-0">Histori Pembayaran Cicilan</h5>
                                <div class="small text-muted mt-1">{{ $p->nomor_pesanan }} - {{ $p->nama }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4 bg-light">
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body p-3 text-center">
                                            <div class="text-muted small mb-1">Total Tagihan</div>
                                            <div class="fw-bold fs-5 text-dark">Rp
                                                {{ number_format($p->total_tagihan, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body p-3 text-center">
                                            <div class="text-muted small mb-1">Telah Dibayar</div>
                                            <div class="fw-bold fs-5 text-success">Rp
                                                {{ number_format($p->sudah_dibayar, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div
                                        class="card border-0 shadow-sm h-100 bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                        <div class="card-body p-3 text-center">
                                            <div class="text-danger small fw-semibold mb-1">Sisa Hutang</div>
                                            <div class="fw-bold fs-5 text-danger">Rp
                                                {{ number_format($p->sisa_tagihan, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm mb-0">
                                <div class="card-header bg-white fw-bold py-3"><i
                                        class="bi bi-clock-history me-2 text-primary"></i>Catatan Pembayaran Midtrans</div>
                                <div class="card-body p-0">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="bg-light text-muted small">
                                            <tr>
                                                <th class="ps-4 py-2">Status Sistem</th>
                                                <th class="py-2">Trx ID Midtrans</th>
                                                <th class="text-end pe-4 py-2">Nominal Disetor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($p->histori_cicilan as $cicilan)
                                                <tr>
                                                    <td class="ps-4 small text-dark">
                                                        <i class="bi bi-check2-circle text-success me-1"></i> Sukses
                                                        Divalidasi
                                                    </td>
                                                    <td class="small font-monospace text-muted">
                                                        {{ $cicilan->pesanan_id_midtrans }}</td>
                                                    <td class="text-end pe-4 fw-semibold text-success">+ Rp
                                                        {{ number_format($cicilan->nominal_pembayaran, 0, ',', '.') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-4 text-muted small">Belum ada
                                                        pembayaran cicilan yang masuk.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

@endsection
