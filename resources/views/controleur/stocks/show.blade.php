@extends('layouts.controleur')

@section('title', 'Détails stock')
@section('header', 'Fiche de stock')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Stock - {{ $stock->produit }}</h3>
                    <p class="text-white/80 text-sm">{{ $stock->zone }} - {{ $stock->entrepot ?? 'Entrepôt principal' }}</p>
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
                <div>
                    <label class="text-gray-500 text-sm">Dernier mouvement</label>
                    <p>{{ $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y H:i') : 'Aucun mouvement' }}</p>
                </div>
            </div>
            
            <!-- Statistiques stock -->
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
            
            <!-- Historique des mouvements -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold mb-4">Historique des mouvements</h3>
                
                <!-- Entrées -->
                <div class="mb-6">
                    <h4 class="font-semibold text-green-600 mb-2"><i class="fas fa-arrow-down mr-2"></i>Dernières entrées</h4>
                    @if($entrees->count() > 0)
                    <div class="space-y-2">
                        @foreach($entrees as $entree)
                        <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $entree->code_collecte }}</p>
                                <p class="text-xs text-gray-500">{{ $entree->date_collecte->format('d/m/Y') }} - Producteur: {{ $entree->producteur->nom_complet }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-green-600">+ {{ number_format($entree->quantite_nette) }} kg</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Aucune entrée enregistrée</p>
                    @endif
                </div>
                
                <!-- Sorties -->
                <div>
                    <h4 class="font-semibold text-orange-600 mb-2"><i class="fas fa-arrow-up mr-2"></i>Dernières sorties</h4>
                    @if($sorties->count() > 0)
                    <div class="space-y-2">
                        @foreach($sorties as $sortie)
                        <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                            <div>
                                <p class="font-medium">{{ $sortie->code_distribution }}</p>
                                <p class="text-xs text-gray-500">{{ $sortie->date_distribution->format('d/m/Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-orange-600">- {{ number_format($sortie->quantite) }} {{ $sortie->semence->unite }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Aucune sortie enregistrée</p>
                    @endif
                </div>
            </div>
            
            <!-- Notes -->
            @if($stock->notes)
            <div class="mt-6 p-3 bg-gray-50 rounded-lg">
                <label class="text-gray-500 text-sm">Notes</label>
                <p class="mt-1">{{ $stock->notes }}</p>
            </div>
            @endif
            
            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('controleur.stocks.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection