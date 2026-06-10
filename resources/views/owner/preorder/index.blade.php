@extends('layouts.app') {{-- Sesuaikan jika Owner menggunakan layouts.owner --}}

@section('title', 'Kelola Pre-Order')

@section('content')
<div class="p-4" style="background-color:#f8f9fa; min-height:100vh;">

    {{-- HEADER --}}
    <div class="sticky-top py-3 mb-4" style="background-color:#f8f9fa; z-index:1020; margin-top:-1.5rem; padding-top:1.5rem !important;">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="h4 fw-bold mb-1 d-flex align-items-center">
                    <i class="bi bi-box-seam-fill me-2 text-primary"></i>
                    Kelola Pre-Order (PO)
                </div>
                <div class="text-muted small">
                    Pantau pesanan pelanggan yang kekurangan stok. Teruskan ke gudang setelah Anda melakukan restok dari Supplier.
                </div>
            </div>
        </div>
    </div>

    {{-- STAT CARD --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#1565c0!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Total Antrean PO</div>
                            <div class="h3 fw-bold text-primary mb-0">{{ $statTotalPO }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">Semua pesanan berstatus PO</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e3f2fd; width: 40px; height: 40px;">
                            <i class="bi bi-clock-history fs-5" style="color: #1565c0;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#dc3545!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Menunggu Restok</div>
                            <div class="h3 fw-bold text-danger mb-0">{{ $statMenunggu }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">Stok barang belum mencukupi</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #ffebee; width: 40px; height: 40px;">
                            <i class="bi bi-x-octagon fs-5" style="color: #dc3545;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3" style="border-color:#198754!important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-secondary small fw-semibold">Siap Diproses</div>
                            <div class="h3 fw-bold text-success mb-0">{{ $statSiapProses }}</div>
                            <div class="text-muted mt-1" style="font-size:11px;">Tinggal teruskan ke gudang</div>
                        </div>
                        <div class="rounded-3 p-2 d-flex align-items-center justify-content-center" style="background-color: #e8f5e9; width: 40px; height: 40px;">
                            <i class="bi bi-check2-all fs-5" style="color: #198754;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <form action="{{ url()->current() }}" method="GET"
        class="bg-white p-3 rounded-3 shadow-sm mb-4 mt-2 d-flex flex-wrap gap-2 align-items-center border">
        
        <div class="input-group input-group-sm" style="max-width: 250px;">
            <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
            <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                placeholder="Cari No Pesanan / Pelanggan...">
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

        <a href="{{ url()->current() }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
            <i class="bi bi-arrow-clockwise"></i> Reset
        </a>

        <div class="ms-auto text-muted small">
            Menampilkan <b class="text-dark">{{ $pesananPO->firstItem() ?? 0 }}</b> sampai <b class="text-dark">{{ $pesananPO->lastItem() ?? 0 }}</b> dari {{ $pesananPO->total() }} data
        </div>
    </form>

    {{-- ======================================================== --}}
    {{-- DAFTAR TABEL PESANAN PO --}}
    {{-- ======================================================== --}}
    <h5 class="fw-bold mb-3 mt-2"><i class="bi bi-list-ul me-2 text-secondary"></i>Daftar Pesanan Pre-Order</h5>
    
    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead class="bg-light">
                    <tr class="text-muted small">
                        <th class="ps-3 py-3 border-0">NOMOR PESANAN</th>
                        <th class="py-3 border-0">PELANGGAN</th>
                        <th class="py-3 border-0 text-center">METODE PEMBAYARAN</th>
                        <th class="py-3 border-0 text-center">STATUS KESIAPAN STOK</th>
                        <th class="py-3 border-0 text-center" style="width: 150px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pesananPO as $p)
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold small text-dark">{{ $p->nomor }}</div>
                                <div class="text-muted" style="font-size: 11px;">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y H:i') }} WIB
                                </div>
                            </td>
                            
                            <td>
                                <div class="fw-semibold small">{{ $p->UserPelanggan ? $p->UserPelanggan->nama : 'User Terhapus' }}</div>
                                <div class="text-muted" style="font-size: 11px;">{{ $p->UserPelanggan ? $p->UserPelanggan->nama_toko : '-' }}</div>
                            </td>

                            <td class="text-center">
                                @if ($p->metode_pembayaran == 0)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="bi bi-cash me-1"></i>Cash</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-journal-text me-1"></i>Kontrabon</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($p->is_ready)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Stok Terpenuhi
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                        <i class="bi bi-x-circle-fill me-1"></i> Menunggu Supplier
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <button type="button" class="btn btn-light btn-sm border text-primary px-3 py-1 rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDetailPO{{ $p->nomor }}">
                                    <i class="bi bi-list-check"></i> Cek Stok
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-check-circle fs-2 text-success opacity-50 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Semua Aman!</h6>
                                <p class="small mb-0">Tidak ada pesanan yang tersangkut di antrean Pre-Order.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TEMPLATE PAGINATION --}}
        <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
            <span class="text-muted" style="font-size: 12px;">Halaman {{ $pesananPO->currentPage() }} dari {{ $pesananPO->lastPage() }}</span>
            <div class="m-0">
                @if ($pesananPO->hasPages())
                    {{ $pesananPO->links('pagination::bootstrap-5') }}
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

{{-- ====================================================== --}}
{{-- AREA MODAL DETAIL PO DAN GATEKEEPER --}}
{{-- ====================================================== --}}
@foreach ($pesananPO as $p)
<div class="modal fade" id="modalDetailPO{{ $p->nomor }}" tabindex="-1" aria-labelledby="modalDetailPOLabel{{ $p->nomor }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <div>
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalDetailPOLabel{{ $p->nomor }}">
                        <i class="bi bi-clipboard2-data text-primary"></i> Rincian Barang PO
                    </h5>
                    <div class="text-muted small mt-1 font-monospace">{{ $p->nomor }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 pt-2">
                
                {{-- Info Singkat Pelanggan --}}
                <div class="d-flex align-items-center gap-2 mb-4 bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded-3 p-3">
                    <i class="bi bi-person-circle fs-3 text-primary"></i>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 14px;">{{ $p->UserPelanggan->nama ?? '-' }} <span class="text-secondary fw-normal">({{ $p->UserPelanggan->nama_toko ?? '-' }})</span></div>
                        <div class="text-muted" style="font-size: 11px;">Pesan pada: {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }} WIB</div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;"><i class="bi bi-box-seam me-2"></i>KONDISI STOK BARANG SAAT INI</h6>
                
                <div class="table-responsive border rounded-3 overflow-hidden mb-4">
                    <table class="table table-striped align-middle mb-0 text-sm">
                        <thead class="bg-light border-bottom">
                            <tr class="text-muted" style="font-size: 12px;">
                                <th class="ps-3 py-2">PRODUK</th>
                                <th class="py-2 text-center">DIPESAN</th>
                                <th class="py-2 text-center">STOK GUDANG</th>
                                <th class="pe-3 py-2 text-center">STATUS ITEM</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p->items as $item)
                                @php
                                    // Deteksi jika stok item ini kurang
                                    $isMinus = $item->produk && $item->produk->stok < $item->jumlah;
                                @endphp
                                <tr class="{{ $isMinus ? 'table-danger' : '' }}">
                                    <td class="ps-3 py-2">
                                        <div class="fw-semibold {{ $isMinus ? 'text-danger' : 'text-dark' }}">{{ $item->produk->nama ?? 'Produk Tidak Ditemukan' }}</div>
                                        <div class="text-muted font-monospace" style="font-size: 10px;">SKU: {{ $item->produk_id }} 
                                            @if($item->produk && $item->produk->preorder == 1) <span class="badge bg-warning text-dark ms-1">Barang PO</span> @endif
                                        </div>
                                    </td>
                                    <td class="py-2 text-center fw-bold fs-6">{{ $item->jumlah }}</td>
                                    <td class="py-2 text-center fw-bold fs-6">{{ $item->produk->stok ?? 0 }}</td>
                                    <td class="pe-3 py-2 text-center">
                                        @if($isMinus)
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Kurang {{ $item->jumlah - $item->produk->stok }}</span>
                                        @else
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- GATEKEEPER LOGIC --}}
                @if($p->is_ready)
                    <div class="alert alert-success border-success border-opacity-50 small mb-0 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Stok Sudah Terpenuhi!</strong><br>
                            Admin gudang telah memasukkan stok barang dari supplier. Anda sudah bisa meneruskan pesanan ini untuk diproses gudang.
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger border-danger border-opacity-50 small mb-0 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Stok Masih Kurang!</strong><br>
                            Silakan minta Admin Gudang untuk melakukan input <i>Pembelian Supplier</i> agar stok bertambah. Tombol proses akan terkunci otomatis.
                        </div>
                    </div>
                @endif
            </div>

            <div class="modal-footer border-top-0 bg-light py-3 d-flex justify-content-between">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Tutup</button>
                
                {{-- TOMBOL TERUSKAN (TERKUNCI / TERBUKA) --}}
                @if($p->is_ready)
                    <form action="{{ route('owner.preorder.teruskan', $p->nomor) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-success fw-bold px-4" onclick="return confirm('Teruskan pesanan ini ke Admin Gudang sekarang?');">
                            <i class="bi bi-send-check me-2"></i> Proses ke Gudang
                        </button>
                    </form>
                @else
                    <button type="button" class="btn btn-secondary fw-bold px-4" disabled title="Terkunci karena stok kurang">
                        <i class="bi bi-lock-fill me-2"></i> Menunggu Restok
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- TOAST NOTIFICATION --}}
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