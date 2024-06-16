@extends('layout.app')
@section('title', 'Dashboard')
@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/maps/leaflet.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/charts/apexcharts.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-4 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="fw-bolder mb-0">0</h2>
                        <p class="card-text">Transaksi Hari Ini</p>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="cpu" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="fw-bolder mb-0">0</h2>
                        <p class="card-text">Transaksi Dalam Proses</p>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="cpu" class="font-medium-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="fw-bolder mb-0">0</h2>
                        <p class="card-text">Pendapatan Bulan Ini</p>
                    </div>
                    <div class="avatar bg-light-primary p-50 m-0">
                        <div class="avatar-content">
                            <i data-feather="cpu" class="font-medium-5"></i>
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
                                    <th>Total Transaksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>PT ABC</td>
                                    <td>100</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>CV MSA</td>
                                    <td>94</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>PT XYZ</td>
                                    <td>90</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>PT Indonesia Jaya</td>
                                    <td>80</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>PT Makmur Sejahtera</td>
                                    <td>70</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>PT Sejahtera</td>
                                    <td>60</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>PT Maju Terus</td>
                                    <td>50</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>PT Jaya Abadi</td>
                                    <td>40</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>PT Jaya Makmur</td>
                                    <td>30</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>PT Jaya Sentosa</td>
                                    <td>20</td>
                                </tr>
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
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('assets') }}/app-assets/vendors/js/maps/leaflet.min.js"></script>

    <script src="{{ asset('assets') }}/app-assets/vendors/js/charts/apexcharts.min.js"></script>

    <script>
        var options = {
            series: [{
                name: 'Transaksi',
                data: [560, 780, 640, 329, 230, 470, 0, 0, 0, 0, 0, 0]
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            },
            fill: {
                opacity: 1
            },

        };

        var chart = new ApexCharts(document.querySelector("#monthly-chart"), options);
        chart.render();

        var optionsWeekly = {
            series: [{
                name: 'Transaksi',
                data: [560, 780, 640, 0, 0, 0, 0]
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
                categories: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            },
            fill: {
                opacity: 1
            },

        };

        var chartWeekly = new ApexCharts(document.querySelector("#weekly-chart"), optionsWeekly);
        chartWeekly.render();

        $(() => {
            var mapCenter = [-6.2765247, 106.9589698];

            // Initialize the map
            var map = L.map('map').setView(mapCenter, 8);

            // Set basemap tiles (choose a provider like OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // add 3 markers
            const markers = [
                [-6.2765247, 106.9589698],
                [-6.93648, 107.6681201],
                [-7.3028752, 112.7428974],
            ];

            const truckIcon = L.icon({
                iconUrl: "{{ asset('assets') }}/img/truck.png",
                iconSize: [50, 50],
                iconAnchor: [25, 25],
                popupAnchor: [0, -25]
            });

            // popup content
            const popupContent = `
                <div>
                    No. Polisi: B 1234 ABC</br>
                    No. SJ : 123456<br/>
                    Total Barang: 100<br/>
                    Total Manifest: 10<br/>
                </div>
            `;

            markers.forEach(marker => {
                L.marker(marker, {
                    icon: truckIcon
                }).addTo(map).bindPopup(popupContent);
            });

        })
    </script>

@endsection
