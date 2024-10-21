@extends('layout.app')
@section('title')
    <span>Surat Jalan</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Surat Jalan</h4>
                    <a href="{{ route('traveldocument.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>
                        Buat Surat Jalan</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tblSuratJalan">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Surat Jalan</th>
                                        <th>Destinations</th>
                                        <th>Jumlah Manifest</th>
                                        <th>Status Surat Jalan</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal List Detail Surat Jalan --}}
    <div class="modal fade" id="twoFactorAuthModal" tabindex="-1" aria-labelledby="twoFactorAuthTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg two-factor-auth">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 mx-50">
                    <form action="" id="formDetailTravel">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="nosuratjalan">Nomor Surat Jalan</label>
                                    <input type="text" name="nosuratjalan" id="nosuratjalan" class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="kendaraan">Kendaraan</label>
                                    <input type="text" name="kendaraan" id="kendaraan" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="driver">Driver</label>
                                    <input type="text" name="driver" id="driver" class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <input type="text" name="destination" id="destination" class="form-control" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-1">
                            <div class="col-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Manifest</th>
                                            <th>Jumlah AWB</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblListManifest">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- End Modal List Detail Surat Jalan --}}
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script>
        let iTravel;
        var table
        $(document).ready(function() {
            table = $('#tblSuratJalan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/delivery/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'travelno',
                        name: 'travelno'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'jml_manifest',
                        name: 'jml_manifest'
                    },
                    {
                        data: 'status_traveldocument',
                        name: 'status_traveldocument'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    },
                ]
            });
        });
        //delete Surat jalan
        function deleteTravelDocument(x, i) {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin akan menghapus surat jalan tersebut.?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.location.origin + '/' + listRoutes['traveldocument.delete'].replace(
                            '{id}', i),
                        type: "POST",
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(e) {
                            notifSweetAlertSuccess(e.meta.message);
                            table.ajax.reload()
                        },
                        error: function(e) {
                            console.log(e)
                        }
                    })
                }
            })
        }

        function getDetail(x, i){
            iTravel='';
            iTravel=i
            $.getJSON(window.location.origin + '/' + listRoutes['traveldocument.listDetail'].replace('{id}', i), function(e){
            }).done(function(e){
                detailTravel(e.data.traveldocument, e.data.detailtraveldocuments)
            }).fail(function(e){
                console.log(e);
            })
        }

        const detailTravel=(x, y)=>{
            let noUrut = 1;
            $('#tblListManifest').html('');
            form = document.getElementById('formDetailTravel');
            form.reset();
            $('#nosuratjalan').val(x.travelno);
            $('#kendaraan').val(x.vehicle.police_no);
            $('#driver').val(x.driver.name);
            $('#destination').val(x.destination.name);
            if(y.length >=0){
                y.map((x, i)=>{
                    $('#tblListManifest').append(
                        `
                        <tr>
                            <td>${noUrut++}</td>
                            <td>${x.manifestno}</td>
                            <td>${x.jml_awb}</td>
                        </tr>
                        `
                    )
                })
            }
        }
    </script>
@endsection
