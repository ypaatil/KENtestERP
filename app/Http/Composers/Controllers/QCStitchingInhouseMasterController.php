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
use Session;

class QCStitchingInhouseMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '109')
        ->first();
        $vendorId=Session::get('vendorId');
        $user_type=Session::get('user_type');
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
 {

        //   DB::enableQueryLog();
        $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
        ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
         ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
        ->where('qcstitching_inhouse_master.delflag','=', '0')
        ->get(['qcstitching_inhouse_master.*','usermaster.username','L1.Ac_name','L2.Ac_name as vendor_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    
 }
 elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
 {
      $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'qcstitching_inhouse_master.userId', 'left outer')
        ->join('ledger_master as L1', 'L1.Ac_code', '=', 'qcstitching_inhouse_master.Ac_code', 'left outer')
         ->join('ledger_master as L2', 'L2.Ac_code', '=', 'qcstitching_inhouse_master.vendorId', 'left outer')
        ->where('qcstitching_inhouse_master.delflag','=', '0')->where( 'qcstitching_inhouse_master.vendorId',$vendorId)
        ->get(['qcstitching_inhouse_master.*','usermaster.username','L1.Ac_name','L2.Ac_name as vendor_name']);
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
                'mainstyle_id'=>'required',
                
               
    ]);
 
 
$data1=array(
           
        'qcsti_code'=>$TrNo, 
        'qcsti_date'=>$request->qcsti_date, 
        'sales_order_no'=>$request->sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'vendorId'=>$request->vendorId,
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
        
        
        
    );
 
    QCStitchingInhouseMasterModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='QCStitchingInhouse'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array(
          
                    'qcsti_code'=>$TrNo,
                    'qcsti_date'=>$request->qcsti_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
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
                 
                  $data5[]=array(
          
                    'qcsti_code'=>$TrNo,
                    'qcsti_date'=>$request->qcsti_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
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
 
 
 $size_array=
 
 
                      $data3[]=array(
                  
                        'qcsti_code'=>$TrNo, 
                        'qcsti_date'=>$request->qcsti_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
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
 
                     
 
                      $data4[]=array(
                  
                        'qcsti_code'=>$TrNo, 
                        'qcsti_date'=>$request->qcsti_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
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
                          
                           
                          
              
              } // if loop avoid zero qty
            }
          QCStitchingInhouseDetailModel::insert($data2);
          QCStitchingInhouseRejectDetailModel::insert($data5);
          QCStitchingInhouseSizeDetailModel::insert($data3);
          QCStitchingInhouseSizeRejectDetailModel::insert($data4);
         
    }
    
        
   //$InsertSizeData=DB::select('call AddSizeQtyFromQCStitchingInhouse("'.$TrNo.'")');
           
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
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
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
        //   DB::enableQueryLog();
        $QCStitchingInhouseMasterList = QCStitchingInhouseMasterModel::find($id);
        
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
         
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$QCStitchingInhouseMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$QCStitchingInhouseMasterList->sales_order_no)->DISTINCT()->get();
        
        $QCStitchingInhouseDetailList =QCStitchingInhouseDetailModel::where('qcstitching_inhouse_detail.qcsti_code','=', $QCStitchingInhouseMasterList->qcsti_code)->get();
          $QCStitchingInhouseRejectDetailList =QCStitchingInhouseRejectDetailModel::where('qcstitching_inhouse_reject_detail.qcsti_code','=', $QCStitchingInhouseMasterList->qcsti_code)->get();
     
        //  
         //--------
        
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
        
        //---------
        
             // DB::enableQueryLog(); 
         
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
        
        return view('QCStitchingInhouseMasterEdit',compact('QCStitchingInhouseDetailList','QCStitchingInhouseRejectDetailList','ColorList' ,'BuyerList',  'MasterdataList','SizeDetailList','QCStitchingInhouseMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger' ));
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
             
                'qcsti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
               
    ]);
 
  
$data1=array(
           
        'qcsti_code'=>$request->qcsti_code, 
        'qcsti_date'=>$request->qcsti_date, 
        'sales_order_no'=>$request->sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'vendorId'=>$request->vendorId,
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
         
    );
//   DB::enableQueryLog();   
$QCStitchingInhouseList = QCStitchingInhouseMasterModel::findOrFail($request->qcsti_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$QCStitchingInhouseList->fill($data1)->save();

 
DB::table('qcstitching_inhouse_size_detail')->where('qcsti_code', $request->input('qcsti_code'))->delete();
DB::table('qcstitching_inhouse_size_detail2')->where('qcsti_code', $request->input('qcsti_code'))->delete();
DB::table('qcstitching_inhouse_detail')->where('qcsti_code', $request->input('qcsti_code'))->delete();
 
   $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array(
          
                    'qcsti_code'=>$request->qcsti_code,
                    'qcsti_date'=>$request->qcsti_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
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
                 
                  $data5[]=array(
          
                    'qcsti_code'=>$request->qcsti_code,
                    'qcsti_date'=>$request->qcsti_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
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
 
 
 $size_array=
 
 
                      $data3[]=array(
                  
                        'qcsti_code'=>$request->qcsti_code, 
                        'qcsti_date'=>$request->qcsti_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
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
 
                     
 
                      $data4[]=array(
                  
                        'qcsti_code'=>$request->qcsti_code, 
                        'qcsti_date'=>$request->qcsti_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
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
                          
                           
                          
              
              } // if loop avoid zero qty
            }
          QCStitchingInhouseDetailModel::insert($data2);
          QCStitchingInhouseRejectDetailModel::insert($data5);
          QCStitchingInhouseSizeDetailModel::insert($data3);
          QCStitchingInhouseSizeRejectDetailModel::insert($data4);
         
    }
           
           
   // $InsertSizeData=DB::select('call AddSizeQtyFromQCStitchingInhouse("'.$request->qcsti_code.'")');
           
           
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
            // DB::enableQueryLog();  
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
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required>
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
      $List = DB::select("SELECT qcstitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from qcstitching_inhouse_size_detail inner join color_master on 
      color_master.color_id=qcstitching_inhouse_size_detail.color_id where 
      qcstitching_inhouse_size_detail.vw_code='".$request->vw_code."' and
      qcstitching_inhouse_size_detail.color_id='".$row->color_id."'
       ");

  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
 
 
  
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
          
          if(isset($row->s1)) { $html.='<td >  <input style="width:80px; padding:2px;"     name="s1[]"  type="number" class="size_id" id="s1" value="0" required />'.$s1.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s1.'" min="0" name="s_1[]" class="size_id2" type="number" id="s_1" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s2)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s2[]" type="number" class="size_id" id="s2" value="0" required />'.$s2.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s2.'" min="0" name="s_2[]" class="size_id2" type="number" id="s_2" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s3)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s3[]" type="number" class="size_id" id="s3" value="0" required />'.$s3.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s3.'" min="0" name="s_3[]" class="size_id2" type="number" id="s_3" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s4)) { $html.='<td>   <input style="width:80px; padding:2px; "  name="s4[]" type="number" class="size_id" id="s4" value="0" required />'.$s4.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s4.'" min="0" name="s_4[]" class="size_id2" type="number" id="s_4" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s5)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s5[]" type="number" class="size_id" id="s5" value="0" required />'.$s5.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s5.'" min="0" name="s_5[]" class="size_id2" type="number" id="s_5" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s6)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s6[]" type="number" class="size_id" id="s6" value="0" required />'.$s6.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s6.'" min="0" name="s_6[]" class="size_id2" type="number" id="s_6" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s7)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s7[]" type="number" class="size_id" id="s7" value="0" required />'.$s7.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s7.'" min="0" name="s_7[]" class="size_id2" type="number" id="s_7" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s8)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s8[]" type="number" class="size_id" id="s8" value="0" required />'.$s8.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s8.'" min="0" name="s_8[]" class="size_id2" type="number" id="s_8" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s9)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s9[]" type="number" class="size_id" id="s9" value="0" required />'.$s9.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s9.'" min="0" name="s_9[]" class="size_id2" type="number" id="s_9" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s10)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s10[]" type="number" class="size_id" id="s10" value="0" required />'.$s10.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s10.'" min="0" name="s_10[]" class="size_id2" type="number" id="s_10" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s11)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s11[]" type="number" class="size_id" id="s11" value="0" required />'.$s11.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s11.'" min="0" name="s_11[]" class="size_id2" type="number" id="s_11" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s12)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s12[]" type="number" class="size_id" id="s12" value="0" required />'.$s12.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s12.'" min="0" name="s_12[]" class="size_id2" type="number" id="s_12" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s13)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s13[]" type="number" class="size_id" id="s13" value="0" required />'.$s13.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s13.'" min="0" name="s_13[]" class="size_id2" type="number" id="s_13" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s14)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s14[]" type="number" class="size_id" id="s14" value="0" required />'.$s14.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s14.'" min="0" name="s_14[]" class="size_id2" type="number" id="s_14" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s15)) { $html.='<td>  <input style="width:80px; padding:2px; " name="s15[]" type="number" class="size_id" id="s15" value="0" required />'.$s15.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s15.'" min="0" name="s_15[]" class="size_id2" type="number" id="s_15" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s16)) { $html.='<td>  <input style="width:80px; padding:2px; "   name="s16[]" type="number" class="size_id" id="s16" value="0" required />'.$s16.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s16.'" min="0" name="s_16[]" class="size_id2" type="number" id="s_16" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s17)) { $html.='<td>   <input style="width:80px; padding:2px; "  name="s17[]" type="number" class="size_id" id="s17" value="0" required />'.$s17.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s17.'" min="0" name="s_17[]" class="size_id2" type="number" id="s_17" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s18)) { $html.='<td>  <input style="width:80px; padding:2px; "  name="s18[]" type="number" class="size_id" id="s18" value="0" required />'.$s18.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s18.'" min="0" name="s_18[]" class="size_id2" type="number" id="s_18" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s19)) { $html.='<td> <input style="width:80px; padding:2px; "   name="s19[]" type="number" class="size_id" id="s19" value="0" required />'.$s19.'<input style="width:80px; padding:2px; background-color:#FAACAA;" max="'.$s19.'" min="0" name="s_19[]" class="size_id2" type="number" id="s_19" value="" placeholder="Rejected" required /> </td>';}
          if(isset($row->s20)) { $html.='<td>   <input style="width:80px; padding:2px; "   name="s20[]" type="number" class="size_id" id="s20" value="0" required />'.$s20.'<input style="width:80px; padding:2px; background-color:#FAACAA; " max="'.$s20.'" min="0" name="s_20[]" class="size_id2" type="number" id="s_20" value="" placeholder="Rejected" required /> </td>';}
          
          
          
          
          
          $html.='<td>'.($row->size_qty_total-$List[0]->size_qty_total).' 
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
          <input type="hidden" name="size_qty_total2[]" class="size_qty_total2" value="" id="size_qty_total2" style="width:80px; height:30px; float:left;"  readOnly required />
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
     
    public function destroy($id)
    {
        DB::table('qcstitching_inhouse_master')->where('qcsti_code', $id)->delete();
         DB::table('qcstitching_inhouse_size_detail2')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_size_detail')->where('qcsti_code', $id)->delete();
         DB::table('qcstitching_inhouse_size_reject_detail')->where('qcsti_code', $id)->delete();
        DB::table('qcstitching_inhouse_detail')->where('qcsti_code', $id)->delete();
        Session::flash('messagedelete', 'Deleted record successfully'); 
        
    }
}
