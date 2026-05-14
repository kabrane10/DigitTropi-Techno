@extends('layouts.admin')

@section('title', 'Opérations - ' . $cooperative->nom)
@section('header', 'Gestion des opérations : ' . $cooperative->nom)

@section('content')
<div class="space-y-6">
    <!-- Navigation des opérations -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <div class="flex flex-wrap gap-3">
            <!-- <a href="{{ route('admin.cooperatives.operations.distribution-semence.create', $cooperative) }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                <i class="fas fa-seedling mr-2"></i>Distribuer semences
            </a> -->
            <!-- <a href="{{ route('admin.cooperatives.operations.distribution-intrant.create', $cooperative) }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-flask mr-2"></i>Distribuer intrants
            </a> -->
            <!-- <a href="{{ route('admin.cooperatives.operations.collecte.create', $cooperative) }}" 
               class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                <i class="fas fa-truck mr-2"></i>Nouvelle collecte
            </a> -->
            <a href="{{ route('admin.credits.create', ['cooperative_id' => $cooperative->id]) }}" 
               class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                <i class="fas fa-hand-holding-usd mr-2"></i>Octroyer crédit
            </a>
        </div>
    </div>
    
    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Semences distribuées</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_semences'], 2) }} kg</p>
                    <p class="text-xs opacity-75">{{ number_format($stats['valeur_semences'], 0, ',', ' ') }} CFA</p>
                </div>
                <i class="fas fa-seedling text-3xl opacity-50"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Intrants distribués</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_intrants'], 2) }}</p>
                    <p class="text-xs opacity-75">{{ number_format($stats['valeur_intrants'], 0, ',', ' ') }} CFA</p>
                </div>
                <i class="fas fa-flask text-3xl opacity-50"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Collectes</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['total_collectes'], 2) }} kg</p>
                    <p class="text-xs opacity-75">{{ number_format($stats['valeur_collectes'], 0, ',', ' ') }} CFA</p>
                </div>
                <i class="fas fa-truck text-3xl opacity-50"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm opacity-90">Crédits</p>
                    <p class="text-2xl font-bold">{{ number_format($stats['credits_total'], 0, ',', ' ') }} CFA</p>
                    <p class="text-xs opacity-75">Reste: {{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</p>
                </div>
                <i class="fas fa-hand-holding-usd text-3xl opacity-50"></i>
            </div>
        </div>
    </div>
    
    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Évolution des collectes -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Évolution des collectes (6 mois)</h3>
            <canvas id="collectesChart" height="200"></canvas>
        </div>
        
        <!-- Top produits collectés -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Top produits collectés</h3>
            <canvas id="topProduitsChart" height="200"></canvas>
        </div>
    </div>
    
    <!-- Historique des distributions récentes -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Distributions récentes</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Produit</th>
                        <th class="px-4 py-2 text-right">Quantité</th>
                        <th class="px-4 py-2 text-right">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($cooperative->distributionsSemences->take(5) as $dist)
                    <tr>
                        <td class="px-4 py-2">{{ $dist->date_distribution->format('d/m/Y') }}</td>
                        <td class="px-4 py-2"><span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Semence</span></td>
                        <td class="px-4 py-2">{{ $dist->semence->nom }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($dist->quantite, 2) }} {{ $dist->semence->unite }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($dist->montant_total, 0, ',', ' ') }} CFA</td>
                    </tr>
                    @endforeach
                    @foreach($cooperative->distributionsIntrants->take(5) as $dist)
                    <tr>
                        <td class="px-4 py-2">{{ $dist->date_distribution->format('d/m/Y') }}</td>
                        <td class="px-4 py-2"><span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">Intrant</span></td>
                        <td class="px-4 py-2">{{ $dist->intrant->nom }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($dist->quantite, 2) }} {{ $dist->intrant->unite }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($dist->montant_total, 0, ',', ' ') }} CFA</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des collectes par mois
    const collectesCtx = document.getElementById('collectesChart').getContext('2d');
    new Chart(collectesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($collectesParMois->pluck('mois')) !!},
            datasets: [{
                label: 'Quantité collectée (kg)',
                data: {!! json_encode($collectesParMois->pluck('total')) !!},
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    
    // Graphique des top produits
    const topCtx = document.getElementById('topProduitsChart').getContext('2d');
    new Chart(topCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProduits->pluck('produit')) !!},
            datasets: [{
                label: 'Quantité (kg)',
                data: {!! json_encode($topProduits->pluck('total')) !!},
                backgroundColor: '#3b82f6'
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
});
</script>
@endsection