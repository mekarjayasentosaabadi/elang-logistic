<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Masterprice;
use Illuminate\Http\Request;
use App\Models\CustomerPrice;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseFormatter;
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
                        <a class="dropdown-item"  href="' . url('/customer/' . $encryptId) . '" data-confirm-delete="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            <span>Hapus</span>
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
        $outlet = Outlet::where('is_active', '1')->get();
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
                // 'photos'        => 'required|mimes:jpg,png',
            ]);
            $dataStored = [
                'name'          => $request->name,
                'phone'         => $request->phone,
                'address'       => $request->address,
                'email'         => $request->email,
                'photos'        => 'default.jpg',
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
            if($request->file('photos')){
                $request->validate([
                    'photos'    => 'required|mimes:png,jpg|max:1024'
                ]);
                $files          = $request->file('photos');
                $fileName       = time().'.'.$files->getClientOriginalExtension();
                $files->storeAs('public/customer', $fileName);
                $dataStored['photos']=$fileName;
            }
            $customer = User::create($dataStored);
            $dataCustomer = User::where('id', $customer->id)->firstOrFail();
            return ResponseFormatter::success([$dataCustomer], 'Data customer berhasil di simpan.');
        } catch (Exception $error) {
            return ResponseFormatter::error([], 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = User::where('id', Crypt::decrypt($id))->firstOrFail();

        // $customer_prices_outlet = $customer->customer_prices->load('destination')->groupBy('outlet_id');


        // $customer_prices = [];
        // foreach ($customer_prices_outlet as $key => $value) {
        //     $cs = [];
        //     $cs['outlet'] = $value[0]->outlet->name;

        //     $cs['prices'] = $value->groupBy('armada');
        //     $customer_prices[] = $cs;
        // }

        return view('pages.customer.show2', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer   = User::where('id', Crypt::decrypt($id))->where('role_id', '4')->firstOrFail();
        $outlet     = Outlet::where('is_active', '1')->get();
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
                $request->validate([
                    'photos'        => 'required|mimes:jpg,png',
                ]);
                $files          = $request->file('photos');
                $fileName       = time().'.'.$files->getClientOriginalExtension();
                $files->storeAs('public/customer', $fileName);
                $dataStored['photos']=$fileName;
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
            } else {
                $update=[
                    'is_active' => 1
                ];
            }
            $status->update($update);
            return ResponseFormatter::success([$status], 'Success Memperbaharui data');
        } catch (Exception $error) {
            return ResponseFormatter::error([$error], 'Gagal Memperbaharui data');
        }
    }

    function getcustomerprice($id){
        $customerprice = CustomerPrice::with('destination')->where('customer_id', Crypt::decrypt($id))->get();
        return ResponseFormatter::success([
            'customerprice'     => $customerprice
        ], 'get customer price successfuly');
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
                'destination_id'    => $value->destinations_id,
                'price'             => $value->price,
                'minweights'        => $value->minweights,
                'nextweightprices'  => $value->nextweightprices,
                'minimumprice'     => $value->minimumprice,
                'masterprices_id'   => $value->id,
                'estimation'        => $value->estimation
            ];
            $customerprice[]=$dataCustomer;
        }
        CustomerPrice::insert($customerprice);
        return ResponseFormatter::success([], 'Generate Customer Prices Successfuly');
    }
}
