<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Destination;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;

class OrderController extends Controller
{
    function index()
    {
        return view('pages.order.index');
    }

    public function getAll()
    {

        $q = Order::with('customer', 'histories');


        return DataTables::of($q)
            ->addColumn('numberorders', function ($query) {
                return $query->numberorders;
            })
            ->addColumn('customer', function ($query) {
                return $query->customer->name;
            })
            ->editColumn('status_orders', function ($query) {
                $html = status_html($query->status_orders);
                $html .= '<small class="text-sm"><br/><i class="fas fa-truck"></i>' . $query->status_orders . '</small>';
                return  $html;
            })
            ->editColumn('created_at', function ($query) {
                return $query->created_at->format('d-m-Y H:i');
            })
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                $btn .= '<div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"  href="' . url('/order/' . $encryptId .'/detail') . '">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>Detail</span>
                        </a>
                        ';
                if ($query->status_orders == 1) {

                    $btn .= '<a class="dropdown-item"  href="' . url('/order/' . $encryptId . '/edit') . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                        <span>Edit</span>
                                    </a>
                                    <a class="dropdown-item"  href="' . url('/order/' . $encryptId) . '" data-confirm-delete="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M16 6l-1 14-10-1"></path></svg>
                                        <span>Batalkan</span>
                                    </a>';
                }

                $btn .= '</div>
                </div>';
                return $btn;
            })
            ->rawColumns(['numberorders', 'customer',  'created_at','status_orders', 'aksi', 'created_at'])
            ->addIndexColumn()
            ->make(true);
    }

    function create()
    {
        $customers = User::where('role_id', 4)->get();
        $destinations = Destination::all();
        return view('pages.order.create', compact('customers', 'destinations'));
    }

    function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->has('pesanan_masal')) {
                $validator =Validator::make($request->all(), [
                    'customer_id'       => 'required',
                    'destination1_id'   => 'required',
                ], [
                    'customer_id.required'     => 'Pilih Salah Satu Customer',
                    'destination1_id.required' => 'Pilih Salah Satu Destinasi'
                ]);

                if ($validator->fails()) {
                    $errors       = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }

                // create order sebanyak total
                for ($i = 0; $i < $request->total; $i++) {
                    // $order->awb = generateAwb();

                    $order = new Order();
                    $order->numberorders    = generateOrderCode();
                    $order->customer_id     = $request->customer_id;
                    $order->destinations_id = ($request->destination_id != null? $request->destination_id : $request->destination1_id);
                    $order->status_orders   = 1;
                    $order->outlet_id       = Auth::user()->id;
                    $order->save();

                    // create history awb -> "Pesanan sedang diambl kurir"
                    // $order->histories()->create([
                    //     'order_id' => $order->id,
                    //     'awb' => $order->awb,
                    //     'status' => 'Pesanan sedang diambil kurir',
                    // ]);
                }
                DB::commit();
                Alert::success('Berhasil', 'Pesanan Berhasil Dibuat');
                return redirect()->to('/order');
            } else {
                $validator =Validator::make($request->all(), [
                    'customer_id'       =>  'required',
                    'destination_id'    =>  'required',
                    'receiver'          =>  'required',
                    'armada'            =>  'required',
                    'address'           =>  'required',
                    'weight'            =>  'required',
                    'volume'            =>  'required',
                    'estimation'        =>  'required',
                    'description'       =>  'required',
                    'note'              =>  'required',
                ], [
                    'customer_id.required'    => 'Pilih Salah Satu Customer',
                    'destination_id.required' => 'Pilih Salah Satu Destinasi',
                    'receiver.required'       => 'Penerima Harus Diisi',
                    'armada.required'         => 'Pilih Salah Satu Armada',
                    'service.required'        => 'Pilih Salah Satu Jenis',
                    'address.required'        => 'Penerima Harus Diisi',
                    'weight.required'         => 'Berat Harus Diisi',
                    'volume.required'         => 'Volume Harus Diisi',
                    'estimation.required'     => 'Estimasi Harus Diisi',
                    'description.required'    => 'Deskripsi Harus Diisi',
                    'note.required'           => 'Catatan Harus Diisi',
                ]);

                if ($validator->fails()) {
                    $errors       = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }

                if ($validator->fails()) {
                    $errors       = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }

                // $order->awb = generateAwb();
                $order = new Order();
                $order->numberorders    = generateOrderCode();
                $order->customer_id     =  $request->customer_id;
                $order->status_orders   =  2;
                $order->penerima        =  $request->receiver;
                $order->armada          =  $request->armada;
                $order->service         =  $request->service;
                $order->destinations_id  =  $request->destination_id;
                $order->address         =  $request->address;
                $order->weight          =  $request->weight;
                $order->volume          =  $request->volume;
                $order->price           =  $request->price;
                $order->estimation      =  $request->estimation;
                $order->description     =  $request->description;
                $order->note            =  $request->note;
                $order->save();

                // create history awb -> "Pesanan sedang di proses di gudang Bekasi"
                // $order->histories()->create([
                //     'order_id' => $order->id,
                //     'awb' => $order->awb,
                //     'status' => 'Pesanan sedang di proses di gudang Bekasi',
                // ]);
                DB::commit();
                Alert::success('Berhasil', 'Pesanan Berhasil Dibuat');
                return redirect()->to('/order');
            }
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $order = Order::find($id);
        return view('pages.order.detail', compact('order'));
    }

    function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $order = Order::find($id);
        $customers = User::where('role_id', 4)->get();
        $destinations = Destination::all();
        return view('pages.order.edit', compact('order', 'customers', 'destinations'));
    }

    function update(Request $request, $id)
    {

        $validator =Validator::make($request->all(), [
            'customer_id'       =>  'required',
            'destination_id'    =>  'required',
            'receiver'          =>  'required',
            'armada'            =>  'required',
            'address'           =>  'required',
            'weight'            =>  'required',
            'volume'            =>  'required',
            'estimation'        =>  'required',
            'description'       =>  'required',
            'note'              =>  'required',
        ], [
            'customer_id.required'    => 'Pilih Salah Satu Customer',
            'destination_id.required' => 'Pilih Salah Satu Destinasi',
            'receiver.required'       => 'Penerima Harus Diisi',
            'armada.required'         => 'Pilih Salah Satu Armada',
            'service.required'        => 'Pilih Salah Satu Jenis',
            'address.required'        => 'Penerima Harus Diisi',
            'weight.required'         => 'Berat Harus Diisi',
            'volume.required'         => 'Volume Harus Diisi',
            'estimation.required'     => 'Estimasi Harus Diisi',
            'description.required'    => 'Deskripsi Harus Diisi',
            'note.required'           => 'Catatan Harus Diisi',
        ]);

        if ($validator->fails()) {
            $errors       = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);

            Alert::error('Gagal', $errorMessage);
            return redirect()->back()->withInput();
        }

        $order = Order::find(Crypt::decrypt($id));
        $order->customer_id     = $request->customer_id;
        $order->status_orders   = 2;
        $order->penerima        = $request->receiver;
        $order->armada          = $request->armada;
        $order->service         = $request->service;
        $order->destinations_id = $request->destination_id;
        $order->address         = $request->address;
        $order->weight          = $request->weight;
        $order->volume          = $request->volume;
        $order->price           = $request->price;
        $order->payment_method  = $request->payment_method;
        $order->estimation      = $request->estimation;
        $order->description     = $request->description;
        $order->note            = $request->note;
        $order->save();

        // create history awb -> "Pesanan sedang di proses di gudang Bekasi"
        // $order->histories()->create([
        //     'order_id' => $order->id,
        //     'awb' => $order->awb,
        //     'status' => 'Pesanan sedang di proses di gudang Bekasi',
        // ]);
        DB::commit();
        Alert::success('Berhasil', 'Pesanan Berhasil Diupdate');
        return redirect()->to('/order');
    }

}
