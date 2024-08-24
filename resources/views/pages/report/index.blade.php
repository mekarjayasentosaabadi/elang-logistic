@extends('layout.app')

@section('title')
    <span>Laporan</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('custom-css')
    <style>
        th, td {
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs mt-3" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="laporanpengiriman-tab" data-bs-toggle="tab" href="#laporanpengiriman" aria-controls="laporanpengiriman" role="tab" aria-selected="true">
                        <i data-feather="package" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Pengiriman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="laporantransaksi-tab" data-bs-toggle="tab" href="#laporantransaksi" aria-controls="laporantransaksi" role="tab" aria-selected="false">
                        <i data-feather="clipboard" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Transaksi</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="laporanpengiriman" aria-labelledby="laporanpengiriman-tab" role="tabpanel">
                    @if (Auth::user()->role_id == '1')
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Outlet</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="outlet_id_select">Outlet</label>
                                        <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                            <option value="" hidden>Pilih Outlet</option>
                                            @foreach ($outlets as $outlet)
                                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- report pengiriman --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Laporan Pengiriman</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <form action="" method="POST" id="form-report-pengiriman">
                                <div class="row mt-2" id="">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="driver">Driver</label>
                                            @if (Auth::user()->role_id == '1')
                                                <select name="driver" id="driver" class="form-control">
                                                    <option value="">Pilih Driver</option>
                                                </select>
                                            @else
                                                <select name="driver" id="driver" class="form-control">
                                                    <option value="">Pilih Driver</option>
                                                    @foreach ($drivers as $driver)
                                                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="jenis_pengiriman">Jenis Pengiriman</label>
                                            <select name="jenis_pengiriman" id="jenis_pengiriman" class="form-control">
                                                <option value="">Pilih Jenis Pengiriman</option>
                                                <option value="1">Darat</option>
                                                <option value="2">Laut</option>
                                                <option value="3">Udara</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_awal_berangkat">Tanggal Awal Berangkat</label>
                                            <input class="form-control" type="date" name="tanggal_awal_berangkat" id="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_akhir_berangkat">Tanggal Akhir Berangkat</label>
                                            <input class="form-control" type="date" name="tanggal_akhir_berangkat" id="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" id="">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="destination">Destinasi</label>
                                            <select name="destination" id="destination" class="form-control">
                                                <option value="">Pilih Destinasi</option>
                                                @foreach ($destinations as $destination)
                                                    <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status_surattugas">Status Surat Tugas</label>
                                            <select name="status_surattugas" id="status_surattugas" class="form-control">
                                                <option value="">Pilih Status</option>
                                                <option value="1">Process</option>
                                                <option value="2">Done</option>
                                                <option value="0">Cancle</option>
                                                <option value="5">All</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 text-end">
                                    <button type="submit" class="btn btn-primary"><i data-feather="eye" class="font-medium-3 me-50"></i>Lihat</button>
                                </div>
                            </form>
                        </div>
                        <hr class="mb-3">
                        <div class="table-responsive">
                            <table class="table" id="tbl-laporanpengiriman">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Driver</th>
                                        <th>No Surat Jalan</th>
                                        <th>No Kendaraan</th>
                                        <th>Tanggal Berangat</th>
                                        <th>Tanggal Finish</th>
                                        <th>Jenis Pengiriman</th>
                                        <th>Asal</th>
                                        <th>Destinasi</th>
                                        <th>Volume/Berat</th>
                                        <th>Total Volume/Berat</th>
                                    </tr>
                                </thead>
                                <tbody id="tblbody-reportpengiriman">

                                </tbody>
                            </table>
                        </div>
                        <form action="/report/downloadreportpengiriman" method="post">
                            @csrf
                            <input type="hidden" name="outlet_id_select" id="outlet_id_select-hidden" value="">
                            <input type="hidden" name="driver" id="driver-hidden" value="">
                            <input type="hidden" name="jenis_pengiriman" id="jenis_pengiriman-hidden" value="">
                            <input type="hidden" name="tanggal_awal_berangkat" id="tanggal_awal_berangkat-hidden" value="">
                            <input type="hidden" name="tanggal_akhir_berangkat" id="tanggal_akhir_berangkat-hidden" value="">
                            <input type="hidden" name="destination" id="destination-hidden" value="">
                            <input type="hidden" name="status_surattugas" id="status_surattugas-hidden" value="">
                            <button type="submit" class="btn btn-success mt-3 mb-3 float-end btn-sm d-none" id="btn-download-reportpengiriman"><i data-feather="download" class="font-medium-3 me-50"></i>Download</button>
                        </form>
                        {{-- <a href="/report/downloadreportpengiriman" class="btn btn-success mt-3 mb-3 float-end btn-sm"><i data-feather="download" class="font-medium-3 me-50"></i>Download</a> --}}
                    </div>
                </div>
                </div>

                {{-- end report pengiriman --}}

                {{-- report transaksi --}}
                <div class="tab-pane" id="laporantransaksi" aria-labelledby="laporantransaksi-tab" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            @if (Auth::user()->role_id == '1')
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Outlet</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="outlet_id_select_customer">Outlet</label>
                                                    <select name="outlet_id_select_customer" id="outlet_id_select_customer" class="form-control">
                                                        <option value="" hidden>Pilih Outlet</option>
                                                        @foreach ($outlets as $outlet)
                                                            <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Laporan Transaksi</h4>
                                </div>
                                <div class="card-body">
                                    <div class="">
                                        <form action="" method="POST" id="form-report-transaksi">
                                            <div class="row mt-2" id="">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="customer">Nama Customer</label>
                                                        <select name="customer" id="customer" class="form-control">
                                                            <option value="">Pilih Customer</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="destination_transaksi">Destinasi</label>
                                                        <select name="destination_transaksi" id="destination_transaksi" class="form-control">
                                                            <option value="">Pilih Destinasi</option>
                                                            @foreach ($destinations as $destination)
                                                                <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row mt-2" id="">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tanggal_order_awal">Tanggal Order Awal</label>
                                                        <input class="form-control" type="date" name="tanggal_order_awal" id="tanggal_order_awal">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="tanggal_order_akhir">Tanggal Order Akhir</label>
                                                        <input class="form-control" type="date" name="tanggal_order_akhir" id="tanggal_order_akhir">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2" id="">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status Order</label>
                                                        <select name="status" id="status" class="form-control">
                                                            <option value="">Pilih Status</option>
                                                            <option value="1">Pending</option>
                                                            <option value="2">Process</option>
                                                            <option value="3">Done</option>
                                                            <option value="4">Cancle</option>
                                                            <option value="5">ALL</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mt-2">
                                                        <button type="submit" class="btn btn-primary float-end"><i data-feather="eye" class="font-medium-3 me-50"></i>Lihat</button>
                                                    </div>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <hr class="mt-3 mb-3">
                                    <div class="table-responsive">
                                        <table class="table" id="tbl-laporantransaksi">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Customer</th>
                                                    <th>AWB</th>
                                                    <th>Tanggal Order</th>
                                                    <th>Tanggal Finish</th>
                                                    <th>Asal</th>
                                                    <th>Destinasi</th>
                                                    <th>Volume/Berat</th>
                                                    <th>Total Volume/Berat</th>
                                                    <th>Total Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblbody-reporttransaksi">

                                            </tbody>
                                        </table>
                                    </div>
                                    <form action="/report/downloadreporttransaksi" method="post" id="form-hidden-report-transaksi">
                                        @csrf
                                        <input type="hidden" name="tanggal_order_awal" id="tanggal_order_awal_hidden" value="">
                                        <input type="hidden" name="tanggal_order_akhir" id="tanggal_order_akhir_hidden" value="">
                                        <input type="hidden" name="customer_id" id="customer_id_hidden" value="">
                                        <input type="hidden" name="destination_id" id="destination_id_hidden" value="">
                                        <input type="hidden" name="status" id="status_hidden" value="">
                                        <input type="hidden" name="outlet_id_select_customer" id="outlet_id_select_customer_hidden" value="">
                                        <button type="submit" class="btn btn-success mt-3 mb-3 float-end btn-sm d-none" id="btn-download-reporttransaksi"><i data-feather="download" class="font-medium-3 me-50"></i>Download</button>
                                    </form>
                                    {{-- <a href="/report/downloadreporttransaksi" class="btn btn-success mt-3 mb-3 float-end btn-sm"><i data-feather="download" class="font-medium-3 me-50"></i>Download</a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end report transaksi --}}

            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            //------report pengiriman js------//
            $('#destination').select2();

            // get driver by outlet
            $('#outlet_id_select').change(function () {
                const outlet_id = $('#outlet_id_select').val();
                $.ajax({
                    url: '/report/getDriverByOutlet',
                    type: 'GET',
                    data: {
                        outlet_id: outlet_id
                    },

                    success: function (response) {
                        var driver = response.drivers;
                        var driverSelect = $('#driver')
                        driverSelect.empty();

                        driverSelect.append('<option value="" hidden>Pilih Driver</option>');

                        if (driver != null) {
                            driver.forEach(function (driver) {
                                driverSelect.append('<option value="'+ driver.id +'">'+driver.name+'</option>')
                            });
                        }
                    },
                    error: function(response){
                        console.log(response);
                    }

                })
            })


            $('#form-report-pengiriman').validate({
            //    rules:{
            //         'driver'                  : 'required',
            //         'jenis_pengiriman'        : 'required',
            //         'tanggal_awal_berangkat'  : 'required',
            //         'tanggal_akhir_berangkat' : 'required',
            //         'destination'             : 'required',
            //         'status'                  : 'required'
            //    },
            //    messages:{
            //         'driver'                  : 'Pilih salah satu driver.',
            //         'jenis_pengiriman'        : 'Pilih salah satu jenis pengiriman.',
            //         'tanggal_awal_berangkat'  : 'Tanggal awal berangkat harus diisi.',
            //         'tanggal_akhir_berangkat' : 'Tanggal akhir berangkat harus diisi.',
            //         'destination'             : 'Pilih salah satu destinasi.',
            //         'status'                  : 'Pilih salah satu status.'
            //    },
               submitHandler:function(){
                    var formData = new FormData($('#form-report-pengiriman')[0])

                    var outlet_id = $('#outlet_id_select').val()

                    formData.append('outlet_id', outlet_id)

                    $.ajax({
                        url: '/report/getReportPengiriman',
                        type: "POST",
                        dataType: "JSON",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response){
                              $('#outlet_id_select-hidden').val($('#outlet_id_select').val())
                              $('#driver-hidden').val($('#driver').val())
                              $('#jenis_pengiriman-hidden').val($('#jenis_pengiriman').val())
                              $('#tanggal_awal_berangkat-hidden').val($('#tanggal_awal_berangkat').val())
                              $('#tanggal_akhir_berangkat-hidden').val($('#tanggal_akhir_berangkat').val())
                              $('#destination-hidden').val($('#destination').val())
                              $('#status_surattugas-hidden').val($('#status_surattugas').val())
                              $('#btn-download-reportpengiriman').removeClass('d-none')

                            var dataReports = response.dataReport

                            $('#tblbody-reportpengiriman').empty();

                            dataReports.forEach(function(report, index) {
                                report.detailsurattugas.forEach(function(detail) {
                                    let jenisPengiriman = report.jenis_pengiriman
                                    if (jenisPengiriman == 1) {
                                        jenisPengiriman = "Darat"
                                    }else if (jenisPengiriman == 2) {
                                        jenisPengiriman = "Laut"
                                    }else if (jenisPengiriman == 3) {
                                        jenisPengiriman = "Udara"
                                    }
                                    $('#tblbody-reportpengiriman').append(`
                                        <tr>
                                            <td>${index + 1}</td>
                                            <td>${report.driver && report.driver.name ? report.driver.name : '-'}</td>
                                            <td>${report.travelno ? report.travelno : '-'}</td>
                                            <td>${report.vehicle && report.vehicle.police_no ? report.vehicle.police_no : '-'}</td>
                                            <td>${report.start_date ? report.start_date : '-'}</td>
                                            <td>${report.finish_date ? report.finish_date : '-'}</td>
                                            <td>${jenisPengiriman ? jenisPengiriman : '-'}</td>
                                            <td>${report.outlet && report.outlet.destination && report.outlet.destination.name ? report.outlet.destination.name : '-'}</td>
                                            <td>${report.destinasi ? report.destinasi : '-'}</td>
                                            <td>${report.berat_volume ? report.berat_volume : '-'}</td>
                                            <td>${report.berat_volume ? report.berat_volume : '-'}</td>
                                        </tr>
                                    `);
                                });
                            });
                        },
                        error: function(e){
                            $('#tblbody-reportpengiriman').empty();
                            console.log('error');
                        }
                    })
                },

               errorPlacement:function (error, element) {
                    if (element.closest('.form-group').length) {
                        error.insertAfter(element.closest('.form-group'))
                    }else{
                        error.insertAfter(element);
                    }
                }
            })
            //------end pengiriman js------//







            //------report transaksi js------//
            $('#destination_transaksi').select2();

            // getCustomerByOutlet
            $('#outlet_id_select_customer').change(function () {
                const outlet_id = $('#outlet_id_select_customer').val();
                $.ajax({
                    url: '/report/getCustomerByOutlet',
                    type: 'GET',
                    data: {
                        outlet_id : outlet_id
                    },

                    success: function (response) {
                        var customer = response.customers
                        var customerSelect = $('#customer')
                        customerSelect.empty();

                        customerSelect.append('<option value="" hidden>Pilih Customer</option>');

                        if (customer != null) {
                            customer.forEach(function (customer) {
                                customerSelect.append('<option value="'+ customer.id +'">'+customer.name+'</option>');
                            });
                        }

                    },

                    error:function(response){
                        console.log('error');
                    }
                })
            })

            // form validate
            $('#form-report-transaksi').validate({
                // rules :{
                //     'customer'              : 'required',
                //     'destination_transaksi' : 'required',
                //     'tanggal_order_awal'    : 'required',
                //     'tanggal_order_akhir'   : 'required',
                //     'status'                : 'required'
                // },

                // messages : {
                //     'customer'              : 'Pilih salah satu customer.',
                //     'destination_transaksi' : 'Pilih salah satu destinasi.',
                //     'tanggal_order_awal'    : 'Tanggal order awal harus diisi',
                //     'tanggal_order_akhir'   : 'Tanggal order akhir harus diisi',
                //     'status'                : 'Pilih salah satu status.'
                // },

                submitHandler:function(){
                    var formData = new FormData($('#form-report-transaksi')[0])

                    var outlet_id = $('#outlet_id_select_customer').val()

                    formData.append('outlet_id', outlet_id)


                    $.ajax({
                        url: '/report/getReportTransaksi',
                        type: "POST",
                        dataType: "JSON",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#outlet_id_select_customer_hidden').val($('#outlet_id_select_customer').val())
                            $('#customer_id_hidden').val($('#customer').val())
                            $('#destination_id_hidden').val($('#destination_transaksi').val())
                            $('#tanggal_order_awal_hidden').val($('#tanggal_order_awal').val())
                            $('#tanggal_order_akhir_hidden').val($('#tanggal_order_akhir').val())
                            $('#status_hidden').val($('#status').val())
                            $('#btn-download-reporttransaksi').removeClass('d-none')



                            var dataOrders = response.orders

                            $('#tblbody-reporttransaksi').empty();

                            dataOrders.forEach(function (order, index) {

                                var formattedPrice = new Intl.NumberFormat('id-ID', {
                                    style: 'currency',
                                    currency: 'IDR',
                                    maximumFractionDigits: 0,
                                }).format(order.price);



                                $('#tblbody-reporttransaksi').append(`
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${order.customer && order.customer.name ? order.customer.name : '-'}</td>
                                        <td>${order.numberorders ? order.numberorders : '-'}</td>
                                        <td>${
                                            order.created_at
                                                ? new Date(order.created_at).toLocaleString('id-ID', {
                                                    year: 'numeric',
                                                    month: '2-digit',
                                                    day: '2-digit',
                                                    hour: '2-digit',
                                                    minute: '2-digit',
                                                    second: '2-digit',
                                                    hour12: false
                                                }).replace(/\//g, '-')
                                                : '-'
                                        }</td>
                                        <td>${
                                            order.detailmanifests &&
                                            order.detailmanifests.manifest &&
                                            order.detailmanifests.manifest.detailtraveldocument &&
                                            order.detailmanifests.manifest.detailtraveldocument.traveldocument &&
                                            order.detailmanifests.manifest.detailtraveldocument.traveldocument.finish_date
                                                ? order.detailmanifests.manifest.detailtraveldocument.traveldocument.finish_date
                                                : '-'
                                        }</td>
                                        <td>${order.outlet && order.outlet.destination && order.outlet.destination.name ? order.outlet.destination.name : '-'}</td>
                                        <td>${order.destination && order.destination.name ? order.destination.name : '-'}</td>
                                        <td>${order.weight ? order.weight : '-'}</td>
                                        <td>${order.weight ? order.weight : '-'}</td>
                                        <td>${formattedPrice ? formattedPrice : '-'}</td>
                                    </tr>
                                `);
                            })
                        },

                        error: function (response) {
                            $('#tblbody-reporttransaksi').empty();
                            console.log('error');
                        }

                    })
                },

                errorPlacement:function (error, element) {
                    if (element.closest('.form-group').length) {
                        error.insertAfter(element.closest('.form-group'))
                    }else{
                        error.insertAfter(element);
                    }
                }
            })
            //------end report transaksi js------//
        });
    </script>
@endsection
