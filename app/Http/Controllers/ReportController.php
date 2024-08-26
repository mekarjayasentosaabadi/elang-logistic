<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Surattugas;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Traveldocument;
use Illuminate\Support\Carbon;
use Elibyy\TCPDF\Facades\TCPDF;
use App\Models\Detailsurattugas;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index() {
        $outlets = Outlet::all();
        $drivers = User::where('role_id', '5')->where('outlets_id', Auth::user()->outlets_id)->get();
        $destinations = Destination::all();
        return view('pages.report.index', compact('outlets', 'destinations', 'drivers'));
    }




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


        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id) {
                $query->where('outlets_id', $request->outlet_id);
            }
        }else{
            $query->where('outlets_id', Auth::user()->outlets_id);
        }


        if ($request->driver) {
            $query->where('driver_id', $request->driver);
        }


        if ($request->destination) {
            $query->whereHas('detailsurattugas.traveldocument', function ($q) use ($request) {
                $q->where('destinations_id', $request->destination);
            });
        }


        if ($request->tanggal_awal_berangkat && $request->tanggal_akhir_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereBetween('start', [$request->tanggal_awal_berangkat, $request->tanggal_akhir_berangkat]);
            });
        }elseif($request->tanggal_awal_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereDate('start', $request->tanggal_awal_berangkat);
            });
        }elseif($request->tanggal_akhir_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereDate('start', $request->tanggal_akhir_berangkat);
            });
        }



        if ($request->jenis_pengiriman) {
            $query->whereHas('detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order', function($q) use ($request) {
                $q->where('armada', $request->jenis_pengiriman);
            });
        }


        if (isset($request->status_surattugas)) {
            if ($request->status_surattugas == '5') {
                $query->whereIn('statussurattugas', ['0', '1', '2']);
            }else{
                $query->where('statussurattugas', $request->status_surattugas);
            }
        }


        $dataReport = $query->get()->map(function($report) {
            return array_merge($report->toArray(), [
                'travelno'          => $report->detailsurattugas->first()->traveldocument->travelno ?? null,
                'start_date'        => $report->detailsurattugas->first()->traveldocument->start ?? null,
                'finish_date'       => $report->detailsurattugas->first()->traveldocument->finish_date ?? null,
                'jenis_pengiriman'  => $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->armada ?? null,
                'destinasi'         => $report->detailsurattugas->first()->traveldocument->destination->name ?? null,
                'berat_volume'      => $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->weight ??  $report->detailsurattugas->first()->traveldocument->first()->detailtraveldocument->first()->manifest->first()->detailmanifests->first()->order->volume,
            ]);
        });

        return response()->json(['dataReport'=>$dataReport]);
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


        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id) {
                $query->where('outlet_id', $request->outlet_id);
            }
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
            if ($request->status == '5') {
                $query->whereIn('status_orders', ['1', '2', '3', '4']);
            }else{
                $query->where('status_orders', $request->status);
            }
        }

        $orders = $query->get();
        return response()->json(['orders' => $orders]);
    }




    public function downloadreportpengiriman(Request $request) {

        $query = Surattugas::with([
            'driver',
            'vehicle',
            'detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order.destination',
            'outlet.destination'
        ]);


        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id_select) {
                $query->where('outlets_id', $request->outlet_id_select);
            }
        }else{
            $query->where('outlets_id', Auth::user()->outlets_id);
        }


        if ($request->driver) {
            $query->where('driver_id', $request->driver);
        }


        if ($request->destination) {
            $query->whereHas('detailsurattugas.traveldocument', function ($q) use ($request) {
                $q->where('destinations_id', $request->destination);
            });
        }


        if ($request->tanggal_awal_berangkat && $request->tanggal_akhir_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereBetween('start', [$request->tanggal_awal_berangkat, $request->tanggal_akhir_berangkat]);
            });
        }elseif($request->tanggal_awal_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereDate('start', $request->tanggal_awal_berangkat);
            });
        }elseif($request->tanggal_akhir_berangkat) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request) {
                $q->whereDate('start', $request->tanggal_akhir_berangkat);
            });
        }



        if ($request->jenis_pengiriman) {
            $query->whereHas('detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order', function($q) use ($request) {
                $q->where('armada', $request->jenis_pengiriman);
            });
        }


        if (isset($request->status_surattugas)) {
            if ($request->status_surattugas == '5') {
                $query->whereIn('statussurattugas', ['0', '1', '2']);
            }else{
                $query->where('statussurattugas', $request->status_surattugas);
            }
        }


        $dataReports = $query->get();


        // dd($dataReports);

        $pdf = new TCPDF;
        $pdf::SetFont('helvetica', '', 12);
        $pdf::SetTitle("pengiriman");
        $pdf::SetAuthor('Kaushal');
        $pdf::SetSubject('Generated PDF');

        $imagePath = public_path('assets/img/logo.png');

        $pdf::AddPage('L', 'A4');
        $html = view()->make('pages.report.printreportpengiriman', compact('imagePath', 'dataReports'));

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output("reportperngiriman.pdf", 'D');
        $pdf::reset();
    }





    public function downloadreporttransaksi(Request $request) {
        $query = Order::with('customer', 'destination', 'outlet.destination', 'detailmanifests.manifest.detailtraveldocument.traveldocument');


        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id_select_customer) {
                $query->where('outlet_id', $request->outlet_id_select_customer);
            }
        }else{
            $query->where('outlet_id', Auth::user()->outlets_id);
        }



        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->destination_id) {
            $query->where('destinations_id', $request->destination_id);
        }

        if ($request->tanggal_order_awal && $request->tanggal_order_akhir) {
            $query->whereBetween('created_at', [$request->tanggal_order_awal, $request->tanggal_order_akhir]);
        } elseif ($request->tanggal_order_awal) {
            $query->whereDate('created_at', $request->tanggal_order_awal);
        } elseif ($request->tanggal_order_akhir) {
            $query->whereDate('created_at', $request->tanggal_order_akhir);
        }



        if ($request->status) {
            if ($request->status == '5') {
                $query->whereIn('status_orders', ['1', '2', '3', '4']);
            }else{
                $query->where('status_orders', $request->status);
            }
        }

        $orders = $query->get();


        $pdf = new TCPDF;
        $pdf::SetFont('helvetica', '', 12);
        $pdf::SetTitle("reporttransaksi");
        $pdf::SetAuthor('Kaushal');
        $pdf::SetSubject('Generated PDF');

        $imagePath = public_path('assets/img/logo.png');

        $pdf::AddPage('L', 'A4');
        $html = view()->make('pages.report.printreporttransaksi', compact('imagePath', 'orders'));

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output("reporttransaksi.pdf", 'D');
        $pdf::reset();
    }
}
