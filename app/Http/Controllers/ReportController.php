<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Destination;
use App\Models\Detailsurattugas;
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
        return view('pages.report.index', compact('outlets', 'destinations', 'drivers'));
    }



    // public function reportTransaksi() {
    //     $outlets = Outlet::all();
    //     $drivers = User::where('role_id', '5')->where('outlets_id', Auth::user()->outlets_id)->get();
    //     $destinations = Destination::all();

    //     return view('pages.report.reporttransaksi', compact('outlets', 'destinations', 'drivers'));
    // }




    public function getDriverByOutlet(Request $request) {
        if ($request->outlet_id) {
            $drivers = User::where('role_id', '5')->where('outlets_id', $request->outlet_id)->get();

            return response()->json(['drivers' => $drivers]);
        }

        return response()->json(['drivers' => []]);
    }



    public function getReportPengiriman(Request $request) {
        $query = Surattugas::with([
            'driver',
            'vehicle',
            'detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order.destination',
            'outlet.destination'
        ]);


        if ($request->outlet_id && Auth::user()->role_id == 1) {
            $query->where('outlets_id', $request->outlet_id);
        }else{
            $query->where('outlets_id', Auth::user()->outlets_id);
        }

        if ($request->driver) {
            $query->where('driver', $request->driver);
        }

        if ($request->destination) {
            $query->whereHas('detailsurattugas.traveldocument', function ($q) use ($request) {
                $q->where('destinations_id', $request->destination);
            });
        }

        if ($request->tanggal_awal_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereDate('start', $request->tanggal_awal_berangkat);
            });
        }

        if ($request->tanggal_akhir_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereDate('start', $request->tanggal_akhir_berangkat);
            });
        }


        if ($request->tanggal_awal_berangkat && $request->tanggal_akhir_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereBetween('start', [$request->tanggal_awal_berangkat, $request->tanggal_akhir_berangkat]);
            });
        }



        if ($request->jenis_pengiriman) {
            $query->whereHas('detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order', function($q) use ($request) {
                $q->where('armada', $request->jenis_pengiriman);
            });
        }


        if (isset($request->status_surattugas)) {
               if ($request->status_surattugas == '5') {
                   $query->OrWhere('statussurattugas', '0')->OrWhere('statussurattugas', '1')->OrWhere('statussurattugas', '2');
                }else{
                    $query->where('statussurattugas', $request->status_surattugas);
                }
        }

        $dataReport = $query->get()->map(function($report) {
            $driver = User::find($report->driver);
            $firstDetail = $report->detailsurattugas->first()->traveldocument ?? null;
            $firstManifest = $firstDetail ? $firstDetail->detailtraveldocument->first()->manifest->first() : null;
            $firstOrder = $firstManifest ? $firstManifest->detailmanifests->first()->order : null;


            return [
                'driver'             => $driver->name,
                'no_surat_jalan'     => $firstDetail->travelno,
                'no_kendaraan'       => $report->vehicle->police_no,
                'tanggal_berangkat'  => $firstDetail->start,
                'tanggal_finish'     => $firstDetail->finish_date,
                'jenis_pengiriman'   => $firstOrder->armada,
                'asal'               => $report->outlet->destination->name,
                'destinasi'          => $firstDetail->destination->name,
                'volume_berat'       => $firstOrder ? ($firstOrder->weight ?? $firstOrder->volume) : null,
                'total_volume_berat' => $report->detailsurattugas->sum(function($detail) {
                    $firstDetail     = $detail->traveldocument;
                    $firstManifest   = $firstDetail ? $firstDetail->detailtraveldocument->first()->manifest->first() : null;
                    $firstOrder      = $firstManifest ? $firstManifest->detailmanifests->first()->order : null;
                    return $firstOrder ? ($firstOrder->weight ?? $firstOrder->volume) : 0;
                })
            ];
        });

        return response()->json([
            'data' => $dataReport,
            'draw' => $request->input('draw'),
            'recordsTotal' => $query->count(), // Total record count
            'recordsFiltered' => $query->count() // Filtered record count
        ]);
    }


    public function getCustomerByOutlet(Request $request) {
        if ($request->outlet_id) {
            $customers = User::where('role_id', '4')->where('outlets_id', $request->outlet_id)->get();
            return response()->json(['customers' => $customers]);
        }

        return response()->json(['customers' => []]);
    }



    public function getReportTransaksi(Request $request) {

        $query = Order::with('customer', 'destination', 'outlet.destination', 'detailmanifests.manifest.detailtraveldocument.traveldocument');

        if ($request->outlet_id && Auth::user()->role_id == 1) {
            $query->where('outlet_id', $request->outlet_id);
        }else{
            $query->where('outlet_id', Auth::user()->outlets_id);
        }

        if ($request->customer) {
            $query->where('customer_id', $request->customer);
        }

        if ($request->destination_transaksi) {
            $query->where('destinations_id', $request->destination_transaksi);
        }

        if ($request->tanggal_order_awal && $request->tanggal_order_akhir) {
            $query->whereBetween('created_at', [$request->tanggal_order_awal, $request->tanggal_order_akhir]);
        } elseif ($request->tanggal_order_awal) {
            $query->whereDate('created_at', $request->tanggal_order_awal);
        } elseif ($request->tanggal_order_akhir) {
            $query->whereDate('created_at', $request->tanggal_order_akhir);
        }


        if ($request->status) {
            $query->where('status_orders', $request->status);
        }

        $orders = $query->get();
        return response()->json(['orders' => $orders]);
    }
}
