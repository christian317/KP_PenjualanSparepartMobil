@extends('layouts.app')

@section('title', 'Rincian Kontrabon')
@section('page', 'Detail Kontrabon')

@section('content')
    <div class="p-4" style="background-color:#f8f9fa; min-height:100vh;">

        {{-- KEMBALI & HEADER --}}
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('owner.kontrabon.index') }}"
                class="btn btn-white border shadow-sm rounded-3 px-3 py-2 text-secondary">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <div class="d-flex align-items-center gap-2">
                    <h4 class="fw-bold mb-0 text-dark">Rincian Buku Kontrabon</h4>
                    @if ($kontrabon->status == 0)
                        <span class="badge bg-warning text-dark"><i class="bi bi-journal-medical me-1"></i>Draf</span>
                    @else
                        <span class="badge bg-success"><i class="bi bi-journal-check me-1"></i>Sudah Terbit</span>
                    @endif
                </div>
                <div class="text-muted small mt-1 font-monospace"><i
                        class="bi bi-folder2 me-1"></i>{{ $kontrabon->nomor_kontrabon }}</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-3 p-3 mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 mb-4">
            {{-- KIRI: INFO PELANGGAN --}}
            <div class="col-md-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-person-badge me-2 text-danger"></i>Informasi
                            Pelanggan</div>
                    </div>
                    <div class="card-body p-4">
                        <div class="fw-bold text-dark fs-5">{{ $kontrabon->nama }}</div>
                        <div class="badge text-black border border-danger px-2 py-1 shadow-sm" style="font-size: 13px;">
                            <i class="bi bi-wallet2 me-1"></i>Total Hutang: Rp {{ number_format($totalHutangKeseluruhan, 0, ',', '.') }}
                        </div>
                        <div class="text-secondary small mb-3">{{ $kontrabon->nama_toko }} | {{ $kontrabon->telepon }}</div>

                        <hr class="opacity-25">
                        <div class="d-flex justify-content-between small text-muted mb-2">
                            <span>Masa Tampungan Pesanan:</span>
                            <span class="text-dark fw-semibold">
                                {{ \Carbon\Carbon::parse($kontrabon->tanggal_mulai)->format('d M y') }} -
                                {{ \Carbon\Carbon::parse($kontrabon->tanggal_selesai)->format('d M y') }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Jatuh Tempo Pembayaran:</span>
                            <span class="text-dark fw-bold">
                                {{ $piutang ? \Carbon\Carbon::parse($piutang->tanggal_jatuh_tempo)->format('d F Y') : 'Belum Diterbitkan' }}
                            </span>
                        </div>
                        @if ($kontrabon->status == 0)
                            <div
                                class="bg-warning bg-opacity-10 border border-warning border-opacity-50 p-3 rounded-3 mt-4">
                                <div class="small text-dark mb-2 fw-semibold">Tutup Draf dan Terbitkan Kontrabon</div>
                                <form action="{{ route('owner.kontrabon.detail.terbitkan', $kontrabon->nomor_kontrabon) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm fw-bold w-100 shadow-sm">
                                        <i class="bi bi-send-check me-1"></i> Terbitkan Kontrabon Sekarang
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-cash-coin me-2 text-primary"></i>Ringkasan Keuangan
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 h-100">
                            <div class="col-sm-4">
                                <div
                                    class="bg-light p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center">
                                    <div class="text-muted small mb-1">Total Tagihan</div>
                                    <div class="fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div
                                    class="bg-success bg-opacity-10 border border-success border-opacity-25 p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center">
                                    <div class="text-success small fw-semibold mb-1">Telah Dibayar</div>
                                    <div class="fw-bold text-success">Rp {{ number_format($dibayar, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div
                                    class="bg-danger bg-opacity-10 border border-danger border-opacity-25 p-3 rounded-3 text-center h-100 d-flex flex-column justify-content-center">
                                    <div class="text-danger small fw-semibold mb-1">Sisa Hutang</div>
                                    <div class="fw-bold text-danger">Rp {{ number_format($sisa, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="progress mt-4" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: {{ $persen }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- DAFTAR PESANAN DALAM KONTRABON INI (MENGGUNAKAN TABEL) --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-box-seam me-2 text-secondary"></i>Daftar Pesanan
                            Tergabung</div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr class="small text-muted border-bottom">
                                        <th class="ps-4 py-3 border-0">NOMOR PESANAN</th>
                                        <th class="ps-4 py-3 border-0">TANGGAL</th>
                                        <th class="py-3 border-0 text-center">STATUS</th>
                                        <th class="text-end py-3 border-0">SUBTOTAL</th>
                                        <th class="text-center pe-4 py-3 border-0" style="width: 140px;">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pesananList as $pesanan)
                                        <tr class="border-bottom">
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-dark small">{{ $pesanan->nomor }}</div>
                                                <div class="text-muted" style="font-size:11px;">
                                                    {{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}</div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($pesanan->tanggal)->format('d M Y') }}</td>
                                            <td class="text-center">
                                                @if ($pesanan->status == 0)
                                                    <span
                                                        class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">Sedang
                                                        Diproses</span>
                                                @elseif($pesanan->status == 1)
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">Dikirim</span>
                                                @elseif($pesanan->status == 2)
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Selesai</span>
                                                @elseif($pesanan->status == 5)
                                                    <span
                                                        class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-2 py-1">Menunggu
                                                        Approval</span>
                                                @elseif ($pesanan->status == 6)
                                                    <span
                                                        class="badge bg-warning bg-opacity-25 text-warning border border-warning border-opacity-50 px-2 py-1">Pre-Order</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold text-dark small">Rp
                                                {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                            <td class="text-center pe-4">
                                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                                    {{-- Tombol Detail --}}
                                                    <button type="button"
                                                        class="btn btn-light btn-sm border text-primary px-2 py-1 rounded-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalPesanan-{{ $pesanan->nomor }}"
                                                        title="Detail Produk">
                                                        <i class="bi bi-eye"></i>
                                                    </button>

                                                    {{-- Tombol ACC & Tolak (Hanya Muncul jika Status = 5) --}}
                                                    @if ($pesanan->status == 5)
                                                        <form
                                                            action="{{ route('owner.kontrabon.approve', $pesanan->nomor) }}"
                                                            method="POST" class="m-0">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-success px-2 py-1 rounded-2"
                                                                title="Setujui"
                                                                onclick="return confirm('Setujui pesanan ini?');"><i
                                                                    class="bi bi-check-lg"></i></button>
                                                        </form>
                                                        <form
                                                            action="{{ route('owner.kontrabon.tolak', $pesanan->nomor) }}"
                                                            method="POST" class="m-0">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm btn-outline-danger px-2 py-1 rounded-2"
                                                                title="Tolak"
                                                                onclick="return confirm('Tolak pesanan ini?');"><i
                                                                    class="bi bi-x-lg"></i></button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4 small">Tidak ada pesanan
                                                aktif pada kontrabon ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- HISTORI CICILAN --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-clock-history me-2 text-secondary"></i>Histori
                            Pembayaran Kontrabon</div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted border-bottom">
                                    <th class="ps-4 py-3">ID Transaksi (Midtrans)</th>
                                    <th class="text-end pe-4 py-3">Nominal Disetor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histori_cicilan as $cicilan)
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-3">
                                            <div class="small font-monospace text-dark">
                                                {{ $cicilan->pesanan_id_midtrans }}</div>
                                            <div class="text-muted small">{{ \Carbon\Carbon::parse($cicilan->pesanan->tanggal)->format('d M Y, H:i') }}</div>
                                            <span class="badge bg-success-subtle text-success-emphasis mt-1 px-2 py-0"
                                                style="font-size: 10px;">Berhasil</span>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-success py-3 align-middle">+ Rp
                                            {{ number_format($cicilan->nominal_pembayaran, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted small">
                                            Belum ada cicilan yang disetor oleh pelanggan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL PRODUK PER PESANAN --}}
    @foreach ($pesananList as $pesanan)
        <div class="modal fade" id="modalPesanan-{{ $pesanan->nomor }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                    <div class="modal-header bg-light border-0 px-4 py-3">
                        <div>
                            <h5 class="modal-title fw-bold mb-0">Detail Produk Pesanan</h5>
                            <div class="text-muted small mt-1 font-monospace">{{ $pesanan->nomor }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted border-bottom">
                                    <th class="ps-4 py-2">Kode</th>
                                    <th class="ps-4 py-2">Nama Produk</th>
                                    <th class="text-center py-2">Qty</th>
                                    <th class="text-end pe-4 py-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pesanan->items as $item)
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-3 small">
                                            <div class="fw-semibold text-dark">{{ $item->produk_id }}</div>
                                        </td>
                                        <td class="ps-4 py-3 small">
                                            <div class="fw-semibold text-dark">{{ $item->nama_produk }}</div>
                                            <div class="text-muted mt-1" style="font-size:11px;">Rp
                                                {{ number_format($item->harga, 0, ',', '.') }}</div>
                                        </td>
                                        <td class="text-center fw-bold text-dark small">{{ $item->jumlah }}</td>
                                        <td class="text-end pe-4 small fw-bold">Rp
                                            {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="2" class="ps-4 py-3 small fw-bold text-muted text-end">TOTAL:</td>
                                    <td class="pe-4 py-3 fw-bold text-danger text-end">Rp
                                        {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer border-0 px-4 py-3 bg-light justify-content-end">
                        <button type="button" class="btn btn-secondary fw-semibold rounded-3 px-4"
                            data-bs-dismiss="modal">Tutup</button>
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
