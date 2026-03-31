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

        <form action="{{ route('admin.produk.update', $produk->kode_produk) }}" method="POST" enctype="multipart/form-data">
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
                                    <input name="nama_produk" type="text" value="{{ $produk->nama_produk }}"
                                        class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Kode</label>
                                    <input name="kode_produk" type="text" value="{{ $produk->kode_produk }}"
                                        class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold small text-secondary">Part Model</label>
                                    <input name="part_model" type="text" value="{{ $produk->part_model }}"
                                        class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold small text-secondary">Kategori</label>
                                    <select name="kategori_id" class="form-select rounded-3 py-2" required>
                                        <option value="">-- Pilih Kategori --</option>

                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}"
                                                {{ $k->id == $produk->kategori_id ? 'selected' : '' }}>
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
                                            <option value="{{ $b->id }}"
                                                {{ $b->id == $produk->brand_id ? 'selected' : '' }}>
                                                {{ $b->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Harga Jual (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">Rp</span>
                                        <input name="harga" type="number" value="{{ $produk->harga }}"
                                            class="form-control rounded-end-3 py-2" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Stok Awal</label>
                                    <div class="input-group">
                                        <input name="stok_produk" value="{{ $produk->stok_produk }}"
                                            class="form-control py-2" type="number" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold small text-secondary">Satuan</label>
                                    <select name="unit" class="form-select rounded-3 py-2" required>
                                        <option {{ $produk->unit == 'set' ? 'selected' : '' }}>set</option>
                                        <option {{ $produk->unit == 'pcs' ? 'selected' : '' }}>pcs</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Deskripsi Produk</label>
                                    <textarea name="deskripsi_produk" class="form-control rounded-3" rows="3">{{ $produk->deskripsi_produk }}</textarea>
                                </div>
                                <div class="col-12 pt-2">
                                    <div class="form-check form-switch">
                                        <input name="status_produk" class="form-check-input border-secondary"
                                            type="checkbox" {{ $produk->status_produk ? 'checked' : '' }}
                                            style="transform: scale(1.2);">
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

                                <div id="image-preview-container" class="mb-3">
                                    @if (!empty($produk->gambar))
                                        <div class="mb-2 text-muted small">Foto Saat Ini:</div>
                                        <img src="{{ asset('storage/produk/' . $produk->gambar) }}" alt="Foto Produk"
                                            id="preview-img" class="img-thumbnail" style="max-height: 150px;">
                                    @else
                                        <i class="bi bi-cloud-upload text-muted display-6" id="placeholder-icon"></i>
                                    @endif
                                </div>

                                <div class="fw-bold small text-dark mt-2">Ganti Foto Produk</div>
                                <div class="text-muted mb-3" style="font-size:11px;">
                                    PNG, JPG, WEBP · Maks 2MB <br>
                                    <span class="text-danger">*Kosongkan jika tidak ingin mengubah foto</span>
                                </div>

                                <input type="file" name="gambar" id="input-gambar"
                                    class="form-control form-control-sm"
                                    accept="image/png,image/jpg,image/jpeg,image/webp" onchange="previewImage(this)">
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
                        <i class="bi bi-check-circle me-2"></i>Edit Produk
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
@endsection
