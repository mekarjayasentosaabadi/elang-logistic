@extends('layout.app')
@section('title')
    <span>Surat Jalan</span>
    <small>/</small>
    <small>Edit</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                    <a href="{{ route('traveldocument.index') }}" class="btn btn-warning btn-sm"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="#" id="formAddSuratJalan">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="noSuratJalan">No Surat Jalan</label>
                                    <input type="text" name="noSuratJalan" id="noSuratJalan" class="form form-control" value="{{ $suratjalan->travelno }}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="vehicle">Kendaraan</label>
                                    <select name="vehicle" id="vehicle" class="form-control">
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach ($vehicle as $item)
                                            <option value="{{ $item->id }}" {{ $suratjalan->vehicle_id == $item->id ? 'selected' : '' }}>{{ $item->police_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="driver">Driver</label>
                                    <select name="driver" id="driver" class="form-control">
                                        <option value="">-- Pilih Driver --</option>
                                        @foreach ($driver as $item)
                                            <option value="{{ $item->id }}" {{ $suratjalan->driver_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <select name="destination" id="destination" class="form-control" onchange="manifestOrder(this.value)" disabled>
                                        <option value="">-- Pilih Destination --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}" {{ $suratjalan->destinations_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group  mt-1">
                                    <label for="description">Note</label>
                                    <textarea name="description" id="description" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-2">
                            <div class="table-responsive">
                                <div class="col-12">
                                    <table class="table table-hover" id="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Manifest No</th>
                                                <th>Jumlah Awb</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblListManifest">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-md" ><li class="fa fa-save"></li>Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script>
        let base = new URL(window.location.href);
        let path = base.pathname;
        let segment = path.split("/");
        let suratJalanId = segment["2"];
        var urlBase = window.location.origin;
        $(document).ready(function(){
            $.getJSON(urlBase + '/' + listRoutes['traveldocument.listDetail'].replace('{id}', suratJalanId ), function(){

            }).done(function(e){
                console.log(e);
            }).fail(function(e){
                console.log(e);
            })
        })
    </script>
@endsection
