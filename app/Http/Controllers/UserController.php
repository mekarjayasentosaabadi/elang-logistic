<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
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

        confirmDelete('Hapus Outlet', 'Apakah Anda Yakin Ingin Menghapus Pengguna Ini?');
        return view('pages.user.index');
    }

    public function getAll()
    {

        $q = User::query();


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
        return view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $user      = User::find($decrypted);
        return view('pages.user.edit', compact('user'));
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

            $validator   = Validator::make($request->all(), [
                'name'      => 'required',
                'role_id'   => 'required',
                'status_id' => 'required',
                'email'     => 'required|unique:users,email,' . $decrypted
            ],[
                'name.required'       => 'Nama harus diisi',
                'role_id.required'    => 'Pilih salah satu',
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
                'role_id'   => $request->role_id,
                'status_id' => $request->status_id,
                'email'     => $request->email,
            ];

            if ($request->reset_password) {
                $dataUser['password'] = Hash::make($request->reset_password);
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
