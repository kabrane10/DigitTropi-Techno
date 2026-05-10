@extends('layouts.admin')

@section('title', 'Détails intrant')
@section('header', 'Fiche intrant')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Informations générales -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="text-center mb-4">
                <div class="w-20 h-20 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-boxes text-primary text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold">{{ $intrant->nom }}</h3>
                <p class="text-gray-500 text-sm">{{ $intrant->code_intrant }}</p>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center">
                    <i class="fas fa-tag w-8 text-gray-400"></i>
                    <span>{{ $intrant->type_label }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-ruler w-8 text-gray-400"></i>
                    <span>Unité: {{ $intrant->unite }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-money-bill-wave w-8 text-gray-400"></i>
                    <span>Prix: {{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA/{{ $intrant->unite }}</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-chart-line w-8 text-gray-400"></i>
                    <span>Statut: {{ $intrant->est_actif ? 'Actif' : 'Inactif' }}</span>
                </div>
            </div>
            
            @if($intrant->description)
            <div class="mt-4 pt-4 border-t">
                <label class="text-gray-500 text-sm">Description</label>
                <p class="text-sm mt-1">{{ $intrant->description }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Stocks par zone -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold mb-4">📊 Stocks par zone</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($intrant->stocks as $stock)
                <div class="border rounded-lg p-4 {{ $stock->est_critique ? 'border-red-300 bg-red-50' : 'border-green-300 bg-green-50' }}">
                    <div class="flex justify-between items-start mb-3">
                        <h4 class="font-bold text-lg">{{ $stock->zone }}</h4>
                        @if($stock->est_critique)
                        <span class="px-2 py-1 text-xs rounded-full bg-red-500 text-white">⚠️ Stock critique</span>
                        @endif
                    </div>
                    <div class="text-center mb-3">
                        <p class="text-3xl font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($stock->stock_actuel) }}
                        </p>
                        <p class="text-sm text-gray-500">{{ $stock->unite }}</p>
                    </div>
                    <div class="flex justify-between text-sm mb-3">
                        <span>Seuil alerte:</span>
                        <span class="font-semibold">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</span>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.intrants.stock', ['intrant' => $intrant->id, 'zone' => $stock->zone]) }}" 
                           class="flex-1 text-center bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-secondary">
                            <i class="fas fa-chart-line mr-1"></i>Gérer
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection