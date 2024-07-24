<!DOCTYPE html>
<html>

<head>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 8px;
        }

        /* #customers tr:nth-child(even){background-color: #f2f2f2;} */

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            color: white;
        }
        #barcode-style{
            text-align: center !important;
            display: flex !important;
            justify-content: center !important;
        }
    </style>
</head>

<body>
    <table id="customers">
        <tr>
            <td colspan="2">
                <div id="barcode-style">
                    {!! DNS1D::getBarcodeHTML("$cleanOrderNumber", 'PHARMA'); !!}
                </div>
                <div style="text-align: center">
                    {{$order->numberorders}}
                </div>
            </td>
            <td rowspan="2">
                <div>Tanggal : {{ $order->created_at }}</div>
                <div>No Order : {{ $order->numberorders }}</div>
                <div>Servis : {{ $order->service == '1' ? 'Document' : 'Package' }}</div>
                <div>Deskripsi : {{ $order->description }}</div>
                <div>Berat : {{ $order->weight }}</div>
                <div>Jumlah Kiriman : {{ $order->koli }}</div>
                <div>Kota Tujuan : {{ $order->destination->name }}</div>
            </td>
        </tr>
        <tr>
            <td style="max-width: 40px"><img src="{{asset('assets/img/logo.png')}}" width="170" alt=""></td>
            <td>
                <div>Pengirim </div>
                <div>{{ $order->customer->name }}</div>
                <div>Penrima </div>
                <div>{{ $order->penerima }}</div>
            </td>
        </tr>
    </table>

</body>

</html>
