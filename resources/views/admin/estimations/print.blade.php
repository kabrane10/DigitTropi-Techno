<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Estimation de besoin - {{ $estimation->code_estimation }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }
        body{
            font-family:'Helvetica Neue',Arial,sans-serif;
            font-size:12px;
            padding:20px;
            background:white;
        }
        .print-container{
            max-width:800px;
            margin:0 auto;
        }
        .header{
            text-align:center;
            margin-bottom:30px;
            border-bottom:2px solid #2d6a4f;
            padding-bottom:20px;
        }
        .logo{
            max-height:50px;
            margin-bottom:10px;
        }
        .logo-img {
           height: 70px;
           width: auto;
           background: white;
           border-radius: 12px;
           padding: 8px;
           box-shadow: 0 2px 5px rgba(0,0,0,0.1);
       }
        .title{
            font-size:24px;
            font-weight:bold;
            color:#2d6a4f;
        }
        .subtitle{
            font-size:12px;
            color:#666;
        }
        .code{
            font-size:11px;
            color:#999;
            margin-top:5px;
        }
        .section{
            margin-bottom:20px;
            border:1px solid #ddd;
            border-radius:8px;
            overflow:hidden;
        }
        .section-title{
            background:#2d6a4f;
            color:white;
            padding:10px 15px;
            font-weight:bold;
            font-size:14px;
        }
        .section-content{
            padding:15px;
        }
        .info-grid{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:15px;
        }
        .info-label{
            font-size:11px;
            color:#666;
            margin-bottom:3px;
        }
        .info-value{
            font-size:13px;font-weight:500}
        .info-value.highlight{
            font-weight:bold;
            color:#2d6a4f;
        }
        .totals{
            margin-top:15px;
            padding:15px;
            background:#f0fdf4;
            border-radius:8px;
            text-align:center;
        }
        .total-grand{
            font-size:18px;
            font-weight:bold;
            color:#2d6a4f;
        }
        .badge{
            padding:3px 8px;
            border-radius:12px;
            font-size:10px;
            display:inline-block;
        }
        .badge-approuve{
            background:#d1fae5;
            color:#065f46;
        }
        .badge-rejete{
            background:#fee2e2
            ;color:#991b1b;
        }
        .badge-attente{
            background:#fef3c7;
            color:#92400e;
        }
        .signatures{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:40px;margin-top:30px;
            padding-top:20px;
            border-top:1px solid #ddd;
        }
        .signature-line{
            border-top:1px solid #333;
            margin-top:40px;
            padding-top:10px;
        }
        .footer{
            margin-top:30px;
            text-align:center;
            font-size:10px;
            color:#999;
            border-top:1px solid #ddd;
            padding-top:15px;
        }
        .btn-print{
            display:block;
            background:#2d6a4f;
            color:white;padding:10px;
            margin-bottom:20px;
            text-align:center;
            border-radius:5px;
            cursor:pointer;
        }
        @media print{
            .btn-print{
                display:none;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="btn-print" onclick="window.print()"> Imprimer / Télécharger PDF</div>
        
        <div class="header">
            <img src="{{ asset('images/img6.png') }}"  class="logo-img">
            <div class="title">FICHE D'ESTIMATION DE BESOIN</div>
            <div class="subtitle">Tropi-Techno Sarl - Agriculture Biologique au Togo</div>
            <div class="code">N° {{ $estimation->code_estimation }}</div>
            <div class="code">Date: {{ $estimation->date_estimation->format('d/m/Y') }}</div>
        </div>
        
        <div class="section">
            <div class="section-title"> INFORMATIONS PRODUCTEUR</div>
            <div class="section-content">
                <div class="info-grid">
                    <div><div class="info-label">Nom complet</div><div class="info-value highlight">{{ $estimation->producteur->nom_complet }}</div></div>
                    <div><div class="info-label">Code producteur</div><div class="info-value">{{ $estimation->producteur->code_producteur }}</div></div>
                    <div><div class="info-label">Contact</div><div class="info-value">{{ $estimation->producteur->contact }}</div></div>
                    <div><div class="info-label">Région</div><div class="info-value">{{ $estimation->producteur->region }}</div></div>
                    <div><div class="info-label">Localisation</div><div class="info-value">{{ $estimation->producteur->localisation }}</div></div>
                </div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title"> BESOINS EN SEMENCES</div>
            <div class="section-content">
                <div class="info-grid">
                    <div><div class="info-label">Semence</div><div class="info-value highlight">{{ $estimation->semence->nom }} ({{ $estimation->semence->variete }})</div></div>
                    <div><div class="info-label">Quantité estimée</div><div class="info-value">{{ number_format($estimation->quantite_estimee) }} {{ $estimation->semence->unite }}</div></div>
                    <div><div class="info-label">Superficie prévue</div><div class="info-value">{{ number_format($estimation->superficie_prevue, 2) }} hectares</div></div>
                    <div><div class="info-label">Rendement estimé</div><div class="info-value">{{ number_format($estimation->rendement_estime ?? 0) }} kg/ha</div></div>
                </div>
            </div>
        </div>
        
        @php
            $intrants = json_decode($estimation->intrants);
        @endphp

        <!-- Section Intrants (NOUVEAU) -->
        <div class="section">
            <div class="section-title"> INTRANTS REQUIS</div>
            <div class="section-content">
                @if(!empty($intrants) && count($intrants) > 0)
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="border:1px solid #ddd;padding:8px;text-align:left">Type d'intrant</th>
                                <th style="border:1px solid #ddd;padding:8px;text-align:right">Quantité</th>
                                <th style="border:1px solid #ddd;padding:8px;text-align:left">Unité</th>
                                <th style="border:1px solid #ddd;padding:8px;text-align:right">Coût estimé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($intrants as $intrant)
                            <tr>
                                <td style="border:1px solid #ddd;padding:8px">
                                    @if($intrant->type == 'engrais')  Engrais
                                    @elseif($intrant->type == 'pesticide')  Pesticide
                                    @elseif($intrant->type == 'herbicide')  Herbicide
                                    @else  {{ ucfirst($intrant->type) }}
                                    @endif
                                </td>
                                <td style="border:1px solid #ddd;padding:8px;text-align:right">{{ number_format($intrant->quantite, 2) }}</td>
                                <td style="border:1px solid #ddd;padding:8px">{{ $intrant->unite }}</td>
                                <td style="border:1px solid #ddd;padding:8px;text-align:right">{{ number_format($intrant->cout_estime, 0, ',', ' ') }} CFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" style="border:1px solid #ddd;padding:8px;text-align:right"><strong>Total intrants</strong></td>
                                <td style="border:1px solid #ddd;padding:8px;text-align:right"><strong>{{ number_format(collect($intrants)->sum('cout_estime'), 0, ',', ' ') }} CFA</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-gray-500">Aucun intrant spécifié pour cette estimation</p>
                @endif
            </div>
        </div>
        
        <div class="section">
           <div class="section-title"> RÉCAPITULATIF FINANCIER</div>
           <div class="section-content">
               @php
                   // Calculer une seule fois pour éviter les répétitions
                   $totalIntrants = collect($intrants ?? [])->sum('cout_estime');
                   $totalSemences = $estimation->cout_semences ?? 0;
                   $totalAutresFrais = $estimation->autres_frais ?? 0;
                   $totalGeneral = $totalSemences + $totalIntrants + $totalAutresFrais;
               @endphp
        
               <div class="info-grid">
                   <div>
                       <div class="info-label"> Coût des semences</div>
                       <div class="info-value">{{ number_format($totalSemences, 0, ',', ' ') }} CFA</div>
                   </div>
                   <div>
                       <div class="info-label"> Coût des intrants</div>
                       <div class="info-value">{{ number_format($totalIntrants, 0, ',', ' ') }} CFA</div>
                   </div>
                   <div>
                       <div class="info-label"> Montant crédit estimé</div>
                       <div class="info-value">{{ number_format($estimation->credit_montant ?? 0, 0, ',', ' ') }} CFA</div>
                   </div>
                   <div>
                       <div class="info-label"> Autres frais</div>
                       <div class="info-value">{{ number_format($totalAutresFrais, 0, ',', ' ') }} CFA</div>
                   </div>
                   <div>
                       <div class="info-label"> Total estimation</div>
                       <div class="info-value highlight">{{ number_format($totalGeneral, 0, ',', ' ') }} CFA</div>
                   </div>
               </div>
        
               @if($estimation->credit_montant && $totalGeneral > $estimation->credit_montant)
                   <div class="mt-3 p-2 bg-yellow-50 text-yellow-700 rounded text-sm">
                       ⚠️ Le montant du crédit estimé ({{ number_format($estimation->credit_montant, 0, ',', ' ') }} CFA) 
                       est inférieur au coût total estimé ({{ number_format($totalGeneral, 0, ',', ' ') }} CFA).
                   </div>
               @endif
           </div>
       </div>
        
        <div class="section">
            <div class="section-title"> STATUT DE L'ESTIMATION</div>
            <div class="section-content">
                <div class="info-grid">
                    <div><div class="info-label">Statut</div><div><span class="badge badge-{{ $estimation->statut }}">{{ $estimation->statut_label }}</span></div></div>
                    <div><div class="info-label">Date d'estimation</div><div class="info-value">{{ $estimation->date_estimation->format('d/m/Y') }}</div></div>
                    <div><div class="info-label">Validité</div><div class="info-value">{{ $estimation->date_validite ? $estimation->date_validite->format('d/m/Y') : 'Non spécifiée' }}</div></div>
                </div>
            </div>
        </div>
        
        @if($estimation->observations)
        <div class="section">
            <div class="section-title"> OBSERVATIONS</div>
            <div class="section-content"><p>{{ $estimation->observations }}</p></div>
        </div>
        @endif
        
        <div class="totals">
            <div class="total-grand">{{ number_format(($estimation->cout_semences ?? 0) + (collect($intrants)->sum('cout_estime') ?? 0) + ($estimation->autres_frais ?? 0), 0, ',', ' ') }} CFA</div>
            <div>Montant total estimé</div>
        </div>
        
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature du producteur</p>
                    <p style="font-size:10px;color:#999;margin-top:5px">{{ $estimation->producteur->nom_complet }}</p>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <p>Signature de l'agent</p>
                    <p style="font-size:10px;color:#999;margin-top:5px">Tropi-Techno Sarl</p>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Document généré automatiquement - Tropi-Techno Sarl</p>
            <p>RN:17, Bamabodolo, Sokodé-Togo | Tel: +228 25 50 63 12 | Email: tropitechno@admin.com</p>
            <p>Cette estimation est valable pour une durée de 30 jours</p>
        </div>
    </div>
</body>
</html>
