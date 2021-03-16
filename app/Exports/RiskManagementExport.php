<?php

namespace App\Exports;

use App\Models\RiskManagement;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiskManagementExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Rizici i prilike'),
            'description'    => __('Tabela rizika i prilika'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Opis rizika / prilike'),
            __('Verovatnoća'),
            __('Posledice'),
            __('Ukupno'),
            __('Prihvatljivo'),
            __('Mera'),
            __('Uzrok'),
            __('Mera za smanjenje rizika/ korišćenje prilike'),
            __('Odgovornost'),
            __('Rok za realizaciju'),
            __('Status'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = RiskManagement::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->count() + 1;

        $sheet->getStyle('A1:L1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);
        $sheet->getStyle('A2:L'.$count)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:L'.$count)->getAlignment()->setIndent(1);
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getFont()->setSize(12);
        $sheet->getStyle('1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:L')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A:L')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:E'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
        $sheet->getStyle('F2:L'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFD4');
        $sheet->getStyle('B:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J:L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 40,
            'G' => 40,
            'H' => 40,
            'I' => 30,
            'J' => 20,
            'K' => 20,
            'L' => 15
        ];
    }

    public function collection()
    {
        return RiskManagement::where([
            ['standard_id', session('standard')],
            ['team_id',Auth::user()->current_team_id],
        ])->with('standard')->get();
    }

    public function map($risk): array
    {
        return [
            $risk->description,
            $risk->probability,
            $risk->frequency,
            $risk->total,
            $risk->acceptable,
            $risk->measure != null ? $risk->measure : "/",
            $risk->cause != null ? $risk->cause : "/",
            $risk->risk_lowering_measure != null ? $risk->risk_lowering_measure : "/",
            $risk->responsibility != null ? $risk->responsibility : "/",
            $risk->deadline != null ? date('d.m.Y', strtotime($risk->deadline)) : "/",
            $risk->status == 1 ? __('Otvorena') : __('Zatvorena'),
            $risk->standard->name
        ];
    }
}
