<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Customer</th>
            <th>AWB</th>
            <th>Tanggal Order</th>
            <th>Tanggal Finish</th>
            <th>Asal</th>
            <th>Destinasi</th>
            <th>Volume / Berat  </th>
            <th>Total Volume / Berat</th>
            <th>Total Harga</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->customer->name ?? '-' }}</td>
                <td>{{ $order->numberorders ?? '-' }}</td>
                <td>{{ $order->created_at ?? '-' }}</td>
                <td>{{ $order->finish_date ?? '-' }}</td>
                <td>{{ $order->outlet->destination->name  ?? '-'}}</td>
                <td>{{ $order->destination->name }}</td>
                <td>{{ $order->weight ?? $order->volume ?? '-'}}</td>
                <td>{{ $order->weight ?? $order->volume ?? '-'}}</td>
                <td>{{ formatRupiah($order->price) ?? '-'}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="7" style="text-align: right; font-weight: bold;">Total</td>
            <td>{{ $totalWeightVolume }}</td>
            <td>{{ $totalWeightVolume }}</td>
            <td>{{ formatRupiah($totalPrice) }}</td>
        </tr>
    </tbody>
</table>
