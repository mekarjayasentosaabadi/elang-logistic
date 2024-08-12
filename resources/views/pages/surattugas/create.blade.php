@extends('layout.app')
@section('title')
    <span>Surat Tugas</span>
    <small>/</small>
    <small>Create</small>
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
                                    <select name="destination" id="destination" class="form-control select2"
                                        onchange="listSuratJalan(this.value)">
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
                                            <option value="{{ $item->id }}">{{ $item->police_no }}</option>
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
                        <div class="row mt-2">
                            <div class="table-responsive">
                                <div class="col-12">
                                    <table class="table table-hover" id="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>No Surat Jalan</th>
                                                <th>Destination</th>
                                                <th>Jumlah Manifest</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tblListSuratJalan">

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
        let listArrDataSuratJalan = [];
        let allId = [];


        $('.select2').select2();

        function listSuratJalan(x) {
            $.getJSON(urlBase + '/' + listRoutes['surattugas.suratjalan'].replace('{id}', x), function(e) {}).done(function(
                r) {
                getListSuratJalan(r)
            }).fail(function(r) {
                console.log(r);
            })
        }

        const getListSuratJalan = (x) => {
            console.log(x)
            if (x.data.dataSuratJalan.length <= 0) {
                $('#tblListSuratJalan').html('')
                listArrDataSuratJalan = []
            } else {
                listArrDataSuratJalan = []
                x.data.dataSuratJalan.map((x) => {
                    let arrDataSuratJalan = {
                        suratJalanId: x.id,
                        noSuratJalan: x.travelno,
                        destination: x.destination,
                        jumlahManifest: x.jml_manifest,
                    }
                    listArrDataSuratJalan.push(arrDataSuratJalan);
                })
                listDataManifest();
            }
        }
        const listDataManifest = () => {
            $('#tblListSuratJalan').html('')

            let noUrut = 1;
            listArrDataSuratJalan.map((x, i) => {
                $('#tblListSuratJalan').append(
                    `
                    <tr>
                        <td><input type="checkbox" class="form-check-input cb-child" id="cb-child" value="${x.suratJalanId}"></td>
                        <td>${x.noSuratJalan}</td>
                        <td>${x.destination}</td>
                        <td>${x.jumlahManifest}</td>
                        <td>-</td>
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
                    var messageErrors = ['pilih surat jalan terlebih dahulu'];
                    notifSweetAlertErrors(messageErrors);
                } else {
                    $.ajax({
                        url: urlBase + '/' + listRoutes['surattugas.store'],
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            suratTugas: $('#noSuratTugas').val(),
                            destination: $('#destination').val(),
                            description: $('#description').val(),
                            suratjalan: allId
                        },
                        success: function(e) {
                            console.log(e)
                            notifSweetAlertSuccess(e.meta.message);
                            setTimeout(function() {
                                location.replace(window.location.origin + '/surattugas')
                            }, 1500)
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
