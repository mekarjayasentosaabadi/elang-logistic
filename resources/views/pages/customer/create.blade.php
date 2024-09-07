@extends('layout.app')

@section('title')
    <span>Customer</span>
    <small>/</small>
    <small>Create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Add Customer</h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-warning"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" enctype="multipart/form-data" id="form-add-customer">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="code_customer">Kode Customer</label>
                                    <input type="text" name="code_customer" id="code_customer" class="form-control" placeholder="" disabled>
                                    <input type="checkbox" name="otomatiscode" id="otomatiscode" class="form-check-input" value="1" onchange="codeOtomatis(this, value)"> Kode Customer Manual
                                    <input type="hidden" name="kodecustomer" id="kodecustomer" value="1">
                                </div>
                                @if (auth()->user()->role_id == '1')
                                <div class="form-group mt-1">
                                    <label for="outlets">Outlets</label>
                                    <select name="outlets" id="outlets" class="form-control">
                                        <option value="">-- Pilih Outlet --</option>
                                        @foreach ($outlet as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group mt-1">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Example : PT. ABC">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="080xxxxxx">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="xyz@elang.com">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="photos">Photos</label>
                                    <input type="file" name="photos" id="photos" class="form-control">
                                </div>
                                <div class="form-group mt-1">
                                    <button type="submit" class="btn btn-primary btn-md pull-right"><li class="fa fa-save"></li> Simpan</button>
                                </div>
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
    <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        var baseUrl = window.location.origin;
        $('#form-add-customer').validate({
            rules:{
                'name': 'required',
                'phone': 'required',
                'address': 'required',
                'email': 'required',
                // 'photos': 'required'
            },
            submitHandler:function(){
                $.ajax({
                    url: baseUrl+'/customer/save',
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-add-customer')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e){
                        notifSweetAlertSuccess(e.meta.message);
                        setTimeout(function(){
                            location.replace(baseUrl + '/customer' );
                        }, 1500)
                    },
                    error: function(e){
                        console.log(e)
                        if(e.status== 422){
                            notifSweetAlertErrors(e.responseJSON.message);
                        }
                    }
                })
            }
        })
        function detailCustomer(e){
            $('#code_customer').val(e.data[0].code_customer)
        }

        function codeOtomatis(x, val){
            var id = $('#kodecustomer').val();
            id == "1" ? $('#code_customer').prop("disabled", false) && $('#kodecustomer').prop("value", "2") : $('#code_customer').prop("disabled", true) && $('#kodecustomer').prop("value", "1")
        }
    </script>

@endsection
