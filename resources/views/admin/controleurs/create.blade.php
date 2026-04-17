@extends('layouts.admin')

@section('title', 'Ajouter un contrôleur')
@section('header', 'Nouveau contrôleur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.controleurs.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Téléphone *</label>
                <input type="text" name="telephone" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région de contrôle *</label>
                <select name="region_controle" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="Centrale">Centrale</option>
                    <option value="Kara">Kara</option>
                    <option value="Savanes">Savanes</option>
                    <option value="Toutes">Toutes les régions</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'embauche *</label>
                <input type="date" name="date_embauche" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif">Actif</option>
                    <option value="inactif">Inactif</option>
                </select>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.controleurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
    
    <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
        <p class="text-sm text-yellow-800">
            <i class="fas fa-info-circle mr-2"></i>
            Le mot de passe par défaut est : <strong>password123</strong>
        </p>
    </div>
</div>
@endsection