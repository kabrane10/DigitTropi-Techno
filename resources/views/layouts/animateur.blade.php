<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Animateur - Tropi-Techno</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            width: 5px;
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
    
    <!-- Bouton menu mobile -->
    <button id="mobileMenuButton" class="fixed top-4 left-4 z-50 md:hidden bg-primary text-white p-2 rounded-lg shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>
    
    <!-- Overlay pour mobile -->
    <div id="overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>
    
    <div class="flex h-screen">
        <!-- Sidebar (responsive) -->
        <div id="sidebar" class="sidebar sidebar-mobile fixed md:relative w-64 font-bold h-full bg-gradient-to-b from-primary to-secondary overflow-y-auto shadow-xl z-50 transition-transform duration-300">
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <img src="{{ asset('images/img6.png') }}" class="h-10 w-auto rounded-full" alt="Logo">
                        </div>
                        <div>
                            <span class="font-bold text-xl text-white">Tropi-Techno</span>
                            <p class="text-xs text-white/70">Animateur</p>
                        </div>
                    </div>
                    <button id="closeSidebar" class="md:hidden text-white/70 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Informations animateur connecté -->
            <div class="p-4 border-b border-white/10">
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-white/70 text-xs">Connecté en tant que</p>
                    <p class="text-white font-semibold">{{ Auth::guard('animateur')->user()->nom_complet ?? 'Animateur' }}</p>
                    <p class="text-white/60 text-xs">{{ Auth::guard('animateur')->user()->code_animateur ?? '' }}</p>
                </div>
            </div>
            
            <nav class="p-4 space-y-1">
                <a href="{{ route('animateur.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('animateur.dashboard') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Gestion</p>
                    <a href="{{ route('animateur.agents.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('animateur.agents.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Mes agents</span>
                    </a>
                    <a href="{{ route('animateur.agents.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-user-plus w-5"></i>
                        <span>Nouvel agent</span>
                    </a>
                    <a href="{{ route('animateur.producteurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('animateur.producteurs.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-tractor w-5"></i>
                        <span>Producteurs</span>
                    </a>
                    <a href="{{ route('animateur.suivi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('animateur.suivi.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Suivi terrain</span>
                    </a>
                    <a href="{{ route('animateur.rapports.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('animateur.rapports.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span>Rapports</span>
                    </a>
                </div>
            </nav>
            
            <div class="sticky bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-gradient-to-b from-gray-900 to-primary-dark">
                <form action="{{ route('animateur.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-white/80 hover:text-white w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 w-full md:ml-0 overflow-y-auto">
            <!-- Top Bar (responsive) -->
            <div class="bg-white shadow-sm sticky top-0 z-20">
                <div class="px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center">
                    <h1 class="text-lg sm:text-2xl font-bold text-dark pl-10 md:pl-0">@yield('header')</h1>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs sm:text-sm font-semibold text-dark">{{ Auth::guard('animateur')->user()->nom_complet ?? 'Animateur' }}</p>
                                <p class="text-xs text-gray-500">Animateur</p>
                            </div>
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-chalkboard-user text-sm sm:text-base"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
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
        
        document.querySelectorAll('.delete-confirm').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>