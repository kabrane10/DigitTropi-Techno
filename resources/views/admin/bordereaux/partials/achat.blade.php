<!-- Partie Bordereau d'Achat / Facture de vente -->
<div class="space-y-6">
    <!-- Informations générales -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-primary mb-3">Informations de vente</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Numéro de facture</label>
                <p class="font-semibold">
                    {{ is_string($contenu['numero_vente'] ?? null) ? $contenu['numero_vente'] : (is_string($contenu['numero_achat'] ?? null) ? $contenu['numero_achat'] : 'N/A') }}
                </p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Date de vente</label>
                <p>{{ is_string($contenu['date_vente'] ?? null) ? $contenu['date_vente'] : (is_string($contenu['date_achat'] ?? null) ? $contenu['date_achat'] : 'N/A') }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Vendeur</label>
                <p>{{ is_array($contenu['vendeur'] ?? null) ? ($contenu['vendeur']['nom'] ?? 'Tropi-Techno Sarl') : (is_string($contenu['vendeur'] ?? null) ? $contenu['vendeur'] : 'Tropi-Techno Sarl') }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Mode de paiement</label>
                <p>{{ is_string($contenu['mode_paiement'] ?? null) ? $contenu['mode_paiement'] : 'N/A' }}</p>
            </div>
            @if(isset($contenu['reference_facture']) && is_string($contenu['reference_facture']))
            <div>
                <label class="text-gray-500 text-sm">Référence facture</label>
                <p>{{ $contenu['reference_facture'] }}</p>
            </div>
            @endif
            <div>
                <label class="text-gray-500 text-sm">Statut</label>
                <p>
                    @php
                        $statut = is_string($contenu['statut'] ?? null) ? $contenu['statut'] : 'confirme';
                    @endphp
                    <span class="px-2 py-1 text-xs rounded-full 
                        @if($statut == 'confirme') bg-green-100 text-green-800
                        @elseif($statut == 'en_attente') bg-yellow-100 text-yellow-800
                        @else bg-blue-100 text-blue-800
                        @endif">
                        {{ ucfirst($statut) }}
                    </span>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Informations acheteur (producteur) -->
    <div class="border-b pb-4">
        <h4 class="font-semibold text-primary mb-3">Informations acheteur</h4>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-gray-500 text-sm">Nom du producteur</label>
                @php
                    $acheteurNom = 'N/A';
                    if (isset($contenu['acheteur']) && is_array($contenu['acheteur'])) {
                        $acheteurNom = $contenu['acheteur']['nom'] ?? 'N/A';
                    } elseif (isset($contenu['fournisseur']) && is_array($contenu['fournisseur'])) {
                        $acheteurNom = $contenu['fournisseur']['nom'] ?? 'N/A';
                    } elseif (is_string($contenu['acheteur'] ?? null)) {
                        $acheteurNom = $contenu['acheteur'];
                    }
                @endphp
                <p class="font-semibold">{{ $acheteurNom }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Code producteur</label>
                @php
                    $acheteurCode = 'N/A';
                    if (isset($contenu['acheteur']) && is_array($contenu['acheteur'])) {
                        $acheteurCode = $contenu['acheteur']['code'] ?? 'N/A';
                    } elseif (isset($contenu['fournisseur']) && is_array($contenu['fournisseur'])) {
                        $acheteurCode = $contenu['fournisseur']['code'] ?? 'N/A';
                    }
                @endphp
                <p>{{ $acheteurCode }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Contact</label>
                @php
                    $acheteurContact = 'N/A';
                    if (isset($contenu['acheteur']) && is_array($contenu['acheteur'])) {
                        $acheteurContact = $contenu['acheteur']['contact'] ?? 'N/A';
                    } elseif (isset($contenu['fournisseur']) && is_array($contenu['fournisseur'])) {
                        $acheteurContact = $contenu['fournisseur']['contact'] ?? 'N/A';
                    }
                @endphp
                <p>{{ $acheteurContact }}</p>
            </div>
            <div>
                <label class="text-gray-500 text-sm">Localisation</label>
                @php
                    $acheteurLocalisation = 'N/A';
                    if (isset($contenu['acheteur']) && is_array($contenu['acheteur'])) {
                        $acheteurLocalisation = $contenu['acheteur']['localisation'] ?? 'N/A';
                    }
                @endphp
                <p>{{ $acheteurLocalisation }}</p>
            </div>
        </div>
    </div>
    
    <!-- Tableau des produits vendus -->
    <div>
        <h4 class="font-semibold text-primary mb-3">Produits vendus</h4>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm">Produit</th>
                        <th class="px-4 py-2 text-right text-sm">Quantité</th>
                        <th class="px-4 py-2 text-right text-sm">Prix unitaire</th>
                        <th class="px-4 py-2 text-right text-sm">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @if(isset($contenu['produits']) && is_array($contenu['produits']))
                        @foreach($contenu['produits'] as $produit)
                        @php
                            $nom = is_array($produit) ? ($produit['nom'] ?? 'N/A') : 'N/A';
                            $quantite = is_array($produit) ? ($produit['quantite'] ?? 0) : 0;
                            $prix = is_array($produit) ? ($produit['prix_unitaire'] ?? 0) : 0;
                            $montant = is_array($produit) ? ($produit['montant'] ?? 0) : 0;
                        @endphp
                        <tr>
                            <td class="px-4 py-2">{{ $nom }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($quantite) }} {{ $produit['unite'] ?? '' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($prix, 0, ',', ' ') }} CFA</td>
                            <td class="px-4 py-2 text-right">{{ number_format($montant, 0, ',', ' ') }} CFA</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">Aucun produit</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-right font-semibold">TOTAL</td>
                        @php
                            $total = 0;
                            if (isset($contenu['montant_total'])) {
                                $total = is_numeric($contenu['montant_total']) ? $contenu['montant_total'] : 0;
                            } elseif (isset($contenu['produits']) && is_array($contenu['produits'])) {
                                foreach ($contenu['produits'] as $produit) {
                                    $total += is_array($produit) ? ($produit['montant'] ?? 0) : 0;
                                }
                            }
                        @endphp
                        <td class="px-4 py-2 text-right font-bold text-primary">{{ number_format($total, 0, ',', ' ') }} CFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Observations -->
    @if(isset($contenu['observations']) && is_string($contenu['observations']) && !empty($contenu['observations']))
    <div class="border-t pt-4">
        <label class="text-gray-500 text-sm">Observations</label>
        <p class="mt-1 p-3 bg-gray-50 rounded-lg">{{ $contenu['observations'] }}</p>
    </div>
    @endif
</div>