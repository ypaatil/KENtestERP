<?php

namespace App\Http\Controllers;
use App\Models\FabricOutwardModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\ItemModel;
use App\Models\PartModel;
use App\Models\FabricTransactionModel;
use App\Models\FabricTrimPartModel;
use App\Models\FabricOutwardDetailModel;
use App\Models\CounterNumberModel;
use Illuminate\Support\Facades\DB;
use App\Models\VendorPurchaseOrderModel;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;
use App\Models\FinishedGoodModel;
use App\Models\StockAssociationForFabricModel;
use Session;
use DataTables;
date_default_timezone_set("Asia/Kolkata");
ini_set('memory_limit', '1G');
use App\Services\FabricOutwardDetailActivityLog;
use App\Services\FabricOutwardMasterActivityLog;

use Log;

class FabricOutwardController extends Controller
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
            ->where('form_id', '52')
            ->first();
    
        $query = DB::table('fabric_outward_master')
            ->select(
                'fabric_outward_master.*',
                'usermaster.username',
                'ledger_master.Ac_name',
                DB::raw('(SELECT sales_order_no FROM vendor_purchase_order_master WHERE vendor_purchase_order_master.vpo_code = fabric_outward_master.vpo_code) AS sales_order_no'),
                DB::raw('COALESCE(fod.total_amount, 0) as total_amount'),
                DB::raw('COALESCE(cpg.total_count, 0) + COALESCE(tm.total_count, 0) as all_count')
            )
            ->join('usermaster', 'usermaster.userId', '=', 'fabric_outward_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_outward_master.vendorId')
            ->leftJoin(DB::raw('(
                SELECT fout_code, SUM(meter * item_rate) as total_amount 
                FROM fabric_outward_details 
                GROUP BY fout_code
            ) as fod'), 'fod.fout_code', '=', 'fabric_outward_master.fout_code')
            ->leftJoin(DB::raw('(
                SELECT vpo_code, COUNT(*) as total_count 
                FROM cut_panel_grn_master 
                GROUP BY vpo_code
            ) as cpg'), 'cpg.vpo_code', '=', 'fabric_outward_master.vpo_code')
            ->leftJoin(DB::raw('(
                SELECT vpo_code, COUNT(*) as total_count 
                FROM task_master 
                GROUP BY vpo_code
            ) as tm'), 'tm.vpo_code', '=', 'fabric_outward_master.vpo_code')
            ->where('fabric_outward_master.delflag', '=', '0');
    
        // Apply 3 months filter conditionally
        if ($request->page != 1) {
            $query->where('fabric_outward_master.fout_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'));
        }
    
        $FabricOutwardList = $query->get();
    
        if ($request->ajax()) {
            return Datatables::of($FabricOutwardList)
                ->addIndexColumn()
                ->addColumn('fout_code1', function ($row) {
                    return substr($row->fout_code, 5, 20);
                })
                ->addColumn('action1', function ($row) {
                    return '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="FabricOutwardPrint/' . base64_encode($row->fout_code) . '" title="print">
                                <i class="fas fa-print"></i>
                            </a>';
                })
                ->addColumn('action2', function ($row) {
                    return '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="FabricOutwardRollsPrint/' . base64_encode($row->fout_code) . '" title="print">
                                <i class="fas fa-print"></i>
                            </a>';
                })
                ->addColumn('action3', function ($row) use ($chekform) {
                    return '<a class="btn btn-primary btn-icon btn-sm" href="' . route('FabricOutward.edit', $row->fout_code) . '">
                                <i class="fas fa-pencil-alt"></i>
                            </a>';
                })
                ->addColumn('action4', function ($row) use ($chekform) {
                    if (($chekform->delete_access == 1 && $row->all_count == 0) || Session::get('user_type') == 1) {
                        return '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="' . csrf_token() . '" data-id="' . $row->fout_code . '" data-route="' . route('FabricOutward.destroy', $row->fout_code) . '">
                                    <i class="fas fa-trash"></i>
                                </a>';
                    } else {
                        return '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete">
                                    <i class="fas fa-lock"></i>
                                </a>';
                    }
                })
                ->addColumn('total_amount', function ($row) {
                    return round($row->total_amount, 2);
                })
                ->rawColumns(['action1', 'action2', 'action3', 'action4'])
                ->make(true);
        }
    
        return view('FabricOutwardMasterList', compact('FabricOutwardList', 'chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FABRIC_OUTWARD'");
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ShadeList = DB::table('shade_master')->where('shade_master.delflag','=', '0')->get();
        $OutTypeList = DB::table('outward_type_master')->where('outward_type_master.delflag','=', '0')->whereIN('outward_type_master.out_type_id', [1,2,5,6,7])->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        return view('FabricOutwardMaster',compact('Ledger','ShadeList', 'PartList','CPList','OutTypeList','ItemList','counter_number','MainStyleList','SubStyleList','FGList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
             
            'fout_code'=>'required',
            'fout_date'=>'required',
            'vendorId'=>'required', 
            'total_meter'=>'required',
            'total_taga_qty'=>'required', 
        ]);
             
          
        
            $data1=array(
            
                'fout_code'=>$request->fout_code, 
                'fout_date'=>$request->fout_date,
                'out_type_id'=>$request->out_type_id,
                'vendorId'=>$request->vendorId, 
                'vpo_code'=>$request->vpo_code,
                'sample_indent_code'=>$request->sample_indent_code,
                'mainstyle_id' =>$request->mainstyle_id, 
                'substyle_id' =>$request->substyle_id,  
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no,  
                'style_description' => $request->style_description,
                'total_meter'=>$request->total_meter,  
                'total_taga_qty'=>$request->total_taga_qty,
                'narration'=>$request->in_narration,  
                'c_code' => $request->c_code,
                'userId'=>$request->userId, 
                'delflag'=>'0', 
                'CounterId'=>'1'
            );
            
            FabricOutwardModel::insert($data1);
            
            DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='FABRIC_OUTWARD'");
            $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                
            for($x=0; $x<count($request->item_code); $x++) 
            {
                $data2=array(
                'fout_code' =>$request->fout_code,
                'fout_date' => $request->fout_date,
                'out_type_id'=>$request->out_type_id,
                'vendorId'=>$request->vendorId,
                'vpo_code'=>$request->vpo_code,
                'sample_indent_code'=>$request->sample_indent_code,
                'mainstyle_id' =>$request->mainstyle_id,
                'substyle_id' =>$request->substyle_id,
                'fg_id' =>$request->fg_id,
                'style_no' => $request->style_no, 
                'style_description' => $request->style_description,
                'part_id' =>$request->part_ids[$x],
                'item_code' =>$request->item_code[$x],
                'meter' => $request->meters[$x],
                'width' => $request->widths[$x],
                'shade_id' =>$request->shade_ids[$x],
                'track_code' =>$request->track_codes[$x] ,
                'roll_no' =>$request->roll_no[$x] ,
                'item_rate' => $request->item_rate[$x],
                'usedflag' => '0',
                );
                  
                FabricOutwardDetailModel::insert($data2);
                
                
                $purchaseData = DB::table('inward_details')->where('inward_details.track_code', $request->track_codes[$x])->get();
                
                $buyer_id = isset($purchaseData[0]->buyer_id) ? $purchaseData[0]->buyer_id : 50;
                $po_code = isset($purchaseData[0]->po_code) ? $purchaseData[0]->po_code : '';   
                DB::select("update fabric_outward_details set po_code='".$po_code."' where track_code ='".$request->track_codes[$x]."'"); 
                DB::select("update fabric_outward_details set buyer_id='".$buyer_id."' where po_code ='".$po_code."'"); 
                            
                $item_code = isset($request->item_code[$x]) ? $request->item_code[$x] : 0;
                $item_qty = isset($request->meters[$x]) ? $request->meters[$x] : 0;
                $track_codes = isset($request->track_codes[$x]) ? $request->track_codes[$x] : ""; 
                        
                $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $track_codes)->get();
                $updated_string = '';
                $totalOutQty = 0;
                foreach($existingData as $outwards)
                {
                    $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
                    $totalOutQty += $outwards->meter;
                }
                DB::table('dump_fabric_stock_data')
                        ->where('item_code', '=', $item_code)
                        ->where('track_name', '=', $track_codes)
                        ->update([
                            'fout_date' =>  $request->fout_date,
                            'outward_qty' => $totalOutQty,
                            'ind_outward_qty' => '',
                        ]);
                DB::table('dump_fabric_stock_data')
                    ->where('item_code', '=', $item_code) 
                    ->where('track_name', '=', $track_codes)
                    ->update([
                        'fout_date' =>  $request->fout_date,
                        'outward_qty' => $totalOutQty,
                        'ind_outward_qty' => $updated_string
                ]);
                
                
                $data3[]=array(
                    'tr_code' =>$request->fout_code,
                    'tr_date' => $request->fout_date,
                    'Ac_code' =>$request->vendorId,
                    'cp_id' =>0,
                    'job_code'=>$request->vpo_code, 
                    'po_code'=>'',
                    'invoice_no'=>0,
                    'gp_no' =>0,
                    'fg_id' =>$request->fg_id,
                    'style_no' => $request->style_no,
                    'item_code'=>$request->item_code[$x], 
                    'part_id' =>$request->part_ids[$x],
                    'shade_id' =>$request->shade_ids[$x],
                    'track_code' => $request->track_codes[$x],
                    'old_meter'=>'0',
                    'short_meter'=>'0',
                    'rejected_meter'=>'0',
                    'meter' => $request->meters[$x],
                    'tr_type' => '3',
                    'userId'=>$request->userId,
                );
                
              
            $outwardData = DB::SELECT("SELECT 
                    sum(fabric_outward_details.meter) as item_qty,
                    fabric_outward_details.*,vendor_purchase_order_master.*,fabric_checking_details.po_code
                FROM 
                    fabric_outward_details  
                LEFT JOIN 
                    fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                LEFT JOIN 
                    vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                WHERE 
                    fabric_outward_details.fout_code = '".$request->fout_code."'");
                             
            $po_code = isset($outwardData[0]->po_code) ? $outwardData[0]->po_code : ""; 
            $sales_order_no = isset($outwardData[0]->sales_order_no) ? $outwardData[0]->sales_order_no : ""; 
            $item_code = isset($outwardData[0]->item_code) ? $outwardData[0]->item_code : 0; 
                
            $purchaseData = DB::table('inward_details')->where('inward_details.track_code', $request->track_codes[$x])->get();
            
            $buyer_id = isset($purchaseData[0]->buyer_id) ? $purchaseData[0]->buyer_id : 50;
            $po_code = isset($purchaseData[0]->po_code) ? $purchaseData[0]->po_code : '';   
        
            DB::select("update fabric_outward_details set buyer_id='".$buyer_id."' where po_code ='".$po_code."'"); 
                
            //DB::enableQueryLog();
            $tempData = DB::table("dump_fabric_stock_association")->where('po_code','=',$po_code)->where('item_code','=',$request->item_code[$x])->where('sales_order_no','=',$sales_order_no)->get();
            //dd(DB::getQueryLog());
            if(count($tempData) == 0)
            {
                $fabricAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                            FROM stock_association_for_fabric as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ".$request->item_code[$x]." 
                            AND sta.po_code='".$po_code."' AND sta.sales_order_no='".$sales_order_no."' GROUP BY sta.item_code");
                  // dd(DB::getQueryLog());                  
                foreach($fabricAssocData as $row)
                { 
                    $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                    
        
                    $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                   
                    $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
                    
                     
                    $fabricOutwardData1 = DB::select("select sum(fabric_outward_details.meter) as qty  FROM fabric_outward_details 
                                                INNER JOIN  fabric_checking_details ON  fabric_checking_details.track_code =  fabric_outward_details.track_code
                                                INNER JOIN  vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  fabric_outward_details.vpo_code
                                                WHERE fabric_checking_details.po_code='".$row->po_code."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND fabric_outward_details.item_code = ".$row->item_code);
                                                
                    $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0; 
                    $fabricOutwardStock = isset($fabricOutwardData1[0]->qty) ? $fabricOutwardData1[0]->qty : 0;
                     
                 
                    $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                    
                    $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                    $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                    
        
                    $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                    
                    DB::table('dump_fabric_stock_association')->insert(
                    array(
                      'item_name' => $row->item_name,
                      'po_code' => $row->po_code,
                      'po_date' => $row->po_date,
                      'bom_code' => $row->bom_code,
                      'sales_order_no' => $row->sales_order_no,
                      'item_code' => $row->item_code,
                      'allocated_qty' => $allocated_qty,
                      'totalAssoc' => $totalAssoc,
                      'otherAvaliableStock' => $otherAvaliableStock,
                      'fabricOutwardStock' => $fabricOutwardStock,
                      'eachAvaliableQty' =>  $eachAvaliableQty,
                    )
                  );
                }
            }
            else
            {
                 
                $fabricOutwardData = DB::SELECT("SELECT sum(fabricOutwardStock) as outward_qty FROM dump_fabric_stock_association  
                                                WHERE  po_code='".$po_code."' AND  item_code='".$item_code."' AND sales_order_no='".$sales_order_no."'"); 
     
                $outward_qty = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                
                
                DB::table('dump_fabric_stock_association')
                        ->where('po_code', '=', $po_code)
                        ->where('item_code', '=', $item_code)
                        ->where('sales_order_no', '=', $sales_order_no)
                        ->update(['fabricOutwardStock' => $outward_qty + $request->meters[$x]]);
       
            } 
            }
            FabricTransactionModel::insert($data3);
            $InsertSizeData=DB::select('call FabricOutwardStockAllocation("'.$request->fout_code.'")');
 
        }
        
        return redirect()->route('FabricOutward.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricOutwardModel $fabricOutwardModel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //DB::enableQueryLog();
        //dd(DB::getQueryLog());
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $OutTypeList = DB::table('outward_type_master')->where('outward_type_master.delflag','=', '0')->whereIN('outward_type_master.out_type_id', [1,2,5,6,7])->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  DB::table('shade_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
        $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=', '0')->get();
        $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
       
        $FabricOutwardMasterList = FabricOutwardModel::find($id);
        // DB::enableQueryLog();
         
        // $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $FabricOutwardDetails = FabricOutwardDetailModel::join('main_style_master','main_style_master.mainstyle_id', '=', 'fabric_outward_details.mainstyle_id','left') 
        ->join('item_master', 'item_master.item_code', '=', 'fabric_outward_details.item_code','left')
        ->where('fabric_outward_details.fout_code','=', $FabricOutwardMasterList->fout_code)
        ->get(['fabric_outward_details.*','main_style_master.mainstyle_name','item_master.item_name', 'item_master.color_name', 'item_master.item_description', 'item_master.dimension']);
  
       // $VPOrderList= VendorPurchaseOrderModel::select('vpo_code','sales_order_no')->where('vendorId',$FabricOutwardMasterList->vendorId)->get();
               
        // $S1= VendorPurchaseOrderModel::select('vpo_code')
        //     ->whereNotIn('vpo_code',function($query){
        //     $query->select('vpo_code')->from('fabric_outward_master');
        //     });
            
        // $S2=FabricOutwardModel::select('vpo_code')->where('fout_code',$FabricOutwardMasterList->fout_code);
        // // DB::enableQueryLog();
        // $VPOrderList = $S1->union($S2)->get();
        
        $S1 = DB::table('vendor_purchase_order_master as vpo')
            ->select('vpo.vpo_code')
            ->leftJoin('fabric_outward_master as fom', 'vpo.vpo_code', '=', 'fom.vpo_code')
            ->whereNull('fom.vpo_code');
        
        $S2 = DB::table('fabric_outward_master')
            ->select('vpo_code')
            ->where('fout_code', $FabricOutwardMasterList->fout_code);
        
        $VPOrderList = $S1->unionAll($S2)->get();
        

    //   dd(DB::getQueryLog());
        return view('FabricOutwardMasterEdit',compact('FabricOutwardMasterList','OutTypeList', 'ShadeList', 'PartList', 'Ledger','CPList','MainStyleList','SubStyleList','FGList',  'FabricOutwardDetails','VPOrderList'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FabricOutwardModel $fabricOutwardModel,FabricOutwardDetailActivityLog $loggerDetail,FabricOutwardMasterActivityLog $loggerMaster)
    {
        //echo "<pre>"; print_R($_POST);exit;
        $this->validate($request, [
             
            'fout_code'=>'required',
            'fout_date'=>'required',
            'vendorId'=>'required',
          
            'mainstyle_id'=>'required',
            'style_no'=>'required',
            'total_meter'=>'required',
            'total_taga_qty'=>'required',
            
            'c_code'=>'required',
             ]);

             $data1=array(

                'fout_code'=>$request->fout_code, 'fout_date'=>$request->fout_date,'out_type_id'=>$request->out_type_id,
             'vendorId'=>$request->vendorId, 'vpo_code'=>$request->vpo_code,
            'mainstyle_id' =>$request->mainstyle_id, 'substyle_id' =>$request->substyle_id,  'fg_id' =>$request->fg_id,
            'style_no' => $request->style_no,  'style_description' => $request->style_description, 
                'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
                 'narration'=>$request->in_narration,  'c_code' => $request->c_code,
                'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','updated_at'=>date("Y-m-d H:i:s")
                
                
            );
            
            
            
                    $MasterOldFetch = DB::table('fabric_outward_master')
                    ->select('fout_date','total_meter','total_taga_qty','narration')  
                    ->where('fout_code',$request->fout_code)
                    ->first();
        
             $MasterOld = (array) $MasterOldFetch;
        
        
                  $MasterNew=[
             'fout_date'=>$request->fout_date,
            'total_meter'=>$request->total_meter,
            'total_taga_qty'=>$request->total_taga_qty,
            'narration'=>$request->in_narration
            ];

          
               try {
            $loggerMaster->logIfChangedFabricOutwardMaster(
            'fabric_outward_master',
            $request->fout_code,
            $MasterOld,
            $MasterNew,
            'UPDATE',
            $request->fout_date,
            'fabric_outward_master'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for fabric_checking_master.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'fout_code' =>  $request->fout_code,
            'data' => $MasterNew
            ]);
            }  
            
            

            
            
            $FabricOutwardMasterList = FabricOutwardModel::findOrFail($request->input('fout_code'));  
   
            $FabricOutwardMasterList->fill($data1)->save();
            
            
            
                     $olddata1 = DB::table('fabric_outward_details')
            ->select('track_code','meter','width')  
            ->where('fout_code',$request->input('fout_code'))
            ->get()
            ->map(function ($item) {
            return (array) $item;
            })
            ->toArray();
            
            $combinedOldData = $olddata1;
            
            

            DB::table('fabric_outward_details')->where('fout_code', $request->input('fout_code'))->delete();
            DB::table('fabric_transaction')->where('tr_code', $request->input('fout_code'))->delete();

            $item_code = $request->input('item_code');
            if(count($item_code)>0)
            {
                  
            for($x=0; $x<count($request->item_code); $x++) 
            {
                # code...
                
                       // if($request->track_codes[$x]!=''){ $track_code=$request->track_codes[$x];}else{$track_code='P'.++$PBarcodes;}
                            $data2=array(
                            'fout_code' =>$request->fout_code,
                            'fout_date' => $request->fout_date,
                            'out_type_id'=>$request->out_type_id,
                            'vendorId'=>$request->vendorId, 
                            'vpo_code'=>$request->vpo_code,
                            'mainstyle_id' =>$request->mainstyle_id,
                            'substyle_id' =>$request->substyle_id, 
                            'fg_id' =>$request->fg_id,
                            'style_no' => $request->style_no,  
                            'style_description' => $request->style_description,
                            'part_id' =>$request->part_ids[$x],
                            'item_code' =>$request->item_code[$x],
                            'meter' => $request->meters[$x],
                            'width' => $request->widths[$x],
                            'shade_id' =>$request->shade_ids[$x],
                            'track_code' =>$request->track_codes[$x],
                            'roll_no' =>$request->roll_no[$x] ,
                             'item_rate' => $request->item_rate[$x],
                            'usedflag' => '0',
                            
                            );
                   
                  
                              // DB::enableQueryLog();
                                $Roll = DB::table('fabric_checking_details')->select('po_code','old_meter','reject_short_meter','meter')
                                ->where('track_code',$request->track_codes[$x])->first();    
                               // dd(DB::getQueryLog());
                              
                                $po_code = "";
                                
                                $data3=array(
                                    'tr_code' =>$request->fout_code,
                                    'tr_date' => $request->fout_date,
                                    'Ac_code' =>$request->vendorId,
                                    'cp_id' =>0,
                                    'job_code'=>$request->vpo_code, 
                                    'po_code'=>$po_code,
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>$request->mainstyle_id,
                                    'style_no' => $request->style_no,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_ids[$x],
                                    'shade_id' =>$request->shade_ids[$x],
                                    'track_code' => $request->track_codes[$x],
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meters[$x],
                                    'tr_type' => '3',
                                    'userId'=>$request->userId,
                                );
                    
                
                        
                
                            FabricOutwardDetailModel::insert($data2);
                            FabricTransactionModel::insert($data3);
                  
                            $purchaseData = DB::table('inward_details')->where('inward_details.track_code', $request->track_codes[$x])->get();
                            
                            $buyer_id = isset($purchaseData[0]->buyer_id) ? $purchaseData[0]->buyer_id : 50;
                            $po_code = isset($purchaseData[0]->po_code) ? $purchaseData[0]->po_code : '';   
                            DB::select("update fabric_outward_details set po_code='".$po_code."' where track_code ='".$request->track_codes[$x]."'"); 
                            DB::select("update fabric_outward_details set buyer_id='".$buyer_id."' where po_code ='".$po_code."'"); 
                            
                            $item_code = isset($request->item_code[$x]) ? $request->item_code[$x] : 0;
                            $fout_date = $request->fout_date;
                            $item_qty = isset($request->meters[$x]) ? $request->meters[$x] : 0;
                            $track_codes = isset($request->track_codes[$x]) ? $request->track_codes[$x] : ""; 
                            $new_entry = $request->fout_date.'=>'.$item_qty; 
                            
                       
                                    
                            $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $track_codes)->get();
                            $updated_string = '';
                            $totalOutQty = 0;
                            foreach($existingData as $outwards)
                            {
                                $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
                                $totalOutQty += $outwards->meter;
                            }
                            DB::table('dump_fabric_stock_data')
                                    ->where('item_code', '=', $item_code)
                                    ->where('track_name', '=', $track_codes)
                                    ->update([
                                        'fout_date' =>  $request->fout_date,
                                        'outward_qty' => $totalOutQty,
                                        'ind_outward_qty' => '',
                                    ]);
                            DB::table('dump_fabric_stock_data')
                                ->where('item_code', '=', $item_code) 
                                ->where('track_name', '=', $track_codes)
                                ->update([
                                    'fout_date' =>  $request->fout_date,
                                    'outward_qty' => $totalOutQty,
                                    'ind_outward_qty' => $updated_string
                            ]); 
                
                            $purchaseData = DB::table('inward_details')->where('inward_details.track_code', $request->track_codes[$x])->get();
                            
                            $buyer_id = isset($purchaseData[0]->buyer_id) ? $purchaseData[0]->buyer_id : 50;
                            $po_code = isset($purchaseData[0]->po_code) ? $purchaseData[0]->po_code : '';   
                        
                            DB::select("update fabric_outward_details set buyer_id='".$buyer_id."' where po_code ='".$po_code."'"); 
                        
                            // if ($existingData) 
                            // {  
                            //     // // Check if both fout_date and item_qty mismatch
                            //     // if ($existingData->fout_date !== $fout_date || $existingData->ind_outward_qty != $item_qty) 
                            //     // { 
                            //     //     // Split the existing data into an array
                            //     //     $existingEntries = explode(',', $existingData->ind_outward_qty);
                           
                            //     //     // Check if the new entry already exists in the array
                            //     //     if (!in_array($new_entry, $existingEntries)) 
                            //     //     {
                            //     //         // If the new entry does not exist, add it to the array
                            //     //         $existingEntries[] = $new_entry;
                            
                            //     //         // Join the array back into a comma-separated string
                            //     //         $updatedData = implode(',', $existingEntries);
                            //     //         DB::table('dump_fabric_stock_data')
                            //     //             ->where('item_code', '=', $item_code)
                            //     //             ->where('track_name', '=', $track_codes)
                            //     //             ->update([
                            //     //                 'fout_date' =>  $request->fout_date,
                            //     //                 'outward_qty' => $item_qty,
                            //     //                 'ind_outward_qty' => '',
                            //     //             ]);
                            //     //         // Update the database with the new value
                            //     //         DB::table('dump_fabric_stock_data')
                            //     //             ->where('item_code', '=', $item_code)
                            //     //             ->where('track_name', '=', $track_codes)
                            //     //             ->update([
                            //     //                 'fout_date' =>  $request->fout_date,
                            //     //                 'outward_qty' => $item_qty,
                            //     //                 'ind_outward_qty' => $updatedData,
                            //     //             ]);
                                           
                            //     //     }
                            //     //     else
                            //     //     {
                          
                                           
                            //               if($existingData->ind_outward_qty != '' && $existingData->fout_date !== $fout_date || $existingData->ind_outward_qty != $item_qty)
                            //               { 
                            //                         // Split the existing data into an array
                            //                         $existingEntries = explode(',', $existingData->ind_outward_qty);
                            //                         unset($existingEntries[0]);
                                                    
                            //                         // Check if the new entry already exists in the array
                            //                         if (!in_array($new_entry, $existingEntries)) 
                            //                         {
                            //                             // If the new entry does not exist, add it to the array
                            //                             $existingEntries[] = $new_entry;
                                            
                            //                             // Join the array back into a comma-separated string
                            //                             $updatedData = implode(',', $existingEntries);
                                                        
                            //                              DB::table('dump_fabric_stock_data')
                            //                             ->where('item_code', '=', $item_code) 
                            //                             ->where('track_name', '=', $track_codes)
                            //                             ->update([
                            //                                 'fout_date' =>  $request->fout_date,
                            //                                 'outward_qty' => $item_qty,
                            //                                 'ind_outward_qty' => $updatedData
                            //                             ]);
                                                        
                            //                         }
                            //                         else
                            //                         {
                            //                              DB::table('dump_fabric_stock_data')
                            //                             ->where('item_code', '=', $item_code) 
                            //                             ->where('track_name', '=', $track_codes)
                            //                             ->update([
                            //                                 'fout_date' =>  $request->fout_date,
                            //                                 'outward_qty' => $item_qty,
                            //                                 'ind_outward_qty' => DB::raw("REPLACE(ind_outward_qty, '$new_entry', '$new_entry')")
                            //                             ]);
                            //                         }
                                                            
                                                   
                                                    
                            //               }
                            //               else
                            //               {
                            //                      DB::table('dump_fabric_stock_data')
                            //                     ->where('item_code', '=', $item_code) 
                            //                     ->where('track_name', '=', $track_codes)
                            //                     ->update([
                            //                         'fout_date' =>  $request->fout_date,
                            //                         'outward_qty' => $item_qty,
                            //                         'ind_outward_qty' => $new_entry
                            //                     ]);
                            //               }
                                  
                            //     //     }
                            //     // }
                            // } 
                            // else 
                            // { 
                            //     DB::table('dump_fabric_stock_data')
                            //     ->where('item_code', '=', $item_code) 
                            //     ->where('track_name', '=', $track_codes)
                            //     ->update([
                            //         'fout_date' =>  $request->fout_date,
                            //         'outward_qty' => $item_qty,
                            //         'ind_outward_qty' =>  DB::raw("REPLACE(ind_outward_qty, '$new_entry', '$new_entry')")
                            //     ]);
                                 
                            // }


                            
                           
                            // $existingData = DB::table('dump_fabric_stock_data')
                            //     ->where('item_code', '=', $item_code)
                            //     ->where('track_name', '=', $track_codes)
                            //     ->value('ind_outward_qty');
                                
                            // $newData = $existingData ? $existingData . ',' . $request->fout_date . '=>' . $item_qty : $request->fout_date . '=>' . $item_qty;
                             
                            // DB::table('dump_fabric_stock_data')
                            //     ->where('item_code', '=', $item_code)
                            //     ->where('track_name', '=', $track_codes)
                            //     ->update(['fout_date' => $request->fout_date, 'outward_qty' => $item_qty, 'ind_outward_qty' => $newData]);
                            
                
                        
                        $outwardData = DB::SELECT("SELECT 
                                sum(fabric_outward_details.meter) as item_qty,
                                fabric_outward_details.*,vendor_purchase_order_master.*,fabric_checking_details.po_code
                            FROM 
                                fabric_outward_details  
                            LEFT JOIN 
                                fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                            LEFT JOIN 
                                vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                            WHERE 
                                fabric_outward_details.fout_code = '".$request->fout_code."'");
                                         
                        $po_code = isset($outwardData[0]->po_code) ? $outwardData[0]->po_code : ""; 
                        $item_qty = isset($outwardData[0]->item_qty) ? $outwardData[0]->item_qty : ""; 
                        $sales_order_no = isset($outwardData[0]->sales_order_no) ? $outwardData[0]->sales_order_no : ""; 
            
                        //DB::enableQueryLog();
                        $tempData = DB::table("dump_fabric_stock_association")->where('po_code','=',$po_code)->where('item_code','=',$request->item_code[$x])->where('sales_order_no','=',$sales_order_no)->get();
                        //dd(DB::getQueryLog());
                        if(count($tempData) == 0)
                        {
                            $fabricAssocData = DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code,tr_type 
                                        FROM stock_association_for_fabric as sta LEFT JOIN item_master ON item_master.item_code = sta.item_code WHERE  sta.item_code = ".$item_qty." 
                                        AND sta.po_code='".$po_code."' AND sta.sales_order_no='".$sales_order_no."' GROUP BY sta.item_code");
                              // dd(DB::getQueryLog());                  
                            foreach($fabricAssocData as $row)
                            { 
                                $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."'  AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                                
                    
                                $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                               
                                $otherAvaliableData = DB::select("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE po_code = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code=".$row->item_code." AND sales_order_no!='".$row->sales_order_no."' AND tr_type=".$row->tr_type);
                                
                                 
                                $fabricOutwardData1 = DB::select("select sum(fabric_outward_details.meter) as qty  FROM fabric_outward_details 
                                                            INNER JOIN  fabric_checking_details ON  fabric_checking_details.track_code =  fabric_outward_details.track_code
                                                            INNER JOIN  vendor_purchase_order_master ON  vendor_purchase_order_master.vpo_code =  fabric_outward_details.vpo_code
                                                            WHERE fabric_checking_details.po_code='".$row->po_code."' AND vendor_purchase_order_master.sales_order_no = '".$row->sales_order_no."' AND fabric_outward_details.item_code = ".$row->item_code);
                                                            
                                $otherAvaliableStock = isset($otherAvaliableData[0]->qty) ? $otherAvaliableData[0]->qty : 0; 
                                $fabricOutwardStock = isset($fabricOutwardData1[0]->qty) ? $fabricOutwardData1[0]->qty : 0;
                                 
                             
                                $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                                
                                $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                                $stockData = DB::SELECT("SELECT sum(qty) as qty FROM stock_association_for_fabric WHERE `po_code` = '".$row->po_code."' AND bom_code='".$row->bom_code."' AND item_code='".$row->item_code."' AND sales_order_no='".$row->sales_order_no."' AND tr_type=1");
                                
                    
                                $totalAssoc = isset($stockData[0]->qty) ? $stockData[0]->qty : 0;
                                
                                DB::table('dump_fabric_stock_association')->insert(
                                array(
                                  'item_name' => $row->item_name,
                                  'po_code' => $row->po_code,
                                  'po_date' => $row->po_date,
                                  'bom_code' => $row->bom_code,
                                  'sales_order_no' => $row->sales_order_no,
                                  'item_code' => $row->item_code,
                                  'allocated_qty' => $allocated_qty,
                                  'totalAssoc' => $totalAssoc,
                                  'otherAvaliableStock' => $otherAvaliableStock,
                                  'fabricOutwardStock' => $fabricOutwardStock,
                                  'eachAvaliableQty' =>  $eachAvaliableQty,
                                )
                              );
                            }
                        }
                        else
                        {
                             
                            $fabricOutwardData = DB::SELECT("SELECT sum(fabricOutwardStock) as outward_qty FROM dump_fabric_stock_association  
                                                            WHERE  po_code='".$outwardData[0]->po_code."' AND  item_code='".$outwardData[0]->item_code."' AND sales_order_no='".$outwardData[0]->sales_order_no."'"); 
                 
                            $outward_qty = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                            
                            $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric 
                                                WHERE `po_code` = '".$outwardData[0]->po_code."' AND item_code='".$outwardData[0]->item_code."' AND sales_order_no='".$outwardData[0]->sales_order_no."' 
                                                AND tr_type=1");
                                        
                            $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                                       
                            $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter),0) as outward_qty FROM fabric_outward_details
                                                            INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
                                                            INNER JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
                                                            WHERE  fabric_outward_details.item_code='".$outwardData[0]->item_code."' and fabric_checking_details.po_code='".$outwardData[0]->po_code."' AND vendor_purchase_order_master.sales_order_no='".$outwardData[0]->sales_order_no."' GROUP BY fabric_outward_details.item_code"); 
                                      
                            $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
                                      
                            $eachData = DB::SELECT("SELECT  ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE po_code='".$outwardData[0]->po_code."' AND item_code='".$outwardData[0]->item_code."' AND sales_order_no='".$outwardData[0]->sales_order_no."' AND tr_type = 2  AND tr_code IS NULL"); 
                                            $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
                                  
                            $remainStock = $allocated_qty - $eachAvaliableQty;

                            //echo $outward_qty.'<br/>';
                            //echo $request->meters[$x];exit;
                            DB::table('dump_fabric_stock_association')
                                    ->where('po_code', '=', $outwardData[0]->po_code)
                                    ->where('item_code', '=', $outwardData[0]->item_code)
                                    ->where('sales_order_no', '=', $outwardData[0]->sales_order_no)
                                    ->update(['fabricOutwardStock' => $fabricOutwardStock]);
                   
                        } 
                        
                        
                        
                                     $newDataDetail2[]=[
                                'track_code' =>$request->track_codes[$x],          
                                'meter' => $request->meters[$x],
                                'width' => $request->widths[$x]
                                ];    
                        
                        
                    }
                     $InsertSizeData=DB::select('call FabricOutwardStockAllocation("'.$request->fout_code.'")');
                     
                } 
                
               
               
              $combinedNewData = $newDataDetail2;       
           
            try {
            $loggerDetail->logIfChangedFabricOutwardDetail(
            'fabric_outward_details',
            $request->fout_code,
            $combinedOldData,
            $combinedNewData,
            'UPDATE',
            $request->fout_date,
            'fabric_outward_details'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for fabric_outward_details.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'fout_code' => $request->fout_code,
            'data' => $combinedNewData
            ]);
            }  
               
               
               
    
                return redirect()->route('FabricOutward.index');
            }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricOutwardModel  $fabricOutwardModel
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {      
         
        
        $outData = DB::table('fabric_outward_details')->select('item_code','track_code','fout_date','meter')->where('fout_code', '=', $id)->get();     
        foreach($outData as  $rows)
        {
            $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $rows->track_code)->where('fout_code', '!=', $id)->get();
            $updated_string = '';
            foreach($existingData as $outwards)
            {
                $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
            }
            DB::table('dump_fabric_stock_data')
            ->where('item_code', '=', $rows->item_code)
            ->where('track_name', '=', $rows->track_code)
            ->update([
                'fout_date' =>  $rows->fout_date,
                'outward_qty' => $rows->meter,
                'ind_outward_qty' => $updated_string,
            ]);
        }            
                            
        $outwardData = DB::SELECT("SELECT 
                sum(fabric_outward_details.meter) as item_qty,
                fabric_outward_details.*,vendor_purchase_order_master.*,fabric_checking_details.po_code
            FROM 
                fabric_outward_details  
            LEFT JOIN 
                fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
            LEFT JOIN 
                vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
            WHERE 
                fabric_outward_details.fout_code = '".$id."'");
        
             
        $fabricOutwardStock=0;
   
        $fabricOutwardData = DB::SELECT("SELECT sum(fabricOutwardStock) as outward_qty FROM dump_fabric_stock_association  
                                        WHERE  po_code='".$outwardData[0]->po_code."' AND  item_code='".$outwardData[0]->item_code."' AND sales_order_no='".$outwardData[0]->sales_order_no."'"); 

        $item_qty = isset($outwardData[0]->item_qty) ? $outwardData[0]->item_qty : 0;
        $outward_qty = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
        
        $fabricOutwardStock = $outward_qty - $item_qty;
        
        DB::table('dump_fabric_stock_association')
                ->where('po_code', '=', $outwardData[0]->po_code)
                ->where('item_code', '=', $outwardData[0]->item_code)
                ->where('sales_order_no', '=', $outwardData[0]->sales_order_no)
                ->update(['fabricOutwardStock' => $fabricOutwardStock]);
                
        DB::table('stock_association_for_fabric')
        ->where('po_code', '=', $outwardData[0]->po_code)
        ->where('item_code', '=', $outwardData[0]->item_code)
        ->where('tr_type', '=', 2)
        ->delete();    
        
        DB::table('fabric_outward_master')->where('fout_code', $id)->delete();
        DB::table('fabric_outward_details')->where('fout_code', $id)->delete();
        $detail =FabricTransactionModel::where('tr_code',$id)->delete();
         
  
        Session::flash('delete', 'Deleted record successfully'); 
        
    }

 
 
    public function getSalesOrderDetail2(Request $request)
    { 
        $vpo_codes= $request->input('vpo_code');
        
        
     if($sales_order_no!='')
        {
            $MasterdataList = DB::select("select   Ac_code, mainstyle_id, substyle_id, fg_id, style_no, style_description from vendor_purchse_order_master where  vpo_code in (". $vpo_codes.")");
        }
        
        return json_encode($MasterdataList);
    
    }
    
    // public function FabricOutwardData()
    // {
      
    //     $FabricOutwardDetails = DB::table('fabric_outward_details')->
    //         select('fabric_outward_details.*', 'part_master.part_name','ledger_master.ac_name','item_master.dimension', 
    //         'item_master.item_name','item_master.color_name','item_master.item_description',
    //         'quality_master.quality_name','inward_details.po_code',
    //         DB::raw('(select sales_order_no from vendor_purchase_order_master where vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code) as sales_order_no'))
    //         ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'fabric_outward_details.vendorId')
    //         ->leftJoin('item_master', 'item_master.item_code', '=', 'fabric_outward_details.item_code')
    //         ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
    //         ->leftJoin('inward_details', 'inward_details.track_code', '=', 'fabric_outward_details.track_code')
    //         ->leftJoin('part_master', 'part_master.part_id', '=', 'fabric_outward_details.part_id')
    //         ->orderby('fout_date','DESC')
    //         ->get();
    
    //     return view('FabricOutwardData',compact('FabricOutwardDetails'));
    // }
    
    public function FabricOutwardData(Request $request)
    {  
        
        if ($request->ajax()) 
        { 
           //DB::enableQueryLog();
            $FabricOutwardDetails = DB::table('fabric_outward_details')->
                select('fabric_outward_details.*', 'part_master.part_name','ledger_master.ac_short_name','item_master.dimension', 
                'item_master.item_name','item_master.color_name','item_master.item_description',
                'quality_master.quality_name','inward_details.po_code','inward_details.item_rate','LM1.ac_short_name as buyer','out_type_name',
                  DB::raw('(select sales_order_no from vendor_purchase_order_master where vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code) as sales_order_no'),
                   DB::raw('(SELECT trade_name FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to OR ledger_details.ac_code = inward_details.Ac_code LIMIT 1) as trade_name'),
                   DB::raw('(SELECT site_code FROM ledger_details WHERE ledger_details.sr_no = purchase_order.bill_to OR ledger_details.ac_code = inward_details.Ac_code LIMIT 1) as site_code'),
                   DB::raw('(SELECT ledger_master.ac_short_name FROM ledger_master WHERE ledger_master.ac_code = purchase_order.Ac_code  OR ledger_master.ac_code = inward_details.Ac_code LIMIT 1) as supplier_name')
                  )
                ->leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'fabric_outward_details.vendorId')
                ->leftJoin('item_master', 'item_master.item_code', '=', 'fabric_outward_details.item_code')
                ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
                ->leftJoin('inward_details', 'inward_details.track_code', '=', 'fabric_outward_details.track_code')
                ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'fabric_outward_details.buyer_id')
                ->leftJoin('part_master', 'part_master.part_id', '=', 'fabric_outward_details.part_id')
                ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'fabric_outward_details.po_code')
                ->join('fabric_outward_master', 'fabric_outward_master.fout_code', '=', 'fabric_outward_details.fout_code')
                ->leftJoin('outward_type_master', 'outward_type_master.out_type_id', '=', 'fabric_outward_master.out_type_id')
                ->orderby('fout_date','DESC')
                ->get();
            //dd(DB::getQueryLog());
            return Datatables::of($FabricOutwardDetails)
            ->addColumn('vendorName',function ($row) 
            { 

                $vendorName = $row->buyer;
               
                return $vendorName;
            })
            ->addColumn('item_value',function ($row) 
            {
                
                $item_value = $row->meter * $row->item_rate;
               
                return $item_value;
            })
            ->addColumn('bill_to',function ($row) 
            { 
                if($row->site_code != '')
                {
                    $bill_to = $row->trade_name.'('.$row->site_code.')';
                }
                else
                {
                    $bill_to = $row->trade_name;
                }
                return $bill_to;
            })
            ->rawColumns(['vendorName','item_value','bill_to'])
            ->make(true);
        }
        return view('FabricOutwardData');
    }
        
    public function FabricOutwardDataMD(Request $request,$DFilter) 
    {
        if ($request->ajax()) 
        { 
            if($DFilter == 'd')
            {
                $filterDate = " AND fabric_outward_details.fout_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            }
            else if($DFilter == 'm')
            {
                $filterDate = ' AND MONTH(fabric_outward_details.fout_date) = MONTH(CURRENT_DATE()) and YEAR(fabric_outward_details.fout_date)=YEAR(CURRENT_DATE()) AND fabric_outward_details.fout_date != "'.date('Y-m-d').'"';
            }
            else if($DFilter == 'y')
            {
                $filterDate = ' AND fabric_outward_details.fout_date between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
                
            }
            else
            {
                $filterDate = "";
            }
        
            $FabricOutwardDetails = DB::select("SELECT fabric_outward_details.*,part_master.part_name,ledger_master.ac_name,item_master.dimension, 
                item_master.item_name,item_master.color_name,item_master.item_description, quality_master.quality_name,inward_details.po_code, 
                (select sales_order_no from vendor_purchase_order_master where vendor_purchase_order_master.vpo_code=fabric_outward_details.vpo_code) as sales_order_no
                FROM fabric_outward_details 
                LEFT JOIN ledger_master ON ledger_master.ac_code = fabric_outward_details.vendorId
                LEFT JOIN item_master ON item_master.item_code = fabric_outward_details.item_code
                LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                LEFT JOIN inward_details ON inward_details.track_code = fabric_outward_details.track_code
                LEFT JOIN part_master ON part_master.part_id = fabric_outward_details.part_id
                WHERE 1 ".$filterDate." ORDER BY fout_date DESC");
            
            return Datatables::of($FabricOutwardDetails)
            ->addColumn('vendorName',function ($row) 
            {
                $VendorData = DB::select("select ledger_master.ac_name as vendorName from vendor_purchase_order_master 
                              inner join ledger_master on ledger_master.ac_code=vendor_purchase_order_master.Ac_code 
                              where vpo_code='".$row->vpo_code."'");

                $vendorName = isset($VendorData[0]->vendorName) ? $VendorData[0]->vendorName:"-";
               
                return $vendorName;
            })
            ->addColumn('item_value',function ($row) 
            {
                
                $item_value = $row->meter * $row->item_rate;
               
                return $item_value;
            })
             ->rawColumns(['vendorName','item_value'])
             
             ->make(true);
        }
        return view('FabricOutwardData');
    }
 
    public function getFabricRecord(Request $request)
    { 
        $track_code= $request->input('track_code');
        $vpo_code = $request->vpo_code;
        $out_type_id = $request->out_type_id;
        
        $CBD = DB::table('fabric_outward_details')->select(DB::raw("ifnull(SUM(meter),0) as meter"))->where('track_code',$track_code)->first();
                       
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
   
        $PartList = FabricTrimPartModel::where('part_master.delflag','=', '0')->get();
        $ShadeList = DB::table('shade_master')->get();
       
        $Roll = DB::table('fabric_checking_details')->select('track_code', 'fabric_checking_details.item_code','fabric_checking_details.item_rate','width','item_description','color_name','part_id' ,'roll_no','shade_id','meter','reject_short_meter')
        ->leftjoin('item_master', 'item_master.item_code', '=', 'fabric_checking_details.item_code')
        ->where('track_code',$track_code)->first();
      
        if(!empty($CBD))
        {
            $meter =   $Roll->meter - $CBD->meter ;
     
        }
        else
        {
            $meter = $CBD->meter;
        }
    
             // DB::enableQueryLog();
              $vendorPurcaseOrderData = DB::SELECT("SELECT(SELECT count(*) FROM vendor_purchase_order_trim_fabric_details WHERE vpo_code = '".$vpo_code."' AND  item_code='".$Roll->item_code."') + 
                                        (SELECT count(*) FROM vendor_purchase_order_detail WHERE vpo_code = '".$vpo_code."' AND  item_code='".$Roll->item_code."') + 
                                        (SELECT count(*) FROM vendor_purchase_order_packing_trims_details WHERE vpo_code = '".$vpo_code."' AND  item_code='".$Roll->item_code."') as total_count");
              // dd(DB::getQueryLog());
              $total_count = isset($vendorPurcaseOrderData[0]->total_count) ? $vendorPurcaseOrderData[0]->total_count: 0;
        if($out_type_id == 1)
        {
        //   if($meter>0 && $total_count > 0)
        //   {   
        
            $html = '';
                        
                        $no=1;
                    
                        $html .='<tr class="thisRow">';
                    
                    $html .='
                    <td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                    $html.='<td>
                    <input type="text" name="track_codes[]" class="track_code" id="track_codes'.$no.'" value="'.$track_code.'" style="width:80px;" required readOnly/>
                    <input type="hidden" name="item_rate[]"   id="item_rate'.$no.'" value="'.$Roll->item_rate.'" style="width:80px;" required readOnly/>
                    </td> ';
                   
                    $html.='<td>
                    <input type="text"  name="roll_no[]"  id="roll_no'.$no.'" value="'.$Roll->roll_no.'" style="width:80px;" required readOnly /> </td>  
                    
                    <td> <input type="hidden"  id="item_code'.$no.'" value="'.$Roll->item_code.'" style="width:80px;" readOnly /> <select name="item_code[]"  id="item_code" style="width:100px;" required disabled>
                    <option value="">--Item--</option>';
                    
                    foreach($ItemList as  $row1)
                    {
                        $html.='<option value="'.$row1->item_code.'"';
                    
                        $row1->item_code == $Roll->item_code ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td>  
                    <td>'.$Roll->color_name.'</td>
                     <td>'.$Roll->item_description.'</td>
                    <td> <select name="part_ids[]"  id="part_ids'.$no.'" style="width:100px;" required disabled>
                    <option value="">--Part--</option>';
                    foreach($PartList as  $rowP)
                    {
                        $html.='<option value="'.$rowP->part_id.'"';

                        $rowP->part_id == $Roll->part_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowP->part_name.'</option>';
                    }
                    $html.='</select></td> 
                   
                   
                   
                   
                   
                    <td> <select name="shade_ids[]"  id="shade_ids'.$no.'" style="width:100px;" required disabled>
                    <option value="">--Shade--</option>';
                    foreach($ShadeList as  $rowP)
                    {
                        $html.='<option value="'.$rowP->shade_id.'"';

                        $rowP->shade_id == $Roll->shade_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowP->shade_name.'</option>';
                    }
                    $html.='</select></td>';
                    
                    
                      $html.='<td>
                    <input type="number" step="any" name="widths[]"  id="widths'.$no.'" value="'.$Roll->width.'" style="width:80px;" required  /> </td> ';
                    $html.='<td>
                    <input type="number" step="any" name="meters[]" class="METER" id="meters'.$no.'" min="0"  max="'.($meter + $Roll->reject_short_meter).'" maxval="'.($meter + $Roll->reject_short_meter).'" value="'.($meter + $Roll->reject_short_meter).'" style="width:80px;" required onkeyup="mycalc();"  set="0" /> <span>'.($meter + $Roll->reject_short_meter).'</span> </td> 
                    <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
                    
                        $html .='</tr>';
                        $no=$no+1;
                   return response()->json(['html' => $html]);
                // }
                // else
                // {
                //          return response()->json(['html' => 'Zero','total_count'=>$total_count]);  
                // }
        }
        else
        {
            //  if($meter>0)
        //   {   
        
                $html = '';
                        
                        $no=1;
                    
                        $html .='<tr class="thisRow">';
                    
                    $html .='
                    <td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;" readOnly/></td>';
                    $html.='<td>
                    <input type="text" name="track_codes[]" class="track_code" id="track_codes'.$no.'" value="'.$track_code.'" style="width:80px;" required readOnly/>
                    <input type="hidden" name="item_rate[]"   id="item_rate'.$no.'" value="'.$Roll->item_rate.'" style="width:80px;" required readOnly/>
                    </td> ';
                   
                    $html.='<td>
                    <input type="text"  name="roll_no[]"  id="roll_no'.$no.'" value="'.$Roll->roll_no.'" style="width:80px;" required readOnly /> </td>  
                    
                    <td> 
                    <input type="hidden"  id="item_code'.$no.'" value="'.$Roll->item_code.'" style="width:80px;" readOnly /> <select name="item_code[]"  id="item_code" style="width:100px;" required disabled>
                    <option value="">--Item--</option>';
                    
                    foreach($ItemList as  $row1)
                    {
                        $html.='<option value="'.$row1->item_code.'"';
                    
                        $row1->item_code == $Roll->item_code ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td>  
                    <td>'.$Roll->color_name.'</td>
                     <td>'.$Roll->item_description.'</td>
                    <td> <select name="part_ids[]"  id="part_ids'.$no.'" style="width:100px;" required disabled>
                    <option value="">--Part--</option>';
                    foreach($PartList as  $rowP)
                    {
                        $html.='<option value="'.$rowP->part_id.'"';

                        $rowP->part_id == $Roll->part_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowP->part_name.'</option>';
                    }
                    $html.='</select></td> 
                    <td> <select name="shade_ids[]"  id="shade_ids'.$no.'" style="width:100px;" required disabled>
                    <option value="">--Shade--</option>';
                    foreach($ShadeList as  $rowP)
                    {
                        $html.='<option value="'.$rowP->shade_id.'"';

                        $rowP->shade_id == $Roll->shade_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowP->shade_name.'</option>';
                    }
                    $html.='</select></td>';
                    
                    
                    $html.='<td>
                    <input type="number" step="any" name="widths[]"  id="widths'.$no.'" value="'.$Roll->width.'" style="width:80px;" required  /> </td> ';
                    $html.='<td><input type="number" step="any" name="meters[]" class="METER" id="meters'.$no.'" min="0" max="'.($meter + $Roll->reject_short_meter).'"   maxval="'.($meter + $Roll->reject_short_meter).'" value="'.($meter + $Roll->reject_short_meter).'" style="width:80px;" required onkeyup="mycalc();"  set="0" /> <span>'.($meter + $Roll->reject_short_meter).'</span></td> 
                    <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>';
                    
                        $html .='</tr>';
                        $no=$no+1;
                   return response()->json(['html' => $html]);
                // }
                // else
                // {
                //          return response()->json(['html' => 'Zero','total_count'=>$total_count]);  
                // }
        }

    }

    public function GetSINCodeForFabricOutwardList(Request $request)
    {
                
        $no=1;
        $html = '';
        $SINCodeData = DB::SELECT("SELECT * FROM sample_indent_fabric WHERE sample_indent_code='".$request->sample_indent_code."'");
        $SINCodeMasterData = DB::SELECT("SELECT * FROM sample_indent_master WHERE sample_indent_code='".$request->sample_indent_code."'");
        $mainstyle_id = isset($SINCodeMasterData[0]->mainstyle_id) ? $SINCodeMasterData[0]->mainstyle_id : 0;
        $substyle_id = isset($SINCodeMasterData[0]->substyle_id) ? $SINCodeMasterData[0]->substyle_id : 0;
        $style_description = isset($SINCodeMasterData[0]->style_description) ? $SINCodeMasterData[0]->style_description : '';
        
        foreach($SINCodeData as $rows)
        {
            $ItemList = ItemModel::where('item_code','=', $rows->fabric_item_code)->where('item_master.delflag','=', '0')->get();
   
            $stockData=DB::select("SELECT sum(qty) as actual_qty  FROM stock_association_for_fabric WHERE bom_code='".$request->sample_indent_code."' AND item_code=".$rows->fabric_item_code." AND tr_type = 1");
            $stockData1=DB::select("SELECT sum(meter) as total_meter FROM fabric_outward_details WHERE sample_indent_code = '".$request->sample_indent_code."' AND item_code=".$rows->fabric_item_code);
            //DB::enableQueryLog();
            // $data=DB::select("SELECT item_master.item_name,sta.po_code,sta.po_date, sta.bom_code,sta.sales_order_no,sta.item_code, sum(qty) as fabric_qty
            //     FROM stock_association_for_fabric as sta INNER JOIN item_master ON item_master.item_code = sta.item_code 
            //     WHERE sta.item_code='".$rows->fabric_item_code."'  GROUP BY sta.bom_code,sta.item_code,sta.sales_order_no");
           
            // // dd(DB::getQueryLog());
            // $totalAssoc = 0;
            // foreach ($data as $row) 
            // {
                
            //     $allocatedStockData = DB::SELECT("SELECT sum(qty) as allocated_qty FROM stock_association_for_fabric WHERE item_code='".$row->item_code."' AND tr_type=1");
                
            //     $allocated_qty = isset($allocatedStockData[0]->allocated_qty) ? $allocatedStockData[0]->allocated_qty : 0;
                
            //     $fabricOutwardData = DB::SELECT("SELECT ifnull(sum(fabric_outward_details.meter),0) as outward_qty FROM fabric_outward_details
            //                         INNER JOIN vendor_purchase_order_master ON vendor_purchase_order_master.vpo_code = fabric_outward_details.vpo_code 
            //                         INNER JOIN fabric_checking_details ON fabric_checking_details.track_code = fabric_outward_details.track_code 
            //                         WHERE  fabric_outward_details.item_code='".$row->item_code."' GROUP BY fabric_outward_details.item_code"); 
               
            //     $fabricOutwardStock = isset($fabricOutwardData[0]->outward_qty) ? $fabricOutwardData[0]->outward_qty : 0;
             
            //     $eachData = DB::SELECT("SELECT ifnull(sum(qty),0) as each_qty FROM stock_association_for_fabric WHERE item_code='".$row->item_code."'
            //                         AND tr_type = 2  AND tr_code IS NULL"); 
            //     $eachAvaliableQty = isset($eachData[0]->each_qty) ? $eachData[0]->each_qty : 0;
            
            //     $remainStock = $allocated_qty - $eachAvaliableQty;
                
            //     $totalAssoc += $allocated_qty;
                                
            //     // $assoc_qty += ($remainStock - $fabricOutwardStock);
            
            // }
            
            $allocated_qty = isset($stockData[0]->actual_qty) ? $stockData[0]->actual_qty : 0;
            $allocated_qty1 = isset($stockData1[0]->total_meter) ? $stockData1[0]->total_meter : 0;
            
            $currentDate = date("Y-m-d");
    
            $FabricInwardDetails = DB::select("SELECT dump_fabric_stock_data.*, 
                                (SELECT sum(grn_qty) 
                                 FROM dump_fabric_stock_data AS df 
                                 WHERE df.item_code = ? AND df.in_date = dump_fabric_stock_data.in_date 
                                 AND df.in_date <= ?) as gq
                             FROM dump_fabric_stock_data 
                             LEFT JOIN fabric_checking_details 
                             ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                             WHERE dump_fabric_stock_data.in_date <= ? 
                             AND dump_fabric_stock_data.item_code = ?", [
                                 $rows->fabric_item_code, 
                                 $currentDate, 
                                 $currentDate, 
                                 $rows->fabric_item_code
                             ]);
        
            $data = [];
            $total = 0;
        
            foreach($FabricInwardDetails as $row) { 
                $grn_qty = isset($row->gq) ? $row->gq : 0; 
                $ind_outward1 = explode(",", $row->ind_outward_qty);
                $q_qty = 0; 
        
                foreach ($ind_outward1 as $indu) {
                    $ind_outward2 = explode("=>", $indu);
                    $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                    
                    if ($ind_outward2[0] <= $currentDate) {
                        $q_qty += $q_qty1;
                    } 
                }
        
                $stocks = ($row->qc_qty > 0) ? $row->qc_qty - $q_qty : $grn_qty - $q_qty;
        
                if ($stocks > 0) {
                    $key = $row->suplier_name . '_' . $row->po_no;
                    if (!isset($data[$key])) {
                        $data[$key] = [
                            'suplier_name' => $row->suplier_name,
                            'po_no' => $row->po_no,
                            'stocks' => 0,
                        ];
                    }
                    $total += round($stocks);
                }
            }
            
            $html .='<tr>
                        <td>'.$no.'</td> 
                        <td>'.$rows->fabric_item_code.'</td>  
                        <td>'.$ItemList[0]->item_name.'</td> 
                        <td class="assoc_qty">'.(abs($allocated_qty - $allocated_qty1)).'</td> 
                        <td class="order_qty">'.(abs($rows->fabric_qty - $allocated_qty1)).'</td>
                        <td class="actual_stock_qty" onclick="stockPopup(this,'.$rows->fabric_item_code.');">'.$total.'</td> 
                    </tr>';
            $no=$no+1;
        }
        
        return response()->json(['html' => $html, 'mainstyle_id'=>$mainstyle_id,'substyle_id'=>$substyle_id,'style_description'=>$style_description]);
        
    }
   

    public function GetStockAssociationData(Request $request)
    {
                
        $no=1;
        $html = '';
        // $vendorData = DB::SELECT("SELECT vendor_purchase_order_master.sales_order_no, sum(stock_association_for_fabric.qty) as assoc_qty FROM vendor_purchase_order_master 
        //                 LEFT JOIN stock_association_for_fabric ON stock_association_for_fabric.sales_order_no = vendor_purchase_order_master.sales_order_no 
        //                 INNER JOIN item_master ON item_master.item_code = stock_association_for_fabric.item_code
        //                 WHERE vpo_code='".$request->vpo_code."' GROUP BY stock_association_for_fabric.item_code");
        
        // $vendorData = DB::SELECT("SELECT vendor_purchase_order_detail.*,item_master.item_name,item_master.item_name FROM vendor_purchase_order_detail  
        //             INNER JOIN item_master ON item_master.item_code = vendor_purchase_order_detail.item_code
        //             WHERE vendor_purchase_order_detail.vpo_code='".$request->vpo_code."' GROUP BY vendor_purchase_order_detail.item_code");
                        
          
        $vendorData = DB::SELECT("SELECT vendor_purchase_order_detail.sales_order_no,
                                vendor_purchase_order_detail.item_code,
                                item_master.item_name
                            FROM 
                                vendor_purchase_order_detail
                            INNER JOIN 
                                item_master 
                            ON 
                                item_master.item_code = vendor_purchase_order_detail.item_code
                            WHERE 
                                vendor_purchase_order_detail.vpo_code = '".$request->vpo_code."'
                            GROUP BY 
                                vendor_purchase_order_detail.item_code, 
                                item_master.item_name
                            
                            UNION
                             
                            SELECT 
                                 vendor_purchase_order_trim_fabric_details.sales_order_no, 
                                 vendor_purchase_order_trim_fabric_details.item_code,
                                item_master.item_name
                            FROM 
                                vendor_purchase_order_trim_fabric_details
                            INNER JOIN 
                                item_master 
                            ON 
                                item_master.item_code = vendor_purchase_order_trim_fabric_details.item_code
                            WHERE 
                                vendor_purchase_order_trim_fabric_details.vpo_code = '".$request->vpo_code."'");
                                         
        foreach($vendorData as $row)
        {
            $OrderData = DB::SELECT("SELECT sum(bom_qty) as order_qty FROM vendor_purchase_order_fabric_details WHERE vpo_code='".$request->vpo_code."' AND  sales_order_no='".$row->sales_order_no."' AND item_code='".$row->item_code."'");
            
            $OrderData1 = DB::SELECT("SELECT sum(bom_qty) as order_qty FROM vendor_purchase_order_trim_fabric_details WHERE vpo_code='".$request->vpo_code."' AND  sales_order_no='".$row->sales_order_no."' AND item_code='".$row->item_code."'");
            
            $avaliableData=DB::select("SELECT ((SELECT sum(qty) FROM stock_association_for_fabric WHERE  sales_order_no='".$row->sales_order_no."' AND  item_code='".$row->item_code."' AND tr_type=1) 
                                    -  (SELECT ifnull(sum(qty),0) FROM stock_association_for_fabric WHERE  sales_order_no='".$row->sales_order_no."' AND  item_code='".$row->item_code."' AND tr_type=2)) as avaliable_qty");
              
            
            $available_qty  = $avaliableData[0]->avaliable_qty; 
            
            $order_qty = $OrderData[0]->order_qty ? $OrderData[0]->order_qty : 0;
            $order_qty1 = $OrderData1[0]->order_qty ? $OrderData1[0]->order_qty : 0;
            if($order_qty > 0)
            {
                $order_qty2 = $order_qty;
            }
            else if($order_qty1 > 0)
            {
                $order_qty2 = $order_qty1;
            }
            else
            {
                $order_qty2 = 0;
            }
            
            $html .='<tr>
                        <td>'.$no.'</td> 
                        <td>'.$row->item_code.'</td>  
                        <td>'.$row->item_name.'</td> 
                        <td class="assoc_qty">'.($available_qty).'</td> 
                        <td class="order_qty">'.($order_qty2).'</td>
                        <td class="actual_stock_qty" onclick="stockPopup(this);">0</td> 
                    </tr>';
            $no=$no+1;
        }
        
        return response()->json(['html' => $html]);
        
    }  
     
    public function GetStockDetailPopupForFabric(Request $request)
    {
        $currentDate = date("Y-m-d");
    
        // Precompute grn_qty for each item_code and in_date in a subquery
        $FabricInwardDetails = DB::select("
            SELECT 
                dump_fabric_stock_data.suplier_name,
                dump_fabric_stock_data.po_no,
                dump_fabric_stock_data.qc_qty,
                dump_fabric_stock_data.ind_outward_qty,
                COALESCE(grn_data.total_grn_qty, 0) AS grn_qty
            FROM dump_fabric_stock_data
            LEFT JOIN fabric_checking_details 
                ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
            LEFT JOIN (
                SELECT item_code, in_date, SUM(grn_qty) AS total_grn_qty
                FROM dump_fabric_stock_data
                WHERE in_date <= ?
                GROUP BY item_code, in_date
            ) AS grn_data
                ON grn_data.item_code = dump_fabric_stock_data.item_code
                AND grn_data.in_date = dump_fabric_stock_data.in_date
            WHERE dump_fabric_stock_data.in_date <= ? 
            AND dump_fabric_stock_data.item_code = ?
        ", [$currentDate, $currentDate, $request->item_code]);
    
        $data = [];
        $total = 0;
    
        foreach ($FabricInwardDetails as $row) {
            $grn_qty = $row->grn_qty ?? 0;
            $q_qty = 0;
    
            // Parse ind_outward_qty entries and sum quantities if date is <= currentDate
            if ($row->ind_outward_qty) {
                $ind_outward_entries = explode(",", $row->ind_outward_qty);
                foreach ($ind_outward_entries as $entry) {
                    $ind_outward_parts = explode("=>", $entry);
                    $date = $ind_outward_parts[0] ?? null;
                    $quantity = $ind_outward_parts[1] ?? 0;
    
                    if ($date <= $currentDate) {
                        $q_qty += $quantity;
                    }
                }
            }
    
            // Calculate available stock based on QC or GRN quantity
            $stocks = ($row->qc_qty > 0) ? $row->qc_qty - $q_qty : $grn_qty - $q_qty;
    
            if ($stocks > 0) {
                $key = $row->suplier_name . '_' . $row->po_no;
                if (!isset($data[$key])) {
                    $data[$key] = [
                        'suplier_name' => $row->suplier_name,
                        'po_no' => $row->po_no,
                        'stocks' => 0,
                    ];
                }
                $data[$key]['stocks'] += $stocks;
                $total += round($stocks);
            }
        }
    
        // Prepare HTML output
        $html = array_reduce($data, function ($carry, $row) {
            return $carry . '<tr>
                                <td>' . htmlspecialchars($row['suplier_name']) . '</td>
                                <td>' . htmlspecialchars($row['po_no']) . '</td> 
                                <td style="text-align:end;">' . number_format($row['stocks'], 2) . '</td> 
                             </tr>';
        }, '');
    
        $html1 = '<tr>
                    <th></th>
                    <th>Total</th> 
                    <th style="text-align:end;">' . number_format($total, 2) . '</th> 
                  </tr>';
    
        return response()->json(['html' => $html, 'html1' => $html1]);
    }


}
