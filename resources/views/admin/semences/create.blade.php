@extends('layouts.admin')

@section('title', 'Nouvelle semence')
@section('header', 'Ajouter une semence')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.semences.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom de la semence *</label>
                <input type="text" name="nom" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Soja, Maïs, Arachide...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Variété *</label>
                <input type="text" name="variete" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: TGX-1987-10F, SAMMAZ 52...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Type *</label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="soja">Soja</option>
                    <option value="arachide">Arachide</option>
                    <option value="sesame">Sésame</option>
                    <option value="mais">Maïs</option>
                    <option value="riz">Riz</option>
                    <option value="gombo">Gombo</option>
                    <option value="autres">Autres</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Unité *</label>
                <select name="unite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="kg">Kilogramme (kg)</option>
                    <option value="tonne">Tonne (t)</option>
                    <option value="sac">Sac</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA) *</label>
                <input type="number" step="1" name="prix_unitaire" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 500">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Stock initial *</label>
                <input type="number" step="0.01" name="stock_disponible" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Quantité initiale">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Caractéristiques de la semence..."></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.semences.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection