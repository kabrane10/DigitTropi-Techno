@extends('layouts.admin')

@section('title', 'Modifier animateur')
@section('header', 'Modifier un animateur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.animateurs.update', $animateur) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet', $animateur->nom_complet) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" required value="{{ old('email', $animateur->email) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required value="{{ old('contact', $animateur->contact) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="Centrale" {{ $animateur->region == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ $animateur->region == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ $animateur->region == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone de responsabilité *</label>
                <input type="text" name="zone_responsabilite" required value="{{ old('zone_responsabilite', $animateur->zone_responsabilite) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'embauche *</label>
                <input type="date" name="date_embauche" required value="{{ old('date_embauche', $animateur->date_embauche->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ $animateur->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $animateur->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="en_conge" {{ $animateur->statut == 'en_conge' ? 'selected' : '' }}>En congé</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Qualifications / Compétences</label>
                <textarea name="qualifications" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Listez les compétences et qualifications...">{{ old('qualifications', $animateur->qualifications) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.animateurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection