<?php

namespace App\Exports;

use App\Models\Surattugas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportPengirimanExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $dataReports;

    public function __construct($dataReports)
    {
        $this->dataReports = $dataReports;
    }

    public function view(): View
    {
        return view('pages.report.excelreportpengiriman', [
            'dataReports' => $this->dataReports,
        ]);
    }

    public function headings(): array
    {
        return [
            'No', 'Driver', 'Surat Tugas', 'No Kendaraan', 'Tanggal Berangkat', 'Tanggal Finish', 'Jenis Pengiriman', 'Asal', 'Destinasi', 'Volume/Berat', 'Total Volume/Berat'
        ];
    }
}
