@extends('layout.app')
@section('title')
    <span>Update Resi</span>
    <small>/</small>
    <small>Index</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('update-resi.store') }}" id="form-update-resi" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="update_data">Update Data</label>
                                    <select name="update_data" id="update_data" class="form-control">
                                        <option value="">-- Pilih Data --</option>
                                        <option value="1">Surat Tugas</option>
                                        <option value="2">Surat Jalan/Manifest</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label for="noResi" id="label-no-resi">No Manifest</label>
                                    <select name="noResi[]" id="noResi" class="form-control select2" multiple
                                        data-ajax-cache="false">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                <div class="form-group mt-1">
                                    <label for="noResi">Status Resi</label>
                                    <select name="status_resi" id="status_resi" class="form-control">
                                        <option value="">-- Pilih Status Resi --</option>
                                        <option value="1">Tiba di</option>
                                        <option value="2">Di berangkatkan</option>
                                    </select>
                                </div>
                            </div>
                            @if (auth()->user()->role_id == '1')
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group mt-1">
                                        <label for="outlet_id">Outlet</label>
                                        <select name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="">-- Pilih Outlet --</option>
                                            @foreach ($outlet as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12">
                                <div class="form-group mt-1">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="is_arived" value="checked"
                                            name="is_arived">
                                        <label class="form-check-label" for="is_arived" id="label_is_arived">Apakah manifest
                                            sudah tiba?</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2 float-end"><li class="fa fa-save"></li> Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="pill" href="#home"
                                aria-expanded="true">Data Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="pill" href="#profile"
                                aria-expanded="false">Manifest</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home" aria-labelledby="home-tab"
                            aria-expanded="true">
                            <div class="table-responsive">
                                <table class="table" id="tbl-order">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No Order</th>
                                            <th>Destinasi</th>
                                            <th>Pengirim</th>
                                            <th>Penerima</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab"
                            aria-expanded="false">
                            <div class="table-responsive">
                                <table class="table " id="tbl-manifests">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Manifest No</th>
                                            <th>Destinations</th>
                                            <th>Jumlah AWB</th>
                                            <th>Status Manifest</th>
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
    </div>
@endsection

@section('custom-js')
    <script>
        var table
        $(document).ready(function() {
            table = $('#tbl-order').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/update-resi/getListOrder') }}",
                    type: 'GET',
                    data: function(data) {
                        data.update_data = $('#update_data').val();
                        data.noResi = $('#noResi').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'numberorders',
                        name: 'orders.numberorders',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'destination',
                        name: 'destination.name',
                        searchable: true
                    },
                    {
                        data: 'pengirim',
                        name: 'customer.name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'penerima',
                        name: 'orders.penerima',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'created_at',
                        name: 'orders.created_at',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'status_orders',
                        name: 'status_orders',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            table_manifest = $('#tbl-manifests').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/update-resi/getListManifest') }}",
                    type: 'GET',
                    data: function(data) {
                        data.update_data = $('#update_data').val();
                        data.noResi = $('#noResi').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'manifestno',
                        name: 'manifestno'
                    },
                    {
                        data: 'destination',
                        name: 'destination.name'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'option',
                        name: 'option'
                    },
                ]
            });

            $('#update_data').on('change', function() {
                table.ajax.reload();
                table_manifest.ajax.reload();
            });

            $('#noResi').on('change', function() {
                table.ajax.reload();
                table_manifest.ajax.reload();
            });

            $('.select2').attr('disabled', true);
            $('.select2').select2({
                placeholder: 'Pilih Data',
                ajax: {
                    url: "{{ route('update-resi.getResi') }}",
                    data: function(params) {
                        return {
                            q: params.term,
                            update_data: $('#update_data').val()
                        };
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: false
                },
                cache: false
            });

            $('#update_data').on('change', function() {
                if ($(this).val() == 1) {
                    $('#label-no-resi').text('No Surat Tugas');
                    $('#label_is_arived').text('Apakah surat tugas sudah sampai tujuan akhir?');
                } else {
                    $('#label-no-resi').text('No Manifest');
                    $('#label_is_arived').text('Apakah manifest sudah tiba?');
                }
                $('.select2').attr('disabled', false);
                $('#noResi').val(null).trigger('change');
                // reset select2
                $('#noResi').empty();
            });

            $('#form-update-resi').validate({
                rules: {
                    update_data: {
                        required: true
                    },
                    noResi: {
                        required: true
                    },
                    status_resi: {
                        required: true
                    },
                    outlet_id: {
                        required: true
                    }
                },
                messages: {
                    update_data: {
                        required: 'Data tidak boleh kosong'
                    },
                    noResi: {
                        required: 'Data tidak boleh kosong'
                    },
                    status_resi: {
                        required: 'Data tidak boleh kosong'
                    },
                    outlet_id: {
                        required: 'Data tidak boleh kosong'
                    }
                },
            });
        });
    </script>
@endsection
