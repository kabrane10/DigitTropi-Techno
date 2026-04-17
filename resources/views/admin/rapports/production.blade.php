@extends('layouts.admin')

@section('title', 'Rapport de production')
@section('header', 'Rapport de production')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Collectes par période</h2>
        <div class="flex space-x-3">
            <form method="GET" class="flex items-center space-x-2 flex-wrap gap-2">
                <select name="produit" class="px-3 py-2 border rounded-lg">
                    <option value="">Tous les produits</option>
                    @foreach($produits_liste as $p)
                    <option value="{{ $p }}" {{ $produit == $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_debut" value="{{ $date_debut }}" class="px-3 py-2 border rounded-lg">
                <input type="date" name="date_fin" value="{{ $date_fin }}" class="px-3 py-2 border rounded-lg">
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg">Filtrer</button>
            </form>
            <a href="{{ route('admin.rapports.production', ['export' => 'pdf', 'produit' => $produit, 'date_debut' => $date_debut, 'date_fin' => $date_fin]) }}" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-file-pdf mr-2"></i>PDF
            </a>
        </div>
    </div>
    
    <!-- Cartes stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-primary/10 rounded-xl p-4">
            <p class="text-sm text-gray-600">Quantité totale</p>
            <p class="text-2xl font-bold text-primary">{{ number_format($stats['total_quantite']) }} kg</p>
        </div>
        <div class="bg-green-100 rounded-xl p-4">
            <p class="text-sm text-gray-600">Valeur totale</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_valeur'], 0, ',', ' ') }} CFA</p>
        </div>
        <div class="bg-blue-100 rounded-xl p-4">
            <p class="text-sm text-gray-600">Nombre de collectes</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['nb_collectes']) }}</p>
        </div>
        <div class="bg-orange-100 rounded-xl p-4">
            <p class="text-sm text-gray-600">Producteurs actifs</p>
            <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['nb_producteurs']) }}</p>
        </div>
    </div>
    
    <!-- Top producteurs -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Top producteurs (période)</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Producteur</th>
                        <th class="px-4 py-2 text-right">Quantité (kg)</th>
                        <th class="px-4 py-2 text-right">Montant (CFA)</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($top_producteurs as $tp)
                    <tr>
                        <td class="px-4 py-2">{{ $tp->producteur->nom_complet }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($tp->total_quantite) }}</td>
                        <td class="px-4 py-2 text-right">{{ number_format($tp->total_montant, 0, ',', ' ') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Liste des collectes -->
    <h3 class="text-lg font-semibold mb-4">Détail des collectes</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Producteur</th>
                    <th class="px-4 py-2 text-left">Produit</th>
                    <th class="px-4 py-2 text-right">Quantité</th>
                    <th class="px-4 py-2 text-right">Montant</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($collectes as $collecte)
                <tr>
                    <td class="px-4 py-2">{{ $collecte->date_collecte->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $collecte->code_collecte }}</td>
                    <td class="px-4 py-2">{{ $collecte->producteur->nom_complet }}</td>
                    <td class="px-4 py-2">{{ $collecte->produit }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($collecte->quantite_nette) }} kg</td>
                    <td class="px-4 py-2 text-right">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $collectes->links() }}
    </div>
</div>
@endsection