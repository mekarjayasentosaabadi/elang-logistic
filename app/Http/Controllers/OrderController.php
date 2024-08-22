<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Order;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\CustomerPrice;
use App\Models\HistoryAwb;
use App\Models\Destination;
use App\Models\HistoryUpdateOrder;
use App\Models\Masterprice;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;
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
        confirmDelete('Batalkan Transaksi', 'Apakah Anda Yakin Ingin Membatalkan Transaksi Ini?');
        return view('pages.order.index');
    }




    function getAll()
    {
        if (Auth::user()->role_id == '1') {
            $q = Order::with('customer', 'histories');
        } else {
            $q = Order::where('outlet_id', Auth::user()->outlets_id)->with('customer', 'histories')->get();
        }


        return DataTables::of($q)
            ->addColumn('numberorders', function ($query) {
                return $query->numberorders;
            })
            ->addColumn('pengirim', function ($query) {
                return $query->customer->name;
            })
            ->addColumn('penerima', function ($query) {
                return $query->penerima;
            })
            ->editColumn('status_orders', function ($query) {
                $html = status_html($query->status_orders);
                $html .= '<small class="text-sm"><br/><i class="fas fa-truck"></i> ' . $query->status_awb . '</small>';
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
                        <a class="dropdown-item"  href="' . url('/order/' . $encryptId . '/detail') . '">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>Detail</span>
                        </a>';
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
                if ($query->status_orders == 2) {
                    $btn .= '<a class="dropdown-item"  href="' . url('/order/' . $encryptId . '/edit') . '">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                <span>Edit</span>
                            </a>';
                }
                if ($query->status_orders == 2 || $query->status_orders == 3) {
                    $btn .= '<a class="dropdown-item"  href="' . url('/order/' . $encryptId . '/print') . '" target="_blank">
                            <?xml version="1.0" encoding="utf-8"?><svg width="14" height="14"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 114.13" style="enable-background:new 0 0 122.88 114.13" xml:space="preserve"><g><path d="M23.2,29.44V3.35V0.53C23.2,0.24,23.44,0,23.73,0h2.82h54.99c0.09,0,0.17,0.02,0.24,0.06l1.93,0.8l-0.2,0.49l0.2-0.49 c0.08,0.03,0.14,0.08,0.2,0.14l12.93,12.76l0.84,0.83l-0.37,0.38l0.37-0.38c0.1,0.1,0.16,0.24,0.16,0.38v1.18v13.31 c0,0.29-0.24,0.53-0.53,0.53h-5.61c-0.29,0-0.53-0.24-0.53-0.53v-6.88H79.12H76.3c-0.29,0-0.53-0.24-0.53-0.53 c0-0.02,0-0.03,0-0.05v-2.77h0V6.69H29.89v22.75c0,0.29-0.24,0.53-0.53,0.53h-5.64C23.44,29.97,23.2,29.73,23.2,29.44L23.2,29.44z M30.96,67.85h60.97h0c0.04,0,0.08,0,0.12,0.01c0.83,0.02,1.63,0.19,2.36,0.49c0.79,0.33,1.51,0.81,2.11,1.41 c0.59,0.59,1.07,1.31,1.4,2.1c0.3,0.73,0.47,1.52,0.49,2.35c0.01,0.04,0.01,0.08,0.01,0.12v0v9.24h13.16h0c0.04,0,0.07,0,0.11,0.01 c0.57-0.01,1.13-0.14,1.64-0.35c0.57-0.24,1.08-0.59,1.51-1.02c0.43-0.43,0.78-0.94,1.02-1.51c0.21-0.51,0.34-1.07,0.35-1.65 c-0.01-0.03-0.01-0.07-0.01-0.1v0V43.55v0c0-0.04,0-0.07,0.01-0.11c-0.01-0.57-0.14-1.13-0.35-1.64c-0.24-0.56-0.59-1.08-1.02-1.51 c-0.43-0.43-0.94-0.78-1.51-1.02c-0.51-0.22-1.07-0.34-1.65-0.35c-0.03,0.01-0.07,0.01-0.1,0.01h0H11.31h0 c-0.04,0-0.08,0-0.11-0.01c-0.57,0.01-1.13,0.14-1.64,0.35C9,39.51,8.48,39.86,8.05,40.29c-0.43,0.43-0.78,0.94-1.02,1.51 c-0.21,0.51-0.34,1.07-0.35,1.65c0.01,0.03,0.01,0.07,0.01,0.1v0v35.41v0c0,0.04,0,0.08-0.01,0.11c0.01,0.57,0.14,1.13,0.35,1.64 c0.24,0.57,0.59,1.08,1.02,1.51C8.48,82.65,9,83,9.56,83.24c0.51,0.22,1.07,0.34,1.65,0.35c0.03-0.01,0.07-0.01,0.1-0.01h0h13.16 v-9.24v0c0-0.04,0-0.08,0.01-0.12c0.02-0.83,0.19-1.63,0.49-2.35c0.31-0.76,0.77-1.45,1.33-2.03c0.02-0.03,0.04-0.06,0.07-0.08 c0.59-0.59,1.31-1.07,2.1-1.4c0.73-0.3,1.52-0.47,2.36-0.49C30.87,67.85,30.91,67.85,30.96,67.85L30.96,67.85L30.96,67.85z M98.41,90.27v17.37v0c0,0.04,0,0.08-0.01,0.12c-0.02,0.83-0.19,1.63-0.49,2.36c-0.33,0.79-0.81,1.51-1.41,2.11 c-0.59,0.59-1.31,1.07-2.1,1.4c-0.73,0.3-1.52,0.47-2.35,0.49c-0.04,0.01-0.08,0.01-0.12,0.01h0H30.96h0 c-0.04,0-0.08-0.01-0.12-0.01c-0.83-0.02-1.62-0.19-2.35-0.49c-0.79-0.33-1.5-0.81-2.1-1.4c-0.6-0.6-1.08-1.31-1.41-2.11 c-0.3-0.73-0.47-1.52-0.49-2.35c-0.01-0.04-0.01-0.08-0.01-0.12v0V90.27H11.31h0c-0.04,0-0.08,0-0.12-0.01 c-1.49-0.02-2.91-0.32-4.2-0.85c-1.39-0.57-2.63-1.41-3.67-2.45c-1.04-1.04-1.88-2.28-2.45-3.67c-0.54-1.3-0.84-2.71-0.85-4.2 C0,79.04,0,79,0,78.96v0V43.55v0c0-0.04,0-0.08,0.01-0.12c0.02-1.49,0.32-2.9,0.85-4.2c0.57-1.39,1.41-2.63,2.45-3.67 c1.04-1.04,2.28-1.88,3.67-2.45c1.3-0.54,2.71-0.84,4.2-0.85c0.04-0.01,0.08-0.01,0.12-0.01h0h100.25h0c0.04,0,0.08,0,0.12,0.01 c1.49,0.02,2.91,0.32,4.2,0.85c1.39,0.57,2.63,1.41,3.67,2.45c1.04,1.04,1.88,2.28,2.45,3.67c0.54,1.3,0.84,2.71,0.85,4.2 c0.01,0.04,0.01,0.08,0.01,0.12v0v35.41v0c0,0.04,0,0.08-0.01,0.12c-0.02,1.49-0.32,2.9-0.85,4.2c-0.57,1.39-1.41,2.63-2.45,3.67 c-1.04,1.04-2.28,1.88-3.67,2.45c-1.3,0.54-2.71,0.84-4.2,0.85c-0.04,0.01-0.08,0.01-0.12,0.01h0H98.41L98.41,90.27z M89.47,15.86 l-7-6.91v6.91H89.47L89.47,15.86z M91.72,74.54H31.16v32.89h60.56V74.54L91.72,74.54z"/></g></svg>
                            <span>Cetak Resi Fo1</span>
                        </a>';
                    $btn .= '<a class="dropdown-item"  href="' . url('/order/' . $encryptId . '/print-v2') . '" target="_blank">
                            <?xml version="1.0" encoding="utf-8"?><svg width="14" height="14"  version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.88 114.13" style="enable-background:new 0 0 122.88 114.13" xml:space="preserve"><g><path d="M23.2,29.44V3.35V0.53C23.2,0.24,23.44,0,23.73,0h2.82h54.99c0.09,0,0.17,0.02,0.24,0.06l1.93,0.8l-0.2,0.49l0.2-0.49 c0.08,0.03,0.14,0.08,0.2,0.14l12.93,12.76l0.84,0.83l-0.37,0.38l0.37-0.38c0.1,0.1,0.16,0.24,0.16,0.38v1.18v13.31 c0,0.29-0.24,0.53-0.53,0.53h-5.61c-0.29,0-0.53-0.24-0.53-0.53v-6.88H79.12H76.3c-0.29,0-0.53-0.24-0.53-0.53 c0-0.02,0-0.03,0-0.05v-2.77h0V6.69H29.89v22.75c0,0.29-0.24,0.53-0.53,0.53h-5.64C23.44,29.97,23.2,29.73,23.2,29.44L23.2,29.44z M30.96,67.85h60.97h0c0.04,0,0.08,0,0.12,0.01c0.83,0.02,1.63,0.19,2.36,0.49c0.79,0.33,1.51,0.81,2.11,1.41 c0.59,0.59,1.07,1.31,1.4,2.1c0.3,0.73,0.47,1.52,0.49,2.35c0.01,0.04,0.01,0.08,0.01,0.12v0v9.24h13.16h0c0.04,0,0.07,0,0.11,0.01 c0.57-0.01,1.13-0.14,1.64-0.35c0.57-0.24,1.08-0.59,1.51-1.02c0.43-0.43,0.78-0.94,1.02-1.51c0.21-0.51,0.34-1.07,0.35-1.65 c-0.01-0.03-0.01-0.07-0.01-0.1v0V43.55v0c0-0.04,0-0.07,0.01-0.11c-0.01-0.57-0.14-1.13-0.35-1.64c-0.24-0.56-0.59-1.08-1.02-1.51 c-0.43-0.43-0.94-0.78-1.51-1.02c-0.51-0.22-1.07-0.34-1.65-0.35c-0.03,0.01-0.07,0.01-0.1,0.01h0H11.31h0 c-0.04,0-0.08,0-0.11-0.01c-0.57,0.01-1.13,0.14-1.64,0.35C9,39.51,8.48,39.86,8.05,40.29c-0.43,0.43-0.78,0.94-1.02,1.51 c-0.21,0.51-0.34,1.07-0.35,1.65c0.01,0.03,0.01,0.07,0.01,0.1v0v35.41v0c0,0.04,0,0.08-0.01,0.11c0.01,0.57,0.14,1.13,0.35,1.64 c0.24,0.57,0.59,1.08,1.02,1.51C8.48,82.65,9,83,9.56,83.24c0.51,0.22,1.07,0.34,1.65,0.35c0.03-0.01,0.07-0.01,0.1-0.01h0h13.16 v-9.24v0c0-0.04,0-0.08,0.01-0.12c0.02-0.83,0.19-1.63,0.49-2.35c0.31-0.76,0.77-1.45,1.33-2.03c0.02-0.03,0.04-0.06,0.07-0.08 c0.59-0.59,1.31-1.07,2.1-1.4c0.73-0.3,1.52-0.47,2.36-0.49C30.87,67.85,30.91,67.85,30.96,67.85L30.96,67.85L30.96,67.85z M98.41,90.27v17.37v0c0,0.04,0,0.08-0.01,0.12c-0.02,0.83-0.19,1.63-0.49,2.36c-0.33,0.79-0.81,1.51-1.41,2.11 c-0.59,0.59-1.31,1.07-2.1,1.4c-0.73,0.3-1.52,0.47-2.35,0.49c-0.04,0.01-0.08,0.01-0.12,0.01h0H30.96h0 c-0.04,0-0.08-0.01-0.12-0.01c-0.83-0.02-1.62-0.19-2.35-0.49c-0.79-0.33-1.5-0.81-2.1-1.4c-0.6-0.6-1.08-1.31-1.41-2.11 c-0.3-0.73-0.47-1.52-0.49-2.35c-0.01-0.04-0.01-0.08-0.01-0.12v0V90.27H11.31h0c-0.04,0-0.08,0-0.12-0.01 c-1.49-0.02-2.91-0.32-4.2-0.85c-1.39-0.57-2.63-1.41-3.67-2.45c-1.04-1.04-1.88-2.28-2.45-3.67c-0.54-1.3-0.84-2.71-0.85-4.2 C0,79.04,0,79,0,78.96v0V43.55v0c0-0.04,0-0.08,0.01-0.12c0.02-1.49,0.32-2.9,0.85-4.2c0.57-1.39,1.41-2.63,2.45-3.67 c1.04-1.04,2.28-1.88,3.67-2.45c1.3-0.54,2.71-0.84,4.2-0.85c0.04-0.01,0.08-0.01,0.12-0.01h0h100.25h0c0.04,0,0.08,0,0.12,0.01 c1.49,0.02,2.91,0.32,4.2,0.85c1.39,0.57,2.63,1.41,3.67,2.45c1.04,1.04,1.88,2.28,2.45,3.67c0.54,1.3,0.84,2.71,0.85,4.2 c0.01,0.04,0.01,0.08,0.01,0.12v0v35.41v0c0,0.04,0,0.08-0.01,0.12c-0.02,1.49-0.32,2.9-0.85,4.2c-0.57,1.39-1.41,2.63-2.45,3.67 c-1.04,1.04-2.28,1.88-3.67,2.45c-1.3,0.54-2.71,0.84-4.2,0.85c-0.04,0.01-0.08,0.01-0.12,0.01h0H98.41L98.41,90.27z M89.47,15.86 l-7-6.91v6.91H89.47L89.47,15.86z M91.72,74.54H31.16v32.89h60.56V74.54L91.72,74.54z"/></g></svg>
                            <span>Cetak Resi Fo2</span>
                        </a>';
                }
                $btn .= '</div></div>';
                return $btn;
            })->rawColumns(['numberorders', 'pengirim', 'penerima', 'created_at', 'status_orders', 'aksi', 'created_at'])
            ->addIndexColumn()
            ->make(true);
    }



    function getHistoryUpdateOrder(Request $request)
    {

        $q = HistoryUpdateOrder::with('order')->where('order_id', $request->order_id);



        return DataTables::of($q)
            ->addColumn('numberorders', function ($query) {
                return $query->numberorders;
            })
            ->addColumn('pengirim', function ($query) {
                return $query->order->customer->name;
            })
            ->addColumn('penerima', function ($query) {
                return $query->penerima;
            })
            ->editColumn('status_orders', function ($query) {
                $html = status_html($query->status_orders);
                $html .= '<small class="text-sm"><br/><i class="fas fa-truck"></i> ' . $query->status_awb . '</small>';
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
                        <a class="dropdown-item"  href="' . url('/order/' . $encryptId . '/detailhistoryupdate') . '">
                            <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                            <span>Detail</span>
                        </a>';

                $btn .= '</div>';
                return $btn;
            })->rawColumns(['numberorders', 'pengirim', 'penerima', 'created_at', 'status_orders', 'aksi', 'created_at'])
            ->addIndexColumn()
            ->make(true);
    }


    function getEstimation(Request $request)
    {

        if ($request->outletasal) {
            $estimation = CustomerPrice::where('armada', $request->armada)->where('destination_id', $request->destination_id)->where('outlet_id', $request->outletasal)->where('customer_id', $request->customer_id)->first();
            if ($estimation == null) {
                $estimation = Masterprice::where('armada', $request->armada)->where('destinations_id', $request->destination_id)->where('outlets_id', $request->outletasal)->first();
            }
        } else {
            if ($request->customer_id) {
                $estimation = CustomerPrice::where('armada', $request->armada)->where('destination_id', $request->destination_id)->where('outlet_id', Auth::user()->outlets_id)->where('customer_id', $request->customer_id)->first();
                if ($estimation == null) {
                    $estimation = Masterprice::where('armada', $request->armada)->where('destinations_id', $request->destination_id)->where('outlets_id', Auth::user()->outlets_id)->first();
                }
            } else {
                $estimation = Masterprice::where('armada', $request->armada)->where('destinations_id', $request->destination_id)->where('outlets_id', Auth::user()->outlets_id)->first();
            }
        }

        if ($estimation) {
            $response = [
                'status' => 'success',
                'data' => [
                    'price'      => $estimation->price,
                    'estimation' => $estimation->estimation,
                    'minweights' => $estimation->minweights,
                ]
            ];
        } else {
            $response = [
                'status' => 'error',
                'data' => [
                    'price'      => '0',
                    'estimation' => '0',
                    'minweights' => '0',
                ]
            ];
        }

        return response()->json($response);
    }

    function getCunstomer(Request $request)
    {
        if ($request->outletasal) {
            $customers = User::where('outlets_id', $request->outletasal)->where('role_id', '4')->get(['id', 'name']);
            return response()->json(['customers' => $customers]);
        }

        return response()->json(['customers', []]);
    }



    function create()
    {
        if (Auth::user()->role_id == 1) {
            $customers = User::where('role_id', 4)->get();
        } else {
            $customers = User::where('role_id', 4)->where('outlets_id', Auth::user()->outlets_id)->get();
        }
        $destinations = Destination::all();
        $outlets = Outlet::all();
        return view('pages.order.create', compact('customers', 'destinations', 'outlets'));
    }




    function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->has('pesanan_masal')) {
                if (Auth::user()->role_id == 1) {
                    $validator = Validator::make($request->all(), [
                        'outlet_id' => 'required'
                    ], [
                        'outlet_id.required' => 'Pilih Salah Satu Outlet Asal'
                    ]);
                    if ($validator->fails()) {
                        $error = $validator->errors()->all();
                        $errorMessage = implode(', ', $error);

                        Alert::error('Gagal', $errorMessage);
                        return redirect()->back()->withInput();
                    }
                }

                $validator = Validator::make($request->all(), [
                    'customer_id'       => 'required',
                    'destination1_id'   => 'required',
                ], [
                    'customer_id.required'     => 'Pilih Salah Satu Customer',
                    'destination1_id.required' => 'Pilih Salah Satu Destinasi',
                ]);

                if ($validator->fails()) {
                    $errors       = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }

                // create order sebanyak total
                for ($i = 0; $i < $request->total; $i++) {

                    if ($request->outlet_id) {
                        $outlet = Outlet::find($request->outlet_id);
                    } else {
                        $outlet = Outlet::where('id', Auth::user()->outlets_id)->first();
                    }

                    $order = new Order();
                    $order->numberorders    = generateAwb();
                    $order->customer_id     = $request->customer_id;
                    $order->destinations_id = ($request->destination_id != null ? $request->destination_id : $request->destination1_id);
                    $order->status_orders   = 1;
                    $order->status_awb      = "Pesanan sedang diambil kurir";
                    $order->outlet_id       = $outlet->id;
                    $order->save();

                    // create history awb -> "Pesanan sedang diambl kurir"
                    $order->histories()->create([
                        'order_id' => $order->id,
                        'awb' => $order->numberorders,
                        'status' => 'Pesanan sedang diambil kurir',
                    ]);
                }
                DB::commit();
                Alert::success('Berhasil', 'Pesanan Berhasil Dibuat');
                return redirect()->to('/order');
            } else {
                if (Auth::user()->role_id == "1") {
                    $validator = Validator::make($request->all(), [
                        'outlet_id' => 'required',
                    ], [
                        'outlet_id.required' => 'Pilih Salah Satu Outlet Asal',
                    ]);

                    if ($validator->fails()) {
                        $errors = $validator->errors()->all();
                        $errorMessage = implode(', ', $errors);

                        Alert::error('Gagal', $errorMessage);
                        return redirect()->back()->withInput();
                    }
                }
                $validator = Validator::make($request->all(), [
                    'customer_id'       =>  'required',
                    'destination_id'    =>  'required',
                    'armada'            =>  'required',
                    'address'           =>  'required',
                    // 'weight'            =>  'required',
                    // 'volume'            =>  'required',
                    'estimation'        =>  'required',
                    'payment_method'    =>  'required',
                    'description'       =>  'required',
                    'note'              =>  'required',
                    'koli'              =>  'required',
                    'receiver'          =>  'required',
                    'awb'               => 'required|unique:orders,numberorders',
                ], [
                    'customer_id.required'    => 'Pilih Salah Satu Customer',
                    'destination_id.required' => 'Pilih Salah Satu Destinasi',
                    'armada.required'         => 'Pilih Salah Satu Armada',
                    'service.required'        => 'Pilih Salah Satu Jenis',
                    'address.required'        => 'Penerima Harus Diisi',
                    // 'weight.required'         => 'Berat Harus Diisi',
                    // 'volume.required'         => 'Volume Harus Diisi',
                    'estimation.required'     => 'Estimasi Harus Diisi',
                    'payment_method.required' => 'Pilih Salah Satu Metode Pembayaran',
                    'description.required'    => 'Deskripsi Harus Diisi',
                    'note.required'           => 'Catatan Harus Diisi',
                    'koli.required'           => 'Koli Harus Diisi',
                    'receiver.required'       => 'Penerima Harus Diisi',
                    'awb.required'            => 'AWB Harus Diisi',
                    'awb.unique'              => 'AWB Sudah Digunakan Harap Gunkan AWB Lain',
                ]);

                if ($validator->fails()) {
                    $errors       = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }

                // $order->awb = generateAwb();
                if ($request->outlet_id) {
                    $outlet = Outlet::find($request->outlet_id);
                } else {
                    $outlet = Outlet::where('id', Auth::user()->outlets_id)->first();
                }

                $gudangLocation = Outlet::find($outlet->id);

                $order = new Order();
                $order->numberorders    =  $request->awb;
                $order->customer_id     =  $request->customer_id;
                $order->status_orders   =  2;
                $order->outlet_id       =  $request->outlet;
                $order->armada          =  $request->armada;
                $order->service         =  $request->service;
                $order->destinations_id =  $request->destination_id;
                $order->address         =  $request->address;
                $order->weight          =  $request->weight;
                $order->volume          =  $request->volume;
                $order->payment_method  =  $request->payment_method;
                $order->price           =  $request->price;
                $order->estimation      =  $request->estimation;
                $order->description     =  $request->description;
                $order->koli            =  $request->koli;
                $order->note            =  $request->note;
                $order->outlet_id       =  $outlet->id;
                $order->penerima        =  $request->receiver;
                $order->status_awb      =  'Pesanan sedang di proses di ' . $gudangLocation->name . ' oleh ' . Auth::user()->name;
                $order->save();

                // create history awb -> "Pesanan sedang di proses di gudang ..."
                $order->histories()->create([
                    'order_id'  => $order->id,
                    'awb'       => $order->numberorders,
                    'status'    => 'Pesanan sedang di proses di ' . $gudangLocation->name . ' oleh ' . Auth::user()->name,
                    'created_by' => Auth::user()->id,
                ]);
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




    function show($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }
        $order      = Order::find($id);
        $historyAwbs = HistoryAwb::where('order_id', $order->id)->get();

        return view('pages.order.detail', compact('order', 'historyAwbs'));
    }


    public function showHistoryupdateOrder($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }


        $historyOrder      = HistoryUpdateOrder::find($id);
        return view('pages.order.detailhistoryupdateorder',compact('historyOrder'));
    }


    public function historyupdate($id) {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $order      = Order::find($id);

        return view('pages.order.historyupdateorder', compact('order'));
    }




    function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $order          = Order::find($id);
        if (Auth::user()->role_id == 1) {
            $customers = User::where('role_id', 4)->get();
        } else {
            $customers = User::where('role_id', 4)->where('outlets_id', Auth::user()->outlets_id)->get();
        }
        $destinations   = Destination::all();
        $outlets        = Outlet::all();
        return view('pages.order.edit', compact('order', 'customers', 'destinations', 'outlets'));
    }




    function update(Request $request, $id)
    {
        try {
            if (Auth::user()->role_id == "1") {
                $validator = Validator::make($request->all(), [
                    'outlet_id' => 'required',
                ], [
                    'outlet_id.required' => 'Pilih Salah Satu Outlet Asal',
                ]);

                if ($validator->fails()) {
                    $errors = $validator->errors()->all();
                    $errorMessage = implode(', ', $errors);

                    Alert::error('Gagal', $errorMessage);
                    return redirect()->back()->withInput();
                }
            }

            $order = Order::find(Crypt::decrypt($id));

            $validator = Validator::make($request->all(), [
                'customer_id'       =>  'required',
                'destination_id'    =>  'required',
                'armada'            =>  'required',
                'address'           =>  'required',
                // 'weight'            =>  'required',
                // 'volume'            =>  'required',
                'estimation'        =>  'required',
                'payment_method'    =>  'required',
                'description'       =>  'required',
                'note'              =>  'required',
                'koli'              =>  'required',
                'receiver'          =>  'required',
                'awb'               => 'required|unique:orders,numberorders,' . $order->id,
            ], [
                'customer_id.required'    => 'Pilih Salah Satu Customer',
                'destination_id.required' => 'Pilih Salah Satu Destinasi',
                'armada.required'         => 'Pilih Salah Satu Armada',
                'payment_method.required' => 'Pilih Salah Satu Metode Pembayaran',
                'service.required'        => 'Pilih Salah Satu Jenis',
                'address.required'        => 'Penerima Harus Diisi',
                // 'weight.required'         => 'Berat Harus Diisi',
                // 'volume.required'         => 'Volume Harus Diisi',
                'estimation.required'     => 'Estimasi Harus Diisi',
                'description.required'    => 'Deskripsi Harus Diisi',
                'note.required'           => 'Catatan Harus Diisi',
                'koli.required'           => 'Koli Harus Diisi',
                'receiver.required'       => 'Penerima Harus Diisi',
                'awb.required'            => 'AWB Harus Diisi',
                'awb.unique'              => 'AWB Sudah Digunakan Harap Gunkan AWB Lain',
            ]);

            if ($validator->fails()) {
                $errors       = $validator->errors()->all();
                $errorMessage = implode(', ', $errors);

                Alert::error('Gagal', $errorMessage);
                return redirect()->back()->withInput();
            }



            if ($request->outlet_id) {
                $outlet = Outlet::find($request->outlet_id);
            } else {
                $outlet = Outlet::where('id', $order->outlet_id)->first();
            }

            $gudangLocation = Outlet::find($outlet->id);

            $order->customer_id     = $request->customer_id;
            $order->status_orders   = 2;
            $order->outlet_id       = $outlet->id;
            $order->numberorders    = $request->awb;
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
            $order->koli            = $request->koli;
            $order->status_awb      = 'Pesanan sedang di proses di gudang ' . $gudangLocation->name . ' oleh ' . Auth::user()->name;
            $order->save();

            // create history awb -> "Pesanan sedang di proses di gudang Bekasi"
            $order->histories()->create([
                'order_id' => $order->id,
                'awb'      => $order->numberorders,
                'status'   => 'Pesanan sedang di proses di gudang ' . $gudangLocation->name,
                'created_by' => Auth::user()->id,
            ]);

             //create history order
             $historyOrder = new HistoryUpdateOrder();
             $historyOrder->order_id         = $order->id;
             $historyOrder->numberorders     = $order->numberorders;
             $historyOrder->customer_id      = $order->customer_id;
             $historyOrder->outlet_id        = $order->outlet_id;
             $historyOrder->destinations_id  = $order->destinations_id;
             $historyOrder->koli             = $order->koli;
             $historyOrder->weight           = $order->weight;
             $historyOrder->volume           = $order->volume;
             $historyOrder->price            = $order->price;
             $historyOrder->content          = $order->content;
             $historyOrder->penerima         = $order->penerima;
             $historyOrder->armada           = $order->armada;
             $historyOrder->address          = $order->address;
             $historyOrder->estimation       = $order->estimation;
             $historyOrder->status_orders    = $order->status_orders;
             $historyOrder->payment_method   = $order->payment_method;
             $historyOrder->status_awb       = $order->status_awb;
             $historyOrder->service          = $order->service;
             $historyOrder->description      = $order->description;
             $historyOrder->note             = $order->note;
             $historyOrder->photos           = $order->photos;
             $historyOrder->created_by       = Auth::user()->id;
             $historyOrder->save();



            DB::commit();
            Alert::success('Berhasil', 'Pesanan Berhasil Diupdate');
            return redirect()->to('/order');
        } catch (\Throwable $th) {
            dd($th);
            DB::rollBack();
            Alert::error('Gagal', 'Terjadi Kesalahan');
            return redirect()->back();
        }
    }




    function destroy($id)
    {
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $order = Order::find(Crypt::decrypt($id));
        $order->status_orders   = 4;
        $order->status_awb      = "Pesanan dibatalkan";
        $order->save();

        HistoryAwb::create([
            'order_id' => $order->id,
            'awb'      => $order->numberorders,
            'status'   => 'Pesanan dibatalkan',
            'created_by' => Auth::user()->id,
        ]);
        DB::commit();
        Alert::success('Berhasil', 'Pesanana Berhasil Dibatalkan');
        return redirect()->back();
    }



    function printformat1($id)
    {
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $order = Order::find($decrypted);
        $originLocationOrder = Destination::find($order->outlet->location_id);



        $pdf = new TCPDF;
        $pdf::SetFont('helvetica', '', 12);
        $pdf::SetTitle("$order->numberorders-pdf");
        $pdf::SetAuthor('Kaushal');
        $pdf::SetSubject('Generated PDF');


        // Set margin
        $leftMargin = 5;
        $topMargin = 5;
        $rightMargin = 5;
        $bottomMargin = 5;

        // Set margins to page
        $pdf::SetMargins($leftMargin, $topMargin, $rightMargin);
        $pdf::SetAutoPageBreak(true, $bottomMargin);

        //set paper size
        $pageWidth = 300;
        $pageHeight = 130;
        $pdf::AddPage('L', [$pageWidth, $pageHeight]);

        // Path gambar
        $imagePath = public_path('assets/img/logo.png');

        // HTML dengan gambar dalam tabel
        $html = view()->make('pages.order.print', compact('order', 'imagePath'));

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output("$order->numberorders-pdf.pdf", 'I');
        $pdf::reset();
    }



    function printformat2($id)
    {
        try {
            $decrypted = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $order = Order::find($decrypted);
        $originLocationOrder = Destination::find($order->outlet->location_id);

        return view('pages.order.print2', compact('order', 'originLocationOrder'));
    }
}
