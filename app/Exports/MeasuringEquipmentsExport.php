<?php

namespace App\Exports;


use App\Models\MeasuringEquipment;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MeasuringEquipmentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles,WithMapping, WithColumnWidths, WithProperties
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Merna oprema'),
            'description'    => __('Merna oprema'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Oznaka merne opreme'),
            __('Naziv merne opreme'),
            __('Datum poslednjeg etaloniranja/baždarenja'),
            __('Datum sledećeg etaloniranja/baždarenja'),
            __('Standard'),
            __('Kreirao'),
          
            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = MeasuringEquipment::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:F1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:F'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:F'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A:F')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:F')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:F'.$count)->getFill()
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
            'F' => 55,
          
            
            
        ];
    }

    public function collection()
    {
        return MeasuringEquipment::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with(['user','standard'])->get();
    }

    public function map($measuringEquipment): array
    {
        return [
            $measuringEquipment->label,
            $measuringEquipment->name,
            $measuringEquipment->last_calibration_date ?? '/',
            $measuringEquipment->next_calibration_date ?? '/',
            $measuringEquipment->standard->name,
            $measuringEquipment->user->name,
        ];
    }
}
