@extends('layouts.admin')

@section('title', 'Nouveau suivi')
@section('header', 'Enregistrer un suivi parcellaire')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.suivi.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" id="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $p)
                    <option value="{{ $p->id }}" {{ ($producteur_selectionne && $producteur_selectionne->id == $p->id) ? 'selected' : '' }}>
                        {{ $p->nom_complet }} - {{ $p->code_producteur }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Animateur *</label>
                <select name="animateur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un animateur</option>
                    @foreach($animateurs as $a)
                    <option value="{{ $a->id }}">{{ $a->nom_complet }} - {{ $a->zone_responsabilite }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date du suivi *</label>
                <input type="date" name="date_suivi" required value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Distribution associée</label>
                <select name="distribution_semence_id" id="distribution_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucune</option>
                    @isset($distributions)
                        @foreach($distributions as $dist)
                        <option value="{{ $dist->id }}">
                            {{ $dist->code_distribution }} - {{ $dist->semence->nom }} ({{ number_format($dist->quantite) }} {{ $dist->semence->unite }})
                        </option>
                        @endforeach
                    @endisset
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie actuelle (ha) *</label>
                <input type="number" step="0.01" name="superficie_actuelle" required
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Hauteur des plantes (cm)</label>
                <input type="number" step="0.5" name="hauteur_plantes"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Stade de croissance *</label>
                <select name="stade_croissance" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="Semis">Semis</option>
                    <option value="Levée">Levée</option>
                    <option value="Croissance végétative">Croissance végétative</option>
                    <option value="Floraison">Floraison</option>
                    <option value="Fructification">Fructification</option>
                    <option value="Maturation">Maturation</option>
                    <option value="Récolte">Récolte</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Santé des cultures *</label>
                <select name="sante_cultures" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez</option>
                    <option value="excellente">Excellente</option>
                    <option value="bonne">Bonne</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="mauvaise">Mauvaise</option>
                    <option value="critique">Critique</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Taux de levée (%)</label>
                <input type="number" name="taux_levée" min="0" max="100"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Problèmes constatés</label>
                <textarea name="problemes_constates" rows="2" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Maladies, ravageurs, sécheresse, etc..."></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Recommandations</label>
                <textarea name="recommandations" rows="2" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Conseils pour l'agriculteur..."></textarea>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Actions prises</label>
                <textarea name="actions_prises" rows="2" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Actions réalisées suite au suivi..."></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.suivi.liste') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer le suivi
            </button>
        </div>
    </form>
</div>

<script>
    const producteurSelect = document.getElementById('producteur_id');
    const distributionSelect = document.getElementById('distribution_id');
    
    producteurSelect.addEventListener('change', function() {
        const producteurId = this.value;
        if (producteurId) {
            fetch(`/admin/suivi/distributions/${producteurId}`)
                .then(response => response.json())
                .then(data => {
                    distributionSelect.innerHTML = '<option value="">Aucune</option>';
                    data.forEach(dist => {
                        distributionSelect.innerHTML += `<option value="${dist.id}">${dist.code_distribution} - ${dist.semence.nom} (${dist.quantite} ${dist.semence.unite})</option>`;
                    });
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        } else {
            distributionSelect.innerHTML = '<option value="">Aucune</option>';
        }
    });
</script>
@endsection