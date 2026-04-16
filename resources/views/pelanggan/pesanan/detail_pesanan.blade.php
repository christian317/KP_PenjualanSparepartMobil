@extends('layouts.pelanggan')

@section('title', 'Detail Pesanan – SparePartKu')

@section('content')
<div class="container py-4 pb-5 min-vh-100" style="max-width:860px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pelanggan.pesanan.index') }}" class="btn btn-white border shadow-sm rounded-3 px-3">
            <i class="bi bi-arrow-left text-secondary"></i>
        </a>
        <div>
            <h5 class="fw-bold mb-0">Detail Pesanan</h5>
            <p class="text-muted mb-0 small">{{ $pesanan->nomor_pesanan }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="text-muted small mb-1">Tanggal Pemesanan</div>
                    <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($pesanan->tanggal_pemesanan)->format('d F Y, H:i') }} WIB</div>
                </div>
                
                <div class="text-end">
                    <div class="text-muted small mb-1">Status Pesanan</div>
                    @if ($pesanan->status_pesanan == 0)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">Sedang Diproses</span>
                    @elseif ($pesanan->status_pesanan == 1)
                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill">Sedang Dikirim</span>
                    @elseif ($pesanan->status_pesanan == 2)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">Selesai</span>
                    @elseif ($pesanan->status_pesanan == 3)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill">Dibatalkan</span>
                    @elseif ($pesanan->status_pesanan == 4)
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded-pill text-dark">Menunggu Refund Admin</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Shipping Progress Bar (Hanya jika Dikirim / Selesai) --}}
        @if(in_array($pesanan->status_pesanan, [1, 2]))
        <div class="px-4 pb-4 pt-3 border-top bg-light bg-opacity-50">
            <div class="d-flex justify-content-between align-items-start position-relative px-2">
                <div class="position-absolute top-0 start-0 bg-danger" style="height:3px; width: {{ $pesanan->status_pesanan == 2 ? '100%' : '66%' }}; margin-top:11px; z-index:0;"></div>
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
                    <div class="rounded-circle {{ $pesanan->status_pesanan == 2 ? 'bg-danger text-white' : 'bg-secondary bg-opacity-25 text-secondary' }} d-flex align-items-center justify-content-center" style="width:26px;height:26px;font-size:11px;">
                        @if($pesanan->status_pesanan == 2) <i class="bi bi-check"></i> @else 4 @endif
                    </div>
                    <div class="{{ $pesanan->status_pesanan == 2 ? 'fw-semibold text-danger' : 'text-secondary' }} mt-1" style="font-size:10px;">Selesai</div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-box-seam me-2 text-danger"></i>Rincian Produk</h6>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach ($items as $index => $item)
                    <div class="list-group-item px-4 py-3 border-0 {{ $index > 0 ? 'border-top' : '' }}">
                        <div class="d-flex gap-3 align-items-center">
                            @if($item->gambar)
                                <img src="{{ asset('storage/produk/' . $item->gambar) }}" class="bg-light border rounded-3 object-fit-cover flex-shrink-0" style="width:60px;height:60px;">
                            @else
                                <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 text-secondary" style="width:60px;height:60px;font-size:24px;">
                                    <i class="bi bi-card-image"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark">{{ $item->nama_produk }}</div>
                                <div class="text-muted small">{{ $item->jumlah }} {{ $item->unit }} × Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                            </div>
                            <div class="fw-bold text-dark">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        {{-- Info Refund jika Batal/Menunggu Refund --}}
        @if($refund)
        <div class="px-4 py-3 border-top bg-warning bg-opacity-10">
            <div class="d-flex align-items-start gap-2">
                <i class="bi bi-info-circle text-warning mt-1 flex-shrink-0"></i>
                <div>
                    <div class="fw-semibold small text-dark mb-1">Informasi Pembatalan & Refund</div>
                    <div class="text-muted" style="font-size:12px;">
                        <strong>Alasan:</strong> {{ $refund->alasan_batal }}<br>
                        <strong>Rekening Tujuan:</strong> {{ $refund->nama_bank }} - {{ $refund->nomor_rekening }} (a/n {{ $refund->atas_nama }})
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Catatan Pesanan --}}
        @if($pesanan->catatan)
        <div class="px-4 py-3 border-top bg-light">
            <div class="fw-semibold small text-dark mb-1">Catatan Pembeli:</div>
            <div class="text-muted small fst-italic">"{{ $pesanan->catatan }}"</div>
        </div>
        @endif
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-receipt me-2 text-danger"></i>Rincian Pembayaran</h6>
        </div>
        <div class="card-body p-4">
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Metode Pembayaran</span>
                <span class="fw-semibold text-uppercase">{{ $pesanan->metode_pembayaran }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small">
                <span class="text-muted">Status Pembayaran</span>
                @if ($pesanan->status_pembayaran == 1)
                    <span class="text-success fw-bold">Lunas</span>
                @elseif ($pesanan->status_pembayaran == 0 && $pesanan->metode_pembayaran == '0')
                    <span class="text-danger fw-bold">Belum Lunas</span>
                @elseif ($pesanan->metode_pembayaran == '1')
                    <span class="text-info fw-bold">Kontrabón (Piutang Aktif)</span>
                @endif
            </div>
            <hr class="opacity-25 my-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark">Total Belanja</span>
                <h5 class="fw-bold text-danger mb-0">Rp {{ number_format($totalHarga, 0, ',', '.') }}</h5>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 justify-content-end">
        @if ($pesanan->status_pesanan == 0 && $pesanan->metode_pembayaran == '0' && $pesanan->status_pembayaran == 1)
            <button class="btn btn-outline-danger rounded-3 fw-semibold px-4" data-bs-toggle="modal" data-bs-target="#modalRefund-{{ $pesanan->nomor_pesanan }}">
                <i class="bi bi-arrow-counterclockwise me-2"></i>Batalkan & Refund
            </button>
        @endif

        @if ($pesanan->status_pesanan == 0 && $pesanan->status_pembayaran == 0)
            <button class="btn btn-danger rounded-3 fw-bold px-5">
                Bayar Sekarang
            </button>
        @endif
    </div>

</div>

@if ($pesanan->status_pesanan == 0 && $pesanan->metode_pembayaran == '0' && $pesanan->status_pembayaran == 1)
<div class="modal fade" id="modalRefund-{{ $pesanan->nomor_pesanan }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <form action="{{ route('pelanggan.pesanan.cancel_pesanan', $pesanan->nomor_pesanan) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white border-0 px-4 py-3">
                    <div>
                        <h5 class="modal-title fw-bold mb-0"><i class="bi bi-arrow-counterclockwise me-2"></i>Batalkan & Ajukan Refund</h5>
                        <div class="opacity-75 small">{{ $pesanan->nomor_pesanan }} · Rp {{ number_format($totalHarga, 0, ',', '.') }}</div>
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

@endsection