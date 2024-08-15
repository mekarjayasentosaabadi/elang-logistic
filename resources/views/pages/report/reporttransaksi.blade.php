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
                            <tbody>
                                <tr>
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
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jeruk</td>
                                    <td>EL-000001</td>
                                    <td>12/8/2023</td>
                                    <td>16/8/2023</td>
                                    <td>Bandung</td>
                                    <td>Yogyakarta</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                    <td>Rp 200.000</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Jeruk</td>
                                    <td>EL-000001</td>
                                    <td>12/8/2023</td>
                                    <td>16/8/2023</td>
                                    <td>Bandung</td>
                                    <td>Yogyakarta</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                    <td>Rp 200.000</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Jeruk</td>
                                    <td>EL-000001</td>
                                    <td>12/8/2023</td>
                                    <td>16/8/2023</td>
                                    <td>Bandung</td>
                                    <td>Yogyakarta</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                    <td>Rp 200.000</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Jeruk</td>
                                    <td>EL-000001</td>
                                    <td>12/8/2023</td>
                                    <td>16/8/2023</td>
                                    <td>Bandung</td>
                                    <td>Yogyakarta</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                    <td>Rp 200.000</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Jeruk</td>
                                    <td>EL-000001</td>
                                    <td>12/8/2023</td>
                                    <td>16/8/2023</td>
                                    <td>Bandung</td>
                                    <td>Yogyakarta</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                    <td>Rp 200.000</td>
                                </tr>

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
                }
            })
        });
    </script>
@endsection
