@extends('layouts.pelanggan')

@section('title', 'Pembayaran Cicilan Kontrabon')

@section('content')

<div class="container py-4 pb-5 min-vh-100" style="max-width:1130px;">
    {{-- KEMBALI & HEADER --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pelanggan.pembayaran_kontrabon.index') }}" class="btn btn-light border shadow-sm rounded-3 px-3">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h5 class="fw-bold mb-0">Detail Tagihan Kontrabon</h5>
            {{-- PERBAIKAN 1: Menggunakan $kontrabon->id --}}
            <p class="text-muted mb-0 small font-monospace">{{ $kontrabon->id }}</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        {{-- BAGIAN KIRI: RINGKASAN TAGIHAN & RINCIAN PESANAN --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white p-4 border-bottom pb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-receipt-cutoff me-2 text-danger"></i>Ringkasan Tagihan</h6>
                </div>
                <div class="card-body p-4">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4">
                            <div class="bg-light p-3 rounded-3 text-center h-100 border">
                                <div class="text-muted small mb-1">Total Tagihan</div>
                                <div class="fw-bold text-dark">Rp {{ number_format($piutang->total_tagihan, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-success bg-opacity-10 border border-success border-opacity-25 p-3 rounded-3 text-center h-100">
                                <div class="text-success small fw-semibold mb-1">Telah Dibayar</div>
                                <div class="fw-bold text-success">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="bg-danger bg-opacity-10 border border-danger border-opacity-25 p-3 rounded-3 text-center h-100">
                                <div class="text-danger small fw-semibold mb-1">Sisa Hutang</div>
                                <div class="fw-bold text-danger">Rp {{ number_format($piutang->sisa_tagihan, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ $persentase }}%;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 11px;">
                        <span>0%</span>
                        <span class="fw-bold text-success">{{ number_format($persentase, 1) }}% Terbayar</span>
                        <span>100%</span>
                    </div>

                    <hr class="opacity-25 my-4">

                    <div class="d-flex align-items-start gap-3 p-3 {{ $isOverdue ? 'bg-danger border-danger text-danger' : 'bg-primary border-primary text-primary' }} bg-opacity-10 border border-opacity-25 rounded-3">
                        <i class="bi {{ $isOverdue ? 'bi-exclamation-octagon-fill' : 'bi-info-circle-fill' }} fs-4 mt-1"></i>
                        <div>
                            <div class="fw-bold small mb-1">
                                Status: Memasuki Bulan ke-{{ $bulanKe }} 
                                @if($isOverdue) <span class="badge bg-danger ms-1">JATUH TEMPO</span> @endif
                            </div>
                            <div class="text-secondary" style="font-size: 12px;">
                                Batas akhir pelunasan adalah <b>{{ \Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->format('d F Y') }}</b>. 
                                @if ($bulanKe >= 3 || $isOverdue)
                                    <span class="text-danger fw-semibold">Anda diwajibkan untuk segera melunasi seluruh sisa tagihan.</span>
                                @else
                                    Anda bebas menentukan nominal cicilan bulan ini sesuai dengan kemampuan Anda.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- AKORDEON DAFTAR PESANAN --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white p-4 border-bottom pb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam me-2 text-secondary"></i>Rincian Pesanan Tergabung</h6>
                </div>
                <div class="card-body p-0">
                    <div class="accordion accordion-flush" id="accordionPesanan">
                        @foreach($pesananList as $index => $pesanan)
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-3 px-4 bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                    <div class="w-100 d-flex justify-content-between align-items-center pe-3">
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $pesanan->nomor }}</div>
                                            <div class="text-muted" style="font-size:11px;">{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}</div>
                                        </div>
                                        <div class="fw-bold text-dark small">
                                            Total: Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#accordionPesanan">
                                <div class="accordion-body p-0">
                                    <div class="list-group list-group-flush">
                                        @foreach($pesanan->items as $item)
                                        <div class="list-group-item px-4 py-3 border-bottom border-light">
                                            <div class="d-flex gap-3">
                                                
                                                {{-- Kiri: Gambar Produk --}}
                                                <div class="flex-shrink-0">
                                                    @if(isset($item->gambar_produk) && $item->gambar_produk)
                                                        <img src="{{ asset('storage/produk/' . $item->gambar_produk) }}" 
                                                            class="rounded-3 border object-fit-cover shadow-sm" 
                                                            style="width: 60px; height: 60px;" alt="Foto Produk">
                                                    @else
                                                        <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted shadow-sm" 
                                                            style="width: 60px; height: 60px;">
                                                            <i class="bi bi-image fs-3 opacity-50"></i>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Kanan: Detail Produk --}}
                                                <div class="flex-grow-1 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <div class="fw-semibold text-dark" style="font-size: 13px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                            {{ $item->nama_produk }}
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center mt-1">
                                                            <div class="text-muted font-monospace text-uppercase" style="font-size: 11px;">
                                                                SKU: {{ $item->produk_id }}
                                                            </div>
                                                            <div class="text-secondary fw-semibold" style="font-size: 12px;">
                                                                x{{ $item->jumlah }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="text-end">
                                                        <span class="fw-bold text-dark" style="font-size: 14px;">Rp{{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- HISTORI PEMBAYARAN CICILAN --}}
            {{-- ========================================================= --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mt-4 mb-4">
                <div class="card-header bg-white p-4 border-bottom pb-3">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Histori Pembayaran</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @forelse($histori_cicilan as $histori)
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold text-dark small">
                                            {{ \Carbon\Carbon::parse($histori->updated_at)->format('d F Y') }} 
                                            <span class="text-muted ms-1">{{ \Carbon\Carbon::parse($histori->updated_at)->format('H:i') }} WIB</span>
                                        </div>
                                        <div class="text-muted font-monospace mt-1" style="font-size: 11px;">Trx ID: {{ $histori->pesanan_id_midtrans }}</div>
                                    </div>
                                    <div class="text-success fw-bold d-flex align-items-center gap-1">
                                        <i class="bi bi-plus-lg"></i> Rp {{ number_format($histori->nominal_pembayaran, 0, ',', '.') }}
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item px-4 py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-2 opacity-25 d-block mb-2"></i>
                                <span class="small">Belum ada riwayat pembayaran yang masuk.</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: FORM PEMBAYARAN DINAMIS --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 h-100 " style="top: 20px;">
                <div class="card-header bg-white p-4 border-bottom-0 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-credit-card me-2 text-danger"></i>Bayar Cicilan</h6>
                </div>
                <div class="card-body p-4 pt-3">

                    {{-- PERBAIKAN 2: Action Route menggunakan $kontrabon->id --}}
                    <form action="{{ route('pelanggan.pembayaran_kontrabon.proses', $kontrabon->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-semibold">Masukkan Nominal Pembayaran (Rp)</label>
                            
                            {{-- Input Grup dengan Rupiah --}}
                            <div class="input-group input-group-lg border rounded-3 overflow-hidden shadow-sm">
                                <span class="input-group-text bg-white border-0 fw-bold text-muted">Rp</span>
                                <input type="number" name="nominal_bayar" id="inputNominal" 
                                    class="form-control border-0 fw-bold text-dark fs-4" 
                                    placeholder="0" 
                                    min="10000" 
                                    max="{{ $piutang->sisa_tagihan }}" 
                                    value="{{ $piutang->sisa_tagihan }}" 
                                    required>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-2">
                                <div class="text-muted" style="font-size: 11px;">Min: Rp 10.000</div>
                                <div class="text-danger fw-semibold" style="font-size: 11px;" id="peringatanMax"></div>
                            </div>
                        </div>

                        {{-- Tombol Pintasan Nominal --}}
                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-secondary btn-sm w-100 py-2 rounded-3 set-nominal" data-nilai="{{ floor($piutang->sisa_tagihan / 2) }}">
                                    Bayar Setengah (50%)
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 py-2 rounded-3 fw-bold set-nominal" data-nilai="{{ $piutang->sisa_tagihan }}">
                                    Lunasi Semua
                                </button>
                            </div>
                        </div>

                        <hr class="opacity-25 mb-4">

                        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold rounded-3 shadow-sm fs-6 d-flex justify-content-center align-items-center gap-2" id="btnSubmit">
                            <i class="bi bi-shield-lock"></i> Bayar Sekarang
                        </button>

                        <div class="text-center mt-3 text-muted" style="font-size: 11px;">
                            <i class="bi bi-lock-fill text-success"></i> Pembayaran Anda dienkripsi dan diproses secara otomatis 24/7.
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputNominal = document.getElementById('inputNominal');
        const btnSetNominals = document.querySelectorAll('.set-nominal');
        const peringatanMax = document.getElementById('peringatanMax');
        const maxBayar = {{ $piutang->sisa_tagihan }};

        // Fungsi Pintasan Tombol Nominal
        btnSetNominals.forEach(btn => {
            btn.addEventListener('click', function() {
                inputNominal.value = this.getAttribute('data-nilai');
                cekMaximal();
            });
        });

        // Validasi saat ngetik
        inputNominal.addEventListener('input', cekMaximal);

        function cekMaximal() {
            let nilai = parseInt(inputNominal.value);
            if (nilai > maxBayar) {
                inputNominal.value = maxBayar;
                peringatanMax.textContent = "Maksimal pembayaran adalah sisa hutang Anda.";
            } else {
                peringatanMax.textContent = "";
            }
        }
    });
</script>

@endsection