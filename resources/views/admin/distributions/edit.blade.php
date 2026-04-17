@extends('layouts.admin')

@section('title', 'Modifier la distribution')
@section('header', 'Modifier une distribution de semences')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.distributions.update', $distribution) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Code distribution</label>
                <input type="text" value="{{ $distribution->code_distribution }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur</label>
                <input type="text" value="{{ $distribution->producteur->nom_complet }}" disabled
                       class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Semence *</label>
                <select name="semence_id" id="semence_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez une semence</option>
                    @foreach($semences as $semence)
                    <option value="{{ $semence->id }}" {{ $distribution->semence_id == $semence->id ? 'selected' : '' }} 
                            data-stock="{{ $semence->stock_disponible + ($distribution->semence_id == $semence->id ? $distribution->quantite : 0) }}">
                        {{ $semence->nom }} ({{ $semence->variete }}) - Stock: {{ number_format($semence->stock_disponible + ($distribution->semence_id == $semence->id ? $distribution->quantite : 0)) }} {{ $semence->unite }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Quantité *</label>
                <input type="number" step="0.01" name="quantite" id="quantite" required 
                       value="{{ old('quantite', $distribution->quantite) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                <p id="stockInfo" class="text-xs text-gray-500 mt-1"></p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Superficie emblavée (ha) *</label>
                <input type="number" step="0.01" name="superficie_emblevee" required 
                       value="{{ old('superficie_emblevee', $distribution->superficie_emblevee) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date de distribution *</label>
                <input type="date" name="date_distribution" required 
                       value="{{ old('date_distribution', $distribution->date_distribution->format('Y-m-d')) }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Saison *</label>
                <select name="saison" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="principale" {{ $distribution->saison == 'principale' ? 'selected' : '' }}>☀️ Principale</option>
                    <option value="contre-saison" {{ $distribution->saison == 'contre-saison' ? 'selected' : '' }}>☀️ Contre-saison</option>
                    <option value="hivernage" {{ $distribution->saison == 'hivernage' ? 'selected' : '' }}>☀️ Hivernage</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Crédit associé</label>
                <select name="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Aucun</option>
                    @foreach($credits as $credit)
                    <option value="{{ $credit->id }}" {{ $distribution->credit_id == $credit->id ? 'selected' : '' }}>
                        {{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Observations</label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">{{ old('observations', $distribution->observations) }}</textarea>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.distributions.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Mettre à jour
            </button>
        </div>
    </form>
</div>

<script>
    const semenceSelect = document.getElementById('semence_id');
    const quantiteInput = document.getElementById('quantite');
    const stockInfo = document.getElementById('stockInfo');
    
    function updateStockInfo() {
        const selected = semenceSelect.options[semenceSelect.selectedIndex];
        const stock = selected.dataset.stock;
        if (stock) {
            stockInfo.textContent = 'Stock disponible : ' + Number(stock).toLocaleString() + ' kg';
            if (quantiteInput.value > stock) {
                quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            } else {
                quantiteInput.setCustomValidity('');
            }
        }
    }
    
    semenceSelect.addEventListener('change', updateStockInfo);
    quantiteInput.addEventListener('input', updateStockInfo);
    updateStockInfo();
</script>
@endsection