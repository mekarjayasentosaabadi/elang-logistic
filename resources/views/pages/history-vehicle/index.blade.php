@extends('layout.app')
@section('title')
    <span>Outlet</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Status Kendaraan</h4>
                </div>
                <div class="card-body">
                    @if ($surattugas)
                        <form action="{{ url('/update-vehicle') }}" method="POST" id="formsurattugas">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-sm-12 mb-1">
                                    <div class="form-group">
                                        <label for="vehicle_id">Kendaraan</label>
                                        <input type="text" class="form-control" name="vehicle_id" id="vehicle_id"
                                            value="{{ $surattugas->vehicle->police_no }}" readonly>
                                    </div>

                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mb-1">
                                    <div class="form-group">
                                        <label for="noResi">Status</label>
                                        <select name="status_resi" id="satus" class="form-control">
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1">Tiba/Transit di</option>
                                            <option value="2">Di Berangkatkan</option>
                                            <option value="3">Di Turunkan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group ">
                                        <label for="outlet_id">Outlet</label>
                                        <select name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="">-- Pilih Outlet --</option>
                                            @foreach ($outlet as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12 mt-2">
                                    <div class="form-group ">
                                        <label for="manifest_id">Manifest</label>
                                        <select name="manifest_id[]" id="manifest_id" class="form-control" multiple>
                                            <option value="">-- Pilih Manifest --</option>
                                            <option value="all">Semua</option>
                                            @foreach ($manifest as $item)
                                                <option value="{{ $item->id }}">{{ $item->manifestno }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mt-2 float-end">
                                        <li class="fa fa-save"></li> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-danger" role="alert">
                            Anda belum memiliki surat tugas
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('#manifest_id').select2();
            $('#formsurattugas').validate({
                rules: {
                    satus: {
                        required: true
                    },
                    outlet_id: {
                        required: true
                    }
                },
                messages: {
                    satus: {
                        required: 'Status harus diisi'
                    },
                    outlet_id: {
                        required: 'Outlet harus diisi'
                    }
                },
            })
        })
    </script>
@endsection
