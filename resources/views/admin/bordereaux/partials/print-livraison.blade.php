<!-- Bordereau de Livraison -->
<div class="info-section">
    <h3 style="margin-bottom: 15px;">Destinataire</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Nom</div>
            <div class="info-value">{{ $contenu['destinataire'] ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Adresse de livraison</div>
            <div class="info-value">{{ $contenu['adresse_livraison'] ?? '-' }}</div>
        </div>
        @if(isset($contenu['telephone']))
        <div class="info-item">
            <div class="info-label">Téléphone</div>
            <div class="info-value">{{ $contenu['telephone'] }}</div>
        </div>
        @endif
    </div>
</div>

<div class="info-section">
    <h3 style="margin-bottom: 15px;">Informations transport</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Transporteur</div>
            <div class="info-value">{{ $contenu['transporteur'] ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Date livraison prévue</div>
            <div class="info-value">{{ isset($contenu['date_livraison_prevue']) ? \Carbon\Carbon::parse($contenu['date_livraison_prevue'])->format('d/m/Y') : '-' }}</div>
        </div>
        @if(isset($contenu['immatriculation']))
        <div class="info-item">
            <div class="info-label">Immatriculation</div>
            <div class="info-value">{{ $contenu['immatriculation'] }}</div>
        </div>
        @endif
        @if(isset($contenu['conducteur']))
        <div class="info-item">
            <div class="info-label">Conducteur</div>
            <div class="info-value">{{ $contenu['conducteur'] }}</div>
        </div>
        @endif
    </div>
</div>

<!-- Tableau des produits livrés -->
<div class="table-section">
    <h3 style="margin-bottom: 15px;">Produits à livrer</h3>
    <table class="livraison-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Unité</th>
                <th class="text-right">Poids unitaire (kg)</th>
                <th class="text-right">Poids total (kg)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPoids = 0; @endphp
            @foreach($contenu['produits'] as $produit)
            @php 
                // Gérer l'unité avec une valeur par défaut
                $unite = $produit['unite'] ?? 'kg';
                
                // Calculer le poids unitaire en kg
                if ($unite == 'tonne') {
                    $poidsUnitaire = 1000;
                } elseif ($unite == 'sac') {
                    $poidsUnitaire = 50;
                } elseif ($unite == 'carton') {
                    $poidsUnitaire = 20;
                } else {
                    $poidsUnitaire = 1; // kg par défaut
                }
                
                $poidsTotal = $produit['quantite'] * $poidsUnitaire;
                $totalPoids += $poidsTotal;
            @endphp
            <tr>
                <td>{{ $produit['nom'] }}</td>
                <td class="text-right">{{ number_format($produit['quantite'], 2) }}</td>
                <td class="text-right">{{ $unite }}</td>
                <td class="text-right">{{ number_format($poidsUnitaire) }}</td>
                <td class="text-right">{{ number_format($poidsTotal) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>POIDS TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format($totalPoids) }} kg</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

@if(isset($contenu['observations']) && $contenu['observations'])
<div class="info-section">
    <h3 style="margin-bottom: 10px;">Observations</h3>
    <p>{{ $contenu['observations'] }}</p>
</div>
@endif

<!-- État de livraison -->
<div class="signatures">
    <div class="signature-box">
        <p class="signature-title">Bon de livraison</p>
        <div class="checkbox-group">
            <p>☐ Livraison conforme</p>
            <p>☐ Réserves émises</p>
            <p class="signature-line">Signature du destinataire : _________________</p>
        </div>
    </div>
</div>

<style>
    .livraison-table {
        width: 100%;
        border-collapse: collapse;
    }
    .livraison-table th, .livraison-table td {
        border: 1px solid #ddd;
        padding: 10px;
    }
    .livraison-table th {
        background: #f5f5f5;
    }
    .text-right {
        text-align: right;
    }
    .total-row {
        background: #f0fdf4;
    }
    .signature-box {
        text-align: center;
    }
    .signature-title {
        font-weight: bold;
        margin-bottom: 10px;
    }
    .checkbox-group {
        text-align: left;
        margin-top: 20px;
    }
    .checkbox-group p {
        margin: 5px 0;
    }
    .signature-line {
        margin-top: 30px;
        padding-top: 10px;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .info-item {
        margin-bottom: 10px;
    }
    .info-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 3px;
    }
    .info-value {
        font-size: 14px;
        font-weight: 500;
    }
</style>