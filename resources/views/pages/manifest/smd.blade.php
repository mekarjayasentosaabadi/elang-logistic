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
    <div class="fs-10px mx-2 ">
        <div class="pt-2 w-full">
            <div style="text-align: center; width: 190px">
                {!! '<img  width="210" height="40" src="data:image/png;base64,' . DNS1D::getBarcodePNG("$manifest->no_smd", 'C39+') .'" alt="barcode"   />' !!}
                <h5 class="">No SMD : {{ $manifest->no_smd}} </h5>
            </div>
            <hr>
            <h3 class="text-center mb-2">SURAT MUATAN DATARAT</h3>
            <div class="">
                <div class="row">
                    <div class="col-12">
                        Bersama ini kami menerangkan bahwa Mobil No. Pol <b>{{ $manifest->detailSuratTugas->surattugas->vehicle->police_no ?? '-' }}</b> yang dikemudikan oleh Sdr. <b> {{ $manifest->detailSuratTugas->surattugas->driver->name ?? '-' }} </b>, mengangkut barang dari :
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center mx-1">
                <div class="w-100">
                    <div class="row w-full">
                        <div class="col-4 border" style="padding: 5px">
                            Kota Asal : <b> {{ $manifest->outlet->destination->name ?? '-'}} </b>
                        </div>
                        <div class="col-4 border" style="padding: 5px">
                            Tujuan : <b> {{ $manifest->destination->name ?? '-' }} </b>
                        </div>
                        <div class="col-4 border" style="padding: 5px">
                            Tanggal : <b>{{ $manifest->created_at }}</b> 
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex mt-1">
                Sejumlah :
            </div>
            <div class="d-flex justify-content-center mx-1">
                <div class="w-100">
                    <div class="row w-full">
                        <div class="col-4 border" style="padding: 5px">
                            {{-- Koli : <b> {{ $manifest->detailsurattugas->count() }} </b> --}}
                            Koli : <b> {{ $totalKoli }} </b>
                        </div>
                        <div class="col-4 border" style="padding: 5px">Berat : <b> {{ $totalBerat }} </b> kg</div>
                        <div class="col-4 border" style="padding: 5px">Keterangan : </div>
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
