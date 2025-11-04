<?php
namespace App\Http\Controllers;
use App\Models\SparePurchaseOrderModel;
use App\Models\SparePurchaseOrderDetailModel;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\CategoryModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;
use DataTables;
ini_set('memory_limit', '10G');

class SparePurchaseOrderController extends Controller
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
        ->where('form_id', '31')
        ->first();
        
       
             
        $data = SparePurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'spare_purchase_order.Ac_code')
            ->join('usermaster', 'usermaster.userId', '=', 'spare_purchase_order.userId')
            ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'spare_purchase_order.tax_type_id')
            ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'spare_purchase_order.po_type_id')
            ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'spare_purchase_order.firm_id')    
            ->where('spare_purchase_order.delflag','=', '0')
            ->where('spare_purchase_order.userId','=',  Session::get('userId'))
            ->get(['spare_purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','po_type_master.po_type_name']);

        if ($request->ajax()) 
        {
                return Datatables::of($data)
                ->addIndexColumn() 
                ->addColumn('action1', function ($row) use ($chekform)
                { 
                    $btn1 = '<a class="btn btn-outline-secondary btn-sm print" target="_blank" href="SparePurchaseOrderPrint/'.$row->sr_no.'" title="print">
                                <i class="fas fa-print"></i>
                            </a>';
                  
                    return $btn1;
                })
                ->addColumn('action2', function ($row) use ($chekform)
                {
                    if($chekform->edit_access==1)
                    {  
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm"  href="'.route('SparePurchaseOrder.edit', $row->sr_no).'" >
                                    <i class="fas fa-pencil-alt"></i>
                               </a>';
                    }
                    else
                    { 
                        $btn2 = '<a class="btn btn-primary btn-icon btn-sm">
                                    <i class="fas fa-lock"></i>
                                </a>';   
                    }
                    return $btn2;
                })
                ->addColumn('action3', function ($row) use ($chekform){
             
                    if($chekform->delete_access==1 && $row->username == Session::get('username') || Session::get('user_type') == 1  )
                    {      
             
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm DeleteRecord" data-toggle="tooltip" data-original-title="Delete" data-token="'.csrf_token().'" data-id="'.$row->pur_code.'" data-potype="'.base64_encode($row->cat_id).'"  data-route="'.route('SparePurchaseOrder.destroy',base64_encode($row->pur_code)).'"><i class="fas fa-trash"></i></a>'; 
                    }  
                    else
                    {
                        $btn3 = '<a class="btn btn-danger btn-icon btn-sm" data-toggle="tooltip" data-original-title="Delete"> <i class="fas fa-lock"></i></a>'; 
                   
                    }
                    return $btn3;
                })
                ->rawColumns(['action1','action2','action3'])
        
                ->make(true);
        }
        return view('SparePurchaseOrderList', compact('chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='Other_Purchase' and c_name='C1'")); 
        $firmlist = DB::table('firm_master')->get();
        $ledgerlist = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")->where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->where('ledger_master.ac_code','>', '39')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $CategroyList = CategoryModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $itemlist = DB::table('spare_item_master')->get();
        $unitlist = DB::table('unit_master')->get();
        
        return view('SparePurchaseOrder',compact('firmlist','ledgerlist','gstlist','itemlist','ClassList','code','unitlist','POTypeList','CategroyList'));     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $firm_id = $request->input('firm_id');      
    
        $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no', c_code, code"))
            ->where('c_name', '=', 'C1')
            ->where('type', '=', 'SparePurchaseOrder')
            ->where('firm_id', '=', $firm_id)
            ->first();
    
        $class_ids = implode(",", (array) $request->input('class_id', []));
    
        $TrNo = ($codefetch->code ?? '') . ($codefetch->tr_no ?? '');
    
        $data = [
            'pur_code' => $TrNo,
            'cat_id' =>  $request->cat_id,
            'class_id' => $class_ids,
            'pur_date' => $request->input('pur_date'),
            'Ac_code' => $request->input('Ac_code') ?? '',
            'tax_type_id' => $request->input('tax_type_id') ?? 0,
            'total_qty' => $request->input('total_qty') ?? 0,
            'Gross_amount' => $request->input('Gross_amount') ?? 0,
            'Gst_amount' => $request->input('Gst_amount') ?? 0,
            'totFreightAmt' => $request->input('totFreightAmt') ?? 0,
            'Net_amount' => $request->input('Net_amount') ?? 0,
            'narration' => $request->input('narration') ?? '',
            'firm_id' => $firm_id,
            'c_code' => $codefetch->c_code ?? '',
            'gstNo' => $request->input('gstNo') ?? '',
            'address' => $request->input('address') ?? '',
            'deliveryAddress' => $request->input('deliveryAddress') ?? '',
            'supplierRef' => $request->input('supplierRef') ?? '',
            'terms_and_conditions' => $request->input('terms_and_conditions') ?? '',
            'delivery_date' => $request->input('delivery_date') ?? null,
            'userId' => $request->input('userId') ?? null,
            'reason_disapproval' => '0',
            'delflag' => 0
        ];
    
        // Debug the data to ensure it's correct
        // dd($data);
    
        SparePurchaseOrderModel::insert($data);
    
        DB::table('counter_number')
            ->where('c_name', 'C1')
            ->where('type', 'SparePurchaseOrder')
            ->where('firm_id', $firm_id)
            ->increment('tr_no');
    
        $itemcodes = count($request->spare_item_codes);
     
        for ($x = 0; $x < $itemcodes; $x++) 
        {
            $data2 = [
                'pur_code' => $TrNo,
                'pur_date' => $request->input('pur_date'),
                'cat_id' =>  $request->cat_id,
                'class_id' => $class_ids,
                'Ac_code' => $request->input('Ac_code'),
                'spare_item_code' => $request->spare_item_codes[$x],
                'hsn_code' => $request->hsn_code[$x],
                'unit_id' => $request->unit_id[$x],
                'item_qty' => $request->item_qtys[$x],
                'item_rate' => $request->item_rates[$x],
                'disc_per' => $request->disc_pers[$x],
                'disc_amount' => $request->disc_amounts[$x],
                'pur_cgst' => $request->pur_cgsts[$x],
                'camt' => $request->camts[$x],
                'pur_sgst' => $request->pur_sgsts[$x],
                'samt' => $request->samts[$x],
                'pur_igst' => $request->pur_igsts[$x],
                'iamt' => $request->iamts[$x],
                'amount' => $request->amounts[$x],
                'freight_hsn' => $request->freight_hsn[$x],
                'freight_amt' => $request->freight_amt[$x],
                'total_amount' => $request->total_amounts[$x], 
                'totalQty' => $request->totalQtys[$x],
                'firm_id' => $firm_id
            ];
            
            SparePurchaseOrderDetailModel::insert($data2);
            
    } 
    
        return redirect()->route('SparePurchaseOrder.index')->with('message', 'Add Record Successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    { 
        $SparePurchaseList = SparePurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'spare_purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'spare_purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'spare_purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'spare_purchase_order.firm_id')    
        ->where('spare_purchase_order.delflag','=', '0')
         ->where('spare_purchase_order.approveFlag','=', '1')
        ->get(['spare_purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('SparePurchaseOrderPrint', compact('SparePurchaseList'));
    }
    
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '31')
        ->first();

        $data = SparePurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'spare_purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'spare_purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'spare_purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'spare_purchase_order.firm_id')    
        ->where('spare_purchase_order.delflag','=', '0')
         ->where('spare_purchase_order.approveFlag','=', '2')
        ->get(['spare_purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    { 
        $firmlist = DB::table('firm_master')->get(); 
        $ledgerlist = LedgerModel::SELECT('ledger_master.*', 'business_type.Bt_name')->join('business_type', "business_type.Bt_id","=","ledger_master.bt_id")->where('ledger_master.delflag','=', '0')->whereIn('ledger_master.bt_id', [1,2,4])->get();
        $gstlist = DB::table('tax_type_master')->get();
        $itemlist = DB::table('spare_item_master')->get();
        $unitlist = DB::table('unit_master')->get();
        $ClassList = ClassificationModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $SpareMasterData = SparePurchaseOrderModel::find($id); 
        $CategroyList = CategoryModel::where('delflag','=', '0')->where('cat_id','=', '5')->get();
        //DB::enableQueryLog();
        $detailpurchase = SparePurchaseOrderDetailModel::select('machine_model_master.mc_model_name','spare_item_master.cat_id','spare_item_master.class_id','spare_item_master.mc_model_id','spare_purchase_order_detail.pur_code','spare_purchase_order_detail.pur_date','spare_purchase_order_detail.cat_id',
                        'spare_purchase_order_detail.class_id','spare_purchase_order_detail.Ac_code','spare_purchase_order_detail.spare_item_code','spare_purchase_order_detail.disc_per','spare_purchase_order_detail.disc_amount','spare_purchase_order_detail.amount',
                        'spare_purchase_order_detail.freight_hsn','spare_purchase_order_detail.freight_amt','spare_purchase_order_detail.total_amount','spare_purchase_order_detail.firm_id','spare_purchase_order_detail.item_qty','spare_purchase_order_detail.item_rate',
                        'spare_item_master.machinetype_id','spare_item_master.mc_make_Id','spare_item_master.unit_id','spare_item_master.dimension','spare_item_master.cgst_per','spare_item_master.sgst_per','spare_item_master.igst_per',
                        'spare_item_master.hsn_code','spare_purchase_order_detail.camt','spare_purchase_order_detail.samt','spare_purchase_order_detail.iamt', 'spare_purchase_order_detail.freight_hsn','spare_purchase_order_detail.freight_amt')
                        ->join('spare_item_master','spare_item_master.spare_item_code', '=', 'spare_purchase_order_detail.spare_item_code')
                        ->leftjoin('machine_model_master','machine_model_master.mc_model_id', '=', 'spare_item_master.mc_model_id')
                        ->where('pur_code','=', $SpareMasterData->pur_code)->get();
        //dd(DB::getQueryLog());
        return view('SparePurchaseOrderEdit',compact('SpareMasterData','firmlist','ledgerlist','gstlist','ClassList','itemlist','detailpurchase','unitlist','POTypeList','CategroyList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SparePurchaseOrderModel  $SparePurchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code)
    {     
        $class_ids = implode(",", (array)$request->input('class_id', []));
        
        $data = array('pur_code'=>$request->input('pur_code'),
        'cat_id' =>  $request->cat_id,
        "class_id"=> $class_ids,
        "pur_date"=> $request->input('pur_date'),
        "Ac_code"=> $request->input('Ac_code'),
        "po_type_id"=> $request->input('po_type_id'),
        "tax_type_id"=> $request->input('tax_type_id'),
        "total_qty"=> $request->input('total_qty'),
        "Gross_amount"=> $request->input('Gross_amount'),
        "Gst_amount"=> $request->input('Gst_amount'),
        "totFreightAmt"=> $request->input('totFreightAmt'),
        "Net_amount"=> $request->input('Net_amount'),
        "narration"=> $request->input('narration'),
        "firm_id"=> $request->input('firm_id'),
        "c_code"=> $request->input('c_code'),
        "gstNo"=> $request->input('gstNo'),
        "address"=> $request->input('address'),
        "deliveryAddress"=> $request->input('deliveryAddress'),
        "supplierRef"=> $request->input('supplierRef'),
        "userId"=> $request->input('userId'),
        "approveFlag"=> $request->input('approveFlag'),
        "terms_and_conditions"=> $request->input('terms_and_conditions'),
        "delivery_date"=>$request->input('delivery_date'), 
        "delflag"=>0,
        "reason_disapproval"=> $request->input('reason_disapproval'),
        );
        
        // Insert
        $purchase = SparePurchaseOrderModel::findOrFail($pur_code);  
        
        $purchase->fill($data)->save();
        
       DB::table('spare_purchase_order_detail')->where('pur_code', $request->input('pur_code'))->delete(); 
    
    
        $cnt = $request->input('cnt');
        
        $itemcodes=count($request->spare_item_codes);
         
        for($x=0;$x<$itemcodes;$x++) 
        {
            $data2=array(
            'pur_code' =>$request->input('pur_code'),
            'pur_date' => $request->input('pur_date'),
            'cat_id' =>  $request->cat_id,
            "class_id"=> $class_ids,
            'Ac_code' => $request->input('Ac_code'),
            'spare_item_code' => $request->spare_item_codes[$x],
            'hsn_code' => $request->hsn_code[$x],
            'unit_id' => $request->unit_id[$x],
            'item_qty' => $request->item_qtys[$x],
            'item_rate' => $request->item_rates[$x],
            'disc_per' => $request->disc_pers[$x],
            'disc_amount' => $request->disc_amounts[$x],
            'pur_cgst' => $request->pur_cgsts[$x],
            'camt' => $request->camts[$x],
            'pur_sgst' => $request->pur_sgsts[$x],
            'samt' => $request->samts[$x],
            'pur_igst' => $request->pur_igsts[$x],
            'iamt' => $request->iamts[$x],
            'amount' => $request->amounts[$x],
            'freight_hsn' => $request->freight_hsn[$x],
            'freight_amt' => $request->freight_amt[$x],
            'total_amount' => $request->total_amounts[$x], 
            'totalQty'=> isset($request->totalQtys[$x])? $request->totalQtys[$x] :0,
            'firm_id' => $request->firm_id); 
             SparePurchaseOrderDetailModel::insert($data2);  
        } 
    
       return redirect()->route('SparePurchaseOrder.index')->with('message', 'Update Record Succesfully');
    }

  
    public function destroy($pur_code, Request $request)
    {
        $pur_code=base64_decode($pur_code);
        SparePurchaseOrderModel::where('pur_code',$pur_code)->delete(); 
        SparePurchaseOrderDetailModel::where('pur_code',$pur_code)->delete();  
        
        Session::flash('delete', 'Deleted record successfully'); 
        
    }

    public function GetPartyDetails(Request $request)
    {
        $ac_code= $request->input('ac_code');
        $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='".$ac_code."' and delflag=0");
        return json_encode($PartyRecords);
    }
    
    
    public function GetClassLists(Request $request)
    {
        $cat_ids=explode(',',$request->cat_id);
         
        $ClassList = DB::table('classification_master')->select('classification_master.class_id', 'class_name')
        ->whereIN('cat_id',$cat_ids)->get();
        
        if (!$cat_ids)
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
    
    public function GetPOList(Request $request)
    {
        $POList = DB::table('spare_purchase_order')->select('spare_purchase_order.pur_code')
        ->where('Ac_code',$request->Ac_code)->get();
        
        if(!$request->Ac_code)
        {
            $html = '<option value="">--PO No--</option>';
            } else {
            $html = '';
            $html = '<option value="">--PO No--</option>';
            
            foreach ($POList as $row) 
            {$html .= '<option value="'.$row->pur_code.'">'.$row->pur_code.'</option>';}
        }
          return response()->json(['html' => $html]);
    }
    
    public function getItemCodeList(Request $request)
    { 
        $ItemList = DB::SELECT("SELECT spare_item_code FROM item_master WHERE delflag=0 AND class_id =".$request->class_id);
        $item_arr = [];
        foreach($ItemList as $row)
        {
            $item_arr[] = $row->spare_item_code;
        }
        return response()->json(['ItemList' => $item_arr]);
    }

    public function GetSpareItemMasterData(Request $request)
    { 
        $ItemData= DB::select('select spare_item_master.*,machine_model_master.mc_model_name from spare_item_master 
                    LEFT JOIN machine_model_master ON machine_model_master.mc_model_id = spare_item_master.mc_model_id 
                    WHERE spare_item_master.spare_item_code = '.$request->spare_item_code); 
        return response()->json(['ItemData' => $ItemData]);
    }

    public function GetSpareItemDetail(Request $request)
    {
        $html=''; 
        $no=1;
        
        $class_ids=$request->class_ids; 
              
        $class_ids_array = explode(',', $class_ids); // Convert to array
        
        $itemlist1 = DB::table('spare_item_master')
            ->where('delflag', '=', '0')
            ->whereIn('class_id', $class_ids_array)
            ->get();
            
         // dd(DB::getQueryLog());
       
            
        $unitlist=DB::table('unit_master')->where('delflag','=','0')->get();

        $html .='<tr class="cls_">';
        $html .='
        <td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px; height:30px;"/></td>
        <td><button type="button" onclick="insertRow(this);mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
    
        <td> <select name="spare_item_codes[]"  id="spare_item_code" style="width:250px; height:30px;" onchange="GetSpareItemMasterData(this);" >
        <option value="">--Select Item--</option>';
        foreach($itemlist1 as  $row1)
        {
            $html.='<option value="'.$row1->spare_item_code.'"';
            $html.='> ('.$row1->spare_item_code.') '.$row1->item_name.'</option>';
        }
        $html.='</select></td> 
        <td class="model_cls" nowrap></td>
        <td><input type="text"  name="hsn_code[]" value="" id="hsn_code" style="width:80px;"  readOnly/> </td>';
        $html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;  height:30px;"  disabled>
        <option value="">--Select Unit--</option>';
        foreach($unitlist as  $rowunit)
        {
            $html.='<option value="'.$rowunit->unit_id.'"';
            $html.='>'.$rowunit->unit_name.'</option>';
        }
        $html.='</select></td>';
        $html.='
        <td><input type="number" step="any" class="ITEMQTY"   name="item_qtys[]"  value="0" id="item_qty" style="width:80px;  height:30px;" required/></td>
        <td><input type="number" step="any" name="item_rates[]"  value="0" class="RATE"  id="item_rate" style="width:80px; height:30px;"  required/></td>
        <td><input type="number" step="any" name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="pur_cgsts[]" readOnly value="0" class="pur_cgsts"  id="pur_cgst" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="camts[]" readOnly value="0" class="GSTAMT"  id="camt" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="pur_sgsts[]" readOnly value="0" class=""  id="pur_sgst" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="samts[]" readOnly  value="0" class="GSTAMT"  id="samt" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="pur_igsts[]" readOnly value="0" class=""  id="pur_igst" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="iamts[]" readOnly value="0" class="GSTAMT"  id="iamt" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="amounts[]" readOnly value="0" class="GROSS"  id="amount" style="width:80px; height:30px;" required/></td>
        <td><input type="number" step="any" name="freight_hsn[]" readOnly class="" id="freight_hsn" value="0" style="width:80px; height:30px;"></td>
        <td><input type="number" step="any" name="freight_amt[]" onkeyup="calFreightAmt(this);"  class="FREIGHT" id="freight_amt" value="0" style="width:80px; height:30px;"></td>
        <td><input type="number" step="any" name="total_amounts[]" readOnly class="TOTAMT" value="0"  id="total_amount" style="width:80px; height:30px;" required/></td>';
        $html .='</tr>';
     
        return response()->json(['html' => $html]);
    }
    
    public function SparePurchaseOrderPrint($id)
    { 
        //DB::enableQueryLog();
        $SparePurchaseList = SparePurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'spare_purchase_order.Ac_code')
         ->leftjoin('usermaster', 'usermaster.userId', '=', 'spare_purchase_order.userId')
         ->leftjoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'spare_purchase_order.tax_type_id')
         ->leftjoin('firm_master', 'firm_master.firm_id', '=', 'spare_purchase_order.firm_id')    
         ->where('spare_purchase_order.delflag','=', '0')
         ->where('spare_purchase_order.sr_no','=', $id)
         ->get(['spare_purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','lm1.address as address','lm1.gst_no','lm1.pan_no','firm_master.firm_name','tax_type_master.tax_type_name']);
        //dd(DB::getQueryLog());
        return view('SparePurchaseOrderPrint', compact('SparePurchaseList'));
    }
    
    
}
