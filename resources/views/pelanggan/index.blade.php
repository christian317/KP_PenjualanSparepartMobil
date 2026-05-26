@extends('layouts.pelanggan')

@section('title', 'Katalog Sparepart')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <section
        style="background: linear-gradient(135deg, #1A2332 0%, #243040 60%, #2c3e50 100%); padding: 36px 28px 32px; position: relative; overflow: hidden;">
        <div
            style="position:absolute;top:-60px;right:-60px;width:300px;height:300px;border-radius:50%;background:rgba(214,40,40,0.07);pointer-events:none;">
        </div>
        <div
            style="position:absolute;bottom:-80px;left:10%;width:200px;height:200px;border-radius:50%;background:rgba(247,127,0,0.05);pointer-events:none;">
        </div>

        <div class="container-fluid px-0" style="max-width:1400px; margin:0 auto; position:relative; z-index:1;">
            <div class="d-flex align-items-start justify-content-between gap-4 flex-wrap">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-2 d-flex align-items-center justify-content-center bg-danger"
                            style="width:36px;height:36px;font-size:18px;">
                            <i class="bi bi-tools text-white"></i>
                        </div>
                        <h4 class="fw-bold text-white mb-0" style="font-size:24px; letter-spacing:-.3px;">
                            Katalog Sparepart Mobil
                        </h4>
                    </div>

                    <p class="mb-3" style="color:rgba(255,255,255,0.5); font-size:13.5px;">
                        Ratusan produk berkualitas tersedia untuk kendaraan Anda
                    </p>

                    @if (Session::get('status_mitra') != 1)
                        <span class="badge border px-3 py-2 mt-2"
                            style="background:rgba(255,255,255,0.1); color:#ced4da; border-color:rgba(255,255,255,0.2) !important; font-size:12px;">
                            <i class="bi bi-person me-1"></i>Pelanggan Reguler
                        </span>
                    @else
                        <div class="mb-2 ">
                            <span class="badge border px-3 py-2 shadow-sm"
                                style="background:rgba(247,127,0,0.15); color:#F77F00; border-color:rgba(247,127,0,0.3) !important; font-size:12px;">
                                <i class="bi bi-star-fill me-1"></i>Pelanggan Mitra
                            </span>
                        </div>
                    @endif
                </div>

                {{-- INFORMASI TOTAL HUTANG (LIMIT DIHAPUS) --}}
                @if (Session::get('status_mitra') == 1)
                    <div style="min-width:300px; max-width:380px;">
                        <div class="p-3 rounded-3 shadow-sm"
                            style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(10px);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-white-50 fw-semibold mb-1" style="font-size: 11px;">
                                        Total Hutang Berjalan
                                    </div>
                                    <div class="text-white fw-bold" style="font-size: 20px;">
                                        Rp {{ number_format($totalHutang ?? 0, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-wallet2 text-white fs-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            </div>
        </div>
    </section>

    <div class="container-fluid px-3 px-md-4 py-4" style="max-width:1400px; margin:0 auto;">

        <form action="{{ route('pelanggan.index') }}" method="GET" id="formFilter"
            class="bg-white rounded-3 shadow-sm p-3 mb-4 d-flex flex-wrap gap-2 align-items-center border">
            
            <div class="input-group input-group-sm flex-grow-1" style="max-width:380px; min-width:180px;">
                <span class="input-group-text bg-light border-end-0 text-secondary"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control bg-light border-start-0 ps-0" placeholder="Cari produk, merek, kode…">
                <button type="submit" class="btn btn-danger px-3"><i class="bi bi-search"></i></button>
            </div>

            <select name="kategori" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $k)
                    <option value="{{ $k->id }}" {{ request('kategori') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>

            <select name="brand" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Semua Brand</option>
                @foreach ($brand as $b)
                    <option value="{{ $b->id }}" {{ request('brand') == $b->id ? 'selected' : '' }}>
                        {{ $b->nama }}
                    </option>
                @endforeach
            </select>

            <div style="min-width: 220px; max-width: 300px;">
                <select name="mobil" class="form-select select2-mobil">
                    <option value="">🚗 Semua Mobil</option>
                    @foreach ($jenis_mobil as $m)
                        <option value="{{ $m->id }}" {{ request('mobil') == $m->id ? 'selected' : '' }}>
                            {{ $m->merk }} {{ $m->nama_model }} {{ $m->tahun_mobil ? '(' . $m->tahun_mobil . ')' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('pelanggan.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-arrow-clockwise me-1"></i>Reset
            </a>

            <div class="ms-auto text-muted small">
                Menampilkan <strong class="text-dark">{{ $produk->total() }}</strong> produk
            </div>
        </form>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach ($produk as $item)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden product-card">
                        <a href="{{ route('pelanggan.detail_produk', $item->id) }}" class="text-decoration-none">
                            <div class="position-relative overflow-hidden" style="height:160px; background:#f4f6f9;">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/produk/' . $item->gambar) }}" class="w-100 h-100"
                                        style="object-fit:cover; transition:transform .35s;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-gear-wide-connected text-secondary"
                                            style="font-size:52px; opacity:.2;"></i>
                                    </div>
                                @endif

                                @if ($item->stok > 0)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-success"
                                        style="font-size:10px;">
                                        <i class="bi bi-check-circle me-1"></i>{{ $item->stok }} tersedia
                                    </span>
                                @else
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-secondary"
                                        style="font-size:10px;">
                                        <i class="bi bi-x-circle me-1"></i>Habis
                                    </span>
                                @endif

                                @if ($item->preorder == 1)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-warning text-dark"
                                        style="font-size:10px;">
                                        <i class="bi bi-clock-history me-1"></i>Pre-order
                                    </span>
                                @endif

                                <div class="product-img-overlay position-absolute top-0 start-0 w-100 h-100"
                                    style="background:rgba(26,35,50,0); transition:background .25s;"></div>
                            </div>
                        </a>

                        <div class="card-body p-3 d-flex flex-column">

                            <div class="text-danger fw-bold text-uppercase mb-1"
                                style="font-size:10px; letter-spacing:.6px;">
                                {{ $item->kategori->nama }}
                            </div>

                            <a href="{{ route('pelanggan.detail_produk', $item->id) }}"
                                class="text-decoration-none text-dark">
                                <div class="fw-bold mb-1 lh-sm"
                                    style="font-size:13.5px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;"
                                    title="{{ $item->nama }}">
                                    {{ $item->nama }}
                                </div>
                            </a>

                            <div class="text-muted mb-2 d-flex align-items-center gap-1" style="font-size:11px;">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border"
                                    style="font-size:10px;">{{ $item->brand->nama }}</span>
                                <span>·</span>
                                <span class="font-monospace">{{ $item->id }}</span>
                            </div>

                            <div class="mb-3">
                                @if ($item->jenisMobil->count() > 0)
                                    <div class="text-primary fw-bold mb-1"
                                        style="font-size:9.5px; letter-spacing:.4px; text-transform:uppercase;">
                                        <i class="bi bi-car-front me-1"></i>Cocok untuk:
                                    </div>
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach ($item->jenisMobil->take(2) as $mobil)
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"
                                                style="font-size:9px;">
                                                {{ $mobil->nama_model }}
                                            </span>
                                        @endforeach
                                        @if ($item->jenisMobil->count() > 2)
                                            <span class="badge bg-light text-secondary border" style="font-size:9px;">
                                                +{{ $item->jenisMobil->count() - 2 }} lainnya
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border"
                                        style="font-size:9px;">
                                        <i class="bi bi-infinity me-1"></i>Universal / Umum
                                    </span>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <div class="fw-bold text-dark mb-2" style="font-size:16px; letter-spacing:-.2px;">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <small class="fw-normal text-muted"
                                        style="font-size:11px;">/{{ $item->unit }}</small>
                                </div>
@if ($item->stok > 0 && $item->preorder == 0)
                                    {{-- KONDISI 1: BARANG NORMAL & STOK TERSEDIA --}}
                                    <form action="{{ route('pelanggan.checkout.keranjang.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit"
                                            class="btn btn-danger btn-sm w-100 fw-semibold rounded-2 d-flex align-items-center justify-content-center gap-1">
                                            <i class="bi bi-cart-plus"></i> Keranjang
                                        </button>
                                    </form>

                                @elseif ($item->preorder == 1)
                                    {{-- KONDISI 2: BARANG PRE-ORDER (BISA DIBELI MESKI STOK 0 ATAU > 0) --}}
                                    <form action="{{ route('pelanggan.checkout.keranjang.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <button type="submit"
                                            class="btn btn-outline-danger btn-sm w-100 fw-bold rounded-2 d-flex align-items-center justify-content-center gap-1">
                                            <i class="bi bi-clock-history"></i> Pre-Order
                                        </button>
                                    </form>

                                @else
                                    {{-- KONDISI 3: BARANG NORMAL & STOK HABIS (TOMBOL MATI) --}}
                                    <button type="button"
                                        class="btn btn-secondary btn-sm w-100 fw-semibold rounded-2 d-flex align-items-center justify-content-center gap-1" disabled>
                                        <i class="bi bi-x-circle"></i> Habis
                                    </button>
                                @endif
                            </div>
                        </div>

                        <div class="bg-danger" style="height:3px; opacity:0; transition:opacity .25s;"
                            class="product-bar"></div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($produk->total() == 0)
            <div class="text-center py-5 my-4">
                <div class="mb-3" style="font-size:64px; opacity:.2;">🔍</div>
                <h5 class="fw-bold text-muted">Produk tidak ditemukan</h5>
                <p class="text-muted small">Coba ubah kata kunci atau filter pencarian</p>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-danger mt-1">
                    <i class="bi bi-arrow-left me-1"></i>Lihat Semua Produk
                </a>
            </div>
        @endif

        <div class="d-flex justify-content-center mt-5">
            {{ $produk->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .product-card {
            transition: transform .25s ease, box-shadow .25s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.13) !important;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .product-card:hover .product-img-overlay {
            background: rgba(26, 35, 50, 0.04) !important;
        }

        .product-card:hover .bg-danger.product-bar,
        .product-card:hover>.bg-danger:last-child {
            opacity: 1 !important;
        }

        /* ── Pagination Styling ── */
        .pagination .page-link {
            color: #D62828;
            border-color: #e9ecef;
            border-radius: 8px;
            margin: 0 2px;
            font-weight: 600;
            font-size: 13px;
        }

        .pagination .page-item.active .page-link {
            background-color: #D62828;
            border-color: #D62828;
            color: #fff;
        }

        .pagination .page-link:hover {
            background-color: #fff0f0;
            border-color: #D62828;
            color: #D62828;
        }

        .pagination .page-item.disabled .page-link {
            color: #ccc;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #D62828;
            box-shadow: 0 0 0 3px rgba(214, 40, 40, .1);
        }
        
        /* Modifikasi untuk menyamakan tinggi Select2 dengan form-select-sm Bootstrap */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: calc(1.5em + 0.5rem + 2px) !important;
            padding: 0.25rem 0.5rem !important;
            font-size: .875rem !important;
            border-color: #dee2e6;
        }
        
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #D62828 !important;
            box-shadow: 0 0 0 3px rgba(214, 40, 40, .1) !important;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        @if (Session::has('toast_success'))
            document.addEventListener("DOMContentLoaded", function() {
                showToast("{{ Session::get('toast_success') }}");
            });
        @endif

        $(document).ready(function() {
            // Inisialisasi Select2
            $('.select2-mobil').select2({
                theme: 'bootstrap-5',
                placeholder: "🚗 Cari Jenis Mobil...",
                allowClear: true,
                width: '100%'
            });

            $('.select2-mobil').on('select2:select select2:unselect', function (e) {
                $('#formFilter').submit();
            });
        });
    </script>
@endsection