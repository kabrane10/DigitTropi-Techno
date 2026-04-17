@extends('layouts.admin')

@section('title', 'Modifier semence')
@section('header', 'Modifier une semence')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.semences.update', $semence) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code semence</label>
                <input type="text" value="{{ $semence->code_semence }}" disabled class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom de la semence *</label>
                <input type="text" name="nom" required value="{{ old('nom', $semence->nom) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Variété *</label>
                <input type="text" name="variete" required value="{{ old('variete', $semence->variete) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Type *</label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="soja" {{ $semence->type == 'soja' ? 'selected' : '' }}>Soja</option>
                    <option value="arachide" {{ $semence->type == 'arachide' ? 'selected' : '' }}>Arachide</option>
                    <option value="sesame" {{ $semence->type == 'sesame' ? 'selected' : '' }}>Sésame</option>
                    <option value="mais" {{ $semence->type == 'mais' ? 'selected' : '' }}>Maïs</option>
                    <option value="riz" {{ $semence->type == 'riz' ? 'selected' : '' }}>Riz</option>
                    <option value="gombo" {{ $semence->type == 'gombo' ? 'selected' : '' }}>Gombo</option>
                    <option value="autres" {{ $semence->type == 'autres' ? 'selected' : '' }}>Autres</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Unité *</label>
                <select name="unite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="kg" {{ $semence->unite == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                    <option value="tonne" {{ $semence->unite == 'tonne' ? 'selected' : '' }}>Tonne (t)</option>
                    <option value="sac" {{ $semence->unite == 'sac' ? 'selected' : '' }}>Sac</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA) *</label>
                <input type="number" step="1" name="prix_unitaire" required value="{{ old('prix_unitaire', $semence->prix_unitaire) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Stock actuel</label>
                <input type="text" value="{{ number_format($semence->stock_disponible) }} {{ $semence->unite }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">Pour modifier le stock, utilisez le bouton "Ajouter du stock"</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('description', $semence->description) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.semences.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection