@extends('layout.app')
@section('title', 'Dashboard')
@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/charts/apexcharts.css">

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="fw-bolder mb-0" id="itemTrxToday">0</h2>
                        <p class="card-text" id="trxToday">Transaksi Hari Ini</p>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="fa fa-solid fa-truck font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="fw-bolder mb-0" id="itemTrxProcess">0</h2>
                        <p class="card-text" id="trxProcess">Transaksi Dalam Proses</p>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="fa fa-solid fa-money-bill-transfer font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="fw-bolder mb-0" id="itemIncomeThisMonth">0</h2>
                        <p class="card-text" id="incomeThisMonth">Pendapatan Bulan Ini</p>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i class="fa fa-solid fa-money-bill font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Grafik Transaksi Mingguan</h4>
                </div>
                <div class="card-body">
                    <div id="weekly-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Grafik Transaksi Tahun 2024</h4>
                </div>
                <div class="card-body">
                    <div id="monthly-chart"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top 10 Customer</h4>
                </div>
                <div class="card-body">
                    <div class="table">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Total Orders</th>
                                    <th>Total Transaksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-customer">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Peta Truck</h4>
                </div>
                <div class="card-body">
                    <div id="map" class="leaflet-container"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')

<script src="{{ asset('assets') }}/app-assets/vendors/js/charts/apexcharts.min.js"></script>
{{-- <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script> --}}

    <script>
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        });
        $(document).ready(function(){
            getData();
        })
        const getData=()=>{
            $.getJSON("{{ url('/getData') }}", function(e) {}).done(function(e) {
                $('#itemTrxToday').html(e.data.trxToday)
                $('#itemTrxProcess').html(e.data.trxProcess)
                $('#itemIncomeThisMonth').html(formatter.format(e.data.totalIncome))
                transaksiBulanan(e)
                transaksiMingguan(e)
                topCustomer(e)
            })
        }
        //transaksi bulanan
        function transaksiBulanan(e){
            let dataTransaksi = [];
            var bulan
            if(e.data.transaksiperbulan.length >= 0){
                    e.data.transaksiperbulan.map((x)=>{
                        if(x.bulan == 1){
                        bulan = 'Januari'
                        }else if(x.bulan == 2){
                            bulan = 'Februari'
                        }else if(x.bulan == 3){
                            bulan = 'Maret'
                        }else if(x.bulan == 4){
                            bulan = 'April'
                        }else if(x.bulan == 5){
                            bulan = 'Mei'
                        }else if(x.bulan == 6){
                            bulan = 'Juni'
                        }else if(x.bulan == 7){
                            bulan = 'Juli'
                        }else if(x.bulan == 8){
                            bulan = 'Agustus'
                        }else if(x.bulan == 9){
                            bulan = 'September'
                        }else if(x.bulan == 10){
                            bulan = 'Oktober'
                        }else if(x.bulan == 11){
                            bulan = 'November'
                        }else {
                            bulan = 'November'
                        }
                        let arrDataTransaksi = {
                            bulan: bulan,
                            transaksi: x.total
                        }
                        dataTransaksi.push(arrDataTransaksi)
                    })
                    console.log(dataTransaksi)
                }
                let transaksiData = []
                let bulanTransaksi = []
                dataTransaksi.map((x,i)=>{
                    transaksiData.push(x.transaksi)
                    bulanTransaksi.push(x.bulan)
                })
                var options = {
                    series: [{
                        name: 'Transaksi',
                        data: transaksiData
                    }, ],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: bulanTransaksi,
                    },
                    fill: {
                        opacity: 1
                    },

                };
                var chart = new ApexCharts(document.querySelector("#monthly-chart"), options);
                chart.render();
        }
        //transaksi weekly
        function transaksiMingguan(e){
            let dataTransaksiMingguan = [];
                let dataHariTransaksi = [];
            if(e.data.transaksimingguan.length >= 0){
                    e.data.transaksimingguan.map((x)=>{
                        dataTransaksiMingguan.push(x.total)
                        dataHariTransaksi.push(x.day_name)
                    })
                }
                var optionsWeekly = {
                    series: [{
                        name: 'Transaksi',
                        data: dataTransaksiMingguan
                    }, ],
                    chart: {
                        type: 'bar',
                        height: 350
                    },

                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    colors: ['#FFA500'],
                    xaxis: {
                        categories: dataHariTransaksi,
                    },
                    fill: {
                        opacity: 1
                    },

                };

                var chartWeekly = new ApexCharts(document.querySelector("#weekly-chart"), optionsWeekly);
                chartWeekly.render();
        }
        //top customer
        function topCustomer(e){
            console.log(e)
            if(e.data.topcustomer.length >= 0){
                let noUrut = 1;
                e.data.topcustomer.map((x, i)=>{
                    $('#tbody-customer').append(`
                        <tr>
                            <td>${noUrut++}</td>
                            <td>${x.name}</td>
                            <td>${x.total_transaksi}</td>
                            <td>${formatter.format(x.total_orders)}</td>
                        </tr>
                    `)
                })
            }
        }
        let latitude = -7.351564695753717
        let longitude = 108.63515798523339
        var zoomLevel = 13;
        var map = L.map('map').setView([latitude, longitude], zoomLevel);

        // Menambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Menambahkan marker di koordinat yang ditentukan
        var marker = L.marker([latitude, longitude]).addTo(map)
            .bindPopup('Lokasi Kantor.')
            .openPopup();
    </script>

@endsection
