<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('sneat/assets/img/hft_clinic_logo.svg') }}" alt="Logo SIM Klinik by Hatta"
                    width="48" />
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">HFT Clinic</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    @php
        $role = Auth::user()->role ?? '';
    @endphp

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Dashboard</div>
            </a>
        </li>

        {{-- Menu untuk Admin dan Dokter --}}
        @if ($role === 'admin' || $role === 'dokter')
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>

            @if ($role === 'admin')
                <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bxs-user-account"></i>
                        <div data-i18n="Boxicons">Pengguna</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <a href="{{ route('services.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-server"></i>
                        <div data-i18n="Boxicons">Layanan</div>
                    </a>
                </li>
            @endif

            {{-- Menu Dokter bisa untuk Admin dan Dokter --}}
            <li class="menu-item {{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                <a href="{{ route('doctors.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-layer-plus"></i>
                    <div data-i18n="Boxicons">Dokter</div>
                </a>
            </li>
        @endif

        {{-- Menu Pasien untuk Admin dan Pasien --}}
        @if (in_array($role, ['admin', 'pasien']))
            <li class="menu-item {{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <a href="{{ route('patients.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-user-detail"></i>
                    <div data-i18n="Boxicons">Pasien</div>
                </a>
            </li>
        @endif

        {{-- Jadwal Dokter hanya untuk Admin --}}
        @if ($role === 'admin')
            <li class="menu-item {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                <a href="{{ route('schedules.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-calendar-alt"></i>
                    <div data-i18n="Boxicons">Jadwal Dokter</div>
                </a>
            </li>
        @endif

        {{-- Menu Janji Temu & Pembayaran untuk Admin, Resepsionis, dan Pasien --}}
        @if (in_array($role, ['admin', 'resepsionis', 'pasien']))
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi</span>
            </li>

            <li class="menu-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <a href="{{ route('appointments.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-add-to-queue"></i>
                    <div data-i18n="Boxicons">Janji Temu</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('payments.finished') ? 'active' : '' }}">
                <a href="{{ route('payments.finished') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card-alt"></i>
                    <div data-i18n="Boxicons">Pembayaran</div>
                </a>
            </li>
        @endif

        {{-- Menu Laporan (Admin, Resepsionis, dan Pasien hanya bisa lihat kunjungan) --}}
        @if (in_array($role, ['admin', 'resepsionis', 'pasien']))
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan</span>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link">
                    <i class="menu-icon tf-icons bx bxs-spreadsheet"></i>
                    <div data-i18n="Boxicons">Kunjungan</div>
                </a>
            </li>

            @if (in_array($role, ['admin', 'resepsionis']))
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-money"></i>
                        <div data-i18n="Boxicons">Pendapatan</div>
                    </a>
                </li>
            @endif
        @endif
    </ul>
</aside>
