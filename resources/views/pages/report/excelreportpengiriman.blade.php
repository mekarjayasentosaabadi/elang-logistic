<table>
    <thead>
        <tr>
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
        @foreach($dataReports as $index => $dataReport)
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
