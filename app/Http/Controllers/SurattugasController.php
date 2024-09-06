<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Vehicle;
use App\Models\Manifest;
use App\Models\HistoryAwb;
use App\Models\Surattugas;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Detailmanifest;
use App\Models\Traveldocument;
use App\Models\Detailsurattugas;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Models\Detailtraveldocument;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class SurattugasController extends Controller
{
    public function index()
    {
        return view('pages.surattugas.index');
    }

    function getAll()
    {
        $table = Surattugas::query();
        if (auth()->user()->role_id != 1) {
            $table->where('outlets_id', auth()->user()->outlets_id);
        }

        return DataTables::of($table)

            ->addColumn('jumlah_surat_tugas', function ($x) {
                return $x->detailsurattugas->count();
            })
            ->addColumn('status', function ($x) {
                if ($x->statussurattugas == 0) {
                    return '<span class="text-danger">Cancel</span>';
                } else if ($x->statussurattugas == 1) {
                    return '<span class="text-success">Process</span>';
                } else if ($x->statussurattugas == 2) {
                    return '<span class="text-primary">On The Way</span>';
                } else {
                    return '<span class="text-primary">Done</span>';
                }
            })
            ->addColumn('option', function ($x) {
                $option = '<div>';

                $option .= '<a href="surattugas/' . Crypt::encrypt($x->id) . '/printsurattugas" class="btn btn-primary btn-sm" title="Cetak Surat Tugas"><li class="fa fa-print"></li></a> ';
                $option .= '<a title="Detail Surat Tugas" href="surattugas/' . Crypt::encrypt($x->id) . '/detail" class="btn btn-success btn-sm "><i class="fa fa-list"></i></a> ';
                if ($x->statussurattugas == 1) {
                    $option .= '<button class="btn btn-success btn-sm" title="Berangkatkan" onclick="onGoing(' . $x->id . ')"><li class="fa fa-truck"></li></button> ';
                    $option .= '<button class="btn btn-danger btn-sm" onclick="deleteSuratTugas(this, ' . $x->id . ')"><i class="fa fa-trash"></i></button> ';
                }
                return $option;
            })
            ->rawColumns(['status', 'option'])
            ->addIndexColumn()
            ->make(true);
    }

    function create()
    {
        $destination    = Destination::all();
        $vehicle        = Vehicle::where('is_active', '1')->get();
        $driver         = User::where('role_id', '5')->where('outlets_id', auth()->user()->outlets_id)->get();
        $outlets = Outlet::all();
        return view('pages.surattugas.create', compact('destination', 'vehicle', 'driver', 'outlets'));
    }

    function getManifest($id)
    {
        $outletId = auth()->user()->role_id == 1 ? $id : auth()->user()->outlet->id;
        $db = Manifest::with(['destination', 'detailmanifests']);
        $db->whereDoesntHave('detailSuratTugas');
        $db->where('outlet_id', $outletId)->where('status_manifest', '1');
        $db = $db->get();
        return ResponseFormatter::success(['dataManifest' => $db], 'Berhasil mengambil data');
    }

    function store(Request $request)
    {
        DB::beginTransaction();
        try {

            // check if surat tugas already exist
            $checkSuratTugas = Surattugas::where('nosurattugas', $request->suratTugas)->first();
            if ($checkSuratTugas) {
                return ResponseFormatter::error([], 'Nomor surat tugas sudah ada', 500);
            }

            $outletId = auth()->user()->role_id == 1 ? $request->outlet_id : auth()->user()->outlet->id;
            $storedDataSuratJalan = [
                'nosurattugas'      => $request->suratTugas,
                'statussurattugas'  => 1,
                'note'              => $request->description,
                'outlets_id'        => $outletId,
                'vehicle_id'        => $request->vehicle_id,
                'driver_id'         => $request->driver_id,
                'destination_id'    => $request->destination_id
            ];
            $suratTugas = Surattugas::create($storedDataSuratJalan);
            $input = $request->input();
            $dataDetail = [];
            if (@$input['suratjalan']) {
                foreach ($input['suratjalan'] as $key => $value) {
                    $dataDetail[] = [
                        'surattugas_id'         => $suratTugas->id,
                        'manifest_id'    => $value,
                        'created_at'            => now(),
                        'updated_at'            => now()
                    ];
                }
            }
            Detailsurattugas::insert($dataDetail);
            DB::commit();
            return ResponseFormatter::success([
                'detailSt'      => $dataDetail
            ], 'Surat tugas berhasil di simpan');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([$error], 'Something went wrong');
        }
    }

    function delete($id)
    {
        Detailsurattugas::where('surattugas_id', $id)->delete();
        Surattugas::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Berhasil menghapus data Surat tugas');
    }

    function edit($id)
    {
        $destination    = Destination::all();
        $vehicle        = Vehicle::where('is_active', '1')->get();
        $driver         = User::where('role_id', '5')->where('outlets_id', auth()->user()->outlets_id)->get();
        $outlets = Outlet::all();
        $surattugas     = Surattugas::find(Crypt::decrypt($id));
        return view('pages.surattugas.edit', compact('destination', 'surattugas', 'vehicle', 'driver', 'outlets'));
    }

    function getListSuratJalan($id)
    {
        $listSuratTugas = DB::table('detailsurattugas')
            ->leftJoin('traveldocuments', 'detailsurattugas.traveldocuments_id', '=', 'traveldocuments.id')
            ->leftJoin('detailtraveldocuments', 'traveldocuments.id', '=', 'detailtraveldocuments.traveldocuments_id')
            ->select('detailsurattugas.id as idsurattugas', 'traveldocuments.id', 'traveldocuments.travelno', DB::raw('count(detailtraveldocuments.manifests_id) as jml_manifest'))
            ->where('detailsurattugas.surattugas_id', Crypt::decrypt($id))
            ->groupBy('detailsurattugas.id', 'traveldocuments.id', 'traveldocuments.travelno')
            ->get();

        return ResponseFormatter::success(['listSuratTugas' => $listSuratTugas], 'Get data successfuly');
    }

    function deleteList($id)
    {
        Detailsurattugas::where('id', $id)->delete();
        return ResponseFormatter::success([], 'Data surat tugas berhasil di hapus.!!');
    }

    function onGoing($id)
    {

        DB::beginTransaction();
        try {

            $suratTugas = Surattugas::find($id);
            foreach ($suratTugas->detailsurattugas as $key => $value) {
                $value->manifest->update([
                    'status_manifest' => 2
                ]);
                $order = Order::whereIn('id', $value->manifest->detailmanifests->pluck('orders_id'))->update([
                    'status_orders' => 2
                ]);

                $orderDataComp = Order::whereIn('id', $value->manifest->detailmanifests->pluck('orders_id'))->get();
                //Insert data History AWB
                $arrDataHistory = [];
                foreach ($orderDataComp as $key => $order) {
                    $gudangLocation = Outlet::find($order->outlet_id);
                    $arrDataHistory[] = [
                        'order_id'      => $order['id'],
                        'awb'           => $order['numberorders'],
                        'status'        => 'Pesanan di berangkatkan dari ' . $gudangLocation->name,
                        'created_by'    => auth()->user()->id,
                        'created_at'    => now(),
                        'updated_at'    => now()
                    ];
                    $order = Order::find($order['id']);
                    $order->update([
                        'status_awb' => 'Pesanan di berangkatkan dari ' . $gudangLocation->name
                    ]);
                }
                HistoryAwb::insert($arrDataHistory);
            }
            Surattugas::where('id', $id)->update([
                'statussurattugas' => 2
            ]);
            DB::commit();
            return ResponseFormatter::success([], 'Berhasil meng on goingkan surat tugas');
        } catch (Exception $error) {
            DB::rollBack();
            return ResponseFormatter::error([], 'Something went wrong');
        }
    }

    //detail surat tugas
    function detailsurattugas($id){
        $datailSuratTugas = Surattugas::with(['outlet', 'driver', 'vehicle', 'destination'])->where('id', Crypt::decrypt($id))->first();
        $listSuratTugasManifest = Surattugas::with(['destination', 'detailsurattugas.manifest'])->where('id', Crypt::decrypt($id))->get();
        return view('pages.surattugas.detail', compact('datailSuratTugas', 'listSuratTugasManifest' ));
    }


    function printsurattugas($id) {
        try {
            $id = decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }


        $surattugas = Surattugas::find($id);

        $totalKoli = 0;
        $totalBerat = 0;

        foreach ($surattugas->detailsurattugas as $detailSurat) {
            if ($detailSurat->manifest) {
                foreach ($detailSurat->manifest->detailmanifests as $detailManifest) {
                    $order = $detailManifest->order;
                    if ($order) {
                        $totalKoli += $order->koli;
                        $totalBerat += $order->weight;
                    }
                }
            }
        }
        return view('pages.surattugas.surattugas', compact('surattugas', 'totalKoli', 'totalBerat'));
    }
}
