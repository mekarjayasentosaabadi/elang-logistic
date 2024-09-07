@extends('layout.app')

@section('title')
    <span>Customer</span>
    <small>/</small>
    <small>Update</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Form Update Customer</h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-warning"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form action="#" method="POST" enctype="multipart/form-data" id="form-update-customer">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="code_customer">Kode Customer</label>
                                    <input type="text" name="code_customer" id="code_customer" class="form-control" placeholder="Kode Customer" value="{{ $customer->code_customer }}" disabled>
                                </div>
                                @if (auth()->user()->role_id == '1')
                                <div class="form-group mt-1">
                                    <label for="outlets">Outlets</label>
                                    <select name="outlets" id="outlets" class="form-control">
                                        <option value="">-- Pilih Outlet --</option>
                                        @foreach ($outlet as $item)
                                            <option value="{{ $item->id }}" {{ $customer->outlets_id == $item->id ? 'selected' : ''  }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-group mt-1">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Ex: PT. ABCD" value="{{ $customer->name }}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="080xxxxxx" value="{{ $customer->phone }}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ $customer->address }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-lg-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="xyz@elang.com" value="{{ $customer->email }}">
                                </div>
                                <div class="form-group mt-1">
                                    <label for="photos">Photos</label>
                                    <input type="file" name="photos" id="photos" class="form-control">
                                </div>
                                <div class="form-group mt-1">
                                    <img class="img-fluid rounded mb-2"
                                    {{-- src="{{ asset('storage/customer/')$customer->picures }}" height="110" --}}
                                    src="{{ asset( $customer->picures == null ? 'assets/img/img_default.jpg' : 'storage/customer/'.$customer->picures) }}"
                                    width="110" alt="User avatar" />
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
    <script src="{{ asset('assets/app-assets/vendor/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/notifsweetalert.js') }}"></script>
    <script type="text/javascript">
        let base = new URL(window.location.href);
        let path = base.pathname;
        let segment = path.split("/");
        let customerId = segment["2"];
        console.log(customerId);
        $('#form-update-customer').validate({
            rules:{
                'name': 'required',
                'phone': 'required',
                'address': 'required',
                'email': 'required',
                // 'photos': 'required'
            },
            submitHandler:function(){
                $.ajax({
                    url: window.location.origin +'/'+ listRoutes['customer.update'].replace('{id}', customerId ),
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-update-customer')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e){
                        notifSweetAlertSuccess(e.meta.message);
                        setTimeout(function(){
                            location.replace(window.location.origin + '/customer' );
                        }, 1500)
                    },
                    error: function(e){
                        if(e.status== 422){
                            notifSweetAlertErrors(e.responseJSON.message);
                        }
                    }
                })
            }
        })
    </script>
@endsection
