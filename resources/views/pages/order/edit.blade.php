@extends('layout.app')

@section('title')
    <span>Tranasksi</span>
    <small>/</small>
    <small>Edit</small>
@endsection

@section('content')
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
                                        <input type="text" name="awb" id="awb" class="form-control" readonly
                                            value="{{ $order->awb }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiver">Penerima</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control" value="{{ $order->penerima }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="armada">Armada</label>
                                        <select name="armada" id="armada" class="form-control">
                                            <option value="">Pilih Armada</option>
                                            <option {{ $order->armada == '1' ? 'selected' : '' }} value="1">Darat</option>
                                            <option {{ $order->armada == '2' ? 'selected' : '' }} value="2">Laut</option>
                                            <option {{ $order->armada == '3' ? 'selected' : '' }} value="3">Udara</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service">Jenis Barang</label>
                                        <select name="service" id="service" class="form-control">
                                            <option value="">Pilih Jenis</option>
                                            <option {{ $order->service == '1' ? 'selected' : '' }} value="1">Dokumen</option>
                                            <option {{ $order->service == '2' ? 'selected' : '' }} value="2">Paket</option>
                                        </select>
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
                                        <label for="address">Alamat</label>
                                        <textarea name="address" id="address" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="weight">Berat</label>
                                        <div class="input-group">
                                            <input type="text" name="weight" id="weight" class="form-control">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="volume">Volume</label>
                                        <div class="input-group">
                                            <input type="text" name="volume" id="volume" class="form-control">
                                            <span class="input-group-text">M<sup>3</sup></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Harga</label>
                                        <input type="text" name="price" id="price" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimation">Estimasi</label>
                                        <input type="text" name="estimation" id="estimation" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option {{ $order->payment_method == '1' ? 'selected' : '' }} value="1">Tagih Tujuan</option>
                                            <option {{ $order->payment_method == '2' ? 'selected' : '' }} value="2">Tagih Pada Pengirim</option>
                                            <option {{ $order->payment_method == '3' ? 'selected' : '' }} value="3">Tunai</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" id="note" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Deskripsi Barang</label>
                                        <textarea name="description" id="description" class="form-control"></textarea>
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
            $('#customer_id').select2();
            $('#destination_id').select2();

            $('#form-edit-transaksi').validate({
                rules:{
                    'customer_id' : {
                        required: true
                    },
                    'receiver' : {
                        required: function (element) {
                            return $('#pesanan_masal')
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
                },
                messages: {
                    'customer_id'     :  'Pilih salah satu.',
                    'receiver'        :  'Penerima harus diisi.',
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
                    'note'            :  'Catatan harus diisi.'
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
