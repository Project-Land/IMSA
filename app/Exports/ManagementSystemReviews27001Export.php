<?php

namespace App\Exports;

use App\Models\ManagementSystemReview;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable;

class ManagementSystemReviews27001Export implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{
    use Exportable;

    public function forId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Zapisnik sa preispitivanja'),
            'description'    => __('Zapisnik sa preispitivanja'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Godina'),
            __('Učestvovali u preispitivanju'),
            __('Status mera iz prethodnog preispitivanja'),
            __('Promene u eksternim i internim pitanjima koje su relevantne za sistem menadžmenta'),
            __('Obim ispunjenosti ciljeva'),
            __('Neusaglašenosti i korektivne mere'),
            __('Rezultati praćenja i merenja'),
            __('Rezultati internih provera'),
            __('Rezultati eksternih provera'),
            __('Efektivnost mera koje se odnose na rizike i prilike'),
            __('Povratne informacije od zainteresovanih strana'),
            __('Prilike za poboljšanje'),
            __('Potrebe za izmenama u sistemu menadžmenta'),
            __('Potrebe za resursima'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:O1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:O2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:O2')->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:O')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:O')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:O2')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 30,
            'C' => 30,
            'D' => 30,
            'E' => 30,
            'F' => 30,
            'G' => 30,
            'H' => 30,
            'I' => 30,
            'J' => 30,
            'K' => 30,
            'L' => 30,
            'M' => 30,
            'N' => 30,
            'O' => 15,
        ];
    }

    public function query()
    {
        return ManagementSystemReview::query()->where('id', $this->id)->with('standard')->latest()->take(1);
    }

    public function map($msr): array
    {
        return [
            $msr->year,
            $msr->participants,
            $msr->measures_status,
            $msr->internal_external_changes,
            $msr->objectives_scope,
            $msr->inconsistancies_corrective_measures,
            $msr->monitoring_measurement_results,
            $msr->checks_results,
            $msr->checks_results_desc,
            $msr->measures_effectiveness,
            $msr->relevant_communication_with_stakeholders,
            $msr->improvement_opportunities ?? "/",
            $msr->needs_for_change ?? "/",
            $msr->needs_for_resources ?? "/",
            $msr->standard->name
        ];
    }
}
