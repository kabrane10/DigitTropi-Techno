<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribution de semences - {{ $distribution->code_distribution }}</title>
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
            background: white;
        }
        
        .print-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        
        /* En-tête */
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
            font-size: 12px;
            color: #666;
        }
        
        .code {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }
        
        /* Sections */
        .section {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .section-title {
            background: #2d6a4f;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .section-content {
            padding: 15px;
        }
        
        /* Grilles */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 13px;
            font-weight: 500;
        }
        
        .info-value.highlight {
            font-weight: bold;
            color: #2d6a4f;
        }
        
        /* Tableaux */
        .table-section {
            margin-top: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background: #f5f5f5;
            font-weight: 600;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Totaux */
        .totals {
            margin-top: 15px;
            padding: 10px;
            background: #f0fdf4;
            border-radius: 8px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        
        .total-label {
            font-weight: normal;
        }
        
        .total-value {
            font-weight: bold;
            color: #2d6a4f;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
        
        .badge-principale { background: #d1fae5; color: #065f46; }
        .badge-contre-saison { background: #fef3c7; color: #92400e; }
        .badge-hivernage { background: #dbeafe; color: #1e40af; }
        
        /* Signatures */
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .signature-box {
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 10px;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        /* Bouton impression */
        .btn-print {
            display: inline-block;
            background: #2d6a4f;
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        
        @media print {
            .btn-print {
                display: none;
            }
            body {
                padding: 0;
                margin: 0;
            }
            .print-container {
                box-shadow: none;
                border: none;
            }
            .section {
                border: 1px solid #ddd;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Bouton d'impression -->
        <div style="text-align: center; margin-bottom: 20px;">
            <button onclick="window.print()" class="btn-print">
                 Imprimer / Télécharger PDF
            </button>
        </div>
        
        <!-- En-tête -->
        <div class="header">
        <img src="{{ asset('images/img6.png') }}"  class="logo-img">
            <div class="title">FICHE DE DISTRIBUTION DE SEMENCES</div>
            <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
            <div class="code">N° {{ $distribution->code_distribution }}</div>
        </div>
        
        <!-- Informations générales -->
        <div class="section">
            <div class="section-title"> INFORMATIONS GÉNÉRALES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Date de distribution</div>
                        <div class="info-value">{{ $distribution->date_distribution->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Saison</div>
                        <div class="info-value">
                            <span class="badge badge-{{ $distribution->saison }}">
                                {{ ucfirst($distribution->saison) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations producteur -->
        <div class="section">
            <div class="section-title"> INFORMATIONS PRODUCTEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value">{{ $distribution->producteur->nom_complet }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Code producteur</div>
                        <div class="info-value">{{ $distribution->producteur->code_producteur }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact</div>
                        <div class="info-value">{{ $distribution->producteur->contact }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Localisation</div>
                        <div class="info-value">{{ $distribution->producteur->localisation }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Région</div>
                        <div class="info-value">{{ $distribution->producteur->region }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Culture pratiquée</div>
                        <div class="info-value">{{ $distribution->producteur->culture_pratiquee }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations semences -->
        <div class="section">
            <div class="section-title"> INFORMATIONS SEMENCES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Semence</div>
                        <div class="info-value">{{ $distribution->semence->nom }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Variété</div>
                        <div class="info-value">{{ $distribution->semence->variete }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Type</div>
                        <div class="info-value">{{ ucfirst($distribution->semence->type) }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Unité</div>
                        <div class="info-value">{{ $distribution->semence->unite }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Détails de la distribution -->
        <div class="section">
            <div class="section-title"> DÉTAILS DE LA DISTRIBUTION</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Quantité distribuée</div>
                        <div class="info-value highlight">{{ number_format($distribution->quantite) }} {{ $distribution->semence->unite }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Superficie emblavée</div>
                        <div class="info-value">{{ number_format($distribution->superficie_emblevee, 2) }} hectares</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Rendement estimé</div>
                        <div class="info-value">
                            @if($distribution->rendement_estime)
                                {{ number_format($distribution->rendement_estime) }} kg/ha
                            @else
                                <span class="badge" style="background:#f3f4f6; color:#6b7280;">Non estimé</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Production totale estimée</div>
                        <div class="info-value highlight">
                            @if($distribution->rendement_estime)
                                {{ number_format($distribution->superficie_emblevee * $distribution->rendement_estime) }} kg
                            @else
                                <span class="badge" style="background:#f3f4f6; color:#6b7280;">Non calculable</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($distribution->rendement_estime)
                <div class="totals">
                    <div class="total-row">
                        <span class="total-label"> Ratio semence/superficie :</span>
                        <span class="total-value">{{ number_format($distribution->quantite / $distribution->superficie_emblevee, 2) }} {{ $distribution->semence->unite }}/ha</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label"> Objectif de production :</span>
                        <span class="total-value">{{ number_format($distribution->superficie_emblevee * $distribution->rendement_estime) }} kg</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Crédit associé -->
        @if($distribution->credit)
        <div class="section">
            <div class="section-title"> CRÉDIT ASSOCIÉ</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Code crédit</div>
                        <div class="info-value">{{ $distribution->credit->code_credit }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Montant total</div>
                        <div class="info-value">{{ number_format($distribution->credit->montant_total, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Reste à payer</div>
                        <div class="info-value">{{ number_format($distribution->credit->montant_restant, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            <span class="badge" style="background:#fef3c7; color:#92400e;">
                                {{ ucfirst($distribution->credit->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Observations -->
        @if($distribution->observations)
        <div class="section">
            <div class="section-title"> OBSERVATIONS</div>
            <div class="section-content">
                <p>{{ $distribution->observations }}</p>
            </div>
        </div>
        @endif
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du producteur</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">{{ $distribution->producteur->nom_complet }}</p>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature de l'agent</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">Tropi-Techno Sarl</p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Document généré automatiquement - Tropi-Techno Sarl</p>
            <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
            <p>Ce document est une preuve officielle de distribution de semences</p>
        </div>
    </div>
</body>
</html>