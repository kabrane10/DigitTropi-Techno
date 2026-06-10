@extends('layouts.admin')

@section('title', 'Ajouter une Zone de Stockage')
@section('header', ' Ajouter une zone')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.zones.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-warehouse text-primary mr-2"></i>Configuration de la zone
                </h3>
            </div>
            
            {{-- Nom de la Zone --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Nom de la Zone de Stockage *
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
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
                Assurez-vous de donner un nom explicite à la zone pour faciliter la répartition et la cartographie des lots d'intrants par les coopératives.
            </p>
        </div>
        
        {{-- Boutons d'action alignés à droite --}}
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.zones.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition flex items-center text-sm font-semibold shadow-sm">
                <i class="fas fa-save mr-2"></i> Enregistrer la zone
            </button>
        </div>
    </form>
</div>
@endsection