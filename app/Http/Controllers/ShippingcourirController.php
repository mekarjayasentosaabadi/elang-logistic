<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ShippingcourirController extends Controller
{
    public function index() {
        return view('pages.shippingcourir.index');
    }

    public function getAll()
    {

        if (Auth::user()->role_id == '1') {
            $q = User::where('role_id', '!=', '4')->where('role_id', '!=', '1')->get();
        }else{
            $q = User::where('outlets_id', Auth::user()->outlets_id)->where('role_id', '!=', '4')->get();
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

    public function create() {
        $couriers = User::where('role_id', '3')->get();
        $outlets  = Outlet::all();
        return view('pages.shippingcourir.create', compact('couriers', 'outlets'));
    }

    public function edit($id) {
        $couriers = User::where('role_id', '3')->get();
        $outlets  = Outlet::all();
        return view('pages.shippingcourir.edit', compact('couriers', 'outlets'));
    }
}
