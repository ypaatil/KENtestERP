<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use Maatwebsite\Excel\Facades\Excel;
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
use App\Models\FirmModel;
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
use App\Models\LedgerDetailModel;

use App\Models\StitchingInhouseMasterModel;
use App\Models\StitchingInhouseDetailModel;
use App\Models\StitchingInhouseSizeDetailModel;

use App\Models\FinishingInhouseMasterModel;
use App\Models\FinishingInhouseDetailModel;
use App\Models\FinishingInhouseSizeDetailModel;

use App\Models\PackingInhouseMasterModel;
use App\Models\PackingInhouseDetailModel;
use App\Models\PackingInhouseSizeDetailModel;

use App\Models\FGTransferToLocationInwardModel;
use App\Models\FGTransferToLocationInwardDetailModel;
use App\Models\FGTransferToLocationInwardSizeDetailModel;
use App\Models\FGTransferToLocationModel;
use App\Models\FGTransferToLocationDetailModel;
use App\Models\LocationModel;
use App\Exports\FGSTOCKExport;
use Session;
use DataTables;
use DateTime;
use DateInterval;
use DatePeriod;
date_default_timezone_set("Asia/Kolkata"); 
setlocale(LC_MONETARY, 'en_IN');
class FGTransferToLocationInwardController extends Controller
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
        ->where('form_id', '108')
        ->first();
        
        $FGTransferToLocationInwardList = FGTransferToLocationInwardModel::join('usermaster', 'usermaster.userId', '=', 'fg_trasnfer_to_location_inward_master.userId', 'left outer')
        ->join('ledger_master as L1', 'L1.Ac_code', '=', 'fg_trasnfer_to_location_inward_master.Ac_code', 'left outer')
        ->join('location_master', 'location_master.loc_id', '=', 'fg_trasnfer_to_location_inward_master.from_loc_id', 'left outer')
        ->where('fg_trasnfer_to_location_inward_master.delflag','=', '0')
        ->where('fg_trasnfer_to_location_inward_master.fgti_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
        ->get(['fg_trasnfer_to_location_inward_master.*','usermaster.username','L1.Ac_name','location_master.location' ]);
  
  
        return view('FGTransferToLocationInwardList', compact('FGTransferToLocationInwardList','chekform'));
    }

    public function cartonPackingShowAll()
    { 
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '108')
        ->first();
        
        $FGTransferToLocationInwardList = FGTransferToLocationInwardModel::join('usermaster', 'usermaster.userId', '=', 'fg_trasnfer_to_location_inward_master.userId', 'left outer')
        ->join('ledger_master as L1', 'L1.Ac_code', '=', 'fg_trasnfer_to_location_inward_master.Ac_code', 'left outer')
        ->join('location_master', 'location_master.loc_id', '=', 'fg_trasnfer_to_location_inward_master.from_loc_id', 'left outer')
        ->where('fg_trasnfer_to_location_inward_master.delflag','=', '0') 
        ->get(['fg_trasnfer_to_location_inward_master.*','usermaster.username','L1.Ac_name','location_master.location' ]);
  
  
        return view('FGTransferToLocationInwardList', compact('FGTransferToLocationInwardList','chekform'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FGTransferToLocationInward'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
         
        $FGTCodeList = FGTransferToLocationModel::where('delflag','=', '0')->get(); 
        
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        return view('FGTransferToLocationInwardMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList', 'BuyerPurchaseOrderList','Ledger',  'counter_number','FirmList', 'LocationList','FGTCodeList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //echo '<pre>'; print_R($_POST);exit;
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
        ->where('c_name','=','C1')
        ->where('type','=','FGTransferToLocationInward')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
          
        $this->validate($request, [
             
                'fgti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required', 
                'order_rate'=>'required',
               
    ]);
 
    $sales_order_no=implode($request->sales_order_no,',');
 
     $RTV = isset($request->isRTV) ? $request->isRTV: "";
     if($RTV == 'on')
     {
         $isRTV = 1;
     }
     else
     {
         $isRTV = 0;
     }
    $Ac_code = isset($request->Ac_code) ? $request->Ac_code: 0;
    
    $data1=array
    (
        'fgti_code'=>$TrNo,
        'fgt_code'=>$request->fgt_code,  
        'fgti_date'=>$request->fgti_date, 
        'firm_id'=>$request->firm_id,
        'sales_order_no'=>$sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty,
        'order_rate'=>$request->order_rate,
        'vendor_amount'=>$request->order_amount,
        'narration'=>$request->narration,
        'buyer_location_id'=>$request->buyer_location_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
        'endflag'=>'0',
        'from_loc_id'=>$request->from_loc_id,
        'to_loc_id'=>$request->to_loc_id,
        'driver_name'=>$request->driver_name,
        'vehical_no'=>$request->vehical_no,
     );
       
    FGTransferToLocationInwardModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FGTransferToLocationInward'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array
                    (
					'fgti_code'=>$TrNo,
                    'fgti_date'=>$request->fgti_date,
                    'sales_order_no'=>$request->sales_order_nos[$x],
                    'Ac_code'=>$request->Ac_code, 
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'from_carton_no'=>$request->from_carton_no[$x],
                    'to_carton_no'=>$request->to_carton_no[$x],
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
                  
                        'fgti_code'=>$TrNo, 
                        'fgti_date'=>$request->fgti_date, 
                        'sales_order_no'=>$request->sales_order_nos[$x],
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'from_carton_no'=>$request->from_carton_no[$x],
                        'to_carton_no'=>$request->to_carton_no[$x],
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
                        'order_rate'=>$request->order_rate
                          );
                          
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
                     
                    $size_array1 = explode(',', $request->size_array[$x]); 
                    $size_qty_array1 = explode(',', $request->size_qty_array[$x]); 
                    $sales_order_nos = $request->sales_order_nos[$x]; 
                    $color_ids = $request->color_id[$x];
                    
                    // foreach($size_array1 as $key=>$szx)
                    // {
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $szx)->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                        
                    //     // $recordCount = DB::table('FGStockDataByTwo')
                    //     // ->where('code', $request->fgti_code)
                    //     // ->where('sales_order_no',$sales_order_nos )
                    //     // ->where('color_id', $color_ids)
                    //     // ->where('size_id', $szx)
                    //     // ->count();
                    //     // //DB::enableQueryLog();
                    //     // if($recordCount == 0)
                    //     // {
                    //         DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                         size_name,size_qty,location_id,data_type_id,color_id,size_id,is_sale)
                    //                         select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$sales_order_nos.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                         "'.$color_name.'","'.$size_name.'","'.$size_qty_array1[$key].'","'.$request->location_id.'",2,"'.$color_ids.'","'.$szx.'", 1'); 
                                            
                    //     // }
                    //               //  dd(DB::getQueryLog());
                    // }
                    
                    //  if($s1 > 0)
                    //  { 
                    
                    //     $size_array1 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array1[0])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                    //  //DB::enableQueryLog();

                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array1[0].'"'); 
                    //                   //  dd(DB::getQueryLog());
                    //  } 
                     
                    //  if($s2 > 0)
                    //  { 
                         
                    //     $size_array2 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array2[1])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array2[1].'"'); 
                    //  }
                     
                    //  if($s3 > 0)
                    //  { 
                         
                    //     $size_array3 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array3[2])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array3[2].'"'); 
                    //  }
                     
                    //  if($s4 > 0)
                    //  { 
                         
                    //     $size_array4 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array4[3])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array4[3].'"'); 
                    //  }
              
                    //  if($s5 > 0)
                    //  { 
                         
                    //     $size_array5 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array5[4])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array5[4].'"'); 
                    //  }
                     
                    //  if($s6 > 0)
                    //  { 
                         
                    //     $size_array6 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array6[5])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array6[5].'"'); 
                    //  }
                     
                    //  if($s7 > 0)
                    //  { 
                         
                    //     $size_array7 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array7[6])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array7[6].'"'); 
                    //  }
                     
                    //  if($s8 > 0)
                    //  { 
                         
                    //     $size_array8 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array8[7])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array8[7].'"'); 
                    //  }
                     
                    //  if($s9 > 0)
                    //  { 
                         
                    //     $size_array9 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array9[8])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array9[8].'"'); 
                    //  }
                     
                    //  if($s10 > 0)
                    //  { 
                         
                    //     $size_array10 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array10[9])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array10[9].'"'); 
                    //  }
                     
                    //  if($s11 > 0)
                    //  { 
                         
                    //     $size_array11 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array11[10])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array11[10].'"'); 
                    //  }
                     
                    //  if($s12 > 0)
                    //  { 
                         
                    //     $size_array12 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array12[11])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array12[11].'"'); 
                    //  }
                     
                    //  if($s13 > 0)
                    //  { 
                         
                    //     $size_array13 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array13[12])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array13[12].'"'); 
                    //  }
                     
                    //  if($s14 > 0)
                    //  { 
                         
                    //     $size_array14 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array14[13])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array14[13].'"'); 
                    //  }
                     
                    //  if($s15 > 0)
                    //  { 
                         
                    //     $size_array15 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array15[14])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array15[14].'"'); 
                    //  }
                     
                    //  if($s16 > 0)
                    //  { 
                         
                    //     $size_array16 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array16[15])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array16[15].'"'); 
                    //  }
                     
                    //  if($s17 > 0)
                    //  { 
                         
                    //     $size_array17 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array17[16])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array17[16].'"'); 
                    //  }
                     
                    //  if($s18 > 0)
                    //  { 
                         
                    //     $size_array18 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array18[17])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array18[17].'"'); 
                    //  }
                     
                    //  if($s19 > 0)
                    //  { 
                         
                    //     $size_array19 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array19[18])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array19[18].'"'); 
                    //  }
                     
                    //  if($s20 > 0)
                    //  { 
                         
                    //     $size_array20 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array20[19])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$TrNo.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array20[19].'"'); 
                    //  }
              
              } 
            }
          FGTransferToLocationInwardDetailModel::insert($data2);
          FGTransferToLocationInwardSizeDetailModel::insert($data3);
          
         
    }
    
        
    $InsertSizeData=DB::select('call AddSizeQtyFGTransferToLocationInward("'.$TrNo.'")');
           
    return redirect()->route('FGTransferToLocationInward.index')->with('message', 'Data Saved Succesfully');  
      
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
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        //   DB::enableQueryLog();
        $FGTransferToLocationInwardList = FGTransferToLocationInwardModel::find($id);
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
         
         $LedgerDetail  = LedgerDetailModel::where('ledger_details.ac_code',$FGTransferToLocationInwardList->Ac_code)->get();
         $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->where('Ac_code',$FGTransferToLocationInwardList->Ac_code)->get();
        
         $FGTCodeList = FGTransferToLocationModel::where('delflag','=', '0')->get(); 
         
         $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
                ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
                ->where('tr_code','=',$FGTransferToLocationInwardList->sales_order_no)->distinct()->get();
                
      
      
       $sales_order_no='';
          $SalesOrderList=explode(",",$FGTransferToLocationInwardList->sales_order_no);
            foreach($SalesOrderList as $List)
        {
            $sales_order_no=$sales_order_no."'".$List."',";
            
        }
        $sales_order_no=rtrim($sales_order_no,",");
        
      
      
      
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->whereIn('tr_code',$SalesOrderList)->DISTINCT()->get();
        
        $FGTransferToLocationInwardDetailList =FGTransferToLocationInwardDetailModel::where('fg_trasnfer_to_location_inward_detail.fgti_code','=', $FGTransferToLocationInwardList->fgti_code)->get();
        //  
       
        
             // DB::enableQueryLog(); 
        
        $S1= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')
        ->whereNotIn('buyer_purchse_order_master.tr_code',function($query){
        $query->select('fg_trasnfer_to_location_inward_master.sales_order_no')->from('fg_trasnfer_to_location_inward_master');
        });
        $sales_order_no='';
          $SalesOrderList=explode(",",$FGTransferToLocationInwardList->sales_order_no);
            foreach($SalesOrderList as $List)
        {
            $sales_order_no=$sales_order_no."'".$List."',";
            
        }
        $sales_order_no=rtrim($sales_order_no,",");
        
        
        
        $S2=FGTransferToLocationInwardModel::select('sales_order_no')->where('sales_order_no',$sales_order_no);
        
        $VendorWorkOrderList = $S1->union($S2)->get();
        
        
        $SalesOrder=explode(",",$sales_order_no);
        // echo $SalesOrder[0];
        // DB::enableQueryLog();  
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('tr_code',$SalesOrderList[0])->get();
     //echo    $BuyerPurchaseOrderMasterList[0]->sz_code;
    //       $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
         
        $sz_codes = isset($BuyerPurchaseOrderMasterList[0]->sz_code) ? $BuyerPurchaseOrderMasterList[0]->sz_code : 0;
         
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $sz_codes)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        //  DB::enableQueryLog();  
        $MasterdataList = DB::select("SELECT sales_order_detail.item_code, sales_order_detail.color_id, color_name,  
          sum(size_qty_total) as size_qty_total, ".$sizes." from sales_order_detail inner join color_master on 
          color_master.color_id=sales_order_detail.color_id where tr_code IN(".$sales_order_no.")
          group by sales_order_detail.color_id");
          
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
         
        
        return view('FGTransferToLocationInwardEdit',compact('FGTCodeList','FGTransferToLocationInwardList','FGTransferToLocationInwardDetailList','ColorList', 'FirmList','BuyerPurchaseOrderList','LedgerDetail',  'MasterdataList','SizeDetailList','FGTransferToLocationInwardList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger','LocationList' ));
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
             
                'fgti_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
               
    ]);
 
  
    //echo '<pre>';print_r($_POST);exit;
    $sales_order_no=implode($request->sales_order_no,',');
 
 
  
     $RTV = isset($request->isRTV) ? $request->isRTV: "";
     if($RTV == 'on')
     {
         $isRTV = 1;
     }
     else
     {
         $isRTV = 0;
     }
  
    $data1=array(
           
        'fgti_code'=>$request->fgti_code, 
        'fgt_code'=>$request->fgt_code,  
        'fgti_date'=>$request->fgti_date,
        'firm_id'=>$request->firm_id,
        'sales_order_no'=>$sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty,
        'order_rate'=>$request->order_rate,
        'vendor_amount'=>$request->order_amount,
        'narration'=>$request->narration,
        'buyer_location_id'=>$request->buyer_location_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
        'endflag'=>$request->endflag,
        'from_loc_id'=>$request->from_loc_id,
        'to_loc_id'=>$request->to_loc_id,
        'driver_name'=>$request->driver_name,
        'vehical_no'=>$request->vehical_no,
    );
//   DB::enableQueryLog();   
$PackingInhouseList = FGTransferToLocationInwardModel::findOrFail($request->fgti_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$PackingInhouseList->fill($data1)->save();

 
DB::table('fg_trasnfer_to_location_inward_size_detail')->where('fgti_code', $request->input('fgti_code'))->delete();
DB::table('fg_trasnfer_to_location_inward_size_detail2')->where('fgti_code', $request->input('fgti_code'))->delete();
DB::table('fg_trasnfer_to_location_inward_detail')->where('fgti_code', $request->input('fgti_code'))->delete(); 
//DB::table('FGStockDataByTwo')->where('code', $request->input('fgti_code'))->delete();

 $color_id= $request->color_id;
 //echo count($color_id);exit;
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array(
          
                    'fgti_code'=>$request->fgti_code,
                    'fgti_date'=>$request->fgti_date,
                    'sales_order_no'=>$request->sales_order_nos[$x],
                    'Ac_code'=>$request->Ac_code, 
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'from_carton_no'=>$request->from_carton_no[$x],
                    'to_carton_no'=>$request->to_carton_no[$x],
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
                  
                        'fgti_code'=>$request->fgti_code,
                        'fgti_date'=>$request->fgti_date, 
                        'sales_order_no'=>$request->sales_order_nos[$x],
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'from_carton_no'=>$request->from_carton_no[$x],
                        'to_carton_no'=>$request->to_carton_no[$x],
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
                        'order_rate'=>$request->order_rate
                          );
              
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
                     
                    //  if($s1 > 0)
                    //  { 
                      // if($x == 0)
                      // { 
                    $size_array1 = explode(',', $request->size_array[$x]); 
                    $size_qty_array1 = explode(',', $request->size_qty_array[$x]); 
                    $sales_order_nos = $request->sales_order_nos[$x];
                    $color_ids = $request->color_id[$x];
                    // foreach($size_array1 as $key=>$szx)
                    // {
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $szx)->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                        
                    //     // $recordCount = DB::table('FGStockDataByTwo')
                    //     // ->where('code', $request->fgti_code)
                    //     // ->where('sales_order_no',$sales_order_nos )
                    //     // ->where('color_id', $color_ids)
                    //     // ->where('size_id', $szx)
                    //     // ->count();
                    //     // //DB::enableQueryLog();
                    //     // if($recordCount == 0)
                    //     // {
                    //     //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //     //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //     //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$sales_order_nos.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //     //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$color_ids.'","'.$szx.'"'); 
                    //     // }
                        
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                         size_name,size_qty,location_id,data_type_id,color_id,size_id,is_sale)
                    //                         select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$sales_order_nos.'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                         "'.$color_name.'","'.$size_name.'","'.$size_qty_array1[$key].'","'.$request->location_id.'",2,"'.$color_ids.'","'.$szx.'", 1'); 
                    //               //  dd(DB::getQueryLog());
                    // }
                       //}
                    //  } 
                     
                    //  if($s2 > 0)
                    //  { 
                         
                    //     $size_array2 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array2[1])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array2[1].'"'); 
                    //  }
                     
                    //  if($s3 > 0)
                    //  { 
                         
                    //     $size_array3 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array3[2])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array3[2].'"'); 
                    //  }
                     
                    //  if($s4 > 0)
                    //  { 
                         
                    //     $size_array4 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array4[3])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array4[3].'"'); 
                    //  }
              
                    //  if($s5 > 0)
                    //  { 
                         
                    //     $size_array5 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array5[4])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array5[4].'"'); 
                    //  }
                     
                    //  if($s6 > 0)
                    //  { 
                         
                    //     $size_array6 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array6[5])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array6[5].'"'); 
                    //  }
                     
                    //  if($s7 > 0)
                    //  { 
                         
                    //     $size_array7 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array7[6])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array7[6].'"'); 
                    //  }
                     
                    //  if($s8 > 0)
                    //  { 
                         
                    //     $size_array8 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array8[7])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array8[7].'"'); 
                    //  }
                     
                    //  if($s9 > 0)
                    //  { 
                         
                    //     $size_array9 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array9[8])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array9[8].'"'); 
                    //  }
                     
                    //  if($s10 > 0)
                    //  { 
                         
                    //     $size_array10 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array10[9])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array10[9].'"'); 
                    //  }
                     
                    //  if($s11 > 0)
                    //  { 
                         
                    //     $size_array11 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array11[10])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array11[10].'"'); 
                    //  }
                     
                    //  if($s12 > 0)
                    //  { 
                         
                    //     $size_array12 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array12[11])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array12[11].'"'); 
                    //  }
                     
                    //  if($s13 > 0)
                    //  { 
                         
                    //     $size_array13 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array13[12])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array13[12].'"'); 
                    //  }
                     
                    //  if($s14 > 0)
                    //  { 
                         
                    //     $size_array14 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array14[13])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array14[13].'"'); 
                    //  }
                     
                    //  if($s15 > 0)
                    //  { 
                         
                    //     $size_array15 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array15[14])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array15[14].'"'); 
                    //  }
                     
                    //  if($s16 > 0)
                    //  { 
                         
                    //     $size_array16 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array16[15])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array16[15].'"'); 
                    //  }
                     
                    //  if($s17 > 0)
                    //  { 
                         
                    //     $size_array17 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array17[16])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array17[16].'"'); 
                    //  }
                     
                    //  if($s18 > 0)
                    //  { 
                         
                    //     $size_array18 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array18[17])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array18[17].'"'); 
                    //  }
                     
                    //  if($s19 > 0)
                    //  { 
                         
                    //     $size_array19 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array19[18])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array19[18].'"'); 
                    //  }
                     
                    //  if($s20 > 0)
                    //  { 
                         
                    //     $size_array20 = explode(',', $request->size_array[$x]); 
                         
                    //     $sizeDetailData = DB::table('size_detail')->select('size_name')->where('size_id', $size_array20[19])->first();
                     
                    //     $size_name = isset($sizeDetailData->size_name) ? $sizeDetailData->size_name : "";
                     
                    //     DB::SELECT('INSERT INTO FGStockDataByTwo(code,entry_date,ac_name,sales_order_no,mainstyle_name,substyle_name,fg_name,style_no,style_description,color_name,
                    //                     size_name,size_qty,location_id,data_type_id,color_id,size_id)
                    //                     select "'.$request->fgti_code.'","'.$request->fgti_date.'","'.$ac_name.'","'.$request->sales_order_nos[$x].'","'.$mainstyle_name.'","'.$substyle_name.'","'.$fg_name.'","'.$request->style_no.'","'.$request->style_description.'",
                    //                     "'.$color_name.'","'.$size_name.'",0,"'.$request->location_id.'",2,"'.$request->color_id[$x].'","'.$size_array20[19].'"'); 
                    //  }
                     
              }  
            }
          FGTransferToLocationInwardDetailModel::insert($data2);
          FGTransferToLocationInwardSizeDetailModel::insert($data3);
          
    }
    
           
           
     $InsertSizeData=DB::select('call AddSizeQtyFGTransferToLocationInward("'.$request->fgti_code.'")');
           
           
     return redirect()->route('FGTransferToLocationInward.index')->with('message', 'Data Saved Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
   public function getPackingInhouseDetails(Request $request)
    { 
       // $sales_order_no= $request->input('sales_order_no');
        $SalesOrders=explode(',',$request->sales_order_no);
        $MasterdataList = DB::select("select Ac_code,tr_code, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code ='".$SalesOrders[0]."'");
        return json_encode($MasterdataList);
    }   
      



    public function GetMaxMinvalueList(Request $request)
    { 
        
     $color_id=$request->input('color_id');
     $sales_order_no=$request->sales_order_no;
     $isRTV = isset($request->isRTV) ? $request->isRTV: 0;
     $Ac_code = isset($request->Ac_code) ? $request->Ac_code: 0;
     $sizeList='';
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$sales_order_no)->first();
     $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
       $sizes='';
      $no=1;
         foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'ifnull(sum(s'.$no.'),0) as s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
     
    //   $CompareList = DB::select("SELECT ifnull(carton_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
    //   ifnull(sum(size_qty_total),0) as size_qty_total from carton_packing_inhouse_size_detail inner join color_master on 
    //   color_master.color_id=carton_packing_inhouse_size_detail.color_id where 
    //   carton_packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
    //   carton_packing_inhouse_size_detail.color_id='".$color_id."'
    //   ");
        
  
       $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
    //   DB::enableQueryLog(); 
    
      
      if($isRTV == 1)
      {
          
            $CompareList = DB::select("SELECT ifnull(carton_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
              ifnull(sum(size_qty_total),0) as size_qty_total from carton_packing_inhouse_size_detail 
              inner join color_master on  color_master.color_id=carton_packing_inhouse_size_detail.color_id 
              inner join fg_trasnfer_to_location_inward_master on fg_trasnfer_to_location_inward_master.fgti_code=carton_packing_inhouse_size_detail.fgti_code
              where 
              carton_packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
              carton_packing_inhouse_size_detail.color_id='".$color_id."'  AND fg_trasnfer_to_location_inward_master.isRTV = 1");
       
          //DB::enableQueryLog(); 
            $List = DB::select("SELECT  ifnull(packing_inhouse_size_detail.item_code,0) as item_code,ifnull(packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizex.", 
              ifnull(sum(size_qty_total),0) as size_qty_total from packing_inhouse_size_detail 
              inner join packing_inhouse_master on  packing_inhouse_master.pki_code=packing_inhouse_size_detail.pki_code 
              inner join color_master on  color_master.color_id=packing_inhouse_size_detail.color_id 
              where 
              packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
              packing_inhouse_size_detail.Ac_code ='".$Ac_code."' and
              packing_inhouse_size_detail.color_id='".$color_id."' AND packing_inhouse_master.packing_type_id=2");
          
      }
      else
      {
             
            $CompareList = DB::select("SELECT ifnull(carton_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
              ifnull(sum(size_qty_total),0) as size_qty_total from carton_packing_inhouse_size_detail 
              inner join color_master on color_master.color_id=carton_packing_inhouse_size_detail.color_id 
              inner join fg_trasnfer_to_location_inward_master on fg_trasnfer_to_location_inward_master.fgti_code=carton_packing_inhouse_size_detail.fgti_code
              where 
              carton_packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
              carton_packing_inhouse_size_detail.color_id='".$color_id."' AND fg_trasnfer_to_location_inward_master.isRTV = 0");
               //DB::enableQueryLog(); 
            $List = DB::select("SELECT  ifnull(packing_inhouse_size_detail.item_code,0) as item_code,ifnull(packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizex.", 
              ifnull(sum(size_qty_total),0) as size_qty_total from packing_inhouse_size_detail 
              inner join packing_inhouse_master on  packing_inhouse_master.pki_code=packing_inhouse_size_detail.pki_code 
              inner join color_master on color_master.color_id=packing_inhouse_size_detail.color_id 
              where 
              packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
              packing_inhouse_size_detail.Ac_code ='".$Ac_code."' and
              packing_inhouse_size_detail.color_id='".$color_id."'  AND packing_inhouse_master.packing_type_id!=2");   
             //dd(DB::getQueryLog());
           
      }


      
       
   if(isset($List[0]->s_1)) { $s1=((intval($List[0]->s_1))-(intval($CompareList[0]->s1))); $sizeList=$sizeList.$s1.' as s1, ';}
   if(isset($List[0]->s_2)) { $s2=((intval($List[0]->s_2))-(intval($CompareList[0]->s2))); $sizeList=$sizeList.$s2.' as s2, ';}
   if(isset($List[0]->s_3)) { $s3=((intval($List[0]->s_3))-(intval($CompareList[0]->s3))); $sizeList=$sizeList.$s3.' as s3, ';}
   if(isset($List[0]->s_4)) { $s4=((intval($List[0]->s_4))-(intval($CompareList[0]->s4))); $sizeList=$sizeList.$s4.' as s4, ';}
   if(isset($List[0]->s_5)) { $s5=((intval($List[0]->s_5))-(intval($CompareList[0]->s5))); $sizeList=$sizeList.$s5.' as s5, ';}
   if(isset($List[0]->s_6)) { $s6=((intval($List[0]->s_6))-(intval($CompareList[0]->s6))); $sizeList=$sizeList.$s6.' as s6, ';}
   if(isset($List[0]->s_7)) { $s7=((intval($List[0]->s_7))-(intval($CompareList[0]->s7))); $sizeList=$sizeList.$s7.' as s7, ';}
   if(isset($List[0]->s_8)) { $s8=((intval($List[0]->s_8))-(intval($CompareList[0]->s8))); $sizeList=$sizeList.$s8.' as s8, ';}
   if(isset($List[0]->s_9)) { $s9=((intval($List[0]->s_9))-(intval($CompareList[0]->s9))); $sizeList=$sizeList.$s9.' as s9, ';}
   if(isset($List[0]->s_10)) { $s10=((intval($List[0]->s_10))-(intval($CompareList[0]->s10))); $sizeList=$sizeList.$s10.' as s10, ';}
   if(isset($List[0]->s_11)) { $s11=((intval($List[0]->s_11))-(intval($CompareList[0]->s11))); $sizeList=$sizeList.$s11.' as s11, ';}
   if(isset($List[0]->s_12)) { $s12=((intval($List[0]->s_12))-(intval($CompareList[0]->s12))); $sizeList=$sizeList.$s12.' as s12, ';}
   if(isset($List[0]->s_13)) { $s13=((intval($List[0]->s_13))-(intval($CompareList[0]->s13))); $sizeList=$sizeList.$s13.' as s13, ';}
   if(isset($List[0]->s_14)) { $s14=((intval($List[0]->s_14))-(intval($CompareList[0]->s14))); $sizeList=$sizeList.$s14.' as s14, ';}
   if(isset($List[0]->s_15)) { $s15=((intval($List[0]->s_15))-(intval($CompareList[0]->s15))); $sizeList=$sizeList.$s15.' as s15, ';}
   if(isset($List[0]->s_16)) { $s16=((intval($List[0]->s_16))-(intval($CompareList[0]->s16))); $sizeList=$sizeList.$s16.' as s16, ';}
   if(isset($List[0]->s_17)) { $s17=((intval($List[0]->s_17))-(intval($CompareList[0]->s17))); $sizeList=$sizeList.$s17.' as s17, ';}
   if(isset($List[0]->s_18)) { $s18=((intval($List[0]->s_18))-(intval($CompareList[0]->s18))); $sizeList=$sizeList.$s18.' as s18, ';}
   if(isset($List[0]->s_19)) { $s19=((intval($List[0]->s_19))-(intval($CompareList[0]->s19))); $sizeList=$sizeList.$s19.' as s19, ';}
   if(isset($List[0]->s_20)) { $s20=((intval($List[0]->s_20))-(intval($CompareList[0]->s20))); $sizeList=$sizeList.$s20.' as s20, ';}
       
       
      // echo $s1;
       
   
       $MasterdataList=DB::select("select ".$List[0]->item_code." as item_code,".$sizeList.($nox-1)." as size_count");
  
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


public function getBuyerLocationList(Request $request)
{
    $LocationList = DB::table('ledger_details')->select('sr_no','consignee_address','site_code')
    ->where('ac_code','=',$request->Ac_code)->get();
    
    
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Location List--</option>';
        } else {
        $html = '';
        
        foreach ($LocationList as $row) 
        {$html .= '<option value="'.$row->sr_no.'">('.$row->site_code.') '.$row->consignee_address.'</option>';}
    }
      return response()->json(['html' => $html]);
}



 
public function getSalesOrderList(Request $request)
{
    $BuyerPurchaseOrderList = BuyerPurchaseOrderMasterModel::select('tr_code')
    ->where('Ac_code','=',$request->Ac_code)->get();
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Sales Order List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Sales Order List--</option>';
        
        foreach ($BuyerPurchaseOrderList as $row) 
        {$html .= '<option value="'.$row->tr_code.'">'.$row->tr_code.'</option>';}
    }
      return response()->json(['html' => $html]);
}







 public function PKI_GetOrdarQtyByRow(Request $request)
  {
      $SalesOrders=explode(',',$request->sales_order_no);
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->whereIn('buyer_purchse_order_master.tr_code',$SalesOrders)->first();
     
      
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::whereIn('tr_code',$SalesOrders)->first();
      
      
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        // DB::enableQueryLog();  
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$BuyerPurchaseOrderDetailList->tr_code."'");

//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
      $html = '';
     
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;height:30px;" readonly></td>';
                
        $html.='<td> <input style="width:80px; float:left;"  name="from_carton_no[]"   type="number" id="carton_no" value="" required /></td>';
        $html.='<td> <input style="width:80px; float:left;"  name="to_carton_no[]"   type="number" id="carton_no" value="" required /></td>'; 
      $html.=' <td>
      
        <input type="hidden" name="hidden_sales_order_no[]" id="hidden_sales_order_no" value="" />
        <select name="sales_order_nos[]" class="select2-select"  id="sales_order_nos0"  onchange="CalculateQtyRowProxx(this);" style="width:150px; height:30px;" required>
        <option value="">--Sales Order No--</option>';

        foreach($SalesOrders as  $value)
        {
            $html.='<option value="'.$value.'"';
           
            $html.='>'.$value.'</option>';
        }
        
        $html.='</select></td>';
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2-select" onchange="CalculateQtyRowProColor(this);" id="color_id0" style="width:200px; height:30px;" required>
        <option value="">--Select Color--</option>';
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;" max="0" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
          $html.='<td>
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readonly />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
          $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';

          
          
           $html.='</tr>';

        
         

      return response()->json(['html' => $html]);
         
  }

  
  public function PKI_GetOrderQty1(Request $request)
  {
      $SalesOrders=explode(',',$request->sales_order_no);
       
      $lastSalesOrder = $request->latestSelected; 
       
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->whereIn('buyer_purchse_order_master.tr_code',$SalesOrders)->first();
         

      $BuyerPurchaseOrderMasterList1 = BuyerPurchaseOrderMasterModel::select('sz_code')->whereIn('buyer_purchse_order_master.tr_code',[$lastSalesOrder])->first();
        
      $sz_code = $BuyerPurchaseOrderMasterList1->sz_code;
      
      
     // echo $lastSalesOrder;exit;
      // DB::enableQueryLog();  
      $BuyerPurchaseOrderMasterList2 = BuyerPurchaseOrderMasterModel::select('tr_code')->where('buyer_purchse_order_master.sz_code','=',$sz_code)->whereIn('buyer_purchse_order_master.tr_code',$SalesOrders)->get();
       // dd(DB::getQueryLog());
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::whereIn('tr_code',$SalesOrders)->first();
     
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $sz_code)->get();
      $sizes='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        // DB::enableQueryLog();  
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$BuyerPurchaseOrderDetailList->tr_code."'");

     $html = '';
     
     $rowCount = count($BuyerPurchaseOrderMasterList2);
     
      if($rowCount == 1)
      {
      $html .= '  
      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2 tbl_'.$BuyerPurchaseOrderMasterList->sz_code.'">
              <thead>
              <tr>
              <th>SrNo</th>
               <th nowrap>From Carton  No</th>
               <th nowrap>To Carton  No</th>
                <th nowrap>Sales Order No</th>
              <th>Color</th>';
                 foreach ($SizeDetailList as $sz) 
                  {
                      $html.='<th>'.$sz->size_name.'</th>';
                  }
                  $html.=' 
                  <th>Total Qty</th>
                  <th>Add/Remove</th>
                  </tr>
              </thead>
              <tbody  id="CartonData">';
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
                
        $html.='<td> <input style="width:80px; float:left;"  name="from_carton_no[]"   type="number" id="from_carton_no" value="" required /></td>';
        $html.='<td> <input style="width:80px; float:left;"  name="to_carton_no[]"   type="number" id="to_carton_no" value="" required /></td>';
         
      $html.=' <td>
      
        <input type="hidden" name="hidden_sales_order_no[]" id="hidden_sales_order_no" value="" />
        <select name="sales_order_nos[]" class="select2-select select2 fg_select_'.$sz_code.'"  id="sales_order_nos0" onchange="CalculateQtyRowProxx(this);" style="width:150px; height:30px;" required>
        <option value="">--Sales Order No--</option>';

        foreach($BuyerPurchaseOrderMasterList2 as  $value)
        {
            $html.='<option value="'.$value->tr_code.'"';
           
            $html.='>'.$value->tr_code.'</option>';
        }
        
        $html.='</select></td>';
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:200px; height:30px;" onchange="CalculateQtyRowProColor(this);" required>
        <option value="">--Select Color--</option>';
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;" max="0" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
          $html.='<td>
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
          $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';

          
          
           $html.='</tr>';

          $no=$no+1;
        
          $html.=' 
            </tbody>
            </table>';

      }
      
      
      $html1='<option>--Sales Order No--</option>';
      foreach($BuyerPurchaseOrderMasterList2 as  $value)
      {
            $html1.='<option value="'.$value->tr_code.'"';
           
            $html1.='>'.$value->tr_code.'</option>';
      } 
      
      return response()->json(['html' => $html, 'html1' => $html1, 'sz_code' => $sz_code]);
         
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
     
public function PKI_GetColorList(Request $request)
{
        //  DB::enableQueryLog();  
      

    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
     'color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$request->sales_order_no)->DISTINCT()->get();
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    
    
    if (!$request->sales_order_no)
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
 
 <td> <select name="item_code[]"  id="item_code'.$no.'" style="width:200px; height:30px;" required>
<option value="">--Item List--</option>';
foreach($ItemList as  $rowitem)
{
    $html.='<option value="'.$rowitem->item_code.'"';

    $rowitem->item_code == $codefetch->item_code ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowitem->item_name.'</option>';
}
$html.='</select></td>
 
 <td> <select name="class_id[]"  id="class_id'.$no.'" style="width:200px; height:30px;" required>
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
                <td> <select name="item_codes[]"  id="item_code'.$no.'" style="width:200px; height:30px;" required>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_ids[]"  id="class_id'.$no.'" style="width:200px; height:30px;" required>
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
                <td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:200px; height:30px;" required>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:200px; height:30px;" required>
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
     
     
     
     
     
     
      public function CartonPackingPrint($fgti_code)
    {
        
         
    //   DB::enableQueryLog();
       
         $FGTransferToLocationInward = FGTransferToLocationInwardModel::join('usermaster', 'usermaster.userId', '=', 'fg_trasnfer_to_location_inward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fg_trasnfer_to_location_inward_master.Ac_code')
        ->where('fg_trasnfer_to_location_inward_master.fgti_code', $fgti_code)
         ->get(['fg_trasnfer_to_location_inward_master.*','usermaster.username','ledger_master.Ac_name','fg_trasnfer_to_location_inward_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
       
       $SalesOrderList=explode(",", $FGTransferToLocationInward[0]->sales_order_no);
        
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::whereIn('tr_code',$SalesOrderList)->get();
                   
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
        //   DB::enableQueryLog();  
          $CartonPackingList = DB::select("SELECT from_carton_no,to_carton_no,  carton_packing_inhouse_size_detail.sales_order_no,	carton_packing_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from 	carton_packing_inhouse_size_detail 
        
        inner join color_master on color_master.color_id=	carton_packing_inhouse_size_detail.color_id 
        where fgti_code='".$FGTransferToLocationInward[0]->fgti_code."' group by carton_packing_inhouse_size_detail.sales_order_no,	carton_packing_inhouse_size_detail.color_id, carton_packing_inhouse_size_detail.from_carton_no");
        //           $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('CartonPackingPrint', compact('FGTransferToLocationInward','CartonPackingList','SizeDetailList','FirmDetail'));
      
    }
     
      
    public function FGStockReport(Request $request)
    {
       
    if ($request->ajax()) 
        { 
           
            $FinishedGoodsStock = DB::select("SELECT FG.entry_date, FG.`ac_name`,job_status_master.job_status_name,sales_order_costing_master.total_cost_value, brand_master.brand_name, FG.`sales_order_no`, FG.`mainstyle_name`, FG.`substyle_name`, FG.`fg_name`, FG.`style_no`, 
                FG.`style_description`, FG.`color_name`, FG.`size_name`,ifnull(sum(FG.`size_qty`),0)  as packing_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate,buyer_purchse_order_master.sam FROM FGStockDataByOne as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                inner join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
                left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
                where FG.data_type_id=1 group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
            return Datatables::of($FinishedGoodsStock)
           ->addColumn('fob_rate',function ($row) {
    
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
          ->addColumn('stock',function ($row) {
    
             $stock =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    
             return money_format('%!.0n',round($stock));
           })
          ->addColumn('Value',function ($row) {
              
               if($row->total_cost_value == 0)
                {
                     $Value =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->order_rate);
                }
                else
                {
                    $Value =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->total_cost_value);
                }
              
    
             return  money_format('%!.0n',round($Value));
           })
           ->addColumn('packing_qty',function ($row) {
    
             $packing_qty = $row->packing_qty;
    
             return money_format('%!.0n',round($packing_qty));
             
           })
           ->addColumn('carton_pack_qty',function ($row) {
    
             $carton_pack_qty = $row->carton_pack_qty;
    
             return money_format('%!.0n',round($carton_pack_qty));
           })
           ->addColumn('transfer_qty',function ($row) {
    
             $transfer_qty = $row->transfer_qty;
    
             return money_format('%!.0n',round($transfer_qty));
           })
             ->rawColumns(['fob_rate','stock','Value','packing_qty','carton_pack_qty','transfer_qty'])
             
             ->make(true);
    
            }
            
          return view('FGStockReport');
        
    }
 

    public function FGStockReportTrial1(Request $request)
    {
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='FGStockReportTrial?currentDate=".date('Y-m-d')."';</script>";
        }
 
        // $packingData = DB::SELECT("SELECT sum(size_qty) as total_packing from FGStockDataByTwo where data_type_id=1 AND entry_date <='" .$currentDate."'");
        // $cartonData = DB::SELECT("SELECT sum(size_qty) as total_carton from FGStockDataByTwo where data_type_id=2 AND entry_date <='" .$currentDate."'");
        // $transferData = DB::SELECT("SELECT sum(size_qty) as total_transfer from FGStockDataByTwo where data_type_id=3 AND entry_date <='" .$currentDate."'");
        
        
        // $t_packing = isset($packingData[0]->total_packing) ? $packingData[0]->total_packing : 0;
        // $t_carton = isset($cartonData[0]->total_carton) ? $cartonData[0]->total_carton : 0;
        // $t_transfer = isset($transferData[0]->total_transfer) ? $transferData[0]->total_transfer : 0;
        // $t_stock = $t_packing - $t_carton - $t_transfer;
        
        $total_packing = 0;
        $total_carton = 0;
        $total_transfer = 0;
        $total_stock = 0;
        $total_stock1 = 0;
        
        $currentDate = $request->currentDate ? $request->currentDate : "";
        
        
         
        // $FinishedGoodsStock1 = DB::select("SELECT sales_order_costing_master.total_cost_value,ifnull((SELECT sum(d3.size_qty)from FGStockDataByTwo as d3 where d3.data_type_id=1 and d3.sales_order_no=FG.sales_order_no and d3.color_id=FG.color_id ".$d3."
        //         and d3.size_id=FG.size_id GROUP BY d3.size_id),0) as packing_qty, ifnull((SELECT sum(d2.size_qty) from FGStockDataByTwo as d2 
        //         where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id ".$d2."  GROUP BY d2.size_id),0) as carton_pack_qty ,
        //         ifnull((SELECT  sum(d1.size_qty)from FGStockDataByTwo as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no  and d1.color_id=FG.color_id ".$d1."
        //         and d1.size_id=FG.size_id GROUP BY d1.size_id),0)  as transfer_qty,buyer_purchse_order_master.order_rate FROM FGStockDataByTwo as`FG`   
        //         inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
        //         inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
        //         inner join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
        //         left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
        //         where 1 ".$fg." group by FG.sales_order_no,FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
       
         $t_value = 0;
        // foreach($FinishedGoodsStock1 as $row)
        // {
        //     if($row->total_cost_value == 0)
        //     {
        //          $t_value +=($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->order_rate);
        //     }
        //     else
        //     {
        //         $t_value +=($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->total_cost_value);
        //     }
        // }
        $total_value = 0;
        $total_value1 = 0;
        if ($request->ajax()) 
        {  
        
            // DB::enableQueryLog();   
            $FinishedGoodsStock = DB::table('FGStockDataByTwo as FG')
                         ->select("FG.data_type_id","FG.ac_name","FG.sales_order_no","FG.mainstyle_name","FG.color_name","FG.size_name","FG.color_id","FG.size_id",
                            "sales_order_costing_master.total_cost_value","buyer_purchse_order_master.order_rate","brand_master.brand_name","job_status_master.job_status_name","buyer_purchse_order_master.sam")
                         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
                         ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
                         ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
                         ->leftjoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'FG.sales_order_no') 
                         ->where('FG.data_type_id','=',1)
                         ->groupBy('FG.entry_date','FG.color_id','FG.size_id')    
                         ->get();
            //dd(DB::getQueryLog());  
            // $FinishedGoodsStock = DB::select("SELECT FG.data_type_id,FG.ac_name,FG.sales_order_no,FG.mainstyle_name,FG.color_name,FG.size_name, sum(size_qty) as size_qty, 
            //         sales_order_costing_master.total_cost_value,buyer_purchse_order_master.order_rate,brand_master.brand_name,job_status_master.job_status_name,buyer_purchse_order_master.sam FROM FGStockDataByTwo as`FG`   
            //         inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
            //         inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
            //         inner join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
            //         left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
            //         where FG.entry_date <='" .$currentDate."'  GROUP BY FG.sales_order_no,FG.entry_date,FG.color_id,FG.size_id order by FG.color_id asc, FG.size_id asc");
            
          
           return Datatables::of($FinishedGoodsStock)
          ->addColumn('fob_rate',function ($row) {
    
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
          ->addColumn('stock',function ($row) use ($currentDate)
          { 
              $packingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get();
                         
              $cartonData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',2)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get();
                         
              $tramsferData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',3)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get();
                         
              $packing = isset($packingData[0]->size_qty) ? $packingData[0]->size_qty : 0; 
              $carton = isset($cartonData[0]->size_qty) ? $cartonData[0]->size_qty : 0; 
              $transfer = isset($tramsferData[0]->size_qty) ? $tramsferData[0]->size_qty : 0; 
              
              
              $stock =($packing - $carton - $transfer);
    
              return money_format('%!.0n',round($stock));
          })
          ->addColumn('Value',function ($row) use ($currentDate)
          {
               
             
              $packingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get();
                         
              $cartonData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',2)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get();
                         
              $tramsferData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',3)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get();
                         
              $packing_qty = isset($packingData[0]->size_qty) ? $packingData[0]->size_qty : 0; 
              $carton_qty = isset($cartonData[0]->size_qty) ? $cartonData[0]->size_qty : 0; 
              $transfer_qty = isset($tramsferData[0]->size_qty) ? $tramsferData[0]->size_qty : 0; 
              
               
              
              if($row->total_cost_value == 0)
              {
                     $Value =($packing_qty - $carton_qty - $transfer_qty) * ($row->order_rate);
              }
              else
              {
                    $Value =($packing_qty - $carton_qty - $transfer_qty) * ($row->total_cost_value);
              }
              
             return  money_format('%!.0n',round($Value));
          })
          ->addColumn('packing_qty',function ($row) use ($currentDate)
          {
     
              $packingData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',1)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get(); 
                         
              $packing_qty = isset($packingData[0]->size_qty) ? $packingData[0]->size_qty : 0;  
               
              return money_format('%!.0n',round($packing_qty));
             
          })
          ->addColumn('carton_pack_qty',function ($row) use ($currentDate)
          { 
              
              $cartonData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',2)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get(); 
                          
              $carton_pack_qty = isset($cartonData[0]->size_qty) ? $cartonData[0]->size_qty : 0;  
               
              return money_format('%!.0n',round($carton_pack_qty));
          })
          ->addColumn('transfer_qty',function ($row) use ($currentDate)
          {
                            
              $tramsferData = DB::table('FGStockDataByTwo as FG')
                         ->select(DB::raw("sum(size_qty) as size_qty"))
                         ->where('FG.data_type_id','=',3)
                         ->where('FG.sales_order_no','=',$row->sales_order_no)
                         ->where('FG.size_id','=',$row->size_id)
                         ->where('FG.color_id','=',$row->color_id)
                         ->where('FG.entry_date','<=',$currentDate)   
                         ->get(); 
                         
              $transfer_qty = isset($tramsferData[0]->size_qty) ? $tramsferData[0]->size_qty : 0; 
              
               
             return money_format('%!.0n',round($transfer_qty));
          })
             ->rawColumns(['fob_rate','stock','Value','packing_qty','carton_pack_qty','transfer_qty'])
             
             ->make(true);
    
            }
            
          return view('FGStockReportTrial',compact('currentDate','total_packing','total_carton','total_transfer','total_value','total_stock','total_value1','total_stock1'));
        
    }
   
    public function FGStockReportTrial(Request $request)
    {
        
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='FGStockReportTrial?currentDate=".date('Y-m-d')."';</script>";
        }
        $total_packing = 0;
        $total_carton = 0;
        $total_transfer = 0;
        $total_stock = 0;
        $total_stock1 = 0;
        $total_value = 0;
        $total_value1 = 0;
        
        $salesOrderList = DB::SELECT("SELECT tr_code FROM buyer_purchse_order_master WHERE delflag = 0 AND og_id != 4");
        $jobStatusList = DB::SELECT("SELECT job_status_id,job_status_name FROM job_status_master WHERE delflag = 0");
        $brandList = DB::SELECT("SELECT brand_id,brand_name FROM brand_master WHERE delflag = 0");
        $mainStyleList = DB::SELECT("SELECT mainstyle_id,mainstyle_name FROM main_style_master WHERE delflag = 0");
        $buyerList = DB::SELECT("SELECT ac_code,ac_name FROM ledger_master WHERE delflag = 0");
        $orderTypeList = DB::SELECT("SELECT orderTypeId,order_type FROM order_type_master WHERE delflag = 0");
        $colorList = DB::SELECT("SELECT color_master.color_id,color_master.color_name FROM color_master 
                        INNER JOIN buyer_purchase_order_detail ON buyer_purchase_order_detail.color_id = color_master.color_id 
                        WHERE color_master.delflag = 0 GROUP BY buyer_purchase_order_detail.color_id");
        
        
        return view('FGStockReportTrial',compact('currentDate','total_packing','total_carton','total_transfer','total_value','total_stock','total_value1','total_stock1','salesOrderList','jobStatusList','brandList','mainStyleList','buyerList','orderTypeList','colorList'));
    }
    
    public function fg_stock_export(Request $request)
    {
     
      return Excel::download(new FGSTOCKExport(), 'FGSTOCK.csv');
      
       return view('FGStockReport');
    }
     
    // public function FGStockReportMD(Request $request,$isOpening,$DFilter)
    // {
    //     if ($request->ajax()) 
    //     {
           
    //         $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
        
    //         if($DFilter == 'd')
    //         {
    //             $filterDate = " AND carton_packing_inhouse_size_detail2.fgti_date <= '".date('Y-m-d')."'";
    //             $packingFilterDate = " AND packing_inhouse_size_detail2.pki_date <= '".date('Y-m-d')."'";
    //             $transferPackingFilterDate = " AND transfer_packing_inhouse_size_detail2.tpki_date <= '".date('Y-m-d')."'";
    //         }
    //         else if($DFilter == 'm')
    //         {
    //             $cartonPackingFilterDate = ' AND carton_packing_inhouse_size_detail2.fgti_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
    //             $packingFilterDate = ' AND packing_inhouse_size_detail2.pki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
    //             $transferPackingFilterDate = ' AND transfer_packing_inhouse_size_detail2.tpki_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
    //         }
    //         else if($DFilter == 'y')
    //         {
    //             $cartonPackingFilterDate = ' AND carton_packing_inhouse_size_detail2.fgti_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
    //             $packingFilterDate = ' AND packing_inhouse_size_detail2.pki_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
    //             $transferPackingFilterDate = ' AND transfer_packing_inhouse_size_detail2.tpki_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
    //         }
    //         else
    //         {
    //             $cartonPackingFilterDate = "";
    //             $packingFilterDate = "";
    //             $transferPackingFilterDate = "";
    //         }
            
    //         if($isOpening == 1)
    //         {
    //             $po_status = ' AND buyer_purchse_order_master.job_status_id in (1)';
    //         }
    //         else if($isOpening == 2)
    //         {
    //             $po_status = ' AND buyer_purchse_order_master.job_status_id in (2,3)';
    //         }
    //     //DB::enableQueryLog();
    //     $FinishedGoodsStock = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no,
    //         color_master.color_name,color_master.style_img_path, brand_master.brand_name, 
    //         size_detail.size_name, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
    //         mainstyle_name, job_status_master.job_status_name,
            
    //         (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
    //         inner join fg_trasnfer_to_location_inward_master on fg_trasnfer_to_location_inward_master.fgti_code=carton_packing_inhouse_size_detail2.fgti_code
    //         where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
    //         carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
    //         and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
    //         and fg_trasnfer_to_location_inward_master.endflag=1 ".$cartonPackingFilterDate."
 
    //         ) as 'carton_pack_qty',
            
    //         (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
    //         inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
    //         where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
    //         transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
    //         and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
    //         and transfer_packing_inhouse_size_detail2.usedFlag=1 ".$transferPackingFilterDate."
    //         ) as 'transfer_qty',
    //             order_rate
    //         FROM `packing_inhouse_size_detail2`
    //         INNER JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
    //         INNER JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
    //         LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
    //         LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
    //         LEFT JOIN color_master on color_master.color_id=packing_inhouse_size_detail2.color_id
    //         LEFT JOIN size_detail on size_detail.size_id = packing_inhouse_size_detail2.size_id
    //         LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id WHERE 1 ".$packingFilterDate." 
    //         ".$po_status."
    //         GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
     
    //         //dd(DB::getQueryLog());
    //         return Datatables::of($FinishedGoodsStock)
    //         ->addIndexColumn()
    //         ->addColumn('imagePath',function ($row) 
    //         {
                
    //         return '<a href="images/'.$row->style_img_path.'" target="_blank"><img src="thumbnail/'.$row->style_img_path.'"  width="100" height="100"  align="center" /></a>';
            
    //       })
    //      ->addColumn('Carton_Paking_Qty',function ($row) {
    
    //          $CartonpackingQty =($row->packing_grn_qty - $row->carton_pack_qty - $row->transfer_qty);
    
    //          return $CartonpackingQty;
    //       })
    //       ->addColumn('Value',function ($row) {
    
    //          $Value =($row->packing_grn_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->order_rate);
    
    //          return $Value;
    //       })
           
    //          ->rawColumns(['imagePath','Carton_Paking_Qty','Value'])
             
    //          ->make(true);
    
    //         }
            
    //       return view('FGStockReport');
        
    // } 
    public function FGStockReportMD(Request $request,$isOpening,$DFilter)
    {
        if($DFilter == 'd')
        {
            $filterDate = " AND FG.entry_date <= '".date('Y-m-d')."'";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND FG.entry_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else if($DFilter == 'y')
        {
            $filterDate = ' AND FG.entry_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else
        {
            $filterDate = "";
        }
        
        if($isOpening == 1)
        {
            $po_status = ' AND buyer_purchse_order_master.job_status_id in (1)';
        }
        else if($isOpening == 2)
        {
            $po_status = ' AND buyer_purchse_order_master.job_status_id in (2,3)';
        }
    
       if ($request->ajax()) 
        { 
           
            $FinishedGoodsStock = DB::select("SELECT FG.entry_date, FG.`ac_name`,job_status_master.job_status_name,sales_order_costing_master.total_cost_value, brand_master.brand_name, FG.`sales_order_no`,
            FG.`mainstyle_name`, FG.`substyle_name`, FG.`fg_name`, FG.`style_no`, buyer_purchse_order_master.sam, buyer_purchse_order_master.order_rate,
                FG.`style_description`, FG.`color_name`, FG.`size_name`,ifnull(sum(FG.`size_qty`),0)  as packing_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByOne as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByOne as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByOne as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                inner join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                inner join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
                 left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
                where FG.data_type_id=1 ".$filterDate." ".$po_status." group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
            
            // $totalpack = 0;
            // $totalcarton = 0;
            // $totaltransfer = 0;
            // $totalstock = 0;
            // $totalvalue = 0;
            
            
            // foreach($FinishedGoodsStock as $fg)
            // {
            //     $totalpack = $totalpack + $fg->packing_qty;
            //     $totalcarton = $totalcarton + $fg->carton_pack_qty;
            //     $totaltransfer = $totaltransfer + $fg->transfer_qty;
                
            //     //$totalstock = $totalpack - $totalcarton - $totaltransfer;
            //   // $totalvalue = ($totalstock) * $fg->order_rate;
            // }
            // $totalstock = $totalpack - $totalcarton - $totaltransfer;
            // echo $totalvalue.'<br/>';
            // echo $totalstock.'<br/>';exit;
            
            return Datatables::of($FinishedGoodsStock)
              
         ->addColumn('fob_rate',function ($row) {
    
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
          ->addColumn('stock',function ($row) {
    
             $stock =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty);
    
             return $stock;
           })
          ->addColumn('Value',function ($row) {
              
              
               if($row->total_cost_value == 0)
                {
                    $fob_rate =  round($row->order_rate,4);
                }
                else
                {
                    $fob_rate = round($row->total_cost_value,4);
                }
    
             $Value =($row->packing_qty - $row->carton_pack_qty - $row->transfer_qty) * $fob_rate;
    
             return $Value;
           })
           
             ->rawColumns(['stock','Value'])
             
             ->make(true);
    
            }
            
          return view('FGStockReport');
    }
     
     
    public function FGLocationStockReport(Request $request)
    {
        
     if ($request->ajax()) {
                
    $arrayFiled=array();
              $loc_id=$request->loc_id;
           //  DB::enableQueryLog();
         $StockQty=0;
         $packing_grn_qty=0;
         $carton_pack_qty=0;
         $transfer_qty=0;
         $loc_transfer_qty=0;
         $loc_rec_transfer_qty=0;
         
         
       $FinishedGoodsStock = DB::select("SELECT   '".$loc_id."' as loc_id,ledger_master.Ac_name, job_status_master.job_status_name,  buyer_purchse_order_master.tr_code as sales_order_no,
           buyer_purchase_order_size_detail.color_id,buyer_purchase_order_size_detail.size_id,mainstyle_name, buyer_purchse_order_master.style_no,
           color_master.color_name,color_master.style_img_path, brand_master.brand_name, buyer_purchse_order_master.order_rate,
            size_detail.size_name 
            
            FROM `buyer_purchase_order_size_detail`
            
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=buyer_purchase_order_size_detail.tr_code
            LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
            LEFT JOIN ledger_master on  ledger_master.Ac_code=buyer_purchase_order_size_detail.Ac_code
            LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
             LEFT JOIN main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
            LEFT JOIN color_master on color_master.color_id=buyer_purchase_order_size_detail.color_id
            LEFT JOIN size_detail on size_detail.size_id = buyer_purchase_order_size_detail.size_id
            where buyer_purchse_order_master.tr_code='".$request->sales_order_no."'
            GROUP by buyer_purchase_order_size_detail.tr_code, buyer_purchase_order_size_detail.color_id, buyer_purchase_order_size_detail.size_id");
            // dd(DB::getQueryLog());
          
            return Datatables::of($FinishedGoodsStock)
            ->addIndexColumn()
            ->addColumn('imagePath',function ($row) 
            {
                
            return '<a href="images/'.$row->style_img_path.'" target="_blank"><img src="thumbnail/'.$row->style_img_path.'"  width="100" height="100"  align="center" /></a>';
            
           })
         ->addColumn('arrayFiled',function ($row) {
    
    
     
        $Packing_GRN_QTY=DB::select(" SELECT ifnull(sum(size_qty),0) as packing_grn_qty from packing_inhouse_size_detail2 
        inner join packing_inhouse_master on packing_inhouse_master.pki_code=packing_inhouse_size_detail2.pki_code
        where packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."' 
        and packing_inhouse_size_detail2.size_id='".$row->size_id."'
        and   packing_inhouse_size_detail2.location_id='".$row->loc_id."'"
        
        );
   
     
     // DB::enableQueryLog();
        $CartonPackingQTY=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
        inner join fg_trasnfer_to_location_inward_master on fg_trasnfer_to_location_inward_master.fgti_code=carton_packing_inhouse_size_detail2.fgti_code
        where carton_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
        and carton_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        and fg_trasnfer_to_location_inward_master.endflag=1");
     // dd(DB::getQueryLog());
    
    
        $TransferO2OQty=DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
        inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        where transfer_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."'
        and transfer_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        and transfer_packing_inhouse_size_detail2.usedFlag=1
        ");
   
    
    
        $LocationTransferQty=DB::select("SELECT ifnull(sum(size_qty),0) as loc_transfer_qty from loc_transfer_packing_inhouse_size_detail2 
        inner join loc_transfer_packing_inhouse_master on loc_transfer_packing_inhouse_master.ltpki_code=loc_transfer_packing_inhouse_size_detail2.ltpki_code
        where loc_transfer_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        loc_transfer_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."' 
        and loc_transfer_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        and   loc_transfer_packing_inhouse_size_detail2.from_loc_id='".$row->loc_id."'
          ");
    
    
    
        $LocationReecivedQty=DB::select("SELECT ifnull(sum(size_qty),0) as loc_rec_transfer_qty from loc_transfer_packing_inhouse_size_detail2 
        inner join loc_transfer_packing_inhouse_master on loc_transfer_packing_inhouse_master.ltpki_code=loc_transfer_packing_inhouse_size_detail2.ltpki_code
        where loc_transfer_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        loc_transfer_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."' 
        and loc_transfer_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        and   loc_transfer_packing_inhouse_size_detail2.to_loc_id='".$row->loc_id."'
         ");
    
                  
         $packing_grn_qty=isset($Packing_GRN_QTY[0]) ? $Packing_GRN_QTY[0]->packing_grn_qty : 0 ;
         if($packing_grn_qty>0){
         $carton_pack_qty=isset($CartonPackingQTY[0]) ? $CartonPackingQTY[0]->carton_pack_qty : 0;
         }
         else
         {
             $carton_pack_qty=0;
         }
         $transfer_qty=isset($TransferO2OQty[0]) ? $TransferO2OQty[0]->transfer_qty : 0;
         $loc_transfer_qty=isset($LocationTransferQty[0]) ? $LocationTransferQty[0]->loc_transfer_qty : 0;
         $loc_rec_transfer_qty=isset($LocationReecivedQty[0]) ? $LocationReecivedQty[0]->loc_rec_transfer_qty : 0 ;
         
         $StockQty=$packing_grn_qty -$carton_pack_qty-$transfer_qty -$loc_transfer_qty + $loc_rec_transfer_qty;
         $Value =$StockQty * ($row->order_rate);
           
            $arrayFiled=array(
                'packing_grn_qty'=>money_format('%!.0n',$packing_grn_qty),
                'carton_pack_qty'=>money_format('%!.0n',$carton_pack_qty),
                'transfer_qty'=>money_format('%!.0n',$transfer_qty),
                'loc_transfer_qty'=>money_format('%!.0n',$loc_transfer_qty),
                'loc_rec_transfer_qty'=>money_format('%!.0n',$loc_rec_transfer_qty),
                'StockQty'=>money_format('%!.0n',$StockQty),
                'Value'=>money_format('%!i',$Value)
                );
            
                     
             return   $arrayFiled;   })
           
        //     ->addColumn('Value',function ($row)     {
        //           $StockQty= $packing_grn_qty - $carton_pack_qty - $transfer_qty - $loc_transfer_qty + $loc_rec_transfer_qty;
        //         $Value =$StockQty * ($row->order_rate);
                
        //       return $Value;
              
        //   })
           
        //   ->addColumn('packing_grn_qty',function ($row)   {
        //     $Packing_GRN_QTY=DB::select(" SELECT ifnull(sum(size_qty),0) as packing_grn_qty from packing_inhouse_size_detail2 
        //     inner join packing_inhouse_master on packing_inhouse_master.pki_code=packing_inhouse_size_detail2.pki_code
        //     where packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        //     packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."' 
        //     and packing_inhouse_size_detail2.size_id='".$row->size_id."'
        //     and   packing_inhouse_size_detail2.location_id='".$row->loc_id."'");
        //      return $Packing_GRN_QTY[0]->packing_grn_qty;
        //   })
           
        //   ->addColumn('carton_pack_qty',function ($row)   {
        //       $CartonPackingQTY=DB::select("SELECT ifnull(sum(size_qty),0) as carton_pack_qty from carton_packing_inhouse_size_detail2 
        //         inner join fg_trasnfer_to_location_inward_master on fg_trasnfer_to_location_inward_master.fgti_code=carton_packing_inhouse_size_detail2.fgti_code
        //         where carton_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        //         carton_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."'
        //         and carton_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        //         and fg_trasnfer_to_location_inward_master.endflag=1");
             
             
             
        //      return $CartonPackingQTY[0]->carton_pack_qty;
        //   })
           
        //   ->addColumn('transfer_qty',function ($row){
             
             
        //         $TransferO2OQty=DB::select("SELECT ifnull(sum(size_qty),0) as transfer_qty from transfer_packing_inhouse_size_detail2 
        //         inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
        //         where transfer_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        //         transfer_packing_inhouse_size_detail2.main_sales_order_no='".$row->sales_order_no."'
        //         and transfer_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        //         and transfer_packing_inhouse_size_detail2.usedFlag=1
        //         ");
             
             
             
        //      return $TransferO2OQty[0]->transfer_qty;
        //   })
           
        //   ->addColumn('loc_transfer_qty',function ($row)     {
             
             
        //     $LocationTransferQty=DB::select("SELECT ifnull(sum(size_qty),0) as loc_transfer_qty from loc_transfer_packing_inhouse_size_detail2 
        //     inner join loc_transfer_packing_inhouse_master on loc_transfer_packing_inhouse_master.ltpki_code=loc_transfer_packing_inhouse_size_detail2.ltpki_code
        //     where loc_transfer_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        //     loc_transfer_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."' 
        //     and loc_transfer_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        //     and   loc_transfer_packing_inhouse_size_detail2.from_loc_id='".$row->loc_id."'
        //       ");
             
             
        //      return $LocationTransferQty[0]->loc_transfer_qty;
        //   })
           
        //   ->addColumn('loc_rec_transfer_qty',function ($row)     {
             
             
        //     $LocationReecivedQty=DB::select("SELECT ifnull(sum(size_qty),0) as loc_rec_transfer_qty from loc_transfer_packing_inhouse_size_detail2 
        //     inner join loc_transfer_packing_inhouse_master on loc_transfer_packing_inhouse_master.ltpki_code=loc_transfer_packing_inhouse_size_detail2.ltpki_code
        //     where loc_transfer_packing_inhouse_size_detail2.color_id='".$row->color_id."' and 
        //     loc_transfer_packing_inhouse_size_detail2.sales_order_no='".$row->sales_order_no."' 
        //     and loc_transfer_packing_inhouse_size_detail2.size_id='".$row->size_id."'
        //     and   loc_transfer_packing_inhouse_size_detail2.to_loc_id='".$row->loc_id."'
        //      ");
             
             
             
        //      return $LocationReecivedQty[0]->loc_rec_transfer_qty;
        //   })
              
             ->rawColumns(['imagePath',  "arrayFiled", "arrayFiled", 'packing_grn_qty','carton_pack_qty','transfer_qty','loc_transfer_qty','loc_rec_transfer_qty'])
             
             ->make(true);
    
            }
            
          return view('GetLocationFGStockReport');
        
    }
     
    public function FGStockSummaryReport(Request $request)
    {
        $job_status_id = isset($request->job_status_id) ? $request->job_status_id : 0;
        $tr_date = isset($request->tr_date) ? $request->tr_date : date('Y-m-d');
        
        if($job_status_id == 1)
        {
            
             $FinishedGoodsStock = DB::select("SELECT buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.tr_code, buyer_purchse_order_master.brand_id, Ac_name,brand_master.brand_name, 
                mainstyle_name, buyer_purchse_order_master.order_rate,ifnull(sales_order_costing_master.total_cost_value,0) as total_cost_value, buyer_purchse_order_master.mainstyle_id
                FROM buyer_purchse_order_master
                left JOIN sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
                left JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                left JOIN ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
                LEFT JOIN main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
                Where buyer_purchse_order_master.job_status_id =".$job_status_id."
                GROUP BY buyer_purchse_order_master.brand_id,buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.mainstyle_id");
        }
        else if($job_status_id == 2)
        {
            
             $FinishedGoodsStock = DB::select("SELECT buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.tr_code, buyer_purchse_order_master.brand_id, Ac_name,brand_master.brand_name, 
                mainstyle_name, buyer_purchse_order_master.order_rate,ifnull(sales_order_costing_master.total_cost_value,0) as total_cost_value, buyer_purchse_order_master.mainstyle_id
                FROM buyer_purchse_order_master
                left JOIN sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
                left JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                left JOIN ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
                LEFT JOIN main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
                Where buyer_purchse_order_master.job_status_id !=1
                GROUP BY buyer_purchse_order_master.brand_id,buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.mainstyle_id");
        }
        else
        {
            $FinishedGoodsStock = DB::select("SELECT buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.tr_code, buyer_purchse_order_master.brand_id, Ac_name,brand_master.brand_name, 
                mainstyle_name, buyer_purchse_order_master.order_rate,ifnull(sales_order_costing_master.total_cost_value,0) as total_cost_value, buyer_purchse_order_master.mainstyle_id
                FROM buyer_purchse_order_master
                left JOIN sales_order_costing_master on sales_order_costing_master.sales_order_no=buyer_purchse_order_master.tr_code
                left JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
                left JOIN ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
                LEFT JOIN main_style_master on main_style_master.mainstyle_id=buyer_purchse_order_master.mainstyle_id
                GROUP BY buyer_purchse_order_master.brand_id,buyer_purchse_order_master.Ac_code,buyer_purchse_order_master.mainstyle_id");
        }
          return view('FGStockSummaryReport', compact('FinishedGoodsStock','job_status_id','tr_date'));
        
    }
    
    
    public function FGStockOrderWiseSummaryReport(Request $request)
    {
        $FinishedGoodsStock = DB::select("SELECT buyer_purchse_order_master.Ac_code,
            buyer_purchse_order_master.brand_id, Ac_name,  
            brand_master.brand_name,  mainstyle_name,
            ifnull(sum(packing_inhouse_detail.size_qty_total),0) as 'packing_grn_qty',
            order_rate
            FROM `packing_inhouse_detail`
            left JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_detail.sales_order_no
            left JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            left JOIN ledger_master on ledger_master.ac_code=buyer_purchse_order_master.Ac_code
            LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_inhouse_detail.mainstyle_id
            GROUP BY buyer_purchse_order_master.brand_id,  buyer_purchse_order_master.tr_code");
    
          return view('FGStockSummaryReport', compact('FinishedGoodsStock'));
        
    }
     
    
    public function GetCartonPackingReport()
    {
         
         $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where delflag=0 and og_id!=4');
         
         return view('GetCartonPackingReport', compact('SalesOrderList'));
    }
      
    public function CartonPackingReport(Request $request)
    {
  
        $sales_order_no = $request->sales_order_no; 
        $job_status_id = $request->job_status_id; 
        
        return view('rptCartonPackingDashboard', compact('sales_order_no', 'job_status_id'));
        
    }
    
    public function LoadCartonPackingReport(Request $request)
    {
        ini_set('memory_limit', '10G');
        $sales_order_filter = '';
        if($request->sales_order_no != 0)
        {
            $sales_order_filter .= " AND cpsd.sales_order_no='".$request->sales_order_no."'";
        }
        
        
        $job_status_filter = '';
        if($request->job_status_id != 2)
        {
            $job_status_filter .= " AND cpim.endflag='".$request->job_status_id."'";
        }
        
        $CartonPackingList = DB::select("SELECT 
                                cpim.fgti_code, 
                                cpsd.from_carton_no, 
                                cpsd.to_carton_no, 
                                cpsd.sales_order_no,
                                cpsd.color_id, 
                                cm.color_name, 
                                sd.size_name,
                                sum(cpsd.size_qty) as size_qty, 
                                (size_qty_total) as size_qty_total,
                                (stm.sale_code) as sale_codes,
                                (stm.sale_date) as sale_dates
                            FROM 
                                carton_packing_inhouse_size_detail2 cpsd
                            LEFT JOIN 
                                color_master cm ON cm.color_id = cpsd.color_id 
                            LEFT JOIN  
                                fg_trasnfer_to_location_inward_master cpim ON cpim.fgti_code = cpsd.fgti_code
                            LEFT JOIN  
                                size_detail sd ON sd.size_id = cpsd.size_id
                            INNER JOIN
                                sale_transaction_master stm ON FIND_IN_SET(cpim.fgti_code, carton_packing_nos)
                            WHERE 
                               1 ".$sales_order_filter." ".$job_status_filter."
                            GROUP BY 
                                cpsd.fgti_code,
                                cpsd.sales_order_no,
                                cpsd.color_id,
                                cpsd.size_id"); 
          $no=1; 
          $totalAmt=0; 
          $totalQty=0;
          $html = "";
          foreach ($CartonPackingList as $row) 
          { 
                                    
            $html .='<tr>  
                  <td>'.$no.'</td> 
                  <td>'.$row->fgti_code.'</td> 
                  <td>'.$row->from_carton_no.'</td> 
                  <td>'.$row->to_carton_no.'</td> 
                  <td>'.$row->sale_codes.'</td> 
                  <td>'.(date('d-m-Y', strtotime($row->sale_dates))).'</td> 
                  <td>'.$row->sales_order_no.'</td> 
                  <td>'.$row->color_name.'</td> 
                  <td>'.$row->size_name.'</td> 
                  <td>'.$row->size_qty.'</td>  
            </tr>';

            $no=$no+1; 
            $totalQty = $totalQty + $row->size_qty;
        }
        
        return response()->json(['html' => $html]);
    }
     
     
     
       public function GetLocationFGStockReport()
     {
         
         $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where delflag=0 and og_id!=4');
         $LocationList=DB::select('select loc_id, location from location_master where delflag=0');
         
         
         
         return view('GetLocationFGStockReport', compact('SalesOrderList','LocationList'));
     }
     
     
     
     
     
     
    public function destroy($id)
    {
        DB::table('fg_trasnfer_to_location_inward_master')->where('fgti_code', $id)->delete();
        DB::table('fg_trasnfer_to_location_inward_size_detail2')->where('fgti_code', $id)->delete();
        DB::table('fg_trasnfer_to_location_inward_size_detail')->where('fgti_code', $id)->delete();
        DB::table('fg_trasnfer_to_location_inward_detail')->where('fgti_code', $id)->delete();
        // DB::table('FGStockDataByTwo')->where('code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    
     
    
    
     public function FGStockReport_2(Request $request)
    {
       
     if ($request->ajax()) {
                
               // $fgti_codes=explode(",",$CPKIList->fgti_code);
              //  DB::enableQueryLog();  
            //DB::enableQueryLog();
            $FinishedGoodsStock = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no,
            color_master.color_name,color_master.style_img_path, brand_master.brand_name, 
            size_detail.size_name, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty',
            mainstyle_name, job_status_master.job_status_name,
            
            (SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
            inner join fg_trasnfer_to_location_inward_master on fg_trasnfer_to_location_inward_master.fgti_code=carton_packing_inhouse_size_detail2.fgti_code
            where carton_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            carton_packing_inhouse_size_detail2.sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and carton_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and fg_trasnfer_to_location_inward_master.endflag=1
 
            ) as 'carton_pack_qty',
            
            (SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
            inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
            where transfer_packing_inhouse_size_detail2.color_id=packing_inhouse_size_detail2.color_id and 
            transfer_packing_inhouse_size_detail2.main_sales_order_no=packing_inhouse_size_detail2.sales_order_no 
            and transfer_packing_inhouse_size_detail2.size_id=packing_inhouse_size_detail2.size_id
            and transfer_packing_inhouse_size_detail2.usedFlag=1
            
             
            ) as 'transfer_qty',
                order_rate
            FROM `packing_inhouse_size_detail2`
            LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
            LEFT JOIN job_status_master on  job_status_master.job_status_id=buyer_purchse_order_master.job_status_id
            LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
            LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
            LEFT JOIN color_master on color_master.color_id=packing_inhouse_size_detail2.color_id
            LEFT JOIN size_detail on size_detail.size_id = packing_inhouse_size_detail2.size_id
            LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id
            GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");
            //dd(DB::getQueryLog());
            //   $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);
            
            return Datatables::of($FinishedGoodsStock)
            ->addIndexColumn()
            ->addColumn('imagePath',function ($row) 
            {
                
            return '<a href="images/'.$row->style_img_path.'" target="_blank"><img src="thumbnail/'.$row->style_img_path.'"  width="100" height="100"  align="center" /></a>';
            
           })
         ->addColumn('Carton_Paking_Qty',function ($row) {
    
             $CartonpackingQty =($row->packing_grn_qty - $row->carton_pack_qty - $row->transfer_qty);
    
             return $CartonpackingQty;
           })
          ->addColumn('Value',function ($row) {
    
             $Value =($row->packing_grn_qty - $row->carton_pack_qty - $row->transfer_qty) * ($row->order_rate);
    
             return $Value;
           })
           
             ->rawColumns(['imagePath','Carton_Paking_Qty','Value'])
             
             ->make(true);
    
            }
            
          return view('FGStockReport_2');
        
    }
       
    public function checkDifferentSizeGroup(Request $request)
    {
         $first_sales_order_no = $request->sales_order_no[0]; 
         $sales_order_no = $request->sales_order_no; 
         $cnt = 0;
         $cnt1 = 0;
         $mismatched =  "";
         if($sales_order_no != "")
         {
             foreach($sales_order_no as $row)
             { 
                
                $SalesOrderList=DB::select('select sz_code from buyer_purchse_order_master where delflag=0  AND tr_code="'.$first_sales_order_no.'"');
                $fssz_code = isset($SalesOrderList[0]->sz_code) ? $SalesOrderList[0]->sz_code :"";
                //DB::enableQueryLog();
                $isExists = DB::select('select count(*) as count from buyer_purchse_order_master where delflag=0 AND tr_code="'.$row.'" AND sz_code='.$fssz_code);
                //dd(DB::getQueryLog());
                $count = isset($isExists[0]->count) ? $isExists[0]->count: 0;
                
                if($count == 0)
                {
                    $cnt1 = 1;
                    $mismatched = $row;
                }
                
                $cnt++;
             }
         }
         
         return response()->json(['cnt' => $cnt1,'mismatched'=>$mismatched]);  
    }
    
     public function LoadFGStockReportTrial(Request $request)
    { 
          
        //   $FinishedGoodsStock = DB::table('FGStockDataByTwo as FG')
        //                  ->select("FG.data_type_id","FG.ac_name","FG.sales_order_no","FG.mainstyle_name","FG.color_name","FG.size_name","FG.color_id","FG.size_id",
        //                     "sales_order_costing_master.total_cost_value","buyer_purchse_order_master.order_rate","brand_master.brand_name","job_status_master.job_status_name","buyer_purchse_order_master.sam")
        //                  ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
        //                  ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        //                  ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
        //                  ->leftjoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'FG.sales_order_no') 
        //                  ->where('FG.data_type_id','=',1)
        //                  ->groupBy('FG.entry_date','FG.color_id','FG.size_id')    
        //                  ->get();
         $currentDate = $request->currentDate ? $request->currentDate : "";  
         $Ac_code = $request->ac_code; 
         $sales_order_no = $request->sales_order_no;
         $brand_id = $request->brand_id;
         $mainstyle_id = $request->mainstyle_id; 
         $color_id = $request->color_id; 
         $job_status_id = $request->job_status_id; 
         $orderTypeId = $request->orderTypeId; 
         
             
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
         
           
        if($color_id != "")
        {
            $filter .= " AND buyer_purchse_order_master.color_id='".$color_id."'";
        } 
        
           
        if($orderTypeId != "")
        {
            $filter .= " AND buyer_purchse_order_master.order_type='".$orderTypeId."'";
        } 
          
        if($currentDate != "")
        {
            $filter .= " AND (
                    (FG.data_type_id IN (1, 3) AND FG.entry_date <= '".$currentDate."') OR 
                    (FG.data_type_id = 2 AND FG.invoice_date <= '".$currentDate."')
                )";
        } 
          
        if($job_status_id == 1)
        {
                $filter .= " AND buyer_purchse_order_master.job_status_id = 1";
        }
        else if($job_status_id == 2)
        {
               $filter .= " AND buyer_purchse_order_master.job_status_id != 1";
        } 
        
        $html = [];
        
        //   $perPage = 1000; 
        //   $page = $request->input('page', 1); 
            
        //   $FinishedGoodsStock = DB::table('FGStockDataByTwo as FG')
        //                      ->select("FG.code","FG.data_type_id","FG.ac_name","FG.sales_order_no","FG.mainstyle_name","FG.color_name","FG.size_name","FG.color_id","FG.size_id",
        //                         "sales_order_costing_master.total_cost_value","buyer_purchse_order_master.order_rate","brand_master.brand_name","job_status_master.job_status_name","buyer_purchse_order_master.sam")
        //                      ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'FG.sales_order_no')
        //                      ->join('job_status_master', 'job_status_master.job_status_id', '=', 'buyer_purchse_order_master.job_status_id')
        //                      ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id')
        //                      ->leftjoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'FG.sales_order_no') 
        //                      ->whereIn('FG.data_type_id',[1,2,3])
        //                      ->groupBy('sales_order_no','color_id', 'size_id')
        //                      ->orderBy('FG.entry_date','asc') 
        //                      ->get(); 
            //DB::enableQueryLog();
            $FinishedGoodsStock = DB::SELECT("SELECT 
                                    FG.code,
                                    FG.data_type_id,
                                    FG.ac_name,
                                    FG.sales_order_no,
                                    FG.mainstyle_name,
                                    FG.color_name,
                                    FG.size_name,
                                    FG.color_id,
                                    FG.size_id,
                                    sales_order_costing_master.total_cost_value,
                                    buyer_purchse_order_master.order_rate,
                                    brand_master.brand_name, 
                                    buyer_purchse_order_master.job_status_id,
                                    buyer_purchse_order_master.sam,
                                    order_type_master.order_type,
                                    buyer_purchse_order_master.order_close_date,
                                    SUM(CASE WHEN FG.data_type_id = 1 AND FG.entry_date <= '".$currentDate."'  THEN FG.size_qty ELSE 0 END) AS packing_qty,
                                    SUM(CASE WHEN FG.data_type_id = 2 AND FG.is_sale = 0 AND FG.invoice_date <= '".$currentDate."'  THEN FG.size_qty ELSE 0 END) AS carton_pack_qty,
                                    SUM(CASE WHEN FG.data_type_id = 3 AND FG.entry_date <= '".$currentDate."'  THEN FG.size_qty ELSE 0 END) AS transfer_qty
                                FROM 
                                    FGStockDataByTwo AS FG
                                INNER JOIN 
                                    buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no 
                                LEFT JOIN 
                                    brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                                LEFT JOIN 
                                    order_type_master ON order_type_master.orderTypeId = buyer_purchse_order_master.order_type
                                LEFT JOIN 
                                    sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
                                WHERE 1 ".$filter."
                                GROUP BY 
                                    FG.sales_order_no, FG.color_id, FG.size_id
                                ORDER BY 
                                    FG.entry_date ASC");
                
            //dd(DB::getQueryLog());                   
            $total_packing = 0;
            $total_carton = 0;
            $total_transfer = 0;
            $total_stock = 0; 
            $total_value = 0; 
            $srno = 1;    
            foreach($FinishedGoodsStock as $row)
            {
                
                //   $packingData = ""; 
                //   $cartonData = "";                
                //   $tramsferData = "";
            
                //   $packingData = DB::table('FGStockDataByTwo as FG')
                //              ->select(DB::raw("sum(size_qty) as size_qty"))
                //              ->where('FG.data_type_id','=',1)
                //              ->where('FG.sales_order_no','=',$row->sales_order_no)
                //              ->where('FG.size_id','=',$row->size_id)
                //              ->where('FG.color_id','=',$row->color_id)
                //              ->where('FG.entry_date','<=',$currentDate)   
                //              ->groupBy('FG.size_id') 
                //              ->get();
             
                //      // DB::enableQueryLog();
                //     $cartonData = DB::table('FGStockDataByTwo as FG')
                //                  ->join('temp_sales_transaction', function ($join) {
                //                         $join->on('temp_sales_transaction.fgti_code', '=', 'FG.code');
                //                         $join->on('temp_sales_transaction.sales_order_no', '=', 'FG.sales_order_no');  
                //                  })
                //                  ->select(DB::raw("sum(FG.size_qty) as size_qty"))
                //                  ->where('FG.data_type_id','=',2)
                //                  ->where('FG.sales_order_no','=',$row->sales_order_no)
                //                  ->where('FG.size_id','=',$row->size_id)
                //                  ->where('FG.color_id','=',$row->color_id)
                //                  ->where('temp_sales_transaction.sale_date','<=',$currentDate)  
                //                  ->groupBy('FG.size_id')  
                //                  ->get();
                                 
                //         //   dd(DB::getQueryLog());            
                //         $tramsferData = DB::table('FGStockDataByTwo as FG')
                //                  ->select(DB::raw("sum(size_qty) as size_qty"))
                //                  ->where('FG.data_type_id','=',3)
                //                  ->where('FG.sales_order_no','=',$row->sales_order_no)
                //                  ->where('FG.size_id','=',$row->size_id)
                //                  ->where('FG.color_id','=',$row->color_id)
                //                  ->where('FG.entry_date','<=',$currentDate)  
                //                  ->groupBy('FG.size_id')  
                //                  ->get();
                                  
                //     // }
                     
                $packing_qty = isset($row->packing_qty) ? $row->packing_qty : 0; 
                $carton_pack_qty = isset($row->carton_pack_qty) ? $row->carton_pack_qty : 0; 
                $transfer_qty = isset($row->transfer_qty) ? $row->transfer_qty : 0; 
                
                $stock  =   $packing_qty - $carton_pack_qty - $transfer_qty;
                if($row->total_cost_value == 0)
                {
                     $value = $stock * $row->order_rate;
                     $fob_rate =  number_format($row->order_rate,4);
                     $fob_rate1 =  $row->order_rate;
                }
                else
                {
                    $value = $stock * $row->total_cost_value;
                    $fob_rate = number_format($row->total_cost_value,4);
                    $fob_rate1 = $row->total_cost_value;
                }  
               
                             
               // $packing_qty = isset($packingData[0]->size_qty) ? $packingData[0]->size_qty : 0;  
            
                if($row->job_status_id == 1 || $currentDate < $row->order_close_date)
                {   
                    $status = "Moving"; 
                }
                else
                {
                    $status = "Non Moving";
                }
                 
                
                 $html[] =  array(
                    'srno'=>$srno++,
                    'ac_name'=>$row->ac_name,
                    'sales_order_no'=>$row->sales_order_no,
                    'sam'=>$row->sam,
                    'status'=>$status,
                    'order_type'=>$row->order_type,
                    'brand_name'=>$row->brand_name,
                    'mainstyle_name'=>$row->mainstyle_name,
                    'color_name'=>$row->color_name,
                    'size_name'=>$row->size_name,
                    'packing_qty'=>(money_format('%!.0n',round($packing_qty))),
                    'carton_pack_qty'=>(money_format('%!.0n',round($carton_pack_qty))),
                    'transfer_qty'=>(money_format('%!.0n',round($transfer_qty))),
                    'stock'=>(money_format('%!.0n',round($stock))),
                    'fob_rate'=>$fob_rate,
                    'value'=>(money_format('%!.0n',round($value))), 
                );    
                
            $total_packing += $packing_qty;
            $total_carton += $carton_pack_qty;
            $total_transfer += $transfer_qty;
            $total_stock += $stock; 
            $total_value += ($stock*$fob_rate1); 
        }  
         
        $jsonData = json_encode($html);
        
        return response()->json(['html' => $jsonData,'total_packing'=>(money_format('%!.0n',round($total_packing))),
                            'total_carton'=>(money_format('%!.0n',round($total_carton))),'total_transfer'=>(money_format('%!.0n',round($total_transfer))),'total_stock'=>(money_format('%!.0n',round($total_stock))),
                            'total_value'=>(money_format('%!.0n',round($total_value))),'total_stock1'=>round($total_stock/100000,2),'total_value1'=>round($total_value/100000, 2),
                            'Ac_code'=>$Ac_code,'sales_order_no'=>$sales_order_no,'brand_id'=>$brand_id,'mainstyle_id'=>$mainstyle_id,'color_id'=>$color_id,'job_status_id'=>$job_status_id,'orderTypeId'=>$orderTypeId]);
    }
    
    public function DumpFGData()
    {    
        DB::SELECT("DELETE FROM temp_sales_transaction");

        $SaleData = DB::SELECT("SELECT sale_transaction_master.sale_code,sale_transaction_master.sale_date,carton_packing_nos FROM sale_transaction_master");
        foreach($SaleData as $row)
        {
            $cartonData = explode(",",$row->carton_packing_nos);
            foreach($cartonData as $row1)
            {
                 $SaleData1 = DB::SELECT("SELECT order_qty as total_qty,sale_date,sales_order_no FROM sale_transaction_detail WHERE sale_code = '".$row->sale_code."'");
                
                 $total_qty = isset($SaleData1[0]->total_qty) ? $SaleData1[0]->total_qty: 0;
                 $sale_date = isset($SaleData1[0]->sale_date) ? $SaleData1[0]->sale_date: 0;
                 $sales_order_no = isset($SaleData1[0]->sales_order_no) ? $SaleData1[0]->sales_order_no: 0;
                
                 DB::SELECT('INSERT INTO temp_sales_transaction(sale_code,sale_date,order_qty,fgti_code,sales_order_no)
                 select "'.$row->sale_code.'","'.$row->sale_date.'","'.$total_qty.'","'.$row1.'","'.$sales_order_no.'"');   
               
            }
        } 
        
        //DB::select("CALL sp_FGStockDataByTwo()"); 
         
        return 1;
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
    
            $date = date('Y-m', $currentDate);
            $rangArray[] = $date;
        }
    
        return $rangArray;
    }
    
    public function QuantitativeInventoryReport(Request $request)
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
       
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
       // DB::enableQueryLog();

        $Financial_Year=DB::select("SELECT * FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        //dd(DB::getQueryLog());
       // print_r($Financial_Year);exit;
        $fDate = $Financial_Year[0]->fdate;
        $tDate = date('Y-m-d');
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        //print_r($period);exit;
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        return view('QuantitativeInventoryReport',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1'));
    }
   
    public function QuantitativeInventoryReport1(Request $request)
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
       
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
       // DB::enableQueryLog();

        $Financial_Year=DB::select("SELECT * FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        //dd(DB::getQueryLog());
       // print_r($Financial_Year);exit;
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
            $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        //print_r($period);exit;
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        return view('QuantitativeInventoryReport1',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1'));
    } 
    
    public function LoadFabricQuantitiveReport(Request $request)
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        
        $OpeningFabricQtyArr = [];
        $InwardFabricQtyArr = [];
        $OutwardFabricQtyArr = [];
        $ClosingFabricQtyArr = [];
        
        
        $OpeningFabricValueArr = [];
        $InwardFabricValueArr = [];
        $OutwardFabricValueArr = [];
        $ClosingFabricValueArr = [];
        $cntr1 =0;
       
       
        foreach($period as $dates)
        {  
                $firstDate = $dates."-01";
                $lastDate = date("Y-m-t", strtotime( $dates."-01"));
              
                $total_value = 0;
                $total_stock = 0; 
                
                $FabricInwardDetails1 =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date < '".$firstDate."' ) as gq,
                        (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date < '".$firstDate."') as oq 
                        FROM dump_fabric_stock_data WHERE in_date < '".$firstDate."'");
                    
                foreach ($FabricInwardDetails1 as $row1) 
                {
                    $outward_qty1 = $row1->oq ?? 0;
                    $grn_qty1 = $row1->gq ?? 0;
                    $ind_outward2 = explode(",", $row1->ind_outward_qty);
                    $q_qty1 = 0;
                    $rate = $row1->rate;
                    
                    foreach ($ind_outward2 as $indu1) 
                    {
                        $ind_outward_parts = explode("=>", $indu1);
                        if (count($ind_outward_parts) == 2) 
                        {
                            [$ind_outward_key, $ind_outward_value] = $ind_outward_parts;
                            $q_qty1 += ($ind_outward_key < $firstDate) ? $ind_outward_value : 0;
                        }
                    }
                    
                    $stocks = ($row1->qc_qty > 0) ? $row1->qc_qty - $q_qty1 : $grn_qty1 - $q_qty1;
                    
                    $total_stock += $stocks;
                    $total_value += $stocks * $rate;
                }


                $FabricopeningStockQty = $total_stock; 
                
                $FabricopeningStockValue = $total_value; 
                
                $FabInOutStockList1=DB::select("select   
                    (select ifnull(sum(inward_details.meter),sum(fabric_checking_details.meter)) as meter from inward_details
                    left join fabric_checking_details on fabric_checking_details.track_code=inward_details.track_code where in_date BETWEEN '".$firstDate."' AND '".$lastDate."') as InwardQty,
                    
                    (select ifnull(sum(inward_details.meter * inward_details.item_rate),sum(fabric_checking_details.meter * inward_details.item_rate)) as meter from inward_details
                    left join fabric_checking_details on fabric_checking_details.track_code=inward_details.track_code where in_date BETWEEN '".$firstDate."' AND '".$lastDate."') as InwardValue");
                   
                 
                $FabricInwardQty = isset($FabInOutStockList1[0]->InwardQty) ? $FabInOutStockList1[0]->InwardQty : 0; 
                $FabricInwardValue = isset($FabInOutStockList1[0]->InwardValue) ? $FabInOutStockList1[0]->InwardValue : 0; 
                
                $InwardFabricQtyArr[] = $FabricInwardQty;
                $InwardFabricValueArr[] = $FabricInwardValue;
                 
                $FabInOutStockList2=DB::select("select (select ifnull(sum(meter),0) as meter from fabric_outward_details where fout_date BETWEEN '".$firstDate."' AND '".$lastDate."') as OutwardQty,
                    (select ifnull(sum(meter * fabric_outward_details.item_rate),0) as meter from fabric_outward_details where fout_date BETWEEN '".$firstDate."' AND '".$lastDate."') as OutwardValue");
                
                $FabricOutwardQty = isset($FabInOutStockList2[0]->OutwardQty) ? $FabInOutStockList2[0]->OutwardQty : 0; 
                $FabricOutwardValue = isset($FabInOutStockList2[0]->OutwardValue) ? $FabInOutStockList2[0]->OutwardValue : 0;  
                
                $OutwardFabricQtyArr[] = $FabricOutwardQty;
                $OutwardFabricValueArr[] = $FabricOutwardValue;
                
                $OpeningsQty = $OpeningsValue = 0;
                
                if ($firstDate == date('Y-04-01')) {
                    $OpeningsQty = $FabricopeningStockQty;
                    $OpeningsValue = $FabricopeningStockValue;
                } else {
                    $OpeningsQty = $FabricopeningStockQty + $FabricInwardQty - $FabricOutwardQty;
                    $OpeningsValue = $FabricopeningStockValue + $FabricInwardValue - $FabricOutwardValue;
                }
                
                if ($cntr1 == 0) {
                    $openingStockQty = $OpeningsQty;
                    $openingStockValue = $OpeningsValue;
                } else {
                    $openingStockQty = $FabricopeningStockQty;
                    $openingStockValue = $FabricopeningStockValue;
                }
                
                $ClosingFabricQtyArr[] = $openingStockQty + $FabricInwardQty - $FabricOutwardQty;
                $ClosingFabricValueArr[] = $openingStockValue + $FabricInwardValue - $FabricOutwardValue;
                
                if ($cntr1 == 0) {
                    $OpeningFabricQtyArr[] = $openingStockQty;
                    $OpeningFabricValueArr[] = $openingStockValue;
                } else {
                    $OpeningFabricQtyArr[] = $ClosingFabricQtyArr[$cntr1 - 1];
                    $OpeningFabricValueArr[] = $ClosingFabricValueArr[$cntr1 - 1];
                } 
                
            $cntr1++;
        }
        
        
        $html ='<tr>
                    <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                    <td style="background: antiquewhite;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Opening Stock</td>
                    <td style="background: antiquewhite;border-right: 1px solid gray;" nowrap  class="sticky-col third-col">meters</td>'; 
                    for($i = 0; $i< count($period);$i++)
                    {   
                        
                        $opening_qty = round($OpeningFabricQtyArr[$i]/100000,2);
                        $opening_value = round(($OpeningFabricValueArr[$i]/100000),2); 
                        
                        if($InwardFabricQtyArr[$i] == 0 && $OutwardFabricQtyArr[$i] == 0)
                        {
                            $opening_qty = 0; 
                        }
                      
                        
                        if($InwardFabricValueArr[$i] == 0 && $OutwardFabricValueArr[$i] == 0)
                        {
                            $opening_value = 0; 
                        }
        
        
                         $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$opening_qty.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$opening_value.'</td>';
                    }
                 $html .='</tr>
                <tr>
                    <td style="background: antiquewhite;" nowrap class="sticky-col first-col">FABRIC</td>
                    <td style="background: antiquewhite;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Inward</td>
                    <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">meters</td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {  
                   
                     $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.round($InwardFabricQtyArr[$i]/100000,2).'</td> 
                              <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($InwardFabricValueArr[$i]/100000),2).'</td>';
                    
                    }
                    
                $html .='</tr>
                <tr>
                    <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                    <td style="background: antiquewhite;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Outward</td>
                    <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">meters</td>';
                  
                    for($i = 0; $i< count($period);$i++)
                    {  
                  
                     $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.round($OutwardFabricQtyArr[$i]/100000,2).'</td> 
                             <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($OutwardFabricValueArr[$i]/100000),2).'</td>';
                    
                    }
                     
                $html .='</tr>
                <tr>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Closing Stock</td>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky-col third-col">meters</td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {   
                        $closing_qty = round($ClosingFabricQtyArr[$i]/100000,2);
                        $closing_value = round(($ClosingFabricValueArr[$i]/100000),2);
                        
                        if($InwardFabricQtyArr[$i] == 0 && $OutwardFabricQtyArr[$i] == 0)
                        { 
                            $closing_qty = 0;
                        }
                      
                        
                        if($InwardFabricValueArr[$i] == 0 && $OutwardFabricValueArr[$i] == 0)
                        { 
                            $closing_value = 0;
                        }
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;">'.$closing_qty.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;">'.$closing_value.'</td>'; 
                    }
                    
                $html .='</tr>';
                
            return response()->json(['html' => $html]);
    }
    
    public function LoadTrimsQuantitiveReport(Request $request)
    {   
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
            $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
         
        $OpeningTrimsQtyArr = [];
        $InwardTrimsQtyArr = [];
        $OutwardTrimsQtyArr = [];
        $ClosingTrimsQtyArr = [];
        
        
        $OpeningTrimsValueArr = [];
        $InwardTrimsValueArr = [];
        $OutwardTrimsValueArr = [];
        $ClosingTrimsValueArr = [];
        $cntr1 =0;
        
        foreach($period as $dates)
        {  
            $firstDate = $dates."-01";
            $lastDate = date("Y-m-t", strtotime( $dates."-01"));
          
            $TrimOpeningData =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq,sum(outward_qty) as oq FROM dump_trim_stock_data WHERE trimDate < '".$firstDate."' GROUP BY po_no,item_code");     
            
            $total_opening_value = 0;
          
            
            foreach($TrimOpeningData as $row)
            {
                $q_qty = 0;   
                $ind_outward1 = (explode(",",$row->ind_outward_qty));
                
                foreach($ind_outward1 as $indu)
                {
                    
                     $ind_outward2 = (explode("=>",$indu));
                      
                     if($ind_outward2[0] < $firstDate)
                     {
                        $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                        $q_qty = $q_qty + $ind_out;
                       
                     }
                } 
              
                $stocks =  $row->gq - $q_qty; 
                $total_opening_value += ($stocks * $row->rate);
            }


            // $TrimInwardData = DB::select("select sum(trimsInwardDetail.item_qty * trimsInwardDetail.item_rate)  as Inward from trimsInwardDetail INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
            //         where item_master.cat_id !=4 AND trimDate BETWEEN '".$firstDate."' AND '".$lastDate."'");
                    
            // $TrimsOutwardData = DB::SELECT("SELECT trimsOutwardDetail.item_qty,trimsOutwardDetail.item_code,trimsOutwardDetail.po_code FROM trimsOutwardDetail 
            //         INNER JOIN item_master ON item_master.item_code = trimsOutwardDetail.item_code 
            //         WHERE item_master.cat_id != 4 AND trimsOutwardDetail.tout_date BETWEEN '".$firstDate."' AND '".$lastDate."'");
            
                    
            // $outward_qty = 0;
            // foreach($TrimsOutwardData as $row)
            // {
            //     $TrimsInwardData = DB::SELECT("SELECT trimsInwardDetail.item_rate FROM trimsInwardDetail  
            //         INNER JOIN item_master ON item_master.item_code = trimsInwardDetail.item_code 
            //         WHERE item_master.cat_id != 4 AND trimsInwardDetail.item_code = '".$row->item_code."' AND po_code='".$row->po_code."'");
                
            //     $item_rate = isset($TrimsInwardData[0]->item_rate) ? $TrimsInwardData[0]->item_rate: 0;  
                
            // }
              
            
            // Bind parameters
         //DB::enableQueryLog();
            $TrimInwardData = DB::select("
                SELECT 
                    SUM(Inward) AS Inward,
                    SUM(Outward) AS Outward
                FROM (
                    SELECT 
                        COALESCE(SUM(ti.item_qty * ti.item_rate), 0) AS Inward,
                        0 AS Outward
                    FROM 
                        trimsInwardDetail ti
                        INNER JOIN item_master im ON ti.item_code = im.item_code
                    WHERE 
                        im.cat_id != 4
                        AND ti.trimDate BETWEEN '".$firstDate."' AND '".$lastDate."'
                    UNION ALL
                    SELECT 
                        0 AS Inward,
                        COALESCE(SUM(tout.item_qty * tout.item_rate), 0) AS Outward
                    FROM 
                        trimsOutwardDetail tout
                        INNER JOIN item_master im ON tout.item_code = im.item_code
                    WHERE 
                        im.cat_id != 4
                        AND tout.tout_date BETWEEN '".$firstDate."' AND '".$lastDate."'
                ) AS combined_data");


                //dd(DB::getQueryLog());

            $outward_qty = isset($TrimInwardData[0]->Outward) ? $TrimInwardData[0]->Outward: 0;  
            $inwardQty = isset($TrimInwardData[0]->Inward) ? $TrimInwardData[0]->Inward: 0;  
            $openingStock = $total_opening_value;  
            $outwardQty = $outward_qty;      
     
            
            $ClosingTrimsValueArr[] = $openingStock + $inwardQty - $outwardQty;
            if($cntr1 == 0)
            { 
                $OpeningTrimsValueArr[] = $openingStock; 
            }
            else
            {  
                $OpeningTrimsValueArr[] = $ClosingTrimsValueArr[$cntr1-1];
                 
            }
            
            
            $InwardTrimsValueArr[] = $inwardQty;
            $OutwardTrimsValueArr[] = $outwardQty;
            $cntr1++; 
        }           
        
     
       $html ='<tr>
                    <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                    <td style="background: antiquewhite;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Opening Stock</td>
                    <td style="background: antiquewhite;border-right: 1px solid gray;" nowrap  class="sticky-col third-col">-</td>'; 
                    for($i = 0; $i< count($period);$i++)
                    {      
                        
                        $opening_value = round(($OpeningTrimsValueArr[$i]/100000),2); 
                        if($InwardTrimsValueArr[$i] == 0 && $OutwardTrimsValueArr[$i] == 0)
                        {
                            $opening_value = 0; 
                        }      
                         $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">-</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$opening_value.'</td>';
                    }
                 $html .='</tr>
                <tr>
                    <td style="background: antiquewhite;" nowrap class="sticky-col first-col">TRIMS</td>
                    <td style="background: antiquewhite;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Inward</td>
                    <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">-</td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {  
                   
                     $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">-</td> 
                              <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($InwardTrimsValueArr[$i]/100000),2).'</td>';
                    
                    }
                    
                $html .='</tr>
                <tr>
                    <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                    <td style="background: antiquewhite;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Outward</td>
                    <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">-</td>';
                  
                    for($i = 0; $i< count($period);$i++)
                    {  
                  
                     $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">-</td> 
                             <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($OutwardTrimsValueArr[$i]/100000),2).'</td>';
                    
                    }
                     
                $html .='</tr>
                <tr>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Closing Stock</td>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky-col third-col">-</td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {  
                        $closing_value = round(($ClosingTrimsValueArr[$i]/100000),2);
                        if($InwardTrimsValueArr[$i] == 0 && $OutwardTrimsValueArr[$i] == 0)
                        { 
                            $closing_value = 0;
                        }
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;">-</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;">'.$closing_value.'</td>'; 
                    }
                    
                $html .='</tr>';                          
                
        return response()->json(['html' => $html]);
    } 
    
    public function LoadWIPQuantitiveReport(Request $request)
    { 
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
            $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
        }
        $tDate1  = date("y-m-d");
        $period1 = $this->getBetweenDates($fDate, $tDate);
        $period2 = $this->getBetweenDates($fDate, $tDate1);
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        $pd1 = array();
       
        foreach ($period2 as $date1) 
        {
            $pd1[] = $date1; 
        }
        $period = array_unique($pd);
        $period1 = array_unique($pd1);
        $OpeningWIPQtyArr = [];
        $InwardWIPQtyArr = [];
        $OutwardWIPQtyArr = [];
        $ClosingWIPQtyArr = [];
        
        
        $OpeningWIPValueArr = [];
        $InwardWIPValueArr = [];
        $OutwardWIPValueArr = [];
        $ClosingWIPValueArr = [];
        
        $CuttingWIPPCSArr = [];
        $CuttingWIPMinArr = [];
        $SewingWIPPCSArr = [];
        $SewingWIPMinArr = [];
        $PackingWIPPCSArr = [];
        $PackingWIPMinArr = [];
        $WIPPCSArr = [];
        $WIPMinArr = [];
        
        $CuttingWIPValueArr = [];
        $SewingWIPValueArr = [];
        $PackingWIPValueArr = [];
        $WIPValueArr = [];
        
        foreach($period as $dates)
        {  
            $firstDate = $dates."-01";
            $lastDate = date("Y-m-t", strtotime( $dates."-01"));
          
            $total_value = 0;
            $total_stock = 0; 
             

            $Buyer_Purchase_Order_List = DB::select("SELECT * FROM buyer_purchse_order_master 
                    WHERE 
                        (
                            order_received_date <= '".$lastDate."' 
                            AND buyer_purchse_order_master.job_status_id = 1 
                            AND og_id != 4
                            AND order_type != 2
                        ) 
                        OR 
                            (
                                order_close_date = '".$lastDate."'
                                AND og_id != 4 
                                AND buyer_purchse_order_master.delflag = 0  
                            )  
                         AND order_type != 2
                    ORDER BY buyer_purchse_order_master.tr_code");
            $totalCuttingWIPPCS = 0;
            $totalCuttingWIPMin = 0;
            $totalSewingWIPPCS = 0;
            $totalSewingWIPMin = 0;
            $totalPackingWIPPCS = 0;
            $totalPackingWIPMin = 0;
            $totalWIPPCS = 0;
            $totalWIPMin = 0;
            $totalCuttingWIPValue = 0;
            $totalSewingWIPValue = 0;
            $totalPackingWIPValue = 0;
            $totalWIPValue = 0;    
            foreach($Buyer_Purchase_Order_List as $row)  
            {
             
                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where  sales_order_no='".$row->tr_code."' AND vw_date <= '".$lastDate."'");
                
                
                $CutPanelData = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <= '".$lastDate."'");
                
                if(count($CutPanelData) > 0)
                {
                        $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                }
                else
                {
                        $cutPanelIssueQty = 0;
                } 
                
                $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$lastDate."'");
                
                if(count($StichingData) > 0)
                {
                        $stichingQty = $StichingData[0]->stiching_qty;
                }
                else
                {
                        $stichingQty = 0;
                }
                
                
              $PackingData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$lastDate."' AND packing_type_id=4");
    
              if(count($PackingData) > 0)
              {
                     $pack_order_qty = $PackingData[0]->total_qty;
              }
              else
              {
                     $pack_order_qty = 0;
              }
             
              
        
              $sewing = $cutPanelIssueQty - $stichingQty;
              
               
              $WIPAdjustQtyData=DB::select("SELECT ifnull(sum(size_qty_total),0) as WIP_adjust_qty from WIP_Adjustable_Qty_detail where sales_order_no='".$row->tr_code."'");
              
              $rejectData = DB::select("SELECT ifnull(sum(size_qty_total),0) as total_qty  from qcstitching_inhouse_reject_detail  
                            WHERE qcstitching_inhouse_reject_detail.sales_order_no = '".$row->tr_code."' AND qcsti_date <='".$lastDate."'");
                            
              $total_adjustable_qty = isset($WIPAdjustQtyData[0]->WIP_adjust_qty) ? $WIPAdjustQtyData[0]->WIP_adjust_qty : 0; 
              $reject_qty = isset($rejectData[0]->total_qty) ? $rejectData[0]->total_qty : 0;
              
              $totalWIPPCS += (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty-$total_adjustable_qty) - $reject_qty);
              $totalWIPMin += ((($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty-$total_adjustable_qty) - $reject_qty) * $row->sam);
              
              $SalesCostingData = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
      
              $fabric_value = isset($SalesCostingData[0]->fabric_value) ? $SalesCostingData[0]->fabric_value : 0;  
              $sewing_trims_value = isset($SalesCostingData[0]->sewing_trims_value) ? $SalesCostingData[0]->sewing_trims_value : 0;
              $packing_trims_value = isset($SalesCostingData[0]->packing_trims_value) ? $SalesCostingData[0]->packing_trims_value : 0;        
      
              $totalCuttingWIPPCS += ($VendorData[0]->work_order_qty - $cutPanelIssueQty);
              $totalCuttingWIPMin += ($VendorData[0]->work_order_qty - $cutPanelIssueQty) * $row->sam;
              $totalCuttingWIPValue += ($fabric_value +  $sewing_trims_value + $packing_trims_value) * ($VendorData[0]->work_order_qty - $cutPanelIssueQty);
              
              $totalSewingWIPPCS += $sewing;
              $totalSewingWIPMin += $sewing * $row->sam;
              $totalSewingWIPValue +=  ($fabric_value +  $sewing_trims_value + $packing_trims_value) * $sewing;
              
              $totalPackingWIPPCS += $stichingQty - $pack_order_qty;
              $totalPackingWIPMin += ($stichingQty - $pack_order_qty)  * $row->sam;
              $totalPackingWIPValue +=  ($fabric_value +  $sewing_trims_value + $packing_trims_value) * ($stichingQty - $pack_order_qty);
              
              $totalWIPValue += (($fabric_value +  $sewing_trims_value + $packing_trims_value) * (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty-$total_adjustable_qty) - $reject_qty));
             
         }           
      

            $CuttingWIPPCSArr[] =  $totalCuttingWIPPCS;
            $CuttingWIPMinArr[] =  $totalCuttingWIPMin;
            $CuttingWIPValueArr[] =  $totalCuttingWIPValue;
                                            
            $SewingWIPPCSArr[] =  $totalSewingWIPPCS;
            $SewingWIPMinArr[] =  $totalSewingWIPMin;
            $SewingWIPValueArr[] =  $totalSewingWIPValue;
            
            $PackingWIPPCSArr[] =  $totalPackingWIPPCS;
            $PackingWIPMinArr[] =  $totalPackingWIPMin;
            $PackingWIPValueArr[] =  $totalPackingWIPValue;
            
            $WIPPCSArr[] =  $totalWIPPCS;
            $WIPMinArr[] =  $totalWIPMin;
            $WIPValueArr[] =  $totalWIPValue;
         
         
            $totalCuttingWIPPCS = 0;
            $totalCuttingWIPMin = 0;
            $totalCuttingWIPValue = 0;
            $totalSewingWIPPCS = 0;
            $totalSewingWIPMin = 0;
            $totalSewingWIPValue = 0;
            $totalPackingWIPPCS = 0;
            $totalPackingWIPMin = 0;
            $totalPackingWIPValue = 0;
            $totalWIPPCS = 0;
            $totalWIPMin = 0;
            $totalWIPValue = 0;

        }
        
        $html = '<tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Cutting</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">piece</td>';
                   
                    for($i = 0; $i< count($period);$i++)
                    { 
                        
                        if($i >= count($period1))
                        {
                            $CuttingWIPPCS = 0;
                            $CuttingWIPValue = 0;
                           
                        }
                        else
                        {
                            $CuttingWIPPCS = round($CuttingWIPPCSArr[$i]/100000,2); 
                            $CuttingWIPValue = round($CuttingWIPValueArr[$i]/100000,2); 
                        }
                        

                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$CuttingWIPPCS.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$CuttingWIPValue.'</td>';
                        
                       
                    }
                $html .='</tr> 
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Cutting</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">minutes</td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    { 
                        
                        if($i >= count($period1))
                        {
                            $CuttingWIPMin = 0; 
                           
                        }
                        else
                        {
                            $CuttingWIPMin = round($CuttingWIPMinArr[$i]/100000,2); 
                        }
                        
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$CuttingWIPMin.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">-</td>'; 
                    }
                    
                $html .='</tr>
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Sewing</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">piece</td>';
                   
                    for($i = 0; $i< count($period);$i++)
                    { 
                        if($i >= count($period1))
                        {
                            $SewingWIPPCS = 0;
                            $SewingWIPValue = 0;
                           
                        }
                        else
                        {
                            $SewingWIPPCS = round($SewingWIPPCSArr[$i]/100000,2); 
                            $SewingWIPValue = round($SewingWIPValueArr[$i]/100000,2); 
                        }
                        
                       
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$SewingWIPPCS.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$SewingWIPValue.'</td>';
                    
                    }
                  
                $html .='</tr>
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;">WIP</td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Sewing</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">minutes</td>';
                     
                    for($i = 0; $i< count($period);$i++)
                    { 
                    
                        if($i >= count($period1))
                        {
                            $SewingWIPMin = 0;  
                        }
                        else
                        {
                            $SewingWIPMin = round($SewingWIPMinArr[$i]/100000,2);  
                        }
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$SewingWIPMin.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">-</td>';
                         
                    }
                    
                $html .='</tr> 
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Packing</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">piece</td>';
                   
                    for($i = 0; $i< count($period);$i++)
                    { 
                        if($i >= count($period1))
                        {
                            $PackingWIPPCS = 0;
                            $PackingWIPValue = 0;
                           
                        }
                        else
                        {
                            $PackingWIPPCS = round($PackingWIPPCSArr[$i]/100000,2); 
                            $PackingWIPValue = round($PackingWIPValueArr[$i]/100000,2); 
                        }
                        
                     
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$PackingWIPPCS.'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$PackingWIPValue.'</td>';
                    
                    }
                 $html .='</tr> 
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Packing</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">minutes</td>';
                     
                    for($i = 0; $i< count($period);$i++)
                    {  
                    
                        if($i >= count($period1))
                        {
                            $PackingWIPMin = 0;  
                        }
                        else
                        {
                            $PackingWIPMin = round($PackingWIPMinArr[$i]/100000,2);  
                        }
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$PackingWIPMin.'</td> 
                                <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">-</td>';
                             
                    }
                    
                 $html .='</tr> 
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Total</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;;border-right: 1px solid gray;">piece</td>';
                   
                    for($i = 0; $i< count($period);$i++)
                    { 
                  
                        if($i >= count($period1))
                        {
                            $WIPPCS = 0;
                            $WIPValue = 0;
                           
                        }
                        else
                        {
                            $WIPPCS = round($WIPPCSArr[$i]/100000,2); 
                            $WIPValue = round($WIPValueArr[$i]/100000,2); 
                        }
                    
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$WIPPCS.'</td> 
                                  <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$WIPValue.'</td>';
                     
                    }
                     
                 $html .='</tr>
                <tr>
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;border-bottom: 3px solid black;">WIP</td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid #8080803d;">Total</td>
                    <td class="sticky-col third-col" style="background: antiquewhite;border-bottom: 3px solid black;">minutes</td>';
                   
                    for($i = 0; $i< count($period);$i++)
                    { 
                  
                        if($i >= count($period1))
                        {
                            $WIPMin = 0;  
                        }
                        else
                        { 
                            $WIPMin = round($WIPMinArr[$i]/100000,2); 
                        }
                    
                     
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;">'.$WIPMin.'</td> 
                            <td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;">-</td>';
                             
                    }
                    
                 $html .='</tr>';
                             
        return response()->json(['html' => $html]);
    }  
    
    public function LoadFGQuantitiveReport(Request $request)
    {   
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);  
        
        $OpeningFGQtyArr = [];
        $InwardFGQtyArr = [];
        $OutwardFGQtyArr = [];
        $TransferFGQtyArr = [];
        $ClosingFGQtyArr = [];
        
        
        $OpeningFGValueArr = [];
        $InwardFGValueArr = [];
        $OutwardFGValueArr = [];
        $TransferFGValueArr = [];
        $ClosingFGValueArr = [];
        
        $OpeningFGQtyArr1 = [];
        $OpeningFGValueArr1 = [];
        
        $cntr2 = 0;
        
        
        foreach($period as $dates)
        {     
        
            $openingQtyFG = 0;
            $openingValueFG = 0;
            $packingQtyFG = 0;
            $packingValueFG = 0;
            $cartonQtyFG = 0;
            $cartonValueFG = 0;
            $transferQtyFG = 0;
            $transferValueFG = 0;
        
            $firstDate = date($dates."-01");
            $lastDate = date("Y-m-t", strtotime( $dates."-01"));
             
    
            $FinishedGoodsStock = DB::select("SELECT FG.entry_date,
                SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (1) THEN FG.size_qty ELSE 0 END) AS total_packing_qty,
                SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (1) THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) AS total_packing_value,
                SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (2) THEN FG.size_qty ELSE 0 END) AS total_carton_qty,
                SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (2) THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) AS total_carton_value,
                SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (3) THEN FG.size_qty ELSE 0 END) AS total_transfer_qty,
                SUM(CASE WHEN FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' AND FG.data_type_id IN (3) THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) AS total_transfer_value,
                SUM(FG.size_qty) AS total_stock,
                SUM(FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) AS total_stock_value,
                (SUM(CASE WHEN FG.data_type_id IN (1) AND FG.entry_date < '".$firstDate."' THEN FG.size_qty ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (2) AND FG.entry_date < '".$firstDate."' THEN FG.size_qty ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (3) AND FG.entry_date < '".$firstDate."' THEN FG.size_qty ELSE 0 END)) AS opening_stock,
                (SUM(CASE WHEN FG.data_type_id IN (1) AND FG.entry_date < '".$firstDate."' THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (2) AND FG.entry_date < '".$firstDate."' THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END) - SUM(CASE WHEN FG.data_type_id IN (3) AND FG.entry_date < '".$firstDate."' THEN (FG.size_qty * IFNULL(sales_order_costing_master.total_cost_value, buyer_purchse_order_master.order_rate)) ELSE 0 END)) AS opening_value
            FROM 
                FGStockDataByTwo AS FG
            INNER JOIN 
                buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no 
            INNER JOIN 
                job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
            INNER JOIN 
                brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
            LEFT JOIN 
                order_type_master ON order_type_master.orderTypeId = buyer_purchse_order_master.order_type
            LEFT JOIN 
                sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
            WHERE 
                FG.data_type_id IN (1, 2, 3)
                AND (FG.entry_date BETWEEN '".$firstDate."' AND '".$lastDate."' OR FG.entry_date < '".$firstDate."')");

               
            foreach($FinishedGoodsStock as $row)
            {
                $openingQtyFG += $row->opening_stock;
                $openingValueFG += $row->opening_value;
                
                $packingQtyFG += $row->total_packing_qty;
                $packingValueFG += $row->total_packing_value;
                
                $cartonQtyFG += $row->total_carton_qty;
                $cartonValueFG += $row->total_carton_value;
                
                $transferQtyFG += $row->total_transfer_qty;
                $transferValueFG += $row->total_transfer_value;
            }
           
            
            $InwardFGQtyArr[] = $packingQtyFG;
            $InwardFGValueArr[] = $packingValueFG;
            $TransferFGQtyArr[] = $transferQtyFG;  
            $TransferFGValueArr[] = $transferValueFG;  
            $OutwardFGQtyArr[] = $cartonQtyFG;   
            $OutwardFGValueArr[] = $cartonValueFG;  
             
        
            
            $ClosingFGQtyArr[] = $openingQtyFG + $packingQtyFG - $cartonQtyFG - $transferQtyFG;
            $ClosingFGValueArr[] = $openingValueFG + $packingValueFG - $cartonValueFG - $transferValueFG;
         
       
            $OpeningFGQtyArr[] = $openingQtyFG;
            $OpeningFGValueArr[] = $openingValueFG;
             
                
            $cntr2++;
        } 
        $html = '<tr>
                        <td class="sticky-col first-col" style="background: antiquewhite;"></td>
                        <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;">Opening Stock</td>
                        <td class="sticky-col third-col" style="background: antiquewhite;border-right: 1px solid gray;">piece</td>'; 
                        for($i = 0; $i< count($period);$i++)  
                        {  
                            $opening_qty = round($OpeningFGQtyArr[$i]/100000,2);
                            $opening_value = round($OpeningFGValueArr[$i]/100000,2);
                            
                            if($InwardFGQtyArr[$i] == 0 && $TransferFGQtyArr[$i] == 0 && $OutwardFGQtyArr[$i] == 0)
                            {
                                $opening_qty = 0;
                                $opening_value = 0;
                            }
                            $html .= '<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.$opening_qty.'</td> 
                            <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.$opening_value.'</td>';
                        } 
                    $html .= '</tr>
                    <tr>
                        <td style="background: antiquewhite;" class="sticky-col first-col">FG</td>
                        <td style="background: antiquewhite;border-right: 1px solid #8080803d;" class="sticky-col second-col">Production</td>
                        <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">piece</td>';
                        
                        for($i = 0; $i< count($period);$i++)
                        {     
                        
                        $html .= '<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.round($InwardFGQtyArr[$i]/100000,2).'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($InwardFGValueArr[$i])/100000,2).'</td>';
                        
                        }
                         
                    $html .= '</tr>
                    <tr>
                        <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                        <td style="background: antiquewhite;border-right: 1px solid #8080803d;" class="sticky-col second-col">Transfer</td>
                        <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">piece</td>';
                         
                        for($i = 0; $i< count($period);$i++)
                        {   
                       
                        $html .= '<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.round($TransferFGQtyArr[$i]/100000,2).'</td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($TransferFGValueArr[$i])/100000,2).'</td>';
                       
                        }
                        
                    $html .= '</tr>
                    <tr>
                        <td style="background: antiquewhite;" class="sticky-col first-col"></td>
                        <td style="background: antiquewhite;border-right: 1px solid #8080803d;" class="sticky-col second-col">Outward</td>
                        <td style="background: antiquewhite;border-right: 1px solid gray;" class="sticky-col third-col">piece</td>';
                        
                        for($i = 0; $i< count($period);$i++)
                        {   
                            $html .= '<td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;">'.round($OutwardFGQtyArr[$i]/100000,2).'</td> 
                            <td class="text-right" style="background:'.$colorArr[0].';border-right: 1px solid gray;">'.round(($OutwardFGValueArr[$i])/100000,2).'</td>';
                        }
                       
                    $html .= '</tr> 
                    <tr>
                        <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col"></td>
                        <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col">Closing Stock</td>
                        <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky-col third-col">piece</td>';
                        
                        for($i=0;$i< count($period); $i++)
                        {     
                            $closing_qty = round($ClosingFGQtyArr[$i]/100000,2);
                            $closing_value = round($ClosingFGValueArr[$i]/100000,2);
                            
                            if($InwardFGValueArr[$i] == 0 && $TransferFGValueArr[$i] == 0 && $OutwardFGValueArr[$i] == 0)
                            {
                                $closing_qty = 0;
                                $closing_value = 0;
                            }
                        
                            $html .= '<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;">'.$closing_qty.'</td> 
                            <td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;">'.$closing_value.'</td>';
                        }
                    $html .= '</tr>';
        
        return response()->json(['html' => $html]);
    }  
    
    public function InventoryReportMovingNonMoving(Request $request)
    { 
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        return view('InventoryReportMovingNonMoving',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1'));
    }
    
     public function InventoryReportMovingNonMovingIframe(Request $request)
    { 
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : "";
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        return view('InventoryReportMovingNonMovingIframe',compact('period','colorArr','Financial_Year','fin_year_id','Financial_Year1'));
    }
    
    public function LoadTrimsInventoryMovingNonMovingReport(Request $request)
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : 4;
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $period2 = $this->getBetweenDates($fDate, $tDate);
         
         
        $pd = array();
        $pd1 = array();
       
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        
        foreach ($period2 as $date1) 
        {
            $pd1[] = $date1; 
        }
        
        $period3 = array_unique($pd1);
          
        $ClosingTrimsValueArr = [];  
        $ClosingTrimsValueArrNon = [];
        $monthArr = [];
        $cntr1 =0;
        
        foreach($period as $dates)
        {  
            $firstDate = $dates."-01";
            $lastDate = date("Y-m-t", strtotime( $dates."-01"));
          
             
          $TrimsInwardDetails = DB::select("
                    SELECT 
                        dump_trim_stock_data.*,  
                        SUM(grn_qty) AS gq 
                    FROM 
                        dump_trim_stock_data 
                    INNER JOIN 
                        item_master ON item_master.item_code = dump_trim_stock_data.item_code 
                        WHERE item_master.class_id != 94 AND trimDate <= '$lastDate' AND job_status_id IN(1)
                    GROUP BY 
                        dump_trim_stock_data.po_no, 
                        dump_trim_stock_data.item_code
                ");

            //dd(DB::getQueryLog());
        
            $total_value = 0;
            $total_stock = 0;
            $total_amount = 0;
            
            foreach($TrimsInwardDetails as $row)
            {
                
                if($row->closeDate <= $lastDate && $row->job_status_id == 1)
                {
                    $q_qty = 0;  
                    $grn_qty = isset($row->gq) ? $row->gq : 0; 
                    $ind_outward1 = (explode(",",$row->ind_outward_qty));
                    
                 
                    foreach($ind_outward1 as $indu)
                    {
                        
                         $ind_outward2 = (explode("=>",$indu));
                          
                         if($ind_outward2[0] <= $lastDate)
                         {
                            $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                            $q_qty = $q_qty + $ind_out;
                           
                         }
                    } 
                  
                    $stocks =  $row->gq - $q_qty;
                      
                            
                    $total_value += ($stocks * $row->rate);  
                }
                else if($row->closeDate >= $lastDate && $row->job_status_id == 2)
                {
                    $q_qty = 0;  
                    $grn_qty = isset($row->gq) ? $row->gq : 0; 
                    $ind_outward1 = (explode(",",$row->ind_outward_qty));
                    
                 
                    foreach($ind_outward1 as $indu)
                    {
                        
                         $ind_outward2 = (explode("=>",$indu));
                          
                         if($ind_outward2[0] <= $lastDate)
                         {
                            $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                            $q_qty = $q_qty + $ind_out;
                           
                         }
                    } 
                  
                    $stocks =  $grn_qty - $q_qty;
                      
                            
                    $total_value += ($stocks * $row->rate);  
                }
             
            }
            
            if(date("Y-m", strtotime(date("Y-m-d"))) >= date("Y-m", strtotime($lastDate)))
            { 
                $ClosingTrimsValueArr[] = $total_value;
            }
            else
            { 
                $ClosingTrimsValueArr[] = 0;
            }
          
        }    

        foreach($period as $dates)
        {  
            $firstDate = $dates."-01";
            $lastDate = date("Y-m-t", strtotime( $dates."-01"));
             
            $TrimsInwardDetails =DB::select("SELECT dump_trim_stock_data.*, sum(grn_qty) as gq FROM dump_trim_stock_data  
                                INNER JOIN item_master ON item_master.item_code = dump_trim_stock_data.item_code WHERE  
                                item_master.class_id != 94 AND trimDate <='".$lastDate."'  AND job_status_id IN (0,2)
                                GROUP BY dump_trim_stock_data.po_no,dump_trim_stock_data.item_code");
            
            $total_value = 0;
            $total_stock = 0;
            $total_amount = 0;
            
            foreach($TrimsInwardDetails as $row)
            {
                $q_qty = 0;  
                $grn_qty = isset($row->gq) ? $row->gq : 0; 
                $ind_outward1 = (explode(",",$row->ind_outward_qty));
             
                foreach($ind_outward1 as $indu)
                {
                    
                     $ind_outward2 = (explode("=>",$indu));
                      
                     if($ind_outward2[0] <= $lastDate)
                     {
                        $ind_out = isset($ind_outward2[1]) ? $ind_outward2[1] : 0; 
                        $q_qty = $q_qty + $ind_out;
                       
                     }
                } 
              
                $stocks =  $grn_qty - $q_qty;
                        
                $total_value += ($stocks * $row->rate);  
            }
            
            if(date("Y-m", strtotime(date("Y-m-d"))) >= date("Y-m", strtotime($lastDate)))
            { 
                $ClosingTrimsValueArrNon[] = $total_value;
            }
            else
            { 
                $ClosingTrimsValueArrNon[] = 0;
            }
           $monthArr[] = $lastDate;
          
        }    
        
            $ClosingTrimsValueArr = array_values($ClosingTrimsValueArr);
            $ClosingTrimsValueArrNon = array_values($ClosingTrimsValueArrNon);
            
            $html ='<tr>
                    <td style="background: antiquewhite;color:black;border-bottom: 3px solid black;" class="sticky-col first-col" rowspan=3><b>A</b></td>
                    <td style="background: antiquewhite;color:black;border-bottom: 3px solid black;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col" rowspan=3><b>Trims</b></td>
                    <td style="background: antiquewhite;color:black;border-bottom: 0.5px solid black;border-right: 1px solid gray;" class="sticky-col third-col"><b>Moving</b></td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {    
                        $closing_value = money_format('%!.0n',round($ClosingTrimsValueArr[$i]));
                        $ls_date = $monthArr[$i];
                        
                        $html .='<td class="text-right" style="background:#ff000017!important;color:black;border-bottom: 0.5px solid black;border-right: 1px solid #8080803d;">-</td> 
                        <td class="text-right" style="background:#ff000017!important;color:black;border-bottom: 0.5px solid black;border-right: 1px solid gray;"><b><a href="/TrimsStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=1" target="_blank">'.$closing_value.'</a></b></td>';
                         
                        $closing_value= 0;
                    }
                    
                $html .='</tr> 
                    <tr> 
                    <td style="background: antiquewhite;color:black;border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky-col third-col"><b>Non Moving</b></td>';
                    
                    for($j = 0; $j< count($period3);$j++)
                    {    
                        if (!empty($ClosingTrimsValueArrNon)) 
                        {
                             $closing_valueNon = money_format('%!.0n',round($ClosingTrimsValueArrNon[$j]));  
                        }
                        else
                        {
                            $closing_valueNon = 0;
                        }
                       
                        $ls_date = $monthArr[$j];
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;">-</td> 
                        <td class="text-right Trims_non_moving_value" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;"><b><a href="/TrimsStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=2" target="_blank">'.$closing_valueNon.'</a></b></td>';
                         
                        $closing_valueNon= 0;
                    }
                    
                $html .='</tr> 
                        <tr>
                            <td style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;" class="sticky-col third-col">Total</td>';
                            
                            for($j = 0; $j< count($period3);$j++)
                            {    
                                $closedTrims = isset($ClosingTrimsValueArrNon[$j]) ? $ClosingTrimsValueArrNon[$j] : 0;
                                
                                $total_closing_valueNon = money_format('%!.0n',round($closedTrims + $ClosingTrimsValueArr[$j])); 
                                $ls_date = $monthArr[$j];
                                 
                                $html .='<td class="text-right" style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;">-</td> 
                                <td class="text-right trims_total_value" style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;"><b><a href="/TrimsStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=0" target="_blank">'.$total_closing_valueNon.'</a></b></td>';
                                 
                               $total_closing_valueNon= 0;
                            }
                            
                    $html .='</tr>';
                return response()->json(['html' => $html]);
    }
    
    
    public function LoadFabricInventoryMovingNonMovingReport(Request $request)
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : 4;
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $period2 = $this->getBetweenDates($fDate, $tDate);
         
         
        $pd = array();
        $pd1 = array();
       
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        
        foreach ($period2 as $date1) 
        {
            $pd1[] = $date1; 
        }
        
        $period3 = array_unique($pd1);
         
        $ClosingFabricQtyArr = []; 
        $ClosingFabricValueArr = []; 
        $ClosingFabricQtyArrNon = []; 
        $ClosingFabricValueArrNon = [];
        $monthArr = [];
        
        foreach ($period as $dates) 
        {  
            $firstDate = $dates . "-01";
            $lastDate = date("Y-m-t", strtotime($dates . "-01"));
        
            $total_value = 0;
            $total_stock = 0; 
        
            $FabricInwardDetails1 = DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$lastDate."') as gq
                    FROM dump_fabric_stock_data  INNER JOIN item_master ON item_master.item_code = dump_fabric_stock_data.item_code WHERE in_date <='".$lastDate."' AND  job_status_id = 1 AND item_master.class_id != 94");
        
            foreach ($FabricInwardDetails1 as $row) 
            {
                $grn_qty = isset($row->gq) ? $row->gq : 0; 
                $ind_outward1 = (explode(",", $row->ind_outward_qty));
                $q_qty = 0; 
        
                foreach ($ind_outward1 as $indu) {
                    $ind_outward2 = (explode("=>", $indu));
                    $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                    if ($ind_outward2[0] <= $lastDate) {
                        $q_qty = $q_qty + $q_qty1;
                    } 
                } 
                if ($row->qc_qty != '' ) {
                    $stocks = $row->qc_qty - $q_qty;
                } else {
                    $stocks = $row->gq - $q_qty;
                }
                $total_value += ($stocks) * $row->rate;  
                $total_stock += $stocks; 
            }
            if(date("Y-m", strtotime(date("Y-m-d"))) >= date("Y-m", strtotime($lastDate)))
            {
                $ClosingFabricQtyArr[] = $total_stock;
                $ClosingFabricValueArr[] = $total_value;
            }
            else
            {
                $ClosingFabricQtyArr[] = 0;
                $ClosingFabricValueArr[] = 0;
            }
           
           $monthArr[] = $lastDate;
        }
        
        
        $ClosingFabricQtyArr = array_values($ClosingFabricQtyArr);
        $ClosingFabricValueArr = array_values($ClosingFabricValueArr);

        foreach($period3 as $dates1)
        {  
            $firstDate1 = $dates1."-01";
            $lastDate1 = date("Y-m-t", strtotime( $dates1."-01"));
          
            $total_value1 = 0;
            $total_stock1 = 0; 
            
            $FabricInwardDetails1 =DB::select("SELECT dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$lastDate1."') as gq
                    FROM dump_fabric_stock_data INNER JOIN item_master ON item_master.item_code = dump_fabric_stock_data.item_code WHERE item_master.class_id != 94 AND dump_fabric_stock_data.in_date <= '".$lastDate1."'  AND job_status_id IN(0,2)");
            
            
            foreach($FabricInwardDetails1 as $row)
            {
                $grn_qty = isset($row->gq) ? $row->gq : 0; 
                $ind_outward1 = (explode(",",$row->ind_outward_qty));
                $q_qty = 0; 
               
                foreach($ind_outward1 as $indu)
                {
                    
                     $ind_outward2 = (explode("=>",$indu));
                     $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                     if($ind_outward2[0] <= $lastDate1)
                     {
                         $q_qty = $q_qty + $q_qty1;
                     }
                } 
                if($row->qc_qty != '' )
                {
                    $stocks1 =  $row->qc_qty- $q_qty;
                } 
                else
                {
                     $stocks1 =  $row->gq - $q_qty;
                }
                   
                    $total_value1 += ($stocks1) * $row->rate;  
                    $total_stock1 +=  $stocks1; 
            } 
            
            if(date("Y-m", strtotime(date("Y-m-d"))) >= date("Y-m", strtotime($lastDate1)))
            {
                $ClosingFabricQtyArrNon[] = $total_stock1;
                $ClosingFabricValueArrNon[] = $total_value1;
            }
            else
            {
                $ClosingFabricQtyArrNon[] = 0;
                $ClosingFabricValueArrNon[] = 0;
            }
        }
        
        $ClosingFabricQtyArrNon = array_values($ClosingFabricQtyArrNon);
        $ClosingFabricValueArrNon = array_values($ClosingFabricValueArrNon);
        
        $html ='<tr>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col" rowspan=3><b>B</b></td>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col" rowspan=3><b>Fabric</b></td>
                    <td style="background: antiquewhite;border-bottom: 0.5px solid black;border-right: 1px solid gray;" class="sticky-col third-col"><b>Moving</b></td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {   
                        $closing_qty = money_format('%!.0n',round($ClosingFabricQtyArr[$i]));   
                        $closing_value = money_format('%!.0n',round($ClosingFabricValueArr[$i]));
                        $ls_date = $monthArr[$i];
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 0.5px solid black;border-right: 1px solid #8080803d;"><a href="/FabricStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=1" target="_blank"><b>'.$closing_qty.'</b></a></td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-bottom: 0.5px solid black;border-right: 1px solid gray;"><a href="/FabricStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=1" target="_blank"><b>'.$closing_value.'</b></a></td>';
                        
                        $closing_qty = 0;
                        $closing_value= 0;
                    }
                    
                $html .='</tr> 
                    <tr>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky-col third-col"><b>Non Moving</b></td>';
                    
                    for($j = 0; $j< count($period3);$j++)
                    {   
                        $closing_qtyNon = money_format('%!.0n',round($ClosingFabricQtyArrNon[$j]));  
                        $closing_valueNon = money_format('%!.0n',round($ClosingFabricValueArrNon[$j]));  
                        $ls_date = $monthArr[$j];
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;"><a href="/FabricStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=2" target="_blank"><b>'.$closing_qtyNon.'</b></a></td> 
                        <td class="text-right Fabric_non_moving_value" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;"><a href="/FabricStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=2" target="_blank"><b>'.$closing_valueNon.'</b></a></td>';
                        
                        $closing_qtyNon = 0;
                        $closing_valueNon= 0;
                    }
                    
                $html .='</tr>';
                    
                $html .='<tr>
                    <td style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;" class="sticky-col third-col">Total</td>';
                   
                    for($j = 0; $j< count($period3);$j++)
                    {   
                        $total_closing_qtyNon =  money_format('%!.0n',round($ClosingFabricQtyArr[$j] + $ClosingFabricQtyArrNon[$j]));   
                        $total_closing_valueNon = money_format('%!.0n',round($ClosingFabricValueArrNon[$j] + $ClosingFabricValueArr[$j])); 
                        $ls_date = $monthArr[$j];
                        $html .='<td class="text-right total_qty" style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;"><a href="/FabricStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=0" target="_blank"><b>'.$total_closing_qtyNon.'</b></a></td> 
                        <td class="text-right fabric_total_value" style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;"><a href="/FabricStockDataTrialCloned?currentDate='.$ls_date.'&job_status_id=0" target="_blank"><b>'.$total_closing_valueNon.'</b></a></td>';
                        
                        $total_closing_qtyNon = 0;
                        $total_closing_valueNon= 0;
                         
                    }
                    
                $html .='</tr>';
                
                return response()->json(['html' => $html]);
    }
    
    
    public function LoadFGInventoryMovingNonMovingReport(Request $request)
    {
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : 4;
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
           $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
           
        }
         
        $period1 = $this->getBetweenDates($fDate, $tDate);
         
        $period2 = $this->getBetweenDates($fDate, $tDate);
         
         
        $pd = array();
        $pd1 = array();
       
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        
        $period = array_unique($pd);
        
        
        foreach ($period2 as $date1) 
        {
            $pd1[] = $date1; 
        }
        
        $period3 = array_unique($pd1);
         
        $ClosingFGQtyArr = []; 
        $ClosingFGValueArr = []; 
        $ClosingFGQtyArrNon = []; 
        $ClosingFGValueArrNon = [];
          
        $monthArr = [];
        
        foreach ($period as $dates) 
        {  
            $firstDate = $dates . "-01";
            $lastDate = date("Y-m-t", strtotime($dates . "-01"));
        
            $total_value = 0;
            $total_stock = 0; 
        
            $FinishedGoodsStock = DB::SELECT("SELECT FG.sales_order_no,
                        sales_order_costing_master.total_cost_value,buyer_purchse_order_master.order_rate,
                        buyer_purchse_order_master.sam FROM FGStockDataByTwo as FG 
                        INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no 
                        LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
                        WHERE FG.data_type_id IN(1,2,3)  AND buyer_purchse_order_master.job_status_id NOT IN(0,2,3,4,5) GROUP BY FG.sales_order_no");
                        
             
        
             //dd(DB::getQueryLog());                   
            $total_packing = 0;
            $total_carton = 0;
            $total_transfer = 0;
            $total_stock = 0; 
            $total_value = 0; 
            $srno = 1;    
            foreach($FinishedGoodsStock as $row)
            {
                
                   $packingData = ""; 
                   $cartonData = "";                
                   $tramsferData = "";
       
                   $cartonData = DB::table('FGStockDataByTwo as FG')
                    ->select(
                        DB::raw("sum(CASE WHEN FG.data_type_id = 1  AND FG.entry_date <= '".$lastDate."'  THEN size_qty ELSE 0 END) as packingData"),
                        DB::raw("sum(CASE WHEN FG.data_type_id = 2  AND FG.invoice_date <= '".$lastDate."'  THEN size_qty ELSE 0 END) as cartonData"),
                        DB::raw("sum(CASE WHEN FG.data_type_id = 3  AND FG.entry_date <= '".$lastDate."'  THEN size_qty ELSE 0 END) as tramsferData")
                    )
                    ->where('FG.sales_order_no', '=', $row->sales_order_no) 
                    ->groupBy('FG.sales_order_no')  
                    ->get();

                      
                $packing_qty = isset($cartonData[0]->packingData) ? $cartonData[0]->packingData : 0; 
                $carton_pack_qty = isset($cartonData[0]->cartonData) ? $cartonData[0]->cartonData : 0; 
                $transfer_qty = isset($cartonData[0]->tramsferData) ? $cartonData[0]->tramsferData : 0; 
                
                $stock  =   $packing_qty - $carton_pack_qty - $transfer_qty;
                if($row->total_cost_value == 0)
                {
                     $value = $stock * $row->order_rate;
                     $fob_rate =  number_format($row->order_rate,4);
                     $fob_rate1 =  $row->order_rate;
                }
                else
                {
                    $value = $stock * $row->total_cost_value;
                    $fob_rate = number_format($row->total_cost_value,4);
                    $fob_rate1 = $row->total_cost_value;
                }  
               
                             
                $packing_qty = isset($packingData[0]->packingData) ? $packingData[0]->packingData : 0; 
                 
                    
                $total_packing += $packing_qty;
                $total_carton += $carton_pack_qty;
                $total_transfer += $transfer_qty;
                $total_stock += $stock; 
                $total_value += ($stock*$fob_rate1); 
            }  
            if(date("Y-m", strtotime(date("Y-m-d"))) >= date("Y-m", strtotime($lastDate)))
            {
                $ClosingFGQtyArr[] = $total_stock;
                $ClosingFGValueArr[] = $total_value;
            }
            else
            {
                $ClosingFGQtyArr[] = 0;
                $ClosingFGValueArr[] = 0;
            }

           $monthArr[] = $lastDate;
           
        }
        
        
        $ClosingFGQtyArr = array_values($ClosingFGQtyArr);
        $ClosingFGValueArr = array_values($ClosingFGValueArr);

        foreach($period3 as $dates1)
        {  
            $firstDate1 = $dates1."-01";
            $lastDate1 = date("Y-m-t", strtotime( $dates1."-01"));
          
            $total_value1 = 0;
            $total_stock1 = 0; 
            
            
             $FinishedGoodsStock1 = DB::SELECT("SELECT FG.sales_order_no,sales_order_costing_master.total_cost_value,buyer_purchse_order_master.order_rate,buyer_purchse_order_master.sam
                        FROM FGStockDataByTwo as FG INNER JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no 
                        LEFT JOIN sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
                        WHERE FG.data_type_id IN(1,2,3) AND buyer_purchse_order_master.job_status_id IN(0,2,3,4,5) GROUP BY FG.sales_order_no");                 
             //dd(DB::getQueryLog());                   
            $total_packing = 0;
            $total_carton = 0;
            $total_transfer = 0; 
            $srno = 1;    
            foreach($FinishedGoodsStock1 as $row)
            {
                
                   $packingData = ""; 
                   $cartonData = "";                
                   $tramsferData = "";
       
                   $packingData = DB::table('FGStockDataByTwo as FG')
                    ->select(
                        DB::raw("sum(CASE WHEN FG.data_type_id = 1  AND FG.entry_date <= '".$lastDate1."'  THEN size_qty ELSE 0 END) as packingData"),
                        DB::raw("sum(CASE WHEN FG.data_type_id = 2  AND FG.invoice_date <= '".$lastDate1."'  THEN size_qty ELSE 0 END) as cartonData"),
                        DB::raw("sum(CASE WHEN FG.data_type_id = 3  AND FG.entry_date <= '".$lastDate1."'  THEN size_qty ELSE 0 END) as tramsferData")
                    )
                    ->where('FG.sales_order_no', '=', $row->sales_order_no)
                    ->groupBy('FG.sales_order_no')  
                    ->get(); 
                      
                $packing_qty = isset($packingData[0]->packingData) ? $packingData[0]->packingData : 0; 
                $carton_pack_qty = isset($packingData[0]->cartonData) ? $packingData[0]->cartonData : 0; 
                $transfer_qty = isset($packingData[0]->tramsferData) ? $packingData[0]->tramsferData : 0; 
                
                $stock  =   $packing_qty - $carton_pack_qty - $transfer_qty;
                if($row->total_cost_value == 0)
                {
                     $value = $stock * $row->order_rate;
                     $fob_rate =  number_format($row->order_rate,4);
                     $fob_rate1 =  $row->order_rate;
                }
                else
                {
                    $value = $stock * $row->total_cost_value;
                    $fob_rate = number_format($row->total_cost_value,4);
                    $fob_rate1 = $row->total_cost_value;
                }  
               
                             
                $packing_qty = isset($packingData[0]->packingData) ? $packingData[0]->packingData : 0; 
                 
                    
                $total_packing += $packing_qty;
                $total_carton += $carton_pack_qty;
                $total_transfer += $transfer_qty;
                $total_stock1 += $stock; 
                $total_value1 += ($stock*$fob_rate1); 
            }  
            if(date("Y-m", strtotime(date("Y-m-d"))) >= date("Y-m", strtotime($lastDate1)))
            {
                $ClosingFGQtyArrNon[] = $total_stock1;
                $ClosingFGValueArrNon[] = $total_value1;
            }
            else
            {
                $ClosingFGQtyArrNon[] = 0;
                $ClosingFGValueArrNon[] = 0;
            }
        }
        
        $ClosingFGQtyArrNon = array_values($ClosingFGQtyArrNon);
        $ClosingFGValueArrNon = array_values($ClosingFGValueArrNon);
        
        $html ='<tr>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;" class="sticky-col first-col" rowspan=3><b>C</b></td>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid #8080803d;" nowrap class="sticky-col second-col" rowspan=3><b>FG</b></td>
                    <td style="background: antiquewhite;border-bottom: 0.5px solid black;border-right: 1px solid gray;" class="sticky-col third-col"><b>Moving</b></td>';
                    
                    for($i = 0; $i< count($period);$i++)
                    {   
                        $closing_qty =  money_format('%!.0n',round($ClosingFGQtyArr[$i]));    
                        $closing_value = money_format('%!.0n',round($ClosingFGValueArr[$i]));     
                        $ls_date = $monthArr[$i];
                        
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 0.5px solid black;border-right: 1px solid #8080803d;"><b><a href="FGStockReportTrial?currentDate='.$ls_date.'&job_status_id=1" target="_blank">'.$closing_qty.'</a></b></td> 
                        <td class="text-right" style="background:'.$colorArr[0].';border-bottom: 0.5px solid black;border-right: 1px solid gray;"><b><a href="FGStockReportTrial?currentDate='.$ls_date.'&job_status_id=1" target="_blank">'.$closing_value.'</a></b></td>';
                        
                        $closing_qty = 0;
                        $closing_value= 0;
                    }
                    
                $html .='</tr> 
                    <tr>
                    <td style="background: antiquewhite;border-bottom: 3px solid black;border-right: 1px solid gray;" class="sticky-col third-col"><b>Non Moving</b></td>';
                    
                    for($j = 0; $j< count($period3);$j++)
                    {   
                        $closing_qtyNon = money_format('%!.0n',round($ClosingFGQtyArrNon[$j]));      
                        $closing_valueNon = money_format('%!.0n',round($ClosingFGValueArrNon[$j]));   
                        $ls_date = $monthArr[$j];
                        
                        $html .='<td class="text-right" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid #8080803d;"><b><a href="FGStockReportTrial?currentDate='.$ls_date.'&job_status_id=2" target="_blank">'.$closing_qtyNon.'</a></b></td> 
                        <td class="text-right FG_non_moving_value" style="background:'.$colorArr[0].';border-bottom: 3px solid black;border-right: 1px solid gray;"><b><a href="FGStockReportTrial?currentDate='.$ls_date.'&job_status_id=2" target="_blank">'.$closing_valueNon.'</a></b></td>';
                        
                        $closing_qtyNon = 0;
                        $closing_valueNon= 0;
                    }
                    
                $html .='</tr> 
                    <tr>
                        <td style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;" class="sticky-col third-col">Total</td>';
                        
                        for($j = 0; $j< count($period3);$j++)
                        {   
                            $total_closing_qtyNon =  money_format('%!.0n',round($ClosingFGQtyArrNon[$j] + $ClosingFGQtyArr[$j]));  
                            $total_closing_valueNon = money_format('%!.0n',round($ClosingFGValueArrNon[$j] + $ClosingFGValueArr[$j])); 
                            $ls_date = $monthArr[$j];  
                            
                            $html .='<td class="text-right total_qty" style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;"><b><a href="FGStockReportTrial?currentDate='.$ls_date.'&job_status_id=0" target="_blank">'.$total_closing_qtyNon.'</a></b></td> 
                            <td class="text-right FG_total_value" style="background: #e48200;border-bottom: 3px solid black;border-right: 1px solid gray;color: #fff;font-weight: 800;"><b><a href="FGStockReportTrial?currentDate='.$ls_date.'&job_status_id=0" target="_blank">'.$total_closing_valueNon.'</a></b></td>';
                            
                            $total_closing_qtyNon = 0;
                            $total_closing_valueNon= 0;
                        }
                        
                $html .='</tr>';
                return response()->json(['html' => $html]);
    } 
    
    public function LoadWIPInventoryMovingNonMoving(Request $request)
    { 
        $fin_year_id = isset($request->fin_year_id) ? $request->fin_year_id : 4;
        if($fin_year_id == "" || $fin_year_id == 0)
        {
            $maxData = DB::SELECT("SELECT MAX(fin_year_id) as max_id FROM financial_year_master WHERE delflag=0");
            $fin_year_id = isset($maxData[0]->max_id) ? $maxData[0]->max_id : 0;
        }
       
        $Financial_Year1=DB::select("SELECT fin_year_id, fin_year_name FROM financial_year_master where delflag=0");
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id='".$fin_year_id."'");
        
        $colorArr = array('#ff000017','#1fd81f2e','#0000ff24','#b3d22952','#a0913154','#ffa5004f','#8d331633','#4868e030','#c45ecca8','#5ecc5ea8', '#8d331663', '#4868e06e');
        
        $fDate = $Financial_Year[0]->fdate;
        $year =  date('Y');
       
        $tDate = date($year.'-03-d');
        
        if($fDate > $tDate)
        {
            $tDate = date('Y-m-d', strtotime('+1 year', strtotime($tDate)));
        }
        $tDate1  = date("y-m-d");
        $period1 = $this->getBetweenDates($fDate, $tDate);
        $period2 = $this->getBetweenDates($fDate, $tDate1);
        $pd = array();
       
        foreach ($period1 as $date) 
        {
            $pd[] = $date; 
        }
        $pd1 = array();
       
        foreach ($period2 as $date1) 
        {
            $pd1[] = $date1; 
        }
        $period = array_unique($pd);
        $period1 = array_unique($pd1);
        $WIPPCSArr = [];
        $WIPValueArr = [];
        
        $monthArr1 = [];
        $monthArr = [];
        
        foreach($period as $dates)
        {  
            $firstDate = $dates."-01";
            $lastDate = date("Y-m-t", strtotime( $dates."-01"));
          
            $total_value = 0;
            $total_stock = 0; 
             

            $Buyer_Purchase_Order_List = DB::select("SELECT * FROM buyer_purchse_order_master 
                    WHERE 
                        (
                            order_received_date <= '".$lastDate."' 
                            AND buyer_purchse_order_master.job_status_id = 1 
                            AND og_id != 4
                            AND order_type != 2
                        ) 
                        OR 
                            (
                                order_close_date = '".$lastDate."'
                                AND og_id != 4 
                                AND buyer_purchse_order_master.delflag = 0  
                            )  
                         AND order_type != 2
                    ORDER BY buyer_purchse_order_master.tr_code");
             
            $totalWIPPCS = 0; 
            $totalWIPValue = 0;    
            foreach($Buyer_Purchase_Order_List as $row)  
            {
             
                $VendorData=DB::select("SELECT ifnull(sum(size_qty_total),0) as work_order_qty from vendor_work_order_detail  
                     where  sales_order_no='".$row->tr_code."' AND vw_date <= '".$lastDate."'");
                
                
                $CutPanelData = DB::select("SELECT ifnull(sum(size_qty),0) as total_qty  from cut_panel_grn_size_detail2  
                      where cut_panel_grn_size_detail2.sales_order_no = '".$row->tr_code."' AND cpg_date <= '".$lastDate."'");
                
                if(count($CutPanelData) > 0)
                {
                        $cutPanelIssueQty = $CutPanelData[0]->total_qty;
                }
                else
                {
                        $cutPanelIssueQty = 0;
                } 
                
                $StichingData=DB::select("SELECT ifnull(sum(total_qty),0) as stiching_qty from stitching_inhouse_master  
                     where  sales_order_no='".$row->tr_code."' AND sti_date <='".$lastDate."'");
                
                if(count($StichingData) > 0)
                {
                        $stichingQty = $StichingData[0]->stiching_qty;
                }
                else
                {
                        $stichingQty = 0;
                }
                
                
              $PackingData = DB::select("SELECT ifnull(sum(total_qty),0) as total_qty  from packing_inhouse_master  
                            WHERE packing_inhouse_master.sales_order_no = '".$row->tr_code."' AND pki_date <='".$lastDate."' AND packing_type_id=4");
    
              if(count($PackingData) > 0)
              {
                     $pack_order_qty = $PackingData[0]->total_qty;
              }
              else
              {
                     $pack_order_qty = 0;
              }
             
              
        
              $sewing = $cutPanelIssueQty - $stichingQty;
              
               
              $WIPAdjustQtyData=DB::select("SELECT ifnull(sum(size_qty_total),0) as WIP_adjust_qty from WIP_Adjustable_Qty_detail where sales_order_no='".$row->tr_code."'");
              
              $rejectData = DB::select("SELECT ifnull(sum(size_qty_total),0) as total_qty  from qcstitching_inhouse_reject_detail  
                            WHERE qcstitching_inhouse_reject_detail.sales_order_no = '".$row->tr_code."' AND qcsti_date <='".$lastDate."'");
                            
              $total_adjustable_qty = isset($WIPAdjustQtyData[0]->WIP_adjust_qty) ? $WIPAdjustQtyData[0]->WIP_adjust_qty : 0; 
              $reject_qty = isset($rejectData[0]->total_qty) ? $rejectData[0]->total_qty : 0;
              
              $totalWIPPCS += (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty-$total_adjustable_qty) - $reject_qty); 
              
              $SalesCostingData = DB::select("SELECT * from sales_order_costing_master WHERE sales_order_no = '".$row->tr_code."'");
      
              $fabric_value = isset($SalesCostingData[0]->fabric_value) ? $SalesCostingData[0]->fabric_value : 0;  
              $sewing_trims_value = isset($SalesCostingData[0]->sewing_trims_value) ? $SalesCostingData[0]->sewing_trims_value : 0;
              $packing_trims_value = isset($SalesCostingData[0]->packing_trims_value) ? $SalesCostingData[0]->packing_trims_value : 0;        
       
              $totalWIPValue += (($fabric_value +  $sewing_trims_value + $packing_trims_value) * (($VendorData[0]->work_order_qty - $cutPanelIssueQty) + $sewing +($stichingQty - $pack_order_qty-$total_adjustable_qty) - $reject_qty));
             
         }            
            $WIPPCSArr[] =  $totalWIPPCS; 
            $WIPValueArr[] =  $totalWIPValue; 
          
            $totalWIPPCS = 0; 
            $totalWIPValue = 0;

           $monthArr[] = $lastDate;
           $monthArr1[] = $firstDate;

        }
         
        $html = '<tr style="border:2px solid black;" id="WIPClass">
                    <td class="sticky-col first-col" nowrap style="background: antiquewhite;"><b>D</b></td>
                    <td nowrap class="sticky-col second-col" style="background: antiquewhite;border-right: 1px solid #8080803d;"><b>WIP</b></td>
                    <td class="sticky-col third-col" style="background: antiquewhite;;border-right: 1px solid gray;"><b>Moving</b></td>';
                   
                    for($i = 0; $i< count($period);$i++)
                    { 
                  
                        if($i >= count($period1))
                        {
                            $WIPPCS = 0;
                            $WIPValue = 0;
                           
                        }
                        else
                        {
                            $WIPPCS = money_format('%!.0n',round($WIPPCSArr[$i]));     
                            $WIPValue = money_format('%!.0n',round($WIPValueArr[$i])); 
                        }
                        
                        $fs_date = $monthArr1[$i];  
                        $ls_date = $monthArr[$i];  
                    
                        $html .='<td class="text-right total_qty" style="background:'.$colorArr[0].';border-right: 1px solid #8080803d;"><b><a href="rptTotalWIPReport?fromDate='.$fs_date.'&toDate='.$ls_date.'" target="_blank">'.$WIPPCS.'</a></b></td> 
                                  <td class="text-right WIP_total_value" style="background:'.$colorArr[0].';border-right: 1px solid gray;"><b><a href="rptTotalWIPReport?fromDate='.$fs_date.'&toDate='.$ls_date.'" target="_blank">'.$WIPValue.'</a></b></td>';
                     
                    }
                     
                 $html .='</tr>';
              
        return response()->json(['html' => $html]);
    }  
    public function RunCronFGJob()
    { 
         date_default_timezone_set("Asia/Calcutta"); 
         $time = date("H:i", strtotime("+60 seconds"));
         
         DB::table('syncronization_time_mgmt')->update(['sync_table'=>0]);
         DB::table('syncronization_time_mgmt')->where('stmt_type','=',3)->update(['start_time' => $time, 'status' => 0,'sync_table'=>1]);
    }
    
     public function FGInventoryAgingReport()
    {
        return view('FGInventoryAgingReport');
    }
    
    public function loadFGInventoryAgingReport()
    {
            $currentDate = date('Y-m-d');
        
            $html = [];
    
            $FinishedGoodsStock = DB::SELECT("SELECT 
                        FG.code,
                        FG.data_type_id,
                        FG.ac_name as buyer_name,
                        FG.sales_order_no,
                        FG.mainstyle_name,
                        FG.color_name, 
                        FG.color_id,
                        FG.entry_date,
                        sales_order_costing_master.total_cost_value,
                        buyer_purchse_order_master.order_rate,
                        buyer_purchse_order_master.job_status_id,
                        buyer_purchse_order_master.sam,buyer_purchse_order_master.order_close_date,
                        SUM(CASE WHEN FG.data_type_id = 1 THEN FG.size_qty ELSE 0 END) AS packing_qty,
                        SUM(CASE WHEN FG.data_type_id = 2 THEN FG.size_qty ELSE 0 END) AS carton_pack_qty,
                        SUM(CASE WHEN FG.data_type_id = 3 THEN FG.size_qty ELSE 0 END) AS transfer_qty
                    FROM 
                        FGStockDataByTwo AS FG
                    INNER JOIN 
                        buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = FG.sales_order_no 
                    LEFT JOIN 
                        sales_order_costing_master ON sales_order_costing_master.sales_order_no = FG.sales_order_no
                    WHERE 
                        FG.data_type_id IN (1, 2, 3) AND 
                        FG.entry_date <='".$currentDate."'
                    GROUP BY 
                        FG.sales_order_no, FG.color_id
                    ORDER BY 
                        FG.entry_date ASC");                 
             //dd(DB::getQueryLog());    
     
            foreach($FinishedGoodsStock as $row)
            { 
                
                $packing_qty = isset($row->packing_qty) ? $row->packing_qty : 0; 
                $carton_pack_qty = isset($row->carton_pack_qty) ? $row->carton_pack_qty : 0; 
                $transfer_qty = isset($row->transfer_qty) ? $row->transfer_qty : 0; 
                
                $stocks  =   $packing_qty - $carton_pack_qty - $transfer_qty;
                
                $stocks1 = ($row->entry_date >= date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
                $stocks2 = ($row->entry_date >= date('Y-m-d', strtotime('-60 days')) && $row->entry_date < date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
                $stocks3 = ($row->entry_date >= date('Y-m-d', strtotime('-90 days')) && $row->entry_date < date('Y-m-d', strtotime('-60 days'))) ? $stocks : 0;
                $stocks4 = ($row->entry_date >= date('Y-m-d', strtotime('-180 days')) && $row->entry_date < date('Y-m-d', strtotime('-90 days'))) ? $stocks : 0;
                $stocks5 = ($row->entry_date >= date('Y-m-d', strtotime('-365 days')) && $row->entry_date < date('Y-m-d', strtotime('-180 days'))) ? $stocks : 0;
                $stocks6 = ($row->entry_date <= date('Y-m-d', strtotime('-1 year'))) ? $stocks : 0;
                
                $total_stock = $stocks1 + $stocks2 + $stocks3 + $stocks4 + $stocks5 + $stocks6;
                    
                if($row->total_cost_value == 0)
                {
                     $value1 = $stocks1 * $row->order_rate; 
                     $value2 = $stocks2 * $row->order_rate; 
                     $value3 = $stocks3 * $row->order_rate; 
                     $value4 = $stocks4 * $row->order_rate; 
                     $value5 = $stocks5 * $row->order_rate; 
                     $value6 = $stocks6 * $row->order_rate; 
                     $total_value = ($stocks1 * $row->order_rate) + ($stocks2 * $row->order_rate) + ($stocks3 * $row->order_rate) + ($stocks4 * $row->order_rate) + ($stocks5 * $row->order_rate) + ($stocks6 * $row->order_rate);
                }
                else
                {
                    $value1 = $stocks1 * $row->total_cost_value; 
                    $value2 = $stocks2 * $row->total_cost_value; 
                    $value3 = $stocks3 * $row->total_cost_value; 
                    $value4 = $stocks4 * $row->total_cost_value; 
                    $value5 = $stocks5 * $row->total_cost_value; 
                    $value6 = $stocks6 * $row->total_cost_value; 
                    $total_value = ($stocks1 * $row->total_cost_value) + ($stocks2 * $row->total_cost_value) + ($stocks3 * $row->total_cost_value) + ($stocks4 * $row->total_cost_value) + ($stocks5 * $row->total_cost_value) + ($stocks6 * $row->total_cost_value);
                } 
                    
               
                if($total_stock != 0 || $total_value != 0)
                { 
                    $html[] = [
                        'srno' => count($html) + 1,
                        'sales_order_no' => $row->sales_order_no,
                        'buyer_name' => $row->buyer_name,
                        'mainstyle_name' => $row->mainstyle_name,
                        'color_name' => $row->color_name,
                        'total_stock30' => money_format("%!.0n", $stocks1),
                        'total_value30' => money_format("%!.0n", round($value1,2)),
                        'total_stock60' => money_format("%!.0n", $stocks2),
                        'total_value60' => money_format("%!.0n", round($value2,2)),
                        'total_stock90' => money_format("%!.0n", $stocks3),
                        'total_value90' => money_format("%!.0n", round($value3,2)),
                        'total_stock180' => money_format("%!.0n", $stocks4),
                        'total_value180' => money_format("%!.0n", round($value4,2)),
                        'total_stock365' => money_format("%!.0n", $stocks5),
                        'total_value365' => money_format("%!.0n", round($value5,2)),
                        'previousYearstock' => money_format("%!.0n", $stocks6),
                        'previousYearValue' => money_format("%!.0n", round($value6,2)),
                        'total_stock' => money_format("%!.0n", $total_stock),
                        'total_value' => money_format("%!.0n", round($total_value,2))
                    ];
                }
     
        } 
        
        $jsonData = json_encode($html);
        return response()->json(['html' => $jsonData]);
    }
    
    public function GetFGTransferLocationData(Request $request)
    {
        $html = '';
     
        $FGTransferToLocationInwardList = FGTransferToLocationModel::find($request->fgt_code);
        
        $FGTransferToLocationInwardDetailList =FGTransferToLocationDetailModel::where('fg_trasnfer_to_location_detail.fgt_code','=', $request->fgt_code)->get();
        $SalesOrderList=explode(",",$FGTransferToLocationInwardList->sales_order_no);
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('tr_code',$SalesOrderList[0])->get();
        $sz_codes = isset($BuyerPurchaseOrderMasterList[0]->sz_code) ? $BuyerPurchaseOrderMasterList[0]->sz_code : 0;
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $sz_codes)->get();
        
      
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
            ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
            ->whereIn('tr_code',$SalesOrderList)->DISTINCT()->get();
        
        
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
         
        $sizes=rtrim($sizes,',');
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                 <thead>
                    <tr>
                       <th>SrNo</th>
                       <th nowrap>From Carton No</th>
                       <th nowrap>To Carton No</th>
                       <th nowrap>Sales Order No</th>
                       <th>Color</th>';
                       foreach ($SizeDetailList as $sz) 
                       {
                       $html .= '<th>'.$sz->size_name.'</th>';
                       }
                       $html .= '<th>Total Qty</th>
                       <th>Add/Remove</th>
                    </tr>
                 </thead>
                 <tbody id="CartonData">';
                    $no=1;$n=1; 
                     foreach($FGTransferToLocationInwardDetailList as $List) 
                     {
                         $html .= '<tr>
                           <td>'.$no.'</td>
                           <td><input type="text" name="from_carton_no[]" value="'.$List->from_carton_no.'" id="id" style="width:80px;height:30px; "/></td>
                           <td><input type="text" name="to_carton_no[]" value="'.$List->to_carton_no.'" id="id" style="width:80px;height:30px; "/></td>
                           <td>
                              <select name="sales_order_nos[]" id="sales_order_nos" style="width:150px; height:30px;" disabled class="select2" onchange="CalculateQtyRowProxx(this);">
                                 <option value="">--Sales Order--</option>';
                                 foreach($BuyerPurchaseOrderList as  $row)
                                 {
                                 $html .= '<option value="'.$row->tr_code.'"
                                '.($row->tr_code == $List->sales_order_no ? 'selected="selected"' : '').'
                                 >'.$row->tr_code.'</option>';
                                 }
                              $html .= '</select>
                           </td>
                           <td>
                              <input type="hidden" name="item_codef[]" value="'.$List->item_code.'" id="item_codef"  />
                              <select name="color_id[]"   id="color_id" style="width:200px; height:30px;"  class="select2" onchange="ResetAllValues(this);" >
                                 <option value="">--Color  List--</option>';
                                 foreach($ColorList as  $row)
                                 {
                                 $html .= '<option value="'.$row->color_id.'"
                                 '.($row->color_id == $List->color_id ? 'selected="selected"' : '').'
                                 >'.$row->color_name.'</option>';
                                 }
                              $html .= '</select>
                           </td>';
                           
                           $n=1;   $SizeQtyList=explode(',', $List->size_qty_array);
                          
                           foreach($SizeQtyList  as $szQty)
                           {
                               $html .= '<td><input style="width:80px; float:left;" max="'.$szQty.'" min="0" name="s'.$n.'[]" class="size_id" type="number" id="s'.$n.'" value="'.$szQty.'" required />  </td>';
                               $n=$n+1; 
                           }
                           $html .= '<td><input type="number" name="size_qty_total[]" class="size_qty_total" value="'.$List->size_qty_total.'" id="size_qty_total" style="width:80px; height:30px; float:left;" readonly />
                              <input type="hidden" name="size_qty_array[]"  value="'.$List->size_qty_array.'" id="size_qty_array" style="width:80px; float:left;"  />
                              <input type="hidden" name="size_array[]"  value="'.$List->size_array.'" id="size_array" style="width:80px;  float:left;"  />
                           </td>
                           <td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>
                        </tr>';
                         $no=$no+1;
                     }
                 $html .= '</tbody>
              </table>';
          
          $sales_order_no = $FGTransferToLocationInwardList->sales_order_no;
          $Ac_code = $FGTransferToLocationInwardList->Ac_code;
          $from_loc_id = $FGTransferToLocationInwardList->from_loc_id;
          $to_loc_id = $FGTransferToLocationInwardList->to_loc_id;
          $driver_name = $FGTransferToLocationInwardList->driver_name;
          $vehical_no = $FGTransferToLocationInwardList->vehical_no;
          $vehical_no = $FGTransferToLocationInwardList->vehical_no;
          $total_qty = $FGTransferToLocationInwardList->total_qty;
          $narration = $FGTransferToLocationInwardList->narration;
          
          return response()->json(['html' => $html, 'narration'=>$narration ,'total_qty'=>$total_qty,'Ac_code'=>$Ac_code,'from_loc_id'=>$from_loc_id,'to_loc_id'=>$to_loc_id,'driver_name'=>$driver_name,'vehical_no'=>$vehical_no,'sales_order_no'=>$sales_order_no ]);
     
    }
}
