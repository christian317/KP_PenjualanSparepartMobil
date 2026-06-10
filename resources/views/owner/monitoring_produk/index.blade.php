@extends('layouts.app')

@section('title', 'Monitoring Produk')

@section('content')
    <div class=" p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-eye-fill me-2 text-primary"></i>Monitoring Produk & Stok
                    </div>
                    <div class="text-muted small">Pantau katalog produk dan ketersediaan stok sparepart di gudang</div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    {{-- TOMBOL TAMBAH DIHAPUS - DIGANTI BADGE MONITORING --}}
                    <span class="badge bg-primary bg-opacity-10 text-primary px-4 py-2 border border-primary border-opacity-25 fw-bold d-flex align-items-center" style="font-size: 13px;">
                        <i class="bi bi-shield-lock-fill me-2 fs-6"></i> Mode Pengawasan (Read-Only)
                    </span>
                </div>
            </div>
        </div>

        {{-- STATISTIK CARDS (SAMA PERSIS DENGAN ADMIN GUDANG) --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #1565c0 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Total Produk</div>
                                <div class="h3 fw-bold mb-0">{{ $totalProduk }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">{{ $kategori->count() }} kategori
                                </div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e3f2fd; width: 40px; height: 40px;">
                                <i class="bi bi-box-seam fs-5" style="color: #1565c0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #2d6a4f !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Produk Aktif</div>
                                <div class="h3 fw-bold mb-0">{{ $produkAktif }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Tampil di katalog</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e8f5e9; width: 40px; height: 40px;">
                                <i class="bi bi-check-circle fs-5" style="color: #2d6a4f;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #f57c00 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Stok Menipis</div>
                                <div class="h3 fw-bold mb-0 text-warning">{{ $stokMenipis }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Mencapai Batas Min.</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #fff3e0; width: 40px; height: 40px;">
                                <i class="bi bi-exclamation-triangle fs-5" style="color: #f57c00;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #dc3545 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Stok Habis</div>
                                <div class="h3 fw-bold mb-0 text-danger">{{ $stokHabis }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Perlu restock</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #ffebee; width: 40px; height: 40px;">
                                <i class="bi bi-x-circle fs-5" style="color: #dc3545;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM FILTER & SEARCH (SAMA PERSIS) --}}
        <form action="{{ url()->current() }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-3 d-flex flex-wrap gap-2 align-items-center">
            <div class="input-group input-group-sm " style="max-width: 300px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                    placeholder="Cari produk, SKU, merek…">
            </div>

            <select name="kategori" class="form-select form-select-sm w-auto">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-select form-select-sm w-auto">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <select name="stok" class="form-select form-select-sm w-auto">
                <option value="">Semua Stok</option>
                <option value="ok" {{ request('stok') == 'ok' ? 'selected' : '' }}>Stok OK</option>
                <option value="menipis" {{ request('stok') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
            </select>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <a href="{{ url()->current() }}" class="btn btn-light border btn-sm px-2 text-secondary"
                    title="Reset Filter">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
            <div class="ms-auto text-muted small">
                Menampilkan <b class="text-dark">{{ $produk->firstItem() ?? 0 }}</b> sampai <b
                    class="text-dark">{{ $produk->lastItem() ?? 0 }}</b> dari {{ $produk->total() }} produk
            </div>
        </form>

        {{-- TABEL DATA --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">FOTO</th>
                            <th class="py-3 border-0">NAMA PRODUK</th>
                            <th class="py-3 border-0">IDENTITAS</th>
                            <th class="py-3 border-0 ">HARGA</th>
                            <th class="py-3 border-0">STOK</th>
                            <th class="py-3 border-0 text-center pe-3">STATUS KATALOG</th>
                            {{-- KOLOM AKSI DIHAPUS --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produk as $item)
                            <tr>
                                <td class="ps-3">
                                    <img src="{{ $item->gambar ? asset('storage/produk/' . $item->gambar) : asset('images/no-image.png') }}"
                                        class="bg-light rounded-2" style="width: 46px; height: 46px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="fw-bold mb-0" style="font-size: 14px;">
                                        {{ $item->nama }}
                                    </div>

                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <code class="text-secondary" style="font-size: 13px;">
                                            SKU: {{ $item->id }}
                                        </code>

                                        @if ($item->preorder == 1)
                                            <span class="badge bg-warning text-dark rounded-pill"
                                                style="font-size: 10px;">
                                                Pre Order
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold mb-0" style="font-size: 14px;">{{ $item->brand->nama }}</div>
                                    <span class="text-secondary"
                                        style="font-size: 13px;">Kategori: {{ $item->kategori->nama }}</span>
                                </td>
                                <td class="fw-bold text-danger" style="font-size: 13.5px;">Rp
                                    {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td>
                                    <div class="d-flex flex-column align-items-start">
                                        {{-- LOGIKA WARNA BADGE STOK (SAMA PERSIS) --}}
                                        @if ($item->stok <= 0)
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 fw-bold mb-1">
                                                <i class="bi bi-x-circle me-1"></i>Habis (0)
                                            </span>
                                        @elseif ($item->stok <= $item->min_stok)
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-2 py-1 fw-bold mb-1">
                                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $item->stok }} pcs
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-bold mb-1">
                                                {{ $item->stok }} pcs
                                            </span>
                                        @endif

                                        {{-- INFO MINIMUM STOK --}}
                                        <div class="text-muted mt-1" style="font-size: 11px;">
                                            Min: <span class="fw-bold text-secondary">{{ $item->min_stok }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center pe-3">
                                    {{-- FORM SWITCH DIHAPUS - DIGANTI BADGE STATIS --}}
                                    @if ($item->status)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 fw-semibold">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-1 fw-semibold">
                                            Nonaktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted"> {{-- colspan jadi 6 karena aksi dihapus --}}
                                    <i class="bi bi-inbox fs-2 opacity-50 d-block mb-2"></i>
                                    Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">Halaman {{ $produk->currentPage() }} dari
                    {{ $produk->lastPage() }} · {{ $produk->total() }} produk total</span>
                <div class="m-0">
                    @if ($produk->hasPages())
                        {{ $produk->links('pagination::bootstrap-5') }}
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
@endsection