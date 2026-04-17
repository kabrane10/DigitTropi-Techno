@extends('layouts.admin')

@section('title', 'Détails bordereau')
@section('header', $bordereau->type_label)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <!-- En-tête -->
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-4">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-white text-xl font-semibold">{{ $bordereau->type_label }}</h3>
                    <p class="text-white/80 text-sm">N° {{ $bordereau->code_bordereau }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/80 text-sm">Émis le {{ $bordereau->date_emission->format('d/m/Y H:i') }}</p>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-white text-sm">
                        {{ $bordereau->statut_label }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            <!-- Différents affichages selon le type -->
            @if($bordereau->type == 'collecte')
                @include('admin.bordereaux.partials.collecte', ['contenu' => $bordereau->contenu])
            @elseif($bordereau->type == 'achat')
                @include('admin.bordereaux.partials.achat', ['contenu' => $bordereau->contenu])
            @elseif($bordereau->type == 'chargement')
                @include('admin.bordereaux.partials.chargement', ['contenu' => $bordereau->contenu])
            @elseif($bordereau->type == 'livraison')
                @include('admin.bordereaux.partials.livraison', ['contenu' => $bordereau->contenu])
            @endif
            
            <!-- Pied de page -->
            <div class="mt-6 pt-6 border-t flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-500">Émetteur : {{ $bordereau->emetteur }}</p>
                    <p class="text-sm text-gray-500">Destinataire : {{ $bordereau->destinataire ?? '-' }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.bordereaux.print', $bordereau) }}" target="_blank" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-print mr-2"></i>Imprimer
                    </a>
                    <a href="{{ route('admin.bordereaux.index') }}" class="border px-4 py-2 rounded-lg hover:bg-gray-50">
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection