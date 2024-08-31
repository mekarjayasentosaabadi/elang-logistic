<?php

namespace App\Http\Controllers;

use App\Models\Manifest;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Surattugas;
use App\Models\Traveldocument;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

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

    function getListOrder(Request $request)
    {
        if (Auth::user()->role_id == '1') {
            $q = Order::with('customer', 'histories');
        } else {
            $q = Order::where('outlet_id', Auth::user()->outlets_id)->with('customer', 'histories')->get();
        }

        if ($request->update_data == '2' && $request->noResi) {
            // $q where order id in manifest detail
            $q = $q->whereHas('detailmanifests', function ($query) use ($request) {
                $query->whereIn('manifests_id', $request->noResi);
            });
        } elseif ($request->update_data == '1' && $request->noResi) {
            //    get list manifest from surat tugas
            $surattugas = Surattugas::whereIn('id', $request->noResi)->get();
            $data = [];
            foreach ($surattugas as $key => $value) {
                $data[] = $value->detailsurattugas->pluck('manifest_id')->toArray();
            }
            $q = $q->whereHas('detailmanifests', function ($query) use ($data) {
                $query->whereIn('manifests_id', $data);
            });
        } else {
            $q = $q->where('id', 0);
        }


        return DataTables::of($q)
            ->editColumn('destination', function ($query) {
                return $query->destination->name;
            })
            ->addColumn('numberorders', function ($query) {
                return $query->numberorders ?? '-';
            })
            ->addColumn('pengirim', function ($query) {
                return $query->customer->name ?? '-';
            })
            ->addColumn('penerima', function ($query) {
                return $query->penerima ?? '-';
            })
            ->editColumn('status_orders', function ($query) {
                $html = status_html($query->status_orders);
                $html .= '<small class="text-sm"><br/><i class="fas fa-truck"></i> ' . $query->status_awb . '</small>';
                return  $html;
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at ? $query->created_at->format('d-m-Y H:i') : '-';
            })
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                //detail
                $btn .= '<a href="' . url('/order/' . $encryptId . '/detail') . '" target="_blank" class="btn btn-primary btn-sm" title="Detail"><i class="fa fa-eye"></i></a> ';
                return $btn;
            })->rawColumns(['numberorders', 'pengirim', 'penerima', 'created_at', 'status_orders', 'aksi', 'created_at'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getListManifest(Request $request)
    {

        $q = Manifest::with(['destination', 'outlet']);
        if (auth()->user()->role_id != 1) {
            $q->where('outlet_id', auth()->user()->outlets_id);
        }


        if ($request->update_data == '2' && $request->noResi) {
            // $q where order id in manifest detail
            $q = $q->whereHas('detailmanifests', function ($query) use ($request) {
                $query->whereIn('manifests_id', $request->noResi);
            });
        } elseif ($request->update_data == '1' && $request->noResi) {
            //    get list manifest from surat tugas
            $surattugas = Surattugas::whereIn('id', $request->noResi)->get();
            $data = [];
            foreach ($surattugas as $key => $value) {
                $data[] = $value->detailsurattugas->pluck('manifest_id')->toArray();
            }
            $q = $q->whereHas('detailmanifests', function ($query) use ($data) {
                $query->whereIn('manifests_id', $data);
            });
        } else {
            $q = $q->where('id', 0);
        }


        return DataTables::of($q)
            ->editColumn('destination', function ($e) {
                return $e->destination->name;
            })
            ->editColumn('jumlah', function ($e) {
                return $e->detailmanifests->count();
            })
            ->addColumn('status', function ($e) {
                if ($e->status_manifest == 0) {
                    $status = '<div class="text-danger">';
                    $status .= 'Cancel</div>';
                    return $status;
                } elseif ($e->status_manifest == 1) {
                    $status = '<div class="text-primary"><li class="fa fa-gears"></li> ';
                    $status .= 'Process</div>';
                    return $status;
                } elseif ($e->status_manifest == 2) {
                    $status = '<div class="text-primary"><li class="fa fa-truck"></li> ';
                    $status .= 'On The Way</div>';
                    return $status;
                } else {
                    $status = '<div class="text-success"><li class="fa fa-check"></li> ';
                    $status .= 'Done</div>';
                    return $status;
                }
            })
            ->addColumn('option', function ($x) {
                return "";
            })
            ->rawColumns(['status', 'option'])
            ->addIndexColumn()
            ->make(true);
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
                }
            } else {
                // manifest
                // get manifest
                $manifest = Manifest::whereIn('id', $request->noResi)->get();
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
