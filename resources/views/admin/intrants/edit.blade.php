@extends('layouts.admin')

@section('title', 'Modifier intrant')
@section('header', '✏️ Modifier l\'intrant - ' . $intrant->nom)

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.intrants.update', $intrant) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations générales -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-info-circle text-primary mr-2"></i>Informations générales
                </h3>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code intrant
                </label>
                <input type="text" value="{{ $intrant->code_intrant }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
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
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-folder text-primary mr-1"></i> Type *
                </label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="engrais" {{ $intrant->type == 'engrais' ? 'selected' : '' }}>🌱 Engrais</option>
                    <option value="pesticide" {{ $intrant->type == 'pesticide' ? 'selected' : '' }}>🐛 Pesticide</option>
                    <option value="herbicide" {{ $intrant->type == 'herbicide' ? 'selected' : '' }}>🌿 Herbicide</option>
                    <option value="semence" {{ $intrant->type == 'semence' ? 'selected' : '' }}>🌾 Semence</option>
                    <option value="autre" {{ $intrant->type == 'autre' ? 'selected' : '' }}>📦 Autre</option>
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
                    <option value="kg" {{ $intrant->unite == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                    <option value="litre" {{ $intrant->unite == 'litre' ? 'selected' : '' }}>Litre (L)</option>
                    <option value="sac" {{ $intrant->unite == 'sac' ? 'selected' : '' }}>Sac</option>
                    <option value="bol" {{ $intrant->unite == 'bol' ? 'selected' : '' }}>bol</option>
                </select>
                @error('unite')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Prix unitaire (CFA) *
                </label>
                <input type="number" step="1" name="prix_unitaire" required value="{{ old('prix_unitaire', $intrant->prix_unitaire) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                @error('prix_unitaire')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-toggle-on text-primary mr-1"></i> Statut
                </label>
                <select name="est_actif" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="1" {{ $intrant->est_actif ? 'selected' : '' }}>✅ Actif</option>
                    <option value="0" {{ !$intrant->est_actif ? 'selected' : '' }}>⛔ Inactif</option>
                </select>
                @error('est_actif')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-align-left text-primary mr-1"></i> Description
                </label>
                <textarea name="description" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Description de l'intrant...">{{ old('description', $intrant->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Configuration des stocks par zone -->
            <div class="md:col-span-2 mt-4">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-2"></i>Configuration des stocks par zone
                </h3>
                <p class="text-sm text-gray-500 mb-4">Ajustez les seuils d'alerte et les emplacements pour chaque zone.</p>
            </div>
            
            @foreach($intrant->stocks as $stock)
            <div class="border rounded-lg p-4 {{ $stock->est_critique ? 'bg-red-50 border-red-300' : 'bg-gray-50' }}">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="font-semibold text-dark flex items-center">
                        <i class="fas fa-location-dot text-primary mr-2"></i>
                        Zone {{ $stock->zone }}
                    </h4>
                    @if($stock->est_critique)
                    <span class="px-2 py-1 text-xs rounded-full bg-red-500 text-white">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Stock critique
                    </span>
                    @endif
                </div>
                
                <input type="hidden" name="stocks[{{ $stock->zone }}][id]" value="{{ $stock->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            Stock actuel
                        </label>
                        <div class="flex items-center">
                            <span class="text-xl font-bold {{ $stock->est_critique ? 'text-red-600' : 'text-green-600' }}">
                                {{ number_format($stock->stock_actuel) }}
                            </span>
                            <span class="ml-2 text-gray-500">{{ $stock->unite }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Pour modifier, utilisez la page de gestion du stock</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold mb-2">
                            <i class="fas fa-chart-line text-primary mr-1"></i> Seuil d'alerte *
                        </label>
                        <input type="number" step="0.01" name="stocks[{{ $stock->zone }}][seuil_alerte]" 
                               value="{{ old("stocks.{$stock->zone}.seuil_alerte", $stock->seuil_alerte) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <p class="text-xs text-gray-500 mt-1">En dessous de ce seuil, une alerte sera déclenchée</p>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold mb-2">
                            <i class="fas fa-warehouse text-primary mr-1"></i> Emplacement physique
                        </label>
                        <input type="text" name="stocks[{{ $stock->zone }}][emplacement]" 
                               value="{{ old("stocks.{$stock->zone}.emplacement", $stock->emplacement) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                               placeholder="Ex: Entrepôt Nord, Allée B, Étagère 3">
                        <p class="text-xs text-gray-500 mt-1">Localisation précise pour faciliter la gestion logistique</p>
                    </div>
                </div>
                
                @if($stock->stock_actuel > 0 && $stock->emplacement)
                <div class="mt-3 pt-3 border-t text-xs text-gray-500">
                    <i class="fas fa-qrcode mr-1"></i> Code emplacement: {{ Str::slug($stock->zone . '-' . $stock->emplacement, '-') }}
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
            <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>Attention
            </h4>
            <p class="text-sm text-yellow-700">La modification de l'unité ou du prix unitaire n'affecte pas les stocks existants. Pour modifier les quantités, utilisez la page de gestion du stock.</p>
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