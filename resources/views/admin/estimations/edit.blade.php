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
            
            <!-- Intrants supplémentaires (optionnel) -->
            <div class="md:col-span-2">
                <div class="border rounded-lg p-4 bg-gray-50">
                    <div class="flex items-center justify-between mb-3">
                        <label class="text-sm font-semibold text-dark">
                            <i class="fas fa-boxes text-primary mr-1"></i> Intrants supplémentaires (optionnel)
                        </label>
                        <button type="button" onclick="ajouterIntrant()" class="text-primary text-sm hover:underline">
                            <i class="fas fa-plus-circle mr-1"></i> Ajouter un intrant
                        </button>
                    </div>
                    
                    <div id="intrants-container" class="space-y-3">
                        @php
                            $intrants = json_decode($estimation->intrants ?? '[]', true);
                        @endphp
                        @if(!empty($intrants))
                            @foreach($intrants as $index => $intrant)
                            <div class="intrant-item grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-white rounded-lg border">
                                <div>
                                    <label class="block text-xs font-semibold mb-1">Type d'intrant</label>
                                    <select name="intrants[{{ $index }}][type]" class="w-full px-3 py-2 border rounded-lg text-sm">
                                        <option value="engrais" {{ $intrant['type'] == 'engrais' ? 'selected' : '' }}> Engrais</option>
                                        <option value="pesticide" {{ $intrant['type'] == 'pesticide' ? 'selected' : '' }}> Pesticide</option>
                                        <option value="herbicide" {{ $intrant['type'] == 'herbicide' ? 'selected' : '' }}> Herbicide</option>
                                        <option value="autre" {{ $intrant['type'] == 'autre' ? 'selected' : '' }}> Autre</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold mb-1">Quantité</label>
                                    <input type="number" step="0.01" name="intrants[{{ $index }}][quantite]" value="{{ $intrant['quantite'] }}" class="w-full px-3 py-2 border rounded-lg text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold mb-1">Unité</label>
                                    <select name="intrants[{{ $index }}][unite]" class="w-full px-3 py-2 border rounded-lg text-sm">
                                        <option value="kg" {{ $intrant['unite'] == 'kg' ? 'selected' : '' }}>kg</option>
                                        <option value="litre" {{ $intrant['unite'] == 'litre' ? 'selected' : '' }}>Litre</option>
                                        <option value="sac" {{ $intrant['unite'] == 'sac' ? 'selected' : '' }}>Sac</option>
                                        <option value="bol" {{ $intrant['unite'] == 'bol' ? 'selected' : '' }}>Bol</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" onclick="supprimerIntrant(this)" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <input type="hidden" name="intrants" id="intrants_json">
                </div>
            </div>
            
            <!-- Observations -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Observations
                </label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires...">{{ old('observations', $estimation->observations) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3"> Récapitulatif de l'estimation</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Semence</p>
                    <p class="font-semibold" id="recap_semence">{{ $estimation->semence->nom }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="font-semibold text-primary" id="recap_quantite">{{ number_format($estimation->quantite_estimee) }} {{ $estimation->semence->unite }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Superficie</p>
                    <p class="font-semibold" id="recap_superficie">{{ number_format($estimation->superficie_prevue, 2) }} ha</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Crédit estimé</p>
                    <p class="font-semibold text-green-600" id="recap_credit">{{ number_format($estimation->credit_montant ?? 0, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
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
    let intrantIndex = {{ count(json_decode($estimation->intrants ?? '[]', true)) }};
    
    function ajouterIntrant() {
        const container = document.getElementById('intrants-container');
        const newDiv = document.createElement('div');
        newDiv.className = 'intrant-item grid grid-cols-1 md:grid-cols-4 gap-3 p-3 bg-white rounded-lg border';
        newDiv.innerHTML = `
            <div>
                <label class="block text-xs font-semibold mb-1">Type d'intrant</label>
                <select name="intrants[${intrantIndex}][type]" class="w-full px-3 py-2 border rounded-lg text-sm">
                    <option value="engrais">🧪 Engrais</option>
                    <option value="pesticide">🐛 Pesticide</option>
                    <option value="herbicide">🌿 Herbicide</option>
                    <option value="autre">📦 Autre</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Quantité</label>
                <input type="number" step="0.01" name="intrants[${intrantIndex}][quantite]" class="w-full px-3 py-2 border rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Unité</label>
                <select name="intrants[${intrantIndex}][unite]" class="w-full px-3 py-2 border rounded-lg text-sm">
                    <option value="kg">kg</option>
                    <option value="litre">Litre</option>
                    <option value="sac">Sac</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="button" onclick="supprimerIntrant(this)" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newDiv);
        intrantIndex++;
    }
    
    function supprimerIntrant(btn) {
        const intrantItem = btn.closest('.intrant-item');
        intrantItem.remove();
    }
</script>
@endsection