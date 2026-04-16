@extends('layouts.app')

@section('title', 'Monitoring Kontrabon')
@section('page', 'Monitoring Kontrabon')

@section('content')
<div class="p-4" style="background-color:#f8f9fa; min-height:100vh;">

    {{-- HEADER --}}
    <div class="sticky-top py-3 mb-4" style="background-color:#f8f9fa; z-index:1020; margin-top:-1.5rem; padding-top:1.5rem !important;">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="h4 fw-bold mb-1 d-flex align-items-center">
                    <i class="bi bi-wallet2 me-2 text-danger"></i>
                    Monitoring Kontrabon & Piutang
                </div>
                <div class="text-muted small">
                    Pantau limit pelanggan, progres pembayaran cicilan, dan tagihan jatuh tempo.
                </div>
            </div>
        </div>
    </div>

    {{-- STAT CARD --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#dc3545!important;">
                <div class="card-body p-3">
                    <div class="text-secondary small fw-semibold">Perlu Approval</div>
                    <div class="h3 fw-bold text-danger mb-0">{{ $statPerluApproval }}</div>
                    <div class="text-muted mt-1" style="font-size:11px;">Pesanan Melebihi Limit</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#1565c0!important;">
                <div class="card-body p-3">
                    <div class="text-secondary small fw-semibold">Total Piutang Berjalan</div>
                    <div class="h3 fw-bold text-primary mb-0">Rp {{ number_format($statKontrabonAktif, 0, ',', '.') }}</div>
                    <div class="text-muted mt-1" style="font-size:11px;">Uang di tangan pelanggan</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#f57f17!important;">
                <div class="card-body p-3">
                    <div class="text-secondary small fw-semibold">Tagihan Overdue</div>
                    <div class="h3 fw-bold text-warning mb-0">{{ $statOverdue }}</div>
                    <div class="text-muted mt-1" style="font-size:11px;">Lewat Jatuh Tempo</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" class="bg-white p-3 rounded-3 shadow-sm mb-3 d-flex flex-wrap gap-2 align-items-center">
        <div class="input-group input-group-sm" style="max-width:300px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0" placeholder="Cari pesanan / pelanggan...">
        </div>
        <a href="{{ url()->current() }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0"><i class="bi bi-x"></i> Reset</a>
        
        <div class="ms-auto text-muted small">
            Menampilkan <b class="text-dark">{{ $piutang->firstItem() ?? 0 }}</b> - <b class="text-dark">{{ $piutang->lastItem() ?? 0 }}</b> dari {{ $piutang->total() }}
        </div>
    </form>

    {{-- TABLE MONITORING --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr class="text-muted small">
                        <th class="ps-3 py-3 border-0">PESANAN</th>
                        <th class="py-3 border-0">PELANGGAN</th>
                        <th class="py-3 border-0">TAGIHAN & SISA</th>
                        <th class="py-3 border-0 text-center">JATUH TEMPO</th>
                        <th class="py-3 border-0 text-center">STATUS</th>
                        <th class="py-3 border-0 text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($piutang as $p)
                        <tr class="{{ $p->status_pesanan == 5 ? 'table-warning bg-opacity-10' : ($p->is_overdue ? 'table-danger bg-opacity-10' : '') }}">
                            
                            {{-- PESANAN --}}
                            <td class="ps-3">
                                <div class="fw-bold small text-dark">{{ $p->nomor_pesanan }}</div>
                                <div class="text-muted" style="font-size:11px;">Tgl: {{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->format('d/m/Y') }}</div>
                            </td>

                            {{-- PELANGGAN --}}
                            <td>
                                <div class="fw-semibold small">{{ $p->nama }}</div>
                                <div class="text-muted" style="font-size:11px;">Limit: Rp {{ number_format($p->limit_hutang,0,',','.') }}</div>
                            </td>

                            {{-- TAGIHAN & PROGRESS BAYAR --}}
                            <td>
                                <div class="d-flex justify-content-between mb-1" style="font-size:11px;">
                                    <span class="text-muted">Total: Rp {{ number_format($p->total_tagihan,0,',','.') }}</span>
                                    <span class="fw-bold text-danger">Sisa: Rp {{ number_format($p->sisa_tagihan,0,',','.') }}</span>
                                </div>
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar {{ $p->status == 1 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $p->persentase_bayar }}%"></div>
                                </div>
                                <div class="text-muted mt-1" style="font-size:10px;">Telah dibayar: Rp {{ number_format($p->sudah_dibayar,0,',','.') }}</div>
                            </td>

                            {{-- JATUH TEMPO --}}
                            <td class="text-center">
                                <div class="fw-semibold small {{ $p->is_overdue ? 'text-danger' : 'text-dark' }}">
                                    {{ \Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->format('d M Y') }}
                                </div>
                                @if($p->status == 0)
                                    <div style="font-size:10px;" class="{{ $p->is_overdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                        {{ Carbon\Carbon::parse($p->tanggal_jatuh_tempo)->diffForHumans() }}
                                    </div>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="text-center">
                                @if ($p->status_pesanan == 5)
                                    <span class="badge bg-warning text-dark border border-warning">Perlu Approval</span>
                                @elseif ($p->status == 1)
                                    <span class="badge bg-success bg-opacity-25 text-success">Lunas</span>
                                @elseif ($p->is_overdue)
                                    <span class="badge bg-danger bg-opacity-25 text-danger">Overdue</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Belum Lunas</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    <button class="btn btn-light btn-sm border text-primary" title="Lihat Detail" data-bs-toggle="modal" data-bs-target="#modal{{ $p->nomor_pesanan }}">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>

                                    @if ($p->status_pesanan == 5)
                                        <form action="{{ url('/keuangan/kontrabon/' . $p->nomor_pesanan . '/approve') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui" onclick="return confirm('Setujui pesanan ini?');"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form action="{{ url('/keuangan/kontrabon/' . $p->nomor_pesanan . '/tolak') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Tolak" onclick="return confirm('Tolak pesanan ini?');"><i class="bi bi-x-lg"></i></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada data piutang berjalan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size:12px;">Halaman {{ $piutang->currentPage() }} dari {{ $piutang->lastPage() }}</span>
            {{ $piutang->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

{{-- ================= MODAL DETAIL & PEMBAYARAN ================= --}}
@foreach ($piutang as $p)
@php
    $sisaLimit = $p->limit_hutang - $p->terpakai_saat_ini;
    $defisit = $sisaLimit - $p->total_tagihan;
@endphp
<div class="modal fade" id="modal{{ $p->nomor_pesanan }}" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">

            <div class="p-3 border-bottom bg-light">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">Detail Kontrabon & Pesanan</div>
                        <div class="text-muted small">{{ $p->nomor_pesanan }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body p-4">
                
                {{-- INFO OVERLIMIT JIKA STATUS 5 --}}
                @if($p->status_pesanan == 5)
                    <div class="alert alert-danger p-3 mb-4 rounded-3 border-0">
                        <div class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Peringatan Overlimit Hutang!</div>
                        <div class="row g-2 small">
                            <div class="col-4">Sisa Limit Tersedia:<br><strong class="text-dark">Rp {{ number_format($sisaLimit,0,',','.') }}</strong></div>
                            <div class="col-4">Tagihan Pesanan Ini:<br><strong class="text-dark">Rp {{ number_format($p->total_tagihan,0,',','.') }}</strong></div>
                            <div class="col-4 text-danger">Defisit (Kekurangan):<br><strong>Rp {{ number_format(abs($defisit),0,',','.') }}</strong></div>
                        </div>
                    </div>
                @endif

                <div class="row g-4 mb-4">
                    {{-- INFO PELANGGAN --}}
                    <div class="col-md-6">
                        <div class="text-muted fw-semibold mb-2" style="font-size:11px;">INFORMASI PELANGGAN</div>
                        <div class="bg-light p-3 rounded-3 border h-100">
                            <div class="fw-bold text-dark">{{ $p->nama }}</div>
                            <div class="text-secondary small">{{ $p->nama_toko }}</div>
                            <hr class="my-2 opacity-25">
                            <div class="d-flex justify-content-between small text-muted">
                                <span>Limit Total:</span>
                                <span class="text-dark fw-semibold">Rp {{ number_format($p->limit_hutang,0,',','.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- INFO PEMBAYARAN --}}
                    <div class="col-md-6">
                        <div class="text-muted fw-semibold mb-2" style="font-size:11px;">STATUS PEMBAYARAN</div>
                        <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 p-3 rounded-3 h-100">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Total Tagihan:</span>
                                <span class="text-dark fw-semibold">Rp {{ number_format($p->total_tagihan,0,',','.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-muted">Telah Dibayar (Cicilan):</span>
                                <span class="text-success fw-semibold">Rp {{ number_format($p->sudah_dibayar,0,',','.') }}</span>
                            </div>
                            <hr class="border-primary opacity-25 my-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-danger">Sisa Hutang:</span>
                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($p->sisa_tagihan,0,',','.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RINCIAN BARANG --}}
                <div class="text-muted fw-semibold mb-2" style="font-size:11px;">RINCIAN BARANG YANG DIPESAN</div>
                <div class="table-responsive border rounded-3 mb-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="ps-3">Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($p->items as $item)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold small">{{ $item->nama_produk }}</div>
                                    <div class="text-muted" style="font-size:10px;">{{ $item->produk_id }}</div>
                                </td>
                                <td class="text-center small">{{ $item->jumlah }}</td>
                                <td class="text-end fw-semibold small pe-3">Rp {{ number_format($item->harga * $item->jumlah,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="p-3 border-top d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
@endforeach

<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
    <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body fw-semibold" id="toastMsg"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    @if (Session::has('toast_success'))
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('toastMsg').textContent = "{{ Session::get('toast_success') }}";
            new bootstrap.Toast(document.getElementById('liveToast')).show();
        });
    @endif
</script>
@endsection