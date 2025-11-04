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
use App\Http\Controllers\TrimsOutwardController;
use App\Http\Controllers\CartonPackingInhouseMasterController;
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
use App\Http\Controllers\ReportViewerController;
use App\Http\Controllers\OpenOrderPPCController;
use App\Http\Controllers\RejectedPcsDeliveryChallanController;


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
Route::get('/dashboard2nd',[AdminController::class,'dashboard2nd']);

Route::get('WorkInProgressStatusList',[AdminController::class,'WorkInProgressStatusList']);

Route::get('OrderStatusListDashboard',[AdminController::class,'OrderStatusListDashboard']);

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

Route::resource('DashboardMaster', DashboardController::class);
Route::resource('/Country', CountryController::class);
Route::resource('State', StateController::class);
Route::resource('City', CityController::class);
Route::resource('Line', LineController::class);
Route::resource('MainStyle', MainStyleController::class);
Route::resource('SubStyle', SubStyleController::class);
Route::get('/SubStyleList',[SubStyleController::class,'GetSubStyleList'])->name('SubStyleList');
Route::get('/StyleList',[SubStyleController::class,'GetStyleList'])->name('StyleList');
Route::resource('MerchantMaster', MerchantMasterController::class);
Route::resource('PDMerchantMaster', PDMerchantMasterController::class);

Route::resource('PaymentTerms', PaymentTermsController::class);
Route::resource('DeliveryTerms', DeliveryTermsController::class);
Route::resource('ShipmentMode', ShipmentModeController::class);
Route::resource('Process', ProcessController::class);
Route::resource('Warehouse', WarehouseController::class);
Route::resource('OrderGroup', OrderGroupController::class);
Route::resource('Currency', CurrencyController::class);
Route::resource('MachineType', MachineTypeController::class);
Route::resource('FabricDefect', FabricDefectController::class);


Route::resource('Form', UserManagementController::class);
Route::resource('User_Management', PermissionController::class);
Route::resource('District', DistrictController::class);
Route::resource('Taluka', TalukaController::class);
Route::resource('Category', CategoryContoller::class);

Route::resource('Rack', RackController::class);

Route::resource('Item', ItemMasterController::class);

Route::get('list/{id}',[ItemMasterController::class,'activeDeactiveList']);

Route::post('itemimport',[ItemMasterController::class,'itemimport'])->name('itemimport');
Route::get('/ClassList',[ItemMasterController::class,'GetClassList'])->name('ClassList');


Route::get('itemexist',[ItemMasterController::class,'itemexist'])->name('itemexist');

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
Route::resource('Commission', CommissionController::class);


Route::post('importcolor',[ColorController::class,'importcolor'])->name('importcolor');

Route::resource('Classification', ClassificationController::class);
Route::resource('Size', SizeController::class);
Route::resource('Location', LocationController::class);

Route::resource('PurchaseOrder', PurchaseOrderController::class);

Route::resource('TrimsInward', TrimsInwardController::class);

Route::get('/getPoForTrims',[TrimsInwardController::class,'getPoForTrims'])->name('getPoForTrims');
Route::get('TrimsGRNPrint/{id}',[TrimsInwardController::class,'TrimsGRNPrint']);

Route::get('/getPoMasterDetailTrims',[TrimsInwardController::class,'getPoMasterDetailTrims'])->name('getPoMasterDetailTrims');
Route::get('/GetTrimsGRNReport',[TrimsInwardController::class,'GetTrimsGRNReport'])->name('GetTrimsGRNReport');
Route::get('/TrimsGRNReportPrint',[TrimsInwardController::class,'TrimsGRNReportPrint'])->name('TrimsGRNReportPrint');


Route::get('POApprovalList',[PurchaseOrderController::class,'show'])->name('POApprovalList');
Route::get('GetPOList',[PurchaseOrderController::class,'GetPOList'])->name('GetPOList');
Route::get('getBoMDetail',[PurchaseOrderController::class,'getBoMDetail'])->name('getBoMDetail');
Route::get('getClassLists',[PurchaseOrderController::class,'getClassLists'])->name('getClassLists');

Route::get('PODisApprovalList',[PurchaseOrderController::class,'Disapprovedshow'])->name('PODisApprovalList');
Route::get('PartyDetail',[PurchaseOrderController::class,'GetPartyDetails'])->name('PartyDetail');

Route::resource('Brand', BrandController::class);
Route::resource('Season', SeasonController::class);
Route::resource('JobStatus', JobStatusController::class);
Route::resource('BuyerJobCard', BuyerJobCardController::class);
Route::resource('FabricInward', FabricInwardController::class);

Route::get('/PrintBarcode',[FabricInwardController::class,'PrintFabricBarcode'])->name('PrintBarcode');

Route::get('/FabricGRNData',[FabricInwardController::class,'FabricGRNData'])->name('FabricGRNData');

Route::get('/FabricStockData',[FabricInwardController::class,'FabricStockData'])->name('FabricStockData');
Route::get('/FabricStockSummaryData',[FabricInwardController::class,'FabricStockSummaryData'])->name('FabricStockSummaryData');
Route::get('/FabricPOVsGRNDashboard',[FabricInwardController::class,'FabricPOVsGRNDashboard'])->name('FabricPOVsGRNDashboard');


Route::get('/TrimsGRNData',[TrimsInwardController::class,'TrimsGRNData'])->name('TrimsGRNData');
Route::get('/TrimsStockData',[TrimsInwardController::class,'TrimsStockData'])->name('TrimsStockData');
Route::get('/TrimsPOVsGRNDashboard',[TrimsInwardController::class,'TrimsPOVsGRNDashboard'])->name('TrimsPOVsGRNDashboard');

Route::get('/getPo',[FabricInwardController::class,'getPo'])->name('getPo');

Route::get('/getPoMasterDetail',[FabricInwardController::class,'getPoMasterDetail'])->name('getPoMasterDetail');


Route::get('PODetail',[FabricInwardController::class,'getPODetails'])->name('PODetail');
Route::get('ItemRateFromPO',[FabricInwardController::class,'getItemRateFromPO'])->name('ItemRateFromPO');

// Start SalesOrderCosting-------------------//
Route::resource('SalesOrderCosting', SalesOrderCostingController::class);

 
Route::get('/GetCostingData/{id}',[SalesOrderCostingController::class,'GetCostingData']);


Route::get('/RepeatSalesOrderCosting/{id}',[SalesOrderCostingController::class,'RepeatSalesOrderCosting'])->name('RepeatSalesOrderCosting');
Route::post('/RepeatSalesOrderCostSave',[SalesOrderCostingController::class,'repeatstore'])->name('RepeatSalesOrderCostSave');


Route::get('/SalesOrderDetails',[SalesOrderCostingController::class,'getSalesOrderDetails'])->name('SalesOrderDetails');
Route::get('/ItemDetails',[SalesOrderCostingController::class,'GetItemData'])->name('ItemDetails');

Route::get('/SalesCostingProfitSheet',[SalesOrderCostingController::class,'costingProfitSheet'])->name('SalesCostingProfitSheet');
Route::get('/SalesCostingProfitSheet2',[SalesOrderCostingController::class,'costingProfitSheet2'])->name('SalesCostingProfitSheet2');

Route::get('/GetCostingProfitByFilter',[SalesOrderCostingController::class,'GetCostingProfitByFilter'])->name('GetCostingProfitByFilter');
Route::get('/costingProfitSheet3',[SalesOrderCostingController::class,'costingProfitSheet3'])->name('costingProfitSheet3');

Route::resource('BOM', BOMController::class);

Route::get('BUDGETPrint/{id}',[BOMController::class,'show']);

Route::get('BOMPrint/{id}',[BOMController::class,'bomPrint']);


Route::get('/GetOrderQty',[BOMController::class,'GetOrderQty'])->name('GetOrderQty');
Route::get('/GetMultipleBOMData',[BOMController::class,'GetMultipleBOMData'])->name('GetMultipleBOMData');
Route::get('/MultipleBOMData',[BOMController::class,'MultipleBOMData'])->name('MultipleBOMData');



Route::get('/GetSizeList',[BOMController::class,'GetSizeList'])->name('GetSizeList');
Route::get('/GetItemList',[BOMController::class,'GetItemList'])->name('GetItemList');
Route::get('/GetClassItemList',[BOMController::class,'GetClassItemList'])->name('GetClassItemList');

Route::get('/GetClassList',[BOMController::class,'GetClassList'])->name('GetClassList');
Route::get('/GetItemColorList',[BOMController::class,'GetItemColorList'])->name('GetItemColorList');
Route::get('/GetSewingTrimItemList',[BOMController::class,'GetSewingTrimItemList'])->name('GetSewingTrimItemList');
Route::get('/GetPackingTrimItemList',[BOMController::class,'GetPackingTrimItemList'])->name('GetPackingTrimItemList');



Route::get('/GetColorList',[BOMController::class,'GetColorList'])->name('GetColorList');
Route::get('/FabricWiseSalesOrderCosting',[BOMController::class,'GetFabricWiseSalesOrderCosting'])->name('FabricWiseSalesOrderCosting');
Route::get('/PackingWiseSalesOrderCosting',[BOMController::class,'GetPackingWiseSalesOrderCosting'])->name('PackingWiseSalesOrderCosting');
Route::get('/ItemWiseSalesOrderCosting',[BOMController::class,'GetItemWiseSalesOrderCosting'])->name('ItemWiseSalesOrderCosting');
Route::get('/TrimFabricWiseSalesOrderCosting',[BOMController::class,'GetTrimFabricWiseSalesOrderCosting'])->name('TrimFabricWiseSalesOrderCosting');


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

// Vendor Work Order End ----------------------------



//Vendor Purchase Order Start -----------------
Route::resource('VendorPurchaseOrder', VendorPurchaseOrderController::class);

Route::get('VPPrint/{id}',[VendorPurchaseOrderController::class,'VPPrint']);

Route::get('/VPO_GetOrderQty',[VendorPurchaseOrderController::class,'VPO_GetOrderQty'])->name('VPO_GetOrderQty');
Route::get('/VPO_GetSizeList',[VendorPurchaseOrderController::class,'VPO_GetSizeList'])->name('VPO_GetSizeList');
Route::get('/VPO_GetItemList',[VendorPurchaseOrderController::class,'VPO_GetItemList'])->name('VPO_GetItemList');
Route::get('/VPO_GetClassList',[VendorPurchaseOrderController::class,'VPO_GetClassList'])->name('VPO_GetClassList');
Route::get('/VPO_GetColorList',[VendorPurchaseOrderController::class,'VPO_GetColorList'])->name('VPO_GetColorList');
Route::get('/GetFabricConsumptionPO',[VendorPurchaseOrderController::class,'GetFabricConsumptionPO'])->name('GetFabricConsumptionPO');
Route::get('/CuttingPOItemList',[VendorPurchaseOrderController::class,'GetCuttingPOItemList'])->name('CuttingPOItemList');
Route::get('/POVsMaterialIssueReport',[VendorPurchaseOrderController::class,'POVsMaterialIssueReport'])->name('POVsMaterialIssueReport');



Route::get('/VendorPurchaseOrderDetails',[VendorPurchaseOrderController::class,'getVendorPurchaseOrderDetails'])->name('VendorPurchaseOrderDetails');
Route::get('/getVendorPO',[VendorPurchaseOrderController::class,'getVendorPO'])->name('getVendorPO');
Route::get('/getVendorAllPO',[VendorPurchaseOrderController::class,'getVendorAllPO'])->name('getVendorAllPO');

Route::get('/GetVPOVsIssueReport',[VendorPurchaseOrderController::class,'GetVPOVsIssueReport'])->name('GetVPOVsIssueReport');

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

// End SalesOrderCosting-------------------//

Route::resource('FabricChecking', FabricCheckingController::class);
Route::get('/InwardList',[FabricCheckingController::class,'getDetails'])->name('InwardList');
Route::get('/InwardMasterList',[FabricCheckingController::class,'getMasterdata'])->name('InwardMasterList');

Route::get('/FabricCheckingDashboard',[FabricCheckingController::class,'FabricCheckingDashboard'])->name('FabricCheckingDashboard');
Route::get('/FabricCheckingRejectDashboard',[FabricCheckingController::class,'FabricCheckingRejectDashboard'])->name('FabricCheckingRejectDashboard');


 
Route::resource('BuyerPurchaseOrder', BuyerPurchaseOrderController::class);
Route::get('SaleOrderPrint/{id}',[BuyerPurchaseOrderController::class,'show']);
Route::get('/GetAddress',[BuyerPurchaseOrderController::class,'getAddress'])->name('GetAddress');
Route::get('/TaxList',[BuyerPurchaseOrderController::class,'GetTaxList'])->name('TaxList');
Route::get('/SizeDetailList',[BuyerPurchaseOrderController::class,'GetSizeDetailList'])->name('SizeDetailList');
Route::get('/SalesOrderOpen',[BuyerPurchaseOrderController::class,'SalesOrderOpen'])->name('SalesOrderOpen');
Route::get('/SalesOrderSample',[BuyerPurchaseOrderController::class,'SalesOrderSample'])->name('SalesOrderSample');
Route::get('/CheckOpenWorkProcessOrders',[BuyerPurchaseOrderController::class,'CheckOpenWorkProcessOrders'])->name('CheckOpenWorkProcessOrders');



Route::get('/SalesOrderClosed',[BuyerPurchaseOrderController::class,'SalesOrderClosed'])->name('SalesOrderClosed');
Route::get('/SalesOrderCancelled',[BuyerPurchaseOrderController::class,'SalesOrderCancelled'])->name('SalesOrderCancelled');
Route::get('/OpenSalesOrderDashboard',[BuyerPurchaseOrderController::class,'OpenSalesOrderDashboard'])->name('OpenSalesOrderDashboard');
Route::get('/BuyerOpenSalesOrderDashboard',[BuyerPurchaseOrderController::class,'BuyerOpenSalesOrderDashboard'])->name('BuyerOpenSalesOrderDashboard');
Route::get('/OpenSalesOrderDetailDashboard',[BuyerPurchaseOrderController::class,'OpenSalesOrderDetailDashboard'])->name('OpenSalesOrderDetailDashboard');
Route::get('/TotalSalesOrderDetailDashboard',[BuyerPurchaseOrderController::class,'TotalSalesOrderDetailDashboard'])->name('TotalSalesOrderDetailDashboard');
Route::get('/OpenSalesOrderMonthDetailDashboard',[BuyerPurchaseOrderController::class,'OpenSalesOrderMonthDetailDashboard'])->name('OpenSalesOrderMonthDetailDashboard');
Route::get('/TotalSalesOrderDetailDashboardFilter',[BuyerPurchaseOrderController::class,'TotalSalesOrderDetailDashboardFilter'])->name('TotalSalesOrderDetailDashboardFilter');


Route::get('/DailyProductionDetailDashboard',[BuyerPurchaseOrderController::class,'DailyProductionDetailDashboard'])->name('DailyProductionDetailDashboard');
Route::get('/OrderProgressDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressDetailDashboard'])->name('OrderProgressDetailDashboard');
Route::get('/OrderProgressFinishingDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressFinishingDetailDashboard'])->name('OrderProgressFinishingDetailDashboard');
Route::get('/OrderProgressPackingDetailDashboard',[BuyerPurchaseOrderController::class,'OrderProgressPackingDetailDashboard'])->name('OrderProgressPackingDetailDashboard');



Route::get('/SalesOrderCostingBOMStatusDashboard',[BuyerPurchaseOrderController::class,'SalesOrderCostingBOMStatusDashboard'])->name('SalesOrderCostingBOMStatusDashboard');
Route::get('/CostingOHPDashboard',[BuyerPurchaseOrderController::class,'CostingOHPDashboard'])->name('CostingOHPDashboard');
Route::get('/CostingVSBudgetDashboard',[BuyerPurchaseOrderController::class,'CostingVSBudgetDashboard'])->name('CostingVSBudgetDashboard');


Route::get('/GetOCRReport',[BuyerPurchaseOrderController::class,'GetOCRReport'])->name('GetOCRReport');
Route::get('/GetMerchandiseOCRReport',[BuyerPurchaseOrderController::class,'GetMerchandiseOCRReport'])->name('GetMerchandiseOCRReport');
Route::get('/MerchandiseOCRReport',[BuyerPurchaseOrderController::class,'MerchandiseOCRReport'])->name('MerchandiseOCRReport');
Route::get('/GetOCRSummaryReport',[BuyerPurchaseOrderController::class,'GetOCRSummaryReport'])->name('GetOCRSummaryReport');
Route::get('/GetOCRSummaryReport1',[BuyerPurchaseOrderController::class,'GetOCRSummaryReport1'])->name('GetOCRSummaryReport1');
Route::get('/GetOrderVsShipmentReport',[BuyerPurchaseOrderController::class,'GetOrderVsShipmentReport'])->name('GetOrderVsShipmentReport');

Route::get('/OCRSummaryReport',[BuyerPurchaseOrderController::class,'OCRSummaryReport'])->name('OCRSummaryReport');
Route::get('/OCRSummaryReport1',[BuyerPurchaseOrderController::class,'OCRSummaryReport1'])->name('OCRSummaryReport1');

Route::get('/OCRReport',[BuyerPurchaseOrderController::class,'OCRReport'])->name('OCRReport');


Route::get('/GetCutPlanReport',[BuyerPurchaseOrderController::class,'GetCutPlanReport'])->name('GetCutPlanReport');
Route::get('/CuttingPOList',[BuyerPurchaseOrderController::class,'CuttingPOList'])->name('CuttingPOList');
Route::get('/CutPlanReport',[BuyerPurchaseOrderController::class,'CutPlanReport'])->name('CutPlanReport');


Route::get('/SeasonList',[BuyerPurchaseOrderController::class,'GetSeasonList'])->name('SeasonList');
Route::get('/BrandList',[BuyerPurchaseOrderController::class,'GetBrandList'])->name('BrandList');
Route::get('/BuyerSalesOrderSizeQtyDashboard',[BuyerPurchaseOrderController::class,'BuyerSalesOrderSizeQtyDashboard'])->name('BuyerSalesOrderSizeQtyDashboard');




Route::resource('Task', TaskMasterController::class);
Route::get('/CompletedTask',[TaskMasterController::class,'CompletedTaskList'])->name('CompletedTask');
Route::resource('MaterialOutward', MaterialOutwardController::class);
Route::resource('MaterialInward', MaterialInwardController::class);

Route::resource('FabricCutting', CuttingMasterController::class);
Route::get('/RatioList',[CuttingMasterController::class,'getRatioDetails'])->name('RatioList');
Route::get('/EndDataList',[CuttingMasterController::class,'getEndDataDetails'])->name('EndDataList');
Route::get('/CheckingFabricList',[CuttingMasterController::class,'getCheckingFabricdata'])->name('CheckingFabricList');
Route::get('/CheckingMasterList',[CuttingMasterController::class,'getCheckingMasterdata'])->name('CheckingMasterList');

Route::get('/CompletedCutting',[CuttingMasterController::class,'CompletedCutting'])->name('CompletedCutting');

Route::resource('StitchingInhouse', StitchingInhouseMasterController::class);
Route::get('/VendorWorkOrderDetails',[StitchingInhouseMasterController::class,'getVendorWorkOrderDetails'])->name('VendorWorkOrderDetails');
Route::get('/VW_GetOrderQty',[StitchingInhouseMasterController::class,'VW_GetOrderQty'])->name('VW_GetOrderQty');
Route::get('/StitchingGRNDashboard',[StitchingInhouseMasterController::class,'StitchingGRNDashboard'])->name('StitchingGRNDashboard');

Route::get('/GetDailyProductionReport',[StitchingInhouseMasterController::class,'GetDailyProductionReport'])->name('GetDailyProductionReport');
Route::get('/DailyProductionReport',[StitchingInhouseMasterController::class,'DailyProductionReport'])->name('DailyProductionReport');
Route::get('/GetVendorStatusReport',[StitchingInhouseMasterController::class,'GetVendorStatusReport'])->name('GetVendorStatusReport');
Route::get('/VendorStatusReport',[StitchingInhouseMasterController::class,'VendorStatusReport'])->name('VendorStatusReport');

Route::get('/StitchingGRNPrint/{id}',[StitchingInhouseMasterController::class,'StitchingGRNPrint']);


Route::resource('CutPanelIssue', CutPanelIssueMasterController::class);
Route::get('/VW_GetCutOrderQty',[CutPanelIssueMasterController::class,'VW_GetCutOrderQty'])->name('VW_GetCutOrderQty');
Route::get('/GetLineList',[CutPanelIssueMasterController::class,'GetLineList'])->name('GetLineList');
Route::get('/GetCUTGRNQty',[CutPanelIssueMasterController::class,'GetCUTGRNQty'])->name('GetCUTGRNQty');

Route::get('/CUTGRNQty',[CutPanelIssueMasterController::class,'CUTGRNQty'])->name('CUTGRNQty');

Route::get('/CutPanelStockSummary',[CutPanelIssueMasterController::class,'CutPanelStockSummary'])->name('CutPanelStockSummary');
Route::get('/CutPanelGRNReport',[CutPanelIssueMasterController::class,'CutPanelGRNReport'])->name('CutPanelGRNReport');


Route::get('/CutPanelIssuePrint/{id}',[CutPanelIssueMasterController::class,'CutPanelIssuePrint']);


Route::get('/CutPanelIssueReport',[CutPanelIssueMasterController::class,'CutPanelIssueReport'])->name('CutPanelIssueReport');
Route::resource('OutwardForFinishing', OutwardForFinishingMasterController::class);
Route::get('/vpo_GetFinishingPOQty',[OutwardForFinishingMasterController::class,'vpo_GetFinishingPOQty'])->name('vpo_GetFinishingPOQty');

Route::get('/OutwardForFinishingPrint/{id}',[OutwardForFinishingMasterController::class,'OutwardForFinishingPrint']);




Route::get('/GetSTITCHINGGRNQty',[FinishingInhouseMasterController::class,'GetSTITCHINGGRNQty'])->name('GetSTITCHINGGRNQty'); 
Route::get('/FinishingGRNPrint/{id}',[FinishingInhouseMasterController::class,'FinishingGRNPrint']);

Route::resource('OutwardForPacking', OutwardForPackingMasterController::class);
Route::get('/vpo_GetPackingPOQty',[OutwardForPackingMasterController::class,'vpo_GetPackingPOQty'])->name('vpo_GetPackingPOQty');  

Route::get('/OutwardForPackingPrint/{id}',[OutwardForPackingMasterController::class,'OutwardForPackingPrint']);



Route::resource('CutPanelGRN', CutPanelGRNMasterController::class);
Route::get('/VPO_GetCutOrderQty',[CutPanelGRNMasterController::class,'VPO_GetCutOrderQty'])->name('VPO_GetCutOrderQty');
Route::get('/VendorProcessOrderDetails',[CutPanelGRNMasterController::class,'getVendorProcessOrderDetails'])->name('VendorProcessOrderDetails');
Route::get('/CutPanelGRNPrint/{id}',[CutPanelGRNMasterController::class,'CutPanelGRNPrint']);
Route::get('/PPCCuttingReport',[CutPanelGRNMasterController::class,'PPCCuttingReport'])->name('PPCCuttingReport');







Route::resource('QCStitchingInhouse', QCStitchingInhouseMasterController::class);
Route::get('/StitchingInhouseDetails',[QCStitchingInhouseMasterController::class,'getStitchingInhouseDetails'])->name('StitchingInhouseDetails');
Route::get('/STI_GetOrderQty',[QCStitchingInhouseMasterController::class,'STI_GetOrderQty'])->name('STI_GetOrderQty');
Route::get('/QCStitchingReport',[QCStitchingInhouseMasterController::class,'QCStitchingReport'])->name('QCStitchingReport');


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


Route::resource('CartonPackingInhouse', CartonPackingInhouseMasterController::class);
Route::get('/CartonPackingInhouseDetails',[CartonPackingInhouseMasterController::class,'getPackingInhouseDetails'])->name('PackingInhouseDetails');
Route::get('/PKI_GetOrderQty',[CartonPackingInhouseMasterController::class,'PKI_GetOrderQty'])->name('PKI_GetOrderQty');
Route::get('/PKI_GetOrdarQtyByRow',[CartonPackingInhouseMasterController::class,'PKI_GetOrdarQtyByRow'])->name('PKI_GetOrdarQtyByRow');

Route::get('/FGStockReport',[CartonPackingInhouseMasterController::class,'FGStockReport'])->name('FGStockReport');
Route::get('/FGStockSummaryReport',[CartonPackingInhouseMasterController::class,'FGStockSummaryReport'])->name('FGStockSummaryReport');

Route::get('/GetCartonPackingReport',[CartonPackingInhouseMasterController::class,'GetCartonPackingReport'])->name('GetCartonPackingReport');
Route::get('/CartonPackingReport',[CartonPackingInhouseMasterController::class,'CartonPackingReport'])->name('CartonPackingReport');
 
Route::get('/PKI_GetColorList',[CartonPackingInhouseMasterController::class,'PKI_GetColorList'])->name('PKI_GetColorList');
Route::get('PKI_GetMaxMinvalueList',[CartonPackingInhouseMasterController::class,'GetMaxMinvalueList'])->name('PKI_GetMaxMinvalueList');

Route::get('NewSalesOrderList',[CartonPackingInhouseMasterController::class,'getSalesOrderList'])->name('NewSalesOrderList');
Route::get('/BuyerLocationList',[CartonPackingInhouseMasterController::class,'getBuyerLocationList'])->name('BuyerLocationList');

Route::get('/CartonPackingPrint/{id}',[CartonPackingInhouseMasterController::class,'CartonPackingPrint']);

Route::resource('TransferPackingInhouse', TransferPackingInhouseMasterController::class);
Route::get('/FG_GetRawData',[TransferPackingInhouseMasterController::class,'FG_GetRawData'])->name('FG_GetRawData');
Route::get('/FGStockData',[TransferPackingInhouseMasterController::class,'FGStockData'])->name('FGStockData');
Route::get('/FG_GetColorList',[TransferPackingInhouseMasterController::class,'FG_GetColorList'])->name('FG_GetColorList');
Route::get('/FGPackingInhouseDetails',[TransferPackingInhouseMasterController::class,'FGPackingInhouseDetails'])->name('FGPackingInhouseDetails');
Route::get('/PKI_GetTransferQtyByRow',[TransferPackingInhouseMasterController::class,'PKI_GetTransferQtyByRow'])->name('PKI_GetTransferQtyByRow');
Route::get('TPKI_GetMaxMinvalueList',[TransferPackingInhouseMasterController::class,'TPKI_GetMaxMinvalueList'])->name('TPKI_GetMaxMinvalueList');



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

Route::resource('JobPart', JobPartController::class);
Route::resource('FabricTrimPart', FabricTrimPartController::class);
Route::resource('Quality', QualityController::class);

Route::post('qualityimport',[QualityController::class,'qualityimport'])->name('qualityimport');

Route::resource('FabricOutward', FabricOutwardController::class);
//Fabric Outward Controller for Fabric Issue to Internal Department.
Route::get('/FabricRecord',[FabricOutwardController::class,'getFabricRecord'])->name('FabricRecord');
Route::resource('FabricOutwardReport', FabricOutwardReportController::class);


Route::get('/GetFabricInOutStockReportForm',[FabricOutwardReportController::class,'GetFabricInOutStockReportForm'])->name('GetFabricInOutStockReportForm'); 

Route::get('/FabricInOutStockReport',[FabricOutwardReportController::class,'getFabricInOutStockReport'])->name('FabricInOutStockReport');
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
Route::get('getVendorProcessOrder',[TrimsOutwardController::class,'getVendorProcessOrder'])->name('getVendorProcessOrder');
Route::get('getVendorMasterDetail',[TrimsOutwardController::class,'getVendorMasterDetail'])->name('getVendorMasterDetail');
Route::get('getvendortablenew',[TrimsOutwardController::class,'getvendortablenew'])->name('getvendortablenew');
Route::get('getProcessTrimData',[TrimsOutwardController::class,'getProcessTrimData'])->name('getProcessTrimData');
Route::get('TrimOutwardPrint/{id}',[TrimsOutwardController::class,'show']);
Route::get('TrimOutwardStandardPrint/{id}',[TrimsOutwardController::class,'TrimOutwardStandardPrint']);
Route::get('TrimOutwardStandardPrint2/{id}',[TrimsOutwardController::class,'TrimOutwardStandardPrint2']);
Route::resource('PresentEmployees', PresentEmployeesController::class);
Route::resource('ActivityMaster', ActivityMasterController::class);
Route::resource('ActivityTypeMaster', ActivityTypeMasterController::class);
Route::resource('T_And_A_Master', T_And_A_MasterController::class);
Route::get('getSalesOrderDetail', [T_And_A_MasterController::class,'getSalesOrderDetail'])->name('getSalesOrderDetail');
Route::get('Timeline', [T_And_A_MasterController::class,'Timeline'])->name('Timeline');
Route::get('GetTNAMasterData', [T_And_A_MasterController::class,'GetTNAMasterData'])->name('GetTNAMasterData');
Route::resource('PPCMaster', PPCMasterController::class);
Route::resource('T_And_A_TemplateMaster', T_And_A_TemplateMasterController::class);
Route::get('getSalesOrderDetail2', [T_And_A_TemplateMasterController::class,'getSalesOrderDetail2'])->name('getSalesOrderDetail2');
Route::get('Timeline2', [T_And_A_TemplateMasterController::class,'Timeline2'])->name('Timeline2');
Route::get('PPCCalendarReport/{id}/{id2}',[PPCMasterController::class,'PPCCalendarReport'])->name('PPCCalendarReport');
Route::get('PPCCalendarReport/{id}/{id2}/{id3}/{id4}',[PPCMasterController::class,'PPCCalendarReport'])->name('PPCCalendarReport');
//Route::get('PPCCalendarReport',[PPCMasterController::class,'PPCCalendarReport'])->name('PPCCalendarReport');

Route::post('search',[PPCMasterController::class,'search'])->name('search');
Route::get('rptSAH_PPC',[PPCMasterController::class,'rptSAH_PPC'])->name('rptSAH_PPC');
Route::get('SAH_PPCMaster',[PPCMasterController::class,'SAH_PPCMaster'])->name('SAH_PPCMaster');

Route::get('/GetSalesOrderList',[PPCMasterController::class,'GetSalesOrderList'])->name('GetSalesOrderList'); 
Route::get('/GetSaleOrderWiseColorList',[PPCMasterController::class,'GetSaleOrderWiseColorList'])->name('GetSaleOrderWiseColorList'); 

Route::post('PPCMaster/SAHPPC',[PPCMasterController::class,'SAHPPC'])->name('SAHPPC');

Route::get('WIPDetailReport/{id}',[BuyerPurchaseOrderController::class,'WIPDetailReport'])->name('WIPDetailReport');

Route::get('GetPPCData',[PPCMasterController::class,'GetPPCData'])->name('GetPPCData');

Route::resource('ReportViewer', ReportViewerController::class);

Route::get('ReportViewerDashboard',[ReportViewerController::class,'ReportViewerDashboard'])->name('ReportViewerDashboard');

Route::get('/PrintSaleTransaction/{id}/{id1}/{id2}',[SaleTransactionMasterController::class,'PrintSaleTransaction'])->name('PrintSaleTransaction');

Route::get('MonthlyShipmentTargetMaster',[SaleTransactionMasterController::class,'MonthlyShipmentTargetMaster'])->name('MonthlyShipmentTargetMaster');

Route::get('GetBuyerData',[SaleTransactionMasterController::class,'GetBuyerData'])->name('GetBuyerData');

Route::get('GetStyleCategoryData',[SaleTransactionMasterController::class,'GetStyleCategoryData'])->name('GetStyleCategoryData');

Route::post('monthlyShipmentTargetStore',[SaleTransactionMasterController::class,'monthlyShipmentTargetStore'])->name('monthlyShipmentTargetStore');

Route::get('rptMonthlyShipmentTarget',[SaleTransactionMasterController::class,'rptMonthlyShipmentTarget'])->name('rptMonthlyShipmentTarget'); 

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
Route::post('VendorWorkOrderStock',[BuyerPurchaseOrderController::class,'VendorWorkOrderStock'])->name('VendorWorkOrderStock');

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

Route::get('GetStockDetailPopup',[PurchaseOrderController::class,'GetStockDetailPopup'])->name('GetStockDetailPopup');

Route::get('Get_Ledger_Item_Report',[ItemMasterController::class,'Get_Ledger_Item_Report'])->name('Get_Ledger_Item_Report');

Route::get('GetClassifictionData',[ItemMasterController::class,'GetClassifictionData'])->name('GetClassifictionData');

Route::get('GetItemData',[ItemMasterController::class,'GetItemData'])->name('GetItemData');

Route::post('rptItemLedger',[ItemMasterController::class,'rptItemLedger'])->name('rptItemLedger');

Route::get('Get_Cut_Panel_Issue_VS_Production',[PPCMasterController::class,'Get_Cut_Panel_Issue_VS_Production'])->name('Get_Cut_Panel_Issue_VS_Production');

Route::post('rptCutPanelIssueVsProduction',[PPCMasterController::class,'rptCutPanelIssueVsProduction'])->name('rptCutPanelIssueVsProduction');

Route::get('Get_WIP_Report',[PPCMasterController::class,'Get_WIP_Report'])->name('Get_WIP_Report');

Route::post('rptWIPReport',[PPCMasterController::class,'rptWIPReport'])->name('rptWIPReport');

Route::get('rptFabricMovingAndNonMovingStock',[FabricInwardController::class,'rptFabricMovingAndNonMovingStock'])->name('rptFabricMovingAndNonMovingStock');

Route::get('Get_WIP_Report1',[PPCMasterController::class,'Get_WIP_Report1'])->name('Get_WIP_Report1');

Route::post('rptWIPReport1',[PPCMasterController::class,'rptWIPReport1'])->name('rptWIPReport1');

Route::get('GetMovingReportData',[FabricInwardController::class,'GetMovingReportData'])->name('GetMovingReportData');

Route::get('GetNonMovingReportData',[FabricInwardController::class,'GetNonMovingReportData'])->name('GetNonMovingReportData');

Route::resource('RejectedPcsDeliveryChallan', RejectedPcsDeliveryChallanController::class);

Route::get('Reject_GetOrdarQtyByRow',[RejectedPcsDeliveryChallanController::class,'Reject_GetOrdarQtyByRow'])->name('Reject_GetOrdarQtyByRow');

Route::get('Reject_PC_GetOrderQty',[RejectedPcsDeliveryChallanController::class,'Reject_PC_GetOrderQty'])->name('Reject_PC_GetOrderQty');

Route::post('rptCuttingOCR1',[BuyerPurchaseOrderController::class,'rptCuttingOCR1'])->name('rptCuttingOCR1');

});  