@extends('layouts.app')

@section('content')
    <div class="col px-4 pt-4 pb-5 bg-light min-vh-100">

        <div class="sticky-top py-3 mb-4"
            style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.produk.kategori.create') }}"
                    class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">Edit Kategori</h4>
                    <p class="text-muted mb-0 small">Isi data Kategori sparepart dengan lengkap</p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="fw-bold d-flex align-items-center text-dark">
                            <i class="bi bi-info-circle me-2 text-danger"></i>Informasi Kategori
                        </div>
                    </div>
                    <form method="POST" action="/admin.produk.kategori.update/{{ $kategori->id }}">
                        @csrf
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold small text-secondary">Nama Kategori</label>
                                    <input name="nama" type="text" value="{{ $kategori->nama }}" class="form-control rounded-3 py-2" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold small text-secondary">Deskripsi Produk</label>
                                    <textarea name="deskripsi" type="text" class="form-control rounded-3" rows="3">{{ $kategori->deskripsi }}</textarea>
                                </div>
                            </div>
                            <div class="d-grid gap-2" style="margin-top: 15px">
                                <button type="submit" class="btn btn-danger py-2 fw-bold rounded-3 shadow-sm border-0"
                                    style="background-color: #3c3cff;">
                                    <i class="bi bi-check-circle me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
