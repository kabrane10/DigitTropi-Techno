@extends('layouts.animateur')

@section('title', 'Détails producteur')
@section('header', 'Fiche producteur')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1"><div class="bg-white rounded-xl shadow-sm p-6"><div class="text-center mb-4"><div class="w-24 h-24 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3"><i class="fas fa-user-farmer text-primary text-4xl"></i></div><h3 class="text-xl font-bold">{{ $producteur->nom_complet }}</h3><p class="text-gray-500">{{ $producteur->code_producteur }}</p></div><div class="space-y-3"><div class="flex items-center"><i class="fas fa-phone w-8"></i><span>{{ $producteur->contact }}</span></div><div class="flex items-center"><i class="fas fa-map-marker-alt w-8"></i><span>{{ $producteur->localisation }} ({{ $producteur->region }})</span></div><div class="flex items-center"><i class="fas fa-tractor w-8"></i><span>{{ $producteur->culture_pratiquee }}</span></div><div class="flex items-center"><i class="fas fa-chart-line w-8"></i><span>{{ number_format($producteur->superficie_totale,2) }} ha</span></div></div></div></div>
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="text-lg font-semibold mb-4">Crédits</h3>@if($credits->count()>0)@foreach($credits as $c)<div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg mb-2"><div><p class="font-medium">{{ $c->code_credit }}</p><p class="text-sm text-gray-500">{{ number_format($c->montant_total,0,',',' ') }} CFA</p></div><span class="px-2 py-1 text-xs rounded-full {{ $c->statut=='actif'?'bg-yellow-100 text-yellow-800':'bg-green-100 text-green-800' }}">{{ $c->statut }}</span></div>@endforeach@else<p class="text-gray-500 text-center">Aucun crédit</p>@endif</div>
        <div class="bg-white rounded-xl shadow-sm p-6"><h3 class="text-lg font-semibold mb-4">Dernières collectes</h3>@if($collectes->count()>0)@foreach($collectes as $c)<div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg mb-2"><div><p class="font-medium">{{ $c->produit }}</p><p class="text-xs text-gray-500">{{ $c->date_collecte->format('d/m/Y') }}</p></div><div class="text-right"><p class="font-semibold text-primary">{{ number_format($c->quantite_nette) }} kg</p><p class="text-xs text-gray-500">{{ number_format($c->montant_total,0,',',' ') }} CFA</p></div></div>@endforeach@else<p class="text-gray-500 text-center">Aucune collecte</p>@endif</div>
    </div>
</div>
@endsection