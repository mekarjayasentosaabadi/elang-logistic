@extends('layout.app')
@section('title')
    <span>Outlet</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('custom-css')
    <link rel="stylesheet" href="{{ asset('assets') }}/fontawesome-free-6.5.2-web/css/all.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 col-lg-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Table Outlet</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Outlet</th>
                                        <th>Alamat</th>
                                        <th>Phone</th>
                                        <th>status</th>
                                        <th>Option</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Bandung 1</td>
                                        <td>Jl. Bandung-Jakarta</td>
                                        <td>+6222 654148</td>
                                        <td><span class="badge rounded-pill badge-light-danger me-1">Non Active</span></td>
                                        <td>
                                            <a href=""><i class="fas fa-edit text-success"></i></a>
                                            <a href=""><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Bandung 2</td>
                                        <td>Jl. Bandung</td>
                                        <td>+6222 654148</td>
                                        <td><span class="badge rounded-pill badge-light-success me-1">Active</span></td>
                                        <td>
                                            <a href=""><i class="fas fa-edit text-success"></i></a>
                                            <a href=""><i class="fa fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-4 col lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Form Add / Update Outlet
                    </h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="mb-1">
                            <label class="form-label" for="nama-outlet">Nama Outlet</label>
                            <input type="text" class="form-control" id="nama-outlet" placeholder="Enter Name Outlet" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-1">
                            <label for="form-label" for="alamat-outlet">Alamat Outlet</label>
                            <textarea name="" id="alamat-outlet" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="mb-1">
                            <label class="form-label" for="phone-number">Phone Number</label>
                            <input type="text" class="form-control" id="phone-number" placeholder="Enter Phone Number" />
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary pull-right"><i class="fas fa-save"></i> Simpan / Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
@endsection
