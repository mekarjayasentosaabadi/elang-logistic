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
                        <a href="{{ url('/order') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet_id_select">Outlet Asal</label>
                            @if ($order->status_orders == 1)
                                <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                    <option value="">Pilih Outlet Asal</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ $outlet->id  == $order->outlet_id ? 'selected' : '' }} >{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <select name="outlet_id_select" id="outlet_id_select" class="form-control" disabled>
                                    <option value="">Pilih Outlet Asal</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ $outlet->id  == $order->outlet_id ? 'selected' : '' }} >{{ $outlet->name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="outlet_id_select" id="outlet_id_select"  value="{{ $order->outlet_id }}">
                            @endif

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
                    @if (Auth::user()->role_id != "1")
                        <a href="{{ url('/order') }}" class="btn btn-warning">Kembali</a>
                    @endif
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
                                    @if ($order->status_orders == 1)
                                        <select name="customer_id" id="customer_id" class="form-control">
                                            <option value="">Pilih Customer</option>
                                            @if (Auth::user()->role_id != '1')
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ $customer->id == $order->customer_id ? 'selected' : '' }}>{{ $customer->name }}
                                                        </option>
                                                    @endforeach
                                            @endif
                                        </select>
                                    @else
                                        <select name="customer_id" id="customer_id" class="form-control" disabled>
                                            <option value="">Pilih Customer</option>
                                            @if (Auth::user()->role_id != '1')
                                                    @foreach ($customers as $customer)
                                                        <option value="{{ $customer->id }}"
                                                            {{ $customer->id == $order->customer_id ? 'selected' : '' }}>{{ $customer->name }}
                                                        </option>
                                                    @endforeach
                                            @endif
                                        </select>
                                        <input type="hidden" name="customer_id" value="{{ $order->customer_id }}">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="pesanan_normal">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="awb">AWB</label>
                                        @if ($order->status_orders == 1)
                                            <input type="text" name="awb" id="awb" class="form-control"
                                                value="{{ !empty(old('awb')) ? old('awb') : $order->numberorders }}"  maxlength="10" minlength="10">
                                        @else
                                            <input type="text" name="awb" id="awb" class="form-control" value="{{$order->numberorders}}"  maxlength="10" minlength="10" readonly>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option {{ Old('payment_method', $order->payment_method) == '1' ? 'selected' : '' }} value="1">Tagih Tujuan</option>
                                            <option {{ Old('payment_method', $order->payment_method) == '2' ? 'selected' : '' }} value="2">Tagih Pada Pengirim</option>
                                            <option {{ Old('payment_method', $order->payment_method) == '3' ? 'selected' : '' }} value="3">Tunai</option>
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
                                            <option {{  Old('armada', $order->armada) == '1' ? 'selected' : '' }} value="1">Darat</option>
                                            <option {{  Old('armada', $order->armada) == '2' ? 'selected' : '' }} value="2">Laut</option>
                                            <option {{  Old('armada', $order->armada) == '3' ? 'selected' : '' }} value="3">Udara</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiver">Penerima</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control" value="{{ Old('receiver', $order->penerima) }}" placeholder="masukan penerima">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="destination_id">Destinasi</label>
                                        @if ($order->status_orders == 1)
                                            <select name="destination_id" id="destination_id" class="form-control">
                                                <option value="">Pilih Destinasi</option>
                                                @foreach ($destinations as $destination)
                                                    <option {{ Old('destination_id', $order->destinations_id ) == $destination->id ? 'selected' : '' }} value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="destination_id" id="destination_id" class="form-control" disabled readonly>
                                                <option value="">Pilih Destinasi</option>
                                                @foreach ($destinations as $destination)
                                                    <option {{ Old('destination_id', $order->destinations_id ) == $destination->id ? 'selected' : '' }} value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="destination_id" value="{{ $order->destination->id }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service">Jenis Barang</label>
                                        <select name="service" id="service" class="form-control">
                                            <option value="">Pilih Jenis</option>
                                            <option {{ Old('service', $order->service) == '1' ? 'selected' : '' }} value="1">Dokumen</option>
                                            <option {{ Old('service', $order->service) == '2' ? 'selected' : '' }} value="2">Paket</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="weight">Berat</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" id="weight" class="form-control" value="{{ Old('weight', $order->weight) }}"  placeholder="masukan berat kg">
                                            <input type="hidden" id="weight_val" value="{{$order->weight }}">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="error-minweight"></span>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                        <textarea name="address" id="address" class="form-control" placeholder="masukan alamat lengkap">{{ Old('address', $order->address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="volume">Volume</label>
                                        {{-- <div class="input-group">
                                            <input type="number" name="volume" id="volume" class="form-control" value="{{ Old('volume', $order->volume) }}">
                                            <span class="input-group-text">M<sup>3</sup></span>
                                        </div> --}}
                                        <div class="d-flex gap-1">
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">P</span>
                                                <input type="text" name="panjang" id="panjang" class="form-control"
                                                value="{{ old('panjang', $order->panjang_volume) }}" placeholder="panjang">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">L</span>
                                                <input type="text" name="lebar" id="lebar" class="form-control"
                                                value="{{ old('lebar', $order->lebar_volume) }}" placeholder="lebar">
                                            </div>
                                            <div class="input-group">
                                                <span class="input-group-text" id="basic-addon1">T</span>
                                                <input type="text" name="tinggi" id="tinggi" class="form-control"
                                                value="{{ old('tinggi', $order->tinggi_volume) }}" placeholder="tinggi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Harga</label>
                                        <input type="hidden" name="price_id" id="price_id" value="{{ Old('price_id') }}">
                                        <input type="hidden" name="price_val" id="price_val" value="{{ $order->price }}">
                                        <input type="text" name="price" id="price" class="form-control" value="{{ Old('price', $order->price) }}"  placeholder="masukan harga">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="koli">Koli</label>
                                        <input type="number" name="koli" id="koli" class="form-control" value="{{ Old('koli', $order->koli) }}" placeholder="masukan koli">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimation">Estimasi</label>
                                        <div class="input-group">
                                            <input type="number" name="estimation" id="estimation" class="form-control" value="{{ Old('estimation', $order->estimation) }}" placeholder="masukan estimasi">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Deskripsi Barang</label>
                                        <textarea name="description" id="description" class="form-control" placeholder="masukan deskripsi">{{ Old('description', $order->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" id="note" class="form-control" placeholder="masukan catatan">{{ Old('note', $order->note) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 float-end btn-send-update">Update</button>
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

            
            $('#outlet_id_select').attr('data-outlet-id', '{{ $order->outlet_id }}');
            $('#customer_id').attr('data-customer-id', '{{ $order->customer_id }}');

            var initialOutletId = $('#outlet_id_select').data('outlet-id');
            loadCustomers(initialOutletId);

            $('#outlet_id_select').change(function() {
                var selectedValue = $(this).val();
                $('#outlet_id_hidden').val(selectedValue);
                loadCustomers(selectedValue);
            });


            function loadCustomers(outletId) {
                if (!outletId) return;

                $.ajax({
                    url: '{{ url('/order/get-customer') }}',
                    type: 'GET',
                    data: {
                        outletasal: outletId
                    },
                    success: function(response) {
                        var customers = response.customers;
                        var customerSelect = $('#customer_id');
                        var initialCustomerId = customerSelect.data('customer-id');
                        customerSelect.empty();

                        customerSelect.append('<option value="">Pilih Customer</option>');

                        if (customers && Array.isArray(customers)) {
                            customers.forEach(function(customer) {
                                var selected = (customer.id == initialCustomerId) ? 'selected' : '';
                                customerSelect.append('<option value="' + customer.id + '" ' + selected + '>' + customer.name + '</option>');
                            });
                        } else {
                            console.error('Unexpected response format: customers is not an array');
                        };


                        if ($('#outlet_id_hidden').val() != '' && $('#armada').val() != '' && $('#destination_id').val() != '' && $('#customer_id').val() != '') {
                            sendEstimationRequest();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ', xhr.responseText);
                    }
                });
            }


            function sendEstimationRequest() {
                var outletasal      = $('#outlet_id_hidden').val()
                var customer_id     = $('#customer_id').val()
                var armada          = $('#armada').val()
                var destination_id  = $('#destination_id').val()


                if (armada || destination_id || customer_id || outletasal) {
                    $.ajax({
                        url: '{{ url('/order/get-estimation') }}',
                        type: 'GET',
                        data: {
                            outletasal      : outletasal,
                            customer_id     : customer_id,
                            armada          : armada,
                            destination_id  : destination_id,
                        },
                        success: function(response) {
                            var pricePerKg      = parseFloat(response.data.price) / parseFloat(response.data.minweights)
                            var nextweightprice =response.data.nextweightprices
                            var minimumweight   = response.data.minweights
                            var price           = response.data.price
                            var price_id        = response.data.price_id

                            $('#price_id').val(response.data.price_id);
                            $('#estimation').val(response.data.estimation)
                            $('#weight').val(response.data.minweights)
                            $('#price').val(response.data.price)

                            if ($('#weight').val() <=  $('#weight_val').val()) {
                                $('#weight').val($('#weight_val').val())
                            }

                            if ($('#price').val() <=  $('#price_val').val()) {
                                $('#price').val($('#price_val').val())
                            }


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


            // $('.volume').hide();
            // $('#select_option_berat_volume').change(function () {
            //     var weightOrVolume = $('#select_option_berat_volume').val();
            //     if (weightOrVolume == "berat") {
            //         $('.weight').show()
            //         $('.volume').hide()
            //     }else if(weightOrVolume == "volume"){
            //         $('.weight').hide()
            //         $('.volume').show()
            //     }
            // })

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
                    'koli'            :  'Koli harus diisi.',
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
