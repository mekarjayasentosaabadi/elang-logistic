<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>

    <title>Elang Logistics</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('layout.css')
    <style>
        .fs-10px {
            font-size: 10px;
        }

        .fs-6px {
            font-size: 6px;
        }

        .text-top {
            vertical-align: top;
        }

        .align-left {
            text-align: left;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="fs-10px mt-4 container">
        <div class="w-100">
            <h5 class="">No : {{ $surattugas->nosurattugas ?? '-'}} </h5>
            <h3 class="text-center mb-2">SURAT TUGAS</h3>
            <div class="">
                <div class="row">
                    <div class="col-12">
                        Bersama ini kami menerangkan bahwa Mobil No. Pol <b> {{ $surattugas->vehicle->police_no ?? '-' }} </b> yang dikemudikan oleh Sdr. <b> {{ $surattugas->driver->name ?? '-' }} </b>, mengangkut barang dari :
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <div class="container">
                    <div class="row w-full">
                        <div class="col-4 border">
                            Kota Asal : <b> {{ $surattugas->outlet->destination->name ?? '-'}} </b>
                        </div>
                        <div class="col-4 border">Tujuan : <b> {{ $surattugas->destination->name ?? '-' }} </b></div>
                        <div class="col-4 border">Tanggal : <b>{{ $surattugas->created_at }}</b> </div>
                    </div>
                </div>
            </div>
            <div class="d-flex ">
                Sejumlah :
            </div>
            <div class="d-flex justify-content-center">
                <div class="container">
                    <div class="row w-full">
                        <div class="col-4 border">
                            {{-- Koli : <b> {{ $surattugas->detailsurattugas->count() }} </b> --}}
                            Koli : <b> {{ $totalKoli }} </b>
                        </div>
                        <div class="col-4 border">Berat : <b> {{$totalBerat}} </b> kg</div>
                        <div class="col-4 border">Keterangan : </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="d-flex ">
                Demikian Surat Muatan Darat Ini di buat :
            </div>
            <br>
            <div class="row w-full">
                <div class="d-flex col-4 justify-content-center">
                    Tanda Tangan <br>
                    Ops. Kota Asal
                </div>
                <div class="d-flex col-4 justify-content-center">
                    Tanda Tangan <br>
                    Pengemudi
                </div>
                <div class="d-flex col-4 justify-content-center">
                    Tanda Tangan <br>
                    Ops. Kota Tujuan
                </div>
            </div>
        </div>
        <button id="print-button" type="button" class="btn btn-primary btn-sm mt-3 no-print">Cetak Sekarang <i class="bi bi-printer" style="color: white"></i></button>
    </div>
    <script type="text/javascript">
       function printPage() {
            window.print();
        }

        window.onload = printPage;

        document.getElementById('print-button').addEventListener('click', printPage);
    </script>
</body>
<!-- END: Body-->
