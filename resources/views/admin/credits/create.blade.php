@extends('layouts.admin')

@section('title', 'Nouveau crédit')
@section('header', 'Accorder un crédit agricole')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.credits.store') }}" method="POST">
        @csrf
        
        @if(request()->has('producteur_id'))
            <input type="hidden" name="producteur_id" value="{{ request('producteur_id') }}">
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <p class="text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Crédit pour : <strong>{{ \App\Models\Producteur::find(request('producteur_id'))->nom_complet }}</strong>
                </p>
            </div>
            @else
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}">{{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold mb-2">Producteur *</label>
                <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez un producteur</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}">{{ $producteur->nom_complet }} - {{ $producteur->code_producteur }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Coopérative *</label>
                <select name="cooperative_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">Sélectionnez une coopérative</option>
                    @foreach($cooperatives as $cooperative)
                    <option value="{{ $cooperative->id }}">{{ $cooperative->nom }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Montant total (CFA) *</label>
                <input type="number" name="montant_total" required min="1000" step="1000"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 100000">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Taux d'intérêt (%) *</label>
                <input type="number" name="taux_interet" required min="0" max="100" step="0.5"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 5">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Durée (mois) *</label>
                <input type="number" name="duree_mois" required min="1" max="60"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 12">
            </div>
            
            <div>
                <label class="block text-sm font-semibold mb-2">Date d'octroi *</label>
                <input type="date" name="date_octroi" required value="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">Conditions particulières</label>
                <textarea name="conditions" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Conditions spécifiques du crédit..."></textarea>
            </div>
        </div>
        
        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h4 class="font-semibold text-blue-800 mb-2">Informations</h4>
            <p class="text-sm text-blue-700">Le montant des intérêts sera calculé automatiquement. La date d'échéance sera fixée à +{{ old('duree_mois', 12) }} mois.</p>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.credits.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i>Accorder le crédit
            </button>
        </div>
    </form>
</div>

<script>
    // Calcul automatique de l'échéance
    document.querySelector('input[name="duree_mois"]').addEventListener('change', function() {
        const duree = this.value;
        const dateOctroi = document.querySelector('input[name="date_octroi"]').value;
        if (dateOctroi && duree) {
            const date = new Date(dateOctroi);
            date.setMonth(date.getMonth() + parseInt(duree));
            const echeance = date.toISOString().split('T')[0];
            // Afficher l'échéance calculée
        }
    });
</script>
@endsection