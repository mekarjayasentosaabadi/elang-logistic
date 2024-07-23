<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;

class OutletController extends Controller
{
    public function index()
    {

        // confirm delete
        confirmDelete('Hapus Outlet', 'Apakah Anda Yakin Ingin Menghapus Outlet Ini?');
        return view('pages.outlet.index');
    }

    public function getAll()
    {

        // $q = Outlet::with(['operators'])->get();
        $q = DB::table('outlets')
                ->leftJoin('users', 'outlets.id', '=', 'users.outlets_id')
                ->select('outlets.id', 'outlets.name', 'users.name as namaoperator', 'outlets.type', 'outlets.email', 'outlets.phone', 'outlets.is_active')
                ->get();

        return DataTables::of($q)
            ->editColumn('type', function ($query) {
                return typeOutlet($query->type);
            })
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                $btn .= '<div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"  href="' . url('/outlet/' . $encryptId . '/edit') . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            <span>Edit</span>
                        </a>
                         <a class="dropdown-item"  href="' . url('/outlet/' . $encryptId) . '" data-confirm-delete="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            <span>Hapus</span>
                        </a>
                    </div>
                </div>';
                return $btn;
            })
            ->addColumn('toogle', function($x){
                $checked = $x->is_active == '1' ? 'checked' : '';
                $toogle = '';
                $toogle .= '<div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" '.$checked.' id="paymentTerms" onclick="changeStatus(this, '.$x->id.')" />
                                <label class="form-check-label" for="paymentTerms"></label>
                            </div>';
                return $toogle;
            })
            ->rawColumns(['aksi', 'operator', 'toogle'])
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        $destination = Destination::all();
        return view('pages.outlet.create', compact('destination'));
    }


    function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => Rule::unique('outlets', 'name'),
        ]);

        if ($validate->fails()) {
            Alert::error('Gagal', 'Nama Outlet Sudah Ada');
            return redirect()->back()->withInput();
        }


        $outlet = Outlet::create($request->except(['_token', 'findlat', 'findlong']));
        if ($outlet) {
            Alert::success('Berhasil', 'Outlet Berhasil Ditambahkan');
            return redirect()->to('/outlet');
        } else {
            Alert::error('Gagal', 'Outlet Gagal Ditambahkan');
            return redirect()->back()->withInput();
        }
    }

    function edit($id)
    {
        $id = Crypt::decrypt($id);
        $outlet = Outlet::find($id);
        $destination = Destination::all();
        return view('pages.outlet.edit', compact('outlet', 'destination'));
    }

    function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => Rule::unique('outlets', 'name')->ignore(Crypt::decrypt($id)),
        ]);

        if ($validate->fails()) {
            Alert::error('Gagal', 'Nama Outlet Sudah Ada');
            return redirect()->back()->withInput();
        }

        $id = Crypt::decrypt($id);
        $outlet = Outlet::find($id);
        $outlet->update($request->except(['_token', 'findlat', 'findlong']));
        if ($outlet) {
            Alert::success('Berhasil', 'Outlet Berhasil Diubah');
            return redirect()->to('/outlet');
        } else {
            Alert::error('Gagal', 'Outlet Gagal Diubah');
            return redirect()->back()->withInput();
        }
    }

    function destroy($id)
    {
        $id = Crypt::decrypt($id);
        $outlet = Outlet::find($id);
        $outlet->delete();
        if ($outlet) {
            Alert::success('Berhasil', 'Outlet Berhasil Dihapus');
            return redirect()->to('/outlet');
        } else {
            Alert::error('Gagal', 'Outlet Gagal Dihapus');
            return redirect()->back();
        }
    }

    function changeStatus(Request $request, $id){
        try {
            $status = Outlet::find($id);
            if($status->is_active == '1'){
                $update=[
                    'is_active' => '0'
                ];
            } else {
                $update=[
                    'is_active' => '1'
                ];
            }
            $status->update($update);
            return ResponseFormatter::success([$status], 'Success Memperbaharui data');
        } catch (Exception $error) {
            return ResponseFormatter::error([$error], 'Gagal Memperbaharui data');
        }
    }
}
