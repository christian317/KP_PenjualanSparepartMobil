<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Jaya Abadi – Solusi Belanja Sparepart Bengkel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Montserrat:wght@600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        .ff-bc {
            font-family: 'Montserrat', sans-serif !important;
        }

        .ff-dm {
            font-family: 'Inter', sans-serif !important;
        }

        body {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        .display-1,
        .display-2,
        .display-3,
        .display-4,
        .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            letter-spacing: -0.5px;
        }
    </style>
</head>

<body class="bg-white ff-dm">

    <nav class="navbar navbar-expand-lg fixed-top shadow-sm" style="background:#1A2332;">
        <div class="container-xl">

            <a class="navbar-brand fw-bold text-white fs-4 d-flex align-items-center gap-2" href="#">
                <span class="d-flex align-items-center justify-content-center bg-danger rounded-2"
                    style="width:34px;height:34px;font-size:16px;">
                    <i class="bi bi-car-front-fill text-white"></i>
                </span>
                Jaya<span class="text-danger">Abadi</span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <i class="bi bi-list text-white fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navMain">
                <ul class="navbar-nav mx-auto gap-1">
                    <li class="nav-item"><a class="nav-link text-white text-opacity-75 fw-medium px-3"
                            href="#produk">Katalog</a></li>
                    <li class="nav-item"><a class="nav-link text-white text-opacity-75 fw-medium px-3"
                            href="#fitur">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link text-white text-opacity-75 fw-medium px-3"
                            href="#cara-kerja">Solusi Mitra</a></li>
                    <li class="nav-item"><a class="nav-link text-white text-opacity-75 fw-medium px-3"
                            href="#alur">Alur Belanja</a></li>
                    <li class="nav-item"><a class="nav-link text-white text-opacity-75 fw-medium px-3"
                            href="#kontak">Hubungi Kami</a></li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-outline-light fw-semibold px-4 rounded-pill">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-danger fw-semibold px-4 rounded-pill">Daftar</a>
                </div>
            </div>
        </div>
    </nav>


    <section class="d-flex align-items-center"
        style="min-height:100vh; background:linear-gradient(135deg,#1A2332 0%,#0f1923 55%,#1a0a0a 100%); padding-top:80px;">
        <div class="container-xl py-5">
            <div class="row align-items-center g-5">

                <div class="col-lg-6">
                    <div class="d-inline-flex align-items-center gap-2 rounded-pill px-3 py-2 mb-4 border border-danger border-opacity-50"
                        style="background:rgba(214,40,40,.1);">
                        <span class="rounded-circle bg-danger"
                            style="width:8px;height:8px;display:inline-block;"></span>
                        <span class="text-danger fw-bold"
                            style="font-size:12px;letter-spacing:.8px;text-transform:uppercase;">Distributor Sparepart
                            Terpercaya</span>
                    </div>

                    <h1 class="ff-bc fw-bolder text-white lh-sm mb-4" style="font-size:clamp(40px,5.5vw,72px);">
                        Stok Lengkap,<br>
                        <span class="text-danger">Bengkel Anda</span><br>
                        Jalan Terus
                    </h1>

                    <p class="text-white text-opacity-75 mb-4" style="font-size:16px;max-width:480px;line-height:1.7;">
                        Temukan ribuan sparepart original dengan harga transparan. Nikmati kemudahan belanja online,
                        pantau status pesanan, dan manfaatkan fasilitas <strong>Kontrabon 3 Bulan</strong> khusus untuk
                        Bengkel Mitra kami.
                    </p>

                    <div class="d-flex gap-3 flex-wrap mb-5 mt-2">
                        <a href="{{ route('register') }}"
                            class="btn btn-danger btn-lg fw-bold px-5 rounded-pill d-inline-flex align-items-center gap-2 shadow-lg">
                            <i class="bi bi-rocket-takeoff"></i> Gabung Jadi Mitra
                        </a>
                        <a href="#alur"
                            class="btn btn-outline-light btn-lg px-5 rounded-pill fw-semibold d-inline-flex align-items-center gap-2">
                            <i class="bi bi-cart"></i> Cara Pesan
                        </a>
                    </div>

                    <div class="d-flex gap-4 flex-wrap">
                        <div class="text-center">
                            <div class="ff-bc fw-bolder text-white" style="font-size:28px;line-height:1;">Midtrans</div>
                            <div class="text-white text-opacity-50 mt-1" style="font-size:12px;font-weight:500;">100%
                                Pembayaran Aman</div>
                        </div>
                        <div class="border-start border-secondary opacity-50"></div>
                        <div class="text-center">
                            <div class="ff-bc fw-bolder text-white" style="font-size:28px;line-height:1;">Otomatis</div>
                            <div class="text-white text-opacity-50 mt-1" style="font-size:12px;font-weight:500;">Sistem
                                Terintegrasi</div>
                        </div>
                        <div class="border-start border-secondary opacity-50"></div>
                        <div class="text-center">
                            <div class="ff-bc fw-bolder text-white" style="font-size:28px;line-height:1;">Garansi</div>
                            <div class="text-white text-opacity-50 mt-1" style="font-size:12px;font-weight:500;">
                                Kualitas Terjamin</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-none d-lg-block">
                    <div class="position-relative">

                        <div class="position-absolute top-50 start-50 translate-middle rounded-circle bg-danger opacity-25"
                            style="width:360px;height:360px;filter:blur(80px);"></div>

                        <div class="card border-0 rounded-4 shadow-lg position-relative" style="background:#1e2d3d;">
                            <div class="card-body p-4">
                                <div
                                    class="d-flex justify-content-between align-items-center mb-3 border-bottom border-secondary border-opacity-25 pb-3">
                                    <span class="fw-bold text-white" style="font-size:16px;"><i
                                            class="bi bi-wallet2 text-danger me-2"></i>Status Pesanan & Tagihan</span>
                                    <span class="badge bg-success text-white rounded-pill">Sistem Terintegrasi</span>
                                </div>

                                <div class="d-flex flex-column gap-3 mt-3">
                                    <div class="d-flex justify-content-between align-items-center rounded-3 p-3"
                                        style="background:rgba(255,255,255,.05);">
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="bg-primary bg-opacity-25 p-2 rounded-3 text-primary"><i
                                                    class="bi bi-truck fs-5"></i></div>
                                            <div>
                                                <div class="text-white fw-bold mb-1">Pesanan INV-9982</div>
                                                <div class="text-white text-opacity-50" style="font-size:12px;">Dalam
                                                    Perjalanan</div>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary text-white">Sedang Dikirim</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center rounded-3 p-3 border border-danger border-opacity-50"
                                        style="background:rgba(214,40,40,.1);">
                                        <div class="d-flex gap-3 align-items-center">
                                            <div class="bg-danger bg-opacity-25 p-2 rounded-3 text-danger"><i
                                                    class="bi bi-receipt fs-5"></i></div>
                                            <div>
                                                <div class="text-white fw-bold mb-1">Tagihan Kontrabon</div>
                                                <div class="text-white text-opacity-50" style="font-size:12px;">Sisa:
                                                    Rp 2.500.000</div>
                                            </div>
                                        </div>
                                        <div class="btn btn-danger btn-sm fw-bold px-3 rounded-pill">Bayar Cicilan
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="position-absolute top-0 start-0 translate-middle-y ms-4">
                            <div class="card border-0 shadow-lg rounded-3 px-3 py-2 d-flex flex-row align-items-center gap-2"
                                style="background:#fff;">
                                <div class="bg-success rounded-circle" style="width:10px;height:10px;"></div>
                                <span class="fw-bold text-dark" style="font-size:13px;">Belanja 24 Jam Nonstop</span>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 end-0 translate-middle-y me-3">
                            <div class="card border-0 shadow-lg rounded-3 px-3 py-2 d-flex flex-row align-items-center gap-2"
                                style="background:#fff;">
                                <i class="bi bi-shield-check text-success fs-4"></i>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size:13px;">Transaksi Terenkripsi</div>
                                    <div class="text-secondary" style="font-size:11px;">Data Anda Aman</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="position-absolute bottom-0 start-0 w-100 overflow-hidden" style="line-height:0;">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
                style="width:100%;height:60px;display:block;">
                <path d="M0,40 C360,80 1080,0 1440,40 L1440,60 L0,60 Z" fill="#ffffff" />
            </svg>
        </div>
    </section>


    <section class="py-4 border-bottom" id="produk">
        <div class="container-xl">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="h6 text-uppercase fw-bold text-secondary mb-0"
                    style="font-size:12px;letter-spacing:1px;white-space:nowrap;">Brand Tersedia</div>
                <div class="flex-grow-1 border-top border-2"></div>
            </div>
            <div class="d-flex flex-wrap gap-4 align-items-center justify-content-between">
                @foreach (['NGK', 'Brembo', 'Monroe', 'Bosch', 'Toyota', 'Shell', 'GS Astra', 'Denso', 'ATE', 'Moog'] as $brand)
                    <div class="fw-bold text-secondary text-uppercase ff-bc"
                        style="font-size:18px;letter-spacing:1px;opacity:.4;">{{ $brand }}</div>
                @endforeach
            </div>
        </div>
    </section>


    <section class="py-5" id="fitur" style="background:#f8f9fa;">
        <div class="container-xl py-4">
            <div class="text-center mb-5">
                <div class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill px-3 py-2 mb-3"
                    style="font-size:12px;letter-spacing:.5px;">KEUNGGULAN PLATFORM KAMI</div>
                <h2 class="ff-bc fw-bolder" style="font-size:clamp(32px,4vw,44px);">Semua Kebutuhan Bengkel <br><span
                        class="text-danger">Dalam Satu Aplikasi</span></h2>
            </div>

            <div class="row g-4">
                @foreach ([
        ['icon' => 'bi-search', 'color' => 'danger', 'title' => 'Katalog Super Lengkap', 'desc' => 'Akses ribuan sparepart original dari berbagai merek. Dilengkapi fitur pencarian pintar untuk menemukan barang dengan cepat.'],
        ['icon' => 'bi-cart-check', 'color' => 'primary', 'title' => 'Pemesanan Instan', 'desc' => 'Tidak perlu lagi antre via WhatsApp. Masukkan barang ke keranjang dan checkout kapan saja, 24 jam sehari.'],
        ['icon' => 'bi-credit-card', 'color' => 'success', 'title' => 'Opsi Bayar Fleksibel', 'desc' => 'Bayar pesanan secara langsung (Transfer) dengan aman via Midtrans, atau gunakan metode hutang (Kontrabon) jika Anda adalah Mitra.'],
        ['icon' => 'bi-box-seam', 'color' => 'warning', 'title' => 'Stok Selalu Akurat', 'desc' => 'Sistem kami terintegrasi langsung dengan gudang. Ketersediaan barang selalu ter-update otomatis sehingga Anda tidak perlu ragu saat memesan.'],
        ['icon' => 'bi-receipt', 'color' => 'info', 'title' => 'Riwayat & Tagihan Terpusat', 'desc' => 'Semua faktur, riwayat pembelian, dan sisa tagihan tercatat rapi di dalam sistem. Pembukuan bengkel Anda jadi lebih mudah.'],
        ['icon' => 'bi-award', 'color' => 'secondary', 'title' => 'Jaminan Kualitas', 'desc' => 'Setiap pesanan dipacking dengan teliti oleh tim gudang dilengkapi dengan surat jalan resmi untuk memastikan kesesuaian produk.'],
    ] as $f)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 rounded-4 fitur-card" style="transition:all .25s;">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center justify-content-center bg-{{ $f['color'] }} bg-opacity-10 rounded-3 mb-4"
                                    style="width:56px;height:56px;">
                                    <i class="bi {{ $f['icon'] }} text-{{ $f['color'] }}"
                                        style="font-size:24px;"></i>
                                </div>
                                <h5 class="ff-bc fw-bold mb-2" style="font-size:20px;">{{ $f['title'] }}</h5>
                                <p class="text-secondary fw-medium mb-0" style="font-size:14px;line-height:1.6;">
                                    {{ $f['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <section class="py-5" id="cara-kerja" style="background:linear-gradient(135deg,#1A2332,#0f1923);">
        <div class="container-xl py-4">
            <div class="text-center mb-5">
                <div class="badge bg-danger bg-opacity-25 text-danger fw-bold rounded-pill px-3 py-2 mb-3"
                    style="font-size:12px;letter-spacing:.5px;">SOLUSI BISNIS ANDA</div>
                <h2 class="ff-bc fw-bolder text-white" style="font-size:clamp(32px,4vw,44px);">Tingkatkan Performa
                    <span class="text-danger">Bengkel Anda</span></h2>
                <p class="text-white text-opacity-50 mt-2 fw-medium" style="font-size:15px;">Bergabunglah bersama
                    ribuan bengkel lain yang telah mempercayakan suplai sparepart mereka kepada kami.</p>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card border-0 rounded-4 h-100"
                        style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08)!important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-circle bg-primary bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:52px;height:52px;font-size:24px;">
                                    <i class="bi bi-shop text-primary"></i>
                                </div>
                                <div>
                                    <div class="badge bg-primary bg-opacity-25 text-primary mb-1 fw-bold"
                                        style="font-size:10px;">AKSES TANPA BATAS</div>
                                    <div class="ff-bc fw-bold text-white" style="font-size:20px;">Belanja 24/7</div>
                                </div>
                            </div>
                            <p class="text-white text-opacity-75 fw-medium" style="font-size:14px;line-height:1.6;">
                                Tinggalkan kebiasaan lama memesan via chat yang lambat. Akses katalog kami kapanpun Anda
                                butuh, cek harga secara transparan, dan stok selalu ter-update secara otomatis di layar
                                Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 rounded-4 h-100 border-danger"
                        style="background:rgba(214,40,40,.08);border:1px solid rgba(214,40,40,.3)!important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:52px;height:52px;font-size:24px;background:rgba(214,40,40,.25);">
                                    <i class="bi bi-wallet2 text-danger"></i>
                                </div>
                                <div>
                                    <div class="badge bg-danger bg-opacity-25 text-danger mb-1 fw-bold"
                                        style="font-size:10px;">KHUSUS BENGKEL MITRA</div>
                                    <div class="ff-bc fw-bold text-white" style="font-size:20px;">Kontrabon 3 Bulan
                                    </div>
                                </div>
                            </div>
                            <p class="text-white text-opacity-75 fw-medium" style="font-size:14px;line-height:1.6;">
                                Jaga perputaran kas bengkel Anda! Pesan barang hari ini, bayar nanti. Tagihan akan
                                direkap secara mingguan dan Anda bebas menyicil pembayarannya hingga batas waktu 3 bulan
                                ke depan.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 rounded-4 h-100"
                        style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08)!important;">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="rounded-circle bg-success bg-opacity-25 d-flex align-items-center justify-content-center flex-shrink-0"
                                    style="width:52px;height:52px;font-size:24px;">
                                    <i class="bi bi-shield-check text-success"></i>
                                </div>
                                <div>
                                    <div class="badge bg-success bg-opacity-25 text-success mb-1 fw-bold"
                                        style="font-size:10px;">MIDTRANS SECURED</div>
                                    <div class="ff-bc fw-bold text-white" style="font-size:20px;">Transaksi Aman</div>
                                </div>
                            </div>
                            <p class="text-white text-opacity-75 fw-medium" style="font-size:14px;line-height:1.6;">
                                Pembayaran dijamin aman menggunakan Payment Gateway terkemuka. Tidak ada biaya
                                tersembunyi. Tentukan sendiri nominal cicilan Anda dan sistem akan langsung memperbarui
                                sisa tagihan Anda.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="py-5" id="alur">
        <div class="container-xl py-4">
            <div class="text-center mb-5">
                <div class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill px-3 py-2 mb-3"
                    style="font-size:12px;letter-spacing:.5px;">ALUR BELANJA MUDAH</div>
                <h2 class="ff-bc fw-bolder" style="font-size:clamp(32px,4vw,44px);">Pilih Metode Sesuai <span
                        class="text-danger">Kebutuhan</span></h2>
            </div>

            {{-- ALUR 1: PEMBAYARAN CASH --}}
            <h5 class="fw-bold text-dark mt-5"><i class="bi bi-cash-coin text-success me-2"></i>Alur Pembelian
                Langsung (Transfer)</h5>
            <div class="row g-0 align-items-start mt-4 mb-5 pb-4 border-bottom">
                @foreach ([['no' => '01', 'icon' => 'bi-cart-plus', 'color' => 'danger', 'title' => 'Pilih Produk', 'desc' => 'Cari dan masukkan sparepart ke keranjang belanja Anda.'], ['no' => '02', 'icon' => 'bi-credit-card', 'color' => 'primary', 'title' => 'Pilih Transfer', 'desc' => 'Lakukan Checkout dan pilih opsi pembayaran "Transfer".'], ['no' => '03', 'icon' => 'bi-shield-check', 'color' => 'success', 'title' => 'Bayar Aman', 'desc' => 'Selesaikan pembayaran secara instan melalui Midtrans.'], ['no' => '04', 'icon' => 'bi-box-seam', 'color' => 'warning', 'title' => 'Diproses', 'desc' => 'Barang pesanan Anda akan segera disiapkan oleh tim.'], ['no' => '05', 'icon' => 'bi-truck', 'color' => 'info', 'title' => 'Dikirim', 'desc' => 'Tunggu pesanan Anda diantar dengan aman ke lokasi bengkel.'], ['no' => '06', 'icon' => 'bi-check-circle', 'color' => 'success', 'title' => 'Selesai', 'desc' => 'Terima barang dan pastikan semua sesuai pesanan.']] as $i => $step)
                    <div class="col-6 col-md-4 col-lg-2 mt-3 mt-lg-0">
                        <div class="text-center px-2 pb-4 position-relative">
                            @if ($i < 5)
                                <div class="position-absolute top-0 d-none d-lg-block"
                                    style="left:50%;width:100%;height:3px;background:linear-gradient(to right,#dee2e6,#dee2e6);margin-top:28px;z-index:0;">
                                </div>
                            @endif
                            <div class="rounded-circle border-2 d-flex align-items-center justify-content-center mx-auto mb-3 position-relative bg-white shadow-sm"
                                style="width:60px;height:60px;border:3px solid #dee2e6;z-index:1;">
                                <i class="bi {{ $step['icon'] }} text-{{ $step['color'] }}"
                                    style="font-size:24px;"></i>
                            </div>
                            <div class="ff-bc fw-bolder text-danger mb-1" style="font-size:14px;letter-spacing:1px;">
                                {{ $step['no'] }}</div>
                            <div class="fw-bold text-dark mb-1" style="font-size:15px;">{{ $step['title'] }}</div>
                            <div class="text-secondary fw-medium mx-auto"
                                style="font-size:13px;line-height:1.5;max-width:140px;">{{ $step['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ALUR 2: PEMBAYARAN KONTRABON (MITRA) --}}
            <h5 class="fw-bold text-dark mt-4"><i class="bi bi-journal-text text-danger me-2"></i>Alur Pembelian Tempo
                (Kontrabon Khusus Mitra)</h5>
            <div class="row g-0 align-items-start mt-4">
                @foreach ([['no' => '01', 'icon' => 'bi-cart-plus', 'color' => 'danger', 'title' => 'Pilih Produk', 'desc' => 'Cari dan masukkan sparepart ke keranjang belanja Anda.'], ['no' => '02', 'icon' => 'bi-journal-check', 'color' => 'primary', 'title' => 'Pilih Kontrabon dan Aprroval', 'desc' => 'Checkout pesanan tanpa perlu mengeluarkan uang di awal.'], ['no' => '03', 'icon' => 'bi-truck', 'color' => 'warning', 'title' => 'Terima Barang', 'desc' => 'Pesanan diproses dan langsung dikirim ke bengkel Anda.'], ['no' => '04', 'icon' => 'bi-receipt', 'color' => 'danger', 'title' => 'Tagihan Terbit', 'desc' => 'Sistem akan menggabungkan pesanan menjadi tagihan mingguan.'], ['no' => '05', 'icon' => 'bi-wallet2', 'color' => 'success', 'title' => 'Cicil Fleksibel', 'desc' => 'Ketik nominal bayar sesuai kemampuan via dashboard tagihan.'], ['no' => '06', 'icon' => 'bi-calendar-check', 'color' => 'primary', 'title' => 'Lunas', 'desc' => 'Pastikan tagihan dilunasi sebelum masa tempo 3 bulan habis.']] as $i => $step)
                    <div class="col-6 col-md-4 col-lg-2 mt-3 mt-lg-0">
                        <div class="text-center px-2 pb-4 position-relative">
                            @if ($i < 5)
                                <div class="position-absolute top-0 d-none d-lg-block"
                                    style="left:50%;width:100%;height:3px;background:linear-gradient(to right,#dee2e6,#dee2e6);margin-top:28px;z-index:0;">
                                </div>
                            @endif
                            <div class="rounded-circle border-2 d-flex align-items-center justify-content-center mx-auto mb-3 position-relative bg-white shadow-sm"
                                style="width:60px;height:60px;border:3px solid #dee2e6;z-index:1;">
                                <i class="bi {{ $step['icon'] }} text-{{ $step['color'] }}"
                                    style="font-size:24px;"></i>
                            </div>
                            <div class="ff-bc fw-bolder text-danger mb-1" style="font-size:14px;letter-spacing:1px;">
                                {{ $step['no'] }}</div>
                            <div class="fw-bold text-dark mb-1" style="font-size:15px;">{{ $step['title'] }}</div>
                            <div class="text-secondary fw-medium mx-auto"
                                style="font-size:13px;line-height:1.5;max-width:140px;">{{ $step['desc'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <footer class="py-5 mt-4 border-top" id="kontak" style="background:#1A2332;">
        <div class="container-xl pt-3">
            <div class="row g-5 mb-5">

                <div class="col-md-5">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="d-flex align-items-center justify-content-center bg-danger rounded-2"
                            style="width:34px;height:34px;font-size:16px;">
                            <i class="bi bi-car-front-fill text-white"></i>
                        </span>
                        <span class="ff-bc fw-bolder text-white fs-4">Jaya<span
                                class="text-danger">Abadi</span></span>
                    </div>
                    <p class="text-white text-opacity-50 mb-4 fw-medium"
                        style="font-size:14px;line-height:1.7; max-width:400px;">
                        Pusat kulakan sparepart otomotif terpercaya. Memberikan kemudahan berbelanja, kelengkapan
                        barang, dan keamanan bertransaksi bagi seluruh bengkel di Indonesia.
                    </p>
                </div>

                <div class="col-6 col-md-3">
                    <div class="ff-bc fw-bold text-white mb-3" style="font-size:16px;letter-spacing:.5px;">PINTASAN
                        CEPAT</div>
                    <div class="d-flex flex-column gap-3">
                        <a href="#produk" class="text-white text-opacity-75 text-decoration-none fw-medium"
                            style="font-size:14px;">Katalog Sparepart</a>
                        <a href="#cara-kerja" class="text-white text-opacity-75 text-decoration-none fw-medium"
                            style="font-size:14px;">Keuntungan Kemitraan</a>
                        <a href="{{ route('register') }}"
                            class="text-white text-opacity-75 text-decoration-none fw-medium"
                            style="font-size:14px;">Daftar Jadi Mitra</a>
                        <a href="{{ route('login') }}"
                            class="text-white text-opacity-75 text-decoration-none fw-medium"
                            style="font-size:14px;">Masuk Aplikasi</a>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="ff-bc fw-bold text-white mb-3" style="font-size:16px;letter-spacing:.5px;">HUBUNGI
                        KAMI</div>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-geo-alt text-danger mt-1 flex-shrink-0"></i>
                            <span class="text-white text-opacity-75 fw-medium" style="font-size:14px;">TIK III Blok E4 No. 13, Bandung</span>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-telephone text-danger mt-1 flex-shrink-0"></i>
                            <span class="text-white text-opacity-75 fw-medium" style="font-size:14px;">+62 811 2233
                                4455</span>
                        </div>
                        <div class="d-flex align-items-start gap-3">
                            <i class="bi bi-clock text-danger mt-1 flex-shrink-0"></i>
                            <span class="text-white text-opacity-75 fw-medium" style="font-size:14px;">Senin–Sabtu,
                                08.00–17.00 WIB</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="border-top border-secondary border-opacity-25 pt-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="text-white text-opacity-25 fw-medium" style="font-size:13px;">© 2026 CV Jaya Abadi.
                    Seluruh hak cipta dilindungi.</div>
                <div class="d-flex gap-4">
                    <span class="text-white text-opacity-25 fw-medium" style="font-size:13px;">Powered by Midtrans
                        Secured</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const t = document.querySelector(a.getAttribute('href'));
                if (t) {
                    e.preventDefault();
                    t.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            document.querySelector('.navbar').style.boxShadow =
                window.scrollY > 10 ? '0 2px 20px rgba(0,0,0,.3)' : 'none';
        });

        // Card hover lift
        document.querySelectorAll('.fitur-card').forEach(c => {
            c.addEventListener('mouseenter', () => {
                c.style.transform = 'translateY(-6px)';
                c.style.boxShadow = '0 16px 40px rgba(0,0,0,.12)';
            });
            c.addEventListener('mouseleave', () => {
                c.style.transform = '';
                c.style.boxShadow = '';
            });
        });
    </script>
</body>

</html>
