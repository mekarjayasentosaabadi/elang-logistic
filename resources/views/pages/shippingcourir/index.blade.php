@extends('layout.app')

@section('title')
    <span>Shipping Courir</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Shipping Courir</h4>
                    @if (Auth::user()->role_id == '1' || Auth::user()->role_id == '2')
                        <a href="{{ url('/shipping-courir/create') }}" class="btn btn-primary">Tambah Pengiriman</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-user">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Courier</th>
                                    <th>Nama Penerima</th>
                                    <th>Jumlah Barang</th>
                                    <th>Status Aktif</th>
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
            $('#tbl-user').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/shipping-courir/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable : false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        searchable:true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        searchable:true
                    },
                    {
                        data: 'role_id',
                        name: 'role_id',
                        searchable:true
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        searchable:true
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable:false,
                        searchable:false
                    }
                ]
            });
        });
    </script>
@endsection
