@extends('layout.app')
@section('title')
    <span>Surat Jalan</span>
    <small>/</small>
    <small>Create</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"></h4>
                    <a href="{{ route('traveldocument.index') }}" class="btn btn-warning btn-sm">
                        <li class="fa fa-undo"></li> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <form action="#" id="formAddSuratJalan">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="noSuratJalan">No Surat Jalan</label>
                                    <input type="text" name="noSuratJalan" id="noSuratJalan" class="form form-control"
                                        required>
                                </div>

                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <select name="destination" id="destination" class="form-control select2"
                                        onchange="manifestOrder(this.value)">
                                        <option value="">-- Pilih Destination --</option>
                                        @foreach ($destination as $item)
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
                                                <th>Manifest No</th>
                                                <th>Jumlah Awb</th>
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

        $('.select2').select2();

        function manifestOrder(x) {
            $.getJSON(urlBase + '/' + listRoutes['traveldocument.manifestorder'].replace('{id}', x), function(e) {}).done(
                function(r) {
                    getListManifests(r)
                }).fail(function(r) {
                console.log(r);
            })
        }

        const getListManifests = (x) => {
            if (x.data.datamanifest.length <= 0) {
                $('#tblListManifest').html('')
                listArrDataManifest = []
            } else {
                listArrDataManifest = []
                x.data.datamanifest.map((x) => {
                    let arrDataManifest = {
                        manifestsId: x.id,
                        manifestNo: x.manifestno,
                        jumlahawb: x.jumlahawb,
                    }
                    listArrDataManifest.push(arrDataManifest);
                })
                listDataManifest();
            }
        }
        const listDataManifest = () => {
            $('#tblListManifest').html("")
            let noUrut = 1;
            listArrDataManifest.map((x, i) => {
                $('#tblListManifest').append(
                    `
                    <tr>
                        <td><input type="checkbox" class="form-check-input cb-child" id="cb-child" value="${x.manifestsId}"></td>
                        <td>${x.manifestNo}</td>
                        <td>${x.jumlahawb}</td>
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
        }

        $('#formAddSuratJalan').validate({
            rules: {
                'noSuratJalan': 'required',
                'vehicle': 'required',
                'driver': 'required',
                'destination': 'required'
            },
            submitHandler: function() {
                $.ajax({
                    url: urlBase + '/' + listRoutes['traveldocument.store'],
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        suratJalan: $('#noSuratJalan').val(),
                        kendaraan: $('#vehicle').val(),
                        driver: $('#driver').val(),
                        destination: $('#destination').val(),
                        description: $('#description').val(),
                        manifest: allId
                    },
                    success: function(e) {
                        notifSweetAlertSuccess(e.meta.message);
                        setTimeout(function() {
                            location.replace(window.location.origin + '/delivery')
                        }, 1500)
                    },
                    error: function(e) {
                        console.log(e);
                    }
                })
            }
        })
    </script>
@endsection
