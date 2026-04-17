<div class="space-y-6">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="text-gray-500 text-sm">Numéro de collecte</label>
            <p class="font-semibold">{{ $contenu['numero_collecte'] }}</p>
        </div>
        <div>
            <label class="text-gray-500 text-sm">Date de collecte</label>
            <p>{{ $contenu['date_collecte'] }}</p>
        </div>
    </div>
    
    <div class="border-t pt-4">
        <h4 class="font-semibold mb-3">Informations producteur</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Nom</label>
                <p>{{ $contenu['producteur']['nom'] }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Code producteur</label>
                <p>{{ $contenu['producteur']['code'] }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Contact</label>
                <p>{{ $contenu['producteur']['contact'] }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Localisation</label>
                <p>{{ $contenu['producteur']['localisation'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="border-t pt-4">
        <h4 class="font-semibold mb-3">Détails de la collecte</h4>
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr><th class="px-4 py-2 text-left">Produit</th><th class="px-4 py-2 text-right">Qté brute</th><th class="px-4 py-2 text-right">Qté nette</th><th class="px-4 py-2 text-right">Prix unitaire</th><th class="px-4 py-2 text-right">Montant</th></tr>
            </thead>
            <tbody>
                @foreach($contenu['produits'] as $produit)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $produit['nom'] }}</td>
                    <td class="px-4 py-2 text-right">{{ number_format($produit['quantite_brute']) }} kg</td>
                    <td class="px-4 py-2 text-right">{{ number_format($produit['quantite_nette']) }} kg</td>
                    <td class="px-4 py-2 text-right">{{ number_format($produit['prix_unitaire'], 0, ',', ' ') }} CFA</td>
                    <td class="px-4 py-2 text-right">{{ number_format($produit['montant'], 0, ',', ' ') }} CFA</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr><td colspan="4" class="px-4 py-2 text-right font-semibold">Total :</td><td class="px-4 py-2 text-right font-bold">{{ number_format($contenu['montant_total'], 0, ',', ' ') }} CFA</td></tr>
                @if(isset($contenu['montant_deduict']) && $contenu['montant_deduict'] > 0)
                <tr><td colspan="4" class="px-4 py-2 text-right">Déduction crédit :</td><td class="px-4 py-2 text-right text-red-600">- {{ number_format($contenu['montant_deduict'], 0, ',', ' ') }} CFA</td></tr>
                <tr><td colspan="4" class="px-4 py-2 text-right font-semibold">Net à payer :</td><td class="px-4 py-2 text-right font-bold text-green-600">{{ number_format($contenu['montant_a_payer'], 0, ',', ' ') }} CFA</td></tr>
                @endif
            </tfoot>
        </table>
    </div>
    
    @if(isset($contenu['observations']) && $contenu['observations'])
    <div class="border-t pt-4">
        <label class="text-gray-500 text-sm">Observations</label>
        <p class="mt-1">{{ $contenu['observations'] }}</p>
    </div>
    @endif
</div>