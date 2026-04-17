<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des producteurs - Tropi-Techno</title>
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
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport des producteurs</div>
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total producteurs</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Producteurs actifs</div>
            <div class="stat-value">{{ number_format($stats['producteurs_actifs']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Superficie totale</div>
            <div class="stat-value">{{ number_format($stats['total_superficie'], 2) }} ha</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr><th>Code</th><th>Nom</th><th>Contact</th><th>Région</th><th>Culture</th><th>Superficie</th><th>Statut</th></tr>
        </thead>
        <tbody>
            @foreach($producteurs as $producteur)
            <tr>
                <td>{{ $producteur->code_producteur }}</td>
                <td>{{ $producteur->nom_complet }}</td>
                <td>{{ $producteur->contact }}</td>
                <td>{{ $producteur->region }}</td>
                <td>{{ $producteur->culture_pratiquee }}</td>
                <td class="text-right">{{ number_format($producteur->superficie_totale, 2) }} ha</td>
                <td>{{ $producteur->statut }}</td>
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