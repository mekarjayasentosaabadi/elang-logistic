<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Destination;
use App\Models\Masterprice;
use Illuminate\Http\Request;
use App\Models\CustomerPrice;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('pages.customer.index');
    }

    public function getAll()
    {
        $q = auth()->user()->role_id == '1' ? User::where('role_id', '4')->get() : User::where('role_id', '4')->where('outlets_id', auth()->user()->outlets_id)->get();

        return DataTables::of($q)
            ->addColumn('aksi', function ($query) {
                $encryptId = Crypt::encrypt($query->id);
                $btn = '';
                $btn .= '<div class="dropdown">
                    <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0 waves-effect waves-float waves-light" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item"  href="' . url('/customer/' . $encryptId) . '">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>Detail</span>
                        </a>
                        <a class="dropdown-item"  href="' . url('/customer/' . $encryptId . '/edit') . '">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                <span>Edit</span>
                        </a>
                    </div>
                </div>';
                return $btn;
            })
            ->addColumn('toogle', function($x){
                $checked = $x->is_active == 1 ? 'checked' : '';
                $toogle = '';
                $toogle .= '<div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" '.$checked.' id="paymentTerms" onclick="changeStatus(this, '.$x->id.')" />
                                <label class="form-check-label" for="paymentTerms"></label>
                            </div>';
                return $toogle;
            })
            ->rawColumns(['is_active', 'role_id', 'aksi', 'toogle'])
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlet = Outlet::where('is_active', '1')->where('type', '!=', 2)->get();
        return view('pages.customer.create', compact('outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = auth()->user()->role_id;
            $code_otomatis = $request->input('kodecustomer');
            $request->validate([
                'name'          => 'required',
                'phone'         => 'required|unique:users,phone',
                'address'       => 'required',
                'email'         => 'required|unique:users,email',
            ],[
                'email.unique'  => 'Email ini sudah terdaftar, silahkan gunakan email lain.',
                'phone.unique'  => 'Nomor ini sudah terdaftar, silahkan gunakan nomor lain.',
            ]);
            $dataStored = [
                'name'          => $request->name,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'email'         => $request->email,
                'picures'        => 'img_default.jpg',
                'role_id'       => '4',
                'password'      => Hash::make('elang123'),
                'code_customer' => $request->code_customer,
                'is_otomatis'   => '0'
            ];
            if($auth == '1'){
                $request->validate([
                    'outlets'   => 'required'
                ]);
                $dataStored['outlets_id']   = $request->outlets;
            } else {
                $dataStored['outlets_id']   = auth()->user()->outlets_id;
            }
            if($code_otomatis == "1"){
                $latestCustomer = User::where('role_id', '4')->where('is_otomatis', '1')->latest()->first();
                $latesCode = $latestCustomer ? intval(substr($latestCustomer->code_customer, 2)): 0;
                $kode_customer = 'C-'. str_pad($latesCode + 1, 6, '0', STR_PAD_LEFT);
                $dataStored['code_customer'] = $kode_customer;
                $dataStored['is_otomatis']  = '1';
            }
            if($request->hasFile('photos')){
                $files          = $request->file('photos');
                $fileName       = time().'.'.$files->getClientOriginalExtension();
                $files->storeAs('public/customer', $fileName);
                $dataStored['picures']=$fileName;
            }
            $customer = User::create($dataStored);
            $dataCustomer = User::where('id', $customer->id)->firstOrFail();
            return ResponseFormatter::success([$dataCustomer], 'Data customer berhasil di simpan.');
        } catch (Exception $error) {
            return ResponseFormatter::error([$error], 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer       = User::where('id', Crypt::decrypt($id))->first();
        $outlet         = Outlet::where('is_active', '1')->get();
        $destination    = Destination::all();
        // $customer_prices_outlet = $customer->customer_prices->load('destination')->groupBy('outlet_id');


        // $customer_prices = [];
        // foreach ($customer_prices_outlet as $key => $value) {
        //     $cs = [];
        //     $cs['outlet'] = $value[0]->outlet->name;

        //     $cs['prices'] = $value->groupBy('armada');
        //     $customer_prices[] = $cs;
        // }

        return view('pages.customer.show2', compact('customer', 'outlet', 'destination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer   = User::where('id', Crypt::decrypt($id))->where('role_id', '4')->firstOrFail();
        $outlet     = Outlet::where('is_active', '1')->where('type', '!=', 2)->get();
        return view('pages.customer.update', compact('customer', 'outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $auth = auth()->user()->role_id;
            $request->validate([
                'name'          => 'required',
                'phone'         => 'required|unique:users,phone,'.Crypt::decrypt($id).',id',
                'address'       => 'required',
                'email'         => 'required|unique:users,email,'.Crypt::decrypt($id).',id'
                // 'photos'        => 'required|mimes:jpg,png',
            ],[
                'email.unique'  => 'Email ini sudah terdaftar, silahkan gunakan email lain.',
                'phone.unique'  => 'Nomor ini sudah terdaftar, silahkan gunakan nomor lain.',
            ]);
            $dataStored = [
                'name'          => $request->name,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'email'         => $request->email,
                // 'picures'        => 'default.jpg',
            ];
            if($auth == '1'){
                $request->validate([
                    'outlets'   => 'required'
                ]);
                $dataStored['outlets_id']   = $request->outlets;
            } else {
                $dataStored['outlets_id']   = auth()->user()->outlets_id;
            }
            if($request->file('photos')){
                // $request->validate([
                //     'photos'        => 'required|mimes:jpg,png',
                // ]);
                $files          = $request->file('photos');
                $fileName       = time().'.'.$files->getClientOriginalExtension();
                $files->storeAs('public/customer', $fileName);
                $dataStored['picures']=$fileName;
            }
            User::where('id', Crypt::decrypt($id))->update($dataStored);
            // Alert::success('Success', 'Data berhasil di perbaharui.');
            return ResponseFormatter::success([], 'Data customer berhasil di perbaharui.');
        } catch (Exception $error) {
            return ResponseFormatter::error([], 'Something went wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }

    function changeStatus(Request $request, $id){
        try {
            $status = User::find($id);
            if($status->is_active == 1){
                $update=[
                    'is_active' => 0
                ];
                $status->update($update);
                return ResponseFormatter::success([$status], 'Berhasil menonaktifkan Customer');
            } else {
                $update=[
                    'is_active' => 1
                ];
                $status->update($update);
                return ResponseFormatter::success([$status], 'Berhasil mengaktifkan Customer');
            }
        } catch (Exception $error) {
            return ResponseFormatter::error([$error], 'Gagal Memperbaharui data');
        }   
    }

    function getcustomerprice($id){
        $customerprice = CustomerPrice::with(['destination', 'origin'])->where('customer_id', Crypt::decrypt($id))->get();
        return DataTables::of($customerprice)
                ->addColumn('service', function($x){
                    if($x->armada == 1){
                        return "Darat";
                    } else if($x->armada == 2){
                        return "Laut";
                    } else {
                        return "Udara";
                    }
                })
                ->addColumn('action', function($x){
                    // $btn = "<button>";
                    $destination = ''.$x->destination->name.'';
                    // return ''.$destination.'';
                    $btn = '<button data-bs-toggle="modal" data-bs-target="#exampleModalCenter" class="btn btn-primary btn-sm" onclick="changePrice('.$x->id.')"><i class="fa fa-edit"></i></button>';
                    return $btn;
                })
                ->rawColumns(['service', 'action'])
                ->addIndexColumn()
                ->make(true);
    }

    function generatecustomerprice($id){
        $customer = User::where('id', Crypt::decrypt($id))->where('role_id', '4')->firstOrFail();
        $masterprice = Masterprice::where('outlets_id', $customer->outlets_id)->get();
        $customerprice = [];
        foreach ($masterprice as $key => $value) {
            $dataCustomer = [
                'customer_id'       => $customer->id,
                'outlet_id'         => $value->outlets_id,
                'armada'            => $value->armada,
                'origin_id'         => $value->origin_id,
                'destination_id'    => $value->destinations_id,
                'price'             => $value->price,
                'minweights'        => $value->minweights,
                'nextweightprices'  => $value->nextweightprices,
                'minimumprice'     => $value->minimumprice,
                'masterprices_id'   => $value->id,
                'estimation'        => $value->estimation,
                'created_at'        => Carbon::now()
            ];
            $customerprice[]=$dataCustomer;
        }
        CustomerPrice::insert($customerprice);
        return ResponseFormatter::success([], 'Generate Customer Prices Successfuly');
    }

    function changeprice(Request $request, $id){
        $dataUpdate = [
            'price'     => $request->price,
        ];
        if(Auth::user()->role_id == '1'){
            $dataUpdate = [
                'price'     => $request->price ?? 0,
                'minweights'=> $request->minweight ?? 0,
                'nextweightprices'=> $request->pricenext ?? 0,
                'minimumprice'  => $request->minimumprice ?? 0,
                'estimation'  => $request->estimation ?? 0,
            ];
        }

        CustomerPrice::where('id', $id)->update($dataUpdate);
        return ResponseFormatter::success([], 'Berhasil memperbaharui Data Harga');
    }

    function addmanualprice(Request $request, $id){
        $customer = User::with('outlet')->where('id', Crypt::decrypt($id))->firstOrFail();
        $search = [
            'outlets_id'    => $request->outlet,
            'armada'        => $request->armada,
            'destinations_id'=> $request->destination,
            'origin_id'     => $request->origin
        ];
        $filter = Masterprice::where($search)->first();
        if($filter){
            return ResponseFormatter::success(['validate'=>false], 'Data Masterprice tersebut sudah ada.!, Mohon periksa kembali');
        }
        $dataStoredMasterPrice = [
            'outlets_id'        => $request->outlet,
            'armada'            => $request->armada,
            'origin_id'         => $request->origin,
            'destinations_id'   => $request->destination,
            'price'             => $request->price,
            'minweights'         => $request->minweight,
            'nextweightprices'  => $request->pricenext,
            'minimumprice'      => $request->minimumprice,
            'estimation'        => $request->estimation
        ];
        $masterprice = Masterprice::create($dataStoredMasterPrice);
        $dataStoredCustomerPrice = [
            'customer_id'       => $customer->id,
            'outlet_id'         => $request->outlet,
            'armada'            => $request->armada,
            'origin_id'         => $request->origin,
            'destination_id'    => $request->destination,
            'price'             => $request->price,
            'minweights'        => $request->minweight,
            'nextweightprices'  => $request->pricenext,
            'minimumprice'      => $request->minimumprice,
            'masterprices_id'   => $masterprice->id,
            'estimation'        => $request->estimation
        ];
        CustomerPrice::create($dataStoredCustomerPrice);
        return ResponseFormatter::success(['validate'=>true], 'Berhasil menambahkan data hargamanual price.!');
    }

    function getDetailPrice($id){
        $customerprice = CustomerPrice::with(['destination', 'origin'])->where('id', $id)->first();
        return ResponseFormatter::success([$customerprice], 'Get data successfuly');
    }
}
