@extends('layouts.app')

@section('title', 'Kelola Pesanan')
@section('page', 'Kelola Pesanan')

@section('content')
    <div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-box-seam me-2" style="color: #f57c00;"></i>Kelola Antrean Pesanan
                    </div>
                    <div class="text-muted small">Pantau dan siapkan pesanan aktif untuk segera dikirim ke pelanggan.</div>
                </div>
            </div>
        </div>

        {{-- Info Statistik --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #f57c00 !important;">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-semibold">Perlu Diproses</div>
                            <div class="h3 fw-bold mb-0 text-warning">{{ $statDiproses }}</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">Menunggu disiapkan</div>
                        </div>
                        <div class="rounded-3 p-2 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #1565c0 !important;">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-semibold">Dikirim</div>
                            <div class="h3 fw-bold mb-0 text-primary">{{ $statDikirim }}</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">Dalam perjalanan ke pelanggan</div>
                        </div>
                        <div class="rounded-3 p-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-truck fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #2d6a4f !important;">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-secondary small fw-semibold">Selesai Hari Ini</div>
                            <div class="h3 fw-bold mb-0 text-success">{{ $statSelesaiHariIni }}</div>
                            <div class="text-muted mt-1" style="font-size: 11px;">Pesanan sukses diterima</div>
                        </div>
                        <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                            style="width: 45px; height: 45px;">
                            <i class="bi bi-bag-check fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter Pencarian --}}
        <form action="{{ url()->current() }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-4 mt-2 d-flex flex-wrap gap-2 align-items-center border">

            <div class="input-group input-group-sm" style="max-width: 250px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input name="search" value="{{ request('search') }}" class="form-control bg-light border-start-0"
                    placeholder="Cari No Pesanan / Pelanggan...">
            </div>

            <div class="d-flex align-items-center gap-2">
                <input type="date" name="tanggal" class="form-control form-control-sm text-muted"
                    value="{{ request('tanggal') }}" title="Pilih Tanggal">

                <select name="bulan" class="form-select form-select-sm text-muted" style="max-width: 120px;">
                    <option value="">Semua Bulan</option>
                    @php
                        $bulanSekarang = request('bulan');
                        $namaBulan = [
                            '01' => 'Jan',
                            '02' => 'Feb',
                            '03' => 'Mar',
                            '04' => 'Apr',
                            '05' => 'Mei',
                            '06' => 'Jun',
                            '07' => 'Jul',
                            '08' => 'Agt',
                            '09' => 'Sep',
                            '10' => 'Okt',
                            '11' => 'Nov',
                            '12' => 'Des',
                        ];
                    @endphp
                    @foreach ($namaBulan as $val => $name)
                        <option value="{{ $val }}" {{ $bulanSekarang == $val ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>

                <select name="tahun" class="form-select form-select-sm text-muted" style="max-width: 120px;">
                    <option value="">Semua Tahun</option>
                    @for ($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                            {{ $y }}</option>
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
                Menampilkan <b class="text-dark">{{ $pesanan->firstItem() ?? 0 }}</b> - <b
                    class="text-dark">{{ $pesanan->lastItem() ?? 0 }}</b> dari {{ $pesanan->total() }} pesanan
            </div>
        </form>

        {{-- Daftar Pesanan --}}
        <h5 class="fw-bold mb-3 mt-2"><i class="bi bi-list-ul me-2 text-secondary"></i>Antrean Diproses Gudang</h5>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NOMOR PESANAN</th>
                            <th class="py-3 border-0">PELANGGAN</th>
                            <th class="py-3 border-0 text-center">JUMLAH BARANG</th>
                            <th class="py-3 border-0 text-center">STATUS</th>
                            <th class="py-3 border-0 text-center" style="width: 190px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                            @if ($p->status == 0)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small text-dark">{{ $p->nomor }}</div>
                                        <div class="text-muted" style="font-size: 11px;">
                                            {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }} WIB
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold small">{{ $p->UserPelanggan->nama ?? 'User Terhapus' }}</div>
                                        <div class="text-muted" style="font-size: 11px;">
                                            <i
                                                class="bi bi-shop me-1"></i>{{ $p->UserPelanggan->nama_toko ?? 'Pelanggan Biasa' }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 px-3 py-2"
                                            style="font-size: 12px;">
                                            <i class="bi bi-box-seam me-1"></i> {{ $p->total_item }} Item
                                        </span>
                                    </td>

                                    @if ($p->status == 0)
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-2 py-1">Perlu
                                                Diproses</span>
                                        </td>
                                    @endif

                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button"
                                                class="btn btn-light btn-sm border text-primary px-3 py-1 rounded-2 shadow-sm"
                                                data-bs-toggle="modal" data-bs-target="#modalDetail{{ $p->nomor }}"
                                                title="Lihat Surat Jalan">
                                                <i class="bi bi-eye"></i> Detail
                                            </button>

                                            @if ($p->status == 0)
                                                <form action="{{ route('admin.pesanan.kirim', $p->nomor) }}" method="POST"
                                                    class="m-0 form-kirim">
                                                    @csrf
                                                    <button type="button"
                                                        class="btn btn-sm btn-primary fw-semibold px-2 py-1 rounded-2 d-flex align-items-center gap-1 shadow-sm btn-kirim"
                                                        title="Kirim Barang">
                                                        <i class="bi bi-truck"></i> Kirim
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle fs-2 text-success opacity-50 d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark mb-1">Gudang Bersih!</h6>
                                    <p class="small mb-0">Belum ada antrean pesanan yang perlu disiapkan saat ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">Halaman {{ $pesanan->currentPage() }} dari
                    {{ $pesanan->lastPage() }}</span>
                <div class="m-0">
                    @if ($pesanan->hasPages())
                        {{ $pesanan->links('pagination::bootstrap-5') }}
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


        {{-- Tabel dikirim --}}
        <h5 class="fw-bold mb-3 mt-4"><i class="bi bi-truck me-2 text-info"></i>Pesanan Sedang Dikirim</h5>

        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NOMOR PESANAN</th>
                            <th class="py-3 border-0">PELANGGAN</th>
                            <th class="py-3 border-0 text-center">JUMLAH BARANG</th>
                            <th class="py-3 border-0 text-center">STATUS</th>
                            <th class="py-3 border-0 text-center" style="width: 190px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                            @if ($p->status == 1)
                                <tr>
                                    <td class="ps-3">
                                        <div class="fw-bold small text-dark">{{ $p->nomor }}</div>
                                        <div class="text-muted" style="font-size: 11px;">
                                            {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }} WIB
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fw-semibold small">{{ $p->UserPelanggan->nama ?? 'User Terhapus' }}</div>
                                        <div class="text-muted" style="font-size: 11px;">
                                            <i
                                                class="bi bi-shop me-1"></i>{{ $p->UserPelanggan->nama_toko ?? 'Pelanggan Biasa' }}
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 px-3 py-2"
                                            style="font-size: 12px;">
                                            <i class="bi bi-box-seam me-1"></i> {{ $p->total_item }} Item
                                        </span>
                                    </td>

                                    @if ($p->status == 1)
                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-50 px-2 py-1">Sedang
                                                Dikirim</span>
                                        </td>
                                    @endif
                                        
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            <button type="button"
                                                class="btn btn-light btn-sm border text-primary px-3 py-1 rounded-2 shadow-sm"
                                                data-bs-toggle="modal" data-bs-target="#modalDetail{{ $p->nomor }}"
                                                title="Lihat Surat Jalan">
                                                <i class="bi bi-eye"></i> Detail
                                            </button>
                                            
                                            {{-- TOMBOL SELESAI BARU --}}
                                            <form action="{{ route('admin.pesanan.selesai', $p->nomor) }}" method="POST" class="m-0 form-selesai">
                                                @csrf
                                                <button type="button"
                                                    class="btn btn-sm btn-success fw-semibold px-2 py-1 rounded-2 d-flex align-items-center gap-1 shadow-sm btn-selesai"
                                                    title="Konfirmasi Pesanan Selesai">
                                                    <i class="bi bi-check2-all"></i> Selesai
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-check-circle fs-2 text-success opacity-50 d-block mb-2"></i>
                                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Pengiriman Aktif!</h6>
                                    <p class="small mb-0">Belum ada pesanan yang sedang dalam perjalanan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div
                class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">Halaman {{ $pesanan->currentPage() }} dari
                    {{ $pesanan->lastPage() }}</span>
                <div class="m-0">
                    @if ($pesanan->hasPages())
                        {{ $pesanan->links('pagination::bootstrap-5') }}
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

    {{-- Modal Detail Pesanan --}}
    @foreach ($pesanan as $p)
        <div class="modal fade" id="modalDetail{{ $p->nomor }}" tabindex="-1"
            aria-labelledby="modalDetailLabel{{ $p->nomor }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg rounded-4">

                    <div class="modal-header bg-light border-bottom-0 pb-3">
                        <div>
                            <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2"
                                id="modalDetailLabel{{ $p->nomor }}">
                                <i class="bi bi-box-seam text-warning"></i> Detail Pengemasan
                            </h5>
                            <div class="text-muted small mt-1">{{ $p->nomor }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4 pt-2">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">TUJUAN PENGIRIMAN</h6>
                                    <div class="mb-2"><i class="bi bi-person text-muted me-2"></i><span
                                            class="fw-semibold">{{ $p->UserPelanggan->nama ?? '-' }}</span></div>
                                    <div class="mb-2"><i
                                            class="bi bi-shop text-muted me-2"></i>{{ $p->UserPelanggan->nama_toko ?? 'Pelanggan Biasa' }}
                                    </div>
                                    <div class="mb-2"><i
                                            class="bi bi-telephone text-muted me-2"></i>{{ $p->UserPelanggan->telepon }}
                                    </div>
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-geo-alt-fill text-danger me-2 mt-1"></i>
                                        <span>{{ $p->UserPelanggan->alamat }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3 h-100 border border-light">
                                    <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;">INFORMASI TRANSAKSI</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><i class="bi bi-calendar3 me-2"></i>Waktu Pesan</span>
                                        <span
                                            class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted"><i class="bi bi-wallet2 me-2"></i>Metode
                                            Pembayaran</span>
                                        <span
                                            class="fw-semibold">{{ $p->metode_pembayaran == 0 ? 'Transfer' : 'Kontrabon' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted"><i class="bi bi-check-circle me-2"></i>Status
                                            Pembayaran</span>
                                        @if ($p->status_pembayaran == 1)
                                            <span class="text-success fw-bold">LUNAS</span>
                                        @else
                                            <span class="text-warning fw-bold">BELUM LUNAS</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="fw-bold text-dark mb-3" style="font-size: 13px;"><i
                                class="bi bi-inboxes me-2"></i>RINCIAN BARANG</h6>
                        <div class="table-responsive border rounded-3 overflow-hidden mb-3">
                            <table class="table table-borderless table-striped align-middle mb-0 text-sm">
                                <thead class="bg-light border-bottom">
                                    <tr class="text-muted" style="font-size: 12px;">
                                        <th class="ps-3 py-2">PRODUK</th>
                                        <th class="py-2 text-center">QTY</th>
                                        <th class="py-2 text-end">HARGA</th>
                                        <th class="pe-3 py-2 text-end">SUBTOTAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($p->items as $item)
                                        <tr>
                                            <td class="ps-3 py-2">
                                                <div class="fw-semibold text-dark">
                                                    {{ $item->nama ?? 'Produk Tidak Ditemukan' }}</div>
                                                <div class="text-muted" style="font-size: 10px;">SKU:
                                                    {{ $item->produk_id }}</div>
                                            </td>
                                            <td class="py-2 text-center fs-6 fw-bold">{{ $item->jumlah }}</td>
                                            <td class="py-2 text-end">Rp {{ number_format($item->harga, 0, ',', '.') }}
                                            </td>
                                            <td class="pe-3 py-2 text-end fw-bold">Rp
                                                {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3 pe-3">
                            <div class="d-flex align-items-center gap-5 border-top pt-2" style="min-width: 320px;">
                                <div class="fw-semibold">TOTAL KESELURUHAN :</div>
                                <div class="fw-bold text-end fs-5">Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        @if ($p->catatan)
                            <div
                                class="mt-4 p-3 bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3">
                                <div class="fw-bold text-warning-emphasis mb-1" style="font-size: 12px;">
                                    <i class="bi bi-chat-left-text me-2"></i>CATATAN PEMBELI:
                                </div>
                                <div class="text-dark small">{{ $p->catatan }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer border-top-0 bg-light py-3 d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary rounded-3 px-4 fw-semibold"
                            data-bs-dismiss="modal">Tutup</button>
                        <div class="d-flex gap-2">
                            @if ($p->status == 0)
                                <a href="{{ route('admin.pesanan.resi', $p->nomor) }}" target="_blank"
                                    class="btn btn-outline-dark fw-semibold rounded-3 px-3 d-flex align-items-center">
                                    <i class="bi bi-file-earmark-post me-2"></i> Cetak Resi
                                </a>
                                
                                <a href="{{ route('admin.pesanan.faktur', $p->nomor) }}" target="_blank"
                                    class="btn btn-outline-dark fw-semibold rounded-3 px-3 d-flex align-items-center">
                                    <i class="bi bi-printer me-2"></i> Cetak Faktur PDF
                                </a>
                                <form action="{{ route('admin.pesanan.kirim', $p->nomor) }}" method="POST"
                                    class="m-0 form-kirim">
                                    @csrf
                                    <button type="button"
                                        class="btn btn-primary fw-semibold rounded-3 px-4 d-flex align-items-center btn-kirim">
                                        <i class="bi bi-truck me-2"></i> Proses Kirim
                                    </button>
                                </form>
                            @endif
                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-kirim').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                    title: 'Kirim Pesanan?',
                    html: `
                <div style="font-size:14px;">
                    Pastikan:
                    <ul style="text-align:left; margin-top:10px;">
                        <li>Barang fisik sudah sesuai dengan daftar</li>
                        <li>Jumlah kuantitas sudah benar</li>
                        <li>Paket sudah siap diangkut kurir</li>
                    </ul>
                </div>
            `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kirim Sekarang!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#0d6efd',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Konfirmasi Pesanan Selesai
        document.querySelectorAll('.btn-selesai').forEach(btn => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Konfirmasi Pesanan Selesai?',
                    html: `
                    <div style="font-size:14px;">
                        Pastikan Anda telah mengecek status tracking di aplikasi ekspedisi dan memastikan barang telah diterima oleh pelanggan dengan baik.
                    </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Selesaikan!',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        @if (Session::has('toast_success'))
            document.addEventListener("DOMContentLoaded", function() {
                showToast("{{ Session::get('toast_success') }}");
            });
        @endif

        function showToast(msg) {
            document.getElementById('toastMsg').textContent = msg;
            new bootstrap.Toast(document.getElementById('liveToast')).show();
        }
    </script>
@endsection