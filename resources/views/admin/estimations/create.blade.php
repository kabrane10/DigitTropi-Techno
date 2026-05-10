@extends('layouts.admin')

@section('title', 'Nouvelle estimation')
@section('header', 'Fiche d\'estimation de besoin')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.estimations.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Sélectionnez</option>
                    @foreach($producteurs as $p)
                    <option value="{{ $p->id }}">{{ $p->nom_complet }} ({{ $p->code_producteur }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Semence *</label>
                <select name="semence_id" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Sélectionnez</option>
                    @foreach($semences as $s)
                    <option value="{{ $s->id }}">{{ $s->nom }} ({{ $s->variete }})</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité estimée *</label>
                <input type="number" step="0.01" name="quantite_estimee" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie prévue (ha) *</label>
                <input type="number" step="0.01" name="superficie_prevue" required class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Montant crédit estimé (CFA)</label>
                <input type="number" step="1000" name="credit_montant" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date estimation *</label>
                <input type="date" name="date_estimation" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg">
                    <option value="en_attente"> En attente</option>
                    <option value="approuve"> Approuvé</option>
                    <option value="rejete"> Rejeté</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.estimations.index') }}" class="px-4 py-2 border rounded-lg">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i>Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection