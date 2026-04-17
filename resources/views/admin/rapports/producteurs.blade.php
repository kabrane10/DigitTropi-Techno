@extends('layouts.admin')

@section('title', 'Rapport producteurs')
@section('header', 'Rapport des producteurs')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <h2 class="text-xl font-semibold">Liste des producteurs</h2>
        <div class="flex space-x-3">
            <form method="GET" class="flex items-center space-x-2 flex-wrap gap-2">
                <select name="region" class="px-3 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Toutes régions</option>
                    <option value="Centrale" {{ request('region') == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ request('region') == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ request('region') == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
                <select name="statut" class="px-3 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Tous statuts</option>
                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
            </form>
            <a href="{{ route('admin.rapports.export', 'producteurs') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                <i class="fas fa-file-excel mr-2"></i>Excel
            </a>
            <a href="{{ route('admin.rapports.producteurs', ['export' => 'pdf', 'region' => request('region'), 'statut' => request('statut')]) }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                <i class="fas fa-file-pdf mr-2"></i>PDF
            </a>
        </div>
    </div>
    
    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Total producteurs</p>
            <p class="text-2xl font-bold">{{ number_format($stats['total']) }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Producteurs actifs</p>
            <p class="text-2xl font-bold">{{ number_format($stats['producteurs_actifs']) }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Superficie totale</p>
            <p class="text-2xl font-bold">{{ number_format($stats['total_superficie'], 2) }} ha</p>
        </div>
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
            <p class="text-sm opacity-90">Régions couvertes</p>
            <p class="text-2xl font-bold">{{ $stats['par_region']->count() }}</p>
        </div>
    </div>
    
    <!-- Producteurs par région -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold mb-4">Producteurs par région</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($stats['par_region'] as $region)
            <div class="bg-gray-50 rounded-xl p-4 text-center">
                <p class="text-sm text-gray-500">{{ $region->region }}</p>
                <p class="text-2xl font-bold text-primary">{{ number_format($region->total) }}</p>
                <p class="text-xs text-gray-500">producteurs</p>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Tableau des producteurs -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Nom</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Région</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Culture</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Superficie</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($producteurs as $producteur)
                <tr>
                    <td class="px-4 py-3 text-sm">{{ $producteur->code_producteur }}</td>
                    <td class="px-4 py-3 font-medium">{{ $producteur->nom_complet }}</td>
                    <td class="px-4 py-3 text-sm">{{ $producteur->contact }}</td>
                    <td class="px-4 py-3 text-sm">{{ $producteur->region }}</td>
                    <td class="px-4 py-3 text-sm">{{ $producteur->culture_pratiquee }}</td>
                    <td class="px-4 py-3 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $producteur->statut == 'actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $producteur->statut }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-users text-4xl mb-2 opacity-50"></i>
                        <p>Aucun producteur trouvé</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $producteurs->links() }}
    </div>
</div>
@endsection