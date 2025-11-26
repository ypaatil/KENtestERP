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
use Session;
use DataTables;

class VendorWorkOrderController extends Controller
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
        ->where('form_id', '98')
        ->first();
        
        if( $request->page == 1)
        {
                
            $VendorWorkOrderList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.Ac_code', 'left outer')
            ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
            ->join('ledger_master as LM2', 'LM2.Ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_work_order_master.endflag', 'left outer')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
            ->where('vendor_work_order_master.delflag','=', '0')
            ->get(['vendor_work_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','season_master.season_name','job_status_master.job_status_name', 'LM2.ac_short_name as vendorName','buyer_purchse_order_master.job_status_id']);
        }
        else
        {
            
            $VendorWorkOrderList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.Ac_code', 'left outer')
            ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
            ->join('ledger_master as LM2', 'LM2.Ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
            ->join('job_status_master', 'job_status_master.job_status_id', '=', 'vendor_work_order_master.endflag', 'left outer')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no')
            ->where('vendor_work_order_master.delflag','=', '0')
            ->where('vendor_work_order_master.vw_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            ->get(['vendor_work_order_master.*','usermaster.username','ledger_master.ac_short_name as Ac_name','season_master.season_name','job_status_master.job_status_name', 'LM2.ac_short_name as vendorName','buyer_purchse_order_master.job_status_id']);
        }
        
        if ($request->ajax()) 
        {
            return Datatables::of($VendorWorkOrderList)
            ->addIndexColumn()
            ->addColumn('vw_code1',function ($row) {
        
                 $vw_codeData =substr($row->vw_code,3,15);
        
                 return $vw_codeData;
            })  
            ->addColumn('updated_at',function ($row) {
        
                 $updated_at = date("d-m-Y h:i:s", strtotime($row->updated_at));
        
                 return $updated_at;
            }) 
            ->addColumn('action1', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="JobWorkGarmentContractPrint/'.$row->vw_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action2', function ($row) 
            {
                $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="VWPrint/'.$row->vw_code.'" title="print">
                            <i class="fas fa-print"></i>
                            </a>';
                return $btn1;
            })
            ->addColumn('action3', function ($row) use ($chekform)
            {
                if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                {  
                    if($row->job_status_id != 2 )
                    {
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('VendorWorkOrder.edit', $row->vw_code).'" >
                                <i class="fas fa-pencil-alt"></i>
                           </a>';
                    }
                    else
                    {
                        $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                    }
                }
                else
                { 
                    $btn3 = '<a class="btn btn-primary btn-icon btn-sm">
                                <i class="fas fa-lock"></i>
                            </a>';   
                }
                return $btn3;
            })
            ->addColumn('action4', function ($row) use ($chekform){
         
         
               $stichingData = DB::SELECT("select count(*) as total_count FROM stitching_inhouse_master WHERE vw_code='".$row->vw_code."'");
               $total_count = isset($stichingData[0]->total_count) ? $stichingData[0]->total_count : 0;
         
                if($chekform->delete_access==1 && $total_count == 0 && $row->username == Session::get('username') || Session::get('user_type') == 1)
                {      
         
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->vw_code.'"  data-route="'.route('VendorWorkOrder.destroy', $row->vw_code).'"><i class="fas fa-trash"></i></a>'; 
                }  
                else
                {
                    $btn4 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
               
                }
                
                
                
                return $btn4;
            })
            ->addColumn('action5', function ($row)  use ($chekform)
            {
                if($chekform->edit_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1 || Session::get('user_type') == 3)
                {  
                    if($row->endflag != 2)
                    {
                        $btn1 = '<a class="btn btn-outline-warning btn-sm" href="javascript:void(0);" vw_code="'.$row->vw_code.'" onclick="closeOrder(this);" title="close_order">
                           <i class="fas fa-home"></i>
                            </a>';
                    }
                    else
                    {
                        $btn1 = 'Closed';
                    }
                }
                else
                {
                      $btn1 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; ;
                }
                
                
                return $btn1;
            })
            ->rawColumns(['action1','action2','action3','action4','action5','updated_at'])
    
            ->make(true);
        }
        return view('VendorWorkOrderList', compact('VendorWorkOrderList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='VendorWorkOrder'");
        //$ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $Ledger2 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList5= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
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
  
        return view('VendorWorkOrder',compact('UnitList','Ledger2',  'ClassList','ClassList2','ClassList3','ItemList5','ItemList2','ItemList3','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList', 'counter_number'));

         
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
            ->where('type','=','VendorWorkOrder')
            ->where('firm_id','=',1)
            ->first();
            $TrNo=$codefetch->code.'-'.$codefetch->tr_no;  
        
        
        
    
       try {  
           
             DB::beginTransaction();
           
            $this->validate($request, [
                 
                    'vw_date'=> 'required', 
                    'Ac_code'=> 'required', 
                    'sales_order_no'=>'required',
                
                   
            ]);
 
             
            $data1=array(
                       
                    'vw_code'=>$TrNo, 
                    'vw_date'=>$request->vw_date, 
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
                    'narration'=>$request->narration,
                    'debit_reject_garment'=>$request->debit_reject_garment,
                    'userId'=>$request->userId,
                    'delflag'=>'0',
                    'c_code'=>$request->c_code,
                    'vendorId'=>$request->vendorId,
                    'cons_per_piece'=>$request->cons_per_piece,
                    'vendorRate'=>$request->vendorRate,
                    'final_bom_qty'=>$request->final_bom_qty,
                    'delivery_date'=>$request->delivery_date,
                    'endflag'=>'1'
                    
                );
             
                VendorWorkOrderModel::insert($data1);
                DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='VendorWorkOrder'");
            
                $color_id= $request->input('color_id');
                if(count($color_id)>0)
                {   
                
                for($x=0; $x<count($color_id); $x++) {
                    # code...
                  if($request->size_qty_total[$x]>0)
                          {
                                $data2[]=array(
                      
                                'vw_code'=>$TrNo,
                                'vw_date'=>$request->vw_date,
                                'sales_order_no'=>$request->sales_order_no,
                                'Ac_code'=>$request->Ac_code,
                                'po_code'=>$request->po_code,
                                'style_no'=>$request->style_no,
                                'item_code'=>$request->item_codeo[$x],
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
                              
                                    'vw_code'=>$TrNo, 
                                    'vw_date'=>$request->vw_date, 
                                    'Ac_code'=>$request->Ac_code,
                                    'sales_order_no'=>$request->sales_order_no,
                                    'po_code'=>$request->po_code,
                                    'style_no'=>$request->style_no,
                                    'item_code'=>$request->item_codeo[$x],
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
                      VendorWorkOrderDetailModel::insert($data2);
                      VendorWorkOrderSizeDetailModel::insert($data3);
                      
                     
                }
                
                
            //     $item_code= $request->input('item_code');
            //     if(isset($item_code) && count($item_code)>0)
            //     {   
                
            //     for($x=0; $x<count($item_code); $x++) 
            //     {
            //         # code...
             
            //             $data4[]=array(
            //             'vw_code'=>$TrNo, 
            //             'vw_date'=>$request->vw_date,  
            //             'cost_type_id'=>$request->cost_type_id,
            //             'Ac_code'=>$request->Ac_code, 
            //             'sales_order_no'=>$request->sales_order_no,
            //             'season_id'=>$request->season_id,
            //             'currency_id'=>$request->currency_id, 
            //             'item_code' => $request->item_code[$x],
            //             'class_id' => $request->class_id[$x],
            //             'description' => $request->description[$x],
            //             'consumption' => $request->consumption[$x],
            //             'unit_id'=> $request->unit_id[$x],
            //             'wastage' => $request->wastage[$x],
            //             'bom_qty' => $request->bom_qty[$x] ,
            //             'final_cons' => $request->final_cons[$x],
                        
            //             'actual_qty' => $request->bom_qty1[$x],
            //             'size_qty' => $request->size_qty[$x] 
            //             );
                           
            //     } // if loop avoid zero qty
                           
            //               VendorWorkOrderFabricDetailModel::insert($data4);
            //             }
                     
            
            //   $item_codesx = $request->input('item_codesx');
            // if(isset($item_codesx))
            // {     if(isset($item_codesx) && count($item_codesx)>0)
            //     {
            //      for($x=0; $x<count($item_codesx); $x++) 
            //      {
            //         # code...
            //             $data6[]=array(
            //             'vw_code'=>$TrNo, 
            //             'vw_date'=>$request->vw_date, 
            //             'cost_type_id'=>$request->cost_type_id,
            //             'Ac_code'=>$request->Ac_code, 
            //             'sales_order_no'=>$request->sales_order_no,
            //             'season_id'=>$request->season_id,
            //             'currency_id'=>$request->currency_id, 
            //             'item_code' => $request->item_codesx[$x],
            //             'class_id' => $request->class_idsx[$x],
            //             'description' => $request->descriptionsx[$x],
            //             'consumption' => $request->consumptionsx[$x],
            //             'unit_id'=> $request->unit_idsx[$x],
            //             'wastage' => $request->wastagesx[$x],
            //             'bom_qty' => $request->bom_qtysx[$x],
            //             'final_cons' => $request->final_conssx[$x],
            //             'actual_qty' => $request->bom_qtysx1[$x],
            //             'size_qty' => $request->size_qtysx[$x] 
            //              );
            //             }
            //           VendorWorkOrderTrimFabricDetailModel::insert($data6);
            //     }
            // }            
                     
                     
                     
                      
              $item_codes = $request->input('item_codess');
                if(isset($item_codes) && count($item_codes)>0)
                {
                    for($x=0; $x<count($item_codes); $x++) 
                    {
                        $data5[]=array(
                        'vw_code'=>$TrNo, 
                        'vw_date'=>$request->vw_date, 
                        'cost_type_id'=>$request->cost_type_id,
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
                        'final_cons' => $request->final_conss[$x],
                        'actual_qty' => $request->bom_qtyss1[$x],
                        'size_qty' => $request->size_qtyss[$x] 
                         );
                    }
                    VendorWorkOrderSewingTrimsDetailModel::insert($data5);
                }
                 
            // $item_codess = $request->input('item_codess');
            //     if(isset($item_codess) && count($item_codess)>0)
            //     {
            //      for($x=0; $x<count($item_codess); $x++) {
            //         # code...
                   
            //             $data7[]=array(
            //             'vw_code'=>$TrNo, 
            //             'vw_date'=>$request->vw_date,  
            //             'cost_type_id'=>$request->cost_type_id,
            //             'Ac_code'=>$request->Ac_code, 
            //             'sales_order_no'=>$request->sales_order_no,
            //             'season_id'=>$request->season_id,
            //             'currency_id'=>$request->currency_id, 
            //             'item_code' => $request->item_codess[$x],
            //             'class_id' => $request->class_idss[$x],
            //             'description' => $request->descriptionss[$x],
            //             'consumption' => $request->consumptionss[$x],
            //             'unit_id'=> $request->unit_idss[$x],
            //             'wastage' => $request->wastagess[$x],
            //             'bom_qty' => $request->bom_qtyss[$x],
            //             'final_cons' => $request->final_consss[$x],
            //              'actual_qty' => $request->bom_qtyss1[$x],
            //             'size_qty' => $request->size_qtyss[$x] 
                        
            //             );
            //             }
            //           VendorWorkOrderPackingTrimsDetailModel::insert($data7);
                      
                      DB::select('call AddSizeQtyFromVendorWorkOrder("'.$TrNo.'")');
                // } 

            DB::commit();
       
            return redirect()->route('VendorWorkOrder.index')->with('message', 'Data Saved Succesfully');  
      
         }
         catch (\Exception $e) 
         {
            // If an exception occurs, rollback the transaction and handle the exception
             \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
          
              DB::rollBack();
      
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
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

    public function VWPrint($vw_code)
    {
        
       $BOMList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_work_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_work_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_work_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_work_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_work_order_master.fg_id', 'left outer')  
         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no', 'left outer')    
        ->where('vendor_work_order_master.delflag','=', '0')
        ->where('vendor_work_order_master.vw_code','=', $vw_code)
        ->get(['buyer_purchse_order_master.sam','vendor_work_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name','ledger_master.pan_no as buyerpan','ledger_master.address']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
    
        return view('VendorWorkOrderPrint', compact('BOMList'));     
    }

     public function VWPrintView($vw_code)
    {

       $BOMList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_work_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_work_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_work_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_work_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_work_order_master.fg_id', 'left outer')  
         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no', 'left outer')   
         ->join('ledger_master as buyerLedger', 'buyerLedger.Ac_code', '=', 'buyer_purchse_order_master.Ac_code', 'left outer')
        ->where('vendor_work_order_master.delflag','=', '0')
        ->where('vendor_work_order_master.vw_code','=', $vw_code)
        ->get(['buyer_purchse_order_master.sam','vendor_work_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name','ledger_master.pan_no as buyerpan','ledger_master.address'
        ,'ledger_master.ac_short_name as Ac_name','buyerLedger.Ac_name as buyer_name']);

          return view('VendorWorkOrderPrintView', compact('BOMList'));     
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $JobStatusList= DB::table('job_status_master')->whereIn('job_status_id',[1,2])->get();
       // $ItemList = ItemModel::where('delflag','=', '0')->where('class_id','=', $codefetch->class_id)->get();
      
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
          
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList5= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $Ledger2 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        
        $VendorWorkOrderMasterList = VendorWorkOrderModel::find($id);
        
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$VendorWorkOrderMasterList->sales_order_no)->distinct()->get();
        
        
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$VendorWorkOrderMasterList->sales_order_no)->DISTINCT()->get();
        
        $VendorWorkOrderDetailList = VendorWorkOrderDetailModel::where('vendor_work_order_detail.vw_code','=', $VendorWorkOrderMasterList->vw_code)->get();
        // DB::enableQueryLog(); 
        $FabricList = VendorWorkOrderFabricDetailModel::where('vendor_work_order_fabric_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->get();
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        //$SewingTrimsList = VendorWorkOrderSewingTrimsDetailModel::where('vendor_work_order_sewing_trims_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->orderBy('item_code')->get();
       
        
       $BOMList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_work_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_work_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_work_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_work_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_work_order_master.fg_id', 'left outer')  
         ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'vendor_work_order_master.sales_order_no', 'left outer')    
        ->where('vendor_work_order_master.delflag','=', '0')
        ->where('vendor_work_order_master.vw_code','=', $VendorWorkOrderMasterList->vw_code)
        ->get(['buyer_purchse_order_master.sam','vendor_work_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name','ledger_master.pan_no as buyerpan','ledger_master.address']);
        
        // $PackingTrimsList = VendorWorkOrderPackingTrimsDetailModel::
        //     where('vendor_work_order_packing_trims_details.vw_code','=', '00')
        //     ->orderBy('item_code')->get();
       
        $TrimFabricList = VendorWorkOrderTrimFabricDetailModel::where('vendor_work_order_trim_fabric_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->orderBy('item_code')->get();
        // DB::enableQueryLog(); 
        
        $S1= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
        ->whereNotIn('sales_order_costing_master.sales_order_no',function($query){
        $query->select('sales_order_no')->from('vendor_work_order_master');
        });
        
        $S2=VendorWorkOrderModel::select('sales_order_no')->where('sales_order_no',$VendorWorkOrderMasterList->sales_order_no);
        $SalesOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($VendorWorkOrderMasterList->sales_order_no);
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
       //DB::enableQueryLog();
        $StitchingGRNList = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail 
        left join color_master on color_master.color_id=stitching_inhouse_size_detail.color_id 
        where sales_order_no='".$VendorWorkOrderMasterList->sales_order_no."' AND stitching_inhouse_size_detail.vw_code='".$VendorWorkOrderMasterList->vw_code."' 
        group by stitching_inhouse_size_detail.color_id");
       //dd(DB::getQueryLog());
        
        return view('VendorWorkOrderEdit',compact('BOMList','StitchingGRNList','JobStatusList',  'VendorWorkOrderDetailList','Ledger2','TrimFabricList','ColorList','ItemList5','SizeDetailList','VendorWorkOrderMasterList','FabricList', 'UnitList','ClassList','ClassList2','ItemList','ItemList2','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
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
                'vw_date'=> 'required', 
                'Ac_code'=> 'required', 
                'sales_order_no'=> 'required', 
        ]);
        
        try
        {
            
            DB::beginTransaction();
           
            $data1=array(
                            'vw_code'=>$request->vw_code, 
                            'vw_date'=>$request->vw_date, 
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
                            'narration'=>$request->narration,
                            'debit_reject_garment'=>$request->debit_reject_garment,
                            'userId'=>$request->userId,
                            'delflag'=>'0',
                            'c_code'=>$request->c_code,
                            'vendorId'=>$request->vendorId,
                            'cons_per_piece'=>$request->cons_per_piece,
                            'vendorRate'=>$request->vendorRate,
                            'final_bom_qty'=>$request->final_bom_qty,
                            'delivery_date'=>$request->delivery_date,
                            'endflag'=>$request->endflag,
                );
                
            //   DB::enableQueryLog(); 
            $VendorWorkOrderList = VendorWorkOrderModel::findOrFail($request->vw_code); 
            //  $query = DB::getQueryLog();
            //         $query = end($query);
            //         dd($query);
             
            $VendorWorkOrderList->fill($data1)->save();
            
            DB::table('vendor_work_order_detail')->where('vw_code', $request->input('vw_code'))->delete();
            // DB::table('vendor_work_order_fabric_details')->where('vw_code', $request->input('vw_code'))->delete();
            // DB::table('vendor_work_order_trim_fabric_details')->where('vw_code', $request->input('vw_code'))->delete();
            // DB::table('vendor_work_order_packing_trims_details')->where('vw_code', $request->input('vw_code'))->delete();
            DB::table('vendor_work_order_sewing_trims_details')->where('vw_code', $request->input('vw_code'))->delete();
            DB::table('vendor_work_order_size_detail')->where('vw_code', $request->input('vw_code'))->delete(); 
             
             
              $color_id= $request->input('color_id');
                if(count($color_id)>0)
                {   
                
                for($x=0; $x<count($color_id); $x++) {
                    # code...
                  if($request->size_qty_total[$x]>0)
                          {
                                $data2[]=array(
                      
                                'vw_code'=>$request->vw_code, 
                                'vw_date'=>$request->vw_date, 
                                'sales_order_no'=>$request->sales_order_no,
                                'Ac_code'=>$request->Ac_code,
                                'po_code'=>$request->po_code,
                                'style_no'=>$request->style_no,
                                'item_code'=>$request->item_codeo[$x],
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
                              
                                    'vw_code'=>$request->vw_code, 
                                    'vw_date'=>$request->vw_date,  
                                    'Ac_code'=>$request->Ac_code,
                                    'sales_order_no'=>$request->sales_order_no,
                                    'po_code'=>$request->po_code,
                                    'style_no'=>$request->style_no,
                                    'item_code'=>$request->item_codeo[$x],
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
                      VendorWorkOrderDetailModel::insert($data2);
                      VendorWorkOrderSizeDetailModel::insert($data3);
                      
                     
                }
                
                
                // $item_code= $request->input('item_code');
                // if(isset($item_code) && count($item_code)>0)
                // {   
                
                // for($x=0; $x<count($item_code); $x++) 
                // {
                //     # code...
             
                //         $data4[]=array(
                //       'vw_code'=>$request->vw_code, 
                //         'vw_date'=>$request->vw_date,  
                //         'cost_type_id'=>$request->cost_type_id,
                //         'Ac_code'=>$request->Ac_code, 
                //         'sales_order_no'=>$request->sales_order_no,
                //         'season_id'=>$request->season_id,
                //         'currency_id'=>$request->currency_id, 
                //         'item_code' => $request->item_code[$x],
                //         'class_id' => $request->class_id[$x],
                //         'description' => $request->description[$x],
                       
                //         'consumption' => $request->consumption[$x],
                //         'unit_id'=> $request->unit_id[$x],
                //         'wastage' => $request->wastage[$x],
                //         'bom_qty' => $request->bom_qty[$x] ,
                //         'final_cons' => $request->final_cons[$x],
                //          'actual_qty' => $request->bom_qty1[$x],
                //         'size_qty' => $request->size_qty[$x] 
                //         );
                           
                // } // if loop avoid zero qty
                           
                //           VendorWorkOrderFabricDetailModel::insert($data4);
                //         }
                    
                    
                // $item_codesx = $request->input('item_codesx');
                // if(isset($item_codesx) && count($item_codesx)>0)
                // {
                //  for($x=0; $x<count($item_codesx); $x++) 
                //  {
                //     # code...
                //         $data6[]=array(
                //         'vw_code'=>$request->vw_code, 
                //         'vw_date'=>$request->vw_date, 
                //         'cost_type_id'=>$request->cost_type_id,
                //         'Ac_code'=>$request->Ac_code, 
                //         'sales_order_no'=>$request->sales_order_no,
                //         'season_id'=>$request->season_id,
                //         'currency_id'=>$request->currency_id, 
                //         'item_code' => $request->item_codesx[$x],
                //         'class_id' => $request->class_idsx[$x],
                //         'description' => $request->descriptionsx[$x],
                        
                        
                //         'consumption' => $request->consumptionsx[$x],
                //         'unit_id'=> $request->unit_idsx[$x],
                //         'wastage' => $request->wastagesx[$x],
                //         'bom_qty' => $request->bom_qtysx[$x],
                //         'final_cons' => $request->final_conssx[$x],
                //         'actual_qty' => isset($request->bom_qtyssx1[$x]) ? bom_qtyssx1[$x] : 0,
                //         'size_qty' => isset($request->size_qtysx[$x]) ? $request->size_qtysx[$x] : 0
                //          );
                //         }
                //       VendorWorkOrderTrimFabricDetailModel::insert($data6);
                // }    
                    
                      
              $item_codes = $request->input('item_codess');
                if(isset($item_codes) && count($item_codes)>0)
                {
                 for($x=0; $x<count($item_codes); $x++) 
                 {
                    # code...
                        $data5[]=array(
                        'vw_code'=>$request->vw_code,  
                        'vw_date'=>$request->vw_date, 
                        'cost_type_id'=>$request->cost_type_id,
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
                        'final_cons' => $request->final_conss[$x],
                        'actual_qty' => $request->bom_qtyss1[$x],
                        'size_qty' => $request->size_qtyss[$x] 
                         );
                 }
                      //DB::enableQueryLog();
                      VendorWorkOrderSewingTrimsDetailModel::insert($data5);
                      //dd(DB::getQueryLog());
                }
                 
            // $item_codess = $request->input('item_codess');
            //     if(isset($item_codess) && count($item_codess)>0)
            //     {
            //      for($x=0; $x<count($item_codess); $x++) {
            //         # code...
                   
            //             $data7[]=
            //             array(
            //                     'vw_code'=>$request->vw_code, 
            //                     'vw_date'=>$request->vw_date,  
            //                     'cost_type_id'=>$request->cost_type_id,
            //                     'Ac_code'=>$request->Ac_code, 
            //                     'sales_order_no'=>$request->sales_order_no,
            //                     'season_id'=>$request->season_id,
            //                     'currency_id'=>$request->currency_id, 
            //                     'item_code' => $request->item_codess[$x],
            //                     'class_id' => $request->class_idss[$x],
            //                     'description' => $request->descriptionss[$x],
            //                     'consumption' => $request->consumptionss[$x],
            //                     'unit_id'=> $request->unit_idss[$x],
            //                     'wastage' => $request->wastagess[$x],
            //                     'bom_qty' => $request->bom_qtyss[$x],
            //                     'final_cons' => $request->final_consss[$x],
            //                     'actual_qty' => $request->bom_qtyss1[$x],
            //                     'size_qty' => $request->size_qtyss[$x] 
            //           );
            //             }
                    //   VendorWorkOrderPackingTrimsDetailModel::insert($data7);
                      
                      DB::select('call AddSizeQtyFromVendorWorkOrder("'.$request->vw_code.'")');
                // } 
       
                DB::commit();
                return redirect()->route('VendorWorkOrder.index')->with('message', 'Data Update Succesfully'); 
         
         } 
         catch (\Exception $e) 
         {
            \Log::info('Message:: '.$e->getMessage().' line:: '.$e->getLine().' Code:: '.$e->getCode().' file:: '.$e->getFile());
          
            DB::rollBack();
          
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
         
        }
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
        
        $MasterdataList = DB::select("select *, (select sales_order_costing_master.production_value from sales_order_costing_master where sales_order_no='".$sales_order_no."') as production_value  from buyer_purchse_order_master where buyer_purchse_order_master.delflag=0 and tr_code='".$sales_order_no."'");
        
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
//     $item_code= $request->item_code;
//     $sales_order_no= $request->sales_order_no;
// //print_r($item_code);
//     $codefetch = DB::table('item_master')->select("class_id")
//     ->where('item_code','=',$request->item_code)
//     ->first();
//     $Class_id=$codefetch->class_id;
 
//     $data = DB::select(DB::raw("SELECT distinct class_id , `description`, `consumption`,
//     `rate_per_unit`, `wastage`,`bom_qty`, `total_amount` from sales_order_packing_trims_costing_details
//     where  class_id=$Class_id and sales_order_no='$sales_order_no'")); 
//     echo json_encode($data);

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

  
  public function W_GetOrderQty(Request $request)
  {
      // W_   as Vendor Work Order sa same function name is defined in BOM, So Name prepended by W_
      
      $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($request->tr_code);
    //   DB::enableQueryLog();  
      $BuyerPurchaseOrderDetailList = BuyerPurchaseOrderDetailModel::where('tr_code',$request->tr_code)->first();
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
       $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
       ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
       ->where('tr_code','=',$request->tr_code)->DISTINCT()->get();
      
   
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
        //  DB::enableQueryLog();  
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
                  <th>Action   <input type="button" class="size_btn btn-primary" id="MBtn" is_click="0" value="Calculate All" onclick="MainBtn();"></th>
                  </tr>
              </thead>
              <tbody>';
          $no=1;
          foreach ($MasterdataList as $row) 
        {
          $html .='<tr>';
          $html .='
          <td>'.$no.'</td>';
           
        $html.=' <td> <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" disabled>
        <option value="">--Select Color--</option>';

        foreach($ColorList as  $row1)
        {
            $html.='<option value="'.$row1->color_id.'"';
            $row1->color_id == $row->color_id ? $html.='selected="selected"' : '';
            $html.='>'.$row1->color_name.'</option>';
        }
        
        $html.='</select>
        
          <input type="hidden" name="item_codeo[]"  value="'.$row->item_code.'" id="item_codeo" style="width:80px; height:30px; float:left;"  />
        </td>';


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
          
          
          
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" max="'.($total_qty-$List->size_qty_total).'" style="width:80px; height:30px; float:left;" required readOnly  />
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
  
  public function W_GetSizeList(Request $request)
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
     
public function W_GetColorList(Request $request)
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


public function W_GetItemList(Request $request)
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

public function W_GetClassList(Request $request)
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
    
     
     
     
     
       public function GetFabricConsumption(Request $request)
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
  
  
  // DB::enableQueryLog();

  $codefetch = DB::table('bom_fabric_details')->select("item_code","consumption","description","wastage","class_id","unit_id","rate_per_unit")
  ->where('item_code','=',$tr_code->item_code) ->where('sales_order_no','=',$sales_order_no)
  ->first();
 // dd(DB::getQueryLog());
//   $class_ids = isset($codefetch->class_id) ? $codefetch->class_id : 0;
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
 
 <td><input type="text"    name="description[]" value="'.$codefetch->description.'" id="description" style="width:200px; height:30px;" readOnly   /></td> 
 
<td><input type="number" step="any"    name="consumption[]" value="'.$codefetch->consumption.'" readOnly id="consumption" style="width:80px; height:30px;" required /></td> 
  
<td> <select name="unit_id[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
<option value="">--Unit--</option>';
foreach($UnitList as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $codefetch->unit_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowunit->unit_name.'</option>';
}
$html.='</select></td> 

<td><input type="number" step="any" max="'.$codefetch->wastage.'" min="0"  name="wastage[]" value="0"  class="WASTAGE" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
<td><input type="text"  name="bom_qty[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="bom_qty1[]" value="'.$bom_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="final_cons[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
<input type="hidden"  name="size_qty[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
 
</td> 

';
     $html .='</tr>';
      
    return response()->json(['html' => $html]);
     
    }  
     
     
     
     
     
      public function GetTrimFabricConsumption(Request $request)
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
    
  $ClassList = ClassificationModel::where('delflag','=', '0')->get();
  //DB::enableQueryLog(); 
  
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
   $ItemList = ItemModel::where('delflag','=', '0')->get();
 // print_r(count($codefetch));
  $html = '';

     $x=0;$qty=0;
     
foreach($SizeList as $sz)
 { 
     
     
        $qty=$SizeQty[$x++];
        if($qty!=0)
        {
            
            // DB::enableQueryLog();
           $codefetch = DB::table('bom_trim_fabric_details')->select("item_code","consumption","description","wastage","class_id","unit_id")
          ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
          ->orderBy('item_code', 'ASC')->get(); 
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
           
            foreach($codefetch as $rowsew)
            {   
                // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                // $bom_qty=round(($rowsew->consumption*$qty),2);
            $total_consumption= ($rowsew->consumption);
            $bom_qty=round(($rowsew->consumption*$qty),2);
                
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="idsx[]" value="'.$no.'" id="idsx" style="width:50px;"/></td>';
                $html.=' 
                <td> <select name="item_codesx[]"  id="item_codesx'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Item List--</option>';
                foreach($ItemList as  $rowitem)
                {
                    $html.='<option value="'.$rowitem->item_code.'"';
                    $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowitem->item_name.'</option>';
                }
                $html.='</select></td>
                <td> <select name="class_idsx[]"  id="class_idsx'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text" readOnly   name="descriptionsx[]" value="'.$rowsew->description.'" id="descriptionx" style="width:200px; height:30px;"   /></td> 
                <td><input type="number" step="any" readOnly   name="consumptionsx[]" value="'.$rowsew->consumption.'" id="consumptionx" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_idsx[]"  id="unit_idsx'.$no.'" style="width:100px; height:30px;" required disabled>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="any" max="'.$rowsew->wastage.'" min="0"   name="wastagesx[]" value="0" class="WASTAGE1" id="wastagesx'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtysx[]" value="'.$bom_qty.'" id="bom_qtysx'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="bom_qtysx1[]" value="'.$bom_qty.'" id="bom_qtysx1'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_conssx[]" value="'.$total_consumption.'" id="final_conss'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtysx[]" value="'.$size_qty_total.'" id="size_qtysx'.$no.'" style="width:80px; height:30px;" required readOnly />
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
        
           $codefetch = DB::table('bom_packing_trims_details')->select('sales_order_no',"item_code","consumption","description","wastage","class_id","unit_id")
          ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
          ->orderBy('item_code', 'ASC')->get(); 
          
        //   $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
           
            foreach($codefetch as $rowsew)
            {   
                $sizes ="";
                $SizeListFromBOM=DB::select("select size_array from bom_packing_trims_details where sales_order_no='".$rowsew->sales_order_no."' and item_code='".$rowsew->item_code."' limit 0,1");
                                           
                $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
           
                if(isset($SizeListFromBOM[0]->size_array))
                {
                    $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
                    $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                    foreach($SizeDetailList as $sz)
                    {
                         $sizes=$sizes.$sz->size_name.', ';
                    }
                }
                //$total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                //$bom_qty=round(($total_consumption*$qty),2);
                $total_consumption= ($rowsew->consumption);
                $bom_qty=round(($rowsew->consumption*$qty),2);
                
                $html .='<tr class="thisRow">';
                $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
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
                 <td>'.$ColorListpacking[0]->color_name.'</td>
                 <td>'.rtrim($sizes, ', ').'</td>
                <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required disabled>
                <option value="">--Classification--</option>';
                foreach($ClassList as  $rowclass)
                {
                    $html.='<option value="'.$rowclass->class_id.'"';
                    $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                    $html.='>'.$rowclass->class_name.'</option>';
                }
                $html.='</select></td> 
                <td><input type="text" readOnly   name="descriptionss[]" value="'.$rowsew->description.'" id="description" style="width:200px; height:30px;"   /></td> 
                <td><input type="number" step="any" readOnly   name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
                <option value="">--Unit--</option>';
                foreach($UnitList as  $rowunit)
                {
                   $html.='<option value="'.$rowunit->unit_id.'"';
                   $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                   $html.='>'.$rowunit->unit_name.'</option>';
                }
                $html.='</select></td>
                <td><input type="number" step="any" max="'.$rowsew->wastage.'" min="0"   name="wastagess[]" value="0"  class="WASTAGE3" id="wastage'.$no.'" style="width:80px; height:30px;" required /></td> 
                <td><input type="text"  name="bom_qtyss[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="bom_qtyss1[]" value="'.$qty.'" id="bom_qtyss1'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;" required readOnly />
                <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                </td> 
                ';
                $html .='</tr>';
                $no++;
            } //main foreach
    }  // if loop for size qty 0
     
 }  // Size Array Foreach
    return response()->json(['html' => $html]);
     
    }    
     
     
    public function getVendorAllWorkOrders(Request $request)
    {
        //   DB::enableQueryLog();
             
         $POList = DB::select("select vw_code,sales_order_no from vendor_work_order_master where vendorId ='".$request->vendorId."'");
            // $query = DB::getQueryLog();
            // $query = end($query);
            // dd($query);
        
        if (!$request->vendorId)
        {
            $html = '<option value="">--Select--</option>';
            } else {
            $html = '';
            $html = '<option value="">--Select--</option>';
            
            foreach ($POList as $row)  
            { $vw_code=$row->vw_code;$html .= '<option value="'.$vw_code.'">'.$row->vw_code.'('.$row->sales_order_no.')</option>';}
        }
          return response()->json(['html' => $html]);
    }
     
    public function SalesOrderNoList(Request $request)
    {
        
        if($request->vendorId == 'All')
        {
            $SalesOrderList = DB::select("select sales_order_no from vendor_work_order_master WHERE 1 GROUP BY sales_order_no");
        }
        else
        {
            $SalesOrderList = DB::select("select sales_order_no from vendor_work_order_master where vendorId ='".$request->vendorId."' GROUP BY sales_order_no");
        }
        if (!$request->vendorId)
        {
            $html = '<option value="">--Select--</option>';
            } else {
            $html = '';
            $html = '<option value="All">All</option>';
            
            foreach ($SalesOrderList as $row)  
            { 
                $html .= '<option value="'.$row->sales_order_no.'">'.$row->sales_order_no.'</option>';
            }
        }
          return response()->json(['html' => $html]);
    }
     
    public function destroy($id)
    { 
          // DB::enableQueryLog();
            $Datacount=   DB::select("SELECT (select count(trimOutCode)  FROM `trimOutwardMaster` WHERE vw_code= '".$id."') as trimcounts,
            (select count(cpi_code)  FROM `cut_panel_issue_master` WHERE vw_code= '".$id."') as cpicounts
            
            ");
            // dd(DB::getQueryLog());
             
            $counts=($Datacount[0]->trimcounts + $Datacount[0]->cpicounts);
            $Data=   DB::select("SELECT (select GROUP_CONCAT(trimOutCode)   FROM `trimOutwardMaster` WHERE vw_code= '".$id."') as trimOutCode,
             (select GROUP_CONCAT(cpi_code)   FROM `cut_panel_issue_master` WHERE vw_code= '".$id."') as cpi_code
            ");
            
            
            
            
            
            if($counts==0)
            {
                DB::table('vendor_work_order_master')->where('vw_code', $id)->delete();  
                DB::table('vendor_work_order_detail')->where('vw_code', $id)->delete();
                DB::table('vendor_work_order_fabric_details')->where('vw_code', $id)->delete();
                DB::table('vendor_work_order_packing_trims_details')->where('vw_code', $id)->delete();
                DB::table('vendor_work_order_sewing_trims_details')->where('vw_code', $id)->delete();
                DB::table('vendor_work_order_size_detail')->where('vw_code', $id)->delete();
                Session::flash('messagedelete', 'Deleted record successfully'); 
            }
            else
            {
                Session::flash('messagedelete', "Vendor Work Order can't be deleted, Remove References -> Trims Outward: ".$Data[0]->trimOutCode." ---------------- Cut Panel Issue: ".$Data[0]->cpi_code); 
            }
   
}
    
    public function GetVendorWorkOrderOCR()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where delflag=0 and og_id!=4');
        
        return view('GetVendorWorkOrderOCR',compact('Ledger','SalesOrderList'));
    }
     
    public function rptVendorWorkOrderOCR(Request $request)
    {
        $vendorId = $request->vendorId;
        $sales_order_no = $request->sales_order_no;
        $vw_code = $request->vw_code;
        //DB::enableQueryLog();
        
        $VendorWorkOrderList =  DB::table('vendor_work_order_master')->select('vendor_work_order_master.*','ledger_master.ac_name','fg_master.fg_name') 
                                ->join('ledger_master', 'ledger_master.ac_code', '=', 'vendor_work_order_master.Ac_code')
                                ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_work_order_master.fg_id')
                                ->where('vendorId', '=',$request->vendorId)    
                                ->where('sales_order_no', '=',$request->sales_order_no)   
                                ->where('vw_code', '=',$request->vw_code)   
                                ->get();
                                
                                
        //dd(DB::getQueryLog());
        return view('rptVendorWorkOrderOCR',compact('VendorWorkOrderList','vendorId','sales_order_no','vw_code'));
        
    }
    
    public function JobWorkGarmentContractPrint($vw_code)
    {
        $VendorList = VendorWorkOrderModel::join('usermaster', 'usermaster.userId', '=', 'vendor_work_order_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'vendor_work_order_master.vendorId', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'vendor_work_order_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'vendor_work_order_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'vendor_work_order_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'vendor_work_order_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'vendor_work_order_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'vendor_work_order_master.fg_id', 'left outer')   
        ->where('vendor_work_order_master.delflag','=', '0')
        ->where('vendor_work_order_master.vw_code','=', $vw_code)
        ->get(['vendor_work_order_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name',
        'currency_master.currency_name','main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name','ledger_master.pan_no as buyerpan','ledger_master.address']);
   
        return view('JobWorkGarmentContractPrint', compact('VendorList'));
    }
    
    public function GetWorkOrderDeviation()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $SalesOrderList = DB::select("select DISTINCT cut_panel_issue_size_detail2.sales_order_no from vendor_work_order_master 
        INNER JOIN cut_panel_issue_size_detail2 ON cut_panel_issue_size_detail2.sales_order_no = vendor_work_order_master.sales_order_no");
        
        $vendorWorkOrderList = DB::select("select DISTINCT cut_panel_issue_size_detail2.vw_code from vendor_work_order_master 
        INNER JOIN cut_panel_issue_size_detail2 ON cut_panel_issue_size_detail2.vw_code = vendor_work_order_master.vw_code");
        
        $colorList = DB::select("select DISTINCT cut_panel_issue_size_detail2.color_id,color_master.color_name from color_master 
        INNER JOIN cut_panel_issue_size_detail2 ON cut_panel_issue_size_detail2.color_id = color_master.color_id");
        
        $lineList = DB::select("select DISTINCT cut_panel_issue_size_detail2.line_id,line_master.line_name from line_master 
        INNER JOIN cut_panel_issue_size_detail2 ON cut_panel_issue_size_detail2.line_id = line_master.line_id");
        
        return view('GetWorkOrderDeviation',compact('Ledger','SalesOrderList','vendorWorkOrderList','colorList','lineList'));
    }
    
    public function rptWorkOrderDeviation(Request $request)
    {
        $vendorId = $request->vendorId;
        $sales_order_no = $request->sales_order_no;
        $line_id = $request->line_id;
        $vw_code = $request->vw_code;
        $color_id = $request->color_id;
        
        if($vendorId > 0)
        {
            $vId = " AND cut_panel_issue_size_detail2.vendorId = '".$vendorId."'";
            $vId1 = " AND shsd.vendorId = '".$vendorId."'";
        }
        else
        {
             $vId = "";
             $vId1 = "";
        }
        
        if($sales_order_no != "")
        {
            $sId = " AND cut_panel_issue_size_detail2.sales_order_no = '".$sales_order_no."'";
            $sId1 = " AND shsd.sales_order_no = '".$sales_order_no."'";
        }
        else
        {
             $sId = "";
             $sId1 = "";
        }
        
        if($line_id > 0)
        {
            $lId = " AND cut_panel_issue_size_detail2.line_id = '".$line_id."'";
            $lId1 = " AND shsd.line_id = '".$line_id."'";
        }
        else
        {
             $lId = "";
             $lId1 = "";
        }
        
        if($vw_code > 0)
        {
            $wId = " AND cut_panel_issue_size_detail2.vw_code = '".$vw_code."'";
            $wId1 = " AND shsd.vw_code = '".$vw_code."'";
        }
        else
        {
             $wId = "";
             $wId1 = "";
        }
           
        if($color_id > 0)
        {
            $cId = " AND cut_panel_issue_size_detail2.color_id = '".$color_id."'";
            $cId1 = " AND shsd.color_id = '".$color_id."'";
        }
        else
        {
             $cId = "";
             $cId1 = "";
        }
        
        $CutPanelIssueDetails = DB::select("SELECT cpi_code, cpi_date,  cut_panel_issue_size_detail2.color_id,sales_order_no, vw_code,cut_panel_issue_size_detail2.vendorId, LM1.Ac_name, LM2.Ac_name as vendor_Name, 
                color_master.color_name,line_master.line_name,line_master.line_id
                FROM `cut_panel_issue_size_detail2`
                LEFT JOIN buyer_purchse_order_master on  buyer_purchse_order_master.tr_code= cut_panel_issue_size_detail2.sales_order_no
                LEFT JOIN ledger_master as LM1 on LM1.ac_code  =cut_panel_issue_size_detail2.Ac_code
                LEFT JOIN ledger_master as LM2 on LM2.ac_code  =cut_panel_issue_size_detail2.vendorId
                LEFT JOIN color_master on color_master.color_id=cut_panel_issue_size_detail2.color_id
                LEFT JOIN size_detail on size_detail.size_id = cut_panel_issue_size_detail2.size_id
                LEFT JOIN line_master on line_master.line_id = cut_panel_issue_size_detail2.line_id
                where cut_panel_issue_size_detail2.size_qty!=0 ".$vId." ".$sId." ".$lId." ".$wId." ".$cId." GROUP BY cut_panel_issue_size_detail2.vw_code, cut_panel_issue_size_detail2.color_id ORDER BY cut_panel_issue_size_detail2.vendorId ASC");
        // DB::enableQueryLog();   
         $sizeData =  DB::select("SELECT Distinct cut_panel_issue_size_detail2.size_id,cut_panel_issue_size_detail2.color_id, sum(cut_panel_issue_size_detail2.size_qty) as size_qty,cut_panel_issue_size_detail2.vw_code, LM2.Ac_name as vendor_Name,
                color_master.color_name,cut_panel_issue_size_detail2.vendorId,line_master.line_name,
                cut_panel_issue_size_detail2.sales_order_no,cut_panel_issue_size_detail2.size_id,size_detail.size_name 
                FROM cut_panel_issue_size_detail2 
                INNER JOIN size_detail ON size_detail.size_id = cut_panel_issue_size_detail2.size_id
                INNER JOIN ledger_master as LM2 on LM2.ac_code  =cut_panel_issue_size_detail2.vendorId
                INNER JOIN color_master on color_master.color_id=cut_panel_issue_size_detail2.color_id
                INNER JOIN line_master on line_master.line_id = cut_panel_issue_size_detail2.line_id
                WHERE 1 ".$vId." ".$sId." ".$lId." ".$wId." ".$cId." GROUP BY cut_panel_issue_size_detail2.size_id ORDER BY cut_panel_issue_size_detail2.size_id ASC");
                
               //dd(DB::getQueryLog());   
          $sizeList = DB::select("SELECT size_detail.size_id,size_detail.size_name,sum(cut_panel_issue_size_detail2.size_qty), LM2.Ac_name as vendor_Name,cut_panel_issue_size_detail2.vendorId,
                cut_panel_issue_size_detail2.sales_order_no,cut_panel_issue_size_detail2.vw_code,color_master.color_name,line_master.line_name FROM `cut_panel_issue_size_detail2` 
                INNER JOIN size_detail ON size_detail.size_id = cut_panel_issue_size_detail2.size_id
                INNER JOIN ledger_master as LM2 on LM2.ac_code  =cut_panel_issue_size_detail2.vendorId
                INNER JOIN color_master on color_master.color_id=cut_panel_issue_size_detail2.color_id
                INNER JOIN line_master on line_master.line_id = cut_panel_issue_size_detail2.line_id
                WHERE 1 ".$vId." ".$sId." ".$lId." ".$wId." ".$cId." GROUP BY cut_panel_issue_size_detail2.size_id");
         // DB::enableQueryLog();
        // $sizeList = DB::select("
        //                 SELECT 
        //                             cut_panel_issue_size_detail2.size_id,
        //                             sd.size_name,
        //                             SUM(cut_panel_issue_size_detail2.size_qty) AS total_qty,
        //                             lm.ac_name AS vendor_name,
        //                             cut_panel_issue_size_detail2.vendorId,
        //                             cut_panel_issue_size_detail2.sales_order_no,
        //                             cut_panel_issue_size_detail2.vw_code,
        //                             cm.color_name,
        //                             lmst.line_name,
        //                             1 AS source_priority
        //                         FROM cut_panel_issue_size_detail2 
        //                         INNER JOIN size_detail sd ON sd.size_id = cut_panel_issue_size_detail2.size_id
        //                         INNER JOIN ledger_master lm ON lm.ac_code = cut_panel_issue_size_detail2.vendorId
        //                         INNER JOIN color_master cm ON cm.color_id = cut_panel_issue_size_detail2.color_id
        //                         INNER JOIN line_master lmst ON lmst.line_id = cut_panel_issue_size_detail2.line_id
        //                         WHERE 1 $vId $sId $lId $wId $cId GROUP BY cut_panel_issue_size_detail2.size_id
                    
        //                         UNION ALL
                    
        //                         SELECT 
        //                             shsd.size_id,
        //                             sd.size_name,
        //                             SUM(shsd.size_qty) AS total_qty,
        //                             lm.ac_name AS vendor_name,
        //                             shsd.vendorId,
        //                             shsd.sales_order_no,
        //                             shsd.vw_code,
        //                             cm.color_name,
        //                             lmst.line_name,
        //                             2 AS source_priority
        //                         FROM stitching_inhouse_size_detail2 shsd
        //                         INNER JOIN size_detail sd ON sd.size_id = shsd.size_id
        //                         INNER JOIN ledger_master lm ON lm.ac_code = shsd.vendorId
        //                         INNER JOIN color_master cm ON cm.color_id = shsd.color_id
        //                         INNER JOIN line_master lmst ON lmst.line_id = shsd.line_id
        //                         WHERE 1  $vId1 $sId1 $lId1 $wId1 $cId1 GROUP BY shsd.size_id
        //             ");
        //dd(DB::getQueryLog());
        return view('rptWorkOrderDeviation',compact("CutPanelIssueDetails","sizeData","sizeList","vendorId","sales_order_no","line_id","vw_code","color_id"));
    }
    
    public function pushDataInTable()
    {
            $vendorData = DB::select("SELECT vw_code FROM vendor_work_order_master  WHERE delflag=0");
        
            foreach($vendorData as $row)
            {
                  DB::select('call AddSizeQtyFromVendorWorkOrder("'.$row->vw_code.'")');
            }
            return 1;  
    } 
    public function WorkOrderClose(Request $request)
    {    
         DB::table('vendor_work_order_master')->where('vw_code',$request->vw_code)->update(['endflag'=>2]);
         return 1;
    } 
    
         
    public function GetSewingData(Request $request)
    {
        
        $html = "";
        $VendorWorkOrderMasterList = VendorWorkOrderModel::find($request->vw_code);
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        
        $SewingTrimsList = VendorWorkOrderSewingTrimsDetailModel::where('vendor_work_order_sewing_trims_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->orderBy('item_code')->get();
        if(count($SewingTrimsList)>0)
        {
            $no=1; 
            foreach($SewingTrimsList as $List) 
            {
                $SizeListFromBOM=DB::select("select size_array from bom_sewing_trims_details where sales_order_no='".$List->sales_order_no."' and item_code='".$List->item_code."' limit 0,1");
                $colors='';$sizes='';
                if(isset($SizeListFromBOM[0]->size_array))
                {
                    $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
                    $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                    foreach($SizeDetailList as $sz)
                    {
                    $sizes=$sizes.$sz->size_name.', ';
                    }
                    $ColorListpacking= BOMSewingTrimsDetailModel::select('color_id')
                    ->where('item_code', $List->item_code)->where('sales_order_no', $VendorWorkOrderMasterList->sales_order_no)->get();
                    $colorids = explode(',', $ColorListpacking[0]->color_id);  
                    $ColorList= VendorWorkOrderDetailModel::
                    join('color_master','vendor_work_order_detail.color_id','=','color_master.color_id')->
                    whereIn('vendor_work_order_detail.color_id', $colorids)
                    ->where('vendor_work_order_detail.sales_order_no', $VendorWorkOrderMasterList->sales_order_no)
                    ->where('vendor_work_order_detail.vw_code', $VendorWorkOrderMasterList->vw_code)
                    ->distinct('vendor_work_order_detail.color_id')->get('color_name');
                    foreach($ColorList as $color)
                    {
                         $colors=$colors.$color->color_name.', ';
                    }
                } 
            $html .= '<tr>
               <td><input type="text" name="id" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>
               <td>
                  <select name="item_codes[]" class="item_sewing_trims" id="item_codes" style="width:200px; height:30px;" required disabled>
                     <option value="">--Item List--</option>';
                     foreach($ItemList2 as  $row)
                     {
                         
                         if($row->item_code == $List->item_code)
                         {
                             $item_selected = 'selected';
                         }
                         else
                         {
                             $item_selected = '';
                         }
                         
                        $html .= '<option value="'.$row->item_code.'" '.$item_selected.'>'.$row->item_name.'</option>';
                     }
                  $html .= '</select>
               </td>
               <td>'.rtrim($colors, ',').'</td>
               <td>'.rtrim($sizes,',').'</td>
               <td>
                  <select name="class_ids[]"   id="class_ids" style="width:200px; height:30px;" required disabled>
                     <option value="">--Classification--</option>';
                     foreach($ClassList2 as  $row)
                     {
                         
                         if($row->class_id == $List->class_id)
                         {
                             $class_selected = 'selected';
                         }
                         else
                         {
                             $class_selected = '';
                         }
                         
                        $html .= '<option value="'.$row->class_id.'"  '.$class_selected.'>'.$row->class_name.'</option>';
                     }
                   $html .= '</select>
               </td>
               <td><input type="text"    name="descriptions[]" readOnly value="'.$List->description.'" id="descriptions" style="width:200px; height:30px;"   /></td>
               <td><input type="number" step="0.01" readOnly  name="consumptions[]"  value="'.$List->consumption.'" id="consumptions" style="width:80px; height:30px;" required /></td>
               <td>
                  <select name="unit_ids[]" class="select2" id="unit_ids" style="width:100px; height:30px;" required disabled>
                     <option value="">--Unit List--</option>';
                     foreach($UnitList as  $row)
                     {
                         if($row->unit_id == $List->unit_id)
                         {
                             $unit_selected = 'selected';
                         }
                         else
                         {
                             $unit_selected = '';
                         }
                         
                       $html .= '<option value="'.$row->unit_id.'" '.$unit_selected.'>'.$row->unit_name.'</option>';
                     }
                  $html .= '</select>
               </td>
               <td><input type="number" step="0.01" max="5" min="0" class="WASTAGE2"  name="wastages[]" value="'.$List->wastage.'" id="wastages" style="width:80px; height:30px;" required /></td>
               <td><input type="text" name="bom_qtys[]" value="'.$List->bom_qty.'" id="bom_qtys" style="width:80px; height:30px;" required readOnly/>
                  <input type="hidden" name="bom_qtys1[]" value="'.$List->actual_qty.'" id="bom_qtys1" style="width:80px; height:30px;" required readOnly/>
               </td>
               <input type="hidden"  name="final_conss[]" value="'.$List->final_cons.'" id="final_conss'.$no.'" style="width:80px; height:30px;" required readOnly />
               <input type="hidden"  name="size_qtys[]" value="'.$List->size_qty.'" id="size_qtys'.$no.'" style="width:80px; height:30px;" required readOnly />
            </tr>'; 
            $no=$no+1;  
            }
        }
          return response()->json(['html' => $html]);
    }
    
    public function TestVendorWorkOrder($id)
    {
        $JobStatusList= DB::table('job_status_master')->whereIn('job_status_id',[1,2])->get();
        $UnitList = UnitModel::where('delflag','=', '0')->get();
       // $ItemList = ItemModel::where('delflag','=', '0')->where('class_id','=', $codefetch->class_id)->get();
      
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
          
        $CPList= DB::table('cp_master')->get();
        $CostTypeList= DB::table('costing_type_master')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.ac_code','>', '39')->get();
        $CurrencyList = CurrencyModel::where('currency_master.delflag','=', '0')->get();
        $ItemList5= ItemModel::where('delflag','=', '0')->where('class_id','=', '7')->get();
        $ItemList2 = ItemModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '1')->get();
        $ClassList2 = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '2')->get();
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get();
        $SeasonList = SeasonModel::where('season_master.delflag','=', '0')->get();
        $Ledger2 = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $QualityList= QualityModel::where('quality_master.delflag','=', '0')->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        
        $VendorWorkOrderMasterList = VendorWorkOrderModel::find($id);
        
        $ItemList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.item_code', 'item_name')
        ->join('item_master', 'item_master.item_code', '=', 'buyer_purchase_order_detail.item_code', 'left outer')
        ->where('tr_code','=',$VendorWorkOrderMasterList->sales_order_no)->distinct()->get();
        
        
          $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$VendorWorkOrderMasterList->sales_order_no)->DISTINCT()->get();
        
        $VendorWorkOrderDetailList = VendorWorkOrderDetailModel::where('vendor_work_order_detail.vw_code','=', $VendorWorkOrderMasterList->vw_code)->get();
        // DB::enableQueryLog(); 
        $FabricList = VendorWorkOrderFabricDetailModel::where('vendor_work_order_fabric_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->get();
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        $SewingTrimsList = VendorWorkOrderSewingTrimsDetailModel::where('vendor_work_order_sewing_trims_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->orderBy('item_code')->get();
       
        // $PackingTrimsList = VendorWorkOrderPackingTrimsDetailModel::
        //     where('vendor_work_order_packing_trims_details.vw_code','=', '00')
        //     ->orderBy('item_code')->get();
       
        $TrimFabricList = VendorWorkOrderTrimFabricDetailModel::where('vendor_work_order_trim_fabric_details.vw_code','=', $VendorWorkOrderMasterList->vw_code)->orderBy('item_code')->get();
        // DB::enableQueryLog(); 
        
        $S1= SalesOrderCostingMasterModel::select('sales_order_costing_master.sales_order_no')
        ->whereNotIn('sales_order_costing_master.sales_order_no',function($query){
        $query->select('sales_order_no')->from('vendor_work_order_master');
        });
        
        $S2=VendorWorkOrderModel::select('sales_order_no')->where('sales_order_no',$VendorWorkOrderMasterList->sales_order_no);
        $SalesOrderList = $S1->union($S2)->get();
        $BuyerPurchaseOrderMasterList = BuyerPurchaseOrderMasterModel::find($VendorWorkOrderMasterList->sales_order_no);
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
       //DB::enableQueryLog();
        $StitchingGRNList = DB::select("SELECT stitching_inhouse_size_detail.color_id, color_name, ".$sizes.", 
        sum(size_qty_total) as size_qty_total from stitching_inhouse_size_detail 
        left join color_master on color_master.color_id=stitching_inhouse_size_detail.color_id 
        where sales_order_no='".$VendorWorkOrderMasterList->sales_order_no."' AND stitching_inhouse_size_detail.vw_code='".$VendorWorkOrderMasterList->vw_code."' 
        group by stitching_inhouse_size_detail.color_id");
       //dd(DB::getQueryLog());
        
        return view('TestVendorWorkOrder',compact('StitchingGRNList','JobStatusList',  'VendorWorkOrderDetailList','Ledger2','TrimFabricList','ColorList','ItemList5','SizeDetailList','VendorWorkOrderMasterList','FabricList','SewingTrimsList', 'UnitList','ClassList','ClassList2','ItemList','ItemList2','MainStyleList','SubStyleList','FGList','CostTypeList','SalesOrderList','Ledger','QualityList', 'CPList', 'CurrencyList', 'SeasonList'));
         
    }
    
    public function GetWorkOrderSewingCreateConsumption1(Request $request)
    {
        
        $size_qty_array= $request->input('size_qty_array');
        $size_array= $request->input('size_array');
        $allTotal1= $request->allTotal;
        $allTotal= $request->size_qty_total;
        $sumAllTotal= $request->sumAllTotal;
        $SizeList=explode(',', $size_array);
        $SizeQty=explode(',', $size_qty_array);
        $All_Total = explode(',', $allTotal);
        $All_Total1 = explode(',', $allTotal1);
        $color_ids = $request->color_ids;
        $tbl_len = $request->tbl_len;
        
        //print_r($size_array);exit;
        $no= 1;
        $color_id= $request->input('color_id');
        $size_qty_total= $request->size_qty_total;
        $sales_order_no= $request->input('sales_order_no');
       
        $item_code= $request->item_code;
        $sales_order_no= $request->sales_order_no;
     
        
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
         
        $html = '';
    
        $x=0;$qty=0;
        // print_R($sz_array);exit;
        // foreach($SizeList as $sz)
        //  { 
            
                // $sz_array = explode(',', $SizeList);
               
                $qty=$SizeQty[$x++];
                // if($qty!=0)
                // {
                  
                    //   $query = DB::table('bom_sewing_trims_details')
                    //     ->join("color_master", function ($join) {
                    //         $join->on(DB::raw("FIND_IN_SET(color_master.color_id, bom_sewing_trims_details.color_id)"), ">", DB::raw("0"));
                    //     })
                    //     ->select(
                    //         'sales_order_no',
                    //         'item_code',
                    //         'bom_sewing_trims_details.color_id',
                    //         'consumption',
                    //         'description',
                    //         'wastage',
                    //         'bom_sewing_trims_details.class_id',
                    //         'bom_sewing_trims_details.unit_id',
                    //         'size_array',
                    //         DB::raw("GROUP_CONCAT(DISTINCT color_master.color_name ORDER BY color_master.color_name SEPARATOR ', ') as color_names")
                    //     )
                    //     ->where('sales_order_no', $sales_order_no)
                    //     ->whereRaw("FIND_IN_SET(?, bom_sewing_trims_details.color_id)", [$color_id]) // Ensure the given color_id exists in the column
                    //     ->groupBy(
                    //         'sales_order_no',
                    //         'item_code',
                    //         'bom_sewing_trims_details.color_id',
                    //         'consumption',
                    //         'description',
                    //         'wastage',
                    //         'bom_sewing_trims_details.class_id',
                    //         'bom_sewing_trims_details.unit_id',
                    //         'size_array'
                    //     );
                    
                    // // Fetch results
                    // $results = $query->get();

                    $size_array1 = explode(',', $size_array);  // $size_string = 'S,M,L,XL'
                    $size_qty_array1 = explode(',', $size_qty_array); // $size_qty_string = '10,20,30,40'

                    $size_qty_map = array_combine($size_array1, $size_qty_array1);
                    
                    $query = DB::table('bom_sewing_trims_details')
                        ->select(
                            'sales_order_no',
                            'item_code',
                            DB::raw("$color_id as color_id"),
                            'consumption',
                            'description',
                            'wastage',
                            'bom_sewing_trims_details.class_id',
                            'bom_sewing_trims_details.unit_id',
                            'size_array',
                            'color_master.color_name as color_names'
                        )
                        ->join('color_master', function ($join) use ($color_id) {
                            $join->on(DB::raw('FIND_IN_SET(color_master.color_id, bom_sewing_trims_details.color_id)'), '>', DB::raw('0'))
                                 ->where('color_master.color_id', $color_id);
                        })
                        ->where('sales_order_no', $sales_order_no)
                        ->whereRaw('FIND_IN_SET(?, bom_sewing_trims_details.color_id)', [$color_id])
                        ->where(function ($query) use ($size_array1) {
                            foreach ($size_array1 as $index => $size_id) {
                                if ($index === 0) {
                                    $query->whereRaw("FIND_IN_SET(?, size_array)", [$size_id]);
                                } else {
                                    $query->orWhereRaw("FIND_IN_SET(?, size_array)", [$size_id]);
                                }
                            }
                        })
                        ->groupBy('sales_order_no', 'item_code', 'consumption', 'size_array', 'color_master.color_name');
                    
                    $codefetch = $query->get();

                   
                    $szc = 0;
                    $temp_color = '';
                    foreach($codefetch as $key=>$rowsew)
                    {   
                        
                        $sizes =""; 
                         
                        $SizeListFromBOM=DB::select("select size_array from bom_sewing_trims_details where sales_order_no='".$rowsew->sales_order_no."' and item_code='".$rowsew->item_code."' limit 0,1");
                                         
                               
                        // $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                        
                        if(isset($SizeListFromBOM[0]->size_array))
                        {
                            $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
                            $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                            foreach($SizeDetailList as $sz)
                            {
                                 $sizes=$sizes.$sz->size_name.', '; 
                            }
                        } 
                        $array2 = explode(',', $rowsew->size_array);            
                        $commonElements = array_intersect($SizeList, $array2);
                         
                        $matchedQuantities =  0;
                  
                    // foreach ($array2 as $element) 
                    // { 
                    //     $index = array_search($element, $SizeList); 
                    //     if($color_id  == $rowsew->color_id)
                    //     {
                    //         $matchedQuantities += $SizeQty[$index];
                    //     }
                    //     else
                    //     {
                    //          $matchedQuantities = $SizeList[$index];
                    //     }
                    // } 
                          
                       
                        // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                        // $bom_qty=round(($total_consumption*$qty),2);
                               
                        $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=', $rowsew->class_id)->get();
                        $ItemList = ItemModel::where('delflag','=', '0')->where('item_code','=', $rowsew->item_code)->get(); 
                        $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=', $rowsew->unit_id)->get();
                        
                        $total_consumption= ($rowsew->consumption);
                        $temp_s1 = rtrim($sizes, ', ');
                        $sizesArray = explode(', ', $temp_s1);
                        $count = count($sizesArray);
                        $index = array_search($size_ids[0], $SizeList);
                      //echo '<pre>';print_R($array2);exit;
                    
                      if($count == 1)
                      { 
                          $ind_qty = $SizeQty[$index]; 
                      }
                      else
                      {
                          if ($SizeList == $array2) 
                          {
                                $selected_color_ids = explode(',', $color_ids);
                                $existing_color_ids = DB::table('bom_sewing_trims_details')
                                    ->where('item_code', $rowsew->item_code)
                                    ->where('sales_order_no', $rowsew->sales_order_no)
                                    ->pluck('color_id')
                                    ->toArray();
                             
                    //             // Convert database color_id values into an array
                                $existing_color_ids_array = [];
                                foreach ($existing_color_ids as $ids) {
                                    $existing_color_ids_array = array_merge($existing_color_ids_array, explode(',', $ids));
                                }
                                
                                // Check if both 2001 and 2003 exist
                                $allExist = empty(array_diff($selected_color_ids, $existing_color_ids_array));
                                $allExist1 = $allExist ?? 0;
                                if ($allExist == 1) 
                                {
                                    // $totalSum = 0;
                                    
                                    // if (is_array($All_Total1)) {
                                    //     foreach ($All_Total1 as $pair) {
                                    //         if (strpos($pair, '=>') !== false) {
                                    //             list($colorId, $qty) = explode('=>', $pair);
                                    
                                    //             $colorId = trim($colorId);
                                    //             $qty = trim($qty);
                                    
                                    //             // Match current colorId with existing_color_ids_array
                                    //             if (in_array($colorId, $existing_color_ids_array)) 
                                    //             {
                                    //                 $totalSum += (float)$qty;
                                    //             }
                                    //         }
                                    //     }
                                    // }
                                    
                                    $ind_qty = $sumAllTotal;

                                }
                                else
                                {
                                 if (count($selected_color_ids) > 0)
                                 { 
                                    $totalSum = 0;
                                    
                                    if (is_array($All_Total1)) {
                                        foreach ($All_Total1 as $pair) { 
                                            if (strpos($pair, '=>') !== false) {
                                                list($colorId, $qty) = explode('=>', $pair);
                                    
                                                $colorId = trim($colorId);
                                                $qty = trim($qty);
                                    
                                                // Match current colorId with existing_color_ids_array
                                                if (in_array($colorId, $existing_color_ids_array)) 
                                                {
                                                    $totalSum += (float)$qty;
                                                }
                                            }
                                        }
                                    }
                                    
                                    $ind_qty = $totalSum;
                                 }
                                 else
                                 {
                                     $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                                 }
                               
                                 
                                 //$ind_qty = $sumAllTotal ? $sumAllTotal : $SizeQty[$index]; 
                                   
                                }
                                
                          }
                          else
                          {
                              //echo $All_Total[$index];exit;
                              $ind_qty =  $sumAllTotal; 
                          } 
                      }
                      
                        // $selected_color_ids = explode(',', $color_ids);
                        // $existing_color_ids = DB::table('bom_sewing_trims_details')
                        //     ->where('item_code', $rowsew->item_code)
                        //     ->where('sales_order_no', $rowsew->sales_order_no)
                        //     ->pluck('color_id')
                        //     ->toArray();
                        
                        // // Convert database color_id values into an array
                        
                         
                        // $existing_color_ids_array = [];
                        // foreach ($existing_color_ids as $ids) {
                        //     $existing_color_ids_array = array_merge($existing_color_ids_array, explode(',', $ids));
                             
                        // }
                        // //print_r($existing_color_ids_array);exit;
             
                        // $allExist = array_diff($selected_color_ids, $existing_color_ids_array);
                        // //print_R($allExist);exit;
                        // $allExist1 = count($allExist);
                         
                        // if ($allExist1 == 1) 
                        // {  
                        //     $ind_qty = $sumAllTotal; 
                        // }
                        // else
                        // {    
                        //      $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                        // }
                        
                    
                       
                       // $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                       
                     
                        if(Session::get('user_type') == 1)
                        {
                            $mx = 500;    
                        }
                        else
                        {
                            $mx = 5;    
                        }
                        if($tbl_len == 1)
                        {
                            // if(strpos($rowsew->size_array, ',') === false) 
                            // {
                            //     $bom_qty = isset($SizeQty[$szc]) ? $SizeQty[$szc] : 0;
                               
                            //     $szc++;
                               
                            //     // if($temp_color != $rowsew->class_id)
                            //     // {
                            //     //     $szc = 0;
                            //     // }
                            // } 
                            // else
                            // {
                            //   $szc = 0;
                            //   $size_name =  rtrim($sizes, ', ');
                            //   $bom_qty = $ind_qty * $rowsew->consumption; 
                            // } 
                            
                        
                            // $sizedata = DB::SELECT("SELECT size_name FROM `size_detail` WHERE size_id IN (".$rowsew->size_array.")");
                            
                            // $size_name = isset($sizedata[0]->size_name) ? $sizedata[0]->size_name : "";    
                            $row_size_ids = explode(',', $rowsew->size_array);
                            $total_size_qty = 0;
                        
                            foreach ($row_size_ids as $size_id) {
                                if (isset($size_qty_map[$size_id])) {
                                    $total_size_qty += $size_qty_map[$size_id];
                                }
                            }
                        
                            $total_size_qty1 = is_numeric($total_size_qty) ? $total_size_qty : 0;
                            $consumption = is_numeric($rowsew->consumption) ? $rowsew->consumption : 0;
                            $bom_qty =  $consumption * $total_size_qty1;
                        }
                        else
                        {
                            $ind_qty = is_numeric($ind_qty) ? $ind_qty : 0;
                            $consumption = is_numeric($rowsew->consumption) ? $rowsew->consumption : 0;
                            $bom_qty = $ind_qty * $consumption;
                        }
                         
                        
                        $size_name =  rtrim($sizes, ', ');
    
                        $html .='<tr class="thisRow">';
                        $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                        $html .='<td><input type="text"  value="'.$rowsew->item_code.'"  style="width:50px;" readOnly/></td>';
                        $html.='<td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                        <option value="">--Item List--</option>';
                        foreach($ItemList as  $rowitem)
                        {
                            $html.='<option value="'.$rowitem->item_code.'"';
                            $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowitem->item_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="text" name="color_ids[]" value="'.$rowsew->color_names.'"  style="width:200px;" disabled/></td>
                        <td><input type="text"  name="sizes_ids[]" value="'.$size_name.'"  style="width:200px;" disabled/></td>
                        <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:150px; height:30px;" required disabled>
                        <option value="">--Classification--</option>';
                        foreach($ClassList as  $rowclass)
                        {
                            $html.='<option value="'.$rowclass->class_id.'"';
                            $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowclass->class_name.'</option>';
                        }
                        $html.='</select></td>  
                        <td><input type="number" step="any"  readOnly  name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                        <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                        <option value="">--Unit--</option>';
                        foreach($UnitList as  $rowunit)
                        {
                           $html.='<option value="'.$rowunit->unit_id.'"';
                           $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                           $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE2"  name="wastagess[]" value="0" id="wastage'.$no.'" onchange="calculateBomWithWastage(this);" style="width:80px; height:30px;" /></td> 
                        <td><input type="text"  name="bom_qtyss[]" data-color="'.$rowsew->color_names.'" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="bom_qtyss1[]" value="'.$ind_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        </td> 
                        ';
                        $html .='</tr>';
                        $no++;
                        
                        $temp_color = $rowsew->class_id;
                    } //main foreach
            // }  // if loop for size qty 0
             
        //  }  // Size Array Foreach
       return response()->json(['html' => $html]);
     
    }  
    
       
    public function GetWorkOrderSewingCreateConsumption(Request $request)
    {
        
        $size_qty_array= $request->input('size_qty_array');
        $size_array= $request->input('size_array');
        $allTotal1= $request->allTotal;
        $allTotal= $request->size_qty_total;
        $sumAllTotal= $request->sumAllTotal;
        $SizeList=explode(',', $size_array);
        $SizeQty=explode(',', $size_qty_array);
        $All_Total = explode(',', $allTotal);
        $All_Total1 = explode(',', $allTotal1);
        $color_ids = $request->color_ids;
        $tbl_len = $request->tbl_len;
        
        //print_r($size_array);exit;
        $no= 1;
        $color_id= $request->input('color_id');
        $size_qty_total= $request->size_qty_total;
        $sales_order_no= $request->input('sales_order_no');
       
        $item_code= $request->item_code;
        $sales_order_no= $request->sales_order_no;
     
        
        $ColorList = DB::table('buyer_purchase_order_detail')->select('buyer_purchase_order_detail.color_id', 'color_name')
        ->join('color_master', 'color_master.color_id', '=', 'buyer_purchase_order_detail.color_id', 'left outer')
        ->where('tr_code','=',$sales_order_no)->DISTINCT()->get();
         
        $html = '';
    
        $x=0;$qty=0;
        // print_R($sz_array);exit;
        // foreach($SizeList as $sz)
        //  { 
            
                // $sz_array = explode(',', $SizeList);
               
                $qty=$SizeQty[$x++];
                // if($qty!=0)
                // {
                  
                    //   $query = DB::table('bom_sewing_trims_details')
                    //     ->join("color_master", function ($join) {
                    //         $join->on(DB::raw("FIND_IN_SET(color_master.color_id, bom_sewing_trims_details.color_id)"), ">", DB::raw("0"));
                    //     })
                    //     ->select(
                    //         'sales_order_no',
                    //         'item_code',
                    //         'bom_sewing_trims_details.color_id',
                    //         'consumption',
                    //         'description',
                    //         'wastage',
                    //         'bom_sewing_trims_details.class_id',
                    //         'bom_sewing_trims_details.unit_id',
                    //         'size_array',
                    //         DB::raw("GROUP_CONCAT(DISTINCT color_master.color_name ORDER BY color_master.color_name SEPARATOR ', ') as color_names")
                    //     )
                    //     ->where('sales_order_no', $sales_order_no)
                    //     ->whereRaw("FIND_IN_SET(?, bom_sewing_trims_details.color_id)", [$color_id]) // Ensure the given color_id exists in the column
                    //     ->groupBy(
                    //         'sales_order_no',
                    //         'item_code',
                    //         'bom_sewing_trims_details.color_id',
                    //         'consumption',
                    //         'description',
                    //         'wastage',
                    //         'bom_sewing_trims_details.class_id',
                    //         'bom_sewing_trims_details.unit_id',
                    //         'size_array'
                    //     );
                    
                    // // Fetch results
                    // $results = $query->get();

                    $size_array1 = explode(',', $size_array);  // $size_string = 'S,M,L,XL'
                    $size_qty_array1 = explode(',', $size_qty_array); // $size_qty_string = '10,20,30,40'

                    $size_qty_map = array_combine($size_array1, $size_qty_array1);
                    
                    $query = DB::table('bom_sewing_trims_details')
                        ->select(
                            'sales_order_no',
                            'item_code',
                            DB::raw("$color_id as color_id"),
                            'consumption',
                            'description',
                            'wastage',
                            'bom_sewing_trims_details.class_id',
                            'bom_sewing_trims_details.unit_id',
                            'size_array',
                            'color_master.color_name as color_names'
                        )
                        ->join('color_master', function ($join) use ($color_id) {
                            $join->on(DB::raw('FIND_IN_SET(color_master.color_id, bom_sewing_trims_details.color_id)'), '>', DB::raw('0'))
                                 ->where('color_master.color_id', $color_id);
                        })
                        ->where('sales_order_no', $sales_order_no)
                        ->whereRaw('FIND_IN_SET(?, bom_sewing_trims_details.color_id)', [$color_id])
                        ->where(function ($query) use ($size_array1) {
                            foreach ($size_array1 as $index => $size_id) {
                                if ($index === 0) {
                                    $query->whereRaw("FIND_IN_SET(?, size_array)", [$size_id]);
                                } else {
                                    $query->orWhereRaw("FIND_IN_SET(?, size_array)", [$size_id]);
                                }
                            }
                        })
                        ->groupBy('sales_order_no', 'item_code', 'consumption', 'size_array', 'color_master.color_name');
                    
                    $codefetch = $query->get();

                   
                    $szc = 0;
                    $temp_color = '';
                    foreach($codefetch as $key=>$rowsew)
                    {   
                        
                        $sizes =""; 
                         
                        $SizeListFromBOM=DB::select("select size_array from bom_sewing_trims_details where sales_order_no='".$rowsew->sales_order_no."' and item_code='".$rowsew->item_code."' limit 0,1");
                                         
                               
                        // $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                        
                        if(isset($SizeListFromBOM[0]->size_array))
                        {
                            $size_ids = explode(',', $SizeListFromBOM[0]->size_array); 
                            $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                            foreach($SizeDetailList as $sz)
                            {
                                 $sizes=$sizes.$sz->size_name.', '; 
                            }
                        } 
                        $array2 = explode(',', $rowsew->size_array);            
                        $commonElements = array_intersect($SizeList, $array2);
                         
                        $matchedQuantities =  0;
                  
                    // foreach ($array2 as $element) 
                    // { 
                    //     $index = array_search($element, $SizeList); 
                    //     if($color_id  == $rowsew->color_id)
                    //     {
                    //         $matchedQuantities += $SizeQty[$index];
                    //     }
                    //     else
                    //     {
                    //          $matchedQuantities = $SizeList[$index];
                    //     }
                    // } 
                          
                       
                        // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                        // $bom_qty=round(($total_consumption*$qty),2);
                               
                        $ClassList = ClassificationModel::where('delflag','=', '0')->where('class_id','=', $rowsew->class_id)->get();
                        $ItemList = ItemModel::where('delflag','=', '0')->where('item_code','=', $rowsew->item_code)->get(); 
                        $UnitList = UnitModel::where('delflag','=', '0')->where('unit_id','=', $rowsew->unit_id)->get();
                        
                        $total_consumption= ($rowsew->consumption);
                        $temp_s1 = rtrim($sizes, ', ');
                        $sizesArray = explode(', ', $temp_s1);
                        $count = count($sizesArray);
                        $index = array_search($size_ids[0], $SizeList);
                      //echo '<pre>';print_R($array2);exit;
                    
                      if($count == 1)
                      { 
                          $ind_qty = $SizeQty[$index]; 
                      }
                      else
                      {
                          if ($SizeList == $array2) 
                          {
                                $selected_color_ids = explode(',', $color_ids);
                                $existing_color_ids = DB::table('bom_sewing_trims_details')
                                    ->where('item_code', $rowsew->item_code)
                                    ->where('sales_order_no', $rowsew->sales_order_no)
                                    ->pluck('color_id')
                                    ->toArray();
                                
                    //             // Convert database color_id values into an array
                                $existing_color_ids_array = [];
                                foreach ($existing_color_ids as $ids) {
                                    $existing_color_ids_array = array_merge($existing_color_ids_array, explode(',', $ids));
                                }
                                
                                // Check if both 2001 and 2003 exist
                                $allExist = empty(array_diff($selected_color_ids, $existing_color_ids_array));
                                $allExist1 = $allExist ?? 0;
                                if ($allExist == 1) 
                                {
                                  $ind_qty = $sumAllTotal;
                                }
                                else
                                {
                                 if (count($selected_color_ids) > 0)
                                 {
                                    $totalSum = 0;
                                    
                                    if (is_array($All_Total1)) {
                                        foreach ($All_Total1 as $pair) { 
                                            if (strpos($pair, '=>') !== false) {
                                                list($colorId, $qty) = explode('=>', $pair);
                                    
                                                $colorId = trim($colorId);
                                                $qty = trim($qty);
                                    
                                                // Match current colorId with existing_color_ids_array
                                                if (in_array($colorId, $existing_color_ids_array)) 
                                                {
                                                    $totalSum += (float)$qty;
                                                }
                                            }
                                        }
                                    }
                                    
                                    $ind_qty = $totalSum;
                                 }
                                 else
                                 {
                                    $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $size_qty_total; 
                                    // $totalSum = 0;
                                    
                                    // if (is_array($All_Total1)) {
                                    //     foreach ($All_Total1 as $pair) { 
                                    //         if (strpos($pair, '=>') !== false) {
                                    //             list($colorId, $qty) = explode('=>', $pair);
                                    
                                    //             $colorId = trim($colorId);
                                    //             $qty = trim($SizeQty[$index]);
                                    
                                    //             // Match current colorId with existing_color_ids_array
                                    //             if (in_array($colorId, $existing_color_ids_array)) 
                                    //             {
                                    //                 $totalSum += (float)$qty;
                                    //             }
                                    //         }
                                    //     }
                                    // }
                                    
                                    // $ind_qty = $totalSum;
                                 }
                                 
                                 //$ind_qty = $sumAllTotal ? $sumAllTotal : $SizeQty[$index]; 
                                   
                                }
                                
                          }
                          else
                          {
                              //echo $All_Total[$index];exit;
                              $ind_qty =  $sumAllTotal; 
                          } 
                      }
                      
                        // $selected_color_ids = explode(',', $color_ids);
                        // $existing_color_ids = DB::table('bom_sewing_trims_details')
                        //     ->where('item_code', $rowsew->item_code)
                        //     ->where('sales_order_no', $rowsew->sales_order_no)
                        //     ->pluck('color_id')
                        //     ->toArray();
                        
                        // // Convert database color_id values into an array
                        
                         
                        // $existing_color_ids_array = [];
                        // foreach ($existing_color_ids as $ids) {
                        //     $existing_color_ids_array = array_merge($existing_color_ids_array, explode(',', $ids));
                             
                        // }
                        // //print_r($existing_color_ids_array);exit;
             
                        // $allExist = array_diff($selected_color_ids, $existing_color_ids_array);
                        // //print_R($allExist);exit;
                        // $allExist1 = count($allExist);
                         
                        // if ($allExist1 == 1) 
                        // {  
                        //     $ind_qty = $sumAllTotal; 
                        // }
                        // else
                        // {    
                        //      $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                        // }
                        
                    
                       
                       // $ind_qty = $SizeQty[$index] ? $SizeQty[$index] : $sumAllTotal; 
                       
                   
                        //$ind_qty = $totalSum;
                                    
                        if(Session::get('user_type') == 1)
                        {
                            $mx = 500;    
                        }
                        else
                        {
                            $mx = 5;    
                        }
                        if($tbl_len == 1)
                        {
                            // if(strpos($rowsew->size_array, ',') === false) 
                            // {
                            //     $bom_qty = isset($SizeQty[$szc]) ? $SizeQty[$szc] : 0;
                               
                            //     $szc++;
                               
                            //     // if($temp_color != $rowsew->class_id)
                            //     // {
                            //     //     $szc = 0;
                            //     // }
                            // } 
                            // else
                            // {
                            //   $szc = 0;
                            //   $size_name =  rtrim($sizes, ', ');
                            //   $bom_qty = $ind_qty * $rowsew->consumption; 
                            // } 
                            
                        
                            // $sizedata = DB::SELECT("SELECT size_name FROM `size_detail` WHERE size_id IN (".$rowsew->size_array.")");
                            
                            // $size_name = isset($sizedata[0]->size_name) ? $sizedata[0]->size_name : "";    
                            $row_size_ids = explode(',', $rowsew->size_array);
                            $total_size_qty = 0;
                        
                            foreach ($row_size_ids as $size_id) {
                                if (isset($size_qty_map[$size_id])) {
                                    $total_size_qty += $size_qty_map[$size_id];
                                }
                            }
                        
                            $total_size_qty1 = is_numeric($total_size_qty) ? $total_size_qty : 0;
                            $consumption = is_numeric($rowsew->consumption) ? $rowsew->consumption : 0;
                            $bom_qty =  $consumption * $total_size_qty1;
                        }
                        else
                        {
                            $ind_qty = is_numeric($ind_qty) ? $ind_qty : 0;
                            $consumption = is_numeric($rowsew->consumption) ? $rowsew->consumption : 0;
                            $bom_qty = $ind_qty * $consumption;
                        }
                        
                        $size_name =  rtrim($sizes, ', ');
    
                        $html .='<tr class="thisRow">';
                        $html .='<td><input type="text" name="idss[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                        $html .='<td><input type="text"  value="'.$rowsew->item_code.'"  style="width:50px;" readOnly/></td>';
                        $html.='<td> <select name="item_codess[]"  id="item_code'.$no.'" style="width:250px; height:30px;" required disabled>
                        <option value="">--Item List--</option>';
                        foreach($ItemList as  $rowitem)
                        {
                            $html.='<option value="'.$rowitem->item_code.'"';
                            $rowitem->item_code == $rowsew->item_code ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowitem->item_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="text" name="color_ids[]" value="'.$rowsew->color_names.'"  style="width:200px;" disabled/></td>
                        <td><input type="text"  name="sizes_ids[]" value="'.$size_name.'"  style="width:200px;" disabled/></td>
                        <td> <select name="class_idss[]"  id="class_id'.$no.'" style="width:150px; height:30px;" required disabled>
                        <option value="">--Classification--</option>';
                        foreach($ClassList as  $rowclass)
                        {
                            $html.='<option value="'.$rowclass->class_id.'"';
                            $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                            $html.='>'.$rowclass->class_name.'</option>';
                        }
                        $html.='</select></td>  
                        <td><input type="number" step="any"  readOnly  name="consumptionss[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                        <td> <select name="unit_idss[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required>
                        <option value="">--Unit--</option>';
                        foreach($UnitList as  $rowunit)
                        {
                           $html.='<option value="'.$rowunit->unit_id.'"';
                           $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                           $html.='>'.$rowunit->unit_name.'</option>';
                        }
                        $html.='</select></td>
                        <td><input type="number" step="any" max="'.$mx.'" min="0" class="WASTAGE2"  name="wastagess[]" value="0" id="wastage'.$no.'" onchange="calculateBomWithWastage(this);" style="width:80px; height:30px;" /></td> 
                        <td><input type="text"  name="bom_qtyss[]" data-color="'.$rowsew->color_names.'" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="bom_qtyss1[]" value="'.$ind_qty.'" id="bom_qty1'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="final_consss[]" value="'.$total_consumption.'" id="size_qt'.$no.'" style="width:80px; height:30px;"  readOnly />
                        <input type="hidden"  name="size_qtyss[]" value="'.$size_qty_total.'" id="size_qty'.$no.'" style="width:80px; height:30px;"  readOnly />
                        </td> 
                        ';
                        $html .='</tr>';
                        $no++;
                        
                        $temp_color = $rowsew->class_id;
                    } //main foreach
            // }  // if loop for size qty 0
             
        //  }  // Size Array Foreach
       return response()->json(['html' => $html]);
     
    }  
    
    public function GetSewingConsumption(Request $request)
    {
             
        $size_qty_array= $request->input('size_qty_array');
        $size_array= $request->input('size_array');
        $SizeList=explode(',', $size_array);
        $SizeQty=explode(',', $size_qty_array); 
        
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
               $codefetch = DB::table('bom_sewing_trims_details')->select("bom_sewing_trims_details.item_code","consumption","description","wastage","bom_sewing_trims_details.class_id","bom_sewing_trims_details.unit_id","cat_id")
               ->join('item_master', 'item_master.item_code', '=', 'bom_sewing_trims_details.item_code', 'left outer')
              ->whereRaw('FIND_IN_SET('.$color_id.',color_id)')->whereRaw('FIND_IN_SET('.$sz.',size_array)')->where('sales_order_no','=',$sales_order_no)
              ->orderBy('item_master.cat_id', 'ASC')
              ->orderBy('item_master.class_id', 'ASC')
              ->orderBy('bom_sewing_trims_details.item_code', 'ASC')->get(); 
          
               
                foreach($codefetch as $rowsew)
                {   
                    
                    $ColorListpacking= ColorModel::where('color_id', $color_id)->where('delflag','=', '0')->get('color_name');
                   
                    $size_ids = explode(',', isset($size_array) ? $size_array : ""); 
                    $SizeDetailList = SizeDetailModel::whereIn('size_id',$size_ids)->get('size_name');
                    $sizes='';
                    foreach($SizeDetailList as $sz)
                    {
                        $sizes=$sizes.$sz->size_name.', ';
                    }
                                                        
                    // $total_consumption= ($rowsew->consumption)+(($rowsew->consumption)*($rowsew->wastage/100));
                    // $bom_qty=round(($total_consumption*$qty),2);
                    $total_consumption= ($rowsew->consumption);
                    $bom_qty=round(($rowsew->consumption*$qty),2);
                
                    $html .='<tr class="thisRow">';
                    $html .='<td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
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
                    <td>'.$ColorListpacking[0]->color_name.'</td>
                    <td>'.rtrim($sizes,',').'</td>
                    <td> <select name="class_ids[]"  id="class_id'.$no.'" style="width:250px; height:30px;" required disabled>
                    <option value="">--Classification--</option>';
                    foreach($ClassList as  $rowclass)
                    {
                        $html.='<option value="'.$rowclass->class_id.'"';
                        $rowclass->class_id == $rowsew->class_id ? $html.='selected="selected"' : ''; 
                        $html.='>'.$rowclass->class_name.'</option>';
                    }
                    $html.='</select></td> 
                    <td><input type="text"    name="descriptions[]" value="'.$rowsew->description.'" readOnly id="description" style="width:200px; height:30px;"   /></td> 
                    <td><input type="number" step="any" readOnly   name="consumptions[]" value="'.$rowsew->consumption.'" id="consumption" style="width:80px; height:30px;" required /></td> 
                    <td> <select name="unit_ids[]"  id="unit_id'.$no.'" style="width:100px; height:30px;" required disabled>
                    <option value="">--Unit--</option>';
                    foreach($UnitList as  $rowunit)
                    {
                       $html.='<option value="'.$rowunit->unit_id.'"';
                       $rowunit->unit_id == $rowsew->unit_id ? $html.='selected="selected"' : ''; 
                       $html.='>'.$rowunit->unit_name.'</option>';
                    }
                    $html.='</select></td>
                    <td><input type="number" step="any" max="'.$rowsew->wastage.'" min="0"   name="wastages[]" value="0"  class="WASTAGE2" id="wastager'.$no.'" style="width:80px; height:30px;" required /></td> 
                    <td><input type="text"  name="bom_qtys[]" value="'.$bom_qty.'" id="bom_qty'.$no.'" style="width:80px; height:30px;" required readOnly />
                     <input type="hidden"  name="bom_qtys1[]" value="'.$qty.'" id="bom_qtys1'.$no.'" style="width:80px; height:30px;" required readOnly />
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
     
      
   public function VWOrder_GetOrderQty(Request $request)
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
                   <th>Action   <input type="button" class="size_btn btn-primary" id="MBtn" is_click="0" value="Calculate All" onclick="MainBtn(); this.disabled=true;"></th>
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
        
        <select name="color_id[]" class="select2-select"  id="color_id0" style="width:250px; height:30px;" disabled>
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
      $CompareList = DB::select("SELECT vendor_work_order_size_detail.color_id, color_name, ".$sizex.", 
      sum(size_qty_total) as size_qty_total from vendor_work_order_size_detail inner join color_master on 
      color_master.color_id=vendor_work_order_size_detail.color_id where 
      vendor_work_order_size_detail.sales_order_no='".$request->tr_code."' and
      vendor_work_order_size_detail.color_id='".$row->color_id."'");
 
 
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
          if(isset($row->s1)) {$total_qty=$total_qty+round($row->s1); $html.='<td>'.$s1.' <input style="width:80px; float:left;" sz_group max="'.$s1.'" min="0" name="s1[]" class="size_id" type="number" id="s1" value="0" required /></td>';}
          if(isset($row->s2)) {$total_qty=$total_qty+round($row->s2); $html.='<td>'.$s2.' <input style="width:80px; float:left;" sz_group max="'.$s2.'" min="0" name="s2[]" type="number" class="size_id" id="s2" value="0" required /></td>';}
          if(isset($row->s3)) {$total_qty=$total_qty+round($row->s3); $html.='<td>'.$s3.' <input style="width:80px; float:left;" sz_group max="'.$s3.'" min="0" name="s3[]" type="number" class="size_id" id="s3" value="0" required /></td>';}
          if(isset($row->s4)) {$total_qty=$total_qty+round($row->s4); $html.='<td>'.$s4.' <input style="width:80px; float:left;" sz_group max="'.$s4.'" min="0" name="s4[]" type="number" class="size_id" id="s4" value="0" required /></td>';}
          if(isset($row->s5)) {$total_qty=$total_qty+round($row->s5); $html.='<td>'.$s5.' <input style="width:80px; float:left;" sz_group max="'.$s5.'" min="0" name="s5[]" type="number" class="size_id" id="s5" value="0" required /></td>';}
          if(isset($row->s6)) {$total_qty=$total_qty+round($row->s6); $html.='<td>'.$s6.' <input style="width:80px; float:left;" sz_group max="'.$s6.'" min="0" name="s6[]" type="number" class="size_id" id="s6" value="0" required /></td>';}
          if(isset($row->s7)) {$total_qty=$total_qty+round($row->s7); $html.='<td>'.$s7.' <input style="width:80px; float:left;" sz_group max="'.$s7.'" min="0" name="s7[]" type="number" class="size_id" id="s7" value="0" required /></td>';}
          if(isset($row->s8)) {$total_qty=$total_qty+round($row->s8); $html.='<td>'.$s8.' <input style="width:80px; float:left;" sz_group max="'.$s8.'" min="0" name="s8[]" type="number" class="size_id" id="s8" value="0" required /></td>';}
          if(isset($row->s9)) {$total_qty=$total_qty+round($row->s9); $html.='<td>'.$s9.' <input style="width:80px; float:left;" sz_group max="'.$s9.'" min="0" name="s9[]" type="number" class="size_id" id="s9" value="0" required /></td>';}
          if(isset($row->s10)) {$total_qty=$total_qty+round($row->s10); $html.='<td>'.$s10.' <input style="width:80px; float:left;" sz_group max="'.$s10.'" min="0" name="s10[]" type="number" class="size_id" id="s10" value="0" required /></td>';}
          if(isset($row->s11)) {$total_qty=$total_qty+round($row->s11); $html.='<td>'.$s11.' <input style="width:80px; float:left;" sz_group max="'.$s11.'" min="0" name="s11[]" type="number" class="size_id" id="s11" value="0" required /></td>';}
          if(isset($row->s12)) {$total_qty=$total_qty+round($row->s12); $html.='<td>'.$s12.' <input style="width:80px; float:left;" sz_group max="'.$s12.'" min="0" name="s12[]" type="number" class="size_id" id="s12" value="0" required /></td>';}
          if(isset($row->s13)) {$total_qty=$total_qty+round($row->s13); $html.='<td>'.$s13.' <input style="width:80px; float:left;" sz_group max="'.$s13.'" min="0" name="s13[]" type="number" class="size_id" id="s13" value="0" required /></td>';}
          if(isset($row->s14)) {$total_qty=$total_qty+round($row->s14); $html.='<td>'.$s14.' <input style="width:80px; float:left;" sz_group max="'.$s14.'" min="0" name="s14[]" type="number" class="size_id" id="s14" value="0" required /></td>';}
          if(isset($row->s15)) {$total_qty=$total_qty+round($row->s15); $html.='<td>'.$s15.' <input style="width:80px; float:left;" sz_group max="'.$s15.'" min="0" name="s15[]" type="number" class="size_id" id="s15" value="0" required /></td>';}
          if(isset($row->s16)) {$total_qty=$total_qty+round($row->s16); $html.='<td>'.$s16.' <input style="width:80px; float:left;" sz_group max="'.$s16.'" min="0" name="s16[]" type="number" class="size_id" id="s16" value="0" required /></td>';}
          if(isset($row->s17)) {$total_qty=$total_qty+round($row->s17); $html.='<td>'.$s17.' <input style="width:80px; float:left;" sz_group max="'.$s17.'" min="0" name="s17[]" type="number" class="size_id" id="s17" value="0" required /></td>';}
          if(isset($row->s18)) {$total_qty=$total_qty+round($row->s18); $html.='<td>'.$s18.' <input style="width:80px; float:left;" sz_group max="'.$s18.'" min="0" name="s18[]" type="number" class="size_id" id="s18" value="0" required /></td>';}
          if(isset($row->s19)) {$total_qty=$total_qty+round($row->s19); $html.='<td>'.$s19.' <input style="width:80px; float:left;" sz_group max="'.$s19.'" min="0" name="s19[]" type="number" class="size_id" id="s19" value="0" required /></td>';}
          if(isset($row->s20)) {$total_qty=$total_qty+round($row->s20); $html.='<td>'.$s20.' <input style="width:80px; float:left;" sz_group max="'.$s20.'" min="0" name="s20[]" type="number" class="size_id" id="s20" value="0" required /></td>';}
       
              
             
         
          $html.='<td>'.($total_qty-$List->size_qty_total).' 
          
          <input type="number" name="size_qty_total[]" class="size_qty_total" value="" id="size_qty_total" style="width:80px; height:30px; float:left;"  readOnly required />
        <input type="hidden" name="size_qty_array[]"  value="" id="size_qty_array" style="width:80px; float:left;"  />
        <input type="hidden" name="size_array[]"  value="'.$BuyerPurchaseOrderDetailList->size_array.'" id="size_array" style="width:80px;  float:left;"  />
          
          </td>';
            $html.='<td>  <input type="button" name="size_btn" class="size_btn btn-primary" id="size_btn" value="Calculate" disabled></td>';
          
          
          $html.='</tr>';

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
  
    
}
