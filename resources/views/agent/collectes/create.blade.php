@extends('layouts.agent')

@section('title', 'Nouvelle collecte')
@section('header', 'Enregistrer une collecte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.collectes.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}">{{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de collecte *</label>
                <input type="date" name="date_collecte" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <input type="text" name="produit" required class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: Maïs, Soja...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone de collecte *</label>
                <input type="text" name="zone_collecte" required class="w-full px-4 py-2 border rounded-lg" placeholder="Ex: Sokodé">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité brute (kg) *</label>
                <input type="number" step="0.01" name="quantite_brute" id="quantite_brute" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité nette (kg) *</label>
                <input type="number" step="0.01" name="quantite_nette" id="quantite_nette" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3">Récapitulatif</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-primary" id="montant_total">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg">
                    <p class="text-sm text-gray-500">Net à payer</p>
                    <p class="text-xl font-bold text-green-600" id="net_payer">0 CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.collectes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
    const quantiteNette = document.getElementById('quantite_nette');
    const prixUnitaire = document.getElementById('prix_unitaire');
    const montantTotalSpan = document.getElementById('montant_total');
    const netPayerSpan = document.getElementById('net_payer');
    
    function calculerMontant() {
        const quantite = parseFloat(quantiteNette.value) || 0;
        const prix = parseFloat(prixUnitaire.value) || 0;
        const total = quantite * prix;
        montantTotalSpan.textContent = total.toLocaleString() + ' CFA';
        netPayerSpan.textContent = total.toLocaleString() + ' CFA';
    }
    
    quantiteNette.addEventListener('input', calculerMontant);
    prixUnitaire.addEventListener('input', calculerMontant);
</script>
@endsection