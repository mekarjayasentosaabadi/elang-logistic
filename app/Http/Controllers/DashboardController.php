<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helper\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    function index()
    {
        return view('pages.dashboard.index');
    }
    //getdatadashboard
    function getData(){
        $tglAwal = Carbon::now()->startOfMonth();
        $tglAkhir = Carbon::now()->endOfMonth();
        $today = Carbon::today()->format('Y-m-d');
        $roleId = Auth::user()->role_id;
        $outletId = Auth::user()->outlets_id;
        if($roleId == '1'){
            $trxToday = Order::whereDate('created_at', $today)->count();
            $trxProcess = Order::where('status_orders', '2')->count();
            $totalIncome = Order::whereBetween('created_at', [$tglAwal, $tglAkhir])->sum('price');
            $transaksiperbulan = Order::select(
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('SUM(price) as total'))
                ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
                ->orderBy(DB::raw('MONTH(created_at)'), 'asc')->get();
            $startOfWeek    = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            $endOfWeek      = Carbon::now()->endOfWeek(Carbon::SATURDAY);

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $dailyTransactions = Order::select(DB::raw('DAYOFWEEK(created_at) as day_of_week'), DB::raw('SUM(price) as total'))
                        ->whereBetWeen('created_at', [$startOfWeek, $endOfWeek])
                        ->groupBy('day_of_week')
                        ->orderBy('day_of_week')
                        ->get();
            $daysOfWeek = [
            1 => 'Minggu',
            2 => 'Senin',
            3 => 'Selasa',
            4 => 'Rabu',
            5 => 'Kamis',
            6 => 'Jumat',
            7 => 'Sabtu'
            ];
            $dailyTransactions = $dailyTransactions->map(function ($transaction) use ($daysOfWeek) {
            $transaction->day_name = $daysOfWeek[$transaction->day_of_week];
            return $transaction;
            });
            $topCustomer = DB::table('orders')
                                ->join('users', 'users.id', '=', 'orders.customer_id')
                                ->select('users.id', 'users.name', DB::raw('SUM(orders.price) as total_orders'), DB::raw('COUNT(orders.id) as total_transaksi'))
                                ->where('users.role_id', '4')
                                ->groupBy('users.id')
                                ->orderBy(DB::raw('SUM(orders.price)'), 'DESC')
                                ->limit(10)
                                ->get();
        }
        if($roleId == '2'){
            $trxToday = Order::whereDate('created_at', $today)
                            ->where('outlet_id', $outletId)
                            ->count();
            $trxProcess = Order::where('status_orders', '2')->where('outlet_id', $outletId)->count();
            $totalIncome = Order::whereBetween('created_at', [$tglAwal, $tglAkhir])->where('outlet_id', $outletId)->sum('price');
            $transaksiperbulan = Order::select(
                                    DB::raw('YEAR(created_at) as tahun'),
                                    DB::raw('MONTH(created_at) as bulan'),
                                    DB::raw('SUM(price) as total'))
                                    ->where('outlet_id', $outletId)
                                    ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                                    ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
                                    ->orderBy(DB::raw('MONTH(created_at)'), 'asc')->get();
            $startOfWeek    = Carbon::now()->startOfWeek(Carbon::SUNDAY);
            $endOfWeek      = Carbon::now()->endOfWeek(Carbon::SATURDAY);

            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $dailyTransactions = Order::select(DB::raw('DAYOFWEEK(created_at) as day_of_week'), DB::raw('SUM(price) as total'))
                                ->whereBetWeen('created_at', [$startOfWeek, $endOfWeek])
                                ->where('outlet_id', $outletId)
                                ->groupBy('day_of_week')
                                ->orderBy('day_of_week')
                                ->get();
            $daysOfWeek = [
                1 => 'Minggu',
                2 => 'Senin',
                3 => 'Selasa',
                4 => 'Rabu',
                5 => 'Kamis',
                6 => 'Jumat',
                7 => 'Sabtu'
            ];
            $dailyTransactions = $dailyTransactions->map(function ($transaction) use ($daysOfWeek) {
                $transaction->day_name = $daysOfWeek[$transaction->day_of_week];
                return $transaction;
            });
            $topCustomer = DB::table('orders')
                                ->join('users', 'users.id', '=', 'orders.customer_id')
                                ->select('users.id', 'users.name', DB::raw('SUM(orders.price) as total_orders'), DB::raw('COUNT(orders.id) as total_transaksi'))
                                ->where('users.role_id', '4')
                                ->groupBy('users.id')
                                ->orderBy(DB::raw('SUM(orders.price)'), 'DESC')
                                ->limit(10)
                                ->get();
        }
        return ResponseFormatter::success([
            'trxToday' => $trxToday,
            'trxProcess' => $trxProcess,
            'totalIncome' => $totalIncome,
            'transaksiperbulan' => $transaksiperbulan,
            'transaksimingguan' => $dailyTransactions,
            'topcustomer'       => $topCustomer
        ], 'Berhasil mengambil data');
    }
}
