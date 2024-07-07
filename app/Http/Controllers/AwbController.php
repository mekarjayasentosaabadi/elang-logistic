<?php

namespace App\Http\Controllers;

use App\Models\HistoryAwb;
use Illuminate\Http\Request;

class AwbController extends Controller
{
    function index()
    {
        return view('pages.resi.index');
    }

    function getResi($awb)
    {
        $history = HistoryAwb::where('awb', $awb)->get();
        // created at format
        $dt = [];
        foreach ($history as $key => $value) {
            $dt[] = [
                'created_at' => $value->created_at->format('d-m-Y H:i'),
                'status' => $value->status
            ];
        }
        return response()->json($dt);
    }
}
