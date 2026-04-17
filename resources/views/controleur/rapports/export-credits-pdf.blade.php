<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d6a4f; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #2d6a4f; }
        .date { font-size: 11px; color: #999; margin-top: 10px; }
        .stats-grid { display: flex; gap: 15px; margin-bottom: 20px; }
        .stat-card { flex: 1; background: #f5f5f5; padding: 10px; border-radius: 8px; text-align: center; }
        .stat-value { font-size: 16px; font-weight: bold; color: #2d6a4f; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-value">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</div><div>Total crédits</div></div>
        <div class="stat-card"><div class="stat-value">{{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</div><div>Crédits actifs</div></div>
        <div class="stat-card"><div class="stat-value">{{ number_format($stats['taux_remboursement'], 1) }}%</div><div>Taux remboursement</div></div>
    </div>
    
    <table>
        <thead><tr><th>Code</th><th>Producteur</th><th>Montant</th><th>Reste</th><th>Échéance</th><th>Statut</th></tr></thead>
        <tbody>
            @foreach($credits as $c)
            <tr><td>{{ $c->code_credit }}</td><td>{{ $c->producteur->nom_complet }}</td><td class="text-right">{{ number_format($c->montant_total, 0, ',', ' ') }}</td><td class="text-right">{{ number_format($c->montant_restant, 0, ',', ' ') }}</td><td>{{ $c->date_echeance->format('d/m/Y') }}</td><td>{{ $c->statut }}</td></tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer"><p>Tropi-Techno Sarl - Agriculture Biologique au Togo</p></div>
</body>
</html>