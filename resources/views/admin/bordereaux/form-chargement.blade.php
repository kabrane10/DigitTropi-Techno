@extends('layouts.admin')

@section('title', 'Bordereau de chargement')
@section('header', 'Générer un bordereau de chargement')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.bordereaux.generer-chargement') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Produit *</label>
                <input type="text" name="produit" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité (kg) *</label>
                <input type="number" step="0.01" name="quantite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Destination *</label>
                <input type="text" name="destination" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de départ *</label>
                <input type="date" name="date_depart" required value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Transporteur *</label>
                <input type="text" name="transporteur" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Immatriculation *</label>
                <input type="text" name="immatriculation" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Conducteur *</label>
                <input type="text" name="conducteur" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"></textarea>
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
@endsection