@extends('layout.app')
@section('title')
    <span>Outlet</span>
    <small>/</small>
    <small>create</small>
@endsection

@section('custom-css')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Outlet</h4>
                    <a href="{{ route('outlet.index') }}" class="btn btn-warning btn-md"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="{{ url('/outlet') }}" method="post" id="formValidate">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="location">Location</label>
                                    <select name="location_id" id="location" class="form-control select2">
                                        <option value="">-- Select location --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="name">Nama Outlet</label>
                                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                                        class="form-control" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                        class="form-control" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="lat">Latitude</label>
                                            <input type="text" name="lat" id="lat" value="{{ old('lat') }}"
                                                class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="long">Longitude</label>
                                            <input type="text" name="long" id="long" value="{{ old('long') }}"
                                                class="form-control" readonly required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div id="maps" class="leaflet-container"></div>
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
                        <button type="submit" class="btn btn-primary float-end"><li class="fa fa-save"></li> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('custom-js')

    <script>
        let markers;
        let latitude = -7.351564695753717
        let longitude = 108.63515798523339
        var zoomLevel = 13;
        var maps = L.map('maps').setView([latitude, longitude], zoomLevel);

        // Menambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(maps);

        // Menambahkan marker di koordinat yang ditentukan
        function marker(latitude, longitude) {
            // var marker
            if(markers != undefined){
                markers.remove()
            }
            markers = L.marker([latitude, longitude]).addTo(maps)
                .bindPopup('Lokasi Kantor.')
                .openPopup();
        }

        maps.on('click', function(e) {
            let latitude = e.latlng.lat.toString().substring(0, 15);
            let longitude = e.latlng.lng.toString().substring(0, 15);
            $('#lat').val(latitude);
            $('#long').val(longitude);
            updateMarker(latitude, longitude);
        })
        function updateMarker(latitude, longitude) {
            marker(latitude, longitude);
        }
        var formValidate = $('#formValidate');
        $(document).ready(function(){
            $('.select2').select2();

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
    </script>
@endsection
