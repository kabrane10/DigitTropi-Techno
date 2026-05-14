{{-- resources/views/admin/partials/_beneficiaire_selector.blade.php --}}

<div class="mb-6">
    <label class="block text-sm font-semibold mb-3">
        <i class="fas fa-users text-primary mr-1"></i> Type de bénéficiaire *
    </label>
    <div class="flex flex-wrap gap-4">
        <label class="inline-flex items-center cursor-pointer">
            <input type="radio" name="beneficiaire_type" value="producteur" 
                   {{ old('beneficiaire_type', $beneficiaire_type ?? 'producteur') == 'producteur' ? 'checked' : '' }}
                   class="form-radio text-primary beneficiaire-radio w-4 h-4">
            <span class="ml-2 flex items-center">
                <i class="fas fa-user text-green-600 mr-1"></i> 
                Producteur individuel
            </span>
        </label>
        <label class="inline-flex items-center cursor-pointer">
            <input type="radio" name="beneficiaire_type" value="cooperative" 
                   {{ old('beneficiaire_type') == 'cooperative' ? 'checked' : '' }}
                   class="form-radio text-primary beneficiaire-radio w-4 h-4">
            <span class="ml-2 flex items-center">
                <i class="fas fa-handshake text-purple-600 mr-1"></i> 
                Coopérative
            </span>
        </label>
    </div>
</div>

<div id="producteur-section" class="beneficiaire-section mb-6" style="display: {{ old('beneficiaire_type', $beneficiaire_type ?? 'producteur') == 'producteur' ? 'block' : 'none' }}">
    <div class="bg-green-50 rounded-lg p-4">
        <label class="block text-sm font-semibold mb-2 text-green-800">
            <i class="fas fa-user text-green-600 mr-1"></i> Sélectionnez le producteur *
        </label>
        <select name="producteur_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-green-500">
            <option value="">-- Choisissez un producteur --</option>
            @foreach($producteurs ?? [] as $producteur)
            <option value="{{ $producteur->id }}" {{ old('producteur_id', $producteur_id ?? '') == $producteur->id ? 'selected' : '' }}>
                {{ $producteur->nom_complet }} ({{ $producteur->code_producteur }}) - {{ $producteur->region }}
            </option>
            @endforeach
        </select>
        @if(($producteurs ?? collect())->isEmpty())
        <p class="text-red-500 text-xs mt-1">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            Aucun producteur actif. <a href="{{ route('admin.producteurs.create') }}" class="underline">Créez-en un</a>
        </p>
        @endif
    </div>
</div>

<div id="cooperative-section" class="beneficiaire-section mb-6" style="display: {{ old('beneficiaire_type') == 'cooperative' ? 'block' : 'none' }}">
    <div class="bg-purple-50 rounded-lg p-4">
        <label class="block text-sm font-semibold mb-2 text-purple-800">
            <i class="fas fa-handshake text-purple-600 mr-1"></i> Sélectionnez la coopérative *
        </label>
        <select name="cooperative_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-purple-500">
            <option value="">-- Choisissez une coopérative --</option>
            @foreach($cooperatives ?? [] as $cooperative)
            <option value="{{ $cooperative->id }}" {{ old('cooperative_id', $cooperative_id ?? '') == $cooperative->id ? 'selected' : '' }}>
                {{ $cooperative->nom }} ({{ $cooperative->code_cooperative }}) - {{ $cooperative->region }}
            </option>
            @endforeach
        </select>
        @if(($cooperatives ?? collect())->isEmpty())
        <p class="text-red-500 text-xs mt-1">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            Aucune coopérative active. <a href="{{ route('admin.cooperatives.create') }}" class="underline">Créez-en une</a>
        </p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('.beneficiaire-radio');
    const producteurSection = document.getElementById('producteur-section');
    const cooperativeSection = document.getElementById('cooperative-section');
    
    if (radios.length && producteurSection && cooperativeSection) {
        radios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'producteur') {
                    producteurSection.style.display = 'block';
                    cooperativeSection.style.display = 'none';
                } else {
                    producteurSection.style.display = 'none';
                    cooperativeSection.style.display = 'block';
                }
            });
        });
    }
});
</script>