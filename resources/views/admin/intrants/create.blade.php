@extends('layouts.admin')

@section('title', 'Nouvel intrant')
@section('header', 'Ajouter un intrant')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.intrants.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom de l'intrant *</label>
                <input type="text" name="nom" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Type *</label>
                <select name="type" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="engrais"> Engrais</option>
                    <option value="pesticide"> Pesticide</option>
                    <option value="herbicide"> Herbicide</option>
                    <option value="semence"> Semence</option>
                    <option value="autre"> Autre</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Unité *</label>
                <select name="unite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="kg">Kilogramme (kg)</option>
                    <option value="litre">Litre (L)</option>
                    <option value="sac">Sac</option>
                    <option value="botte">Bol</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA) *</label>
                <input type="number" step="1" name="prix_unitaire" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2">ℹ️ Information</h4>
            <p class="text-sm text-blue-700">Le stock initial sera créé automatiquement pour les 3 zones (Centrale, Kara, Savanes) avec 0 unité. Vous pourrez ajouter du stock ultérieurement.</p>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.intrants.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection