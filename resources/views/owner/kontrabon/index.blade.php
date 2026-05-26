@extends('layouts.app')

@section('title', 'Kelola Kontrabon')

@section('content')
<div class="p-4" style="background-color:#f8f9fa; min-height:100vh;">

    {{-- HEADER --}}
    <div class="sticky-top py-3 mb-4" style="background-color:#f8f9fa; z-index:1020; margin-top:-1.5rem; padding-top:1.5rem !important;">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="h4 fw-bold mb-1 d-flex align-items-center">
                    <i class="bi bi-wallet2 me-2 text-danger"></i>
                    Kelola Kontrabon
                </div>
                <div class="text-muted small">
                    Kelola dan pantau progres pembayaran cicilan serta tagihan jatuh tempo pelanggan Mitra.
                </div>
            </div>
        </div>
    </div>

    {{-- STAT CARD --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#dc3545!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Perlu Approval</div>
                            <div class="h3 fw-bold text-danger mb-0">{{ $statPerluApproval }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">Pesanan Baru Menunggu ACC</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #ffebee; width: 40px; height: 40px;">
                            <i class="bi bi-shield-check fs-5" style="color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#1565c0!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Piutang Berjalan</div>
                            <div class="h3 fw-bold text-primary mb-0">Rp {{ number_format($statKontrabonAktif, 0, ',', '.') }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">Menunggu pelunasan pelanggan</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e3f2fd; width: 40px; height: 40px;">
                            <i class="bi bi-cash-stack fs-5" style="color: #1565c0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#f57f17!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Tagihan Overdue</div>
                            <div class="h3 fw-bold text-warning mb-0">{{ $statOverdue }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">Lewat masa jatuh tempo</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #fff3e0; width: 40px; height: 40px;">
                            <i class="bi bi-alarm fs-5" style="color: #f57f17;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- BAGIAN 1: ANTREAN APPROVAL PESANAN (Muncul jika ada) --}}
    {{-- ======================================================== --}}
    @if($pesananPending->total() > 0)
    <div class="card border border-warning shadow-sm rounded-3 mb-5">
        <div class="card-header bg-warning bg-opacity-10 py-3 border-bottom border-warning border-opacity-50">
            <div class="fw-bold text-dark d-flex align-items-center">
                <i class="bi bi-exclamation-circle-fill text-warning me-2 fs-5"></i>
                Terdapat {{ $pesananPending->total() }} Pesanan Menunggu Persetujuan
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr class="text-muted small">
                        <th class="ps-3 py-3 border-0">NOMOR KONTRABON</th>
                        <th class="ps-3 py-3 border-0">NOMOR PESANAN</th>
                        <th class="py-3 border-0">PELANGGAN</th>
                        <th class="py-3 border-0 text-end">TOTAL NILAI</th>
                        <th class="py-3 border-0 text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pesananPending as $pending)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold small text-dark">{{ $pending->nomor_kontrabon }}</div>
                        </td>
                        <td class="ps-3">
                            <div class="fw-bold small text-dark">{{ $pending->nomor }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ \Carbon\Carbon::parse($pending->tanggal)->format('d M Y H:i') }} WIB</div>
                        </td>
                        <td>
                            <div class="fw-semibold small">{{ $pending->nama }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $pending->nama_toko }}</div>
                        </td>
                        <td class="text-end pe-4 fw-bold text-danger">
                            Rp {{ number_format($pending->total_nilai, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center flex-wrap">
                               <form action="{{ route('owner.kontrabon.approve', $pending->nomor) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success px-2 py-1 rounded-2" title="Setujui" onclick="return confirm('Setujui pesanan ini?');"><i class="bi bi-check-lg"></i></button>
                                </form>
                                <form action="{{ route('owner.kontrabon.tolak', $pending->nomor) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1 rounded-2" title="Tolak" onclick="return confirm('Tolak pesanan ini?');"><i class="bi bi-x-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- TEMPLATE PAGINATION ANTREAN APPROVAL --}}
        <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size: 12px;">Halaman {{ $pesananPending->currentPage() }} dari {{ $pesananPending->lastPage() }}</span>
            <div class="m-0">
                @if ($pesananPending->hasPages())
                    {{ $pesananPending->links('pagination::bootstrap-5') }}
                @else
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                    </ul>
                @endif
            </div>
        </div>

    </div>
    @endif

    {{-- FILTER FORM --}}
    <form action="{{ route('owner.kontrabon.index') }}" method="GET"
        class="bg-white p-3 rounded-3 shadow-sm mb-4 mt-2 d-flex flex-wrap gap-2 align-items-center border">
        
        <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                placeholder="Cari No Kontrabon / Pelanggan...">
        </div>

        <div class="d-flex align-items-center gap-2">
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" title="Pilih Tanggal Spesifik">
            
            <select name="bulan" class="form-select form-select-sm" style="max-width: 140px;">
                <option value="">Semua Bulan</option>
                @php
                    $bulanSekarang = request('bulan');
                    $namaBulan = [
                        '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', 
                        '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', 
                        '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
                    ];
                @endphp
                @foreach ($namaBulan as $val => $name)
                    <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>

            <select name="tahun" class="form-select form-select-sm" style="max-width: 120px;">
                <option value="">Semua Tahun</option>
                @php $tahunSekarang = request('tahun'); @endphp
                @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                    <option value="{{ $y }}" {{ $tahunSekarang == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>

        <button type="submit" class="btn btn-primary btn-sm fw-semibold ms-1">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>

        <a href="{{ route('owner.kontrabon.index') }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
            <i class="bi bi-arrow-clockwise"></i> Reset
        </a>

        <div class="ms-auto text-muted small">
            Menampilkan <b class="text-dark">{{ $kontrabonList->firstItem() ?? 0 }}</b> sampai <b class="text-dark">{{ $kontrabonList->lastItem() ?? 0 }}</b> dari {{ $kontrabonList->total() }} data
        </div>
    </form>

    {{-- ======================================================== --}}
    {{-- BAGIAN 2: DAFTAR BUKU KONTRABON UTAMA --}}
    {{-- ======================================================== --}}
    <h5 class="fw-bold mb-3 mt-2"><i class="bi bi-journal-text me-2 text-secondary"></i>Daftar Rekap Kontrabon</h5>
    
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr class="text-muted small">
                        <th class="ps-3 py-3 border-0">NOMOR KONTRABON</th>
                        <th class="py-3 border-0">PELANGGAN</th>
                        <th class="py-3 border-0">TAGIHAN & PROGRESS</th>
                        <th class="py-3 border-0 text-center">TANGGAL RECAP</th>
                        <th class="py-3 border-0 text-center">STATUS</th>
                        <th class="py-3 border-0 text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kontrabonList as $kb)
                            <tr class="{{ $kb->is_overdue ? 'table-danger bg-opacity-10' : '' }}">
                                <td class="ps-3">
                                    <div class="fw-bold small text-dark">{{ $kb->nomor_kontrabon }}</div>
                                    <div class="text-muted" style="font-size:11px;">Mulai: {{ \Carbon\Carbon::parse($kb->tanggal_mulai)->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold small">{{ $kb->nama }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $kb->nama_toko }}</div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-between mb-1" style="font-size:11px;">
                                        <span class="text-muted">Total: Rp {{ number_format($kb->total_tagihan_tampil,0,',','.') }}</span>
                                        <span class="fw-bold text-danger">Sisa: Rp {{ number_format($kb->sisa_tagihan_tampil,0,',','.') }}</span>
                                    </div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar {{ $kb->status == 1 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $kb->persentase_bayar }}%"></div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if ($kb->tanggal_jatuh_tempo)
                                        <div class="fw-semibold small {{ $kb->is_overdue ? 'text-danger' : 'text-dark' }}">
                                            {{ \Carbon\Carbon::parse($kb->tanggal_jatuh_tempo)->format('d M Y') }}
                                        </div>
                                        @if($kb->status == 0)
                                            <div style="font-size:10px;" class="{{ $kb->is_overdue ? 'text-danger fw-bold' : 'text-muted' }}">
                                                {{ \Carbon\Carbon::parse($kb->tanggal_jatuh_tempo)->diffForHumans() }}
                                            </div>
                                        @endif
                                    @else
                                        <div class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1" style="font-size:10px;">
                                            <i class="bi bi-inbox me-1"></i>Masa Tampung<br>
                                            (s/d {{ \Carbon\Carbon::parse($kb->tanggal_selesai)->format('d M Y') }})
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($kb->status === 0)
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">Draf</span>
                                    @elseif ($kb->status == 1)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Terbit</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Menunggu Piutang</span>
                                    @endif
                                    
                                    @if($kb->is_overdue)
                                        <div class="badge bg-danger text-white mt-1 px-2 py-1" style="font-size: 9px;">OVERDUE</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{-- BUTTON PINDAH HALAMAN --}}
                                    <a href="{{ route('owner.kontrabon.detail', $kb->nomor_kontrabon) }}" class="btn btn-light btn-sm border text-primary px-3 py-1 rounded-2 shadow-sm" title="Lihat Detail Rincian">
                                        Lihat Rincian <i class="bi bi-chevron-right ms-1" style="font-size: 10px;"></i>
                                    </a>
                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-journal-x fs-2 opacity-50 d-block mb-2"></i>
                                Tidak ada data rekap kontrabon.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- TEMPLATE PAGINATION DAFTAR KONTRABON --}}
        <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size: 12px;">Halaman {{ $kontrabonList->currentPage() }} dari {{ $kontrabonList->lastPage() }}</span>
            <div class="m-0">
                @if ($kontrabonList->hasPages())
                    {{ $kontrabonList->links('pagination::bootstrap-5') }}
                @else
                    <ul class="pagination mb-0">
                        <li class="page-item disabled"><span class="page-link">&lsaquo;</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item disabled"><span class="page-link">&rsaquo;</span></li>
                    </ul>
                @endif
            </div>
        </div>

    </div>
</div>

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