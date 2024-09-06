<?php

namespace App\Http\Controllers;

use App\Models\HistoryVehicle;
use App\Models\Outlet;
use App\Models\Surattugas;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class HistoryVehicleController extends Controller
{
    //
    function index()
    {
        // get vehicle data by surat tugas where status != 3
        $surattugas = Surattugas::where('statussurattugas', '!=', 3)->where('driver_id', auth()->user()->id)->first();
        $outlet = Outlet::all();

        return view('pages.history-vehicle.index', compact('surattugas', 'outlet'));
    }

    function store(Request $request)
    {
        $outletId = $request->outlet_id;
        $surattugas = Surattugas::where('statussurattugas', '!=', 3)->where('driver_id', auth()->user()->id)->first();
        $message = $request->satus == 'Tiba' ? 'Tiba di ' : 'Dibearangkatkan dari ';
        $outlet = Outlet::find($outletId);
        $destination = $outlet->location_id;
        HistoryVehicle::create([
            'vehicle_id'    => $surattugas->vehicle_id,
            'user_id'       => auth()->user()->id,
            'status'        => $request->satus,
            'outlet_id'     => $outletId,
            'destination_id' => $destination,
            'note'          => 'Kendaran ' . $message . Outlet::find($outletId)->name,
            'noreference'   => $surattugas->id
        ]);
        Alert::success('Success', 'Berhasil update status kendaraan');
        return redirect()->back();
    }
}
