<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\SalesOrderFabricCostingDetailModel;
use App\Models\SeasonModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ClassificationModel;
use App\Models\CurrencyModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\QualityModel;
use App\Models\SalesOrderSewingTrimsCostingDetailModel;
use Illuminate\Support\Facades\DB;
use App\Models\SalesOrderCostingMasterModel;
use App\Models\SalesOrderPackingTrimsCostingDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use Session;

class SalesOrderCostingController extends Controller
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
        ->where('form_id', '88')
        ->first();
        

        //   DB::enableQueryLog();
        $SalesOrderCostingList = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code', 'left outer')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id', 'left outer')
        ->join('fg_master', 'fg_master.fg_id', '=', 'sales_order_costing_master.fg_id', 'left outer')
        ->where('sales_order_costing_master.delflag','=', '0')
        ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.Ac_name',  'fg_master.fg_name','main_style_master.mainstyle_name' ]);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('SalesOrderCostingMasterList', compact('SalesOrderCostingList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BUYER_JOB_CARD'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $SalesOrderList= BuyerPurchaseOrderMasterModel::whereNotIn('buyer_purchse_order_master.tr_code',function($query){
               $query->select('sales_order_no')->from('sales_order_costing_master');
            })->get();


        return view('SalesOrderCostingMaster',compact('ClassList','ClassList2','ClassList3','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList','ColorList','counter_number'));

         
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
  ->where('type','=','SALES_ORDER_COSTING')
   ->where('firm_id','=',1)
  ->first();
  $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        
        
        $this->validate($request, [
             
                'soc_date'=> 'required', 
                 'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'agent_commission_value'=> 'required',
                'total_cost_value'=> 'required',
                'other_value'=> 'required',
                'production_value'=> 'required',
                'fabric_value'=> 'required',
                'sewing_trims_value'=> 'required',
                'packing_trims_value'=> 'required',
                
    ]);
 
 
$data1=array(
           
        'soc_code'=>$TrNo, 
        'soc_date'=>$request->soc_date, 
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
        'order_rate'=>$request->order_rate,
        'exchange_rate'=>$request->exchange_rate,
        'sam'=>$request->sam,
        
        'fabric_value'=>$request->fabric_value, 
        'sewing_trims_value'=>$request->sewing_trims_value,
        'packing_trims_value'=>$request->packing_trims_value, 
         'production_value'=>$request->production_value,
        'other_value'=>$request->other_value,   
        'transaport_value'=>$request->transport_value,
        'agent_commision_value'=>$request->agent_commission_value,
        'dbk_value'=>$request->dbk_value, 
        'garment_reject_value'=>$request->garment_reject_value,   
        'testing_charges_value'=>$request->testing_charges_value,   
        'finance_cost_value'=>$request->finance_cost_value,   
        'extra_value'=>$request->extra_value,   
        'total_cost_value'=>$request->total_cost_value,
        'narration'=>$request->narration,
        'is_approved'=>'0',
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
        
    );
 
    SalesOrderCostingMasterModel::insert($data1);
 DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='SALES_ORDER_COSTING'");

    $class_id= $request->input('class_id');
    if(count($class_id)>0)
    {
    
    for($x=0; $x<count($class_id); $x++) {
        # code...
            $data2[]=array(
                
            'soc_code'=>$TrNo, 
            'soc_date'=>$request->soc_date, 
            'cost_type_id'=>$request->cost_type_id,
            'Ac_code'=>$request->Ac_code, 
            'sales_order_no'=>$request->sales_order_no,
            'season_id'=>$request->season_id,
            'currency_id'=>$request->currency_id, 
            'class_id' => $request->class_id[$x],
            'description' => $request->description[$x],
            'consumption' => $request->consumption[$x],
            'rate_per_unit' => $request->rate_per_unit[$x],
            'wastage' => $request->wastage[$x],
            'bom_qty' => $request->bom_qty[$x],
            'total_amount' => $request->total_amount[$x],
            
             );
            }
          SalesOrderFabricCostingDetailModel::insert($data2);
         
    }

   $class_ids = $request->input('class_ids');
    if(count($class_ids)>0)
    {
     for($x=0; $x<count($class_ids); $x++) {
        # code...
            $data3[]=array(
                
            'soc_code'=>$TrNo, 
            'soc_date'=>$request->soc_date, 
            'cost_type_id'=>$request->cost_type_id,
            'Ac_code'=>$request->Ac_code, 
            'sales_order_no'=>$request->sales_order_no,
            'season_id'=>$request->season_id,
            'currency_id'=>$request->currency_id, 
            'class_id' => $request->class_ids[$x],
            'description' => $request->descriptions[$x],
            'consumption' => $request->consumptions[$x],
            'rate_per_unit' => $request->rate_per_units[$x],
            'wastage' => $request->wastages[$x],
            'bom_qty' => $request->bom_qtys[$x],
            'total_amount' => $request->total_amounts[$x],
           
             );
            }
          SalesOrderSewingTrimsCostingDetailModel::insert($data3);
    }
     
$class_idss = $request->input('class_idss');
    if(count($class_idss)>0)
    {
     for($x=0; $x<count($class_idss); $x++) {
        # code...
            $data4[]=array(
                
            'soc_code'=>$TrNo, 
            'soc_date'=>$request->soc_date, 
           'cost_type_id'=>$request->cost_type_id,
            'Ac_code'=>$request->Ac_code, 
            'sales_order_no'=>$request->sales_order_no,
            'season_id'=>$request->season_id,
            'currency_id'=>$request->currency_id, 
            'class_id' => $request->class_idss[$x],
            'description' => $request->descriptionss[$x],
            'consumption' => $request->consumptionss[$x],
            'rate_per_unit' => $request->rate_per_unitss[$x],
            'wastage' => $request->wastagess[$x],
            'bom_qty' => $request->bom_qtyss[$x],
            'total_amount' => $request->total_amountss[$x],
            
             );
            }
          SalesOrderPackingTrimsCostingDetailModel::insert($data4);
    } 

       
    return redirect()->route('SalesOrderCosting.index')->with('message', 'Data Saved Succesfully');  
      
  }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(SalesOrderCostingMasterModel $SalesOrderCostingMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   $ApproveMasterList= DB::table('approve_master')->get();
        $CPList= DB::table('cp_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $SalesOrderCostingMasterList = SalesOrderCostingMasterModel::find($id);
        // DB::enableQueryLog();
        $FabricList = SalesOrderFabricCostingDetailModel::where('sales_order_fabric_costing_details.soc_code','=', $SalesOrderCostingMasterList->soc_code)->get();
        $SewingTrimsList = SalesOrderSewingTrimsCostingDetailModel::where('sales_order_sewing_trims_costing_details.soc_code','=', $SalesOrderCostingMasterList->soc_code)->get();
        $PackingTrimsList = SalesOrderPackingTrimsCostingDetailModel::where('sales_order_packing_trims_costing_details.soc_code','=', $SalesOrderCostingMasterList->soc_code)->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();

         $sales_order_no=$SalesOrderCostingMasterList->sales_order_no;
        //   DB::enableQueryLog();
         $S1= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->whereNotIn('buyer_purchse_order_master.tr_code',function($query){
               $query->select('sales_order_no')->from('sales_order_costing_master');
            });
        //     $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        // 
              
           $S2=SalesOrderCostingMasterModel::select('sales_order_no')->where('sales_order_no',$sales_order_no);
    //    
              
         $SalesOrderList = $S1->union($S2)->get();
 
 
       $is_approved=0;
        if($SalesOrderCostingMasterList->is_approved==1){ $is_approved=1;}else{$is_approved=2;}
 
 
 
  return view('SalesOrderCostingMasterEdit',compact('is_approved','ApproveMasterList','ClassList','ClassList2','ClassList3','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','SalesOrderCostingMasterList','FabricList','SewingTrimsList','PackingTrimsList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList' ));
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
             
                'soc_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
                'agent_commission_value'=> 'required',
                'total_cost_value'=> 'required',
                'other_value'=> 'required',
                'other_value'=> 'required',
                'production_value'=> 'required',
                 'fabric_value'=> 'required',
                'sewing_trims_value'=> 'required',
                'packing_trims_value'=> 'required',
                
             
              
    ]);
 
 
$data1=array(
        'soc_code'=>$request->soc_code, 
        'soc_date'=>$request->soc_date, 
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
        'order_rate'=>$request->order_rate,
        'exchange_rate'=>$request->exchange_rate,
        'sam'=>$request->sam,
        'fabric_value'=>$request->fabric_value, 
        'sewing_trims_value'=>$request->sewing_trims_value,
        'packing_trims_value'=>$request->packing_trims_value, 
        'production_value'=>$request->production_value,
        'other_value'=>$request->other_value,   
        'transaport_value'=>$request->transport_value,
        'agent_commision_value'=>$request->agent_commission_value,
        'dbk_value'=>$request->dbk_value, 
        'garment_reject_value'=>$request->garment_reject_value,   
        'testing_charges_value'=>$request->testing_charges_value,   
        'finance_cost_value'=>$request->finance_cost_value,   
        'extra_value'=>$request->extra_value,
        'total_cost_value'=>$request->total_cost_value,
        'narration'=>$request->narration,
        'is_approved'=>$request->is_approved,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
   );
//   DB::enableQueryLog();   
$SalesOrderCostingList = SalesOrderCostingMasterModel::findOrFail($request->soc_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$SalesOrderCostingList->fill($data1)->save();


DB::table('sales_order_fabric_costing_details')->where('soc_code', $request->input('soc_code'))->delete();
DB::table('sales_order_packing_trims_costing_details')->where('soc_code', $request->input('soc_code'))->delete();
DB::table('sales_order_sewing_trims_costing_details')->where('soc_code', $request->input('soc_code'))->delete();
 
 $class_id= $request->input('class_id');
    if(count($class_id)>0)
    {
    
    for($x=0; $x<count($class_id); $x++) {
        # code...
            $data2[]=array(
            'soc_code'=>$request->soc_code, 
            'soc_date'=>$request->soc_date, 
            'cost_type_id'=>$request->cost_type_id,
            'Ac_code'=>$request->Ac_code, 
            'sales_order_no'=>$request->sales_order_no,
            'season_id'=>$request->season_id,
            'currency_id'=>$request->currency_id, 
            'class_id' => $request->class_id[$x],
            'description' => $request->description[$x],
            'consumption' => $request->consumption[$x],
            'rate_per_unit' => $request->rate_per_unit[$x],
            'wastage' => $request->wastage[$x],
            'bom_qty' => $request->bom_qty[$x],
            'total_amount' => $request->total_amount[$x],
            );
            }
          SalesOrderFabricCostingDetailModel::insert($data2);
     }

   $class_ids = $request->input('class_ids');
    if(count($class_ids)>0)
    {
     for($x=0; $x<count($class_ids); $x++) {
        # code...
            $data3[]=array(
            'soc_code'=>$request->soc_code, 
            'soc_date'=>$request->soc_date, 
           'cost_type_id'=>$request->cost_type_id,
            'Ac_code'=>$request->Ac_code, 
            'sales_order_no'=>$request->sales_order_no,
            'season_id'=>$request->season_id,
            'currency_id'=>$request->currency_id, 
            'class_id' => $request->class_ids[$x],
            'description' => $request->descriptions[$x],
            'consumption' => $request->consumptions[$x],
            'rate_per_unit' => $request->rate_per_units[$x],
            'wastage' => $request->wastages[$x],
            'bom_qty' => $request->bom_qtys[$x],
            'total_amount' => $request->total_amounts[$x],
            );
            }
          SalesOrderSewingTrimsCostingDetailModel::insert($data3);
          
}
     
$class_idss = $request->input('class_idss');
    if(count($class_idss)>0)
    {
     for($x=0; $x<count($class_idss); $x++) {
        # code...
            $data4[]=array(
            'soc_code'=>$request->soc_code, 
            'soc_date'=>$request->soc_date, 
            'cost_type_id'=>$request->cost_type_id,
            'Ac_code'=>$request->Ac_code, 
            'sales_order_no'=>$request->sales_order_no,
            'season_id'=>$request->season_id,
            'currency_id'=>$request->currency_id, 
            'class_id' => $request->class_idss[$x],
            'description' => $request->descriptionss[$x],
            'consumption' => $request->consumptionss[$x],
            'rate_per_unit' => $request->rate_per_unitss[$x],
            'wastage' => $request->wastagess[$x],
            'bom_qty' => $request->bom_qtyss[$x],
            'total_amount' => $request->total_amountss[$x],
            );
            }
           SalesOrderPackingTrimsCostingDetailModel::insert($data4);
     } 
  return redirect()->route('SalesOrderCosting.index')->with('message', 'Data Saved Succesfully');  

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
        $InsertSizeData=DB::select('call AddSizeQtyFromSalesOrder("'.$sales_order_no.'")');
        $MasterdataList = DB::select("select *,(select sales_order_costing_master.production_value from sales_order_costing_master where sales_order_no='".$sales_order_no."') as production_value from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code='".$sales_order_no."'");
        return json_encode($MasterdataList);
    
    }   
     
public function GetItemData(Request $request)
{
    $item_code= $request->item_code;
    $data = DB::select(DB::raw("SELECT item_code, hsn_code, unit_id, item_image_path , item_description, quality_code
    from item_master where item_code='$request->item_code'")); 
    echo json_encode($data);

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
        'sub_style_master.substyle_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path','buyer_purchse_order_master.total_qty']);
        return view('saleCostingSheet',compact('SalesOrderCostingMaster'));  
} 
     
     
     public function costingProfitSheet()
     {
          //  DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
        ->join('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
        ->join('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
        ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
        ->where('sales_order_costing_master.delflag','=', '0')
        ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.Ac_name',
        'costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name',
        'sub_style_master.substyle_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path']);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('SalesCostingProfitSheet', compact('SalesOrderCostingMaster' ));
     }
     
     
    public function costingProfitSheet2()
     {
          //  DB::enableQueryLog();
        $SalesOrderCostingMaster = SalesOrderCostingMasterModel::join('usermaster', 'usermaster.userId', '=', 'sales_order_costing_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'sales_order_costing_master.Ac_code')
        ->join('season_master', 'season_master.season_id', '=', 'sales_order_costing_master.season_id')
        ->join('currency_master', 'currency_master.cur_id', '=', 'sales_order_costing_master.currency_id')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'sales_order_costing_master.cost_type_id')
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'sales_order_costing_master.substyle_id')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'sales_order_costing_master.mainstyle_id')
        ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'sales_order_costing_master.sales_order_no')
        ->where('sales_order_costing_master.delflag','=', '0')
        ->get(['sales_order_costing_master.*','usermaster.username','ledger_master.Ac_name',
        'costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name',
        'sub_style_master.substyle_name','main_style_master.mainstyle_name','buyer_purchse_order_master.style_img_path']);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('SalesCostingProfitSheet2', compact('SalesOrderCostingMaster' ));
     } 
     
     
     
    public function destroy($id)
    {
        DB::table('sales_order_costing_master')->where('soc_code', $id)->delete();
        DB::table('sales_order_fabric_costing_details')->where('soc_code',$id)->delete();
        DB::table('sales_order_packing_trims_costing_details')->where('soc_code', $id)->delete();
        DB::table('sales_order_sewing_trims_costing_details')->where('soc_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
