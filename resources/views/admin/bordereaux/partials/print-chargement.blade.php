<!-- Bordereau de Chargement -->
<div class="info-section">
    <h3 style="margin-bottom: 15px;">Informations de chargement</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Produit</div>
            <div class="info-value">{{ $contenu['produit'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Quantité</div>
            <div class="info-value">{{ number_format($contenu['quantite']) }} kg</div>
        </div>
        <div class="info-item">
            <div class="info-label">Destination</div>
            <div class="info-value">{{ $contenu['destination'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Date de départ</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($contenu['date_depart'])->format('d/m/Y') }}</div>
        </div>
    </div>
</div>

<div class="info-section">
    <h3 style="margin-bottom: 15px;">Informations transporteur</h3>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Transporteur</div>
            <div class="info-value">{{ $contenu['transporteur'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Immatriculation</div>
            <div class="info-value">{{ $contenu['immatriculation'] }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Conducteur</div>
            <div class="info-value">{{ $contenu['conducteur'] }}</div>
        </div>
    </div>
</div>

@if(isset($contenu['observations']) && $contenu['observations'])
<div class="info-section">
    <h3 style="margin-bottom: 10px;">Observations</h3>
    <p>{{ $contenu['observations'] }}</p>
</div>
@endif

<!-- Tableau de chargement -->
<div class="table-section">
    <h3 style="margin-bottom: 15px;">Détail du chargement</h3>
    <table class="chargement-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Quantité</th>
                <th class="text-right">Poids unitaire</th>
                <th class="text-right">Poids total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $contenu['produit'] }}</td>
                <td class="text-right">{{ number_format($contenu['quantite']) }}</td>
                <td class="text-right">1 kg</td>
                <td class="text-right">{{ number_format($contenu['quantite']) }} kg</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right"><strong>TOTAL CHARGEMENT</strong></td>
                <td class="text-right"><strong>{{ number_format($contenu['quantite']) }} kg</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<style>
    .chargement-table {
        width: 100%;
        border-collapse: collapse;
    }
    .chargement-table th, .chargement-table td {
        border: 1px solid #ddd;
        padding: 10px;
    }
    .chargement-table th {
        background: #f5f5f5;
    }
    .text-right {
        text-align: right;
    }
    .total-row {
        background: #f0fdf4;
    }
</style>