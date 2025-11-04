<?php

namespace App\Http\Controllers;
use App\Jobs\SyncFabricDataJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\FabricTransactionModel;
use App\Models\TrimGateEntryModel;
use App\Models\TrimGateEntryDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\PurchaseOrderModel; 
use App\Models\ColorModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\ItemModel;
use App\Models\RackModel;
use App\Models\UnitModel;
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
date_default_timezone_set("Asia/Kolkata");

setlocale(LC_MONETARY, 'en_IN'); 

class TrimsGateEntryController extends Controller
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
                ->where('form_id', '359')
                ->first(); 
         
        $TrimGateEntryList = TrimGateEntryModel::join('usermaster', 'usermaster.userId', '=', 'trim_gate_entry_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trim_gate_entry_master.Ac_code') 
            ->where('trim_gate_entry_master.delflag','=', '0')
            ->where('trim_gate_entry_master.tge_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)')) 
            ->orderByRaw('CAST(SUBSTRING(trim_gate_entry_master.tge_code, 4) AS UNSIGNED) DESC')
            ->get(['trim_gate_entry_master.*','usermaster.username','ledger_master.Ac_name']);
  
         return view('TrimGateEntryMasterList', compact('TrimGateEntryList','chekform'));
    }


    public function TrimGateEntryShowAll()
    { 
 
        $chekform = DB::table('form_auth')
                ->where('emp_id', Session::get('userId'))
                ->where('form_id', '359')
                ->first(); 
        // DB::enableQueryLog();  
         $TrimGateEntryList = TrimGateEntryModel::join('usermaster', 'usermaster.userId', '=', 'trim_gate_entry_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trim_gate_entry_master.Ac_code') 
            ->where('trim_gate_entry_master.delflag','=', '0')
            ->orderByRaw('CAST(SUBSTRING(trim_gate_entry_master.tge_code, 4) AS UNSIGNED) DESC')
            ->get(['trim_gate_entry_master.*','usermaster.username','ledger_master.Ac_name']);
        // dd(DB::getQueryLog());
         return view('TrimGateEntryMasterList', compact('TrimGateEntryList','chekform'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TrimGateEntry'");
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','!=', '1')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $FGList =  DB::table('fg_master')->get();
        $BillToList =  DB::table('ledger_details')->get();
        $POList = PurchaseOrderModel::where('purchase_order.bom_type','!=', '1')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get(); 
        
        return view('TrimGateEntryMaster',compact('Ledger','LocationList', 'POList','FGList', 'counter_number','ItemList','UnitList','BillToList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo '<pre>';print_R($_POST);exit;
        $item_code = $request->item_code; 
        
        $data1=array(
    
            'tge_code'=>$request->tge_code, 'tge_date'=>$request->tge_date,'po_code'=>$request->po_code,'po_code2'=>$request->po_code2,'dc_no'=>$request->dc_no, 
            'dc_date'=>$request->dc_date, 'invoice_no'=>$request->invoice_no,
            'invoice_date' =>$request->invoice_date, 'Ac_code' => $request->Ac_code, 
            'location_id'=>$request->location_id,'lr_no'=>$request->lr_no, 
            'transport_name'=>$request->transport_name,'vehicle_no'=>$request->vehicle_no,
            'total_qty'=>$request->total_qty,'total_received_meter'=>$request->total_received_meter,
            'total_amt'=>$request->total_amt,  'remark' => $request->remark,'is_manual'=>$request->is_manual,'bill_to'=>$request->bill_to,
            'userId'=>$request->userId, 'delflag'=>'0', 'created_at'=>date("Y-m-d H:i:s"), 'updated_at'=>date("Y-m-d H:i:s")
        );
        
            
            TrimGateEntryModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1  where c_name ='C1' AND type='TrimGateEntry'"); 
      
        if(count($item_code)>0)
        { 
            if(isset($request->item_code) && is_array($request->item_code))
            {
                for($x=0; $x<count($request->item_code); $x++) 
                { 
                        $data2=array(
                        'tge_code' =>$request->tge_code,
                        'tge_date' => $request->tge_date,
                        'po_code'=>$request->po_code,
                        'po_code2'=>$request->po_code2,
                        'is_manual'=>isset($request->is_manual) ? $request->is_manual : 0, 
                        'item_name'=>isset($request->item_name[$x]) ? $request->item_name[$x] : '',  
                        'item_code'=>isset($request->item_code[$x]) ? $request->item_code[$x] : 0,  
                        'item_description' =>$request->item_description[$x],
                        'unit_id'=>$request->unit_id[$x],
                        'challan_qty' => $request->challan_qty[$x],
                        'unit_id' => $request->unit_id[$x],
                        'receive_qty' => $request->receive_qty[$x],
                        'rate' => $request->rate[$x], 
                        'amount' => $request->amount[$x],
                        'remarks' => $request->remarks[$x]
                        );         
                    TrimGateEntryDetailModel::insert($data2); 
                } 
            } 
                
        }
     
       return redirect()->route('TrimGateEntry.index')->with('message', ' Record Created Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricGateEntryModel  $FabricGateEntryModel
     * @return \Illuminate\Http\Response
     */
    public function show(TrimGateEntryModel $TrimGateEntryModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricGateEntryModel  $FabricGateEntryModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='TrimGateEntry'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $FGList =  DB::table('fg_master')->get();
        $POList = PurchaseOrderModel::where('purchase_order.bom_type','!=', '1')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $UnitList = UnitModel::where('unit_master.delflag','=', '0')->get(); 
        
        
        $TrimGateEntryMasterList = TrimGateEntryModel::where('tge_code',$id)->first();
       
        if($TrimGateEntryMasterList->po_code != '')
        {
            $ItemList = DB::SELECT("SELECT item_master.item_code, item_master.item_name FROM purchaseorder_detail 
                LEFT JOIN item_master ON item_master.item_code = purchaseorder_detail.item_code 
                WHERE purchaseorder_detail.pur_code = '".$TrimGateEntryMasterList->po_code."' GROUP BY purchaseorder_detail.item_code");
        }
        
        if($TrimGateEntryMasterList->po_code2 != '')
        { 
            //DB::enableQueryLog();
            $ItemList = DB::SELECT("SELECT item_master.item_code, item_master.item_name FROM item_master WHERE delflag=0 AND cat_id != 1"); 
            //dd(DB::getQueryLog()); 
        }
       
        $TrimGateEntryDetails = TrimGateEntryDetailModel::join('item_master', 'item_master.item_code', '=', 'trim_gate_entry_details.item_code', 'left')
                                    ->where('trim_gate_entry_details.tge_code','=', $TrimGateEntryMasterList->tge_code)
                                    ->get(['trim_gate_entry_details.*','item_master.item_name', 'trim_gate_entry_details.item_name as item_names']);
       
        if(strpos($TrimGateEntryMasterList->po_code, "PO/") !== false)
        {
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.pur_code='".$TrimGateEntryMasterList->po_code."'");
        } 
        else 
        {     
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.Ac_code='".$TrimGateEntryMasterList->Ac_code."'");
        }

        return view('TrimGateEntryMasterEdit',compact('TrimGateEntryMasterList','POList','LocationList', 'Ledger', 'FGList', 'TrimGateEntryDetails','counter_number','ItemList', 'UnitList','BillToList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricGateEntryModel  $FabricGateEntryModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data1=array(
    
            'tge_code'=>$request->tge_code, 'tge_date'=>$request->tge_date,'po_code'=>$request->po_code,'po_code2'=>$request->po_code2,'dc_no'=>$request->dc_no, 
            'dc_date'=>$request->dc_date, 'invoice_no'=>$request->invoice_no,
            'invoice_date' =>$request->invoice_date, 'Ac_code' => $request->Ac_code, 
            'location_id'=>$request->location_id,'lr_no'=>$request->lr_no, 
            'transport_name'=>$request->transport_name,'vehicle_no'=>$request->vehicle_no,
            'total_qty'=>$request->total_qty,'total_received_meter'=>$request->total_received_meter,
            'total_amt'=>$request->total_amt,  'remark' => $request->remark,'is_manual'=>$request->is_manual,'bill_to'=>$request->bill_to,
            'userId'=>$request->userId, 'delflag'=>'0', 'updated_at'=>date("Y-m-d H:i:s")
        );
 
        $TrimGateEntryMasterList = TrimGateEntryModel::findOrFail($id);  
   
        $TrimGateEntryMasterList->fill($data1)->save();
         
        DB::table('trim_gate_entry_details')->where('tge_code', $TrimGateEntryMasterList->tge_code)->delete(); 
        
        $item_code = $request->input('item_code');
     
        if(count($item_code)>0)
        { 
            if(isset($request->item_code) && is_array($request->item_code))
            {
                for($x=0; $x<count($request->item_code); $x++) 
                { 
                        $data2=array(
                        'tge_code' =>$request->tge_code,
                        'tge_date' => $request->tge_date,
                        'po_code'=>$request->po_code,
                        'po_code2'=>$request->po_code2,
                        'is_manual'=>isset($request->is_manual) ? $request->is_manual : 0, 
                        'item_name'=>isset($request->item_name[$x]) ? $request->item_name[$x] : '',  
                        'item_code'=>isset($request->item_code[$x]) ? $request->item_code[$x] : 0,  
                        'item_description' =>$request->item_description[$x],
                        'challan_qty' => $request->challan_qty[$x],
                        'unit_id'=>$request->unit_id[$x],
                        'receive_qty' => $request->receive_qty[$x],
                        'rate' => $request->rate[$x], 
                        'amount' => $request->amount[$x],
                        'remarks' => $request->remarks[$x]
                        );         
                        TrimGateEntryDetailModel::insert($data2); 
                } 

            } 
                
        }
     
        return redirect()->route('TrimGateEntry.index')->with('message', 'Update Record Succesfully');
    }


    public function GetItemDetails(Request $request)
    {
        $item_code = $request->item_code;
        $data=DB::table('item_master')->where('item_code','=',$item_code)->where('cat_id','!=',1)->get(['item_master.*']);
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricGateEntryModel  $FabricGateEntryModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids=base64_decode($id);
        
        DB::table('trim_gate_entry_master')->where('tge_code', $ids)->delete();
        DB::table('trim_gate_entry_details')->where('tge_code', $ids)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function TrimsGateEntryReport(Request $request)
    { 
        ini_set('memory_limit', '10G'); 
         
        $fromDate =  isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");
        
         
        if ($request->ajax())
        {
           $FabricGateData = DB::select("
                SELECT 
                    trim_gate_entry_master.*,
                    trim_gate_entry_details.item_code,
                    item_master.item_name,
                    IFNULL(item_master.item_description,'-') AS item_description,
                    trim_gate_entry_details.item_name AS item_names,
                    ledger_details.trade_name,
                    ledger_details.site_code,
                    ledger_master.ac_name AS supplier_name,
                    location_master.location, 
                    IFNULL(SUM(trim_gate_entry_details.challan_qty),0) AS challan_qty,
                    trim_gate_entry_details.rate, 
                    trim_gate_entry_details.amount, 
                    trim_gate_entry_details.remarks 
                FROM trim_gate_entry_master  
                LEFT JOIN trim_gate_entry_details 
                    ON trim_gate_entry_details.tge_code = trim_gate_entry_master.tge_code
                LEFT JOIN item_master 
                    ON item_master.item_code = trim_gate_entry_details.item_code
                LEFT JOIN ledger_master 
                    ON ledger_master.ac_code = trim_gate_entry_master.Ac_code
                LEFT JOIN ledger_details 
                    ON ledger_details.sr_no = trim_gate_entry_master.bill_to 
                    OR ledger_details.ac_code = trim_gate_entry_master.Ac_code
                LEFT JOIN location_master 
                    ON location_master.loc_id = trim_gate_entry_master.location_id
                WHERE trim_gate_entry_master.tge_date BETWEEN '".$fromDate."' AND '".$toDate."' 
                GROUP BY trim_gate_entry_details.tge_code,trim_gate_entry_details.po_code,trim_gate_entry_details.item_code");

           
            return Datatables::of($FabricGateData)
            ->addIndexColumn() 
            ->addColumn('tge_date',function ($row) 
            {
                $tge_date = date("d-M-Y", strtotime($row->tge_date));
                return $tge_date;
            }) 
            ->addColumn('dc_date',function ($row) 
            {
                $dc_date = date("d-M-Y", strtotime($row->dc_date));
                return $dc_date;
            }) 
            ->addColumn('invoice_date',function ($row) 
            {
                $invoice_date = date("d-M-Y", strtotime($row->invoice_date));
                return $invoice_date;
            }) 
            ->addColumn('item_name',function ($row) 
            {
                $item_name = isset($row->item_name) ?  $row->item_name : $row->item_names;
                return $item_name;
            }) 
            ->addColumn('challan_qty',function ($row) 
            {
                $challan_qty = money_format('%!.0n',($row->challan_qty));
                return $challan_qty;
            })
            ->addColumn('bill_name',function ($row) 
            { 
                $bill_name =  $row->trade_name.'('.$row->site_code.')';
                return $bill_name;
            }) 
            ->rawColumns(['item_name','challan_qty','tge_date','dc_date','invoice_date','bill_name'])
            ->make(true);
        } 
        
        return view('TrimsGateEntryReport', compact('fromDate','toDate'));
        
    }
    
    public function GetPurchaseBillToDetails(Request $request)
    {
        
        $po_code= $request->po_code;
        
        $PartyDetails = DB::select("select ledger_details.sr_no,ledger_details.trade_name,ledger_details.site_code from purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to  where purchase_order.pur_code='".$po_code."'");
        
        $html = '';
        foreach($PartyDetails as  $row)
        {
            $html.='<option value="'.$row->sr_no.'">'.$row->trade_name.'('.$row->site_code.')</option>';
        }
        
        return response()->json(['detail' => $html]); 
         
    }
    
}