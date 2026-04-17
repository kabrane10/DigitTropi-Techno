@extends('layouts.admin')

@section('title', 'Nouvel achat')
@section('header', 'Créer un bordereau d\'achat')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.achats.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Collecte *</label>
                <select name="collecte_id" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Sélectionnez une collecte</option>
                    @foreach($collectes as $collecte)
                    <option value="{{ $collecte->id }}">
                        {{ $collecte->code_collecte }} - {{ $collecte->producteur->nom_complet }} - {{ $collecte->produit }} ({{ number_format($collecte->quantite_nette) }} kg)
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'achat *</label>
                <input type="date" name="date_achat" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Acheteur *</label>
                <input type="text" name="acheteur" required class="w-full px-4 py-2 border rounded-lg" placeholder="Nom de l'acheteur">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité (kg) *</label>
                <input type="number" step="0.01" name="quantite" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Prix unitaire (CFA/kg) *</label>
                <input type="number" step="1" name="prix_achat" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Mode de paiement *</label>
                <select name="mode_paiement" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="especes">Espèces</option>
                    <option value="virement">Virement bancaire</option>
                    <option value="cheque">Chèque</option>
                    <option value="mobile_money">Mobile Money</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Référence facture</label>
                <input type="text" name="reference_facture" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.achats.index') }}" class="px-4 py-2 border rounded-lg">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg">Enregistrer</button>
        </div>
    </form>
</div>
@endsection