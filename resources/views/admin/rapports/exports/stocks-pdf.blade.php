<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des stocks - Tropi-Techno</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d6a4f;
            padding-bottom: 20px;
        }
        
        .logo {
            max-height: 50px;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2d6a4f;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        
        .date {
            font-size: 11px;
            color: #999;
            margin-top: 10px;
        }
        
        .stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }
        
        .stat-card {
            flex: 1;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th {
            background: #2d6a4f;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge-critique {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            background: #fee2e2;
            color: #991b1b;
        }
        
        .badge-normal {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            background: #d1fae5;
            color: #065f46;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2d6a4f;
            margin: 20px 0 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo" style="max-height: 50px;">
        <div class="title">Rapport des stocks</div>
        <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <!-- Statistiques -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Stock total</div>
            <div class="stat-value">{{ number_format($stats['total_stock']) }} kg</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Valeur estimée</div>
            <div class="stat-value">{{ number_format($stats['valeur_totale'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Produits en stock</div>
            <div class="stat-value">{{ number_format($stats['nb_produits']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Alertes stock</div>
            <div class="stat-value" style="color: #dc2626;">{{ number_format($stats['alertes']) }}</div>
        </div>
    </div>
    
    <!-- Stock par zone -->
    @if(isset($stock_par_zone) && $stock_par_zone->count() > 0)
    <div class="section-title">Stock par zone</div>
    <div class="stats" style="margin-bottom: 20px;">
        @foreach($stock_par_zone as $zone)
        <div class="stat-card">
            <div class="stat-label">{{ $zone->zone }}</div>
            <div class="stat-value">{{ number_format($zone->total) }} kg</div>
        </div>
        @endforeach
    </div>
    @endif
    
    <!-- Tableau des stocks -->
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Zone</th>
                <th>Entrepôt</th>
                <th class="text-right">Stock actuel</th>
                <th class="text-right">Seuil alerte</th>
                <th class="text-center">Statut</th>
                <th>Dernier mouvement</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stocks as $stock)
            <tr>
                <td>{{ $stock->produit }}</td>
                <td>{{ $stock->zone }}</td>
                <td>{{ $stock->entrepot ?? '-' }}</td>
                <td class="text-right"><strong>{{ number_format($stock->stock_actuel) }}</strong> {{ $stock->unite }}</td>
                <td class="text-right">{{ number_format($stock->seuil_alerte) }} {{ $stock->unite }}</td>
                <td class="text-center">
                    @if($stock->stock_actuel <= $stock->seuil_alerte)
                        <span class="badge-critique">Stock critique</span>
                    @else
                        <span class="badge-normal">Normal</span>
                    @endif
                </td>
                <td>{{ $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Aucun stock trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Stocks critiques -->
    @php
        $stocks_critiques = $stocks->filter(function($stock) {
            return $stock->stock_actuel <= $stock->seuil_alerte;
        });
    @endphp
    
    @if($stocks_critiques->count() > 0)
    <div class="section-title">⚠️ Stocks critiques à réapprovisionner</div>
    <table style="margin-top: 10px;">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Zone</th>
                <th class="text-right">Stock actuel</th>
                <th class="text-right">Quantité recommandée</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks_critiques as $stock)
            <tr>
                <td>{{ $stock->produit }}</td>
                <td>{{ $stock->zone }}</td>
                <td class="text-right"><strong style="color: #dc2626;">{{ number_format($stock->stock_actuel) }} {{ $stock->unite }}</strong></td>
                <td class="text-right">{{ number_format($stock->seuil_alerte * 2) }} {{ $stock->unite }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <div class="footer">
        <p>Document généré automatiquement - Tropi-Techno Sarl</p>
        <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
        <p>Ce document est une synthèse financière officielle de la période.</p>
    </div>
</body>
</html>