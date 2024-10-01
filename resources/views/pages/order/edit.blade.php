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
                        <a href="{{ url('/order') }}" class="btn btn-warning">
                            <li class="fa fa-undo"></li>Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet_id_select">Outlet Asal</label>
                            @if ($order->status_orders == 1)
                                <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                    <option value="">Pilih Outlet Asal</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}"
                                            {{ $outlet->id == $order->outlet_id ? 'selected' : '' }}>{{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <select name="outlet_id_select" id="outlet_id_select" class="form-control" disabled>
                                    <option value="">Pilih Outlet Asal</option>
                                    @foreach ($outlets as $outlet)
                                        <option value="{{ $outlet->id }}"
                                            {{ $outlet->id == $order->outlet_id ? 'selected' : '' }}>{{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="outlet_id_select" id="outlet_id_select"
                                    value="{{ $order->outlet_id }}">
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <form id="form-edit-transaksi" action="{{ url('/order/' . Crypt::encrypt($order->id)) }}" method="post">
        <input type="hidden" id="status_orders" name="status_orders" value="{{ $order->status_orders }}">
        <input type="hidden" id="price_orders" name="price_orders" value="{{ $order->price }}">
        <input type="hidden" id="estimation_orders" name="estimation_orders" value="{{ $order->estimation }}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Tranasksi</h4>
                        @if (Auth::user()->role_id != '1')
                            <a href="{{ url('/order') }}" class="btn btn-warning">
                                <li class="fa fa-undo"></li>Kembali
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
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
                                                        {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
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
                                                        {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                                                        {{ $customer->name }}
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
                                                value="{{ !empty(old('awb')) ? old('awb') : $order->numberorders }}"
                                                maxlength="10" minlength="10">
                                        @else
                                            <input type="text" name="awb" id="awb" class="form-control"
                                                value="{{ $order->numberorders }}" maxlength="10" minlength="10" readonly>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment_method">Metode Pembayaran</label>
                                        <select name="payment_method" id="payment_method" class="form-control">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option
                                                {{ Old('payment_method', $order->payment_method) == '1' ? 'selected' : '' }}
                                                value="1">Tagih Tujuan</option>
                                            <option
                                                {{ Old('payment_method', $order->payment_method) == '2' ? 'selected' : '' }}
                                                value="2">Tagih Pada Pengirim</option>
                                            <option
                                                {{ Old('payment_method', $order->payment_method) == '3' ? 'selected' : '' }}
                                                value="3">Tunai</option>
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
                                            <option {{ Old('armada', $order->armada) == '1' ? 'selected' : '' }}
                                                value="1">Darat</option>
                                            <option {{ Old('armada', $order->armada) == '2' ? 'selected' : '' }}
                                                value="2">Laut</option>
                                            <option {{ Old('armada', $order->armada) == '3' ? 'selected' : '' }}
                                                value="3">Udara</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="receiver">Penerima</label>
                                        <input type="text" name="receiver" id="receiver" class="form-control"
                                            value="{{ Old('receiver', $order->penerima) }}"
                                            placeholder="masukan penerima">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pengambilan_id">Pengambilan</label>
                                        @if ($order->status_orders == 1)
                                            <select name="pengambilan_id" id="pengambilan_id" class="form-control">
                                                <option value="">Pilih Pengambilan</option>
                                                @foreach ($destinations as $destination)
                                                    <option
                                                        {{ Old('pengambilan_id', $order->pengambilan_id) == $destination->id ? 'selected' : '' }}
                                                        value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="pengambilan_id" id="pengambilan_id" class="form-control"
                                                disabled readonly>
                                                <option value="">Pilih Pengambilan</option>
                                                @foreach ($destinations as $destination)
                                                    <option
                                                        {{ Old('pengambilan_id', $order->pengambilan_id) == $destination->id ? 'selected' : '' }}
                                                        value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="pengambilan_id"
                                                value="{{ $order->pengambilan->id }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="service">Jenis Barang</label>
                                        <select name="service" id="service" class="form-control">
                                            <option value="">Pilih Jenis</option>
                                            <option {{ Old('service', $order->service) == '1' ? 'selected' : '' }}
                                                value="1">Dokumen</option>
                                            <option {{ Old('service', $order->service) == '2' ? 'selected' : '' }}
                                                value="2">Paket</option>
                                        </select>
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
                                                    <option
                                                        {{ Old('destination_id', $order->destinations_id) == $destination->id ? 'selected' : '' }}
                                                        value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="destination_id" id="destination_id" class="form-control"
                                                disabled readonly>
                                                <option value="">Pilih Destinasi</option>
                                                @foreach ($destinations as $destination)
                                                    <option
                                                        {{ Old('destination_id', $order->destinations_id) == $destination->id ? 'selected' : '' }}
                                                        value="{{ $destination->id }}">{{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="destination_id"
                                                value="{{ $order->destination->id }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="estimation">Estimasi</label>
                                        <div class="input-group">
                                            <input type="number" name="estimation" id="estimation" class="form-control"
                                                value="{{ Old('estimation', $order->estimation) }}"
                                                placeholder="masukan estimasi">
                                            <span class="input-group-text">Hari</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                {{-- <div class="col-md-6">
                                    <div class="form-group ">
                                        <label for="weight">Berat</label>
                                        <div class="input-group">
                                            <input type="number" name="weight" id="weight" class="form-control" value="{{ Old('weight', $order->weight) }}"  placeholder="masukan berat kg">
                                            <input type="hidden" id="weight_val" value="{{$order->weight }}">
                                            <span class="input-group-text">Kg</span>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="error-minweight"></span>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Alamat</label>
                                        <textarea name="address" id="address" class="form-control" placeholder="masukan alamat lengkap">{{ Old('address', $order->address) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="note">Catatan</label>
                                        <textarea name="note" id="note" class="form-control" placeholder="masukan catatan">{{ Old('note', $order->note) }}</textarea>
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
                            </div>
                        </div>
                        {{-- <button type="submit" class="btn btn-primary mt-2 float-end btn-send-update"><li class="fa fa-save"></li> Update</button> --}}
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
                                    @if ($detailorders->count() > 0)
                                        @foreach ($detailorders as $index => $detailorder)
                                            <tr>
                                                <th>{{ $index + 1 }}</th>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="hidden" name="detail_order_id[]"
                                                            value="{{ $detailorder->id }}">
                                                        <input style="min-width: 150px"
                                                            value="{{ $detailorder->weight }}" type="number"
                                                            name="weight[]" id="weight" class="form-control weight"
                                                            placeholder="masukan berat kg">
                                                        {{-- <span class="text-danger error-minweight"></span> --}}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input style="min-width: 150px"
                                                            value="{{ $detailorder->panjang }}" name="panjang_volume[]"
                                                            type="number" id="panjang_volume"
                                                            class="form-control panjang_volume" placeholder="Panjang">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input style="min-width: 150px" value="{{ $detailorder->lebar }}"
                                                            name="lebar_volume[]" type="number" id="lebar_volume"
                                                            class="form-control lebar_volume" placeholder="Lebar">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input style="min-width: 150px"
                                                            value="{{ $detailorder->tinggi }}" name="tinggi_volume[]"
                                                            type="number" id="tinggi_volume"
                                                            class="form-control tinggi_volume" placeholder="Tinggi">
                                                    </div>
                                                </td>
                                                <td><input value="{{ $detailorder->total_volume }}" name="total_volume[]"
                                                        style="min-width: 150px" type="text" id="total_volume"
                                                        class="form-control total_volume bg-transparent" readonly></td>
                                                <td><input value="{{ $detailorder->berat_volume }}" name="kg_volume[]"
                                                        style="min-width: 150px" type="text" id="kg_volume"
                                                        class="form-control kg_volume bg-transparent" readonly>
                                                </td>
                                                <td class="hidden"><input value="{{ $detailorder->harga }}"
                                                        name="price[]" style="min-width: 150px" type="text"
                                                        id="price" class="form-control price bg-transparent" readonly>
                                                </td>
                                                <td>
                                                    @if ($index > 0)
                                                        <button type="button" class="btn btn-danger remove-data-koli"
                                                            data-id="{{ encrypt($detailorder->id) }}">
                                                            <li class="fa fa-trash"></li>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <th>1</th>
                                            <td>
                                                <div class="input-group">
                                                    <input type="hidden" name="detail_order_id[]">
                                                    <input style="min-width: 150px" type="number" name="weight[]"
                                                        id="weight" class="form-control weight"
                                                        placeholder="masukan berat kg">
                                                    <span class="text-danger error-minweight"></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input style="min-width: 150px" name="panjang_volume[]"
                                                        type="number" id="panjang_volume"
                                                        class="form-control panjang_volume" placeholder="Panjang">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input style="min-width: 150px" name="lebar_volume[]" type="number"
                                                        id="lebar_volume" class="form-control lebar_volume"
                                                        placeholder="Lebar">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input style="min-width: 150px" name="tinggi_volume[]" type="number"
                                                        id="tinggi_volume" class="form-control tinggi_volume"
                                                        placeholder="Tinggi">
                                                </div>
                                            </td>
                                            <td><input name="total_volume[]" style="min-width: 150px" type="text"
                                                    id="total_volume" class="form-control total_volume bg-transparent"
                                                    readonly></td>
                                            <td><input name="kg_volume[]" style="min-width: 150px" type="text"
                                                    id="kg_volume" class="form-control kg_volume bg-transparent" readonly>
                                            </td>
                                            <td class="hidden"><input name="price[]" style="min-width: 150px"
                                                    type="text" id="price"class="form-control price"></td>
                                            <td></td>
                                        </tr>
                                    @endif
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
                            <p>Total Berat: <input class="form-control" type="text" name="total_weight"
                                    id="total-weight"></input></p>
                            <p>Total Harga: <input class="form-control" type="text" name="total_price"
                                    id="total-price"></input></p>
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
            if ($('#status_orders').val() == 1) {
                sendEstimationRequest();
            } else if ($('#status_orders').val() == 2 || $('#status_orders').val() == 3) {
                sendEstimationRequest();
                calculateTotals()
            }


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
                                var selected = (customer.id == initialCustomerId) ? 'selected' :
                                    '';
                                customerSelect.append('<option value="' + customer.id + '" ' +
                                    selected + '>' + customer.name + '</option>');
                            });
                        } else {
                            console.error('Unexpected response format: customers is not an array');
                        };


                        // if ($('#outlet_id_hidden').val() != '' && $('#armada').val() != '' && $('#destination_id').val() != '' && $('#customer_id').val() != '') {
                        //     sendEstimationRequest();
                        // }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ', xhr.responseText);
                    }
                });
            }


            // send and get estimations
            function sendEstimationRequest() {
                var outletasal = $('#outlet_id_hidden').val()
                var customer_id = $('#customer_id').val()
                var armada = $('#armada').val()
                var pengambilan_id = $('#pengambilan_id').val()
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
                            pengambilan_id: pengambilan_id
                        },

                        success: function(response) {
                            var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data
                                .minweights)

                            var nextweightprice = response.data.nextweightprices
                            minimumweight = response.data.minweights;

                            var price = response.data.price
                            var price_id = response.data.price_id

                            $('#price_id').val(response.data.price_id);
                            // $('.price').val(response.data.price)
                            $('#total-harga').text(response.data.price);
                            $('#estimation').val(response.data.estimation)
                            // $('.weight').val(response.data.minweights)

                            // price dan estimasi jika destinasi dan armada dan origin dan outlet tidak ada pada master price
                            $('#total-price').val($('#price_orders').val());
                            $('#estimation').val($('#estimation_orders').val());
                            

                            $('.weight').off('keyup').on('keyup', function() {
                                if ($('.weight').val() > $('#kg_volume').val()) {
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
                                } else {
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

            $('#armada, #destination_id, #customer_id, #outlet_id_select, #pengambilan_id').change(function() {
                sendEstimationRequest()
                calculateTotals()
            })


            function sendEstimationRequestTableRow(rowIndex) {
                var outletasal = $('#outlet_id_hidden').val();
                var customer_id = $('#customer_id').val();
                var armada = $('#armada').val();
                var pengambilan_id = $('#pengambilan_id').val()
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
                            pengambilan_id: pengambilan_id
                        },
                        success: function(response) {
                            var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data
                                .minweights);

                            var nextweightprice = response.data.nextweightprices;
                            var minimumweight = response.data.minweights;
                            var price = response.data.price;

                            // $(`#price-${rowIndex}`).val(price);
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

                        if (kgVolume > $('#weight').val()) {
                            sendEstimationRequestCalculateVolume(kgVolume);
                        } else {
                            sendEstimationRequestCalculateVolume($('#weight').val());
                        }
                    }

                    if ($('#armada').val() == "2") {
                        kgVolume = totalVolume / 4000
                        kgVolume = Math.ceil(kgVolume);

                        $('#kg_volume').val(kgVolume)

                        if (kgVolume > $('#weight').val()) {
                            sendEstimationRequestCalculateVolume(kgVolume);
                        } else {
                            sendEstimationRequestCalculateVolume($('#weight').val());
                        }
                    }

                    if ($('#armada').val() == "3") {
                        kgVolume = totalVolume / 5000
                        kgVolume = Math.ceil(kgVolume);

                        $('#kg_volume').val(kgVolume)

                        if (kgVolume > $('#weight').val()) {
                            sendEstimationRequestCalculateVolume(kgVolume);
                        } else {
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
                        } else {
                            sendEstimationRequestCalculateVolumeTableRow(weight, rowIndex);
                        }

                    }

                    if ($('#armada').val() == "2") {
                        var kgVolume = Math.ceil(totalVolume / 4000);
                        $(`#kg_volume-${rowIndex}`).val(kgVolume);

                        if (kgVolume > weight) {
                            sendEstimationRequestCalculateVolumeTableRow(kgVolume, rowIndex);
                        } else {
                            sendEstimationRequestCalculateVolumeTableRow(weight, rowIndex);
                        }

                    }


                    if ($('#armada').val() == "3") {
                        var kgVolume = Math.ceil(totalVolume / 5000);
                        $(`#kg_volume-${rowIndex}`).val(kgVolume);

                        if (kgVolume > weight) {
                            sendEstimationRequestCalculateVolumeTableRow(kgVolume, rowIndex);
                        } else {
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
                var pengambilan_id = $('#pengambilan_id').val()
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
                            pengambilan_id: pengambilan_id
                        },
                        success: function(response) {
                            var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data
                                .minweights)
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
                var pengambilan_id = $('#pengambilan_id').val()
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
                            pengambilan_id: pengambilan_id
                        },
                        success: function(response) {
                            var pricePerKg = parseFloat(response.data.price) / parseFloat(response.data
                                .minweights)
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
                    <td class="hidden"><input name="price[]" style="min-width: 150px" type="text" id="price-${rowIndex}" class="form-control price bg-transparent"></td>
                    <td><button type="button"  class="btn btn-danger remove-row"><li class="fa fa-trash"></li></button></td>
                </tr>
                `;
                $('#koli-table tbody').append(newRow);

                sendEstimationRequestTableRow(rowIndex);
                handleVolumeChangeRowTable(rowIndex);
            }

            $(document).on('click', '#add-row-btn', function() {
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

                let total_temp_weight = 0;
                let total_temp_kg_volume = 0;
                $('#koli-table tbody tr').each(function() {
                    let weight = parseFloat($(this).find('.weight').val()) || 0;
                    let kg_volume = parseFloat($(this).find('.kg_volume').val()) || 0;

                    total_temp_weight += weight;
                    total_temp_kg_volume += kg_volume;
                });
                if (total_temp_weight > total_temp_kg_volume) {
                    totalWeight = total_temp_weight;
                } else {
                    totalWeight = total_temp_kg_volume;
                }


                $('#total-weight').val(totalWeight.toFixed(0));


                var outletasal = $('#outlet_id_hidden').val()
                var customer_id = $('#customer_id').val()
                var armada = $('#armada').val()
                var pengambilan_id = $('#pengambilan_id').val()
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
                            pengambilan_id: pengambilan_id
                        },
                        success: function(response) {
                            var pricePerKg = parseFloat(response.data.price)
                            var nextweightprice = response.data.nextweightprices
                            minimumweight = response.data.minweights;

                            var price = response.data.price
                            var price_id = response.data.price_id
                            var totalPrice = 0;
                            if ($('#armada').val() || $('#destination_id').val() || $('#customer_id')
                                .val() || $('#outlet_id_hidden').val()) {


                                if ($('#armada').val() == 1) {
                                    if (totalWeight < minimumweight) {
                                        totalWeight = minimumweight
                                    }
                                    totalPrice = price + ((totalWeight - minimumweight) *
                                        nextweightprice)
                                } else if ($('#armada').val() == 2) {
                                    if (totalWeight < minimumweight) {
                                        totalWeight = minimumweight
                                    }
                                    totalPrice = totalWeight * pricePerKg;
                                } else if ($('#armada').val() == 3) {
                                    totalPrice = totalWeight * pricePerKg;
                                }
                                if (totalPrice > 0) {
                                    $('#total-price').val(totalPrice.toFixed(0));
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Error: ', xhr.responseText)
                        }
                    });
                }

            }


            $(document).on('click', '.remove-data-koli', function() {
                var id = $(this).data('id');
                var row = $(this).closest('tr');

                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    $.ajax({
                        url: '/order/delete-koli-order',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                row.remove();
                                calculateTotals();
                            } else {
                                alert('Gagal menghapus data.');
                            }
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan.');
                        }
                    });
                }
            });


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
            $('#pengambilan_id').select2();

            $('#form-edit-transaksi').validate({
                rules: {
                    'customer_id': {
                        required: true
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
                    'pengambilan_id': {
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
                    'armada': 'Pilih salah satu armada.',
                    'service': 'Pilih jenis barang.',
                    'destination_id': 'Pilih salah satu destinasi.',
                    'pengambilan_id': 'Pilih salah satu pengambilan.',
                    'address': 'Alamat harus diisi.',
                    'weight': 'Berat harus diisi.',
                    'volume': 'Volume harus diisi.',
                    'price': 'Harga harus diisi.',
                    'payment_method': 'Pilih salah satu.',
                    'koli': 'Koli harus diisi.',
                    'estimation': 'Estimasi harus diisi.',
                    'description': 'Deskripsi harus diisi.',
                    'note': 'Catatan harus diisi.',
                    'receiver': 'Penerima harus diisi.'
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
