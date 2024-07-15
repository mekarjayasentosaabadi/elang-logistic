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
                                    @if (count($roles) === 1)
                                        <input type="text" name="role_id" id="role_id" class="form-control"
                                            value="{{ reset($roles) }}" disabled readonly>
                                    @else
                                        <select name="role_id" id="role_id" class="form-control">
                                            @foreach ($roles as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ $user->role_id == $key ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endforeach
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
        $(document).ready(function() {
            $('#form-edit-user').validate({
                rules: {
                    'name': 'required',
                    'status_id': 'required',
                    'email': 'required',
                },
                messages: {
                    'name': 'Nama harus diisi.',
                    'status_id': 'Pilih salah satu.',
                    'email': 'Email harus diisi',
                },
            })
        });
    </script>
@endsection
