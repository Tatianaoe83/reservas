<?php

namespace App\Exports;

use Illuminate\Contracts\View\View as ViewContract;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReporteGeneralExport implements FromView, ShouldAutoSize, WithEvents
{
    /**
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function view(): ViewContract
    {
        return view('reportes.excel.general', $this->data);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                $baseStyle = [
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 11,
                        'color' => ['argb' => 'FF111827'],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFE5E7EB'],
                        ],
                    ],
                ];

                $sheet->getStyle("A1:G{$highestRow}")->applyFromArray($baseStyle);

                $primaryHeader = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1F2937'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $secondaryHeader = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF1F2937'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE0F2FE'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $totalRowStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF047857'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD1FAE5'],
                    ],
                ];

                for ($row = 1; $row <= $highestRow; $row++) {
                    $firstValue = trim((string) $sheet->getCell("A{$row}")->getValue());

                    if ($firstValue === '') {
                        continue;
                    }

                    if ($firstValue === 'Resumen General') {
                        $this->applyRowStyle($sheet, 'A', 'B', $row, $primaryHeader);
                        continue;
                    }

                    if ($firstValue === 'Resumen por Vehículo') {
                        $this->applyRowStyle($sheet, 'A', 'D', $row, $primaryHeader);
                        continue;
                    }

                    if ($firstValue === 'Vehículo') {
                        $this->applyRowStyle($sheet, 'A', 'D', $row, $secondaryHeader);
                        continue;
                    }

                    if ($firstValue === 'Resumen por Cliente') {
                        $this->applyRowStyle($sheet, 'A', 'D', $row, $primaryHeader);
                        continue;
                    }

                    if ($firstValue === 'Cliente') {
                        $this->applyRowStyle($sheet, 'A', 'D', $row, $secondaryHeader);
                        continue;
                    }

                    if ($firstValue === 'Detalle de Reservas') {
                        $this->applyRowStyle($sheet, 'A', 'G', $row, $primaryHeader);
                        continue;
                    }

                    if ($firstValue === 'Fecha') {
                        $this->applyRowStyle($sheet, 'A', 'G', $row, $secondaryHeader);
                        continue;
                    }

                    $rowValues = [];
                    for ($col = 1; $col <= 7; $col++) {
                        $columnLetter = Coordinate::stringFromColumnIndex($col);
                        $value = trim((string) $sheet->getCell("{$columnLetter}{$row}")->getValue());
                        if ($value !== '') {
                            $rowValues[] = $value;
                        }
                    }

                    if (!empty($rowValues)) {
                        $containsTotal = false;
                        foreach ($rowValues as $value) {
                            if (stripos($value, 'total') === 0 || stripos($value, 'TOTALES') === 0) {
                                $containsTotal = true;
                                break;
                            }
                        }

                        if ($containsTotal) {
                            $this->applyRowStyle($sheet, 'A', 'G', $row, $totalRowStyle);
                        }
                    }
                }

                // Ajustar altura de las filas para mejor lectura
                for ($row = 1; $row <= $highestRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(20);
                }
            },
        ];
    }

    private function applyRowStyle(Worksheet $sheet, string $startColumn, string $endColumn, int $row, array $style): void
    {
        $sheet->getStyle("{$startColumn}{$row}:{$endColumn}{$row}")->applyFromArray($style);
    }
}

