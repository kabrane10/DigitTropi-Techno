<?php

namespace App\Exports;

use App\Models\CreditAgricole;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CreditsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $statut;

    public function __construct($statut = null)
    {
        $this->statut = $statut;
    }

    public function query()
    {
        $query = CreditAgricole::with(['producteur', 'cooperative']);
        
        if ($this->statut) {
            $query->where('statut', $this->statut);
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Code crédit',
            'Code producteur',
            'Producteur',
            'Coopérative',
            'Montant total (CFA)',
            'Montant restant (CFA)',
            'Taux intérêt (%)',
            'Durée (mois)',
            'Date octroi',
            'Date échéance',
            'Statut',
            'Conditions'
        ];
    }

    public function map($credit): array
    {
        return [
            $credit->code_credit,
            $credit->producteur->code_producteur ?? '',
            $credit->producteur->nom_complet ?? '',
            $credit->cooperative->nom ?? '',
            $credit->montant_total,
            $credit->montant_restant,
            $credit->taux_interet,
            $credit->duree_mois,
            $credit->date_octroi->format('d/m/Y'),
            $credit->date_echeance->format('d/m/Y'),
            $credit->statut,
            $credit->conditions
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