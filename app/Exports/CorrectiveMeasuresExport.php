<?php

namespace App\Exports;

use App\Models\CorrectiveMeasure;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;

class CorrectiveMeasuresExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{

    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Neusaglašenosti i korektivne mere'),
            'description'    => __('Tabela neusaglašenosti i korektivnih mera'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Br. kartona'),
            __('Datum pokretanja'),
            __('Status'),
            __('Sistem menadžment'),
            __('Organizaciona celina'),
            __('Izvor neusaglašenosti'),
            __('Opis neusaglašenosti'),
            __('Uzrok neusaglašenosti'),
            __('Mera za otklanjanje neusaglašenosti'),
            __('Rok za realizaciju korektivne mere'),
            __('Mera odobrena'),
            __('Razlog neodobravanja mere'),
            __('Datum odobravanja mere'),
            __('Mera efektivna')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = CorrectiveMeasure::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:N1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:N'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:N'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:N')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:N')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:N'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
        $sheet->getStyle('B:D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J:N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 25,
            'F' => 30,
            'G' => 40,
            'H' => 40,
            'I' => 30,
            'J' => 20,
            'K' => 20,
            'L' => 15,
            'M' => 15,
            'N' => 15
        ];
    }

    public function collection()
    {
        return CorrectiveMeasure::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with(['standard', 'sector'])->get();
    }

    public function map($measure): array
    {
        if($measure->measure_effective == '1'){
            $measureEffective = __('Da');
        }
        elseif($measure->measure_effective == '0'){
            $measureEffective = __('Ne');
        }
        elseif($measure->measure_effective == null){
            $measureEffective = "/";
        }
        else{
            $measureEffective = "/";
        }

        return [
            $measure->name,
            date('d.m.Y', strtotime($measure->noncompliance_cause_date)),
            $measure->measure_status == 1 ? __('Otvorena') : __('Zatvorena'),
            $measure->standard->name,
            $measure->sector->name,
            __($measure->noncompliance_source),
            $measure->noncompliance_description,
            $measure->noncompliance_cause,
            $measure->measure,
            date('d.m.Y', strtotime($measure->measure_date)),
            $measure->approval == 1 ? __('Odobrena') : __('Neodobrena'),
            $measure->measure_approval_reason != null ? $measure->measure_approval_reason : "/",
            $measure->measure_approval_date != null ? date('d.m.Y', strtotime($measure->measure_approval_date)) : "/",
            $measure->measure_effective = $measureEffective
        ];
    }
}
