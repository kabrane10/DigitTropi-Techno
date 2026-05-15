@extends('layouts.admin')

@section('title', 'Nouvelle distribution d\'intrants')
@section('header', 'Distribuer des intrants agricoles')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.distributions-intrants.store') }}" method="POST">
        @csrf
        
        <!-- Sélecteur bénéficiaire (Producteur ou Coopérative) -->
        @include('admin.partials._beneficiaire_selector', [
            'producteurs' => $producteurs,
            'cooperatives' => $cooperatives,
            'beneficiaire_type' => old('beneficiaire_type', $beneficiaire_type ?? 'producteur'),
            'producteur_id' => $producteur_id ?? null,
            'cooperative_id' => $cooperative_id ?? null
        ])
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code distribution (auto-généré) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code distribution
                </label>
                <input type="text" value="Généré automatiquement" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Date de distribution -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date de distribution *
                </label>
                <input type="date" name="date_distribution" required value="{{ old('date_distribution', date('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Intrant -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-flask text-primary mr-1"></i> Intrant *
                </label>
                <select name="intrant_id" id="intrant_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez un intrant --</option>
                    @foreach($intrants as $intrant)
                    <option value="{{ $intrant->id }}" 
                            data-prix="{{ $intrant->prix_unitaire }}"
                            data-unite="{{ $intrant->unite }}"
                            data-type="{{ $intrant->type }}"
                            {{ old('intrant_id') == $intrant->id ? 'selected' : '' }}>
                        {{ $intrant->nom }} ({{ $intrant->type_label }}) - 
                        {{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA/{{ $intrant->unite }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Zone -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Zone de livraison *
                </label>
                <select name="zone" id="zone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une zone --</option>
                    @foreach($zones as $zone)
                    <option value="{{ $zone }}" {{ old('zone') == $zone ? 'selected' : '' }}>
                         {{ $zone }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1" id="stock-info"></p>
            </div>
            
            <!-- Quantité -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité *
                </label>
                <div class="flex">
                    <input type="number" step="0.01" name="quantite" id="quantite" required 
                           value="{{ old('quantite') }}"
                           class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-primary"
                           placeholder="Quantité à distribuer">
                    <span class="px-3 py-2 bg-gray-100 border border-l-0 rounded-r-lg" id="unite_label">kg</span>
                </div>
                <p class="text-xs text-gray-500 mt-1" id="stock_restant"></p>
            </div>
            
            <!-- Prix unitaire (auto-calculé) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-tag text-primary mr-1"></i> Prix unitaire (CFA)
                </label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" readonly
                       value="{{ old('prix_unitaire', 0) }}"
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">Prix automatique selon l'intrant sélectionné</p>
            </div>
            
            <!-- Montant total (auto-calculé) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calculator text-primary mr-1"></i> Montant total (CFA)
                </label>
                <input type="number" name="montant_total" id="montant_total" readonly
                       value="{{ old('montant_total', 0) }}"
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Crédit associé -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Crédit associé (optionnel)
                </label>
                <select name="credit_id" id="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Aucun crédit --</option>
                    @foreach($credits as $credit)
                    <option value="{{ $credit->id }}" {{ old('credit_id') == $credit->id ? 'selected' : '' }}>
                        {{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA restant
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Notes -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Notes
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires sur la distribution...">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3">Récapitulatif de la distribution</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Bénéficiaire</p>
                    <p class="font-semibold text-purple-600" id="recap_beneficiaire">-</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Intrant</p>
                    <p class="font-semibold" id="recap_intrant">-</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="font-semibold text-primary" id="recap_quantite">0</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="font-semibold text-blue-600" id="recap_montant">0 CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.distributions-intrants.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Enregistrer la distribution
            </button>
        </div>
    </form>
</div>

<script>
    // Éléments du DOM
    const intrantSelect = document.getElementById('intrant_id');
    const zoneSelect = document.getElementById('zone');
    const quantiteInput = document.getElementById('quantite');
    const prixUnitaireInput = document.getElementById('prix_unitaire');
    const montantTotalInput = document.getElementById('montant_total');
    const uniteLabel = document.getElementById('unite_label');
    const stockInfo = document.getElementById('stock-info');
    const stockRestant = document.getElementById('stock_restant');
    const recapBeneficiaire = document.getElementById('recap_beneficiaire');
    const recapIntrant = document.getElementById('recap_intrant');
    const recapQuantite = document.getElementById('recap_quantite');
    const recapMontant = document.getElementById('recap_montant');
    
    // Récupérer le nom du bénéficiaire depuis le sélecteur
    function updateBeneficiaireRecap() {
        const producteurSection = document.getElementById('producteur-section');
        const cooperativeSection = document.getElementById('cooperative-section');
        
        if (producteurSection && producteurSection.style.display !== 'none') {
            const producteurSelect = document.querySelector('select[name="producteur_id"]');
            const selected = producteurSelect?.options[producteurSelect.selectedIndex];
            if (selected && selected.value) {
                recapBeneficiaire.textContent = selected.text.split('-')[0].trim();
                recapBeneficiaire.classList.add('text-green-600');
            } else {
                recapBeneficiaire.textContent = '-';
            }
        } else if (cooperativeSection && cooperativeSection.style.display !== 'none') {
            const cooperativeSelect = document.querySelector('select[name="cooperative_id"]');
            const selected = cooperativeSelect?.options[cooperativeSelect.selectedIndex];
            if (selected && selected.value) {
                recapBeneficiaire.textContent = selected.text.split('-')[0].trim();
                recapBeneficiaire.classList.add('text-purple-600');
            } else {
                recapBeneficiaire.textContent = '-';
            }
        }
    }
    
    // Données des stocks par zone (passées depuis le contrôleur)
    const stocksData = @json($stocks);
    const zones = @json($zones);
    
    // Mettre à jour les informations quand l'intrant change
    function updateIntrantInfo() {
        const selected = intrantSelect.options[intrantSelect.selectedIndex];
        const prix = parseFloat(selected.dataset.prix) || 0;
        const unite = selected.dataset.unite || 'kg';
        const nom = selected.text.split('-')[0].trim();
        
        prixUnitaireInput.value = prix.toLocaleString();
        uniteLabel.textContent = unite;
        recapIntrant.textContent = nom;
        
        updateStockInfo();
        calculateMontant();
    }
    
    // Mettre à jour les informations de stock
    function updateStockInfo() {
        const intrantId = intrantSelect.value;
        const zone = zoneSelect.value;
        
        if (!intrantId || !zone) {
            stockInfo.innerHTML = '';
            stockRestant.innerHTML = '';
            return;
        }
        
        const stockDisponible = stocksData[intrantId]?.[zone] || 0;
        const unite = intrantSelect.options[intrantSelect.selectedIndex]?.dataset.unite || 'kg';
        
        stockInfo.innerHTML = `<i class="fas fa-boxes mr-1"></i>Stock disponible dans la zone ${zone} : <strong>${stockDisponible.toLocaleString()} ${unite}</strong>`;
        
        const quantite = parseFloat(quantiteInput.value) || 0;
        if (quantite > 0) {
            const reste = stockDisponible - quantite;
            if (reste < 0) {
                stockRestant.innerHTML = `<span class="text-red-500"><i class="fas fa-exclamation-triangle mr-1"></i>Stock insuffisant !</span>`;
                quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            } else {
                stockRestant.innerHTML = `<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Reste après distribution : ${reste.toLocaleString()} ${unite}</span>`;
                quantiteInput.setCustomValidity('');
            }
        } else {
            stockRestant.innerHTML = '';
        }
        
        // Vérifier le seuil d'alerte
        if (stockDisponible <= 50 && stockDisponible > 0) {
            stockInfo.innerHTML += `<span class="ml-2 text-orange-500"><i class="fas fa-bell"></i> Stock critique !</span>`;
        } else if (stockDisponible <= 0) {
            stockInfo.innerHTML += `<span class="ml-2 text-red-500"><i class="fas fa-times-circle"></i> Rupture de stock !</span>`;
        }
    }
    
    // Calculer le montant total
    function calculateMontant() {
        const quantite = parseFloat(quantiteInput.value) || 0;
        const prix = parseFloat(prixUnitaireInput.value?.replace(/\s/g, '')) || 0;
        const montant = quantite * prix;
        
        montantTotalInput.value = montant.toLocaleString();
        recapQuantite.textContent = `${quantite.toLocaleString()} ${uniteLabel.textContent}`;
        recapMontant.textContent = `${montant.toLocaleString()} CFA`;
        
        const intrantId = intrantSelect.value;
        const zone = zoneSelect.value;
        if (intrantId && zone) {
            const stockDisponible = stocksData[intrantId]?.[zone] || 0;
            if (quantite > stockDisponible) {
                quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
                stockRestant.innerHTML = `<span class="text-red-500"><i class="fas fa-exclamation-triangle mr-1"></i>Stock insuffisant !</span>`;
            } else {
                quantiteInput.setCustomValidity('');
                const reste = stockDisponible - quantite;
                stockRestant.innerHTML = `<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Reste après distribution : ${reste.toLocaleString()} ${uniteLabel.textContent}</span>`;
            }
        }
    }
    
    // Écouter les changements du sélecteur de bénéficiaire
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.beneficiaire-radio');
        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                setTimeout(updateBeneficiaireRecap, 100);
            });
        });
        
        const producteurSelect = document.querySelector('select[name="producteur_id"]');
        const cooperativeSelect = document.querySelector('select[name="cooperative_id"]');
        
        if (producteurSelect) producteurSelect.addEventListener('change', updateBeneficiaireRecap);
        if (cooperativeSelect) cooperativeSelect.addEventListener('change', updateBeneficiaireRecap);
        
        updateBeneficiaireRecap();
    });
    
    // Événements
    intrantSelect.addEventListener('change', updateIntrantInfo);
    zoneSelect.addEventListener('change', updateStockInfo);
    quantiteInput.addEventListener('input', () => {
        updateStockInfo();
        calculateMontant();
    });
    
    // Initialisation
    if (intrantSelect.value) updateIntrantInfo();
</script>
@endsection