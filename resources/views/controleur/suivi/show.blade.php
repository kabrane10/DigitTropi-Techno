@extends('layouts.controleur')

@section('title', 'Détails suivi')
@section('header', 'Fiche de suivi parcellaire')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">Suivi #{{ $suivi->code_suivi }}</h3>
                    <p class="text-white/80 text-sm">{{ $suivi->date_suivi->format('d/m/Y') }}</p>
                </div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                    {{ $suivi->sante_cultures }}
                </span>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Informations producteur -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">👨‍🌾 Producteur</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Nom complet</label>
                        <p class="font-semibold">{{ $suivi->producteur->nom_complet }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Code producteur</label>
                        <p>{{ $suivi->producteur->code_producteur }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Contact</label>
                        <p>{{ $suivi->producteur->contact }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Région</label>
                        <p>{{ $suivi->producteur->region }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Informations animateur -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">👨‍🏫 Animateur</h4>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 text-sm">Nom complet</label>
                            <p class="font-semibold">{{ $suivi->animateur->nom_complet }}</p>
                        </div>
                        <div>
                            <label class="text-gray-500 text-sm">Zone responsabilité</label>
                            <p>{{ $suivi->animateur->zone_responsabilite }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Données techniques -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-dark mb-3">Données techniques</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <label class="text-gray-500 text-sm">Superficie actuelle</label>
                        <p class="font-semibold">{{ number_format($suivi->superficie_actuelle, 2) }} hectares</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Hauteur des plantes</label>
                        <p>{{ $suivi->hauteur_plantes ? number_format($suivi->hauteur_plantes) . ' cm' : 'Non mesuré' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Stade de croissance</label>
                        <p>{{ $suivi->stade_croissance }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500 text-sm">Taux de levée</label>
                        <p>{{ $suivi->taux_levée ? $suivi->taux_levée . '%' : 'Non évalué' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Problèmes constatés -->
            @if($suivi->problemes_constates)
            <div class="mb-6 p-4 bg-red-50 rounded-lg">
                <h4 class="font-semibold text-red-700 mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Problèmes constatés</h4>
                <p class="text-red-600">{{ $suivi->problemes_constates }}</p>
            </div>
            @endif
            
            <!-- Recommandations -->
            @if($suivi->recommandations)
            <div class="mb-6 p-4 bg-green-50 rounded-lg">
                <h4 class="font-semibold text-green-700 mb-2"><i class="fas fa-lightbulb mr-2"></i>Recommandations</h4>
                <p class="text-green-600">{{ $suivi->recommandations }}</p>
            </div>
            @endif
            
            <!-- Actions prises -->
            @if($suivi->actions_prises)
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <h4 class="font-semibold text-blue-700 mb-2"><i class="fas fa-check-circle mr-2"></i>Actions prises</h4>
                <p class="text-blue-600">{{ $suivi->actions_prises }}</p>
            </div>
            @endif
            
            <div class="mt-6 pt-4 border-t">
                <a href="{{ route('controleur.suivi.index') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>
@endsection