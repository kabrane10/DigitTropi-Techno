@extends('layouts.admin')

@section('title', 'Dashboard intrants')
@section('header', ' Tableau de bord des intrants')

@section('content')
<!-- KPIs principaux -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Stock total</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_stock']) }} <span class="text-lg">kg</span></p>
                <div class="flex gap-3 mt-2 text-xs">
                    <span class="text-green-600"><i class="fas fa-arrow-up mr-1"></i>Entrées: +{{ number_format($stats['entrees_mois']) }} kg</span>
                    <span class="text-red-600"><i class="fas fa-arrow-down mr-1"></i>Sorties: -{{ number_format($stats['sorties_mois']) }} kg</span>
                </div>
            </div>
            <i class="fas fa-boxes text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Valeur du stock</p>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} <span class="text-lg">CFA</span></p>
                <p class="text-xs text-gray-500 mt-1">Basé sur le prix d'achat moyen</p>
            </div>
            <i class="fas fa-money-bill-wave text-blue-500 text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Alertes actives</p>
                <p class="text-3xl font-bold text-red-600">{{ number_format($stats['alertes']) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['taux_alerte']) }}% du catalogue</p>
            </div>
            <i class="fas fa-exclamation-triangle text-red-500 text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Rotation du stock</p>
                <p class="text-3xl font-bold text-green-600">{{ number_format($stats['rotation_stock'], 1) }}x</p>
                <p class="text-xs text-gray-500 mt-1">Moyenne sur 12 mois</p>
            </div>
            <i class="fas fa-sync-alt text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<!-- Graphiques et Top intrants -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Évolution de la valeur du stock -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold"> Évolution de la valeur du stock</h3>
            <select id="periodeGraph" class="px-3 py-1 border rounded-lg text-sm">
                <option value="6">6 derniers mois</option>
                <option value="12">12 derniers mois</option>
                <option value="3">3 derniers mois</option>
            </select>
        </div>
        <canvas id="evolutionChart" height="200"></canvas>
    </div>
    
    <!-- Top 5 des sorties -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">🏆 Top 5 des intrants les plus utilisés</h3>
        <div class="space-y-4">
            @foreach($topSorties as $item)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium">{{ $item->intrant->nom }}</span>
                    <span class="text-gray-500">{{ number_format($item->total_quantite) }} {{ $item->intrant->unite }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php $max = $topSorties->first()->total_quantite ?? 1; @endphp
                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ ($item->total_quantite / $max) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @if($topSorties->isEmpty())
        <p class="text-gray-500 text-center py-6">Aucune donnée de sortie disponible</p>
        @endif
    </div>
</div>

<!-- Zones interactives -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Stock par zone (cliquable) -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold"> Stock par zone</h3>
            <button onclick="resetZoneFilter()" class="text-xs text-primary hover:underline">Réinitialiser</button>
        </div>
        <div class="space-y-4">
            @foreach($stocksParZone as $zone)
            <div class="cursor-pointer group" onclick="filterByZone('{{ $zone->zone }}')">
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium group-hover:text-primary transition">{{ $zone->zone }}</span>
                    <span>{{ number_format($zone->total) }} kg</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php $maxZone = $stocksParZone->max('total'); @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $maxZone > 0 ? ($zone->total / $maxZone) * 100 : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Activité récente -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4"> Activité récente</h3>
        <div class="space-y-3 max-h-80 overflow-y-auto">
            @forelse($activitesRecentes as $activite)
            <div class="flex items-center text-sm border-b pb-3">
                <span class="w-20 text-gray-400 text-xs">{{ $activite->created_at->diffForHumans() }}</span>
                <span class="flex-shrink-0 w-24">
                    <span class="px-2 py-1 text-xs rounded-full {{ $activite->type == 'entree' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $activite->type == 'entree' ? ' Entrée' : ' Sortie' }}
                    </span>
                </span>
                <span class="flex-1 font-medium">{{ number_format($activite->quantite) }} {{ $activite->stock->unite }}</span>
                <span class="flex-1 text-gray-600">{{ $activite->stock->intrant->nom }}</span>
                <span class="text-gray-400 text-xs w-24 truncate">{{ $activite->stock->zone }}</span>
                <span class="text-gray-400 text-xs italic w-20 truncate">{{ $activite->user->nom ?? 'Système' }}</span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-6">Aucune activité récente</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Actions rapides intelligentes -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4">⚡ Actions rapides</h3>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($alertesParZone as $zone)
        <a href="{{ route('admin.intrants.index', ['zone' => $zone->zone]) }}" 
           class="flex items-center justify-between p-3 rounded-lg border hover:shadow-md transition {{ $zone->nb_alertes > 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50' }}">
            <div>
                <p class="font-semibold">📍 {{ $zone->zone }}</p>
                @if($zone->nb_alertes > 0)
                <p class="text-sm text-red-600">{{ $zone->nb_alertes }} alerte(s) à réapprovisionner</p>
                @else
                <p class="text-sm text-green-600">Stock OK</p>
                @endif
            </div>
            <i class="fas fa-arrow-right text-gray-400"></i>
        </a>
        @endforeach
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <a href="{{ route('admin.intrants.create') }}" class="flex items-center justify-center gap-2 p-3 bg-primary text-white rounded-lg hover:bg-secondary transition">
            <i class="fas fa-plus-circle"></i> Nouvel intrant
        </a>
        <a href="{{ route('admin.intrants.alertes') }}" class="flex items-center justify-center gap-2 p-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
            <i class="fas fa-exclamation-triangle"></i> Gérer les alertes
            @if($stats['alertes'] > 0)
            <span class="bg-white text-orange-600 px-2 py-0.5 rounded-full text-xs">{{ $stats['alertes'] }}</span>
            @endif
        </a>
        <button onclick="genererRapport()" class="flex items-center justify-center gap-2 p-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <i class="fas fa-file-pdf"></i> Rapport d'inventaire PDF
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let evolutionChart = null;
    
    function loadEvolutionChart(months) {
        fetch(`/admin/intrants/evolution-data?months=${months}`)
            .then(response => response.json())
            .then(data => {
                if (evolutionChart) evolutionChart.destroy();
                
                const ctx = document.getElementById('evolutionChart').getContext('2d');
                evolutionChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Valeur du stock (CFA)',
                            data: data.values,
                            borderColor: '#2d6a4f',
                            backgroundColor: 'rgba(45, 106, 79, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.raw.toLocaleString() + ' CFA';
                                    }
                                }
                            }
                        }
                    }
                });
            });
    }
    
    document.getElementById('periodeGraph').addEventListener('change', function() {
        loadEvolutionChart(this.value);
    });
    
    function filterByZone(zone) {
        window.location.href = "{{ route('admin.intrants.index') }}?zone=" + zone;
    }
    
    function resetZoneFilter() {
        window.location.href = "{{ route('admin.intrants.dashboard') }}";
    }
    
    function genererRapport() {
        window.open("{{ route('admin.intrants.rapport-pdf') }}", '_blank');
    }
    
    // Chargement initial
    loadEvolutionChart(6);
</script>
@endsection