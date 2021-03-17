<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithMapping;


class SuppliersExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithColumnWidths, WithProperties, WithMapping
{
    public function properties(): array
    {
        return [
            'creator'        => Auth::user()->currentTeam->name,
            'title'          => __('Odobreni isporučioci'),
            'description'    => __('Tabela odobrenih isporučilaca'),
            'company'        => Auth::user()->currentTeam->name,
        ];
    }

    public function headings(): array
    {
        return [
            __('Naziv isporučioca'),
            __('Predmet nabavke'),
            __('Ime i prezime kontakt osobe kod isporučioca'),
            __('Broj telefona kontakt osobe kod isporučioca'),
            __('Email kontakt osobe kod isporučioca'),
            __('Kvalitet'),
            __('Cena'),
            __('Rok isporuke'),
            __('Status'),
            __('Datum kreiranja'),
            __('Datum sledećeg preispitivanja'),
            __('Standard')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $count = Supplier::where([
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
        $sheet->getStyle('F:L')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A:L')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:L'.$count)->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFFFFFED');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 40,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 15,
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15
        ];
    }

    public function collection()
    {
        return Supplier::where([
            ['standard_id', session('standard')],
            ['team_id', Auth::user()->current_team_id]
        ])->with('standard')->get();
    }

    public function map($supplier): array
    {
        return [
            $supplier->supplier_name,
            $supplier->subject,
            $supplier->personal_info ?? "/",
            $supplier->phone_number ?? "/",
            $supplier->email ?? "/",
            $supplier->quality,
            $supplier->price,
            $supplier->shippment_deadline,
            $supplier->status == 0 ? __('Neodobren') : __('Odobren'),
            date('d.m.Y', strtotime($supplier->created_at)),
            date('d.m.Y', strtotime($supplier->deadline_date)),
            $supplier->standard->name
        ];
    }
}
