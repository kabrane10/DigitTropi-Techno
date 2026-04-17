@extends('layouts.admin')

@section('title', 'Détails collecte')
@section('header', 'Fiche de collecte')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <h3 class="text-white text-xl font-semibold">Collecte #{{ $collecte->code_collecte }}</h3>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                    {{ $collecte->date_collecte->format('d/m/Y') }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-gray-500 text-sm">Producteur</label>
                    <p class="font-semibold text-lg">{{ $collecte->producteur->nom_complet }}</p>
                    <p class="text-sm text-gray-500">{{ $collecte->producteur->code_producteur }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Zone de collecte</label>
                    <p class="font-semibold">{{ $collecte->zone_collecte }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Produit</label>
                    <p class="font-semibold text-lg">{{ $collecte->produit }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Quantité</label>
                    <p class="font-semibold">{{ number_format($collecte->quantite_nette) }} kg (brut: {{ number_format($collecte->quantite_brute) }} kg)</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Prix unitaire</label>
                    <p class="font-semibold">{{ number_format($collecte->prix_unitaire, 0, ',', ' ') }} CFA/kg</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Montant total</label>
                    <p class="font-semibold text-xl text-primary">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
                
                @if($collecte->credit_id)
                <div>
                    <label class="text-gray-500 text-sm">Crédit associé</label>
                    <p class="font-semibold">{{ $collecte->credit->code_credit ?? 'N/A' }}</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Montant déduit</label>
                    <p class="font-semibold text-orange-600">{{ number_format($collecte->montant_deduict, 0, ',', ' ') }} CFA</p>
                </div>
                @endif
                
                <div>
                    <label class="text-gray-500 text-sm">Montant à payer</label>
                    <p class="font-semibold text-xl text-green-600">{{ number_format($collecte->montant_a_payer, 0, ',', ' ') }} CFA</p>
                </div>
                
                <div>
                    <label class="text-gray-500 text-sm">Statut paiement</label>
                    <p>
                        <span class="px-3 py-1 rounded-full text-sm 
                            @if($collecte->statut_paiement == 'paye') bg-green-100 text-green-800
                            @elseif($collecte->statut_paiement == 'partiel') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ $collecte->statut_paiement }}
                        </span>
                    </p>
                </div>
            </div>
            
            @if($collecte->observations)
            <div class="mt-6 pt-6 border-t">
                <label class="text-gray-500 text-sm">Observations</label>
                <p class="mt-1 text-gray-700">{{ $collecte->observations }}</p>
            </div>
            @endif
            
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.collectes.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour
                </a>
                <form action="{{ route('admin.collectes.destroy', $collecte) }}" method="POST" class="inline delete-confirm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    <form action="{{ route('admin.bordereaux.generer-collecte', $collecte->id) }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
        <i class="fas fa-file-alt mr-2"></i>Générer bordereau
    </button>
</form>
</div>
@endsection