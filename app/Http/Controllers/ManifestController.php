<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Manifest;
use Illuminate\Http\Request;
use App\Models\Detailmanifest;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use App\Models\Outlet;
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
            $q->where('orders.outlet_id', auth()->user()->outlets_id);
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
                if ($x->status_manifest != 3 && $x->status_manifest != 2) {
                    $option = '<div>';
                    $option .= '<a href="manifest/' . Crypt::encrypt($x->id) . '/edit" class="btn btn-warning btn-sm "><i class="fa fa-edit"></i></a> ';
                    $option .= '<button class="btn btn-danger btn-sm" onclick="deleteManifest(this, ' . $x->id . ')"><i class="fa fa-trash"></i></button> ';
                    $option .= '<a href="manifest/' . Crypt::encrypt($x->id) . '/print" target="_blank" class="btn btn-success btn-sm" title="Cetak Resi Manifest"><i class="fa fa-print"></i></a></div>';
                    return $option;
                }
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
        $dataManifest = [
            'manifestno'    => $request->manifestno,
            'carier'        => $request->carrier,
            'commodity'     => $request->commodity,
            'flight_no'     => $request->flightno,
            'no_bags'       => $request->nobags,
            'flight_file'   => $request->flagsfile,
            'notes'         => $request->notes,
            'no_smd'        => $request->no_smd,
            'outlet_id' => $request->outlet_id,
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
        return ResponseFormatter::success([], 'Manifest berhasil di simpan.!');
    }

    //edit
    function edit($id)
    {
        return view('pages.manifest.update');
    }

    //get detail
    function getdetail($id)
    {
        $datamanifest           = Manifest::where('id', Crypt::decrypt($id))->firstOrFail();
        $q = DB::table('orders')
            ->join('users', 'orders.customer_id', '=', 'users.id')
            ->join('destinations', 'orders.destinations_id', '=', 'destinations.id')
            ->join('detailmanifests', 'orders.id', '=', 'detailmanifests.orders_id')
            ->select('orders.id', 'orders.outlet_id', 'orders.status_orders', 'orders.numberorders', 'users.name as namacustomer', 'destinations.name as destination', 'detailmanifests.manifests_id', 'orders.weight', 'detailmanifests.id as detailmanifestid')
            ->where('detailmanifests.manifests_id', Crypt::decrypt($id))
            ->where('status_orders', '2')
            ->get();
        return ResponseFormatter::success([
            'manifest'          => $datamanifest,
            'detailmanifest'    => $q
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
        DetailManifest::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Berhasil menghaputs data');
    }

    function update(Request $request, $id)
    {
        $dataManifest = [
            'manifestno'    => $request->manifestno,
            'carier'        => $request->carrier,
            'commodity'     => $request->commodity,
            'flight_no'     => $request->flightno,
            'no_bags'       => $request->nobags,
            'flight_file'   => $request->flagsfile
        ];
        // $manifest = Manifest::create($dataManifest);
        Manifest::where('id', Crypt::decrypt($id))->update($dataManifest);
        return ResponseFormatter::success([], 'Manifest berhasil di perbaharui.!');
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
        $manifest       = Manifest::where('id', Crypt::decrypt($id))->first();
        return view('pages.manifest.resi', compact('dataManifest', 'manifest'));
    }
}
