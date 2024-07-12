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
                                        <option value="1">Superadmin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Courier</option>
                                        <option value="4">Driver</option>
                                        <option value="5">Customer</option>
                                        <option value="6">Directur</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
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
            $('#form-create-user').validate({
                rules:{
                    'name'       : 'required',
                    'role_id'    : 'required',
                    'email'      : 'required'

                },
                messages:{
                    'name'    : "Nama harus diisi.",
                    'role_id' : "Pilih salah satu.",
                    'email'   : "Email harus diisi."
                },
            })
        });
    </script>
@endsection
