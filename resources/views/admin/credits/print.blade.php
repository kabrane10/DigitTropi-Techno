<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de crédit - {{ $credit->code_credit }}</title>
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
        
        /* Badge statut */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
        
        .badge-actif { background: #fef3c7; color: #92400e; }
        .badge-rembourse { background: #d1fae5; color: #065f46; }
        .badge-impaye { background: #fee2e2; color: #991b1b; }
        
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
                🖨️ Imprimer / Télécharger PDF
            </button>
        </div>
        
        <!-- En-tête -->
        <div class="header">
            <img src="{{ asset('images/img6.png') }}" alt="Logo" class="logo" style="max-height: 50px;">
            <div class="title">FICHE DE CRÉDIT AGRICOLE</div>
            <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
            <div class="code">N° {{ $credit->code_credit }}</div>
        </div>
        
        <!-- Informations générales -->
        <div class="section">
            <div class="section-title"> INFORMATIONS GÉNÉRALES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Date d'octroi</div>
                        <div class="info-value">{{ $credit->date_octroi->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date d'échéance</div>
                        <div class="info-value">{{ $credit->date_echeance->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Durée</div>
                        <div class="info-value">{{ $credit->duree_mois }} mois</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Taux d'intérêt</div>
                        <div class="info-value">{{ $credit->taux_interet }}%</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            <span class="badge badge-{{ $credit->statut }}">
                                {{ ucfirst($credit->statut) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations producteur -->
        <div class="section">
            <div class="section-title">INFORMATIONS PRODUCTEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value">{{ $credit->producteur->nom_complet }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Code producteur</div>
                        <div class="info-value">{{ $credit->producteur->code_producteur }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact</div>
                        <div class="info-value">{{ $credit->producteur->contact }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Localisation</div>
                        <div class="info-value">{{ $credit->producteur->localisation }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations financières -->
        <div class="section">
            <div class="section-title"> INFORMATIONS FINANCIÈRES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Capital emprunté</div>
                        <div class="info-value highlight">{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Intérêts totaux</div>
                        <div class="info-value">{{ number_format($montantAvecInterets - $credit->montant_total, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Total à rembourser</div>
                        <div class="info-value highlight">{{ number_format($montantAvecInterets, 0, ',', ' ') }} CFA</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Mensualité</div>
                        <div class="info-value">{{ number_format($mensualite, 0, ',', ' ') }} CFA/mois</div>
                    </div>
                </div>
                
                <div class="totals">
                    <div class="total-row">
                        <span class="total-label">Déjà remboursé :</span>
                        <span class="total-value">{{ number_format($montantRembourse, 0, ',', ' ') }} CFA</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Reste à payer :</span>
                        <span class="total-value">{{ number_format($resteAPayer, 0, ',', ' ') }} CFA</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Taux de remboursement :</span>
                        <span class="total-value">{{ number_format($tauxRemboursement, 1) }}%</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Historique des remboursements -->
        @if($credit->remboursements->count() > 0)
        <div class="section">
            <div class="section-title"> HISTORIQUE DES REMBOURSEMENTS</div>
            <div class="section-content">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-right">Montant</th>
                            <th>Mode</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($credit->remboursements as $remboursement)
                        <tr>
                            <td>{{ $remboursement->date_remboursement->format('d/m/Y') }}</td>
                            <td class="text-right">{{ number_format($remboursement->montant, 0, ',', ' ') }} CFA</td>
                            <td>{{ $remboursement->mode_paiement }}</td>
                            <td>{{ $remboursement->type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right"><strong>{{ number_format($montantRembourse, 0, ',', ' ') }} CFA</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Tableau d'amortissement -->
        <div class="section">
            <div class="section-title">TABLEAU D'AMORTISSEMENT</div>
            <div class="section-content">
                <div class="table-section">
                    <table>
                        <thead>
                            <tr>
                                <th class="text-center">Mois</th>
                                <th class="text-center">Date échéance</th>
                                <th class="text-right">Mensualité</th>
                                <th class="text-right">Intérêts</th>
                                <th class="text-right">Capital</th>
                                <th class="text-right">Capital restant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($amortissement as $echeance)
                            <tr>
                                <td class="text-center">{{ $echeance['mois'] }}</td>
                                <td class="text-center">{{ $echeance['date'] }}</td>
                                <td class="text-right">{{ number_format($echeance['mensualite'], 0, ',', ' ') }} CFA</td>
                                <td class="text-right">{{ number_format($echeance['interets'], 0, ',', ' ') }} CFA</td>
                                <td class="text-right">{{ number_format($echeance['capital'], 0, ',', ' ') }} CFA</td>
                                <td class="text-right">{{ number_format($echeance['capital_restant'], 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right"><strong>TOTAUX</strong></td>
                                <td class="text-right"><strong>{{ number_format($montantAvecInterets, 0, ',', ' ') }} CFA</strong></td>
                                <td class="text-right"><strong>{{ number_format($montantAvecInterets - $credit->montant_total, 0, ',', ' ') }} CFA</strong></td>
                                <td class="text-right"><strong>{{ number_format($credit->montant_total, 0, ',', ' ') }} CFA</strong></td>
                                <td class="text-right"><strong>-</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du producteur</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">{{ $credit->producteur->nom_complet }}</p>
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
            <p>Ce document fait office de reconnaissance de dette officielle</p>
        </div>
    </div>
</body>
</html>