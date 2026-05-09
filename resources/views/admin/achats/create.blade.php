@extends('layouts.admin')

@section('title', 'Nouvel achat')
@section('header', 'Créer un achat (Collecte → Tropi-Techno)')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
        <p class="text-sm text-blue-700">
            <i class="fas fa-info-circle mr-2"></i>
            L'achat est créé à partir d'une collecte effectuée auprès d'un producteur. 
            Tropi-Techno achète la récolte au producteur.
        </p>
    </div>

    <form action="{{ route('admin.achats.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Collecte source -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-truck-loading text-primary mr-1"></i> Collecte source *
                </label>
                <select name="collecte_id" id="collecte_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une collecte --</option>
                    @foreach($collectes as $collecte)
                    <option value="{{ $collecte->id }}" 
                            data-produit="{{ $collecte->produit }}"
                            data-quantite="{{ $collecte->quantite_nette }}"
                            data-producteur="{{ $collecte->producteur->nom_complet }}">
                        {{ $collecte->code_collecte }} - {{ $collecte->producteur->nom_complet }} - 
                        {{ $collecte->produit }} ({{ number_format($collecte->quantite_nette) }} kg) - 
                        {{ number_format($collecte->date_collecte->format('d/m/Y')) }}
                    </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">La collecte détermine le produit et la quantité maximale</p>
            </div>
            
            <!-- Infos collecte sélectionnée -->
            <div class="bg-gray-50 rounded-lg p-3">
                <p class="text-sm font-semibold text-gray-700 mb-2">Informations collecte</p>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-gray-500">Producteur:</span>
                        <span id="info_producteur" class="font-medium">-</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Produit:</span>
                        <span id="info_produit" class="font-medium">-</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Quantité disponible:</span>
                        <span id="info_quantite" class="font-medium">-</span>
                    </div>
                </div>
            </div>
            
            <!-- Date d'achat -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date d'achat *
                </label>
                <input type="date" name="date_achat" required value="{{ date('Y-m-d') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Acheteur (automatique) -->
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
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Quantité à acheter">
                <p id="quantite_max_info" class="text-xs text-gray-500 mt-1"></p>
            </div>
            
            <!-- Prix unitaire -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-money-bill-wave text-primary mr-1"></i> Prix unitaire (CFA/kg) *
                </label>
                <input type="number" step="1" name="prix_achat" id="prix_achat" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Prix convenu">
            </div>
            
            <!-- Mode de paiement -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-credit-card text-primary mr-1"></i> Mode de paiement *
                </label>
                <select name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="especes"> Espèces</option>
                    <option value="virement"> Virement bancaire</option>
                    <option value="cheque"> Chèque</option>
                    <option value="mobile_money"> Mobile Money</option>
                </select>
            </div>
            
            <!-- Référence facture (générée automatiquement) -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hashtag text-primary mr-1"></i> Référence facture
                </label>
                <div class="flex gap-2">
                    <input type="text" name="reference_facture" id="reference_facture" 
                           class="flex-1 px-4 py-2 border rounded-lg bg-gray-50 focus:outline-none focus:border-primary"
                           placeholder="Générée automatiquement" readonly>
                    <button type="button" onclick="genererReference()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-sync-alt mr-1"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Référence unique pour le suivi comptable</p>
            </div>
            
            <!-- Notes -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Notes
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires..."></textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3"> Récapitulatif de l'achat</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="text-xl font-bold text-primary" id="recap_quantite">0 kg</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Prix unitaire</p>
                    <p class="text-xl font-bold text-primary" id="recap_prix">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-green-600" id="recap_total">0 CFA</p>
                </div>
            </div>
            
            <!-- Stats vendeur -->
            <div class="mt-3 pt-3 border-t border-green-200 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Vendeur :</span>
                    <span class="font-semibold" id="recap_vendeur">-</span>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-gray-600">Produit :</span>
                    <span class="font-semibold" id="recap_produit">-</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.achats.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">
                Annuler
            </a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition">
                <i class="fas fa-save mr-2"></i> Enregistrer l'achat
            </button>
        </div>
    </form>
</div>

<script>
    const collecteSelect = document.getElementById('collecte_id');
    const quantiteInput = document.getElementById('quantite');
    const prixInput = document.getElementById('prix_achat');
    const referenceInput = document.getElementById('reference_facture');
    
    const recapQuantite = document.getElementById('recap_quantite');
    const recapPrix = document.getElementById('recap_prix');
    const recapTotal = document.getElementById('recap_total');
    const recapVendeur = document.getElementById('recap_vendeur');
    const recapProduit = document.getElementById('recap_produit');
    const infoProducteur = document.getElementById('info_producteur');
    const infoProduit = document.getElementById('info_produit');
    const infoQuantite = document.getElementById('info_quantite');
    const quantiteMaxInfo = document.getElementById('quantite_max_info');
    
    let quantiteMax = 0;
    
    // Mettre à jour les infos quand on sélectionne une collecte
    collecteSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const producteur = selected.dataset.producteur;
        const produit = selected.dataset.produit;
        const quantite = parseFloat(selected.dataset.quantite) || 0;
        
        quantiteMax = quantite;
        
        // Mettre à jour les affichages
        infoProducteur.textContent = producteur || '-';
        infoProduit.textContent = produit || '-';
        infoQuantite.textContent = quantite ? quantite.toLocaleString() + ' kg' : '-';
        quantiteMaxInfo.textContent = quantite ? `Quantité maximale disponible: ${quantite.toLocaleString()} kg` : '';
        
        recapVendeur.textContent = producteur || '-';
        recapProduit.textContent = produit || '-';
        
        // Vérifier la quantité saisie
        if (quantiteInput.value > quantiteMax) {
            quantiteInput.setCustomValidity(`La quantité ne peut pas dépasser ${quantiteMax} kg`);
        } else {
            quantiteInput.setCustomValidity('');
        }
        
        // Générer une nouvelle référence
        genererReference();
        updateTotals();
    });
    
    // Vérifier la quantité
    quantiteInput.addEventListener('input', function() {
        const value = parseFloat(this.value) || 0;
        
        if (value > quantiteMax && quantiteMax > 0) {
            this.setCustomValidity(`La quantité ne peut pas dépasser ${quantiteMax} kg`);
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
        
        recapQuantite.textContent = value.toLocaleString() + ' kg';
        updateTotals();
    });
    
    // Mettre à jour les totaux
    function updateTotals() {
        const quantite = parseFloat(quantiteInput.value) || 0;
        const prix = parseFloat(prixInput.value) || 0;
        const total = quantite * prix;
        
        recapPrix.textContent = prix.toLocaleString() + ' CFA';
        recapTotal.textContent = total.toLocaleString() + ' CFA';
    }
    
    prixInput.addEventListener('input', updateTotals);
    
    // Générer la référence facture automatique
    function genererReference() {
        const date = new Date();
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        
        // Récupérer l'ID de la collecte pour plus d'unicité
        const collecteId = collecteSelect.value;
        const suffixe = collecteId ? `-${collecteId}` : '';
        
        const reference = `ACH-${year}${month}${day}-${random}${suffixe}`;
        referenceInput.value = reference;
    }
    
    // Générer la référence au chargement
    genererReference();
    
    // Mettre à jour les champs quantité max quand on sélectionne
    collecteSelect.dispatchEvent(new Event('change'));
</script>
@endsection