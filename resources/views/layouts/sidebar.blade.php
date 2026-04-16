<aside class="sidebar-desktop bg-dark text-white d-flex flex-column border-end border-secondary">
    <div class="px-3 py-3 border-bottom border-secondary">
        <div class="fw-bold fs-5 text-truncate">
            <i class="bi bi-car-front-fill text-danger me-1"></i>CV<span class="text-danger">Jaya Abadi</span>
        </div>
        @if (Session::get('role_id') == 1)
            <span class="badge bg-danger mt-1" style="font-size:10px;letter-spacing:1px;">GUDANG</span>
        @elseif (Session::get('role_id') == 2)
            <span class="badge bg-danger mt-1" style="font-size:10px;letter-spacing:1px;">KEUANGAN</span>
        @endif
    </div>

    <div class="flex-grow-1 py-2 overflow-auto">
        <div class="text-uppercase text-secondary px-3 py-2" style="font-size:10px;">Menu</div>
        @if (Session::get('role_id') == 1)
            <!-- Dashboard -->
            <a href="{{ route('admin.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('admin.index') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <!-- Produk -->
            <a href="{{ route('admin.produk.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('admin.produk*') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-box-seam"></i> Kelola Produk
            </a>


            <!-- Pesanan -->
            <a href="{{ route('admin.pesanan.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('admin.pesanan*') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-list-ul"></i> Kelola Pesanan
                <span class="ms-auto badge bg-danger" style="font-size:10px;">12</span>
            </a>


            <!-- Pembelian -->
            <a href="{{ route('admin.pembelian.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('admin.pembelian*') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-cart-plus"></i> Kelola Pembelian
            </a>

        @elseif(Session::get('role_id') == 2)
        <!-- Dashboard -->
            <a href="{{ route('keuangan.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('keuangan.index') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        {{-- Kelola User --}}
            <a href="{{ route('keuangan.user.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('keuangan.user.index') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-people"></i> Kelola User
            </a>

            <a href="{{ route('keuangan.kontrabon.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('keuangan.kontrabon*') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-list-ul"></i> Kelola Kontrabon
                <span class="ms-auto badge bg-danger" style="font-size:10px;"></span>
            </a>

            <a href="{{ route('keuangan.transaksi.index') }}"
                class="d-flex align-items-center gap-2 px-3 py-2 text-decoration-none
                {{ Request::routeIs('keuangan.transaksi*') ? 'text-white bg-danger bg-opacity-25 border-start border-danger border-3' : 'text-white-50' }}">
                <i class="bi bi-list-ul"></i> Transaksi
                <span class="ms-auto badge bg-danger" style="font-size:10px;"></span>
            </a>
        @endif
        <!-- Logout -->
        <div class="text-uppercase text-secondary px-3 py-2 mt-2" style="font-size:10px;">Akun</div>
        <a href="{{ route('logout') }}"
            class="d-flex align-items-center gap-2 px-3 py-2 text-white-50 text-decoration-none">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <div class="p-3 border-top border-secondary mt-auto">
        <div class="d-flex align-items-center gap-2 bg-white bg-opacity-10 rounded-3 p-2">
            <div class="rounded-circle bg-danger d-flex align-items-center justify-content-center text-white fw-bold"
                style="width:34px;height:34px;font-size:13px;flex-shrink:0;">{{ substr(Session::get('nama'), 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <div class="text-white fw-semibold text-truncate" style="font-size:13px;">{{ Session::get('nama') }}
                </div>
                <div class="text-white-50 text-truncate" style="font-size:11px;">Admin Gudang</div>
            </div>
        </div>
    </div>
</aside>

<div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="sidebarMobile" style="width: 240px;">
    <div class="offcanvas-header border-bottom border-secondary">
        <h5 class="offcanvas-title fw-bold text-white">
            <i class="bi bi-car-front-fill text-danger me-1"></i>Spare<span class="text-danger">PartKu</span>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <div class="py-2">
            <a href="#" class="d-flex align-items-center gap-2 px-3 py-2 text-white text-decoration-none">
                <i class="bi bi-speedometer2 text-danger"></i> Dashboard
            </a>
            <a href="#" class="d-flex align-items-center gap-2 px-3 py-2 text-white-50 text-decoration-none">
                <i class="bi bi-list-ul"></i> Kelola Pesanan
            </a>
        </div>
    </div>
</div>
