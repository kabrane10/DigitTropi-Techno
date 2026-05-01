<?php

namespace App\Exports;

use App\Models\Achat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AchatsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $statut;

    public function __construct($statut = null)
    {
        $this->statut = $statut;
    }

    public function query()
    {
        $query = Achat::with(['collecte.producteur']);
        
        if ($this->statut) {
            $query->where('statut', $this->statut);
        }
        
        return $query->orderBy('date_achat', 'desc');
    }

    public function headings(): array
    {
        return [
            'Code achat',
            'Date achat',
            'Code producteur',
            'Producteur',
            'Produit',
            'Quantité (kg)',
            'Prix unitaire (CFA)',
            'Montant total (CFA)',
            'Acheteur',
            'Mode paiement',
            'Statut',
            'Référence facture',
            'Notes'
        ];
    }

    public function map($achat): array
    {
        return [
            $achat->code_achat,
            $achat->date_achat->format('d/m/Y'),
            $achat->collecte->producteur->code_producteur ?? '',
            $achat->collecte->producteur->nom_complet ?? '',
            $achat->collecte->produit ?? '',
            $achat->quantite,
            $achat->prix_achat,
            $achat->montant_total,
            $achat->acheteur,
            $achat->mode_paiement,
            $achat->statut,
            $achat->reference_facture,
            $achat->notes
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