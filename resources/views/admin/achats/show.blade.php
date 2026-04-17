@extends('layouts.admin')

@section('title', 'Détails achat')
@section('header', 'Fiche bordereau d\'achat')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Bordereau d'achat</h3>
                    <p class="text-white/80 text-sm">N° {{ $achat->code_achat }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">{{ $achat->date_achat->format('d/m/Y') }}</p>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        {{ $achat->statut }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Informations générales -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Informations générales</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Code achat</label>
                        <p class="font-semibold">{{ $achat->code_achat }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Date d'achat</label>
                        <p>{{ $achat->date_achat->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Acheteur</label>
                        <p>{{ $achat->acheteur }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Mode de paiement</label>
                        <p>{{ $achat->mode_paiement }}</p>
                    </div>
                    @if($achat->reference_facture)
                    <div>
                        <label class="text-gray-500 text-sm">Référence facture</label>
                        <p>{{ $achat->reference_facture }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Informations collecte -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Informations collecte associée</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Code collecte</label>
                        <p class="font-semibold">{{ $achat->collecte->code_collecte }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Producteur</label>
                        <p>{{ $achat->collecte->producteur->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Produit</label>
                        <p>{{ $achat->collecte->produit }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Date collecte</label>
                        <p>{{ $achat->collecte->date_collecte->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Détails de l'achat -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Détails de l'achat</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <label class="text-gray-500 text-sm">Quantité</label>
                        <p class="text-2xl font-bold text-primary">{{ number_format($achat->quantite) }} kg</p>
                    </div>
                    <div class="text-center">
                        <label class="text-gray-500 text-sm">Prix unitaire</label>
                        <p class="text-2xl font-bold text-primary">{{ number_format($achat->prix_achat, 0, ',', ' ') }} CFA/kg</p>
                    </div>
                    <div class="text-center">
                        <label class="text-gray-500 text-sm">Montant total</label>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</p>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            @if($achat->notes)
            <div class="mb-6">
                <label class="text-gray-500 text-sm">Notes</label>
                <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $achat->notes }}</p>
            </div>
            @endif
            
            <!-- Boutons d'action -->
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.achats.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour
                </a>
                <div class="space-x-3">
                    <a href="{{ route('admin.achats.edit', $achat) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <form action="{{ route('admin.bordereaux.generer-achat', $achat->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            <i class="fas fa-file-alt mr-2"></i>Générer bordereau
                        </button>
                    </form>
                    <form action="{{ route('admin.achats.destroy', $achat) }}" method="POST" class="inline delete-confirm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection