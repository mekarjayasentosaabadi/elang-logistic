@extends('layout.app')

@section('title')
    <span>Profile</span>
    <small>/</small>
    <small>Index</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Profile</h4>
                    </div>
                    <div class="card-body">
                        <img class="img-fluid rounded mb-2"
                            src="{{ asset('assets') }}/app-assets/images/portrait/small/avatar-s-11.jpg" height="110"
                            width="110" alt="User avatar" />
                        <div class="col-lg-6 col-md-12 mb-1 mb-sm-0">
                            <label for="formFile" class="form-label">Change Pictures</label>
                            <input class="form-control" type="file" id="formFile" />
                        </div>
                        <button class="btn btn-primary btn-md mt-1" type="button">
                            <li class="fa fa-save"></li> Upload
                        </button>
                        <hr>
                        <ul class="nav nav-tabs mt-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"
                                    aria-controls="home" role="tab" aria-selected="true">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile"
                                    aria-controls="profile" role="tab" aria-selected="false">Password</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home" aria-labelledby="home-tab" role="tabpanel">
                                <form action="#" method="POST" id="form-update-profile">
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" required value="{{ $profile->name }}">
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" required value="{{ $profile->email }}">
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">Phone Number</label>
                                        <input type="text" name="phonenumber" id="phonenumber" class="form-control" required value="{{ $profile->phone }}">
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">Address</label>
                                        <textarea name="address" id="address" cols="30" rows="3" class="form-control">{{ $profile->address }}</textarea>
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <button class="btn btn-primary btn-md" type="submit">
                                            <li class="fa fa-save"></li> Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                <form action="#" method="POST" id="form-update-password">
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">Old Password</label>
                                        <input type="password" name="oldpassword"id="oldpassword" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">New Password</label>
                                        <input type="password" name="newpassword"id="newpassword" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <label for="">Confirm Password</label>
                                        <input type="password" name="confirmpassword"id="confirmpassword" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-5 col-xl-5 col-sm-12 mt-1">
                                        <button class="btn btn-primary btn-md" type="submit"><li class="fa fa-save"></li> Change Password</button>
                                    </div>

                                </form>
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
        var baseUrl = window.location.origin;
        $('#form-update-profile').validate({
            rules: {
                'name': 'required',
                'email': 'required',
                'address': 'required'
            },
            submitHandler: function () {
                $.ajax({
                    url: baseUrl +'/'+ listRoutes['profile.update'],
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-update-profile')[0]),
                    processData: false,
                    contentType: false,
                    success: function(e){
                        notifSweetAlertSuccess(e.meta.message);
                    },
                    error: function(e){
                        if(e.status== 422){
                            notifSweetAlertErrors(e.responseJSON.errors);
                        }
                    }
                })
            }
        })

        //update password
        $('#form-update-password').validate({
            rules: {
                'oldpassword': 'required',
                'newpassword': 'required',
                confirmpassword: {
                    required: true,
                    equalTo: "#newpassword"
                }
            },
            submitHandler: function(){
                $.ajax({
                    url : baseUrl + '/' + listRoutes['profile.changepassword'],
                    type: "POST",
                    dataType: "JSON",
                    data: new FormData($('#form-update-password')[0]),
                    contentType: false,
                    processData: false,
                    success: function(e){
                        if(e.meta.code == 422){
                            notifSweetAlertErrors(e.meta.message);
                        }else{
                            notifSweetAlertSuccess(e.meta.message);
                        }
                    },
                    error: function(e){
                        if(e.status== 422){
                            notifSweetAlertErrors(e.responseJSON.errors);
                        }
                    }
                })
            }
        })
    </script>
@endsection
