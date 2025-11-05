<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BOMMasterModel;
use App\Models\LedgerModel;
use App\Models\BOMSewingTrimsDetailModel;
use App\Models\SeasonModel;
use App\Models\SizeDetailModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ClassificationModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\CurrencyModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\QualityModel;
use App\Models\BOMFabricDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\BOMPackingTrimsDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\BOMTrimFabricDetailModel;
use App\Models\SalesOrderCostingMasterModel;
use App\Models\BrandModel;
use Session;
use DataTables;  

setlocale(LC_MONETARY, 'en_IN'); 
date_default_timezone_set('Asia/Calcutta');
use App\Services\BomDetailActivityLog;
use Log;

class BOMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '90')
        ->first();
        
        $userId = Session::get('userId');
                     
        if( $request->page == 1)
        { 
            // $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
            //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer') 
            //     ->where('bom_master.delflag','=', '0')
            //     ->get(['bom_master.*','usermaster.username','ledger_master.ac_short_name']);
                
             
            $BOMList = DB::SELECT("SELECT bom_master.*,brand_master.brand_name,usermaster.username,ledger_master.ac_short_name FROM bom_master 
                        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = bom_master.sales_order_no 
                        LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id  
                        LEFT JOIN ledger_master ON ledger_master.Ac_code = bom_master.Ac_code 
                        LEFT JOIN usermaster ON usermaster.userId = bom_master.userId WHERE bom_master.delflag = 0
                        AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".$userId.")");
        }
        else
        {
            // $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
            //     ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer') 
            //     ->where('bom_master.delflag','=', '0')
            //     ->where('bom_master.bom_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            //     ->get(['bom_master.*','usermaster.username','ledger_master.ac_short_name']);
         
            $BOMList = DB::SELECT("SELECT bom_master.*,brand_master.brand_name,usermaster.username,ledger_master.ac_short_name FROM bom_master 
                        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = bom_master.sales_order_no
                        LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id  
                        LEFT JOIN ledger_master ON ledger_master.Ac_code = bom_master.Ac_code  
                        LEFT JOIN usermaster ON usermaster.userId = bom_master.userId WHERE bom_master.delflag = 0
                        AND bom_master.bom_date > LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)
                        AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".$userId.")");
                        
        //   $BOMList = DB::SELECT("SELECT bom_master.*,usermaster.username,ledger_master.ac_short_name FROM bom_master 
        //                 LEFT JOIN ledger_master ON ledger_master.Ac_code = bom_master.Ac_code 
        //                 LEFT JOIN usermaster ON usermaster.userId = bom_master.userId 
        //                 INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = bom_master.sales_order_no 
        //                 WHERE bom_master.delflag = 0 AND bom_master.bom_date > LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)
        //                 AND bom_master.userId =".Session::get('userId')." 
        //                 AND buyer_purchse_order_master.brand_id IN(SELECT brand_id FROM buyer_brand_auth_details WHERE userId=".Session::get('userId'));
                        
        }

       
       
        if ($request->ajax()) 
        {
            return Datatables::of($BOMList)
            ->addIndexColumn()
            ->addColumn('bom_code1',function ($row) {
        
                 $bom_codeData =substr($row->bom_code,4,15);
        
                 return $bom_codeData;
            }) 
            ->addColumn('updated_at',function ($row) {
        
                 $updated_at = date("d-m-Y h:i:s", strtotime($row->updated_at));
        
                 return $updated_at;
            }) 
            ->addColumn('action1', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="BOMPrint/'.$row->bom_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action2', function ($row) 
            {
                $btn2 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="BUDGETPrint/'.$row->bom_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn2;
            })
            ->addColumn('action3', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1 OR Session::get('user_type') == 1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('BOM.edit', $row->bom_code).'" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                }
                else
                { 
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                }
                return $btn3;
            })
            ->addColumn('action4', function ($row) use ($chekform){
         
                if($chekform->delete_access==1 && $row->username == Session::get('username') OR Session::get('user_type') == 1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->bom_code.'"  data-route="'.route('BOM.destroy', $row->bom_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            }) 
            ->addColumn('action5', function ($row) 
            {
                $btn2 = '<a class="btn btn-outline-secondary btn-sm" target="_blank" href="VendorPurchaseOrder/create" title="Cutting">
                            <i class="fas fa-forward"></i>
                            </a>';
                return $btn2;
            })
            
            ->addColumn('action8', function ($row) 
            {
                $btn2 = '<a class="btn btn-outline-secondary btn-sm" target="_blank" href="BOMMasterRepeat/'.$row->bom_code.'" title="Repeat">
                            <i class="fas fa-plus"></i>
                            </a>';
                return $btn2;
            })
            
            ->addColumn('action6', function ($row) 
            {
                $btn2 = '<a class="btn btn-outline-secondary btn-sm" target="_blank" href="VendorWorkOrder/create" title="Stitching">
                            <i class="fas fa-forward"></i>
                            </a>';
                return $btn2;
            })
            
            ->addColumn('action7', function ($row) 
            {
                $btn2 = '<a class="btn btn-outline-secondary btn-sm" target="_blank" href="VendorPurchaseOrder/create" title="Packing">
                            <i class="fas fa-forward"></i>
                            </a>';
                return $btn2;
            })
            ->rawColumns(['action1','action2','action3','action4','action5','action6','action7','action8','updated_at'])
    
            ->make(true);
        }
        return view('BOMMasterList', compact('BOMList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function BOMMasterRepeat($id)
    {
        $ApproveMasterList= DB::table('approve_master')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        
        
        $OrderTypeList = DB::table('order_type_master')->where('delflag','=', '0')->get();
        $OrderGroupList = DB::table('order_group_master')->where('delflag','=', '0')->get();
   
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        //$ColorList = ColorModel::where('color_master.delflag','=', '1')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        
        $BOMMasterList = BOMMasterModel::find($id);
        // 
        
        $ClassList = DB::table('classification_master')->select('class_id', 'class_name')->whereIn('class_id',[1,2,7])->get();
        
        
        $ClassList5 = DB::table('sales_order_fabric_costing_details')->select('sales_order_fabric_costing_details.class_id', 'class_name')
        ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_fabric_costing_details.class_id', 'left outer')
        ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->where('classification_master.delflag','=', '0')->distinct()->get();
        
        $ClassList2 = DB::table('sales_order_sewing_trims_costing_details')->select('sales_order_sewing_trims_costing_details.class_id', 'class_name')
        ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_sewing_trims_costing_details.class_id', 'left outer')
        ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->distinct()->get();
        
        $ClassList3 = DB::table('sales_order_packing_trims_costing_details')->select('sales_order_packing_trims_costing_details.class_id', 'class_name')
        ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_packing_trims_costing_details.class_id', 'left outer')
        ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->distinct()->get();
        
        $FabricList = BOMFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id',
        'consumption', 'unit_id', 'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount','remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
         purchaseorder_detail.item_code=bom_fabric_details.item_code and FIND_IN_SET(bom_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
         DB::raw("(select ifnull(count(item_code),0) from vendor_work_order_fabric_details where 
         vendor_work_order_fabric_details.item_code=bom_fabric_details.item_code and  vendor_work_order_fabric_details.sales_order_no=bom_fabric_details.sales_order_no) as item_count_fab")
         )
        ->where('bom_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
         

        // 
        
        // DB::enableQueryLog(); 
        
        $TrimFabricList = BOMTrimFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
        'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
         purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
          DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
         purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count_fab"),
          DB::raw("(select ifnull(count(item_code),0) from vendor_purchase_order_trim_fabric_details where 
         vendor_purchase_order_trim_fabric_details.item_code=bom_trim_fabric_details.item_code and  vendor_purchase_order_trim_fabric_details.sales_order_no=bom_trim_fabric_details.sales_order_no) as item_count_trim_fab")
         )
        ->where('bom_trim_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
        
        // $query = DB::getQueryLog();
        //     $query = end($query);
        //     dd($query);
        
        
        $SewingTrimsList = BOMSewingTrimsDetailModel::select('bom_sewing_trims_details.item_code', 'bom_sewing_trims_details.class_id', 'item_master.item_description as description', 'bom_sewing_trims_details.color_id', 'size_array', 'bom_sewing_trims_details.consumption', 'bom_sewing_trims_details.unit_id',
        'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  purchaseorder_detail.sales_order_no=bom_sewing_trims_details.sales_order_no and
       purchaseorder_detail.item_code=bom_sewing_trims_details.item_code and FIND_IN_SET(bom_sewing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
       DB::raw("(select ifnull(count(item_code),0) from vendor_work_order_sewing_trims_details where 
         vendor_work_order_sewing_trims_details.item_code=bom_sewing_trims_details.item_code and  vendor_work_order_sewing_trims_details.sales_order_no=bom_sewing_trims_details.sales_order_no) as item_count_sew"))
            ->leftJoin('item_master', 'item_master.item_code', 'bom_sewing_trims_details.item_code')
            ->where('bom_sewing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
        
        $PackingTrimsList = BOMPackingTrimsDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
        'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  
        purchaseorder_detail.item_code=bom_packing_trims_details.item_code and FIND_IN_SET(bom_packing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
        DB::raw("(select ifnull(count(item_code),0) from vendor_purchase_order_packing_trims_details where 
        vendor_purchase_order_packing_trims_details.item_code=bom_packing_trims_details.item_code and  vendor_purchase_order_packing_trims_details.sales_order_no=bom_packing_trims_details.sales_order_no) as item_count_pack"))  
        ->where('bom_packing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
        
        
        // DB::enableQueryLog(); 
        
            //DB::enableQueryLog(); 
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
             ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
             ->where('tr_code','=',$BOMMasterList->sales_order_no)->DISTINCT()->get();
            // $query = DB::getQueryLog();
            // $query = end($query);
            // dd($query);
        $ItemList1 = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$BOMMasterList->sales_order_no)->where('item_master.delflag','=',0)->DISTINCT()->get();
        
        $ItemList4= ItemModel::where('delflag','=', '0')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        
        // $S1 = SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
        //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
        //     ->where('sales_order_costing_master.is_approved', '=', 2)
        //     ->where('buyer_purchse_order_master.job_status_id', '=', 1)
        //     ->whereNotIn('sales_order_costing_master.sales_order_no', function($query) {
        //         $query->select('sales_order_no')->from('bom_master');
        //     });
        
        // $S2 = BOMMasterModel::select('sales_order_no')
        //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'bom_master.sales_order_no')
        //     ->where('buyer_purchse_order_master.job_status_id', '=', 1)
        //     ->where('sales_order_no', $BOMMasterList->sales_order_no);
        
        // $SalesOrderList = $S1->union($S2)->get();
    // DB::enableQueryLog();
        $SalesOrderList= SalesOrderCostingMasterModel::join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')->select('sales_order_costing_master.sales_order_no')
             ->where('buyer_purchse_order_master.job_status_id', '=', 1)
             ->whereRaw("sales_order_costing_master.sales_order_no NOT IN(select bom_master.sales_order_no from bom_master where bom_master.sales_order_no=sales_order_costing_master.sales_order_no)  AND sales_order_costing_master.sales_order_no IS NOT NULL")
            ->get();
                // dd(DB::getQueryLog());
            
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($BOMMasterList->sales_order_no);
       
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        //  DB::enableQueryLog();  
        $MasterdataList = DB::select("SELECT sales_order_detail.color_id, color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from sales_order_detail 
        inner join color_master on color_master.color_id=sales_order_detail.color_id 
        where tr_code='".$BOMMasterList->sales_order_no."' group by sales_order_detail.color_id");
        
        $BrandList = BrandModel::select('*')->get();
          
        return view('BOMMasterRepeat',compact('BrandList','OrderTypeList','OrderGroupList','ColorList','BuyerPurchaseOrderMasterList','ClassList5', 'ApproveMasterList', 'MasterdataList','SizeDetailList','BOMMasterList','FabricList','TrimFabricList','ItemList4',  'SewingTrimsList','PackingTrimsList','UnitList','ClassList','ClassList2','ClassList3','ItemList1','ItemList2','ItemList3','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
         
    }
    
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BOM'");
        // $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList4= ItemModel::where('delflag','=', '0')->get(); 
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
      
        $SalesOrderList= SalesOrderCostingMasterModel::join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')->select('sales_order_costing_master.sales_order_no')
         ->where('buyer_purchse_order_master.job_status_id', '=', 1)
         ->whereRaw("sales_order_costing_master.sales_order_no NOT IN(select bom_master.sales_order_no from bom_master where bom_master.sales_order_no=sales_order_costing_master.sales_order_no)  AND sales_order_costing_master.sales_order_no IS NOT NULL")
        ->get();
        
        // $S1 = SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
        //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
        //     ->where('sales_order_costing_master.is_approved', '=', 2)
        //     ->where('buyer_purchse_order_master.job_status_id', '=', 1)
        //     ->whereNotIn('sales_order_costing_master.sales_order_no', function($query) {
        //         $query->select('sales_order_no')->from('bom_master');
        //     });
        
        // $S2 = BOMMasterModel::select('sales_order_no')
        //     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'bom_master.sales_order_no')
        //     ->where('buyer_purchse_order_master.job_status_id', '=', 1)
        //     ->where('sales_order_no', $BOMMasterList->sales_order_no);
        
        // $SalesOrderList = $S1->union($S2)->get();
        
        $OrderTypeList = DB::table('order_type_master')->where('delflag','=', '0')->get();
        $OrderGroupList = DB::table('order_group_master')->where('delflag','=', '0')->get();
        $BrandList = BrandModel::select('*')->get();
      
        return view('BOMMaster',compact('BrandList','OrderTypeList','OrderGroupList','UnitList','ClassList','ClassList2','ClassList3','ItemList2','ItemList3','ItemList4',  'MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList', 'counter_number'));

         
    }

    public function BOMMasterTrial()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BOM'");
        // $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList4= ItemModel::where('delflag','=', '0')->get(); 
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        
        $SalesOrderList= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
        //  ->whereRaw("sales_order_costing_master.sales_order_no NOT IN(select bom_master.sales_order_no from bom_master where bom_master.sales_order_no=sales_order_costing_master.sales_order_no)")
        ->get();
        
        
        return view('BOMMasterTrial',compact('UnitList','ClassList','ClassList2','ClassList3','ItemList2','ItemList3','ItemList4',  'MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList', 'counter_number'));

         
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $this->validate($request, [
             
                'bom_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'total_cost_value'=> 'required',
                'fabric_value'=> 'required',
                'sewing_trims_value'=> 'required',
                'packing_trims_value'=> 'required',
               
        ]);
 
        try 
        {
                DB::beginTransaction();
        
                $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
                ->where('c_name','=','C1')
                ->where('type','=','BOM')
                ->where('firm_id','=',1)
                ->first();
                $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
                
                $data1=array(
                   
                'bom_code'=>$TrNo, 
                'bom_date'=>$request->bom_date, 
                'cost_type_id'=>$request->cost_type_id,
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'season_id'=>$request->season_id,
                'currency_id'=>$request->currency_id, 
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'total_qty'=>$request->total_qty,
                'order_rate'=>$request->order_rate,
                'fabric_value'=>$request->fabric_value ?? 0, 
                'sewing_trims_value'=>$request->sewing_trims_value ?? 0,
                'packing_trims_value'=>$request->packing_trims_value ?? 0,
                'total_cost_value'=>$request->total_cost_value ?? 0,
                'narration'=>$request->narration,
                'is_approved'=>'0',
                'userId'=>$request->userId,
                'delflag'=>'0',
                'c_code'=>$request->c_code,
                
            );
         
            BOMMasterModel::insert($data1);
            
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='BOM'");
        
            $item_code= $request->input('item_code');
            if(count($item_code)>0)
            {
            
            for($x=0; $x<count($item_code); $x++) {
                # code...
         
                    $data2=array(
                        
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date,  
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_code[$x],
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'color_id' => '',
                    'consumption' => $request->consumption[$x],
                    'unit_id'=> $request->unit_id[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x],
                    'bom_qty' => $request->bom_qty[$x],
                    'item_qty' => $request->bom_qty1[$x],
                    'total_amount' => $request->total_amount[$x],
                    'remark' => $request->remark[$x],
                     );
                  BOMFabricDetailModel::insert($data2);
                    }
                 
            }
        
           $item_codes = $request->input('item_codes');
            if(count($item_codes)>0)
            {
             for($x=0; $x<count($item_codes); $x++) {
                # code...
         
                    $data3=array(
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date, 
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codes[$x],
                    'class_id' => $request->class_ids[$x],
                    'description' => $request->descriptions[$x],
                    'color_id' => $request->color_arrays[$x],
                    'size_array' => $request->size_arrays[$x],
                    'consumption' => $request->consumptions[$x],
                    'unit_id'=> $request->unit_ids[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x],
                    'bom_qty' => $request->bom_qtys[$x],
                    'item_qty' => $request->bom_qtys1[$x],
                    'total_amount' => $request->total_amounts[$x],
                    
                    'remark' => $request->remarks[$x],
                     );
                  BOMSewingTrimsDetailModel::insert($data3);
                    }
            }
             
             
             
            $item_codesx = $request->input('item_codesx');
            if(count($item_codesx)>0)
            {
                for($x=0; $x<count($item_codesx); $x++) 
                {
                    
                    $data6=array(
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date, 
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codesx[$x],
                    'class_id' => $request->class_idsx[$x],
                    'description' => $request->descriptionsx[$x],
                    'color_id' => $request->color_arraysx[$x],
                    'size_array' => $request->size_arraysx[$x],
                    'consumption' => $request->consumptionsx[$x],
                    'unit_id'=> $request->unit_idsx[$x],
                    'rate_per_unit' => $request->rate_per_unitsx[$x],
                    'wastage' => $request->wastagesx[$x],
                    'bom_qty' => $request->bom_qtysx[$x],
                    'item_qty' => $request->bom_qtysx1[$x],
                    'total_amount' => $request->total_amountsx[$x],
                    'remark' => $request->remarksx[$x],
                      );
                  BOMTrimFabricDetailModel::insert($data6);
                }
            }
             
            $item_codess = $request->input('item_codess');
            if(count($item_codess)>0)
            {
             for($x=0; $x<count($item_codess); $x++) {
                # code...
               
                    $data4=array(
                        
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date,  
                   'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codess[$x],
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'color_id' => $request->color_arrayss[$x],
                    'size_array' => $request->size_arrayss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'unit_id' => $request->unit_idss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'item_qty' => $request->bom_qtyss1[$x],
                    'total_amount' => $request->total_amountss[$x],
                    'remark' => $request->remarkss[$x],
                    
                     );
                  BOMPackingTrimsDetailModel::insert($data4);
                }
            }
        
            DB::commit();
            return redirect()->route('BOM.index')->with('message', 'Data Saved Succesfully');  
        } 
        catch (\Exception $e) 
        {
       
             \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
          
              DB::rollBack();
          
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
     
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'bom_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'bom_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'bom_master.fg_id', 'left outer') 
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code)
        
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name'
        ,'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('budgetPrint', compact('BOMList'));  
      
    }

    public function bomPrint($bom_code)
    {
        $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'bom_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'bom_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'bom_master.fg_id', 'left outer')   
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code)
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('bomPrint', compact('BOMList'));     
        
        
    }






    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
            $ApproveMasterList= DB::table('approve_master')->get();
            $CPList= DB::table('cp_master')->get();
            $CostTypeList= DB::table('costing_type_master')->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
            $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
            
            
       
            $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
            $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
            //$ColorList = ColorModel::where('color_master.delflag','=', '1')->get();
            $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
            $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
            $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
            $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
            
            $BOMMasterList = BOMMasterModel::find($id);
            // 
            
            $ClassList = DB::table('classification_master')->select('class_id', 'class_name')->whereIn('class_id',[1,2,7])->get();
            
            
            $ClassList5 = DB::table('sales_order_fabric_costing_details')->select('sales_order_fabric_costing_details.class_id', 'class_name')
            ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_fabric_costing_details.class_id', 'left outer')
            ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->where('classification_master.delflag','=', '0')->distinct()->get();
            
            $ClassList2 = DB::table('sales_order_sewing_trims_costing_details')->select('sales_order_sewing_trims_costing_details.class_id', 'class_name')
            ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_sewing_trims_costing_details.class_id', 'left outer')
            ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->distinct()->get();
            
            $ClassList3 = DB::table('sales_order_packing_trims_costing_details')->select('sales_order_packing_trims_costing_details.class_id', 'class_name')
            ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_packing_trims_costing_details.class_id', 'left outer')
            ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->distinct()->get();
            
            $FabricList = BOMFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id',
            'consumption', 'unit_id', 'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount','remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
             purchaseorder_detail.item_code=bom_fabric_details.item_code and FIND_IN_SET(bom_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
             DB::raw("(select ifnull(count(item_code),0) from vendor_work_order_fabric_details where 
             vendor_work_order_fabric_details.item_code=bom_fabric_details.item_code and  vendor_work_order_fabric_details.sales_order_no=bom_fabric_details.sales_order_no) as item_count_fab")
             )
            ->where('bom_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
             
    
            // 
            
            // DB::enableQueryLog(); 
            
            $TrimFabricList = BOMTrimFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
            'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
             purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
              DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
             purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count_fab"),
              DB::raw("(select ifnull(count(item_code),0) from vendor_purchase_order_trim_fabric_details where 
             vendor_purchase_order_trim_fabric_details.item_code=bom_trim_fabric_details.item_code and  vendor_purchase_order_trim_fabric_details.sales_order_no=bom_trim_fabric_details.sales_order_no) as item_count_trim_fab")
             )
            ->where('bom_trim_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
             
            
            // DB::enableQueryLog();
            $SewingTrimsList = BOMSewingTrimsDetailModel::select('bom_sewing_trims_details.item_code', 'bom_sewing_trims_details.class_id', 'item_master.item_description as description', 'bom_sewing_trims_details.color_id', 'size_array', 'bom_sewing_trims_details.consumption', 'bom_sewing_trims_details.unit_id',
            'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  purchaseorder_detail.sales_order_no=bom_sewing_trims_details.sales_order_no and
           purchaseorder_detail.item_code=bom_sewing_trims_details.item_code and FIND_IN_SET(bom_sewing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
           DB::raw("(select ifnull(count(item_code),0) from vendor_work_order_sewing_trims_details where 
             vendor_work_order_sewing_trims_details.item_code=bom_sewing_trims_details.item_code and  vendor_work_order_sewing_trims_details.sales_order_no=bom_sewing_trims_details.sales_order_no) as item_count_sew"))
                ->leftJoin('item_master', 'item_master.item_code', 'bom_sewing_trims_details.item_code')
                ->where('bom_sewing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
            // dd(DB::getQueryLog());
            $PackingTrimsList = BOMPackingTrimsDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
            'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  
            purchaseorder_detail.item_code=bom_packing_trims_details.item_code and FIND_IN_SET(bom_packing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
            DB::raw("(select ifnull(count(item_code),0) from vendor_purchase_order_packing_trims_details where 
            vendor_purchase_order_packing_trims_details.item_code=bom_packing_trims_details.item_code and  vendor_purchase_order_packing_trims_details.sales_order_no=bom_packing_trims_details.sales_order_no) as item_count_pack"))  
            ->where('bom_packing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
              
            // DB::enableQueryLog(); 
            
                //DB::enableQueryLog(); 
                 $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
                 ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                 ->where('tr_code','=',$BOMMasterList->sales_order_no)->DISTINCT()->get();
                // $query = DB::getQueryLog();
                // $query = end($query);
                // dd($query);
            $ItemList1 = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
            ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
            ->where('tr_code','=',$BOMMasterList->sales_order_no)->where('item_master.delflag','=',0)->DISTINCT()->get();
            
            // $ItemList4= ItemModel::where('delflag','=', '0')->get();
            // $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
            // $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
             
            $S1= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
            ->whereNotIn('sales_order_costing_master.sales_order_no',function($query){
            $query->select('sales_order_no')->from('bom_master');
            });
             
            $S2=BOMMasterModel::select('sales_order_no')->where('sales_order_no',$BOMMasterList->sales_order_no);
            $SalesOrderList = $S1->union($S2)->get();
            $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($BOMMasterList->sales_order_no);
            $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
            $sizes='';
            $no=1;
            foreach ($SizeDetailList as $sz) 
            { 
                $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                $no=$no+1;
            }
            $sizes=rtrim($sizes,',');
            //  DB::enableQueryLog();  
            $MasterdataList = DB::select("SELECT sales_order_detail.color_id, color_name, ".$sizes.", 
            sum(size_qty_total) as size_qty_total  from sales_order_detail 
            inner join color_master on color_master.color_id=sales_order_detail.color_id 
            where tr_code='".$BOMMasterList->sales_order_no."' group by sales_order_detail.color_id");
              
              
            $OrderTypeList = DB::table('order_type_master')->where('delflag','=', '0')->get();
            $OrderGroupList = DB::table('order_group_master')->where('delflag','=', '0')->get();
    
            $BrandList = BrandModel::select('*')->get();
        
            return view('BOMMasterEdit',compact('OrderTypeList','OrderGroupList','BrandList','ColorList','BuyerPurchaseOrderMasterList','ClassList5', 'ApproveMasterList', 'MasterdataList','SizeDetailList','BOMMasterList','FabricList','TrimFabricList',  'SewingTrimsList','PackingTrimsList','UnitList','ClassList','ClassList2','ClassList3','ItemList1','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
    }


    public function BOMMasterEditTrial($id)
    {   
            $ApproveMasterList= DB::table('approve_master')->get();
            $CPList= DB::table('cp_master')->get();
            $CostTypeList= DB::table('costing_type_master')->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
            $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
            
            
       
            $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
            $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
            //$ColorList = ColorModel::where('color_master.delflag','=', '1')->get();
            $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
            $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
            $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
            $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
            
            $BOMMasterList = BOMMasterModel::find($id);
            // 
            
            $ClassList = DB::table('classification_master')->select('class_id', 'class_name')->whereIn('class_id',[1,2,7])->get();
            
            
            $ClassList5 = DB::table('sales_order_fabric_costing_details')->select('sales_order_fabric_costing_details.class_id', 'class_name')
            ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_fabric_costing_details.class_id', 'left outer')
            ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->where('classification_master.delflag','=', '0')->distinct()->get();
            
            $ClassList2 = DB::table('sales_order_sewing_trims_costing_details')->select('sales_order_sewing_trims_costing_details.class_id', 'class_name')
            ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_sewing_trims_costing_details.class_id', 'left outer')
            ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->distinct()->get();
            
            $ClassList3 = DB::table('sales_order_packing_trims_costing_details')->select('sales_order_packing_trims_costing_details.class_id', 'class_name')
            ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_packing_trims_costing_details.class_id', 'left outer')
            ->where('sales_order_no','=',$BOMMasterList->sales_order_no)->distinct()->get();
            
            $FabricList = BOMFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id',
            'consumption', 'unit_id', 'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount','remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
             purchaseorder_detail.item_code=bom_fabric_details.item_code and FIND_IN_SET(bom_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
             DB::raw("(select ifnull(count(item_code),0) from vendor_work_order_fabric_details where 
             vendor_work_order_fabric_details.item_code=bom_fabric_details.item_code and  vendor_work_order_fabric_details.sales_order_no=bom_fabric_details.sales_order_no) as item_count_fab")
             )
            ->where('bom_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
             
    
            // 
            
            // DB::enableQueryLog(); 
            
            $TrimFabricList = BOMTrimFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
            'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
             purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
              DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
             purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count_fab"),
              DB::raw("(select ifnull(count(item_code),0) from vendor_purchase_order_trim_fabric_details where 
             vendor_purchase_order_trim_fabric_details.item_code=bom_trim_fabric_details.item_code and  vendor_purchase_order_trim_fabric_details.sales_order_no=bom_trim_fabric_details.sales_order_no) as item_count_trim_fab")
             )
            ->where('bom_trim_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
             
            
            // DB::enableQueryLog();
            $SewingTrimsList = BOMSewingTrimsDetailModel::select('bom_sewing_trims_details.item_code', 'bom_sewing_trims_details.class_id', 'item_master.item_description as description', 'bom_sewing_trims_details.color_id', 'size_array', 'bom_sewing_trims_details.consumption', 'bom_sewing_trims_details.unit_id',
            'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  purchaseorder_detail.sales_order_no=bom_sewing_trims_details.sales_order_no and
           purchaseorder_detail.item_code=bom_sewing_trims_details.item_code and FIND_IN_SET(bom_sewing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
           DB::raw("(select ifnull(count(item_code),0) from vendor_work_order_sewing_trims_details where 
             vendor_work_order_sewing_trims_details.item_code=bom_sewing_trims_details.item_code and  vendor_work_order_sewing_trims_details.sales_order_no=bom_sewing_trims_details.sales_order_no) as item_count_sew"))
                ->leftJoin('item_master', 'item_master.item_code', 'bom_sewing_trims_details.item_code')
                ->where('bom_sewing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
            // dd(DB::getQueryLog());
            $PackingTrimsList = BOMPackingTrimsDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
            'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
            DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  
            purchaseorder_detail.item_code=bom_packing_trims_details.item_code and FIND_IN_SET(bom_packing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"),
            DB::raw("(select ifnull(count(item_code),0) from vendor_purchase_order_packing_trims_details where 
            vendor_purchase_order_packing_trims_details.item_code=bom_packing_trims_details.item_code and  vendor_purchase_order_packing_trims_details.sales_order_no=bom_packing_trims_details.sales_order_no) as item_count_pack"))  
            ->where('bom_packing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
              
            // DB::enableQueryLog(); 
            
                //DB::enableQueryLog(); 
                 $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
                 ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                 ->where('tr_code','=',$BOMMasterList->sales_order_no)->DISTINCT()->get();
                // $query = DB::getQueryLog();
                // $query = end($query);
                // dd($query);
            $ItemList1 = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
            ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
            ->where('tr_code','=',$BOMMasterList->sales_order_no)->where('item_master.delflag','=',0)->DISTINCT()->get();
            
            // $ItemList4= ItemModel::where('delflag','=', '0')->get();
            // $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
            // $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
             
            $S1= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
            ->whereNotIn('sales_order_costing_master.sales_order_no',function($query){
            $query->select('sales_order_no')->from('bom_master');
            });
             
            $S2=BOMMasterModel::select('sales_order_no')->where('sales_order_no',$BOMMasterList->sales_order_no);
            $SalesOrderList = $S1->union($S2)->get();
            $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($BOMMasterList->sales_order_no);
            $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
            $sizes='';
            $no=1;
            foreach ($SizeDetailList as $sz) 
            { 
                $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
                $no=$no+1;
            }
            $sizes=rtrim($sizes,',');
            //  DB::enableQueryLog();  
            $MasterdataList = DB::select("SELECT sales_order_detail.color_id, color_name, ".$sizes.", 
            sum(size_qty_total) as size_qty_total  from sales_order_detail 
            inner join color_master on color_master.color_id=sales_order_detail.color_id 
            where tr_code='".$BOMMasterList->sales_order_no."' group by sales_order_detail.color_id");
              
            return view('BOMMasterEditTrial',compact('ColorList','BuyerPurchaseOrderMasterList','ClassList5', 'ApproveMasterList', 'MasterdataList','SizeDetailList','BOMMasterList','FabricList','TrimFabricList',  'SewingTrimsList','PackingTrimsList','UnitList','ClassList','ClassList2','ClassList3','ItemList1','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $soc_code,BomDetailActivityLog $loggerDetail)
    { 
        //echo '<pre>';print_R($_POST);exit;
          $this->validate($request, [
             
                'bom_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'total_cost_value'=> 'required',
                'fabric_value'=> 'required',
                'sewing_trims_value'=> 'required',
                'packing_trims_value'=> 'required',
        ]);
     
     
        try 
        {
            DB::beginTransaction();
            $data1=array(
                'bom_code'=>$request->bom_code, 
                'bom_date'=>$request->bom_date, 
                'cost_type_id'=>$request->cost_type_id,
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'season_id'=>$request->season_id,
                'currency_id'=>$request->currency_id, 
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'total_qty'=>$request->total_qty,
                'order_rate'=>$request->order_rate,
                'fabric_value'=>$request->fabric_value ?? 0,  
                'sewing_trims_value'=>$request->sewing_trims_value ?? 0, 
                'packing_trims_value'=>$request->packing_trims_value ?? 0,  
                'total_cost_value'=>$request->total_cost_value ?? 0, 
                'narration'=>$request->narration,
                'is_approved'=>$request->is_approved ? $request->is_approved : 0,
                'userId'=>$request->userId,
                'delflag'=>'0',
                'c_code'=>$request->c_code,
                
            );
        //   DB::enableQueryLog();   
        $BOMList = BOMMasterModel::findOrFail($request->bom_code); 
        //  $query = DB::getQueryLog();
        //         $query = end($query);
        //         dd($query);
        $BOMList->fill($data1)->save();
        
        
        
           $class_id= $request->class_id;
            
            if(!empty($class_id))   
            {
                
                 $newArray=[];
            
            DB::table('bom_fabric_details')->where('bom_code', $request->bom_code)->delete();
            for($x=0; $x<count($class_id); $x++) {
                # code...
             $data2[]=array(
                        
                    'bom_code'=>$request->bom_code, 
                    'bom_date'=>$request->bom_date,  
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_code[$x],
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'color_id' => '',
                    'consumption' => $request->consumption[$x],
                    'unit_id'=> $request->unit_id[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x],
                    'bom_qty' => $request->bom_qty[$x],
                    'item_qty' => $request->bom_qty1[$x],
                    'total_amount' => $request->total_amount[$x],
                    'remark' => $request->remark[$x],
                    
                     );
                     
                     
             $newArray[]=['sr_no'=>$request->sr_no_bom[$x],'item_code'=>$request->item_code[$x],'consumption'=>$request->consumption[$x],'description'=>$request->description[$x],'rate_per_unit'=>$request->rate_per_unit[$x],'wastage'=>$request->wastage[$x],'bom_qty'=>$request->bom_qty[$x],'total_amount'=>$request->total_amount[$x]];
                     
                    }
                  BOMFabricDetailModel::insert($data2);
                  
                  
                  $oldArray = json_decode(htmlspecialchars_decode($request->fabric_old_data), true);
            //   logger('Old Data:', ['data' => $oldArray]);
            //   logger('New Data:', ['data' => $newArray]);
                  
                         $combinedNewData = $newArray;  
                         $combinedOldData=$oldArray;
           
            try {
            $loggerDetail->logIfChangedBomDetail(
            'bom',
            $request->bom_code,
            $combinedOldData,
            $combinedNewData,
            'UPDATE',
            $request->input('bom_date'),
            'Fabric'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for sales_order_fabric_costing_details.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'bom_code' => $request->bom_code,
            'data' => $combinedNewData
            ]);
            }  
                  
                  
                 
            }
             
           $class_ids = $request->class_ids;
            if(!empty($class_ids))   
            {
                $newSewingArray=[];
                
             DB::table('bom_sewing_trims_details')->where('bom_code', $request->bom_code)->delete();
             for($x=0; $x<count($class_ids); $x++) {
                 
                    $data3[]=array(
                    'bom_code'=>$request->bom_code, 
                    'bom_date'=>$request->bom_date, 
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codes[$x],
                    'class_id' => $request->class_ids[$x],
                    'description' => $request->descriptions[$x],
                    'color_id' => $request->color_arrays[$x],
                    'size_array' => $request->size_arrays[$x],
                    'consumption' => $request->consumptions[$x],
                    'unit_id'=> $request->unit_ids[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x],
                    'bom_qty' => $request->bom_qtys[$x],
                    'item_qty' => $request->bom_qtys1[$x],
                    'total_amount' => $request->total_amounts[$x],
                    'remark' => $request->remarks[$x],
                   
                     );
                     
                     
                    $newSewingArray[]=['sr_no'=>$request->sr_no_sewing_trims[$x],
                    'class_id' => $request->class_ids[$x],
                    'item_code' => $request->item_codes[$x],  
                    'color_id' => $request->color_arrays[$x],
                    'size_array' => $request->size_arrays[$x],
                    'consumption' => $request->consumptions[$x],
                    'rate_per_unit' => $request->rate_per_units[$x],
                    'wastage' => $request->wastages[$x],
                    'bom_qty' => $request->bom_qtys[$x],
                    'total_amount' => $request->total_amounts[$x],
                    'remark' => $request->remarks[$x]];   
                     
                     
                    }
                  BOMSewingTrimsDetailModel::insert($data3);
                  
                  
                  
                  
                $oldSewingArray = json_decode(htmlspecialchars_decode($request->sewing_old_data), true);
            //   logger('Old Data Sewing:', ['data' => $oldSewingArray]);
            //   logger('New Data Sewing:', ['data' => $newSewingArray]);
                  
                         $combinedNewSewingData = $newSewingArray;  
                         $combinedOldSewingData=$oldSewingArray;
           
            try {
            $loggerDetail->logIfChangedBomDetail(
            'bom',
            $request->bom_code,
            $combinedOldSewingData,
            $combinedNewSewingData,
            'UPDATE',
            $request->input('bom_date'),
            'Sewing'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for Sewing.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'bom_code' => $request->bom_code,
            'data' => $combinedNewSewingData
            ]);
            }    
            
            
            
                  
            }
             
             
              $class_idsx = $request->class_idsx;
            
            if(!empty($class_idsx))  
            {
             DB::table('bom_trim_fabric_details')->where('bom_code', $request->bom_code)->delete(); 
             for($x=0; $x<count($class_idsx); $x++) {
                
                    $data6[]=array(
                    'bom_code'=>$request->bom_code,
                    'bom_date'=>$request->bom_date, 
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codesx[$x],
                    'class_id' => $request->class_idsx[$x],
                    'description' => $request->descriptionsx[$x],
                    'color_id' => $request->color_arraysx[$x],
                    'size_array' => $request->size_arraysx[$x],
                    'consumption' => $request->consumptionsx[$x],
                    'unit_id'=> $request->unit_idsx[$x],
                    'rate_per_unit' => $request->rate_per_unitsx[$x],
                    'wastage' => $request->wastagesx[$x],
                    'bom_qty' => $request->bom_qtysx[$x],
                    'item_qty' => $request->bom_qtysx1[$x],
                    'total_amount' => $request->total_amountsx[$x],
                    'remark' => $request->remarksx[$x],
                      );
                    }
                  BOMTrimFabricDetailModel::insert($data6);
            }
             
           
             
            $class_idss = $request->class_idss;
            if(!empty($class_idss))
            {
                $newPackingTrimsArray=[];
                
             DB::table('bom_packing_trims_details')->where('bom_code', $request->bom_code)->delete();
             for($x=0; $x<count($class_idss); $x++) {
                # code...
               
                    $data4[]=array(
                        
                    'bom_code'=>$request->bom_code, 
                    'bom_date'=>$request->bom_date,  
                   'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codess[$x],
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'color_id' => $request->color_arrayss[$x],
                    'size_array' => $request->size_arrayss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'unit_id' => $request->unit_idss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'item_qty' => $request->bom_qtyss1[$x],
                    'total_amount' => $request->total_amountss[$x],
                    'remark' => $request->remarkss[$x],
                    
                     );
                     
                    $newPackingTrimsArray[]=['sr_no'=>$request->sr_no_packing_trims[$x],
                    'class_id' => $request->class_idss[$x],
                    'item_code' => $request->item_codess[$x],  
                    'color_id' => $request->color_arrayss[$x],
                    'size_array' => $request->size_arrayss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'total_amount' => $request->total_amountss[$x],
                    'remark' => $request->remarkss[$x]];     
                     
                     
                    }
                  BOMPackingTrimsDetailModel::insert($data4);
                  
                  
                  
               $oldPackingTrimsArray = json_decode(htmlspecialchars_decode($request->packing_trims_old_data), true);
               logger('Old Data Sewing:', ['data' => $oldPackingTrimsArray]);
               logger('New Data Sewing:', ['data' => $newPackingTrimsArray]);
                  
                         $combinedNewPackingTrimsData = $newPackingTrimsArray;  
                         $combinedOldPackingTrimsData=$oldPackingTrimsArray;
           
            try {
            $loggerDetail->logIfChangedBomDetail(
            'bom',
            $request->bom_code,
            $combinedOldPackingTrimsData,
            $combinedNewPackingTrimsData,
            'UPDATE',
            $request->input('bom_date'),
            'Packing'
            );
            // Log::info('Logger called successfully for sales_order_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for Packing Trims.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'bom_code' => $request->bom_code,
            'data' => $combinedNewPackingTrimsData
            ]);
            }    
                  
                  
                  
                  
                  
            } 
            DB::commit();
            return redirect()->route('BOM.index')->with('message', 'Data Saved Succesfully');  
        } 
        catch (\Exception $e) {
        // If an exception occurs, rollback the transaction and handle the exception
         \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
      
          DB::rollBack();
      
        return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
     
        }
    }
    
    public function BOMMasterEditTrialUpdate(Request $request, BomDetailActivityLog $loggerDetail)
    {
        
        // echo '<pre>'; print_r($_POST);exit;
        // --- Validate ---
        $this->validate($request, [
            'bom_date'            => 'required',
            'Ac_code'             => 'required',
            'sales_order_no'      => 'required',
            'total_cost_value'    => 'required',
            'fabric_value'        => 'required',
            'sewing_trims_value'  => 'required',
            'packing_trims_value' => 'required',
        ]);
    
        try {
            DB::beginTransaction();
    
            // --- Master Update ---
            $dataMaster = [
                'bom_code'           => $request->bom_code,
                'bom_date'           => $request->bom_date,
                'cost_type_id'       => $request->cost_type_id,
                'sales_order_no'     => $request->sales_order_no,
                'Ac_code'            => $request->Ac_code,
                'season_id'          => $request->season_id,
                'currency_id'        => $request->currency_id,
                'mainstyle_id'       => $request->mainstyle_id,
                'substyle_id'        => $request->substyle_id,
                'fg_id'              => $request->fg_id,
                'style_no'           => $request->style_no,
                'style_description'  => $request->style_description,
                'total_qty'          => $request->total_qty,
                'order_rate'         => $request->order_rate,
                'fabric_value'       => $request->fabric_value ?? 0,
                'sewing_trims_value' => $request->sewing_trims_value ?? 0,
                'packing_trims_value'=> $request->packing_trims_value ?? 0,
                'total_cost_value'   => $request->total_cost_value ?? 0,
                'narration'          => $request->narration,
                'is_approved'        => $request->is_approved ?? 0,
                'userId'             => $request->userId,
                'delflag'            => '0',
                'c_code'             => $request->c_code,
            ];
    
            $bomMaster = BOMMasterModel::findOrFail($request->bom_code);
            $bomMaster->fill($dataMaster)->save();
    
            // --- Fabric Details ---
            $this->updateDetailTable(
                'bom_fabric_details',
                BOMFabricDetailModel::class,
                $request->class_id,
                function ($x) use ($request) {
                    return [
                        'bom_code'      => $request->bom_code,
                        'bom_date'      => $request->bom_date,
                        'cost_type_id'  => $request->cost_type_id,
                        'Ac_code'       => $request->Ac_code,
                        'sales_order_no'=> $request->sales_order_no,
                        'season_id'     => $request->season_id,
                        'currency_id'   => $request->currency_id,
                        'item_code'     => $request->item_code[$x],
                        'class_id'      => $request->class_id[$x],
                        'description'   => $request->description[$x],
                        'color_id'      => '',
                        'consumption'   => $request->consumption[$x],
                        'unit_id'       => $request->unit_id[$x],
                        'rate_per_unit' => $request->rate_per_unit[$x],
                        'wastage'       => $request->wastage[$x],
                        'bom_qty'       => $request->bom_qty[$x],
                        'item_qty'      => $request->bom_qty1[$x],
                        'total_amount'  => $request->total_amount[$x],
                        'remark'        => $request->remark[$x],
                    ];
                },
                function ($x) use ($request) {
                    return [
                        'sr_no'        => $request->sr_no_bom[$x],
                        'item_code'    => $request->item_code[$x],
                        'description'  => $request->description[$x],
                        'consumption'  => $request->consumption[$x],
                        'rate_per_unit'=> $request->rate_per_unit[$x],
                        'wastage'      => $request->wastage[$x],
                        'bom_qty'      => $request->bom_qty[$x],
                        'total_amount' => $request->total_amount[$x],
                    ];
                },
                $request->fabric_old_data,
                $loggerDetail,
                'Fabric',
                $request
            );
    
            // --- Sewing Trims ---
            $this->updateDetailTable(
                'bom_sewing_trims_details',
                BOMSewingTrimsDetailModel::class,
                $request->class_ids,
                function ($x) use ($request) {
                    return [
                        'bom_code'      => $request->bom_code,
                        'bom_date'      => $request->bom_date,
                        'cost_type_id'  => $request->cost_type_id,
                        'Ac_code'       => $request->Ac_code,
                        'sales_order_no'=> $request->sales_order_no,
                        'season_id'     => $request->season_id,
                        'currency_id'   => $request->currency_id,
                        'item_code'     => $request->item_codes[$x],
                        'class_id'      => $request->class_ids[$x],
                        'description'   => $request->descriptions[$x],
                        'color_id'      => $request->color_arrays[$x],
                        'size_array'    => $request->size_arrays[$x],
                        'consumption'   => $request->consumptions[$x],
                        'unit_id'       => $request->unit_ids[$x],
                        'rate_per_unit' => $request->rate_per_units[$x],
                        'wastage'       => $request->wastages[$x],
                        'bom_qty'       => $request->bom_qtys[$x],
                        'item_qty'      => $request->bom_qtys1[$x],
                        'total_amount'  => $request->total_amounts[$x],
                        'remark'        => $request->remarks[$x],
                    ];
                },
                function ($x) use ($request) {
                    return [
                        'sr_no'        => $request->sr_no_sewing_trims[$x],
                        'class_id'     => $request->class_ids[$x],
                        'item_code'    => $request->item_codes[$x],
                        'color_id'     => $request->color_arrays[$x],
                        'size_array'   => $request->size_arrays[$x],
                        'consumption'  => $request->consumptions[$x],
                        'rate_per_unit'=> $request->rate_per_units[$x],
                        'wastage'      => $request->wastages[$x],
                        'bom_qty'      => $request->bom_qtys[$x],
                        'total_amount' => $request->total_amounts[$x],
                        'remark'       => $request->remarks[$x],
                    ];
                },
                $request->sewing_old_data,
                $loggerDetail,
                'Sewing',
                $request
            );
    
            // --- Trim Fabric (no logging) ---
            if (!empty($request->class_idsx)) {
                DB::table('bom_trim_fabric_details')->where('bom_code', $request->bom_code)->delete();
                $data = [];
                foreach ($request->class_idsx as $x => $classId) {
                    $data[] = [
                        'bom_code'     => $request->bom_code,
                        'bom_date'     => $request->bom_date,
                        'cost_type_id' => $request->cost_type_id,
                        'Ac_code'      => $request->Ac_code,
                        'sales_order_no'=>$request->sales_order_no,
                        'season_id'    => $request->season_id,
                        'currency_id'  => $request->currency_id,
                        'item_code'    => $request->item_codesx[$x],
                        'class_id'     => $classId,
                        'description'  => $request->descriptionsx[$x],
                        'color_id'     => $request->color_arraysx[$x],
                        'size_array'   => $request->size_arraysx[$x],
                        'consumption'  => $request->consumptionsx[$x],
                        'unit_id'      => $request->unit_idsx[$x],
                        'rate_per_unit'=> $request->rate_per_unitsx[$x],
                        'wastage'      => $request->wastagesx[$x],
                        'bom_qty'      => $request->bom_qtysx[$x],
                        'item_qty'     => $request->bom_qtysx1[$x],
                        'total_amount' => $request->total_amountsx[$x],
                        'remark'       => $request->remarksx[$x],
                    ];
                }
                BOMTrimFabricDetailModel::insert($data);
            }
    
            // --- Packing Trims ---
            $this->updateDetailTable(
                'bom_packing_trims_details',
                BOMPackingTrimsDetailModel::class,
                $request->class_idss,
                function ($x) use ($request) {
                    return [
                        'bom_code'      => $request->bom_code,
                        'bom_date'      => $request->bom_date,
                        'cost_type_id'  => $request->cost_type_id,
                        'Ac_code'       => $request->Ac_code,
                        'sales_order_no'=> $request->sales_order_no,
                        'season_id'     => $request->season_id,
                        'currency_id'   => $request->currency_id,
                        'item_code'     => $request->item_codess[$x],
                        'class_id'      => $request->class_idss[$x],
                        'description'   => $request->descriptionss[$x],
                        'color_id'      => $request->color_arrayss[$x],
                        'size_array'    => $request->size_arrayss[$x],
                        'consumption'   => $request->consumptionss[$x],
                        'unit_id'       => $request->unit_idss[$x],
                        'rate_per_unit' => $request->rate_per_unitss[$x],
                        'wastage'       => $request->wastagess[$x],
                        'bom_qty'       => $request->bom_qtyss[$x],
                        'item_qty'      => $request->bom_qtyss1[$x],
                        'total_amount'  => $request->total_amountss[$x],
                        'remark'        => $request->remarkss[$x],
                    ];
                },
                function ($x) use ($request) {
                    return [
                        'sr_no'        => $request->sr_no_packing_trims[$x],
                        'class_id'     => $request->class_idss[$x],
                        'item_code'    => $request->item_codess[$x],
                        'color_id'     => $request->color_arrayss[$x],
                        'size_array'   => $request->size_arrayss[$x],
                        'consumption'  => $request->consumptionss[$x],
                        'rate_per_unit'=> $request->rate_per_unitss[$x],
                        'wastage'      => $request->wastagess[$x],
                        'bom_qty'      => $request->bom_qtyss[$x],
                        'total_amount' => $request->total_amountss[$x],
                        'remark'       => $request->remarkss[$x],
                    ];
                },
                $request->packing_trims_old_data,
                $loggerDetail,
                'Packing',
                $request
            );
    
            DB::commit();
            return redirect()->route('BOM.index')->with('message', 'Data Saved Successfully');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Update BOM failed", [
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
                'code'    => $e->getCode()
            ]);
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Reusable function for handling BOM detail tables with logging
     */
    private function updateDetailTable($table, $modelClass, $classIds, $rowBuilder, $logBuilder, $oldDataJson, $loggerDetail, $section, $request)
    {
        if (empty($classIds)) return;
    
        DB::table($table)->where('bom_code', $request->bom_code)->delete();
    
        $data = [];
        $newArray = [];
    
        foreach ($classIds as $x => $classId) {
            $data[] = $rowBuilder($x);
            $newArray[] = $logBuilder($x);
        }
    
        $modelClass::insert($data);
    
        $oldArray = json_decode(htmlspecialchars_decode($oldDataJson), true);
        try {
            $loggerDetail->logIfChangedBomDetail(
                'bom',
                $request->bom_code,
                $oldArray,
                $newArray,
                'UPDATE',
                $request->input('bom_date'),
                $section
            );
        } catch (\Exception $e) {
            Log::error("Logger failed for {$section}.", [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'bom_code'=> $request->bom_code,
                'data'    => $newArray
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
   public function getSalesOrderDetails(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $MasterdataList = DB::select("select * from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code='".$sales_order_no."'");
        return json_encode($MasterdataList);
    }   
     
    public function GetItemData(Request $request)
    {
        $item_code= $request->item_code;
        $data = DB::select(DB::raw("SELECT item_code, hsn_code, unit_id, item_image_path , item_description, quality_code
        from item_master where item_code='$request->item_code'")); 
        echo json_encode($data);

    } 
     

public function GetItemWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $size_id= $request->size_id;
    $color_id= $request->color_id;
    $sales_order_no= $request->sales_order_no;
//print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id","item_name")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;
    $description=$codefetch->item_name;
 
    // DB::enableQueryLog();
    $data = DB::select(DB::raw("SELECT distinct class_id ,'".$description."' as description1, max(consumption) as consumption, ".$Unit_id." as unit_id, ".$Class_id." as class_id,
     (select sum(size_qty) from buyer_purchase_order_size_detail where   
     tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))  as bom_qty,
     max(rate_per_unit) as rate_per_unit, max(wastage) as wastage, max(total_amount) as total_amount from sales_order_sewing_trims_costing_details
    where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
    //  $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
    echo json_encode($data);

}

public function GetTrimFabricWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $size_id= $request->size_id;
    $color_id= $request->color_id;
    $sales_order_no= $request->sales_order_no;
    // $Class_id= $request->class_id;
//print_r($item_code);
    $codefetch = DB::table('item_master')->select("unit_id","item_name","class_id")
    ->where('item_code','=',$request->item_code)
    ->first(); 
    $Unit_id=$codefetch->unit_id;
    $description=$codefetch->item_name;
    $class_id=$codefetch->class_id;
 
     // DB::enableQueryLog();
    $data = DB::select(DB::raw("SELECT distinct class_id , '".$description."' as description1, max(consumption) as consumption, ".$Unit_id." as unit_id, ".$class_id." as class_id,
     (select sum(size_qty) from buyer_purchase_order_size_detail where   
     tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))  as bom_qty,
     max(rate_per_unit) as rate_per_unit, max(wastage) as wastage, max(total_amount) as total_amount from sales_order_fabric_costing_details
    where  class_id='$class_id' and sales_order_no='$sales_order_no'")); 
    //dd(DB::getQueryLog());
    echo json_encode($data);

}




public function GetFabricWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 //print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id","item_name")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;
    $description=$codefetch->item_name;
    
    $data = DB::select(DB::raw("SELECT distinct class_id , '".$description."' as description1, max(consumption) as consumption,".$Unit_id." as unit_id,".$Class_id." as class_id,
     ((select sum(size_qty_total) from buyer_purchase_order_detail where item_code=$item_code and 
     tr_code='$sales_order_no') ) as bom_qty , 
    max(rate_per_unit) as rate_per_unit, max(wastage) as wastage, max(total_amount) as total_amount from sales_order_fabric_costing_details
    where  class_id=$Class_id and sales_order_no='$sales_order_no'")); 
   echo json_encode($data);
 }





public function GetPackingWiseSalesOrderCosting(Request $request)
{
$item_code= $request->item_code;
$size_id= $request->size_id;
$color_id= $request->color_id;
$sales_order_no= $request->sales_order_no;
//print_r($item_code);
$codefetch = DB::table('item_master')->select("class_id","unit_id","item_name")
->where('item_code','=',$request->item_code)
->first();
$Class_id=$codefetch->class_id;
$Unit_id=$codefetch->unit_id;
 $description=$codefetch->item_name;
//    DB::enableQueryLog();
$data = DB::select(DB::raw("SELECT distinct class_id , '".$description."' as description1,  max(consumption) as consumption,".$Unit_id." as unit_id,".$Class_id." as class_id,
 ((select sum(size_qty) from buyer_purchase_order_size_detail where   
 tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id)) ) as bom_qty,
 max(rate_per_unit) as rate_per_unit, max(wastage) as wastage, max(total_amount) as total_amount from sales_order_packing_trims_costing_details
where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
 
//  $query = DB::getQueryLog();
//  $query = end($query);
//  dd($query);
echo json_encode($data);
 
}

  
  public function GetOrderQty(Request $request)
  {
      
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($request->tr_code);
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
//  DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT sales_order_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total  from sales_order_detail inner join color_master on 
      color_master.color_id=sales_order_detail.color_id where tr_code='".$request->tr_code."' group by sales_order_detail.color_id");
       

//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
      $html = '';
      $html .= '  <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
              <thead>
              <tr>
              <th>Sr No</th>
              
              <th>Color</th>';
                 foreach ($SizeDetailList as $sz) 
                  {
                      $html.='<th class="text-center">'.$sz->size_name.'</th>';
                       
                  }
                  $html.=' 
                  <th class="text-center">Total Qty</th>
                  </tr>
              </thead>
              <tbody>';
          $no=1;
          foreach ($MasterdataList as $row) 
        {
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
             
          $html.='<td>'.$row->color_name.' </td>';

          if(isset($row->s1)) { $html.='<td class="text-right">'.$row->s1.'</td>';}
          if(isset($row->s2)) { $html.='<td class="text-right">'.$row->s2.'</td>';}
          if(isset($row->s3)) { $html.='<td class="text-right">'.$row->s3.'</td>';}
          if(isset($row->s4)) { $html.='<td class="text-right">'.$row->s4.'</td>';}
          if(isset($row->s5)) { $html.='<td class="text-right">'.$row->s5.'</td>';}
          if(isset($row->s6)) { $html.='<td class="text-right">'.$row->s6.'</td>';}
          if(isset($row->s7)) { $html.='<td class="text-right">'.$row->s7.'</td>';}
          if(isset($row->s8)) { $html.='<td class="text-right">'.$row->s8.'</td>';}
          if(isset($row->s9)) { $html.='<td class="text-right">'.$row->s9.'</td>';}
          if(isset($row->s10)) { $html.='<td class="text-right">'.$row->s10.'</td>';}
          if(isset($row->s11)) { $html.='<td class="text-right">'.$row->s11.'</td>';}
          if(isset($row->s12)) { $html.='<td class="text-right">'.$row->s12.'</td>';}
          if(isset($row->s13)) { $html.='<td class="text-right">'.$row->s13.'</td>';}
          if(isset($row->s14)) { $html.='<td class="text-right">'.$row->s14.'</td>';}
          if(isset($row->s15)) { $html.='<td class="text-right">'.$row->s15.'</td>';}
          if(isset($row->s16)) { $html.='<td class="text-right">'.$row->s16.'</td>';}
          if(isset($row->s17)) { $html.='<td class="text-right">'.$row->s17.'</td>';}
          if(isset($row->s18)) { $html.='<td class="text-right">'.$row->s18.'</td>';}
          if(isset($row->s19)) { $html.='<td class="text-right">'.$row->s19.'</td>';}
          if(isset($row->s20)) { $html.='<td class="text-right">'.$row->s20.'</td>';}
          $html.='<td class="text-right">'.$row->size_qty_total.'</td>';
          $html.='</tr>';

          $no=$no+1;
        }
        
        
       $html.=' <tr  style="background-color:#eee; text-align:center; border: 1px solid;">
  
  <th></th>

<th class="text-right">Total</th>';

 
    $SizeWsList=explode(',', $BuyerPurchaseOrderMasterList->sz_ws_total);
 
 foreach($SizeWsList  as $sztotal)
{
    $html.='<th style="text-align:right;">'.$sztotal.'</th>';

}
$html.='<th class="text-right">'.$BuyerPurchaseOrderMasterList->total_qty.'</th>

</tr>';
        
        
        
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
  
  public function GetSizeList(Request $request)
  {
  
    $codefetch = DB::table('buyer_purchse_order_master')->select("sz_code")
    ->where('tr_code','=',$request->tr_code)
    ->first();
    $sz_code=$codefetch->sz_code;
//print_r($sz_code);
    $SizeList= SizeDetailModel::select('size_id','size_name')->where('sz_code',$sz_code)->get();

    if (!$request->tr_code) {
        $html = '<option value="">--Size List--</option>';
        } else {
        $html = '';
       // $html = '<option value="">--Size List--</option>';
        
        foreach ($SizeList as $row) {
                $html .= '<option value="'.$row->size_id.'">'.$row->size_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);

  }
     
public function GetColorList(Request $request)
{
     $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
     'color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$request->tr_code)->DISTINCT()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Color List--</option>';
        } else {
        $html = '';
       // $html = '<option value="">--Color List--</option>';
        
        foreach ($ColorList as $row) 
        {$html .= '<option value="'.$row->color_id.'">'.$row->color_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}



public function GetItemColorList(Request $request)
{
    $ColorList = DB::table('buyer_purchase_order_detail')
        ->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('item_code', '=', $request->item_code)
        ->where('tr_code', '=', $request->tr_code)
        ->distinct()
        ->get();

    $data = '';
    foreach ($ColorList as $row) {
        $data .= $row->color_name . ', ';
    }

    // ✅ Remove trailing comma and space properly
    $data = rtrim($data, ', ');
    
    $Colors = [
        "color_name" => $data
    ];

    return response()->json($Colors);
}


public function GetClassItemList(Request $request)
{
   
    //$class_ids = implode(', ', $request->class_id);
    if (is_array($request->class_id) && !empty($request->class_id)) 
    {
    
        $valid_class_ids = array_filter($request->class_id, 'is_numeric');
    
        if (!empty($valid_class_ids)) 
        {
            
            $class_ids = implode(', ', $valid_class_ids);
        } 
        else 
        {
           $class_ids = $request->class_id;
        }
    } 
    else 
    {
      $class_ids = $request->class_id;
    }

    if($request->class_id == 7)
    {
        $ItemList = DB::table('item_master')->select('item_master.item_code', 'item_master.item_name')->where('item_master.delflag','=',0)->get();
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.'('.$row->item_code.') '.$row->item_name.'</option>';}
    }
    else
    {
         //DB::enableQueryLog();

         $ItemList = DB::SELECT("SELECT item_code,item_name FROM item_master WHERE delflag=0 AND class_id IN (".$class_ids.")");
        
        //dd(DB::getQueryLog());
       
            $html = '';
            $html = '<option value="">--Item List--</option>';
            
            foreach ($ItemList as $row) 
            {$html .= '<option value="'.$row->item_code.'">'.'('.$row->item_code.') '.$row->item_name.'</option>';}
    }
    
    return response()->json(['html' => $html]);
}


public function GetItemList(Request $request)
{
    $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
    ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
    ->where('tr_code','=',$request->tr_code)->where('item_master.delflag','=',0)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.'('.$row->item_code.') '.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}



public function GetTrimFabricList(Request $request)
{
    $ItemList= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get(); 
        $html = '';
        $html = '<option value="">--Item List--</option>';
         foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    
      return response()->json(['html' => $html]);
}



public function GetClassList(Request $request)
{
    $ClassList = DB::table('sales_order_fabric_costing_details')->select('sales_order_fabric_costing_details.class_id', 'class_name')
    ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_fabric_costing_details.class_id', 'left outer')
    ->where('sales_order_no','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Classification--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Classification--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}


public function GetSewingClassList(Request $request)
{
    $ClassList = DB::table('sales_order_sewing_trims_costing_details')->select('sales_order_sewing_trims_costing_details.class_id', 'class_name')
    ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_sewing_trims_costing_details.class_id', 'left outer')
    ->where('sales_order_no','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Classification--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Classification--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}



public function GetPackingClassList(Request $request)
{
    $ClassList = DB::table('sales_order_packing_trims_costing_details')->select('sales_order_packing_trims_costing_details.class_id', 'class_name')
    ->join('classification_master', 'classification_master.class_id', '=', 'sales_order_packing_trims_costing_details.class_id', 'left outer')
    ->where('sales_order_no','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Classification--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Classification--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->class_id.'">'.$row->class_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}





public function GetSewingTrimItemList(Request $request)
{
    $ClassList = DB::table('sales_order_sewing_trims_costing_details')->select('item_master.item_code', 'item_name')
    ->join('item_master', 'item_master.class_id', '=', 'sales_order_sewing_trims_costing_details.class_id', 'left outer')
    ->where('sales_order_no','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.'('.$row->item_code.')'.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}


public function GetPackingTrimItemList(Request $request)
{
    $ClassList = DB::table('sales_order_packing_trims_costing_details')->select('item_master.item_code', 'item_name')
    ->join('item_master', 'item_master.class_id', '=', 'sales_order_packing_trims_costing_details.class_id', 'left outer')
    ->where('sales_order_no','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ClassList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.'('.$row->item_code.')'.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}



     
     public function GetCostingData($soc_code)
{
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
        ->join('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
        ->join('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
        ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
        ->where('sales_order_costing_master.delflag','=', '0')
        ->where('sales_order_costing_master.soc_code','=', $soc_code)
        ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.Ac_name',
        'costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name',
        'sub_style_master.substyle_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path']);
    
        return view('saleCostingSheet',compact('SalesOrderCostingMaster'));  
} 
    
     
     public function GetMultipleBOMData()
    {
        $SalesOrderList=DB::select('select bom_code , sales_order_no from bom_master');
        return view('GetMultipleBOMData',compact('SalesOrderList'));  
    } 
     
     
     
     
      public function MultipleBOMData(Request $request)
    {
        
        $SalesOrderList=DB::select('select bom_code , sales_order_no from bom_master');
         
         $bom_code1=array();
         $bom_codes='';
        $bom_code1=$request->bom_code;
        
         foreach($bom_code1 as $bom)
        {
            $bom_codes=$bom_codes."'".$bom."',";
            
        }
        $bom_codes=rtrim($bom_codes,",");
        
      //  $bom_code=implode(",",$bom_codes);
        
        
  //  echo $bom_codes;  
  // print_r($bom_code);
        
       // DB::enableQueryLog();
    //   $FabricList = BOMFabricDetailModel::
    //   leftJoin('item_master','item_master.item_code','=','bom_fabric_details.item_code')
    //   ->join('classification_master','classification_master.class_id','=','bom_fabric_details.class_id') 
    //   ->join('unit_master','unit_master.unit_id','=','bom_fabric_details.unit_id')  
    //   ->whereIN('bom_fabric_details.bom_code', [$bom_codes])->get(); 
      //  dd(DB::getQueryLog());
      
      $FabricList=DB::select("select bom_fabric_details.*, item_name, item_image_path, description, class_name, unit_name from bom_fabric_details
      inner join item_master on item_master.item_code =bom_fabric_details.item_code
      inner join classification_master on classification_master.class_id=bom_fabric_details.class_id
      inner join unit_master on unit_master.unit_id=bom_fabric_details.unit_id
      where bom_fabric_details.bom_code in ($bom_codes) group by bom_fabric_details.sales_order_no, bom_fabric_details.item_code 
      order by bom_fabric_details.sales_order_no,bom_fabric_details.item_code");
       
    //   $TrimFabricList = BOMTrimFabricDetailModel::
    //   join('item_master','item_master.item_code','=','bom_trim_fabric_details.item_code')
    //   ->join('classification_master','classification_master.class_id','=','bom_trim_fabric_details.class_id') 
    //   ->join('unit_master','unit_master.unit_id','=','bom_trim_fabric_details.unit_id') 
    //   ->whereIN('bom_trim_fabric_details.bom_code',  [$bom_codes])->get();  
        
      $TrimFabricList=DB::select("select bom_trim_fabric_details.*, item_name, item_image_path, description, class_name, unit_name
      from bom_trim_fabric_details
      inner join item_master on item_master.item_code =bom_trim_fabric_details.item_code
      inner join classification_master on classification_master.class_id=bom_trim_fabric_details.class_id
      inner join unit_master on unit_master.unit_id=bom_trim_fabric_details.unit_id
      where bom_code in ($bom_codes)  group by bom_trim_fabric_details.sales_order_no, bom_trim_fabric_details.item_code 
      order by bom_trim_fabric_details.sales_order_no, bom_trim_fabric_details.item_code
        
        ");
         
    //   $SewingTrimsList = BOMSewingTrimsDetailModel::
    //   join('item_master','item_master.item_code','=','bom_sewing_trims_details.item_code')
    //   ->join('classification_master','classification_master.class_id','=','bom_sewing_trims_details.class_id') 
    //   ->join('unit_master','unit_master.unit_id','=','bom_sewing_trims_details.unit_id') 
    //   ->whereIN('bom_sewing_trims_details.bom_code', [$bom_codes])->get();   
         //DB::enableQueryLog();
      $SewingTrimsList=DB::select("select bom_sewing_trims_details.*, item_name, item_image_path, description, class_name, unit_name
      from bom_sewing_trims_details
      inner join item_master on item_master.item_code =bom_sewing_trims_details.item_code
      inner join classification_master on classification_master.class_id=bom_sewing_trims_details.class_id
      inner join unit_master on unit_master.unit_id=bom_sewing_trims_details.unit_id
      where bom_code in ($bom_codes) group by bom_sewing_trims_details.sales_order_no, bom_sewing_trims_details.item_code 
      order by bom_sewing_trims_details.sales_order_no, bom_sewing_trims_details.item_code");
        //dd(DB::getQueryLog());
      
        
        
    //   $PackingTrimsList = BOMPackingTrimsDetailModel::leftJoin('item_master','item_master.item_code','=','bom_packing_trims_details.item_code')
    //   ->leftJoin('classification_master','classification_master.class_id','=','bom_packing_trims_details.class_id') 
    //   ->leftJoin('unit_master','unit_master.unit_id','=','bom_packing_trims_details.unit_id') 
    //   ->whereIN('bom_packing_trims_details.bom_code',  [$bom_codes])->get();
        
      $PackingTrimsList=DB::select("select bom_packing_trims_details.*, item_name, item_image_path, description, class_name, unit_name
      from bom_packing_trims_details
      inner join item_master on item_master.item_code =bom_packing_trims_details.item_code
      inner join classification_master on classification_master.class_id=bom_packing_trims_details.class_id
      inner join unit_master on unit_master.unit_id=bom_packing_trims_details.unit_id
      where bom_code in ($bom_codes)  group by bom_packing_trims_details.sales_order_no, bom_packing_trims_details.item_code 
      order by bom_packing_trims_details.sales_order_no, bom_packing_trims_details.item_code");
        
    return view('MultipleBOMData',compact('FabricList','TrimFabricList','SewingTrimsList','PackingTrimsList','SalesOrderList'));  
         
        
    }  
     
    public function destroy($id)
    {
            $Data=   DB::select("SELECT GROUP_CONCAT(pur_code) as pur_code, count(*) as counts FROM `purchase_order` WHERE  FIND_IN_SET('".$id."', bom_code)");
            //echo $Data[0]->counts;
            if($Data[0]->counts==0)
            {
                DB::table('bom_master')->where('bom_code', $id)->delete();
                DB::table('bom_packing_trims_details')->where('bom_code',$id)->delete();
                DB::table('bom_sewing_trims_details')->where('bom_code', $id)->delete();
                DB::table('bom_fabric_details')->where('bom_code', $id)->delete();
                DB::table('bom_trim_fabric_details')->where('bom_code', $id)->delete();
                Session::flash('messagedelete', 'Deleted record successfully'); 
            }
            else
            {
                Session::flash('messagedelete', 'Data Can not Be Deleted as Purchase Order against this BOM Filled: '.$Data[0]->pur_code); 
            }
        
    }
    
    public function GetColorWiseBOMDetail()
    {
        $bomList =   DB::table('bom_master')->where('delflag','=', 0)->get();
        return view('GetColorWiseBOMDetail',compact('bomList'));  
    }     
    
    public function rptColorWiseBOMDetail(Request $request)
    {
        //DB::enableQueryLog();
        $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
                ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
                ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
                ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
                ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
                 ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'bom_master.mainstyle_id', 'left outer') 
                ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'bom_master.substyle_id', 'left outer')  
                 ->join('fg_master', 'fg_master.fg_id', '=', 'bom_master.fg_id', 'left outer')   
                ->where('bom_master.delflag','=', '0')
                ->where('bom_master.sales_order_no','=', $request->sales_order_no)
                ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
                'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
        
        //dd(DB::getQueryLog());
        $sales_order_no = $request->sales_order_no;
        $color_id = $request->color_id;
        
        return view('rptColorWiseBOMDetail',compact('BOMList','sales_order_no','color_id'));  
    } 
    
    public function GetBOMWiseColorList(Request $request)
    {
        $colorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_master.color_name')
                    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
                    ->where('buyer_purchase_order_detail.tr_code','=',$request->sales_order_no)
                    ->distinct()
                    ->get();
         
        $html = '';
        $html = '<option value="">--Select--</option>';
        
        foreach ($colorList as $row) 
        {
            $html .= '<option value="'.$row->color_id.'">'.$row->color_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
    
        
    public function GetBOMFabricFromSalesOrder(Request $request)
    {
       // DB::enableQueryLog();
      $FabricList = DB::table('buyer_purchase_order_detail')
        ->select(
            'buyer_purchase_order_detail.item_code',
            'item_master.item_name',
            'item_master.item_description',
            'unit_master.unit_id',
            'unit_master.unit_name',
            'color_master.color_id',
            'color_master.color_name',
            'classification_master.class_id',
            'classification_master.class_name',
            'sales_order_fabric_costing_details.consumption',
            'sales_order_fabric_costing_details.rate_per_unit',
            'sales_order_fabric_costing_details.bom_qty',
            'sales_order_fabric_costing_details.total_amount',
            'sales_order_fabric_costing_details.wastage',
            DB::raw('SUM(buyer_purchase_order_detail.size_qty_total) AS item_qty')
        )
        ->leftJoin('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code')
        ->leftJoin('unit_master', 'unit_master.unit_id', '=', 'item_master.unit_id')
        ->leftJoin('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
        ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
        ->leftJoin('sales_order_fabric_costing_details', function ($join) {
            $join->on('sales_order_fabric_costing_details.sales_order_no', '=', 'buyer_purchase_order_detail.tr_code')
                 ->on('sales_order_fabric_costing_details.class_id', '=', 'item_master.class_id'); // ensure class_id match
        })
        ->where('buyer_purchase_order_detail.tr_code', '=', $request->tr_code)
        ->groupBy(
            'buyer_purchase_order_detail.item_code'
        )
        ->get();

        //dd(DB::getQueryLog());

        
        // Convert to collection
        $FabricCollection = collect($FabricList);
        
        // Unique Item List
        $itemList = $FabricCollection->map(function ($item) {
            return [
                'item_code' => $item->item_code,
                'item_name' => $item->item_name,
                'item_description' => $item->item_description,
            ];
        })->unique('item_code')->values();
        
        // Unique Unit List
        $unitList = $FabricCollection->map(function ($item) {
            return [
                'unit_id' => $item->unit_id,
                'unit_name' => $item->unit_name,
            ];
        })->unique('unit_id')->values();
        
        // Unique Classification List
        $classificationList = $FabricCollection->map(function ($item) {
            return [
                'class_id' => $item->class_id,
                'class_name' => $item->class_name,
            ];
        })->unique('class_id')->values();

        $html = '';
        $sr_no = 1;
        foreach ($FabricList as $row) 
        {
            
           $ColorListsss = DB::table('buyer_purchase_order_detail')
                ->select('buyer_purchase_order_detail.color_id', 'color_name')
                ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                ->where('item_code', '=', $row->item_code)
                ->where('tr_code', '=', $request->tr_code)
                ->distinct()
                ->get();
            
            // Combine color names into comma-separated string
            $colorNames = $ColorListsss->pluck('color_name')->implode(', ');
            
            // Create array (if needed for JSON or structure)
            $Colors = [
                "color_name" => $colorNames
            ];
            
            $bom_qty= $row->item_qty * $row->consumption;
            $bom_qty1 = ($bom_qty + ($bom_qty*($row->wastage/100)));
            $total_amount = $bom_qty1 * $row->rate_per_unit;
            
            $html .= '<tr>
                          <td><input type="text" name="id" value="'.($sr_no++).'" id="id" style="width:50px;"></td>
                          <td> 
                            <select name="item_code[]" id="item_code" style="width:270px; height:30px;" disabled onchange="CheckDuplicateItemForFabric(this);">
                                <option value="">--Select--</option>';
                                foreach ($itemList as $items) {
                                    $selected = ($items['item_code'] == $row->item_code) ? 'selected="selected"' : '';
                                    $html .= '<option value="'.$items['item_code'].'" '.$selected.'>'.$items['item_name'].'</option>';
                                }
                            $html .= '</select>
                          </td>
                          <td><textarea type="text" name="colors[]" id="colors" style="width:200px; height:30px;" readonly="">'.(htmlspecialchars($Colors['color_name'])).'</textarea></td>
                          <td>
                            <select name="class_id[]" id="class_id" style="width:270px; height:30px;" disabled>
                                <option value="">--Select--</option>';
                                foreach ($classificationList as $classes) {
                                    $selected = ($classes['class_id'] == $row->class_id) ? 'selected="selected"' : '';
                                    $html .= '<option value="'.$classes['class_id'].'" '.$selected.'>'.$classes['class_name'].'</option>';
                                }
                            $html .= '</select> 
                          </td>
                          <td><input type="text" name="description[]" value="'.$row->item_description.'" style="width:200px; height:30px;" readonly></td>
                          <td><input type="number" step="any" name="consumption[]" value="'.$row->consumption.'" min="0" max="'.$row->consumption.'" id="consumption" style="width:80px; height:30px;"></td>
                          <td>  
                            <select name="unit_id[]" id="unit_id" style="width:100px; height:30px;" disabled>
                                <option value="">--Select--</option>';
                                foreach ($unitList as $units) {
                                    $selected = ($units['unit_id'] == $row->unit_id) ? 'selected="selected"' : '';
                                    $html .= '<option value="'.$units['unit_id'].'" '.$selected.'>'.$units['unit_name'].'</option>';
                                }
                            $html .= '</select>
                          </td>
                          <td><input type="number" step="any" name="rate_per_unit[]" value="'.$row->rate_per_unit.'" id="rate_per_unit" style="width:80px; height:30px;"></td>
                          <td><input type="number" step="any" name="wastage[]" value="'.$row->wastage.'" id="wastage" style="width:80px; height:30px;"></td>
                          <td>
                             <input type="text" min="0" max="'.$bom_qty1.'" name="bom_qty[]" value="'.$bom_qty1.'" id="bom_qty" style="width:80px; height:30px;" readonly>
                             <input type="hidden" name="bom_qty1[]" value="'.$row->item_qty.'" id="bom_qty1" style="width:80px; height:30px;">
                             <input type="hidden" name="bom_qty_expect[]" value="0" id="bom_qty_expect" style="width:80px; height:30px;">
                          </td>
                          <td><input type="number" step="any" class="FABRIC" name="total_amount[]" value="'.$total_amount.'" id="total_amount" style="width:80px; height:30px;" readonly></td>
                          <td><input type="text" name="remark[]" value="" id="remark" style="width:80px; height:30px;"></td>
                          <td><input type="button" name="Fbutton[]" class="btn btn-warning pull-left" value="+"></td>
                          <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X"></td>
                    </tr>';
        }
        return response()->json(['html' => $html]);
    }
    public function GetBOMFabricRepeat(Request $request)
    {
        $FabricList = DB::table('buyer_purchase_order_detail')
        ->select(
            'buyer_purchase_order_detail.item_code',
            'item_master.item_name',
            'item_master.item_description',
            'unit_master.unit_id',
            'unit_master.unit_name',
            'color_master.color_id',
            'color_master.color_name',
            'classification_master.class_id',
            'classification_master.class_name',
            'sales_order_fabric_costing_details.consumption',
            'sales_order_fabric_costing_details.rate_per_unit',
            'sales_order_fabric_costing_details.bom_qty',
            'sales_order_fabric_costing_details.total_amount',
            'sales_order_fabric_costing_details.wastage',
            DB::raw("sum(buyer_purchase_order_detail.size_qty_total) as item_qty")
        )
        ->leftJoin('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code')
        ->leftJoin('unit_master', 'unit_master.unit_id', '=', 'item_master.unit_id')
        ->leftJoin('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id')
        ->leftJoin('classification_master', 'classification_master.class_id', '=', 'item_master.class_id')
        ->leftJoin('sales_order_fabric_costing_details', 'sales_order_fabric_costing_details.sales_order_no', '=', 'buyer_purchase_order_detail.tr_code')
        ->where('buyer_purchase_order_detail.tr_code', '=', $request->tr_code)
        ->groupBy('buyer_purchase_order_detail.item_code')
        ->get();

        
        // Convert to collection
        $FabricCollection = collect($FabricList);
        
        // Unique Item List
        $itemList = $FabricCollection->map(function ($item) {
            return [
                'item_code' => $item->item_code,
                'item_name' => $item->item_name,
                'item_description' => $item->item_description,
            ];
        })->unique('item_code')->values();
        
        // Unique Unit List
        $unitList = $FabricCollection->map(function ($item) {
            return [
                'unit_id' => $item->unit_id,
                'unit_name' => $item->unit_name,
            ];
        })->unique('unit_id')->values();
        
        // Unique Classification List
        $classificationList = $FabricCollection->map(function ($item) {
            return [
                'class_id' => $item->class_id,
                'class_name' => $item->class_name,
            ];
        })->unique('class_id')->values();

        $html = '';
        $sr_no = 1;
        foreach ($FabricList as $row) 
        {
            
            $bom_qty= $row->item_qty * $row->consumption;
            $bom_qty1 = ($bom_qty + ($bom_qty*($row->wastage/100)));
            $total_amount = $bom_qty1 * $row->rate_per_unit;
            
            $html .= '<tr>
                          <td><input type="text" name="id" value="'.($sr_no++).'" id="id" style="width:50px;"></td>
                          <td> 
                            <select name="item_code[]" id="item_code" style="width:270px; height:30px;">
                                <option value="">--Select--</option>';
                                foreach ($itemList as $items) {
                                    $selected = ($items['item_code'] == $row->item_code) ? 'selected="selected"' : '';
                                    $html .= '<option value="'.$items['item_code'].'" '.$selected.'>'.$items['item_name'].'</option>';
                                }
                            $html .= '</select>
                          </td>
                          <td><textarea type="text" name="colors[]" id="colors" style="width:200px; height:30px;" readonly="">'.$row->color_name.'</textarea></td>
                          <td>
                            <select name="class_id[]" id="class_id" style="width:270px; height:30px;" disabled>
                                <option value="">--Select--</option>';
                                foreach ($classificationList as $classes) {
                                    $selected = ($classes['class_id'] == $row->class_id) ? 'selected="selected"' : '';
                                    $html .= '<option value="'.$classes['class_id'].'" '.$selected.'>'.$classes['class_name'].'</option>';
                                }
                            $html .= '</select>
                          </td>
                          <td><input type="text" name="description[]" value="'.$row->item_description.'" style="width:200px; height:30px;" readonly></td>
                          <td><input type="number" step="any" name="consumption[]" value="'.$row->consumption.'" id="consumption" style="width:80px; height:30px;" readonly></td>
                          <td>  
                            <select name="unit_id[]" id="unit_id" style="width:100px; height:30px;" disabled>
                                <option value="">--Select--</option>';
                                foreach ($unitList as $units) {
                                    $selected = ($units['unit_id'] == $row->unit_id) ? 'selected="selected"' : '';
                                    $html .= '<option value="'.$units['unit_id'].'" '.$selected.'>'.$units['unit_name'].'</option>';
                                }
                            $html .= '</select>
                          </td>
                          <td><input type="number" step="any" name="rate_per_unit[]" value="'.$row->rate_per_unit.'" id="rate_per_unit" style="width:80px; height:30px;" readonly></td>
                          <td><input type="number" step="any" name="wastage[]" value="'.$row->wastage.'" id="wastage" style="width:80px; height:30px;"></td>
                          <td>
                             <input type="text" min="0" max="'.$bom_qty1.'" name="bom_qty[]" value="'.$bom_qty1.'" id="bom_qty" style="width:80px; height:30px;" readonly>
                             <input type="hidden" name="bom_qty1[]" value="'.$row->item_qty.'" id="bom_qty1" style="width:80px; height:30px;">
                             <input type="hidden" name="bom_qty_expect[]" value="0" id="bom_qty_expect" style="width:80px; height:30px;">
                          </td>
                          <td><input type="number" step="any" class="FABRIC" name="total_amount[]" value="'.$total_amount.'" id="total_amount" style="width:80px; height:30px;" readonly></td>
                          <td><input type="text" name="remark[]" value="" id="remark" style="width:80px; height:30px;"></td>
                          <td><input type="button" name="Fbutton[]" class="btn btn-warning pull-left" value="+"></td>
                          <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X"></td>
                    </tr>';
        }
        return response()->json(['html' => $html]);
    }
    
    public function GetBOMSewingRepeat(Request $request)
    {
        $SewingData = DB::SELECT("SELECT class_id FROM sales_order_sewing_trims_costing_details WHERE sales_order_no = '".$request->sales_order_no."'");
         
        return response()->json(['SewingData' => $SewingData]);
    }
    
    public function GetBOMPackingRepeat(Request $request)
    {
        $packingData = DB::SELECT("SELECT class_id FROM sales_order_packing_trims_costing_details WHERE sales_order_no = '".$request->sales_order_no."'");
         
        return response()->json(['packingData' => $packingData]);
    }
    
    public function BOMRepeatStore(Request $request)
    {
        //echo '<pre>';print_R($_POST);exit;
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
            ->where('c_name','=','C1')
            ->where('type','=','BOM')
            ->where('firm_id','=',1)
            ->first();
            $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
            
            
            
        $this->validate($request, [
             
                'bom_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'total_cost_value'=> 'required',
                'fabric_value'=> 'required',
                'sewing_trims_value'=> 'required',
                'packing_trims_value'=> 'required',
        ]);
 
 
       try {  
           
          DB::beginTransaction();
            $data1=array(
                   
                'bom_code'=>$TrNo, 
                'bom_date'=>$request->bom_date, 
                'cost_type_id'=>$request->cost_type_id,
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'season_id'=>$request->season_id,
                'currency_id'=>$request->currency_id, 
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'total_qty'=>$request->total_qty,
                'order_rate'=>$request->order_rate,
                'fabric_value'=>$request->fabric_value, 
                'sewing_trims_value'=>$request->sewing_trims_value,
                'packing_trims_value'=>$request->packing_trims_value, 
                'total_cost_value'=>$request->total_cost_value,
                'narration'=>$request->narration,
                'is_approved'=>'0',
                'userId'=>$request->userId,
                'delflag'=>'0',
                'c_code'=>$request->c_code,
                
            );
         
            BOMMasterModel::insert($data1);
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='BOM'");
        
            $item_code= $request->input('item_code');
            if(count($item_code)>0)
            {
            
            for($x=0; $x<count($item_code); $x++) {
                
         
                    $data2[]=array(
                        
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date,  
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_code[$x],
                    'class_id' => $request->class_id[$x],
                    'description' => $request->description[$x],
                    'color_id' => '',
                    'consumption' => $request->consumption[$x],
                    'unit_id'=> $request->unit_id[$x],
                    'rate_per_unit' => $request->rate_per_unit[$x],
                    'wastage' => $request->wastage[$x],
                    'bom_qty' => $request->bom_qty[$x],
                    'item_qty' => $request->bom_qty1[$x],
                    'total_amount' => $request->total_amount[$x],
                    'remark' => $request->remark[$x],
                     );
                    }
                  BOMFabricDetailModel::insert($data2);
                 
            }
        
            $class_ids = $request->input('class_ids');
            if(count($class_ids)>0)
            {
                for($x=0; $x<count($class_ids); $x++) 
                {
                        $data3[]=array(
                        'bom_code'=>$TrNo, 
                        'bom_date'=>$request->bom_date, 
                        'cost_type_id'=>$request->cost_type_id,
                        'Ac_code'=>$request->Ac_code, 
                        'sales_order_no'=>$request->sales_order_no,
                        'season_id'=>$request->season_id,
                        'currency_id'=>$request->currency_id, 
                        'item_code' => $request->item_codes[$x],
                        'class_id' => $request->class_ids[$x],
                        'description' => $request->descriptions[$x],
                        'color_id' => $request->color_arrays[$x],
                        'size_array' => $request->size_arrays[$x],
                        'consumption' => $request->consumptions[$x],
                        'unit_id'=> $request->unit_ids[$x],
                        'rate_per_unit' => $request->rate_per_units[$x],
                        'wastage' => $request->wastages[$x],
                        'bom_qty' => $request->bom_qtys[$x],
                        'item_qty' => $request->bom_qtys1[$x],
                        'total_amount' => $request->total_amounts[$x],
                        'remark' => $request->remarks[$x],
                         );
                }
                BOMSewingTrimsDetailModel::insert($data3);
            }
             
             
             
            $class_idsx = $request->input('class_idsx');
            if(count($class_idsx)>0)
            {
             for($x=0; $x<count($class_idsx); $x++) {
                
                    $data6[]=array(
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date, 
                    'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codesx[$x],
                    'class_id' => $request->class_idsx[$x],
                    'description' => $request->descriptionsx[$x],
                    'color_id' => $request->color_arraysx[$x],
                    'size_array' => $request->size_arraysx[$x],
                    'consumption' => $request->consumptionsx[$x],
                    'unit_id'=> $request->unit_idsx[$x],
                    'rate_per_unit' => $request->rate_per_unitsx[$x],
                    'wastage' => $request->wastagesx[$x],
                    'bom_qty' => $request->bom_qtysx[$x],
                    'item_qty' => $request->bom_qtysx1[$x],
                    'total_amount' => $request->total_amountsx[$x],
                    'remark' => $request->remarksx[$x],
                      );
                    }
                  BOMTrimFabricDetailModel::insert($data6);
            }
             
             
             
             
             
            $class_idss = $request->input('class_idss');
            if(count($class_idss)>0)
            {
             for($x=0; $x<count($class_idss); $x++) {
              
                    $data4[]=array(
                        
                    'bom_code'=>$TrNo, 
                    'bom_date'=>$request->bom_date,  
                   'cost_type_id'=>$request->cost_type_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codess[$x],
                    'class_id' => $request->class_idss[$x],
                    'description' => $request->descriptionss[$x],
                    'color_id' => $request->color_arrayss[$x],
                    'size_array' => $request->size_arrayss[$x],
                    'consumption' => $request->consumptionss[$x],
                    'unit_id' => $request->unit_idss[$x],
                    'rate_per_unit' => $request->rate_per_unitss[$x],
                    'wastage' => $request->wastagess[$x],
                    'bom_qty' => $request->bom_qtyss[$x],
                    'item_qty' => $request->bom_qtyss1[$x],
                    'total_amount' => $request->total_amountss[$x],
                    'remark' => $request->remarkss[$x],
                    
                     );
                    }
                  BOMPackingTrimsDetailModel::insert($data4);
            }
            DB::commit(); 
      
            return redirect()->route('BOM.index')->with('message', 'Data Saved Succesfully');  
         } 
         catch (\Exception $e) 
         {
            // If an exception occurs, rollback the transaction and handle the exception
           \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
      
            DB::rollBack();
          
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
     
        }
           
          
    }
   
    public function PartialDispatchCostingReport(Request $request)
    { 
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate = isset($request->toDate) ? $request->toDate : date("Y-m-d");
   
        //DB::enableQueryLog();
        $Buyer_Purchase_Order_List = BuyerPurchaseOrderMasterModel::join('buyer_purchase_order_detail', 'buyer_purchase_order_detail.tr_code', '=', 'buyer_purchse_order_master.tr_code')
            ->join('sale_transaction_detail', 'sale_transaction_detail.sales_order_no', '=', 'buyer_purchase_order_detail.tr_code') 
            ->join('sale_transaction_master', 'sale_transaction_master.sale_code', '=', 'sale_transaction_detail.sale_code')
            
            // Use FIND_IN_SET to match each cpki_code in the comma-separated carton_packing_nos
            ->join('carton_packing_inhouse_detail', function ($join) {
                $join->on(DB::raw("FIND_IN_SET(carton_packing_inhouse_detail.cpki_code, sale_transaction_master.carton_packing_nos)"), '>', DB::raw('0'));
            })
        
            ->join('color_master as cpki_color', 'cpki_color.color_id', '=', 'carton_packing_inhouse_detail.color_id')
                
            ->whereBetween('sale_transaction_detail.sale_date', [$fromDate, $toDate])
        
            ->groupBy('buyer_purchase_order_detail.tr_code')
        
            ->get([
                'buyer_purchase_order_detail.*',
                'cpki_color.color_name as color_name','sale_transaction_detail.sale_date',
                DB::raw('SUM(sale_transaction_detail.order_qty) as total_order_qty')
            ]);

        //dd(DB::getQueryLog());
       
         return view('PartialDispatchCostingReport', compact('Buyer_Purchase_Order_List', 'fromDate','toDate'));
     }
}
