<?php

namespace App\Http\Controllers;

use App\Models\Manifest;
use App\Models\Outlet;
use App\Models\Surattugas;
use App\Models\Traveldocument;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UpdateResiController extends Controller
{
    function index()
    {
        $outlet = Outlet::all();
        return view('pages.update-resi.index', compact('outlet'));
    }

    function getResi(Request $request)
    {
        if ($request->update_data == '1') {
            $list_data = Surattugas::where('statussurattugas', 2)->get();
        } else {
            $list_data = Manifest::where('status_manifest', 2)->get();
        }

        $data = [];
        foreach ($list_data as $key => $value) {
            $data[] = [
                'id' => $value->id,
                'name' => $value->manifestno ?? $value->nosurattugas,
            ];
        }

        return response()->json($data);
    }

    function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $outlet = Auth::user()->role_id == 1 ? $request->outlet_id : Auth::user()->outlets_id;
            $outlet = Outlet::find($outlet);
            $is_arived = $request->has('is_arived') ? 3 : 2;
            if ($request->status_resi == '1') {
                $message = "Pesanan tiba di " . $outlet->name;
            } else {
                $message = "Pesanan di berangkatkan dari " . $outlet->name;
            }
            if ($request->update_data == '1') {
                // surat tugas
                // get surat tugas
                $surattugas = Surattugas::whereIn('id', $request->noResi)->get();
                foreach ($surattugas as $key => $value) {
                    // get order 
                    $details = $value->detailsurattugas;
                    $value->update([
                        'statussurattugas' => $is_arived,
                    ]);
                    foreach ($details as $key2 => $detail) {
                        $manifest = $detail->traveldocument->detailtraveldocument->manifest->detailmanifests;

                        $status_manifest = Manifest::find($detail->traveldocument->detailtraveldocument->manifest->id);

                        if ($status_manifest->status_manifest == '2') {
                            foreach ($manifest as $key3 => $manifests) {
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
                        } else {
                            $travelDocument = $detail->traveldocument->id;
                            Traveldocument::where('id', $travelDocument)->update([
                                'status_traveldocument' => $is_arived,
                            ]);

                            // update status manifest
                            $detail->traveldocument->detailtraveldocument->manifest->update([
                                'status_manifest' => $is_arived,
                            ]);
                        }
                    }
                }
            } else {
                // manifest
                // get manifest
                $manifest = Manifest::whereIn('id', $request->noResi)->get();
                foreach ($manifest as $key => $value) {
                    $value->detailtraveldocument->traveldocument->update([
                        'status_traveldocument' => $is_arived,
                    ]);
                    Traveldocument::where('id', $value->detailtraveldocument->traveldocument->id)->update([
                        'status_traveldocument' => $is_arived,
                    ]);

                    // get order 
                    $details = $value->detailmanifests;
                    $value->update([
                        'status_manifest' => $is_arived,
                    ]);
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
            }
            DB::commit();
            Alert::success('Berhasil', 'Data Berhasil Diubah');
            return redirect()->route('update-resi.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Gagal', 'Data Gagal Diubah');
            return redirect()->back();
        }
    }
}
