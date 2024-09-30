@extends('layout.app')

@section('title')
    <span>Transaksi</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter Transaksi</h4>
                </div>
                <div class="card-body">
                    <div>
                        <form action="" method="POST" id="form-filter-order">
                            <div class="row mt-2" id="">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_order">Status Order</label>
                                        <select name="status_order" id="status_order" class="form-control">
                                            <option value="">Pilih Status Order</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Process</option>
                                            <option value="3">Done</option>
                                            <option value="4">Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="destinasi">Destinasi</label>
                                        <select name="destinasi" id="destinasi" class="form-control">
                                            <option value="">Pilih Destinasi</option>
                                            @foreach ($destinations as $destination)
                                                <option value="{{ $destination->id }}">{{ $destination->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2" id="">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="keterlambatan">Keterlambatan</label>
                                        <select name="keterlambatan" id="keterlambatan" class="form-control">
                                            <option value="">Pilih Keterlambatan</option>
                                            <option value="Terlambat">Terlambat</option>
                                            <option value="Hampir Terlambat">Hampir Terlambat</option>
                                            <option value="Masih Dalam Estimasi">Masih Dalam Estimasi</option>
                                            <option value="Tepat Waktu">Tepat Waktu</option>
                                            <option value="Belum Ada Estimasi">Belum Ada Estimasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-end">
                                <button type="submit" class="btn btn-primary"><i data-feather="eye"
                                        class="font-medium-3 me-50"></i>Lihat</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Transaksi</h4>
                    @if (Auth::user()->role_id != '4')
                        <a href="{{ url('/order/create') }}" class="btn btn-primary">
                            <li class="fa fa-plus"></li> Tambah Tansaksi
                        </a>
                    @endif
                </div>
                <div class="card-body">
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
                                    <th>Catatan</th>
                                    <th>Status</th>
                                    <th>Estimasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        const idOrder =
            "{{ session()->has('id') ? session()->get('id') : '' }}";
        $(document).ready(function() {
            // if id order is not null then create hyperlink to print order
            if (idOrder != '') {
                //pindah halaman baru dengan target _blank
                // window.location.href = "{{ url('/order') }}/" + idOrder + "/print";
                window.open("{{ url('/order') }}/" + idOrder + "/print", "_blank");
            }

            $('#destinasi').select2();


            $('#tbl-order').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/order/getAll') }}",
                    type: 'GET'
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
                        data: 'note',
                        name: 'note',
                    },
                    {
                        data: 'status_orders',
                        name: 'status_orders',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'estimation',
                        name: 'estimation',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });



            $('#form-filter-order').on('submit', function(e) {
                e.preventDefault();

                var outlet_id = $('#outlet_id').val();
                var destination_id = $('#destination_id').val();
                var keterlambatan = $('#keterlambatan').val();
                var status = $('#status').val();

                $('#tbl-order').DataTable().destroy();

                $('#tbl-order').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('/order/getAll') }}",
                        type: 'GET',
                        data: {
                            destinasi: $('#destinasi').val(),
                            status_order: $('#status_order').val(),
                            keterlambatan: $('#keterlambatan').val(),
                        },
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
                            data: 'note',
                            name: 'note',
                        },
                        {
                            data: 'status_orders',
                            name: 'status_orders',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'estimation',
                            name: 'estimation',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'aksi',
                            name: 'aksi',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

            });



        });
    </script>
@endsection
