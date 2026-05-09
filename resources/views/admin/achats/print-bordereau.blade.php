<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bordereau d'achat - {{ $achat->code_achat }}</title>
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
            font-size: 12px;
            color: #666;
        }
        
        .code {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        .logo-img {
           height: 70px;
           width: auto;
           background: white;
           border-radius: 12px;
           padding: 8px;
           box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
        
        /* Tableau */
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
            padding: 10px;
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
            padding: 15px;
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
        
        .total-grand {
            font-size: 16px;
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
        
        .badge-confirme { background: #d1fae5; color: #065f46; }
        .badge-attente { background: #fef3c7; color: #92400e; }
        .badge-annule { background: #fee2e2; color: #991b1b; }
        
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
            .badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
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
        <img src="{{ asset('images/img6.png') }}"  class="logo-img">
            <div class="title">BORDEREAU D'ACHAT</div>
            <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
            <div class="code">N° {{ $achat->code_achat }}</div>
            <div class="code">Date: {{ $achat->date_achat->format('d/m/Y') }}</div>
        </div>
        
        <!-- Informations vendeur -->
        <div class="section">
            <div class="section-title"> INFORMATIONS VENDEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value highlight">{{ $achat->collecte->producteur->nom_complet }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Code producteur</div>
                        <div class="info-value">{{ $achat->collecte->producteur->code_producteur }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact</div>
                        <div class="info-value">{{ $achat->collecte->producteur->contact }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Localisation</div>
                        <div class="info-value">{{ $achat->collecte->producteur->localisation }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Région</div>
                        <div class="info-value">{{ $achat->collecte->producteur->region }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations acheteur -->
        <div class="section">
            <div class="section-title"> INFORMATIONS ACHETEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom</div>
                        <div class="info-value highlight">{{ $achat->acheteur }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Mode de paiement</div>
                        <div class="info-value">
                            @if($achat->mode_paiement == 'especes')  Espèces
                            @elseif($achat->mode_paiement == 'virement')  Virement bancaire
                            @elseif($achat->mode_paiement == 'cheque')  Chèque
                            @else  Mobile Money
                            @endif
                        </div>
                    </div>
                    @if($achat->reference_facture)
                    <div class="info-item">
                        <div class="info-label">Référence facture</div>
                        <div class="info-value">{{ $achat->reference_facture }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">Statut</div>
                        <div class="info-value">
                            <span class="badge badge-{{ $achat->statut }}">
                                @if($achat->statut == 'confirme')  Confirmé
                                @elseif($achat->statut == 'en_attente')  En attente
                                @else  Annulé
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations collecte source -->
        <div class="section">
            <div class="section-title"> COLLECTE SOURCE</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Code collecte</div>
                        <div class="info-value">{{ $achat->collecte->code_collecte }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date collecte</div>
                        <div class="info-value">{{ $achat->collecte->date_collecte->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Produit</div>
                        <div class="info-value highlight">{{ $achat->collecte->produit }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Quantité collectée</div>
                        <div class="info-value">{{ number_format($achat->collecte->quantite_nette) }} kg</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Détails de l'achat -->
        <div class="section">
            <div class="section-title"> DÉTAILS DE L'ACHAT</div>
            <div class="section-content">
                <div class="table-section">
                    <table>
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th class="text-right">Quantité</th>
                                <th class="text-right">Prix unitaire</th>
                                <th class="text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $achat->collecte->produit }}</td>
                                <td class="text-right">{{ number_format($achat->quantite) }} kg</td>
                                <td class="text-right">{{ number_format($achat->prix_achat, 0, ',', ' ') }} CFA</td>
                                <td class="text-right">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="totals">
                    <div class="total-row">
                        <span class="total-label">Quantité totale :</span>
                        <span class="total-value">{{ number_format($achat->quantite) }} kg</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Montant total :</span>
                        <span class="total-grand">{{ number_format($achat->montant_total, 0, ',', ' ') }} CFA</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Observations -->
        @if($achat->notes)
        <div class="section">
            <div class="section-title"> OBSERVATIONS</div>
            <div class="section-content">
                <p>{{ $achat->notes }}</p>
            </div>
        </div>
        @endif
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du vendeur</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">{{ $achat->collecte->producteur->nom_complet }}</p>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature de l'acheteur</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">Tropi-Techno Sarl</p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Document généré automatiquement - Tropi-Techno Sarl</p>
            <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
            <p>Ce document fait office de preuve d'achat officielle</p>
        </div>
    </div>
</body>
</html>