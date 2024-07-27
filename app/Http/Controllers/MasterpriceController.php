<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Destination;
use App\Models\Masterprice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Crypt;

class MasterpriceController extends Controller
{
    public function index(){
        return view('pages.masterprice.index');
    }

    public function getAll(){

        $tbl = Masterprice::with(['outlet', 'destination'])->get();
        return DataTables::of($tbl)
            ->addColumn('outlet',function($x){
                $outlets = $x->outlet->name;
                return $outlets;
            })
            ->addColumn('namaarmada', function($x){
                $armada = $x->armada;
                if($armada == 1){
                    return 'Darat';
                }else if($armada == 2){
                    return 'Laut';
                } else {
                    return 'Udara';
                }
            })
            ->addColumn('destination', function($x){
                $destination = $x->destination->name;
                return $destination;
            })
            ->addColumn('option', function($x){
                $role = auth()->user()->role_id;
                if($role == '1'){
                    $html = '<div>';
                    $html .= '<a href="'.url('/masterprice/'.Crypt::encrypt($x->id).'/edit').'" class="btn btn-primary btn-sm" title="Edit"><i class="fa fa-edit"></i></a>';
                    $html .= '</div>';
                    return $html;
                }
                return '-';
            })
            ->editColumn('estimation', function($x){
                $estimation = $x->estimation .' Hari';
                return $estimation;
            })
            ->rawColumns(['outlet', 'namaarmada', 'destination', 'option', 'estimation'])
            ->addIndexColumn()
            ->make(true);
    }
    public function create(){
        $outlet         = Outlet::where('is_active', '1')->get();
        $destination    = Destination::all();
        return view('pages.masterprice.create', compact('outlet', 'destination'));
    }

    function store(Request $request){
        try {
            $search = [
                'outlets_id'    => $request->outlet,
                'armada'        => $request->armada,
                'destinations_id'=> $request->destination
            ];
            $filter = Masterprice::where($search)->first();
            if($filter){
                return ResponseFormatter::success(['validate'=>false], 'Data Masterprice tersebut sudah ada.!, Mohon periksa kembali');
            }
            $dataStored = [
                'outlets_id'    => $request->outlet,
                'armada'        => $request->armada,
                'destinations_id'   => $request->destination,
                'price'             => $request->price,
                'minweight'         => $request->minweight,
                'nextweightprices'  => $request->pricenext,
                'minimumprice'      => $request->minimumprice,
                'estimation'        => $request->estimation
            ];
            Masterprice::create($dataStored);
            return ResponseFormatter::success(['validate'=>true], 'Master price berhasil di simpan.!');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([$th], 'Something went wrong');
        }
    }

    function edit($id){
        $outlet         = Outlet::where('is_active', '1')->get();
        $destination    = Destination::all();
        $masterprice    = Masterprice::where('id', Crypt::decrypt($id))->firstOrFail();
        return view('pages.masterprice.edit', compact('outlet', 'destination', 'masterprice'));
    }

    function update(Request $request, $id){
        try {
            $search = [
                'outlets_id'    => $request->outlet,
                'armada'        => $request->armada,
                'destinations_id'=> $request->destination
            ];
            //search id
            $masterPrice = Masterprice::where('id', Crypt::decrypt($id))->first();
            //search same
            $filter = Masterprice::where($search)->first();

            if($filter->id != $masterPrice->id){
                return ResponseFormatter::success(['validate'=>false], 'Data Masterprice tersebut sudah ada.!, Mohon periksa kembali');
            }
            $dataStored = [
                'outlets_id'    => $request->outlet,
                'armada'        => $request->armada,
                'destinations_id'   => $request->destination,
                'price'             => $request->price,
                'minweights'         => $request->minweight,
                'nextweightprices'  => $request->pricenext,
                'minimumprice'      => $request->minimumprice,
                'estimation'        => $request->estimation
            ];
            Masterprice::where('id', Crypt::decrypt($id))->update($dataStored);
            return ResponseFormatter::success(['validate'=>true], 'Master price berhasil diperbaharui.!');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([$th], 'Something went wrong');
        }
    }
}
