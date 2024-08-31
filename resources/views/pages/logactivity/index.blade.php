@extends('layout.app')

@section('title')
    <span>Log Activity</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Log Activity</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-log">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Users / Pengguna</th>
                                    <th>Path</th>
                                    <th>IP Address</th>
                                    <th>Method</th>
                                    <th>Perangkat</th>
                                    <th>Waktu</th>
                                    <th>Detail</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tbl-log').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/logactifity/getData') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'user',
                        name: 'user.name'
                    },
                    {
                        data: 'path',
                        name: 'path'
                    },
                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },
                    {
                        data: 'method',
                        name: 'method'
                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent'
                    },
                    {
                        data: 'waktu',
                        name: 'waktu'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    }
                ]
            });
        });
    </script>
@endsection
