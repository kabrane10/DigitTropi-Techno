@extends('layouts.admin')

@section('title', 'Modifier contrôleur')
@section('header', 'Modifier un contrôleur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.controleurs.update', $controleur) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet', $controleur->nom_complet) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" required value="{{ old('email', $controleur->email) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Téléphone *</label>
                <input type="text" name="telephone" required value="{{ old('telephone', $controleur->telephone) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région de contrôle *</label>
                <select name="region_controle" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="Centrale" {{ $controleur->region_controle == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ $controleur->region_controle == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ $controleur->region_controle == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                    <option value="Toutes" {{ $controleur->region_controle == 'Toutes' ? 'selected' : '' }}>Toutes les régions</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'embauche *</label>
                <input type="date" name="date_embauche" required value="{{ old('date_embauche', $controleur->date_embauche->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ $controleur->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $controleur->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.controleurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection