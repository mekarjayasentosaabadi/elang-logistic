<?php

namespace App\Exports;

use App\Models\Surattugas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportPengirimanExport implements FromView, WithHeadingRow, WithStyles, WithCustomStartCell, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $dataReports;
    protected $totalWeightVolume;

    public function __construct($dataReports)
    {
        $this->dataReports = $dataReports;
        $this->calculateTotals();
    }

    // calculate total
    public function calculateTotals() {
        $this->totalWeightVolume = 0;

        foreach ($this->dataReports as $report) {
            $weightVolume = $report->order_weight ?? $order_volume ?? 0;

            $this->totalWeightVolume += $weightVolume;
        }
    }


    public function view(): View
    {
        return view('pages.report.excelreportpengiriman', [
            'dataReports' => $this->dataReports,
            'totalWeightVolume' => $this->totalWeightVolume
        ]);
    }

    // heading table
    public function headings(): array
    {
        return [
            'No', 'Driver', 'Surat Tugas', 'No Kendaraan', 'Tanggal Berangkat', 'Tanggal Finish', 'Jenis Pengiriman', 'Asal', 'Destinasi', 'Volume/Berat', 'Total Volume/Berat'
        ];
    }

    // style border table
    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->dataReports)+2;
        $sheet->getStyle('A1:K'. $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ]);

        return [
            1 => ['font' => ['bold' => true,]],
        ];
    }

    // auto size column
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event){
                foreach (range('A', 'K') as $column) {
                    $event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }

    // start cell table
    public function startCell(): string
    {
        return 'A1';
    }
}
