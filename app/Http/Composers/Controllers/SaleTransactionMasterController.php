<?php

namespace App\Http\Controllers;

use App\Models\SaleTransactionMasterModel;
use App\Models\SaleTransactionDetailModel;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ItemModel;
use App\Models\UnitModel;
use App\Models\SizeModel;
use App\Models\POTypeModel;
use App\Models\JobStatusModel;
use App\Models\ClassificationModel;
use App\Models\FabricTransactionModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;

class SaleTransactionMasterController extends Controller
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
            ->where('form_id', '115')
            ->first();
 
  
        $SaleTransactionMasterList = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        ->leftJoin('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
         ->leftJoin('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
          ->leftJoin('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        ->where('sale_transaction_master.delflag','=', '0')
         ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('SaleTransactionMasterList', compact('SaleTransactionMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
 
                $code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='SaleTransaction' and c_name='C1'"));
                $firmlist = DB::table('firm_master')->get();
                $ledgerlist = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.bt_id','=', '2')->where('ledger_master.ac_code','>', '39')->get();
                $gstlist = DB::table('tax_type_master')->get();
                $unitlist = DB::table('unit_master')->get();
                

				return view('SaleTransactionMaster',compact('firmlist','ledgerlist','gstlist', 'code','unitlist' ));     
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
  ->where('type','=','SaleTransaction')
   ->where('firm_id','=',$firm_id)
  ->first();
  //DB::enableQueryLog();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/

 

 $TrNo=$codefetch->code.'/21-22/'.'KDPL'.$codefetch->tr_no;
 

$carton_packing_nos=implode(",",$request->input('carton_packing_no'));

$data = array('sale_code'=>$TrNo,
"carton_packing_nos"=>$carton_packing_nos,
"sale_date"=> $request->input('sale_date'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"total_qty"=> $request->input('total_qty'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $codefetch->c_code,
"terms_and_conditions"=> $request->input('terms_and_conditions'),
"userId"=> $request->input('userId'),
"delflag"=>0
);

// Insert
$value = SaleTransactionMasterModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}
 
$sales_order_no=count($request->sales_order_no);

 

if($sales_order_no>0)
{

for($x=0;$x<$sales_order_no; $x++) {
# code...

$data2=array(

'sale_code' =>$TrNo,
'sale_date' => $request->input('sale_date'),
'Ac_code' => $request->input('Ac_code'),
'sales_order_no' => $request->sales_order_no[$x],
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'order_qty' => $request->order_qty[$x],
'order_rate' => $request->order_rate[$x],
'disc_per' => $request->disc_pers[$x],
'disc_amount' => $request->disc_amounts[$x],
'sale_cgst' => $request->sale_cgsts[$x],
'camt' => $request->camts[$x],
'sale_sgst' => $request->sale_sgsts[$x],
'samt' => $request->samts[$x],
'sale_igst' => $request->sale_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'total_amount' => $request->total_amounts[$x],
'firm_id' => $request->firm_id);

SaleTransactionDetailModel::insert($data2);


}

}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='SaleTransaction' AND firm_id='".$request->input('firm_id')."'");  



  return redirect()->route('SaleTransaction.index')->with('message', 'Add Record Succesfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '115')
        ->first();

        $data = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        ->where('sale_transaction_master.delflag','=', '0')
         ->where('sale_transaction_master.approveFlag','=', '1')
        ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }
    
    
    
     public function Disapprovedshow()
    {

   
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '115')
        ->first();

        $data = SaleTransactionMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'sale_transaction_master.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'sale_transaction_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_transaction_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'sale_transaction_master.firm_id')    
        ->where('sale_transaction_master.delflag','=', '0')
         ->where('sale_transaction_master.approveFlag','=', '2')
        ->get(['sale_transaction_master.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('POApprovalList', compact('data','chekform'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $id=base64_decode($id);
        $firmlist = DB::table('firm_master')->get();
        $ledgerlist = DB::table('ledger_master')->get();
        $gstlist = DB::table('tax_type_master')->get();
        
        $unitlist = DB::table('unit_master')->get();
    
        $SaleTransactionMasterList = SaleTransactionMasterModel::find($id);
        
         $CartonPackingList = DB::select("select distinct cpki_code,sales_order_no from carton_packing_inhouse_size_detail where Ac_code ='".$SaleTransactionMasterList->Ac_code."'");

	 $SaleTransactionDetails = SaleTransactionDetailModel::
	   where('sale_code','=', $SaleTransactionMasterList->sale_code)->get(['sale_transaction_detail.*']);


        return view('SaleTransactionMasterEdit',compact('SaleTransactionMasterList','firmlist','CartonPackingList','ledgerlist','gstlist', 'SaleTransactionDetails','unitlist' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sale_code)
    {
       // print_r($sale_code); exit;
  
$sale_code=base64_decode($sale_code);


$carton_packing_nos=implode(",",$request->input('carton_packing_no'));

 




$data = array('sale_code'=>$sale_code,
"sale_date"=> $request->input('sale_date'),
"Ac_code"=> $request->input('Ac_code'),
"carton_packing_nos"=>$carton_packing_nos,
"tax_type_id"=> $request->input('tax_type_id'),
"total_qty"=> $request->input('total_qty'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"userId"=> $request->input('userId'),
"terms_and_conditions"=> $request->input('terms_and_conditions'),
 
"delflag"=>0,
 
);

// Insert
$SalesTransactionMasterList = SaleTransactionMasterModel::findOrFail($sale_code);  

$SalesTransactionMasterList->fill($data)->save();
 

   DB::table('sale_transaction_detail')->where('sale_code', $sale_code)->delete();
 


 
$cnt=count($request->sales_order_no);

if($cnt>0)
{

for($x=0;$x<$cnt;$x++) {
# code...

$data2=array(
'sale_code' =>$sale_code,
'sale_date' => $request->input('sale_date'),
'Ac_code' => $request->input('Ac_code'),
'sales_order_no' => $request->input('sales_order_no')[$x], 
'hsn_code' => $request->hsn_code[$x],
'unit_id' => $request->unit_id[$x],
'order_qty' => $request->order_qty[$x],
'order_rate' => $request->order_rate[$x],
'disc_per' => $request->disc_pers[$x],
'disc_amount' => $request->disc_amounts[$x],
'sale_cgst' => $request->sale_cgsts[$x],
'camt' => $request->camts[$x],
'sale_sgst' => $request->sale_sgsts[$x],
'samt' => $request->samts[$x],
'sale_igst' => $request->sale_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'total_amount' => $request->total_amounts[$x],
'firm_id' => $request->firm_id);

//  DB::enableQueryLog();
SaleTransactionDetailModel::insert($data2);
//  $query = DB::getQueryLog();
// $query = end($query);
// dd($query); 

}
}

  return redirect()->route('SaleTransaction.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleTransactionMasterModel  $SaleTransactionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($sale_code)
    {
			$sale_code=base64_decode($sale_code);
            $master =SaleTransactionMasterModel::where('sale_code',$sale_code)->delete();      
            $detail =SaleTransactionDetailModel::where('sale_code',$sale_code)->delete();
            Session::flash('delete', 'Deleted record successfully'); 
    }
    
 
    
     public function GetPartyDetails(Request $request)
    {
        
            $ac_code= $request->input('ac_code');
            $PartyRecords = DB::select("select state_id ,gst_no from ledger_master where ac_code='".$ac_code."' and delflag=0");
            return json_encode($PartyRecords);
         
    }
    
       public function getSalesOrderData(Request $request)
    {
        
        
        $itemlist=DB::table('item_master')->get();
 $unitlist=DB::table('unit_master')->get();
 //DB::enableQueryLog();
$cpki_code='';
  $caron_packing_nos=explode(',',$request->carton_packing_nos);
    foreach($caron_packing_nos as $cpki)
        {
           $cpki_code=$cpki_code."'".$cpki."',";
        }
        $cpki_code=rtrim($cpki_code,",");




//   $data=DB::table('carton_packing_inhouse_size_detail')->select('tr_code as sales_order_no', 'order_rate','total_qty as order_qty' , 'unit_id')
//   ->whereIn('tr_code',$sales_order_nos)->get();
  
  //DB::enableQueryLog();
      $MasterdataList = DB::select("SELECT carton_packing_inhouse_size_detail.item_code, carton_packing_inhouse_size_detail.color_id, color_name, sales_order_no,buyer_purchse_order_master.unit_id,
      sum(size_qty_total) as size_qty_total from carton_packing_inhouse_size_detail
      inner join buyer_purchse_order_master on buyer_purchse_order_master.tr_code=carton_packing_inhouse_size_detail.sales_order_no
      inner join color_master on 
      color_master.color_id=carton_packing_inhouse_size_detail.color_id where cpki_code in (".$cpki_code.")
      group by carton_packing_inhouse_size_detail.color_id");
  
//   dd(DB::getQueryLog());
 

   $html='';

$no=1;

     foreach ($MasterdataList as $value) 
     {
          
        $order_rate=0;
        $order_qty=$value->size_qty_total;
        $total_amount =   $order_rate * $order_qty;
if($request->tax_type_id==1)
{
   $sgst=2.5;
   $cgst=2.5;
   $igst=0;
    $Camt=($total_amount * (2.5/100));
    $Samt=($total_amount * (2.5/100));
    $Iamt=0;                 
    $TAmount=$total_amount + $Camt+ $Samt;
    $igst_per=0;
     
} 
elseif($request->tax_type_id==2)
{ 
     $sgst=0;
   $cgst=0;
   $igst=5;
    $Iamt=($total_amount * (5/100));
    $Camt=0;
    $Samt=0;
    $TAmount=$total_amount + $Iamt;

} 
   
   $html .='<tr id="bomdis">';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
<td><input type="text" name="sales_order_no[]" required  readOnly id="sales_order_no" style="width:150px;" value="'.$value->sales_order_no.'"/>  </td> 
 
<td><input type="text"  name="hsn_code[]" value=""  required    id="hsn_code" style="width:100px;" required  /> </td>';

$html .='<td> <select name="unit_id[]"  id="unit_id" style="width:100px;" required disabled>
<option value="">--Select Unit--</option>';

foreach($unitlist as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$rowunit->unit_name.'</option>';
}
 
$html.='</select></td>';

 
$html.='
<td><input type="text" class="ITEMQTY"   name="order_qty[]" readOnly  value="'.$value->size_qty_total.'" id="order_qty" style="width:80px;" required/>
	<input type="hidden"  class="ROWCOUNT" id="ROWCOUNT"   value="1">
</td>
<td><input type="text"   name="order_rate[]"  value="'.$order_rate.'" class="RATE"  id="order_rate" style="width:80px;"  required  onchange="CalculateRow(this);"   /></td>
<td><input type="text"   name="disc_pers[]"  value="0" class=""  id="disc_per" style="width:80px;" required/></td>
<td><input type="text"   name="disc_amounts[]"  value="0" class=""  id="disc_amount" style="width:80px;" required  readOnly/></td>
<td><input type="text"   name="sale_cgsts[]" readOnly value="'.$cgst.'" class="sale_cgsts"  id="sale_cgst" style="width:80px;" required/></td>
<td><input type="text"   name="camts[]" readOnly value="'.number_format((float)$Camt, 2, '.', '').'" class="GSTAMT"  id="camt" style="width:80px;" required/></td>
<td><input type="text"   name="sale_sgsts[]" readOnly value="'.$sgst.'" class=""  id="sale_sgst" style="width:80px;" required/></td>
<td><input type="text"   name="samts[]" readOnly  value="'.number_format((float)$Samt, 2, '.', '').'" class="GSTAMT"  id="samt" style="width:80px;" required/></td>
<td><input type="text"   name="sale_igsts[]" readOnly value="'.$igst.'" class=""  id="sale_igst" style="width:80px;" required/></td>
<td><input type="text"   name="iamts[]" readOnly value="'.number_format((float)$Iamt, 2, '.', '').'" class="GSTAMT"  id="iamt" style="width:80px;" required/></td>
<td><input type="text"   name="amounts[]" readOnly value="'.number_format((float)$total_amount, 2, '.', '').'" class="GROSS"  id="amount" style="width:80px;" required/></td>
<td><input type="text"   name="total_amounts[]" readOnly class="TOTAMT" value="'.number_format((float)$TAmount, 2, '.', '').'"  id="total_amount" style="width:80px;" required/></td>

<td> <input type="button" class="btn btn-danger pull-left" onclick="deleteRow(this);" value="X" ></td>';
  
    $html .='</tr>';
    $no=$no+1;

      }


return response()->json(['html' => $html]);
         
    }
    
  
  
  
public function CartonPackingList(Request $request)
{
    //   DB::enableQueryLog();
         
     $CartonPackingList = DB::select("select distinct cpki_code,sales_order_no from carton_packing_inhouse_size_detail where Ac_code ='".$request->Ac_code."'");
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
    
    if (!$request->Ac_code)
    {
        $html = '<option value="">--Carton Packing List--</option>';
        } else {
        $html = '';
        $html = '<option value="">--Carton Packing List--</option>';
        
        foreach ($CartonPackingList as $row)  
        
        {$html .= '<option value="'.$row->cpki_code.'">'.$row->cpki_code.'('.$row->sales_order_no.')</option>';}
    }
      return response()->json(['html' => $html]);
}  
  
  
     
    
}
