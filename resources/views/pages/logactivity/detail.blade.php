@extends('layout.app')
@section('title')
    <span>Log Activity</span>
    <small>/</small>
    <small>Detail</small>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card invoice-preview-card">
                <div class="card-body invoice-padding pb-0">
                    <!-- Header starts -->
                    <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                        <div>
                            <div class="logo-wrapper">

                                <h3 class="text-primary invoice-logo">User Detail</h3>
                            </div>
                            <p class="card-text mb-25">Nama Pengguna : <b id="namaUser"></b> </p>
                            <p class="card-text mb-25">Email : <b id="email"> </b> </p>
                            <p class="card-text mb-25">Phone Number : <b id="phone"> </b> </p>
                            <p class="card-text mb-25">Path : <b id="path"> </b> </p>
                            <p class="card-text mb-25">Method : <b id="method"> </b> </p>
                            <p class="card-text mb-25">IP Address : <b id="ipaddress"> </b> </p>
                            <p class="card-text mb-25">Tanggal Akses : <b id="tanggalaksess"> </b> </p>
                            <p class="card-text mb-25">Perangkat  : <b id="perangkat"> </b> </p>
                            <p class="card-text mb-25">Role : </p>
                            <p class="card-text mb-0">Payload : <b id="payloads"></b> </p>

                        </div>
                        <div class="mt-md-0 mt-2">
                            <a href="{{ route('logactivity.index') }}" class="btn btn-warning btn-sm"><li class="fa fa-undo"></li> Kembali </a>
                        </div>
                    </div>
                    <!-- Header ends -->
                </div>

                <!-- Invoice Description ends -->

                <hr class="invoice-spacing" />

                <!-- Invoice Note starts -->
                <div class="card-body invoice-padding pt-0">
                    <div class="row">
                        <div class="col-12">
                            <span class="fw-bold">Note:</span>
                            <span></span>
                        </div>
                    </div>
                </div>
                <!-- Invoice Note ends -->
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    {{-- <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script> --}}
    <script>
        let base = new URL(window.location.href);
        let path = base.pathname;
        let segment = path.split("/");
        let usersid = segment["2"];
        $(document).ready(function() {
            $.getJSON(window.location.origin + '/' + listRoutes['logactivity.getDetail'].replace('{id}', usersid), function(){

            }).done(function(e){
                console.log(e);
                $('#namaUser').html(e.data[0].user.name)
                $('#email').html(e.data[0].user.email)
                $('#phone').html(e.data[0].user.phone)
                $('#path').html(e.data[0].path)
                $('#method').html(e.data[0].method)
                $('#ipaddress').html(e.data[0].ip_address)
                $('#tanggalaksess').html(e.data[0].created_at)
                $('#perangkat').html(e.data[0].user_agent)
                $('#payloads').html(e.data[0].description)
            }).fail(function(e){
                console.log(e);
            })
        });
    </script>
@endsection
