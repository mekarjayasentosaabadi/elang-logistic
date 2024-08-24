<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Report Transaksi</title>
    <style>
        .fw-bold{
            font-weight: bold;
        }
        @media print {
            thead {
            display: table-header-group;
            }
            tfoot {
                display: table-footer-group;
            }
            tr {
                page-break-inside: avoid;
            }
            table {
                width: 100%;
                table-layout: fixed;
            }
        }
    </style>
</head>
<body>
    <img src="{{$imagePath}}" alt="" width="200">
    <p>Alamat: Jalan Wastukencana No. 2, Bandung, Jawa Barat 40117, Indonesia.</p>

    <p>Rincian Filter:</p>
    <h1 style="text-align: center; font-weight: 200;">Laporan Pengiriman Barang</h1>
    <table border="1" style="padding-left: 3px;">
        <thead>
            <tr class="fw-bold">
                <th>No</th>
                <th>Driver</th>
                <th>No Surat Jalan</th>
                <th>No Kendaraan</th>
                <th>Tanggal Berangkat</th>
                <th>Tanggal Finish</th>
                <th>Jenis Pengiriman</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Volume / Berat</th>
                <th>Total Volume / Berat</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($dataReports) --}}
              @foreach ($dataReports as $index => $dataReport)
                <tr>
                    <td>1</td>
                    <td>{{ $dataReport->driver->name }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->travelno ?? '-' }}</td>
                    <td>{{ $dataReport->vehicle->police_no ?? '-' }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->start ?? '-' }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->finish_date ?? '-' }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->armada ?? '-' }}</td>
                    <td>{{ $dataReport->outlet->destination->name ?? '-' }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->destination->name ?? '-' }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->weight ?? $dataReport->detailsurattugas->first()->traveldocument->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->volume ?? '-' }}</td>
                    <td>{{ $dataReport->detailsurattugas->first()->traveldocument->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->weight ?? $dataReport->detailsurattugas->first()->traveldocument->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->volume ?? '-' }}</td>
                </tr>
              @endforeach
        </tbody>
      </table>
</body>
</html>
