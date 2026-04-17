<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche de suivi parcellaire - {{ $suivi->code_suivi }}</title>
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
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #2d6a4f;
            padding-bottom: 20px;
        }
        
        .logo {
           height: 70px;
           width: auto;
           background: white;
           border-radius: 12px;
           padding: 8px;
           box-shadow: 0 2px 5px rgba(0,0,0,0.1);
       }

       @media print {
           .logo-img {
        filter: grayscale(0%);
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}   
        .title {
            font-size: 20px;
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
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
        }
        
        .badge-excellente { background: #d1fae5; color: #065f46; }
        .badge-bonne { background: #dbeafe; color: #1e40af; }
        .badge-moyenne { background: #fef3c7; color: #92400e; }
        .badge-mauvaise { background: #fed7aa; color: #9a3412; }
        .badge-critique { background: #fee2e2; color: #991b1b; }
        
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
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
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
             <img src="{{ asset('images/img6.png') }}"  class="logo">
            <div class="title">FICHE DE SUIVI PARCELLAIRE</div>
            <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
            <div class="code">N° {{ $suivi->code_suivi }}</div>
        </div>
        
        <!-- Informations générales -->
        <div class="section">
            <div class="section-title">📋 INFORMATIONS GÉNÉRALES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Date du suivi</div>
                        <div class="info-value">{{ $suivi->date_suivi->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Code suivi</div>
                        <div class="info-value">{{ $suivi->code_suivi }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations producteur -->
        <div class="section">
            <div class="section-title">👨‍🌾 INFORMATIONS PRODUCTEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value">{{ $suivi->producteur->nom_complet }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Code producteur</div>
                        <div class="info-value">{{ $suivi->producteur->code_producteur }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Contact</div>
                        <div class="info-value">{{ $suivi->producteur->contact }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Région</div>
                        <div class="info-value">{{ $suivi->producteur->region }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Localisation</div>
                        <div class="info-value">{{ $suivi->producteur->localisation }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Culture pratiquée</div>
                        <div class="info-value">{{ $suivi->producteur->culture_pratiquee }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informations animateur -->
        <div class="section">
            <div class="section-title">👨‍🏫 INFORMATIONS ANIMATEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nom complet</div>
                        <div class="info-value">{{ $suivi->animateur->nom_complet }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Zone responsabilité</div>
                        <div class="info-value">{{ $suivi->animateur->zone_responsabilite }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Données techniques -->
        <div class="section">
            <div class="section-title">🌱 DONNÉES TECHNIQUES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Superficie actuelle</div>
                        <div class="info-value">{{ number_format($suivi->superficie_actuelle, 2) }} hectares</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Hauteur des plantes</div>
                        <div class="info-value">{{ $suivi->hauteur_plantes ? number_format($suivi->hauteur_plantes) . ' cm' : 'Non mesuré' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Stade de croissance</div>
                        <div class="info-value">{{ $suivi->stade_croissance }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Taux de levée</div>
                        <div class="info-value">{{ $suivi->taux_levée ? $suivi->taux_levée . '%' : 'Non évalué' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Santé des cultures</div>
                        <div class="info-value">
                            <span class="badge badge-{{ $suivi->sante_cultures }}">
                                {{ ucfirst($suivi->sante_cultures) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Problèmes et recommandations -->
        @if($suivi->problemes_constates)
        <div class="section">
            <div class="section-title">⚠️ PROBLÈMES CONSTATÉS</div>
            <div class="section-content">
                <p>{{ $suivi->problemes_constates }}</p>
            </div>
        </div>
        @endif
        
        @if($suivi->recommandations)
        <div class="section">
            <div class="section-title">💡 RECOMMANDATIONS</div>
            <div class="section-content">
                <p>{{ $suivi->recommandations }}</p>
            </div>
        </div>
        @endif
        
        @if($suivi->actions_prises)
        <div class="section">
            <div class="section-title">✅ ACTIONS PRISES</div>
            <div class="section-content">
                <p>{{ $suivi->actions_prises }}</p>
            </div>
        </div>
        @endif
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature de l'animateur</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">{{ $suivi->animateur->nom_complet }}</p>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du producteur</p>
                    <p style="font-size: 10px; color: #999; margin-top: 5px;">{{ $suivi->producteur->nom_complet }}</p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Document généré automatiquement - Tropi-Techno Sarl</p>
            <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
            <p>Ce document fait foi de suivi parcellaire</p>
        </div>
    </div>
</body>
</html>