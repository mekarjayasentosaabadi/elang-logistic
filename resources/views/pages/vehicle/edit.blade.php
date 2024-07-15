@extends('layout.app')
@section('title')
    <span>Vehicle</span>
    <small>/</small>
    <small>Edit</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Vehicle</h4>
                </div>
                <div class="card-body">
                    <form id="form-select-driver" action="{{ url('/vehicle/' . Crypt::encrypt($vehicle->id)) }}" method="post" autocomplete="off">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="type">Tipe</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="" hidden>Pilih tipe</option>
                                        <option {{ $vehicle->type == '1' ? 'selected' : '' }} value="1">Truck Container</option>
                                        <option {{ $vehicle->type == '2' ? 'selected' : '' }} value="2">Truck BOX</option>
                                        <option {{ $vehicle->type == '3' ? 'selected' : '' }} value="3">Truck Pickup</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="no_police">No Police</label>
                                    <input type="text" name="no_police" id="no_police" class="form-control" value="{{ $vehicle->police_no }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="no_stnk">No STNK</label>
                                    <input type="text" name="no_stnk" id="no_stnk" class="form-control" value="{{ $vehicle->no_stnk }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="status_id">Ubah Status</label>
                                    <select name="status_id" id="status_id" class="form-control">
                                        <option value="" hidden>Pilih Status</option>
                                        <option value="1" {{ $vehicle->is_active == '1' ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $vehicle->is_active == '0' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
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
                    'no_stnk'   : 'required',
                    'status_id' : 'required'
                },
                messages:{
                    'no_police' : "No police harus diisi.",
                    'type'      : "Pilih salah satu.",
                    'no_stnk'   : "No STNK harus diisi.",
                    'status_id' : "Pilih salah satu."
                },
            })
        });
    </script>
@endsection
