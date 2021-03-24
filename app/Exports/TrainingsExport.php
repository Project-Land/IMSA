<?php

namespace App\Exports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;

class TrainingsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{

    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Obuke'),
            'description'    => __('Tabela obuka'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Naziv'),
            __('Vrsta'),
            __('Opis'),
            __('Br. zaposlenih - planirano'),
            __('Termin / Mesto'),
            __('Resursi'),
            __('Br. zaposlenih').'-'.__('realizovano'),
            __('Ocena efekata obuke'),
            __('Učesnici'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $query = Training::where([
            ['standard_id', session('standard')],
            ['team_id',Auth::user()->current_team_id]]);

        if(session('training_year') != 'all'){
            $query->where('year', session('training_year'));
        }

        $count = $query->count() + 1;

        $sheet->getStyle('A1:J1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:J'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:J'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:J')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:J')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:J'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 55,
            'D' => 15,
            'E' => 30,
            'F' => 30,
            'G' => 15,
            'H' => 15,
            'I' => 30,
            'J' => 15
        ];
    }

    public function collection()
    {
        $query = Training::where([
            ['standard_id', session('standard')],
            ['team_id',Auth::user()->current_team_id]]);

        if(session('training_year') != 'all'){
            $query->where('year', session('training_year'));
        }

        return $query->with('standard', 'users')->orderBy('training_date', 'desc')->get();
    }

    public function map($training): array
    {
        return [
            $training->name,
            $training->type,
            $training->description,
            $training->num_of_employees,
            date('d.m.Y', strtotime($training->training_date)).' u '.date('H:i', strtotime($training->training_date)).', '.$training->place,
            $training->resources,
            $training->final_num_of_employees ?? '/',
            $training->rating ?? '/',
            $training->users->isNotEmpty() ? $training->users->pluck('name')->implode(', ') : "/",
            $training->standard->name
        ];
    }
}
