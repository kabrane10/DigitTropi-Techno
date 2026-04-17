@extends('layouts.admin')

@section('title', 'Détails coopérative')
@section('header', 'Fiche coopérative')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Carte informations -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-handshake text-primary text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $cooperative->nom }}</h3>
                <p class="text-gray-500">{{ $cooperative->code_cooperative }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-phone w-8 text-gray-400"></i>
                    <span>{{ $cooperative->contact }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-envelope w-8 text-gray-400"></i>
                    <span>{{ $cooperative->email ?? 'Non renseigné' }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-map-marker-alt w-8 text-gray-400"></i>
                    <span>{{ $cooperative->localisation }} ({{ $cooperative->region }})</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-calendar w-8 text-gray-400"></i>
                    <span>Créée le {{ $cooperative->date_creation->format('d/m/Y') }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-users w-8 text-gray-400"></i>
                    <span>{{ number_format($stats['nb_membres']) }} membres</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-chart-line w-8 text-gray-400"></i>
                    <span>{{ number_format($stats['superficie_totale'], 2) }} hectares cultivés</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-tag w-8 text-gray-400"></i>
                    <span class="px-2 py-1 text-xs rounded-full {{ $cooperative->statut == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $cooperative->statut }}
                    </span>
                </div>
            </div>
            
            @if($cooperative->description)
            <div class="mt-4 pt-4 border-t">
                <label class="text-gray-500 text-sm">Description</label>
                <p class="text-sm mt-1">{{ $cooperative->description }}</p>
            </div>
            @endif
            
            <div class="mt-6 pt-4 border-t flex justify-between">
                <a href="{{ route('admin.cooperatives.edit', $cooperative) }}" class="text-green-600 hover:underline">
                    <i class="fas fa-edit mr-1"></i>Modifier
                </a>
                <form action="{{ route('admin.cooperatives.destroy', $cooperative) }}" method="POST" class="inline delete-confirm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:underline">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Statistiques et membres -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Cartes stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Crédits accordés</p>
                <p class="text-2xl font-bold">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</p>
            </div>
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Crédits restants</p>
                <p class="text-2xl font-bold">{{ number_format($stats['credits_restants'], 0, ',', ' ') }} CFA</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-4 text-white">
                <p class="text-sm opacity-90">Superficie totale</p>
                <p class="text-2xl font-bold">{{ number_format($stats['superficie_totale'], 2) }} ha</p>
            </div>
        </div>
        
        <!-- Liste des membres -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">Membres de la coopérative</h3>
            @if($cooperative->producteurs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs">Code</th>
                            <th class="px-4 py-2 text-left text-xs">Nom</th>
                            <th class="px-4 py-2 text-left text-xs">Contact</th>
                            <th class="px-4 py-2 text-left text-xs">Culture</th>
                            <th class="px-4 py-2 text-right text-xs">Superficie</th>
                            <th class="px-4 py-2 text-center text-xs">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($cooperative->producteurs as $producteur)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $producteur->code_producteur }}</td>
                            <td class="px-4 py-2">{{ $producteur->nom_complet }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->contact }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producteur->culture_pratiquee }}</td>
                            <td class="px-4 py-2 text-right text-sm">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('admin.producteurs.show', $producteur) }}" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-gray-500 text-center py-6">Aucun membre dans cette coopérative</p>
            @endif
        </div>
    </div>
</div>
@endsection