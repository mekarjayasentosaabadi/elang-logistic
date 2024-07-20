<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Manifest;
use Illuminate\Http\Request;
use App\Models\Detailmanifest;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Crypt;

class ManifestController extends Controller
{
    public function index(){
        return view('pages.manifest.index');
    }
    public function getAll(){

        $q = Manifest::withCount('detailmanifests')->get();
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
                    $option .= '<a href="manifest/'.Crypt::encrypt($x->id).'/edit" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></div>';
                    return $option;
                }
            })
            ->addColumn('jumlahawb', function($x){
                $jumlahawb = $x->detailmanifests_count;
                return $jumlahawb;
            })
            ->rawColumns(['status', 'option', 'jumlahawb'])
            ->addIndexColumn()
            ->make(true);
    }
    function getOrders(){
        $outletId = auth()->user()->outlet->id;
        $q = Order::where('outlet_id', $outletId)->where('status_orders', 2)->get();
        return DataTables::of($q)
            ->addColumn('namecustomer', function($query){
                $nameCustomer = $query->customer->name;
                return $nameCustomer;
            })
            ->addColumn('destination', function($d){
                $destination = $d->destination->name;
                return $destination;
            })
            ->addColumn('check', function($cek){
                $valueCheck = $cek->id;
                $check = '<div>';
                $check .= '<input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="'.$valueCheck.'" onclick="check(this, '.$valueCheck.')"/>';
                $check .= '</div>';
                return $check;
            })
            ->rawColumns(['namecustomer', 'destination', 'check'])
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
            'manifestno'        => 1111112,
            'carier'       => $request->carrier,
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
        $datadetailmanifest     = Detailmanifest::with(['order.customer', 'order.destination'])->where('manifests_id', Crypt::decrypt($id))->get();
        return ResponseFormatter::success([
            'manifest'          => $datamanifest,
            'detailmanifest'    => $datadetailmanifest
        ], 'Success get data');
    }
}
