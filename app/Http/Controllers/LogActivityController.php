<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Crypt;

class LogActivityController extends Controller
{
    public function index()
    {
        return view('pages.logactivity.index');
    }
    //getData LogActivity
    function getData()
    {
        $q = LogActivity::with('user')->orderBy('created_at', 'DESC')->get();
        return DataTables::of($q)
            ->editColumn('user', function ($x) {
                return $x->user->name ?? '-';
            })
            ->addColumn('waktu', function ($x) {
                return $x->created_at->format('d-m-Y H:i:s');
            })
            ->addColumn('aksi', function ($x) {
                $btn = '<a href="logactifity/'.Crypt::encrypt($x->id).'/detail" title="Detail" class="btn btn-warning btn-sm"><li class="fa fa-list"></li></a>';
                return $btn;
            })
            ->editColumn('path', function($x){
                $patch = Str::limit($x->path, 30);
                $patnya = '<div>'.$patch.'.... </div>';
                return $patnya;
            })
            ->rawColumns(['waktu', 'aksi', 'path'])
            ->addIndexColumn()
            ->make(true);
    }
    function detail($id){
        return view('pages.logactivity.detail');
    }

    function getDetail($id){
        $q = LogActivity::with(['user'])->where('id', Crypt::decrypt($id))->first();
        return ResponseFormatter::success([$q], 'Berhasil mengambil data');
    }
}
