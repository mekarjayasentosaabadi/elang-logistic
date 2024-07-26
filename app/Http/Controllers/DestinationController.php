<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Destination;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Crypt;

class DestinationController extends Controller
{
    public function index(){
        return view('pages.destination.index');
    }
    function getAll(){
        $q = Destination::all();
        return DataTables::of($q)
            ->addColumn('aksi', function ($query) {
                $btn = '';
                $btn .= '<div>';
                $btn .= '<button class="btn btn-primary btn-sm" onclick="editData(this, '.$query->id.')" title="Edit Destination"><li class="fa fa-edit"></li>';
                $btn .= '</button> <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="showOutlet('.$query->id.')"><li class="fa fa-list"></li></button> </div>';
                return $btn;

            })
            ->rawColumns(['aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    function edit($id){
        return ResponseFormatter::success([
            Destination::where('id', $id)->first()
        ], 'Succes mengambil data');
    }

    function stored(Request $request){
        Destination::create([
            'name'      => $request->name
        ]);
        return ResponseFormatter::success([], 'Data Destination berhasil di simpan.!');
    }

    function update(Request $request, $id){
        Destination::where('id', $id)->update([
            'name'      => $request->name
        ]);
        return ResponseFormatter::success([], 'Data Destination berhasil di perbaharui.!');
    }

    function listoutlet($id){
        $list = Outlet::with('operators')->where('location_id', $id)->get();
        if(!$list){
            return ResponseFormatter::error([], 'Data tidak ditemukan');
        }
        return ResponseFormatter::success([
            'listoutlet'    => $list
        ],'Success mengambil data');
    }
}
