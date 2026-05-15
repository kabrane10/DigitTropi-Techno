<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribution intrants - {{ $distribution->code_distribution }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">

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
            border-bottom: 2px solid #2563eb;
            padding-bottom: 20px;
        }
        
        .logo {
            max-height: 50px;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
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
            background: #2563eb;
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
            color: #2563eb;
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
            color: #2563eb;
        }
        
        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
        
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
            background: #2563eb;
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
            .bg-gradient-to-r {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
            <img src="{{ asset('images/img6.png') }}" alt="Logo" class="logo" style="max-height: 50px;">
            <div class="title">BORDEREAU DE DISTRIBUTION D'INTRANTS</div>
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
                        <div class="info-label">Zone de livraison</div>
                        <div class="info-value">{{ $distribution->zone }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations bénéficiaire -->
        <div class="section">
            <div class="section-title">
                @if($distribution->beneficiaire_type === 'App\\Models\\Cooperative' || $distribution->cooperative_id)
                     INFORMATIONS COOPÉRATIVE
                @else
                     INFORMATIONS PRODUCTEUR
                @endif
            </div>
            <div class="section-content">
                @if($distribution->beneficiaire_type === 'App\\Models\\Cooperative' || $distribution->cooperative_id)
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nom de la coopérative</div>
                            <div class="info-value">{{ $distribution->cooperative->nom ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Code coopérative</div>
                            <div class="info-value">{{ $distribution->cooperative->code_cooperative ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact</div>
                            <div class="info-value">{{ $distribution->cooperative->contact ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Région</div>
                            <div class="info-value">{{ $distribution->cooperative->region ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Responsable</div>
                            <div class="info-value">{{ $distribution->cooperative->nom_responsable ?? 'N/A' }}</div>
                        </div>
                    </div>
                @else
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Nom complet</div>
                            <div class="info-value">{{ $distribution->producteur->nom_complet ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Code producteur</div>
                            <div class="info-value">{{ $distribution->producteur->code_producteur ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Contact</div>
                            <div class="info-value">{{ $distribution->producteur->contact ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Région</div>
                            <div class="info-value">{{ $distribution->producteur->region ?? 'N/A' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Culture pratiquée</div>
                            <div class="info-value">{{ $distribution->producteur->culture_pratiquee ?? 'N/A' }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Informations intrants -->
        <div class="section">
            <div class="section-title"> INTRANTS DISTRIBUÉS</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Intrant</div>
                        <div class="info-value">{{ $distribution->intrant->nom ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Type</div>
                        <div class="info-value">{{ $distribution->intrant->type_label ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Quantité distribuée</div>
                        <div class="info-value highlight">{{ number_format($distribution->quantite, 2) }} {{ $distribution->intrant->unite ?? '' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Prix unitaire</div>
                        <div class="info-value">{{ number_format($distribution->prix_unitaire, 0, ',', ' ') }} CFA/{{ $distribution->intrant->unite ?? '' }}</div>
                    </div>
                </div>
                
                <div class="totals">
                    <div class="total-row">
                        <span class="total-label"> Montant total :</span>
                        <span class="total-value">{{ number_format($distribution->montant_total, 0, ',', ' ') }} CFA</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Crédit associé -->
        @if($distribution->credit)
        <div class="section">
            <div class="section-title"> INFORMATIONS CRÉDIT</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Code crédit</div>
                        <div class="info-value">{{ $distribution->credit->code_credit }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Montant total crédit</div>
                        <div class="info-value">{{ number_format($distribution->credit->montant_total, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Reste à payer</div>
                        <div class="info-value">{{ number_format($distribution->credit->montant_restant, 0, ',', ' ') }} CFA</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Observations -->
        @if($distribution->notes)
        <div class="section">
            <div class="section-title">OBSERVATIONS</div>
            <div class="section-content">
                <p>{{ $distribution->notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du bénéficiaire</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">
                        @if($distribution->beneficiaire_type === 'App\\Models\\Cooperative' || $distribution->cooperative_id)
                            {{ $distribution->cooperative->nom ?? 'N/A' }}
                        @else
                            {{ $distribution->producteur->nom_complet ?? 'N/A' }}
                        @endif
                    </p>
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
            <p>Ce document est une preuve officielle de distribution d'intrants</p>
        </div>
    </div>
</body>
</html>