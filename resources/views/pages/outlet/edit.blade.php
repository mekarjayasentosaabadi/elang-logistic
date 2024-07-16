@extends('layout.app')
@section('title')
    <span>Outlet</span>
    <small>/</small>
    <small>edit</small>
@endsection

@section('custom-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/app-assets/vendors/css/maps/leaflet.min.css">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Outlet</h4>
                    <a href="{{ route('outlet.index') }}" class="btn btn-warning btn-md"> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ url('/outlet/' . Crypt::encrypt($outlet->id)) }}" method="post" id="formValidate">
                        @csrf
                        @method('PATCH')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="ops">Operator</label>
                                    <select name="ops_id" id="ops" class="form-control">
                                        <option value="">-- Select Operator --</option>
                                        @foreach ($operator as $item)
                                            <option value="{{ $item->id }}" {{ $outlet->ops_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="location">Location</label>
                                    <select name="location_id" id="location" class="form-control">
                                        <option value="">-- Select location --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}" {{ $outlet->location_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="name">Nama Outlet</label>
                                    <input type="text" name="name" id="name" value="{{ $outlet->name }}"
                                        class="form-control" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ $outlet->phone }}"
                                        class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group mb-2">
                                            <label for="lat">Latitude</label>
                                            <input type="text" name="lat" id="lat" value="{{ $outlet->lat }}"
                                                class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group mb-2">
                                            <label for="long">Longitude</label>
                                            <input type="text" name="long" id="long" value="{{ $outlet->long }}"
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
                                    <input type="text" name="email" id="email" value="{{ $outlet->email }}"
                                        class="form-control" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="type">Jenis Outlet</label>
                                    <select name="type" class="form-control">
                                        <option value="">Pilih Jenis</option>
                                        <option value="1" {{ $outlet->type == '1' ? 'selected' : '' }}>Cabang
                                        </option>
                                        <option value="2" {{ $outlet->type == '2' ? 'selected' : '' }}>Agen</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="address">Address</label>
                                    <textarea name="address" class="form-control">{{ $outlet->address }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Ubah</button>
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
        var lat = $('#lat').val();
        var long = $('#long').val();
        $(() => {
            var mapCenter = [lat, long];

            // Initialize the map
            var map = L.map('map').setView(mapCenter, 8);

            // Set basemap tiles (choose a provider like OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // add 3 markers
            const markers = [lat, long]

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
