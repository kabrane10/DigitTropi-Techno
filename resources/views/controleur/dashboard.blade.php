@extends('layouts.controleur')

@section('title', 'Dashboard')
@section('header', 'Tableau de bord - Contrôle')

@section('content')

@include('admin.partials.greeting')

<!-- Cartes statistiques -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Producteurs</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_producteurs']) }}</p>
            </div>
            <i class="fas fa-users text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Crédits actifs</p>
                <p class="text-3xl font-bold">{{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-hand-holding-usd text-yellow-500 text-3xl opacity-50"></i>
        </div>
        @if(isset($credits_retard) && $credits_retard > 0)
        <p class="text-xs text-red-600 mt-1">
            <i class="fas fa-exclamation-triangle mr-1"></i>{{ $credits_retard }} crédits en retard
        </p>
        @endif
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Collectes totales</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_collectes']) }} kg</p>
                <p class="text-xs text-gray-600 mt-1">{{ number_format($stats['valeur_collectes'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-truck-loading text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Stock total</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_stock']) }} kg</p>
                <p class="text-xs text-red-600 mt-1">{{ number_format($stats['alertes_stock']) }} alertes</p>
            </div>
            <i class="fas fa-boxes text-blue-500 text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Taux remboursement</p>
                <p class="text-3xl font-bold">{{ number_format($stats['taux_remboursement'], 1) }}%</p>
            </div>
            <i class="fas fa-chart-line text-purple-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Producteurs par région -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Producteurs par région</h3>
        <canvas id="regionChart" height="200"></canvas>
    </div>
    
    <!-- Collectes par produit -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Collectes par produit</h3>
        <canvas id="produitChart" height="200"></canvas>
    </div>
</div>

<!-- Top producteurs et derniers inscrits -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Top producteurs -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Top 5 producteurs (collectes)</h3>
        <div class="space-y-3">
            @foreach($topProducteurs as $producteur)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $producteur->nom_complet }}</p>
                    <p class="text-xs text-gray-500">{{ $producteur->code_producteur }}</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-primary">{{ number_format($producteur->collectes_sum_quantite_nette ?? 0) }} kg</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Derniers producteurs inscrits -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Derniers producteurs inscrits</h3>
        <div class="space-y-3">
            @foreach($derniersProducteurs as $producteur)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $producteur->nom_complet }}</p>
                    <p class="text-xs text-gray-500">{{ $producteur->region }} - {{ $producteur->culture_pratiquee }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">{{ $producteur->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Évolution des collectes -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">Évolution des collectes (12 mois)</h3>
    <canvas id="evolutionChart" height="100"></canvas>
</div>

<script>
    // Graphique des régions
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    new Chart(regionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($stats['producteurs_par_region']->pluck('region')) !!},
            datasets: [{
                label: 'Nombre de producteurs',
                data: {!! json_encode($stats['producteurs_par_region']->pluck('total')) !!},
                backgroundColor: '#2d6a4f',
                borderRadius: 8
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    
    // Graphique des produits
    const produitCtx = document.getElementById('produitChart').getContext('2d');
    new Chart(produitCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($collectesParProduit->pluck('produit')) !!},
            datasets: [{
                data: {!! json_encode($collectesParProduit->pluck('total')) !!},
                backgroundColor: ['#2d6a4f', '#52b788', '#ffb703', '#1b4332', '#40916c']
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    
    // Graphique d'évolution
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Quantité collectée (kg)',
                data: [1200, 1900, 1500, 2100, 1800, 2500, 2200, 2800, 2400, 3000, 2700, 3200],
                borderColor: '#2d6a4f',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
</script>
@endsection