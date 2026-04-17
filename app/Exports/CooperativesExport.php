<?php

namespace App\Exports;

use App\Models\Cooperative;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CooperativesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return Cooperative::with('producteurs')->get();
    }

    public function headings(): array
    {
        return [
            'Code', 'Nom', 'Contact', 'Email', 'Région', 'Localisation',
            'Nombre de membres', 'Date création', 'Statut', 'Description'
        ];
    }

    public function map($cooperative): array
    {
        return [
            $cooperative->code_cooperative,
            $cooperative->nom,
            $cooperative->contact,
            $cooperative->email ?? '-',
            $cooperative->region,
            $cooperative->localisation,
            $cooperative->producteurs->count(),
            $cooperative->date_creation->format('d/m/Y'),
            $cooperative->statut,
            $cooperative->description ?? '-'
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
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(40);
        
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}