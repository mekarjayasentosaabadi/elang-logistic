@extends('layout.app')
@section('title')
    <span>Shipping Courier</span>
    <small>/</small>
    <small>Edit</small>
@endsection

@section('content')
    @if (Auth::user()->role_id == '1')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Outlet</h4>
                        <a href="{{ url('/shipping-courier') }}" class="btn btn-warning">Kembali</a>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="outlet_id_select">Outlet</label>
                            <select name="outlet_id_select" id="outlet_id_select" class="form-control">
                                <option value="">Pilih Outlet</option>
                                @foreach ($outlets as $outlet)
                                    <option value="{{ $outlet->id }}"
                                        {{ old('outlet_id', $shippingCourier->outlet_id) == $outlet->id ? 'selected' : '' }}>{{ $outlet->name }}</option>
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
            <form id="form-create-shipping" action="{{ url('shipping-courier/'.Crypt::encrypt($shippingCourier->id).'/update') }}" method="post">
                @method('PATCH')
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Pengiriman</h4>
                        @if (Auth::user()->role_id != '1')
                            <a href="{{ url('/shipping-courier') }}" class="btn btn-warning">Kembali</a>
                        @endif
                    </div>
                    <div class="card-body mb-5">
                        @csrf
                        <div id="hidden-inputs-container">
                            @foreach ($shippingCourier->detailshippingcourier as $detail)
                                @php
                                    $order = $detail->order;
                                @endphp
                                <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="shipping_no">Nomor Pengiriman</label>
                                    <input type="text" name="shipping_no" id="shipping_no" class="form-control"
                                        placeholder="Masukan nomor pengiriman" value="{{ $shippingCourier->shippingno }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="courier">Kurir</label>
                                    <select name="courier" id="courier" class="form-control">
                                        <option value="" hidden>Pilih Kurir</option>
                                        @foreach ($couriers as $courier)
                                            <option value="{{ $courier->id }}" {{ $courier->id == $shippingCourier->driver_id ? 'selected' : '' }}>
                                                {{ $courier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Catatan</label>
                                    <textarea name="note" rows="4" id="note" class="form-control" placeholder="masukan catatan">{{ $shippingCourier->notes }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tambahkan paket</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter"> Add Paket</button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table" id="tbl-paket">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Numbers</th>
                                            <th>Customer</th>
                                            <th>Kg</th>
                                            <th>Destinations</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl-detail-paket">
                                        @foreach ($shippingCourier->detailshippingcourier as $detail)
                                            @php
                                                $order = $detail->order;
                                            @endphp
                                            <tr data-id="{{ $order->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->numberorders }}</td>
                                                <td>{{ $order->customer->name }}</td>
                                                <td>{{ $order->weight }}</td>
                                                <td>{{ $order->destination->name }}</td>
                                                <td><button type="button" class="btn text-danger d-flex" onclick="removeRow(this)"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>Cancle</button></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <button class="btn btn-primary btn-md float-end">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">List Data Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-orders">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Order / AWB</th>
                                    <th>Customer</th>
                                    <th>Destination</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->numberorders }}</td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $order->destination->name }}</td>
                                        <td>
                                            <input class="form-check-input" name="checkbox" type="checkbox" value="{{ $order->id }}" onchange="check(this)" {{ in_array($order->id, $shippingCourier->detailshippingcourier->pluck('orders_id')->toArray()) ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Selesai</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        function check(el) {
            var id = $(el).val();
            if ($(el).is(':checked')) {
                $.ajax({
                    url: "{{ url('/shipping-courier/getOrderDetail') }}",
                    type: 'GET',
                    data: { id: id },
                    success: function(response) {
                        $('#tbl-detail-paket').append(`
                            <tr data-id="${response.id}">
                                <td>${$('#tbl-detail-paket tr').length + 1}</td>
                                <td>${response.numberorders}</td>
                                <td>${response.customer}</td>
                                <td>${response.weight}</td>
                                <td>${response.destination}</td>
                                <td><button type="button" class="btn text-danger d-flex" onclick="removeRow(this)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50">
                                    <polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>Cancle</button></td>
                            </tr>
                        `);
                        $('#hidden-inputs-container').append(`<input type="hidden" name="order_ids[]" value="${response.id}">`);
                    }
                });
            } else {
                $('#tbl-detail-paket tr[data-id="' + id + '"]').remove();
                $('#hidden-inputs-container input[value="' + id + '"]').remove();
            }
        }

        function removeRow(el) {
            var id = $(el).closest('tr').data('id');
            $(el).closest('tr').remove();
            $('#hidden-inputs-container input[value="' + id + '"]').remove();
            $('#tbl-orders input[value="' + id + '"]').prop('checked', false);
        }

        $(document).ready(function () {
            $('#form-create-shipping').validate({
                rules: {
                    'shipping_no': 'required',
                    'courier': 'required',
                    'order_id': 'required',
                    'note': 'required',
                },
                messages: {
                    'shipping_no': "Nomor pengiriman harus diisi.",
                    'courier': "Pilih salah satu kurir.",
                    'order_id': "Pilih salah satu paket",
                    'note': "Catatan harus diisi."
                },
            })
        })
    </script>
@endsection
