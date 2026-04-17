<?php

namespace App\Exports;

use App\Models\Producteur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProducteursExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Producteur::with('cooperative')->get();
    }

    public function headings(): array
    {
        return [
            'Code', 'Nom', 'Contact', 'Email', 'Région', 'Localisation',
            'Culture', 'Superficie (ha)', 'Statut', 'Date enregistrement', 'Coopérative'
        ];
    }

    public function map($producteur): array
    {
        return [
            $producteur->code_producteur,
            $producteur->nom_complet,
            $producteur->contact,
            $producteur->email ?? '-',
            $producteur->region,
            $producteur->localisation,
            $producteur->culture_pratiquee,
            $producteur->superficie_totale,
            $producteur->statut,
            $producteur->date_enregistrement->format('d/m/Y'),
            $producteur->cooperative->nom ?? '-'
        ];
    }

    // Ajustement automatique des colonnes
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}