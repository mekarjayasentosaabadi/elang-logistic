<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\ShippingCourier;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailShippingCourier;
use App\Models\Manifest;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class ShippingcourierController extends Controller
{
    public function index() {
        return view('pages.shippingcourier.index');
    }

    public function getAll()
    {

        if (Auth::user()->role_id == '2') {

             $dataUser = User::where('id', Auth::user()->id)->first();
             $userOutlet = Outlet::where('id', $dataUser->outlets_id)->first();

             $q = ShippingCourier::with(['detailshippingcourier.order', 'driver'])
                    ->whereHas('detailshippingcourier.order', function($query) use ($userOutlet) {
                        $query->where('destinations_id', $userOutlet->location_id);
             });


        }else if(Auth::user()->role_id == '3'){
            $q = ShippingCourier::with(['detailshippingcourier.order', 'driver'])
                ->where('driver_id', Auth::user()->id)

            ->get();
        }

        return DataTables::of($q)
            ->editColumn('shippingno', function ($query) {
                return $query->shippingno;
            })
            ->editColumn('nama_kurir', function ($query) {
                return $query->driver->name;
            })
            ->editColumn('order_id', function ($query) {
                return $query->detailshippingcourier->count();
            })
            ->editColumn('status', function ($query) {
                if ($query->status_shippingcourier == '1') {
                    return '<span class="badge badge-light-primary">Process</span>';
                } else if($query->status_shippingcourier == '2'){
                    return '<span class="badge badge-light-success">Finish</span>';
                } else {
                    return '<span class="badge badge-light-danger">Cancle</span>';
                }
            })
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                $btn .= '<div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"  href="' . url('/shipping-courier/' . $encryptId . '/edit') . '">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                            <span>Edit</span>
                        </a>
                    </div>
                </div>';
                return $btn;
            })
            ->rawColumns(['shippingno', 'nama_kurir', 'status', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getOrder(){
        $outletsByUser = Outlet::where('id', Auth::user()->outlets_id)->first();

        $q   = Order::where('destinations_id', $outletsByUser->location_id)
                    ->where('status_orders', 2)
                    ->whereDoesntHave('detailshippingcourier')
                    ->whereHas('detailmanifests.manifest', function ($query){
                        $query->where('status_manifest', '3');
                    })->get();

        return DataTables::of($q)
            ->addColumn('numberorders', function ($query){
                return $query->numberorders;
            })
            ->addColumn('namacustomer', function ($query){
                return $query->customer->name;
            })
            ->addColumn('destination', function ($query){
                return $query->destination->name;
            })
            ->addColumn('check', function($cek){
                $valueCheck = $cek->id;
                $check = '<div>
                            <input class="form-check-input" name="checkbox" type="checkbox" id="" value="' . $valueCheck . '" onchange="check(this)""/>
                          </div>';
                return $check;
            })
            ->rawColumns(['numberorders', 'namacustomer', 'destination','check'])
            ->addIndexColumn()
            ->make(true);
    }

    public function getOrderDetail(Request $request){
        $order = Order::find($request->id);
        return response()->json([
            'numberorders'  => $order->numberorders,
            'customer'      => $order->customer->name,
            'weight'        => $order->weight,
            'destination'   => $order->destination->name,
            'id'            => $order->id
        ]);
    }

    public function create() {
        $couriers = User::where('role_id', '3')->where('outlets_id', Auth::user()->outlets_id)->get();
        $outlets  = Outlet::all();



        return view('pages.shippingcourier.create', compact('couriers', 'outlets', ));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'shipping_no' => 'required',
            'courier'     => 'required',
            'order_ids'   => 'required',
            'note'        => 'required',
        ], [
            'shipping_no.required'     => 'Nomor pengiriman harus diisi',
            'courier.required'         => 'Pilih kurir',
            'order_ids.required'       => 'Pilih paket yang akan dikirim',
            'note.required'            => 'Catatan harus diisi',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            $errorMessage =implode(', ', $error);
            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }


        $shippingCourier = new ShippingCourier();
        $shippingCourier->shippingno = $request->shipping_no;
        $shippingCourier->driver_id  = $request->courier;
        $shippingCourier->notes      = $request->note;
        $shippingCourier->save();

        foreach ($request['order_ids'] as $order_id) {
            $detailShippingCourier = new DetailShippingCourier();
            $detailShippingCourier->shipping_id = $shippingCourier->id;
            $detailShippingCourier->orders_id = $order_id;
            $detailShippingCourier->save();
        }

        Alert::success('Berhasil', 'Data pengiriman berhasil ditambahkan.');
        return redirect('/shipping-courier');
    }

    public function edit($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }


        $couriers = User::where('role_id', '3')->where('outlets_id', Auth::user()->outlets_id)->get();
        $outlets  = Outlet::all();
        $outletsByUser = Outlet::where('id', Auth::user()->outlets_id)->first();

        $shippingCourier = ShippingCourier::with('detailshippingcourier')->where('id', $id)->first();
        $orders = Order::where('destinations_id', $outletsByUser->location_id)
                        ->where('status_orders', 2)
                        ->whereHas('detailmanifests.manifest', function ($query){
                            $query->where('status_manifest', '3');
                        })->get();



        return view('pages.shippingcourier.edit', compact('couriers', 'outlets', 'orders', 'shippingCourier'));
    }

    public function update(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'shipping_no' => 'required',
            'courier'     => 'required',
            'order_ids'   => 'required|array',
            'note'        => 'required',
        ],[
            'shipping_no.required'     => 'Nomor pengiriman harus diisi',
            'courier.required'         => 'Pilih kurir',
            'order_ids.required'       => 'Pilih paket yang akan dikirim',
            'note.required'            => 'Catatan harus diisi',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            $errorMessage = implode(', ', $error);
            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }

        $shippingCourier = ShippingCourier::find($id);
        if (!$shippingCourier) {
            Alert::error('Gagal', 'Data pengiriman tidak ditemukan.');
            return redirect()->back();
        }

        $shippingCourier->update([
            'shippingno' => $request->shipping_no,
            'driver_id' => $request->courier,
            'notes' => $request->note,
        ]);


        DetailShippingCourier::where('shipping_id', $shippingCourier->id)->delete();

        foreach ($request->order_ids as $order_id) {
            $detailShippingCourier = new DetailShippingCourier();
            $detailShippingCourier->shipping_id = $shippingCourier->id;
            $detailShippingCourier->orders_id = $order_id;
            $detailShippingCourier->save();
        }

        Alert::success('Berhasil', 'Data pengiriman berhasil diperbarui.');
        return redirect('/shipping-courier');
    }

}
