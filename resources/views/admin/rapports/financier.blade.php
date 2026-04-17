@extends('layouts.admin')

@section('title', 'Rapport financier')
@section('header', 'Rapport financier')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Synthèse financière</h2>
        <div class="flex space-x-3">
            <form method="GET" class="flex items-center space-x-2">
                <select name="periode" class="px-3 py-2 border rounded-lg">
                    <option value="mois" {{ $rapport['periode'] == 'mois' ? 'selected' : '' }}>Ce mois</option>
                    <option value="trimestre" {{ $rapport['periode'] == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                    <option value="annee" {{ $rapport['periode'] == 'annee' ? 'selected' : '' }}>Cette année</option>
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
            </form>
            <a href="{{ route('admin.rapports.financier', ['export' => 'pdf', 'periode' => $rapport['periode']]) }}" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>PDF
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Crédits accordés</p>
            <p class="text-2xl font-bold">{{ number_format($rapport['credits_accordes'], 0, ',', ' ') }} CFA</p>
            <p class="text-xs opacity-75">{{ $rapport['date_debut']->format('d/m/Y') }} - {{ $rapport['date_fin']->format('d/m/Y') }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Remboursements</p>
            <p class="text-2xl font-bold">{{ number_format($rapport['remboursements'], 0, ',', ' ') }} CFA</p>
            <p class="text-xs opacity-75">Taux: {{ number_format($rapport['taux_recouvrement'], 1) }}%</p>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Collectes</p>
            <p class="text-2xl font-bold">{{ number_format($rapport['collectes_montant'], 0, ',', ' ') }} CFA</p>
            <p class="text-xs opacity-75">{{ number_format($rapport['collectes_quantite']) }} kg</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Solde net</p>
            <p class="text-2xl font-bold">{{ number_format($rapport['collectes_montant'] - $rapport['credits_accordes'], 0, ',', ' ') }} CFA</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top producteurs -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Top 10 producteurs (ventes)</h3>
            <div class="space-y-3">
                @foreach($top_producteurs as $producteur)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium">{{ $producteur->nom_complet }}</p>
                        <p class="text-xs text-gray-500">{{ $producteur->code_producteur }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-primary">{{ number_format($producteur->collectes_sum_montant_total, 0, ',', ' ') }} CFA</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Évolution -->
        <div>
            <h3 class="text-lg font-semibold mb-4">Indicateurs clés</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm">Taux de recouvrement</span>
                        <span class="text-sm font-semibold">{{ number_format($rapport['taux_recouvrement'], 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $rapport['taux_recouvrement'] }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm">Part des collectes / crédits</span>
                        <span class="text-sm font-semibold">{{ $rapport['credits_accordes'] > 0 ? number_format(($rapport['collectes_montant'] / $rapport['credits_accordes']) * 100, 1) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $rapport['credits_accordes'] > 0 ? ($rapport['collectes_montant'] / $rapport['credits_accordes']) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection