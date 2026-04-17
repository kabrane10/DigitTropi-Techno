
<!-- Informations producteur -->
<div class="info-section">
    <h3 style="margin-bottom: 15px;">Informations producteur</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Nom du producteur</div>
            <div class="info-value">{{ $contenu['producteur']['nom'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Code producteur</div>
            <div class="info-value">{{ $contenu['producteur']['code'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Contact</div>
            <div class="info-value">{{ $contenu['producteur']['contact'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Localisation</div>
            <div class="info-value">{{ $contenu['producteur']['localisation'] }}</div>
        </div>
    </div>
</div>

<!-- Détails de la collecte -->
<div class="table-section">
    <h3 style="margin-bottom: 15px;">Détails de la collecte</h3>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-right">Qté brute</th>
                <th class="text-right">Qté nette</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contenu['produits'] as $produit)
            <tr>
                <td>{{ $produit['nom'] }}</td>
                <td class="text-right">{{ number_format($produit['quantite_brute']) }} kg</td>
                <td class="text-right">{{ number_format($produit['quantite_nette']) }} kg</td>
                <td class="text-right">{{ number_format($produit['prix_unitaire'], 0, ',', ' ') }} CFA</td>
                <td class="text-right">{{ number_format($produit['montant'], 0, ',', ' ') }} CFA</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Totaux -->
<div class="totals">
    <p>Montant total: <strong>{{ number_format($contenu['montant_total'], 0, ',', ' ') }} CFA</strong></p>
    @if(isset($contenu['montant_deduict']) && $contenu['montant_deduict'] > 0)
    <p>Déduction crédit: <strong>- {{ number_format($contenu['montant_deduict'], 0, ',', ' ') }} CFA</strong></p>
    @endif
    <p class="total-grand">Net à payer: {{ number_format($contenu['montant_a_payer'], 0, ',', ' ') }} CFA</p>
</div>