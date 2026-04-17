@extends('layouts.admin')

@section('title', 'Modifier suivi')
@section('header', 'Modifier le suivi parcellaire')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.suivi.update', $suivi) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations fixes -->
            <div>
                <label class="block text-sm font-semibold mb-2">Code suivi</label>
                <input type="text" value="{{ $suivi->code_suivi }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur</label>
                <input type="text" value="{{ $suivi->producteur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Animateur</label>
                <input type="text" value="{{ $suivi->animateur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date du suivi *</label>
                <input type="date" name="date_suivi" required 
                       value="{{ old('date_suivi', $suivi->date_suivi->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie actuelle (ha) *</label>
                <input type="number" step="0.01" name="superficie_actuelle" required 
                       value="{{ old('superficie_actuelle', $suivi->superficie_actuelle) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Hauteur des plantes (cm)</label>
                <input type="number" step="0.5" name="hauteur_plantes" 
                       value="{{ old('hauteur_plantes', $suivi->hauteur_plantes) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Stade de croissance *</label>
                <select name="stade_croissance" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
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
                <select name="sante_cultures" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="excellente" {{ $suivi->sante_cultures == 'excellente' ? 'selected' : '' }}>Excellente</option>
                    <option value="bonne" {{ $suivi->sante_cultures == 'bonne' ? 'selected' : '' }}>Bonne</option>
                    <option value="moyenne" {{ $suivi->sante_cultures == 'moyenne' ? 'selected' : '' }}>Moyenne</option>
                    <option value="mauvaise" {{ $suivi->sante_cultures == 'mauvaise' ? 'selected' : '' }}>Mauvaise</option>
                    <option value="critique" {{ $suivi->sante_cultures == 'critique' ? 'selected' : '' }}>Critique</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Taux de levée (%)</label>
                <input type="number" name="taux_levée" min="0" max="100" 
                       value="{{ old('taux_levée', $suivi->taux_levée) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Problèmes constatés</label>
                <textarea name="problemes_constates" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Maladies, ravageurs, sécheresse, inondations...">{{ old('problemes_constates', $suivi->problemes_constates) }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Recommandations</label>
                <textarea name="recommandations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Conseils et recommandations pour l'agriculteur...">{{ old('recommandations', $suivi->recommandations) }}</textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Actions prises</label>
                <textarea name="actions_prises" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Actions réalisées suite à ce suivi...">{{ old('actions_prises', $suivi->actions_prises) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.suivi.show', $suivi) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection