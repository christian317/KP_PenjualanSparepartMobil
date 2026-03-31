@extends('layouts.pelanggan')

@section('title', 'Katalog Sparepart')

@section('content')

    <section class="py-5 px-4" style="background: linear-gradient(135deg, #1A2332 0%, #2c3e50 100%);">
        <div class="container-fluid px-2" style="max-width:1400px; margin:0 auto;">
            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <h4 class="fw-bold text-white mb-2">🔧 Katalog Sparepart Mobil</h4>
                    <p class="text-white-50 mb-3">Ratusan produk berkualitas tersedia untuk kendaraan Anda</p>
                    <span class="badge border px-3 py-2"
                        style="background:rgba(247,127,0,0.15); color:#F77F00; border-color:#F77F00 !important; font-size:12px;">
                        <i class="bi bi-star-fill me-1"></i>Pelanggan Mitra – Tersedia Kontrabon 3 Bulan
                    </span>
                </div>
                <div class="text-white opacity-10 d-none d-md-block" style="font-size:80px; line-height:1;">⚙️</div>
            </div>
        </div>
    </section>

    <div class="px-0 px-md-2" style="max-width:1400px; margin:0 auto;">

        <form action="{{ route('pelanggan.index') }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-4 d-flex flex-wrap gap-2 align-items-center">

            <div class="input-group input-group-sm" style="max-width: 400px;">
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control bg-light border-start-0" placeholder="Cari produk, merek, kode…">

                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-search"></i>
                </button>
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

            <a href="{{ route('pelanggan.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-x"></i> Reset
            </a>

            <div class="ms-auto text-muted small">
                Menampilkan <strong class="text-dark">{{ $produk->total() }}</strong> produk
            </div>
        </form>

        <div class="row row-cols-2 row-cols-lg-4 g-3">
            @foreach ($produk as $item)
                <div class="col">
                    <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden product-card">
                        <a href="{{ route('pelanggan.detail_produk', $item->kode_produk) }}" class="text-decoration-none">
                            <div class="d-flex align-items-center justify-content-center position-relative bg-light"
                                style="height:150px;">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/produk/' . $item->gambar) }}" class="img-fluid h-100 w-100"
                                        style="object-fit: cover;">
                                @else
                                    <div class="text-center opacity-25">
                                        <i class="bi bi-gear-wide-connected" style="font-size:48px;"></i>
                                    </div>
                                @endif

                                @if ($item->stok_produk <= 0)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-secondary">Habis</span>
                                @elseif($item->stok_produk <= 5)
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Stok Tipis:
                                        {{ $item->stok_produk }}</span>
                                @else
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-success">Stok:
                                        {{ $item->stok_produk }}</span>
                                @endif
                            </div>
                        </a>

                        <div class="card-body p-3 d-flex flex-column">
                            <div class="text-danger fw-semibold mb-1 text-uppercase"
                                style="font-size:10px; letter-spacing: 0.5px;">
                                {{ $item->kategori->nama }}
                            </div>

                            <a href="{{ route('pelanggan.detail_produk', $item->kode_produk) }}" class="text-decoration-none text-dark">
                                <div class="fw-bold mb-1 small text-truncate" title="{{ $item->nama_produk }}">
                                    {{ $item->nama_produk }}
                                </div>
                            </a>

                            <div class="text-muted mb-2" style="font-size:11px;">
                                {{ $item->brand->nama }} · Kode: {{ $item->kode_produk }}
                            </div>

                            <div class="mt-auto">
                                <div class="fw-bold text-dark mb-3">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <small class="fw-normal text-muted"
                                        style="font-size: 10px;">/{{ $item->unit }}</small>
                                </div>

                                @if ($item->stok_produk > 0)
                                    <form action="{{ route('pelanggan.pesanan.keranjang.tambah') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $item->kode_produk }}">
                                        <button type="submit" class="btn btn-danger btn-sm w-100 fw-semibold rounded-2 shadow-sm">
                                            <i class="bi bi-cart-plus me-1"></i> Keranjang
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm w-100 fw-semibold rounded-2" disabled>
                                        <i class="bi bi-x-circle me-1"></i> Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-5">
            {{ $produk->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection