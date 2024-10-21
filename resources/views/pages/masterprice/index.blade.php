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
                    <a href="{{ route('masterprice.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add
                        Price</a>
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
        <div class="modal-dialog modal-lg">
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
                                    <th>Aksi</th>
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
    {{-- Modal Edit Price --}}
    <div class="modal fade" id="modalEditPrice" tabindex="-1" aria-labelledby="modalEditPriceTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #343131">
                    <h5 class="modal-title" id="modalEditPriceTitle" style="color: white;">Edit Harga </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="#" id="formeditprice" method="POST">
                    @csrf
                    <div class="modal-body" style="background-color: #F7EED3">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="asaloutlet">Asal Outlet</label>
                                    <input type="text" name="asaloutlet" id="asaloutlet" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="form-group">
                                    <label for="origin">Origin</label>
                                    <input type="text" name="origin" id="origin" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-12 mt-1">
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <input type="text" name="destination" id="destination" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-12 mt-1 form-edit-harga" id="form-price">
                                <div class="form-group">
                                    <label for="price">Harga</label>
                                    <input type="text" name="price" id="price" class="form-control edit-harga">
                                </div>
                            </div>
                            <div class="col-12 mt-1 form-edit-harga" id="form-nextweightprices">
                                <div class="form-group">
                                    <label for="nextweightprices">Next Weight Price</label>
                                    <input type="text" name="nextweightprices" id="nextweightprices"
                                        class="form-control edit-harga">
                                </div>
                            </div>
                            <div class="col-12 mt-1 form-edit-harga" id="form-minweights">
                                <div class="form-group">
                                    <label for="minweights">Minimum Berat</label>
                                    <input type="text" name="minweights" id="minweights" class="form-control edit-harga">
                                </div>
                            </div>
                            <div class="col-12 mt-1 form-edit-harga" id="form-estimation">
                                <div class="form-group">
                                    <label for="estimation">Estimation</label>
                                    <input type="text" name="estimation" id="estimation"
                                        class="form-control edit-harga">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #F7EED3">
                        <button class="btn btn-primary btn-sm" type="submit" onclick="ubah()">
                            <li class="fa fa-save"></li> Perbaharui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Modal Edit Price --}}
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        var table;
        var table2;
        var baseUrl = window.location.origin;
        var masterPriceId;
        let armada = 0;

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
                        name: 'outlet.name',
                        searchable: true
                    },
                    {
                        data: 'origin.name',
                        name: 'origin.name',
                        searchable: true
                    },
                    {
                        data: 'namaarmada',
                        name: 'namaarmada',
                        searchable: true
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]
            });

            $('#tbl-masterprice').on('click', '.btn-list', function() {
                var orid = $(this).data('orid');
                var oid = $(this).data('oid');
                var armd = $(this).data('armd');
                showDetail(orid, oid, armd);
                $('#exampleModalCenter').modal('show');
            });
        });

        function showDetail(orid, oid, armd) {
            // console.log(orid,oid,armd)
            $('#tbl-list').DataTable().destroy();
            var url = window.location.origin + '/' + listRoutes['masterprice.listhargapublic'].replace('{id}', orid)
                .replace('{id2}', oid).replace('{id3}', armd);

            if (armd == 1) {
                $('#tbl-list thead tr').html(
                    '<th>#</th><th>Asal Outlet</th><th>Origin</th><th>Destination</th><th>Harga</th><th>Harga Berikutnya</th><th>Estimasi</th><th>Aksi</th>'
                );
                table2 = $('#tbl-list').DataTable({
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
                            name: 'outlet.name',
                            searchable: true
                        },
                        {
                            data: 'origin.name',
                            name: 'origin.name',
                            searchable: true
                        },
                        {
                            data: 'destination.name',
                            name: 'destination.name',
                            searchable: true
                        },
                        {
                            data: 'price',
                            name: 'price',
                            searchable: true
                        },
                        {
                            data: 'nextweightprices',
                            name: 'nextweightprices',
                        },
                        {
                            data: 'estimation',
                            name: 'estimation',
                        },
                        {
                            data: 'aksi',
                            name: 'aksi'
                        }
                    ]
                });
            } else if (armd == 2) {
                $('#tbl-list thead tr').html(
                    '<th>#</th><th>Asal Outlet</th><th>Origin</th><th>Destination</th><th>Harga</th><th>Min. Kilo</th><th>Estimasi</th><th>Aksi</th>'
                );
                table2 = $('#tbl-list').DataTable({
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
                            name: 'outlet.name',
                            searchable: true
                        },
                        {
                            data: 'origin.name',
                            name: 'origin.name',
                            searchable: true
                        },
                        {
                            data: 'destination.name',
                            name: 'destination.name',
                            searchable: true
                        },
                        {
                            data: 'price',
                            name: 'price',
                            searchable: true
                        },
                        {
                            data: 'minweights',
                            name: 'minweights',
                        },
                        {
                            data: 'estimation',
                            name: 'estimation',
                        },
                        {
                            data: 'aksi',
                            name: 'aksi'
                        }
                    ]
                });
            } else {
                $('#tbl-list thead tr').html(
                    '<th>#</th><th>Asal Outlet</th><th>Origin</th><th>Destination</th><th>Harga</th><th>Estimasi</th><th>Aksi</th>'
                );
                table2 = $('#tbl-list').DataTable({
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
                            name: 'outlet.name',
                            searchable: true
                        },
                        {
                            data: 'origin.name',
                            name: 'origin.name',
                            searchable: true
                        },
                        {
                            data: 'destination.name',
                            name: 'destination.name',
                            searchable: true
                        },
                        {
                            data: 'price',
                            name: 'price',
                            searchable: true
                        },
                        {
                            data: 'estimation',
                            name: 'estimation',
                        },
                        {
                            data: 'aksi',
                            name: 'aksi'
                        }
                    ]
                });
            }
            armada = armd;
        }

        function editPrice(txt, id) {
            $.getJSON(window.location.origin + '/' + listRoutes['masterprice.detail'].replace('{id}', id), function(e) {})
                .done(function(e) {
                    masterPriceId = id;
                    $('#price').val(e.data[0].price);
                    $('#nextweightprices').val(e.data[0].nextweightprices);
                    $('#minweights').val(e.data[0].minweights);
                    $('#asaloutlet').val(e.data[0].outlet.name);
                    $('#origin').val(e.data[0].origin.name);
                    $('#destination').val(e.data[0].destination.name);
                    $('#estimation').val(e.data[0].estimation);
                    $('.form-edit-harga').removeClass('d-none');
                    if (armada == 1) {
                        $('#form-minweightprice').addClass('d-none');
                        $('#form-minimumprice').addClass('d-none');
                        $('#form-minweights').addClass('d-none');
                    } else if (armada == 2) {
                        $('#form-nextweightprices').addClass('d-none');
                    } else {
                        $('#form-minweightprice').addClass('d-none');
                        $('#form-nextweightprices').addClass('d-none');
                        $('#form-minweights').addClass('d-none');
                    }

                })
        }

        function ubah() {
            console.log(masterPriceId)
        }
        $('#formeditprice').validate({
            rules: {
                'price': 'required',
            },
            submitHandler: function() {
                $.ajax({
                    url: baseUrl + '/' + listRoutes['masterprice.ubah'].replace('{id}', masterPriceId),
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#formeditprice')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e) {
                        notifSweetAlertSuccess(e.meta.message);
                        table2.ajax.reload();
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            }
        })
    </script>
@endsection
