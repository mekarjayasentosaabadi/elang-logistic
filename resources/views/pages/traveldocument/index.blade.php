@extends('layout.app')
@section('title')
    <span>Surat Jalan</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Surat Jalan</h4>
                    <a href="{{ route('traveldocument.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Buat Surat Jalan</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tblSuratJalan">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Surat Jalan</th>
                                        <th>Destinations</th>
                                        <th>Jumlah Manifest</th>
                                        <th>Status Surat Jalan</th>
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
            table = $('#tblSuratJalan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/delivery/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'travelno',
                        name: 'travelno'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'jml_manifest',
                        name: 'jml_manifest'
                    },
                    {
                        data: 'status_traveldocument',
                        name: 'status_traveldocument'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]
            });
        });
    </script>
@endsection
