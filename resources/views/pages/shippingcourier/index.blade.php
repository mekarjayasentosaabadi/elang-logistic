@extends('layout.app')

@section('title')
    <span>Shipping Courier</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Shipping Courier</h4>
                    @if (Auth::user()->role_id == '1' || Auth::user()->role_id == '2')
                        <a href="{{ url('/shipping-courier/create') }}" class="btn btn-primary">Tambah Pengiriman</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-user">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    {{-- <th>No Pengiriman</th> --}}
                                    <th>Nama Kurir</th>
                                    <th>Paket Yang dikirim</th>
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
            $('#tbl-user').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/shipping-courier/getAll') }}",
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    // {
                    //     data: 'shippingno',
                    //     name: 'shippingno',
                    //     orderable: false,
                    //     searchable: true
                    // },
                    {
                        data: 'nama_kurir',
                        name: 'driver.name',
                        orderable: false,
                        searchable: true

                    },
                    {
                        data: 'jml_paket',
                        name: 'jml_paket',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false,
                        orderable: false,
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
