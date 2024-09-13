@extends('layout.app')
@section('title')
    <span>Surat Tugas</span>
    <small>/</small>
    <small>Detail</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card invoice-preview-card">
                <div class="card-body invoice-padding pb-0">
                    <!-- Header starts -->
                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                        <div>
                            <div class="logo-wrapper">

                                <h3 class="text-primary invoice-logo">SURAT TUGAS DETAILS</h3>
                            </div>
                            <p class="card-text mb-25">Nomor Surat Tugas : <b> {{ $datailSuratTugas->nosurattugas }} </b> </p>
                            <p class="card-text mb-25">Tanggal Dibuat : <b>{{ $datailSuratTugas->created_at }} </b> </p>
                            <p class="card-text mb-25">No Surat Muatan Darat : <b> </b> </p>
                            <p class="card-text mb-25">Destination : <b> {{ $datailSuratTugas->destination->name }} </b> </p>
                            <p class="card-text mb-25">Kendaraan / Vehicle : <b>{{ $datailSuratTugas->vehicle->police_no }} / {{ $datailSuratTugas->vehicle->no_stnk }}</b> </p>
                            <p class="card-text mb-25">Driver : <b>{{ $datailSuratTugas->driver->name }}</b></p>
                            <p class="card-text mb-0">Note / Catatan : {{ $datailSuratTugas->note }} </p>
                        </div>
                        <div class="mt-md-0 mt-2">
                            <a href="{{ route('surattugas.index') }}" class="btn btn-warning btn-sm"><li class="fa fa-undo"></li> Kembali </a>
                        </div>
                    </div>
                    <!-- Header ends -->
                </div>

                <hr class="invoice-spacing" />
                <br>
                <!-- Invoice Description starts -->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="py-1">#</th>
                                <th class="py-1">Nomor Manifest</th>
                                <th class="py-1">No SMD</th>
                                <th class="py-1">Destinations</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-detail-surattugas">
                            @foreach ($listSuratTugasManifest as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->detailsurattugas->first()->manifest->manifestno }}</td>
                                    <td>{{ $item->detailsurattugas->first()->manifest->no_smd }}</td>
                                    <td>{{ $item->destination->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-body invoice-padding pb-0">
                    <div class="row invoice-sales-total-wrapper">
                        <div class="col-md-6 d-flex justify-content-end order-md-2 order-1">
                            <div class="invoice-total-wrapper">
                                <div class="invoice-total-item">
                                    <div class="invoice-total-amount" id="total-item"></div>
                                </div>
                                <div class="invoice-total-item">
                                    <div class="invoice-total-amount" id="total-jumlah-kg"></div>
                                </div>
                                <div class="invoice-total-item">
                                    <div class="invoice-total-amount" id="total-jumlah-koli"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Invoice Description ends -->

                <hr class="invoice-spacing" />

                <!-- Invoice Note starts -->
                <div class="card-body invoice-padding pt-0">
                    <div class="row">
                        <div class="col-12">
                            <span class="fw-bold">Note:</span>
                            <span></span>
                        </div>
                    </div>
                </div>
                <!-- Invoice Note ends -->
            </div>
        </div>
    </div>
@endsection

@section('custom-js')

@endsection
