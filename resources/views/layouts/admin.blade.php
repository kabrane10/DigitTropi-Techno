<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Admin Tropi-Techno - @yield('title')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Styles pour le menu responsive */
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
        <div id="sidebar" class="sidebar sidebar-mobile fixed md:relative w-64 font-bold bg-gradient-to-b from-primary to-secondary h-full overflow-y-auto shadow-xl z-50 transition-transform duration-300">
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <img src="{{ asset('images/img6.png') }}" class="h-10 w-auto rounded-full">
                        </div>
                        <div>
                            <span class="font-bold text-xl text-white">Tropi-Techno</span>
                            <p class="text-xs text-white/70">Administration</p>
                        </div>
                    </div>
                    <button id="closeSidebar" class="md:hidden text-white/70 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- ========== GESTION DES PRODUCTEURS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Producteurs</p>
                    <a href="{{ route('admin.producteurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.producteurs.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Liste producteurs</span>
                    </a>
                    <a href="{{ route('admin.producteurs.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-user-plus w-5"></i>
                        <span>Nouveau producteur</span>
                    </a>
                </div>
                
                <!-- ========== COOPÉRATIVES ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Coopératives</p>
                    <a href="{{ route('admin.cooperatives.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.cooperatives.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-handshake w-5"></i>
                        <span>Gestion coopératives</span>
                    </a>
                    <a href="{{ route('admin.cooperatives.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouvelle coopérative</span>
                    </a>
                </div>
                
                <!-- ========== SEMENCES & DISTRIBUTIONS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Semences & Distributions</p>
                    <a href="{{ route('admin.distributions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.distributions.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-seedling w-5"></i>
                        <span>Distributions</span>
                    </a>
                    <a href="{{ route('admin.distributions.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-truck w-5"></i>
                        <span>Nouvelle distribution</span>
                    </a>
                    <a href="{{ route('admin.distributions.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span>Dashboard distributions</span>
                    </a>
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Semences</p>
                    <a href="{{ route('admin.semences.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.semences.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-seedling w-5"></i>
                        <span>Liste des semences</span>
                    </a>
                    <a href="{{ route('admin.semences.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouvelle semence</span>
                    </a>
                </div>

                <!-- ========== ACHATS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Achats</p>
                    <a href="{{ route('admin.achats.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.achats.index') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span>Dashboard achats</span>
                    </a>
                    <a href="{{ route('admin.achats.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Liste des achats</span>
                    </a>
                </div>
                
                <!-- ========== CRÉDITS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Crédits</p>
                    <a href="{{ route('admin.credits.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.credits.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-hand-holding-usd w-5"></i>
                        <span>Gestion crédits</span>
                    </a>
                    <a href="{{ route('admin.credits.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouveau crédit</span>
                    </a>
                </div>
                
                <!-- ========== COLLECTES ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Collectes</p>
                    <a href="{{ route('admin.collectes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.collectes.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-truck-loading w-5"></i>
                        <span>Gestion collectes</span>
                    </a>
                    <a href="{{ route('admin.collectes.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouvelle collecte</span>
                    </a>
                    <a href="{{ route('admin.collectes.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Dashboard collectes</span>
                    </a>
                </div>
                
                <!-- ========== STOCKS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Stocks</p>
                    <a href="{{ route('admin.stocks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.stocks.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-boxes w-5"></i>
                        <span>Gestion stocks</span>
                    </a>
                    <a href="{{ route('admin.stocks.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-chart-bar w-5"></i>
                        <span>Dashboard stocks</span>
                    </a>
                </div>
                
                <!-- ========== SUIVI TERRAIN ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Suivi Terrain</p>
                    <a href="{{ route('admin.suivi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.suivi.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Tableau de bord suivi</span>
                    </a>
                    <a href="{{ route('admin.suivi.liste') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-list w-5"></i>
                        <span>Liste des suivis</span>
                    </a>
                    <a href="{{ route('admin.suivi.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouveau suivi</span>
                    </a>
                </div>
                
                <!-- ========== RAPPORTS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Rapports</p>
                    <a href="{{ route('admin.rapports.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.rapports.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-chart-pie w-5"></i>
                        <span>Tableau de bord</span>
                    </a>
                    <a href="{{ route('admin.rapports.financier') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-chart-line w-5"></i>
                        <span>Rapport financier</span>
                    </a>
                    <a href="{{ route('admin.rapports.production') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-tractor w-5"></i>
                        <span>Rapport production</span>
                    </a>
                    <a href="{{ route('admin.rapports.credits') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-hand-holding-usd w-5"></i>
                        <span>Rapport crédits</span>
                    </a>
                    <a href="{{ route('admin.rapports.producteurs') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-users w-5"></i>
                        <span>Rapport producteurs</span>
                    </a>
                    <a href="{{ route('admin.rapports.stocks') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-boxes w-5"></i>
                        <span>Rapport stocks</span>
                    </a>
                </div>
                
                <!-- ========== BORDEREAUX ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Bordereaux</p>
                    <a href="{{ route('admin.bordereaux.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.bordereaux.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-file-alt w-5"></i>
                        <span>Tous les bordereaux</span>
                    </a>
                    <a href="{{ route('admin.bordereaux.form-chargement') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-truck w-5"></i>
                        <span>Bordereau chargement</span>
                    </a>
                    <a href="{{ route('admin.bordereaux.form-livraison') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-boxes w-5"></i>
                        <span>Bordereau livraison</span>
                    </a>
                    <a href="{{ route('admin.bordereaux.form-achat') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span>Bordereau achat</span>
                    </a>
                    <a href="{{ route('admin.bordereaux.form-collecte') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-truck-loading w-5"></i>
                        <span>Bordereau de collecte</span>
                    </a>
                </div>
                
                <!-- ========== PERSONNEL ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Personnel</p>
                    <a href="{{ route('admin.animateurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.animateurs.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-chalkboard-user w-5"></i>
                        <span>Animateurs</span>
                    </a>
                    <a href="{{ route('admin.controleurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.controleurs.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Contrôleurs</span>
                    </a>
                    <a href="{{ route('admin.agents.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.agents.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Agents terrain</span>
                    </a>
                </div>
                
                <!-- ========== CONTENU ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Contenu</p>
                    <a href="{{ route('admin.actualites.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.actualites.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-newspaper w-5"></i>
                        <span>Actualités</span>
                    </a>
                </div>
                <!-- ========== GALERIE ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Galerie</p>
    
                    <!-- Option 1: Photos individuelles (ancien système) -->
                    <a href="{{ route('admin.galerie.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.galerie.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-image w-5"></i>
                        <span>Photos individuelles</span>
                    </a>
                    <a href="{{ route('admin.galerie.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Ajouter une photo</span>
                    </a>
    
                    <!-- Option 2: Albums photos (nouveau système) -->
                    <a href="{{ route('admin.albums.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.albums.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-images w-5"></i>
                        <span>Albums photos</span>
                    </a>
                    <a href="{{ route('admin.albums.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Créer un album</span>
                    </a>
                </div>

                <!-- ========== SYSTÈME ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Système</p>
                    <a href="{{ route('admin.backup.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('admin.backup.*') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-database w-5"></i>
                        <span>Sauvegardes</span>
                    </a>
                </div>
            </nav>
            
            <!-- Déconnexion -->
            <div class="sticky bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-gradient-to-b from-gray-900 to-primary-dark">
                <form action="{{ route('admin.logout') }}" method="POST">
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
                        <!-- <div class="relative">
                            <i class="fas fa-bell text-gray-500 cursor-pointer hover:text-primary text-sm sm:text-base"></i>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-3 h-3 sm:w-4 sm:h-4 flex items-center justify-center">3</span>
                        </div> -->
                         <!-- Notifications -->
                        <div class="relative">
                            <button id="notificationBtn" class="relative focus:outline-none">
                                <i class="fas fa-bell text-gray-500 hover:text-primary text-xl transition"></i>
                                <span id="notificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                            </button>
                
                             <!-- Dropdown notifications -->
                            <div id="notificationDropdown" class="absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl hidden z-50 border">
                                <div class="p-3 border-b flex justify-between items-center">
                                    <h3 class="font-semibold text-dark">Notifications</h3>
                                    <button id="markAllRead" class="text-xs text-primary hover:underline">Tout marquer comme lu</button>
                                </div>
                                <div id="notificationList" class="max-h-96 overflow-y-auto">
                                    <div class="p-6 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-3xl mb-2 opacity-50"></i>
                                        <p>Aucune notification</p>
                                    </div>
                                </div>
                                <div class="p-2 border-t text-center">
                                    <a href="{{ route('admin.notifications.index') }}" class="text-xs text-gray-500 hover:text-primary">Voir toutes les notifications</a>
                                </div>
                            </div>
                        </div>
            
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs sm:text-sm font-semibold text-dark">Admin User</p>
                                <p class="text-xs text-gray-500">Administrateur</p>
                            </div>
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-user text-sm sm:text-base"></i>
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
        // Menu mobile
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
        
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', openSidebar);
        }
        
        if (closeSidebar) {
            closeSidebar.addEventListener('click', closeSidebarFunc);
        }
        
        if (overlay) {
            overlay.addEventListener('click', closeSidebarFunc);
        }
        
        // Confirmation avant suppression
        document.querySelectorAll('.delete-confirm').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.')) {
                    e.preventDefault();
                }
            });
        });
        
        // Gérer le redimensionnement
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('sidebar-mobile', 'open');
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });
    </script>
  <script>
// Script notifications simplifié
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('notificationBtn');
    const dropdown = document.getElementById('notificationDropdown');
    const list = document.getElementById('notificationList');
    const countSpan = document.getElementById('notificationCount');
    const markAllBtn = document.getElementById('markAllRead');
    
    function loadNotifications() {
        fetch('/admin/notifications/recent', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur serveur');
            }
            return response.json();
        })
        .then(data => {
            console.log('Notifications:', data);
            
            // Mettre à jour le compteur
            if (data.non_lues > 0) {
                countSpan.textContent = data.non_lues > 99 ? '99+' : data.non_lues;
                countSpan.classList.remove('hidden');
            } else {
                countSpan.classList.add('hidden');
            }
            
            // Afficher les notifications
            if (data.notifications && data.notifications.length > 0) {
                let html = '';
                data.notifications.forEach(notif => {
                    let date = new Date(notif.created_at);
                    let dateStr = date.toLocaleDateString('fr-FR') + ' ' + date.toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'});
                    
                    html += `
                        <div class="p-3 border-b hover:bg-gray-50 cursor-pointer ${!notif.is_read ? 'bg-blue-50' : ''}" onclick="window.location.href='${notif.url}'">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    ${getIcon(notif.type)}
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold">${notif.title}</p>
                                    <p class="text-xs text-gray-600">${notif.message}</p>
                                    <p class="text-xs text-gray-400 mt-1">${dateStr}</p>
                                </div>
                                ${!notif.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                            </div>
                        </div>
                    `;
                });
                list.innerHTML = html;
            } else {
                list.innerHTML = '<div class="p-6 text-center text-gray-500">Aucune notification</div>';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            list.innerHTML = '<div class="p-6 text-center text-red-500">Erreur de chargement</div>';
        });
    }
    
    function getIcon(type) {
        const icons = {
            'stock': '<i class="fas fa-boxes text-orange-500 text-lg"></i>',
            'credit': '<i class="fas fa-hand-holding-usd text-red-500 text-lg"></i>',
            'producteur': '<i class="fas fa-user-plus text-green-500 text-lg"></i>',
            'collecte': '<i class="fas fa-truck-loading text-blue-500 text-lg"></i>',
            'remboursement': '<i class="fas fa-money-bill-wave text-green-500 text-lg"></i>'
        };
        return icons[type] || '<i class="fas fa-bell text-gray-400 text-lg"></i>';
    }
    
    function markAllAsRead() {
        fetch('/admin/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(() => loadNotifications())
        .catch(error => console.error('Erreur:', error));
    }
    
    // Événements
    if (btn) {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                loadNotifications();
            }
        });
    }
    
    document.addEventListener('click', () => {
        if (dropdown) dropdown.classList.add('hidden');
    });
    
    if (dropdown) {
        dropdown.addEventListener('click', (e) => e.stopPropagation());
    }
    
    if (markAllBtn) {
        markAllBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            markAllAsRead();
        });
    }
    
    setInterval(() => {
    loadNotifications();
}, 10000); // Recharger toutes les 10 secondes
    
});
</script>
</body>
</html>