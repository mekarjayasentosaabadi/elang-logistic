<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Destination;
use App\Models\Masterprice;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class MasterpriceController extends Controller
{
    public function index(){
        return view('pages.masterprice.index');
    }

    public function getAll(){
       $tbl = Masterprice::selectRaw('origin_id, outlets_id, armada')
                  ->with(['outlet' => function($q){
                      $q->select('id', 'name');
                  }, 'destination', 'origin'])
                  ->groupBy('origin_id', 'outlets_id', 'armada');
        return DataTables::of($tbl)
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
            ->addColumn('aksi', function($x){
                $aksi = '<div>';
                $aksi .= '<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="showDetail('.$x->origin_id.','.$x->outlets_id.','.$x->armada.')"><li class="fa fa-list"></li></button>';
                $aksi .= '</div>';
                return $aksi;
            })
            ->rawColumns(['namaarmada', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getGetListPrice(Request $request){
        try {
            $destination    = Destination::all()->except($request->origin_id);

            if (Auth::user()->role_id == 1) {
                $existingPrices = DB::table('masterprices')
                                  ->where('outlets_id', $request->outlet_id)
                                  ->where('armada',  $request->armada)
                                  ->where('origin_id', $request->origin_id)
                                  ->get();
            }else{
                $existingPrices = DB::table('masterprices')
                                  ->where('outlets_id', Auth::user()->outlets_id)
                                  ->where('armada',  $request->armada)
                                  ->where('origin_id', $request->origin_id)
                                  ->get();
            }
            
            return response()->json([
                'destination'       => $destination,
                'armada'            => $request->armada,
                'existingPrices'    => $existingPrices
            ]);
        } catch (\Throwable $th) {
            Alert::error('Gagal', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }



    public function create(){
        $outlet         = Outlet::where('is_active', '1')->get();
        $destination    = Destination::all();
        return view('pages.masterprice.create', compact('outlet', 'destination'));
    }

    function store(Request $request){
        try {
            // $search = [
            //     'outlets_id'    => $request->outlet,
            //     'armada'        => $request->armada,
            //     'destinations_id'=> $request->destination
            // ];
            // $filter = Masterprice::where($search)->first();
            
            // if($filter){
            //     return ResponseFormatter::success(['validate'=>false], 'Data Masterprice tersebut sudah ada.!, Mohon periksa kembali');
            // }
            $destination = Destination::all();
            $destination = $request->destination_id;
            $outletId = auth()->user()->role_id == 1 ? $request->outlet_id : auth()->user()->outlet->id;
            for($i = 0; $i < count($destination); $i++){
                    $minWeight = $request->weight[$i] ?? 0; 
                    $minWeight = is_numeric($minWeight) ? (int) $minWeight : 0;
                    $masterPrice = new Masterprice();
                    $dataStored = [
                        'outlets_id'       => $outletId,
                        'armada'           => $request->armada,
                        'origin_id'        => $request->origin_id,
                        'destinations_id'  => $request->destination_id[$i],
                        'price'            => $request->price_weight[$i] ?? 0,
                        'minweights'        => $request->weight[$i] ?? 0,
                        'nextweightprices' => $request->next_weight_price[$i] ?? 0,
                        'minimumprice'     => 0,
                        'estimation'       => $request->estimation[$i] ?? 0
                    ];
                    if ($request->armada == 1) {
                        $dataStored['minweights'] = 10;
                    }

                    $dataMasterPrice = Masterprice::where('origin_id', $request->origin_id)
                                                    ->where('outlets_id', $outletId)
                                                    ->where('armada', $request->armada)
                                                    ->where('destinations_id', $request->destination_id[$i])
                                                    ->where('price', 0)
                                                    ->first();


                    $dataPriceOld = Masterprice::where($dataStored)->first();

                    if($dataMasterPrice){
                        $dataMasterPrice->update($dataStored);
                    }else{
                        if($dataPriceOld){
                            $dataPriceOld->update($dataStored);
                        }
                        else{
                            $masterPrice->create($dataStored);
                        }
                    }
            }


            Alert::success('Berhasil', 'Berhasil Memasukan Data');
            return redirect('/masterprice');


            // $dataStored = [
            //     'outlets_id'    => $request->outlet,
            //     'armada'        => $request->armada,
            //     'destinations_id'   => $request->destination,
            //     'price'             => $request->price,
            //     'minweight'         => $request->minweight,
            //     'nextweightprices'  => $request->pricenext,
            //     'minimumprice'      => $request->minimumprice,
            //     'estimation'        => $request->estimation
            // ];
            // Masterprice::create($dataStored);
            // return ResponseFormatter::success(['validate'=>true], 'Master price berhasil di simpan.!');
        } catch (\Throwable $th) {
            dd($th);
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
                $pesan = ['Data Masterprice tersebut sudah ada.!, Mohon periksa kembali'];
                return ResponseFormatter::success(['validate'=>false], $pesan);
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
            $pesan = ['Master price berhasil diperbaharui.!'];
            return ResponseFormatter::success(['validate'=>true], $pesan);
        } catch (\Throwable $th) {
            return ResponseFormatter::error([$th], 'Something went wrong');
        }
    }

    function listhargapublic($id, $id2, $id3){
        // $dataList = Masterprice::with(['outlets', 'destinations', 'armada'])->where('outlets_id', $id)->where('destinations_id', $id2)->where('armada', $id3)->get();
        // return ResponseFormatter::success([$dataList], 'Berhasil mengambil data harga Customer');
        // var_dump($id, $id2, $id3);
        $tbl = Masterprice::with(['outlet', 'origin', 'destination'])->where('outlets_id', $id2)->where('armada', $id3)->where('origin_id', $id);
        return DataTables::of($tbl)
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
            ->addColumn('aksi', function($x){
                $aksi = '<div>';
                $aksi .= '<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" onclick="showDetail('.$x->origin_id.','.$x->outlets_id.','.$x->armada.')"><li class="fa fa-list"></li></button>';
                $aksi .= '</div>';
                return $aksi;
            })
            ->rawColumns(['namaarmada', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }
}
