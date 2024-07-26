@extends('layout.app')
@section('title')
    <span>Harga Public</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Harga Public</h4>
                    <a href="{{ route('masterprice.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Price</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tbl-masterprice">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Asal Outlet</th>
                                        <th>Armada</th>
                                        <th>Tujuan / Destination</th>
                                        <th>Price</th>
                                        <th>Estimation</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script>
        var table
        $(document).ready(function() {
            table = $('#tbl-masterprice').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/masterprice/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'outlet',
                        name: 'outlet'
                    },
                    {
                        data: 'namaarmada',
                        name: 'namaarmada'
                    },
                    {
                        data: 'destination',
                        name: 'destination'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'estimation',
                        name: 'estimation'
                    },
                    {
                        data: 'option',
                        name: 'option'
                    }
                ]
            });
        });
    </script>
@endsection
