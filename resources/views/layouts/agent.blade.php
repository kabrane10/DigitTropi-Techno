<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="agent-id" content="{{ Auth::guard('agent')->user()->id ?? '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agent Terrain - Tropi-Techno</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#2d6a4f">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Tropi-Techno">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        /* Scrollbar personnalisée */
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
        
        /* Animation pour le mode hors-ligne */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        .offline-pulse {
            animation: pulse 2s ease-in-out infinite;
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
        <div id="sidebar" class="sidebar sidebar-mobile fixed md:relative w-64 bg-gray-900  h-full overflow-y-auto shadow-xl z-50 transition-transform duration-300">
            <div class="p-5 border-b border-white/10">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <img src="{{ asset('images/img6.png') }}" class="h-10 w-auto rounded-full" alt="Logo">
                        </div>
                        <div>
                            <span class="font-bold text-xl text-white">Tropi-Techno</span>
                            <p class="text-xs text-white/70">Agent Terrain</p>
                        </div>
                    </div>
                    <button id="closeSidebar" class="md:hidden text-white/70 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Informations agent connecté -->
            <div class="p-4 border-b border-white/10">
                <div class="bg-white/10 rounded-lg p-3">
                    <p class="text-white/70 text-xs">Connecté en tant que</p>
                    <p class="text-white font-semibold">{{ Auth::guard('agent')->user()->nom_complet ?? 'Agent' }}</p>
                    <p class="text-white/60 text-xs">{{ Auth::guard('agent')->user()->code_agent ?? '' }}</p>
                </div>
            </div>
            
            <nav class="p-4 space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('agent.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.dashboard') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                
                <!-- ========== GESTION DES PRODUCTEURS ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Producteurs</p>
                    <a href="{{ route('agent.producteurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.producteurs.index') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Mes producteurs</span>
                    </a>
                    <a href="{{ route('agent.producteurs.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10 offline-form">
                        <i class="fas fa-user-plus w-5"></i>
                        <span>Nouveau producteur</span>
                    </a>
                </div>
                
                <!-- ========== GESTION DES COLLECTES ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Collectes</p>
                    <a href="{{ route('agent.collectes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.collectes.index') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-truck-loading w-5"></i>
                        <span>Mes collectes</span>
                    </a>
                    <a href="{{ route('agent.collectes.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10 offline-form">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouvelle collecte</span>
                    </a>
                </div>
                
                <!-- ========== SUIVI TERRAIN ========== -->
                <div class="pt-4">
                    <p class="text-white/50 text-xs uppercase px-4 mb-2">Suivi Terrain</p>
                    <a href="{{ route('agent.suivi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('agent.suivi.index') ? 'bg-primary text-white' : 'text-white/80 hover:bg-white/10' }}">
                        <i class="fas fa-clipboard-list w-5"></i>
                        <span>Mes suivis</span>
                    </a>
                    <a href="{{ route('agent.suivi.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg transition text-white/80 hover:bg-white/10 offline-form">
                        <i class="fas fa-plus-circle w-5"></i>
                        <span>Nouveau suivi</span>
                    </a>
                </div>
            </nav>
            
            <!-- Déconnexion -->
            <div class="sticky bottom-0 left-0 right-0 p-4 border-t border-white/10 bg-gradient-to-b from-gray-900 to-primary-dark">
                <form action="{{ route('agent.logout') }}" method="POST">
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
                        <!-- Statut connexion hors-ligne -->
                        <div id="online-status" class="text-sm px-3 py-1 rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-wifi"></i> En ligne
                        </div>
                        
                        <!-- Badge éléments en attente -->
                        <div id="offline-badge" class="relative hidden">
                            <i class="fas fa-cloud-upload-alt text-gray-500 text-lg"></i>
                            <span class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                        </div>
                        
                        <!-- Agent user -->
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs sm:text-sm font-semibold text-dark">{{ Auth::guard('agent')->user()->nom_complet ?? 'Agent' }}</p>
                                <p class="text-xs text-gray-500">Agent Terrain</p>
                            </div>
                            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-primary rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-user-circle text-sm sm:text-base"></i>
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
                
                <!-- Avertissement mode hors-ligne -->
                <div id="offline-warning" class="hidden bg-orange-50 border-l-4 border-orange-500 text-orange-700 p-3 mb-4 rounded shadow-sm">
                    <div class="flex items-center">
                        <i class="fas fa-wifi-slash mr-2"></i>
                        <span>Mode hors-ligne actif. Les données seront sauvegardées localement et synchronisées automatiquement.</span>
                    </div>
                </div>
                
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
    
    <!-- Scripts offline -->
    <script src="{{ asset('js/offline-manager.js') }}"></script>
    <script>
        // Passer le token au gestionnaire offline
        @auth('agent')
        if (typeof offlineManager !== 'undefined') {
            offlineManager.setToken('{{ $token ?? '' }}');
        }
        @endauth
        
        // Mettre à jour l'affichage du statut
        function updateOnlineStatus() {
            const status = document.getElementById('online-status');
            const warning = document.getElementById('offline-warning');
            const badge = document.getElementById('offline-badge');
            
            if (navigator.onLine) {
                if (status) {
                    status.innerHTML = '<i class="fas fa-wifi"></i> En ligne';
                    status.className = 'text-sm px-3 py-1 rounded-full bg-green-100 text-green-800';
                }
                if (warning) warning.classList.add('hidden');
            } else {
                if (status) {
                    status.innerHTML = '<i class="fas fa-wifi-slash"></i> Hors ligne';
                    status.className = 'text-sm px-3 py-1 rounded-full bg-orange-100 text-orange-800 offline-pulse';
                }
                if (warning) warning.classList.remove('hidden');
            }
        }
        
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        updateOnlineStatus();
        
        // Marquer les formulaires pour sauvegarde hors-ligne
        document.querySelectorAll('form').forEach(form => {
            if (!form.classList.contains('delete-confirm') && !form.action.includes('logout')) {
                form.classList.add('offline-form');
            }
        });
    </script>
    
    <script>
    // Installation de la PWA
    let deferredPrompt;
    
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        
        // Afficher un bouton d'installation
        const installBtn = document.createElement('button');
        installBtn.innerHTML = '<i class="fas fa-download mr-2"></i>Installer l\'application';
        installBtn.className = 'fixed bottom-20 right-4 z-50 bg-primary text-white px-4 py-2 rounded-lg shadow-lg hover:bg-secondary transition z-50';
        installBtn.onclick = async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('Application installée');
                }
                deferredPrompt = null;
                installBtn.remove();
            }
        };
        document.body.appendChild(installBtn);
    });
</script>
</body>
</html>