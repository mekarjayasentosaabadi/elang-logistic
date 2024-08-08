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
                    <a href="{{ route('surattugas.index') }}" class="btn btn-warning btn-sm"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="#" id="formAddSuratTugas">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="noSuratTugas">No Tugas</label>
                                    <input type="text" name="noSuratTugas" id="noSuratTugas" class="form form-control" required value="{{ $surattugas->nosurattugas }}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="destination">Destination</label>
                                    <select name="destination" id="destination" class="form-control" onchange="listSuratJalan(this.value)">
                                        <option value="">-- Pilih Destination --</option>
                                        @foreach ($destination as $item)
                                            <option value="{{ $item->id }}" {{ $surattugas->iddestination == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="form-group  mt-1">
                                        <label for="description">Note</label>
                                        <textarea name="description" id="description" cols="30" rows="2" class="form-control"></textarea>
                                    </div>
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
                                                <th>Jumlah Manifest</th>
                                                <th>Option</th>
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
                                <button type="submit" class="btn btn-primary btn-md" ><li class="fa fa-save"></li>Simpan</button>
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
        let base = new URL(window.location.href);
        let path = base.pathname;
        let segment = path.split("/");
        let suratTugasId = segment["2"];
        var urlBase = window.location.origin;
        let listArrDataSuratJalan = [];
        let allId = [];
        $(document).ready(function() {
            getList()
        });

        const getList = () =>{
            $.getJSON(urlBase + '/' + listRoutes['surattugas.getListSuratJalan'].replace('{id}', suratTugasId), function(e){}).done(function(r){
                listDetail(r);
            }).fail(function(e){
                console.log(e);
            })
        }

        const listDetail = (x) =>{
            $('#tblListSuratJalan').html('')
            let noUrut = 1;
            if(x.data.listSuratTugas.length >=0){
                x.data.listSuratTugas.map((x)=>{
                    $('#tblListSuratJalan').append(
                        `
                            <tr>
                                <td>${noUrut++}</td>
                                <td>${x.travelno}</td>
                                <td>${x.jml_manifest}</td>
                                <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusList(this, '${x.idsurattugas}')"><li class="fa fa-trash"></li></button></td>
                            </tr>
                        `
                    )
                })
            }
        }

        function hapusList(x,id){
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin akan menghapus Surat Jalan tersebut.?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.location.origin + '/' + listRoutes['surattugas.deleteList'].replace('{id}', id),
                        type: "POST",
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(e) {
                            notifSweetAlertSuccess(e.meta.message);
                            getList();
                        },
                        error: function(e) {
                            console.log(e)
                        }
                    })
                }
            })
        }
    </script>
@endsection
