<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteInsumosExport implements FromView, WithStyles
{
    public function __construct(
        private array $data
    ) {
    }

    public function view(): View
    {
        return view('reportes.exports.insumos', $this->data);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getDefaultRowDimension()->setRowHeight(18);
        $sheet->getStyle('A1:Z1000')->getFont()->setName('Calibri');
        $sheet->getStyle('A1:Z1000')->getAlignment()->setVertical('center');
    }
}

