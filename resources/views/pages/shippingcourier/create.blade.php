@extends('layout.app')
@section('title')
    <span>Shipping Courier</span>
    <small>/</small>
    <small>create</small>
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
                                        {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>{{ $outlet->name }}</option>
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
            <form id="form-create-shipping" action="{{ url('shipping-courier/store') }}" method="post">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Pengiriman</h4>
                        @if (Auth::user()->role_id != '1')
                            <a href="{{ url('/shipping-courier') }}" class="btn btn-warning">Kembali</a>
                        @endif
                    </div>
                    <div class="card-body mb-5">
                        @csrf
                        <div id="hidden-inputs-container"></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="shipping_no">Nomor Pengiriman</label>
                                    <input type="text" name="shipping_no" id="shipping_no" class="form-control"
                                        placeholder="Masukan nomor pengiriman">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="courier">Kurir</label>
                                    <select name="courier" id="courier" class="form-control">
                                        <option value="" hidden>Pilih Kurir</option>
                                        @foreach ($couriers as $courier)
                                            <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Catatan</label>
                                    <textarea name="note" rows="4" id="note" class="form-control" placeholder="masukan catatan">{{ old('address') }}</textarea>
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

    {{-- Modal  --}}
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
                        <table class="table " id="tbl-orders">
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
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        // remove row detail paket
         function removeRow(button) {
            const orderId = $(button).closest('tr').data('id');
            $('input[name="order_ids[]"][value="' + orderId + '"]').remove();
            $(button).closest('tr').remove();
        }

        $(document).ready(function() {
            table = $('#tbl-orders').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/shipping-courier/getOrders') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'numberorders',
                        name: 'numberorders'
                    },
                    {
                        data: 'namacustomer',
                        name: 'namacustomer'
                    },
                    {
                        data: 'destination',
                        name: 'destination'
                    },
                    {
                        data: 'check',
                        name: 'check'
                    },
                ]
            });



             // Definisikan fungsi check
             window.check = function(checkbox) {
                let rowNumber = 1 ;
                const orderId = $(checkbox).val();
                const isChecked = $(checkbox).is(':checked');

                if (isChecked) {
                    // Mengambil detail pesanan dan menambahkannya ke tabel
                    $.ajax({
                        url: "{{ url('/shipping-courier/getOrderDetail') }}", // Route untuk mengambil detail pesanan
                        type: 'GET',
                        data: { id: orderId },
                        success: function(response) {
                            // Menambahkan baris ke tabel
                            $('#tbl-detail-paket').append(`
                                <tr data-id="${orderId}">
                                    <td>${rowNumber++}</td>
                                    <td>${response.numberorders}</td>
                                    <td>${response.customer}</td>
                                    <td>${response.weight}</td>
                                    <td>${response.destination}</td>
                                    <td><button type="button" class="btn text-danger d-flex" onclick="removeRow(this)"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>Cancle</button></td>
                                </tr>
                            `);
                            $('#hidden-inputs-container').append(`<input type="hidden" name="order_ids[]" value="${orderId}">`);
                        }
                    });
                } else {
                    $('#tbl-detail-paket tr[data-id="' + orderId + '"]').remove();
                }
            }


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
        });
    </script>
@endsection