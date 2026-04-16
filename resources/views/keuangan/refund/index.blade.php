@extends('layouts.app')
@section('content')
<div class="col px-4 pt-4 pb-5 bg-light min-vh-100">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Daftar Pengajuan Refund</h4>
            <p class="text-muted mb-0 small">Transfer uang ke pelanggan secara manual, lalu tandai selesai di sini.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 text-secondary small fw-semibold">No. Pesanan</th>
                            <th class="py-3 text-secondary small fw-semibold">Pelanggan</th>
                            <th class="py-3 text-secondary small fw-semibold">Informasi Rekening Tujuan</th>
                            <th class="py-3 text-secondary small fw-semibold">Alasan Batal</th>
                            <th class="py-3 text-secondary small fw-semibold text-center">Status</th>
                            <th class="py-3 pe-4 text-end text-secondary small fw-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($refunds as $r)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-danger">{{ $r->nomor_pesanan }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($r->created_at)->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $r->nama }}</div>
                                    <div class="text-muted" style="font-size: 11px;">{{ $r->nama_toko }}</div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="badge bg-dark">{{ $r->nama_bank }}</span>
                                        <div class="fw-bold text-dark">{{ $r->nomor_rekening }}</div>
                                    </div>
                                    <div class="text-muted mt-1" style="font-size: 11px;">a/n {{ $r->atas_nama }}</div>
                                </td>
                                <td>
                                    <div class="text-dark small" style="max-width: 200px;">{{ $r->alasan_batal }}</div>
                                </td>
                                <td class="text-center">
                                    @if ($r->status_refund == 0)
                                        <span class="badge bg-warning text-dark">Menunggu Transfer</span>
                                    @else
                                        <span class="badge bg-success">Refund Selesai</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    @if ($r->status_refund == 0)
                                        <form action="{{ route('keuangan.refund.selesai', $r->nomor_pesanan) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-2">
                                                <input type="file" name="bukti_transfer" class="form-control form-control-sm" required accept="image/jpeg,image/png,image/jpg">
                                            </div>
                                            <button type="submit" class="btn btn-success btn-sm fw-semibold rounded-3 shadow-sm w-100" onclick="return confirm('Apakah Anda yakin sudah mentransfer dan mengunggah bukti yang benar?');">
                                                <i class="bi bi-check-circle me-1"></i> Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection