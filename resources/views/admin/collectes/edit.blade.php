@extends('layouts.admin')

@section('title', 'Modifier la collecte')
@section('header', 'Modifier la collecte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.collectes.update', $collecte) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Informations bénéficiaire (lecture seule - non modifiable) -->
        <div class="mb-6 p-4 rounded-lg {{ $collecte->cooperative_id ? 'bg-purple-50' : 'bg-green-50' }}">
            <div class="flex items-center">
                @if($collecte->cooperative_id || $collecte->beneficiaire_type === 'App\\Models\\Cooperative')
                    <i class="fas fa-handshake text-purple-600 text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-600">Bénéficiaire (non modifiable)</p>
                        <p class="font-semibold text-lg">{{ $collecte->cooperative->nom ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Code: {{ $collecte->cooperative->code_cooperative ?? 'N/A' }} - Coopérative</p>
                    </div>
                @else
                    <i class="fas fa-user text-green-600 text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm text-gray-600">Bénéficiaire (non modifiable)</p>
                        <p class="font-semibold text-lg">{{ $collecte->producteur->nom_complet }}</p>
                        <p class="text-sm text-gray-500">Code: {{ $collecte->producteur->code_producteur }} - Producteur</p>
                    </div>
                @endif
            </div>
            <input type="hidden" name="beneficiaire_type" value="{{ $collecte->cooperative_id ? 'cooperative' : 'producteur' }}">
            <input type="hidden" name="producteur_id" value="{{ $collecte->producteur_id }}">
            <input type="hidden" name="cooperative_id" value="{{ $collecte->cooperative_id }}">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code collecte
                </label>
                <input type="text" value="{{ $collecte->code_collecte }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date de collecte *
                </label>
                <input type="date" name="date_collecte" required value="{{ old('date_collecte', $collecte->date_collecte->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-boxes text-primary mr-1"></i> Produit *
                </label>
                <select name="produit" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez un produit --</option>
                    <option value="Maïs" {{ old('produit', $collecte->produit) == 'Maïs' ? 'selected' : '' }}> Maïs</option>
                    <option value="Soja" {{ old('produit', $collecte->produit) == 'Soja' ? 'selected' : '' }}> Soja</option>
                    <option value="Riz" {{ old('produit', $collecte->produit) == 'Riz' ? 'selected' : '' }}> Riz</option>
                    <option value="Arachide" {{ old('produit', $collecte->produit) == 'Arachide' ? 'selected' : '' }}> Arachide</option>
                    <option value="Sésame" {{ old('produit', $collecte->produit) == 'Sésame' ? 'selected' : '' }}> Sésame</option>
                    <option value="Gombo" {{ old('produit', $collecte->produit) == 'Gombo' ? 'selected' : '' }}> Gombo</option>
                    <option value="Autre" {{ old('produit', $collecte->produit) == 'Autre' ? 'selected' : '' }}> Autre</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Zone de collecte *
                </label>
                <select name="zone_collecte" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une zone --</option>
                    <option value="Centrale" {{ old('zone_collecte', $collecte->zone_collecte) == 'Centrale' ? 'selected' : '' }}>🌍 Centrale</option>
                    <option value="Kara" {{ old('zone_collecte', $collecte->zone_collecte) == 'Kara' ? 'selected' : '' }}>🏔️ Kara</option>
                    <option value="Savanes" {{ old('zone_collecte', $collecte->zone_collecte) == 'Savanes' ? 'selected' : '' }}>🌾 Savanes</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité brute (kg) *
                </label>
                <input type="number" step="0.01" name="quantite_brute" id="quantite_brute" required 
                       value="{{ old('quantite_brute', $collecte->quantite_brute) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Poids total à la réception</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-balance-scale text-primary mr-1"></i> Quantité nette (kg) *
                </label>
                <input type="number" step="0.01" name="quantite_nette" id="quantite_nette" required 
                       value="{{ old('quantite_nette', $collecte->quantite_nette) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Poids après nettoyage et tri</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-tag text-primary mr-1"></i> Prix unitaire (CFA/kg) *
                </label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required 
                       value="{{ old('prix_unitaire', $collecte->prix_unitaire) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-credit-card text-primary mr-1"></i> Statut paiement
                </label>
                <select name="statut_paiement" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="en_attente" {{ $collecte->statut_paiement == 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                    <option value="partiel" {{ $collecte->statut_paiement == 'partiel' ? 'selected' : '' }}>🟡 Partiel</option>
                    <option value="paye" {{ $collecte->statut_paiement == 'paye' ? 'selected' : '' }}>✅ Payé</option>
                </select>
            </div>
            
            @if($collecte->credit_id)
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Crédit associé
                </label>
                <input type="text" value="{{ $collecte->credit->code_credit ?? 'N/A' }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-minus-circle text-primary mr-1"></i> Montant déduit (CFA)
                </label>
                <input type="number" step="100" name="montant_deduict" id="montant_deduict" 
                       value="{{ old('montant_deduict', $collecte->montant_deduict) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">Montant prélevé sur le crédit</p>
            </div>
            @else
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Crédit associé
                </label>
                <input type="text" value="Aucun crédit associé" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            @endif
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Observations
                </label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires...">{{ old('observations', $collecte->observations) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3"> Récapitulatif financier</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-primary" id="montant_total">{{ number_format($collecte->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant déduit</p>
                    <p class="text-xl font-bold text-orange-500" id="montant_deduit_affiche">{{ number_format($collecte->montant_deduict, 0, ',', ' ') }} CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Net à payer</p>
                    <p class="text-xl font-bold text-green-600" id="net_payer">{{ number_format($collecte->montant_a_payer, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
        </div>
        
        <!-- Message d'avertissement si modification importante -->
        @if($collecte->credit_id && $collecte->montant_deduict > 0)
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">⚠️ Attention</p>
                    <p class="text-sm text-yellow-700">
                        La modification du montant déduit affectera le solde du crédit associé. 
                        Vérifiez bien les nouvelles valeurs.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.collectes.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Annuler
            </a>
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
        const quantite = parseFloat(quantiteNette?.value) || 0;
        const prix = parseFloat(prixUnitaire?.value) || 0;
        const total = quantite * prix;
        montantTotalSpan.textContent = total.toLocaleString('fr-FR') + ' CFA';
        
        const deduction = parseFloat(montantDeduit?.value) || 0;
        if (montantDeduitAffiche) montantDeduitAffiche.textContent = deduction.toLocaleString('fr-FR') + ' CFA';
        
        const net = total - deduction;
        netPayerSpan.textContent = net.toLocaleString('fr-FR') + ' CFA';
        
        // Vérifier que la déduction ne dépasse pas le total
        if (deduction > total && montantDeduit) {
            montantDeduit.value = total;
            calculerMontant();
        }
    }
    
    if (quantiteNette) quantiteNette.addEventListener('input', calculerMontant);
    if (prixUnitaire) prixUnitaire.addEventListener('input', calculerMontant);
    if (montantDeduit) montantDeduit.addEventListener('input', calculerMontant);
    
    // Calcul initial
    calculerMontant();
</script>
@endsection