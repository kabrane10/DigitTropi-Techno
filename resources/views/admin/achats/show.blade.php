@extends('layouts.admin')

@section('title', 'Détails achat')
@section('header', 'Fiche d\'achat Tropi-Techno')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Achat #{{ $achat->code_achat }}</h3>
                    <p class="text-white/80 text-sm">{{ $achat->date_achat->format('d/m/Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        {{ $achat->statut }}
                    </span>
                    <button onclick="window.print()" class="px-3 py-1 bg-white/20 rounded-full text-white text-sm hover:bg-white/30 transition">
                        <i class="fas fa-print mr-1"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6" id="print-content">
            <!-- Informations générales -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-info-circle text-primary mr-2"></i>Informations générales
                </h4>
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
                        <p class="font-semibold text-primary">{{ $achat->acheteur }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Mode de paiement</label>
                        <p>
                            @if($achat->mode_paiement == 'especes')  Espèces
                            @elseif($achat->mode_paiement == 'virement')  Virement bancaire
                            @elseif($achat->mode_paiement == 'cheque')  Chèque
                            @else  Mobile Money
                            @endif
                        </p>
                    </div>
                    @if($achat->reference_facture)
                    <div>
                        <label class="text-gray-500 text-sm">Référence facture</label>
                        <p class="font-semibold text-blue-600">{{ $achat->reference_facture }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Informations collecte source -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-truck-loading text-primary mr-2"></i>Collecte source
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Code collecte</label>
                        <p class="font-semibold">{{ $achat->collecte->code_collecte }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Date collecte</label>
                        <p>{{ $achat->collecte->date_collecte->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Producteur</label>
                        <p class="font-semibold">{{ $achat->collecte->producteur->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Code producteur</label>
                        <p>{{ $achat->collecte->producteur->code_producteur }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Produit</label>
                        <p class="font-semibold">{{ $achat->collecte->produit }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Quantité collectée</label>
                        <p>{{ number_format($achat->collecte->quantite_nette) }} kg</p>
                    </div>
                </div>
            </div>
            
            <!-- Détails de l'achat -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-shopping-cart text-primary mr-2"></i>Détails de l'achat
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-green-50 rounded-lg">
                    <div class="text-center">
                        <label class="text-gray-500 text-sm">Quantité achetée</label>
                        <p class="text-2xl font-bold text-primary">{{ number_format($achat->quantite) }} kg</p>
                        <p class="text-xs text-gray-500">sur {{ number_format($achat->collecte->quantite_nette) }} kg collectés</p>
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
            
            <!-- Signatures pour impression -->
            <div class="mt-6 pt-6 border-t print-signatures">
                <div class="grid grid-cols-2 gap-8">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">Signature du vendeur</p>
                        <div class="border-t border-gray-300 pt-2 mt-8">
                            <p class="text-xs text-gray-400">{{ $achat->collecte->producteur->nom_complet }}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">Signature de l'acheteur</p>
                        <div class="border-t border-gray-300 pt-2 mt-8">
                            <p class="text-xs text-gray-400">Tropi-Techno Sarl</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between no-print">
                <a href="{{ route('admin.achats.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour
                </a>
                <div class="space-x-3">
                    <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </button>
                    <a href="{{ route('admin.achats.edit', $achat) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    {{-- BOUTON POUR GÉNÉRER LE BORDEREAU D'ACHAT DÉDIÉ --}}
                    <form action="{{ route('admin.achats.print-bordereau', $achat->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600">
                            <i class="fas fa-file-invoice mr-2"></i>Générer bordereau
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

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        .print-signatures {
            display: block !important;
        }
        body {
            padding: 0;
            margin: 0;
        }
        .bg-gradient-to-r {
            background: #2d6a4f !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-gray-50, .bg-green-50 {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection