@extends('layouts.admin')

@section('title', 'Détails du stock')
@section('header', 'Fiche de stock')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Détails du stock</h3>
                    <p class="text-white/80 text-sm">{{ $stock->produit }} - {{ $stock->zone }}</p>
                </div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                    {{ $stock->unite }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="text-gray-500 text-sm">Produit</label>
                    <p class="font-semibold text-lg">{{ $stock->produit }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Zone</label>
                    <p>{{ $stock->zone }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Entrepôt</label>
                    <p>{{ $stock->entrepot ?? 'Non spécifié' }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Unité</label>
                    <p>{{ $stock->unite }}</p>
                </div>
            </div>
            
            <!-- Statistiques de stock -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-50 rounded-xl p-4 text-center">
                    <p class="text-sm text-gray-600">Stock actuel</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 text-center">
                    <p class="text-sm text-gray-600">Entrées totales</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stock->quantite_entree) }} {{ $stock->unite }}</p>
                </div>
                <div class="bg-orange-50 rounded-xl p-4 text-center">
                    <p class="text-sm text-gray-600">Sorties totales</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stock->quantite_sortie) }} {{ $stock->unite }}</p>
                </div>
            </div>
            
            <!-- Seuil d'alerte -->
            <div class="mb-6">
                <label class="text-gray-500 text-sm">Seuil d'alerte</label>
                <div class="mt-2">
                    <div class="flex items-center">
                        <span class="font-semibold mr-2">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</span>
                        @if($stock->stock_actuel <= $stock->seuil_alerte)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Stock critique
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Stock normal
                            </span>
                        @endif
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        @php
                            $pourcentage = $stock->seuil_alerte > 0 ? ($stock->stock_actuel / $stock->seuil_alerte) * 100 : 0;
                            $pourcentage = min($pourcentage, 100);
                        @endphp
                        <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Dernier mouvement -->
            <div class="mb-6">
                <label class="text-gray-500 text-sm">Dernier mouvement</label>
                <p>{{ $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y H:i') : 'Aucun mouvement' }}</p>
            </div>
            
            <!-- Notes -->
            @if($stock->notes)
            <div class="mb-6">
                <label class="text-gray-500 text-sm">Notes</label>
                <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $stock->notes }}</p>
            </div>
            @endif
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.stocks.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i> Retour
                </a>
                <div class="space-x-3">
                    <a href="{{ route('admin.stocks.mouvements', $stock) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-history mr-2"></i> Voir mouvements
                    </a>
                    <a href="{{ route('admin.stocks.edit', $stock) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-edit mr-2"></i> Modifier seuil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection