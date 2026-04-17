<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Contrôleur - Tropi-Techno</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-mobile {
            transform: translateX(-100%);
        }
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0) !important;
            }
        }
        .overlay {
            transition: opacity 0.3s ease;
        }
        .sidebar::-webkit-scrollbar {
            width: 15px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-gray-100 font-inter">
    
    <button id="mobileMenuButton" class="fixed top-4 left-4 z-50 md:hidden bg-primary text-white p-2 rounded-lg shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>
    
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>
    
    <div class="flex h-screen">
        <div id="sidebar" class="sidebar sidebar-mobile fixed md:relative w-64 font-bold h-full bg-gradient-to-b from-primary to-secondary overflow-y-auto shadow-xl z-50 transition-transform duration-300">
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <img src="{{ asset('images/img6.png') }}" class="h-10 w-auto rounded-full" alt="Logo">
                        </div>
                        <div>
                            <span class="font-bold text-xl text-white">Tropi-Techno</span>
                            <p class="text-xs text-white/70">Contrôleur</p>
                        </div>
                    </div>
                    <button id="closeSidebar" class="md:hidden text-white/70 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4 border-b border-white/10">
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-white/70 text-xs">Connecté en tant que</p>
                    <p class="text-white font-semibold">{{ Auth::guard('controleur')->user()->nom_complet ?? 'Contrôleur' }}</p>
                    <p class="text-white/60 text-xs">{{ Auth::guard('controleur')->user()->code_controleur ?? '' }}</p>
                </div>
            </div>
            
            <nav class="p-4 space-y-1">
                <a href="{{ route('controleur.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.dashboard') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Gestion</p>
                    <a href="{{ route('controleur.producteurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.producteurs.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Producteurs</span>
                    </a>
                    <a href="{{ route('controleur.credits.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.credits.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-hand-holding-usd w-5"></i>
                        <span>Crédits</span>
                    </a>
                    <a href="{{ route('controleur.collectes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.collectes.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-truck-loading w-5"></i>
                        <span>Collectes</span>
                    </a>
                    <a href="{{ route('controleur.rapports.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.rapports.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span>Rapports</span>
                    </a>
                </div>
                 <!-- ========== STOCKS ========== -->
                 <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Stocks</p>
                    <a href="{{ route('controleur.stocks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.stocks.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-boxes w-5"></i>
                        <span>État des stocks</span>
                    </a>
                </div>
                
                <!-- ========== SUIVI TERRAIN ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Suivi Terrain</p>
                    <a href="{{ route('controleur.suivi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('controleur.suivi.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Suivi parcellaire</span>
                    </a>
                </div>
                
            </nav>
            
            <div class="sticky bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-gradient-to-b from-gray-900 to-primary-dark">
                <form action="{{ route('controleur.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-white/80 hover:text-white w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="flex-1 w-full md:ml-0 overflow-y-auto">
            <div class="bg-white shadow-sm sticky top-0 z-20">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center">
                    <h1 class="text-lg sm:text-2xl font-bold text-dark pl-10 md:pl-0">@yield('header')</h1>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs sm:text-sm font-semibold text-dark">{{ Auth::guard('controleur')->user()->nom_complet ?? 'Contrôleur' }}</p>
                                <p class="text-xs text-gray-500">Contrôleur</p>
                            </div>
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-user-check text-sm sm:text-base"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 sm:p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded shadow-sm">
                        <div class="flex items-center text-sm sm:text-base">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 sm:p-4 mb-4 sm:mb-6 rounded shadow-sm">
                        <div class="flex items-center text-sm sm:text-base">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </div>
    </div>
    
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const closeSidebar = document.getElementById('closeSidebar');
        
        function openSidebar() {
            sidebar.classList.remove('sidebar-mobile');
            sidebar.classList.add('open');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebarFunc() {
            sidebar.classList.add('sidebar-mobile');
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        
        if (mobileMenuButton) mobileMenuButton.addEventListener('click', openSidebar);
        if (closeSidebar) closeSidebar.addEventListener('click', closeSidebarFunc);
        if (overlay) overlay.addEventListener('click', closeSidebarFunc);
        
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-mobile', 'open');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
</body>
</html>