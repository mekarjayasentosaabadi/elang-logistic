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
                                <img src="{{ asset('assets/img/logo.png') }}" height="30" alt="">
                            </div>
                        </div>
                        <div class="mt-md-0 mt-2">
                            <h4 class="text-end mb-1 fw-bold">ASPERINE</h4>
                        </div>
                    </div>
                    <hr class="my-1" />

                    <div class="table-responsive">
                        <table class="border w-100">
                            <thead>
                                <tr class="border">
                                    <th class="border px-1 fs-8px">Keterangan</th>
                                    <th class="border px-1 fs-8px">Asal</th>
                                    <th class="border px-1 fs-8px">Tujuan</th>
                                    <th class="border px-1 fs-8px">Koli</th>
                                    <th class="border px-1 fs-8px">Berat</th>
                                    <th class="border px-1 fs-8px">Berat Volume</th>
                                    <th class="border px-1 fs-8px">Nomer Pesanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom">
                                    <td class="border px-1 fs-8px"></td>
                                    <td class="border px-1 fs-8px">{{ $originLocationOrder->name}}</td>
                                    <td class="border px-1 fs-8px">{{ $order->destination->name }}</td>
                                    <td class="border px-1 fs-8px">{{ $order->koli }}</td>
                                    <td class="border px-1 fs-8px">{{ $order->weight }}</td>
                                    <td class="border px-1 fs-8px">{{ $order->volume }}</td>
                                    <td class="border px-1 fs-8px">{{ $order->numberorders }}</td>
                                </tr>
                                <tr class="">
                                    <td class="border px-1  fs-8px text-top align-left" colspan="3" rowspan="4">Pengirim: {{ $order->customer->name }}</td>
                                    <td class="border px-1  fs-8px text-top align-left" colspan="3" rowspan="4">Penerima:  {{ $order->penerima }}</td>
                                    <td class="px-1  fs-8px">Cara Pembayaran</td>
                                </tr>

                                <tr class="">
                                    <td class="px-1  fs-8px"><input type="checkbox" {{ $order->payment_method == '1' ? 'checked' : '' }} disabled> Tagih Tujuan</td>
                                </tr>
                                <tr class="">
                                    <td class="px-1  fs-8px"><input type="checkbox" {{ $order->payment_method == '2' ? 'checked' : '' }} disabled> Tagih Pada Pengirim</td>
                                </tr>
                                <tr class="">
                                    <td class="px-1  fs-8px"><input type="checkbox" {{ $order->payment_method == '3' ? 'checked' : '' }} disabled> Tunai</td>
                                </tr>



                                <tr class="border-bottom">
                                    <td class="border px-1  fs-8px text-top align-left" rowspan="2" colspan="2">
                                        Instruksi Khusus</td>
                                    <td class="border px-1  fs-8px text-top align-left" rowspan="2">Nilai Barang <br> {{ $order->price }}</td>
                                    <td class="border px-1  fs-8px fs-8px text-top align-left" rowspan="2"
                                        colspan="3">Keterangan Barang</td>
                                    <td class="border px-1  fs-8px">Biaya</td>
                                </tr>
                                <tr class="border-bottom">
                                    <td class="border px-1  fs-8px">Jenis Pelayanan <br>
                                        <div class="d-flex gap-1">
                                            <div><input type="checkbox" {{ $order->service == '1' ? 'checked' : '' }} disabled> Dokumen</div>
                                            <div><input type="checkbox" {{ $order->service == '2' ? 'checked' : '' }} disabled> Package</div>
                                        </div>
                                    </td>
                                </tr>

                                <tr class="border-bottom">
                                    <td class="border px-1  fs-8px text-top align-left" colspan="2">Tanda Tangan Pengirim <br>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="d-flex mx-1 justify-content-between">
                                            <div>Tanggal: </div>
                                            <div>Jam: </div>
                                        </div>
                                    </td>
                                    <td class="border px-1  fs-8px text-top align-left" colspan="2">Tanda Tangan Petugas <br>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="d-flex mx-1 justify-content-between">
                                            <div>Tanggal: </div>
                                            <div>Jam: </div>
                                        </div>
                                    </td>
                                    <td class="border px-1 fs-8px text-top align-left" colspan="2">Nama & Tanda Tangan Penerima  <br>
                                        <br>
                                        <br>
                                        <br>
                                        <div class="d-flex mx-1 justify-content-between">
                                            <div>Tanggal: </div>
                                            <div>Jam: </div>
                                        </div></td>
                                    <td class="border px-1  fs-6px" style="max-width: 100px"><i>Syarat Syarat
                                            Pengiriman: <br> Saya / kami dengan ini menerima seluruh kondisi yang
                                            tercantum dibalik resi ini sebagai syarat dan kondisi pengiriman. Elang
                                            Logistic memberi batas nilai kiriman hingga maks 10 kali biaya kirim per
                                            resi atas resiko kehilangan / kerusakan. kiriman dengan nilai di atas 10
                                            kali biaya kirim dianjurkan untuk diasuransikan</i> </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between mt-1">
                            <div class="fs-8px">Putih: Pengirim</div>
                            <div class="fs-8px">Merah: Tagihan</div>
                            <div class="fs-8px">Hujau: Arsip</div>
                            <div class="fs-8px">Biru: POD</div>
                            <div class="fs-8px">Kuning: Penerima</div>
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
