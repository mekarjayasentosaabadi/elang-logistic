<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        confirmDelete('Hapus Pengguna', 'Apakah Anda Yakin Ingin Menghapus Pengguna Ini?');
        return view('pages.user.index');
    }

    public function getAll()
    {

        if (Auth::user()->role_id == '1') {
            $q = User::query();
        }else{
            $q = User::where('outlets_id', Auth::user()->outlets_id)->get();
        }


        return DataTables::of($q)
            ->editColumn('role_id', function ($query) {
                return role($query->role_id);
            })
            ->editColumn('is_active', function ($query) {
                if ($query->is_active == '1') {
                    return '<span class="badge badge-light-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-light-danger">Tidak Aktif</span>';
                }
            })
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                $btn .= '<div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                         <a class="dropdown-item"  href="' . url('/user/' . $encryptId . '/edit') . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            <span>Edit</span>
                        </a>
                        <a class="dropdown-item" href="#" data-confirm-reset="true" data-url="' . url('/user/' . $encryptId . '/resetpassword') . '">
                            <svg viewBox="0 0 21 21" xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g fill="none" fill-rule="evenodd" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" transform="matrix(0 1 1 0 2.5 2.5)"> <path d="m3.98652376 1.07807068c-2.38377179 1.38514556-3.98652376 3.96636605-3.98652376 6.92192932 0 4.418278 3.581722 8 8 8s8-3.581722 8-8-3.581722-8-8-8"></path> <path d="m4 1v4h-4" transform="matrix(1 0 0 -1 0 6)"></path> </g> </svg>
                            <span>Reset Password</span>
                        </a>
                         <a class="dropdown-item"  href="' . url('/user/' . $encryptId) . '" data-confirm-delete="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            <span>Hapus</span>
                        </a>
                    </div>
                </div>';
                return $btn;
            })
            ->rawColumns(['is_active', 'role_id', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $isAdmin = Outlet::where('id', Auth::user()->outlets_id)->first();
        $outlets = Outlet::all();
        return view('pages.user.create', compact('outlets', 'isAdmin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->outlet_id) {

            $validator   = Validator::make($request->all(), [
                'outlet_id'   => 'required'
            ],[
                'outlet_id.required'     => 'Outlet harus diisi',
            ]);

            if ($validator->fails()) {
                $errors       = $validator->errors()->all();
                $errorMessage = implode(', ', $errors);

                Alert::error('Gagal', $errorMessage);
                return redirect()->back()->withInput();
            }

        }

        $validator   = Validator::make($request->all(), [
            'name'   => 'required',
            'role_id'=> 'required',
            'email'  => 'required|unique:users,email'
        ],[
            'name.required'     => 'Nama harus diisi',
            'role_id.required'  => 'Pilih role',
            'email.required'    => 'Email harus diisi',
            'email.unique'      => 'Alamat email sudah digunakan.'
        ]);

        if ($validator->fails()) {
            $errors       = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);

            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }

        $dataUser = [
            'name'      => $request->name,
            'role_id'   => $request->role_id,
            'email'     => $request->email,
            'password'  => Hash::make("elang123")
        ];

        if ($request->outlet_id) {
            $dataUser['outlets_id'] = $request->outlet_id;
        }

        $user = User::create($dataUser);

        if ($user) {
            Alert::success('Berhasil', 'Pengguna Berhasil Ditambahkan');
            return redirect()->to('/user');
        } else {
            Alert::error('Gagal', 'Pengguna Gagal Ditambahkan');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $isAdmin   = Outlet::where('id', Auth::user()->outlets_id)->first();
        $user      = User::find($decrypted);
        $outlets   = Outlet::all();
        return view('pages.user.edit', compact('user', 'outlets', 'isAdmin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
            try {
                $decrypted = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                abort(404);
            }

            if ($request->outlet_id) {

                $validator   = Validator::make($request->all(), [
                    'outlet_id'   => 'required'
                ],[
                    'outlet_id.required'     => 'Outlet harus diisi',
                ]);

                if ($validator->fails()) {
                    $errors       = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }

            }

            $validator   = Validator::make($request->all(), [
                'name'      => 'required',
                'status_id' => 'required',
                'email'     => 'required|unique:users,email,' . $decrypted
            ],[
                'name.required'       => 'Nama harus diisi',
                'status_id.required'  => 'Pilih salah satu',
                'email.required'      => 'Email harus diisi',
                'email.unique'        => 'Alamat email sudah digunakan.'
            ]);

            if ($validator->fails()) {
                $errors       = $validator->errors()->all();
                $errorMessage = implode('<br>', $errors);

                Alert::error('Gagal', $errorMessage);
                return redirect()->back()->withInput();
            }

            $dataUser = [
                'name'      => $request->name,
                'status_id' => $request->status_id,
                'is_active' => $request->status_id,
                'email'     => $request->email,
            ];
            if ($request->role_id) {
                $dataUser['role_id'] = $request->role_id;
            }

            if ($request->outlet_id) {
                $dataUser['outlets_id'] = $request->outlet_id;
            }

            $user = User::find($decrypted)->update($dataUser);

            if ($user) {
                Alert::success('Berhasil', 'Pengguna Berhasil Edit');
                return redirect()->to('/user');
            } else {
                Alert::error('Gagal', 'Pengguna Gagal Edit');
                return redirect()->back()->withInput();
            }
    }

    public function resetpassword($id){
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $updatingUserPassword = [
            'password' => Hash::make("elang123")
        ];

        $dataUser = User::find($decrypted)->update($updatingUserPassword);
        if ($dataUser) {
            Alert::success('Berhasil', 'Password Pengguna Berhasil di Perbaharui');
            return redirect()->to('/user');
        }else{
            Alert::error('Gagal', 'Password Pengguna Gagal di Perbaharui');
            return redirect()->to('/user');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $decrypted = Crypt::decrypt($id);
        $dataUser = User::find($decrypted)->delete();
        return redirect()->back();
    }
}
