@extends('layouts.admin')

@section('title', 'Rapport stocks')
@section('header', 'Rapport des stocks')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-xl font-semibold">État des stocks</h2>
        <div class="flex space-x-3">
            <form method="GET" class="flex items-center space-x-2">
                <select name="zone" class="px-3 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes zones</option>
                    @foreach($zones as $zone)
                    <option value="{{ $zone }}" {{ request('zone') == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </form>
            <a href="{{ route('admin.rapports.export', 'stocks') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                <i class="fas fa-file-excel mr-2"></i>Excel
            </a>
            <a href="{{ route('admin.rapports.stocks', ['export' => 'pdf', 'zone' => request('zone')]) }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                <i class="fas fa-file-pdf mr-2"></i>PDF
            </a>
        </div>
    </div>
    
    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Stock total</p>
            <p class="text-2xl font-bold">{{ number_format($stats['total_stock']) }} kg</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Valeur estimée</p>
            <p class="text-2xl font-bold">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }} CFA</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Produits en stock</p>
            <p class="text-2xl font-bold">{{ number_format($stats['nb_produits']) }}</p>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Alertes stock</p>
            <p class="text-2xl font-bold">{{ number_format($stats['alertes']) }}</p>
        </div>
    </div>
    
    <!-- Tableau des stocks -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Produit</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Zone</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Entrepôt</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Stock actuel</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Seuil alerte</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Dernier mouvement</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($stocks as $stock)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $stock->produit }}</td>
                    <td class="px-4 py-3 text-sm">{{ $stock->zone }}</td>
                    <td class="px-4 py-3 text-sm">{{ $stock->entrepot ?? '-' }}</td>
                    <td class="px-4 py-3 text-right font-semibold {{ $stock->stock_actuel <= $stock->seuil_alerte ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
                    </td>
                    <td class="px-4 py-3 text-right">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</td>
                    <td class="px-4 py-3">
                        @if($stock->stock_actuel <= $stock->seuil_alerte)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Critique
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                Normal
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y') : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-boxes text-4xl mb-2 opacity-50"></i>
                        <p>Aucun stock trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Stock critique -->
    @if($stocks->where('stock_actuel', '<=', 'seuil_alerte')->count() > 0)
    <div class="mt-8 p-4 bg-red-50 rounded-xl">
        <h3 class="text-lg font-semibold text-red-700 mb-3">
            <i class="fas fa-exclamation-triangle mr-2"></i>Stocks critiques
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($stocks->where('stock_actuel', '<=', 'seuil_alerte') as $stock)
            <div class="flex justify-between items-center p-3 bg-white rounded-lg">
                <div>
                    <p class="font-medium">{{ $stock->produit }}</p>
                    <p class="text-xs text-gray-500">{{ $stock->zone }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-red-600">{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                    <p class="text-xs text-gray-500">Seuil: {{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection