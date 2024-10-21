@extends('layout.app')

@section('title')
    <span>Transaksi</span>
    <small>/</small>
    <small>History Update Order</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-2">
                <li class="nav-item">
                    <a class="nav-link" href="/order/{{ encrypt($order->id) }}/detail">
                        <i data-feather="eye" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Detail Order</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/order/{{ encrypt($order->id) }}/historyupdate"">
                        <i data-feather="clock" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">History Update</span>
                    </a>
                </li>
            </ul>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar History Update</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-order">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Order</th>
                                    <th>Pengirim</th>
                                    <th>Penerima</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('#tbl-order').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/order/getHistoryUpdateOrder') }}",
                    type: 'GET',
                    data: {
                        order_id: "{{ $order->id }}"
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'numberorders',
                        name: 'order.numberorders',
                        searchable: true
                    },
                    {
                        data: 'pengirim',
                        name: 'order.customer.name',
                        searchable: true
                    },
                    {
                        data: 'penerima',
                        name: 'order.penerima',
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'order.created_at',
                        searchable: true
                    },
                    {
                        data: 'status_orders',
                        name: 'status_orders',
                        searchable: true
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false, searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
