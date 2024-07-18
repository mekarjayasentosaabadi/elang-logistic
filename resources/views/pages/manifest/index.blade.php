@extends('layout.app')
@section('title')
    <span>Manifest</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Manifest</h4>
                    <a href="{{ url('/manifests/create') }}" class="btn btn-primary">Tambah Manifest</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tbl-manifests">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Manifest No</th>
                                        <th>Destinations</th>
                                        <th>Total Manifest</th>
                                        <th>Status Manifest</th>
                                        <th>Options</th>
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
            table = $('#tbl-manifests').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/manifest/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'manifestno',
                        name: 'manifestno'
                    },
                    {
                        data: 'manifestno',
                        name: 'manifestno'
                    },
                    {
                        data: 'manifestno',
                        name: 'manifestno'
                    },
                    {
                        data: 'manifestno',
                        name: 'manifestno'
                    },
                    {
                        data: 'manifestno',
                        name: 'manifestno'
                    },
                ]
            });
        });
    </script>
@endsection
