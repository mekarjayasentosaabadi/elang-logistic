@extends('layout.app')

@section('title')
    <span>Transaksi</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Transaksi</h4>
                    @if (Auth::user()->role_id != '4')
                        <a href="{{ url('/order/create') }}" class="btn btn-primary"><li class="fa fa-plus"></li> Tambah Tansaksi</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-order">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>No Order</th>
                                    <th>Destinasi</th>
                                    <th>Pengirim</th>
                                    <th>Penerima</th>
                                    <th>Tanggal</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                    <th>Status Keterlambatan</th>
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
                    url: "{{ url('/order/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'numberorders',
                        name: 'orders.numberorders',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'destination',
                        name: 'destination.name',
                        searchable: true
                    },
                    {
                        data: 'pengirim',
                        name: 'customer.name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'penerima',
                        name: 'orders.penerima',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'orders.created_at',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'note',
                        name: 'note',
                    },
                    {
                        data: 'status_orders',
                        name: 'status_orders',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status_kenerlambatan',
                        name: 'status_kenerlambatan',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endsection
