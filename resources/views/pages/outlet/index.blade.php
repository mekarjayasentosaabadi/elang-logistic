@extends('layout.app')
@section('title')
    <span>Outlet</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Outlet</h4>
                    <a href="{{ url('/outlet/create') }}" class="btn btn-primary"><li class="fa fa-plus"></li> Tambah Outlet</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tbl-outlet">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Outlet</th>
                                        <th>Tipe</th>
                                        <th>Email</th>
                                        <th>No. HP</th>
                                        <th>Status</th>
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
            table = $('#tbl-outlet').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/outlet/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'toogle',
                        name: 'toogle'
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

        function changeStatus(txt, i) {
            console.log(i)
            var baseUrl = window.location.origin;
            $.ajax({
                url: baseUrl + '/' + listRoutes['outlet.changestatus'].replace('{id}', i),
                type: "POST",
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(e) {
                    notifSweetAlertSuccess(e.meta.message);
                },
                error: function(e) {
                    alert('Gagal mengeksekusi data.!')
                }
            })
        }
    </script>
@endsection
