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
                            <form action="#" method="POST" id="form-add-update-data">
                                @csrf
                                <h4>Form Add or Edit Destinations</h4>
                                <div class="form-group mt-2">
                                    <label for="destination">Name Destination</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="form-group mt-2">
                                    <button type="submit" class="btn btn-primary btn-md" id="btnsaveedit"><li class="fa fa-save"></li> Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal List Outlet --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">List Data Outlet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table " id="tbl-orders">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Outlet</th>
                                    <th>Alamat</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody id="tbl-list">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        let idDestination = undefined;
        let list = [];
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

        function editData(txt, id, name) {
            idDestination = undefined;
            $.getJSON(window.location.origin + '/' + listRoutes['destination.edit'].replace('{id}', id), function() {})
                .done(function(e) {
                    idDestination = e.data[0].id;
                    $('#name').val(e.data[0].name);
                }).fail(function(e) {
                    alert('gagal')
                })
        }

        $('#form-add-update-data').validate({
            rules: {
                'name': 'required'
            },
            message: {
                name: {
                    required: 'Name destination tidak boleh kosong.!'
                }
            },
            submitHandler: function() {
                var urlStored;
                if (idDestination != undefined) {
                    urlStored = window.location.origin + '/' + listRoutes['destination.update'].replace('{id}',
                        idDestination);
                } else {
                    urlStored = window.location.origin + '/' + listRoutes['destination.stored'];
                }
                $.ajax({
                    url: urlStored,
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-add-update-data')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e) {
                        notifSweetAlertSuccess(e.meta.message);
                        idDestination = undefined;
                        $('#name').val('');
                        $('#tbl-destinations').DataTable().ajax.reload(null, false);
                    },
                    error: function(e) {
                        if (e.status == 422) {
                            notifSweetAlertErrors(e.responseJSON.errors);
                        }
                    }
                })
            }

        })
        function showOutlet(x){
            $.getJSON(window.location.origin + '/'+ listRoutes['destination.listoutlet'].replace('{id}', x), function(){

            }).done(function(x){
                if(x.data.listoutlet.length > 0){
                    list=[]
                    x.data.listoutlet.map((x)=>{
                        listDataOutlet = {
                            outletname: x.name,
                            address: x.address,
                            phone: x.phone
                        }
                        list.push(listDataOutlet);
                    })
                    getList();
                } else {
                    $('#tbl-list').html('')
                }
            });
        }
        const getList=()=>{
            $('#tbl-list').html('')
            let no = 1;
            list.map((x,i)=>{
                $('#tbl-list').append(
                    `
                    <tr>
                        <td>${no++}</td>
                        <td>${x.outletname}</td>
                        <td>${x.address}</td>
                        <td>${x.phone}</td>
                    </tr>
                    `
                )
            })
        }
    </script>
@endsection
