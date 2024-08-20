@extends('layout.app')

@section('title')
    <span>Laporan</span>
    <small>/</small>
    <small>Transaksi</small>
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
                    <a class="nav-link" href="{{ url('/report') }}">
                        <i data-feather="package" class="font-medium-3 me-50"></i>
                        <span class="fw-bold">Pengiriman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/report/transaksi">
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
                                        <label for="destination">Destinasi</label>
                                        <select name="destination" id="destination" class="form-control">
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
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Pilih Status</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Process</option>
                                            <option value="3">Done</option>
                                            <option value="4">Cancle</option>
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
                        <table class="table" id="tbl-laporanpengiriman">
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
                                {{-- <tr>
                                    <td>1</td>
                                    <td>Jeruk</td>
                                    <td>EL-000001</td>
                                    <td>12/8/2023</td>
                                    <td>16/8/2023</td>
                                    <td>Bandung</td>
                                    <td>Yogyakarta</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                    <td>Rp 200.000</td>
                                </tr> --}}
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
    <script>

        $(document).ready(function() {
            $('#destination').select2();

            // getCustomerByOutlet
            $('#outlet_id_select').change(function () {
                const outlet_id = $('#outlet_id_select').val();
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
                rules :{
                    'customer'              : 'required',
                    'destination'           : 'required',
                    'tanggal_order_awal'    : 'required',
                    'tanggal_order_akhir'   : 'required',
                    'status'                : 'required'
                },

                messages : {
                    'customer'              : 'Pilih salah satu customer.',
                    'destination'           : 'Pilih salah satu destination.',
                    'tanggal_order_awal'    : 'Tanggal order awal harus diisi',
                    'tanggal_order_akhir'   : 'Tanggal order akhir harus diisi',
                    'status'                : 'Pilih salah satu status.'
                },

                submitHandler:function(){
                    var formData = new FormData($('#form-report-transaksi')[0])

                    var outlet_id = $('#outlet_id_select').val()

                    formData.append('outlet_id', outlet_id)


                    $.ajax({
                        url: '/report/getReportTransaksi',
                        type: "POST",
                        dataType: "JSON",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#form-report-transaksi')[0].reset();
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
                                        <td>${order.customer.name}</td>
                                        <td>${order.numberorders}</td>
                                        <td>${new Date(order.created_at).toLocaleString('id-ID', {
                                                year: 'numeric',
                                                month: '2-digit',
                                                day: '2-digit',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                second: '2-digit',
                                                hour12: false
                                            }).replace(/\//g, '-')}
                                        </td>
                                        <td>${order.detailmanifests.manifest.detailtraveldocument.traveldocument.finish_date}</td>
                                        <td>${order.outlet.destination.name}</td>
                                        <td>${order.destination.name}</td>
                                        <td>${order.weight}</td>
                                        <td>${order.weight}</td>
                                        <td>${formattedPrice}</td>
                                    </tr>
                                `)
                            })
                        },

                        error: function (response) {
                            $('#form-report-transaksi')[0].reset();
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
        });
    </script>
@endsection
