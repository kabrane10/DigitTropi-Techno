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
            </div>
            
            <!-- Producteur (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user text-primary mr-1"></i> Producteur
                </label>
                <input type="text" value="{{ $estimation->producteur->nom_complet }} ({{ $estimation->producteur->code_producteur }})" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Semence -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-seedling text-primary mr-1"></i> Semence *
                </label>
                <select name="semence_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une semence --</option>
                    @foreach($semences as $semence)
                    <option value="{{ $semence->id }}" {{ $estimation->semence_id == $semence->id ? 'selected' : '' }}>
                        {{ $semence->nom }} ({{ $semence->variete }})
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Quantité estimée -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité estimée *
                </label>
                <input type="number" step="0.01" name="quantite_estimee" required 
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
                    $intrants = json_decode($estimation->intrants ?? '[]', true);
                @endphp
                @foreach($intrants as $index => $intrant)
                    <div class="intrant-item grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold mb-1">Type</label>
                            <select name="intrants[{{ $index }}][type]" class="w-full px-3 py-2 border rounded-lg text-sm">
                                <option value="engrais" {{ ($intrant['type'] ?? '') == 'engrais' ? 'selected' : '' }}>Engrais</option>
                                <option value="pesticide" {{ ($intrant['type'] ?? '') == 'pesticide' ? 'selected' : '' }}>Pesticide</option>
                                <option value="herbicide" {{ ($intrant['type'] ?? '') == 'herbicide' ? 'selected' : '' }}>Herbicide</option>
                                <option value="autre" {{ ($intrant['type'] ?? '') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Quantité</label>
                            <input type="number" step="0.01" name="intrants[{{ $index }}][quantite]" value="{{ $intrant['quantite'] ?? '' }}" class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Unité</label>
                            <input type="text" name="intrants[{{ $index }}][unite]" value="{{ $intrant['unite'] ?? '' }}" class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1">Coût estimé (CFA)</label>
                            <input type="number" step="100" name="intrants[{{ $index }}][cout_estime]" value="{{ $intrant['cout_estime'] ?? '' }}" class="w-full px-3 py-2 border rounded-lg text-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-intrant bg-red-500 text-white px-3 py-2 rounded-lg">-</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-intrant" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i>Ajouter un intrant
            </button>
        </div>

        <!-- Section pour les coûts -->
        <div class="mt-6 pt-6 border-t">
            <h3 class="text-lg font-semibold mb-4">Estimation des coûts</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="cout_semences" class="block text-sm font-semibold mb-2">Coût des semences (CFA)</label>
                    <input type="number" id="cout_semences" name="cout_semences" step="100" value="{{ old('cout_semences', $estimation->cout_semences) }}" class="w-full px-4 py-2 border rounded-lg cost-calculator">
                </div>
                <div>
                    <label for="cout_intrants" class="block text-sm font-semibold mb-2">Coût des intrants (CFA)</label>
                    <input type="number" id="cout_intrants" name="cout_intrants" value="{{ old('cout_intrants', $estimation->cout_intrants) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100 cost-calculator" readonly>
                </div>
                <div>
                    <label for="autres_frais" class="block text-sm font-semibold mb-2">Autres frais (CFA)</label>
                    <input type="number" id="autres_frais" name="autres_frais" step="100" value="{{ old('autres_frais', $estimation->autres_frais) }}" class="w-full px-4 py-2 border rounded-lg cost-calculator">
                </div>
                <div>
                    <label for="total_estimation" class="block text-sm font-semibold mb-2">Total Estimation (CFA)</label>
                    <input type="number" id="total_estimation" name="total_estimation" value="{{ old('total_estimation', $estimation->total_estimation) }}" class="w-full px-4 py-2 border rounded-lg bg-gray-100" readonly>
                </div>
            </div>
        </div>
        
        <!-- Observations -->
        <div class="md:col-span-2 mt-6">
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
    let intrantIndex = {{ count($intrants) }};

    // --- Cost Calculation Elements ---
    const coutSemencesInput = document.getElementById('cout_semences');
    const coutIntrantsInput = document.getElementById('cout_intrants');
    const autresFraisInput = document.getElementById('autres_frais');
    const totalEstimationInput = document.getElementById('total_estimation');

    // --- Functions for calculation ---
    function calculateTotalEstimation() {
        const semences = parseFloat(coutSemencesInput.value) || 0;
        const intrants = parseFloat(coutIntrantsInput.value) || 0;
        const autres = parseFloat(autresFraisInput.value) || 0;
        totalEstimationInput.value = (semences + intrants + autres).toFixed(2);
    }

    function calculateTotalIntrantsCost() {
        let total = 0;
        const costInputs = container.querySelectorAll('input[name$="[cout_estime]"]');
        costInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        coutIntrantsInput.value = total.toFixed(2);
        calculateTotalEstimation(); // Recalculate the grand total
    }

    // --- Event Listeners ---

    // Listen for changes in the main cost fields
    [coutSemencesInput, autresFraisInput].forEach(input => {
        input.addEventListener('input', calculateTotalEstimation);
    });

    // Listen for changes within the intrants container
    container.addEventListener('input', function (e) {
        if (e.target.name && e.target.name.includes('[cout_estime]')) {
            calculateTotalIntrantsCost();
        }
    });

    // Handle adding new intrant rows
    addButton.addEventListener('click', function () {
        const intrantRow = document.createElement('div');
        intrantRow.classList.add('intrant-item', 'grid', 'grid-cols-1', 'md:grid-cols-5', 'gap-4', 'mb-4');
        intrantRow.innerHTML = `
            <div>
                <label class="block text-xs font-semibold mb-1">Type</label>
                <select name="intrants[${intrantIndex}][type]" class="w-full px-3 py-2 border rounded-lg text-sm">
                    <option value="engrais">Engrais</option>
                    <option value="pesticide">Pesticide</option>
                    <option value="herbicide">Herbicide</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Quantité</label>
                <input type="number" step="0.01" name="intrants[${intrantIndex}][quantite]" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Ex: 10.5">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Unité</label>
                <input type="text" name="intrants[${intrantIndex}][unite]" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Ex: kg, L, sac">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Coût estimé (CFA)</label>
                <input type="number" step="100" name="intrants[${intrantIndex}][cout_estime]" class="w-full px-3 py-2 border rounded-lg text-sm" placeholder="Ex: 5000">
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-intrant bg-red-500 text-white px-3 py-2 rounded-lg">-</button>
            </div>
        `;
        container.appendChild(intrantRow);
        intrantIndex++;
    });

    // Handle removing intrant rows
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-intrant')) {
            e.target.closest('.intrant-item').remove();
            calculateTotalIntrantsCost(); // Recalculate after removing
        }
    });
    
    // Initial calculation on page load
    calculateTotalIntrantsCost();
});
</script>
@endsection
