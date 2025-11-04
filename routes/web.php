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
use App\Http\Controllers\CityController;
use App\Http\Controllers\CategoryContoller;
use App\Http\Controllers\ItemMasterController;
use App\Http\Controllers\HSNMasterController;
use App\Http\Controllers\OtherPurchaseControlller;
use App\Http\Controllers\GeneralSaleController;
use App\Http\Controllers\MultiPaymentController;
use App\Http\Controllers\MultiReceiptController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MerchantMasterController; 
use App\Http\Controllers\PDMerchantMasterController;  
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
use App\Http\Controllers\JobPartController;
use App\Http\Controllers\FabricTrimPartController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\FabricOutwardController;
use App\Http\Controllers\FabricCheckingReportController; 
use App\Http\Controllers\FabricCuttingReportController; 
use App\Http\Controllers\FabricTrimCardReportController; 
use App\Http\Controllers\PDFController;
use App\Http\Controllers\FabricSummaryGRNController;
use App\Http\Controllers\MaterialInwardStoreController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\RequisitionOutwardController;
use App\Http\Controllers\ReturnableOutwardController;
use App\Http\Controllers\POReportController;
use App\Http\Controllers\MaterialInwardStoreReportController;
use App\Http\Controllers\RequisitionReportController;
use App\Http\Controllers\POItemWiseReportController;
use App\Http\Controllers\MIStoreItemwiseReportController;
use App\Http\Controllers\RequisitionOutwardReportController;
use App\Http\Controllers\StockReportController;
use App\Http\Controllers\FabricOutwardReportController;
use App\Http\Controllers\OrderGroupController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PaymentTermsController;
use App\Http\Controllers\DeliveryTermsController;
use App\Http\Controllers\ShipmentModeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ProcessController; 
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\MachineTypeController; 
use App\Http\Controllers\FabricDefectController; 
use App\Http\Controllers\MainStyleController; 
use App\Http\Controllers\SubStyleController; 
use App\Http\Controllers\SalesOrderCostingController; 
use App\Http\Controllers\ClassificationController;
use App\Http\Controllers\BOMController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\VendorWorkOrderController;
use App\Http\Controllers\VendorPurchaseOrderController;
use App\Http\Controllers\TrimsInwardController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\StitchingInhouseMasterController;
use App\Http\Controllers\FinishingInhouseMasterController;
use App\Http\Controllers\PackingInhouseMasterController;
use App\Http\Controllers\PackingMasterController;
use App\Http\Controllers\PackingVendorMasterController;
use App\Http\Controllers\TrimsOutwardController;
use App\Http\Controllers\CartonPackingInhouseMasterController;
use App\Http\Controllers\FGTransferToLocationController;
use App\Http\Controllers\FGTransferToLocationInwardController;
use App\Http\Controllers\CutPanelIssueMasterController;
use App\Http\Controllers\QCStitchingInhouseMasterController;
use App\Http\Controllers\LineController;
use App\Http\Controllers\CutPanelGRNMasterController;
use App\Http\Controllers\OutwardForFinishingMasterController;
use App\Http\Controllers\OutwardForPackingMasterController;
use App\Http\Controllers\SaleTransactionMasterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresentEmployeesController;
use App\Http\Controllers\ActivityMasterController;
use App\Http\Controllers\ActivityTypeMasterController;
use App\Http\Controllers\T_And_A_MasterController;
use App\Http\Controllers\PPCMasterController;
use App\Http\Controllers\T_And_A_TemplateMasterController;
use App\Http\Controllers\TransferPackingInhouseMasterController;
use App\Http\Controllers\FGLocationTransferOutwardMasterController;
use App\Http\Controllers\ReportViewerController;
use App\Http\Controllers\OpenOrderPPCController;
use App\Http\Controllers\RejectedPcsDeliveryChallanController;
use App\Http\Controllers\StockAssociationController;
use App\Http\Controllers\StockAssociationForFabricController;
use App\Http\Controllers\ReturnPackingInhouseMasterController;
use App\Http\Controllers\DeliveryChallanController;
use App\Http\Controllers\StitchingDefectController;
use App\Http\Controllers\StitchingOperationController;
use App\Http\Controllers\DHUController;
use App\Http\Controllers\KDPLWiseSetPercentageController;
use App\Http\Controllers\BuyerPortalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WashingInhouseController;
use App\Http\Controllers\OCRController;
use App\Http\Controllers\OperationNameController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\CuttingEntryController;
use App\Http\Controllers\DailyProductionEntryController;
use App\Http\Controllers\EmployeeMasterController;
use App\Http\Controllers\WIPAdjustableQtyController;
use App\Http\Controllers\PackingRejectionController;
use App\Http\Controllers\BuyerCostingController;
use App\Http\Controllers\BuyerBrandAuthController;
use App\Http\Controllers\MaterialIssueController;
use App\Http\Controllers\FGLocationTransferInwardMasterController;
use App\Http\Controllers\OutletSaleController;
use App\Http\Controllers\FGOutletOpeningController;
use App\Http\Controllers\SampleTypeController;
use App\Http\Controllers\SampleIndentController;
use App\Http\Controllers\SampleCadDeptController;
use App\Http\Controllers\SampleQcDeptController;
use App\Http\Controllers\SampleCustomerFeedbackController;
use App\Http\Controllers\FinishingRateController;
use App\Http\Controllers\PerticularController;
use App\Http\Controllers\FinishingBillingController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\InwardForPackingController;
use App\Http\Controllers\OpportunityController;
use Illuminate\Http\Request;
use App\Mail\WebsiteOrder;
use Illuminate\Support\Facades\Mail;  
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\MonthlyBudgetController;
use App\Http\Controllers\SparesReturnMaterialStatusController;
use App\Http\Controllers\MaterialReturnController; 
use App\Http\Controllers\MaterialTransferFromInwardController; 
use App\Http\Controllers\SpareItemController; 
use App\Http\Controllers\SparePurchaseOrderController; 
use App\Http\Controllers\MachineModelMasterController; 
use App\Http\Controllers\WashTypeController; 
use App\Http\Controllers\FabricInwardCuttingDepartmentController;
use App\Http\Controllers\FabricOutwardCuttingDepartmentController;  
use App\Http\Controllers\FabricGateEntryController; 
use App\Http\Controllers\TrimsGateEntryController;
use App\Http\Controllers\StyleNoController;
// use App\Http\Controllers\ERPQueryController; 

// Maintenance Module From Seaquid 02-12-2024
use App\Http\Controllers\MachineLocationMasterController;
use App\Http\Controllers\MachineMakeMasterController;
use App\Http\Controllers\MachineMainTypeMasterController;
use App\Http\Controllers\PreventiveNameMasterController;
use App\Http\Controllers\PurposeMasterController;
use App\Http\Controllers\MachineMasterController;
use App\Http\Controllers\InwardRentedMachineController;
use App\Http\Controllers\MachineTransferController;
use App\Http\Controllers\MachineryMaintanceController;
use App\Http\Controllers\MachineryPreventiveController;
use App\Http\Controllers\MaterialTransferFromController;
// End of Maintenance Module

use App\Http\Controllers\OBMasterController;
use App\Http\Controllers\LinePlanController;
use App\Http\Controllers\AssignToOrderController;
use App\Http\Controllers\DailyProductionEntry;
use App\Http\Controllers\StyleMasterController;
use App\Http\Controllers\HourlyProductionEntryController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\FormTableMasterController; 
use App\Http\Controllers\POAuthorityMatrixController; 
use App\Http\Controllers\SOPurchaseOrderAuthorityMatrixController; 
use App\Http\Controllers\BarcodeBrandController;
// use DB;

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


Route::get('clear_cache', function () {

    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear'); 
    Artisan::call('optimize:clear'); 
    Artisan::call('route:cache');   
    Artisan::call('cache:clear');  
    
    dd("Cache is cleared");
  
});


Route::get('/', function () {
    
$financialYearList=DB::table('ken_year_databases')->select('year_id','year_name')->where('delflag',0)->get();

return view('login',compact('financialYearList'));
// return view('UnderMaintance');
});

Route::get('login',[LoginController::class,'index']);

Route::post('/auth',[AuthController::class,'auth'])->name('auth');

Route::get('/buyerPortalLogin',[LoginController::class,'buyerPortalLogin'])->name('buyerPortalLogin');
 
Route::get('/logout',[AdminController::class,'logout'])->name('logout');
Route::get('/TestAnimation',[AdminController::class,'TestAnimation'])->name('TestAnimation');

// Route::post('/codex-command', [ERPQueryController::class, 'processPrompt']);

Route::get('chatgpt',[AdminController::class,'chatgpt'])->name('chatgpt');
// routes/web.php
// Route::get('/get-table-names', function () {
//     $tables = DB::select('SHOW TABLES');
//     return response()->json($tables);
// });

// Route::get('/get-table-data/{table}', function ($table) {
//     try {
//         date_default_timezone_set('Asia/Kolkata');
//         //DB::enableQueryLog();
//         $data = DB::table($table)->orderBy('created_at', 'desc')->limit(50)->get(); // Limit rows for performance 
//         //dd(DB::getQueryLog());
//         return response()->json(['success' => true, 'data' => $data]);
//     } catch (\Exception $e) {
//         return response()->json(['success' => false, 'error' => 'Invalid table name']);
//     }
// });

Route::get('ken-b2b',[AdminController::class,'Exhibition'])->name('Exhibition');
Route::get('ExhibitionProductList',[AdminController::class,'ExhibitionProductList'])->name('ExhibitionProductList');
Route::get('LoadExhibitionProducts',[AdminController::class,'LoadExhibitionProducts'])->name('LoadExhibitionProducts');
Route::get('ken-b2bProduct/{id}',[AdminController::class,'ExhibitionProduct'])->name('ExhibitionProduct');
Route::delete('/DeleteProductSubFilter/{id}', [AdminController::class, 'DeleteProductSubFilter'])->name('DeleteProductSubFilter');
Route::delete('/DeleteProduct/{id}', [AdminController::class, 'DeleteProduct'])->name('DeleteProduct');
Route::post('ExhibitionProductImport',[AdminController::class,'ExhibitionProductImport'])->name('ExhibitionProductImport');
Route::post('NewProductStore',[AdminController::class,'NewProductStore'])->name('NewProductStore');
Route::post('UpdateProductFilter',[AdminController::class,'UpdateProductFilter'])->name('UpdateProductFilter');
Route::post('ExhibitionProductImport',[AdminController::class,'ExhibitionProductImport'])->name('ExhibitionProductImport');
Route::get('ProductMaster',[AdminController::class,'ProductMaster'])->name('ProductMaster');
Route::get('ProductFilterCategoryList',[AdminController::class,'ProductFilterCategoryList'])->name('ProductFilterCategoryList');
Route::post('ProductUploadImage',[AdminController::class,'ProductUploadImage'])->name('ProductUploadImage');
Route::post('UpdateExProductDetails',[AdminController::class,'UpdateExProductDetails'])->name('UpdateExProductDetails');
Route::get('DeleteAllExhibitionProducts',[AdminController::class,'DeleteAllExhibitionProducts'])->name('DeleteAllExhibitionProducts');

Route::group(['middleware'=>['SetDatabaseForWeb','admin_auth']],function(){ 
   
Route::get('/dashboard',[AdminController::class,'dashboard']);
// Route::get('/dashboard2nd',[AdminController::class,'MDDashboard1']);
Route::get('/dashboard2nd',[AdminController::class,'GraphicalDashboard'])->name('dashboard2nd');
Route::get('/operation_dashboard',[AdminController::class,'operation_dashboard'])->name('operation_dashboard');


Route::get('/activity_log',[AdminController::class,'activity_log'])->name('activity_log');
Route::get('/activity_inward_log',[AdminController::class,'activity_inward_log'])->name('activity_inward_log');
Route::get('/activity_sales_order_log',[AdminController::class,'activity_sales_order_log'])->name('activity_sales_order_log');
Route::get('/activity_sales_order_costing_log',[AdminController::class,'activity_sales_order_costing_log']);
Route::get('/activity_purchase_order_log',[AdminController::class,'activity_purchase_order_log'])->name('activity_purchase_order_log');




Route::get('/mis_dashboard_pbi',[AdminController::class,'mis_dashboard_pbi'])->name('mis_dashboard_pbi');

Route::get('CheckTodayBirthdayHRMS',[AdminController::class,'CheckTodayBirthdayHRMS'])->name('CheckTodayBirthdayHRMS');
 
Route::get('WorkInProgressStatusList',[AdminController::class,'WorkInProgressStatusList'])->name('WorkInProgressStatusList');

Route::get('OrderStatusListDashboard',[AdminController::class,'OrderStatusListDashboard']);

Route::get('loadBookingSummary',[AdminController::class,'loadBookingSummary'])->name('loadBookingSummary');
Route::get('loadJobWorkBookingSummary',[AdminController::class,'loadJobWorkBookingSummary'])->name('loadJobWorkBookingSummary');

Route::get('SalesOrderDetailDashboard',[AdminController::class,'SalesOrderDetailDashboard'])->name('SalesOrderDetailDashboard');
Route::get('SalesDashboard',[AdminController::class,'SalesDashboard'])->name('SalesDashboard');
Route::get('RawMaterialDashboard',[AdminController::class,'RawMaterialDashboard'])->name('RawMaterialDashboard');
Route::get('Finishing',[AdminController::class,'Finishing'])->name('Finishing');
Route::get('OrderStatus',[AdminController::class,'OrderStatus'])->name('OrderStatus');
Route::get('SaleStatus',[AdminController::class,'SaleStatus'])->name('SaleStatus');
Route::get('FabricStatus',[AdminController::class,'FabricStatus'])->name('FabricStatus');
Route::get('FinishingGoodsStatus',[AdminController::class,'FinishingGoodsStatus'])->name('FinishingGoodsStatus');
Route::get('TrimStatus',[AdminController::class,'TrimStatus'])->name('TrimStatus');
Route::get('WorkInProgressStatus',[AdminController::class,'WorkInProgressStatus'])->name('WorkInProgressStatus');
Route::get('GarmentSale',[AdminController::class,'GarmentSale'])->name('GarmentSale');
Route::get('GarmentPurchase',[AdminController::class,'GarmentPurchase'])->name('GarmentPurchase');
Route::get('FinishedGoodsInward',[AdminController::class,'FinishedGoodsInward'])->name('FinishedGoodsInward');
Route::get('AllDataMDDashboard',[AdminController::class,'AllDataMDDashboard'])->name('AllDataMDDashboard');
Route::get('AllDataMDDashboard1',[AdminController::class,'AllDataMDDashboard1'])->name('AllDataMDDashboard1');
Route::get('/refreshData',[AdminController::class,'refreshData'])->name('refreshData');
Route::get('DumpFGStockReport',[AdminController::class,'DumpFGStockReport'])->name('DumpFGStockReport');
Route::get('DumpFGStockReport1',[AdminController::class,'DumpFGStockReport1'])->name('DumpFGStockReport1');
Route::get('TempLastRecord',[AdminController::class,'TempLastRecord'])->name('TempLastRecord');
Route::get('DeleteTempLastRecord',[AdminController::class,'DeleteTempLastRecord'])->name('DeleteTempLastRecord');
Route::get('TempFGStockReport',[AdminController::class,'TempFGStockReport'])->name('TempFGStockReport');
Route::get('GetHRMSEmpData',[AdminController::class,'GetHRMSEmpData'])->name('GetHRMSEmpData');


//Year Ending Forms
Route::get('/copy-records', [CopyController::class, 'showForm'])->name('copy.form');
Route::post('/copy-records', [CopyController::class, 'handleForm'])->name('copy.records');
Route::resource('FormTableMaster', FormTableMasterController::class);
//End of Year Ending Forms













Route::resource('DashboardMaster', DashboardController::class);
Route::get('ERPDashboard',[DashboardController::class,'ERPDashboard'])->name('ERPDashboard');
Route::get('loadERPInventoryData',[AdminController::class,'loadERPInventoryData'])->name('loadERPInventoryData');
Route::get('loadERPInventoryData1',[AdminController::class,'loadERPInventoryData1'])->name('loadERPInventoryData1');
Route::get('loadERPProductionData',[AdminController::class,'loadERPProductionData'])->name('loadERPProductionData');
Route::get('GetTotalOrderBookingSummary',[AdminController::class,'GetTotalOrderBookingSummary'])->name('GetTotalOrderBookingSummary'); 

Route::resource('/Country', CountryController::class); 
Route::resource('State', StateController::class);
Route::resource('City', CityController::class);
Route::resource('Line', LineController::class);
Route::resource('MainStyle', MainStyleController::class);
Route::get('changeMainStyleCategoryStatus',[MainStyleController::class,'changeMainStyleCategoryStatus'])->name('changeMainStyleCategoryStatus');
Route::resource('SubStyle', SubStyleController::class);
Route::get('changeSubStyleCategoryStatus',[SubStyleController::class,'changeSubStyleCategoryStatus'])->name('changeSubStyleCategoryStatus');
Route::get('GetMainStyleImage',[SalesOrderCostingController::class,'GetMainStyleImage'])->name('GetMainStyleImage');
Route::get('/SubStyleList',[SubStyleController::class,'GetSubStyleList'])->name('SubStyleList');
Route::get('/StyleList',[SubStyleController::class,'GetStyleList'])->name('StyleList');
Route::resource('MerchantMaster', MerchantMasterController::class);
Route::get('changeMerchantStatus',[MerchantMasterController::class,'changeMerchantStatus'])->name('changeMerchantStatus');
Route::resource('PDMerchantMaster', PDMerchantMasterController::class);
Route::get('changePDMerchantStatus',[PDMerchantMasterController::class,'changePDMerchantStatus'])->name('changePDMerchantStatus');

Route::resource('PaymentTerms', PaymentTermsController::class);
Route::resource('DeliveryTerms', DeliveryTermsController::class);
Route::resource('ShipmentMode', ShipmentModeController::class);
Route::resource('Process', ProcessController::class);
Route::resource('Warehouse', WarehouseController::class);
Route::resource('OrderGroup', OrderGroupController::class);
Route::resource('Currency', CurrencyController::class);
Route::resource('MachineType', MachineTypeController::class);
Route::resource('FabricDefect', FabricDefectController::class);


Route::get('UnderMaintance',[PermissionController::class,'UnderMaintance'])->name('UnderMaintance');
Route::get('UserManagementReport',[PermissionController::class,'UserManagementReport'])->name('UserManagementReport');
Route::resource('Form', UserManagementController::class);
Route::get('CheckFormSequence',[UserManagementController::class,'CheckFormSequence'])->name('CheckFormSequence');
Route::resource('User_Management', PermissionController::class);
Route::resource('District', DistrictController::class);
Route::resource('Taluka', TalukaController::class);
Route::resource('Category', CategoryContoller::class);

Route::resource('Rack', RackController::class);

Route::resource('Item', ItemMasterController::class);

Route::resource('HSN', HSNMasterController::class);

Route::get('list/{id}',[ItemMasterController::class,'activeDeactiveList']);

Route::post('itemimport',[ItemMasterController::class,'itemimport'])->name('itemimport');
Route::get('/ClassList',[ItemMasterController::class,'GetClassList'])->name('ClassList');


Route::get('itemexist',[ItemMasterController::class,'itemexist'])->name('itemexist');
Route::get('GetItemUnits',[ItemMasterController::class,'GetItemUnits'])->name('GetItemUnits');

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
Route::resource('Position', PositionController::class);
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
Route::resource('StyleNo', StyleNoController::class);
Route::resource('Commission', CommissionController::class);

Route::post('uploadFileForFG',[FinishedGoodController::class,'uploadFileForFG'])->name('uploadFileForFG');
Route::delete('deleteFGImage',[FinishedGoodController::class,'deleteFGImage'])->name('deleteFGImage');
Route::get('/GetFGInOutStockReportForm',[FinishedGoodController::class,'GetFGInOutStockReportForm'])->name('GetFGInOutStockReportForm');
Route::get('/FGInOutStockReport',[FinishedGoodController::class,'FGInOutStockReport'])->name('FGInOutStockReport');
Route::get('/changeFGCategoryStatus',[FinishedGoodController::class,'changeFGCategoryStatus'])->name('changeFGCategoryStatus');

Route::post('importcolor',[ColorController::class,'importcolor'])->name('importcolor');

Route::get('changeColorStatus',[ColorController::class,'changeColorStatus'])->name('changeColorStatus');

Route::resource('Classification', ClassificationController::class);
Route::resource('Size', SizeController::class);
Route::get('changeSizeStatus',[SizeController::class,'changeSizeStatus'])->name('changeSizeStatus');

Route::resource('Location', LocationController::class);

Route::resource('PurchaseOrder', PurchaseOrderController::class);

Route::resource('TrimsInward', TrimsInwardController::class);

Route::get('/getPoForTrims',[TrimsInwardController::class,'getPoForTrims'])->name('getPoForTrims');
Route::get('/test',[TrimsInwardController::class,'test'])->name('test');
Route::get('checkPOIsExist',[TrimsInwardController::class,'checkPOIsExist'])->name('checkPOIsExist');
Route::get('TrimsGRNPrint/{id}',[TrimsInwardController::class,'TrimsGRNPrint']);
Route::get('/GetTrimCodeWiseData',[TrimsInwardController::class,'GetTrimCodeWiseData'])->name('GetTrimCodeWiseData');
Route::get('/GetTrimCodeWiseStockData',[TrimsInwardController::class,'GetTrimCodeWiseStockData'])->name('GetTrimCodeWiseStockData');

Route::get('/DemoCreate',[TrimsInwardController::class,'DemoCreate'])->name('DemoCreate'); 
Route::get('/GetItemWorkOrderPucharseOrder',[TrimsInwardController::class,'GetItemWorkOrderPucharseOrder'])->name('GetItemWorkOrderPucharseOrder'); 

Route::get('GetTrimsVendorName',[TrimsInwardController::class,'GetTrimsVendorName'])->name('GetTrimsVendorName');

Route::get('/getPoMasterDetailTrims',[TrimsInwardController::class,'getPoMasterDetailTrims'])->name('getPoMasterDetailTrims');
Route::get('/GetTrimsGRNReport',[TrimsInwardController::class,'GetTrimsGRNReport'])->name('GetTrimsGRNReport');
Route::get('/TrimsGRNReportPrint',[TrimsInwardController::class,'TrimsGRNReportPrint'])->name('TrimsGRNReportPrint');
Route::get('/GetOnPageTrimStock',[TrimsInwardController::class,'GetOnPageTrimStock'])->name('GetOnPageTrimStock');

Route::get('/TrimsInventoryAgingReport',[TrimsInwardController::class,'TrimsInventoryAgingReport'])->name('TrimsInventoryAgingReport');
Route::get('/loadTrimsInventoryAgingReport',[TrimsInwardController::class,'loadTrimsInventoryAgingReport'])->name('loadTrimsInventoryAgingReport');
Route::get('/SyncTrimsStock',[TrimsInwardController::class,'SyncTrimsStock'])->name('SyncTrimsStock');

Route::get('/TrimsInwardData',[TrimsInwardController::class,'GetTrimsInwardList'])->name('TrimsInwardData');
Route::get('/GetComparePOInwardList',[TrimsInwardController::class,'GetComparePOInwardList'])->name('GetComparePOInwardList');
Route::get('/loadDateWiseTrimStockData',[TrimsInwardController::class,'loadDateWiseTrimStockData'])->name('loadDateWiseTrimStockData');
Route::get('/TrimsStocks1',[TrimsInwardController::class,'TrimsStocks1'])->name('TrimsStocks1');
Route::get('/LoadTrimsStockDataTrialCloned',[TrimsInwardController::class,'LoadTrimsStockDataTrialCloned'])->name('LoadTrimsStockDataTrialCloned');
Route::get('/GetTrimsInOutStockReportForm',[TrimsInwardController::class,'GetTrimsInOutStockReportForm'])->name('GetTrimsInOutStockReportForm');
Route::get('/TrimsInOutStockReport',[TrimsInwardController::class,'TrimsInOutStockReport'])->name('TrimsInOutStockReport');
Route::get('/TrimsInwardShowAll',[TrimsInwardController::class,'TrimsInwardShowAll'])->name('TrimsInwardShowAll');
Route::get('/getTrimsPODetails',[TrimsInwardController::class,'getTrimsPODetails'])->name('getTrimsPODetails');
Route::get('/rptTrimsAssocation',[StockAssociationController::class,'rptTrimsAssocation'])->name('rptTrimsAssocation');
Route::get('/LoadTrimsAssocation',[TrimsOutwardController::class,'LoadTrimsAssocation'])->name('LoadTrimsAssocation');
Route::get('get_associated_stock',[TrimsOutwardController::class,'get_associated_stock'])->name('get_associated_stock');
Route::get('get_associated_stock_packing',[TrimsOutwardController::class,'get_associated_stock_packing'])->name('get_associated_stock_packing');
Route::get('GetStockDetailPopupForTrims',[TrimsOutwardController::class,'GetStockDetailPopupForTrims'])->name('GetStockDetailPopupForTrims');
Route::get('/DumpTrimsStockAssocation',[StockAssociationController::class,'DumpTrimsStockAssocation'])->name('DumpTrimsStockAssocation');
Route::post('/StorePopupData',[StockAssociationController::class,'StorePopupData'])->name('StorePopupData');
Route::get('get_associated_stock_sample',[TrimsOutwardController::class,'get_associated_stock_sample'])->name('get_associated_stock_sample');
Route::get('getvendortablenewTrial',[TrimsOutwardController::class,'getvendortablenewTrial'])->name('getvendortablenewTrial');
 
Route::get('POApprovalList',[PurchaseOrderController::class,'show'])->name('POApprovalList');
Route::get('getItemListFromPO',[PurchaseOrderController::class,'getItemListFromPO'])->name('getItemListFromPO');
Route::get('UpdatePurchaseOrderStatus',[PurchaseOrderController::class,'UpdatePurchaseOrderStatus'])->name('UpdatePurchaseOrderStatus');
Route::get('GetAllTradersFromLedger',[PurchaseOrderController::class,'GetAllTradersFromLedger'])->name('GetAllTradersFromLedger');


Route::get('GetPOList',[PurchaseOrderController::class,'GetPOList'])->name('GetPOList');
Route::get('getBoMDetail',[PurchaseOrderController::class,'getBoMDetail'])->name('getBoMDetail');
Route::get('getBoMDetailDemoCreate',[PurchaseOrderController::class,'getBoMDetailDemoCreate'])->name('getBoMDetailDemoCreate');
Route::get('getClassLists',[PurchaseOrderController::class,'getClassLists'])->name('getClassLists');
Route::get('getItemCodeList',[PurchaseOrderController::class,'getItemCodeList'])->name('getItemCodeList');

Route::get('PODisApprovalList',[PurchaseOrderController::class,'Disapprovedshow'])->name('PODisApprovalList');
Route::get('PartyDetail',[PurchaseOrderController::class,'GetPartyDetails'])->name('PartyDetail');
Route::get('GetPartyDetailsPurchase',[PurchaseOrderController::class,'GetPartyDetailsPurchase'])->name('GetPartyDetailsPurchase');
Route::get('GetAllTradersFromPurchase',[PurchaseOrderController::class,'GetAllTradersFromPurchase'])->name('GetAllTradersFromPurchase');
Route::get('GetBuyerFromBOM',[PurchaseOrderController::class,'GetBuyerFromBOM'])->name('GetBuyerFromBOM');

Route::resource('Brand', BrandController::class);
Route::resource('Season', SeasonController::class);
Route::resource('JobStatus', JobStatusController::class);
Route::resource('BuyerJobCard', BuyerJobCardController::class);
Route::resource('FabricInward', FabricInwardController::class);

Route::get('/PrintBarcode',[FabricInwardController::class,'PrintFabricBarcode'])->name('PrintBarcode');

Route::get('/FabricGRNData',[FabricInwardController::class,'FabricGRNData'])->name('FabricGRNData');

Route::get('/FabricGRNDataMD/{id}',[FabricInwardController::class,'FabricGRNDataMD'])->name('FabricGRNDataMD');

Route::get('/FabricStockData',[FabricInwardController::class,'FabricStockData'])->name('FabricStockData');
Route::get('/FabricStockDataMD/{id}/{id1}',[FabricInwardController::class,'FabricStockDataMD'])->name('FabricStockDataMD');
Route::get('/FabricStockSummaryData',[FabricInwardController::class,'FabricStockSummaryData'])->name('FabricStockSummaryData');
Route::get('/FabricPOVsGRNDashboard',[FabricInwardController::class,'FabricPOVsGRNDashboard'])->name('FabricPOVsGRNDashboard');
Route::get('/FabricStockData1',[FabricInwardController::class,'FabricStockData1'])->name('FabricStockData1');
Route::get('/FabricStocks',[FabricInwardController::class,'FabricStocks'])->name('FabricStocks');
Route::get('/FabricStockDataTrial',[FabricInwardController::class,'FabricStockDataTrial'])->name('FabricStockDataTrial');
Route::get('/FabricStockDataTrialCloned',[FabricInwardController::class,'FabricStockDataTrialCloned'])->name('FabricStockDataTrialCloned');
Route::get('/LoadFabricStockDataTrialCloned',[FabricInwardController::class,'LoadFabricStockDataTrialCloned'])->name('LoadFabricStockDataTrialCloned');
Route::get('/LoadFabricStockDataTrialCloned1',[FabricInwardController::class,'LoadFabricStockDataTrialCloned1'])->name('LoadFabricStockDataTrialCloned1');
Route::get('/FabricStockDataTrialCloned1',[FabricInwardController::class,'FabricStockDataTrialCloned1'])->name('FabricStockDataTrialCloned1');

Route::get('/LoadFabricStockDataTrialCloned2',[FabricInwardController::class,'LoadFabricStockDataTrialCloned2'])->name('LoadFabricStockDataTrialCloned2');
Route::get('/RefreshDumpData',[FabricInwardController::class,'RefreshDumpData'])->name('RefreshDumpData');
Route::get('/FabricStocks1',[FabricInwardController::class,'FabricStocks1'])->name('FabricStocks1');
Route::get('/UpdateFoutDumpData',[FabricInwardController::class,'UpdateFoutDumpData'])->name('UpdateFoutDumpData');
Route::get('/FabricInwardShowAll',[FabricInwardController::class,'FabricInwardShowAll'])->name('FabricInwardShowAll');

Route::get('RunCronJob',[FabricInwardController::class,'RunCronJob'])->name('RunCronJob');
Route::get('/TrimsGRNData',[TrimsInwardController::class,'TrimsGRNData'])->name('TrimsGRNData');
Route::get('/TrimsGRNDataMD/{id}',[TrimsInwardController::class,'TrimsGRNDataMD'])->name('TrimsGRNDataMD');
Route::get('/TrimsStockData',[TrimsInwardController::class,'TrimsStockData'])->name('TrimsStockData');
Route::get('/TrimsStockDataMD/{id}/{id1}',[TrimsInwardController::class,'TrimsStockDataMD'])->name('TrimsStockDataMD');
Route::get('/TrimsPOVsGRNDashboard',[TrimsInwardController::class,'TrimsPOVsGRNDashboard'])->name('TrimsPOVsGRNDashboard');
Route::get('/TrimsStockData1',[TrimsInwardController::class,'TrimsStockData1'])->name('TrimsStockData1');
Route::get('/trimStocks',[TrimsInwardController::class,'trimStocks'])->name('trimStocks');
Route::get('/loadDumpTrimStockData',[TrimsInwardController::class,'loadDumpTrimStockData'])->name('loadDumpTrimStockData');
Route::get('/TrimsStockDataTrial',[TrimsInwardController::class,'TrimsStockDataTrial'])->name('TrimsStockDataTrial');
Route::get('/TrimsStockDataTrialCloned',[TrimsInwardController::class,'TrimsStockDataTrialCloned'])->name('TrimsStockDataTrialCloned');
Route::get('/UpdateFoutDumpData',[TrimsInwardController::class,'UpdateFoutDumpData'])->name('UpdateFoutDumpData');

Route::get('/LoadTrimsStockDataTrialCloned2',[TrimsInwardController::class,'LoadTrimsStockDataTrialCloned2'])->name('LoadTrimsStockDataTrialCloned2');
Route::get('/TrimsStockDataTrialCloned1',[TrimsInwardController::class,'TrimsStockDataTrialCloned1'])->name('TrimsStockDataTrialCloned1');

Route::get('stockAllocate',[TrimsInwardController::class,'stockAllocate'])->name('stockAllocate');
Route::get('RunCronTrimJob',[TrimsInwardController::class,'RunCronTrimJob'])->name('RunCronTrimJob');

Route::get('/getPo',[FabricInwardController::class,'getPo'])->name('getPo');

Route::get('/getPoMasterDetail',[FabricInwardController::class,'getPoMasterDetail'])->name('getPoMasterDetail');
Route::get('loadDumpFabricStockData',[FabricInwardController::class,'loadDumpFabricStockData'])->name('loadDumpFabricStockData');


Route::get('PODetail',[FabricInwardController::class,'getPODetails'])->name('PODetail');
Route::get('ItemRateFromPO',[FabricInwardController::class,'getItemRateFromPO'])->name('ItemRateFromPO');
Route::get('CheckFabricEntryInChecking',[FabricInwardController::class,'CheckFabricEntryInChecking'])->name('CheckFabricEntryInChecking');
Route::get('DeleteDataFromDump',[FabricInwardController::class,'DeleteDataFromDump'])->name('DeleteDataFromDump');

// Start SalesOrderCosting-------------------//
Route::resource('SalesOrderCosting', SalesOrderCostingController::class);

 
Route::get('/GetCostingData/{id}',[SalesOrderCostingController::class,'GetCostingData']);
Route::get('/GetCostingSalesOrderWiseData/{id}',[SalesOrderCostingController::class,'GetCostingSalesOrderWiseData']);
Route::get('/FiltersPage',[SalesOrderCostingController::class,'FiltersPage']);


Route::get('/RepeatSalesOrderCosting/{id}',[SalesOrderCostingController::class,'RepeatSalesOrderCosting'])->name('RepeatSalesOrderCosting');
Route::post('/RepeatSalesOrderCostSave',[SalesOrderCostingController::class,'repeatstore'])->name('RepeatSalesOrderCostSave');

Route::get('/checkCostingStatus',[SalesOrderCostingController::class,'checkCostingStatus'])->name('checkCostingStatus');
Route::get('/SalesOrderDetails',[SalesOrderCostingController::class,'getSalesOrderDetails'])->name('SalesOrderDetails');
Route::get('/SalesOrderCostingStatus',[SalesOrderCostingController::class,'SalesOrderCostingStatus'])->name('SalesOrderCostingStatus');
Route::get('/ItemDetails',[SalesOrderCostingController::class,'GetItemData'])->name('ItemDetails');

Route::get('/SalesCostingProfitSheet',[SalesOrderCostingController::class,'costingProfitSheet'])->name('SalesCostingProfitSheet');
Route::get('/SalesCostingProfitSheet2',[SalesOrderCostingController::class,'costingProfitSheet2'])->name('SalesCostingProfitSheet2');

Route::get('/GetCostingProfitByFilter',[SalesOrderCostingController::class,'GetCostingProfitByFilter'])->name('GetCostingProfitByFilter');
Route::get('/costingProfitSheet3',[SalesOrderCostingController::class,'costingProfitSheet3'])->name('costingProfitSheet3');

Route::resource('BOM', BOMController::class);

Route::get('BUDGETPrint/{id}',[BOMController::class,'show']);

Route::get('BOMPrint/{id}',[BOMController::class,'bomPrint']);


Route::get('BOMMasterEditTrial/{id}',[BOMController::class,'BOMMasterEditTrial'])->name('BOMMasterEditTrial');
Route::get('BOMMasterTrial',[BOMController::class,'BOMMasterTrial'])->name('BOMMasterTrial');
Route::put('/BOMMasterEditTrialUpdate', [BOMController::class, 'BOMMasterEditTrialUpdate'])->name('BOMMasterEditTrialUpdate');
Route::get('/GetOrderQty',[BOMController::class,'GetOrderQty'])->name('GetOrderQty');
Route::get('/GetMultipleBOMData',[BOMController::class,'GetMultipleBOMData'])->name('GetMultipleBOMData');
Route::get('/MultipleBOMData',[BOMController::class,'MultipleBOMData'])->name('MultipleBOMData');



Route::get('/GetSizeList',[BOMController::class,'GetSizeList'])->name('GetSizeList');
Route::get('/GetItemList',[BOMController::class,'GetItemList'])->name('GetItemList');
Route::get('/GetClassItemList',[BOMController::class,'GetClassItemList'])->name('GetClassItemList');
Route::get('/GetBOMFabricFromSalesOrder',[BOMController::class,'GetBOMFabricFromSalesOrder'])->name('GetBOMFabricFromSalesOrder');
Route::get('/GetBOMFabricRepeat',[BOMController::class,'GetBOMFabricRepeat'])->name('GetBOMFabricRepeat');
Route::get('/GetBOMSewingRepeat',[BOMController::class,'GetBOMSewingRepeat'])->name('GetBOMSewingRepeat');
Route::get('/GetBOMPackingRepeat',[BOMController::class,'GetBOMPackingRepeat'])->name('GetBOMPackingRepeat');
Route::post('/BOMRepeatStore',[BOMController::class,'BOMRepeatStore'])->name('BOMRepeatStore');

Route::get('/GetClassList',[BOMController::class,'GetClassList'])->name('GetClassList');
Route::get('/GetSewingClassList',[BOMController::class,'GetSewingClassList'])->name('GetSewingClassList');
Route::get('/GetPackingClassList',[BOMController::class,'GetPackingClassList'])->name('GetPackingClassList');
 
Route::get('/GetItemColorList',[BOMController::class,'GetItemColorList'])->name('GetItemColorList');
Route::get('/GetSewingTrimItemList',[BOMController::class,'GetSewingTrimItemList'])->name('GetSewingTrimItemList');
Route::get('/GetPackingTrimItemList',[BOMController::class,'GetPackingTrimItemList'])->name('GetPackingTrimItemList');



Route::get('/GetColorList',[BOMController::class,'GetColorList'])->name('GetColorList');
Route::get('/FabricWiseSalesOrderCosting',[BOMController::class,'GetFabricWiseSalesOrderCosting'])->name('FabricWiseSalesOrderCosting');
Route::get('/PackingWiseSalesOrderCosting',[BOMController::class,'GetPackingWiseSalesOrderCosting'])->name('PackingWiseSalesOrderCosting');
Route::get('/ItemWiseSalesOrderCosting',[BOMController::class,'GetItemWiseSalesOrderCosting'])->name('ItemWiseSalesOrderCosting');
Route::get('/TrimFabricWiseSalesOrderCosting',[BOMController::class,'GetTrimFabricWiseSalesOrderCosting'])->name('TrimFabricWiseSalesOrderCosting');
Route::get('GetColorWiseBOMDetail',[BOMController::class,'GetColorWiseBOMDetail'])->name('GetColorWiseBOMDetail');
Route::get('GetBOMWiseColorList',[BOMController::class,'GetBOMWiseColorList'])->name('GetBOMWiseColorList');
Route::post('rptColorWiseBOMDetail',[BOMController::class,'rptColorWiseBOMDetail'])->name('rptColorWiseBOMDetail');
Route::get('PartialDispatchCostingReport',[BOMController::class,'PartialDispatchCostingReport'])->name('PartialDispatchCostingReport');

Route::get('BOMMasterRepeat/{id}',[BOMController::class,'BOMMasterRepeat'])->name('BOMMasterRepeat');


// Vendor Work Order Start ----------------------------
Route::resource('VendorWorkOrder', VendorWorkOrderController::class);

Route::get('VWPrint/{id}',[VendorWorkOrderController::class,'VWPrint']);

Route::get('/W_GetOrderQty',[VendorWorkOrderController::class,'W_GetOrderQty'])->name('W_GetOrderQty');
Route::get('/W_GetSizeList',[VendorWorkOrderController::class,'W_GetSizeList'])->name('W_GetSizeList');
Route::get('/W_GetItemList',[VendorWorkOrderController::class,'W_GetItemList'])->name('W_GetItemList');
Route::get('/W_GetClassList',[VendorWorkOrderController::class,'W_GetClassList'])->name('W_GetClassList');
Route::get('/W_GetColorList',[VendorWorkOrderController::class,'W_GetColorList'])->name('W_GetColorList');
Route::get('/GetFabricConsumption',[VendorWorkOrderController::class,'GetFabricConsumption'])->name('GetFabricConsumption');
Route::get('/GetSewingConsumption',[VendorWorkOrderController::class,'GetSewingConsumption'])->name('GetSewingConsumption');
Route::get('/GetPackingConsumption',[VendorWorkOrderController::class,'GetPackingConsumption'])->name('GetPackingConsumption');
Route::get('/GetTrimFabricConsumption',[VendorWorkOrderController::class,'GetTrimFabricConsumption'])->name('GetTrimFabricConsumption');
Route::get('/VendorAllWorkOrders',[VendorWorkOrderController::class,'getVendorAllWorkOrders'])->name('VendorAllWorkOrders');
Route::get('JobWorkGarmentContractPrint/{id}',[VendorWorkOrderController::class,'JobWorkGarmentContractPrint'])->name('JobWorkGarmentContractPrint');
Route::get('pushDataInTable',[VendorWorkOrderController::class,'pushDataInTable'])->name('pushDataInTable');
Route::post('WorkOrderClose',[VendorWorkOrderController::class,'WorkOrderClose'])->name('WorkOrderClose');
Route::get('GetSewingData',[VendorWorkOrderController::class,'GetSewingData'])->name('GetSewingData');
Route::get('TestVendorWorkOrder/{id}',[VendorWorkOrderController::class,'TestVendorWorkOrder'])->name('TestVendorWorkOrder');
Route::get('GetWorkOrderSewingCreateConsumption',[VendorWorkOrderController::class,'GetWorkOrderSewingCreateConsumption'])->name('GetWorkOrderSewingCreateConsumption');
Route::get('GetWorkOrderSewingCreateConsumption1',[VendorWorkOrderController::class,'GetWorkOrderSewingCreateConsumption1'])->name('GetWorkOrderSewingCreateConsumption1');
Route::get('VWOrder_GetOrderQty',[VendorWorkOrderController::class,'VWOrder_GetOrderQty'])->name('VWOrder_GetOrderQty');

// Vendor Work Order End ----------------------------

//Vendor Purchase Order Start -----------------
Route::resource('VendorPurchaseOrder', VendorPurchaseOrderController::class);
Route::get('ob_pending_list',[VendorPurchaseOrderController::class,'ob_pending_list'])->name('ob_pending_list');

Route::get('VPPrint/{id}',[VendorPurchaseOrderController::class,'VPPrint']);
Route::get('GetVendorName',[VendorPurchaseOrderController::class,'GetVendorName'])->name('GetVendorName');
Route::get('/VPO_GetOrderQty',[VendorPurchaseOrderController::class,'VPO_GetOrderQty'])->name('VPO_GetOrderQty');
Route::get('/VPO_GetSizeList',[VendorPurchaseOrderController::class,'VPO_GetSizeList'])->name('VPO_GetSizeList');
Route::get('/VPO_GetItemList',[VendorPurchaseOrderController::class,'VPO_GetItemList'])->name('VPO_GetItemList');
Route::get('/VPO_GetClassList',[VendorPurchaseOrderController::class,'VPO_GetClassList'])->name('VPO_GetClassList');
Route::get('/VPO_GetColorList',[VendorPurchaseOrderController::class,'VPO_GetColorList'])->name('VPO_GetColorList');
Route::get('/GetFabricConsumptionPO',[VendorPurchaseOrderController::class,'GetFabricConsumptionPO'])->name('GetFabricConsumptionPO');
Route::get('/GetTrimsConsumptionPO',[VendorPurchaseOrderController::class,'GetTrimsConsumptionPO'])->name('GetTrimsConsumptionPO');
Route::get('/CuttingPOItemList',[VendorPurchaseOrderController::class,'GetCuttingPOItemList'])->name('CuttingPOItemList');
Route::get('/POVsMaterialIssueReport',[VendorPurchaseOrderController::class,'POVsMaterialIssueReport'])->name('POVsMaterialIssueReport');
Route::get('/VendorPurchaseOrderDetails',[VendorPurchaseOrderController::class,'getVendorPurchaseOrderDetails'])->name('VendorPurchaseOrderDetails');
Route::get('/getVendorPO',[VendorPurchaseOrderController::class,'getVendorPO'])->name('getVendorPO');
Route::get('/getVendorAllPO',[VendorPurchaseOrderController::class,'getVendorAllPO'])->name('getVendorAllPO');
Route::get('/GetVPOVsIssueReport',[VendorPurchaseOrderController::class,'GetVPOVsIssueReport'])->name('GetVPOVsIssueReport');
Route::post('PurchaseOrderClose',[VendorPurchaseOrderController::class,'PurchaseOrderClose'])->name('PurchaseOrderClose');
Route::get('VendorPurchaseOrderShowAll',[VendorPurchaseOrderController::class,'VendorPurchaseOrderShowAll'])->name('VendorPurchaseOrderShowAll');

Route::get('/GetPurchasePackingConsumption',[VendorPurchaseOrderController::class,'GetPurchasePackingConsumption'])->name('GetPurchasePackingConsumption');
Route::get('/VendorPurchaseOrderDemo',[FabricInwardController::class,'VendorPurchaseOrderDemo'])->name('VendorPurchaseOrderDemo');
Route::get('/GetPurchasePackingCreateConsumption',[VendorPurchaseOrderController::class,'GetPurchasePackingCreateConsumption'])->name('GetPurchasePackingCreateConsumption');
Route::get('/VendorPurchaseOrderMergedPrint',[VendorPurchaseOrderController::class,'VendorPurchaseOrderMergedPrint'])->name('VendorPurchaseOrderMergedPrint');
//Vendor Purchase Order End -----------------
 
 
// Route::get('/GetCostingData/{id}',[SalesOrderCostingController::class,'GetCostingData']);

// Route::get('/SalesOrderDetails',[SalesOrderCostingController::class,'getSalesOrderDetails'])->name('SalesOrderDetails');
// Route::get('/ItemDetails',[SalesOrderCostingController::class,'GetItemData'])->name('ItemDetails');

Route::resource('SaleTransaction', SaleTransactionMasterController::class);
Route::get('/getSalesOrderData',[SaleTransactionMasterController::class,'getSalesOrderData'])->name('getSalesOrderData');
Route::get('/CartonPackingList',[SaleTransactionMasterController::class,'CartonPackingList'])->name('CartonPackingList');
Route::get('/GetSalesOrderList',[SaleTransactionMasterController::class,'GetSalesOrderList'])->name('GetSalesOrderList');

Route::get('/GetSaleReport',[SaleTransactionMasterController::class,'GetSaleReport'])->name('GetSaleReport');
Route::get('/SaleFilterReport',[SaleTransactionMasterController::class,'SaleFilterReport'])->name('SaleFilterReport'); 
Route::get('/SaleFilterReportMD/{id}',[SaleTransactionMasterController::class,'SaleFilterReportMD'])->name('SaleFilterReportMD'); 
Route::get('GetSalesInvoiceCode',[SaleTransactionMasterController::class,'GetSalesInvoiceCode'])->name('GetSalesInvoiceCode');
Route::get('saleTransactionShowAll',[SaleTransactionMasterController::class,'saleTransactionShowAll'])->name('saleTransactionShowAll');
Route::get('SalesTransactionPrint',[SaleTransactionMasterController::class,'SalesTransactionPrint'])->name('SalesTransactionPrint');
Route::get('GetKGDPLSales',[SaleTransactionMasterController::class,'GetKGDPLSales'])->name('GetKGDPLSales');
Route::get('GetSalesSummaryReport',[SaleTransactionMasterController::class,'GetSalesSummaryReport'])->name('GetSalesSummaryReport');
Route::get('GetInvoiceWiseSalesOrderList',[SaleTransactionMasterController::class,'GetInvoiceWiseSalesOrderList'])->name('GetInvoiceWiseSalesOrderList');
Route::get('/sampleGiftOutwardReport',[SaleTransactionMasterController::class,'sampleGiftOutwardReport'])->name('sampleGiftOutwardReport');  
Route::get('/EInvoice/{id}',[SaleTransactionMasterController::class,'EInvoice'])->name('EInvoice');    
Route::get('/EWayBill/{id}',[SaleTransactionMasterController::class,'EWayBill'])->name('EWayBill');  
Route::get('/EInvoicePreview/{id}',[SaleTransactionMasterController::class,'EInvoicePreview'])->name('EInvoicePreview');      
Route::post('/SaleInvoiceQRCode',[SaleTransactionMasterController::class,'SaleInvoiceQRCode'])->name('SaleInvoiceQRCode');  
Route::post('/SaleEwayBillQRCode',[SaleTransactionMasterController::class,'SaleEwayBillQRCode'])->name('SaleEwayBillQRCode');  
Route::get('GetPartyDetailsSale',[SaleTransactionMasterController::class,'GetPartyDetailsSale'])->name('GetPartyDetailsSale'); 
Route::get('ReadDistanceFromPincode',[SaleTransactionMasterController::class,'ReadDistanceFromPincode'])->name('ReadDistanceFromPincode');    

Route::post('/export-tally-xml', [SaleTransactionMasterController::class, 'export'])->name('export.tally.xml');
// End SalesOrderCosting-------------------//

Route::resource('FabricChecking', FabricCheckingController::class);
Route::get('/InwardList',[FabricCheckingController::class,'getDetails'])->name('InwardList');
Route::get('/InwardMasterList',[FabricCheckingController::class,'getMasterdata'])->name('InwardMasterList');

Route::get('/PrintBarcode',[FabricCheckingController::class,'PrintFabricBarcode'])->name('PrintBarcode');

  Route::get('ItemMinMaxFromPO',[FabricInwardController::class,'getItemMinMaxFromPO'])->name('ItemMinMaxFromPO');
  
Route::get('/FabricCheckingDashboard',[FabricCheckingController::class,'FabricCheckingDashboard'])->name('FabricCheckingDashboard');
Route::get('/FabricCheckingRejectDashboard',[FabricCheckingController::class,'FabricCheckingRejectDashboard'])->name('FabricCheckingRejectDashboard');
Route::get('/FabricCheckingShowAll',[FabricCheckingController::class,'FabricCheckingShowAll'])->name('FabricCheckingShowAll');


Route::resource('FabricSummaryGRN', FabricSummaryGRNController::class);
Route::get('/GetPOItemList',[FabricSummaryGRNController::class,'GetPOItemList'])->name('GetPOItemList');
Route::get('/GetPOColorList',[FabricSummaryGRNController::class,'GetPOColorList'])->name('GetPOColorList');
Route::get('/GetPoCodeFromChk',[FabricSummaryGRNController::class,'GetPoCodeFromChk'])->name('GetPoCodeFromChk');
    
Route::resource('BuyerPurchaseOrder', BuyerPurchaseOrderController::class);
Route::get('SaleOrderPrint/{id}',[BuyerPurchaseOrderController::class,'show']);
Route::get('/GetAddress',[BuyerPurchaseOrderController::class,'getAddress'])->name('GetAddress');
Route::get('/TaxList',[BuyerPurchaseOrderController::class,'GetTaxList'])->name('TaxList');
Route::get('/SizeDetailList',[BuyerPurchaseOrderController::class,'GetSizeDetailList'])->name('SizeDetailList');
Route::get('/SalesOrderOpen',[BuyerPurchaseOrderController::class,'SalesOrderOpen'])->name('SalesOrderOpen');
Route::get('/SalesOrderSample',[BuyerPurchaseOrderController::class,'SalesOrderSample'])->name('SalesOrderSample');
Route::get('/CheckOpenWorkProcessOrders',[BuyerPurchaseOrderController::class,'CheckOpenWorkProcessOrders'])->name('CheckOpenWorkProcessOrders');
Route::get('/EditDetailData',[BuyerPurchaseOrderController::class,'EditDetailData'])->name('EditDetailData');
Route::get('/dumpOCRSummaryData',[BuyerPurchaseOrderController::class,'dumpOCRSummaryData'])->name('dumpOCRSummaryData');
Route::get('/loadOCRReport',[BuyerPurchaseOrderController::class,'loadOCRReport'])->name('loadOCRReport');
Route::get('/FabricTrimsPOFollowUpReport',[BuyerPurchaseOrderController::class,'FabricTrimsPOFollowUpReport'])->name('FabricTrimsPOFollowUpReport');
Route::get('/UnitWiseDPRReport',[BuyerPurchaseOrderController::class,'UnitWiseDPRReport'])->name('UnitWiseDPRReport');
Route::get('/LoadUnitWiseDPRReport',[BuyerPurchaseOrderController::class,'LoadUnitWiseDPRReport'])->name('LoadUnitWiseDPRReport');
Route::get('/GetDestinationForSalesOrderList',[BuyerPurchaseOrderController::class,'GetDestinationForSalesOrderList'])->name('GetDestinationForSalesOrderList');


Route::get('/SalesOrderClosed',[BuyerPurchaseOrderController::class,'SalesOrderClosed'])->name('SalesOrderClosed');
Route::get('/SalesOrderCancelled',[BuyerPurchaseOrderController::class,'SalesOrderCancelled'])->name('SalesOrderCancelled');
Route::get('/OpenSalesOrderDashboard',[BuyerPurchaseOrderController::class,'OpenSalesOrderDashboard'])->name('OpenSalesOrderDashboard');
Route::get('/BuyerOpenSalesOrderDashboard',[BuyerPurchaseOrderController::class,'BuyerOpenSalesOrderDashboard'])->name('BuyerOpenSalesOrderDashboard');
Route::get('/OpenSalesOrderDetailDashboard',[BuyerPurchaseOrderController::class,'OpenSalesOrderDetailDashboard'])->name('OpenSalesOrderDetailDashboard');
Route::get('/OpenSalesOrderDetailDashboardColorWise',[BuyerPurchaseOrderController::class,'OpenSalesOrderDetailDashboardColorWise'])->name('OpenSalesOrderDetailDashboardColorWise');
Route::get('/OpenSalesOrderDetailDashboard1',[BuyerPurchaseOrderController::class,'OpenSalesOrderDetailDashboard1'])->name('OpenSalesOrderDetailDashboard1');
Route::get('/OpenSalesOrderDetailDashboardTrial',[BuyerPurchaseOrderController::class,'OpenSalesOrderDetailDashboardTrial'])->name('OpenSalesOrderDetailDashboardTrial');
Route::get('/OpenSalesOrderDetailMDDashboard/{id}',[BuyerPurchaseOrderController::class,'OpenSalesOrderDetailMDDashboard'])->name('OpenSalesOrderDetailMDDashboard');
Route::get('/TotalSalesOrderDetailDashboard',[BuyerPurchaseOrderController::class,'TotalSalesOrderDetailDashboard'])->name('TotalSalesOrderDetailDashboard');
Route::get('/TotalSalesOrderPendingForOCR/{id}',[BuyerPurchaseOrderController::class,'TotalSalesOrderPendingForOCR'])->name('TotalSalesOrderPendingForOCR');
Route::get('/GetLiveRunningOrderStatus',[BuyerPurchaseOrderController::class,'GetLiveRunningOrderStatus'])->name('GetLiveRunningOrderStatus');
Route::get('/rptLiveRunningOrderStatus',[BuyerPurchaseOrderController::class,'rptLiveRunningOrderStatus'])->name('rptLiveRunningOrderStatus');
Route::get('/GetOpenOrderReport',[BuyerPurchaseOrderController::class,'GetOpenOrderReport'])->name('GetOpenOrderReport');
Route::get('/GetOpenOrderReport1',[BuyerPurchaseOrderController::class,'GetOpenOrderReport1'])->name('GetOpenOrderReport1');
Route::get('/GetOpenOrderReportTrial',[BuyerPurchaseOrderController::class,'GetOpenOrderReportTrial'])->name('GetOpenOrderReportTrial');
Route::get('/GetOpenOrderReportTrial1',[BuyerPurchaseOrderController::class,'GetOpenOrderReportTrial1'])->name('GetOpenOrderReportTrial1');
Route::get('/GetOpenOrderSummaryReport',[BuyerPurchaseOrderController::class,'GetOpenOrderSummaryReport'])->name('GetOpenOrderSummaryReport');
Route::get('/OrderBookingReport',[BuyerPurchaseOrderController::class,'OrderBookingReport'])->name('OrderBookingReport');
 
Route::get('/TotalSalesOrderDetailMDDashboard/{id}',[BuyerPurchaseOrderController::class,'TotalSalesOrderDetailMDDashboard'])->name('TotalSalesOrderDetailMDDashboard');
Route::get('/OpenSalesOrderMonthDetailDashboard',[BuyerPurchaseOrderController::class,'OpenSalesOrderMonthDetailDashboard'])->name('OpenSalesOrderMonthDetailDashboard');
Route::get('/TotalSalesOrderDetailDashboardFilter',[BuyerPurchaseOrderController::class,'TotalSalesOrderDetailDashboardFilter'])->name('TotalSalesOrderDetailDashboardFilter');
Route::get('/MaterialIssueReport',[BuyerPurchaseOrderController::class,'MaterialIssueReport'])->name('MaterialIssueReport');
Route::get('/LoadMaterialIssueReport',[BuyerPurchaseOrderController::class,'LoadMaterialIssueReport'])->name('LoadMaterialIssueReport');


Route::get('/DailyProductionDetailDashboard',[BuyerPurchaseOrderController::class,'DailyProductionDetailDashboard'])->name('DailyProductionDetailDashboard');
Route::get('/ProductionReport1',[BuyerPurchaseOrderController::class,'ProductionReport1'])->name('ProductionReport1');
Route::get('/ProductionReport2',[BuyerPurchaseOrderController::class,'ProductionReport2'])->name('ProductionReport2');
Route::get('/rptProductionDPR',[BuyerPurchaseOrderController::class,'rptProductionDPR'])->name('rptProductionDPR');
Route::get('/OrderProgressDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressDetailDashboard'])->name('OrderProgressDetailDashboard'); 
Route::get('/OrderProgressFinishingDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressFinishingDetailDashboard'])->name('OrderProgressFinishingDetailDashboard');
Route::get('/OrderProgressPackingDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressPackingDetailDashboard'])->name('OrderProgressPackingDetailDashboard');
Route::get('/OrderProgressCuttingDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressCuttingDetailDashboard'])->name('OrderProgressCuttingDetailDashboard');



Route::get('/SalesOrderCostingBOMStatusDashboard',[BuyerPurchaseOrderController::class,'SalesOrderCostingBOMStatusDashboard'])->name('SalesOrderCostingBOMStatusDashboard');
Route::get('/CostingOHPDashboard',[BuyerPurchaseOrderController::class,'CostingOHPDashboard'])->name('CostingOHPDashboard');
Route::get('/DashboardCostingOHPDashboard/{id}/{id1}',[BuyerPurchaseOrderController::class,'DashboardCostingOHPDashboard'])->name('DashboardCostingOHPDashboard');
Route::get('/LoadCostingOHPDashboard',[BuyerPurchaseOrderController::class,'LoadCostingOHPDashboard'])->name('LoadCostingOHPDashboard');
Route::get('/LoadCostingOHPDashboard1',[BuyerPurchaseOrderController::class,'LoadCostingOHPDashboard1'])->name('LoadCostingOHPDashboard1');
Route::get('/ApprovedAutorizedPerson',[BuyerPurchaseOrderController::class,'ApprovedAutorizedPerson'])->name('ApprovedAutorizedPerson');
Route::get('/CostingVSBudgetDashboard',[BuyerPurchaseOrderController::class,'CostingVSBudgetDashboard'])->name('CostingVSBudgetDashboard');
Route::get('/filterCostingOHPDashboardRecords',[BuyerPurchaseOrderController::class,'filterCostingOHPDashboardRecords'])->name('filterCostingOHPDashboardRecords');


Route::get('/GetOCRReport',[BuyerPurchaseOrderController::class,'GetOCRReport'])->name('GetOCRReport');
Route::get('/GetMerchandiseOCRReport',[BuyerPurchaseOrderController::class,'GetMerchandiseOCRReport'])->name('GetMerchandiseOCRReport');
Route::get('/MerchandiseOCRReport',[BuyerPurchaseOrderController::class,'MerchandiseOCRReport'])->name('MerchandiseOCRReport');
Route::get('/GetOCRSummaryReport',[BuyerPurchaseOrderController::class,'GetOCRSummaryReport'])->name('GetOCRSummaryReport');

Route::get('/GetOCRSummaryReport/{id}',[BuyerPurchaseOrderController::class,'GetOCRSummaryReportMD'])->name('GetOCRSummaryReportMD');

Route::get('/GetOCRSummaryReport1',[BuyerPurchaseOrderController::class,'GetOCRSummaryReport1'])->name('GetOCRSummaryReport1');
Route::get('/GetOrderVsShipmentReport',[BuyerPurchaseOrderController::class,'GetOrderVsShipmentReport'])->name('GetOrderVsShipmentReport');
Route::get('/GetOrderVsShipmentReportMD/{id}',[BuyerPurchaseOrderController::class,'GetOrderVsShipmentReportMD'])->name('GetOrderVsShipmentReportMD');

Route::get('/OCRSummaryReport',[BuyerPurchaseOrderController::class,'OCRSummaryReport'])->name('OCRSummaryReport');
Route::get('/OCRSummaryReport1',[BuyerPurchaseOrderController::class,'OCRSummaryReport1'])->name('OCRSummaryReport1');

Route::get('/OCRReport',[BuyerPurchaseOrderController::class,'OCRReport'])->name('OCRReport');

Route::get('/GetOCRSummary',[BuyerPurchaseOrderController::class,'GetOCRSummary'])->name('GetOCRSummary');
Route::get('/rptOCRSummary',[BuyerPurchaseOrderController::class,'rptOCRSummary'])->name('rptOCRSummary');

Route::get('/GetCutPlanReport',[BuyerPurchaseOrderController::class,'GetCutPlanReport'])->name('GetCutPlanReport');
Route::get('/CuttingPOList',[BuyerPurchaseOrderController::class,'CuttingPOList'])->name('CuttingPOList');
Route::get('/CutPlanReport',[BuyerPurchaseOrderController::class,'CutPlanReport'])->name('CutPlanReport');

Route::get('/SeasonList',[BuyerPurchaseOrderController::class,'GetSeasonList'])->name('SeasonList');
Route::get('/BrandList',[BuyerPurchaseOrderController::class,'GetBrandList'])->name('BrandList');
Route::get('/BuyerSalesOrderSizeQtyDashboard',[BuyerPurchaseOrderController::class,'BuyerSalesOrderSizeQtyDashboard'])->name('BuyerSalesOrderSizeQtyDashboard');
Route::get('/GetSalesOrderListFromVendor',[BuyerPurchaseOrderController::class,'GetSalesOrderListFromVendor'])->name('GetSalesOrderListFromVendor');
Route::get('/GetBuyerListFromVendor',[BuyerPurchaseOrderController::class,'GetBuyerListFromVendor'])->name('GetBuyerListFromVendor');
Route::get('/GetMainStyleListFromVendor',[BuyerPurchaseOrderController::class,'GetMainStyleListFromVendor'])->name('GetMainStyleListFromVendor'); 

Route::resource('Task', TaskMasterController::class);
Route::get('/CompletedTask',[TaskMasterController::class,'CompletedTaskList'])->name('CompletedTask');
Route::resource('MaterialOutward', MaterialOutwardController::class);
Route::resource('MaterialInward', MaterialInwardController::class);
Route::get('/DeleteMaterialInwardAttachment',[MaterialInwardController::class,'DeleteMaterialInwardAttachment'])->name('DeleteMaterialInwardAttachment');
Route::get('/MaintenanceSparesGRNReport',[MaterialInwardController::class,'MaintenanceSparesGRNReport'])->name('MaintenanceSparesGRNReport');
Route::get('/MaintenanceSpareIssueReport',[MaterialInwardController::class,'MaintenanceSpareIssueReport'])->name('MaintenanceSpareIssueReport');
Route::get('/MaintenanceSparesStockReport',[MaterialInwardController::class,'MaintenanceSparesStockReport'])->name('MaintenanceSparesStockReport');
Route::get('/SpareItemLedgerReport',[MaterialInwardController::class,'SpareItemLedgerReport'])->name('SpareItemLedgerReport');
Route::get('/SpareItemLedgerList',[MaterialInwardController::class,'SpareItemLedgerList'])->name('SpareItemLedgerList');
Route::get('/GetMachineDetails',[MaterialOutwardController::class,'GetMachineDetails'])->name('GetMachineDetails');
Route::get('/GetItemDescriptionForMachine',[MaterialOutwardController::class,'GetItemDescriptionForMachine'])->name('GetItemDescriptionForMachine');  
Route::get('/GetGRNListFromSpareItemCode',[MaterialOutwardController::class,'GetGRNListFromSpareItemCode'])->name('GetGRNListFromSpareItemCode');  
Route::get('/GetSpareStock',[MaterialOutwardController::class,'GetSpareStock'])->name('GetSpareStock'); 
Route::get('/getMaterialPODetails',[MaterialInwardController::class,'getMaterialPODetails'])->name('getMaterialPODetails'); 
Route::get('/getPoForMaterialInward',[MaterialInwardController::class,'getPoForMaterialInward'])->name('getPoForMaterialInward'); 
Route::get('/GetMateriralInwardCodeWiseData',[MaterialInwardController::class,'GetMateriralInwardCodeWiseData'])->name('GetMateriralInwardCodeWiseData'); 
Route::get('/MaterialGRNPrint/{id}',[MaterialInwardController::class,'MaterialGRNPrint'])->name('MaterialGRNPrint'); 
Route::get('/MaterialInwardShowAll',[MaterialInwardController::class,'MaterialInwardShowAll'])->name('MaterialInwardShowAll'); 
Route::get('/LocationWiseSpareStockReport',[MaterialInwardController::class,'LocationWiseSpareStockReport'])->name('LocationWiseSpareStockReport'); 
Route::get('/GetSpareItemList',[MaterialInwardController::class,'GetSpareItemList'])->name('GetSpareItemList'); 
Route::get('/GetSpareItemUnit',[MaterialInwardController::class,'GetSpareItemUnit'])->name('GetSpareItemUnit'); 

Route::resource('FabricCutting', CuttingMasterController::class);
Route::get('/RatioList',[CuttingMasterController::class,'getRatioDetails'])->name('RatioList');
Route::get('/EndDataList',[CuttingMasterController::class,'getEndDataDetails'])->name('EndDataList');
Route::get('/CheckingFabricList',[CuttingMasterController::class,'getCheckingFabricdata'])->name('CheckingFabricList');
Route::get('/CheckingMasterList',[CuttingMasterController::class,'getCheckingMasterdata'])->name('CheckingMasterList');
Route::get('/FabricCuttingPrint/{id}',[CuttingMasterController::class,'FabricCuttingPrint'])->name('FabricCuttingPrint');

Route::get('/CompletedCutting',[CuttingMasterController::class,'CompletedCutting'])->name('CompletedCutting');

Route::resource('StitchingInhouse', StitchingInhouseMasterController::class);
Route::get('/VendorWorkOrderDetails',[StitchingInhouseMasterController::class,'getVendorWorkOrderDetails'])->name('VendorWorkOrderDetails');
Route::get('/VW_GetOrderQty',[StitchingInhouseMasterController::class,'VW_GetOrderQty'])->name('VW_GetOrderQty');
Route::get('/VW_CutPanelGetOrderQty',[StitchingInhouseMasterController::class,'VW_CutPanelGetOrderQty'])->name('VW_CutPanelGetOrderQty');
Route::get('/StitchingGRNDashboard',[StitchingInhouseMasterController::class,'StitchingGRNDashboard'])->name('StitchingGRNDashboard');
Route::get('/StitchingGRNDashboardMD/{id}/{id1}',[StitchingInhouseMasterController::class,'StitchingGRNDashboardMD'])->name('StitchingGRNDashboardMD');
Route::get('/newSewingReport',[StitchingInhouseMasterController::class,'newSewingReport'])->name('newSewingReport');

Route::get('/SetStitichingQtyForDailyProduction',[DailyProductionEntryController::class,'SetStitichingQtyForDailyProduction'])->name('SetStitichingQtyForDailyProduction');

Route::get('GetMontlyBudgetProductionReport',[StitchingInhouseMasterController::class,'GetMontlyBudgetProductionReport'])->name('GetMontlyBudgetProductionReport');
Route::get('GetProductionSummaryReport',[StitchingInhouseMasterController::class,'GetProductionSummaryReport'])->name('GetProductionSummaryReport');  

Route::get('GetBuyerWiseFOBProductionReport',[StitchingInhouseMasterController::class,'GetBuyerWiseFOBProductionReport'])->name('GetBuyerWiseFOBProductionReport'); 
Route::get('GetBuyerWiseJobWorkProductionReport',[StitchingInhouseMasterController::class,'GetBuyerWiseJobWorkProductionReport'])->name('GetBuyerWiseJobWorkProductionReport'); 
Route::get('GetBuyerWiseStockProductionReport',[StitchingInhouseMasterController::class,'GetBuyerWiseStockProductionReport'])->name('GetBuyerWiseStockProductionReport'); 

Route::get('/GetDailyProductionReport',[StitchingInhouseMasterController::class,'GetDailyProductionReport'])->name('GetDailyProductionReport');
Route::get('/DailyProductionReport',[StitchingInhouseMasterController::class,'DailyProductionReport'])->name('DailyProductionReport');
Route::get('/GetVendorStatusReport',[StitchingInhouseMasterController::class,'GetVendorStatusReport'])->name('GetVendorStatusReport');
Route::get('/VendorStatusReport',[StitchingInhouseMasterController::class,'VendorStatusReport'])->name('VendorStatusReport');
Route::get('/GetDailyEfficiencyReport',[StitchingInhouseMasterController::class,'GetDailyEfficiencyReport'])->name('GetDailyEfficiencyReport');
Route::get('/DailyEfficiencyReport',[StitchingInhouseMasterController::class,'DailyEfficiencyReport'])->name('DailyEfficiencyReport');

Route::get('/StitchingGRNPrint/{id}',[StitchingInhouseMasterController::class,'StitchingGRNPrint']);


Route::resource('CutPanelIssue', CutPanelIssueMasterController::class);
Route::get('/VW_GetCutOrderQty',[CutPanelIssueMasterController::class,'VW_GetCutOrderQty'])->name('VW_GetCutOrderQty');
Route::get('/GetLineList',[CutPanelIssueMasterController::class,'GetLineList'])->name('GetLineList');
Route::get('/GetCUTGRNQty',[CutPanelIssueMasterController::class,'GetCUTGRNQty'])->name('GetCUTGRNQty');

Route::get('/CUTGRNQty',[CutPanelIssueMasterController::class,'CUTGRNQty'])->name('CUTGRNQty');

Route::get('GetCutPanelIssueReport',[CutPanelIssueMasterController::class,'GetCutPanelIssueReport'])->name('GetCutPanelIssueReport');

Route::get('CutPanelStockSummary',[CutPanelIssueMasterController::class,'CutPanelStockSummary'])->name('CutPanelStockSummary');
Route::get('/CutPanelGRNReport',[CutPanelIssueMasterController::class,'CutPanelGRNReport'])->name('CutPanelGRNReport');
Route::get('/CutPanelGRNReportMD/{id}',[CutPanelIssueMasterController::class,'CutPanelGRNReportMD'])->name('CutPanelGRNReportMD');
Route::get('GetSalesOrder',[CutPanelIssueMasterController::class,'GetSalesOrder'])->name('GetSalesOrder');
Route::get('GetBuyerItemData',[CutPanelIssueMasterController::class,'GetBuyerItemData'])->name('GetBuyerItemData');
Route::get('GetStyleData',[CutPanelIssueMasterController::class,'GetStyleData'])->name('GetStyleData');
Route::get('GetGarmentColor',[CutPanelIssueMasterController::class,'GetGarmentColor'])->name('GetGarmentColor');
Route::get('GetSalesOrderDataFromBuyerVendor',[CutPanelIssueMasterController::class,'GetSalesOrderDataFromBuyerVendor'])->name('GetSalesOrderDataFromBuyerVendor');
Route::get('cutPanelIssueShowAll',[CutPanelIssueMasterController::class,'cutPanelIssueShowAll'])->name('cutPanelIssueShowAll');


Route::get('/CutPanelIssuePrint/{id}',[CutPanelIssueMasterController::class,'CutPanelIssuePrint']);


Route::get('/CutPanelIssueReport',[CutPanelIssueMasterController::class,'CutPanelIssueReport'])->name('CutPanelIssueReport');
Route::get('/CutPanelIssueReportMD/{id}',[CutPanelIssueMasterController::class,'CutPanelIssueReportMD'])->name('CutPanelIssueReportMD');
Route::resource('OutwardForFinishing', OutwardForFinishingMasterController::class);
Route::get('/vpo_GetFinishingPOQty',[OutwardForFinishingMasterController::class,'vpo_GetFinishingPOQty'])->name('vpo_GetFinishingPOQty');

Route::get('/OutwardForFinishingPrint/{id}',[OutwardForFinishingMasterController::class,'OutwardForFinishingPrint']);




Route::get('/GetSTITCHINGGRNQty',[FinishingInhouseMasterController::class,'GetSTITCHINGGRNQty'])->name('GetSTITCHINGGRNQty'); 
Route::get('/FinishingGRNPrint/{id}',[FinishingInhouseMasterController::class,'FinishingGRNPrint']);

Route::resource('OutwardForPacking', OutwardForPackingMasterController::class);
Route::get('/vpo_GetPackingPOQty',[OutwardForPackingMasterController::class,'vpo_GetPackingPOQty'])->name('vpo_GetPackingPOQty');  
Route::get('/vw_GetPackingPOQty',[OutwardForPackingMasterController::class,'vw_GetPackingPOQty'])->name('vw_GetPackingPOQty');  
Route::get('/vw_GetStitichingPOQty',[OutwardForPackingMasterController::class,'vw_GetStitichingPOQty'])->name('vw_GetStitichingPOQty');  
Route::get('/FinishingInwardOutwardSummaryReport',[OutwardForPackingMasterController::class,'FinishingInwardOutwardSummaryReport'])->name('FinishingInwardOutwardSummaryReport');  
Route::get('/FinishingInwardOutwardDetailReport',[OutwardForPackingMasterController::class,'FinishingInwardOutwardDetailReport'])->name('FinishingInwardOutwardDetailReport');  

Route::get('/OutwardForPackingPrint/{id}',[OutwardForPackingMasterController::class,'OutwardForPackingPrint']);
Route::get('/rptOutwardForPackingReport',[OutwardForPackingMasterController::class,'rptOutwardForPackingReport'])->name('rptOutwardForPackingReport'); 
Route::get('/OutwardForPackingMergedPrint',[OutwardForPackingMasterController::class,'OutwardForPackingMergedPrint'])->name('OutwardForPackingMergedPrint'); 

Route::resource('CutPanelGRN', CutPanelGRNMasterController::class);
Route::get('/VPO_GetCutOrderQty',[CutPanelGRNMasterController::class,'VPO_GetCutOrderQty'])->name('VPO_GetCutOrderQty');
Route::get('/VendorProcessOrderDetails',[CutPanelGRNMasterController::class,'getVendorProcessOrderDetails'])->name('VendorProcessOrderDetails');
Route::get('/CutPanelGRNPrint/{id}',[CutPanelGRNMasterController::class,'CutPanelGRNPrint']);
Route::get('/PPCCuttingReport',[CutPanelGRNMasterController::class,'PPCCuttingReport'])->name('PPCCuttingReport');
Route::get('/CutPanelGRNShowAll',[CutPanelGRNMasterController::class,'CutPanelGRNShowAll'])->name('CutPanelGRNShowAll');

Route::resource('QCStitchingInhouse', QCStitchingInhouseMasterController::class);
Route::get('/StitchingInhouseDetails',[QCStitchingInhouseMasterController::class,'getStitchingInhouseDetails'])->name('StitchingInhouseDetails');
Route::get('/STI_GetOrderQty',[QCStitchingInhouseMasterController::class,'STI_GetOrderQty'])->name('STI_GetOrderQty');
Route::get('/QCStitchingReport',[QCStitchingInhouseMasterController::class,'QCStitchingReport'])->name('QCStitchingReport');
Route::get('/QualityControl',[QCStitchingInhouseMasterController::class,'QualityControl'])->name('QualityControl');
Route::get('/QualityControlTrial',[QCStitchingInhouseMasterController::class,'QualityControlTrial'])->name('QualityControlTrial');
Route::get('/GetQualityControlVWTable',[QCStitchingInhouseMasterController::class,'GetQualityControlVWTable'])->name('GetQualityControlVWTable');
Route::get('/GetQualityControlVWTableTrial',[QCStitchingInhouseMasterController::class,'GetQualityControlVWTableTrial'])->name('GetQualityControlVWTableTrial');
Route::get('/ReadEachDefectDataTrial',[QCStitchingInhouseMasterController::class,'ReadEachDefectDataTrial'])->name('ReadEachDefectDataTrial');
Route::get('/ReadFinalAlterDataTrial',[QCStitchingInhouseMasterController::class,'ReadFinalAlterDataTrial'])->name('ReadFinalAlterDataTrial');
Route::get('/StoreQualityControlDataTrial',[QCStitchingInhouseMasterController::class,'StoreQualityControlDataTrial'])->name('StoreQualityControlDataTrial');
Route::get('/StoreAlterQualityControlDataTrial',[QCStitchingInhouseMasterController::class,'StoreAlterQualityControlDataTrial'])->name('StoreAlterQualityControlDataTrial');
Route::get('/StoreQualityControlData',[QCStitchingInhouseMasterController::class,'StoreQualityControlData'])->name('StoreQualityControlData');
Route::get('/StoreAlterQualityControlData',[QCStitchingInhouseMasterController::class,'StoreAlterQualityControlData'])->name('StoreAlterQualityControlData');
Route::get('/ReadEachDefectData',[QCStitchingInhouseMasterController::class,'ReadEachDefectData'])->name('ReadEachDefectData');
Route::get('/ReadFinalAlterData',[QCStitchingInhouseMasterController::class,'ReadFinalAlterData'])->name('ReadFinalAlterData');
Route::get('/QualityControlReport',[QCStitchingInhouseMasterController::class,'QualityControlReport'])->name('QualityControlReport');


Route::get('/newSewingReport',[StitchingInhouseMasterController::class,'newSewingReport'])->name('newSewingReport');

Route::get('/QCStitchingGRNPrint/{id}',[QCStitchingInhouseMasterController::class,'QCStitchingGRNPrint']);

Route::resource('FinishingInhouse', FinishingInhouseMasterController::class);
//Route::get('/QCStitchingInhouseDetails',[FinishingInhouseMasterController::class,'getQCStitchingInhouseDetails'])->name('QCStitchingInhouseDetails');
//Route::get('/QC_GetOrderQty',[FinishingInhouseMasterController::class,'QC_GetOrderQty'])->name('QC_GetOrderQty');
Route::get('/vpo_GetFinishedPOQty',[FinishingInhouseMasterController::class,'vpo_GetFinishedPOQty'])->name('vpo_GetFinishedPOQty');


Route::resource('PackingInhouse', PackingInhouseMasterController::class);
Route::get('/PackingInhouseDetails',[PackingInhouseMasterController::class,'getFinishingInhouseDetails'])->name('FinishingInhouseDetails');
Route::get('/FNSI_GetOrderQty',[PackingInhouseMasterController::class,'FNSI_GetOrderQty'])->name('FNSI_GetOrderQty');
Route::get('/GetFINISHINGGRNQty',[PackingInhouseMasterController::class,'GetFINISHINGGRNQty'])->name('GetFINISHINGGRNQty');
Route::get('/Op_GetOrderQty',[PackingInhouseMasterController::class,'Op_GetOrderQty'])->name('Op_GetOrderQty');
Route::get('/PackingGRNPrint/{id}',[PackingInhouseMasterController::class,'PackingGRNPrint']);
Route::get('/PackingGRNReport',[PackingInhouseMasterController::class,'PackingGRNReport'])->name('PackingGRNReport');
Route::get('/LoadPackingGRNReport',[PackingInhouseMasterController::class,'LoadPackingGRNReport'])->name('LoadPackingGRNReport');
Route::get('/PackingGRNReportMD/{id}',[PackingInhouseMasterController::class,'PackingGRNReportMD'])->name('PackingGRNReportMD');
Route::get('/Stitching_GetOrderQty',[PackingInhouseMasterController::class,'Stitching_GetOrderQty'])->name('Stitching_GetOrderQty');
Route::get('/FNSI_GetOrderQtyPacking',[PackingInhouseMasterController::class,'FNSI_GetOrderQtyPacking'])->name('FNSI_GetOrderQtyPacking');
Route::get('/FNSI_GetOrderQtyPackingDemo',[PackingInhouseMasterController::class,'FNSI_GetOrderQtyPackingDemo'])->name('FNSI_GetOrderQtyPackingDemo');
Route::get('/DemoEdit/{id}',[PackingInhouseMasterController::class,'DemoEdit'])->name('DemoEdit');
Route::get('/OutletSalesReport',[OutletSaleController::class,'OutletSalesReport'])->name('OutletSalesReport'); 


Route::resource('PackingMaster', PackingMasterController::class);
Route::get('/PackingInhouseDetails1',[PackingMasterController::class,'getFinishingInhouseDetails1'])->name('FinishingInhouseDetails1');
Route::get('/FNSI_GetOrderQty1',[PackingMasterController::class,'FNSI_GetOrderQty1'])->name('FNSI_GetOrderQty1');
Route::get('/GetFINISHINGGRNQty1',[PackingMasterController::class,'GetFINISHINGGRNQty1'])->name('GetFINISHINGGRNQty1');
Route::get('/Op_GetOrderQty1',[PackingMasterController::class,'Op_GetOrderQty1'])->name('Op_GetOrderQty1');
Route::get('/PackingGRNPrint1/{id}',[PackingMasterController::class,'PackingGRNPrint1']);
Route::get('/PackingGRNReport1',[PackingMasterController::class,'PackingGRNReport1'])->name('PackingGRNReport1');
Route::get('/LoadPackingGRNReport1',[PackingMasterController::class,'LoadPackingGRNReport1'])->name('LoadPackingGRNReport1');
Route::get('/PackingGRNReportMD1/{id}',[PackingMasterController::class,'PackingGRNReportMD1'])->name('PackingGRNReportMD1');
Route::get('/Stitching_GetOrderQty1',[PackingMasterController::class,'Stitching_GetOrderQty1'])->name('Stitching_GetOrderQty1');

Route::resource('PackingVendor', PackingVendorMasterController::class);
Route::get('/PackingVendorGRNPrint/{id}',[PackingVendorMasterController::class,'PackingVendorGRNPrint']);

Route::resource('CartonPackingInhouse', CartonPackingInhouseMasterController::class);
Route::get('/CartonPackingInhouseDetails',[CartonPackingInhouseMasterController::class,'getPackingInhouseDetails'])->name('PackingInhouseDetails');
Route::get('/PKI_GetOrderQty',[CartonPackingInhouseMasterController::class,'PKI_GetOrderQty'])->name('PKI_GetOrderQty');
Route::get('/PKI_GetOrdarQtyByRow',[CartonPackingInhouseMasterController::class,'PKI_GetOrdarQtyByRow'])->name('PKI_GetOrdarQtyByRow');

Route::get('/FGStockReport',[CartonPackingInhouseMasterController::class,'FGStockReport'])->name('FGStockReport');
Route::get('/FGStockReportTrial',[CartonPackingInhouseMasterController::class,'FGStockReportTrial'])->name('FGStockReportTrial');
Route::get('/LoadFGStockReportTrial',[CartonPackingInhouseMasterController::class,'LoadFGStockReportTrial'])->name('LoadFGStockReportTrial');
Route::get('/FGStockReportTrial2',[CartonPackingInhouseMasterController::class,'FGStockReportTrial2'])->name('FGStockReportTrial2');
Route::get('/LoadFGStockReportTrial1',[CartonPackingInhouseMasterController::class,'LoadFGStockReportTrial1'])->name('LoadFGStockReportTrial1');
Route::get('/QuantitativeInventoryReport',[CartonPackingInhouseMasterController::class,'QuantitativeInventoryReport'])->name('QuantitativeInventoryReport');
Route::get('/QuantitativeInventoryReport1',[CartonPackingInhouseMasterController::class,'QuantitativeInventoryReport1'])->name('QuantitativeInventoryReport1');
Route::get('/LoadFabricQuantitiveReport',[CartonPackingInhouseMasterController::class,'LoadFabricQuantitiveReport'])->name('LoadFabricQuantitiveReport');
Route::get('/LoadTrimsQuantitiveReport',[CartonPackingInhouseMasterController::class,'LoadTrimsQuantitiveReport'])->name('LoadTrimsQuantitiveReport');
Route::get('/LoadWIPQuantitiveReport',[CartonPackingInhouseMasterController::class,'LoadWIPQuantitiveReport'])->name('LoadWIPQuantitiveReport');
Route::get('/LoadFGQuantitiveReport',[CartonPackingInhouseMasterController::class,'LoadFGQuantitiveReport'])->name('LoadFGQuantitiveReport');
Route::get('/RunCronFGJob',[CartonPackingInhouseMasterController::class,'RunCronFGJob'])->name('RunCronFGJob');
Route::get('/InventoryReportMovingNonMoving',[CartonPackingInhouseMasterController::class,'InventoryReportMovingNonMoving'])->name('InventoryReportMovingNonMoving');
Route::get('/InventoryReportMovingNonMovingIframe',[CartonPackingInhouseMasterController::class,'InventoryReportMovingNonMovingIframe'])->name('InventoryReportMovingNonMovingIframe');
Route::get('/LoadFabricInventoryMovingNonMovingReport',[CartonPackingInhouseMasterController::class,'LoadFabricInventoryMovingNonMovingReport'])->name('LoadFabricInventoryMovingNonMovingReport');
Route::get('/LoadTrimsInventoryMovingNonMovingReport',[CartonPackingInhouseMasterController::class,'LoadTrimsInventoryMovingNonMovingReport'])->name('LoadTrimsInventoryMovingNonMovingReport');
Route::get('/LoadFGInventoryMovingNonMovingReport',[CartonPackingInhouseMasterController::class,'LoadFGInventoryMovingNonMovingReport'])->name('LoadFGInventoryMovingNonMovingReport');
Route::get('/LoadWIPInventoryMovingNonMoving',[CartonPackingInhouseMasterController::class,'LoadWIPInventoryMovingNonMoving'])->name('LoadWIPInventoryMovingNonMoving');
Route::get('/LoadCartonPackingReport',[CartonPackingInhouseMasterController::class,'LoadCartonPackingReport'])->name('LoadCartonPackingReport');
Route::get('/loadFGInventoryAgingReport',[CartonPackingInhouseMasterController::class,'loadFGInventoryAgingReport'])->name('loadFGInventoryAgingReport'); 
Route::get('/FGInventoryAgingReport',[CartonPackingInhouseMasterController::class,'FGInventoryAgingReport'])->name('FGInventoryAgingReport'); 


Route::get('/FGStockReport_2',[CartonPackingInhouseMasterController::class,'FGStockReport_2'])->name('FGStockReport_2');

Route::get('/FGStockReportMD/{id}/{id1}',[CartonPackingInhouseMasterController::class,'FGStockReportMD'])->name('FGStockReportMD');
Route::get('/FGStockSummaryReport',[CartonPackingInhouseMasterController::class,'FGStockSummaryReport'])->name('FGStockSummaryReport');
Route::get('/FGStockOrderWiseSummaryReport',[CartonPackingInhouseMasterController::class,'FGStockOrderWiseSummaryReport'])->name('FGStockOrderWiseSummaryReport');

Route::get('/GetLocationFGStockReport',[CartonPackingInhouseMasterController::class,'GetLocationFGStockReport'])->name('GetLocationFGStockReport');
Route::get('/FGLocationStockReport',[CartonPackingInhouseMasterController::class,'FGLocationStockReport'])->name('FGLocationStockReport');



Route::get('/GetCartonPackingReport',[CartonPackingInhouseMasterController::class,'GetCartonPackingReport'])->name('GetCartonPackingReport');
Route::get('/CartonPackingReport',[CartonPackingInhouseMasterController::class,'CartonPackingReport'])->name('CartonPackingReport');
 
Route::get('/PKI_GetColorList',[CartonPackingInhouseMasterController::class,'PKI_GetColorList'])->name('PKI_GetColorList');
Route::get('PKI_GetMaxMinvalueList',[CartonPackingInhouseMasterController::class,'GetMaxMinvalueList'])->name('PKI_GetMaxMinvalueList');

Route::get('NewSalesOrderList',[CartonPackingInhouseMasterController::class,'getSalesOrderList'])->name('NewSalesOrderList');
Route::get('/BuyerLocationList',[CartonPackingInhouseMasterController::class,'getBuyerLocationList'])->name('BuyerLocationList');

Route::get('/CartonPackingPrint/{id}',[CartonPackingInhouseMasterController::class,'CartonPackingPrint']);

Route::post('fg_stock_export',[CartonPackingInhouseMasterController::class,'fg_stock_export'])->name('fg_stock_export');

Route::get('/checkDifferentSizeGroup',[CartonPackingInhouseMasterController::class,'checkDifferentSizeGroup'])->name('checkDifferentSizeGroup');
Route::get('/DumpFGData',[CartonPackingInhouseMasterController::class,'DumpFGData'])->name('DumpFGData');
Route::get('/cartonPackingShowAll',[CartonPackingInhouseMasterController::class,'cartonPackingShowAll'])->name('cartonPackingShowAll');
Route::get('/GetStyleNoListForCarton',[CartonPackingInhouseMasterController::class,'GetStyleNoListForCarton'])->name('GetStyleNoListForCarton');
Route::get('/CartonPackingInhouseDemo',[CartonPackingInhouseMasterController::class,'CartonPackingInhouseDemo'])->name('CartonPackingInhouseDemo');
Route::get('/CartonPackingInhouseDemoEdit/{id}',[CartonPackingInhouseMasterController::class,'CartonPackingInhouseDemoEdit'])->name('CartonPackingInhouseDemoEdit');
Route::get('/PKI_GetOrderQtyCarton',[CartonPackingInhouseMasterController::class,'PKI_GetOrderQtyCarton'])->name('PKI_GetOrderQtyCarton');


Route::resource('FGTransferToLocation', FGTransferToLocationController::class);
Route::get('/PKI_GetOrderQty1',[FGTransferToLocationController::class,'PKI_GetOrderQty1'])->name('PKI_GetOrderQty1');

Route::get('/GetFGTransferLocationData',[FGTransferToLocationInwardController::class,'GetFGTransferLocationData'])->name('GetFGTransferLocationData');

Route::resource('FGTransferToLocationInward', FGTransferToLocationInwardController::class);

Route::resource('TransferPackingInhouse', TransferPackingInhouseMasterController::class);
Route::get('/FG_GetRawData',[TransferPackingInhouseMasterController::class,'FG_GetRawData'])->name('FG_GetRawData');
Route::get('/FGStockData',[TransferPackingInhouseMasterController::class,'FGStockData'])->name('FGStockData');
Route::get('/FG_GetColorList',[TransferPackingInhouseMasterController::class,'FG_GetColorList'])->name('FG_GetColorList');
Route::get('/FGPackingInhouseDetails',[TransferPackingInhouseMasterController::class,'FGPackingInhouseDetails'])->name('FGPackingInhouseDetails');
Route::get('/PKI_GetTransferQtyByRow',[TransferPackingInhouseMasterController::class,'PKI_GetTransferQtyByRow'])->name('PKI_GetTransferQtyByRow');
Route::get('TPKI_GetMaxMinvalueList',[TransferPackingInhouseMasterController::class,'TPKI_GetMaxMinvalueList'])->name('TPKI_GetMaxMinvalueList');
Route::get('TransferPackingPrint/{id}',[TransferPackingInhouseMasterController::class,'TransferPackingPrint'])->name('TransferPackingPrint');
 
Route::get('checkTransferDifferentSizeGroup',[TransferPackingInhouseMasterController::class,'checkTransferDifferentSizeGroup'])->name('checkTransferDifferentSizeGroup');

Route::get('CheckInvoiceStatus',[TransferPackingInhouseMasterController::class,'CheckInvoiceStatus'])->name('CheckInvoiceStatus');
 
//Location to Location KDPL FG Stock Transfer
Route::resource('FGLocationTransferOutward', FGLocationTransferOutwardMasterController::class);
Route::get('/LTFG_GetRawData',[FGLocationTransferOutwardMasterController::class,'LTFG_GetRawData'])->name('LTFG_GetRawData');
Route::get('LTPKI_GetMaxMinvalueList',[FGLocationTransferOutwardMasterController::class,'LTPKI_GetMaxMinvalueList'])->name('LTPKI_GetMaxMinvalueList');
Route::get('/LTPKI_GetTransferQtyByRow',[FGLocationTransferOutwardMasterController::class,'LTPKI_GetTransferQtyByRow'])->name('LTPKI_GetTransferQtyByRow');
Route::get('/LocFGStockData',[FGLocationTransferOutwardMasterController::class,'LocFGStockData'])->name('LocFGStockData');
Route::get('/FGStockSizeValue',[FGLocationTransferOutwardMasterController::class,'FGStockSizeValue'])->name('FGStockSizeValue');

Route::get('/LocTransferPackingPrint/{id}',[FGLocationTransferOutwardMasterController::class,'LocTransferPackingPrint']);

Route::resource('Task', TaskMasterController::class);
Route::get('/CommanData',[TaskMasterController::class,'getCommanDetails'])->name('CommanData');
Route::get('/SizeBalanceList',[TaskMasterController::class,'getBalanceDetails'])->name('SizeBalanceList');
Route::get('TaskList',[CuttingMasterController::class,'GetTaskList'])->name('TaskList');

Route::resource('FabricTrimCard', FabricTrimCardMasterController::class);
Route::get('/JobCardDetail',[FabricTrimCardMasterController::class,'getJobCardDetails'])->name('JobCardDetail');


Route::get('/Average',[FabricTrimCardMasterController::class,'getColorAverage'])->name('Average');
Route::get('/AverageTrim',[FabricTrimCardMasterController::class,'getColorAverageTrim'])->name('AverageTrim');
Route::get('/ColorDetails',[FabricTrimCardMasterController::class,'getColorDetails'])->name('ColorDetails');
Route::get('/TrimColorDetails',[FabricTrimCardMasterController::class,'getTrimColorDetails'])->name('TrimColorDetails');

Route::get('/SalesOrderDetail2',[FabricOutwardController::class,'getSalesOrderDetail2'])->name('SalesOrderDetail2');

Route::get('/FabricOutwardData',[FabricOutwardController::class,'FabricOutwardData'])->name('FabricOutwardData');

Route::get('/GetSINCodeForFabricOutwardList',[FabricOutwardController::class,'GetSINCodeForFabricOutwardList'])->name('GetSINCodeForFabricOutwardList');

Route::get('/GetStockDetailPopupForFabric',[FabricOutwardController::class,'GetStockDetailPopupForFabric'])->name('GetStockDetailPopupForFabric');

Route::get('/GetStockAssociationData',[FabricOutwardController::class,'GetStockAssociationData'])->name('GetStockAssociationData');

Route::get('/FabricOutwardDataMD/{id}',[FabricOutwardController::class,'FabricOutwardDataMD'])->name('FabricOutwardDataMD');


Route::resource('JobCardReport', BuyerJobcardReportController::class);
Route::resource('InwardReport', FabricInwardReportController::class);
Route::get('FabricGRNPrintNew/{id}',[FabricInwardReportController::class,'FabricGRNPrint']);
Route::get('/GetFabricGRNReport',[FabricInwardReportController::class,'GetFabricGRNReport'])->name('GetFabricGRNReport'); 
Route::get('/FabricGRNFilterReport',[FabricInwardReportController::class,'FabricGRNFilterReport'])->name('FabricGRNFilterReport'); 

Route::resource('BundleBarcode', BundleController::class);
Route::get('AddBundleBarcode/{id1}/{id2}',[BundleController::class,'AddBundleBarcode']);
Route::get('/BundleList',[BundleController::class,'getDetails'])->name('BundleList');
Route::get('/BundleSplitList',[BundleController::class,'getRowDetails'])->name('BundleSplitList');
Route::get('/BundlePrint',[BundleController::class,'BundlePrinting'])->name('BundlePrint');
Route::get('/SessionValue',[BundleController::class,'getSessionValue'])->name('SessionValue');
Route::get('/GetJobPartList',[BundleController::class,'GetJobPartList'])->name('GetJobPartList');

Route::get('BundleBarcodeShowAll',[BundleController::class,'BundleBarcodeShowAll'])->name('BundleBarcodeShowAll');

Route::resource('JobPart', JobPartController::class);
Route::resource('FabricTrimPart', FabricTrimPartController::class);
Route::resource('Quality', QualityController::class);

Route::post('qualityimport',[QualityController::class,'qualityimport'])->name('qualityimport');

Route::resource('FabricOutward', FabricOutwardController::class);
//Fabric Outward Controller for Fabric Issue to Internal Department.
Route::get('/FabricRecord',[FabricOutwardController::class,'getFabricRecord'])->name('FabricRecord');
Route::resource('FabricOutwardReport', FabricOutwardReportController::class);


Route::get('/GetFabricInOutStockReportForm',[FabricOutwardReportController::class,'GetFabricInOutStockReportForm'])->name('GetFabricInOutStockReportForm'); 
Route::get('/GetFabricInOutStockSummaryReport',[FabricOutwardReportController::class,'GetFabricInOutStockSummaryReport'])->name('GetFabricInOutStockSummaryReport'); 

Route::get('/FabricInOutStockReport',[FabricOutwardReportController::class,'getFabricInOutStockReport'])->name('FabricInOutStockReport');
Route::get('/FabricInOutStockSummaryReport',[FabricOutwardReportController::class,'FabricInOutStockSummaryReport'])->name('FabricInOutStockSummaryReport');
Route::get('FabricOutwardPrint/{id}',[FabricOutwardReportController::class,'FabricOutwardPrint']);
Route::get('FabricOutwardRollsPrint/{id}',[FabricOutwardReportController::class,'FabricOutwardRollsPrint']);

Route::resource('FabricCheckingReport', FabricCheckingReportController::class);

Route::get('FabricCheckPrint/{id}',[FabricCheckingReportController::class,'FabricCheckPrint']);
Route::resource('FabricCuttingReport', FabricCuttingReportController::class);
Route::resource('FabricTrimCardReport', FabricTrimCardReportController::class);

Route::resource('InwardStore', MaterialInwardStoreController::class);


Route::get('/inwardApprovalList',[MaterialInwardStoreController::class,'show'])->name('inwardApprovalList');

Route::get('/getPoDetails',[MaterialInwardStoreController::class,'getPoDetails'])->name('getPoDetails');

Route::get('/getPoMasterDetails',[MaterialInwardStoreController::class,'getPoMasterDetails'])->name('getPoMasterDetails');

Route::resource('Requisition', RequisitionController::class);

Route::get('/requisitionApproval',[RequisitionController::class,'show'])->name('requisitionApproval');


Route::get('/GETSTOCK',[RequisitionController::class,'GETSTOCK'])->name('GETSTOCK');


Route::resource('RequisitionOutward', RequisitionOutwardController::class);


Route::get('/getRequitionDetails',[RequisitionOutwardController::class,'getRequitionDetails'])->name('getRequitionDetails');
Route::get('/getMasterDetails',[RequisitionOutwardController::class,'getMasterDetails'])->name('getMasterDetails');

Route::resource('ReturnableOutward', ReturnableOutwardController::class);


Route::resource('POReport', POReportController::class);
Route::post('POReport/pdf',[POReportController::class,'pdf'])->name('pdf');

Route::get('/FabricStock',[StockReportController::class,'FabricStock'])->name('FabricStock');
Route::get('/FabricSummary',[StockReportController::class,'FabricStock2'])->name('FabricSummary');
Route::get('/FabricStockPage',[StockReportController::class,'GetOnPageFabricStock'])->name('FabricStockPage');
 



Route::get('/InwardData',[StockReportController::class,'GetInwardFabList'])->name('InwardData');
Route::get('/GetCompareFabricPOInwardData',[StockReportController::class,'GetCompareFabricPOInwardData'])->name('GetCompareFabricPOInwardData');


Route::get('/FabricSummaryPage',[StockReportController::class,'GetOnPageFabricStockSummary'])->name('FabricSummaryPage');

Route::resource('POReportItemWise', POItemWiseReportController::class);
Route::post('POReportItemWise/pdf',[POItemWiseReportController::class,'pdf'])->name('itempdf');


Route::resource('MIStoreReport', MaterialInwardStoreReportController::class);

Route::post('MIStoreReport/pdf',[MaterialInwardStoreReportController::class,'pdf'])->name('inwardpdf');


Route::resource('RequisitionReport', RequisitionReportController::class);

Route::post('RequisitionReport/pdf',[RequisitionReportController::class,'pdf'])->name('Requisitionpdf');


Route::resource('MIStoreReportItemwise', MIStoreItemwiseReportController::class);

Route::post('MIStoreReportItemwise/pdf',[MIStoreItemwiseReportController::class,'pdf'])->name('MIitempdf');
Route::resource('RequisitionOutwardReport', RequisitionOutwardReportController::class);
Route::post('RequisitionOutwardReport/pdf',[RequisitionOutwardReportController::class,'pdf'])->name('MIOutwardpdf');
Route::get('MostPurchaseItems/pdf',[POItemWiseReportController::class,'itemsPdf'])->name('itemsPdf');
Route::get('MostConsumedItems/pdf',[POItemWiseReportController::class,'MostconsumeditemsPdf'])->name('Mostconsumeditems');
Route::get('StockReport/pdf',[StockReportController::class,'itemStockPdf'])->name('itemStockPdf');
Route::get('print/{id}',[POReportController::class,'generatePO']);
Route::resource('TrimsOutward', TrimsOutwardController::class);
Route::get('getVendorCode',[TrimsOutwardController::class,'getVendorCode'])->name('getVendorCode');
Route::get('getTrimsItemRate',[TrimsOutwardController::class,'getTrimsItemRate'])->name('getTrimsItemRate');
Route::get('TrimsOutwardData',[TrimsOutwardController::class,'TrimsOutwardData'])->name('TrimsOutwardData');
Route::get('TrimsOutwardDataMD/{id}',[TrimsOutwardController::class,'TrimsOutwardDataMD'])->name('TrimsOutwardDataMD');
Route::get('getVendorProcessOrder',[TrimsOutwardController::class,'getVendorProcessOrder'])->name('getVendorProcessOrder');
Route::get('getVendorMasterDetail',[TrimsOutwardController::class,'getVendorMasterDetail'])->name('getVendorMasterDetail');
Route::get('getvendortablenew',[TrimsOutwardController::class,'getvendortablenew'])->name('getvendortablenew');
Route::get('getProcessTrimData',[TrimsOutwardController::class,'getProcessTrimData'])->name('getProcessTrimData');
Route::get('getProcessTrimDataTrial',[TrimsOutwardController::class,'getProcessTrimDataTrial'])->name('getProcessTrimDataTrial');
Route::get('TrimOutwardPrint/{id}',[TrimsOutwardController::class,'show']);
Route::get('TrimOutwardStandardPrint/{id}',[TrimsOutwardController::class,'TrimOutwardStandardPrint']);
Route::get('TrimOutwardStandardPrint2/{id}',[TrimsOutwardController::class,'TrimOutwardStandardPrint2']);
Route::get('TrimOutwardStockQty',[TrimsOutwardController::class,'TrimOutwardStockQty'])->name('TrimOutwardStockQty'); 
Route::get('/TrimsOutwardTrial',[TrimsOutwardController::class,'TrimsOutwardTrial'])->name('TrimsOutwardTrial');
Route::resource('PresentEmployees', PresentEmployeesController::class);
Route::resource('ActivityMaster', ActivityMasterController::class);
Route::resource('ActivityTypeMaster', ActivityTypeMasterController::class);
Route::resource('T_And_A_Master', T_And_A_MasterController::class);
Route::get('getSalesOrderDetail', [T_And_A_MasterController::class,'getSalesOrderDetail'])->name('getSalesOrderDetail');
Route::get('Timeline', [T_And_A_MasterController::class,'Timeline'])->name('Timeline');
Route::get('GetTNAMasterData', [T_And_A_MasterController::class,'GetTNAMasterData'])->name('GetTNAMasterData');
Route::resource('PPCMaster', PPCMasterController::class);
Route::get('PPCHolidayCalendar', [PPCMasterController::class,'PPCHolidayCalendar'])->name('PPCHolidayCalendar');
Route::get('PPCHolidayCalendarList', [PPCMasterController::class,'PPCHolidayCalendarList'])->name('PPCHolidayCalendarList');
Route::post('StorePPCHolidayCalendar', [PPCMasterController::class,'StorePPCHolidayCalendar'])->name('StorePPCHolidayCalendar');
Route::delete('PPCHolidayDelete/{id}', [PPCMasterController::class,'PPCHolidayDelete'])->name('PPCHolidayDelete');
Route::resource('T_And_A_TemplateMaster', T_And_A_TemplateMasterController::class);
Route::get('getSalesOrderDetail2', [T_And_A_TemplateMasterController::class,'getSalesOrderDetail2'])->name('getSalesOrderDetail2');
Route::get('Timeline2', [T_And_A_TemplateMasterController::class,'Timeline2'])->name('Timeline2');
Route::get('PPCCalendarReport/{id}/{id2}',[PPCMasterController::class,'PPCCalendarReport'])->name('PPCCalendarReport');
Route::get('PPCCalendarReport/{id}/{id2}/{id3}/{id4}',[PPCMasterController::class,'PPCCalendarReport'])->name('PPCCalendarReport');
//Route::get('PPCCalendarReport',[PPCMasterController::class,'PPCCalendarReport'])->name('PPCCalendarReport');

Route::post('search',[PPCMasterController::class,'search'])->name('search');
Route::get('rptSAH_PPC',[PPCMasterController::class,'rptSAH_PPC'])->name('rptSAH_PPC');
Route::get('SAH_PPCMaster',[PPCMasterController::class,'SAH_PPCMaster'])->name('SAH_PPCMaster');
Route::get('SAHPPCEdit/{id}',[PPCMasterController::class,'SAHPPCEdit'])->name('SAHPPCEdit');
Route::get('rptSAHMonthWise_PPC',[PPCMasterController::class,'rptSAHMonthWise_PPC'])->name('rptSAHMonthWise_PPC');
Route::put('SAH_PPCMasterUpdate/{id}',[PPCMasterController::class,'SAH_PPCMasterUpdate'])->name('SAH_PPCMasterUpdate');

Route::delete('/SAHPPCDelete/{id}',[PPCMasterController::class,'SAHPPCDelete'])->name('SAHPPCDelete');

Route::get('/GetSalesOrderList',[PPCMasterController::class,'GetSalesOrderList'])->name('GetSalesOrderList'); 
Route::get('/GetSaleOrderWiseColorList',[PPCMasterController::class,'GetSaleOrderWiseColorList'])->name('GetSaleOrderWiseColorList'); 

Route::post('PPCMaster/SAHPPC',[PPCMasterController::class,'SAHPPC'])->name('SAHPPC');

Route::get('WIPDetailReport/{id}',[BuyerPurchaseOrderController::class,'WIPDetailReport'])->name('WIPDetailReport');

Route::get('MonthlyShipmentTargetReport',[BuyerPurchaseOrderController::class,'MonthlyShipmentTargetReport'])->name('MonthlyShipmentTargetReport');
Route::get('LoadMonthlyShipmentTargetReport',[BuyerPurchaseOrderController::class,'LoadMonthlyShipmentTargetReport'])->name('LoadMonthlyShipmentTargetReport');

Route::get('GetPPCData',[PPCMasterController::class,'GetPPCData'])->name('GetPPCData');

Route::get('GetColorWiseQty',[PPCMasterController::class,'GetColorWiseQty'])->name('GetColorWiseQty');

Route::get('PPCWIPReport',[PPCMasterController::class,'PPCWIPReport'])->name('PPCWIPReport');

Route::get('StorePPCWIPData',[PPCMasterController::class,'StorePPCWIPData'])->name('StorePPCWIPData');

Route::resource('ReportViewer', ReportViewerController::class);

Route::get('ReportViewerDashboard',[ReportViewerController::class,'ReportViewerDashboard'])->name('ReportViewerDashboard');

Route::get('/PrintSaleTransaction/{id}',[SaleTransactionMasterController::class,'PrintSaleTransaction'])->name('PrintSaleTransaction');
Route::get('/DCPrintSaleTransaction/{id}',[SaleTransactionMasterController::class,'DCPrintSaleTransaction'])->name('DCPrintSaleTransaction');

Route::get('MonthlyShipmentTargetMaster',[SaleTransactionMasterController::class,'MonthlyShipmentTargetMaster'])->name('MonthlyShipmentTargetMaster');

Route::get('GetBuyerData',[SaleTransactionMasterController::class,'GetBuyerData'])->name('GetBuyerData');

Route::get('GetStyleCategoryData',[SaleTransactionMasterController::class,'GetStyleCategoryData'])->name('GetStyleCategoryData');

Route::post('monthlyShipmentTargetStore',[SaleTransactionMasterController::class,'monthlyShipmentTargetStore'])->name('monthlyShipmentTargetStore');

Route::get('rptMonthlyShipmentTarget',[SaleTransactionMasterController::class,'rptMonthlyShipmentTarget'])->name('rptMonthlyShipmentTarget'); 

Route::get('GetMontlyBudgetSalesReport',[SaleTransactionMasterController::class,'GetMontlyBudgetSalesReport'])->name('GetMontlyBudgetSalesReport'); 
Route::get('GetBuyerWiseFOBSalesReport',[SaleTransactionMasterController::class,'GetBuyerWiseFOBSalesReport'])->name('GetBuyerWiseFOBSalesReport'); 
Route::get('GetBuyerWiseJobWorkSalesReport',[SaleTransactionMasterController::class,'GetBuyerWiseJobWorkSalesReport'])->name('GetBuyerWiseJobWorkSalesReport'); 
Route::get('GetBuyerWiseStockSalesReport',[SaleTransactionMasterController::class,'GetBuyerWiseStockSalesReport'])->name('GetBuyerWiseStockSalesReport'); 
Route::get('checkInvoice',[SaleTransactionMasterController::class,'checkInvoice'])->name('checkInvoice');
Route::get('GetTradePartyDetailsSale',[SaleTransactionMasterController::class,'GetTradePartyDetailsSale'])->name('GetTradePartyDetailsSale'); 
Route::get('getEInvoiceTokenForProduction',[SaleTransactionMasterController::class,'getEInvoiceTokenForProduction'])->name('getEInvoiceTokenForProduction');   

Route::post('generate-einvoice', [SaleTransactionMasterController::class, 'generateEInvoice']);

Route::post('generate-ewaybill', [SaleTransactionMasterController::class, 'generateEWayBill']);

Route::get('rptOpenOrderPPC',[OpenOrderPPCController::class,'rptOpenOrderPPC'])->name('rptOpenOrderPPC');

Route::resource('OpenOrderPPC', OpenOrderPPCController::class);

Route::get('GetPlannedVsActual',[PPCMasterController::class,'GetPlannedVsActual'])->name('GetPlannedVsActual');
Route::post('rptPlannedVsActual',[PPCMasterController::class,'rptPlannedVsActual'])->name('rptPlannedVsActual');
Route::get('/GetPlanLineList',[PPCMasterController::class,'GetPlanLineList'])->name('GetPlanLineList');
Route::get('rptFabricOCR',[FabricInwardController::class,'rptFabricOCR'])->name('rptFabricOCR');
Route::get('GetVendorWorkOrderOCR',[VendorWorkOrderController::class,'GetVendorWorkOrderOCR'])->name('GetVendorWorkOrderOCR');
Route::post('rptVendorWorkOrderOCR',[VendorWorkOrderController::class,'rptVendorWorkOrderOCR'])->name('rptVendorWorkOrderOCR');
Route::get('SalesOrderNoList',[VendorWorkOrderController::class,'SalesOrderNoList'])->name('SalesOrderNoList');

Route::get('GetVendorWorkOrderStock',[BuyerPurchaseOrderController::class,'GetVendorWorkOrderStock'])->name('GetVendorWorkOrderStock');
Route::post('/VendorWorkOrderStock',[BuyerPurchaseOrderController::class,'VendorWorkOrderStock'])->name('VendorWorkOrderStock');
// Route::get('VendorWorkOrderStockPaginate',[BuyerPurchaseOrderController::class,'VendorWorkOrderStockPaginate'])->name('VendorWorkOrderStockPaginate');
// Route::get('VendorWorkOrderStock', array('page' => 'VendorWorkOrderStockPaginate', function() {
//     $url = url('VendorWorkOrderStockPaginate');
//     return redirect()->route('VendorWorkOrderStockPaginate');
// }));

Route::get('DeviationPPCList',[PPCMasterController::class,'DeviationPPCList'])->name('DeviationPPCList');

Route::get('deviationPPCMaster',[PPCMasterController::class,'deviationPPCMaster'])->name('deviationPPCMaster');

Route::get('deviationPPCMasterEdit/{id}',[PPCMasterController::class,'deviationPPCMasterEdit'])->name('deviationPPCMasterEdit');

Route::post('PPCMaster/deviationPPCMasterStore',[PPCMasterController::class,'deviationPPCMasterStore'])->name('deviationPPCMasterStore');

Route::get('deviationPPCMasterUpdate/{id}',[PPCMasterController::class,'deviationPPCMasterUpdate'])->name('deviationPPCMasterUpdate');

Route::get('deviationPPCMasterDelete/{id}',[PPCMasterController::class,'deviationPPCMasterDelete'])->name('deviationPPCMasterDelete');

Route::get('GetDeviationPPC',[PPCMasterController::class,'GetDeviationPPC'])->name('GetDeviationPPC');

Route::post('rptDeviationPPC',[PPCMasterController::class,'rptDeviationPPC'])->name('rptDeviationPPC');

Route::get('getItemDescription',[TrimsOutwardController::class,'getItemDescription'])->name('getItemDescription');

Route::get('SpeededDashboard',[AdminController::class,'SpeededDashboard'])->name('SpeededDashboard'); 

Route::get('MDDashboard',[AdminController::class,'MDDashboard'])->name('MDDashboard'); 
Route::get('MDDashboard1',[AdminController::class,'MDDashboard1'])->name('MDDashboard1'); 

Route::get('orderBookingDashboard',[AdminController::class,'orderBookingDashboard'])->name('orderBookingDashboard'); 
Route::get('salesMDDashboard',[AdminController::class,'salesMDDashboard'])->name('salesMDDashboard'); 
Route::get('ocrMDDashboard',[AdminController::class,'ocrMDDashboard'])->name('ocrMDDashboard'); 
Route::get('fabricMDDashboard',[AdminController::class,'fabricMDDashboard'])->name('fabricMDDashboard'); 
Route::get('trimsMDDashboard',[AdminController::class,'trimsMDDashboard'])->name('trimsMDDashboard'); 
Route::get('operationMDDashboard',[AdminController::class,'operationMDDashboard'])->name('operationMDDashboard'); 
Route::get('openOrderMDDashboard',[AdminController::class,'openOrderMDDashboard'])->name('openOrderMDDashboard'); 
Route::get('inventoryStatusMDDashboard',[AdminController::class,'inventoryStatusMDDashboard'])->name('inventoryStatusMDDashboard'); 
Route::get('GetQuntitiveInventoryReport',[AdminController::class,'GetQuntitiveInventoryReport'])->name('GetQuntitiveInventoryReport'); 
Route::get('QuaititativeInventoryReportList',[AdminController::class,'QuaititativeInventoryReportList'])->name('QuaititativeInventoryReportList'); 

Route::get('GraphicalDashboard',[AdminController::class,'GraphicalDashboard'])->name('GraphicalDashboard');
Route::get('GraphicalDashboardOriginalCode',[AdminController::class,'GraphicalDashboardOriginalCode'])->name('GraphicalDashboardOriginalCode');
Route::get('AnalysisQualityControl',[AdminController::class,'AnalysisQualityControl'])->name('AnalysisQualityControl');

Route::get('Outsource',[AdminController::class,'OutsourceDashboard'])->name('Outsource'); 

Route::get('GraphicalSaleDashboard',[AdminController::class,'GraphicalSaleDashboard'])->name('GraphicalSaleDashboard'); 

Route::get('GetStockDetailPopup',[PurchaseOrderController::class,'GetStockDetailPopup'])->name('GetStockDetailPopup');

Route::get('Get_Ledger_Item_Report',[ItemMasterController::class,'Get_Ledger_Item_Report'])->name('Get_Ledger_Item_Report');

Route::get('GetClassifictionData',[ItemMasterController::class,'GetClassifictionData'])->name('GetClassifictionData');

Route::get('GetClassifictionTrimsData',[ItemMasterController::class,'GetClassifictionTrimsData'])->name('GetClassifictionTrimsData');

Route::get('GetItemData',[ItemMasterController::class,'GetItemData'])->name('GetItemData');

Route::post('rptItemLedger',[ItemMasterController::class,'rptItemLedger'])->name('rptItemLedger');




Route::get('Get_Cut_Panel_Issue_VS_Production',[PPCMasterController::class,'Get_Cut_Panel_Issue_VS_Production'])->name('Get_Cut_Panel_Issue_VS_Production');

Route::post('rptCutPanelIssueVsProduction',[PPCMasterController::class,'rptCutPanelIssueVsProduction'])->name('rptCutPanelIssueVsProduction');

Route::get('Get_WIP_Report',[PPCMasterController::class,'Get_WIP_Report'])->name('Get_WIP_Report');

Route::post('rptWIPReport',[PPCMasterController::class,'rptWIPReport'])->name('rptWIPReport');

Route::get('rptFGMovingReport',[PPCMasterController::class,'rptFGMovingReport'])->name('rptFGMovingReport');

Route::get('Get_Total_WIP_Report',[PPCMasterController::class,'Get_Total_WIP_Report'])->name('Get_Total_WIP_Report');

Route::get('rptTotalWIPReport',[PPCMasterController::class,'rptTotalWIPReport'])->name('rptTotalWIPReport');

Route::get('StageWiseWIPReport',[PPCMasterController::class,'StageWiseWIPReport'])->name('StageWiseWIPReport');

Route::get('VendorWiseWIPReport',[PPCMasterController::class,'VendorWiseWIPReport'])->name('VendorWiseWIPReport');

Route::get('Get_WIP_Report2',[PPCMasterController::class,'Get_WIP_Report2'])->name('Get_WIP_Report2');

Route::post('rptWIPReport2',[PPCMasterController::class,'rptWIPReport2'])->name('rptWIPReport2');

Route::get('Get_Finishing_WIP_Report',[PPCMasterController::class,'Get_Finishing_WIP_Report'])->name('Get_Finishing_WIP_Report');

Route::get('rptFinishingWIP',[PPCMasterController::class,'rptFinishingWIP'])->name('rptFinishingWIP');

Route::get('GetSalesOrderNoList',[PPCMasterController::class,'GetSalesOrderNoList'])->name('GetSalesOrderNoList');

Route::get('rptFabricMovingAndNonMovingStock',[FabricInwardController::class,'rptFabricMovingAndNonMovingStock'])->name('rptFabricMovingAndNonMovingStock');

Route::get('Get_WIP_Report1',[PPCMasterController::class,'Get_WIP_Report1'])->name('Get_WIP_Report1');

Route::post('rptWIPReportss',[PPCMasterController::class,'rptWIPReportss'])->name('rptWIPReportss');

Route::get('Get_Daily_WIP_Tracking_Report',[PPCMasterController::class,'Get_Daily_WIP_Tracking_Report'])->name('Get_Daily_WIP_Tracking_Report');

Route::post('rptDailyWIPTracking',[PPCMasterController::class,'rptDailyWIPTracking'])->name('rptDailyWIPTracking');

Route::get('GetMovingReportData',[FabricInwardController::class,'GetMovingReportData'])->name('GetMovingReportData');

Route::get('GetNonMovingReportData',[FabricInwardController::class,'GetNonMovingReportData'])->name('GetNonMovingReportData');

Route::get('/InventoryAgingReport',[FabricInwardController::class,'InventoryAgingReport'])->name('InventoryAgingReport');

Route::get('/loadInventoryAgingReport',[FabricInwardController::class,'loadInventoryAgingReport'])->name('loadInventoryAgingReport');  

Route::get('GetOpeningReportData',[FabricInwardController::class,'GetOpeningReportData'])->name('GetOpeningReportData');

Route::resource('RejectedPcsDeliveryChallan', RejectedPcsDeliveryChallanController::class);

Route::get('Reject_GetOrdarQtyByRow',[RejectedPcsDeliveryChallanController::class,'Reject_GetOrdarQtyByRow'])->name('Reject_GetOrdarQtyByRow');

Route::get('Reject_PC_GetOrderQty',[RejectedPcsDeliveryChallanController::class,'Reject_PC_GetOrderQty'])->name('Reject_PC_GetOrderQty');

Route::post('rptCuttingOCR1',[BuyerPurchaseOrderController::class,'rptCuttingOCR1'])->name('rptCuttingOCR1');

Route::resource('StockAssociation', StockAssociationController::class);

Route::get('GetAllocatedStockData',[StockAssociationController::class,'GetAllocatedStockData'])->name('GetAllocatedStockData');

Route::get('GetItemDataFromDetail',[StockAssociationController::class,'GetItemDataFromDetail'])->name('GetItemDataFromDetail');

Route::get('rptFabricAssocation',[StockAssociationForFabricController::class,'rptFabricAssocation'])->name('rptFabricAssocation');

Route::get('DumpFabricStockAssocation',[StockAssociationForFabricController::class,'DumpFabricStockAssocation'])->name('DumpFabricStockAssocation');

Route::post('StoreFabricPopupData',[StockAssociationForFabricController::class,'StoreFabricPopupData'])->name('StoreFabricPopupData');

Route::resource('StockAssociationForFabric', StockAssociationForFabricController::class);

Route::get('getPoForFabric',[FabricSummaryGRNController::class,'getPoForFabric'])->name('getPoForFabric');
Route::get('stockAllocateForFabric',[FabricSummaryGRNController::class,'stockAllocateForFabric'])->name('stockAllocateForFabric');

Route::get('GetAllocatedFabricStockData',[StockAssociationForFabricController::class,'GetAllocatedFabricStockData'])->name('GetAllocatedFabricStockData');

Route::get('GetItemFabricDataFromDetail',[StockAssociationForFabricController::class,'GetItemFabricDataFromDetail'])->name('GetItemFabricDataFromDetail');

Route::get('GetWorkOrderDeviation',[VendorWorkOrderController::class,'GetWorkOrderDeviation'])->name('GetWorkOrderDeviation');

Route::post('rptWorkOrderDeviation',[VendorWorkOrderController::class,'rptWorkOrderDeviation'])->name('rptWorkOrderDeviation');

Route::resource('ReturnPackingInhouseMaster', ReturnPackingInhouseMasterController::class);

Route::get('GetSaleInvoices',[ReturnPackingInhouseMasterController::class,'GetSaleInvoices'])->name('GetSaleInvoices');

Route::get('/Op_ReturnGetOrderQty',[ReturnPackingInhouseMasterController::class,'Op_ReturnGetOrderQty'])->name('Op_ReturnGetOrderQty');

Route::get('/GetTaxType',[ReturnPackingInhouseMasterController::class,'GetTaxType'])->name('GetTaxType');

Route::get('/PrintReturnPackingInhouse/{id}',[ReturnPackingInhouseMasterController::class,'PrintReturnPackingInhouse'])->name('PrintReturnPackingInhouse');


// Delivery Challan Start
Route::resource('DeliveryChallan', DeliveryChallanController::class);

Route::get('getAddressForDC',[DeliveryChallanController::class,'getAddressForDC'])->name('getAddressForDC');

Route::get('getDeliveryChallan',[DeliveryChallanController::class,'getDeliveryChallan'])->name('getDeliveryChallan');

Route::get('getDeliveryChallanDetailsData',[DeliveryChallanController::class,'getDeliveryChallanDetailsData'])->name('getDeliveryChallanDetailsData');

Route::get('DeliveryChallanPrint/{id}',[DeliveryChallanController::class,'show']);
// Delivery Challan End

Route::get('GetVendorBuyerWiseData',[DeliveryChallanController::class,'GetVendorBuyerWiseData'])->name('GetVendorBuyerWiseData');

Route::get('Get_Gate_Pass1',[DeliveryChallanController::class,'Get_Gate_Pass1'])->name('Get_Gate_Pass1');
Route::post('rptGatePass1',[DeliveryChallanController::class,'rptGatePass1'])->name('rptGatePass1');

Route::get('Get_Gate_Pass2',[DeliveryChallanController::class,'Get_Gate_Pass2'])->name('Get_Gate_Pass2');
Route::post('rptGatePass2',[DeliveryChallanController::class,'rptGatePass2'])->name('rptGatePass2');

Route::get('Get_Gate_Pass3',[DeliveryChallanController::class,'Get_Gate_Pass3'])->name('Get_Gate_Pass3');
Route::post('rptGatePass3',[DeliveryChallanController::class,'rptGatePass3'])->name('rptGatePass3');

Route::resource('StitchingDefect', StitchingDefectController::class);
Route::resource('StitchingOperation',  StitchingOperationController::class);
Route::resource('DHU', DHUController::class);

Route::get('GetDHUReport',[DHUController::class,'GetDHUReport'])->name('GetDHUReport');
Route::post('rptDHU',[DHUController::class,'rptDHU'])->name('rptDHU');
Route::get('GetDHUMainStyleList',[DHUController::class,'GetDHUMainStyleList'])->name('GetDHUMainStyleList');
Route::get('GetDHUDefectList',[StitchingOperationController::class,'GetDHUDefectList'])->name('GetDHUDefectList');

Route::get('Get_Produced_Minutes_report',[PPCMasterController::class,'Get_Produced_Minutes_report'])->name('Get_Produced_Minutes_report');

Route::post('rptProducedMinutes',[PPCMasterController::class,'rptProducedMinutes'])->name('rptProducedMinutes');


Route::resource('KDPLWiseSetPercentage', KDPLWiseSetPercentageController::class);


Route::get('Get_WIP_Report3',[PPCMasterController::class,'Get_WIP_Report3'])->name('Get_WIP_Report3');

Route::get('rptWIPReportss3',[PPCMasterController::class,'rptWIPReportss3'])->name('rptWIPReportss3');
Route::get('GetWIPInOutStockReportForm',[PPCMasterController::class,'GetWIPInOutStockReportForm'])->name('GetWIPInOutStockReportForm');
Route::get('WIPInOutStockReport',[PPCMasterController::class,'WIPInOutStockReport'])->name('WIPInOutStockReport');
Route::get('WIPInOutStockReportList', [PPCMasterController::class, 'WIPInOutStockReportList'])->name('WIPInOutStockReportList');
Route::get('rptWIPReportss4',[PPCMasterController::class,'rptWIPReportss4'])->name('rptWIPReportss4');

Route::resource('WashingInhouse', WashingInhouseController::class);
Route::get('WashingGRNPrint/{id}',[WashingInhouseController::class,'WashingGRNPrint'])->name('WashingGRNPrint');
Route::get('/vpo_GetWashingPOQty',[WashingInhouseController::class,'vpo_GetWashingPOQty'])->name('vpo_GetWashingPOQty');
Route::get('/WashingInwardReport',[WashingInhouseController::class,'WashingInwardReport'])->name('WashingInwardReport');
Route::get('/WashingOutwardReport',[WashingInhouseController::class,'WashingOutwardReport'])->name('WashingOutwardReport');
Route::get('/WashingInwardOutwardReport',[WashingInhouseController::class,'WashingInwardOutwardReport'])->name('WashingInwardOutwardReport');

Route::resource('OCR', OCRController::class);

Route::resource('OperationName', OperationNameController::class);
Route::resource('Operation', OperationController::class);
Route::get('/GetOperationList',[OperationController::class,'GetOperationList'])->name('GetOperationList');
Route::get('/GetMainstyleFromKDPL',[OperationController::class,'GetMainstyleFromKDPL'])->name('GetMainstyleFromKDPL');

Route::resource('CuttingEntry', CuttingEntryController::class);
Route::get('/GetEmpList',[CuttingEntryController::class,'GetEmpList'])->name('GetEmpList');
Route::get('/GetBuyerPurchaseData',[CuttingEntryController::class,'GetBuyerPurchaseData'])->name('GetBuyerPurchaseData');
Route::get('/GetPartList',[CuttingEntryController::class,'GetPartList'])->name('GetPartList');
Route::get('/GetCuttingOperationList',[CuttingEntryController::class,'GetCuttingOperationList'])->name('GetCuttingOperationList');
Route::get('/checkDuplicateBundleNo',[CuttingEntryController::class,'checkDuplicateBundleNo'])->name('checkDuplicateBundleNo');
Route::get('/cutting_slip/{id}',[CuttingEntryController::class,'cutting_slip']);
Route::get('get_cutting_detail',[CuttingEntryController::class,'get_cutting_detail'])->name('get_cutting_detail');
Route::post('show_cutting_detail',[CuttingEntryController::class,'show_cutting_detail'])->name('show_cutting_detail');
Route::get('cutting_bundle_report',[CuttingEntryController::class,'cutting_bundle_report'])->name('cutting_bundle_report');

Route::resource('DailyProductionEntry', DailyProductionEntryController::class);
Route::get('/GetCuttingEntryData',[DailyProductionEntryController::class,'GetCuttingEntryData'])->name('GetCuttingEntryData');
Route::get('/GetDailyProductionOperationList',[DailyProductionEntryController::class,'GetDailyProductionOperationList'])->name('GetDailyProductionOperationList');
Route::get('/employeeWiseDetailReport',[DailyProductionEntryController::class,'employeeWiseDetailReport'])->name('employeeWiseDetailReport');
Route::get('/GetEmployeeWiseDetailReport',[DailyProductionEntryController::class,'GetEmployeeWiseDetailReport'])->name('GetEmployeeWiseDetailReport');
Route::get('/GetHRMSCompanyList',[DailyProductionEntryController::class,'GetHRMSCompanyList'])->name('GetHRMSCompanyList');
Route::get('/GetHRMSSubCompanyList',[DailyProductionEntryController::class,'GetHRMSSubCompanyList'])->name('GetHRMSSubCompanyList');
Route::get('/GetHRMSEmployeeList',[DailyProductionEntryController::class,'GetHRMSEmployeeList'])->name('GetHRMSEmployeeList');
Route::get('/GetEmpWiseSalesOrderList',[DailyProductionEntryController::class,'GetEmpWiseSalesOrderList'])->name('GetEmpWiseSalesOrderList');
Route::get('/GetEmpWiseMainStyleList',[DailyProductionEntryController::class,'GetEmpWiseMainStyleList'])->name('GetEmpWiseMainStyleList');
Route::get('/DailyOperatorsLineWise',[DailyProductionEntryController::class,'DailyOperatorsLineWise'])->name('DailyOperatorsLineWise');
Route::get('/SetDailyOperator',[DailyProductionEntryController::class,'SetDailyOperator'])->name('SetDailyOperator');
Route::get('/GetHRMSDepartmentList',[DailyProductionEntryController::class,'GetHRMSDepartmentList'])->name('GetHRMSDepartmentList');
Route::get('/GetHRMSBranchList',[DailyProductionEntryController::class,'GetHRMSBranchList'])->name('GetHRMSBranchList'); 
Route::get('/searchDailyOperatorList',[DailyProductionEntryController::class,'searchDailyOperatorList'])->name('searchDailyOperatorList');
Route::get('/productionCostReport',[DailyProductionEntryController::class,'productionCostReport'])->name('productionCostReport');
Route::get('/EmployeeListCostingWise/{id}',[DailyProductionEntryController::class,'EmployeeListCostingWise'])->name('EmployeeListCostingWise');
Route::get('/operationWiseProductionList',[DailyProductionEntryController::class,'operationWiseProductionList'])->name('operationWiseProductionList');
Route::post('/operation_list',[DailyProductionEntryController::class,'operation_list'])->name('operation_list');
Route::post('/get_groups',[DailyProductionEntryController::class,'get_groups'])->name('get_groups');
Route::post('/get_rates',[DailyProductionEntryController::class,'get_rates'])->name('get_rates');
Route::get('/EmployeeDateWiseSalary',[DailyProductionEntryController::class,'EmployeeDateWiseSalary'])->name('EmployeeDateWiseSalary');
Route::get('/EmployeeDetailedSalaryReport',[DailyProductionEntryController::class,'EmployeeDetailedSalaryReport'])->name('EmployeeDetailedSalaryReport');
Route::get('/EmployeeDetailedSalaryReportPrint',[DailyProductionEntryController::class,'EmployeeDetailedSalaryReportPrint'])->name('EmployeeDetailedSalaryReportPrint');
Route::get('/EmployeeDetailedProductionReport',[DailyProductionEntryController::class,'EmployeeDetailedProductionReport'])->name('EmployeeDetailedProductionReport');

Route::get('/pcs_rate_salary_report',[DailyProductionEntryController::class,'pcs_rate_salary_report'])->name('pcs_rate_salary_report');
Route::get('/pcs_rate_salary_report_print',[DailyProductionEntryController::class,'pcs_rate_salary_report_print'])->name('pcs_rate_salary_report_print');
Route::get('/bundle_pending_for_production',[DailyProductionEntryController::class,'bundle_pending_for_production'])->name('bundle_pending_for_production');
Route::get('/bundle_pending_for_production_print',[DailyProductionEntryController::class,'bundle_pending_for_production_print'])->name('bundle_pending_for_production_print');
Route::post('get_styles',[DailyProductionEntryController::class,'get_styles'])->name('get_styles');

Route::get('/EmployeeDetailedProductionExport/{id1}/{id2}/{id3}/{id4}/{id5}/{id6}',[DailyProductionEntryController::class,'EmployeeDetailedProductionExport']);
Route::get('/EmployeeDetailedSalaryReportExport/{id1}/{id2}/{id3}/{id4}/{id5}',[DailyProductionEntryController::class,'EmployeeDetailedSalaryReportExport']);
Route::get('/employee_detailed_production_export/{id1}/{id2}/{id3}/{id4}',[DailyProductionEntryController::class,'employee_detailed_production_export']);
Route::post('previous_production_exist_record',[DailyProductionEntryController::class,'previous_production_exist_record'])->name('previous_production_exist_record');



Route::controller(HourlyProductionEntryController::class)->group(function () {
      Route::resource('hourly_production', HourlyProductionEntryController::class);
      Route::get('get_attendance','get_attendance')->name('get_attendance'); 
      Route::post('attendance_import','attendance_import')->name('attendance_import');
      Route::post('attendance_delete','attendance_delete')->name('attendance_delete');  
      Route::get('get_attendance/{id}','show');  
      Route::post('get_hourly_production_table_by_operator_new','get_hourly_production_table_by_operator_new')->name('get_hourly_production_table_by_operator_new');  
      Route::post('store_update_hourly_production','store_update_hourly_production')->name('store_update_hourly_production');    
      Route::post('delete_operator','delete_operator')->name('delete_operator');     
      Route::get('get_hourly_operation_production','get_hourly_operation_production')->name('get_hourly_operation_production'); 
      Route::post('show_hourly_operation_production','show_hourly_operation_production')->name('show_hourly_operation_production'); 
      Route::get('get_hourly_operation_production_detail','get_hourly_operation_production_detail')->name('get_hourly_operation_production_detail'); 
      Route::post('show_hourly_operation_production_detail','show_hourly_operation_production_detail')->name('show_hourly_operation_production_detail'); 
      Route::post('update_hourly_production_down_time','update_hourly_production_down_time')->name('update_hourly_production_down_time');    
      
});


Route::controller(POAuthorityMatrixController::class)->group(function () {
      Route::resource('po_authority_matrix', POAuthorityMatrixController::class);
     
});

Route::controller(BarcodeBrandController::class)->group(function () {
      Route::resource('barcode_brand', BarcodeBrandController::class);
      Route::get('barcode_brand_print/{id}','barcode_brand_print'); 
      
     
});


Route::controller(SOPurchaseOrderAuthorityMatrixController::class)->group(function () {
      Route::resource('so_po_authority_matrix', SOPurchaseOrderAuthorityMatrixController::class);
      Route::post('get_item_codes','get_item_codes')->name('get_item_codes');  
      Route::post('get_item_details','get_item_details')->name('get_item_details');  
      Route::post('po_matrix_detail','po_matrix_detail')->name('po_matrix_detail');   
      Route::get('handsontable','handsontable')->name('handsontable');      
      Route::get('get_report_data','get_report_data')->name('get_report_data');    
      Route::post('saveExcelData','saveExcelData')->name('saveExcelData'); 
      Route::get('load_data','load_data')->name('load_data');      
     Route::post('bulkUpdateExcelData','bulkUpdateExcelData')->name('bulkUpdateExcelData');    
     
});


  


Route::resource('BuyerCosting', BuyerCostingController::class);
Route::get('/BuyerCostingPrint/{id}',[BuyerCostingController::class,'BuyerCostingPrint']);
Route::get('/RepeatBuyerCostingEdit',[BuyerCostingController::class,'RepeatBuyerCostingEdit'])->name('RepeatBuyerCostingEdit');
Route::post('/RepeatBuyerCostingUpdate',[BuyerCostingController::class,'RepeatBuyerCostingUpdate'])->name('RepeatBuyerCostingUpdate');
Route::get('/ReviseBuyerCostingEdit',[BuyerCostingController::class,'ReviseBuyerCostingEdit'])->name('ReviseBuyerCostingEdit');
Route::post('/ReviseBuyerCostingUpdate',[BuyerCostingController::class,'ReviseBuyerCostingUpdate'])->name('ReviseBuyerCostingUpdate');
Route::resource('Employee', EmployeeMasterController::class);
Route::get('/MonthlyOrderStatusReport',[BuyerPurchaseOrderController::class,'MonthlyOrderStatusReport'])->name('MonthlyOrderStatusReport');

Route::resource('WIPAdjustableQty', WIPAdjustableQtyController::class);
Route::resource('PackingRejection', PackingRejectionController::class);
Route::get('/GetPackingOrderDetails',[PackingRejectionController::class,'GetPackingOrderDetails'])->name('GetPackingOrderDetails');
Route::get('/Packing_GetOrderQty',[PackingRejectionController::class,'Packing_GetOrderQty'])->name('Packing_GetOrderQty');
Route::get('/PackingRejectionOrderQty',[PackingRejectionController::class,'PackingRejectionOrderQty'])->name('PackingRejectionOrderQty');
Route::get('/PackingRejectionReport',[PackingRejectionController::class,'PackingRejectionReport'])->name('PackingRejectionReport');
Route::resource('BuyerBrandAuth', BuyerBrandAuthController::class);
Route::resource('MaterialIssue', MaterialIssueController::class);
Route::get('/SaveMaterialIssue',[MaterialIssueController::class,'SaveMaterialIssue'])->name('SaveMaterialIssue');
Route::resource('FGLocationTransferInward', FGLocationTransferInwardMasterController::class);

Route::get('/FGLocationTransferInwardPrint/{id}',[FGLocationTransferInwardMasterController::class,'FGLocationTransferInwardPrint']); 
Route::get('/GetFGLocOutwardData',[FGLocationTransferInwardMasterController::class,'GetFGLocOutwardData'])->name('GetFGLocOutwardData');
Route::get('/FGLocationTransferInwardBarcode/{id}',[FGLocationTransferInwardMasterController::class,'FGLocationTransferInwardBarcode'])->name('FGLocationTransferInwardBarcode');
$route['generate-barcode'] = 'FGLocationTransferInwardMasterController/generateBarcode';
Route::get('/OutletInwardReport',[FGLocationTransferInwardMasterController::class,'OutletInwardReport'])->name('OutletInwardReport');
Route::get('/OutletStockReport',[FGLocationTransferInwardMasterController::class,'OutletStockReport'])->name('OutletStockReport');

Route::resource('OutletSale', OutletSaleController::class);
Route::get('/GetEmployeeDetails',[OutletSaleController::class,'GetEmployeeDetails'])->name('GetEmployeeDetails');
Route::get('/GetBarcodeDetails',[OutletSaleController::class,'GetBarcodeDetails'])->name('GetBarcodeDetails');
Route::get('/GetBarcodeDetailsTest',[OutletSaleController::class,'GetBarcodeDetailsTest'])->name('GetBarcodeDetailsTest');
Route::get('/OutletSalePrint/{id}',[OutletSaleController::class,'OutletSalePrint'])->name('OutletSalePrint');
Route::get('/OutletSaleReport',[OutletSaleController::class,'OutletSaleReport'])->name('OutletSaleReport');
Route::get('/OutletEmployeeWiseReport',[OutletSaleController::class,'OutletEmployeeWiseReport'])->name('OutletEmployeeWiseReport');
Route::get('/OutletUI',[OutletSaleController::class,'OutletUI'])->name('OutletUI');
Route::get('/BrandWiseInwardOutwardReport',[OutletSaleController::class,'BrandWiseInwardOutwardReport'])->name('BrandWiseInwardOutwardReport');

Route::resource('FGOutletOpening', FGOutletOpeningController::class);
Route::get('/SizeOutletDetailList',[FGOutletOpeningController::class,'SizeOutletDetailList'])->name('SizeOutletDetailList');
Route::get('/FGOutletOpeningBarcode/{id}',[FGOutletOpeningController::class,'FGOutletOpeningBarcode'])->name('FGOutletOpeningBarcode');
Route::get('/FGOutletOpeningPrint/{id}',[FGOutletOpeningController::class,'FGOutletOpeningPrint'])->name('FGOutletOpeningPrint');
Route::get('/GetFGOpeningOutwardData',[FGOutletOpeningController::class,'GetFGOpeningOutwardData'])->name('GetFGOpeningOutwardData');

Route::resource('SampleType', SampleTypeController::class);
Route::resource('SampleIndent', SampleIndentController::class);
Route::get('/SizeSampleIndentList',[SampleIndentController::class,'SizeSampleIndentList'])->name('SizeSampleIndentList');
Route::get('/SampleIndentPrint/{id}',[SampleIndentController::class,'SampleIndentPrint'])->name('SampleIndentPrint');
Route::get('/GetDepartmentType',[SampleIndentController::class,'GetDepartmentType'])->name('GetDepartmentType');
Route::get('/rptSamplingStatus',[SampleIndentController::class,'rptSamplingStatus'])->name('rptSamplingStatus');
Route::resource('SampleCadDept', SampleCadDeptController::class); 
Route::get('/GetSampleIndentMasterData',[SampleCadDeptController::class,'GetSampleIndentMasterData'])->name('GetSampleIndentMasterData');
Route::resource('SampleQcDept', SampleQcDeptController::class); 
Route::get('/GetSampleIndentMasterQCData',[SampleQcDeptController::class,'GetSampleIndentMasterQCData'])->name('GetSampleIndentMasterQCData');
Route::resource('SampleCustomerFeedback', SampleCustomerFeedbackController::class); 
Route::get('/GetSINCodeList',[SampleCustomerFeedbackController::class,'GetSINCodeList'])->name('GetSINCodeList');
Route::get('/GetSampleIndentMasterCustomerData',[SampleCustomerFeedbackController::class,'GetSampleIndentMasterCustomerData'])->name('GetSampleIndentMasterCustomerData');
Route::get('/SampleCustomerFeedbackPrint/{id}',[SampleCustomerFeedbackController::class,'SampleCustomerFeedbackPrint'])->name('SampleCustomerFeedbackPrint');

Route::get('/DeleteSampleQcAttachment',[SampleQcDeptController::class,'DeleteSampleQcAttachment'])->name('DeleteSampleQcAttachment');
Route::get('/GetSINCodeForTrimOutwardList',[TrimsOutwardController::class,'GetSINCodeForTrimOutwardList'])->name('GetSINCodeForTrimOutwardList');
Route::get('/GetSINCodeWiseSampleData',[TrimsOutwardController::class,'GetSINCodeWiseSampleData'])->name('GetSINCodeWiseSampleData');
Route::get('/GetPOListFromItemCode',[TrimsOutwardController::class,'GetPOListFromItemCode'])->name('GetPOListFromItemCode');
Route::get('/getItemPORate',[TrimsOutwardController::class,'getItemPORate'])->name('getItemPORate');
Route::resource('FinishingRate', FinishingRateController::class);  
Route::resource('Perticular', PerticularController::class); 
Route::resource('FinishingBilling', FinishingBillingController::class); 
Route::get('/GetPerticularCode',[FinishingBillingController::class,'GetPerticularCode'])->name('GetPerticularCode');
Route::get('/GetPackingQtySalesOrderWise',[FinishingBillingController::class,'GetPackingQtySalesOrderWise'])->name('GetPackingQtySalesOrderWise');
Route::get('/FinishingBillingPrint/{id}',[FinishingBillingController::class,'FinishingBillingPrint'])->name('FinishingBillingPrint');
Route::resource('Lead', LeadController::class); 
Route::get('/CRMReportPrint',[LeadController::class,'CRMReportPrint'])->name('CRMReportPrint');
Route::resource('InwardForPacking', InwardForPackingController::class); 
Route::get('/vw_GetInwardForPackingPOQty',[InwardForPackingController::class,'vw_GetInwardForPackingPOQty'])->name('vw_GetInwardForPackingPOQty');
Route::get('/InwardForPackingPrint/{id}',[InwardForPackingController::class,'InwardForPackingPrint'])->name('InwardForPackingPrint');
Route::get('/getOutwardForPackingDetails',[OutwardForPackingMasterController::class,'getOutwardForPackingDetails'])->name('getOutwardForPackingDetails');
Route::get('/DemoExcel',[BuyerPurchaseOrderController::class,'DemoExcel'])->name('DemoExcel');
Route::get('/DExcel1',[BuyerPurchaseOrderController::class,'DExcel1'])->name('DExcel1');
Route::get('/GaneshExcel',[BuyerPurchaseOrderController::class,'GaneshExcel'])->name('GaneshExcel');
Route::resource('Opportunity', OpportunityController::class); 
Route::get('/OpportunityPrint/{id}',[OpportunityController::class,'OpportunityPrint'])->name('OpportunityPrint');
Route::get('/OpportunityEdit',[OpportunityController::class,'OpportunityEdit'])->name('OpportunityEdit');
Route::get('/OpportunityCreate',[OpportunityController::class,'OpportunityCreate'])->name('OpportunityCreate');
Route::post('/OpportunityStore',[OpportunityController::class,'OpportunityStore'])->name('OpportunityStore');
Route::post('/OpportunityDetailStore',[OpportunityController::class,'OpportunityDetailStore'])->name('OpportunityDetailStore');
Route::post('/OpportunityDetailUpdate',[OpportunityController::class,'OpportunityDetailUpdate'])->name('OpportunityDetailUpdate');
Route::post('/OpportunityMasterUpdate',[OpportunityController::class,'OpportunityMasterUpdate'])->name('OpportunityMasterUpdate');  
Route::get('/DeleteOpportunityDetail',[OpportunityController::class,'DeleteOpportunityDetail'])->name('DeleteOpportunityDetail');
Route::get('/getVendorWorkOrderDetailsForPacking',[OutwardForPackingMasterController::class,'getVendorWorkOrderDetailsForPacking'])->name('getVendorWorkOrderDetailsForPacking');
Route::get('/GetOutwardForPackingPOQty',[InwardForPackingController::class,'GetOutwardForPackingPOQty'])->name('GetOutwardForPackingPOQty');
Route::get('/GetInwardForPackingPOQty',[InwardForPackingController::class,'GetInwardForPackingPOQty'])->name('GetInwardForPackingPOQty');
Route::resource('MaterialReturn',MaterialReturnController::class);
Route::resource('MaterialTransferFrom',MaterialTransferFromController::class);
Route::get('/GetMaterialInwardOutwardStock',[MaterialTransferFromController::class,'GetMaterialInwardOutwardStock'])->name('GetMaterialInwardOutwardStock');
Route::get('/MaterialTransferPrint/{id}',[MaterialTransferFromController::class,'MaterialTransferPrint'])->name('MaterialTransferPrint');
Route::resource('MaterialTransferFromInward',MaterialTransferFromInwardController::class);
Route::get('/DeleteMaterialTransferFromInwardAttachment',[MaterialTransferFromInwardController::class,'DeleteMaterialTransferFromInwardAttachment'])->name('DeleteMaterialTransferFromInwardAttachment');
Route::get('/GetMaterialTransferFromData',[MaterialTransferFromInwardController::class,'GetMaterialTransferFromData'])->name('GetMaterialTransferFromData');
Route::get('/GetPOListFromSpareItemCode',[MaterialTransferFromController::class,'GetPOListFromSpareItemCode'])->name('GetPOListFromSpareItemCode');
Route::get('/GetSpareMaterialTransferFromStock',[MaterialTransferFromController::class,'GetSpareMaterialTransferFromStock'])->name('GetSpareMaterialTransferFromStock');
Route::post('unit_wise_pl_register',[DailyProductionEntry::class,'unit_wise_pl_register'])->name('unit_wise_pl_register');
Route::post('load_pl_register_by_unit',[DailyProductionEntry::class,'load_pl_register_by_unit'])->name('load_pl_register_by_unit');
Route::get('get_linewise_efficiency_yearly',[DailyProductionEntry::class,'get_linewise_efficiency_yearly'])->name('get_linewise_efficiency_yearly');
Route::post('show_line_wise_efficiency_yearly',[DailyProductionEntry::class,'show_line_wise_efficiency_yearly'])->name('show_line_wise_efficiency_yearly');
Route::post('get_line_list',[DailyProductionEntry::class,'get_line_list'])->name('get_line_list');

Route::resource('SpareItem',SpareItemController::class);
Route::resource('SparePurchaseOrder',SparePurchaseOrderController::class);
Route::get('GetSpareItemDetail',[SparePurchaseOrderController::class,'GetSpareItemDetail'])->name('GetSpareItemDetail');
Route::get('GetSpareItemMasterData',[SparePurchaseOrderController::class,'GetSpareItemMasterData'])->name('GetSpareItemMasterData');
Route::get('SparePurchaseOrderPrint/{id}',[SparePurchaseOrderController::class,'SparePurchaseOrderPrint'])->name('SparePurchaseOrderPrint');
 
Route::resource('MachineModel',MachineModelMasterController::class);

Route::resource('WashType',WashTypeController::class);


Route::resource('FabricGateEntry', FabricGateEntryController::class);
Route::get('GetItemDetails',[FabricGateEntryController::class,'GetItemDetails'])->name('GetItemDetails');
Route::get('FabricGateEntryReport',[FabricGateEntryController::class,'FabricGateEntryReport'])->name('FabricGateEntryReport');
Route::get('GetPOApproveStatus',[FabricGateEntryController::class,'GetPOApproveStatus'])->name('GetPOApproveStatus');
Route::resource('TrimGateEntry', TrimsGateEntryController::class);
Route::get('TrimsGateEntryReport',[TrimsGateEntryController::class,'TrimsGateEntryReport'])->name('TrimsGateEntryReport');
Route::get('TrimGateEntryShowAll',[TrimsGateEntryController::class,'TrimGateEntryShowAll'])->name('TrimGateEntryShowAll');
Route::get('GetPurchaseBillToDetails',[TrimsGateEntryController::class,'GetPurchaseBillToDetails'])->name('GetPurchaseBillToDetails');

Route::resource('FabricInwardCuttingDepartment', FabricInwardCuttingDepartmentController::class);
Route::get('GetFabricOutwardData',[FabricInwardCuttingDepartmentController::class,'GetFabricOutwardData'])->name('GetFabricOutwardData');
Route::get('FabricStockCuttingWIP',[FabricInwardCuttingDepartmentController::class,'FabricStockCuttingWIP'])->name('FabricStockCuttingWIP');

Route::resource('FabricOutwardCuttingDepartment', FabricOutwardCuttingDepartmentController::class);
Route::get('GetFabricOutwardCuttingData',[FabricOutwardCuttingDepartmentController::class,'GetFabricOutwardCuttingData'])->name('GetFabricOutwardCuttingData');
Route::get('FabricCuttingWIP',[FabricOutwardCuttingDepartmentController::class,'FabricCuttingWIP'])->name('FabricCuttingWIP');

//HRMS OPERATION ROUTE START

Route::resource('ob', OBMasterController::class);
Route::post('get_cat_sub_cat_by_style',[OBMasterController::class,'get_cat_sub_cat_by_style'])->name('get_cat_sub_cat_by_style');
Route::post('ob_import',[OBMasterController::class,'ob_import'])->name('ob_import');
Route::post('delete_operation',[OBMasterController::class,'delete_operation'])->name('delete_operation');


Route::resource('assign_to_order', AssignToOrderController::class);
Route::resource('line_plan', LinePlanController::class);
Route::post('get_selected_operator',[LinePlanController::class,'get_selected_operator'])->name('get_selected_operator');
Route::post('get_sales_order_by_style',[AssignToOrderController::class,'get_sales_order_by_style'])->name('get_sales_order_by_style');

Route::post('get_operation_detail',[LinePlanController::class,'get_operation_detail'])->name('get_operation_detail');
Route::post('get_operation_ids',[LinePlanController::class,'get_operation_ids'])->name('get_operation_ids');
Route::post('get_line_table',[LinePlanController::class,'get_line_table'])->name('get_line_table');
Route::get('line_wise_operator', [LinePlanController::class,'line_wise_operator'])->name('line_wise_operator');

Route::get('GetEmpDetailFromEmpCode',[LinePlanController::class,'GetEmpDetailFromEmpCode'])->name('GetEmpDetailFromEmpCode');

Route::resource('daily_production_entry',DailyProductionEntry::class);

Route::post('check_exists_production',[DailyProductionEntry::class,'check_exists_production'])->name('check_exists_production');
Route::post('get_operators',[DailyProductionEntry::class,'get_operators'])->name('get_operators');
Route::post('get_over_all_sam',[DailyProductionEntry::class,'get_over_all_sam'])->name('get_over_all_sam');
Route::post('get_daily_production_table_by_operator',[DailyProductionEntry::class,'get_daily_production_table_by_operator'])->name('get_daily_production_table_by_operator');
Route::post('get_daily_production_table_by_operator_new',[DailyProductionEntry::class,'get_daily_production_table_by_operator_new'])->name('get_daily_production_table_by_operator_new');
Route::post('get_operator_detail',[DailyProductionEntry::class,'get_operator_detail'])->name('get_operator_detail');
Route::post('get_operation_ids_by_operator',[DailyProductionEntry::class,'get_operation_ids_by_operator'])->name('get_operation_ids_by_operator');
Route::post('get_group_ids_by_line',[DailyProductionEntry::class,'get_group_ids_by_line'])->name('get_group_ids_by_line');
Route::post('check_exist_record',[DailyProductionEntry::class,'check_exist_record'])->name('check_exist_record');
Route::get('get_daily_production',[DailyProductionEntry::class,'get_daily_production'])->name('get_daily_production');
Route::post('show_daily_production',[DailyProductionEntry::class,'show_daily_production'])->name('show_daily_production');
Route::post('get_employee_sub_company',[DailyProductionEntry::class,'get_employee_sub_company'])->name('get_employee_sub_company');
Route::get('get_unitwise_efficiency',[DailyProductionEntry::class,'get_unitwise_efficiency'])->name('get_unitwise_efficiency');
Route::post('unit_wise_efficiency',[DailyProductionEntry::class,'unit_wise_efficiency'])->name('unit_wise_efficiency');
Route::post('get_operators_list',[DailyProductionEntry::class,'get_operators_list'])->name('get_operators_list');
Route::post('load_efficiency_by_unit',[DailyProductionEntry::class,'load_efficiency_by_unit'])->name('load_efficiency_by_unit');
Route::post('load_efficiency_by_unit_optimize',[DailyProductionEntry::class,'load_efficiency_by_unit_optimize'])->name('load_efficiency_by_unit_optimize');

Route::post('get_range_wise_operators',[DailyProductionEntry::class,'get_range_wise_operators'])->name('get_range_wise_operators');
Route::post('get_eff_datewise_operators',[DailyProductionEntry::class,'get_eff_datewise_operators'])->name('get_eff_datewise_operators');
Route::post('get_date_wise_operation_detail',[DailyProductionEntry::class,'get_date_wise_operation_detail'])->name('get_date_wise_operation_detail');
Route::get('get_linewise_efficiency',[DailyProductionEntry::class,'get_linewise_efficiency'])->name('get_linewise_efficiency');
Route::post('line_wise_efficiency',[DailyProductionEntry::class,'line_wise_efficiency'])->name('line_wise_efficiency');
Route::get('get_top_n_bottom_n',[DailyProductionEntry::class,'get_top_n_bottom_n'])->name('get_top_n_bottom_n');
Route::post('show_top_n_bottom_n_efficiency',[DailyProductionEntry::class,'show_top_n_bottom_n_efficiency'])->name('show_top_n_bottom_n_efficiency');
Route::get('get_unitwise_pl',[DailyProductionEntry::class,'get_unitwise_pl'])->name('get_unitwise_pl');
Route::post('unit_wise_pl',[DailyProductionEntry::class,'unit_wise_pl'])->name('unit_wise_pl');
Route::post('load_pl_by_unit',[DailyProductionEntry::class,'load_pl_by_unit'])->name('load_pl_by_unit');
Route::get('get_unitwise_pl_register',[DailyProductionEntry::class,'get_unitwise_pl_register'])->name('get_unitwise_pl_register');
Route::post('unit_wise_pl_register',[DailyProductionEntry::class,'unit_wise_pl_register'])->name('unit_wise_pl_register');
Route::get('show_line_wise_efficiency_monthly/{id}/{id1}',[DailyProductionEntry::class,'show_line_wise_efficiency_monthly']);
Route::get('show_line_wise_efficiency_weekly_new/{id1}/{id2}/{id3}',[DailyProductionEntry::class,'show_line_wise_efficiency_weekly_new']);
Route::get('get_linewise_efficiency_weekly',[DailyProductionEntry::class,'get_linewise_efficiency_weekly'])->name('get_linewise_efficiency_weekly');
Route::post('show_line_wise_efficiency_weekly',[DailyProductionEntry::class,'show_line_wise_efficiency_weekly'])->name('show_line_wise_efficiency_weekly');
Route::get('get_all_unit_efficiency_yearly',[DailyProductionEntry::class,'get_all_unit_efficiency_yearly'])->name('get_all_unit_efficiency_yearly');
Route::post('show_line_wise_efficiency_yearly_all_unit',[DailyProductionEntry::class,'show_line_wise_efficiency_yearly_all_unit'])->name('show_line_wise_efficiency_yearly_all_unit');
Route::get('show_all_unit_wise_efficiency_monthly/{id}',[DailyProductionEntry::class,'show_all_unit_wise_efficiency_monthly']);
Route::get('show_all_unitwise_efficiency_weekly/{id1}/{id2}',[DailyProductionEntry::class,'show_all_unitwise_efficiency_weekly']);
Route::get('show_all_unit_and_datewise_efficiency/{id}/{id1}',[DailyProductionEntry::class,'show_all_unit_and_datewise_efficiency']);
Route::resource('Style', StyleMasterController::class);
Route::get('/rate_chart/{id}',[StyleMasterController::class,'rate_chart']);
Route::get('get_routes',[DailyProductionEntry::class,'get_routes'])->name('get_routes');
Route::post('unit_wise_efficiency_pcs',[DailyProductionEntry::class,'unit_wise_efficiency_pcs'])->name('unit_wise_efficiency_pcs');
Route::post('load_efficiency_by_unit_pc',[DailyProductionEntry::class,'load_efficiency_by_unit_pc'])->name('load_efficiency_by_unit_pc');
Route::post('get_date_wise_operation_detail_pcs',[DailyProductionEntry::class,'get_date_wise_operation_detail_pcs'])->name('get_date_wise_operation_detail_pcs');


//END HRMS OPERATION ROUTE END



 

// Maintenance Module Route From Seaquid 02-12-2024


Route::resource('MachineLocation', MachineLocationMasterController::class);
Route::resource('MachineMake',MachineMakeMasterController::class);
Route::resource('MachineMainType',MachineMainTypeMasterController::class);
Route::resource('PreventiveName',PreventiveNameMasterController::class);
Route::resource('PurposeMaster',PurposeMasterController::class);
Route::resource('MacMaster',MachineMasterController::class); 
Route::resource('InwardRentedMachine',InwardRentedMachineController::class);
Route::get('maintance_dashboard',[InwardRentedMachineController::class,'maintance_dashboard']);
Route::get('viewmaindash',[InwardRentedMachineController::class,'viewmaindash'])->name('viewmaindash');
Route::resource('MachineTransfer',MachineTransferController::class);
Route::post('getmachinecode',[MachineTransferController::class, 'getmachinecode'])->name('getmachinecode');
Route::post('getmake',[MachineTransferController::class, 'getmake'])->name('getmake'); 
Route::resource('MachineryMaintance',MachineryMaintanceController::class);
Route::post('getmachinerycode',[MachineryMaintanceController::class, 'getmachinerycode'])->name('getmachinerycode');
Route::resource('MachineryPreventive', MachineryPreventiveController::class);
Route::resource('SparesReturnMaterialStatus',SparesReturnMaterialStatusController::class);
 
//End of Maintenance Routes
 
Route::controller(MonthlyBudgetController::class)->group(function () {
    Route::resource('monthly_budget', MonthlyBudgetController::class);
  //  Route::get('bulk_salary_summary','bulk_salary_summary')->name('bulk_salary_summary'); 
  
});

// Route::get('/FGLocationTransferInwardBarcode/{id}', [FGLocationTransferInwardMasterController::class, 'generateBarcodeLabel'])
//      ->name('generate.barcode.label');
});

Route::group(['middleware'=>['SetDatabaseForWebBuyer','buyer_auth']],function()
{ 
    Route::resource('BuyerPortal', BuyerPortalController::class); 
    Route::post('BuyerAuth',[BuyerPortalController::class,'BuyerAuth'])->name('BuyerAuth');
    Route::get('LoadDailyProdDashboard',[BuyerPortalController::class,'LoadDailyProdDashboard'])->name('LoadDailyProdDashboard');
});

 
// Route::post('/SendEmailToClientExhibition', function (Request $request) {
//     try {
//         $data = [
//             'firstName' => 'John',
//             'lastName' => 'Doe',
//             'subject' => 'Test Email',
//             'description' => 'This is a test email.'
//         ];

//         Mail::send('WebsiteOrderMail', $data, function($message) {
//             $message->from('bhikajikamble143@gmail.com')
//                     ->subject('Test Email')
//                     ->to('bhikajikamble143@gmail.com')
//                     ->setBody('Test body text');
//         });

//         return response()->json(['message' => 'Email sent successfully']);
//     } catch (\Exception $e) {
//         \Log::error('Error sending email: ' . $e->getMessage());
//         return response()->json(['error' => 'Failed to send email', 'message' => $e->getMessage()], 500);
//     }
// });

// Route::post('/SendEmailToClientExhibition', function (Request $request) {
//     try {
//         $data = [
//             'firstName' => 'John',
//             'lastName' => 'Doe',
//             'subject' => 'Test Email',
//             'description' => 'This is a test email.'
//         ];

//         Mail::send('WebsiteOrderMail', $data, function ($message) use ($data) {
//             $message->from('kenhrms@kenindia.in', 'Test')
//                     ->to('bhikajikamble143@gmail.com')
//                     ->subject($data['subject']); 
//         });

//         if (Mail::failures()) {
//             return response()->json(['error' => 'Email sending failed'], 500);
//         }

//         return response()->json(['message' => 'Email sent successfully']);
//     } catch (\Exception $e) {
//         \Log::error('Error sending email: ' . $e->getMessage());
//         return response()->json(['error' => 'Failed to send email', 'message' => $e->getMessage()], 500);
//     }
// });

Route::post('/SendEmailToClientExhibition', function (Request $request) {
    // Log the request data for debugging
   
    // Extract parameters from the request
    $send_to = $request->input('email');
    $from = 'pd.fabrics@kenindia.in';
    $firstName = $request->input('first_name');
    $lastName = $request->input('last_name');
    $mobileNo = $request->input('contact_no');
    $description = $request->input('message');
    $attachment = $request->file('attachment');
    
    $id = DB::table('exhibition_inquiry')->insertGetId([
        'first_name' => $firstName,
        'last_name' => $lastName,
        'contact_no' => $mobileNo,
        'email' => $send_to,
        'message' => $description,
        'created_at' => now()
    ]);
    
    $subject = 'Fabric Inquiry from Bharat Tex -  BT#'.$id;
    
    // Check if an attachment is uploaded
    if (!$attachment) {
        throw new \Exception('No attachment uploaded');
    }
    
    $originalFileName = $attachment->getClientOriginalName();

    // Store the file temporarily
    $tempFilePath = $attachment->store('temp');

    if (!$tempFilePath) {
        throw new \Exception('Failed to store attachment');
    }

    // Set the mail from address and clear configuration cache
    setEnvironmentValue('MAIL_FROM_ADDRESS', $from);
    Artisan::call('config:clear');

    // Send email to each recipient
    $recipients = explode(",", $send_to);
    $recipients[] = 'pd.fabrics@kenindia.in';
    foreach ($recipients as $recipient) {
        // Validate email address
        $recipient = trim($recipient);  
        
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'mobileNo' => $mobileNo,
            'subject' => $subject,
            'description' => $description
        ];
        Mail::send('WebsiteOrderMail', $data, function($message) use ($from, $subject, $description, $tempFilePath, $recipient, $originalFileName) {
            $message->from($from)
                    ->subject($subject)
                    ->to($recipient)
                     ->attach(storage_path('app/' . $tempFilePath), [
                        'as' => $originalFileName // Use original file name for the attachment
                    ])
                    ->setBody($description);
        });
       
    }

    // Clean up the temporary file
    unlink(storage_path('app/' . $tempFilePath));

    // DB::table('exhibition_inquiry')->insert([
    //     'first_name' => $firstName,
    //     'last_name' => $lastName,
    //     'contact_no' => $mobileNo,
    //     'email' => $send_to,
    //     'message' => $description,
    //     "created_at"=>date("Y-m-d H:i:s")
    // ]);
            
    return response()->json(['message' => 'Email sent successfully']);
  
});


Route::post('/SendEmailToClient', function (Request $request) {
    // Log the request data for debugging
   
    // Extract parameters from the request
    $from = $request->input('from');
    $firstName = $request->input('firstName');
    $lastName = $request->input('lastName');
    $mobileNo = $request->input('mobileNo');
    $send_to = $request->input('email');
    $subject = 'Fabric Inquiry';
    $description = $request->input('description');
    $attachment = $request->file('attachment');
    
    
    // Check if an attachment is uploaded
    if (!$attachment) {
        throw new \Exception('No attachment uploaded');
    }
    
    $originalFileName = $attachment->getClientOriginalName();

    // Store the file temporarily
    $tempFilePath = $attachment->store('temp');

    if (!$tempFilePath) {
        throw new \Exception('Failed to store attachment');
    }

    // Set the mail from address and clear configuration cache
    setEnvironmentValue('MAIL_FROM_ADDRESS', $from);
    Artisan::call('config:clear');

    // Send email to each recipient
    $recipients = explode(",", $send_to);
    foreach ($recipients as $recipient) {
        // Validate email address
        $recipient = trim($recipient);  
        
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'mobileNo' => $mobileNo,
            'subject' => $subject,
            'description' => $description
        ];
        Mail::send('WebsiteOrderMail', $data, function($message) use ($from, $subject, $description, $tempFilePath, $recipient, $originalFileName) {
            $message->from($from)
                    ->subject($subject)
                    ->to($recipient)
                     ->attach(storage_path('app/' . $tempFilePath), [
                        'as' => $originalFileName // Use original file name for the attachment
                    ])
                    ->setBody($description);
        });
       
    }

    // Clean up the temporary file
    unlink(storage_path('app/' . $tempFilePath));

    return response()->json(['message' => 'Email sent successfully']);
  
});


Route::get('/SendEmailToProductClient', function (Request $request) {
    // Log the request data for debugging
   
    // Extract parameters from the request
    $from = $request->input('from');
    $firstName = $request->input('firstName');
    $lastName = $request->input('lastName');
    $mobileNo = $request->input('mobileNo');
    $send_to = $request->input('email');
    $subject = 'Fabric Enquiry';
    $description = $request->input('description'); 
    
      
    setEnvironmentValue('MAIL_FROM_ADDRESS', $from);
    Artisan::call('config:clear');

    // Send email to each recipient
    $recipients = explode(",", $send_to);
    foreach ($recipients as $recipient) {
        // Validate email address
        $recipient = trim($recipient); 
  
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'mobileNo' => $mobileNo,
            'subject' => $subject,
            'description' => $description
        ];
        Mail::send('WebsiteOrderMail', $data, function($message) use ($from, $subject, $description, $recipient) {
            $message->from($from)
                    ->subject($subject)
                    ->to($recipient)
                    ->setBody($description);
        });
       
    }

    return response()->json(['message' => 'Email sent successfully']);
  
});

Route::get('/inventoryEmployeelist', function (Request $request) {
    $employeesData = DB::connection('hrms_database')
        ->table('employeemaster')
        ->select('fullName', 'employeeCode')
        ->get();

    $data = [];

    foreach ($employeesData as $row) {
        $data[] = [
            'fullName' => $row->fullName,
            'employeeCode' => $row->employeeCode
        ];
    }

    return response()->json(['data' => $data]);
});

