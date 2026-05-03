@extends('layouts.agent')

@section('title', 'Dashboard')
@section('header', 'Tableau de bord')

@section('content')

@include('admin.partials.greeting')

<!-- Cartes statistiques -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Producteurs</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['total_producteurs']) }}</p>
                <p class="text-xs text-green-600 mt-1">{{ number_format($stats['producteurs_actifs']) }} actifs</p>
            </div>
            <i class="fas fa-users text-primary text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Superficie totale</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['superficie_totale'], 2) }} ha</p>
            </div>
            <i class="fas fa-map-marked-alt text-secondary text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Collectes totales</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['total_collectes']) }} kg</p>
                <p class="text-xs text-gray-600 mt-1 hidden sm:block">{{ number_format($stats['valeur_collectes'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-truck-loading text-accent text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Suivis ce mois</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['suivis_mois']) }}</p>
            </div>
            <i class="fas fa-clipboard-list text-green-500 text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<!-- Graphique et dernières collectes -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Évolution des collectes -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <h3 class="text-lg font-semibold mb-4">Évolution des collectes (6 mois)</h3>
        <canvas id="collectesChart" height="200"></canvas>
    </div>
    
    <!-- Dernières collectes -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <h3 class="text-lg font-semibold mb-4">Dernières collectes</h3>
        <div class="space-y-3">
            @forelse($dernieres_collectes as $collecte)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:shadow-md transition">
                <div class="flex-1">
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
<div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mt-6">
    <div class="flex justify-between items-center mb-4 flex-wrap gap-2">
        <h3 class="text-lg font-semibold">Producteurs récents</h3>
        <a href="{{ route('agent.producteurs.index') }}" class="text-primary hover:underline text-sm">
            Voir tous <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Contact</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Culture</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Superficie</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($producteurs as $producteur)
                <tr>
                    <td class="px-4 py-2 text-sm font-mono">{{ $producteur->code_producteur }}</td>
                    <td class="px-4 py-2">{{ $producteur->nom_complet }}</td>
                    <td class="px-4 py-2 text-sm">{{ $producteur->contact }}</td>
                    <td class="px-4 py-2 text-sm">{{ $producteur->culture_pratiquee }}</td>
                    <td class="px-4 py-2 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-2 opacity-50"></i>
                        <p>Aucun producteur enregistré</p>
                        <a href="{{ route('agent.producteurs.create') }}" class="inline-block mt-2 text-primary hover:underline">
                            + Ajouter un producteur
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des collectes - données dynamiques
    const ctx = document.getElementById('collectesChart').getContext('2d');
    
    // Données réelles des 6 derniers mois
    @php
        $moisLabels = [];
        $moisData = [];
        for($i = 5; $i >= 0; $i--) {
            $mois = now()->subMonths($i);
            $moisLabels[] = $mois->format('M');
            $total = \App\Models\Collecte::whereMonth('date_collecte', $mois->month)
                ->whereYear('date_collecte', $mois->year)
                ->whereHas('producteur', function($q) use ($agent) {
                    $q->where('agent_terrain_id', $agent->id);
                })
                ->sum('quantite_nette');
            $moisData[] = $total;
        }
    @endphp
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($moisLabels) !!},
            datasets: [{
                label: 'Quantité collectée (kg)',
                data: {!! json_encode($moisData) !!},
                borderColor: '#2d6a4f',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#2d6a4f',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { font: { size: 12 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.raw;
                            return label + ': ' + value.toLocaleString() + ' kg';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Quantité (kg)',
                        font: { size: 12 }
                    }
                }
            }
        }
    });
</script>
@endsection