@extends('layouts.admin')

@section('title', 'Modifier la collecte')
@section('header', 'Modifier la collecte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.collectes.update', $collecte) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code collecte</label>
                <input type="text" value="{{ $collecte->code_collecte }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de collecte *</label>
                <input type="date" name="date_collecte" required value="{{ old('date_collecte', $collecte->date_collecte->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur</label>
                <input type="text" value="{{ $collecte->producteur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <input type="text" name="produit" required value="{{ old('produit', $collecte->produit) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone de collecte *</label>
                <input type="text" name="zone_collecte" required value="{{ old('zone_collecte', $collecte->zone_collecte) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité brute (kg) *</label>
                <input type="number" step="0.01" name="quantite_brute" id="quantite_brute" required 
                       value="{{ old('quantite_brute', $collecte->quantite_brute) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité nette (kg) *</label>
                <input type="number" step="0.01" name="quantite_nette" id="quantite_nette" required 
                       value="{{ old('quantite_nette', $collecte->quantite_nette) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required 
                       value="{{ old('prix_unitaire', $collecte->prix_unitaire) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut paiement</label>
                <select name="statut_paiement" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="en_attente" {{ $collecte->statut_paiement == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="partiel" {{ $collecte->statut_paiement == 'partiel' ? 'selected' : '' }}>Partiel</option>
                    <option value="paye" {{ $collecte->statut_paiement == 'paye' ? 'selected' : '' }}>Payé</option>
                </select>
            </div>
            
            @if($collecte->credit_id)
            <div>
                <label class="block text-sm font-semibold mb-2">Crédit associé</label>
                <input type="text" value="{{ $collecte->credit->code_credit ?? 'N/A' }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Montant déduit (CFA)</label>
                <input type="number" name="montant_deduict" id="montant_deduict" 
                       value="{{ old('montant_deduict', $collecte->montant_deduict) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            @endif
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('observations', $collecte->observations) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3">Récapitulatif</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-primary" id="montant_total">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Montant déduit</p>
                    <p class="text-xl font-bold text-orange-500" id="montant_deduit_affiche">{{ number_format($collecte->montant_deduict, 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Net à payer</p>
                    <p class="text-xl font-bold text-green-600" id="net_payer">{{ number_format($collecte->montant_a_payer, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.collectes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    const quantiteNette = document.getElementById('quantite_nette');
    const prixUnitaire = document.getElementById('prix_unitaire');
    const montantTotalSpan = document.getElementById('montant_total');
    const montantDeduit = document.getElementById('montant_deduict');
    const montantDeduitAffiche = document.getElementById('montant_deduit_affiche');
    const netPayerSpan = document.getElementById('net_payer');
    
    function calculerMontant() {
        const quantite = parseFloat(quantiteNette.value) || 0;
        const prix = parseFloat(prixUnitaire.value) || 0;
        const total = quantite * prix;
        montantTotalSpan.textContent = total.toLocaleString('fr-FR') + ' CFA';
        
        const deduction = parseFloat(montantDeduit?.value) || 0;
        if (montantDeduitAffiche) montantDeduitAffiche.textContent = deduction.toLocaleString('fr-FR') + ' CFA';
        
        const net = total - deduction;
        netPayerSpan.textContent = net.toLocaleString('fr-FR') + ' CFA';
    }
    
    quantiteNette.addEventListener('input', calculerMontant);
    prixUnitaire.addEventListener('input', calculerMontant);
    if (montantDeduit) montantDeduit.addEventListener('input', calculerMontant);
</script>
@endsection