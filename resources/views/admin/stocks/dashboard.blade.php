@extends('layouts.admin')

@section('title', 'Dashboard stocks')
@section('header', 'Tableau de bord des stocks')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Produits en stock</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_produits']) }}</p>
            </div>
            <i class="fas fa-boxes text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Stock total (kg)</p>
                <p class="text-3xl font-bold">{{ number_format($stats['stock_total']) }}</p>
            </div>
            <i class="fas fa-weight-hanging text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Alertes stock</p>
                <p class="text-3xl font-bold text-red-600">{{ number_format($stats['alertes']) }}</p>
            </div>
            <i class="fas fa-exclamation-triangle text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Valeur estimée</p>
                <p class="text-3xl font-bold">{{ number_format($stats['valeur_stock'], 0, ',', ' ') }} CFA</p>
            </div>
            <i class="fas fa-money-bill-wave text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Stock par zone -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Stock par zone</h3>
        <div class="space-y-3">
            @foreach($stock_par_zone as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">{{ $item->zone }}</span>
                    <span class="text-sm text-gray-500">{{ number_format($item->total) }} kg</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $max = $stock_par_zone->max('total');
                        $pourcentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Top produits -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Top 10 produits en stock</h3>
        <div class="space-y-3">
            @foreach($top_produits as $produit)
            <div class="flex justify-between items-center p-2 border-b">
                <span class="font-medium">{{ $produit->produit }} ({{ $produit->zone }})</span>
                <span class="font-semibold text-primary">{{ number_format($produit->stock_actuel) }} {{ $produit->unite }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Alertes stock -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">
        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Stocks critiques
    </h3>
    @if($stocks_alerte->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($stocks_alerte as $stock)
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <p class="font-semibold">{{ $stock->produit }}</p>
            <p class="text-sm text-gray-600">Zone: {{ $stock->zone }}</p>
            <div class="flex justify-between items-center mt-2">
                <span class="text-sm">Stock: <strong class="text-red-600">{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</strong></span>
                <span class="text-sm">Seuil: {{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</span>
            </div>
            <div class="mt-2">
                <a href="{{ route('admin.stocks.mouvements', $stock) }}" class="text-primary text-sm hover:underline">
                    Voir détails <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-8 text-green-500">
        <i class="fas fa-check-circle text-5xl mb-2"></i>
        <p>Aucun stock critique - Tous les niveaux sont bons</p>
    </div>
    @endif
</div>
@endsection