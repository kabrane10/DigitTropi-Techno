@extends('layouts.admin')

@section('title', 'Dashboard collectes')
@section('header', 'Tableau de bord des collectes')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total collecté</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_collecte']) }} kg</p>
            </div>
            <i class="fas fa-weight-hanging text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Valeur totale</p>
                <p class="text-3xl font-bold">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-money-bill-wave text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Collecte du mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['collecte_mois']) }} kg</p>
            </div>
            <i class="fas fa-calendar-alt text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Valeur du mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['valeur_mois'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-chart-line text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Collectes par produit -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Collectes par produit</h3>
        <div class="space-y-3">
            @foreach($collectes_par_produit as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">{{ $item->produit }}</span>
                    <span class="text-sm text-gray-500">{{ number_format($item->total) }} kg</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $max = $collectes_par_produit->max('total');
                        $pourcentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Dernières collectes -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Dernières collectes</h3>
        <div class="space-y-3">
            @foreach($collectes_recentes as $collecte)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $collecte->producteur->nom_complet }}</p>
                    <p class="text-sm text-gray-500">{{ $collecte->produit }} - {{ number_format($collecte->quantite_nette) }} kg</p>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-primary">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                    <p class="text-xs text-gray-500">{{ $collecte->date_collecte->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection