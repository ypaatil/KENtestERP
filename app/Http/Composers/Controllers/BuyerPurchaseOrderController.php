<?php
namespace App\Http\Controllers;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\MerchantMasterModel;
use App\Models\PDMerchantMasterModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\SizeDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\OrderGroupModel;
use App\Models\BrandModel;
use App\Models\CurrencyModel;
use App\Models\PaymentTermsModel;
use App\Models\DeliveryTermsModel;
use App\Models\ShipmentModeModel;
use App\Models\WarehouseModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\SeasonModel;
use App\Models\SalesOrderDetailModel;
use App\Models\Country;
use Illuminate\Http\Request;
use Session;
use Image;
 

class BuyerPurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $job_status_id= 0;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
       ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name' ]);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_value=0;
    $total_qty=0;
    $open_qty=0;
    $shipped_qty=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
    
    $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function SalesOrderOpen()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_value=0;
    $total_qty=0;
    $open_qty=0;
    $shipped_qty=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
    
    $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
    }
    
   
   public function SalesOrderClosed()
    {
           $job_status_id= 2;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '2')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     $total_value=0;
    $total_qty=0;
    $open_qty=0;
    $shipped_qty=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
    
    $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
     }
       
public function SalesOrderCancelled()
    {
           $job_status_id= 3;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
       ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '3')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_value=0;
    $total_qty=0;
    $open_qty=0;
    $shipped_qty=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
    
    $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
    }
    
    
       public function OpenSalesOrderDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '95')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
       ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
        //   DB::enableQueryLog();
       $OpenOrderList = DB::select("SELECT  
       buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name,
    sum(total_qty) as OrderQty, sum(shipped_qty) as shipped_qty, sum(balance_qty) as balance_qty  
    FROM `buyer_purchse_order_master` 
     left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
    left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
    left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
    left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
    where buyer_purchse_order_master.job_status_id=1
    group by main_style_master.mainstyle_id, buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.userId
    ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      return view('OpenSalesOrderDashboard', compact('OpenOrderList','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
        
     }
    
    
    
        public function BuyerOpenSalesOrderDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '118')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
            //   DB::enableQueryLog();
        $OpenOrderList = DB::select("SELECT  
        buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name,
        sum(total_qty) as OrderQty, sum(shipped_qty) as shipped_qty, sum(balance_qty) as balance_qty  
        FROM `buyer_purchse_order_master` 
        left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
        left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
        left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
        left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
        where buyer_purchse_order_master.job_status_id=1
        group by buyer_purchse_order_master.Ac_code, main_style_master.mainstyle_id, buyer_purchse_order_master.userId
        ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      return view('BuyerOpenSalesOrderDashboard', compact('OpenOrderList','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
        
     }
    
         public function SalesOrderCostingBOMStatusDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '118')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
            //   DB::enableQueryLog();
        $BOMCostingStatusList = DB::select("SELECT  tr_code,po_code, 
        buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, style_no,
        order_received_date,brand_name,shipment_date,order_rate, total_qty  ,  shipped_qty ,
        ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
        ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
        ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
        FROM `buyer_purchse_order_master` 
        left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
        left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
        left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
        left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
        left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
        where buyer_purchse_order_master.job_status_id=1
        ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      return view('SalesOrderCostingBOMStatusDashboard', compact('BOMCostingStatusList','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
        
     }
     
     
     
     
          public function CostingOHPDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '118')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel:: join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
            //   DB::enableQueryLog();
        $BOMCostingStatusList = DB::select("SELECT  tr_code,po_code, 
        buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
        order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,
        (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,
        
        ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
        ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
        ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
        FROM `buyer_purchse_order_master` 
        left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
        left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
        left outer join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
        left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
        left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
        left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
        where buyer_purchse_order_master.job_status_id=1
        ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      return view('CostingOHPDashboard', compact('BOMCostingStatusList','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
        
     }
    
     public function OpenSalesOrderDetailDashboard()
    {
           $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
         //   DB::enableQueryLog();
       $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
          ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
       // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('OpenSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
     
       public function OpenSalesOrderMonthDetailDashboard()
    {
           $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
         $ShipmentMonth = DB::select('select distinct DATE_FORMAT(shipment_date,"%M-%Y") as ShipMonth
         from buyer_purchse_order_master where buyer_purchse_order_master.job_status_id=1 group by buyer_purchse_order_master.shipment_date  order by shipment_date asc');
  
        
         //   DB::enableQueryLog();
       $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
          ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
       // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('OpenSalesOrderMonthDetailDashboard', compact('Buyer_Purchase_Order_List','ShipmentMonth','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
     
     public function GetOCRReport()
     {
         
         $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where job_status_id=1');
         
         return view('GetOCRReport', compact('SalesOrderList'));
     }
     
     public function OCRReport(Request $request)
     {
         
         $sales_order_no=$request->sales_order_no;
         
        //  DB::enableQueryLog();
         $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
       ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
        ->where('buyer_purchse_order_master.tr_code','=', $sales_order_no)
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name' ]);
   
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
         
         
         return view('rptOCRReport', compact('Buyer_Purchase_Order_List'));
     }
    
    
    
      public function CostingVSBudgetDashboard()
    {
           $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
         //   DB::enableQueryLog();
       $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
            
        join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->join('unit_master', 'unit_master.unit_id', '=', 'buyer_purchse_order_master.unit_id')
        ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        ->leftJoin('bom_master', 'bom_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username',
        'sales_order_costing_master.transaport_value',
        'sales_order_costing_master.production_value',
        'sales_order_costing_master.agent_commision_value',
        'sales_order_costing_master.other_value',
        'sales_order_costing_master.dbk_value',
        'sales_order_costing_master.fabric_value as cfabric_value',
        'sales_order_costing_master.sewing_trims_value as csewing_trims_value',
        'sales_order_costing_master.packing_trims_value as cpacking_trims_value',
        'sales_order_costing_master.total_cost_value as ctotal_cost_value',
         'bom_master.fabric_value as bfabric_value',
        'bom_master.sewing_trims_value as bsewing_trims_value',
        'bom_master.packing_trims_value as bpacking_trims_value',
        'bom_master.total_cost_value as btotal_cost_value',
        'ledger_master.Ac_name','fg_master.fg_name','unit_master.unit_name','brand_master.brand_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
       // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('CostingVSBudgetDashboard', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
       public function TotalSalesOrderDetailDashboard()
    {
           $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
         //   DB::enableQueryLog();
       $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
       ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.og_id','!=', '4')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
       // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('TotalSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
    
    
    
    
    
           public function DailyProductionDetailDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '116')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
       $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code, 
       buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no  , merchant_master.merchant_name ,
       buyer_purchse_order_master.Ac_code, ac_name, username, 
    buyer_purchase_order_detail.color_id,color_name, sum(size_qty_total) as order_qty, 
    (select ifnull(sum(size_qty_total),0)  from cut_panel_grn_detail where cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_grn_detail.sales_order_no=buyer_purchse_order_master.tr_code and cut_panel_grn_detail.cpg_date='".date('Y-m-d')."') as today_cutting_qty,
   (select  ifnull(sum(size_qty_total),0)  from cut_panel_grn_detail where cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_grn_detail.sales_order_no=buyer_purchse_order_master.tr_code) as total_cutting_qty,
    (select ifnull(sum(size_qty_total),0)  from cut_panel_issue_detail where cut_panel_issue_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_issue_detail.sales_order_no=buyer_purchse_order_master.tr_code and cut_panel_issue_detail.cpi_date='".date('Y-m-d')."') as today_cut_panel_issue,
    (select ifnull(sum(size_qty_total),0)  from cut_panel_issue_detail where cut_panel_issue_detail.color_id=buyer_purchase_order_detail.color_id and cut_panel_issue_detail.sales_order_no=buyer_purchse_order_master.tr_code) as total_cut_panel_issue,
    (select ifnull(sum(size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and stitching_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and stitching_inhouse_detail.sti_date='".date('Y-m-d')."')  as today_stitching_qty,
    (select ifnull(sum(size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and stitching_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code)  as total_stitching_qty,
   
    (select ifnull(sum(size_qty_total),0)  from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.color_id=buyer_purchase_order_detail.color_id and qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code and qcstitching_inhouse_reject_detail.qcsti_date='".date('Y-m-d')."')  as today_qcstitching_reject_qty,
    (select ifnull(sum(size_qty_total),0)  from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.color_id=buyer_purchase_order_detail.color_id and qcstitching_inhouse_reject_detail.sales_order_no=buyer_purchse_order_master.tr_code)  as total_qcstitching_reject_qty,
   
    (select ifnull(sum(size_qty_total),0)  from finishing_inhouse_detail where finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and finishing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and finishing_inhouse_detail.fns_date='".date('Y-m-d')."')  as today_finishing_qty,
    (select ifnull(sum(size_qty_total),0)  from finishing_inhouse_detail where finishing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and finishing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code)   as total_finishing_qty,
    
    (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail where packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and packing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code and packing_inhouse_detail.pki_date='".date('Y-m-d')."') as today_packing_qty,
    (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail where packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id and packing_inhouse_detail.sales_order_no=buyer_purchse_order_master.tr_code)  as total_packing_qty 
     
        
    FROM `buyer_purchse_order_master` 
    inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code
    left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
    inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
    left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
    left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
    left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
    where buyer_purchse_order_master.job_status_id=1
    group by main_style_master.mainstyle_id, buyer_purchse_order_master.Ac_code,    buyer_purchase_order_detail.color_id ,buyer_purchse_order_master.userId
    ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query); 
      return view('DailyProductionDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
        
     }
     
    
    
     public function OrderProgressDetailDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '130')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
      $ProductionOrderDetailList = DB::select("SELECT vendor_work_order_master.vw_code , vendor_work_order_master.sales_order_no,vendor_work_order_detail.po_code, LM2.Ac_name as vendorName,
       vendor_work_order_master.mainstyle_id,mainstyle_name,vendor_work_order_master.style_no  ,  
ifnull( (vendor_work_order_detail.size_qty_total),0) as order_qty,
       
    vendor_work_order_detail.color_id,color_name,    
    (select ifnull(sum(cut_panel_issue_detail.size_qty_total),0)  from cut_panel_issue_detail where cut_panel_issue_detail.color_id=vendor_work_order_detail.color_id
    and cut_panel_issue_detail.vw_code=vendor_work_order_master.vw_code) as total_cut_panel_issue,
    (select ifnull(sum(stitching_inhouse_detail.size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
    and stitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code)  as total_stitching_qty,
    (select ifnull(sum(qcstitching_inhouse_reject_detail.size_qty_total),0)  from qcstitching_inhouse_reject_detail where qcstitching_inhouse_reject_detail.color_id=vendor_work_order_detail.color_id
    and qcstitching_inhouse_reject_detail.vw_code=vendor_work_order_master.vw_code)  as total_qcstitching_reject_qty,
    (select ifnull(sum(qcstitching_inhouse_detail.size_qty_total),0)  from qcstitching_inhouse_detail where qcstitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
    and qcstitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code)  as total_qcstitching_pass_qty
    
    FROM `vendor_work_order_master` 
    inner join vendor_work_order_detail on vendor_work_order_detail.vw_code=vendor_work_order_master.vw_code
    inner join color_master on color_master.color_id=vendor_work_order_detail.color_id
    left outer join main_style_master on main_style_master.mainstyle_id=vendor_work_order_master.mainstyle_id
    left outer join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
    left outer join ledger_master LM2 on LM2.ac_code=vendor_work_order_master.vendorId
    left outer join usermaster on usermaster.userId=vendor_work_order_master.userId
      group by vendor_work_order_master.Ac_code,vendor_work_order_master.vw_code,vendor_work_order_detail.color_id 
    ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query); 
      return view('OrderProgressDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
        
     }
    
     public function OrderProgressFinishingDetailDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '131')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
      $ProductionOrderDetailList = DB::select("SELECT vendor_purchase_order_master.vpo_code , vendor_purchase_order_master.sales_order_no,
      vendor_purchase_order_detail.po_code, LM2.Ac_name as vendorName,
       vendor_purchase_order_master.mainstyle_id,mainstyle_name,vendor_purchase_order_master.style_no  ,  
ifnull( (vendor_purchase_order_detail.size_qty_total),0) as order_qty,
       
    vendor_purchase_order_detail.color_id,color_name,    
    (select ifnull(sum(size_qty_total),0)  from finishing_inhouse_detail where finishing_inhouse_detail.color_id=vendor_purchase_order_detail.color_id and finishing_inhouse_detail.vpo_code=vendor_purchase_order_master.vpo_code)   as total_finishing_qty 
   
    
    
    
    FROM `vendor_purchase_order_master` 
    inner join vendor_purchase_order_detail on vendor_purchase_order_detail.vpo_code=vendor_purchase_order_master.vpo_code
    inner join color_master on color_master.color_id=vendor_purchase_order_detail.color_id
    left outer join main_style_master on main_style_master.mainstyle_id=vendor_purchase_order_master.mainstyle_id
    left outer join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
    left outer join ledger_master LM2 on LM2.ac_code=vendor_purchase_order_master.vendorId
    left outer join usermaster on usermaster.userId=vendor_purchase_order_master.userId
    where vendor_purchase_order_master.process_id=2
    
      group by vendor_purchase_order_master.Ac_code,vendor_purchase_order_master.vpo_code,vendor_purchase_order_detail.color_id 
    ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query); 
      return view('OrderProgressFinishingDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
        
     }
    
     public function OrderProgressPackingDetailDashboard()
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '132')
        ->first();
         
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
         ->where('buyer_purchse_order_master.job_status_id','=', '1')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
      $ProductionOrderDetailList = DB::select("SELECT vendor_purchase_order_master.vpo_code , vendor_purchase_order_master.sales_order_no,
      vendor_purchase_order_detail.po_code, LM2.Ac_name as vendorName,
       vendor_purchase_order_master.mainstyle_id,mainstyle_name,vendor_purchase_order_master.style_no  ,  
ifnull( (vendor_purchase_order_detail.size_qty_total),0) as order_qty,
       
    vendor_purchase_order_detail.color_id,color_name,    
    (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail where packing_inhouse_detail.color_id=vendor_purchase_order_detail.color_id and packing_inhouse_detail.vpo_code=vendor_purchase_order_master.vpo_code)   as total_packing_qty 
     
    FROM `vendor_purchase_order_master` 
    inner join vendor_purchase_order_detail on vendor_purchase_order_detail.vpo_code=vendor_purchase_order_master.vpo_code
    inner join color_master on color_master.color_id=vendor_purchase_order_detail.color_id
    left outer join main_style_master on main_style_master.mainstyle_id=vendor_purchase_order_master.mainstyle_id
    left outer join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
    left outer join ledger_master LM2 on LM2.ac_code=vendor_purchase_order_master.vendorId
    left outer join usermaster on usermaster.userId=vendor_purchase_order_master.userId
    where vendor_purchase_order_master.process_id=3
    
      group by vendor_purchase_order_master.Ac_code,vendor_purchase_order_master.vpo_code,vendor_purchase_order_detail.color_id 
    ");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query); 
      return view('OrderProgressPackingDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
        
     }
    
    
    
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUYER_PURCHASE_ORDER'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $OrderGroupList = OrderGroupModel::where('order_group_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $PaymentTermsList = PaymentTermsModel::where('payment_term.delflag','=', '0')->get();
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $MerchantList = MerchantMasterModel::where('merchant_master.delflag','=', '0')->get();
         $PDMerchantList = PDMerchantMasterModel::where('PDMerchant_master.delflag','=', '0')->get();
        
        
        $ShipmentList = ShipmentModeModel::where('shipment_mode_master.delflag','=', '0')->get();
        $WarehouseList = WarehouseModel::where('warehouse_master.delflag','=', '0')->get();
         $CountryList = Country::where('country_master.delflag','=', '0')->get();
        
        $JobStatusList= DB::table('job_status_master')->get();
        return view('BuyerPurchaseOrderMaster',compact('OrderGroupList','MerchantList','PDMerchantList','ItemList','BrandList','SeasonList','MainStyleList','SubStyleList','CurrencyList','PaymentTermsList','DeliveryTermsList','ShipmentList','CountryList','WarehouseList', 'Ledger', 'FGList','UnitList','SizeList','ColorList','counter_number', 'JobStatusList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
   $SessionValue=Session::get('BuyerPurchase');
   if($SessionValue==1)
   {
          session()->put('BuyerPurchase','0');
          
          $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
          ->where('c_name','=','C1')
          ->where('type','=','BUYER_PURCHASE_ORDER')
           ->where('firm_id','=',1)
          ->first();
          $TrNo=$codefetch->code.'-'.$codefetch->tr_no;
       
       
        $this->validate($request, [
           
           'tr_date'=>'required',
           'Ac_code'=>'required',
           'mainstyle_id'=>'required',
           'substyle_id'=>'required',
           'fg_id'=>'required',
           'style_no'=>'required',
           'po_code'=>'required',
           'total_qty'=>'required',
           'order_rate'=>'required',
           'order_value'=>'required',
           'shipped_qty'=>'required',
           'balance_qty'=>'required',
           'job_status_id'=>'required',
           'og_id'=>'required',
           'brand_id'=>'required',
           'order_received_date'=>'required',
            
           'season_id'=>'required',
           'currency_id'=>'required',
           'ptm_id'=>'required',
           'dterm_id'=>'required',
           'style_description'=>'required',
           'unit_ids'=>'required',
           'ship_id'=>'required',
           'country_id'=>'required',
           'warehouse_id'=>'required',
           'shipment_date'=>'required',
           'plan_cut_date'=>'required',
           'inspection_date'=>'required',
           'ex_factory_date'=>'required',
          
           
           'userId'=>'required', 
           'c_code'=>'required', 
]);
 
 
 
  // Upload style_pic_path
    $style_pic_path=$request->file('style_pic_path');
    if($style_pic_path) 
    {
   
    $image = $request->file('style_pic_path');
    $input['imagename'] = time().'STL.'.$image->getClientOriginalExtension();
 
    $destinationPath = public_path('/thumbnail');
    $img = Image::make($image->getRealPath());
    $img->resize(100, 100, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
    $StyleImageName=$input['imagename'];
}
else
{
    $StyleImageName='';
}

// Upload style_pic_path End
 
// Upload File1
if($request->hasFile('doc_path1')) 
{
    $fileName1 = time().'PO.'.$request->doc_path1->extension();  
    $request->doc_path1->move(public_path('uploads'), $fileName1);
    $fullTempFilePath= public_path('uploads/');
    $output = shell_exec("shrink " . $fullTempFilePath  . $fullTempFilePath . "-compressed ");
    shell_exec("mv " . $fullTempFilePath . "-compressed " . $fullTempFilePath);
    $fullTempFilePath=$fileName1;
    // Compress and Save  File1 End
}
 else
{
    $fullTempFilePath='';
}
 
 
$data1=array(
       
'tr_code'=>$TrNo,
'tr_date'=>$request->tr_date,
'Ac_code'=>$request->Ac_code,
'mainstyle_id'=>$request->mainstyle_id,
'substyle_id'=>$request->substyle_id,
'fg_id'=>$request->fg_id,
'style_no'=>$request->style_no,
'po_code'=>$request->po_code,
'sz_code'=>$request->sz_code,
'total_qty'=>$request->total_qty,
'order_rate'=>$request->order_rate,
'order_value'=>$request->order_value,

'shipped_qty'=>$request->shipped_qty,
'balance_qty'=>$request->balance_qty,
'sz_ws_total'=>$request->sz_ws_total,
'job_status_id'=>$request->job_status_id,
'og_id'=>$request->og_id,
'brand_id'=>$request->brand_id,
'order_received_date'=>$request->order_received_date,
 
'season_id'=>$request->season_id,
'currency_id'=>$request->currency_id,
'ptm_id'=>$request->ptm_id,
'dterm_id'=>$request->dterm_id,
'style_description'=>$request->style_description,
'style_img_path'=>$StyleImageName,
'ship_id'=>$request->ship_id,
'country_id'=>$request->country_id,
'warehouse_id'=>$request->warehouse_id,
'shipment_date'=>$request->shipment_date,
'plan_cut_date'=>$request->plan_cut_date,
'inspection_date'=>$request->inspection_date,
'ex_factory_date'=>$request->ex_factory_date,
'buyer_document_path'=>$fullTempFilePath,
'narration'=>$request->narration, 
'unit_id'=>$request->unit_ids,
'merchant_id'=>$request->merchant_id,
'PDMerchant_id'=>$request->PDMerchant_id,
'userId'=>$request->userId, 
'c_code'=>$request->c_code, 
'job_status_id'=>$request->job_status_id,
'delflag'=>'0',

);
BuyerPurchaseOrderMasterModel::insert($data1);
DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='BUYER_PURCHASE_ORDER'");

$color_id = $request->input('color_id');
if(count($color_id)>0)
{

for($x=0; $x<count($color_id); $x++) {
    # code...
  
 
$data2[]=array(
          
                    'tr_code'=>$TrNo,
                    'tr_date'=>$request->tr_date,
                    'Ac_code'=>$request->Ac_code,
                    'po_code'=>$request->po_code,
                    'style_no'=>$request->style_no,
                    'color_id'=>$request->color_id[$x],
                    'item_code'=>$request->item_code[$x],
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array[$x],
                    'size_qty_total'=>$request->size_qty_total[$x],
                    'unit_id'=>$request->unit_ids,
                    'shipment_allowance'=>$request->shipment_allowance[$x],
                    'garment_rejection_allowance'=>$request->garment_rejection_allowance[$x], 
         );
        
        
         $s1=isset($request->s1[$x]) ? $request->s1[$x] : 0; $s11=isset($request->s11[$x]) ? $request->s11[$x] : 0;
                      $s2=isset($request->s2[$x]) ? $request->s2[$x] : 0; $s12=isset($request->s12[$x]) ? $request->s12[$x] : 0;
                      $s3=isset($request->s3[$x]) ? $request->s3[$x] : 0; $s13=isset($request->s13[$x]) ? $request->s13[$x] : 0;
                      $s4=isset($request->s4[$x]) ? $request->s4[$x] : 0; $s14=isset($request->s14[$x]) ? $request->s14[$x] : 0;
                      $s5=isset($request->s5[$x]) ? $request->s5[$x] : 0; $s15=isset($request->s15[$x]) ? $request->s15[$x] : 0;
                      $s6=isset($request->s6[$x]) ? $request->s6[$x] : 0; $s16=isset($request->s16[$x]) ? $request->s16[$x] : 0;
                      $s7=isset($request->s7[$x]) ? $request->s7[$x] : 0; $s17=isset($request->s17[$x]) ? $request->s17[$x] : 0;
                      $s8=isset($request->s8[$x]) ? $request->s8[$x] : 0; $s18=isset($request->s18[$x]) ? $request->s18[$x] : 0;
                      $s9=isset($request->s9[$x]) ? $request->s9[$x] : 0; $s19=isset($request->s19[$x]) ? $request->s19[$x] : 0;
                      $s10=isset($request->s10[$x]) ? $request->s10[$x] : 0; $s20=isset($request->s20[$x]) ? $request->s20[$x] : 0;


                      $data3[]=array(
                  
                        'tr_code'=>$TrNo,
                        'tr_date'=>$request->tr_date,
                        'Ac_code'=>$request->Ac_code,
                        'po_code'=>$request->po_code,
                        'style_no'=>$request->style_no,
                        'color_id'=>$request->color_id[$x],
                        'item_code'=>$request->item_code[$x],
                        'size_array'=>$request->size_array[$x],
                        's1'=>$s1,
                        's2'=>$s2,
                        's3'=>$s3,
                        's4'=>$s4,
                        's5'=>$s5,
                        's6'=>$s6,
                        's7'=>$s7,
                        's8'=>$s8,
                        's9'=>$s9,
                        's10'=>$s10,
                        's11'=>$s11,
                        's12'=>$s12,
                        's13'=>$s13,
                        's14'=>$s14,
                        's15'=>$s15,
                        's16'=>$s16,
                        's17'=>$s17,
                        's18'=>$s18,
                        's19'=>$s19,
                        's20'=>$s20,
                        'size_qty_total'=>$request->size_qty_total[$x],
                        'unit_id'=>$request->unit_ids,
                        'shipment_allowance'=>$request->shipment_allowance[$x],
                        'garment_rejection_allowance'=>$request->garment_rejection_allowance[$x],
                          );
        
        
        }
 BuyerPurchaseOrderDetailModel::insert($data2);
 SalesOrderDetailModel::insert($data3);
 
}
 $request->session()->put('BuyerPurchase','0');
 return redirect()->route('BuyerPurchaseOrder.index')->with('message', 'New Record Saved Succesfully..!');

}
else
{
     return redirect()->route('BuyerPurchaseOrder.index')->with('message', 'Record Already Saved..!!');
}


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BuyerPurchaseOrderMasterModel  $buyerPurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function show($tr_code)
    {
       $SalesOrderCostingMaster = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
        ->join('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'buyer_purchse_order_master.season_id', 'left outer') 
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer') 
        ->join('payment_term', 'payment_term.ptm_id', '=', 'buyer_purchse_order_master.ptm_id', 'left outer') 
        ->join('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 'buyer_purchse_order_master.dterm_id', 'left outer') 
        ->join('shipment_mode_master', 'shipment_mode_master.ship_id', '=', 'buyer_purchse_order_master.ship_id', 'left outer')  
        ->join('warehouse_master', 'warehouse_master.warehouse_id', '=', 'buyer_purchse_order_master.warehouse_id', 'left outer')  
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.tr_code','=', $tr_code)
        
        ->get(['buyer_purchse_order_master.*','usermaster.username',
        'ledger_master.Ac_name','fg_master.fg_name','job_status_master.job_status_name',
        'order_group_master.order_group_name',
        'season_master.season_name','brand_master.brand_name',
        'payment_term.ptm_name','delivery_terms_master.delivery_term_name','shipment_mode_master.ship_mode_name','warehouse_master.warehouse_name']);
        
        
      $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();   
        
    
    return view('saleOrderPrint',compact('SalesOrderCostingMaster','SizeList'));  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BuyerPurchaseOrderMasterModel  $buyerPurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $OrderGroupList = OrderGroupModel::where('order_group_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $PaymentTermsList = PaymentTermsModel::where('payment_term.delflag','=', '0')->get();
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $ShipmentList = ShipmentModeModel::where('shipment_mode_master.delflag','=', '0')->get();
        $WarehouseList = WarehouseModel::where('warehouse_master.delflag','=', '0')->get();
        $CountryList = Country::where('country_master.delflag','=', '0')->get();
        $MerchantList = MerchantMasterModel::where('merchant_master.delflag','=', '0')->get();
        $PDMerchantList = PDMerchantMasterModel::where('PDMerchant_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.cat_id','=', '1')->get();
        $JobStatusList= DB::table('job_status_master')->get();
        $CostingStatus= DB::select("select ifnull(sales_order_costing_master.is_approved,0) from sales_order_costing_master where sales_order_no='".$id."'");
       
        if(isset($CostingStatus[0]->is_approved)){ $is_approved=$CostingStatus[0]->is_approved;}else{$is_approved=2;}
         //  $Address = DB::select('select consignee_address from ledger_details where Ac_code='.$request->Ac_code.' and site_code="'.$request->site_code.'"');
         $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($id);
        // DB::enableQueryLog();
        
         $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
        $BuyerPurchaseOrderDetaillist = BuyerPurchaseOrderDetailModel::where('buyer_purchase_order_detail.tr_code','=', $BuyerPurchaseOrderMasterList->tr_code)
        ->get(['buyer_purchase_order_detail.*']);
 // DB::enableQueryLog();
     $BOMCheck= DB::table('bom_master')->where('bom_master.sales_order_no',$BuyerPurchaseOrderMasterList->tr_code)->count();
 // echo $BOMCheck;
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  return view('BuyerPurchaseOrderMasterEdit',compact('OrderGroupList','BOMCheck','is_approved','MerchantList','PDMerchantList','SizeDetailList','ItemList','BrandList','SeasonList','MainStyleList','SubStyleList','CurrencyList','PaymentTermsList','DeliveryTermsList','ShipmentList','CountryList','WarehouseList','BuyerPurchaseOrderMasterList','UnitList', 'Ledger','FGList','SizeList', 'ColorList',  'JobStatusList','BuyerPurchaseOrderDetaillist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BuyerPurchaseOrderMasterModel  $buyerPurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
            $this->validate($request, [
            
           'tr_date'=>'required',
           'Ac_code'=>'required',
           'mainstyle_id'=>'required',
           'substyle_id'=>'required',
           'fg_id'=>'required',
           'style_no'=>'required',
           'po_code'=>'required',
           'total_qty'=>'required',
           'order_rate'=>'required',
           'order_value'=>'required',
           'shipped_qty'=>'required',
           'balance_qty'=>'required',
           'job_status_id'=>'required',
           'og_id'=>'required',
           'brand_id'=>'required',
           'order_received_date'=>'required',
           'unit_ids'=>'required',
           'season_id'=>'required',
           'currency_id'=>'required',
           'ptm_id'=>'required',
           'dterm_id'=>'required',
           'style_description'=>'required',
           'ship_id'=>'required',
           'country_id'=>'required',
           'warehouse_id'=>'required',
           'shipment_date'=>'required',
           'plan_cut_date'=>'required',
           'inspection_date'=>'required',
           'ex_factory_date'=>'required',
           'userId'=>'required', 
           'c_code'=>'required', 

]);


// Upload style_pic_path
    $style_pic_path=$request->file('style_pic_path');
    if($style_pic_path) 
    {
   
    $image = $request->file('style_pic_path');
    $input['imagename'] = time().'STL.'.$image->getClientOriginalExtension();
 
    $destinationPath = public_path('/thumbnail');
    $img = Image::make($image->getRealPath());
    $img->resize(100, 100, function ($constraint) {
        $constraint->aspectRatio();
    })->save($destinationPath.'/'.$input['imagename']);

    $destinationPath1 = public_path('/images/');
   // if (!is_dir($destinationPath)){mkdir($destinationPath);}
    $image->move($destinationPath1, $input['imagename']);
    $StyleImageName=$input['imagename'];
    
    if($request->style_pic_pathold!='')
    {
    unlink('thumbnail/'.$request->style_pic_pathold);
    unlink('images/'.$request->style_pic_pathold);
    }
}
else
{
    $StyleImageName=$request->style_pic_pathold;
}

// Upload style_pic_path End
 
// Upload File1
if($request->hasFile('doc_path1')) 
{
    $fileName1 = time().'PO.'.$request->doc_path1->extension();  
    $request->doc_path1->move(public_path('uploads'), $fileName1);
    $fullTempFilePath= public_path('uploads/');
    $output = shell_exec("shrink " . $fullTempFilePath  . $fullTempFilePath . "-compressed ");
    shell_exec("mv " . $fullTempFilePath . "-compressed " . $fullTempFilePath);
    $fullTempFilePath=$fileName1;
    // Compress and Save  File1 End
   
 unlink('uploads/'.$request->doc_path1old);
     
}
 else
{
    $fullTempFilePath=$request->doc_path1old; 
}



 
$data1=array(
      
'tr_code'=>$request->tr_code,
'tr_date'=>$request->tr_date,
'Ac_code'=>$request->Ac_code,
'mainstyle_id'=>$request->mainstyle_id,
'substyle_id'=>$request->substyle_id,
'fg_id'=>$request->fg_id,
'style_no'=>$request->style_no,
'po_code'=>$request->po_code,
'sz_code'=>$request->sz_code,
'total_qty'=>$request->total_qty,
'order_rate'=>$request->order_rate,
'order_value'=>$request->order_value,
'shipped_qty'=>$request->shipped_qty,
'balance_qty'=>$request->balance_qty,
'sz_ws_total'=>$request->sz_ws_total,
'job_status_id'=>$request->job_status_id,
'og_id'=>$request->og_id,
'brand_id'=>$request->brand_id,
'order_received_date'=>$request->order_received_date,
 
'season_id'=>$request->season_id,
'currency_id'=>$request->currency_id,
'ptm_id'=>$request->ptm_id,
'dterm_id'=>$request->dterm_id,
'style_description'=>$request->style_description,
'style_img_path'=>$StyleImageName,
'ship_id'=>$request->ship_id,
'country_id'=>$request->country_id,
'warehouse_id'=>$request->warehouse_id,
'shipment_date'=>$request->shipment_date,
'plan_cut_date'=>$request->plan_cut_date,
'inspection_date'=>$request->inspection_date,
'ex_factory_date'=>$request->ex_factory_date,
'buyer_document_path'=>$fullTempFilePath,
'narration'=>$request->narration, 
'unit_id'=>$request->unit_ids,
'merchant_id'=>$request->merchant_id, 
'PDMerchant_id'=>$request->PDMerchant_id,
'userId'=>$request->userId, 
'c_code'=>$request->c_code, 
'job_status_id'=>$request->job_status_id,
'delflag'=>'0',
'created_at'=>$request->created_at,
    
);
 
        $BuyerPurchaseOrderList = BuyerPurchaseOrderMasterModel::findOrFail($request->input('tr_code'));  
        $BuyerPurchaseOrderList->fill($data1)->save();

        DB::table('buyer_purchase_order_detail')->where('tr_code', $request->input('tr_code'))->delete();
         DB::table('sales_order_detail')->where('tr_code', $request->input('tr_code'))->delete();
        

        $color_id = $request->input('color_id');
        if(count($color_id)>0)
        {
        
        for($x=0; $x<count($color_id); $x++) 
        {
            # code...
          
            $data2[]=array(
                  
                            'tr_code'=>$request->tr_code,
                            'tr_date'=>$request->tr_date,
                            'Ac_code'=>$request->Ac_code,
                            'po_code'=>$request->po_code,
                            'style_no'=>$request->style_no,
                            'color_id'=>$request->color_id[$x],
                            'item_code'=>$request->item_code[$x],
                            'size_array'=>$request->size_array[$x],
                            'size_qty_array'=>$request->size_qty_array[$x],
                            'size_qty_total'=>$request->size_qty_total[$x],
                            'unit_id'=>$request->unit_ids,
                            'shipment_allowance'=>$request->shipment_allowance[$x],
                            'garment_rejection_allowance'=>$request->garment_rejection_allowance[$x],
              
                 );
      
                      $s1=isset($request->s1[$x]) ? $request->s1[$x] : 0; $s11=isset($request->s11[$x]) ? $request->s11[$x] : 0;
                      $s2=isset($request->s2[$x]) ? $request->s2[$x] : 0; $s12=isset($request->s12[$x]) ? $request->s12[$x] : 0;
                      $s3=isset($request->s3[$x]) ? $request->s3[$x] : 0; $s13=isset($request->s13[$x]) ? $request->s13[$x] : 0;
                      $s4=isset($request->s4[$x]) ? $request->s4[$x] : 0; $s14=isset($request->s14[$x]) ? $request->s14[$x] : 0;
                      $s5=isset($request->s5[$x]) ? $request->s5[$x] : 0; $s15=isset($request->s15[$x]) ? $request->s15[$x] : 0;
                      $s6=isset($request->s6[$x]) ? $request->s6[$x] : 0; $s16=isset($request->s16[$x]) ? $request->s16[$x] : 0;
                      $s7=isset($request->s7[$x]) ? $request->s7[$x] : 0; $s17=isset($request->s17[$x]) ? $request->s17[$x] : 0;
                      $s8=isset($request->s8[$x]) ? $request->s8[$x] : 0; $s18=isset($request->s18[$x]) ? $request->s18[$x] : 0;
                      $s9=isset($request->s9[$x]) ? $request->s9[$x] : 0; $s19=isset($request->s19[$x]) ? $request->s19[$x] : 0;
                      $s10=isset($request->s10[$x]) ? $request->s10[$x] : 0; $s20=isset($request->s20[$x]) ? $request->s20[$x] : 0;


                      $data3[]=array(
                  
                        'tr_code'=>$request->tr_code,
                        'tr_date'=>$request->tr_date,
                        'Ac_code'=>$request->Ac_code,
                        'po_code'=>$request->po_code,
                        'style_no'=>$request->style_no,
                        'color_id'=>$request->color_id[$x],
                        'item_code'=>$request->item_code[$x],
                        'size_array'=>$request->size_array[$x],
                        's1'=>$s1,
                        's2'=>$s2,
                        's3'=>$s3,
                        's4'=>$s4,
                        's5'=>$s5,
                        's6'=>$s6,
                        's7'=>$s7,
                        's8'=>$s8,
                        's9'=>$s9,
                        's10'=>$s10,
                        's11'=>$s11,
                        's12'=>$s12,
                        's13'=>$s13,
                        's14'=>$s14,
                        's15'=>$s15,
                        's16'=>$s16,
                        's17'=>$s17,
                        's18'=>$s18,
                        's19'=>$s19,
                        's20'=>$s20,
                        'size_qty_total'=>$request->size_qty_total[$x],
                        'unit_id'=>$request->unit_ids,
                        'shipment_allowance'=>$request->shipment_allowance[$x],
                        'garment_rejection_allowance'=>$request->garment_rejection_allowance[$x],
                          );
                
                }
 
 BuyerPurchaseOrderDetailModel::insert($data2);
 SalesOrderDetailModel::insert($data3);
    }


    return redirect()->route('BuyerPurchaseOrder.index')->with('message', 'Update Record Succesfully..!');
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BuyerPurchaseOrderMasterModel  $buyerPurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('buyer_purchse_order_master')->where('tr_code', $id)->delete();
        DB::table('sales_order_detail')->where('tr_code', $id)->delete();
        DB::table('buyer_purchase_order_size_detail')->where('tr_code', $id)->delete();
        DB::table('buyer_purchase_order_detail')->where('tr_code', $id)->delete();
        return redirect()->route('BuyerPurchaseOrder.index')->with('message', 'Delete Record Succesfully');
        
    }


    public function GetTaxList(Request $request)
    {
          
        $TaxList = DB::select('select item_code , cgst_per, sgst_per, igst_per from item_master where item_code='.$request->item_code);
        return json_encode($TaxList);
    
    }
    
    
    public function GetBrandList(Request $request)
    { $html = '';
          if (!$request->Ac_code) {
        $html = '<option value="">--Brand Name--</option>';
        } else {
       
         $html = '<option value="">--Brand Name--</option>';
        $StyleList = DB::table('brand_master')->where('Ac_code', $request->Ac_code)->get();
        
        foreach ($StyleList as $row) {
                $html .= '<option value="'.$row->brand_id.'">'.$row->brand_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    } 
    
     public function GetSeasonList(Request $request)
    { $html = '';
        if (!$request->Ac_code) 
        {
            $html = '<option value="">--Season Name--</option>';
        } 
        else 
        {
            $html = '<option value="">--Season Name--</option>';
            $StyleList = DB::table('season_master')->where('Ac_code', $request->Ac_code)->get();
        
        foreach ($StyleList as $row) 
        {
            $html .= '<option value="'.$row->season_id.'">'.$row->season_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    } 
    
    
    
    public function GetSizeDetailList(Request $request)
    { 
    $sz_code= $request->input('sz_code');
    
    $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
    $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
    $ItemList=ItemModel::where('item_master.delflag','=', '0')->get();
    $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $sz_code)->get();
    
    $sizes='';
    
    foreach ($SizeDetailList as $sz) 
    {
        
        $sizes=$sizes.$sz->size_id.',';
    }
    $sizes=rtrim($sizes,',');
    
    
    
    $html = '';
    
    $html .= ' <div class="table-wrap" id="divSelect">
    <div class="table-responsive">
     <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
            <thead>
            <tr>
            <th>SrNo</th>
             <th>Fabric Color Code</th>
            <th>Garment Color</th>';
               foreach ($SizeDetailList as $sz) 
                {
                    $html.='<th>'.$sz->size_name.'</th>';
                     
                }
                $html.=' 
                <th>Total Qty</th>
                
                <th>Shipment Allowance</th>
                
                <th>Add/Remove</th>
            </tr>
            </thead>
            <tbody>';
        $no=1;
        
        $html .='<tr>';
        $html .='
        <td><input type="text" name="id[]" value="'.$no.'" id="id0" style="width:50px;"/></td>
        
        <td> <select name="item_code[]" class="select2-select"  id="item_code0" style="width:250px; height:30px;" required>
        <option value="">--Select Item--</option>';

        foreach($ItemList as  $row2)
        {
            $html.='<option value="'.$row2->item_code.'"';
            $html.='>'.$row2->item_name.'</option>';
        }
        
        $html.='</select></td> 
        
        <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $row1)
        {
            $html.='<option value="'.$row1->color_id.'"';
            $html.='>'.$row1->color_name.'</option>';
        }
        
        $html.='</select></td>';
        $n=1;
        foreach ($SizeDetailList as $row) 
        {
            $html.='<td><input type="number" name="s'.$n.'[]" class="size_id"   value="0" id="size_id0" style="width:80px; height:30px;" onkeyup="mycalc();" /></td>';
            $n=$n+1;
        }
        $html.='
         <td class="track">
        <input type="number" readOnly name="size_qty_total[]" class="QTY" value="0" id="size_qty_total" style="width:80px; height:30px;""  /> <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px;"  />
        <input type="hidden" name="size_array[]"  value="'.$sizes.'" id="size_array" style="width:80px; "  /></td>
          
          <td><input type="number" step="0.01" name="shipment_allowance[]"  value="0" id="shipment_allowance" style="width:80px;" required />
           <input type="hidden" step="0.01" name="garment_rejection_allowance[]"   value="0" id="garment_rejection_allowance" style="width:80px;" required />
        </td>
        <td>
        <input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" class="Abutton btn btn-warning pull-left"> <input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
     $html .='</tr>
     
      </tbody>
   
</table> 
</div>
</div>
<table id="footable_1" style=" margin-left:auto;margin-right:auto;">
<tbody>
<tr>
<th colspan="2">Total</th>
<th colspan="2"> &nbsp;</th>
<th colspan="2">&nbsp; </th>';
    $nx=1;
    foreach ($SizeDetailList as $row) 
        {
            $html.='<th> '.$row->size_name.' <input type="number" name="s'.$nx.'total[]" class="size_total"   value="" id="s'.$nx.'total" style="width:80px; height:30px;"  " /></th> ';
            $nx=$nx+1;
        }
  $html.='<th><input type="hidden" name="sz_ws_total" value="" id="sz_ws_total" style="width:80px; height:30px;"  " /> </th>
    <th></th>
    <th></th>
    <th></th>
    </tr> <tbody> </table>
       ';     
               return response()->json(['html' => $html]);
            
    }

    public function getAddress(Request $request)
    {
          
        $Address = DB::select('select consignee_address from ledger_details where Ac_code='.$request->Ac_code.' and site_code="'.$request->site_code.'"');
        return json_encode($Address);
    
    }

}
