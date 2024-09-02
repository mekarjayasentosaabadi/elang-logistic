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
                        <a href="{{ route('manifest.index') }}" class="btn btn-warning"><li class="fa fa-undo"></li> Kembali</a>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-xs-12">
                                <div class="form-group">
                                    <label for="manifestsno">Manifest No</label>
                                    <input type="text" name="manifestno" id="manifestno" class="form-control" required
                                        minlength="1" maxlength="10">
                                </div>

                                <div class="form-group mt-1 " id="form-commodity">
                                    <label for="commodity">Commodity</label>
                                    <select name="commodity" id="commodity" class="form-control">
                                        <option value="">-- Select Commodity --</option>
                                        <option value="1">LV</option>
                                        <option value="2">HV</option>
                                        <option value="3">FE</option>
                                        <option value="4">MIX</option>
                                    </select>
                                </div>
                                <div class="form-group mt-1 " id="form-flight-no">
                                    <label for="flightno">Flight No</label>
                                    <input type="text" name="flightno" id="flightno" class="form-control">
                                </div>
                                <div class="form-group mt-1 " id="form-no-bags">
                                    <label for="no_smd">No Surat Muatan Darat</label>
                                    <input type="text" name="no_smd" id="no_smd" class="form-control">
                                    <input type="hidden" name="destination_id" id="destination_id">
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
                                <div class="form-group mt-1 " id="form-flags-file">
                                    <label for="flagsfile">Flags File</label>
                                    <input type="text" name="flagsfile" id="flagsfile" class="form-control">
                                </div>
                                {{-- notes --}}
                                <div class="form-group mt-1">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (Auth::user()->role_id == '1')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Outlet Asal</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="outlet_id">Outlet Asal</label>
                                        <select name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="">Pilih Outlet Asal</option>
                                            @foreach ($outlets as $outlet)
                                                <option value="{{ $outlet->id }}"
                                                    {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>
                                                    {{ $outlet->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- Card Detail --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Manifest</h3>
                        <button type="button" class="btn btn-primary" id="add-order"><li class="fa fa-plus"></li> Add
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
                                            <th>Berat</th>
                                            <th>Koli</th>
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
                                                    Total Berat :
                                                </div>
                                                <div class="col-md-6" id="total-jumlah-kg">

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Total Koli :
                                                </div>
                                                <div class="col-md-6" id="total-jumlah-koli">

                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <button class="btn btn-primary btn-md" onclick="saveManifest()">
                                                        <li class="fa fa-save"></li> Simpan</button>
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
                                    <th>Berat</th>
                                    <th>Koli</th>
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
    <script>
        let arrOrders = [];
        let arrOrdersTemp = [];
        let idOrders = [];
        var table
        let destination = '';
        const role_id = '{{ Auth::user()->role_id }}';
        $(document).ready(function() {
            table = $('#tbl-orders').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/manifest/getOrders') }}",
                    type: 'GET',
                    data: function(d) {
                        d.outlet_id = $('#outlet_id').val();
                        d.idOrders = idOrders;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'numberorders',
                        name: 'numberorders'
                    },
                    {
                        data: 'namecustomer',
                        name: 'customer.name'
                    },
                    {
                        data: 'destination',
                        name: 'destination.name'
                    },
                    {
                        data: 'weight',
                        name: 'weight',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'koli',
                        name: 'koli',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'check',
                        name: 'check'
                    },
                ]
            });

            $('#add-order').click(function() {
                if (role_id == '1') {
                    if ($('#outlet_id').val() == '') {
                        notifSweetAlert('warning', 'Pilih Outlet Asal terlebih dahulu');
                    } else {
                        $('#exampleModalCenter').modal('show');
                    }
                } else {

                    $('#exampleModalCenter').modal('show');
                }
                table.ajax.reload();
            });

            $('#tbl-orders').on('click', '.checkbox-table', function() {
                // check the checkbox checked or not
                const checked = $(this).prop('checked');

                if (checked) {
                    const id = $(this).val();
                    var baseUrl = window.location.origin + '/' + listRoutes['manifest.checkOrders'].replace(
                        '{id}', id);
                    $.getJSON(baseUrl, function() {

                    }).done(function(e) {
                        if (destination == '') {
                            destination = e.data[0].destination.name;
                            $('#destination_id').val(e.data[0].destination.id);
                        } else {
                            if (destination != e.data[0].destination.name) {
                                // set to unchecked
                                $('#tbl-orders #checkbox-table-' + id).prop('checked', false);
                                notifSweetAlert('warning', 'Destination harus sama');
                                return false;
                            }
                        }

                        let dataArrCheck = {
                            ordersid: e.data[0].id,
                            numberorder: e.data[0].numberorders,
                            customername: e.data[0].customer.name,
                            destination: e.data[0].destination.name,
                            volume: e.data[0].volume,
                            weight: e.data[0].weight,
                            koli: e.data[0].koli,
                            content: e.data[0].content,
                        }
                        arrOrders.push(dataArrCheck);
                        idOrders.push(e.data[0].id);
                        getDataDetailOrders();


                    }).fail(function(e) {
                        console.log(e)
                    })
                } else {
                    arrOrders = arrOrders.filter((x) => x.ordersid != $(this).val());
                    idOrders = idOrders.filter((x) => x != $(this).val());
                    getDataDetailOrders();
                    if (arrOrders.length == 0) {
                        destination = '';
                        $('#destination_id').val('');
                    }
                }

            });


        });

        function checkcarrier() {
            // var carrier = $('#carrier').val();
            // carrier == "3" ? $('#form-commodity').removeClass("hidden") && $('#form-flight-no').removeClass("hidden") && $(
            //         '#form-no-bags').removeClass("hidden") && $('#form-flags-file').removeClass("hidden") : $(
            //         '#form-commodity').addClass("hidden") && $('#form-flight-no').addClass("hidden") && $('#form-no-bags')
            //     .addClass("hidden") && $('#form-flags-file').addClass("hidden");
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
                        <td>${x.weight ?? x.volume}</td>
                        <td>${x.koli}</td>
                        <td><button type="button" class="btn btn-danger btn-sm" onclick="removeDetail(${x.ordersid})"><i class="fa fa-trash"></i></button></td>
                    </tr>
                    `
                )
            })
            totalItems();
            totalKg();
        }

        const removeDetail = (id) => {
            arrOrders = arrOrders.filter((x) => x.ordersid != id);
            idOrders = idOrders.filter((x) => x != id);
            getDataDetailOrders();
            if (arrOrders.length == 0) {
                destination = '';
                $('#destination_id').val('');
            }
        }

        function totalItems() {
            $('#total-item').html(arrOrders.length);
        }

        function totalKg() {
            let jumlahKg = []
            let jumlahKoli = []
            totalKgs = 0;
            totalKoli = 0;
            arrOrders.map((x, i) => {
                convert = Math.round(x.weight ?? x.volume);
                jumlahKg.push(convert);
                jumlahKoli.push(x.koli);
            })
            for (i in jumlahKg) {
                totalKgs += jumlahKg[i];
                totalKoli += jumlahKoli[i];
            }
            $('#total-jumlah-kg').html(totalKgs);
            $('#total-jumlah-koli').html(totalKoli);
        }

        //save manifest
        function saveManifest() {
            $('#form-add-manifest').validate({
                rules: {
                    'manifestno': 'required',
                    'carrier': 'required',
                    'no_smd': 'required',
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
                            if (e.meta.code == 200) {
                                notifSweetAlertSuccess(e.meta.message);
                                setTimeout(function() {
                                    location.replace(window.location.origin + '/manifest');
                                }, 1500);
                            } else {
                                notifSweetAlertErrors(e.meta.message);
                            }
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
