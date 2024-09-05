@extends('layout.app')

@section('title')
    <span>Transaksi</span>
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
                        <a href="{{ url('/order') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet_id_select">Outlet Asal</label>
                            <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                <option value="">Pilih Outlet Asal</option>
                                @foreach ($outlets as $outlet)
                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
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
                    <h4 class="card-title">Tambah Transaksi</h4>
                    @if (Auth::user()->role_id != '1')
                        <a href="{{ url('/order') }}" class="btn btn-warning">Kembali</a>
                    @endif
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
                                        <option value="" hidden>Pilih Customer</option>
                                        @if (Auth::user()->role_id != '1')
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}" value="{{ $customer->id }}"
                                                    {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group pesanan_pengambilan">
                                    <label for="destination1_id">Destinasi</label>
                                    <select name="destination1_id" id="destination1_id" class="form-control">
                                        <option value="">Pilih Destinasi</option>
                                        @foreach ($destinations as $destination)
                                            <option value="{{ $destination->id }}"
                                                {{ old('destination1_id') == $destination->id ? 'selected' : '' }}>
                                                {{ $destination->name }}</option>
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
                                    <input type="number" name="total" id="total" class="form-control"
                                        value="{{ old('total') }}">
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
                                            <option {{ old('payment_method') == '1' ? 'selected' : '' }} value="1">
                                                Tagih Tujuan</option>
                                            <option {{ old('payment_method') == '2' ? 'selected' : '' }} value="2">
                                                Tagih Pada Pengirim</option>
                                            <option {{ old('payment_method') == '3' ? 'selected' : '' }} value="3">
                                                Tunai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="armada">Service</label>
                                        <select name="armada" id="armada" class="form-control">
                                            <option value="">Pilih Service</option>
                                            <option {{ old('armada') == '1' ? 'selected' : '' }} value="1">Darat
                                            </option>
                                            <option {{ old('armada') == '2' ? 'selected' : '' }} value="2">Laut
                                            </option>
                                            <option {{ old('armada') == '3' ? 'selected' : '' }} value="3">Udara
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiver">Penerima</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control"
                                            value="{{ old('receiver') }}" placeholder="masukan penerima">
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
                                                <option {{ old('destination_id') == $destination->id ? 'selected' : '' }}
                                                    value="{{ $destination->id }}">{{ $destination->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service">Jenis Barang</label>
                                        <select name="service" id="service" class="form-control">
                                            <option value="">Pilih Jenis</option>
                                            <option {{ old('service') == '1' ? 'selected' : '' }} value="1">Dokumen
                                            </option>
                                            <option {{ old('service') == '2' ? 'selected' : '' }} value="2">Paket
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="weight">Berat</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" id="weight" class="form-control"
                                                value="{{ old('weight') }}" placeholder="masukan berat kg">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="error-minweight"></span>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                        <textarea name="address" id="address" class="form-control" placeholder="masukan alamat lengkap">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="row">
                                            <label for="volume">Volume</label>
                                            <div class="d-flex gap-1">
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1">P</span>
                                                    <input type="text" name="panjang" id="panjang" class="form-control"
                                                    value="{{ old('panjang') }}" placeholder="panjang">
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1">L</span>
                                                    <input type="text" name="lebar" id="lebar" class="form-control"
                                                    value="{{ old('lebar') }}" placeholder="lebar">
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-text" id="basic-addon1">T</span>
                                                    <input type="text" name="tinggi" id="tinggi" class="form-control"
                                                    value="{{ old('tinggi') }}" placeholder="tinggi">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Harga</label>
                                        <input type="hidden" name="price_id" id="price_id">
                                        <input type="text" name="price" id="price" class="form-control"
                                            value="{{ old('price') }}" placeholder="masukan harga">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="koli">Koli</label>
                                        <input type="number" name="koli" id="koli" class="form-control"
                                            value="{{ old('koli') }}" placeholder="masukan koli">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimation">Estimasi</label>
                                        <div class="input-group">
                                            <input type="number" name="estimation" id="estimation" class="form-control"
                                                value="{{ old('estimation') }}" placeholder="masukan estimasi">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Deskripsi Barang</label>
                                        <textarea name="description" id="description" class="form-control" placeholder="masukan deskripsi">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" id="note" class="form-control" placeholder="masukan catatan">{{ old('note') }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-2 float-end btn-send-update">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            sendEstimationRequest()

            // Saat elemen outlet_id_select berubah
            $('#outlet_id_select').change(function() {
                var selectedValue = $(this).val();
                $('#outlet_id_hidden').val(selectedValue);
            });

            // send and get estimations
            function sendEstimationRequest() {
                var outletasal = $('#outlet_id_hidden').val()
                var customer_id = $('#customer_id').val()
                var armada = $('#armada').val()
                var destination_id = $('#destination_id').val()


                if (armada || destination_id || customer_id || outletasal) {
                    $.ajax({
                        url: '{{ url('/order/get-estimation') }}',
                        type: 'GET',
                        data: {
                            outletasal: outletasal,
                            customer_id: customer_id,
                            armada: armada,
                            destination_id: destination_id,
                        },
                        success: function(response) {
                            var pricePerKg      = parseFloat(response.data.price) / parseFloat(response.data.minweights)
                            var nextweightprice =response.data.nextweightprices
                            var minimumweight   = response.data.minweights
                            var price           = response.data.price
                            var price_id        = response.data.price_id

                            $('#price_id').val(response.data.price_id);
                            $('#price').val(response.data.price)
                            $('#estimation').val(response.data.estimation)
                            $('#weight').val(response.data.minweights)


                            $('#weight').off('keyup').on('keyup', function() {
                                var weight = parseFloat($('#weight').val()) || 0
                                if (weight < minimumweight) {
                                    $('#error-minweight').text('minimal berat ' + minimumweight + ' kg')
                                    $('#weight').addClass('border border-danger')
                                    $('#price').val(price);
                                    $('.btn-send-update').attr('type', 'button');
                                } else {
                                    $('#error-minweight').empty();
                                    $('.btn-send-update').attr('type', 'submit');
                                    totalPrice = 0
                                    if (weight > minimumweight) {
                                        if (armada == '1') {
                                            totalPrice = price + ((weight - minimumweight) * nextweightprice)
                                        }else if(armada == '2' || armada == '3'){
                                            if (pricePerKg) {
                                               totalPrice = weight * pricePerKg;
                                            }
                                        }
                                    } else {
                                        totalPrice = price;
                                    }
                                    $('#price').val(totalPrice.toFixed(0))


                                    $('#weight').removeClass('border border-danger')
                                }
                            })
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error: ', xhr.responseText)
                        }
                    });
                }
            }

            $('#armada, #destination_id, #customer_id, #outlet_id_select').change(sendEstimationRequest);


            // get customer
            $('#outlet_id_select').change(function() {
                $.ajax({
                    url: '{{ url('/order/get-customer') }}',
                    type: 'GET',
                    data: {
                        outletasal: $('#outlet_id_hidden').val()
                    },

                    success: function(response) {
                        var customers = response.customers;
                        var customerSelect = $('#customer_id')
                        customerSelect.empty();

                        customerSelect.append(
                        '<option value="" hidden>Pilih Customer</option>');

                        if (customers != null) {
                            customers.forEach(function(customer) {
                                customerSelect.append('<option value="' + customer.id +
                                    '" hidden>' + customer.name + '</option>')
                            });
                        }
                    },

                    error: function(xhr, status, error) {
                        // console.error('AJAX Error: ', xhr.responseText)
                        console.log('error');
                    }

                })
            })




            // $('.volume').hide();
            // $('#select_option_berat_volume').change(function() {
            //     var weightOrVolume = $('#select_option_berat_volume').val();
            //     if (weightOrVolume == "berat") {
            //         $('.weight').show()
            //         $('.volume').hide()
            //     } else if (weightOrVolume == "volume") {
            //         $('.weight').hide()
            //         $('.volume').show()
            //     }
            // })



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
                rules: {
                    'customer_id': {
                        required: true
                    },
                    'total': {
                        required: function(element) {
                            return $('#pesanan_masal').is(':checked')
                        }
                    },
                    'destination1_id': {
                        required: function(element) {
                            return $('#pesanan_masal').is(':checked')
                        }
                    },
                    'armada': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'service': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'destination_id': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'address': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'weight': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'volume': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'price': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'payment_method': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'estimation': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'description': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'note': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'koli': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },
                    'receiver': {
                        required: function(element) {
                            return $('#pesanan_masal')
                        }
                    },

                },
                messages: {
                    'customer_id': 'Pilih salah satu.',
                    'destination1_id': 'Pilih salah satu.',
                    'total': 'Total harus diisi.',
                    'armada': 'Pilih salah satu armada.',
                    'service': 'Pilih jenis barang.',
                    'destination_id': 'Pilih salah satu destinasi.',
                    'address': 'Alamat harus diisi.',
                    'weight': 'Berat harus diisi.',
                    'volume': 'Volume harus diisi.',
                    'price': 'Harga harus diisi.',
                    'estimation': 'Estimasi harus diisi.',
                    'description': 'Deskripsi harus diisi.',
                    'note': 'Catatan harus diisi.',
                    'koli': 'Koli harus diisi.',
                    'receiver': 'Penerima harus diisi.',
                    'payment_method': 'Pilih salah satu.'
                },
                errorPlacement: function(error, element) {
                    if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'))
                    } else if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('span.select2'))
                    } else {
                        error.insertAfter(element);
                    }
                }
            })
        });
    </script>
@endsection
