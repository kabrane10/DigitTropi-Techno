@extends('layouts.admin')

@section('title', 'Modifier estimation')
@section('header', 'Modifier la fiche d\'estimation de besoin')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.estimations.update', $estimation) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code estimation (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code estimation
                </label>
                <input type="text" value="{{ $estimation->code_estimation }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <input type="hidden" name="code_estimation" value="{{ $estimation->code_estimation }}">
            </div>
            
            <!-- Producteur (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user text-primary mr-1"></i> Producteur
                </label>
                <input type="text" value="{{ $estimation->producteur->nom_complet }} ({{ $estimation->producteur->code_producteur }})" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <input type="hidden" name="producteur_id" value="{{ $estimation->producteur_id }}">
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
                            data-prix="{{ $semence->prix_unitaire }}"
                            {{ $estimation->semence_id == $semence->id ? 'selected' : '' }}>
                        {{ $semence->nom }} ({{ $semence->variete }}) - {{ number_format($semence->prix_unitaire, 0, ',', ' ') }} CFA/{{ $semence->unite }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Quantité estimée -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité estimée *
                </label>
                <input type="number" step="0.01" name="quantite_estimee" id="quantite_estimee" required 
                       value="{{ old('quantite_estimee', $estimation->quantite_estimee) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Superficie prévue -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marked-alt text-primary mr-1"></i> Superficie prévue (ha) *
                </label>
                <input type="number" step="0.01" name="superficie_prevue" required 
                       value="{{ old('superficie_prevue', $estimation->superficie_prevue) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Crédit estimé -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Montant crédit estimé (CFA)
                </label>
                <input type="number" step="1000" name="credit_montant" 
                       value="{{ old('credit_montant', $estimation->credit_montant) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Date estimation -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date estimation *
                </label>
                <input type="date" name="date_estimation" required 
                       value="{{ old('date_estimation', $estimation->date_estimation->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>

            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Statut
                </label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="en_attente" {{ $estimation->statut == 'en_attente' ? 'selected' : '' }}> En attente</option>
                    <option value="approuve" {{ $estimation->statut == 'approuve' ? 'selected' : '' }}> Approuvé</option>
                    <option value="rejete" {{ $estimation->statut == 'rejete' ? 'selected' : '' }}> Rejeté</option>
                </select>
            </div>
        </div>

        <!-- Section pour les intrants -->
        <div class="mt-6 pt-6 border-t">
            <h3 class="text-lg font-semibold mb-4">Besoins en intrants</h3>
            <div id="intrants-container">
                @php
                    $intrants = is_string($estimation->intrants) ? json_decode($estimation->intrants, true) : ($estimation->intrants ?? []);
                @endphp
                @foreach($intrants as $index => $intrant)
                    <div class="intrant-item grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold mb-1">Intrant *</label>
                            <select name="intrants[{{ $index }}][intrant_id]" class="intrant-select w-full px-3 py-2 border rounded-lg text-sm" required>
                                <option value="">-- Sélectionnez un intrant --</option>
                                @foreach($intrants_disponibles as $intrantDispo)
                                <option value="{{ $intrantDispo->id }}" 
                                        data-prix="{{ $intrantDispo->prix_unitaire }}"
                                        data-unite="{{ $intrantDispo->unite }}"
                                        {{ ($intrant['intrant_id'] ?? '') == $intrantDispo->id ? 'selected' : '' }}>
                                    {{ $intrantDispo->nom }} ({{ $intrantDispo->type }}) - {{ number_format($intrantDispo->prix_unitaire, 0, ',', ' ') }} CFA/{{ $intrantDispo->unite }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Quantité *</label>
                            <input type="number" step="0.01" name="intrants[{{ $index }}][quantite]" value="{{ $intrant['quantite'] ?? '' }}" class="intrant-quantite w-full px-3 py-2 border rounded-lg text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Unité</label>
                            <input type="text" name="intrants[{{ $index }}][unite]" value="{{ $intrant['unite'] ?? '' }}" class="intrant-unite w-full px-3 py-2 border rounded-lg text-sm bg-gray-100" readonly>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Coût estimé (CFA)</label>
                            <input type="number" step="100" name="intrants[{{ $index }}][cout_estime]" value="{{ $intrant['cout_estime'] ?? '' }}" class="intrant-cout w-full px-3 py-2 border rounded-lg text-sm bg-gray-100" readonly>
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-intrant bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-intrant" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                <i class="fas fa-plus mr-2"></i>Ajouter un intrant
            </button>
        </div>

        <!-- Section pour les coûts -->
        <div class="mt-6 pt-6 border-t">
            <h3 class="text-lg font-semibold mb-4">Estimation des coûts</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="cout_semences" class="block text-sm font-semibold mb-2">Coût des semences (CFA)</label>
                    <input type="number" id="cout_semences" name="cout_semences" step="100" value="{{ old('cout_semences', $estimation->cout_semences) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                </div>
                <div>
                    <label for="cout_intrants" class="block text-sm font-semibold mb-2">Coût des intrants (CFA)</label>
                    <input type="number" id="cout_intrants" name="cout_intrants" value="{{ old('cout_intrants', $estimation->cout_intrants) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                </div>
                <div>
                    <label for="autres_frais" class="block text-sm font-semibold mb-2">Autres frais (CFA)</label>
                    <input type="number" id="autres_frais" name="autres_frais" step="100" value="{{ old('autres_frais', $estimation->autres_frais) }}" class="w-full px-4 py-2 border rounded-lg">
                </div>
                <div>
                    <label for="total_estimation" class="block text-sm font-semibold mb-2">Total Estimation (CFA)</label>
                    <input type="number" id="total_estimation" name="total_estimation" value="{{ old('total_estimation', $estimation->total_estimation) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2 italic">
                <i class="fas fa-info-circle"></i> Le coût des semences et intrants est calculé automatiquement à partir des prix unitaires définis.
            </p>
        </div>
        
        <!-- Observations -->
        <div class="mt-6">
            <label class="block text-sm font-semibold mb-2">
                <i class="fas fa-comment text-primary mr-1"></i> Observations
            </label>
            <textarea name="observations" rows="3" 
                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                      placeholder="Informations complémentaires...">{{ old('observations', $estimation->observations) }}</textarea>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.estimations.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('intrants-container');
    const addButton = document.getElementById('add-intrant');
    let intrantIndex = {{ is_array($intrants) ? count($intrants) : 0 }};

    // --- DOM Elements for cost calculation ---
    const semenceSelect = document.getElementById('semence_id');
    const quantiteEstimeeInput = document.getElementById('quantite_estimee');
    const coutSemencesInput = document.getElementById('cout_semences');
    const coutIntrantsInput = document.getElementById('cout_intrants');
    const autresFraisInput = document.getElementById('autres_frais');
    const totalEstimationInput = document.getElementById('total_estimation');

    // --- Functions ---
    
    // Calculer le coût des semences
    function calculateSemenceCost() {
        const selectedOption = semenceSelect.options[semenceSelect.selectedIndex];
        const prixUnitaire = selectedOption ? parseFloat(selectedOption.dataset.prix) || 0 : 0;
        const quantite = parseFloat(quantiteEstimeeInput.value) || 0;
        
        const coutSemences = prixUnitaire * quantite;
        coutSemencesInput.value = coutSemences.toFixed(2);
        
        calculateTotalEstimation();
    }
    
    // Calculer le coût total des intrants
    function calculateTotalIntrantsCost() {
        let total = 0;
        const intrantRows = container.querySelectorAll('.intrant-item');
        
        intrantRows.forEach(row => {
            const intrantSelect = row.querySelector('.intrant-select');
            const quantiteInput = row.querySelector('.intrant-quantite');
            
            if (intrantSelect && intrantSelect.value && quantiteInput.value) {
                const prixUnitaire = parseFloat(intrantSelect.options[intrantSelect.selectedIndex]?.dataset.prix) || 0;
                const quantite = parseFloat(quantiteInput.value) || 0;
                const cout = prixUnitaire * quantite;
                total += cout;
                
                // Mettre à jour le champ coût estimé visible
                const coutEstimeInput = row.querySelector('.intrant-cout');
                if (coutEstimeInput) {
                    coutEstimeInput.value = cout.toFixed(2);
                }
            }
        });
        
        coutIntrantsInput.value = total.toFixed(2);
        calculateTotalEstimation();
    }
    
    // Calculer le total général
    function calculateTotalEstimation() {
        const semences = parseFloat(coutSemencesInput.value) || 0;
        const intrants = parseFloat(coutIntrantsInput.value) || 0;
        const autres = parseFloat(autresFraisInput.value) || 0;
        totalEstimationInput.value = (semences + intrants + autres).toFixed(2);
    }
    
    // Mettre à jour le coût d'une ligne d'intrant spécifique
    function updateIntrantCost(row) {
        const intrantSelect = row.querySelector('.intrant-select');
        const quantiteInput = row.querySelector('.intrant-quantite');
        const coutInput = row.querySelector('.intrant-cout');
        const uniteInput = row.querySelector('.intrant-unite');
        
        if (intrantSelect && intrantSelect.value && quantiteInput.value) {
            const selectedOption = intrantSelect.options[intrantSelect.selectedIndex];
            const prixUnitaire = parseFloat(selectedOption.dataset.prix) || 0;
            const unite = selectedOption.dataset.unite || '';
            const quantite = parseFloat(quantiteInput.value) || 0;
            const cout = prixUnitaire * quantite;
            
            coutInput.value = cout.toFixed(2);
            if (uniteInput) uniteInput.value = unite;
        } else {
            coutInput.value = '0';
        }
        
        calculateTotalIntrantsCost();
    }
    
    // Créer une nouvelle ligne d'intrant
    function createIntrantRow(index) {
        const div = document.createElement('div');
        div.className = 'intrant-item grid grid-cols-1 md:grid-cols-6 gap-4 mb-4 p-4 bg-gray-50 rounded-lg';
        
        div.innerHTML = `
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold mb-1">Intrant *</label>
                <select name="intrants[${index}][intrant_id]" class="intrant-select w-full px-3 py-2 border rounded-lg text-sm" required>
                    <option value="">-- Sélectionnez un intrant --</option>
                    @foreach($intrants_disponibles as $intrant)
                    <option value="{{ $intrant->id }}" data-prix="{{ $intrant->prix_unitaire }}" data-unite="{{ $intrant->unite }}">
                        {{ $intrant->nom }} ({{ $intrant->type }}) - {{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA/{{ $intrant->unite }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Quantité *</label>
                <input type="number" step="0.01" name="intrants[${index}][quantite]" class="intrant-quantite w-full px-3 py-2 border rounded-lg text-sm" required>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Unité</label>
                <input type="text" name="intrants[${index}][unite]" class="intrant-unite w-full px-3 py-2 border rounded-lg text-sm bg-gray-100" readonly>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Coût estimé (CFA)</label>
                <input type="number" step="100" name="intrants[${index}][cout_estime]" class="intrant-cout w-full px-3 py-2 border rounded-lg text-sm bg-gray-100" readonly>
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-intrant bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        // Ajouter les event listeners
        const intrantSelect = div.querySelector('.intrant-select');
        const quantiteInput = div.querySelector('.intrant-quantite');
        
        const update = () => updateIntrantCost(div);
        intrantSelect.addEventListener('change', update);
        quantiteInput.addEventListener('input', update);
        
        return div;
    }
    
    // --- Event Listeners ---
    
    // Calcul du coût des semences
    if (semenceSelect && quantiteEstimeeInput) {
        semenceSelect.addEventListener('change', calculateSemenceCost);
        quantiteEstimeeInput.addEventListener('input', calculateSemenceCost);
        // Calcul initial
        calculateSemenceCost();
    }
    
    // Autres frais
    if (autresFraisInput) {
        autresFraisInput.addEventListener('input', calculateTotalEstimation);
    }
    
    // Ajouter un intrant
    if (addButton) {
        addButton.addEventListener('click', function () {
            const newRow = createIntrantRow(intrantIndex++);
            container.appendChild(newRow);
        });
    }
    
    // Supprimer un intrant
    if (container) {
        container.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-intrant') || e.target.closest('.remove-intrant')) {
                const button = e.target.classList.contains('remove-intrant') ? e.target : e.target.closest('.remove-intrant');
                button.closest('.intrant-item').remove();
                calculateTotalIntrantsCost();
            }
        });
    }
    
    // Attacher les événements aux lignes d'intrants existantes
    const existingRows = document.querySelectorAll('.intrant-item');
    existingRows.forEach(row => {
        const intrantSelect = row.querySelector('.intrant-select');
        const quantiteInput = row.querySelector('.intrant-quantite');
        
        const update = () => updateIntrantCost(row);
        if (intrantSelect) intrantSelect.addEventListener('change', update);
        if (quantiteInput) quantiteInput.addEventListener('input', update);
        
        // Calcul initial pour chaque ligne existante
        updateIntrantCost(row);
    });
    
    // Calcul initial du total des intrants
    calculateTotalIntrantsCost();
});
</script>
@endsection