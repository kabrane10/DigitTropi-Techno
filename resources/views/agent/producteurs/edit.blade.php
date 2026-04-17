@extends('layouts.agent')

@section('title', 'Modifier producteur')
@section('header', 'Modifier un producteur')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.producteurs.update', $producteur) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code producteur</label>
                <input type="text" value="{{ $producteur->code_producteur }}" disabled class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Nom complet *</label>
                <input type="text" name="nom_complet" required value="{{ old('nom_complet', $producteur->nom_complet) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact *</label>
                <input type="text" name="contact" required value="{{ old('contact', $producteur->contact) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $producteur->email) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Région *</label>
                <select name="region" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="Centrale" {{ $producteur->region == 'Centrale' ? 'selected' : '' }}>Centrale</option>
                    <option value="Kara" {{ $producteur->region == 'Kara' ? 'selected' : '' }}>Kara</option>
                    <option value="Savanes" {{ $producteur->region == 'Savanes' ? 'selected' : '' }}>Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Localisation *</label>
                <input type="text" name="localisation" required value="{{ old('localisation', $producteur->localisation) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Culture pratiquée *</label>
                <input type="text" name="culture_pratiquee" required value="{{ old('culture_pratiquee', $producteur->culture_pratiquee) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie totale (ha) *</label>
                <input type="number" step="0.01" name="superficie_totale" required value="{{ old('superficie_totale', $producteur->superficie_totale) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ $producteur->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ $producteur->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    <option value="en_attente" {{ $producteur->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Coopérative</label>
                <select name="cooperative_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucune</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}" {{ $producteur->cooperative_id == $cooperative->id ? 'selected' : '' }}>{{ $cooperative->nom }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('notes', $producteur->notes) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.producteurs.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection