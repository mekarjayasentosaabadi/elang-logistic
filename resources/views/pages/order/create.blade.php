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
                        <a href="{{ url('/order') }}" class="btn btn-warning">
                            <li class="fa fa-undo"></li> Kembali
                        </a>
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
    <form id="form-create-transaksi" action="{{ url('/order') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Transaksi</h4>
                        @if (Auth::user()->role_id != '1')
                            <a href="{{ url('/order') }}" class="btn btn-warning">
                                <li class="fa fa-undo"></li>Kembali
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
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
                        <div class="pesanan_pengambilan">
                            <button type="submit" class="btn btn-primary mt-2 float-end btn-send-update">
                                <li class="fa fa-save"></li> Simpan
                            </button>
                        </div>
                        <div class="pesanan_normal">
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
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                        <textarea name="address" id="address" class="form-control" placeholder="masukan alamat lengkap">{{ old('address') }}</textarea>
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
                    </div>
                </div>
            </div>
        </div>
        <div class="row pesanan_normal">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Koli</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="koli-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Berat</th>
                                        <th scope="col">P</th>
                                        <th scope="col">L</th>
                                        <th scope="col">T</th>
                                        <th scope="col">Total Volume</th>
                                        <th scope="col">Berat Volume</th>
                                        <th scope="col" class="hidden">Harga</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>1</th>
                                        <td>
                                            <div class="input-group">
                                                <input style="min-width: 150px" type="number" name="weight[]" id="weight"
                                                    class="form-control weight"
                                                    placeholder="masukan berat kg">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input style="min-width: 150px" name="panjang_volume[]" type="number" id="panjang_volume"
                                                    class="form-control panjang_volume" placeholder="Panjang">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input style="min-width: 150px" name="lebar_volume[]" type="number" id="lebar_volume"
                                                    class="form-control lebar_volume" placeholder="Lebar">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input style="min-width: 150px" name="tinggi_volume[]" type="number" id="tinggi_volume"
                                                    class="form-control tinggi_volume" placeholder="Tinggi">
                                            </div>
                                        </td>
                                        <td><input name="total_volume[]" style="min-width: 150px" type="text"
                                                id="total_volume" class="form-control total_volume bg-transparent"
                                                readonly></td>
                                        <td><input name="kg_volume[]" style="min-width: 150px" type="text"
                                                id="kg_volume" class="form-control kg_volume bg-transparent" readonly>
                                        </td>
                                        <td><input name="price[]" style="min-width: 150px" type="text" id="price"
                                                class="form-control price bg-transparent hidden"></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-success mt-2" id="add-row-btn">
                            <li class="fa fa-plus"></li> Tambah Koli
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pesanan_normal">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Total</h4>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <p>Total Berat: <input class="form-control" type="text" name="total_weight" id="total-weight"></input></p>
                            <p>Total Harga: <input class="form-control" type="text" name="total_price" id="total-price"></input></p>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 float-end btn-send-update">
                            <li class="fa fa-save"></li> Simpan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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

                let minimumweight = 0;
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
                                var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data.minweights)
                                
                                var nextweightprice = response.data.nextweightprices
                                minimumweight = response.data.minweights;

                                var price = response.data.price
                                var price_id = response.data.price_id

                                $('#price_id').val(response.data.price_id);
                                // $('.price').val(response.data.price)
                                $('#total-harga').text(response.data.price);
                                $('#estimation').val(response.data.estimation)
                                // $('.weight').val(response.data.minweights)


                                $('.weight').off('keyup').on('keyup', function() {
                                   if ($('.weight').val() >  $('#kg_volume').val()) {
                                        var weight = parseFloat($('.weight').val()) || 0
                                        $('.btn-send-update').attr('type', 'submit');
                                            totalPrice = 0
                                            if (weight > minimumweight) {
                                                if (armada == '1') {
                                                    totalPrice = price + ((weight - minimumweight) *
                                                        nextweightprice)
                                                } else if (armada == '2' || armada == '3') {
                                                    if (pricePerKg) {
                                                        totalPrice = weight * pricePerKg;
                                                    }
                                                }
                                            } else {
                                                totalPrice = price;
                                            }
                                            $('.price').val(totalPrice.toFixed(0))
                                            $('#total-harga').text(totalPrice.toFixed(0));
                                            $('.weight').removeClass('border border-danger')
                                        calculatetotalsvolume()
                                    }else{
                                        calculatetotalsvolume()
                                   }
                                })
                                // calculateTotals();
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ', xhr.responseText)
                            }
                        });
                    }
                }

                $('#armada, #destination_id, #customer_id, #outlet_id_select').change(sendEstimationRequest);


                function sendEstimationRequestTableRow(rowIndex) {
                    var outletasal = $('#outlet_id_hidden').val();
                    var customer_id = $('#customer_id').val();
                    var armada = $('#armada').val();
                    var destination_id = $('#destination_id').val();

                    // console.log(rowIndex);

                    var weightField = rowIndex ? $(`#weight-${rowIndex}`) : null;

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
                                var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data.minweights);

                                var nextweightprice = response.data.nextweightprices;
                                var minimumweight = response.data.minweights;
                                var price = response.data.price;

                                $(`#price-${rowIndex}`).val(price);
                                // weightField.val(minimumweight);

                                    weightField.off('keyup').on('keyup', function() {
                                        var weight = parseFloat($(this).val()) || 0;
                                        var totalPrice = 0;

                                        if (weight > $(`#kg_volume-${rowIndex}`).val()) {
                                            $(this).removeClass('border border-danger');

                                            if (weight > minimumweight) {
                                                if (armada == '1') {
                                                    totalPrice = price + ((weight - minimumweight) *
                                                        nextweightprice);
                                                } else if (armada == '2' || armada == '3') {
                                                    totalPrice = weight * pricePerKg;
                                                }
                                            } else {
                                                totalPrice = price;
                                            }
                                            
                                            $(`#price-${rowIndex}`).val(totalPrice.toFixed(0));
                                        }
                                        calculateTotals();
                                    });
                                // calculateTotals();
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ', xhr.responseText);
                            }
                        });
                    }
                }



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


                // calculatetotals volume
                function calculatetotalsvolume() {
                    var panjang_volume = parseFloat($('#panjang_volume').val())
                    var lebar_volume = parseFloat($('#lebar_volume').val())
                    var tinggi_volume = parseFloat($('#tinggi_volume').val())

                    if (!isNaN(panjang_volume) && !isNaN(lebar_volume) && !isNaN(tinggi_volume)) {
                        var totalVolume = panjang_volume * lebar_volume * tinggi_volume
                        $('#total_volume').val(totalVolume)

                        

                        if ($('#armada').val() == "1") {
                            kgVolume = totalVolume / 4000
                            kgVolume = Math.ceil(kgVolume);

                            $('#kg_volume').val(kgVolume)

                            if (kgVolume > $('#weight').val() ) {
                                sendEstimationRequestCalculateVolume(kgVolume);
                            }
                            else{
                                sendEstimationRequestCalculateVolume($('#weight').val());
                            }
                        }

                        if ($('#armada').val() == "2") {
                            kgVolume = totalVolume / 4000
                            kgVolume = Math.ceil(kgVolume);

                            $('#kg_volume').val(kgVolume)

                            if (kgVolume > $('#weight').val() ) {
                                sendEstimationRequestCalculateVolume(kgVolume);
                            }
                            else{
                                sendEstimationRequestCalculateVolume($('#weight').val());
                            }
                        }

                        if ($('#armada').val() == "3") {
                            kgVolume = totalVolume / 5000
                            kgVolume = Math.ceil(kgVolume);

                            $('#kg_volume').val(kgVolume)

                            if (kgVolume > $('#weight').val() ) {
                                sendEstimationRequestCalculateVolume(kgVolume);
                            }
                            else{
                                sendEstimationRequestCalculateVolume($('#weight').val());
                            }
                        }

                    }
                }

                $('#panjang_volume, #lebar_volume, #tinggi_volume').on('keyup', calculatetotalsvolume)


                function calculatetotalsvolumeTableRow(rowIndex) {
                    var panjang_volume = parseFloat($(`#panjang_volume-${rowIndex}`).val()) || 0;
                    var lebar_volume = parseFloat($(`#lebar_volume-${rowIndex}`).val()) || 0;
                    var tinggi_volume = parseFloat($(`#tinggi_volume-${rowIndex}`).val()) || 0;
                    var weight = parseFloat($(`#weight-${rowIndex}`).val()) || 0;

                    if (panjang_volume > 0 && lebar_volume > 0 && tinggi_volume > 0) {
                        var totalVolume = panjang_volume * lebar_volume * tinggi_volume;
                        $(`#total_volume-${rowIndex}`).val(totalVolume);
                    

                        if ($('#armada').val() == "1") {
                            var kgVolume = Math.ceil(totalVolume / 4000);
                            $(`#kg_volume-${rowIndex}`).val(kgVolume);  

                            if (kgVolume > weight) {
                                sendEstimationRequestCalculateVolumeTableRow(kgVolume, rowIndex);
                            }else{
                                sendEstimationRequestCalculateVolumeTableRow(weight, rowIndex);
                            }

                        }

                        if ($('#armada').val() == "2") {
                            var kgVolume = Math.ceil(totalVolume / 4000);
                            $(`#kg_volume-${rowIndex}`).val(kgVolume);  

                            if (kgVolume > weight) {
                                sendEstimationRequestCalculateVolumeTableRow(kgVolume, rowIndex);
                            }else{
                                sendEstimationRequestCalculateVolumeTableRow(weight, rowIndex);
                            }

                        }


                        if ($('#armada').val() == "3") {
                            var kgVolume = Math.ceil(totalVolume / 5000);
                            $(`#kg_volume-${rowIndex}`).val(kgVolume);  

                            if (kgVolume > weight) {
                                sendEstimationRequestCalculateVolumeTableRow(kgVolume, rowIndex);
                            }else{
                                sendEstimationRequestCalculateVolumeTableRow(weight, rowIndex);
                            }

                        }
                    }
                }
                

                function handleVolumeChangeRowTable(rowIndex) {
                    $(`#panjang_volume-${rowIndex}, #lebar_volume-${rowIndex}, #tinggi_volume-${rowIndex}`).on('keyup',
                        function() {
                            calculatetotalsvolumeTableRow(rowIndex);
                            calculateTotals();
                        });
                }


            

                // next weight price volume
                function sendEstimationRequestCalculateVolume(kgVolume) {
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
                                var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data.minweights)
                                var totalPriceKg = pricePerKg * kgVolume;
                                

                                var nextweightprice = response.data.nextweightprices
                                var minimumweight = response.data.minweights
                                var price = response.data.price
                                var price_id = response.data.price_id


                                $('#price_id').val(price_id);

                                if ($('#armada').val() == 1) {
                                    totalPrice = price + ((kgVolume - minimumweight) * nextweightprice)
                                } else if ($('#armada').val() == 2) {
                                    totalPrice = totalPriceKg;
                                } else if ($('#armada').val() == 3) {
                                    totalPrice = totalPriceKg;
                                }

                                $('#price').val(totalPrice.toFixed(0))

                                $('#total-harga').text(totalPrice.toFixed(0));
                                calculateTotals();
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ', xhr.responseText)
                            }
                        });
                    }
                }


                function sendEstimationRequestCalculateVolumeTableRow(kgVolume, rowIndex) {
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
                                var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data.minweights)
                                var totalPriceKg = pricePerKg * kgVolume;

                                var nextweightprice = response.data.nextweightprices
                                var minimumweight = response.data.minweights
                                var price = response.data.price
                                var price_id = response.data.price_id


                                $(`#price_id-${rowIndex}`).val(price_id);

                                if ($('#armada').val() == 1) {
                                    totalPrice = price + ((kgVolume - minimumweight) * nextweightprice)
                                } else if ($('#armada').val() == 2) {
                                    totalPrice = totalPriceKg;
                                } else if ($('#armada').val() == 3) {
                                    totalPrice = totalPriceKg;
                                }

                                
                                $(`#price-${rowIndex}`).val(totalPrice.toFixed(0))
                                
                                $(`#total-harga-${rowIndex}`).text(totalPrice.toFixed(0));
                                calculateTotals();
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ', xhr.responseText)
                            }
                        });
                    }
                }

                
                let rowIndex = 0;
                function addRowTableKoli() {
                    rowIndex++;
                    let rowCount = $('#koli-table tbody tr').length + 1;
                    let newRow =
                        `
                    <tr>
                        <td>${rowCount}</td>
                        <td>
                            <div class="input-group">
                                <input  style="min-width: 150px" type="number" name="weight[]" id="weight-${rowIndex}"  class="form-control weight" placeholder="masukan berat kg">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input  style="min-width: 150px" name="panjang_volume[]" type="number" id="panjang_volume-${rowIndex}" class="form-control panjang_volume" placeholder="Panjang">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input  style="min-width: 150px" name="lebar_volume[]" type="number" id="lebar_volume-${rowIndex}" class="form-control lebar_volume" placeholder="Lebar">
                            </div>
                        </td>
                        <td>
                            <div class="input-group">
                                <input  style="min-width: 150px" name="tinggi_volume[]" type="number" id="tinggi_volume-${rowIndex}" class="form-control tinggi_volume" placeholder="Tinggi">
                            </div>
                        </td>
                        <td><input name="total_volume[]" style="min-width: 150px" type="text" id="total_volume-${rowIndex}" class="form-control total_volume bg-transparent" readonly></td>
                        <td><input name="kg_volume[]" style="min-width: 150px" type="text" id="kg_volume-${rowIndex}" class="form-control kg_volume bg-transparent" readonly></td>
                        <td><input name="price[]" style="min-width: 150px" type="text" id="price-${rowIndex}" class="form-control price bg-transparent hidden"></td>
                        <td><button type="button"  class="btn btn-danger remove-row"><li class="fa fa-trash"></li></button></td>
                    </tr>
                    `;
                    $('#koli-table tbody').append(newRow);

                    sendEstimationRequestTableRow(rowIndex);
                    handleVolumeChangeRowTable(rowIndex);
                }

                $(document).on('click', '#add-row-btn', function() {
                    calculateTotals();
                    addRowTableKoli();
                });


                $(document).on('click', '.remove-row', function() {
                    if ($('#koli-table tbody tr').length > 1) {
                        $(this).closest('tr').remove();
                        calculateTotals();
                    } else {
                        alert("Tidak bisa menghapus semua row.");
                    }
                });



            // calculate totals weight and price order
            function calculateTotals() {
                    let totalWeight = 0;
                    // let totalPrice = 0;

                    $('#koli-table tbody tr').each(function() {
                        let weight = parseFloat($(this).find('.weight').val()) || 0;
                        let kg_volume = parseFloat($(this).find('.kg_volume').val()) || 0;

                        let calculateWeight = 0;
                        if (weight < kg_volume) {
                            calculateWeight = kg_volume
                        }else if(weight > kg_volume){
                            calculateWeight = weight
                        }

                        totalWeight += calculateWeight;
                    });

                    $('#total-weight').val(totalWeight.toFixed(0));


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
                                var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data.minweights)
                                var nextweightprice = response.data.nextweightprices
                                minimumweight = response.data.minweights;

                                var price = response.data.price
                                var price_id = response.data.price_id
                               
                               if ($('#armada').val() || $('#destination_id').val() || $('#customer_id').val() || $('#outlet_id_hidden').val()) {
                                   totalWeight = $('#total-weight').val();

                                   if ($('#armada').val() == 1) {
                                       totalPrice = price + ((totalWeight - minimumweight) * nextweightprice)
                                   } else if ($('#armada').val() == 2) {
                                       totalPrice = totalWeight * pricePerKg;
                                   } else if ($('#armada').val() == 3) {
                                       totalPrice = totalWeight * pricePerKg;
                                   }

                                   $('#total-price').val(totalPrice.toFixed(0));
                               }
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ', xhr.responseText)
                            }
                        });
                    }
                   
            }
            calculateTotals();


            // $('.volume').hide();`
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
            $('.pesanan_normal').hide();

            $('#pesanan_masal').change(function() {
                if ($(this).is(':checked')) {
                    $('.pesanan_pengambilan').show();
                    $('.pesanan_normal').hide();
                } else {
                    $('.pesanan_pengambilan').hide();
                    $('.pesanan_normal').show();
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
                    'armada': 'Pilih salah satu service.',
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
                    } else if (element.attr("name") == "panjang" || element.attr("name") == "lebar" ||
                        element.attr("name") == "tinggi") {
                        error.insertAfter(element.closest('.input-group'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            })
        });
    </script>
@endsection
