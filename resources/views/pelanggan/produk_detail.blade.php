@extends('layouts.pelanggan')

@section('title', $item->nama_produk)

@section('content')
    <div class="py-3 mb-4"
        style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('pelanggan.index') }}"
                class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0 text-dark">Detail Produk</h4>
                <p class="text-muted mb-0 small">{{ $item->nama_produk }}</p>
            </div>
        </div>
    </div>
    <div class="px-3 px-md-4 py-4 pb-5" style="max-width:1200px; margin:0 auto;">
        <div class="row g-4">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="bg-white d-flex align-items-center justify-content-center p-3" style="min-height: 400px;">
                        @if ($item->gambar)
                            <img src="{{ asset('storage/produk/' . $item->gambar) }}" class="img-fluid rounded"
                                alt="{{ $item->nama_produk }}" id="mainImage">
                        @else
                            <i class="bi bi-gear-wide-connected opacity-25" style="font-size: 150px;"></i>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="ps-md-4">
                    <span class="badge bg-danger mb-2"
                        style="font-size: 11px; letter-spacing: 1px;">{{ strtoupper($item->kategori->nama) }}</span>
                    <h2 class="fw-bold text-dark mb-1">{{ $item->nama_produk }}</h2>
                    <p class="text-muted mb-3">Merek: <span class="fw-bold text-dark">{{ $item->brand->nama }}</span> |
                        Kode: <span class="fw-bold text-dark">{{ $item->kode_produk }}</span></p>

                    <h3 class="text-danger fw-bold mb-4">Rp {{ number_format($item->harga, 0, ',', '.') }} <small
                            class="text-muted fw-normal" style="font-size: 14px;">/ {{ $item->unit }}</small></h3>

                    <hr class="opacity-25">

                    <div class="row g-3 mb-4">
                        <div class="col-6 col-md-4">
                            <div class="small text-muted">Ketersediaan</div>
                            @if ($item->stok_produk > 0)
                                <div class="fw-bold text-success"><i class="bi bi-check-circle-fill me-1"></i> Stok Ready
                                    ({{ $item->stok_produk }})</div>
                            @else
                                <div class="fw-bold text-danger"><i class="bi bi-x-circle-fill me-1"></i> Stok Habis</div>
                            @endif
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="small text-muted">Model Kendaraan</div>
                            <div class="fw-bold text-dark">{{ $item->jenisMobil->nama ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold small text-muted mb-2">Deskripsi Produk</label>
                        <div class="text-dark" style="line-height: 1.6;">
                            {!! nl2br(e($item->deskripsi_produk ?? 'Tidak ada deskripsi untuk produk ini.')) !!}
                        </div>
                    </div>

                    @if ($item->stok_produk > 0)
                        <form action="{{ route('pelanggan.pesanan.keranjang.tambah') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->kode_produk }}">

                            <div class="d-flex gap-2">
                                <div style="width: 100px;">
                                    <input type="number" name="jumlah" class="form-control py-2" value="1"
                                        min="1" max="{{ $item->stok_produk }}" required>
                                </div>
                                <button type="submit" class="btn btn-danger btn-lg flex-grow-1 fw-bold">
                                    <i class="bi bi-cart-plus me-2"></i> Tambah ke Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <button class="btn btn-secondary btn-lg w-100 fw-bold" disabled>Produk Tidak Tersedia</button>
                    @endif

                    <div class="mt-4 p-3 bg-light rounded-3 border border-warning border-opacity-25">
                        <small class="text-muted"><i class="bi bi-info-circle-fill text-warning me-1"></i> Pelanggan Mitra
                            CV Jaya Abadi dapat menggunakan metode pembayaran <strong>Kontrabón 3 Bulan</strong> untuk
                            produk ini.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
