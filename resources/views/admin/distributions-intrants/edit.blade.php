@extends('layouts.admin')

@section('title', 'Modifier la distribution d\'intrants')
@section('header', 'Modifier une distribution d\'intrants agricoles')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.distributions-intrants.update', $distribution) }}" method="POST">
        @csrf
        @method('PUT')
        
        @php
            // Déterminer le type de bénéficiaire actuel
            $currentBeneficiaireType = 'producteur';
            if ($distribution->cooperative_id || $distribution->beneficiaire_type === 'App\\Models\\Cooperative') {
                $currentBeneficiaireType = 'cooperative';
            }
        @endphp
        
        <!-- Message d'avertissement si modification dangereuse -->
        @if($distribution->credit_id)
        <div class="mb-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">⚠️ Attention - Crédit associé</p>
                    <p class="text-sm text-yellow-700">
                        Cette distribution est liée à un crédit ({{ $distribution->credit->code_credit }}). 
                        La modification de la quantité ou du prix affectera le montant restant du crédit.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Message d'information sur le stock -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-blue-800">📦 Gestion du stock</p>
                    <p class="text-sm text-blue-700">
                        La modification de la quantité ajustera automatiquement le stock dans la zone concernée.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Sélecteur bénéficiaire (lecture seule - on ne peut pas changer le bénéficiaire) -->
        <div class="mb-6">
            <label class="block text-sm font-semibold mb-3">
                <i class="fas fa-users text-primary mr-1"></i> Bénéficiaire
            </label>
            <div class="bg-gray-100 rounded-lg p-4">
                @if($currentBeneficiaireType == 'cooperative')
                    <div class="flex items-center">
                        <i class="fas fa-handshake text-purple-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $distribution->cooperative->nom }}</p>
                            <p class="text-sm text-gray-500">Code: {{ $distribution->cooperative->code_cooperative }} - Coopérative</p>
                        </div>
                    </div>
                    <input type="hidden" name="beneficiaire_type" value="cooperative">
                    <input type="hidden" name="cooperative_id" value="{{ $distribution->cooperative_id }}">
                @else
                    <div class="flex items-center">
                        <i class="fas fa-user text-green-600 text-2xl mr-3"></i>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $distribution->producteur->nom_complet }}</p>
                            <p class="text-sm text-gray-500">Code: {{ $distribution->producteur->code_producteur }} - Producteur</p>
                        </div>
                    </div>
                    <input type="hidden" name="beneficiaire_type" value="producteur">
                    <input type="hidden" name="producteur_id" value="{{ $distribution->producteur_id }}">
                @endif
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code distribution (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code distribution
                </label>
                <input type="text" value="{{ $distribution->code_distribution }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Date de distribution -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date de distribution *
                </label>
                <input type="date" name="date_distribution" required 
                       value="{{ old('date_distribution', $distribution->date_distribution->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Intrant (lecture seule - ne peut pas changer car impact stock) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-flask text-primary mr-1"></i> Intrant
                </label>
                <input type="text" value="{{ $distribution->intrant->nom }} ({{ $distribution->intrant->type_label }})" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <input type="hidden" name="intrant_id" value="{{ $distribution->intrant_id }}">
            </div>
            
            <!-- Zone (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Zone de livraison
                </label>
                <input type="text" value="{{ $distribution->zone }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <input type="hidden" name="zone" value="{{ $distribution->zone }}">
            </div>
            
            <!-- Quantité -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité *
                </label>
                <div class="flex">
                    <input type="number" step="0.01" name="quantite" id="quantite" required 
                           value="{{ old('quantite', $distribution->quantite) }}"
                           class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-primary"
                           placeholder="Quantité à distribuer">
                    <span class="px-3 py-2 bg-gray-100 border border-l-0 rounded-r-lg">{{ $distribution->intrant->unite }}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1" id="stock-info"></p>
                <p class="text-xs text-gray-500 mt-1" id="stock_restant"></p>
            </div>
            
            <!-- Prix unitaire (auto-calculé, lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-tag text-primary mr-1"></i> Prix unitaire (CFA)
                </label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" readonly
                       value="{{ old('prix_unitaire', $distribution->prix_unitaire) }}"
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">Prix selon l'intrant (non modifiable)</p>
            </div>
            
            <!-- Montant total (auto-calculé) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calculator text-primary mr-1"></i> Montant total (CFA)
                </label>
                <input type="number" name="montant_total" id="montant_total" readonly
                       value="{{ old('montant_total', $distribution->montant_total) }}"
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Crédit associé (lecture seule si déjà associé) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Crédit associé
                </label>
                @if($distribution->credit_id)
                    <input type="text" value="{{ $distribution->credit->code_credit }} - {{ number_format($distribution->credit->montant_restant, 0, ',', ' ') }} CFA restant" disabled
                           class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                    <input type="hidden" name="credit_id" value="{{ $distribution->credit_id }}">
                    <p class="text-xs text-yellow-600 mt-1">
                        <i class="fas fa-info-circle mr-1"></i>Le crédit ne peut pas être changé après création
                    </p>
                @else
                    <select name="credit_id" id="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <option value="">-- Aucun crédit --</option>
                        @foreach($credits as $credit)
                        <option value="{{ $credit->id }}" {{ old('credit_id', $distribution->credit_id) == $credit->id ? 'selected' : '' }}>
                            {{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA restant
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>
            
            <!-- Notes -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Notes
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires sur la distribution...">{{ old('notes', $distribution->notes) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3">📊 Récapitulatif de la distribution</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Bénéficiaire</p>
                    <p class="font-semibold {{ $currentBeneficiaireType == 'cooperative' ? 'text-purple-600' : 'text-green-600' }}">
                        {{ $currentBeneficiaireType == 'cooperative' ? $distribution->cooperative->nom : $distribution->producteur->nom_complet }}
                    </p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Intrant</p>
                    <p class="font-semibold">{{ $distribution->intrant->nom }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="font-semibold text-primary" id="recap_quantite">{{ number_format($distribution->quantite, 2) }} {{ $distribution->intrant->unite }}</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="font-semibold text-blue-600" id="recap_montant">{{ number_format($distribution->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.distributions-intrants.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    // Éléments du DOM
    const quantiteInput = document.getElementById('quantite');
    const prixUnitaireInput = document.getElementById('prix_unitaire');
    const montantTotalInput = document.getElementById('montant_total');
    const recapQuantite = document.getElementById('recap_quantite');
    const recapMontant = document.getElementById('recap_montant');
    const stockInfo = document.getElementById('stock-info');
    const stockRestant = document.getElementById('stock_restant');
    
    // Données des stocks par zone (passées depuis le contrôleur)
    const stocksData = @json($stocks);
    const intrantId = {{ $distribution->intrant_id }};
    const zone = '{{ $distribution->zone }}';
    const quantiteOriginale = {{ $distribution->quantite }};
    const unite = '{{ $distribution->intrant->unite }}';
    
    // Mettre à jour les informations de stock
    function updateStockInfo() {
        const stockDisponible = (stocksData[intrantId]?.[zone] || 0) + quantiteOriginale;
        const quantite = parseFloat(quantiteInput.value) || 0;
        
        stockInfo.innerHTML = `<i class="fas fa-boxes mr-1"></i>Stock actuel dans la zone ${zone} : <strong>${stockDisponible.toLocaleString()} ${unite}</strong>`;
        
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
        const prix = parseFloat(prixUnitaireInput.value) || 0;
        const montant = quantite * prix;
        
        montantTotalInput.value = montant.toLocaleString();
        recapQuantite.textContent = `${quantite.toLocaleString()} ${unite}`;
        recapMontant.textContent = `${montant.toLocaleString()} CFA`;
        
        // Vérifier le stock
        const stockDisponible = (stocksData[intrantId]?.[zone] || 0) + quantiteOriginale;
        if (quantite > stockDisponible) {
            quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            stockRestant.innerHTML = `<span class="text-red-500"><i class="fas fa-exclamation-triangle mr-1"></i>Stock insuffisant !</span>`;
        } else {
            quantiteInput.setCustomValidity('');
            const reste = stockDisponible - quantite;
            stockRestant.innerHTML = `<span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Reste après distribution : ${reste.toLocaleString()} ${unite}</span>`;
        }
    }
    
    // Événements
    quantiteInput.addEventListener('input', () => {
        updateStockInfo();
        calculateMontant();
    });
    
    // Initialisation
    updateStockInfo();
    calculateMontant();
</script>
@endsection