<!-- Bordereau d'Achat / Facture de vente - Version impression -->
<div class="info-section">
    <h3 style="margin-bottom: 15px;">Informations de vente</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Numéro de facture</div>
            <div class="info-value">{{ $contenu['numero_vente'] ?? $contenu['numero_achat'] ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Date de vente</div>
            <div class="info-value">{{ $contenu['date_vente'] ?? $contenu['date_achat'] ?? 'N/A' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Mode de paiement</div>
            <div class="info-value">{{ $contenu['mode_paiement'] ?? 'N/A' }}</div>
        </div>
        @if(isset($contenu['reference_facture']))
        <div class="info-item">
            <div class="info-label">Référence facture</div>
            <div class="info-value">{{ $contenu['reference_facture'] }}</div>
        </div>
        @endif
        <div class="info-item">
            <div class="info-label">Statut</div>
            <div class="info-value">
                <span class="badge {{ ($contenu['statut'] ?? 'confirme') == 'confirme' ? 'badge-confirme' : 'badge-attente' }}">
                    {{ $contenu['statut'] ?? 'Confirmé' }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Vendeur -->
<div class="info-section">
    <h3 style="margin-bottom: 15px;">Vendeur</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Nom</div>
            <div class="info-value">
                @if(is_array($contenu['vendeur'] ?? null))
                    {{ $contenu['vendeur']['nom'] ?? 'Tropi-Techno Sarl' }}
                @else
                    {{ $contenu['vendeur'] ?? 'Tropi-Techno Sarl' }}
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Adresse</div>
            <div class="info-value">
                @if(is_array($contenu['vendeur'] ?? null))
                    {{ $contenu['vendeur']['adresse'] ?? 'RN:17, Bamabodolo, Sokodé-Togo' }}
                @else
                    'RN:17, Bamabodolo, Sokodé-Togo'
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Téléphone</div>
            <div class="info-value">
                @if(is_array($contenu['vendeur'] ?? null))
                    {{ $contenu['vendeur']['tel'] ?? '+228 25 50 63 12' }}
                @else
                    '+228 25 50 63 12'
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Acheteur -->
<div class="info-section">
    <h3 style="margin-bottom: 15px;">Acheteur</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Nom</div>
            <div class="info-value">
                @if(is_array($contenu['acheteur'] ?? null))
                    {{ $contenu['acheteur']['nom'] ?? 'N/A' }}
                @elseif(is_array($contenu['fournisseur'] ?? null))
                    {{ $contenu['fournisseur']['nom'] ?? 'N/A' }}
                @else
                    {{ $contenu['acheteur'] ?? $contenu['fournisseur'] ?? 'N/A' }}
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Code</div>
            <div class="info-value">
                @if(is_array($contenu['acheteur'] ?? null))
                    {{ $contenu['acheteur']['code'] ?? 'N/A' }}
                @elseif(is_array($contenu['fournisseur'] ?? null))
                    {{ $contenu['fournisseur']['code'] ?? 'N/A' }}
                @else
                    'N/A'
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Contact</div>
            <div class="info-value">
                @if(is_array($contenu['acheteur'] ?? null))
                    {{ $contenu['acheteur']['contact'] ?? 'N/A' }}
                @elseif(is_array($contenu['fournisseur'] ?? null))
                    {{ $contenu['fournisseur']['contact'] ?? 'N/A' }}
                @else
                    'N/A'
                @endif
            </div>
        </div>
        <div class="info-item">
            <div class="info-label">Localisation</div>
            <div class="info-value">
                @if(is_array($contenu['acheteur'] ?? null))
                    {{ $contenu['acheteur']['localisation'] ?? 'N/A' }}
                @else
                    'N/A'
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Tableau des produits -->
<div class="table-section">
    <h3 style="margin-bottom: 15px;">Produits vendus</h3>
    <table class="achat-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @if(isset($contenu['produits']) && is_array($contenu['produits']))
                @foreach($contenu['produits'] as $produit)
                @php 
                    $quantite = is_array($produit) ? ($produit['quantite'] ?? 0) : 0;
                    $prix = is_array($produit) ? ($produit['prix_unitaire'] ?? 0) : 0;
                    $montant = is_array($produit) ? ($produit['montant'] ?? $quantite * $prix) : 0;
                    $total += $montant;
                @endphp
                <tr>
                    <td>{{ is_array($produit) ? ($produit['nom'] ?? 'N/A') : $produit }}</td>
                    <td class="text-right">{{ number_format($quantite) }}</td>
                    <td class="text-right">{{ number_format($prix, 0, ',', ' ') }} CFA</td>
                    <td class="text-right">{{ number_format($montant, 0, ',', ' ') }} CFA</td>
                </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format($total, 0, ',', ' ') }} CFA</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Observations -->
@if(isset($contenu['observations']) && !is_array($contenu['observations']))
<div class="info-section">
    <h3 style="margin-bottom: 10px;">Observations</h3>
    <p>{{ $contenu['observations'] }}</p>
</div>
@endif

<style>
    .achat-table {
        width: 100%;
        border-collapse: collapse;
    }
    .achat-table th, .achat-table td {
        border: 1px solid #ddd;
        padding: 10px;
    }
    .achat-table th {
        background: #f5f5f5;
    }
    .text-right {
        text-align: right;
    }
    .total-row {
        background: #f0fdf4;
    }
    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
    }
    .badge-confirme {
        background: #d1fae5;
        color: #065f46;
    }
    .badge-attente {
        background: #fef3c7;
        color: #92400e;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .info-section {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }
    .info-item {
        margin-bottom: 10px;
    }
    .info-label {
        font-size: 11px;
        color: #666;
        margin-bottom: 3px;
    }
    .info-value {
        font-size: 13px;
        font-weight: 500;
    }
</style>