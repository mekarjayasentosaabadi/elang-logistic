<?php

namespace App\Http\Controllers;

use App\Models\Manifest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ManifestController extends Controller
{
    public function index(){
        return view('pages.manifest.index');
    }
    public function getAll(){

        $q = Manifest::withCount('detailmanifests')->get();
        return DataTables::of($q)
            ->addIndexColumn()
            ->make(true);
    }
}
