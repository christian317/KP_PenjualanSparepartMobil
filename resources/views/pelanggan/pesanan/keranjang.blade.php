@extends('layouts.pelanggan')

@section('title', 'Keranjang Belanja')

@section('content')
    <div style="max-width:1100px;margin:0 auto;" class="px-3 px-md-4 py-4 pb-5">

        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            <a href="{{ route('pelanggan.index') }}"
                class="btn btn-link text-danger fw-semibold text-decoration-none p-0 small">
                <i class="bi bi-arrow-left me-1"></i>Lanjut Belanja
            </a>
            <h5 class="fw-bold mb-0"><i class="bi bi-cart3 me-2"></i>Keranjang Belanja</h5>
            <span class="badge bg-light text-secondary border">{{ count($cart) }} Item</span>
        </div>

        <div class="row g-4">

            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 rounded-top-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" checked>
                            <label class="form-check-label small fw-semibold" for="selectAll">Pilih Semua</label>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width:40px;"></th>
                                    <th class="text-muted fw-semibold small">Produk</th>
                                    <th class="text-center text-muted fw-semibold small">Jumlah</th>
                                    <th class="text-end text-muted fw-semibold small">Harga</th>
                                    <th style="width:40px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                @forelse($cart as $item)
                                    <tr class="cart-item">
                                        <td class="ps-3">
                                            <input class="form-check-input item-checkbox" type="checkbox"
                                                data-id="{{ $item->produk_id }}" data-price="{{ $item->produk->harga }}"
                                                data-quantity="{{ $item->jumlah }}"
                                                {{ $item->is_selected ? 'checked' : 'checked' }}>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-3 align-items-center">
                                                <img src="{{ asset('storage/produk/'.$item->produk->gambar) }}" class="rounded-3" style="width:52px;height:52px;object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold small">{{ $item->produk->nama_produk }}</div>
                                                    <div class="text-muted" style="font-size:11px;">{{ $item->produk->brand->nama }} · SKU: {{ $item->produk_id }}</div>
                                                    <div class="text-danger fw-bold d-md-none" style="font-size:13px;">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-1">
                                                <form action="{{ route('pelanggan.pesanan.keranjang.update') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->produk_id }}">
                                                    <input type="hidden" name="type" value="minus">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2 py-0">−</button>
                                                </form>

                                                <span class="fw-bold small px-2">{{ $item->jumlah }}</span>

                                                <form action="{{ route('pelanggan.pesanan.keranjang.update') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $item->produk_id }}">
                                                    <input type="hidden" name="type" value="plus">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-2 py-0">+</button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="text-end fw-bold text-dark item-subtotal">
                                            Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('pelanggan.pesanan.keranjang.hapus') }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $item->produk_id }}">
                                                <button type="submit" class="btn btn-link text-secondary p-0" onclick="return confirm('Hapus produk ini?')">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted small">Keranjang belanja kosong</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Subtotal (<span id="checkedCount">0</span> produk)</span>
                            <span class="fw-semibold text-dark" id="totalHarga">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small">
                            <span class="text-muted">Total Diskon</span>
                            <span class="text-success fw-semibold">-Rp 0</span>
                        </div>
                        <hr class="opacity-25">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold">Total Bayar</span>
                            <h5 class="fw-bold text-danger mb-0" id="totalBayar">Rp 0</h5>
                        </div>

                        <button type="button" class="btn btn-danger w-100 fw-bold py-2 shadow-sm rounded-3" id="btnCheckout"
                            {{ count($cart) == 0 ? 'disabled' : '' }}>
                            Check Out<i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>

                <div class="mt-3 p-3 bg-white rounded-3 border-start border-4 border-warning shadow-sm">
                    <div class="d-flex gap-2">
                        <i class="bi bi-info-circle-fill text-warning"></i>
                        <small class="text-muted" style="font-size: 11px;">Pelanggan Mitra dapat memilih metode
                            <b>Kontrabon 3 Bulan</b> pada langkah selanjutnya.</small>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <form id="formCheckout" action="{{ route('pelanggan.pesanan.checkout') }}" method="GET" class="d-none">
        </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const totalHargaEl = document.getElementById('totalHarga');
            const totalBayarEl = document.getElementById('totalBayar');
            const checkedCountEl = document.getElementById('checkedCount');
            const btnCheckout = document.getElementById('btnCheckout');

            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(number);
            }

            function calculateTotal() {
                let total = 0;
                let count = 0;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const price = parseInt(cb.getAttribute('data-price'));
                        const qty = parseInt(cb.getAttribute('data-quantity'));
                        total += price * qty;
                        count++;
                    }
                });

                const formattedTotal = formatRupiah(total);
                totalHargaEl.textContent = formattedTotal;
                totalBayarEl.textContent = formattedTotal;
                checkedCountEl.textContent = count;

                btnCheckout.disabled = (count === 0);
            }

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                calculateTotal();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(checkboxes).every(c => c.checked);
                    selectAll.checked = allChecked;
                    calculateTotal();
                });
            });

            calculateTotal();

            // PERBAIKAN 3: Menangani proses submit ke Controller
            btnCheckout.addEventListener('click', function() {
                const form = document.getElementById('formCheckout');
                form.innerHTML = ''; // Bersihkan isi form jika tombol diklik ulang
                
                let adaYangDicentang = false;

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        adaYangDicentang = true;
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'produk_terpilih[]';
                        input.value = cb.getAttribute('data-id'); 
                        form.appendChild(input);
                    }
                });

                if (adaYangDicentang) {
                    form.submit(); // Kirim data produk yang dicentang
                } else {
                    alert('Silakan pilih minimal satu produk untuk di-checkout.');
                }
            });
        });
    </script>
@endsection