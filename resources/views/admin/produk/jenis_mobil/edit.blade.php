@extends('layouts.app')

@section('title', 'Edit Jenis Mobil')

@section('content')
<div class="col px-4 pt-4 pb-5 bg-light min-vh-100">

    {{-- HEADER --}}
    <div class="sticky-top py-3 mb-4"
        style="background-color: #f8f9fa; z-index: 1020; margin-top: -1.5rem; padding-top: 1.5rem !important;">
        
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.produk.jenis_mobil.create') }}"
                class="btn btn-light border rounded-3 px-3 py-2 text-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i>
            </a>

            <div>
                <h4 class="fw-bold mb-0 text-dark">Edit Jenis Mobil</h4>
                <p class="text-muted mb-0 small">
                    Perbarui data jenis mobil dengan lengkap
                </p>
            </div>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- VALIDATION ERROR --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-circle me-1"></i>
                Terjadi Kesalahan
            </div>

            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-8">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                {{-- HEADER CARD --}}
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="fw-bold d-flex align-items-center text-dark">
                        <i class="bi bi-car-front-fill me-2 text-primary"></i>
                        Informasi Jenis Mobil
                    </div>
                </div>

                {{-- FORM --}}
                <form method="POST"
                    action="{{ route('admin.produk.jenis_mobil.update', $jenis_mobil->id) }}">

                    @csrf

                    <div class="card-body p-4">

                        <div class="row g-3">

                            {{-- MERK --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Merk Mobil
                                </label>

                                <input type="text"
                                    name="merk"
                                    class="form-control rounded-3 py-2"
                                    value="{{ old('merk', $jenis_mobil->merk) }}"
                                    placeholder="Contoh: Toyota"
                                    required>
                            </div>

                            {{-- MODEL --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Nama Model
                                </label>

                                <input type="text"
                                    name="nama_model"
                                    class="form-control rounded-3 py-2"
                                    value="{{ old('nama_model', $jenis_mobil->nama_model) }}"
                                    placeholder="Contoh: Avanza"
                                    required>
                            </div>

                            {{-- TAHUN --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">
                                    Tahun Mobil
                                </label>

                                <input type="text"
                                    name="tahun_mobil"
                                    class="form-control rounded-3 py-2"
                                    value="{{ old('tahun_mobil', $jenis_mobil->tahun_mobil) }}"
                                    placeholder="Contoh: 2020">
                            </div>

                        </div>

                        {{-- BUTTON --}}
                        <div class="d-grid mt-4">
                            <button type="submit"
                                class="btn text-white py-2 fw-bold rounded-3 shadow-sm border-0"
                                style="background-color: #3c3cff;">

                                <i class="bi bi-check-circle me-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection