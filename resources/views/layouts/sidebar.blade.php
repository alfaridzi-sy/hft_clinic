<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('sneat/assets/img/hft_clinic_logo.svg') }}" alt="Logo SIM Klinik by Hatta"
                    width="48">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">HFT Clinic</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item active">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
        </li>

        <li class="menu-item">
            <a href="{{ route('users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user-account"></i>
                <div data-i18n="Boxicons">Pengguna</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('services.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-server"></i>
                <div data-i18n="Boxicons">Layanan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('patients.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                <div data-i18n="Boxicons">Pasien</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="{{ route('doctors.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-layer-plus"></i>
                <div data-i18n="Boxicons">Dokter</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar-alt"></i>
                <div data-i18n="Boxicons">Jadwal Dokter</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-add-to-queue"></i>
                <div data-i18n="Boxicons">Janji Temu</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card-alt"></i>
                <div data-i18n="Boxicons">Pembayaran</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan</span>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-spreadsheet"></i>
                <div data-i18n="Boxicons">Kunjungan</div>
            </a>
        </li>

        <li class="menu-item">
            <a href="javascript:void(0)" class="menu-link">
                <i class="menu-icon tf-icons bx bx-money"></i>
                <div data-i18n="Boxicons">Pendapatan</div>
            </a>
        </li>
    </ul>
</aside>
