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
                                    <span class="invoice-number">| {{ $order->numberorders }}</span>
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
                                                <td>: {{ $order->penerima ?? '-' }}</td>
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
                                                <td class="pe-1">Service</td>
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
                                                <td>: {{ $order->estimation.' hari' ?? '0 hari' }}</td>
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

            <!-- Detail Order Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                            @if ($order->status_orders == "2" || $order->status_orders == "3" )
                                <a class="btn btn-outline-secondary w-100 mb-75" href="/order/{{ Crypt::encrypt($order->id); }}/print" target="_blank">
                                    Print Format 1</a>
                                <a class="btn btn-outline-secondary w-100 mb-75" href="/order/{{ Crypt::encrypt($order->id); }}/print-v2" target="_blank">
                                    Print Format 2</a>
                            @endif
                            <a class="btn btn-primary w-100 mb-75" href="/order">Kemali</a>
                    </div>
                </div>
            </div>
            <div class="cocard-body col-md-9  invoice-padding pt-0">
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

@section('custom-js')
@endsection
