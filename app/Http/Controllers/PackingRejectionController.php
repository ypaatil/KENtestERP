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
use App\Models\PackingRejectionMasterModel;
use App\Models\PackingRejectionDetailModel;
use App\Models\PackingRejectionSizeDetailModel;
use App\Models\LineModel;
use Session;
use DataTables;

class PackingRejectionController extends Controller
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
      
        $PackingRejectionMasterList = PackingRejectionMasterModel::join('usermaster', 'usermaster.userId', '=', 'packing_rejection_master.userId', 'left outer')
        ->join('ledger_master as L1', 'L1.Ac_code', '=', 'packing_rejection_master.Ac_code', 'left outer')
        ->join('ledger_master as L2', 'L2.Ac_code', '=', 'packing_rejection_master.vendorId', 'left outer')
        ->where('packing_rejection_master.delflag','=', '0')
        ->where('packing_rejection_master.qcp_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 2 MONTH)'))
        ->get(['packing_rejection_master.*','usermaster.username','L1.Ac_name','L2.Ac_name as vendor_name']); 
        if ($request->ajax()) 
        {
            return Datatables::of($PackingRejectionMasterList)
            ->addIndexColumn()
            ->addColumn('qcp_code1',function ($row) {
        
                 $qcp_codeData =substr($row->qcp_code,4,15);
        
                 return $qcp_codeData;
            })  
            ->addColumn('action1', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1)
                {  
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('PackingRejection.edit', $row->qcp_code).'" >
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
            ->addColumn('action2', function ($row) use ($chekform){
         
                if($chekform->delete_access==1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->qcp_code.'"  data-route="'.route('PackingRejection.destroy', $row->qcp_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                return $btn4;
            })
            ->rawColumns(['action1','action2'])
    
            ->make(true);
        } 
    
        return view('PackingRejectionMasterList', compact('PackingRejectionMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='PackingRejection'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
         
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $vendorId=Session::get('vendorId');
       
        $packingOrderList= DB::select('SELECT vpo_code,sales_order_no FROM vendor_purchase_order_master where delflag=0 AND process_id=3');
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
      
        return view('PackingRejectionMaster',compact( 'ItemList', 'MainStyleList','SubStyleList','FGList','BuyerList', 'packingOrderList','Ledger',  'counter_number'));
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
        ->where('type','=','PackingRejection')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        $this->validate($request, [
                'qcp_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'vendorId'=>'required',
                'mainstyle_id'=>'required',
        ]);
 
 
        $data1=array(
            'qcp_code'=>$TrNo, 
            'qcp_date'=>$request->qcp_date, 
            'sales_order_no'=>$request->sales_order_no,
            'Ac_code'=>$request->Ac_code, 
            'vendorId'=>$request->vendorId,
            'vpo_code'=>$request->vpo_code,
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
 
        PackingRejectionMasterModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='PackingRejection'");
    
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
          
                    'qcp_code'=>$TrNo,
                    'qcp_date'=>$request->qcp_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'vpo_code'=>$request->vpo_code,
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
              
                    'qcp_code'=>$TrNo, 
                    'qcp_date'=>$request->qcp_date, 
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId, 
                    'vpo_code'=>$request->vpo_code,
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
            PackingRejectionDetailModel::insert($data2);
        }
        if($data3 != "")
        {
            PackingRejectionSizeDetailModel::insert($data3);
        }
    }
    
        
    $InsertSizeData=DB::select('call AddSizeQtyFromPackingRejection("'.$TrNo.'")');
           
    return redirect()->route('PackingRejection.index')->with('message', 'Data Saved Succesfully');  
      
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
           
            $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='PackingRejection'");
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
            $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
            $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
            $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
            $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get(); 
          
            $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get(); 
           
            $packingOrderList= DB::select('SELECT vpo_code,sales_order_no FROM vendor_purchase_order_master where delflag=0 AND process_id=3');
        
            //   DB::enableQueryLog();
            $PackingRejectionMasterList = PackingRejectionMasterModel::find($id);
             
            $PackingRejectionDetailList = PackingRejectionDetailModel::where('packing_rejection_detail.qcp_code','=', $PackingRejectionMasterList->qcp_code)->get(); 
            
            $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($PackingRejectionMasterList->sales_order_no);
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
            $MasterdataList = DB::select("SELECT vendor_purchase_order_size_detail.item_code, vendor_purchase_order_size_detail.color_id, color_name, ".$sizes.", 
              sum(size_qty_total) as size_qty_total from vendor_purchase_order_size_detail inner join color_master on 
              color_master.color_id=vendor_purchase_order_size_detail.color_id where vpo_code='".$PackingRejectionMasterList->vpo_code."'
              group by vendor_purchase_order_size_detail.color_id");
            
            $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
                ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
                ->where('tr_code','=',$PackingRejectionMasterList->sales_order_no)->DISTINCT()->get();
                
            return view('PackingRejectionMasterEdit',compact('PackingRejectionDetailList','ColorList','packingOrderList', 'BuyerList', 'MasterdataList','SizeDetailList','PackingRejectionMasterList', 'MainStyleList','SubStyleList','FGList', 'Ledger','counter_number' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $qcp_code)
    {
            $this->validate($request, [ 
                'qcp_date'=> 'required'
            ]);
         
          
            $data1=array(
                'qcp_code'=>$request->qcp_code, 
                'qcp_date'=>$request->qcp_date, 
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'vendorId'=>$request->vendorId,
                'vpo_code'=>$request->vpo_code,
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
        $PackingRejectionList = PackingRejectionMasterModel::findOrFail($request->qcp_code); 
        //  $query = DB::getQueryLog();
        //         $query = end($query);
        //         dd($query);
        $PackingRejectionList->fill($data1)->save();
        
         
        DB::table('packing_rejection_size_detail')->where('qcp_code', $request->input('qcp_code'))->delete();
        DB::table('packing_rejection_size_detail2')->where('qcp_code', $request->input('qcp_code'))->delete();
        DB::table('packing_rejection_detail')->where('qcp_code', $request->input('qcp_code'))->delete();
         $data2 = array();
         $data3 = array();
         $color_id= $request->input('color_id');
            if(count($color_id)>0)
            {   
            
            for($x=0; $x<count($color_id); $x++) {
                # code...
              if($request->size_qty_total[$x]>0)
              {
                    $data2[]=array(
          
                    'qcp_code'=>$request->qcp_code,
                    'qcp_date'=>$request->qcp_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'vpo_code'=>$request->vpo_code,
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
                  
                        'qcp_code'=>$request->qcp_code,
                        'qcp_date'=>$request->qcp_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'vpo_code'=>$request->vpo_code,
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
              }  
            } 
          if($data2 != "")
          {
                PackingRejectionDetailModel::insert($data2);
          }
          if($data3 != "")
          {
                PackingRejectionSizeDetailModel::insert($data3);
          }
          
    }   
           
     $InsertSizeData=DB::select('call AddSizeQtyFromPackingRejection("'.$request->qcp_code.'")');
           
           
     return redirect()->route('PackingRejection.index')->with('message', 'Data Updated Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
    public function GetPackingOrderDetails(Request $request)
    { 
        $vpo_code= $request->input('vpo_code');
        $MasterdataList = DB::select("select Ac_code,sales_order_no, vendorId, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from vendor_purchase_order_master where vendor_purchase_order_master.delflag=0 AND process_id =3 AND vpo_code='".$vpo_code."'");
        return json_encode($MasterdataList);
    }   
      
    public function Packing_GetOrderQty(Request $request)
    {
          $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($request->tr_code);
        //   DB::enableQueryLog();  
          $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::where('tr_code',$request->tr_code)->first();
           
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
        //DB::enableQueryLog();
          $MasterdataList = DB::select("SELECT packing_inhouse_size_detail.sales_order_no, packing_inhouse_size_detail.vpo_code,packing_inhouse_size_detail.item_code, packing_inhouse_size_detail.color_id, color_name, ".$sizes.",
          sum(size_qty_total) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
          color_master.color_id=packing_inhouse_size_detail.color_id where packing_inhouse_size_detail.sales_order_no='".$request->tr_code."' AND packing_inhouse_size_detail.vpo_code='".$request->vpo_code."'
          group by packing_inhouse_size_detail.color_id");
          // dd(DB::getQueryLog());
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
            <input  type="hidden" id="item_code" value="'.$row->item_code.'" required />';
            
            $ColorList = DB::table('color_master')->select('color_id', 'color_name')->where('color_id','=',$row->color_id)->first();
             $html.='<input type="text" class="form-control" value="'.$ColorList->color_name.'" readonly/></td>';
             
                 $sizex='';
          $nox=1;
          foreach ($SizeDetailList as $sz) 
          {
              $sizex=$sizex.'sum(s'.$nox.') as s_'.$nox.',';
              $nox=$nox+1;
          }
          $sizex=rtrim($sizex,',');
          // DB::enableQueryLog();
          $CompareList = DB::select("SELECT packing_rejection_size_detail.color_id, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from packing_rejection_size_detail where packing_rejection_size_detail.color_id='".$row->color_id."'
          AND packing_rejection_size_detail.sales_order_no='".$row->sales_order_no."'
          and packing_rejection_size_detail.vpo_code='".$request->vpo_code."'");
          //dd(DB::getQueryLog());
     
            foreach($CompareList as $List)
            {
               if(isset($row->s1)) { $s1=($row->s1 - (intval($List->s_1))); }
               if(isset($row->s2)) { $s2=($row->s2 - (intval($List->s_2))); }
               if(isset($row->s3)) { $s3=($row->s3 - (intval($List->s_3))); }
               if(isset($row->s4)) { $s4=($row->s4 - (intval($List->s_4))); }
               if(isset($row->s5)) { $s5=($row->s5 - (intval($List->s_5))); }
               if(isset($row->s6)) { $s6=($row->s6 - (intval($List->s_6))); }
               if(isset($row->s7)) { $s7=($row->s7 - (intval($List->s_7)));}
               if(isset($row->s8)) { $s8=($row->s8 - (intval($List->s_8)));}
               if(isset($row->s9)) { $s9=($row->s9 - (intval($List->s_9)));}
               if(isset($row->s10)) { $s10=($row->s10 - (intval($List->s_10)));}
               if(isset($row->s11)) { $s11=($row->s11 - (intval($List->s_11)));}
               if(isset($row->s12)) { $s12=($row->s12 - (intval($List->s_12)));}
               if(isset($row->s13)) { $s13=($row->s13 - (intval($List->s_13)));}
               if(isset($row->s14)) { $s14=($row->s14 - (intval($List->s_14)));}
               if(isset($row->s15)) { $s15=($row->s15 - (intval($List->s_15)));}
               if(isset($row->s16)) {$s16=($row->s16 - (intval($List->s_16)));}
               if(isset($row->s17)) { $s17=($row->s17 - (intval($List->s_17)));}
               if(isset($row->s18)) { $s18=($row->s18 - (intval($List->s_18)));}
               if(isset($row->s19)) { $s19=($row->s19 - (intval($List->s_19)));}
               if(isset($row->s20)) { $s20=($row->s20 - (intval($List->s_20)));}
                
                
            }
            $total_qty=0;
              if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.'</td>';}
              if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.'</td>';}
              if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.'</td>';}
              if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.'</td>';}
              if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.'</td>';}
              if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.'</td>';}
              if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.'</td>';}
              if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.'</td>';}
              if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.'</td>';}
              if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.'</td>';}
              if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.'</td>';}
              if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.'</td>';}
              if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.'</td>';}
              if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.'</td>';}
              if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.'</td>';}
              if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.'</td>';}
              if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.'</td>';}
              if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.'</td>';}
              if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.'</td>';}
              if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.'</td>';}
           
                  
                 
             
              $html.='<td>'.($total_qty - $List->size_qty_total).' 
            <input type="hidden"  value="" id="size_qty_array" style="width:80px; float:left;"  />
            <input type="hidden"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
              
              </td> 
              </tr>';
    
              $no=$no+1;
            }
              $html.=' 
                </tbody>';
                $szcount = [];
                $tdcount = count($SizeDetailList);
                for($i=0; $i < $tdcount; $i++)
                {
                    $szcount[] = 0; 
                }
                $commaSeparatedValues = implode(',', $szcount);
                $html.='</table><input type="hidden" name="allTotal" value="'.htmlspecialchars($commaSeparatedValues).'" id="allTotal"><input type="hidden" name="sumAllTotal" value="" id="sumAllTotal">';
    
    
          return response()->json(['html' => $html]);
             
    }
    
    public function PackingRejectionOrderQty(Request $request)
    {
        
          $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($request->tr_code);
        //   DB::enableQueryLog();  
          $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::where('tr_code',$request->tr_code)->first();
           
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
        
          $MasterdataList = DB::select("SELECT packing_inhouse_size_detail.sales_order_no, packing_inhouse_size_detail.vpo_code,packing_inhouse_size_detail.item_code, packing_inhouse_size_detail.color_id, color_name, ".$sizes.",
          sum(size_qty_total) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
          color_master.color_id=packing_inhouse_size_detail.color_id where packing_inhouse_size_detail.sales_order_no='".$request->tr_code."' AND packing_inhouse_size_detail.vpo_code='".$request->vpo_code."'
          group by packing_inhouse_size_detail.color_id");
           
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
            <input  name="item_codef[]"  type="hidden" id="item_code" value="'.$row->item_code.'" required />';
            
            $ColorList = DB::table('color_master')->select('color_id', 'color_name')->where('color_id','=',$row->color_id)->first();
             $html.='<input  name="color_id[]"  type="hidden" id="color_id" value="'.$row->color_id.'" required />
             <input type="text" class="form-control" style="width:150px;" value="'.$ColorList->color_name.'" readonly/></td>';
             
          $sizex='';
          $nox=1;
          foreach ($SizeDetailList as $sz) 
          {
              $sizex=$sizex.'sum(s'.$nox.') as s_'.$nox.',';
              $nox=$nox+1;
          }
          $sizex=rtrim($sizex,',');
          // DB::enableQueryLog();
          $CompareList = DB::select("SELECT packing_rejection_size_detail.color_id, ".$sizex.", 
          sum(size_qty_total) as size_qty_total from packing_rejection_size_detail where packing_rejection_size_detail.color_id='".$row->color_id."'
          AND packing_rejection_size_detail.sales_order_no='".$row->sales_order_no."'
          and packing_rejection_size_detail.vpo_code='".$request->vpo_code."'");
          //dd(DB::getQueryLog());
     
            foreach($CompareList as $List)
            {
               if(isset($row->s1)) { $s1=($row->s1 - (intval($List->s_1))); }
               if(isset($row->s2)) { $s2=($row->s1 - (intval($List->s_2))); }
               if(isset($row->s3)) { $s3=($row->s1 - (intval($List->s_3))); }
               if(isset($row->s4)) { $s4=($row->s1 - (intval($List->s_4))); }
               if(isset($row->s5)) { $s5=($row->s1 - (intval($List->s_5))); }
               if(isset($row->s6)) { $s6=($row->s1 - (intval($List->s_6))); }
               if(isset($row->s7)) { $s7=($row->s1 - (intval($List->s_7)));}
               if(isset($row->s8)) { $s8=($row->s1 - (intval($List->s_8)));}
               if(isset($row->s9)) { $s9=($row->s1 - (intval($List->s_9)));}
               if(isset($row->s10)) { $s10=($row->s1 - (intval($List->s_10)));}
               if(isset($row->s11)) { $s11=($row->s1 - (intval($List->s_11)));}
               if(isset($row->s12)) { $s12=($row->s1 - (intval($List->s_12)));}
               if(isset($row->s13)) { $s13=($row->s1 - (intval($List->s_13)));}
               if(isset($row->s14)) { $s14=($row->s1 - (intval($List->s_14)));}
               if(isset($row->s15)) { $s15=($row->s1 - (intval($List->s_15)));}
               if(isset($row->s16)) {$s16=($row->s1 - (intval($List->s_16)));}
               if(isset($row->s17)) { $s17=($row->s1 - (intval($List->s_17)));}
               if(isset($row->s18)) { $s18=($row->s1 - (intval($List->s_18)));}
               if(isset($row->s19)) { $s19=($row->s1 - (intval($List->s_19)));}
               if(isset($row->s20)) { $s20=($row->s1 - (intval($List->s_20)));}
                
                
            }
            $total_qty=0;
            $total_qty1=0;
            if(isset($row->s1)) {$total_qty1=$total_qty1+round($row->s1);}
            if(isset($row->s2)) {$total_qty1=$total_qty1+round($row->s2);}
            if(isset($row->s3)) {$total_qty1=$total_qty1+round($row->s3);}
            if(isset($row->s4)) {$total_qty1=$total_qty1+round($row->s4);}
            if(isset($row->s5)) {$total_qty1=$total_qty1+round($row->s5);}
            if(isset($row->s6)) {$total_qty1=$total_qty1+round($row->s6);}
            if(isset($row->s7)) {$total_qty1=$total_qty1+round($row->s7);}
            if(isset($row->s8)) {$total_qty1=$total_qty1+round($row->s8);}
            if(isset($row->s9)) {$total_qty1=$total_qty1+round($row->s9);}
            if(isset($row->s10)) {$total_qty1=$total_qty1+round($row->s10);}
            if(isset($row->s11)) {$total_qty1=$total_qty1+round($row->s11);}
            if(isset($row->s12)) {$total_qty1=$total_qty1+round($row->s12);}
            if(isset($row->s13)) {$total_qty1=$total_qty1+round($row->s13);}
            if(isset($row->s14)) {$total_qty1=$total_qty1+round($row->s14);}
            if(isset($row->s15)) {$total_qty1=$total_qty1+round($row->s15);}
            if(isset($row->s16)) {$total_qty1=$total_qty1+round($row->s16);}
            if(isset($row->s17)) {$total_qty1=$total_qty1+round($row->s17);}
            if(isset($row->s18)) {$total_qty1=$total_qty1+round($row->s18);}
            if(isset($row->s19)) {$total_qty1=$total_qty1+round($row->s19);}
            if(isset($row->s20)) {$total_qty1=$total_qty1+round($row->s20);}
            
            if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
            if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
            if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
            if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
            if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
            if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
            if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
            if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
            if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
            if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
            if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
            if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
            if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
            if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
            if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
            if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
            if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
            if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
            if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
            if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.' <input style="width:80px; float:left;" max="'.($total_qty1 - $List->size_qty_total).'" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
   
            $html.='<td>'.($total_qty - $List->size_qty_total).'  
                        <input type="hidden" name="overall_size_qty" value="'.($total_qty - $List->size_qty_total).'" class="overall_size_qty" style="width:80px; float:left;"> 
                        <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" required readOnly  />
                        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
                        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
                        <input type="hidden" name="is_transfered[]"   value="" id="is_transfered" style="width:80px; height:30px; float:left;" required readOnly  />
                        <input type="hidden" name="trans_sales_order_no[]"  value="" id="trans_sales_order_no" style="width:80px; float:left;"  />
                        <input type="hidden" name="transfer_code[]"  value="" id="transfer_code" style="width:80px;  float:left;"  />
                    </td> 
              </tr>';
    
              $no=$no+1;
            }
              $html.=' 
            </tbody>';
            $szcount = [];
            $tdcount = count($SizeDetailList);
            for($i=0; $i < $tdcount; $i++)
            {
                $szcount[] = 0; 
            }
            $commaSeparatedValues = implode(',', $szcount);
            $html.='</table><input type="hidden" name="allTotal" value="'.htmlspecialchars($commaSeparatedValues).'" id="allTotal"><input type="hidden" name="sumAllTotal" value="" id="sumAllTotal">';
    
            
          return response()->json(['html' => $html]);
    }
    
      
    public function destroy($id)
    {
        DB::table('packing_rejection_master')->where('qcp_code', $id)->delete();
        DB::table('packing_rejection_detail')->where('qcp_code', $id)->delete(); 
        DB::table('packing_rejection_size_detail')->where('qcp_code', $id)->delete();
        DB::table('packing_rejection_size_detail2')->where('qcp_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
     
    public function PackingRejectionReport(Request $request)
    {
        //DB::enableQueryLog();
        $PackingRejectDetails = DB::select("SELECT packing_rejection_master.qcp_code,packing_rejection_size_detail2.color_id,packing_rejection_size_detail2.size_id, 
        packing_rejection_master.qcp_date, packing_rejection_master.sales_order_no, packing_rejection_size_detail2.vpo_code, 
        ledger_master.Ac_name, L1.Ac_name as vendorName, mainstyle_name,packing_rejection_size_detail2.style_no, 
        color_master.color_name, brand_master.brand_name, 
        size_detail.size_name, ifnull(packing_rejection_size_detail2.size_qty,0) as 'qty'
     
        FROM `packing_rejection_size_detail2`
        INNER JOIN packing_rejection_master ON packing_rejection_master.qcp_code = packing_rejection_size_detail2.qcp_code
        LEFT JOIN buyer_purchse_order_master on buyer_purchse_order_master.tr_code=packing_rejection_size_detail2.sales_order_no
        LEFT JOIN brand_master on brand_master.brand_id=buyer_purchse_order_master.brand_id
        LEFT JOIN ledger_master on ledger_master.ac_code=packing_rejection_size_detail2.Ac_code
        LEFT JOIN ledger_master as L1 on L1.ac_code=packing_rejection_size_detail2.vendorId
        LEFT JOIN color_master on color_master.color_id=packing_rejection_size_detail2.color_id
        LEFT JOIN size_detail on size_detail.size_id = packing_rejection_size_detail2.size_id 
        LEFT JOIN main_style_master on main_style_master.mainstyle_id=packing_rejection_size_detail2.mainstyle_id 
        WHERE packing_rejection_master.delflag=0 GROUP BY packing_rejection_size_detail2.size_id"); 
       //dd(DB::getQueryLog());
 
        if ($request->ajax()) 
        {
            $data = Datatables::of($PackingRejectDetails)
                ->addIndexColumn()
                ->addColumn('TotalQty', function ($row) {
                    return $row->qty;
                })
                ->rawColumns(['TotalQty'])
                ->make(true);
            
            // Log the data to verify it's correct
            \Log::info($data);
            return $data;
        }
        
    
        return view('PackingRejectionReport');
        
    }
}
