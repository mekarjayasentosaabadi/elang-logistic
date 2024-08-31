@extends('layout.app')
@section('title')
    <span>Manifest</span>
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

                                <h3 class="text-primary invoice-logo">MANIFEST DETAILS</h3>
                            </div>
                            <p class="card-text mb-25">Nomor Manifest : <b> {{ $manifest->manifestno }}</b> </p>
                            <p class="card-text mb-25">No Surat Muatan Darat : <b> {{ $manifest->no_smd }}</b> </p>
                            <p class="card-text mb-25">Tanggal : <b> {{ $manifest->created_at }} </b> </p>
                            <p class="card-text mb-25">Carier : </p>
                            <p class="card-text mb-0">Note / Catatan : {{ $manifest->notes }} </p>
                        </div>
                        <div class="mt-md-0 mt-2">
                            <a href="{{ route('manifest.index') }}" class="btn btn-warning btn-sm"><li class="fa fa-undo"></li> Kembali </a>
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
                                <th class="py-1">Nomor AWB</th>
                                <th class="py-1">Customer</th>
                                <th class="py-1">Destinations</th>
                                <th class="py-1">Berat</th>
                                <th class="py-1">Koli</th>
                            </tr>
                        </thead>
                        <tbody id="tbl-detail-manifests">

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    {{-- <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script> --}}
    <script>
        let arrOrders = [];
        let arrOrdersTemp = [];
        let idOrders = [];
        var table
        let destination = '';
        const manifestId = '{{ $manifest->id }}';
        const role_id = '{{ Auth::user()->role_id }}';
        $(document).ready(function() {

            // get detail manifest
            $.ajax({
                url: window.location.origin + '/' + listRoutes['manifest.getdetail'].replace(
                    '{id}', manifestId),
                type: "GET",
                dataType: "JSON",
                success: function(e) {
                    e.data.detailmanifest.map((x, i) => {
                        getData(x.orders_id);
                    })
                },
                error: function(e) {
                    console.log(e)
                }
            })
        });

        const getData = (id) => {
            var baseUrl = window.location.origin + '/' + listRoutes['manifest.checkOrders'].replace(
                '{id}', id);
            $.getJSON(baseUrl, function() {

            }).done(function(e) {
                if (destination == '') {
                    destination = e.data[0].destination.name;
                    $('#destination_id').val(e.data[0].destination.id);
                } else {
                    if (destination != e.data[0].destination.name) {
                        // set to unchecked
                        $('#tbl-orders #checkbox-table-' + id).prop('checked', false);
                        notifSweetAlert('warning', 'Destination harus sama');
                        return false;
                    }
                }

                let dataArrCheck = {
                    ordersid: e.data[0].id,
                    numberorder: e.data[0].numberorders,
                    customername: e.data[0].customer.name,
                    destination: e.data[0].destination.name,
                    volume: e.data[0].volume,
                    weight: e.data[0].weight,
                    koli: e.data[0].koli,
                    content: e.data[0].content,
                }
                arrOrders.push(dataArrCheck);
                idOrders.push(e.data[0].id);
                getDataDetailOrders();


            }).fail(function(e) {
                console.log(e)
            })
        }

        const getDataDetailOrders = () => {
            $('#tbl-detail-manifests').html('')
            let noOrders = 1;
            arrOrders.map((x, i) => {
                $('#tbl-detail-manifests').append(
                    `
                    <tr>
                        <td>${noOrders++}</td>
                        <td>${x.numberorder}<input type="hidden" name="ordersid[]" value="${x.ordersid}"></td>
                        <td>${x.customername}</td>
                        <td>${x.destination}</td>
                        <td>${x.weight ?? x.volume}</td>
                        <td>${x.koli}</td>
                    </tr>
                    `
                )
            })
            totalItems();
            totalKg();
        }
        function totalItems() {
            $('#total-item').html('<b>Total Item :</b> '+arrOrders.length);
        }

        function totalKg() {
            let jumlahKg = []
            let jumlahKoli = []
            totalKgs = 0;
            totalKoli = 0;
            arrOrders.map((x, i) => {
                convert = Math.round(x.weight ?? x.volume);
                jumlahKg.push(convert);
                jumlahKoli.push(x.koli);
            })
            for (i in jumlahKg) {
                totalKgs += jumlahKg[i];
                totalKoli += jumlahKoli[i];
            }
            $('#total-jumlah-kg').html('<b>Total Berat : </b>'+totalKgs);
            $('#total-jumlah-koli').html('<b>Total Koli : </b>'+totalKoli);
        }
    </script>
@endsection
