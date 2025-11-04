<?php

namespace App\Http\Controllers;
use App\Jobs\SyncFabricDataJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Models\FabricTransactionModel;
use App\Models\FabricGateEntryModel;
use App\Models\FabricGateEntryDetailModel;
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
date_default_timezone_set("Asia/Kolkata");

setlocale(LC_MONETARY, 'en_IN'); 

class FabricGateEntryController extends Controller
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
                ->where('form_id', '356')
                ->first(); 
         
        $FabricGateEntryList = FabricGateEntryModel::join('usermaster', 'usermaster.userId', '=', 'fabric_gate_entry_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_gate_entry_master.Ac_code') 
            ->leftjoin('ledger_details', 'ledger_details.sr_no', '=', 'fabric_gate_entry_master.bill_to') 
            ->where('fabric_gate_entry_master.delflag','=', '0')
            ->where('fabric_gate_entry_master.fge_date','>', DB::raw('LAST_DAY(CURRENT_DATE - INTERVAL 3 MONTH)'))  
            ->orderByRaw('CAST(SUBSTRING(fabric_gate_entry_master.fge_date, 4) AS UNSIGNED) DESC')
            ->get(['fabric_gate_entry_master.*','usermaster.username','ledger_master.ac_short_name','ledger_details.trade_name', 'ledger_details.site_code']);
  
         return view('FabricGateEntryMasterList', compact('FabricGateEntryList','chekform'));
    }


    public function FabricGateEntryShowAll()
    { 

        $chekform = DB::table('form_auth')
                ->where('emp_id', Session::get('userId'))
                ->where('form_id', '36')
                ->first(); 
         
         $FabricGateEntryList = FabricGateEntryModel::join('usermaster', 'usermaster.userId', '=', 'fabric_gate_entry_master.userId')
            ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_gate_entry_master.Ac_code') 
            ->leftjoin('ledger_details', 'ledger_details.sr_no', '=', 'fabric_gate_entry_master.bill_to') 
            ->where('fabric_gate_entry_master.delflag','=', '0')
            ->orderByRaw('CAST(SUBSTRING(fabric_gate_entry_master.fge_date, 4) AS UNSIGNED) DESC')
            ->get(['fabric_gate_entry_master.*','usermaster.username','ledger_master.ac_short_name','ledger_details.trade_name', 'ledger_details.site_code']);
  
         return view('FabricGateEntryList', compact('FabricGateEntryList','chekform'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FabricGateEntry'");
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $FGList =  DB::table('fg_master')->get();
        $POList = PurchaseOrderModel::where('purchase_order.bom_type','=', '1')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        $BillToList =  DB::table('ledger_details')->get();
        
        return view('FabricGateEntryMaster',compact('Ledger','LocationList', 'POList','FGList', 'counter_number','ItemList','BillToList'));
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
    
            'fge_code'=>$request->fge_code, 'fge_date'=>$request->fge_date,'po_code'=>$request->po_code,'po_code2'=>$request->po_code2,'dc_no'=>$request->dc_no, 
            'dc_date'=>$request->dc_date, 'invoice_no'=>$request->invoice_no,
            'invoice_date' =>$request->invoice_date, 'Ac_code' => $request->Ac_code, 
            'location_id'=>$request->location_id,'lr_no'=>$request->lr_no, 
            'transport_name'=>$request->transport_name,'vehicle_no'=>$request->vehicle_no,'is_manual'=>$request->is_manual,
            'total_meter'=>$request->total_meter,  'total_roll'=>$request->total_roll,'total_meter'=>$request->total_meter,'total_received_meter'=>$request->total_received_meter,'bill_to'=>$request->bill_to,
            'total_amount'=>$request->total_amount,  'remark' => $request->remark,
            'userId'=>$request->userId, 'delflag'=>'0', 'created_at'=>date("Y-m-d H:i:s"), 'updated_at'=>date("Y-m-d H:i:s")
        );
        
            
        FabricGateEntryModel::insert($data1);
        DB::select("update counter_number set tr_no=tr_no + 1  where c_name ='C1' AND type='FabricGateEntry'"); 
      
        if(count($item_code)>0)
        { 
            if(isset($request->item_code) && is_array($request->item_code))
            {
                for($x=0; $x<count($request->item_code); $x++) 
                { 
                        $data2=array(
                        'fge_code' =>$request->fge_code,
                        'fge_date' => $request->fge_date,
                        'po_code'=>$request->po_code,
                        'po_code2'=>$request->po_code2,
                        'is_manual'=>isset($request->is_manual) ? $request->is_manual : 0, 
                        'item_name'=>isset($request->item_name[$x]) ? $request->item_name[$x] : '',  
                        'item_code'=>isset($request->item_code[$x]) ? $request->item_code[$x] : 0,   
                        'item_description' =>$request->item_description[$x],
                        'challan_qty' => $request->challan_qty[$x],
                        'no_of_roll' => $request->no_of_roll[$x],
                        'receive_qty' => $request->receive_qty[$x],
                        'rate' => $request->rate[$x], 
                        'amount' => $request->amount[$x],
                        'remarks' => $request->remarks[$x]
                        );         
                    FabricGateEntryDetailModel::insert($data2); 
                } 
            } 
                
        }
     
       return redirect()->route('FabricGateEntry.index')->with('message', ' Record Created Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricGateEntryModel  $FabricGateEntryModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricGateEntryModel $FabricGateEntryModel)
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
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FabricGateEntry'");
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $FGList =  DB::table('fg_master')->get();
        $POList = PurchaseOrderModel::where('purchase_order.bom_type','=', '1')->get();
        $LocationList = LocationModel::where('location_master.delflag','=', '0')->get(); 
        
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
      
        $FabricGateEntryMasterList = FabricGateEntryModel::where('fge_code',$id)->first();
       
        if($FabricGateEntryMasterList->po_code != '')
        {
            $ItemList = DB::SELECT("SELECT item_master.item_code, item_master.item_name FROM purchaseorder_detail 
                LEFT JOIN item_master ON item_master.item_code = purchaseorder_detail.item_code 
                WHERE purchaseorder_detail.pur_code = '".$FabricGateEntryMasterList->po_code."' GROUP BY purchaseorder_detail.item_code");
        }
        
        if($FabricGateEntryMasterList->po_code2 != '')
        {
            $ItemList = DB::SELECT("SELECT item_master.item_code, item_master.item_name FROM item_master WHERE delflag=0");
        }
        // DB::enableQueryLog();
        $FabricGateEntryDetails = FabricGateEntryDetailModel::join('item_master', 'item_master.item_code', '=', 'fabric_gate_entry_details.item_code', 'left')
                                    ->where('fabric_gate_entry_details.fge_code','=', $FabricGateEntryMasterList->fge_code)
                                    ->get(['item_master.item_name','fabric_gate_entry_details.*', 'fabric_gate_entry_details.item_name as item_names']);
                                    
                                      
        if(strpos($FabricGateEntryMasterList->po_code, "PO/") !== false)
        {
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.pur_code='".$FabricGateEntryMasterList->po_code."'");
        } 
        else 
        {     
            $BillToList = DB::SELECT("SELECT ledger_details.sr_no, ledger_details.trade_name, ledger_details.site_code FROM purchase_order INNER JOIN ledger_details ON ledger_details.sr_no = purchase_order.bill_to WHERE purchase_order.Ac_code='".$FabricGateEntryMasterList->Ac_code."'");
        }
        // dd(DB::getQueryLog()); 
        return view('FabricGateEntryMasterEdit',compact('FabricGateEntryMasterList','POList','LocationList', 'Ledger', 'FGList', 'FabricGateEntryDetails','counter_number','ItemList','BillToList'));
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
    
            'fge_code'=>$request->fge_code, 'fge_date'=>$request->fge_date,'po_code'=>$request->po_code,'po_code2'=>$request->po_code2,'dc_no'=>$request->dc_no, 
            'dc_date'=>$request->dc_date, 'invoice_no'=>$request->invoice_no,
            'invoice_date' =>$request->invoice_date, 'Ac_code' => $request->Ac_code, 
            'location_id'=>$request->location_id,'lr_no'=>$request->lr_no, 
            'transport_name'=>$request->transport_name,'vehicle_no'=>$request->vehicle_no,'is_manual'=>$request->is_manual,
            'total_meter'=>$request->total_meter,  'total_roll'=>$request->total_roll,'total_meter'=>$request->total_meter,'total_received_meter'=>$request->total_received_meter,'bill_to'=>$request->bill_to,
            'total_amount'=>$request->total_amount,  'remark' => $request->remark,
            'userId'=>$request->userId, 'delflag'=>'0', 'updated_at'=>date("Y-m-d H:i:s")
        );
 
        $FabricGateEntryMasterList = FabricGateEntryModel::findOrFail($id);  
   
        $FabricGateEntryMasterList->fill($data1)->save();
         
        DB::table('fabric_gate_entry_details')->where('fge_code', $FabricGateEntryMasterList->fge_code)->delete(); 
        
        $item_code = $request->input('item_code');
     
        if(count($item_code)>0)
        { 
            if(isset($request->item_code) && is_array($request->item_code))
            {
                for($x=0; $x<count($request->item_code); $x++) 
                { 
                        $data2=array(
                        'fge_code' =>$request->fge_code,
                        'fge_date' => $request->fge_date,
                        'po_code'=>$request->po_code,
                        'po_code2'=>$request->po_code2,
                        'is_manual'=>isset($request->is_manual) ? $request->is_manual : 0, 
                        'item_name'=>isset($request->item_name[$x]) ? $request->item_name[$x] : '',  
                        'item_code'=>isset($request->item_code[$x]) ? $request->item_code[$x] : 0,  
                        'item_description' =>$request->item_description[$x],
                        'challan_qty' => $request->challan_qty[$x],
                        'no_of_roll' => $request->no_of_roll[$x],
                        'receive_qty' => $request->receive_qty[$x],
                        'rate' => $request->rate[$x], 
                        'amount' => $request->amount[$x],
                        'remarks' => $request->remarks[$x]
                        );         
                        FabricGateEntryDetailModel::insert($data2); 
                } 
            } 
                
        }
     
        return redirect()->route('FabricGateEntry.index')->with('message', 'Update Record Succesfully');
    }


    public function GetItemDetails(Request $request)
    {
        $item_code = $request->item_code;
        $data=DB::table('item_master')->where('item_code','=',$item_code)->get(['item_master.*']);
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
        
        DB::table('fabric_gate_entry_master')->where('fge_code', $ids)->delete();
        DB::table('fabric_gate_entry_details')->where('fge_code', $ids)->delete();
        
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    public function FabricGateEntryReport(Request $request)
    { 
        ini_set('memory_limit', '10G'); 
         
        $fromDate =  isset($request->fromDate) ? $request->fromDate : date("Y-m-01");
        $toDate =  isset($request->toDate) ? $request->toDate : date("Y-m-d");
        
         
        if ($request->ajax())
        {
            $FabricGateData = DB::select("SELECT fabric_gate_entry_master.*,fabric_gate_entry_details.item_code,item_master.item_name,ifnull(item_master.item_description,'-') as item_description,
            fabric_gate_entry_details.item_name as item_names,
            ledger_master.ac_short_name as supplier_name,location_master.location, IFNULL(sum(no_of_roll),0) as total_roll, IFNULL(sum(fabric_gate_entry_details.challan_qty),0) as challan_qty,
            fabric_gate_entry_details.rate, fabric_gate_entry_details.amount, fabric_gate_entry_details.remarks,ledger_details.trade_name, ledger_details.site_code FROM fabric_gate_entry_master  
            inner join fabric_gate_entry_details on fabric_gate_entry_details.fge_code=fabric_gate_entry_master.fge_code
            LEFT join ledger_details on ledger_details.sr_no=fabric_gate_entry_master.bill_to
            LEFT join item_master on item_master.item_code=fabric_gate_entry_details.item_code
            LEFT join ledger_master on ledger_master.ac_code=fabric_gate_entry_master.Ac_code
            LEFT join location_master on location_master.loc_id=fabric_gate_entry_master.location_id
            WHERE fabric_gate_entry_master.fge_date BETWEEN '".$fromDate."' AND '".$toDate."' GROUP BY fabric_gate_entry_details.fge_code");
            
            
            return Datatables::of($FabricGateData)
            ->addIndexColumn() 
            ->addColumn('fge_date',function ($row) 
            {
                $fge_date = date("d-M-Y", strtotime($row->fge_date));
                return $fge_date;
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
            ->addColumn('total_roll',function ($row) 
            {
                $total_roll = money_format('%!.0n',($row->total_roll));
                return $total_roll;
            }) 
            ->addColumn('challan_qty',function ($row) 
            {
                $challan_qty = money_format('%!.0n',($row->challan_qty));
                return $challan_qty;
            })
            ->addColumn('total_roll',function ($row) 
            {
                $total_roll = money_format('%!.0n',($row->total_roll));
                return $total_roll;
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
            ->rawColumns(['total_roll','item_name','challan_qty','fge_date','dc_date','invoice_date','bill_to'])
            ->make(true);
        } 
        
        return view('FabricGateEntryReport', compact('fromDate','toDate'));
        
    }
    
    
    public function GetPOApproveStatus(Request $request)
    {

        $purchaseList = DB::table('purchase_order')->select('purchase_order.*')->where('po_type_id','=',2)->where('approveFlag','=',0)->where('pur_code','=',$request->po_code)->get();
            
        $isApprove = count($purchaseList) ? count($purchaseList) : 0;
        
        return response()->json(['isApprove' => $isApprove]);
    }
 
}
