<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    .p-10 {
        padding: 10px;
    }

    .text-center {
        text-align: center
    }
</style>

<body>
    <div>
        <table border="1" class="p-10">
            <tr>
                <th colspan="2">
                    <div class="text-center">
                        {!! '<img  width="120" height="30" src="data:image/png;base64,' .
                            DNS1D::getBarcodePNG("$order->numberorders", 'C39+') .
                            '" alt="barcode"   />' !!}
                        <div>{{ $order->numberorders }}</div>
                    </div>
                </th>
                <th rowspan="3">
                    <div>Tanggal : {{ $order->created_at }}</div>
                    <div>No. Order : {{ $order->numberorders }}</div>
                    <div>Servis :
                        @if ($order->armada == 1)
                            Darat
                        @elseif($order->armada == 2)
                            Udara
                        @else
                            Laut
                        @endif
                    </div>
                    <div>Deskripsi : {{ $order->description }}</div>
                    <div>Berat : {{ $order->weight }}</div>
                    <div>Jumlah Kiriman : {{ $order->koli }}</div>
                    <div>Biaya Kirim : {{ $order->price }}</div>
                    <div>Kota Tujuan : {{ $order->destination->name }} </div>
                </th>
            </tr>
            <tr>
                <td rowspan="2">
                    <img src="{{ $imagePath }}" alt="Logo" width="120">
                </td>
                <td rowspan="2">
                    <div>Pengirim: </div>
                    <div>{{ $order->customer->name }}</div>
                    <div>Penrima:</div>
                    <div>{{ $order->penerima }}</div>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
        </table>
    </div>
</body>

</html>
