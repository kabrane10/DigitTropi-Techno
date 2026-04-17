@extends('layouts.admin')

@section('title', 'Détails distribution')
@section('header', 'Fiche de distribution de semences')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Distribution #{{ $distribution->code_distribution }}</h3>
                    <p class="text-white/80 text-sm">{{ $distribution->date_distribution->format('d/m/Y') }}</p>
                </div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                    {{ $distribution->saison }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Infos producteur -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Producteur</h4>
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
                <h4 class="text-lg font-semibold text-dark mb-3">Semences distribuées</h4>
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
                        <p>{{ $distribution->rendement_estime ? number_format($distribution->rendement_estime) . ' kg/ha' : 'Non estimé' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Crédit associé -->
            @if($distribution->credit)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Crédit associé</h4>
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
            
            <!-- Actions -->
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.distributions.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour
                </a>
                <div class="space-x-3">
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
@endsection