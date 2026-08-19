<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Sistem Billing WiFi</title>
    
    <meta name="description" content="Sistem Manajemen Billing WiFi" />

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets1/images/favicon.png') }}?v=2" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <!-- Icons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            
            <!-- Menu Sidebar -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="javascript:void(0);" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('assets1/images/dreamnet.svg') }}" alt="DreamNetIndonesia" style="width: 150px; height: auto;">
                        </span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    
                    <!-- ========================================== -->
                    <!-- MENU KHUSUS PELANGGAN (CLIENT)             -->
                    <!-- ========================================== -->
                    @if(auth()->check() && auth()->user()->role == 'client')
                        
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Menu Utama</span>
                        </li>

                        <!-- Dashboard -->
                        <li class="menu-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('client.dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>

                        <!-- Tagihan Saya -->
                        <li class="menu-item">
                            <a href="{{ route('client.tagihan') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-receipt"></i>
                                <div data-i18n="Tagihan Saya">Tagihan Saya</div>
                            </a>
                        </li>

                        <!-- Pusat Bantuan (Pengaduan) -->
                        <li class="menu-item">
                            <a href="{{ route('client.bantuan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-support"></i>
                                <div data-i18n="Pusat Bantuan">Bantuan & Laporan</div>
                            </a>
                        </li>

                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Pengaturan</span>
                        </li>

                        <!-- Profil Saya -->
                        <li class="menu-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                            <a href="{{ route('profile.edit') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Profil Saya">Profil Saya</div>
                            </a>
                        </li>

                    @endif

                    <!-- ========================================== -->
                    <!-- MENU KHUSUS ADMINISTRATOR                  -->
                    <!-- ========================================== -->
                    @if(auth()->check() && auth()->user()->role == 'admin')
                        
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Manajemen Sistem</span>
                        </li>

                        <!-- Menu Dashboard Admin -->
                        <li class="menu-item {{ request()->routeIs('admin.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>

                        <!-- Menu Data Pelanggan -->
                        <li class="menu-item {{ request()->routeIs('admin.pelanggan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pelanggan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Pelanggan">Data Pelanggan</div>
                            </a>
                        </li>

                        <!-- Menu Broadcast Gangguan -->
                        <li class="menu-item {{ request()->routeIs('admin.broadcast.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.broadcast.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-broadcast"></i>
                                <div data-i18n="Broadcast">Broadcast Gangguan</div>
                            </a>
                        </li>

                        <!-- Menu Paket WiFi -->
                        <li class="menu-item {{ request()->routeIs('admin.paket.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.paket.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-wifi"></i>
                                <div data-i18n="Paket">Paket WiFi</div>
                            </a>
                        </li>

                        <!-- Menu Data Tagihan -->
                        <li class="menu-item {{ request()->routeIs('admin.tagihan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tagihan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                                <div data-i18n="Tagihan">Data Tagihan</div>
                            </a>
                        </li>
                        <!-- Menu Data Laporan / Pengaduan -->
                        <li class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.laporan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-support"></i>
                                <div data-i18n="Laporan">Data Laporan</div>
                            </a>
                        </li>
                        <!-- Menu Data Teknisi -->
                        <li class="menu-item {{ request()->routeIs('*.teknisi.*') || request()->routeIs('teknisi.*') ? 'active' : '' }}">
                            <a href="{{ Route::has('admin.teknisi.index') ? route('admin.teknisi.index') : route('teknisi.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-wrench"></i>
                                <div data-i18n="Teknisi">Data Teknisi</div>
                            </a>
                        </li>
                        <!-- Menu Pengaturan -->
                        <li class="menu-item {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.pengaturan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-cog"></i>
                                <div data-i18n="Pengaturan">Pengaturan</div>
                            </a>
                        </li>
                    @endif

                </ul>
            </aside>
            <!-- / Menu Sidebar -->

            <!-- Layout container -->
            <div class="layout-page">
                
                <!-- Navbar Atas -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- User Dropdown -->
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=random" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'User') }}&background=random" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ auth()->user()->name ?? 'Guest' }}</span>
                                                    <small class="text-muted">{{ auth()->check() ? ucfirst(auth()->user()->role) : '' }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <!-- Form Logout -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar Atas -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Konten Dinamis (Area yang tadinya putih polos) -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Konten Dinamis -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- / Content wrapper -->
            </div>
            <!-- / Layout container -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core Scripts (Menggunakan file lokal) -->
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    
    <!-- Template Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @yield('scripts')
</body>
</html>
