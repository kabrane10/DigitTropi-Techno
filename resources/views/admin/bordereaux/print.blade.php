<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bordereau - {{ $bordereau->code_bordereau }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: white;
            padding: 20px;
        }
        
        .bordereau-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* En-tête */
        .header {
            background: #2d6a4f;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0 0;
            opacity: 0.8;
        }
        
        /* Informations */
        .info-section {
            padding: 20px;
            border-bottom: 1px solid #eee;
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
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .info-value {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }
        
        /* Tableau */
        .table-section {
            padding: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
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
        
        /* Totaux */
        .totals {
            padding: 20px;
            background: #f9f9f9;
            text-align: right;
        }
        
        .totals p {
            margin: 5px 0;
        }
        
        .total-grand {
            font-size: 18px;
            font-weight: bold;
            color: #2d6a4f;
        }
        
        /* Signatures */
        .signatures {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            padding: 20px;
            margin-top: 20px;
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
            background: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .logo-img {
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
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .bordereau-container {
                box-shadow: none;
                border: none;
            }
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
        }
    </style>
</head>
<body>
    <div class="bordereau-container">
        <!-- Bouton d'impression -->
        <div class="no-print" style="text-align: center; padding: 20px;">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Imprimer / Télécharger PDF
            </button>
        </div>
        
        <!-- En-tête avec logo et coordonnées -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/img6.png') }}"  class="logo-img">
                <div class="company-info">
                    <div class="company-name">TROPI-TECHNO SARL</div>
                    <div class="company-slogan">Agriculture Biologique au Togo</div>
                    <div class="company-address" style="font-size: 10px; margin-top: 5px;">
                        RN:17, Bamabodolo, Sokodé-Togo<br>
                        Tél: +228 25 50 63 12 / +228 92 95 29 61<br>
                        Email: tropitechno@admin.com
                    </div>
                </div>
            </div>
            <div class="bordereau-title">
                <h1>{{ $bordereau->type_label }}</h1>
                <p>N° {{ $bordereau->code_bordereau }}</p>
                <p>Émis le {{ $bordereau->date_emission->format('d/m/Y H:i') }}</p>
            </div>
        </div>        
        <!-- Informations générales -->
        <div class="info-section">
            <h3 style="margin-bottom: 15px;">Informations</h3>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Type de bordereau</div>
                    <div class="info-value">{{ $bordereau->type_label }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Statut</div>
                    <div class="info-value">{{ $bordereau->statut_label }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Émetteur</div>
                    <div class="info-value">{{ $bordereau->emetteur }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Destinataire</div>
                    <div class="info-value">{{ $bordereau->destinataire ?? '-' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Contenu selon le type -->
        @if($bordereau->type == 'collecte')
            @include('admin.bordereaux.partials.print-collecte', ['contenu' => $bordereau->contenu])
        @elseif($bordereau->type == 'achat')
            @include('admin.bordereaux.partials.print-achat', ['contenu' => $bordereau->contenu])
        @elseif($bordereau->type == 'chargement')
            @include('admin.bordereaux.partials.print-chargement', ['contenu' => $bordereau->contenu])
        @elseif($bordereau->type == 'livraison')
            @include('admin.bordereaux.partials.print-livraison', ['contenu' => $bordereau->contenu])
        @endif
        
        <!-- Observations -->
        @if($bordereau->observations)
        <div class="info-section">
            <h3 style="margin-bottom: 10px;">Observations</h3>
            <p>{{ $bordereau->observations }}</p>
        </div>
        @endif
        
        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature de l'émetteur</p>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du destinataire</p>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Ce document est une preuve officielle de transaction. Merci de conserver ce document.</p>
            <p>Tropi-Techno Sarl - Agriculture Biologique au Togo</p>
        </div>
    </div>
</body>
</html>