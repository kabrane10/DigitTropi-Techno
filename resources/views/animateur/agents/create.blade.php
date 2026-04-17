@extends('layouts.animateur')

@section('title', 'Nouvel agent')
@section('header', 'Ajouter un agent')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
    <form action="{{ route('animateur.agents.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><label class="block text-sm font-semibold mb-2">Nom complet *</label><input type="text" name="nom_complet" required class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Email *</label><input type="email" name="email" required class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Téléphone *</label><input type="text" name="telephone" required class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Zone d'affectation *</label><input type="text" name="zone_affectation" required class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-semibold mb-2">Date d'embauche *</label><input type="date" name="date_embauche" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg"></div>
        </div>
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg"><p class="text-sm text-yellow-800"><i class="fas fa-info-circle mr-2"></i>Mot de passe par défaut: <strong>password123</strong></p></div>
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('animateur.agents.index') }}" class="px-4 py-2 border rounded-lg">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg">Enregistrer</button>
        </div>
    </form>
</div>
@endsection