<?php

namespace App\Http\Controllers;

use App\Models\TrimsInwardMasterModel;
use App\Models\TrimsInwardDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\RackModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\PurchaseOrderModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use Illuminate\Support\Facades\DB;
use Session;


class TrimsInwardController extends Controller
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
            ->where('form_id', '100')
            ->first();
            
            
           $data = TrimsInwardMasterModel::join('ledger_master','ledger_master.ac_code','=','trimsInwardMaster.Ac_code')
        ->join('usermaster','usermaster.userId', '=', 'trimsInwardMaster.userId')
         ->leftJoin('po_type_master', 'po_type_master.po_type_id', '=', 'trimsInwardMaster.po_type_id')
         ->where('trimsInwardMaster.delflag','=', '0')
          ->get(['trimsInwardMaster.*','usermaster.username','ledger_master.ac_name','po_type_master.po_type_name']); 

        return view('TrimsInwardList', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {


                $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='TrimMaster' and c_name='C1'"));
                
                $JobStatusList = JobStatusModel::where('job_status_master.delflag','=', '0')->get();
                $firmlist = DB::table('firm_master')->get();
                 
                 $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
                $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
                $gstlist = DB::table('tax_type_master')->get();
                $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id', '1')->get();
                $itemlist = DB::table('item_master')->where('item_master.delflag','0')->where('item_master.cat_id','!=','1')->get();
                $unitlist = DB::table('unit_master')->get();
             
                $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','!=', '1')->get();
return view('TrimsInward',compact('firmlist','RackList','ledgerlist','gstlist','itemlist','code','unitlist','POTypeList','JobStatusList' ,'POList'));     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
  $firm_id=$request->input('firm_id');      
  $is_opening=isset($request->is_opening) ? 1 : 0;
//DB::enableQueryLog();
  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','TrimMaster')
   ->where('firm_id','=',1)
  ->first();
  //DB::enableQueryLog();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.''.$codefetch->tr_no;
// DB::enableQueryLog();
$sr_no = TrimsInwardMasterModel::max('sr_no');
// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);
$po_code='';
if($is_opening==1){$po_code='OS'.($sr_no+1);}else{ $po_code= $request->input('po_code');} 

$data = array('trimCode'=>$TrNo,
"po_code"=> $po_code,  
"trimDate"=> $request->input('trimDate'),
"invoice_no"=> $request->input('invoice_no'),
"invoice_date"=> $request->input('invoice_date'),
"Ac_code"=> $request->input('Ac_code'),
"po_type_id"=> $request->input('po_type_id'),
"totalqty"=> $request->input('totalqty'),
'total_amount' => $request->total_amount,
"delflag"=>0,
'is_opening'=>$is_opening,
"userId"=> $request->input('userId')
);

// Insert
$value = TrimsInwardMasterModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}



$cnt = $request->input('cnt');

$itemcodes=count($request->item_codes);



if($cnt>0)
{

for($x=0;$x<$itemcodes; $x++) {
# code...

$data2[]=array(

'trimCode' =>$TrNo,
'trimDate' => $request->input('trimDate'),
'Ac_code' => $request->input('Ac_code'),
 "po_code"=> $po_code,  
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_codes[$x],
'unit_id' => $request->unit_ids[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'amount' => $request->amounts[$x],
'rack_id' => $request->rack_id[$x]

);
}
TrimsInwardDetailModel::insert($data2);
}

$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='TrimMaster' AND firm_id=1");  



  return redirect()->route('TrimsInward.index')->with('message', 'Add Record Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '9')
        ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
         ->where('purchase_order.approveFlag','=', '1')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }
    
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '9')
        ->first();

        $data = PurchaseOrderModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_order.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_order.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_order.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_order.firm_id')    
        ->where('purchase_order.delflag','=', '0')
         ->where('purchase_order.approveFlag','=', '2')
        ->get(['purchase_order.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $JobStatusList = JobStatusModel::where('job_status_master.delflag','=', '0')->get();
        $firmlist = DB::table('firm_master')->where('firm_master.delflag','=', '0')->get();
        $gstlist = DB::table('tax_type_master')->get();
        $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id', '1')->get();
        $itemlist = DB::table('item_master')->where('item_master.delflag','0')->where('item_master.cat_id','!=','1')->get();
        $unitlist = DB::table('unit_master')->where('unit_master.delflag','=', '0')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $purchasefetch = TrimsInwardMasterModel::find($id);
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','!=', '1')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
     $detailpurchase = TrimsInwardDetailModel::join('item_master','item_master.item_code', '=', 'trimsInwardDetail.item_code')
  ->where('trimCode','=', $purchasefetch->trimCode)->get(['trimsInwardDetail.*','item_master.item_name','item_master.item_description']);


        return view('TrimsInwardEdit',compact('POList','RackList','purchasefetch','firmlist','ledgerlist','gstlist','itemlist','detailpurchase','unitlist','POTypeList','JobStatusList','BOMLIST'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code)
    {
        
        
        $is_opening=isset($request->is_opening) ? 1 : 0;
       $po_code='';
     
    
    //  DB::enableQueryLog();
  $sr_no = TrimsInwardMasterModel::select('sr_no')->where('trimCode','=',$request->trimCode)->get();
// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);
    
    
       if($is_opening==1){$po_code='OS'.($sr_no[0]->sr_no);}else{ $po_code= $request->input('po_code');} 
       
   $data = array('trimCode'=>$request->trimCode,
   "po_code"=> $po_code,  
    "trimDate"=> $request->input('trimDate'),
    "invoice_no"=> $request->input('invoice_no'),
    "invoice_date"=> $request->input('invoice_date'),
    "Ac_code"=> $request->input('Ac_code'),
    "po_type_id"=> $request->input('po_type_id'),
    "totalqty"=> $request->input('totalqty'),
    'total_amount' => $request->total_amount,
    "delflag"=>0,
    'is_opening'=>$is_opening,
    "userId"=> $request->input('userId')
    );
 
 
// Insert
$purchase = TrimsInwardMasterModel::findOrFail($pur_code);  

$purchase->fill($data)->save();



   DB::table('trimsInwardDetail')->where('trimCode', $request->input('trimCode'))->delete();


$cnt = $request->input('cnt');

$itemcodes=count($request->item_codes);

if($cnt>0)
{

for($x=0;$x<$itemcodes;$x++) {
# code...

$data2[]=array(

'trimCode' =>$request->trimCode,
'trimDate' => $request->input('trimDate'),
'Ac_code' => $request->input('Ac_code'),
 "po_code"=> $po_code,  
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_codes[$x],
'unit_id' => $request->unit_ids[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'amount' => $request->amounts[$x],
'rack_id' => $request->rack_id[$x]);

}
TrimsInwardDetailModel::insert($data2);
    
    
}

  return redirect()->route('TrimsInward.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrderModel  $purchaseOrderModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($trimCode)
    {
        
          $trimCode=base64_decode($trimCode);
          
            $master =TrimsInwardMasterModel::where('trimCode',$trimCode)->delete(); 
            
            $detail =TrimsInwardDetailModel::where('trimCode',$trimCode)->delete();
         
            Session::flash('delete', 'Deleted record successfully'); 
          
        
    }
    
     public function closestatus($id)
    {
              
          
        
    }
    
     public function GetPartyDetails(Request $request)
    {
        
            $ac_code= $request->input('ac_code');
            $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='".$ac_code."' and delflag=0");
            return json_encode($PartyRecords);
        
          
        
    
    
    }
    
    
    
     public function getBoMDetail(Request $request)
    {
    
  

 $itemlist=DB::table('item_master')
   ->get();

    $unitlist=DB::table('unit_master')
   ->get();
   
       
     



     if($request->type==1)
     {
         
      $table="bom_fabric_details"; 

      
     } 
     else if($request->type==2)
     {
         
    $table="bom_sewing_trims_details"; 
         
     } else if($request->type==3)
     {
         
          $table="bom_packing_trims_details"; 
         

     }

//DB::enableQueryLog();

  $bom_codeids=explode(',',$request->bom_code);

  $data=DB::table($table)
  ->whereIn('bom_code',$bom_codeids)
  ->get();
  
   //dd(DB::getQueryLog());
 

   $html='';

$no=1;

     foreach ($data as $value) {
          

  

if($request->tax_type_id==1)
{
    
$datagst = DB::select(DB::raw("SELECT item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$value->item_code'"));


                 $Camt=($value->total_amount * ($datagst[0]->cgst_per/100));
                 
                  $Samt=($value->total_amount * ($datagst[0]->sgst_per/100));
                  
                  $Iamt=0;                 
              
                  $TAmount=$value->total_amount + $Camt+ $Samt + 0;
                  
                  $igst_per=0;
                  
                  
                

} else  if($request->tax_type_id==2)
{

$datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$value->item_code'"));


    $Iamt=($value->total_amount * ($datagst[0]->igst_per/100));
    
    $Camt=0;
    $Samt=0;
    
  $TAmount=$value->total_amount + $Iamt + 0;

} 
   else if($request->tax_type_id==3)
{
    
$datagst = DB::select(DB::raw("SELECT item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per=0 as igst_per,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$value->item_code'"));

}
        
    
   $html .='<tr id="bomdis">';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
<td> <select name="item_codes[]"  id="item_code" style="width:100px;" required>
<option value="">--Select Item--</option>';

foreach($itemlist as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> 



<td>
 <img  src="https://ken.korbofx.org/thumbnail/'.$datagst[0]->item_image_path.'"  id="item_image" name="item_image[]" class="imgs">
 
<input type="hidden"  name="hsn_code[]" value="'.$datagst[0]->hsn_code.'" id="hsn_code" style="width:80px;" required/> </td>';

$html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;" required>
<option value="">--Select Unit--</option>';

foreach($unitlist as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$rowunit->unit_name.'</option>';
}
 
$html.='</select></td>';

 
$html.='
<td><input type="text"   name="item_qtys[]"   value="'.$value->bom_qty.'" id="item_qty" style="width:80px;" required/>
	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
</td>
<td><input type="text"   name="item_rates[]"  value="'.$value->rate_per_unit.'" class="RATE"  id="item_rate" style="width:80px;" required/></td>
<td><input type="text"   name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px;" required/></td>
<td><input type="text"   name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px;" required/></td>
<td><input type="text"   name="pur_cgsts[]"  value="'.$datagst[0]->cgst_per.'" class="pur_cgsts"  id="pur_cgst" style="width:80px;" required/></td>
<td><input type="text"   name="camts[]"  value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_sgsts[]"  value="'.$datagst[0]->sgst_per.'" class=""  id="pur_sgst" style="width:80px;" required/></td>
<td><input type="text"   name="samts[]"  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_igsts[]"  value="'.$datagst[0]->igst_per.'" class=""  id="pur_igst" style="width:80px;" required/></td>
<td><input type="text"   name="iamts[]"  value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px;" required/></td>
<td><input type="text"   name="amounts[]"  value="'.$value->total_amount.'" class="GROSS"  id="amount" style="width:80px;" required/></td>
<td><input type="text" name="freight_hsn[]" class="" id="freight_hsn" value="0" style="width:80px;"></td>

<td><input type="text" name="freight_amt[]" class="FREIGHT" id="freight_amt" value="0" style="width:80px;"></td>


<td><input type="text"   name="total_amounts[]"  class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px;" required/></td>

<td><button type="button" onclick="insertRow();mycalc(); " class="btn btn-warning pull-left">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;

      }


return response()->json(['html' => $html]);
    
    
    }
    
 
    
    
       public function getPoForTrims(Request $request)
    {

    $po_code= base64_decode($request->po_code);
    $itemlist=DB::table('item_master')->where('item_master.cat_id','!=','1')->where('item_master.delflag','0')->get();
    $unitlist=DB::table('unit_master')->where('unit_master.delflag','0')->get();
     $RackList=DB::table('rack_master')->where('rack_master.delflag','0')->get();

     $data=DB::select(DB::raw("SELECT   `pur_code`, `pur_date`, `Ac_code`, 
     purchaseorder_detail.item_code,item_master.item_description, purchaseorder_detail.hsn_code,
     purchaseorder_detail.unit_id,purchaseorder_detail.item_rate, sum(purchaseorder_detail.item_qty)  as totalQty   FROM   purchaseorder_detail
     inner join item_master on item_master.item_code=purchaseorder_detail.item_code
     where pur_code='".$po_code."'  group by item_code"));
        
   $html='';

       $html .= '
       <div class="table-wrap" id="trimInward">
<div class="table-responsive">
       <table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>SrNo</th>
<th>Item Code</th>
<th>Item Name</th>
<th>Item Description</th>
<th>Unit</th>
<th>Qty</th>
<th>Item Rate</th>
<th>Amount</th>
<th>Rack</th>

<th>Add/Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
      foreach ($data as $value) {
    
   $html .='<tr>';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
 <td>'.$value->item_code.'</td>
 
<td> <select name="item_codes[]"  id="item_codes" style="width:300px; height:30px;" required disabled >
<option value="">--Select Item--</option>';

foreach($itemlist as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> ';
 $html.='<td><input type="text" value="'.$value->item_description.'" style="width:250px;height:30px;" readOnly required/>';
$html .='<td> <select name="unit_ids[]"  id="unit_ids" style="width:100px; height:30px;" required disabled >
<option value="">--Select Unit--</option>';

foreach($unitlist as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$rowunit->unit_name.'</option>';
}
 
$html.='</select></td>';
 
$html.='<td><input type="text" class="QTY"  name="item_qtys[]"    value="'.round($value->totalQty,2).'" id="item_qty" style="width:80px;height:30px;" required/>
<input type="hidden"  name="hsn_code[]" value="'.$value->hsn_code  .'" id="hsn_code" style="width:80px; height:30px;" readOnly required/>
</td>';
$html.='<td><input type="text"   name="item_rates[]" readOnly  value="'.round($value->item_rate,5).'" id="item_rates" style="width:80px;height:30px;" required/></td>';

$html.='<td><input type="text" class="AMT"  name="amounts[]" readOnly  value="'.(round($value->totalQty,2)*round($value->item_rate,2)).'" id="amounts" style="width:80px;height:30px;" required/></td>';



$html .='<td> <select name="rack_ids[]"  id="rack_ids" style="width:100px; height:30px;" required  >
<option value="">--Select Rack--</option>';

foreach($RackList as  $rowrack)
{
    $html.='<option value="'.$rowrack->rack_id.'"';
 
    $html.='>'.$rowrack->rack_name.'</option>';
}
 
$html.='</select></td>


<td><button type="button" onclick=" mycalc(); " class="btn btn-warning pull-left Abutton">+</button> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;


      }

       $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';
       $html .='</table>
       </div>
</div>';


return response()->json(['html' => $html]);

    
    }
    
    
           public function getPoMasterDetailTrims(Request $request)
    {


         $po_codee= base64_decode($request->po_code);

    $data=DB::table('purchase_order')->where('pur_code','=',$po_codee)
   ->get(['purchase_order.*']);

 
  return $data;


    }
    
    
    
    
    
    public function TrimsGRNData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        
        
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        
        // DB::enableQueryLog();
        $TrimsInwardDetails = TrimsInwardDetailModel::
          leftJoin('ledger_master', 'ledger_master.ac_code', '=', 'trimsInwardDetail.Ac_code')
          ->leftJoin('item_master', 'item_master.item_code', '=', 'trimsInwardDetail.item_code')
          ->leftJoin('rack_master', 'rack_master.rack_id', '=', 'trimsInwardDetail.rack_id')
          ->leftJoin('trimsInwardMaster', 'trimsInwardMaster.trimCode', '=', 'trimsInwardDetail.trimCode')
          
          ->get(['trimsInwardDetail.*', 'trimsInwardMaster.is_opening', 'trimsInwardMaster.invoice_no','trimsInwardMaster.po_code',  'trimsInwardMaster.invoice_date',  'ledger_master.ac_name','item_master.dimension', 'item_master.item_name','item_master.color_name','item_master.item_description', 'rack_master.rack_name']);
     // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('TrimsGRNData',compact('TrimsInwardDetails'));
    }
    
    
     public function TrimsStockData()
    {
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '1')->get();
        $FGList =  DB::table('fg_master')->get();
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->where('item_master.cat_id','=', '1')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_status','=', '1')->where('purchase_order.bom_type','=', '1')->get();
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $RackMasterList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        // DB::enableQueryLog();
        $TrimsInwardDetails = DB::select("select trimsInwardDetail.*,
        
        (select ifnull(sum(item_qty),0)  from trimsOutwardDetail 
        where trimsOutwardDetail.po_code=trimsInwardDetail.po_code and trimsOutwardDetail.item_code=trimsInwardDetail.item_code) as out_qty ,
        
        trimsInwardMaster.is_opening, trimsInwardMaster.invoice_no,trimsInwardMaster.po_code, 
        trimsInwardMaster.invoice_date,  ledger_master.ac_name,item_master.dimension,item_master.item_name,
        item_master.color_name,item_master.item_description,rack_master.rack_name
        from trimsInwardDetail inner join trimsInwardMaster on trimsInwardMaster.trimCode=trimsInwardDetail.trimCode
        inner join ledger_master on ledger_master.ac_code=trimsInwardDetail.ac_code
        inner join item_master on item_master.item_code=trimsInwardDetail.item_code
        inner join rack_master on rack_master.rack_id=trimsInwardDetail.rack_id");
         
    
     // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('TrimsStockData',compact('TrimsInwardDetails'));
    }
    
    
    
    
    
     public function TrimsGRNPrint($trimCode)
    {
        
         $FirmDetail =  DB::table('firm_master')->first();
         
         $trimCode=base64_decode($trimCode);
         $TrimsInwardMaster = TrimsInwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'trimsInwardMaster.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'trimsInwardMaster.Ac_code')
         ->where('trimsInwardMaster.trimCode', $trimCode)
         ->get(['trimsInwardMaster.*','usermaster.username','ledger_master.Ac_name', 'ledger_master.gst_no','ledger_master.pan_no','ledger_master.state_id','ledger_master.address']);
         return view('TrimsGRNPrint', compact('FirmDetail','TrimsInwardMaster'));
      
    }
    
    
   
    
}
