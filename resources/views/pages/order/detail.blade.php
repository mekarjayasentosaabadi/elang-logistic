@extends('layout.app')

@section('title')
    <span>Transaksi</span>
    <small>/</small>
    <small>Detail</small>
@endsection

@section('content')
    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="">
                <div class="card invoice-preview-card">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="logo-wrapper">
                                    <img src="{{asset('assets/img/logo.png')}}" height="24" alt="">
                                </div>
                            </div>
                            <div class="mt-md-0 mt-2 d-flex flex-column align-items-end">
                                <h4 class="invoice-title">
                                    Nomor Order
                                    <span class="invoice-number">| {{ $order->numberorders }}</span>
                                </h4>
                                <div class="d-flex gap-1">
                                    @if ($order->status_orders == "2" || $order->status_orders == "3" )
                                        <a class="btn btn-outline-secondary " href="/order/{{ Crypt::encrypt($order->id); }}/print" target="_blank">Print Format 1</a>
                                        <a class="btn btn-outline-secondary " href="/order/{{ Crypt::encrypt($order->id); }}/print-v2" target="_blank">Print Format 2</a>
                                    @endif
                                        <a class="btn btn-warning " href="/order">Kembali</a>
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
                                        <p>Pengirim: {{$order->customer->name}}</p>
                                        <p>Penerima: {{ $order->penerima ?? '-' }}</p>
                                        <p>Destinasi: {{ $order->destination->name }}</p>
                                        <p>Jenis Barang: {{ $order->service == 1 ? 'Document' : 'Package' }}</p>
                                        <p>Koli: {{ $order->koli ?? '-' }}</p>
                                        <p>Berat: {{ $order->weight ?? '-' }}</p>
                                        <p>Volume: {{ $order->volume ?? '-' }}</p>
                                        <p>Harga: {{ $order->price ?? '-' }}</p>
                                        <p>Metode Pembayaran:
                                            @if ($order->payment_method == '1')
                                                Tagih Tujuan
                                            @elseif ($order->payment_method == '2')
                                                Tagih Pada Pengirim
                                            @elseif ($order->payment_method == '3')
                                                Tunai
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <p>Konten: {{ $order->content ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p>Service:
                                            @if ($order->armada == '1')
                                                Darat
                                            @elseif ($order->armada == '2')
                                                Laut
                                            @elseif ($order->armada == '3')
                                                Udara
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <p>Alamat: {{ $order->address ?? '-' }}</p>
                                        <p>Estimasi: {{ $order->estimation.' hari' ?? '0 hari' }}</p>
                                        <p>Deskripsi Barang: {{ $order->description ?? '-' }}</p>
                                        <p>Catatan: {{ $order->note ?? '-' }}</p>
                                        <p>Foto bukti diterima:
                                            @if ($order->photos)
                                                <div class="mt-1">
                                                        <img width="60" src="{{asset('storage/'.$order->photos)}}" alt="">
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </p>
                                        <p>Status Awb: {{ $order->status_awb ?? '-' }}</p>
                                        <p>Status:
                                            @if ($order->status_orders == '1')
                                                <small class="text-sm badge bg-warning">Panding</small>
                                            @elseif ($order->status_orders == '2')
                                                <small class="text-sm badge bg-primary">Diprocess</small>
                                            @elseif ($order->status_orders == '3')
                                                <small class="text-sm badge bg-success">Done</small>
                                            @elseif ($order->status_orders == '4')
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
            <!-- /Invoice -->

            <!-- Detail Order Actions -->
            {{-- <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                            @if ($order->status_orders == "2" || $order->status_orders == "3" )
                                <a class="btn btn-outline-secondary w-100 mb-75" href="/order/{{ Crypt::encrypt($order->id); }}/print" target="_blank">
                                    Print Format 1</a>
                                <a class="btn btn-outline-secondary w-100 mb-75" href="/order/{{ Crypt::encrypt($order->id); }}/print-v2" target="_blank">
                                    Print Format 2</a>
                            @endif
                            <a class="btn btn-warning w-100 mb-75" href="/order">Kembali</a>
                    </div>
                </div>
            </div> --}}
            <div class="cocard-body  invoice-padding pt-0">
                <div class="card">
                    <h4 class="card-header">History Resi</h4>
                    <div class="card-body">
                        <ul class="timeline ms-50">
                            @foreach ($historyAwbs as $historyAwb)
                                <li class="timeline-item">
                                    <span class="timeline-point timeline-point-indicator"></span>
                                    <div class="timeline-event">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                            <h6>No Order {{ $historyAwb->awb }}</h6>
                                            <span class="timeline-event-time me-1">{{ $historyAwb->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p>{{ $historyAwb->status }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- /Detail Order Actions -->
        </div>
    </section>
@endsection
