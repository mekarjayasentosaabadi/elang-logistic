@extends('layout.app')
@section('title')
    <span>Manifest</span>
    <small>/</small>
    <small>Create</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <form action="#" id="form-add-manifest">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Add Manifest</h4>
                        <a href="{{ route('manifest.index') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-xs-12">
                                <div class="form-group">
                                    <label for="manifestsno">Manifest No</label>
                                    <input type="text" name="manifestno" id="manifestno" class="form-control" required>
                                </div>

                                <div class="form-group mt-1 hidden" id="form-commodity">
                                    <label for="commodity">Commodity</label>
                                    <select name="commodity" id="commodity" class="form-control">
                                        <option value="">-- Select Commodity --</option>
                                        <option value="1">LV</option>
                                        <option value="2">HV</option>
                                        <option value="3">FE</option>
                                        <option value="4">MIX</option>
                                    </select>
                                </div>
                                <div class="form-group mt-1 hidden" id="form-flight-no">
                                    <label for="flightno">Flight No</label>
                                    <input type="text" name="flightno" id="flightno" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-xs-12">
                                <div class="form-group">
                                    <label for="carrier">Carrier</label>
                                    <select name="carrier" id="carrier" class="form-control" onchange="checkcarrier()">
                                        <option value="">-- Pilih Carrier --</option>
                                        <option value="1">Darat</option>
                                        <option value="2">Laut</option>
                                        <option value="3">Udara</option>
                                    </select>
                                </div>

                                <div class="form-group mt-1 hidden" id="form-no-bags">
                                    <label for="nobags">No Bags</label>
                                    <input type="text" name="nobags" id="nobags" class="form-control">
                                </div>
                                <div class="form-group mt-1 hidden" id="form-flags-file">
                                    <label for="flagsfile">Flags File</label>
                                    <input type="text" name="flagsfile" id="flagsfile" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Card Detail --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Manifest</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModalCenter"> Add
                            Order</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" id="tbl-manifests">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Numbers</th>
                                            <th>Customer</th>
                                            <th>Destinations</th>
                                            <th>Items</th>
                                            <th>Kg</th>
                                            <th>Content</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-detail-manifests">

                                    </tbody>
                                </table>

                                <div class="mt-4">
                                    <div class="row">
                                        <div class="col-md-6">

                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Total Items :
                                                </div>
                                                <div class="col-md-6" id="total-item">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Total Kg :
                                                </div>
                                                <div class="col-md-6" id="total-jumlah-kg">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Total AWB :
                                                </div>
                                                <div class="col-md-6">

                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary btn-md" onclick="saveManifest()">
                                                        Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal  --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">List Data Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table " id="tbl-orders">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Order/ AWB</th>
                                    <th>Customer</th>
                                    <th>Destination</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    {{-- <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script> --}}
    <script>
        let arrOrders = [];
        let arrOrdersTemp = [];
        var table
        $(document).ready(function() {
            table = $('#tbl-orders').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/manifest/getOrders') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'numberorders',
                        name: 'numberorders'
                    },
                    {
                        data: 'namacustomer',
                        name: 'namacustomer'
                    },
                    {
                        data: 'destination',
                        name: 'destination'
                    },
                    {
                        data: 'check',
                        name: 'check'
                    },
                ]
            });
        });

        function checkcarrier() {
            var carrier = $('#carrier').val();
            carrier == "3" ? $('#form-commodity').removeClass("hidden") && $('#form-flight-no').removeClass("hidden") && $(
                    '#form-no-bags').removeClass("hidden") && $('#form-flags-file').removeClass("hidden") : $(
                    '#form-commodity').addClass("hidden") && $('#form-flight-no').addClass("hidden") && $('#form-no-bags')
                .addClass("hidden") && $('#form-flags-file').addClass("hidden");
        }

        function check(x, id) {
            var baseUrl = window.location.origin + '/' + listRoutes['manifest.checkOrders'].replace('{id}', id);
            $.getJSON(baseUrl, function() {

            }).done(function(e) {
                console.log(e)
                let dataArrCheck = {
                    ordersid: e.data[0].id,
                    numberorder: e.data[0].numberorders,
                    customername: e.data[0].customer.name,
                    destination: e.data[0].destination.name,
                    items: e.data[0].volume,
                    kg: e.data[0].weight,
                    content: e.data[0].content,
                }
                arrOrders.push(dataArrCheck);
                getDataDetailOrders();

            }).fail(function(e) {
                console.log(e)
            })
        }

        // function check(x, i){
        //     let checkedvalue = [];
        //     // let checkboxes = $('input[name="checkbox'+i+'"]').prop()
        //     let checkboxes = document.getElementById('checkbox'+i);
        //     // checkedvalue.push(checkboxes, checkboxes.val())
        //     console.log(checkboxes.prop("chekced"));
        // }

        const getDataDetailOrders = () => {
            $('#tbl-detail-manifests').html('')
            let noOrders = 1;
            arrOrders.map((x, i) => {
                $('#tbl-detail-manifests').append(
                    `
                    <tr>
                        <td>${noOrders++}</td>
                        <td>${x.numberorder}<input type="hidden" name="ordersid[]" value="${x.ordersid}"></td>
                        <td>${x.customername}</td>
                        <td>${x.destination}</td>
                        <td>${x.items}</td>
                        <td>${x.kg}</td>
                        <td>${x.content}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(${i})"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    `
                )
            })
            totalItems();
            totalKg();
        }

        const removeDetail = (i) => {
            arrOrders.splice(i, 1)
            getDataDetailOrders()
        }

        function totalItems() {
            let jumlahItem = []
            totalItem = 0;
            arrOrders.map((x, i) => {
                convert = Math.round(x.items);
                jumlahItem.push(convert);
            })
            for (i in jumlahItem) {
                totalItem += jumlahItem[i];
            }
            $('#total-item').html(totalItem);
        }

        function totalKg() {
            let jumlahKg = []
            totalKgs = 0;
            arrOrders.map((x, i) => {
                convert = Math.round(x.kg);
                jumlahKg.push(convert);
            })
            for (i in jumlahKg) {
                totalKgs += jumlahKg[i];
            }
            $('#total-jumlah-kg').html(totalKgs);
        }

        //save manifest
        function saveManifest() {
            $('#form-add-manifest').validate({
                rules: {
                    'manifestno': 'required',
                    'carrier': 'required',
                    // 'commodity': 'required',
                    // 'flightno': 'required',
                    // 'nobags': 'required',
                    // 'flagsfile': 'required'
                },
                submitHandler: function() {
                    $.ajax({
                        url: window.location.origin + '/manifest/store',
                        type: "POST",
                        dataType: "JSON",
                        data: new FormData($('#form-add-manifest')[0]),
                        processData: false,
                        contentType: false,
                        success: function(e) {
                            notifSweetAlertSuccess(e.meta.message);
                            location.replace(window.location.origin + '/manifest');
                        },
                        error: function(e) {
                            notifSweetAlertErrors(e.meta.message);
                        }
                    })
                }
            })
        }
    </script>
@endsection
