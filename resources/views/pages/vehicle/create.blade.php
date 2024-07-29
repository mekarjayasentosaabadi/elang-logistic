@extends('layout.app')
@section('title')
    <span>Vehicle</span>
    <small>/</small>
    <small>create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Vehicle</h4>
                    <a href="{{ url('/vehicle') }}" class="btn btn-warning">Kembali</a>
                </div>
                <div class="card-body">
                    <form id="form-select-driver" action="{{ url('vehicle/store') }}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="type">Tipe</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="" hidden>Pilih tipe</option>
                                        <option {{ old('type') == '1' ? 'selected' : ''  }} value="1">Truck Container</option>
                                        <option {{ old('type') == '2' ? 'selected' : ''  }} value="2">Truck BOX</option>
                                        <option {{ old('type') == '3' ? 'selected' : ''  }} value="3">Truck Pickup</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="no_police">No Police</label>
                                    <input type="text" name="no_police" id="no_police" class="form-control" value="{{ old('no_police') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="no_stnk">No STNK</label>
                                    <input type="text" name="no_stnk" id="no_stnk" class="form-control" value="{{ old('no_stnk') }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#form-select-driver').validate({
                rules:{
                    'no_police' : 'required',
                    'type'      : 'required',
                    'no_stnk'   : 'required'

                },
                messages:{
                    'no_police' : "No police harus diisi.",
                    'type'      : "Pilih salah satu.",
                    'no_stnk'   : "No STNK harus diisi."
                },
            })
        });
    </script>
@endsection
