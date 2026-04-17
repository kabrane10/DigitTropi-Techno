<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Agent - Tropi-Techno</title>
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-inter">
    
    <!-- Sidebar -->
    <div class="fixed w-64 h-full bg-gradient-to-b from-primary to-secondary font-bold shadow-xl">
        <div class="p-5 border-b border-white/20">
            <div class="flex items-center space-x-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="fas fa-leaf text-white text-xl"></i>
                </div>
                <div>
                    <span class="font-bold text-xl text-white">Tropi-Techno</span>
                    <p class="text-xs text-white/70">Agent Terrain</p>
                </div>
            </div>
        </div>
        
        <div class="p-4">
            <div class="bg-white/10 rounded-lg p-3 mb-4">
                <p class="text-white/70 text-xs">Connecté en tant que</p>
                <p class="text-white font-semibold">{{ $agent->nom_complet }}</p>
                <p class="text-white/60 text-xs">{{ $agent->code_agent }}</p>
            </div>
            
            <nav class="space-y-1">
                <a href="{{ route('agent.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-white/20 text-white">
                    <i class="fas fa-chart-line w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('agent.producteurs.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 transition">
                    <i class="fas fa-users w-5"></i>
                    <span>Mes producteurs</span>
                </a>
                <a href="{{ route('agent.producteurs.create') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 transition">
                    <i class="fas fa-user-plus w-5"></i>
                    <span>Nouveau producteur</span>
                </a>
                <a href="{{ route('agent.collectes.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 transition">
                    <i class="fas fa-truck-loading w-5"></i>
                    <span>Collectes</span>
                </a>
                <a href="{{ route('agent.suivi.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-white/80 hover:bg-white/10 transition">
                    <i class="fas fa-clipboard-list w-5"></i>
                    <span>Suivi terrain</span>
                </a>
            </nav>
        </div>
        
        <div class="absolute bottom-0 w-full p-4 border-t border-white/20">
            <form action="{{ route('agent.logout') }}" method="POST">
                @csrf
                <button class="flex items-center space-x-3 text-white/80 hover:text-white w-full px-4 py-2 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="ml-64 p-6">
        <!-- Top Bar -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-dark">Tableau de bord</h1>
            <div class="flex items-center space-x-3">
                <span class="text-gray-600">{{ now()->format('d/m/Y') }}</span>
                <div class="w-10 h-10 bg-primary/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-primary"></i>
                </div>
            </div>
        </div>
        
        <!-- Cartes statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Producteurs</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_producteurs']) }}</p>
                        <p class="text-xs text-green-600 mt-1">{{ number_format($stats['producteurs_actifs']) }} actifs</p>
                    </div>
                    <i class="fas fa-users text-primary text-3xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Superficie totale</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['superficie_totale'], 2) }} ha</p>
                    </div>
                    <i class="fas fa-map-marked-alt text-secondary text-3xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes totales</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['total_collectes']) }} kg</p>
                        <p class="text-xs text-gray-600 mt-1">{{ number_format($stats['valeur_collectes'], 0, ',', ' ') }} CFA</p>
                    </div>
                    <i class="fas fa-truck-loading text-accent text-3xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Suivis ce mois</p>
                        <p class="text-3xl font-bold">{{ number_format($stats['suivis_mois']) }}</p>
                    </div>
                    <i class="fas fa-clipboard-list text-green-500 text-3xl opacity-50"></i>
                </div>
            </div>
        </div>
        
        <!-- Graphique et dernières collectes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Évolution des collectes -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Évolution des collectes (6 mois)</h3>
                <canvas id="collectesChart" height="200"></canvas>
            </div>
            
            <!-- Dernières collectes -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold mb-4">Dernières collectes</h3>
                <div class="space-y-3">
                    @forelse($dernieres_collectes as $collecte)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $collecte->producteur->nom_complet }}</p>
                            <p class="text-xs text-gray-500">{{ $collecte->produit }} - {{ $collecte->date_collecte->format('d/m/Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-primary">{{ number_format($collecte->quantite_nette) }} kg</p>
                            <p class="text-xs text-gray-500">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">Aucune collecte enregistrée</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Liste des producteurs récents -->
        <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Producteurs récents</h3>
                <a href="{{ route('agent.producteurs.index') }}" class="text-primary hover:underline text-sm">Voir tous →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs">Code</th>
                            <th class="px-4 py-2 text-left text-xs">Nom</th>
                            <th class="px-4 py-2 text-left text-xs">Contact</th>
                            <th class="px-4 py-2 text-left text-xs">Culture</th>
                            <th class="px-4 py-2 text-right text-xs">Superficie</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($producteurs as $producteur)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $producteur->code_producteur }}</td>
                            <td class="px-4 py-2">{{ $producteur->nom_complet }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->contact }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->culture_pratiquee }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        // Graphique des collectes
        const ctx = document.getElementById('collectesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
                datasets: [{
                    label: 'Quantité collectée (kg)',
                    data: [1200, 1900, 1500, 2100, 1800, 2500],
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
</body>
</html>