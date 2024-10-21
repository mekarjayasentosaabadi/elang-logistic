@extends('layout.app')
@section('title')
    <span>Pengguna</span>
    <small>/</small>
    <small>create</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Pengguna</h4>
                    <a href="{{ url('/user') }}" class="btn btn-warning"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form id="form-create-user" action="{{ url('user/store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="role_id">Role</label>
                                    <select name="role_id" id="role_id" class="form-control">
                                        <option value="" hidden>Pilih Role</option>
                                        @if (Auth::user()->role_id == "1")
                                            <option value="1">Superadmin</option>
                                            <option value="2">Admin</option>
                                            <option value="3">Courier</option>
                                            <option value="5">Driver</option>
                                        @elseif(Auth::user()->role_id == "2" && $isAdmin->type == '1')
                                            <option value="5">Driver</option>
                                            <option value="3">Courier</option>
                                        @elseif(Auth::user()->role_id == "2" && $isAdmin->type == '2')
                                            <option value="5">Driver</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                                @if (Auth::user()->role_id == "1")
                                    <div class="form-group mb-3 outlet_id">
                                        <label for="outlet_id">Outlet</label>
                                        <select name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="" hidden>Pilih Outlet</option>
                                                @foreach ($outlets as $outlet)
                                                    <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end"><li class="fa fa-save"></li> Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script src="{{ asset('assets/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.outlet_id').hide()
            $('#role_id').change(function () {
                var role = $('#role_id').val()
                console.log(role);
                if (role == "2" || role == '3' || role == '5') {
                    $('.outlet_id').show()
                }else{
                    $('.outlet_id').hide()
                }
            })

            $('#form-create-user').validate({
                rules:{
                    'name'       : 'required',
                    'role_id'    : 'required',
                    'email'      : 'required',
                    'outlet_id'  : 'required',

                },
                messages:{
                    'name'      : "Nama harus diisi.",
                    'role_id'   : "Pilih salah satu role.",
                    'email'     : "Email harus diisi.",
                    'outlet_id' : "Pilih salah outlet."
                },
            })
        });
    </script>
@endsection
