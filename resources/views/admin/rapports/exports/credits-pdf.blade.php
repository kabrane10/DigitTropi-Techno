<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des crédits - Tropi-Techno</title>
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
            max-height: 60px;
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
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
        
        .badge-actif { background: #fef3c7; color: #92400e; }
        .badge-rembourse { background: #d1fae5; color: #065f46; }
        .badge-impaye { background: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" alt="Logo" class="logo" style="max-height: 50px;">
        <div class="title">Rapport des crédits agricoles</div>
        <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
        <div class="date">Généré le {{ date('d/m/Y H:i') }}</div>
    </div>
    
    <!-- Statistiques -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-label">Total crédits</div>
            <div class="stat-value">{{ number_format($stats['total_credits'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Crédits actifs</div>
            <div class="stat-value">{{ number_format($stats['credits_actifs'], 0, ',', ' ') }} CFA</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Nombre de crédits</div>
            <div class="stat-value">{{ number_format($stats['nb_credits']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Taux remboursement</div>
            <div class="stat-value">{{ number_format($stats['taux_remboursement'], 1) }}%</div>
        </div>
    </div>
    
    <!-- Tableau des crédits -->
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Producteur</th>
                <th>Coopérative</th>
                <th class="text-right">Montant</th>
                <th class="text-right">Reste</th>
                <th>Échéance</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($credits as $credit)
            <tr>
                <td>{{ $credit->code_credit }}</td>
                <td>{{ $credit->producteur->nom_complet }}</td>
                <td>{{ $credit->cooperative->nom ?? '-' }}</td>
                <td class="text-right">{{ number_format($credit->montant_total, 0, ',', ' ') }}</td>
                <td class="text-right">{{ number_format($credit->montant_restant, 0, ',', ' ') }}</td>
                <td>{{ $credit->date_echeance->format('d/m/Y') }}</td>
                <td>
                    <span class="badge badge-{{ $credit->statut }}">
                        {{ $credit->statut }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Aucun crédit trouvé</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Document généré automatiquement - Tropi-Techno Sarl</p>
        <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
        <p>Ce document est une synthèse financière officielle de la période.</p>
    </div>
</body>
</html>