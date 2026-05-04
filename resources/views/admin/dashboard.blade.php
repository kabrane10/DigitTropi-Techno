@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('header', 'Tableau de bord')

@section('content')

@include('admin.partials.greeting')

{{-- ----- DÉBUT DE LA CORRECTION ROBUSTE ----- --}}
@isset($last_backup_date)
    @php
        // Calcule l'âge de la sauvegarde en heures, si la date existe
        $backupAge = $last_backup_date ? round((time() - $last_backup_date) / 3600, 1) : null;
        
        // Détermine le statut en fonction de l'âge de la sauvegarde
        $backupStatus = ($backupAge !== null && $backupAge <= 30) ? 'ok' : 'warning';
    @endphp

    <!-- Carte Dernière sauvegarde -->
    <div class="bg-white rounded-xl shadow-sm mb-6 p-6 border-l-4 {{ $backupStatus == 'ok' ? 'border-green-500' : 'border-yellow-500' }}">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Dernière sauvegarde</p>
                <p class="text-2xl font-bold">
                    @if($last_backup_date)
                        {{ date('d/m/Y H:i', $last_backup_date) }}
                    @else
                        Jamais
                    @endif
                </p>
                @if($backupAge !== null)
                    <p class="text-xs {{ $backupStatus == 'ok' ? 'text-green-600' : 'text-yellow-600' }} mt-1">
                        <i class="fas fa-clock mr-1"></i>Il y a {{ $backupAge }} heures
                    </p>
                @endif
            </div>
            <i class="fas fa-database {{ $backupStatus == 'ok' ? 'text-green-500' : 'text-yellow-500' }} text-3xl opacity-50"></i>
        </div>
    </div>
@endisset
{{-- ----- FIN DE LA CORRECTION ROBUSTE ----- --}}


<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- ... Le reste de votre code reste inchangé ... --}}
</div>

@endsection
