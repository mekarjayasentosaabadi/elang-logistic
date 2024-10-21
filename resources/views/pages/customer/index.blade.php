@extends('layout.app')

@section('title')
    <span>Customer</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Customer</h4>
                    <a href="{{ route('customer.create') }}" class="btn btn-primary"><li class="fa fa-plus"></li> Tambah Customer</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tbl-customer">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Customer</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#tbl-customer').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('/customer/getAll') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                    },
                    {
                        data: 'code_customer',
                        name: 'code_customer'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'toogle',
                        name: 'toogle'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi'
                    }
                ]
            });
        });

        function changeStatus(txt, i){
            console.log(i)
            var baseUrl = window.location.origin;
            $.ajax({
                url: baseUrl+'/'+listRoutes['customer.changestatus'].replace('{id}',i),
                type: "POST",
                dataType: "JSON",
                processData: false,
                contentType: false,
                success: function(e){
                    notifSweetAlertSuccess(e.meta.message);
                },
                error: function(e){
                    alert('Gagal mengeksekusi data.!')
                }
            })
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Kamu yakin?',
                text: "Apakah kamu yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/customer/' + id + '/delete';
                }
            })
        }
    </script>
@endsection
