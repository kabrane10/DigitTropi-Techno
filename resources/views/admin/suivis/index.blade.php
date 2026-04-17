@extends('layouts.admin')

@section('title', 'Suivi des exploitations')
@section('header', 'Tableau de bord - Suivi terrain')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Producteurs</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_producteurs']) }}</p>
            </div>
            <i class="fas fa-users text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Producteurs suivis</p>
                <p class="text-3xl font-bold">{{ number_format($stats['producteurs_avec_suivi']) }}</p>
            </div>
            <i class="fas fa-clipboard-list text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Taux de couverture</p>
                <p class="text-3xl font-bold">{{ number_format($stats['taux_couverture'], 1) }}%</p>
            </div>
            <i class="fas fa-chart-line text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Suivis ce mois</p>
                <p class="text-3xl font-bold">{{ number_format($stats['suivis_mois']) }}</p>
            </div>
            <i class="fas fa-calendar-alt text-green-500 text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Derniers suivis -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Derniers suivis terrain</h3>
        <div class="space-y-3">
            @foreach($suivis_recents as $suivi)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $suivi->producteur->nom_complet }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $suivi->date_suivi->format('d/m/Y') }} - {{ $suivi->animateur->nom_complet }}
                    </p>
                </div>
                <div class="text-right">
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($suivi->sante_cultures == 'excellente') bg-green-100 text-green-800
                        @elseif($suivi->sante_cultures == 'bonne') bg-blue-100 text-blue-800
                        @elseif($suivi->sante_cultures == 'moyenne') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $suivi->sante_cultures }}
                    </span>
                    <a href="{{ route('admin.suivi.show', $suivi) }}" class="ml-2 text-primary">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('admin.suivi.liste') }}" class="text-primary hover:underline">Voir tous les suivis →</a>
        </div>
    </div>
    
    <!-- Santé des cultures -->
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">État de santé des cultures</h3>
        <div class="space-y-3">
            @foreach($sante_cultures as $item)
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-medium">
                        @if($item->sante_cultures == 'excellente') 🌟 Excellente
                        @elseif($item->sante_cultures == 'bonne') ✅ Bonne
                        @elseif($item->sante_cultures == 'moyenne') ⚠️ Moyenne
                        @elseif($item->sante_cultures == 'mauvaise') ❌ Mauvaise
                        @else 🔴 Critique
                        @endif
                    </span>
                    <span class="text-sm text-gray-500">{{ $item->total }} suivis</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    @php
                        $max = $sante_cultures->max('total');
                        $pourcentage = $max > 0 ? ($item->total / $max) * 100 : 0;
                    @endphp
                    <div class="bg-primary h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Producteurs sans suivi -->
<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="text-lg font-semibold mb-4">Producteurs sans suivi récent</h3>
    @if($producteurs_sans_suivi->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($producteurs_sans_suivi as $producteur)
        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
            <div>
                <p class="font-medium">{{ $producteur->nom_complet }}</p>
                <p class="text-xs text-gray-500">{{ $producteur->region }} - {{ $producteur->culture_pratiquee }}</p>
            </div>
            <a href="{{ route('admin.suivi.create', ['producteur_id' => $producteur->id]) }}" class="bg-primary text-white px-3 py-1 rounded text-sm">
                <i class="fas fa-plus mr-1"></i>Ajouter suivi
            </a>
        </div>
        @endforeach
    </div>
    @else
    <p class="text-green-600 text-center">✅ Tous les producteurs ont des suivis récents !</p>
    @endif
</div>

<div class="mt-6 flex justify-end">
    <a href="{{ route('admin.suivi.create') }}" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
        <i class="fas fa-plus mr-2"></i>Nouveau suivi terrain
    </a>
</div>
@endsection