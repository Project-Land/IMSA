<?php

namespace App\Exports;

use App\Models\Complaint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;

class ComplaintsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
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
            __('Oznaka'),
            __('Opis'),
            __('Datum podnošenja'),
            __('Proces na koji se reklamacija odnosi'),
            __('Opravdana / prihvaćena'),
            __('Rok za realizaciju'),
            __('Lice odgovorno za rešavanje'),
            __('Način rešavanja'),
            __('Datum zatvaranja'),
            __('Status'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = Complaint::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:K1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:K'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:K'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E:F')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I:K')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:K')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:K')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:K'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 50,
            'C' => 20,
            'D' => 25,
            'E' => 15,
            'F' => 20,
            'G' => 20,
            'H' => 30,
            'I' => 15,
            'J' => 15,
            'K' => 15
        ];
    }

    public function collection()
    {
        return Complaint::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with('standard', 'sector')->get();
    }

    public function map($complaint): array
    {
        return [
            $complaint->name,
            $complaint->description,
            date('d.m.Y', strtotime($complaint->submission_date)),
            $complaint->sector->name,
            $complaint->accepted == 0 ? __('Ne') : __('Da'),
            $complaint->deadline_date != null ? date('d.m.Y', strtotime($complaint->deadline_date)) : "/",
            $complaint->responsible_person ?? "/",
            $complaint->way_of_solving ?? '/',
            $complaint->closing_date != null ? date('d.m.Y', strtotime($complaint->closing_date)) : "/",
            $complaint->status == 0 ? __('Zatvorena') : __('Otvorena'),
            $complaint->standard->name
        ];
    }
}
