@extends('layouts.admin')

@section('title', 'Modifier la distribution')
@section('header', 'Modifier une distribution de semences')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.distributions.update', $distribution) }}" method="POST">
        @csrf
        @method('PUT')
        
        @php
            // Déterminer le type de bénéficiaire actuel
            $currentBeneficiaireType = 'producteur';
            $currentProducteurId = $distribution->producteur_id;
            $currentCooperativeId = $distribution->cooperative_id;
            
            if ($distribution->cooperative_id || $distribution->beneficiaire_type === 'App\\Models\\Cooperative') {
                $currentBeneficiaireType = 'cooperative';
                $currentCooperativeId = $distribution->cooperative_id;
                $currentProducteurId = null;
            }
        @endphp
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code distribution (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code distribution
                </label>
                <input type="text" value="{{ $distribution->code_distribution }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Bénéficiaire (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-users text-primary mr-1"></i> Bénéficiaire
                </label>
                <div class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                    @if($currentBeneficiaireType == 'cooperative')
                        <div class="flex items-center">
                            <i class="fas fa-handshake text-purple-600 mr-2"></i>
                            <div>
                                <span class="font-semibold">{{ $distribution->cooperative->nom ?? 'N/A' }}</span>
                                <span class="text-xs text-gray-500 ml-2">(Coopérative)</span>
                                <br>
                                <span class="text-xs text-gray-400">Code: {{ $distribution->cooperative->code_cooperative ?? 'N/A' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center">
                            <i class="fas fa-user text-green-600 mr-2"></i>
                            <div>
                                <span class="font-semibold">{{ $distribution->producteur->nom_complet ?? 'N/A' }}</span>
                                <span class="text-xs text-gray-500 ml-2">(Producteur)</span>
                                <br>
                                <span class="text-xs text-gray-400">Code: {{ $distribution->producteur->code_producteur ?? 'N/A' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Champs cachés pour conserver les IDs -->
                <input type="hidden" name="beneficiaire_type" value="{{ $currentBeneficiaireType }}">
                <input type="hidden" name="producteur_id" value="{{ $currentProducteurId }}">
                <input type="hidden" name="cooperative_id" value="{{ $currentCooperativeId }}">
            </div>
            
            <!-- Semence -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-seedling text-primary mr-1"></i> Semence *
                </label>
                <select name="semence_id" id="semence_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une semence --</option>
                    @foreach($semences as $semence)
                    <option value="{{ $semence->id }}" 
                            data-stock="{{ $semence->stock_disponible + ($distribution->semence_id == $semence->id ? $distribution->quantite : 0) }}"
                            data-unite="{{ $semence->unite }}"
                            data-prix="{{ $semence->prix_unitaire }}"
                            {{ $distribution->semence_id == $semence->id ? 'selected' : '' }}>
                        {{ $semence->nom }} ({{ $semence->variete }}) - Stock: {{ number_format($semence->stock_disponible + ($distribution->semence_id == $semence->id ? $distribution->quantite : 0)) }} {{ $semence->unite }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Quantité -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité *
                </label>
                <div class="flex">
                    <input type="number" step="0.01" name="quantite" id="quantite" required 
                           value="{{ old('quantite', $distribution->quantite) }}"
                           class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-primary">
                    <span class="px-3 py-2 bg-gray-100 border border-l-0 rounded-r-lg" id="unite_label">kg</span>
                </div>
                <p id="stock_info" class="text-xs text-gray-500 mt-1"></p>
            </div>
            
            <!-- Prix unitaire (automatique) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-tag text-primary mr-1"></i> Prix unitaire (CFA)
                </label>
                <input type="number" name="prix_unitaire" id="prix_unitaire" readonly
                       value="{{ old('prix_unitaire', $distribution->prix_unitaire ?? $distribution->semence->prix_unitaire ?? 0) }}"
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">Prix automatique selon la semence sélectionnée</p>
            </div>
            
            <!-- Montant total (calculé) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calculator text-primary mr-1"></i> Montant total (CFA)
                </label>
                <input type="number" name="montant_total" id="montant_total" readonly
                       value="{{ old('montant_total', $distribution->montant_total ?? ($distribution->quantite * ($distribution->prix_unitaire ?? $distribution->semence->prix_unitaire ?? 0))) }}"
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Superficie emblavée -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marked-alt text-primary mr-1"></i> Superficie emblavée (ha) *
                </label>
                <input type="number" step="0.01" name="superficie_emblevee" id="superficie" required 
                       value="{{ old('superficie_emblevee', $distribution->superficie_emblevee) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 2.5">
            </div>
            
            <!-- Rendement estimé -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Rendement estimé (kg/ha)
                </label>
                <input type="number" step="0.01" name="rendement_estime" id="rendement_estime" 
                       value="{{ old('rendement_estime', $distribution->rendement_estime) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 2500">
                <p class="text-xs text-gray-500 mt-1">Estimation du rendement attendu par hectare</p>
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
            
            <!-- Saison -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-cloud-sun text-primary mr-1"></i> Saison *
                </label>
                <select name="saison" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="principale" {{ $distribution->saison == 'principale' ? 'selected' : '' }}>☀️ Principale (Juin - Septembre)</option>
                    <option value="contre-saison" {{ $distribution->saison == 'contre-saison' ? 'selected' : '' }}>☀️ Contre-saison (Octobre - Décembre)</option>
                    <option value="hivernage" {{ $distribution->saison == 'hivernage' ? 'selected' : '' }}>☀️ Hivernage (Janvier - Mai)</option>
                </select>
            </div>
            
            <!-- Crédit associé -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Crédit associé (optionnel)
                </label>
                <select name="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Aucun crédit --</option>
                    @foreach($credits as $credit)
                    <option value="{{ $credit->id }}" {{ $distribution->credit_id == $credit->id ? 'selected' : '' }}>
                        {{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA restant
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Observations -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Observations
                </label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires...">{{ old('observations', $distribution->observations) }}</textarea>
            </div>
        </div>
        
        <!-- Message d'avertissement si modification majeure -->
        @if($distribution->credit_id)
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">⚠️ Attention</p>
                    <p class="text-sm text-yellow-700">
                        Cette distribution est liée à un crédit. La modification de la quantité ou du prix unitaire affectera le montant total et le crédit associé.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3">Récapitulatif de la distribution</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Bénéficiaire</p>
                    <p class="font-semibold" id="recap_beneficiaire">
                        @if($currentBeneficiaireType == 'cooperative')
                            {{ $distribution->cooperative->nom ?? 'N/A' }}
                        @else
                            {{ $distribution->producteur->nom_complet ?? 'N/A' }}
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Semence</p>
                    <p class="font-semibold" id="recap_semence">{{ $distribution->semence->nom ?? '-' }} ({{ $distribution->semence->variete ?? '-' }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="font-semibold text-primary" id="recap_quantite">{{ number_format($distribution->quantite) }} {{ $distribution->semence->unite ?? 'kg' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="font-semibold text-blue-600" id="recap_montant">{{ number_format($distribution->montant_total ?? ($distribution->quantite * ($distribution->prix_unitaire ?? $distribution->semence->prix_unitaire ?? 0)), 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Superficie</p>
                    <p class="font-semibold" id="recap_superficie">{{ number_format($distribution->superficie_emblevee, 2) }} ha</p>
                </div>
            </div>
            
            <!-- Production totale estimée -->
            <div class="mt-3 pt-3 border-t border-green-200">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-semibold text-dark"> Production totale estimée :</p>
                    <p class="text-lg font-bold text-primary" id="recap_production_totale">
                        @php
                            $productionTotale = ($distribution->superficie_emblevee ?? 0) * ($distribution->rendement_estime ?? 0);
                        @endphp
                        {{ number_format($productionTotale) }} kg
                    </p>
                </div>
                <p class="text-xs text-gray-500 mt-1">Calculé à partir de la superficie × rendement estimé</p>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.distributions.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    const semenceSelect = document.getElementById('semence_id');
    const quantiteInput = document.getElementById('quantite');
    const prixUnitaireInput = document.getElementById('prix_unitaire');
    const montantTotalInput = document.getElementById('montant_total');
    const uniteLabel = document.getElementById('unite_label');
    const stockInfo = document.getElementById('stock_info');
    
    const recapSemence = document.getElementById('recap_semence');
    const recapQuantite = document.getElementById('recap_quantite');
    const recapMontant = document.getElementById('recap_montant');
    const recapSuperficie = document.getElementById('recap_superficie');
    const recapProductionTotale = document.getElementById('recap_production_totale');
    
    const superficieInput = document.getElementById('superficie');
    const rendementInput = document.getElementById('rendement_estime');
    
    // Stock initial (avec restauration de l'ancienne quantité pour le calcul)
    let originalSemenceId = "{{ $distribution->semence_id }}";
    let originalQuantite = {{ $distribution->quantite }};
    
    // Mettre à jour les infos quand on sélectionne une semence
    function updateSemenceInfo() {
        const selected = semenceSelect.options[semenceSelect.selectedIndex];
        
        if (!selected || !selected.value) {
            prixUnitaireInput.value = '';
            montantTotalInput.value = '';
            return;
        }
        
        const stock = parseFloat(selected.dataset.stock);
        const unite = selected.dataset.unite || 'kg';
        const prix = parseFloat(selected.dataset.prix) || 0;
        
        prixUnitaireInput.value = prix;
        
        if (stock !== undefined) {
            stockInfo.textContent = `Stock disponible: ${Number(stock).toLocaleString()} ${unite}`;
            uniteLabel.textContent = unite;
            checkQuantity(stock);
        }
        
        recapSemence.textContent = selected.textContent.split('-')[0].trim() || '-';
        calculateMontant();
        updateProductionTotale();
    }
    
    // Vérifier la quantité par rapport au stock
    function checkQuantity(stock) {
        const quantite = parseFloat(quantiteInput.value) || 0;
        if (stock !== undefined && quantite > stock) {
            quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            if (stockInfo) stockInfo.classList.add('text-red-500');
        } else {
            quantiteInput.setCustomValidity('');
            if (stockInfo) stockInfo.classList.remove('text-red-500');
        }
    }
    
    // Calculer le montant total
    function calculateMontant() {
        const quantite = parseFloat(quantiteInput.value) || 0;
        const prix = parseFloat(prixUnitaireInput.value) || 0;
        const montant = quantite * prix;
        
        montantTotalInput.value = montant > 0 ? montant.toFixed(2) : '';
        recapQuantite.textContent = `${quantite.toLocaleString()} ${uniteLabel.textContent}`;
        recapMontant.textContent = `${montant.toLocaleString()} CFA`;
        
        // Vérifier le stock avec la semence actuelle
        const selected = semenceSelect.options[semenceSelect.selectedIndex];
        if (selected && selected.value) {
            const stock = parseFloat(selected.dataset.stock);
            checkQuantity(stock);
        }
    }
    
    // Mettre à jour la production totale estimée
    function updateProductionTotale() {
        const superficie = parseFloat(superficieInput?.value) || 0;
        const rendement = parseFloat(rendementInput?.value) || 0;
        const productionTotale = superficie * rendement;
        
        if (recapProductionTotale) {
            if (productionTotale > 0) {
                recapProductionTotale.textContent = `${productionTotale.toLocaleString()} kg`;
                recapProductionTotale.classList.add('text-green-600');
            } else {
                recapProductionTotale.textContent = '0 kg';
                recapProductionTotale.classList.remove('text-green-600');
            }
        }
        
        if (recapSuperficie) {
            recapSuperficie.textContent = `${superficie.toLocaleString()} ha`;
        }
    }
    
    // Écouteurs d'événements
    if (semenceSelect) semenceSelect.addEventListener('change', updateSemenceInfo);
    if (quantiteInput) quantiteInput.addEventListener('input', calculateMontant);
    if (superficieInput) superficieInput.addEventListener('input', updateProductionTotale);
    if (rendementInput) rendementInput.addEventListener('input', updateProductionTotale);
    
    // Initialisation
    updateSemenceInfo();
    updateProductionTotale();
</script>
@endsection