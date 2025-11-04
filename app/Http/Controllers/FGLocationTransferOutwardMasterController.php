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
use App\Models\LocationModel;
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
use App\Models\LocTransferPackingInhouseMasterModel;
use App\Models\LocTransferPackingInhouseDetailModel;
use App\Models\LocTransferPackingInhouseSizeDetailModel;
use Session;
use DataTables;

class FGLocationTransferOutwardMasterController extends Controller
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
        ->where('form_id', '201')
        ->first();
        
         $LocationList = LocationModel::where('location_master.delflag','=', '0')->get();
        
        $TransferPackingInhouseMasterList = LocTransferPackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'loc_transfer_packing_inhouse_master.userId', 'left outer')
            ->join('ledger_master as L1', 'L1.Ac_code', '=', 'loc_transfer_packing_inhouse_master.Ac_code', 'left outer')
            ->join('location_master as Loc1', 'Loc1.loc_id', '=', 'loc_transfer_packing_inhouse_master.from_loc_id', 'left outer')
            ->join('location_master as Loc2', 'Loc2.loc_id', '=', 'loc_transfer_packing_inhouse_master.to_loc_id', 'left outer')
                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'loc_transfer_packing_inhouse_master.sales_order_no', 'left outer')
                    ->join('brand_master', 'brand_master.brand_id', '=', 'buyer_purchse_order_master.brand_id', 'left outer')
            ->where('loc_transfer_packing_inhouse_master.delflag','=', '0')
            ->orderBy(DB::raw('CAST(SUBSTRING(loc_transfer_packing_inhouse_master.ltpki_code, LOCATE("-", loc_transfer_packing_inhouse_master.ltpki_code) + 1) AS UNSIGNED)'), 'DESC')
            ->get(['loc_transfer_packing_inhouse_master.*','usermaster.username','L1.Ac_name', 'Loc1.location as from_location', 'Loc2.location as to_location','brand_master.brand_name' ]);

        if ($request->ajax()) 
        { 
            return Datatables::of($TransferPackingInhouseMasterList) 
            ->addIndexColumn()
            ->addColumn('srno',function ($row) { 
                static $srno = 1;   
                return $srno++;
            }) 
            ->addColumn('action1', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="LocTransferPackingPrint/'.$row->ltpki_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action2', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('FGLocationTransferOutward.edit', $row->ltpki_code).'" >
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
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->ltpki_code.'"  data-route="'.route('FGLocationTransferOutward.destroy', $row->ltpki_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['action1','action2','action3', 'srno'])
    
            ->make(true);
        }
        return view('FGLocationTransferOutwardMasterList', compact('TransferPackingInhouseMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FGLocationTransferOutward'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get();
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->get();
        return view('FGLocationTransferOutwardMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList','LocationList', 'BuyerPurchaseOrderList','Ledger',  'counter_number','FirmList'));
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
        ->where('type','=','FGLocationTransferOutward')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
          
        $this->validate($request, [
             
                'ltpki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'main_sales_order_no'=>'required',
                'Ac_code'=>'required',
                'mainstyle_id'=>'required',
               
    ]);
 
   
    $data1=array
    (
        'ltpki_code'=>$TrNo, 
        'ltpki_date'=>$request->ltpki_date, 
        'firm_id'=>$request->firm_id,
        'sales_order_no'=>$request->main_sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty, 
        'narration'=>$request->narration,
        'from_loc_id'=>$request->from_loc_id,
        'to_loc_id'=>$request->to_loc_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
     );
 
    LocTransferPackingInhouseMasterModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FGLocationTransferOutward'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
        for($x=0; $x<count($color_id); $x++) 
        { 
            if($request->size_qty_total[$x]>0)
            {
                    $data2=array
                    (
    					'ltpki_code'=>$TrNo,
                        'ltpki_date'=>$request->ltpki_date,
    					'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
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
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
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
                        'ltpki_code'=>$TrNo, 
                        'ltpki_date'=>$request->ltpki_date, 
						'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
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
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
                    );
                LocTransferPackingInhouseDetailModel::insert($data2);
                LocTransferPackingInhouseSizeDetailModel::insert($data3);
              } // if loop avoid zero qty
        }
        
          
    }
    
        
   $InsertSizeData=DB::select('call AddSizeQtyFromLocTransferPackingInhouse("'.$TrNo.'")');
           
    return redirect()->route('FGLocationTransferOutward.index')->with('message', 'Data Saved Succesfully');  
      
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
          
        $TransferPackingInhouseMasterList = LocTransferPackingInhouseMasterModel::find($id);
       
        $FirmList = FirmModel::where('firm_master.delflag','=', '0')->get();
        
         
        $LedgerDetail  = LedgerDetailModel::where('ledger_details.ac_code',$TransferPackingInhouseMasterList->Ac_code)->get();
        
         
        $BuyerPurchaseOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code')->where('Ac_code',$TransferPackingInhouseMasterList->Ac_code)->get();
        
        // DB::enableQueryLog();
        
        // $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$TransferPackingInhouseMasterList->sales_order_no)->distinct()->get();
        
      
      $LocationList = LocationModel::where('location_master.delflag','=', '0')->get();
	 
        
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code',$TransferPackingInhouseMasterList->sales_order_no)->DISTINCT()->get();
        
        $TransferPackingInhouseDetailList =LocTransferPackingInhouseDetailModel::where('loc_transfer_packing_inhouse_detail.ltpki_code','=', $TransferPackingInhouseMasterList->ltpki_code)->get();
        //  
        
             // DB::enableQueryLog(); 
        
        
        
        //   DB::enableQueryLog();  
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('tr_code',$TransferPackingInhouseMasterList->sales_order_no)->get();
    
    //       $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
    
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
      
        
         return view('FGLocationTransferOutwardMasterEdit',compact('TransferPackingInhouseDetailList','ColorList' ,'LocationList','FirmList','BuyerPurchaseOrderList', 'LedgerDetail',  'SizeDetailList','TransferPackingInhouseMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList', 'Ledger' ));
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
             
                'ltpki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'main_sales_order_no'=> 'required', 
    ]);
 
   
  
$data1=array(
           
        'ltpki_code'=>$request->ltpki_code, 
        'ltpki_date'=>$request->ltpki_date,
        'firm_id'=>$request->firm_id,
        'sales_order_no'=>$request->main_sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'mainstyle_id'=>$request->mainstyle_id,
        'substyle_id'=>$request->substyle_id,
        'fg_id'=>$request->fg_id,
        'style_no'=>$request->style_no,
        'style_description'=>$request->style_description,
        'total_qty'=>$request->total_qty, 
        'narration'=>$request->narration,
        'from_loc_id'=>$request->from_loc_id,
        'to_loc_id'=>$request->to_loc_id,
        'userId'=>$request->userId,
        'delflag'=>'0',
        'c_code'=>$request->c_code,
         
    );
//   DB::enableQueryLog();   
$PackingInhouseList = LocTransferPackingInhouseMasterModel::findOrFail($request->ltpki_code); 
//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
$PackingInhouseList->fill($data1)->save();

 
DB::table('loc_transfer_packing_inhouse_size_detail')->where('ltpki_code', $request->input('ltpki_code'))->delete();
DB::table('loc_transfer_packing_inhouse_size_detail2')->where('ltpki_code', $request->input('ltpki_code'))->delete();
DB::table('loc_transfer_packing_inhouse_detail')->where('ltpki_code', $request->input('ltpki_code'))->delete();
 
 $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array(
          
                    'ltpki_code'=>$request->ltpki_code,
                    'ltpki_date'=>$request->ltpki_date,
					'sales_order_no'=>$request->main_sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
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
                    'from_loc_id'=>$request->from_loc_id,
                    'to_loc_id'=>$request->to_loc_id,
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
                  
                        'ltpki_code'=>$request->ltpki_code,
                        'ltpki_date'=>$request->ltpki_date, 
						'sales_order_no'=>$request->main_sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
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
                        'from_loc_id'=>$request->from_loc_id,
                        'to_loc_id'=>$request->to_loc_id,
                          );
              
              } // if loop avoid zero qty
            }
          LocTransferPackingInhouseDetailModel::insert($data2);
          LocTransferPackingInhouseSizeDetailModel::insert($data3);
          
    }
    
           
           
    $InsertSizeData=DB::select('call AddSizeQtyFromLocTransferPackingInhouse("'.$request->ltpki_code.'")');
           
           
     return redirect()->route('FGLocationTransferOutward.index')->with('message', 'Data Saved Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
   public function FGPackingInhouseDetails(Request $request)
    { 
       
         $SalesOrders=$request->sales_order_no;
        $MasterdataList = DB::select("select Ac_code,tr_code, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code ='".$SalesOrders."'");
        return json_encode($MasterdataList);
    }   
      



 public function LTPKI_GetMaxMinvalueList(Request $request)
    { 
         $color_id=$request->input('color_id');
         $from_loc_id=$request->from_loc_id;
         $main_sales_order_no=$request->main_sales_order_no;
         
         $sizeList='';
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$main_sales_order_no)->first();
     $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
       $sizes='';
      $no=1;
         foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'ifnull(sum(s'.$no.'),0) as s'.$no.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        
        
        // DB::enableQueryLog();
       $LocRecList = DB::select("SELECT ifnull(loc_transfer_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from loc_transfer_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=loc_transfer_packing_inhouse_size_detail.color_id where 
      sales_order_no='".$main_sales_order_no."' and to_loc_id='".$from_loc_id."' and
      loc_transfer_packing_inhouse_size_detail.color_id='".$color_id."'");  
    //       $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
      
      $CompareList = DB::select("SELECT ifnull(loc_transfer_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from loc_transfer_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=loc_transfer_packing_inhouse_size_detail.color_id where 
      sales_order_no='".$main_sales_order_no."' and from_loc_id='".$from_loc_id."' and
      loc_transfer_packing_inhouse_size_detail.color_id='".$color_id."'");
    //          $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
      
       
      $CompareList2 = DB::select("SELECT ifnull(transfer_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from transfer_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=transfer_packing_inhouse_size_detail.color_id where 
        sales_order_no='".$main_sales_order_no."' and
      transfer_packing_inhouse_size_detail.color_id='".$color_id."'");
      
      
        //   DB::enableQueryLog();
       $CartonList = DB::select("SELECT ifnull(carton_packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizes.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from carton_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=carton_packing_inhouse_size_detail.color_id where 
      carton_packing_inhouse_size_detail.sales_order_no ='".$main_sales_order_no."'  and
      carton_packing_inhouse_size_detail.color_id='".$color_id."'");
    //       $query = DB::getQueryLog();
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
        // DB::enableQueryLog(); 
    
     
      $List = DB::select("SELECT  ifnull(packing_inhouse_size_detail.item_code,0) as item_code,
      ifnull(packing_inhouse_size_detail.color_id,0) as color_id, color_name, ".$sizex.", 
      ifnull(sum(size_qty_total),0) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=packing_inhouse_size_detail.color_id where 
      packing_inhouse_size_detail.sales_order_no ='".$main_sales_order_no."' and   location_id='".$from_loc_id."' and
      packing_inhouse_size_detail.color_id='".$color_id."'");
       
    //  $query = DB::getQueryLog();
    //  $query = end($query);
    //  dd($query);
      
       
   if(isset($List[0]->s_1)) { $s1=((intval($List[0]->s_1)) +   (intval($LocRecList[0]->s1))-(intval($CompareList[0]->s1))-(intval($CompareList2[0]->s1))- ($List[0]->size_qty_total > 0 ? intval($CartonList[0]->s1) : 0)); $sizeList=$sizeList.$s1.' as s1, ';}
//   echo '   '.$List[0]->s_1;
//   echo '   '.$CompareList[0]->s1;
//   echo '   '.$CompareList2[0]->s1;
//   echo '    '.$CartonList[0]->s1;
//   echo '     '.(($List[0]->color_id > 0) ? intval($CartonList[0]->s1) : 0);
//   exit;
   if(isset($List[0]->s_2)) { $s2=((intval($List[0]->s_2)) +   (intval($LocRecList[0]->s2))   - (intval($CompareList[0]->s2))-(intval($CompareList2[0]->s2))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s2): 0)); $sizeList=$sizeList.$s2.' as s2, ';}
   if(isset($List[0]->s_3)) { $s3=((intval($List[0]->s_3))+   (intval($LocRecList[0]->s3))-(intval($CompareList[0]->s3))-(intval($CompareList2[0]->s3))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s3): 0)); $sizeList=$sizeList.$s3.' as s3, ';}
   if(isset($List[0]->s_4)) { $s4=((intval($List[0]->s_4))+   (intval($LocRecList[0]->s4))-(intval($CompareList[0]->s4))-(intval($CompareList2[0]->s4))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s4): 0)); $sizeList=$sizeList.$s4.' as s4, ';}
   if(isset($List[0]->s_5)) { $s5=((intval($List[0]->s_5))+   (intval($LocRecList[0]->s5))-(intval($CompareList[0]->s5))-(intval($CompareList2[0]->s5))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s5): 0)); $sizeList=$sizeList.$s5.' as s5, ';}
   if(isset($List[0]->s_6)) { $s6=((intval($List[0]->s_6))+   (intval($LocRecList[0]->s6))-(intval($CompareList[0]->s6))-(intval($CompareList2[0]->s6))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s6): 0)); $sizeList=$sizeList.$s6.' as s6, ';}
   if(isset($List[0]->s_7)) { $s7=((intval($List[0]->s_7))+   (intval($LocRecList[0]->s7))-(intval($CompareList[0]->s7))-(intval($CompareList2[0]->s7))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s7): 0)); $sizeList=$sizeList.$s7.' as s7, ';}
   if(isset($List[0]->s_8)) { $s8=((intval($List[0]->s_8))+   (intval($LocRecList[0]->s8))-(intval($CompareList[0]->s8))-(intval($CompareList2[0]->s8))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s8): 0)); $sizeList=$sizeList.$s8.' as s8, ';}
   if(isset($List[0]->s_9)) { $s9=((intval($List[0]->s_9))+   (intval($LocRecList[0]->s9))-(intval($CompareList[0]->s9))-(intval($CompareList2[0]->s9))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s9): 0)); $sizeList=$sizeList.$s9.' as s9, ';}
   if(isset($List[0]->s_10)) { $s10=((intval($List[0]->s_10))+   (intval($LocRecList[0]->s10))-(intval($CompareList[0]->s10))-(intval($CompareList2[0]->s10))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s10): 0)); $sizeList=$sizeList.$s10.' as s10, ';}
   if(isset($List[0]->s_11)) { $s11=((intval($List[0]->s_11))+   (intval($LocRecList[0]->s11))-(intval($CompareList[0]->s11))-(intval($CompareList2[0]->s11))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s11): 0)); $sizeList=$sizeList.$s11.' as s11, ';}
   if(isset($List[0]->s_12)) { $s12=((intval($List[0]->s_12))+   (intval($LocRecList[0]->s12))-(intval($CompareList[0]->s12))-(intval($CompareList2[0]->s12))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s12): 0)); $sizeList=$sizeList.$s12.' as s12, ';}
   if(isset($List[0]->s_13)) { $s13=((intval($List[0]->s_13))+   (intval($LocRecList[0]->s13))-(intval($CompareList[0]->s13))-(intval($CompareList2[0]->s13))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s13): 0)); $sizeList=$sizeList.$s13.' as s13, ';}
   if(isset($List[0]->s_14)) { $s14=((intval($List[0]->s_14))+   (intval($LocRecList[0]->s14))-(intval($CompareList[0]->s14))-(intval($CompareList2[0]->s14))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s14): 0)); $sizeList=$sizeList.$s14.' as s14, ';}
   if(isset($List[0]->s_15)) { $s15=((intval($List[0]->s_15))+   (intval($LocRecList[0]->s15))-(intval($CompareList[0]->s15)) -(intval($CompareList2[0]->s15)) - (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s15): 0)); $sizeList=$sizeList.$s15.' as s15, ';}
   if(isset($List[0]->s_16)) { $s16=((intval($List[0]->s_16))+   (intval($LocRecList[0]->s16))-(intval($CompareList[0]->s16))-(intval($CompareList2[0]->s16))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s16): 0)); $sizeList=$sizeList.$s16.' as s16, ';}
   if(isset($List[0]->s_17)) { $s17=((intval($List[0]->s_17))+   (intval($LocRecList[0]->s17))-(intval($CompareList[0]->s17))-(intval($CompareList2[0]->s17))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s17): 0)); $sizeList=$sizeList.$s17.' as s17, ';}
   if(isset($List[0]->s_18)) { $s18=((intval($List[0]->s_18))+   (intval($LocRecList[0]->s18))-(intval($CompareList[0]->s18))-(intval($CompareList2[0]->s18))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s18): 0)); $sizeList=$sizeList.$s18.' as s18, ';}
   if(isset($List[0]->s_19)) { $s19=((intval($List[0]->s_19))+   (intval($LocRecList[0]->s19))-(intval($CompareList[0]->s19))-(intval($CompareList2[0]->s19))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s19): 0)); $sizeList=$sizeList.$s19.' as s19, ';}
   if(isset($List[0]->s_20)) { $s20=((intval($List[0]->s_20))+   (intval($LocRecList[0]->s20))-(intval($CompareList[0]->s20))-(intval($CompareList2[0]->s20))- (($List[0]->size_qty_total > 0) ? intval($CartonList[0]->s20): 0)); $sizeList=$sizeList.$s20.' as s20, ';}
       
     // exit; 
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



    function FGStockSizeValue(Request $request)
    { 
        $FGStockData = DB::select("SELECT ifnull(sum(FG.`size_qty`),0)  as packing_grn_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByTwo  as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByTwo as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, 
                
                ifnull((SELECT  sum(d3.size_qty)from loc_transfer_packing_inhouse_size_detail2 as d3 where d3.sales_order_no=FG.sales_order_no and d3.color_id=FG.color_id 
                and d3.size_id=FG.size_id),0)  as loc_transfer_qty,
                
                FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByTwo as`FG`   
                inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                where FG.data_type_id=1 AND FG.sales_order_no ='".$request->sales_order_no."' AND FG.size_id = ".$request->size_id." AND  FG.color_id = ".$request->color_id."
                group by FG.sales_order_no, FG.color_id, FG.size_id");
        
        $packing_grn_qty = isset($FGStockData[0]->packing_grn_qty) ? $FGStockData[0]->packing_grn_qty : 0; 
        $carton_pack_qty = isset($FGStockData[0]->carton_pack_qty) ? $FGStockData[0]->carton_pack_qty: 0; 
        $transfer_qty = isset($FGStockData[0]->transfer_qty) ? $FGStockData[0]->transfer_qty : 0; 
        $loc_transfer_qty = isset($FGStockData[0]->loc_transfer_qty) ? $FGStockData[0]->loc_transfer_qty : 0; 
        $FGStock = $packing_grn_qty - $carton_pack_qty- $transfer_qty- $loc_transfer_qty;
        
        return $FGStock;
    }



  public function LTPKI_GetTransferQtyByRow(Request $request)
  {
       
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$request->sales_order_no)->first();
     
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::where('tr_code',$request->sales_order_no)->first();
      
      $BuyerPurchaseOrderList = BuyerPurchaseOrderMasterModel::select('tr_code as  sales_order_no')->get();
       $colorList=DB::select("select DISTINCT buyer_purchase_order_detail.color_id, color_name from buyer_purchase_order_detail
        inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
        where tr_code='".$request->sales_order_no."'");
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
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$BuyerPurchaseOrderMasterList->sales_order_no."'");
 
      $html = '';
     
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;height:30px;"/></td>';
                 
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2"  id="color_id0" style="width:250px; height:30px;"  onchange="setFGLimit(this);" required>
        <option value="">--Select Color--</option>';
            foreach ($colorList as $color) 
                  {
                     $html.='<option value="'.$color->color_id.'"';
           
                    $html.='>'.$color->color_name.'</option>';
                  }
        
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);" name="s1[]" class="size_id" type="number" id="s1" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);" name="s2[]" type="number" class="size_id" id="s2" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);" name="s3[]" type="number" class="size_id" id="s3" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s4[]" type="number" class="size_id" id="s4" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s5[]" type="number" class="size_id" id="s5" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s6[]" type="number" class="size_id" id="s6" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s7[]" type="number" class="size_id" id="s7" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s8[]" type="number" class="size_id" id="s8" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s9[]" type="number" class="size_id" id="s9" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s10[]" type="number" class="size_id" id="s10" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s11[]" type="number" class="size_id" id="s11" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s12[]" type="number" class="size_id" id="s12" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s13[]" type="number" class="size_id" id="s13" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s14[]" type="number" class="size_id" id="s14" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s15[]" type="number" class="size_id" id="s15" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s16[]" type="number" class="size_id" id="s16" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s17[]" type="number" class="size_id" id="s17" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s18[]" type="number" class="size_id" id="s18" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s19[]" type="number" class="size_id" id="s19" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;" max="0" min="0" onchange="checkNumber(this);"  name="s20[]" type="number" class="size_id" id="s20" value="0" required /> <span></span></td>';}
          $html.='<td>
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
          $html.='<td><button type="button" onclick="addNewRow();" id="AButton" class="AButton btn btn-warning pull-left">+</button> &nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone1(this);" value="X" ></td>';

          
          
           $html.='</tr>';

        
         

      return response()->json(['html' => $html]);
         
  }

  
  
  
  public function LocFGStockData(Request $request)
  {
 
     
     $html = '';
     
     
        //   DB::enableQueryLog();  


    //   $FinishedGoodsStock = DB::select("SELECT    buyer_purchse_order_master.tr_code as sales_order_no,  
    //   color_master.color_name,color_master.style_img_path, brand_master.brand_name, 
    //     size_detail.size_name, 
        
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from packing_inhouse_size_detail2 
    //     inner join packing_inhouse_master on packing_inhouse_master.pki_code=packing_inhouse_size_detail2.pki_code
    //     where packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     packing_inhouse_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and   packing_inhouse_size_detail2.location_id='".$request->from_loc_id."'
    //      ),0) as 'packing_grn_qty',
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from carton_packing_inhouse_size_detail2 
    //     inner join carton_packing_inhouse_master on carton_packing_inhouse_master.cpki_code=carton_packing_inhouse_size_detail2.cpki_code
    //     where carton_packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     carton_packing_inhouse_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and carton_packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and carton_packing_inhouse_master.endflag=1
    //     ),0)  as 'carton_pack_qty',
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from loc_transfer_packing_inhouse_size_detail2 
    //     inner join loc_transfer_packing_inhouse_master on loc_transfer_packing_inhouse_master.ltpki_code=loc_transfer_packing_inhouse_size_detail2.ltpki_code
    //     where loc_transfer_packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     loc_transfer_packing_inhouse_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and loc_transfer_packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and   loc_transfer_packing_inhouse_size_detail2.from_loc_id='".$request->from_loc_id."'
    //      ),0) as 'loc_transfer_qty',
        
        
    //     ifnull((SELECT ifnull(sum(size_qty),0) from loc_transfer_packing_inhouse_size_detail2 
    //     inner join loc_transfer_packing_inhouse_master on loc_transfer_packing_inhouse_master.ltpki_code=loc_transfer_packing_inhouse_size_detail2.ltpki_code
    //     where loc_transfer_packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     loc_transfer_packing_inhouse_size_detail2.sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and loc_transfer_packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and   loc_transfer_packing_inhouse_size_detail2.to_loc_id='".$request->from_loc_id."'
    //      ),0) as 'loc_rec_transfer_qty',
        
          
    //       ifnull((SELECT ifnull(sum(size_qty),0) from transfer_packing_inhouse_size_detail2 
    //     inner join transfer_packing_inhouse_master on transfer_packing_inhouse_master.tpki_code=transfer_packing_inhouse_size_detail2.tpki_code
    //     where transfer_packing_inhouse_size_detail2.color_id=buyer_purchase_order_size_detail.color_id and 
    //     transfer_packing_inhouse_size_detail2.main_sales_order_no=buyer_purchase_order_size_detail.tr_code 
    //     and transfer_packing_inhouse_size_detail2.size_id=buyer_purchase_order_size_detail.size_id
    //     and transfer_packing_inhouse_size_detail2.usedFlag=1
    //     ),0) as 'transfer_qty' 
         
        
    //     FROM `buyer_purchase_order_size_detail`
        
    //     LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=buyer_purchase_order_size_detail.tr_code
    //     LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
    //   LEFT JOIN color_master on color_master.color_id=buyer_purchase_order_size_detail.color_id
    //     LEFT JOIN size_detail on size_detail.size_id = buyer_purchase_order_size_detail.size_id
    //   where buyer_purchse_order_master.tr_code='".$request->sales_order_no."'
    //     GROUP by buyer_purchase_order_size_detail.tr_code, buyer_purchase_order_size_detail.color_id, buyer_purchase_order_size_detail.size_id");

        //DB::enableQueryLog();
        $FinishedGoodsStock = DB::select("SELECT FG.entry_date, FG.`ac_name`,job_status_master.job_status_name,sales_order_costing_master.total_cost_value,
                brand_master.brand_name, FG.`sales_order_no`, FG.`mainstyle_name`, FG.`substyle_name`, FG.`fg_name`, FG.`style_no`, 
                FG.`style_description`, FG.`color_name`, FG.`size_name`,ifnull(sum(FG.`size_qty`),0)  as packing_grn_qty , ifnull((SELECT  sum(d2.size_qty) from FGStockDataByTwo as d2 
                where d2.data_type_id=2 and d2.sales_order_no=FG.sales_order_no and d2.color_id=FG.color_id and d2.size_id=FG.size_id),0) as carton_pack_qty ,
                
                ifnull((SELECT  sum(d1.size_qty)from FGStockDataByTwo as d1 where d1.data_type_id=3 and d1.sales_order_no=FG.sales_order_no and d1.color_id=FG.color_id 
                and d1.size_id=FG.size_id),0)  as transfer_qty, 
                
                ifnull((SELECT sum(d3.size_qty) from loc_transfer_packing_inhouse_size_detail2 as d3 where d3.sales_order_no=FG.sales_order_no and d3.color_id=FG.color_id 
                and d3.size_id=FG.size_id),0)  as loc_transfer_qty, 
                
                FG.`color_id`, FG.`size_id`, buyer_purchse_order_master.order_rate FROM FGStockDataByTwo as`FG`   
                left join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=FG.sales_order_no
                left join job_status_master ON job_status_master.job_status_id = buyer_purchse_order_master.job_status_id
                left join brand_master On brand_master.brand_id = buyer_purchse_order_master.brand_id
                left join sales_order_costing_master On sales_order_costing_master.sales_order_no = FG.sales_order_no
                where FG.data_type_id =1 AND FG.sales_order_no ='".$request->sales_order_no."' group by FG.sales_order_no, FG.color_id, FG.size_id order by FG.color_id asc, FG.size_id asc");
           //dd(DB::getQueryLog());     
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
     
     $html.=' 
                                    <table id="tbl" class="table table-bordered   nowrap w-100">
                                          <thead>
                                            <tr style="text-align:center; white-space:nowrap">
											     
											    <th nowrap>Sales Order No</th>
												<th nowrap>Buyer Brand</th>
											    <th nowrap>Garment Color</th> 
                                                <th nowrap>Size</th> 
                                                <th nowrap>Packing GRN Qty</th> 
                                                <th nowrap>Carton Paking Qty</th>
                                                <th nowrap>O2O Transfer Qty</th>
                                                <th nowrap> Location Transfer Qty</th>
                                                <th nowrap>FG Stock</th>
                                              
                                            </tr>
                                            </thead>';
                                            
                                            foreach($FinishedGoodsStock as $FG)
                                            {
                                                if(($FG->packing_grn_qty - $FG->carton_pack_qty- $FG->transfer_qty- $FG->loc_transfer_qty) > 0)
                                                {
                                                    $html.='<tr>';
                                                     $html.='<td nowrap>'.$FG->sales_order_no.'</td>';
                                                     $html.='<td nowrap>'.$FG->brand_name.'</td>';
                                                     $html.='<td nowrap>'.$FG->color_name.'</td>';
                                                     $html.='<td nowrap>'.$FG->size_name.'</td>';
                                                     $html.='<td nowrap>'.$FG->packing_grn_qty.'</td>';
                                                     $html.='<td nowrap>'.$FG->carton_pack_qty.'</td>';
                                                     $html.='<td nowrap>'.$FG->transfer_qty.'</td>';
                                                     $html.='<td nowrap>'.$FG->loc_transfer_qty.'</td>';
                                                    //  $html.='<td>'.($FG->packing_grn_qty - $FG->carton_pack_qty- $FG->transfer_qty- $FG->loc_transfer_qty + $FG->loc_rec_transfer_qty ).'</td>';
                                                     $html.='<td nowrap>'.($FG->packing_grn_qty - $FG->carton_pack_qty- $FG->transfer_qty- $FG->loc_transfer_qty).'</td>';
                                                   $html.='</tr>';
                                                }
                                            }
        
                                            $html.='<tbody>
                                         
                                            </tbody>
                                        </table>
                                       ';
                        
                        
                          return response()->json(['html' => $html]);
  }
  
  
  function LocTransferPackingPrint($ltpki_code)
  {
      
       //   DB::enableQueryLog();
       
         $LocTransferPackingInhouseMaster = LocTransferPackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'loc_transfer_packing_inhouse_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'loc_transfer_packing_inhouse_master.Ac_code')
         ->join('location_master as loc1', 'loc1.loc_id', '=', 'loc_transfer_packing_inhouse_master.from_loc_id')
         ->join('location_master as loc2', 'loc2.loc_id', '=', 'loc_transfer_packing_inhouse_master.to_loc_id')
        ->where('loc_transfer_packing_inhouse_master.ltpki_code', $ltpki_code)
         ->get(['loc_transfer_packing_inhouse_master.*','usermaster.username','ledger_master.Ac_name','loc_transfer_packing_inhouse_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address','loc1.location as fromlocation','loc2.location as tolocation' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
       
       $SalesOrderList=explode(",", $LocTransferPackingInhouseMaster[0]->sales_order_no);
       $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
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
          $LocTransferPackingList = DB::select("SELECT    loc_transfer_packing_inhouse_size_detail.sales_order_no,
          loc_transfer_packing_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from  loc_transfer_packing_inhouse_size_detail 
          inner join color_master on color_master.color_id=	loc_transfer_packing_inhouse_size_detail.color_id 
        where ltpki_code='".$LocTransferPackingInhouseMaster[0]->ltpki_code."' 
        group by loc_transfer_packing_inhouse_size_detail.sales_order_no,loc_transfer_packing_inhouse_size_detail.color_id");
        //           $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('FGLocationTransferOutwardMasterPrint', compact('LocTransferPackingInhouseMaster','LocTransferPackingList','SizeDetailList','FirmDetail','LocationList'));
  }
  
  public function LTFG_GetRawData(Request $request) 
  {
    //   $SalesOrder=$request->sales_order_no;
    //   echo $SalesOrder;
    // DB::enableQueryLog();  
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')
      ->where('buyer_purchse_order_master.tr_code',$request->sales_order_no)->first();
     
     
       $SalesOrderList = BuyerPurchaseOrderMasterModel::select('tr_code as sales_order_no')->get();
    
    // DB::enableQueryLog();  
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::select('tr_code as sales_order_no', 'size_array')
      ->where('buyer_purchase_order_detail.tr_code',$request->sales_order_no)->first();
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      
      $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
      $sizes='';
      $sizes_id = '';
      $no=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizes=$sizes.'s'.$no.',';
          $sizes_id =$sizes_id.$sz->size_id.',';
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
      $sizes_Arr=rtrim($sizes_id,',');
        // DB::enableQueryLog();  
        $colorList=DB::select("select buyer_purchase_order_detail.color_id, color_name from buyer_purchase_order_detail
        inner join color_master on color_master.color_id=buyer_purchase_order_detail.color_id
        where tr_code='".$request->sales_order_no."'");
      $MasterdataList = DB::select("select ".$sizes." from sales_order_detail where tr_code='".$request->sales_order_no."'");

//  $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
       $html = '';
      $html .= '   
      <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
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
                  <th>Add/Remove</th>
                  </tr>
              </thead>
              <tbody  id="CartonData">';
          $no=1;
          
        
          $html .='<tr>';
          $html .='
          <td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;height:30px;"/></td>';
                
      
      
        $html.=' <td>
        <input  name="item_codef[]"  type="hidden" id="item_code" value="" required />
        
        <select name="color_id[]" class="select2-select select2"  id="color_id0" style="width:250px; height:30px;" onchange="setFGLimit(this);" sales_order_no="'.$request->sales_order_no.'" size_array="'.$sizes_Arr.'" required>
        <option value="">--Select Color--</option>';
         foreach ($colorList as $color) 
                  {
                     $html.='<option value="'.$color->color_id.'"';
           
                    $html.='>'.$color->color_name.'</option>';
                  }
        
        
        $html.='</select></td>';
    
      
          if(isset($MasterdataList[0]->s1)) { $html.='<td>  <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);"  name="s1[]" class="size_id" type="number" id="s1" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s2)) { $html.='<td>  <input style="width:80px; float:left;"   min="0"  onchange="checkNumber(this);" name="s2[]" type="number" class="size_id" id="s2" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s3)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" onchange="checkNumber(this);" name="s3[]" type="number" class="size_id" id="s3" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s4)) { $html.='<td>  <input style="width:80px; float:left;"  min="0" onchange="checkNumber(this);" name="s4[]" type="number" class="size_id" id="s4" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s5)) { $html.='<td>  <input style="width:80px; float:left;" min="0" onchange="checkNumber(this);" name="s5[]" type="number" class="size_id" id="s5" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s6)) { $html.='<td>  <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);"  name="s6[]" type="number" class="size_id" id="s6" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s7)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s7[]" type="number" class="size_id" id="s7" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s8)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);"  name="s8[]" type="number" class="size_id" id="s8" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s9)) { $html.='<td> <input style="width:80px; float:left;"  min="0" onchange="checkNumber(this);"  name="s9[]" type="number" class="size_id" id="s9" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s10)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s10[]" type="number" class="size_id" id="s10" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s11)) { $html.='<td> <input style="width:80px; float:left;"  min="0"   onchange="checkNumber(this);" name="s11[]" type="number" class="size_id" id="s11" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s12)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s12[]" type="number" class="size_id" id="s12" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s13)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s13[]" type="number" class="size_id" id="s13" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s14)) { $html.='<td><input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s14[]" type="number" class="size_id" id="s14" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s15)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s15[]" type="number" class="size_id" id="s15" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s16)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s16[]" type="number" class="size_id" id="s16" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s17)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s17[]" type="number" class="size_id" id="s17" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s18)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s18[]" type="number" class="size_id" id="s18" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s19)) { $html.='<td> <input style="width:80px; float:left;" min="0"   onchange="checkNumber(this);" name="s19[]" type="number" class="size_id" id="s19" value="0" required /> <span></span></td>';}
          if(isset($MasterdataList[0]->s20)) { $html.='<td> <input style="width:80px; float:left;"  min="0"  onchange="checkNumber(this);" name="s20[]" type="number" class="size_id" id="s20" value="0" required /> <span></span></td>';}
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
    
      $size_array = $BuyerPurchaseOrderDetailList->size_array;

      return response()->json(['html' => $html,'size_array' => $size_array]);
         
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
     
public function FG_GetColorList(Request $request)
{
    $sales_order_no=$request->sales_order_no;
    $main_sales_order_no=$request->main_sales_order_no;
    
    //  DB::enableQueryLog();  
      

    $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id','color_name')
    ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
    ->where('tr_code','=',$sales_order_no)
    ->whereRaw("buyer_purchase_order_detail.color_id in (select distinct color_id from buyer_purchase_order_detail where tr_code='".$request->main_sales_order_no."')")
    ->DISTINCT()->get();
   
    //   $query = DB::getQueryLog();
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
     
     
     
     
     
     
    //   public function LocTransferPackingPrint($ltpki_code)
    // {
        
         
    // //   DB::enableQueryLog();
       
    //      $TransferPackingInhouseMaster = LocTransferPackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'loc_transfer_packing_inhouse_master.userId')
    //      ->join('ledger_master', 'ledger_master.Ac_code', '=', 'loc_transfer_packing_inhouse_master.Ac_code')
    //     ->where('loc_transfer_packing_inhouse_master.ltpki_code', $ltpki_code)
    //      ->get(['loc_transfer_packing_inhouse_master.*','usermaster.username','ledger_master.Ac_name','loc_transfer_packing_inhouse_master.sales_order_no',
    //      'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
    //     //         $query = DB::getQueryLog();
    //     //     $query = end($query);
    //     //   dd($query);
       
    //   $SalesOrderList=explode(",", $TransferPackingInhouseMaster[0]->sales_order_no);
        
    //     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::whereIn('tr_code',$SalesOrderList)->get();
                   
        
    //     $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList[0]->sz_code)->get();
    //     $sizes='';
    //     $no=1;
    //     foreach ($SizeDetailList as $sz) 
    //     {
    //         $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
    //         $no=$no+1;
    //     }
    //     $sizes=rtrim($sizes,',');
    //     //   DB::enableQueryLog();  
    //       $TransferPackingList = DB::select("SELECT   	loc_transfer_packing_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
    //     sum(size_qty_total) as size_qty_total  from 	loc_transfer_packing_inhouse_size_detail 
        
    //     inner join color_master on color_master.color_id=	loc_transfer_packing_inhouse_size_detail.color_id 
    //     where ltpki_code='".$TransferPackingInhouseMaster[0]->ltpki_code."' group by 	loc_transfer_packing_inhouse_size_detail.color_id");
    //     //           $query = DB::getQueryLog();
    //     //   $query = end($query);
    //     //   dd($query);
    //     $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
    //          return view('LocTransferPackingPrint', compact('TransferPackingInhouseMaster','TransferPackingList','SizeDetailList','FirmDetail'));
      
    // }
     
      
        public function FGStockReport(Request $request)
    {
        $ltpki_code='';
        $CPKIList=DB::select("select
  `sale_code`,
   SUBSTRING_INDEX(SUBSTRING_INDEX(sale_transaction_master.transfer_packing_nos, ',', numbers.n), ',', -1) as ltpki_code
from
  numbers inner join sale_transaction_master
  on CHAR_LENGTH(sale_transaction_master.transfer_packing_nos)
     -CHAR_LENGTH(REPLACE(sale_transaction_master.transfer_packing_nos, ',', ''))>=numbers.n-1
order by
  sale_code, n");    
        foreach($CPKIList as $codes)
        {
            $ltpki_code=$ltpki_code."'".$codes->ltpki_code."',";
        }
        $ltpki_code=rtrim($ltpki_code,",");
  //echo $ltpki_code;;
 if ($request->ajax()) {
            
            
        
            
            
           // $ltpki_codes=explode(",",$CPKIList->ltpki_code);
          //  DB::enableQueryLog();  
          
        $FinishedGoodsStock = DB::select("SELECT  Ac_name, sales_order_no, packing_inhouse_size_detail2.style_no, color_master.color_name,color_master.style_img_path, brand_master.brand_name, 
        size_detail.size_name, ifnull(sum(packing_inhouse_size_detail2.size_qty),0) as 'packing_grn_qty', mainstyle_name,
        (SELECT ifnull(sum(size_qty),0) from loc_transfer_packing_inhouse_size_detail2 
        where color_id=packing_inhouse_size_detail2.color_id and 
        sales_order_no=packing_inhouse_size_detail2.sales_order_no 
        and size_id=packing_inhouse_size_detail2.size_id
        and  loc_transfer_packing_inhouse_size_detail2.ltpki_code in ($ltpki_code)) as 'carton_pack_qty', order_rate
        FROM `packing_inhouse_size_detail2`
        LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code=packing_inhouse_size_detail2.sales_order_no
        LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
        LEFT JOIN ledger_master on ledger_master.ac_code=packing_inhouse_size_detail2.Ac_code
        LEFT JOIN color_master on color_master.color_id=packing_inhouse_size_detail2.color_id
        LEFT JOIN size_detail on size_detail.size_id = packing_inhouse_size_detail2.size_id
        LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_inhouse_size_detail2.mainstyle_id
        GROUP by packing_inhouse_size_detail2.sales_order_no, packing_inhouse_size_detail2.color_id, packing_inhouse_size_detail2.size_id");

        //   $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        
        return Datatables::of($FinishedGoodsStock)
        ->addIndexColumn()
        ->addColumn('Carton_Paking_Qty',function ($row) {

         $TransferPackingQty =($row->packing_grn_qty - $row->carton_pack_qty);

         return $TransferPackingQty;
       })
      ->addColumn('Value',function ($row) {

         $Value =($row->packing_grn_qty - $row->carton_pack_qty) * ($row->order_rate);

         return $Value;
       })
       
         ->rawColumns(['Carton_Paking_Qty','Value'])
         
         ->make(true);

        }
        
      return view('FGStockReport');
        
    }
     
     
    public function destroy($id)
    {
        DB::table('loc_transfer_packing_inhouse_master')->where('ltpki_code', $id)->delete();
        DB::table('loc_transfer_packing_inhouse_size_detail2')->where('ltpki_code', $id)->delete();
        DB::table('loc_transfer_packing_inhouse_size_detail')->where('ltpki_code', $id)->delete();
        DB::table('loc_transfer_packing_inhouse_detail')->where('ltpki_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
}
