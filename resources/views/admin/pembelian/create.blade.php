@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="col px-4 pt-4 pb-5 bg-light min-vh-100">
    <div class="sticky-top py-3 mb-4" style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem;">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.pembelian.index') }}" class="btn btn-light border rounded-3 shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h4 class="fw-bold mb-0">Input Pembelian Stok</h4>
                <p class="text-muted mb-0 small">Pilih produk menggunakan fitur pencarian cepat Select2</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.pembelian.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            {{-- BAGIAN KIRI: INFO FAKTUR --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold text-dark"><i class="bi bi-info-circle me-2 text-danger"></i>Informasi Faktur</div>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nomor Faktur</label>
                            <input type="text" name="nomor_pembelian" class="form-control" value="{{ old('nomor_pembelian') }}" required placeholder="Contoh: PB-2024001">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Nama Supplier</label>
                            <input type="text" name="nama_supplier" class="form-control" value="{{ old('nama_supplier') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Tanggal</label>
                            <input type="date" name="tanggal_pembelian" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                @if($errors->any())
                <div class="alert alert-danger shadow-sm rounded-3">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-danger py-2 fw-bold shadow-sm rounded-3">
                        <i class="bi bi-check-circle me-2"></i>Simpan Pembelian
                    </button>
                </div>
            </div>

            {{-- BAGIAN KANAN: ITEM PRODUK --}}
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div class="fw-bold"><i class="bi bi-box-seam me-2 text-danger"></i>Item Sparepart</div>
                        <button type="button" class="btn btn-sm btn-success px-3 fw-semibold rounded-2" onclick="addBaris()">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0" id="tableItem">
                            <thead class="table-light small">
                                <tr>
                                    <th style="width: 75%" class="ps-4">Pilih Produk</th>
                                    <th style="width: 15%" class="text-center">Jumlah (Qty)</th>
                                    <th style="width: 10%" class="text-center"><i class="bi bi-gear"></i></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="baris-item">
                                    <td class="ps-4 py-3">
                                        <select name="items[0][kode_produk]" class="form-select select2-produk" required>
                                            <option value="">Ketik Kode / Nama Produk...</option>
                                            @foreach($semuaProduk as $prod)
                                                <option value="{{ $prod->kode_produk }}">
                                                    {{ $prod->kode_produk }} - {{ $prod->nama_produk }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 text-center">
                                        <input type="number" name="items[0][jumlah]" class="form-control text-center mx-auto" style="max-width: 100px;" value="1" min="1" required>
                                    </td>
                                    <td class="text-center py-3">
                                        <button type="button" class="btn btn-light text-danger btn-sm rounded-2 mt-1" onclick="removeBaris(this)">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let rowIndex = 1;

    // 1. Simpan daftar produk murni dari Backend (Blade) ke dalam variabel JS.
    // Menggunakan backtick (`) agar aman dari enter/newline.
    const opsiProdukBersih = `
        <option value="">Ketik Kode / Nama Produk...</option>
        @foreach($semuaProduk as $prod)
            <option value="{{ $prod->kode_produk }}">{{ $prod->kode_produk }} - {{ $prod->nama_produk }}</option>
        @endforeach
    `;

    // 2. Fungsi inisialisasi Select2
    function initSelect2(element) {
        element.select2({
            theme: 'bootstrap-5',
            placeholder: "Ketik Kode / Nama Produk...",
            allowClear: true,
            width: '100%'
        });
    }

    // 3. Panggil saat halaman pertama kali dimuat (untuk baris pertama)
    $(document).ready(function() {
        initSelect2($('.select2-produk'));
    });

    // 4. Fungsi Tambah Baris (Full jQuery agar lebih stabil)
    function addBaris() {
        // Buat struktur baris baru menggunakan opsi produk yang bersih
        let rowHTML = `
            <tr class="baris-item">
                <td class="ps-4 py-3">
                    <select name="items[${rowIndex}][kode_produk]" class="form-select select2-baru" required>
                        ${opsiProdukBersih}
                    </select>
                </td>
                <td class="py-3 text-center">
                    <input type="number" name="items[${rowIndex}][jumlah]" class="form-control text-center mx-auto" style="max-width: 100px;" value="1" min="1" required>
                </td>
                <td class="text-center py-3">
                    <button type="button" class="btn btn-light text-danger btn-sm rounded-2 mt-1" onclick="removeBaris(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        
        // Masukkan baris ke dalam tabel
        $('#tableItem tbody').append(rowHTML);
        
        // Cari elemen select di baris yang BARU SAJA ditambahkan, lalu inisialisasi Select2
        let selectBaru = $('#tableItem tbody tr:last').find('.select2-baru');
        initSelect2(selectBaru);

        rowIndex++;
    }

    // 5. Fungsi Hapus Baris
    function removeBaris(btn) {
        let baris = $('.baris-item');
        if (baris.length > 1) {
            let row = $(btn).closest('tr');
            
            // Hancurkan instansi Select2 sebelum menghapus baris untuk mencegah memory leak
            row.find('select').select2('destroy'); 
            row.remove();
        } else {
            alert('Minimal harus ada satu produk untuk dibeli.');
        }
    }
</script>
@endsection