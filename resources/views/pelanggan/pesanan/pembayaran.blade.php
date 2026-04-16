@extends('layouts.pelanggan')
@section('content')
<div class="container py-5 text-center" style="min-height: 70vh;">
    <div class="card border-0 shadow-sm p-5 mx-auto" style="max-width: 500px;">
        <i class="bi bi-wallet2 text-danger mb-3" style="font-size: 50px;"></i>
        <h4 class="fw-bold">Selesaikan Pembayaran</h4>
        <p class="text-muted">Silakan klik tombol di bawah untuk melanjutkan ke metode pembayaran pilihan Anda.</p>
        <hr class="opacity-25">
        <div class="mb-4">
            <small class="text-muted d-block">Total yang harus dibayar:</small>
            <h2 class="fw-bold text-danger">Rp {{ number_format($totalBayar, 0, ',', '.') }}</h2>
        </div>
        <button id="pay-button" class="btn btn-danger w-100 py-3 fw-bold rounded-3">BAYAR SEKARANG</button>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function () {
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) { window.location.href = "{{ route('pelanggan.index') }}"; },
            onPending: function(result) { window.location.href = "{{ route('pelanggan.index') }}"; },
            onError: function(result) { alert("Pembayaran gagal!"); }
        });
    };
</script>
@endsection