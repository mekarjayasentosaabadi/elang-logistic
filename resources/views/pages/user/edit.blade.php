@extends('layout.app')
@section('title')
    <span>Pengguna</span>
    <small>/</small>
    <small>Edit</small>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Pengguna</h4>
                    <a href="{{ url('/user') }}" class="btn btn-warning"><li class="fa fa-undo"></li> Kembali</a>
                </div>
                <div class="card-body">
                    <form id="form-edit-user" action="{{ url('/user/' . Crypt::encrypt($user->id)) }}" method="post">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="name">Nama</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $user->name }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="role_id">Role</label>
                                    @if (Auth::user()->role_id == "2" && ($user->role_id == '1' || $user->role_id == '2' || $user->role_id == '4' || $user->role_id == '6'))
                                        @if ($user->role_id == '1')
                                            <input type="text" class="form-control" value="Superadmin" readonly>
                                        @elseif ($user->role_id == '2')
                                            <input type="text" class="form-control" value="Admin" readonly>
                                        @elseif ($user->role_id == '4')
                                            <input type="text" class="form-control" value="Customer" readonly>
                                        @elseif ($user->role_id == '6')
                                            <input type="text" class="form-control" value="Directur" readonly>
                                        @endif
                                    @else
                                        <select name="role_id" id="role_id" class="form-control">
                                            <option value="" hidden>Pilih Role</option>
                                            @if (Auth::user()->role_id == "1")
                                                <option {{ $user->role_id == '1' ? 'selected' : '' }} value="1">Superadmin</option>
                                                <option {{ $user->role_id == '2' ? 'selected' : '' }} value="2">Admin</option>
                                                <option {{ $user->role_id == '3' ? 'selected' : '' }} value="3">Courier</option>
                                                <option {{ $user->role_id == '5' ? 'selected' : '' }} value="5">Driver</option>
                                            @elseif(Auth::user()->role_id == "2" && $isAdmin->type == '1')
                                                <option {{ $user->role_id == '5' ? 'selected' : '' }} value="5">Driver</option>
                                                <option {{ $user->role_id == '3' ? 'selected' : '' }} value="3">Courier</option>
                                            @elseif(Auth::user()->role_id == "2" && $isAdmin->type == '2')
                                                <option {{ $user->role_id == '5' ? 'selected' : '' }} value="5">Driver</option>
                                            @endif
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        value="{{ $user->email }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="status_id">Ubah Status</label>
                                    <select name="status_id" id="status_id" class="form-control">
                                        <option value="" hidden>Pilih Status</option>
                                        <option value="1" {{ $user->is_active == '1' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="0" {{ $user->is_active == '0' ? 'selected' : '' }}>Nonaktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if (Auth::user()->role_id == "1")
                                    <div class="form-group mb-3 outlet_id">
                                        <label for="outlet_id">Outlet</label>
                                        <select name="outlet_id" id="outlet_id" class="form-control">
                                            <option value="" hidden>Pilih Outlet</option>
                                                @foreach ($outlets as $outlet)
                                                    <option {{ $user->outlets_id == $outlet->id ? 'selected' : '' }} value="{{ $outlet->id }}">{{ $outlet->name }}</option>
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
        $(document).ready(function() {

            $('.outlet_id').hide()

            var role = $('#role_id').val()
            if (role == "2" || role == "3" || role == "5") {
                $('.outlet_id').show()
            }else{
                $('.outlet_id').hide()
            }
            $('#role_id').change(function () {
                var role = $('#role_id').val()
                if (role == "2" || role == "3"|| role == "5") {
                    $('.outlet_id').show()
                }else{
                    $('.outlet_id').hide()
                }
            })

            $('#form-edit-user').validate({
                rules: {
                    'name'      : 'required',
                    'status_id' : 'required',
                    'email'     : 'required',
                    'outlet_id' : 'required',
                },
                messages: {
                    'name'      : 'Nama harus diisi.',
                    'status_id' : 'Pilih salah satu.',
                    'email'     : 'Email harus diisi.',
                    'outlet_id' : 'Pilih salah outlet.',
                },
            })
        });
    </script>
@endsection
