@extends('layouts.admin')

@section('title', 'Bordereau d\'achat')
@section('header', 'Générer un bordereau d\'achat')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.bordereaux.generer-achat-direct') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informations acheteur (producteur) -->
            <div class="md:col-span-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-user text-primary mr-2"></i>Informations acheteur (Producteur)
                </h3>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur (Acheteur) *</label>
                <select name="producteur_id" id="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}">
                        {{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Contact producteur</label>
                <input type="text" id="contact" readonly class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <!-- Informations vente -->
            <div class="md:col-span-2 mt-2">
                <h3 class="text-lg font-semibold text-dark mb-4 border-b pb-2">
                    <i class="fas fa-shopping-cart text-primary mr-2"></i>Informations vente (Tropi-Techno)
                </h3>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de vente *</label>
                <input type="date" name="date_achat" required value="{{ date('Y-m-d') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Vendeur (Tropi-Techno)</label>
                <input type="text" value="Tropi-Techno Sarl" disabled 
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <input type="text" name="produit" id="produit" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Nom du produit (semences, intrants, etc.)">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité *</label>
                <input type="number" step="0.01" name="quantite" id="quantite" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA) *</label>
                <input type="number" step="1" name="prix_unitaire" id="prix_unitaire" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Mode de paiement *</label>
                <select name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="especes">Espèces</option>
                    <option value="virement">Virement bancaire</option>
                    <option value="cheque">Chèque</option>
                    <option value="mobile_money">Mobile Money</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Référence facture</label>
                <input type="text" name="reference_facture" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="N° de facture">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes / Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires..."></textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3">Récapitulatif de la vente</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="text-xl font-bold text-primary" id="quantite_display">0</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Prix unitaire</p>
                    <p class="text-xl font-bold text-primary" id="prix_display">0 CFA</p>
                </div>
                <div class="bg-white p-3 rounded-lg shadow-sm">
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-green-600" id="total_display">0 CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.bordereaux.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-file-alt mr-2"></i>Générer le bordereau
            </button>
        </div>
    </form>
</div>

<script>
    const producteurSelect = document.getElementById('producteur_id');
    const contactInput = document.getElementById('contact');
    
    // Charger les contacts des producteurs
    const producteurs = @json($producteurs);
    
    producteurSelect.addEventListener('change', function() {
        const selectedId = this.value;
        const producteur = producteurs.find(p => p.id == selectedId);
        if (producteur) {
            contactInput.value = producteur.contact;
        } else {
            contactInput.value = '';
        }
    });
    
    // Calcul automatique
    const quantiteInput = document.getElementById('quantite');
    const prixInput = document.getElementById('prix_unitaire');
    const quantiteDisplay = document.getElementById('quantite_display');
    const prixDisplay = document.getElementById('prix_display');
    const totalDisplay = document.getElementById('total_display');
    
    function updateTotals() {
        const quantite = parseFloat(quantiteInput.value) || 0;
        const prix = parseFloat(prixInput.value) || 0;
        const total = quantite * prix;
        
        quantiteDisplay.textContent = quantite.toLocaleString();
        prixDisplay.textContent = prix.toLocaleString() + ' CFA';
        totalDisplay.textContent = total.toLocaleString() + ' CFA';
    }
    
    quantiteInput.addEventListener('input', updateTotals);
    prixInput.addEventListener('input', updateTotals);
</script>
@endsection