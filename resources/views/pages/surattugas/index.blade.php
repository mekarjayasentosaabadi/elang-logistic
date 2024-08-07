@extends('layout.app')
@section('title')
    <span>Surat Tugas</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Surat Tugas</h4>
                    <a href="{{ route('surattugas.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i>
                        Buat Surat Tugas</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table " id="tblSuratTugas">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>No Surat Tugas</th>
                                        <th>Jumlah Surat Jalan</th>
                                        <th>Destination</th>
                                        <th>Status</th>
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
            table = $('#tblSuratTugas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/surattugas/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'nosurattugas',
                        name: 'nosurattugas'
                    },
                    {
                        data: 'jumlah_surat_tugas',
                        name: 'jumlah_surat_tugas'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'option',
                        name: 'option'
                    }
                ]
            });
        });
    </script>
@endsection
