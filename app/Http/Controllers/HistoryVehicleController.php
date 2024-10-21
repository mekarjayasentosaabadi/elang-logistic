<?php

namespace App\Http\Controllers;

use App\Models\HistoryVehicle;
use App\Models\Manifest;
use App\Models\Outlet;
use App\Models\Surattugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class HistoryVehicleController extends Controller
{
    //
    function index()
    {
        // get vehicle data by surat tugas where status != 3
        $surattugas = Surattugas::where('statussurattugas', '!=', 3)->where('driver_id', auth()->user()->id)->first();
        $outlet = Outlet::all();

        if (!$surattugas) {
            $manifest = [];
        } else {
            $manifest = Manifest::where('id', $surattugas->detailSuratTugas->pluck('manifest_id')->toArray())->get();
        }

        return view('pages.history-vehicle.index', compact('surattugas', 'outlet', 'manifest'));
    }

    function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $outletId = $request->outlet_id;
            $surattugas = Surattugas::where('statussurattugas', '!=', 3)->where('driver_id', auth()->user()->id)->first();
            $message = $request->satus == 'Tiba' ? 'Tiba di ' : 'Dibearangkatkan dari ';
            $outlet = Outlet::find($outletId);
            $destination = $outlet->location_id;
            $is_arived = $request->status_resi == 3 ? 3 : 2;
            if ($request->status_resi == '1' || $request->status_resi == '3') {
                $message = "Pesanan tiba di " . $outlet->name;
            } else {
                $message = "Pesanan di berangkatkan dari " . $outlet->name;
            }
            if (!in_array('all', $request->manifest_id)) {
                $manifest = Manifest::whereIn('id', $request->manifest_id)->get();
                foreach ($manifest as $key => $value) {
                    // get order 
                    $value->update([
                        'status_manifest' => $is_arived,
                    ]);
                    $details = $value->detailmanifests;
                    foreach ($details as $key2 => $detail) {
                        $order = $detail->order;
                        $order->update([
                            'status_awb' => $message,
                        ]);
                        $order->histories()->create([
                            'order_id' => $order->id,
                            'awb'      => $order->numberorders,
                            'status'   => $message,
                            'created_by' => Auth::user()->id,
                        ]);
                    }
                }
                // update status surat tugas when all manifest is arrived
                $surattugas = Surattugas::whereHas('detailsurattugas', function ($query) use ($request) {
                    $query->whereIn('manifest_id', $request->manifest_id);
                })->first();

                if ($surattugas) {
                    $status = 0;
                    foreach ($surattugas->detailsurattugas as $key => $value) {
                        if ($value->manifest->status_manifest == 3) {
                            $status++;
                        }
                    }
                    if ($status == $surattugas->detailsurattugas->count()) {
                        $surattugas->update([
                            'statussurattugas' => 3,
                        ]);
                    }
                }
                if ($request->status_resi == '1' || $request->status_resi == '3') {
                    $message = "Tiba di";
                } else {
                    $message = "Dibearangkatkan dari";
                }

                HistoryVehicle::create([
                    'vehicle_id'    => $surattugas->vehicle_id,
                    'user_id'       => auth()->user()->id,
                    'status'        => $request->status_resi,
                    'outlet_id'     => $outlet->id,
                    'destination_id' => $outlet->location_id,
                    'note'          => 'Kendaran ' . $message . $outlet->name,
                    'noreference'   => $surattugas->id
                ]);
            } else {
                // surat tugas
                // get surat tugas
                $surattugas = Surattugas::where('id', $surattugas->id)->get();
                foreach ($surattugas as $key => $value) {
                    // get order 
                    $details = $value->detailsurattugas;
                    $value->update([
                        'statussurattugas' => $is_arived,
                    ]);
                    foreach ($details as $key2 => $detail) {

                        $status_manifest = Manifest::find($detail->manifest_id);
                        if ($status_manifest->status_manifest == '2') {
                            foreach ($detail->manifest->detailmanifests as $key3 => $manifests) {
                                $order = $manifests->order;
                                $order->update([
                                    'status_awb' => $message,
                                ]);
                                $order->histories()->create([
                                    'order_id' => $order->id,
                                    'awb'      => $order->numberorders,
                                    'status'   => $message,
                                    'created_by' => Auth::user()->id,
                                ]);
                            }
                            // update status manifest
                            $detail->manifest->update([
                                'status_manifest' => $is_arived,
                            ]);
                        }
                    }
                    if ($request->status_resi == '1' || $request->status_resi == '3') {
                        $message = "Tiba di";
                    } else {
                        $message = "Dibearangkatkan dari";
                    }
                    HistoryVehicle::create([
                        'vehicle_id'    => $value->vehicle_id,
                        'user_id'       => auth()->user()->id,
                        'status'        => $request->status_resi,
                        'outlet_id'     => $outlet->id,
                        'destination_id' => $outlet->location_id,
                        'note'          => 'Kendaran ' . $message . $outlet->name,
                        'noreference'   => $value->id
                    ]);
                }
            }



            DB::commit();
            Alert::success('Success', 'Berhasil update status kendaraan');
            return redirect()->back();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Alert::error('Error', 'Gagal update status kendaraan');
            return redirect()->back();
        }
    }
}
