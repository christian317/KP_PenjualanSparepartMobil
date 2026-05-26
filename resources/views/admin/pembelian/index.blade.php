@extends('layouts.app')

@section('title', 'Riwayat Pembelian')

@section('content')
    <div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-box-arrow-in-down me-2" style="color: #1565c0;"></i>Riwayat Pembelian Stok
                    </div>
                    <div class="text-muted small">Manajemen stok masuk (restock) dari supplier</div>
                </div>
                <div>
                    <a href="{{ route('admin.pembelian.create') }}"
                        class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Input Pembelian
                    </a>
                </div>
            </div>
        </div>

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FORM FILTER 1 BARIS (COMPACT) --}}
        <form action="{{ route('admin.pembelian.index') }}" method="GET"
            class="bg-white p-2 rounded-3 shadow-sm mb-4 d-flex flex-wrap flex-xl-nowrap gap-2 align-items-center border">

            <div class="input-group input-group-sm flex-grow-1" style="min-width: 150px; max-width: 250px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control bg-light border-start-0" placeholder="Cari faktur / supplier...">
            </div>

            <input type="date" name="tanggal" class="form-control form-control-sm w-auto text-muted"
                value="{{ request('tanggal') }}" title="Pilih Tanggal" required>

            <select name="bulan" class="form-select form-select-sm w-auto text-muted">
                <option value="">Bulan...</option>
                @php
                    $bulanSekarang = request('bulan');
                    $namaBulan = [
                        '01' => 'Jan',
                        '02' => 'Feb',
                        '03' => 'Mar',
                        '04' => 'Apr',
                        '05' => 'Mei',
                        '06' => 'Jun',
                        '07' => 'Jul',
                        '08' => 'Agt',
                        '09' => 'Sep',
                        '10' => 'Okt',
                        '11' => 'Nov',
                        '12' => 'Des',
                    ];
                @endphp
                @foreach ($namaBulan as $val => $name)
                    <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>{{ $name }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="form-select form-select-sm w-auto text-muted">
                <option value="">Tahun...</option>
                @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}
                    </option>
                @endfor
            </select>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <a href="{{ route('admin.pembelian.index') }}" class="btn btn-light border btn-sm px-2 text-secondary"
                    title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>

            <div class="ms-auto text-muted small" style="white-space: nowrap;">
                <b class="text-dark">{{ $pembelian->firstItem() ?? 0 }}</b> - <b
                    class="text-dark">{{ $pembelian->lastItem() ?? 0 }}</b> dari {{ $pembelian->total() }}
            </div>
        </form>

        {{-- TABLE DATA --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NO. FAKTUR</th>
                            <th class="py-3 border-0">TANGGAL</th>
                            <th class="py-3 border-0">SUPPLIER</th>
                            <th class="py-3 border-0 text-center">TOTAL ITEM</th>
                            <th class="py-3 border-0 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembelian as $p)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-bold small text-dark">{{ $p->id }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold small text-dark">{{ $p->nama_supplier }}</div>
                                </td>
                                <td class="text-center">
                                    <span
                                    
                                        class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 fw-semibold">
                                        {{ $p->details->sum('jumlah') }} Unit
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-light btn-sm border text-primary px-2 py-1 rounded-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDetailBeli{{ str_replace(['/', '-'], '', $p->id) }}">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 opacity-50 d-block mb-2"></i>
                                    Belum ada data riwayat pembelian stok.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">
                    Halaman {{ $pembelian->currentPage() }} dari {{ $pembelian->lastPage() }}
                </span>

                <div class="m-0">
                    @if ($pembelian->hasPages())
                        {{ $pembelian->links('pagination::bootstrap-5') }}
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

    {{-- ================= MODAL DETAIL PEMBELIAN ================= --}}
    @foreach ($pembelian as $p)
        <div class="modal fade" id="modalDetailBeli{{ str_replace(['/', '-'], '', $p->id) }}" tabindex="-1"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg rounded-4">

                    <div class="modal-header border-bottom bg-light rounded-top-4 py-3 px-4">
                        <div>
                            <h5 class="modal-title fw-bold text-dark mb-0">Detail Faktur Pembelian</h5>
                            <div class="text-muted small mt-1 font-monospace">{{ $p->id }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3 mb-4 bg-light rounded-3 p-3 mx-0 border">
                            <div class="col-md-6">
                                <div class="text-muted fw-semibold" style="font-size:11px; letter-spacing:0.5px;">NAMA
                                    SUPPLIER</div>
                                <div class="fw-bold text-dark mt-1">{{ $p->nama_supplier }}</div>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="text-muted fw-semibold" style="font-size:11px; letter-spacing:0.5px;">TANGGAL
                                    MASUK</div>
                                <div class="fw-bold text-dark mt-1">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d F Y') }}</div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Rincian Stok Masuk</h6>

                        <div class="table-responsive border rounded-3 mb-4">
                            <table class="table table-hover table-borderless align-middle mb-0">
                                <thead class="table-light border-bottom">
                                    <tr>
                                        <th class="text-muted small fw-semibold ps-3">Kode Produk</th>
                                        <th class="text-muted small fw-semibold">Nama Sparepart</th>
                                        <th class="text-center text-muted small fw-semibold">Brand</th>
                                        <th class="text-center text-muted small fw-semibold pe-3" width="20%">Jumlah
                                            Masuk</th>
                                    </tr>
                                </thead>
                                <tbody class="small">
                                    @foreach ($p->details as $det)
                                        <tr>
                                            <td class="ps-3 py-3">
                                                <code class="text-secondary fw-bold">{{ $det->produk->id }}</code>
                                            </td>
                                            <td class="fw-semibold text-dark">
                                                {{ $det->produk->nama }}
                                            </td>
                                            <td class="text-center">
                                                {{ $det->produk->brand->nama }}
                                            </td>
                                            <td class="text-center pe-3">
                                                <span class="badge bg-dark px-3 py-2 rounded-pill">{{ $det->jumlah }}
                                                    Unit</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if ($p->catatan)
                            <div class="text-muted fw-semibold mb-2" style="font-size:12px;">CATATAN PEMBELIAN</div>
                            <div class="p-3 bg-light rounded-3 border text-secondary small">
                                {{ $p->catatan }}
                            </div>
                        @else
                            <div class="text-muted fw-semibold mb-2" style="font-size:12px;">CATATAN PEMBELIAN</div>
                            <div class="p-3 bg-light rounded-3 border text-secondary small fst-italic opacity-75">
                                Tidak ada catatan yang dilampirkan pada faktur ini.
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-top-0 pt-0 pb-4 px-4 justify-content-end">
                        <button type="button" class="btn btn-light border fw-semibold rounded-3 px-4"
                            data-bs-dismiss="modal">Tutup</button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

@endsection
