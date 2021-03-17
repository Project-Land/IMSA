<?php

namespace App\Exports;

use App\Models\EvaluationOfLegalAndOtherRequirement;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EvaluationOfLegalAndOtherRequirementsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles,WithMapping, WithColumnWidths, WithProperties
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Vrednovanje zakonskih i drugih zahteva'),
            'description'    => __('Vrednovanje zakonskih i drugih zahteva'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Nivo sa kojeg zahtev potiče'),
            __('Naziv dokumenta/zakona, ili opis zahteva'),
            __('Ocena usaglašenosti'),
            __('Datum poslednjeg ažuriranja'),
            __('Standard'),
            __('Napomena'),
            __('Kreirao'),
            __('Karton korektivne mere'),
            __('Izvor informacije o neusaglašenostima'),
            __('Opis neusaglašenosti'),
            __('Uzrok neusaglašenosti'),
            __('Mera za otklanjanje neusaglašenosti'),
            __('Odobravanje mere'),
            __('Razlog neodobravanja mere'),
            __('Da li je mera sprovedena?'),
            __('Mera efektivna'),
          
            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = EvaluationOfLegalAndOtherRequirement::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:P1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:P'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:P'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A:P')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:P')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:G'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
        $sheet->getStyle('E2:P'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFD4');
       
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
            'M' => 55,
            'N' => 55,
            'O' => 55,
            'P' => 55,
          
            
            
        ];
    }

    public function collection()
    {
        return EvaluationOfLegalAndOtherRequirement::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with(['user','standard','correctiveMeasures'])->get();
    }

    public function map($requirement): array
    {
        return [
            $requirement->requirement_level,
            $requirement->document_name,
            $requirement->compliance == 1? __('Usaglašen') : __('Neusaglašen'),
            $requirement->updated_at,
            $requirement->standard->name,
            $requirement->note,
            $requirement->user->name,
            isset($requirement->correctiveMeasures[0]) ? 
            $requirement->correctiveMeasures[0]->name  :'/',
            isset($requirement->correctiveMeasures[0]) ? $requirement->correctiveMeasures[0]->noncompliance_source :'/',
            isset($requirement->correctiveMeasures[0]) ? $requirement->correctiveMeasures[0]->noncompliance_description  :'/',
            isset($requirement->correctiveMeasures[0]) ? $requirement->correctiveMeasures[0]->noncompliance_cause :'/',
            isset($requirement->correctiveMeasures[0]) ? $requirement->correctiveMeasures[0]->measure :'/',
            isset($requirement->correctiveMeasures[0]) ? ($requirement->correctiveMeasures[0]->measure_approval==1 ? __('Da'): __('Ne')) :'/',
            isset($requirement->correctiveMeasures[0]) ? $requirement->correctiveMeasures[0]->measure_approval_reason ?? '/' :'/',
            isset($requirement->correctiveMeasures[0]) ? ($requirement->correctiveMeasures[0]->measure_status ==1 ? __('Da'): __('Ne')) :'/',
            isset($requirement->correctiveMeasures[0]) ? ($requirement->correctiveMeasures[0]->measure_effective == false ? ($requirement->correctiveMeasures[0]->measure_effective === null ? '/' :__('Ne')) : __('Da')) :'/',

        ];
    }
}
