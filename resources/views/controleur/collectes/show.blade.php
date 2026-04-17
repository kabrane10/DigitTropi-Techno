@extends('layouts.controleur')

@section('title', 'Détails collecte')
@section('header', 'Fiche de collecte')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Collecte #{{ $collecte->code_collecte }}</h3>
                    <p class="text-white/80 text-sm">{{ $collecte->date_collecte->format('d/m/Y') }}</p>
                </div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                    {{ $collecte->statut_paiement }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-gray-500 text-sm">Producteur</label>
                    <p class="font-semibold">{{ $collecte->producteur->nom_complet }}</p>
                    <p class="text-sm text-gray-500">{{ $collecte->producteur->code_producteur }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Zone de collecte</label>
                    <p>{{ $collecte->zone_collecte }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Produit</label>
                    <p class="font-semibold text-lg">{{ $collecte->produit }}</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Quantité</label>
                    <p>{{ number_format($collecte->quantite_nette) }} kg (brut: {{ number_format($collecte->quantite_brute) }} kg)</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Prix unitaire</label>
                    <p>{{ number_format($collecte->prix_unitaire, 0, ',', ' ') }} CFA/kg</p>
                </div>
                <div>
                    <label class="text-gray-500 text-sm">Montant total</label>
                    <p class="text-xl font-bold text-primary">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
            
            @if($collecte->observations)
            <div class="mt-4 pt-4 border-t">
                <label class="text-gray-500 text-sm">Observations</label>
                <p class="mt-1">{{ $collecte->observations }}</p>
            </div>
            @endif
            
            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('controleur.collectes.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection