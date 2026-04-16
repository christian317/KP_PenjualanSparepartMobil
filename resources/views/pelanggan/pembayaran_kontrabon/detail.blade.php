@extends('layouts.pelanggan')

@section('title', 'Pembayaran Cicilan Kontrabon')

@section('content')

@php
    // Kalkulasi Dasar
    $sudahDibayar = $piutang->total_tagihan - $piutang->sisa_tagihan;
    $persentase = $piutang->total_tagihan > 0 ? ($sudahDibayar / $piutang->total_tagihan) * 100 : 0;

    // Logika Bulan & Tenggat Waktu
    $tanggalPesan = \Carbon\Carbon::parse($piutang->tanggal_pemesanan);
    $sekarang = \Carbon\Carbon::now();
    
    // Menghitung selisih bulan (Bulan 1, Bulan 2, dst)
    $bulanKe = $tanggalPesan->diffInMonths($sekarang) + 1;

    // Nominal Opsi
    $opsi1 = $satuCicilan;
    $opsi2 = $satuCicilan * 2;
    $opsiFull = $sisaTagihan;

    // Penentuan Visibilitas Opsi Berdasarkan Aturan Bulan
    $showOpsi1 = false;
    $showOpsi2 = false;

    if ($bulanKe < 3) {
        if ($sisaTagihan > $opsi1) $showOpsi1 = true;
        if ($sisaTagihan > $opsi2) $showOpsi2 = true;

        // Aturan Khusus Bulan Ke-2: 
        // Jika belum bayar sama sekali (tunggakan), minimal harus bayar 2 bulan sekaligus
        if ($bulanKe == 2 && $sudahDibayar < $opsi1) {
            $showOpsi1 = false; 
        }
    }
@endphp

{{-- ── HERO BANNER ── --}}
<section style="background: linear-gradient(135deg, #1A2332 0%, #243040 60%, #2c3e50 100%); padding: 40px 28px; position: relative; overflow: hidden;">
    <div style="position:absolute;top:-50px;right:-50px;width:250px;height:250px;border-radius:50%;background:rgba(247,127,0,0.08);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:5%;width:200px;height:200px;border-radius:50%;background:rgba(40,167,69,0.05);pointer-events:none;"></div>

    <div class="container px-0" style="max-width:1000px; position:relative; z-index:1;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('pelanggan.pembayaran_kontrabon.index', ['nomor_pesanan' => $piutang->nomor_pesanan]) }}" class="btn btn-light bg-opacity-10 border-0 text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge bg-warning text-dark px-3 py-1 rounded-pill" style="font-size:11px; letter-spacing:0.5px;">KONTRABON</span>
                    <span class="text-white-50 font-monospace small">{{ $piutang->nomor_pesanan }}</span>
                </div>
                <h4 class="fw-bold text-white mb-0" style="letter-spacing:-.3px;">Pembayaran Tagihan</h4>
            </div>
        </div>
    </div>
</section>

<div class="container py-5" style="max-width:1000px; margin-top: -30px; position: relative; z-index: 2;">
    <div class="row g-4">
        
        {{-- BAGIAN KIRI: RINGKASAN TAGIHAN --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white p-4 border-bottom-0 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-receipt-cutoff me-2 text-danger"></i>Ringkasan Tagihan</h6>
                </div>
                <div class="card-body p-4">
                    
                    <div class="bg-light p-3 rounded-3 border mb-4">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Total Keseluruhan</span>
                            <span class="fw-semibold text-dark">Rp {{ number_format($piutang->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small">
                            <span class="text-muted">Telah Dibayar</span>
                            <span class="fw-semibold text-success">- Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</span>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="progress mb-2" style="height: 6px; border-radius:10px;">
                            <div class="progress-bar bg-success" style="width: {{ $persentase }}%;"></div>
                        </div>
                        <div class="text-end text-muted" style="font-size: 10px;">{{ number_format($persentase, 1) }}% Terbayar</div>
                        
                        <hr class="opacity-25 my-3">
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-danger fw-bold">Sisa Tagihan</span>
                            <span class="text-danger fw-bold fs-4">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Informasi Dinamis Berdasarkan Bulan --}}
                    <div class="d-flex align-items-start gap-3 p-3 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3">
                        <i class="bi bi-info-circle-fill text-primary fs-4 mt-1"></i>
                        <div>
                            <div class="fw-bold text-primary small mb-1">Status: Memasuki Bulan ke-{{ $bulanKe }}</div>
                            <div class="text-secondary" style="font-size: 12px;">
                                @if ($bulanKe >= 3)
                                    Sesuai kebijakan, karena telah memasuki bulan ke-3 batas kontrabon, Anda diwajibkan untuk <b>melunasi seluruh sisa tagihan</b>.
                                @elseif ($bulanKe == 2 && !$showOpsi1)
                                    Karena Anda belum melakukan pembayaran di bulan pertama, Anda diwajibkan membayar akumulasi cicilan bulan 1 & 2 saat ini.
                                @else
                                    Anda bebas menentukan jumlah cicilan (tanpa bunga) yang ingin dibayarkan bulan ini.
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- BAGIAN KANAN: PILIHAN PEMBAYARAN --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 h-100">
                <div class="card-header bg-white p-4 border-bottom-0 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-credit-card me-2 text-danger"></i>Pilih Nominal Pembayaran</h6>
                </div>
                <div class="card-body p-4 pt-3">

                    <form action="{{ route('pelanggan.pembayaran_kontrabon.proses', $piutang->nomor_pesanan) }}" method="POST" id="formPembayaran">
                        @csrf
                        <input type="hidden" name="nominal_bayar" id="nominalBayarInput" value="{{ $sisaTagihan }}">

                        <div class="row g-3 mb-4">
                            
                            {{-- Opsi 1: Bayar 1x Cicilan --}}
                            @if ($showOpsi1)
                            <div class="col-12">
                                <input type="radio" class="btn-check" name="opsi_bayar" id="opsi1" autocomplete="off" value="{{ $opsi1 }}">
                                <label class="btn btn-outline-secondary w-100 text-start p-3 rounded-3 option-card" for="opsi1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark mb-1">Bayar 1x Cicilan</div>
                                            <div class="text-muted" style="font-size:12px;">Sesuai tagihan bulan ini</div>
                                        </div>
                                        <div class="fs-5 fw-bold text-dark">Rp {{ number_format($opsi1, 0, ',', '.') }}</div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            {{-- Opsi 2: Bayar 2x Cicilan --}}
                            @if ($showOpsi2)
                            <div class="col-12">
                                <input type="radio" class="btn-check" name="opsi_bayar" id="opsi2" autocomplete="off" value="{{ $opsi2 }}">
                                <label class="btn btn-outline-secondary w-100 text-start p-3 rounded-3 option-card" for="opsi2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark mb-1">Bayar 2x Cicilan</div>
                                            <div class="text-muted" style="font-size:12px;">Lebih cepat lunas</div>
                                        </div>
                                        <div class="fs-5 fw-bold text-dark">Rp {{ number_format($opsi2, 0, ',', '.') }}</div>
                                    </div>
                                </label>
                            </div>
                            @endif

                            {{-- Opsi 3: Pelunasan Full (Selalu Muncul & Terpilih Default) --}}
                            <div class="col-12">
                                <input type="radio" class="btn-check" name="opsi_bayar" id="opsi3" autocomplete="off" value="{{ $opsiFull }}" checked>
                                <label class="btn btn-outline-danger w-100 text-start p-3 rounded-3 option-card" for="opsi3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold mb-1">Pelunasan Penuh</div>
                                            <div class="small opacity-75">
                                                @if($bulanKe >= 3) Diwajibkan di bulan ke-3 @else Selesaikan seluruh sisa tagihan @endif
                                            </div>
                                        </div>
                                        <div class="fs-5 fw-bold">Rp {{ number_format($opsiFull, 0, ',', '.') }}</div>
                                    </div>
                                </label>
                            </div>

                        </div>

                        {{-- Total Action --}}
                        <div class="bg-light p-3 rounded-3 border d-flex justify-content-between align-items-center mb-4">
                            <span class="text-muted fw-semibold small">Akan Dibayar:</span>
                            <span class="fs-3 fw-bold text-danger" id="displayNominal">Rp {{ number_format($opsiFull, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 py-3 fw-bold rounded-3 shadow-sm fs-6 d-flex justify-content-center align-items-center gap-2" id="btnSubmit">
                            <i class="bi bi-shield-lock"></i> Bayar via Midtrans
                        </button>

                        <div class="text-center mt-3 text-muted" style="font-size: 11px;">
                            <i class="bi bi-lock-fill text-success"></i> Pembayaran Anda dienkripsi dan diproses secara aman.
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Custom Styling untuk Opsi Radio Button */
    .option-card {
        border-width: 2px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    
    .btn-check:not(:checked) + .btn-outline-secondary {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .btn-check:not(:checked) + .btn-outline-secondary:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .btn-check:not(:checked) + .btn-outline-danger {
        background-color: #fff5f5;
        border-color: #ffe3e3;
        color: #dc3545;
    }
    .btn-check:not(:checked) + .btn-outline-danger:hover {
        background-color: #ffe3e3;
        border-color: #ffc9c9;
    }

    .btn-check:checked + .btn-outline-secondary {
        background-color: #fff;
        border-color: #0d6efd;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.1);
    }
    .btn-check:checked + .btn-outline-secondary .text-dark {
        color: #0d6efd !important;
    }

    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.2);
    }
    .btn-check:checked + .btn-outline-danger .text-dark,
    .btn-check:checked + .btn-outline-danger .opacity-75 {
        color: white !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="opsi_bayar"]');
        const displayNominal = document.getElementById('displayNominal');
        const inputNominal = document.getElementById('nominalBayarInput');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Jalankan format awal saat halaman dimuat
        const checkedRadio = document.querySelector('input[name="opsi_bayar"]:checked');
        if(checkedRadio) {
            inputNominal.value = checkedRadio.value;
            displayNominal.innerHTML = 'Rp ' + formatRupiah(checkedRadio.value);
        }

        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if(this.checked) {
                    const nilai = parseInt(this.value);
                    inputNominal.value = nilai;
                    displayNominal.innerHTML = 'Rp ' + formatRupiah(nilai);
                }
            });
        });
    });
</script>

@endsection