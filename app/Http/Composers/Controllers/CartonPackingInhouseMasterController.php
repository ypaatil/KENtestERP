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

use App\Models\CartonPackingInhouseMasterModel;
use App\Models\CartonPackingInhouseDetailModel;
use App\Models\CartonPackingInhouseSizeDetailModel;

 

use Session;

class CartonPackingInhouseMasterController extends Controller
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
        
        //   DB::enableQueryLog();
        $CartonPackingInhouseMasterList = CartonPackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'carton_packing_inhouse_master.userId', 'left outer')
        ->join('ledger_master as L1', 'L1.Ac_code', '=', 'carton_packing_inhouse_master.Ac_code', 'left outer')
        
        ->where('carton_packing_inhouse_master.delflag','=', '0')
        ->get(['carton_packing_inhouse_master.*','usermaster.username','L1.Ac_name' ]);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('CartonPackingInhouseMasterList', compact('CartonPackingInhouseMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CartonPackingInhouse'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        return view('CartonPackingInhouseMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList', 'BuyerPurchaseOrderList','Ledger',  'counter_number','FirmList'));
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
        ->where('type','=','CartonPackingInhouse')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
          
        $this->validate($request, [
             
                'cpki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'mainstyle_id'=>'required',
                'order_rate'=>'required',
               
    ]);
 
 $sales_order_no=implode($request->sales_order_no,',');
 
$data1=array
    (
        'cpki_code'=>$TrNo, 
        'cpki_date'=>$request->cpki_date, 
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
        'order_amount'=>$request->order_amount,
        'narration'=>$request->narration,
        'buyer_location_id'=>$request->buyer_location_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
     );
 
    CartonPackingInhouseMasterModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='CartonPackingInhouse'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array
                    (
					'cpki_code'=>$TrNo,
                    'cpki_date'=>$request->cpki_date,
                    'sales_order_no'=>$request->sales_order_nos[$x],
                    'Ac_code'=>$request->Ac_code, 
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'carton_no'=>$request->carton_no[$x],
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
                  
                        'cpki_code'=>$TrNo, 
                        'cpki_date'=>$request->cpki_date, 
                        'sales_order_no'=>$request->sales_order_nos[$x],
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'carton_no'=>$request->carton_no[$x],
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
              
              } // if loop avoid zero qty
            }
          CartonPackingInhouseDetailModel::insert($data2);
          CartonPackingInhouseSizeDetailModel::insert($data3);
          
         
    }
    
        
   $InsertSizeData=DB::select('call AddSizeQtyFromCartonPackingInhouse("'.$TrNo.'")');
           
    return redirect()->route('CartonPackingInhouse.index')->with('message', 'Data Saved Succesfully');  
      
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
        $CartonPackingInhouseMasterList = CartonPackingInhouseMasterModel::find($id);
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
         
         $LedgerDetail  = LedgerDetailModel::where('ledger_details.ac_code',$CartonPackingInhouseMasterList->Ac_code)->get();
          $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->where('Ac_code',$CartonPackingInhouseMasterList->Ac_code)->get();
        
         
         
         
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$CartonPackingInhouseMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$CartonPackingInhouseMasterList->sales_order_no)->DISTINCT()->get();
        
        
        
         
        
        
        
        $CartonPackingInhouseDetailList =CartonPackingInhouseDetailModel::where('carton_packing_inhouse_detail.cpki_code','=', $CartonPackingInhouseMasterList->cpki_code)->get();
        //  
       
        
             // DB::enableQueryLog(); 
        
        $S1= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')
        ->whereNotIn('buyer_purchse_order_master.tr_code',function($query){
        $query->select('carton_packing_inhouse_master.sales_order_no')->from('carton_packing_inhouse_master');
        });
        $S2=CartonPackingInhouseMasterModel::select('sales_order_no')->where('sales_order_no',$CartonPackingInhouseMasterList->sales_order_no);
        $VendorWorkOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($CartonPackingInhouseMasterList->sales_order_no);
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
        $MasterdataList = DB::select("SELECT sales_order_detail.item_code, sales_order_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from sales_order_detail inner join color_master on 
      color_master.color_id=sales_order_detail.color_id where tr_code='".$CartonPackingInhouseMasterList->sales_order_no."'
      group by sales_order_detail.color_id");
        
        return view('CartonPackingInhouseMasterEdit',compact('CartonPackingInhouseDetailList','ColorList' ,'FirmList','BuyerPurchaseOrderList','LedgerDetail',  'MasterdataList','SizeDetailList','CartonPackingInhouseMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger' ));
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
             
                'cpki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
               
    ]);
 
  
  
   $sales_order_no=implode($request->sales_order_no,',');
 
 
  
  
$data1=array(
           
        'cpki_code'=>$request->cpki_code, 
        'cpki_date'=>$request->cpki_date,
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
        'order_amount'=>$request->order_amount,
        'narration'=>$request->narration,
        'buyer_location_id'=>$request->buyer_location_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
         
    );
//   DB::enableQueryLog();   
$PackingInhouseList = CartonPackingInhouseMasterModel::findOrFail($request->cpki_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$PackingInhouseList->fill($data1)->save();

 
DB::table('carton_packing_inhouse_size_detail')->where('cpki_code', $request->input('cpki_code'))->delete();
DB::table('carton_packing_inhouse_size_detail2')->where('cpki_code', $request->input('cpki_code'))->delete();
DB::table('carton_packing_inhouse_detail')->where('cpki_code', $request->input('cpki_code'))->delete();
 
 $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array(
          
                    'cpki_code'=>$request->cpki_code,
                    'cpki_date'=>$request->cpki_date,
                    'sales_order_no'=>$request->sales_order_nos[$x],
                    'Ac_code'=>$request->Ac_code, 
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'carton_no'=>$request->carton_no[$x],
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
                  
                        'cpki_code'=>$request->cpki_code,
                        'cpki_date'=>$request->cpki_date, 
                        'sales_order_no'=>$request->sales_order_nos[$x],
                        'Ac_code'=>$request->Ac_code, 
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'carton_no'=>$request->carton_no[$x],
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
              
              } // if loop avoid zero qty
            }
          CartonPackingInhouseDetailModel::insert($data2);
          CartonPackingInhouseSizeDetailModel::insert($data3);
          
    }
    
           
           
    $InsertSizeData=DB::select('call AddSizeQtyFromCartonPackingInhouse("'.$request->cpki_code.'")');
           
           
     return redirect()->route('CartonPackingInhouse.index')->with('message', 'Data Saved Succesfully'); 
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
    //   DB::enableQueryLog(); 
       $CompareList = DB::select("SELECT ifnull(carton_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from carton_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=carton_packing_inhouse_size_detail.color_id where 
      carton_packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
      carton_packing_inhouse_size_detail.color_id='".$color_id."'
       ");
        
    //      $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
       $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
    //   DB::enableQueryLog(); 
    
     
      $List = DB::select("SELECT  ifnull(packing_inhouse_size_detail.item_code,0) as item_code,ifnull(packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizex.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=packing_inhouse_size_detail.color_id where 
      packing_inhouse_size_detail.sales_order_no ='".$sales_order_no."' and
      packing_inhouse_size_detail.color_id='".$color_id."'");
       
    //  $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
      
       
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
       
    //   DB::enableQueryLog(); 
       $MasterdataList=DB::select("select ".$List[0]->item_code." as item_code,".$sizeList.($nox-1)." as size_count");
    //          $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
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
        $html = '<option value="">--Location List--</option>';
        
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
          <td>'.$no.'</td>';
                
        $html.='<td> <input style="width:80px; float:left;"  name="carton_no[]"   type="number" id="carton_no" value="" required /></td>';
         
      $html.=' <td>
      
        
        <select name="sales_order_nos[]" class="select2-select"  id="sales_order_nos0" style="width:150px; height:30px;" required>
        <option value="">--Sales Order No--</option>';

        foreach($SalesOrders as  $value)
        {
            $html.='<option value="'.$value.'"';
           
            $html.='>'.$value.'</option>';
        }
        
        $html.='</select></td>';
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required>
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

        
         

      return response()->json(['html' => $html]);
         
  }

  
  public function PKI_GetOrderQty(Request $request)
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
      $html .= '  
      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
              <thead>
              <tr>
              <th>SrNo</th>
               <th>Carton  No</th>
                <th>Sales Order No</th>
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
                
        $html.='<td> <input style="width:80px; float:left;"  name="carton_no[]"   type="number" id="carton_no" value="" required /></td>';
         
      $html.=' <td>
      
        
        <select name="sales_order_nos[]" class="select2-select"  id="sales_order_nos0" style="width:150px; height:30px;" required>
        <option value="">--Sales Order No--</option>';

        foreach($SalesOrders as  $value)
        {
            $html.='<option value="'.$value.'"';
           
            $html.='>'.$value.'</option>';
        }
        
        $html.='</select></td>';
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required>
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
        DB::table('carton_packing_inhouse_master')->where('cpki_code', $id)->delete();
        DB::table('packing_inhouse_size_detail2')->where('cpki_code', $id)->delete();
        DB::table('packing_inhouse_size_detail')->where('cpki_code', $id)->delete();
        DB::table('carton_packing_inhouse_detail')->where('cpki_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
}
