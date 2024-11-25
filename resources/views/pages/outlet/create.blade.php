@extends('layout.app')
@section('title')
    <span>Outlet</span>
    <small>/</small>
    <small>create</small>
@endsection

@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/maps/leaflet.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Outlet</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('/outlet') }}" method="post" id="formValidate">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        class="form-control" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group mb-2">
                                            <label for="lat">Latitude</label>
                                            <input type="text" name="lat" id="lat" value="{{ old('lat') }}"
                                                class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-2">
                                            <label for="long">Longitude</label>
                                            <input type="text" name="long" id="long" value="{{ old('long') }}"
                                                class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-2 ">
                                        <button type="button" class="btn btn-warning mt-2 mb-2" data-bs-toggle="modal"
                                            data-bs-target="#default">
                                            <i class="fas fa-search"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade text-start" id="default" tabindex="-1"
                                            data-bs-backdrop="static" data-bs-keyboard="false"
                                            aria-labelledby="addressModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="addressModalLabel">Pilih Lokasi</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="map" style="height: 400px;"></div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="findlat">Latitude</label>
                                                                    <input type="text" name="findlat" id="findlat"
                                                                        class="form-control">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="findlong">Longitude</label>
                                                                    <input type="text" name="findlong" id="findlong"
                                                                        class="form-control">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" id="btnPilih" class="btn btn-primary"
                                                            data-bs-dismiss="modal">Pilih</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" value="{{ old('email') }}"
                                        class="form-control" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="type">Jenis Outlet</label>
                                    <select name="type" class="form-control">
                                        <option value="">Pilih Jenis</option>
                                        <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Cabang
                                        </option>
                                        <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Agen</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="address">Address</label>
                                    <textarea name="address" class="form-control">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('custom-js')
    <script src="{{ asset('assets') }}/app-assets/vendors/js/maps/leaflet.min.js"></script>

    <script>
        var formValidate = $('#formValidate');
        $(() => {
            var mapCenter = [-6.2765247, 106.9589698];

            // Initialize the map
            var map = L.map('map').setView(mapCenter, 8);

            // Set basemap tiles (choose a provider like OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // add 3 markers
            const markers = [-6.2765247, 106.9589698]

            marker = L.marker(markers, {
                draggable: true
            }).addTo(map).on('dragend', function(e) {
                var coord = e.target.getLatLng();
                $('#findlat').val(coord.lat);
                $('#findlong').val(coord.lng);
            });

            formValidate.validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    lat: {
                        required: true
                    },
                    long: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    address: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: 'Nama harus diisi'
                    },
                    phone: {
                        required: 'Phone harus diisi'
                    },
                    email: {
                        required: 'Email harus diisi'
                    },
                    lat: {
                        required: 'Latitude harus diisi'
                    },
                    long: {
                        required: 'Longitude harus diisi'
                    },
                    type: {
                        required: 'Jenis harus diisi'
                    },
                    address: {
                        required: 'Alamat harus diisi'
                    }
                }
            })

        })

        $('#btnPilih').click(() => {
            var lat = $('#findlat').val();
            var long = $('#findlong').val();

            $('#lat').val(lat);
            $('#long').val(long);
        })
    </script>
@endsection
