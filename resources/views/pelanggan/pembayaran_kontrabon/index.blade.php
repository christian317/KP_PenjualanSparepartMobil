@extends('layouts.pelanggan')

@section('title', 'Tagihan Kontrabon Saya')

@section('content')
    <div class="container py-4 pb-5 min-vh-100" style="max-width:1130px;">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('pelanggan.index') }}" class="btn btn-white border shadow-sm rounded-3 px-3">
                <i class="bi bi-arrow-left text-secondary"></i>
            </a>
            <div>
                <h5 class="fw-bold mb-0">Tagihan Kontrabon</h5>
                <p class="text-muted mb-0 small">Pantau status pembayaran Anda</p>
            </div>
        </div>

        <div class="container py-4" style="max-width:1200px; position: relative; z-index: 2;">
            {{-- DAFTAR TAGIHAN --}}
            <h5 class="fw-bold mb-3"><i class="bi bi-list-ul me-2 text-danger"></i>Daftar Tagihan Anda</h5>

            @forelse ($piutang as $p)
                <div
                    class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden {{ $p->status == 1 ? 'opacity-75' : '' }}">
                    <div class="row g-0 align-items-center">

                        {{-- Kiri: Info Rekapan Kontrabon --}}
                        <div class="col-md-4 p-4 bg-light h-100">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge {{ $p->status == 1 ? 'bg-success' : 'bg-primary' }} rounded-pill small">
                                    {{ $p->status == 1 ? 'LUNAS' : 'CICILAN BERJALAN' }}
                                </span>
                                @if ($p->is_overdue)
                                    <span class="badge bg-danger rounded-pill small"><i
                                            class="bi bi-exclamation-triangle-fill"></i> JATUH TEMPO</span>
                                @endif
                            </div>
                            
                            {{-- PERBAIKAN 1: Ganti $p->nomor_kontrabon menjadi $p->kontrabon_id --}}
                            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-folder2 me-2"></i>{{ $p->kontrabon_id }}</h5>
                            
                            <div class="text-muted small mb-3">
                                Periode:
                                {{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M') : '-' }}
                                -
                                {{ $p->tanggal_selesai ? \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') : '-' }}
                            </div>

                            <div class="text-muted small fw-semibold mb-1">Batas Akhir Pembayaran:</div>
                            <div class="fw-bold {{ $p->is_overdue ? 'text-danger' : 'text-dark' }}">
                                {{ $p->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d F Y') : '-' }}
                            </div>
                        </div>

                        {{-- Tengah: Progress Pembayaran --}}
                        <div class="col-md-5 p-4 border-end">
                            <div class="row text-center mb-3">
                                <div class="col-6 border-end">
                                    <div class="text-muted small mb-1">Total Tagihan</div>
                                    <div class="fw-bold text-dark">Rp {{ number_format($p->total_tagihan, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small mb-1">Telah Dibayar</div>
                                    <div class="fw-bold text-success">Rp
                                        {{ number_format($p->sudah_dibayar, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <div class="progress" style="height: 8px; border-radius:10px;">
                                <div class="progress-bar bg-success" style="width: {{ $p->persentase }}%;"></div>
                            </div>
                            <div class="text-end text-muted mt-1" style="font-size: 11px;">
                                {{ number_format($p->persentase, 1) }}% Terbayar</div>
                        </div>

                        {{-- Kanan: Aksi Bayar --}}
                        <div class="col-md-3 p-4 text-center">
                            <div class="text-muted small mb-1">Sisa Pembayaran</div>
                            <h3 class="fw-bold text-danger mb-3">Rp {{ number_format($p->sisa_tagihan, 0, ',', '.') }}</h3>

                            @if ($p->status == 0)
                                {{-- PERBAIKAN 2: Ganti parameter route menjadi $p->kontrabon_id --}}
                                <a href="{{ route('pelanggan.pembayaran_kontrabon.detail', $p->kontrabon_id) }}"
                                    class="btn btn-danger w-100 fw-bold rounded-3 shadow-sm py-2">
                                    Rincian & Bayar
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
                    <div class="text-muted mb-3"><i class="bi bi-receipt-cutoff" style="font-size: 3rem; opacity:0.5;"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Tidak ada tagihan</h5>
                    <p class="text-muted">Anda belum memiliki tagihan kontrabon yang diterbitkan saat ini.</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $piutang->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>
@endsection