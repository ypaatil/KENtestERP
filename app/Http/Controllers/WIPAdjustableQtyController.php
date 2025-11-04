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
use App\Models\WIPAdjustableQtyModel;
use App\Models\WIPAdjustableQtyDetailModel;
use App\Models\WIPAdjustableQtySizeDetailModel;
use App\Models\LineModel;
use Session;
use DataTables;

class WIPAdjustableQtyController extends Controller
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
        ->where('form_id', '256')
        ->first();
        $vendorId=Session::get('vendorId');
        $user_type=Session::get('user_type');
     
        $WIPAdjustableQtyData = WIPAdjustableQtyModel::join('usermaster', 'usermaster.userId', '=', 'WIP_Adjustable_Qty.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'WIP_Adjustable_Qty.Ac_code', 'left outer')
                 ->join('ledger_master as L2', 'L2.Ac_code', '=', 'WIP_Adjustable_Qty.vendorId', 'left outer')
                ->where('WIP_Adjustable_Qty.delflag','=', '0')
                ->get(['WIP_Adjustable_Qty.*','usermaster.username','L1.Ac_name','L2.Ac_name as vendor_name']);
    
        return view('WIPAdjustableQtyList', compact('chekform','WIPAdjustableQtyData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='WIPAdjustableQty'");
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
      
        return view('WIPAdjustableQtyMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList','BuyerList',  'VendorWorkOrderList','Ledger',  'counter_number'));
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
        ->where('type','=','WIPAdjustableQty')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        
        
        $this->validate($request, [
             
                'WIPAQ_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'vendorId'=>'required',
                'mainstyle_id'=>'required',
        ]);
 
 
        $data1=array(
               
            'WIPAQ_code'=>$TrNo, 
            'WIPAQ_date'=>$request->WIPAQ_date, 
            'sales_order_no'=>$request->sales_order_no,
            'Ac_code'=>$request->Ac_code, 
            'vendorId'=>$request->vendorId,
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
            'c_code'=>$request->c_code
        );
     
        WIPAdjustableQtyModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='WIPAdjustableQty'");
    
        $color_id= $request->input('color_id');
        $data2 = array();
        $data3 = array();
        if(count($color_id)>0)
        {   
        
        for($x=0; $x<count($color_id); $x++) 
        {
             
            if($request->size_qty_total[$x]>0)
            {
                    $data2[]=array(
          
                    'WIPAQ_code'=>$TrNo,
                    'WIPAQ_date'=>$request->WIPAQ_date,
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
                  
                        'WIPAQ_code'=>$TrNo, 
                        'WIPAQ_date'=>$request->WIPAQ_date, 
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
              
              } // if loop avoid zero qty
            }
          if($data2 != "")
          {
                WIPAdjustableQtyDetailModel::insert($data2);
          }
          if($data3 != "")
          {
                WIPAdjustableQtySizeDetailModel::insert($data3);
          }
          
         
    }
    
        
   $InsertSizeData=DB::select('call AddSizeQtyFromWIPAdjustableQty("'.$TrNo.'")');
           
    return redirect()->route('WIPAdjustableQty.index')->with('message', 'Data Saved Succesfully');  
      
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
        $WIPAdjustableQtyMasterList = WIPAdjustableQtyModel::find($id);
        
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
         
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$WIPAdjustableQtyMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$WIPAdjustableQtyMasterList->sales_order_no)->DISTINCT()->get();
       
        
        //--------
        
         $vendorId=Session::get('vendorId');
       if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
                 $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
        ->whereNotIn('vendor_work_order_master.vw_code',function($query){
        $query->select('WIP_Adjustable_Qty.vw_code')->from('WIP_Adjustable_Qty');
        });
                $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
            
             $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
             ->where('vendor_work_order_master.vendorId',$vendorId)
            ->whereNotIn('vendor_work_order_master.vw_code',function($query){
            $query->select('WIP_Adjustable_Qty.vw_code')->from('WIP_Adjustable_Qty');
            });
              $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
        $WIPAdjustableQtyDetailList =WIPAdjustableQtyDetailModel::where('WIP_Adjustable_Qty_detail.WIPAQ_code','=', $WIPAdjustableQtyMasterList->WIPAQ_code)->get();
        $S2=WIPAdjustableQtyModel::select('vw_code','sales_order_no')->where('vw_code',$WIPAdjustableQtyMasterList->vw_code);
        $VendorWorkOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($WIPAdjustableQtyMasterList->sales_order_no);
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
              color_master.color_id=vendor_work_order_size_detail.color_id where vw_code='".$WIPAdjustableQtyMasterList->vw_code."'
              group by vendor_work_order_size_detail.color_id");
                
        return view('WIPAdjustableQtyMasterEdit',compact('WIPAdjustableQtyDetailList','ColorList' ,'BuyerList', 'MasterdataList','SizeDetailList','WIPAdjustableQtyMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger' ));
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
            'WIPAQ_date'=> 'required', 
            'Ac_code'=> 'required', 
            'sales_order_no'=> 'required'
        ]);
 
  
        $data1=array(
               
            'WIPAQ_code'=>$request->WIPAQ_code, 
            'WIPAQ_date'=>$request->WIPAQ_date, 
            'sales_order_no'=>$request->sales_order_no,
            'Ac_code'=>$request->Ac_code, 
            'vendorId'=>$request->vendorId,
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
             
        );
       
        $WIPAdjustableQtyList = WIPAdjustableQtyModel::findOrFail($request->WIPAQ_code); 
    
        $WIPAdjustableQtyList->fill($data1)->save();
    
     
        DB::table('WIP_Adjustable_Qty_size_detail')->where('WIPAQ_code', $request->WIPAQ_code)->delete();
        DB::table('WIP_Adjustable_Qty_size_detail2')->where('WIPAQ_code', $request->WIPAQ_code)->delete();
        DB::table('WIP_Adjustable_Qty_detail')->where('WIPAQ_code', $request->WIPAQ_code)->delete();
        
        $data2 = array();
        $data3 = array();
        $color_id= $request->input('color_id');
        
        if(count($color_id)>0)
        {   
        
            for($x=0; $x<count($color_id); $x++) 
            {
                if($request->size_qty_total[$x]>0)
                {
                        $data2[]=array(
                  
                            'WIPAQ_code'=>$request->WIPAQ_code,
                            'WIPAQ_date'=>$request->WIPAQ_date,
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
                      
                            'WIPAQ_code'=>$request->WIPAQ_code,
                            'WIPAQ_date'=>$request->WIPAQ_date, 
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
                            'vendor_rate'=>$request->vendor_rate);
                      
                      } 
            } 
            if($data2 != "")
            {
                WIPAdjustableQtyDetailModel::insert($data2);
            }
            if($data3 != "")
            {
                WIPAdjustableQtySizeDetailModel::insert($data3);
            }
                  
        }     
        
        $InsertSizeData=DB::select('call AddSizeQtyFromWIPAdjustableQty("'.$request->WIPAQ_code.'")');
               
        return redirect()->route('WIPAdjustableQty.index')->with('message', 'Data Saved Succesfully'); 
    }
    
       
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
      

     
    public function destroy($id)
    {
        DB::table('WIP_Adjustable_Qty')->where('WIPAQ_code', $id)->delete();
        DB::table('WIP_Adjustable_Qty_size_detail')->where('WIPAQ_code', $id)->delete();
        DB::table('WIP_Adjustable_Qty_size_detail2')->where('WIPAQ_code', $id)->delete();
        DB::table('WIP_Adjustable_Qty_detail')->where('WIPAQ_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
       
}
