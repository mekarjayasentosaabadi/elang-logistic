@extends('layout.app')
@section('title')
    <span>Harga Public</span>
    <small>/</small>
    <small>Create</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Add Harga Public</h4>
                    <a href="{{ route('masterprice.index') }}" class="btn btn-warning"><i class="fa fa-undo"></i> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="#" id="form-add-price">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="outlet">Asal Outlet</label>
                                    <select name="outlet" id="outlet" class="form-control">
                                        <option value="">-- Pilih Outlet --</option>
                                        @foreach ($outlet as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="armada">Service</label>
                                    <select name="armada" id="armada" class="form-control">
                                        <option value="">-- Pilih Armada --</option>
                                        <option value="1">Darat</option>
                                        <option value="2">Laut</option>
                                        <option value="3">Udara</option>
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="destination">Destination</label>
                                    <select name="destination" id="destination" class="form-control select2">
                                        <option value="">-- Pilih Outlet --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="price">Price</label>
                                    <input type="text" name="price" id="price" class="form-control"
                                        placeholder="10000">
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="minweight">Berat Minimal</label>
                                            <div class="input-group">
                                                <input type="text" name="minweight" id="minweight"
                                                    class="form form-control">
                                                <span class="input-group-text">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="minimumprice">Minimum Price</label>
                                    <input type="text" name="minimumprice" id="minimumprice" class="form-control"
                                        placeholder="20000">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="estimation">Estimation</label>
                                    <input type="text" name="estimation" id="estimation" class="form-control"
                                        placeholder="1">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="pricenext">Next Weight Price</label>
                                    <input type="text" name="pricenext" id="pricenext" class="form-control"
                                        placeholder="2000">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <button class="btn btn-primary btn-md"><i class="fa fa-save"></i> Simpan</button>
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
    <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        $('.select2').select2()
        $('#form-add-price').validate({
            rules: {
                'outlet': 'required',
                'armada': 'required',
                'destination': 'required',
                'price': 'required',
                'minweight': 'required',
                'minimumprice': 'required',
                'estimation': 'required',
                'pricenext': 'required'
            },
            submitHandler: function() {
                $.ajax({
                    url: window.location.origin + '/' + listRoutes['masterprice.store'],
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-add-price')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e) {
                        if (e.data.validate == false) {
                            notifSweetAlertErrors(e.meta.message);
                        } else {
                            notifSweetAlertSuccess(e.meta.message);
                            setTimeout(function() {
                                location.replace(window.location.origin + '/masterprice')
                            }, 1500)
                        }
                    },
                    error: function(e) {
                        notifSweetAlertErrors(e.meta.message);
                    }
                })
            }
        })
    </script>
@endsection
