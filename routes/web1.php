<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\TalukaController;
use App\Http\Controllers\CategoryContoller;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\OtherPurchaseControlller;
use App\Http\Controllers\GeneralSaleController;
use App\Http\Controllers\MultiPaymentController;
use App\Http\Controllers\MultiReceiptController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\JournalVoucherController;
use App\Http\Controllers\ContraTransactionController;
use App\Http\Controllers\FabricController;
use App\Http\Controllers\GeneralPurchaseReturnController;
use App\Http\Controllers\GeneralSalesReturnController;
use App\Http\Controllers\DrNoteController;
use App\Http\Controllers\transportController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\BusinessTypeController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\JobWorkerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FinishedGoodController;
use App\Http\Controllers\EmployeeGroupController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\JobStatusController;
use App\Http\Controllers\BuyerJobCardController;
use App\Http\Controllers\FabricInwardController;
use App\Http\Controllers\FabricCheckingController;
use App\Http\Controllers\BuyerPurchaseOrderController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\TaskMasterController;
use App\Http\Controllers\CrNoteController;
use App\Http\Controllers\MaterialOutwardController;
use App\Http\Controllers\MaterialInwardController;
use App\Http\Controllers\CuttingMasterController;
use App\Http\Controllers\FabricTrimCardMasterController;
use App\Http\Controllers\BuyerJobcardReportController;
use App\Http\Controllers\FabricInwardReportController;
use App\Http\Controllers\BundleController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Route::get('login',[LoginController::class,'index']);

Route::post('/Auth',[LoginController::class,'auth'])->name('Auth');

Route::get('/logout',[AdminController::class,'logout'])->name('logout');

Route::group(['middleware'=>'admin_auth'],function(){
Route::get('/dashboard',[AdminController::class,'dashboard']);
Route::resource('/Country', CountryController::class);
Route::resource('State', StateController::class);
Route::resource('Form', UserManagementController::class);
Route::resource('User_Management', PermissionController::class);
Route::resource('District', DistrictController::class);
Route::resource('Taluka', TalukaController::class);
Route::resource('Category', CategoryContoller::class);
Route::resource('Item', ItemMasterController::class);
Route::resource('OtherPurchase', OtherPurchaseControlller::class);
Route::get('GSTPER',[OtherPurchaseControlller::class,'GetData'])->name('GSTPER');
Route::resource('GeneralSales', GeneralSaleController::class);
Route::resource('MultiPayment', MultiPaymentController::class);
Route::get('getPaymentBillDetails',[MultiPaymentController::class,'getPaymentBillDetails'])->name('GetPaymentBillDetails');
Route::resource('MultiReceipt', MultiReceiptController::class);
Route::resource('Receipt_Transaction', ReceiptController::class);
Route::resource('Payment_Transaction', PaymentController::class);
Route::resource('Journal_Voucher', JournalVoucherController::class);
Route::resource('Contra_Transaction', ContraTransactionController::class);
Route::get('getUnpaidBills',[MultiReceiptController::class,'getUnpaidBills'])->name('getUnpaidBills');
Route::get('getReceiptDetail',[MultiReceiptController::class,'getReceiptDetail'])->name('getReceiptDetail');
Route::get('getUnpaidPaymentBills',[MultiPaymentController::class,'getUnpaidPaymentBills'])->name('getUnpaidPaymentBills');
Route::get('getPaymentDetail',[MultiPaymentController::class,'getPaymentDetail'])->name('getPaymentDetail');
Route::resource('Fabric_Purchase', FabricController::class);
Route::get('PartyShortlist',[FabricController::class,'PartyShortlist'])->name('PartyShortlist');
Route::resource('GeneralPurchaseReturn', GeneralPurchaseReturnController::class);
Route::resource('GeneralSalesReturn', GeneralSalesReturnController::class);
Route::resource('DrNote', DrNoteController::class);
Route::resource('CrNote', CrNoteController::class);
Route::get('PartyDetail',[DrNoteController::class,'GetData'])->name('PartyDetail');
Route::get('PartyDetail',[CrNoteController::class,'GetData'])->name('PartyDetail');
Route::get('/StateList',[LedgerController::class,'GetStateList'])->name('StateList');
Route::get('/DistrictList',[LedgerController::class,'GetDistrictList'])->name('DistrictList');
Route::get('/TalukaList',[LedgerController::class,'GetTalukaList'])->name('TalukaList');

Route::resource('Transport', transportController::class);
Route::resource('Ledger', LedgerController::class);
Route::resource('BusinessType', BusinessTypeController::class);
Route::resource('Firm', FirmController::class);
Route::resource('JobWorker', JobWorkerController::class);
Route::resource('Department', DepartmentController::class);
Route::resource('FinishedGood', FinishedGoodController::class);
Route::resource('EmployeeGroup', EmployeeGroupController::class);
Route::resource('Unit', UnitController::class);
Route::resource('Color', ColorController::class);
Route::resource('Size', SizeController::class);
Route::resource('Location', LocationController::class);
Route::resource('PurchaseOrder', PurchaseOrderController::class);
Route::resource('Brand', BrandController::class);
Route::resource('Season', SeasonController::class);
Route::resource('JobStatus', JobStatusController::class);
Route::resource('BuyerJobCard', BuyerJobCardController::class);
Route::resource('FabricInward', FabricInwardController::class);
Route::resource('FabricChecking', FabricCheckingController::class);
Route::get('/InwardList',[FabricCheckingController::class,'getDetails'])->name('InwardList');
Route::get('/InwardMasterList',[FabricCheckingController::class,'getMasterdata'])->name('InwardMasterList');
Route::resource('BuyerPurchaseOrder', BuyerPurchaseOrderController::class);
Route::get('/GetAddress',[BuyerPurchaseOrderController::class,'getAddress'])->name('GetAddress');
Route::get('/TaxList',[BuyerPurchaseOrderController::class,'GetTaxList'])->name('TaxList');
Route::resource('Task', TaskMasterController::class);
Route::resource('MaterialOutward', MaterialOutwardController::class);
Route::resource('MaterialInward', MaterialInwardController::class);

Route::resource('FabricCutting', CuttingMasterController::class);
Route::get('/RatioList',[CuttingMasterController::class,'getRatioDetails'])->name('RatioList');
Route::get('/EndDataList',[CuttingMasterController::class,'getEndDataDetails'])->name('EndDataList');
Route::get('/CheckingFabricList',[CuttingMasterController::class,'getCheckingFabricdata'])->name('CheckingFabricList');
Route::get('/CheckingMasterList',[CuttingMasterController::class,'getCheckingMasterdata'])->name('CheckingMasterList');

Route::resource('Task', TaskMasterController::class);
Route::get('/CommanData',[TaskMasterController::class,'getCommanDetails'])->name('CommanData');
Route::get('/SizeBalanceList',[TaskMasterController::class,'getBalanceDetails'])->name('SizeBalanceList');
Route::get('TaskList',[CuttingMasterController::class,'GetTaskList'])->name('TaskList');

Route::resource('FabricTrimCard', FabricTrimCardMasterController::class);
Route::get('/JobCardDetail',[FabricTrimCardMasterController::class,'getJobCardDetails'])->name('JobCardDetail');
Route::get('/Average',[FabricTrimCardMasterController::class,'getColorAverage'])->name('Average');
Route::get('/ColorDetails',[FabricTrimCardMasterController::class,'getColorDetails'])->name('ColorDetails');

Route::resource('JobCardReport', BuyerJobcardReportController::class);
Route::resource('InwardReport', FabricInwardReportController::class);
Route::resource('BundleBarcode', BundleController::class);
Route::get('/BundleList',[BundleController::class,'getDetails'])->name('BundleList');
Route::get('/BundleSplitList',[BundleController::class,'getRowDetails'])->name('BundleSplitList');
Route::get('/BundlePrint',[BundleController::class,'BundlePrinting'])->name('BundlePrint');


});