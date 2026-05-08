@extends('layouts.admin')

@section('title', 'Détails distribution')
@section('header', 'Fiche de distribution de semences')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Distribution #{{ $distribution->code_distribution }}</h3>
                    <p class="text-white/80 text-sm">{{ $distribution->date_distribution->format('d/m/Y') }}</p>
                </div>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        {{ $distribution->saison }}
                    </span>
                    <button onclick="window.print()" class="px-3 py-1 bg-white/20 rounded-full text-white text-sm hover:bg-white/30 transition">
                        <i class="fas fa-print mr-1"></i> Imprimer
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-6" id="print-content">
            <!-- Infos producteur -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-user text-primary mr-2"></i>Producteur
                </h4>
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Nom complet</label>
                        <p class="font-semibold">{{ $distribution->producteur->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Code producteur</label>
                        <p>{{ $distribution->producteur->code_producteur }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Contact</label>
                        <p>{{ $distribution->producteur->contact }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Région</label>
                        <p>{{ $distribution->producteur->region }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Infos semences -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-seedling text-primary mr-2"></i>Semences distribuées
                </h4>
                <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Semence</label>
                        <p class="font-semibold">{{ $distribution->semence->nom }} ({{ $distribution->semence->variete }})</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Quantité</label>
                        <p class="font-semibold text-primary">{{ number_format($distribution->quantite) }} {{ $distribution->semence->unite }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Superficie emblavée</label>
                        <p>{{ number_format($distribution->superficie_emblevee, 2) }} hectares</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Rendement estimé</label>
                        <p>
                            @if($distribution->rendement_estime)
                                <span class="font-semibold text-blue-600">{{ number_format($distribution->rendement_estime) }} kg/ha</span>
                            @else
                                <span class="text-gray-400">Non estimé</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Production totale estimée -->
                @if($distribution->rendement_estime)
                <div class="mt-3 p-3 bg-green-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <p class="text-sm font-semibold text-dark">
                            <i class="fas fa-chart-line text-primary mr-1"></i>Production totale estimée :
                        </p>
                        <p class="text-lg font-bold text-primary">
                            {{ number_format($distribution->superficie_emblevee * $distribution->rendement_estime) }} kg
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Superficie × Rendement estimé</p>
                </div>
                @endif
            </div>
            
            <!-- Crédit associé -->
            @if($distribution->credit)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">
                    <i class="fas fa-hand-holding-usd text-primary mr-2"></i>Crédit associé
                </h4>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 text-sm">Code crédit</label>
                            <p>{{ $distribution->credit->code_credit }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Montant total</label>
                            <p>{{ number_format($distribution->credit->montant_total, 0, ',', ' ') }} CFA</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Reste à payer</label>
                            <p class="text-orange-600">{{ number_format($distribution->credit->montant_restant, 0, ',', ' ') }} CFA</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Statut</label>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($distribution->credit->statut == 'actif') bg-yellow-100 text-yellow-800
                                @elseif($distribution->credit->statut == 'rembourse') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $distribution->credit->statut }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Observations -->
            @if($distribution->observations)
            <div class="mb-6">
                <label class="text-gray-500 text-sm">Observations</label>
                <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $distribution->observations }}</p>
            </div>
            @endif
            
            <!-- Signatures pour impression -->
            <div class="mt-6 pt-6 border-t print-signatures">
                <div class="grid grid-cols-2 gap-8">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">Signature du producteur</p>
                        <div class="border-t border-gray-300 pt-2 mt-8">
                            <p class="text-xs text-gray-400">{{ $distribution->producteur->nom_complet }}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-2">Signature de l'agent</p>
                        <div class="border-t border-gray-300 pt-2 mt-8">
                            <p class="text-xs text-gray-400">Tropi-Techno Sarl</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between no-print">
                <a href="{{ route('admin.distributions.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour
                </a>
                <div class="space-x-3">
                     <a href="{{ route('admin.distributions.print', $distribution) }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-print mr-2"></i>Imprimer la fiche
                    </a>
                    <a href="{{ route('admin.distributions.edit', $distribution) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    <form action="{{ route('admin.distributions.destroy', $distribution) }}" method="POST" class="inline delete-confirm">
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
        .bg-gray-50, .bg-blue-50, .bg-green-50 {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection