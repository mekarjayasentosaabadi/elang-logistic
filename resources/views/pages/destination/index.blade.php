@extends('layout.app')
@section('title')
    <span>Destination</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Destinations</h4>
                    {{-- <a href="{{ url('/outlet/create') }}" class="btn btn-primary">Tambah Outlet</a> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="table-responsive">
                                <table class="table " id="tbl-destinations">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Destinations</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <form action="">
                                <h4>Form Add or Edit Destinations</h4>
                                <div class="form-group mt-2">
                                    <label for="destination">Name Destination</label>
                                    <input type="text" name="" id="" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <button class="btn btn-primary btn-md" id="btnsaveedit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>

        let idDestination=[];
        var table
        $(document).ready(function() {
            table = $('#tbl-destinations').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/destination/getAll') }}",
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
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        function editData(txt, id, name){
            $.getJSON(window.location.origin + '/'+listRoutes['destination.edit'].replace('{id}', id), function(){
            }).done(function(e){
                dataDestination = ["Cek","CEL"];
                idDestination[0]=e.data[0].id;
                idDestination[1]=e.data[0].name;
                // idDestination=['1'];
            }).fail(function(e){
            })
            console.log(idDestination);
            // console.log(iDestination)
        }
    </script>
@endsection
