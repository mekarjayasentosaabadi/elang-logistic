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
                                        <select name="driver" id="driver" class="form-control">
                                            <option value="">Pilih Driver</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jenis_pengiriman">Jenis Pengiriman</label>
                                        <select name="jenis_pengiriman" id="jenis_pengiriman" class="form-control">
                                            <option value="">Pilih Jenis Pengiriman</option>
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
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">Pilih Status</option>
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
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Pisang</td>
                                    <td>SE-00001</td>
                                    <td>B 222021 2012</td>
                                    <td>12/8/2023</td>
                                    <td>15/8/2023</td>
                                    <td>Darat</td>
                                    <td>Jakarta</td>
                                    <td>Bandung</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Pisang Sang Pisang</td>
                                    <td>SE-00001</td>
                                    <td>B 222021 2012</td>
                                    <td>12/8/2023</td>
                                    <td>15/8/2023</td>
                                    <td>Darat</td>
                                    <td>Jakarta</td>
                                    <td>Bandung</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Pisang</td>
                                    <td>SE-00001</td>
                                    <td>B 222021 2012</td>
                                    <td>12/8/2023</td>
                                    <td>15/8/2023</td>
                                    <td>Darat</td>
                                    <td>Jakarta</td>
                                    <td>Bandung</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Pisang</td>
                                    <td>SE-00001</td>
                                    <td>B 222021 2012</td>
                                    <td>12/8/2023</td>
                                    <td>15/8/2023</td>
                                    <td>Darat</td>
                                    <td>Jakarta</td>
                                    <td>Bandung</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Pisang</td>
                                    <td>SE-00001</td>
                                    <td>B 222021 2012</td>
                                    <td>12/8/2023</td>
                                    <td>15/8/2023</td>
                                    <td>Darat</td>
                                    <td>Jakarta</td>
                                    <td>Bandung</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Pisang</td>
                                    <td>SE-00001</td>
                                    <td>B 222021 2012</td>
                                    <td>12/8/2023</td>
                                    <td>15/8/2023</td>
                                    <td>Darat</td>
                                    <td>Jakarta</td>
                                    <td>Bandung</td>
                                    <td>200kg</td>
                                    <td>200kg</td>
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
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function() {
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
               }
            })
        });
    </script>
@endsection
