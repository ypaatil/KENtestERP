<?php

namespace App\Http\Controllers;
use App\Models\FabricTransactionModel;
use App\Models\FabricCheckingModel;
use App\Models\FabricCheckingDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use  App\Models\FabricInwardModel;
use App\Models\FabricDefectModel;
use App\Models\POTypeModel;
use App\Models\PurchaseOrderModel;
use App\Models\ItemModel;
use App\Models\ShadeModel;
use App\Models\RackModel;
use App\Models\PartModel;
use App\Models\DefectModel;
use App\Models\CounterNumberModel;
use App\Models\FabricCheckStatusModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FabricCheckingReportController;
use Session;
use DataTables;
use App\Services\FabricCheckingDetailActivityLog;
use App\Services\FabricCheckingMasterActivityLog;

date_default_timezone_set("Asia/Kolkata");

class FabricCheckingController extends Controller
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
        ->where('form_id', '37')
        ->first();  
    
         
        $FabricCheckingList = DB::table('fabric_checking_master')
            ->leftJoin('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
            ->leftJoin('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->leftJoin('inward_master', 'inward_master.in_code', '=', 'fabric_checking_master.in_code')
            ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
            ->leftJoin('fabric_checking_details', 'fabric_checking_master.chk_code', '=', 'fabric_checking_details.chk_code')
            ->leftJoin('fabric_outward_details', 'fabric_checking_details.track_code', '=', 'fabric_outward_details.track_code')
            ->leftJoin('fabric_outward_master', 'fabric_outward_master.fout_code', '=', 'fabric_outward_details.fout_code')
            ->where('fabric_checking_master.delflag', 0)
            ->where('fabric_checking_master.chk_date', '>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))
            ->groupBy('fabric_checking_master.chk_code')
            ->select('fabric_checking_master.*', 
                     'usermaster.username', 
                     'ledger_master.Ac_name', 
                     'cp_master.cp_name', 
                     'inward_master.po_code',
                     DB::raw('COUNT(fabric_outward_details.track_code) as total_count'))
            ->orderByRaw('CAST(SUBSTRING(fabric_checking_master.chk_code, 5) AS SIGNED) DESC')
            ->get();


      
          return view('FabricCheckingMasterList', compact('FabricCheckingList','chekform'));
    }

    public function FabricCheckingShowAll()
    { 
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '37')
        ->first();  
    
         
       $FabricCheckingList = FabricCheckingModel::leftjoin('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
          ->leftjoin('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
          ->leftjoin('inward_master', 'inward_master.in_code', '=', 'fabric_checking_master.in_code')
          ->leftjoin('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
          ->where('fabric_checking_master.delflag','=', '0')
          ->orderByRaw('CAST(SUBSTRING(fabric_checking_master.chk_code, 5) AS SIGNED) DESC')
          ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name','inward_master.po_code']);
  
        return view('FabricCheckingMasterList', compact('FabricCheckingList','chekform'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CHECKING'");
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
        $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $POList1 = PurchaseOrderModel::select('pur_code')->where('purchase_order.po_type_id','=', '1');
        $POList2=FabricInwardModel::select('po_code as pur_code');
        
        $POList=$POList1->union($POList2)->get();
        
        $CPList =  DB::table('cp_master')->get();
        $DefectList =  FabricDefectModel::where('fabric_defect_master.delflag', '=', '0')->get();
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        $excludedCodes = DB::table('fabric_checking_master')->pluck('in_code');
        $GRNList = FabricInwardModel::select('in_code')
        ->where('delflag', '0')
        ->whereNotIn('in_code', $excludedCodes)
        ->get();

        $BillToList =  DB::table('ledger_details')->get();
        
        return view('FabricCheckingMaster',compact('Ledger','FGList','GRNList','DefectList','POList','RackList',  'CPList', 'ShadeList','counter_number', 'ItemList', 'PartList','FabCheckList','POTypeList','BOMLIST','CPList','BillToList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
      $data='';       
             
               $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','CHECKING')
  ->where('firm_id','=',1)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;   
             
$is_opening=isset($request->is_opening) ? 1 : 0;

                $data1=array(

                    'chk_code'=>$TrNo, 'chk_date'=>$request->chk_date,'in_code'=>$request->in_code,'cp_id'=>$request->cp_id, 
                    'Ac_code'=>$request->Ac_code,'po_code'=>$request->po_code,'invoice_date'=>$request->invoice_date, 'invoice_no'=>$request->invoice_no,
                    'po_type_id'=>$request->po_type_id,  
                    'total_meter'=>$request->total_meter,
                    'total_taga_qty'=>$request->total_taga_qty,
                    'bill_to'=>$request->bill_to,
                    'total_kg'=>$request->total_kg,
                    'in_narration'=>$request->in_narration,  'c_code' => $codefetch->c_code,
                    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','is_opening' =>$is_opening,
                    
                );

                FabricCheckingModel::insert($data1);

 DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='CHECKING'");


                $item_code = $request->input('item_code');
                if(count($item_code)>0)
                { 
                    
                for($x=0; $x<count($item_code); $x++) 
                {
                    # code...
                    
                                $data2=array(
                                'chk_code' =>$TrNo,
                                'chk_date' => $request->chk_date,
                                'cp_id' =>$request->cp_id,
                                'Ac_code' =>$request->Ac_code,
                                'po_code'=>$request->po_code,
                                'item_code' => $request->item_code[$x],
                                'part_id' =>$request->part_id[$x],
                                'roll_no' => $request->roll_no[$x],
                                'old_meter' => $request->old_meter[$x],
                                'meter' => $request->meter[$x],
                                'width' => $request->width[$x],
                                'kg' => $request->kg[$x],
                                'shade_id' => $request->shade_id[$x],
                                'status_id' => $request->fcs_id[$x],
                                'defect_id' => $request->defect_id[$x],
                                'reject_short_meter' => $request->reject_short_meter[$x],
                                'short_meter' => $request->short_meter[$x],
                                'extra_meter' => $request->extra_meter[$x],
                                'shrinkage' => $request->shrinkage[$x],
                                'track_code' => $request->track_code[$x],
                                'item_rate' => $request->item_rate[$x],
                                'rack_id'=>$request->rack_id[$x],
                                'usedflag' => '0',
                            
                                );
                          
                            
                            $fabricCheckingData = DB::table('fabric_check_status_master')->select('fcs_name')->where('fcs_id',  $request->fcs_id[$x])->first();
                               
                            $fcs_name = isset($fabricCheckingData->fcs_name) ? $fabricCheckingData->fcs_name : '';
                             
                         
//************* Barcode *********************************


$ItemDetails=ItemModel::select('item_name', 'color_name', 'item_description')->where('item_code','=',$request->item_code[$x] )->first();
    $Shade=ShadeModel::where('shade_id','=',$request->shade_id[$x] )->first();
    // DB::enableQueryLog();
    $Status=FabricCheckStatusModel::where('fcs_id','=',$request->fcs_id[$x] )->first();
    // dd(DB::getQueryLog());
    $Parts=PartModel::where('part_id','=',$request->part_id[$x] )->first(); 



$data=$data.'SIZE 59.10 mm, 60.1 mm
DIRECTION 0,0
REFERENCE 0,0
OFFSET 0 mm
SET PEEL OFF
SET CUTTER OFF
SET PARTIAL_CUTTER OFF
SET TEAR ON
CLS
CODEPAGE 1252
TEXT 453,465,"ROMAN.TTF",180,1,8,"FABRIC QUALITY"
TEXT 453,430,"ROMAN.TTF",180,1,8,"PO NO"
TEXT 453,397,"ROMAN.TTF",180,1,8,"ITEM CODE"
TEXT 453,357,"ROMAN.TTF",180,1,8,"FABRIC CODE"
TEXT 453,315,"ROMAN.TTF",180,1,10,"ROLL NO "
TEXT 453,275,"ROMAN.TTF",180,1,10,"SHADE"
TEXT 453,235,"ROMAN.TTF",180,1,10,"WIDTH"
TEXT 453,195,"ROMAN.TTF",180,1,10,"REMARK"
TEXT 453,148,"ROMAN.TTF",180,1,12,"QTY MTR"
BARCODE 358,102,"128M",49,0,180,3,6,"'.$request->track_code[$x].'"
TEXT 275,46,"ROMAN.TTF",180,1,10,"'.$request->track_code[$x].'"
BAR 462,8, 3, 459
BAR 9,8, 454, 3
BAR 14,4, 3, 463
BAR 12,466, 454, 3
TEXT 236,465,"ROMAN.TTF",180,1,8,"'.$ItemDetails->item_description.'"
TEXT 236,430,"ROMAN.TTF",180,1,8,"'.$request->po_code.'"
TEXT 236,397,"ROMAN.TTF",180,1,8,"'.$request->item_code[$x].'"
TEXT 236,357,"ROMAN.TTF",180,1,8,"'.$ItemDetails->item_name.'"
TEXT 236,315,"ROMAN.TTF",180,1,10, "'.$request->roll_no[$x].'"
TEXT 236,275,"ROMAN.TTF",180,1,10,"'.$Shade->shade_name.'"
TEXT 236,235,"ROMAN.TTF",180,1,10,"'.$request->width[$x].'"
TEXT 236,195,"ROMAN.TTF",180,1,10,"'.$Status->fcs_name.'"
TEXT 236,148,"ROMAN.TTF",180,1,12,"'.$request->meter[$x].'"
PRINT 1,1
';        
//*************End Barcode List *****************************
                                
                                
                                $short_meter=$request->old_meter[$x]-$request->reject_short_meter[$x]-$request->meter[$x];
                                $data3=array(
                                   'tr_code' =>$TrNo,
                                    'tr_date' => $request->chk_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'job_code'=>0, 
                                    'po_code'=>$request->po_code,
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>0,
                                    'style_no' =>0,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>$request->shade_id[$x],
                                    'track_code' => $request->track_code[$x],
                                    'old_meter'=>$request->old_meter[$x],
                                    'short_meter'=>$short_meter,
                                    'rejected_meter'=>$request->reject_short_meter[$x],
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '2',
                                    'rack_id'=>$request->rack_id[$x],
                                    'userId'=>$request->userId,
                                );
                                
                           
                         
                       FabricCheckingDetailModel::insert($data2);
                       FabricTransactionModel::insert($data3);
         
                    
                    $fabricChecking = DB::table('fabric_checking_details')->select('meter','width')->where('track_code',  $request->track_code[$x])->first();
                    $totalFabricMeter =  $request->meter[$x] + $request->reject_short_meter[$x];
                    DB::table('dump_fabric_stock_data') 
                        ->where('track_name', '=', $request->track_code[$x])
                        ->update(['fcs_name' => $fcs_name, 'qc_qty' => $totalFabricMeter, 'width' => $request->width[$x]]);
                        
                    DB::select("update inward_details set usedflag=1 where track_code='".$request->track_code[$x]."'");
                }  
                        
                        
                
                }

         /* -------------------------------- Generate Barcode for Each Roll ---------- */
             
                   
                    // $start= "<xpml><page quantity='0' pitch='40.0 mm'></xpml>"; 
                   // $end="<xpml></page></xpml><xpml><end/></xpml>";
                      $start= ""; 
                        $end="";
                    $data=$start.$data.$end;
                     
            	    $dir="barcode";
                    $pagename = 'data';
                    $newFileName = $dir."/".$pagename.".prn";
                    $newFileContent = $data;
                    if(file_put_contents($newFileName, $newFileContent) !== false) 
                    {
                       // echo "File created (" . basename($newFileName) . ")";
                        $result= array('result' => 'success');
                    } 
                    else
                    {
                        //echo "Cannot create file (" . basename($newFileName) . ")";
                         $result= array('result' => 'failed');
                    } 
              
        /* -------------------------------- End Generate Barcode for Each Roll Twice---------- */   
                
        $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$TrNo)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
        
         return redirect()->route('FabricChecking.index'); 
    }





  public function FabricCheckPrint($chk_code)
    {
        
         
         $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$TrNo)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
         return view('rptFabricChecking', compact('fabricChekingMaster'));
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricCheckingModel $fabricCheckingModel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
        $FabricCheckingMasterList = FabricCheckingModel::find($id);
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $GRNList = FabricInwardModel::select('in_code')->where('inward_master.delflag','=', '0')->get(); 
        $CPList =  DB::table('cp_master')->get();
        $DefectList =  FabricDefectModel::where('fabric_defect_master.delflag', '=', '0')->get();
        $POList1 = PurchaseOrderModel::select('pur_code')->where('purchase_order.po_type_id','=', '1');
        $PONo=FabricInwardModel::select('po_code as pur_code')->where('in_code', $FabricCheckingMasterList->in_code)->get();
        $POList2=FabricInwardModel::select('po_code as pur_code')->where('in_code', $FabricCheckingMasterList->in_code);
        
       // DB::select("select distinct po_code as pur_code from inward_master where in_code='".$FabricCheckingMasterList->in_code."'");
        
        $POList=$POList1->union($POList2)->get();
        
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        $FabricCheckingDetails = FabricCheckingDetailModel::join('item_master', 'item_master.item_code', '=', 'fabric_checking_details.item_code')->where('fabric_checking_details.chk_code','=', $FabricCheckingMasterList->chk_code)->get(['fabric_checking_details.*','item_master.item_name']);
  
      
        if(strpos($FabricCheckingMasterList->po_code, "PO/") !== false)
        {
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.pur_code='".$FabricCheckingMasterList->po_code."'");
        } 
        else 
        {     
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.Ac_code='".$FabricCheckingMasterList->Ac_code."'");
        }
        
        return view('FabricCheckingMasterEdit',compact('FabricCheckingMasterList','PONo','GRNList','DefectList','RackList','POList','Ledger','CPList','FGList', 'ShadeList', 'PartList','ItemList',  'FabricCheckingDetails',
            'FabCheckList','POTypeList','BOMLIST','CPList','BillToList'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,FabricCheckingDetailActivityLog $loggerDetail,FabricCheckingMasterActivityLog $loggerMaster)
    {
        $data='';   
        $is_opening=isset($request->is_opening) ? 1 : 0;
        $data1=array(
            
             'chk_code'=>$request->chk_code, 'chk_date'=>$request->chk_date,'in_code'=>$request->in_code,'cp_id'=>$request->cp_id, 
            'Ac_code'=>$request->Ac_code,'po_code'=>$request->po_code,'invoice_date'=>$request->invoice_date, 'invoice_no'=>$request->invoice_no,
            'po_type_id'=>$request->po_type_id, 'total_meter'=>$request->total_meter,
            'total_taga_qty'=>$request->total_taga_qty,'is_opening' =>$is_opening,
             'bill_to'=>$request->bill_to,
            'total_kg'=>$request->total_kg,'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
            'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','updated_at'=>date('Y-m-d H:i:s'),
        );
 
 
 
             $MasterOldFetch = DB::table('fabric_checking_master')
            ->select('chk_date', 'in_code','total_meter','total_taga_qty','is_opening','total_kg','in_narration')  
            ->where('chk_code',$request->chk_code)
            ->first();
        
             $MasterOld = (array) $MasterOldFetch;
        
        
                  $MasterNew=[
             'chk_date'=>$request->chk_date,
             'in_code'=>$request->in_code,
            'total_meter'=>$request->total_meter,
            'total_taga_qty'=>$request->total_taga_qty,
            'is_opening' =>$is_opening,
            'total_kg'=>$request->total_kg,
            'in_narration'=>$request->in_narration
            ];

          
               try {
            $loggerMaster->logIfChangedFabricCheckingMaster(
            'fabric_checking_master',
            $request->chk_code,
            $MasterOld,
            $MasterNew,
            'UPDATE',
            $request->chk_date,
            'fabric_checking_master'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for fabric_checking_master.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'chk_code' =>  $request->chk_code,
            'data' => $MasterNew
            ]);
            }  
 
 
 
 
 
 
        $FabricCheckingMasterList = FabricCheckingModel::findOrFail($request->input('chk_code'));  
        $FabricCheckingMasterList->fill($data1)->save();
        
        
        
             $olddata1 = DB::table('fabric_checking_details')
            ->select('track_code','roll_no','old_meter','meter','width','kg','shade_id','status_id','defect_id','reject_short_meter','short_meter','extra_meter','shrinkage','rack_id')  
            ->where('chk_code',$request->input('chk_code'))
            ->get()
            ->map(function ($item) {
            return (array) $item;
            })
            ->toArray();
            
            $combinedOldData = $olddata1;
        
        
        
          
        DB::table('fabric_checking_details')->where('chk_code', $request->input('chk_code'))->delete();
        DB::table('fabric_transaction')->where('tr_code', $request->input('chk_code'))->delete();
        // DB::enableQueryLog();
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->first();  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      
         $item_code = $request->input('item_code');
         echo count($item_code);
                if(count($item_code)>0)
                { 
                    
                    $newDataDetail2=[];
                    
                for($x=0; $x<count($item_code); $x++) 
                {
                    $data2=array(
                        'chk_code' =>$request->chk_code,
                        'chk_date' => $request->chk_date,
                        'cp_id' =>$request->cp_id,
                        'Ac_code' =>$request->Ac_code,
                        'po_code'=>$request->po_code,
                        'item_code' => $request->item_code[$x],
                        'part_id' =>$request->part_id[$x],
                        'roll_no' => $request->roll_no[$x],
                        'old_meter' => $request->old_meter[$x],
                        'meter' => $request->meter[$x],
                        'width' => $request->width[$x],
                        'kg' => $request->kg[$x],
                        'shade_id' => $request->shade_id[$x],
                        'status_id' => $request->fcs_id[$x],
                        'defect_id' => $request->defect_id[$x],
                        'reject_short_meter' => $request->reject_short_meter[$x],
                        'short_meter' => $request->short_meter[$x],
                        'extra_meter' => $request->extra_meter[$x],
                        'shrinkage' => $request->shrinkage[$x],
                        'track_code' => $request->track_code[$x],
                        'item_rate' => $request->item_rate[$x],
                        'rack_id'=>$request->rack_id[$x],
                        'usedflag' => '0',
                      );
                        
                
                    $fabricCheckingData = DB::table('fabric_check_status_master')->select('fcs_name')->where('fcs_id',  $request->fcs_id[$x])->first();
                       
                    $fcs_name = isset($fabricCheckingData->fcs_name) ? $fabricCheckingData->fcs_name : '';
                    
                   
                        
     //************* Barcode *********************************


// $ItemList=ItemModel::where('item_code','=',$request->item_code[$x])->first(); 
// $ShadeList=ShadeModel::where('shade_id','=',$request->shade_id[$x])->first(); 
// $StatusList = FabricCheckStatusModel::where('fcs_id','=',$request->fcs_id[$x])->first(); 
// $Ledger = LedgerModel::where('ledger_master.Ac_code','=', $request->Ac_code )->first();   

$ItemDetails=ItemModel::select('item_name', 'color_name', 'item_description')->where('item_code','=',$request->item_code[$x] )->first();
    $Shade=ShadeModel::where('shade_id','=',$request->shade_id[$x] )->first();
    // DB::enableQueryLog();
    $Status=FabricCheckStatusModel::where('fcs_id','=',$request->fcs_id[$x] )->first();
    // dd(DB::getQueryLog());
    $Parts=PartModel::where('part_id','=',$request->part_id[$x] )->first(); 



$data=$data.'SIZE 59.10 mm, 60.1 mm
DIRECTION 0,0
REFERENCE 0,0
OFFSET 0 mm
SET PEEL OFF
SET CUTTER OFF
SET PARTIAL_CUTTER OFF
SET TEAR ON
CLS
CODEPAGE 1252
TEXT 453,465,"ROMAN.TTF",180,1,8,"FABRIC QUALITY"
TEXT 453,430,"ROMAN.TTF",180,1,8,"PO NO"
TEXT 453,397,"ROMAN.TTF",180,1,8,"ITEM CODE"
TEXT 453,357,"ROMAN.TTF",180,1,8,"FABRIC CODE"
TEXT 453,315,"ROMAN.TTF",180,1,10,"ROLL NO "
TEXT 453,275,"ROMAN.TTF",180,1,10,"SHADE"
TEXT 453,235,"ROMAN.TTF",180,1,10,"WIDTH"
TEXT 453,195,"ROMAN.TTF",180,1,10,"REMARK"
TEXT 453,148,"ROMAN.TTF",180,1,12,"QTY MTR"
BARCODE 358,102,"128M",49,0,180,3,6,"'.$request->track_code[$x].'"
TEXT 275,46,"ROMAN.TTF",180,1,10,"'.$request->track_code[$x].'"
BAR 462,8, 3, 459
BAR 9,8, 454, 3
BAR 14,4, 3, 463
BAR 12,466, 454, 3
TEXT 236,465,"ROMAN.TTF",180,1,8,"'.$ItemDetails->item_description.'"
TEXT 236,430,"ROMAN.TTF",180,1,8,"'.$request->po_code.'"
TEXT 236,397,"ROMAN.TTF",180,1,8,"'.$request->item_code[$x].'"
TEXT 236,357,"ROMAN.TTF",180,1,8,"'.$ItemDetails->item_name.'"
TEXT 236,315,"ROMAN.TTF",180,1,10, "'.$request->roll_no[$x].'"
TEXT 236,275,"ROMAN.TTF",180,1,10,"'.$Shade->shade_name.'"
TEXT 236,235,"ROMAN.TTF",180,1,10,"'.$request->width[$x].'"
TEXT 236,195,"ROMAN.TTF",180,1,10,"'.$Status->fcs_name.'"
TEXT 236,148,"ROMAN.TTF",180,1,12,"'.$request->meter[$x].'"
PRINT 1,1
';   
 
// $data=$data.'SIZE 101.6 mm, 76.2 mm
// GAP 3 mm, 0 mm
// DIRECTION 0,0
// REFERENCE 0,0
// OFFSET 0 mm
// SET PEEL OFF
// SET CUTTER OFF
// SET PARTIAL_CUTTER OFF
// SET TEAR ON
// CLS
// CODEPAGE 1252
// TEXT 758,573,"ROMAN.TTF",180,1,12,"Supplier:"
// TEXT 590,573,"ROMAN.TTF",180,1,12,"'.$Ledger->ac_name.'"
// TEXT 758,510,"ROMAN.TTF",180,1,12,"PO No:"
// TEXT 590,510,"ROMAN.TTF",180,1,12,"'.$request->po_code.'"
// TEXT 758,458,"ROMAN.TTF",180,1,12,"Fabric Color/Code:"
// TEXT 469,458,"ROMAN.TTF",180,1,12,"'.$ItemList->item_name.'"
// TEXT 758,407,"ROMAN.TTF",180,1,12,"GRN No:"
// TEXT 590,407,"ROMAN.TTF",180,1,12,"'.$request->in_code.'"
// TEXT 758,352,"ROMAN.TTF",180,1,12,"Item Code:"
// TEXT 590,352,"ROMAN.TTF",180,1,12,"'.$request->item_code[$x].'"
// TEXT 360,352,"ROMAN.TTF",180,1,12,"Width:"
// TEXT 203,352,"ROMAN.TTF",180,1,12,"'.$request->width[$x].'"
// TEXT 758,301,"ROMAN.TTF",180,1,12,"Shade:"
// TEXT 590,301,"ROMAN.TTF",180,1,12,"'.$ShadeList->shade_name.'"
// TEXT 758,245,"ROMAN.TTF",180,1,12,"Roll No:"
// TEXT 360,245,"ROMAN.TTF",180,1,12,"Roll Mtr:"
// TEXT 203,245,"ROMAN.TTF",180,1,12,"'.$request->meter[$x].' Mtr"
// TEXT 760,189,"ROMAN.TTF",180,1,12,"Remark:"
// TEXT 590,189,"ROMAN.TTF",180,1,12,"'.$StatusList->fcs_name.'"
// TEXT 590,245,"ROMAN.TTF",180,1,12,"'.$request->track_code[$x].'"
// TEXT 360,301,"ROMAN.TTF",180,1,12,"Lot No:"
// TEXT 203,301,"ROMAN.TTF",180,1,12," "
// BARCODE 561,130,"39",55,0,180,3,8,"'.$request->track_code[$x].'"
// TEXT 449,70,"ROMAN.TTF",180,1,12,"'.$request->track_code[$x].'"
// PRINT 1,1';      
//*************End Barcode List *****************************                   
                        
                               $short_meter=$request->old_meter[$x]-$request->reject_short_meter[$x]-$request->meter[$x];
                                  
                               $data3=array(
                                   'tr_code' =>$request->chk_code,
                                    'tr_date' => $request->chk_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'job_code'=>0, 
                                    'po_code'=>$request->po_code,
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>0,
                                    'style_no' =>0,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>$request->shade_id[$x],
                                    'track_code' => $request->track_code[$x],
                                    'old_meter'=>$request->old_meter[$x],
                                    'short_meter'=>$short_meter,
                                    'rejected_meter'=>$request->reject_short_meter[$x],
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '2',
                                    'rack_id'=>$request->rack_id[$x],
                                    'userId'=>$request->userId,
                                );
                                
            
                    FabricCheckingDetailModel::insert($data2);
                    FabricTransactionModel::insert($data3);
                    
                    $fabricChecking = DB::table('fabric_checking_details')->select('meter','width')->where('track_code',  $request->track_code[$x])->first();
                    
                    $totalFabricMeter =  $request->meter[$x] + $request->reject_short_meter[$x];
                    
                    DB::table('dump_fabric_stock_data') 
                        ->where('track_name', '=', $request->track_code[$x])
                        ->update(['fcs_name' => $fcs_name, 'qc_qty' => $totalFabricMeter, 'width' => $request->width[$x]]);
                         
                    DB::select("update inward_details set usedflag=1 where track_code='".$request->track_code[$x]."'");
                    
                    
                        $newDataDetail2[]=[
                        'track_code' => $request->track_code[$x],           
                        'roll_no' => $request->roll_no[$x],
                        'old_meter' => $request->old_meter[$x],
                        'meter' => $request->meter[$x],
                        'width' => $request->width[$x],
                        'kg' => $request->kg[$x],
                        'shade_id' => $request->shade_id[$x],
                        'status_id' => $request->fcs_id[$x],
                        'defect_id' => $request->defect_id[$x],
                        'reject_short_meter' => $request->reject_short_meter[$x],
                        'short_meter' => $request->short_meter[$x],
                        'extra_meter' => $request->extra_meter[$x],
                        'shrinkage' => $request->shrinkage[$x],
                        'rack_id'=>$request->rack_id[$x]];     
                    
                    
                }
                
                
                
                
                  }
                  
                  
            $combinedNewData = $newDataDetail2;       
           
            try {
            $loggerDetail->logIfChangedFabricCheckingDetail(
            'fabric_checking_details',
            $request->input('chk_code'),
            $combinedOldData,
            $combinedNewData,
            'UPDATE',
            $request->chk_date,
            'fabric_checking_details'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for fabric_checking_details.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'chk_code' => $request->input('chk_code'),
            'data' => $combinedNewData
            ]);
            }     
                  
                  
                  
                  
     /* -------------------------------- Generate Barcode for Each Roll ---------- */
             
                   
                   // $start= "<xpml><page quantity='0' pitch='40.0 mm'></xpml>"; 
                   // $end="<xpml></page></xpml><xpml><end/></xpml>";
                      $start= ""; 
                    $end="";
                    
                    $data=$start.$data.$end;
                     
            	    $dir="barcode";
                    $pagename = 'data';
                    $newFileName = $dir."/".$pagename.".prn";
                    $newFileContent = $data;
                    if(file_put_contents($newFileName, $newFileContent) !== false) 
                    {
                       // echo "File created (" . basename($newFileName) . ")";
                        $result= array('result' => 'success');
                    } 
                    else
                    {
                        //echo "Cannot create file (" . basename($newFileName) . ")";
                         $result= array('result' => 'failed');
                    } 
              
        /* -------------------------------- End Generate Barcode for Each Roll Twice---------- */                 
                  
                  
                  
                  
                  
                  $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$request->chk_code)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
        
        return redirect()->route('FabricChecking.index');
             
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $InCode =  FabricCheckingModel::select('in_code')->where('fabric_checking_master.chk_code','=', $id)->first();
        DB::select("update inward_details set usedflag=0 where in_code='".$InCode->in_code."'");
        
        DB::table('fabric_checking_master')->where('chk_code', $id)->delete();
        $checkingData =  DB::table('fabric_checking_details')->select('track_code')->where('chk_code', $id)->get();
        
        foreach($checkingData as $row)
        {
             DB::table('dump_fabric_stock_data') 
                        ->where('track_name', '=', $row->track_code)
                        ->update(['fcs_name' => '', 'qc_qty' => 0, 'width' => 0]);
        }
        
        DB::table('fabric_checking_details')->where('chk_code', $id)->delete();
        $detail =FabricTransactionModel::where('tr_code',$id)->delete();
            
      Session::flash('delete', 'Deleted record successfully'); 
        
    }


    

    public function getMasterdata(Request $request)
    { 
        $in_code= $request->input('in_code');
        
        $MasterdataList = DB::select("SELECT `in_code`, `in_date`, `invoice_no`,`invoice_date`, cp_id,  `Ac_code`, `po_code`,  `po_type_id`,  
        `total_meter`, `total_kg`, `total_taga_qty`, `in_narration` from inward_master  where in_code='".$in_code."'");
        return json_encode($MasterdataList);
    
    }
 
 
    public function getDetails(Request $request)
    { 
    $in_code= $request->input('in_code');
    $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
    $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
    $FGList =  FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();
    $PartList =  PartModel::where('part_master.delflag', '=', '0')->get();
    $ShadeList =  ShadeModel::where('shade_master.delflag', '=', '0')->get();
    $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
    $CPList =  DB::table('cp_master')->get();
    $DefectList =  FabricDefectModel::where('fabric_defect_master.delflag', '=', '0')->get();
    $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
    $InwardFabric = DB::select("SELECT inward_master.`in_code`, inward_master.`in_date`,
    inward_details.`item_code`, inward_details.`roll_no`,  
    inward_details.`meter`,inward_details.part_id,inward_details.kg, inward_details.`track_code`,inward_details.`item_rate`, inward_details.`usedflag`, 
    inward_master.`total_meter`, inward_master.`total_taga_qty`
    FROM `inward_details` 
    inner join inward_master on inward_master.in_code=inward_details.in_code where inward_details.usedflag=0 and inward_master.in_code='". $in_code."'");
    $html ='';
    $html .= '<input type="number" value="'.count($InwardFabric).'" name="cntrr" id="cntrr" readonly="" hidden="true"  />';
    $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
                    <thead>
                    <tr>
                    <th>Sr No</th>
                    <th>Item Name</th>
                    <th>Part</th>
                    <th>Supplier Roll No</th>
                    <th>GRN Meter</th>
                    <th>QC Meter</th>
                    <th>Width</th>
                    <th>Shade</th>
                    <th>Status</th>
                    <th>Defect</th>
                    <th>Rejected</th>
                    <th>Short Meter</th>
                    <th>Extra Meter</th>
                    <th>Shrinkage</th>
                    <th>TrackCode</th>
                    <th>Rack Location</th>
                    <th>Remove</th>
                    </tr>
                    </thead>
                    <tbody>';
                    $no=1;
                    foreach ($InwardFabric as $row) {
                        $html .='<tr>';
                        
                    $html .='
                    <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
                     
                    <td> <select name="item_code[]"  id="item_code" style="width:200px; height:30px;" required disabled>
                    <option value="">--Item--</option>';
                    
                    foreach($ItemList as  $row1)
                    {
                        $html.='<option value="'.$row1->item_code.'"';
                    
                        $row1->item_code == $row->item_code ? $html.='selected="selected"' : ''; 
                    
                    
                        $html.='>'.$row1->item_name.'</option>';
                    }
                     
                    $html.='</select></td> 
                    
                    <td> <select name="part_id[]"  id="part_id" style="width:200px;height:30px;" required disabled>
                    <option value="">--Part--</option>';
                    foreach($PartList as  $rowfg)
                    {
                        $html.='<option value="'.$rowfg->part_id.'"';
                    
                        $rowfg->part_id == $row->part_id ? $html.='selected="selected"' : ''; 
                        
                        $html.='>'.$rowfg->part_name.'</option>';
                    }
                     
                    $html.='</select></td>
                    <td><input type="text"   name="roll_no[]"   value="'.$row->roll_no.'" id="roll_no" style="width:80px;height:30px;" required/></td>
                    <td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();"   value="1"  id="taga_qty1" style="width:50px;height:30px;"/>
                    <input type="text" readOnly name="old_meter[]" onchange="mycalc();"   value="'.$row->meter.'" id="old_meter1" style="width:80px;height:30px;" required/></td>
                    <td><input type="text" class="METER" name="meter[]" onchange="mycalc();"   value="'.$row->meter.'" id="meter1" style="width:80px;height:30px;" required/></td>
                    <td><input type="text"  name="width[]"  value="0" id="width" style="width:80px;height:30px;" required/>
                     <input type="hidden"   class="KG" name="kg[]" onkeyup="mycalc();" value="'.$row->kg.'" id="kg" style="width:80px;height:30px;" required/> 
                    </td>
                    <td> <select name="shade_id[]"  id="shade_id" class="select2" style="width:100px;height:30px;" required>
                    <option value="">--Shade--</option>';
                    foreach($ShadeList as  $rowfg)
                    {
                        $html.='<option value="'.$rowfg->shade_id.'"';
                        if($rowfg->shade_id==1){  $html.='selected="selected"';}
                        $html.='>'.$rowfg->shade_name.'</option>';
                    }
                     
                    $html.='</select></td> 
                    
                    <td> <select name="fcs_id[]"  id="fcs_id" class="select2" style="width:100px;height:30px;" required>
                    <option value="">--Status--</option>';
                    foreach($FabCheckList as  $rowfg)
                    {
                        $html.='<option value="'.$rowfg->fcs_id.'"';
                         if($rowfg->fcs_id==1){  $html.='selected="selected"';}
                        $html.='>'.$rowfg->fcs_name.'</option>';
                    }
                    $html.='</select></td> 
                    <td> <select name="defect_id[]"  id="defect_id" class="select2" style="width:100px;height:30px;" required>
                    <option value="0">--Defect--</option>';
                    foreach($DefectList as  $rowdef)
                    {
                        $html.='<option value="'.$rowdef->fdef_id.'"';
                        
                        $html.='>'.$rowdef->fabricdefect_name.'</option>';
                    }
                    $html.='</select></td>
                    <td><input type="text"  name="reject_short_meter[]" onchange="mycalc();"   value="0" id="reject_short_meter" style="width:80px;height:30px;" required/></td>
                    <td><input type="text"  name="short_meter[]" onchange="mycalc();"   value="0" id="short_meter" style="width:80px;height:30px;" /></td>
                    <td><input type="text"  name="extra_meter[]" onchange="mycalc();"   value="0" id="extra_meter" style="width:80px;height:30px;" required/></td>
                    <td><input type="number" step="any" name="shrinkage[]"  value="0"  style="width:80px;height:30px;"  />
                    <td><input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;height:30px;" readOnly/>
                    <input type="hidden" name="item_rate[]"  value="'.$row->item_rate.'" id="item_rate" style="width:80px;height:30px;" readOnly/>
                    </td>
                    
                    <td> <select name="rack_id[]"  id="rack_id" class="select2" style="width:100px;height:30px;"  >
                    <option value="0">--Fabric Status--</option>';
                     foreach($RackList as  $row)
                    {
                        $html.='<option value="'.$row->rack_id.'"';
                        $html.='>'.$row->rack_name.'</option>';
                    }
                    $html.='</select></td> 
                    
                    <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
                    ';
                      
                $html .='</tr>';
                $no=$no+1;
                }
                
                $html .='</tbody>
                </table>';
            
                if(count($InwardFabric)!=0)
                {
                      return response()->json(['html' => $html]);
                }
    }
     
    
    public function FabricCheckingDashboard(Request $request)
    {
       
     if ($request->ajax()) {
                
               // $cpki_codes=explode(",",$CPKIList->cpki_code);
              //  DB::enableQueryLog();  
            //DB::enableQueryLog();
            $FabricCheckData = DB::select("SELECT fabric_checking_details.chk_date,fabric_checking_details.chk_code,fabric_checking_details.track_code, fabric_checking_details.chk_date as chkdate,ledger_master.Ac_name, 
            fabric_checking_details.`item_code`, fabric_checking_details.`item_code` as itemcode, item_master.item_name, item_master.color_name,ifnull(fabric_checking_details.shrinkage,0) as shrinkage,fabric_checking_details.status_id,
            item_master.item_description, sum(`meter`) as totalPassMeter, 
            ROUND(sum(`reject_short_meter`),2) as totalRejectMeter,fabric_checking_master.in_code,inward_master.po_code,
            
            (SELECT trade_name FROM ledger_details WHERE ledger_details.sr_no = fabric_checking_master.bill_to OR ledger_details.ac_code = fabric_checking_master.Ac_code LIMIT 1) as trade_name,
            (SELECT site_code FROM ledger_details WHERE ledger_details.sr_no = fabric_checking_master.bill_to OR ledger_details.ac_code = fabric_checking_master.Ac_code LIMIT 1) as site_code,
                   
            (select sum(chk.old_meter) from fabric_checking_details as chk where chk.chk_code=fabric_checking_details.chk_code AND chk.track_code=fabric_checking_details.track_code AND chk.item_code=fabric_checking_details.item_code) as total_Meter,
            
            (select sum(chk.meter) from fabric_checking_details as chk where chk.chk_code=fabric_checking_details.chk_code AND chk.track_code=fabric_checking_details.track_code AND chk.item_code=fabric_checking_details.item_code) as total_pass_meter
            
             
            FROM `fabric_checking_details` 
            inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code 
            inner join ledger_master on ledger_master.Ac_code=fabric_checking_details.Ac_code 
            inner join item_master on item_master.item_code=fabric_checking_details.item_code
            inner join inward_master on inward_master.in_code=fabric_checking_master.in_code  
            WHERE fabric_checking_master.delflag=0
            group by fabric_checking_details.track_code,  fabric_checking_details.item_code");
            //dd(DB::getQueryLog());
            //   $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);
            
            return Datatables::of($FabricCheckData)
            ->addIndexColumn()
             
          ->addColumn('totalPassPer',function ($row) {
              
              if($row->totalPassMeter > 0 && $row->total_pass_meter > 0)
              { 
                $totalPassPer =round((($row->totalPassMeter/$row->total_pass_meter)*100),2);
              }
              else
              {
                  $totalPassPer = 0;
              }
             return $totalPassPer;
           })
           
          ->addColumn('status',function ($row) {
              
              if($row->status_id==1)
              { 
                  $status = 'Passed';
              }
              else if($row->status_id==2)
              {
                  $status = 'Rejected';
              }
              else if($row->status_id==3)
              {
                  $status = 'Second Passed';
              }
              else
              {
                  $status == '';
              }
              
             return $status;
           })
            ->addColumn('totalRejectPer',function ($row) {
                if($row->totalRejectMeter > 0 && $row->total_Meter > 0)
                { 
                    $totalRejectPer =round((($row->totalRejectMeter/$row->total_Meter)*100),2);
                }
                else
                {
                    $totalRejectPer = 0;
                }
             return $totalRejectPer;
           })
            ->addColumn('totalPassRejectPer',function ($row) 
            { 
                $totalPassRejectPer = $row->totalPassMeter + $row->totalRejectMeter;
                return $totalPassRejectPer;
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
           
           
            ->rawColumns(['totalPassPer','totalRejectPer','totalPassRejectPer','status','bill_to'])
            ->make(true);
    
            }
            
          return view('FabricCheckingDashboard');
        
    }
     
    
    
    
  public function FabricCheckingRejectDashboard(Request $request)
    {
       
     if ($request->ajax()) {
                
               // $cpki_codes=explode(",",$CPKIList->cpki_code);
              //  DB::enableQueryLog();  
            //DB::enableQueryLog();
            $FabricCheckData = DB::select("SELECT in_code, fabric_checking_master.`chk_date`, 
            fabric_checking_master.`chk_date` as chkdate,ledger_master.Ac_name,   
            fabric_checking_master.`po_code`,  count(track_code) as totalRolls,
            fabric_checking_details.`item_code`, fabric_checking_details.`item_code` as itemcode,
            item_master.item_name, item_master.color_name,
            item_master.item_description, sum(`old_meter`) as totalMeter ,
            sum(`reject_short_meter`) as totalRejectMeter  , fabric_defect_master.fabricdefect_name as defect_name,
            (SELECT trade_name FROM ledger_details WHERE ledger_details.sr_no = fabric_checking_master.bill_to OR ledger_details.ac_code = fabric_checking_master.Ac_code LIMIT 1) as trade_name,
            (SELECT site_code FROM ledger_details WHERE ledger_details.sr_no = fabric_checking_master.bill_to OR ledger_details.ac_code = fabric_checking_master.Ac_code LIMIT 1) as site_code
            FROM `fabric_checking_details`  
            inner join fabric_checking_master on fabric_checking_master.chk_code=fabric_checking_details.chk_code
            inner join ledger_master on ledger_master.Ac_code=fabric_checking_details.Ac_code 
            inner join item_master on item_master.item_code=fabric_checking_details.item_code
            inner join fabric_defect_master on fabric_defect_master.fdef_id=fabric_checking_details.defect_id
            where 1
            group by  `chk_date`, in_code, item_code,  defect_id,track_code");
            //dd(DB::getQueryLog());
            //   $query = DB::getQueryLog();
            //   $query = end($query);
            //   dd($query);
            
            return Datatables::of($FabricCheckData)
            ->addIndexColumn() 
            ->addColumn('bill_name',function ($row) 
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

            ->rawColumns(['bill_name'])
            ->make(true);
    
            }
            
          return view('FabricCheckingRejectDashboard');
        
    }  
    
    
    public function PrintFabricBarcode(Request $request)
{
    $data='';
   
    $ItemDetails=ItemModel::select('item_name', 'color_name', 'item_description')->where('item_code','=',$request->item_code )->first();
    $Shade=ShadeModel::where('shade_id','=',$request->shade_id )->first();
    // DB::enableQueryLog();
    $Status=FabricCheckStatusModel::where('fcs_id','=',$request->fcs_id )->first();
    // dd(DB::getQueryLog());
    $Parts=PartModel::where('part_id','=',$request->part_id )->first(); 
    
    $part_name=$Parts->part_name;
    $start=''; $end='';
    
    
$start= "<xpml><page quantity='0' pitch='40.0 mm'></xpml>"; 
$end="<xpml></page></xpml><xpml><end/></xpml>";
   
   
$data=$data.'SIZE 59.10 mm, 60.1 mm
DIRECTION 0,0
REFERENCE 0,0
OFFSET 0 mm
SET PEEL OFF
SET CUTTER OFF
SET PARTIAL_CUTTER OFF
SET TEAR ON
CLS
CODEPAGE 1252
TEXT 453,465,"ROMAN.TTF",180,1,8,"FABRIC QUALITY"
TEXT 453,430,"ROMAN.TTF",180,1,8,"PO NO"
TEXT 453,397,"ROMAN.TTF",180,1,8,"ITEM CODE"
TEXT 453,357,"ROMAN.TTF",180,1,8,"FABRIC CODE"
TEXT 453,315,"ROMAN.TTF",180,1,10,"ROLL NO "
TEXT 453,275,"ROMAN.TTF",180,1,10,"SHADE"
TEXT 453,235,"ROMAN.TTF",180,1,10,"WIDTH"
TEXT 453,195,"ROMAN.TTF",180,1,10,"REMARK"
TEXT 453,148,"ROMAN.TTF",180,1,12,"QTY MTR"
BARCODE 358,102,"128M",49,0,180,3,6,"'.$request->track_code.'"
TEXT 275,46,"ROMAN.TTF",180,1,10,"'.$request->track_code.'"
BAR 462,8, 3, 459
BAR 9,8, 454, 3
BAR 14,4, 3, 463
BAR 12,466, 454, 3
TEXT 236,465,"ROMAN.TTF",180,1,8,"'.$ItemDetails->item_description.'"
TEXT 236,430,"ROMAN.TTF",180,1,8,"'.$request->po_code.'"
TEXT 236,397,"ROMAN.TTF",180,1,8,"'.$request->item_code.'"
TEXT 236,357,"ROMAN.TTF",180,1,8,"'.$ItemDetails->item_name.'"
TEXT 236,315,"ROMAN.TTF",180,1,10, "'.$request->roll_no.'"
TEXT 236,275,"ROMAN.TTF",180,1,10,"'.$Shade->shade_name.'"
TEXT 236,235,"ROMAN.TTF",180,1,10,"'.$request->width.'"
TEXT 236,195,"ROMAN.TTF",180,1,10,"'.$Status->fcs_name.'"
TEXT 236,148,"ROMAN.TTF",180,1,12,"'.$request->meter.'"
PRINT 1,1
';   
    
    	 $data=$start.$data.$end;
    	            
                    					 
                    $dir="barcode";
                    $pagename = 'data2';
                    $newFileName = $dir."/".$pagename.".prn";
                    $newFileContent = $data;
                    if (file_put_contents($newFileName, $newFileContent) !== false) {
                       // echo "File created (" . basename($newFileName) . ")";
                        $result= array('result' => 'success');
                    } else {
                        //echo "Cannot create file (" . basename($newFileName) . ")";
                         $result= array('result' => 'failed');
                    }
                    
                    
                    // $printJob = Printing::newPrintTask()
                    // ->printer($printerId)
                    // ->file($newFileName)
                    // ->send();
                    
                    
                    return json_encode($result);
                    
                    
}


    



}



 