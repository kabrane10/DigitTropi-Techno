@extends('layouts.admin')

@section('title', 'Nouveau crédit')
@section('header', 'Accorder un crédit agricole')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.credits.store') }}" method="POST">
        @csrf
        
        @php
            // Récupération des paramètres d'URL pour pré-remplissage
            $producteur_id = request()->input('producteur_id');
            $montant_total = request()->input('montant_total');
            $estimation_id = request()->input('estimation_id');
            $type_intrant = request()->input('type_intrant', 'semences');
            $quantite_intrant = request()->input('quantite_intrant');
            $unite_intrant = request()->input('unite_intrant', 'kg');
            $taux_interet = request()->input('taux_interet', 5);
            $duree_mois = request()->input('duree_mois', 12);
        @endphp
        
        <!-- Message d'information si conversion depuis estimation -->
        @if($estimation_id)
        <div class="mb-4 p-3 bg-green-50 rounded-lg border-l-4 border-green-500">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-green-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-green-800">Crédit basé sur une estimation</p>
                    <p class="text-sm text-green-700">Ce crédit est basé sur l'estimation N° {{ $estimation_id }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Producteur -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user text-primary mr-1"></i> Producteur *
                </label>
                @if($producteur_id)
                    <input type="hidden" name="producteur_id" value="{{ $producteur_id }}">
                    <input type="text" value="{{ \App\Models\Producteur::find($producteur_id)->nom_complet ?? 'Producteur trouvé' }} - {{ \App\Models\Producteur::find($producteur_id)->code_producteur ?? '' }}" 
                           class="w-full px-4 py-2 border rounded-lg bg-gray-100" disabled>
                @else
                    <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                        <option value="">-- Sélectionnez un producteur --</option>
                        @foreach($producteurs as $producteur)
                        <option value="{{ $producteur->id }}" {{ old('producteur_id', $producteur_id) == $producteur->id ? 'selected' : '' }}>
                            {{ $producteur->nom_complet }} ({{ $producteur->code_producteur }}) - {{ $producteur->region }}
                        </option>
                        @endforeach
                    </select>
                @endif
            </div>
            
            <!-- Coopérative -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-handshake text-primary mr-1"></i> Coopérative *
                </label>
                <select name="cooperative_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une coopérative --</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}" {{ old('cooperative_id') == $cooperative->id ? 'selected' : '' }}>
                        {{ $cooperative->nom }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Montant total -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Montant total (CFA) *
                </label>
                <input type="number" name="montant_total" required min="1000" step="1000"
                       value="{{ old('montant_total', $montant_total) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 100000">
            </div>
            
            <!-- Taux d'intérêt -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Taux d'intérêt (%) *
                </label>
                <input type="number" name="taux_interet" required min="0" max="100" step="0.5"
                       value="{{ old('taux_interet', $taux_interet) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 5">
            </div>
            
            <!-- Durée -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar-alt text-primary mr-1"></i> Durée (mois) *
                </label>
                <input type="number" name="duree_mois" id="duree_mois" required min="1" max="60"
                       value="{{ old('duree_mois', $duree_mois) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 12">
            </div>

            <!-- Type d'intrant -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-boxes text-primary mr-1"></i> Type d'intrant *
                </label>
                <select name="type_intrant" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez un intrant --</option>
                    <option value="semences" {{ old('type_intrant', $type_intrant) == 'semences' ? 'selected' : '' }}> Semences</option>
                    <option value="engrais" {{ old('type_intrant', $type_intrant) == 'engrais' ? 'selected' : '' }}> Engrais</option>
                    <option value="pesticides" {{ old('type_intrant', $type_intrant) == 'pesticides' ? 'selected' : '' }}> Pesticides</option>
                    <option value="herbicides" {{ old('type_intrant', $type_intrant) == 'herbicides' ? 'selected' : '' }}> Herbicides</option>
                    <option value="autres" {{ old('type_intrant', $type_intrant) == 'autres' ? 'selected' : '' }}> Autres</option>
                </select>
            </div>

            <!-- Quantité d'intrant -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité d'intrant *
                </label>
                <div class="flex">
                    <input type="number" step="0.01" name="quantite_intrant" id="quantite_intrant" required 
                           value="{{ old('quantite_intrant', $quantite_intrant) }}"
                           class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-primary"
                           placeholder="Quantité">
                    <select name="unite_intrant" required class="px-3 py-2 bg-gray-100 border border-l-0 rounded-r-lg">
                        <option value="kg" {{ old('unite_intrant', $unite_intrant) == 'kg' ? 'selected' : '' }}>kg</option>
                        <option value="litre" {{ old('unite_intrant', $unite_intrant) == 'litre' ? 'selected' : '' }}>Litre</option>
                        <option value="sac" {{ old('unite_intrant', $unite_intrant) == 'sac' ? 'selected' : '' }}>Sac</option>
                        <option value="botte" {{ old('unite_intrant', $unite_intrant) == 'bol' ? 'selected' : '' }}>Bol</option>
                    </select>
                </div>
            </div>

            <!-- Date d'octroi -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar-check text-primary mr-1"></i> Date d'octroi *
                </label>
                <input type="date" name="date_octroi" id="date_octroi" required value="{{ old('date_octroi', date('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Conditions -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-file-alt text-primary mr-1"></i> Conditions particulières
                </label>
                <textarea name="conditions" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Conditions spécifiques du crédit...">{{ old('conditions') }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3">📊 Récapitulatif du crédit</h4>
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
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.credits.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Accorder le crédit
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
        if (tauxAnnuel == 0) {
            return montant / dureeMois;
        }
        const tauxMensuel = tauxAnnuel / 12 / 100;
        const mensualite = montant * tauxMensuel * Math.pow(1 + tauxMensuel, dureeMois) / (Math.pow(1 + tauxMensuel, dureeMois) - 1);
        return Math.round(mensualite);
    }
    
    // Mise à jour du récapitulatif
    function updateRecap() {
        const montant = parseFloat(montantInput.value) || 0;
        const taux = parseFloat(tauxInput.value) || 0;
        const duree = parseInt(dureeInput.value) || 0;
        const dateOctroi = dateOctroiInput.value;
        
        if (montant > 0 && duree > 0) {
            const mensualite = calculerMensualite(montant, taux, duree);
            const totalRembourser = mensualite * duree;
            const interets = totalRembourser - montant;
            
            mensualiteSpan.textContent = mensualite.toLocaleString() + ' CFA';
            interetsSpan.textContent = interets.toLocaleString() + ' CFA';
            totalSpan.textContent = totalRembourser.toLocaleString() + ' CFA';
        } else {
            mensualiteSpan.textContent = '0 CFA';
            interetsSpan.textContent = '0 CFA';
            totalSpan.textContent = '0 CFA';
        }
        
        // Calcul de la date d'échéance
        if (dateOctroi && duree > 0) {
            const date = new Date(dateOctroi);
            date.setMonth(date.getMonth() + duree);
            const echeance = date.toLocaleDateString('fr-FR');
            dateEcheanceSpan.textContent = echeance;
        } else {
            dateEcheanceSpan.textContent = '-';
        }
    }
    
    // Écouteurs d'événements
    montantInput.addEventListener('input', updateRecap);
    tauxInput.addEventListener('input', updateRecap);
    dureeInput.addEventListener('input', updateRecap);
    dateOctroiInput.addEventListener('change', updateRecap);
    
    // Calcul initial
    updateRecap();
</script>
@endsection