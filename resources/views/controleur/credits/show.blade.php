@extends('layouts.controleur')

@section('title', 'Détails crédit')
@section('header', 'Fiche crédit')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div><h3 class="text-white text-xl font-semibold">Crédit #{{ $credit->code_credit }}</h3><p class="text-white/80 text-sm">{{ $credit->date_octroi->format('d/m/Y') }}</p></div>
                <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">{{ $credit->statut }}</span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div><label class="text-gray-500 text-sm">Producteur</label><p class="font-semibold">{{ $credit->producteur->nom_complet }}</p></div>
                <div><label class="text-gray-500 text-sm">Date échéance</label><p>{{ $credit->date_echeance->format('d/m/Y') }}</p></div>
                <div><label class="text-gray-500 text-sm">Montant total</label><p class="text-xl font-bold text-primary">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</p></div>
                <div><label class="text-gray-500 text-sm">Reste à payer</label><p class="text-xl font-bold text-red-600">{{ number_format($resteAPayer, 0, ',', ' ') }} CFA</p></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4"><label class="text-gray-500 text-sm">Déjà remboursé</label><p class="text-xl font-bold text-green-600">{{ number_format($montantRembourse, 0, ',', ' ') }} CFA ({{ number_format($tauxRemboursement, 1) }}%)</p></div>
        </div>
    </div>
</div>
@endsection