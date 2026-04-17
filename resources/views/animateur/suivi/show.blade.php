@extends('layouts.animateur')

@section('title', 'Détails suivi')
@section('header', 'Fiche de suivi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4"><div class="flex justify-between items-center"><div><h3 class="text-white text-xl font-semibold">Suivi #{{ $suivi->code_suivi }}</h3><p class="text-white/80 text-sm">{{ $suivi->date_suivi->format('d/m/Y') }}</p></div><span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">{{ $suivi->sante_cultures }}</span></div></div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6"><div><label class="text-gray-500 text-sm">Producteur</label><p class="font-semibold">{{ $suivi->producteur->nom_complet }}</p></div><div><label class="text-gray-500 text-sm">Superficie actuelle</label><p>{{ number_format($suivi->superficie_actuelle,2) }} ha</p></div><div><label class="text-gray-500 text-sm">Hauteur des plantes</label><p>{{ $suivi->hauteur_plantes ? number_format($suivi->hauteur_plantes).' cm' : 'Non mesuré' }}</p></div><div><label class="text-gray-500 text-sm">Stade de croissance</label><p>{{ $suivi->stade_croissance }}</p></div><div><label class="text-gray-500 text-sm">Taux de levée</label><p>{{ $suivi->taux_levée ? $suivi->taux_levée.'%' : 'Non évalué' }}</p></div></div>
            @if($suivi->problemes_constates)<div class="mb-4 p-3 bg-red-50 rounded-lg"><label class="text-red-700 text-sm font-semibold">Problèmes constatés</label><p class="text-red-600">{{ $suivi->problemes_constates }}</p></div>@endif
            @if($suivi->recommandations)<div class="mb-4 p-3 bg-green-50 rounded-lg"><label class="text-green-700 text-sm font-semibold">Recommandations</label><p class="text-green-600">{{ $suivi->recommandations }}</p></div>@endif
        </div>
    </div>
</div>
@endsection