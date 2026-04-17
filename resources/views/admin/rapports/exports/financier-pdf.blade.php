<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport financier - Tropi-Techno</title>
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
        
        .period {
            font-size: 13px;
            color: #2d6a4f;
            margin-top: 10px;
            font-weight: bold;
        }
        
        .date {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }
        
        .stats-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .stat-card {
            flex: 1;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            min-width: 150px;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        .stat-value-positive {
            color: #059669;
        }
        
        .stat-value-negative {
            color: #dc2626;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2d6a4f;
            margin: 25px 0 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        
        .totals-row {
            background: #f0fdf4;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .summary-box {
            background: #f0fdf4;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #ddd;
        }
        
        .summary-label {
            font-weight: bold;
        }
        
        .summary-value {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo" style="max-height: 50px;">
        <div class="title">Rapport financier</div>
        <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
        <div class="period">
            Période: {{ \Carbon\Carbon::parse($rapport['date_debut'])->format('d/m/Y') }} 
            au {{ \Carbon\Carbon::parse($rapport['date_fin'])->format('d/m/Y') }}
        </div>
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <!-- Cartes statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Crédits accordés</div>
            <div class="stat-value">{{ number_format($rapport['credits_accordes'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Remboursements</div>
            <div class="stat-value stat-value-positive">{{ number_format($rapport['remboursements'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Collectes</div>
            <div class="stat-value">{{ number_format($rapport['collectes_montant'], 0, ',', ' ') }} CFA</div>
            <div style="font-size: 10px; color: #666;">{{ number_format($rapport['collectes_quantite']) }} kg</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Taux de recouvrement</div>
            <div class="stat-value">{{ number_format($rapport['taux_recouvrement'], 1) }}%</div>
        </div>
    </div>
    
    <!-- Synthèse -->
    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Total des crédits accordés</span>
            <span class="summary-value">{{ number_format($rapport['credits_accordes'], 0, ',', ' ') }} CFA</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total des remboursements</span>
            <span class="summary-value stat-value-positive">{{ number_format($rapport['remboursements'], 0, ',', ' ') }} CFA</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total des collectes</span>
            <span class="summary-value">{{ number_format($rapport['collectes_montant'], 0, ',', ' ') }} CFA</span>
        </div>
        <div class="summary-item" style="border-bottom: none; font-size: 14px;">
            <span class="summary-label">Solde net</span>
            <span class="summary-value {{ ($rapport['collectes_montant'] - $rapport['credits_accordes']) >= 0 ? 'stat-value-positive' : 'stat-value-negative' }}">
                {{ number_format($rapport['collectes_montant'] - $rapport['credits_accordes'], 0, ',', ' ') }} CFA
            </span>
        </div>
    </div>
    
    <!-- Top producteurs -->
    @if(isset($top_producteurs) && $top_producteurs->count() > 0)
    <div class="section-title">Top 10 producteurs (ventes)</div>
    <table>
        <thead>
            <tr>
                <th>Rang</th>
                <th>Producteur</th>
                <th class="text-right">Code</th>
                <th class="text-right">Montant (CFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($top_producteurs as $index => $producteur)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $producteur->nom_complet }}</td>
                <td class="text-right">{{ $producteur->code_producteur }}</td>
                <td class="text-right">{{ number_format($producteur->collectes_sum_montant_total, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    
    <!-- Indicateurs clés -->
    <div class="section-title">Indicateurs clés</div>
    <div class="stats-grid" style="margin-bottom: 0;">
        <div class="stat-card">
            <div class="stat-label">Taux de recouvrement</div>
            <div class="stat-value">{{ number_format($rapport['taux_recouvrement'], 1) }}%</div>
            <div class="progress-bar" style="margin-top: 8px; height: 6px; background: #e5e7eb; border-radius: 3px;">
                <div style="width: {{ $rapport['taux_recouvrement'] }}%; height: 6px; background: #059669; border-radius: 3px;"></div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Part collectes / crédits</div>
            <div class="stat-value">
                {{ $rapport['credits_accordes'] > 0 ? number_format(($rapport['collectes_montant'] / $rapport['credits_accordes']) * 100, 1) : 0 }}%
            </div>
            <div class="progress-bar" style="margin-top: 8px; height: 6px; background: #e5e7eb; border-radius: 3px;">
                <div style="width: {{ $rapport['credits_accordes'] > 0 ? ($rapport['collectes_montant'] / $rapport['credits_accordes']) * 100 : 0 }}%; height: 6px; background: #2563eb; border-radius: 3px;"></div>
            </div>
        </div>
    </div>
    
    <div class="footer">
        <p>Document généré automatiquement - Tropi-Techno Sarl</p>
        <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
        <p>Ce document est une synthèse financière officielle de la période.</p>
    </div>
</body>
</html>