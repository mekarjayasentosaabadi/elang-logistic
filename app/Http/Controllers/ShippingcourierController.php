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
use App\Models\HistoryAwb;
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

    // shipping-courier: Admin / Superadmin / courier get all shipping ajax response
    public function getAll()
    {

        // superadmin
        if (Auth::user()->role_id == '1') {
            $q = ShippingCourier::with(['detailshippingcourier.order', 'driver']);
        }

        // admin
        else if (Auth::user()->role_id == '2') {

             $dataUser = User::where('id', Auth::user()->id)->first();
             $userOutlet = Outlet::where('id', $dataUser->outlets_id)->first();

             $q = ShippingCourier::with(['detailshippingcourier.order', 'driver'])
                    ->whereHas('detailshippingcourier.order', function($query) use ($userOutlet) {
                        $query->where('destinations_id', $userOutlet->location_id);
                     });


        }

        // courier
        else if(Auth::user()->role_id == '3'){
            $dataUser = User::where('id', Auth::user()->id)->first();
            $userOutlet = Outlet::where('id', $dataUser->outlets_id)->first();

            $q = ShippingCourier::with(['detailshippingcourier.order', 'driver'])
                ->where('driver_id', Auth::user()->id)
                ->whereHas('detailshippingcourier.order', function($query) use ($userOutlet) {
                    $query->where('destinations_id', $userOutlet->location_id);
                 });
        }


        return DataTables::of($q)
            ->editColumn('shippingno', function ($query) {
                return $query->shippingno;
            })
            ->editColumn('nama_kurir', function ($query) {
                return $query->driver->name;
            })
            ->editColumn('jml_paket', function ($query) {

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
                    <div class="dropdown-menu dropdown-menu-end">';
                        if (Auth::user()->role_id == '1' || Auth::user()->role_id == '2') {
                            $btn .= '<a class="dropdown-item"  href="' . url('/shipping-courier/' . $encryptId . '/edit') . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Edit</span>
                                    </a>';
                        }
                        if (Auth::user()->role_id == '3') {
                            $btn .= '<a class="dropdown-item"  href="' . url('/shipping-courier/' . $encryptId . '/show') . '">
                                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                        <span>Detail</span>
                                    </a>';
                        }
                    '</div>
                </div>';
                return $btn;
            })
            ->rawColumns(['shippingno', 'nama_kurir', 'jml_paket','status', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }


    // shipping-courier: courier get ajax order detail response
    public function getDetail(Request $request)
    {

        $dataUser = User::where('id', Auth::user()->id)->first();
        $userOutlet = Outlet::where('id', $dataUser->outlets_id)->first();

        $q = DetailShippingCourier::where('shipping_id', $request->id)->with('order');

        return DataTables::of($q)
            ->editColumn('numberorders', function ($query) {
                return $query->order->numberorders;
            })
            ->editColumn('penerima', function ($query) {
                return $query->order->penerima;
            })
            ->editColumn('address', function ($query) {
                return $query->order->address;
            })
            ->editColumn('status', function ($query) {
                if ($query->status_detail == '1') {
                    return '<span class="badge badge-light-primary">Process</span>';
                } else if($query->status_detail == '2'){
                    return '<span class="badge badge-light-primary">On The Way</span>';
                } else {
                    return '<span class="badge badge-light-success">Finish</span>';
                }
            })
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                if ($query->status_detail == '1') {
                    $btn .= '<a class="btn btn-primary btn-sm"  href="' . url('/shipping-courier/' . $encryptId . '/sendShipping') . '">
                                <svg fill="#ffffff"  width="14" height="14" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 495.003 495.003" xml:space="preserve" stroke="#ffffff"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g id="XMLID_51_"> <path id="XMLID_53_" d="M164.711,456.687c0,2.966,1.647,5.686,4.266,7.072c2.617,1.385,5.799,1.207,8.245-0.468l55.09-37.616 l-67.6-32.22V456.687z"></path> <path id="XMLID_52_" d="M492.431,32.443c-1.513-1.395-3.466-2.125-5.44-2.125c-1.19,0-2.377,0.264-3.5,0.816L7.905,264.422 c-4.861,2.389-7.937,7.353-7.904,12.783c0.033,5.423,3.161,10.353,8.057,12.689l125.342,59.724l250.62-205.99L164.455,364.414 l156.145,74.4c1.918,0.919,4.012,1.376,6.084,1.376c1.768,0,3.519-0.322,5.186-0.977c3.637-1.438,6.527-4.318,7.97-7.956 L494.436,41.257C495.66,38.188,494.862,34.679,492.431,32.443z"></path> </g> </g></svg>
                             </a>';
                }else if ($query->status_detail == '2') {
                    $btn .= '<a class="btn btn-success btn-sm"  href="' . url('/shipping-courier/' . $encryptId . '/done') . '">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" x="0px" y="0px" width="100" height="100" viewBox="0 0 30 30">
                                    <path fill="#ffffff" d="M15,3C8.373,3,3,8.373,3,15c0,6.627,5.373,12,12,12s12-5.373,12-12C27,8.373,21.627,3,15,3z M21.707,12.707l-7.56,7.56 c-0.188,0.188-0.442,0.293-0.707,0.293s-0.52-0.105-0.707-0.293l-3.453-3.453c-0.391-0.391-0.391-1.023,0-1.414s1.023-0.391,1.414,0 l2.746,2.746l6.853-6.853c0.391-0.391,1.023-0.391,1.414,0S22.098,12.316,21.707,12.707z"></path>
                                </svg>
                             </a>';
                }else if($query->status_detail == '3'){
                    $btn .= ' <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 50 50">
                                    <path fill="#28c76f" d="M 25 2 C 12.317 2 2 12.317 2 25 C 2 37.683 12.317 48 25 48 C 37.683 48 48 37.683 48 25 C 48 20.44 46.660281 16.189328 44.363281 12.611328 L 42.994141 14.228516 C 44.889141 17.382516 46 21.06 46 25 C 46 36.579 36.579 46 25 46 C 13.421 46 4 36.579 4 25 C 4 13.421 13.421 4 25 4 C 30.443 4 35.393906 6.0997656 39.128906 9.5097656 L 40.4375 7.9648438 C 36.3525 4.2598437 30.935 2 25 2 z M 43.236328 7.7539062 L 23.914062 30.554688 L 15.78125 22.96875 L 14.417969 24.431641 L 24.083984 33.447266 L 44.763672 9.046875 L 43.236328 7.7539062 z"></path>
                                </svg>';
                }
                return $btn;
            })
            ->rawColumns(['shippingno', 'nama_kurir', 'status', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }


    // shipping-courier: Admin / Superadmin / courier get order ajax response
    public function getOrder(){
        $outletsByUser = Outlet::where('id', Auth::user()->outlets_id)->first();

        if (Auth::user()->role_id == '1') {
            $q   = Order::where('status_orders', 2)
                    ->whereDoesntHave('detailshippingcourier');
        }else{
            $q   = Order::where('destinations_id', $outletsByUser->location_id)
                    ->where('status_orders', 2)
                    ->whereDoesntHave('detailshippingcourier')
                    ->whereHas('detailmanifests.manifest', function ($query){
                        $query->where('status_manifest', '3');
                    })->get();
        }

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


    // shipping-courier: Admin / Superadmin get detail order ajax response
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


    // shipping-courier: Admin / Superadmin get data courier ajax response
    public function getCourier(Request $request){
        $couriers = User::where('role_id', '3')->where('outlets_id', $request->outletasal)->get();
        return response()->json(['couriers'=>$couriers]);
    }


    // shipping-courier: courier get data order by outlet ajax response
    public function getOrdersByOutlet(Request $request){
        $outletsByUser = Outlet::where('id', $request->outletasal)->first();


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


    // shipping-courier: Admin / Superadmin create shipping
    public function create() {
        $couriers = User::where('role_id', '3')->where('outlets_id', Auth::user()->outlets_id)->get();
        $outlets  = Outlet::all();



        return view('pages.shippingcourier.create', compact('couriers', 'outlets', ));
    }


    // shipping-courier: Admin / Superadmin stored data shipping
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'shipping_no' => 'required|unique:shippingcouriers,shippingno',
            'courier'     => 'required',
            'order_ids'   => 'required',
            'note'        => 'required',
        ], [
            'shipping_no.required'     => 'Nomor pengiriman harus diisi',
            'shipping_no.unique'       => 'Nomor pengiriman sudah digunakan',
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



    // shipping-courier: Admin / Superadmin edit page
    public function edit($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }


       if (Auth::user()->role_id != '1') {
            $couriers = User::where('role_id', '3')->where('outlets_id', Auth::user()->outlets_id)->get();
            $outlets  = Outlet::all();
            $outletsByUser = Outlet::where('id', Auth::user()->outlets_id)->first();

            $shippingCourier = ShippingCourier::with('detailshippingcourier')->where('id', $id)->first();
            $orders = Order::where('destinations_id', $outletsByUser->location_id)
                            ->where('status_orders', 2)
                            ->whereHas('detailmanifests.manifest', function ($query){
                                $query->where('status_manifest', '3');
                            })->get();
       }else if(Auth::user()->role_id == '1'){
            $couriers = [];
            $outlets  = Outlet::all();
            $shippingCourier = ShippingCourier::with('detailshippingcourier')->where('id', $id)->first();

            $orders = [];
       }

        $showAddPaketButton = true;
        foreach ($shippingCourier->detailshippingcourier as $detail) {
            if ($detail->status_detail == 2 || $detail->status_detail == 3) {
                $showAddPaketButton = false;
                break;
            }
        }




        return view('pages.shippingcourier.edit', compact('couriers', 'outlets', 'orders', 'shippingCourier', 'showAddPaketButton'));
    }



    // shipping-courier: Admin / Superadmin update process
    public function update(Request $request, $id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'shipping_no' => 'required|unique:shippingcouriers,shippingno,'.$id,
            'courier'     => 'required',
            'order_ids'   => 'required',
            'note'        => 'required',
        ],[
            'shipping_no.required'     => 'Nomor pengiriman harus diisi',
            'shipping_no.unique'       => 'Nomor pengiriman sudah digunakan',
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



    // shipping-courier: courier get detail page
    public function show($id){
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $shippingCourier = ShippingCourier::find($id);


        return view('pages.shippingcourier.detail', compact('shippingCourier'));
    }

    // shipping-courier: Courier updates status to on the way
    public function sendShipping($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $detailShippingCourier = DetailShippingCourier::find($id);
        $detailShippingCourier->update([
            'status_detail' => '2'
        ]);

        $order = Order::find($detailShippingCourier->orders_id);;
        $order->update([
            'status_awb' => "Pesanan sedang di antar oleh kurir ke alamat tujuan"
        ]);

        HistoryAwb::create([
            'order_id' => $order->id,
            'awb'      => $order->numberorders,
            'status'   => "Pesanan sedang di antar oleh kurir ke alamat tujuan"
        ]);

        return back();
    }

    // shipping-courier: courier get done page
    public function done($id){
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $detailShippingCourier = DetailShippingCourier::find($id);
        return view('pages.shippingcourier.done', compact('detailShippingCourier'));
    }


    // shipping-courier: courier store shipping done
    public function storeShippingDone(Request $request, $id){
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'penerima'       => 'required',
            'bukti_diterima' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'note'           => 'required',
        ],[
            'penerima.required'               => 'Nomor pengiriman harus diisi.',
            'couribukti_diterimaer.required'  => 'Pilih kurir',
            'note.required'                   => 'Catatan harus diisi',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            $errorMessage = implode(', ', $error);
            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }


        $detailShippingCourier = DetailShippingCourier::find($id);
        $detailShippingCourier->update(
            [
                'notes'         => $request->note,
                'status_detail' => '3'
            ]
        );

        $dataOrder = [
            'penerima'      => $request->penerima,
            'photos'        => $request->file('bukti_diterima')->store('images'),
            'status_orders' => '3',
            'status_awb'    => 'Pesanan telah diterima oleh pihak terkait'
        ];

        $order = Order::find($detailShippingCourier->orders_id);
        $order->update($dataOrder);

        $dataHistoryAwb = [
            'order_id' => $order->id,
            'awb'      => $order->numberorders,
            'status'   => 'Pesanan telah diterima oleh pihak terkait'
        ];

        HistoryAwb::create($dataHistoryAwb);

        // jika semua list detail shipping selesai maka ubah status shipping jadi success
        $allDetailsDone = DetailShippingCourier::where('shipping_id', $detailShippingCourier->shipping_id)->get();
        $allDetailsDoneStatus = $allDetailsDone->every(function ($detail) {
            return $detail->status_detail == 3;
        });

        if ($allDetailsDoneStatus) {
            $shippingCourier = ShippingCourier::where('id', $detailShippingCourier->shipping_id)->update([
                'status_shippingcourier' => '2'
            ]);
        }

        Alert::success('Berhasil', 'Pengiriman berhasil');
        return redirect('shipping-courier/'.Crypt::encrypt($detailShippingCourier->shipping_id).'/show');
    }
}
