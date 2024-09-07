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
            <div>
                <table class="w-100 table table table-striped-columns">
                    <tr>
                        <th>No</th>
                        <th>No Manifest</th>
                        <th>Destination</th>
                        <th>Jumlah AWB</th>
                        <th>Catatan</th>
                        <th>Status Manifest</th>
                    </tr>
                    @foreach ($detailSurattugas as $index => $data)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $data->manifest->manifestno ?? '-' }}</td>
                            <td>{{ $data->manifest->destination->name ?? '-' }}</td>
                            <td>{{ $data->manifest->detailmanifests->count() ?? '-' }}</td>
                            <td>{{ $data->manifest->notes ?? '-' }}</td>
                            <td>
                                @if ($data->manifest->status_manifest == '0')
                                    <div class="text-danger">Cancel</div>
                                @elseif ($data->manifest->status_manifest == '1')
                                    <div class="text-primary"><li class="fa fa-gears"></li> Process</div>
                                @elseif ($data->manifest->status_manifest == '2')
                                    <div class="text-primary"><li class="fa fa-truck"></li> On The Way</div>
                                @elseif ($data->manifest->status_manifest == '3')
                                    <div class="text-success"><li class="fa fa-check"></li> Success</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
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
