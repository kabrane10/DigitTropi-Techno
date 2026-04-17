@extends('layouts.admin')

@section('title', 'Dashboard achats')
@section('header', 'Tableau de bord des achats')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total achats</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_achats']) }}</p>
            </div>
            <i class="fas fa-shopping-cart text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Montant total</p>
                <p class="text-3xl font-bold">{{ number_format($stats['montant_total'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-money-bill-wave text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Quantité totale</p>
                <p class="text-3xl font-bold">{{ number_format($stats['quantite_totale']) }} kg</p>
            </div>
            <i class="fas fa-weight-hanging text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Achats ce mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['achats_mois']) }}</p>
            </div>
            <i class="fas fa-calendar-alt text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Achats par produit -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Achats par produit</h3>
        <div class="space-y-3">
            @foreach($achats_par_produit as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">{{ $item->produit }}</span>
                    <span class="text-sm text-gray-500">{{ number_format($item->total) }} kg</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $max = $achats_par_produit->max('total');
                        $pourcentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Achats par mode de paiement -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Achats par mode de paiement</h3>
        <div class="space-y-3">
            @foreach($achats_par_paiement as $item)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div>
                    <i class="fas 
                        @if($item->mode_paiement == 'especes') fa-money-bill-wave text-green-600
                        @elseif($item->mode_paiement == 'virement') fa-university text-blue-600
                        @elseif($item->mode_paiement == 'cheque') fa-file-invoice text-purple-600
                        @else fa-mobile-alt text-orange-600
                        @endif mr-2"></i>
                    <span class="font-medium">{{ ucfirst($item->mode_paiement) }}</span>
                </div>
                <div class="text-right">
                    <p class="font-semibold">{{ number_format($item->total) }} achats</p>
                    <p class="text-xs text-gray-500">{{ number_format($item->montant, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Derniers achats -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">Derniers achats</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs">Code</th>
                    <th class="px-4 py-2 text-left text-xs">Date</th>
                    <th class="px-4 py-2 text-left text-xs">Producteur</th>
                    <th class="px-4 py-2 text-left text-xs">Produit</th>
                    <th class="px-4 py-2 text-right text-xs">Quantité</th>
                    <th class="px-4 py-2 text-right text-xs">Montant</th>
                    <th class="px-4 py-2 text-left text-xs">Acheteur</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($achats_recents as $achat)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $achat->code_achat }}</td>
                    <td class="px-4 py-2 text-sm">{{ $achat->date_achat->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $achat->collecte->producteur->nom_complet }}</td>
                    <td class="px-4 py-2">{{ $achat->collecte->produit }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($achat->quantite) }} kg</td>
                    <td class="px-4 py-2 text-right">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</td>
                    <td class="px-4 py-2">{{ $achat->acheteur }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Évolution des achats -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">Évolution des achats (6 derniers mois)</h3>
    <canvas id="evolutionChart" height="100"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($evolution->pluck('mois')) !!},
            datasets: [
                {
                    label: 'Quantité (kg)',
                    data: {!! json_encode($evolution->pluck('total_quantite')) !!},
                    borderColor: '#2d6a4f',
                    backgroundColor: 'rgba(45, 106, 79, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y'
                },
                {
                    label: 'Montant (CFA)',
                    data: {!! json_encode($evolution->pluck('total_montant')) !!},
                    borderColor: '#ffb703',
                    backgroundColor: 'rgba(255, 183, 3, 0.1)',
                    tension: 0.4,
                    fill: true,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.raw;
                            if (context.dataset.label.includes('Montant')) {
                                return label + ': ' + value.toLocaleString() + ' CFA';
                            }
                            return label + ': ' + value.toLocaleString() + ' kg';
                        }
                    }
                }
            },
            scales: {
                y: { title: { display: true, text: 'Quantité (kg)' } },
                y1: { position: 'right', title: { display: true, text: 'Montant (CFA)' }, grid: { drawOnChartArea: false } }
            }
        }
    });
</script>
@endsection