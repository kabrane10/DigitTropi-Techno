<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Tropi-Techno</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d6a4f; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #2d6a4f; }
        .date { font-size: 11px; color: #999; margin-top: 10px; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 16px; font-weight: bold; color: #2d6a4f; border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 15px; }
        .stats-grid { display: flex; gap: 15px; margin-bottom: 20px; }
        .stat-card { flex: 1; background: #f5f5f5; padding: 15px; border-radius: 8px; text-align: center; }
        .stat-value { font-size: 18px; font-weight: bold; color: #2d6a4f; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport d'activités</div>
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <div class="section">
        <div class="section-title">📊 Producteurs</div>
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['total_producteurs']) }}</div><div>Total producteurs</div></div>
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['producteurs_actifs'] ?? 0) }}</div><div>Producteurs actifs</div></div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">💰 Crédits</div>
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</div><div>Total crédits</div></div>
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</div><div>Crédits actifs</div></div>
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['taux_remboursement'], 1) }}%</div><div>Taux remboursement</div></div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">🚚 Collectes</div>
        <div class="stats-grid">
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['total_collectes']) }} kg</div><div>Collectes totales</div></div>
            <div class="stat-card"><div class="stat-value">{{ number_format($stats['valeur_collectes'], 0, ',', ' ') }} CFA</div><div>Valeur totale</div></div>
        </div>
    </div>
    
    <div class="footer">
        <p>Tropi-Techno Sarl - Agriculture Biologique au Togo</p>
    </div>
</body>
</html>