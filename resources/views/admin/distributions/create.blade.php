@extends('layouts.admin')

@section('title', 'Nouvelle distribution')
@section('header', 'Distribuer des semences')

@section('content')
<div class="bg-white rounded-xl shadow-sm p-6">
    <form action="{{ route('admin.distributions.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Producteur -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-user text-primary mr-1"></i> Producteur *
                </label>
                <select name="producteur_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez un producteur --</option>
                    @foreach($producteurs as $producteur)
                    <option value="{{ $producteur->id }}" {{ old('producteur_id') == $producteur->id ? 'selected' : '' }}>
                        {{ $producteur->nom_complet }} ({{ $producteur->code_producteur }}) - {{ $producteur->region }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Semence -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-seedling text-primary mr-1"></i> Semence *
                </label>
                <select name="semence_id" id="semence_id" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Sélectionnez une semence --</option>
                    @foreach($semences as $semence)
                    <option value="{{ $semence->id }}" 
                            data-stock="{{ $semence->stock_disponible }}"
                            data-unite="{{ $semence->unite }}"
                            {{ old('semence_id') == $semence->id ? 'selected' : '' }}>
                        {{ $semence->nom }} ({{ $semence->variete }}) - Stock: {{ number_format($semence->stock_disponible) }} {{ $semence->unite }}
                    </option>
                    @endforeach
                </select>
                @if($semences->isEmpty())
                <p class="text-red-500 text-xs mt-1">
                    <i class="fas fa-exclamation-triangle mr-1"></i> 
                    Aucune semence en stock. Veuillez d'abord <a href="{{ route('admin.stocks.create') }}" class="underline">ajouter du stock</a>.
                </p>
                @endif
            </div>
            
            <!-- Quantité -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-weight-hanging text-primary mr-1"></i> Quantité *
                </label>
                <div class="flex">
                    <input type="number" step="0.01" name="quantite" id="quantite" required 
                           class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:border-primary">
                    <span class="px-3 py-2 bg-gray-100 border border-l-0 rounded-r-lg" id="unite_label">kg</span>
                </div>
                <p id="stock_info" class="text-xs text-gray-500 mt-1"></p>
            </div>
            
            <!-- Superficie -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-map-marked-alt text-primary mr-1"></i> Superficie emblavée (ha) *
                </label>
                <input type="number" step="0.01" name="superficie_emblevee" required 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                       placeholder="Ex: 2.5">
            </div>
            
            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-calendar text-primary mr-1"></i> Date de distribution *
                </label>
                <input type="date" name="date_distribution" required value="{{ date('Y-m-d') }}" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
            </div>
            
            <!-- Saison -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-cloud-sun text-primary mr-1"></i> Saison *
                </label>
                <select name="saison" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="principale">☀️ Principale (Juin - Septembre)</option>
                    <option value="contre-saison">☀️ Contre-saison (Octobre - Décembre)</option>
                    <option value="hivernage">☀️ Hivernage (Janvier - Mai)</option>
                </select>
            </div>
            
            <!-- Crédit -->
            <div>
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-hand-holding-usd text-primary mr-1"></i> Crédit associé (optionnel)
                </label>
                <select name="credit_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary">
                    <option value="">-- Aucun crédit --</option>
                    @foreach($credits as $credit)
                    <option value="{{ $credit->id }}">
                        {{ $credit->code_credit }} - {{ number_format($credit->montant_restant, 0, ',', ' ') }} CFA restant
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Observations -->
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold mb-2">
                    <i class="fas fa-comment text-primary mr-1"></i> Observations
                </label>
                <textarea name="observations" rows="3" 
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-primary"
                          placeholder="Informations complémentaires..."></textarea>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="mt-6 p-4 bg-green-50 rounded-lg">
            <h4 class="font-semibold text-dark mb-3">Récapitulatif</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Producteur</p>
                    <p class="font-semibold" id="recap_producteur">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Semence</p>
                    <p class="font-semibold" id="recap_semence">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Quantité</p>
                    <p class="font-semibold text-primary" id="recap_quantite">0 kg</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Superficie</p>
                    <p class="font-semibold" id="recap_superficie">0 ha</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('admin.distributions.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Annuler</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary">
                <i class="fas fa-save mr-2"></i> Enregistrer la distribution
            </button>
        </div>
    </form>
</div>

<script>
    const semenceSelect = document.getElementById('semence_id');
    const quantiteInput = document.getElementById('quantite');
    const uniteLabel = document.getElementById('unite_label');
    const stockInfo = document.getElementById('stock_info');
    const recapProducteur = document.getElementById('recap_producteur');
    const recapSemence = document.getElementById('recap_semence');
    const recapQuantite = document.getElementById('recap_quantite');
    const recapSuperficie = document.getElementById('recap_superficie');
    const producteurSelect = document.querySelector('select[name="producteur_id"]');
    const superficieInput = document.querySelector('input[name="superficie_emblevee"]');
    
    // Mettre à jour les infos quand on sélectionne une semence
    semenceSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const stock = selected.dataset.stock;
        const unite = selected.dataset.unite || 'kg';
        
        if (stock) {
            stockInfo.textContent = `Stock disponible: ${Number(stock).toLocaleString()} ${unite}`;
            uniteLabel.textContent = unite;
            
            // Vérifier si la quantité dépasse le stock
            if (quantiteInput.value > stock) {
                quantiteInput.setCustomValidity('Quantité supérieure au stock disponible');
            } else {
                quantiteInput.setCustomValidity('');
            }
        }
        
        recapSemence.textContent = selected.textContent.split('-')[0].trim();
    });
    
    // Vérifier la quantité
    quantiteInput.addEventListener('input', function() {
        const selected = semenceSelect.options[semenceSelect.selectedIndex];
        const stock = selected.dataset.stock;
        const unite = selected.dataset.unite || 'kg';
        
        if (stock && this.value > stock) {
            this.setCustomValidity('Quantité supérieure au stock disponible');
            stockInfo.classList.add('text-red-500');
        } else {
            this.setCustomValidity('');
            stockInfo.classList.remove('text-red-500');
        }
        
        recapQuantite.textContent = `${Number(this.value).toLocaleString()} ${uniteLabel.textContent}`;
    });
    
    // Mettre à jour le récapitulatif producteur
    producteurSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        recapProducteur.textContent = selected.textContent.split('-')[0].trim();
    });
    
    // Mettre à jour le récapitulatif superficie
    superficieInput.addEventListener('input', function() {
        recapSuperficie.textContent = `${Number(this.value).toLocaleString()} ha`;
    });
</script>
@endsection