<?php

namespace App\Exports;


use App\Models\Goal;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GoalsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles,WithMapping, WithColumnWidths, WithProperties

{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $year;
    public function __construct($year)
    {
         $this->year=$year;
    }

    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Ciljevi'),
            'description'    => __('Ciljevi'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Cilj'),
            __('Godina'),
            __('Standard'),
            __('Nivo važnosti'),
            __('Rok za realizaciju cilja'),
            __('Odgovornost za praćenje i realizaciju cilja'),
            __('Resursi'),
            __('KPI'),
            __('Aktivnosti'),
            __('Da li je cilj ispunjen'),
            __('Analiza'),
            __('Kreirao'),
            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = Goal::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id],
            ['year', $this->year]
        ])->count() + 1;

        $sheet->getStyle('A1:L1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:L'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:L'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A:L')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:L')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:L'.$count)->getFill()
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
            'G' => 55,
            'H' => 55,
            'I' => 55,
            'J' => 55,
            'K' => 55,
            'L' => 55,
            
            
        ];
    }

    public function collection()
    {
        return Goal::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id],
            ['year', $this->year]
        ])->with(['user','standard'])->get();
    }

    public function map($goal): array
    {
        return [
            $goal->goal,
            $goal->year,
            $goal->standard->name,
            $goal->level < 3 ? ($goal->level == 1 ? __('Mali'): __('Srednji')): __('Veliki'),
            $goal->deadline,
            $goal->responsibility ?? '/',
            $goal->resources ?? '/',
            $goal->kpi ?? '/',
            $goal->activities ?? '/',
            $goal->status ==1 ? __('Da'):__('Ne'),
            $goal->analysis ?? "/",
            $goal->user->name,
            
           
        ];
    }
}
