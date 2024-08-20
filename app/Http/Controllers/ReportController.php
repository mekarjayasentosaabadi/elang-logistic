<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Destination;
use App\Models\Surattugas;
use Illuminate\Http\Request;
use App\Models\Traveldocument;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index() {
        $outlets = Outlet::all();
        $drivers = User::where('role_id', '5')->where('outlets_id', Auth::user()->outlets_id)->get();
        $destinations = Destination::all();
        return view('pages.report.reportpengiriman', compact('outlets', 'destinations', 'drivers'));
    }



    public function reportTransaksi() {
        return view('pages.report.reporttransaksi');
    }




    public function getDriverByOutlet(Request $request) {
        if ($request->outletid) {
            $drivers = User::where('role_id', '5')->where('outlets_id', $request->outletid)->get();

            return response()->json(['drivers' => $drivers]);
        }

        return response()->json(['drivers' => []]);
    }



    public function getReportPengiriman(Request $request) {
        $query = Surattugas::with([
            'driver',
            'vehicle',
            'detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order.destination',
            'outlet'
        ]);


        // if ($request->has('outlet_id')) {
        //     $query->where('outlets_id', $request->outlet_id);
        // }
        // else {
        //     $query->where('outlets_id', Auth::user()->outlets_id);
        // }


        if ($request->has('driver')) {
            $query->where('driver', $request->driver);
        }

        if ($request->has('tanggal_awal_berangkat') && $request->has('tanggal_akhir_berangkat')) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereBetween('start', [$request->tanggal_awal_berangkat, $request->tanggal_akhir_berangkat]);
            });
        }


        if ($request->has('jenis_pengiriman')) {
            $query->whereHas('detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order', function($q) use ($request) {
                $q->where('armada', $request->jenis_pengiriman);
            });
        }

        if ($request->has('status')) {
                $query->where('statussurattugas', $request->status);
        }

        $dataReport = $query->get()->map(function($report) {
            return array_merge($report->toArray(), [
                'travelno'          => $report->detailsurattugas->first()->traveldocument->travelno ?? null,
                'start_date'        => $report->detailsurattugas->first()->traveldocument->start ?? null,
                'finish_date'       => $report->detailsurattugas->first()->traveldocument->finish_date ?? null,
                'jenis_pengiriman'  => $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->armada ?? null,
                'destinasi'         => $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->first()->destination->name ?? null,
                'berat_volume'      => $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->weight ??  $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->volume,
            ]);
        });

        return response()->json(['dataReport'=>$dataReport]);
    }
}
