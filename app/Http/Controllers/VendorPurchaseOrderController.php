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
use App\Models\SalesOrderCostingMasterModel;
use App\Models\VendorPurchaseOrderModel;
use App\Models\VendorPurchaseOrderSizeDetailModel;
use App\Models\VendorPurchaseOrderDetailModel;
use App\Models\VendorPurchaseOrderFabricDetailModel;
use App\Models\VendorPurchaseOrderSewingTrimsDetailModel;
use App\Models\VendorPurchaseOrderPackingTrimsDetailModel;
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\VendorPurchaseOrderTrimFabricDetailModel;
use App\Models\VendorWorkOrderDetailModel;
use Carbon\Carbon;
use DataTables;

use Session;

class VendorPurchaseOrderController extends Controller
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
            ->where('form_id', '99')
            ->first();
    
        $job_status_id = isset($request->job_status_id) ? $request->job_status_id : 1;
        
        // DB::enableQueryLog(); 
        $baseQuery = VendorPurchaseOrderModel::join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
            ->join('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId', 'left outer')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
            ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer')
            ->join('process_master', 'process_master.process_id', '=', 'vendor_purchase_order_master.process_id', 'left outer')
            ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag', 'left outer')
            ->join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
            ->where('vendor_purchase_order_master.delflag', '=', '0')
            ->where('vendor_purchase_order_master.endflag', '=', $job_status_id)
            ->select([
                'vendor_purchase_order_master.vpo_code',
                'vendor_purchase_order_master.sales_order_no',
                'vendor_purchase_order_master.delivery_date',
                'vendor_purchase_order_master.final_bom_qty',
                'vendor_purchase_order_master.vpo_date',
                'vendor_purchase_order_master.process_id',
                'vendor_purchase_order_master.userId',
                'process_master.process_name',
                'job_status_master.job_status_name',
                'buyer_purchse_order_master.po_code',
                'main_style_master.mainstyle_name',
                'L2.ac_short_name as vendorName',
                'ledger_master.ac_short_name as Ac_name',
                'costing_type_master.cost_type_name',
                'usermaster.username',
                'vendor_purchase_order_master.updated_at',
                'vendor_purchase_order_master.endflag',
                'buyer_purchse_order_master.job_status_id'
            ]);
        // Apply filtering only when needed
        if ($request->page != 1) {
            $baseQuery->where('vendor_purchase_order_master.vpo_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 6 MONTH)'));
        }
    
        $VendorPurchaseOrderList = $baseQuery->orderByRaw("CAST(SUBSTRING(vendor_purchase_order_master.vpo_code, 4) AS UNSIGNED) ASC")->get();
    
        // dd(DB::getQueryLog());
        if ($request->ajax()) {
            return Datatables::of($VendorPurchaseOrderList)
                ->addIndexColumn()
                ->addColumn('vpo_code1', function ($row) {
                    return substr($row->vpo_code, 4, 15);
                })
                ->addColumn('updated_at', function ($row) {
                    return date("d-m-Y", strtotime($row->updated_at));
                })
                ->addColumn('chk', function ($row) 
                {
                    $chk = '<input type="checkbox" class="chk" name="chk[]" vpo_code="'.$row->vpo_code.'" style="width:40px; height: 29px;" />';
                    return $chk;
                })
                ->addColumn('action1', function ($row) {
                    return '<a target="_blank" class="btn btn-outline-secondary btn-sm print" href="VPPrint/' . $row->vpo_code . '" title="print">
                                <i class="fas fa-print"></i>
                            </a>';
                })
                ->addColumn('action2', function ($row) {
                    return '<a target="_blank" class="btn btn-outline-secondary btn-sm print" href="WashingGRNPrint/' . $row->vpo_code . '" title="print">
                                <i class="fas fa-print"></i>
                            </a>';
                }) 
                ->addColumn('action3', function ($row)  use ($chekform) 
                {  
                        if($chekform->edit_access == 1 && $row->job_status_id != 2)
                        {
                            $btn1 = '<a class="btn btn-primary btn-icon btn-sm" href="' . route('VendorPurchaseOrder.edit', $row->vpo_code) . '">
                                  <i class="fas fa-pencil-alt"></i>
                                </a>';
                        }
                        else
                        {
                            $btn1 = '<a class="btn btn-primary btn-icon btn-sm"><i class="fas fa-lock"></i></a>';
                        } 
                    return $btn1; 
                }) 
                ->addColumn('action4', function ($row)  use ($chekform) 
                {  
                        if($row->endflag != 2)
                        {
                            $btn1 = '<a class="btn btn-outline-warning btn-sm" href="javascript:void(0);" vpo_code="'.$row->vpo_code.'" onclick="closeOrder(this);" title="close_order">
                               <i class="fas fa-home"></i>
                                </a>';
                        }
                        else
                        {
                            $btn1 = 'Closed';
                        }
                    
                    
                    
                    return $btn1;
                    // }
                    // else
                    // {
                    //       $btn1 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                    // }
                     
                    // return (Session::get('username') == $row->username && $row->endflag != 2 || Session::get('user_type') == 1 && $row->endflag != 2 || Session::get('user_type') == 3 && $row->endflag != 2)
                    //     ? '<a class="btn btn-outline-warning btn-sm" href="javascript:void(0);" vpo_code="' . $row->vpo_code . '" onclick="closeOrder(this);" title="close_order">
                    //             <i class="fas fa-home"></i>
                    //         </a>'
                    //     : '<a class="btn btn-outline-secondary btn-sm" title="Close Order">
                    //             <i class="fas fa-lock"></i>
                    //         </a>';
                }) 
                ->addColumn('action5', function ($row) {
                    // Step 1: Determine the table name based on process_id
                    $table = null;
                    switch ($row->process_id) {
                        case 1: $table = 'cut_panel_grn_master'; break;
                        case 2: $table = 'finishing_inhouse_master'; break;
                        case 3: $table = 'packing_inhouse_master'; break;
                        case 4: $table = 'washing_inhouse_master'; break;
                        default: return '<a class="btn btn-danger btn-icon btn-sm" title="Delete">
                                            <i class="fas fa-lock"></i>
                                        </a>';
                    }
                
                    // Step 2: Count matching records in the selected table
                    $total_count = DB::table($table)->where('vpo_code', $row->vpo_code)->count();
                
                    // Step 3: Fetch process authorization for the user
                    $processAuth = DB::table('process_auth')
                        ->where('username', Session::get('username'))
                        ->where('process_id', $row->process_id)
                        ->first();
                
                    // Step 4: Conditions to enable delete button
                    $canDelete = ($total_count == 0) 
                                 && (optional($processAuth)->isDelete == 1) 
                                 && (Session::get('user_type') == 1 || Session::get('username') == $row->username);
                
                    if ($canDelete) {
                        return '<button class="btn btn-sm delete" data-token="' . csrf_token() . '" 
                                    data-id="' . $row->vpo_code . '" 
                                    data-route="' . route('VendorPurchaseOrder.destroy', $row->vpo_code) . '" 
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>';
                    } else {
                        return '<a class="btn btn-danger btn-icon btn-sm" title="Delete">
                                    <i class="fas fa-lock"></i>
                                </a>';
                    }
                })


                ->rawColumns(['vpo_code1', 'chk', 'action1', 'action2', 'action3', 'action4', 'action5', 'updated_at'])
                ->make(true);
        }
    
        return view('VendorPurchaseOrderList', compact('VendorPurchaseOrderList', 'chekform','job_status_id'));
    }

    public function VendorPurchaseOrderMergedPrint(Request $request)
    {
        
        $vpoCodes = explode(',', request('vpo_codes'));
        $vpoCodes1 =   request('vpo_codes');
      
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
       
        $VendorPurchaseOrderList = VendorPurchaseOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
                ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
                ->join('ledger_master as LM2', 'LM2.Ac_code', '=', 'vendor_purchase_order_master.vendorId', 'left outer')
                ->join('season_master', 'season_master.season_id', '=', 'vendor_purchase_order_master.season_id', 'left outer')
                ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_purchase_order_master.currency_id', 'left outer')
                ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer') 
                ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_purchase_order_master.substyle_id', 'left outer')  
                ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_purchase_order_master.fg_id', 'left outer')   
                ->where('vendor_purchase_order_master.delflag','=', '0')
                ->whereIN('vendor_purchase_order_master.vpo_code', [$vpoCodes])
                ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.Ac_name','LM2.Ac_name as vendorName','LM2.address', 'LM2.pan_no','LM2.gst_no','costing_type_master.cost_type_name','season_master.season_name',
                'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
                
         $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
        
        return view('VendorPurchaseOrderMergedPrint', compact('vpoCodes','FirmDetail', 'vpoCodes1', 'VendorPurchaseOrderList'));
      
    }
    
    
    public function ob_pending_list(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '99')
        ->first();
        
  
    $filter = VendorPurchaseOrderModel::join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code')
    ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId')
    ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no')
    ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id') 
    ->leftJoin('assigned_to_orders', 'assigned_to_orders.sales_order_no', '=', 'vendor_purchase_order_master.sales_order_no')  
    ->leftJoin('ob_masters', 'ob_masters.mainstyle_id', '=', 'assigned_to_orders.mainstyle_id_operation')    
     ->leftJoin('main_style_master as s1', 's1.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id')
     ->leftJoin('main_style_master_operation as s2', 's2.mainstyle_id', '=', 'ob_masters.mainstyle_id')    
    ->where('vendor_purchase_order_master.delflag','=', '0')
    ->where('vendor_purchase_order_master.process_id',1);
    
        
        if ($request->fromDate != "" && $request->toDate != "") {
            $filter->whereBetween('vendor_purchase_order_master.vpo_date', [$request->fromDate, $request->toDate]);
        }
        
        $VendorPurchaseOrderList = $filter->where('vendor_purchase_order_master.vpo_date', '>=', Carbon::now()->subDays(31));
        
        
        
        $VendorPurchaseOrderList = $filter->get([
              DB::raw('DISTINCT s2.mainstyle_name as styleOP,s1.mainstyle_name as styleERP'),  
             'vendor_purchase_order_master.*', 
            'buyer_purchse_order_master.po_code',
            'buyer_purchse_order_master.style_no',
            'L2.ac_short_name as vendorName',
            'ledger_master.ac_short_name as Ac_name',
            'brand_master.brand_name',
            'buyer_purchse_order_master.sam',
           DB::raw('(ob_masters.total_sam) as opSam')
        ]);
            
        return view('OBPending_list', compact('VendorPurchaseOrderList','chekform'));
    }

    public function VendorPurchaseOrderShowAll()
    { 
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '99')
        ->first();
        
 
        // if( $request->page == 1)
        // { 
            $VendorPurchaseOrderList = VendorPurchaseOrderModel:: 
             join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
            ->join('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId', 'left outer')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no', 'left outer')
           ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer')
            ->join('process_master', 'process_master.process_id', '=', 'vendor_purchase_order_master.process_id', 'left outer')
            ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag', 'left outer')
            ->join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
            ->where('vendor_purchase_order_master.delflag','=', '0')
            ->get(['vendor_purchase_order_master.*','process_master.process_name','job_status_master.job_status_name', 'buyer_purchse_order_master.po_code','main_style_master.mainstyle_name','L2.ac_short_name as vendorName','ledger_master.ac_short_name as Ac_name','costing_type_master.cost_type_name','usermaster.username']);
  
        // }
        // else
        // {
              
        // } 
       
        
        if ($request->ajax()) 
        {
                return Datatables::of($VendorPurchaseOrderList)
                ->addIndexColumn()
                ->addColumn('vpo_code1',function ($row) {
                     $vpo_code1 = substr($row->vpo_code,4,15);
                     return $vpo_code1;
                }) 
                ->addColumn('updated_at',function ($row) 
                {
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
                     return $updated_at;
                })  
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a target="_blank" class="btn btn-outline-secondary btn-sm print" href="VPPrint/'.$row->vpo_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                     return $btn1;
                })
                ->addColumn('action2', function ($row) 
                {
                    $btn1 = '<a target="_blank" class="btn btn-outline-secondary btn-sm print" href="WashingGRNPrint/'.$row->vpo_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                     return $btn1;
                })
                ->addColumn('action3', function ($row) use ($chekform)
                { 
                    if($chekform->edit_access==1)
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('VendorPurchaseOrder.edit', $row->vpo_code).'" >
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
                ->addColumn('action4', function ($row) use ($chekform)
                { 
                     if($row->username == Session::get('username') OR Session::get('user_type') == 1)  
                     {
                        $btn4='<td>
                                <a class="btn btn-outline-warning btn-sm" href="javascript:void(0);" vpo_code="'.$row->vpo_code.'" onclick="closeOrder(this);" title="close_order">
                                <i class="fas fa-home"></i>
                                </a>
                             </td>';
                     }
                     else
                     {
                     $btn4='<td>
                            <a class="btn btn-outline-secondary btn-sm" href="" title="Close Order">
                            <i class="fas fa-lock"></i>
                            </a>
                         </td>';
                     }
                    return $btn4;
                })
                ->addColumn('action5', function ($row) use ($chekform){
             
                    if($row->process_id == 1)
                    {
                        $cuttingData = DB::SELECT("select count(*) as total_count FROM cut_panel_grn_master WHERE vpo_code='".$row->vpo_code."'");
                        $total_count = isset($cuttingData[0]->total_count) ? $cuttingData[0]->total_count : 0;
                    }
                    else if($row->process_id == 2)
                    {
                        $finishingData = DB::SELECT("select count(*) as total_count FROM finishing_inhouse_master WHERE vpo_code='".$row->vpo_code."'");
                        $total_count = isset($finishingData[0]->total_count) ? $finishingData[0]->total_count : 0; 
                    }
                    else if($row->process_id == 3)
                    {
                        $packingData = DB::SELECT("select count(*) as total_count FROM packing_inhouse_master WHERE vpo_code='".$row->vpo_code."'");
                        $total_count = isset($packingData[0]->total_count) ? $packingData[0]->total_count : 0;
                    }
                    else if($row->process_id == 4)
                    {
                        $washingData = DB::SELECT("select count(*) as total_count FROM washing_inhouse_master WHERE vpo_code='".$row->vpo_code."'");
                        $total_count = isset($washingData[0]->total_count) ? $washingData[0]->total_count : 0;
                    }
                    else
                    {
                         $total_count = 0;
                    }
       
                    if($total_count == 0 && $isDelete==1 && $row->username == Session::get('username') OR Session::get('user_type') == 1)
                    {      
             
                        $btn5 = '<button class="btn btn-sm delete"  data-placement="top" id="DeleteRecord" data-token="'.csrf_token().'" data-id="'.$row->vpo_code.'"  data-route="'.route('VendorPurchaseOrder.destroy', $row->vpo_code ).'" title="Delete">
                                <i class="fas fa-trash"></i>
                                </button>'; 
                    }  
                    else
                    {
                        $btn5 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['vpo_code1','action1','action2','action3','action4','action5','updated_at'])
        
                ->make(true);
        }
        return view('VendorPurchaseOrderList', compact('chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='VendorPurchaseOrder'");
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $ProcessList= DB::table('process_master')->join('process_auth', 'process_auth.process_id', '=', 'process_master.process_id')->where('process_auth.username', Session::get('username'))->where('process_master.delflag','=', '0')->groupBy('process_auth.process_id')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $Ledger2 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $SalesOrderList= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')->get();
         
        return view('VendorPurchaseOrder',compact('UnitList','ProcessList','ClassList','ClassList2','ClassList3','ItemList2','ItemList3','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','Ledger2','QualityList', 'CPList', 'CurrencyList', 'SeasonList', 'counter_number'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            DB::beginTransaction();
            //echo '<pre>'; print_R($_POST);exit;
            $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
            ->where('c_name','=','C1')
            ->where('type','=','VendorPurchaseOrder')
            ->where('firm_id','=',1)
            ->first();
            
            if($request->process_id==1)
            {
                    $TrNo='CPO'.'-'.$codefetch->tr_no;  
            }
            elseif($request->process_id==2)
            {
                 $TrNo='FPO'.'-'.$codefetch->tr_no;  
            }
            elseif($request->process_id==3)
            {
                 $TrNo='PPO'.'-'.$codefetch->tr_no;  
            }
            elseif($request->process_id==4)
            {
                 $TrNo='WPO'.'-'.$codefetch->tr_no;  
            }
            elseif($request->process_id==5)
            {
                 $TrNo='EMB'.'-'.$codefetch->tr_no;  
            }
            elseif($request->process_id==6)
            {
                 $TrNo='PRT'.'-'.$codefetch->tr_no;  
            }
            
            
            $this->validate($request, [
                         
                            'vpo_date'=> 'required', 
                            'Ac_code'=> 'required', 
                            'sales_order_no'=>'required',
                        
                           
            ]);
             
             
            $data1=array(
                    'vpo_code'=>$TrNo,
                    'vpo_date'=>$request->vpo_date, 
                    'delivery_date'=>$request->delivery_date,
                    'cost_type_id'=>$request->cost_type_id,
                    'process_id'=>$request->process_id,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'order_rate'=>$request->order_rate,
                    'narration'=>$request->narration,
                    'userId'=>$request->userId,
                    'delflag'=>'0',
                    'c_code'=>$request->c_code,
                    'vendorId'=>$request->vendorId,
                     'final_bom_qty'=>$request->final_bom_qty,
                     'endflag'=>'1',
                     'line_id'=> isset($request->line_id) ? $request->line_id : 0
                );
             
                VendorPurchaseOrderModel::insert($data1);
                DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='VendorPurchaseOrder'");
            
                $color_id= $request->input('color_id');
                if(count($color_id)>0)
                {   
                
                for($x=0; $x<count($color_id); $x++) {
                    # code...
                  if($request->size_qty_total[$x]>0)
                          {
                                $data2[]=array(
                      
                                'vpo_code'=>$TrNo,
                                'vpo_date'=>$request->vpo_date,
                                'process_id'=>$request->process_id,
                                'sales_order_no'=>$request->sales_order_no,
                                'Ac_code'=>$request->Ac_code,
                                'po_code'=>$request->po_code,
                                'style_no'=>$request->style_no,
                                'item_code'=>$request->item_codef[$x],
                                'color_id'=>$request->color_id[$x],
                                'size_array'=>$request->size_array[$x],
                                'size_qty_array'=>$request->size_qty_array[$x],
                                'size_qty_total'=>$request->size_qty_total[$x],
                               
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
                              
                                    'vpo_code'=>$TrNo, 
                                    'vpo_date'=>$request->vpo_date, 
                                    'process_id'=>$request->process_id,
                                    'Ac_code'=>$request->Ac_code,
                                    'sales_order_no'=>$request->sales_order_no,
                                    'po_code'=>$request->po_code,
                                    'style_no'=>$request->style_no,
                                     'item_code'=>$request->item_codef[$x],
                                    'color_id'=>$request->color_id[$x],
                                   
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
                                   
                                      );
                          
                          } // if loop avoid zero qty
                        }
                      VendorPurchaseOrderDetailModel::insert($data2);
                      VendorPurchaseOrderSizeDetailModel::insert($data3);
                      
                     
                }
                
                $item_code= $request->input('item_code');
                if(isset($item_code) && count($item_code)>0)
                {   
                
                for($x=0; $x<count($request->item_code); $x++) 
                {
                    # code...
             
                        $data4[]=array(
                        'vpo_code'=>$TrNo, 
                        'vpo_date'=>$request->vpo_date,  
                        'cost_type_id'=>$request->cost_type_id,
                        'process_id'=>$request->process_id,
                        'Ac_code'=>$request->Ac_code, 
                        'sales_order_no'=>$request->sales_order_no,
                        'season_id'=>$request->season_id,
                        'currency_id'=>$request->currency_id, 
                        'item_code' => $request->item_code[$x],
                        'class_id' => $request->class_id[$x],
                        'description' => $request->description[$x],
                        'color_id' => isset($request->color_id[$x]) ? $request->color_id[$x] : "",
                        'consumption' => $request->consumption[$x],
                        'unit_id'=> $request->unit_id[$x],
                        'wastage' => $request->wastage[$x],
                        'bom_qty' => $request->bom_qty[$x] ,
                         'actual_qty' => $request->bom_qty1[$x],
                        'final_cons' => $request->final_cons[$x],
                        'size_qty' => $request->size_qty[$x] 
                        );
                           
                } // if loop avoid zero qty
                           
                           VendorPurchaseOrderFabricDetailModel::insert($data4);
                        }
                       
               
                $item_codesx= $request->input('item_codesx');
                if(isset($item_codesx) && count($item_codesx)>0)
                {        
                      for($x=0; $x<count($request->item_codesx); $x++) 
                {
                    # code...
             
                        $data5[]=array(
                        'vpo_code'=>$TrNo, 
                        'vpo_date'=>$request->vpo_date,  
                        'cost_type_id'=>$request->cost_type_id,
                        'process_id'=>$request->process_id,
                        'Ac_code'=>$request->Ac_code, 
                        'sales_order_no'=>$request->sales_order_no,
                        'season_id'=>$request->season_id,
                        'currency_id'=>$request->currency_id, 
                        'item_code' => $request->item_codesx[$x],
                        'class_id' => $request->class_idsx[$x],
                        'description' => $request->descriptionsx[$x],
                        'consumption' => $request->consumptionsx[$x],
                        'unit_id'=> $request->unit_idsx[$x],
                        'wastage' => $request->wastagesx[$x],
                        'bom_qty' => $request->bom_qtysx[$x] ,
                         'actual_qty' => $request->bom_qtysx1[$x],
                        'final_cons' => $request->final_conssx[$x],
                        'size_qty' => $request->size_qtysx[$x] 
                        );
                           
                } // if loop avoid zero qty
                           
                           VendorPurchaseOrderTrimFabricDetailModel::insert($data5);
                        }  
                       
                       
                       
                       
                       $item_codess = $request->input('item_codess');
                if(isset($item_codess) && count($item_codess)>0)
                {
                 for($x=0; $x<count($request->item_codess); $x++) {
                    # code...
                   
                        $data7[]=array(
                        'vpo_code'=>$TrNo,
                       'vpo_date'=>$request->vpo_date,  
                        'cost_type_id'=>$request->cost_type_id,
                        'process_id'=>$request->process_id,
                        'Ac_code'=>$request->Ac_code, 
                        'sales_order_no'=>$request->sales_order_no,
                        'season_id'=>$request->season_id,
                        'currency_id'=>$request->currency_id, 
                        'item_code' => $request->item_codess[$x],
                        'class_id' => $request->class_idss[$x],
                        'description' => '',
                        'consumption' => $request->consumptionss[$x],
                        'unit_id'=> $request->unit_idss[$x],
                        'wastage' => $request->wastagess[$x],
                        'bom_qty' => $request->bom_qtyss[$x],
                         'actual_qty' => $request->bom_qtyss1[$x],
                        'final_cons' => $request->final_consss[$x],
                        'size_qty' => $request->size_qtyss[$x] 
                        
                        );
                        }
                      VendorPurchaseOrderPackingTrimsDetailModel::insert($data7);
                }  
        
            $InsertSizeData=DB::select('call AddSizeQtyFromVendorPurchaseOrder("'.$TrNo.'")');
            DB::commit();
            return redirect()->route('VendorPurchaseOrder.index')->with('message', 'Data Saved Succesfully'); 
        
         }
         catch (\Exception $e) 
         {
            // If an exception occurs, rollback the transaction and handle the exception
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
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code)
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('budgetPrint', compact('BOMList'));  
      
    }

    public function VPPrint($vpo_code)
    {
       $BOMList = VendorPurchaseOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
        ->join('ledger_master as LM2', 'LM2.Ac_code', '=', 'vendor_purchase_order_master.vendorId', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_purchase_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_purchase_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_purchase_order_master.substyle_id', 'left outer')  
        ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_purchase_order_master.fg_id', 'left outer')   
        ->where('vendor_purchase_order_master.delflag','=', '0')
        ->where('vendor_purchase_order_master.vpo_code','=', $vpo_code)
        ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.Ac_name','LM2.Ac_name as vendorName','LM2.address', 'LM2.pan_no','LM2.gst_no','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
         $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
        
    
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('cuttingOrderPrint', compact('BOMList','FirmDetail'));     
    }

     public function VPPrintView($vpo_code)
    {
       $BOMList = VendorPurchaseOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
        ->join('ledger_master as LM2', 'LM2.Ac_code', '=', 'vendor_purchase_order_master.vendorId', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_purchase_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_purchase_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_purchase_order_master.substyle_id', 'left outer')  
        ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_purchase_order_master.fg_id', 'left outer')   
        ->where('vendor_purchase_order_master.delflag','=', '0')
        ->where('vendor_purchase_order_master.vpo_code','=', $vpo_code)
        ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.Ac_name','LM2.Ac_name as vendorName','LM2.address', 'LM2.pan_no','LM2.gst_no','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
         $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
        
 
        return view('VPPrintView', compact('BOMList','FirmDetail'));     
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $JobStatusList= DB::table('job_status_master')->whereIn('job_status_id',[1,2])->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
       // $ItemList = ItemModel::where('delflag','=', '0')->where('class_id','=', $codefetch->class_id)->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $Ledger2 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList5= ItemModel::where('delflag','=', '0')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ProcessList= DB::table('process_master')->join('process_auth', 'process_auth.process_id', '=', 'process_master.process_id')->where('process_auth.username', Session::get('username'))->where('process_master.delflag','=', '0')->groupBy('process_auth.process_id')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $UnitList3 = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $VendorPurchaseOrderMasterList = VendorPurchaseOrderModel::find($id);
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$VendorPurchaseOrderMasterList->sales_order_no)->distinct()->get();
        
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$VendorPurchaseOrderMasterList->sales_order_no)->DISTINCT()->get();
        
        $VendorPurchaseOrderDetailList = VendorPurchaseOrderDetailModel::where('vendor_purchase_order_detail.vpo_code','=', $VendorPurchaseOrderMasterList->vpo_code)->get();
    //   DB::enableQueryLog(); 
        
        $FabricList = VendorPurchaseOrderFabricDetailModel::select('vendor_purchase_order_fabric_details.*', 
        DB::raw("(select ifnull(count(item_code),0) from fabric_outward_details where 
        fabric_outward_details.item_code=vendor_purchase_order_fabric_details.item_code
        and   fabric_outward_details.vpo_code='$VendorPurchaseOrderMasterList->vpo_code') as item_count"))->
        where('vendor_purchase_order_fabric_details.vpo_code','=', $VendorPurchaseOrderMasterList->vpo_code)->get();
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        
     
        $TrimFabricList = VendorPurchaseOrderTrimFabricDetailModel::where('vendor_purchase_order_trim_fabric_details.vpo_code','=', $VendorPurchaseOrderMasterList->vpo_code)->get();
       
         //  DB::enableQueryLog();
        $PackingTrimsList = DB::table('vendor_purchase_order_packing_trims_details')->select("*")->where('vendor_purchase_order_packing_trims_details.vpo_code','=', $VendorPurchaseOrderMasterList->vpo_code)->groupBy('item_code')->get();
         //dd(DB::getQueryLog());
      
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        // $SewingTrimsList = VendorPurchaseOrderSewingTrimsDetailModel::where('vendor_work_order_sewing_trims_details.vVPO_code','=', $VendorPurchaseOrderMasterList->vw_code)->get();
        // $PackingTrimsList = VendorPurchaseOrderPackingTrimsDetailModel::where('vendor_work_order_packing_trims_details.vw_code','=', $VendorPurchaseOrderMasterList->vw_code)->get();
        
        // DB::enableQueryLog(); 
        
        $S1= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
        ->whereNotIn('sales_order_costing_master.sales_order_no',function($query){
        $query->select('sales_order_no')->from('vendor_purchase_order_master');
        });
        $S2=VendorPurchaseOrderModel::select('sales_order_no')->where('sales_order_no',$VendorPurchaseOrderMasterList->sales_order_no);
        $SalesOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($VendorPurchaseOrderMasterList->sales_order_no);
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
        where tr_code='".$VendorPurchaseOrderMasterList->sales_order_no."' group by sales_order_detail.color_id");
        
        if($VendorPurchaseOrderMasterList->process_id==1)
        {
            //  DB::enableQueryLog(); 
                $VendorProcessDataList = DB::select("SELECT  1 as process_id,cut_panel_grn_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from cut_panel_grn_size_detail 
                inner join color_master on color_master.color_id=cut_panel_grn_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by 	cut_panel_grn_size_detail.color_id");
                
        //          $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
                
        }
        elseif($VendorPurchaseOrderMasterList->process_id==2)
        {
                $VendorProcessDataList = DB::select("SELECT  2 as process_id,finishing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from finishing_inhouse_size_detail 
                inner join color_master on color_master.color_id=finishing_inhouse_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by	finishing_inhouse_size_detail.color_id");
        }
        elseif($VendorPurchaseOrderMasterList->process_id==3)
        {
                $VendorProcessDataList = DB::select("SELECT  3 as process_id, packing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from packing_inhouse_size_detail 
                inner join color_master on color_master.color_id=packing_inhouse_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by 	packing_inhouse_size_detail.color_id");
        }
        elseif($VendorPurchaseOrderMasterList->process_id==4)
        {
                $VendorProcessDataList = DB::select("SELECT  4 as process_id,washing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from washing_inhouse_size_detail 
                inner join color_master on color_master.color_id=washing_inhouse_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by washing_inhouse_size_detail.color_id");
        }
        else
        {
            $VendorProcessDataList = "";
        }
       
        $LineList= DB::table('line_master')->where('Ac_code','=',$VendorPurchaseOrderMasterList->vendorId)->get();
        
        return view('VendorPurchaseOrderEdit',compact('LineList','VendorPurchaseOrderDetailList','Ledger2','JobStatusList','UnitList3','VendorProcessDataList','PackingTrimsList', 'ProcessList', 'ColorList','TrimFabricList',  'MasterdataList','SizeDetailList','VendorPurchaseOrderMasterList','FabricList', 'UnitList','ClassList','ClassList3', 'ItemList','ItemList3','ItemList5', 'MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $soc_code)
    {
        
        
        try
        {
            DB::beginTransaction();
        //echo '<pre>';print_r($_POST);exit;
          $this->validate($request, [
             
                        'vpo_date'=> 'required', 
                        'Ac_code'=> 'required', 
                        'sales_order_no'=> 'required', 
                       
            ]);
         
          
            $data1=array(
                'vpo_code'=>$request->vpo_code, 
                'vpo_date'=>$request->vpo_date, 
                 'delivery_date'=>$request->delivery_date,
                'cost_type_id'=>$request->cost_type_id,
                'process_id'=>$request->process_id,
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'season_id'=>$request->season_id,
                'currency_id'=>$request->currency_id, 
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                 'order_rate'=>$request->order_rate,
                'narration'=>$request->narration,
                'userId'=>$request->userId,
                'delflag'=>'0',
                'c_code'=>$request->c_code,
                'vendorId'=>$request->vendorId,
                 'final_bom_qty'=>$request->final_bom_qty,
                 'endflag'=>$request->endflag,
                 'line_id'=> isset($request->line_id) ? $request->line_id : 0
            );
        //   DB::enableQueryLog();   
        $VendorPurchaseOrderList = VendorPurchaseOrderModel::findOrFail($request->vpo_code); 
        //  $query = DB::getQueryLog();
        //         $query = end($query);
        //         dd($query);
        $VendorPurchaseOrderList->fill($data1)->save();
        
        DB::table('vendor_purchase_order_trim_fabric_details')->where('vpo_code', $request->input('vpo_code'))->delete();
        DB::table('vendor_purchase_order_packing_trims_details')->where('vpo_code', $request->input('vpo_code'))->delete();
        DB::table('vendor_purchase_order_fabric_details')->where('vpo_code', $request->input('vpo_code'))->delete();
        DB::table('vendor_purchase_order_size_detail')->where('vpo_code', $request->input('vpo_code'))->delete();
        DB::table('vendor_purchase_order_size_detail2')->where('vpo_code', $request->input('vpo_code'))->delete();
        DB::table('vendor_purchase_order_detail')->where('vpo_code', $request->input('vpo_code'))->delete();
         
          $color_id= $request->input('color_id');
            if(!empty($color_id))
            {   
            
            for($x=0; $x<count($color_id); $x++) {
                # code...
              if($request->size_qty_total[$x]>0)
                      {
                            $data2[]=array(
                  
                            'vpo_code'=>$request->vpo_code, 
                            'vpo_date'=>$request->vpo_date, 
                            'process_id'=>$request->process_id,
                            'sales_order_no'=>$request->sales_order_no,
                            'Ac_code'=>$request->Ac_code,
                            'po_code'=>$request->po_code,
                            'style_no'=>$request->style_no,
                            'item_code'=>$request->item_codef[$x],
                            'color_id'=>$request->color_id[$x],
                            'size_array'=>$request->size_array[$x],
                            'size_qty_array'=>$request->size_qty_array[$x],
                            'size_qty_total'=>$request->size_qty_total[$x],
                           
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
                          
                                'vpo_code'=>$request->vpo_code, 
                                'vpo_date'=>$request->vpo_date,  
                                'process_id'=>$request->process_id,
                                'Ac_code'=>$request->Ac_code,
                                'sales_order_no'=>$request->sales_order_no,
                                'po_code'=>$request->po_code,
                                'style_no'=>$request->style_no,
                                 'item_code'=>$request->item_codef[$x],
                                'color_id'=>$request->color_id[$x],
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
                                  );
                      
                      } // if loop avoid zero qty
                    }
                  VendorPurchaseOrderDetailModel::insert($data2);
                  VendorPurchaseOrderSizeDetailModel::insert($data3);
                   
            }
            
            
            $color_id= $request->color_id;
         
            if(!empty($color_id))
            {   
            
            for($x=0; $x<count($color_id); $x++) 
            {
                # code...
         
                    $data4[]=array(
                    'vpo_code'=>$request->vpo_code, 
                    'vpo_date'=>$request->vpo_date,  
                    'cost_type_id'=>$request->cost_type_id,
                    'process_id'=>$request->process_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codef[$x],
                    'class_id' => isset($request->class_id[$x]) ? $request->class_id[$x] : 0,
                    'description' => isset($request->description[$x]) ? $request->description[$x] : "",
                    'color_id' => isset($request->color_id[$x]) ? $request->color_id[$x] : 0,
                    'consumption' =>isset($request->consumption[$x]) ? $request->consumption[$x] : 0,  
                    'unit_id'=> isset($request->unit_id[$x]) ? $request->unit_id[$x] : 0,  
                    'wastage' => isset($request->wastage[$x]) ? $request->wastage[$x] : 0,   
                    'bom_qty' =>  isset($request->bom_qty[$x]) ? $request->bom_qty[$x] : 0,   
                     'actual_qty' =>  isset($request->bom_qty1[$x]) ? $request->bom_qty1[$x] : 0,   
                    'final_cons' =>isset($request->final_cons[$x]) ? $request->final_cons[$x] : 0, 
                    'size_qty' => isset($request->size_qty[$x]) ? $request->size_qty[$x] : 0 
                    );
                       
            } // if loop avoid zero qty
                       
                       VendorPurchaseOrderFabricDetailModel::insert($data4);
                    }
                   
                   
                $item_codesx= $request->input('item_codesx');
                
            if(isset($item_codesx) &&   count($item_codesx)>0)
            {        
                  for($x=0; $x<count( $request->item_codesx); $x++) 
                  {
                # code...
         
                    $data5 = array(
                    'vpo_code'=>$request->vpo_code, 
                    'vpo_date'=>$request->vpo_date,  
                    'cost_type_id'=>$request->cost_type_id,
                    'process_id'=>$request->process_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' => $request->item_codesx[$x],
                    'class_id' => $request->class_idsx[$x],
                    'description' => $request->descriptionsx[$x],
                    'consumption' => $request->consumptionsx[$x],
                    'unit_id'=> $request->unit_idsx[$x],
                    'wastage' => $request->wastagesx[$x],
                    'bom_qty' => $request->bom_qtysx[$x] ,
                     'actual_qty' => $request->bom_qtysx1[$x],
                    'final_cons' => $request->final_conssx[$x],
                    'size_qty' => $request->size_qtysx[$x] 
                    );
                   VendorPurchaseOrderTrimFabricDetailModel::insert($data5);
                  } // if loop avoid zero qty
                       
                      
                    }      
                   
                   
            $class_idss = $request->class_idss;
            //print_r($class_idss);exit; 
            if($class_idss != "")
            {
             for($x=0; $x<count($request->class_idss); $x++) {
                # code...
               
                    $data7[]=array(
                    'vpo_code'=>$request->vpo_code,
                   'vpo_date'=>$request->vpo_date,
                    'cost_type_id'=>$request->cost_type_id,
                    'process_id'=>$request->process_id,
                    'Ac_code'=>$request->Ac_code, 
                    'sales_order_no'=>$request->sales_order_no,
                    'season_id'=>$request->season_id,
                    'currency_id'=>$request->currency_id, 
                    'item_code' =>isset($request->item_codess[$x]) ? $request->item_codess[$x] : "",   
                    'class_id' =>isset($request->class_idss[$x]) ? $request->class_idss[$x] : "",    
                    'description' => isset($request->descriptionss[$x]) ? $request->descriptionss[$x] : "",  
                    'consumption' =>isset($request->consumptionss[$x]) ? $request->consumptionss[$x] : "",   
                    'unit_id'=>isset($request->unit_idss[$x]) ? $request->unit_idss[$x] : "",   
                    'wastage' =>isset($request->wastagess[$x]) ? $request->wastagess[$x] : "",    
                    'bom_qty' =>isset($request->bom_qtyss[$x]) ? $request->bom_qtyss[$x] : "",  
                     'actual_qty' =>isset($request->bom_qtyss1[$x]) ? $request->bom_qtyss1[$x] : "",  
                    'final_cons' =>isset($request->final_consss[$x]) ? $request->final_consss[$x] : "",   
                    'size_qty' =>isset($request->size_qtyss[$x]) ? $request->size_qtyss[$x] : "",   
                    
                    );
                    }
                  VendorPurchaseOrderPackingTrimsDetailModel::insert($data7);
            } 
                    
            $InsertSizeData=DB::select('call AddSizeQtyFromVendorPurchaseOrder("'.$request->vpo_code.'")'); 
            DB::commit();
            return redirect()->route('VendorPurchaseOrder.index')->with('message', 'Data Saved Succesfully'); 
         }
         catch (\Exception $e) 
         {
            // If an exception occurs, rollback the transaction and handle the exception
             \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
          
              DB::rollBack();
      
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
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
      

public function GetItemWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $size_id= $request->size_id;
    $color_id= $request->color_id;
    $sales_order_no= $request->sales_order_no;
//print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;

 
    // DB::enableQueryLog();
    $data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`, ".$Unit_id." as unit_id, ".$Class_id." as class_id,
     (select sum(size_qty) from buyer_purchase_order_size_detail where   
     tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))*bom_qty as bom_qty,
     `rate_per_unit`, `wastage`, `total_amount` from sales_order_sewing_trims_costing_details
    where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
    //  $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
    echo json_encode($data);

}


public function GetFabricWiseSalesOrderCosting(Request $request)
{
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 //print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;
    $data = DB::select(DB::raw("SELECT distinct class_id ,   `description`, `consumption`,".$Unit_id." as unit_id,".$Class_id." as class_id,
     (select sum(size_qty_total) from buyer_purchase_order_detail where item_code=$item_code and 
     tr_code='$sales_order_no') as bom_qty , 
    `rate_per_unit`, `wastage`, `total_amount` from sales_order_fabric_costing_details
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
$codefetch = DB::table('item_master')->select("class_id","unit_id")
->where('item_code','=',$request->item_code)
->first();
$Class_id=$codefetch->class_id;
$Unit_id=$codefetch->unit_id;
//    DB::enableQueryLog();
$data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`,".$Unit_id." as unit_id,".$Class_id." as class_id,
 ((select sum(size_qty) from buyer_purchase_order_size_detail where   
 tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))*bom_qty) as bom_qty,
 `rate_per_unit`, `wastage`, `total_amount` from sales_order_packing_trims_costing_details
where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
 
//  $query = DB::getQueryLog();
//  $query = end($query);
//  dd($query);
echo json_encode($data);
 
}

  
  public function VPO_GetOrderQty(Request $request)
  {
      // VPO_   as Vendor Work Order sa same function name is defined in BOM, So Name prepended by VPO_
      
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($request->tr_code);
    //   DB::enableQueryLog();  
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::where('tr_code',$request->tr_code)->first();
      
       $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$request->tr_code)->DISTINCT()->get();
      
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'sum(s'.$no.')+(sum(s'.$no.')*((shipment_allowance+garment_rejection_allowance)/100)) as s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        //   DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT sales_order_detail.item_code, sales_order_detail.color_id, color_name, ".$sizes.", 
      (sum(size_qty_total)+(sum(size_qty_total)*((shipment_allowance+garment_rejection_allowance)/100))) as size_qty_total from sales_order_detail inner join color_master on 
      color_master.color_id=sales_order_detail.color_id where tr_code='".$request->tr_code."'
      group by sales_order_detail.color_id");
       

//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
      $html = '';
      $html .= '  <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
              <thead>
              <tr>
              <th>SrNo</th>
              
              <th>Color</th>';
                 foreach ($SizeDetailList as $sz) 
                  {
                      $html.='<th>'.$sz->size_name.'</th>';
                  }
                  $html.=' 
                  <th>Total Qty</th>
                   <th>Action   <input type="button" class="size_btn btn-primary" id="MBtn" is_click="0" value="Calculate All" onclick="MainBtn();this.disabled=true;"></th>
                  </tr>
              </thead>
              <tbody>';
          $no=1;
          foreach ($MasterdataList as $row) 
        {
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
           
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="'.$row->item_code.'" required />
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $row1)
        {
            $html.='<option value="'.$row1->color_id.'"';
            $row1->color_id == $row->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$row1->color_name.'</option>';
        }
        
        $html.='</select></td>';



         
             $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'sum(s'.$nox.') as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
        // DB::enableQueryLog();  
      $CompareList = DB::select("SELECT vendor_purchase_order_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from vendor_purchase_order_size_detail inner join color_master on 
      color_master.color_id=vendor_purchase_order_size_detail.color_id where 
      vendor_purchase_order_size_detail.sales_order_no='".$request->tr_code."' and
      vendor_purchase_order_size_detail.color_id='".$row->color_id."' and vendor_purchase_order_size_detail.process_id='".$request->process_id."'");


// $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);

 
foreach($CompareList as $List)
{
   if(isset($row->s1)) { $s1=((round($row->s1))-(intval($List->s_1))); }
   if(isset($row->s2)) { $s2=((round($row->s2))-(intval($List->s_2))); }
   if(isset($row->s3)) { $s3=((round($row->s3))-(intval($List->s_3))); }
   if(isset($row->s4)) { $s4=((round($row->s4))-(intval($List->s_4))); }
   if(isset($row->s5)) { $s5=((round($row->s5))-(intval($List->s_5))); }
   if(isset($row->s6)) { $s6=((round($row->s6))-(intval($List->s_6))); }
   if(isset($row->s7)) { $s7=((round($row->s7))-(intval($List->s_7)));}
   if(isset($row->s8)) { $s8=((round($row->s8))-(intval($List->s_8)));}
   if(isset($row->s9)) { $s9=((round($row->s9))-(intval($List->s_9)));}
   if(isset($row->s10)) { $s10=((round($row->s10))-(intval($List->s_10)));}
   if(isset($row->s11)) { $s11=((round($row->s11))-(intval($List->s_11)));}
   if(isset($row->s12)) { $s12=((round($row->s12))-(intval($List->s_12)));}
   if(isset($row->s13)) { $s13=((round($row->s13))-(intval($List->s_13)));}
   if(isset($row->s14)) { $s14=((round($row->s14))-(intval($List->s_14)));}
   if(isset($row->s15)) { $s15=((round($row->s15))-(intval($List->s_15)));}
   if(isset($row->s16)) {$s16=((round($row->s16))-(intval($List->s_16)));}
   if(isset($row->s17)) { $s17=((round($row->s17))-(intval($List->s_17)));}
   if(isset($row->s18)) { $s18=((round($row->s18))-(intval($List->s_18)));}
   if(isset($row->s19)) { $s19=((round($row->s19))-(intval($List->s_19)));}
   if(isset($row->s20)) { $s20=((round($row->s20))-(intval($List->s_20)));}
    
    
}
$total_qty=0;
          if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.' <input style="width:80px; float:left;" sz_group max="'.$s1.'" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.' <input style="width:80px; float:left;" sz_group max="'.$s2.'" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.' <input style="width:80px; float:left;" sz_group max="'.$s3.'" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.' <input style="width:80px; float:left;" sz_group max="'.$s4.'" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.' <input style="width:80px; float:left;" sz_group max="'.$s5.'" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.' <input style="width:80px; float:left;" sz_group max="'.$s6.'" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.' <input style="width:80px; float:left;" sz_group max="'.$s7.'" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.' <input style="width:80px; float:left;" sz_group max="'.$s8.'" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.' <input style="width:80px; float:left;" sz_group max="'.$s9.'" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.' <input style="width:80px; float:left;" sz_group max="'.$s10.'" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.' <input style="width:80px; float:left;" sz_group max="'.$s11.'" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.' <input style="width:80px; float:left;" sz_group max="'.$s12.'" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.' <input style="width:80px; float:left;" sz_group max="'.$s13.'" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.' <input style="width:80px; float:left;" sz_group max="'.$s14.'" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.' <input style="width:80px; float:left;" sz_group max="'.$s15.'" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.' <input style="width:80px; float:left;" sz_group max="'.$s16.'" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.' <input style="width:80px; float:left;" sz_group max="'.$s17.'" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.' <input style="width:80px; float:left;" sz_group max="'.$s18.'" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.' <input style="width:80px; float:left;" sz_group max="'.$s19.'" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.' <input style="width:80px; float:left;" sz_group max="'.$s20.'" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
       
              
             
         
          $html.='<td>'.($total_qty-$List->size_qty_total).' 
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
          
          </td>';
            $html.='<td>  <input type="button" name="size_btn" class="size_btn btn-primary" id="size_btn" value="Calculate" disabled></td>';
          
          
          $html.='</tr>';

          $no=$no+1;
        }
          $html.=' 
            </tbody>';
            $szcount = [];
            $tdcount = count($SizeDetailList);
            for($i=0; $i < $tdcount; $i++)
            {
                $szcount[] = 0; 
            }
            $commaSeparatedValues = implode(',', $szcount);
            $html.='</table><input type="hidden" name="allTotal" value="'.htmlspecialchars($commaSeparatedValues).'" id="allTotal"><input type="hidden" name="sumAllTotal" value="" id="sumAllTotal">';


      return response()->json(['html' => $html]);
         
  }
  
  public function VPO_GetSizeList(Request $request)
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
        $html = '<option value="">--Size List--</option>';
        
        foreach ($SizeList as $row) {
                $html .= '<option value="'.$row->size_id.'">'.$row->size_name.'</option>';
              
        }
        }
        
        return response()->json(['html' => $html]);

  }
     
public function VPO_GetColorList(Request $request)
{
    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
     'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$request->tr_code)->DISTINCT()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Color List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Color List--</option>';
        
        foreach ($ColorList as $row) 
        {$html .= '<option value="'.$row->color_id.'">'.$row->color_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}


public function VPO_GetItemList(Request $request)
{
    $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
    ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
    ->where('tr_code','=',$request->tr_code)->distinct()->get();
    if (!$request->tr_code)
    {
        $html = '<option value="">--Item List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Item List--</option>';
        
        foreach ($ItemList as $row) 
        {$html .= '<option value="'.$row->item_code.'">'.$row->item_name.'</option>';}
    }
      return response()->json(['html' => $html]);
}

public function VPO_GetClassList(Request $request)
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
    
     
    public function GetTrimsConsumptionPO(Request $request)
    { 
            
            $no= $request->input('no');
            $color_id= $request->input('color_id');
            $size_qty_total= $request->input('size_qty_total');
            $sales_order_no= $request->input('sales_order_no');
           
            $item_code= $request->item_code;
            $sales_order_no= $request->sales_order_no;
         //print_r($item_code);
        //   DB::enableQueryLog();
          
           
        // DB::enableQueryLog();
           $color_ids = $color_id;

            $codefetch = DB::table('bom_trim_fabric_details')
            ->select("color_id", "item_code", "consumption", "description", "wastage", "class_id", "unit_id", "rate_per_unit")
            ->where(function($query) use ($color_ids) {
                $color_ids_array = explode(',', $color_ids);
                foreach ($color_ids_array as $color_id) {
                    $query->orWhere('color_id', 'LIKE', "%$color_id%");
                }
            })
            ->where('sales_order_no', '=', $sales_order_no)
            ->get();
    //dd(DB::getQueryLog());
           
              
            //  $total_consumption= ($codefetch->consumption)+(($codefetch->consumption)*($codefetch->wastage/100));
            //  $bom_qty=round(($total_consumption*$size_qty_total),2);
             
            
            //echo '<pre>';print_R($codefetch);exit;
            $html = '';
            foreach($codefetch as $rows)
            {
                
                 $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=',$rows->unit_id)->get();
                 $ItemList = ItemModel::where('delflag','=', '0')->where('item_code','=',$rows->item_code)->get(); 
                 $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=',$rows->class_id)->get();
             
                $total_consumption= ($rows->consumption);
                $bom_qty=round(($rows->consumption*$size_qty_total),2);
             
                 if(Session::get('user_type') == 1)
                 {
                    $mx = 500;    
                 }
                 else
                 {
                    $mx = 5;    
                 }
                $html .='<tr class="thisRow">';
                   
                $html .='
                <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
                $html.=' 
                 
                 <td> <select name="item_codesx[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                
                    $rowitem->item_code == $rows->item_code ? $html.='selected="selected"' : ''; 
                    
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                 
                 <td> <select name="class_idsx[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rows->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                 
                 <td><input type="text"  readOnly  name="descriptionsx[]" value="'.$rows->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                 
                <td><input type="number" step="any" readOnly   name="consumptionsx[]" value="'.$rows->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                  
                <td> <select name="unit_idsx[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                    $html.='<option value="'.$rowunit->unit_id.'"';
                
                    $rowunit->unit_id == $rows->unit_id ? $html.='selected="selected"' : ''; 
                    
                    $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td> 
                
                <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE"  name="wastagesx[]" value="'.$rows->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtysx[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                
                <input type="hidden"  name="bom_qtysx1[]" value="'.$bom_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_conssx[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtysx[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                 
                </td>';
            
              
                 $html .='</tr>';
            }
             
            return response()->json(['html' => $html]);
     
    }    
     
     
    public function GetFabricConsumptionPO(Request $request)
    { 
    
    $no= $request->input('no');
    $color_id= $request->input('color_id');
    $size_qty_total= $request->input('size_qty_total');
    $sales_order_no= $request->input('sales_order_no');
   
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 //print_r($item_code);
//   DB::enableQueryLog();
 
  $tr_code = DB::table('buyer_purchase_order_detail')->select("item_code")
  ->where('color_id','=',$color_id) ->where('tr_code','=',$sales_order_no)
  ->distinct()
  ->first();
  
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
  
  
//   DB::enableQueryLog();

  $codefetch = DB::table('bom_fabric_details')->select("item_code","consumption","description","wastage","class_id","unit_id","rate_per_unit")
  ->where('item_code','=',$tr_code->item_code)->where('sales_order_no','=',$sales_order_no)
  ->first();
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=',$codefetch->unit_id)->get();
     $ItemList = ItemModel::where('delflag','=', '0')->where('item_code','=',$codefetch->item_code)->get(); 
     $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=',$codefetch->class_id)->get();
             
     $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)->DISTINCT()->get(); 
     
    //  $total_consumption= ($codefetch->consumption)+(($codefetch->consumption)*($codefetch->wastage/100));
    //  $bom_qty=round(($total_consumption*$size_qty_total),2);
                
     $total_consumption= ($codefetch->consumption);
     $bom_qty=round(($codefetch->consumption*$size_qty_total),2);
    
     if(Session::get('user_type') == 1)
     {
        $mx = 500;    
     }
     else
     {
        $mx = 5;    
     }
    
     $html = '';
     
    $html .='<tr class="thisRow">';
   
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
$html.=' 
 
 <td> <select name="item_code[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
<option value="">--Item List--</option>';
foreach($ItemList as  $rowitem)
{
    $html.='<option value="'.$rowitem->item_code.'"';

    $rowitem->item_code == $codefetch->item_code ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowitem->item_name.'</option>';
}
$html.='</select></td>
 
 <td> <select name="class_id[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required disabled>
<option value="">--Classification--</option>';
foreach($ClassList as  $rowclass)
{
    $html.='<option value="'.$rowclass->class_id.'"';
    $rowclass->class_id == $codefetch->class_id ? $html.='selected="selected"' : ''; 
    $html.='>'.$rowclass->class_name.'</option>';
}
$html.='</select></td> 
 
 <td><input type="text"  readOnly  name="description[]" value="'.$codefetch->description.'" id="description" style="width:200px; height:30px;" required /></td> 
 
<td><input type="number" step="any" readOnly   name="consumption[]" value="'.$codefetch->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
  
<td> <select name="unit_id[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
<option value="">--Unit--</option>';
foreach($UnitList as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $codefetch->unit_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td> 

<td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE" onchange="calculateBomWithWastage1(this);" name="wastage[]" value="0" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
<td><input type="text"  name="bom_qty[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />

<input type="hidden"  name="bom_qty1[]" value="'.$bom_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="final_cons[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="size_qty[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
 
</td> 

';

  
     $html .='</tr>';
      
    return response()->json(['html' => $html]);
     
    }  
     
     
   public function GetSewingConsumption(Request $request)
    {
         
    $size_qty_array= $request->input('size_qty_array');
    $size_array= $request->input('size_array');
    $SizeList=explode(',', $size_array);
    $SizeQty=explode(',', $size_qty_array);
    //print_r($size_qty_array); 
   //  print_r($size_qty_array);
    $no= $request->input('no');
    $color_id= $request->input('color_id');
    $size_qty_total= $request->input('size_qty_total');
    $sales_order_no= $request->input('sales_order_no');
   
    $item_code= $request->item_code;
    $sales_order_no= $request->sales_order_no;
 
    $UnitList = UnitModel::where('delflag','=', '0')->get();
    
    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
    
  $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
  //DB::enableQueryLog(); 
  
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
   $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', 2)->get();
 // print_r(count($codefetch));
  $html = '';

     $x=0;$qty=0;
     
foreach($SizeList as $sz)
 { 
     
     
        $qty=$SizeQty[$x++];
        if($qty!=0)
        {
            
            // DB::enableQueryLog();
           $codefetch = DB::table('bom_sewing_trims_details')->select("item_code","consumption","description","wastage","class_id","unit_id")
          ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
          ->get(); 
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
           
            foreach($codefetch as $rowsew)
            {   
                // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                // $bom_qty=round(($total_consumption*$qty),2);
              
                $total_consumption= ($rowsew->consumption);
                $bom_qty=round(($rowsew->consumption*$qty),2);
             
                if(Session::get('user_type') == 1)
                {
                    $mx = 500;    
                }
                else
                {
                    $mx = 5;    
                }
                        
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                $html.=' 
                <td> <select name="item_codes[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_ids[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text"   readOnly name="descriptions[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                <td><input type="number" step="any" readOnly   name="consumptions[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_ids[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE1"   name="wastages[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtys[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="text"  name="bom_qtys1[]" value="'.$qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_conss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtys[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                </td> 
                ';
                $html .='</tr>';
            } //main foreach
    }  // if loop for size qty 0
     
 }  // Size Array Foreach
    return response()->json(['html' => $html]);
     
    }    
     
     
    public function GetPurchasePackingConsumption(Request $request)
    {
        //echo '<pre>'; print_R($_GET);exit;
        $size_qty_array= $request->input('size_qty_array');
        $size_array= $request->input('size_array');
        $allTotal= $request->allTotal;
        $sumAllTotal= $request->sumAllTotal;
        $SizeList=explode(',', $size_array);
        $SizeQty=explode(',', $size_qty_array);
        $All_Total = explode(',', $allTotal);
        
       //  print_r($size_qty_array);
        $no= 1;
        $color_id= $request->input('color_id');
        $size_qty_total= $request->size_qty_total;
        $sales_order_no= $request->input('sales_order_no');
       
        $item_code= $request->item_code;
        $sales_order_no= $request->sales_order_no;
     
        
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
         
      $html = '';
    
         $x=0;$qty=0;
      
        // foreach($SizeList as $sz)
        //  { 
            
                // $sz_array = explode(',', $SizeList);
                // print_R($sz_array);exit;
                $qty=$SizeQty[$x++];
                // if($qty!=0)
                // {
                   // DB::enableQueryLog();
                    $query = DB::table('bom_packing_trims_details')
                        ->join("color_master", "color_master.color_id", "=", "bom_packing_trims_details.color_id")
                        ->select('sales_order_no', 'item_code', 'bom_packing_trims_details.color_id', 'consumption', 'description', 'wastage', 'bom_packing_trims_details.class_id', 'bom_packing_trims_details.unit_id','size_array','color_master.color_name')
                        ->where('sales_order_no', '=', $sales_order_no);
                    
                    $whereConditions = [];
                    
                    foreach ($SizeList as $index => $size_id) {
                        if (isset($SizeQty[$index])) {
                            $qty1 = $SizeQty[$index];
                            
                            if ($qty1 > 0) {
                                $whereConditions[] = "FIND_IN_SET($size_id, size_array)";
                            }
                        }
                    }
                    
                    if (!empty($whereConditions)) {
                        $query->whereRaw('(' . implode(' OR ', $whereConditions) . ')');
                    }
                    
                    $codefetch = $query->groupBy('bom_packing_trims_details.item_code')->orderBy('item_code', 'ASC')->get();
    
    
                   //  dd(DB::getQueryLog());
                   
                   
                   
                    foreach($codefetch as $key=>$rowsew)
                    {   
                        
                         $sizes =""; 
                    $SizeListFromBOM=DB::select("select size_array from bom_packing_trims_details where sales_order_no='".$rowsew->sales_order_no."' and item_code='".$rowsew->item_code."' limit 0,1");
                                               
                    // $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
               
                    if(isset($SizeListFromBOM[0]->size_array))
                    {
                        $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
                        $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                        foreach($SizeDetailList as $sz)
                        {
                             $sizes=$sizes.$sz->size_name.', '; 
                        }
                    } 
                    $array2 = explode(',', $rowsew->size_array);            
                   
                       
                        // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                        // $bom_qty=round(($total_consumption*$qty),2);
                               
                        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->where('class_id','=', $rowsew->class_id)->get();
                        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', 3)->where('item_code','=', $rowsew->item_code)->get(); 
                        $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=', $rowsew->unit_id)->get();
                        
                        $total_consumption= ($rowsew->consumption);
                        $temp_s1 = rtrim($sizes, ', ');
                         
                        $index = array_search($size_ids[0], $SizeList);
                        //echo '<pre>';print_R($SizeList);exit;
                       if ($SizeList === $array2) 
                       {
                            $ind_qty = $sumAllTotal;
                       }
                       else
                       {
                           $ind_qty = $All_Total[$index];
                           
                       }
                          
                        if($All_Total[$index] == 0)
                        {
                           $ind_qty =  0;
                        }
                        $bom_qty = $ind_qty * $rowsew->consumption; 
                     
                        if(Session::get('user_type') == 1)
                        {
                            $mx = 500;    
                        }
                        else
                        {
                            $mx = 5;    
                        }
                        
                        $html .='<tr class="thisRow">';
                        $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                        $html .='<td><input type="text"  value="'.$rowsew->item_code.'"  style="width:50px;" readOnly/></td>';
                        $html.='<td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                        <option value="">--Item List--</option>';
                        foreach($ItemList as  $rowitem)
                        {
                            $html.='<option value="'.$rowitem->item_code.'"';
                            $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowitem->item_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="text" name="color_ids[]" value="'.$rowsew->color_name.'"  style="width:200px;" disabled/></td>
                        <td><input type="text"  name="sizes_ids[]" value="'.rtrim($sizes, ', ').'"  style="width:200px;" disabled/></td>
                        <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:150px; height:30px;" required disabled>
                        <option value="">--Classification--</option>';
                        foreach($ClassList as  $rowclass)
                        {
                            $html.='<option value="'.$rowclass->class_id.'"';
                            $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowclass->class_name.'</option>';
                        }
                        $html.='</select></td>  
                        <td><input type="number" step="any"  readOnly  name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                        <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                        <option value="">--Unit--</option>';
                        foreach($UnitList as  $rowunit)
                        {
                           $html.='<option value="'.$rowunit->unit_id.'"';
                           $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                           $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE2"  name="wastagess[]" value="0" id="wastage'.$no.'" onchange="calculateBomWithWastage(this);" style="width:80px; height:30px;" disabled /></td> 
                        <td><input type="text"  name="bom_qtyss[]" data-color="'.$rowsew->color_name.'" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="bom_qtyss1[]" value="'.$bom_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        </td> 
                        ';
                        $html .='</tr>';
                        $no++;
                    } //main foreach
            // }  // if loop for size qty 0
             
        //  }  // Size Array Foreach
       return response()->json(['html' => $html]);
     
    }    
     
    
    // public function GetPurchasePackingCreateConsumption(Request $request)
    // {
    //     //echo '<pre>'; print_R($_GET);exit;
    //     $size_qty_array= $request->input('size_qty_array');
    //     $size_array= $request->input('size_array');
    //     $allTotal= $request->allTotal;
    //     $sumAllTotal= $request->sumAllTotal;
    //     $SizeList=explode(',', $size_array);
    //     $SizeQty=explode(',', $size_qty_array);
    //     $All_Total = explode(',', $allTotal);
        
    //   //  print_r($size_qty_array);
    //     $no= 1;
    //     $color_id= $request->input('color_id');
    //     $size_qty_total= $request->size_qty_total;
    //     $sales_order_no= $request->input('sales_order_no');
       
    //     $item_code= $request->item_code;
    //     $sales_order_no= $request->sales_order_no;
     
        
    //     $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    //     ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    //     ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
         
    //   $html = '';
    
    //      $x=0;$qty=0;
      
    //     // foreach($SizeList as $sz)
    //     //  { 
            
    //             // $sz_array = explode(',', $SizeList);
    //             // print_R($sz_array);exit;
    //             $qty=$SizeQty[$x++];
    //             // if($qty!=0)
    //             // {
    //               // DB::enableQueryLog();
    //                 $query = DB::table('bom_packing_trims_details')
    //                     ->join("color_master", "color_master.color_id", "=", "bom_packing_trims_details.color_id")
    //                     ->select('sales_order_no', 'item_code', 'bom_packing_trims_details.color_id', 'consumption', 'description', 'wastage', 'bom_packing_trims_details.class_id', 'bom_packing_trims_details.unit_id','size_array','color_master.color_name')
    //                     ->where('sales_order_no', '=', $sales_order_no);
                    
    //                 $whereConditions = [];
                    
    //                 foreach ($SizeList as $index => $size_id) {
    //                     if (isset($SizeQty[$index])) {
    //                         $qty1 = $SizeQty[$index];
                            
    //                         if ($qty1 > 0) {
    //                             $whereConditions[] = "FIND_IN_SET($size_id, size_array)";
    //                         }
    //                     }
    //                 }
                    
    //                 if (!empty($whereConditions)) {
    //                     $query->whereRaw('(' . implode(' OR ', $whereConditions) . ')');
    //                 }
                    
    //                 $codefetch = $query->groupBy('bom_packing_trims_details.item_code')->orderBy('item_code', 'ASC')->get();
    
    
    //               //  dd(DB::getQueryLog());
                   
                   
                   
    //                 foreach($codefetch as $key=>$rowsew)
    //                 {   
                        
    //                      $sizes =""; 
    //                 $SizeListFromBOM=DB::select("select size_array from bom_packing_trims_details where sales_order_no='".$rowsew->sales_order_no."' and item_code='".$rowsew->item_code."' limit 0,1");
                                               
    //                 // $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
               
    //                 if(isset($SizeListFromBOM[0]->size_array))
    //                 {
    //                     $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
    //                     $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
    //                     foreach($SizeDetailList as $sz)
    //                     {
    //                          $sizes=$sizes.$sz->size_name.', '; 
    //                     }
    //                 } 
    //                 $array2 = explode(',', $rowsew->size_array);            
    //                 $commonElements = array_intersect($SizeList, $array2);
                     
    //                 $matchedQuantities =  0;
                  
    //                 // foreach ($array2 as $element) 
    //                 // { 
    //                 //     $index = array_search($element, $SizeList); 
    //                 //     if($color_id  == $rowsew->color_id)
    //                 //     {
    //                 //         $matchedQuantities += $SizeQty[$index];
    //                 //     }
    //                 //     else
    //                 //     {
    //                 //          $matchedQuantities = $SizeList[$index];
    //                 //     }
    //                 // } 
                          
                       
    //                     // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
    //                     // $bom_qty=round(($total_consumption*$qty),2);
                               
    //                     $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->where('class_id','=', $rowsew->class_id)->get();
    //                     $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', 3)->where('item_code','=', $rowsew->item_code)->get(); 
    //                     $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=', $rowsew->unit_id)->get();
                        
    //                     $total_consumption= ($rowsew->consumption);
    //                     $temp_s1 = rtrim($sizes, ', ');
    //                     $index = array_search($size_ids[0], $SizeList);
    //                  // echo '<pre>';print_R($All_Total);exit;
    //                   if ($SizeList === $array2) 
    //                   {
    //                         $ind_qty = $sumAllTotal;
    //                   }
    //                   else
    //                   {
    //                       $ind_qty = $All_Total[$index];
    //                   }
                          
    //                     $bom_qty = $ind_qty * $rowsew->consumption; 
                     
    //                     if(Session::get('user_type') == 1)
    //                     {
    //                         $mx = 500;    
    //                     }
    //                     else
    //                     {
    //                         $mx = 5;    
    //                     }
                        
    //                     $html .='<tr class="thisRow">';
    //                     $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
    //                     $html .='<td><input type="text"  value="'.$rowsew->item_code.'"  style="width:50px;" readOnly/></td>';
    //                     $html.='<td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
    //                     <option value="">--Item List--</option>';
    //                     foreach($ItemList as  $rowitem)
    //                     {
    //                         $html.='<option value="'.$rowitem->item_code.'"';
    //                         $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
    //                         $html.='>'.$rowitem->item_name.'</option>';
    //                     }
    //                     $html.='</select></td>
    //                     <td><input type="text" name="color_ids[]" value="'.$rowsew->color_name.'"  style="width:200px;" disabled/></td>
    //                     <td><input type="text"  name="sizes_ids[]" value="'.rtrim($sizes, ', ').'"  style="width:200px;" disabled/></td>
    //                     <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:150px; height:30px;" required disabled>
    //                     <option value="">--Classification--</option>';
    //                     foreach($ClassList as  $rowclass)
    //                     {
    //                         $html.='<option value="'.$rowclass->class_id.'"';
    //                         $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
    //                         $html.='>'.$rowclass->class_name.'</option>';
    //                     }
    //                     $html.='</select></td>  
    //                     <td><input type="number" step="any"  readOnly  name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
    //                     <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
    //                     <option value="">--Unit--</option>';
    //                     foreach($UnitList as  $rowunit)
    //                     {
    //                       $html.='<option value="'.$rowunit->unit_id.'"';
    //                       $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
    //                       $html.='>'.$rowunit->unit_name.'</option>';
    //                     }
    //                     $html.='</select></td>
    //                     <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE2"  name="wastagess[]" value="0" id="wastage'.$no.'" onchange="calculateBomWithWastage(this);" style="width:80px; height:30px;" /></td> 
    //                     <td><input type="text"  name="bom_qtyss[]" data-color="'.$rowsew->color_name.'" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
    //                     <input type="hidden"  name="bom_qtyss1[]" value="'.$bom_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;"  readOnly />
    //                     <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;"  readOnly />
    //                     <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
    //                     </td> 
    //                     ';
    //                     $html .='</tr>';
    //                     $no++;
    //                 } //main foreach
    //         // }  // if loop for size qty 0
             
    //     //  }  // Size Array Foreach
    //   return response()->json(['html' => $html]);
     
    // }     
    
        
    public function GetPurchasePackingCreateConsumption(Request $request)
    {
        
        $size_qty_array= $request->input('size_qty_array');
        $size_array= $request->input('size_array');
        $allTotal1= $request->allTotal;
        $allTotal= $request->size_qty_total;
        $sumAllTotal= $request->sumAllTotal;
        $SizeList=explode(',', $size_array);
        $SizeQty=explode(',', $size_qty_array);
        $All_Total = explode(',', $allTotal);
        $All_Total1 = explode(',', $allTotal1);
        $color_ids = $request->color_ids;
        $tbl_len = $request->tbl_len;
        
        //print_r($size_array);exit;
        $no= 1;
        $color_id= $request->input('color_id');
        $size_qty_total= $request->size_qty_total;
        $sales_order_no= $request->input('sales_order_no');
       
        $item_code= $request->item_code;
        $sales_order_no= $request->sales_order_no;
     
        
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
         
        $html = '';
    
        $x=0;$qty=0;
        // print_R($sz_array);exit;
        // foreach($SizeList as $sz)
        //  { 
            
                // $sz_array = explode(',', $SizeList);
               
                $qty=$SizeQty[$x++];
                // if($qty!=0)
                // {
                  
                    //   $query = DB::table('bom_packing_trims_details')
                    //     ->join("color_master", function ($join) {
                    //         $join->on(DB::raw("FIND_IN_SET(color_master.color_id, bom_packing_trims_details.color_id)"), ">", DB::raw("0"));
                    //     })
                    //     ->select(
                    //         'sales_order_no',
                    //         'item_code',
                    //         'bom_packing_trims_details.color_id',
                    //         'consumption',
                    //         'description',
                    //         'wastage',
                    //         'bom_packing_trims_details.class_id',
                    //         'bom_packing_trims_details.unit_id',
                    //         'size_array',
                    //         DB::raw("GROUP_CONCAT(DISTINCT color_master.color_name ORDER BY color_master.color_name SEPARATOR ', ') as color_names")
                    //     )
                    //     ->where('sales_order_no', $sales_order_no)
                    //     ->whereRaw("FIND_IN_SET(?, bom_packing_trims_details.color_id)", [$color_id]) // Ensure the given color_id exists in the column
                    //     ->groupBy(
                    //         'sales_order_no',
                    //         'item_code',
                    //         'bom_packing_trims_details.color_id',
                    //         'consumption',
                    //         'description',
                    //         'wastage',
                    //         'bom_packing_trims_details.class_id',
                    //         'bom_packing_trims_details.unit_id',
                    //         'size_array'
                    //     );
                    
                    // // Fetch results
                    // $results = $query->get();

                    $size_array1 = explode(',', $size_array);  // $size_string = 'S,M,L,XL'
                    $size_qty_array1 = explode(',', $size_qty_array); // $size_qty_string = '10,20,30,40'

                    $size_qty_map = array_combine($size_array1, $size_qty_array1);
                    
                    $query = DB::table('bom_packing_trims_details')
                        ->select(
                            'sales_order_no',
                            'item_code',
                            DB::raw("$color_id as color_id"),
                            'consumption',
                            'description',
                            'wastage',
                            'bom_packing_trims_details.class_id',
                            'bom_packing_trims_details.unit_id',
                            'size_array',
                            'color_master.color_name as color_names'
                        )
                        ->join('color_master', function ($join) use ($color_id) {
                            $join->on(DB::raw('FIND_IN_SET(color_master.color_id, bom_packing_trims_details.color_id)'), '>', DB::raw('0'))
                                 ->where('color_master.color_id', $color_id);
                        })
                        ->where('sales_order_no', $sales_order_no)
                        ->whereRaw('FIND_IN_SET(?, bom_packing_trims_details.color_id)', [$color_id])
                        ->where(function ($query) use ($size_array1) {
                            foreach ($size_array1 as $index => $size_id) {
                                if ($index === 0) {
                                    $query->whereRaw("FIND_IN_SET(?, size_array)", [$size_id]);
                                } else {
                                    $query->orWhereRaw("FIND_IN_SET(?, size_array)", [$size_id]);
                                }
                            }
                        })
                        ->groupBy('sales_order_no', 'item_code', 'consumption', 'size_array', 'color_master.color_name');
                    
                    $codefetch = $query->get();

                   
                    $szc = 0;
                    $temp_color = '';
                    foreach($codefetch as $key=>$rowsew)
                    {   
                        
                        $sizes =""; 
                         
                        $SizeListFromBOM=DB::select("select size_array from bom_packing_trims_details where sales_order_no='".$rowsew->sales_order_no."' and item_code='".$rowsew->item_code."' limit 0,1");
                                         
                               
                        // $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                        
                        if(isset($SizeListFromBOM[0]->size_array))
                        {
                            $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
                            $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                            foreach($SizeDetailList as $sz)
                            {
                                 $sizes=$sizes.$sz->size_name.', '; 
                            }
                        } 
                        $array2 = explode(',', $rowsew->size_array);            
                        $commonElements = array_intersect($SizeList, $array2);
                         
                        $matchedQuantities =  0;
                  
                    // foreach ($array2 as $element) 
                    // { 
                    //     $index = array_search($element, $SizeList); 
                    //     if($color_id  == $rowsew->color_id)
                    //     {
                    //         $matchedQuantities += $SizeQty[$index];
                    //     }
                    //     else
                    //     {
                    //          $matchedQuantities = $SizeList[$index];
                    //     }
                    // } 
                          
                       
                        // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                        // $bom_qty=round(($total_consumption*$qty),2);
                               
                        $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=', $rowsew->class_id)->get();
                        $ItemList = ItemModel::where('delflag','=', '0')->where('item_code','=', $rowsew->item_code)->get(); 
                        $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=', $rowsew->unit_id)->get();
                        
                        $total_consumption= ($rowsew->consumption);
                        $temp_s1 = rtrim($sizes, ', ');
                        $sizesArray = explode(', ', $temp_s1);
                        $count = count($sizesArray);
                        $index = array_search($size_ids[0], $SizeList);
                      //echo '<pre>';print_R($array2);exit;
                    
                      if($count == 1)
                      { 
                          $ind_qty = $SizeQty[$index]; 
                      }
                      else
                      {
                          if ($SizeList == $array2) 
                          {
                                $selected_color_ids = explode(',', $color_ids);
                                $existing_color_ids = DB::table('bom_packing_trims_details')
                                    ->where('item_code', $rowsew->item_code)
                                    ->where('sales_order_no', $rowsew->sales_order_no)
                                    ->pluck('color_id')
                                    ->toArray();
                                
                    //             // Convert database color_id values into an array
                                $existing_color_ids_array = [];
                                foreach ($existing_color_ids as $ids) {
                                    $existing_color_ids_array = array_merge($existing_color_ids_array, explode(',', $ids));
                                }
                                
                                // Check if both 2001 and 2003 exist
                                $allExist = empty(array_diff($selected_color_ids, $existing_color_ids_array));
                                $allExist1 = $allExist ?? 0;
                                if ($allExist == 1) 
                                {
                                  $ind_qty = $sumAllTotal;
                                }
                                else
                                {
                                 if (count($selected_color_ids) > 0)
                                 {
                                     $ind_qty =  $size_qty_total;
                                 }
                                 else
                                 {
                                     $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                                 }
                               
                                 
                                 //$ind_qty = $sumAllTotal ? $sumAllTotal : $SizeQty[$index]; 
                                   
                                }
                                
                          }
                          else
                          {
                              //echo $All_Total[$index];exit;
                              $ind_qty =  $sumAllTotal; 
                          } 
                      }
                      
                        // $selected_color_ids = explode(',', $color_ids);
                        // $existing_color_ids = DB::table('bom_sewing_trims_details')
                        //     ->where('item_code', $rowsew->item_code)
                        //     ->where('sales_order_no', $rowsew->sales_order_no)
                        //     ->pluck('color_id')
                        //     ->toArray();
                        
                        // // Convert database color_id values into an array
                        
                         
                        // $existing_color_ids_array = [];
                        // foreach ($existing_color_ids as $ids) {
                        //     $existing_color_ids_array = array_merge($existing_color_ids_array, explode(',', $ids));
                             
                        // }
                        // //print_r($existing_color_ids_array);exit;
             
                        // $allExist = array_diff($selected_color_ids, $existing_color_ids_array);
                        // //print_R($allExist);exit;
                        // $allExist1 = count($allExist);
                         
                        // if ($allExist1 == 1) 
                        // {  
                        //     $ind_qty = $sumAllTotal; 
                        // }
                        // else
                        // {    
                        //      $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                        // }
                        
                    
                       
                       // $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                       
                     
                        if(Session::get('user_type') == 1)
                        {
                            $mx = 500;    
                        }
                        else
                        {
                            $mx = 5;    
                        }
                        if($tbl_len == 1)
                        {
                            // if(strpos($rowsew->size_array, ',') === false) 
                            // {
                            //     $bom_qty = isset($SizeQty[$szc]) ? $SizeQty[$szc] : 0;
                               
                            //     $szc++;
                               
                            //     // if($temp_color != $rowsew->class_id)
                            //     // {
                            //     //     $szc = 0;
                            //     // }
                            // } 
                            // else
                            // {
                            //   $szc = 0;
                            //   $size_name =  rtrim($sizes, ', ');
                            //   $bom_qty = $ind_qty * $rowsew->consumption; 
                            // } 
                            
                        
                            // $sizedata = DB::SELECT("SELECT size_name FROM `size_detail` WHERE size_id IN (".$rowsew->size_array.")");
                            
                            // $size_name = isset($sizedata[0]->size_name) ? $sizedata[0]->size_name : "";    
                            $row_size_ids = explode(',', $rowsew->size_array);
                            $total_size_qty = 0;
                        
                            foreach ($row_size_ids as $size_id) {
                                if (isset($size_qty_map[$size_id])) {
                                    $total_size_qty += $size_qty_map[$size_id];
                                }
                            }
                        
                            $bom_qty = $rowsew->consumption * $total_size_qty;
                        }
                        else
                        {
                            $bom_qty = $ind_qty * $rowsew->consumption; 
                        }
                        
                        $size_name =  rtrim($sizes, ', ');
    
                        $html .='<tr class="thisRow">';
                        $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                        $html .='<td><input type="text"  value="'.$rowsew->item_code.'"  style="width:50px;" readOnly/></td>';
                        $html.='<td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                        <option value="">--Item List--</option>';
                        foreach($ItemList as  $rowitem)
                        {
                            $html.='<option value="'.$rowitem->item_code.'"';
                            $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowitem->item_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="text" name="color_ids[]" value="'.$rowsew->color_names.'"  style="width:200px;" disabled/></td>
                        <td><input type="text"  name="sizes_ids[]" value="'.$size_name.'"  style="width:200px;" disabled/></td>
                        <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:150px; height:30px;" required disabled>
                        <option value="">--Classification--</option>';
                        foreach($ClassList as  $rowclass)
                        {
                            $html.='<option value="'.$rowclass->class_id.'"';
                            $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowclass->class_name.'</option>';
                        }
                        $html.='</select></td>  
                        <td><input type="number" step="any"  readOnly  name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                        <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                        <option value="">--Unit--</option>';
                        foreach($UnitList as  $rowunit)
                        {
                           $html.='<option value="'.$rowunit->unit_id.'"';
                           $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                           $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE2"  name="wastagess[]" value="0" id="wastage'.$no.'" onchange="calculateBomWithWastage(this);" style="width:80px; height:30px;" /></td> 
                        <td><input type="text"  name="bom_qtyss[]" data-color="'.$rowsew->color_names.'" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="bom_qtyss1[]" value="'.$bom_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        </td> 
                        ';
                        $html .='</tr>';
                        $no++;
                        
                        $temp_color = $rowsew->class_id;
                    } //main foreach
            // }  // if loop for size qty 0
             
        //  }  // Size Array Foreach
       return response()->json(['html' => $html]);
     
    }   
    
    
    public function getVendorPurchaseOrderDetails(Request $request)
    { 
        $vpo_code= $request->vpo_code;
        $MasterdataList = DB::select("select distinct sales_order_no,mainstyle_id,substyle_id,fg_id,style_no,
        style_description, vendorId  from vendor_purchase_order_master where vpo_code='$vpo_code'");
        return json_encode($MasterdataList);
    }   
     
     
     
     public function getVendorPO(Request $request)
{
    //   DB::enableQueryLog();
         
     $POList = DB::select("select vpo_code,sales_order_no from vendor_purchase_order_master where vendorId ='".$request->vendorId."' and process_id='".$request->process_id."'");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
    if (!$request->vendorId)
    {
        $html = '<option value="">--PO List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--PO List--</option>';
        
        foreach ($POList as $row)  
        { $vpo_code=$row->vpo_code;$html .= '<option value="'.$vpo_code.'">'.$row->vpo_code.'('.$row->sales_order_no.')</option>';}
    }
      return response()->json(['html' => $html]);
}



public function getVendorAllPO(Request $request)
{
    //   DB::enableQueryLog();
         
     $POList = DB::select("select vpo_code,sales_order_no from vendor_purchase_order_master where vendorId ='".$request->vendorId."'");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
    if (!$request->vendorId)
    {
        $html = '<option value="">--PO List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--PO List--</option>';
        
        foreach ($POList as $row)  
        { $vpo_code=$row->vpo_code;$html .= '<option value="'.$vpo_code.'">'.$row->vpo_code.'('.$row->sales_order_no.')</option>';}
    }
      return response()->json(['html' => $html]);
}



public function  POVsMaterialIssueReport(Request $request)
{
    
    $FirmDetail =  DB::table('firm_master')->first();
    $vpo_code=$request->vpo_code;
    $vw_code=$request->vw_code;
    $sales_order_no=$request->sales_order_no;
    if($request->vpo_code!='')
    {   
        $order_type=1;
       
        $VendorOrderList = VendorPurchaseOrderDetailModel::where('vendor_purchase_order_detail.vpo_code','=', $request->vpo_code)->get();
      
    }
    else
    {   
        $order_type=2;
        //  DB::enableQueryLog();
        $VendorOrderList = VendorWorkOrderDetailModel::where('vendor_work_order_detail.vw_code','=', $request->vw_code)->get();
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    }
     
 return view('POVsMaterialIssueReportPrint' ,compact('VendorOrderList','order_type','FirmDetail','vpo_code','vw_code','sales_order_no')); 

}

     
    public function GetVPOVsIssueReport(Request $request)
{
    $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
    $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where job_status_id=1');
         return view('GetVPOVsIssueReport' ,compact('SalesOrderList','LedgerList'));  
    
}
     
       
  
     
     
        public function GetCuttingPOItemList(Request $request)
        {
            $html='';
             if($request->part_id==1)
           { 
               
            //   DB::enableQueryLog();
                $ItemList = DB::select("select distinct item_code from vendor_purchase_order_size_detail2 as f1
                where f1.vpo_code in ('".$request->vpo_code."')");
        //           $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
                
           }
           else
           {
               
               
                $ItemList = DB::select("select distinct item_code from vendor_purchase_order_trim_fabric_details
                as f2 where f2.vpo_code in ('".$request->vpo_code."')");
                
                
           }
            
            foreach ($ItemList as $row) 
            {
                
                
                $item = ItemModel::where('item_code','=', $row->item_code)->first(); 
                $html .= '<option value="'.$row->item_code.'">'.$item->item_name.'</option>';
                
                
            }    
   
             return response()->json(['html' => $html]);
        }    
        
    public function GetVendorName(Request $request)
    {

           
        $vendorData = DB::select("select ac_name from vendor_purchase_order_master INNER JOIN ledger_master ON ledger_master.ac_code = vendor_purchase_order_master.vendorId where vpo_code = '".$request->vpo_code."'");
   

        return response()->json(['html' => $vendorData[0]->ac_name]);
    }
        
    public function destroy($id)
    {
        
       // DB::enableQueryLog();
        $Datacount=   DB::select("SELECT (select count(trimOutCode)  FROM `trimOutwardMaster` WHERE vpo_code= '".$id."')as trimcounts
        , (select count(cpg_code)  FROM `cut_panel_grn_master` WHERE vpo_code= '".$id."')as cutgrncounts,
        (select  count(fout_code)  FROM `fabric_outward_master` WHERE vpo_code= '".$id."') as fabriccounts");
   // dd(DB::getQueryLog());
   // exit;
    
   //  $checkNextEntryCount=DB::table('cut_panel_issue_master')->where('vpo_code', $id->vpo_code)->count();
     $counts=($Datacount[0]->trimcounts + $Datacount[0]->fabriccounts  + $Datacount[0]->cutgrncounts  );
    
    
    
    
        $Data=   DB::select("SELECT (select GROUP_CONCAT(trimOutCode)   FROM `trimOutwardMaster` WHERE vpo_code= '".$id."') as trimOutCode
        , (select GROUP_CONCAT(cpg_code)  FROM `cut_panel_grn_master` WHERE vpo_code= '".$id."') as cpg_code,
        (select GROUP_CONCAT(fout_code)FROM `fabric_outward_master` WHERE vpo_code= '".$id."') as fout_code  ");
        
        
            //echo $Data[0]->counts;
            if($counts==0)
            {
        
                    DB::table('vendor_purchase_order_master')->where('vpo_code', $id)->delete();
                    DB::table('vendor_purchase_order_fabric_details')->where('vpo_code', $id)->delete();
                    DB::table('vendor_purchase_order_size_detail')->where('vpo_code', $id)->delete();
                    DB::table('vendor_purchase_order_detail')->where('vpo_code', $id)->delete();
                    DB::table('vendor_purchase_order_size_detail2')->where('vpo_code', $id)->delete();
                    Session::flash('delete', 'Deleted record successfully'); 
         
            }
            else
            {
                Session::flash('messagedelete', "Process Order can't be deleted, Remove References -> Trims Outward: ".$Data[0]->trimOutCode." & --------Fabric Outward List : ".$Data[0]->fout_code." & --------Cut Panel GRN List : ".$Data[0]->cpg_code); 
            }

    }
    public function PurchaseOrderClose(Request $request)
    {    
         DB::table('vendor_purchase_order_master')->where('vpo_code',$request->vpo_code)->update(['endflag'=>2]);
         return 1;
    } 
    
      
}
