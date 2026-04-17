@extends('layouts.admin')

@section('title', 'Modifier achat')
@section('header', 'Modifier le bordereau d\'achat')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.achats.update', $achat) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code achat</label>
                <input type="text" value="{{ $achat->code_achat }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'achat *</label>
                <input type="date" name="date_achat" required value="{{ old('date_achat', $achat->date_achat->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Collecte</label>
                <input type="text" value="{{ $achat->collecte->code_collecte }} - {{ $achat->collecte->producteur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Acheteur *</label>
                <input type="text" name="acheteur" required value="{{ old('acheteur', $achat->acheteur) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité (kg) *</label>
                <input type="number" step="0.01" name="quantite" id="quantite" required 
                       value="{{ old('quantite', $achat->quantite) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_achat" id="prix_achat" required 
                       value="{{ old('prix_achat', $achat->prix_achat) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Mode de paiement *</label>
                <select name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="especes" {{ $achat->mode_paiement == 'especes' ? 'selected' : '' }}>Espèces</option>
                    <option value="virement" {{ $achat->mode_paiement == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                    <option value="cheque" {{ $achat->mode_paiement == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="mobile_money" {{ $achat->mode_paiement == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="confirme" {{ $achat->statut == 'confirme' ? 'selected' : '' }}>Confirmé</option>
                    <option value="en_attente" {{ $achat->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="annule" {{ $achat->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Référence facture</label>
                <input type="text" name="reference_facture" value="{{ old('reference_facture', $achat->reference_facture) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('notes', $achat->notes) }}</textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold mb-3">Récapitulatif</h4>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="text-xl font-bold text-primary" id="quantite_display">{{ number_format($achat->quantite) }} kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Prix unitaire</p>
                    <p class="text-xl font-bold text-primary" id="prix_display">{{ number_format($achat->prix_achat, 0, ',', ' ') }} CFA</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Montant total</p>
                    <p class="text-xl font-bold text-green-600" id="total_display">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.achats.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    const quantiteInput = document.getElementById('quantite');
    const prixInput = document.getElementById('prix_achat');
    const quantiteDisplay = document.getElementById('quantite_display');
    const prixDisplay = document.getElementById('prix_display');
    const totalDisplay = document.getElementById('total_display');
    
    function updateTotals() {
        const quantite = parseFloat(quantiteInput.value) || 0;
        const prix = parseFloat(prixInput.value) || 0;
        const total = quantite * prix;
        
        quantiteDisplay.textContent = quantite.toLocaleString() + ' kg';
        prixDisplay.textContent = prix.toLocaleString() + ' CFA';
        totalDisplay.textContent = total.toLocaleString() + ' CFA';
    }
    
    quantiteInput.addEventListener('input', updateTotals);
    prixInput.addEventListener('input', updateTotals);
</script>
@endsection