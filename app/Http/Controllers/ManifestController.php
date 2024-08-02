<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Manifest;
use Illuminate\Http\Request;
use App\Models\Detailmanifest;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ManifestController extends Controller
{
    public function index(){
        return view('pages.manifest.index');
    }
    public function getAll(){

        $q = DB::table('manifests')
                ->leftJoin('detailmanifests', 'manifests.id', '=', 'detailmanifests.manifests_id')
                ->leftJoin('orders', 'detailmanifests.orders_id', '=', 'orders.id')
                ->leftJoin('destinations', 'orders.destinations_id', '=', 'destinations.id')
                ->select('manifests.id', 'manifests.manifestno', 'destinations.name as namadestinasi', 'orders.outlet_id', 'manifests.status_manifest', DB::raw('COUNT(detailmanifests.id) as jumlahmamnifest'))
                ->where('orders.outlet_id', auth()->user()->outlets_id)
                ->groupBy('manifests.id', 'destinations.name', 'manifests.manifestno', 'orders.outlet_id')
                ->get();
        return DataTables::of($q)
            ->addColumn('status', function($e){
                if($e->status_manifest == 0){
                    $status ='<div class="text-danger">';
                    $status .= 'Cancel</div>';
                    return $status;
                } elseif($e->status_manifest == 1) {
                    $status ='<div class="text-primary">';
                    $status .= 'Process</div>';
                    return $status;
                } elseif($e->status_manifest == 2) {
                    $status ='<div class="text-primary">';
                    $status .= 'On The Way</div>';
                    return $status;
                } else {
                    $status ='<div class="text-success">';
                    $status .= 'Finish</div>';
                    return $status;
                }
            })
            ->addColumn('option', function($x){
                if($x->status_manifest != 3){
                    $option = '<div>';
                    $option .= '<a href="manifest/'.Crypt::encrypt($x->id).'/edit" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a> ';
                    $option .= '<button class="btn btn-danger btn-sm" onclick="deleteManifest(this, '.$x->id.')"><i class="fa fa-trash"></i></button> ';
                    $option .= '<a href="manifest/'.Crypt::encrypt($x->id).'/print" target="_blank" class="btn btn-success btn-sm" title="Cetak Resi Manifest"><i class="fa fa-print"></i></a></div>';
                    return $option;
                }
            })
            ->rawColumns(['status', 'option'])
            ->addIndexColumn()
            ->make(true);
    }
    function getOrders(){
        $outletId = auth()->user()->outlet->id;
        $q = DB::table('orders')
                ->join('users', 'orders.customer_id', '=', 'users.id')
                ->join('destinations', 'orders.destinations_id', '=', 'destinations.id')
                ->select('orders.id', 'orders.outlet_id', 'orders.status_orders', 'orders.numberorders', 'users.name as namacustomer', 'destinations.name as destination')
                ->where('outlet_id', $outletId)
                ->where('status_orders', '2')
                ->get();
        return DataTables::of($q)
            ->addColumn('check', function($cek){
                $valueCheck = $cek->id;
                $check = '<div>';
                $check .= '<input class="form-check-input" name="checkbox'.$valueCheck.'" type="checkbox" id="checkbox[]" value="'.$valueCheck.'" onchange="check(this, '.$valueCheck.')"/>';
                $check .= '</div>';
                return $check;
            })
            ->rawColumns(['check'])
            ->addIndexColumn()
            ->make(true);
    }
    function create(){
        return view('pages.manifest.create');
    }

    //get detail orders to array
    function checkOrders($id){
        $data = Order::with(['customer', 'destination'])->where('id', $id)->firstOrFail();
        return ResponseFormatter::success([$data], 'get data successfuly');
    }

    //stored
    function store(Request $request){
        $dataManifest = [
            'manifestno'    => $request->manifestno,
            'carier'        => $request->carrier,
            'commodity'     => $request->commodity,
            'flight_no'     => $request->flightno,
            'no_bags'       => $request->nobags,
            'flight_file'   => $request->flagsfile
        ];
        $manifest = Manifest::create($dataManifest);
        $dataDetail = [];
        $input = $request->input();
        if(@$input['ordersid']){
            foreach ($input['ordersid'] as $key => $value) {
                $dataDetail[] = [
                    'manifests_id'      => $manifest->id,
                    'orders_id'         => $input['ordersid'][$key]
                ];
            }
        }
        Detailmanifest::insert($dataDetail);
        return ResponseFormatter::success([], 'Manifest berhasil di simpan.!');
    }

    //edit
    function edit($id){
        return view('pages.manifest.update');
    }

    //get detail
    function getdetail($id){
        $datamanifest           = Manifest::where('id', Crypt::decrypt($id))->firstOrFail();
        $q = DB::table('orders')
                ->join('users', 'orders.customer_id', '=', 'users.id')
                ->join('destinations', 'orders.destinations_id', '=', 'destinations.id')
                ->join('detailmanifests', 'orders.id', '=', 'detailmanifests.orders_id')
                ->select('orders.id', 'orders.outlet_id', 'orders.status_orders', 'orders.numberorders', 'users.name as namacustomer', 'destinations.name as destination', 'detailmanifests.manifests_id', 'orders.weight', 'detailmanifests.id as detailmanifestid')
                ->where('detailmanifests.manifests_id', Crypt::decrypt($id))
                ->where('status_orders', '2')
                ->get();
        return ResponseFormatter::success([
            'manifest'          => $datamanifest,
            'detailmanifest'    => $q
        ], 'Success get data');
    }

    //delete
    function delete(Request $request, $id){
        Detailmanifest::where('manifests_id', $id)->delete();
        Manifest::where('id', $id)->delete();
        return ResponseFormatter::success([],'Success menghapus data');
    }

    function deletedetailold(Request $request, $id){
        DetailManifest::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Berhasil menghaputs data');
    }

    function update(Request $request, $id){
        $dataManifest = [
            'manifestno'    => $request->manifestno,
            'carier'        => $request->carrier,
            'commodity'     => $request->commodity,
            'flight_no'     => $request->flightno,
            'no_bags'       => $request->nobags,
            'flight_file'   => $request->flagsfile
        ];
        // $manifest = Manifest::create($dataManifest);
        Manifest::where('id', Crypt::decrypt($id))->update($dataManifest);
        return ResponseFormatter::success([], 'Manifest berhasil di perbaharui.!');
    }

    function adddetail(Request $request, $id, $ordersid){
        $manifest = Manifest::where('id', Crypt::decrypt($id))->first();
        $dataUpdate = [
            'manifests_id'      => $manifest->id,
            'orders_id'         => $ordersid
        ];
        Detailmanifest::insert($dataUpdate);
        return ResponseFormatter::success([], 'Berhasil menambahkan data');
    }

    function printresi($id){
        $dataManifest   = Detailmanifest::with('order.destination')->where('manifests_id', Crypt::decrypt($id))->get();
        $manifest       = Manifest::where('id', Crypt::decrypt($id))->first();
        return view('pages.manifest.resi', compact('dataManifest', 'manifest'));
    }
}
