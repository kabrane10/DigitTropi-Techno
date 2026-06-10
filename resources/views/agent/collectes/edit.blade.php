@extends('layouts.agent')

@section('title', 'Modifier collecte')
@section('header', 'Modifier une collecte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('agent.collectes.update', $collecte) }}" method="POST" class="offline-form">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
             <label class="block text-sm font-semibold mb-2">Producteur *</label>
             <input type="hidden" name="beneficiaire_type" value="producteur">
            <select name="beneficiaire_id" id="producteur_selector" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <option value="">Sélectionnez un producteur</option>
                @foreach($producteurs as $producteur)
                <option value="{{ $producteur->id }}" 
                        data-credits='{{ json_encode($producteur->credits) }}'
                        {{ old('beneficiaire_id', $collecte->beneficiaire_id) == $producteur->id ? 'selected' : '' }}>
                    {{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Date de collecte *</label>
                <input type="date" name="date_collecte" required value="{{ old('date_collecte', $collecte->date_collecte->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <select name="produit" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    @foreach(['Maïs', 'Soja', 'Riz', 'Arachide', 'Sésame', 'Gombo', 'Autre'] as $produit)
                        <option value="{{ $produit }}" {{ old('produit', $collecte->produit) == $produit ? 'selected' : '' }}>{{ $produit }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone de collecte *</label>
                <select name="zone_collecte" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                     @foreach(['Centrale', 'Kara', 'Savanes'] as $zone)
                        <option value="{{ $zone }}" {{ old('zone_collecte', $collecte->zone_collecte) == $zone ? 'selected' : '' }}>{{ $zone }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité brute (kg) *</label>
                <input type="number" step="0.01" name="quantite_brute" id="quantite_brute" required value="{{ old('quantite_brute', $collecte->quantite_brute) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité nette (kg) *</label>
                <input type="number" step="0.01" name="quantite_nette" id="quantite_nette" required value="{{ old('quantite_nette', $collecte->quantite_nette) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required value="{{ old('prix_unitaire', $collecte->prix_unitaire) }}"
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
                <input type="number" step="100" name="montant_deduict" id="montant_deduict" value="{{ old('montant_deduict', $collecte->montant_deduict) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Maximum déductible : <span id="max_deduction">0</span> CFA</p>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires...">{{ old('observations', $collecte->observations) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3">Récapitulatif</h4>
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
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const producteurSelector = document.getElementById('producteur_selector');
    const creditSelect = document.getElementById('credit_id');
    
    function populateCredits() {
        creditSelect.innerHTML = '<option value="">-- Aucun crédit --</option>';
        
        const selectedOption = producteurSelector.options[producteurSelector.selectedIndex];
        if (!selectedOption || !selectedOption.value) return;

        const credits = JSON.parse(selectedOption.getAttribute('data-credits') || '[]');
        const currentCreditId = "{{ old('credit_id', $collecte->credit_id) }}";

        credits.forEach(function(credit) {
            const option = new Option(
                `${credit.code_credit} - ${parseFloat(credit.montant_restant).toLocaleString('fr-FR')} CFA restant`,
                credit.id
            );
            if (credit.id == currentCreditId) {
                option.selected = true;
            }
            creditSelect.add(option);
        });
        
        creditSelect.dispatchEvent(new Event('change'));
    }

    producteurSelector.addEventListener('change', populateCredits);
    populateCredits();

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