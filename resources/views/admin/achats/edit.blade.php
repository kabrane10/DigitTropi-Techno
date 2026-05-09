@extends('layouts.admin')

@section('title', 'Modifier achat')
@section('header', 'Modifier le bordereau d\'achat')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="mb-4 p-3 bg-yellow-50 rounded-lg">
        <p class="text-sm text-yellow-700">
            <i class="fas fa-info-circle mr-2"></i>
            Modification de l'achat effectué auprès du producteur.
            La collecte source ne peut pas être modifiée.
        </p>
    </div>

    <form action="{{ route('admin.achats.update', $achat) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Code achat (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-barcode text-primary mr-1"></i> Code achat
                </label>
                <input type="text" value="{{ $achat->code_achat }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Collecte source (lecture seule) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-truck-loading text-primary mr-1"></i> Collecte source
                </label>
                <input type="text" value="{{ $achat->collecte->code_collecte }} - {{ $achat->collecte->producteur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Informations collecte -->
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-sm font-semibold text-gray-700 mb-2">Informations collecte source</p>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-gray-500">Producteur:</span>
                        <span class="font-medium">{{ $achat->collecte->producteur->nom_complet }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Produit:</span>
                        <span class="font-medium">{{ $achat->collecte->produit }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Quantité collectée:</span>
                        <span class="font-medium">{{ number_format($achat->collecte->quantite_nette) }} kg</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Date collecte:</span>
                        <span class="font-medium">{{ $achat->collecte->date_collecte->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Quantité maximale -->
            <div class="bg-blue-50 rounded-lg p-3">
                <p class="text-sm font-semibold text-blue-700 mb-2">
                    <i class="fas fa-info-circle mr-1"></i> Quantité maximale
                </p>
                <p class="text-lg font-bold text-blue-800">{{ number_format($achat->collecte->quantite_nette) }} kg</p>
                <p class="text-xs text-blue-600 mt-1">La quantité ne peut pas dépasser ce seuil</p>
            </div>
            
            <!-- Date d'achat -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date d'achat *
                </label>
                <input type="date" name="date_achat" required value="{{ old('date_achat', $achat->date_achat->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Acheteur (automatiquement Tropi-Techno) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user text-primary mr-1"></i> Acheteur
                </label>
                <input type="text" name="acheteur" value="Tropi-Techno Sarl" readonly
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
                <p class="text-xs text-gray-500 mt-1">L'acheteur est Tropi-Techno Sarl</p>
            </div>
            
            <!-- Quantité -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité (kg) *
                </label>
                <input type="number" step="0.01" name="quantite" id="quantite" required 
                       value="{{ old('quantite', $achat->quantite) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p id="quantite_max_info" class="text-xs text-gray-500 mt-1">
                    Quantité maximale disponible: {{ number_format($achat->collecte->quantite_nette) }} kg
                </p>
            </div>
            
            <!-- Prix unitaire -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Prix unitaire (CFA/kg) *
                </label>
                <input type="number" step="1" name="prix_achat" id="prix_achat" required 
                       value="{{ old('prix_achat', $achat->prix_achat) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Prix convenu">
            </div>
            
            <!-- Mode de paiement -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-credit-card text-primary mr-1"></i> Mode de paiement *
                </label>
                <select name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="especes" {{ $achat->mode_paiement == 'especes' ? 'selected' : '' }}> Espèces</option>
                    <option value="virement" {{ $achat->mode_paiement == 'virement' ? 'selected' : '' }}> Virement bancaire</option>
                    <option value="cheque" {{ $achat->mode_paiement == 'cheque' ? 'selected' : '' }}> Chèque</option>
                    <option value="mobile_money" {{ $achat->mode_paiement == 'mobile_money' ? 'selected' : '' }}> Mobile Money</option>
                </select>
            </div>
            
            <!-- Statut -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Statut
                </label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="confirme" {{ $achat->statut == 'confirme' ? 'selected' : '' }}> Confirmé</option>
                    <option value="en_attente" {{ $achat->statut == 'en_attente' ? 'selected' : '' }}> En attente</option>
                    <option value="annule" {{ $achat->statut == 'annule' ? 'selected' : '' }}> Annulé</option>
                </select>
            </div>
            
            <!-- Référence facture -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hashtag text-primary mr-1"></i> Référence facture
                </label>
                <div class="flex gap-2">
                    <input type="text" name="reference_facture" id="reference_facture" 
                           value="{{ old('reference_facture', $achat->reference_facture) }}"
                           class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                           placeholder="N° de facture">
                    <button type="button" onclick="genererReference()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-sync-alt mr-1"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Générer une nouvelle référence si besoin</p>
            </div>
            
            <!-- Notes -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Notes
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires...">{{ old('notes', $achat->notes) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3"> Récapitulatif de l'achat</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="text-xl font-bold text-primary" id="recap_quantite">{{ number_format($achat->quantite) }} kg</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Prix unitaire</p>
                    <p class="text-xl font-bold text-primary" id="recap_prix">{{ number_format($achat->prix_achat, 0, ',', ' ') }} CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-green-600" id="recap_total">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
            
            <!-- Stats vendeur -->
            <div class="mt-3 pt-3 border-t border-green-200 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Vendeur :</span>
                    <span class="font-semibold">{{ $achat->collecte->producteur->nom_complet }}</span>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-gray-600">Produit :</span>
                    <span class="font-semibold">{{ $achat->collecte->produit }}</span>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-gray-600">Facture :</span>
                    <span class="font-semibold text-blue-600">{{ $achat->reference_facture ?? 'Non spécifiée' }}</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.achats.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    const quantiteInput = document.getElementById('quantite');
    const prixInput = document.getElementById('prix_achat');
    const recapQuantite = document.getElementById('recap_quantite');
    const recapPrix = document.getElementById('recap_prix');
    const recapTotal = document.getElementById('recap_total');
    const quantiteMax = {{ $achat->collecte->quantite_nette }};
    
    // Mettre à jour les totaux en temps réel
    function updateTotals() {
        const quantite = parseFloat(quantiteInput.value) || 0;
        const prix = parseFloat(prixInput.value) || 0;
        const total = quantite * prix;
        
        recapQuantite.textContent = quantite.toLocaleString() + ' kg';
        recapPrix.textContent = prix.toLocaleString() + ' CFA';
        recapTotal.textContent = total.toLocaleString() + ' CFA';
        
        // Vérifier la limite de quantité
        if (quantite > quantiteMax) {
            quantiteInput.setCustomValidity(`La quantité ne peut pas dépasser ${quantiteMax} kg`);
            quantiteInput.classList.add('border-red-500');
        } else {
            quantiteInput.setCustomValidity('');
            quantiteInput.classList.remove('border-red-500');
        }
    }
    
    // Générer une nouvelle référence facture
    function genererReference() {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        
        const reference = `FAC-${year}${month}${day}-${random}`;
        document.getElementById('reference_facture').value = reference;
    }
    
    // Événements
    quantiteInput.addEventListener('input', updateTotals);
    prixInput.addEventListener('input', updateTotals);
    
    // Initialisation
    updateTotals();
</script>
@endsection