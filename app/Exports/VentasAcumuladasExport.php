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

class VentasAcumuladasExport implements FromView, ShouldAutoSize, WithEvents
{
    /**
     * @param array<string, mixed> $data
     */
    public function __construct(private array $data)
    {
    }

    public function view(): ViewContract
    {
        return view('reportes.excel.ventas-acumuladas', $this->data);
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

                $sheet->getStyle("A1:H{$highestRow}")->applyFromArray($baseStyle);

                $primaryHeader = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF0F172A'],
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
                        'startColor' => ['argb' => 'FFFDE68A'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ];

                $accentHeader = [
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FF1E40AF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE0ECFF'],
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

                    if ($firstValue === 'Mes') {
                        $this->applyRowStyle($sheet, 'A', 'H', $row, $secondaryHeader);
                        continue;
                    }

                    if (str_starts_with($firstValue, 'Total ')) {
                        $this->applyRowStyle($sheet, 'A', 'H', $row, $totalRowStyle);
                        continue;
                    }

                    if ($firstValue === 'TOTAL GENERAL:') {
                        $this->applyRowStyle($sheet, 'A', 'H', $row, $totalRowStyle);
                        continue;
                    }

                    $secondValue = trim((string) $sheet->getCell("B{$row}")->getValue());
                    $thirdValue = trim((string) $sheet->getCell("C{$row}")->getValue());

                    if ($secondValue === '' && $thirdValue === '' && $firstValue !== 'Resumen General') {
                        $this->applyRowStyle($sheet, 'A', 'H', $row, $accentHeader);
                    }

                    $rowValues = [];
                    for ($col = 1; $col <= 8; $col++) {
                        $columnLetter = Coordinate::stringFromColumnIndex($col);
                        $value = trim((string) $sheet->getCell("{$columnLetter}{$row}")->getValue());
                        if ($value !== '') {
                            $rowValues[] = $value;
                        }
                    }

                    if (!empty($rowValues)) {
                        foreach ($rowValues as $value) {
                            if (stripos($value, 'No hay ventas') === 0) {
                                $this->applyRowStyle($sheet, 'A', 'H', $row, $accentHeader);
                                break;
                            }
                        }
                    }
                }

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

