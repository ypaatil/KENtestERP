<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\BOMMasterModel;
use App\Models\LedgerModel;
use App\Models\SizeDetailModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\CurrencyModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
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
use App\Models\OutwardForPackingMasterModel;
use App\Models\OutwardForPackingDetailModel;
use App\Models\OutwardForPackingSizeDetailModel; 
use App\Models\StitchingInhouseMasterModel; 
use Session;
use DataTables;

class OutwardForPackingMasterController extends Controller
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
        ->where('form_id', '114')
        ->first();
         
        $OutwardForPackingMasterList = OutwardForPackingMasterModel::leftjoin('usermaster', 'usermaster.userId', '=', 'outward_for_packing_master.userId')
            ->leftJoin('ledger_master as L1', 'L1.Ac_code', '=', 'outward_for_packing_master.Ac_code')
            ->leftJoin('ledger_master as L2', 'L2.Ac_code', '=', 'outward_for_packing_master.vendorId')
            ->where('outward_for_packing_master.delflag', '=', '0')
            ->where(function ($query) {
                $query->where('outward_for_packing_master.userId', '=', Session::get('userId'))
                      ->orWhere('usermaster.user_type', '=', 6);
            })
            ->get([
                'outward_for_packing_master.*',
                'usermaster.username',
                'L1.Ac_name',
                'L2.Ac_name as vendor_name'
            ]);



         
        if ($request->ajax()) 
        {
            return Datatables::of($OutwardForPackingMasterList)
            ->addIndexColumn()
            ->addColumn('chk', function ($row) 
            {
                $chk = '<input type="checkbox" class="chk" name="chk[]" ofp_code="'.$row->ofp_code.'" style="width:40px; height: 29px;" />';
                return $chk;
            })
            ->addColumn('ofp_code1',function ($row) 
            {
                 $ofp_codeData =substr($row->ofp_code,4,15);
                 return $ofp_codeData;
            }) 
            ->addColumn('action1', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="OutwardForPackingPrint/'.$row->ofp_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action2', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('OutwardForPacking.edit', $row->ofp_code).'" >
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
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->ofp_code.'"  data-route="'.route('OutwardForPacking.destroy', $row->ofp_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['chk', 'action1','action2','action3'])
    
            ->make(true);
        }
        return view('OutwardForPackingMasterList', compact('OutwardForPackingMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='OutwardForPacking'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ToSent = DB::table('location_master')->where('delflag','=', '0')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $SalesOrderList = DB::SELECT("SELECT * FROM buyer_purchse_order_master WHERE delflag=0");  
        return view('OutwardForPackingMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList', 'SalesOrderList','Ledger',  'counter_number','ToSent'));
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
        ->where('type','=','OutwardForPacking')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        
        
        $this->validate($request, [
             
                'ofp_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
				'vendorId'=>'required',
				'mainstyle_id'=>'required',
               
        ]);
     
     
        $data1=array(
               
            'ofp_code'=>$TrNo, 
            'ofp_date'=>$request->ofp_date, 
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
            'vendor_rate'=>0,
            'vendor_amount'=>0,
            'narration'=>$request->narration,
            'userId'=>$request->userId,
            'delflag'=>'0',
            'c_code'=>$request->c_code,
            'sent_to'=>$request->sent_to, 
        );
     
        OutwardForPackingMasterModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='OutwardForPacking'");
    
        $color_id= $request->input('color_id');
        if(count($color_id)>0)
        {   
        
        for($x=0; $x<count($color_id); $x++) {
            # code...
          if($request->size_qty_total[$x]>0)
                  {
                        $data2=array(
              
                        'ofp_code'=>$TrNo,
                        'ofp_date'=>$request->ofp_date,
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
     
                          $data3=array(
                      
                            'ofp_code'=>$TrNo, 
                            'ofp_date'=>$request->ofp_date, 
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
                  
                              OutwardForPackingDetailModel::insert($data2);
                              OutwardForPackingSizeDetailModel::insert($data3);
                  } // if loop avoid zero qty
                }
              
             
        }
        $InsertSizeData=DB::select('call AddSizeQtyFromOutwardForPacking("'.$TrNo.'")');
           
        return redirect()->route('OutwardForPacking.index')->with('message', 'Data Saved Succesfully');  
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

    public function VPPrint($vw_code)
    {
       $BOMList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_work_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_work_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_work_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_work_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_work_order_master.fg_id', 'left outer')   
        ->where('vendor_work_order_master.delflag','=', '0')
        ->where('vendor_work_order_master.vw_code','=', $vw_code)
        ->get(['vendor_work_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
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
        $OutwardForPackingMasterList = OutwardForPackingMasterModel::find($id);
        
        //  $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
          
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('buyer_purchase_order_detail.tr_code','=',$OutwardForPackingMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
            ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
            ->where('buyer_purchase_order_detail.tr_code','=',$OutwardForPackingMasterList->sales_order_no)->DISTINCT()->get();
          
          $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$OutwardForPackingMasterList->sales_order_no)->first();
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
                ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                ->where('tr_code','=',$OutwardForPackingMasterList->sales_order_no)->DISTINCT()->get();
            
          $ToSent = DB::table('location_master')->where('delflag','=', '0')->get();
         
          $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
          $sizes='';
          $sizes1='';
          $no=1;
           //DB::enableQueryLog();  
          $OutwardForPackingDetailList = DB::select("SELECT outward_for_packing_detail.size_qty_array ,outward_for_packing_detail.size_array,  
          outward_for_packing_detail.item_code, outward_for_packing_detail.color_id, color_name,
          outward_for_packing_detail.size_qty_total from outward_for_packing_detail 
          inner join color_master on color_master.color_id=outward_for_packing_detail.color_id  
          where outward_for_packing_detail.ofp_code='".$OutwardForPackingMasterList->ofp_code."'
          group by outward_for_packing_detail.color_id order by outward_for_packing_detail.color_id ASC");
        // dd(DB::getQueryLog());
        
        $S1= VendorWorkOrderModel::select('vendor_work_order_master.vw_code','vendor_work_order_master.sales_order_no')
        ->whereNotIn('vendor_work_order_master.vw_code',function($query){
        $query->select('outward_for_packing_master.vw_code')->from('outward_for_packing_master');
        });
        $S2=OutwardForPackingMasterModel::select('vw_code','sales_order_no')->where('ofp_code',$OutwardForPackingMasterList->ofp_code) ;
        $VendorWorkOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($OutwardForPackingMasterList->sales_order_no);
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
            color_master.color_id=vendor_work_order_size_detail.color_id where vw_code='".$OutwardForPackingMasterList->vw_code."'
            group by vendor_work_order_size_detail.color_id");
        
        
        $VendorWorkDataList = DB::select("SELECT vendor_work_order_size_detail.color_id, color_name, ".$sizes.", 
            sum(size_qty_total) as size_qty_total  from vendor_work_order_size_detail 
            inner join color_master on color_master.color_id=vendor_work_order_size_detail.color_id 
            where vw_code='".$OutwardForPackingMasterList->vw_code."' group by 	vendor_work_order_size_detail.color_id"); 
         
         
        $inwardForPackingList = DB::select("SELECT inward_for_packing_size_detail.item_code, inward_for_packing_size_detail.color_id, color_name, ".$sizes.", 
          sum(size_qty_total) as size_qty_total from inward_for_packing_size_detail inner join color_master on 
          color_master.color_id=inward_for_packing_size_detail.color_id where ofp_code='".$OutwardForPackingMasterList->ofp_code."'
          group by inward_for_packing_size_detail.color_id order by inward_for_packing_size_detail.color_id ASC");
        
        return view('OutwardForPackingMasterEdit',compact('OutwardForPackingDetailList','ColorList','VendorWorkDataList','inwardForPackingList', 'MasterdataList','SizeDetailList','OutwardForPackingMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','VendorWorkOrderList','Ledger','ToSent' ));
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
             
                'ofp_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
               
        ]);
 
  
    $data1=array(
           
        'ofp_code'=>$request->ofp_code, 
        'ofp_date'=>$request->ofp_date, 
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
        'vendor_rate'=>0,
        'vendor_amount'=>0,
        'narration'=>$request->narration,
       'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
        'sent_to'=>$request->sent_to,
         
    );
//   DB::enableQueryLog();   
$OutwardForPackingList = OutwardForPackingMasterModel::findOrFail($request->ofp_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$OutwardForPackingList->fill($data1)->save();

 
DB::table('outward_for_packing_size_detail')->where('ofp_code', $request->input('ofp_code'))->delete();
DB::table('outward_for_packing_size_detail2')->where('ofp_code', $request->input('ofp_code'))->delete();
DB::table('outward_for_packing_detail')->where('ofp_code', $request->input('ofp_code'))->delete();
 
 $color_id= $request->color_id;
 
    for($x=0; $x<count($color_id); $x++) 
    { 
            $data2=array(
  
            'ofp_code'=>$request->ofp_code,
            'ofp_date'=>$request->ofp_date,
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
      //DB::enableQueryLog();
              OutwardForPackingDetailModel::insert($data2);
        //  dd(DB::getQueryLog());
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
          
                'ofp_code'=>$request->ofp_code,
                'ofp_date'=>$request->ofp_date, 
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
                
                OutwardForPackingSizeDetailModel::insert($data3);
            }
         
          $InsertSizeData=DB::select('call AddSizeQtyFromOutwardForPacking("'.$request->ofp_code.'")');
           
           
          return redirect()->route('OutwardForPacking.index')->with('message', 'Data Saved Succesfully'); 
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
     
    public function getOutwardForPackingDetails(Request $request)
    { 
        $ofp_code= $request->input('ofp_code');
        $MasterdataList = DB::select("select Ac_code,sales_order_no, vendorId, mainstyle_id, substyle_id, fg_id, style_no, style_description from outward_for_packing_master where outward_for_packing_master.delflag=0 and ofp_code='".$ofp_code."'");
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
     (select sum(size_qty) from buyer_work_order_size_detail where   
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
     (select sum(size_qty_total) from buyer_work_order_detail where item_code=$item_code and 
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
 ((select sum(size_qty) from buyer_work_order_size_detail where   
 tr_code='$sales_order_no' and color_id in ($color_id) and size_id in ($size_id))*bom_qty) as bom_qty,
 `rate_per_unit`, `wastage`, `total_amount` from sales_order_packing_trims_costing_details
where  class_id='$Class_id' and sales_order_no='$sales_order_no'")); 
 
//  $query = DB::getQueryLog();
//  $query = end($query);
//  dd($query);
echo json_encode($data);
 
}

  
  public function vpo_GetPackingPOQty(Request $request)
  {
      // vpo_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by vpo_
      
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
      $List = DB::select("SELECT outward_for_packing_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from outward_for_packing_size_detail inner join color_master on 
      color_master.color_id=outward_for_packing_size_detail.color_id where 
      outward_for_packing_size_detail.vw_code='".$request->vw_code."' and
      outward_for_packing_size_detail.color_id='".$row->color_id."'
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
        
        
        
            if(isset($row->s1)) { $html.='<td>'.$s1.' <input style="width:80px; float:left;"  name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) { $html.='<td>'.$s2.' <input style="width:80px; float:left;"    name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) { $html.='<td>'.$s3.' <input style="width:80px; float:left;"    name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) { $html.='<td>'.$s4.' <input style="width:80px; float:left;"    name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) { $html.='<td>'.$s5.' <input style="width:80px; float:left;"    name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) { $html.='<td>'.$s6.' <input style="width:80px; float:left;"    name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) { $html.='<td>'.$s7.' <input style="width:80px; float:left;"    name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) { $html.='<td>'.$s8.' <input style="width:80px; float:left;"    name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) { $html.='<td>'.$s9.' <input style="width:80px; float:left;"    name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) { $html.='<td>'.$s10.' <input style="width:80px; float:left;"  name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) { $html.='<td>'.$s11.' <input style="width:80px; float:left;"  name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) { $html.='<td>'.$s12.' <input style="width:80px; float:left;"  name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) { $html.='<td>'.$s13.' <input style="width:80px; float:left;"  name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) { $html.='<td>'.$s14.' <input style="width:80px; float:left;"  name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) { $html.='<td>'.$s15.' <input style="width:80px; float:left;"  name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) { $html.='<td>'.$s16.' <input style="width:80px; float:left;"  name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) { $html.='<td>'.$s17.' <input style="width:80px; float:left;"  name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) { $html.='<td>'.$s18.' <input style="width:80px; float:left;"  name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) { $html.='<td>'.$s19.' <input style="width:80px; float:left;"  name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) { $html.='<td>'.$s20.' <input style="width:80px; float:left;"  name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
        
        
        
        
          $html.='<td>'.($row->size_qty_total-$List[0]->size_qty_total).' 
          
          <input type="number" name="size_qty_total[]" max="'.($row->size_qty_total-$List[0]->size_qty_total).'" min="0" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$VendorWorkOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
           $html.='</tr>';

          $no=$no+1;
        }
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
   
   public function vw_GetPackingPOQty(Request $request)
   {
      // vpo_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by vpo_
      
      $VendorWorkOrderMasterList = VendorWorkOrderModel::find($request->sales_order_no);
    //   DB::enableQueryLog();  
      $VendorWorkOrderDetailList = VendorWorkOrderDetailModel::where('sales_order_no',$request->sales_order_no)->first();
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$request->sales_order_no)->first(); 
  
      $ColorList = DB::table('vendor_work_order_detail')->select('vendor_work_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'vendor_work_order_detail.color_id', 'left outer')
        ->where('sales_order_no','=',$request->sales_order_no)->DISTINCT()->get();
        
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $sizes1='';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'sum(stitching_inhouse_size_detail.s'.$no.') as s'.$no.',';
          $sizes1=$sizes1.'sum(stitching_inhouse_size_detail.s'.$no.') as stitch'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
      $sizes1=rtrim($sizes1,',');
        //   DB::enableQueryLog();  
     $MasterdataList = DB::select("SELECT stitching_inhouse_size_detail.item_code, stitching_inhouse_size_detail.color_id, color_name, ".$sizes.", 
          sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
          color_master.color_id=stitching_inhouse_size_detail.color_id where sales_order_no='".$request->sales_order_no."'
          group by stitching_inhouse_size_detail.color_id order by stitching_inhouse_size_detail.color_id");
           
 
      $html = '';
      $html .= '  <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
              <thead>
              <tr>
                    <th>Sr No</th>
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
        if(count($MasterdataList) > 0)
        {
                foreach ($MasterdataList as $row) 
                {
                      $html .='<tr>';
                      $html .='
                      <td>'.$no.'</td>';
                       
                    $html.=' <td>
                    <input  name="item_codef[]"  type="hidden" id="item_code" value="'.$row->item_code.'" required />
                    
                    <select name="color_id[]" class="select2-select"  id="color_ids0" style="width:250px; height:30px;" disabled>
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
                    
                    $List = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizex.", 
                      sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
                      color_master.color_id=stitching_inhouse_size_detail.color_id where 
                      stitching_inhouse_size_detail.sales_order_no='".$request->sales_order_no."' and
                      stitching_inhouse_size_detail.color_id='".$row->color_id."'
                       ");
                
                     
                    $List1 = DB::select("SELECT outward_for_packing_size_detail.color_id, color_name, ".$sizex.", 
                          sum(size_qty_total) as size_qty_total from outward_for_packing_size_detail inner join color_master on 
                          color_master.color_id=outward_for_packing_size_detail.color_id where 
                          outward_for_packing_size_detail.sales_order_no='".$request->sales_order_no."' and
                          outward_for_packing_size_detail.color_id='".$row->color_id."'");
                 
                  
                       if(isset($row->s1)) { $s1=((intval($row->s1))-(intval($List1[0]->s_1))); }
                       if(isset($row->s2)) { $s2=((intval($row->s2))-(intval($List1[0]->s_2))); }
                       if(isset($row->s3)) { $s3=((intval($row->s3))-(intval($List1[0]->s_3))); }
                       if(isset($row->s4)) { $s4=((intval($row->s4))-(intval($List1[0]->s_4))); }
                       if(isset($row->s5)) { $s5=((intval($row->s5))-(intval($List1[0]->s_5))); }
                       if(isset($row->s6)) { $s6=((intval($row->s6))-(intval($List1[0]->s_6))); }
                       if(isset($row->s7)) { $s7=((intval($row->s7))-(intval($List1[0]->s_7)));}
                       if(isset($row->s8)) { $s8=((intval($row->s8))-(intval($List1[0]->s_8)));}
                       if(isset($row->s9)) { $s9=((intval($row->s9))-(intval($List1[0]->s_9)));}
                       if(isset($row->s10)) { $s10=((intval($row->s10))-(intval($List1[0]->s_10)));}
                       if(isset($row->s11)) { $s11=((intval($row->s11))-(intval($List1[0]->s_11)));}
                       if(isset($row->s12)) { $s12=((intval($row->s12))-(intval($List1[0]->s_12)));}
                       if(isset($row->s13)) { $s13=((intval($row->s13))-(intval($List1[0]->s_13)));}
                       if(isset($row->s14)) { $s14=((intval($row->s14))-(intval($List1[0]->s_14)));}
                       if(isset($row->s15)) { $s15=((intval($row->s15))-(intval($List1[0]->s_15)));}
                       if(isset($row->s16)) {$s16=((intval($row->s16))-(intval($List1[0]->s_16)));}
                       if(isset($row->s17)) { $s17=((intval($row->s17))-(intval($List1[0]->s_17)));}
                       if(isset($row->s18)) { $s18=((intval($row->s18))-(intval($List1[0]->s_18)));}
                       if(isset($row->s19)) { $s19=((intval($row->s19))-(intval($List1[0]->s_19)));}
                       if(isset($row->s20)) { $s20=((intval($row->s20))-(intval($List1[0]->s_20)));}
                         
                    
                      if(isset($row->s1)) { $html.='<td>'.$s1.' <input style="width:80px; float:left;" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
                      if(isset($row->s2)) { $html.='<td>'.$s2.' <input style="width:80px; float:left;" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
                      if(isset($row->s3)) { $html.='<td>'.$s3.' <input style="width:80px; float:left;" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
                      if(isset($row->s4)) { $html.='<td>'.$s4.' <input style="width:80px; float:left;" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
                      if(isset($row->s5)) { $html.='<td>'.$s5.' <input style="width:80px; float:left;" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
                      if(isset($row->s6)) { $html.='<td>'.$s6.' <input style="width:80px; float:left;" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
                      if(isset($row->s7)) { $html.='<td>'.$s7.' <input style="width:80px; float:left;" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
                      if(isset($row->s8)) { $html.='<td>'.$s8.' <input style="width:80px; float:left;" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
                      if(isset($row->s9)) { $html.='<td>'.$s9.' <input style="width:80px; float:left;" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
                      if(isset($row->s10)) { $html.='<td>'.$s10.' <input style="width:80px; float:left;" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
                      if(isset($row->s11)) { $html.='<td>'.$s11.' <input style="width:80px; float:left;" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
                      if(isset($row->s12)) { $html.='<td>'.$s12.' <input style="width:80px; float:left;" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
                      if(isset($row->s13)) { $html.='<td>'.$s13.' <input style="width:80px; float:left;" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
                      if(isset($row->s14)) { $html.='<td>'.$s14.' <input style="width:80px; float:left;" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
                      if(isset($row->s15)) { $html.='<td>'.$s15.' <input style="width:80px; float:left;" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
                      if(isset($row->s16)) { $html.='<td>'.$s16.' <input style="width:80px; float:left;" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
                      if(isset($row->s17)) { $html.='<td>'.$s17.' <input style="width:80px; float:left;" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
                      if(isset($row->s18)) { $html.='<td>'.$s18.' <input style="width:80px; float:left;" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
                      if(isset($row->s19)) { $html.='<td>'.$s19.' <input style="width:80px; float:left;" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
                      if(isset($row->s20)) { $html.='<td>'.$s20.' <input style="width:80px; float:left;" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';} 
                      
                     
                     
                      $html.='<td>'.($row->size_qty_total-$List1[0]->size_qty_total).' 
                        <input type="hidden" name="overall_size_qty"  value="'.($row->size_qty_total-$List1[0]->size_qty_total).'" class="overall_size_qty" style="width:80px; float:left;"  />
                      <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" oninput="qtyCheck(this);" readOnly />
                    <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
                    <input type="hidden" name="size_array[]"  value="'.$VendorWorkOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
                          
                       $html.='</tr>';
            
                      $no=$no+1;
                }
            }
        else
        {
            
            
            $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
                ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                ->where('tr_code','=',$request->sales_order_no)->DISTINCT()->get();
        
            $BuyerPurchaseData = DB::SELECT("SELECT * FROM buyer_purchase_order_detail WHERE tr_code = '".$request->sales_order_no."' GROUP BY color_id");
            
            foreach($BuyerPurchaseData as $row)
            {
                $html .='<tr>';
                $html .='<td>'.$no.'</td>';
                $html.=' <td>
                <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
                
                <select name="color_id[]" class="select2-select"  id="color_ids0" style="width:250px; height:30px;" disabled>
                <option value="">--Select Color--</option>';
        
                foreach($ColorList as  $row1)
                {
                    $html.='<option value="'.$row1->color_id.'"';
                    $row1->color_id == $row->color_id ? $html.='selected="selected"' : '';
                    $html.='>'.$row1->color_name.'</option>';
                }
                
                $html.='</select></td>';
        
                
                $nox = 1;  
                foreach ($SizeDetailList as $sz) 
                {
                    $sizex = 's' . $nox;
                    if (isset($sizex)) 
                    { 
                        $html .= '<td>0<input style="width:80px; float:left;" name="' . $sizex . '[]" class="size_id" type="number" id="' . $sizex . '" value="0" required /></td>';
                    }
                
                    $nox++;
                }
    
                 
                $html.='<td>
                <input type="hidden" name="overall_size_qty"  value="0" class="overall_size_qty" style="width:80px; float:left;"  />
                <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" oninput="qtyCheck(this);" readOnly />
                <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
                <input type="hidden" name="size_array[]"  value="'.$row->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
                      
                $html.='</tr>';
                $no=$no+1;
            }
        }
          $html.='</tbody></table>';


      return response()->json(['html' => $html]);
         
    }
     
    
   
   public function vw_GetStitichingPOQty(Request $request)
   {
      
      $VendorWorkOrderMasterList = VendorWorkOrderModel::find($request->sales_order_no);
   
      $StitchingInhouseDetailList = DB::table('stitching_inhouse_detail')->where('stitching_inhouse_detail.sales_order_no',$request->sales_order_no)->first();
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$request->sales_order_no)->first();
      
      $ColorList = DB::table('stitching_inhouse_detail')->select('stitching_inhouse_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'stitching_inhouse_detail.color_id', 'left outer')
        ->where('sales_order_no','=',$request->sales_order_no)->DISTINCT()->get();
      
  
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
      $MasterdataList = DB::select("SELECT stitching_inhouse_size_detail.item_code, stitching_inhouse_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail inner join color_master on 
      color_master.color_id=stitching_inhouse_size_detail.color_id where sales_order_no='".$request->sales_order_no."'
      group by stitching_inhouse_size_detail.color_id order by stitching_inhouse_size_detail.color_id");
       
 
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
        
        <select name="color_ids[]" class="select2-select"  id="color_ids0" style="width:250px; height:30px;" disabled>
        <option value="0">--Select Color--</option>';

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
            
          $List = DB::select("SELECT outward_for_packing_size_detail.color_id, color_name, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from outward_for_packing_size_detail inner join color_master on 
          color_master.color_id=outward_for_packing_size_detail.color_id where 
          outward_for_packing_size_detail.sales_order_no='".$request->sales_order_no."' and
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
              
           $html.='</tr>';

          $no=$no+1;
        }
          $html.='</tbody></table>';


      return response()->json(['html' => $html]);
         
    }
     
      
    public function getVendorPO(Request $request)
    {
        //   DB::enableQueryLog();
             
         $POList = DB::select("select vw_code,sales_order_no from vendor_work_order_master where vendorId ='".$request->vendorId."'");
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
            $vw_code="'".$row->vw_code."'";
            {$html .= '<option value="'.$vw_code.'">'.$row->vw_code.'('.$row->sales_order_no.')</option>';}
        }
          return response()->json(['html' => $html]);
    }
     
     
        public function GetCuttingPOItemList(Request $request)
        {
            $html='';
             if($request->part_id==1)
           { 
                $ItemList = DB::select("select distinct item_code from vendor_work_order_size_detail2 as f1
                where f1.vw_code in (".$request->vw_code.")");
           }
           else
           {
                $ItemList = DB::select("select distinct item_code from vendor_work_order_trim_fabric_details
                as f2 where f2.vw_code in (".$request->vw_code.")");
           }
            
            foreach ($ItemList as $row) 
            {
                $item = ItemModel::where('item_code','=', $row->item_code)->first(); 
                $html .= '<option value="'.$row->item_code.'">'.$item->item_name.'</option>';
            }    
   
             return response()->json(['html' => $html]);
        }    
        
        
        
        
        
        
    public function OutwardForPackingPrint($ofp_code)
    {
        
         
        // DB::enableQueryLog();
       
         $OutwardForPackingMaster = OutwardForPackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'outward_for_packing_master.userId')
         ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'outward_for_packing_master.vendorId')
         ->leftJoin('location_master as sent', 'sent.loc_id', '=', 'outward_for_packing_master.sent_to')
         ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'outward_for_packing_master.mainstyle_id')
         ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'outward_for_packing_master.sent_to')
         ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'outward_for_packing_master.fg_id')
         ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','outward_for_packing_master.vw_code')
        ->where('outward_for_packing_master.ofp_code', $ofp_code)
         ->get(['outward_for_packing_master.*','usermaster.username','sent.location as sent_location','outward_for_packing_master.sales_order_no',
         'sent.gst_no as sent_gst_no','sent.loc_inc as sent_loc_inc','sent.pan_no as sent_pan_no','mainstyle_name','substyle_name','fg_name','ledger_master.ac_name','ledger_master.address','ledger_master.gst_no','ledger_master.pan_no']);
    //   dd(DB::getQueryLog());
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
       
        
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$OutwardForPackingMaster[0]->sales_order_no)->get();
                   
        
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
          $OutwardForPackingList = DB::select("SELECT 	outward_for_packing_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from	outward_for_packing_size_detail  
        inner join color_master on color_master.color_id=outward_for_packing_size_detail.color_id 
        where ofp_code='".$OutwardForPackingMaster[0]->ofp_code."' group by outward_for_packing_size_detail.color_id");
             //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('OutwardForPackingPrint', compact('OutwardForPackingMaster','OutwardForPackingList','SizeDetailList','FirmDetail'));
      
    }
 public function OutwardForPackingPrintView($ofp_code)
    {
        
         $OutwardForPackingMaster = OutwardForPackingMasterModel::join('usermaster', 'usermaster.userId', '=', 'outward_for_packing_master.userId')
         ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'outward_for_packing_master.vendorId')
         ->leftJoin('location_master as sent', 'sent.loc_id', '=', 'outward_for_packing_master.sent_to')
         ->leftJoin('main_style_master', 'main_style_master.mainstyle_id', '=', 'outward_for_packing_master.mainstyle_id')
         ->leftJoin('sub_style_master', 'sub_style_master.substyle_id', '=', 'outward_for_packing_master.sent_to')
         ->leftJoin('fg_master', 'fg_master.fg_id', '=', 'outward_for_packing_master.fg_id')
         ->leftJoin('vendor_work_order_master', 'vendor_work_order_master.vw_code', '=','outward_for_packing_master.vw_code')
        ->where('outward_for_packing_master.ofp_code', $ofp_code)
         ->get(['outward_for_packing_master.*','usermaster.username','sent.location as sent_location','outward_for_packing_master.sales_order_no',
         'sent.gst_no as sent_gst_no','sent.loc_inc as sent_loc_inc','sent.pan_no as sent_pan_no','mainstyle_name','substyle_name','fg_name','ledger_master.ac_name','ledger_master.address','ledger_master.gst_no','ledger_master.pan_no']);
         $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$OutwardForPackingMaster[0]->sales_order_no)->get();
                   
        
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');

          $OutwardForPackingList = DB::select("SELECT 	outward_for_packing_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from	outward_for_packing_size_detail  
        inner join color_master on color_master.color_id=outward_for_packing_size_detail.color_id 
        where ofp_code='".$OutwardForPackingMaster[0]->ofp_code."' group by outward_for_packing_size_detail.color_id");
           
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('PackingOutwardView', compact('OutwardForPackingMaster','OutwardForPackingList','SizeDetailList','FirmDetail'));
      
    }

    
    public function OutwardForPackingMergedPrint(Request $request)
    {
        
        $ofpCodes = explode(',', request('ofp_codes'));
        $ofpCodes1 =   request('ofp_codes');
      
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
        return view('OutwardForPackingMergedPrint', compact('ofpCodes','FirmDetail', 'ofpCodes1'));
      
    }
        
    public function destroy($id)
    {
        DB::table('outward_for_packing_master')->where('ofp_code', $id)->delete();
        DB::table('outward_for_packing_detail')->where('ofp_code', $id)->delete();
        DB::table('outward_for_packing_size_detail')->where('ofp_code', $id)->delete();
        DB::table('outward_for_packing_size_detail2')->where('ofp_code', $id)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function rptOutwardForPackingReport(Request $request)
    {
        ini_set('memory_limit', '10G');
        $srno = 1;
        if ($request->ajax()) 
        {  
            $outwardData = DB::select("SELECT outward_for_packing_size_detail2.*,LM1.ac_short_name as from_vendor, LM2.ac_short_name as sent_vendor,LM3.ac_short_name as buyer_name,brand_master.brand_name,
                            fg_master.fg_name,color_master.color_name,size_detail.size_name,main_style_master.mainstyle_name
                            FROM outward_for_packing_size_detail2
                            INNER JOIN outward_for_packing_master ON outward_for_packing_master.ofp_code = outward_for_packing_size_detail2.ofp_code
                            LEFT JOIN ledger_master as LM1 ON LM1.ac_code = outward_for_packing_size_detail2.vendorId
                            LEFT JOIN ledger_master as LM2 ON LM2.ac_code = outward_for_packing_master.sent_to
                            LEFT JOIN ledger_master as LM3 ON LM3.ac_code = outward_for_packing_master.Ac_code
                            LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = outward_for_packing_size_detail2.sales_order_no
                            LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                            LEFT JOIN main_style_master ON main_style_master.mainstyle_id = outward_for_packing_size_detail2.mainstyle_id 
                            LEFT JOIN fg_master ON fg_master.fg_id = outward_for_packing_size_detail2.fg_id
                            LEFT JOIN color_master ON color_master.color_id = outward_for_packing_size_detail2.color_id
                            LEFT JOIN size_detail ON size_detail.size_id = outward_for_packing_size_detail2.size_id");
        
            return Datatables::of($outwardData) 
                ->addColumn('srno', function ($row) use (&$srno) { 
                    return $srno++;
                })  
                ->rawColumns(['srno'])
                ->make(true);
        } 
        return view('rptOutwardForPackingReport');  
    }
    
    public function getVendorWorkOrderDetailsForPacking(Request $request)
    { 
        $sales_order_no= $request->input('sales_order_no');
        $MasterdataList = DB::select("select Ac_code,tr_code, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code='".$sales_order_no."'");
        return json_encode($MasterdataList);
    }   
   
    public function FinishingInwardOutwardSummaryReport(Request $request)
    {
        ini_set('memory_limit', '10G');
        $srno = 1; 
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate = isset($request->toDate) ? $request->toDate : date("Y-m-d");
        
        $inwardData = DB::SELECT("SELECT inward_for_packing_size_detail2.ifp_code, ifnull(inward_for_packing_size_detail2.ifp_date,outward_for_packing_size_detail2.ofp_date) as date ,outward_for_packing_size_detail2.ofp_code, sum(inward_for_packing_size_detail2.size_qty) as inward_qty 
                                FROM outward_for_packing_size_detail2
                                LEFT JOIN inward_for_packing_size_detail2 ON inward_for_packing_size_detail2.ofp_code = outward_for_packing_size_detail2.ofp_code 
                                WHERE inward_for_packing_size_detail2.ifp_date BETWEEN '".$fromDate."' AND '".$toDate."' OR  outward_for_packing_size_detail2.ofp_date BETWEEN '".$fromDate."' AND '".$toDate."' 
                                GROUP BY  outward_for_packing_size_detail2.ofp_date,outward_for_packing_size_detail2.ofp_code,inward_for_packing_size_detail2.ifp_date,inward_for_packing_size_detail2.ifp_code ORDER BY outward_for_packing_size_detail2.ofp_code ASC"); 
        return view('FinishingInwardOutwardSummaryReport', compact('inwardData', 'fromDate','toDate'));  
    }
    
    public function FinishingInwardOutwardDetailReport(Request $request)
    {
        ini_set('memory_limit', '10G');
        $srno = 1; 
        $fromDate = isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate = isset($request->toDate) ? $request->toDate : date("Y-m-d");
        
        // $inwardData = DB::SELECT("SELECT ifp_date,ofp_code, sum(size_qty) as inward_qty, ledger_master.ac_short_name as buyer,inward_for_packing_size_detail2.style_no,
        //                         LM1.ac_short_name as vendor_name, brand_master.brand_name,inward_for_packing_size_detail2.sales_order_no 
        //                         FROM inward_for_packing_size_detail2 
        //                         LEFT JOIN ledger_master ON ledger_master.ac_code = inward_for_packing_size_detail2.Ac_code
        //                         LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_for_packing_size_detail2.vendorId
        //                         LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = inward_for_packing_size_detail2.sales_order_no
        //                         LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
        //                         WHERE ifp_date BETWEEN '".$fromDate."' AND '".$toDate."' GROUP BY inward_for_packing_size_detail2.ifp_date,inward_for_packing_size_detail2.ifp_code 
        //                         order by inward_for_packing_size_detail2.ofp_code asc"); 
                                
        $inwardData = DB::SELECT("SELECT inward_for_packing_size_detail2.ifp_code, ifnull(inward_for_packing_size_detail2.ifp_date,outward_for_packing_size_detail2.ofp_date) as date,
                                outward_for_packing_size_detail2.ofp_code, sum(inward_for_packing_size_detail2.size_qty) as inward_qty, 
                                ledger_master.ac_short_name as buyer,IFNULL(outward_for_packing_size_detail2.style_no, inward_for_packing_size_detail2.style_no) as style_no,
                                LM1.ac_short_name as vendor_name, brand_master.brand_name,IFNULL(outward_for_packing_size_detail2.sales_order_no, inward_for_packing_size_detail2.sales_order_no) as sales_order_no,inward_for_packing_master.narration
                                FROM outward_for_packing_size_detail2
                                LEFT JOIN inward_for_packing_size_detail2 ON inward_for_packing_size_detail2.ofp_code = outward_for_packing_size_detail2.ofp_code 
                                LEFT JOIN inward_for_packing_master ON inward_for_packing_master.ifp_code = inward_for_packing_size_detail2.ifp_code 
                                LEFT JOIN ledger_master ON ledger_master.ac_code = IFNULL(outward_for_packing_size_detail2.Ac_code,inward_for_packing_size_detail2.Ac_code)  
                                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = IFNULL(outward_for_packing_size_detail2.vendorId,inward_for_packing_size_detail2.vendorId)
                                LEFT JOIN buyer_purchse_order_master ON buyer_purchse_order_master.tr_code = IFNULL(outward_for_packing_size_detail2.sales_order_no,inward_for_packing_size_detail2.sales_order_no)
                                LEFT JOIN brand_master ON brand_master.brand_id = buyer_purchse_order_master.brand_id
                                WHERE inward_for_packing_size_detail2.ifp_date BETWEEN '".$fromDate."' AND '".$toDate."' OR  outward_for_packing_size_detail2.ofp_date BETWEEN '".$fromDate."' AND '".$toDate."' 
                                GROUP BY  outward_for_packing_size_detail2.ofp_date,outward_for_packing_size_detail2.ofp_code,inward_for_packing_size_detail2.ifp_date,inward_for_packing_size_detail2.ifp_code ORDER BY outward_for_packing_size_detail2.ofp_code ASC");                         
                                
                                
        return view('FinishingInwardOutwardDetailReport', compact('inwardData', 'fromDate','toDate'));  	
    }
}
