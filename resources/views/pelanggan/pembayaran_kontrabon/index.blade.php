@extends('layouts.pelanggan')

@section('title', 'Tagihan Kontrabon Saya')

@section('content')

{{-- ── HERO BANNER ── --}}
<section style="background: linear-gradient(135deg, #1A2332 0%, #243040 60%, #2c3e50 100%); padding: 40px 28px; position: relative; overflow: hidden;">
    <div style="position:absolute;top:-50px;right:-50px;width:250px;height:250px;border-radius:50%;background:rgba(247,127,0,0.08);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-80px;left:5%;width:200px;height:200px;border-radius:50%;background:rgba(40,167,69,0.05);pointer-events:none;"></div>

    <div class="container px-0" style="max-width:1200px; position:relative; z-index:1;">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-wallet2 fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-white mb-1" style="letter-spacing:-.3px;">Tagihan Kontrabon</h4>
                <div class="text-white-50 small">Kelola dan bayar cicilan tagihan Anda di sini.</div>
            </div>
        </div>
    </div>
</section>

<div class="container py-4" style="max-width:1200px; position: relative; z-index: 2;">
    
    {{-- STATISTIK LIMIT PELANGGAN --}}
    <div class="row g-3 mb-5" style="margin-top: -60px;">
        <div class="col-md-4">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">LIMIT HUTANG TOTAL</div>
                    <h3 class="fw-bold text-dark mb-0">Rp {{ number_format($user->limit_hutang, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="text-muted small fw-semibold mb-1">HUTANG BERJALAN (BELUM LUNAS)</div>
                    <h3 class="fw-bold text-danger mb-0">Rp {{ number_format($totalHutangBerjalan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow rounded-4 h-100 bg-success bg-opacity-10 border border-success border-opacity-25">
                <div class="card-body p-4">
                    <div class="text-success small fw-semibold mb-1">SISA LIMIT TERSEDIA</div>
                    <h3 class="fw-bold text-success mb-0">Rp {{ number_format($sisaLimit, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- DAFTAR TAGIHAN --}}
    <h5 class="fw-bold mb-3"><i class="bi bi-list-ul me-2 text-danger"></i>Daftar Tagihan Anda</h5>

    @forelse ($piutang as $p)
        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden {{ $p->status == 1 ? 'opacity-75' : '' }}">
            <div class="row g-0 align-items-center">
                
                {{-- Kiri: Info Pesanan --}}
                <div class="col-md-4 p-4 bg-light h-100">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="badge {{ $p->status == 1 ? 'bg-success' : 'bg-primary' }} rounded-pill small">
                            {{ $p->status == 1 ? 'LUNAS' : 'CICILAN BERJALAN' }}
                        </span>
                        @if($p->is_overdue)
                            <span class="badge bg-danger rounded-pill small"><i class="bi bi-exclamation-triangle-fill"></i> JATUH TEMPO</span>
                        @endif
                    </div>
                    <h5 class="fw-bold text-dark mb-1">{{ $p->nomor_pesanan }}</h5>
                    <div class="text-muted small mb-3">Tgl Pesan: {{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->format('d M Y') }}</div>
                    
                    <div class="text-muted small fw-semibold mb-1">Batas Akhir Pembayaran:</div>
                    <div class="fw-bold {{ $p->is_overdue ? 'text-danger' : 'text-dark' }}">
                        {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d F Y') }}
                    </div>
                </div>

                {{-- Tengah: Progress Pembayaran --}}
                <div class="col-md-5 p-4 border-end">
                    <div class="row text-center mb-3">
                        <div class="col-6 border-end">
                            <div class="text-muted small mb-1">Total Tagihan</div>
                            <div class="fw-bold text-dark">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small mb-1">Telah Dibayar</div>
                            <div class="fw-bold text-success">Rp {{ number_format($p->sudah_dibayar, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="progress" style="height: 8px; border-radius:10px;">
                        <div class="progress-bar bg-success" style="width: {{ $p->persentase }}%;"></div>
                    </div>
                    <div class="text-end text-muted mt-1" style="font-size: 11px;">{{ number_format($p->persentase, 1) }}% Terbayar</div>
                </div>

                {{-- Kanan: Aksi Bayar --}}
                <div class="col-md-3 p-4 text-center">
                    <div class="text-muted small mb-1">Sisa Pembayaran</div>
                    <h3 class="fw-bold text-danger mb-3">Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}</h3>

                    @if ($p->status == 0)
                        <a href="{{ route('pelanggan.pembayaran_kontrabon.index', $p->nomor_pesanan) }}" class="btn btn-danger w-100 fw-bold rounded-3 shadow-sm py-2">
                            Bayar Cicilan
                        </a>
                    @else
                        <button class="btn btn-success w-100 fw-bold rounded-3 py-2" disabled>
                            <i class="bi bi-check-circle-fill me-1"></i> Sudah Lunas
                        </button>
                    @endif
                </div>

            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="text-muted mb-3"><i class="bi bi-receipt-cutoff" style="font-size: 3rem; opacity:0.5;"></i></div>
            <h5 class="fw-bold text-dark">Tidak ada tagihan</h5>
            <p class="text-muted">Anda belum memiliki tagihan kontrabon saat ini.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $piutang->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection