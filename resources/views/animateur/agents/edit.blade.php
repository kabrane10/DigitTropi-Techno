@extends('layouts.animateur')

@section('title', 'Modifier agent')
@section('header', 'Modifier un agent')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
    <form action="{{ route('animateur.agents.update', $agent) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-semibold mb-2">Nom complet *</label><input type="text" name="nom_complet" required value="{{ $agent->nom_complet }}" class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Email *</label><input type="email" name="email" required value="{{ $agent->email }}" class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Téléphone *</label><input type="text" name="telephone" required value="{{ $agent->telephone }}" class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Zone d'affectation *</label><input type="text" name="zone_affectation" required value="{{ $agent->zone_affectation }}" class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Statut</label><select name="statut" class="w-full px-4 py-2 border rounded-lg"><option value="actif" {{ $agent->statut=='actif'?'selected':'' }}>Actif</option><option value="inactif" {{ $agent->statut=='inactif'?'selected':'' }}>Inactif</option></select></div>
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('animateur.agents.index') }}" class="px-4 py-2 border rounded-lg">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection