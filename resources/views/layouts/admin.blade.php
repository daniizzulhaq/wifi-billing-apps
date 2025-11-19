<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 lg:hidden hidden"></div>
        
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 w-64 bg-gray-800 text-white flex-shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-30">
            <div class="p-4 border-b border-gray-700 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold"><i class="fas fa-wifi"></i> WiFi Billing</h1>
                    <p class="text-xs text-gray-400">Admin Panel</p>
                </div>
                <!-- Close button (Mobile only) -->
                <button id="closeSidebar" class="lg:hidden text-white hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-home w-5"></i> Dashboard
                </a>
                <a href="{{ route('admin.pelanggan.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.pelanggan.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-users w-5"></i> Pelanggan
                </a>
                <a href="{{ route('admin.tagihan.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.tagihan.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-file-invoice w-5"></i> Tagihan
                </a>
                <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.pembayaran.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-money-bill w-5"></i> Pembayaran
                </a>
                <a href="{{ route('admin.kas.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.kas.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-cash-register w-5"></i> Kas
                </a>
                <a href="{{ route('admin.laporan.laba-rugi') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-700 {{ request()->routeIs('admin.laporan.*') ? 'bg-gray-700' : '' }}">
                    <i class="fas fa-chart-line w-5"></i> Laporan
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden w-full">
            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <!-- Hamburger Menu (Mobile only) -->
                        <button id="openSidebar" class="lg:hidden text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-lg lg:text-xl font-semibold text-gray-800">@yield('header')</h2>
                    </div>
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <span class="text-xs lg:text-sm text-gray-600 hidden sm:inline">{{ Auth::user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs lg:text-sm text-red-600 hover:text-red-800">
                                <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                @if(session('success'))
                    <div class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const openSidebar = document.getElementById('openSidebar');
        const closeSidebar = document.getElementById('closeSidebar');

        function toggleSidebar(show) {
            if (show) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        }

        openSidebar.addEventListener('click', () => toggleSidebar(true));
        closeSidebar.addEventListener('click', () => toggleSidebar(false));
        sidebarOverlay.addEventListener('click', () => toggleSidebar(false));

        // Close sidebar when clicking on navigation links (mobile only)
        if (window.innerWidth < 1024) {
            const navLinks = sidebar.querySelectorAll('nav a');
            navLinks.forEach(link => {
                link.addEventListener('click', () => toggleSidebar(false));
            });
        }

        // Alert auto-dismiss
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 3000);
    </script>
</body>
</html>