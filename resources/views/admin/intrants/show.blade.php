@extends('layouts.admin')

@section('title', 'Détails intrant')
@section('header', ' Fiche intrant - ' . $intrant->nom)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations générales -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6 sticky top-20">
            <div class="text-center mb-4">
                <div class="w-20 h-20 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-boxes text-primary text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $intrant->nom }}</h3>
                <p class="text-gray-500 text-sm">{{ $intrant->code_intrant }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Type</span>
                    <span class="font-semibold">{{ $intrant->type_label }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Unité</span>
                    <span class="font-semibold">{{ $intrant->unite }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Prix unitaire</span>
                    <span class="font-semibold text-primary">{{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Statut</span>
                    <span class="px-2 py-1 text-xs rounded-full {{ $intrant->est_actif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $intrant->est_actif ? 'Actif' : 'Inactif' }}
                    </span>
                </div>
            </div>
            
            @if($intrant->description)
            <div class="mt-4 pt-4 border-t">
                <label class="text-gray-500 text-sm">Description</label>
                <p class="text-sm mt-1">{{ $intrant->description }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Stock et graphiques -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Valeur totale -->
        <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white">
            <p class="text-sm opacity-90">Valeur totale du stock</p>
            <p class="text-3xl font-bold">
                {{ number_format($intrant->stocks->sum(fn($s) => $s->stock_actuel * $intrant->prix_unitaire), 0, ',', ' ') }} CFA
            </p>
            <p class="text-xs opacity-75 mt-1">{{ number_format($intrant->stocks->sum('stock_actuel')) }} {{ $intrant->unite }} au total</p>
        </div>
        
        <!-- Graphique de répartition -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4"> Répartition du stock par zone</h3>
            <canvas id="stockChart" height="200"></canvas>
        </div>
        
        <!-- Stock par zone -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4"> Détail par zone</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($intrant->stocks as $stock)
                <div class="border rounded-lg p-4 {{ $stock->est_critique ? 'border-red-300 bg-red-50' : 'border-green-300 bg-green-50' }}">
                    <div class="flex justify-between items-start mb-2">
                        <h4 class="font-bold text-lg">{{ $stock->zone }}</h4>
                        @if($stock->est_critique)
                        <span class="px-2 py-1 text-xs rounded-full bg-red-500 text-white">⚠️ Critique</span>
                        @endif
                    </div>
                    <div class="text-center mb-3">
                        <p class="text-3xl font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($stock->stock_actuel) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $stock->unite }}</p>
                    </div>
                    <div class="text-sm text-gray-600 mb-2">
                        <div class="flex justify-between">
                            <span>Seuil alerte:</span>
                            <span>{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span>Valeur:</span>
                            <span class="font-semibold">{{ number_format($stock->stock_actuel * $intrant->prix_unitaire, 0, ',', ' ') }} CFA</span>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span>Emplacement:</span>
                            <span>{{ $stock->emplacement ?? 'Non défini' }}</span>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('admin.intrants.stock', ['id' => $intrant->id, 'zone' => $stock->zone]) }}" 
                           class="flex-1 text-center bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-secondary">
                            <i class="fas fa-chart-line mr-1"></i>Gérer
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('stockChart').getContext('2d');
    const zones = @json($intrant->stocks->pluck('zone'));
    const stocks = @json($intrant->stocks->pluck('stock_actuel'));
    const couleurs = ['#2d6a4f', '#52b788', '#ffb703', '#1b4332', '#40916c'];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: zones,
            datasets: [{
                data: stocks,
                backgroundColor: couleurs.slice(0, zones.length),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                            return `${label}: ${value.toLocaleString()} ${@json($intrant->unite)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection