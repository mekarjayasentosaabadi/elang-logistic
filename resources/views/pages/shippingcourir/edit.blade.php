@extends('layout.app')
@section('title')
    <span>Shipping Courir</span>
    <small>/</small>
    <small>edit</small>
@endsection

@section('content')
    @if (Auth::user()->role_id == '1')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Outlet</h4>
                        <a href="{{ url('/shipping-courir') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet_id_select">Outlet</label>
                            <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                <option value="">Pilih Outlet</option>
                                @foreach ($outlets as $outlet)
                                    <option  value="{{ $outlet->id }}" {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Pengiriman</h4>
                    @if (Auth::user()->role_id != '1')
                        <a href="{{ url('/shipping-courir') }}" class="btn btn-warning">Kembali</a>
                    @endif
                </div>
                <div class="card-body">
                    <form id="form-edit-shipping" action="{{ url('shipping-courier/update') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="courier">Kurir</label>
                                    <select name="courier" id="courier" class="form-control">
                                        <option value="" hidden>Pilih Kurir</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="penerima">Penerima</label>
                                    <input type="text" name="penerima" id="penerima" class="form-control" placeholder="Masukan nama penerima">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="jml_barang">Jumlah Barang Kiriman</label>
                                    <input type="number" name="jml_barang" id="jml_barang" class="form-control" placeholder="masukan jumlah barang yang akan dikirim">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="address">Alamat Tujuan</label>
                                    <textarea name="address" id="address" class="form-control" placeholder="masukan alamat lengkap">{{ old('address') }}</textarea>
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

            $('#form-edit-shipping').validate({
                rules:{
                    'courier'       : 'required',
                    'penerima'      : 'required',
                    'jml_barang'    : 'required',
                    'address'       : 'required',
                },
                messages:{
                    'courier'       : "Pilih salah satu courier.",
                    'penerima'      : "Penerima harus diisi.",
                    'jml_barang'    : "Jumlah barang kiriman harus diisi",
                    'address'       : "Alamat harus diisi."
                },
            })
        });
    </script>
@endsection
