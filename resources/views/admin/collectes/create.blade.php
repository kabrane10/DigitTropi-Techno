@extends('layouts.admin')

@section('title', 'Nouvelle collecte')
@section('header', 'Enregistrer une collecte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.collectes.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" id="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}">{{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de collecte *</label>
                <input type="date" name="date_collecte" required value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <input type="text" name="produit" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Maïs, Soja, Arachide...">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone de collecte *</label>
                <input type="text" name="zone_collecte" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Sokodé, Kara...">
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
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Crédit (optionnel)</label>
                <select name="credit_id" id="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucun</option>
                    @foreach($credits as $credit)
                    <option value="{{ $credit->id }}">{{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA restant</option>
                    @endforeach
                </select>
            </div>
            
            <div id="deduction_div" style="display: none;">
                <label class="block text-sm font-semibold mb-2">Montant à déduire du crédit (CFA)</label>
                <input type="number" name="montant_deduict" id="montant_deduict" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires..."></textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3">Récapitulatif</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-primary" id="montant_total">0 CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Montant à déduire</p>
                    <p class="text-xl font-bold text-orange-500" id="montant_deduit_affiche">0 CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Net à payer</p>
                    <p class="text-xl font-bold text-green-600" id="net_payer">0 CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.collectes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer la collecte
            </button>
        </div>
    </form>
</div>

<script>
    // Calcul automatique
    const quantiteNette = document.getElementById('quantite_nette');
    const prixUnitaire = document.getElementById('prix_unitaire');
    const montantTotalSpan = document.getElementById('montant_total');
    const montantDeduit = document.getElementById('montant_deduict');
    const montantDeduitAffiche = document.getElementById('montant_deduit_affiche');
    const netPayerSpan = document.getElementById('net_payer');
    const creditSelect = document.getElementById('credit_id');
    const deductionDiv = document.getElementById('deduction_div');
    
    function calculerMontant() {
        const quantite = parseFloat(quantiteNette.value) || 0;
        const prix = parseFloat(prixUnitaire.value) || 0;
        const total = quantite * prix;
        montantTotalSpan.textContent = total.toLocaleString('fr-FR') + ' CFA';
        
        const deduction = parseFloat(montantDeduit.value) || 0;
        montantDeduitAffiche.textContent = deduction.toLocaleString('fr-FR') + ' CFA';
        
        const net = total - deduction;
        netPayerSpan.textContent = net.toLocaleString('fr-FR') + ' CFA';
    }
    
    quantiteNette.addEventListener('input', calculerMontant);
    prixUnitaire.addEventListener('input', calculerMontant);
    montantDeduit.addEventListener('input', calculerMontant);
    
    creditSelect.addEventListener('change', function() {
        if (this.value) {
            deductionDiv.style.display = 'block';
            // Suggérer le montant maximum déductible
            const montantTotal = parseFloat(quantiteNette.value) * parseFloat(prixUnitaire.value) || 0;
            montantDeduit.value = Math.min(montantTotal, 50000);
            calculerMontant();
        } else {
            deductionDiv.style.display = 'none';
            montantDeduit.value = 0;
            calculerMontant();
        }
    });
</script>
@endsection