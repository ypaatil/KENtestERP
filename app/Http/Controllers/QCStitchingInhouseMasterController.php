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
use App\Models\VendorWorkOrderModel;
use App\Models\VendorWorkOrderSizeDetailModel;
use App\Models\VendorWorkOrderDetailModel;
use App\Models\VendorWorkOrderFabricDetailModel;
use App\Models\VendorWorkOrderSewingTrimsDetailModel;
use App\Models\VendorWorkOrderPackingTrimsDetailModel;
use App\Models\BuyerPurchaseOrderDetailModel;
use App\Models\VendorWorkOrderTrimFabricDetailModel;
use App\Models\StitchingInhouseMasterModel;
use App\Models\StitchingInhouseDetailModel;
use App\Models\StitchingInhouseSizeDetailModel;
use App\Models\QCStitchingInhouseMasterModel;
use App\Models\QCStitchingInhouseDetailModel;
use App\Models\QCStitchingInhouseSizeDetailModel;  
use App\Models\QCStitchingInhouseSizeRejectDetailModel;  
use App\Models\QCStitchingInhouseRejectDetailModel;
use App\Models\SourceModel;
use App\Models\DestinationModel;
use Session;
use DataTables;
setlocale(LC_MONETARY, 'en_IN'); 
date_default_timezone_set('Asia/Calcutta');

class QCStitchingInhouseMasterController extends Controller
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
        ->where('form_id', '109')
        ->first();
        $vendorId=Session::get('vendorId');
        $user_type=Session::get('user_type');
        
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            if( $request->page == 1)
            {
                $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'qcstitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('qcstitching_inhouse_reject_detail', 'qcstitching_inhouse_reject_detail.qcsti_code', '=', 'qcstitching_inhouse_master.qcsti_code')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'qcstitching_inhouse_master.sales_order_no')
                ->where('qcstitching_inhouse_master.delflag','=', '0')
                ->groupBy('qcstitching_inhouse_master.qcsti_code')
                ->get(['qcstitching_inhouse_master.*','line_master.line_name','usermaster.username','L1.ac_short_name as Ac_name','buyer_purchse_order_master.sam','L2.ac_short_name as vendor_name',DB::raw('sum(qcstitching_inhouse_reject_detail.size_qty_total) as total_reject_qty')]);
            }
            else
            {
                $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'qcstitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('qcstitching_inhouse_reject_detail', 'qcstitching_inhouse_reject_detail.qcsti_code', '=', 'qcstitching_inhouse_master.qcsti_code')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'qcstitching_inhouse_master.sales_order_no')
                ->where('qcstitching_inhouse_master.delflag','=', '0')
                ->where('qcstitching_inhouse_master.qcsti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                ->groupBy('qcstitching_inhouse_master.qcsti_code')
                ->get(['qcstitching_inhouse_master.*','line_master.line_name','usermaster.username','L1.ac_short_name as Ac_name','buyer_purchse_order_master.sam','L2.ac_short_name as vendor_name',DB::raw('sum(qcstitching_inhouse_reject_detail.size_qty_total) as total_reject_qty')]);
            }
            
                if ($request->ajax()) 
                {
                    return Datatables::of($QCStitchingInhouseMasterList)
                    ->addIndexColumn()
                    ->addColumn('qcsti_code1',function ($row) {
                
                         $qcsti_codeData =substr($row->qcsti_code,4,15);
                
                         return $qcsti_codeData;
                    }) 
                    ->addColumn('total_pass_qty',function ($row) 
                    {
                         $total_pass_qty = $row->total_qty - $row->total_reject_qty;   
                         return $total_pass_qty;
                    }) 
                    ->addColumn('updated_at',function ($row) 
                    {
                         $updated_at = date("d-m-Y", strtotime($row->updated_at));   
                         return $updated_at;
                    }) 
                    ->addColumn('total_reject_qty',function ($row) {
                
                        // $rejectData = DB::SELECT("SELECT ifnull(sum(size_qty_total),0) as size_qty_total FROM qcstitching_inhouse_reject_detail WHERE qcsti_code='".$row->qcsti_code."'");
                         
                         $total_reject_qty = $row->total_reject_qty; 
                         return $total_reject_qty;
                    }) 
                    ->addColumn('total_value',function ($row) {
                
                         $total_value = $row->total_qty * $row->sam;   
                         return  round($total_value,2);
                    })  
                    ->addColumn('action1', function ($row) 
                    {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="QCStitchingGRNPrint/'.$row->qcsti_code.'" title="print">
                                    <i class="fas fa-print"></i>
                                    </a>';
                        return $btn1;
                    })
                    ->addColumn('action2', function ($row) use ($chekform)
                    {
                        if($chekform->edit_access==1)
                        {  
                            $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('QCStitchingInhouse.edit', $row->qcsti_code).'" >
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
                 
                        if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                        {      
                 
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->qcsti_code.'"  data-route="'.route('QCStitchingInhouse.destroy', $row->qcsti_code).'"><i class="fas fa-trash"></i></a>'; 
                        }  
                        else
                        {
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                       
                        }
                        return $btn4;
                    })
                    ->rawColumns(['action1','action2','action3','total_pass_qty','total_reject_qty', 'total_value','updated_at'])
                    ->make(true);
                }
        }
        else if(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {
            if( $request->page == 1)
            {
                $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
                     ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
                     ->join('line_master', 'line_master.line_id', '=', 'qcstitching_inhouse_master.line_id', 'left outer')
                     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'qcstitching_inhouse_master.sales_order_no')
                    ->where('qcstitching_inhouse_master.delflag','=', '0')->where( 'qcstitching_inhouse_master.vendorId',$vendorId)
                    ->get(['qcstitching_inhouse_master.*','line_master.line_name','buyer_purchse_order_master.sam','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name']);
            }
            else
            {
                 $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
                    ->join('line_master', 'line_master.line_id', '=', 'qcstitching_inhouse_master.line_id', 'left outer')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'qcstitching_inhouse_master.sales_order_no')
                    ->where('qcstitching_inhouse_master.delflag','=', '0')->where( 'qcstitching_inhouse_master.vendorId',$vendorId)
                    ->where('qcstitching_inhouse_master.qcsti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                    ->get(['qcstitching_inhouse_master.*','line_master.line_name','buyer_purchse_order_master.sam','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name']);
            }    
            
            if ($request->ajax()) 
            {
                return Datatables::of($QCStitchingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('qcsti_code1',function ($row) {
            
                     $qcsti_codeData =substr($row->qcsti_code,4,15);
            
                     return $qcsti_codeData;
                }) 
                ->addColumn('total_pass_qty',function ($row) {
            
                     $total_pass_qty = $row->total_qty;   
                     return $total_pass_qty;
                }) 
                ->addColumn('total_reject_qty',function ($row) {
            
                    // $rejectData = DB::SELECT("SELECT ifnull(sum(size_qty_total),0) as size_qty_total FROM qcstitching_inhouse_reject_detail WHERE qcsti_code='".$row->qcsti_code."'");
                     
                     $total_reject_qty = 0;   
                     return $total_reject_qty;
                }) 
                ->addColumn('total_value',function ($row) {
            
                     $total_value = $row->total_qty * $row->sam;   
                     return round($total_value,2);
                })   
                ->addColumn('updated_at',function ($row) 
                {
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));   
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="QCStitchingGRNPrint/'.$row->qcsti_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('QCStitchingInhouse.edit', $row->qcsti_code).'" >
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
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->qcsti_code.'"  data-route="'.route('QCStitchingInhouse.destroy', $row->qcsti_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['action1','action2','action3','total_pass_qty','total_reject_qty','total_value','updated_at'])
        
                ->make(true);
            }
        } 
        else
        {
            if( $request->page == 1)
            {
                $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
                     ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
                     ->join('line_master', 'line_master.line_id', '=', 'qcstitching_inhouse_master.line_id', 'left outer')
                     ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'qcstitching_inhouse_master.sales_order_no')
                    ->where('qcstitching_inhouse_master.delflag','=', '0')->where( 'qcstitching_inhouse_master.vendorId',$vendorId)
                    ->get(['qcstitching_inhouse_master.*','line_master.line_name','buyer_purchse_order_master.sam','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name']);
            }
            else
            {
                 $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
                    ->join('line_master', 'line_master.line_id', '=', 'qcstitching_inhouse_master.line_id', 'left outer')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'qcstitching_inhouse_master.sales_order_no')
                    ->where('qcstitching_inhouse_master.delflag','=', '0')->where( 'qcstitching_inhouse_master.vendorId',$vendorId)
                    ->where('qcstitching_inhouse_master.qcsti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                    ->get(['qcstitching_inhouse_master.*','line_master.line_name','buyer_purchse_order_master.sam','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name']);
            }    
            
            if ($request->ajax()) 
            {
                return Datatables::of($QCStitchingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('qcsti_code1',function ($row) {
            
                     $qcsti_codeData =substr($row->qcsti_code,4,15);
            
                     return $qcsti_codeData;
                }) 
                ->addColumn('total_pass_qty',function ($row) {
            
                     $total_pass_qty = $row->total_qty;   
                     return $total_pass_qty;
                }) 
                ->addColumn('total_reject_qty',function ($row) {
            
                    // $rejectData = DB::SELECT("SELECT ifnull(sum(size_qty_total),0) as size_qty_total FROM qcstitching_inhouse_reject_detail WHERE qcsti_code='".$row->qcsti_code."'");
                     
                     $total_reject_qty = 0;   
                     return $total_reject_qty;
                }) 
                ->addColumn('total_value',function ($row) {
            
                     $total_value = $row->total_qty * $row->sam;   
                     return round($total_value,2);
                })   
                ->addColumn('updated_at',function ($row) 
                {
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));   
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="QCStitchingGRNPrint/'.$row->qcsti_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('QCStitchingInhouse.edit', $row->qcsti_code).'" >
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
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->qcsti_code.'"  data-route="'.route('QCStitchingInhouse.destroy', $row->qcsti_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['action1','action2','action3','total_pass_qty','total_reject_qty','total_value','updated_at'])
        
                ->make(true);
            }
        } 
        return view('QCStitchingInhouseMasterList', compact('QCStitchingInhouseMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='QCStitchingInhouse'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $vendorId=Session::get('vendorId');
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
          $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->where('vendor_work_order_master.vendorId',$vendorId)->get();
          $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        } 
        else 
        {  
          $VendorWorkOrderList= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')->where('vendor_work_order_master.vendorId',$vendorId)->get();
          $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        } 
        return view('QCStitchingInhouseMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList','BuyerList', 'VendorWorkOrderList','Ledger',  'counter_number'));
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
        ->where('type','=','QcStitchingInhouse')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        $this->validate($request, [
                'qcsti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'vendorId'=>'required',
                'mainstyle_id'=>'required'
        ]);
     
        $data1=array(
            'qcsti_code'=>$TrNo, 
            'qcsti_date'=>$request->qcsti_date, 
            'sales_order_no'=>$request->sales_order_no,
            'Ac_code'=>$request->Ac_code, 
            'vendorId'=>$request->vendorId,
            'line_id'=>$request->line_id,
            'vw_code'=>$request->vw_code,
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
            'created_at'=>date("Y-m-d h:i:s")
        );
      
        $salesOrderData = DB::table('buyer_purchse_order_master')->select('sam')->where('tr_code','=',$request->sales_order_no)->first();
        $sam = isset($salesOrderData->sam) ? $salesOrderData->sam : 0;
        
        QCStitchingInhouseMasterModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='QCStitchingInhouse'");
    
        $color_id= $request->input('color_id');
        
        $total_pass_qty = 0;
        $total_reject_qty = 0;
        $total_minutes = 0;
        if(count($color_id)>0)
        {   
        
        for($x=0; $x<count($color_id); $x++) 
        {
            $overall_size_qty_total = $request->size_qty_total[$x] + $request->size_qty_total2[$x];
            if($overall_size_qty_total > 0)
            {
                    $data2=array(
              
                        'qcsti_code'=>$TrNo,
                        'qcsti_date'=>$request->qcsti_date,
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'line_id'=>$request->line_id,
                        'vw_code'=>$request->vw_code,
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'size_array'=>$request->size_array[$x],
                        'size_qty_array'=>$request->size_qty_array[$x],
                        'size_qty_total'=>$request->size_qty_total[$x]
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
                      
                            'qcsti_code'=>$TrNo, 
                            'qcsti_date'=>$request->qcsti_date, 
                            'sales_order_no'=>$request->sales_order_no,
                            'Ac_code'=>$request->Ac_code, 
                            'vendorId'=>$request->vendorId,
                            'line_id'=>$request->line_id,
                            'vw_code'=>$request->vw_code,
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
                            'vendor_rate'=>$request->vendor_rate
                              );
                        $total_pass_qty += $request->size_qty_total[$x];
                        
                        $total_minutes += $request->size_qty_total[$x] * $sam;
                        
                        QCStitchingInhouseDetailModel::insert($data2);
                        QCStitchingInhouseSizeDetailModel::insert($data3);
            } 
                  
            if($overall_size_qty_total > 0)
            {
                   
                     
                    $data5=array(
              
                        'qcsti_code'=>$TrNo,
                        'qcsti_date'=>$request->qcsti_date,
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'line_id'=>$request->line_id,
                        'vw_code'=>$request->vw_code,
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'size_array'=>$request->size_array[$x],
                        'size_qty_array'=>$request->size_qty_array2[$x],
                        'size_qty_total'=>$request->size_qty_total2[$x],
                       
                    );
     
                    $s_1=isset($request->s_1[$x]) ? $request->s_1[$x] : 0; $s_11=isset($request->s_11[$x]) ? $request->s_11[$x] : 0;
    			    $s_2=isset($request->s_2[$x]) ? $request->s_2[$x] : 0; $s_12=isset($request->s_12[$x]) ? $request->s_12[$x] : 0;
    			    $s_3=isset($request->s_3[$x]) ? $request->s_3[$x] : 0; $s_13=isset($request->s_13[$x]) ? $request->s_13[$x] : 0;
    			    $s_4=isset($request->s_4[$x]) ? $request->s_4[$x] : 0; $s_14=isset($request->s_14[$x]) ? $request->s_14[$x] : 0;
    			    $s_5=isset($request->s_5[$x]) ? $request->s_5[$x] : 0; $s_15=isset($request->s_15[$x]) ? $request->s_15[$x] : 0;
    			    $s_6=isset($request->s_6[$x]) ? $request->s_6[$x] : 0; $s_16=isset($request->s_16[$x]) ? $request->s_16[$x] : 0;
    			    $s_7=isset($request->s_7[$x]) ? $request->s_7[$x] : 0; $s_17=isset($request->s_17[$x]) ? $request->s_17[$x] : 0;
    			    $s_8=isset($request->s_8[$x]) ? $request->s_8[$x] : 0; $s_18=isset($request->s_18[$x]) ? $request->s_18[$x] : 0;
    			    $s_9=isset($request->s_9[$x]) ? $request->s_9[$x] : 0; $s_19=isset($request->s_19[$x]) ? $request->s_19[$x] : 0;
    			    $s_10=isset($request->s_10[$x]) ? $request->s_10[$x] : 0; $s_20=isset($request->s_20[$x]) ? $request->s_20[$x] : 0;
     
                    $data4=array(
                      
                            'qcsti_code'=>$TrNo, 
                            'qcsti_date'=>$request->qcsti_date, 
                            'sales_order_no'=>$request->sales_order_no,
                            'Ac_code'=>$request->Ac_code, 
                            'vendorId'=>$request->vendorId,
                            'line_id'=>$request->line_id,
                            'vw_code'=>$request->vw_code,
                            'mainstyle_id'=>$request->mainstyle_id,
                            'substyle_id'=>$request->substyle_id,
                            'fg_id'=>$request->fg_id,
                            'style_no'=>$request->s_tyle_no,
                            'style_description'=>$request->style_description,
                            'item_code'=>$request->item_codef[$x],
                            'color_id'=>$request->color_id[$x],
                            'size_array'=>$request->size_array[$x],
                            's1'=>$s_1,
                            's2'=>$s_2,
                            's3'=>$s_3,
                            's4'=>$s_4,
                            's5'=>$s_5,
                            's6'=>$s_6,
                            's7'=>$s_7,
                            's8'=>$s_8,
                            's9'=>$s_9,
                            's10'=>$s_10,
                            's11'=>$s_11,
                            's12'=>$s_12,
                            's13'=>$s_13,
                            's14'=>$s_14,
                            's15'=>$s_15,
                            's16'=>$s_16,
                            's17'=>$s_17,
                            's18'=>$s_18,
                            's19'=>$s_19,
                            's20'=>$s_20,
                            'size_qty_total'=>$request->size_qty_total2[$x],
                            'vendor_rate'=>$request->vendor_rate
                        );
                        
                        $total_reject_qty += $request->size_qty_total2[$x];
                        $total_minutes += $request->size_qty_total2[$x] * $sam;
                        
                        QCStitchingInhouseRejectDetailModel::insert($data5);
                        QCStitchingInhouseSizeRejectDetailModel::insert($data4);   
                  } 
                  
                }
        }
        
        $InsertSizeData=DB::select("call AddSizeQtyFromQCStitchingInhouse('".$TrNo."')");
         
        $defectData = DB::SELECT("SELECT sum(defect_qty) as total_defect FROM dhu_details INNER JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code WHERE dhu_master.vw_code='".$request->vw_code."' AND dhu_master.line_no=".$request->line_id." AND dhu_master.fg_id=".$request->fg_id." AND dhu_master.vendorId=".$request->vendorId);
        
        $totalPass = $total_pass_qty;
        $totalReject = $total_reject_qty;
        $totalProduction = $totalPass + $totalReject;
        $totalDefect = isset($defectData[0]->total_defect) ? $defectData[0]->total_defect : 0;
        
        if($total_minutes > 0 && $totalProduction > 0)
        { 
            $avgSAM = ($total_minutes/$totalProduction);
        }
        else
        {
            $avgSAM = 0;
        }
        
        $anotherDatabaseMasterArr=array( 
            'productionDate'=>$request->qcsti_date, 
            'branch_id'=>$request->vendorId,
            'userId'=>$request->userId, 
            'delflag'=>0, 
        );
        $anotherDatabaseDetailArr=array(
               
            'productionDate'=>$request->qcsti_date,
            'sam'=>$avgSAM, 
            'pass'=>$totalPass, 
            'reject'=>$totalReject, 
            'totalProduction'=>$totalProduction,
            'defect'=>$totalDefect, 
            'branch_id'=>$request->vendorId,
            'deptCostId'=>$request->line_id, 
            'userId'=>$request->userId, 
            'mainstyle_id'=>$request->mainstyle_id
        );
        DB::connection('hrms_database')->table('production_master')->insert((array)$anotherDatabaseMasterArr);
        $proId = DB::connection('hrms_database')->table('production_master')->select('proId')->max('proId');
        
        $anotherDatabaseDetailArr=array(
            'proId' =>$proId,
            'productionDate'=>$request->qcsti_date,
            'sam'=>$avgSAM, 
            'pass'=>$totalPass, 
            'reject'=>$totalReject, 
            'totalProduction'=>$totalProduction,
            'defect'=>$totalDefect, 
            'branch_id'=>$request->vendorId,
            'deptCostId'=>$request->line_id, 
            'userId'=>$request->userId, 
            'mainstyle_id'=>$request->mainstyle_id, 
            'qcsti_code'=>$TrNo, 
            'sti_type'=>2,  
        );
        DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr); 
        SourceModel::on('mysql');
        return redirect()->route('QCStitchingInhouse.index')->with('message', 'Data Saved Succesfully');  
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
  
        return view('budgetPrint', compact('BOMList'));  
      
    }

    public function VPPrint($vpo_code)
    {
       $BOMList = VendorPurchaseOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_purchase_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_purchase_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_purchase_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_purchase_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_purchase_order_master.fg_id', 'left outer')   
        ->where('vendor_purchase_order_master.delflag','=', '0')
        ->where('vendor_purchase_order_master.vpo_code','=', $vpo_code)
        ->get(['vendor_purchase_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('cuttingOrderPrint', compact('BOMList'));     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
    
        $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::find($id);
        
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$QCStitchingInhouseMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$QCStitchingInhouseMasterList->sales_order_no)->DISTINCT()->get();
        //DB::enableQueryLog();
        $QCStitchingInhouseDetailList =QCStitchingInhouseDetailModel::where('qcstitching_inhouse_detail.qcsti_code','=', $QCStitchingInhouseMasterList->qcsti_code)->get();
        //dd(DB::getQueryLog());
        $QCStitchingInhouseRejectDetailList =QCStitchingInhouseRejectDetailModel::where('qcstitching_inhouse_reject_detail.qcsti_code','=', $QCStitchingInhouseMasterList->qcsti_code)->get();
     
        
        $vendorId=Session::get('vendorId');
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
                 $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
        ->whereNotIn('vendor_work_order_master.vw_code',function($query){
        $query->select('qcstitching_inhouse_master.vw_code')->from('qcstitching_inhouse_master');
        });
                $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
            
            $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_work_order_master.vw_code',function($query){
            $query->select('qcstitching_inhouse_master.vw_code')->from('qcstitching_inhouse_master');
            });
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
        else
        {  
            
            $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_work_order_master.vw_code',function($query){
            $query->select('qcstitching_inhouse_master.vw_code')->from('qcstitching_inhouse_master');
            });
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
         
        $S2=QCStitchingInhouseMasterModel::select('vw_code','sales_order_no')->where('vw_code',$QCStitchingInhouseMasterList->vw_code);
        $VendorWorkOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($QCStitchingInhouseMasterList->sales_order_no);
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
        $MasterdataList = DB::select("SELECT vendor_work_order_size_detail.item_code, vendor_work_order_size_detail.color_id, color_name, ".$sizes.", 
          sum(size_qty_total) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
          color_master.color_id=vendor_work_order_size_detail.color_id where vw_code='".$QCStitchingInhouseMasterList->vw_code."'
          group by vendor_work_order_size_detail.color_id");
        
        $LineList = DB::table('line_master')->select('line_master.line_id','line_name')->where('Ac_code','=',$QCStitchingInhouseMasterList->vendorId)->DISTINCT()->get();
     
        return view('QCStitchingInhouseMasterEdit',compact('LineList','QCStitchingInhouseDetailList','QCStitchingInhouseRejectDetailList','ColorList' ,'BuyerList',  'MasterdataList','SizeDetailList','QCStitchingInhouseMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger' ));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $qcsti_code)
    {
          $this->validate($request, [
                     
                        'qcsti_date'=> 'required', 
                        'Ac_code'=> 'required', 
                        'sales_order_no'=> 'required', 
                       
            ]);
         
              
    $data1=array(
               
            'qcsti_code'=>$qcsti_code, 
            'qcsti_date'=>$request->qcsti_date, 
            'sales_order_no'=>$request->sales_order_no,
            'Ac_code'=>$request->Ac_code, 
            'vendorId'=>$request->vendorId,
            'line_id'=>$request->line_id,
            'vw_code'=>$request->vw_code,
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
            'updated_at'=>date("Y-m-d h:i:s")
             
        );
    //   DB::enableQueryLog();   
    $QCStitchingInhouseList = QCStitchingInhouseMasterModel::findOrFail($qcsti_code); 
    //  $query = DB::getQueryLog();
    //         $query = end($query);
    //         dd($query);
    //DB::enableQueryLog();
    $QCStitchingInhouseList->fill($data1)->save();
    //dd(DB::getQueryLog());
    
    $salesOrderData = DB::table('buyer_purchse_order_master')->select('sam')->where('tr_code','=',$request->sales_order_no)->first();
    $sam = isset($salesOrderData->sam) ? $salesOrderData->sam : 0;
    
    $total_pass_qty = 0;
    $total_reject_qty = 0;
    $total_minutes = 0;

 
   $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
        DB::table('qcstitching_inhouse_size_detail')->where('qcsti_code', $qcsti_code)->delete();
        DB::table('qcstitching_inhouse_reject_detail')->where('qcsti_code', $qcsti_code)->delete();
        DB::table('qcstitching_inhouse_size_reject_detail')->where('qcsti_code', $qcsti_code)->delete();
        DB::table('qcstitching_inhouse_size_detail2')->where('qcsti_code', $qcsti_code)->delete();
        DB::table('qcstitching_inhouse_detail')->where('qcsti_code', $qcsti_code)->delete();
        
        for($x=0; $x<count($color_id); $x++) 
        {
            //   if($request->size_qty_total[$x]>0)
            //   {
                    $data2 =array(
          
                    'qcsti_code'=>$qcsti_code,
                    'qcsti_date'=>$request->qcsti_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'line_id'=>$request->line_id,
                    'vw_code'=>$request->vw_code,
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
                   
                 );
                 
                  $data5 =array(
          
                    'qcsti_code'=>$qcsti_code,
                    'qcsti_date'=>$request->qcsti_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'line_id'=>$request->line_id,
                    'vw_code'=>$request->vw_code,
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'item_code'=>$request->item_codef[$x],
                    'color_id'=>$request->color_id[$x],
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array2[$x],
                    'size_qty_total'=>$request->size_qty_total2[$x],
                   
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
 
 
                      $data3 =array(
                  
                        'qcsti_code'=>$qcsti_code, 
                        'qcsti_date'=>$request->qcsti_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'line_id'=>$request->line_id,
                        'vw_code'=>$request->vw_code,
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
                        'vendor_rate'=>$request->vendor_rate
                          );
                                   
                      $s_1=isset($request->s_1[$x]) ? $request->s_1[$x] : 0; $s_11=isset($request->s_11[$x]) ? $request->s_11[$x] : 0;
        			  $s_2=isset($request->s_2[$x]) ? $request->s_2[$x] : 0; $s_12=isset($request->s_12[$x]) ? $request->s_12[$x] : 0;
        			  $s_3=isset($request->s_3[$x]) ? $request->s_3[$x] : 0; $s_13=isset($request->s_13[$x]) ? $request->s_13[$x] : 0;
        			  $s_4=isset($request->s_4[$x]) ? $request->s_4[$x] : 0; $s_14=isset($request->s_14[$x]) ? $request->s_14[$x] : 0;
        			  $s_5=isset($request->s_5[$x]) ? $request->s_5[$x] : 0; $s_15=isset($request->s_15[$x]) ? $request->s_15[$x] : 0;
        			  $s_6=isset($request->s_6[$x]) ? $request->s_6[$x] : 0; $s_16=isset($request->s_16[$x]) ? $request->s_16[$x] : 0;
        			  $s_7=isset($request->s_7[$x]) ? $request->s_7[$x] : 0; $s_17=isset($request->s_17[$x]) ? $request->s_17[$x] : 0;
        			  $s_8=isset($request->s_8[$x]) ? $request->s_8[$x] : 0; $s_18=isset($request->s_18[$x]) ? $request->s_18[$x] : 0;
        			  $s_9=isset($request->s_9[$x]) ? $request->s_9[$x] : 0; $s_19=isset($request->s_19[$x]) ? $request->s_19[$x] : 0;
        			  $s_10=isset($request->s_10[$x]) ? $request->s_10[$x] : 0; $s_20=isset($request->s_20[$x]) ? $request->s_20[$x] : 0;
   
                      $data4 =array(
                  
                        'qcsti_code'=>$qcsti_code, 
                        'qcsti_date'=>$request->qcsti_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'line_id'=>$request->line_id,
                        'vw_code'=>$request->vw_code,
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->s_tyle_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'size_array'=>$request->size_array[$x],
                        's1'=>$s_1,
                        's2'=>$s_2,
                        's3'=>$s_3,
                        's4'=>$s_4,
                        's5'=>$s_5,
                        's6'=>$s_6,
                        's7'=>$s_7,
                        's8'=>$s_8,
                        's9'=>$s_9,
                        's10'=>$s_10,
                        's11'=>$s_11,
                        's12'=>$s_12,
                        's13'=>$s_13,
                        's14'=>$s_14,
                        's15'=>$s_15,
                        's16'=>$s_16,
                        's17'=>$s_17,
                        's18'=>$s_18,
                        's19'=>$s_19,
                        's20'=>$s_20,
                        'size_qty_total'=>$request->size_qty_total2[$x],
                        'vendor_rate'=>$request->vendor_rate
                          );
                          
                          $total_pass_qty += $request->size_qty_total[$x]; 
                          $total_minutes += $request->size_qty_total[$x] * $sam;
                          
                          $total_reject_qty += $request->size_qty_total2[$x];
                          $total_minutes += $request->size_qty_total2[$x] * $sam;
                    
                    
                          QCStitchingInhouseDetailModel::insert($data2);
                          QCStitchingInhouseRejectDetailModel::insert($data5);
                          QCStitchingInhouseSizeDetailModel::insert($data3);
                          QCStitchingInhouseSizeRejectDetailModel::insert($data4);  
                          
              
              }
            // }
     
         
    }
           
           
    $InsertSizeData=DB::select('call AddSizeQtyFromQCStitchingInhouse("'.$request->qcsti_code.'")');
           
    $defectData = DB::SELECT("SELECT sum(defect_qty) as total_defect FROM dhu_details INNER JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code WHERE dhu_master.vw_code='".$request->vw_code."' AND dhu_master.line_no=".$request->line_id." AND dhu_master.fg_id=".$request->fg_id." AND dhu_master.vendorId=".$request->vendorId);
    
    $totalPass = $total_pass_qty;
    $totalReject = $total_reject_qty;
    $totalProduction = $totalPass + $totalReject;
    $totalDefect = isset($defectData[0]->total_defect) ? $defectData[0]->total_defect : 0;
    
    if($total_minutes > 0 && $totalProduction > 0)
    { 
        $avgSAM = ($total_minutes/$totalProduction);
    }
    else
    {
        $avgSAM = 0;
    }
    
    $productionData = DB::connection('hrms_database')->table('production_master')->select('proId','productionDate','branch_id','userId')->where('productionDate','=',$request->qcsti_date)->where('branch_id','=',$request->vendorId)->get();
      
    if(count($productionData) == 0)
    {
        $anotherDatabaseMasterArr=array( 
            'productionDate'=>$request->qcsti_date, 
            'branch_id'=>$request->vendorId,
            'userId'=>$request->userId, 
            'delflag'=>0, 
        );
        DB::connection('hrms_database')->table('production_master')->insert((array)$anotherDatabaseMasterArr);
        $proId = DB::connection('hrms_database')->table('production_master')->select('proId')->max('proId');
        
        $anotherDatabaseDetailArr=array(
            'proId' =>$proId,
            'productionDate'=>$request->qcsti_date,
            'sam'=>$avgSAM, 
            'pass'=>$totalPass, 
            'reject'=>$totalReject, 
            'totalProduction'=>$totalProduction,
            'defect'=>$totalDefect, 
            'branch_id'=>$request->vendorId,
            'deptCostId'=>$request->line_id, 
            'userId'=>$request->userId, 
            'mainstyle_id'=>$request->mainstyle_id, 
            'qcsti_code'=>$qcsti_code, 
            'sti_type'=>2
        );
        DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr); 
        SourceModel::on('mysql');     
    }
    else
    {
        
        $productionMasterData = DB::connection('hrms_database')->table('production_master')->select('proId','productionDate','branch_id','userId')->where('productionDate','=',$request->qcsti_date)->where('branch_id','=',$request->vendorId)->first();
      
        DB::connection('hrms_database')->table('production_detail')->where('branch_id','=',$request->vendorId)->where('qcsti_code','=',$qcsti_code)->delete(); 
        $anotherDatabaseDetailArr=array(
            'proId' =>$productionMasterData->proId,
            'productionDate'=>$request->qcsti_date,
            'sam'=>$avgSAM, 
            'pass'=>$totalPass, 
            'reject'=>$totalReject, 
            'totalProduction'=>$totalProduction,
            'defect'=>$totalDefect, 
            'branch_id'=>$request->vendorId,
            'deptCostId'=>$request->line_id, 
            'userId'=>$request->userId, 
            'mainstyle_id'=>$request->mainstyle_id,
            'qcsti_code'=>$qcsti_code,  
            'sti_type'=>2
        );
        //echo '<pre>'; print_r($anotherDatabaseDetailArr);exit;
        DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr);
        //DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr); 
        SourceModel::on('mysql');
    }
    
        
           
     return redirect()->route('QCStitchingInhouse.index')->with('message', 'Data Saved Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
       
   public function getStitchingInhouseDetails(Request $request)
    { 
        $vw_code= $request->input('vw_code');
        $MasterdataList = DB::select("select Ac_code,sales_order_no, vendorId, mainstyle_id, substyle_id, fg_id, style_no, vendorRate as order_rate, style_description from vendor_work_order_master where vendor_work_order_master.delflag=0 and vw_code='".$vw_code."'");
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

  
 public function STI_GetOrderQty(Request $request)
  {
      // VW_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by VW_
      
      $VendorWorkOrderMasterList = VendorWorkOrderModel::find($request->vw_code);
    //   DB::enableQueryLog();  
    //print_r($request->vw_code);
      $VendorWorkOrderDetailList = VendorWorkOrderDetailModel::where('vw_code',$request->vw_code)->first();
    //  DB::enableQueryLog(); 
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$VendorWorkOrderMasterList->sales_order_no)->first();
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     $ColorList = DB::table('vendor_work_order_detail')->select('vendor_work_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'vendor_work_order_detail.color_id', 'left outer')
        ->where('vw_code','=',$request->vw_code)->DISTINCT()->get();
      
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
             
      $MasterdataList = DB::select("SELECT stitching_inhouse_size_detail.item_code, stitching_inhouse_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
      color_master.color_id=stitching_inhouse_size_detail.color_id where vw_code='".$request->vw_code."'
      group by stitching_inhouse_size_detail.color_id");
       

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
      
              $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
      //DB::enableQueryLog();  
        $List = DB::select("SELECT qcstitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from qcstitching_inhouse_size_detail inner join color_master on 
          color_master.color_id=qcstitching_inhouse_size_detail.color_id where 
          qcstitching_inhouse_size_detail.vw_code='".$request->vw_code."' and
          qcstitching_inhouse_size_detail.color_id='".$row->color_id."'");
         // dd(DB::getQueryLog());
       //DB::enableQueryLog();
        $List1 = DB::select("SELECT qcstitching_inhouse_size_reject_detail.color_id, color_name, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from qcstitching_inhouse_size_reject_detail inner join color_master on 
          color_master.color_id=qcstitching_inhouse_size_reject_detail.color_id where 
          qcstitching_inhouse_size_reject_detail.vw_code='".$request->vw_code."' and
          qcstitching_inhouse_size_reject_detail.color_id='".$row->color_id."'");
       //dd(DB::getQueryLog());
       
       if(isset($row->s1)) { $s1=((intval($row->s1))-(intval($List[0]->s_1)) - (intval($List1[0]->s_1))); }
       if(isset($row->s2)) { $s2=((intval($row->s2))-(intval($List[0]->s_2)) - (intval($List1[0]->s_2))); }
       if(isset($row->s3)) { $s3=((intval($row->s3))-(intval($List[0]->s_3)) - (intval($List1[0]->s_3))); }
       if(isset($row->s4)) { $s4=((intval($row->s4))-(intval($List[0]->s_4)) - (intval($List1[0]->s_4))); }
       if(isset($row->s5)) { $s5=((intval($row->s5))-(intval($List[0]->s_5)) - (intval($List1[0]->s_5))); }
       if(isset($row->s6)) { $s6=((intval($row->s6))-(intval($List[0]->s_6)) - (intval($List1[0]->s_6))); }
       if(isset($row->s7)) { $s7=((intval($row->s7))-(intval($List[0]->s_7)) - (intval($List1[0]->s_7)));}
       if(isset($row->s8)) { $s8=((intval($row->s8))-(intval($List[0]->s_8)) - (intval($List1[0]->s_8)));}
       if(isset($row->s9)) { $s9=((intval($row->s9))-(intval($List[0]->s_9)) - (intval($List1[0]->s_9)));}
       if(isset($row->s10)) { $s10=((intval($row->s10))-(intval($List[0]->s_10)) - (intval($List1[0]->s_10)));}
       if(isset($row->s11)) { $s11=((intval($row->s11))-(intval($List[0]->s_11)) - (intval($List1[0]->s_11)));}
       if(isset($row->s12)) { $s12=((intval($row->s12))-(intval($List[0]->s_12)) - (intval($List1[0]->s_12)));}
       if(isset($row->s13)) { $s13=((intval($row->s13))-(intval($List[0]->s_13)) - (intval($List1[0]->s_13)));}
       if(isset($row->s14)) { $s14=((intval($row->s14))-(intval($List[0]->s_14)) - (intval($List1[0]->s_14)));}
       if(isset($row->s15)) { $s15=((intval($row->s15))-(intval($List[0]->s_15)) - (intval($List1[0]->s_15)));}
       if(isset($row->s16)) {$s16=((intval($row->s16))-(intval($List[0]->s_16)) - (intval($List1[0]->s_16)));}
       if(isset($row->s17)) { $s17=((intval($row->s17))-(intval($List[0]->s_17)) - (intval($List1[0]->s_17)));}
       if(isset($row->s18)) { $s18=((intval($row->s18))-(intval($List[0]->s_18)) - (intval($List1[0]->s_18)));}
       if(isset($row->s19)) { $s19=((intval($row->s19))-(intval($List[0]->s_19)) - (intval($List1[0]->s_19)));}
       if(isset($row->s20)) { $s20=((intval($row->s20))-(intval($List[0]->s_20)) - (intval($List1[0]->s_20)));}
    
  //**********************Safe for 6 month to enable min and max restriction***********
        //   if(isset($row->s1)) { $html.='<td >  <input style="width:80px; padding:2px; class="form-control" " max="'.$s1.'" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required />'.$s1.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s1.'" min="0" name="s_1[]" class="size_id2" type="number" id="s_1" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s2)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s2.'" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required />'.$s2.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s2.'" min="0" name="s_2[]" class="size_id2" type="number" id="s_2" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s3)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s3.'" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required />'.$s3.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s3.'" min="0" name="s_3[]" class="size_id2" type="number" id="s_3" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s4)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s4.'" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required />'.$s4.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s4.'" min="0" name="s_4[]" class="size_id2" type="number" id="s_4" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s5)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s5.'" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required />'.$s5.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s5.'" min="0" name="s_5[]" class="size_id2" type="number" id="s_5" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s6)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s6.'" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required />'.$s6.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s6.'" min="0" name="s_6[]" class="size_id2" type="number" id="s_6" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s7)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s7.'" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required />'.$s7.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s7.'" min="0" name="s_7[]" class="size_id2" type="number" id="s_7" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s8)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s8.'" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required />'.$s8.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s8.'" min="0" name="s_8[]" class="size_id2" type="number" id="s_8" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s9)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s9.'" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required />'.$s9.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s9.'" min="0" name="s_9[]" class="size_id2" type="number" id="s_9" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s10)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s10.'" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required />'.$s10.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s10.'" min="0" name="s_10[]" class="size_id2" type="number" id="s_10" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s11)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s11.'" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required />'.$s11.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s11.'" min="0" name="s_11[]" class="size_id2" type="number" id="s_11" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s12)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s12.'" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required />'.$s12.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s12.'" min="0" name="s_12[]" class="size_id2" type="number" id="s_12" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s13)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s13.'" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required />'.$s13.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s13.'" min="0" name="s_13[]" class="size_id2" type="number" id="s_13" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s14)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s14.'" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required />'.$s14.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s14.'" min="0" name="s_14[]" class="size_id2" type="number" id="s_14" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s15)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s15.'" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required />'.$s15.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s15.'" min="0" name="s_15[]" class="size_id2" type="number" id="s_15" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s16)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s16.'" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required />'.$s16.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s16.'" min="0" name="s_16[]" class="size_id2" type="number" id="s_16" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s17)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s17.'" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required />'.$s17.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s17.'" min="0" name="s_17[]" class="size_id2" type="number" id="s_17" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s18)) { $html.='<td>  <input style="width:80px; padding:2px; " max="'.$s18.'" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required />'.$s18.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s18.'" min="0" name="s_18[]" class="size_id2" type="number" id="s_18" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s19)) { $html.='<td> <input style="width:80px; padding:2px; " max="'.$s19.'" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required />'.$s19.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s19.'" min="0" name="s_19[]" class="size_id2" type="number" id="s_19" value="" placeholder="Rejected" required /> </td>';}
        //   if(isset($row->s20)) { $html.='<td>   <input style="width:80px; padding:2px; " max="'.$s20.'" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required />'.$s20.'<input style="width:80px; padding:2px; background-color:#FAACAA; " max="'.$s20.'" min="0" name="s_20[]" class="size_id2" type="number" id="s_20" value="" placeholder="Rejected" required /> </td>';}
   //**************************** End *********************************** 
          
          if(isset($row->s1)) { $html.='<td >  <input style="width:80px; padding:2px;"     name="s1[]"  type="number" class="size_id" id="s1" value="0" required />'.$s1.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_1[]" class="size_id2" type="number" id="s_1" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s2)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s2[]" type="number" class="size_id" id="s2" value="0" required />'.$s2.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_2[]" class="size_id2" type="number" id="s_2" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s3)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s3[]" type="number" class="size_id" id="s3" value="0" required />'.$s3.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_3[]" class="size_id2" type="number" id="s_3" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s4)) { $html.='<td>   <input style="width:80px; padding:2px; "  name="s4[]" type="number" class="size_id" id="s4" value="0" required />'.$s4.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_4[]" class="size_id2" type="number" id="s_4" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s5)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s5[]" type="number" class="size_id" id="s5" value="0" required />'.$s5.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_5[]" class="size_id2" type="number" id="s_5" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s6)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s6[]" type="number" class="size_id" id="s6" value="0" required />'.$s6.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_6[]" class="size_id2" type="number" id="s_6" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s7)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s7[]" type="number" class="size_id" id="s7" value="0" required />'.$s7.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_7[]" class="size_id2" type="number" id="s_7" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s8)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s8[]" type="number" class="size_id" id="s8" value="0" required />'.$s8.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_8[]" class="size_id2" type="number" id="s_8" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s9)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s9[]" type="number" class="size_id" id="s9" value="0" required />'.$s9.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_9[]" class="size_id2" type="number" id="s_9" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s10)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s10[]" type="number" class="size_id" id="s10" value="0" required />'.$s10.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_10[]" class="size_id2" type="number" id="s_10" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s11)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s11[]" type="number" class="size_id" id="s11" value="0" required />'.$s11.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_11[]" class="size_id2" type="number" id="s_11" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s12)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s12[]" type="number" class="size_id" id="s12" value="0" required />'.$s12.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_12[]" class="size_id2" type="number" id="s_12" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s13)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s13[]" type="number" class="size_id" id="s13" value="0" required />'.$s13.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_13[]" class="size_id2" type="number" id="s_13" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s14)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s14[]" type="number" class="size_id" id="s14" value="0" required />'.$s14.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_14[]" class="size_id2" type="number" id="s_14" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s15)) { $html.='<td>  <input style="width:80px; padding:2px; " name="s15[]" type="number" class="size_id" id="s15" value="0" required />'.$s15.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_15[]" class="size_id2" type="number" id="s_15" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s16)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s16[]" type="number" class="size_id" id="s16" value="0" required />'.$s16.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_16[]" class="size_id2" type="number" id="s_16" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s17)) { $html.='<td>   <input style="width:80px; padding:2px; "  name="s17[]" type="number" class="size_id" id="s17" value="0" required />'.$s17.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_17[]" class="size_id2" type="number" id="s_17" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s18)) { $html.='<td>  <input style="width:80px; padding:2px; "  name="s18[]" type="number" class="size_id" id="s18" value="0" required />'.$s18.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_18[]" class="size_id2" type="number" id="s_18" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s19)) { $html.='<td> <input style="width:80px; padding:2px; "   name="s19[]" type="number" class="size_id" id="s19" value="0" required />'.$s19.'<input style="width:80px; padding:2px; background-color:#FAACAA;"   name="s_19[]" class="size_id2" type="number" id="s_19" value="0" placeholder="Rejected" required /> </td>';}
          if(isset($row->s20)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s20[]" type="number" class="size_id" id="s20" value="0" required />'.$s20.'<input style="width:80px; padding:2px; background-color:#FAACAA; "   name="s_20[]" class="size_id2" type="number" id="s_20" value="0" placeholder="Rejected" required /> </td>';}
          
          
          
          $allTotal = $row->size_qty_total - $List[0]->size_qty_total - $List1[0]->size_qty_total;
          
          $html.='<td>'.($allTotal).' 
             
          <input type="hidden" name="overall_size_qty"  value="'.($allTotal).'" class="overall_size_qty" style="width:80px; float:left;"  />
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
          <input type="number" name="size_qty_total2[]" class="size_qty_total2" value="0" id="size_qty_total2" style="width:80px; height:30px; float:left; background-color:#FAACAA; ""  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
         <input type="hidden" name="size_qty_array2[]"  value="" id="size_qty_array2" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$VendorWorkOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
           $html.='</tr>';

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
     
    public function QCStitchingGRNPrint($qcsti_code)
    {
        
         
    //   DB::enableQueryLog();
       
         $QCStitchingInhouseMaster = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'qcstitching_inhouse_master.vendorId')
         ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','qcstitching_inhouse_master.vw_code')
        ->where('qcstitching_inhouse_master.qcsti_code', $qcsti_code)
         ->get(['qcstitching_inhouse_master.*','usermaster.username','ledger_master.Ac_name','qcstitching_inhouse_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
          
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$QCStitchingInhouseMaster[0]->sales_order_no)->get();
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
          $QCStitchingGRNList = DB::select("SELECT   item_master.item_name,	qcstitching_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from 	qcstitching_inhouse_size_detail 
        inner join item_master on item_master.item_code=	qcstitching_inhouse_size_detail.item_code 
        inner join color_master on color_master.color_id=	qcstitching_inhouse_size_detail.color_id 
        where qcsti_code='".$QCStitchingInhouseMaster[0]->qcsti_code."' group by 	qcstitching_inhouse_size_detail.color_id");
        //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
         
      
             return view('QCStitchingGRNPrint', compact('QCStitchingInhouseMaster','QCStitchingGRNList','SizeDetailList','FirmDetail'));
      
    }


    public function QCStitchingGRNPrintPage($qcsti_code)
    {
        
         
    //   DB::enableQueryLog();
       
         $QCStitchingInhouseMaster = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'qcstitching_inhouse_master.vendorId')
         ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','qcstitching_inhouse_master.vw_code')
        ->where('qcstitching_inhouse_master.qcsti_code', $qcsti_code)
         ->get(['qcstitching_inhouse_master.*','usermaster.username','ledger_master.Ac_name','qcstitching_inhouse_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
          
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$QCStitchingInhouseMaster[0]->sales_order_no)->get();
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
          $QCStitchingGRNList = DB::select("SELECT   item_master.item_name,	qcstitching_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from 	qcstitching_inhouse_size_detail 
        inner join item_master on item_master.item_code=	qcstitching_inhouse_size_detail.item_code 
        inner join color_master on color_master.color_id=	qcstitching_inhouse_size_detail.color_id 
        where qcsti_code='".$QCStitchingInhouseMaster[0]->qcsti_code."' group by 	qcstitching_inhouse_size_detail.color_id");
        //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
         
      
             return view('QCStitchingGRNPrintView', compact('QCStitchingInhouseMaster','QCStitchingGRNList','SizeDetailList','FirmDetail'));
      
    }

    
    
      
     
    public function QCStitchingReport(Request $request)
    {
        ini_set('memory_limit', '10G');
        //DB::enableQueryLog();
        $QCStitchingDetails = DB::select("SELECT qcstitching_inhouse_size_detail2.qcsti_code,qcstitching_inhouse_size_detail2.color_id,qcstitching_inhouse_size_detail2.size_id,
        qcstitching_inhouse_size_detail2.qcsti_date,line_master.line_name, qcstitching_inhouse_size_detail2.sales_order_no, qcstitching_inhouse_size_detail2.vw_code, 
        ledger_master.Ac_name, L1.Ac_name as vendorName, mainstyle_name,qcstitching_inhouse_size_detail2.style_no, 
        color_master.color_name, color_master.style_img_path, brand_master.brand_name, 
        size_detail.size_name, ifnull(sum(qcstitching_inhouse_size_detail2.size_qty),0) as pass_qty, sum(qcstitching_inhouse_size_reject_detail2.size_qty) as rejectQty, 
        (ifnull(sum(qcstitching_inhouse_size_reject_detail2.size_qty),0) + ifnull(sum(qcstitching_inhouse_size_reject_detail2.size_qty),0)) as TotalQty
     
        FROM `qcstitching_inhouse_size_detail2`
        INNER JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=qcstitching_inhouse_size_detail2.sales_order_no
        INNER JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
        INNER JOIN ledger_master on ledger_master.ac_code=qcstitching_inhouse_size_detail2.Ac_code
        INNER JOIN ledger_master as L1 on L1.ac_code=qcstitching_inhouse_size_detail2.vendorId
        INNER JOIN color_master on color_master.color_id=qcstitching_inhouse_size_detail2.color_id
        INNER JOIN size_detail on size_detail.size_id = qcstitching_inhouse_size_detail2.size_id
        INNER JOIN line_master on line_master.line_id = qcstitching_inhouse_size_detail2.line_id
        LEFT JOIN qcstitching_inhouse_size_reject_detail2 on  qcstitching_inhouse_size_reject_detail2.qcsti_code=qcstitching_inhouse_size_detail2.qcsti_code 
        AND qcstitching_inhouse_size_reject_detail2.color_id = qcstitching_inhouse_size_detail2.color_id
        AND qcstitching_inhouse_size_reject_detail2.size_id = qcstitching_inhouse_size_detail2.size_id
        INNER JOIN main_style_master on main_style_master.mainstyle_id=qcstitching_inhouse_size_detail2.mainstyle_id
        GROUP BY qcstitching_inhouse_size_detail2.qcsti_code,qcstitching_inhouse_size_detail2.color_id,qcstitching_inhouse_size_detail2.size_id");
        
       //dd(DB::getQueryLog());
        if(count($QCStitchingDetails) > 0)
        {
            if ($request->ajax()) 
            {
               return Datatables::of($QCStitchingDetails)
               ->addIndexColumn()
               ->make(true);
            }
        }
        return view('QCStitchingReport');
        
    }
      
    public function destroy($id)
    {
        $QcData = DB::table('qcstitching_inhouse_master')->select('qcsti_date','vendorId')->where('qcsti_code', $id)->first();
        DB::table('qcstitching_inhouse_reject_detail')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_size_detail2')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_size_detail')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_size_reject_detail')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_size_reject_detail2')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_detail')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_master')->where('qcsti_code', $id)->delete();
        
        $prodctionData = DB::connection('hrms_database')->table('production_master')->select('proId')->where('productionDate','=',$QcData->qcsti_date)->where('branch_id','=',$QcData->vendorId)->first();
        DB::connection('hrms_database')->table('production_master')->where('productionDate','=',$QcData->qcsti_date)->where('branch_id','=',$QcData->vendorId)->delete();  
        DB::connection('hrms_database')->table('production_detail')->where('proId','=',$prodctionData->proId)->delete(); 

        SourceModel::on('mysql');
        Session::flash('messagedelete', 'Deleted record successfully'); 
        
    }
    
    public function QualityControl()
    {  
        $workOrderData = DB::select("SELECT 
                                vendor_work_order_master.vw_code, (select sum(total_qty) FROM cut_panel_issue_master WHERE vw_code = vendor_work_order_master.vw_code AND  vw_code = vendor_work_order_master.vw_code) as cutting_qty,
                                (select sum(total_qty) FROM stitching_inhouse_master WHERE vw_code = vendor_work_order_master.vw_code) as stitiching_qty
                            FROM vendor_work_order_master
                            INNER JOIN buyer_purchse_order_master 
                                ON buyer_purchse_order_master.tr_code = vendor_work_order_master.sales_order_no 
                            WHERE vendor_work_order_master.vendorId = 56 
                                AND vendor_work_order_master.endflag = 1 
                                AND buyer_purchse_order_master.job_status_id = 1
                            GROUP BY 
                                vendor_work_order_master.vw_code");
    

        $StitichingOperationData = DB::select("SELECT * FROM dhu_stiching_defect_type");
        $StitichingDefectData = DB::select("SELECT * FROM dhu_stiching_operation");
        
        $maxData = DB::SELECT("SELECT max(QualityControlId) as QualityControlId FROM quality_control_master");
        $maxId = isset($maxData[0]->QualityControlId) ? $maxData[0]->QualityControlId : 0;
        return view('QualityControl', compact('workOrderData', 'StitichingOperationData','StitichingDefectData', 'maxId'));
    }
    
   
   
    public function GetQualityControlVWTable(Request $request)
    {
        $html='';          
        $html1 = '';
        $workOrderData = DB::SELECT("SELECT  quality_control_master.*,quality_control_detail.*,vendor_work_order_detail.*,quality_control_alter_detail.*, color_master.color_name, 
                        (select sum(total_qty) FROM cut_panel_issue_master INNER JOIN cut_panel_issue_detail ON cut_panel_issue_detail.cpi_code = cut_panel_issue_master.cpi_code
                        WHERE cut_panel_issue_master.vw_code = vendor_work_order_detail.vw_code AND cut_panel_issue_detail.color_id = vendor_work_order_detail.color_id) as cutting_qty,vendor_work_order_detail.sales_order_no,
                        vendor_work_order_detail.vw_code,vendor_work_order_detail.color_id FROM vendor_work_order_detail   
                        INNER JOIN color_master ON color_master.color_id = vendor_work_order_detail.color_id
                        LEFT JOIN quality_control_master ON quality_control_master.vw_code = vendor_work_order_detail.vw_code AND quality_control_master.color_id = vendor_work_order_detail.color_id
                        LEFT JOIN quality_control_detail ON quality_control_detail.QualityControlId = quality_control_master.QualityControlId 
                        LEFT JOIN quality_control_alter_detail ON quality_control_alter_detail.QualityControlId = quality_control_master.QualityControlId 
                        WHERE vendor_work_order_detail.vw_code='".$request->vw_code."' GROUP BY vendor_work_order_detail.color_id"); 
     
        foreach($workOrderData as $row)
        {
                $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($row->sales_order_no);
                $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
                
                
                $html .=' <table>
                            <thead>
                              <tr>
                                <th><div class="live-dot"></div>&nbsp;&nbsp;<h1 class="line_nos">'.$row->line_no.'</h1></th>
                                <th><h3>Order No. : '.$row->sales_order_no.'</h3></th>
                                <th colspan="2"><h3>Work Order : '.$row->vw_code.'</h3></th>
                                <th colspan="2"><h3>Color : '.$row->color_name.' ('.$row->color_id.')</h3></th>
                                <th colspan="2"><h3>Cutting : '.$row->cutting_qty.'</h3></th> 
                                <th colspan="'.((count($SizeDetailList) + 3) - 8).'"><h3></h3></th> 
                              </tr>
                              <tr> 
                                <th rowspan="4">Status</th>';
                                foreach ($SizeDetailList as $sz) 
                                {
                                  $html .=' <th><h2>'.$sz->size_name.'</h2></th>';
                                }
                                $html .=' <th>Total</th>
                                <th>Line Bal.</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr> 
                                <td><input type="hidden" class="initial_line_bal" value="'.$row->cutting_qty.'"><h2>Pass</h2></td>';
                             
                                $QCPassData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.status_id = 1 AND quality_control_master.vw_code='".$request->vw_code."' 
                                            AND quality_control_master.sales_order_no='".$row->sales_order_no."'  
                                            AND quality_control_master.color_id='".$row->color_id."'  
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                $total_pass = isset($QCPassData[0]->total_qty) ? $QCPassData[0]->total_qty : 0;
                                
                                foreach ($SizeDetailList as $sz) 
                                {
                                     //DB::enableQueryLog();
                                  $QCData = DB::SELECT("SELECT sum(size_qty) as size_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.size_id='$sz->size_id' AND quality_control_detail.status_id = 1 
                                            AND quality_control_master.vw_code='".$request->vw_code."'   
                                            AND quality_control_master.sales_order_no='".$row->sales_order_no."'   
                                            AND quality_control_master.color_id='".$row->color_id."'
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                    //dd(DB::getQueryLog());
                                  $size_qty = isset($QCData[0]->size_qty) ? $QCData[0]->size_qty : 0; 
                                  
                                  $html .='<td><button class="pass" onclick="QtyCalculate(this);calculateTotal(this);" sales_order_no="'.$row->sales_order_no.'"  vw_code="'.$row->vw_code.'"  color_id="'.$row->color_id.'"  
                                            cutting_qty="'.$row->cutting_qty.'"  sz_code="'.$sz->size_id.'" status_id = "1">'.$size_qty.'</button><input type="hidden" class="pass_qty" name="pass_'.$sz->size_name.'" value="'.$size_qty.'"></td>';
                                }
                                $html .='<td><h2 class="total_pass">'.$total_pass.'</h2></td>
                                <td rowspan="4"><h2 class="total_line_bal">'.$row->line_bal.'</h2></td>
                              </tr>
                              <tr >
                                <td><h2>Reject</h2></td>';
                                
                                $QCRejectData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.status_id = 2 AND quality_control_master.vw_code='".$request->vw_code."' AND quality_control_master.color_id='".$row->color_id."'
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                $total_reject = isset($QCRejectData[0]->total_qty) ? $QCRejectData[0]->total_qty : 0;
                                
                                foreach ($SizeDetailList as $sz) 
                                {
                                  $QCData1 = DB::SELECT("SELECT  sum(size_qty) as size_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.size_id='$sz->size_id' AND quality_control_detail.status_id = 2 
                                            AND quality_control_master.vw_code='".$request->vw_code."' AND quality_control_master.color_id='".$row->color_id."'
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                    
                                  $size_qty1 = isset($QCData1[0]->size_qty) ? $QCData1[0]->size_qty : 0; 
                                  
                                  $html .='<td><button class="reject" onclick="QtyCalculate(this);calculateTotal(this);" sales_order_no="'.$row->sales_order_no.'"  vw_code="'.$row->vw_code.'"  color_id="'.$row->color_id.'"   
                                            cutting_qty="'.$row->cutting_qty.'"  sz_code="'.$sz->size_id.'" status_id = "2">'.$size_qty1.'</button><input type="hidden" class="reject_qty" name="reject_'.$sz->size_name.'" value="'.$size_qty1.'"></td>';
                                }
                                $html .='<td><h2 class="total_reject">'.$total_reject.'</h2></td>
                              </tr>
                              <tr>
                                <td><h2>Alter</h2></td>';
                                //DB::enableQueryLog();
    
                                $QCAlterData = DB::SELECT("SELECT  sum(quality_control_alter_detail.size_qty) as total_qty FROM quality_control_alter_detail  
                                                        INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId   
                                                        WHERE DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."' AND quality_control_master.color_id='".$row->color_id."'
                                                        AND quality_control_master.vw_code='".$request->vw_code."'");  
                                //dd(DB::getQueryLog());
                                $total_alter = isset($QCAlterData[0]->total_qty) ? $QCAlterData[0]->total_qty : 0;
                                
                                foreach ($SizeDetailList as $sz) 
                                {
                                  $QCAlterData1 = DB::SELECT("SELECT  sum(quality_control_alter_detail.size_qty) as size_qty FROM quality_control_alter_detail
                                                        INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId 
                                                        WHERE quality_control_alter_detail.size_id='$sz->size_id' AND quality_control_master.vw_code='".$request->vw_code."'
                                                        AND quality_control_master.color_id='".$row->color_id."' AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                    
                                  $size_qty2 = isset($QCAlterData1[0]->size_qty) ? $QCAlterData1[0]->size_qty : 0; 
                                  
                                  $html .='<td><button class="alter" onclick="OpenPopup(this);" QualityControlId="'.$row->QualityControlId.'" sales_order_no="'.$row->sales_order_no.'"  vw_code="'.$row->vw_code.'" color_id="'.$row->color_id.'"  
                                            cutting_qty="'.$row->cutting_qty.'" sz_code="'.$sz->size_id.'" status_id = "3">'.$size_qty2.'</button><input type="hidden" class="alter_qty" name="alter_'.$sz->size_name.'" value="'.$size_qty2.'"></td>';
                                }
                                $html .='<td><h2 class="total_alter">'.$total_alter.'</h2></td>
                              </tr>
                            </tbody>
                          </table>';
                
                
            }
          
        $sales_order_no = isset($workOrderData[0]->sales_order_no) ? $workOrderData[0]->sales_order_no : 0;        
          
        $StitichingOperationData = DB::SELECT("SELECT dhu_stiching_defect_type.* FROM buyer_purchse_order_master   
                    INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    INNER JOIN dhu_stiching_defect_type ON dhu_stiching_defect_type.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    WHERE buyer_purchse_order_master.tr_code='".$sales_order_no."'");       
          
        
        foreach($StitichingOperationData as $row)
        {
            $html1 .= '<li class="operation-item" operationId="'.$row->dhu_sdt_Id.'" onclick="ReadEachDefectData(this);">'.($row->dhu_sdt_marathi_Name ?? $row->dhu_sdt_Name).'</li>';
        }
        return response()->json(['html' => $html, 'html1' => $html1]);
    }    
    
    public function StoreQualityControlData(Request $request)
    { 
        $existing = DB::table('quality_control_master')
            ->where('vw_code', $request->vw_code)
            ->where('sales_order_no', $request->sales_order_no)
            ->where('color_id', $request->color_id)
            ->where('QualityControlDate', date("Y-m-d"))
            ->first();
        
        $data = [
            "vw_code"           => $request->vw_code,
            "sales_order_no"    => $request->sales_order_no,
            "color_id"          => $request->color_id,
            "cutting_qty"       => $request->cutting_qty,
            "line_no"          => $request->line_no,
            "line_bal"          => $request->line_bal,
            "QualityControlDate"=> date("Y-m-d"),
            "userId"            => Session::get('userId'),
            "updated_at"        => date("Y-m-d H:i:s"),
            "delflag"           => 0,
        ];
        
        if ($existing) {
            // Update the existing entry
            DB::table('quality_control_master')
                ->where('QualityControlId', $existing->QualityControlId)
                ->update($data);
        
            $QualityControlId = $existing->QualityControlId;
        } else {
            // Insert new entry
            $QualityControlId = DB::table('quality_control_master')->insertGetId($data);
        }

        DB::table('quality_control_detail')->join('quality_control_master', 'quality_control_master.QualityControlId', '=', 'quality_control_detail.QualityControlId')
                                    ->where('quality_control_detail.QualityControlId', $QualityControlId)
                                    ->where('quality_control_detail.status_id', $request->status_id)
                                    ->where('quality_control_detail.size_id', $request->size_id)
                                    ->where('quality_control_master.QualityControlDate',date("Y-m-d"))
                                    ->delete();
        
        DB::table('quality_control_detail')->insert([
            "QualityControlId"=>$QualityControlId,
            "status_id"=>$request->status_id,
            "line_no"=>$request->line_no,
            "size_id"=>$request->size_id,
            "size_qty"=>$request->size_qty,
            "total_qty"=>$request->total_qty,
        ]);
        
        return 1;
    }
    
    public function StoreAlterQualityControlData(Request $request)
    { 

        $existing = DB::table('quality_control_master')
            ->where('vw_code', $request->vw_code)
            ->where('sales_order_no', $request->sales_order_no)
            ->where('color_id', $request->color_id)
            ->where('QualityControlDate', date("Y-m-d"))
            ->first();
        
        $data = [
            "vw_code"           => $request->vw_code,
            "sales_order_no"    => $request->sales_order_no,
            "color_id"          => $request->color_id,
            "cutting_qty"       => 0,
            "line_bal"          => 0,
            "line_no"           => $request->line_no,
            "QualityControlDate"=> date("Y-m-d"),
            "userId"            => Session::get('userId'),
            "updated_at"        => date("Y-m-d H:i:s"),
            "delflag"           => 0,
        ];
        
        if ($existing) {
            // Update the existing entry
            DB::table('quality_control_master')
                ->where('QualityControlId', $existing->QualityControlId)
                ->update($data);
        
            $QualityControlId = $existing->QualityControlId;
        } else {
            // Insert new entry
            $QualityControlId = DB::table('quality_control_master')->insertGetId($data);
        }
        
        DB::table('quality_control_alter_detail')->join('quality_control_master', 'quality_control_master.QualityControlId', '=', 'quality_control_alter_detail.QualityControlId')
                ->where('quality_control_alter_detail.QualityControlId', $QualityControlId)
                ->where('quality_control_alter_detail.vw_code', $request->vw_code)
                ->where('quality_control_alter_detail.sales_order_no', $request->sales_order_no)
                ->where('quality_control_alter_detail.color_id', $request->color_id)
                ->where('quality_control_alter_detail.operationId', $request->operationId)
                ->where('quality_control_alter_detail.defectId', $request->defectId)
                ->where('quality_control_alter_detail.size_id', $request->size_id)
                ->where('quality_control_master.QualityControlDate',date("Y-m-d"))
                ->delete();
        
        DB::table('quality_control_alter_detail')->insert([
            "QualityControlId"=>$QualityControlId,
            "vw_code"=>$request->vw_code,
            "sales_order_no"=>$request->sales_order_no,
            "color_id"=>$request->color_id,
            "operationId"=>$request->operationId,
            "line_no"=>$request->line_no,
            "defectId"=>$request->defectId, 
            "size_id"=>$request->size_id,
            "size_qty"=>$request->size_qty
        ]); 
        
        return 1;
    }
    
    public function QualityControlTrial()
    {  
        $workOrderData = DB::select("SELECT 
                                vendor_work_order_master.vw_code, (select sum(total_qty) FROM cut_panel_issue_master WHERE vw_code = vendor_work_order_master.vw_code AND  vw_code = vendor_work_order_master.vw_code) as cutting_qty,
                                (select sum(total_qty) FROM stitching_inhouse_master WHERE vw_code = vendor_work_order_master.vw_code) as stitiching_qty
                            FROM vendor_work_order_master
                            INNER JOIN buyer_purchse_order_master 
                                ON buyer_purchse_order_master.tr_code = vendor_work_order_master.sales_order_no 
                            WHERE vendor_work_order_master.vendorId = 56 
                                AND vendor_work_order_master.endflag = 1 
                                AND buyer_purchse_order_master.job_status_id = 1
                            GROUP BY 
                                vendor_work_order_master.vw_code");
    

        $StitichingOperationData = DB::select("SELECT * FROM dhu_stiching_defect_type");
        $StitichingDefectData = DB::select("SELECT * FROM dhu_stiching_operation");
        
        $maxData = DB::SELECT("SELECT max(QualityControlId) as QualityControlId FROM quality_control_master");
        $maxId = isset($maxData[0]->QualityControlId) ? $maxData[0]->QualityControlId : 0;
        return view('QualityControlTrial', compact('workOrderData', 'StitichingOperationData','StitichingDefectData', 'maxId'));
    }      
    
    public function StoreQualityControlDataTrial(Request $request)
    { 
        $existing = DB::table('quality_control_master')
            ->where('vw_code', $request->vw_code)
            ->where('sales_order_no', $request->sales_order_no)
            ->where('color_id', $request->color_id)
            ->where('QualityControlDate', date("Y-m-d"))
            ->first();
        
        $data = [
            "vw_code"           => $request->vw_code,
            "sales_order_no"    => $request->sales_order_no,
            "color_id"          => $request->color_id,
            "cutting_qty"       => $request->cutting_qty,
            "line_no"          => $request->line_no,
            "line_bal"          => $request->line_bal,
            "QualityControlDate"=> date("Y-m-d"),
            "userId"            => Session::get('userId'),
            "created_at"        => date("Y-m-d H:i:s"),
            "delflag"           => 0,
        ];
        
        if ($existing) {
            // Update the existing entry
            DB::table('quality_control_master')
                ->where('QualityControlId', $existing->QualityControlId)
                ->update($data);
        
            $QualityControlId = $existing->QualityControlId;
        } else {
            // Insert new entry
            $QualityControlId = DB::table('quality_control_master')->insertGetId($data);
        }

        DB::table('quality_control_detail')->join('quality_control_master', 'quality_control_master.QualityControlId', '=', 'quality_control_detail.QualityControlId')
                                    ->where('quality_control_detail.QualityControlId', $QualityControlId)
                                    ->where('quality_control_detail.status_id', $request->status_id)
                                    ->where('quality_control_detail.size_id', $request->size_id)
                                    ->where('quality_control_master.QualityControlDate',date("Y-m-d"))
                                    ->delete();
        
        DB::table('quality_control_detail')->insert([
            "QualityControlId"=>$QualityControlId,
            "status_id"=>$request->status_id,
            "line_no"=>$request->line_no,
            "size_id"=>$request->size_id,
            "size_qty"=>$request->size_qty,
            "total_qty"=>$request->total_qty,
        ]);
        
        return 1;
    }
    
    public function StoreAlterQualityControlDataTrial(Request $request)
    { 

        $existing = DB::table('quality_control_master')
            ->where('vw_code', $request->vw_code)
            ->where('sales_order_no', $request->sales_order_no)
            ->where('color_id', $request->color_id)
            ->where('QualityControlDate', date("Y-m-d"))
            ->first();
        
        $data = [
            "vw_code"           => $request->vw_code,
            "sales_order_no"    => $request->sales_order_no,
            "color_id"          => $request->color_id,
            "cutting_qty"       => 0,
            "line_bal"          => 0,
            "line_no"           => $request->line_no,
            "QualityControlDate"=> date("Y-m-d"),
            "userId"            => Session::get('userId'),
            "created_at"        => date("Y-m-d H:i:s"),
            "delflag"           => 0,
        ];
        
        if ($existing) {
            // Update the existing entry
            DB::table('quality_control_master')
                ->where('QualityControlId', $existing->QualityControlId)
                ->update($data);
        
            $QualityControlId = $existing->QualityControlId;
        } else {
            // Insert new entry
            $QualityControlId = DB::table('quality_control_master')->insertGetId($data);
        }
        
        DB::table('quality_control_alter_detail')->join('quality_control_master', 'quality_control_master.QualityControlId', '=', 'quality_control_alter_detail.QualityControlId')
                ->where('quality_control_alter_detail.QualityControlId', $QualityControlId)
                ->where('quality_control_alter_detail.vw_code', $request->vw_code)
                ->where('quality_control_alter_detail.sales_order_no', $request->sales_order_no)
                ->where('quality_control_alter_detail.color_id', $request->color_id)
                ->where('quality_control_alter_detail.operationId', $request->operationId)
                ->where('quality_control_alter_detail.defectId', $request->defectId)
                ->where('quality_control_alter_detail.size_id', $request->size_id)
                ->where('quality_control_master.QualityControlDate',date("Y-m-d"))
                ->delete();
        
        DB::table('quality_control_alter_detail')->insert([
            "QualityControlId"=>$QualityControlId,
            "vw_code"=>$request->vw_code,
            "sales_order_no"=>$request->sales_order_no,
            "color_id"=>$request->color_id,
            "operationId"=>$request->operationId,
            "line_no"=>$request->line_no,
            "defectId"=>$request->defectId, 
            "size_id"=>$request->size_id,
            "size_qty"=>$request->size_qty
        ]); 
        
        return 1;
    }
    
    public function GetQualityControlVWTableTrial(Request $request)
    {
        $html='';          
        $html1 = '';
        $workOrderData = DB::SELECT("SELECT  quality_control_master.*,quality_control_detail.*,vendor_work_order_detail.*,quality_control_alter_detail.*, color_master.color_name, 
                        (select sum(total_qty) FROM cut_panel_issue_master INNER JOIN cut_panel_issue_detail ON cut_panel_issue_detail.cpi_code = cut_panel_issue_master.cpi_code
                        WHERE cut_panel_issue_master.vw_code = vendor_work_order_detail.vw_code AND cut_panel_issue_detail.color_id = vendor_work_order_detail.color_id) as cutting_qty,vendor_work_order_detail.sales_order_no,
                        vendor_work_order_detail.vw_code,vendor_work_order_detail.color_id FROM vendor_work_order_detail   
                        INNER JOIN color_master ON color_master.color_id = vendor_work_order_detail.color_id
                        LEFT JOIN quality_control_master ON quality_control_master.vw_code = vendor_work_order_detail.vw_code AND quality_control_master.color_id = vendor_work_order_detail.color_id
                        LEFT JOIN quality_control_detail ON quality_control_detail.QualityControlId = quality_control_master.QualityControlId 
                        LEFT JOIN quality_control_alter_detail ON quality_control_alter_detail.QualityControlId = quality_control_master.QualityControlId 
                        WHERE vendor_work_order_detail.vw_code='".$request->vw_code."' GROUP BY vendor_work_order_detail.color_id"); 
     
        foreach($workOrderData as $row)
        {
                $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($row->sales_order_no);
                $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
                
                
                $html .=' <table>
                            <thead>
                              <tr>
                                <th><div class="live-dot"></div>&nbsp;&nbsp;<h1 class="line_nos">'.$row->line_no.'</h1></th>
                                <th><h3>Order No. : '.$row->sales_order_no.'</h3></th>
                                <th colspan="2"><h3>Work Order : '.$row->vw_code.'</h3></th>
                                <th colspan="2"><h3>Color : '.$row->color_name.' ('.$row->color_id.')</h3></th>
                                <th colspan="2"><h3>Cutting : '.$row->cutting_qty.'</h3></th> 
                                <th colspan="'.((count($SizeDetailList) + 3) - 8).'"><h3></h3></th> 
                              </tr>
                              <tr> 
                                <th rowspan="4">Status</th>';
                                foreach ($SizeDetailList as $sz) 
                                {
                                  $html .=' <th><h2>'.$sz->size_name.'</h2></th>';
                                }
                                $html .=' <th>Total</th>
                                <th>Line Bal.</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr> 
                                <td><input type="hidden" class="initial_line_bal" value="'.$row->cutting_qty.'"><h2>Pass</h2></td>';
                             
                                $QCPassData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.status_id = 1 AND quality_control_master.vw_code='".$request->vw_code."' 
                                            AND quality_control_master.sales_order_no='".$row->sales_order_no."'  
                                            AND quality_control_master.color_id='".$row->color_id."'  
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                $total_pass = isset($QCPassData[0]->total_qty) ? $QCPassData[0]->total_qty : 0;
                                
                                foreach ($SizeDetailList as $sz) 
                                {
                                     //DB::enableQueryLog();
                                  $QCData = DB::SELECT("SELECT sum(size_qty) as size_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.size_id='$sz->size_id' AND quality_control_detail.status_id = 1 
                                            AND quality_control_master.vw_code='".$request->vw_code."'   
                                            AND quality_control_master.sales_order_no='".$row->sales_order_no."'   
                                            AND quality_control_master.color_id='".$row->color_id."'
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                    //dd(DB::getQueryLog());
                                  $size_qty = isset($QCData[0]->size_qty) ? $QCData[0]->size_qty : 0; 
                                  
                                  $html .='<td><button class="pass" onclick="QtyCalculate(this);calculateTotal(this);" sales_order_no="'.$row->sales_order_no.'"  vw_code="'.$row->vw_code.'"  color_id="'.$row->color_id.'"  
                                            cutting_qty="'.$row->cutting_qty.'"  sz_code="'.$sz->size_id.'" status_id = "1">'.$size_qty.'</button><input type="hidden" class="pass_qty" name="pass_'.$sz->size_name.'" value="'.$size_qty.'"></td>';
                                }
                                $html .='<td><h2 class="total_pass">'.$total_pass.'</h2></td>
                                <td rowspan="4"><h2 class="total_line_bal">'.$row->line_bal.'</h2></td>
                              </tr>
                              <tr >
                                <td><h2>Reject</h2></td>';
                                
                                $QCRejectData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.status_id = 2 AND quality_control_master.vw_code='".$request->vw_code."' AND quality_control_master.color_id='".$row->color_id."'
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                $total_reject = isset($QCRejectData[0]->total_qty) ? $QCRejectData[0]->total_qty : 0;
                                
                                foreach ($SizeDetailList as $sz) 
                                {
                                  $QCData1 = DB::SELECT("SELECT  sum(size_qty) as size_qty FROM quality_control_detail 
                                            INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_detail.QualityControlId  
                                            WHERE quality_control_detail.size_id='$sz->size_id' AND quality_control_detail.status_id = 2 
                                            AND quality_control_master.vw_code='".$request->vw_code."' AND quality_control_master.color_id='".$row->color_id."'
                                            AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                    
                                  $size_qty1 = isset($QCData1[0]->size_qty) ? $QCData1[0]->size_qty : 0; 
                                  
                                  $html .='<td><button class="reject" onclick="QtyCalculate(this);calculateTotal(this);" sales_order_no="'.$row->sales_order_no.'"  vw_code="'.$row->vw_code.'"  color_id="'.$row->color_id.'"   
                                            cutting_qty="'.$row->cutting_qty.'"  sz_code="'.$sz->size_id.'" status_id = "2">'.$size_qty1.'</button><input type="hidden" class="reject_qty" name="reject_'.$sz->size_name.'" value="'.$size_qty1.'"></td>';
                                }
                                $html .='<td><h2 class="total_reject">'.$total_reject.'</h2></td>
                              </tr>
                              <tr>
                                <td><h2>Alter</h2></td>';
                                //DB::enableQueryLog();
    
                                $QCAlterData = DB::SELECT("SELECT  sum(quality_control_alter_detail.size_qty) as total_qty FROM quality_control_alter_detail  
                                                        INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId   
                                                        WHERE DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."' AND quality_control_master.color_id='".$row->color_id."'
                                                        AND quality_control_master.vw_code='".$request->vw_code."'");  
                                //dd(DB::getQueryLog());
                                $total_alter = isset($QCAlterData[0]->total_qty) ? $QCAlterData[0]->total_qty : 0;
                                
                                foreach ($SizeDetailList as $sz) 
                                {
                                  $QCAlterData1 = DB::SELECT("SELECT  sum(quality_control_alter_detail.size_qty) as size_qty FROM quality_control_alter_detail
                                                        INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId 
                                                        WHERE quality_control_alter_detail.size_id='$sz->size_id' AND quality_control_master.vw_code='".$request->vw_code."'
                                                        AND quality_control_master.color_id='".$row->color_id."' AND DATE(quality_control_master.QualityControlDate) = '".(date("Y-m-d"))."'");  
                                    
                                  $size_qty2 = isset($QCAlterData1[0]->size_qty) ? $QCAlterData1[0]->size_qty : 0; 
                                  
                                  $html .='<td><button class="alter" onclick="OpenPopup(this);" QualityControlId="'.$row->QualityControlId.'" sales_order_no="'.$row->sales_order_no.'"  vw_code="'.$row->vw_code.'" color_id="'.$row->color_id.'"  
                                            cutting_qty="'.$row->cutting_qty.'" sz_code="'.$sz->size_id.'" status_id = "3">'.$size_qty2.'</button><input type="hidden" class="alter_qty" name="alter_'.$sz->size_name.'" value="'.$size_qty2.'"></td>';
                                }
                                $html .='<td><h2 class="total_alter">'.$total_alter.'</h2></td>
                              </tr>
                            </tbody>
                          </table>';
                
                
            }
          
        $sales_order_no = isset($workOrderData[0]->sales_order_no) ? $workOrderData[0]->sales_order_no : 0;        
          
        $StitichingOperationData = DB::SELECT("SELECT dhu_stiching_defect_type.* FROM buyer_purchse_order_master   
                    INNER JOIN main_style_master ON main_style_master.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    INNER JOIN dhu_stiching_defect_type ON dhu_stiching_defect_type.mainstyle_id = buyer_purchse_order_master.mainstyle_id 
                    WHERE buyer_purchse_order_master.tr_code='".$sales_order_no."'");       
          
        
        foreach($StitichingOperationData as $row)
        {
            $html1 .= '<li class="operation-item" operationId="'.$row->dhu_sdt_Id.'" onclick="ReadEachDefectData(this);">'.($row->dhu_sdt_marathi_Name ?? $row->dhu_sdt_Name).'</li>';
        }
        return response()->json(['html' => $html, 'html1' => $html1]);
    }  
        
    public function ReadEachDefectDataTrial(Request $request)
    { 
     
        $EachdefectData = DB::table('quality_control_alter_detail')->select('quality_control_alter_detail.*', DB::raw('sum(size_qty) as size_qty')) 
                ->join('quality_control_master', 'quality_control_master.QualityControlId', '=', 'quality_control_alter_detail.QualityControlId')
                ->where('quality_control_alter_detail.vw_code', $request->vw_code)
                ->where('quality_control_alter_detail.sales_order_no', $request->sales_order_no)
                ->where('quality_control_alter_detail.color_id', $request->color_id)
                ->where('quality_control_alter_detail.operationId', $request->operationId)
                ->where('quality_control_alter_detail.size_id', $request->size_id)
                ->where('quality_control_master.QualityControlDate',date("Y-m-d"))
                ->groupBy('defectId')
                ->get();
        
        $totalEachDefectData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_alter_detail 
                                INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId
                                WHERE quality_control_alter_detail.vw_code='".$request->vw_code."' 
                                AND quality_control_alter_detail.sales_order_no='".$request->sales_order_no."' 
                                AND quality_control_alter_detail.color_id='".$request->color_id."' 
                                AND quality_control_alter_detail.operationId='".$request->operationId."' 
                                AND quality_control_alter_detail.size_id='".$request->size_id."'
                                AND DATE(quality_control_master.QualityControlDate)='".date("Y-m-d")."'");
         
       
        $total_qty = isset($totalEachDefectData[0]->total_qty) ? $totalEachDefectData[0]->total_qty : 0;
        return response()->json(['EachdefectData' => $EachdefectData, 'total_qty'=>$total_qty]);
    }
    
    public function ReadFinalAlterDataTrial(Request $request)
    {  
        //  DB::enableQueryLog();
        $totalEachDefectData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_alter_detail   
                                INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId WHERE 
                                quality_control_alter_detail.vw_code='".$request->vw_code."' 
                                AND quality_control_alter_detail.sales_order_no='".$request->sales_order_no."' 
                                AND quality_control_alter_detail.color_id='".$request->color_id."' 
                                AND quality_control_alter_detail.size_id='".$request->size_id."' AND DATE(quality_control_master.QualityControlDate)='".date("Y-m-d")."'");
         //  dd(DB::getQueryLog());
        $total_qty = isset($totalEachDefectData[0]->total_qty) ? $totalEachDefectData[0]->total_qty : 0;
        return response()->json(['total_qty'=>$total_qty]);
    }
    
    public function ReadEachDefectData(Request $request)
    { 
     
        $EachdefectData = DB::table('quality_control_alter_detail')->select('quality_control_alter_detail.*', DB::raw('sum(size_qty) as size_qty')) 
                ->join('quality_control_master', 'quality_control_master.QualityControlId', '=', 'quality_control_alter_detail.QualityControlId')
                ->where('quality_control_alter_detail.vw_code', $request->vw_code)
                ->where('quality_control_alter_detail.sales_order_no', $request->sales_order_no)
                ->where('quality_control_alter_detail.color_id', $request->color_id)
                ->where('quality_control_alter_detail.operationId', $request->operationId)
                ->where('quality_control_alter_detail.size_id', $request->size_id)
                ->where('quality_control_master.QualityControlDate',date("Y-m-d"))
                ->groupBy('defectId')
                ->get();
        
        $totalEachDefectData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_alter_detail 
                                INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId
                                WHERE quality_control_alter_detail.vw_code='".$request->vw_code."' 
                                AND quality_control_alter_detail.sales_order_no='".$request->sales_order_no."' 
                                AND quality_control_alter_detail.color_id='".$request->color_id."' 
                                AND quality_control_alter_detail.operationId='".$request->operationId."' 
                                AND quality_control_alter_detail.size_id='".$request->size_id."'
                                AND DATE(quality_control_master.QualityControlDate)='".date("Y-m-d")."'");
         
       
        $total_qty = isset($totalEachDefectData[0]->total_qty) ? $totalEachDefectData[0]->total_qty : 0;
        return response()->json(['EachdefectData' => $EachdefectData, 'total_qty'=>$total_qty]);
    }
    
    public function ReadFinalAlterData(Request $request)
    {  
        //  DB::enableQueryLog();
        $totalEachDefectData = DB::SELECT("SELECT sum(size_qty) as total_qty FROM quality_control_alter_detail   
                                INNER JOIN quality_control_master ON quality_control_master.QualityControlId = quality_control_alter_detail.QualityControlId WHERE 
                                quality_control_alter_detail.vw_code='".$request->vw_code."' 
                                AND quality_control_alter_detail.sales_order_no='".$request->sales_order_no."' 
                                AND quality_control_alter_detail.color_id='".$request->color_id."' 
                                AND quality_control_alter_detail.size_id='".$request->size_id."' AND DATE(quality_control_master.QualityControlDate)='".date("Y-m-d")."'");
         //  dd(DB::getQueryLog());
        $total_qty = isset($totalEachDefectData[0]->total_qty) ? $totalEachDefectData[0]->total_qty : 0;
        return response()->json(['total_qty'=>$total_qty]);
    }
    
     
    public function QualityControlReport(Request $request)
    {
        $srno = 1;
        $filter = '';
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-d");
        $toDate = isset($request->toDate) ? $request->toDate : date("Y-m-d");
        $sales_order_no = isset($request->sales_order_no) ? $request->sales_order_no : "";
        $vw_code = isset($request->vw_code) ? $request->vw_code : "";
        $Ac_code = isset($request->Ac_code) ? $request->Ac_code : 0;
        $brand_id = isset($request->brand_id) ? $request->brand_id : 0;
        $line_id = isset($request->line_id) ? $request->line_id : 0;
        
        $SalesOrderList= DB::select('SELECT tr_code as sales_order_no FROM buyer_purchse_order_master WHERE delflag = 0');
        $VendorWorkOrderList= DB::select('SELECT vw_code FROM vendor_work_order_master WHERE delflag = 0'); 
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $BrandList = DB::SELECT("SELECT brand_id, brand_name FROM brand_master WHERE delflag=0");    
        
        if ($request->ajax()) 
        {  
            if($fromDate != '' && $toDate != '')
            {
                $filter .= " AND qcm.QualityControlDate BETWEEN '".$fromDate."' AND '".$toDate."'"; 
            }
            
            if($sales_order_no != '')
            {
                $filter .= " AND qcm.sales_order_no ='".$sales_order_no."'"; 
            }
            
            if($sales_order_no != '')
            {
                $filter .= " AND qcm.sales_order_no ='".$sales_order_no."'"; 
            }
            
            if($vw_code != '')
            {
                $filter .= " AND qcm.vw_code ='".$vw_code."'"; 
            }
            
            if($Ac_code != '')
            {
                $filter .= " AND bpom.Ac_code ='".$Ac_code."'"; 
            }
            
            if($brand_id > 0)
            {
                $filter .= " AND bpom.brand_id ='".$brand_id."'"; 
            }
            
            if($line_id > 0)
            {
                $filter .= " AND qcm.line_no ='".$line_id."'"; 
            }
            
           $QualityControlList = DB::select("SELECT 
                                            NULL AS dhu_so_Name, 
                                            qcd.QualityControlId,
                                            qcd.size_id,
                                            NULL AS operationId,
                                            NULL AS operation,
                                            NULL AS defectId,
                                            qcm.QualityControlDate,
                                            qcm.sales_order_no,
                                            qcm.vw_code,
                                            bm.brand_name,
                                            led.Ac_name AS buyer_name,
                                            cpim.style_no,
                                            bpom.sam,
                                            bpom.order_rate,
                                            sd.size_name,
                                            qcm.line_no as line_name,
                                            msm.mainstyle_name,
                                            cm.color_name,
                                            qcd.status_id,
                                            (
                                                SELECT SUM(qcd2.size_qty)
                                                FROM quality_control_detail qcd2
                                                INNER JOIN quality_control_master qcm2 ON qcm2.QualityControlId = qcd2.QualityControlId
                                                WHERE qcm2.vw_code = qcm.vw_code
                                                  AND qcd2.size_id = qcd.size_id
                                                  AND qcm2.color_id = qcm.color_id
                                                  AND qcd2.status_id = 1
                                                  AND qcm2.QualityControlDate = qcm.QualityControlDate
                                            ) AS pass_qty,
                                            (
                                                SELECT SUM(qcd3.size_qty)
                                                FROM quality_control_detail qcd3
                                                INNER JOIN quality_control_master qcm3 ON qcm3.QualityControlId = qcd3.QualityControlId
                                                WHERE qcm3.vw_code = qcm.vw_code
                                                  AND qcd3.size_id = qcd.size_id
                                                  AND qcm3.color_id = qcm.color_id
                                                  AND qcd3.status_id = 2
                                                  AND qcm3.QualityControlDate = qcm.QualityControlDate
                                            ) AS reject_qty,
                                            NULL AS alter_qty
                                        FROM quality_control_master qcm
                                        LEFT JOIN quality_control_detail qcd ON qcd.QualityControlId = qcm.QualityControlId 
                                        LEFT JOIN buyer_purchse_order_master bpom ON bpom.tr_code = qcm.sales_order_no
                                        LEFT JOIN cut_panel_issue_master cpim ON cpim.vw_code = qcm.vw_code
                                        LEFT JOIN ledger_master led ON led.Ac_code = bpom.Ac_code
                                        LEFT JOIN size_detail sd ON sd.size_id = qcd.size_id 
                                        LEFT JOIN color_master cm ON cm.color_id = qcm.color_id
                                        LEFT JOIN main_style_master msm ON msm.mainstyle_id = bpom.mainstyle_id
                                        LEFT JOIN brand_master bm ON bm.brand_id = bpom.brand_id
                                        LEFT JOIN sales_order_costing_master socm ON socm.sales_order_no = bpom.tr_code
                                        WHERE 1=1 ".$filter."
                                        GROUP BY  qcd.status_id,qcm.color_id, qcd.size_id
                                        
                                        UNION ALL
                                        
                                        SELECT 
                                            dso.dhu_so_Name,
                                            qad.QualityControlId,
                                            qad.size_id,
                                            qad.operationId,
                                            dsd.dhu_sdt_Name as operation,
                                            qad.defectId,
                                            qcm.QualityControlDate,
                                            qcm.sales_order_no,
                                            qcm.vw_code,
                                            bm.brand_name,
                                            led.Ac_name AS buyer_name,
                                            cpim.style_no,
                                            bpom.sam,
                                            bpom.order_rate,
                                            sd.size_name,
                                            qcm.line_no as line_name,
                                            msm.mainstyle_name,
                                            cm.color_name,
                                            NULL AS status_id,
                                            NULL AS pass_qty,
                                            NULL AS reject_qty,
                                            (
                                                SELECT SUM(qad2.size_qty)
                                                FROM quality_control_alter_detail qad2
                                                INNER JOIN quality_control_master qcm2 ON qcm2.QualityControlId = qad2.QualityControlId
                                                WHERE qcm2.vw_code = qcm.vw_code
                                                  AND qad2.size_id = qad.size_id
                                                  AND qcm2.color_id = qcm.color_id
                                                  AND qad2.defectId = qad.defectId
                                                  AND qad2.operationId = qad.operationId
                                                  AND qcm2.QualityControlDate = qcm.QualityControlDate
                                            ) AS alter_qty
                                        FROM quality_control_master qcm
                                        LEFT JOIN quality_control_alter_detail qad ON qad.QualityControlId = qcm.QualityControlId
                                        LEFT JOIN dhu_stiching_operation dso ON dso.dhu_so_Id = qad.defectId
                                        LEFT JOIN dhu_stiching_defect_type dsd ON dsd.dhu_sdt_Id = qad.operationId
                                        LEFT JOIN buyer_purchse_order_master bpom ON bpom.tr_code = qcm.sales_order_no
                                        LEFT JOIN cut_panel_issue_master cpim ON cpim.vw_code = qcm.vw_code
                                        LEFT JOIN ledger_master led ON led.Ac_code = bpom.Ac_code
                                        LEFT JOIN size_detail sd ON sd.size_id = qad.size_id
                                        LEFT JOIN color_master cm ON cm.color_id = qcm.color_id
                                        LEFT JOIN main_style_master msm ON msm.mainstyle_id = bpom.mainstyle_id
                                        LEFT JOIN brand_master bm ON bm.brand_id = bpom.brand_id
                                        LEFT JOIN sales_order_costing_master socm ON socm.sales_order_no = bpom.tr_code
                                        WHERE 1=1 ".$filter."
                                        GROUP BY qcm.color_id, qad.size_id, qad.operationId, qad.defectId");
                    
            return Datatables::of($QualityControlList)
            ->addColumn('srno',function ($row) use ($srno) 
            {
                $srno = $srno + 1;
                return $srno;
            })
            ->addColumn('line_no',function ($row) 
            {
                $line_no = $row->line_name;
                
                return $line_no;
            })
            ->addColumn('qty',function ($row) 
            {
                if($row->status_id == 1)
                {
                    $qty = $row->pass_qty;
                }
                else if($row->status_id == 2)
                { 
                    $qty = $row->reject_qty;
                }
                else
                {
                    $qty =  $row->alter_qty;
                }
                
                return $qty;
            })
            ->addColumn('type',function ($row) 
            {
                if($row->status_id == 1)
                {
                    $type = "Pass";
                }
                else if($row->status_id == 2)
                { 
                    $type = "Reject";
                }
                else
                {
                    $type = "Alter";
                }
                
                return $type;
            })
            ->addColumn('alter_type',function ($row) 
            { 
                if($row->status_id == 1)
                {
                    $alter_type = '';
                }
                else if($row->status_id == 2)
                { 
                    $alter_type = '';
                }
                else
                {
                    $alter_type = $row->dhu_so_Name;
                } 
                
                return $alter_type;
            })
            ->addColumn('Minutes',function ($row) 
            {
                if($row->status_id == 1)  
                {
                    $Minutes = number_format($row->pass_qty  * $row->sam,2); 
                }
                else if($row->status_id == 2)
                { 
                    $Minutes = "";
                }
                else
                {
                    $Minutes = "";
                } 
               
                return  $Minutes;
            })
             ->rawColumns(['srno','line_no','Minutes','qty'])
             
             ->make(true);
    
            }
            
          return view('QualityControlReport',compact('fromDate','toDate','SalesOrderList','VendorWorkOrderList','LedgerList','BrandList','sales_order_no','vw_code','Ac_code','brand_id','line_id'));
        
    }
}
