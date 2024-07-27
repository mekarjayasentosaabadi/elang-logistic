@extends('layout.app')

@section('title')
    <span>Customer</span>
    <small>/</small>
    <small>Detail</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Detail Customer</h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-warning"> Kembali </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <img src="{{ Storage::url('customer/' . $customer->pictures) }}" alt=""
                                class="img-thumnail">
                        </div>
                        <div class="col-md-10 col-lg-10 col-sm-12">
                            <table class="table ">
                                <tr>
                                    <th width="20%">Nama</th>
                                    <td>: {{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>: {{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>: {{ $customer->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Harga Customer</h4>
                    <button class="btn btn-primary btn-md" onclick="generatePrice()" id="btn-generate-price"><i class="fa fa-recycle"></i> Generate Harga Customer</button>
                    <button class="btn btn-primary btn-md" id="btn-add-other-price"><i class="fa fa-plus"></i> Tambah Harga Manual</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table" id="tbl-Price-customer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Armada</th>
                                            <th>Tujuan / Destination</th>
                                            <th>Price</th>
                                            <th>Estimation</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-price-customer">

                                    </tbody>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        let base = new URL(window.location.href);
        let path = base.pathname;
        let segment = path.split("/");
        let customerId = segment["2"];
        let listPriceCustomer = [];
        $(document).ready(function(){
            //get data customer price
            $.getJSON(window.location.origin +'/'+listRoutes['customer.getcustomerprice'].replace('{id}',customerId), function(e){
            }).done(function(e){
                if(e.data.customerprice.length == 0){
                    $('#tbody-price-customer').html(
                        `
                        <tr>
                            <td colspan="6" class="text-center">Belum Ada Harga</td>
                        </tr>
                        `
                    )
                    $('#btn-generate-price').removeClass('hidden')
                    $('#btn-add-other-price').addClass('hidden')
                } else {
                    $('#btn-add-other-price').removeClass('hidden')
                    $('#btn-generate-price').addClass('hidden')
                    e.data.customerprice.map((x)=>{
                        let dataPriceCustomer = {
                            icustomerprice: x.id,
                            armada: x.armada,
                            destination: x.destination.name,
                            price: x.price,
                            estimation: x.estimation
                        }
                        listPriceCustomer.push(dataPriceCustomer)
                    })
                    getListPriceCustomer()
                }
            }).fail(function(e){
                console.log(e);
            })
        })
        //confirm generate price customer
        function generatePrice(){
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin akan melakukan generate Harga Customer.?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result)=>{
                if(result.isConfirmed){
                    $.ajax({
                        url: window.location.origin + '/' + listRoutes['customer.generatecustomerprice'].replace('{id}', customerId),
                        type: "POST",
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(e){
                            notifSweetAlertSuccess(e.meta.message);
                            setTimeout(function(){
                                location.reload()
                            }, 1500)
                        },
                        error: function(e){
                            console.log(e);
                        }
                    })
                }
            })
        }

        //get list price customer
        const getListPriceCustomer=()=>{
            $('#tbody-price-customer').html('');
            let no = 1;
            listPriceCustomer.map((x,i)=>{
                var armada;
                if(x.armada== 1){
                    armada = 'Darat';
                }else if(x.armada== 2){
                    armada = 'Laut';
                } else {
                    armada = 'Udara';
                }
                $('#tbody-price-customer').append(
                    `
                    <tr>
                        <td>${no++}</td>
                        <td>${armada}</td>
                        <td>${x.destination}</td>
                        <td>${x.price}</td>
                        <td>${x.estimation}</td>
                        <td><button class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    `
                )
            })
        }
    </script>
@endsection
