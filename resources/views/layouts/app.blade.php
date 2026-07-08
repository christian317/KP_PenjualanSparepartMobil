<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Jaya Abadi - @yield('title')</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- JS (URUTAN PENTING!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body {
    background-color: #f8f9fa;
    overflow-x: hidden;
}

/* Desktop Sidebar */
.sidebar-desktop {
    width: 240px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1030;
    overflow-y: auto;
}

/* Content Desktop */
#main-content {
    margin-left: 240px;
    min-height: 100vh;
    transition: all .3s ease;
}

/* Mobile */
@media (max-width: 991.98px) {

    #main-content {
        margin-left: 0;
        width: 100%;
    }

    .sidebar-desktop {
        display: none !important;
    }
}

/* Scrollbar */
.sidebar-desktop::-webkit-scrollbar {
    width: 5px;
}

.sidebar-desktop::-webkit-scrollbar-thumb {
    background: #555;
    border-radius: 10px;
}
    </style>
</head>
<body>

    @include('layouts.sidebar')

    <!-- Header Mobile -->
    <nav class="navbar navbar-dark bg-dark d-lg-none sticky-top">
        <div class="container-fluid">
            <button class="btn btn-outline-light"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarMobile">
                <i class="bi bi-list fs-4"></i>
            </button>

            <span class="navbar-brand mb-0 h1 fw-bold">
                <i class="bi bi-car-front-fill text-danger me-1"></i>
                CV<span class="text-danger">Jaya Abadi</span>
            </span>
        </div>
    </nav>

    <main id="main-content">
        <div class="p-3 p-lg-4">
            @yield('content')
        </div>
    </main>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>