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

class ManagementSystemReviews45001Export implements FromQuery, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
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
            __('Potrebe i očekivanja zainteresovanih strana i obaveze za usklađenost'),
            __('Obim ispunjenosti ciljeva'),
            __('Incidenti'),
            __('Neusaglašenosti i korektivne mere'),
            __('Rezultati praćenja i merenja'),
            __('Ispunjenost obaveza za usklađenost'),
            __('Rezultati internih provera'),
            __('Rezultati eksternih provera'),
            __('Konsultovanje i učestvovanje radnika'),
            __('Adekvatnost resursa'),
            __('Efektivnost mera koje se odnose na rizike i prilike'),
            __('Relevantno komuniciranje sa zainteresovanim stranama'),
            __('Prilike za poboljšanja'),
            __('Pogodnost, adekvatnost i efektivnost OH&S sistema menadžmenta'),
            __('Prilike za stalna poboljšanja'),
            __('Potrebe za izmenama u sistemu menadžmenta'),
            __('Mere, ako su potrebne'),
            __('Prilike za poboljšanje i integrisanje sa drugim procesima i sistemima menadžmenta'),
            __('Eventualne posledice po strateško usmerenje organizacije'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:X1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:X2')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:X2')->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('X')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:X')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:X')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:X2')->getFill()
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
            'O' => 30,
            'P' => 30,
            'Q' => 30,
            'R' => 30,
            'S' => 30,
            'T' => 30,
            'U' => 30,
            'V' => 30,
            'W' => 30,
            'X' => 15
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
            $msr->customer_satisfaction,
            $msr->objectives_scope,
            $msr->incidents ?? "/",
            $msr->inconsistancies_corrective_measures,
            $msr->monitoring_measurement_results,
            $msr->fulfillment_of_obligations ?? "/",
            $msr->checks_results,
            $msr->checks_results_desc,
            $msr->consulting_and_employee_participation ?? "/",
            $msr->resource_adequacy,
            $msr->measures_effectiveness,
            $msr->relevant_communication_with_stakeholders ?? "/",
            $msr->improvement_opportunities ?? "/",
            $msr->cae ?? "/",
            $msr->continous_improvement_opportunities ?? "/",
            $msr->needs_for_change ?? "/",
            $msr->measures_optional ?? "/",
            $msr->opportunities ?? "/",
            $msr->consequences ?? "/",
            $msr->standard->name
        ];
    }
}
