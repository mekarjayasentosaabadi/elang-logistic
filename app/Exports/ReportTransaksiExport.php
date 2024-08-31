<?php
namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportTransaksiExport implements FromView, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('pages.report.excelreporttransaksi', [
            'orders' => $this->orders,
        ]);
    }

    public function headings(): array
    {
        return [
            'Nama Customer', 'AWB', 'Tanggal Order', 'Tanggal Finish', 'Asal', 'Destinasi', 'Volume/Berat', 'Total Volume/Berat', 'Total Harga'
        ];
    }
}
