@extends('layouts.admin')

@section('title', 'Dashboard collectes')
@section('header', 'Tableau de bord des collectes')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total collecté</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_collecte'] ?? 0) }} kg</p>
            </div>
            <i class="fas fa-weight-hanging text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Valeur totale</p>
                <p class="text-3xl font-bold">{{ number_format($stats['valeur_totale'] ?? 0, 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-money-bill-wave text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Collecte du mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['collecte_mois'] ?? 0) }} kg</p>
            </div>
            <i class="fas fa-calendar-alt text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Valeur du mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['valeur_mois'] ?? 0, 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-chart-line text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Collectes par produit -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4"> Collectes par produit</h3>
        <div class="space-y-3">
            @forelse($collectes_par_produit ?? [] as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">{{ $item->produit }}</span>
                    <span class="text-sm text-gray-500">{{ number_format($item->total) }} kg</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $max = $collectes_par_produit->max('total') ?? 1;
                        $pourcentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucune donnée de collecte</p>
            @endforelse
        </div>
    </div>
    
    <!-- Dernières collectes -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4"> Dernières collectes</h3>
        <div class="space-y-3">
            @forelse($collectes_recentes ?? [] as $collecte)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <div class="flex items-center">
                        @if($collecte->cooperative_id || $collecte->beneficiaire_type === 'App\\Models\\Cooperative')
                            <i class="fas fa-handshake text-purple-600 mr-2"></i>
                            <p class="font-medium">{{ $collecte->cooperative->nom ?? 'N/A' }}</p>
                        @else
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            <p class="font-medium">{{ $collecte->producteur->nom_complet ?? 'N/A' }}</p>
                        @endif
                        <span class="text-xs text-gray-400 ml-2">
                            ({{ $collecte->cooperative_id ? 'Coopérative' : 'Producteur' }})
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $collecte->produit }} - {{ number_format($collecte->quantite_nette) }} kg
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-primary">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                    <p class="text-xs text-gray-500">{{ $collecte->date_collecte->format('d/m/Y') }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucune collecte récente</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Graphique d'évolution (optionnel) -->
@if(isset($collectes_par_mois) && $collectes_par_mois->count() > 0)
<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4">📈 Évolution des collectes (6 derniers mois)</h3>
    <canvas id="evolutionChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('evolutionChart');
    if (ctx) {
        const labels = {!! json_encode($collectes_par_mois->pluck('mois')->reverse()->values() ?? []) !!};
        const data = {!! json_encode($collectes_par_mois->pluck('total')->reverse()->values() ?? []) !!};
        
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quantité collectée (kg)',
                    data: data,
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
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
                                return context.raw.toLocaleString() + ' kg';
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endif

<!-- Stats supplémentaires : Répartition par type de bénéficiaire -->
@if(isset($stats['collectes_producteurs']) || isset($stats['collectes_cooperatives']))
<div class="mt-6 bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4"> Répartition par bénéficiaire</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-green-50 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-user text-green-600 text-2xl mr-3"></i>
                <div>
                    <p class="text-sm text-gray-600">Producteurs individuels</p>
                    <p class="text-2xl font-bold text-green-700">{{ number_format($stats['collectes_producteurs'] ?? 0) }} kg</p>
                </div>
            </div>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-handshake text-purple-600 text-2xl mr-3"></i>
                <div>
                    <p class="text-sm text-gray-600">Coopératives</p>
                    <p class="text-2xl font-bold text-purple-700">{{ number_format($stats['collectes_cooperatives'] ?? 0) }} kg</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Légende des statuts -->
<div class="mt-6 flex flex-wrap justify-center gap-4 text-xs text-gray-500">
    <span class="flex items-center"><span class="w-3 h-3 bg-primary rounded-full mr-1"></span> Collecte</span>
    <span class="flex items-center"><span class="w-3 h-3 bg-green-500 rounded-full mr-1"></span> Payé</span>
    <span class="flex items-center"><span class="w-3 h-3 bg-yellow-500 rounded-full mr-1"></span> Paiement partiel</span>
    <span class="flex items-center"><span class="w-3 h-3 bg-red-500 rounded-full mr-1"></span> En attente</span>
</div>
@endsection