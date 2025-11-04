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
use Session;

class BOMController extends Controller
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
        ->where('form_id', '90')
        ->first();
        

        //   DB::enableQueryLog();
        $BOMList = BOMMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
         
        ->where('bom_master.delflag','=', '0')
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name', 'season_master.season_name' ]);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('BOMMasterList', compact('BOMList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='BOM'");
        // $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
         $ItemList4= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get(); 
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
        
        $SalesOrderList= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
         ->whereRaw("sales_order_costing_master.sales_order_no NOT IN(select bom_master.sales_order_no from bom_master where bom_master.sales_order_no=sales_order_costing_master.sales_order_no)")
        ->get();
        
        
        return view('BOMMaster',compact('UnitList','ClassList','ClassList2','ClassList3','ItemList2','ItemList3','ItemList4',  'MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList', 'counter_number'));

         
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

    $class_id= $request->input('class_id');
    if(count($class_id)>0)
    {
    
    for($x=0; $x<count($class_id); $x++) {
        # code...
 
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
            'color_id' => $request->color_id[$x],
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
     for($x=0; $x<count($class_ids); $x++) {
        # code...
 
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
        # code...
 
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
        # code...
       
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

       
    return redirect()->route('BOM.index')->with('message', 'Data Saved Succesfully');  
      
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
       
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        //$ColorList = ColorModel::where('color_master.delflag','=', '1')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $BOMMasterList = BOMMasterModel::find($id);
        // 
        
        $FabricList = BOMFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id',
        'consumption', 'unit_id', 'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount','remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
         purchaseorder_detail.item_code=bom_fabric_details.item_code and FIND_IN_SET(bom_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"))
        ->where('bom_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
        
        // 
        
        // DB::enableQueryLog(); 
        
        $TrimFabricList = BOMTrimFabricDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
        'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where 
         purchaseorder_detail.item_code=bom_trim_fabric_details.item_code and FIND_IN_SET(bom_trim_fabric_details.bom_code, purchaseorder_detail.bom_code)) as item_count"))
        ->where('bom_trim_fabric_details.bom_code','=', $BOMMasterList->bom_code)->get();
        
        // $query = DB::getQueryLog();
        //     $query = end($query);
        //     dd($query);
        
        
        $SewingTrimsList = BOMSewingTrimsDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
        'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  
       purchaseorder_detail.item_code=bom_sewing_trims_details.item_code and FIND_IN_SET(bom_sewing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"))
            ->where('bom_sewing_trims_details.bom_code','=', $BOMMasterList->bom_code)->get();
        
        $PackingTrimsList = BOMPackingTrimsDetailModel::select('item_code', 'class_id', 'description', 'color_id', 'size_array', 'consumption', 'unit_id',
        'rate_per_unit', 'wastage', 'bom_qty','item_qty', 'total_amount' ,'remark',
        DB::raw("(select ifnull(count(item_code),0) from purchaseorder_detail where  
          purchaseorder_detail.item_code=bom_packing_trims_details.item_code and FIND_IN_SET(bom_packing_trims_details.bom_code, purchaseorder_detail.bom_code)) as item_count"))
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
        ->where('tr_code','=',$BOMMasterList->sales_order_no)->DISTINCT()->get();
        
        $ItemList4= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
         
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
          
        return view('BOMMasterEdit',compact('ColorList','BuyerPurchaseOrderMasterList', 'ApproveMasterList', 'MasterdataList','SizeDetailList','BOMMasterList','FabricList','TrimFabricList','ItemList4',  'SewingTrimsList','PackingTrimsList','UnitList','ClassList','ClassList2','ClassList3','ItemList1','ItemList2','ItemList3','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
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
             
                 'bom_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'total_cost_value'=> 'required',
                'fabric_value'=> 'required',
                'sewing_trims_value'=> 'required',
                'packing_trims_value'=> 'required',
                
             
              
    ]);
 
 
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
        'fabric_value'=>$request->fabric_value, 
        'sewing_trims_value'=>$request->sewing_trims_value,
        'packing_trims_value'=>$request->packing_trims_value, 
        'total_cost_value'=>$request->total_cost_value,
        'narration'=>$request->narration,
        'is_approved'=>$request->is_approved,
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

DB::table('bom_fabric_details')->where('bom_code', $request->bom_code)->delete();
DB::table('bom_packing_trims_details')->where('bom_code', $request->bom_code)->delete();
DB::table('bom_sewing_trims_details')->where('bom_code', $request->bom_code)->delete();
DB::table('bom_trim_fabric_details')->where('bom_code', $request->bom_code)->delete(); 


   $class_id= $request->input('class_id');
    if(count($class_id)>0)
    {
    
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
            'color_id' => $request->color_id[$x],
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
     for($x=0; $x<count($class_ids); $x++) {
        # code...
       
 
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
            }
          BOMSewingTrimsDetailModel::insert($data3);
    }
     
     
     
      $class_idsx = $request->input('class_idsx');
    if(count($class_idsx)>0)
    {
     for($x=0; $x<count($class_idsx); $x++) {
        # code...
 
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
     
     
      
     
     
     
     
     
     
     
$class_idss = $request->input('class_idss');
    if(count($class_idss)>0)
    {
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
            }
          BOMPackingTrimsDetailModel::insert($data4);
    } 

       
     return redirect()->route('BOM.index')->with('message', 'Data Saved Succesfully');  
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
    $codefetch = DB::table('item_master')->select("class_id","unit_id")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;

 
    // DB::enableQueryLog();
    $data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`, ".$Unit_id." as unit_id, ".$Class_id." as class_id,
     (select sum(size_qty) from buyer_purchase_order_size_detail where   
     tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))  as bom_qty,
     `rate_per_unit`, `wastage`, `total_amount` from sales_order_sewing_trims_costing_details
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
//print_r($item_code);
    $codefetch = DB::table('item_master')->select("class_id","unit_id")
    ->where('item_code','=',$request->item_code)
    ->first();
    $Class_id=$codefetch->class_id;
    $Unit_id=$codefetch->unit_id;

 
    // DB::enableQueryLog();
    $data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`, ".$Unit_id." as unit_id, ".$Class_id." as class_id,
     (select sum(size_qty) from buyer_purchase_order_size_detail where   
     tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))  as bom_qty,
     `rate_per_unit`, `wastage`, `total_amount` from sales_order_fabric_costing_details
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
    
    
    $data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`,".$Unit_id." as unit_id,".$Class_id." as class_id,
     ((select sum(size_qty_total) from buyer_purchase_order_detail where item_code=$item_code and 
     tr_code='$sales_order_no') ) as bom_qty , 
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
 tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id)) ) as bom_qty,
 `rate_per_unit`, `wastage`, `total_amount` from sales_order_packing_trims_costing_details
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

          if(isset($row->s1)) { $html.='<td>'.$row->s1.'</td>';}
          if(isset($row->s2)) { $html.='<td>'.$row->s2.'</td>';}
          if(isset($row->s3)) { $html.='<td>'.$row->s3.'</td>';}
          if(isset($row->s4)) { $html.='<td>'.$row->s4.'</td>';}
          if(isset($row->s5)) { $html.='<td>'.$row->s5.'</td>';}
          if(isset($row->s6)) { $html.='<td>'.$row->s6.'</td>';}
          if(isset($row->s7)) { $html.='<td>'.$row->s7.'</td>';}
          if(isset($row->s8)) { $html.='<td>'.$row->s8.'</td>';}
          if(isset($row->s9)) { $html.='<td>'.$row->s9.'</td>';}
          if(isset($row->s10)) { $html.='<td>'.$row->s10.'</td>';}
          if(isset($row->s11)) { $html.='<td>'.$row->s11.'</td>';}
          if(isset($row->s12)) { $html.='<td>'.$row->s12.'</td>';}
          if(isset($row->s13)) { $html.='<td>'.$row->s13.'</td>';}
          if(isset($row->s14)) { $html.='<td>'.$row->s14.'</td>';}
          if(isset($row->s15)) { $html.='<td>'.$row->s15.'</td>';}
          if(isset($row->s16)) { $html.='<td>'.$row->s16.'</td>';}
          if(isset($row->s17)) { $html.='<td>'.$row->s17.'</td>';}
          if(isset($row->s18)) { $html.='<td>'.$row->s18.'</td>';}
          if(isset($row->s19)) { $html.='<td>'.$row->s19.'</td>';}
          if(isset($row->s20)) { $html.='<td>'.$row->s20.'</td>';}
          $html.='<td>'.$row->size_qty_total.'</td>';
          $html.='</tr>';

          $no=$no+1;
        }
        
        
       $html.=' <tr  style="background-color:#eee; text-align:center; border: 1px solid;">
  
  <th></th>

<th>Total</th>';

 
    $SizeWsList=explode(',', $BuyerPurchaseOrderMasterList->sz_ws_total);
 
 foreach($SizeWsList  as $sztotal)
{
    $html.='<th style="text-align:right;">'.$sztotal.'</th>';

}
$html.='<th>'.$BuyerPurchaseOrderMasterList->total_qty.'</th>

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
{ //  print_r($request->item_code);
        //  DB::enableQueryLog();  
     
     $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id',
     'color_name')->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('item_code','=',$request->item_code)->where('tr_code','=',$request->tr_code)->DISTINCT()->get();
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     $data='';
     foreach($ColorList as $row)
     {
       $data=$data.$row->color_name.', ';
     }
     //$Colors->color = $data;
    // $html='<input type="text"  name="colors[]" value="'.rtrim($data, ',').'" id="colors" style="width:200px; height:30px;" required />';
    
   $Colors= array (
      "color_name"=> rtrim($data, ',') 
    );
    echo json_encode($Colors);
}


public function GetClassItemList(Request $request)
{
    $ItemList = DB::table('item_master')->select('item_master.item_code', 'item_master.item_name')
    ->where('class_id','=',$request->class_id)->get();
    if (!$request->class_id)
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


public function GetItemList(Request $request)
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
    
     
     
    public function destroy($id)
    {
        DB::table('bom_master')->where('bom_code', $id)->delete();
        DB::table('bom_packing_trims_details')->where('bom_code',$id)->delete();
        DB::table('bom_sewing_trims_details')->where('bom_code', $id)->delete();
        DB::table('bom_fabric_details')->where('bom_code', $id)->delete();
         DB::table('bom_trim_fabric_details')->where('bom_code', $id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
}
