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
use App\Models\LineModel;
use Session;
use DataTables;
use App\Models\SourceModel;
use App\Models\DestinationModel;
setlocale(LC_MONETARY, 'en_IN'); 
date_default_timezone_set('Asia/Calcutta');

class StitchingInhouseMasterController extends Controller
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
        ->where('form_id', '104')
        ->first();
        $vendorId=Session::get('vendorId');
        $user_type=Session::get('user_type');
     
        if( $vendorId==56 && $user_type!=6)
         {
            if( $request->page == 1)
            {
                $StitchingInhouseMasterList = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_master.sales_order_no')
                ->where('stitching_inhouse_master.delflag','=', '0')
                ->get(['stitching_inhouse_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','buyer_purchse_order_master.sam','line_master.line_name']);
            }
            else
            {
                $StitchingInhouseMasterList = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_master.sales_order_no')
                ->where('stitching_inhouse_master.delflag','=', '0')
                ->where('stitching_inhouse_master.sti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                ->get(['stitching_inhouse_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','buyer_purchse_order_master.sam','line_master.line_name']);
            }
                if ($request->ajax()) 
                {
                    return Datatables::of($StitchingInhouseMasterList)
                    ->addIndexColumn()
                    ->addColumn('sti_code1',function ($row) {
                
                         $sti_codeData =substr($row->sti_code,4,15);
                
                         return $sti_codeData;
                    }) 
                    ->addColumn('updated_at',function ($row) {
                
                         $updated_at = date("d-m-Y", strtotime($row->updated_at));
                
                         return $updated_at;
                    }) 
                    ->addColumn('total_value',function ($row) {
                
                         $total_value = $row->total_qty * $row->sam;
                 
                         return round($total_value,2);
                    }) 
                    ->addColumn('action1', function ($row) 
                    {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="StitchingGRNPrint/'.$row->sti_code.'" title="print">
                                    <i class="fas fa-print"></i>
                                    </a>';
                        return $btn1;
                    })
                    ->addColumn('action2', function ($row) use ($chekform,$user_type)
                    {
                        $today = date('Y-m-d'); // Current date in Y-m-d format
                        $entryDate = date('Y-m-d', strtotime($row->sti_date)); // Convert sti_date to Y-m-d format

                        if ($chekform->edit_access == 1 && $entryDate == $today || $user_type == 1) 
                        {  
                            $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('StitchingInhouse.edit', $row->sti_code).'" >
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
                 
                        if($chekform->delete_access==1)
                        {      
                 
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->sti_code.'"  data-route="'.route('StitchingInhouse.destroy', $row->sti_code).'"><i class="fas fa-trash"></i></a>'; 
                        }  
                        else
                        {
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                       
                        }
                        return $btn4;
                    })
                    ->rawColumns(['action1','action2','action3','total_value','updated_at'])
            
                    ->make(true);
                }
         }
         else if($vendorId!=56 && $user_type==6)
         { 
           
            if( $request->page == 1)
            {
                $StitchingInhouseMasterList = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_master.sales_order_no')
                ->where('stitching_inhouse_master.delflag','=', '0')->where( 'stitching_inhouse_master.vendorId',$vendorId)
                ->get(['stitching_inhouse_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','buyer_purchse_order_master.sam','line_master.line_name']);
                
            }
            else
            {
                $StitchingInhouseMasterList = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_master.sales_order_no')
                ->where('stitching_inhouse_master.delflag','=', '0')
                ->where( 'stitching_inhouse_master.vendorId',$vendorId)
                ->where('stitching_inhouse_master.sti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                ->get(['stitching_inhouse_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','buyer_purchse_order_master.sam','line_master.line_name']);
            }
                if ($request->ajax()) 
                {
                    return Datatables::of($StitchingInhouseMasterList)
                    ->addIndexColumn()
                    ->addColumn('sti_code1',function ($row) {
                
                         $sti_codeData =substr($row->sti_code,4,15);
                
                         return $sti_codeData;
                    }) 
                    ->addColumn('updated_at',function ($row) {
                
                         $updated_at = date("d-m-Y", strtotime($row->updated_at));
                
                         return $updated_at;
                    }) 
                    ->addColumn('total_value',function ($row) {
                
                         $total_value = $row->total_qty * $row->sam;
                
                         return round($total_value,2);
                    }) 
                    ->addColumn('action1', function ($row) 
                    {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="StitchingGRNPrint/'.$row->sti_code.'" title="print">
                                    <i class="fas fa-print"></i>
                                    </a>';
                        return $btn1;
                    })
                    ->addColumn('action2', function ($row) use ($chekform)
                    {
                        if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                        {  
                            $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('StitchingInhouse.edit', $row->sti_code).'" >
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
                 
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->sti_code.'"  data-route="'.route('StitchingInhouse.destroy', $row->sti_code).'"><i class="fas fa-trash"></i></a>'; 
                        }  
                        else
                        {
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                       
                        }
                        return $btn4;
                    })
                    ->rawColumns(['action1','action2','action3','total_value','updated_at'])
            
                    ->make(true);
                }
         }
         else  
         { 
           
            if( $request->page == 1)
            {
                $StitchingInhouseMasterList = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_master.sales_order_no')
                ->where('stitching_inhouse_master.delflag','=', '0')->where( 'stitching_inhouse_master.vendorId',$vendorId)
                ->get(['stitching_inhouse_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','buyer_purchse_order_master.sam','line_master.line_name']);
                
            }
            else
            {
                $StitchingInhouseMasterList = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_master.vendorId', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_master.line_id', 'left outer')
                ->leftjoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_master.sales_order_no')
                ->where('stitching_inhouse_master.delflag','=', '0')
                ->where( 'stitching_inhouse_master.vendorId',$vendorId)
                ->where('stitching_inhouse_master.sti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
                ->get(['stitching_inhouse_master.*','usermaster.username','L1.ac_short_name as Ac_name','L2.ac_short_name as vendor_name','buyer_purchse_order_master.sam','line_master.line_name']);
            }
                if ($request->ajax()) 
                {
                    return Datatables::of($StitchingInhouseMasterList)
                    ->addIndexColumn()
                    ->addColumn('sti_code1',function ($row) {
                
                         $sti_codeData =substr($row->sti_code,4,15);
                
                         return $sti_codeData;
                    }) 
                    ->addColumn('updated_at',function ($row) {
                
                         $updated_at = date("d-m-Y", strtotime($row->updated_at));
                
                         return $updated_at;
                    }) 
                    ->addColumn('total_value',function ($row) {
                
                         $total_value = $row->total_qty * $row->sam;
                
                         return round($total_value,2);
                    }) 
                    ->addColumn('action1', function ($row) 
                    {
                        $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="StitchingGRNPrint/'.$row->sti_code.'" title="print">
                                    <i class="fas fa-print"></i>
                                    </a>';
                        return $btn1;
                    })
                    ->addColumn('action2', function ($row) use ($chekform)
                    {
                        if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                        {  
                            $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('StitchingInhouse.edit', $row->sti_code).'" >
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
                 
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->sti_code.'"  data-route="'.route('StitchingInhouse.destroy', $row->sti_code).'"><i class="fas fa-trash"></i></a>'; 
                        }  
                        else
                        {
                            $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                       
                        }
                        return $btn4;
                    })
                    ->rawColumns(['action1','action2','action3','total_value','updated_at'])
            
                    ->make(true);
                }
         }
         
        return view('StitchingInhouseMasterList', compact('chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='StitchingInhouse'");
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
        }
      
        return view('StitchingInhouseMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList','BuyerList',  'VendorWorkOrderList','Ledger',  'counter_number'));
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
            ->where('type','=','StitchingInhouse')
            ->where('firm_id','=',1)
            ->first();
            $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
            
            
            
            $this->validate($request, [
                'sti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'vendorId'=>'required',
                'mainstyle_id'=>'required'
                       
            ]);
         
         
            $data1=array(
                'sti_code'=>$TrNo, 
                'sti_date'=>$request->sti_date, 
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'vendorId'=>$request->vendorId,
                'line_id'=>$request->line_id,
                'total_workers'=>$request->total_workers,
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
                'total_helpers'=>$request->total_helpers
                
            );
            $salesOrderData = DB::table('buyer_purchse_order_master')->select('sam')->where('tr_code','=',$request->sales_order_no)->first();
            $sam = isset($salesOrderData->sam) ? $salesOrderData->sam : 0;
            StitchingInhouseMasterModel::insert($data1);
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='StitchingInhouse'");
        
            $color_id= $request->input('color_id');
            $data2 = array();
            $data3 = array();
            $total_pass_qty = 0; 
            $total_minutes = 0;
            $sti_date = $request->sti_date;
            
            if(count($color_id)>0)
            {   
            
            for($x=0; $x<count($color_id); $x++)
            {
            //   if($request->size_qty_total[$x]>0)
            //   {
                        $data2[]=array(
              
                        'sti_code'=>$TrNo,
                        'sti_date'=>$request->sti_date,
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
                      
                            'sti_code'=>$TrNo, 
                            'sti_date'=>$request->sti_date, 
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
                 // } 
                }
              if($data2 != "")
              {
                    StitchingInhouseDetailModel::insert($data2);
              }
              if($data3 != "")
              {
                    StitchingInhouseSizeDetailModel::insert($data3);
              } 
        } 
        
        $InsertSizeData=DB::select('call AddSizeQtyFromStitchingInhouse("'.$TrNo.'")');
        
        $defectData = DB::SELECT("SELECT sum(defect_qty) as total_defect FROM dhu_details INNER JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code WHERE dhu_master.vw_code='".$request->vw_code."' AND dhu_master.line_no=".$request->line_id." AND dhu_master.fg_id=".$request->fg_id." AND dhu_master.vendorId=".$request->vendorId);
        
        $totalPass = $total_pass_qty;
        $totalReject = 0;
        $totalProduction = $totalPass;
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
            'productionDate'=>$sti_date, 
            'branch_id'=>$request->vendorId,
            'userId'=>$request->userId, 
            'delflag'=>0, 
        );
        $anotherDatabaseDetailArr=array(
               
            'productionDate'=>$sti_date,
            'sam'=>$avgSAM, 
            'pass'=>$totalPass, 
            'reject'=>0, 
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
            'productionDate'=>$sti_date,
            'sam'=>$avgSAM, 
            'pass'=>$totalPass, 
            'reject'=>0, 
            'totalProduction'=>$totalProduction,
            'defect'=>$totalDefect, 
            'branch_id'=>$request->vendorId,
            'deptCostId'=>$request->line_id, 
            'userId'=>$request->userId, 
            'mainstyle_id'=>$request->mainstyle_id, 
            'sti_code'=>$TrNo, 
            'sti_type'=>1,  
        );
        DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr); 
        SourceModel::on('mysql');
        
        return redirect()->route('StitchingInhouse.index')->with('message', 'Data Saved Succesfully');  
      
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
    
    public function newSewingReport(Request $request)
    {
        
        ini_set('memory_limit', '10G'); 
        $srno = 1;
        $tempo = "";
        $temp = "";
        $tempo1 = "";
        $temp1 = "";
        $tempo2 = "";
        $temp2 = "";
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate = isset($request->toDate) ? $request->toDate : date("Y-m-d");
        if ($request->ajax()) 
        {  
            if($fromDate != "" && $toDate != "")
            {
               $StitchingInhouseMasterList = DB::table('stitching_inhouse_size_detail2') 
                    ->join('stitching_inhouse_master', 'stitching_inhouse_master.sti_code', '=', 'stitching_inhouse_size_detail2.sti_code')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_size_detail2.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_size_detail2.vendorId', 'left outer')
                    ->join('size_detail', 'size_detail.size_id', '=', 'stitching_inhouse_size_detail2.size_id', 'left outer')
                    ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_size_detail2.line_id', 'left outer')
                    ->join('color_master', 'color_master.color_id', '=', 'stitching_inhouse_size_detail2.color_id',  'left outer')
                    ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'stitching_inhouse_size_detail2.mainstyle_id', 'left outer')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no', 'left outer')
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code') 
                    ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fromDate, $toDate])
                    ->groupBy('stitching_inhouse_size_detail2.sti_code','stitching_inhouse_size_detail2.color_id','stitching_inhouse_size_detail2.size_id')
                    ->get(['stitching_inhouse_size_detail2.*','stitching_inhouse_master.total_workers','brand_master.brand_name',
                    'L1.Ac_name as buyer_name','L2.Ac_name as vendor_name','buyer_purchse_order_master.sam','buyer_purchse_order_master.order_rate','size_detail.size_name',
                    'line_master.line_name','main_style_master.mainstyle_name','color_master.color_name','sales_order_costing_master.production_value',
                    'sales_order_costing_master.total_cost_value','sales_order_costing_master.other_value']);
            }
            else
            {
               $StitchingInhouseMasterList = DB::table('stitching_inhouse_size_detail2') 
                    ->join('stitching_inhouse_master', 'stitching_inhouse_master.sti_code', '=', 'stitching_inhouse_size_detail2.sti_code')
                    ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_size_detail2.Ac_code', 'left outer')
                    ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_size_detail2.vendorId', 'left outer')
                    ->join('size_detail', 'size_detail.size_id', '=', 'stitching_inhouse_size_detail2.size_id', 'left outer')
                    ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_size_detail2.line_id', 'left outer')
                    ->join('color_master', 'color_master.color_id', '=', 'stitching_inhouse_size_detail2.color_id',  'left outer')
                    ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'stitching_inhouse_size_detail2.mainstyle_id', 'left outer')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no', 'left outer')
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                    ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code') 
                    ->groupBy('stitching_inhouse_size_detail2.sti_code','stitching_inhouse_size_detail2.color_id','stitching_inhouse_size_detail2.size_id')
                    ->get(['stitching_inhouse_size_detail2.*','stitching_inhouse_master.total_workers','brand_master.brand_name', 
                    'L1.Ac_name as buyer_name','L2.Ac_name as vendor_name','buyer_purchse_order_master.sam','buyer_purchse_order_master.order_rate','size_detail.size_name',
                    'line_master.line_name','main_style_master.mainstyle_name','color_master.color_name','sales_order_costing_master.production_value',
                    'sales_order_costing_master.total_cost_value','sales_order_costing_master.other_value']);
            }
            return Datatables::of($StitchingInhouseMasterList)
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
            ->addColumn('fob_rate',function ($row) 
            {
                if($row->total_cost_value == 0)
                {
                    $fob_rate =  number_format($row->order_rate,4);
                }
                else
                {
                    $fob_rate = number_format($row->total_cost_value,4);
                }
                return $fob_rate;
            })
            ->addColumn('Minutes',function ($row) 
            {
                $Minutes = $row->size_qty * $row->sam;
               
                return number_format($Minutes,2);
            })
            ->addColumn('valueOfProduction',function ($row) 
            {
                if($row->total_cost_value == 0)
                {
                    $fob_rate =  $row->order_rate;
                }
                else
                {
                    $fob_rate = $row->total_cost_value;
                }
                $valueOfProduction = $row->size_qty * $fob_rate;
               
                return number_format($valueOfProduction,2);
            })
            ->addColumn('CMOHP',function ($row) 
            {
                $profit_value=0.0;
                $profit_value=  ($row->order_rate - $row->total_cost_value);
                
                $cmohp1 = $row->production_value + $profit_value + $row->other_value;
                $cmohp2 = $row->sam;
                if($cmohp1 && $cmohp2)
                {
                    $cmohp = $cmohp1/$cmohp2;
                }
                else
                {
                    $cmohp = 0;
                } 
               
                return number_format($cmohp,4);
            })
            ->addColumn('CMOHP_Value',function ($row) 
            {
                $profit_value=0.0;
                $profit_value=  ($row->order_rate - $row->total_cost_value);
                
                $cmohp1 = $row->production_value + $profit_value + $row->other_value;
                $cmohp2 = $row->sam;
                if($cmohp1 && $cmohp2)
                {
                    $cmohp = $cmohp1/$cmohp2;
                }
                else
                {
                    $cmohp = 0;
                } 
               
                $CMOHP_Value = $cmohp * ($row->size_qty * $row->sam);
               
                return number_format($CMOHP_Value,2);
            })
             ->rawColumns(['srno','line_no','Minutes','valueOfProduction','CMOHP','CMOHP_Value','fob_rate'])
             
             ->make(true);
    
            }
            
          return view('newSewingReport',compact('fromDate','toDate'));
        
    }
    public function StitchingGRNDashboard(Request $request)
    {
        
        ini_set('memory_limit', '10G'); 
        
        $srno = 1;
        $tempo = "";
        $temp = "";
        $tempo1 = "";
        $temp1 = "";
        $tempo2 = "";
        $temp2 = "";
        $total_workers1 = 0;
        $total_helpers1 = 0;
        $total_manpower1 = 0;
        
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate = isset($request->toDate) ? $request->toDate : date("Y-m-d");
        
        if ($request->ajax()) 
        {  
          
          $StitchingInhouseMasterList = DB::table('stitching_inhouse_size_detail2') 
                ->join('stitching_inhouse_master', 'stitching_inhouse_master.sti_code', '=', 'stitching_inhouse_size_detail2.sti_code')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_size_detail2.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_size_detail2.vendorId', 'left outer')
                ->join('size_detail', 'size_detail.size_id', '=', 'stitching_inhouse_size_detail2.size_id', 'left outer')
                ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_size_detail2.line_id', 'left outer')
                ->join('color_master', 'color_master.color_id', '=', 'stitching_inhouse_size_detail2.color_id',  'left outer')
                ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'stitching_inhouse_size_detail2.mainstyle_id', 'left outer')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no', 'left outer')
                ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id', 'left outer')
                ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fromDate, $toDate])
                ->groupBy('stitching_inhouse_size_detail2.vendorId','stitching_inhouse_size_detail2.sti_code','stitching_inhouse_size_detail2.color_id','stitching_inhouse_size_detail2.size_id')
                ->get(['stitching_inhouse_size_detail2.*','stitching_inhouse_master.total_workers','brand_master.brand_name',
                'L1.Ac_name as buyer_name','L2.Ac_name as vendor_name','buyer_purchse_order_master.sam','size_detail.size_name',
                'line_master.line_name','main_style_master.mainstyle_name','color_master.color_name','job_status_master.job_status_name',DB::raw('sum(total_workers) as total_workers'),DB::raw('sum(total_helpers) as total_helpers')]);
          
            // $minSizeSub = DB::table('stitching_inhouse_size_detail2 as s2')
            //     ->select(DB::raw('MIN(s2.size_id) as min_size_id'), 's2.sti_code')
            //     ->groupBy('s2.sti_code');
            
            // $StitchingInhouseMasterList = DB::table('stitching_inhouse_size_detail2')
            //     ->join('stitching_inhouse_master', 'stitching_inhouse_master.sti_code', '=', 'stitching_inhouse_size_detail2.sti_code')
            //     ->leftJoin('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_size_detail2.Ac_code')
            //     ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_size_detail2.vendorId')
            //     ->leftJoin('size_detail', 'size_detail.size_id', '=', 'stitching_inhouse_size_detail2.size_id')
            //     ->leftJoin('line_master', 'line_master.line_id', '=', 'stitching_inhouse_size_detail2.line_id')
            //     ->leftJoin('color_master', 'color_master.color_id', '=', 'stitching_inhouse_size_detail2.color_id')
            //     ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'stitching_inhouse_size_detail2.mainstyle_id')
            //     ->leftJoin('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
            //     ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
            //     ->leftJoin('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
            //     ->leftJoinSub($minSizeSub, 'min_sizes', function($join) {
            //         $join->on('min_sizes.sti_code', '=', 'stitching_inhouse_size_detail2.sti_code');
            //     })
            //     ->select([
            //         'stitching_inhouse_size_detail2.sti_code',
            //         'stitching_inhouse_size_detail2.sti_date',
            //         'stitching_inhouse_size_detail2.sales_order_no',
            //         'stitching_inhouse_size_detail2.mainstyle_id',
            //         'stitching_inhouse_size_detail2.color_id',  
            //         'stitching_inhouse_size_detail2.vw_code',   
            //         'stitching_inhouse_size_detail2.style_no',  
            //         'stitching_inhouse_size_detail2.vendorId',
            //         'stitching_inhouse_size_detail2.Ac_code',
            //         'stitching_inhouse_size_detail2.line_id',
            //         'stitching_inhouse_size_detail2.size_id',
            //         'size_detail.size_name',
            //         'stitching_inhouse_size_detail2.size_qty',
            //         DB::raw('L1.Ac_name as buyer_name'),
            //         DB::raw('L2.Ac_name as vendor_name'),
            //         DB::raw('buyer_purchse_order_master.sam'),
            //         DB::raw('line_master.line_name'),
            //         DB::raw('main_style_master.mainstyle_name'),
            //         DB::raw('color_master.color_name'),
            //         DB::raw('brand_master.brand_name'),
            //         DB::raw('job_status_master.job_status_name'),
            //         DB::raw('CASE WHEN stitching_inhouse_size_detail2.size_id = min_sizes.min_size_id THEN stitching_inhouse_master.total_workers ELSE 0 END as total_workers'),
            //         DB::raw('CASE WHEN stitching_inhouse_size_detail2.size_id = min_sizes.min_size_id THEN stitching_inhouse_master.total_helpers ELSE 0 END as total_helpers'),
            //     ])
            //     ->orderBy('stitching_inhouse_size_detail2.sti_code')
            //     ->orderBy('stitching_inhouse_size_detail2.size_id')
            //     ->get();


            return Datatables::of($StitchingInhouseMasterList)
            ->addColumn('srno',function ($row) use ($srno) 
            {
                $srno = $srno + 1;
                return $srno;
            })
            ->addColumn('line_no',function ($row) 
            {
                $line_no = $row->line_id."-".$row->line_name;
                
                return $line_no;
            })
            ->addColumn('Minutes',function ($row) 
            {
                $Minutes = $row->size_qty * $row->sam;
               
                return number_format($Minutes,2);
            })
            ->addColumn('total_workers',function ($row) use ($tempo,$temp) 
            {
                 
                if($temp != $row->sales_order_no)
                {
                    $total_workers1 = $row->total_workers;
                    $tempo = $row->sti_code;
                }
                else
                {
                    $total_workers1 = 0;
                }
                
                $temp = $total_workers1;
                //$total_workers1 = $row->total_workers;
                return $total_workers1;
            })
            ->addColumn('total_helpers',function ($row) use ($tempo1,$temp1,$total_helpers1) 
            {
                if($temp1 != $row->sales_order_no)
                {
                    $total_helpers1 = $row->total_helpers;
                    $tempo1 = $row->sti_code;
                }
                else
                {
                    $total_helpers1 = 0;
                }
                
                $temp1 = $total_helpers1;
                //$total_helpers1 = $row->total_helpers;
                return $total_helpers1; 
            })
            ->addColumn('total_manpower',function ($row) use ($tempo2,$temp2,$total_manpower1) 
            { 
                if($temp2 != $row->sales_order_no) 
                {
                    $total_manpower1 = $row->total_workers + $row->total_helpers;
                    $tempo2 = $row->sti_code;
                }
                else
                {
                    $total_manpower1 = 0;
                }
                
                $temp2 = $total_manpower1; 
                //$total_manpower1 = $row->total_workers + $row->total_helpers;
                return $total_manpower1;
            })
             ->rawColumns(['srno','line_no','Minutes','total_workers','total_helpers','total_manpower'])
             
             ->make(true);
    
            }
            
          return view('StitchingGRNDashboard', compact('fromDate','toDate'));
        
    }
    
    // public function StitchingGRNDashboard()
    // {
    //     $StitchingInhouseMasterList = DB::table('stitching_inhouse_size_detail2') 
    //     ->join('ledger_master as L1', 'L1.Ac_code', '=', 'stitching_inhouse_size_detail2.Ac_code', 'left outer')
    //     ->join('ledger_master as L2', 'L2.Ac_code', '=', 'stitching_inhouse_size_detail2.vendorId', 'left outer')
    //     ->join('size_detail', 'size_detail.size_id', '=', 'stitching_inhouse_size_detail2.size_id', 'left outer')
    //     ->join('line_master', 'line_master.line_id', '=', 'stitching_inhouse_size_detail2.line_id', 'left outer')
    //     ->join('color_master', 'color_master.color_id', '=', 'stitching_inhouse_size_detail2.color_id',  'left outer')
    //     ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'stitching_inhouse_size_detail2.mainstyle_id', 'left outer')
    //     ->join('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'stitching_inhouse_size_detail2.sales_order_no', 'left outer')
    //     ->get(['stitching_inhouse_size_detail2.*', 'L1.Ac_name','L2.Ac_name as vendor_name','sales_order_costing_master.sam','size_detail.size_name','line_master.line_name','main_style_master.mainstyle_name','color_master.color_name']);
   
    //     return view('StitchingGRNDashboard', compact('StitchingInhouseMasterList'));
    // }
   
    // public function StitchingGRNDashboardMD($vendorId,$DFilter)
    // {
    //     $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at
    //      FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
         
    //     if($DFilter == 'd')
    //     {
    //         $filterDate = " AND stitching_inhouse_size_detail2.sti_date =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    //     }
    //     else if($DFilter == 'm')
    //     {
    //         $filterDate = ' AND MONTH(stitching_inhouse_size_detail2.sti_date) = MONTH(CURRENT_DATE()) and YEAR(stitching_inhouse_size_detail2.sti_date)=YEAR(CURRENT_DATE()) AND stitching_inhouse_size_detail2.sti_date !="'.date('Y-m-d').'"';
    //     }
    //     else if($DFilter == 'y')
    //     {
    //         $filterDate = ' AND stitching_inhouse_size_detail2.sti_date between "'.$Financial_Year[0]->fdate.'" and NOW()';
    //     }
    //     else
    //     {
    //         $filterDate = "";
    //     }
        
    //     $StitchingInhouseMasterList = DB::select("SELECT stitching_inhouse_size_detail2.*, L1.Ac_name,L2.Ac_name as vendor_name,sales_order_costing_master.sam,size_detail.size_name,
    //             line_master.line_name,main_style_master.mainstyle_name,color_master.color_name FROM stitching_inhouse_size_detail2
    //             LEFT JOIN ledger_master as L1 ON L1.Ac_code = stitching_inhouse_size_detail2.Ac_code
    //             LEFT JOIN ledger_master as L2 ON L2.Ac_code = stitching_inhouse_size_detail2.vendorId
    //             LEFT JOIN size_detail ON size_detail.size_id = stitching_inhouse_size_detail2.size_id
    //             LEFT JOIN line_master ON line_master.line_id = stitching_inhouse_size_detail2.line_id
    //             LEFT JOIN color_master ON color_master.color_id = stitching_inhouse_size_detail2.color_id
    //             LEFT JOIN main_style_master ON main_style_master.mainstyle_id = stitching_inhouse_size_detail2.mainstyle_id
    //             LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = stitching_inhouse_size_detail2.sales_order_no
    //             WHERE stitching_inhouse_size_detail2.vendorId = ".$vendorId."".$filterDate);
    
    //     return view('StitchingGRNDashboard', compact('StitchingInhouseMasterList'));
    // }


    public function StitchingGRNDashboardMD(Request $request,$vendorId,$DFilter)
    {
        $srno = 1;
        
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at
         FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
         
        if($DFilter == 'd')
        {
            $filterDate = " AND stitching_inhouse_size_detail2.sti_date =  DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND MONTH(stitching_inhouse_size_detail2.sti_date) = MONTH(CURRENT_DATE()) and YEAR(stitching_inhouse_size_detail2.sti_date)=YEAR(CURRENT_DATE()) AND stitching_inhouse_size_detail2.sti_date !="'.date('Y-m-d').'"';
        }
        else if($DFilter == 'y')
        {
            $filterDate = ' AND stitching_inhouse_size_detail2.sti_date between "'.$Financial_Year[0]->fdate.'" and NOW()';
        }
        else
        {
            $filterDate = "";
        }
        
        if ($request->ajax()) 
        { 
           //DB::enableQueryLog();
           $StitchingInhouseMasterList = DB::select("SELECT stitching_inhouse_size_detail2.*,stitching_inhouse_master.total_workers, L1.Ac_name,L2.Ac_name as vendor_name,sales_order_costing_master.sam,size_detail.size_name,
                line_master.line_name,main_style_master.mainstyle_name,color_master.color_name FROM stitching_inhouse_size_detail2
                LEFT JOIN ledger_master as L1 ON L1.Ac_code = stitching_inhouse_size_detail2.Ac_code
                LEFT JOIN ledger_master as L2 ON L2.Ac_code = stitching_inhouse_size_detail2.vendorId
                LEFT JOIN size_detail ON size_detail.size_id = stitching_inhouse_size_detail2.size_id
                LEFT JOIN line_master ON line_master.line_id = stitching_inhouse_size_detail2.line_id
                LEFT JOIN color_master ON color_master.color_id = stitching_inhouse_size_detail2.color_id
                LEFT JOIN main_style_master ON main_style_master.mainstyle_id = stitching_inhouse_size_detail2.mainstyle_id
                LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = stitching_inhouse_size_detail2.sales_order_no
                LEFT JOIN stitching_inhouse_master ON stitching_inhouse_master.sti_code = stitching_inhouse_size_detail2.sti_code
                WHERE stitching_inhouse_size_detail2.size_qty != 0 AND stitching_inhouse_size_detail2.vendorId = ".$vendorId."".$filterDate);
          //dd(DB::getQueryLog());
            return Datatables::of($StitchingInhouseMasterList)
            ->addColumn('srno',function ($row) use ($srno) 
            {
                $srno = $srno + 1;
                return $srno;
            })
            ->addColumn('line_no',function ($row) 
            {
                $line_no = $row->line_id."-".$row->line_name;
                
                return $line_no;
            })
            ->addColumn('Minutes',function ($row) 
            {
                
                $Minutes = $row->size_qty * $row->sam;
               
                return round($Minutes,2);
            })
            ->addColumn('total_operator1',function ($row) 
            {
                
                $total_operator1 = $row->total_workers;
               
                return $total_operator1;
            })
             ->rawColumns(['srno','line_no','Minutes','total_operator1'])
             
             ->make(true);
    
            }
            
          return view('StitchingGRNDashboard');
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
        //   DB::enableQueryLog();
        $StitchingInhouseMasterList = StitchingInhouseMasterModel::find($id);
        
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
         
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$StitchingInhouseMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$StitchingInhouseMasterList->sales_order_no)->DISTINCT()->get();
        $LineList = LineModel::where('line_master.delflag','=', '0')->where('line_master.Ac_code','=', $StitchingInhouseMasterList->vendorId)->get();
       
        
        //--------
        
        $vendorId=Session::get('vendorId');
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
                 $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
        ->whereNotIn('vendor_work_order_master.vw_code',function($query){
        $query->select('stitching_inhouse_master.vw_code')->from('stitching_inhouse_master');
        });
                $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        else if(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
            
             $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_work_order_master.vw_code',function($query){
            $query->select('stitching_inhouse_master.vw_code')->from('stitching_inhouse_master');
            });
              $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
        else 
        {  
            
             $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_work_order_master.vw_code',function($query){
            $query->select('stitching_inhouse_master.vw_code')->from('stitching_inhouse_master');
            });
              $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
        $StitchingInhouseDetailList =StitchingInhouseDetailModel::where('stitching_inhouse_detail.sti_code','=', $StitchingInhouseMasterList->sti_code)->get();
        $S2=StitchingInhouseMasterModel::select('vw_code','sales_order_no')->where('vw_code',$StitchingInhouseMasterList->vw_code);
        $VendorWorkOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($StitchingInhouseMasterList->sales_order_no);
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
      color_master.color_id=vendor_work_order_size_detail.color_id where vw_code='".$StitchingInhouseMasterList->vw_code."'
      group by vendor_work_order_size_detail.color_id");
        
        return view('StitchingInhouseMasterEdit',compact('StitchingInhouseDetailList','ColorList' ,'BuyerList',  'LineList', 'MasterdataList','SizeDetailList','StitchingInhouseMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger' ));
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
             
                'sti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
               
            ]);
         
          
        $data1=array(
                   
                'sti_code'=>$request->sti_code, 
                'sti_date'=>$request->sti_date, 
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'vendorId'=>$request->vendorId,
                'line_id'=>$request->line_id,
                'total_workers'=>$request->total_workers,
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
                'total_helpers'=>$request->total_helpers
                 
            );
        //   DB::enableQueryLog();   
        $StitchingInhouseList = StitchingInhouseMasterModel::findOrFail($request->sti_code); 
        //  $query = DB::getQueryLog();
        //         $query = end($query);
        //         dd($query);
        $StitchingInhouseList->fill($data1)->save();
        $salesOrderData = DB::table('buyer_purchse_order_master')->select('sam')->where('tr_code','=',$request->sales_order_no)->first();
        $sam = isset($salesOrderData->sam) ? $salesOrderData->sam : 0;
        
        $total_pass_qty = 0; 
        $total_minutes = 0;
         
        DB::table('stitching_inhouse_size_detail')->where('sti_code', $request->input('sti_code'))->delete();
        DB::table('stitching_inhouse_size_detail2')->where('sti_code', $request->input('sti_code'))->delete();
        DB::table('stitching_inhouse_detail')->where('sti_code', $request->input('sti_code'))->delete();
        $data2 = array();
        $data3 = array();
        $color_id= $request->input('color_id');
        if (is_array($color_id) && count($color_id) > 0) 
        {   
            for($x=0; $x<count($color_id); $x++) 
            {
                // if($request->size_qty_total[$x]>0)
                // {
                    $data2[]=array( 
                        'sti_code'=>$request->sti_code,
                        'sti_date'=>$request->sti_date,
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
                  
                        'sti_code'=>$request->sti_code,
                        'sti_date'=>$request->sti_date, 
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
                      //}  
                    } 
                  if($data2 != "")
                  {
                        StitchingInhouseDetailModel::insert($data2);
                  }
                  if($data3 != "")
                  {
                        StitchingInhouseSizeDetailModel::insert($data3);
                  }
                  
            } 
           
            $InsertSizeData=DB::select('call AddSizeQtyFromStitchingInhouse("'.$request->sti_code.'")');
                  
        $defectData = DB::SELECT("SELECT sum(defect_qty) as total_defect FROM dhu_details INNER JOIN dhu_master ON dhu_master.dhu_code = dhu_details.dhu_code WHERE dhu_master.vw_code='".$request->vw_code."' AND dhu_master.line_no=".$request->line_id." AND dhu_master.fg_id=".$request->fg_id." AND dhu_master.vendorId=".$request->vendorId);
        
        $totalPass = $total_pass_qty;
        $totalReject = 0;
        $totalProduction = $totalPass;
        $totalDefect = isset($defectData[0]->total_defect) ? $defectData[0]->total_defect : 0;
        
        if($total_minutes > 0 && $totalProduction > 0)
        { 
            $avgSAM = ($total_minutes/$totalProduction);
        }
        else
        {
            $avgSAM = 0;
        }
        
        $productionData = DB::connection('hrms_database')->table('production_master')->select('proId','productionDate','branch_id','userId')->where('productionDate','=',$request->sti_date)->where('branch_id','=',$request->vendorId)->get();
          
        if(count($productionData) == 0)
        {
            $anotherDatabaseMasterArr=array( 
                'productionDate'=>$request->sti_date, 
                'branch_id'=>$request->vendorId,
                'userId'=>$request->userId, 
                'delflag'=>0, 
            );
            DB::connection('hrms_database')->table('production_master')->insert((array)$anotherDatabaseMasterArr);
            $proId = DB::connection('hrms_database')->table('production_master')->select('proId')->max('proId');
            
            $anotherDatabaseDetailArr=array(
                'proId' =>$proId,
                'productionDate'=>$request->sti_date,
                'sam'=>$avgSAM, 
                'pass'=>$totalPass, 
                'reject'=>$totalReject, 
                'totalProduction'=>$totalProduction,
                'defect'=>$totalDefect, 
                'branch_id'=>$request->vendorId,
                'deptCostId'=>$request->line_id, 
                'userId'=>$request->userId, 
                'mainstyle_id'=>$request->mainstyle_id, 
                'sti_code'=>$request->sti_code, 
                'sti_type'=>1
            );
            DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr); 
            SourceModel::on('mysql');     
        }
        else
        {
            
            $productionMasterData = DB::connection('hrms_database')->table('production_master')->select('proId','productionDate','branch_id','userId')->where('productionDate','=',$request->sti_date)->where('branch_id','=',$request->vendorId)->first();
          
            DB::connection('hrms_database')->table('production_detail')->where('branch_id','=',$request->vendorId)->where('sti_code','=',$request->sti_code)->delete(); 
            $anotherDatabaseDetailArr=array(
                'proId' =>$productionMasterData->proId,
                'productionDate'=>$request->sti_date,
                'sam'=>$avgSAM, 
                'pass'=>$totalPass, 
                'reject'=>$totalReject, 
                'totalProduction'=>$totalProduction,
                'defect'=>$totalDefect, 
                'branch_id'=>$request->vendorId,
                'deptCostId'=>$request->line_id, 
                'userId'=>$request->userId, 
                'mainstyle_id'=>$request->mainstyle_id,
                'sti_code'=>$request->sti_code,  
                'sti_type'=>1
            );
            //echo '<pre>'; print_r($anotherDatabaseDetailArr);exit;
            DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr);
            //DB::connection('hrms_database')->table('production_detail')->insert((array)$anotherDatabaseDetailArr); 
            SourceModel::on('mysql');
        } 
               
           
        return redirect()->route('StitchingInhouse.index')->with('message', 'Data Saved Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
   public function getVendorWorkOrderDetails(Request $request)
    { 
        $vw_code= $request->input('vw_code');
        $MasterdataList = DB::select("select Ac_code,sales_order_no, vendorId, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from vendor_work_order_master where vendor_work_order_master.delflag=0 and vw_code='".$vw_code."'");
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

  
  public function VW_GetOrderQty(Request $request)
  {
      // VW_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by VW_
      
      $VendorWorkOrderMasterList = VendorWorkOrderModel::find($request->vw_code);
    //   DB::enableQueryLog();  
      $VendorWorkOrderDetailList = VendorWorkOrderDetailModel::where('vw_code',$request->vw_code)->first();
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$VendorWorkOrderMasterList->sales_order_no)->first();
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
        //   DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT vendor_work_order_size_detail.item_code, vendor_work_order_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
      color_master.color_id=vendor_work_order_size_detail.color_id where vw_code='".$request->vw_code."'
      group by vendor_work_order_size_detail.color_id");
       

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
        //  DB::enableQueryLog();  
      $List = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
      color_master.color_id=stitching_inhouse_size_detail.color_id where 
      stitching_inhouse_size_detail.vw_code='".$request->vw_code."' and
      stitching_inhouse_size_detail.color_id='".$row->color_id."'
       ");

     
       $List1 = DB::select("SELECT cut_panel_issue_size_detail.color_id, color_name, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from cut_panel_issue_size_detail inner join color_master on 
          color_master.color_id=cut_panel_issue_size_detail.color_id where 
          cut_panel_issue_size_detail.vw_code='".$request->vw_code."' and
          cut_panel_issue_size_detail.color_id='".$row->color_id."'");
 
  
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
         
        if(isset($row->s1)) { $html.='<td>'.$s1.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) { $html.='<td>'.$s2.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) { $html.='<td>'.$s3.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) { $html.='<td>'.$s4.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) { $html.='<td>'.$s5.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) { $html.='<td>'.$s6.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) { $html.='<td>'.$s7.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) { $html.='<td>'.$s8.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) { $html.='<td>'.$s9.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) { $html.='<td>'.$s10.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) { $html.='<td>'.$s11.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) { $html.='<td>'.$s12.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) { $html.='<td>'.$s13.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) { $html.='<td>'.$s14.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) { $html.='<td>'.$s15.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) { $html.='<td>'.$s16.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) { $html.='<td>'.$s17.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) { $html.='<td>'.$s18.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) { $html.='<td>'.$s19.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) { $html.='<td>'.$s20.' <input style="width:80px; float:left;"  max="'.$List1[0]->size_qty_total.'"  name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';} 
          
          $zeroList = [];
          foreach ($SizeDetailList as $size) 
          { 
              $zeroList[] = 0;
          }
          
          $html.='<td>'.($List1[0]->size_qty_total-$List[0]->size_qty_total).' 
            <input type="hidden" name="overall_size_qty"  value="'.($List1[0]->size_qty_total-$List[0]->size_qty_total).'" class="overall_size_qty" style="width:80px; float:left;"  />
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" oninput="qtyCheck(this);" readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="'.(implode(',', $zeroList)).'" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$VendorWorkOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
           $html.='</tr>';

          $no=$no+1;
        }
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
  
   public function VW_CutPanelGetOrderQty(Request $request)
   {
      // VW_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by VW_
      
      $VendorWorkOrderMasterList = VendorWorkOrderModel::find($request->vw_code);
    //   DB::enableQueryLog();  
      $VendorWorkOrderDetailList = VendorWorkOrderDetailModel::where('vw_code',$request->vw_code)->first();
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$VendorWorkOrderMasterList->sales_order_no)->first();
   
      
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
      $MasterdataList = DB::select("SELECT vendor_work_order_size_detail.item_code, vendor_work_order_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
      color_master.color_id=vendor_work_order_size_detail.color_id where vw_code='".$request->vw_code."'
      group by vendor_work_order_size_detail.color_id");
       

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
            
        $ColorList = DB::table('color_master')->select('color_id', 'color_name')->where('color_id','=',$row->color_id)->get();
        
        $html .='<tr>';
        $html .='
        <td>'.$no.'</td>';
           
        $html.=' <td> <select name="color_idsss[]" class="select2-select"  id="color_id01" style="width:250px; height:30px;" disabled>
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
        //  DB::enableQueryLog();  
      $List = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
          color_master.color_id=stitching_inhouse_size_detail.color_id where 
          stitching_inhouse_size_detail.vw_code='".$request->vw_code."' and
          stitching_inhouse_size_detail.color_id='".$row->color_id."'");

   
       $List1 = DB::select("SELECT cut_panel_issue_size_detail.color_id, color_name, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from cut_panel_issue_size_detail inner join color_master on 
          color_master.color_id=cut_panel_issue_size_detail.color_id where 
          cut_panel_issue_size_detail.vw_code='".$request->vw_code."' and
          cut_panel_issue_size_detail.color_id='".$row->color_id."'");
       
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
          
         
         
          $html.='<td>'.($List1[0]->size_qty_total-$List[0]->size_qty_total).'</td>
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
    
  public function GetDailyProductionReport()
  {
    $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->where('ledger_master.ac_code','>', '39')->get();
    return view('GetDailyProductionReport',compact('LedgerList'));  
  }    
  public function GetVendorStatusReport()  
  {
    $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->where('ledger_master.ac_code','>', '39')->get();
    return view('GetVendorStatusReport',compact('LedgerList'));  
  }    
 
    public function VendorStatusReport(Request $request)
    {
        $fdate=$request->fdate;
        $tdate=$request->tdate;
        $vendorId=$request->vendorId;
        $FirmDetail =  DB::table('firm_master')->first();
        $LineList=DB::select("select line_id,line_name from line_master where Ac_code='".$request->vendorId."'");
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code',$request->vendorId)->get();
        return view('VendorStatusReport',compact('LineList','LedgerList','fdate','tdate', 'vendorId','FirmDetail'));  
    }     
  
   public function DailyProductionReport(Request $request)
   {
        $fdate=$request->fdate;
        $tdate=$request->tdate;
        $vendorId=$request->vendorId;
        $line_id=$request->line_id;
        $FirmDetail =  DB::table('firm_master')->first();
        $LineList=DB::select("select line_id,line_name from line_master where Ac_code='".$request->vendorId."'");
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code',$request->vendorId)->get();
        return view('DailyProductionReport',compact('LineList','LedgerList','fdate','tdate', 'vendorId','FirmDetail','line_id'));  
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
     
     
     
     
       public function StitchingGRNPrint($sti_code)
    {
        
         
    //   DB::enableQueryLog();
       
         $StitchingInhouseMaster = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'stitching_inhouse_master.vendorId')
         ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','stitching_inhouse_master.vw_code')
        ->where('stitching_inhouse_master.sti_code', $sti_code)
         ->get(['stitching_inhouse_master.*','usermaster.username','ledger_master.Ac_name','stitching_inhouse_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
          
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$StitchingInhouseMaster[0]->sales_order_no)->get();
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
          $StitchingGRNList = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from stitching_inhouse_size_detail 
        left join color_master on color_master.color_id=stitching_inhouse_size_detail.color_id 
        where sti_code='".$StitchingInhouseMaster[0]->sti_code."' group by 	stitching_inhouse_size_detail.color_id");
             //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('StitchingGRNPrint', compact('StitchingInhouseMaster','StitchingGRNList','SizeDetailList','FirmDetail'));
      
    }

     public function StitchingGRNPrintView($sti_code)
    {
               
         $StitchingInhouseMaster = StitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'stitching_inhouse_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'stitching_inhouse_master.vendorId')
         ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','stitching_inhouse_master.vw_code')
        ->where('stitching_inhouse_master.sti_code', $sti_code)
         ->get(['stitching_inhouse_master.*','usermaster.username','ledger_master.Ac_name','stitching_inhouse_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
          
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$StitchingInhouseMaster[0]->sales_order_no)->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        
          $StitchingGRNList = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from stitching_inhouse_size_detail 
        left join color_master on color_master.color_id=stitching_inhouse_size_detail.color_id 
        where sti_code='".$StitchingInhouseMaster[0]->sti_code."' group by 	stitching_inhouse_size_detail.color_id");
            
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('StitchingGRNPrintView', compact('StitchingInhouseMaster','StitchingGRNList','SizeDetailList','FirmDetail'));
      
    }
      
    public function destroy($id)
    {
        $stiData = DB::table('stitching_inhouse_master')->select('sti_date','vendorId','sti_code')->where('sti_code', $id)->first();
        
        DB::table('stitching_inhouse_master')->where('sti_code', $id)->delete();
        DB::table('stitching_inhouse_size_detail2')->where('sti_code', $id)->delete();
        DB::table('stitching_inhouse_size_detail')->where('sti_code', $id)->delete();
        DB::table('stitching_inhouse_detail')->where('sti_code', $id)->delete();
        
        
        $prodctionData = DB::connection('hrms_database')->table('production_master')->select('proId')->where('productionDate','=',$stiData->sti_date)->where('branch_id','=',$stiData->vendorId)->first();
        DB::connection('hrms_database')->table('production_master')->where('productionDate','=',$stiData->sti_date)->where('branch_id','=',$stiData->vendorId)->delete();  
        DB::connection('hrms_database')->table('production_detail')->where('sti_code','=',$stiData->sti_code)->delete(); 

        SourceModel::on('mysql');
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
   
    
    public function GetMontlyBudgetProductionReport(Request $request)
    {
         
            $html = "";  
            $html1 = "";
            $totalLPcs = 0;
            $totalRsCr = 0;
            $totalLMin = 0;
            $totalLCMOHP = 0;
            $CMOHP_Value = 0;
            $TotalLMPer = 0;
            $totalLPcs1 = 0;
            $totalRsCr1 = 0;
            $totalLMin1 = 0;
            $totalLCMOHP1 = 0;
            $CMOHP_Value1 = 0;
            $TotalLMPer1 = 0;
            $fdate= $request->fromDate;
            $tdate= $request->toDate; 
                              
            $html = '<table id="monthly_budget0" class="table dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th>Sr No.</th>
                                        <th>Buyer Name</th> 
                                        <th class="text-center">Order Category</th>
                                        <th class="text-center">L Pcs</th>
                                        <th class="text-center">FOB</th>
                                        <th class="text-center">Rs. Cr.</th> 
                                        <th class="text-center">L Min</th>
                                        <th class="text-center">CMOHP</th> 
                                        <th class="text-center">% (LM)</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="monthlyBudgetTbodyProduction1">';
            //DB::enableQueryLog();
               $stitchingData = DB::table('stitching_inhouse_size_detail2')
                ->select(
                    'order_group_master.order_group_name',
                    'order_category.OrderCategoryShortName',
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty) as total_size_qty'),
                    'stitching_inhouse_size_detail2.Ac_code',
                    'ledger_master.ac_short_name',
                    'production_value',
                    'total_cost_value',
                    'other_value',
                    'buyer_purchse_order_master.sam',
                    'buyer_purchse_order_master.order_rate',
                    DB::raw('CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END as fob_rate'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * ROUND(CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END, 2)) as value_production'),
                    DB::raw('SUM(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value)) / buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value'),
                    DB::raw('SUM(((sales_order_costing_master.production_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value) + 
                            sales_order_costing_master.other_value)/buyer_purchse_order_master.sam) * 
                            (stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam)) as cmohp'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes')
                )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'stitching_inhouse_size_detail2.Ac_code')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('order_category', 'order_category.orderCategoryId', '=', 'buyer_purchse_order_master.orderCategoryId')
                ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fdate, $tdate])
                ->groupBy('stitching_inhouse_size_detail2.Ac_code', 'buyer_purchse_order_master.orderCategoryId')
                ->orderBy('ledger_master.ac_short_name', 'ASC')
                ->get();
           
            //dd(DB::getQueryLog());
            $stitchingData1 = DB::SELECT("SELECT sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes FROM stitching_inhouse_size_detail2 
                                            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
                                            WHERE stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'");  
                          
            
            
            $overallLMin = isset($stitchingData1[0]->minutes) ? $stitchingData1[0]->minutes : 0;
            $srno = 1;
            foreach($stitchingData as $rows)
            {     
                $minutess = $rows->minutes/100000;
                
                if($overallLMin > 0 && $minutess > 0)
                { 
                    $LMPer = sprintf("%.2f",((sprintf("%.2f",($minutess))/sprintf("%.2f",($overallLMin/100000))) * 100));
                } 
                else
                {
                    $LMPer = 0;
                }
               
                $value_production = $rows->value_production;
                
                $first_character = substr($rows->order_group_name, 0, 1);
                      
                $html .='<tr> 
                        <td  nowrap>'.($srno++).'</td>
                        <td  nowrap>'.$rows->ac_short_name.'</td> 
                        <td class="text-center">'.$first_character."-".$rows->OrderCategoryShortName.'</td>
                        <td class="text-center">'.sprintf("%.2f", $rows->total_size_qty/100000).'</td>
                        <td class="text-center">'.sprintf("%.2f", $value_production/$rows->total_size_qty).'</td>
                        <td class="text-center">'.sprintf("%.2f", $value_production/10000000).'</td>
                        <td class="text-center">'.sprintf("%.2f", $rows->minutes/100000).'</td>
                        <td class="text-center">'.sprintf("%.2f", $rows->cmohp_value/$rows->minutes).'</td> 
                        <td class="text-right"'.(sprintf("%.2f",($overallLMin/100000))).'-'.(sprintf("%.2f",($minutess))).'>'.$LMPer.'</td> 
                     </tr>';
                         
                                
                $totalLPcs +=  $rows->total_size_qty;
                $totalRsCr +=  $value_production;
                $totalLMin += $rows->minutes;
                $CMOHP_Value += $rows->cmohp;
                $TotalLMPer += $LMPer;
            } 
           $html .= '</tbody>
                    <tfoot>';
                    
            if($CMOHP_Value>0 && $totalLPcs>0)
            {
                 $totalLCMOHP = $CMOHP_Value/$totalLMin;   
            }
            else
            {
                 $totalLCMOHP = 0;   
            }
           
           if($totalRsCr > 0 && $totalLPcs >0)
           { 
             $ttlcrpc = sprintf("%.2f", $totalRsCr/$totalLPcs);
           }
           else
           {
               $ttlcrpc = 0;
           }
            
            $html .='<tr>
                        <th  nowrap class="text-right"></th>
                        <th  nowrap class="text-right"></th>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-center">'.sprintf("%.2f", $totalLPcs/100000).'</th>
                        <th class="text-center">'.$ttlcrpc.'</th>
                        <th class="text-center">'.sprintf("%.2f", $totalRsCr/10000000).'</th>
                        <th class="text-center">'.sprintf("%.2f", ($totalLMin/100000)).'</th>
                        <th class="text-center">'.sprintf("%.2f", $totalLCMOHP).'</th> 
                        <th class="text-right">'.round($TotalLMPer).'.00</th> 
                     </tr>';
                $html .= '</tfoot>
                               </table>';         
          $total_count = count($stitchingData);           
        
        
        
        
        
        
          $html1 = '<table id="monthly_budget2" class="table dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th>Sr No.</th>
                                        <th>Vendor Name</th> 
                                        <th class="text-center">L Pcs</th>
                                        <th class="text-center">FOB</th>
                                        <th class="text-center">Rs. Cr.</th> 
                                        <th class="text-center">L Min</th>
                                        <th class="text-center">CMOHP</th> 
                                        <th class="text-center">% (LM)</th> 
                                     </tr>
                                  </thead>
                                  <tbody id="monthlyBudgetTbodyProduction2">';
           //  DB::enableQueryLog();
            
            $stitchingData2 = DB::table('stitching_inhouse_size_detail2')
                ->select(
                    'order_group_master.order_group_name',
                    'order_category.OrderCategoryShortName',
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty) as total_size_qty'),
                    'stitching_inhouse_size_detail2.Ac_code',
                    'ledger_master.ac_short_name',
                    'production_value',
                    'total_cost_value',
                    'other_value',
                    'buyer_purchse_order_master.sam',
                    'buyer_purchse_order_master.order_rate','stitching_inhouse_size_detail2.vendorId',
                    DB::raw('CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END as fob_rate'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * ROUND(CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END, 2)) as value_production'),
                    DB::raw('SUM(((sales_order_costing_master.production_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value) + 
                            sales_order_costing_master.other_value)/buyer_purchse_order_master.sam) * 
                            (stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam)) / sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam)  as cmohp_value'),
                    DB::raw('SUM(((sales_order_costing_master.production_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value) + 
                            sales_order_costing_master.other_value)/buyer_purchse_order_master.sam) * 
                            (stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam)) as cmohp'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes')
                )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'stitching_inhouse_size_detail2.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('order_category', 'order_category.orderCategoryId', '=', 'buyer_purchse_order_master.orderCategoryId')
                ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fdate, $tdate])
                ->groupBy('stitching_inhouse_size_detail2.vendorId') 
                ->get();
                
  
           //dd(DB::getQueryLog());
            $stitchingData3 = DB::SELECT("SELECT sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes FROM stitching_inhouse_size_detail2 
                                            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
                                            WHERE stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'");  
                          
      
            $overallLMin1 = isset($stitchingData3[0]->minutes) ? $stitchingData3[0]->minutes : 0;
            $srno1 = 1;
            $totalLPcs2 =  0;
            $totalLPcs3 =  0;
            $totalRsCr2 = 0;
            $totalLMin2 = 0;
            $CMOHP_Value2 = 0;
            $TotalLMPer2 = 0;
            
            foreach($stitchingData2 as $rows1)
            {     
                $minutess1 = $rows1->minutes/100000;
                
                if($overallLMin1 > 0 && $minutess1 > 0)
                { 
                    $LMPer1 = sprintf("%.2f",((sprintf("%.2f",($minutess1))/sprintf("%.2f",($overallLMin1/100000))) * 100));
                } 
                else
                {
                    $LMPer1 = 0;
                }
                
                $value_production1 = $rows1->value_production;
               
                $html1 .='<tr> 
                        <td  nowrap>'.($srno1++).'</td>
                        <td  nowrap>'.$rows1->ac_short_name.'</td> 
                        <td class="text-center">'.sprintf("%.2f", $rows1->total_size_qty/100000).'</td>
                        <td class="text-center">'.sprintf("%.2f", $value_production1/$rows1->total_size_qty).'</td>
                        <td class="text-center">'.sprintf("%.2f", $value_production1/10000000).'</td>
                        <td class="text-center">'.sprintf("%.2f", $rows1->minutes/100000).'</td>
                        <td class="text-center">'.sprintf("%.2f", $rows1->cmohp_value).'</td> 
                        <td class="text-right"'.(sprintf("%.2f",($overallLMin1/100000))).'-'.(sprintf("%.2f",($minutess1))).'>'.$LMPer1.'</td> 
                     </tr>';
                
                $totalLPcs1 +=  $rows1->total_size_qty;
                $totalRsCr1 += $value_production1;
                $totalLMin1 += $rows1->minutes;
                $CMOHP_Value1 += $rows1->cmohp;
                $TotalLMPer1 += $LMPer1;
                
                if($srno1 > 5)
                {  
                    $totalLPcs2 +=  sprintf("%.2f", $rows1->total_size_qty);
                    $totalLPcs2 += sprintf("%.2f", $rows1->total_size_qty/100000);
                    $totalRsCr2 += sprintf("%.2f", $value_production1);
                    $totalLMin2 += sprintf("%.2f", $rows1->minutes);
                    $CMOHP_Value2 += sprintf("%.2f", $rows1->cmohp);
                    $TotalLMPer2 += sprintf("%.2f", $LMPer1);
                }
                
                
                if($srno1 == 5)
                {      
                    $html1 .='<tr style="background: #fe8b6624;"> 
                        <td  nowrap></td>
                        <td  nowrap class="text-right"><b>Total:</b></td> 
                        <td class="text-center"><b>'.sprintf("%.2f", $totalLPcs1/100000).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $totalRsCr1/$totalLPcs1).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $totalRsCr1/10000000).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $totalLMin1/100000).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", ($CMOHP_Value1/$totalLMin1)).'</b></td> 
                        <td class="text-right"><b>'.$TotalLMPer1.'</b></td> 
                     </tr>';  
                }
                if($srno1 == 10)
                {
                     $html1 .='<tr style="background: #fe8b6624;"> 
                        <td  nowrap></td>
                        <td  nowrap class="text-right"><b>Total:</b></td> 
                        <td class="text-center"><b>'.sprintf("%.2f", $totalLPcs2/100000).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $totalRsCr2/$totalLPcs2).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $totalRsCr2/10000000).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $totalLMin2/100000).'</b></td>
                        <td class="text-center"><b>'.sprintf("%.2f", $CMOHP_Value2/$totalLMin2).'</b></td> 
                        <td class="text-right"><b>'.$TotalLMPer2.'</b></td> 
                     </tr>';  
                }
            } 
           $html1 .= '</tbody>
                    <tfoot>';
            
            
            $totalLCMOHP1 = $CMOHP_Value1;   
         
            
            if($totalRsCr1>0 && $totalLPcs1>0)
            { 
                $ttcp1 = sprintf("%.2f", $totalRsCr1/$totalLPcs1);
            }
            else
            {
                $ttcp1 = 0;
            }
            
            $html1 .='<tr style="background: #80808052;">
                        <th  nowrap class="text-right"></th>
                        <th  nowrap class="text-right"><b>Grand Total : </b></th>
                        <th class="text-center"><b>'.sprintf("%.2f", $totalLPcs1/100000).' </b></th>
                        <th class="text-center"><b>'.$ttcp1.' </b></th>
                        <th class="text-center"><b>'.sprintf("%.2f", $totalRsCr1/10000000).' </b></th>
                        <th class="text-center"><b>'.sprintf("%.2f", ($totalLMin1/100000)).' </b></th>
                        <th class="text-center"><b>'.sprintf("%.2f", $totalLCMOHP1/$totalLMin1).' </b></th> 
                        <th class="text-right"><b>'.round($TotalLMPer1).'.00 </b></th> 
                     </tr>';
                $html1 .= '</tfoot>
                               </table>';         
        $total_count1 = count($stitchingData2);       
        
        
        
        
        
        return response()->json(['html' => $html,'html1' => $html1,'total_count'=>$total_count]);
    }
    
        
    public function GetBuyerWiseFOBProductionReport(Request $request)
    {
         
             $fdate= $request->BuyerFOBProdFromDate;
             $tdate= $request->BuyerFOBProdToDate; 
            
            
            // $stitchingData = DB::SELECT("SELECT sum(stitching_inhouse_size_detail2.size_qty) as total_size_qty,stitching_inhouse_size_detail2.Ac_code,ledger_master.ac_short_name,production_value,total_cost_value,other_value,buyer_purchse_order_master.sam,buyer_purchse_order_master.order_rate,
            //                                 sum(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value))/buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value,
            //                                 sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes  FROM stitching_inhouse_size_detail2 
            //                                 LEFT JOIN ledger_master ON ledger_master.ac_code = stitching_inhouse_size_detail2.Ac_code 
            //                                 LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = stitching_inhouse_size_detail2.sales_order_no
            //                                 INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
            //                                 WHERE buyer_purchse_order_master.order_type=1 AND stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'  GROUP BY stitching_inhouse_size_detail2.Ac_code ORDER BY ledger_master.ac_short_name ASC");  
                                       
           
           $stitchingData = DB::table('stitching_inhouse_size_detail2')
                ->select(
                    'order_group_master.order_group_name',
                    'order_category.OrderCategoryShortName',
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty) as total_size_qty'),
                    'stitching_inhouse_size_detail2.Ac_code',
                    'ledger_master.ac_short_name',
                    'production_value',
                    'total_cost_value',
                    'other_value',
                    'buyer_purchse_order_master.sam',
                    'buyer_purchse_order_master.order_rate',
                    DB::raw('CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END as fob_rate'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * ROUND(CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END, 2)) as value_production'),
                    DB::raw('SUM(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value)) / buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes')
                )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'stitching_inhouse_size_detail2.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('order_category', 'order_category.orderCategoryId', '=', 'buyer_purchse_order_master.orderCategoryId')
                ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                ->where("buyer_purchse_order_master.order_type","=","1")
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fdate, $tdate])
                ->groupBy('stitching_inhouse_size_detail2.Ac_code')
                ->orderBy('ledger_master.ac_short_name', 'ASC')
                ->get();
                
            $html = "";
            $html1 = "";
            $totalLPcs = 0;
            $totalRsCr = 0;
            $totalLMin = 0;
            $totalLCMOHP = 0;
            $CMOHP_Value = 0;
            $cmohp = 0; 
            
            foreach($stitchingData as $rows)
            {     
               
                $value_production = $rows->value_production;
                
                $html .='<tr>
                        <td  nowrap>'.$rows->ac_short_name.'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->total_size_qty/100000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $value_production/$rows->total_size_qty).'</td>
                        <td class="text-right">'.sprintf("%.2f", $value_production/10000000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->minutes/100000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->cmohp_value/$rows->total_size_qty).'</td> 
                     </tr>';
                         
                                
                $totalLPcs += $rows->total_size_qty;
                $totalRsCr += $value_production;
                $totalLMin += $rows->minutes;
                $CMOHP_Value += $rows->cmohp_value;
            } 
       
            $totalLCMOHP = $CMOHP_Value/$totalLPcs;   
            
            $html1 .='<tr>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-right">'.sprintf("%.2f", $totalLPcs/100000).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr/$totalLPcs).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr/10000000).'</th>
                        <th class="text-right">'.sprintf("%.2f", ($totalLMin/100000)).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalLCMOHP).'</th> 
                     </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
            
    public function GetBuyerWiseJobWorkProductionReport(Request $request)
    {
         
             $fdate= $request->BuyerJobWorkProdFromDate;
             $tdate= $request->BuyerJobWorkProdToDate; 
            
            
            // $stitchingData = DB::SELECT("SELECT sum(stitching_inhouse_size_detail2.size_qty) as total_size_qty,stitching_inhouse_size_detail2.Ac_code,ledger_master.ac_short_name,production_value,total_cost_value,other_value,buyer_purchse_order_master.sam,buyer_purchse_order_master.order_rate,
            //                                 sum(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value))/buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value,sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.order_rate) as value_production,sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes  FROM stitching_inhouse_size_detail2 
            //                                 LEFT JOIN ledger_master ON ledger_master.ac_code = stitching_inhouse_size_detail2.Ac_code 
            //                                 LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = stitching_inhouse_size_detail2.sales_order_no
            //                                 INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
            //                                 WHERE buyer_purchse_order_master.order_type=3 AND stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'  GROUP BY stitching_inhouse_size_detail2.Ac_code ORDER BY ledger_master.ac_short_name ASC");  
                                       
            $stitchingData = DB::table('stitching_inhouse_size_detail2')
                ->select(
                    'order_group_master.order_group_name',
                    'order_category.OrderCategoryShortName',
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty) as total_size_qty'),
                    'stitching_inhouse_size_detail2.Ac_code',
                    'ledger_master.ac_short_name',
                    'production_value',
                    'total_cost_value',
                    'other_value',
                    'buyer_purchse_order_master.sam',
                    'buyer_purchse_order_master.order_rate',
                    DB::raw('CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END as fob_rate'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * ROUND(CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END, 2)) as value_production'),
                    DB::raw('SUM(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value)) / buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes')
                )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'stitching_inhouse_size_detail2.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('order_category', 'order_category.orderCategoryId', '=', 'buyer_purchse_order_master.orderCategoryId')
                ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                ->where("buyer_purchse_order_master.order_type","=","3")
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fdate, $tdate])
                ->groupBy('stitching_inhouse_size_detail2.Ac_code')
                ->orderBy('ledger_master.ac_short_name', 'ASC')
                ->get();
                
            $html = "";
            $html1 = "";
            $totalLPcs = 0;
            $totalRsCr = 0;
            $totalLMin = 0;
            $totalLCMOHP = 0;
            $CMOHP_Value = 0;
            $cmohp = 0; 
            
            foreach($stitchingData as $rows)
            {      
               
                $value_production = $rows->value_production;
                      
                $html .='<tr>
                        <td  nowrap>'.$rows->ac_short_name.'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->total_size_qty/100000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $value_production/$rows->total_size_qty).'</td>
                        <td class="text-right">'.sprintf("%.2f", $value_production/10000000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->minutes/100000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->cmohp_value/$rows->total_size_qty).'</td> 
                     </tr>';
                         
                                
                $totalLPcs +=  $rows->total_size_qty;
                $totalRsCr += $value_production;
                $totalLMin += $rows->minutes;
                $CMOHP_Value += $rows->cmohp_value;
            } 
            
            if($CMOHP_Value > 0 && $totalLPcs > 0)
            { 
                $totalLCMOHP = $CMOHP_Value/$totalLPcs;   
            }
            else
            {
                $totalLCMOHP = 0;   
            }
            
            if($totalRsCr > 0 && $totalLPcs > 0)
            { 
                $totalFOB = $totalRsCr/$totalLPcs;   
            }
            else
            {
                $totalFOB = 0;   
            }
            
            
            $html1 .='<tr>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-right">'.sprintf("%.2f", $totalLPcs/100000).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalFOB).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr/10000000).'</th>
                        <th class="text-right">'.sprintf("%.2f", ($totalLMin/100000)).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalLCMOHP).'</th> 
                     </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
    
                
    public function GetBuyerWiseStockProductionReport(Request $request)
    {
         
             $fdate= $request->BuyerStockProdFromDate;
             $tdate= $request->BuyerStockProdToDate; 
            
            
            // $stitchingData = DB::SELECT("SELECT sum(stitching_inhouse_size_detail2.size_qty) as total_size_qty,stitching_inhouse_size_detail2.Ac_code,ledger_master.ac_short_name,production_value,total_cost_value,other_value,buyer_purchse_order_master.sam,buyer_purchse_order_master.order_rate,
            //                                 sum(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value))/buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value,
            //                                 sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes  FROM stitching_inhouse_size_detail2 
            //                                 LEFT JOIN ledger_master ON ledger_master.ac_code = stitching_inhouse_size_detail2.Ac_code 
            //                                 LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = stitching_inhouse_size_detail2.sales_order_no
            //                                 INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
            //                                 WHERE buyer_purchse_order_master.order_type=2 AND stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'  GROUP BY stitching_inhouse_size_detail2.Ac_code ORDER BY ledger_master.ac_short_name ASC");  
                                       
            $stitchingData = DB::table('stitching_inhouse_size_detail2')
                ->select(
                    'order_group_master.order_group_name',
                    'order_category.OrderCategoryShortName',
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty) as total_size_qty'),
                    'stitching_inhouse_size_detail2.Ac_code',
                    'ledger_master.ac_short_name',
                    'production_value',
                    'total_cost_value',
                    'other_value',
                    'buyer_purchse_order_master.sam',
                    'buyer_purchse_order_master.order_rate',
                    DB::raw('CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END as fob_rate'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * ROUND(CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END, 2)) as value_production'),
                    DB::raw('SUM(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value)) / buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes')
                )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'stitching_inhouse_size_detail2.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('order_category', 'order_category.orderCategoryId', '=', 'buyer_purchse_order_master.orderCategoryId')
                ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id')
                ->where("buyer_purchse_order_master.order_type","=","2")
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fdate, $tdate])
                ->groupBy('stitching_inhouse_size_detail2.Ac_code')
                ->orderBy('ledger_master.ac_short_name', 'ASC')
                ->get();
                
            $html = "";
            $html1 = "";
            $totalLPcs = 0;
            $totalRsCr = 0;
            $totalLMin = 0;
            $totalLCMOHP = 0;
            $CMOHP_Value = 0;
            $cmohp = 0; 
            
            foreach($stitchingData as $rows)
            {      
             
                $value_production = $rows->value_production;
                         
                $html .='<tr>
                        <td  nowrap>'.$rows->ac_short_name.'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->total_size_qty/100000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $value_production/$rows->total_size_qty).'</td>
                        <td class="text-right">'.sprintf("%.2f", $value_production/10000000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->minutes/100000).'</td>
                        <td class="text-right">'.sprintf("%.2f", $rows->cmohp_value/$rows->total_size_qty).'</td> 
                     </tr>';
                         
                                
                $totalLPcs +=  $rows->total_size_qty;
                $totalRsCr += $value_production;
                $totalLMin += $rows->minutes;
                $CMOHP_Value += $rows->cmohp_value;
            } 
            
            if($CMOHP_Value > 0 && $totalLPcs > 0)
            { 
                $totalLCMOHP = $CMOHP_Value/$totalLPcs;   
            }
            else
            {
                $totalLCMOHP = 0;   
            }
            
            if($totalRsCr > 0 && $totalLPcs > 0)
            { 
                $totalFOB = $totalRsCr/$totalLPcs;   
            }
            else
            {
                $totalFOB = 0;   
            }
            
            
            $html1 .='<tr>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-right">'.sprintf("%.2f", $totalLPcs/100000).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalFOB).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalRsCr/10000000).'</th>
                        <th class="text-right">'.sprintf("%.2f", ($totalLMin/100000)).'</th>
                        <th class="text-right">'.sprintf("%.2f", $totalLCMOHP).'</th> 
                     </tr>';
        return response()->json(['html' => $html,'html1' => $html1]);
    }
    
    public function GetProductionSummaryReport(Request $request)
    {
         
            $fdate= $request->ProdSummaryFromDate;
            $tdate= $request->ProdSummaryToDate; 
                              
            $html = '<table id="prodSummary" class="table dt-datatable table-bordered nowrap w-100">
                                  <thead> 
                                     <tr style="text-align:center; white-space:nowrap;background: #00000061;color: #fff;">
                                        <th class="text-center">Sr No.</th>
                                        <th class="text-center">Type</th>  
                                        <th class="text-center">L Min</th> 
                                        <th class="text-center">% (LM)</th>  
                                     </tr>
                                  </thead>
                                  <tbody>';
            
                // $stitchingData = DB::SELECT("SELECT order_group_name,OrderCategoryName,sum(stitching_inhouse_size_detail2.size_qty) as total_size_qty,stitching_inhouse_size_detail2.Ac_code,
                //                                 sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes  FROM stitching_inhouse_size_detail2 
                //                                 LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = stitching_inhouse_size_detail2.sales_order_no
                //                                 LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
                //                                 LEFT JOIN order_category ON order_category.orderCategoryId = buyer_purchse_order_master.orderCategoryId 
                //                                 LEFT JOIN order_group_master ON order_group_master.og_id = buyer_purchse_order_master.og_id
                //                                 WHERE stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'  GROUP BY buyer_purchse_order_master.orderCategoryId");  
                                       
            $stitchingData = DB::table('stitching_inhouse_size_detail2')
                ->select(
                    'order_group_master.order_group_name',
                    'order_category.OrderCategoryShortName',
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty) as total_size_qty'),
                    'stitching_inhouse_size_detail2.Ac_code',
                    'ledger_master.ac_short_name',
                    'production_value',
                    'total_cost_value',
                    'other_value',
                    'buyer_purchse_order_master.sam',
                    'buyer_purchse_order_master.order_rate',
                    DB::raw('CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END as fob_rate'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * ROUND(CASE WHEN total_cost_value = 0 THEN buyer_purchse_order_master.order_rate ELSE total_cost_value END, 2)) as value_production'),
                    DB::raw('SUM(((production_value + other_value + (buyer_purchse_order_master.order_rate - sales_order_costing_master.total_cost_value)) / buyer_purchse_order_master.sam) * stitching_inhouse_size_detail2.size_qty) as cmohp_value'),
                    DB::raw('SUM(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes')
                )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'stitching_inhouse_size_detail2.vendorId')
                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'stitching_inhouse_size_detail2.sales_order_no')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'buyer_purchse_order_master.tr_code')
                ->leftJoin('order_category', 'order_category.orderCategoryId', '=', 'buyer_purchse_order_master.orderCategoryId')
                ->leftJoin('order_group_master', 'order_group_master.og_id', '=', 'buyer_purchse_order_master.og_id') 
                ->whereBetween('stitching_inhouse_size_detail2.sti_date', [$fdate, $tdate])
                ->groupBy('buyer_purchse_order_master.orderCategoryId')
                ->orderBy('ledger_master.ac_short_name', 'ASC')
                ->get();
                
                
            $stitchingData1 = DB::SELECT("SELECT sum(stitching_inhouse_size_detail2.size_qty * buyer_purchse_order_master.sam) as minutes FROM stitching_inhouse_size_detail2 
                                            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = stitching_inhouse_size_detail2.sales_order_no 
                                            WHERE stitching_inhouse_size_detail2.sti_date BETWEEN '".$fdate."' AND '".$tdate."'");  
                         
          
            $totalLMin = 0;
            $TotalLMPer = 0;
            
            $overallLMin = isset($stitchingData1[0]->minutes) ? $stitchingData1[0]->minutes : 0;
            $srno = 1;
            foreach($stitchingData as $rows)
            {     
                $minutess = $rows->minutes/100000;
                
                if($overallLMin > 0 && $minutess > 0)
                { 
                    $LMPer = sprintf("%.2f",((sprintf("%.2f",($minutess))/sprintf("%.2f",($overallLMin/100000))) * 100));
                } 
                else
                {
                    $LMPer = 0;
                }
                
                $first_character = $rows->order_group_name;
                      
                $html .='<tr> 
                        <td class="text-center" nowrap>'.($srno++).'</td>
                        <td class="text-center">'.$first_character."-".$rows->OrderCategoryName.'</td>
                        <td class="text-center">'.sprintf("%.2f", $rows->minutes/100000).'</td>
                        <td class="text-center"'.(sprintf("%.2f",($overallLMin/100000))).'-'.(sprintf("%.2f",($minutess))).'>'.$LMPer.'</td>  
                     </tr>';
                         
                $totalLMin += $rows->minutes;
                $TotalLMPer += $LMPer;
            } 
           $html .= '</tbody>
                    <tfoot>';
            
            $html .='<tr>
                        <th  nowrap class="text-right"></th>
                        <th  nowrap class="text-right">Total : </th>
                        <th class="text-center">'.sprintf("%.2f", ($totalLMin/100000)).'</th>
                        <th class="text-center">'.round($TotalLMPer).'.00</th>  
                     </tr>';
                $html .= '</tfoot>
                               </table>';         
        $total_count = count($stitchingData);             
        return response()->json(['html' => $html,'total_count'=>$total_count]);
    }
    
    public function GetDailyEfficiencyReport()
    {
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->where('ledger_master.ac_code','>', '39')->get();
        return view('GetDailyEfficiencyReport',compact('LedgerList'));  
    } 

    public function DailyEfficiencyReport(Request $request)
    {
        $fdate = isset($request->fromDate) ? $request->fromDate : date("Y-m-d", strtotime("-1 day"));
        $tdate = isset($request->toDate) ? $request->toDate : date("Y-m-d", strtotime("-1 day"));

        
        $vendorId = !empty($request->vendorId) ? $request->vendorId : [56, 115, 110, 113, 686];

        $line_id=$request->line_id; 
        
        $FirmDetail =  DB::table('firm_master')->first();
        
        $LineList=DB::select("select line_id,line_name from line_master where delflag=0");
        $LedgerList = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        
        $vendorIds = is_array($vendorId) ? $vendorId : [$vendorId];
        //DB::enableQueryLog();
        $LedgerData = DB::select("SELECT line_id, line_name,ledger_master.ac_short_name,ledger_master.ac_code 
            FROM ledger_master LEFT JOIN line_master ON line_master.ac_code = ledger_master.Ac_code WHERE ledger_master.delflag = 0 AND line_master.delflag = 0 
            AND ledger_master.Ac_code IN (".implode(',', $vendorIds).") order by ledger_master.ac_short_name,line_master.line_name ASC");
                
        //dd(DB::getQueryLog());
        $groupedLedgerData = [];
        foreach ($LedgerData as $row) {
            $groupedLedgerData[$row->ac_short_name][] = $row;
        }
        return view('DailyEfficiencyReport',compact('LineList','LedgerList','fdate','tdate', 'vendorId','FirmDetail','line_id','groupedLedgerData','vendorIds'));  
    } 
        
}
