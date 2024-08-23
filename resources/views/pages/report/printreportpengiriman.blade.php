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
              <tr>
                <td>1</td>
                <td>Jeruk</td>
                <td>SJ-000001</td>
                <td>EL 22201 ZE</td>
                <td>2024-08-24 13:25:52</td>
                <td>2024-08-27 13:25:52</td>
                <td>Darat</td>
                <td>Ambon</td>
                <td>Balikpapan</td>
                <td>10</td>
                <td>10</td>
              </tr>
        </tbody>
      </table>
</body>
</html>
