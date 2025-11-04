<?php

namespace App\Http\Controllers;

use App\Models\MaterialInwardStoreModel;
use App\Models\MIStoreDetailModel;
use Illuminate\Http\Request;
use DB;
use Session;

class MaterialInwardStoreController extends Controller
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
->where('form_id', '56')
->first();

        $data = MaterialInwardStoreModel::join('ledger_master as lm1','lm1.ac_code', '=', 'store_inward_master.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'store_inward_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'store_inward_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'store_inward_master.firm_id')   
          ->join('inwardtype', 'inwardtype.inwardTypeId', '=', 'store_inward_master.inwardTypeId')   
        ->where('store_inward_master.delflag','=', '0')
         ->where('store_inward_master.inwardApproveFlag','=', '0')    
        ->get(['store_inward_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','inwardtype.inwardType']);

        return view('storeInwardList', compact('data','chekform'));
    
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
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$locationlist = DB::table('location_master')->get();
$inwardTypelist = DB::table('inwardtype')->get();
$polist=DB::table('purchase_order')->get();
$unitlist = DB::table('unit_master')->get();


return view('MaterialInwardStore',compact('firmlist','ledgerlist','gstlist','itemlist','code','locationlist','inwardTypelist','polist','unitlist')); 



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

//DB::enableQueryLog();
  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','MaterialInwardStore')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;


     
 $data = array('storeInCode'=>$TrNo,
"storeInward_date"=> $request->input('pur_date'),
"inwardTypeId"=> $request->input('inwardTypeId'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"pur_bill_no"=> $request->input('pur_bill_no'),
"pur_bill_date"=> $request->input('pur_bill_date'),
"dc_no"=> $request->input('dc_no'),
"dc_date"=> $request->input('dc_date'),
"po_no"=> $request->input('po_no'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"totFreightAmt"=> $request->input('totFreightAmt'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $codefetch->c_code,
"loc_id"=> $request->input('loc_id'),
"gstNo"=> $request->input('gstNo'),
"address"=> $request->input('address'),
"userId"=> $request->input('userId'),
"inwardApproveFlag"=> 0,
"delflag"=>0
);

// Insert
$value = MaterialInwardStoreModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}




$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0; $x<$cnt; $x++) {
# code...

$data2=array(

'storeInCode' =>$TrNo,
'storeInward_date' => $request->input('pur_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->item_codes[$x],
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
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
'firm_id' => $request->firm_id);

MIStoreDetailModel::insert($data2);



DB::table('itemtransaction')->insert([
'trNo' => $TrNo,
'trDate' => $request->input('pur_date'),
'trType' => 1,
'item_code' => $request->item_codes[$x],
'qtyIn' => $request->item_qtys[$x],
'qtyOut' =>0,
'isDeleted' =>0,
'isModify' => date('Y-m-d h:i:s')
]); 
                  

}

}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='MaterialInwardStore' AND firm_id='".$request->input('firm_id')."'");  


  return redirect()->route('InwardStore.index')->with('message', 'Add Record Succesfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialInwardStoreModel  $materialInwardStoreModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        
 $chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '44')
->first();

        $data = MaterialInwardStoreModel::join('ledger_master as lm1','lm1.ac_code', '=', 'store_inward_master.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'store_inward_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'store_inward_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'store_inward_master.firm_id')   
          ->join('inwardtype', 'inwardtype.inwardTypeId', '=', 'store_inward_master.inwardTypeId')   
        ->where('store_inward_master.delflag','=', '0')
         ->where('store_inward_master.inwardApproveFlag','=', '1')    
        ->get(['store_inward_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','inwardtype.inwardType']);

        return view('inwardApprovalList', compact('data','chekform'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialInwardStoreModel  $materialInwardStoreModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     
      $firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$locationlist = DB::table('location_master')->get();
$inwardTypelist = DB::table('inwardtype')->get();
$polist=DB::table('purchase_order')->get();
   $unitlist=DB::table('unit_master')
   ->get();

        $purchasefetch = MaterialInwardStoreModel::find($id);

 $detailpurchase = MIStoreDetailModel::join('item_master','item_master.item_code', '=', 'store_inward_detail.item_code')
  ->where('storeInCode','=', $purchasefetch->storeInCode)->get(['store_inward_detail.*','item_master.item_name']);


        return view('MaterialInwardStoreEdit',compact('purchasefetch','firmlist','ledgerlist','gstlist','itemlist','detailpurchase','locationlist','inwardTypelist','polist','unitlist')); 


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialInwardStoreModel  $materialInwardStoreModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $incode)
    {
   
   
$data = array('storeInCode'=>$request->input('pur_code'),
"storeInward_date"=> $request->input('pur_date'),
"inwardTypeId"=> $request->input('inwardTypeId'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"pur_bill_no"=> $request->input('pur_bill_no'),
"pur_bill_date"=> $request->input('pur_bill_date'),
"dc_no"=> $request->input('dc_no'),
"dc_date"=> $request->input('dc_date'),
"po_no"=> $request->input('po_no'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"totFreightAmt"=> $request->input('totFreightAmt'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"loc_id"=> $request->input('loc_id'),
"gstNo"=> $request->input('gstNo'),
"address"=> $request->input('address'),
"userId"=> $request->input('userId'),
"inwardApproveFlag"=> $request->input('inwardApproveFlag'),
"delflag"=>0
);

// Insert
$purchase = MaterialInwardStoreModel::findOrFail($incode);  

$purchase->fill($data)->save();


   DB::table('store_inward_detail')->where('storeInCode', $request->input('pur_code'))->delete();

   DB::table('itemtransaction')->where('trNo', $request->input('pur_code'))->delete();

   


$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0;$x<$cnt;$x++) {
# code...

$data2=array(

'storeInCode' =>$request->input('pur_code'),
'storeInward_date' => $request->input('pur_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->input('item_codes')[$x], 
'hsn_code' => $request->input('hsn_code')[$x], 
'unit_id' => $request->input('unit_id')[$x], 
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
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
'firm_id' => $request->firm_id);

MIStoreDetailModel::insert($data2);


DB::table('itemtransaction')->insert([
'trNo' => $request->input('pur_code'),
'trDate' => $request->input('pur_date'),
'trType' => 1,
'item_code' => $request->item_codes[$x],
'qtyIn' => $request->item_qtys[$x],
'qtyOut' =>0,
'isDeleted' =>0,
'isModify' => date('Y-m-d h:i:s')
]); 



}
}

  return redirect()->route('InwardStore.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialInwardStoreModel  $materialInwardStoreModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
         $master=MaterialInwardStoreModel::where('storeInCode',$id)->delete();

      $detail=MIStoreDetailModel::where('storeInCode',$id)->delete();

     Session::flash('delete', 'Deleted record successfully'); 
    
    }


   public function getPoDetails(Request $request)
    {
    


       $pur_code= $request->pur_code;

 $itemlist=DB::table('item_master')
   ->get();

    $unitlist=DB::table('unit_master')
   ->get();



   $data=DB::table('purchaseorder_detail')
   ->where('pur_code','=',$pur_code)
   ->get();

   $html='';

       $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th><button type="button" onclick="insertRow();mycalc();" class="btn btn-warning pull-left">+</button></th>
<th>Item Name</th>
<th>HSN</th>
<th>Quantity</th>
<th>Rate</th>
<th>CGST%</th>
<th>CAMT</th>
<th>SGST%</th>
<th>SAMT</th>
<th>IGST%</th>
<th>IAMT</th>
<th>Amount</th>
<th>Freight HSN</th>
<th>Freight</th>
<th>Total Amount</th>
<th>Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
      foreach ($data as $value) {
    
   $html .='<tr>';
    
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

<td><input type="text"  name="hsn_code[]" value="'.$value->hsn_code  .'" id="hsn_code" style="width:80px;" required/> </td>';
 
$html.='<td><input type="text"   name="item_qtys[]"   value="'.$value->item_qty.'" id="item_qty" style="width:80px;" required/></td>

<td><input type="text"   name="item_rates[]"  value="'.$value->item_rate.'" class="RATE"  id="item_rate" style="width:80px;" required/></td>
<td><input type="text"   name="pur_cgsts[]"  value="'.$value->pur_cgst.'" class="pur_cgsts"  id="pur_cgst" style="width:80px;" required/></td>
<td><input type="text"   name="camts[]"  value="'.$value->camt.'" class="GSTAMT"  id="camt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_sgsts[]"  value="'.$value->pur_sgst.'" class=""  id="pur_sgst" style="width:80px;" required/></td>
<td><input type="text"   name="samts[]"  value="'.$value->samt.'" class="GSTAMT"  id="samt" style="width:80px;" required/></td>
<td><input type="text"   name="pur_igsts[]"  value="'.$value->pur_igst.'" class=""  id="pur_igst" style="width:80px;" required/></td>
<td><input type="text"   name="iamts[]"  value="'.$value->iamt.'" class="GSTAMT"  id="iamt" style="width:80px;" required/></td>
<td><input type="text"   name="amounts[]"  value="'.$value->amount.'" class="GROSS"  id="amount" style="width:80px;" required/></td>
<td><input type="text" name="freight_hsn[]" class="" id="freight_hsn" value="'.$value->freight_hsn.'" style="width:80px;"></td>

<td><input type="text" name="freight_amt[]" class="FREIGHT" id="freight_amt" value="'.$value->freight_amt.'" style="width:80px;"></td>

<td><input type="text"   name="total_amounts[]"  class="TOTAMT" value="'.$value->total_amount.'"  id="total_amount" style="width:80px;" required/></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;


      }

       $html .='<input type="number" value="'.count($data).'" name="cnt" id="cnt" readonly="" hidden="true"  />';
       $html .='</table>';


return response()->json(['html' => $html]);

    
    }


      public function getPoMasterDetails(Request $request)
    {

         $pur_code= $request->pur_code;

    $data=DB::table('purchase_order')->where('pur_code','=',$pur_code)
   ->get(['purchase_order.*']);

 
  return $data;


    }

}