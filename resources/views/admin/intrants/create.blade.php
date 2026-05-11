@extends('layouts.admin')

@section('title', 'Nouvel intrant')
@section('header', '➕ Ajouter un intrant')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.intrants.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations générales -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-info-circle text-primary mr-2"></i>Informations générales
                </h3>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-tag text-primary mr-1"></i> Nom de l'intrant *
                </label>
                <input type="text" name="nom" required value="{{ old('nom') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Engrais NPK 15-15-15">
                @error('nom')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-folder text-primary mr-1"></i> Type *
                </label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez --</option>
                    <option value="engrais" {{ old('type') == 'engrais' ? 'selected' : '' }}>🌱 Engrais</option>
                    <option value="pesticide" {{ old('type') == 'pesticide' ? 'selected' : '' }}>🐛 Pesticide</option>
                    <option value="herbicide" {{ old('type') == 'herbicide' ? 'selected' : '' }}>🌿 Herbicide</option>
                    <option value="semence" {{ old('type') == 'semence' ? 'selected' : '' }}>🌾 Semence</option>
                    <option value="autre" {{ old('type') == 'autre' ? 'selected' : '' }}>📦 Autre</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-ruler text-primary mr-1"></i> Unité *
                </label>
                <select name="unite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="kg" {{ old('unite') == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                    <option value="litre" {{ old('unite') == 'litre' ? 'selected' : '' }}>Litre (L)</option>
                    <option value="sac" {{ old('unite') == 'sac' ? 'selected' : '' }}>Sac</option>
                    <option value="bol" {{ old('unite') == 'bol' ? 'selected' : '' }}>Bol</option>
                </select>
                @error('unite')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Prix unitaire (CFA) *
                </label>
                <input type="number" step="1" name="prix_unitaire" required value="{{ old('prix_unitaire') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 5000">
                @error('prix_unitaire')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-align-left text-primary mr-1"></i> Description
                </label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Description de l'intrant...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Configuration des stocks par zone -->
            <div class="md:col-span-2 mt-4">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>Configuration des stocks par zone
                </h3>
                <p class="text-sm text-gray-500 mb-4">Définissez les paramètres initiaux pour chaque zone.</p>
            </div>
            
            @php
                $zones = ['Centrale', 'Kara', 'Savanes'];
            @endphp
            
            @foreach($zones as $zone)
            <div class="border rounded-lg p-4 bg-gray-50">
                <h4 class="font-semibold text-dark mb-3 flex items-center">
                    <i class="fas fa-location-dot text-primary mr-2"></i>
                    Zone {{ $zone }}
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Stock initial ({{ old('unite', 'kg') }})
                        </label>
                        <input type="number" step="0.01" name="stocks[{{ $zone }}][initial]" value="0"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Seuil d'alerte ({{ old('unite', 'kg') }})
                        </label>
                        <input type="number" step="0.01" name="stocks[{{ $zone }}][seuil_alerte]" value="100"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <p class="text-xs text-gray-500 mt-1">En dessous de ce seuil, une alerte sera déclenchée</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-2">
                            Emplacement physique
                        </label>
                        <input type="text" name="stocks[{{ $zone }}][emplacement]" 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                               placeholder="Ex: Entrepôt Nord, Allée B, Étagère 3">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>Information
            </h4>
            <p class="text-sm text-blue-700">Le stock initial sera ajouté automatiquement. Vous pourrez ajuster les seuils d'alerte et les emplacements ultérieurement.</p>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.intrants.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i> Créer l'intrant
            </button>
        </div>
    </form>
</div>
@endsection