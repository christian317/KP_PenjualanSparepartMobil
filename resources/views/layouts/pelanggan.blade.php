<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Jaya Abadi - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .navbar-brand {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .nav-link {
            font-size: 0.95rem;
            font-weight: 500;
        }

        .cart-badge {
            position: absolute;
            top: 0;
            right: 0;
            transform: translate(50%, -50%);
            font-size: 0.7rem;
        }

        /* Perbaikan agar footer selalu di bawah jika konten sedikit */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex: 1;
        }
    </style>
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="bi bi-car-front-fill text-danger me-2"></i>CV <span class="text-danger ms-1">Jaya Abadi</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link {{ Request::is('/') ? 'active' : '' }}"
                            href="/">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('pelanggan.pesanan.index') }}">Pesanan Saya</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pelanggan.pesanan.daftar_tagihan') }}">
                            <i class="bi bi-wallet2"></i> Tagihan Saya
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('pelanggan.pesanan.keranjang') }}" class="text-white position-relative">
                        <i class="bi bi-cart3 fs-5"></i>

                        @php
                            $userIdForBadge = session('user_id');
                            $displayCount = 0;

                            if ($userIdForBadge) {
                                $displayCount = DB::table('keranjang')
                                    ->where('user_id', $userIdForBadge)
                                    ->count();
                            }
                        @endphp

                        <span class="badge rounded-pill bg-danger cart-badge">
                            {{ $displayCount }}
                        </span>
                    </a>

                    @if (Session::has('user_id'))
                        <div class="dropdown">
                        <a class="text-white text-decoration-none dropdown-toggle d-flex align-items-center gap-2"
                            href="#" data-bs-toggle="dropdown">
                            
                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                style="width: 32px; height: 32px; font-size: 14px; line-height: 0; text-transform: uppercase;">
                                {{-- Jika nama tidak ada (admin), gunakan inisial 'A' dari kata Admin --}}
                                {{ substr(Session::get('nama') ?? 'Admin', 0, 1) }}
                            </div>

                            <span class="d-none d-md-inline small">
                                {{ Session::get('nama') ?? 'Administrator' }}
                            </span>

                            @if (Session::get('role') == 'pelanggan')
                                <span class="px-2 py-1 rounded-pill fw-bold text-white border border-white border-opacity-25" 
                                    style="font-size: 10px; background: rgba(255, 255, 255, 0.1); letter-spacing: 0.5px; vertical-align: middle;">
                                    <i class="bi bi-patch-check-fill me-1" 
                                    style="color: {{ Session::get('status_bengkel') == 1 ? '#0dcaf0' : '#adb5bd' }};"></i>
                                    {{ Session::get('status_bengkel') == 1 ? 'MITRA' : 'REGULER' }}
                                    
                                </span>
                            @endif
                        </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i
                                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-danger btn-sm px-4 fw-bold rounded-pill">Login</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="container-fluid px-4 py-4" style="max-width:1400px; margin:0 auto;">
        @yield('content')
    </main>

    <footer class="bg-white border-top py-4 mt-auto">
        <div class="container text-center">
            <p class="text-muted small mb-0">&copy; 2026 <strong>CV Jaya Abadi Sparepart</strong>. All rights reserved.
            </p>
        </div>
    </footer>
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="notifToast" class="toast align-items-center text-bg-success border-0 shadow-lg" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-semibold">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <span id="toastMessage"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('toast_success'))
                const toastEl = document.getElementById('notifToast');
                const msg = "{{ session('toast_success') }}";

                document.getElementById('toastMessage').textContent = msg;

                const toast = new bootstrap.Toast(toastEl, {
                    delay: 3000
                });
                toast.show();
            @endif
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
