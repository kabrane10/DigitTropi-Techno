@extends('layouts.admin')

@section('title', 'Modifier intrant')
@section('header', 'Modifier un intrant')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.intrants.update', $intrant) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code intrant (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code intrant
                </label>
                <input type="text" value="{{ $intrant->code_intrant }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Nom -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-tag text-primary mr-1"></i> Nom de l'intrant *
                </label>
                <input type="text" name="nom" required value="{{ old('nom', $intrant->nom) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                @error('nom')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Type -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-folder text-primary mr-1"></i> Type *
                </label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="engrais" {{ $intrant->type == 'engrais' ? 'selected' : '' }}> Engrais</option>
                    <option value="pesticide" {{ $intrant->type == 'pesticide' ? 'selected' : '' }}> Pesticide</option>
                    <option value="herbicide" {{ $intrant->type == 'herbicide' ? 'selected' : '' }}> Herbicide</option>
                    <option value="semence" {{ $intrant->type == 'semence' ? 'selected' : '' }}> Semence</option>
                    <option value="autre" {{ $intrant->type == 'autre' ? 'selected' : '' }}> Autre</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Unité -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-ruler text-primary mr-1"></i> Unité *
                </label>
                <select name="unite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="kg" {{ $intrant->unite == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                    <option value="litre" {{ $intrant->unite == 'litre' ? 'selected' : '' }}>Litre (L)</option>
                    <option value="sac" {{ $intrant->unite == 'sac' ? 'selected' : '' }}>Sac</option>
                    <option value="botte" {{ $intrant->unite == 'bol' ? 'selected' : '' }}>Bol</option>
                </select>
                @error('unite')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Prix unitaire -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Prix unitaire (CFA) *
                </label>
                <input type="number" step="1" name="prix_unitaire" required value="{{ old('prix_unitaire', $intrant->prix_unitaire) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Prix par {{ $intrant->unite }}</p>
                @error('prix_unitaire')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-toggle-on text-primary mr-1"></i> Statut
                </label>
                <select name="est_actif" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="1" {{ $intrant->est_actif ? 'selected' : '' }}> Actif</option>
                    <option value="0" {{ !$intrant->est_actif ? 'selected' : '' }}> Inactif</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Les intrants inactifs ne seront pas disponibles pour les distributions</p>
                @error('est_actif')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Description -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-align-left text-primary mr-1"></i> Description
                </label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Description de l'intrant, informations complémentaires...">{{ old('description', $intrant->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <!-- Information sur les stocks -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-1"></i> Gestion des stocks
            </h4>
            <p class="text-sm text-blue-700">
                La modification de l'unité ou du prix unitaire n'affecte pas les stocks existants.
                Les stocks sont gérés séparément par zone.
            </p>
        </div>
        
        <!-- Aperçu des stocks actuels -->
        <div class="mt-6">
            <h4 class="font-semibold text-dark mb-3"> Stock actuel par zone</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($intrant->stocks as $stock)
                <div class="bg-gray-50 rounded-lg p-3 text-center">
                    <p class="font-semibold">{{ $stock->zone }}</p>
                    <p class="text-xl font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($stock->stock_actuel) }} {{ $stock->unite }}
                    </p>
                    <p class="text-xs text-gray-500">Seuil: {{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</p>
                    <a href="{{ route('admin.intrants.stock', ['intrant' => $intrant->id, 'zone' => $stock->zone]) }}" 
                       class="text-xs text-primary hover:underline mt-1 inline-block">
                        Gérer le stock →
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.intrants.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection