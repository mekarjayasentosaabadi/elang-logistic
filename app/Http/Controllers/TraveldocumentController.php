<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Traveldocument;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Models\Detailtraveldocument;
use Illuminate\Support\Facades\Crypt;

class TraveldocumentController extends Controller
{
    public function index(){
        return view('pages.traveldocument.index');
    }

    function getAll(){
        if(auth()->user()->role_id == '2'){
            $q = DB::table('traveldocuments')
                        ->join('detailtraveldocuments', 'traveldocuments.id', '=', 'detailtraveldocuments.traveldocuments_id')
                        ->join('destinations', 'traveldocuments.destinations_id', '=', 'destinations.id')
                        ->select('traveldocuments.id','traveldocuments.travelno', 'destinations.name','traveldocuments.status_traveldocument', DB::raw('count(detailtraveldocuments.traveldocuments_id) as jml_manifest'))
                        ->where('traveldocuments.outlets_id', auth()->user()->outlets_id)
                        ->groupBy('traveldocuments.travelno', 'destinations.name', 'traveldocuments.status_traveldocument')
                        ->get();
        } else {
            $q = DB::table('traveldocuments')
                        ->join('detailtraveldocuments', 'traveldocuments.id', '=', 'detailtraveldocuments.traveldocuments_id')
                        ->join('destinations', 'traveldocuments.destinations_id', '=', 'destinations.id')
                        ->select('traveldocuments.id','traveldocuments.travelno', 'destinations.name','traveldocuments.status_traveldocument', DB::raw('count(detailtraveldocuments.traveldocuments_id) as jml_manifest'))
                        ->groupBy('traveldocuments.travelno', 'destinations.name', 'traveldocuments.status_traveldocument')
                        ->get();
        }
            return DataTables::of($q)
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                $btn .= '<div>';
                $btn .= '<a href="'.url('/delivery/'.$encryptId.'/edit').'" class="btn btn-warning btn-sm" title="Edit"><li class="fa fa-edit"></li></a> ';
                $btn .= '<a href="'.url('/delivery/'.$encryptId.'/cetak').'" class="btn btn-primary btn-sm" title="Cetak"><li class="fa fa-print"></li></a> ';
                $btn .= '<button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#twoFactorAuthModal" onclick="getDetail(this,'.$query->id.')"><li class="fa fa-search"></li></button> ';
                $btn .= '<a class="btn btn-danger btn-sm" title="Cancel" onclick="deleteTravelDocument(this,'.$query->id.')"><li class="fa fa-trash"></li></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->editColumn('status_traveldocument', function($s){
                if($s->status_traveldocument == 0){
                    return '<span class="text-danger">Cancel</span>';
                } else if($s->status_traveldocument == 1){
                    return '<span class="text-success">Process</span>';
                } else if($s->status_traveldocument == 2){
                    return '<span>On The Way</span>';
                } else if($s->status_traveldocument == 3){
                    return '<span>Transit</span>';
                } else {
                    return '<span>Finish</span>';
                }
            })
            ->rawColumns(['aksi', 'status_traveldocument'])
            ->addIndexColumn()
            ->make(true);
    }

    //create
    function create(){
        $vehicle        = Vehicle::where('is_active', '1')->get();
        $driver         = User::where('role_id', '5' )->where('outlets_id', auth()->user()->outlets_id)->get();
        $destination    = Destination::all();
        return view('pages.traveldocument.create', compact(['vehicle', 'driver', 'destination']));
    }

    //listDetailManifest
    function manifestorder($id){
        $q = DB::table('manifests')
                ->leftJoin('detailmanifests', 'manifests.id', '=', 'detailmanifests.manifests_id')
                ->leftJoin('orders', 'detailmanifests.orders_id', '=', 'orders.id')
                ->leftJoin('destinations', 'orders.destinations_id', '=', 'destinations.id')
                ->select('manifests.id', 'manifests.manifestno', 'destinations.name as namadestinasi', 'orders.outlet_id', 'manifests.status_manifest', DB::raw('COUNT(detailmanifests.id) as jumlahawb'))
                ->where('orders.destinations_id', $id)
                ->where('orders.outlet_id', auth()->user()->outlets_id)
                ->where('manifests.status_manifest', '1')
                ->groupBy('manifests.id', 'destinations.name', 'manifests.manifestno', 'orders.outlet_id')
                ->get();
        return ResponseFormatter::success([
            'datamanifest'      => $q
        ], 'get data successfuly');
    }

    function store(Request $request){
        $storedTravelDocuments = [
            'travelno'                  => $request->suratJalan,
            'vehicle_id'                => $request->kendaraan,
            'driver_id'                 => $request->driver,
            'description'               => $request->description,
            'status_traveldocument'     => 1,
            'destinations_id'           => $request->destination,
        ];
        if(auth()->user()->role_id == '2'){
            $storedTravelDocuments['outlets_id']=auth()->user()->outlets_id;
        }
        $traveldocument = Traveldocument::create($storedTravelDocuments);
        $input = $request->input();
        $dataDetail = [];
        if(@$input['manifest']){
            foreach ($input['manifest'] as $key => $value) {
                $dataDetail[] = [
                    'traveldocuments_id'        => $traveldocument->id,
                    'manifests_id'              => $value
                ];
            }
        }
        Detailtraveldocument::insert($dataDetail);
        return ResponseFormatter::success([], 'Berhasil menambahkan data Surat jalan');
    }

    function print($id){
        return '';
    }
    //delete
    function delete($id){
        //delete detail shipping
        Detailtraveldocument::where('traveldocuments_id', $id)->delete();
        Traveldocument::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Berhasil menghapus data Surat jalan');
    }
    //edit
    function edit($id){
        $suratjalan     = Traveldocument::where('id', Crypt::decrypt($id))->firstOrFail();
        $vehicle        = Vehicle::where('is_active', '1')->get();
        $driver         = User::where('role_id', '5' )->where('outlets_id', auth()->user()->outlets_id)->get();
        $destination    = Destination::all();
        return view('pages.traveldocument.edit', compact(['suratjalan', 'vehicle', 'driver', 'destination']));
    }

    //listDetailTravelDocument
    function listDetail($id){
        $detailTravelDocument = Traveldocument::with(['driver', 'vehicle', 'destination'])->where('id', $id)->first();
        $q = DB::table('detailtraveldocuments')
                ->join('manifests', 'detailtraveldocuments.manifests_id', '=', 'manifests.id')
                ->select('manifests.manifestno', DB::raw('count(detailtraveldocuments.manifests_id) as jml_awb') )
                ->where('detailtraveldocuments.traveldocuments_id', $id)
                ->groupBy('manifests.manifestno')
                ->get();

        return ResponseFormatter::success([
            'traveldocument'        => $detailTravelDocument,
            'detailtraveldocuments' => $q
        ], 'Get data successfuly');
    }
}
