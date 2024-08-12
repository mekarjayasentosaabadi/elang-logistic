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
                        <button type="submit" class="btn btn-primary mt-2 float-end">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
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
