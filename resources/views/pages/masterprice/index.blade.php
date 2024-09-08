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
                    {{-- @if (auth()->user()->role_id == '1') --}}
                        <a href="{{ route('masterprice.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Price</a>
                    {{-- @endif --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tbl-masterprice">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Asal Outlet</th>
                                        <th>Origin</th>
                                        <th>Service</th>
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
    {{-- Modal list data start --}}
        {{-- Modal List Outlet --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">List Data Harga Public</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table " id="tbl-list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Asal Outlet</th>
                                    <th>Origin</th>
                                    <th>Destination</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    {{-- End Modal list data start --}}
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
                        data: 'outlet.name',
                        name: 'outlet.name'
                    },
                    {
                        data: 'origin.name',
                        name: 'origin.name'
                    },
                    {
                        data: 'namaarmada',
                        name: 'namaarmada'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]
            });
        });

        function showDetail(orid,oid,armd){
            // console.log(orid,oid,armd)
            $('#tbl-list').DataTable().destroy();
            var url = window.location.origin + '/'+ listRoutes['masterprice.listhargapublic'].replace('{id}', orid).replace('{id2}', oid).replace('{id3}', armd);
            table = $('#tbl-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: url,
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'outlet.name',
                        name: 'outlet.name'
                    },
                    {
                        data: 'origin.name',
                        name: 'origin.name'
                    },
                    {
                        data: 'destination.name',
                        name: 'destination.name'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    }
                ]
            });
        }
    </script>
@endsection
