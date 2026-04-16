@extends('layouts.app')

@section('title', 'Kelola Pesanan')
@section('page', 'Kelola Pesanan')

@section('content')
    <div class="p-4" style="background-color: #f8f9fa; min-height: 100vh;">

        {{-- HEADER --}}
        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="h4 fw-bold mb-1 d-flex align-items-center">
                        <i class="bi bi-list-ul me-2" style="color: #dc3545;"></i>Kelola Pesanan
                    </div>
                    <div class="text-muted small">Pantau dan perbarui status pengiriman pesanan</div>
                </div>
            </div>
        </div>

        {{-- STAT CARD --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #f57c00 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Perlu Diproses</div>
                                <div class="h3 fw-bold mb-0 text-warning">{{ $statDiproses }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Menunggu dikirim</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #fff3e0; width: 40px; height: 40px;">
                                <i class="bi bi-hourglass-split fs-5" style="color: #f57c00;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #1565c0 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Sedang Dikirim</div>
                                <div class="h3 fw-bold mb-0 text-primary">{{ $statDikirim }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Dalam perjalanan</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e3f2fd; width: 40px; height: 40px;">
                                <i class="bi bi-truck fs-5" style="color: #1565c0;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card h-100 border-0 shadow-sm border-start border-4 rounded-3"
                    style="border-color: #2d6a4f !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-secondary small fw-semibold">Selesai Hari Ini</div>
                                <div class="h3 fw-bold mb-0 text-success">{{ $statSelesaiHariIni }}</div>
                                <div class="text-muted mt-1" style="font-size: 11px;">Pesanan sukses</div>
                            </div>
                            <div class="rounded-3 p-2 d-flex align-items-center justify-content-center"
                                style="background-color: #e8f5e9; width: 40px; height: 40px;">
                                <i class="bi bi-bag-check fs-5" style="color: #2d6a4f;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH --}}
        <form action="{{ url()->current() }}" method="GET"
            class="bg-white p-3 rounded-3 shadow-sm mb-3 d-flex flex-wrap gap-2 align-items-center">
            
            <div class="input-group input-group-sm" style="max-width: 300px;">
                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ request('search') }}"
                    class="form-control bg-light border-start-0" placeholder="No. pesanan / nama...">
            </div>

            <select name="metode" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">Semua Metode Bayar</option>
                <option value="0" {{ request('metode') == '0' ? 'selected' : '' }}>Cash Lunas</option>
                <option value="1" {{ request('metode') == '1' ? 'selected' : '' }}>Kontrabon</option>
            </select>

            <a href="{{ url()->current() }}" class="btn btn-link btn-sm text-decoration-none text-muted p-0 ms-1">
                <i class="bi bi-x"></i> Reset
            </a>

            <div class="ms-auto text-muted small">
                Menampilkan <b class="text-dark">{{ $pesanan->firstItem() ?? 0 }}</b> sampai <b class="text-dark">{{ $pesanan->lastItem() ?? 0 }}</b> dari {{ $pesanan->total() }} pesanan
            </div>
        </form>

        {{-- TABLE DATA --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th class="ps-3 py-3 border-0">NO. PESANAN</th>
                            <th class="py-3 border-0">PELANGGAN</th>
                            <th class="py-3 border-0">TOTAL</th>
                            <th class="py-3 border-0">PEMBAYARAN</th>
                            <th class="py-3 border-0 text-center">STATUS</th>
                            <th class="py-3 border-0 text-center" style="width:185px;">AKSI GUDANG</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pesanan as $p)
                            <tr class="{{ $p->status_pesanan == 3 ? 'table-danger bg-opacity-10' : ($p->status_pesanan == 2 ? 'opacity-75' : '') }}">
                                <td class="ps-3">
                                    <div class="fw-bold small">{{ $p->nomor_pesanan }}</div>
                                    <div class="text-muted" style="font-size:11px;">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->format('d F Y . H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold small">{{ $p->nama }}</div>
                                    <div class="text-muted" style="font-size:11px;">{{ $p->nama_toko }}</div>
                                </td> 
                                <td class="fw-bold text-danger" style="font-size:13.5px;">
                                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </td>

                                {{-- Status Pembayaran --}}
                                <td>
                                    @if ($p->metode_pembayaran == '1')
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1">Kontrabon</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Cash</span>
                                        @if ($p->status_pembayaran == 1)
                                            <div class="text-success mt-1 fw-semibold" style="font-size:10px;"><i class="bi bi-check-circle me-1"></i>Lunas</div>
                                        @else
                                            <div class="text-danger mt-1 fw-semibold" style="font-size:10px;"><i class="bi bi-clock me-1"></i>Belum Bayar</div>
                                        @endif
                                    @endif
                                </td>

                                {{-- Status Pengiriman --}}
                                <td class="text-center">
                                    @if ($p->status_pesanan == 0)
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-50 px-2 py-1">Perlu Diproses</span>
                                    @elseif ($p->status_pesanan == 1)
                                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info border-opacity-50 px-2 py-1">Sedang Dikirim</span>
                                    @elseif ($p->status_pesanan == 2)
                                        <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-50 px-2 py-1">Selesai</span>
                                    @elseif ($p->status_pesanan == 3)
                                        <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-50 px-2 py-1">Dibatalkan</span>
                                    @endif
                                </td>

                                {{-- Tombol Aksi Gudang --}}
                                <td>
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        <button type="button" class="btn btn-sm btn-light border text-primary px-2 py-1 rounded-2" 
                                            data-bs-toggle="modal" data-bs-target="#modalDetail{{ $p->nomor_pesanan }}">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>

                                        @if ($p->status_pesanan == 0)
                                            <form action="{{ url('/gudang/pesanan/' . $p->nomor_pesanan . '/kirim') }}"
                                                method="POST" class="m-0">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary fw-semibold px-2 py-1 rounded-2 d-flex align-items-center gap-1"
                                                    onclick="return confirm('Kirim pesanan ini sekarang?');">
                                                    <i class="bi bi-truck"></i> Kirim
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 opacity-50 d-block mb-2"></i>
                                    Belum ada pesanan masuk.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="card-footer bg-white py-3 border-top border-light d-flex justify-content-between align-items-center">
                <span class="text-muted" style="font-size: 12px;">
                    Halaman {{ $pesanan->currentPage() }} dari {{ $pesanan->lastPage() }}
                </span>
                
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

    {{-- ================= MODAL DETAIL PESANAN ================= --}}
    @foreach ($pesanan as $p)
    <div class="modal fade" id="modalDetail{{ $p->nomor_pesanan }}" tabindex="-1" aria-labelledby="modalDetailLabel{{ $p->nomor_pesanan }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-bottom bg-light rounded-top-4 py-3 px-4">
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalDetailLabel{{ $p->nomor_pesanan }}">Detail Pesanan</h5>
                        <div class="text-muted small mt-1 font-monospace">{{ $p->nomor_pesanan }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3 mb-4 bg-light rounded-3 p-3 mx-0 border">
                        <div class="col-md-6">
                            <div class="text-muted fw-semibold" style="font-size:11px; letter-spacing:0.5px;">PELANGGAN / TOKO</div>
                            <div class="fw-bold text-dark mt-1">{{ $p->nama }}</div>
                            <div class="small text-secondary">{{ $p->nama_toko }}</div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="text-muted fw-semibold" style="font-size:11px; letter-spacing:0.5px;">WAKTU PEMESANAN</div>
                            <div class="fw-bold text-dark mt-1">{{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->format('d F Y') }}</div>
                            <div class="small text-secondary">{{ \Carbon\Carbon::parse($p->tanggal_pemesanan)->format('H:i') }} WIB</div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2 text-danger"></i>Rincian Produk Disiapkan</h6>
                    
                    <div class="table-responsive border rounded-3 mb-4">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="text-muted small fw-semibold ps-3">Nama Produk</th>
                                    <th class="text-center text-muted small fw-semibold" width="15%">Harga Satuan</th>
                                    <th class="text-center text-muted small fw-semibold" width="15%">Jumlah</th>
                                    <th class="text-end text-muted small fw-semibold pe-3" width="25%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="small">
                                @foreach ($p->items as $item)
                                    <tr>
                                        <td class="ps-3 py-3">
                                            <div class="fw-semibold text-dark">{{ $item->nama_produk }}</div>
                                            <div class="text-muted font-monospace" style="font-size:10px;">{{ $item->produk_id }}</div>
                                        </td>
                                        <td class="text-center text-secondary">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                        <td class="text-center fw-bold text-dark fs-6">{{ $item->jumlah }}</td>
                                        <td class="text-end fw-bold text-dark pe-3">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="text-muted fw-semibold mb-2" style="font-size:12px;">CATATAN PELANGGAN</div>
                            <div class="p-3 bg-light rounded-3 border text-secondary small" style="min-height: 100px;">
                                {{ $p->catatan ?: 'Tidak ada catatan dari pelanggan.' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-danger bg-opacity-10 rounded-3 h-100">
                                <div class="card-body p-3 d-flex flex-column justify-content-center">
                                    <div class="d-flex justify-content-between mb-1 small text-dark">
                                        <span>Total Harga Produk</span>
                                        <span>Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2 small text-dark">
                                        <span>Metode Pembayaran</span>
                                        <span class="fw-semibold">{{ $p->metode_pembayaran == 1 ? 'Kontrabon' : 'Cash Lunas' }}</span>
                                    </div>
                                    <hr class="border-danger opacity-25 my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-danger">Total Tagihan</span>
                                        <span class="fw-bold text-danger fs-5">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0 pb-4 px-4 justify-content-between">
                    <button type="button" class="btn btn-light border fw-semibold rounded-3 px-4" data-bs-dismiss="modal">Tutup</button>
                    
                    @if ($p->status_pesanan == 0)
                        <form action="{{ url('/gudang/pesanan/' . $p->nomor_pesanan . '/kirim') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-primary fw-semibold rounded-3 px-4" onclick="return confirm('Kirim pesanan ini sekarang?');">
                                <i class="bi bi-truck me-1"></i> Proses & Kirim Pesanan
                            </button>
                        </form>
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
                showToast("{{ Session::get('toast_success') }}");
            });
        @endif

        function showToast(msg) {
            document.getElementById('toastMsg').textContent = msg;
            new bootstrap.Toast(document.getElementById('liveToast')).show();
        }
    </script>
@endsection