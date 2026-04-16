@extends('layouts.pelanggan')

@section('title', 'Pesanan Saya – SparePartKu')

@section('content')
<div class="container py-4 pb-5 min-vh-100" style="max-width:860px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pelanggan.index') }}" class="btn btn-white border shadow-sm rounded-3 px-3">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h5 class="fw-bold mb-0">Riwayat Pesanan</h5>
            <p class="text-muted mb-0 small">Pantau status pengiriman dan riwayat transaksi Anda</p>
        </div>
    </div>

    @php
        // Menghitung jumlah untuk masing-masing tab status
        $userId = Session::get('user_id');
        $countSemua = \App\Models\Pesanan::where('user_pelanggan_id', $userId)->count();
        $countDiproses = \App\Models\Pesanan::where('user_pelanggan_id', $userId)->where('status_pesanan', 0)->count();
        $countDikirim = \App\Models\Pesanan::where('user_pelanggan_id', $userId)->where('status_pesanan', 1)->count();
        $countSelesai = \App\Models\Pesanan::where('user_pelanggan_id', $userId)->where('status_pesanan', 2)->count();
        $countBatal = \App\Models\Pesanan::where('user_pelanggan_id', $userId)->whereIn('status_pesanan', [3, 4])->count();
    @endphp

    <div class="d-flex gap-2 mb-4 flex-wrap">
        <button class="btn btn-danger btn-sm rounded-pill px-4 fw-semibold tab-btn active-tab" onclick="filterTab(this,'semua')">
            Semua <span class="badge bg-white text-danger ms-1">{{ $countSemua }}</span>
        </button>
        <button class="btn btn-light border btn-sm rounded-pill px-4 fw-semibold tab-btn" onclick="filterTab(this,'diproses')">
            Diproses <span class="badge bg-secondary-subtle text-secondary-emphasis ms-1">{{ $countDiproses }}</span>
        </button>
        <button class="btn btn-light border btn-sm rounded-pill px-4 fw-semibold tab-btn" onclick="filterTab(this,'dikirim')">
            Dikirim <span class="badge bg-info-subtle text-info-emphasis ms-1">{{ $countDikirim }}</span>
        </button>
        <button class="btn btn-light border btn-sm rounded-pill px-4 fw-semibold tab-btn" onclick="filterTab(this,'selesai')">
            Selesai <span class="badge bg-success-subtle text-success-emphasis ms-1">{{ $countSelesai }}</span>
        </button>
        <button class="btn btn-light border btn-sm rounded-pill px-4 fw-semibold tab-btn" onclick="filterTab(this,'batal')">
            Dibatalkan <span class="badge bg-danger-subtle text-danger-emphasis ms-1">{{ $countBatal }}</span>
        </button>
    </div>

    @forelse ($pesanan as $p)
        @php
            // Menentukan data-status untuk filter JS
            $dataStatus = 'diproses';
            if($p->status_pesanan == 1) $dataStatus = 'dikirim';
            if($p->status_pesanan == 2) $dataStatus = 'selesai';
            if($p->status_pesanan == 3 || $p->status_pesanan == 4) $dataStatus = 'batal';

            // Ambil data refund jika ada (Untuk status 3 atau 4)
            $refund = null;
            if(in_array($p->status_pesanan, [3, 4])) {
                $refund = \Illuminate\Support\Facades\DB::table('pengajuan_refund')->where('nomor_pesanan', $p->nomor_pesanan)->first();
            }
        @endphp

        <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden order-card {{ $p->status_pesanan == 3 ? 'opacity-75' : '' }}" data-status="{{ $dataStatus }}">
            
            <div class="card-header bg-white py-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="fw-bold text-dark">
                        <i class="bi bi-bag-check me-2 text-danger"></i>{{ $p->nomor_pesanan }}
                    </span>
                    <span class="text-muted small">
                        <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->format('d M Y') }}
                    </span>
                    
                    {{-- Badge Status Pesanan --}}
                    @if ($p->status_pesanan == 0)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill">
                            <i class="bi bi-hourglass-split me-1"></i>Sedang Diproses
                        </span>
                    @elseif ($p->status_pesanan == 1)
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1 rounded-pill">
                            <i class="bi bi-truck me-1"></i>Sedang Dikirim
                        </span>
                    @elseif ($p->status_pesanan == 2)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">
                            <i class="bi bi-check-circle me-1"></i>Selesai
                        </span>
                    @elseif ($p->status_pesanan == 3)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill">
                            <i class="bi bi-x-circle me-1"></i>Dibatalkan
                        </span>
                    @elseif ($p->status_pesanan == 4)
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill text-dark">
                            <i class="bi bi-clock me-1"></i>Menunggu Refund Admin
                        </span>
                    @endif
                </div>

                {{-- Badge Status Pembayaran --}}
                @if ($p->status_pembayaran == 1)
                    <span class="badge bg-success-subtle text-success-emphasis px-2 py-1 rounded-pill small">
                        <i class="bi bi-credit-card me-1"></i>Sudah Dibayar
                    </span>
                @elseif ($p->status_pembayaran == 0 && $p->metode_pembayaran == '0')
                    <span class="badge bg-danger-subtle text-danger-emphasis px-2 py-1 rounded-pill small">
                        <i class="bi bi-credit-card me-1"></i>Belum Lunas
                    </span>
                @elseif ($p->metode_pembayaran == '1')
                    <span class="badge bg-info-subtle text-info-emphasis px-2 py-1 rounded-pill small">
                        <i class="bi bi-file-text me-1"></i>Kontrabón
                    </span>
                @endif
            </div>

            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @php $totalHarga = 0; @endphp
                    @foreach ($p->items as $index => $item)
                        @php $totalHarga += ($item->harga * $item->jumlah); @endphp
                        <div class="list-group-item px-4 py-3 border-0 {{ $index > 0 ? 'border-top' : '' }}">
                            <div class="d-flex gap-3 align-items-center">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/produk/' . $item->gambar) }}" class="bg-light border rounded-3 object-fit-cover flex-shrink-0" style="width:52px;height:52px;">
                                @else
                                    <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 text-secondary" style="width:52px;height:52px;font-size:24px;">
                                        <i class="bi bi-box-seam"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small text-dark">{{ $item->nama_produk }}</div>
                                    <div class="text-muted" style="font-size:12px;">{{ $item->jumlah }} × Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                                </div>
                                <div class="fw-bold text-dark small">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 1. Shipping Progress Bar (Hanya jika Dikirim) --}}
            @if($p->status_pesanan == 1)
            <div class="px-4 pb-3 pt-2 border-top bg-light bg-opacity-50">
                <div class="d-flex justify-content-between align-items-start position-relative">
                    <div class="position-absolute top-0 start-0 bg-danger" style="height:3px;width:66%;margin-top:11px;z-index:0;"></div>
                    <div class="position-absolute top-0 start-0 bg-secondary bg-opacity-25" style="height:3px;width:100%;margin-top:11px;z-index:-1;"></div>
                    
                    <div class="d-flex flex-column align-items-center" style="z-index:1;">
                        <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white" style="width:26px;height:26px;font-size:11px;"><i class="bi bi-check"></i></div>
                        <div class="fw-semibold mt-1" style="font-size:10px;color:#2d6a4f;">Dikonfirmasi</div>
                    </div>
                    <div class="d-flex flex-column align-items-center" style="z-index:1;">
                        <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white" style="width:26px;height:26px;font-size:11px;"><i class="bi bi-check"></i></div>
                        <div class="fw-semibold mt-1" style="font-size:10px;color:#2d6a4f;">Diproses</div>
                    </div>
                    <div class="d-flex flex-column align-items-center" style="z-index:1;">
                        <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white" style="width:26px;height:26px;font-size:11px;"><i class="bi bi-truck-fill" style="font-size:9px;"></i></div>
                        <div class="fw-semibold mt-1 text-danger" style="font-size:10px;">Dikirim</div>
                    </div>
                    <div class="d-flex flex-column align-items-center" style="z-index:1;">
                        <div class="rounded-circle bg-secondary bg-opacity-25 d-flex align-items-center justify-content-center text-secondary" style="width:26px;height:26px;font-size:11px;">4</div>
                        <div class="text-secondary mt-1" style="font-size:10px;">Selesai</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 2. Kontrabon Info (Hanya jika Kontrabon) --}}
            @if($p->metode_pembayaran == '1')
            <div class="px-4 py-2 border-top bg-info bg-opacity-10 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-info"></i>
                <span class="small text-info fw-semibold">Kontrabón · Jatuh Tempo: <strong>{{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->addMonths(3)->format('d M Y') }}</strong></span>
                @if($p->status_pembayaran == 1)
                    <span class="badge bg-success-subtle text-success-emphasis ms-auto">✓ Lunas</span>
                @endif
            </div>
            @endif

            {{-- 3. Pending Refund Info (Hanya jika status 4) --}}
            @if($p->status_pesanan == 4 && $refund)
            <div class="px-4 py-3 border-top bg-warning bg-opacity-10">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-clock-history text-warning mt-1 flex-shrink-0"></i>
                    <div>
                        <div class="fw-semibold small text-dark">Pengajuan refund sedang diproses oleh admin</div>
                        <div class="text-muted" style="font-size:12px;">Refund akan ditransfer ke <strong>{{ $refund->nama_bank }} {{ $refund->nomor_rekening }}</strong> a/n <strong>{{ $refund->atas_nama }}</strong> dalam 3–5 hari kerja.</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 4. Batal/Refund Alert (Hanya jika status 3) --}}
            @if($p->status_pesanan == 3)
            <div class="px-4 py-2 border-top bg-warning bg-opacity-10 d-flex align-items-center gap-2">
                <i class="bi bi-info-circle text-warning"></i>
                <div>
                    <span class="small fw-semibold text-dark">Alasan Pembatalan:</span>
                    <span class="small text-secondary"> {{ $refund ? $refund->alasan_pembatalan : 'Dibatalkan oleh sistem/pengguna.' }}</span>
                </div>
            </div>
            @endif

            <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="text-muted" style="font-size:11px;text-transform:uppercase;letter-spacing:.5px;">Total Belanja</div>
                    <div class="fw-bold text-danger fs-5 lh-1">Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                    <div class="text-muted" style="font-size:11px;">
                        {{ ucfirst($p->metode_pembayaran) }} · {{ $p->items->count() }} item 
                        @if($p->status_pesanan == 3 && $refund && $p->metode_pembayaran == '0')
                            · Refund Selesai ({{ $refund->nama_bank }})
                        @endif
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('pelanggan.pesanan.detail_pesanan', ['nomor_pesanan' => $p->nomor_pesanan]) }}" class="btn btn-light border btn-sm rounded-3 fw-semibold" ">
                        <i class="bi bi-eye me-1"></i>Detail Pesanan
                    </a>

                    {{-- Tombol Konfirmasi Diterima --}}
                    @if ($p->status_pesanan == 1)
                        <form action="#" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-success btn-sm rounded-3 fw-semibold" onclick="return confirm('Konfirmasi pesanan diterima?');">
                                <i class="bi bi-check-circle me-1"></i>Konfirmasi Diterima
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Cetak Faktur (Hanya jika sudah dikirim/selesai) --}}
                    @if (in_array($p->status_pesanan, [1, 2]))
                        <button class="btn btn-outline-secondary btn-sm rounded-3 fw-semibold" onclick="showToast('Fitur cetak faktur akan segera hadir.')">
                            <i class="bi bi-printer me-1"></i>Cetak Faktur
                        </button>
                    @endif

                    {{-- Tombol Batalkan & Refund (Hanya jika status 0 dan Cash Lunas) --}}
                    @if ($p->status_pesanan == 0 && $p->metode_pembayaran == '0' && $p->status_pembayaran == 1)
                        <button class="btn btn-outline-danger btn-sm rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRefund-{{ $p->nomor_pesanan }}">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Batalkan & Refund
                        </button>
                    @endif

                    {{-- Tombol Bayar Sekarang (Hanya jika status 0 dan Cash Belum Lunas) --}}
                    @if ($p->status_pesanan == 0 && $p->metode_pembayaran == '0' && $p->status_pembayaran == 0)
                        <button class="btn btn-danger btn-sm rounded-3 fw-semibold" onclick="showToast('Melanjutkan pembayaran...')">
                            <i class="bi bi-wallet2 me-1"></i>Bayar Sekarang
                        </button>
                    @endif
                </div>
            </div>
        </div>

        @if ($p->status_pesanan == 0 && $p->metode_pembayaran == '0' && $p->status_pembayaran == 1)
        <div class="modal fade" id="modalRefund-{{ $p->nomor_pesanan }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                    <form action="{{ route('pelanggan.pesanan.cancel_pesanan', $p->nomor_pesanan) }}" method="POST">
                        @csrf
                        <div class="modal-header bg-danger text-white border-0 px-4 py-3">
                            <div>
                                <h5 class="modal-title fw-bold mb-0"><i class="bi bi-arrow-counterclockwise me-2"></i>Batalkan & Ajukan Refund</h5>
                                <div class="opacity-75 small">{{ $p->nomor_pesanan }} · Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-4 py-3">
                            <div class="alert border-0 bg-warning bg-opacity-10 text-dark d-flex gap-2 align-items-start mb-4 rounded-3">
                                <i class="bi bi-info-circle text-warning mt-1 flex-shrink-0"></i>
                                <div class="small">
                                    Dana Anda akan ditransfer manual oleh Admin ke rekening yang Anda isi di bawah ini. Proses refund membutuhkan <strong>3–5 hari kerja</strong>.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small">Alasan Pembatalan <span class="text-danger">*</span></label>
                                <textarea name="alasan_pembatalan" class="form-control rounded-3" rows="3" placeholder="Contoh: Salah pilih barang, ukuran tidak sesuai…" required></textarea>
                            </div>
                            <hr class="text-secondary opacity-25">
                            <div class="fw-semibold small text-dark mb-3"><i class="bi bi-bank me-2 text-danger"></i>Info Rekening Tujuan Refund</div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">Bank <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_bank" class="form-control rounded-3" placeholder="BCA / Mandiri / GoPay" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-semibold small">No. Rekening <span class="text-danger">*</span></label>
                                    <input type="number" name="nomor_rekening" class="form-control rounded-3" placeholder="0812xxxx" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small">Atas Nama (Sesuai Rekening) <span class="text-danger">*</span></label>
                                    <input type="text" name="atas_nama" class="form-control rounded-3" placeholder="Nama sesuai rekening bank" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 py-3 bg-light">
                            <button type="button" class="btn btn-light border fw-semibold rounded-3" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger fw-bold rounded-3 px-4">
                                <i class="bi bi-send me-2"></i>Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @empty
        <div id="empty-state" class="text-center py-5 my-4">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:96px;height:96px;">
                <i class="bi bi-box-seam text-secondary" style="font-size:44px;opacity:.4;"></i>
            </div>
            <h6 class="fw-bold text-dark">Tidak ada pesanan</h6>
            <p class="text-muted small mb-4">Anda belum memiliki riwayat transaksi.</p>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-danger rounded-pill px-4 fw-semibold">
                <i class="bi bi-grid me-2"></i>Mulai Belanja
            </a>
        </div>
    @endforelse

    <div id="empty-state-filter" class="d-none text-center py-5 my-4">
        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:96px;height:96px;">
            <i class="bi bi-box-seam text-secondary" style="font-size:44px;opacity:.4;"></i>
        </div>
        <h6 class="fw-bold text-dark">Tidak ada pesanan</h6>
        <p class="text-muted small mb-4">Belum ada pesanan dengan status ini.</p>
    </div>

    {{-- Pagination Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $pesanan->links('pagination::bootstrap-5') }}
    </div>

</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index:99999;">
    <div id="liveToast" class="toast align-items-center bg-dark text-white border-0 shadow" role="alert" data-bs-delay="3000">
        <div class="d-flex">
            <div class="toast-body fw-semibold d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success"></i>
                <span id="toast-msg">OK</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<script>
    // Menampilkan pesan sukses dari Controller (Flash Message)
    @if(Session::has('toast_success'))
        document.addEventListener("DOMContentLoaded", function() {
            showToast("{{ Session::get('toast_success') }}");
        });
    @endif

    // ── Toast Script ──
    function showToast(msg) {
        document.getElementById('toast-msg').textContent = msg;
        new bootstrap.Toast(document.getElementById('liveToast')).show();
    }

    // ── Filter Tab Script ──
    function filterTab(btn, status) {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('btn-danger', 'active-tab');
            b.classList.add('btn-light', 'border');
        });
        btn.classList.add('btn-danger', 'active-tab');
        btn.classList.remove('btn-light', 'border');

        const cards = document.querySelectorAll('.order-card');
        let visible = 0;
        cards.forEach(c => {
            const match = status === 'semua' || c.dataset.status === status;
            c.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        // Tampilkan "empty state" jika hasil filter kosong
        document.getElementById('empty-state-filter').classList.toggle('d-none', visible > 0);
        
        // Sembunyikan pagination bawaan jika sedang menggunakan filter tab (karena JS hanya memfilter halaman saat ini)
        const pagination = document.querySelector('.pagination');
        if(pagination) pagination.parentElement.style.display = status === 'semua' ? 'flex' : 'none';
    }
</script>
@endsection