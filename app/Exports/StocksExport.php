<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class StocksExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Stock::all();
    }

    public function headings(): array
    {
        return [
            'Produit', 'Zone', 'Entrepôt', 'Stock actuel', 'Seuil alerte', 
            'Unité', 'Entrées totales', 'Sorties totales', 'Statut', 'Dernier mouvement'
        ];
    }

    public function map($stock): array
    {
        $statut = $stock->stock_actuel <= $stock->seuil_alerte ? 'Stock critique' : 'Normal';
        
        return [
            $stock->produit,
            $stock->zone,
            $stock->entrepot ?? '-',
            $stock->stock_actuel,
            $stock->seuil_alerte,
            $stock->unite,
            $stock->quantite_entree ?? 0,
            $stock->quantite_sortie ?? 0,
            $statut,
            $stock->dernier_mouvement ? $stock->dernier_mouvement->format('d/m/Y H:i') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style pour l'en-tête
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);
        $sheet->getStyle('A1:J1')->getFont()->setSize(12);
        $sheet->getStyle('A1:J1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:J1')->getFill()->getStartColor()->setRGB('2d6a4f');
        $sheet->getStyle('A1:J1')->getFont()->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Largeurs personnalisées
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        
        // Alignement des nombres à droite
        $sheet->getStyle('D:D')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E:E')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G:G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('H:H')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Couleur pour les stocks critiques
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $statut = $sheet->getCell("I{$row}")->getValue();
            if ($statut == 'Stock critique') {
                $sheet->getStyle("A{$row}:J{$row}")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle("A{$row}:J{$row}")->getFill()->getStartColor()->setRGB('FEE2E2');
            }
        }
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}