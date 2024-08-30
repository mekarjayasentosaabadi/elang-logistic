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
    <p>Alamat: {{ $userAddress }}</p>

    <p>Rincian Filter:
        @if (Auth::user()->role_id == 1)
            @if ($dataFilter['outlet'])
                Outlet:  {{ $dataFilter['outlet'] }},
            @endif
        @else
            Outlet:  {{ $dataFilter['outlet'] }},
        @endif


        @if ($dataFilter['customer_id'] != null)
            Customer:  {{ $dataFilter['customer'] }},
        @endif


        @if (!empty($dataFilter['destination']))
            Destination:  {{ $dataFilter['destination'] }},
        @endif


        @if (($dataFilter['tanggal_order_awal'] != null) && ($dataFilter['tanggal_order_akhir']  != null))
            Dari Tanggal {{ $dataFilter['tanggal_order_awal'] }} s.d {{ $dataFilter['tanggal_order_akhir'] }},
        @elseif ($dataFilter['tanggal_order_awal']!= null)
            Tanggal Awal Order {{ $dataFilter['tanggal_order_awal'] }},
        @elseif ($dataFilter['tanggal_order_akhir'])
            Tanggal Awal Order {{ $dataFilter['tanggal_order_akhir'] }},
        @endif


        @if ($dataFilter['status'] != null)
            @if ($dataFilter['status'] == '1')
                Status Order: Pending,
            @elseif ($dataFilter['status'] == '2')
                Status Order: Process,
            @elseif ($dataFilter['status'] == '3')
                Status Order: Done,
            @elseif ($dataFilter['status'] == '4')
                Status Order: Dibatalkan,
            @endif
        @endif

    </p>
    <h1 style="text-align: center; font-weight: 200;">Laporan Transaksi</h1>
    <table border="1" style="padding-left: 3px">
        <thead>
            <tr class="fw-bold">
                <th>No</th>
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
              @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->numberorders ?? '-' }}</td>
                    <td>{{ $order->created_at ?? '-' }}</td>
                    <td>{{ $order->finish_date ?? '-' }}</td>
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
