<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Outlet;
use App\Models\Manifest;
use App\Models\Surattugas;
use Illuminate\Http\Request;
use App\Models\Detailmanifest;
use App\Models\Detailsurattugas;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ManifestController extends Controller
{
    public function index()
    {
        $outlets = Outlet::all();
        return view('pages.manifest.index', compact('outlets'));
    }
    public function getAll()
    {

        $q = Manifest::with(['destination', 'outlet']);
        if (auth()->user()->role_id != 1) {
            $q->where('outlet_id', auth()->user()->outlets_id);
        }

        $q->orderByRaw("
                CASE
                    WHEN status_manifest = 1 THEN 1
                    WHEN status_manifest = 2 THEN 2
                    WHEN status_manifest = 3 THEN 3
                END
        ");

        return DataTables::of($q)
            ->editColumn('destination', function ($e) {
                return $e->destination->name ?? '-';
            })
            ->editColumn('jumlah', function ($e) {
                return $e->detailmanifests->count();
            })
            ->editColumn('notes', function ($e) {
                return $e->notes;
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
                $option = '<div>';
                if ($x->status_manifest != 3 && $x->status_manifest != 2) {
                    $option .= '<a title="Edit Manifest" href="manifest/' . Crypt::encrypt($x->id) . '/edit" class="btn btn-warning btn-sm "><i class="fa fa-edit"></i></a> ';
                    $option .= '<button title="Delete Manifest" class="btn btn-danger btn-sm" onclick="deleteManifest(this, ' . $x->id . ')"><i class="fa fa-trash"></i></button> ';
                }
                $option .= '<a title="Detail Manifest" href="manifest/' . Crypt::encrypt($x->id) . '/detail" class="btn btn-success btn-sm "><i class="fa fa-list"></i></a> ';
                $option .= '<a href="manifest/' . Crypt::encrypt($x->id) . '/print" target="_blank" class="btn btn-success btn-sm" title="Cetak Resi Manifest"><i class="fa fa-print"></i></a></div>';

                if ($x->detailSuratTugas != null) {
                    $option .= '<a href="manifest/' . Crypt::encrypt($x->id) . '/printSmd" target="_blank" class="btn btn-success btn-sm" title="Cetak SMD"><i class="fa fa-print"></i></a></div>';
                }
                return $option;
            })
            ->rawColumns(['status', 'option'])
            ->addIndexColumn()
            ->make(true);
    }
    function getOrders(Request $request)
    {
        $outletId = auth()->user()->role_id == 1 ? $request->outlet_id : auth()->user()->outlet->id;

        $q = Order::with(['customer', 'destination'])->where('outlet_id', $outletId)->where('status_orders', '2');
        // where orders_id not in detailmanifests
        $q->whereDoesntHave('detailmanifests');


        return DataTables::of($q)
            ->addColumn('namecustomer', function ($e) {
                return $e->customer->name;
            })
            ->editColumn('destination', function ($e) {
                return $e->destination->name;
            })
            ->editColumn('weight', function ($e) {
                return $e->weight ?? $e->volume;
            })
            ->addColumn('check', function ($cek) {
                $valueCheck = $cek->id;
                $checked = in_array($valueCheck, request()->input('idOrders', [])) ? 'checked' : '';
                $check = '<div>';
                $check .= '<input class="form-check-input checkbox-table" id="checkbox-table-' . $valueCheck . '" name="checkbox' . $valueCheck . '" type="checkbox" id="checkbox[]" ' . $checked . ' value="' . $valueCheck . '" />';
                $check .= '</div>';
                return $check;
            })
            ->rawColumns(['check'])
            ->addIndexColumn()
            ->make(true);
    }
    function create()
    {
        $outlets = Outlet::all();
        return view('pages.manifest.create', compact('outlets'));
    }

    //get detail orders to array
    function checkOrders($id)
    {
        $data = Order::with(['customer', 'destination'])->where('id', $id)->firstOrFail();
        return ResponseFormatter::success([$data], 'get data successfuly');
    }

    //stored
    function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // check maniest if exist
            $checkManifest = Manifest::where('manifestno', $request->manifestno)->first();
            if ($checkManifest) {
                return ResponseFormatter::error([], 'Nomor manifest sudah ada');
            }

            // check if manifest long then 10 char
            if (strlen($request->manifestno) > 10) {
                return ResponseFormatter::error([], 'Nomor manifest tidak boleh lebih dari 10 karakter');
            }

            // check no_smd
            if (strlen($request->no_smd) > 10) {
                return ResponseFormatter::error([], 'Nomor SMD tidak boleh lebih dari 10 karakter');
            }

            $checkSmd = Manifest::where('no_smd', $request->no_smd)->first();
            if ($checkSmd) {
                return ResponseFormatter::error([], 'Nomor SMD sudah ada');
            }

            $outletId = auth()->user()->role_id == 1 ? $request->outlet_id : auth()->user()->outlet->id;
            $dataManifest = [
                'manifestno'    => $request->manifestno,
                'carier'        => $request->carrier,
                'commodity'     => $request->commodity,
                'flight_no'     => $request->flightno,
                'no_bags'       => $request->nobags,
                'flight_file'   => $request->flagsfile,
                'notes'         => $request->notes,
                'no_smd'        => $request->no_smd,
                'outlet_id' => $outletId,
                'destination_id' => $request->destination_id
            ];
            $manifest = Manifest::create($dataManifest);
            $dataDetail = [];
            $input = $request->input();
            if (@$input['ordersid']) {
                foreach ($input['ordersid'] as $key => $value) {
                    $dataDetail[] = [
                        'manifests_id'      => $manifest->id,
                        'orders_id'         => $input['ordersid'][$key]
                    ];
                }
            }
            Detailmanifest::insert($dataDetail);
            DB::commit();
            return ResponseFormatter::success([], 'Manifest berhasil di simpan.!');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error([$e->getMessage()], 'Gagal menambahkan data');
        }
    }

    //edit
    function edit($id)
    {
        $outlets = Outlet::all();
        $manifest = Manifest::where('id', Crypt::decrypt($id))->firstOrFail();
        $manifest->listArrayId = json_encode($manifest->detailmanifests->pluck('orders_id')->toArray());
        return view('pages.manifest.update', compact('outlets', 'manifest'));
    }

    //get detail
    function getdetail($id)
    {
        $datamanifest           = Manifest::where('id', $id)->firstOrFail();
        return ResponseFormatter::success([
            'manifest'          => $datamanifest,
            'detailmanifest'    => $datamanifest->detailmanifests
        ], 'Success get data');
    }

    //delete
    function delete(Request $request, $id)
    {
        Detailmanifest::where('manifests_id', $id)->delete();
        Manifest::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Success menghapus data');
    }

    function deletedetailold(Request $request, $id)
    {
        // cek
        Detailmanifest::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Berhasil menghaputs data');
    }

    function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // check if manifest long then 10 char
            if (strlen($request->manifestno) > 10) {
                return ResponseFormatter::error([], 'Nomor manifest tidak boleh lebih dari 10 karakter');
            }

            // check maniest if exist
            $checkManifest = Manifest::where('manifestno', $request->manifestno)->where('id', '!=', $id)->first();
            if ($checkManifest) {
                return ResponseFormatter::error([], 'Nomor manifest sudah ada', 500);
            }

            // check no_smd
            if (strlen($request->no_smd) > 10) {
                return ResponseFormatter::error([], 'Nomor SMD tidak boleh lebih dari 10 karakter');
            }

            $checkSmd = Manifest::where('no_smd', $request->no_smd)->where('id', '!=', $id)->first();
            if ($checkSmd) {
                return ResponseFormatter::error([], 'Nomor SMD sudah ada', 500);
            }

            // remove detail manifest
            Detailmanifest::where('manifests_id', $id)->delete();
            $outletId = auth()->user()->role_id == 1 ? $request->outlet_id : auth()->user()->outlet->id;
            $dataManifest = [
                'manifestno'    => $request->manifestno,
                'carier'        => $request->carrier,
                'commodity'     => $request->commodity,
                'flight_no'     => $request->flightno,
                'no_bags'       => $request->nobags,
                'flight_file'   => $request->flagsfile,
                'notes'         => $request->notes,
                'no_smd'        => $request->no_smd,
                'outlet_id' => $outletId,
                'destination_id' => $request->destination_id
            ];
            $manifest = Manifest::where('id', $id)->update($dataManifest);

            $dataDetail = [];
            $input = $request->input();
            if (@$input['ordersid']) {
                foreach ($input['ordersid'] as $key => $value) {
                    $dataDetail[] = [
                        'manifests_id'      => $id,
                        'orders_id'         => $input['ordersid'][$key]
                    ];
                }
            }
            Detailmanifest::insert($dataDetail);
            DB::commit();
            return ResponseFormatter::success([], 'Manifest berhasil di ubah.!');
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error([$e->getMessage()], 'Gagal menambahkan data');
        }
    }

    function adddetail(Request $request, $id, $ordersid)
    {
        $manifest = Manifest::where('id', Crypt::decrypt($id))->first();
        $dataUpdate = [
            'manifests_id'      => $manifest->id,
            'orders_id'         => $ordersid
        ];
        Detailmanifest::insert($dataUpdate);
        return ResponseFormatter::success([], 'Berhasil menambahkan data');
    }

    function printresi($id)
    {
        $dataManifest   = Detailmanifest::with('order.destination')->where('manifests_id', Crypt::decrypt($id))->get();
        $manifest       = Manifest::with(['destination', 'outlet'])->where('id', Crypt::decrypt($id))->first();
        return view('pages.manifest.resi', compact('dataManifest', 'manifest'));
    }

    //detail manifest
    function detailmanifest($id)
    {
        $outlets = Outlet::all();
        $manifest = Manifest::where('id', Crypt::decrypt($id))->firstOrFail();
        $manifest->listArrayId = json_encode($manifest->detailmanifests->pluck('orders_id')->toArray());
        return view('pages.manifest.detail', compact('outlets', 'manifest'));
    }

    function printSmd($id) {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }


        $manifest = Manifest::find($id);

        $totalKoli = 0;
        $totalBerat = 0;
        foreach ($manifest->detailmanifests as $detailManifest) {
            $order = $detailManifest->order;
            if ($order) {
                $totalKoli += $order->koli;
                $totalBerat += $order->weight;
            }
        }
        return view('pages.manifest.smd', compact('manifest', 'totalKoli', 'totalBerat'));
    }
}
