@extends('layouts.admin')

@section('title', 'Modifier la Zone de Stockage')
@section('header', ' Modifier la zone')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.zones.update', $zone->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-edit text-primary mr-2"></i>Modification de la zone : {{ $zone->name }}
                </h3>
            </div>
            
            {{-- Nom de la Zone (Prend toute la largeur) --}}
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Nom de la Zone de Stockage *
                </label>
                <input type="text" name="name" required value="{{ old('name', $zone->name) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary @error('name') border-red-500 @enderror"
                       placeholder="Ex: Zone Centrale, Entrepôt Nord...">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        {{-- Encadré d'information contextuel --}}
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Information
            </h4>
            <p class="text-sm text-blue-700">
                La modification du nom de la zone sera immédiatement répercutée sur les fiches de stocks et les alertes d'intrants associées à cet emplacement.
            </p>
        </div>
        
        {{-- Boutons d'action --}}
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.zones.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition flex items-center">
                <i class="fas fa-save mr-2"></i> Mettre à jour la zone
            </button>
        </div>
    </form>
</div>
@endsection
