@extends('layout.app')

@section('title')
    <span>Tranasksi</span>
    <small>/</small>
    <small>Create</small>
@endsection

@section('content')

    @if (Auth::user()->role_id == '1')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Outlet Asal</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet_id_select">Outlet Asal</label>
                            <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                <option value="">Pilih Outlet Asal</option>
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
                    <h4 class="card-title">Tambah Tranasksi</h4>
                </div>
                <div class="card-body">
                    <form id="form-create-transaksi" action="{{ url('/order') }}" method="post">
                        @csrf
                        <input type="hidden" name="outlet_id" id="outlet_id_hidden">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="customer_id">Pengirim</label>
                                    <select name="customer_id" id="customer_id" class="form-control">
                                        <option value="">Pilih Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}" value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group pesanan_pengambilan">
                                    <label for="destination1_id">Destinasi</label>
                                    <select name="destination1_id" id="destination1_id" class="form-control">
                                        <option value="">Pilih Destinasi</option>
                                        @foreach ($destinations as $destination)
                                            <option value="{{ $destination->id }}" value="{{ $customer->id }}" {{ old('destination1_id') == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="checkbox" id="pesanan_masal" name="pesanan_masal"
                                        value="checked" checked />
                                    <label class="form-check-label" for="pesanan_masal">Buat Pesanan untuk pengambilan
                                        barang</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 pesanan_pengambilan" id="">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="total">Total Pesanan</label>
                                    <input type="number" name="total" id="total" class="form-control" value="{{ old('total') }}">
                                </div>
                            </div>
                        </div>
                        <div id="pesanan_normal">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="awb">AWB</label>
                                        <input type="text" name="awb" id="awb" class="form-control"
                                            value="{{ generateAwb() }}" maxlength="10" minlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option {{ old('payment_method') == '1' ? 'selected' : '' }} value="1">Tagih Tujuan</option>
                                            <option {{ old('payment_method') == '2' ? 'selected' : '' }} value="2">Tagih Pada Pengirim</option>
                                            <option {{ old('payment_method') == '3' ? 'selected' : '' }} value="3">Tunai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="armada">Service</label>
                                        <select name="armada" id="armada" class="form-control">
                                            <option  value="">Pilih Service</option>
                                            <option {{ old('armada') == '1' ? 'selected' : '' }} value="1">Darat</option>
                                            <option {{ old('armada') == '2' ? 'selected' : '' }} value="2">Laut</option>
                                            <option {{ old('armada') == '3' ? 'selected' : '' }} value="3">Udara</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiver">Penerima</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control" value="{{ old('receiver') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="destination_id">Destinasi</label>
                                        <select name="destination_id" id="destination_id" class="form-control">
                                            <option value="">Pilih Destinasi</option>
                                            @foreach ($destinations as $destination)
                                                <option {{ old('destination_id') == $destination->id ? 'selected' : '' }} value="{{ $destination->id }}">{{ $destination->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service">Jenis Barang</label>
                                        <select name="service" id="service" class="form-control">
                                            <option value="">Pilih Jenis</option>
                                            <option {{ old('service') == '1' ? 'selected' : '' }} value="1">Dokumen</option>
                                            <option {{ old('service') == '2' ? 'selected' : '' }} value="2">Paket</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="select_option_berat_volume">Berat / Volume</label>
                                        <select name="select_option_berat_volume" id="select_option_berat_volume" class="form-control">
                                            <option value="berat">Berat</option>
                                            <option value="volume">Volume</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                        <textarea name="address" id="address" class="form-control">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 weight">
                                    <div class="form-group ">
                                        <label for="weight">Berat</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" id="weight" class="form-control" value="{{ old('weight') }}">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 volume">
                                    <div class="form-group">
                                        <label for="volume">Volume</label>
                                        <div class="input-group">
                                            <input type="number" name="volume" id="volume" class="form-control" value="{{ old('volume') }}">
                                            <span class="input-group-text">M<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Harga</label>
                                        <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="koli">Koli</label>
                                        <input type="number" name="koli" id="koli" class="form-control" value="{{ old('koli') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimation">Estimasi</label>
                                        <div class="input-group">
                                            <input type="number" name="estimation" id="estimation" class="form-control" value="{{ old('estimation') }}">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Deskripsi Barang</label>
                                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" id="note" class="form-control">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-2 float-end">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {

            function sendEstimationRequest() {
                var customer_id     = $('#customer_id').val();
                var armada          = $('#armada').val();
                var awb             = $('#awb').val();
                var destination_id  = $('#destination_id').val();

                if (armada && destination_id) {
                    $.ajax({
                        url: '{{ url('/order/get-estimation') }}',
                        type: 'GET',
                        data: {
                            customer_id     : customer_id,
                            awb             : awb,
                            armada          : armada,
                            destination_id  : destination_id,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                console.log(response.data.estimation);
                                $('#estimation').val(response.data.estimation);
                            } else {
                                console.error('Error: ', response);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ', xhr.responseText);
                        }
                    });
                }
            }

            $('#armada, #destination_id').change(sendEstimationRequest);

            // Saat elemen outlet_id_select berubah
            $('#outlet_id_select').change(function() {
                var selectedValue = $(this).val();
                $('#outlet_id_hidden').val(selectedValue);
            });

            $('.volume').hide();
            $('#select_option_berat_volume').change(function () {
                var weightOrVolume = $('#select_option_berat_volume').val();
                if (weightOrVolume == "berat") {
                    $('.weight').show()
                    $('.volume').hide()
                }else if(weightOrVolume == "volume"){
                    $('.weight').hide()
                    $('.volume').show()
                }
            })



            $('#customer_id').select2();
            $('#destination1_id').select2();
            $('#destination_id').select2();
            $('#pesanan_normal').hide();

            $('#pesanan_masal').change(function() {
                if ($(this).is(':checked')) {
                    $('.pesanan_pengambilan').show();
                    $('#pesanan_normal').hide();
                } else {
                    $('.pesanan_pengambilan').hide();
                    $('#pesanan_normal').show();
                }
            });

            $('#form-create-transaksi').validate({
                rules:{
                    'customer_id' : {
                        required: true
                    },
                    'total' : {
                        required: function (element) {
                            return $('#pesanan_masal').is(':checked')
                        }
                    },
                    'destination1_id' : {
                        required: function (element) {
                            return $('#pesanan_masal').is(':checked')
                        }
                    },
                    'armada' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'service' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'destination_id' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'address' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'weight' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'volume' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'price' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'estimation' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'description' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'note' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'koli' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'receiver' : {
                        required: function (element) {
                            return $('#pesanan_masal')
                        }
                    },
                },
                messages: {
                    'customer_id'     :  'Pilih salah satu.',
                    'destination1_id' :  'Pilih salah satu.',
                    'total'           :  'Total harus diisi.',
                    'armada'          :  'Pilih salah satu armada.',
                    'service'         :  'Pilih jenis barang.',
                    'destination_id'  :  'Pilih salah satu destinasi.',
                    'address'         :  'Alamat harus diisi.',
                    'weight'          :  'Berat harus diisi.',
                    'volume'          :  'Volume harus diisi.',
                    'price'           :  'Harga harus diisi.',
                    'estimation'      :  'Estimasi harus diisi.',
                    'koli'            :  'Koli harus diisi.',
                    'receiver'        :  'Penerima harus diisi.'
                },
                errorPlacement:function (error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'))
                    }else if (element.hasClass('select2-hidden-accessible')){
                        error.insertAfter(element.next('span.select2'))
                    }else{
                        error.insertAfter(element);
                    }
                }
            })
        });
    </script>
@endsection
