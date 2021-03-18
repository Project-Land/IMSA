<?php

namespace App\Exports;

use App\Models\CustomerSatisfaction;
use App\Models\SatisfactionColumn;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerSatisfactionExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Zadovoljstvo korisnika'),
            'description'    => __('Zadovoljstvo korisnika'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        $columns = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();

        $arrOfCols = [
            __('Klijent')
        ];
        foreach($columns as $col){
            array_push($arrOfCols, $col->name);
        }

        array_push($arrOfCols, __('Prosek'));
        array_push($arrOfCols, __('Napomena'));
        array_push($arrOfCols, __('Datum'));
        array_push($arrOfCols, __('Standard'));

        return [
            $arrOfCols
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = CustomerSatisfaction::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:O1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:O'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:O'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:O')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:O')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:O'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20
        ];
    }

    public function collection()
    {
        return CustomerSatisfaction::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with('standard')->get();
    }

    public function map($cs): array
    {
        $res = [
            $cs->customer
        ];

        $columns = SatisfactionColumn::where('team_id', Auth::user()->current_team_id)->whereNotNull('name')->get();

        foreach($columns as $col){
            array_push($res, $cs->{$col->column_name} ?? "/");
        }

        array_push($res, $cs->average());
        array_push($res, $cs->comment ?? "/");
        array_push($res, $cs->created_at);
        array_push($res, $cs->standard->name);

        return [
            $res
        ];
    }
}
