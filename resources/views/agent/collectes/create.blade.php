@extends('layouts.agent')

@section('title', 'Nouvelle collecte')
@section('header', 'Enregistrer une collecte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.collectes.store') }}" method="POST" class="offline-form">
        @csrf
        
        <div class="mb-4">
             <label class="block text-sm font-semibold mb-2">Producteur *</label>
             <input type="hidden" name="beneficiaire_type" value="producteur">
            <select name="beneficiaire_id" id="producteur_selector" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Sélectionnez un producteur</option>
                @foreach($producteurs as $producteur)
                <option value="{{ $producteur->id }}" data-credits='{{ json_encode($producteur->credits->where("statut", "actif")) }}'>
                    {{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Date de collecte *</label>
                <input type="date" name="date_collecte" required value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <select name="produit" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez un produit --</option>
                    <option value="Maïs"> Maïs</option>
                    <option value="Soja"> Soja</option>
                    <option value="Riz"> Riz</option>
                    <option value="Arachide"> Arachide</option>
                    <option value="Sésame"> Sésame</option>
                    <option value="Gombo"> Gombo</option>
                    <option value="Autre"> Autre</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone de collecte *</label>
                <select name="zone_collecte" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une zone --</option>
                    <option value="Centrale"> Centrale</option>
                    <option value="Kara"> Kara</option>
                    <option value="Savanes"> Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité brute (kg) *</label>
                <input type="number" step="0.01" name="quantite_brute" id="quantite_brute" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité nette (kg) *</label>
                <input type="number" step="0.01" name="quantite_nette" id="quantite_nette" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Poids après nettoyage et tri</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Crédit (optionnel)</label>
                <select name="credit_id" id="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Aucun crédit --</option>
                </select>
            </div>
            
            <div id="deduction_div" style="display: none;">
                <label class="block text-sm font-semibold mb-2">Montant à déduire du crédit (CFA)</label>
                <input type="number" step="100" name="montant_deduict" id="montant_deduict" value="0"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Maximum déductible : <span id="max_deduction">0</span> CFA</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires..."></textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3"> Récapitulatif</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-primary" id="montant_total">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant à déduire</p>
                    <p class="text-xl font-bold text-orange-500" id="montant_deduit_affiche">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Net à payer</p>
                    <p class="text-xl font-bold text-green-600" id="net_payer">0 CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('agent.collectes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer la collecte
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const producteurSelector = document.getElementById('producteur_selector');
        const creditSelect = document.getElementById('credit_id');
        
        producteurSelector.addEventListener('change', function() {
            creditSelect.innerHTML = '<option value="">-- Aucun crédit --</option>';
            const selectedOption = this.options[this.selectedIndex];
            const credits = JSON.parse(selectedOption.getAttribute('data-credits') || '[]');

            credits.forEach(function(credit) {
                const option = new Option(
                    `${credit.code_credit} - ${parseFloat(credit.montant_restant).toLocaleString('fr-FR')} CFA restant`,
                    credit.id
                );
                creditSelect.add(option);
            });
        });

        const quantiteNette = document.getElementById('quantite_nette');
        const prixUnitaire = document.getElementById('prix_unitaire');
        const montantTotalSpan = document.getElementById('montant_total');
        const montantDeduit = document.getElementById('montant_deduict');
        const montantDeduitAffiche = document.getElementById('montant_deduit_affiche');
        const netPayerSpan = document.getElementById('net_payer');
        const deductionDiv = document.getElementById('deduction_div');
        const maxDeductionSpan = document.getElementById('max_deduction');
        
        function calculerMontant() {
            const quantite = parseFloat(quantiteNette.value) || 0;
            const prix = parseFloat(prixUnitaire.value) || 0;
            const total = quantite * prix;
            montantTotalSpan.textContent = total.toLocaleString('fr-FR') + ' CFA';
            
            let deduction = parseFloat(montantDeduit.value) || 0;
            if (deduction > total) {
                deduction = total;
                montantDeduit.value = total;
            }
            montantDeduitAffiche.textContent = deduction.toLocaleString('fr-FR') + ' CFA';
            
            const net = total - deduction;
            netPayerSpan.textContent = net.toLocaleString('fr-FR') + ' CFA';
            
            maxDeductionSpan.textContent = total.toLocaleString('fr-FR');
        }
        
        quantiteNette.addEventListener('input', calculerMontant);
        prixUnitaire.addEventListener('input', calculerMontant);
        montantDeduit.addEventListener('input', calculerMontant);
        
        creditSelect.addEventListener('change', function() {
            if (this.value) {
                deductionDiv.style.display = 'block';
            } else {
                deductionDiv.style.display = 'none';
                montantDeduit.value = 0;
            }
            calculerMontant();
        });
        
        calculerMontant();
    });
</script>
@endsection