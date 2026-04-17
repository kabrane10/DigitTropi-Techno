@extends('layouts.controleur')

@section('title', 'Détails rapport')
@section('header', 'Rapport détaillé')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold">Rapport détaillé</h2>
        <a href="{{ route('controleur.rapports.export-pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            <i class="fas fa-file-pdf mr-2"></i>Exporter PDF
        </a>
    </div>
    
    <!-- Section Producteurs -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-primary mb-4">📊 Producteurs</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_producteurs']) }}</p>
                <p class="text-sm text-gray-600">Total producteurs</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['producteurs_actifs'] ?? 0) }}</p>
                <p class="text-sm text-gray-600">Producteurs actifs</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['superficie_totale'] ?? 0, 2) }} ha</p>
                <p class="text-sm text-gray-600">Superficie totale</p>
            </div>
        </div>
    </div>
    
    <!-- Section Crédits -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-primary mb-4">💰 Crédits</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</p>
                <p class="text-sm text-gray-600">Total crédits</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</p>
                <p class="text-sm text-gray-600">Crédits actifs</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['taux_remboursement'], 1) }}%</p>
                <p class="text-sm text-gray-600">Taux remboursement</p>
            </div>
        </div>
    </div>
    
    <!-- Section Collectes -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-primary mb-4">🚚 Collectes</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_collectes']) }} kg</p>
                <p class="text-sm text-gray-600">Collectes totales</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['valeur_collectes'], 0, ',', ' ') }} CFA</p>
                <p class="text-sm text-gray-600">Valeur totale</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['collectes_mois'] ?? 0) }} kg</p>
                <p class="text-sm text-gray-600">Collectes du mois</p>
            </div>
        </div>
    </div>
    
    <!-- Suivis -->
    <div class="mb-8">
        <h3 class="text-lg font-semibold text-primary mb-4">📋 Suivis terrain</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-indigo-50 rounded-lg p-4 text-center">
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['suivis_mois'] ?? 0) }}</p>
                <p class="text-sm text-gray-600">Suivis ce mois</p>
            </div>
        </div>
    </div>
    
    <div class="mt-6 pt-4 border-t">
        <a href="{{ route('controleur.rapports.index') }}" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-1"></i> Retour aux rapports
        </a>
    </div>
</div>
@endsection