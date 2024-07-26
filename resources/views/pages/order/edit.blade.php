@extends('layout.app')

@section('title')
    <span>Tranasksi</span>
    <small>/</small>
    <small>Edit</small>
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
                                <option value="{{ $outlet->id }}" {{ $outlet->id  == $order->outlet_id ? 'selected' : '' }} >{{ $outlet->name }}</option>
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
                    <h4 class="card-title">Update Tranasksi</h4>
                </div>
                <div class="card-body">
                    <form id="form-edit-transaksi" action="{{ url('/order/' . Crypt::encrypt($order->id)) }}" method="post">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="outlet_id" id="outlet_id_hidden" value="{{ $order->outlet_id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer_id">Pengirim</label>
                                    <select name="customer_id" id="customer_id" class="form-control">
                                        <option value="">Pilih Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="pesanan_normal">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="awb">AWB</label>
                                        <input type="text" name="awb" id="awb" class="form-control"
                                            value="{{ !empty(old('awb')) ? old('awb') : $order->numberorders }}"  maxlength="10" minlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option {{ Old('payment_method') == '1' ? 'selected' : '' }} value="1">Tagih Tujuan</option>
                                            <option {{ Old('payment_method') == '2' ? 'selected' : '' }} value="2">Tagih Pada Pengirim</option>
                                            <option {{ Old('payment_method') == '3' ? 'selected' : '' }} value="3">Tunai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="armada">Servcie</label>
                                        <select name="armada" id="armada" class="form-control">
                                            <option value="">Pilih Servcie</option>
                                            <option {{  Old('armada') == '1' ? 'selected' : '' }} value="1">Darat</option>
                                            <option {{  Old('armada') == '2' ? 'selected' : '' }} value="2">Laut</option>
                                            <option {{  Old('armada') == '3' ? 'selected' : '' }} value="3">Udara</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiver">Penerima</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control" value="{{ Old('receiver') }}">
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
                                                <option {{ $order->destination->name == $destination->name ? 'selected' : '' }} value="{{ $destination->id }}">{{ $destination->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service">Jenis Barang</label>
                                        <select name="service" id="service" class="form-control">
                                            <option value="">Pilih Jenis</option>
                                            <option {{ Old('service') == '1' ? 'selected' : '' }} value="1">Dokumen</option>
                                            <option {{ Old('service') == '2' ? 'selected' : '' }} value="2">Paket</option>
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
                                        <textarea name="address" id="address" class="form-control">{{ Old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6 weight">
                                    <div class="form-group ">
                                        <label for="weight">Berat</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" id="weight" class="form-control" value="{{ Old('weight') }}">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 volume">
                                    <div class="form-group">
                                        <label for="volume">Volume</label>
                                        <div class="input-group">
                                            <input type="number" name="volume" id="volume" class="form-control" value="{{ Old('volume') }}">
                                            <span class="input-group-text">M<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Harga</label>
                                        <input type="text" name="price" id="price" class="form-control" value="{{ Old('price') }}">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="koli">Koli</label>
                                        <input type="number" name="koli" id="koli" class="form-control" value="{{ Old('koli') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimation">Estimasi</label>
                                        <div class="input-group">
                                            <input type="number" name="estimation" id="estimation" class="form-control" value="{{ Old('estimation') }}">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Deskripsi Barang</label>
                                        <textarea name="description" id="description" class="form-control">{{ Old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" id="note" class="form-control">{{ Old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 float-end">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {

            $('#outlet_id_select').change(function() {
                var selectedValue = $(this).val();
                $('#outlet_id_hidden').val(selectedValue);
                console.log($('#outlet_id_hidden').val())  ;
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
            $('#destination_id').select2();

            $('#form-edit-transaksi').validate({
                rules:{
                    'customer_id' : {
                        required: true
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
                    'payment_method' : {
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
                    'armada'          :  'Pilih salah satu armada.',
                    'service'         :  'Pilih jenis barang.',
                    'destination_id'  :  'Pilih salah satu destinasi.',
                    'address'         :  'Alamat harus diisi.',
                    'weight'          :  'Berat harus diisi.',
                    'volume'          :  'Volume harus diisi.',
                    'price'           :  'Harga harus diisi.',
                    'payment_method'  :  'Pilih salah satu.',
                    'estimation'      :  'Estimasi harus diisi.',
                    'description'     :  'Deskripsi harus diisi.',
                    'note'            :  'Catatan harus diisi.',
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
