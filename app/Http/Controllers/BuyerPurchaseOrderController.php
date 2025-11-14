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
use App\Models\SalesOrderCostingMasterModel;
use App\Models\StockAssociationForFabricModel;
use App\Models\StockAssociationModel;
use App\Models\StyleNoModel;
use App\Models\Country;
use Illuminate\Http\Request;
use Session;
use Image;
use DataTables;
use Mail;
use DateTime;
use App\Mail\SalesOrderEmail;
use Carbon\Carbon;
use App\Models\SourceModel;
use App\Models\DestinationModel;
use App\Services\SalesOrderDetailActivityLog;
use App\Services\SalesOrderMasterActivityLog;
use Log;

setlocale(LC_MONETARY, 'en_IN');
class BuyerPurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
         $job_status_id= 0;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first(); 
        
        // $Buyer_Purchase_Order_List = DB::table('buyer_brand_auth_details')
        //     ->select(
        //         'buyer_purchse_order_master.*',
        //         'usermaster.username',
        //         'ledger_master.ac_short_name as Ac_name',
        //         'fg_master.fg_name',
        //         'brand_master.brand_name',
        //         'merchant_master.merchant_name',
        //         'job_status_master.job_status_name',
        //         'main_style_master.mainstyle_name',
        //         DB::raw("(select ifnull(sum(order_qty), 0) from sale_transaction_detail where sales_order_no = buyer_purchse_order_master.tr_code) as ShippedQty")
        //     )
        //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.userId', '=', 'buyer_purchse_order_master.userId')
        //     ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        //     ->join('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
        //     ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        //     ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
        //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
            // ->where('buyer_purchse_order_master.userId', '=', function ($query) {
            //     $query->select('userId')
            //           ->from('buyer_brand_auth_details')
            //           ->limit(1);
            // })
            // ->where('buyer_purchse_order_master.brand_id', '=', function ($query) {
            //     $query->select('brand_id')
            //           ->from('buyer_brand_auth_details')
            //           ->where('buyer_brand_auth_details.auth_id', '=', 1)
            //           ->limit(1);
            // })
            // ->where('buyer_purchse_order_master.delflag', '0')
            // ->where('buyer_purchse_order_master.og_id', '!=', '4')
            // ->get();
         //DB::enableQueryLog();
         $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.ac_short_name as Ac_name,fg_master.fg_name,brand_master.brand_name,buyer_purchse_order_master.style_no,
                merchant_master.merchant_name,main_style_master.mainstyle_name,buyer_purchse_order_master.order_close_date
                FROM buyer_purchse_order_master  
                INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId 
                INNER JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id 
                INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
                INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id 
                INNER JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")");
                
         //dd(DB::getQueryLog());

         if ($request->ajax()) 
         {
        
                return Datatables::of($Buyer_Purchase_Order_List)
                ->addIndexColumn()
                ->addColumn('tr_code1',function ($row) {
            
                     $tr_codeData =substr($row->tr_code, strpos($row->tr_code, '-') + 1);
            
                     return $tr_codeData;
                }) 
                ->addColumn('action1', function ($row) 
                {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SaleOrderPrint/'.$row->tr_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 || Session::get('user_type') == 1  )
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('BuyerPurchaseOrder.edit', $row->tr_code).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->tr_code.'"  data-route="'.route('BuyerPurchaseOrder.destroy', $row->tr_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['action1','action2','action3'])
        
                ->make(true);
        }
    //       return view('FGStockReport');
          
    //      //   DB::enableQueryLog();
    //     $Buyer_Purchase_Order_List = DB::table('buyer_purchse_order_master')->
    //         select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','brand_master.brand_name',
    //         'merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name', DB::raw("(select ifnull(sum(order_qty),0)   from sale_transaction_detail where sales_order_no=buyer_purchse_order_master.tr_code) as 'ShippedQty'") )
    //         ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
    //   ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
    //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
    //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
    //     ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
    //     ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
    //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
    //     ->where('buyer_purchse_order_master.delflag','=', '0')
    //     ->where('buyer_purchse_order_master.og_id','!=', '4')
    //     ->get();
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    $total_value=0;
    $total_qty=0;
    $open_qty=0;
    $shipped_qty=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
    
    $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
     }

public function SalesOrderPrintView($tr_code)
    {
         $SalesOrderCostingMaster = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
        ->join('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'buyer_purchse_order_master.season_id', 'left outer') 
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer') 
        ->join('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 'buyer_purchse_order_master.dterm_id', 'left outer') 
        ->join('shipment_mode_master', 'shipment_mode_master.ship_id', '=', 'buyer_purchse_order_master.ship_id', 'left outer')  
        ->join('warehouse_master', 'warehouse_master.warehouse_id', '=', 'buyer_purchse_order_master.warehouse_id', 'left outer')  
        ->join('payment_term', 'payment_term.ptm_id', '=', 'buyer_purchse_order_master.ptm_id', 'left outer')
         ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
         ->join('PDMerchant_master', 'PDMerchant_master.PDMerchant_id','=','buyer_purchse_order_master.PDMerchant_id')
          ->join('country_master', 'country_master.c_id', '=', 'buyer_purchse_order_master.country_id')
        
        

        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.tr_code','=', $tr_code)
          ->select(
        'buyer_purchse_order_master.*',
        'brand_master.brand_name',
        'fg_master.fg_name',
        'payment_term.ptm_name',
        'shipment_mode_master.ship_mode_name',
        'merchant_master.merchant_name',
        'PDMerchant_master.PDMerchant_name',
        'country_master.c_name',
        'warehouse_master.warehouse_name',
        'delivery_terms_master.delivery_term_name',
        'order_group_master.order_group_name',
        'ledger_master.ac_name'
    )
        
        ->get(['buyer_purchse_order_master.*','usermaster.username',
        'ledger_master.Ac_name','fg_master.fg_name','job_status_master.job_status_name',
        'order_group_master.order_group_name',
        'season_master.season_name','brand_master.brand_name','delivery_terms_master.delivery_term_name','shipment_mode_master.ship_mode_name','warehouse_master.warehouse_name','buyer_purchse_order_master.sam']);
       // print_r($SalesOrderCostingMaster[0]);exit;
        
      $SizeList= SizeModel::where('delflag','=', '0')->get();   
        
    
    return view('SalesOrderPrintView',compact('SalesOrderCostingMaster','SizeList'));  
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public function SalesOrderSample(Request $request)
     {  
         $job_status_id= 0;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
            select('buyer_purchse_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_no','buyer_purchse_order_master.order_close_date')
            ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId', 'left outer')
            ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
            ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id', 'left outer')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
            ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
            ->where('buyer_purchse_order_master.delflag','=', '0')
            ->where('buyer_purchse_order_master.og_id','=', '4')
            ->get();
        
        if ($request->ajax()) 
         { 
                return Datatables::of($Buyer_Purchase_Order_List)
                ->addIndexColumn()
                ->addColumn('tr_code1',function ($row) {
            
                     $tr_codeData =substr($row->tr_code, strpos($row->tr_code, '-') + 1);
            
                     return $tr_codeData;
                }) 
                ->addColumn('action1', function ($row) 
                {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SaleOrderPrint/'.$row->tr_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('BuyerPurchaseOrder.edit', $row->tr_code).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->tr_code.'"  data-route="'.route('BuyerPurchaseOrder.destroy', $row->tr_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['action1','action2','action3'])
        
                ->make(true);
        }
        
        $total_value=0;
        $total_qty=0;
        $open_qty=0;
        $shipped_qty=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
        
        $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
}
     
     
     
     public function SalesOrderOpen(Request $request)
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        //  ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        //  ->where('buyer_purchse_order_master.job_status_id','=', '1')
        //   ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name', 
        //     DB::raw("(select ifnull(sum(order_qty),0)   from sale_transaction_detail where sales_order_no=buyer_purchse_order_master.tr_code) as 'ShippedQty'")]);
         
         $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.ac_short_name as Ac_name,fg_master.fg_name,brand_master.brand_name,
                merchant_master.merchant_name,job_status_master.job_status_name,main_style_master.mainstyle_name,buyer_purchse_order_master.style_no,buyer_purchse_order_master.order_close_date
                FROM buyer_purchse_order_master  
                INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId 
                INNER JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id 
                INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
                INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id 
                INNER JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id = 1 AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")");
                
                
         if ($request->ajax()) 
         {
        
                return Datatables::of($Buyer_Purchase_Order_List)
                ->addIndexColumn()
                ->addColumn('tr_code1',function ($row) {
            
                     $tr_codeData =substr($row->tr_code, strpos($row->tr_code, '-') + 1);
            
                     return $tr_codeData;
                }) 
                ->addColumn('action1', function ($row) 
                {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SaleOrderPrint/'.$row->tr_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 || Session::get('user_type') == 1  )
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('BuyerPurchaseOrder.edit', $row->tr_code).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->tr_code.'"  data-route="'.route('BuyerPurchaseOrder.destroy', $row->tr_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['action1','action2','action3'])
        
                ->make(true);
        }
        $total_value=0;
        $total_qty=0;
        $open_qty=0;
        $shipped_qty=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
        
        $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
    }
    
    
    
    
        
    public function BuyerSalesOrderSizeQtyDashboard(Request $request)
    { 
        ini_set('memory_limit', '10G'); 
        
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : '';
        $fromDate =  isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");
        
        $filter = " AND buyer_purchse_order_master.tr_date BETWEEN '".$fromDate."' AND '".$toDate."'";
        
        if($sales_order_no != '')
        {
             $filter .= " AND buyer_purchse_order_master.tr_code='".$sales_order_no."'";
        }
        
        if ($request->ajax())
        {
            $FabricCheckData = DB::select("SELECT buyer_purchse_order_master.`tr_code`,buyer_purchse_order_master.`tr_date`,
            brand_master.brand_name, main_style_master.mainstyle_name,fg_master.fg_name, 
            buyer_purchse_order_master.`tr_date`, ledger_master.ac_short_name as buyer,
            buyer_purchse_order_master.`po_code`, buyer_purchse_order_master.`style_no`,
            color_master.`color_name`, item_master.item_name, size_detail.size_name, `size_qty`,
            job_status_master.job_status_name FROM `buyer_purchase_order_size_detail` 
            inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=buyer_purchase_order_size_detail.tr_code
            inner join ledger_master on ledger_master.ac_code=buyer_purchase_order_size_detail.Ac_code
            inner join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            inner join main_style_master on main_style_master.mainstyle_id= buyer_purchse_order_master.mainstyle_id
            inner join fg_master on fg_master.fg_id=buyer_purchse_order_master.fg_id
            inner join item_master on item_master.item_code=buyer_purchase_order_size_detail.item_code
            inner join color_master on  color_master.color_id=buyer_purchase_order_size_detail.color_id
            inner join size_detail on size_detail.size_id=buyer_purchase_order_size_detail.size_id
            inner join job_status_master on job_status_master.job_status_id=buyer_purchse_order_master.job_status_id WHERE 1 ".$filter);
           
            return Datatables::of($FabricCheckData)
            ->addIndexColumn()
            ->addColumn('size_qty',function ($row) 
            {
                $size_qty = money_format('%!.0n',($row->size_qty));
                return $size_qty;
            }) 
            ->make(true);
        }
        
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0");
        
        return view('BuyerSalesOrderSizeQtyDashboard', compact('salesOrderList','sales_order_no','fromDate','toDate'));
        
    } 
    
    public function SalesOrderClosed(Request $request)
    {
           $job_status_id= 2;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        
        //  ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        //  ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        //  ->where('buyer_purchse_order_master.job_status_id','=', '2')
        //  ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name', 
        //     DB::raw("(select ifnull(sum(order_qty),0)   from sale_transaction_detail where sales_order_no=buyer_purchse_order_master.tr_code) as 'ShippedQty'")]);
     
        $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.ac_short_name as Ac_name,fg_master.fg_name,brand_master.brand_name,
                merchant_master.merchant_name,job_status_master.job_status_name,main_style_master.mainstyle_name, buyer_purchse_order_master.style_no,buyer_purchse_order_master.order_close_date
                FROM buyer_purchse_order_master  
                INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId 
                INNER JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id 
                INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
                INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id 
                INNER JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id = 2 AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")");
                
     
        if ($request->ajax()) 
         {
        
                return Datatables::of($Buyer_Purchase_Order_List)
                ->addIndexColumn()
                ->addColumn('tr_code1',function ($row) {
            
                     $tr_codeData =substr($row->tr_code, strpos($row->tr_code, '-') + 1);
            
                     return $tr_codeData;
                }) 
                ->addColumn('action1', function ($row) 
                {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SaleOrderPrint/'.$row->tr_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 || Session::get('user_type') == 1  )
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('BuyerPurchaseOrder.edit', $row->tr_code).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->tr_code.'"  data-route="'.route('BuyerPurchaseOrder.destroy', $row->tr_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['action1','action2','action3'])
        
                ->make(true);
        }
        $total_value=0;
        $total_qty=0;
        $open_qty=0;
        $shipped_qty=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_value=$total_value + $row->order_value; $total_qty=$total_qty+$row->total_qty; $open_qty=$open_qty+$row->balance_qty; $shipped_qty=$shipped_qty+$row->shipped_qty;}
        
        $NoOfOrder=count($Buyer_Purchase_Order_List);
    
        return view('BuyerPurchaseOrderMasterList', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrder','total_value','total_qty','open_qty','shipped_qty'));
     }
       
    public function SalesOrderCancelled(Request $request)
    {
           $job_status_id= 3;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '38')
        ->first();
         //   DB::enableQueryLog();
    //     $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
    //   ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
    //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
    //     ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
    //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
    //     ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
    //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
    //     ->where('buyer_purchse_order_master.delflag','=', '0')
    //      ->where('buyer_purchse_order_master.job_status_id','=', '3')
    //     ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name', 
    //         DB::raw("(select ifnull(sum(order_qty),0)   from sale_transaction_detail where sales_order_no=buyer_purchse_order_master.tr_code) as 'ShippedQty'")]);
         
           $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.ac_short_name as Ac_name,fg_master.fg_name,brand_master.brand_name,
                merchant_master.merchant_name,job_status_master.job_status_name,main_style_master.mainstyle_name,buyer_purchse_order_master.style_no,buyer_purchse_order_master.order_close_date
                FROM buyer_purchse_order_master  
                INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId 
                INNER JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id 
                INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
                INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id 
                INNER JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id = 3 AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId').")");
                
     
         if ($request->ajax()) 
         {
        
                return Datatables::of($Buyer_Purchase_Order_List)
                ->addIndexColumn()
                ->addColumn('tr_code1',function ($row) {
            
                     $tr_codeData =substr($row->tr_code, strpos($row->tr_code, '-') + 1);
            
                     return $tr_codeData;
                }) 
                ->addColumn('action1', function ($row) 
                {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SaleOrderPrint/'.$row->tr_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 || Session::get('user_type') == 1  )
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('BuyerPurchaseOrder.edit', $row->tr_code).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->tr_code.'"  data-route="'.route('BuyerPurchaseOrder.destroy', $row->tr_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['action1','action2','action3'])
        
                ->make(true);
        }
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
    
    public function OpenSalesOrderDetailDashboard(Request $request)
    {
        
        $DFilter = "";
           $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
      //DB::enableQueryLog();
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        // select('buyer_purchse_order_master.*','order_type_master.order_type','usermaster.username','ledger_master.Ac_name', 'sales_order_costing_master.total_cost_value','sales_order_costing_master.order_rate','sales_order_costing_master.production_value','sales_order_costing_master.other_value',
        // DB::raw('(select ifnull(sum(order_qty),0) from sale_transaction_detail where sale_transaction_detail.sales_order_no=buyer_purchse_order_master.tr_code) as shipped_qty'),
        // DB::raw('(select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty2'),
        // DB::raw('(select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty'),
        // DB::raw('(select ifnull(sum(total_meter),0) from fabric_outward_master INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code 
        //             where vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issued'),
        // DB::raw('(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark'),
        // 'fg_master.fg_name','merchant_master.merchant_name',
        // 'brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
        // ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        // , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
        // ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        // ->leftJoin('order_type_master', 'order_type_master.orderTypeId', '=', 'buyer_purchse_order_master.order_type')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->where('buyer_purchse_order_master.job_status_id','=', '1')
        // ->get();
        
        $Ac_code = "";
        $po_code = "";
        $sales_order_no = "";
        $brand_id = "";
        $mainstyle_id = "";
        $fg_id = "";
        
        $ReportDate = isset($request->ReportDate) ? $request->ReportDate : date('Y-m-d'); 
        $fob = isset($request->fob) ? $request->fob : 0; 
        $job_work = isset($request->job_work) ? $request->job_work : 0; 
        
        $filter = '';
        if($fob > 0)
        {
           // $filter .= ' AND buyer_purchse_order_master.order_type = 1';
           $filter .= ' AND bpo.order_type = 1';
        }
        
        if($job_work > 0)
        {
           // $filter .= ' AND buyer_purchse_order_master.order_type = 3';
           $filter .= ' AND bpo.order_type = 3';
        }
         
        //  $Buyer_Purchase_Order_List = DB::SELECT("SELECT order_group_master.order_group_name,buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name, sales_order_costing_master.total_cost_value,sales_order_costing_master.order_rate,sales_order_costing_master.production_value,sales_order_costing_master.other_value,
        //         (select ifnull(sum(order_qty),0) from sale_transaction_detail where sale_transaction_detail.sales_order_no=buyer_purchse_order_master.tr_code) as shipped_qty,
        //         (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty2,
        //         (select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty,
        //         (select ifnull(sum(total_meter),0) from fabric_outward_master INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code 
        //         where vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issued,(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark,
        //         fg_master.fg_name,merchant_master.merchant_name,brand_master.brand_name,main_style_master.mainstyle_name,
        //         (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
        //         (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty,buyer_purchse_order_master.order_rate
        //         FROM buyer_purchse_order_master INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code
        //         LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
        //         INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
        //         LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
        //         LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id 
        //         WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4  AND order_close_date = '".$ReportDate."' $filter
        //         OR order_received_date <= '".$ReportDate."' AND  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id IN(1) ".$filter);
         
 


     /*   $Buyer_Purchase_Order_List = DB::SELECT("
                SELECT 
                    order_group_master.order_group_name,
                    buyer_purchse_order_master.*,
                    usermaster.username,
                    ledger_master.ac_short_name, 
                    sales_order_costing_master.total_cost_value,
                    sales_order_costing_master.order_rate,
                    sales_order_costing_master.production_value,
                    sales_order_costing_master.other_value,
            
                    -- Shipped Qty
                    (SELECT IFNULL(SUM(order_qty),0) 
                     FROM sale_transaction_detail 
                     WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code AND sale_transaction_detail.sale_date <= '$ReportDate') AS shipped_qty,
            
                    (SELECT SUM(total_qty) 
                     FROM packing_inhouse_master 
                     WHERE sales_order_no = buyer_purchse_order_master.tr_code AND packing_inhouse_master.pki_date <= '$ReportDate') AS shipped_qty2,
            
                    (SELECT SUM(adjust_qty) 
                     FROM buyer_purchase_order_detail 
                     WHERE tr_code = buyer_purchse_order_master.tr_code AND buyer_purchase_order_detail.tr_date <= '$ReportDate') AS adjust_qty,
            
                    (SELECT IFNULL(SUM(total_meter),0) 
                     FROM fabric_outward_master 
                     INNER JOIN vendor_purchase_order_master 
                        ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code 
                     WHERE vendor_purchase_order_master.sales_order_no = buyer_purchse_order_master.tr_code AND fabric_outward_master.fout_date <= '$ReportDate') AS fabric_issued,
            
                    (SELECT remark 
                     FROM buyer_purchase_order_detail 
                     WHERE tr_code = buyer_purchse_order_master.tr_code 
                     GROUP BY buyer_purchase_order_detail.tr_code) AS remark,
            
                    fg_master.fg_name,
                    merchant_master.merchant_name,
                    brand_master.brand_name,
                    main_style_master.mainstyle_name,
            
                    (SELECT IFNULL(SUM(total_qty),0) 
                     FROM cut_panel_grn_master 
                     WHERE cut_panel_grn_master.sales_order_no = buyer_purchse_order_master.tr_code AND cut_panel_grn_master.cpg_date <= '$ReportDate') AS cut_qty,
            
                    (SELECT IFNULL(SUM(total_qty),0) 
                     FROM stitching_inhouse_master 
                     WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code AND stitching_inhouse_master.sti_date <= '$ReportDate') AS prod_qty,
            
                    buyer_purchse_order_master.order_rate
            
                FROM buyer_purchse_order_master
            
                INNER JOIN usermaster 
                    ON usermaster.userId = buyer_purchse_order_master.userId
            
                INNER JOIN ledger_master 
                    ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code
            
                LEFT JOIN brand_master 
                    ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            
                LEFT JOIN main_style_master 
                    ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
            
                INNER JOIN fg_master 
                    ON fg_master.fg_id = buyer_purchse_order_master.fg_id
            
                LEFT JOIN merchant_master 
                    ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
            
                LEFT JOIN sales_order_costing_master 
                    ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
            
                LEFT JOIN order_group_master 
                    ON order_group_master.og_id = buyer_purchse_order_master.og_id
            
                WHERE 
                    buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4
                    AND buyer_purchse_order_master.order_type != 2
                    AND buyer_purchse_order_master.order_received_date <= '$ReportDate'
                    AND (
                        buyer_purchse_order_master.order_close_date > '$ReportDate' 
                        OR buyer_purchse_order_master.order_close_date IS NULL
                    )
                    $filter 
            ");  */


       
       $Buyer_Purchase_Order_List = DB::select("
    SELECT 
        og.order_group_name,
        bpo.*,
        u.username,
        l.ac_short_name,
        socm.total_cost_value,
        socm.order_rate,
        socm.production_value,
        socm.other_value,
        s.shipped_qty,
        p.shipped_qty2,
        a.adjust_qty,
        a.remark,
        f.fabric_issued,
        fg.fg_name,
        m.merchant_name,
        br.brand_name,
        ms.mainstyle_name,
        c.cut_qty,
        sti.prod_qty,
        bpo.order_rate
    FROM buyer_purchse_order_master bpo

    INNER JOIN usermaster u ON u.userId = bpo.userId
    INNER JOIN ledger_master l ON l.Ac_code = bpo.Ac_code
    LEFT JOIN brand_master br ON br.brand_id = bpo.brand_id
    LEFT JOIN main_style_master ms ON ms.mainstyle_id = bpo.mainstyle_id
    INNER JOIN fg_master fg ON fg.fg_id = bpo.fg_id
    LEFT JOIN merchant_master m ON m.merchant_id = bpo.merchant_id
    LEFT JOIN sales_order_costing_master socm ON socm.sales_order_no = bpo.tr_code
    LEFT JOIN order_group_master og ON og.og_id = bpo.og_id

    LEFT JOIN (
        SELECT sales_order_no, IFNULL(SUM(order_qty),0) AS shipped_qty
        FROM sale_transaction_detail
        WHERE sale_date <= ?
        GROUP BY sales_order_no
    ) s ON s.sales_order_no = bpo.tr_code

    LEFT JOIN (
        SELECT sales_order_no, SUM(total_qty) AS shipped_qty2
        FROM packing_inhouse_master
        WHERE pki_date <= ?
        GROUP BY sales_order_no
    ) p ON p.sales_order_no = bpo.tr_code

    LEFT JOIN (
        SELECT tr_code, SUM(adjust_qty) AS adjust_qty, MAX(remark) AS remark
        FROM buyer_purchase_order_detail
        WHERE tr_date <= ?
        GROUP BY tr_code
    ) a ON a.tr_code = bpo.tr_code

    LEFT JOIN (
        SELECT vpo.sales_order_no, IFNULL(SUM(fom.total_meter),0) AS fabric_issued
        FROM fabric_outward_master fom
        INNER JOIN vendor_purchase_order_master vpo
            ON vpo.vpo_code = fom.vpo_code
        WHERE fom.fout_date <= ?
        GROUP BY vpo.sales_order_no
    ) f ON f.sales_order_no = bpo.tr_code

    LEFT JOIN (
        SELECT sales_order_no, IFNULL(SUM(total_qty),0) AS cut_qty
        FROM cut_panel_grn_master
        WHERE cpg_date <= ?
        GROUP BY sales_order_no
    ) c ON c.sales_order_no = bpo.tr_code

    LEFT JOIN (
        SELECT sales_order_no, IFNULL(SUM(total_qty),0) AS prod_qty
        FROM stitching_inhouse_master
        WHERE sti_date <= ?
        GROUP BY sales_order_no
    ) sti ON sti.sales_order_no = bpo.tr_code

    WHERE 
        bpo.delflag = 0
        AND bpo.og_id != 4
        $filter
        AND bpo.order_type != 2
        AND bpo.order_received_date <= ?
        AND (bpo.order_close_date > ? OR bpo.order_close_date IS NULL)
", [
    $ReportDate, // shipped_qty
    $ReportDate, // shipped_qty2
    $ReportDate, // adjust_qty / remark
    $ReportDate, // fabric_issued
    $ReportDate, // cut_qty
    $ReportDate, // prod_qty
    $ReportDate, // order_received_date
    $ReportDate  // order_close_date
]);     
       
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        //foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
       
        /*$salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4");
        $jobStatusList = DB::SELECT("SELECT job_status_id,job_status_name FROM job_status_master WHERE delflag = 0");
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $styleList = DB::SELECT("SELECT fg_id,fg_name FROM fg_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");
        $poList = DB::SELECT("SELECT po_code FROM buyer_purchse_order_master WHERE delflag = 0 GROUP BY buyer_purchse_order_master.po_code");*/
    
    return view('OpenSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','DFilter', 'job_status_id','NoOfOrderc',  'ReportDate',  'sales_order_no' ));
        //return view('OpenSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','DFilter','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc','salesOrderList','jobStatusList','buyerList','brandList','styleList','mainStyleList','poList','ReportDate','Ac_code','po_code','sales_order_no','brand_id','mainstyle_id','fg_id'));
    }
    
    public function OpenSalesOrderDetailDashboardColorWise(Request $request)
    {
        
        $DFilter = "";
        $job_status_id= 1;
        
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first(); 
        
        $Ac_code = "";
        $po_code = "";
        $sales_order_no = "";
        $brand_id = "";
        $mainstyle_id = "";
        $fg_id = "";
        
        $ReportDate = isset($request->ReportDate) ? $request->ReportDate : date('Y-m-d'); 
        $fob = isset($request->fob) ? $request->fob : 0; 
        $job_work = isset($request->job_work) ? $request->job_work : 0; 
        
        $filter = '';
        if($fob > 0)
        {
            $filter .= ' AND buyer_purchse_order_master.order_type = 1';
        }
        
        if($job_work > 0)
        {
            $filter .= ' AND buyer_purchse_order_master.order_type = 3';
        }
         
      
        $Buyer_Purchase_Order_List = DB::select("
                SELECT 
                    og.order_group_name,
                    bpom.*,
                    u.username,
                    lm.ac_short_name, 
                    socm.total_cost_value,
                    socm.order_rate,
                    socm.production_value,
                    socm.other_value,
                    cm.color_name,
                    fg.fg_name,
                    mm.merchant_name,
                    bm.brand_name,
                    msm.mainstyle_name,
                    sod.color_id,
            
                    -- Shipped Qty
                    COALESCE(st.shipped_qty, 0) AS shipped_qty,
                    COALESCE(pid.shipped_qty2, 0) AS shipped_qty2,
                    COALESCE(bpod.adjust_qty, 0) AS adjust_qty,
                    COALESCE(fom.fabric_issued, 0) AS fabric_issued,
                    bpod.remark,
                    COALESCE(cpg.cut_qty, 0) AS cut_qty,
                    COALESCE(sim.prod_qty, 0) AS prod_qty,
                    COALESCE(sod.size_qty_total, 0) AS total_qty
                    
                FROM buyer_purchse_order_master bpom
            
                INNER JOIN usermaster u 
                    ON u.userId = bpom.userId
            
                INNER JOIN ledger_master lm 
                    ON lm.Ac_code = bpom.Ac_code
            
                LEFT JOIN brand_master bm 
                    ON bm.brand_id = bpom.brand_id
            
                LEFT JOIN main_style_master msm 
                    ON msm.mainstyle_id = bpom.mainstyle_id
            
                INNER JOIN fg_master fg 
                    ON fg.fg_id = bpom.fg_id
            
                LEFT JOIN merchant_master mm 
                    ON mm.merchant_id = bpom.merchant_id
            
                LEFT JOIN sales_order_costing_master socm 
                    ON socm.sales_order_no = bpom.tr_code
            
                LEFT JOIN order_group_master og 
                    ON og.og_id = bpom.og_id
            
                INNER JOIN sales_order_detail sod
                    ON sod.tr_code = bpom.tr_code   -- ✅ FIXED join condition
            
                INNER JOIN color_master cm 
                    ON cm.color_id = sod.color_id
            
                LEFT JOIN (
                    SELECT 
                        std.sales_order_no,
                        cpihd.color_id,
                        SUM(cpihd.size_qty_total) AS shipped_qty
                    FROM sale_transaction_detail std
                    INNER JOIN sale_transaction_master stm 
                        ON stm.sale_code = std.sale_code
                    INNER JOIN (
                        SELECT sale_code, SUBSTRING_INDEX(SUBSTRING_INDEX(carton_packing_nos, ',', n.n), ',', -1) AS cpki_code
                        FROM sale_transaction_master
                        JOIN numbers n ON CHAR_LENGTH(carton_packing_nos) - CHAR_LENGTH(REPLACE(carton_packing_nos, ',', '')) >= n.n-1
                    ) stc ON stc.sale_code = stm.sale_code
                    INNER JOIN carton_packing_inhouse_detail cpihd
                        ON cpihd.cpki_code = stc.cpki_code
                    WHERE std.sale_date <= '$ReportDate'
                    GROUP BY std.sales_order_no, cpihd.color_id
                ) st 
                    ON st.sales_order_no = bpom.tr_code  
                   AND st.color_id = sod.color_id
                
                            
                LEFT JOIN (
                    SELECT sales_order_no, color_id, SUM(size_qty_total) AS shipped_qty2
                    FROM packing_inhouse_detail
                    WHERE pki_date <= '$ReportDate'
                    GROUP BY sales_order_no, color_id
                ) pid 
                    ON pid.sales_order_no = bpom.tr_code 
                   AND pid.color_id = sod.color_id

            
                LEFT JOIN (
                    SELECT tr_code,color_id, SUM(adjust_qty) AS adjust_qty, MAX(remark) AS remark
                    FROM buyer_purchase_order_detail
                    WHERE tr_date <= '$ReportDate'
                    GROUP BY tr_code, color_id
                ) bpod ON bpod.tr_code = bpom.tr_code
                   AND bpod.color_id = sod.color_id
            
                LEFT JOIN (
                    SELECT vpm.sales_order_no, SUM(fom.total_meter) AS fabric_issued
                    FROM fabric_outward_master fom
                    INNER JOIN vendor_purchase_order_master vpm
                        ON vpm.vpo_code = fom.vpo_code
                    WHERE fom.fout_date <= '$ReportDate'
                    GROUP BY vpm.sales_order_no
                ) fom ON fom.sales_order_no = bpom.tr_code
            
                LEFT JOIN (
                    SELECT sales_order_no, color_id, SUM(size_qty_total) AS cut_qty
                    FROM cut_panel_grn_detail
                    WHERE cpg_date <= '$ReportDate'
                    GROUP BY sales_order_no,color_id
                ) cpg ON cpg.sales_order_no = bpom.tr_code
                   AND cpg.color_id = sod.color_id
            
                LEFT JOIN (
                    SELECT sales_order_no, color_id, SUM(size_qty_total) AS prod_qty
                    FROM stitching_inhouse_detail
                    WHERE sti_date <= '$ReportDate'
                    GROUP BY sales_order_no, color_id
                ) sim ON sim.sales_order_no = bpom.tr_code
                       AND sim.color_id = sod.color_id

                WHERE  
                    bpom.delflag = 0 
                    AND bpom.og_id != 4
                    AND bpom.order_type != 2
                    AND bpom.order_received_date <= '$ReportDate'
                    AND (bpom.order_close_date > '$ReportDate' OR bpom.order_close_date IS NULL)
                    $filter
                GROUP BY bpom.tr_code, bpom.style_no, sod.color_id
            ");


        //dd(DB::getQueryLog());
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
        
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4");
        $jobStatusList = DB::SELECT("SELECT job_status_id,job_status_name FROM job_status_master WHERE delflag = 0");
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $styleList = DB::SELECT("SELECT fg_id,fg_name FROM fg_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");
        $poList = DB::SELECT("SELECT po_code FROM buyer_purchse_order_master WHERE delflag = 0 GROUP BY buyer_purchse_order_master.po_code");
    
    
        return view('OpenSalesOrderDetailDashboardColorWise', compact('Buyer_Purchase_Order_List','DFilter','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc','salesOrderList','jobStatusList','buyerList','brandList','styleList','mainStyleList','poList','ReportDate','Ac_code','po_code','sales_order_no','brand_id','mainstyle_id','fg_id'));
    }
    
    public function OpenSalesOrderDetailDashboard1(Request $request)
    {
        
        $DFilter = "";
        $job_status_id= 1;
        $chekform = DB::table('form_auth')
                    ->where('emp_id', Session::get('userId'))
                    ->where('form_id', '96')
                    ->first();
        
        $Ac_code = "";
        $po_code = "";
        $sales_order_no = "";
        $brand_id = "";
        $mainstyle_id = "";
        $fg_id = "";
        
        $ReportDate = isset($request->ReportDate) ? $request->ReportDate : date('Y-m-d'); 
        $fob = isset($request->fob) ? $request->fob : 0; 
        $job_work = isset($request->job_work) ? $request->job_work : 0; 
        
        $filter = '';
        if($fob > 0)
        {
            $filter .= ' AND buyer_purchse_order_master.order_type = 1';
        }
        
        if($job_work > 0)
        {
            $filter .= ' AND buyer_purchse_order_master.order_type = 3';
        }
         
         $Buyer_Purchase_Order_List = DB::SELECT("SELECT order_group_master.order_group_name,buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name, sales_order_costing_master.total_cost_value,sales_order_costing_master.order_rate,sales_order_costing_master.production_value,sales_order_costing_master.other_value,
                (select ifnull(sum(order_qty),0) from sale_transaction_detail where sale_transaction_detail.sales_order_no=buyer_purchse_order_master.tr_code) as shipped_qty,
                (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty2,
                (select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty,
                (select ifnull(sum(total_meter),0) from fabric_outward_master INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code 
                where vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issued,(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark,
                fg_master.fg_name,merchant_master.merchant_name,brand_master.brand_name,main_style_master.mainstyle_name,
                (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty,
                (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty,buyer_purchse_order_master.order_rate
                FROM buyer_purchse_order_master INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code
                LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
                INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
                LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code
                LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id 
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4  AND order_close_date = '".$ReportDate."' $filter
                OR order_received_date <= '".$ReportDate."' AND  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4  ".$filter);
         
        //dd(DB::getQueryLog());
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
        
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4");
        $jobStatusList = DB::SELECT("SELECT job_status_id,job_status_name FROM job_status_master WHERE delflag = 0");
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $styleList = DB::SELECT("SELECT fg_id,fg_name FROM fg_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");
        $poList = DB::SELECT("SELECT po_code FROM buyer_purchse_order_master WHERE delflag = 0 GROUP BY buyer_purchse_order_master.po_code");
    
    
        return view('OpenSalesOrderDetailDashboard1', compact('Buyer_Purchase_Order_List','DFilter','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc','salesOrderList','jobStatusList','buyerList','brandList','styleList','mainStyleList','poList','ReportDate','Ac_code','po_code','sales_order_no','brand_id','mainstyle_id','fg_id'));
    }
    
    public function OpenSalesOrderDetailMDDashboard($DFilter)
    {
                 
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        $job_status_id= 1;
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
        if($DFilter == 'd')
        {
            $filterDate = " AND buyer_purchse_order_master.order_received_date <= '".date('Y-m-d')."'";
            $ShipfilterDate=" and packing_inhouse_master.pki_date <= '".date('Y-m-d')."'";
            $CutfilterDate=" and cut_panel_grn_master.cpg_date <= '".date('Y-m-d')."'";
            $ProdfilterDate="and stitching_inhouse_master.sti_date <= '".date('Y-m-d')."'";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND buyer_purchse_order_master.order_received_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
             $ShipfilterDate=' and packing_inhouse_master.pki_date <=    DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $CutfilterDate=' and cut_panel_grn_master.cpg_date <=    DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $ProdfilterDate='and stitching_inhouse_master.sti_date <=    DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else if($DFilter == 'y')
        {
            $filterDate = ' AND buyer_purchse_order_master.order_received_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
             $ShipfilterDate=" and packing_inhouse_master.pki_date <=   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '%Y-%m-%d')";
            $CutfilterDate=" and cut_panel_grn_master.cpg_date <=   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."'  - INTERVAL 1 MONTH), '%Y-%m-%d')";
            $ProdfilterDate="and stitching_inhouse_master.sti_date <=   DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."'  - INTERVAL 1 MONTH), '%Y-%m-%d')";
            
        }
        else
        {
            $filterDate = "";
        }
        
           //DB::enableQueryLog();
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        // select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name',
        // DB::raw('(select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty2'),
        // DB::raw('(select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty'),
        // DB::raw('(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark'),
        // 'fg_master.fg_name','merchant_master.merchant_name',
        // 'brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
        // ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
        // , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
        // ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->where('buyer_purchse_order_master.job_status_id','=', '1')
        // ->get();
        //DB::enableQueryLog();
        $Buyer_Purchase_Order_List = DB::select("select buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name, 
            (select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code AND buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 ".$ShipfilterDate." ) as shipped_qty2,
            (select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code  AND buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4  ) as adjust_qty,
            (select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code  AND buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4   GROUP BY buyer_purchase_order_detail.tr_code ) as remark,
            fg_master.fg_name,merchant_master.merchant_name,brand_master.brand_name,job_status_master.job_status_name,main_style_master.mainstyle_name,
            (select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code  ".$CutfilterDate.") as cut_qty,
            (select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code ".$ProdfilterDate." ) as prod_qty
            FROM buyer_purchse_order_master INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId
            INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
            INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id
            LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id
            INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id  
            WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id = 1 ".$filterDate."
            ");
        //dd(DB::getQueryLog());
        // dd(DB::getQueryLog());
       
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('OpenSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','DFilter','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
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
         
         $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where delflag=0 and og_id!=4');
         
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
    
     public function GetOCRSummaryReport1()
     {
         
        $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id IN(1,2)');
        return view('GetOCRSummaryReport1', compact('JobStatusList'));
     }
     
     public function OCRSummaryReport1(Request $request)
     {
         
         $job_status_id=$request->job_status_id;
         //DB::enableQueryLog();
         $SalesOrderList=DB::select('select tr_code as sales_order_no,sales_order_costing_master.*,ledger_master.ac_name,fg_master.fg_name,brand_master.brand_name,
            job_status_name,order_close_date,main_style_master.mainstyle_name,buyer_purchse_order_master.sam,buyer_purchse_order_master.inr_rate,shipped_qty,order_received_date from buyer_purchse_order_master 
            LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code  
            LEFT JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code  
            LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id   
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id  
            LEFT JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id   
            where buyer_purchse_order_master.delflag=0 and buyer_purchse_order_master.og_id!=4 AND buyer_purchse_order_master.job_status_id ='.$job_status_id);
         //dd(DB::getQueryLog());
         return view('rptOCRSummaryReport1', compact('job_status_id','SalesOrderList'));
     }
     
     public function GetOCRSummaryReport()
     {
         
         $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master');
         
         $OrderTypeList=DB::select('select * from order_type_master');
         
         return view('GetOCRSummaryReport', compact('JobStatusList','OrderTypeList'));
     }
     
     
     public function GetOCRSummaryReportMD(Request $request)
     {
         
         $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master');
         
         return view('GetOCRSummaryReport', compact('JobStatusList'));
     } 
     
     
     public function OCRSummaryReport(Request $request)
     {
         
         $job_status_id = $request->job_status_id;
         $str2 = $request->orderTypeId;
         $fromDate = $request->fromDate;
         $toDate = $request->toDate;
         $str = json_encode($str2);
         $str1 =  str_replace(array('[',']'),'',$str);
         $orderTypeId = str_replace('"', '', $str1);
         
         return view('rptOCRSummaryReport', compact('job_status_id','orderTypeId','fromDate','toDate'));
     }
    
    
    
    
    public function GetMerchandiseOCRReport()
     {
         
         $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where delflag=0 and og_id!=4');
         
         return view('GetMerchandiseOCRReport', compact('SalesOrderList'));
     }
     
     public function MerchandiseOCRReport(Request $request)
     {
         
         
         $sales_order_no=$request->sales_order_no;
         
        //   DB::enableQueryLog();
         $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
              ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
                ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
                ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                ->join('buyer_purchase_order_detail', 'buyer_purchase_order_detail.tr_code', '=', 'buyer_purchse_order_master.tr_code')
                ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
                ->where('buyer_purchse_order_master.tr_code','=', $sales_order_no)
                ->groupBy('buyer_purchase_order_detail.tr_code','buyer_purchase_order_detail.color_id')
                ->get(['buyer_purchase_order_detail.*', DB::raw('(select ifnull(sum(size_qty_total),0) from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_detail.cpki_code
                     where
                     carton_packing_inhouse_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     carton_packing_inhouse_detail.color_id=buyer_purchase_order_detail.color_id  
                     and carton_packing_inhouse_master.endflag=1 ) as invoice_qty'),
                     DB::raw('(select ifnull(sum(size_qty_total),0) from cut_panel_grn_detail where
                     cut_panel_grn_detail.sales_order_no=buyer_purchase_order_detail.tr_code and
                     cut_panel_grn_detail.color_id=buyer_purchase_order_detail.color_id
                     ) as cut_order_qty'),'buyer_purchase_order_detail.color_id','color_master.color_name','usermaster.username',
                        'ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name' ]);
            //   dd(DB::getQueryLog()); 
        // $Buyer_Purchase_Order_List = DB::select("select buyer_purchase_order_detail.color_id,buyer_purchase_order_detail.tr_code FROM buyer_purchase_order_detail 
        //                              INNER JOIN color_master ON color_master.color_id = buyer_purchase_order_detail.color_id
        //                              WHERE buyer_purchase_order_detail.tr_code='".$sales_order_no."'");
        
        // BuyerPurchaseOrderMasterModel::join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id') 
        //         ->where('buyer_purchse_order_master.tr_code','=', $sales_order_no)
        //         ->get(['buyer_purchse_order_master.color_id','buyer_purchse_order_master.tr_code']);
         
        //   DB::enableQueryLog();    
          
         $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
                ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
                ->leftJoin('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
                ->leftJoin('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
                ->leftJoin('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
                ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
                ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
                ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
                ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                ->where('sales_order_costing_master.delflag','=', '0')
                ->where('buyer_purchse_order_master.og_id','!=', '4')
                ->where('sales_order_costing_master.sales_order_no','=', $sales_order_no)
                ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.Ac_name','job_status_master.job_status_name',
                'costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name',
                'sub_style_master.substyle_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path',
                'brand_master.brand_name','buyer_purchse_order_master.total_qty as order_qty','buyer_purchse_order_master.order_received_date','buyer_purchse_order_master.inr_rate']);
            // dd(DB::getQueryLog()); 
      
        // $FinishedGoodsStock = DB::select("SELECT  ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', job_status_master.job_status_name,
            
        //     (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
        //     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
        //     where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
        //     carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        //     and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
        //     and carton_packing_inhouse_master.endflag=1 AND carton_packing_inhouse_size_detail2.sales_order_no = '".$sales_order_no."'
        //     ) as 'carton_pack_qty',
            
        //     (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
        //     inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        //     where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
        //     transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        //     and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
        //     and transfer_packing_inhouse_size_detail2.usedFlag=1 AND transfer_packing_inhouse_size_detail2.sales_order_no = '".$sales_order_no."'
        //     ) as 'transfer_qty',
        //         order_rate
        //     FROM `packing_inhouse_size_detail2`
        //     LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        //     LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
        //     WHERE packing_inhouse_size_detail2.sales_order_no = '".$sales_order_no."'
        //     GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
     
       
            $FinishedGoodsStock = DB::select("SELECT FG.entry_date, FG.`ac_name`,job_status_master.job_status_name, FG.`sales_order_no`, FG.`mainstyle_name`, FG.`substyle_name`, FG.`fg_name`, FG.`style_no`, 
                FG.`style_description`, FG.`color_name`, FG.`size_name`,ifnull(sum(FG.`size_qty`),0)  as packing_grn_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                ifnull((SELECT  sum(d1.size_qty) from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate,buyer_purchse_order_master.sam FROM FGStockDataByOne as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id 
                where FG.data_type_id=1 AND FG.sales_order_no = '".$sales_order_no."' group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
       
        if(count($SalesOrderCostingMaster) > 0)
        {
            $currency_name = $SalesOrderCostingMaster[0]->currency_name;
            $exchange_rate = money_format('%!i',$SalesOrderCostingMaster[0]->exchange_rate); 
            $mainstyle_name = $SalesOrderCostingMaster[0]->mainstyle_name;
            $substyle_name =  $SalesOrderCostingMaster[0]->substyle_name;
            $inr_rate = money_format('%!i',$SalesOrderCostingMaster[0]->inr_rate);
            $production_value =   $SalesOrderCostingMaster[0]->production_value;
            $dbk_value =   $SalesOrderCostingMaster[0]->dbk_value;
            $printing_value =   $SalesOrderCostingMaster[0]->printing_value;
            $embroidery_value =   $SalesOrderCostingMaster[0]->embroidery_value;
            $ixd_value =   $SalesOrderCostingMaster[0]->ixd_value;
            $agent_commision_value =   $SalesOrderCostingMaster[0]->agent_commision_value;
        }
        else
        {
            $currency_name = "";
            $exchange_rate = "";
            $mainstyle_name = "";
            $substyle_name = "";
            $inr_rate = 0;
            $production_value = 0;
            $dbk_value = 0;
            $printing_value = 0;
            $embroidery_value = 0;
            $ixd_value = 0;
            $agent_commision_value = 0;
        }
        
         $fg_stock = 0;
         foreach($FinishedGoodsStock as $row)
         {
              $fg_stock = $fg_stock + ($row->packing_grn_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->order_rate);
         }
         
         return view('rptMerchandiseOCRReport', compact('Buyer_Purchase_Order_List','SalesOrderCostingMaster','fg_stock','currency_name','exchange_rate','mainstyle_name','substyle_name','inr_rate','production_value','dbk_value','printing_value','embroidery_value','ixd_value','agent_commision_value'));
     }
    
    
    
    public function GetOrderVsShipmentReport()
    {
         $DFilter = "";   
         return view('rptOrderVsShipment',compact('DFilter'));
         
    }    
    
    public function GetOrderVsShipmentReportMD($DFilter)
    {
           
         return view('rptOrderVsShipment',compact('DFilter'));
         
    }
     
    //  public function OrderVsShipmentReport(Request $request)
    //  {
         
    //      $sales_order_no=$request->sales_order_no;
    //      return view('rptMerchandiseOCRReport', compact('Buyer_Purchase_Order_List'));
    //  }
    
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

        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('CostingVSBudgetDashboard', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
    }
      
    public function TotalSalesOrderPendingForOCR()
    {
         
        $job_status_id= 1;
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
            //DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.og_id','!=', '4')
        ->where('buyer_purchse_order_master.job_status_id','=', '5')
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name',
        'fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name',
        'job_status_master.job_status_name','main_style_master.mainstyle_name']);

        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('TotalSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
     
     
     public function TotalSalesOrderDetailMDDashboard($DFilter)
     {
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        $job_status_id= 1;
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
        
        if($DFilter == 'd')
        {
            $filterDate = " AND buyer_purchse_order_master.order_received_date  =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND MONTH(buyer_purchse_order_master.order_received_date) =  MONTH("'.date('Y-m-d').'") and
            YEAR(buyer_purchse_order_master.order_received_date) =  YEAR("'.date('Y-m-d').'")
            AND buyer_purchse_order_master.order_received_date !="'.date('Y-m-d').'"';
        }
        else if($DFilter == 'y')
        {
            $filterDate = " AND  buyer_purchse_order_master.order_received_date between '".$Financial_Year[0]->fdate."'
            and '".$Financial_Year[0]->tdate."'
             AND buyer_purchse_order_master.order_received_date !='".date('Y-m-d')."'";
             
            
        }
        else
        {
            $filterDate = "";
        }
        
            //DB::enableQueryLog();
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        // ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
//DB::enableQueryLog();
        $Buyer_Purchase_Order_List = DB::select("SELECT buyer_purchse_order_master.*,usermaster.username,ledger_master.Ac_name,fg_master.fg_name,merchant_master.merchant_name,
        brand_master.brand_name,job_status_master.job_status_name,main_style_master.mainstyle_name,(select consumption FROM sales_order_fabric_costing_details WHERE sales_order_fabric_costing_details.sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_fabric_costing_details.sales_order_no) as consumption 
        FROM buyer_purchse_order_master INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId
        LEFT JOIN merchant_master ON merchant_master.merchant_id = buyer_purchse_order_master.merchant_id 
        INNER JOIN ledger_master ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
        LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
        LEFT JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id
        INNER JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id 
        INNER JOIN job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
        WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id != 3 ".$filterDate);
 //dd(DB::getQueryLog());
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('TotalSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
    
    public function TotalSalesOrderDetailDashboardFilter(Request $request)
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
        ->where('buyer_purchse_order_master.delflag','=', '0')->whereBetween('order_received_date',array($request->fdate,$request->tdate))
        ->where('buyer_purchse_order_master.og_id','!=', '4')
        ->where('buyer_purchse_order_master.job_status_id','!=', '3')
        
        ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    $total_valuec=0;
    $total_qtyc=0;
    $open_qtyc=0;
    $shipped_qtyc=0;
    foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    $NoOfOrderc=count($Buyer_Purchase_Order_List);
    
        return view('TotalSalesOrderDetailDashboardFilter', compact('Buyer_Purchase_Order_List','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
  
         
     }
    
    
    public function DailyProductionDetailDashboard(Request $request)
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '116')
        ->first();
         
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        //  ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.job_status_id','=', '1')
        // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
        // $total_valuec=0;
        // $total_qtyc=0;
        // $open_qtyc=0;
        // $shipped_qtyc=0;
        // foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        // $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
        
         $fromDate = $request->fromDate;
         $toDate = $request->toDate; 
         $Ac_code = $request->ac_code;
         $po_code = $request->po_code;
         $sales_order_no = $request->sales_order_no;
         $brand_id = $request->brand_id;
         $mainstyle_id = $request->mainstyle_id;
         $fg_id = $request->fg_id; 
         $color_id = $request->color_id; 
         
             
         $filter = "";
         
        
         if($sales_order_no != "") 
         {
             $filter .= " AND buyer_purchse_order_master.tr_code='".$sales_order_no."'"; 
         }
         
         if($Ac_code != "") 
         {
             $filter .= " AND buyer_purchse_order_master.Ac_code='".$Ac_code."'"; 
         }
         
         if($brand_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.brand_id='".$brand_id."'";
         }
         
         if($mainstyle_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.mainstyle_id='".$mainstyle_id."'";
         }
         
         if($fg_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.fg_id='".$fg_id."'";
         } 
           
        if($color_id != "")
        {
            $filter .= " AND buyer_purchse_order_master.buyer_purchase_order_detail='".$color_id."'";
        } 
        
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4"); 
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $styleList = DB::SELECT("SELECT fg_id,fg_name FROM fg_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");  
        $poList = DB::SELECT("SELECT po_code FROM buyer_purchse_order_master WHERE delflag = 0 GROUP BY buyer_purchse_order_master.po_code");
        $colorList = DB::SELECT("SELECT color_master.color_id,color_master.color_name FROM color_master 
                        INNER JOIN buyer_purchase_order_detail ON buyer_purchase_order_detail.color_id = color_master.color_id 
                        WHERE color_master.delflag = 0 GROUP BY buyer_purchase_order_detail.color_id");
        
        //DB::enableQueryLog();
        $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code, 
           buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no  , merchant_master.merchant_name ,
           buyer_purchse_order_master.Ac_code, ac_name, username, 
            buyer_purchase_order_detail.color_id,color_name, sum(size_qty_total) as order_qty ,brand_master.brand_name
            FROM `buyer_purchse_order_master` 
            inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where buyer_purchse_order_master.job_status_id=1 and  buyer_purchse_order_master.og_id!=4 ".$filter."
            group by main_style_master.mainstyle_id, buyer_purchse_order_master.Ac_code, buyer_purchase_order_detail.color_id ,buyer_purchse_order_master.userId,buyer_purchse_order_master.tr_code");
     
        //dd(DB::getQueryLog());
        return view('DailyProductionDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id','fromDate','toDate','Ac_code','po_code','sales_order_no','brand_id','mainstyle_id','fg_id','color_id','salesOrderList','brandList','styleList','mainStyleList','buyerList','poList','colorList'));
     
     }
     
        
    public function rptProductionDPR(Request $request)
    { 
         
        $DPRDate = isset($request->DPRDate) ? $request->DPRDate : date("Y-m-d");
        $style_no = isset($request->style_no) ? $request->style_no : 0; 
        $vendorId = isset($request->vendorId) ? $request->vendorId : 0; 
        $outsourceId = isset($request->outsourceId) ? $request->outsourceId : 0; 
        $line_id = isset($request->line_id) ? $request->line_id : 0; 
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : ""; 
        $Ac_code = isset($request->Ac_code) ? $request->Ac_code : 0; 
        $mainstyle_id = isset($request->mainstyle_id) ? $request->mainstyle_id : 0; 
        

        $fgFilter = '';
        
        if($style_no > 0)
        {
               $fgFilter .=" AND buyer_purchse_order_master.fg_id=".$style_no;
        }
        
        if($sales_order_no != '')
        {
               $fgFilter .=" AND buyer_purchse_order_master.tr_code='".$sales_order_no."'";
        }
           
        if($Ac_code > 0)
        {
               $fgFilter .=" AND buyer_purchse_order_master.Ac_code='".$Ac_code."'";
        }
           
        if($mainstyle_id > 0)
        {
               $fgFilter .=" AND buyer_purchse_order_master.mainstyle_id='".$mainstyle_id."'";
        }
        
        if($vendorId > 0)
        {
            $vendorFilter = " AND vendorId='".$vendorId."'";
        }
        else
        {
            $vendorFilter = "";
        }
        
        if($outsourceId > 0)
        {
            $outSourceFilter = " AND vendorId='".$outsourceId."'";
        }
        else
        {
            $outSourceFilter = "";
        }
        
        if($vendorId > 0)
        {
            $vendorFilter1 = " AND packing_inhouse_master.vendorId='".$vendorId."'";
            $vendorFilter2 = " AND packing_rejection_master.vendorId='".$vendorId."'";
        }
        else
        {
            $vendorFilter1 = "";
            $vendorFilter2 = "";
        }
        
        if($outsourceId > 0)
        {
            $outsourceFilter1 = " AND packing_inhouse_master.vendorId='".$outsourceId."'";
            $outsourceFilter2 = " AND packing_rejection_master.vendorId='".$outsourceId."'";
        }
        else
        {
            $outsourceFilter1 = "";
            $outsourceFilter2 = "";
        }
        
        $lineFilter = '';
        if($line_id > 0)
        {
           $lineFilter =" AND line_id=".$line_id;
        } 
 
       // DB::enableQueryLog();
        $ProductionOrderDetailList = DB::select("WITH CutPanelIssues AS (
                            SELECT color_id, Ac_code, sales_order_no, SUM(size_qty_total) AS total_cut_panel_issue
                            FROM cut_panel_issue_detail WHERE cpi_date <= '".$DPRDate."' ".$vendorFilter." ".$lineFilter." ".$outSourceFilter." 
                            GROUP BY color_id, Ac_code, sales_order_no
                        ),
                        QCStitchingRejects AS (
                            SELECT color_id, Ac_code, sales_order_no, SUM(size_qty_total) AS total_qcstitching_reject_qty
                            FROM qcstitching_inhouse_reject_detail WHERE qcsti_date <= '".$DPRDate."' ".$vendorFilter." ".$lineFilter." ".$outSourceFilter."  
                            GROUP BY color_id, Ac_code, sales_order_no
                        ),
                        PackingQty AS (
                            SELECT packing_inhouse_detail.color_id, packing_inhouse_master.Ac_code, packing_inhouse_detail.sales_order_no,
                                   SUM(packing_inhouse_detail.size_qty_total) AS total_packing_qty
                            FROM packing_inhouse_detail
                            INNER JOIN packing_inhouse_master ON packing_inhouse_master.pki_code = packing_inhouse_detail.pki_code
                            WHERE packing_inhouse_master.packing_type_id = 4 AND packing_inhouse_master.pki_date <= '".$DPRDate."'
                            GROUP BY packing_inhouse_detail.color_id, packing_inhouse_master.Ac_code, packing_inhouse_detail.sales_order_no
                        ),
                        CuttingQty AS (
                            SELECT color_id, sales_order_no, SUM(size_qty_total) AS total_cutting_qty
                            FROM cut_panel_grn_detail WHERE cpg_date  <= '".$DPRDate."' 
                            GROUP BY color_id, sales_order_no
                        ),
                        StitchingQty AS (
                            SELECT color_id, Ac_code, sales_order_no, SUM(size_qty_total) AS total_stitching_qty
                            FROM qcstitching_inhouse_detail WHERE qcsti_date  <= '".$DPRDate."' ".$vendorFilter." ".$outSourceFilter." 
                            GROUP BY color_id, Ac_code, sales_order_no
                        ),
                        PackingRejects AS (
                            SELECT packing_rejection_detail.color_id, packing_rejection_master.Ac_code, packing_rejection_detail.sales_order_no,
                                   SUM(packing_rejection_detail.size_qty_total) AS total_packing_rej_qty
                            FROM packing_rejection_detail
                            INNER JOIN packing_rejection_master ON packing_rejection_master.qcp_code = packing_rejection_detail.qcp_code  WHERE packing_rejection_detail.qcp_date  <= '".$DPRDate."' ".$vendorFilter2." ".$outsourceFilter2."
                            GROUP BY packing_rejection_detail.color_id, packing_rejection_master.Ac_code, packing_rejection_detail.sales_order_no
                        ),
                        WashingQty AS (
                            SELECT color_id, Ac_code, sales_order_no, SUM(size_qty_total) AS total_fi_washing_qty
                            FROM washing_inhouse_detail WHERE wash_date <= '".$DPRDate."'
                            GROUP BY color_id, Ac_code, sales_order_no
                        ),
                        VendorWashingQty AS (
                            SELECT vendor_purchase_order_detail.color_id, vendor_purchase_order_detail.Ac_code, vendor_purchase_order_detail.sales_order_no,
                                   SUM(vendor_purchase_order_detail.size_qty_total) AS total_washing_qty
                            FROM vendor_purchase_order_detail
                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_detail.vpo_code
                            WHERE vendor_purchase_order_detail.process_id = 4 AND vendor_purchase_order_master.vpo_date <= '".$DPRDate."'
                            GROUP BY vendor_purchase_order_detail.color_id, vendor_purchase_order_detail.Ac_code, vendor_purchase_order_detail.sales_order_no
                        )
                        
                        SELECT 
                            buyer_purchse_order_master.tr_code,
                            buyer_purchse_order_master.po_code,
                            buyer_purchse_order_master.style_no,
                            buyer_purchse_order_master.Ac_code,
                            ledger_master.ac_short_name,
                            buyer_purchase_order_detail.color_id,
                            color_master.color_name,
                            SUM(buyer_purchase_order_detail.size_qty_total) AS order_qty,
                            fg_master.fg_name,
                            COALESCE(cut_issues.total_cut_panel_issue, 0) AS total_cut_panel_issue,
                            COALESCE(qc_rejects.total_qcstitching_reject_qty, 0) AS total_qcstitching_reject_qty,
                            COALESCE(packing.total_packing_qty, 0) AS total_packing_qty,
                            COALESCE(cutting.total_cutting_qty, 0) AS total_cutting_qty,
                            COALESCE(stitching.total_stitching_qty, 0) AS total_stitching_qty,
                            COALESCE(packing_rejects.total_packing_rej_qty, 0) AS total_packing_rej_qty,
                            COALESCE(washing.total_fi_washing_qty, 0) AS total_fi_washing_qty,
                            COALESCE(vendor_washing.total_washing_qty, 0) AS total_washing_qty
                        FROM buyer_purchse_order_master
                        INNER JOIN buyer_purchase_order_detail ON buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
                        INNER JOIN color_master ON color_master.color_id = buyer_purchase_order_detail.color_id
                        LEFT JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                        LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id
                        LEFT JOIN CutPanelIssues AS cut_issues ON cut_issues.color_id = buyer_purchase_order_detail.color_id 
                            AND cut_issues.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND cut_issues.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN QCStitchingRejects AS qc_rejects ON qc_rejects.color_id = buyer_purchase_order_detail.color_id 
                            AND qc_rejects.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND qc_rejects.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN PackingQty AS packing ON packing.color_id = buyer_purchase_order_detail.color_id 
                            AND packing.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND packing.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN CuttingQty AS cutting ON cutting.color_id = buyer_purchase_order_detail.color_id 
                            AND cutting.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN StitchingQty AS stitching ON stitching.color_id = buyer_purchase_order_detail.color_id 
                            AND stitching.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND stitching.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN PackingRejects AS packing_rejects ON packing_rejects.color_id = buyer_purchase_order_detail.color_id 
                            AND packing_rejects.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND packing_rejects.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN WashingQty AS washing ON washing.color_id = buyer_purchase_order_detail.color_id 
                            AND washing.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND washing.sales_order_no = buyer_purchse_order_master.tr_code
                        LEFT JOIN VendorWashingQty AS vendor_washing ON vendor_washing.color_id = buyer_purchase_order_detail.color_id 
                            AND vendor_washing.Ac_code = buyer_purchse_order_master.Ac_code 
                            AND vendor_washing.sales_order_no = buyer_purchse_order_master.tr_code
                        WHERE buyer_purchse_order_master.og_id != 4  
                        AND buyer_purchse_order_master.job_status_id IN (1) ".$fgFilter." 
                        GROUP BY buyer_purchase_order_detail.color_id, buyer_purchase_order_detail.tr_code");
                                
        //dd(DB::getQueryLog());
         
        $LineList = DB::table('line_master')->select('line_master.line_id','line_name')->where('Ac_code','=',$vendorId)->DISTINCT()->get(); 
        
        $vendorList = DB::select("SELECT ledger_master.ac_code, ac_name FROM ledger_master  
                            where  bt_id = 4 AND ledger_master.delflag=0 AND ledger_master.ac_code IN(56,115,110,628,686) 
                            GROUP BY ledger_master.ac_code order by ledger_master.ac_code");
        
        $outsourceList = DB::select("SELECT ledger_master.ac_code, ac_name FROM ledger_master   
                            where bt_id = 4 AND ledger_master.delflag=0 
                            AND ledger_master.ac_code NOT IN(56,115,110,628,686) GROUP BY ledger_master.ac_code");
        
        $styleList = DB::select("SELECT fg_master.fg_id, fg_name FROM fg_master 
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.fg_id = fg_master.fg_id
                            where  buyer_purchse_order_master.og_id!=4  
                            AND buyer_purchse_order_master.job_status_id IN(1)  AND fg_master.delflag=0 GROUP BY buyer_purchse_order_master.fg_id");
        
        $BuyerPurchaseList = DB::select("SELECT tr_code FROM buyer_purchse_order_master where og_id!=4  AND job_status_id IN(1)");

        
        $BuyerList = DB::select("SELECT ledger_master.ac_code, ledger_master.ac_name FROM ledger_master 
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.Ac_code = ledger_master.ac_code
                            where ledger_master.bt_id = 2 AND ledger_master.delflag=0 AND buyer_purchse_order_master.og_id!=4  
                            AND buyer_purchse_order_master.job_status_id IN(1) GROUP BY buyer_purchse_order_master.Ac_code");

        
        $mainStyleList = DB::select("SELECT main_style_master.mainstyle_id, mainstyle_name FROM main_style_master  
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.mainstyle_id = main_style_master.mainstyle_id
                            where  main_style_master.delflag=0 AND buyer_purchse_order_master.og_id!=4  
                            AND buyer_purchse_order_master.job_status_id IN(1) GROUP BY buyer_purchse_order_master.mainstyle_id");
     
        return view('rptProductionDPR', compact('ProductionOrderDetailList', 'vendorId', 'outsourceId','DPRDate','vendorList', 'outsourceList', 'styleList','style_no','line_id','LineList','sales_order_no','Ac_code','mainstyle_id', 'BuyerPurchaseList', 'BuyerList', 'mainStyleList'));
     
     }
     
     
    
    //  public function OrderProgressDetailDashboard()
    // {
    //       $job_status_id= 1;
    //       $chekform = DB::table('form_auth')
    //     ->where('emp_id', Session::get('userId'))
    //     ->where('form_id', '130')
    //     ->first();
         
    //     $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
    //      ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
    //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
    //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
    //     ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
    //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
    //     ->where('buyer_purchse_order_master.delflag','=', '0')
    //      ->where('buyer_purchse_order_master.job_status_id','=', '1')
    //     ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    // $total_valuec=0;
    // $total_qtyc=0;
    // $open_qtyc=0;
    // $shipped_qtyc=0;
    // foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    // $NoOfOrderc=count($Buyer_Purchase_Order_List);
    //       //  DB::enableQueryLog();
    // $ProductionOrderDetailList = DB::select("SELECT vendor_work_order_master.vw_code ,  vendor_work_order_master.sales_order_no,vendor_work_order_detail.po_code, LM2.Ac_name as vendorName,
    //     vendor_work_order_master.mainstyle_id,mainstyle_name,vendor_work_order_master.style_no,
    //     job_status_master.job_status_name,
    //     ifnull( (vendor_work_order_detail.size_qty_total),0) as order_qty,vendor_work_order_detail.color_id,color_name,    
    //     (select ifnull(sum(cut_panel_issue_detail.size_qty_total),0)  from cut_panel_issue_detail where cut_panel_issue_detail.color_id=vendor_work_order_detail.color_id
    //     and cut_panel_issue_detail.vw_code=vendor_work_order_master.vw_code) as total_cut_panel_issue,
    //     (select ifnull(sum(stitching_inhouse_detail.size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
    //     and stitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code)  as total_stitching_qty,
    //     (select ifnull(sum(qcstitching_inhouse_reject_detail.size_qty_total),0)  from qcstitching_inhouse_reject_detail 
    //     where qcstitching_inhouse_reject_detail.color_id=vendor_work_order_detail.color_id
    //     and qcstitching_inhouse_reject_detail.vw_code=vendor_work_order_detail.vw_code )  as total_qcstitching_reject_qty,
    //     (select ifnull(sum(qcstitching_inhouse_detail.size_qty_total),0)  from qcstitching_inhouse_detail 
    //     where qcstitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
    //     and qcstitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code)  as total_qcstitching_pass_qty
        
    //     FROM vendor_work_order_master 
    //     inner join vendor_work_order_detail on vendor_work_order_detail.vw_code=vendor_work_order_master.vw_code
    //     inner join color_master on color_master.color_id=vendor_work_order_detail.color_id
    //     inner join job_status_master on job_status_master.job_status_id=vendor_work_order_master.endflag
    //     left outer join main_style_master on main_style_master.mainstyle_id=vendor_work_order_master.mainstyle_id
    //     left outer join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
    //     left outer join ledger_master LM2 on LM2.ac_code=vendor_work_order_master.vendorId
    //     left outer join usermaster on usermaster.userId=vendor_work_order_master.userId
    //     group by vendor_work_order_master.Ac_code,vendor_work_order_master.vw_code,vendor_work_order_detail.color_id");
 
    //     return view('OrderProgressDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
        
    //  }
    
    
    public function OrderProgressDetailDashboard(Request $request)
    {
       
        $vendorId = $request->vendorId; 
        $job_status_id =  $request->job_status_id; 
        $vw_code =  $request->vw_code;   
          
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $vendorWorkOrderList = DB::table('vendor_work_order_master')->where('delflag', '0')->get();
        $jobStatusList = DB::table('job_status_master')->where('delflag', '0')->get();
        $filters = "";
        if($vendorId > 0 && $job_status_id > 0 && $vw_code !="")
        {
            $filters = " AND vendor_work_order_master.vendorId =".$vendorId." AND vendor_work_order_master.endflag=".$job_status_id." AND vendor_work_order_master.vw_code='".$vw_code."'";
        }
        else if($vendorId > 0 && $job_status_id > 0 && $vw_code =="")
        {
            $filters = " AND vendor_work_order_master.vendorId =".$vendorId." AND vendor_work_order_master.endflag=".$job_status_id;
        }
        else if($job_status_id > 0 && $vw_code !="" && $vendorId == 0)
        { 
            $filters = " AND vendor_work_order_master.endflag=".$job_status_id." AND vendor_work_order_master.vw_code='".$vw_code;
        }
        else if($vendorId > 0 && $vw_code !="" && $job_status_id == 0)
        {
            $filters = " AND vendor_work_order_master.vendorId =".$vendorId." AND vendor_work_order_master.vw_code='".$vw_code;
        }
        else if($vendorId > 0 && $vw_code == "" && $job_status_id == 0)
        {
              $filters = " AND vendor_work_order_master.vendorId = $request->vendorId"; 
        }
        else if($vendorId == 0 && $vw_code != "" && $job_status_id == 0)
        {
        
              $filters = " AND vendor_work_order_master.vw_code ='".$vw_code."'"; 
        }
        else if($vendorId == 0 && $vw_code == "" && $job_status_id > 0)
        {
        
              $filters = " AND vendor_work_order_master.endflag=".$job_status_id;
        }
        else
        {
            $filters = "";
        }
        
         
        if($request->ajax()) 
        { 
        
        
            //   $ProductionOrderDetailList = DB::select("SELECT vendor_work_order_master.vw_code, vendor_work_order_master.sales_order_no,
            //     vendor_work_order_detail.po_code, LM2.Ac_name as vendorName, vendor_work_order_master.mainstyle_id,mainstyle_name,vendor_work_order_master.style_no,
            //     job_status_master.job_status_name, ifnull( (vendor_work_order_detail.size_qty_total),0) as order_qty,vendor_work_order_detail.color_id,color_name,    
            //     (select ifnull(sum(cut_panel_issue_detail.size_qty_total),0)  from cut_panel_issue_detail 
            //     where cut_panel_issue_detail.color_id=vendor_work_order_detail.color_id and cut_panel_issue_detail.vw_code=vendor_work_order_master.vw_code AND cut_panel_issue_detail.vendorId=vendor_work_order_master.vendorId ) as total_cut_panel_issue,
            //     (select ifnull(sum(stitching_inhouse_detail.size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
            //     and stitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code AND stitching_inhouse_detail.vendorId= vendor_work_order_master.vendorId)  as total_stitching_qty,
            //     (select ifnull(sum(qcstitching_inhouse_reject_detail.size_qty_total),0)  from qcstitching_inhouse_reject_detail 
            //     where qcstitching_inhouse_reject_detail.color_id=vendor_work_order_detail.color_id
            //     and qcstitching_inhouse_reject_detail.vw_code=vendor_work_order_detail.vw_code  AND qcstitching_inhouse_reject_detail.vendorId=vendor_work_order_master.vendorId )  as total_qcstitching_reject_qty,
            //     (select ifnull(sum(qcstitching_inhouse_detail.size_qty_total),0)  from qcstitching_inhouse_detail 
            //     where qcstitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
            //     and qcstitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code  AND qcstitching_inhouse_detail.vendorId=vendor_work_order_master.vendorId)  as total_qcstitching_pass_qty
                
            //     FROM vendor_work_order_master 
            //     inner join vendor_work_order_detail on vendor_work_order_detail.vw_code=vendor_work_order_master.vw_code
            //     inner join color_master on color_master.color_id=vendor_work_order_detail.color_id
            //     inner join job_status_master on job_status_master.job_status_id=vendor_work_order_master.endflag
            //     left outer join main_style_master on main_style_master.mainstyle_id=vendor_work_order_master.mainstyle_id
            //     left outer join ledger_master on ledger_master.ac_code=vendor_work_order_master.Ac_code
            //     left outer join ledger_master LM2 on LM2.ac_code=vendor_work_order_master.vendorId
            //     left outer join usermaster on usermaster.userId=vendor_work_order_master.userId
            //     WHERE 1 ".$filters."
            //     group by vendor_work_order_master.Ac_code,vendor_work_order_master.vw_code,vendor_work_order_detail.color_id");
        
            $ProductionOrderDetailList = DB::select("
                    SELECT 
                        vwm.vw_code,
                        vwm.sales_order_no,
                        vwd.po_code,
                        LM2.Ac_name as vendorName,
                        vwm.mainstyle_id,
                        msm.mainstyle_name,
                        vwm.style_no,
                        jsm.job_status_name,
                        IFNULL(vwd.size_qty_total, 0) as order_qty,
                        vwd.color_id,
                        cm.color_name,
                        IFNULL(cut.total_cut_panel_issue, 0) as total_cut_panel_issue,
                        IFNULL(stitch.total_stitching_qty, 0) as total_stitching_qty,
                        IFNULL(qc_rej.total_qcstitching_reject_qty, 0) as total_qcstitching_reject_qty,
                        IFNULL(qc_pass.total_qcstitching_pass_qty, 0) as total_qcstitching_pass_qty
                
                    FROM vendor_work_order_master vwm
                    INNER JOIN vendor_work_order_detail vwd ON vwd.vw_code = vwm.vw_code
                    INNER JOIN color_master cm ON cm.color_id = vwd.color_id
                    INNER JOIN job_status_master jsm ON jsm.job_status_id = vwm.endflag
                    LEFT JOIN main_style_master msm ON msm.mainstyle_id = vwm.mainstyle_id
                    LEFT JOIN ledger_master LM ON LM.ac_code = vwm.Ac_code
                    LEFT JOIN ledger_master LM2 ON LM2.ac_code = vwm.vendorId
                    LEFT JOIN usermaster um ON um.userId = vwm.userId
                
                    LEFT JOIN (
                        SELECT vw_code, vendorId, color_id, SUM(size_qty_total) as total_cut_panel_issue
                        FROM cut_panel_issue_detail
                        GROUP BY vw_code, vendorId, color_id
                    ) cut ON cut.vw_code = vwm.vw_code AND cut.vendorId = vwm.vendorId AND cut.color_id = vwd.color_id
                
                    LEFT JOIN (
                        SELECT vw_code, vendorId, color_id, SUM(size_qty_total) as total_stitching_qty
                        FROM stitching_inhouse_detail
                        GROUP BY vw_code, vendorId, color_id
                    ) stitch ON stitch.vw_code = vwm.vw_code AND stitch.vendorId = vwm.vendorId AND stitch.color_id = vwd.color_id
                
                    LEFT JOIN (
                        SELECT vw_code, vendorId, color_id, SUM(size_qty_total) as total_qcstitching_reject_qty
                        FROM qcstitching_inhouse_reject_detail
                        GROUP BY vw_code, vendorId, color_id
                    ) qc_rej ON qc_rej.vw_code = vwd.vw_code AND qc_rej.vendorId = vwm.vendorId AND qc_rej.color_id = vwd.color_id
                
                    LEFT JOIN (
                        SELECT vw_code, vendorId, color_id, SUM(size_qty_total) as total_qcstitching_pass_qty
                        FROM qcstitching_inhouse_detail
                        GROUP BY vw_code, vendorId, color_id
                    ) qc_pass ON qc_pass.vw_code = vwm.vw_code AND qc_pass.vendorId = vwm.vendorId AND qc_pass.color_id = vwd.color_id
                
                    WHERE 1=1 {$filters}
                
                    GROUP BY vwm.Ac_code, vwm.vw_code, vwd.color_id
                ");
                

            //     $ProductionOrderDetailList = DB::table('vendor_work_order_master')->
            // select('vendor_work_order_master.vw_code', 'vendor_work_order_master.sales_order_no',
            //     'vendor_work_order_detail.po_code', 'LM2.Ac_name as vendorName', 'vendor_work_order_master.mainstyle_id','mainstyle_name','vendor_work_order_master.style_no',
            //     'job_status_master.job_status_name',  DB::raw('ifnull( (vendor_work_order_detail.size_qty_total),0) as order_qty'),'vendor_work_order_detail.color_id','color_name', 
            // DB::raw("(select ifnull(sum(cut_panel_issue_detail.size_qty_total),0)  from cut_panel_issue_detail 
            //     where cut_panel_issue_detail.color_id=vendor_work_order_detail.color_id and cut_panel_issue_detail.vw_code=vendor_work_order_master.vw_code 
            //     AND cut_panel_issue_detail.vendorId=vendor_work_order_master.vendorId ) as 'total_cut_panel_issue'"),
            
            // DB::raw("(select ifnull(sum(stitching_inhouse_detail.size_qty_total),0)  from stitching_inhouse_detail where stitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
            //     and stitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code AND stitching_inhouse_detail.vendorId= vendor_work_order_master.vendorId)  as 'total_stitching_qty'"),
            
            // DB::raw("(select ifnull(sum(qcstitching_inhouse_reject_detail.size_qty_total),0)  from qcstitching_inhouse_reject_detail 
            //     where qcstitching_inhouse_reject_detail.color_id=vendor_work_order_detail.color_id
            //     and qcstitching_inhouse_reject_detail.vw_code=vendor_work_order_detail.vw_code  
            //     AND qcstitching_inhouse_reject_detail.vendorId=vendor_work_order_master.vendorId )  as 'total_qcstitching_reject_qty'"),
           
            // DB::raw("(select ifnull(sum(qcstitching_inhouse_detail.size_qty_total),0)  from qcstitching_inhouse_detail 
            //     where qcstitching_inhouse_detail.color_id=vendor_work_order_detail.color_id
            //     and qcstitching_inhouse_detail.vw_code=vendor_work_order_master.vw_code  
            //     AND qcstitching_inhouse_detail.vendorId=vendor_work_order_master.vendorId)  as 'total_qcstitching_pass_qty'")
            //     ) 
                
            // ->join('vendor_work_order_detail', 'vendor_work_order_detail.vw_code', '=', 'vendor_work_order_master.vw_code')
            // ->join('color_master', 'color_master.color_id', '=', 'vendor_work_order_detail.color_id')
            // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_work_order_master.endflag')
            // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_work_order_master.mainstyle_id', 'left outer')
            // ->join('ledger_master', 'ledger_master.ac_code', '=', 'vendor_work_order_master.ac_code', 'left outer')
            // ->join('ledger_master as LM2', 'LM2.ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
            // ->join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
            // ->where('vendor_work_order_master.vendorId','=', $request->vendorId) 
            // ->groupBy('vendor_work_order_master.Ac_code')
            // ->groupBy('vendor_work_order_master.vw_code')
            // ->groupBy('vendor_work_order_detail.color_id') 
            // ->get();
         
            return Datatables::of($ProductionOrderDetailList)
             
            ->addColumn('bal_total_cut_panel_issue',function ($row) 
            {
                $bal_total_cut_panel_issue = $row->order_qty- $row->total_cut_panel_issue;
               
                return $bal_total_cut_panel_issue;
            })
            ->addColumn('bal_total_stitching_qty',function ($row) 
            {
                
                $bal_total_stitching_qty = $row->total_cut_panel_issue-$row->total_stitching_qty;
               
                return $bal_total_stitching_qty;
            })
            ->addColumn('total_qcstitching_pending_qty',function ($row) 
            {
                
                $total_qcstitching_pending_qty = $row->total_stitching_qty - $row->total_qcstitching_pass_qty - $row->total_qcstitching_reject_qty;
               
                return $total_qcstitching_pending_qty;
            })
           
            ->rawColumns(['bal_total_cut_panel_issue','bal_total_stitching_qty','total_qcstitching_pending_qty'])
            ->make(true);
        }
        return view('OrderProgressDetailDashboard', compact('job_status_id','Ledger','vendorWorkOrderList','jobStatusList','vendorId','vw_code')); 
        
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
    
    // public function OrderProgressPackingDetailDashboard()
    // {
    //       $job_status_id= 1;
    //       $chekform = DB::table('form_auth')
    //     ->where('emp_id', Session::get('userId'))
    //     ->where('form_id', '132')
    //     ->first();
         
    //     $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
    //      ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
    //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
    //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
    //     ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
    //     ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
    //     ->where('buyer_purchse_order_master.delflag','=', '0')
    //      ->where('buyer_purchse_order_master.job_status_id','=', '1')
    //     ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
    // $total_valuec=0;
    // $total_qtyc=0;
    // $open_qtyc=0;
    // $shipped_qtyc=0;
    // foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
    // $NoOfOrderc=count($Buyer_Purchase_Order_List);
    //         // DB::enableQueryLog();
    // $ProductionOrderDetailList = DB::select("SELECT vendor_purchase_order_master.vpo_code , vendor_purchase_order_master.sales_order_no,
    //   vendor_purchase_order_detail.po_code, LM2.Ac_name as vendorName,LM2.ac_code as vendorId,job_status_master.job_status_name,
    //   vendor_purchase_order_master.mainstyle_id,mainstyle_name,vendor_purchase_order_master.style_no  ,  
    //   ifnull( (vendor_purchase_order_detail.size_qty_total),0) as order_qty,
       
    //   vendor_purchase_order_detail.color_id,color_name,    
    // (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail where packing_inhouse_detail.color_id=vendor_purchase_order_detail.color_id
    // and packing_inhouse_detail.vpo_code=vendor_purchase_order_master.vpo_code)   as total_packing_qty 
     
    // FROM `vendor_purchase_order_master` 
    // inner join vendor_purchase_order_detail on vendor_purchase_order_detail.vpo_code=vendor_purchase_order_master.vpo_code
    // inner join color_master on color_master.color_id=vendor_purchase_order_detail.color_id
    // left outer join main_style_master on main_style_master.mainstyle_id=vendor_purchase_order_master.mainstyle_id
    // left outer join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
    // left outer join ledger_master LM2 on LM2.ac_code=vendor_purchase_order_master.vendorId
    // left outer join usermaster on usermaster.userId=vendor_purchase_order_master.userId
    //  inner join job_status_master on job_status_master.job_status_id=vendor_purchase_order_master.endflag
    // where vendor_purchase_order_master.process_id=3  
    
    //   group by vendor_purchase_order_master.Ac_code,vendor_purchase_order_master.vpo_code,vendor_purchase_order_detail.color_id 
    // ");
    //     // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query); 
    //   return view('OrderProgressPackingDetailDashboard', compact('ProductionOrderDetailList','chekform','job_status_id'));
        
    //  }
    
    public function OrderProgressPackingDetailDashboard(Request $request)
    { 
        $vendorId = $request->vendorId ? $request->vendorId : '';
        $sales_order_no = $request->sales_order_no ? $request->sales_order_no : '';
        $job_status_id = $request->job_status_id;
       
        $filter = '';
        
        if($request->job_status_id > 0)
        {
            $filter =  ' AND bpom.job_status_id="'.$job_status_id.'"';
        } 
        
        if($vendorId != '')
        {
            $filter .= ' AND vpom.vendorId="'.$vendorId.'"';
        }
        
        if($sales_order_no != '')
        {
            $filter .= ' AND vpod.sales_order_no="'.$sales_order_no.'"';
        }
          
        if ($request->ajax()) 
        { 
            // $ProductionOrderDetailList = DB::select("SELECT vendor_purchase_order_master.vpo_code , vendor_purchase_order_master.sales_order_no,
            //     vendor_purchase_order_detail.po_code, LM2.ac_short_name as vendorName,LM2.ac_code as vendorId,job_status_master.job_status_name,
            //     vendor_purchase_order_master.mainstyle_id,mainstyle_name,vendor_purchase_order_master.style_no,ledger_master.ac_short_name as buyer_name,brand_master.brand_name,fg_master.fg_name,  
            //     ifnull( (vendor_purchase_order_detail.size_qty_total),0) as order_qty,
               
            //     vendor_purchase_order_detail.color_id,color_name,    
            //     (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail where packing_inhouse_detail.color_id=vendor_purchase_order_detail.color_id
            //     and packing_inhouse_detail.vpo_code=vendor_purchase_order_master.vpo_code)   as total_packing_qty 
                 
            //     FROM `vendor_purchase_order_master` 
            //     inner join vendor_purchase_order_detail on vendor_purchase_order_detail.vpo_code=vendor_purchase_order_master.vpo_code
            //     inner join color_master on color_master.color_id=vendor_purchase_order_detail.color_id
            //     left outer join main_style_master on main_style_master.mainstyle_id=vendor_purchase_order_master.mainstyle_id
            //     left outer join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code
            //     left outer join ledger_master LM2 on LM2.ac_code=vendor_purchase_order_master.vendorId
            //     left outer join usermaster on usermaster.userId=vendor_purchase_order_master.userId
            //     inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_detail.sales_order_no
            //     left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            //     left outer join fg_master on fg_master.fg_id=vendor_purchase_order_master.fg_id
            //     inner join job_status_master on job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
            //     where vendor_purchase_order_master.process_id=3 AND buyer_purchse_order_master.og_id != 4 ".$filter."
            //     group by vendor_purchase_order_master.Ac_code,vendor_purchase_order_master.vpo_code,vendor_purchase_order_detail.color_id");  
            
            
             $ProductionOrderDetailList = DB::select("SELECT 
                                            vpom.vpo_code,
                                            vpom.sales_order_no,
                                            vpod.po_code,
                                            LM2.ac_short_name AS vendorName,
                                            LM2.ac_code AS vendorId,
                                            jsm.job_status_name,
                                            vpom.mainstyle_id,
                                            msm.mainstyle_name,
                                            vpom.style_no,
                                            lm.ac_short_name AS buyer_name,
                                            bm.brand_name,
                                            fm.fg_name,
                                            IFNULL(vpod.size_qty_total, 0) AS order_qty,
                                            cm.color_id,
                                            cm.color_name,    
                                            IFNULL(pid.total_packing_qty, 0) AS total_packing_qty
                                        FROM 
                                            vendor_purchase_order_master AS vpom
                                        INNER JOIN 
                                            vendor_purchase_order_detail AS vpod ON vpod.vpo_code = vpom.vpo_code
                                        INNER JOIN 
                                            color_master AS cm ON cm.color_id = vpod.color_id
                                        LEFT JOIN 
                                            main_style_master AS msm ON msm.mainstyle_id = vpom.mainstyle_id
                                        LEFT JOIN 
                                            ledger_master AS lm ON lm.ac_code = vpom.Ac_code
                                        LEFT JOIN 
                                            ledger_master AS LM2 ON LM2.ac_code = vpom.vendorId
                                        LEFT JOIN 
                                            usermaster AS um ON um.userId = vpom.userId
                                        INNER JOIN 
                                            buyer_purchse_order_master AS bpom ON bpom.tr_code = vpod.sales_order_no
                                        LEFT JOIN 
                                            brand_master AS bm ON bm.brand_id = bpom.brand_id
                                        LEFT JOIN 
                                            fg_master AS fm ON fm.fg_id = vpom.fg_id
                                        INNER JOIN 
                                            job_status_master AS jsm ON jsm.job_status_id = bpom.job_status_id
                                        LEFT JOIN (
                                            SELECT 
                                                vpo_code, 
                                                color_id, 
                                                SUM(size_qty_total) AS total_packing_qty
                                            FROM 
                                                packing_inhouse_detail
                                            GROUP BY 
                                                vpo_code, 
                                                color_id
                                        ) AS pid ON pid.vpo_code = vpom.vpo_code AND pid.color_id = vpod.color_id
                                        WHERE 
                                            vpom.process_id = 3
                                            AND bpom.og_id != 4 
                                            ".$filter."
                                        GROUP BY 
                                            vpom.Ac_code,
                                            vpom.vpo_code,
                                            vpod.color_id");
                                        

            return Datatables::of($ProductionOrderDetailList)
            ->addColumn('sr_no', function ($row) 
            {
                static $sr_no = 0;  
                return ++$sr_no;    
            })
            ->setRowId(function ($row) 
            { 
                static $sr_no_reset = true;
                if($sr_no_reset) 
                {
                    $sr_no_reset = false;  
                    return $sr_no = 0;    
                }
            })
            ->addColumn('order_qty',function ($row) 
            {
                $order_qty = $row->order_qty;
               
                return number_format(($order_qty), 0, '.', ',');
            })
            ->addColumn('bal_total_packing_qty',function ($row) 
            {
                $bal_total_packing_qty = $row->order_qty-$row->total_packing_qty;
               
                return number_format(($bal_total_packing_qty), 0, '.', ',');
            })
            
            ->addColumn('total_packing_qty',function ($row) 
            {
                $total_packing_qty = $row->total_packing_qty;
               
                return number_format(($total_packing_qty), 0, '.', ',');
            })
            
            
            ->rawColumns(['sr_no','bal_total_packing_qty','order_qty','total_packing_qty'])
             
            ->make(true);
        }   
        
        $JobStatusList= DB::table('job_status_master')->get();
        $salesOrderList= DB::table('buyer_purchse_order_master')->where('delflag','=', '0')->get();
        $vendorList = LedgerModel::SELECT('*')->where('delflag','=', '0')->where('bt_id','=', 4)->get();
        
        return view('OrderProgressPackingDetailDashboard', compact('job_status_id', 'JobStatusList', 'salesOrderList', 'vendorList', 'vendorId', 'sales_order_no'));
        
    }
    
    
    public function OrderProgressCuttingDetailDashboard(Request $request)
    { 
        $vendorId = $request->vendorId ? $request->vendorId : '';
        $sales_order_no = $request->sales_order_no ? $request->sales_order_no : '';
        $job_status_id = $request->job_status_id;
       
        $filter = '';
        
        if($request->job_status_id > 0)
        {
            $filter =  ' AND bpom.job_status_id="'.$job_status_id.'"';
        } 
        
        if($vendorId != '')
        {
            $filter .= ' AND vpom.vendorId="'.$vendorId.'"';
        }
        
        if($sales_order_no != '')
        {
            $filter .= ' AND vpod.sales_order_no="'.$sales_order_no.'"';
        }
          
        if ($request->ajax()) 
        {   
            $ProductionOrderDetailList = DB::select("SELECT line_master.line_name,
                    vpom.vpo_code, vpom.sales_order_no, vpod.po_code, LM2.ac_short_name AS vendorName, LM2.ac_code AS vendorId, jsm.job_status_name, vpom.mainstyle_id, msm.mainstyle_name, vpom.style_no,
                    lm.ac_short_name AS buyer_name, bm.brand_name, fm.fg_name, IFNULL(vpod.size_qty_total, 0) AS order_qty, cm.color_id, cm.color_name, IFNULL(pid.total_cutting_qty, 0) AS total_cutting_qty
                FROM 
                    vendor_purchase_order_master AS vpom
                INNER JOIN 
                    vendor_purchase_order_detail AS vpod ON vpod.vpo_code = vpom.vpo_code
                INNER JOIN 
                    color_master AS cm ON cm.color_id = vpod.color_id
                LEFT JOIN 
                    main_style_master AS msm ON msm.mainstyle_id = vpom.mainstyle_id
                LEFT JOIN 
                    ledger_master AS lm ON lm.ac_code = vpom.Ac_code
                LEFT JOIN 
                    line_master ON line_master.line_id = vpom.line_id
                LEFT JOIN 
                    ledger_master AS LM2 ON LM2.ac_code = vpom.vendorId
                LEFT JOIN 
                    usermaster AS um ON um.userId = vpom.userId
                INNER JOIN 
                    buyer_purchse_order_master AS bpom ON bpom.tr_code = vpod.sales_order_no
                LEFT JOIN 
                    brand_master AS bm ON bm.brand_id = bpom.brand_id
                LEFT JOIN 
                    fg_master AS fm ON fm.fg_id = vpom.fg_id
                INNER JOIN 
                    job_status_master AS jsm ON jsm.job_status_id = bpom.job_status_id
                LEFT JOIN (SELECT  vpo_code, color_id, SUM(size_qty_total) AS total_cutting_qty FROM cut_panel_grn_detail GROUP BY vpo_code, color_id) AS pid ON pid.vpo_code = vpom.vpo_code AND pid.color_id = vpod.color_id
                WHERE vpom.process_id = 1 AND bpom.og_id != 4 ".$filter." GROUP BY vpom.Ac_code,vpom.vpo_code,vpod.color_id");
                    

            return Datatables::of($ProductionOrderDetailList)
            ->addColumn('sr_no', function ($row) 
            {
                static $sr_no = 0;  
                return ++$sr_no;    
            })
            ->setRowId(function ($row) 
            { 
                static $sr_no_reset = true;
                if($sr_no_reset) 
                {
                    $sr_no_reset = false;  
                    return $sr_no = 0;    
                }
            })
            ->addColumn('order_qty',function ($row) 
            {
                $order_qty = $row->order_qty;
               
                return number_format(($order_qty), 0, '.', ',');
            })
            ->addColumn('bal_total_cutting_qty',function ($row) 
            {
                $bal_total_cutting_qty = $row->order_qty-$row->total_cutting_qty;
               
                return number_format(($bal_total_cutting_qty), 0, '.', ',');
            })
            
            ->addColumn('total_cutting_qty',function ($row) 
            {
                $total_cutting_qty = $row->total_cutting_qty;
               
                return number_format(($total_cutting_qty), 0, '.', ',');
            })
            
            
            ->rawColumns(['sr_no','bal_total_cutting_qty','order_qty','total_cutting_qty'])
             
            ->make(true);
        }   
        
        $JobStatusList= DB::table('job_status_master')->get();
        $salesOrderList= DB::table('buyer_purchse_order_master')->where('delflag','=', '0')->get();
        $vendorList = LedgerModel::SELECT('*')->where('delflag','=', '0')->where('bt_id','=', 4)->get();
        
        return view('OrderProgressCuttingDetailDashboard', compact('job_status_id', 'JobStatusList', 'salesOrderList', 'vendorList', 'vendorId', 'sales_order_no'));
        
    }
    
    public function WIPDetailReport($vendorId)
    { 
        //DB::enableQueryLog();
         $ProductionOrderDetailList = DB::select("SELECT  vendor_purchase_order_master.sales_order_no, ifnull( (vendor_purchase_order_detail.size_qty_total),0) as order_qty,
            vendor_purchase_order_detail.color_id,color_name,    
            (select ifnull(sum(size_qty_total),0)  from packing_inhouse_detail  where packing_inhouse_detail.color_id=vendor_purchase_order_detail.color_id
            and packing_inhouse_detail.vpo_code=vendor_purchase_order_master.vpo_code AND vendor_purchase_order_master.vendorId =".$vendorId." 
            AND buyer_purchse_order_master.job_status_id = 1)   as total_packing_qty 
             
            FROM `vendor_purchase_order_master` 
            inner join vendor_purchase_order_detail on vendor_purchase_order_detail.vpo_code=vendor_purchase_order_master.vpo_code
            inner join color_master on color_master.color_id=vendor_purchase_order_detail.color_id
            inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=vendor_purchase_order_master.sales_order_no
            where vendor_purchase_order_master.process_id=3 and  vendor_purchase_order_master.endflag=1 AND vendor_purchase_order_master.vendorId =".$vendorId." AND buyer_purchse_order_master.job_status_id = 1
            group by vendor_purchase_order_master.Ac_code,vendor_purchase_order_master.vpo_code,vendor_purchase_order_detail.color_id");
     //dd(DB::getQueryLog());
        return view('WIPDetailReport',compact('ProductionOrderDetailList'));
    }
    
    public function create()
    {
        
        ini_set('memory_limit', '10G');
       // $this->sendEmail();
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUYER_PURCHASE_ORDER'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->where('color_master.status','=', '1')->get();
        $OrderGroupList = OrderGroupModel::join('order_group_auth', "order_group_auth.og_id","=","order_group_master.og_id")->where('order_group_auth.username','=',Session::get('username'))->where('order_group_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->where('ledger_master.delflag','=', '0')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")
                                    ->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '2')->get();
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->where('status','=', '1')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->where('status','=', '1')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->where('status','=', '1')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $PaymentTermsList = PaymentTermsModel::where('payment_term.delflag','=', '0')->get();
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->where('status','=', '1')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $MerchantList = MerchantMasterModel::where('merchant_master.delflag','=', '0')->where('status','=', '1')->get();
        $PDMerchantList = PDMerchantMasterModel::where('PDMerchant_master.delflag','=', '0')->where('status','=', '1')->get();
        $ShipmentList = ShipmentModeModel::where('shipment_mode_master.delflag','=', '0')->get();
        $WarehouseList = WarehouseModel::where('warehouse_master.delflag','=', '0')->get();
        $CountryList = Country::where('country_master.delflag','=', '0')->get();
        $JobStatusList= DB::table('job_status_master')->get();
        $OrderCategoryList= DB::table('order_category')->get();
        return view('BuyerPurchaseOrderMaster',compact('OrderCategoryList','OrderGroupList','MerchantList','PDMerchantList','ItemList','BrandList','SeasonList','MainStyleList','SubStyleList','CurrencyList','PaymentTermsList','DeliveryTermsList','ShipmentList','CountryList','WarehouseList', 'Ledger', 'FGList','UnitList','SizeList','ColorList','counter_number', 'JobStatusList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        if($request->og_id==4)
          {
               $codefetch = DB::table('counter_number')->select(DB::raw("tr_no2 + 1 as 'tr_no', c_code, code2 as code"))
              ->where('c_name','=','C1')
              ->where('type','=','BUYER_PURCHASE_ORDER')
               ->where('firm_id','=',1)
              ->first();
          }
          else
          {     
              $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
              ->where('c_name','=','C1')
              ->where('type','=','BUYER_PURCHASE_ORDER')
               ->where('firm_id','=',1)
              ->first();
              
          }
          
   $TrNo=$codefetch->code.'-'.$codefetch->tr_no;
   
//   if($request->session()->has('BuyerPurchase')) 
//   {
//       if(Session::get('BuyerPurchase')==1)
//          { $SessionValue=Session::get('BuyerPurchase');}else{$SessionValue=0;}
//   }
//   else
//   {
       
//       Session::put('BuyerPurchase', '1');
//       Session::save();
//       $SessionValue=Session::get('BuyerPurchase');
//   }

//   if($SessionValue==1)
//   {
         
       
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
           'order_type'=>'required',
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
 
    
    if($request->hasFile('tech_pack')) 
    {
        $fileName2 = time().'TP.'.$request->tech_pack->extension();  
        $request->tech_pack->move(public_path('uploads'), $fileName2);
        $fullTempFilePath1= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath1  . $fullTempFilePath1 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath1 . "-compressed " . $fullTempFilePath1);
        $fullTempFilePath1=$fileName2;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath1='';
    }
    
    if($request->hasFile('measurement_sheet')) 
    {
        $fileName3 = time().'MS.'.$request->measurement_sheet->extension();  
        $request->measurement_sheet->move(public_path('uploads'), $fileName3);
        $fullTempFilePath2= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath2  . $fullTempFilePath2 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath2 . "-compressed " . $fullTempFilePath2);
        $fullTempFilePath2=$fileName3;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath2='';
    }
    
    if($request->hasFile('fit_pp_comments')) 
    {
        $fileName4 = time().'FPPC.'.$request->fit_pp_comments->extension();  
        $request->fit_pp_comments->move(public_path('uploads'), $fileName4);
        $fullTempFilePath3= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath3  . $fullTempFilePath3 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath3 . "-compressed " . $fullTempFilePath3);
        $fullTempFilePath3=$fileName4;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath3='';
    }
    
    
    if($request->hasFile('approved_fabric_trim')) 
    {
        $fileName5 = time().'AFT.'.$request->approved_fabric_trim->extension();  
        $request->approved_fabric_trim->move(public_path('uploads'), $fileName5);
        $fullTempFilePath4= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath4  . $fullTempFilePath4 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath4 . "-compressed " . $fullTempFilePath4);
        $fullTempFilePath4=$fileName5;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath4='';
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
'inr_rate'=>$request->inr_rate,
'exchange_rate'=>$request->exchange_rate,
'order_rate'=>$request->order_rate,
'order_value'=>$request->order_value,
'shipped_qty'=>$request->shipped_qty,
'balance_qty'=>$request->balance_qty,
'sz_ws_total'=>$request->sz_ws_total,
'job_status_id'=>$request->job_status_id,
'og_id'=>$request->og_id,
'brand_id'=>$request->brand_id,
'order_received_date'=>$request->order_received_date,
'order_type'=>$request->order_type,
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
'tech_pack'=>$fullTempFilePath1,
'measurement_sheet'=>$fullTempFilePath2,
'fit_pp_comments'=>$fullTempFilePath3,
'approved_fabric_trim'=>$fullTempFilePath4,
'narration'=>$request->narration, 
'unit_id'=>$request->unit_ids,
'merchant_id'=>$request->merchant_id,
'PDMerchant_id'=>$request->PDMerchant_id,
'userId'=>$request->userId, 
'c_code'=>$request->c_code, 
'job_status_id'=>$request->job_status_id,
'from_tna_date'=>$request->from_tna_date, 
'to_tna_date'=>$request->to_tna_date, 
'order_close_date'=>$request->order_close_date, 
'orderCategoryId'=>$request->orderCategoryId, 
'in_out_id'=>$request->in_out_id, 
'sam'=>$request->sam, 
'delflag'=>'0',

);

// DB::enableQueryLog();
BuyerPurchaseOrderMasterModel::insert($data1);
// dd(DB::getQueryLog());

if($request->og_id==4)
{
    DB::select("update counter_number set tr_no2=tr_no2 + 1 where c_name ='C1' AND type='BUYER_PURCHASE_ORDER'");
}
else
{
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='BUYER_PURCHASE_ORDER'");
}
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
                    'style_no_id'=>$request->style_no_id[$x],
                    'color_id'=>$request->color_id[$x],
                    'item_code'=>$request->item_code[$x],
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array[$x],
                    'size_qty_total'=>$request->size_qty_total[$x],
                    'unit_id'=>$request->unit_ids,
                    'shipment_allowance'=>$request->shipment_allowance[$x],
                    'adjust_qty'=>$request->adjust_qty[$x],
                    'remark'=>$request->remark[$x],
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
                        'adjust_qty'=>$request->adjust_qty[$x],
                        'remark'=>$request->remark[$x],
                        'garment_rejection_allowance'=>$request->garment_rejection_allowance[$x],
                          );
        
        
        }
         BuyerPurchaseOrderDetailModel::insert($data2);
         SalesOrderDetailModel::insert($data3);
          $InsertSizeData=DB::select('call AddSizeQtyFromSalesOrder("'.$TrNo.'")');
        }
        
        if($request->og_id != 4)
        { 
            $this->sendEmail($TrNo);
        }
       
        //   Session::put('BuyerPurchase', '0');
        //   Session::save();
        //  // echo Session::get('BuyerPurchase');
        //   $SessionValue=0;

            return redirect()->route('BuyerPurchaseOrder.index')->with('message', 'New Record Saved Succesfully..!');
        
        // }
        // else
        // {
        //   if ($request->session()->has('BuyerPurchase') && Session::get('BuyerPurchase')==0) {
        //      return redirect()->route('BuyerPurchaseOrder.index')->with('error', 'Sorry Record has Not been Saved as Session Expired!!');
        //   }
        //   else
        //   {
        //       return redirect()->route('BuyerPurchaseOrder.index')->with('error', 'Sorry Record has Not been Saved as Session Expired!!');
        //   }
        // }


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
        ->join('delivery_terms_master', 'delivery_terms_master.dterm_id', '=', 'buyer_purchse_order_master.dterm_id', 'left outer') 
        ->join('shipment_mode_master', 'shipment_mode_master.ship_id', '=', 'buyer_purchse_order_master.ship_id', 'left outer')  
        ->join('warehouse_master', 'warehouse_master.warehouse_id', '=', 'buyer_purchse_order_master.warehouse_id', 'left outer')  
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.tr_code','=', $tr_code)
        
        ->get(['buyer_purchse_order_master.*','usermaster.username',
        'ledger_master.Ac_name','fg_master.fg_name','job_status_master.job_status_name',
        'order_group_master.order_group_name',
        'season_master.season_name','brand_master.brand_name','delivery_terms_master.delivery_term_name','shipment_mode_master.ship_mode_name','warehouse_master.warehouse_name','buyer_purchse_order_master.sam']);
        
        
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
        $OrderGroupList = OrderGroupModel::join('order_group_auth', "order_group_auth.og_id","=","order_group_master.og_id")->where('order_group_auth.username','=',Session::get('username'))->where('order_group_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->where('ledger_master.delflag','=', '0')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")
                    ->where('ledger_master.Ac_code','>', '39')->where('ledger_master.bt_id','=', '2')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $PaymentTermsList = PaymentTermsModel::where('payment_term.delflag','=', '0')->get();
        $DeliveryTermsList = DeliveryTermsModel::where('delivery_terms_master.delflag','=', '0')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $ShipmentList = ShipmentModeModel::where('shipment_mode_master.delflag','=', '0')->get();
        $CountryList = Country::where('country_master.delflag','=', '0')->get();
        $MerchantList = MerchantMasterModel::where('merchant_master.delflag','=', '0')->get();
        $PDMerchantList = PDMerchantMasterModel::where('PDMerchant_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $JobStatusList= DB::table('job_status_master')->get();
        
        $CostingStatus= DB::select("select is_approved from sales_order_costing_master where sales_order_no='".$id."'");
        $OrderCategoryList= DB::table('order_category')->get();
        //DB::enableQueryLog();
        $SalesOrderCostingList= DB::select("select count(*) as costing_count from sales_order_costing_master where sales_order_no='".$id."'"); 
        //dd(DB::getQueryLog());
        $costing_count = $SalesOrderCostingList[0]->costing_count;
 
        $is_approved= isset($CostingStatus[0]->is_approved) ? $CostingStatus[0]->is_approved : 0; 
         
        $salesOrderCostingData = DB::select('select sam from sales_order_costing_master where sales_order_no="'.$id.'"');
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($id);
        
        $WarehouseList = DB::table('ledger_details')->select('sr_no','site_code')->where('ledger_details.ac_code','=', $BuyerPurchaseOrderMasterList->Ac_code)->get();
        
        $BrandList = BrandModel::where('brand_master.delflag','=', '0')->where('brand_master.Ac_code','=', $BuyerPurchaseOrderMasterList->Ac_code)->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->where('mainstyle_id','=',$BuyerPurchaseOrderMasterList->mainstyle_id)->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->where('fg_id','=',$BuyerPurchaseOrderMasterList->fg_id)->get();
        // DB::enableQueryLog();
         //DB::enableQueryLog();      
         $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
         // dd(DB::getQueryLog());    
            //   DB::enableQueryLog();
              $ShippedQty=DB::select("select ifnull(sum(order_qty),0) as ShippedQty from sale_transaction_detail where sales_order_no='".$BuyerPurchaseOrderMasterList->tr_code."'");
              
        //         $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        if(count($salesOrderCostingData) > 0)
        {
            $sam = $salesOrderCostingData[0]->sam;
        }
        else
        {
            $sam = "";
        }
        $BuyerPurchaseOrderDetaillist = DB::table('buyer_purchase_order_detail')
            ->select('buyer_purchase_order_detail.*') 
            ->where('buyer_purchase_order_detail.tr_code','=', $BuyerPurchaseOrderMasterList->tr_code)
        ->get();
        


        $BOMCheck= DB::table('bom_master')->where('bom_master.sales_order_no',$BuyerPurchaseOrderMasterList->tr_code)->count();
         
        $StyleNoList = StyleNoModel::where('style_no_master.delflag','=', 0)->get();
                
        return view('BuyerPurchaseOrderMasterEdit',compact('OrderCategoryList','StyleNoList','costing_count','sam','OrderGroupList','BOMCheck','ShippedQty','is_approved','MerchantList','PDMerchantList','SizeDetailList','ItemList','BrandList','SeasonList','MainStyleList','SubStyleList','CurrencyList','PaymentTermsList','DeliveryTermsList','ShipmentList','CountryList','WarehouseList','BuyerPurchaseOrderMasterList','UnitList', 'Ledger','FGList','SizeList', 'ColorList',  'JobStatusList','BuyerPurchaseOrderDetaillist'));
    }

   
    public function EditDetailData(Request $request)
    {
        

        $BOMCheck= DB::table('bom_master')->where('bom_master.sales_order_no',$request->tr_code)->count();
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $SizeList= SizeModel::where('size_master.delflag','=', '0')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $StyleNoList = StyleNoModel::where('style_no_master.delflag','=', '0')->where('Ac_code','=', $request->Ac_code)->get();
 
        $html = '';        
        
        $BuyerPurchaseOrderDetaillist = DB::table('buyer_purchase_order_detail')
            ->select('buyer_purchase_order_detail.*') 
            ->where('buyer_purchase_order_detail.tr_code','=', $request->tr_code)
        ->get();
        $nos = 1;
        foreach($BuyerPurchaseOrderDetaillist as $List)
        {
        $html .= '  <tr>
                     <td><input type="text" name="id" value="'.$nos.'" id="id0" style="width:50px;"/></td>
                     <td><input type="text"  value="'.$List->item_code.'" id="icode"  style="width:50px;"/></td>
                     <td>
                        <select name="item_code[]" class="Item select2" style="width:250px; height:30px;"  required >
                        <option value="">--Select Fabric Color--</option>';
                        foreach($ItemList as  $row)
                        {
                            if($row->item_code == $List->item_code)
                            {
                                $select1 = 'selected';
                            }
                            else
                            {
                                $select1 = '';
                            }
                            
                            $html .= '<option value="'.$row->item_code.'" '.$select1.'>('.$row->item_code.') '.$row->item_name.'</option>';
                        }
                            $html .='</select>
                     </td>
                     <td>
                        <select name="style_no_id[]" class="style_no_id select2" style="width:250px; height:30px;">
                        <option value="">--Select Style No.--</option>';
                        foreach($StyleNoList as  $row2)
                        {
                            if($row2->style_no_id == $List->style_no_id)
                            {
                                $select2 = 'selected';
                            }
                            else
                            {
                                $select2 = '';
                            }
                            
                            $html .= '<option value="'.$row2->style_no_id.'" '.$select2.'>('.$row2->style_no_id.') '.$row2->style_no.'</option>';
                        }
                            $html .='</select>
                     </td>
                     <td>
                        <select name="color_id[]" class="color select2 Garment_color"  id="color_id" style="width:250px; height:30px;"required onchange="checkDuplicateColor(this);" >
                        <option value="">--Select Garment Color--</option>';
                        foreach($ColorList as  $row)
                        {                        
                            if($row->color_id == $List->color_id)
                            {
                                $select2 = 'selected'; 
                            }
                            else
                            {
                                $select2 = '';
                            }
                            $html .='<option value="'.$row->color_id.'" '.$select2.' >('.$row->color_id.') '.$row->color_name.'</option>';
                        }
                            $html .='</select></td>';
                      
                     $SizeQtyList=explode(',', $List->size_qty_array);
                     $no=1;
                     foreach($SizeQtyList  as $size_id)
                     {
                         $html .='<td><input type="text" name="s'.$no.'[]"  class="size_id s'.$no.'"  max="99999" oninput="if(this.value.length &gt; 5) this.value = this.value.slice(0,5);"  onkeyup="mycalc();"  value="'.$size_id.'" id="size_id'.$nos.'" style="width:80px;" required/></td>';
                         $no=$no+1;
                     }  
                         $html .='<td class="track">
                        <input type="text" name="size_qty_total[]" class="QTY" value="'.$List->size_qty_total.'" id="size_qty_total'.$nos.'" style="width:80px;" />
                        <input type="hidden" name="size_qty_array[]"  value="'.$List->size_qty_array.'" id="size_qty_array'.$nos.'" style="width:80px;"  />
                        <input type="hidden" name="size_array[]"  value="'.$List->size_array.'" id="size_array" style="width:80px;"  />
                     </td> 
                     <td><input type="number" step="any" name="shipment_allowance[]" value="'.$List->shipment_allowance.'" min="'.$List->shipment_allowance.'" id="shipment_allowance" style="width:80px;" required /> 
                        <input type="hidden" step="any" name="garment_rejection_allowance[]" value="'.$List->garment_rejection_allowance.'" id="garment_rejection_allowance" style="width:80px;" />
                        <input type="hidden" step="any" name="unit_id[]" value="'.$List->unit_id.'" id="unit_id" style="width:80px;" />
                     </td>
                     <td>
                      <input type="number" step="any" name="adjust_qty[]" value="'.$List->adjust_qty.'" id="adjust_qty" style="width:80px;" /> 
                     </td>
                     <td>
                      <input type="text" name="remark[]" value="'.$List->remark.'" id="remark" style="width:200px;" /> 
                     </td>
                     ';
                       if(Session::get('user_type')==1 ||  $BOMCheck == 0)
                       {   
                          $html .='<td><input type="button" style="width:40px;" id="Abutton0"  name="button[]" value="+" class="Abutton btn btn-warning pull-left"></td>
                                    <td><input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
                       }  
                        $html .='</tr>';
                        $nos++;
        }
        return response()->json(['html' => $html]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BuyerPurchaseOrderMasterModel  $buyerPurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,SalesOrderDetailActivityLog $loggerDetail,SalesOrderMasterActivityLog $loggerMaster)
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
           'brand_id'=>'required',
           'order_received_date'=>'required',
           'order_type'=>'required',
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

 
    if($request->hasFile('tech_pack')) 
    {
        unlink('uploads/'.$request->tech_pack_old);
        
        $fileName2 = time().'TP.'.$request->tech_pack->extension();  
        $request->tech_pack->move(public_path('uploads'), $fileName2);
        $fullTempFilePath1= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath1  . $fullTempFilePath1 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath1 . "-compressed " . $fullTempFilePath1);
        $fullTempFilePath1=$fileName1;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath1=$request->tech_pack_old;
    }
    
    if($request->hasFile('measurement_sheet')) 
    {
        unlink('uploads/'.$request->measurement_sheet_old);
        
        $fileName3 = time().'MS.'.$request->measurement_sheet->extension();  
        $request->measurement_sheet->move(public_path('uploads'), $fileName3);
        $fullTempFilePath2= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath2  . $fullTempFilePath2 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath2 . "-compressed " . $fullTempFilePath2);
        $fullTempFilePath2=$fileName3;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath2=$request->measurement_sheet_old;
    }
    
    if($request->hasFile('fit_pp_comments')) 
    {
        unlink('uploads/'.$request->fit_pp_comments_old);
        $fileName4 = time().'FPPC.'.$request->fit_pp_comments->extension();  
        $request->fit_pp_comments->move(public_path('uploads'), $fileName4);
        $fullTempFilePath3= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath3  . $fullTempFilePath3 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath3 . "-compressed " . $fullTempFilePath3);
        $fullTempFilePath3=$fileName4;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath3=$request->fit_pp_comments_old;
    }
    
    
    if($request->hasFile('approved_fabric_trim')) 
    {
        unlink('uploads/'.$request->approved_fabric_trim_old);
        $fileName5 = time().'AFT.'.$request->approved_fabric_trim->extension();  
        $request->approved_fabric_trim->move(public_path('uploads'), $fileName5);
        $fullTempFilePath4= public_path('uploads/');
        $output = shell_exec("shrink " . $fullTempFilePath4  . $fullTempFilePath4 . "-compressed ");
        shell_exec("mv " . $fullTempFilePath4 . "-compressed " . $fullTempFilePath4);
        $fullTempFilePath4=$fileName5;
        // Compress and Save  File1 End
    }
     else
    {
        $fullTempFilePath4=$request->approved_fabric_trim_old;
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
        'inr_rate'=>$request->inr_rate,
        'exchange_rate'=>$request->exchange_rate,
        'order_rate'=>$request->order_rate,
        'order_value'=>$request->order_value,
        'shipped_qty'=>$request->shipped_qty,
        'balance_qty'=>$request->balance_qty,
        'sz_ws_total'=>$request->sz_ws_total,
        'job_status_id'=>$request->job_status_id,
        'og_id'=>isset($request->og_id) ? $request->og_id : 0,
        'brand_id'=>$request->brand_id,
        'order_received_date'=>$request->order_received_date,
        'order_type'=>$request->order_type,
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
        'tech_pack'=>$fullTempFilePath1,
        'measurement_sheet'=>$fullTempFilePath2,
        'fit_pp_comments'=>$fullTempFilePath3,
        'approved_fabric_trim'=>$fullTempFilePath4,
        'narration'=>$request->narration, 
        'unit_id'=>$request->unit_ids,
        'merchant_id'=>$request->merchant_id, 
        'PDMerchant_id'=>$request->PDMerchant_id,
        'userId'=>$request->userId, 
        'c_code'=>$request->c_code, 
        'job_status_id'=>$request->job_status_id,
        'delflag'=>'0',
        'created_at'=>$request->created_at,
        'from_tna_date'=>$request->from_tna_date, 
        'to_tna_date'=>$request->to_tna_date, 
        'order_close_date'=>$request->order_close_date,
        'orderCategoryId'=>$request->orderCategoryId, 
        'in_out_id'=>$request->in_out_id, 
        'sam'=>$request->sam,
        );
//   DB::enableQueryLog();
        $BuyerPurchaseOrderList = BuyerPurchaseOrderMasterModel::findOrFail($request->input('tr_code'));  
        
        
        
             $MasterOldFetch = DB::table('buyer_purchse_order_master')
                    ->select('tr_date', 'Ac_code', 'mainstyle_id', 'substyle_id', 'fg_id', 'style_no', 'po_code', 'sz_code', 'total_qty','inr_rate','exchange_rate',
                   'order_rate', 'order_value', 'shipped_qty', 'balance_qty', 'sz_ws_total', 'og_id', 'brand_id', 'order_received_date', 
                    'order_type', 'season_id', 'currency_id', 'ptm_id', 'dterm_id', 'style_description', 'style_img_path', 'ship_id', 'country_id', 'warehouse_id', 
                    'shipment_date', 'plan_cut_date', 'inspection_date', 'ex_factory_date', 'buyer_document_path', 'tech_pack', 'measurement_sheet', 'fit_pp_comments', 
                    'approved_fabric_trim', 'narration', 'unit_id', 'merchant_id', 'PDMerchant_id','job_status_id','from_tna_date', 'to_tna_date', 'order_close_date','orderCategoryId','in_out_id', 'sam')  
                    ->where('tr_code',$request->tr_code)
                    ->first();
        
             $MasterOld = (array) $MasterOldFetch;
        
        
        
      
        //   dd(DB::getQueryLog());
      
        $BuyerPurchaseOrderList->fill($data1)->save();
        
      
     
        
             $MasterNew=[
        'tr_date'=>$request->tr_date,
        'Ac_code'=>$request->Ac_code,
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'po_code'=>$request->po_code,
        'sz_code'=>$request->sz_code,
        'total_qty'=>$request->total_qty,
        'inr_rate'=>$request->inr_rate,
        'exchange_rate'=>$request->exchange_rate,
        'order_rate'=>$request->order_rate,
        'order_value'=>$request->order_value,
        'shipped_qty'=>$request->shipped_qty,
        'balance_qty'=>$request->balance_qty,
        'sz_ws_total'=>$request->sz_ws_total,
        'og_id'=>isset($request->og_id) ? $request->og_id : 0,
        'brand_id'=>$request->brand_id,
        'order_received_date'=>$request->order_received_date,
        'order_type'=>$request->order_type,
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
        'tech_pack'=>$fullTempFilePath1,
        'measurement_sheet'=>$fullTempFilePath2,
        'fit_pp_comments'=>$fullTempFilePath3,
        'approved_fabric_trim'=>$fullTempFilePath4,
        'narration'=>$request->narration, 
        'unit_id'=>$request->unit_ids,
        'merchant_id'=>$request->merchant_id, 
        'PDMerchant_id'=>$request->PDMerchant_id,
        'job_status_id'=>$request->job_status_id,
        'from_tna_date'=>$request->from_tna_date, 
        'to_tna_date'=>$request->to_tna_date, 
        'order_close_date'=>$request->order_close_date,
        'orderCategoryId'=>$request->orderCategoryId, 
        'in_out_id'=>$request->in_out_id, 
        'sam'=>$request->sam
            ];

          
               try {
            $loggerMaster->logIfChangedSalesOrderMaster(
            'buyer_purchse_order_master',
            $request->tr_code,
            $MasterOld,
            $MasterNew,
            'UPDATE',
            $request->tr_date,
            'buyer_purchse_order_master'
            );
            // Log::info('Logger called successfully for buyer_purchse_order_master.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for buyer_purchse_order_master.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'tr_code' =>  $request->tr_code,
            'data' => $MasterNew
            ]);
            }  
 
        
        
        
            $olddata1 = DB::table('sales_order_detail')
            ->select('color_id','item_code', 
            's1', 's2', 's3', 's4', 's5', 's6', 's7', 's8', 's9', 's10','s11', 's12', 's13', 's14',
            's15', 's16', 's17', 's18', 's19', 's20',
            'size_qty_total', 'shipment_allowance','garment_rejection_allowance','adjust_qty','remark','size_array')  
            ->where('tr_code',$request->input('tr_code'))
            ->get()
            ->map(function ($item) {
            return (array) $item;
            })
            ->toArray();
            
            $combinedOldData = $olddata1;



        DB::table('buyer_purchase_order_detail')->where('tr_code', $request->input('tr_code'))->delete();
        DB::table('sales_order_detail')->where('tr_code', $request->input('tr_code'))->delete();
        

        $color_id = $request->input('color_id');
        if(count($color_id)>0)
        {
            
            $newDataDetail2=[];
        
        for($x=0; $x<count($color_id); $x++) 
        {
            # code...
          
            $data2[]=array(
                  
                            'tr_code'=>$request->tr_code,
                            'tr_date'=>$request->tr_date,
                            'Ac_code'=>$request->Ac_code,
                            'po_code'=>$request->po_code,
                            'style_no'=>$request->style_no,
                            'style_no_id'=>$request->style_no_id[$x],
                            'color_id'=>$request->color_id[$x],
                            'item_code'=>$request->item_code[$x],
                            'size_array'=>$request->size_array[$x],
                            'size_qty_array'=>$request->size_qty_array[$x],
                            'size_qty_total'=>$request->size_qty_total[$x],
                            'unit_id'=>$request->unit_ids,
                            'shipment_allowance'=>$request->shipment_allowance[$x],
                            'adjust_qty'=>$request->adjust_qty[$x],
                            'remark'=>$request->remark[$x],
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
                        'adjust_qty'=>$request->adjust_qty[$x],
                        'remark'=>$request->remark[$x]
                          );
                          
                          
                          
                          
                        $newDataDetail2[]=[
                        'color_id'=>$request->color_id[$x],
                        'item_code'=>$request->item_code[$x],
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
                        'shipment_allowance'=>$request->shipment_allowance[$x],
                        'garment_rejection_allowance'=>$request->garment_rejection_allowance[$x],
                        'adjust_qty'=>$request->adjust_qty[$x],
                        'remark'=>$request->remark[$x],
                        'size_array'=>$request->size_array[$x]
                        ]; 
                
                }
                
                
                
                     $combinedNewData = $newDataDetail2;       
           
            try {
            $loggerDetail->logIfChangedSalesOrderDetail(
            'sales_order_detail',
            $request->tr_code,
            $combinedOldData,
            $combinedNewData,
            'UPDATE',
            $request->input('tr_date'),
            'sales_order_detail'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for sales_order_detail.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'tr_code' => $request->tr_code,
            'data' => $combinedNewData
            ]);
            }  
                
                
                BuyerPurchaseOrderDetailModel::insert($data2);
                SalesOrderDetailModel::insert($data3);
                $InsertSizeData=DB::select('call AddSizeQtyFromSalesOrder("'.$request->tr_code.'")');
                
                $stitchingData = DB::table('stitching_inhouse_master')
                    ->where('sales_order_no', $request->tr_code)
                    ->pluck('sti_code');
                
                if ($stitchingData->isNotEmpty()) 
                { 
                    DB::connection('hrms_database')
                        ->table('production_detail')
                        ->whereIn('sti_code', $stitchingData)
                        ->update(['sam' => $request->sam]);
                }
                
                SourceModel::on('mysql');
 
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
        
      $Records=DB::select("select (select count(soc_code) from sales_order_costing_master where sales_order_no='".$id."') as costings,
      (select count(bom_code) from bom_master where sales_order_no='".$id."') boms, 
      (select count(vw_code) from vendor_work_order_master where sales_order_no='".$id."') as workorders,
      (select count(vpo_code) from vendor_purchase_order_master where sales_order_no='".$id."') as processorders");
      
       
      $counts=$Records[0]->costings + $Records[0]->boms + $Records[0]->workorders + $Records[0]->processorders;
   //   echo $counts;
      if($counts == 0)
      {
        DB::table('buyer_purchse_order_master')->where('tr_code', $id)->delete();
        DB::table('sales_order_detail')->where('tr_code', $id)->delete();
        DB::table('buyer_purchase_order_size_detail')->where('tr_code', $id)->delete();
        DB::table('buyer_purchase_order_detail')->where('tr_code', $id)->delete();
        return redirect()->route('BuyerPurchaseOrder.index')->with('error', 'Delete Record Succesfully');
      }
      else
      {
           return redirect()->route('BuyerPurchaseOrder.index')->with('error', 'Sales Order Can Not be Deleted, Remove Reference entries from Costing, BOM, Work Orders and Process Orders..! ');
      }
      
         
        
    }


    public function GetTaxList(Request $request)
    {
          
        $TaxList = DB::select('select item_code , cgst_per, sgst_per, igst_per from item_master where item_code='.$request->item_code);
        return json_encode($TaxList);
    
    }
    
    
     public function CheckOpenWorkProcessOrders(Request $request)
    {
        //DB::enableQueryLog();
         // dd(DB::getQueryLog());
           $Records=DB::select("select (select count(vw_code) from vendor_work_order_master where endflag=1 and sales_order_no='".$request->sales_order_no."') as workorders,
      (select count(vpo_code) from vendor_purchase_order_master where endflag=1 and  sales_order_no='".$request->sales_order_no."') as processorders");
       // dd(DB::getQueryLog());
        return json_encode($Records);
    
    } 
    
    public function GetBrandList(Request $request)
    { 
        $html = '';
        if (!$request->Ac_code) {
        $html = '<option value="">--Brand Name--</option>';
        } else {
       
         $html = '<option value="">--Brand Name--</option>';
        $StyleList = DB::table('brand_master')->where("delflag", "=", 0)->where('Ac_code', $request->Ac_code)->get();
        
        foreach ($StyleList as $row) {
                $html .= '<option value="'.$row->brand_id.'">'.$row->brand_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    } 
     
    
    public function GetCutPlanReport()
    
    {
        $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where job_status_id=1');
         return view('GetCutPlanReport' ,compact('SalesOrderList'));  
        
    }
    
    
       public function CuttingPOList(Request $request)
    {   
        $html = '';
          if (!$request->sales_order_no) {
        $html = '<option value="">-- Cutting PO No. --</option>';
        } else {
       
         $html = '<option value="">-- Cutting PO No. --</option>';
        $CuttingPOList = DB::table('vendor_purchase_order_master')->select('vpo_code')->where('sales_order_no', $request->sales_order_no)->where('process_id', '1')->get();
        
        foreach ($CuttingPOList as $row) {
                $html .= '<option value="'.$row->vpo_code.'">'.$row->vpo_code.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);
    } 
    
    public function CutPlanReport(Request $request)
    {   
         $FirmDetail =  DB::table('firm_master')->first();
         $sales_order_no=$request->sales_order_no;
         $vpo_code=$request->vpo_code;
         $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where job_status_id=1');
        
       
        
        
         return view('CutPlanReport' ,compact('SalesOrderList','FirmDetail','sales_order_no','vpo_code'));  
        
    }
    
    
    
    public function rptCuttingOCR1(Request $request)
    {
        $FirmDetail =  DB::table('firm_master')->first();
        $sales_order_no=$request->sales_order_no;
        $vpo_code=$request->vpo_code;
        $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where job_status_id=1');
        return view('rptCuttingOCR1',compact('SalesOrderList','FirmDetail','sales_order_no','vpo_code'));
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
      
        ini_set('memory_limit', '10G');  
        $sz_code= $request->input('sz_code');
        $Ac_code= $request->Ac_code;
        
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->where('color_master.status','=', '1')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $ItemList=ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', 1)->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $sz_code)->get();
        $StyleNoList = StyleNoModel::where('style_no_master.delflag','=', 0)->where('Ac_code', '=', $Ac_code)->get();
        
        $sizes='';
        
        foreach ($SizeDetailList as $sz) 
        {
            
            $sizes=$sizes.$sz->size_id.',';
        }
        $sizes=rtrim($sizes,',');
        
        
        
        $html = '';
        
        $html .= '
        <div class="table-responsive">
         <table id="footable_3" class="table  table-bordered table-striped m-b-0  footable_3">
                <thead>
                <tr class="text-center">
                <th>Sr. No.</th>
                <th>Fabric Color</th>
                <th>Style No.</th>
                <th>Garment Color</th>';
                   foreach ($SizeDetailList as $sz) 
                    {
                        $html.='<th>'.$sz->size_name.'</th>';
                         
                    }
                    $html.=' 
                    <th>Total Qty</th>
                    
                    <th nowrap>Ship Allow %</th>
                    <th>Adjust Qty</th>
                    <th>Remark</th>
                    <th>Add</th>
                    <th>Remove</th>
                </tr>
                </thead>
                <tbody>';
            $no=1;
            
            $html .='<tr>';
            $html .='
            <td><input type="text" name="id[]" value="'.$no.'" id="id0" style="width:50px;"/></td>
            
            <td> <select name="item_code[]" class="select2-select select2"  id="item_code0" style="width:250px; height:30px;" required>
            <option value="">--Select Fabric Color--</option>';
    
            foreach($ItemList as  $row2)
            {
                $html.='<option value="'.$row2->item_code.'"';
                $html.='> ('.$row2->item_code.') '.$row2->item_name.'</option>';
            }
            
            $html.='</select></td> 
            
            <td> <select name="style_no_id[]" class="select2-select select2 style_no_id"  id="style_no_id" style="width:250px; height:30px;">
            <option value="">--Select Style No--</option>';
    
            foreach($StyleNoList as  $row5)
            {
                $html.='<option value="'.$row5->style_no_id.'"';
                $html.='>('.$row5->style_no_id.') '.$row5->style_no.'</option>';
            }
            
            $html.='</select></td>
            
            <td> <select name="color_id[]" class="select2-select select2 Garment_color"  id="color_id0" style="width:250px; height:30px;" required onchange="checkDuplicateColor(this);">
            <option value="">--Select Garment Color--</option>';
    
            foreach($ColorList as  $row1)
            {
                $html.='<option value="'.$row1->color_id.'"';
                $html.='> ('.$row1->color_id.') '.$row1->color_name.'</option>';
            }
            
            $html.='</select></td>';
            $n=1;
            foreach ($SizeDetailList as $row) 
            {
                $html.='<td><input type="number" name="s'.$n.'[]" class="size_id"  max="99999" oninput="if(this.value.length > 5) this.value = this.value.slice(0,5);"  value="0" id="size_id0" style="width:80px; height:30px;" onkeyup="mycalc();" /></td>';
                $n=$n+1;
            }
            $html.='
             <td class="track">
            <input type="number" readOnly name="size_qty_total[]" class="QTY" value="0" id="size_qty_total" style="width:80px; height:30px;""  /> <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px;"  />
            <input type="hidden" name="size_array[]"  value="'.$sizes.'" id="size_array" style="width:80px; "  /></td>
              
              <td><input type="number" step="any" name="shipment_allowance[]"  value="0" id="shipment_allowance" style="width:80px;" required />
               <input type="hidden" step="any" name="garment_rejection_allowance[]"   value="0" id="garment_rejection_allowance" style="width:80px;" required />
            </td>
             <td><input type="number" step="any" name="adjust_qty[]"  value="0" id="adjust_qty" style="width:80px;"/></td>
             <td><input type="text" name="remark[]"  value="" id="remark" style="width:200px;"/></td>
            <td><input type="button" style="width:40px;" id="Abutton0"  name="button[]"  value="+" class="Abutton btn btn-warning pull-left"></td>
            <td><input type="button" id="Bbutton0" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
         $html .='</tr>
         
          </tbody>
       
        </table> 
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
                $html.='<th> '.$row->size_name.' <input type="number" name="s'.$nx.'total[]" class="size_total"   value="" id="s'.$nx.'total" style="width:80px; height:30px;" readonly /></th> ';
                $nx=$nx+1;
            }
        $html.='<th><input type="hidden" name="sz_ws_total" value="" id="sz_ws_total" style="width:80px; height:30px;"  " /> </th>
        <th></th>
        <th></th>
        <th></th>
        </tr> <tbody> </table>';    
        
        return response()->json(['html' => $html]);
            
    }

    public function getAddress(Request $request)
    {
          
        $Address = DB::select('select consignee_address from ledger_details where Ac_code='.$request->Ac_code.' and site_code="'.$request->site_code.'"');
        return json_encode($Address);
    
    }
    
      
    public function GetVendorWorkOrderStock()
    {
        $JobStatusList = DB::table('job_status_master')->whereIn('job_status_id',[1,2])->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        return view('GetVendorWorkOrderStockValue',compact('Ledger','JobStatusList'));
    }
    
    
    
    
    public function VendorWorkOrderStock(Request $request)
    { 
        
        if($request->vendorId == 'All')
        {
            // $VendorWorkOrderList=DB::select("select ledger_master.ac_name as vendorName,  sales_order_no,
            // LM2.ac_name as BuyerName, vw_code, job_status_name , vendorId   from vendor_work_order_master 
            // inner join ledger_master on ledger_master.ac_code= vendor_work_order_master.vendorId
            // inner join ledger_master as LM2 on LM2.ac_code= vendor_work_order_master.Ac_code
            // inner join job_status_master on job_status_master.job_status_id= vendor_work_order_master.endflag");
            
            // $VendorCutProcessOrderList=DB::select("select ledger_master.ac_name as vendorName,  sales_order_no,
            // LM2.ac_name as BuyerName, vpo_code, job_status_name , vendorId   from vendor_purchase_order_master 
            // inner join ledger_master on ledger_master.ac_code= vendor_purchase_order_master.vendorId
            // inner join ledger_master as LM2 on LM2.ac_code= vendor_purchase_order_master.Ac_code
            // inner join job_status_master on job_status_master.job_status_id= vendor_purchase_order_master.endflag
            // where vendor_purchase_order_master.process_id=1
            // ");
            
            // $VendorPackProcessOrderList=DB::select("select ledger_master.ac_name as vendorName,  sales_order_no,
            // LM2.ac_name as BuyerName, vpo_code, job_status_name , vendorId   from vendor_purchase_order_master 
            // inner join ledger_master on ledger_master.ac_code= vendor_purchase_order_master.vendorId
            // inner join ledger_master as LM2 on LM2.ac_code= vendor_purchase_order_master.Ac_code
            // inner join job_status_master on job_status_master.job_status_id= vendor_purchase_order_master.endflag
            // where vendor_purchase_order_master.process_id=3");
            
        
             $VendorCutProcessOrderList = DB::table('vendor_purchase_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vpo_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_purchase_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_purchase_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_purchase_order_master.endflag')
                ->where('vendor_purchase_order_master.process_id', "1")
                ->where('vendor_purchase_order_master.endflag',  $request->endflag) 
                ->get();
                
             $VendorWorkOrderList = DB::table('vendor_work_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vw_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_work_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_work_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_work_order_master.endflag')
                ->where('vendor_work_order_master.endflag',  $request->endflag) 
                ->get();
             $VendorPackProcessOrderList = DB::table('vendor_purchase_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vpo_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_purchase_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_purchase_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_purchase_order_master.endflag')
                ->where('vendor_purchase_order_master.process_id', "3")
                ->where('vendor_purchase_order_master.endflag',  $request->endflag) 
                ->get();
            
            
        } 
        else
        {
             // DB::enableQueryLog();
             $VendorCutProcessOrderList = DB::table('vendor_purchase_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vpo_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_purchase_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_purchase_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_purchase_order_master.endflag')
                ->where('vendor_purchase_order_master.vendorId', $request->vendorId)
                ->where('vendor_purchase_order_master.sales_order_no', $request->sales_order_no)
                ->where('vendor_purchase_order_master.endflag', $request->endflag)
                ->where('vendor_purchase_order_master.process_id', "1")  
                ->get();
             //dd(DB::getQueryLog());
             
          
            $VendorWorkOrderList = DB::table('vendor_work_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 
                'vendor_work_order_master.vw_code', 'job_status_name' , 'vendor_work_order_master.vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_work_order_master.vendorId')       
                // ->join('vendor_work_order_detail', 'vendor_work_order_detail.vw_code','=','vendor_work_order_master.vw_code')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_work_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_work_order_master.endflag')
                ->where('vendor_work_order_master.vendorId', $request->vendorId)
                ->where('vendor_work_order_master.sales_order_no', $request->sales_order_no)
                ->where('vendor_work_order_master.endflag', $request->endflag) 
                ->get();
            //DB::enableQueryLog();
             $VendorPackProcessOrderList = DB::table('vendor_purchase_order_master')
                ->select('ledger_master.ac_name as vendorName','vendor_purchase_order_detail.color_id','color_master.color_name',
                        'vendor_purchase_order_master.sales_order_no','LM2.ac_name as BuyerName', 'vendor_purchase_order_master.vpo_code',
                        'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_purchase_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_purchase_order_master.Ac_code')                             
                ->join('vendor_purchase_order_detail', 'vendor_purchase_order_detail.vpo_code','=','vendor_purchase_order_master.vpo_code' )     
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_purchase_order_master.endflag')                      
                ->join('color_master', 'color_master.color_id','=','vendor_purchase_order_detail.color_id')
                ->where('vendor_purchase_order_master.vendorId', $request->vendorId)
                ->where('vendor_purchase_order_master.sales_order_no', $request->sales_order_no)
                ->where('vendor_purchase_order_master.endflag', $request->endflag)
                ->where('vendor_purchase_order_master.process_id', "3")
                ->GROUPBY('vendor_purchase_order_detail.vpo_code')  
                ->get();
            //dd(DB::getQueryLog());
            // $VendorWorkOrderList=DB::select("select ledger_master.ac_name as vendorName,  sales_order_no,
            // LM2.ac_name as BuyerName, vw_code, job_status_name , vendorId   from vendor_work_order_master 
            // inner join ledger_master on ledger_master.ac_code= vendor_work_order_master.vendorId
            // inner join ledger_master as LM2 on LM2.ac_code= vendor_work_order_master.Ac_code
            // inner join job_status_master on job_status_master.job_status_id= vendor_work_order_master.endflag
            // where vendor_work_order_master.vendorId='".$request->vendorId."' and vendor_work_order_master.sales_order_no='".$request->sales_order_no."'");
            
            // $VendorCutProcessOrderList=DB::select("select ledger_master.ac_name as vendorName,  sales_order_no,
            // LM2.ac_name as BuyerName, vpo_code, job_status_name , vendorId   from vendor_purchase_order_master 
            // inner join ledger_master on ledger_master.ac_code= vendor_purchase_order_master.vendorId
            // inner join ledger_master as LM2 on LM2.ac_code= vendor_purchase_order_master.Ac_code
            // inner join job_status_master on job_status_master.job_status_id= vendor_purchase_order_master.endflag
            // where vendor_purchase_order_master.vendorId='".$request->vendorId."' 
            // and vendor_purchase_order_master.sales_order_no='".$request->sales_order_no."'
            // and vendor_purchase_order_master.process_id=1
            // ");
            
            // $VendorPackProcessOrderList=DB::select("select ledger_master.ac_name as vendorName,  sales_order_no,
            // LM2.ac_name as BuyerName, vpo_code, job_status_name , vendorId   from vendor_purchase_order_master 
            // inner join ledger_master on ledger_master.ac_code= vendor_purchase_order_master.vendorId
            // inner join ledger_master as LM2 on LM2.ac_code= vendor_purchase_order_master.Ac_code
            // inner join job_status_master on job_status_master.job_status_id= vendor_purchase_order_master.endflag
            // where vendor_purchase_order_master.vendorId='".$request->vendorId."' 
            // and vendor_purchase_order_master.sales_order_no='".$request->sales_order_no."' and vendor_purchase_order_master.process_id=3"); 
            
        }
            
         return view('VendorWorkOrderStockValueReport',compact('VendorWorkOrderList','VendorCutProcessOrderList','VendorPackProcessOrderList'));
       }

    public function VendorWorkOrderStockPaginate(Request $request)
    {
             $VendorWorkOrderList = DB::table('vendor_work_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vw_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_work_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_work_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_work_order_master.endflag')
                ->paginate(10);
            
             $VendorCutProcessOrderList = DB::table('vendor_purchase_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vpo_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_purchase_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_purchase_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_purchase_order_master.endflag')
                ->where('vendor_purchase_order_master.process_id', "1")
                ->paginate(10);
            
             $VendorPackProcessOrderList = DB::table('vendor_purchase_order_master')
                ->select('ledger_master.ac_name as vendorName',  'sales_order_no','LM2.ac_name as BuyerName', 'vpo_code', 'job_status_name' , 'vendorId')
                ->join('ledger_master', 'ledger_master.ac_code','=','vendor_purchase_order_master.vendorId')                           
                ->join('ledger_master as LM2', 'LM2.ac_code','=','vendor_purchase_order_master.Ac_code')                        
                ->join('job_status_master', 'job_status_master.job_status_id','=','vendor_purchase_order_master.endflag')
                ->where('vendor_purchase_order_master.process_id', "3")
                ->paginate(10);
     
            
         return view('VendorWorkOrderStockValueReport',compact('VendorWorkOrderList','VendorCutProcessOrderList','VendorPackProcessOrderList'));
    }
       
    public function GetLiveRunningOrderStatus()
    { 
        return view('GetLiveRunningOrderStatus');
    }
    
           
    public function rptLiveRunningOrderStatus(Request $request)
    {
        
        $date = $request->date; 
         
           //DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
        select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','order_type_master.order_type','order_type_master.orderTypeId','payment_term.ptm_name',DB::raw('sum(total_qty) as order_qty'),DB::raw('sum(total_qty*buyer_purchse_order_master.sam) as orderMin'),
        DB::raw('(select ifnull(sum(order_qty),0) from sale_transaction_detail 
        INNER JOIN buyer_purchse_order_master as B1 ON B1.tr_code = sale_transaction_detail.sales_order_no 
        where B1.brand_id = buyer_purchse_order_master.brand_id AND job_status_id=1 AND B1.order_type = buyer_purchse_order_master.order_type AND sale_date<="'.$date.'") as shipped_qty'), 
        DB::raw('(select sum(packing_inhouse_master.total_qty) from packing_inhouse_master 
        INNER JOIN buyer_purchse_order_master as B1 ON B1.tr_code = packing_inhouse_master.sales_order_no 
        where B1.brand_id = buyer_purchse_order_master.brand_id AND job_status_id=1 AND B1.order_type = buyer_purchse_order_master.order_type 
        AND packing_inhouse_master.delflag=0 AND pki_date<="'.$date.'" ) as shipped_qty2'),
        DB::raw('(select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty'),
        DB::raw('(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark'),
        'brand_master.brand_name',
        DB::raw('(select ifnull(sum(cut_panel_grn_master.total_qty),0) from cut_panel_grn_master INNER JOIN buyer_purchse_order_master as B1 ON B1.tr_code = cut_panel_grn_master.sales_order_no where B1.brand_id = buyer_purchse_order_master.brand_id AND job_status_id=1 AND B1.order_type = buyer_purchse_order_master.order_type AND cpg_date<="'.$date.'" ) as cut_qty')
        , DB::raw('(select ifnull(sum(stitching_inhouse_master.total_qty),0) from stitching_inhouse_master INNER JOIN buyer_purchse_order_master as B1 ON B1.tr_code = stitching_inhouse_master.sales_order_no where B1.brand_id = buyer_purchse_order_master.brand_id AND job_status_id=1 AND B1.order_type = buyer_purchse_order_master.order_type AND sti_date<="'.$date.'") as prod_qty'))
        ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        ->join('order_type_master', 'order_type_master.orderTypeId', '=', 'buyer_purchse_order_master.order_type')
        ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
        ->leftJoin('payment_term', 'payment_term.ptm_id', '=', 'buyer_purchse_order_master.ptm_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('buyer_purchse_order_master.og_id','!=', '4')
        ->where('buyer_purchse_order_master.job_status_id','=', '1') 
        ->where('buyer_purchse_order_master.order_received_date','<=', $date) 
        ->groupBy('buyer_purchse_order_master.brand_id')
        ->groupBy('buyer_purchse_order_master.order_type')
        ->get(); 
        // dd(DB::getQueryLog()); 
        $job_status_id = 1;
        return view('rptLiveRunningOrderStatus', compact('Buyer_Purchase_Order_List', 'date','job_status_id'));
    }
    
    public function GetOCRSummary()
    { 
        $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id IN(1,2)');
        $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master');
        $OrderTypeList=DB::select('select * from order_type_master where delflag=0');
        $BuyerList=DB::select('select * from ledger_master where delflag=0 AND bt_id = 2');
        
        return view('GetOCRSummary',compact('JobStatusList','SalesOrderList','OrderTypeList','BuyerList'));
    }  
    
    public function dumpOCRSummaryData(Request $request)
    {  
          DB::table('dump_ocr_summary_report')->delete();
          //DB::enableQueryLog();
          
          $BuyerPurchaseData = DB::SELECT("SELECT tr_code,order_rate,sam,job_status_id,order_type,ledger_master.ac_short_name as customerName,main_style_master.mainstyle_name, ifnull((total_qty),0) as total_order_qty FROM buyer_purchse_order_master 
                                INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                                INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                                WHERE og_id !=4 AND job_status_id != 5");
          
          //DB::select('call GetAllDataForOCR()');
          
        //   $BuyerPurchaseData = DB::select("select ifnull((total_qty),0) as total_order_qty,buyer_purchse_order_master.*,
        //     (select ifnull((washing_inhouse_master.total_qty * dbk_value),0) FROM sales_order_costing_master
        //             INNER JOIN  washing_inhouse_master ON  washing_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code 
        //             where sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code  GROUP BY washing_inhouse_master.sales_order_no) as dbk_value,
        //     (select ifnull((embroidery_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_costing_master.sales_order_no) as embroidery_value,
        //     (select ifnull((printing_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_costing_master.sales_order_no) as printing_value,
        //     (select ifnull(sum(testing_qty),0) FROM ocr_mater where sales_order_no = buyer_purchse_order_master.tr_code) as testing_ocr_cost,
        //     (select ifnull(sum(transport_qty),0) FROM ocr_mater where sales_order_no = buyer_purchse_order_master.tr_code) as transport_ocr_cost,
        //     (select ifnull((finance_cost_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as finance_cost_value,
        //     (select ifnull(sum(carton_packing_inhouse_detail.size_qty_total * agent_commision_value),0) FROM sales_order_costing_master INNER JOIN carton_packing_inhouse_detail ON carton_packing_inhouse_detail.sales_order_no = buyer_purchse_order_master.tr_code where sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code) as commision,
        //     (select ifnull(sum(carton_packing_inhouse_detail.size_qty_total * ixd_value),0) FROM sales_order_costing_master INNER JOIN carton_packing_inhouse_detail ON carton_packing_inhouse_detail.sales_order_no = buyer_purchse_order_master.tr_code where sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code) as ixd,
        //     (select ifnull(sum(size_qty_total),0) FROM cut_panel_grn_detail where sales_order_no = buyer_purchse_order_master.tr_code) as cut_qty, 
        //     (select ifnull(sum(size_qty_total),0) from carton_packing_inhouse_detail where sales_order_no = buyer_purchse_order_master.tr_code) as ship_qty,
        //     (select ifnull(sum(amount),0) from sale_transaction_detail where sales_order_no = buyer_purchse_order_master.tr_code) as sales_value,
        //     (select ifnull(sum(fabric_outward_master.total_meter),0) from fabric_outward_master 
        //     INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code
        //     WHERE vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issue_value,
            
        //     (select sum(meter) from inward_details
        //                 where inward_details.item_code=bom_fabric_details.item_code and po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE  purchaseorder_detail.sales_order_no=buyer_purchse_order_master.tr_code)) as GRNQty,
        //                 ifnull((SELECT (LENGTH(purchaseorder_detail.bom_code) - LENGTH(REPLACE(purchaseorder_detail.bom_code, ',', '')) + 1) FROM `purchaseorder_detail` WHERE
        //                 purchaseorder_detail.sales_order_no= buyer_purchse_order_master.tr_code  limit 0,1),1) as order_count,
              
        //     ledger_master.ac_short_name as customerName,main_style_master.mainstyle_name  
        //     FROM buyer_purchse_order_master 
        //     LEFT JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code  
        //     INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id                     
        //     INNER JOIN bom_fabric_details ON bom_fabric_details.sales_order_no =  buyer_purchse_order_master.tr_code 
        //     WHERE og_id !=4 AND job_status_id != 5 GROUP BY tr_code");
            
            //dd(DB::getQueryLog());
            
            $fabricOutwardStock = 0;
            $remainStock = 0;
            $avilable_stock = 0;
            $fabricAllocated_value = 0;
            $fabricOutwardValue = 0;
            $sales_value = 0;
            
             foreach($BuyerPurchaseData as $row)
             {
                $salesCostingData = DB::SELECT("SELECT ifnull((embroidery_value),0) as embroidery_value,ifnull((printing_value),0) as printing_value,ifnull(sum(dbk_value),0) as dbk_value,
                                    ifnull(sum(agent_commision_value),0) as agent_commision_value,ifnull(sum(ixd_value),0) as ixd_value,ifnull((finance_cost_value),0) as finance_cost_value  
                                    FROM sales_order_costing_master WHERE sales_order_no='".$row->tr_code."'"); 
                
                $cartonData = DB::SELECT("select ifnull(sum(size_qty_total),0) as ship_qty from carton_packing_inhouse_detail where sales_order_no ='".$row->tr_code."'"); 
                
                $cutPanelGRNData = DB::SELECT("select ifnull(sum(size_qty_total),0) as cut_qty FROM cut_panel_grn_detail where sales_order_no ='".$row->tr_code."'"); 
                
                $salesData = DB::SELECT("select ifnull(sum(amount),0) as sales_value from sale_transaction_detail where sales_order_no ='".$row->tr_code."'"); 
                
                $outwardData = DB::SELECT("select ifnull(sum(fabric_outward_master.total_meter),0) as fabric_issue_value from fabric_outward_master 
                             INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code
                             WHERE vendor_purchase_order_master.sales_order_no='".$row->tr_code."'"); 
                
                $washingData = DB::SELECT("select ifnull(sum(total_qty),0) as washing from washing_inhouse_master where sales_order_no ='".$row->tr_code."'"); 
                
                $ocrData = DB::SELECT("select ifnull(sum(testing_qty),0) as testing_qty,ifnull(sum(transport_qty),0) as transport_qty from ocr_mater where sales_order_no ='".$row->tr_code."'"); 
                
                $AllocatedStockData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code
                    FROM stock_association_for_fabric as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
                    WHERE  sales_order_no= '".$row->tr_code."' GROUP BY sta.bom_code,sta.po_code,sta.item_code");
                    
                foreach($AllocatedStockData as $fabrics)
                {
                       $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$fabrics->po_code."' AND bom_code='".$fabrics->bom_code."' 
                                    AND item_code='".$fabrics->item_code."' AND sales_order_no='".$fabrics->sales_order_no."' AND class_id=2 AND cat_id=1 AND tr_type=1");
                       
                       $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$fabrics->po_code."' 
                                    AND bom_code='".$fabrics->bom_code."'  AND item_code='".$fabrics->item_code."' AND sales_order_no='".$fabrics->sales_order_no."' AND tr_type=1");
             
                       $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                       $avalibleData = DB::SELECT("SELECT  ifnull(sum(qty),0) as other_allocated_qty FROM stock_association_for_fabric WHERE po_code='".$fabrics->po_code."' 
                                    AND item_code='".$fabrics->item_code."' AND sales_order_no!='".$fabrics->sales_order_no."' AND tr_type = 1"); 
                       
                       $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter),0) as outward_qty FROM fabric_outward_details
                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                                INNER JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                                WHERE  fabric_outward_details.item_code='".$fabrics->item_code."' and fabric_checking_details.po_code='".$fabrics->po_code."' 
                                AND vendor_purchase_order_master.sales_order_no='".$fabrics->sales_order_no."' GROUP BY fabric_outward_details.item_code"); 
                       
                       $otherAvaliableStock = isset($avalibleData[0]->other_allocated_qty) ? $avalibleData[0]->other_allocated_qty : 0;
                       $fabricOutwardStock += isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                       $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                       
                       
                        $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$fabrics->po_code."' AND bom_code='".$fabrics->bom_code."' AND item_code='".$fabrics->item_code."' AND sales_order_no='".$fabrics->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                        $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                    
                       
                       $remainStock += $allocated_qty - $eachAvaliableQty;
                       $inwardData = DB::table('purchaseorder_detail')->select('item_rate')->where('item_code',"=",$fabrics->item_code)->where('pur_code',"=",$fabrics->po_code)->first(); 
                       
                       $inward_rate = isset($inwardData->item_rate) ? $inwardData->item_rate : 0;
                       $avilable_stock += $remainStock - $fabricOutwardStock;
                       
                       $fabricAllocated_value = $remainStock * $inward_rate;
                       $fabricOutwardValue = $fabricOutwardStock * $inward_rate;
                       
                }

                $TrimsAllocatedStockData = DB::select("SELECT purchaseorder_detail.item_rate,dump_trims_stock_association.bom_code,dump_trims_stock_association.po_code,dump_trims_stock_association.item_code,dump_trims_stock_association.sales_order_no,sum(allocated_qty) as allocated_qty,sum(totalAssoc) as totalAssoc,sum(otherAvaliableStock) as otherAvaliableStock,sum(trimOutwardStock) as trimOutwardStock,sum(eachAvaliableQty) as eachAvaliableQty  
                                                        FROM dump_trims_stock_association LEFT JOIN purchaseorder_detail ON purchaseorder_detail.pur_code = dump_trims_stock_association.po_code 
                                                        AND purchaseorder_detail.item_code = dump_trims_stock_association.item_code AND purchaseorder_detail.sales_order_no = dump_trims_stock_association.sales_order_no 
                                                        WHERE  dump_trims_stock_association.sales_order_no= '".$row->tr_code."'  GROUP BY dump_trims_stock_association.item_code");
                
                $trimsOutwardStock = 0;
                $trimsRemainStock = 0;
                $trims_avilable_stock = 0;
                $trimsAllocated_value = 0;
                $trimsOutwardValue =  0;
                $trimsAllocated_qty = 0;
                $trimsOutward_qty = 0;

                foreach($TrimsAllocatedStockData as $trims)
                { 
                
                       $otherAvaliableStock1 = $trims->otherAvaliableStock;
                       $trimsOutwardStock += $trims->trimOutwardStock;
                       $totalAssoc =  $trims->totalAssoc;
                       
                       if($totalAssoc <= 0 && $trims->bom_code != "")
                       {
                            $trimsRemainStock += $trims->allocated_qty - $trims->eachAvaliableQty;
                       }
                       else
                       {
                            
                            $trimsRemainStock += $trims->allocated_qty - $otherAvaliableStock1;
                       }
                       //DB::enableQueryLog();
                    
                       $trimsAllocated_value += $trimsRemainStock * $trims->item_rate;
                       $trimsOutwardValue += $trimsOutwardStock * $trims->item_rate;
                       $trimsOutward_qty += $trimsOutwardStock;
                       $trimsAllocated_qty += $trimsRemainStock;
                       $trims_avilable_stock += ($trimsRemainStock - $trimsOutwardStock) * $trims->item_rate;
                       
                }
            
                

                
                $ship_qty = isset($cartonData[0]->ship_qty) ? $cartonData[0]->ship_qty : 0; 
                $cut_qty = isset($cutPanelGRNData[0]->cut_qty) ? $cutPanelGRNData[0]->cut_qty : 0; 
                $sales_value = isset($salesData[0]->sales_value) ? $salesData[0]->sales_value : 0; 
                $fabric_issue_value = isset($outwardData[0]->fabric_issue_value) ? $outwardData[0]->fabric_issue_value : 0; 
                $embroidery_value = isset($salesCostingData[0]->embroidery_value) ? $salesCostingData[0]->embroidery_value : 0; 
                $printing_value = isset($salesCostingData[0]->printing_value) ? $salesCostingData[0]->printing_value : 0; 
                $dbk_value = isset($salesCostingData[0]->dbk_value) ? $salesCostingData[0]->dbk_value : 0; 
                $agent_commision_value = isset($salesCostingData[0]->agent_commision_value) ? $salesCostingData[0]->agent_commision_value : 0; 
                $finance_cost_value = isset($salesCostingData[0]->finance_cost_value) ? $salesCostingData[0]->finance_cost_value : 0; 
                $ixd_value = isset($salesCostingData[0]->ixd_value) ? $salesCostingData[0]->ixd_value : 0; 
                $washing_value = isset($washingData[0]->washing) ? $washingData[0]->washing : 0; 
                $testing_qty = isset($ocrData[0]->testing_qty) ? $ocrData[0]->testing_qty : 0; 
                $transport_qty = isset($ocrData[0]->transport_qty) ? $ocrData[0]->transport_qty : 0;
                $discount = isset($salesCostingData[0]->discount) ? $salesCostingData[0]->discount : 0; 
                $totaldbk_value= $washing_value * $dbk_value;
                
                $fabricAllocated_qty = $remainStock; 
                $fabricOutward_qty = $fabricOutwardStock; 
         
                
                if($ship_qty > 0 && $cut_qty > 0)
                {
                   $cut_to_ship_qty = ($ship_qty/$cut_qty);
                }
                else
                {
                    $cut_to_ship_qty = 0;
                }
                
                if($ship_qty > 0 && $row->total_order_qty > 0)
                {
                   $order_to_ship_qty = ($ship_qty/$row->total_order_qty);
                }
                else
                {
                    $order_to_ship_qty = 0;
                }
                
                $FabricList = DB::select("select bom_fabric_details.bom_code, bom_fabric_details.item_code,
                (select sum(meter) from inward_details where inward_details.item_code=bom_fabric_details.item_code and
                po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                purchaseorder_detail.item_code=bom_fabric_details.item_code and purchaseorder_detail.sales_order_no='".$row->tr_code."')) as GRNQty,
                ifnull((SELECT (LENGTH(purchaseorder_detail.bom_code) - LENGTH(REPLACE(purchaseorder_detail.bom_code, ',', '')) + 1) FROM `purchaseorder_detail` WHERE
                FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                purchaseorder_detail.item_code=bom_fabric_details.item_code limit 0,1),1) as order_count,
                (select  item_rate from inward_details
                where
                inward_details.item_code=bom_fabric_details.item_code and
                po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                FIND_IN_SET(bom_fabric_details.bom_code,purchaseorder_detail.bom_code) and 
                purchaseorder_detail.item_code=bom_fabric_details.item_code) Limit 0,1) as GRNRate,
                bom_fabric_details.description from  bom_fabric_details 
                where bom_fabric_details.sales_order_no = '".$row->tr_code."'"); 
                
               $leftOverFabric = 0;
               foreach($FabricList as $fabric)
               {
               
                    $IssueMeter=DB::select("select sum(fabric_outward_details.meter) as  issue_meter from fabric_outward_details 
                        INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code
                        WHERE fabric_outward_details.item_code=".$fabric->item_code." AND vendor_purchase_order_master.sales_order_no='".$row->tr_code."'");
                    
                    $leftOverFabric += (($fabric->GRNQty/$fabric->order_count) - $IssueMeter[0]->issue_meter) * $fabric->GRNRate;
               }
   
                $rejectedData = DB::select("SELECT sum((select qcstitching_inhouse_size_reject_detail2.size_qty
                    from  qcstitching_inhouse_size_reject_detail2 where qcsti_code=qcstitching_inhouse_size_detail2.qcsti_code
                    and qcstitching_inhouse_size_reject_detail2.color_id=qcstitching_inhouse_size_detail2.color_id  
                    and  qcstitching_inhouse_size_reject_detail2.size_id = qcstitching_inhouse_size_detail2.size_id )) as reject_order_qty
                    FROM qcstitching_inhouse_size_detail2
                    WHERE qcstitching_inhouse_size_detail2.sales_order_no = '".$row->tr_code."'");
                    
                    if(count($rejectedData) > 0)
                    {
                        $reject_qty = $rejectedData[0]->reject_order_qty;
                    }
                    else
                    {
                       $reject_qty = 0;
                    }
                    
                    // $TOTALCMPOHIncludingFinanceCost =  $sales_value - $fabric_issue_value - ($totaldbk_value + $embroidery_value + $printing_value) - $testing_qty - $transport_qty - $agent_commision_value - $ixd_value + $leftOverFabric;
                    // if($TOTALCMPOHIncludingFinanceCost > 0 && $cut_qty > 0)
                    // { 
                    //     $CMPOHPERPC = ($TOTALCMPOHIncludingFinanceCost)/$cut_qty;
                    // }
                    // else
                    // {
                    //     $CMPOHPERPC = 0;
                    // }
                    // if($CMPOHPERPC > 0 && $row->sam > 0)
                    // { 
                    //     $CMPOHPERMINUTE = $CMPOHPERPC/$row->sam;
                    // }
                    // else
                    // {
                    //     $CMPOHPERMINUTE = 0;
                    // }
                    
                    if($reject_qty > 0 && $cut_qty > 0)
                    { 
                        $Rejection_per = ($reject_qty/$cut_qty) * 100;
                    }
                    else
                    {
                        $Rejection_per = 0;
                    }
                    
                 $sewingData = DB::select("SELECT (SELECT distinct item_rate FROM `purchaseorder_detail` WHERE
                                    bom_sewing_trims_details.sales_order_no=bom_sewing_trims_details.sales_order_no and 
                                    purchaseorder_detail.item_code=bom_sewing_trims_details.item_code Limit 0,1) as po_rate,
                                    item_code,sales_order_no
                                 FROM bom_sewing_trims_details WHERE sales_order_no='".$row->tr_code."'");    
                $sewing = 0;                
                foreach($sewingData as $sew)
                {
                   
                    $IssueMeter=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail 
                                    LEFT JOIN vendor_work_order_master ON  vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code
                                    WHERE trimsOutwardDetail.item_code=".$sew->item_code." AND vendor_work_order_master.sales_order_no='".$sew->sales_order_no."'");
                   
                    $SewingAssociationData=DB::select("SELECT (ifnull(sum(sta.qty),0) -
                            (SELECT ifnull(SUM(stock_association.qty),0)
                            FROM stock_association WHERE  stock_association.po_code= sta.po_code 
                            AND stock_association.item_code =  sta.item_code AND stock_association.tr_type = 2)) as sewingQty
                            FROM stock_association as sta 
                            LEFT JOIN item_master ON item_master.item_code = sta.item_code 
                            WHERE sta.sales_order_no='".$sew->sales_order_no."' AND sta.item_code='".$sew->item_code."' AND sta.tr_type = 1  GROUP BY sta.bom_code,sta.item_code");  
                   
                  $sew1 = isset($SewingAssociationData[0]->sewingQty) ? $SewingAssociationData[0]->sewingQty: 0;   
                  $sew2 = isset($IssueMeter[0]->issue_qty) ? $IssueMeter[0]->issue_qty: 0;       
             
                  $sewing += (($sew1 - $sew2) * $sew->po_rate);
                }
                
                  $packingData = DB::select("SELECT (select  item_rate from trimsInwardDetail    where
                                    trimsInwardDetail.item_code=bom_packing_trims_details.item_code and
                                    po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                                    FIND_IN_SET(bom_packing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                                    purchaseorder_detail.item_code=bom_packing_trims_details.item_code) Limit 0,1) as GRNRate,
                                    (select sum(item_qty) from trimsInwardDetail
                                    where
                                    trimsInwardDetail.item_code=bom_packing_trims_details.item_code and
                                    po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE
                                    FIND_IN_SET(bom_packing_trims_details.bom_code,purchaseorder_detail.bom_code) and 
                                    purchaseorder_detail.item_code=bom_packing_trims_details.item_code)) as GRNQty,
                
                                    item_code,sales_order_no
                                 FROM bom_packing_trims_details WHERE sales_order_no='".$row->tr_code."'");
       
                $packing = 0;                
                foreach($packingData as $pck)
                {
                   
                    $IssueMeter1=DB::select("select sum(trimsOutwardDetail.item_qty) as  issue_qty from trimsOutwardDetail 
                                    INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code
                                    WHERE trimsOutwardDetail.item_code=".$pck->item_code." AND vendor_purchase_order_master.sales_order_no='".$pck->sales_order_no."'");
                   
                    
                  $pks1 = isset($IssueMeter1[0]->issue_qty) ? $IssueMeter1[0]->issue_qty: 0;       
             
                  $packing += (($pks1 - $pck->GRNQty) * $pck->GRNRate);
                }
                
                $overallLeftOver = $sewing - $packing;
                                          
                $packing_order_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as packing_order_qty from packing_inhouse_detail where
                     packing_inhouse_detail.sales_order_no = '".$row->tr_code."'");
                     
                $invoice_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as invoice_qty from carton_packing_inhouse_detail
                     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_detail.cpki_code
                     where carton_packing_inhouse_detail.sales_order_no = '".$row->tr_code."' and carton_packing_inhouse_master.endflag=1 ");
                     
                $kdpl_master_Data = DB::select("select * from kdpl_wise_set_percentage where sales_order_no = '".$row->tr_code."'");
                
                $sewingIssueData = DB::select("SELECT SUM(item_qty * item_rate) as sewing FROM  trimsOutwardDetail 
                                INNER JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = trimsOutwardDetail.vw_code
                                WHERE vendor_work_order_master.sales_order_no = '".$row->tr_code."'");
                                
                $packingIssueData = DB::select("SELECT SUM(item_qty * item_rate) as packing FROM  trimsOutwardDetail 
                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = trimsOutwardDetail.vpo_code
                                WHERE vendor_purchase_order_master.sales_order_no = '".$row->tr_code."'");
             
                $sewTrims = isset($sewingIssueData[0]->sewing) ? $sewingIssueData[0]->sewing : 0; 
                $packTrims = isset($packingIssueData[0]->packing) ? $packingIssueData[0]->packing : 0; 
                
                $fabricIssueData = DB::select("SELECT SUM(meter * item_rate) as fabric FROM  fabric_outward_details 
                                INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code
                                WHERE vendor_purchase_order_master.sales_order_no = '".$row->tr_code."'");
                                
                $fabricTrims = isset($fabricIssueData[0]->fabric) ? $fabricIssueData[0]->fabric : 0;                 
            
                
                if($kdpl_master_Data != "")
                {
                
                    $leftFabricValue = isset($kdpl_master_Data[0]->leftover_fabric_value) ? $kdpl_master_Data[0]->leftover_fabric_value : 50;
                    if($leftFabricValue > 0) 
                    {
                        $leftover_fabric_value = $leftFabricValue/100;
                    }
                    else
                    {
                        $leftover_fabric_value = 0;  
                    }
                    
                    $lefttrimsvalue = isset($kdpl_master_Data[0]->leftover_trims_value) ? $kdpl_master_Data[0]->leftover_trims_value : 50; 
                    if($lefttrimsvalue > 0) 
                    {
                        $leftover_trims_value = $lefttrimsvalue/100;
                    }
                    else
                    {
                        $leftover_trims_value = 0;  
                    }
                    
                     
                    $leftpcsvalue = isset($kdpl_master_Data[0]->left_pcs_value) ? $kdpl_master_Data[0]->left_pcs_value : 70;
                    if($leftpcsvalue > 0) 
                    {
                        $left_pcs_value = $leftpcsvalue/100;
                    }
                    else
                    {
                        $left_pcs_value = 0;  
                    }
                     
                    $rejectionpcsvalue = isset($kdpl_master_Data[0]->rejection_pcs_value) ? $kdpl_master_Data[0]->rejection_pcs_value : 70;
                    if($rejectionpcsvalue > 0) 
                    {
                        $rejection_pcs_value = $rejectionpcsvalue/100;
                    }
                    else
                    {
                        $rejection_pcs_value = 0;  
                    }
                }
                else
                {
                    $leftover_fabric_value = 0;  
                    $leftover_trims_value = 0;
                    $left_pcs_value = 0;
                    $rejection_pcs_value = 0;
                    $rejectionpcsvalue = 70;
                    $leftpcsvalue = 70;
                    $leftFabricValue = 50;
                    $lefttrimsvalue = 50;
                }
                $pass_order_qtyData = DB::select("select ifnull(sum(size_qty_total),0) as pass_order_qty from qcstitching_inhouse_detail where
                             qcstitching_inhouse_detail.sales_order_no = '".$row->tr_code."'"); 
                 $passQty = isset($pass_order_qtyData[0]->pass_order_qty) ? $pass_order_qtyData[0]->pass_order_qty : 0;    
                 
                 
                $trimsAllocated_qty = isset($TrimsData[0]->trimsAllocated_qty) ? $TrimsData[0]->trimsAllocated_qty : 0;
                $trimsOutward_qty = isset($TrimsData[0]->trimsOutward_qty) ? $TrimsData[0]->trimsOutward_qty : 0;
                $trimsAllocated_value = isset($TrimsData[0]->trimsAllocated_value) ? $TrimsData[0]->trimsAllocated_value : 0;
                $trimsOutwardValue = isset($TrimsData[0]->trimsOutwardValue) ? $TrimsData[0]->trimsOutwardValue : 0;
                 
                $left_over_fabric_value_per = ($fabricAllocated_value - $fabricOutwardValue) * ($leftFabricValue/100);
                $left_over_trims_value_per = (($trimsAllocated_value-$trimsOutwardValue) * ($leftover_trims_value/100));
                  
                $TOTALCMPOHIncludingFinanceCost =  ($sales_value - $fabricAllocated_value - $trimsAllocated_value - $discount - $agent_commision_value - $ixd_value - $transport_qty - $testing_qty 
                                                    - $printing_value - $embroidery_value - $dbk_value)
                                                    + $left_over_fabric_value_per + $left_over_trims_value_per + $left_pcs_value + $rejection_pcs_value;
                
                
                
                if($TOTALCMPOHIncludingFinanceCost > 0 && $cut_qty)
                {
                    $cmpoh_per_pc = $TOTALCMPOHIncludingFinanceCost/$cut_qty; 
                }
                else
                {
                    $cmpoh_per_pc = 0;
                }
                
                if($cmpoh_per_pc > 0 && $row->sam)
                {
                    $cmpoh_per_minutes = $cmpoh_per_pc/$row->sam;
                }
                else
                {
                    $cmpoh_per_minutes = 0;
                }
            
            
            
            
                 DB::table('dump_ocr_summary_report')->insert(
                    array(
                      'customer' => $row->customerName,
                      'sales_order_no' => $row->tr_code,
                      'style' => $row->mainstyle_name,
                      'fob_value' => $row->order_rate,
                      'sam' => $row->sam,
                      'order_qty' => $row->total_order_qty,
                      'cut_qty' => $cut_qty,
                      'ship_qty' => $ship_qty,
                      'cut_to_ship_ratio_per' => $cut_to_ship_qty*100,
                      'order_to_ship_ratio_per' => $order_to_ship_qty*100,
                      'fabric_issue_value' =>  $fabricTrims,
                      'trims_issue_value' => $sewTrims+$packTrims, 
                      'dbk_value' =>  $totaldbk_value,
                      'embroidery_value' =>  $embroidery_value,
                      'printing_value' =>  $printing_value, 
                      'testing_cost' => $testing_qty,
                      'transport_cost' => $transport_qty, 
                      'commision_cost' => $agent_commision_value * $ship_qty,
                      'ixd_cost' =>  $ixd_value * $ship_qty,
                      'discount' =>  0,
                      'sales_value' => $sales_value,
                      'left_over_fabric_value_per' => $left_over_fabric_value_per,
                      'left_over_trims_value_per' =>  $left_over_trims_value_per,
                      'left_pcs_value' => ((($passQty - $invoice_qtyData[0]->invoice_qty) * $row->order_rate) * ($leftpcsvalue/100) )."(".$leftpcsvalue.")",
                      'left_pcs_per' => $leftpcsvalue,
                      'rejection_pcs_value' => ($row->order_rate*$reject_qty) * ($rejection_pcs_value)."(".$rejectionpcsvalue.")",
                      'rejection_pcs_per' => $rejectionpcsvalue,
                      'total_cmpoh_including_finance_cost' => $TOTALCMPOHIncludingFinanceCost,
                      'finance_cost_per_costing' => $finance_cost_value * $cut_qty,
                      'total_cmpoh_excluding_finance_cost' => $TOTALCMPOHIncludingFinanceCost-$finance_cost_value,
                      'cmpoh_per_pc' => $cmpoh_per_pc,
                      'cmpoh_per_minutes' => $cmpoh_per_minutes,
                      'loose_pcs' => ($passQty - $invoice_qtyData[0]->invoice_qty),
                      'rejection_pcs' => $reject_qty,
                      'rejection_per' => $Rejection_per,
                      'job_status_id' => $row->job_status_id,
                      'order_type' => $row->order_type,
                      'fabricAllocated_qty' => $fabricAllocated_qty,
                      'fabricAllocated_value' => $fabricAllocated_value,
                      'fabricOutward_qty' => $fabricOutward_qty,
                      'fabricOutwardValue' => $fabricOutwardValue,
                      'fabricAvalible_qty' => $fabricAllocated_qty - $fabricOutward_qty,
                      'fabricAvalible_value' => $fabricAllocated_value - $fabricOutwardValue,
                      
                      'trimsAllocated_qty' => $trimsAllocated_qty,
                      'trimsAllocated_value' => $trimsAllocated_value,
                      'trimsOutward_qty' => $trimsOutward_qty,
                      'trimsOutwardValue' => $trimsOutwardValue,
                      'trimsAvalible_qty' => $trimsAllocated_qty - $trimsOutward_qty,
                      'trimsAvalible_value' => $trimsAllocated_value - $trimsOutwardValue,
                       
                    )
                );
             }

        
    }
    public function loadOCRReport(Request $request)
    { 
          
        $sales_order_no = $request->sales_order_no;
        $job_status_id = $request->job_status_id;
        $orderTypeId = $request->orderTypeId;
        $Ac_code = $request->Ac_code;
        
        if($job_status_id > 0)
        {
            $status_filter = " AND job_status_id=".$job_status_id;
        }
        else
        {
            $status_filter = "";
        }
        
        if($orderTypeId > 0)
        {
            $orderType_filter = " AND order_type=".$orderTypeId;
        }
        else
        {
            $orderType_filter = "";
        }
        
        if($sales_order_no != "")
        {
            $sales_order_no_filter = " AND sales_order_no='".$sales_order_no."'";
        }
        else
        {
            $sales_order_no_filter = "";
        }
        
        $html = "";
        $srno = 1;
       //DB::enableQueryLog();
        $BuyerPurchaseData = DB::select("SELECT DISTINCT * FROM dump_ocr_summary_report WHERE 1 ".$status_filter." ".$orderType_filter." ".$sales_order_no_filter." GROUP BY sales_order_no");
       // dd(DB::getQueryLog());
        foreach($BuyerPurchaseData as $row)
        {
            // $TrimsData = DB::SELECT("SELECT SUM(dump_trims_stock_association.allocated_qty) as trimsAllocated_qty,SUM(dump_trims_stock_association.trimOutwardStock) as trimsOutward_qty,SUM(dump_trims_stock_association.allocated_qty * (select DISTINCT purchaseorder_detail.item_rate FROM purchaseorder_detail 
            //                 WHERE item_code=dump_trims_stock_association.item_code AND pur_code = dump_trims_stock_association.po_code GROUP BY dump_trims_stock_association.sales_order_no)) as trimsAllocated_value,
            //                 SUM(dump_trims_stock_association.trimOutwardStock * (select DISTINCT purchaseorder_detail.item_rate FROM purchaseorder_detail 
            //                 WHERE item_code=dump_trims_stock_association.item_code AND pur_code = dump_trims_stock_association.po_code GROUP BY dump_trims_stock_association.sales_order_no)) as trimsOutwardValue  FROM dump_trims_stock_association  
            //                     WHERE dump_trims_stock_association.sales_order_no='".$row->sales_order_no."' GROUP BY dump_trims_stock_association.sales_order_no"); 
           
            // // $TrimsData1 = DB::SELECT("SELECT SUM(dump_trims_stock_association.allocated_qty * (select DISTINCT purchaseorder_detail.item_rate FROM purchaseorder_detail 
            // //                 WHERE item_code=dump_trims_stock_association.item_code AND pur_code = dump_trims_stock_association.po_code GROUP BY dump_trims_stock_association.sales_order_no)) as trimsAllocated_value,
            // //                 SUM(dump_trims_stock_association.trimOutwardStock * (select DISTINCT purchaseorder_detail.item_rate FROM purchaseorder_detail 
            // //                 WHERE item_code=dump_trims_stock_association.item_code AND pur_code = dump_trims_stock_association.po_code GROUP BY dump_trims_stock_association.sales_order_no)) as trimsOutwardValue
                            
            // //                 FROM dump_trims_stock_association WHERE dump_trims_stock_association.sales_order_no='".$row->sales_order_no."'");
                                
            // $trimsAllocated_qty = isset($TrimsData[0]->trimsAllocated_qty) ? $TrimsData[0]->trimsAllocated_qty : 0;
            // $trimsOutward_qty = isset($TrimsData[0]->trimsOutward_qty) ? $TrimsData[0]->trimsOutward_qty : 0;
            // $trimsAllocated_value = isset($TrimsData[0]->trimsAllocated_value) ? $TrimsData[0]->trimsAllocated_value : 0;
            // $trimsOutwardValue = isset($TrimsData[0]->trimsOutwardValue) ? $TrimsData[0]->trimsOutwardValue : 0;
             
            // $left_over_fabric_value_per = $row->fabricAvalible_value * ($row->left_over_fabric_value_per/100);
            // $left_over_trims_value_per = (($trimsAllocated_value-$trimsOutwardValue) * ($row->left_over_trims_value_per/100));
              
            // $TOTALCMPOHIncludingFinanceCost =  ($row->sales_value - $row->fabricAllocated_value - $trimsAllocated_value - $row->discount - $row->commision_cost - $row->ixd_cost - $row->transport_cost - $row->testing_cost - $row->printing_value - $row->embroidery_value - $row->dbk_value)
            //                                     + $left_over_fabric_value_per + $left_over_trims_value_per + explode("(",$row->left_pcs_value)[0] + explode("(",$row->rejection_pcs_value)[0];
            
            
            // if($TOTALCMPOHIncludingFinanceCost > 0 && $row->cut_qty)
            // {
            //     $cmpoh_per_pc = $TOTALCMPOHIncludingFinanceCost/$row->cut_qty; 
            // }
            // else
            // {
            //     $cmpoh_per_pc = 0;
            // }
            
            // if($cmpoh_per_pc > 0 && $row->sam)
            // {
            //     $cmpoh_per_minutes = $cmpoh_per_pc/$row->sam;
            // }
            // else
            // {
            //     $cmpoh_per_minutes = 0;
            // }
            
            
            
            $html .='<tr nowrap class="tr">
                        <td>'.$srno.'</td>
                        <td>'.$row->customer.'</td>
                        <td>'.$row->sales_order_no.'</td>
                        <td>'.$row->style.'</td>
                        <td class="text-right">'.sprintf('%0.2f', $row->fob_value).'</td>
                        <td class="text-right">'.sprintf('%0.2f', $row->sam).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->order_qty).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->cut_qty).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->ship_qty).'</td>
                        <td nowrap class="text-right">'.number_format($row->cut_to_ship_ratio_per, 2, '.', ',').'</td>
                        <td nowrap class="text-right">'.number_format($row->order_to_ship_ratio_per, 2, '.', ',').'</td> 
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->fabricAllocated_qty,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->fabricAllocated_value,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->fabricOutward_qty,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->fabricOutwardValue,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->fabricAvalible_qty,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->fabricAvalible_value,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->trimsAllocated_qty,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->trimsAllocated_value,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->trimsOutward_qty,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->trimsOutwardValue,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->trimsAvalible_qty,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->trimsAvalible_value,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->dbk_value,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->embroidery_value,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->printing_value,2)).'</td> 
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->testing_cost,2)).'</td>
                        <td>'.money_format('%!.0n',round($row->transport_cost,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->commision_cost,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',round($row->ixd_cost,2)).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->discount).'</td>
                        <td  class="text-right">'.money_format('%!.0n',round($row->sales_value,2)).'</td>
                        <td nowrap class="text-right">'.sprintf('%0.2f', $row->left_over_fabric_value_per).' %</td>
                        <td nowrap class="text-right">'.sprintf('%0.2f', $row->left_over_trims_value_per).'</td>
                        <td nowrap class="text-right">'.$row->left_pcs_value.'</td>
                        <td nowrap class="text-right">'.$row->rejection_pcs_value.'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->total_cmpoh_including_finance_cost).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->finance_cost_per_costing).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->total_cmpoh_excluding_finance_cost).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->cmpoh_per_pc).'</td>
                        <td nowrap class="text-right">'.round($row->cmpoh_per_minutes,2).'</td> 
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->loose_pcs).'</td> 
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->rejection_pcs).'</td>
                        <td nowrap class="text-right">'.money_format('%!.0n',$row->rejection_per).'</td>
                    </tr>';
                    
                    $srno++;
                       
        }
        
         return response()->json(['html' => $html]);
             
         
    }
    public function rptOCRSummary(Request $request)
    {    
    
        $buyerPurchaseOrderList = DB::SELECT("SELECT buyer_purchse_order_master.tr_code,buyer_purchse_order_master.order_rate,buyer_purchse_order_master.sam,
                                buyer_purchse_order_master.job_status_id,order_type,ledger_master.ac_short_name as customerName,main_style_master.mainstyle_name, 
                                ifnull((buyer_purchse_order_master.total_qty),0) as total_order_qty, 
                                (select ifnull(sum(carton_packing_inhouse_detail.size_qty_total),0)  FROM carton_packing_inhouse_detail WHERE sales_order_no = buyer_purchse_order_master.tr_code) as ship_qty,
                                (select ifnull(sum(cut_panel_grn_detail.size_qty_total),0)  FROM cut_panel_grn_detail WHERE sales_order_no = buyer_purchse_order_master.tr_code) as cut_qty,
                                (select ifnull(sum(sale_transaction_detail.amount),0)  FROM sale_transaction_detail WHERE sales_order_no = buyer_purchse_order_master.tr_code) as sales_value,
                                ifnull((embroidery_value),0) as embroidery_value,ifnull((printing_value),0) as printing_value,
                                ifnull(sum(dbk_value),0) as dbk_value, ifnull(sum(agent_commision_value),0) as agent_commision_value,ifnull(sum(ixd_value),0) as ixd_value,ifnull((finance_cost_value),0) as finance_cost_value,
                                ifnull(sum(washing_inhouse_master.total_qty),0) as washing, ifnull(sum(ocr_mater.testing_qty),0) as testing_qty,ifnull(sum(ocr_mater.transport_qty),0) as transport_qty
                                FROM buyer_purchse_order_master 
                                INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                                INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id  
                                LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code 
                                LEFT JOIN washing_inhouse_master ON washing_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code 
                                LEFT JOIN ocr_mater ON ocr_mater.sales_order_no = buyer_purchse_order_master.tr_code  
                                WHERE og_id !=4 AND job_status_id = 1 GROUP BY buyer_purchse_order_master.tr_code"); 
            
        $StockAssociationData = DB::table('stock_association_for_fabric')
            ->select('po_code', 'item_code', DB::raw('SUM(qty) as qty'), 'sales_order_no', 'tr_type') // example aggregate
            ->groupBy('po_code', 'item_code', 'tr_type')
            ->get()
            ->toArray();


        //$FabricOutwardData = DB::table('fabric_outward_details')->join('vendor_purchase_order_master','vendor_purchase_order_master.vpo_code','=','fabric_outward_details.vpo_code') 
         //                           ->join('fabric_checking_details','fabric_checking_details.track_code','=','fabric_outward_details.track_code')
          //                          ->get()->toArray();
        $FabricOutwardData = DB::table('fabric_outward_details')
            ->join('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'fabric_outward_details.vpo_code')
            ->join('purchaseorder_detail', function ($join) {
                $join->on('purchaseorder_detail.pur_code', '=', 'fabric_outward_details.po_code')
                     ->on('purchaseorder_detail.item_code', '=', 'fabric_outward_details.item_code');
            })
            ->select(
                'fabric_outward_details.po_code',
                'fabric_outward_details.item_code',
                DB::raw('SUM(fabric_outward_details.meter) as meter'),
                DB::raw('SUM(fabric_outward_details.meter * purchaseorder_detail.item_rate) as fabric_outward_value'),
                'vendor_purchase_order_master.sales_order_no'
            )
            ->groupBy('vendor_purchase_order_master.sales_order_no')
            ->get()
            ->toArray();

            
        $GroupedData = [];
        foreach ($StockAssociationData as $row)
        {
            $GroupedData[$row->sales_order_no][] = $row;
        }   
                          
        $OutwardGroupedData = [];
        foreach ($FabricOutwardData as $row)
        {
            $OutwardGroupedData[$row->sales_order_no][] = $row;
        }   
        
        // $TrimsAllocatedStockData = DB::table('stock_association')
        //     ->join('dump_trims_stock_association', function ($join) {
        //         $join->on('stock_association.po_code', '=', 'dump_trims_stock_association.po_code')
        //              ->on('stock_association.item_code', '=', 'dump_trims_stock_association.item_code')
        //              ->on('stock_association.sales_order_no', '=', 'dump_trims_stock_association.sales_order_no');
        //     })
        //     ->select(
        //         'dump_trims_stock_association.*',
        //         'stock_association.po_code',
        //         'stock_association.item_code',
        //         'stock_association.sales_order_no',
        //         DB::raw('SUM(stock_association.qty) as qty')
        //     )
        //     ->get()
        //     ->toArray();

       
        // $TrimsAllocatedStockData = DB::table('dump_trims_stock_association')->select("purchaseorder_detail.item_rate", "dump_trims_stock_association.*")
        //     ->join('purchaseorder_detail', function ($join) {
        //         $join->on('purchaseorder_detail.pur_code', '=', 'dump_trims_stock_association.po_code')
        //              ->on('purchaseorder_detail.item_code', '=', 'dump_trims_stock_association.item_code')
        //              ->on('purchaseorder_detail.sales_order_no', '=', 'dump_trims_stock_association.sales_order_no');
        //     })
        //     ->get()
        //     ->toArray();
        
          $trimsStockAssociation1 = DB::table('stock_association')
            ->select('tr_code','po_code', 'item_code', DB::raw('IFNULL(SUM(CASE WHEN stock_association.tr_type = 1 THEN stock_association.qty ELSE 0 END), 0) AS qty'),
                    DB::raw('IFNULL(SUM(CASE WHEN stock_association.tr_type = 2 AND stock_association.tr_code IS NULL THEN stock_association.qty ELSE 0 END), 0) AS each_qty'),'sales_order_no', 'tr_type') 
            // ->where('stock_association.sales_order_no', '=', 'KDPL-1739')
            // ->where('item_code', '=', '7286')
            ->groupBy('item_code','po_code','sales_order_no') 
            ->get()
            ->toArray();
        
      
            $purchaseOrderData = DB::table('purchaseorder_detail')
            ->select('pur_code', 'item_code', 'item_rate', 'sales_order_no')  
            ->groupBy('item_code','pur_code')
            ->get()
            ->toArray();
           
            $poMap = [];
            foreach ($purchaseOrderData as $po) {
                $key = $po->pur_code . '|' . $po->item_code;
                // Allow multiple rates per PO-item-SO if needed
                $poMap[$key][] = $po->item_rate;
            }
            
            $combinedData = [];
            $TrimGroupedData = [];
            
            foreach ($trimsStockAssociation1 as $stock) {
                $key = $stock->po_code . '|' . $stock->item_code;
            
                if (!isset($poMap[$key])) {
                    // optionally collect unmatched stock
                    continue;
                }
            
                // If multiple rates exist, take the first or compute average
                $rate = floatval($poMap[$key][0]);
                $qty = $stock->qty- $stock->each_qty; 
            
                $value = $qty * $rate;
            
                $groupKey = $stock->sales_order_no . '|' . $stock->po_code . '|' . $stock->item_code;
            
                if (!isset($combinedData[$groupKey])) {
                    $combinedData[$groupKey] = [
                        'sales_order_no' => $stock->sales_order_no,
                        'po_code'        => $stock->po_code,
                        'item_code'      => $stock->item_code,
                        'qty'            => 0,
                        'value'          => 0,
                    ];
                }
            
                $combinedData[$groupKey]['qty'] += $qty;
                $combinedData[$groupKey]['value'] += $value;
            
                // Totals per sales order
                $soKey = $stock->sales_order_no;
                if (!isset($TrimGroupedData[$soKey])) {
                    $TrimGroupedData[$soKey] = [
                        'total_qty'   => 0,
                        'total_value' => 0,
                    ];
                }
            
                $TrimGroupedData[$soKey]['total_qty']   += $qty;
                $TrimGroupedData[$soKey]['total_value'] += $value;
            }
            
            // Re-index combined data
            $combinedData = array_values($combinedData);
            
            // Round totals
            foreach ($TrimGroupedData as &$so) {
                $so['total_qty'] = round($so['total_qty'], 2);
                $so['total_value'] = round($so['total_value'], 2);
            }

        //   echo '<pre>'; print_r($TrimGroupedData);exit;  

            // $trimOutwardData1 = DB::table('')
            // ->select('po_code', 'item_code', DB::raw('IFNULL(SUM(item_qty), 0) AS outward_qty'))
            // ->join('vendor_work_order_master', 'vendor_work_order_master.vw_code','=','trimsOutwardDetail.vw_code')
            // ->groupBy('item_code','po_code')
            // ->get()
            // ->toArray(); 
            
            // $trimOutwardData2 = DB::table('')
            // ->select('po_code', 'item_code', DB::raw('IFNULL(SUM(item_qty), 0) AS outward_qty'))
            // ->join('vendor_purchase_order_master', 'vendor_purchase_order_master.vw_code','=','trimsOutwardDetail.vw_code')
            // ->groupBy('item_code','po_code')
            // ->get()
            // ->toArray(); 
            // DB::enableQueryLog(); 
            $TrimOutward = DB::query()
                ->fromSub(function ($query) {
                    $query->from('trimsOutwardDetail')
                        ->select('sales_order_no', 'po_code', 'item_code', DB::raw('SUM(item_qty) as outward_qty'))
                        ->join('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=', 'trimsOutwardDetail.vw_code')
                        // ->where('vendor_work_order_master.sales_order_no', '=', 'KDPL-1739')
                        ->groupBy('sales_order_no', 'po_code', 'item_code')
            
                        ->unionAll(
                            DB::table('trimsOutwardDetail')
                                ->select('sales_order_no', 'po_code', 'item_code', DB::raw('SUM(item_qty) as outward_qty'))
                                ->join('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'trimsOutwardDetail.vpo_code')
                                // ->where('vendor_purchase_order_master.sales_order_no', '=', 'KDPL-1739')
                                ->groupBy('sales_order_no', 'po_code', 'item_code')
                        );
                }, 'u')
                ->select('sales_order_no', 'po_code', 'item_code', DB::raw('SUM(outward_qty) as outward_qty'))
                ->groupBy('sales_order_no', 'po_code', 'item_code')
                ->get();

            // dd(DB::getQueryLog());

            $combinedData1 = [];
            $TrimOutwardGroupedData = [];
            $missingKeys = []; // optional: collect missing mappings
            
            foreach ($TrimOutward as $out) {
                $key = $out->po_code . '|' . $out->item_code;
            
                // Safe rate lookup
                if (!isset($poMap[$key])) {
                    $rate = 0; // default when no mapping found
                    $missingKeys[] = $key; // log missing key for debugging
                } else {
                    $rate = floatval($poMap[$key][0]);
                }
            
                $qty1  = $out->outward_qty;
                $value = $qty1 * $rate;
            
                // Unique grouping key
                $groupKey = $out->sales_order_no . '|' . $out->po_code . '|' . $out->item_code;
            
                if (!isset($combinedData1[$groupKey])) {
                    $combinedData1[$groupKey] = [
                        'sales_order_no' => $out->sales_order_no,
                        'po_code'        => $out->po_code,
                        'item_code'      => $out->item_code,
                        'qty'            => 0,
                        'value'          => 0,
                    ];
                }
            
                $combinedData1[$groupKey]['qty']   += $qty1;
                $combinedData1[$groupKey]['value'] += $value;
            
                // Totals per sales order
                $soKey = $out->sales_order_no;
                if (!isset($TrimOutwardGroupedData[$soKey])) {
                    $TrimOutwardGroupedData[$soKey] = [
                        'total_qty'   => 0,
                        'total_value' => 0,
                    ];
                }
            
                $TrimOutwardGroupedData[$soKey]['total_qty']   += $qty1;
                $TrimOutwardGroupedData[$soKey]['total_value'] += $value;
            }
            
            // Re-index combined data
            $combinedData2 = array_values($combinedData1);
            
            // Round totals
            foreach ($TrimOutwardGroupedData as &$so) {
                $so['total_qty']   = round($so['total_qty'], 2);
                $so['total_value'] = round($so['total_value'], 2);
            }
  
        // $TrimsAllocatedStockData = DB::select("SELECT 
        //                                 a.sales_order_no,
        //                                 a.allocated_qty,
        //                                 a.allocated_value,
        //                                 b.outward_qty,
        //                                 b.outward_value
        //                             FROM (
        //                                 -- Allocated Qty & Value
        //                                 SELECT 
        //                                     sa.sales_order_no,
                                    
        //                                     -- Allocated Qty
        //                                     SUM(
        //                                         CASE 
        //                                             WHEN sa.tr_type = 1 THEN sa.qty
        //                                             WHEN sa.tr_type = 2 AND sa.tr_code IS NULL THEN -sa.qty
        //                                             ELSE 0
        //                                         END
        //                                     ) AS allocated_qty,
                                    
        //                                     -- Allocated Value (qty * item_rate)
        //                                     SUM(
        //                                         CASE 
        //                                             WHEN sa.tr_type = 1 THEN sa.qty * IFNULL((
        //                                                 SELECT pod.item_rate 
        //                                                 FROM purchaseorder_detail pod
        //                                                 WHERE pod.pur_code = sa.po_code 
        //                                                   AND pod.item_code = sa.item_code
        //                                                 LIMIT 1
        //                                             ), 0)
        //                                             WHEN sa.tr_type = 2 AND sa.tr_code IS NULL THEN -sa.qty * IFNULL((
        //                                                 SELECT pod.item_rate 
        //                                                 FROM purchaseorder_detail pod
        //                                                 WHERE pod.pur_code = sa.po_code 
        //                                                   AND pod.item_code = sa.item_code
        //                                                 LIMIT 1
        //                                             ), 0)
        //                                             ELSE 0
        //                                         END
        //                                     ) AS allocated_value
        //                                 FROM stock_association sa
        //                                 WHERE 1
        //                                 GROUP BY sa.sales_order_no
        //                             ) a
        //                             LEFT JOIN (
        //                                 -- Outward Qty & Value
        //                                 SELECT 
        //                                     t.sales_order_no,
        //                                     SUM(t.outward_qty) AS outward_qty,
        //                                     SUM(
        //                                         t.outward_qty * IFNULL(
        //                                             (SELECT pod.item_rate 
        //                                              FROM purchaseorder_detail pod
        //                                              WHERE pod.pur_code = t.po_code
        //                                               AND pod.item_code = t.item_code
        //                                              LIMIT 1), 0)
        //                                     ) AS outward_value
        //                                 FROM (
        //                                     -- From Work Order
        //                                     SELECT 
        //                                         tod.item_code, 
        //                                         tod.po_code, 
        //                                         vwo.sales_order_no, 
        //                                         SUM(tod.item_qty) AS outward_qty
        //                                     FROM trimsOutwardDetail tod
        //                                     INNER JOIN vendor_work_order_master vwo
        //                                           ON vwo.vw_code = tod.vw_code
        //                                     GROUP BY tod.item_code, tod.po_code, vwo.sales_order_no
                                    
        //                                     UNION ALL
                                    
        //                                     -- From Purchase Order
        //                                     SELECT 
        //                                         tod.item_code, 
        //                                         tod.po_code, 
        //                                         vpo.sales_order_no, 
        //                                         SUM(tod.item_qty) AS outward_qty
        //                                     FROM trimsOutwardDetail tod
        //                                     INNER JOIN vendor_purchase_order_master vpo
        //                                           ON vpo.vpo_code = tod.vpo_code
        //                                     GROUP BY tod.item_code, tod.po_code, vpo.sales_order_no
        //                                 ) t
        //                                 WHERE 1
        //                                 GROUP BY t.sales_order_no
        //                             ) b
        //                             ON a.sales_order_no = b.sales_order_no");

        
        $kdpl_master_Data =  DB::table('kdpl_wise_set_percentage')->get()->toArray();
              
        $KDPLGroupedData = [];
        foreach ($kdpl_master_Data as $row)
        {
            $KDPLGroupedData[$row->sales_order_no][] = $row;
        }            
        
        
        $invoiceData = DB::table('carton_packing_inhouse_detail')->join('carton_packing_inhouse_master','carton_packing_inhouse_master.cpki_code','=','carton_packing_inhouse_detail.cpki_code') 
                                    ->WHERE('carton_packing_inhouse_master.endflag', '=', 1)
                                    ->get()->toArray();
         
        $PassData =  DB::table('qcstitching_inhouse_detail')->get()->toArray();
             
        $invoiceGroupedData = [];
        foreach ($invoiceData as $row)
        {
            $invoiceGroupedData[$row->sales_order_no][] = $row;
        }    
        
        $PassGroupedData = [];
        foreach ($PassData as $row)
        {
            $PassGroupedData[$row->sales_order_no][] = $row;
        } 
        
        
        $rejectedData = DB::table('qcstitching_inhouse_size_reject_detail2')->get()->toArray();
              
        $RejectGroupedData = [];
        foreach ($rejectedData as $row)
        {
            $RejectGroupedData[$row->sales_order_no][] = $row;
        }  
        
        return view('rptOCRSummary',compact('buyerPurchaseOrderList', 'GroupedData', 'OutwardGroupedData', 'TrimGroupedData', 'KDPLGroupedData','invoiceGroupedData','PassGroupedData','RejectGroupedData','TrimOutwardGroupedData'));
    }
    
    // public function rptOCRSummary(Request $request)
    // {  
        
    //     $sales_order_no = $request->sales_order_no;
    //     $job_status_id = $request->job_status_id;
    //     $orderTypeId = $request->orderTypeId;
        
    //     if($job_status_id > 0)
    //     {
    //         $status_filter = " AND job_status_id=".$job_status_id;
    //     }
    //     else
    //     {
    //         $status_filter = "";
    //     }
        
    //     if($orderTypeId > 0)
    //     {
    //         $orderType_filter = " AND order_type=".$orderTypeId;
    //     }
    //     else
    //     {
    //         $orderType_filter = "";
    //     }
        
    //     if($sales_order_no != "")
    //     {
    //         $sales_order_no_filter = " AND tr_code='".$sales_order_no."'";
    //     }
    //     else
    //     {
    //         $sales_order_no_filter = "";
    //     }
    //     //DB::enableQueryLog();
    //     $BuyerPurchaseData = DB::select("select ifnull((total_qty),0) as total_order_qty,buyer_purchse_order_master.*,
    //         (select ifnull((dbk_value + embroidery_value + printing_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as washing_emb_printing,
    //         (select ifnull((testing_ocr_cost),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as testing_ocr_cost,
    //         (select ifnull((transport_ocr_cost),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as transport_ocr_cost,
    //         (select ifnull((finance_cost_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as finance_cost_value,
    //         (select ifnull((agent_commision_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as commision,
    //         (select ifnull((ixd_value),0) FROM sales_order_costing_master where sales_order_no = buyer_purchse_order_master.tr_code) as ixd,
    //         (select ifnull(sum(size_qty_total),0) FROM cut_panel_grn_detail where sales_order_no = buyer_purchse_order_master.tr_code) as cut_qty, 
    //         (select ifnull(sum(size_qty_total),0) from carton_packing_inhouse_detail where sales_order_no = buyer_purchse_order_master.tr_code) as ship_qty,
    //         (select ifnull(sum(amount),0) from sale_transaction_detail where sales_order_no = buyer_purchse_order_master.tr_code) as sales_value,
    //         (select ifnull(sum(fabric_outward_master.total_meter),0) from fabric_outward_master 
    //         INNER JOIN vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code
    //         WHERE vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issue_value,
            
    //         (select sum(meter) from inward_details
    //                     where inward_details.item_code=bom_fabric_details.item_code and po_code in (SELECT pur_code FROM `purchaseorder_detail` WHERE  purchaseorder_detail.sales_order_no=buyer_purchse_order_master.tr_code)) as GRNQty,
    //                     ifnull((SELECT (LENGTH(purchaseorder_detail.bom_code) - LENGTH(REPLACE(purchaseorder_detail.bom_code, ',', '')) + 1) FROM `purchaseorder_detail` WHERE
    //                     purchaseorder_detail.sales_order_no= buyer_purchse_order_master.tr_code  limit 0,1),1) as order_count,
              
    //         ledger_master.ac_name as customerName,fg_master.fg_name  
    //         FROM buyer_purchse_order_master 
    //         LEFT JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
    //         LEFT JOIN fg_master ON fg_master.fg_id = buyer_purchse_order_master.fg_id               
    //         INNER JOIN bom_fabric_details ON bom_fabric_details.sales_order_no =  buyer_purchse_order_master.tr_code 
    //         WHERE og_id !=4 ".$sales_order_no_filter." ".$orderType_filter." ".$status_filter." GROUP BY tr_code");
    //         //dd(DB::getQueryLog());                  
    //     return view('rptOCRSummary',compact('BuyerPurchaseData'));
    // } 
    
    public function OpenSalesOrderDetailDashboardTrial(Request $request)
    {
        
        $currentDate = isset($request->currentDate) ? $request->currentDate : "";  
        
        if($currentDate == "")
        { 
            echo "<script>location.href='OpenSalesOrderDetailDashboardTrial?currentDate=".date('Y-m-d')."';</script>";
        }
        
        $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
      //DB::enableQueryLog();
      if($currentDate)
      {
            $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
                    select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name', 'sales_order_costing_master.total_cost_value','sales_order_costing_master.order_rate','sales_order_costing_master.production_value','sales_order_costing_master.other_value',
                    DB::raw('(select ifnull(sum(order_qty),0) from sale_transaction_detail where sale_transaction_detail.sales_order_no=buyer_purchse_order_master.tr_code) as shipped_qty'),
                    DB::raw('(select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty2'),
                    DB::raw('(select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty'),
                    DB::raw('(select ifnull(sum(total_meter),0) from fabric_outward_master INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code where vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issued'),
                    DB::raw('(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark'),
                    'fg_master.fg_name','merchant_master.merchant_name',
                    'brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name','order_group_name'
                    ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
                    , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
                    ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
                    ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                    ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                    ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
                    ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                    ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                    ->join('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                    ->where('buyer_purchse_order_master.delflag','=', '0')
                    ->where('buyer_purchse_order_master.og_id','!=', '4')
                    ->where('buyer_purchse_order_master.tr_date','<=', $currentDate) 
                    ->where('buyer_purchse_order_master.job_status_id','=', '1')
                    ->get();
      }
      else
      {
            $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::
                select('buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name', 'sales_order_costing_master.total_cost_value','sales_order_costing_master.order_rate','sales_order_costing_master.production_value','sales_order_costing_master.other_value',
                DB::raw('(select ifnull(sum(order_qty),0) from sale_transaction_detail where sale_transaction_detail.sales_order_no=buyer_purchse_order_master.tr_code) as shipped_qty'),
                DB::raw('(select sum(total_qty) from packing_inhouse_master where sales_order_no= buyer_purchse_order_master.tr_code) as shipped_qty2'),
                DB::raw('(select sum(adjust_qty) from buyer_purchase_order_detail where tr_code= buyer_purchse_order_master.tr_code) as adjust_qty'),
                DB::raw('(select ifnull(sum(total_meter),0) from fabric_outward_master INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code where vendor_purchase_order_master.sales_order_no=buyer_purchse_order_master.tr_code) as fabric_issued'),
                DB::raw('(select remark from buyer_purchase_order_detail where tr_code = buyer_purchse_order_master.tr_code GROUP BY buyer_purchase_order_detail.tr_code) as remark'),
                'fg_master.fg_name','merchant_master.merchant_name','order_group_name',
                'brand_master.brand_name','job_status_master.job_status_name','main_style_master.mainstyle_name'
                ,DB::raw('(select ifnull(sum(total_qty),0) from cut_panel_grn_master where cut_panel_grn_master.sales_order_no=buyer_purchse_order_master.tr_code) as cut_qty')
                , DB::raw('(select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) as prod_qty'))
                ->join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
                ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
                ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
                ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
                ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')
                ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->join('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                ->where('buyer_purchse_order_master.delflag','=', '0')
                ->where('buyer_purchse_order_master.og_id','!=', '4') 
                ->where('buyer_purchse_order_master.job_status_id','=', '1')
                ->get();
      }
        //dd(DB::getQueryLog());
        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
     
        return view('OpenSalesOrderDetailDashboardTrial', compact('Buyer_Purchase_Order_List','currentDate','chekform','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc'));
    }
    
     
    public function CostingOHPDashboard(Request $request)
    {
        $job_status_id= 1;
        $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '118')
            ->first();
        $Ac_code = isset($request->Ac_code) ? $request->Ac_code : 0;
        return view('CostingOHPDashboard', compact('chekform','job_status_id','Ac_code'));
        
    }
    
    public function DashboardCostingOHPDashboard(Request $request)
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
            ->where('emp_id', Session::get('userId'))
            ->where('form_id', '118')
            ->first();
     
      return view('DashboardCostingOHPDashboard', compact('chekform','job_status_id'));
        
    }
    
    public function LoadCostingOHPDashboard1(Request $request)
    { 
        $type = $request->filter; 
        $job_status_id = $request->job_status_id; 
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $parameter = $request->para1;
        $parameter1 = $request->para2;
      
        if($parameter == 'm')
        {
            $fromDate = date('Y-m-01');
            $toDate = date('Y-m-t');
            $job_status_id = '1,2';
        }
        else if($parameter == 'y')
        {
            $fromDate = date('Y-04-01');
            $nextYear = date('Y')+1;
            $date = $nextYear.'-03-31';
            $toDate = date($date);
            $job_status_id = '1,2';
        }
        
        if($parameter == 'o')
        {
            $job_status_id = 1;
            $fromDate = date('Y-04-01');
            $nextYear = date('Y')+1;
            $date = $nextYear.'-03-31';
            $toDate = date($date);
        }
        else if($parameter == 'c')
        {
            $job_status_id = 2;
            $fromDate = date('Y-04-01');
            $nextYear = date('Y')+1;
            $date = $nextYear.'-03-31';
            $toDate = date($date);
        }
        
        $filter = ' AND buyer_purchse_order_master.order_type IN('.$parameter1.') AND isMarketing=1 AND isCEO=1 AND buyer_purchse_order_master.job_status_id IN('.$job_status_id.') AND buyer_purchse_order_master.og_id!=4 
                    AND buyer_purchse_order_master.order_received_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"';
     
        $filter1 = '';  
        
        // if($type == 0)
        // {
        //     $filter = ' AND buyer_purchse_order_master.order_type IN() AND isMarketing=1 AND isCEO=1 AND buyer_purchse_order_master.job_status_id= '.$job_status_id.' AND buyer_purchse_order_master.og_id!=4 AND buyer_purchse_order_master.order_received_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"';
        // }
        // else if($type == 1)
        // {
            
        //     $filter = ' AND buyer_purchse_order_master.job_status_id= '.$job_status_id.' AND buyer_purchse_order_master.order_received_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND buyer_purchse_order_master.og_id!=4 AND isMarketing != 1 
        //                 OR  buyer_purchse_order_master.og_id!=4 AND isCEO != 1 AND buyer_purchse_order_master.job_status_id= '.$job_status_id.' AND buyer_purchse_order_master.order_received_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"';
    
        // }
        // else if($type == 2)
        // {
        //     $filter = ' AND buyer_purchse_order_master.job_status_id= '.$job_status_id.' AND isMarketing=1 AND isCEO=1 AND buyer_purchse_order_master.og_id!=4 AND buyer_purchse_order_master.order_received_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"';
        // } 
        // else if($type == 3)
        // {
        //     $filter = ' AND isMarketing=1 AND isCEO=1 AND buyer_purchse_order_master.job_status_id= '.$job_status_id.' AND buyer_purchse_order_master.og_id=4 AND buyer_purchse_order_master.order_received_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"'; 
        // }
        
         // DB::enableQueryLog();
  
        $BOMCostingStatusList = DB::select("SELECT  tr_code,tr_date,po_code, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            inner join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where 1 ".$filter." order by buyer_purchse_order_master.tr_date DESC");
            // dd(DB::getQueryLog());
            $html = ""; 
            $countKDPL = 0;
            $totalOrderQty = 0;
            $totalLakhMin = 0;
            $totalOrderValue = 0; 
            $totalcpmValue = 0;
            $totalohpValue = 0;
            $totalcmohpValue = 0;
            $totalOrderRate = 0; 
            $totalFOB = 0;
            $totalcmohpValue1 = 0;
            $totalSAM = 0;
            $head_fob = 0;
            foreach($BOMCostingStatusList as $row)    
            {
                 if($row->production_value > 0 && $row->sam > 0)
                 {
                    $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                 }
                 else
                 {
                    $cm_sam = 0;
                 }
                 
                 $profit_value=0.0;
                 $profit_value=  ($row->order_rate - $row->total_cost_value);
                 $cmd = $row->production_value+$row->other_value+$profit_value;
                 
                 if($cmd > 0 && $row->total_qty > 0)
                 {
                    $CMOHP_per = (($cmd)/$row->order_rate)*100; 
                 }
                 else
                 {
                    $CMOHP_per = 0;
                 }
                  
                //  if($CMOHP_per > 0 && $row->total_qty > 0)
                //  {
                    $CMOHP_value = $row->production_value + $profit_value + $row->other_value;;
                //  }
                //  else
                //  {
                //     $CMOHP_value = 0;
                //  }
                
                  $url = 'https://kenerp.com/GetCostingSalesOrderWiseData/'.$row->tr_code;
                 
                  $html .='<tr>
                            <td style="white-space:nowrap"><a href="'.$url.'" target="_blank">'.$row->tr_code.'</a></td>
                            <td style="white-space:nowrap">'.date('d-M-Y',strtotime($row->tr_date)).'</td>
                            <td style="white-space:nowrap">'.$row->brand_name.'</td>
                            <td style="white-space:nowrap">'.$row->ac_name.'</td>
                            <td style="white-space:nowrap">'.$row->mainstyle_name.'</td>
                            <td style="text-align:right;">'.money_format('%!i',($row->total_qty/100000)).'</td>
                            <td style="text-align:right;">'.money_format('%!i',($row->order_value/10000000)).'</td> 
                            <td style="text-align:right;">'.money_format('%!i',$CMOHP_value/$row->sam).'</td>
                            <td style="text-align:right;">'.money_format('%!i',$CMOHP_per).'</td>
                         </tr>';
                $totalOrderQty += $row->total_qty;
                $totalLakhMin += round(($row->total_qty * $row->sam)/100000,2);
                $totalOrderValue += $row->order_value;
                $totalcpmValue += $cm_sam;
                $totalohpValue += ((($row->other_value+$row->Profit)*$row->total_qty)/100000);
                $totalcmohpValue1 += round(((($CMOHP_value/$row->sam)*($row->total_qty * $row->sam))/100000),2);
                $totalFOB += $CMOHP_per;
                $totalOrderRate += $row->order_rate;
                $totalSAM += $row->sam;
                $countKDPL++;
                $head_fob += (round(((($CMOHP_value/$row->sam)*($row->total_qty * $row->sam))/100000),2)/$row->order_rate) * 100;
        }
        $totalcmohpValue = $totalcmohpValue1/$totalLakhMin;
        
        return response()->json(['html' => $html,'totalOrderQty'=>$totalOrderQty,'totalLakhMin'=>$totalLakhMin,'totalOrderValue'=>$totalOrderValue,'countKDPL'=>$countKDPL,'job_status_id'=>$job_status_id,'fromDate'=>$fromDate,'toDate'=>$toDate,
                                'totalcpmValue'=>$totalcpmValue,'totalohpValue'=>$totalohpValue,'totalcmohpValue'=>$totalcmohpValue,'totalcmohpValue1'=>$totalcmohpValue1,'totalFOB'=>$totalFOB,'totalOrderRate'=>$totalOrderRate,'head_fob'=>$head_fob]);
    }
    
    public function LoadCostingOHPDashboard(Request $request)
    { 
        $type = $request->filter; 
        $order_type = $request->order_type; 
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $Ac_code = isset($request->Ac_code) ? $request->Ac_code : 0;
        
        $filter = ''; 
        $filter1 = '';
        
        if($Ac_code > 0)
        {
             $filter1 = ' AND buyer_purchse_order_master.job_status_id !=3 AND buyer_purchse_order_master.Ac_code='.$Ac_code; 
        }
        if($type == 0)
        {
            $filter = ' AND buyer_purchse_order_master.job_status_id IN(1,2) AND buyer_purchse_order_master.job_status_id !=3  AND buyer_purchse_order_master.og_id!=4 AND buyer_purchse_order_master.tr_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'" and buyer_purchse_order_master.order_type='.$order_type;
        }
        else if($type == 1)
        {
            
            $filter = ' AND buyer_purchse_order_master.job_status_id IN(1,2) AND buyer_purchse_order_master.job_status_id !=3  AND buyer_purchse_order_master.order_type='.$order_type.' AND buyer_purchse_order_master.tr_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'" AND buyer_purchse_order_master.og_id!=4 AND isMarketing != 1  AND isMarketing != 3  AND isMarketing != 4 OR buyer_purchse_order_master.order_type='.$order_type.' AND buyer_purchse_order_master.og_id!=4 AND isCEO != 1   AND isMarketing != 3  AND isMarketing != 4 AND buyer_purchse_order_master.job_status_id !=3 AND buyer_purchse_order_master.tr_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"';
    
        }
        else if($type == 2)
        {
            $filter = ' AND buyer_purchse_order_master.job_status_id IN(1,2) AND buyer_purchse_order_master.job_status_id !=3  AND buyer_purchse_order_master.order_type='.$order_type.' AND isMarketing=1 AND isCEO=1 AND buyer_purchse_order_master.og_id!=4 AND buyer_purchse_order_master.job_status_id !=3 AND buyer_purchse_order_master.tr_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"';
        } 
        else if($type == 3)
        {
            $filter = ' AND buyer_purchse_order_master.job_status_id IN(1,2)  AND buyer_purchse_order_master.job_status_id !=3 AND buyer_purchse_order_master.order_type='.$order_type.' AND buyer_purchse_order_master.og_id=4 AND buyer_purchse_order_master.job_status_id !=3 AND buyer_purchse_order_master.tr_date BETWEEN "'.$fromDate.'" AND "'.$toDate.'"'; 
        }
        else if($type == 4)
        {
            $filter = ' AND buyer_purchse_order_master.isMarketing=3 AND buyer_purchse_order_master.job_status_id !=3';  
        }
        else if($type == 5)
        {
            $filter = ' AND buyer_purchse_order_master.isMarketing=4 AND buyer_purchse_order_master.job_status_id !=3'; 
        } 
        
        
        // DB::enableQueryLog();
        $BOMCostingStatusList = DB::select("SELECT  tr_code,tr_date,po_code,reason, 
            buyer_purchse_order_master.mainstyle_id,mainstyle_name, buyer_purchse_order_master.Ac_code, ac_short_name as ac_name, username,merchant_name, buyer_purchse_order_master.style_no,
            order_received_date,brand_name,shipment_date,buyer_purchse_order_master.order_rate, total_qty  ,order_value,  shipped_qty ,sales_order_costing_master.other_value,
            buyer_purchse_order_master.isMarketing,buyer_purchse_order_master.isCEO,
            (buyer_purchse_order_master.order_rate - ifnull(sales_order_costing_master.total_cost_value,0)) as Profit,buyer_purchse_order_master.sam,sales_order_costing_master.production_value,
            sales_order_costing_master.total_cost_value,
            
            ifnull((select count(sales_order_no) from sales_order_costing_master where sales_order_no=buyer_purchse_order_master.tr_code),0) as Costing_Count,
            ifnull((select count(sales_order_no) from bom_fabric_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Fabric_BOM_Count,
            ifnull((select count(sales_order_no) from bom_sewing_trims_details where sales_order_no=buyer_purchse_order_master.tr_code),0) as Trims_BOM_Count 
            FROM `buyer_purchse_order_master` 
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            inner join sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
            left outer join merchant_master on merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where 1 ".$filter1." ".$filter." order by buyer_purchse_order_master.isMarketing DESC");
         //dd(DB::getQueryLog());
            $html = ""; 
            $countKDPL = 0;
            $totalOrderQty = 0;
            $totalLakhMin = 0;
            $totalOrderValue = 0; 
            $totalcpmValue = 0;
            $totalohpValue = 0;
            $totalcmohpValue1 = 0;
            $totalFOB = 0;
            $totalOrderRate = 0; 
            $totalSAM = 0;
            
            foreach($BOMCostingStatusList as $row)    
            {
                 if($row->production_value > 0 && $row->sam > 0)
                 {
                    $cm_sam = number_format((float)($row->production_value/$row->sam), 2, '.', '');
                 }
                 else
                 {
                    $cm_sam = 0;
                 }
                 
                 $profit_value=0.0;
                 $profit_value=  ($row->order_rate - $row->total_cost_value);
                 $cmd = $row->production_value+$row->other_value+$profit_value;
                 
                 if($cmd > 0 && $row->total_qty > 0)
                 {
                    $CMOHP_per = (($cmd)/$row->order_rate)*100; 
                 }
                 else
                 {
                    $CMOHP_per = 0;
                 }
                  
                //  if($CMOHP_per > 0 && $row->total_qty > 0)
                //  {
                    $CMOHP_value = $row->production_value + $profit_value + $row->other_value;;
                //  }
                //  else
                //  {
                //     $CMOHP_value = 0;
                //  }
                 $marketConfirm = '';
                 $ceoConfirm = '';
                 $marketChk1 = '';
                 $marketChk2 = '';
                 $marketChk3 = '';
                 $marketChk4 = '';
                 
                 if($row->isMarketing == 1)
                 {
                    $marketChk1 = 'selected'; 
                 }
                 else if($row->isMarketing == 2)
                 {
                    $marketChk2 = 'selected'; 
                 } 
                 else if($row->isMarketing == 3)
                 {
                    $marketChk3 = 'selected'; 
                 } 
                 else if($row->isMarketing == 4)
                 {
                    $marketChk4 = 'selected'; 
                 } 
                 else
                 {
                    $marketChk1 = '';
                    $marketChk2 = '';
                    $marketChk3 = '';
                    $marketChk4 = ''; 
                 }
                 
                 
                 if($row->isCEO == 1)
                 {
                    $ceoChk = 'checked';
                    $ceoConfirm = 'Approved';
                 }
                 else
                 {
                    $ceoChk = '';
                    $ceoConfirm = 'Not Approved';
                 }
                 $user_type = Session::get('user_type');
                 
                 if($user_type == 8)
                 {
                     $disabledMarket= '';
                 }
                 else
                 {
                     $disabledMarket = 'disabled';
                 }
                 
                 if($user_type == 9)
                 {
                     $disabledCEO = '';
                 }
                 else
                 {
                     $disabledCEO = 'disabled';
                 }
                 
                 if($row->isMarketing == 3 || $user_type == 2  || $user_type == 3  || $user_type == 4  || $user_type == 5  || $user_type == 6  || $user_type == 7  || $user_type == 9 || $row->isMarketing == 4)
                 {
                      $disabledMarket = 'disabled';
                 }
                 else
                 {
                     $disabledMarket = '';
                 }
                 
                  $url = 'GetCostingSalesOrderWiseData/'.$row->tr_code;
                  if($CMOHP_value > 0 && $row->sam > 0)
                  {
                      $smohpsam= $CMOHP_value/$row->sam;
                  }
                  else
                  {
                      $smohpsam = 0;
                  }
                 
                  $html .='<tr>
                    <td style="white-space:nowrap"><a href="'.$url.'" target="_blank">'.$row->tr_code.'</a></td>
                    <td style="white-space:nowrap">'.date('d-M-Y',strtotime($row->tr_date)).'</td>
                    <td style="white-space:nowrap">'.$row->brand_name.'</td>
                    <td style="white-space:nowrap">'.$row->ac_name.'</td>
                    <td style="white-space:nowrap">'.$row->mainstyle_name.'</td>
                    <td style="text-align:right;">'.money_format('%!i',($row->total_qty/100000)).'</td>
                    <td style="text-align:right;">'.money_format('%!i',($row->order_value/10000000)).'</td>
                    <td style="text-align:center; white-space:nowrap">'.money_format('%!i',($row->total_qty * $row->sam)/100000).'</td>
                    <td style="text-align:right;">'.money_format('%!i',($CMOHP_value*$row->total_qty)/100000).'</td>
                    
                    <td style="text-align:right;">'.money_format('%!i',$smohpsam).'</td>
                    <td style="text-align:right;">'.money_format('%!i',$CMOHP_per).'</td>
                    <td style="text-align:center;"><select class="form-control" sales_order_no="'.$row->tr_code.'"  onchange="MarketAutorization(this);" '.$disabledMarket.' ><option value="">--Select--</option><option value="2" '.$marketChk2.' >Not Approve</option><option value="1" '.$marketChk1.' >Approve</option><option value="3" '.$marketChk3.' >Cancel</option><option value="4" '.$marketChk4.' >Rejected</option></select></td>';
                    if($row->isMarketing == 3 || $row->isMarketing == 4)
                    {
                        $html .='<td style="text-align:center;" nowrap>'.$row->reason.'</td>';
                    }
                    else
                    {
                        $html .='<td style="text-align:center;" nowrap> <input type="checkbox" sales_order_no="'.$row->tr_code.'"  onchange="autorization(this);" who="2" value=""  '.$ceoChk.' '.$disabledCEO.' />  <br/> '.$ceoConfirm.'</td>';
                    }
                $html .='</tr>';
                $totalOrderQty += $row->total_qty/100000;
                $totalLakhMin += ($row->total_qty * $row->sam)/100000;
                $totalOrderValue += $row->order_value/10000000;
                $totalcpmValue += $cm_sam;
                $totalohpValue += ((($row->other_value+$row->Profit)*$row->total_qty)/100000);
                $totalcmohpValue1 += $CMOHP_value;
                $totalFOB += $CMOHP_per;
                $totalOrderRate += $row->order_rate;
                $countKDPL++;
                $totalSAM += $row->sam;
        }
        
        
                    // <td style="text-align:center; white-space:nowrap">'.money_format('%!i',($cm_sam)).' </td>
                    // <td style="text-align:right;">'.money_format('%!i',(((($row->other_value+$row->Profit)*$row->total_qty)/100000))).'</td>
                    
        if($totalcmohpValue1 > 0 && $totalSAM > 0)
        {
            $totalcmohpValue = $totalcmohpValue1/$totalSAM;
        }
        else
        {
             $totalcmohpValue = 0;
        }
        
        
        return response()->json(['html' => $html,'totalOrderQty'=>$totalOrderQty,'totalLakhMin'=>$totalLakhMin,'totalOrderValue'=>$totalOrderValue,'countKDPL'=>$countKDPL,'fromDate'=>$fromDate,'toDate'=>$toDate,
                                'totalcpmValue'=>$totalcpmValue,'totalohpValue'=>$totalohpValue,'totalcmohpValue'=>$totalcmohpValue,'totalcmohpValue1'=>$totalcmohpValue1,'totalFOB'=>$totalFOB,'totalOrderRate'=>$totalOrderRate]);
    }
    
    public function ApprovedAutorizedPerson(Request $request)
    { 
        $chk_value = $request->chk_value;
        $who = $request->who;
        $reason = $request->reason;
          
        if($who == 1)
        {
            $data = DB::table('buyer_purchse_order_master')->where('tr_code',$request->sales_order_no)->update(['isMarketing' => $chk_value,'reason'=>$reason]); 
        }
        else
        {
            $data = DB::table('buyer_purchse_order_master')->where('tr_code',$request->sales_order_no)->update(['isCEO' => $chk_value]); 
        }
        
        $BuyerData = DB::table('buyer_purchse_order_master')
                    ->select('*')
                    ->where('tr_code',$request->sales_order_no)
                    ->where('buyer_purchse_order_master.isMarketing','=', 1)
                    ->where('buyer_purchse_order_master.isCEO','=', 1) 
                    ->get();
                    
        if(count($BuyerData) > 0)
        {
            $data1 = DB::table('sales_order_costing_master')->where('sales_order_no',$request->sales_order_no)->update(['is_approved' => 2]); 
        }
        
        return response()->json(['html' => $data]);
    } 
    
    public function sendEmail($sales_order_no)
    {
        // DB::enableQueryLog();
        $buyerData = DB::table('buyer_purchse_order_master')
                    ->select('merchant_master.email as merchant_email','PDMerchant_master.email as PDMerchant_email')
                    ->leftjoin('merchant_master','merchant_master.merchant_id','=','buyer_purchse_order_master.merchant_id')
                    ->leftjoin('PDMerchant_master','PDMerchant_master.PDMerchant_id','=','buyer_purchse_order_master.PDMerchant_id')
                    ->where('tr_code', $sales_order_no) 
                    ->first();
        // dd(DB::getQueryLog());            
        $merchant_email = isset($buyerData->merchant_email) ? $buyerData->merchant_email : "";    
        $PDMerchant_email = isset($buyerData->PDMerchant_email) ? $buyerData->PDMerchant_email : "";
        
        if($merchant_email != "")
        { 
            Mail::to($merchant_email)->send(new SalesOrderEmail($sales_order_no));
        }
        
        if($PDMerchant_email != "")
        { 
            Mail::to($PDMerchant_email)->send(new SalesOrderEmail($sales_order_no));
        }
    
        return "Email sent successfully!";
    }
    
    public function GetOpenOrderReport(Request $request)
    {   
        $order_received_date = $request->OpenOrderTrDate;
        
        // $Buyer_Purchase_Order_List = DB::select("
        //     SELECT 
        //         buyer_purchse_order_master.orderCategoryId,
        //         buyer_purchse_order_master.sam,
        //         buyer_purchse_order_master.tr_code,
        //         buyer_purchse_order_master.Ac_code,
        //         order_group_name,
        //         OrderCategoryShortName,
        //         ledger_master.ac_code,
        //         ledger_master.ac_short_name AS buyer_name,
        //         COUNT(*) AS totalOrder,
        //         SUM(buyer_purchse_order_master.total_qty) AS total_qty,
        //         SUM(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam) AS totalOrderMin,
        //         SUM(buyer_purchse_order_master.order_value) AS totalOrderValue, 
                
        //         (SELECT IFNULL(SUM(total_qty),0) 
        //              FROM cut_panel_grn_master 
        //              WHERE cut_panel_grn_master.sales_order_no = buyer_purchse_order_master.tr_code AND cut_panel_grn_master.cpg_date <= '".$order_received_date."') AS cut_qty,
                     
        //         SUM((
        //             SELECT IFNULL(SUM(total_qty), 0)
        //             FROM stitching_inhouse_master 
        //             WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code
        //               AND stitching_inhouse_master.sti_date <= '".$order_received_date."'
        //         )) AS produced_qty,
        
        //         SUM((
        //             SELECT IFNULL(SUM(total_qty), 0)
        //             FROM stitching_inhouse_master 
        //             WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code
        //               AND stitching_inhouse_master.sti_date <= '".$order_received_date."'
        //         ) * sam) AS total_prod_min,
        
        //         SUM((
        //             SELECT IFNULL(SUM(total_qty), 0)
        //             FROM stitching_inhouse_master 
        //             WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code
        //               AND stitching_inhouse_master.sti_date <= '".$order_received_date."'
        //         ) * order_rate) AS produced_value,
        
        //         SUM((
        //             SELECT IFNULL(SUM(order_qty), 0)
        //             FROM sale_transaction_detail 
        //             WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code
        //               AND sale_transaction_detail.sale_date <= '".$order_received_date."'
        //         )) AS total_sales_qty,
        
        //         SUM((
        //             SELECT IFNULL(SUM(order_qty), 0)
        //             FROM sale_transaction_detail 
        //             WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code
        //               AND sale_transaction_detail.sale_date <= '".$order_received_date."'
        //         ) * sam) AS total_sales_min,
        
        //         SUM((
        //             SELECT IFNULL(SUM(amount), 0)
        //             FROM sale_transaction_detail 
        //             WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code
        //               AND sale_transaction_detail.sale_date <= '".$order_received_date."'
        //         )) AS total_sales_amount,
        
        //         SUM((
        //             SELECT IFNULL(SUM(adjust_qty), 0)
        //             FROM buyer_purchase_order_detail 
        //             WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
        //               AND buyer_purchase_order_detail.tr_date <= '".$order_received_date."'
        //         )) AS short_close_qty,
        
        //         SUM((
        //             SELECT IFNULL(SUM(adjust_qty), 0)
        //             FROM buyer_purchase_order_detail 
        //             WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
        //               AND buyer_purchase_order_detail.tr_date <= '".$order_received_date."'
        //         ) * sam) AS short_close_min,
        
        //         SUM((
        //             SELECT IFNULL(SUM(adjust_qty), 0)
        //             FROM buyer_purchase_order_detail 
        //             WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
        //               AND buyer_purchase_order_detail.tr_date <= '".$order_received_date."'
        //         ) * order_rate) AS short_close_value
        
        //     FROM buyer_purchse_order_master
        //     INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
        //     LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId
        //     LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
            
        //     WHERE buyer_purchse_order_master.delflag = 0
        //         AND buyer_purchse_order_master.og_id != 4
        //         AND buyer_purchse_order_master.order_type != 2
        //         AND buyer_purchse_order_master.order_received_date <= '".$order_received_date."'
        //         AND (
        //             buyer_purchse_order_master.order_close_date > '".$order_received_date."' 
        //             OR buyer_purchse_order_master.order_close_date IS NULL
        //         )
        //     GROUP BY 
        //         buyer_purchse_order_master.Ac_code, 
        //         buyer_purchse_order_master.orderCategoryId
        //     ORDER BY totalOrder DESC
        // ");
// DB::enableQueryLog();
        $Buyer_Purchase_Order_List = DB::SELECT("
            SELECT 
                order_group_master.order_group_name,
                buyer_purchse_order_master.*,
                ledger_master.ac_short_name, 
                SUM(buyer_purchse_order_master.total_qty) AS total_qty,
                SUM(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam) AS totalOrderMin,
                SUM(buyer_purchse_order_master.order_value) AS totalOrderValue, 
                order_group_name,
                OrderCategoryShortName,
                ledger_master.ac_code,
                ledger_master.ac_short_name AS buyer_name,
                COUNT(*) AS totalOrder,
        
                (
                    SELECT SUM(GREATEST(IFNULL(grn.cut_panel_qty, 0) - IFNULL(po.buyer_po_qty, 0), 0))
                    FROM (
                        SELECT sales_order_no, SUM(total_qty) AS cut_panel_qty
                        FROM cut_panel_grn_master
                        WHERE cpg_date <= '$order_received_date'
                        GROUP BY sales_order_no
                    ) AS grn
                    LEFT JOIN (
                        SELECT tr_code, SUM(total_qty) AS buyer_po_qty
                        FROM buyer_purchse_order_master
                        WHERE delflag = 0
                          AND og_id != 4
                          AND order_type != 2
                          AND order_received_date <= '$order_received_date'
                          AND (
                              order_close_date > '$order_received_date' OR order_close_date IS NULL
                          )
                        GROUP BY tr_code
                    ) AS po
                    ON grn.sales_order_no = po.tr_code
                    WHERE EXISTS (
                        SELECT 1
                        FROM buyer_purchse_order_master bp
                        WHERE bp.tr_code = grn.sales_order_no
                          AND bp.Ac_code = buyer_purchse_order_master.Ac_code
                          AND bp.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                          AND bp.delflag = 0
                          AND bp.og_id != 4
                          AND bp.order_type != 2
                          AND bp.order_received_date <= '$order_received_date'
                          AND (
                              bp.order_close_date > '$order_received_date' OR bp.order_close_date IS NULL
                          )
                    )
                ) AS cut_qty,
                (
                    SELECT SUM(
                        GREATEST(IFNULL(grn.cut_panel_qty, 0) - IFNULL(po.buyer_po_qty, 0), 0) 
                        * IFNULL(bp.order_rate, 0)
                    )
                    FROM (
                        SELECT sales_order_no, SUM(total_qty) AS cut_panel_qty
                        FROM cut_panel_grn_master
                        WHERE cpg_date <= '$order_received_date'
                        GROUP BY sales_order_no
                    ) AS grn
                    LEFT JOIN (
                        SELECT tr_code, SUM(total_qty) AS buyer_po_qty
                        FROM buyer_purchse_order_master
                        WHERE delflag = 0
                          AND og_id != 4
                          AND order_type != 2
                          AND order_received_date <= '$order_received_date'
                          AND (order_close_date > '$order_received_date' OR order_close_date IS NULL)
                        GROUP BY tr_code
                    ) AS po ON grn.sales_order_no = po.tr_code
                    INNER JOIN buyer_purchse_order_master bp 
                        ON bp.tr_code = grn.sales_order_no
                       AND bp.Ac_code = buyer_purchse_order_master.Ac_code
                       AND bp.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                       AND bp.delflag = 0
                       AND bp.og_id != 4
                       AND bp.order_type != 2
                       AND bp.order_received_date <= '$order_received_date'
                       AND (bp.order_close_date > '$order_received_date' OR bp.order_close_date IS NULL)
                ) AS cut_qty_value,
                (
                    SELECT SUM(
                        GREATEST(IFNULL(grn.cut_panel_qty, 0) - IFNULL(po.buyer_po_qty, 0), 0) 
                        * IFNULL(bp.sam, 0)
                    )
                    FROM (
                        SELECT sales_order_no, SUM(total_qty) AS cut_panel_qty
                        FROM cut_panel_grn_master
                        WHERE cpg_date <= '$order_received_date'
                        GROUP BY sales_order_no
                    ) AS grn
                    LEFT JOIN (
                        SELECT tr_code, SUM(total_qty) AS buyer_po_qty
                        FROM buyer_purchse_order_master
                        WHERE delflag = 0
                          AND og_id != 4
                          AND order_type != 2
                          AND order_received_date <= '$order_received_date'
                          AND (order_close_date > '$order_received_date' OR order_close_date IS NULL)
                        GROUP BY tr_code
                    ) AS po ON grn.sales_order_no = po.tr_code
                    INNER JOIN buyer_purchse_order_master bp 
                        ON bp.tr_code = grn.sales_order_no
                       AND bp.Ac_code = buyer_purchse_order_master.Ac_code
                       AND bp.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                       AND bp.delflag = 0
                       AND bp.og_id != 4
                       AND bp.order_type != 2
                       AND bp.order_received_date <= '$order_received_date'
                       AND (bp.order_close_date > '$order_received_date' OR bp.order_close_date IS NULL)
                ) AS cut_qty_min,
                SUM((
                    SELECT IFNULL(SUM(total_qty), 0)
                    FROM stitching_inhouse_master 
                    WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code
                      AND stitching_inhouse_master.sti_date <= '$order_received_date'
                )) AS produced_qty,
        
                SUM((
                    SELECT IFNULL(SUM(total_qty), 0)
                    FROM stitching_inhouse_master 
                    WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code
                      AND stitching_inhouse_master.sti_date <= '$order_received_date'
                ) * sam) AS total_prod_min,
        
                SUM((
                    SELECT IFNULL(SUM(total_qty), 0)
                    FROM stitching_inhouse_master 
                    WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code
                      AND stitching_inhouse_master.sti_date <= '$order_received_date'
                ) * order_rate) AS produced_value,
        
                SUM((
                    SELECT IFNULL(SUM(order_qty), 0)
                    FROM sale_transaction_detail 
                    WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code
                      AND sale_transaction_detail.sale_date <= '$order_received_date'
                )) AS total_sales_qty,
        
                SUM((
                    SELECT IFNULL(SUM(order_qty), 0)
                    FROM sale_transaction_detail 
                    WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code
                      AND sale_transaction_detail.sale_date <= '$order_received_date'
                ) * sam) AS total_sales_min,
        
                SUM((
                    SELECT IFNULL(SUM(amount), 0)
                    FROM sale_transaction_detail 
                    WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code
                      AND sale_transaction_detail.sale_date <= '$order_received_date'
                )) AS total_sales_amount,
        
                SUM((
                    SELECT IFNULL(SUM(adjust_qty), 0)
                    FROM buyer_purchase_order_detail 
                    WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
                      AND buyer_purchase_order_detail.tr_date <= '$order_received_date'
                )) AS short_close_qty,
        
                SUM((
                    SELECT IFNULL(SUM(adjust_qty), 0)
                    FROM buyer_purchase_order_detail 
                    WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
                      AND buyer_purchase_order_detail.tr_date <= '$order_received_date'
                ) * sam) AS short_close_min,
        
                SUM((
                    SELECT IFNULL(SUM(adjust_qty), 0)
                    FROM buyer_purchase_order_detail 
                    WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code
                      AND buyer_purchase_order_detail.tr_date <= '$order_received_date'
                ) * order_rate) AS short_close_value,
        
                MAX(buyer_purchse_order_master.order_rate) AS order_rate
        
            FROM buyer_purchse_order_master 
        
            INNER JOIN ledger_master 
                ON ledger_master.Ac_code = buyer_purchse_order_master.Ac_code 
        
            LEFT JOIN order_group_master 
                ON order_group_master.og_id = buyer_purchse_order_master.og_id
        
            LEFT JOIN order_category 
                ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId
        
            WHERE 
                buyer_purchse_order_master.delflag = 0 
                AND buyer_purchse_order_master.og_id != 4
                AND buyer_purchse_order_master.order_type != 2
                AND buyer_purchse_order_master.order_received_date <= '$order_received_date'
                AND (
                    buyer_purchse_order_master.order_close_date > '$order_received_date' 
                    OR buyer_purchse_order_master.order_close_date IS NULL
                )
        
            GROUP BY 
                buyer_purchse_order_master.Ac_code, 
                buyer_purchse_order_master.orderCategoryId
        ");


            
// dd(DB::getQueryLog());
        $srno = 1;
        $html = '';
        $html1 = '';
        $hTotalOrder = 0; 
        $hTotalQty = 0; 
        $hTotalOrderMin = 0; 
        $hTotalOrderValuee = 0; 
        $hTotalProducedQty = 0; 
        $hTotalProducedMin = 0; 
        $hTotalProducedValue = 0; 
        $hTotalBalProducedQty = 0;
        $hTotalBalProducedMin = 0;
        $hTotalBalProducedValue = 0;
        $hTotalB2P = 0;
        $hTotalSalesValue = 0;
        $hTotalSalesMin = 0;
        $hTotalSalesAmount = 0;
        $overallB2PMin = 0;
             
        foreach($Buyer_Purchase_Order_List as $row)
        { 
            $short_close_min = $row->short_close_min;  
            $total_excess_min = 0;  
             
            $total_actual_min = ($row->totalOrderMin - $row->total_prod_min - $short_close_min) + $total_excess_min; 
            $overallB2PMin += $total_actual_min;  
        }
        
        foreach($Buyer_Purchase_Order_List as $row)
        {
            
            $pq = round(($row->produced_qty/100000),2);
            $tq = round(($row->total_qty/100000),2);
            
            if($pq > 0 && $tq > 0)
            { 
                $completion = sprintf("%.2f",((($pq/$tq) * 100)));
            }
            else
            {
                $completion = 0;
            }
            
            $short_close_qty = $row->short_close_qty;
            $short_close_min = $row->short_close_min;
            $short_close_value = $row->short_close_value;
            
            $total_actual_qty = ($row->total_qty - $row->produced_qty - $short_close_qty)+$row->cut_qty; 
            $total_actual_min = ($row->totalOrderMin - $row->total_prod_min - $short_close_min)+$row->cut_qty_min;
            $total_actual_value = ($row->totalOrderValue - $row->produced_value - $short_close_value)+$row->cut_qty_value;
            $b2p = sprintf("%.2f",((($row->totalOrderMin - $row->total_prod_min - $short_close_min) + $row->cut_qty_min)/100000));
            
            if($total_actual_min > 0 && $overallB2PMin > 0)
            {
                $TotalB2P = $total_actual_min/$overallB2PMin;
            }
            else
            {
                $TotalB2P = 0;
            }
            $first_character = substr($row->order_group_name, 0, 1);
             
            // if($total_actual_min > 0 && $total_actual_value > 0)
            // {
                $html .= '<tr> 
                            <td class="text-center" style="border-right: 3px solid;">'.($srno++).'</td>
                            <td style="border-right: 3px solid;">'.$row->buyer_name.'</td> 
                            <td class="text-center" style="border-right: 3px solid;">'.$first_character."-".$row->OrderCategoryShortName.'</td>
                            <td class="text-center sticky-column"><a style="color: #000!important;" href="/OpenSalesOrderDetailDashboard?fromDate=2018-01-01&toDate='.$request->OpenOrderTrDate.'&ac_code='.$row->ac_code.'" target="_blank">'.$row->totalOrder.'</a></td>
                            <td class="text-center">'.number_format($row->total_qty/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format($row->totalOrderMin/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format($row->totalOrderValue/10000000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format($row->produced_qty/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($row->total_prod_min)/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format(($row->produced_value)/10000000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($row->total_sales_qty)/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($row->total_sales_min)/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format(($row->total_sales_amount)/10000000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($total_actual_qty)/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($total_actual_min)/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format(($total_actual_value)/10000000, 2, '.', ',').'</td>
                            <td class="text-right">'.$completion.'</td>
                            <td class="text-right" '.$b2p.'-'.$overallB2PMin.'>'.(sprintf("%.2f",($TotalB2P * 100))).'</td>
                    </tr>';
            // } 
           $hTotalOrder += $row->totalOrder; 
           $hTotalQty += $row->total_qty; 
           $hTotalOrderMin += $row->totalOrderMin; 
           $hTotalOrderValuee += $row->totalOrderValue; 
           $hTotalProducedQty += $row->produced_qty; 
           $hTotalProducedMin += $row->total_prod_min; 
           $hTotalProducedValue += $row->produced_value; 
           $hTotalBalProducedQty += $total_actual_qty; 
           $hTotalBalProducedMin += $total_actual_min; 
           $hTotalBalProducedValue += $total_actual_value; 
           $hTotalB2P += sprintf("%.2f",($TotalB2P * 100)); 
           $hTotalSalesValue += number_format(($row->total_sales_qty/100000), 2, '.', ','); 
           $hTotalSalesMin +=  $row->total_sales_min/100000; 
           $hTotalSalesAmount += $row->total_sales_amount/10000000; 
        }
        
        $order_per1 = round(($hTotalProducedQty/100000),2);
        $order_per2 = round(($hTotalQty/100000),2);
        if($order_per1 > 0 && $order_per2 > 0)
        { 
            $order_per = ($order_per1/$order_per2) * 100;
        }
        else
        {
            $order_per = 0;
        }
        
        if($hTotalB2P > 0 && $overallB2PMin > 0)
        { 
            $B2P_avg = $hTotalB2P;
        }
        else
        {
            $B2P_avg = 0;
        }
        
        
        $html1 .= '<tr style="border: 3px solid;"> 
                    <td class="text-right" style="border-right: 3px solid;"></td> 
                    <td class="text-right" style="border-right: 3px solid;"></td> 
                    <td class="text-right" style="border-right: 3px solid;"><b>Total:</b></td>
                    <td class="text-center sticky-column"><b>'.$hTotalOrder.'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalQty/100000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalOrderMin/100000, 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format($hTotalOrderValuee/10000000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalProducedQty/100000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalProducedMin)/100000, 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format(($hTotalProducedValue)/10000000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalSalesValue), 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalSalesMin), 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format(($hTotalSalesAmount), 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalBalProducedQty/100000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalBalProducedMin)/100000, 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format(($hTotalBalProducedValue)/10000000, 2, '.', ',').'</b></td>
                    <td class="text-right"><b>'.round($order_per,2).'</b></td>
                    <td class="text-right"><b>'.(int)$B2P_avg.'.00</b></td>
                </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
    
    public function GetOpenOrderReport1(Request $request)
    {
        return view("GetOpenOrderReportTrial");
    }
    public function GetOpenOrderReportTrial(Request $request)
    {   
        $order_received_date = isset($request->OpenOrderTrDate) ? $request->OpenOrderTrDate : date("Y-m-d");
        //DB::enableQueryLog();
        $Buyer_Purchase_Order_List = DB::SELECT("
                SELECT 
                    buyer_purchse_order_master.orderCategoryId,
                    buyer_purchse_order_master.sam,
                    buyer_purchse_order_master.tr_code,
                    buyer_purchse_order_master.Ac_code,
                    order_group_name,
                    OrderCategoryShortName,
                    ledger_master.ac_code,
                    ledger_master.ac_short_name AS buyer_name,
                    COUNT(*) AS totalOrder,
                    SUM(buyer_purchse_order_master.total_qty) AS total_qty,
                    SUM(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam) AS totalOrderMin,
                    SUM(buyer_purchse_order_master.order_value) AS totalOrderValue,
                    (
                        SELECT 
                            SUM((
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) - B1.total_qty)
                        FROM 
                            buyer_purchse_order_master as B1 
                        WHERE 
                            B1.delflag = 0 
                            AND B1.og_id != 4  
                            AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                            AND B1.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                            AND (
                                order_close_date = '".$order_received_date."' 
                                OR (
                                    order_received_date <= '".$order_received_date."' 
                                    
                                    AND (
                                        SELECT SUM(total_qty) 
                                        FROM cut_panel_grn_master 
                                        WHERE sales_order_no = B1.tr_code
                                    ) >= B1.total_qty
                                )
                            )
                            AND (
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) >= B1.total_qty 
                            
                    ) AS total_excess_qty, 
                    (
                        SELECT 
                            SUM((((
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) - B1.total_qty) * B1.sam))
                        FROM 
                            buyer_purchse_order_master as B1 
                        WHERE 
                            B1.delflag = 0 
                            AND B1.og_id != 4  
                            AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                            AND B1.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                            AND (
                                order_close_date = '".$order_received_date."' 
                                OR (
                                    order_received_date <= '".$order_received_date."' 
                                    
                                    AND (
                                        SELECT SUM(total_qty) 
                                        FROM cut_panel_grn_master 
                                        WHERE sales_order_no = B1.tr_code
                                    ) >= B1.total_qty
                                )
                            )
                            AND (
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) >= B1.total_qty 
                            
                    ) AS total_excess_min,  
                    (
                        SELECT 
                            SUM((((
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) - B1.total_qty) * B1.order_rate))
                        FROM 
                            buyer_purchse_order_master as B1 
                        WHERE 
                            B1.delflag = 0 
                            AND B1.og_id != 4  
                            AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                            AND B1.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                            AND (
                                order_close_date = '".$order_received_date."' 
                                OR (
                                    order_received_date <= '".$order_received_date."' 
                                    
                                    AND (
                                        SELECT SUM(total_qty) 
                                        FROM cut_panel_grn_master 
                                        WHERE sales_order_no = B1.tr_code
                                    ) >= B1.total_qty
                                )
                            )
                            AND (
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) >= B1.total_qty 
                            
                    ) AS total_excess_value, 
                    SUM(
                        (SELECT IFNULL(SUM(total_qty), 0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code)
                    ) AS produced_qty,
                    SUM(
                        (SELECT IFNULL(SUM(total_qty), 0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) * sam
                    ) AS total_prod_min,
                    SUM(
                        (SELECT IFNULL(SUM(total_qty), 0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) * order_rate
                    ) AS produced_value,
                    SUM(
                        (SELECT IFNULL(SUM(order_qty), 0) FROM sale_transaction_detail WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code)
                    ) AS total_sales_qty,
                    SUM(
                        (SELECT IFNULL(SUM(order_qty), 0) FROM sale_transaction_detail WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code) * sam
                    ) AS total_sales_min,
                    SUM(
                        (SELECT IFNULL(SUM(amount), 0) FROM sale_transaction_detail WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code)
                    ) AS total_sales_amount,
                    SUM(
                        (SELECT IFNULL(SUM(adjust_qty), 0) FROM buyer_purchase_order_detail WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code)
                    ) AS short_close_qty,
                    SUM(
                        (SELECT IFNULL(SUM(adjust_qty), 0) FROM buyer_purchase_order_detail WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code) * sam
                    ) AS short_close_min,
                    SUM(
                        (SELECT IFNULL(SUM(adjust_qty), 0) FROM buyer_purchase_order_detail WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code) * order_rate
                    ) AS short_close_value
                FROM 
                    buyer_purchse_order_master 
                INNER JOIN 
                    ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                LEFT JOIN 
                    order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                LEFT JOIN 
                    order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                WHERE 
                    (buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4 
                    AND order_close_date = '".$order_received_date."') 
                    OR 
                    (order_received_date <= '".$order_received_date."' 
                    AND buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4) 
                GROUP BY 
                    buyer_purchse_order_master.Ac_code, buyer_purchse_order_master.orderCategoryId 
                ORDER BY 
                    totalOrder DESC
            ");

           // dd(DB::getQueryLog());    
        $srno = 1;
        $html = '';
        $html1 = '';
        $hTotalOrder = 0; 
        $hTotalQty = 0; 
        $hTotalOrderMin = 0; 
        $hTotalOrderValuee = 0; 
        $hTotalProducedQty = 0; 
        $hTotalProducedMin = 0; 
        $hTotalProducedValue = 0; 
        $hTotalBalProducedQty = 0;
        $hTotalBalProducedMin = 0;
        $hTotalBalProducedValue = 0;
        $hTotalB2P = 0;
        $hTotalSalesValue = 0;
        $hTotalSalesMin = 0;
        $hTotalSalesAmount = 0;
        $overallB2PMin = 0;
             
        foreach($Buyer_Purchase_Order_List as $row)
        { 
            $short_close_min = $row->short_close_min;  
            $total_excess_min = $row->total_excess_min;  
             
            $total_actual_min = ($row->totalOrderMin - $row->total_prod_min - $short_close_min) + $total_excess_min; 
            $overallB2PMin += $total_actual_min;  
        }
        
        foreach($Buyer_Purchase_Order_List as $row)
        {
            // $salesData = DB::SELECT("SELECT sum(order_qty) as total_qty, sum(amount) as total_amount FROM sale_transaction_detail 
            //             INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = sale_transaction_detail.sales_order_no 
            //             WHERE order_received_date <= '".$order_received_date."' AND  buyer_purchse_order_master.delflag = 0 
            //             AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id IN(1) AND buyer_purchse_order_master.Ac_code=".$row->Ac_code." 
            //             AND buyer_purchse_order_master.orderCategoryId=".$row->orderCategoryId);
            
            // $total_sales = isset($salesData[0]->total_qty) ? $salesData[0]->total_qty : 0;
            // $total_sales_min = $total_sales * $row->sam;
            // $total_sales_amount = isset($salesData[0]->total_amount) ? $salesData[0]->total_amount : 0;
            
            $pq = round(($row->produced_qty/100000),2);
            $tq = round(($row->total_qty/100000),2);
            
            if($pq > 0 && $tq > 0)
            { 
                $completion = sprintf("%.2f",((($pq/$tq) * 100)));
            }
            else
            {
                $completion = 0;
            }
            
            $short_close_qty = $row->short_close_qty;
            $short_close_min = $row->short_close_min;
            $short_close_value = $row->short_close_value;
            
            
            $total_excess_qty = $row->total_excess_qty;
        
            $total_excess_min = $row->total_excess_min;
     
            $total_excess_value =  $row->total_excess_value;
               
            
            $total_actual_qty = ($row->total_qty - $row->produced_qty - $short_close_qty) + $total_excess_qty;
            $total_actual_min = ($row->totalOrderMin - $row->total_prod_min - $short_close_min) + $total_excess_min;
            $total_actual_value = ($row->totalOrderValue - $row->produced_value - $short_close_value)+$total_excess_value;
            $b2p = sprintf("%.2f",((($row->totalOrderMin - $row->total_prod_min - $short_close_qty) + $total_excess_qty)/100000));
            if($total_actual_min > 0 && $overallB2PMin > 0)
            {
                $TotalB2P = $total_actual_min/$overallB2PMin;
            }
            else
            {
                $TotalB2P = 0;
            }
            $first_character = substr($row->order_group_name, 0, 1);
             
            if($total_actual_qty > 0 && $total_actual_min > 0 && $total_actual_value > 0)
            {
                $html .= '<tr> 
                            <td class="text-center" style="border-right: 3px solid;">'.($srno++).'</td>
                            <td style="border-right: 3px solid;">'.$row->buyer_name.'</td> 
                            <td class="text-center" style="border-right: 3px solid;">'.$first_character."-".$row->OrderCategoryShortName.'</td>
                            <td class="text-center sticky-column"><a style="color: #fff!important;" href="/OpenSalesOrderDetailDashboard?fromDate=2018-01-01&toDate='.$request->OpenOrderTrDate.'&ac_code='.$row->Ac_code.'" target="_blank">'.$row->totalOrder.'</a></td>
                            <td class="text-center">'.number_format($row->total_qty/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format($row->totalOrderMin/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format($row->totalOrderValue/10000000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format($row->produced_qty/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($row->total_prod_min)/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format(($row->produced_value)/10000000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($row->total_sales_qty)/100000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($row->total_sales_min)/100000, 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format(($row->total_sales_amount)/10000000, 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($total_actual_qty/100000), 2, '.', ',').'</td>
                            <td class="text-center">'.number_format(($total_actual_min/100000), 2, '.', ',').'</td>
                            <td class="text-center" style="border-right: 3px solid;">'.number_format(($total_actual_value/10000000), 2, '.', ',').'</td>
                            <td class="text-right">'.$completion.'</td>
                            <td class="text-right" '.$b2p.'-'.$overallB2PMin.'>'.(sprintf("%.2f",($TotalB2P * 100))).'</td>
                    </tr>';
            } 
           $hTotalOrder += $row->totalOrder; 
           $hTotalQty += $row->total_qty; 
           $hTotalOrderMin += $row->totalOrderMin; 
           $hTotalOrderValuee += $row->totalOrderValue; 
           $hTotalProducedQty += $row->produced_qty; 
           $hTotalProducedMin += $row->total_prod_min; 
           $hTotalProducedValue += $row->produced_value; 
           $hTotalBalProducedQty += $total_actual_qty; 
           $hTotalBalProducedMin += $total_actual_min; 
           $hTotalBalProducedValue += $total_actual_value; 
           $hTotalB2P += sprintf("%.2f",($TotalB2P * 100)); 
           $hTotalSalesValue += number_format(($row->total_sales_qty)/100000, 2, '.', ','); 
           $hTotalSalesMin += number_format(($row->total_sales_min)/100000, 2, '.', ','); 
           $hTotalSalesAmount += number_format(($row->total_sales_amount)/10000000, 2, '.', ','); 
        }
        
        $order_per1 = round(($hTotalProducedQty/100000),2);
        $order_per2 = round(($hTotalQty/100000),2);
        if($order_per1 > 0 && $order_per2 > 0)
        { 
            $order_per = ($order_per1/$order_per2) * 100;
        }
        else
        {
            $order_per = 0;
        }
        
        if($hTotalB2P > 0 && $overallB2PMin > 0)
        { 
            $B2P_avg = $hTotalB2P;
        }
        else
        {
            $B2P_avg = 0;
        }
        
        
        $html1 .= '<tr style="border: 3px solid;"> 
                    <td class="text-right" style="border-right: 3px solid;"></td> 
                    <td class="text-right" style="border-right: 3px solid;"></td> 
                    <td class="text-right" style="border-right: 3px solid;"><b>Total:</b></td>
                    <td class="text-center sticky-column"><b>'.$hTotalOrder.'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalQty/100000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalOrderMin/100000, 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format($hTotalOrderValuee/10000000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalProducedQty/100000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalProducedMin)/100000, 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format(($hTotalProducedValue)/10000000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalSalesValue), 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalSalesMin), 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format(($hTotalSalesAmount), 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format($hTotalBalProducedQty/100000, 2, '.', ',').'</b></td>
                    <td class="text-center"><b>'.number_format(($hTotalBalProducedMin)/100000, 2, '.', ',').'</b></td>
                    <td class="text-center" style="border-right: 3px solid;"><b>'.number_format(($hTotalBalProducedValue)/10000000, 2, '.', ',').'</b></td>
                    <td class="text-right"><b>'.round($order_per,2).'</b></td>
                    <td class="text-right"><b>'.(int)$B2P_avg.'.00</b></td>
                </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
    
    public function getMonthsToEndOfFinancialYear() 
    {
        // Current date
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
    
        // Assuming the financial year ends in March
        $endMonth = 3; // March
        $endYear = ($currentMonth <= $endMonth) ? $currentYear : $currentYear + 1;
    
        $months = [];
    
        for ($i = $currentMonth; $i <= 12; $i++) {
            $months[] = Carbon::create()->month($i)->format('F');
        }
    
        for ($i = 1; $i <= $endMonth; $i++) {
            $months[] = Carbon::create()->month($i)->year($endYear)->format('F');
        }
    
        return $months;
    }

    public function getYearsToEndOfFinancialYear() 
    {
        // Current date
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
    
        // Assuming the financial year ends in March
        $endMonth = 3; // March
        $endYear = ($currentMonth <= $endMonth) ? $currentYear : $currentYear + 1;
    
        // Array to store years
        $years = [];
    
        // Add months from the current month to December
        for ($i = $currentMonth; $i <= 12; $i++) {
            $years[] = $currentYear;
        }
    
        // Add months from January to the end of the financial year (March)
        for ($i = 1; $i <= $endMonth; $i++) {
            $years[] = $endYear;
        }
    
        return $years;
    }
    
    function getYearMonthArrayToEndOfFinancialYear() 
    {
        // Current date
        $currentDate = Carbon::now();
        $currentMonth = $currentDate->month;
        $currentYear = $currentDate->year;
    
        // Assuming the financial year ends in March
        $endMonth = 3; // March
        $endYear = ($currentMonth <= $endMonth) ? $currentYear : $currentYear + 1;
    
        // Array to store year-month combinations
        $yearMonthArray = [];
    
        // Add months from the current month to December
        for ($month = $currentMonth; $month <= 12; $month++) {
            $yearMonthArray[] = $currentYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        }
    
        // Add months from January to the end of the financial year (March)
        for ($month = 1; $month <= $endMonth; $month++) {
            $yearMonthArray[] = $endYear . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
        }
    
        return $yearMonthArray;
    }
    
    public function MonthlyShipmentTargetReport(Request $request)
    {
        $months = $this->getMonthsToEndOfFinancialYear();
        $years = $this->getYearsToEndOfFinancialYear();
        
        return view('MonthlyShipmentTargetReport', compact('months','years'));
      
    }
    
    public function LoadMonthlyShipmentTargetReport(Request $request)
    {   
        $order_received_date = date("Y-m-d");
        $months = $this->getMonthsToEndOfFinancialYear();
        $yearMonthArray = $this->getYearMonthArrayToEndOfFinancialYear();
        //DB::enableQueryLog();
            
            $Buyer_Purchase_Order_List = DB::SELECT("
                SELECT 
                    buyer_purchse_order_master.orderCategoryId,
                    buyer_purchse_order_master.sam,
                    buyer_purchse_order_master.tr_code,
                    buyer_purchse_order_master.Ac_code,
                    order_group_name,
                    OrderCategoryShortName,
                    ledger_master.ac_code,
                    ledger_master.ac_short_name AS buyer_name,
                    COUNT(*) AS totalOrder,
                    SUM(buyer_purchse_order_master.total_qty) AS total_qty,
                    SUM(buyer_purchse_order_master.total_qty * buyer_purchse_order_master.sam) AS totalOrderMin,
                    SUM(buyer_purchse_order_master.order_value) AS totalOrderValue,
                    (
                        SELECT 
                            SUM((
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) - B1.total_qty)
                        FROM 
                            buyer_purchse_order_master as B1 
                        WHERE 
                            B1.delflag = 0 
                            AND B1.og_id != 4  
                            AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                            AND B1.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                            AND (
                                order_close_date = '".$order_received_date."' 
                                OR (
                                    order_received_date <= '".$order_received_date."' 
                                    AND B1.job_status_id IN (1) 
                                    
                                    AND (
                                        SELECT SUM(total_qty) 
                                        FROM cut_panel_grn_master 
                                        WHERE sales_order_no = B1.tr_code
                                    ) >= B1.total_qty
                                )
                            )
                            AND (
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) >= B1.total_qty 
                            
                    ) AS total_excess_qty, 
                    (
                        SELECT 
                            SUM((((
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) - B1.total_qty) * B1.sam))
                        FROM 
                            buyer_purchse_order_master as B1 
                        WHERE 
                            B1.delflag = 0 
                            AND B1.og_id != 4  
                            AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                            AND B1.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                            AND (
                                order_close_date = '".$order_received_date."' 
                                OR (
                                    order_received_date <= '".$order_received_date."' 
                                    AND B1.job_status_id IN (1) 
                                    
                                    AND (
                                        SELECT SUM(total_qty) 
                                        FROM cut_panel_grn_master 
                                        WHERE sales_order_no = B1.tr_code
                                    ) >= B1.total_qty
                                )
                            )
                            AND (
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) >= B1.total_qty 
                            
                    ) AS total_excess_min,  
                    (
                        SELECT 
                            SUM((((
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) - B1.total_qty) * B1.order_rate))
                        FROM 
                            buyer_purchse_order_master as B1 
                        WHERE 
                            B1.delflag = 0 
                            AND B1.og_id != 4  
                            AND B1.Ac_code = buyer_purchse_order_master.Ac_code
                            AND B1.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                            AND (
                                order_close_date = '".$order_received_date."' 
                                OR (
                                    order_received_date <= '".$order_received_date."' 
                                    AND B1.job_status_id IN (1) 
                                    
                                    AND (
                                        SELECT SUM(total_qty) 
                                        FROM cut_panel_grn_master 
                                        WHERE sales_order_no = B1.tr_code
                                    ) >= B1.total_qty
                                )
                            )
                            AND (
                                SELECT SUM(total_qty) 
                                FROM cut_panel_grn_master 
                                WHERE sales_order_no = B1.tr_code
                            ) >= B1.total_qty 
                            
                    ) AS total_excess_value, 
                    SUM(
                        (SELECT IFNULL(SUM(total_qty), 0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code)
                    ) AS produced_qty,
                    SUM(
                        (SELECT IFNULL(SUM(total_qty), 0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) * sam
                    ) AS total_prod_min,
                    SUM(
                        (SELECT IFNULL(SUM(total_qty), 0) FROM stitching_inhouse_master WHERE stitching_inhouse_master.sales_order_no = buyer_purchse_order_master.tr_code) * order_rate
                    ) AS produced_value,
                    SUM(
                        (SELECT IFNULL(SUM(order_qty), 0) FROM sale_transaction_detail WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code)
                    ) AS total_sales_qty,
                    SUM(
                        (SELECT IFNULL(SUM(order_qty), 0) FROM sale_transaction_detail WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code) * sam
                    ) AS total_sales_min,
                    SUM(
                        (SELECT IFNULL(SUM(amount), 0) FROM sale_transaction_detail WHERE sale_transaction_detail.sales_order_no = buyer_purchse_order_master.tr_code)
                    ) AS total_sales_amount,
                    SUM(
                        (SELECT IFNULL(SUM(adjust_qty), 0) FROM buyer_purchase_order_detail WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code)
                    ) AS short_close_qty,
                    SUM(
                        (SELECT IFNULL(SUM(adjust_qty), 0) FROM buyer_purchase_order_detail WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code) * sam
                    ) AS short_close_min,
                    SUM(
                        (SELECT IFNULL(SUM(adjust_qty), 0) FROM buyer_purchase_order_detail WHERE buyer_purchase_order_detail.tr_code = buyer_purchse_order_master.tr_code) * order_rate
                    ) AS short_close_value
                FROM 
                    buyer_purchse_order_master 
                INNER JOIN 
                    ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                LEFT JOIN 
                    order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                LEFT JOIN 
                    order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                WHERE 
                    (buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4 
                    AND order_close_date = '".$order_received_date."') 
                    OR 
                    (order_received_date <= '".$order_received_date."' 
                    AND buyer_purchse_order_master.delflag = 0 
                    AND buyer_purchse_order_master.og_id != 4 
                    AND buyer_purchse_order_master.job_status_id IN(1)) 
                GROUP BY 
                    buyer_purchse_order_master.Ac_code 
                ORDER BY 
                    totalOrder DESC
            ");
 
           // dd(DB::getQueryLog());    
        $srno = 1;
        $html = '';
          
        foreach($Buyer_Purchase_Order_List as $row)
        {
            
            $short_close_qty = $row->short_close_qty;
            $short_close_min = $row->short_close_min;
            $short_close_value = $row->short_close_value;
            
            
            $total_excess_qty = $row->total_excess_qty;
        
            $total_excess_min = $row->total_excess_min;
     
            $total_excess_value =  $row->total_excess_value;
               
            
            $total_actual_qty = ($row->total_qty - $row->produced_qty - $short_close_qty) + $total_excess_qty;
            $total_actual_min = ($row->totalOrderMin - $row->total_prod_min - $short_close_min) + $total_excess_min;
            $total_actual_value = ($row->totalOrderValue - $row->produced_value - $short_close_value)+$total_excess_value;
            
            $html .= '<tr> 
                        <td class="text-center" style="border-right: 0.5px solid;background:#0000ff59;color:#fff;">'.($srno++).'</td>
                        <td style="border-right: 0.5px solid;background:#3bc3907a;">'.$row->buyer_name.'</td> 
                        <td class="text-center" style="background:#f1b44c66;">'.number_format(($total_actual_qty/100000), 2, '.', ',').'</td>
                        <td class="text-center" style="background:#f1b44c66;">'.number_format(($total_actual_min/100000), 2, '.', ',').'</td>
                        <td class="text-center" style="border-right: 0.5px solid;background:#f1b44c66;">'.number_format(($total_actual_value/10000000), 2, '.', ',').'</td>';
                        $TotalPlanB2PLPcs = 0; 
                        $TotalPlanB2PLMin = 0;
                        $TotalPlanB2PLCr = 0;
                        foreach($yearMonthArray as $mn)
                        { 
                            $monthlyShipData = DB::SELECT("SELECT sum(targetQty * sam) as prod_min,sum(targetQty) as prod_qty,sum(targetQty * orderRate) as prod_value FROM monthly_shipment_target_detail WHERE buyer_code = ".$row->Ac_code." AND monthDate = '".$mn."'");
                            $prod_qty = isset($monthlyShipData[0]->prod_qty) ? $monthlyShipData[0]->prod_qty : 0;
                            $prod_min = isset($monthlyShipData[0]->prod_min) ? $monthlyShipData[0]->prod_min : 0;
                            $prod_value = isset($monthlyShipData[0]->prod_value) ? $monthlyShipData[0]->prod_value : 0;
                            $html .= '<td class="text-center" style="border-right: 0.5px solid;background:#343a4082;color:#fff;">'.number_format(($prod_min/100000), 2, '.', ',').'</td>';
                            $TotalPlanB2PLPcs += $prod_qty;
                            $TotalPlanB2PLMin += $prod_min;
                            $TotalPlanB2PLCr += $prod_value;
                        }
                    $html .= '<td class="text-center" style="background:#f1b44c66;">'.number_format(($TotalPlanB2PLPcs/100000), 2, '.', ',').'</td>
                              <td class="text-center" style="background:#f1b44c66;">'.number_format(($TotalPlanB2PLMin/100000), 2, '.', ',').'</td>
                              <td class="text-center" style="border-right: 0.5px solid;background:#f1b44c66;">'.number_format(($TotalPlanB2PLCr/10000000), 2, '.', ',').'</td>
                              <td class="text-center" style="border-right: 0.5px solid;background:#ff00006e;">'.number_format((($total_actual_qty - $TotalPlanB2PLPcs)/100000), 2, '.', ',').'</td>
                              <td class="text-center" style="border-right: 0.5px solid;background:#ff00006e;">'.number_format((($total_actual_min - $TotalPlanB2PLMin)/100000), 2, '.', ',').'</td>
                              <td class="text-center" style="border-right: 0.5px solid;background:#ff00006e;">'.number_format((($total_actual_value - $TotalPlanB2PLCr)/10000000), 2, '.', ',').'</td>
                        </tr>';
        
        }
         
        
        return response()->json(['html' => $html]);
    }
        
    public function GetOpenOrderSummaryReport(Request $request)
    {   
        $order_received_date = $request->OpenOrderTrDate;
        //DB::enableQueryLog();

        $Buyer_Purchase_Order_List = DB::SELECT("SELECT  order_group_name,OrderCategoryName, sum(total_qty * sam) as totalOrderMin, 
                sum((select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) * sam)  as total_prod_min
                FROM buyer_purchse_order_master 
                INNER JOIN ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId
                LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND order_close_date = '".$order_received_date."' 
                OR order_received_date <= '".$order_received_date."' AND  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id IN(1)
                GROUP BY buyer_purchse_order_master.orderCategoryId");
                
        $B2PData = DB::SELECT("SELECT  sum(total_qty * sam) as totalOrderMin, 
                sum((select ifnull(sum(total_qty),0) from stitching_inhouse_master where stitching_inhouse_master.sales_order_no=buyer_purchse_order_master.tr_code) * sam)  as total_prod_min
                FROM buyer_purchse_order_master 
                WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND order_close_date = '".$order_received_date."' 
                OR order_received_date <= '".$order_received_date."' AND  buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 AND buyer_purchse_order_master.job_status_id IN(1)");
                
        $TOM = isset($B2PData[0]->totalOrderMin) ? $B2PData[0]->totalOrderMin : 0;       
        $TPM = isset($B2PData[0]->total_prod_min) ? $B2PData[0]->total_prod_min : 0;    
        
        $overallB2PMin = sprintf("%.2f",($TOM - $TPM)/100000);        
                
           // dd(DB::getQueryLog());    
        $srno = 1;
        $html = ''; 
        $hTotalOrderMin = 0;  
        $hTotalProducedMin = 0;   
        $hTotalBalProducedMin = 0;  
        $hTotalOrderMinPer = 0;  
        $hTotalProducedMinPer = 0;   
        $hTotalBalProducedMinPer = 0;  
        
        $html = '<table  class="table dt-datatable table-bordered nowrap w-100">
                          <thead> 
                             <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                <th class="text-center">Sr. No.</th>
                                <th class="text-center">Type</th> 
                                <th class="text-center">Open L Min</th>
                                <th class="text-center">% Open L Min</th> 
                                <th class="text-center">Produce L Min</th>
                                <th class="text-center">% Produce L Min</th> 
                                <th class="text-center">Balance L Min</th>
                                <th class="text-center">% Balance L Min</th> 
                             </tr>
                          </thead>
                          <tbody>';
        
        foreach($Buyer_Purchase_Order_List as $row)
        {    
           $hTotalOrderMin += $row->totalOrderMin; 
           $hTotalProducedMin += $row->total_prod_min; 
           $hTotalBalProducedMin += $row->totalOrderMin - $row->total_prod_min; 
        }
        
        foreach($Buyer_Purchase_Order_List as $row)
        {   
            $first_character =  $row->order_group_name;
            $balance = round((sprintf("%.2f", ($row->totalOrderMin - $row->total_prod_min))/$hTotalBalProducedMin),2) * 100;
            $html .= '<tr> 
                        <td class="text-center">'.($srno++).'</td>  
                        <td >'.$first_character."-".$row->OrderCategoryName.'</td> 
                        <td class="text-center">'.sprintf("%.2f", $row->totalOrderMin/100000).'</td>    
                        <td class="text-center">'.sprintf("%.2f", ($row->totalOrderMin/$hTotalOrderMin) * 100).'</td> 
                        <td class="text-center">'.sprintf("%.2f", ($row->total_prod_min)/100000).'</td> 
                        <td class="text-center">'.sprintf("%.2f", ($row->total_prod_min/$hTotalProducedMin) * 100).'</td> 
                        <td class="text-center">'.sprintf("%.2f", ($row->totalOrderMin - $row->total_prod_min)/100000).'</td> 
                        <td class="text-center">'.sprintf("%.2f", $balance).'</td> 
                    </tr>';
                     
           $hTotalOrderMinPer += sprintf("%.2f", ($row->totalOrderMin/$hTotalOrderMin));
           $hTotalProducedMinPer += sprintf("%.2f", ($row->total_prod_min/$hTotalProducedMin));
           $hTotalBalProducedMinPer += $balance;
        }
     
        
     $html .= '</tbody>
                    <tfoot>
                         <tr> 
                            <th  nowrap class="text-right"></th>
                            <th  nowrap class="text-right">Total : </th>
                            <th class="text-center">'.sprintf("%.2f", $hTotalOrderMin/100000).'</th>
                            <th class="text-center">'.sprintf("%.2f", $hTotalOrderMinPer * 100).'</th>
                            <th class="text-center">'.sprintf("%.2f", $hTotalProducedMin/100000).'</th>
                            <th class="text-center">'.sprintf("%.2f", $hTotalProducedMinPer * 100).'</th>
                            <th class="text-center">'.sprintf("%.2f", $hTotalBalProducedMin/100000).'</th>
                            <th class="text-center">'.sprintf("%.2f", $hTotalBalProducedMinPer).'</th>
                         </tr> 
                     </tfoot>
                     </table>';
        return response()->json(['html' => $html]);
    }
    
    public function TotalSalesOrderDetailDashboard(Request $request)
    {
         
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
         $receivedFromDate = $request->receivedFromDate ? $request->receivedFromDate : date("Y-m-01");
         $receivedToDate = $request->receivedToDate ?  $request->receivedToDate  : date("Y-m-d");
         $OrderFromDate = $request->OrderFromDate;
         $OrderToDate = $request->OrderToDate; 
         $Ac_code = $request->ac_code;
         $po_code = $request->po_code;
         $sales_order_no = $request->sales_order_no;
         $brand_id = $request->brand_id;
         $mainstyle_id = $request->mainstyle_id;
         $fg_id = $request->fg_id;
         $orderTypeId = $request->orderTypeId;
         $merchant_id =  $request->merchant_id;
         $job_status_id =  $request->job_status_id;
         
         $filter = "";
         
         if($receivedFromDate != "" && $receivedToDate != "")
         {
            $filter .= " AND buyer_purchse_order_master.order_received_date BETWEEN '".$receivedFromDate."' AND '".$receivedToDate."'";
         }
         
         if($OrderFromDate != "" && $OrderToDate != "")
         {
            $filter .= " AND buyer_purchse_order_master.order_close_date BETWEEN '".$OrderFromDate."' AND '".$OrderToDate."'";
         }
         
         
         if($Ac_code != "")
         {
            $filter .= " AND buyer_purchse_order_master.Ac_code='".$Ac_code."'";
         }
         
         if($po_code != "")
         {
            $filter .= " AND buyer_purchse_order_master.po_code='".$po_code."'";
         }
        
         if($sales_order_no != "")
         {
            $filter .= " AND buyer_purchse_order_master.tr_code='".$sales_order_no."'";
         }
         
         if($job_status_id != "") 
         {
             $filter .= " AND buyer_purchse_order_master.job_status_id='".$job_status_id."'"; 
         }
         
         if($brand_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.brand_id='".$brand_id."'";
         }
         
         if($mainstyle_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.mainstyle_id='".$mainstyle_id."'";
         }
         
         if($fg_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.fg_id='".$fg_id."'";
         } 
         
         if($orderTypeId != "")
         {
            $filter .= " AND buyer_purchse_order_master.order_type='".$orderTypeId."'";
         }
         
         if($merchant_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.merchant_id='".$merchant_id."'";
         }
         
            //DB::enableQueryLog();
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        // ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.og_id','!=', '4')
        // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name',
        // 'fg_master.fg_name','merchant_master.merchant_name','brand_master.brand_name',
        // 'job_status_master.job_status_name','main_style_master.mainstyle_name',
        //  DB::raw("(select consumption FROM sales_order_fabric_costing_details WHERE sales_order_fabric_costing_details.sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_fabric_costing_details.sales_order_no) as consumption")]);

        $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,order_category.OrderCategoryName, usermaster.username,ledger_master.ac_short_name,fg_master.fg_name,merchant_master.merchant_name,brand_master.brand_name,
                                    job_status_master.job_status_name,main_style_master.mainstyle_name,order_group_master.order_group_name,sub_style_master.substyle_name,
                                    (select consumption FROM sales_order_fabric_costing_details WHERE sales_order_fabric_costing_details.sales_order_no = buyer_purchse_order_master.tr_code GROUP BY sales_order_fabric_costing_details.sales_order_no) as consumption
                                    FROM buyer_purchse_order_master INNER JOIN usermaster ON usermaster.userId = buyer_purchse_order_master.userId
                                    LEFT JOIN merchant_master ON merchant_master.merchant_id=buyer_purchse_order_master.merchant_id
                                    INNER JOIN ledger_master ON ledger_master.Ac_code=buyer_purchse_order_master.Ac_code 
                                    LEFT JOIN brand_master ON brand_master.brand_id=buyer_purchse_order_master.brand_id
                                    LEFT JOIN main_style_master ON main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
                                    INNER JOIN fg_master ON fg_master.fg_id=buyer_purchse_order_master.fg_id
                                    INNER JOIN sub_style_master ON sub_style_master.substyle_id=buyer_purchse_order_master.substyle_id
                                    INNER JOIN job_status_master ON job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
                                    LEFT JOIN order_group_master ON order_group_master.og_id=buyer_purchse_order_master.og_id
                                    LEFT JOIN order_category ON order_category.orderCategoryId=buyer_purchse_order_master.orderCategoryId
                                    WHERE buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 ".$filter);

        $total_valuec=0;
        $total_qtyc=0;
        $open_qtyc=0;
        $shipped_qtyc=0;
        foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        $NoOfOrderc=count($Buyer_Purchase_Order_List);
     
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4");
        $jobStatusList = DB::SELECT("SELECT job_status_id,job_status_name FROM job_status_master WHERE delflag = 0");
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $styleList = DB::SELECT("SELECT fg_id,fg_name FROM fg_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");
        $orderTypeList = DB::SELECT("SELECT orderTypeId,order_type FROM order_type_master WHERE delflag = 0");
        $merchantList = DB::SELECT("SELECT merchant_id,merchant_name FROM merchant_master WHERE delflag = 0");
        $poList = DB::SELECT("SELECT po_code FROM buyer_purchse_order_master WHERE delflag = 0 GROUP BY buyer_purchse_order_master.po_code");
        
        return view('TotalSalesOrderDetailDashboard', compact('Buyer_Purchase_Order_List','salesOrderList','jobStatusList','brandList','styleList','mainStyleList','buyerList','poList','merchantList','chekform','orderTypeId','job_status_id','NoOfOrderc','total_valuec','total_qtyc','open_qtyc','shipped_qtyc','Ac_code','po_code','sales_order_no','brand_id','mainstyle_id','fg_id','orderTypeList','merchant_id','receivedFromDate','receivedToDate','OrderFromDate','OrderToDate'));
  
         
     }
     
    public function MonthlyOrderStatusReport(Request $request)
    { 
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master");
        
        if($request->fin_year_id > 0)
        {
            $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : 4;
        
            $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=".$fin_year_id);
            // DB::enableQueryLog();
            $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,ledger_master.ac_short_name,
                                        (select sum(B1.total_qty) FROM buyer_purchse_order_master as B1 WHERE B1.order_received_date NOT between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND B1.Ac_code= buyer_purchse_order_master.Ac_code AND B1.delflag = 0  AND B1.job_status_id != 3 AND B1.og_id != 4) as opening_qty,
                                        (select sum(B1.total_qty * B1.sam) FROM buyer_purchse_order_master as B1 WHERE B1.order_received_date NOT between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND B1.Ac_code= buyer_purchse_order_master.Ac_code AND B1.delflag = 0  AND B1.job_status_id != 3 AND B1.og_id != 4) as opening_min,
                                        (select sum(B1.total_qty * B1.order_rate) FROM buyer_purchse_order_master as B1 WHERE B1.order_received_date NOT between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND B1.Ac_code= buyer_purchse_order_master.Ac_code  AND B1.job_status_id != 3 AND B1.delflag = 0 AND B1.og_id != 4) as opening_value
                                        FROM buyer_purchse_order_master 
                                        INNER JOIN ledger_master ON ledger_master.Ac_code=buyer_purchse_order_master.Ac_code 
                                        WHERE buyer_purchse_order_master.order_received_date   between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4  AND buyer_purchse_order_master.job_status_id != 3 GROUP BY buyer_purchse_order_master.Ac_code");
    
          
            //dd(DB::getQueryLog());
            $financialYearMonths  = $this->getFinancialYear();
            $from = $Financial_Year[0]->fdate;
            $to = $Financial_Year[0]->tdate; 
        }
        else
        {
            $fin_year_id = 0;
            $Financial_Year=DB::select("SELECT MIN(order_received_date) as fdate, MAX(order_received_date) as tdate FROM buyer_purchse_order_master where delflag=0 AND og_id !=4");
             
            $Buyer_Purchase_Order_List = DB::SELECT("SELECT buyer_purchse_order_master.*,ledger_master.ac_short_name,
                                        (select sum(B1.total_qty) FROM buyer_purchse_order_master as B1 WHERE B1.order_received_date NOT  between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND B1.Ac_code= buyer_purchse_order_master.Ac_code AND B1.delflag = 0 AND B1.og_id != 4) as opening_qty,
                                        (select sum(B1.total_qty * B1.sam) FROM buyer_purchse_order_master as B1 WHERE B1.order_received_date NOT between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND B1.Ac_code= buyer_purchse_order_master.Ac_code AND B1.delflag = 0 AND B1.og_id != 4) as opening_min,
                                        (select sum(B1.total_qty * B1.order_rate) FROM buyer_purchse_order_master as B1 WHERE B1.order_received_date NOT between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND B1.Ac_code= buyer_purchse_order_master.Ac_code AND B1.delflag = 0 AND B1.og_id != 4) as opening_value
                                        FROM buyer_purchse_order_master 
                                        INNER JOIN ledger_master ON ledger_master.Ac_code=buyer_purchse_order_master.Ac_code 
                                        WHERE buyer_purchse_order_master.order_received_date   between '".$Financial_Year[0]->fdate."' and '".$Financial_Year[0]->tdate."' AND buyer_purchse_order_master.delflag = 0 AND buyer_purchse_order_master.og_id != 4 GROUP BY buyer_purchse_order_master.Ac_code");
    
          
       
            $financialYearMonths  = $this->getFinancialYear();
            $from = $Financial_Year[0]->fdate;
            $to = $Financial_Year[0]->tdate;
        }
       
        return view('MonthlyOrderStatusReport', compact('financialYearMonths','Buyer_Purchase_Order_List','from','to','Financial_Year','Financial_Year1','fin_year_id'));
     }
     
    function getFinancialYear()
    {
       $financialMonths = array(
            "APR",
            "MAY",
            "JUN",
            "JUL",
            "AUG",
            "SEPT",
            "OCT",
            "NOV",
            "DEC",
            "JAN",
            "FEB",
            "MAR"
        );
    
        return $financialMonths;
    }  
    
    public function OrderBookingReport(Request $request)
    {
         
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '96')
        ->first();
        
        $from_date = isset($request->from_date) ? $request->from_date : date('Y-04-01');
        $to_date = isset($request->to_date) ? $request->to_date : date('Y-m-d');
  
        $Buyer_Purchase_Order_List = DB::SELECT("SELECT 
                b.*, 
                COALESCE(SUM(b.total_qty), 0) as total_qty,
                COALESCE(SUM(b.total_qty * b.sam), 0) as order_min,
                COALESCE(SUM(b.total_qty * b.order_rate), 0) as order_value, 
                SUM(((s.production_value + (b.order_rate - s.total_cost_value) + s.other_value)/b.sam) * (b.total_qty * b.sam)) as order_value1,
                ledger_master.ac_short_name,
                brand_master.brand_name,
                main_style_master.mainstyle_name,
                DATE_FORMAT(b.order_received_date, '%Y-%m') as order_month, 
                SUM(((s.production_value + (b.order_rate - s.total_cost_value) + s.other_value)/b.sam) * (b.total_qty * b.sam)) / sum(b.total_qty * b.sam)  as cmohp
            FROM 
                buyer_purchse_order_master b
            LEFT JOIN 
                ledger_master ON ledger_master.Ac_code = b.Ac_code 
            LEFT JOIN 
                brand_master ON brand_master.brand_id = b.brand_id
            LEFT JOIN 
                main_style_master ON main_style_master.mainstyle_id = b.mainstyle_id   
            LEFT JOIN 
                sales_order_costing_master s ON s.sales_order_no = b.tr_code 
            WHERE 
                b.delflag = 0 
                AND b.og_id != 4 
                AND b.order_received_date BETWEEN '".$from_date."' AND '".$to_date."' 
                AND b.order_type IN (1,3)
                AND b.job_status_id != 3
            GROUP BY 
                b.Ac_code, 
                b.brand_id, 
                b.mainstyle_id, 
                b.order_received_date, 
                order_month
            ORDER BY 
                b.order_received_date ASC");

        return view('OrderBookingReport', compact('Buyer_Purchase_Order_List', 'from_date', 'to_date'));
     }

    
    public function GetSalesOrderListFromVendor(Request $request)
    { 
       
        $html = '<option value="">--Select--</option>';
        
        $BuyerPurchaseList = DB::select("SELECT tr_code FROM buyer_purchse_order_master where og_id!=4  AND job_status_id IN(1)");
        
        foreach ($BuyerPurchaseList as $row) 
        {
            $html .= '<option value="'.$row->tr_code.'">'.$row->tr_code.'</option>';
        } 
        
        return response()->json(['html' => $html]);
    } 
    
    public function GetBuyerListFromVendor(Request $request)
    { 
       
        $html = '<option value="">--Select--</option>';
        
        $BuyerList = DB::select("SELECT ledger_master.ac_code, ledger_master.ac_name FROM ledger_master 
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.Ac_code = ledger_master.ac_code
                            where ledger_master.bt_id = 2 AND ledger_master.delflag=0 AND buyer_purchse_order_master.og_id!=4  
                            AND buyer_purchse_order_master.job_status_id IN(1) GROUP BY buyer_purchse_order_master.Ac_code");
        
        foreach ($BuyerList as $row) 
        {
            $html .= '<option value="'.$row->ac_code.'">'.$row->ac_name.'</option>';
        } 
        
        return response()->json(['html' => $html]);
    } 
    
    public function GetMainStyleListFromVendor(Request $request)
    { 
       
        $html = '<option value="">--Select--</option>';
        
        $mainStyleList = DB::select("SELECT main_style_master.mainstyle_id, mainstyle_name FROM main_style_master  
                            INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.mainstyle_id = main_style_master.mainstyle_id
                            where  main_style_master.delflag=0 AND buyer_purchse_order_master.og_id!=4  
                            AND buyer_purchse_order_master.job_status_id IN(1) GROUP BY buyer_purchse_order_master.mainstyle_id");
        
        foreach ($mainStyleList as $row) 
        {
            $html .= '<option value="'.$row->mainstyle_id.'">'.$row->mainstyle_name.'</option>';
        } 
        
        return response()->json(['html' => $html]);
    } 
    
    public function DemoExcel(Request $request)
    {  
          return view('DemoExcel');
    } 
    
    public function DExcel1(Request $request)
    {  
          return view('DExcel1');
    } 
    
    public function GaneshExcel(Request $request)
    {  
          return view('GaneshExcel');
    } 
    
    public function MaterialIssueReport(Request $request)
    {
        
        ini_set('memory_limit', '10G'); 
        
        $vendorList = DB::table('ledger_master')->where('bt_id', '=', 4)->where('delflag', '=', 0)->get();
        $brandList = DB::table('brand_master')->where('delflag', '=', 0)->get();
        $salesOrderList = DB::table('buyer_purchse_order_master')->where('delflag', '=', 0)->where('og_id', '!=', 4)->get();
        $vpoList = DB::table('vendor_purchase_order_master') 
                ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','vendor_purchase_order_master.sales_order_no')
                ->select('vpo_code')
                ->where('vendor_purchase_order_master.delflag', '=', 0)
                ->where('buyer_purchse_order_master.job_status_id', '=', 1) 
                ->whereNotIn('process_id', [2, 4, 5, 6])
                ->union(
                    DB::table('vendor_work_order_master')
                        ->join('buyer_purchse_order_master','buyer_purchse_order_master.tr_code','=','vendor_work_order_master.sales_order_no')
                        ->select('vw_code as vpo_code')
                        ->where('vendor_work_order_master.delflag', '=', 0) 
                        ->where('buyer_purchse_order_master.job_status_id', '=', 1) 
                )
                ->get(); 
                
        $jobStatusList = DB::table('job_status_master')->WhereIn('job_status_id',[1,2])->where('delflag', '=', 0)->get();

        $vendorId = isset($request->vendorId) ? $request->vendorId : 0;
        $job_status_id = isset($request->job_status_id) ? $request->job_status_id : 0;
        $brand_id = isset($request->brand_id) ? $request->brand_id : 0;
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : '';
        $vpo_code = isset($request->vpo_code) ? $request->vpo_code : '';
      
        return view('MaterialIssueReport', compact('vendorList', 'vendorId', 'brandList', 'brand_id','vpoList','sales_order_no','vpo_code', 'salesOrderList','jobStatusList','job_status_id'));
    }

    public function LoadMaterialIssueReport(Request $request)
    { 
        $vendorId = isset($request->vendorId) ? $request->vendorId : 0;
        $job_status_id = isset($request->job_status_id) ? $request->job_status_id : 1;
        $brand_id = isset($request->brand_id) ? $request->brand_id : 0;
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : '';
        $vpo_code = isset($request->vpo_code) ? $request->vpo_code : '';
        $btn = $request->btn;
        
        $filter1 = '';
        $filter2 = '';
        $filter3 = '';
        $filter4 = '';
        $filter5 = '';
        
        if($vendorId > 0)
        {
            $filter1 .= ' AND vendor_purchase_order_master.vendorId="'.$vendorId.'"';
            $filter2 .= ' AND vendor_purchase_order_master.vendorId="'.$vendorId.'"';
            $filter3 .= ' AND vendor_purchase_order_master.vendorId="'.$vendorId.'"';
            $filter5 .= ' AND vendor_work_order_master.vendorId="'.$vendorId.'"';
        }
        
        if($brand_id > 0)
        {
            $filter1 .= ' AND buyer_purchse_order_master.brand_id="'.$brand_id.'"';
            $filter2 .= ' AND buyer_purchse_order_master.brand_id="'.$brand_id.'"';
            $filter3 .= ' AND buyer_purchse_order_master.brand_id="'.$brand_id.'"';
            $filter5 .= ' AND buyer_purchse_order_master.brand_id="'.$brand_id.'"';
        }
        
        if($sales_order_no != '')
        {
            $filter1 .= ' AND vendor_purchase_order_fabric_details.sales_order_no="'.$sales_order_no.'"';
            $filter2 .= ' AND vendor_purchase_order_trim_fabric_details.sales_order_no="'.$sales_order_no.'"';
            $filter3 .= ' AND vendor_purchase_order_packing_trims_details.sales_order_no="'.$sales_order_no.'"';
            $filter5 .= ' AND vendor_work_order_sewing_trims_details.sales_order_no="'.$sales_order_no.'"';
        }
        if($vpo_code != '')
        {
            $filter1 .= ' AND vendor_purchase_order_fabric_details.vpo_code="'.$vpo_code.'"';
            $filter2 .= ' AND vendor_purchase_order_trim_fabric_details.vpo_code="'.$vpo_code.'"';
            $filter3 .= ' AND vendor_purchase_order_packing_trims_details.vpo_code="'.$vpo_code.'"';
            $filter5 .= ' AND vendor_work_order_master.vw_code="'.$vpo_code.'"';
        }
        
        if($vpo_code != '')
        {
            $filter4 .= ' AND fabric_outward_details.vpo_code="'.$vpo_code.'"';
        }
        
        // Fabric Data Query
        $fabricData = DB::SELECT("
            SELECT 
                vendor_purchase_order_fabric_details.sales_order_no,
                vendor_purchase_order_fabric_details.vpo_code,
                vendor_purchase_order_fabric_details.item_code,
                SUM(vendor_purchase_order_fabric_details.bom_qty) AS item_qty,
                item_master.item_name,
                item_master.item_description,
                classification_master.class_name,
                process_master.process_name,
                unit_master.unit_name,
                ledger_master.ac_short_name,
                brand_master.brand_name,
                LM1.ac_short_name AS vendor_name
            FROM vendor_purchase_order_fabric_details
            LEFT JOIN item_master ON item_master.item_code = vendor_purchase_order_fabric_details.item_code
            LEFT JOIN classification_master ON classification_master.class_id = vendor_purchase_order_fabric_details.class_id
            LEFT JOIN unit_master ON unit_master.unit_id = vendor_purchase_order_fabric_details.unit_id
            LEFT JOIN process_master ON process_master.process_id = vendor_purchase_order_fabric_details.process_id
            LEFT JOIN ledger_master ON ledger_master.ac_code = vendor_purchase_order_fabric_details.Ac_code
            LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_fabric_details.vpo_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = vendor_purchase_order_master.sales_order_no
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN ledger_master AS LM1 ON LM1.ac_code = vendor_purchase_order_master.vendorId WHERE  job_status_id= ".$job_status_id." AND og_id != 4 AND vendor_purchase_order_master.process_id NOT IN (2,4,3,5,6) AND og_id != 4 ".$filter1."
            GROUP BY vendor_purchase_order_fabric_details.vpo_code, vendor_purchase_order_fabric_details.item_code
        ");
        
        // Sewing Data Query
        $sewingData = DB::SELECT("
            SELECT 
                vendor_purchase_order_trim_fabric_details.sales_order_no,
                vendor_purchase_order_trim_fabric_details.vpo_code,
                vendor_purchase_order_trim_fabric_details.item_code,
                SUM(vendor_purchase_order_trim_fabric_details.bom_qty) AS item_qty,
                item_master.item_name,
                item_master.item_description,
                classification_master.class_name,
                process_master.process_name,
                unit_master.unit_name,
                ledger_master.ac_short_name,
                brand_master.brand_name,
                LM1.ac_short_name AS vendor_name
            FROM vendor_purchase_order_trim_fabric_details
            LEFT JOIN item_master ON item_master.item_code = vendor_purchase_order_trim_fabric_details.item_code
            LEFT JOIN classification_master ON classification_master.class_id = vendor_purchase_order_trim_fabric_details.class_id
            LEFT JOIN unit_master ON unit_master.unit_id = vendor_purchase_order_trim_fabric_details.unit_id
            LEFT JOIN process_master ON process_master.process_id = vendor_purchase_order_trim_fabric_details.process_id
            LEFT JOIN ledger_master ON ledger_master.ac_code = vendor_purchase_order_trim_fabric_details.Ac_code
            LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_trim_fabric_details.vpo_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = vendor_purchase_order_master.sales_order_no
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN ledger_master AS LM1 ON LM1.ac_code = vendor_purchase_order_master.vendorId  WHERE  job_status_id= ".$job_status_id." AND og_id != 4 AND  vendor_purchase_order_master.process_id NOT IN (2,4,5,6) AND og_id != 4 ".$filter2."
            GROUP BY vendor_purchase_order_trim_fabric_details.vpo_code, vendor_purchase_order_trim_fabric_details.item_code
        ");
        
        $packingData = DB::SELECT("
            SELECT 
                vendor_purchase_order_packing_trims_details.sales_order_no,
                vendor_purchase_order_packing_trims_details.vpo_code,
                vendor_purchase_order_packing_trims_details.item_code,
                SUM(vendor_purchase_order_packing_trims_details.bom_qty) AS item_qty,
                item_master.item_name,
                item_master.item_description,
                classification_master.class_name,
                vendor_purchase_order_packing_trims_details.description,
                process_master.process_name,
                unit_master.unit_name,
                ledger_master.ac_short_name,
                brand_master.brand_name,
                LM1.ac_short_name AS vendor_name
            FROM vendor_purchase_order_packing_trims_details
            LEFT JOIN item_master ON item_master.item_code = vendor_purchase_order_packing_trims_details.item_code
            LEFT JOIN classification_master ON classification_master.class_id = vendor_purchase_order_packing_trims_details.class_id
            LEFT JOIN unit_master ON unit_master.unit_id = vendor_purchase_order_packing_trims_details.unit_id
            LEFT JOIN process_master ON process_master.process_id = vendor_purchase_order_packing_trims_details.process_id
            LEFT JOIN ledger_master ON ledger_master.ac_code = vendor_purchase_order_packing_trims_details.Ac_code
            LEFT JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = vendor_purchase_order_packing_trims_details.vpo_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = vendor_purchase_order_master.sales_order_no
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN ledger_master AS LM1 ON LM1.ac_code = vendor_purchase_order_master.vendorId WHERE job_status_id= ".$job_status_id." AND og_id != 4 AND vendor_purchase_order_master.process_id NOT IN (2,4,5,6) AND og_id != 4 ".$filter3."
            GROUP BY vendor_purchase_order_packing_trims_details.vpo_code, vendor_purchase_order_packing_trims_details.item_code"); 

        $workOrderData = DB::SELECT("
            SELECT 
                vendor_work_order_sewing_trims_details.sales_order_no,
                vendor_work_order_sewing_trims_details.vw_code,
                vendor_work_order_sewing_trims_details.item_code,
                SUM(vendor_work_order_sewing_trims_details.bom_qty) AS item_qty,
                item_master.item_name,
                item_master.item_description,
                classification_master.class_name,
                vendor_work_order_sewing_trims_details.description,
                'Sewing' as process_name,
                unit_master.unit_name,
                ledger_master.ac_short_name,
                brand_master.brand_name,
                LM1.ac_short_name AS vendor_name
            FROM vendor_work_order_sewing_trims_details
            LEFT JOIN item_master ON item_master.item_code = vendor_work_order_sewing_trims_details.item_code
            LEFT JOIN classification_master ON classification_master.class_id = vendor_work_order_sewing_trims_details.class_id
            LEFT JOIN unit_master ON unit_master.unit_id = vendor_work_order_sewing_trims_details.unit_id
            LEFT JOIN ledger_master ON ledger_master.ac_code = vendor_work_order_sewing_trims_details.Ac_code
            LEFT JOIN vendor_work_order_master ON vendor_work_order_master.vw_code = vendor_work_order_sewing_trims_details.vw_code
            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = vendor_work_order_sewing_trims_details.sales_order_no
            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN ledger_master AS LM1 ON LM1.ac_code = vendor_work_order_master.vendorId WHERE job_status_id= ".$job_status_id." AND og_id != 4 ".$filter5."
            GROUP BY vendor_work_order_sewing_trims_details.vw_code, vendor_work_order_sewing_trims_details.item_code"); 
            

        $fabricData1 = DB::SELECT("SELECT vpo_code, item_code, IFNULL(SUM(meter), 0) as issue_qty FROM fabric_outward_details WHERE 1 GROUP BY vpo_code, item_code");
        $trimData1 = DB::SELECT("SELECT vpo_code, vw_code, item_code, IFNULL(SUM(item_qty), 0) as issue_qty FROM trimsOutwardDetail WHERE 1 GROUP BY vpo_code, vw_code, item_code");
    
        // Create a map for issue quantities for quick lookup
        $issueQtyMap = [];
        foreach ($fabricData1 as $issueRow) {
            $key = "{$issueRow->vpo_code}_{$issueRow->item_code}";
            $issueQtyMap[$key] = $issueRow->issue_qty;
        }
         
        foreach ($trimData1 as $issueRow1) {
            $key = "{$issueRow1->vw_code}_{$issueRow1->item_code}";
            $issueQtyMap[$key] = $issueRow1->issue_qty;
        }
        
        foreach ($trimData1 as $issueRow1) {
            $key = "{$issueRow1->vpo_code}_{$issueRow1->item_code}";
            $issueQtyMap[$key] = $issueRow1->issue_qty;
        }
    
        $mergedData = [];
    
        // Merge fabric data
        foreach ($fabricData as $row) {
            $key = "{$row->sales_order_no}_{$row->vpo_code}_{$row->item_code}";
            if (!isset($mergedData[$key])) {
                $mergedData[$key] = [
                    'sales_order_no' => $row->sales_order_no,
                    'vpo_code' => $row->vpo_code,
                    'item_code' => $row->item_code,
                    'ac_short_name' => $row->ac_short_name,
                    'brand_name' => $row->brand_name,
                    'vendor_name' => $row->vendor_name,
                    'process_name' => $row->process_name,
                    'class_name' => $row->class_name,
                    'item_name' => $row->item_name,
                    'item_description' => $row->item_description,
                    'unit_name' => $row->unit_name,
                    'items' => []
                ];
            }
            $mergedData[$key]['items'][] = [
                'item_qty' => $row->item_qty,
                'source' => 'fabric'
            ];
        }
    
        // Merge sewing data
        foreach ($sewingData as $row) {
            $key = "{$row->sales_order_no}_{$row->vpo_code}_{$row->item_code}";
            if (!isset($mergedData[$key])) {
                $mergedData[$key] = [
                    'sales_order_no' => $row->sales_order_no,
                    'vpo_code' => $row->vpo_code,
                    'item_code' => $row->item_code,
                    'ac_short_name' => $row->ac_short_name,
                    'brand_name' => $row->brand_name,
                    'vendor_name' => $row->vendor_name,
                    'process_name' => $row->process_name,
                    'class_name' => $row->class_name,
                    'item_name' => $row->item_name,
                    'item_description' => $row->item_description,
                    'unit_name' => $row->unit_name,
                    'items' => []
                ];
            }
            $mergedData[$key]['items'][] = [
                'item_qty' => $row->item_qty,
                'source' => 'sewing'
            ];
        }
    
        // Merge packing data
        foreach ($packingData as $row) {
            $key = "{$row->sales_order_no}_{$row->vpo_code}_{$row->item_code}";
            if (!isset($mergedData[$key])) {
                $mergedData[$key] = [
                    'sales_order_no' => $row->sales_order_no,
                    'vpo_code' => $row->vpo_code,
                    'item_code' => $row->item_code,
                    'ac_short_name' => $row->ac_short_name,
                    'brand_name' => $row->brand_name,
                    'vendor_name' => $row->vendor_name,
                    'process_name' => $row->process_name,
                    'class_name' => $row->class_name,
                    'item_name' => $row->item_name,
                    'item_description' => $row->item_description,
                    'unit_name' => $row->unit_name,
                    'items' => []
                ];
            }
            $mergedData[$key]['items'][] = [
                'item_qty' => $row->item_qty,
                'source' => 'packing'
            ];
        }
    
    
        // Merge packing data
        foreach ($workOrderData as $row) {
            $key = "{$row->vw_code}_{$row->item_code}";
            if (!isset($mergedData[$key])) {
                $mergedData[$key] = [
                    'sales_order_no' => $row->sales_order_no,
                    'vpo_code' => $row->vw_code,
                    'item_code' => $row->item_code,
                    'ac_short_name' => $row->ac_short_name,
                    'brand_name' => $row->brand_name,
                    'vendor_name' => $row->vendor_name,
                    'process_name' => $row->process_name,
                    'class_name' => $row->class_name,
                    'item_name' => $row->item_name,
                    'item_description' => $row->item_description,
                    'unit_name' => $row->unit_name,
                    'items' => []
                ];
            }
            $mergedData[$key]['items'][] = [
                'item_qty' => $row->item_qty,
                'source' => 'work'
            ];
        }
        
        // Reset keys for better readability
        $mergedData = array_values($mergedData);
    
        $html = [];
        $srno = 1;
    
        foreach ($mergedData as $row) 
        {
            $totalBomQty = array_reduce($row['items'], function ($carry, $item) {
                return $carry + $item['item_qty'];
            }, 0);
    
            $issueQtyKey = "{$row['vpo_code']}_{$row['item_code']}";
            $issueQty = isset($issueQtyMap[$issueQtyKey]) ? $issueQtyMap[$issueQtyKey] : 0;
            
            if($btn == 1)
            {
                
                $html[] = [
                    'srno' => $srno++,
                    'sales_order_no' => $row['sales_order_no'],
                    'vpo_code' => $row['vpo_code'],
                    'ac_short_name' => $row['ac_short_name'],
                    'brand_name' => $row['brand_name'],
                    'vendor_name' => $row['vendor_name'],
                    'item_code' => $row['item_code'],
                    'process_name' => $row['process_name'],
                    'class_name' => $row['class_name'],
                    'item_name' => $row['item_name'],
                    'item_description' => $row['item_description'],
                    'unit_name' => $row['unit_name'],
                    'item_qty' => number_format($totalBomQty, 0),
                    'issue_qty' => number_format($issueQty , 0),
                    'bal_qty' => number_format($totalBomQty - $issueQty, 0),  
                ];
            }
            else
            {
                if(($totalBomQty - $issueQty) >= 1)
                {
                    $html[] = [
                        'srno' => $srno++,
                        'sales_order_no' => $row['sales_order_no'],
                        'vpo_code' => $row['vpo_code'],
                        'ac_short_name' => $row['ac_short_name'],
                        'brand_name' => $row['brand_name'],
                        'vendor_name' => $row['vendor_name'],
                        'item_code' => $row['item_code'],
                        'process_name' => $row['process_name'],
                        'class_name' => $row['class_name'],
                        'item_name' => $row['item_name'],
                        'item_description' => $row['item_description'],
                        'unit_name' => $row['unit_name'],
                        'item_qty' => number_format($totalBomQty, 0),
                        'issue_qty' => number_format($issueQty , 0),
                        'bal_qty' => number_format($totalBomQty - $issueQty, 0),  
                    ];
                 }
            }
        }
    
        return response()->json(['html' => $html]);
    }

    public function FabricTrimsPOFollowUpReport(Request $request)
    {
        
        $from_date = isset($request->from_date) ? $request-> from_date : date("Y-m-01");
        $to_date = isset($request->to_date) ? $request-> to_date :  date("Y-m-d");
        $merchant_id = isset($request->merchant_id) ? $request->merchant_id : 0;
        $orderTypeId = isset($request->orderTypeId) ? $request->orderTypeId : 0;
        $Ac_code = isset($request->Ac_code) ? $request->Ac_code : 0;
        $brand_id = isset($request->brand_id) ? $request->brand_id : 0;
       
        $filter = '';
        
        if($from_date != '' && $to_date != '')
        {
            $filter .= ' AND bpom.order_received_date BETWEEN "'.$from_date.'" AND "'.$to_date.'"';
        }
        
        if($merchant_id > 0)
        {
            $filter .= ' AND bpom.merchant_id = '.$merchant_id;
        }
        
        if($orderTypeId > 0)
        {
            $filter .= ' AND bpom.order_type = '.$orderTypeId;
        }
        
        if($Ac_code > 0)
        {
            $filter .= ' AND bpom.Ac_code = '.$Ac_code;
        }
        
        if($brand_id > 0)
        {
            $filter .= ' AND bpom.brand_id = '.$brand_id;
        }
        
        
        $merchantList = DB::SELECT("SELECT * FROM merchant_master WHERE delflag=0");
        $OrderTypeList = DB::SELECT("SELECT * FROM order_type_master WHERE delflag=0 AND orderTypeId !=2");   
        $LedgerList = DB::SELECT("SELECT * FROM ledger_master WHERE delflag=0");   
        $BrandList = DB::SELECT("SELECT * FROM brand_master WHERE delflag=0");                    
           //DB::enableQueryLog();             
        $BuyerPurchaseList = DB::SELECT("WITH 
                                order_quantities AS (
                                    SELECT 
                                        tr_code, 
                                        color_id, 
                                        SUM(size_qty) AS order_qty
                                    FROM 
                                        buyer_purchase_order_size_detail
                                    GROUP BY 
                                        tr_code, color_id
                                ),
                                item_quantities AS (
                                    SELECT 
                                        sales_order_no AS tr_code, 
                                        item_code, 
                                        SUM(item_qty) AS total_item_qty
                                    FROM 
                                        purchaseorder_detail
                                    GROUP BY 
                                        sales_order_no, item_code
                                ),
                                purchase_order_data AS (
                                    SELECT 
                                        sales_order_no, 
                                        GROUP_CONCAT(DISTINCT pod.pur_code) AS pur_codes,  
                                        MIN(po.delivery_date) AS delivery_date, 
                                        pod.Ac_code AS Ac_code,
                                        SUM(pod.item_qty) AS total_item_qty
                                    FROM 
                                        purchaseorder_detail pod
                                    LEFT JOIN 
                                        purchase_order po 
                                    ON pod.pur_code = po.pur_code
                                    GROUP BY 
                                        sales_order_no, pod.Ac_code
                                )
                                SELECT 
                                    bpom.tr_code, 
                                    oq.order_qty, 
                                    iq.total_item_qty, 
                                    bpom.order_received_date, 
                                    mm.merchant_name,
                                    pdm.PDMerchant_name, 
                                    buyer_lm.ac_short_name AS buyer_name,  
                                    supplier_lm.ac_short_name AS supplier_name,   
                                    otm.order_type,
                                    bpom.shipment_date,
                                    msm.mainstyle_name, 
                                    bm.brand_name, 
                                    cm.color_name, 
                                    po.pur_codes,
                                    po.delivery_date
                                FROM 
                                    buyer_purchse_order_master AS bpom
                                INNER JOIN 
                                    buyer_purchase_order_size_detail AS bpod 
                                    ON bpod.tr_code = bpom.tr_code 
                                LEFT JOIN 
                                    order_quantities AS oq 
                                    ON oq.tr_code = bpod.tr_code AND oq.color_id = bpod.color_id
                                LEFT JOIN 
                                    item_quantities AS iq 
                                    ON iq.tr_code = bpod.tr_code AND iq.item_code = bpod.item_code
                                LEFT JOIN 
                                    merchant_master AS mm 
                                    ON mm.merchant_id = bpom.merchant_id
                                LEFT JOIN 
                                    PDMerchant_master AS pdm 
                                    ON pdm.PDMerchant_id = bpom.PDMerchant_id
                                LEFT JOIN 
                                    brand_master AS bm 
                                    ON bm.brand_id = bpom.brand_id
                                LEFT JOIN 
                                    ledger_master AS buyer_lm 
                                    ON buyer_lm.ac_code = bpom.Ac_code
                                LEFT JOIN 
                                    purchase_order_data AS po 
                                    ON po.sales_order_no = bpom.tr_code
                                LEFT JOIN 
                                    ledger_master AS supplier_lm 
                                    ON supplier_lm.ac_code = po.Ac_code
                                LEFT JOIN 
                                    color_master AS cm 
                                    ON cm.color_id = bpod.color_id
                                LEFT JOIN 
                                    main_style_master AS msm 
                                    ON msm.mainstyle_id = bpom.mainstyle_id
                                LEFT JOIN 
                                    order_type_master AS otm 
                                    ON otm.orderTypeId = bpom.order_type
                                WHERE 
                                    bpom.job_status_id = 1 
                                    AND bpom.og_id != 4 
                                    AND bpom.order_type != 2 
                                    {$filter}
                                GROUP BY 
                                    bpom.tr_code, bpod.color_id, bpod.item_code;
                                "); 
                                
            // $BuyerPurchaseList = DB::SELECT("SELECT buyer_purchse_order_master.order_received_date,buyer_purchse_order_master.shipment_date,buyer_purchse_order_master.tr_code,buyer_purchase_order_size_detail.item_code,
            //                         sum(buyer_purchase_order_size_detail.size_qty) as total_size_qty, buyer_purchase_order_size_detail.color_id,buyer_purchase_order_size_detail.tr_code,buyer_purchase_order_size_detail.Ac_code,buyer_purchse_order_master.order_type,
            //                         buyer_purchse_order_master.merchant_id,buyer_purchse_order_master.PDMerchant_id
            //                         FROM buyer_purchase_order_size_detail INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = buyer_purchase_order_size_detail.tr_code
            //                         WHERE buyer_purchse_order_master.job_status_id = 1 AND buyer_purchse_order_master.og_id !=4 AND buyer_purchse_order_master.order_type !=2 GROUP BY buyer_purchase_order_size_detail.tr_code, buyer_purchase_order_size_detail.color_id");                    
            // $master_Arr =[];
            // foreach($BuyerPurchaseList as $row)
            // {
            //     $master_Arr[$row->tr_code][] =  ['color_id' => $row->color_id, 'size_qty' => $row->total_size_qty];
            // }
            
            // echo '<pre>'; print_r($master_Arr);exit;
        //dd(DB::getQueryLog());
        return view('FabricTrimsPOFollowUpReport',compact('BuyerPurchaseList', 'filter', 'from_date','to_date','merchantList','OrderTypeList','LedgerList','BrandList','merchant_id','orderTypeId','Ac_code','brand_id')); 
    }
    
     
    public function UnitWiseDPRReport(Request $request)
    {      
        $LedgerList = DB::SELECT("SELECT * FROM ledger_master WHERE bt_id = 4 AND delflag=0");   
        $BrandList = DB::SELECT("SELECT * FROM brand_master WHERE delflag=0");    
        $SalesOrderList = DB::SELECT("SELECT * FROM buyer_purchse_order_master WHERE delflag=0 AND job_status_id = 1 AND og_id !=4");  
        
        return view('UnitWiseDPRReport',compact('LedgerList', 'BrandList', 'SalesOrderList'));
    } 
     
     
    public function LoadUnitWiseDPRReport(Request $request)
    { 
        $type = $request->type;
        $vendorId = $request->vendorId;
        $brand_id = $request->brand_id;
        $sales_order_no = $request->sales_order_no;
        $filter = '';
         
        if($brand_id > 0)
        {
            $filter .= " AND bpm.brand_id=".$brand_id;
        }
        
        if($sales_order_no != '')
        {
            $filter .= " AND bpm.tr_code='".$sales_order_no."'";
        }
            
        if($type == 1)
        {
            if($vendorId > 0)
            {
                $filter .= " AND cp.vendorId=".$vendorId;
            }
            
            $html = '<thead>
                      <tr>
                         <th>Order No</th>
                         <th>Vendor</th>
                         <th>Brand</th>
                         <th>Style Name</th>
                         <th>Color</th>
                         <th>Line No.</th>
                         <th>Cutting Issue</th>
                         <th>Stitching</th>
                         <th>Line/Bal</th>
                         <th>Cut to Stitch %</th>
                      </tr>
                   </thead>
                   <tbody>';
            
            $BuyerPurchaseData = DB::SELECT("SELECT bpm.tr_code, cm.color_id, cm.color_name, bm.brand_id, bm.brand_name, fm.fg_id, fm.fg_name, LM.ac_short_name AS vendorName,cp.vendorId,lm.line_name,lm.line_id,
                                            CASE 
                                                WHEN SUM(cp.size_qty) IS NULL THEN '' 
                                                ELSE SUM(cp.size_qty) 
                                            END AS cutting
                                        FROM buyer_purchse_order_master bpm 
                                        LEFT JOIN buyer_purchase_order_detail bpd 
                                            ON bpd.tr_code = bpm.tr_code 
                                        LEFT JOIN (
                                            SELECT 
                                                sales_order_no, 
                                                fg_id, 
                                                color_id, 
                                                vendorId, 
                                                line_id, 
                                                size_qty_total as size_qty
                                            FROM cut_panel_issue_detail
                                            UNION ALL
                                            SELECT 
                                                sales_order_no, 
                                                fg_id, 
                                                color_id, 
                                                vendorId, 
                                                line_id, 
                                                size_qty_total as size_qty 
                                            FROM stitching_inhouse_detail
                                        ) AS cp 
                                            ON cp.sales_order_no = bpm.tr_code 
                                            AND cp.fg_id = bpm.fg_id 
                                            AND cp.color_id = bpd.color_id
                                        LEFT JOIN ledger_master AS LM 
                                            ON LM.ac_code = cp.vendorId
                                        LEFT JOIN line_master lm 
                                            ON lm.line_id = cp.line_id 
                                        LEFT JOIN brand_master bm 
                                            ON bm.brand_id = bpm.brand_id 
                                        LEFT JOIN color_master cm 
                                            ON cm.color_id = bpd.color_id 
                                        LEFT JOIN fg_master fm 
                                            ON fm.fg_id = bpm.fg_id 
                                        WHERE bpm.job_status_id = 1 
                                          AND bpm.og_id != 4 ".$filter."
                                        GROUP BY bpm.tr_code, cm.color_id, cm.color_name, bm.brand_id, bm.brand_name, fm.fg_id,fm.fg_name,lm.line_name,lm.line_id,cp.vendorId");

    
    
            foreach ($BuyerPurchaseData as $row) 
            { 
                $stitchingData = DB::SELECT("SELECT SUM(size_qty_total) as stitching FROM stitching_inhouse_detail WHERE fg_id = '".$row->fg_id."' AND line_id = '".$row->line_id."' AND vendorId = '".$row->vendorId."' AND sales_order_no='".$row->tr_code."' AND color_id=".$row->color_id);
                $cuttngingData = DB::SELECT("SELECT SUM(size_qty_total) as cutting FROM cut_panel_issue_detail WHERE fg_id = '".$row->fg_id."' AND line_id = '".$row->line_id."' AND vendorId = '".$row->vendorId."' AND sales_order_no='".$row->tr_code."' AND color_id=".$row->color_id);

                $stitching_qty = isset($stitchingData[0]->stitching) ? $stitchingData[0]->stitching : 0;
                $cutting_qty = isset($cuttngingData[0]->cutting) ? $cuttngingData[0]->cutting : 0;
                   
                if($row->line_name != '')
                {
                    $cut_per = 0;
                    
                    if($stitching_qty > 0 && $cutting_qty > 0)
                    {
                        $cut_per = ($stitching_qty/$cutting_qty) * 100;
                    }
                    
                    if(($cutting_qty - $stitching_qty) != 0)
                    {
                        $html .= '<tr> 
                                    <td>'.$row->tr_code.'</td>
                                    <td>'.$row->vendorName.'</td>
                                    <td>'.$row->brand_name.'</td>
                                    <td>'.$row->fg_name.'</td>
                                    <td>'.$row->color_name.'</td>
                                    <td>'.$row->line_name.'</td>
                                    <td class="text-right">'.number_format(($cutting_qty), 0, '.', ',').'</td>
                                    <td class="text-right">'.number_format(($stitching_qty), 0, '.', ',').'</td>
                                    <td class="text-right">'.number_format((($cutting_qty - $stitching_qty)), 0, '.', ',').'</td>
                                    <td class="text-right">'.number_format(((round($cut_per,2))), 2, '.', ',').'</td>
                                 </tr>';
                    }
                }
    
            } 
            
            $html .= '</tbody>';
        }
        
        else if($type == 2)
        {
            $html = '<thead>
                      <tr>
                         <th>Order No</th>
                         <th>Brand</th>
                         <th>Style Name</th>
                         <th>Color</th>
                         <th>Stitching Qty</th>
                         <th>Washing Out</th>
                         <th>Washing In</th>
                         <th>Difference</th>
                         <th>Packing</th>
                         <th>Difference</th>
                      </tr>
                   </thead>
                   <tbody>';
            
            $BuyerPurchaseData = DB::SELECT("SELECT bpm.tr_code,cm.color_id, cm.color_name,bm.brand_id, bm.brand_name, fm.fg_id, fm.fg_name
                                FROM buyer_purchse_order_master bpm 
                                INNER JOIN buyer_purchase_order_detail bpd 
                                    ON bpd.tr_code = bpm.tr_code 
                                LEFT JOIN brand_master bm 
                                    ON bm.brand_id = bpm.brand_id 
                                LEFT JOIN color_master cm 
                                    ON cm.color_id = bpd.color_id 
                                LEFT JOIN fg_master fm 
                                    ON fm.fg_id = bpm.fg_id 
                                WHERE bpm.job_status_id = 1 
                                  AND bpm.og_id != 4 ".$filter."
                                GROUP BY bpm.tr_code, 
                                         bpd.color_id, 
                                         cm.color_name, 
                                         bm.brand_id, 
                                         bm.brand_name, 
                                         fm.fg_id, 
                                         fm.fg_name");
            
                foreach ($BuyerPurchaseData as $row) 
                {
                    
                   $stitchingData = DB::SELECT("SELECT SUM(size_qty_total) as stitching FROM stitching_inhouse_detail WHERE sales_order_no='".$row->tr_code."' AND color_id=".$row->color_id);
                   $vendorData = DB::SELECT("SELECT SUM(size_qty_total) as washing_out FROM vendor_purchase_order_detail WHERE process_id = 4 AND sales_order_no='".$row->tr_code."' AND color_id=".$row->color_id);
                   $washingData = DB::SELECT("SELECT SUM(size_qty_total) as washing_in FROM washing_inhouse_detail WHERE process_id = 4 AND sales_order_no='".$row->tr_code."' AND color_id=".$row->color_id);
                   $packingData = DB::SELECT("SELECT SUM(size_qty_total) as packing FROM packing_inhouse_detail
                                    INNER JOIN packing_inhouse_master ON packing_inhouse_master.pki_code = packing_inhouse_detail.pki_code
                                    WHERE packing_inhouse_master.packing_type_id = 4 AND packing_inhouse_detail.sales_order_no='".$row->tr_code."' 
                                    AND packing_inhouse_detail.color_id=".$row->color_id);
                   
                   $stitching_qty = isset($stitchingData[0]->stitching) ? $stitchingData[0]->stitching : 0;
                   $washing_out_qty = isset($vendorData[0]->washing_out) ? $vendorData[0]->washing_out : 0;
                   $washing_in_qty = isset($washingData[0]->washing_in) ? $washingData[0]->washing_in : 0;
                   $packing_qty = isset($packingData[0]->packing) ? $packingData[0]->packing : 0;
                   
                    if(($stitching_qty-($washing_in_qty ? $washing_in_qty : 0 )) != 0 && ($stitching_qty-$packing_qty) != 0)
                    {
                        
                        if($washing_out_qty > 0)
                        {
                            $wash_in = $washing_in_qty ? $washing_in_qty : 0; 
                        }
                        else
                        {
                            $wash_in = $stitching_qty;
                        }
                        
                        $html .= '<tr> 
                            <td>'.$row->tr_code.'</td>
                            <td>'.$row->brand_name.'</td>
                            <td>'.$row->fg_name.'</td>
                            <td>'.$row->color_name.'</td>
                            <td class="text-right">'.number_format(($stitching_qty), 0, '.', ',').'</td>
                            <td class="text-right">'.number_format(($washing_out_qty ? $washing_out_qty : $stitching_qty), 0, '.', ',').'</td>
                            <td class="text-right">'.number_format((($wash_in)), 0, '.', ',').'</td>
                            <td class="text-right">'.number_format((($stitching_qty-($wash_in))), 0, '.', ',').'</td>
                            <td class="text-right">'.number_format(($packing_qty), 0, '.', ',').'</td>
                            <td class="text-right">'.number_format((($stitching_qty-$packing_qty)), 0, '.', ',').'</td>
                         </tr>';

                    }
                }
                $html .= '</tbody>';
                   
        }
        else
        {
            $html = '';
        }
        
        return response()->json(['html' => $html]);
    }
    
    public function ProductionReport1(Request $request)
    {
          $job_status_id= 1;
          $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '116')
        ->first();
         
        // $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('usermaster', 'usermaster.userId', '=', 'buyer_purchse_order_master.userId')
        //  ->leftJoin('merchant_master', 'merchant_master.merchant_id', '=', 'buyer_purchse_order_master.merchant_id')  
        // ->join('ledger_master', 'ledger_master.Ac_code', '=', 'buyer_purchse_order_master.Ac_code')
        // ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id', 'left outer')
        // ->join('fg_master', 'fg_master.fg_id', '=', 'buyer_purchse_order_master.fg_id')
        // ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        // ->where('buyer_purchse_order_master.delflag','=', '0')
        // ->where('buyer_purchse_order_master.job_status_id','=', '1')
        // ->get(['buyer_purchse_order_master.*','usermaster.username','ledger_master.Ac_name','fg_master.fg_name','merchant_master.merchant_name','job_status_master.job_status_name','main_style_master.mainstyle_name']);
    
        // $total_valuec=0;
        // $total_qtyc=0;
        // $open_qtyc=0;
        // $shipped_qtyc=0;
        // foreach($Buyer_Purchase_Order_List as $row){$total_valuec=$total_valuec + $row->order_value; $total_qtyc=$total_qtyc+$row->total_qty; $open_qtyc=$open_qtyc+$row->balance_qty; $shipped_qtyc=$shipped_qtyc+$row->shipped_qty;}
        // $NoOfOrderc=count($Buyer_Purchase_Order_List);
            // DB::enableQueryLog();
        
         $Ac_code = $request->ac_code;
         $po_code = $request->po_code;
         $sales_order_no = $request->sales_order_no;
         $brand_id = $request->brand_id;
         
             
         $filter = "";
         
        
         if($po_code != "") 
         {
             $filter .= " AND buyer_purchse_order_master.po_code='".$po_code."'"; 
         }
         
         if($sales_order_no != "") 
         {
             $filter .= " AND buyer_purchse_order_master.tr_code='".$sales_order_no."'"; 
         }
         
         if($Ac_code != "") 
         {
             $filter .= " AND buyer_purchse_order_master.Ac_code='".$Ac_code."'"; 
         }
         
         if($brand_id != "")
         {
            $filter .= " AND buyer_purchse_order_master.brand_id='".$brand_id."'";
         }
         
        
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4"); 
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");  
        $poList = DB::SELECT("SELECT po_code FROM buyer_purchse_order_master WHERE delflag = 0 GROUP BY buyer_purchse_order_master.po_code");
        
        //DB::enableQueryLog();
        $ProductionOrderDetailList = DB::select("SELECT buyer_purchse_order_master.tr_code ,buyer_purchse_order_master.po_code, 
           buyer_purchse_order_master.mainstyle_id,mainstyle_name,buyer_purchse_order_master.style_no,
           buyer_purchse_order_master.Ac_code, ac_short_name, username, 
            buyer_purchase_order_detail.color_id,color_name, sum(size_qty_total) as order_qty ,brand_master.brand_name
            FROM `buyer_purchse_order_master` 
            inner join buyer_purchase_order_detail on buyer_purchase_order_detail.tr_code=buyer_purchse_order_master.tr_code 
            inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
            left outer join main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            left outer join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left outer join ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            left outer join usermaster on usermaster.userId=buyer_purchse_order_master.userId
            where buyer_purchse_order_master.job_status_id=1 and  buyer_purchse_order_master.og_id!=4 ".$filter."
            group by main_style_master.mainstyle_id, buyer_purchse_order_master.Ac_code, buyer_purchase_order_detail.color_id ,buyer_purchse_order_master.userId,buyer_purchse_order_master.tr_code");
     
        //dd(DB::getQueryLog());
        return view('ProductionReport1', compact('ProductionOrderDetailList','chekform','job_status_id','Ac_code','po_code','sales_order_no','brand_id','salesOrderList','brandList','buyerList','poList'));
     
     }
     
    public function ProductionReport2(Request $request)
    {
        $job_status_id= 1;
            
        $fromDate = isset($request->fromDate) ? $request->fromDate : date('Y-m-01');  
        $toDate = isset($request->toDate) ? $request->toDate : date('Y-m-d'); 
        $vendorId = $request->vendorId ? $request->vendorId : 0; 
        $line_id = $request->line_id ? $request->line_id : 0;       
        $period = $this->getBetweenDates($fromDate, $toDate);
        
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->where('ledger_master.ac_code','>', '39')->get();
        $LineList = DB::SELECT("SELECT * FROM line_master where delflag=0 AND Ac_code=".$vendorId);
    
        return view('ProductionReport2', compact('job_status_id', 'period', 'fromDate', 'toDate', 'LedgerList','LineList','vendorId','line_id'));
     
    }
    
    
    public function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];
    
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);
    
        for (
            $currentDate = $startDate;
            $currentDate <= $endDate;
            $currentDate += (86400)
        ) {
    
            $date = date('Y-m-d', $currentDate);
            $rangArray[] = $date;
        }
    
        return $rangArray;
    }
    
    
    public function GetDestinationForSalesOrderList(Request $request)
    {
       
        $ledgerDetails = DB::SELECT("SELECT sr_no, site_code FROM ledger_details WHERE ac_code=".$request->Ac_code." GROUP BY site_code"); 
                 
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($ledgerDetails as $row)  
        {
            $html .= '<option value="'.$row->sr_no.'">'.$row->site_code.'</option>';
        }
        
        return response()->json(['html' => $html]);
    }
     
}
