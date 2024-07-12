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
                    <a href="{{ url('/vehicle/create') }}" class="btn btn-primary">Tambah Vehicle</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-vehicle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Driver</th>
                                    <th>No Police</th>
                                    <th>Type</th>
                                    <th>No Stnk</th>
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
                        orderable: false,
                    },
                    {
                        data: 'driver_name',
                        name: 'driver_name'
                    },
                    {
                        data: 'police_no',
                        name: 'police_no'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'no_stnk',
                        name: 'no_stnk'
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
