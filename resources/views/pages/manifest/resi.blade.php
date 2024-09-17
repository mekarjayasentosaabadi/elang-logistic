<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>

    <title>Elang Logistics</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @include('layout.css')
    <style>
        .fs-8px {
            font-size: 8px;
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

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">

            <div class="content-body">
                <div class="invoice-print p-2">
                    <div class="invoice-header d-flex justify-content-between flex-md-row flex-column">
                        <div>
                            <div class="d-flex mb-1">
                            </div>
                        </div>
                        <div class="mt-md-0 mt-2">
                            <h4 class="text-end mb-1 fw-bold">MNF : {{ $manifest->manifestno }}</h4>
                        </div>
                    </div>
                    <hr class="my-1" />

                    <div class="">
                        <table class="border w-100">
                            <thead>
                                <tr class="border">
                                    <td class="border px-1 fs-8px"><img src="{{ asset('assets/img/logo.png') }}" height="30" alt=""></td>
                                    <td>

                                            <div class="row" style="padding: 5px">
                                                <div class="col-4 fs-8px">
                                                    <div>COURIER MANIFEST</div>
                                                    <div>
                                                        ORIGIN : <br>
                                                        <b>{{ $manifest->outlet->name }}</b>
                                                    </div>
                                                </div>
                                                <div class="col-8 fs-8px">
                                                    <div class="row">
                                                        <div class="col-3">DESTINATION : <b>{{ $manifest->destination->name }} </b></div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3">FLIGHT No</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">DATE : <b> {{ $manifest->created_at }}</b></div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3">No. BAGS</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">CARIER</div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3">CBV/AWB</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-3">COMMODITY</div>
                                                        <div class="col-3">LV/HV/FE/MIX</div>
                                                        <div class="col-3"></div>
                                                        <div class="col-3">FLIGHT FILE</div>
                                                    </div>
                                                </div>
                                            </div>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        <table class="border w-100">
                            <thead>
                                <tr class="border">
                                    <th class="border px-1 fs-8px">Airbill</th>
                                    <th class="border px-1 fs-8px">Shipper</th>
                                    <th class="border px-1 fs-8px">Consignee</th>
                                    <th class="border px-1 fs-8px">Destination</th>
                                    <th class="border px-1 fs-8px">Pcs</th>
                                    <th class="border px-1 fs-8px">Kg</th>
                                    <th class="border px-1 fs-8px">Content</th>
                                    <th class="border px-1 fs-8px">Value</th>
                                    <th class="border px-1 fs-8px"></th>
                                </tr>
                            </thead>
                            <tbody id="tblDetail">
                                @foreach ($dataManifest as $item)
                                    <tr>
                                        <td class="border px-1 fs-8px">{{ $item->order->numberorders }}</td>
                                        <td class="border px-1 fs-8px">-</td>
                                        <td class="border px-1 fs-8px">-</td>
                                        <td class="border px-1 fs-8px">{{ $item->order->destination->name }}</td>
                                        <td class="border px-1 fs-8px">{{ $item->order->koli }}</td>
                                        <td class="border px-1 fs-8px">{{ $item->order->weight }} Kg</td>
                                        <td class="border px-1 fs-8px">{{ $item->order->content }}</td>
                                        <td class="border px-1 fs-8px">-</td>
                                        <td class="border px-1 fs-8px">-</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="fs-8px">Delivered By: _______</div>
                            <div class="fs-8px">Received and Checked By: _____________</div>
                            <div class="fs-8px">Date : _________</div>
                            <div class="fs-8px">Time: __________</div>
                        </div>
                    </div>
                    <button id="print-button" type="button" class="btn btn-primary btn-sm mt-3 no-print">Cetak Sekarang <i class="bi bi-printer" style="color: white"></i></button>
                </div>
            </div>
        </div>
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
