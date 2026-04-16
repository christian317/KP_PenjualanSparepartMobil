<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparepartKu - Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center py-4">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="width:100%;max-width:500px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1">
                <i class="bi bi-car-front-fill text-danger me-1"></i>
                CV.<span class="text-danger">JAYA ABADI</span>
            </h3>
            <small class="text-muted">Platform Sparepart Mobil Terpercaya</small>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills nav-fill mb-4 bg-light rounded-3 p-1" id="loginTab">
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link {{ request()->is('login')}} rounded-3 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('register') }}" class="nav-link active {{ request()->is('register')}} rounded-3 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i>Daftar
                </a>
            </li>
        </ul>
        {{-- Notifikasi --}}
        @if(session('error'))
            <div class="alert alert-danger shadow-sm border-0">{{ session('error') }}</div>
        @endif

        <!-- Form -->
        <form method="POST" action="/register">
            @csrf
            <div class="row g-3">
                <div class="col-6">
                    <label class="form-label fw-semibold text-secondary small">NAMA LENGKAP <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-person text-muted"></i></span>
                        <input type="text" name="nama" class="form-control bg-light border-start-0 ps-0" required>
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label fw-semibold text-secondary small">NAMA TOKO/BENGKEL</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-shop text-muted"></i></span>
                        <input type="text" name="nama_toko" class="form-control bg-light border-start-0 ps-0">
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label fw-semibold text-secondary small">EMAIL <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0 ps-0" required>
                    </div>
                </div>

                <div class="col-6">
                    <label class="form-label fw-semibold text-secondary small">NO. TELEPON <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-telephone text-muted"></i></span>
                        <input type="text" name="telepon" class="form-control bg-light border-start-0 ps-0" required>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold text-secondary small">ALAMAT LENGKAP <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-geo-alt text-muted"></i></span>
                        <textarea class="form-control bg-light border-start-0 ps-0" name="alamat" rows="2" required></textarea>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold text-secondary small">PASSWORD <span
                            class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i
                                class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-start-0 ps-0" required>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="col-12">
                    <div
                        class="d-flex align-items-start gap-2 p-3 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                        <i class="bi bi-info-circle-fill text-warning mt-1 flex-shrink-0"></i>
                        <small class="text-warning-emphasis">
                            Akun baru didaftarkan sebagai <strong>Pelanggan Reguler</strong>.
                            Untuk upgrade ke <strong>Pelanggan Mitra</strong> (bisa Kontrabón), hubungi tim kami.
                        </small>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-12">
                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3">
                        <i class="bi bi-person-check me-2"></i>Daftar Sekarang
                    </button>
                    <p class="text-center mt-3 mb-0 text-muted small">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-danger fw-semibold text-decoration-none">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
