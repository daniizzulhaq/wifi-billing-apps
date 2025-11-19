<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title') - Sistem WiFi</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }
        
        #wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        
        #page-content-wrapper {
            width: 100%;
            margin-left: 250px;
            transition: all 0.3s ease;
        }
        
        .sidebar-heading {
            padding: 1.5rem 1rem;
            font-size: 1.2rem;
            color: white;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .list-group-item {
            border: none;
            background: transparent;
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem 1.5rem;
            transition: all 0.3s;
        }
        
        .list-group-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 2rem;
        }
        
        .list-group-item.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: bold;
        }
        
        .list-group-item i {
            margin-right: 0.75rem;
            width: 20px;
        }
        
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }
        
        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .text-gray-300 {
            color: #dddfeb !important;
        }

        /* Overlay untuk mobile */
        #sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        
        /* Responsive untuk tablet dan mobile */
        @media (max-width: 992px) {
            #sidebar-wrapper {
                margin-left: -250px;
            }
            
            #page-content-wrapper {
                margin-left: 0;
                width: 100%;
            }
            
            #wrapper.toggled #sidebar-wrapper {
                margin-left: 0;
            }
            
            #wrapper.toggled #sidebar-overlay {
                display: block;
            }
            
            .sidebar-heading {
                font-size: 1rem;
                padding: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .navbar .container-fluid {
                padding: 0.5rem 1rem;
            }
            
            .navbar .me-3 {
                display: none;
            }
            
            .list-group-item {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            
            .container-fluid {
                padding: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
        }

        /* Tombol close sidebar di mobile */
        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 10;
        }

        .sidebar-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 992px) {
            .sidebar-close-btn {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <div id="wrapper">
        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay"></div>

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <button class="sidebar-close-btn" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
            <div class="sidebar-heading">
                <i class="fas fa-wifi"></i> Sistem WiFi
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('pelanggan.dashboard') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="{{ route('pelanggan.tagihan.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('pelanggan.tagihan.*') ? 'active' : '' }}">
                    <i class="fas fa-fw fa-file-invoice"></i>
                    Tagihan Saya
                </a>
                <a href="{{ route('pelanggan.pembayaran.index') }}" 
                   class="list-group-item list-group-item-action {{ request()->routeIs('pelanggan.pembayaran.*') ? 'active' : '' }}">
                    <i class="fas fa-fw fa-history"></i>
                    Riwayat Pembayaran
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    <i class="fas fa-fw fa-user"></i>
                    Profil
                </a>
            </div>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white mb-4">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3 d-none d-md-inline">{{ Auth::user()->name }}</span>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li class="d-md-none">
                                    <div class="dropdown-header">
                                        <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                </li>
                                <li class="d-md-none"><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw me-2"></i>
                                        Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cog fa-sm fa-fw me-2"></i>
                                        Pengaturan
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Toggle sidebar
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarClose = document.getElementById('sidebarClose');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const wrapper = document.getElementById('wrapper');

        function toggleSidebar() {
            wrapper.classList.toggle('toggled');
        }

        function closeSidebar() {
            wrapper.classList.remove('toggled');
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarClose.addEventListener('click', closeSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Close sidebar saat klik link di mobile
        if (window.innerWidth <= 992) {
            const sidebarLinks = document.querySelectorAll('#sidebar-wrapper .list-group-item');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', closeSidebar);
            });
        }
        
        // Auto dismiss alerts
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>