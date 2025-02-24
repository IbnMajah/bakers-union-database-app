<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $type;

    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function array(): array
    {
        $labels = $this->data['labels']->toArray();
        $data = $this->data['data']->toArray();

        return array_map(function ($index) use ($labels, $data) {
            return [
                $labels[$index],
                $data[$index]
            ];
        }, array_keys($labels));
    }

    public function headings(): array
    {
        return match($this->type) {
            'transactions' => ['Bakery', 'Total Payments (GMD)'],
            'expenses' => ['Date', 'Total Expenses (GMD)'],
            'summary' => ['Period', 'Net Cash Flow (GMD)'],
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'B' => ['numberFormat' => ['formatCode' => '#,##0.00']],
        ];
    }
}