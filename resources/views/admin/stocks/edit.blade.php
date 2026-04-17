@extends('layouts.admin')

@section('title', 'Modifier le stock')
@section('header', 'Modifier le seuil d\'alerte')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.stocks.update', $stock) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Produit</label>
                <input type="text" value="{{ $stock->produit }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Zone</label>
                <input type="text" value="{{ $stock->zone }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Stock actuel</label>
                <input type="text" value="{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Unité</label>
                <input type="text" value="{{ $stock->unite }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Seuil d'alerte *
                </label>
                <input type="number" step="0.01" name="seuil_alerte" required 
                       value="{{ old('seuil_alerte', $stock->seuil_alerte) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p class="text-xs text-gray-500 mt-1">En dessous de ce seuil, une alerte sera affichée</p>
                @error('seuil_alerte')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Dernier mouvement</label>
                <input type="text" value="{{ $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y H:i') : '-' }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires...">{{ old('notes', $stock->notes ?? '') }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-yellow-800">Information</p>
                    <p class="text-sm text-yellow-700">
                        Actuellement: <strong>{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</strong> en stock.
                        Seuil d'alerte: <strong>{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</strong>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.stocks.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i> Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection