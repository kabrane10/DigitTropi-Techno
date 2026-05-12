<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rapport d'inventaire</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d6a4f; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #2d6a4f; }
        .subtitle { font-size: 12px; color: #666; margin-top: 5px; }
        .date { font-size: 11px; color: #999; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f5f5f5; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport d'inventaire des intrants</div>
        <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
        <div class="date">Généré le {{ $date }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Intrant</th>
                <th>Type</th>
                <th>Unité</th>
                <th class="text-right">Prix unitaire</th>
                <th class="text-right">Stock total</th>
                <th class="text-right">Valeur (CFA)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($intrants as $intrant)
            @php
                $stockTotal = $intrant->stocks->sum('stock_actuel');
                $valeur = $stockTotal * $intrant->prix_unitaire;
            @endphp
            <tr>
                <td>{{ $intrant->code_intrant }}</td>
                <td>{{ $intrant->nom }}</td>
                <td>{{ $intrant->type_label }}</td>
                <td>{{ $intrant->unite }}</td>
                <td class="text-right">{{ number_format($intrant->prix_unitaire, 0, ',', ' ') }} CFA</td>
                <td class="text-right">{{ number_format($stockTotal) }}</td>
                <td class="text-right">{{ number_format($valeur, 0, ',', ' ') }} CFA</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><strong>TOTAL GÉNÉRAL</strong></td>
                <td class="text-right"><strong>{{ number_format($valeurTotale, 0, ',', ' ') }} CFA</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="footer">
        <p>Document généré automatiquement - Tropi-Techno Sarl</p>
        <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12</p>
    </div>
</body>
</html>