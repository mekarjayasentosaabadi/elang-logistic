@extends('layout.app')
@section('title')
    <span>Surat Tugas</span>
    <small>/</small>
    <small>Edit</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                    <a href="{{ route('surattugas.index') }}" class="btn btn-warning btn-sm">
                        <li class="fa fa-undo"></li> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="#" id="formAddSuratTugas">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="noSuratTugas">No Tugas</label>
                                    <input type="text" name="noSuratTugas" id="noSuratTugas" class="form form-control"
                                        required>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="destination">Destination</label>
                                    <select name="destination" id="destination" class="form-control select2" onchange="">
                                        <option value="">-- Pilih Destination --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group ">
                                    <label for="vehicle">Kendaraan</label>
                                    <select name="vehicle" id="vehicle" class="form-control">
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach ($vehicle as $item)
                                            <option value="{{ $item->id }}">{{ $item->police_no }} | {{ $item->type == "1" ? "CDD BOX" : ($item->type == "2" ? "CDE BOX" : "GRANDMAX") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mt-1">
                                    <label for="driver">Driver</label>
                                    <select name="driver" id="driver" class="form-control">
                                        <option value="">-- Pilih Driver --</option>
                                        @foreach ($driver as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group  mt-1">
                                    <label for="description">Note</label>
                                    <textarea name="description" id="description" cols="30" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @if (Auth::user()->role_id == '1')
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Outlet Asal</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="outlet_id">Outlet Asal</label>
                                                <select name="outlet_id" id="outlet_id" class="form-control">
                                                    <option value="">Pilih Outlet Asal</option>
                                                    @foreach ($outlets as $outlet)
                                                        <option value="{{ $outlet->id }}"
                                                            {{ old('outlet_id') == $outlet->id ? 'selected' : '' }}>
                                                            {{ $outlet->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="row mt-2">
                            <div class="table-responsive">
                                <div class="col-12">
                                    <table class="table table-hover" id="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>No Manifest</th>
                                                <th>No SMD</th>
                                                <th>Destination</th>
                                                <th>Jumlah Manifest</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblListManifest">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-md">
                                    <li class="fa fa-save"></li>Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script>
        var urlBase = window.location.origin;
        let listArrDataManifest = [];
        let allId = [];


        $(document).ready(function() {
            listManifest($('#outlet_id').val());
        });

        $('.select2').select2();

        $('#outlet_id').change(function() {
            let outlet_id = $(this).val();
            listManifest(outlet_id);
        });

        function listManifest(x) {
            $.getJSON(urlBase + '/' + listRoutes['surattugas.manifest'].replace('{id}', x), function(e) {}).done(function(
                r) {
                getListManifest(r)
            }).fail(function(r) {
                console.log(r);
            })
        }

        const getListManifest = (x) => {
            if (x.data.dataManifest.length <= 0) {
                $('#tblListManifest').html('')
                listArrDataManifest = []
            } else {
                listArrDataManifest = []
                x.data.dataManifest.map((x) => {
                    let arrDataManifest = {
                        manifestId: x.id,
                        noManifest: x.manifestno,
                        noSmd: x.no_smd,
                        destination: x.destination.name,
                        jumlahManifest: x.detailmanifests.length,
                        notes: x.notes
                    }
                    listArrDataManifest.push(arrDataManifest);
                })
                console.log(listArrDataManifest);
                listDataManifest();
            }
        }
        const listDataManifest = () => {
            $('#tblListManifest').html('')

            let noUrut = 1;
            listArrDataManifest.map((x, i) => {
                $('#tblListManifest').append(
                    `
                    <tr>
                        <td><input type="checkbox" class="form-check-input cb-child" id="cb-child" value="${x.manifestId}"></td>
                        <td>${x.noManifest}</td>
                        <td>${x.noSmd}</td>
                        <td>${x.destination}</td>
                        <td>${x.jumlahManifest}</td>
                        <td>${x.notes}</td>
                    </tr>
                    `
                )
            })
        }
        //function get id checked item
        $('#table tbody').on('click', '.cb-child', function() {
            checkItem()
        })

        function checkItem() {
            allId = []
            let cbCheckedItem = $('#table tbody .cb-child:checked')
            $.each(cbCheckedItem, function(index, res) {
                allId.push(res.value)
            })
            console.log(allId);
        }

        $('#formAddSuratTugas').validate({
            rules: {
                'noSuratTugas': 'required',
                'destination': 'required',
            },
            submitHandler: function() {
                if (allId.length <= 0) {
                    var messageErrors = ['pilih manifest terlebih dahulu'];
                    notifSweetAlertErrors(messageErrors);
                } else {
                    $.ajax({
                        url: urlBase + '/' + listRoutes['surattugas.store'],
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            suratTugas: $('#noSuratTugas').val(),
                            destination_id: $('#destination').val(),
                            description: $('#description').val(),
                            vehicle_id: $('#vehicle').val(),
                            driver_id: $('#driver').val(),
                            suratjalan: allId,
                            outlet_id: $('#outlet_id').val()
                        },
                        success: function(e) {
                            if (e.meta.code == 200) {
                                notifSweetAlertSuccess(e.meta.message);
                                setTimeout(function() {
                                    location.replace(window.location.origin + '/surattugas')
                                }, 1500)
                            } else {
                                notifSweetAlertErrors(e.meta.message);
                            }
                        },
                        error: function(e) {
                            notifSweetAlertErrors(e.responseJSON.errors);
                        }
                    })
                }
            }
        })
    </script>
@endsection
