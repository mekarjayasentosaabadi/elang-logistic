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
                    <a href="{{ route('customer.index') }}" class="btn btn-warning"><li class="fa fa-undo"></li> Kembali </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-lg-2 col-sm-12">
                            <img class="img-fluid rounded mb-2"
                            {{-- src="{{ asset('storage/customer/')$customer->picures }}" height="110" --}}
                            src="{{ asset( $customer->picures == null ? 'assets/img/img_default.jpg' : 'storage/customer/'.$customer->picures) }}"
                            width="110" alt="User avatar" />
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
                    <button class="btn btn-primary btn-md" onclick="generatePrice()" id="btn-generate-price"><i
                            class="fa fa-recycle"></i> Generate Harga Customer</button>
                    <button class="btn btn-primary btn-md" id="btn-add-other-price" data-bs-toggle="modal"
                        data-bs-target="#exampleModalNewHargaManual"><i class="fa fa-plus"></i> Tambah Harga
                        Manual</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table" id="tbl-Price-customer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Service</th>
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
    {{-- Modal edit price --}}
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" id="form-change-pricecustomer">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Ubah Harga / Price</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="service">Service</label>
                                    <input type="text" name="service" id="service" class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="destination">Destination</label>
                                    <input type="text" name="destination" id="destination" class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" name="price" id="price" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary btn-md" type="submit"><i class="fa fa-save"></i> Perbaharui</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End Modal Edit Price --}}
    {{-- Modal new harga manual --}}
    <div class="modal fade" id="exampleModalNewHargaManual" tabindex="-1" aria-labelledby="exampleModalNewHargaManualTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalNewHargaManualTitle">Add Harga Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="#" id="formAddManualPrice">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                        <label for="outlet">Asal Outlet</label>
                                        <select name="outlet" id="outlet" class="form-control">
                                            <option value="">-- Pilih Outlet --</option>
                                            @foreach ($outlet as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="armada">Armada</label>
                                        <select name="armada" id="armada" class="form-control">
                                            <option value="">-- Pilih Armada --</option>
                                            <option value="1">Darat</option>
                                            <option value="2">Laut</option>
                                            <option value="3">Udara</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="destination">Destination</label>
                                        <select name="destination" id="destination" class="form-control">
                                            <option value="">-- Pilih Outlet --</option>
                                            @foreach ($destination as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="price">Price</label>
                                        <input type="text" name="price" id="price" class="form-control"
                                            placeholder="10000">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="minweight">Berat Minimal</label>
                                                <div class="input-group">
                                                    <input type="text" name="minweight" id="minweight"
                                                        class="form form-control">
                                                    <span class="input-group-text">Kg</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="minimumprice">Minimum Price</label>
                                        <input type="text" name="minimumprice" id="minimumprice" class="form-control"
                                            placeholder="20000">
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="estimation">Estimation</label>
                                        <input type="text" name="estimation" id="estimation" class="form-control"
                                            placeholder="1">
                                    </div>
                                    <div class="form-group mt-1">
                                        <label for="pricenext">Next Weight Price</label>
                                        <input type="text" name="pricenext" id="pricenext" class="form-control"
                                            placeholder="2000">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-md"><i class="fa fa-save"></i>
                                        Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
    {{-- End new harga manual --}}
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
        var icustomerprice;
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        });
        $(document).ready(function() {
            //get data customer price
            $.getJSON(window.location.origin + '/' + listRoutes['customer.getcustomerprice'].replace('{id}',
                customerId), function(e) {}).done(function(e) {
                    console.log(e)
                if (e.data.customerprice.length == 0) {
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
                    e.data.customerprice.map((x) => {
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
            }).fail(function(e) {
                console.log(e);
            })
        })
        //confirm generate price customer
        function generatePrice() {
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah anda yakin akan melakukan generate Harga Customer.?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: window.location.origin + '/' + listRoutes['customer.generatecustomerprice']
                            .replace('{id}', customerId),
                        type: "POST",
                        dataType: "JSON",
                        processData: false,
                        contentType: false,
                        success: function(e) {
                            notifSweetAlertSuccess(e.meta.message);
                            setTimeout(function() {
                                location.reload()
                            }, 1500)
                        },
                        error: function(e) {
                            console.log(e);
                        }
                    })
                }
            })
        }

        //get list price customer
        const getListPriceCustomer = () => {
            $('#tbody-price-customer').html('');
            let no = 1;
            listPriceCustomer.map((x, i) => {
                var armada;
                if (x.armada == 1) {
                    armada = 'Darat';
                } else if (x.armada == 2) {
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
                        <td>${ formatter.format(x.price)}</td>
                        <td>${x.estimation}</td>
                        <td><button data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="btn btn-primary btn-sm" onclick="changePrice(${x.icustomerprice},${x.price}, '${armada}', '${x.destination}')"><i class="fa fa-edit"></i></button></td>
                    </tr>
                    `
                )
            })
        }

        function changePrice(x, p, a, d) {
            $('#form-change-pricecustomer')[0].reset();
            icustomerprice = '';
            $('#price').val('')
            $('#service').val('')
            $('#destination').val('')
            $('#destination').val(d)
            $('#service').val(a)
            $('#price').val(p)
            icustomerprice = x;
        }

        $('#form-change-pricecustomer').validate({
            rules: {
                price: {
                    'required': true,
                    'number': true
                }
            },
            submitHandler: function() {
                $.ajax({
                    url: window.location.origin + '/' + listRoutes['customer.changeprice'].replace(
                        '{id}', icustomerprice),
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-change-pricecustomer')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e) {
                        notifSweetAlertSuccess(e.meta.message);
                        setTimeout(function() {
                            location.reload()
                        }, 1500)
                    },
                    error: function(e) {
                        console.log(e)
                    }
                })
            }
        })

        $('#formAddManualPrice').validate({
            rules: {
                'outlet': 'required',
                'armada': 'required',
                'destination': 'required',
                'price': 'required',
                'minweight': 'required',
                'minimumprice': 'required',
                'estimation': 'required',
                'pricenext': 'required'
            },
            submitHandler: function() {
                $.ajax({
                    url: window.location.origin + '/' + listRoutes['customer.addmanualprice'].replace('{id}', customerId),
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#formAddManualPrice')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e){
                        if(e.data.validate == false){
                            notifSweetAlertErrors(e.meta.message);
                        } else {
                            notifSweetAlertSuccess(e.meta.message);
                            setTimeout(function(){
                                location.reload()
                            }, 1500)
                        }
                    },
                    error: function(e){
                        console.log(e);
                    }
                })
            }
        })
    </script>
@endsection
