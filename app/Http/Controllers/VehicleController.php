<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        return view('pages.vehicle.index');
    }

    public function getAll(Request $request){
        $q = Vehicle::query();

        return DataTables::of($q)
        ->  editColumn('type', function ($query){
                return typeVehicle($query->type);
            })
        ->  editColumn('is_active', function ($query){
                if ($query->is_active == '1') {
                    return '<span class="badge badge-light-success">Aktif</span>';
                } else {
                    return '<span class="badge badge-light-danger">Tidak Aktif</span>';
                }
            })
        ->  addColumn('aksi', function ($query){
                $encryptId = Crypt::encrypt($query->id);
                $btn = '<div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="' . url('/vehicle/' . $encryptId . '/edit') . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            <span>Edit</span>
                        </a>
                    </div>
                </div>';
                return $btn;
        })
        ->  rawColumns(['is_active','aksi'])
        ->  addIndexColumn()
        ->  make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator =   Validator::make($request->all(), [
            'no_police' => 'required|unique:vehicles,police_no',
            'type'      => 'required',
            'no_stnk'   => 'required|unique:vehicles,no_stnk'
        ], [
            'no_police.required'    => 'No police harus diisi',
            'no_police.unique'      => 'No police sudah digunakan',
            'type.required'         => 'required',
            'no_stnk.required'      => 'No STNK harus diisi',
            'no_stnk.unique'        => 'No STNK sudah digunakan'
        ]);

        if ($validator->fails()) {
            $errors       = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);

            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }

        $dataVehicle = [
            'police_no' => $request->no_police,
            'type'      => $request->type,
            'no_stnk'   => $request->no_stnk
        ];

        $vehicle = Vehicle::create($dataVehicle);
        if ($vehicle) {
            Alert::success('Berhasil', 'Vehicle Berhasil Ditambahkan');
            return redirect()->to('/vehicle');
        }else{
            Alert::error('Gagal', 'Vehicle Gagal Ditambahkan');
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

        $vehicle    = Vehicle::find($decrypted);
        return view('pages.vehicle.edit', compact('vehicle'));
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

        $validator =   Validator::make($request->all(), [
            'no_police'     => 'required|unique:vehicles,police_no,'.$decrypted,
            'type'          => 'required',
            'no_stnk'       => 'required|unique:vehicles,no_stnk,'.$decrypted,
            'status_id'     => 'required'
        ], [
            'no_police.required'    => 'No police harus diisi',
            'no_police.unique'      => 'No police sudah digunakan',
            'type.required'         => 'Pilih salah satu',
            'no_stnk.required'      => 'No STNK harus diisi',
            'status_id'             => 'Pilih salah satu'
        ]);

        if ($validator->fails()) {
            $errors       = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);

            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }

        $dataVehicle = [
            'police_no'     => $request->no_police,
            'type'          => $request->type,
            'no_stnk'       => $request->no_stnk,
            'is_active'     => $request->status_id,
        ];

        $vehicle = Vehicle::find($decrypted)->update($dataVehicle);

        if ($vehicle) {
            Alert::success('Berhasil', 'Vehicle Berhasil Edit');
            return redirect()->to('/vehicle');
        }else{
            Alert::error('Gagal', 'Vehicle Gagal Edit');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
}
