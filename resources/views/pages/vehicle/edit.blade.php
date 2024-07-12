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
                    <form id="form-select-driver" action="{{ url('/vehicle/' . Crypt::encrypt($vehicle->id)) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="drivers_id">Deriver</label>
                                    <select name="drivers_id" id="drivers_id" class="form-control">
                                        <option value="{{ $vehicle->driver->id }}" hidden>{{ $vehicle->driver->name }}</option>
                                        @foreach ($drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="no_police">No Police</label>
                                    <input type="text" name="no_police" id="no_police" class="form-control" value="{{ $vehicle->police_no }}">
                                </div>
                            </div>
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
                                <div class="form-group mb-3">
                                    <label for="no_stnk">No STNK</label>
                                    <input type="text" name="no_stnk" id="no_stnk" class="form-control" value="{{ $vehicle->no_stnk }}">
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
                    'drivers_id': 'required',
                    'no_police' : 'required',
                    'type'      : 'required',
                    'no_stnk'   : 'required'

                },
                messages:{
                    'name'      : "Pilih salah satu.",
                    'no_police' : "No police harus diisi.",
                    'type'      : "Pilih salah satu.",
                    'no_stnk'   : "No STNK harus diisi."
                },
            })
        });
    </script>
@endsection
