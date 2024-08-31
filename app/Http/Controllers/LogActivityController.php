<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LogActivityController extends Controller
{
    public function index(){
        return view('pages.logactivity.index');
    }
    //getData LogActivity
    function getData(){
        $q = LogActivity::with('user')->get();
        return DataTables::of($q)
            ->addColumn('waktu', function($x){
                return $x->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('aksi', function($x){
                $btn = '<a href="#" title="Detail" class="btn btn-warning btn-sm"><li class="fa fa-list"></li></a>';
                return $btn;
            })
            ->rawColumns(['waktu', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }
}
