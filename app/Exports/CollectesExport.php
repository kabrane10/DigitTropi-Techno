<?php

namespace App\Exports;

use App\Models\Collecte;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CollectesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $produit;
    protected $dateDebut;
    protected $dateFin;

    public function __construct($produit = null, $dateDebut = null, $dateFin = null)
    {
        $this->produit = $produit;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
    }

    public function query()
    {
        $query = Collecte::with(['producteur']);
        
        if ($this->produit) {
            $query->where('produit', $this->produit);
        }
        if ($this->dateDebut) {
            $query->whereDate('date_collecte', '>=', $this->dateDebut);
        }
        if ($this->dateFin) {
            $query->whereDate('date_collecte', '<=', $this->dateFin);
        }
        
        return $query->orderBy('date_collecte', 'desc');
    }

    public function headings(): array
    {
        return [
            'Code collecte',
            'Date collecte',
            'Code producteur',
            'Producteur',
            'Produit',
            'Zone collecte',
            'Quantité brute (kg)',
            'Quantité nette (kg)',
            'Prix unitaire (CFA)',
            'Montant total (CFA)',
            'Montant déduit (CFA)',
            'Net à payer (CFA)',
            'Statut paiement',
            'Crédit associé',
            'Observations'
        ];
    }

    public function map($collecte): array
    {
        return [
            $collecte->code_collecte,
            $collecte->date_collecte->format('d/m/Y'),
            $collecte->producteur->code_producteur ?? '',
            $collecte->producteur->nom_complet ?? '',
            $collecte->produit,
            $collecte->zone_collecte,
            $collecte->quantite_brute,
            $collecte->quantite_nette,
            $collecte->prix_unitaire,
            $collecte->montant_total,
            $collecte->montant_deduict ?? 0,
            $collecte->montant_a_payer,
            $collecte->statut_paiement,
            $collecte->credit ? $collecte->credit->code_credit : '',
            $collecte->observations
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']]],
            1 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2d6a4f']]],
        ];
    }
}