<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-gray-500 text-sm">Produit</label>
            <p class="font-semibold">{{ $contenu['produit'] }}</p>
        </div>
        <div>
            <label class="text-gray-500 text-sm">Quantité</label>
            <p class="font-semibold">{{ number_format($contenu['quantite']) }} kg</p>
        </div>
        <div>
            <label class="text-gray-500 text-sm">Destination</label>
            <p>{{ $contenu['destination'] }}</p>
        </div>
        <div>
            <label class="text-gray-500 text-sm">Date de départ</label>
            <p>{{ \Carbon\Carbon::parse($contenu['date_depart'])->format('d/m/Y') }}</p>
        </div>
    </div>
    
    <div class="border-t pt-4">
        <h4 class="font-semibold mb-3">Informations transport</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Transporteur</label>
                <p>{{ $contenu['transporteur'] }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Immatriculation</label>
                <p>{{ $contenu['immatriculation'] }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Conducteur</label>
                <p>{{ $contenu['conducteur'] }}</p>
            </div>
        </div>
    </div>
    
    @if(isset($contenu['observations']))
    <div class="border-t pt-4">
        <label class="text-gray-500 text-sm">Observations</label>
        <p class="mt-1">{{ $contenu['observations'] }}</p>
    </div>
    @endif
</div>