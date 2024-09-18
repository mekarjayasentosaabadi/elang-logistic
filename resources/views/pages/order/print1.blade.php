<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
    body{
        font-size: 5px;
    }
    .p-10{
        padding: 10px;
    }
    .text-center{
        text-align: center
    }
</style>
<body>
    <div style="width: 100%">
        <table border="1" style="width: 100%; padding: 2px">
            <tr>
                <th><img src="{{ $imagePath }}" alt="Logo" width="55"></th>
            </tr>
            <tr>
                <th class="text-center">
                        {!! '<img  width="220" height="40" src="data:image/png;base64,' . DNS1D::getBarcodePNG("$order->numberorders", 'C39+') .'" alt="barcode"   />' !!}
                        <div>{{ $order->numberorders }}</div>
                </th>
            </tr>
            <tr>
                <td style="width: 100%">
                    <b style="font-size: 6px">Pengirim: {{ $order->customer->name }} | Penerima: {{ $order->penerima }}</b>
                    <br>
                    <div>
                        <table style="width: 50%">
                            <tr>
                                <td ><b>Alamat Tujuan</b>: <br>{{ $order->address }}
                                </td>
                                {{-- <td >Kota Asal: {{ $order->outlet->destination->name }}</td> --}}
                            </tr>
                        </table>
                    </div>
                    
                    <table border="1" style="width: 100%;" style="padding: 3px">
                        <tr>
                            <td style="text-align: center">KOTA ASAL: {{ $order->outlet->destination->name }}</td>
                            <td style="text-align: center">KOTA TUJUAN: {{ $order->destination->name }}</td>
                        </tr>
                    </table>
                    <p>
                        <b>Janis Bayar :</b> {{ $order->payment_method== 1 ? 'Tagih Tujuan' : ($order->payment_method == 2 ? 'Tagih Pada Pengirim' : 'Tunai') }}<br>
                        <b>Total Harga :</b> {{ formatRupiah($order->price) }}<br>
                        <b>Berat: </b> {{ $order->weight }} Kg<br>
                        <b>Deskripsi Barang: </b>{{ $order->description ?? '-' }}
                        {{-- <b>Jumlah Kiriman :</b> {{ $order->koli }}<br> --}}
                        {{-- <b>Service :</b> {{ $order->armada == 1 ? 'Darat' : ($order->armada == 2 ? 'Laut' : 'Udara') }}<br> --}}
                    </p>
                </td>
            </tr>
            {{-- <tr>
                <td>Deskripsi Barang: {{ $order->description ?? '-' }}</td>
            </tr> --}}
            {{-- <tr>
            </tr> --}}
        </table>
    </div>
</body>

</html>
