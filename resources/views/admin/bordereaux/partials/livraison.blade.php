<div class="space-y-6">
    <!-- Destinataire -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-primary mb-3">Destinataire</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Nom</label>
                <p class="font-semibold">{{ $contenu['destinataire'] }}</p>
            </div>
            @if(isset($contenu['telephone']))
            <div>
                <label class="text-gray-500 text-sm">Téléphone</label>
                <p>{{ $contenu['telephone'] }}</p>
            </div>
            @endif
            <div class="col-span-2">
                <label class="text-gray-500 text-sm">Adresse de livraison</label>
                <p>{{ $contenu['adresse_livraison'] }}</p>
            </div>
        </div>
    </div>
    
    <!-- Transport -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-primary mb-3">Transport</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Transporteur</label>
                <p>{{ $contenu['transporteur'] }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Date livraison prévue</label>
                <p>{{ \Carbon\Carbon::parse($contenu['date_livraison_prevue'])->format('d/m/Y') }}</p>
            </div>
            @if(isset($contenu['immatriculation']))
            <div>
                <label class="text-gray-500 text-sm">Immatriculation</label>
                <p>{{ $contenu['immatriculation'] }}</p>
            </div>
            @endif
            @if(isset($contenu['conducteur']))
            <div>
                <label class="text-gray-500 text-sm">Conducteur</label>
                <p>{{ $contenu['conducteur'] }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Produits -->
    <div>
        <h4 class="font-semibold text-primary mb-3">Produits à livrer</h4>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm">Produit</th>
                    <th class="px-4 py-2 text-right text-sm">Quantité</th>
                    <th class="px-4 py-2 text-left text-sm">Unité</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($contenu['produits'] as $produit)
                <tr>
                    <td class="px-4 py-2">{{ $produit['nom'] }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($produit['quantite'], 2) }}</td>
                    <td class="px-4 py-2">{{ $produit['unite'] ?? 'kg' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-4 py-2 font-semibold">Total</td>
                    <td class="px-4 py-2 text-right font-semibold">{{ number_format(array_sum(array_column($contenu['produits'], 'quantite')), 2) }}</td>
                    <td class="px-4 py-2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    @if(isset($contenu['observations']) && $contenu['observations'])
    <div class="border-t pt-4">
        <label class="text-gray-500 text-sm">Observations</label>
        <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $contenu['observations'] }}</p>
    </div>
    @endif
    
    <!-- Signatures -->
    <div class="border-t pt-4 mt-4">
        <div class="grid grid-cols-2 gap-8">
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-2">Signature de l'émetteur</p>
                <div class="border-t border-gray-300 pt-2 mt-8">
                    <p class="text-xs text-gray-400">{{ $contenu['emetteur'] ?? '_________________' }}</p>
                </div>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-500 mb-2">Signature du destinataire</p>
                <div class="border-t border-gray-300 pt-2 mt-8">
                    <p class="text-xs text-gray-400">_________________</p>
                </div>
            </div>
        </div>
    </div>
</div>