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
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

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

        $query = Surattugas::with([
            'driver',
            'vehicle',
            'detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order.destination',
            'outlet.destination'
        ]);

        if (Auth::user()->role_id == 1) {
            if (!empty($params['outlet_id'])) {
                $query->where('surattugas.outlets_id', $params['outlet_id']);
            }
        } else {
            $query->where('surattugas.outlets_id', Auth::user()->outlets_id);
        }

        if (!empty($params['driver'])) {
            $query->where('surattugas.driver_id', $params['driver']);
        }

        if (!empty($params['destination'])) {
            $query->whereHas('detailsurattugas.traveldocument', function ($q) use ($params) {
                $q->where('traveldocuments.destinations_id', $params['destination']);
            });
        }

        if (!empty($params['tanggal_awal_berangkat']) && !empty($params['tanggal_akhir_berangkat'])) {
            $endOfDay = date('Y-m-d 23:59:59', strtotime($params['tanggal_akhir_berangkat']));
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($params, $endOfDay) {
                $q->whereBetween('traveldocuments.start', [$params['tanggal_awal_berangkat'], $endOfDay]);
            });
        } elseif (!empty($params['tanggal_awal_berangkat'])) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($params) {
                $q->whereDate('traveldocuments.start', $params['tanggal_awal_berangkat']);
            });
        } elseif (!empty($params['tanggal_akhir_berangkat'])) {
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($params) {
                $q->whereDate('traveldocuments.start', $params['tanggal_akhir_berangkat']);
            });
        }

        if (!empty($params['jenis_pengiriman'])) {
            $query->whereHas('detailsurattugas.traveldocument.detailtraveldocument.manifest.detailmanifests.order', function($q) use ($params) {
                $q->where('orders.armada', $params['jenis_pengiriman']);
            });
        }

        if (is_numeric($params['status_surattugas'])) {
            if ($params['status_surattugas'] == '5') {
                $query->whereIn('surattugas.statussurattugas', ['0', '1', '2']);
            } else {
                $query->where('surattugas.statussurattugas', $params['status_surattugas']);
            }
        }


        return DataTables::of($query)
            ->editColumn('driver', function ($query) {
                return optional($query->driver)->name ?? '-';
            })
            ->editColumn('travelno', function ($query) {
                return optional(optional($query->detailsurattugas->first())->traveldocument)->travelno ?? '-';
            })
            ->editColumn('vehicle', function ($query) {
                return optional($query->vehicle)->police_no ?? '-';
            })
            ->editColumn('start', function ($query) {
                return optional(optional($query->detailsurattugas->first())->traveldocument)->start ?? '-';
            })
            ->editColumn('finish_date', function ($query) {
                return optional(optional($query->detailsurattugas->first())->traveldocument)->finish_date ?? '-';
            })
            ->editColumn('armada', function ($query) {
                $order = optional(
                    optional(
                        optional(
                            optional(
                                optional(
                                    optional(
                                        optional($query->detailsurattugas->first())->traveldocument
                                    )->detailtraveldocument
                                )->manifest
                            )->first()
                        )->detailmanifests
                    )->first()
                )->order;

                $armada = optional($order)->armada;
                return match ($armada) {
                    1 => 'Darat',
                    2 => 'Laut',
                    3 => 'Udara',
                    default => '-',
                };
            })
            ->editColumn('outlets', function ($query) {
                return optional(optional($query->outlet)->destination)->name ?? '-';
            })
            ->editColumn('destination', function ($query) {
                $traveldocument = optional($query->detailsurattugas->first())->traveldocument;
                return optional(optional($traveldocument)->destination)->name ?? '-';
            })
            ->editColumn('volume/weight', function ($query) {
                $order = optional(
                    optional(
                        optional(
                            optional(
                                optional(
                                    optional(
                                        optional($query->detailsurattugas->first())->traveldocument
                                    )->detailtraveldocument
                                )->manifest
                            )->first()
                        )->detailmanifests
                    )->first()
                )->order;

                return optional($order)->weight ?? optional($order)->volume ?? '-';
            })
            ->editColumn('totalvolume/berat', function ($query) {
                $order = optional(
                    optional(
                        optional(
                            optional(
                                optional(
                                    optional(
                                        optional($query->detailsurattugas->first())->traveldocument
                                    )->detailtraveldocument
                                )->manifest
                            )->first()
                        )->detailmanifests
                    )->first()
                )->order;

                return optional($order)->weight ?? optional($order)->volume ?? '-';
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

        $query = Order::with('customer', 'destination', 'outlet.destination', 'detailmanifests.manifest.detailtraveldocument.traveldocument');


        if (Auth::user()->role_id == 1) {
            if (isset($params['outlet_id'])) {
                $query->where('outlet_id', $params['outlet_id']);
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
            $query->whereBetween('created_at', [$params['tanggal_order_awal'], $endOfDay]);
        } elseif ($params['tanggal_order_awal'] != null) {
            $query->whereDate('created_at', $params['tanggal_order_awal']);
        } elseif ($params['tanggal_order_akhir'] != null) {
            $query->whereDate('created_at', $params['tanggal_order_akhir']);
        }


        if ($params['status'] != null) {
            if ($params['status'] == '5') {
                $query->whereIn('status_orders', ['1', '2', '3', '4']);
            } else {
                $query->where('status_orders', $params['status']);
            }
        }



        return DataTables::of($query)
            ->editColumn('customer', function ($query) {
                return $query->customer->name ?? '-';
            })
            ->editColumn('numberorders', function ($query) {
                return $query->numberorders ?? '-';
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at ?? '-';
            })
            ->editColumn('finish_date', function ($query) {
                return $query->detailmanifests->manifest->detailtraveldocument->traveldocument->finish_date ??  '-';
            })
            ->editColumn('outlets_id', function ($query) {
                return $query->outlet->destination->name ?? '-';
            })
            ->editColumn('destinations_id', function ($query) {
                return $query->destination->name ?? '-';
            })
            ->editColumn('volume/weight', function ($query) {
                return $query->weight ?? $query->volume ?? '-';
            })
            ->editColumn('totalvolume/berat', function ($query) {
                return $query->weight ?? $query->volume ?? '-';
            })
            ->editColumn('price', function ($query) {
                return $query->price ?? '-';
            })
            ->rawColumns([])
            ->addIndexColumn()
            ->make(true);
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
            $endOfDay = date('Y-m-d 23:59:59', strtotime($request->tanggal_akhir_berangkat));
            $query->whereHas('detailsurattugas.traveldocument', function($q) use ($request, $endOfDay) {
                $q->whereBetween('start', [$request->tanggal_awal_berangkat, $endOfDay]);
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
