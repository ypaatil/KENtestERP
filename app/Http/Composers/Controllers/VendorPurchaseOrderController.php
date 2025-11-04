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
use Session;

class VendorPurchaseOrderController extends Controller
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
        ->where('form_id', '99')
        ->first();
        

        //   DB::enableQueryLog();
        $VendorPurchaseOrderList = VendorPurchaseOrderModel:: 
         join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_purchase_order_master.Ac_code', 'left outer')
        ->join('ledger_master as L2', 'L2.Ac_code', '=', 'vendor_purchase_order_master.vendorId', 'left outer')
        ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_purchase_order_master.sales_order_no', 'left outer')
       ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_purchase_order_master.mainstyle_id', 'left outer')
        ->join('process_master', 'process_master.process_id', '=', 'vendor_purchase_order_master.process_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_purchase_order_master.cost_type_id', 'left outer')
        ->where('vendor_purchase_order_master.delflag','=', '0')
        ->get(['vendor_purchase_order_master.*','process_master.process_name', 'buyer_purchse_order_master.po_code','main_style_master.mainstyle_name','L2.Ac_name as vendorName','ledger_master.Ac_name','costing_type_master.cost_type_name']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    
    
    
        return view('VendorPurchaseOrderList', compact('VendorPurchaseOrderList','chekform'));
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
        $ProcessList= DB::table('process_master')->get();
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
         'final_bom_qty'=>$request->final_bom_qty
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
    
    for($x=0; $x<count($item_code); $x++) 
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
            'color_id' => $request->color_id[$x],
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
          for($x=0; $x<count($item_codesx); $x++) 
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
     for($x=0; $x<count($item_codess); $x++) {
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
            'description' => $request->descriptionss[$x],
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
    return redirect()->route('VendorPurchaseOrder.index')->with('message', 'Data Saved Succesfully');  
      
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        
        $UnitList = UnitModel::where('delflag','=', '0')->get();
       // $ItemList = ItemModel::where('delflag','=', '0')->where('class_id','=', $codefetch->class_id)->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $Ledger2 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList5= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ItemList3 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ProcessList= DB::table('process_master')->get();
        $ClassList3 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '3')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
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
        $PackingTrimsList = VendorPurchaseOrderPackingTrimsDetailModel::where('vendor_purchase_order_packing_trims_details.vpo_code','=', $VendorPurchaseOrderMasterList->vpo_code)->get();
        
        
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
                $VendorProcessDataList = DB::select("SELECT  1 as process_id,	cut_panel_grn_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from 	cut_panel_grn_size_detail 
                inner join color_master on color_master.color_id=	cut_panel_grn_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by 	cut_panel_grn_size_detail.color_id");
                
        //          $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
                
        }
        elseif($VendorPurchaseOrderMasterList->process_id==2)
        {
                $VendorProcessDataList = DB::select("SELECT  2 as process_id,		finishing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from 		finishing_inhouse_size_detail 
                inner join color_master on color_master.color_id=		finishing_inhouse_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by 		finishing_inhouse_size_detail.color_id");
        }
        elseif($VendorPurchaseOrderMasterList->process_id==3)
        {
                $VendorProcessDataList = DB::select("SELECT  3 as process_id,	packing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
                sum(size_qty_total) as size_qty_total  from 	packing_inhouse_size_detail 
                inner join color_master on color_master.color_id=	packing_inhouse_size_detail.color_id 
                where vpo_code='".$VendorPurchaseOrderMasterList->vpo_code."' group by 	packing_inhouse_size_detail.color_id");
        }
        
        
        
        
        return view('VendorPurchaseOrderEdit',compact('VendorPurchaseOrderDetailList','Ledger2','VendorProcessDataList','PackingTrimsList', 'ProcessList', 'ColorList','TrimFabricList',  'MasterdataList','SizeDetailList','VendorPurchaseOrderMasterList','FabricList', 'UnitList','ClassList','ClassList3', 'ItemList','ItemList3','ItemList5', 'MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
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
         'final_bom_qty'=>$request->final_bom_qty
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
    if(count($color_id)>0)
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
    
    
    $item_code= $request->input('item_code');
    if(count($item_code)>0)
    {   
    
    for($x=0; $x<count($item_code); $x++) 
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
            'item_code' => $request->item_code[$x],
            'class_id' => $request->class_id[$x],
            'description' => $request->description[$x],
            'color_id' => $request->color_id[$x],
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
        
    if(isset($item_codesx) &&   count($item_codesx)>0)
    {        
          for($x=0; $x<count($item_codesx); $x++) 
    {
        # code...
 
            $data5[]=array(
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
               
    } // if loop avoid zero qty
               
               VendorPurchaseOrderTrimFabricDetailModel::insert($data5);
            }      
           
           
    $item_codess = $request->input('item_codess');
    if(count($item_codess)>0)
    {
     for($x=0; $x<count($item_codess); $x++) {
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
            'item_code' => $request->item_codess[$x],
            'class_id' => $request->class_idss[$x],
            'description' => $request->descriptionss[$x],
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
            
    $InsertSizeData=DB::select('call AddSizeQtyFromVendorPurchaseOrder("'.$request->vpo_code.'")');
           
           
     return redirect()->route('VendorPurchaseOrder.index')->with('message', 'Data Saved Succesfully'); 
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
                   <th>Action</th>
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
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required disabled>
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
          if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.' <input style="width:80px; float:left;" max="'.$s1.'" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.' <input style="width:80px; float:left;" max="'.$s2.'" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.' <input style="width:80px; float:left;" max="'.$s3.'" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.' <input style="width:80px; float:left;" max="'.$s4.'" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.' <input style="width:80px; float:left;" max="'.$s5.'" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.' <input style="width:80px; float:left;" max="'.$s6.'" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.' <input style="width:80px; float:left;" max="'.$s7.'" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.' <input style="width:80px; float:left;" max="'.$s8.'" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.' <input style="width:80px; float:left;" max="'.$s9.'" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.' <input style="width:80px; float:left;" max="'.$s10.'" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.' <input style="width:80px; float:left;" max="'.$s11.'" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.' <input style="width:80px; float:left;" max="'.$s12.'" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.' <input style="width:80px; float:left;" max="'.$s13.'" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.' <input style="width:80px; float:left;" max="'.$s14.'" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.' <input style="width:80px; float:left;" max="'.$s15.'" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.' <input style="width:80px; float:left;" max="'.$s16.'" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.' <input style="width:80px; float:left;" max="'.$s17.'" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.' <input style="width:80px; float:left;" max="'.$s18.'" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.' <input style="width:80px; float:left;" max="'.$s19.'" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.' <input style="width:80px; float:left;" max="'.$s20.'" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
       
              
             
         
          $html.='<td>'.($total_qty-$List->size_qty_total).' 
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
          
          </td>';
            $html.='<td>  <input type="button" name="size_btn" class="size_btn btn-primary" id="size_btn" value="Calculate" ></td>';
          
          
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
     
    //  $total_consumption= ($codefetch->consumption)+(($codefetch->consumption)*($codefetch->wastage/100));
    //  $bom_qty=round(($total_consumption*$size_qty_total),2);
                
     $total_consumption= ($codefetch->consumption);
     $bom_qty=round(($codefetch->consumption*$size_qty_total),2);
    
    
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
 
<td><input type="number" step="0.01" readOnly   name="consumption[]" value="'.$codefetch->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
  
<td> <select name="unit_id[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
<option value="">--Unit--</option>';
foreach($UnitList as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $codefetch->unit_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td> 

<td><input type="number" step="0.01" max="5" min="0" class="WASTAGE"  name="wastage[]" value="'.$codefetch->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
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
                <td><input type="number" step="0.01" readOnly   name="consumptions[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_ids[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="0.01" max="5" min="0" class="WASTAGE1"   name="wastages[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
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
                // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                // $bom_qty=round(($total_consumption*$qty),2);
                
                $total_consumption= ($rowsew->consumption);
                $bom_qty=round(($rowsew->consumption*$qty),2);
             
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                $html.=' 
                <td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text"  readOnly  name="descriptionss[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;" required /></td> 
                <td><input type="number" step="0.01"  readOnly  name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="0.01" max="5" min="0" class="WASTAGE2"  name="wastagess[]" value="'.$rowsew->wastage.'" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtyss[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="text"  name="bom_qtyss1[]" value="'.$qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;" required readOnly />
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
     
    public function destroy($id)
    {
        DB::table('vendor_purchase_order_master')->where('vpo_code', $id)->delete();
        DB::table('vendor_purchase_order_fabric_details')->where('vpo_code', $id)->delete();
        DB::table('vendor_purchase_order_size_detail')->where('vpo_code', $id)->delete();
        DB::table('vendor_purchase_order_detail')->where('vpo_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
}
