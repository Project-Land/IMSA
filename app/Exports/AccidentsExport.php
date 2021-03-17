<?php

namespace App\Exports;

use App\Models\Accident;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class AccidentsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Reklamacije'),
            'description'    => __('Tabela reklamacija'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Prezime, ime povređenog').'/'.__('aktera incidenta'),
            __('Poslovi i zadaci koje obavlјa'),
            __('Datum i vreme povrede').'/'.__('incidenta'),
            __('Uzrok povrede').'/'.__('incidenta'),
            __('KRATAK OPIS POVREDE/INCIDENTA (kako je došlo do povrede/incidenta - u fazama)'),
            __('Šta je greška?'),
            __('Po čijem je nalogu radio?'),
            __('Da li je obučen za rad i upoznat sa opasnostima i rizicima za te poslove?'),
            __('Da li je koristio predviđena lična zaštitna sredstva i opremu i koju?'),
            __('Da li je radio na poslovima sa povećanim rizikom?'),
            __('Da li ispunjava sve uslove za te poslove?'),
            __('Podaci o svedoku-očevicu: Ime, prezime i broj telefona (ako je bilo)'),
            __('Podaci o neposrednom rukovodiocu povređenog/aktera incidenta: Ime, prezime i radno mesto'),
            __('Datum i vreme prijave povrede/incidenta'),
            __('Zapažanje/komentar'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = Accident::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:P1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:P'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:P'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:P')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:P')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:P'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 35,
            'C' => 20,
            'D' => 35,
            'E' => 35,
            'F' => 35,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 25,
            'M' => 25,
            'N' => 20,
            'O' => 30,
            'P' => 15
        ];
    }

    public function collection()
    {
        return Accident::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with('standard')->get();
    }

    public function map($accident): array
    {
        if($accident->injury_type == "mala"){
            $injuryType = __('Laka');
        }
        elseif($accident->injury_type == "velika"){
            $injuryType = __('Teška');
        }
        else{
            $injuryType = __('Incident bez povrede');
        }

        return [
            $accident->name,
            $injuryType,
            date('d.m.Y', strtotime($accident->injury_datetime)).' u '.date('H:i:s', strtotime($accident->injury_datetime)),
            $accident->injury_cause,
            $accident->injury_description,
            $accident->error,
            $accident->order_from,
            $accident->dangers_and_risks,
            $accident->protective_equipment,
            $accident->high_risk_jobs,
            $accident->job_requirements,
            $accident->supervisor,
            $accident->witness ?? "/",
            date('d.m.Y', strtotime($accident->injury_report_datetime)).' u '.date('H:i:s', strtotime($accident->injury_report_datetime)),
            $accident->comment ?? "/",
            $accident->standard->name
        ];
    }
}
