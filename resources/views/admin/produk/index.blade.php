@extends('layouts.app')

@section('title', 'Kelola Produk')
@section('page', 'Kelola Produk')

@section('content')
    <div class=" p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-box-seam me-2" style="color: #dc3545;"></i>Produk & Stok
                    </div>
                    <div class="text-muted small">Kelola katalog produk dan stok sparepart</div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    {{-- TOMBOL UTAMA (Primary Action) --}}
                    <a href="{{ route('admin.produk.create') }}"
                        class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                    </a>

                    {{-- TOMBOL SEKUNDER (Secondary Actions) --}}
                    <a href="{{ route('admin.produk.brand.create') }}"
                        class="btn btn-light border btn-sm px-3 py-2 fw-medium rounded-3 d-flex align-items-center gap-2 hover-shadow">
                        <i class="bi bi-tags text-muted me-1"></i> Tambah Brand
                    </a>

                    <a href="{{ route('admin.produk.kategori.create') }}"
                        class="btn btn-light border btn-sm px-3 py-2 fw-medium rounded-3 d-flex align-items-center gap-2 hover-shadow">
                        <i class="bi bi-folder2-open text-muted me-1"></i> Tambah Kategori
                    </a>

                    <a href="{{ route('admin.produk.jenis_mobil.create') }}"
                        class="btn btn-light border btn-sm px-3 py-2 fw-medium rounded-3 d-flex align-items-center gap-2 hover-shadow">
                        <i class="bi bi-car-front text-muted me-1"></i> Tambah Jenis Mobil
                    </a>
                </div>
            </div>

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

            <form action="{{ route('admin.produk.index') }}" method="GET"
                class="bg-white p-3 rounded-3 shadow-sm mb-3 d-flex flex-wrap gap-2 align-items-center">
                <div class="input-group input-group-sm " style="max-width: 300px;">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                        placeholder="Cari produk, SKU, merek…">
                </div>

                <select name="kategori" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $k)
                        <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>

                <select name="stok" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="">Semua Stok</option>
                    <option value="ok" {{ request('stok') == 'ok' ? 'selected' : '' }}>Stok OK</option>
                    <option value="menipis" {{ request('stok') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                </select>

                <a href="{{ route('admin.produk.index') }}"
                    class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                    <i class="bi bi-x"></i> Reset
                </a>
                <div class="ms-auto text-muted small">
                    Menampilkan <b class="text-dark">{{ $produk->firstItem() ?? 0 }}</b> sampai <b
                        class="text-dark">{{ $produk->lastItem() ?? 0 }}</b> dari {{ $produk->total() }} produk
                </div>
            </form>

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
                                <th class="py-3 border-0">STATUS</th>
                                <th class="py-3 border-0">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produk as $item)
                                <tr>
                                    <td class="ps-3">
                                        <img src="{{ $item->gambar ? asset('storage/produk/' . $item->gambar) : asset('images/no-image.png') }}"
                                            class="bg-light rounded-2"
                                            style="width: 46px; height: 46px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-0" style="font-size: 14px;">{{ $item->nama_produk }}</div>
                                        <code class="text-secondary fw-bold"
                                            style="font-size: 14px;">{{ $item->kode_produk ?? 'N/A' }}</code>
                                    </td>
                                    <td>
                                        <div class="fw-bold mb-0" style="font-size: 14px;">{{ $item->brand->nama }}</div>
                                        <span class="text-secondary fw-bold"
                                            style="font-size: 13px;">{{ $item->kategori->nama }}</span>
                                    </td>
                                    <td class="fw-bold text-danger" style="font-size: 13.5px;">Rp
                                        {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start">
                                            {{-- LOGIKA WARNA BADGE OTOMATIS --}}
                                            @if ($item->stok_produk <= 0)
                                                <span
                                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 fw-bold mb-1">
                                                    <i class="bi bi-x-circle me-1"></i>Habis (0)
                                                </span>
                                            @elseif ($item->stok_produk <= $item->min_stok)
                                                <span
                                                    class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-2 py-1 fw-bold mb-1">
                                                    <i
                                                        class="bi bi-exclamation-triangle me-1"></i>{{ $item->stok_produk }}
                                                    pcs
                                                </span>
                                            @else
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-bold mb-1">
                                                    {{ $item->stok_produk }} pcs
                                                </span>
                                            @endif

                                            {{-- INFO MINIMUM STOK --}}
                                            <div class="text-muted mt-1" style="font-size: 11px;">
                                                Min: <span class="fw-bold text-secondary">{{ $item->min_stok }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.produk.update_status', $item->kode_produk) }}"
                                            method="POST" class="m-0 form-update-status">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch d-flex mb-1">
                                                <input name="status_produk"
                                                    class="form-check-input cursor-pointer toggle-status" type="checkbox"
                                                    value="1" {{ $item->status_produk ? 'checked' : '' }}
                                                    style="transform: scale(1.3); cursor: pointer;"
                                                    onchange="this.closest('form').submit();">
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm gap-1">
                                            <a href="{{ route('admin.produk.edit', $item->kode_produk) }}"
                                                class="btn btn-light text-primary border-0 rounded-2 p-1 px-2">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            {{-- Jika ada tombol hapus --}}
                                            {{-- <form action="{{ route('admin.produk.destroy', $item->kode_produk) }}"
                                            method="POST" style="display:inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-light text-danger..."
                                                onclick="return confirm('Hapus produk ini?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form> --}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div
                    class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                    <span class="text-muted" style="font-size: 12px;">Halaman {{ $produk->currentPage() }} dari
                        {{ $produk->lastPage() }} · {{ $produk->total() }} produk total</span>
                    <div class="m-0">
                        @if ($produk->hasPages())
                            {{-- Jika halaman lebih dari 1, gunakan pagination bawaan Laravel --}}
                            {{ $produk->links('pagination::bootstrap-5') }}
                        @else
                            {{-- Jika hanya 1 halaman, tampilkan pagination statis (dummy) --}}
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">&lsaquo;</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">&rsaquo;</span>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection
