<?php

namespace App\Http\Controllers;

use App\Exports\ReportPengirimanExport;
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
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportTransaksiExport;

class ReportController extends Controller
{
    public function index() {
        $outlets = Outlet::all();
        $drivers = User::where('role_id', '5')->where('outlets_id', Auth::user()->outlets_id)->get();
        $destinations = Destination::all();
        $customers = User::where('role_id', '4')->where('outlets_id', Auth::user()->outlets_id)->get();
        return view('pages.report.index', compact('outlets', 'destinations', 'drivers', 'customers'));
    }




    public function getDriverByOutlet(Request $request) {
        if ($request->outlet_id) {
            $drivers = User::where('role_id', '5')->where('outlets_id', $request->outlet_id)->get();

            return response()->json(['drivers' => $drivers]);
        }

        return response()->json(['drivers' => []]);
    }






    public function getReportPengiriman(Request $request) {
        $formData = $request->input('formData');
        parse_str($formData, $params);

        $query = DB::table('surattugas')
        ->select(
            'surattugas.id',
            'surattugas.*',
            'users.name as driver_name',
            'vehicles.police_no as vehicle_police_no',
            'outlets.name as outlet_name',
            'origin_destinations.name as origin_name',
            'destinations.name as destination_name',
            DB::raw('max(orders.created_at) as order_created_at'),
            DB::raw('max(orders.finish_date) as order_finish_date'),
            DB::raw('max(orders.armada) as order_armada'),
            DB::raw('max(orders.weight) as order_weight'),
            DB::raw('max(orders.volume) as order_volume')
        )
        ->leftJoin('users', 'surattugas.driver_id', '=', 'users.id')
        ->leftJoin('vehicles', 'surattugas.vehicle_id', '=', 'vehicles.id')
        ->leftJoin('outlets', 'surattugas.outlets_id', '=', 'outlets.id')
        ->leftJoin('destinations as origin_destinations', 'outlets.location_id', '=', 'origin_destinations.id')
        ->leftJoin('detailsurattugas', 'surattugas.id', '=', 'detailsurattugas.surattugas_id')
        ->leftJoin('manifests', 'detailsurattugas.manifest_id', '=', 'manifests.id')
        ->leftJoin('detailmanifests', 'manifests.id', '=', 'detailmanifests.manifests_id')
        ->leftJoin('orders', 'detailmanifests.orders_id', '=', 'orders.id')
        ->leftJoin('destinations', 'surattugas.destination_id', '=', 'destinations.id')
        ->groupBy('surattugas.id');



        if (Auth::user()->role_id == 1) {
            if (!empty($params['outlet_id'])) {
                if($params['outlet_id'] != 'all_outlet'){
                    $query->where('surattugas.outlets_id', $params['outlet_id']);
                }
            }
        } else {
            $query->where('surattugas.outlets_id', Auth::user()->outlets_id);
        }

        if (!empty($params['driver'])) {
            $query->where('surattugas.driver_id', $params['driver']);
        }

        if (!empty($params['destination'])) {
            $query->where('surattugas.destination_id', $params['destination']);
        }

        if (!empty($params['tanggal_awal_berangkat']) && !empty($params['tanggal_akhir_berangkat'])) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($params['tanggal_akhir_berangkat']));
            $query->whereBetween('orders.created_at', [$params['tanggal_awal_berangkat'], $endOfDay]);
        } elseif (!empty($params['tanggal_awal_berangkat'])) {
            $query->whereDate('orders.created_at', $params['tanggal_awal_berangkat']);
        } elseif (!empty($params['tanggal_akhir_berangkat'])) {
            $query->whereDate('orders.created_at', $params['tanggal_akhir_berangkat']);
        }

        if (!empty($params['jenis_pengiriman'])) {
            $query->where('orders.armada', $params['jenis_pengiriman']);
        }

        if (is_numeric($params['status_surattugas'])) {
            if ($params['status_surattugas'] == '5') {
                $query->whereIn('surattugas.statussurattugas', ['0', '1', '2', '3']);
            } else {
                $query->where('surattugas.statussurattugas', $params['status_surattugas']);
            }
        }

        $queryResult = $query->get();

        return DataTables::of($queryResult)
            ->editColumn('driver_name', function ($query) {
                return $query->driver_name ?? '-';
            })
            ->editColumn('nosurattugas', function ($query) {
                return $query->nosurattugas ?? '-';
            })
            ->editColumn('vehicle_police_no', function ($query) {
                return $query->vehicle_police_no ?? '-';
            })
            ->editColumn('order_created_at', function ($query) {
                return $query->order_created_at ?? '-';
            })
            ->editColumn('order_finish_date', function ($query) {
                return $query->order_finish_date ?? '-';
            })
            ->editColumn('order_armada', function ($query) {
                $armada = $query->order_armada;
                $aramadaVal = "";
                if ($armada == "1") {
                    $aramadaVal = "Darat";
                }elseif($armada == "2"){
                    $aramadaVal = "Laut";
                }elseif($armada == "3"){
                    $aramadaVal = "Udara";
                }

                return $aramadaVal;
            })
            ->editColumn('origin_name', function ($query) {
                return $query->origin_name ?? '-';
            })
            ->editColumn('destination_name', function ($query) {
                return $query->destination_name ?? '-';
            })
            ->editColumn('volume/weight', function ($query) {
                return $query->order_weight ?? $query->order_volume ?? '-';
            })
            ->editColumn('totalvolume/berat', function ($query) {
                return $query->order_weight ?? $query->order_volume ?? '-';
            })
            ->rawColumns([])
            ->addIndexColumn()
            ->make(true);
    }










    public function getCustomerByOutlet(Request $request) {
        if ($request->outlet_id) {
            $customers = User::where('role_id', '4')->where('outlets_id', $request->outlet_id)->get();
            return response()->json(['customers' => $customers]);
        }

        return response()->json(['customers' => []]);
    }








    public function getReportTransaksi(Request $request) {
        $formData = $request->input('formData');
        parse_str($formData, $params);

        $query = DB::table('orders')
                ->leftJoin('users as customer', 'orders.customer_id', '=', 'customer.id')
                ->leftJoin('destinations as destination', 'orders.destinations_id', '=', 'destination.id')
                ->leftJoin('outlets', 'orders.outlet_id', '=', 'outlets.id')
                ->leftJoin('destinations as outlet_destination', 'outlets.location_id', '=', 'outlet_destination.id')
                ->select(
                    'orders.*',
                    'customer.name as customer_name',
                    'destination.name as destination_name',
                    'outlet_destination.name as outlet_destination_name'
                );


        if (Auth::user()->role_id == 1) {
            if (isset($params['outlet_id'])) {
                if ($params['outlet_id'] != "all_outlet") {
                    $query->where('outlet_id', $params['outlet_id']);
                }
            }
        } else {
            $query->where('outlet_id', Auth::user()->outlets_id);
        }


        if ($params['customer'] != null) {
            $query->where('customer_id', $params['customer']);
        }


        if ($params['destination_transaksi'] != null) {
            $query->where('destinations_id', $params['destination_transaksi']);
        }

        if (($params['tanggal_order_awal'] != null) && ($params['tanggal_order_akhir'] != null)) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($params['tanggal_order_akhir']));

            $query->whereBetween('orders.created_at', [$params['tanggal_order_awal'], $endOfDay]);
        } elseif ($params['tanggal_order_awal'] != null) {
            $query->whereDate('orders.created_at', $params['tanggal_order_awal']);
        } elseif ($params['tanggal_order_akhir'] != null) {
            $query->whereDate('orders.created_at', $params['tanggal_order_akhir']);
        }


        if ($params['status'] != null) {
            if ($params['status'] == '5') {
                $query->whereIn('status_orders', ['1', '2', '3', '4']);
            } else {
                $query->where('status_orders', $params['status']);
            }
        }

        $query = $query->get();

        return DataTables::of($query)
            ->editColumn('customer_name', function ($query) {
                return $query->customer_name  ?? '-';
            })
            ->editColumn('numberorders', function ($query) {
                return $query->numberorders ?? '-';
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at ?? '-';
            })
            ->editColumn('finish_date', function ($query) {
                return $query->finish_date ?? '-';
            })
            ->editColumn('outlet_destination_name ', function ($query) {
                return $query->outlet_destination_name  ?? '-';
            })
            ->editColumn('destination_name', function ($query) {
                return $query->destination_name ?? '-';
            })
            ->editColumn('volume/weight', function ($query) {
                return $query->weight ?? $query->volume ?? '-';
            })
            ->editColumn('totalvolume/berat', function ($query) {
                return $query->weight ?? $query->volume ?? '-';
            })
            ->editColumn('price', function ($query) {
                return formatRupiah($query->price) ?? '-';
            })
            ->rawColumns([])
            ->addIndexColumn()
            ->make(true);
    }




    public function downloadreportpengiriman(Request $request) {
        $dataFilter = $request->except('_token');
        $query = DB::table('surattugas')
        ->select(
            'surattugas.id',
            'surattugas.*',
            'users.name as driver_name',
            'vehicles.police_no as vehicle_police_no',
            'outlets.name as outlet_name',
            'origin_destinations.name as origin_name',
            'destinations.name as destination_name',
            DB::raw('max(orders.created_at) as order_created_at'),
            DB::raw('max(orders.finish_date) as order_finish_date'),
            DB::raw('max(orders.armada) as order_armada'),
            DB::raw('max(orders.weight) as order_weight'),
            DB::raw('max(orders.volume) as order_volume')
        )
        ->leftJoin('users', 'surattugas.driver_id', '=', 'users.id')
        ->leftJoin('vehicles', 'surattugas.vehicle_id', '=', 'vehicles.id')
        ->leftJoin('outlets', 'surattugas.outlets_id', '=', 'outlets.id')
        ->leftJoin('destinations as origin_destinations', 'outlets.location_id', '=', 'origin_destinations.id')
        ->leftJoin('detailsurattugas', 'surattugas.id', '=', 'detailsurattugas.surattugas_id')
        ->leftJoin('manifests', 'detailsurattugas.manifest_id', '=', 'manifests.id')
        ->leftJoin('detailmanifests', 'manifests.id', '=', 'detailmanifests.manifests_id')
        ->leftJoin('orders', 'detailmanifests.orders_id', '=', 'orders.id')
        ->leftJoin('destinations', 'surattugas.destination_id', '=', 'destinations.id')
        ->groupBy('surattugas.id');



        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id_select) {
                if($request->outlet_id_select != 'all_outlet'){
                    $query->where('surattugas.outlets_id', $request->outlet_id_select);
                    $outlet = Outlet::find($request->outlet_id_select);
                    $dataFilter['outlet'] = $outlet->name;
                }else{
                    $dataFilter['outlet'] = "Semua Outlet";
                }
            }
        } else {
            $query->where('surattugas.outlets_id', Auth::user()->outlets_id);
            $outlet = Outlet::find(Auth::user()->outlets_id);
            $dataFilter['outlet'] = $outlet->name;
        }


        if ($request->driver) {
            $query->where('surattugas.driver_id', $request->driver);
            $driver = User::find($request->driver);
            $dataFilter['driver'] = $driver->name;
        }


        if ($request->destination) {
            $query->where('surattugas.destination_id', $request->destination);
            $destination = Destination::find($request->destination);
            $dataFilter['destination'] = $destination->name;
        }

        if (!empty($request->tanggal_awal_berangkat) && !empty($request->tanggal_akhir_berangkat)) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($request->tanggal_akhir_berangkat));
            $query->whereBetween('orders.created_at', [$request->tanggal_awal_berangkat, $endOfDay]);
        } elseif (!empty($request->tanggal_awal_berangkat)) {
            $query->whereDate('orders.created_at', $request->tanggal_awal_berangkat);
        } elseif (!empty($request->tanggal_akhir_berangkat)) {
            $query->whereDate('orders.created_at', $request->tanggal_akhir_berangkat);
        }


        if ($request->jenis_pengiriman) {
            $query->where('orders.armada', $request->jenis_pengiriman);
        }

        if (is_numeric($request->status_surattugas)) {
            if ($request->status_surattugas == '5') {
                $query->whereIn('surattugas.statussurattugas', ['0', '1', '2', '3']);
            } else {
                $query->where('surattugas.statussurattugas', $request->status_surattugas);
            }
        }

        $dataReports = $query->get();

        $user = User::find(Auth::user()->id);
        if($user->role_id == '1'){
            $userAddress = "Outlet Pusat";
        }else{
            $userAddress = $user->outlet->address ?? '-';
        }


        $pdf = new TCPDF;
        $pdf::SetFont('helvetica', '', 12);
        $pdf::SetTitle("pengiriman");
        $pdf::SetAuthor('Kaushal');
        $pdf::SetSubject('Generated PDF');

        $imagePath = public_path('assets/img/logo.png');

        $pdf::AddPage('L', 'A4');
        $html = view()->make('pages.report.printreportpengiriman', compact('imagePath', 'dataReports', 'dataFilter', 'userAddress'));

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output("reportperngiriman.pdf", 'D');
        $pdf::reset();
    }





    public function exportExcelReportPengiriman(Request $request) {
        $query = DB::table('surattugas')
        ->select(
            'surattugas.id',
            'surattugas.*',
            'users.name as driver_name',
            'vehicles.police_no as vehicle_police_no',
            'outlets.name as outlet_name',
            'origin_destinations.name as origin_name',
            'destinations.name as destination_name',
            DB::raw('max(orders.created_at) as order_created_at'),
            DB::raw('max(orders.finish_date) as order_finish_date'),
            DB::raw('max(orders.armada) as order_armada'),
            DB::raw('max(orders.weight) as order_weight'),
            DB::raw('max(orders.volume) as order_volume')
        )
        ->leftJoin('users', 'surattugas.driver_id', '=', 'users.id')
        ->leftJoin('vehicles', 'surattugas.vehicle_id', '=', 'vehicles.id')
        ->leftJoin('outlets', 'surattugas.outlets_id', '=', 'outlets.id')
        ->leftJoin('destinations as origin_destinations', 'outlets.location_id', '=', 'origin_destinations.id')
        ->leftJoin('detailsurattugas', 'surattugas.id', '=', 'detailsurattugas.surattugas_id')
        ->leftJoin('manifests', 'detailsurattugas.manifest_id', '=', 'manifests.id')
        ->leftJoin('detailmanifests', 'manifests.id', '=', 'detailmanifests.manifests_id')
        ->leftJoin('orders', 'detailmanifests.orders_id', '=', 'orders.id')
        ->leftJoin('destinations', 'surattugas.destination_id', '=', 'destinations.id')
        ->groupBy('surattugas.id');



        
        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id_select) {
                if($request->outlet_id_select != 'all_outlet'){
                    $query->where('surattugas.outlets_id', $request->outlet_id_select);
                }
            }
        } else {
            $query->where('surattugas.outlets_id', Auth::user()->outlets_id);
        }


        if ($request->driver) {
            $query->where('surattugas.driver_id', $request->driver);
        }


        if ($request->destination) {
            $query->where('surattugas.destination_id', $request->destination);
        }

        if (!empty($request->tanggal_awal_berangkat) && !empty($request->tanggal_akhir_berangkat)) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($request->tanggal_akhir_berangkat));
            $query->whereBetween('orders.created_at', [$request->tanggal_awal_berangkat, $endOfDay]);
        } elseif (!empty($request->tanggal_awal_berangkat)) {
            $query->whereDate('orders.created_at', $request->tanggal_awal_berangkat);
        } elseif (!empty($request->tanggal_akhir_berangkat)) {
            $query->whereDate('orders.created_at', $request->tanggal_akhir_berangkat);
        }


        if ($request->jenis_pengiriman) {
            $query->where('orders.armada', $request->jenis_pengiriman);
        }

        if (is_numeric($request->status_surattugas)) {
            if ($request->status_surattugas == '5') {
                $query->whereIn('surattugas.statussurattugas', ['0', '1', '2', '3']);
            } else {
                $query->where('surattugas.statussurattugas', $request->status_surattugas);
            }
        }


        $dataReports = $query->get();


        return Excel::download(new ReportPengirimanExport($dataReports), 'reportpengiriman.xlsx');
    }





    public function downloadreporttransaksi(Request $request) {
        $dataFilter = $request->except('_token');

        // $query = Order::with('customer', 'destination', 'outlet.destination', 'detailmanifests.manifest.detailtraveldocument.traveldocument');
        $query = Order::with('customer', 'destination', 'outlet.destination');


        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id_select_customer) {
                if ($request->outlet_id_select_customer != "all_outlet") {
                    $query->where('outlet_id', $request->outlet_id_select_customer);
                    $outlet = Outlet::find($request->outlet_id_select_customer);
                    $dataFilter['outlet'] = $outlet->name;
                }else{
                    $dataFilter['outlet'] = "Semua Outlet";
                }
                
            }
        }else{
            $query->where('outlet_id', Auth::user()->outlets_id);
            $dataUser = User::find(Auth::user()->id);
            $dataFilter['outlet'] = $dataUser->outlet->name;
        }



        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
            $customerData = User::find($request->customer_id);
            $dataFilter['customer'] = $customerData->name;
        }

        if ($request->destination_id) {
            $query->where('destinations_id', $request->destination_id);
            $destinationData = Destination::find($request->destination_id);
            $dataFilter['destination'] = $destinationData->name;
        }

        if ($request->tanggal_order_awal && $request->tanggal_order_akhir) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($request->tanggal_order_akhir));
            $query->whereBetween('created_at', [$request->tanggal_order_awal, $endOfDay]);
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

        $user = User::find(Auth::user()->id);
        if($user->role_id == '1'){
            $userAddress = "Outlet Pusat";
        }else{
            $userAddress = $user->outlet->address ?? '-';
        }


        $pdf = new TCPDF;
        $pdf::SetFont('helvetica', '', 12);
        $pdf::SetTitle("reporttransaksi");
        $pdf::SetAuthor('Kaushal');
        $pdf::SetSubject('Generated PDF');

        $imagePath = public_path('assets/img/logo.png');

        $pdf::AddPage('L', 'A4');
        $html = view()->make('pages.report.printreporttransaksi', compact('imagePath', 'orders', 'userAddress', 'dataFilter'));

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output("reporttransaksi.pdf", 'D');
        $pdf::reset();
    }


    public function exportExcelReportTransaksi(Request $request)  {
        $query = Order::with('customer', 'destination', 'outlet.destination');

        if (Auth::user()->role_id == 1) {
            if ($request->outlet_id_select_customer) {
                if ($request->outlet_id_select_customer != "all_outlet") {
                    $query->where('outlet_id', $request->outlet_id_select_customer);
                }
            }
        } else {
            $query->where('outlet_id', Auth::user()->outlets_id);
        }

        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->destination_id) {
            $query->where('destinations_id', $request->destination_id);
        }

        if ($request->tanggal_order_awal && $request->tanggal_order_akhir) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($request->tanggal_order_akhir));
            $query->whereBetween('created_at', [$request->tanggal_order_awal, $endOfDay]);
        } elseif ($request->tanggal_order_awal) {
            $query->whereDate('created_at', $request->tanggal_order_awal);
        } elseif ($request->tanggal_order_akhir) {
            $query->whereDate('created_at', $request->tanggal_order_akhir);
        }

        if ($request->status) {
            if ($request->status == '5') {
                $query->whereIn('status_orders', ['1', '2', '3', '4']);
            } else {
                $query->where('status_orders', $request->status);
            }
        }

        $orders = $query->get();

        return Excel::download(new ReportTransaksiExport($orders), 'reporttransaksi.xlsx');
    }
}
