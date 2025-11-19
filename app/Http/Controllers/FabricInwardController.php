<?php

namespace App\Http\Controllers;
use App\Jobs\SyncFabricDataJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\FabricTransactionModel;
use App\Models\FabricInwardModel;
use App\Models\FabricInwardDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\PurchaseOrderModel; 
use App\Models\ColorModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\ItemModel;
use App\Models\RackModel;
use App\Models\LocationModel;
use App\Models\CounterNumberModel;
use Image;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Printing;
use Session;
use App\Models\POTypeModel;
use DataTables;
use Illuminate\Support\Facades\Artisan;
use DateTime;
use App\Services\FabricInwardDetailActivityLog;
use App\Services\FabricInwardMasterActivityLog;
use Log;


date_default_timezone_set("Asia/Kolkata");

setlocale(LC_MONETARY, 'en_IN'); 

class FabricInwardController extends Controller
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
                ->where('form_id', '36')
                ->first(); 
         
        $cutoffDate = now()->subMonths(3)->endOfMonth();

        $FabricInwardList = FabricInwardModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
            ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'inward_master.buyer_id')
            ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
            ->leftJoin('inward_details', 'inward_details.in_code', '=', 'inward_master.in_code')
            ->leftJoin('ledger_details', 'ledger_details.sr_no', '=', 'inward_master.bill_to')
            ->leftJoin('fabric_checking_details', 'fabric_checking_details.track_code', '=', 'inward_details.track_code')
            ->leftJoin('fabric_checking_master', function ($join) {
                $join->on('fabric_checking_master.chk_code', '=', 'fabric_checking_details.chk_code')
                     ->where('fabric_checking_master.delflag', '=', 0);
            }) 
            ->where('inward_master.delflag', '0')
            ->where('inward_master.in_date', '>', $cutoffDate)
            ->groupBy(
                'inward_master.sr_no',
                'inward_master.in_code',
                'inward_master.in_date',
                'inward_master.invoice_no',
                'usermaster.username',
                'ledger_master.ac_short_name',
                'cp_master.cp_name',
                'LM1.ac_short_name'
            )
            ->get([
                'inward_master.*',
                'inward_master.in_code',
                'inward_master.in_date',
                'inward_master.invoice_no',
                'usermaster.username',
                'ledger_master.ac_short_name',
                'cp_master.cp_name',
                'LM1.ac_short_name as buyer',
                DB::raw('COUNT(fabric_checking_details.track_code) as total_count'),
                'ledger_details.trade_name', 'ledger_details.site_code'
            ]);


  
         return view('FabricInwardMasterList', compact('FabricInwardList','chekform'));
    }


    public function FabricInwardShowAll()
    { 

        $chekform = DB::table('form_auth')
                ->where('emp_id', Session::get('userId'))
                ->where('form_id', '36')
                ->first();  

        $FabricInwardList = FabricInwardModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
            ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'inward_master.buyer_id')
            ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
            ->leftJoin('inward_details', 'inward_details.in_code', '=', 'inward_master.in_code')
            ->leftJoin('fabric_checking_details', 'fabric_checking_details.track_code', '=', 'inward_details.track_code')
            ->leftJoin('ledger_details', 'ledger_details.sr_no', '=', 'inward_master.bill_to')
            ->leftJoin('fabric_checking_master', function ($join) {
                $join->on('fabric_checking_master.chk_code', '=', 'fabric_checking_details.chk_code')
                     ->where('fabric_checking_master.delflag', '=', 0);
            })
            ->where('inward_master.delflag', '0') 
            ->groupBy(
                'inward_master.sr_no',
                'inward_master.in_code',
                'inward_master.in_date',
                'inward_master.invoice_no',
                'usermaster.username',
                'ledger_master.ac_short_name',
                'cp_master.cp_name',
                'LM1.ac_short_name'
            )
            ->get([
                'inward_master.*',
                'inward_master.in_code',
                'inward_master.in_date',
                'inward_master.invoice_no',
                'usermaster.username',
                'ledger_master.ac_short_name',
                'cp_master.cp_name',
                'LM1.ac_short_name as buyer',
                DB::raw('COUNT(fabric_checking_details.track_code) as total_count'),
                'ledger_details.trade_name', 'ledger_details.site_code'
            ]);

  
         return view('FabricInwardMasterList', compact('FabricInwardList','chekform'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no', 
        
        (select  max(CONVERT(SUBSTRING_INDEX(track_code,'P',-1),UNSIGNED INTEGER))  FROM `inward_details` WHERE `track_code` like 'P%' ) as   PBarcode,
        (select  max(CONVERT(SUBSTRING_INDEX(track_code,'I',-1),UNSIGNED INTEGER))  FROM `inward_details` WHERE `track_code` like 'I%') as   CBarcode
        from counter_number where c_name ='C1' AND type='FABRIC_INWARD'");
         
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $FGList =  DB::table('fg_master')->get();
        $POList = PurchaseOrderModel::where('purchase_order.bom_type','=', '1')->where('purchase_order.po_status','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code','sales_order_no')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $FGECodeList =  DB::table('fabric_gate_entry_master')->where('delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        
        $vendorProcessOrderList = DB::table('vendor_purchase_order_master')->select('vpo_code')->where('process_id','=',1)->get();
        $BillToList =  DB::table('ledger_details')->get();
        
        return view('FabricInwardMaster',compact('Ledger','RackMasterList','LocationList', 'POList', 'PartList','FGList','CPList', 'counter_number','ItemList','POTypeList','gstlist','BOMLIST','vendorProcessOrderList','FGECodeList','BillToList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_R($_POST);exit;
        $this->validate($request, [
             
                'in_code'=>'required',
                'in_date'=>'required',
                'cp_id'=>'required',
                'Ac_code'=>'required',
                'total_meter'=>'required',
                'total_kg'=>'required',
                'total_taga_qty'=>'required',
                'c_code'=>'required',
                 ]);
    
  
    $item_code = $request->item_code; 
   
    $sr_no = FabricInwardModel::max('sr_no');            
    $is_opening=isset($request->is_opening) ? 1 : 0;
                
    if($is_opening==1){$po_code='OSF'.($sr_no+1);}else{ $po_code= $request->input('po_code');}             
    
    $purchaseData = DB::table('purchase_order')->where('pur_code', $request->po_code)->first();
    
    $buyer_id = isset($purchaseData->buyer_id) ? $purchaseData->buyer_id : 50;                            
    $data1=array(

        'in_code'=>$request->in_code, 'in_date'=>$request->in_date,'invoice_date'=>$request->invoice_date,'cp_id'=>$request->cp_id, 
        'Ac_code'=>$request->Ac_code,'po_code'=>$po_code, 'invoice_no'=>$request->invoice_no,
        'po_type_id' =>$request->po_type_id, 'total_kg' => $request->total_kg, 'is_opening'=>$is_opening,'fge_code'=>$request->fge_code,
        'location_id'=>$request->location_id, 'bill_to'=>$request->bill_to,
        
        'isReturnFabricInward'=>$request->isReturnFabricInward,'vpo_code'=>$request->vpo_code,
        'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,'total_amount'=>$request->total_amount,
        'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
        'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1', 'buyer_id'=>$buyer_id
    );
    
    FabricInwardModel::insert($data1);
    
    DB::select("update inward_master set buyer_id='".$buyer_id."' where po_code ='".$request->po_code."'");
  
    if(count($item_code)>0)
    {
        
        $CBarcodes1 = isset($request->CBarcode) ? $request->CBarcode : 0;
        $PBarcodes1 = isset($request->PBarcode) ? $request->PBarcode : 0;
        
        DB::select("update counter_number set tr_no=tr_no + 1, PBarcode='".$PBarcodes1."', CBarcode='".$CBarcodes1."'   where c_name ='C1' AND type='FABRIC_INWARD'"); 
    
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->where('type','=','FABRIC_INWARD' )->first();  
       
        $PBarcodes= $track_code->PBarcode;
        $CBarcodes= $track_code->CBarcode;
        
        if(isset($request->item_code) && is_array($request->item_code))
        {
            for($x=0; $x<count($request->item_code); $x++) 
            { 
                if($request->cp_id==1)
                {
                         if($request->track_code[$x]==''){ $PBarcodeFinal='P'.++$PBarcodes; }else{$PBarcodeFinal=$request->track_code[$x];}
                         
                            $purchaseOrderData = DB::table('purchase_order')->where('pur_code', $po_code)->first();
            
                            $buyer_id = isset($purchaseOrderData->buyer_id) ? $purchaseOrderData->buyer_id : 0;

                            $data2=array(
                            'in_code' =>$request->in_code,
                            'in_date' => $request->in_date,
                            'po_code'=>$po_code,
                            'cp_id' =>$request->cp_id,
                            'Ac_code' =>$request->Ac_code,
                            'item_code'=>$request->item_code[$x], 
                            'part_id' =>$request->part_id[$x],
                            'roll_no' => $request->id[$x],
                            'meter' => $request->meter[$x],
                            'gram_per_meter' => $request->gram_per_meter[$x],
                            'kg' => $request->kg[$x],
                            'item_rate' => $request->item_rates[$x],
                            'amount' => $request->amounts[$x],
                            'shade_id' =>'1',
                            'suplier_roll_no' => $request->suplier_roll_no[$x],
                            'track_code' => $PBarcodeFinal,
                            'is_opening'=>$is_opening,
                            'isReturnFabricInward'=>$request->isReturnFabricInward,
                            'vw_code'=>$request->vw_code,
                            'location_id'=>$request->location_id,
                            'buyer_id'=>$buyer_id,
                            'usedflag' => '0',
                            );
                           
                           $data3=array('tr_code' =>$request->in_code,
                                'tr_date' => $request->in_date,
                                'Ac_code' =>$request->Ac_code,
                                'cp_id' =>$request->cp_id,
                                'po_code'=>$request->po_code,
                                'item_code'=>$request->item_code[$x], 
                                'part_id' =>$request->part_id[$x],
                                'shade_id' =>'1',
                                'track_code' => $PBarcodeFinal,
                                'old_meter'=>'0',
                                'short_meter'=>'0',
                                'rejected_meter'=>'0',
                                'meter' => $request->meter[$x],
                                'tr_type' => '1',
                                'rack_id' => 0,
                                'is_opening'=>$is_opening,
                                'userId'=>$request->userId);
                            
                            $buyerData = DB::table('purchaseorder_detail')
                                ->select('LM1.ac_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                                ->join('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                                ->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
                                ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                                ->where('purchaseorder_detail.pur_code', $request->po_code)
                                ->where('purchaseorder_detail.item_code', $request->item_code[$x])
                                ->groupBy('purchaseorder_detail.pur_code')
                                ->get();
                                
                            $itemData = DB::table('item_master')->join('quality_master','quality_master.quality_code','=','item_master.quality_code')->select('item_name', 'item_description','quality_master.quality_name')->where('item_code', $item_code)->first();
                            $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->Ac_code)->first();
                            
                            $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
                    
                            $suplierName = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                            $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
                            
                            if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") 
                            {
                                $job_status_id = 1;
                                $po_status = "Moving";
                            } 
                            else 
                            {
                                $job_status_id = 2;
                                $po_status = "Non Moving";
                            }
                            
                            if($is_opening == 1)
                            {
                                $job_status_id = 2;
                                $po_status = "Non Moving";
                            }
                
                            $item_name = isset($itemData->item_name) ? $itemData->item_name : "";
                            $item_description = isset($itemData->item_description) ? $itemData->item_description : "";
                            $quality_name = isset($itemData->quality_name) ? $itemData->quality_name : "";
                    
                            $in_date = $request->in_date;
                            $suplier_name = $suplierName;
                            $po_no = $request->po_code;
                            $grn_no = $request->in_code;
                            $invoice_no = $request->invoice_no;
                            $item_code = $request->item_code[$x];
                            $preview =  "";
                            $shade_no = 1;
                            $item_name = $item_name;
                            $quality_name =  str_replace('"', '', $quality_name);
                            $color = "";
                            $item_description =  str_replace('"', '', $item_description);
                            $po_status = $po_status;
                            $job_status_id = $job_status_id;
                            $track_name = $PBarcodeFinal;
                            $grn_qty = $request->meter[$x];
                            $rate = $request->item_rates[$x];
                            $rack_id = 0;
                            
                            DB::SELECT('INSERT INTO dump_fabric_stock_data(in_date,suplier_name,buyer_name,po_no,grn_no,invoice_no,item_code,preview,shade_no,item_name,quality_name,
                                    color,item_Description,po_status,job_status_id,track_name,grn_qty,rate,rack_name,tr_type)
                                    select "'.$in_date.'","'.$suplier_name.'","'.$buyerName.'","'.$po_no.'","'.$grn_no.'","'.$invoice_no.'","'.$item_code.'","'.$preview.'","'.$shade_no.'","'.addslashes($item_name).'",
                                    "'.$quality_name.'","'.$color.'","'.addslashes($item_description).'","'.$po_status.'","'.$job_status_id.'","'.$track_name.'","'.$grn_qty.'","'.$rate.'","'.$rack_id.'",1');
                             
                    }
                    else
                    {
        
                        if($request->track_code[$x]==''){ $CBarcodeFinal='I'.++$CBarcodes; }else{$CBarcodeFinal=$request->track_code[$x];}
                        
                        $purchaseOrderData = DB::table('purchase_order')->where('pur_code', $request->po_code)->first();
        
                        $buyer_id = isset($purchaseOrderData->buyer_id) ? $purchaseOrderData->buyer_id : 0;
                            
                        $data2=array(
                            'in_code' =>$request->in_code,
                            'in_date' => $request->in_date,
                            'po_code'=>$request->po_code,
                            'cp_id' =>$request->cp_id,
                            'Ac_code' =>$request->Ac_code,
                            'item_code'=>$request->item_code[$x], 
                            'part_id' =>$request->part_id[$x],
                            'roll_no' => $request->id[$x],
                            'meter' => $request->meter[$x],
                            'gram_per_meter' => $request->gram_per_meter[$x],
                            'kg' => $request->kg[$x],
                            'item_rate' => $request->item_rates[$x],
                            'amount' => $request->amounts[$x],
                            'shade_id' =>'1',
                            'suplier_roll_no' => $request->suplier_roll_no[$x],
                            'track_code' => $CBarcodeFinal,
                            'usedflag' => '0',
                            'is_opening'=>$is_opening,
                            'buyer_id'=>$buyer_id,
                            'isReturnFabricInward'=>$request->isReturnFabricInward,
                            'vw_code'=>$request->vw_code,
                            'location_id'=>$request->location_id,
                            'fge_code'=>$request->fge_code,
                            );
                            
                            
                           $data3=array('tr_code' =>$request->in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' =>$CBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                    'rack_id' => 0,
                                    'userId'=>$request->userId,
                                    'is_opening'=>$is_opening,
                                    'isReturnFabricInward'=>$request->isReturnFabricInward,
                                    'vw_code'=>$request->vw_code);
                            
                            
                              $buyerData = DB::table('purchaseorder_detail')
                                ->select('LM1.ac_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                                ->join('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                                ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                                ->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchaseorder_detail.buyer_id')
                                ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                                ->where('purchaseorder_detail.pur_code', $request->po_code)
                                ->where('purchaseorder_detail.item_code', $request->item_code[$x])
                                ->groupBy('purchaseorder_detail.pur_code')
                                ->get();
                                
                            $itemData = DB::table('item_master')->join('quality_master','quality_master.quality_code','=','item_master.quality_code')->select('item_name', 'item_description','quality_master.quality_name')->where('item_code', $item_code)->first();
                            $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->Ac_code)->first();
                            
                            $buyerName = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
                    
                            $suplierName = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                            $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
                            
                            if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") 
                            {
                                $job_status_id = 1;
                                $po_status = "Moving";
                            } 
                            else 
                            {
                                $job_status_id = 2;
                                $po_status = "Non Moving";
                            }
                
                            
                            if($is_opening == 1)
                            {
                                $job_status_id = 2;
                                $po_status = "Non Moving";
                            }
                
                            $item_name = isset($itemData->item_name) ? $itemData->item_name : "";
                            $item_description = isset($itemData->item_description) ? $itemData->item_description : "";
                            $quality_name = isset($itemData->quality_name) ? $itemData->quality_name : "";
                     
                            $in_date = $request->in_date;
                            $suplier_name = $suplierName;
                            $po_no = $request->po_code;
                            $grn_no = $request->in_code;
                            $invoice_no = $request->invoice_no;
                            $item_code = $request->item_code[$x];
                            $preview =  "";
                            $shade_no = 1;
                            $item_name = $item_name;
                            $quality_name = str_replace('"', '', $quality_name);
                            $color = "";
                            $item_description =  str_replace('"', '', $item_description);
                            $po_status = $po_status;
                            $job_status_id = $job_status_id;
                            $track_name = $CBarcodeFinal;
                            $grn_qty = $request->meter[$x];
                            $rate = $request->item_rates[$x];
                            $rack_id = 0;
                              
                            
                            DB::SELECT('INSERT INTO dump_fabric_stock_data(in_date,suplier_name,buyer_name,po_no,grn_no,invoice_no,item_code,preview,shade_no,item_name,quality_name,
                                    color,item_Description,po_status,job_status_id,track_name,grn_qty,rate,rack_name,tr_type)
                                    select "'.$in_date.'","'.$suplier_name.'","'.$buyerName.'","'.$po_no.'","'.$grn_no.'","'.$invoice_no.'","'.$item_code.'","'.$preview.'","'.$shade_no.'","'.addslashes($item_name).'",
                                    "'.$quality_name.'","'.$color.'","'.addslashes($item_description).'","'.$po_status.'","'.$job_status_id.'","'.$track_name.'","'.$grn_qty.'","'.$rate.'","'.$rack_id.'",1');
        
        
                    }
            FabricInwardDetailModel::insert($data2);
            FabricTransactionModel::insert($data3);
            }
            }
    
     
    
            $ledgerData = DB::SELECT("SELECT ac_short_name FROM ledger_master WHERE ac_code='".$buyer_id."'");
            $buyerName = isset($ledgerData[0]->ac_short_name) ? $ledgerData[0]->ac_short_name : '';
            DB::select("update dump_fabric_stock_data set buyer_name='".$buyerName."' where po_no ='".$request->po_code."'"); 
    
    }
 
   return redirect()->route('FabricInward.index')->with('message', ' Record Created Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricInwardModel $fabricInwardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        //$id=base64_decode($id);
       // echo $id; exit;
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no',
         (select  max(CONVERT(SUBSTRING_INDEX(track_code,'P',-1),UNSIGNED INTEGER))  FROM `inward_details` WHERE `track_code` like 'P%' ) as   PBarcode,
        (select  max(CONVERT(SUBSTRING_INDEX(track_code,'I',-1),UNSIGNED INTEGER))  FROM `inward_details` WHERE `track_code` like 'I%') as   CBarcode
      
        from counter_number where c_name ='C1' AND type='FABRIC_INWARD'");
        
        //   (SELECT max(substr(`track_code`,2,15))  FROM `inward_details` WHERE `track_code` like 'P%') as PBarcode,
        // (SELECT max(substr(`track_code`,2,15))  FROM `inward_details` WHERE `track_code` like 'I%') as CBarcode
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        //  DB::enableQueryLog();
        //DB::enableQueryLog();

        $FabricInwardMasterList = FabricInwardModel::where('sr_no',$id)->first();
        //dd(DB::getQueryLog());
        $FabricInwardDetails = FabricInwardDetailModel::join('item_master', 'item_master.item_code', '=', 'inward_details.item_code')->where('inward_details.in_code','=', $FabricInwardMasterList->in_code)->get(['inward_details.*','item_master.item_name']);
  
        $vendorProcessOrderList = DB::table('vendor_purchase_order_master')->select('vpo_code')->where('process_id','=',1)->get();
        $FGECodeList =  DB::table('fabric_gate_entry_master')->where('delflag','=', '0')->get();
  
        if(strpos($FabricInwardMasterList->po_code, "PO/") !== false)
        {
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order 
                            INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.pur_code='".$FabricInwardMasterList->po_code."'");
        } 
        else 
        {     
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order 
            INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.Ac_code='".$FabricInwardMasterList->Ac_code."'");
        }
        
        return view('FabricInwardMasterEdit',compact('FabricInwardMasterList','FGECodeList','POList','LocationList',  'RackMasterList', 'PartList', 'Ledger','CPList','FGList', 'FabricInwardDetails','counter_number','ItemList','POTypeList','gstlist','BOMLIST','vendorProcessOrderList','BillToList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,FabricInwardDetailActivityLog $loggerDetail,FabricInwardMasterActivityLog $loggerMaster)
    {
        $this->validate($request, [
             
            'in_code'=>'required',
            'in_date'=>'required',
            'cp_id'=>'required',
            'Ac_code'=>'required',
            'total_meter'=>'required',
            'total_kg'=>'required',
            'total_taga_qty'=>'required',
             
            'c_code'=>'required',
             ]);


         $is_opening=isset($request->is_opening) ? 1 : 0;

    
    
         if($is_opening==1){$po_code='OSF'.($id);}else{ $po_code= $request->input('po_code');} 
 
 
         $purchaseData = DB::table('purchase_order')->where('pur_code', $request->po_code)->first();
     
         $buyer_id = isset($purchaseData->buyer_id) ? $purchaseData->buyer_id : 50;    
    
         $in_code=$request->in_code;
 
         $data1=array(
            'in_code'=>$in_code, 'in_date'=>$request->in_date,'cp_id'=>$request->cp_id, 
            'Ac_code'=>$request->Ac_code,'po_code'=>$po_code, 'invoice_no'=>$request->invoice_no,'invoice_date'=>$request->invoice_date,
            'po_type_id' =>$request->po_type_id, 'total_kg' => $request->total_kg, 'total_amount'=>$request->total_amount,
            'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty, 'is_opening'=>$is_opening,'fge_code'=>$request->fge_code,
            'location_id'=>$request->location_id, 'isReturnFabricInward'=>$request->isReturnFabricInward,
            'vpo_code'=>$request->vpo_code, 'bill_to'=>$request->bill_to,
            'in_narration'=>$request->in_narration, 'c_code' => $request->c_code,
            'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','updated_at'=>date('Y-m-d H:i:s'),'buyer_id'=>$buyer_id,
        );
        
        
            
            $MasterOldFetch = DB::table('inward_master')
            ->select('invoice_no', 'invoice_date', 'in_date', 'location_id', 'total_kg', 'total_amount',
            'total_meter','total_taga_qty', 'is_opening', 'isReturnFabricInward', 'in_narration')  
            ->where('in_code',$in_code)
            ->first();
        
             $MasterOld = (array) $MasterOldFetch;
        
        
                  $MasterNew=[
        "invoice_no"=>$request->invoice_no,
        "invoice_date"=> $request->input('invoice_date'),
        'in_date'=>$request->input('in_date'),
        "location_id"=> $request->input('location_id'),
         'total_kg' => $request->total_kg,
         'total_amount'=>$request->total_amount,
        'total_meter'=>$request->total_meter,
        'total_taga_qty'=>$request->total_taga_qty, 
        'is_opening'=>$is_opening,
        'isReturnFabricInward'=>$request->isReturnFabricInward,
         'in_narration'=>$request->in_narration
        ];
        
          
               try {
            $loggerMaster->logIfChangedFabricInwardMaster(
            'inward_master',
            $in_code,
            $MasterOld,
            $MasterNew,
            'UPDATE',
            $request->in_date,
            'inward_master'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for sale_transaction_master.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'in_code' => $in_code,
            'data' => $MasterNew
            ]);
            }  




//print_r($data1);
// DB::enableQueryLog();

        $FabricInwardMasterList = FabricInwardModel::findOrFail($id);  
   
        $FabricInwardMasterList->fill($data1)->save();
        
        DB::select("update inward_master set buyer_id='".$buyer_id."' where po_code ='".$request->po_code."'"); 
        
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
                      
            $olddata1 = DB::table('inward_details')
            ->select('track_code','item_code','part_id','roll_no','meter','gram_per_meter','kg','item_rate','amount','shade_id','suplier_roll_no')  
            ->where('in_code',$in_code)
            ->get()
            ->map(function ($item) {
            return (array) $item;
            })
            ->toArray();
            
            $combinedOldData = $olddata1;
 
    

        DB::table('inward_details')->where('in_code', $in_code)->delete();
        DB::table('fabric_transaction')->where('tr_code', $in_code)->delete();
       
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->where('type','=','FABRIC_INWARD' )->first(); 
        $CBarcodes = $track_code->CBarcode;
        $PBarcodes = $track_code->PBarcode;

        $CBarcodes1 = isset($track_code->CBarcode) ? $track_code->CBarcode : 0;
        $PBarcodes1 = isset($track_code->PBarcode) ? $track_code->PBarcode : 0;
        
        

  
        $item_code = $request->input('item_code');
    if($item_code != "")
    {
        
        
        
          DB::select("update counter_number set tr_no=tr_no + 1, PBarcode='".$PBarcodes1."', CBarcode='".$CBarcodes1."'   where c_name ='C1' AND type='FABRIC_INWARD'"); 
        
        
            $newDataDetail2=[];
                // DB::table('dump_fabric_stock_data')->where('grn_no', $in_code)->where('po_no', $po_code)->delete();
                for($x=0; $x<count($request->item_code); $x++) 
                {
                    $buyerData = DB::table('purchaseorder_detail')
                                            ->select('LM1.ac_short_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                                            ->join('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                                            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                                            ->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
                                            ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                                            ->where('purchaseorder_detail.pur_code', $request->po_code)
                                            ->where('purchaseorder_detail.item_code', $request->item_code[$x])
                                            ->groupBy('purchaseorder_detail.pur_code')
                                            ->get();
                    $buyer_name = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";

                    if($request->cp_id==1)
                    {

                        if($request->track_code[$x]==''){ $PBarcodeFinal='P'.++$PBarcodes; }else{$PBarcodeFinal=$request->track_code[$x];}
                        
                        $purchaseOrderData = DB::table('purchase_order')->where('pur_code', $po_code)->first();
        
                        $buyer_id = isset($purchaseOrderData->buyer_id) ? $purchaseOrderData->buyer_id : 0;
                        
                        $data2=array(
                        'in_code' =>$in_code,
                        'in_date' => $request->in_date,
                        'po_code'=>$po_code,
                        'cp_id' =>$request->cp_id,
                        'Ac_code' =>$request->Ac_code,
                        'item_code'=>$request->item_code[$x], 
                        'part_id' =>$request->part_id[$x],
                        'roll_no' => $request->id[$x],
                        'meter' => $request->meter[$x],
                        'gram_per_meter' => $request->gram_per_meter[$x],
                        'kg' => $request->kg[$x],
                        'item_rate' => $request->item_rates[$x],
                        'amount' => $request->amounts[$x],
                        'shade_id' =>'1',
                        'suplier_roll_no' => $request->suplier_roll_no[$x],
                        'track_code' => $PBarcodeFinal,
                        'usedflag' => '0',
                        'is_opening'=>$is_opening,
                        'location_id'=>$request->location_id,
                        'buyer_id'=>$buyer_id,
                        'fge_code'=>$request->fge_code
                        );
                        
                           $data3=array(
                                    'tr_code' =>$in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' => $PBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                    'rack_id' =>0,
                                    'userId'=>$request->userId,
                                     
                                );
                        
                         
                            FabricInwardDetailModel::insert($data2);
                            FabricTransactionModel::insert($data3);
                            
                        $itemData = DB::table('item_master')->join('quality_master','quality_master.quality_code','=','item_master.quality_code')->select('item_name', 'item_description','quality_master.quality_name')->where('item_code', $item_code)->first();
                        $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->Ac_code)->first();
                        
                        
                
                        $suplierName = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                        $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
                        
                        if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") 
                        {
                            $job_status_id = 1;
                            $po_status = "Moving";
                        } 
                        else 
                        {
                            $job_status_id = 2;
                            $po_status = "Non Moving";
                        }
            
                            
                        if($is_opening == 1)
                        {
                            $job_status_id = 2;
                            $po_status = "Non Moving";
                        }
            
                        $item_name = isset($itemData->item_name) ? $itemData->item_name : "";
                        $item_description = isset($itemData->item_description) ? $itemData->item_description : "";
                        $quality_name = isset($itemData->quality_name) ? $itemData->quality_name : "";
                
                        $in_date = $request->in_date;
                        $suplier_name = $suplierName;
                        $po_no = $request->po_code;
                        $grn_no = $request->in_code;
                        $invoice_no = $request->invoice_no;
                        $item_code = $request->item_code[$x];
                        $preview =  "";
                        $shade_no = 1;
                        $item_name = $item_name;
                        $quality_name = str_replace('"', '', $quality_name);
                        $color = "";
                        $item_description = str_replace('"', '', $item_description);
                        $po_status = $po_status;
                        $job_status_id = $job_status_id;
                        $track_name = $PBarcodeFinal;
                        $grn_qty = $request->meter[$x];
                        $rate = $request->item_rates[$x];
                        $rack_id = 0;
                          
                        
                        // DB::SELECT('INSERT INTO dump_fabric_stock_data(in_date,suplier_name,po_no,grn_no,invoice_no,item_code,shade_no,item_name,quality_name,
                        //         color,item_Description,po_status,job_status_id,track_name,grn_qty,rate,rack_name,tr_type)
                        //         select "'.$in_date.'","'.$suplier_name.'","'.$po_no.'","'.$grn_no.'","'.$invoice_no.'","'.$item_code.'","'.$shade_no.'","'.addslashes($item_name).'",
                        //         "'.$quality_name.'","'.$color.'","'.addslashes($item_description).'","'.$po_status.'","'.$job_status_id.'","'.$track_name.'","'.$grn_qty.'","'.$rate.'","'.$rack_id.'",1');
                        // $buyerData = DB::table('purchaseorder_detail')
                        //             ->select('LM1.ac_short_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                        //             ->join('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                        //             ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                        //             ->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
                        //             ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                        //             ->where('purchaseorder_detail.pur_code', $request->po_code)
                        //             ->where('purchaseorder_detail.item_code', $request->item_code[$x])
                        //             ->groupBy('purchaseorder_detail.pur_code')
                        //             ->get();
                        $buyerData = DB::table('purchase_order')->select('LM1.ac_name as buyer_name')->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')->where('purchase_order.pur_code','=', $po_no)->get();
                                                
                        $buyer_name = isset($buyerData[0]->buyer_name) ? $buyerData[0]->buyer_name : "";
                        $grnData = DB::SELECT("SELECT * FROM dump_fabric_stock_data WHERE track_name = '".$track_name."' ");
                        
                        $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $track_name)->get();
                        $fabricChecking = DB::select("SELECT sum(meter) as qc from fabric_checking_details where track_code='".$track_name."'");
                        $qc_qty = isset($fabricChecking[0]->qc) ? $fabricChecking[0]->qc : 0;
                        $updated_string = '';
                        foreach($existingData as $outwards)
                        {
                            $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
                        }
                                    
                        DB::table('dump_fabric_stock_data')
                            ->where('track_name', '=', $track_name)
                            ->where('grn_no', '=', $grn_no)
                            ->update([
                                'in_date' => $in_date,
                                'suplier_name' => $suplier_name,
                                'buyer_name' => $buyer_name,
                                'po_no' => $po_no,  
                                'grn_no' => $grn_no,  
                                'invoice_no' => $invoice_no,  
                                'item_code' => $item_code,  
                                'shade_no' => $shade_no,  
                                'item_name' => addslashes($item_name),  
                                'quality_name' => str_replace('"', '', $quality_name),  
                                'color' => $color,  
                                'item_Description' => str_replace('"', '', $item_description),  
                                'po_status' => $po_status,  
                                'job_status_id' => $job_status_id,  
                                'track_name' => $track_name,  
                                'grn_qty' => $grn_qty, 
                                'rate' => $rate, 
                                'rack_name' => $rack_id,  
                                'qc_qty' => $qc_qty,  
                                'ind_outward_qty' => $updated_string,
                                'tr_type' => 1
                        ]);
                          
                              
                                if(count($grnData) > 0)
                                {
                                    
                                    
                         // DB::enableQueryLog();
                                    DB::table('dump_fabric_stock_data')
                                        ->where('in_date', '=', $in_date)
                                        ->where('track_name', '=', $track_name)
                                        ->where('grn_no', '=', $grn_no)
                                        ->update([
                                            'in_date' => $in_date,
                                            'suplier_name' => $suplier_name,
                                            'buyer_name' => $buyer_name,
                                            'po_no' => $po_no,  
                                            'grn_no' => $grn_no,  
                                            'invoice_no' => $request->invoice_no,  
                                            'item_code' => $item_code,  
                                            'shade_no' => $shade_no,  
                                            'item_name' => addslashes($item_name),  
                                            'quality_name' => $quality_name,  
                                            'color' => $color,  
                                            'item_Description' => addslashes($item_description),  
                                            'po_status' => $po_status,  
                                            'job_status_id' => $job_status_id,  
                                            'track_name' => $track_name,  
                                            'grn_qty' => $grn_qty, 
                                            'rate' => $rate, 
                                            'rack_name' => $rack_id,  
                                            'qc_qty' => $qc_qty,  
                                            'ind_outward_qty' => $updated_string,
                                            'tr_type' => 1
                                    ]);

                                    DB::delete("DELETE FROM dump_fabric_stock_data WHERE grn_no = ? AND track_name NOT IN (SELECT track_code FROM inward_details WHERE grn_no = ?)", [$grn_no, $grn_no]);
                                }
                                else
                                {
                                    $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $track_name)->get();
                                    $fabricChecking = DB::select("SELECT sum(meter) as qc, width  from fabric_checking_details where track_code='".$track_name."'");
                                    $qc_qty = isset($fabricChecking[0]->qc) ? $fabricChecking[0]->qc : 0;
                                    $width = isset($fabricChecking[0]->width) ? $fabricChecking[0]->width : 0;
                                    $updated_string = '';
                                    foreach($existingData as $outwards)
                                    {
                                        $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
                                    }
                                    
                                    //DB::enableQueryLog();
                                    DB::table('dump_fabric_stock_data')
                                    ->insert([
                                        'in_date' => $in_date,
                                        'suplier_name' => $suplier_name,
                                        'buyer_name' => $buyer_name,
                                        'po_no' => $po_no,
                                        'grn_no' => $grn_no,
                                        'invoice_no' => $invoice_no,
                                        'item_code' => $item_code,
                                        'shade_no' => $shade_no,
                                        'item_name' => $item_name,  // Assuming $item_name doesn't need addslashes
                                        'quality_name' => $quality_name,
                                        'color' => $color,
                                        'item_Description' => $item_description,  // Assuming $item_description doesn't need addslashes
                                        'po_status' => $po_status,
                                        'job_status_id' => $job_status_id,
                                        'track_name' => $track_name,
                                        'grn_qty' => $grn_qty,
                                        'rate' => $rate,
                                        'rack_name' => $rack_id,
                                        'width' => $width,
                                        'qc_qty' => $qc_qty,  
                                        'ind_outward_qty' => $updated_string,
                                        'tr_type' => 1
                                    ]);
                                   // dd(DB::getQueryLog());
                                }  
                         
                    }
                    else
                    {
                        if($request->track_code[$x]==''){ $CBarcodeFinal='I'.++$CBarcodes; }else{$CBarcodeFinal=$request->track_code[$x];}
                        
                        $purchaseOrderData = DB::table('purchase_order')->where('pur_code', $request->po_code)->first();
        
                        $buyer_id = isset($purchaseOrderData->buyer_id) ? $purchaseOrderData->buyer_id : 0;
                        
                        $data2=array(
                        
                            'in_code' =>$in_code,
                            'in_date' => $request->in_date,
                            'po_code'=>$request->po_code,
                            'cp_id' =>$request->cp_id,
                            'Ac_code' =>$request->Ac_code,
                            'item_code'=>$request->item_code[$x], 
                            'part_id' =>$request->part_id[$x],
                            'roll_no' => $request->id[$x],
                            'meter' => $request->meter[$x],
                            'gram_per_meter' => $request->gram_per_meter[$x],
                            'kg' => $request->kg[$x],
                            'item_rate' => $request->item_rates[$x],
                            'amount' => $request->amounts[$x],
                            'shade_id' =>'1',
                            'is_opening'=>$is_opening,
                            'location_id'=>$request->location_id,
                            'isReturnFabricInward'=>$request->isReturnFabricInward,
                            'vw_code'=> $request->vw_code,
                            'suplier_roll_no' => $request->suplier_roll_no[$x],
                            'track_code' => $CBarcodeFinal,
                            'usedflag' => '0',
                            'buyer_id' => $buyer_id,
                            'fge_code'=>$request->fge_code
                            );
                            
                               $data3=array(
                                    'tr_code' =>$in_code,
                                    'tr_date' => $request->in_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'po_code'=>$request->po_code,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>'1',
                                    'track_code' => $CBarcodeFinal,
                                    'old_meter'=>'0',
                                    'short_meter'=>'0',
                                    'rejected_meter'=>'0',
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '1',
                                    'rack_id' => 0,
                                    'is_opening'=>$is_opening,
                                    'userId'=>$request->userId
                                );
                                
                            FabricInwardDetailModel::insert($data2);
                            FabricTransactionModel::insert($data3);
                            
                            //DB::table('dump_fabric_stock_data')->where('grn_no', $in_code)->where('item_code', $request->item_code[$x])->where('po_no', $request->po_code)->delete();
                               $buyerData = DB::table('purchaseorder_detail')
                                    ->select('LM1.ac_name as buyer_name', 'purchaseorder_detail.job_status_id', 'job_status_master.job_status_name')
                                    ->join('purchase_order', 'purchase_order.pur_code', '=', 'purchaseorder_detail.pur_code')
                                    ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.tr_code', '=', 'purchaseorder_detail.sales_order_no')
                                    ->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
                                    ->leftJoin('job_status_master', 'job_status_master.job_status_id', '=', 'purchaseorder_detail.job_status_id')
                                    ->where('purchaseorder_detail.pur_code', $request->po_code)
                                    ->where('purchaseorder_detail.item_code', $request->item_code[$x])
                                    ->groupBy('purchaseorder_detail.pur_code')
                                    ->get();
                                
                                $buyerData1 = DB::table('purchase_order')->select('LM1.ac_name as buyer_name')->join('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')->where('purchase_order.pur_code','=', $po_no)->get();
                                                
                                $buyer_name = isset($buyerData1[0]->buyer_name) ? $buyerData1[0]->buyer_name : "";
                                
                                $itemData = DB::table('item_master')->join('quality_master','quality_master.quality_code','=','item_master.quality_code')->select('item_name', 'item_description','quality_master.quality_name')->where('item_code', $item_code)->first();
                                
                                $ledgerData = DB::table('ledger_master')->select('ac_name')->where('ac_code', $request->Ac_code)->first();
                        
                                $suplierName = isset($ledgerData->ac_name) ? $ledgerData->ac_name : "";
                                $job_status_id = isset($buyerData[0]->job_status_id) ? $buyerData[0]->job_status_id : 1;
                                
                                if ($job_status_id == 0 || $job_status_id == 1 || $job_status_id == "") 
                                {
                                    $job_status_id = 1;
                                    $po_status = "Moving";
                                } 
                                else 
                                {
                                    $job_status_id = 2;
                                    $po_status = "Non Moving";
                                }
                            
                                if($is_opening == 1)
                                {
                                    $job_status_id = 2;
                                    $po_status = "Non Moving";
                                }
                    
                                $item_name = isset($itemData->item_name) ? $itemData->item_name : "";
                                $item_description = isset($itemData->item_description) ? $itemData->item_description : "";
                                $quality_name = isset($itemData->quality_name) ? $itemData->quality_name : "";
                                $in_date = $request->in_date;
                                $suplier_name = $suplierName;
                                $po_no = $request->po_code;
                                $grn_no = $request->in_code;
                                $invoice_no = $request->invoice_no;
                                $item_code = $request->item_code[$x];
                                $preview =  "";
                                $shade_no = 1;
                                $item_name = $item_name;
                                $quality_name = str_replace('"', '', $quality_name);
                                $color = "";
                                $item_description =  str_replace('"', '', $item_description);
                                $po_status = $po_status;
                                $job_status_id = $job_status_id;
                                $track_name = $CBarcodeFinal;
                                $grn_qty = $request->meter[$x];
                                $rate = $request->item_rates[$x];
                                $rack_id = 0;
                                  
                                
                                // DB::SELECT('INSERT INTO dump_fabric_stock_data(in_date,suplier_name,po_no,grn_no,invoice_no,item_code,shade_no,item_name,quality_name,
                                //         color,item_Description,po_status,job_status_id,track_name,grn_qty,rate,rack_name,tr_type)
                                //         select "'.$in_date.'","'.$suplier_name.'","'.$po_no.'","'.$grn_no.'","'.$invoice_no.'","'.$item_code.'","'.$shade_no.'","'.addslashes($item_name).'",
                                //         "'.$quality_name.'","'.$color.'","'.addslashes($item_description).'","'.$po_status.'","'.$job_status_id.'","'.$track_name.'","'.$grn_qty.'","'.$rate.'","'.$rack_id.'",1');

                                $grnData = DB::SELECT("SELECT * FROM dump_fabric_stock_data WHERE track_name = '".$track_name."' ");
                                
                                if(count($grnData) > 0)
                                { 
                                    $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $track_name)->get();
                                    $fabricChecking = DB::select("SELECT sum(meter) as qc from fabric_checking_details where track_code='".$track_name."'");

                                    $qc_qty = isset($fabricChecking[0]->qc) ? $fabricChecking[0]->qc : 0;
                                    $updated_string = '';
                                    foreach($existingData as $outwards)
                                    {
                                        $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
                                    }
                                    
                                    DB::table('dump_fabric_stock_data')
                                        ->where('in_date', '=', $in_date)
                                        ->where('track_name', '=', $track_name)
                                        ->where('grn_no', '=', $grn_no)
                                        ->update([
                                            'in_date' => $in_date,
                                            'suplier_name' => $suplier_name,
                                            'buyer_name' => $buyer_name,
                                            'po_no' => $po_no,  
                                            'grn_no' => $grn_no,  
                                            'invoice_no' => $request->invoice_no,
                                            'item_code' => $item_code,  
                                            'shade_no' => $shade_no,  
                                            'item_name' => addslashes($item_name),  
                                            'quality_name' => $quality_name,  
                                            'color' => $color,  
                                            'item_Description' => addslashes($item_description),  
                                            'po_status' => $po_status,  
                                            'job_status_id' => $job_status_id,  
                                            'track_name' => $track_name,  
                                            'grn_qty' => $grn_qty, 
                                            'rate' => $rate, 
                                            'rack_name' => $rack_id,  
                                            'qc_qty' => $qc_qty,  
                                            'ind_outward_qty' => $updated_string,  
                                            'tr_type' => 1
                                    ]);
                                    
                                    DB::delete("DELETE FROM dump_fabric_stock_data WHERE grn_no = ? AND track_name NOT IN (SELECT track_code FROM inward_details WHERE grn_no = ?)", [$grn_no, $grn_no]);

                                }
                                else
                                { 
                                    $existingData = DB::table('fabric_outward_details')->select('fout_date','meter')->where('track_code', '=', $track_name)->get();
                                    $fabricChecking = DB::select("SELECT sum(meter) as qc, width from fabric_checking_details where track_code='".$track_name."'");
                                    $width = isset($fabricChecking[0]->width) ? $fabricChecking[0]->width : 0;
                                    $qc_qty = isset($fabricChecking[0]->qc) ? $fabricChecking[0]->qc : 0;
                                    $updated_string = '';
                                    foreach($existingData as $outwards)
                                    {
                                        $updated_string .=  $outwards->fout_date.'=>'.$outwards->meter.","; 
                                    }
                                    
                                    DB::table('dump_fabric_stock_data')
                                    ->insert([
                                        'in_date' => $in_date,
                                        'suplier_name' => $suplier_name,
                                        'buyer_name' => $buyer_name,
                                        'po_no' => $po_no,
                                        'grn_no' => $grn_no,
                                        'invoice_no' => $invoice_no,
                                        'item_code' => $item_code,
                                        'shade_no' => $shade_no,
                                        'item_name' => $item_name,  // Assuming $item_name doesn't need addslashes
                                        'quality_name' => $quality_name,
                                        'color' => $color,
                                        'item_Description' => $item_description,  // Assuming $item_description doesn't need addslashes
                                        'po_status' => $po_status,
                                        'job_status_id' => $job_status_id,
                                        'track_name' => $track_name,
                                        'grn_qty' => $grn_qty,
                                        'qc_qty' => $qc_qty,  
                                        'width' => $width,  
                                        'ind_outward_qty' => $updated_string,  
                                        'rate' => $rate,
                                        'rack_name' => $rack_id,
                                        'tr_type' => 1
                                    ]); 
                                }
                            

                     }
                     
                     
                     
                    $newDataDetail2[]=[
                        'track_code' => $PBarcodeFinal, 
                        'item_code'=>$request->item_code[$x], 
                        'part_id' =>$request->part_id[$x],
                        'roll_no' => $request->id[$x],
                        'meter' => $request->meter[$x],
                        'gram_per_meter' => $request->gram_per_meter[$x],
                        'kg' => $request->kg[$x],
                        'item_rate' => $request->item_rates[$x],
                        'amount' => $request->amounts[$x],
                        'shade_id' =>'1',
                        'suplier_roll_no' => $request->suplier_roll_no[$x]];     
                     
                     
                     
                }
              
              
              
                         $combinedNewData = $newDataDetail2;       
           
            try {
            $loggerDetail->logIfChangedFabricInwardDetail(
            'inward_details',
            $in_code,
            $combinedOldData,
            $combinedNewData,
            'UPDATE',
            $in_date,
            'inward_details'
            );
            // Log::info('Logger called successfully for packing_inhouse_size_detail.', [
            //   $newDataDetail
            // ]);
            } catch (\Exception $e) {
            Log::error('Logger failed for sale_transaction_detail.', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'in_code' => $in_code,
            'data' => $combinedNewData
            ]);
            }     
              
              
      
     //dd(DB::getQueryLog());
     
     
      //  $query = DB::getQueryLog();
            //  $query = end($query);
            //  dd($query);
                
                
            
        
        $ledgerData = DB::SELECT("SELECT ac_short_name FROM ledger_master WHERE ac_code='".$buyer_id."'");
        $buyerName = isset($ledgerData[0]->ac_short_name) ? $ledgerData[0]->ac_short_name : '';
        DB::select("update dump_fabric_stock_data set buyer_name='".$buyerName."' where po_no ='".$request->po_code."'"); 
        
        }
            return redirect()->route('FabricInward.index')->with('message', 'Update Record Succesfully');
    }

 

public function PrintFabricBarcode(Request $request)
{
    $data='';
    // $Colors=ColorModel::where('color_id','=',$request->color_id )->first(); 
    // $color_name=$Colors->color_name;
    $Parts=PartModel::where('part_id','=',$request->part_id )->first(); 
    $part_name=$Parts->part_name;
    // $QualityList = QualityModel::where('quality_code','=',$request->quality_code )->first(); 
    // $quality_name=$QualityList->quality_name;
    $start=''; $end='';
    
    
$start= "<xpml><page quantity='0' pitch='40.0 mm'></xpml>"; 
$end="<xpml></page></xpml><xpml><end/></xpml>";
     	           
// $data=$data.'SIZE 79.8 mm, 40 mm
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
// TEXT 583,284,"0",180,13,10,"Color:"
// TEXT 471,284,"0",180,13,10,"'.$color_name.'"
// TEXT 609,215,"0",180,13,10,"Use for:"
// TEXT 471,215,"0",180,13,10,"'.$part_name.'"
// TEXT 272,284,"0",180,13,10,"Width:"
// TEXT 155,284,"0",180,13,10,"'.$request->width.'"
// TEXT 269,215,"0",180,13,10,"Meter:"
// TEXT 156,215,"0",180,13,10,"'.$request->meter.'"
// TEXT 617,144,"0",180,13,10,"StyleNo:"
// TEXT 471,144,"0",180,13,10,"'.$request->style_no.'"
// TEXT 230,144,"0",180,13,10,"JC:"
// TEXT 159,144,"0",180,13,10,"'.$request->job_code.'"
// BARCODE 464,96,"39",40,0,180,3,8,"'.$request->track_code.'"
// TEXT 344,50,"ROMAN.TTF",180,1,10,"'.$request->track_code.'"
// PRINT 1,2
// ';     	           
    	 
    	 
  $data=$data.'I8,A
q640
O
JF
ZT
Q320,25
<xpml></page></xpml><xpml><page quantity="2" pitch="40.0 mm"></xpml>FK"SSFMT002"
FK"SSFMT002"
FS"SSFMT002"
A607,285,2,3,1,1,N,"Job:"
A533,285,2,3,1,1,N,"'.$request->job_code.'"
A314,285,2,3,1,1,N,"#"
A289,285,2,3,1,1,N,"'.$request->style_no.'"
A607,235,2,3,1,1,N,"CLR:"
A530,235,2,3,1,1,N,""
A319,235,2,3,1,1,N,"W:"
A291,235,2,3,1,1,N,""
A607,184,2,3,1,1,N,"For:"
A530,184,2,3,1,1,N,"'.$part_name.'"
A361,180,2,3,1,1,N,"Qlty:"
B490,92,2,3,3,8,51,N,"'.$request->track_code.'"
A369,35,2,3,1,1,N,"'.$request->track_code.'"
A185,235,2,3,1,1,N,"Mtr:'.$request->meter.'"
A585,135,2,3,1,1,N,"Kg:"
A529,135,2,3,1,1,N,"'.$request->kg.'"
A291,180,2,3,1,1,N,""
A291,151,2,3,1,1,N,""
FE
N
FR"SSFMT002"
P2
';     	 
    	 
    	 $data=$start.$data.$end;
    	            
                    					 
                    $dir="barcode";
                    $pagename = 'data';
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







    public function getPo(Request $request)
    {

        $ItemList = DB::table('purchaseorder_detail')->select('purchaseorder_detail.item_code', 'item_name')
            ->leftJoin('item_master', 'item_master.item_code', '=', 'purchaseorder_detail.item_code')
            ->where('pur_code','=',$request->po_code)->distinct()->get();
            
            
        if (!$request->po_code)
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


    public function getPoMasterDetail(Request $request)
    {
        $po_codee= $request->po_code;
    
        $data=DB::table('purchase_order')->where('pur_code','=',$po_codee)->get(['purchase_order.*']);
        return $data;
    }


  public function getPODetails(Request $request)
    { 
        $po_code= base64_decode($request->input('po_code'));
        //echo $po_code;
        $MasterdataList = DB::select("select po_code, fabric_checking_master.Ac_code, ledger_master.ac_name, fabric_checking_master.po_type_id,fabric_checking_master.is_opening from fabric_checking_master 
        inner join ledger_master on ledger_master.ac_code=fabric_checking_master.Ac_code
        where fabric_checking_master.po_code='". $po_code."'");
        return json_encode($MasterdataList);
    }


    public function getItemRateFromPO(Request $request)
    { 
        $po_code= $request->input('po_code');
        $item_code= $request->input('item_code');
        $Rate = DB::select("select  item_rate from  purchaseorder_detail
        where purchaseorder_detail.pur_code='". $po_code."' and item_code='".$item_code."'");
        return json_encode($Rate);
    }

    public function GetPurchaseDetailItemCodeWise(Request $request)
    { 
        $po_code= $request->input('po_code'); 
        $PurchaseData = DB::select("select  purchaseorder_detail.item_rate, sum(item_qty) as item_qty,  
                        (SELECT sum(inward_details.meter) FROM inward_details
                        WHERE inward_details.po_code = purchaseorder_detail.pur_code AND inward_details.item_code = purchaseorder_detail.item_code) as to_be_received,
                        item_master.item_name,item_master.item_code from  purchaseorder_detail 
                        INNER JOIN item_master ON item_master.item_code = purchaseorder_detail.item_code 
                        where purchaseorder_detail.pur_code='". $po_code."' GROUP BY purchaseorder_detail.item_code");

        $html = '';
        $sr_no = 1;
        foreach($PurchaseData as $row)
        {
             $html .= '<tr class="item_code_'.$row->item_code.'">
                            <td class="text-center">'.($sr_no++).'</td>  
                            <td class="text-center">'.$row->item_code.'</td>  
                            <td>'.$row->item_name.'</td>  
                            <td class="text-center">'.$row->item_qty.'</td>    
                            <td class="text-center">'.$row->to_be_received.'</td>    
                            <td class="text-center bal_qty">'.($row->item_qty-$row->to_be_received).'</td>    
                    </tr>'; 
        }

        return response()->json(['html' => $html]);
    }

    

    // public function FabricGRNData()
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();
    //     $CPList =  DB::table('cp_master')->get();
    //     $PartList = PartModel::where('part_master.delflag','=', '0')->get();
    //     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
    //     $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
    //     $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
    //     $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
    //     // DB::enableQueryLog();
    //     $FabricInwardDetails = FabricInwardDetailModel::
    //       leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'inward_details.Ac_code')
    //       ->leftJoin('item_master', 'item_master.item_code', '=', 'inward_details.item_code')
    //       ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
    //       ->leftJoin('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
    //       ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'inward_details.cp_id')
    //       ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'inward_details.rack_id')
    //       ->get(['inward_details.*', 'cp_master.cp_name','part_master.part_name','ledger_master.ac_name','item_master.dimension', 'item_master.item_name','item_master.color_name','item_master.item_description','quality_master.quality_name','rack_master.rack_name']);
    //  // $query = DB::getQueryLog();
    //     // $query = end($query);
    //     // dd($query);
    //     return view('FabricGRNData',compact('FabricInwardDetails'));
    // }
    
    
    public function FabricGRNData(Request $request)
    {
      
        ini_set('memory_limit', '10G'); 
        if ($request->ajax()) 
        { 
           //DB::enableQueryLog();
           $FabricInwardDetails = FabricInwardDetailModel::
              leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'inward_details.Ac_code')
              ->leftJoin('purchase_order', 'purchase_order.pur_code', '=', 'inward_details.po_code')
              ->leftJoin('ledger_master as LM1', 'LM1.ac_code', '=', 'purchase_order.buyer_id')
              ->leftJoin('ledger_master as LM2', 'LM2.ac_code', '=', 'inward_details.buyer_id')
              ->leftJoin('item_master', 'item_master.item_code', '=', 'inward_details.item_code')
              ->leftJoin('quality_master', 'quality_master.quality_code', '=', 'item_master.quality_code')
              ->leftJoin('part_master', 'part_master.part_id', '=', 'inward_details.part_id')
              ->leftJoin('cp_master', 'cp_master.cp_id', '=', 'inward_details.cp_id')
              ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'inward_details.rack_id')
              ->leftJoin('inward_master', 'inward_master.in_code', '=', 'inward_details.in_code')
              ->leftJoin('vendor_purchase_order_master', 'vendor_purchase_order_master.vpo_code', '=', 'inward_master.vpo_code')
              ->leftJoin('ledger_master as LM3', 'LM3.ac_code', '=', 'vendor_purchase_order_master.vendorId')
              ->get(['inward_details.*','inward_master.invoice_no','inward_master.invoice_date', 'cp_master.cp_name','part_master.part_name','ledger_master.ac_short_name','item_master.dimension', 'item_master.item_name',
                        'item_master.color_name','item_master.item_description','quality_master.quality_name','rack_master.rack_name', 'LM1.ac_short_name as buyer1', 'LM2.ac_short_name as buyer2',
                        'vendor_purchase_order_master.vpo_code', 'LM3.ac_short_name as vendorName', DB::raw('(SELECT trade_name FROM ledger_details WHERE sr_no = purchase_order.bill_to OR ac_code = inward_details.Ac_code LIMIT 1) as trade_name'),
                        DB::raw('(SELECT site_code FROM ledger_details WHERE sr_no = purchase_order.bill_to OR ac_code = inward_details.Ac_code LIMIT 1) as suplier_id')]);
            //dd(DB::getQueryLog());
            return Datatables::of($FabricInwardDetails)
            ->addColumn('item_value',function ($row) 
            {
                $item_value =  round($row->item_rate*$row->meter);
               
                return $item_value;
            })
            ->addColumn('invoice_no',function ($row) 
            {
                
                $invoice_no =  isset($row->invoice_no) ? $row->invoice_no : "";
               
                return $invoice_no;
            })
            ->addColumn('invoice_date',function ($row) 
            {
                
                $invoice_date = isset($row->invoice_date) ? $row->invoice_date : "";
               
                return $invoice_date;
            })
            ->addColumn('buyer',function ($row) 
            {
                
                $buyer = isset($row->buyer1) ? $row->buyer1 : $row->buyer2;
               
                return $buyer;
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
             ->rawColumns(['item_value', 'bill_to'])
             
             ->make(true);
    
            }
            
          return view('FabricGRNData');
        
    }
    
    public function FabricGRNDataMD(Request $request,$DFilter)
    {
        if ($request->ajax()) 
        { 
               
            if($DFilter == 'd')
            {
                $filterDate = " AND inward_details.in_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            }
            else if($DFilter == 'm')
            {
                $filterDate = ' AND MONTH(inward_details.in_date) = MONTH(CURRENT_DATE()) and YEAR(inward_details.in_date)=YEAR(CURRENT_DATE()) AND inward_details.in_date !="'.date('Y-m-d').'"';
            }
            else if($DFilter == 'y')
            {
                $filterDate = ' AND inward_details.in_date between (select fdate from financial_year_master 
                                where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
            }
            else
            {
                $filterDate = "";
            }
            
            //DB::enableQueryLog();
            $FabricInwardDetails = DB::select("SELECT inward_details.*, cp_master.cp_name,part_master.part_name,ledger_master.ac_name,
                item_master.dimension, item_master.item_name,item_master.color_name,item_master.item_description,quality_master.quality_name,rack_master.rack_name
            FROM inward_details
            LEFT JOIN ledger_master ON ledger_master.ac_code = inward_details.Ac_code
            LEFT JOIN item_master ON item_master.item_code = inward_details.item_code
            LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
            LEFT JOIN part_master ON part_master.part_id = inward_details.part_id
            LEFT JOIN cp_master ON cp_master.cp_id = inward_details.cp_id
            LEFT JOIN rack_master ON rack_master.rack_id = inward_details.rack_id
            WHERE 1 ".$filterDate);
            
            return Datatables::of($FabricInwardDetails)
            
            ->addColumn('sales_order_no',function ($row) 
            {
                if($row->is_opening!=1) 
                {
                    $sales_order_no = isset($row->sales_order_no) ? $row->sales_order_no : "";
                }
                else
                {
                    $sales_order_no= 'Opening Stock'; 
                }
                
                return $sales_order_no;
            })
            ->addColumn('item_value',function ($row) 
            {
                
                $item_value =  round($row->item_rate*$row->meter);
               
                return $item_value;
            })
            ->addColumn('invoice_no',function ($row) 
            {
                
                $invoice_no =  isset($row->invoice_no) ? $row->invoice_no : "";
               
                return $invoice_no;
            })
            ->addColumn('invoice_date',function ($row) 
            {
                
                $invoice_date =  isset($row->invoice_date) ? $row->invoice_date : "";
               
                return $invoice_date;
            })
             ->rawColumns(['sales_order_no','item_value'])
             
             ->make(true);
    
            }
            
          return view('FabricGRNData');
        
    }
    
    // public function FabricGRNDataMD($DFilter)
    // {
    //     $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
    //     $FGList =  DB::table('fg_master')->get();
    //     $CPList =  DB::table('cp_master')->get();
    //     $PartList = PartModel::where('part_master.delflag','=', '0')->get();
    //     $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
    //     $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
    //     $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
    //     $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
              
    //     if($DFilter == 'd')
    //     {
    //         $filterDate = " AND inward_details.in_date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
    //     }
    //     else if($DFilter == 'm')
    //     {
    //         $filterDate = ' AND MONTH(inward_details.in_date) = MONTH(CURRENT_DATE()) and YEAR(inward_details.in_date)=YEAR(CURRENT_DATE()) AND inward_details.in_date !="'.date('Y-m-d').'"';
    //     }
    //     else if($DFilter == 'y')
    //     {
    //         $filterDate = ' AND inward_details.in_date between (select fdate from financial_year_master 
    //                         where financial_year_master.fin_year_id=4) and (select tdate from financial_year_master where financial_year_master.fin_year_id=4)';
            
    //     }
    //     else
    //     {
    //         $filterDate = "";
    //     }
        
    //     //DB::enableQueryLog();
    //     $FabricInwardDetails = DB::select("SELECT inward_details.*, cp_master.cp_name,part_master.part_name,ledger_master.ac_name,
    //         item_master.dimension, item_master.item_name,item_master.color_name,item_master.item_description,quality_master.quality_name,rack_master.rack_name
    //         FROM inward_details
    //         LEFT JOIN ledger_master ON ledger_master.ac_code = inward_details.Ac_code
    //         LEFT JOIN item_master ON item_master.item_code = inward_details.item_code
    //         LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
    //         LEFT JOIN part_master ON part_master.part_id = inward_details.part_id
    //         LEFT JOIN cp_master ON cp_master.cp_id = inward_details.cp_id
    //         LEFT JOIN rack_master ON rack_master.rack_id = inward_details.rack_id
    //         WHERE 1 ".$filterDate);
    //     //dd(DB::getQueryLog()); 
    //     return view('FabricGRNData',compact('FabricInwardDetails'));
    // }
    
    
    public function FabricStockData()
    {
        $FabricInwardDetails1 =DB::select("select inward_details.* , ifnull(fabric_checking_details.meter,inward_details.meter) as ActualMeter, inward_master.po_code as po_codes, inward_master.invoice_no, shade_master.shade_name,inward_master.in_code,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
        cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
        item_master.item_name,item_master.color_name,item_master.item_description,
        quality_master.quality_name,rack_master.rack_name,ifnull(purchase_order.po_status,0) as po_status from inward_details
        left JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
        left join inward_master on inward_master.in_code=inward_details.in_code
        left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        left join cp_master on cp_master.cp_id=inward_details.cp_id 
        left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
        left join item_master on item_master.item_code=inward_details.item_code 
        left join quality_master on quality_master.quality_code=item_master.quality_code 
        left join part_master on part_master.part_id=inward_details.part_id 
        left join shade_master on shade_master.shade_id=fabric_checking_details.shade_id 
        left join rack_master on rack_master.rack_id=inward_details.rack_id");
      // dd(DB::getQueryLog());
    //     $query = end($query);
    //     dd($query);
    
    
        $filterDate = "";
        $filterDate1 = "";
        $filterDate2 = "";
        $FabricInwardDetails2 = "";  
        $isOpening = "";  
        return view('FabricStockData',compact('FabricInwardDetails1','FabricInwardDetails2','filterDate','filterDate2','isOpening'));
    }

    public function FabricStockData1()
    {
         return view('FabricStockData1');
    }
    
    public function loadDumpFabricStockData(Request $request)
    {
        $FabricInwardDetails = DB::select("SELECT * FROM dump_fabric_stock_data");
        $totalAmountData = DB::select("SELECT sum(value) as overallAmt FROM dump_fabric_stock_data");
        $html = "";
        $totalGrnQty = 0;
        $totalQc_qty = 0;
        $totalOutward_qty = 0;
        $totalStockQty = 0;
        $totalvalue = 0;
        
        foreach($FabricInwardDetails as $row)
        {
            $html .='<tr>
                         <td style="text-align:center; white-space:nowrap">'.$row->suplier_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->buyer_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->po_status.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->po_no.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->grn_no.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->invoice_no.'</td>
                         <td>'.$row->item_code.'</td>
                         <td style="text-align:center; white-space:nowrap">-</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->shade_no.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->item_name.'</td>
                         <td style="text-align:right;">'.$row->width.'</td>
                         <td style="text-align:right;">'.$row->quality_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->color.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->item_description.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->status.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->track_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.$row->rack_name.'</td>
                         <td style="text-align:center; white-space:nowrap">'.round($row->grn_qty,2).'</td>
                         <td style="text-align:center; white-space:nowrap">'.round($row->qc_qty,2).'</td>
                         <td style="text-align:center; white-space:nowrap">'.round($row->outward_qty,2).'</td>
                         <td style="text-align:right;">'.round($row->stock_qty,2).'</td>
                         <td style="text-align:right;">'.$row->rate.'</td>
                         <td style="text-align:right;">'.round($row->value,2).'</td>
                      </tr>';
                      
                      $totalGrnQty = $totalGrnQty + $row->grn_qty;
                      $totalQc_qty = $totalQc_qty + $row->qc_qty;
                      $totalOutward_qty = $totalOutward_qty + $row->outward_qty;
                      $totalStockQty = $totalStockQty + $row->stock_qty;
                      $totalvalue = $totalvalue + $row->value;
        } 
        
        $overall = isset($totalAmountData[0]->overallAmt) ? $totalAmountData[0]->overallAmt : 0;
        return response()->json(['html' => $html,'overall'=>round($overall),'total_grn_qty'=>$totalGrnQty,'totalQc_qty'=>$totalQc_qty,'totalOutward_qty'=>$totalOutward_qty,'totalStockQty'=>$totalStockQty,'totalvalue'=>$totalvalue]);
    }
    public function FabricStocks()
    {
        
        DB::table('dump_fabric_stock_data')->delete();
        //DB::enableQueryLog();
        $FabricInwardDetails1 =DB::select("select inward_details.* ,fabric_checking_details.chk_date, ifnull(fabric_checking_details.meter,inward_details.meter) as ActualMeter, inward_master.po_code as po_codes, inward_master.invoice_no, shade_master.shade_name,inward_master.in_code,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter, 
        (SELECT fabric_outward_details.fout_date FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code GROUP BY fabric_outward_details.track_code)  as fout_date,
        cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
        item_master.item_name,item_master.color_name,item_master.item_description,
        quality_master.quality_name,rack_master.rack_name,ifnull(purchase_order.po_status,0) as po_status from inward_details
        left JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
        left join inward_master on inward_master.in_code=inward_details.in_code
        left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
        left join cp_master on cp_master.cp_id=inward_details.cp_id 
        left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
        left join item_master on item_master.item_code=inward_details.item_code 
        left join quality_master on quality_master.quality_code=item_master.quality_code 
        left join part_master on part_master.part_id=inward_details.part_id 
        left join shade_master on shade_master.shade_id=fabric_checking_details.shade_id 
        left join rack_master on rack_master.rack_id=inward_details.rack_id ");
       //dd(DB::getQueryLog());
   

        $total_grn_qty = 0;
        $total_qc_qty = 0;
        $total_outward_qty = 0;
        $total_stock_qty = 0;
        $total_value = 0;
        $isOpening = 2;
            
        foreach($FabricInwardDetails1 as $row)  
        {
            if(($row->meter-$row->out_meter)>0)
            {
                         $checking_width =DB::select("select  width,fabric_check_status_master.fcs_name FROM fabric_checking_details 
                         LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                         WHERE track_code = '".$row->track_code."'");
                         
                         if(count($checking_width) > 0)
                         {
                             $width = $checking_width[0]->width;
                             $fcs_name = $checking_width[0]->fcs_name;
                         }
                         else
                         {
                             $width = 0;
                             $fcs_name = "-";
                         }
                         
                        $JobStatusList=DB::select('select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='.$row->po_status);
                        
                        if(count($JobStatusList) > 0)
                        {
                            $job_status_name = $JobStatusList[0]->job_status_name;
                        }
                        else
                        {
                            $job_status_name = "-";
                        }
                        
                        $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_codes."'");
                     
                    if(count($salesOrderNo) > 0)
                    {
                         $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                         INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                         where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                         
                         if(count($buyerData) > 0)
                         {
                            $buyer_name = $buyerData[0]->ac_name;
                         }
                         else
                         {
                            $buyer_name = "-";
                         }
                    }
                    else
                    {
                        $buyer_name = "-";
                    } 
                     
                    $GRNData =DB::select("select meter as grnQty from inward_details where  track_code='".$row->track_code."'");
                        
                    if(count($GRNData) > 0)
                    {
                        $grnQty = $GRNData[0]->grnQty;
                    }
                    else
                    {
                        $grnQty = 0;
                    }
                     
                    $QCData =DB::select("select meter as QCQty from fabric_checking_details where track_code='".$row->track_code."'");
                     
                    if(count($QCData) > 0)
                    {
                         $QCQty = $QCData[0]->QCQty;
                    }
                    else
                    {
                        $QCQty = 0;
                    }
        
                   // $html .='<td><a href="'.url('images/'.$row->item_image_path).'" target="_blank"><img src="'.url('thumbnail/'.$row->item_image_path).'" alt="'.$row->item_code.'"></a></td>';
               
             
            DB::table('dump_fabric_stock_data')->insert(
                array('suplier_name' => $row->ac_name,
                      'buyer_name' => $buyer_name,
                      'in_date' => $row->in_date,
                      'fout_date' => $row->fout_date,
                      'chk_date' => $row->chk_date,
                      'po_status' => $job_status_name,
                      'po_no' => $row->po_codes,
                      'grn_no' => $row->in_code,
                      'invoice_no' => $row->invoice_no, 
                      'item_code' => $row->item_code,
                      'preview' => "-",
                      'shade_no' => $row->shade_name,
                      'item_name' => $row->item_name,
                      'width' => $width,
                      'quality_name' => $row->quality_name,
                      'color' => $row->color_name,
                      'item_description' => $row->item_description,
                      'status' => $fcs_name,
                      'track_name' => $row->track_code,
                      'rack_name' => $row->rack_name,
                      'grn_qty' => $grnQty,
                      'qc_qty' => $QCQty,
                      'outward_qty' => $row->out_meter,
                      'stock_qty' => ($row->ActualMeter-$row->out_meter),
                      'rate' => $row->item_rate,
                      'value' => (($row->ActualMeter-$row->out_meter) * $row->item_rate),
                )
            );
            }
        }
            
            if($isOpening == 2)
            {
                    $FabricInwardDetails2 =DB::select("select inward_details.* ,ifnull(inward_details.meter,0) as ActualMeter,
                    inward_master.po_code as po_codes, inward_master.invoice_no,shade_master.shade_name,
                        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details 
                        WHERE fabric_outward_details.track_code = inward_details.track_code)  as out_meter ,
                        cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
                        item_master.item_name,item_master.color_name,item_master.item_description,
                        quality_master.quality_name,rack_master.rack_name ,ifnull(purchase_order.po_status,0) as po_status from inward_details
                        left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
                        left join inward_master on inward_master.in_code=inward_details.in_code
                         left JOIN purchase_order ON purchase_order.pur_code = inward_master.po_code
                        left join cp_master on cp_master.cp_id=inward_details.cp_id 
                        left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
                        left join item_master on item_master.item_code=inward_details.item_code 
                        left join quality_master on quality_master.quality_code=item_master.quality_code 
                        left join part_master on part_master.part_id=inward_details.part_id 
                        left join shade_master on shade_master.shade_id=inward_details.shade_id 
                        left join rack_master on rack_master.rack_id=inward_details.rack_id 
                        WHERE inward_master.is_opening=1 GROUP BY inward_details.track_code");
        
                foreach($FabricInwardDetails2 as $row)   
                {
                    if(($row->ActualMeter-$row->out_meter)>0)
                    {
                     
                         $checking_width =DB::select("select  width,fabric_check_status_master.fcs_name FROM fabric_checking_details 
                         LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                         WHERE track_code = '".$row->track_code."'");
                         
                         if(count($checking_width) > 0)
                         {
                             $width = $checking_width[0]->width;
                             $fcs_name = $checking_width[0]->fcs_name;
                         }
                         else
                         {
                             $width = 0;
                             $fcs_name = "-";
                         }
                         
                         
                            $job_status_name = "-";
                        
                        
                        $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_codes."'");
                         
                        if(count($salesOrderNo) > 0)
                        {
                             $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                             INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                             where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                             
                             if(count($buyerData) > 0)
                             {
                                $buyer_name = $buyerData[0]->ac_name;
                             }
                             else
                             {
                                $buyer_name = "-";
                             }
                        }
                        else
                        {
                            $buyer_name = "-";
                        } 
                         
                        $GRNData =DB::select("select meter as grnQty from inward_details where  track_code='".$row->track_code."'");
                            
                        if(count($GRNData) > 0)
                        {
                            $grnQty = $GRNData[0]->grnQty;
                        }
                        else
                        {
                            $grnQty = 0;
                        }
                         
                        $QCData =DB::select("select meter as QCQty from fabric_checking_details where track_code='".$row->track_code."'");
                         
                        if(count($QCData) > 0)
                        {
                             $QCQty = $QCData[0]->QCQty;
                        }
                        else
                        {
                            $QCQty = 0;
                        }
                    
                    DB::table('dump_fabric_stock_data')->insert(
                        array('suplier_name' => $row->ac_name,
                              'buyer_name' => $buyer_name,
                              'in_date' => $row->in_date,
                              'fout_date' => $row->fout_date,
                              'chk_date' => $row->chk_date,
                              'po_status' => $job_status_name,
                              'po_no' => $row->po_codes,
                              'grn_no' => $row->in_code,
                              'invoice_no' => $row->invoice_no, 
                              'item_code' => $row->item_code,
                              'preview' => "-",
                              'shade_no' => $row->shade_name,
                              'item_name' => $row->item_name,
                              'width' => $width,
                              'quality_name' => $row->quality_name,
                              'color' => $row->color_name,
                              'item_description' => $row->item_description,
                              'status' => $fcs_name,
                              'track_name' => $row->track_code,
                              'rack_name' => $row->rack_name,
                              'grn_qty' => $grnQty,
                              'qc_qty' => $QCQty,
                              'outward_qty' => $row->out_meter,
                              'stock_qty' => ($row->ActualMeter-$row->out_meter),
                              'rate' => $row->item_rate,
                              'value' => (($row->ActualMeter-$row->out_meter) * $row->item_rate)
                        )
                    );
                    }
                }
        } 
                     
        return 1;
    }
    
    public function FabricStockDataMD($isOpening,$DFilter)
    {  
        $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
     
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
       
        if($DFilter == 'd')
        {
            $filterDate = " AND inward_details.in_date <= '".date('Y-m-d')."'";
            $filterDate1 = " AND fabric_outward_details.fout_date <= '".date('Y-m-d')."'";
            $filterDate2 = " AND fabric_checking_details.chk_date <= '".date('Y-m-d')."'";
        }
        else if($DFilter == 'm')
        {
            $filterDate = ' AND inward_details.in_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate1 = ' AND fabric_outward_details.fout_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate2 = ' AND fabric_checking_details .chk_date <= DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else if($DFilter == 'y')
        {
            $filterDate = ' AND inward_details.in_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate1 = ' AND fabric_outward_details.fout_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
            $filterDate2 = ' AND fabric_checking_details .chk_date <= DATE_FORMAT(LAST_DAY("'.$Financial_Year[0]->fdate.'" - INTERVAL 1 MONTH), "%Y-%m-%d")';
        }
        else
        {
            $filterDate = "";
            $filterDate1 = "";
            $filterDate2 = "";
        }
        
        $FabricInwardDetails1 =DB::select("select inward_details.* ,ifnull(fabric_checking_details.meter,inward_details.meter) as ActualMeter,
        inward_master.po_code as po_codes, inward_master.invoice_no,shade_master.shade_name,
            (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code ".$filterDate1.")  as out_meter ,
            cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
            item_master.item_name,item_master.color_name,item_master.item_description,
            quality_master.quality_name,rack_master.rack_name,ifnull(purchase_order.po_status,0) as po_status from inward_details
            left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
            INNER join inward_master on inward_master.in_code=inward_details.in_code
            INNER JOIN purchase_order ON purchase_order.pur_code = inward_master.po_code
            left join cp_master on cp_master.cp_id=inward_details.cp_id 
            left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
            left join item_master on item_master.item_code=inward_details.item_code 
            left join quality_master on quality_master.quality_code=item_master.quality_code 
            left join part_master on part_master.part_id=inward_details.part_id 
            left join shade_master on shade_master.shade_id=inward_details.shade_id 
            left join rack_master on rack_master.rack_id=inward_details.rack_id WHERE purchase_order.po_status=".$isOpening."  ".$filterDate);
            
      //  DB::enableQueryLog();
        $FabricInwardDetails2 =DB::select("select inward_details.* ,ifnull(fabric_checking_details.meter,inward_details.meter) as ActualMeter,
        inward_master.po_code as po_codes, inward_master.invoice_no,shade_master.shade_name,
            (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details 
            WHERE fabric_outward_details.track_code = inward_details.track_code ".$filterDate1.")  as out_meter ,
            cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
            item_master.item_name,item_master.color_name,item_master.item_description,
            quality_master.quality_name,rack_master.rack_name ,ifnull(purchase_order.po_status,0) as po_status from inward_details
            left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code
            left join inward_master on inward_master.in_code=inward_details.in_code
             left JOIN purchase_order ON purchase_order.pur_code = inward_master.po_code
            left join cp_master on cp_master.cp_id=inward_details.cp_id 
            left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
            left join item_master on item_master.item_code=inward_details.item_code 
            left join quality_master on quality_master.quality_code=item_master.quality_code 
            left join part_master on part_master.part_id=inward_details.part_id 
            left join shade_master on shade_master.shade_id=inward_details.shade_id 
            left join rack_master on rack_master.rack_id=inward_details.rack_id 
            WHERE inward_master.is_opening=1 ".$filterDate);
    //dd(DB::getQueryLog());
        return view('FabricStockData',compact('FabricInwardDetails1','FabricInwardDetails2', 'filterDate','filterDate2','isOpening'));
    }

    public function FabricStockSummaryData(Request $request)
    {
       if ($request->ajax()) 
        { 
            $FabricInwardDetails =DB::select("select inward_details.item_code , item_master.item_name, item_master.color_name, item_master.item_description, sum(inward_details.meter) as meter, quality_master.quality_name, inward_details.item_rate,
                (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.item_code = inward_details.item_code)  as out_meter ,
                cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
                item_master.item_name,item_master.color_name,item_master.item_description,
                quality_master.quality_name,rack_master.rack_name from inward_details
                left join inward_master on inward_master.in_code=inward_details.in_code
                left  join cp_master on cp_master.cp_id=inward_details.cp_id 
                left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
                left join item_master on item_master.item_code=inward_details.item_code 
                left join quality_master on quality_master.quality_code=item_master.quality_code 
                left join part_master on part_master.part_id=inward_details.part_id 
                left join rack_master on rack_master.rack_id=inward_details.rack_id
                group by inward_details.item_code");
           
            return Datatables::of($FabricInwardDetails)
            ->addColumn('item_image_path',function ($row) 
            {
                if($row->item_image_path!='')
                {
                    $item_image_path = '<a href="images/'.$row->item_image_path.'" target="_blank"><img src="images/'.$row->item_image_path.'" width="100" height="80" alt="'.$row->item_code.'"></a>';
                }
                else
                {
                    $item_image_path = 'No Image';
                }
                return $item_image_path;
            })
            ->addColumn('stock',function ($row) 
            {
                $stock = $row->meter-$row->out_meter;
                return $stock;
            })
            ->addColumn('item_value',function ($row) 
            {
                
                $item_value = ($row->meter-$row->out_meter) * $row->item_rate;
               
                return $item_value;
            })
             ->rawColumns(['vendorName','stock','item_value','item_image_path'])
             
             ->make(true);
        }
        return view('FabricSummaryStock');
    }

    public function FabricPOVsGRNDashboard(Request $request)
    {
       
     if ($request->ajax()) {
               
        
            $FabricPOGRNList = DB::select("SELECT  purchase_order.pur_code, purchase_order.pur_date, inward_master.in_code,
            inward_master.in_date, inward_master.invoice_no, inward_master.invoice_date, item_master.item_name,
            item_master.item_description, unit_master.unit_name, inward_details.item_rate,inward_details.item_code,
            (select sum(purchaseorder_detail.item_qty) 
            from purchaseorder_detail where purchaseorder_detail.pur_code=purchase_order.pur_code and
            purchaseorder_detail.item_code=inward_details.item_code) as po_qty, sum(inward_details.meter) as received_qty,
            sum(fabric_checking_details.meter) as pass_meter,sum(fabric_checking_details.reject_short_meter) as reject_short_meter,
            sum(fabric_outward_details.meter) as issue_meter,
            (sum(inward_details.meter)*inward_details.item_rate) as received_Value FROM `inward_details` 
            LEFT join inward_master on inward_master.in_code=inward_details.in_code 
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code 
            LEFT join item_master on item_master.item_code=inward_details.item_code 
            LEFT join unit_master on unit_master.unit_id=item_master.unit_id 
            LEFT join fabric_checking_master on fabric_checking_master.in_code=inward_master.in_code 
            LEFT join fabric_checking_details on fabric_checking_details.chk_code=fabric_checking_master.chk_code 
            LEFT join fabric_outward_details on fabric_outward_details.track_code=inward_details.track_code 
            GROUP by purchase_order.pur_code, inward_details.item_code");
          
            
            return Datatables::of($FabricPOGRNList)
            ->addIndexColumn()
           ->addColumn('PO_value',function ($row) {
    
             $PO_value =round(($row->po_qty * $row->item_rate),2);
    
             return $PO_value;
           })
          ->addColumn('pending_qty',function ($row) {
    
             $pending_qty = round(($row->po_qty - $row->received_qty),2);
    
             return $pending_qty;
           })
          ->addColumn('pass_meter',function ($row) {
    
             $pass_meter = round($row->pass_meter,2);
    
             return $pass_meter;
           }) 
          ->addColumn('reject_short_meter',function ($row) {
    
             $reject_short_meter = round($row->reject_short_meter,2);
    
             return $reject_short_meter;
           })
          ->addColumn('issue_meter',function ($row) {
    
             $issue_meter = round($row->issue_meter,2);
    
             return $issue_meter;
           })
          ->addColumn('balance_meter',function ($row) {
    
             $balance_meter = round((($row->pass_meter) - ($row->reject_short_meter) - ($row->issue_meter)),2);
    
             return $balance_meter;
           })
           ->rawColumns(['PO_value','pending_qty','pass_meter','reject_short_meter','issue_meter','balance_meter'])
           ->make(true);
          }
            
          return view('FabricPOVsGRNDashboard');
        
    }
     
    public function rptFabricOCR()
    {
            //DB::enableQueryLog();
            $FabricOCRList = DB::select("SELECT  purchase_order.pur_code, purchase_order.pur_date, inward_master.in_code,
            inward_master.in_date, inward_master.invoice_no, inward_master.invoice_date, item_master.item_name,
            item_master.item_description, unit_master.unit_name, inward_details.item_rate,
            (select sum(purchaseorder_detail.item_qty) 
            from purchaseorder_detail where purchaseorder_detail.pur_code=purchase_order.pur_code and
            purchaseorder_detail.item_code=inward_details.item_code) as po_qty, sum(meter) as received_qty,
            (sum(meter)*inward_details.item_rate) as received_Value FROM `inward_details` 
            LEFT join inward_master on inward_master.in_code=inward_details.in_code 
            inner join purchase_order on purchase_order.pur_code=inward_master.po_code 
            LEFT join item_master on item_master.item_code=inward_details.item_code 
            LEFT join unit_master on unit_master.unit_id=item_master.unit_id 
            GROUP by purchase_order.pur_code, inward_details.item_code Limit 10");
            //dd(DB::getQueryLog());
          
            
          return view('rptFabricOCR',compact('FabricOCRList'));
        
    }
    
    public function GetVendorWorkOrder()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '4')->get();
        $SalesOrderList=DB::select('select tr_code as sales_order_no from buyer_purchse_order_master where delflag=0 and og_id!=4');
        
        return view('GetVendorWorkOrder',compact('Ledger','SalesOrderList'));
    }
     
    public function rptVendorWorkOrder(Request $request)
    {
        $vendorId = $request->vendorId;
        $sales_order_no = $request->sales_order_no;
        $vw_code = $request->vw_code;
        //DB::enableQueryLog();
        $VendorWorkOrderList = DB::select("");
        //dd(DB::getQueryLog());
        return view('rptVendorWorkOrder',compact('VendorWorkOrderList','vendorId','sales_order_no','vw_code'));
        
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids=base64_decode($id);
        $inwardDetailsData = DB::SELECT('SELECT track_code FROM inward_details WHERE in_code ="'.$ids.'"');
        foreach($inwardDetailsData as $row)
        {
            DB::table('dump_fabric_stock_data')->where('track_name','=', $row->track_code)->delete();
        } 
        
        DB::table('inward_master')->where('in_code', $ids)->delete();
        DB::table('inward_details')->where('in_code', $ids)->delete();
        $detail =FabricTransactionModel::where('tr_code',$ids)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
        
    }
    
    public function rptFabricMovingAndNonMovingStock()
    {
       
        $movingItemsDescription = DB::select("SELECT inward_details.po_code,item_master.quality_code, quality_master.quality_name 
                  FROM inward_details
                  LEFT JOIN item_master ON item_master.item_code = inward_details.item_code
                  LEFT JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
                  LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                  WHERE purchase_order.po_status = 1 GROUP BY item_master.quality_code");
         //DB::enableQueryLog();
         $nonMovingItemsDescription = DB::select("SELECT inward_details.po_code,item_master.quality_code, quality_master.quality_name FROM inward_details
                  LEFT JOIN item_master ON item_master.item_code = inward_details.item_code
                  LEFT JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
                  LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                  WHERE purchase_order.po_status =2 GROUP BY item_master.quality_code");
         //dd(DB::getQueryLog());                      
        return view('rptFabricMovingAndNonMovingStock', compact('movingItemsDescription','nonMovingItemsDescription'));
    }
    
    public function GetMovingReportData()
    {
        $html = '';
        $movingItemsDescription = DB::select("SELECT inward_details.po_code,item_master.quality_code, quality_master.quality_name 
                  FROM inward_details
                  LEFT JOIN item_master ON item_master.item_code = inward_details.item_code
                  LEFT JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
                  LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                  WHERE purchase_order.po_status = 1 GROUP BY item_master.quality_code");
                  
        $movingFabricInwardDetails =DB::select("select inward_details.*,
                         sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details 
                         WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock, purchase_order.pur_code from inward_details 
            left join purchase_order ON purchase_order.pur_code = inward_details.po_code
            where purchase_order.po_status = 1 GROUP BY inward_details.po_code");
                                
        foreach($movingFabricInwardDetails as $moving)
        {
            $SalesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$moving->pur_code."'");
            if(count($SalesOrderNo) > 0)
            {
                 
                $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                where buyer_purchse_order_master.tr_code='".$SalesOrderNo[0]->sales_order_no."'");
               
                if(count($buyerData) > 0 )
                {
                    $ac_name = $buyerData[0]->ac_name;
                }
                else
                {
                    $ac_name = "-";
                }
                
            }
            else
            {
                $ac_name = "-";
            }
           if($moving->stock > 0)
           {
            $html .='<tr>
               <td nowrap>'.$ac_name.'</td>
               <td nowrap>Against PO</td>
               <td class="text-center" nowrap>'.$moving->po_code.'</td>';
                foreach($movingItemsDescription as $item)
                {
                    $MovingFabricInwardDetails =DB::select("select inward_details.po_code,inward_details.in_code,
                         inward_details.cp_id,inward_details.Ac_code,inward_details.item_code,item_master.quality_code,
                         inward_details.part_id,inward_details.shade_id, inward_details.rack_id ,inward_master.po_code as po_codes,
                         inward_master.invoice_no,shade_master.shade_name,
                         sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details 
                         WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock ,
                         cp_master.cp_name,part_master.part_name,ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
                         item_master.item_name,item_master.color_name,item_master.item_description,
                         quality_master.quality_name,rack_master.rack_name from inward_details
                         left join purchase_order ON purchase_order.pur_code = inward_details.po_code
                         left join inward_master on inward_master.in_code=inward_details.in_code
                         left  join cp_master on cp_master.cp_id=inward_details.cp_id 
                         left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
                         left join item_master on item_master.item_code=inward_details.item_code 
                         left join quality_master on quality_master.quality_code=item_master.quality_code 
                         left join part_master on part_master.part_id=inward_details.part_id 
                         left join shade_master on shade_master.shade_id=inward_details.shade_id 
                         left join rack_master on rack_master.rack_id=inward_details.rack_id
                         where purchase_order.po_status = 1 AND inward_details.po_code = '".$moving->po_code."' 
                         AND item_master.quality_code=".$item->quality_code);
              
                   $html .= '<td class="text-center" nowrap>'.round(($MovingFabricInwardDetails[0]->stock ? $MovingFabricInwardDetails[0]->stock : 0),2).'</td>';
                }
            $html .= '</tr>';
            }
        }
          return response()->json(['html' => $html]);
    }
    
    public function GetNonMovingReportData()
    {
          $html = '';
          $nonMovingItemsDescription = DB::select("SELECT inward_details.po_code,item_master.quality_code, quality_master.quality_name FROM inward_details
                  LEFT JOIN item_master ON item_master.item_code = inward_details.item_code
                  LEFT JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
                  LEFT JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                  WHERE purchase_order.po_status =2  GROUP BY item_master.quality_code");
        //dd(DB::getQueryLog());
        
         $nonMovingFabricInwardDetails =DB::select("select inward_details.*,sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) 
                             FROM fabric_outward_details 
                             WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock,purchase_order.pur_code from inward_details 
                                left join purchase_order ON purchase_order.pur_code = inward_details.po_code
                                where purchase_order.po_status = 2  GROUP BY inward_details.po_code");
                                
                                
          foreach($nonMovingFabricInwardDetails as $nonMoving)
          {
                $NonSalesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$nonMoving->pur_code."'");
                if(count($NonSalesOrderNo) > 0)
                {
                   
                    $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                    INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                    where buyer_purchse_order_master.tr_code='".$NonSalesOrderNo[0]->sales_order_no."'");

                    if(count($buyerData) > 0)
                    {
                        $ac_name = $buyerData[0]->ac_name;
                    }
                    else
                    {
                        $ac_name = "-";
                    }
                }
                else
                {
                        $ac_name = "-";
                }
               if($nonMoving->stock > 0)
               {
               $html .= '<tr>
                   <td>'.$ac_name.'</td>';
                        if($nonMoving->is_opening == 1)
                        {
                            $po_Name = 'Opening PO';
                        }
                        else
                        {
                            $po_Name = 'Against PO';
                        }
                   
                   $html .='<td nowrap>'.$po_Name.'</td>
                   <td class="text-center" nowrap>'.$nonMoving->po_code.'</td>';
                    foreach($nonMovingItemsDescription as $nonItem)
                    {
                        //DB::enableQueryLog();
                        $NonFabricInwardDetails =DB::select("select  sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) 
                             FROM fabric_outward_details 
                             WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock 
                             from inward_details
                             left join purchase_order ON purchase_order.pur_code = inward_details.po_code
                             left join inward_master on inward_master.in_code=inward_details.in_code
                             left join item_master on item_master.item_code=inward_details.item_code 
                             left join quality_master on quality_master.quality_code=item_master.quality_code 
                             where purchase_order.po_status = 2 AND inward_details.po_code = '".$nonMoving->po_code."' 
                             AND item_master.quality_code=".$nonItem->quality_code);
                             //dd(DB::getQueryLog());
                        $html .='<td class="text-center" nowrap>'.round(($NonFabricInwardDetails[0]->stock ? $NonFabricInwardDetails[0]->stock : 0),2).'</td>';
                    }
                   
                $html .='</tr>';
                 }
                }
          return response()->json(['html' => $html]);
    }
    
    public function GetOpeningReportData()
    {
        $html = '';
        $openingDetails =DB::select("SELECT sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) 
                             FROM fabric_outward_details 
                             WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock, quality_master.quality_code , GROUP_CONCAT(po_code SEPARATOR ',') as po_code FROM inward_details 
                    INNER JOIN item_master ON item_master.item_code =  inward_details.item_code
                    INNER JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                    WHERE is_opening = 1 GROUP BY inward_details.item_code");
                    
        $openingDetails1 =DB::select("SELECT DISTINCT quality_master.quality_code  FROM inward_details 
                                        INNER JOIN item_master ON item_master.item_code =  inward_details.item_code
                                        INNER JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                                        WHERE is_opening = 1 GROUP BY inward_details.item_code ");
        foreach($openingDetails as $opening)
        {
                $stockData1 = DB::select("SELECT sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) 
                                 FROM fabric_outward_details 
                                 WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock FROM inward_details 
                        INNER JOIN item_master ON item_master.item_code =  inward_details.item_code
                        INNER JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                        WHERE is_opening = 1 AND item_master.quality_code = '".$opening->quality_code."' AND po_code IN('".$opening->po_code."') GROUP BY inward_details.item_code ");
                
                if((isset($stockData1[0]->stock) ? $stockData1[0]->stock : 0) > 0)
                {        
                    $html .= '<tr>
                                <td class="text-center"> - </td>
                                <td class="text-center"> OS </td>
                                <td class="text-center">'.$opening->po_code.'</td>';
                     foreach( $openingDetails1 as $stock)
                     {
                         //DB::enableQueryLog();
                        $stockData = DB::select("SELECT sum(inward_details.meter - (SELECT ifnull(sum(fabric_outward_details.meter),0) 
                                     FROM fabric_outward_details 
                                     WHERE fabric_outward_details.track_code = inward_details.track_code))  as stock FROM inward_details 
                            INNER JOIN item_master ON item_master.item_code =  inward_details.item_code
                            INNER JOIN quality_master ON quality_master.quality_code = item_master.quality_code
                            WHERE is_opening = 1 AND item_master.quality_code = '".$stock->quality_code."' AND po_code IN('".$opening->po_code."') GROUP BY inward_details.item_code ");
                           //dd(DB::getQueryLog()); 
                        if(count($stockData) > 0)
                        {
                           $stock =  $stockData[0]->stock;
                        }
                        else
                        {
                            $stock = 0;
                        } 
                            $html .= '<td class="text-center">'.number_format($stock,2).'</td>';
                     }
                     $html .= '</tr>';
                } 
        }
          return response()->json(['html' => $html]);
    }
    
    public function getItemMinMaxFromPO(Request $request)
    { 
        $po_code= base64_decode($request->input('po_code'));
        $item_code= $request->input('item_code');
        $color_id= $request->input('color_id');
    //   DB::enableQueryLog();
        $Rate = DB::select("select (purchaseorder_detail.item_qty - 
        (select sum(fabric_summary_grn_detail.item_qty) from fabric_summary_grn_detail where 
        fabric_summary_grn_detail.po_code='". $po_code."' and fabric_summary_grn_detail.item_code='".$item_code."' 
        and fabric_summary_grn_detail.color_id='".$color_id."')) as item_qty , item_rate from    purchaseorder_detail
        where purchaseorder_detail.pur_code='". $po_code."' and item_code='".$item_code."' and color_id='".$color_id."'");
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return json_encode($Rate);
    }
    
        
    
    public function FabricStockDataTrial(Request $request)
    {
        //DB::enableQueryLog();
         $Financial_Year=DB::select("SELECT fin_year_id, fin_year_name, fdate, tdate, userId, delflag, created_at, updated_at FROM financial_year_master where fin_year_id=(select max(fin_year_id) from financial_year_master)");
        //dd(DB::getQueryLog());
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='FabricStockDataTrial?currentDate=".date('Y-m-d')."';</script>";
        }
        if($currentDate != "")
        {
            // $in_date = " AND inward_details.in_date BETWEEN '2023-04-01' AND '".$currentDate."'"; 
            // $fout_date =  " AND fabric_outward_details.fout_date BETWEEN '2023-04-01' AND '".$currentDate."'";
            // $chk_date =  " AND fabric_checking_details.chk_date BETWEEN '2023-04-01' AND '".$currentDate."'";
            
            $in_date = " AND inward_details.in_date  <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
        
            $fout_date = " AND fabric_outward_details.fout_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
            $chk_date = " AND fabric_checking_details.chk_date <= DATE_FORMAT(LAST_DAY('".$Financial_Year[0]->fdate."' - INTERVAL 1 MONTH), '".$currentDate."')";
        }
        else
        {
            $in_date = "";
            $fout_date = ""; 
            $chk_date = "";
        }
                  
 
        
        $FabricInwardDetails1 =DB::select("select inward_details.* , ifnull(fabric_checking_details.meter,inward_details.meter) as ActualMeter,
        inward_master.po_code as po_codes, inward_master.invoice_no, shade_master.shade_name,inward_master.in_code,
        (SELECT ifnull(sum(fabric_outward_details.meter),0) FROM fabric_outward_details WHERE fabric_outward_details.track_code = inward_details.track_code ".$fout_date.")  as out_meter,
         ledger_master.ac_name,item_master.dimension,item_master.item_image_path,
        item_master.item_name,item_master.color_name,item_master.item_description,
        quality_master.quality_name,rack_master.rack_name,ifnull(purchase_order.po_status,0) as po_status from inward_details
        left JOIN purchase_order ON purchase_order.pur_code = inward_details.po_code
        left join inward_master on inward_master.in_code=inward_details.in_code
        left JOIN fabric_checking_details ON fabric_checking_details.track_code=inward_details.track_code 
        left join ledger_master on ledger_master.ac_code=inward_details.Ac_code 
        left join item_master on item_master.item_code=inward_details.item_code 
        left join quality_master on quality_master.quality_code=item_master.quality_code  
        left join shade_master on shade_master.shade_id=fabric_checking_details.shade_id 
        left join rack_master on rack_master.rack_id=inward_details.rack_id WHERE 1 ".$in_date);
    
        $filterDate = "";
        $filterDate1 = "";
        $filterDate2 = "";
        $FabricInwardDetails2 = "";  
        $isOpening = "";  
        return view('FabricStockDataTrial',compact('filterDate','filterDate1','FabricInwardDetails1','isOpening','currentDate','FabricInwardDetails2','in_date','fout_date','chk_date'));
    }

    public function FabricStocks1()
    {
        DB::table('dump_fabric_stock_data')->delete();
        
        $fabricData = DB::SELECT("select inward_details.in_date,'',ledger_master.ac_name as suplier_name,inward_master.po_code as po_no,inward_details.in_code as grn_no,
           inward_master.invoice_no,item_master.item_code,item_master.item_image_path as preview,shade_master.shade_name as shade_no,item_master.item_name,
            quality_master.quality_name, item_master.color_name as color,item_master.item_description,job_status_master.job_status_name as po_status,
            job_status_master.job_status_id,inward_details.track_code as track_name, inward_details.meter as grn_qty, 
            inward_details.item_rate as rate,inward_details.rack_id from inward_details 
            left join inward_master on inward_master.in_code=inward_details.in_code
            left JOIN purchase_order ON purchase_order.pur_code = inward_master.po_code
            left JOIN job_status_master ON job_status_master.job_status_id = purchase_order.po_status
            left join ledger_master on ledger_master.ac_code=inward_details.Ac_code                    
            left join item_master on item_master.item_code=inward_details.item_code 
            left join quality_master on quality_master.quality_code=item_master.quality_code  
            left join shade_master on shade_master.shade_id=inward_details.shade_id  
            ORDER BY `po_status` DESC");
       
           
          foreach($fabricData as $row)
          {  
                $in_date = str_replace('"', "", $row->in_date);
                $suplier_name = str_replace('"', "", $row->suplier_name);
                $po_no = str_replace('"', "", $row->po_no);
                $grn_no = str_replace('"', "", $row->grn_no);
                $invoice_no = str_replace('"', "", $row->invoice_no);
                $item_code = str_replace('"', "", $row->item_code);
                $preview = str_replace('"', "", $row->preview);
                $shade_no = str_replace('"', "", $row->shade_no);
                $item_name = str_replace('"', "", $row->item_name);
                $quality_name = str_replace('"', "", $row->quality_name);
                $color = str_replace('"', "", $row->color);
                $item_description = str_replace('"', "", $row->item_description);
                $po_status = str_replace('"', "", $row->po_status);
                $job_status_id = str_replace('"', "", $row->job_status_id);
                $track_name = str_replace('"', "", $row->track_name);
                $grn_qty = str_replace('"', "", $row->grn_qty);
                $rate = str_replace('"', "", $row->rate);
                $rack_id = str_replace('"', "", $row->rack_id);
                  
                $checking_width =DB::select("select width,fabric_check_status_master.fcs_name,meter as QCQty FROM fabric_checking_details 
                     LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                     WHERE track_code = '".$row->track_name."'");
                     
                $QCQty = isset($checking_width[0]->QCQty) ? $checking_width[0]->QCQty : 0;
                
                $outwardData = DB::SELECT("select sum(meter) as outward_qty,fout_date FROM fabric_outward_details WHERE track_code ='".$row->track_name."'");
                $ind_outward_qty1 = "";
                $ind_outward_qty = 0;
             
                
                $fout_date = isset($outwardData[0]->fout_date) ? $outwardData[0]->fout_date : "";
                $outward_qty = isset($outwardData[0]->outward_qty) ? $outwardData[0]->outward_qty : 0; 
                
               //DB::enableQueryLog();

                 DB::SELECT('INSERT INTO dump_fabric_stock_data(in_date,fout_date,suplier_name,po_no,grn_no,invoice_no,item_code,preview,shade_no,item_name,quality_name,
                        color,item_Description,status,track_name,grn_qty,qc_qty,outward_qty,rate,rack_name,tr_type,ind_outward_qty)
                        select "'.$in_date.'","'.$fout_date.'","'.$suplier_name.'","'.$po_no.'","'.$grn_no.'","'.$invoice_no.'","'.$item_code.'","'.$preview.'","'.$shade_no.'","'.$item_name.'",
                        "'.$quality_name.'","'.$color.'","'.$item_description.'","'.$po_status.'","'.$job_status_id.'",'.$track_name.'","'.$grn_qty.'","'.$QCQty.'","'.$outward_qty.'","'.$rate.'","'.$rack_id.'",1,"'.$ind_outward_qty.'"');
                        
                $outwardData1 = DB::SELECT("select meter as outward_qty,fout_date FROM fabric_outward_details WHERE track_code ='".$row->track_name."'");
                
                foreach($outwardData1 as $OD)
                {
                    $ind_outward_qty1 = $OD->fout_date."=>".$OD->outward_qty.",".$ind_outward_qty1;
                }

                $ind_outward_qty = rtrim($ind_outward_qty1,","); 
                
                DB::table('dump_fabric_stock_data')->where('po_no', $row->po_no)->where('item_code', $row->item_code)->update(['ind_outward_qty' => $ind_outward_qty]);
          }
     
        return 1;
    }
    
    public function UpdateFoutDumpData()
    {
        $dumpedData = DB::SELECT("SELECT * FROM dump_fabric_stock_data"); 
           
        foreach($dumpedData as $row)
        {
            $foutData = DB::select("select fout_date FROM fabric_outward_details WHERE fabric_outward_details.track_code='".$row->track_name ."' GROUP BY track_code");

            $fout_date = isset($foutData[0]->fout_date) ? $foutData[0]->fout_date : '';
            
            DB::SELECT("UPDATE dump_fabric_stock_data SET fout_date = '".$fout_date."' WHERE track_name='".$row->track_name."'");
        }
    }
    
    public function RefreshDumpData()
    {
          $dumpedData = DB::SELECT("SELECT * FROM dump_fabric_stock_data"); 
           
           foreach($dumpedData as $row)
           {
                $salesOrderNo=DB::select("select distinct sales_order_no from purchaseorder_detail where  pur_code='".$row->po_no ."'");
                         
                if(count($salesOrderNo) > 0)
                {
                     $buyerData = DB::select("select ledger_master.ac_name from buyer_purchse_order_master 
                     INNER JOIN  ledger_master ON ledger_master.ac_code = buyer_purchse_order_master.Ac_code
                     where buyer_purchse_order_master.tr_code='".$salesOrderNo[0]->sales_order_no."'");
                      
                        $buyer_name = isset($buyerData[0]->ac_name) ? $buyerData[0]->ac_name : "-"; 
                }
                else
                {
                    $buyer_name = "-";
                }
                
                
                $checking_width =DB::select("select  width,fabric_check_status_master.fcs_name,meter as QCQty FROM fabric_checking_details 
                     LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                     WHERE track_code = '".$row->track_name."'");
                     
                $JobStatusList=DB::select("select job_status_id,  job_status_name from job_status_master WHERE job_status_id ='".$row->status."'");
                   
                $width = isset($checking_width[0]->width) ? $checking_width[0]->width : 0;
                $fcs_name = isset($checking_width[0]->fcs_name) ? $checking_width[0]->fcs_name : '';
                $QCQty = isset($checking_width[0]->QCQty) ? $checking_width[0]->QCQty : 0;
                $job_status_name = isset($JobStatusList[0]->job_status_name) ? $JobStatusList[0]->job_status_name : '';
                
                DB::SELECT("UPDATE dump_fabric_stock_data SET buyer_name = '".$buyer_name."', width = '".$width."',fcs_name='".$fcs_name."',qc_qty='".$QCQty."',status='".$job_status_name."' WHERE track_name='".$row->track_name."'");
           }
    }
    
    public function FabricStockDataTrialCloned(Request $request)
    {  
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='FabricStockDataTrialCloned?currentDate=".date('Y-m-d')."';</script>";
        }
        return view('FabricStockDataTrialCloned', compact('currentDate'));
    }
      
    public function LoadFabricStockDataTrialCloned1(Request $request)
    {  
                
        $currentDate = $request->currentDate ? $request->currentDate : "";
        $job_status_id = $request->job_status_id ? $request->job_status_id : "";
        
        //DB::enableQueryLog();
        if($job_status_id == 1)
        {
            $FabricInwardDetails =DB::select("SELECT  fabric_check_status_master.fcs_name, dump_fabric_stock_data.*, inward_master.invoice_date,
                                (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date
                                AND df.in_date <= '".$currentDate."') as gq,
                                COALESCE(
                                    (SELECT trade_name 
                                     FROM ledger_details 
                                     WHERE sr_no = purchase_order.bill_to 
                                     LIMIT 1)
                                ) AS trade_name,
                        
                                COALESCE(
                                    (SELECT site_code 
                                     FROM ledger_details 
                                     WHERE sr_no = purchase_order.bill_to 
                                     LIMIT 1)
                                ) AS site_code,
                                LM1.ac_short_name as suplier_name
                                FROM dump_fabric_stock_data
                                LEFT JOIN inward_master ON inward_master.in_code = dump_fabric_stock_data.grn_no
                                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_master.Ac_code
                                LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_data.po_no
                                WHERE dump_fabric_stock_data.in_date <='".$currentDate."' AND job_status_id = 1");
        }
        else if($job_status_id == 2)
        {
            $FabricInwardDetails =DB::select("SELECT  fabric_check_status_master.fcs_name, dump_fabric_stock_data.*, inward_master.invoice_date,
                                (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                COALESCE(
                                    (SELECT trade_name 
                                     FROM ledger_details 
                                     WHERE sr_no = purchase_order.bill_to 
                                     LIMIT 1)
                                ) AS trade_name,
                        
                                COALESCE(
                                    (SELECT site_code 
                                     FROM ledger_details 
                                     WHERE sr_no = purchase_order.bill_to 
                                     LIMIT 1)
                                ) AS site_code,
                                LM1.ac_short_name as suplier_name
                                FROM dump_fabric_stock_data 
                                LEFT JOIN inward_master ON inward_master.in_code = dump_fabric_stock_data.grn_no
                                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_master.Ac_code
                                LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_data.po_no
                                WHERE dump_fabric_stock_data.in_date <='".$currentDate."' AND job_status_id IN(0,2)");
        }
        else
        {
            
           // DB::enableQueryLog();
            $FabricInwardDetails =DB::select("SELECT  fabric_check_status_master.fcs_name, dump_fabric_stock_data.*, inward_master.invoice_date,
                                (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                COALESCE(
                                    (SELECT trade_name 
                                     FROM ledger_details 
                                     WHERE sr_no = purchase_order.bill_to 
                                     LIMIT 1)
                                ) AS trade_name,
                        
                                COALESCE(
                                    (SELECT site_code 
                                     FROM ledger_details 
                                     WHERE sr_no = purchase_order.bill_to 
                                     LIMIT 1)
                                ) AS site_code,
                                LM1.ac_short_name as suplier_name
                                FROM dump_fabric_stock_data 
                                LEFT JOIN inward_master ON inward_master.in_code = dump_fabric_stock_data.grn_no
                                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_master.Ac_code
                                LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_data.po_no
                                WHERE dump_fabric_stock_data.in_date <='".$currentDate."'");
            //dd(DB::getQueryLog());
                                
        }
        //dd(DB::getQueryLog());
        $html = [];
        $total_value = 0;
        $total_stock = 0; 
        foreach($FabricInwardDetails as $row)
        {
                $grn_qty = isset($row->gq) ? $row->gq : 0; 
                $ind_outward1 = (explode(",",$row->ind_outward_qty));
                $q_qty = 0; 
                
                foreach($ind_outward1 as $indu)
                {
                    
                     $ind_outward2 = (explode("=>",$indu));
                     $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                    
                     if($ind_outward2[0] <= $currentDate)
                     {
                         $q_qty += $q_qty1;
                     } 
                }
               
                if($row->qc_qty > 0 )
                {
                    $stocks =  $row->qc_qty- $q_qty;
                } 
                else
                {
                     $stocks =  $row->gq - $q_qty;
                }
                $total_value += round($stocks * $row->rate,2);  
                $total_stock +=  round($stocks,2);
                        
                if($row->po_status == "Moving")
                {
                    $status = 'Moving';
                }
                else
                {
                    $status = 'Non Moving';
                }
                   
                $buyer_name = $row->buyer_name;
                $closeDate = '';
                $invoice_date = '';
                if($row->closeDate != '')
                {
                    $closeDate = date("d-M-Y", strtotime($row->closeDate));
                }
                
                if($row->invoice_date != '')
                {
                    $invoice_date = date("d-M-Y", strtotime($row->invoice_date));
                }
                
                
                if($row->site_code != '')
                {
                    $bill_to = $row->trade_name.'('.$row->site_code.')';
                }
                else
                {
                    $bill_to = $row->trade_name;
                }
                 
                if($bill_to =='')
                {
                    // DB::enableQueryLog();
                     $tradeData =DB::select("SELECT ledger_details.trade_name FROM ledger_details LEFT JOIN ledger_master ON ledger_master.ac_code = ledger_details.ac_code WHERE ac_short_name LIKE '%".$row->suplier_name."%' LIMIT 1");
                    //  dd(DB::getQueryLog());
                     $tn = isset($tradeData[0]->trade_name) ? $tradeData[0]->trade_name : "";
                     $sc = isset($tradeData[0]->site_code) ? $tradeData[0]->site_code : "";
                     
                     if($sc != '')
                     {
                            $bill_to = $tn.'('.$sc.')';
                     }
                     else
                     {
                            $bill_to = $tn;
                     } 
                }
                
                $html[] =  array(
                        'suplier_name'=>$row->suplier_name,
                        'bill_to'=>$bill_to,
                        'buyer_name'=>$buyer_name,
                        'status'=>$status,
                        'po_status'=>$row->po_status,
                        'po_no'=>$row->po_no,
                        'closeDate'=>$closeDate,
                        'grn_no'=>$row->grn_no,
                        'invoice_no'=>$row->invoice_no,
                        'invoice_date'=>$invoice_date,
                        'item_code'=>$row->item_code, 
                        'shade_no'=>$row->shade_no,
                        'fcs_name'=>$row->fcs_name,
                        'item_name'=>$row->item_name,
                        'width'=>$row->width,
                        'quality_name'=>$row->quality_name,
                        'color'=>$row->color,
                        'item_description'=>$row->item_description,
                        'track_name'=>$row->track_name,
                        'rack_name'=>$row->rack_name,
                        'gq'      => number_format(round($row->gq, 2), 2, '.', ','),
                        'qc_qty'  => number_format(round($row->qc_qty, 2), 2, '.', ','),
                        'q_qty'   => number_format(round($q_qty, 2), 2, '.', ','),
                        'stocks'  => number_format(round($stocks, 2), 2, '.', ','),
                        'rate'    => number_format(round($row->rate, 2), 2, '.', ','),
                        'value'   => number_format(round($stocks * $row->rate, 2), 2, '.', ','),
                    );     
        }
        
        $jsonData = json_encode($html);  
    
      return response()->json(['html' => $jsonData,'total_stock'=>round($total_stock/100000,2),'currentDate'=>$currentDate,'total_value'=>round($total_value/100000,2)]);

    }
    
    public function FabricStockDataTrialCloned1(Request $request)
    {  
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='FabricStockDataTrialCloned1?currentDate=".date('Y-m-d')."';</script>";
        }
        return view('FabricStockDataTrialCloned1', compact('currentDate'));
    }
      
    public function LoadFabricStockDataTrialCloned2(Request $request)
    {  
        $currentDate = $request->currentDate ?? "";
        $job_status_id = $request->job_status_id ?? "";
    
        if ($job_status_id == 1) 
        {
            $FabricInwardDetails = DB::select("SELECT fabric_check_status_master.fcs_name, dump_fabric_stock_data.*, inward_master.invoice_date,
                (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df 
                 WHERE df.track_name = dump_fabric_stock_data.track_name 
                 AND df.in_date = dump_fabric_stock_data.in_date 
                 AND df.in_date <= '".$currentDate."') as gq,
                COALESCE(
                    (SELECT trade_name 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS trade_name,
        
                COALESCE(
                    (SELECT site_code 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS site_code,
                LM1.ac_short_name as suplier_name
                FROM dump_fabric_stock_data
                LEFT JOIN inward_master ON inward_master.in_code = dump_fabric_stock_data.grn_no
                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_master.Ac_code
                LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_data.po_no
                WHERE dump_fabric_stock_data.in_date <= '".$currentDate."' AND job_status_id = 1");
        } 
        elseif ($job_status_id == 2) 
        {
            $FabricInwardDetails = DB::select("SELECT fabric_check_status_master.fcs_name, dump_fabric_stock_data.*, inward_master.invoice_date,
                (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df 
                 WHERE df.track_name = dump_fabric_stock_data.track_name 
                 AND df.in_date = dump_fabric_stock_data.in_date 
                 AND df.in_date <= '".$currentDate."' ) as gq,
                COALESCE(
                    (SELECT trade_name 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS trade_name,
        
                COALESCE(
                    (SELECT site_code 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS site_code,
                LM1.ac_short_name as suplier_name
                FROM dump_fabric_stock_data 
                LEFT JOIN inward_master ON inward_master.in_code = dump_fabric_stock_data.grn_no
                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_master.Ac_code
                LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_data.po_no
                WHERE dump_fabric_stock_data.in_date <= '".$currentDate."' AND job_status_id IN(0,2)");
        } 
        else 
        {
            $FabricInwardDetails = DB::select("SELECT fabric_check_status_master.fcs_name, dump_fabric_stock_data.*, inward_master.invoice_date,
                (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df 
                 WHERE df.track_name = dump_fabric_stock_data.track_name 
                 AND df.in_date = dump_fabric_stock_data.in_date 
                 AND df.in_date <= '".$currentDate."' ) as gq,
                COALESCE(
                    (SELECT trade_name 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS trade_name,
        
                COALESCE(
                    (SELECT site_code 
                     FROM ledger_details 
                     WHERE sr_no = purchase_order.bill_to 
                     LIMIT 1)
                ) AS site_code,
                LM1.ac_short_name as suplier_name
                FROM dump_fabric_stock_data 
                LEFT JOIN inward_master ON inward_master.in_code = dump_fabric_stock_data.grn_no
                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                LEFT JOIN ledger_master as LM1 ON LM1.ac_code = inward_master.Ac_code
                LEFT JOIN purchase_order ON purchase_order.pur_code = dump_fabric_stock_data.po_no
                WHERE dump_fabric_stock_data.in_date <= '".$currentDate."'");
        } 
 
        $html = [];
        $total_value = 0;
        $total_stock = 0;
    
        $currentDateObj = \Carbon\Carbon::parse($currentDate);
    
        foreach ($FabricInwardDetails as $row) {
            $grn_qty = $row->gq ?? 0;
            $ind_outward1 = explode(",", $row->ind_outward_qty);
            $q_qty = 0;
    
            foreach ($ind_outward1 as $indu) {
                $ind_outward2 = explode("=>", $indu);
                $q_qty1 = $ind_outward2[1] ?? 0;
    
                if ($ind_outward2[0] <= $currentDate) {
                    $q_qty += $q_qty1;
                }
            }
    
            $stocks = ($row->qc_qty > 0) ? $row->qc_qty - $q_qty : $grn_qty - $q_qty;
            $stocks = round($stocks, 2);
            $value = round($stocks * $row->rate, 2);
    
            $total_value += $value;
            $total_stock += $stocks;
    
            $status = ($row->po_status == "Moving") ? "Moving" : "Non Moving";
            $buyer_name = $row->buyer_name;
    
            $inDateObj = \Carbon\Carbon::parse($row->in_date);
            $ageInDays = $inDateObj->diffInDays($currentDateObj);
    
            $stock_0_30 = $stock_31_60 = $stock_61_90 = $stock_91_180 = $stock_180_plus = 0;
            $value_0_30 = $value_31_60 = $value_61_90 = $value_91_180 = $value_180_plus = 0;
    
            if ($ageInDays <= 30) {
                $stock_0_30 = $stocks;
                $value_0_30 = $value;
            } elseif ($ageInDays <= 60) {
                $stock_31_60 = $stocks;
                $value_31_60 = $value;
            } elseif ($ageInDays <= 90) {
                $stock_61_90 = $stocks;
                $value_61_90 = $value;
            } elseif ($ageInDays <= 180) {
                $stock_91_180 = $stocks;
                $value_91_180 = $value;
            } else {
                $stock_180_plus = $stocks;
                $value_180_plus = $value;
            }
    
            if($row->site_code != '')
            {
                $bill_to = $row->trade_name.'('.$row->site_code.')';
            }
            else
            {
                $bill_to = $row->trade_name;
            }
             
            if($bill_to =='')
            {
                // DB::enableQueryLog();
                 $tradeData =DB::select("SELECT ledger_details.trade_name FROM ledger_details LEFT JOIN ledger_master ON ledger_master.ac_code = ledger_details.ac_code WHERE ac_short_name LIKE '%".$row->suplier_name."%' LIMIT 1");
                //  dd(DB::getQueryLog());
                 $tn = isset($tradeData[0]->trade_name) ? $tradeData[0]->trade_name : "";
                 $sc = isset($tradeData[0]->site_code) ? $tradeData[0]->site_code : "";
                 
                 if($sc != '')
                 {
                        $bill_to = $tn.'('.$sc.')';
                 }
                 else
                 {
                        $bill_to = $tn;
                 } 
            }
            
            $html[] = [
                'suplier_name' => $row->suplier_name,
                'bill_to'=>$bill_to,
                'buyer_name' => $buyer_name,
                'status' => $status,
                'po_status' => $row->po_status,
                'po_no' => $row->po_no,
                'closeDate' => $row->closeDate,
                'grn_no' => $row->grn_no,
                'in_date' => $row->in_date,
                'invoice_no' => $row->invoice_no,
                'invoice_date' => $row->invoice_date,
                'item_code' => $row->item_code,
                'preview' => $row->preview,
                'shade_no' => $row->shade_no,
                'fcs_name' => $row->fcs_name,
                'item_name' => $row->item_name,
                'width' => $row->width,
                'quality_name' => $row->quality_name,
                'color' => $row->color,
                'item_description' => $row->item_description,
                'track_name' => $row->track_name,
                'rack_name' => $row->rack_name,
                'gq' => round($row->gq, 2),
                'qc_qty' => round($row->qc_qty, 2),
                'q_qty' => round($q_qty, 2),
                'stocks' => $stocks,
                'rate' => $row->rate,
                'value' => $value,
                'stock_0_30' => $stock_0_30,
                'value_0_30' => $value_0_30,
                'stock_31_60' => $stock_31_60,
                'value_31_60' => $value_31_60,
                'stock_61_90' => $stock_61_90,
                'value_61_90' => $value_61_90,
                'stock_91_180' => $stock_91_180,
                'value_91_180' => $value_91_180,
                'stock_180_plus' => $stock_180_plus,
                'value_180_plus' => $value_180_plus,
            ];
        }
    
        return response()->json([
            'html' => json_encode($html),
            'total_stock' => round($total_stock / 100000, 2),
            'currentDate' => $currentDate,
            'total_value' => round($total_value / 100000, 2)
        ]);
    }

     
    public function VendorPurchaseOrderDemo(Request $request)
    { 
        $currentDate = $request->currentDate ? $request->currentDate : "";
        if($currentDate == "")
        { 
            echo "<script>location.href='VendorPurchaseOrderDemo?currentDate=".date('Y-m-d')."';</script>";
        }
        return view('VendorPurchaseOrderDemo', compact('currentDate')); 
   
    }
    
    public function LoadFabricStockDataTrialCloned(Request $request)
    {  
                
        $currentDate = $request->currentDate ? $request->currentDate : "";
        $job_status_id = $request->job_status_id ? $request->job_status_id : "";
        
        //DB::enableQueryLog();
        if($job_status_id == 1)
        {
                $FabricInwardDetails =DB::select("SELECT fabric_check_status_master.fcs_name, fabric_checking_details.status_id, dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$currentDate."') as oq 
                                FROM dump_fabric_stock_data
                                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                                WHERE in_date <='".$currentDate."' AND job_status_id = 1 OR  in_date <='".$currentDate."' AND closeDate > '".$currentDate."'");

        }  
        else if($job_status_id == 2)
        {
               $FabricInwardDetails =DB::select("SELECT fabric_check_status_master.fcs_name, fabric_checking_details.status_id, dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$currentDate."') as oq 
                                FROM dump_fabric_stock_data 
                                LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name 
                                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id
                                WHERE in_date <='".$currentDate."' AND job_status_id IN(0,1,2)  AND closeDate <='".$currentDate."'");
        }
        else
        {
            // DB::enableQueryLog();
            $FabricInwardDetails =DB::select("SELECT fabric_check_status_master.fcs_name, fabric_checking_details.status_id, dump_fabric_stock_data.*, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
                                (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$currentDate."') as oq 
                                FROM dump_fabric_stock_data LEFT JOIN fabric_checking_details ON fabric_checking_details.track_code = dump_fabric_stock_data.track_name
                                LEFT JOIN fabric_check_status_master ON fabric_check_status_master.fcs_id = fabric_checking_details.status_id WHERE in_date <='".$currentDate."'");
            //dd(DB::getQueryLog());
        }                               
     
        $html = [];
        $total_value = 0;
        $total_stock = 0; 
        foreach($FabricInwardDetails as $row)
        {
            $outward_qty = isset($row->oq) ? $row->oq : 0; 
            $grn_qty = isset($row->gq) ? $row->gq : 0; 
            $ind_outward1 = (explode(",",$row->ind_outward_qty));
            $q_qty = 0; 
            
            // foreach($ind_outward1 as $indu)
            // {
            //     $ind_outward2 = explode("=>", $indu);
            //     $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
            
            //     // Check if $ind_outward2[0] is numeric before comparison
            //     if(is_numeric($ind_outward2[0])) {
            //         if($ind_outward2[0] <= $currentDate)
            //         {
            //             $q_qty += $q_qty1;
            //         }
            //         else
            //         {
            //             $q_qty = 0;
            //         }
            //     } else {
            //         // Handle the case where $ind_outward2[0] is non-numeric
            //         // You may log an error or handle it according to your application's logic
            //     }
            // }
            
            foreach($ind_outward1 as $indu)
            {
                
                 $ind_outward2 = (explode("=>",$indu));
                 $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                
                 if($ind_outward2[0] <= $currentDate)
                 {
                     $q_qty += $q_qty1;
                 }
                 else
                 {
                      $q_qty =  0;
                 }
            }
            
            // echo '<pre>';print_r($ind_outward1);exit;
            if($row->qc_qty > 0 )
            {
                $stocks =  $row->qc_qty- $q_qty;
            } 
            else
            {
                 $stocks =  $row->gq - $q_qty;
            }
            // if($stocks > 0)
            // {
                // $html .=' <tr> 
                //             <td style="text-align:center; white-space:nowrap">'.$row->suplier_name.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->buyer_name.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->po_no.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->grn_no.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->invoice_no.'</td>
                //             <td>'.$row->item_code.'</td>';
                //             if($row->preview!='')
                //             {
                //                 $html .='<td><a href="'.url('images/'.$row->preview).'" target="_blank"><img src="'.url('thumbnail/'.$row->preview).'" alt="'.$row->item_code.'"></a></td>';
                //             }
                //             else
                //             {
                //                 $html .='<td>No Image</td>';
                //             }
                //             $html .='<td style="text-align:center; white-space:nowrap">'.$row->shade_no.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->item_name.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->width.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->quality_name.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->color.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->item_description.'</td>
                //             <td>'.$row->track_name.'</td>
                //             <td style="text-align:center; white-space:nowrap">'.$row->rack_name.'</td>
                //             <td style="text-align:right; ">'.($row->gq).'</td>
                //             <td style="text-align:right; ">'.$row->qc_qty.'</td>
                //             <td style="text-align:right; ">'.($q_qty).'</td>';
                            
                //             $html .='<td style="text-align:right; ">'.($stocks).'</td>
                //             <td style="text-align:right; ">'.$row->rate.'</td>
                //             <td style="text-align:right; ">'.($stocks * $row->rate).'</td>
                //          </tr>';
                        $total_value += ($stocks) * $row->rate;  
                        $total_stock +=  $stocks;
             
                if($currentDate < $row->closeDate)
                {
                    $status = 'Moving';
                }
                else
                {
                    $status = $row->po_status;
                }
                
                $buyer_name = $row->buyer_name;
                if($buyer_name == '')
                { 
                    //DB::enableQueryLog();
                    $buyerData = DB::SELECT("SELECT ledger_master.ac_short_name FROM inward_details INNER JOIN ledger_master ON ledger_master.ac_code = inward_details.buyer_id WHERE inward_details.in_code='".$row->grn_no."' AND po_code='".$row->po_no."'");
                    //dd(DB::getQueryLog());
                    $buyer_name = isset($buyerData[0]->ac_short_name) ? $buyerData[0]->ac_short_name : '-';
                }   
                
                $html[] =  array(
                        'suplier_name'=>$row->suplier_name,
                        'buyer_name'=>$buyer_name,
                        'status'=>$status, 
                        'po_no'=>$row->po_no,
                        'grn_no'=>$row->grn_no,
                        'invoice_no'=>$row->invoice_no,
                        'item_code'=>$row->item_code,
                        'preview'=>$row->preview,
                        'shade_no'=>$row->shade_no,
                        'fcs_name'=>$row->fcs_name,
                        'item_name'=>$row->item_name,
                        'width'=>$row->width,
                        'quality_name'=>$row->quality_name,
                        'color'=>$row->color,
                        'item_description'=>$row->item_description,
                        'track_name'=>$row->track_name,
                        'rack_name'=>$row->rack_name,
                        'gq'=>round($row->gq,2), 
                        'qc_qty'=>round($row->qc_qty,2), 
                        'q_qty'=>round($q_qty,2), 
                        'stocks'=>round($stocks,2), 
                        'rate'=>$row->rate,
                        'value'=>round($stocks * $row->rate,2), 
                    );        
            // }
        }
        
        $jsonData = json_encode($html);
        
        // // Output the JSON data
        // $totalInwardData = DB::select("SELECT( (SELECT sum(grn_qty) FROM `dump_fabric_stock_data` WHERE `in_date` <= '".$currentDate."') - (SELECT sum(outward_qty) FROM `dump_fabric_stock_data` WHERE fout_date <= '".$currentDate."')) as stock");
        // $totalInwardData = DB::select("select sum(grn_qty) as inward from dump_fabric_stock_data WHERE in_date <='".$currentDate."'");
        // $totalOutwardData = DB::select("select sum(outward_qty) as outward from dump_fabric_stock_data  WHERE fout_date <='".$currentDate."'");
        
        // $total_stock = isset($totalInwardData[0]->stock) ? $totalInwardData[0]->stock : 0; 
        // $total_outward = isset($totalOutwardData[0]->outward) ? $totalOutwardData[0]->outward : 0;
        // $total_stock = round($total_inward - $total_outward,2);    
    
      return response()->json(['html' => $jsonData,'total_stock'=>round($total_stock/100000,2),'currentDate'=>$currentDate,'total_value'=>round($total_value/100000,2)]);

    }
    
    public function RunCronJob()
    { 
         date_default_timezone_set("Asia/Calcutta"); 
         $time = date("H:i", strtotime("+30 seconds"));
         
         DB::table('syncronization_time_mgmt')->update(['sync_table'=>0]);
         DB::table('syncronization_time_mgmt')->where('stmt_type','=',1)->update(['start_time' => $time, 'status' => 0,'sync_table'=>1]);
    }

    public function CheckFabricEntryInChecking(Request $request)
    { 
        
        $fabricChecking = DB::SELECT("SELECT count(*) as total_count FROM fabric_checking_details WHERE track_code='".$request->track_code."'");
        
        $total_count = $fabricChecking[0]->total_count;
        
        return response()->json(['total_count' => $total_count]);
    }
    
    public function DeleteDataFromDump(Request $request)
    { 
      //  DB::table('dump_fabric_stock_data')->where('track_name','=', $request->track_code)->delete();
        return 1;
    }
    
    public function InventoryAgingReport()
    {
        return view('InventoryAgingReport');
    }
     
    // public function LoadInventoryAgingReport()
    // {
        
    //     $currentDate = date('Y-m-d');
    //     $FabricInwardDetails =DB::select("SELECT dump_fabric_stock_data.*, item_master.item_name, (SELECT sum(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name= dump_fabric_stock_data.track_name AND df.item_code= dump_fabric_stock_data.item_code AND df.in_date = dump_fabric_stock_data.in_date AND df.in_date <= '".$currentDate."' ) as gq,
    //                             (SELECT sum(outward_qty) FROM dump_fabric_stock_data AS df1 WHERE df1.track_name= dump_fabric_stock_data.track_name AND df1.item_code= dump_fabric_stock_data.item_code AND df1.fout_date = dump_fabric_stock_data.fout_date  AND df1.fout_date <= '".$currentDate."') as oq 
    //                             FROM dump_fabric_stock_data
    //                              INNER JOIN  item_master ON item_master.item_code = dump_fabric_stock_data.item_code 
    //                             WHERE in_date <='".$currentDate."' Order BY dump_fabric_stock_data.item_code");
        
    //     $html = [];
        
    //     $total_stock30 = 0;
    //     $total_value30 = 0;
    //     $total_stock60 = 0;
    //     $total_value60 = 0;
    //     $total_stock90 = 0;
    //     $total_value90 = 0;
    //     $total_stock180 = 0;
    //     $total_value180 = 0;
    //     $total_stock365 = 0;
    //     $total_value365 = 0;
    //     $previousYearStock = 0;
    //     $previousYearValue = 0;
    //     $total_stock = 0;
    //     $total_value = 0;
        
    //     foreach ($FabricInwardDetails as $row) 
    //     {
    //         $outward_qty = isset($row->oq) ? $row->oq : 0; 
    //         $grn_qty = isset($row->gq) ? $row->gq : 0; 
    //         $ind_outward1 = (explode(",",$row->ind_outward_qty));
    //         $q_qty = 0; 
 
    //         foreach($ind_outward1 as $indu)
    //         {
                
    //              $ind_outward2 = (explode("=>",$indu));
    //              $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
                
    //              if($ind_outward2[0] <= $currentDate)
    //              {
    //                  $q_qty += $q_qty1;
    //              }
    //              else
    //              {
    //                   $q_qty =  0;
    //              }
    //         }
            
    //         // echo '<pre>';print_r($ind_outward1);exit;
    //         if($row->qc_qty > 0 )
    //         {
    //             $stocks =  $row->qc_qty- $q_qty;
    //         } 
    //         else
    //         {
    //              $stocks =  $row->gq - $q_qty;
    //         }
            
    //         if($row->in_date >= date('Y-m-d', strtotime('-30 days')))
    //         {
    //             $stocks1 = $stocks;
    //         }
    //         else
    //         {
    //             $stocks1 = 0; 
    //         }
            
    //         if($row->in_date >= date('Y-m-d', strtotime('-60 days')) && $row->in_date <= date('Y-m-d', strtotime('-30 days')))
    //         {
    //             $stocks2 = $stocks;
    //         }
    //         else
    //         {
    //             $stocks2 = 0; 
    //         }
            
    //         if($row->in_date >= date('Y-m-d', strtotime('-90 days')) && $row->in_date <= date('Y-m-d', strtotime('-60 days')))
    //         {
    //             $stocks3 = $stocks;
    //         }
    //         else
    //         {
    //             $stocks3 = 0; 
    //         }
    //         if($row->in_date >= date('Y-m-d', strtotime('-180 days')) && $row->in_date <= date('Y-m-d', strtotime('-90 days')))
    //         {
    //             $stocks4 = $stocks;
    //         }
    //         else
    //         {
    //             $stocks4 = 0; 
    //         }
            
    //         if($row->in_date >= date('Y-m-d', strtotime('-365 days')) && $row->in_date <= date('Y-m-d', strtotime('-180 days')))
    //         {
    //             $stocks5 = $stocks;
    //         }
    //         else
    //         {
    //             $stocks5 = 0; 
    //         }
            
    //         if($row->in_date <= date('Y-m-d', strtotime('-1 year')))
    //         {
    //             $stocks6 = $stocks;
    //         }
    //         else
    //         {
    //             $stocks6 = 0; 
    //         }
    //         $total_stock = $stocks1+$stocks2+$stocks3+$stocks4+$stocks5+$stocks6;
    //         $total_value = ($stocks1 * $row->rate)+($stocks2 * $row->rate)+($stocks3 * $row->rate)+($stocks4 * $row->rate)+($stocks5 * $row->rate)+($stocks6 * $row->rate);
            
    //         $html[] = [
    //             'srno' => count($html) + 1, 
    //             'item_code' => $row->item_code,
    //             'item_name' => $row->item_name,
    //             'total_stock30' => round($stocks1,2),
    //             'total_value30' => round($stocks1 * $row->rate,2), 
    //             'total_stock60' => round($stocks2,2),
    //             'total_value60' => round($stocks2 * $row->rate,2),
    //             'total_stock90' => round($stocks3,2),
    //             'total_value90' => round($stocks3 * $row->rate,2),
    //             'total_stock180' => round($stocks4,2),
    //             'total_value180' => round($stocks4 * $row->rate,2), 
    //             'total_stock365' => round($stocks5,2),
    //             'total_value365' => round($stocks5 * $row->rate,2), 
    //             'previousYearStock' => round($stocks6,2), 
    //             'previousYearValue' => round($stocks6 * $row->rate,2), 
    //             'total_stock' => round($total_stock,2), 
    //             'total_value' => round($total_value,2), 
    //         ];
            
    //     }
    //     $jsonData = json_encode($html);
    //     return response()->json(['html' => $jsonData]); 
    // }
     
    public function LoadInventoryAgingReport(Request $request)
    {
        $currentDate = $request->current_date;
        
        // Fetch data from the database
        $FabricInwardDetails = DB::select("SELECT dump_fabric_stock_data.*, item_master.item_name, item_master.item_image_path,
            (SELECT SUM(grn_qty) FROM dump_fabric_stock_data AS df WHERE df.track_name = dump_fabric_stock_data.track_name AND df.item_code = dump_fabric_stock_data.item_code AND df.in_date <= '".$currentDate."') as gq
            FROM dump_fabric_stock_data INNER JOIN item_master ON item_master.item_code = dump_fabric_stock_data.item_code WHERE in_date <= '".$currentDate."' ORDER BY dump_fabric_stock_data.item_code");
        
        $html = [];
        

        $aggregatedData = [];
    
        foreach ($FabricInwardDetails as $row) {
            $item_code = $row->item_code;
    
            // Initialize if item_code doesn't exist in aggregatedData
            if (!isset($aggregatedData[$item_code])) {
                $aggregatedData[$item_code] = [
                    'item_code' => $row->item_code,
                    'item_name' => $row->item_name,
                    'item_image_path' => $row->item_image_path,
                    'total_stock30' => 0,
                    'total_value30' => 0,
                    'total_stock60' => 0,
                    'total_value60' => 0,
                    'total_stock90' => 0,
                    'total_value90' => 0,
                    'total_stock180' => 0,
                    'total_value180' => 0,
                    'total_stock365' => 0,
                    'total_value365' => 0,
                    'previousYearStock' => 0,
                    'previousYearValue' => 0,
                    'total_stock' => 0,
                    'total_value' => 0,
                ];
            }
    
    
            $grn_qty = isset($row->gq) ? $row->gq : 0;
            $ind_outward1 = (explode(",", $row->ind_outward_qty));
            $q_qty = 0;
    
            foreach ($ind_outward1 as $indu) {
                $ind_outward2 = (explode("=>", $indu));
                $q_qty1 = isset($ind_outward2[1]) ? $ind_outward2[1] : 0;
    
                if ($ind_outward2[0] <= $currentDate) {
                    $q_qty += $q_qty1;
                } else {
                    $q_qty = 0;
                }
            }
    
            if ($row->qc_qty > 0) {
                $stocks = $row->qc_qty - $q_qty;
            } else {
                $stocks = $row->gq - $q_qty;
            }
    
            // Assign stock values based on date ranges
            $stocks1 = ($row->in_date >= date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
            $stocks2 = ($row->in_date >= date('Y-m-d', strtotime('-60 days')) && $row->in_date <= date('Y-m-d', strtotime('-30 days'))) ? $stocks : 0;
            $stocks3 = ($row->in_date >= date('Y-m-d', strtotime('-90 days')) && $row->in_date <= date('Y-m-d', strtotime('-60 days'))) ? $stocks : 0;
            $stocks4 = ($row->in_date >= date('Y-m-d', strtotime('-180 days')) && $row->in_date <= date('Y-m-d', strtotime('-90 days'))) ? $stocks : 0;
            $stocks5 = ($row->in_date >= date('Y-m-d', strtotime('-365 days')) && $row->in_date <= date('Y-m-d', strtotime('-180 days'))) ? $stocks : 0;
            $stocks6 = ($row->in_date <= date('Y-m-d', strtotime('-1 year'))) ? $stocks : 0;
    
            // Calculate total stock and total value
            $total_stock = $stocks1 + $stocks2 + $stocks3 + $stocks4 + $stocks5 + $stocks6;
            $total_value = ($stocks1 * $row->rate) + ($stocks2 * $row->rate) + ($stocks3 * $row->rate) + ($stocks4 * $row->rate) + ($stocks5 * $row->rate) + ($stocks6 * $row->rate);
    
            // Aggregate the sums into $aggregatedData
            $aggregatedData[$item_code]['total_stock30'] += round($stocks1, 2);
            $aggregatedData[$item_code]['total_value30'] += round($stocks1 * $row->rate, 2);
            $aggregatedData[$item_code]['total_stock60'] += round($stocks2, 2);
            $aggregatedData[$item_code]['total_value60'] += round($stocks2 * $row->rate, 2);
            $aggregatedData[$item_code]['total_stock90'] += round($stocks3, 2);
            $aggregatedData[$item_code]['total_value90'] += round($stocks3 * $row->rate, 2);
            $aggregatedData[$item_code]['total_stock180'] += round($stocks4, 2);
            $aggregatedData[$item_code]['total_value180'] += round($stocks4 * $row->rate, 2);
            $aggregatedData[$item_code]['total_stock365'] += round($stocks5, 2);
            $aggregatedData[$item_code]['total_value365'] += round($stocks5 * $row->rate, 2);
            $aggregatedData[$item_code]['previousYearStock'] += round($stocks6, 2);
            $aggregatedData[$item_code]['previousYearValue'] += round($stocks6 * $row->rate, 2);
            $aggregatedData[$item_code]['total_stock'] += round($total_stock, 2);
            $aggregatedData[$item_code]['total_value'] += round($total_value, 2);
        }
    
        // Prepare final HTML output
        foreach ($aggregatedData as $data) 
        {
            if ($data['total_stock'] != 0 || $data['total_value'] != 0) 
            {
                $html[] = [
                    'srno' => count($html) + 1,
                    'item_code' => $data['item_code'],
                    'item_name' => $data['item_name'],
                    'item_photo' => '<a href="./images/' . $data['item_image_path'] . '" target="_blank"><img src="./images/' . $data['item_image_path'] . '" alt="" width="100"  height="60"></a>',
                    'total_stock30' => money_format("%!.0n", $data['total_stock30']),
                    'total_value30' => money_format("%!.0n", $data['total_value30']),
                    'total_stock60' => money_format("%!.0n", $data['total_stock60']),
                    'total_value60' => money_format("%!.0n", $data['total_value60']),
                    'total_stock90' => money_format("%!.0n", $data['total_stock90']),
                    'total_value90' => money_format("%!.0n", $data['total_value90']),
                    'total_stock180' => money_format("%!.0n", $data['total_stock180']),
                    'total_value180' => money_format("%!.0n", $data['total_value180']),
                    'total_stock365' => money_format("%!.0n", $data['total_stock365']),
                    'total_value365' => money_format("%!.0n", $data['total_value365']),
                    'previousYearStock' => money_format("%!.0n", $data['previousYearStock']),
                    'previousYearValue' => money_format("%!.0n", $data['previousYearValue']),
                    'total_stock' => money_format("%!.0n", $data['total_stock']),
                    'total_value' => money_format("%!.0n", $data['total_value']),
                ];

            }
        }
    
        // Convert HTML array to JSON for response
        $jsonData = json_encode($html);
    
        // Return JSON response
        return response()->json(['html' => $jsonData, 'currentDate' => $currentDate]);
    }

    public function GetItemPucharseOrder(Request $request)
    {
        // DB::enableQueryLog();
        $vendorPurchaseOrderList = DB::query()
            ->fromSub(function ($query) use ($request) {
                $query->from('vendor_purchase_order_fabric_details')
                    ->select('vendor_purchase_order_fabric_details.item_code')
                    ->where('vpo_code', $request->vpo_code);
            }, 'combined')
            ->join('item_master', 'item_master.item_code', '=', 'combined.item_code')
            ->select('item_master.item_code', 'item_master.item_name')
            ->get();
        // dd(DB::getQueryLog());
        $html = '<option value="">--Select Item--</option>';

        if ($vendorPurchaseOrderList->count() > 0) {
            foreach ($vendorPurchaseOrderList as $row) {
                $html .= '<option value="' . $row->item_code . '">(' . $row->item_code . ') ' . $row->item_name . '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

     public function GetFabricInwardOutwardData(Request $request)
    {
        $detailData = DB::SELECT("SELECT sum(meter) as total_meter,(select sum(inward_details.meter) FROM inward_details WHERE inward_details.item_code = fabric_outward_details.item_code) as received,
                        item_master.item_name, fabric_outward_details.item_code  FROM fabric_outward_details 
                        INNER JOIN item_master ON item_master.item_code = fabric_outward_details.item_code
                        WHERE fabric_outward_details.vpo_code='".$request->vpo_code."' GROUP BY fabric_outward_details.item_code");
                                  
        $html = '';
        $sr_no = 1; 
        
        foreach($detailData as $row)
        {
            $html .='<tr>
                       <td>'.($sr_no++).'</td> 
                       <td>'.$row->item_code.'</td>
                       <td>'.$row->item_name.'</td>
                       <td>'.$row->total_meter.'</td>
                       <td>'.$row->received.'</td>
                       <td>'.($row->total_meter-$row->received).'</td>
                    </tr>';
        }
        
        return response()->json(['html'=>$html]);
    }
 


}
