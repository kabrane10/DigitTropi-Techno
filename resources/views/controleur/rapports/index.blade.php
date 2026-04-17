@extends('layouts.controleur')

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
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Évolution des collectes</h3>
        <canvas id="collectesChart" height="200"></canvas>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Crédits par statut</h3>
        <canvas id="creditsChart" height="200"></canvas>
    </div>
</div>

<!-- Boutons d'export -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <h3 class="text-lg font-semibold mb-4">Exporter les rapports</h3>
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('controleur.rapports.export-pdf', ['type' => 'global']) }}" target="_blank" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center">
            <i class="fas fa-file-pdf mr-2"></i> Exporter rapport global (PDF)
        </a>
        <a href="{{ route('controleur.rapports.export-pdf', ['type' => 'producteurs']) }}" target="_blank" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center">
            <i class="fas fa-file-pdf mr-2"></i> Rapport producteurs (PDF)
        </a>
        <a href="{{ route('controleur.rapports.export-pdf', ['type' => 'credits']) }}" target="_blank" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center">
            <i class="fas fa-file-pdf mr-2"></i> Rapport crédits (PDF)
        </a>
        <a href="{{ route('controleur.rapports.export-pdf', ['type' => 'collectes']) }}" target="_blank" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center">
            <i class="fas fa-file-pdf mr-2"></i> Rapport collectes (PDF)
        </a>
    </div>
</div>

<!-- Derniers producteurs -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <h3 class="text-lg font-semibold mb-4">Derniers producteurs inscrits</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Région</th>
                    <th class="px-4 py-2 text-left">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($derniersProducteurs ?? [] as $producteur)
                <tr>
                    <td class="px-4 py-2">{{ $producteur->nom_complet }}</td>
                    <td class="px-4 py-2">{{ $producteur->code_producteur }}</td>
                    <td class="px-4 py-2">{{ $producteur->region }}</td>
                    <td class="px-4 py-2">{{ $producteur->created_at->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('collectesChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($collectesParMois->pluck('mois')->reverse() ?? []) !!},
            datasets: [{
                label: 'Collectes (kg)',
                data: {!! json_encode($collectesParMois->pluck('total')->reverse() ?? []) !!},
                borderColor: '#2d6a4f',
                backgroundColor: 'rgba(45, 106, 79, 0.1)',
                tension: 0.4,
                fill: true
            }]
        }
    });
    
    new Chart(document.getElementById('creditsChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($creditsParStatut->pluck('statut') ?? []) !!},
            datasets: [{
                data: {!! json_encode($creditsParStatut->pluck('total') ?? []) !!},
                backgroundColor: ['#ffc107', '#28a745', '#dc3545']
            }]
        }
    });
</script>
@endsection