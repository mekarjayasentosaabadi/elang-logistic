@extends('layout.app')

@section('title')
    <span>Tranasksi</span>
    <small>/</small>
    <small>Detail</small>
@endsection

@section('content')
    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="logo-wrapper">
                                    <img src="{{asset('assets/img/logo.png')}}" height="24" alt="">

                                </div>
                            </div>
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Nomor Order
                                    <span class="invoice-number">| 123223492</span>
                                </h4>
                            </div>
                        </div>
                        <!-- Header ends -->
                    </div>

                    <hr class="invoice-spacing" />

                    <!-- Address and Contact starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row invoice-spacing">
                                <h6 class="mb-2">Detail Transaksi</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <tbody>
                                            <tr>
                                                <td class="pe-1">Pengirim</td>
                                                <td>: {{$order->customer->name}}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Penerima</td>
                                                <td>: {{ $order->outlet->name ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Destinasi</td>
                                                <td>: {{ $order->destination->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Jenis Barang</td>
                                                <td>: {{ $order->service == 1 ? 'Document' : 'Package' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Koli</td>
                                                <td>: {{ $order->koli ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Berat</td>
                                                <td>: {{ $order->weight ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Volume</td>
                                                <td>: {{ $order->volume ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Harga</td>
                                                <td>: {{ $order->price ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Metode Pembayaran</td>
                                                @if ($order->payment_method == '1')
                                                    <td>: Tagih Tujuan</td>
                                                @elseif ($order->payment_method == '2')
                                                    <td>: Tagih Pada Pengirim</td>
                                                @elseif ($order->payment_method == '3')
                                                    <td>: Tunai</td>
                                                @else
                                                    <td>: -</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Konten</td>
                                                <td>: {{ $order->content ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Armada</td>
                                                @if ($order->armada == '1')
                                                    <td>: Darat</td>
                                                @elseif ($order->armada == '2')
                                                    <td>: Laut</td>
                                                @elseif ($order->armada == '3')
                                                    <td>: Udara</td>
                                                @else
                                                    <td>: -</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Alamat</td>
                                                <td>: {{ $order->address ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Estimasi</td>
                                                <td>: {{ $order->estimation ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Deskripsi Barang</td>
                                                <td>: {{ $order->description ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Catatan</td>
                                                <td>: {{ $order->note ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Foto</td>
                                                <td>: {{ $order->photos ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Status Awb</td>
                                                <td>: {{ $order->status_awb ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="pe-1">Status</td>
                                                @if ($order->status_orders == '1')
                                                    <td>: <small class="text-sm badge bg-warning">Panding</small></td>
                                                @elseif ($order->status_orders == '2')
                                                    <td>: <small class="text-sm badge bg-primary">Diprocess</small></td>
                                                @elseif ($order->status_orders == '3')
                                                    <td>: <small class="text-sm badge bg-success">Done</small></td>
                                                @elseif ($order->status_orders == '4')
                                                    <td>: <small class="text-sm badge bg-danger">Dibatalkan</small></td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                    <!-- Address and Contact ends -->

                    <hr class="invoice-spacing" />


                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                        <a class="btn btn-outline-secondary w-100 mb-75" href="./app-invoice-print.html" target="_blank">
                            Print</a>
                        <button class="btn btn-primary w-100" data-bs-toggle="modal"
                            data-bs-target="#add-payment-sidebar">
                            Edit Langsung
                        </button>
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>
    </section>


    <!-- Add Payment Sidebar -->
    <div class="modal modal-slide-in fade" id="add-payment-sidebar" aria-hidden="true">
        <div class="modal-dialog sidebar-lg">
            <div class="modal-content p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title">
                        <span class="align-middle">Add Payment</span>
                    </h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <form>
                        <div class="mb-1">
                            <input id="balance" class="form-control" type="text" value="Invoice Balance: 5000.00"
                                disabled />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="amount">Payment Amount</label>
                            <input id="amount" class="form-control" type="number" placeholder="$1000" />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="payment-date">Payment Date</label>
                            <input id="payment-date" class="form-control date-picker" type="text" />
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="payment-method">Payment Method</label>
                            <select class="form-select" id="payment-method">
                                <option value="" selected disabled>Select payment method</option>
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="Debit">Debit</option>
                                <option value="Credit">Credit</option>
                                <option value="Paypal">Paypal</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label class="form-label" for="payment-note">Internal Payment Note</label>
                            <textarea class="form-control" id="payment-note" rows="5" placeholder="Internal Payment Note"></textarea>
                        </div>
                        <div class="d-flex flex-wrap mb-0">
                            <button type="button" class="btn btn-primary me-1" data-bs-dismiss="modal">Send</button>
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Payment Sidebar -->
@endsection

@section('custom-js')
@endsection
