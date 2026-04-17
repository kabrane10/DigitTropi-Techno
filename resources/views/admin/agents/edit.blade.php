@extends('layouts.admin')

@section('title', 'Modifier agent terrain')
@section('header', 'Modifier un agent terrain')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.agents.update', $agent) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code agent</label>
                <input type="text" value="{{ $agent->code_agent }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet', $agent->nom_complet) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email *</label>
                <input type="email" name="email" required value="{{ old('email', $agent->email) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Téléphone *</label>
                <input type="text" name="telephone" required value="{{ old('telephone', $agent->telephone) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone d'affectation *</label>
                <input type="text" name="zone_affectation" required value="{{ old('zone_affectation', $agent->zone_affectation) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superviseur</label>
                <select name="superviseur_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucun</option>
                    @foreach($animateurs as $animateur)
                    <option value="{{ $animateur->id }}" {{ $agent->superviseur_id == $animateur->id ? 'selected' : '' }}>
                        {{ $animateur->nom_complet }} - {{ $animateur->zone_responsabilite }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'embauche *</label>
                <input type="date" name="date_embauche" required value="{{ old('date_embauche', $agent->date_embauche->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ $agent->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $agent->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Producteurs enregistrés</label>
                <input type="text" value="{{ number_format($agent->producteurs_enregistres) }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">Ce nombre est automatiquement mis à jour</p>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-info-circle mr-2"></i>
                Pour réinitialiser le mot de passe, utilisez le bouton dédié dans la fiche de l'agent.
            </p>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.agents.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection