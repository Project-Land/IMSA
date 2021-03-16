<?php

namespace App\Exports;

use App\Models\Stakeholder;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;

class StakeholdersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties
{
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Zainteresovane strane'),
            'description'    => __('Tabela zainteresovanih strana'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Zainteresovana strana'),
            __('Potrebe i očekivanja zainteresovane strane'),
            __('Odgovor preduzeća na potrebe i očekivanja')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = Stakeholder::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:C1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:C'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:C'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:C')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:C')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:C'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 55,
            'C' => 55,
        ];
    }

    public function collection()
    {
        return Stakeholder::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->get(['name', 'expectation', 'response']);
    }
}
