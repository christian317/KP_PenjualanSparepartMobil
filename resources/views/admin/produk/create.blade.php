@extends('layouts.app')

@section('content')
    <div class="col px-4 pt-4 pb-5 bg-light min-vh-100">

        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.produk.index') }}"
                    class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Tambah Produk Baru</h4>
                    <p class="text-muted mb-0 small">Isi data produk sparepart dengan lengkap</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="fw-bold d-flex align-items-center text-dark">
                                <i class="bi bi-info-circle me-2 text-danger"></i>Informasi Produk
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold small text-secondary">Nama Produk</label>
                                    <input name="nama_produk" type="text" class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Kode</label>
                                    <input name="kode_produk" type="text" class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Kategori</label>
                                    <select name="kategori_id" class="form-select rounded-3 py-2" required>
                                        <option value="">-- Pilih Kategori --</option>

                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Merek / Brand</label>
                                    <select name="brand_id" class="form-select rounded-3 py-2" required>
                                        <option value="">-- Pilih Brand --</option>

                                        @foreach ($brand as $b)
                                            <option value="{{ $b->id }}">
                                                {{ $b->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Harga Jual (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">Rp</span>
                                        <input name="harga" type="number" class="form-control rounded-end-3 py-2"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Stok Awal</label>
                                    <div class="input-group">
                                        <input name="stok_produk" class="form-control py-2" type="number" min="0"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Minimal Stok</label>
                                    <div class="input-group">
                                        <input name="min_stok" class="form-control py-2" type="number" min="0"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small text-secondary">Satuan</label>
                                    <select name="unit" class="form-select rounded-3 py-2" required>
                                        <option selected>set</option>
                                        <option>pcs</option>
                                    </select>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label fw-semibold small text-secondary">Part Model <span
                                            class="text-danger">*</span></label>
                                    <div class="card border rounded-3 shadow-sm">
                                        <div class="card-body p-3"
                                            style="max-height: 200px; overflow-y: auto; background-color: #fcfcfc;">
                                            <select name="jenis_mobil_id[]" class="form-select select2" multiple required>
                                                @foreach ($jenis_mobil as $mobil)
                                                    <option value="{{ $mobil->id }}">
                                                        {{ $mobil->merk_mobil }} {{ $mobil->nama_mobil }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            @if ($jenis_mobil->isEmpty())
                                                <div class="text-center text-muted small mt-2">
                                                    Data jenis mobil belum ada
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">
                                        <i class="bi bi-info-circle me-1"></i>Anda dapat menambahkan lebih dari satu jenis
                                        mobil.
                                    </small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Deskripsi Produk</label>
                                    <textarea name="deskripsi_produk" class="form-control rounded-3" rows="3"></textarea>
                                </div>
                                <div class="col-12 pt-2">
                                    <div class="form-check form-switch">
                                        <input name="status_produk" class="form-check-input border-secondary"
                                            type="checkbox" checked style="transform: scale(1.2);">
                                        <label class="form-check-label ms-2 fw-medium">Produk Aktif (tampil di
                                            katalog)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-md-4">

                    <!-- FOTO PRODUK -->
                    <div class="card border-0 shadow-sm rounded-3 mb-3">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="fw-bold text-dark">
                                <i class="bi bi-image me-2 text-danger"></i>Foto Produk
                            </div>
                        </div>

                        <div class="card-body">

                            <div class="border-2 border-dashed rounded-3 p-4 text-center bg-light"
                                style="border-style: dashed !important;">

                                <i class="bi bi-cloud-upload text-muted display-6"></i>

                                <div class="fw-bold small text-dark mt-2">
                                    Upload Foto Produk
                                </div>

                                <div class="text-muted mb-3" style="font-size:11px;">
                                    PNG, JPG, WEBP · Maks 2MB
                                </div>

                                <!-- INPUT FILE -->
                                <input type="file" name="gambar" class="form-control form-control-sm"
                                    accept="image/png,image/jpg,image/jpeg,image/webp" required>

                            </div>

                        </div>
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

                <div class="d-grid gap-2">
                    <button class="btn btn-danger py-2 fw-bold rounded-3 shadow-sm border-0"
                        style="background-color: #dc3545;">
                        <i class="bi bi-check-circle me-2"></i>Simpan Produk
                    </button>
                    <button onclick="showPage('gudang')"
                        class="btn btn-light py-2 fw-semibold rounded-3 text-secondary border">
                        Batal
                    </button>
                </div>
            </div>
    </div>
    </form>

    </div>
    @push('scripts')
        <script>
            $(function() {
                $('.select2').select2({
                    placeholder: "Pilih jenis mobil...",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection
