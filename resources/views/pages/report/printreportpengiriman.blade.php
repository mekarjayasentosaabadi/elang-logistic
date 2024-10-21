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
    <p>Alamat:  {{ $userAddress }}</p>

    <p>Rincian Filter:
        @if (Auth::user()->role_id == 1)
            @if (!empty($dataFilter['outlet']))
                Outlet:  {{ $dataFilter['outlet'] }},
            @endif
        @else
            Outlet:  {{ $dataFilter['outlet'] }},
        @endif

        @if ($dataFilter['jenis_pengiriman'] != null)
            @if ($dataFilter['jenis_pengiriman'] == '1')
                Jenis Pengiriman: Darat,
            @elseif($dataFilter['jenis_pengiriman'] == '2')
                Jenis Pengiriman: Laut,
            @elseif($dataFilter['jenis_pengiriman'] == '3')
                Jenis Pengiriman: Udara,
            @endif
        @endif

        @if (!empty($dataFilter['driver']))
            Driver:  {{ $dataFilter['driver'] }},
        @endif


        @if (($dataFilter['tanggal_awal_berangkat'] != null) && ($dataFilter['tanggal_akhir_berangkat']  != null))
            Tanggal Awal Berangkat Dari Tanggal {{ $dataFilter['tanggal_awal_berangkat'] }} s.d {{ $dataFilter['tanggal_akhir_berangkat'] }},
        @elseif ($dataFilter['tanggal_awal_berangkat']!= null)
            Tanggal Awal Berangkat {{ $dataFilter['tanggal_awal_berangkat'] }},
        @elseif ($dataFilter['tanggal_akhir_berangkat'])
            Tanggal Awal Berangkat {{ $dataFilter['tanggal_akhir_berangkat'] }},
        @endif

        @if (!empty($dataFilter['destination']))
            Destination:  {{ $dataFilter['destination'] }},
        @endif



        @if ($dataFilter['status_surattugas'] != null)
            @if ($dataFilter['status_surattugas'] == '0')
                Status Surat Tugas: Cancle,
            @elseif ($dataFilter['status_surattugas'] == '1')
                Status Surat Tugas: Process,
            @elseif ($dataFilter['status_surattugas'] == '2')
                Status Surat Tugas: On The Way,
            @elseif ($dataFilter['status_surattugas'] == '3')
                Status Surat Tugas: Done,
            @elseif ($dataFilter['status_surattugas'] == '5')
                Status Surat Tugas: All,
            @endif
        @endif



    </p>
    <h1 style="text-align: center; font-weight: 200;">Laporan Pengiriman Barang</h1>
    <table border="1" style="padding-left: 3px;">
        <thead>
            <tr class="fw-bold">
                <th>No</th>
                <th>Driver</th>
                <th>No Surat Tugas</th>
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
              @foreach ($dataReports as $index => $dataReport)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $dataReport->driver_name ?? '-' }}</td>
                    <td>{{ $dataReport->nosurattugas ?? '-' }}</td>
                    <td>{{ $dataReport->vehicle_police_no ?? '-' }}</td>
                    <td>{{ $dataReport->order_created_at  ?? '-' }}</td>
                    <td>{{ $dataReport->order_finish_date  ?? '-' }}</td>
                    <td>
                        @php
                            $armada = $dataReport->order_armada ?? "-" ;
                        @endphp

                        @if ($armada == 1)
                            Darat
                        @elseif ($armada == 2)
                            Laut
                        @elseif ($armada == 3)
                            Udara
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $dataReport->origin_name ?? '-' }}</td>
                    <td>{{ $dataReport->destination_name ?? '-' }}</td>
                    <td>{{ $dataReport->order_weight ??  $dataReport->order_volume ?? '-' }}</td>
                    <td>{{ $dataReport->order_weight ??  $dataReport->order_volume ?? '-' }}</td>
                </tr>
              @endforeach
        </tbody>
      </table>
</body>
</html>
