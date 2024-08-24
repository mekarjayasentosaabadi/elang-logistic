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
    <h1 style="text-align: center; font-weight: 200;">Laporan Transaksi</h1>
    <table border="1" style="padding-left: 3px">
        <thead>
            <tr class="fw-bold">
                <th>Nama Customer</th>
                <th>AWB</th>
                <th>Tanggal Order</th>
                <th>Tanggal Finish</th>
                <th>Asal</th>
                <th>Destinasi</th>
                <th>Volume / Berat</th>
                <th>Total Volume / Berat</th>
                <th>Total Haga</th>
            </tr>
        </thead>
        <tbody>
              @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->numberorders ?? '-' }}</td>
                    <td>{{ $order->created_at ?? '-' }}</td>
                    <td>{{ $order->detailmanifests->manifest->detailtraveldocument->traveldocument->finish_date ?? '-' }}</td>
                    <td>{{ $order->outlet->destination->name ?? '-' }}</td>
                    <td>{{ $order->destination->name ?? '-' }}</td>
                    <td>{{ $order->weight ?? '-' }}</td>
                    <td>{{ $order->weight ?? '-'  }}</td>
                    <td>{{ $order->price ?? '-' }}</td>
                </tr>
              @endforeach
        </tbody>
      </table>
</body>
</html>
