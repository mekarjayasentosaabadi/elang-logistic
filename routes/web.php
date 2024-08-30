<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AwbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManifestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurattugasController;
use App\Http\Controllers\UpdateResiController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\MasterpriceController;
use App\Http\Controllers\ShippingcourirController;
use App\Http\Controllers\TraveldocumentController;
use App\Http\Controllers\ShippingcourierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// dashboard admin, superadmin, courier, customer, directur
Route::middleware(['auth', 'check.role:1,2,4,6'])->group(function (){
    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/getData', [DashboardController::class, 'getData'])->name('dashboard.getData');
});


// master admin and superadmin
Route::middleware(['auth', 'check.role:1,2'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::get('/getAll', [UserController::class, 'getAll'])->name('user.getAll');
        Route::get('/create', [UserController::class, 'create']);
        Route::post('/store', [UserController::class, 'store']);
        Route::get('/{id}/edit', [UserController::class, 'edit']);
        Route::patch('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::post('/{id}/resetpassword', [UserController::class, 'resetpassword']);
    });

    Route::prefix('destination')->group(function () {
        Route::get('/', [DestinationController::class, 'index'])->name('destination.index');
        Route::get('/getAll', [DestinationController::class, 'getAll'])->name('destination.getAll');
        Route::get('/{id}', [DestinationController::class, 'edit'])->name('destination.edit');
        Route::post('/', [DestinationController::class, 'stored'])->name('destination.stored');
        Route::post('/{id}', [DestinationController::class, 'update'])->name('destination.update');
        Route::get('/listoutlet/{id}', [DestinationController::class, 'listoutlet'])->name('destination.listoutlet');
    });

    Route::prefix('customer')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/save', [CustomerController::class, 'store'])->name('customer.stored');
        Route::get('/getAll', [CustomerController::class, 'getAll'])->name('customer.getAll');
        Route::get('/{id}', [CustomerController::class, 'show']);
        Route::get('/{id}/edit', [CustomerController::class, 'edit']);
        Route::post('/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::post('/{id}/changeStatus', [CustomerController::class, 'changeStatus'])->name('customer.changestatus');
        Route::get('/{id}/getcustomerprice', [CustomerController::class, 'getcustomerprice'])->name('customer.getcustomerprice');
        Route::post('/{id}/generatecustomerprice', [CustomerController::class, 'generatecustomerprice'])->name('customer.generatecustomerprice');
        Route::post('/{id}/changeprice', [CustomerController::class, 'changeprice'])->name('customer.changeprice');
        Route::post('/{id}/addmanualprice', [CustomerController::class, 'addmanualprice'])->name('customer.addmanualprice');
    });

    Route::prefix('outlet')->group(function () {
        Route::get('/', [OutletController::class, 'index'])->name('outlet.index');
        Route::get('/getAll', [OutletController::class, 'getAll'])->name('outlet.getAll');
        Route::get('/create', [OutletController::class, 'create']);
        Route::post('/', [OutletController::class, 'store']);
        Route::get('/{id}/edit', [OutletController::class, 'edit']);
        Route::patch('/{id}', [OutletController::class, 'update']);
        Route::delete('/{id}', [OutletController::class, 'destroy']);
        Route::post('/{id}/changeStatus', [OutletController::class, 'changeStatus'])->name('outlet.changestatus');
    });

    Route::prefix('order')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('order.index');
        Route::get('/getAll', [OrderController::class, 'getAll'])->name('order.getAll');
        Route::get('/getHistoryUpdateOrder', [OrderController::class, 'getHistoryUpdateOrder'])->name('order.getHistoryUpdateOrder');
        Route::get('/create', [OrderController::class, 'create']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}/edit', [OrderController::class, 'edit']);
        Route::get('/{id}/detail', [OrderController::class, 'show']);
        Route::get('/{id}/historyupdate', [OrderController::class, 'historyupdate']);
        Route::get('/{id}/detailhistoryupdate', [OrderController::class, 'showHistoryupdateOrder']);
        Route::patch('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
        Route::get('/{id}/print', [OrderController::class, 'printformat1']);
        Route::get('/{id}/print-v2', [OrderController::class, 'printformat2']);
        Route::get('/get-estimation', [OrderController::class, 'getEstimation']);
        Route::get('/get-customer', [OrderController::class, 'getCunstomer']);
    });

    Route::prefix('vehicle')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('vehicle.index');
        Route::get('/getAll', [VehicleController::class, 'getAll'])->name('vehicle.getAll');
        Route::get('/create', [VehicleController::class, 'create']);
        Route::post('/store', [VehicleController::class, 'store']);
        Route::get('/{id}/edit', [VehicleController::class, 'edit']);
        Route::patch('/{id}', [VehicleController::class, 'update']);
    });

    Route::prefix('manifest')->group(function () {
        Route::get('/', [ManifestController::class, 'index'])->name('manifest.index');
        Route::get('/getAll', [ManifestController::class, 'getAll'])->name('manifest.getAll');
        Route::get('/create', [ManifestController::class, 'create'])->name('manifest.create');
        Route::get('/getOrders', [ManifestController::class, 'getOrders'])->name('manifest.getorders');
        Route::get('/checkOrders/{id}', [ManifestController::class, 'checkOrders'])->name('manifest.checkOrders');
        Route::post('/store', [ManifestController::class, 'store'])->name('manifest.store');
        Route::get('/{id}/edit', [ManifestController::class, 'edit'])->name('manifest.edit');
        Route::get('/{id}/getdetail', [ManifestController::class, 'getdetail'])->name('manifest.getdetail');
        Route::post('/{id}/delete', [ManifestController::class, 'delete'])->name('manifest.delete');
        Route::post('/{id}/deletedetailold', [ManifestController::class, 'deletedetailold'])->name('manifest.deletedetailold');
        Route::post('/{id}/update', [ManifestController::class, 'update'])->name('manifest.update');
        Route::post('/{id}/addDetail/{ordersid}', [ManifestController::class, 'addDetail'])->name('manifest.addDetail');
        Route::get('/{id}/print', [ManifestController::class, 'printresi'])->name('manifest.cetakresi');
    });

    //Price
    Route::prefix('masterprice')->group(function () {
        Route::get('/', [MasterpriceController::class, 'index'])->name('masterprice.index');
        Route::get('/getAll', [MasterpriceController::class, 'getAll'])->name('masterprice.getAll');
        Route::get('/create', [MasterpriceController::class, 'create'])->name('masterprice.create');
        Route::post('/', [MasterpriceController::class, 'store'])->name('masterprice.store');
        Route::get('/{id}/edit', [MasterpriceController::class, 'edit'])->name('masterprice.edit');
        Route::post('/{id}', [MasterpriceController::class, 'update'])->name('masterprice.update');
    });

    //Delivery
    Route::prefix('delivery')->group(function () {
        Route::get('/', [TraveldocumentController::class, 'index'])->name('traveldocument.index');
        Route::get('/create', [TraveldocumentController::class, 'create'])->name('traveldocument.create');
        Route::get('/getAll', [TraveldocumentController::class, 'getAll'])->name('traveldocument.getAll');
        Route::get('/{id}/manifestorder', [TraveldocumentController::class, 'manifestorder'])->name('traveldocument.manifestorder');
        Route::post('/', [TraveldocumentController::class, 'store'])->name('traveldocument.store');
        Route::get('/{id}/cetak', [TraveldocumentController::class, 'print'])->name('traveldocument.cetak');
        Route::post('{id}/delete', [TraveldocumentController::class, 'delete'])->name('traveldocument.delete');
        Route::get('/{id}/edit', [TraveldocumentController::class, 'edit'])->name('traveldocument.edit');
        Route::get('/{id}/listDetail', [TraveldocumentController::class, 'listDetail'])->name('traveldocument.listDetail');
        Route::get('/{id}/listDetailEdit', [TraveldocumentController::class, 'listDetailEdit'])->name('traveldocument.listDetailEdit');
        Route::post('{id}/deleteDetail', [TraveldocumentController::class, 'deleteDetail'])->name('traveldocument.deleteDetail');
        Route::post('/{id}', [TraveldocumentController::class, 'update'])->name('traveldocument.update');
    });

    //Surat tugas
    Route::prefix('surattugas')->group(function () {
        Route::get('/', [SurattugasController::class, 'index'])->name('surattugas.index');
        Route::get('/create', [SurattugasController::class, 'create'])->name('surattugas.create');
        Route::get('/getAll', [SurattugasController::class, 'getAll'])->name('surattugas.getAll');
        Route::get('/{id}/manifest', [SurattugasController::class, 'getManifest'])->name('surattugas.manifest');
        Route::post('/', [SurattugasController::class, 'store'])->name('surattugas.store');
        Route::post('/{id}/delete', [SurattugasController::class, 'delete'])->name('surattugas.delete');
        Route::get('/{id}/edit', [SurattugasController::class, 'edit'])->name('surattugas.edit');
        Route::get('/{id}/getListSuratJalan', [SurattugasController::class, 'getListSuratJalan'])->name('surattugas.getListSuratJalan');
        Route::post('/{id}/deleteList', [SurattugasController::class, 'deleteList'])->name('surattugas.deleteList');
        Route::post('/{id}/onGoing', [SurattugasController::class, 'onGoing'])->name('surattugas.onGoing');
    });


    Route::prefix('cek-resi')->group(function () {
        Route::get('/', [AwbController::class, 'index'])->name('cek-resi.index');
        Route::get('/{awb}', [AwbController::class, 'getResi'])->name('cek-resi.find');
    });


    Route::prefix('update-resi')->group(function () {
        Route::get('/', [UpdateResiController::class, 'index'])->name('update-resi.index');
        Route::post('/', [UpdateResiController::class, 'store'])->name('update-resi.store');
        Route::get('/getResi', [UpdateResiController::class, 'getResi'])->name('update-resi.getResi');
    });

    Route::prefix('report')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('report.index');
        Route::get('/transaksi', [ReportController::class, 'reportTransaksi'])->name('report.reportTransaksi');
        Route::get('/getDriverByOutlet', [ReportController::class, 'getDriverByOutlet'])->name('report.getDriverByOutlet');
        Route::get('/getCustomerByOutlet', [ReportController::class, 'getCustomerByOutlet'])->name('report.getCustomerByOutlet');
        Route::post('/getReportPengiriman', [ReportController::class, 'getReportPengiriman'])->name('report.getReportPengiriman');
        Route::post('/getReportTransaksi', [ReportController::class, 'getReportTransaksi'])->name('report.getReportTransaksi');
        Route::post('/downloadreportpengiriman', [ReportController::class, 'downloadreportpengiriman'])->name('report.downloadreportpengiriman');
        Route::post('/downloadreporttransaksi', [ReportController::class, 'downloadreporttransaksi'])->name('report.downloadreporttransaksi');
    });

    Route::get('/cek', function () {
        return view('pages.cek');
    });
});

// shipping courier admin superadmin and courier
Route::middleware(['auth', 'check.role:1,2,3'])->group(function () {
    Route::prefix('shipping-courier')->group(function () {
        Route::get('/', [ShippingcourierController::class, 'index'])->name('shipping.index');
        Route::get('/getAll', [ShippingcourierController::class, 'getAll'])->name('shipping.getAll');
        Route::get('/getDetail', [ShippingcourierController::class, 'getDetail'])->name('shipping.getDetail');
        Route::get('/getOrders', [ShippingcourierController::class, 'getOrder'])->name('shipping.getOrder');
        Route::get('/getOrdersByOutlet', [ShippingcourierController::class, 'getOrdersByOutlet'])->name('shipping.getOrdersByOutlet');
        Route::get('/getOrderDetail', [ShippingcourierController::class, 'getOrderDetail'])->name('shipping.getOrderDetail');
        Route::get('/getCourier', [ShippingcourierController::class, 'getCourier'])->name('shipping.getCourier');
        Route::get('/create', [ShippingcourierController::class, 'create'])->name('shipping.create');
        Route::post('/store', [ShippingcourierController::class, 'store'])->name('shipping.store');
        Route::get('/{id}/done', [ShippingcourierController::class, 'done'])->name('shipping.done');
        Route::get('/{id}/edit', [ShippingcourierController::class, 'edit'])->name('shipping.edit');
        Route::get('/{id}/show', [ShippingcourierController::class, 'show'])->name('shipping.show');
        Route::patch('/{id}/update', [ShippingcourierController::class, 'update'])->name('shipping.update');
        Route::get('/{id}/sendShipping', [ShippingcourierController::class, 'sendShipping'])->name('shipping.sendShipping');
        Route::post('/{id}/storeShippingDone', [ShippingcourierController::class, 'storeShippingDone'])->name('shipping.storeShippingDone');
    });
});


//profile admin superadmin courier
Route::middleware(['auth', 'check.role:1,2,3,4,6'])->group(function () {
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/changepassword', [ProfileController::class, 'changePassword'])->name('profile.changepassword');
        Route::post('/changepicture', [ProfileController::class, 'changepictures'])->name('profile.changepicture');
    });

    Route::get('/logout', [AuthController::class, 'logout']);
});


// logactifity directur
Route::middleware(['auth', 'check.role:6'])->group(function () {
    Route::prefix('logactifity')->group(function () {
        Route::get('/', function () {
            return 'ppp';
        });
    });
});

