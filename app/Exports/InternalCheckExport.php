<?php

namespace App\Exports;

use App\Models\InternalCheck;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InternalCheckExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles,WithMapping, WithColumnWidths, WithProperties
{
    private $year;
    public function __construct($year)
    {
         $this->year=$year;
    }


    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Interne provere'),
            'description'    => __('Interne provere'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Termin provere'),
            __('Područje provere'),
            __('Vođe tima i proveravači'),
            __('Standard'),
            __('Datum kreiranja'),
            __('Kreirao'),
            __('Br programa IP'),
            __('Početak provere'),
            __('Završetak provere'),
            __('Rok za dostavljanje izveštaja'),
            __('Izveštaj sa interne provere - opis'),
            __('Preporuke'),
            __('Neusaglašenosti i korektivne mere'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = InternalCheck::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->whereYear('created_at', $this->year)->count() + 1;

        $sheet->getStyle('A1:M1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:M'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:M'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:M')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:M')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:F'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
        $sheet->getStyle('G2:J'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFD4');
        $sheet->getStyle('K2:M'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFBA');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
            'C' => 50,
            'D' => 15,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 25,
            'I' => 25,
            'J' => 25,
            'K' => 50,
            'L' => 50,
            'M' => 50,
        ];
    }

    public function collection()
    {
        return InternalCheck::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->whereYear('created_at', $this->year)->with(['standard','user','planIp','internalCheckReport.recommendations','internalCheckReport.correctiveMeasures'])->get();
    }

    public function map($internalCheck): array
    {
        return [
            $internalCheck->date,
            $internalCheck->sectorNames(),
            str_replace(',',' - ',$internalCheck->leaders),
            $internalCheck->standard->name,
            $internalCheck->created_at,
            $internalCheck->user->name,
            $internalCheck->planIp->name ?? '/',
            $internalCheck->planIp->check_start ?? '/',
            $internalCheck->planIp->check_end ?? '/',
            $internalCheck->planIp->report_deadline ?? '/',
            $internalCheck->internalCheckReport->specification ?? '/',
            isset($internalCheck->internalCheckReport->recommendations) ?$internalCheck->internalCheckReport->recommendations()->pluck('description')->implode(', ') : '/',
            isset($internalCheck->internalCheckReport->correctiveMeasures) ? $internalCheck->internalCheckReport->correctiveMeasures()->pluck('name')->implode(' -- ') : '/',
        ];
    }
}
