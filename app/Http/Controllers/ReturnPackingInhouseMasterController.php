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
use App\Models\StitchingInhouseMasterModel;
use App\Models\StitchingInhouseDetailModel;
use App\Models\StitchingInhouseSizeDetailModel;
use App\Models\FinishingInhouseMasterModel;
use App\Models\FinishingInhouseDetailModel;
use App\Models\FinishingInhouseSizeDetailModel;
use App\Models\PackingInhouseMasterModel;
use App\Models\PackingInhouseDetailModel;
use App\Models\ReturnPackingInhouseMasterModel;
use App\Models\ReturnPackingInhouseDetailModel;
use App\Models\ReturnPackingInhouseSizeDetailModel;
use App\Models\PackingInhouseSizeDetailModel;
use App\Models\LocationModel;
use Session;
use DataTables;

class ReturnPackingInhouseMasterController extends Controller
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
        ->where('form_id', '200')
        ->first();
        
        $vendorId=Session::get('vendorId');
        $user_type=Session::get('user_type');
        
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            //DB::enableQueryLog();
            $ReturnPackingInhouseMasterList = ReturnPackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'return_packing_inhouse_master.userId', 'left outer')
            ->join('ledger_master as L1', 'L1.Ac_code', '=', 'return_packing_inhouse_master.Ac_code', 'left outer')
             ->join('ledger_master as L2', 'L2.Ac_code', '=', 'return_packing_inhouse_master.vendorId', 'left outer')
             ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'return_packing_inhouse_master.sales_order_no')
            ->where('return_packing_inhouse_master.delflag','=', '0')
            ->get(['return_packing_inhouse_master.*','usermaster.username','L1.Ac_name','L2.Ac_name as vendor_name','sales_order_costing_master.sam']);
            //dd(DB::getQueryLog());
            if ($request->ajax()) 
            {
                return Datatables::of($ReturnPackingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('rpki_code1',function ($row) {
                     $rpki_code1 =substr($row->rpki_code,5,15);
            
                     return $rpki_code1;
                }) 
                ->addColumn('updated_at',function ($row) {
            
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
            
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="PrintReturnPackingInhouse/'.$row->rpki_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1)
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('ReturnPackingInhouseMaster.edit', $row->rpki_code).'" >
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
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->rpki_code.'"  data-route="'.route('ReturnPackingInhouseMaster.destroy', $row->rpki_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['rpki_code1','action1','action2','action3','updated_at'])
        
                ->make(true);
            }
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {
            $ReturnPackingInhouseMasterList = ReturnPackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'return_packing_inhouse_master.userId', 'left outer')
                ->join('ledger_master as L1', 'L1.Ac_code', '=', 'return_packing_inhouse_master.Ac_code', 'left outer')
                ->join('ledger_master as L2', 'L2.Ac_code', '=', 'return_packing_inhouse_master.vendorId', 'left outer')
                ->leftJoin('sales_order_costing_master', 'sales_order_costing_master.sales_order_no', '=', 'return_packing_inhouse_master.sales_order_no')
                ->where('return_packing_inhouse_master.delflag','=', '0')->where( 'return_packing_inhouse_master.vendorId',$vendorId)
                ->get(['return_packing_inhouse_master.*','usermaster.username','L1.Ac_name','L2.Ac_name as vendor_name','sales_order_costing_master.sam']);
            
            if ($request->ajax()) 
            {
                return Datatables::of($ReturnPackingInhouseMasterList)
                ->addIndexColumn()
                ->addColumn('rpki_code1',function ($row) {
            
                     $rpki_code1 =substr($row->rpki_code,4,15);
            
                     return $rpki_code1;
                }) 
                ->addColumn('updated_at',function ($row) {
            
                     $updated_at = date("d-m-Y", strtotime($row->updated_at));
            
                     return $updated_at;
                }) 
                ->addColumn('action1', function ($row) 
                {
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="PackingGRNPrint/'.$row->rpki_code.'" title="print">
                                <i class="fas fa-print"></i>
                                </a>';
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {  
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('ReturnPackingInhouseMaster.edit', $row->rpki_code).'" >
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
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->rpki_code.'"  data-route="'.route('ReturnPackingInhouseMaster.destroy', $row->rpki_code).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn4;
                })
                ->rawColumns(['rpki_code1','action1','action2','action3','updated_at'])
        
                ->make(true);
            }
        }
        return view('ReturnPackingInhouseMasterList', compact('ReturnPackingInhouseMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='ReturnPackingInhouse'");
       
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $vendorId=Session::get('vendorId');
        
       if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
           $VendorPurchaseOrderList= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')->where('process_id',3)->get();
           $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
 
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {  
            
            $VendorPurchaseOrderList= VendorPurchaseOrderModel::select('vendor_purchase_order_master.vpo_code','vendor_purchase_order_master.sales_order_no')->where('process_id',3)->where('vendor_purchase_order_master.vendorId',$vendorId)->get();
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
            
        }
       
       $SalesOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code as sales_order_no')->get();
        return view('ReturnPackingInhouseMaster',compact( 'ItemList', 'SalesOrderList','MainStyleList','SubStyleList','FGList','BuyerList', 'VendorPurchaseOrderList','Ledger',  'counter_number','gstlist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo '<pre>'; print_r($_POST);exit;
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
        ->where('c_name','=','C1')
        ->where('type','=','ReturnPackingInhouse')
        ->where('firm_id','=',1)
        ->first();
        $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
          
        $this->validate($request, [
             
                'rpki_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=>'required',
                'Ac_code'=>'required',
                'vendorId'=>'required',
                'mainstyle_id'=>'required',
                'vendor_rate'=>'required',
               
    ]);
 
    $is_opening=isset($request->is_opening) ? 1 : 0;
 
    $data1=array
    (
        'rpki_code'=>$TrNo, 
        'rpki_date'=>$request->rpki_date, 
        'sales_order_no'=>$request->sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'vendorId'=>$request->vendorId,
        'sale_code'=>$request->sale_code,
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
        'is_opening'=>$is_opening,
        'rate'=>$request->rate,
        'location_id'=>$request->location_id,
        'tax_type_id'=>$request->tax_type_id,
        'created_at'=>date("Y-m-d H:i")
     );
 
    ReturnPackingInhouseMasterModel::insert($data1);
    DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='ReturnPackingInhouse'");

    $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
      {
            $data2=array
            (
                'rpki_code'=>$TrNo,
                'rpki_date'=>$request->rpki_date,
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'vendorId'=>$request->vendorId,
                'sale_code'=>$request->sale_code,
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'item_code'=>$request->item_codef[$x],
                'color_id'=>$request->color_id[$x],
                'hsn_code'=> isset($request->hsn_code[$x]) ? $request->hsn_code[$x] : "",
                'size_array'=>$request->size_array[$x],
                'size_qty_array'=>$request->size_qty_array[$x],
                'size_qty_total'=>$request->size_qty_total[$x],
                'is_opening'=>$is_opening,
                // 'is_transfered'=>$request->is_transfered[$x],
                'trans_sales_order_no'=>$request->trans_sales_order_no[$x],
                'transfer_code'=>$request->transfer_code[$x],
                'location_id'=>$request->location_id,
                'rate'=>$request->rates[$x],
                'cgst'=>$request->cgst[$x],
                'camt'=>$request->camt[$x],
                'sgst'=>$request->sgst[$x],
                'samt'=>$request->samt[$x],
                'igst'=>$request->igst[$x],
                'iamt'=>$request->iamt[$x],
                'amount'=>$request->amount[$x],
                'total_amount'=>$request->total_amount[$x],
            );
             //DB::enableQueryLog();
             ReturnPackingInhouseDetailModel::insert($data2);
             //dd(DB::getQueryLog());   
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
          
                'rpki_code'=>$TrNo, 
                'rpki_date'=>$request->rpki_date, 
                'sales_order_no'=>$request->sales_order_no,
                'Ac_code'=>$request->Ac_code, 
                'vendorId'=>$request->vendorId,
                'sale_code'=>$request->sale_code,
                'mainstyle_id'=>$request->mainstyle_id,
                'substyle_id'=>$request->substyle_id,
                'fg_id'=>$request->fg_id,
                'style_no'=>$request->style_no,
                'style_description'=>$request->style_description,
                'item_code'=>$request->item_codef[$x],
                'color_id'=>$request->color_id[$x],
                'hsn_code'=> isset($request->hsn_code[$x]) ? $request->hsn_code[$x] : "",
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
                'vendor_rate'=>$request->vendor_rate,
                'is_opening'=>$is_opening,
                // 'is_transfered'=>$request->is_transfered[$x],
                'trans_sales_order_no'=>$request->trans_sales_order_no[$x],
                'transfer_code'=>$request->transfer_code[$x],
                'location_id'=>$request->location_id,
                'rate'=>$request->rates[$x],
                'cgst'=>$request->cgst[$x],
                'camt'=>$request->camt[$x],
                'sgst'=>$request->sgst[$x],
                'samt'=>$request->samt[$x],
                'igst'=>$request->igst[$x],
                'iamt'=>$request->iamt[$x],
                'amount'=>$request->amount[$x],
                'total_amount'=>$request->total_amount[$x],
                );
                  
                    
                  if($request->is_transfered[$x]==1){
                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail set usedFlag=1 
                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                  and tpki_code='".$request->transfer_code[$x]."' and
                  color_id='".$request->color_id[$x]."'");
                  
                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail2 set usedFlag=1 
                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                  and tpki_code='".$request->transfer_code[$x]."' and
                  color_id='".$request->color_id[$x]."'");
                  
                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_detail set usedFlag=1 
                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                  and tpki_code='".$request->transfer_code[$x]."' and
                  color_id='".$request->color_id[$x]."'");
                  }
                    
                 ReturnPackingInhouseSizeDetailModel::insert($data3);
              
              } // if loop avoid zero qty
            }
          
          
         
    }
    
        
   $InsertSizeData=DB::select('call AddSizeQtyFromReturnPackingInhouse("'.$TrNo.'")');
           
    return redirect()->route('ReturnPackingInhouseMaster.index')->with('message', 'Data Saved Succesfully');  
      
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


 public function PackingGRNPrint($pki_code)
    {
        
         
    //   DB::enableQueryLog();
       
         $PackingInhouseMaster = PackingInhouseMasterModel::join('usermaster', 'usermaster.userId', '=', 'return_packing_inhouse_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'return_packing_inhouse_master.vendorId')
         ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=','return_packing_inhouse_master.vpo_code')
        ->where('return_packing_inhouse_master.pki_code', $pki_code)
         ->get(['return_packing_inhouse_master.*','usermaster.username','ledger_master.Ac_name','return_packing_inhouse_master.sales_order_no',
         'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address' ]);
       
        //         $query = DB::getQueryLog();
        //     $query = end($query);
        //   dd($query);
       
        
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::where('tr_code',$PackingInhouseMaster[0]->sales_order_no)->get();
                   
        
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
          $PackingGRNList = DB::select("SELECT   item_master.item_name,	packing_inhouse_size_detail.color_id, color_master.color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total  from 	packing_inhouse_size_detail 
        inner join item_master on item_master.item_code=	packing_inhouse_size_detail.item_code 
        inner join color_master on color_master.color_id=	packing_inhouse_size_detail.color_id 
        where pki_code='".$PackingInhouseMaster[0]->pki_code."' group by 	packing_inhouse_size_detail.color_id");
             //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
        $FirmDetail = DB::table('firm_master')->where('delflag','=', '0')->first();
      
             return view('PackingGRNPrint', compact('PackingInhouseMaster','PackingGRNList','SizeDetailList','FirmDetail'));
      
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
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        //   DB::enableQueryLog();
        $ReturnPackingInhouseMasterList = ReturnPackingInhouseMasterModel::find($id);
        $gstlist = DB::table('tax_type_master')->get();
        
        $BuyerList = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
        $vendorId=Session::get('vendorId');
        
        if(Session::get('vendorId')==56 && Session::get('user_type')!=6)
        {
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        }
        elseif(Session::get('vendorId')!=56 && Session::get('user_type')==6)
        {
            $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','=', $vendorId)->get();
        }
         
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$ReturnPackingInhouseMasterList->sales_order_no)->distinct()->get();
        
      
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$ReturnPackingInhouseMasterList->sales_order_no)->DISTINCT()->get();
        //DB::enableQueryLog();
        $ReturnPackingInhouseDetailList = ReturnPackingInhouseDetailModel::where('return_packing_inhouse_detail.rpki_code','=', $ReturnPackingInhouseMasterList->rpki_code)->get();
        //dd(DB::getQueryLog());
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($ReturnPackingInhouseMasterList->sales_order_no);
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
        $sizes='';
        $no=1;
        foreach ($SizeDetailList as $sz) 
        {
            $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
            $no=$no+1;
        }
        $sizes=rtrim($sizes,',');
       // $SalesOrderList= BuyerPurchaseOrderMasterModel::select('buyer_purchse_order_master.tr_code as sales_order_no')->get();
        
        return view('ReturnPackingInhouseMasterEdit',compact('gstlist','ReturnPackingInhouseDetailList','ColorList','LocationList' ,'BuyerList','SizeDetailList','ReturnPackingInhouseMasterList',  'ItemList',  'MainStyleList','SubStyleList','FGList','Ledger' ));
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rpki_code)
    {

 
    $is_opening=isset($request->is_opening) ? 1 : 0;
    $data1=array(
           
        'rpki_code'=>$request->rpki_code, 
        'rpki_date'=>$request->rpki_date, 
        'sales_order_no'=>$request->sales_order_no,
        'Ac_code'=>$request->Ac_code, 
        'vendorId'=>$request->vendorId,
        'sale_code'=>$request->sale_code,
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
        'is_opening'=>$is_opening,
        'rate'=>$request->rate,
        'location_id'=>$request->location_id,
        'tax_type_id'=>$request->tax_type_id,
        'updated_at'=>date("Y-m-d H:i")
    );
  
$ReturnPackingInhouseList = ReturnPackingInhouseMasterModel::findOrFail($request->rpki_code); 
//DB::enableQueryLog();   
$ReturnPackingInhouseList->fill($data1)->save();
//dd(DB::getQueryLog());
 
DB::table('return_packing_inhouse_size_detail')->where('rpki_code', $request->input('rpki_code'))->delete();
DB::table('return_packing_inhouse_size_detail2')->where('rpki_code', $request->input('rpki_code'))->delete();
DB::table('return_packing_inhouse_detail')->where('rpki_code', $request->input('rpki_code'))->delete();
 
 $color_id= $request->input('color_id');
    if(count($color_id)>0)
    {   
    
    for($x=0; $x<count($color_id); $x++) {
        # code...
      if($request->size_qty_total[$x]>0)
              {
                    $data2 =array(
          
                    'rpki_code'=>$request->rpki_code,
                    'rpki_date'=>$request->rpki_date,
                    'sales_order_no'=>$request->sales_order_no,
                    'Ac_code'=>$request->Ac_code, 
                    'vendorId'=>$request->vendorId,
                    'sale_code'=>$request->sale_code,
                    'mainstyle_id'=>$request->mainstyle_id,
                    'substyle_id'=>$request->substyle_id,
                    'fg_id'=>$request->fg_id,
                    'style_no'=>$request->style_no,
                    'style_description'=>$request->style_description,
                    'item_code'=>$request->item_codef[$x],
                    'color_id'=>$request->color_id[$x],
                    'hsn_code'=> isset($request->hsn_code[$x]) ? $request->hsn_code[$x] : "",
                    'size_array'=>$request->size_array[$x],
                    'size_qty_array'=>$request->size_qty_array[$x],
                    'size_qty_total'=>$request->size_qty_total[$x],
                    'is_opening'=>$is_opening,
                    // 'is_transfered'=>$request->is_transfered[$x],
                    'trans_sales_order_no'=>$request->trans_sales_order_no[$x],
                    'transfer_code'=>$request->transfer_code[$x],
                    'location_id'=>$request->location_id,
                    'rate'=>$request->rates[$x],
                    'cgst'=>$request->cgst[$x],
                    'camt'=>$request->camt[$x],
                    'sgst'=>$request->sgst[$x],
                    'samt'=>$request->samt[$x],
                    'igst'=>$request->igst[$x],
                    'iamt'=>$request->iamt[$x],
                    'amount'=>$request->amount[$x],
                    'total_amount'=>$request->total_amount[$x],
                   
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
 
                      $data3 =array(
                  
                        'rpki_code'=>$request->rpki_code,
                        'rpki_date'=>$request->rpki_date, 
                        'sales_order_no'=>$request->sales_order_no,
                        'Ac_code'=>$request->Ac_code, 
                        'vendorId'=>$request->vendorId,
                        'sale_code'=>$request->sale_code,
                        'mainstyle_id'=>$request->mainstyle_id,
                        'substyle_id'=>$request->substyle_id,
                        'fg_id'=>$request->fg_id,
                        'style_no'=>$request->style_no,
                        'style_description'=>$request->style_description,
                        'item_code'=>$request->item_codef[$x],
                        'color_id'=>$request->color_id[$x],
                        'hsn_code'=>isset($request->hsn_code[$x]) ? $request->hsn_code[$x] : "",
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
                        'vendor_rate'=>$request->vendor_rate,
                        'is_opening'=>$is_opening,
                        // 'is_transfered'=>$request->is_transfered[$x],
                        'trans_sales_order_no'=>$request->trans_sales_order_no[$x],
                        'transfer_code'=>$request->transfer_code[$x],
                        'location_id'=>$request->location_id,
                        'rate'=>$request->rates[$x],
                        'cgst'=>$request->cgst[$x],
                        'camt'=>$request->camt[$x],
                        'sgst'=>$request->sgst[$x],
                        'samt'=>$request->samt[$x],
                        'igst'=>$request->igst[$x],
                        'iamt'=>$request->iamt[$x],
                        'amount'=>$request->amount[$x],
                        'total_amount'=>$request->total_amount[$x],
                        );
                          
                           if($request->is_transfered[$x]==1)
                           {
                                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail set usedFlag=1 
                                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                                  and tpki_code='".$request->transfer_code[$x]."' and
                                  color_id='".$request->color_id[$x]."'");
                                  
                                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_size_detail2 set usedFlag=1 
                                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                                  and tpki_code='".$request->transfer_code[$x]."' and
                                  color_id='".$request->color_id[$x]."'");
                                  
                                  $UpdateUseFlag=DB::select("update transfer_packing_inhouse_detail set usedFlag=1 
                                  where main_sales_order_no='".$request->trans_sales_order_no[$x]."' 
                                  and tpki_code='".$request->transfer_code[$x]."' and
                                  color_id='".$request->color_id[$x]."'");
                           }
              
                      ReturnPackingInhouseDetailModel::insert($data2);
                      ReturnPackingInhouseSizeDetailModel::insert($data3);
              }
            }
          
    }
    
           
           
    $InsertSizeData=DB::select('call AddSizeQtyFromReturnPackingInhouse("'.$request->rpki_code.'")');
           
           
     return redirect()->route('ReturnPackingInhouseMaster.index')->with('message', 'Data Update Succesfully'); 
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
     
     
  public function GetFINISHINGGRNQty(Request $request)
  {
     $colors='';
    //   DB::enableQueryLog();  
      $VendorPurchaseOrderColorList = VendorPurchaseOrderDetailModel::select('color_id')->where('vpo_code',$request->vpo_code)->get();
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      
      foreach($VendorPurchaseOrderColorList as $ColorList)
      {
          $colors=$colors.$ColorList->color_id.',';
      }
      
      $colors=rtrim($colors,',');
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
    // DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT finishing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total  from finishing_inhouse_size_detail inner join color_master on 
      color_master.color_id=finishing_inhouse_size_detail.color_id where sales_order_no='".$request->tr_code."' and 
      finishing_inhouse_size_detail.color_id in (".$colors.") group by finishing_inhouse_size_detail.color_id");
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
      outward_for_packing_size_detail.sales_order_no='".$request->tr_code."' and
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
          
          $no=$no+1;
        }
         
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }

   
     
     
     
     
     
   public function getFinishingInhouseDetails(Request $request)
    { 
        $vpo_code= $request->input('vpo_code');
        $MasterdataList = DB::select("select Ac_code,sales_order_no, vendorId, mainstyle_id, substyle_id, fg_id, style_no, order_rate, style_description from vendor_purchase_order_master where vendor_purchase_order_master.delflag=0 and vpo_code='".$vpo_code."'");
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

  
  public function FNSI_GetOrderQty(Request $request)
  {
      // vpo_   as Vendor Work Order   same function name is defined in BOM, So Name prepended by vpo_
    //   DB::enableQueryLog();
      $VendorPurchaseOrderMasterList = VendorPurchaseOrderModel::find($request->vpo_code);
    //  $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query); 
      $VendorPurchaseOrderDetailList = VendorPurchaseOrderDetailModel::where('vpo_code',$request->vpo_code)->first();
      
     $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::select('sz_code')->where('buyer_purchse_order_master.tr_code',$VendorPurchaseOrderMasterList->sales_order_no)->first();
       $ColorList = DB::table('vendor_purchase_order_detail')->select('vendor_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'vendor_purchase_order_detail.color_id', 'left outer')
        ->where('vpo_code','=',$request->vpo_code)->DISTINCT()->get();
      
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
    //   $MasterdataList = DB::select("SELECT finishing_inhouse_size_detail.item_code, finishing_inhouse_size_detail.color_id, color_name, ".$sizes.", 
    //   sum(size_qty_total) as size_qty_total from finishing_inhouse_size_detail inner join color_master on 
    //   color_master.color_id=finishing_inhouse_size_detail.color_id where vpo_code='".$request->vpo_code."'
    //   group by finishing_inhouse_size_detail.color_id");
    
     $MasterdataList = DB::select("SELECT vendor_purchase_order_size_detail.item_code, vendor_purchase_order_size_detail.color_id, color_name, ".$sizes.", 
      sum(size_qty_total) as size_qty_total from vendor_purchase_order_size_detail inner join color_master on 
      color_master.color_id=vendor_purchase_order_size_detail.color_id where vpo_code='".$request->vpo_code."'
      group by vendor_purchase_order_size_detail.color_id");
    
    
       

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
                  <th>Rate</th>
                  <th>CGST%</th>
                  <th>CAMT</th>
                  <th>SGST%</th>
                  <th>SAMT</th>
                  <th>IGST%</th>
                  <th>IAMT</th>
                  <th>Amount</th>
                  <th>Total Amount</th>
                  
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



        //   if(isset($row->s1)) { $html.='<td>'.$row->s1.' <input style="width:80px; float:left;" max='.$row->s1.' min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
        //   if(isset($row->s2)) { $html.='<td>'.$row->s2.' <input style="width:80px; float:left;" max='.$row->s2.' min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
        //   if(isset($row->s3)) { $html.='<td>'.$row->s3.' <input style="width:80px; float:left;" max='.$row->s3.' min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
        //   if(isset($row->s4)) { $html.='<td>'.$row->s4.' <input style="width:80px; float:left;" max='.$row->s4.' min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
        //   if(isset($row->s5)) { $html.='<td>'.$row->s5.' <input style="width:80px; float:left;" max='.$row->s5.' min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
        //   if(isset($row->s6)) { $html.='<td>'.$row->s6.' <input style="width:80px; float:left;" max='.$row->s6.' min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
        //   if(isset($row->s7)) { $html.='<td>'.$row->s7.' <input style="width:80px; float:left;" max='.$row->s7.' min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
        //   if(isset($row->s8)) { $html.='<td>'.$row->s8.' <input style="width:80px; float:left;" max='.$row->s8.' min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
        //   if(isset($row->s9)) { $html.='<td>'.$row->s9.' <input style="width:80px; float:left;" max='.$row->s9.' min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
        //   if(isset($row->s10)) { $html.='<td>'.$row->s10.' <input style="width:80px; float:left;" max='.$row->s10.' min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
        //   if(isset($row->s11)) { $html.='<td>'.$row->s11.' <input style="width:80px; float:left;" max='.$row->s11.' min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
        //   if(isset($row->s12)) { $html.='<td>'.$row->s12.' <input style="width:80px;  float:left;" max='.$row->s12.' min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
        //   if(isset($row->s13)) { $html.='<td>'.$row->s13.' <input style="width:80px; float:left;" max='.$row->s13.' min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
        //   if(isset($row->s14)) { $html.='<td>'.$row->s14.' <input style="width:80px; float:left;" max='.$row->s14.' min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
        //   if(isset($row->s15)) { $html.='<td>'.$row->s15.' <input style="width:80px; float:left;" max='.$row->s15.' min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
        //   if(isset($row->s16)) { $html.='<td>'.$row->s16.' <input style="width:80px; float:left;" max='.$row->s16.' min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
        //   if(isset($row->s17)) { $html.='<td>'.$row->s17.' <input style="width:80px; float:left;" max='.$row->s17.' min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
        //   if(isset($row->s18)) { $html.='<td>'.$row->s18.' <input style="width:80px;  float:left;" max='.$row->s18.' min="0" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
        //   if(isset($row->s19)) { $html.='<td>'.$row->s19.' <input style="width:80px; float:left;" max='.$row->s19.' min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
        //   if(isset($row->s20)) { $html.='<td>'.$row->s20.' <input style="width:80px; float:left;" max='.$row->s20.' min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
    
              $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'ifnull(sum(s'.$nox.'),0) as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
        //  DB::enableQueryLog();  
      $List = DB::select("SELECT packing_inhouse_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=packing_inhouse_size_detail.color_id where 
      packing_inhouse_size_detail.vpo_code='".$request->vpo_code."' and
      packing_inhouse_size_detail.color_id='".$row->color_id."'
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
          if(isset($row->s1)) { $html.='<td>'.$s1.' <input style="width:80px; float:left;"   name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) { $html.='<td>'.$s2.' <input style="width:80px; float:left;"   name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) { $html.='<td>'.$s3.' <input style="width:80px; float:left;"   name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) { $html.='<td>'.$s4.' <input style="width:80px; float:left;"   name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) { $html.='<td>'.$s5.' <input style="width:80px; float:left;"   name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) { $html.='<td>'.$s6.' <input style="width:80px; float:left;"   name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) { $html.='<td>'.$s7.' <input style="width:80px; float:left;"   name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) { $html.='<td>'.$s8.' <input style="width:80px; float:left;"   name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) { $html.='<td>'.$s9.' <input style="width:80px; float:left;"   name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
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
          $html.='<td>'.($row->size_qty_total-$List[0]->size_qty_total).'  
            <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
            <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
            <input type="hidden" name="size_array[]"  value="'.$VendorPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />';
          
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
        DB::table('return_packing_inhouse_master')->where('rpki_code', $id)->delete();
         DB::table('return_packing_inhouse_size_detail2')->where('rpki_code', $id)->delete();
        DB::table('return_packing_inhouse_size_detail')->where('rpki_code', $id)->delete();
        DB::table('return_packing_inhouse_detail')->where('rpki_code', $id)->delete();
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    
    
    
    
    
    
    public function Op_GetOrderQty(Request $request)
    {
      // W_   as Vendor Work Order sa same function name is defined in BOM, So Name prepended by W_
      
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
         // $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
          
           $sizes=$sizes.'sum(s'.$no.')+(sum(s'.$no.')*((shipment_allowance+garment_rejection_allowance)/100)) as s'.$no.',';
          
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        //   DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT sales_order_detail.item_code,sales_order_detail.color_id, color_name, ".$sizes.", 
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
                  
                  <th>Color</th>
                  <th>HSN Code</th>';
                  foreach ($SizeDetailList as $sz) 
                  {
                      $html.='<th>'.$sz->size_name.'</th>';
                  }
                  $html.=' 
                  <th>Total Qty</th>
                  <th>Rate</th>
                  <th>CGST%</th>
                  <th>CAMT</th>
                  <th>SGST%</th>
                  <th>SAMT</th>
                  <th>IGST%</th>
                  <th>IAMT</th>
                  <th>Amount</th>
                  <th>Total Amount</th>
               
                  </tr>
              </thead>
              <tbody>';
          $no=1;
          foreach ($MasterdataList as $row) 
        {
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
           
        $html.=' <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $row1)
        {
            $html.='<option value="'.$row1->color_id.'"';
            $row1->color_id == $row->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$row1->color_name.'</option>';
        }
        
        $html.='</select>
        
          <input type="hidden" name="item_codef[]"  value="'.$row->item_code.'" id="item_codef" style="width:80px; height:30px; float:left;"  />
        </td>
        <td><input type="text" name="hsn_code[]"  value="'.$row->hsn_code.'" id="hsn_code" style="width:80px;  float:left;"  /></td>';


      $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'sum(s'.$nox.') as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
        //  DB::enableQueryLog();  
      $CompareList = DB::select("SELECT vendor_work_order_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
      color_master.color_id=vendor_work_order_size_detail.color_id where 
      vendor_work_order_size_detail.sales_order_no='".$request->tr_code."' and
      vendor_work_order_size_detail.color_id='".$row->color_id."'
       ");


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
          if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.' <input style="width:80px; float:left;" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.' <input style="width:80px; float:left;"   name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.' <input style="width:80px; float:left;"   name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.' <input style="width:80px; float:left;"  name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.' <input style="width:80px; float:left;"  name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.' <input style="width:80px; float:left;"    name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.' <input style="width:80px; float:left;"   name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.' <input style="width:80px; float:left;"   name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.' <input style="width:80px; float:left;"    name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.' <input style="width:80px; float:left;"   name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.' <input style="width:80px; float:left;"   name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.' <input style="width:80px; float:left;"   name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.' <input style="width:80px; float:left;"   name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.' <input style="width:80px; float:left;"  name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.' <input style="width:80px; float:left;"   name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.' <input style="width:80px; float:left;"  name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.' <input style="width:80px; float:left;"   name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.' <input style="width:80px; float:left;"   name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.' <input style="width:80px; float:left;"   name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.' <input style="width:80px; float:left;"   name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
       
         
        
          $html.='<td>'.($total_qty-$List->size_qty_total).'  
          
          
          
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
         
          <input type="hidden" name="is_transfered[]"   value="" id="is_transfered" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="trans_sales_order_no[]"  value="" id="trans_sales_order_no" style="width:80px; float:left;"  />
        <input type="hidden" name="transfer_code[]"  value="" id="transfer_code" style="width:80px;  float:left;"  />
        
        
          
          </td>
          <td><input type="text" name="rate[]"  value="" id="rate" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="cgst[]"  value="" id="cgst" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="camt[]"  value="" id="camt" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="sgst[]"  value="" id="sgst" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="samt[]"  value="" id="samt" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="igst[]"  value="" id="igst" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="iamt[]"  value="" id="iamt" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="amount[]"  value="" id="amount" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="total_amount[]"  value="" id="total_amount" style="width:80px;  float:left;"  /></td>';
          
          
          
          $html.='</tr>';

          $no=$no+1;
       
        
         
        
         $sizet='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizet=$sizet.'ifnull(sum(s'.$nox.'),0) as s'.$nox.',';
          $nox=$nox+1;
      }
      $sizet=rtrim($sizet,',');
        
        
        
        //   DB::enableQueryLog(); 
      $TransferList = DB::select("SELECT main_sales_order_no,tpki_code, transfer_packing_inhouse_size_detail.color_id, color_name, ".$sizet.", 
      sum(size_qty_total) as size_qty_total from transfer_packing_inhouse_size_detail inner join color_master on 
      color_master.color_id=transfer_packing_inhouse_size_detail.color_id where 
      transfer_packing_inhouse_size_detail.sales_order_no='".$request->tr_code."'
      and transfer_packing_inhouse_size_detail.color_id='".$row->color_id."'
      and transfer_packing_inhouse_size_detail.usedFlag=0");
        //     $query = DB::getQueryLog();
        //   $query = end($query);
        //   dd($query);
         
         
         
       $SieQtyArray=''; $total_qty=0;
     
    
    if(count($TransferList)>0 && $TransferList[0]->size_qty_total!=0)
    {
       
        foreach($TransferList as $row1)
        { 
            $SieQtyArray='';
            
            
        $html.='<tr ><td >'.$no.'</td>';
        $html.=' <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px; background-color:#cbe9dc;" required disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $rowx)
        {
            $html.='<option value="'.$rowx->color_id.'"';
            $rowx->color_id == $row1->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$rowx->color_name.'</option>';
        }
        
        $html.='</select>
        
          <input type="hidden" name="item_codef[]"  value="" id="item_codef" style="width:80px; height:30px; float:left;"  />
        </td>
        <td><input type="text" name="hsn_code[]"  value="" id="hsn_code" style="width:80px;  float:left;"  /></td>';
 
             
          if(isset($row1->s1)) {$SieQtyArray=$SieQtyArray.$row1->s1.',';    $total_qty=$total_qty+round($row1->s1); $html.='<td  >  <input style="width:80px; float:left; background-color:#cbe9dc; " name="s1[]" class="size_id" type="number" id="s1" value="'.$row1->s1.'" required readonly /></td>';}
          if(isset($row1->s2)) {$SieQtyArray=$SieQtyArray.$row1->s2.',';    $total_qty=$total_qty+round($row1->s2); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s2[]" type="number" class="size_id" id="s2" value="'.$row1->s2.'" required readonly/></td>';}
          if(isset($row1->s3)) {$SieQtyArray=$SieQtyArray.$row1->s3.',';    $total_qty=$total_qty+round($row1->s3); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s3[]" type="number" class="size_id" id="s3" value="'.$row1->s3.'" requiredreadonly /></td>';}
          if(isset($row1->s4)) {$SieQtyArray=$SieQtyArray.$row1->s4.',';    $total_qty=$total_qty+round($row1->s4); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s4[]" type="number" class="size_id" id="s4" value="'.$row1->s4.'" required readonly/></td>';}
          if(isset($row1->s5)) {$SieQtyArray=$SieQtyArray.$row1->s5.',';    $total_qty=$total_qty+round($row1->s5); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s5[]" type="number" class="size_id" id="s5" value="'.$row1->s5.'" required readonly/></td>';}
          if(isset($row1->s6)) {$SieQtyArray=$SieQtyArray.$row1->s6.',';    $total_qty=$total_qty+round($row1->s6); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"    name="s6[]" type="number" class="size_id" id="s6" value="'.$row1->s6.'" required readonly/></td>';}
          if(isset($row1->s7)) {$SieQtyArray=$SieQtyArray.$row1->s7.',';    $total_qty=$total_qty+round($row1->s7); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s7[]" type="number" class="size_id" id="s7" value="'.$row1->s7.'" required readonly/></td>';}
          if(isset($row1->s8)) {$SieQtyArray=$SieQtyArray.$row1->s8.',';    $total_qty=$total_qty+round($row1->s8); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s8[]" type="number" class="size_id" id="s8" value="'.$row1->s8.'" required readonly/></td>';}
          if(isset($row1->s9)) {$SieQtyArray=$SieQtyArray.$row1->s9.',';    $total_qty=$total_qty+round($row1->s9); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"    name="s9[]" type="number" class="size_id" id="s9" value="'.$row1->s9.'" required readonly/></td>';}
          if(isset($row1->s10)) {$SieQtyArray=$SieQtyArray.$row1->s10.',';    $total_qty=$total_qty+round($row1->s10); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s10[]" type="number" class="size_id" id="s10" value="'.$row1->s10.'" required readonly/></td>';}
          if(isset($row1->s11)) {$SieQtyArray=$SieQtyArray.$row1->s11.',';    $total_qty=$total_qty+round($row1->s11); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s11[]" type="number" class="size_id" id="s11" value="'.$row1->s11.'" required readonly /></td>';}
          if(isset($row1->s12)) {$SieQtyArray=$SieQtyArray.$row1->s12.',';    $total_qty=$total_qty+round($row1->s12); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s12[]" type="number" class="size_id" id="s12" value="'.$row1->s12.'" required readonly/></td>';}
          if(isset($row1->s13)) {$SieQtyArray=$SieQtyArray.$row1->s13.',';    $total_qty=$total_qty+round($row1->s13); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s13[]" type="number" class="size_id" id="s13" value="'.$row1->s13.'" required readonly/></td>';}
          if(isset($row1->s14)) {$SieQtyArray=$SieQtyArray.$row1->s14.',';    $total_qty=$total_qty+round($row1->s14); $html.='<td> <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s14[]" type="number" class="size_id" id="s14" value="'.$row1->s14.'" required readonly/></td>';}
          if(isset($row1->s15)) {$SieQtyArray=$SieQtyArray.$row1->s15.',';    $total_qty=$total_qty+round($row1->s15); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s15[]" type="number" class="size_id" id="s15" value="'.$row1->s15.'" required readonly/></td>';}
          if(isset($row1->s16)) {$SieQtyArray=$SieQtyArray.$row1->s16.',';    $total_qty=$total_qty+round($row1->s16); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"  name="s16[]" type="number" class="size_id" id="s16" value="'.$row1->s16.'" required readonly/></td>';}
          if(isset($row1->s17)) {$SieQtyArray=$SieQtyArray.$row1->s17.',';    $total_qty=$total_qty+round($row1->s17); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s17[]" type="number" class="size_id" id="s17" value="'.$row1->s17.'" required readonly/></td>';}
          if(isset($row1->s18)) {$SieQtyArray=$SieQtyArray.$row1->s18.',';    $total_qty=$total_qty+round($row1->s18); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s18[]" type="number" class="size_id" id="s18" value="'.$row1->s18.'" required readonly/></td>';}
          if(isset($row1->s19)) {$SieQtyArray=$SieQtyArray.$row1->s19.',';    $total_qty=$total_qty+round($row1->s19); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s19[]" type="number" class="size_id" id="s19" value="'.$row1->s19.'" required readonly/></td>';}
          if(isset($row1->s20)) {$SieQtyArray=$SieQtyArray.$row1->s20;        $total_qty=$total_qty+round($row1->s20); $html.='<td>  <input style="width:80px; float:left; background-color:#cbe9dc;"   name="s20[]" type="number" class="size_id" id="s20" value="'.$row1->s20.'" required readonly/></td>';}
       
         
        $SieQtyArray=rtrim($SieQtyArray,',');
          $html.='<td>  
          
          
          
          
        <input type="number" name="size_qty_total[]" class="size_qty_total" value="'.$total_qty.'" id="size_qty_total" style="width:80px; height:30px; float:left; background-color:#cbe9dc;" required readOnly  />
        <input type="hidden" name="size_qty_array[]"  value="'.$SieQtyArray.'" id="size_qty_array" style="width:80px; float:left;"  readOnly/>
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;" readOnly />
         
        <input type="hidden" name="is_transfered[]"   value="1" id="is_transfered" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="trans_sales_order_no[]"  value="'.$row1->main_sales_order_no.'" id="trans_sales_order_no" style="width:80px; float:left;"  />
        <input type="hidden" name="transfer_code[]"  value="'.$row1->tpki_code.'" id="transfer_code" style="width:80px;  float:left;"  />
        
        </td>
          <td><input type="text" name="rate[]"  value="" id="rate" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="cgst[]"  value="" id="cgst" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="camt[]"  value="" id="camt" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="sgst[]"  value="" id="sgst" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="samt[]"  value="" id="samt" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="igst[]"  value="" id="igst" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="iamt[]"  value="" id="iamt" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="amount[]"  value="" id="amount" style="width:80px;  float:left;"  /></td>
          <td><input type="text" name="total_amount[]"  value="" id="total_amount" style="width:80px;  float:left;"  /></td>
        
        </tr>';
            
            
        }
    }
        
        
        }
        
        
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
         
  }
  
    
    
    
    
    
    
     public function PackingGRNReport()
{
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $ItemList = ItemModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
     //  DB::enableQueryLog();  
        $VendorPurchaseOrderList = VendorPurchaseOrderModel::where('vendor_purchase_order_master.process_id','1')
       ->whereIn('vendor_purchase_order_master.sales_order_no', function($query){
        $query->select('buyer_purchse_order_master.tr_code as sales_order_no')->from('buyer_purchse_order_master')->where('buyer_purchse_order_master.job_status_id',1);
        })->distinct('vendor_purchase_order_master.sales_order_no')->get();
    //   $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
      
   return view('PackingGRNReport',compact('VendorPurchaseOrderList',  'ItemList',  'MainStyleList','SubStyleList','FGList', 'Ledger' ));
 }  
    
    
     
    public function GetSaleInvoices(Request $request)
    {   
    
        $SaleInvoiceList = DB::select("SELECT  DISTINCT sale_code from sale_transaction_detail where sales_order_no='".$request->sales_order_no."'");
      
        $html ='<option value="">--Select--</option>';

        foreach($SaleInvoiceList as  $row)
        {
            $html.='<option value="'.$row->sale_code.'">'.$row->sale_code.'</option>';
        }
        
        return response()->json(['html' => $html]);
    }
    
    public function Op_ReturnGetOrderQty(Request $request)
    {
      // W_   as Vendor Work Order sa same function name is defined in BOM, So Name prepended by W_
      
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
         // $sizes=$sizes.'sum(s'.$no.') as s'.$no.',';
          
           $sizes=$sizes.'sum(s'.$no.')+(sum(s'.$no.')*((shipment_allowance+garment_rejection_allowance)/100)) as s'.$no.',';
          
          $no=$no+1;
      }
      $sizes=rtrim($sizes,',');
        //   DB::enableQueryLog();  
      $MasterdataList = DB::select("SELECT sales_order_detail.item_code,sales_order_detail.color_id, color_name, ".$sizes.", 
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
                  <th>Rate</th>
                  <th>CGST%</th>
                  <th>CAMT</th>
                  <th>SGST%</th>
                  <th>SAMT</th>
                  <th>IGST%</th>
                  <th>IAMT</th>
                  <th>Amount</th>
                  <th>Total Amount</th>
               
               </tr>
              </thead>
              <tbody>';
          $no=1;
          foreach ($MasterdataList as $row) 
        {
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
           
        $html.=' <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" required disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $row1)
        {
            $html.='<option value="'.$row1->color_id.'"';
            $row1->color_id == $row->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$row1->color_name.'</option>';
        }
        
        $html.='</select>
        
          <input type="hidden" name="item_codef[]"  value="'.$row->item_code.'" id="item_codef" style="width:80px; height:30px; float:left;"  />
        </td>';


      $sizex='';
      $nox=1;
      foreach ($SizeDetailList as $sz) 
      {
          $sizex=$sizex.'sum(s'.$nox.') as s_'.$nox.',';
          $nox=$nox+1;
      }
      $sizex=rtrim($sizex,',');
      
      $salesData1 = DB::SELECT("SELECT carton_packing_nos FROM sale_transaction_master WHERE sale_code='".$request->sale_code."'");
      $cartonNos = "";
      $cartonNos1 = explode(",", $salesData1[0]->carton_packing_nos);
      foreach($cartonNos1 as $sales)
      {
          $cartonNos .= "'".$sales."',".$cartonNos;
      }
      
      $carton_nos =  rtrim($cartonNos, ','); 
      //  DB::enableQueryLog();  
      $CompareList = DB::select("SELECT carton_packing_inhouse_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total 
      from carton_packing_inhouse_size_detail left join color_master on 
      color_master.color_id=carton_packing_inhouse_size_detail.color_id  where 
      carton_packing_inhouse_size_detail.sales_order_no='".$request->tr_code."' and
      carton_packing_inhouse_size_detail.color_id='".$row->color_id."' AND cpki_code IN(".$carton_nos.")");

     //   dd(DB::getQueryLog());
 
foreach($CompareList as $List)
{
   if(isset($row->s1)) { $s1=((intval($List->s_1))); }
   if(isset($row->s2)) { $s2=((intval($List->s_2))); }
   if(isset($row->s3)) { $s3=((intval($List->s_3))); }
   if(isset($row->s4)) { $s4=((intval($List->s_4))); }
   if(isset($row->s5)) { $s5=((intval($List->s_5))); }
   if(isset($row->s6)) { $s6=((intval($List->s_6))); }
   if(isset($row->s7)) { $s7=((intval($List->s_7)));}
   if(isset($row->s8)) { $s8=((intval($List->s_8)));}
   if(isset($row->s9)) { $s9=((intval($List->s_9)));}
   if(isset($row->s10)) { $s10=((intval($List->s_10)));}
   if(isset($row->s11)) { $s11=((intval($List->s_11)));}
   if(isset($row->s12)) { $s12=((intval($List->s_12)));}
   if(isset($row->s13)) { $s13=((intval($List->s_13)));}
   if(isset($row->s14)) { $s14=((intval($List->s_14)));}
   if(isset($row->s15)) { $s15=((intval($List->s_15)));}
   if(isset($row->s16)) {$s16=((intval($List->s_16)));}
   if(isset($row->s17)) { $s17=((intval($List->s_17)));}
   if(isset($row->s18)) { $s18=((intval($List->s_18)));}
   if(isset($row->s19)) { $s19=((intval($List->s_19)));}
   if(isset($row->s20)) { $s20=((intval($List->s_20)));}
     
}

$total_qty=0;
          if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td><span>'.$s1.' </span><input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td><span>'.$s2.' </span><input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"   name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td><span>'.$s3.' </span><input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td><span>'.$s4.' </span><input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"  name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td><span>'.$s5.' </span><input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"  name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td><span>'.$s6.'</span> <input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"   name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td><span>'.$s7.' </span><input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td><span>'.$s8.' </span><input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td><span>'.$s9.' </span><input style="width:80px; float:left;"    onchange="calculateTax(this);checkTotalQty(this);" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td><span>'.$s10.'</span> <input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td><span>'.$s11.'</span> <input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td><span>'.$s12.' </span><input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td><span>'.$s13.'</span> <input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"   name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td><span>'.$s14.'</span> <input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"  name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td><span>'.$s15.' </span><input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"   name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td><span>'.$s16.'</span> <input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"  name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td><span>'.$s17.'</span> <input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"   name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td><span>'.$s18.' </span><input style="width:80px; float:left;" onchange="calculateTax(this);checkTotalQty(this);"   name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td><span>'.$s19.' </span><input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td><span>'.$s20.'</span> <input style="width:80px; float:left;"  onchange="calculateTax(this);checkTotalQty(this);"  name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
       
         
        
          $html.='<td>'.($List->size_qty_total).'  
          
          
          
          
          <input type="number" name="size_qty_total[]" onchange="calculateTax(this);" class="size_qty_total readonly" value="0" id="size_qty_total" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
         
          <input type="hidden" name="is_transfered[]"   value="" id="is_transfered" style="width:80px; height:30px; float:left;" required readOnly  />
        <input type="hidden" name="trans_sales_order_no[]"  value="" id="trans_sales_order_no" style="width:80px; float:left;"  />
        <input type="hidden" name="transfer_code[]"  value="" id="transfer_code" style="width:80px;  float:left;"  />
        
        
          
          </td>
          
          <td><input type="number" step="any" name="rates[]" onchange="calculateTax(this);" value="" id="rate" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="cgst[]" class="readonly" readonly  value="" id="cgst" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="camt[]" class="readonly" readonly  value="" id="camt" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="sgst[]"  class="readonly" readonly value="" id="sgst" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="samt[]" class="readonly" readonly  value="" id="samt" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="igst[]" class="readonly" readonly  value="" id="igst" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="iamt[]"  class="readonly" readonly value="" id="iamt" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="amount[]" class="readonly" readonly  value="" id="amount" style="width:80px;  float:left;"  /></td>
          <td><input type="number" step="any" name="total_amount[]" class="readonly" readonly  value="" id="total_amount" style="width:80px;  float:left;"  /></td>
          ';
          
          
          
          $html.='</tr>';

          $no=$no+1;
       
        
        
        }
        
        
        
          $html.=' 
            </tbody>
            </table>';


      return response()->json(['html' => $html]);
  }
  
    public function GetTaxType(Request $request)
    {
        //DB::enableQueryLog();
        $TaxList = DB::select("SELECT tax_type_id  from sale_transaction_master where sale_code='".$request->sale_code."'");
        $TaxDetail = DB::select("SELECT sale_cgst,sale_sgst,sale_igst from sale_transaction_detail where sale_code='".$request->sale_code."'");
        
        //dd(DB::getQueryLog()); 
        if(count($TaxList) > 0)
        {
            $tax_type_id = $TaxList[0]->tax_type_id;
        }
        else
        {
            $tax_type_id = '';
        }
        
        return response()->json(['html' => $tax_type_id,'detail'=> $TaxDetail]);
    }
    
    public function PrintReturnPackingInhouse($id)
    {
       // DB::enableQueryLog();
        $MasterList = DB::table('return_packing_inhouse_master')
        ->select('return_packing_inhouse_master.tax_type_id','return_packing_inhouse_master.rpki_code','return_packing_inhouse_detail.*',
        'buyer_purchse_order_master.style_description','main_style_master.mainstyle_name','color_master.color_name')
        ->join('return_packing_inhouse_detail', 'return_packing_inhouse_detail.rpki_code', '=', 'return_packing_inhouse_master.rpki_code') 
        ->join('buyer_purchse_order_master','buyer_purchse_order_master.Ac_code','=','return_packing_inhouse_master.Ac_code')
        ->join('color_master','color_master.color_id','=','return_packing_inhouse_detail.color_id')
        ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'buyer_purchse_order_master.mainstyle_id')
        ->where('buyer_purchse_order_master.delflag','=', '0')
        ->where('return_packing_inhouse_master.rpki_code','=', $id)
        ->groupby('return_packing_inhouse_detail.color_id')
        ->get(); 
        
        //  $DetailList = DB::table('return_packing_inhouse_detail')
        // ->select('return_packing_inhouse_detail.*')
        // ->where('return_packing_inhouse_master.rpki_code','=', $id)
        // ->get(); 
        
        //dd(DB::getQueryLog());
        $BuyerDetail = DB::table('return_packing_inhouse_master')->select('return_packing_inhouse_master.*','ledger_master.*') 
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'return_packing_inhouse_master.Ac_code')
        ->where('return_packing_inhouse_master.rpki_code','=', $id)->first(); 
      
        $ReturnPackingInhouseDetailList = ReturnPackingInhouseDetailModel::where('return_packing_inhouse_detail.rpki_code','=', $id)->first();
        //dd(DB::getQueryLog());
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($ReturnPackingInhouseDetailList->sales_order_no);
        $SizeDetailList = SizeDetailModel::where('size_detail.sz_code','=', $BuyerPurchaseOrderMasterList->sz_code)->get();
        
        return view('PrintReturnPackingInhouse', compact('MasterList','BuyerDetail','SizeDetailList'));
    }
}
