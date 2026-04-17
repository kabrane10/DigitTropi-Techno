@extends('layouts.admin')

@section('title', 'Ajouter du stock')
@section('header', 'Ajouter du stock')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.stocks.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-box text-primary mr-1"></i> Produit *
                </label>
                <input type="text" name="produit" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: Maïs, Soja, Arachide...">
                @error('produit')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marker-alt text-primary mr-1"></i> Zone *
                </label>
                <select name="zone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez une zone</option>
                    <option value="Centrale">Centrale</option>
                    <option value="Kara">Kara</option>
                    <option value="Savanes">Savanes</option>
                </select>
                @error('zone')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-warehouse text-primary mr-1"></i> Entrepôt
                </label>
                <input type="text" name="entrepot" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Nom de l'entrepôt">
                @error('entrepot')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité à ajouter *
                </label>
                <input type="number" step="0.01" name="quantite" id="quantite" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Quantité">
                @error('quantite')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-chart-line text-primary mr-1"></i> Seuil d'alerte
                </label>
                <input type="number" step="0.01" name="seuil_alerte" value="100"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Seuil d'alerte (défaut: 100)">
                <p class="text-xs text-gray-500 mt-1">En dessous de ce seuil, une alerte sera affichée</p>
                @error('seuil_alerte')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-ruler-combined text-primary mr-1"></i> Unité *
                </label>
                <select name="unite" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="kg">Kilogramme (kg)</option>
                    <option value="tonne">Tonne (t)</option>
                    <option value="sac">Sac</option>
                </select>
                @error('unite')
                    <p class="text-red-500 text-xs mt-1">{{ session('message') }}</p>
                @enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-sticky-note text-primary mr-1"></i> Notes
                </label>
                <textarea name="notes" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires..."></textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                <div>
                    <p class="font-semibold text-blue-800">Information</p>
                    <p class="text-sm text-blue-700">Si le produit existe déjà dans cette zone, la quantité sera ajoutée au stock existant.</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.stocks.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i> Ajouter au stock
            </button>
        </div>
    </form>
</div>

<script>
    const quantiteInput = document.getElementById('quantite');
    
    quantiteInput.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
    });
</script>
@endsection