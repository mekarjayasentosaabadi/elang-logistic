@extends('layout.app')

@section('title')
    <span>Vehicle</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Vehicle</h4>
                    <a href="{{ url('/vehicle/create') }}" class="btn btn-primary"><li class="fa fa-plus"></li> Tambah Vehicle</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-vehicle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>No Police</th>
                                    <th>No Stnk</th>
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
            $('#tbl-vehicle').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/vehicle/getAll') }}",
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        orderable : false,
                        searchable: false
                    },
                    {
                        data: 'type',
                        name: 'type',
                        searchable:true
                    },
                    {
                        data: 'police_no',
                        name: 'police_no',
                        searchable:true
                    },
                    {
                        data: 'no_stnk',
                        name: 'no_stnk',
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
