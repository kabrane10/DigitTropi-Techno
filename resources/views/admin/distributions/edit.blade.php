@extends('layouts.admin')

@section('title', 'Modifier la distribution')
@section('header', 'Modifier une distribution de semences')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.distributions.update', $distribution) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code distribution (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code distribution
                </label>
                <input type="text" value="{{ $distribution->code_distribution }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Producteur (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user text-primary mr-1"></i> Producteur
                </label>
                <input type="text" value="{{ $distribution->producteur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Semence -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-seedling text-primary mr-1"></i> Semence *
                </label>
                <select name="semence_id" id="semence_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une semence --</option>
                    @foreach($semences as $semence)
                    <option value="{{ $semence->id }}" {{ $distribution->semence_id == $semence->id ? 'selected' : '' }} 
                            data-stock="{{ $semence->stock_disponible + ($distribution->semence_id == $semence->id ? $distribution->quantite : 0) }}"
                            data-unite="{{ $semence->unite }}">
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
                <p id="stockInfo" class="text-xs text-gray-500 mt-1"></p>
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
            
            <!-- Rendement estimé (NOUVEAU) -->
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
                    <option value="principale" {{ $distribution->saison == 'principale' ? 'selected' : '' }}> Principale (Juin - Septembre)</option>
                    <option value="contre-saison" {{ $distribution->saison == 'contre-saison' ? 'selected' : '' }}>☀️ Contre-saison (Octobre - Décembre)</option>
                    <option value="hivernage" {{ $distribution->saison == 'hivernage' ? 'selected' : '' }}> Hivernage (Janvier - Mai)</option>
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
                        {{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA
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
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3"> Récapitulatif de la distribution</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Producteur</p>
                    <p class="font-semibold" id="recap_producteur">{{ $distribution->producteur->nom_complet }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Semence</p>
                    <p class="font-semibold" id="recap_semence">{{ $distribution->semence->nom }} ({{ $distribution->semence->variete }})</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="font-semibold text-primary" id="recap_quantite">{{ number_format($distribution->quantite) }} kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Superficie</p>
                    <p class="font-semibold" id="recap_superficie">{{ number_format($distribution->superficie_emblevee, 2) }} ha</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Rendement estimé</p>
                    <p class="font-semibold text-blue-600" id="recap_rendement">
                        @if($distribution->rendement_estime)
                            {{ number_format($distribution->rendement_estime) }} kg/ha
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
            
            <!-- Production totale estimée -->
            <div class="mt-3 pt-3 border-t border-green-200">
                <div class="flex justify-between items-center">
                    <p class="text-sm font-semibold text-dark"> Production totale estimée :</p>
                    <p class="text-lg font-bold text-primary" id="recap_production_totale">
                        @php
                            $productionTotale = $distribution->superficie_emblevee * ($distribution->rendement_estime ?? 0);
                        @endphp
                        {{ number_format($productionTotale) }} kg
                    </p>
                </div>
                <p class="text-xs text-gray-500 mt-1">Calculé à partir de la superficie × rendement estimé</p>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.distributions.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    const semenceSelect = document.getElementById('semence_id');
    const quantiteInput = document.getElementById('quantite');
    const uniteLabel = document.getElementById('unite_label');
    const stockInfo = document.getElementById('stockInfo');
    const recapSemence = document.getElementById('recap_semence');
    const recapQuantite = document.getElementById('recap_quantite');
    const recapSuperficie = document.getElementById('recap_superficie');
    const recapRendement = document.getElementById('recap_rendement');
    const recapProductionTotale = document.getElementById('recap_production_totale');
    const superficieInput = document.getElementById('superficie');
    const rendementInput = document.getElementById('rendement_estime');
    
    // Mettre à jour les infos quand on sélectionne une semence
    function updateSemenceInfo() {
        const selected = semenceSelect.options[semenceSelect.selectedIndex];
        const stock = selected.dataset.stock;
        const unite = selected.dataset.unite || 'kg';
        
        if (stock) {
            stockInfo.textContent = `Stock disponible : ${Number(stock).toLocaleString()} ${unite}`;
            uniteLabel.textContent = unite;
            
            if (quantiteInput.value > stock) {
                quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            } else {
                quantiteInput.setCustomValidity('');
            }
        }
        
        recapSemence.textContent = selected.textContent.split('-')[0].trim() || '-';
        updateProductionTotale();
    }
    
    // Vérifier la quantité
    function checkQuantity() {
        const selected = semenceSelect.options[semenceSelect.selectedIndex];
        const stock = selected.dataset.stock;
        
        if (stock && quantiteInput.value > stock) {
            quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            stockInfo.classList.add('text-red-500');
        } else {
            quantiteInput.setCustomValidity('');
            stockInfo.classList.remove('text-red-500');
        }
        
        recapQuantite.textContent = `${Number(quantiteInput.value).toLocaleString()} ${uniteLabel.textContent}`;
    }
    
    // Mettre à jour le récapitulatif superficie
    function updateSuperficie() {
        recapSuperficie.textContent = `${Number(superficieInput.value).toLocaleString()} ha`;
        updateProductionTotale();
    }
    
    // Mettre à jour le récapitulatif rendement
    function updateRendement() {
        if (rendementInput.value) {
            recapRendement.textContent = `${Number(rendementInput.value).toLocaleString()} kg/ha`;
        } else {
            recapRendement.textContent = '-';
        }
        updateProductionTotale();
    }
    
    // Calculer et afficher la production totale estimée
    function updateProductionTotale() {
        const superficie = parseFloat(superficieInput.value) || 0;
        const rendement = parseFloat(rendementInput.value) || 0;
        const productionTotale = superficie * rendement;
        
        if (productionTotale > 0) {
            recapProductionTotale.textContent = `${productionTotale.toLocaleString()} kg`;
            recapProductionTotale.classList.add('text-green-600');
        } else {
            recapProductionTotale.textContent = '0 kg';
        }
    }
    
    // Événements
    semenceSelect.addEventListener('change', updateSemenceInfo);
    quantiteInput.addEventListener('input', checkQuantity);
    superficieInput.addEventListener('input', updateSuperficie);
    rendementInput.addEventListener('input', updateRendement);
    
    // Initialisation
    updateSemenceInfo();
</script>
@endsection