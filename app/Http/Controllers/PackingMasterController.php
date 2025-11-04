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
use App\Models\LocationModel;
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
use App\Models\StitchingInhouseMasterModel;
use App\Models\StitchingInhouseDetailModel;
use App\Models\StitchingInhouseSizeDetailModel;
use App\Models\FinishingInhouseMasterModel;
use App\Models\FinishingInhouseDetailModel;
use App\Models\FinishingInhouseSizeDetailModel;
use App\Models\PackingMasterModel;
use App\Models\PackingDetailModel;
use App\Models\PackingSizeDetailModel;
use Session;
use DataTables;

class PackingMasterController extends Controller
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
        ->where('form_id', '106')
        ->first();
        
        $vendorId=Session::get('vendorId');
        $user_type=Session::get('user_type');
    
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            if( $request->page == 1)
            {
                $PackingInhouseMasterList = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_master.Ac_code', 'left outer')
                 ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_master.vendorId', 'left outer')
                 ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'packing_master.sales_order_no')
                 ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_master.vpo_code')
                 ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag')
                ->where('packing_master.delflag','=', '0')
                ->get(['packing_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','sales_order_costing_master.sam','job_status_master.job_status_name']);
            }
            else
            {
                 $PackingInhouseMasterList = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_master.Ac_code', 'left outer')
                 ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_master.vendorId', 'left outer')
                 ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'packing_master.sales_order_no')
                 ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_master.vpo_code')
                 ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag')
                 ->where('packing_master.delflag','=', '0')
                 ->where('packing_master.pki_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
                 ->get(['packing_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','sales_order_costing_master.sam','job_status_master.job_status_name']);
            }
            if ($request->ajax()) 
            {
                return Datatables::of($PackingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('pki_code1',function ($row) {
            
                     $pki_codeData =substr($row->pki_code,4,15);
            
                     return $pki_codeData;
                }) 
                ->addColumn('updated_at',function ($row) {
            
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
            
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="PackingGRNPrint1/'.$row->pki_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('PackingMaster.edit', $row->pki_code).'" >
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
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->pki_code.'"  data-route="'.route('PackingMaster.destroy', $row->pki_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['action1','action2','action3','action4','updated_at'])
        
                ->make(true);
            }
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {
            
            if( $request->page == 1)
            {
                $PackingInhouseMasterList = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_master.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_master.vendorId', 'left outer')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'packing_master.sales_order_no')
                    ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_master.vpo_code')
                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag')
                    ->where('packing_master.delflag','=', '0')->where( 'packing_master.vendorId',$vendorId)
                    ->get(['packing_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','sales_order_costing_master.sam','job_status_master.job_status_name']);
            }
            else
            {
                $PackingInhouseMasterList = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_master.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_master.vendorId', 'left outer')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'packing_master.sales_order_no')
                    ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_master.vpo_code')
                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag')
                    ->where('packing_master.delflag','=', '0')->where( 'packing_master.vendorId',$vendorId)
                    ->where('packing_master.pki_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                    ->get(['packing_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','sales_order_costing_master.sam','job_status_master.job_status_name']);
            }
            
            if ($request->ajax()) 
            {
                return Datatables::of($PackingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('pki_code1',function ($row) {
            
                     $pki_codeData =substr($row->pki_code,4,15);
            
                     return $pki_codeData;
                }) 
                ->addColumn('updated_at',function ($row) {
            
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
            
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="PackingGRNPrint1/'.$row->pki_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('PackingMaster.edit', $row->pki_code).'" >
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
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->pki_code.'"  data-route="'.route('PackingMaster.destroy', $row->pki_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['action1','action2','action3','action4','updated_at'])
        
                ->make(true);
            }
        }
        else
        {
            
            if( $request->page == 1)
            {
                $PackingInhouseMasterList = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_master.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_master.vendorId', 'left outer')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'packing_master.sales_order_no')
                    ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_master.vpo_code')
                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag')
                    ->where('packing_master.delflag','=', '0')->where( 'packing_master.vendorId',$vendorId)
                    ->get(['packing_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','sales_order_costing_master.sam','job_status_master.job_status_name']);
            }
            else
            {
                $PackingInhouseMasterList = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_master.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_master.vendorId', 'left outer')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'packing_master.sales_order_no')
                    ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'packing_master.vpo_code')
                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_purchase_order_master.endflag')
                    ->where('packing_master.delflag','=', '0')->where( 'packing_master.vendorId',$vendorId)
                    ->where('packing_master.pki_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                    ->get(['packing_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','sales_order_costing_master.sam','job_status_master.job_status_name']);
            }
            
            if ($request->ajax()) 
            {
                return Datatables::of($PackingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('pki_code1',function ($row) {
            
                     $pki_codeData =substr($row->pki_code,4,15);
            
                     return $pki_codeData;
                }) 
                ->addColumn('updated_at',function ($row) {
            
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
            
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="PackingGRNPrint1/'.$row->pki_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('PackingMaster.edit', $row->pki_code).'" >
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
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->pki_code.'"  data-route="'.route('PackingMaster.destroy', $row->pki_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['action1','action2','action3','action4','updated_at'])
        
                ->make(true);
            }
        }
        return view('PackingMasterList', compact('PackingInhouseMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='PackingInhouse'");
       
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
         $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
       $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
       $vendorId=Session::get('vendorId');
       if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
           $VendorPurchaseOrderList= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')->where('process_id',3)->get();
           $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
            
            $VendorPurchaseOrderList= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')->where('process_id',3)->where('vendor_purchase_order_master.vendorId',$vendorId)->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
        else 
        {  
            
            $VendorPurchaseOrderList= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')->where('process_id',3)->where('vendor_purchase_order_master.vendorId',$vendorId)->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
       
       $SalesOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code as sales_order_no')->get();
        return view('PackingMaster',compact( 'ItemList', 'SalesOrderList','MainStyleList','SubStyleList','FGList','BuyerList','LocationList',
        'VendorPurchaseOrderList','Ledger',  'counter_number'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
        ->where('c_name','=','C1')
        ->where('type','=','PackingInhouse')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
          
        $this->validate($request, [
             
                'pki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'vendorId'=>'required',
                'mainstyle_id'=>'required',
                'vendor_rate'=>'required',
               
    ]);
 
    $is_opening=isset($request->is_opening) ? 1 : 0;
     
    $packing_type_id = isset($request->packing_type_id) ? $request->packing_type_id : ""; 
    
    if($packing_type_id != 4)
    {
        $packingTypeData = DB::SELECT("SELECT * FROM packing_type_master WHERE packing_type_id=".$packing_type_id);
        $packing_short_name = isset($packingTypeData[0]->packing_short_name) ? $packingTypeData[0]->packing_short_name : ""; 
        $vpo_code = $packing_short_name."-".$codefetch->tr_no;
    }
    else
    {
        $vpo_code = $request->vpo_code;
    }
     
    $data1=array
    (
        'pki_code'=>$TrNo, 
        'pki_date'=>$request->pki_date, 
        'sales_order_no'=>$request->sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'vendorId'=>$request->vendorId,
        'vpo_code'=>$vpo_code,
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty,
        'vendor_rate'=>$request->vendor_rate,
        'vendor_amount'=>$request->vendor_amount,
        'narration'=>$request->narration,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
        'is_opening'=>$is_opening,
        'rate'=>$request->rate,
        "location_id"=>$request->location_id,
        "packing_type_id"=>$request->packing_type_id
     );
   
    PackingMasterModel::insert($data1);
    
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='PackingInhouse'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
      
      if($request->size_qty_total[$x]>0)
              {
                    $data2=array
                    (
          
                    'pki_code'=>$TrNo,
                    'pki_date'=>$request->pki_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'vpo_code'=>$request->vpo_code,
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'item_code'=>$request->item_codef[$x], 
                    'color_id'=>$request->color_id[$x],
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array[$x],
                    'size_qty_total'=>$request->size_qty_total[$x],
                    'is_opening'=>$is_opening,
                    
                    'is_transfered'=>isset($request->is_transfered[$x]) ? $request->is_transfered[$x] : 0,
                    'trans_sales_order_no'=>isset($request->trans_sales_order_no[$x]) ? $request->trans_sales_order_no[$x] : "",
                    'transfer_code'=>isset($request->transfer_code[$x]) ? $request->transfer_code[$x] : "",
                     "location_id"=>isset($request->location_id) ? $request->location_id : 0,
                   
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
 
                      $data3=array(
                        'pki_code'=>$TrNo, 
                        'pki_date'=>$request->pki_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'vpo_code'=>$request->vpo_code,
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
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
                        'vendor_rate'=>$request->vendor_rate,
                        'is_opening'=>$is_opening,
                        'is_transfered'=>isset($request->is_transfered[$x]) ? $request->is_transfered[$x] : 0,
                        'trans_sales_order_no'=>isset($request->trans_sales_order_no[$x]) ? $request->trans_sales_order_no[$x] : "",
                        'transfer_code'=>isset($request->transfer_code[$x]) ? $request->transfer_code[$x] : "",
                        "location_id"=>isset($request->location_id) ? $request->location_id : 0,
                        );
                          
                          
                          PackingDetailModel::insert($data2);
                          PackingSizeDetailModel::insert($data3);
          
                          $is_transfered =  isset($request->is_transfered[$x]) ? $request->is_transfered[$x] : 0; 
                          $is_rtv =  isset($request->is_rtv[0]) ? $request->is_rtv[0] : 0; 
                          
                          if($is_transfered==1){
                          $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail set usedFlag=1 
                          where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                          and tpki_code='".$request->transfer_code[$x]."' and
                          color_id='".$request->color_id[$x]."'");
                          
                          $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail2 set usedFlag=1 
                          where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                          and tpki_code='".$request->transfer_code[$x]."' and
                          color_id='".$request->color_id[$x]."'");
                          
                          $UpdateUseFlag=DB::select("update transfer_packing_inhouse_detail set usedFlag=1 
                          where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                          and tpki_code='".$request->transfer_code[$x]."' and
                          color_id='".$request->color_id[$x]."'");
                          }
                     
                          if($is_rtv > 0)
                          {
                              $UpdateUseFlag=DB::select("update return_packing_inhouse_size_detail set usedFlag= '$is_rtv' 
                              where sales_order_no='".$request->trans_sales_order_no[$x]."' 
                              and 	rpki_code='".$request->transfer_code[$x]."' and
                              color_id='".$request->color_id[$x]."'");
                              
                              $UpdateUseFlag=DB::select("update return_packing_inhouse_size_detail2 set usedFlag='$is_rtv' 
                              where sales_order_no='".$request->trans_sales_order_no[$x]."' 
                              and 	rpki_code='".$request->transfer_code[$x]."' and
                              color_id='".$request->color_id[$x]."'");
                              
                              $UpdateUseFlag=DB::select("update return_packing_inhouse_detail set usedFlag='$is_rtv'  
                              where sales_order_no='".$request->trans_sales_order_no[$x]."' 
                              and rpki_code='".$request->transfer_code[$x]."' and
                              color_id='".$request->color_id[$x]."'");
                          }
                          
                     $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->Ac_code)->first();
                     $mainStyleData = DB::table('main_style_master')->select('mainstyle_name')->where('mainstyle_id', $request->mainstyle_id)->first();
                     $subStyleData = DB::table('sub_style_master')->select('substyle_name')->where('substyle_id', $request->substyle_id)->first();
                     $fgData = DB::table('fg_master')->select('fg_name')->where('fg_id', $request->fg_id)->first();
                     $colorData = DB::table('color_master')->select('color_name')->where('color_id', $request->color_id[$x])->first();
                     
                     $ac_name = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                     $mainstyle_name = isset($mainStyleData->mainstyle_name) ? $mainStyleData->mainstyle_name : "";
                     $substyle_name = isset($subStyleData->substyle_name) ? $subStyleData->substyle_name : "";
                     $fg_name = isset($fgData->fg_name) ? $fgData->fg_name : "";
                     $color_name = isset($colorData->color_name) ? $colorData->color_name : "";
                     $style_description = str_replace(["'", '"'], '', $request->style_description);
                     
                    //  if($s1 > 0)
                    //  { 
                    //   //  echo "hii";exit; 
                    //     $size_array1 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array1[0])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                    //  //DB::enableQueryLog();

                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s1.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array1[0].'"'); 
                    //                   //  dd(DB::getQueryLog());
                    //  } 
                     
                    //  if($s2 > 0)
                    //  { 
                         
                    //     $size_array2 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array2[1])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s2.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array2[1].'"'); 
                    //  }
                     
                    //  if($s3 > 0)
                    //  { 
                         
                    //     $size_array3 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array3[2])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s3.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array3[2].'"'); 
                    //  }
                     
                    //  if($s4 > 0)
                    //  { 
                         
                    //     $size_array4 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array4[3])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s4.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array4[3].'"'); 
                    //  }
              
                    //  if($s5 > 0)
                    //  { 
                         
                    //     $size_array5 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array5[4])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s5.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array5[4].'"'); 
                    //  }
                     
                    //  if($s6 > 0)
                    //  { 
                         
                    //     $size_array6 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array6[5])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s6.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array6[5].'"'); 
                    //  }
                     
                    //  if($s7 > 0)
                    //  { 
                         
                    //     $size_array7 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array7[6])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s7.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array7[6].'"'); 
                    //  }
                     
                    //  if($s8 > 0)
                    //  { 
                         
                    //     $size_array8 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array8[7])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s8.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array8[7].'"'); 
                    //  }
                     
                    //  if($s9 > 0)
                    //  { 
                         
                    //     $size_array9 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array9[8])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s9.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array9[8].'"'); 
                    //  }
                     
                    //  if($s10 > 0)
                    //  { 
                         
                    //     $size_array10 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array10[9])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s10.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array10[9].'"'); 
                    //  }
                     
                    //  if($s11 > 0)
                    //  { 
                         
                    //     $size_array11 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array11[10])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s11.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array11[10].'"'); 
                    //  }
                     
                    //  if($s12 > 0)
                    //  { 
                         
                    //     $size_array12 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array12[11])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s12.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array12[11].'"'); 
                    //  }
                     
                    //  if($s13 > 0)
                    //  { 
                         
                    //     $size_array13 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array13[12])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s13.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array13[12].'"'); 
                    //  }
                     
                    //  if($s14 > 0)
                    //  { 
                         
                    //     $size_array14 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array14[13])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s14.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array14[13].'"'); 
                    //  }
                     
                    //  if($s15 > 0)
                    //  { 
                         
                    //     $size_array15 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array15[14])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s15.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array15[14].'"'); 
                    //  }
                     
                    //  if($s16 > 0)
                    //  { 
                         
                    //     $size_array16 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array16[15])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s16.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array16[15].'"'); 
                    //  }
                     
                    //  if($s17 > 0)
                    //  { 
                         
                    //     $size_array17 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array17[16])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s17.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array17[16].'"'); 
                    //  }
                     
                    //  if($s18 > 0)
                    //  { 
                         
                    //     $size_array18 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array18[17])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s18.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array18[17].'"'); 
                    //  }
                     
                    //  if($s19 > 0)
                    //  { 
                         
                    //     $size_array19 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array19[18])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s19.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array19[18].'"'); 
                    //  }
                     
                    //  if($s20 > 0)
                    //  { 
                         
                    //     $size_array20 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array20[19])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s20.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array20[19].'"'); 
                    //  }
              } 
            }
          
         
    }
    
        
    $InsertSizeData=DB::select('call AddSizeQtyFromPacking("'.$TrNo.'")');
           
    return redirect()->route('PackingMaster.index')->with('message', 'Data Saved Succesfully');  
      
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


 public function PackingGRNPrint1($pki_code)
    {
        
         
    //   DB::enableQueryLog();
       
         $PackingInhouseMaster = PackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'packing_master.vendorId')
         ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=','packing_master.vpo_code')
        ->where('packing_master.pki_code', $pki_code)
         ->get(['packing_master.*','usermaster.username','ledger_master.Ac_name','packing_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
       
        
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$PackingInhouseMaster[0]->sales_order_no)->get();
                   
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        //  DB::enableQueryLog();  
          $PackingGRNList = DB::select("SELECT   item_master.item_name,	packing_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from 	packing_inhouse_size_detail 
        inner join item_master on item_master.item_code=	packing_inhouse_size_detail.item_code 
        inner join color_master on color_master.color_id=	packing_inhouse_size_detail.color_id 
        where pki_code='".$PackingInhouseMaster[0]->pki_code."' group by 	packing_inhouse_size_detail.color_id");
             //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('PackingGRNPrint1', compact('PackingInhouseMaster','PackingGRNList','SizeDetailList','FirmDetail'));
      
    }



   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        //   DB::enableQueryLog();
        $PackingMasterList = PackingMasterModel::find($id);
        
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
          $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
             $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
          $vendorId=Session::get('vendorId');
       if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
                $S1= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')
                ->where('process_id',2)
        ->whereNotIn('vendor_purchase_order_master.vpo_code',function($query){
        $query->select('packing_master.vpo_code')->from('packing_master');
        });
                $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
            $S1= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')
            ->where('process_id',2)
            ->where('vendor_purchase_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_purchase_order_master.vpo_code',function($query){
            $query->select('packing_master.vpo_code')->from('packing_master');
            });
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
        else 
        {  
            $S1= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')
            ->where('process_id',2)
            ->where('vendor_purchase_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_purchase_order_master.vpo_code',function($query){
            $query->select('packing_master.vpo_code')->from('packing_master');
            });
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
         
         
         
         
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$PackingMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$PackingMasterList->sales_order_no)->DISTINCT()->get();
        
         // DB::enableQueryLog(); 
        $PackingMasterDetailList =PackingDetailModel::where('packing_detail.pki_code','=', $PackingMasterList->pki_code)->get();
        //  
       //dd(DB::getQueryLog());
        
        
       
        $S2=PackingMasterModel::select('vpo_code','sales_order_no')->where('vpo_code',$PackingMasterList->vpo_code);
        $VendorPurchaseOrderList = $S1->union($S2)->get();
        //DB::enableQueryLog();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($PackingMasterList->sales_order_no);
        //dd(DB::getQueryLog());
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
        $MasterdataList = DB::select("SELECT vendor_purchase_order_size_detail.item_code, vendor_purchase_order_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from vendor_purchase_order_size_detail inner join color_master on 
      color_master.color_id=vendor_purchase_order_size_detail.color_id where vpo_code='".$PackingMasterList->vpo_code."'
      group by vendor_purchase_order_size_detail.color_id");
        
        return view('PackingMasterEdit',compact('PackingMasterDetailList','ColorList' ,'BuyerList','LocationList',  'MasterdataList','SizeDetailList','PackingMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorPurchaseOrderList','Ledger' ));
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
          $this->validate($request, [
             
                'pki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
               
    ]);
 
     $is_opening=isset($request->is_opening) ? 1 : 0;
   
    $packing_type_id = isset($request->packing_type_id) ? $request->packing_type_id : ""; 
    $trno = explode("-", $request->pki_code);
    if($packing_type_id != 4)
    {
        $packingTypeData = DB::SELECT("SELECT * FROM packing_type_master WHERE packing_type_id=".$packing_type_id);
        $packing_short_name = isset($packingTypeData[0]->packing_short_name) ? $packingTypeData[0]->packing_short_name : ""; 
        $vpo_code = $packing_short_name."-".$trno[1];
    }
    else
    {
        $vpo_code = $request->vpo_code;
    }
    
    $data1=array(
               
            'pki_code'=>$request->pki_code, 
            'pki_date'=>$request->pki_date, 
            'sales_order_no'=>$request->sales_order_no,
            'Ac_code'=>$request->Ac_code, 
            'vendorId'=>$request->vendorId,
            'vpo_code'=>$vpo_code,
            'mainstyle_id'=>$request->mainstyle_id,
            'substyle_id'=>$request->substyle_id,
            'fg_id'=>$request->fg_id,
            'style_no'=>$request->style_no,
            'style_description'=>$request->style_description,
            'total_qty'=>$request->total_qty,
            'vendor_rate'=>$request->vendor_rate,
            'vendor_amount'=>$request->vendor_amount,
            'narration'=>$request->narration,
            'userId'=>$request->userId,
            'delflag'=>'0',
            'c_code'=>$request->c_code,
            'is_opening'=>$is_opening,
            'rate'=>$request->rate,
            "location_id"=>$request->location_id,
            "packing_type_id"=>$request->packing_type_id
             
        );
        
    $PackingInhouseList = PackingMasterModel::findOrFail($request->pki_code); 
    $PackingInhouseList->fill($data1)->save();
  
     
    DB::table('packing_size_detail')->where('pki_code', $request->pki_code)->delete();
    DB::table('packing_size_detail2')->where('pki_code', $request->pki_code)->delete();
    DB::table('packing_detail')->where('pki_code', $request->pki_code)->delete();
     
    $color_id= $request->color_id;
    // if(count($color_id)>0)
    // {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2=array(
          
                    'pki_code'=>$request->pki_code,
                    'pki_date'=>$request->pki_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'vpo_code'=>$request->vpo_code,
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'item_code'=>$request->item_codef[$x],
                    'color_id'=>$request->color_id[$x],
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array[$x],
                    'size_qty_total'=>$request->size_qty_total[$x],
                    'is_opening'=>$is_opening,
                    'is_transfered'=>$request->is_transfered[$x],
                    'trans_sales_order_no'=>$request->trans_sales_order_no[$x],
                    'transfer_code'=>$request->transfer_code[$x],
                      "location_id"=>$request->location_id,
                   
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
 
                      $data3=array(
                  
                        'pki_code'=>$request->pki_code,
                        'pki_date'=>$request->pki_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'vpo_code'=>$request->vpo_code,
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
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
                        'vendor_rate'=>$request->vendor_rate,
                        'is_opening'=>$is_opening,
                        'is_transfered'=>$request->is_transfered[$x],
                        'trans_sales_order_no'=>$request->trans_sales_order_no[$x],
                        'transfer_code'=>$request->transfer_code[$x],
                          "location_id"=>$request->location_id,
                          );
                          
                           if($request->is_transfered[$x]==1)
                           {
                                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail set usedFlag=1 
                                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                                  and tpki_code='".$request->transfer_code[$x]."' and
                                  color_id='".$request->color_id[$x]."'");
                                  
                                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail2 set usedFlag=1 
                                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                                  and tpki_code='".$request->transfer_code[$x]."' and
                                  color_id='".$request->color_id[$x]."'");
                                  
                                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_detail set usedFlag=1 
                                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                                  and tpki_code='".$request->transfer_code[$x]."' and
                                  color_id='".$request->color_id[$x]."'");
                           }
                           
                     //DB::enableQueryLog();
                     PackingDetailModel::insert($data2);
                     //dd(DB::getQueryLog());
                     PackingSizeDetailModel::insert($data3);
          
                     $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->Ac_code)->first();
                     $mainStyleData = DB::table('main_style_master')->select('mainstyle_name')->where('mainstyle_id', $request->mainstyle_id)->first();
                     $subStyleData = DB::table('sub_style_master')->select('substyle_name')->where('substyle_id', $request->substyle_id)->first();
                     $fgData = DB::table('fg_master')->select('fg_name')->where('fg_id', $request->fg_id)->first();
                     $colorData = DB::table('color_master')->select('color_name')->where('color_id', $request->color_id[$x])->first();
                     
                     $ac_name = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                     $mainstyle_name = isset($mainStyleData->mainstyle_name) ? $mainStyleData->mainstyle_name : "";
                     $substyle_name = isset($subStyleData->substyle_name) ? $subStyleData->substyle_name : "";
                     $fg_name = isset($fgData->fg_name) ? $fgData->fg_name : "";
                     $color_name = isset($colorData->color_name) ? $colorData->color_name : "";
                     $style_description = str_replace(["'", '"'], '', $request->style_description);
                     
                    //  if($s1 > 0)
                    //  { 
                    //   //  echo "hii";exit; 
                    //     $size_array1 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array1[0])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                    //  //DB::enableQueryLog();

                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s1.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array1[0].'"'); 
                    //                   //  dd(DB::getQueryLog());
                    //  } 
                     
                    //  if($s2 > 0)
                    //  { 
                         
                    //     $size_array2 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array2[1])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s2.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array2[1].'"'); 
                    //  }
                     
                    //  if($s3 > 0)
                    //  { 
                         
                    //     $size_array3 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array3[2])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s3.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array3[2].'"'); 
                    //  }
                     
                    //  if($s4 > 0)
                    //  { 
                         
                    //     $size_array4 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array4[3])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s4.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array4[3].'"'); 
                    //  }
              
                    //  if($s5 > 0)
                    //  { 
                         
                    //     $size_array5 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array5[4])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s5.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array5[4].'"'); 
                    //  }
                     
                    //  if($s6 > 0)
                    //  { 
                         
                    //     $size_array6 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array6[5])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s6.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array6[5].'"'); 
                    //  }
                     
                    //  if($s7 > 0)
                    //  { 
                         
                    //     $size_array7 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array7[6])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s7.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array7[6].'"'); 
                    //  }
                     
                    //  if($s8 > 0)
                    //  { 
                         
                    //     $size_array8 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array8[7])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s8.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array8[7].'"'); 
                    //  }
                     
                    //  if($s9 > 0)
                    //  { 
                         
                    //     $size_array9 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array9[8])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s9.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array9[8].'"'); 
                    //  }
                     
                    //  if($s10 > 0)
                    //  { 
                         
                    //     $size_array10 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array10[9])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s10.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array10[9].'"'); 
                    //  }
                     
                    //  if($s11 > 0)
                    //  { 
                         
                    //     $size_array11 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array11[10])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s11.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array11[10].'"'); 
                    //  }
                     
                    //  if($s12 > 0)
                    //  { 
                         
                    //     $size_array12 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array12[11])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s12.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array12[11].'"'); 
                    //  }
                     
                    //  if($s13 > 0)
                    //  { 
                         
                    //     $size_array13 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array13[12])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s13.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array13[12].'"'); 
                    //  }
                     
                    //  if($s14 > 0)
                    //  { 
                         
                    //     $size_array14 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array14[13])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s14.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array14[13].'"'); 
                    //  }
                     
                    //  if($s15 > 0)
                    //  { 
                         
                    //     $size_array15 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array15[14])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s15.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array15[14].'"'); 
                    //  }
                     
                    //  if($s16 > 0)
                    //  { 
                         
                    //     $size_array16 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array16[15])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s16.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array16[15].'"'); 
                    //  }
                     
                    //  if($s17 > 0)
                    //  { 
                         
                    //     $size_array17 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array17[16])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s17.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array17[16].'"'); 
                    //  }
                     
                    //  if($s18 > 0)
                    //  { 
                         
                    //     $size_array18 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array18[17])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s18.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array18[17].'"'); 
                    //  }
                     
                    //  if($s19 > 0)
                    //  { 
                         
                    //     $size_array19 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array19[18])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s19.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array19[18].'"'); 
                    //  }
                     
                    //  if($s20 > 0)
                    //  { 
                         
                    //     $size_array20 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array20[19])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->pki_code.'","'.$request->pki_date.'","'.$ac_name.'","'.$request->sales_order_no.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'","'.$s20.'","'.$request->location_id.'",1,"'.$request->color_id[$x].'","'.$size_array20[19].'"'); 
                    //  }
                     
              
              } // if loop avoid zero qty
            }
          
    //}
    
           
           
     $InsertSizeData=DB::select('call AddSizeQtyFromPacking("'.$request->pki_code.'")');
           
           
     return redirect()->route('PackingMaster.index')->with('message', 'Data Saved Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
     
  public function GetFINISHINGGRNQty1(Request $request)
  {
     $colors='';
    //   DB::enableQueryLog();  
      $VendorPurchaseOrderColorList = VendorPurchaseOrderDetailModel::select('color_id')->where('vpo_code',$request->vpo_code)->get();
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      
      foreach($VendorPurchaseOrderColorList as $ColorList)
      {
          $colors=$colors.$ColorList->color_id.',';
      }
      
      $colors=rtrim($colors,',');
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
    // DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT finishing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total  from finishing_inhouse_size_detail inner join color_master on 
      color_master.color_id=finishing_inhouse_size_detail.color_id where sales_order_no='".$request->tr_code."' and 
      finishing_inhouse_size_detail.color_id in (".$colors.") group by finishing_inhouse_size_detail.color_id");
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

      $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
        //  DB::enableQueryLog();  
      $List = DB::select("SELECT outward_for_packing_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from outward_for_packing_size_detail inner join color_master on 
      color_master.color_id=outward_for_packing_size_detail.color_id where 
      outward_for_packing_size_detail.sales_order_no='".$request->tr_code."' and
      outward_for_packing_size_detail.color_id='".$row->color_id."'");    
       
   
   if(isset($row->s1)) { $s1=((intval($row->s1))-(intval($List[0]->s_1))); }
   if(isset($row->s2)) { $s2=((intval($row->s2))-(intval($List[0]->s_2))); }
   if(isset($row->s3)) { $s3=((intval($row->s3))-(intval($List[0]->s_3))); }
   if(isset($row->s4)) { $s4=((intval($row->s4))-(intval($List[0]->s_4))); }
   if(isset($row->s5)) { $s5=((intval($row->s5))-(intval($List[0]->s_5))); }
   if(isset($row->s6)) { $s6=((intval($row->s6))-(intval($List[0]->s_6))); }
   if(isset($row->s7)) { $s7=((intval($row->s7))-(intval($List[0]->s_7)));}
   if(isset($row->s8)) { $s8=((intval($row->s8))-(intval($List[0]->s_8)));}
   if(isset($row->s9)) { $s9=((intval($row->s9))-(intval($List[0]->s_9)));}
   if(isset($row->s10)) { $s10=((intval($row->s10))-(intval($List[0]->s_10)));}
   if(isset($row->s11)) { $s11=((intval($row->s11))-(intval($List[0]->s_11)));}
   if(isset($row->s12)) { $s12=((intval($row->s12))-(intval($List[0]->s_12)));}
   if(isset($row->s13)) { $s13=((intval($row->s13))-(intval($List[0]->s_13)));}
   if(isset($row->s14)) { $s14=((intval($row->s14))-(intval($List[0]->s_14)));}
   if(isset($row->s15)) { $s15=((intval($row->s15))-(intval($List[0]->s_15)));}
   if(isset($row->s16)) {$s16=((intval($row->s16))-(intval($List[0]->s_16)));}
   if(isset($row->s17)) { $s17=((intval($row->s17))-(intval($List[0]->s_17)));}
   if(isset($row->s18)) { $s18=((intval($row->s18))-(intval($List[0]->s_18)));}
   if(isset($row->s19)) { $s19=((intval($row->s19))-(intval($List[0]->s_19)));}
   if(isset($row->s20)) { $s20=((intval($row->s20))-(intval($List[0]->s_20)));}

          if(isset($row->s1)) { $html.='<td>'.$s1.'</td>';}
          if(isset($row->s2)) { $html.='<td>'.$s2.'</td>';}
          if(isset($row->s3)) { $html.='<td>'.$s3.'</td>';}
          if(isset($row->s4)) { $html.='<td>'.$s4.'</td>';}
          if(isset($row->s5)) { $html.='<td>'.$s5.'</td>';}
          if(isset($row->s6)) { $html.='<td>'.$s6.'</td>';}
          if(isset($row->s7)) { $html.='<td>'.$s7.'</td>';}
          if(isset($row->s8)) { $html.='<td>'.$s8.'</td>';}
          if(isset($row->s9)) { $html.='<td>'.$s9.'</td>';}
          if(isset($row->s10)) { $html.='<td>'.$s10.'</td>';}
          if(isset($row->s11)) { $html.='<td>'.$s11.'</td>';}
          if(isset($row->s12)) { $html.='<td>'.$s12.'</td>';}
          if(isset($row->s13)) { $html.='<td>'.$s13.'</td>';}
          if(isset($row->s14)) { $html.='<td>'.$s14.'</td>';}
          if(isset($row->s15)) { $html.='<td>'.$s15.'</td>';}
          if(isset($row->s16)) { $html.='<td>'.$s16.'</td>';}
          if(isset($row->s17)) { $html.='<td>'.$s17.'</td>';}
          if(isset($row->s18)) { $html.='<td>'.$s18.'</td>';}
          if(isset($row->s19)) { $html.='<td>'.$s19.'</td>';}
          if(isset($row->s20)) { $html.='<td>'.$s20.'</td>';}
          $html.='<td>'.($row->size_qty_total-$List[0]->size_qty_total).'</td>';
          
          $no=$no+1;
        }
         
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }

   
     
     
     
     
     
   public function getFinishingInhouseDetails(Request $request)
    { 
        $vpo_code= $request->input('vpo_code');
        $MasterdataList = DB::select("select Ac_code,sales_order_no, vendorId, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from vendor_purchase_order_master where vendor_purchase_order_master.delflag=0 and vpo_code='".$vpo_code."'");
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

  
  public function FNSI_GetOrderQty1(Request $request)
  {
      // vpo_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by vpo_
    //   DB::enableQueryLog();
      $VendorPurchaseOrderMasterList = VendorPurchaseOrderModel::find($request->vpo_code);
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query); 
      $VendorPurchaseOrderDetailList = VendorPurchaseOrderDetailModel::where('vpo_code',$request->vpo_code)->first();
      
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$VendorPurchaseOrderMasterList->sales_order_no)->first();
     
       $ColorList = DB::table('vendor_purchase_order_detail')->select('vendor_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'vendor_purchase_order_detail.color_id', 'left outer')
        ->where('vpo_code','=',$request->vpo_code)->DISTINCT()->get();
      
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        //   DB::enableQueryLog();  
    //   $MasterdataList = DB::select("SELECT finishing_inhouse_size_detail.item_code, finishing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
    //   sum(size_qty_total) as size_qty_total from finishing_inhouse_size_detail inner join color_master on 
    //   color_master.color_id=finishing_inhouse_size_detail.color_id where vpo_code='".$request->vpo_code."'
    //   group by finishing_inhouse_size_detail.color_id");
    
     $MasterdataList = DB::select("SELECT vendor_purchase_order_size_detail.sales_order_no,vendor_purchase_order_size_detail.item_code, vendor_purchase_order_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from vendor_purchase_order_size_detail inner join color_master on 
      color_master.color_id=vendor_purchase_order_size_detail.color_id where vpo_code='".$request->vpo_code."'
      group by vendor_purchase_order_size_detail.color_id");
    
    
       

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



        //   if(isset($row->s1)) { $html.='<td>'.$row->s1.' <input style="width:80px; float:left;" max='.$row->s1.' min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
        //   if(isset($row->s2)) { $html.='<td>'.$row->s2.' <input style="width:80px; float:left;" max='.$row->s2.' min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
        //   if(isset($row->s3)) { $html.='<td>'.$row->s3.' <input style="width:80px; float:left;" max='.$row->s3.' min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
        //   if(isset($row->s4)) { $html.='<td>'.$row->s4.' <input style="width:80px; float:left;" max='.$row->s4.' min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
        //   if(isset($row->s5)) { $html.='<td>'.$row->s5.' <input style="width:80px; float:left;" max='.$row->s5.' min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
        //   if(isset($row->s6)) { $html.='<td>'.$row->s6.' <input style="width:80px; float:left;" max='.$row->s6.' min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
        //   if(isset($row->s7)) { $html.='<td>'.$row->s7.' <input style="width:80px; float:left;" max='.$row->s7.' min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
        //   if(isset($row->s8)) { $html.='<td>'.$row->s8.' <input style="width:80px; float:left;" max='.$row->s8.' min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
        //   if(isset($row->s9)) { $html.='<td>'.$row->s9.' <input style="width:80px; float:left;" max='.$row->s9.' min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
        //   if(isset($row->s10)) { $html.='<td>'.$row->s10.' <input style="width:80px; float:left;" max='.$row->s10.' min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
        //   if(isset($row->s11)) { $html.='<td>'.$row->s11.' <input style="width:80px; float:left;" max='.$row->s11.' min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
        //   if(isset($row->s12)) { $html.='<td>'.$row->s12.' <input style="width:80px;  float:left;" max='.$row->s12.' min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
        //   if(isset($row->s13)) { $html.='<td>'.$row->s13.' <input style="width:80px; float:left;" max='.$row->s13.' min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
        //   if(isset($row->s14)) { $html.='<td>'.$row->s14.' <input style="width:80px; float:left;" max='.$row->s14.' min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
        //   if(isset($row->s15)) { $html.='<td>'.$row->s15.' <input style="width:80px; float:left;" max='.$row->s15.' min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
        //   if(isset($row->s16)) { $html.='<td>'.$row->s16.' <input style="width:80px; float:left;" max='.$row->s16.' min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
        //   if(isset($row->s17)) { $html.='<td>'.$row->s17.' <input style="width:80px; float:left;" max='.$row->s17.' min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
        //   if(isset($row->s18)) { $html.='<td>'.$row->s18.' <input style="width:80px;  float:left;" max='.$row->s18.' min="0" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
        //   if(isset($row->s19)) { $html.='<td>'.$row->s19.' <input style="width:80px; float:left;" max='.$row->s19.' min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
        //   if(isset($row->s20)) { $html.='<td>'.$row->s20.' <input style="width:80px; float:left;" max='.$row->s20.' min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
    
              $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
       // DB::enableQueryLog();  
      $List = DB::select("SELECT packing_inhouse_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=packing_inhouse_size_detail.color_id where packing_inhouse_size_detail.sales_order_no='".$row->sales_order_no."' AND
      packing_inhouse_size_detail.color_id='".$row->color_id."'
       ");

      $List1 = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
              sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
              color_master.color_id=stitching_inhouse_size_detail.color_id where stitching_inhouse_size_detail.sales_order_no='".$row->sales_order_no."' AND
              stitching_inhouse_size_detail.color_id='".$row->color_id."'");
  
       
     //dd(DB::getQueryLog());
 
   
//   if(isset($row->s1)) { $s1=( (intval($List[0]->s_1))); }
//   if(isset($row->s2)) { $s2=( (intval($List[0]->s_2))); }
//   if(isset($row->s3)) { $s3=( (intval($List[0]->s_3))); }
//   if(isset($row->s4)) { $s4=( (intval($List[0]->s_4))); }
//   if(isset($row->s5)) { $s5=( (intval($List[0]->s_5))); }
//   if(isset($row->s6)) { $s6=( (intval($List[0]->s_6))); }
//   if(isset($row->s7)) { $s7=( (intval($List[0]->s_7)));}
//   if(isset($row->s8)) { $s8=( (intval($List[0]->s_8)));}
//   if(isset($row->s9)) { $s9=( (intval($List[0]->s_9)));}
//   if(isset($row->s10)) { $s10=( (intval($List[0]->s_10)));}
//   if(isset($row->s11)) { $s11=( (intval($List[0]->s_11)));}
//   if(isset($row->s12)) { $s12=( (intval($List[0]->s_12)));}
//   if(isset($row->s13)) { $s13=( (intval($List[0]->s_13)));}
//   if(isset($row->s14)) { $s14=( (intval($List[0]->s_14)));}
//   if(isset($row->s15)) { $s15=( (intval($List[0]->s_15)));}
//   if(isset($row->s16)) {$s16=( (intval($List[0]->s_16)));}
//   if(isset($row->s17)) { $s17=( (intval($List[0]->s_17)));}
//   if(isset($row->s18)) { $s18=( (intval($List[0]->s_18)));}
//   if(isset($row->s19)) { $s19=( (intval($List[0]->s_19)));}
//   if(isset($row->s20)) { $s20=((intval($List[0]->s_20)));}
  
  if(isset($row->s1)) { $s1=((intval($List1[0]->s_1))-(intval($List[0]->s_1))); }
  if(isset($row->s2)) { $s2=((intval($List1[0]->s_2))-(intval($List[0]->s_2))); }
  if(isset($row->s3)) { $s3=((intval($List1[0]->s_3))-(intval($List[0]->s_3))); }
  if(isset($row->s4)) { $s4=((intval($List1[0]->s_4))-(intval($List[0]->s_4))); }
  if(isset($row->s5)) { $s5=((intval($List1[0]->s_5))-(intval($List[0]->s_5))); }
  if(isset($row->s6)) { $s6=((intval($List1[0]->s_6))-(intval($List[0]->s_6))); }
  if(isset($row->s7)) { $s7=((intval($List1[0]->s_7))-(intval($List[0]->s_7)));}
  if(isset($row->s8)) { $s8=((intval($List1[0]->s_8))-(intval($List[0]->s_8)));}
  if(isset($row->s9)) { $s9=((intval($List1[0]->s_9))-(intval($List[0]->s_9)));}
  if(isset($row->s10)) { $s10=((intval($List1[0]->s_10))-(intval($List[0]->s_10)));}
  if(isset($row->s11)) { $s11=((intval($List1[0]->s_11))-(intval($List[0]->s_11)));}
  if(isset($row->s12)) { $s12=((intval($List1[0]->s_12))-(intval($List[0]->s_12)));}
  if(isset($row->s13)) { $s13=((intval($List1[0]->s_13))-(intval($List[0]->s_13)));}
  if(isset($row->s14)) { $s14=((intval($List1[0]->s_14))-(intval($List[0]->s_14)));}
  if(isset($row->s15)) { $s15=((intval($List1[0]->s_15))-(intval($List[0]->s_15)));}
  if(isset($row->s16)) {$s16=((intval($List1[0]->s_16))-(intval($List[0]->s_16)));}
  if(isset($row->s17)) { $s17=((intval($List1[0]->s_17))-(intval($List[0]->s_17)));}
  if(isset($row->s18)) { $s18=((intval($List1[0]->s_18))-(intval($List[0]->s_18)));}
  if(isset($row->s19)) { $s19=((intval($List1[0]->s_19))-(intval($List[0]->s_19)));}
  if(isset($row->s20)) { $s20=((intval($List1[0]->s_20))-(intval($List[0]->s_20)));}
    
//**********************Safe for 6 month to enable min and max restriction***********
        //   if(isset($row->s1)) { $html.='<td>'.$s1.' <input style="width:80px; float:left;" max="'.$s1.'" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
        //   if(isset($row->s2)) { $html.='<td>'.$s2.' <input style="width:80px; float:left;" max="'.$s2.'" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
        //   if(isset($row->s3)) { $html.='<td>'.$s3.' <input style="width:80px; float:left;" max="'.$s3.'" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
        //   if(isset($row->s4)) { $html.='<td>'.$s4.' <input style="width:80px; float:left;" max="'.$s4.'" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
        //   if(isset($row->s5)) { $html.='<td>'.$s5.' <input style="width:80px; float:left;" max="'.$s5.'" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
        //   if(isset($row->s6)) { $html.='<td>'.$s6.' <input style="width:80px; float:left;" max="'.$s6.'" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
        //   if(isset($row->s7)) { $html.='<td>'.$s7.' <input style="width:80px; float:left;" max="'.$s7.'" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
        //   if(isset($row->s8)) { $html.='<td>'.$s8.' <input style="width:80px; float:left;" max="'.$s8.'" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
        //   if(isset($row->s9)) { $html.='<td>'.$s9.' <input style="width:80px; float:left;" max="'.$s9.'" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
        //   if(isset($row->s10)) { $html.='<td>'.$s10.' <input style="width:80px; float:left;" max="'.$s10.'" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
        //   if(isset($row->s11)) { $html.='<td>'.$s11.' <input style="width:80px; float:left;" max="'.$s11.'" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
        //   if(isset($row->s12)) { $html.='<td>'.$s12.' <input style="width:80px; float:left;" max="'.$s12.'" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
        //   if(isset($row->s13)) { $html.='<td>'.$s13.' <input style="width:80px; float:left;" max="'.$s13.'" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
        //   if(isset($row->s14)) { $html.='<td>'.$s14.' <input style="width:80px; float:left;" max="'.$s14.'" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
        //   if(isset($row->s15)) { $html.='<td>'.$s15.' <input style="width:80px; float:left;" max="'.$s15.'" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
        //   if(isset($row->s16)) { $html.='<td>'.$s16.' <input style="width:80px; float:left;" max="'.$s16.'" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
        //   if(isset($row->s17)) { $html.='<td>'.$s17.' <input style="width:80px; float:left;" max="'.$s17.'" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
        //   if(isset($row->s18)) { $html.='<td>'.$s18.' <input style="width:80px; float:left;" max="'.$s18.'" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
        //   if(isset($row->s19)) { $html.='<td>'.$s19.' <input style="width:80px; float:left;" max="'.$s19.'" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
        //   if(isset($row->s20)) { $html.='<td>'.$s20.' <input style="width:80px; float:left;" max="'.$s20.'" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
//**************************** End ***********************************        
          if(isset($row->s1)) { $html.='<td>'.$s1.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) { $html.='<td>'.$s2.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) { $html.='<td>'.$s3.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) { $html.='<td>'.$s4.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) { $html.='<td>'.$s5.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) { $html.='<td>'.$s6.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'"  name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) { $html.='<td>'.$s7.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'"  name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) { $html.='<td>'.$s8.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'"  name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) { $html.='<td>'.$s9.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'"  name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) { $html.='<td>'.$s10.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) { $html.='<td>'.$s11.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) { $html.='<td>'.$s12.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) { $html.='<td>'.$s13.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) { $html.='<td>'.$s14.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) { $html.='<td>'.$s15.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) { $html.='<td>'.$s16.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) { $html.='<td>'.$s17.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) { $html.='<td>'.$s18.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) { $html.='<td>'.$s19.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) { $html.='<td>'.$s20.' <input style="width:80px; float:left;" max="'.((intval($List1[0]->size_qty_total))-(intval($List[0]->size_qty_total))).'" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
          $html.='<td>'.($List1[0]->size_qty_total-$List[0]->size_qty_total).'
            <input type="hidden" name="overall_size_qty" value="'.($List1[0]->size_qty_total-$List[0]->size_qty_total).'" class="overall_size_qty">
            <input type="number" name="size_qty_total[]" class="size_qty_total" value="" max="'.($List1[0]->size_qty_total-$List[0]->size_qty_total).'"  id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly />
            <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
            <input type="hidden" name="size_array[]"  value="'.$VendorPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
           $html.='</tr>';

          $no=$no+1;
        }
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
  
  public function Stitching_GetOrderQty1(Request $request)
  {
          $VendorPurchaseOrderMasterList = VendorPurchaseOrderModel::find($request->vpo_code);
          $VendorPurchaseOrderDetailList = VendorPurchaseOrderDetailModel::where('vpo_code',$request->vpo_code)->first();
          
         $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$VendorPurchaseOrderMasterList->sales_order_no)->first();

          
          $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
          $sizes='';
          $no=1;
          foreach ($SizeDetailList as $sz) 
          {
              $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
              $no=$no+1;
          }
          $sizes=rtrim($sizes,',');
          
          $MasterdataList = DB::select("SELECT vendor_purchase_order_size_detail.sales_order_no,vendor_purchase_order_size_detail.item_code, vendor_purchase_order_size_detail.color_id, color_name, ".$sizes.", 
              sum(size_qty_total) as size_qty_total from vendor_purchase_order_size_detail inner join color_master on 
              color_master.color_id=vendor_purchase_order_size_detail.color_id where vpo_code='".$request->vpo_code."'
              group by vendor_purchase_order_size_detail.color_id");
              
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
                      
                      </tr>
                  </thead>
                  <tbody>';
              $no=1;
            foreach ($MasterdataList as $row) 
            {             
                
              $html .='<tr>';
              $html .='
              <td>'.$no.'</td>';
               
            $html.=' <td>'.$row->color_name.'</td>';
    
        
          $sizex='';
          $nox=1;
          foreach ($SizeDetailList as $sz) 
          {
              $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
              $nox=$nox+1;
          }
          $sizex=rtrim($sizex,',');
             
            
          $List = DB::select("SELECT packing_inhouse_size_detail.color_id, color_name, ".$sizex.", 
              sum(size_qty_total) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
              color_master.color_id=packing_inhouse_size_detail.color_id where packing_inhouse_size_detail.sales_order_no='".$row->sales_order_no."' AND 
              packing_inhouse_size_detail.color_id='".$row->color_id."'");

            
          $List1 = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
              sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
              color_master.color_id=stitching_inhouse_size_detail.color_id where stitching_inhouse_size_detail.sales_order_no='".$row->sales_order_no."' AND 
              stitching_inhouse_size_detail.color_id='".$row->color_id."'");
              
  
          if(isset($row->s1)) { $s1=((intval($List1[0]->s_1))-(intval($List[0]->s_1))); }
          if(isset($row->s2)) { $s2=((intval($List1[0]->s_2))-(intval($List[0]->s_2))); }
          if(isset($row->s3)) { $s3=((intval($List1[0]->s_3))-(intval($List[0]->s_3))); }
          if(isset($row->s4)) { $s4=((intval($List1[0]->s_4))-(intval($List[0]->s_4))); }
          if(isset($row->s5)) { $s5=((intval($List1[0]->s_5))-(intval($List[0]->s_5))); }
          if(isset($row->s6)) { $s6=((intval($List1[0]->s_6))-(intval($List[0]->s_6))); }
          if(isset($row->s7)) { $s7=((intval($List1[0]->s_7))-(intval($List[0]->s_7)));}
          if(isset($row->s8)) { $s8=((intval($List1[0]->s_8))-(intval($List[0]->s_8)));}
          if(isset($row->s9)) { $s9=((intval($List1[0]->s_9))-(intval($List[0]->s_9)));}
          if(isset($row->s10)) { $s10=((intval($List1[0]->s_10))-(intval($List[0]->s_10)));}
          if(isset($row->s11)) { $s11=((intval($List1[0]->s_11))-(intval($List[0]->s_11)));}
          if(isset($row->s12)) { $s12=((intval($List1[0]->s_12))-(intval($List[0]->s_12)));}
          if(isset($row->s13)) { $s13=((intval($List1[0]->s_13))-(intval($List[0]->s_13)));}
          if(isset($row->s14)) { $s14=((intval($List1[0]->s_14))-(intval($List[0]->s_14)));}
          if(isset($row->s15)) { $s15=((intval($List1[0]->s_15))-(intval($List[0]->s_15)));}
          if(isset($row->s16)) {$s16=((intval($List1[0]->s_16))-(intval($List[0]->s_16)));}
          if(isset($row->s17)) { $s17=((intval($List1[0]->s_17))-(intval($List[0]->s_17)));}
          if(isset($row->s18)) { $s18=((intval($List1[0]->s_18))-(intval($List[0]->s_18)));}
          if(isset($row->s19)) { $s19=((intval($List1[0]->s_19))-(intval($List[0]->s_19)));}
          if(isset($row->s20)) { $s20=((intval($List1[0]->s_20))-(intval($List[0]->s_20)));}
    

          if(isset($row->s1)) { $html.='<td>'.$s1.'</td>';}
          if(isset($row->s2)) { $html.='<td>'.$s2.'</td>';}
          if(isset($row->s3)) { $html.='<td>'.$s3.'</td>';}
          if(isset($row->s4)) { $html.='<td>'.$s4.'</td>';}
          if(isset($row->s5)) { $html.='<td>'.$s5.'</td>';}
          if(isset($row->s6)) { $html.='<td>'.$s6.'</td>';}
          if(isset($row->s7)) { $html.='<td>'.$s7.'</td>';}
          if(isset($row->s8)) { $html.='<td>'.$s8.'</td>';}
          if(isset($row->s9)) { $html.='<td>'.$s9.'</td>';}
          if(isset($row->s10)) { $html.='<td>'.$s10.'</td>';}
          if(isset($row->s11)) { $html.='<td>'.$s11.'</td>';}
          if(isset($row->s12)) { $html.='<td>'.$s12.'</td>';}
          if(isset($row->s13)) { $html.='<td>'.$s13.'</td>';}
          if(isset($row->s14)) { $html.='<td>'.$s14.'</td>';}
          if(isset($row->s15)) { $html.='<td>'.$s15.'</td>';}
          if(isset($row->s16)) { $html.='<td>'.$s16.'</td>';}
          if(isset($row->s17)) { $html.='<td>'.$s17.'</td>';}
          if(isset($row->s18)) { $html.='<td>'.$s18.'</td>';}
          if(isset($row->s19)) { $html.='<td>'.$s19.'</td>';}
          if(isset($row->s20)) { $html.='<td>'.$s20.'</td>';}
          $html.='<td>'.($List1[0]->size_qty_total - $List[0]->size_qty_total).'</td>
          </tr>';

          $no=$no+1;
        }
          $html.=' 
            </tbody>
            </table>';


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
  ->where('item_code','=',$tr_code->item_code) ->where('sales_order_no','=',$sales_order_no)
  ->first();
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     $UnitList = UnitModel::where('delflag','=', '0')->get();
     $ItemList = ItemModel::where('delflag','=', '0')->where('class_id','=', $codefetch->class_id)->get();
     $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
     $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
     
     $total_consumption= ($codefetch->consumption)+(($codefetch->consumption)*($codefetch->wastage/100));
     $bom_qty=round(($total_consumption*$size_qty_total),2);
                
    
     $html = '';
     
    $html .='<tr class="thisRow">';
   
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
$html.=' 
 
 <td> <select name="item_code[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required>
<option value="">--Item List--</option>';
foreach($ItemList as  $rowitem)
{
    $html.='<option value="'.$rowitem->item_code.'"';

    $rowitem->item_code == $codefetch->item_code ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowitem->item_name.'</option>';
}
$html.='</select></td>
 
 <td> <select name="class_id[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required>
<option value="">--Classification--</option>';
foreach($ClassList as  $rowclass)
{
    $html.='<option value="'.$rowclass->class_id.'"';
    $rowclass->class_id == $codefetch->class_id ? $html.='selected="selected"' : ''; 
    $html.='>'.$rowclass->class_name.'</option>';
}
$html.='</select></td> 
 
 <td><input type="text"    name="description[]" value="'.$codefetch->description.'" id="description" style="width:200px; height:30px;" required /></td> 
 
<td><input type="number" step="0.01"    name="consumption[]" value="'.$codefetch->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
  
<td> <select name="unit_id[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
<option value="">--Unit--</option>';
foreach($UnitList as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $codefetch->unit_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td> 

<td><input type="number" step="0.01"   name="wastage[]" value="'.$codefetch->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
<td><input type="text"  name="bom_qty[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
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
                $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                $bom_qty=round(($total_consumption*$qty),2);
            
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
                $html.=' 
                <td> <select name="item_codes[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_ids[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text"    name="descriptions[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                <td><input type="number" step="0.01"    name="consumptions[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_ids[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="0.01"   name="wastages[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtys[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
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
     
     
     public function GetPackingConsumption(Request $request)
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
    
  $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
  //DB::enableQueryLog(); 
  
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
   $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', 3)->get();
 // print_r(count($codefetch));
  $html = '';

     $x=0;$qty=0;
     
foreach($SizeList as $sz)
 { 
     
     
        $qty=$SizeQty[$x++];
        if($qty!=0)
        {
            
        // DB::enableQueryLog();
        
           $codefetch = DB::table('bom_packing_trims_details')->select("item_code","consumption","description","wastage","class_id","unit_id")
          ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
          ->get(); 
          
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
           
            foreach($codefetch as $rowsew)
            {   
                $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                $bom_qty=round(($total_consumption*$qty),2);
            
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
                $html.=' 
                <td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text"    name="descriptionss[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                <td><input type="number" step="0.01"    name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="0.01"   name="wastagess[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtyss[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                </td> 
                ';
                $html .='</tr>';
            } //main foreach
    }  // if loop for size qty 0
     
 }  // Size Array Foreach
    return response()->json(['html' => $html]);
     
    }    
     
     
       public function getVendorPurchaseOrderDetails(Request $request)
    { 
        $vpo_code= $request->vpo_code;
        $MasterdataList = DB::select("select distinct sales_order_no,mainstyle_id,substyle_id,fg_id,style_no,style_description, vendorId  from vendor_purchase_order_master where vpo_code in (".$vpo_code.")");
        return json_encode($MasterdataList);
    }   
     
     
     
     public function getVendorPO(Request $request)
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
        $vpo_code="'".$row->vpo_code."'";
        {$html .= '<option value="'.$vpo_code.'">'.$row->vpo_code.'('.$row->sales_order_no.')</option>';}
    }
      return response()->json(['html' => $html]);
}
     
     
        public function GetCuttingPOItemList(Request $request)
        {
            $html='';
             if($request->part_id==1)
           { 
                $ItemList = DB::select("select distinct item_code from vendor_purchase_order_size_detail2 as f1
                where f1.vpo_code in (".$request->vpo_code.")");
           }
           else
           {
                $ItemList = DB::select("select distinct item_code from vendor_purchase_order_trim_fabric_details
                as f2 where f2.vpo_code in (".$request->vpo_code.")");
           }
            
            foreach ($ItemList as $row) 
            {
                $item = ItemModel::where('item_code','=', $row->item_code)->first(); 
                $html .= '<option value="'.$row->item_code.'">'.$item->item_name.'</option>';
            }    
   
             return response()->json(['html' => $html]);
        }    
     
    public function destroy($id)
    {
        DB::table('packing_master')->where('pki_code', $id)->delete();
         DB::table('packing_inhouse_size_detail2')->where('pki_code', $id)->delete();
        DB::table('packing_inhouse_size_detail')->where('pki_code', $id)->delete();
        DB::table('packing_inhouse_detail')->where('pki_code', $id)->delete();
        DB::table('FGStockDataByTwo')->where('code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    
    
    
    
    
    
    public function Op_GetOrderQty1(Request $request)
    {
      // W_   as Vendor Work Order sa same function name is defined in BOM, So Name prepended by W_
      
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
         // $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
          
           $sizes=$sizes.'sum(s'.$no.')+(sum(s'.$no.')*((shipment_allowance+garment_rejection_allowance)/100)) as s'.$no.',';
          
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        //   DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT sales_order_detail.item_code,sales_order_detail.color_id, color_name, ".$sizes.", 
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
               
                  </tr>
              </thead>
              <tbody>';
          $no=1;
          foreach ($MasterdataList as $row) 
        {
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
           
        $html.=' <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $row1)
        {
            $html.='<option value="'.$row1->color_id.'"';
            $row1->color_id == $row->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$row1->color_name.'</option>';
        }
        
        $html.='</select>
        
          <input type="hidden" name="item_codef[]"  value="'.$row->item_code.'" id="item_codef" style="width:80px; height:30px; float:left;"  />
        </td>';


      $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'sum(s'.$nox.') as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
        //  DB::enableQueryLog();  
      $CompareList = DB::select("SELECT vendor_work_order_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
      color_master.color_id=vendor_work_order_size_detail.color_id where 
      vendor_work_order_size_detail.sales_order_no='".$request->tr_code."' and
      vendor_work_order_size_detail.color_id='".$row->color_id."'
       ");


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

if($request->packing_type_id == 2 || $request->packing_type_id == 3 )
{
    $s11 = '';
    $s12 = '';
    $s13 = '';
    $s14 = '';
    $s15 = '';
    $s16 = '';
    $s17 = '';
    $s18 = '';
    $s19 = '';
    $s110 = '';
    $s111 = '';
    $s112 = '';
    $s113 = '';
    $s114 = '';
    $s115 = '';
    $s116 = '';
    $s117 = '';
    $s118 = '';
    $s119 = '';
    $s120 = '';
}
else
{
    $s11 = 'max="'.(isset($s1) ? $s1 : 0).'"';
    $s12 = 'max="'.(isset($s2) ? $s2 : 0).'"';
    $s13 = 'max="'.(isset($s3) ? $s3 : 0).'"';
    $s14 = 'max="'.(isset($s4) ? $s4 : 0).'"';
    $s15 = 'max="'.(isset($s5) ? $s5 : 0).'"';
    $s16 = 'max="'.(isset($s6) ? $s6 : 0).'"';
    $s17 = 'max="'.(isset($s7) ? $s7 : 0).'"';
    $s18 = 'max="'.(isset($s8) ? $s8  : 0).'"';
    $s19 = 'max="'.(isset($s9) ? $s9 : 0).'"';
    $s110 = 'max="'.(isset($s10) ? $s10 : 0).'"';
    $s111 = 'max="'.(isset($s11) ? $s11 : 0).'"';
    $s112 = 'max="'.(isset($s12) ? $s12 : 0).'"';
    $s113 = 'max="'.(isset($s13) ? $s13 : 0).'"';
    $s114 = 'max="'.(isset($s14) ? $s14 : 0).'"';
    $s115 = 'max="'.(isset($s15) ? $s15 : 0).'"';
    $s116 = 'max="'.(isset($s16) ? $s16 : 0).'"';
    $s117 = 'max="'.(isset($s17) ? $s17 : 0).'"';
    $s118 = 'max="'.(isset($s18) ? $s18 : 0).'"';
    $s119 = 'max="'.(isset($s19) ? $s19 : 0).'"';
    $s120 = 'max="'.(isset($s20) ? $s20 : 0).'"';
}

$total_qty=0;
          if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.' <input style="width:80px; float:left;" '.$s11.' name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.' <input style="width:80px; float:left;" '.$s12.' name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.' <input style="width:80px; float:left;" '.$s13.' name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.' <input style="width:80px; float:left;" '.$s14.' name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.' <input style="width:80px; float:left;" '.$s15.' name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.' <input style="width:80px; float:left;" '.$s16.' name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.' <input style="width:80px; float:left;" '.$s17.' name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.' <input style="width:80px; float:left;" '.$s18.' name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.' <input style="width:80px; float:left;" '.$s19.' name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.' <input style="width:80px; float:left;" '.$s110.' name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.' <input style="width:80px; float:left;" '.$s111.' name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.' <input style="width:80px; float:left;" '.$s112.' name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.' <input style="width:80px; float:left;" '.$s113.' name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.' <input style="width:80px; float:left;" '.$s114.' name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.' <input style="width:80px; float:left;" '.$s115.' name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.' <input style="width:80px; float:left;" '.$s116.' name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.' <input style="width:80px; float:left;" '.$s117.' name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.' <input style="width:80px; float:left;" '.$s118.' name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.' <input style="width:80px; float:left;" '.$s119.' name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.' <input style="width:80px; float:left;" '.$s120.' name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
       
         
        
          $html.='<td>'.($total_qty-$List->size_qty_total).'  
          
          
          <input type="hidden" name="overall_size_qty" value="'.($total_qty-$List->size_qty_total).'" class="overall_size_qty" style="width:80px; float:left;">
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
         
          <input type="hidden" name="is_transfered[]"   value="" id="is_transfered" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="trans_sales_order_no[]"  value="" id="trans_sales_order_no" style="width:80px; float:left;"  />
        <input type="hidden" name="transfer_code[]"  value="" id="transfer_code" style="width:80px;  float:left;"  />
        
        
          
          </td>';
          
          
          
          $html.='</tr>';

          $no=$no+1;
       
        
         
        
         $sizet='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizet=$sizet.'ifnull(sum(s'.$nox.'),0) as s'.$nox.',';
          $nox=$nox+1;
      }
      $sizet=rtrim($sizet,',');
      
    if($request->packing_type_id == 1)
    {
        $is_transfered = 1;
    }
    else
    {
         $is_transfered = 0;
    }
    
    if($request->packing_type_id == 2)
    {
        $is_rtv = 1;
    }
    else
    {
        $is_rtv = 0;
    }
    
    if($request->packing_type_id == 2)
    {
        //DB::enableQueryLog();
        $TransferList = DB::select("SELECT sales_order_no as main_sales_order_no,rpki_code as tpki_code, return_packing_inhouse_size_detail.color_id, color_name, ".$sizet.", 
          sum(size_qty_total) as size_qty_total from return_packing_inhouse_size_detail inner join color_master on 
          color_master.color_id=return_packing_inhouse_size_detail.color_id where 
          return_packing_inhouse_size_detail.sales_order_no='".$request->tr_code."'
          and return_packing_inhouse_size_detail.color_id='".$row->color_id."' and return_packing_inhouse_size_detail.usedFlag=0");
        // dd(DB::getQueryLog());
        
        
    }
    else 
    {
        $TransferList = DB::select("SELECT main_sales_order_no,tpki_code, transfer_packing_inhouse_size_detail.color_id, color_name, ".$sizet.", 
          sum(size_qty_total) as size_qty_total from transfer_packing_inhouse_size_detail inner join color_master on 
          color_master.color_id=transfer_packing_inhouse_size_detail.color_id where 
          transfer_packing_inhouse_size_detail.sales_order_no='".$request->tr_code."'
          and transfer_packing_inhouse_size_detail.color_id='".$row->color_id."'
          and transfer_packing_inhouse_size_detail.usedFlag=0");
           
    }
         
    $SieQtyArray=''; $total_qty=0;
   
    if(count($TransferList)>0 && $TransferList[0]->size_qty_total!=0 || count($TransferList)>0 && $request->packing_type_id == 2)
    {
       
        foreach($TransferList as $row1)
        { 
            $SieQtyArray='';
            
            
        $html.='<tr ><td >'.$no.'</td>';
        $html.=' <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px; background-color:#cbe9dc;" disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $rowx)
        {
            $html.='<option value="'.$rowx->color_id.'"';
            $rowx->color_id == $row1->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$rowx->color_name.'</option>';
        }
        
        $html.='</select>
        
          <input type="hidden" name="item_codef[]"  value="" id="item_codef" style="width:80px; height:30px; float:left;"  />
        </td>';
 
             
          if(isset($row1->s1)) {$SieQtyArray=$SieQtyArray.$row1->s1.',';    $total_qty=$total_qty+round($row1->s1); $html.='<td  >  <input style="width:80px; float:left; background-color:#cbe9dc; " name="s1[]" class="size_id" type="number" id="s1" value="'.$row1->s1.'" required readonly /></td>';}
          if(isset($row1->s2)) {$SieQtyArray=$SieQtyArray.$row1->s2.',';    $total_qty=$total_qty+round($row1->s2); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s2[]" type="number" class="size_id" id="s2" value="'.$row1->s2.'" required readonly/></td>';}
          if(isset($row1->s3)) {$SieQtyArray=$SieQtyArray.$row1->s3.',';    $total_qty=$total_qty+round($row1->s3); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s3[]" type="number" class="size_id" id="s3" value="'.$row1->s3.'" requiredreadonly /></td>';}
          if(isset($row1->s4)) {$SieQtyArray=$SieQtyArray.$row1->s4.',';    $total_qty=$total_qty+round($row1->s4); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s4[]" type="number" class="size_id" id="s4" value="'.$row1->s4.'" required readonly/></td>';}
          if(isset($row1->s5)) {$SieQtyArray=$SieQtyArray.$row1->s5.',';    $total_qty=$total_qty+round($row1->s5); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s5[]" type="number" class="size_id" id="s5" value="'.$row1->s5.'" required readonly/></td>';}
          if(isset($row1->s6)) {$SieQtyArray=$SieQtyArray.$row1->s6.',';    $total_qty=$total_qty+round($row1->s6); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"    name="s6[]" type="number" class="size_id" id="s6" value="'.$row1->s6.'" required readonly/></td>';}
          if(isset($row1->s7)) {$SieQtyArray=$SieQtyArray.$row1->s7.',';    $total_qty=$total_qty+round($row1->s7); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s7[]" type="number" class="size_id" id="s7" value="'.$row1->s7.'" required readonly/></td>';}
          if(isset($row1->s8)) {$SieQtyArray=$SieQtyArray.$row1->s8.',';    $total_qty=$total_qty+round($row1->s8); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s8[]" type="number" class="size_id" id="s8" value="'.$row1->s8.'" required readonly/></td>';}
          if(isset($row1->s9)) {$SieQtyArray=$SieQtyArray.$row1->s9.',';    $total_qty=$total_qty+round($row1->s9); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"    name="s9[]" type="number" class="size_id" id="s9" value="'.$row1->s9.'" required readonly/></td>';}
          if(isset($row1->s10)) {$SieQtyArray=$SieQtyArray.$row1->s10.',';    $total_qty=$total_qty+round($row1->s10); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s10[]" type="number" class="size_id" id="s10" value="'.$row1->s10.'" required readonly/></td>';}
          if(isset($row1->s11)) {$SieQtyArray=$SieQtyArray.$row1->s11.',';    $total_qty=$total_qty+round($row1->s11); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s11[]" type="number" class="size_id" id="s11" value="'.$row1->s11.'" required readonly /></td>';}
          if(isset($row1->s12)) {$SieQtyArray=$SieQtyArray.$row1->s12.',';    $total_qty=$total_qty+round($row1->s12); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s12[]" type="number" class="size_id" id="s12" value="'.$row1->s12.'" required readonly/></td>';}
          if(isset($row1->s13)) {$SieQtyArray=$SieQtyArray.$row1->s13.',';    $total_qty=$total_qty+round($row1->s13); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s13[]" type="number" class="size_id" id="s13" value="'.$row1->s13.'" required readonly/></td>';}
          if(isset($row1->s14)) {$SieQtyArray=$SieQtyArray.$row1->s14.',';    $total_qty=$total_qty+round($row1->s14); $html.='<td> <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s14[]" type="number" class="size_id" id="s14" value="'.$row1->s14.'" required readonly/></td>';}
          if(isset($row1->s15)) {$SieQtyArray=$SieQtyArray.$row1->s15.',';    $total_qty=$total_qty+round($row1->s15); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s15[]" type="number" class="size_id" id="s15" value="'.$row1->s15.'" required readonly/></td>';}
          if(isset($row1->s16)) {$SieQtyArray=$SieQtyArray.$row1->s16.',';    $total_qty=$total_qty+round($row1->s16); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s16[]" type="number" class="size_id" id="s16" value="'.$row1->s16.'" required readonly/></td>';}
          if(isset($row1->s17)) {$SieQtyArray=$SieQtyArray.$row1->s17.',';    $total_qty=$total_qty+round($row1->s17); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s17[]" type="number" class="size_id" id="s17" value="'.$row1->s17.'" required readonly/></td>';}
          if(isset($row1->s18)) {$SieQtyArray=$SieQtyArray.$row1->s18.',';    $total_qty=$total_qty+round($row1->s18); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s18[]" type="number" class="size_id" id="s18" value="'.$row1->s18.'" required readonly/></td>';}
          if(isset($row1->s19)) {$SieQtyArray=$SieQtyArray.$row1->s19.',';    $total_qty=$total_qty+round($row1->s19); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s19[]" type="number" class="size_id" id="s19" value="'.$row1->s19.'" required readonly/></td>';}
          if(isset($row1->s20)) {$SieQtyArray=$SieQtyArray.$row1->s20;        $total_qty=$total_qty+round($row1->s20); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s20[]" type="number" class="size_id" id="s20" value="'.$row1->s20.'" required readonly/></td>';}
       
         
        $SieQtyArray=rtrim($SieQtyArray,',');
          $html.='<td>  
          
          
          
          
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="'.$total_qty.'" id="size_qty_total" style="width:80px; height:30px; float:left; background-color:#cbe9dc;" readOnly  />
        <input type="hidden" name="size_qty_array[]"  value="'.$SieQtyArray.'" id="size_qty_array" style="width:80px; float:left;"  readOnly/>
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;" readOnly />
         
        <input type="hidden" name="is_transfered[]"   value="'.$is_transfered.'" id="is_transfered" style="width:80px; height:30px; float:left;"  readOnly  />
        <input type="hidden" name="is_rtv[]"   value="'.$is_rtv.'" id="is_rtv" style="width:80px; height:30px; float:left;"  readOnly  />
        <input type="hidden" name="trans_sales_order_no[]"  value="'.$row1->main_sales_order_no.'" id="trans_sales_order_no" style="width:80px; float:left;"  />
        <input type="hidden" name="transfer_code[]"  value="'.$row1->tpki_code.'" id="transfer_code" style="width:80px;  float:left;"  />
        
        </td></tr>';
            
            
        }
    }
    
    
        
        
        }
        
        
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
  
     public function PackingGRNReport1(Request $request)
    {  
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        
        if($fromDate == "" && $toDate == "")
        { 
            $fromDate = date("Y-m-01");
            $toDate = date("Y-m-d");
            echo "<script>location.href='PackingGRNReport1?fromDate=".$fromDate."&toDate=".$toDate."';</script>";
        }
        return view('PackingGRNReport1', compact('fromDate', 'toDate'));
    }
    
    public function LoadPackingGRNReport1(Request $request)
    {
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        
        ini_set('memory_limit', '10G');
        $DFilter = "";
        // if ($request->ajax()) 
        // { 
        if($fromDate != '' && $toDate != '')
        {
            $filterDate = " AND packing_inhouse_size_detail2.pki_date Between '".$fromDate."' AND '".$toDate."'";
        }
        else
        {
            if($DFilter == 'd')
            {
                $filterDate = " AND packing_inhouse_size_detail2.pki_date =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            }
            else if($DFilter == 'm')
            {
                $filterDate = ' AND MONTH(packing_inhouse_size_detail2.pki_date) = MONTH(CURRENT_DATE()) and YEAR(packing_inhouse_size_detail2.pki_date)=YEAR(CURRENT_DATE()) 
                AND packing_inhouse_size_detail2.pki_date !="'.date('Y-m-d').'"';
            }
            else if($DFilter == 'y')
            {
                $filterDate = ' AND packing_inhouse_size_detail2.pki_date between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)
                                AND packing_inhouse_size_detail2.pki_date !="'.date('Y-m-d').'"';
            }
            else
            {
                $filterDate = "";
            }
            
        }
        //DB::enableQueryLog();
            $MasterdataList = DB::select("SELECT buyer_purchse_order_master.order_rate,packing_inhouse_size_detail2.pki_code,sales_order_costing_master.total_cost_value, ifnull(packing_inhouse_size_detail2.vpo_code,'-') as vpo_code,
            ifnull(packing_inhouse_size_detail2.sales_order_no,'-') as sales_order_no,buyer_purchse_order_master.brand_id,packing_inhouse_size_detail2.substyle_id,
                packing_inhouse_size_detail2.pki_date, ifnull(mainstyle_name,'-') as mainstyle_name,sub_style_master.substyle_name as sub_style_name, 
                ifnull(packing_inhouse_size_detail2.style_no,buyer_purchse_order_master.style_no) as style_no,
                ifnull(packing_inhouse_size_detail2.item_code,0) as item_code,  ifnull(item_name,'-') as item_name,
                ifnull(quality_master.quality_name,'-') as quality_name ,
                packing_inhouse_size_detail2.color_id, ifnull(color_master.color_name,'-') as color_name,
                packing_inhouse_size_detail2.size_id, ifnull(size_name,'-') as size_name,
                ifnull(fg_name,'-') as fg_name, ifnull(job_status_name,'') as job_status_name,
                ifnull(sum(size_qty),0) as size_qty, LM1.ac_name as vendor_name, 
                ifnull(LM2.ac_name,'-') as buyer_name, ifnull(brand_master.brand_name,'-') as brand_name, 
                ifnull(buyer_purchse_order_master.sam,1) as sam from packing_inhouse_size_detail2 
                left join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_size_detail2.vpo_code
                left join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
                left join main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id
                left join sub_style_master on sub_style_master.substyle_id=packing_inhouse_size_detail2.substyle_id
                left join fg_master on fg_master.fg_id=packing_inhouse_size_detail2.fg_id 
                left join item_master on item_master.item_code=packing_inhouse_size_detail2.item_code 
                left join quality_master on quality_master.quality_code=item_master.quality_code
                left join color_master on color_master.color_id=packing_inhouse_size_detail2.color_id 
                left join ledger_master as LM1 on LM1.ac_code = packing_inhouse_size_detail2.vendorId 
                left join ledger_master as LM2 on LM2.ac_code = packing_inhouse_size_detail2.Ac_code 
                left join job_status_master on job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
                left join brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id 
                left join sales_order_costing_master  on sales_order_costing_master.sales_order_no = buyer_purchse_order_master.tr_code 
                left join size_detail on size_detail.size_id=packing_inhouse_size_detail2.size_id WHERE 1 ".$filterDate."
                group by packing_inhouse_size_detail2.pki_code,packing_inhouse_size_detail2.sales_order_no,
                packing_inhouse_size_detail2.color_id ,packing_inhouse_size_detail2.size_id");
    //   //dd(DB::getQueryLog());
    //         return Datatables::of($MasterdataList) 
    //         ->addColumn('fg_name',function ($row) 
    //         {
    //              $fg = $row->fg_name.'('.$row->style_no.')';
    //              return $fg;
    //         }) 
    //         ->addColumn('vpo_code',function ($row) 
    //         {
    //              $vc = isset($row->vpo_code) ? $row->vpo_code : "-";
    //              return $vc;
    //         }) 
    //         ->addColumn('job_status_name',function ($row) 
    //         {
    //              $jc = isset($row->job_status_name) ? $row->job_status_name : "-";
    //              return $jc;
    //         }) 
    //         ->addColumn('total_min',function ($row) 
    //         {
    //              $tm =  $row->size_qty * $row->sam;
    //              return $tm;
    //         })
    //         ->addColumn('quality_rate',function ($row) 
    //         {
    //             $finishingData = DB::SELECT("
    //                     SELECT finishing_rate 
    //                     FROM finishing_rate_details 
    //                     INNER JOIN finishing_rate_master 
    //                         ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
    //                     WHERE finishing_rate_master.brand_id = ? 
    //                       AND finishing_rate_master.substyle_id = ? 
    //                       AND finishing_rate_details.finishing_rate_date <= ? 
    //                     ORDER BY finishing_rate_details.finishing_rate_date DESC 
    //                     LIMIT 1", 
    //                     [$row->brand_id, $row->substyle_id, $row->pki_date]
    //                 );
                    
    //                 $quality_rate = $finishingData[0]->finishing_rate ?? 0;
                    
    //                 if ($quality_rate == 0) {
    //                     $finishingData = DB::SELECT("
    //                         SELECT finishing_rate 
    //                         FROM finishing_rate_details 
    //                         INNER JOIN finishing_rate_master 
    //                             ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
    //                         WHERE finishing_rate_master.brand_id = ? 
    //                           AND finishing_rate_master.substyle_id = ? 
    //                           AND finishing_rate_details.finishing_rate_date >= ? 
    //                         ORDER BY finishing_rate_details.finishing_rate_date ASC 
    //                         LIMIT 1", 
    //                         [$row->brand_id, $row->substyle_id, $row->pki_date]
    //                     );
                    
    //                     $quality_rate = $finishingData[0]->finishing_rate ?? 0;
    //                 }
                    
    //                 return $quality_rate;

    //         })
    //         ->addColumn('packing_rate',function ($row) 
    //         { 
    //               $finishingData = DB::SELECT("
    //                     SELECT packing_rate 
    //                     FROM finishing_rate_details 
    //                     INNER JOIN finishing_rate_master 
    //                         ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
    //                     WHERE finishing_rate_master.brand_id = ? 
    //                       AND finishing_rate_master.substyle_id = ? 
    //                       AND finishing_rate_details.finishing_rate_date <= ? 
    //                     ORDER BY finishing_rate_details.finishing_rate_date DESC 
    //                     LIMIT 1", 
    //                     [$row->brand_id, $row->substyle_id, $row->pki_date]
    //                 );
                    
    //                 $packing_rate = $finishingData[0]->packing_rate ?? 0;
                    
    //                 if ($packing_rate == 0) {
    //                     $finishingData = DB::SELECT("
    //                         SELECT packing_rate 
    //                         FROM finishing_rate_details 
    //                         INNER JOIN finishing_rate_master 
    //                             ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
    //                         WHERE finishing_rate_master.brand_id = ? 
    //                           AND finishing_rate_master.substyle_id = ? 
    //                           AND finishing_rate_details.finishing_rate_date >= ? 
    //                         ORDER BY finishing_rate_details.finishing_rate_date ASC 
    //                         LIMIT 1", 
    //                         [$row->brand_id, $row->substyle_id, $row->pki_date]
    //                     );
                    
    //                     $packing_rate = $finishingData[0]->packing_rate ?? 0;
    //                 }
                    
    //                 return $packing_rate;
                    
    //         })
    //         ->addColumn('kaj_button_rate',function ($row) 
    //         { 
    //               $finishingData = DB::SELECT("
    //                     SELECT kaj_button_rate 
    //                     FROM finishing_rate_details 
    //                     INNER JOIN finishing_rate_master 
    //                         ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
    //                     WHERE finishing_rate_master.brand_id = ? 
    //                       AND finishing_rate_master.substyle_id = ? 
    //                       AND finishing_rate_details.finishing_rate_date <= ? 
    //                     ORDER BY finishing_rate_details.finishing_rate_date DESC 
    //                     LIMIT 1", 
    //                     [$row->brand_id, $row->substyle_id, $row->pki_date]
    //                 );
                    
    //                 $kaj_button_rate = $finishingData[0]->kaj_button_rate ?? 0;
                    
    //                 if ($kaj_button_rate == 0) {
    //                     $finishingData = DB::SELECT("
    //                         SELECT kaj_button_rate 
    //                         FROM finishing_rate_details 
    //                         INNER JOIN finishing_rate_master 
    //                             ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
    //                         WHERE finishing_rate_master.brand_id = ? 
    //                           AND finishing_rate_master.substyle_id = ? 
    //                           AND finishing_rate_details.finishing_rate_date >= ? 
    //                         ORDER BY finishing_rate_details.finishing_rate_date ASC 
    //                         LIMIT 1", 
    //                         [$row->brand_id, $row->substyle_id, $row->pki_date]
    //                     );
                    
    //                     $kaj_button_rate = $finishingData[0]->kaj_button_rate ?? 0;
    //                 }
                    
    //                 return $kaj_button_rate;
    //         })
    //         ->addColumn('fob_rate',function ($row) 
    //         {
    //             if($row->total_cost_value == 0)
    //             { 
    //                  $fob_rate =  number_format($row->order_rate,4); 
    //             }
    //             else
    //             { 
    //                 $fob_rate = number_format($row->total_cost_value,4); 
    //             }  
    //             return $fob_rate;
    //         })
    //         ->addColumn('total_value',function ($row) 
    //         {
    //             if($row->total_cost_value == 0)
    //             { 
    //                  $fob_rate =  $row->order_rate; 
    //             }
    //             else
    //             { 
    //                 $fob_rate = $row->total_cost_value; 
    //             }  
                
    //             $total_value = $row->size_qty * $fob_rate;
                
    //             return number_format($total_value,2);
    //         })
    //          ->rawColumns(['fg_name','total_min','job_status_name','vpo_code','fob_rate','total_value','quality_rate','packing_rate','kaj_button_rate'])
             
    //          ->make(true);
    
    //         }
             
        $html = [];
        foreach($MasterdataList as $row)
        {
                $fg_name = $row->fg_name.'('.$row->style_no.')';
                $vpo_code = isset($row->vpo_code) ? $row->vpo_code : "-";
                $job_status_name = isset($row->job_status_name) ? $row->job_status_name : "-";
                $total_min =  $row->size_qty * $row->sam;
 
                $finishingData = DB::SELECT("
                        SELECT finishing_rate 
                        FROM finishing_rate_details 
                        INNER JOIN finishing_rate_master 
                            ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                        WHERE finishing_rate_master.brand_id = ? 
                          AND finishing_rate_master.substyle_id = ? 
                          AND finishing_rate_details.finishing_rate_date <= ? 
                        ORDER BY finishing_rate_details.finishing_rate_date DESC 
                        LIMIT 1", 
                        [$row->brand_id, $row->substyle_id, $row->pki_date]
                    );
                    
                $quality_rate = $finishingData[0]->finishing_rate ?? 0;
                
                if ($quality_rate == 0) {
                    $finishingData = DB::SELECT("
                        SELECT finishing_rate 
                        FROM finishing_rate_details 
                        INNER JOIN finishing_rate_master 
                            ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                        WHERE finishing_rate_master.brand_id = ? 
                          AND finishing_rate_master.substyle_id = ? 
                          AND finishing_rate_details.finishing_rate_date >= ? 
                        ORDER BY finishing_rate_details.finishing_rate_date ASC 
                        LIMIT 1", 
                        [$row->brand_id, $row->substyle_id, $row->pki_date]
                    );
                
                    $quality_rate = $finishingData[0]->finishing_rate ?? 0;
                }
                
                $finishingData = DB::SELECT("
                    SELECT packing_rate 
                    FROM finishing_rate_details 
                    INNER JOIN finishing_rate_master 
                        ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                    WHERE finishing_rate_master.brand_id = ? 
                      AND finishing_rate_master.substyle_id = ? 
                      AND finishing_rate_details.finishing_rate_date <= ? 
                    ORDER BY finishing_rate_details.finishing_rate_date DESC 
                    LIMIT 1", 
                    [$row->brand_id, $row->substyle_id, $row->pki_date]
                );
                
                $packing_rate = $finishingData[0]->packing_rate ?? 0;
                
                if ($packing_rate == 0) {
                    $finishingData = DB::SELECT("
                        SELECT packing_rate 
                        FROM finishing_rate_details 
                        INNER JOIN finishing_rate_master 
                            ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                        WHERE finishing_rate_master.brand_id = ? 
                          AND finishing_rate_master.substyle_id = ? 
                          AND finishing_rate_details.finishing_rate_date >= ? 
                        ORDER BY finishing_rate_details.finishing_rate_date ASC 
                        LIMIT 1", 
                        [$row->brand_id, $row->substyle_id, $row->pki_date]
                    );
                
                    $packing_rate = $finishingData[0]->packing_rate ?? 0;
                }
                  
                $finishingData = DB::SELECT("
                    SELECT kaj_button_rate 
                    FROM finishing_rate_details 
                    INNER JOIN finishing_rate_master 
                        ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                    WHERE finishing_rate_master.brand_id = ? 
                      AND finishing_rate_master.substyle_id = ? 
                      AND finishing_rate_details.finishing_rate_date <= ? 
                    ORDER BY finishing_rate_details.finishing_rate_date DESC 
                    LIMIT 1", 
                    [$row->brand_id, $row->substyle_id, $row->pki_date]
                );
                
                $kaj_button_rate = $finishingData[0]->kaj_button_rate ?? 0;
                
                if ($kaj_button_rate == 0) {
                    $finishingData = DB::SELECT("
                        SELECT kaj_button_rate 
                        FROM finishing_rate_details 
                        INNER JOIN finishing_rate_master 
                            ON finishing_rate_master.finishing_rate_code = finishing_rate_details.finishing_rate_code 
                        WHERE finishing_rate_master.brand_id = ? 
                          AND finishing_rate_master.substyle_id = ? 
                          AND finishing_rate_details.finishing_rate_date >= ? 
                        ORDER BY finishing_rate_details.finishing_rate_date ASC 
                        LIMIT 1", 
                        [$row->brand_id, $row->substyle_id, $row->pki_date]
                    );
                
                    $kaj_button_rate = $finishingData[0]->kaj_button_rate ?? 0;
                } 
                
                if($row->total_cost_value == 0)
                { 
                     $fob_rate =  number_format($row->order_rate,4); 
                }
                else
                { 
                    $fob_rate = number_format($row->total_cost_value,4); 
                } 
                
                if($row->total_cost_value == 0)
                { 
                     $fob_rate =  $row->order_rate; 
                }
                else
                { 
                    $fob_rate = $row->total_cost_value; 
                }  
                
                $total_value = $row->size_qty * $fob_rate;
                 
                
                $html[] =  array(
                       'pki_code'=>$row->pki_code,
                       'pki_date'=>$row->pki_date,
                       'vpo_code'=>$vpo_code,
                       'job_status_name'=>$job_status_name,
                       'mainstyle_name'=>$row->mainstyle_name,
                       'sales_order_no'=>$row->sales_order_no,
                       'sam'=>$row->sam,
                       'buyer_name'=>$row->buyer_name,
                       'brand_name'=>$row->brand_name,
                       'sub_style_name'=>$row->sub_style_name,
                       'item_name'=>$row->item_name,
                       'vendor_name'=>$row->vendor_name,
                       'fg_name'=>$fg_name,
                       'color_name'=>$row->color_name,
                       'size_name'=>$row->size_name,
                       'quality_rate'=>$quality_rate,
                       'packing_rate'=>$packing_rate,
                       'kaj_button_rate'=>$kaj_button_rate,
                       'size_qty'=>$row->size_qty,
                       'total_min'=>$total_min,
                       'fob_rate'=>$fob_rate,
                       'total_value'=>number_format($total_value,2)
                    );        
            // }
        }
        
        $jsonData = json_encode($html);
        return response()->json(['html' => $jsonData]);
        
    }
    
    
    
    // public function PackingGRNReport1()
    // {
    //     $DFilter = "";
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
    //     $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
    //     $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
    //     $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
    //     $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
    //  //  DB::enableQueryLog();  
    //     $VendorPurchaseOrderList = VendorPurchaseOrderModel::where('vendor_purchase_order_master.process_id','1')
    //   ->whereIn('vendor_purchase_order_master.sales_order_no', function($query){
    //     $query->select('buyer_purchse_order_master.tr_code as sales_order_no')->from('buyer_purchse_order_master')->where('buyer_purchse_order_master.job_status_id',1);
    //     })->distinct('vendor_purchase_order_master.sales_order_no')->get();
    // //   $query = DB::getQueryLog();
    // //     $query = end($query);
    // //     dd($query);
          
    //   return view('PackingGRNReport1',compact('VendorPurchaseOrderList','DFilter','ItemList',  'MainStyleList','SubStyleList','FGList', 'Ledger' ));
    // }  
    
    public function PackingGRNReport1MD(Request $request,$DFilter)
    {
        if ($request->ajax()) 
        { 
            if($DFilter == 'd')
            {
                $filterDate = " AND packing_inhouse_size_detail2.pki_date =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            }
            else if($DFilter == 'm')
            {
                $filterDate = ' AND MONTH(packing_inhouse_size_detail2.pki_date) = MONTH(CURRENT_DATE()) and YEAR(packing_inhouse_size_detail2.pki_date)=YEAR(CURRENT_DATE()) 
                AND packing_inhouse_size_detail2.pki_date !="'.date('Y-m-d').'"';
            }
            else if($DFilter == 'y')
            {
                $filterDate = ' AND packing_inhouse_size_detail2.pki_date between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=3) and (select tdate from financial_year_master where financial_year_master.fin_year_id=3)
                                AND packing_inhouse_size_detail2.pki_date !="'.date('Y-m-d').'"';
            }
            else
            {
                $filterDate = "";
            }
            //DB::enableQueryLog();
            $MasterdataList = DB::select("SELECT packing_inhouse_size_detail2.pki_code, packing_inhouse_size_detail2.vpo_code, packing_inhouse_size_detail2.sales_order_no,
                packing_inhouse_size_detail2.pki_date,mainstyle_name,
                packing_inhouse_size_detail2.style_no, packing_inhouse_size_detail2.item_code,item_name,quality_master.quality_name,
                packing_inhouse_size_detail2.color_id, color_master.color_name, packing_inhouse_size_detail2.size_id, size_name,fg_name,job_status_name,
                ifnull(sum(size_qty),0) as size_qty, ledger_master.ac_name as vendor_name  from packing_inhouse_size_detail2 
                left join vendor_purchase_order_master on vendor_purchase_order_master.vpo_code=packing_inhouse_size_detail2.vpo_code
                left join main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id
                left join fg_master on fg_master.fg_id=packing_inhouse_size_detail2.fg_id 
                left join item_master on item_master.item_code=packing_inhouse_size_detail2.item_code 
                left join quality_master on quality_master.quality_code=item_master.quality_code
                left join color_master on color_master.color_id=packing_inhouse_size_detail2.color_id 
                left join ledger_master on ledger_master.ac_code = packing_inhouse_size_detail2.vendorId 
                left join job_status_master on job_status_master.job_status_id=vendor_purchase_order_master.endflag
                left join size_detail on size_detail.size_id=packing_inhouse_size_detail2.size_id WHERE 1 ".$filterDate."
                group by packing_inhouse_size_detail2.pki_code,packing_inhouse_size_detail2.sales_order_no,
                packing_inhouse_size_detail2.color_id ,packing_inhouse_size_detail2.size_id");
            //dd(DB::getQueryLog());
            return Datatables::of($MasterdataList)
            
            ->addColumn('fg_name',function ($row) 
            {
                 $fg = $row->fg_name.'('.$row->style_no.')';
                 return $fg;
            })
             
            ->addColumn('vpo_code',function ($row) 
            {
                 $vc = isset($row->vpo_code) ? $row->vpo_code : "-";
                 return $vc;
            })
             
            ->addColumn('job_status_name',function ($row) 
            {
                 $jc = isset($row->job_status_name) ? $row->job_status_name : "-";
                 return $jc;
            })
             ->rawColumns(['fg_name'])
             
             ->make(true);
    
            }
            
          return view('PackingGRNReport1');
    }  
    
    
    
    
    
}
