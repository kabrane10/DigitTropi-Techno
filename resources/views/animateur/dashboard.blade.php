@extends('layouts.animateur')

@section('title', 'Dashboard')
@section('header', 'Tableau de bord')

@section('content')

@include('admin.partials.greeting')

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Agents supervisés</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['total_agents']) }}</p>
                <p class="text-xs text-green-600 mt-1">{{ number_format($stats['agents_actifs']) }} actifs</p>
            </div>
            <i class="fas fa-users text-primary text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Producteurs suivis</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['total_producteurs']) }}</p>
                <p class="text-xs text-green-600 mt-1">{{ number_format($stats['producteurs_actifs']) }} actifs</p>
            </div>
            <i class="fas fa-tractor text-secondary text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Superficie totale</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['total_superficie'], 2) }} ha</p>
            </div>
            <i class="fas fa-map-marked-alt text-accent text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Collectes totales</p>
                <p class="text-2xl sm:text-3xl font-bold">{{ number_format($stats['total_collectes']) }} kg</p>
            </div>
            <i class="fas fa-truck-loading text-green-500 text-2xl sm:text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <h3 class="text-lg font-semibold mb-4">Performance des agents</h3>
        <div class="space-y-3">
            @foreach($performanceAgents as $agent)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg flex-wrap gap-2">
                <div><p class="font-medium">{{ $agent->nom_complet }}</p><p class="text-xs text-gray-500">{{ $agent->zone_affectation }}</p></div>
                <div class="text-right"><p class="font-semibold text-primary">{{ number_format($agent->producteurs_count) }} producteurs</p></div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <h3 class="text-lg font-semibold mb-4">Derniers producteurs inscrits</h3>
        <div class="space-y-3">
            @foreach($derniersProducteurs as $producteur)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg flex-wrap gap-2">
                <div><p class="font-medium">{{ $producteur->nom_complet }}</p><p class="text-xs text-gray-500">{{ $producteur->code_producteur }}</p></div>
                <div class="text-right"><p class="text-xs text-gray-500">{{ $producteur->created_at->format('d/m/Y') }}</p></div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection