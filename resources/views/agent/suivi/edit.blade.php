@extends('layouts.agent')

@section('title', 'Modifier suivi')
@section('header', 'Modifier un suivi parcellaire')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.suivi.update', $suivi) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code suivi</label>
                <input type="text" value="{{ $suivi->code_suivi }}" disabled class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}" {{ $suivi->producteur_id == $producteur->id ? 'selected' : '' }}>
                        {{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date du suivi *</label>
                <input type="date" name="date_suivi" required value="{{ $suivi->date_suivi->format('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie actuelle (ha) *</label>
                <input type="number" step="0.01" name="superficie_actuelle" required value="{{ $suivi->superficie_actuelle }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Hauteur des plantes (cm)</label>
                <input type="number" step="0.5" name="hauteur_plantes" value="{{ $suivi->hauteur_plantes }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Stade de croissance *</label>
                <select name="stade_croissance" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="Semis" {{ $suivi->stade_croissance == 'Semis' ? 'selected' : '' }}>Semis</option>
                    <option value="Levée" {{ $suivi->stade_croissance == 'Levée' ? 'selected' : '' }}>Levée</option>
                    <option value="Croissance végétative" {{ $suivi->stade_croissance == 'Croissance végétative' ? 'selected' : '' }}>Croissance végétative</option>
                    <option value="Floraison" {{ $suivi->stade_croissance == 'Floraison' ? 'selected' : '' }}>Floraison</option>
                    <option value="Fructification" {{ $suivi->stade_croissance == 'Fructification' ? 'selected' : '' }}>Fructification</option>
                    <option value="Maturation" {{ $suivi->stade_croissance == 'Maturation' ? 'selected' : '' }}>Maturation</option>
                    <option value="Récolte" {{ $suivi->stade_croissance == 'Récolte' ? 'selected' : '' }}>Récolte</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Santé des cultures *</label>
                <select name="sante_cultures" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="excellente" {{ $suivi->sante_cultures == 'excellente' ? 'selected' : '' }}>🌟 Excellente</option>
                    <option value="bonne" {{ $suivi->sante_cultures == 'bonne' ? 'selected' : '' }}>✅ Bonne</option>
                    <option value="moyenne" {{ $suivi->sante_cultures == 'moyenne' ? 'selected' : '' }}>⚠️ Moyenne</option>
                    <option value="mauvaise" {{ $suivi->sante_cultures == 'mauvaise' ? 'selected' : '' }}>❌ Mauvaise</option>
                    <option value="critique" {{ $suivi->sante_cultures == 'critique' ? 'selected' : '' }}>🔴 Critique</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Taux de levée (%)</label>
                <input type="number" name="taux_levée" min="0" max="100" value="{{ $suivi->taux_levée }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Problèmes constatés</label>
                <textarea name="problemes_constates" rows="2" class="w-full px-4 py-2 border rounded-lg">{{ $suivi->problemes_constates }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Recommandations</label>
                <textarea name="recommandations" rows="2" class="w-full px-4 py-2 border rounded-lg">{{ $suivi->recommandations }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Actions prises</label>
                <textarea name="actions_prises" rows="2" class="w-full px-4 py-2 border rounded-lg">{{ $suivi->actions_prises }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.suivi.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection