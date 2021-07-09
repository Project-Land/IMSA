<?php

namespace App\Exports;

use App\Models\Soa;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SoasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithMapping, WithColumnWidths, WithProperties
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Izjava o primenljivosti'),
            'description'    => __('Izjava o primenljivosti'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Naziv'),
            __('Opis'),
            __('Status'),
            __('Komentar'),
            __('Relevantna dokumenta'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = Soa::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;


        $sheet->getStyle('A1:E1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:E' . $count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:E' . $count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:E')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:E')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:E' . $count)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 55,
            'C' => 55,
            'D' => 55,
            'E' => 55,
        ];
    }

    public function collection()
    {
        return Soa::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with(['user', 'standard', 'soaField', 'documents'])->get();
    }

    public function map($soa): array
    {
        return [
            __($soa->soaField->name),
            __($soa->soaField->description),
            __($soa->status),
            $soa->comment,
            count($soa->documents) > 0 ? $soa->documents()->pluck('file_name')->implode(', ') : '/',
        ];
    }
}
