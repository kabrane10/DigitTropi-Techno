@extends('layouts.admin')

@section('title', 'Modifier crédit')
@section('header', 'Modifier le crédit')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.credits.update', $credit) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Taux d'intérêt (%) *</label>
                <input type="number" name="taux_interet" required min="0" max="100" step="0.5"
                       value="{{ old('taux_interet', $credit->taux_interet) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Statut</label>
                <select name="statut" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="actif" {{ $credit->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="rembourse" {{ $credit->statut == 'rembourse' ? 'selected' : '' }}>Remboursé</option>
                    <option value="impaye" {{ $credit->statut == 'impaye' ? 'selected' : '' }}>Impayé</option>
                    <option value="restructure" {{ $credit->statut == 'restructure' ? 'selected' : '' }}>Restructuré</option>
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Conditions</label>
                <textarea name="conditions" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('conditions', $credit->conditions) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.credits.show', $credit) }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection