<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport de production - Tropi-Techno</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d6a4f; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #2d6a4f; }
        .stats { display: flex; justify-content: space-between; margin-bottom: 30px; gap: 15px; }
        .stat-card { flex: 1; background: #f5f5f5; padding: 15px; border-radius: 8px; text-align: center; }
        .stat-value { font-size: 18px; font-weight: bold; color: #2d6a4f; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #2d6a4f; color: white; padding: 10px; text-align: left; }
        td { padding: 8px 10px; border-bottom: 1px solid #ddd; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport de production</div>
        <div class="subtitle">Période: {{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y') }}</div>
        @if($produit)<div class="subtitle">Produit: {{ $produit }}</div>@endif
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Quantité totale</div>
            <div class="stat-value">{{ number_format($stats['total_quantite']) }} kg</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Valeur totale</div>
            <div class="stat-value">{{ number_format($stats['total_valeur'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Nombre de collectes</div>
            <div class="stat-value">{{ number_format($stats['nb_collectes']) }}</div>
        </div>
    </div>
    
    <h3 style="margin: 20px 0 10px;">Top producteurs</h3>
    <table>
        <thead><tr><th>Producteur</th><th class="text-right">Quantité (kg)</th><th class="text-right">Montant (CFA)</th></tr></thead>
        <tbody>
            @foreach($top_producteurs as $tp)
            <tr>
                <td>{{ $tp->producteur->nom_complet }}</td>
                <td class="text-right">{{ number_format($tp->total_quantite) }}</td>
                <td class="text-right">{{ number_format($tp->total_montant, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré automatiquement - Tropi-Techno Sarl</p>
        <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
        <p>Ce document est une synthèse financière officielle de la période.</p>
    </div>
</body>
</html>