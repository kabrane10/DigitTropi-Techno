<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Distribution {{ $distribution->code_distribution }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2d6a4f; padding-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; color: #2d6a4f; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 14px; font-weight: bold; background: #f5f5f5; padding: 8px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .info-label { font-weight: bold; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; border-top: 1px solid #ddd; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Fiche de distribution de semences</div>
        <div>N° {{ $distribution->code_distribution }}</div>
        <div>Date: {{ $distribution->date_distribution->format('d/m/Y') }}</div>
    </div>
    
    <div class="section">
        <div class="section-title">Producteur</div>
        <div class="info-grid">
            <div><span class="info-label">Nom:</span> {{ $distribution->producteur->nom_complet }}</div>
            <div><span class="info-label">Code:</span> {{ $distribution->producteur->code_producteur }}</div>
            <div><span class="info-label">Contact:</span> {{ $distribution->producteur->contact }}</div>
            <div><span class="info-label">Région:</span> {{ $distribution->producteur->region }}</div>
        </div>
    </div>
    
    <div class="section">
        <div class="section-title">Semences distribuées</div>
        <div class="info-grid">
            <div><span class="info-label">Semence:</span> {{ $distribution->semence->nom }} ({{ $distribution->semence->variete }})</div>
            <div><span class="info-label">Quantité:</span> {{ number_format($distribution->quantite) }} {{ $distribution->semence->unite }}</div>
            <div><span class="info-label">Superficie:</span> {{ number_format($distribution->superficie_emblevee, 2) }} ha</div>
            <div><span class="info-label">Rendement estimé:</span> {{ $distribution->rendement_estime ? number_format($distribution->rendement_estime) . ' kg/ha' : 'Non estimé' }}</div>
        </div>
        @if($distribution->rendement_estime)
        <div style="margin-top: 10px;"><strong>Production totale estimée:</strong> {{ number_format($distribution->superficie_emblevee * $distribution->rendement_estime) }} kg</div>
        @endif
    </div>
    
    @if($distribution->credit)
    <div class="section">
        <div class="section-title">Crédit associé</div>
        <div>Code: {{ $distribution->credit->code_credit }} - Statut: {{ $distribution->credit->statut }}</div>
    </div>
    @endif
    
    @if($distribution->observations)
    <div class="section">
        <div class="section-title">Observations</div>
        <div>{{ $distribution->observations }}</div>
    </div>
    @endif
    
    <div class="footer">
        <p>Tropi-Techno Sarl - Agriculture Biologique au Togo</p>
    </div>
</body>
</html>