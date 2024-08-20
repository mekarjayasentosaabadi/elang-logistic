@extends('layout.app')

@section('title')
    <span>Laporan</span>
    <small>/</small>
    <small>Pengiriman</small>
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
            <ul class="nav nav-pills mb-2">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ url('/report') }}">
                        <i data-feather="package" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Pengiriman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/report/transaksi">
                        <i data-feather="clipboard" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Transaksi</span>
                    </a>
                </li>
            </ul>
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
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Pilih Status</option>
                                            <option value="1">Process</option>
                                            <option value="2">Done</option>
                                            <option value="0">Cancle</option>
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
                    <a href="" class="btn btn-success mt-3 mb-3 float-end btn-sm"><i data-feather="download" class="font-medium-3 me-50"></i>Download</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#destination').select2();

            // get driver by outlet
            $('#outlet_id_select').change(function () {
                const outletId = $('#outlet_id_select').val();
                $.ajax({
                    url: '/report/getDriverByOutlet',
                    type: 'GET',
                    data: {
                        outletid: outletId
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
               rules:{
                    'driver'                  : 'required',
                    'jenis_pengiriman'        : 'required',
                    'tanggal_awal_berangkat'  : 'required',
                    'tanggal_akhir_berangkat' : 'required',
                    'destination'             : 'required',
                    'status'                  : 'required'
               },
               messages:{
                    'driver'                  : 'Pilih salah satu driver.',
                    'jenis_pengiriman'        : 'Pilih salah satu jenis pengiriman.',
                    'tanggal_awal_berangkat'  : 'Tanggal awal berangkat harus diisi.',
                    'tanggal_akhir_berangkat' : 'Tanggal akhir berangkat harus diisi.',
                    'destination'             : 'Pilih salah satu destinasi.',
                    'status'                  : 'Pilih salah satu status.'
               },
               submitHandler:function(){
                    var formData = new FormData($('#form-report-pengiriman')[0])

                    var outletId = $('#outlet_id_select').val()

                    formData.append('outlet_id', outletId)

                    $.ajax({
                        url: '/report/getReportPengiriman',
                        type: "POST",
                        dataType: "JSON",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response){
                            $('#form-report-pengiriman')[0].reset();
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
                                            <td>${report.driver.name}</td>
                                            <td>${report.travelno}</td>
                                            <td>${report.vehicle.police_no}</td>
                                            <td>${report.start_date}</td>
                                            <td>${report.finish_date}</td>
                                            <td>${jenisPengiriman}</td>
                                            <td>${report.outlet.name}</td>
                                            <td>${report.destinasi}</td>
                                            <td>${report.berat_volume}</td>
                                            <td>${report.berat_volume}</td>
                                        </tr>
                                    `);
                                });
                            });
                        },
                        error: function(e){
                            $('#form-report-pengiriman')[0].reset();
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
        });
    </script>
@endsection
