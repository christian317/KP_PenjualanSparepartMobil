<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SparePartKu - Dashboard</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- JS (URUTAN PENTING!) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        body { background-color: #f8f9fa; overflow-x: hidden; }

        /* Sidebar Desktop tetap di kiri */
        .sidebar-desktop {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            overflow-y: auto;
        }

        /* Konten Utama terdorong ke kanan sejauh lebar sidebar */
        #main-content {
            margin-left: 240px; 
            min-height: 100vh;
            transition: margin 0.3s ease;
        }

        /* Responsif: Di layar kecil (HP/Tablet), sidebar desktop hilang, margin jadi 0 */
        @media (max-width: 991.98px) {
            #main-content {
                margin-left: 0;
            }
            .sidebar-desktop {
                display: none !important;
            }
        }

        /* Styling Scrollbar Sidebar agar tipis */
        .sidebar-desktop::-webkit-scrollbar { width: 4px; }
        .sidebar-desktop::-webkit-scrollbar-thumb { background: #444; }
    </style>
</head>
<body>

    @include('layouts.sidebar')

    <main id="main-content">
        <div class="p-4">
            @yield('content')
        </div>
    </main>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>