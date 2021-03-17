<?php

namespace App\Exports;

use App\Models\EnvironmentalAspect;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class EnvironmentalAspectsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles,WithMapping, WithColumnWidths, WithProperties
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Aspekti životne sredine'),
            'description'    => __('Aspekti životne sredine'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Proces'),
            __('Otpad / Fizičko-hemijska pojava'),
            __('Aspekt'),
            __('Uticaj'),
            __('Karakter otpada'),
            __('Verovatnoća pojavljivanja'),
            __('Verovatnoća otkrivanja'),
            __('Ozbiljnost posledica'),
            __('Procenjeni uticaj'),
            __('Standard'),
            __('Kreirao'),
           
            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = EnvironmentalAspect::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;
       
        
        $sheet->getStyle('A1:K1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:K'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:K'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('H')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('I')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A:K')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:K')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:K'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
        foreach(range(1,$count) as $c){
            if($sheet->getCell('I'.$c)->getValue() >8){
            $sheet->getStyle('I'.$c)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFF3333');
            }
        }
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
            'G' => 55,
            'H' => 55,
            'I' => 55,
            'J' => 55,
            'K' => 55,
           
            
            
        ];
    }

    public function collection()
    {
        return EnvironmentalAspect::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with(['user','standard'])->get();
    }

    public function map($requirement): array
    {
        return [
            $requirement->process,
            $requirement->waste,
            $requirement->aspect,
            $requirement->influence,
            $requirement->waste_type,
            $requirement->probability_of_appearance,
            $requirement->probability_of_discovery,
            $requirement->severity_of_consequences,
            $requirement->estimated_impact,
            $requirement->standard->name,
            $requirement->user->name,
          

        ];
    }
}
