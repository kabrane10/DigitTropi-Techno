@extends('layouts.admin')

@section('title', 'Modifier crédit')
@section('header', 'Modifier le crédit agricole')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.credits.update', $credit) }}" method="POST">
        @csrf
        @method('PUT')
        
        @php
            // Déterminer le type de bénéficiaire actuel
            $currentBeneficiaireType = 'producteur';
            if ($credit->cooperative_id || $credit->beneficiaire_type === 'App\\Models\\Cooperative') {
                $currentBeneficiaireType = 'cooperative';
            }
        @endphp
        
        <!-- Sélecteur bénéficiaire (Producteur ou Coopérative) -->
        @include('admin.partials._beneficiaire_selector', [
            'producteurs' => $producteurs,
            'cooperatives' => $cooperatives,
            'beneficiaire_type' => old('beneficiaire_type', $currentBeneficiaireType),
            'producteur_id' => $credit->producteur_id,
            'cooperative_id' => $credit->cooperative_id
        ])
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code crédit (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code crédit
                </label>
                <input type="text" value="{{ $credit->code_credit }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Montant total -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Montant total (CFA) *
                </label>
                <input type="number" name="montant_total" required min="1000" step="1000"
                       value="{{ old('montant_total', $credit->montant_total) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Taux d'intérêt -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Taux d'intérêt (%) *
                </label>
                <input type="number" name="taux_interet" required min="0" max="100" step="0.5"
                       value="{{ old('taux_interet', $credit->taux_interet) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Durée -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar-alt text-primary mr-1"></i> Durée (mois) *
                </label>
                <input type="number" name="duree_mois" id="duree_mois" required min="1" max="60"
                       value="{{ old('duree_mois', $credit->duree_mois) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>

            <!-- Type d'intrant -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-boxes text-primary mr-1"></i> Type d'intrant *
                </label>
                <select name="type_intrant" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez un intrant --</option>
                    <option value="semences" {{ old('type_intrant', $credit->type_intrant) == 'semences' ? 'selected' : '' }}> Semences</option>
                    <option value="engrais" {{ old('type_intrant', $credit->type_intrant) == 'engrais' ? 'selected' : '' }}> Engrais</option>
                    <option value="pesticides" {{ old('type_intrant', $credit->type_intrant) == 'pesticides' ? 'selected' : '' }}> Pesticides</option>
                    <option value="herbicides" {{ old('type_intrant', $credit->type_intrant) == 'herbicides' ? 'selected' : '' }}> Herbicides</option>
                    <option value="autres" {{ old('type_intrant', $credit->type_intrant) == 'autres' ? 'selected' : '' }}> Autres</option>
                </select>
            </div>

            <!-- Quantité d'intrant -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité d'intrant *
                </label>
                <div class="flex">
                    <input type="number" step="0.01" name="quantite_intrant" required 
                           value="{{ old('quantite_intrant', $credit->quantite_intrant) }}"
                           class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-primary">
                    <select name="unite_intrant" required class="px-3 py-2 bg-gray-100 border border-l-0 rounded-r-lg">
                        <option value="kg" {{ old('unite_intrant', $credit->unite_intrant) == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="litre" {{ old('unite_intrant', $credit->unite_intrant) == 'litre' ? 'selected' : '' }}>Litre</option>
                        <option value="sac" {{ old('unite_intrant', $credit->unite_intrant) == 'sac' ? 'selected' : '' }}>Sac</option>
                        <option value="botte" {{ old('unite_intrant', $credit->unite_intrant) == 'botte' ? 'selected' : '' }}>Botte</option>
                    </select>
                </div>
            </div>

            <!-- Date d'octroi -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar-check text-primary mr-1"></i> Date d'octroi *
                </label>
                <input type="date" name="date_octroi" id="date_octroi" required 
                       value="{{ old('date_octroi', $credit->date_octroi->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Statut
                </label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ old('statut', $credit->statut) == 'actif' ? 'selected' : '' }}> Actif</option>
                    <option value="rembourse" {{ old('statut', $credit->statut) == 'rembourse' ? 'selected' : '' }}> Remboursé</option>
                    <option value="impaye" {{ old('statut', $credit->statut) == 'impaye' ? 'selected' : '' }}> Impayé</option>
                    <option value="restructure" {{ old('statut', $credit->statut) == 'restructure' ? 'selected' : '' }}> Restructuré</option>
                </select>
            </div>
            
            <!-- Conditions -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-file-alt text-primary mr-1"></i> Conditions particulières
                </label>
                <textarea name="conditions" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Conditions spécifiques du crédit...">{{ old('conditions', $credit->conditions) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3"> Récapitulatif du crédit</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Mensualité estimée</p>
                    <p class="text-xl font-bold text-primary" id="mensualite_estimee">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Intérêts totaux</p>
                    <p class="text-xl font-bold text-orange-500" id="interets_totaux">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Total à rembourser</p>
                    <p class="text-xl font-bold text-green-600" id="total_rembourser">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Date d'échéance</p>
                    <p class="text-xl font-bold text-primary" id="date_echeance">-</p>
                </div>
            </div>
        </div>
        
        <!-- Message d'information si le crédit a des remboursements -->
        @if($credit->remboursements->count() > 0)
        <div class="mt-4 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-500">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">⚠️ Attention</p>
                    <p class="text-sm text-yellow-700">
                        Ce crédit a déjà {{ $credit->remboursements->count() }} remboursement(s). 
                        La modification du montant ou du taux peut affecter les calculs. 
                        Vérifiez bien les nouvelles valeurs.
                    </p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.credits.show', $credit) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    // Éléments du DOM
    const montantInput = document.querySelector('input[name="montant_total"]');
    const tauxInput = document.querySelector('input[name="taux_interet"]');
    const dureeInput = document.getElementById('duree_mois');
    const dateOctroiInput = document.getElementById('date_octroi');
    const mensualiteSpan = document.getElementById('mensualite_estimee');
    const interetsSpan = document.getElementById('interets_totaux');
    const totalSpan = document.getElementById('total_rembourser');
    const dateEcheanceSpan = document.getElementById('date_echeance');
    
    // Calcul de la mensualité
    function calculerMensualite(montant, tauxAnnuel, dureeMois) {
        if (tauxAnnuel == 0 || dureeMois == 0) {
            return montant / dureeMois;
        }
        const tauxMensuel = tauxAnnuel / 12 / 100;
        const mensualite = montant * tauxMensuel * Math.pow(1 + tauxMensuel, dureeMois) / (Math.pow(1 + tauxMensuel, dureeMois) - 1);
        return Math.round(mensualite);
    }
    
    // Mise à jour du récapitulatif
    function updateRecap() {
        const montant = parseFloat(montantInput?.value) || 0;
        const taux = parseFloat(tauxInput?.value) || 0;
        const duree = parseInt(dureeInput?.value) || 0;
        const dateOctroi = dateOctroiInput?.value;
        
        if (montant > 0 && duree > 0) {
            const mensualite = calculerMensualite(montant, taux, duree);
            const totalRembourser = mensualite * duree;
            const interets = totalRembourser - montant;
            
            if (mensualiteSpan) mensualiteSpan.textContent = mensualite.toLocaleString() + ' CFA';
            if (interetsSpan) interetsSpan.textContent = interets.toLocaleString() + ' CFA';
            if (totalSpan) totalSpan.textContent = totalRembourser.toLocaleString() + ' CFA';
        } else {
            if (mensualiteSpan) mensualiteSpan.textContent = '0 CFA';
            if (interetsSpan) interetsSpan.textContent = '0 CFA';
            if (totalSpan) totalSpan.textContent = '0 CFA';
        }
        
        // Calcul de la date d'échéance
        if (dateOctroi && duree > 0 && dateEcheanceSpan) {
            const date = new Date(dateOctroi);
            date.setMonth(date.getMonth() + duree);
            dateEcheanceSpan.textContent = date.toLocaleDateString('fr-FR');
        } else if (dateEcheanceSpan) {
            dateEcheanceSpan.textContent = '-';
        }
    }
    
    // Écouteurs d'événements
    if (montantInput) montantInput.addEventListener('input', updateRecap);
    if (tauxInput) tauxInput.addEventListener('input', updateRecap);
    if (dureeInput) dureeInput.addEventListener('input', updateRecap);
    if (dateOctroiInput) dateOctroiInput.addEventListener('change', updateRecap);
    
    // Calcul initial
    updateRecap();
</script>
@endsection