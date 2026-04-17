@extends('layouts.admin')

@section('title', 'Mouvements de stock')
@section('header', 'Historique des mouvements - ' . $stock->produit)

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Infos produit -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Produit</label>
                <p class="font-semibold text-lg">{{ $stock->produit }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Zone</label>
                <p>{{ $stock->zone }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Stock actuel</label>
                <p class="text-2xl font-bold {{ $stock->stock_actuel <= $stock->seuil_alerte ? 'text-red-600' : 'text-green-600' }}">
                    {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
                </p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Dernier mouvement</label>
                <p>{{ $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y H:i') : '-' }}</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Entrées (Collectes) -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="bg-green-500 px-6 py-3 rounded-t-xl">
                <h3 class="text-white font-semibold"><i class="fas fa-arrow-down mr-2"></i>Entrées en stock</h3>
            </div>
            <div class="p-4">
                @if($entrees->count() > 0)
                <div class="space-y-3">
                    @foreach($entrees as $entree)
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $entree->code_collecte }}</p>
                            <p class="text-xs text-gray-500">{{ $entree->date_collecte->format('d/m/Y') }}</p>
                            <p class="text-xs">Producteur: {{ $entree->producteur->nom_complet }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-green-600">+ {{ number_format($entree->quantite_nette) }} kg</p>
                            <p class="text-xs text-gray-500">{{ number_format($entree->montant_total, 0, ',', ' ') }} CFA</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-6">Aucune entrée enregistrée</p>
                @endif
            </div>
        </div>
        
        <!-- Sorties (Distributions) -->
        <div class="bg-white rounded-xl shadow-sm">
            <div class="bg-orange-500 px-6 py-3 rounded-t-xl">
                <h3 class="text-white font-semibold"><i class="fas fa-arrow-up mr-2"></i>Sorties de stock</h3>
            </div>
            <div class="p-4">
                @if($sorties->count() > 0)
                <div class="space-y-3">
                    @foreach($sorties as $sortie)
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $sortie->code_distribution }}</p>
                            <p class="text-xs text-gray-500">{{ $sortie->date_distribution->format('d/m/Y') }}</p>
                            <p class="text-xs">Producteur: {{ $sortie->producteur->nom_complet }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-orange-600">- {{ number_format($sortie->quantite) }} {{ $sortie->semence->unite }}</p>
                            <p class="text-xs text-gray-500">{{ $sortie->saison }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-6">Aucune sortie enregistrée</p>
                @endif
            </div>
        </div>
    </div>
    
    <div class="mt-6 text-center">
        <a href="{{ route('admin.stocks.index') }}" class="text-primary hover:underline">
            <i class="fas fa-arrow-left mr-1"></i>Retour aux stocks
        </a>
    </div>
</div>
@endsection