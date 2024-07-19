<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title>Elang Logistics</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .centered-logo {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <div class="invoice-print p-3">

                    <div class="table-responsive">
                        <table class="border w-100">
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="border" rowspan="2" colspan="2">
                                        <div style="display: flex; justify-content: center">
                                            <img src="{{asset('assets/img/barcode.jpg')}}" width="180" alt="">
                                        </div>
                                    </td>
                                    <td class="" rowspan="3">
                                        <div class="ms-1">
                                            <div>Tanggal : {{ $order->created_at }}</div>
                                            <div>No Order : {{ $order->numberorders }}</div>
                                            <div>Service : {{ $order->service == '1' ? 'document' : 'package' }}</div>
                                            <div>Deskripsi : {{ $order->description }}</div>
                                            <div>Berat : {{ $order->weight }}</div>
                                            <div>Jumlah Kiriman : {{ $order->koli }} </div>
                                            <div>Kota Tujuan : {{ $order->destination->name }}</div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom"></tr>
                                <tr class="border-bottom">
                                    <td class="centered-logo">
                                        <img class="mt-3" src="{{asset('assets/img/logo.png')}}" width="120" alt="">
                                    </td>
                                    <td class="border">
                                        <div class="ms-1">
                                            <div>Pengirim</div>
                                            <div>{{ $order->customer->name }}</div>
                                            <div>Penerima</div>
                                            <div>{{ $order->penerima ?? '.'}} </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
</body>
<!-- END: Body-->

</html>
