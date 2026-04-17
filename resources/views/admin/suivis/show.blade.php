@extends('layouts.admin')

@section('title', 'Détails du suivi')
@section('header', 'Fiche de suivi parcellaire')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Suivi parcellaire</h3>
                    <p class="text-white/80 text-sm">N° {{ $suivi->code_suivi }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">{{ $suivi->date_suivi->format('d/m/Y') }}</p>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        {{ $suivi->sante_cultures }}
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
                        <label class="text-gray-500 text-sm">Code suivi</label>
                        <p class="font-semibold">{{ $suivi->code_suivi }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Date du suivi</label>
                        <p>{{ $suivi->date_suivi->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Producteur</label>
                        <p class="font-semibold">{{ $suivi->producteur->nom_complet }}</p>
                        <p class="text-xs text-gray-500">{{ $suivi->producteur->code_producteur }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Animateur</label>
                        <p>{{ $suivi->animateur->nom_complet }}</p>
                        <p class="text-xs text-gray-500">{{ $suivi->animateur->zone_responsabilite }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Informations parcelle -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Informations parcelle</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Superficie actuelle</label>
                        <p class="font-semibold">{{ number_format($suivi->superficie_actuelle, 2) }} hectares</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Hauteur des plantes</label>
                        <p>{{ $suivi->hauteur_plantes ? number_format($suivi->hauteur_plantes) . ' cm' : 'Non mesurée' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Stade de croissance</label>
                        <p>
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ $suivi->stade_croissance }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Taux de levée</label>
                        <p>{{ $suivi->taux_levée ? $suivi->taux_levée . '%' : 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Santé des cultures -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Santé des cultures</h4>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="text-sm text-gray-500">État général :</span>
                        <span class="px-3 py-1 text-sm rounded-full 
                            @if($suivi->sante_cultures == 'excellente') bg-green-100 text-green-800
                            @elseif($suivi->sante_cultures == 'bonne') bg-blue-100 text-blue-800
                            @elseif($suivi->sante_cultures == 'moyenne') bg-yellow-100 text-yellow-800
                            @elseif($suivi->sante_cultures == 'mauvaise') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($suivi->sante_cultures) }}
                        </span>
                    </div>
                    
                    @if($suivi->problemes_constates)
                    <div class="mt-3">
                        <label class="text-gray-500 text-sm">Problèmes constatés</label>
                        <p class="mt-1 p-3 bg-white rounded-lg">{{ $suivi->problemes_constates }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Recommandations et actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                @if($suivi->recommandations)
                <div>
                    <h4 class="text-lg font-semibold text-dark mb-3">Recommandations</h4>
                    <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                        <p>{{ $suivi->recommandations }}</p>
                    </div>
                </div>
                @endif
                
                @if($suivi->actions_prises)
                <div>
                    <h4 class="text-lg font-semibold text-dark mb-3">Actions prises</h4>
                    <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-500">
                        <p>{{ $suivi->actions_prises }}</p>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Distribution associée -->
            @if($suivi->distributionSemence)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Distribution associée</h4>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 text-sm">Code distribution</label>
                            <p class="font-semibold">{{ $suivi->distributionSemence->code_distribution }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Semence</label>
                            <p>{{ $suivi->distributionSemence->semence->nom }} ({{ $suivi->distributionSemence->semence->variete }})</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Quantité distribuée</label>
                            <p>{{ number_format($suivi->distributionSemence->quantite) }} {{ $suivi->distributionSemence->semence->unite }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Saison</label>
                            <p>{{ $suivi->distributionSemence->saison }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Observations -->
            @if($suivi->observations)
            <div class="mb-6">
                <label class="text-gray-500 text-sm">Observations</label>
                <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $suivi->observations }}</p>
            </div>
            @endif
            
            <!-- Boutons d'action -->
            <div class="mt-6 pt-6 border-t flex justify-between">
                <a href="{{ route('admin.suivi.liste') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i>Retour à la liste
                </a>
                <div class="space-x-3">
                    <!-- <a href="{{ route('admin.suivi.edit', $suivi) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a> -->
                    <form action="{{ route('admin.suivi.destroy', $suivi) }}" method="POST" class="inline delete-confirm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.suivi.print', $suivi) }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </a>
                    <a href="{{ route('admin.suivi.edit', $suivi) }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection