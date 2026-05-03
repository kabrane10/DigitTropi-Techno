@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('header', 'Tableau de bord')

@section('content')

@include('admin.partials.greeting')

@php
    $backupDir = storage_path('app/backups');
    $backupFiles = [];
    
    // Récupérer les fichiers de sauvegarde
    if (is_dir($backupDir)) {
        $backupFiles = glob($backupDir . '/backup-*.sqlite');
    }
    
    // Trouver le fichier le plus récent
    $lastBackup = null;
    $backupAge = null;
    
    if (!empty($backupFiles)) {
        $latestTime = 0;
        foreach ($backupFiles as $file) {
            $fileTime = filemtime($file);
            if ($fileTime > $latestTime) {
                $latestTime = $fileTime;
                $lastBackup = $file;
            }
        }
        
        if ($lastBackup) {
            $backupAge = round((time() - filemtime($lastBackup)) / 3600, 1);
        }
    }
    
    $backupStatus = ($backupAge && $backupAge <= 30) ? 'ok' : 'warning';
@endphp

<!-- Carte Dernière sauvegarde -->
<div class="bg-white rounded-xl shadow-sm mb-6 p-6 border-l-4 {{ $backupStatus == 'ok' ? 'border-green-500' : 'border-yellow-500' }}">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm">Dernière sauvegarde</p>
            <p class="text-2xl font-bold">
                @if($lastBackup && file_exists($lastBackup))
                    {{ date('d/m/Y H:i', filemtime($lastBackup)) }}
                @else
                    Jamais
                @endif
            </p>
            @if($backupAge)
                <p class="text-xs {{ $backupAge <= 30 ? 'text-green-600' : 'text-yellow-600' }} mt-1">
                    <i class="fas fa-clock mr-1"></i>Il y a {{ $backupAge }} heures
                </p>
            @endif
        </div>
        <i class="fas fa-database {{ $backupStatus == 'ok' ? 'text-green-500' : 'text-yellow-500' }} text-3xl opacity-50"></i>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Producteurs</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_producteurs'] ?? 0) }}</p>
            </div>
            <i class="fas fa-users text-primary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-secondary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Actualités</p>
                <p class="text-3xl font-bold">{{ $stats['actualites'] ?? 0 }}</p>
            </div>
            <i class="fas fa-newspaper text-secondary text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-accent">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Agents terrain</p>
                <p class="text-3xl font-bold">{{ $stats['agents'] ?? 0 }}</p>
            </div>
            <i class="fas fa-users text-accent text-3xl opacity-50"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-primary">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Superficie totale</p>
                <p class="text-3xl font-bold">{{ number_format($stats['superficie_totale'] ?? 0, 2) }} ha</p>
            </div>
            <i class="fas fa-map-marked-alt text-primary text-3xl opacity-50"></i>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 mt-6">
    <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-6 text-white">
        <p class="text-sm opacity-90">Crédits actifs</p>
        <p class="text-3xl font-bold">{{ number_format($stats['credits_actifs'] ?? 0, 0, ',', ' ') }} CFA</p>
    </div>
    
    <div class="bg-gradient-to-r from-accent to-orange-500 rounded-xl p-6 text-white">
        <p class="text-sm opacity-90">Collecte du mois</p>
        <p class="text-3xl font-bold">{{ number_format($stats['collecte_mois'] ?? 0, 0, ',', ' ') }} kg</p>
    </div>
    
    <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl p-6 text-white">
        <p class="text-sm opacity-90">Producteurs actifs</p>
        <p class="text-3xl font-bold">{{ number_format($stats['producteurs_actifs'] ?? 0) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Actualités récentes</h3>
        <div class="space-y-3">
            @forelse($actualites_recentes as $actualite)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ Str::limit($actualite->titre, 40) }}</p>
                    <p class="text-xs text-gray-500">{{ $actualite->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('admin.actualites.edit', $actualite) }}" class="text-primary">
                    <i class="fas fa-edit"></i>
                </a>
            </div>
            @empty
            <p class="text-gray-500 text-center">Aucune actualité</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-lg font-semibold mb-4">Derniers producteurs inscrits</h3>
        <div class="space-y-3">
            @forelse($producteurs_recents as $producteur)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $producteur->nom_complet }}</p>
                    <p class="text-xs text-gray-500">{{ $producteur->region }} - {{ $producteur->culture_pratiquee }}</p>
                </div>
                <a href="{{ route('admin.producteurs.show', $producteur) }}" class="text-primary">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
            @empty
            <p class="text-gray-500 text-center">Aucun producteur</p>
            @endforelse
        </div>
    </div>
</div>

@endsection