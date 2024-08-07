<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
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
                ->join('traveldocuments', 'detailsurattugas.traveldocuments_id', '=', 'traveldocuments.id')
                ->join('destinations', 'traveldocuments.destinations_id', '=', 'destinations.id')
                ->select('surattugas.nosurattugas', 'surattugas.statussurattugas', 'destinations.name', DB::raw('count(detailsurattugas.traveldocuments_id) as jumlah_surat_tugas'))
                ->where('surattugas.outlets_id', auth()->user()->outlets_id)
                ->groupBy('surattugas.nosurattugas', 'surattugas.statussurattugas', 'destinations.name')
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
                    $option .= '<a href="manifest/'.Crypt::encrypt($x->id).'/edit" class="btn btn-warning btn-sm "><i class="fa fa-edit"></i></a> ';
                    $option .= '<button class="btn btn-danger btn-sm" onclick="deleteManifest(this, '.$x->id.')"><i class="fa fa-trash"></i></button> ';
                    $option .= '<a href="manifest/'.Crypt::encrypt($x->id).'/print" target="_blank" class="btn btn-success btn-sm" title="Cetak Resi Manifest"><i class="fa fa-print"></i></a></div>';
                    return $option;
                })
                ->rawColumns(['status'])
                ->addIndexColumn()
                ->make(true);
    }
}
