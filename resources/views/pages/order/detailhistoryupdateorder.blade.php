@extends('layout.app')

@section('title')
    <span>Transaksi</span>
    <small>/</small>
    <small>History Update Order / Detail</small>
@endsection

@section('content')
    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="">
                <ul class="nav nav-pills mb-2">
                    {{-- <li class="nav-item">
                        <a class="nav-link active" href="/order/{{ encrypt($historyOrder->id) }}/detail">
                            <i data-feather="eye" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Detail Order</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/order/{{ encrypt($historyOrder->id) }}/historyupdate"">
                            <i data-feather="clock" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">History Update</span>
                        </a>
                    </li> --}}
                </ul>
                <div class="card invoice-preview-card">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="logo-wrapper">
                                    <img src="{{ asset('assets/img/logo.png') }}" height="24" alt="">
                                </div>
                            </div>
                            <div class="mt-md-0 mt-2 d-flex flex-column align-items-end">
                                <h4 class="invoice-title">
                                    Nomor Order
                                    <span class="invoice-number">| {{ $historyOrder->numberorders }}</span>
                                </h4>
                                <div class="d-flex gap-1">
                                    {{-- @if ($historyOrder->status_orders == '2' || $historyOrder->status_orders == '3')
                                        <a class="btn btn-outline-secondary "
                                            href="/order/{{ Crypt::encrypt($historyOrder->order_id) }}/print" target="_blank">Print
                                            Format 1</a>
                                        <a class="btn btn-outline-secondary "
                                            href="/order/{{ Crypt::encrypt($historyOrder->order_id) }}/print-v2" target="_blank">Print
                                            Format 2</a>
                                    @endif --}}
                                    <a class="btn btn-warning " href="/order/{{encrypt($historyOrder->order_id)}}/historyupdate">Kembali</a>
                                </div>
                            </div>
                        </div>
                        <!-- Header ends -->
                    </div>

                    <hr class="invoice-spacing" />

                    <div class="card-body invoice-padding pt-0">
                        <div class="row invoice-spacing">
                            <h6 class="mb-2">Detail Transaksi</h6>

                            <div class="d-flex flex-wrap">
                                <div class="col-md-6">
                                    <p>Pengirim: {{ $historyOrder->order->customer->name ?? '-' }}</p>
                                    <p>Penerima: {{ $historyOrder->penerima ?? '-' }}</p>
                                    <p>Pengambilan: {{ $historyOrder->order->pengambilan->name ?? '-' }}</p>
                                    <p>Destinasi: {{ $historyOrder->order->destination->name }}</p>
                                    <p>Jenis Barang: {{ $historyOrder->service == 1 ? 'Document' : 'Package' }}</p>
                                    <p>Koli: {{ $historyOrder->koli ?? '-' }}</p>
                                    <p>Berat: {{ $historyOrder->weight ?? '-' }}</p>
                                    <p>Volume: {{ $historyOrder->volume ?? '-' }}</p>
                                    <p>Harga: {{ $historyOrder->price ?? '-' }}</p>
                                    <p>Metode Pembayaran:
                                        @if ($historyOrder->payment_method == '1')
                                            Tagih Tujuan
                                        @elseif ($historyOrder->payment_method == '2')
                                            Tagih Pada Pengirim
                                        @elseif ($historyOrder->payment_method == '3')
                                            Tunai
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p>Konten: {{ $historyOrder->content ?? '-' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p>Service:
                                        @if ($historyOrder->armada == '1')
                                            Darat
                                        @elseif ($historyOrder->armada == '2')
                                            Laut
                                        @elseif ($historyOrder->armada == '3')
                                            Udara
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p>Alamat: {{ $historyOrder->address ?? '-' }}</p>
                                    <p>Estimasi: {{ $historyOrder->estimation . ' hari' ?? '0 hari' }}</p>
                                    <p>Deskripsi Barang: {{ $historyOrder->description ?? '-' }}</p>
                                    <p>Catatan: {{ $historyOrder->note ?? '-' }}</p>
                                    <p>Foto bukti diterima:
                                        @if ($historyOrder->photos)
                                            <div class="mt-1">
                                                <img width="200" src="{{ asset('storage/images/' . $historyOrder->photos) }}"
                                                    alt="">
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </p>
                                    <p>Status Awb: {{ $historyOrder->status_awb ?? '-' }}</p>
                                    <p>Status:
                                        @if ($historyOrder->status_orders == '1')
                                            <small class="text-sm badge bg-warning">Panding</small>
                                        @elseif ($historyOrder->status_orders == '2')
                                            <small class="text-sm badge bg-primary">Diprocess</small>
                                        @elseif ($historyOrder->status_orders == '3')
                                            <small class="text-sm badge bg-success">Done</small>
                                        @elseif ($historyOrder->status_orders == '4')
                                            <small class="text-sm badge bg-danger">Dibatalkan</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="invoice-spacing" />


                </div>
            </div>
            <!-- /Detail Order Actions -->
        </div>
    </section>
@endsection
