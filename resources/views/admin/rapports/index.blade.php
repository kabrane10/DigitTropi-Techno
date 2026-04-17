@extends('layouts.admin')

@section('title', 'Rapports')
@section('header', 'Tableau de bord des rapports')

@section('content')
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
                <p class="text-gray-500 text-sm">Crédits accordés</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</p>
                <p class="text-xs text-yellow-600 mt-1">{{ number_format($stats['taux_remboursement'], 1) }}% remboursé</p>
            </div>
            <i class="fas fa-hand-holding-usd text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Collecte totale</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_collecte']) }} kg</p>
                <p class="text-xs text-gray-600 mt-1">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-truck-loading text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Collecte du mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['collecte_mois']) }} kg</p>
                <p class="text-xs text-gray-600 mt-1">{{ number_format($stats['valeur_mois'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-calendar-alt text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<!-- Graphiques -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Évolution des collectes (12 derniers mois)</h3>
        <canvas id="collectesChart" height="250"></canvas>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Producteurs par région</h3>
        <canvas id="regionChart" height="250"></canvas>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Distribution des crédits</h3>
        <canvas id="creditsChart" height="250"></canvas>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Rapports rapides</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.rapports.financier') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div>
                    <i class="fas fa-chart-line text-primary mr-3"></i>
                    <span class="font-medium">Rapport financier</span>
                    <p class="text-xs text-gray-500">Synthèse des crédits et remboursements</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.rapports.production') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div>
                    <i class="fas fa-tractor text-primary mr-3"></i>
                    <span class="font-medium">Rapport de production</span>
                    <p class="text-xs text-gray-500">Collectes par produit et producteur</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.rapports.credits') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div>
                    <i class="fas fa-hand-holding-usd text-primary mr-3"></i>
                    <span class="font-medium">Rapport des crédits</span>
                    <p class="text-xs text-gray-500">État des crédits et remboursements</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.rapports.producteurs') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div>
                    <i class="fas fa-users text-primary mr-3"></i>
                    <span class="font-medium">Rapport producteurs</span>
                    <p class="text-xs text-gray-500">Liste et statistiques des producteurs</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
            <a href="{{ route('admin.rapports.stocks') }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                <div>
                    <i class="fas fa-boxes text-primary mr-3"></i>
                    <span class="font-medium">Rapport des stocks</span>
                    <p class="text-xs text-gray-500">État des stocks par zone</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400"></i>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des collectes
    const collectesCtx = document.getElementById('collectesChart').getContext('2d');
    new Chart(collectesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($collectes_par_mois->pluck('mois')->reverse()->values()) !!},
            datasets: [{
                label: 'Quantité collectée (kg)',
                data: {!! json_encode($collectes_par_mois->pluck('total_quantite')->reverse()->values()) !!},
                borderColor: '#2d6a4f',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    
    // Graphique des régions
    const regionCtx = document.getElementById('regionChart').getContext('2d');
    new Chart(regionCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($producteurs_par_region->pluck('region')) !!},
            datasets: [{
                label: 'Nombre de producteurs',
                data: {!! json_encode($producteurs_par_region->pluck('total')) !!},
                backgroundColor: '#52b788',
                borderRadius: 8
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    
    // Graphique des crédits
    const creditsCtx = document.getElementById('creditsChart').getContext('2d');
    new Chart(creditsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($credits_par_statut->pluck('statut')) !!},
            datasets: [{
                data: {!! json_encode($credits_par_statut->pluck('total')) !!},
                backgroundColor: ['#ffc107', '#28a745', '#dc3545', '#17a2b8']
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
</script>
@endsection