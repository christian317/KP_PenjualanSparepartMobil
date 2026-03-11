<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark min-vh-100 d-flex align-items-center justify-content-center">
    <div class="card border-0 shadow-lg rounded-4 p-4" style="width: 100%; max-width: 420px;">
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
                <a href="{{ route('login') }}"
                    class="nav-link active {{ request()->is('login') }} rounded-3 fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('register') }}"
                    class="nav-link {{ request()->is('register') }} rounded-3 fw-semibold">
                    <i class="bi bi-person-plus me-1"></i>Daftar
                </a>
            </li>
        </ul>

        <!-- Tab Content -->
        <form method="POST" action="/login">
            @csrf
            <div class="tab-content">
                <!-- Form Login -->
                <div class="tab-pane fade show active" id="form-login">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">EMAIL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            <input type="email" name="email" class="form-control bg-light border-start-0 ps-0"
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between">
                            <label class="form-label fw-semibold text-secondary small">PASSWORD</label>
                            <a href="#" class="small text-danger text-decoration-none">Lupa password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" name="password"
                                class="form-control bg-light border-start-0 border-end-0 ps-0" required>
                            <button class="btn btn-light border border-start-0" type="button"
                                onclick="togglePassword()">
                                <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Notifikasi --}}
                    @if (session('error'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center p-2 mb-4"
                            role="alert">
                            <i class="bi bi-exclamation-octagon-fill fs-5 me-3"></i>
                                <div class="fw-bold">{{ session('error') }}</div>

                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2 rounded-3 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login
                    </button>

                    <div class="divider d-flex align-items-center gap-2 mb-3">
                        <hr class="flex-grow-1 m-0">
                        <small class="text-muted">atau masuk sebagai</small>
                        <hr class="flex-grow-1 m-0">
                    </div>

                    <!-- Quick Login Roles -->
                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            👤 Pelanggan
                        </button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            📦 Admin Gudang
                        </button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            💰 Admin Keuangan
                        </button>
                    </div>
                </div>

                <!-- Form Register -->
                <div class="tab-pane fade" id="form-register">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">NAMA LENGKAP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-person text-muted"></i>
                            </span>
                            <input type="text" class="form-control bg-light border-start-0 ps-0"
                                placeholder="Nama lengkap Anda">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">EMAIL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control bg-light border-start-0 ps-0"
                                placeholder="email@bengkel.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small">PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control bg-light border-start-0 ps-0"
                                placeholder="Minimal 8 karakter">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary small">KONFIRMASI PASSWORD</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-shield-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control bg-light border-start-0 ps-0"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="agreeCheck">
                        <label class="form-check-label small text-muted" for="agreeCheck">
                            Saya setuju dengan <a href="#" class="text-danger text-decoration-none">Syarat &
                                Ketentuan</a>
                        </label>
                    </div>

                    <button class="btn btn-danger w-100 fw-bold py-2 rounded-3">
                        <i class="bi bi-person-check me-2"></i>Buat Akun
                    </button>

                </div>

            </div>

            <p class="text-center text-muted small mt-4 mb-0">
                &copy; 2025 SparepartKu. All rights reserved.
            </p>
        </form>
    </div>
</body>

</html>
