<?php

namespace App\Http\Controllers;

use App\Models\Surattugas;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Detailsurattugas;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class SurattugasController extends Controller
{
    public function index(){
        return view('pages.surattugas.index');
    }

    function getAll(){
        $table = DB::table('surattugas')
                ->leftJoin('detailsurattugas', 'surattugas.id', '=', 'detailsurattugas.surattugas_id')
                ->leftJoin('traveldocuments', 'detailsurattugas.traveldocuments_id', '=', 'traveldocuments.id')
                ->leftJoin('destinations', 'traveldocuments.destinations_id', '=', 'destinations.id')
                ->select('surattugas.id', 'surattugas.nosurattugas', 'surattugas.statussurattugas', 'destinations.id as iddestination', 'destinations.name', DB::raw('count(detailsurattugas.traveldocuments_id) as jumlah_surat_tugas'))
                ->where('surattugas.outlets_id', auth()->user()->outlets_id)
                ->groupBy('surattugas.id', 'surattugas.nosurattugas', 'surattugas.statussurattugas', 'destinations.name', 'destinations.id')
                ->get();

        return DataTables::of($table)
                ->addColumn('status', function($x){
                    if($x->statussurattugas == 0){
                        return '<span class="text-danger">Cancel</span>';
                    } else if($x->statussurattugas == 1){
                        return '<span class="text-success">Process</span>';
                    } else {
                        return '<span class="text-primary">Done</span>';
                    }
                })
                ->addColumn('option', function($x){
                    $option = '<div>';
                    $option .= '<a href="surattugas/'.Crypt::encrypt($x->id).'/edit" class="btn btn-warning btn-sm "><i class="fa fa-edit"></i></a> ';
                    $option .= '<a class="btn btn-primary btn-sm" title="Cetak Surat Tugas"><li class="fa fa-print"></li></a> ';
                    $option .= '<button class="btn btn-success btn-sm" title="Berangkatkan"><li class="fa fa-truck"></li></button> ';
                    $option .= '<button class="btn btn-danger btn-sm" onclick="deleteSuratTugas(this, '.$x->id.')"><i class="fa fa-trash"></i></button> ';
                    return $option;
                })
                ->rawColumns(['status', 'option'])
                ->addIndexColumn()
                ->make(true);
    }

    function create(){
        $destination    = Destination::all();
        return view('pages.surattugas.create', compact('destination'));
    }

    function getSuratJalan($id){
        $db = DB::table('traveldocuments')
                ->leftJoin('detailtraveldocuments', 'traveldocuments.id', '=', 'detailtraveldocuments.traveldocuments_id')
                ->select('traveldocuments.id','traveldocuments.travelno', DB::raw('count(detailtraveldocuments.manifests_id) as jml_manifest'))
                ->where('traveldocuments.destinations_id', $id)
                ->where('traveldocuments.status_traveldocument', 1)
                ->groupBy('traveldocuments.id','traveldocuments.travelno')
                ->get();
        return ResponseFormatter::success(['dataSuratJalan'=>$db], 'Berhasil mengambil data');
    }

    function store(Request $request){
        try {
            $request->validate([
                'suratTugas'        => 'required|unique:surattugas,nosurattugas'
            ]);
            $storedDataSuratJalan = [
                'nosurattugas'      => $request->suratTugas,
                'statussurattugas'  => 1,
                'note'              => $request->description,
                'outlets_id'        => auth()->user()->outlets_id
            ];
            $suratTugas = Surattugas::create($storedDataSuratJalan);
            $input = $request->input();
            $dataDetail = [];
            if(@$input['suratjalan']){
                foreach ($input['suratjalan'] as $key => $value) {
                    $dataDetail[] = [
                        'surattugas_id'         => $suratTugas->id,
                        'traveldocuments_id'    => $value
                    ];
                }
            }
            Detailsurattugas::insert($dataDetail);
            return ResponseFormatter::success([
                'detailSt'      => $dataDetail
            ], 'Surat tugas berhasil di simpan');
        } catch (Exception $error) {
            return ResponseFormatter::error([$error],'Something went wrong');
        }

    }

    function delete($id){
        Detailsurattugas::where('surattugas_id', $id)->delete();
        Surattugas::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Berhasil menghapus data Surat tugas');
    }

    function edit($id){
        $destination    = Destination::all();
        $surattugas = DB::table('surattugas')
                        ->leftJoin('detailsurattugas', 'surattugas.id', '=', 'detailsurattugas.surattugas_id')
                        ->leftJoin('traveldocuments', 'detailsurattugas.traveldocuments_id', '=', 'traveldocuments.id')
                        ->leftJoin('destinations', 'traveldocuments.destinations_id', '=', 'destinations.id')
                        ->select('surattugas.id', 'surattugas.nosurattugas', 'surattugas.statussurattugas', 'destinations.id as iddestination', 'destinations.name', DB::raw('count(detailsurattugas.traveldocuments_id) as jumlah_surat_tugas'))
                        ->where('surattugas.id', Crypt::decrypt($id))
                        ->groupBy('surattugas.id', 'surattugas.nosurattugas', 'surattugas.statussurattugas', 'destinations.name', 'destinations.id')
                        ->first();
        return view('pages.surattugas.edit', compact('destination', 'surattugas'));
    }

    function getListSuratJalan($id){
        $listSuratTugas = DB::table('detailsurattugas')
                            ->leftJoin('traveldocuments', 'detailsurattugas.traveldocuments_id', '=', 'traveldocuments.id')
                            ->leftJoin('detailtraveldocuments', 'traveldocuments.id', '=', 'detailtraveldocuments.traveldocuments_id')
                            ->select('detailsurattugas.id as idsurattugas','traveldocuments.id','traveldocuments.travelno', DB::raw('count(detailtraveldocuments.manifests_id) as jml_manifest'))
                            ->where('detailsurattugas.surattugas_id', Crypt::decrypt($id))
                            ->groupBy('detailsurattugas.id','traveldocuments.id','traveldocuments.travelno')
                            ->get();

        return ResponseFormatter::success(['listSuratTugas'=>$listSuratTugas], 'Get data successfuly');
    }

    function deleteList($id){
        Detailsurattugas::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Data surat tugas berhasil di hapus.!!');
    }
}
