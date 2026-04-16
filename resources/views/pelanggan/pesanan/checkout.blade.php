@extends('layouts.pelanggan')

@section('title', 'Checkout Pesanan')

@section('content')
    <div style="max-width:1100px;margin:0 auto;" class="px-3 px-md-4 py-4 pb-5">

        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('pelanggan.pesanan.keranjang') }}"
                class="btn btn-link text-danger fw-semibold text-decoration-none p-0 small">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Keranjang
            </a>
            <h5 class="fw-bold mb-0">Checkout Pesanan</h5>
        </div>

        <form action="{{ route('pelanggan.pesanan.proses_checkout') }}" method="POST">
            @csrf
            @if (isset($produkTerpilih))
                @foreach ($produkTerpilih as $id)
                    <input type="hidden" name="produk_terpilih[]" value="{{ $id }}">
                @endforeach
            @endif
            
            <div class="row g-4">
                <div class="col-md-8">
                    {{-- Alamat Pengiriman --}}
                    <div class="card border-0 shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-white py-3">
                            <div class="fw-bold text-dark"><i class="bi bi-geo-alt me-2 text-danger"></i>Alamat Pengiriman
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="fw-bold mb-1">{{ $user->nama }}
                                <span class="fw-normal text-muted">({{ $user->nama_toko }})</span>
                            </div>
                            <div class="text-muted small mb-2">{{ $user->telepon }}</div>
                            <div class="text-dark small">{{ $user->alamat }}</div>
                            <hr class="opacity-25">
                            <a href="#" class="btn btn-outline-secondary btn-sm rounded-2 px-3"
                                style="font-size: 11px;">Ubah Alamat</a>
                        </div>
                    </div>

                    {{-- Rincian Produk --}}
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3">
                            <div class="fw-bold text-dark"><i class="bi bi-box-seam me-2 text-danger"></i>Rincian Produk
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table align-middle mb-0">
                                <tbody id="checkoutTableBody">
                                    @php $totalSubtotal = 0; @endphp
                                    @foreach ($keranjang as $item)
                                        @php $totalSubtotal += ($item->produk->harga * $item->jumlah); @endphp
                                        <tr class="cart-item">
                                            <td class="ps-4 py-3">
                                                <div class="d-flex gap-3 align-items-center">
                                                    <img src="{{ asset('storage/produk/' . $item->produk->gambar) }}"
                                                        class="rounded-3 bg-light"
                                                        style="width:50px;height:50px;object-fit:cover;">
                                                    <div>
                                                        <div class="fw-semibold small text-truncate"
                                                            style="max-width: 250px;">{{ $item->produk->nama_produk }}</div>
                                                        <div class="text-muted" style="font-size:11px;">{{ $item->jumlah }}
                                                            x Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end pe-4 fw-bold text-dark small">
                                                Rp {{ number_format($item->produk->harga * $item->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-body p-4">
                            <h6 class="fw-bold mb-3">Pilih Metode Pembayaran</h6>

                            <div class="d-flex flex-column gap-3 mb-4">

                                {{-- Opsi Cash --}}
                                <label class="w-100 position-relative cursor-pointer m-0">
                                    <input type="radio" name="pay" value="pay" checked class="position-absolute" style="opacity: 0; inset: 0; margin: 0; cursor: pointer; z-index: 10;">
                                    
                                    <div class="payment-card p-3 rounded-3 border d-flex align-items-center gap-3">
                                        <div class="radio-custom flex-shrink-0"></div>
                                        <div>
                                            <div class="fw-bold small">Bayar Sekarang (Cash)</div>
                                            <div class="text-muted" style="font-size:11px;">Transfer via Midtrans</div>
                                        </div>
                                    </div>
                                </label>

                                {{-- Opsi Kontrabon --}}
                                <label class="w-100 position-relative m-0 {{ Session::get('status_bengkel') != 1 ? 'opacity-50' : 'cursor-pointer' }}"
                                    style="{{ Session::get('status_bengkel') != 1 ? 'cursor: not-allowed;' : '' }}">

                                    <input type="radio" name="pay" value="kontrabon" class="position-absolute"
                                        style="opacity: 0; inset: 0; margin: 0; z-index: 10; {{ Session::get('status_bengkel') != 1 ? 'cursor: not-allowed;' : 'cursor: pointer;' }}"
                                        {{ Session::get('status_bengkel') != 1 ? 'disabled' : '' }}>

                                    <div class="payment-card p-3 rounded-3 border d-flex align-items-center gap-3">
                                        <div class="radio-custom flex-shrink-0"></div>
                                        <div class="w-100">
                                            <div class="fw-bold small d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-1">
                                                    Kontrabon
                                                    <span class="badge bg-primary bg-opacity-25 text-primary">Mitra</span>
                                                </div>
                                                @if (Session::get('status_bengkel') != 1)
                                                    <i class="bi bi-lock-fill text-secondary" title="Terkunci"></i>
                                                @endif
                                            </div>
                                            <div class="text-muted" style="font-size:10px;">
                                                Jatuh tempo 3 bulan
                                                @if (Session::get('status_bengkel') != 1)
                                                    <span class="text-danger ms-1 fst-italic">(Khusus Pelanggan Mitra)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </label>

                            </div>

                            <h6 class="fw-bold mb-3 border-top pt-3">Ringkasan Pembayaran</h6>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Total Harga ({{ count($keranjang) }} item)</span>
                                <span class="fw-semibold">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Biaya Pengiriman</span>
                                <span class="text-success fw-semibold">Gratis</span>
                            </div>
                            <hr class="opacity-25">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-bold text-dark">Total Bayar</span>
                                <h5 class="fw-bold text-danger mb-0">
                                    Rp {{ number_format($totalSubtotal, 0, ',', '.') }}
                                </h5>
                            </div>

                            <div class="card border-0 shadow-sm rounded-3 mb-4 bg-light">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-2 small"><i class="bi bi-pencil-square me-2"></i>Catatan Pesanan</h6>
                                    <textarea class="form-control form-control-sm" name="catatan" rows="2"
                                        placeholder="Tulis instruksi khusus pengiriman..."></textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-danger w-100 fw-bold py-2 shadow-sm rounded-3">
                                Konfirmasi Pesanan <i class="bi bi-check2-all ms-1"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 bg-white rounded-3 border-start border-4 border-warning shadow-sm">
                        <div class="d-flex gap-2">
                            <i class="bi bi-shield-check text-warning"></i>
                            <small class="text-muted" style="font-size: 11px;">Pesanan Anda dilindungi dan akan diproses
                                segera setelah pembayaran dikonfirmasi.</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .cursor-pointer {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-card {
            background-color: white;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease;
        }

        /* Lingkaran Radio Buatan */
        .radio-custom {
            width: 18px;
            height: 18px;
            border: 2px solid #adb5bd;
            border-radius: 50%;
            position: relative;
            background-color: white;
            transition: all 0.2s ease;
        }

        /* Merubah Card saat Dipilih */
        input[type="radio"]:checked + .payment-card {
            border: 1px solid #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.05) !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        /* Mengisi Lingkaran Radio saat Dipilih */
        input[type="radio"]:checked + .payment-card .radio-custom {
            border-color: #dc3545;
        }
        input[type="radio"]:checked + .payment-card .radio-custom::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: #dc3545;
            border-radius: 50%;
        }

        .payment-card:hover {
            border-color: #dc3545;
        }
    </style>
@endsection