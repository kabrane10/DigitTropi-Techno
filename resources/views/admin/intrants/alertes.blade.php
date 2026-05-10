@extends('layouts.admin')

@section('title', 'Alertes stock')
@section('header', '⚠️ Stocks critiques')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
        <div>
            <h2 class="text-xl font-semibold">Intrants en stock critique</h2>
            <p class="text-sm text-gray-500 mt-1">Intrants dont le stock est inférieur ou égal au seuil d'alerte</p>
        </div>
        <a href="{{ route('admin.intrants.index') }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
        </a>
    </div>
    
    @if($stocksCritiques->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($stocksCritiques as $stock)
        <div class="border-2 border-red-200 rounded-xl overflow-hidden bg-red-50">
            <div class="bg-red-500 px-4 py-2">
                <h3 class="text-white font-semibold">{{ $stock->intrant->nom }}</h3>
                <p class="text-white/80 text-sm">{{ $stock->zone }}</p>
            </div>
            <div class="p-4">
                <div class="text-center mb-4">
                    <p class="text-sm text-gray-500">Stock actuel</p>
                    <p class="text-3xl font-bold text-red-600">{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</p>
                </div>
                <div class="flex justify-between text-sm mb-4">
                    <span class="text-gray-600">Seuil alerte:</span>
                    <span class="font-semibold">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    @php
                        $pourcentage = ($stock->stock_actuel / $stock->seuil_alerte) * 100;
                        $pourcentage = min($pourcentage, 100);
                    @endphp
                    <div class="bg-red-500 h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.intrants.stock', ['intrant' => $stock->intrant_id, 'zone' => $stock->zone]) }}" 
                       class="flex-1 text-center bg-primary text-white px-3 py-2 rounded-lg text-sm hover:bg-secondary">
                        <i class="fas fa-chart-line mr-1"></i>Gérer
                    </a>
                    <a href="{{ route('admin.intrants.edit', $stock->intrant_id) }}" 
                       class="flex-1 text-center border border-primary text-primary px-3 py-2 rounded-lg text-sm hover:bg-primary hover:text-white transition">
                        <i class="fas fa-edit mr-1"></i>Modifier
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check-circle text-green-500 text-3xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun stock critique</h3>
        <p class="text-gray-500">Tous les intrants sont au-dessus de leur seuil d'alerte</p>
    </div>
    @endif
</div>
@endsection