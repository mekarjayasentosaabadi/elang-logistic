<?php
namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpParser\Node\Expr\FuncCall;

class ReportTransaksiExport implements FromView, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    protected $orders;
    protected $totalWeightVolume;
    protected $totalPrice;

    public function __construct($orders)
    {
        $this->orders = $orders;
        $this->calculateTotals();
    }

    //Calculate the total weight/volume and price
    protected function calculateTotals()
    {
        $this->totalWeightVolume = 0;
        $this->totalPrice = 0;

        foreach ($this->orders as $order) {
                $weightVolume = $order->weight ?? $order->volume ?? 0;
                $this->totalWeightVolume += $weightVolume;
                $this->totalPrice += $order->price ?? 0;

        }
    }

    public function view(): View
    {
        return view('pages.report.excelreporttransaksi', [
            'orders' => $this->orders,
            'totalWeightVolume' => $this->totalWeightVolume,
            'totalPrice' => $this->totalPrice
        ]);
    }

    // heading table
    public function headings(): array
    {
        return [
            'No', 'Nama Customer', 'AWB', 'Tanggal Order', 'Tanggal Finish', 'Asal', 'Destinasi', 'Volume/Berat', 'Total Volume/Berat', 'Total Harga'
        ];
    }

    // style border table
    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->orders)+2;

        $sheet->getStyle('A1:J'. $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    // auto size column
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event)  {
                foreach (range('A', 'J') as $column) {
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
