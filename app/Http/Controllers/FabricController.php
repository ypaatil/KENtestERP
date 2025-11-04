<?php

namespace App\Http\Controllers;

use App\Models\FabricMasterModel;
use App\Models\FabricPurchaseDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FabricController extends Controller
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
->where('form_id', '17')
->first();

        $data = FabricMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_fabric_master.Ac_code')
          ->join('ledger_master as lm2', 'lm2.ac_code', '=', 'purchase_fabric_master.Purchase_Ac_code')
          ->join('usermaster', 'usermaster.userId', '=', 'purchase_fabric_master.userId')
          ->join('cp_master', 'cp_master.cp_id', '=', 'purchase_fabric_master.cp_id')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_fabric_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_fabric_master.firm_id')    
        ->get(['purchase_fabric_master.*','usermaster.username','lm1.ac_name as party_name','lm2.ac_name as purchase_party','firm_master.firm_name','tax_type_master.tax_type_name','cp_master.cp_name']);

        return view('fabric_purchase_list', compact('data','chekform'));


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

$cptypelist = DB::table('cp_master')
->select('cp_id','cp_name')
->get();


$stylenos = DB::table('purchase_fabric_details')
->select('fpur_style_no')
->distinct()
->get();

 return view('fabric_purchase',compact('firmlist','ledgerlist','gstlist','itemlist','cptypelist','stylenos')); 

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
  ->where('type','=','FPURCHASE')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;


       
  $data = array('fpur_code'=>$TrNo,
"fpur_date"=> $request->input('pur_date'),
"cp_id"=> $request->input('cp_id'),
"tax_type_id"=> $request->input('tax_type_id'),
"fpur_bill"=> $request->input('pur_bill_no'),
"Ac_code"=> $request->input('Ac_code'),
"add1"=> $request->input('pur_add1'),
"add2"=> $request->input('pur_add2'),
"less1"=> $request->input('pur_less1'),
"less2"=> $request->input('pur_less2'),
"total_meter"=> $request->input('pur_tmeter'),
"total_qty"=> $request->input('pur_tqty'),
"gross_amount"=> $request->input('pur_gamt'),
"cgst_per"=> $request->input('pur_CGST'),
"cgst_amt"=> $request->input('pur_camt'),
"sgst_per"=> $request->input('pur_SGST'),
"sgst_amt"=> $request->input('pur_samt'),
"igst_per"=> $request->input('pur_IGST'),
"igst_amt"=> $request->input('pur_iamt'),
"gst_amount"=> $request->input('pur_gstamt'),
"net_amount"=> $request->input('pur_namt'),
"narration"=> $request->input('narration'),
"UserId"=> $request->input('userId'),
"CounterId"=> 1,
'showflag' => 0,
"firm_id"=> $request->input('firm_id'),
"Purchase_Ac_code"=> $request->input('Purchase_Ac_code')
);

// Insert
$value = FabricMasterModel::insert($data);
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

'fpur_code' =>$TrNo,
'fpur_date' => $request->input('pur_date'),
'cp_id' => $request->input('cp_id'),
'fpur_bill' => $request->pur_bill_no,
'Ac_code' => $request->Ac_code,
'item_code' => $request->item_codes[$x],
'fpur_style_no' => $request->pur_style_nos[$x],
'fpur_mtr' => $request->pur_mtr[$x],
'fpur_qty' => $request->pur_qty[$x],
'pur_rate' => $request->pur_rate[$x],
'Amount' => $request->Amount[$x],
'usedFlag' => 0);

FabricPurchaseDetailModel::insert($data2);



DB::table('fabric_transaction')->insert([    
'tr_code' => $TrNo,
'tr_date' => $request->input('pur_date'),
'tr_type' => 1, 
'item_code' => $request->item_codes[$x],
'item_type' =>  0,
'cp_id' =>$request->input('cp_id'),
'bill_no' =>  $request->pur_bill_no,
'Ac_code' =>  $request->Ac_code,
'track_code' => '',
'style_no' => $request->pur_style_nos[$x],
'roll_no' => '',
'color' => '',
'width' => 0,
'meter' => $request->pur_mtr[$x],
'actual_width' => 0,
'actual_meter' => 0,
'cutFlag' => 0,
'cutting_lot_no' => 0,
'table_size' => 0,
'layer_count' => 0,
'cutpiece_meter' => 0,
'avg_mtr' => 0,
'fg_id' => 0,
'sz_code' => 0,
'worker_id' => 0,
'machine_id' => 0,
'jw_id' => 0,
'sm_id' => 0,
'assigned_qty' => 0,
'Qty' => $request->pur_qty[$x],
'rate' => $request->pur_rate[$x],
'amount' => $request->Amount[$x],
'UserId' => $request->input('userId'),
'CounterId' => 1,
'showflag' => 0,
'ModifyDate' => date('Y-m-d h:i:s'),
'wt_id' => 0,
]);


}

}


// Base Amount Dr to Purchase Account

DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Purchase_Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('pur_gamt'),
'contraId' => $request->Ac_code,
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);     
                    


 //CGST Amount Dr to CGST INPUT Account
DB::table('ledgerentry')->insert([
'Ac_code' => 34,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('pur_camt'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  

 //SGST Amount Dr to SGST INPUT Account

DB::table('ledgerentry')->insert([
'Ac_code' => 35,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('pur_samt'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);

 // IGST Amount Dr to IGST INPUT Account

DB::table('ledgerentry')->insert([
'Ac_code' => 36,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('pur_iamt'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  



$sqlDate = DB::table('transactions')->select(DB::raw("ifnull(sum(Amount),0) as PaidAmount"))
  ->where('TrNo','=',$TrNo)
  ->where('TrType','=',83)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

$PendingAmount= $request->input('Net_amount')-$PaidAmount;



DB::table('ledgerentry_details')->insert([
'tr_no' => $TrNo,
'date' => $request->input('pur_date'),
'trtype' => 81, 
'ac_code' => $request->input('Ac_code'),
'contra_code' =>  3,
'ac_type' =>'New Ref',
'BillNo' =>  $TrNo,
'BillDate' =>  $request->input('pur_date'),
'Amount' =>  $request->input('pur_namt'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('userId'),
'firm_id' => $request->input('firm_id'),
'c_code' =>$codefetch->c_code
]);


 DB::table('transactions')->insert([
'TrType' => 81,
'TrNo' =>$TrNo,
'Date' =>  $request->input('pur_date'),
'ref_no' => $request->input('pur_code'),
'ref_date' =>$request->input('pur_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('Purchase_Ac_code'),
'CrCode' =>   $request->input('Ac_code'),
'Amount' =>   $request->input('Net_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' =>$codefetch->c_code
]);  


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->input('pur_gamt'),
'contraId' => $request->input('Purchase_Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='FPURCHASE' AND firm_id='".$request->input('firm_id')."'");  


  return redirect()->route('Fabric_Purchase.index')->with('message', 'Add Record Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricMasterModel  $fabricMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricMasterModel $fabricMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricMasterModel  $fabricMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
 $firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();

$cptypelist = DB::table('cp_master')
->select('cp_id','cp_name')
->get();


$stylenos = DB::table('purchase_fabric_details')
->select('fpur_style_no')
->distinct()
->get();

        $fabricpurchasefetch = FabricMasterModel::find($id);

 $detailfabricpurchase = FabricPurchaseDetailModel::join('item_master','item_master.item_code', '=', 'purchase_fabric_details.item_code')
  ->where('fpur_code','=', $fabricpurchasefetch->fpur_code)->get(['purchase_fabric_details.*','item_master.item_name']);






        return view('fabric_purchase_edit',compact('fabricpurchasefetch','firmlist','ledgerlist','gstlist','itemlist','cptypelist','stylenos','detailfabricpurchase'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricMasterModel  $fabricMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$fpur_code)
    {
       

       
  $data = array('fpur_code'=>$request->input('fpur_code'),
"fpur_date"=> $request->input('pur_date'),
"cp_id"=> $request->input('cp_id'),
"tax_type_id"=> $request->input('tax_type_id'),
"fpur_bill"=> $request->input('pur_bill_no'),
"Ac_code"=> $request->input('Ac_code'),
"add1"=> $request->input('pur_add1'),
"add2"=> $request->input('pur_add2'),
"less1"=> $request->input('pur_less1'),
"less2"=> $request->input('pur_less2'),
"total_meter"=> $request->input('pur_tmeter'),
"total_qty"=> $request->input('pur_tqty'),
"gross_amount"=> $request->input('pur_gamt'),
"cgst_per"=> $request->input('pur_CGST'),
"cgst_amt"=> $request->input('pur_camt'),
"sgst_per"=> $request->input('pur_SGST'),
"sgst_amt"=> $request->input('pur_samt'),
"igst_per"=> $request->input('pur_IGST'),
"igst_amt"=> $request->input('pur_iamt'),
"gst_amount"=> $request->input('pur_gstamt'),
"net_amount"=> $request->input('pur_namt'),
"narration"=> $request->input('narration'),
"UserId"=> $request->input('userId'),
"CounterId"=> 1,
'showflag' => 0,
"firm_id"=> $request->input('firm_id'),
"Purchase_Ac_code"=> $request->input('Purchase_Ac_code')
);

// Insert

$fabricpurchase = FabricMasterModel::findOrFail($fpur_code);  

$fabricpurchase->fill($data)->save();

   DB::table('purchase_fabric_details')->where('fpur_code', $request->input('fpur_code'))->delete();
   DB::table('ledgerentry_details')->where('tr_no', $request->input('fpur_code'))->delete();
   DB::table('ledgerentry')->where('TrNo', $request->input('fpur_code'))->delete();
   DB::table('transactions')->where('TrNo', $request->input('fpur_code'))->delete();
   DB::table('fabric_transaction')->where('tr_code', $request->input('fpur_code'))->delete();


$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0; $x<$cnt; $x++) {
# code...

$data2=array(

'fpur_code' =>$request->input('fpur_code'),
'fpur_date' => $request->input('pur_date'),
'cp_id' => $request->input('cp_id'),
'fpur_bill' => $request->pur_bill_no,
'Ac_code' => $request->Ac_code,
'item_code' => $request->item_codes[$x],
'fpur_style_no' => $request->pur_style_nos[$x],
'fpur_mtr' => $request->pur_mtr[$x],
'fpur_qty' => $request->pur_qty[$x],
'pur_rate' => $request->pur_rate[$x],
'Amount' => $request->Amount[$x],
'usedFlag' => 0);

FabricPurchaseDetailModel::insert($data2);



DB::table('fabric_transaction')->insert([    
'tr_code' => $request->input('fpur_code'),
'tr_date' => $request->input('pur_date'),
'tr_type' => 1, 
'item_code' => $request->item_codes[$x],
'item_type' =>  0,
'cp_id' =>$request->input('cp_id'),
'bill_no' =>  $request->pur_bill_no,
'Ac_code' =>  $request->Ac_code,
'track_code' => '',
'style_no' => $request->pur_style_nos[$x],
'roll_no' => '',
'color' => '',
'width' => 0,
'meter' => $request->pur_mtr[$x],
'actual_width' => 0,
'actual_meter' => 0,
'cutFlag' => 0,
'cutting_lot_no' => 0,
'table_size' => 0,
'layer_count' => 0,
'cutpiece_meter' => 0,
'avg_mtr' => 0,
'fg_id' => 0,
'sz_code' => 0,
'worker_id' => 0,
'machine_id' => 0,
'jw_id' => 0,
'sm_id' => 0,
'assigned_qty' => 0,
'Qty' => $request->pur_qty[$x],
'rate' => $request->pur_rate[$x],
'amount' => $request->Amount[$x],
'UserId' => $request->input('userId'),
'CounterId' => 1,
'showflag' => 0,
'ModifyDate' => date('Y-m-d h:i:s'),
'wt_id' => 0,
]);


}

}


// Base Amount Dr to Purchase Account

DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Purchase_Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' =>$request->input('fpur_code'),
'DC' =>'dr',
'Amount' =>  $request->input('pur_gamt'),
'contraId' => $request->Ac_code,
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);     
                    


 //CGST Amount Dr to CGST INPUT Account
DB::table('ledgerentry')->insert([
'Ac_code' => 34,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('fpur_code'),
'DC' =>'dr',
'Amount' =>  $request->input('pur_camt'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  

 //SGST Amount Dr to SGST INPUT Account

DB::table('ledgerentry')->insert([
'Ac_code' => 35,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('fpur_code'),
'DC' =>'dr',
'Amount' =>  $request->input('pur_samt'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);

 // IGST Amount Dr to IGST INPUT Account

DB::table('ledgerentry')->insert([
'Ac_code' => 36,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('fpur_code'),
'DC' =>'dr',
'Amount' =>  $request->input('pur_iamt'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



$sqlDate = DB::table('transactions')->select(DB::raw("ifnull(sum(Amount),0) as PaidAmount"))
  ->where('TrNo','=',$request->input('fpur_code'))
  ->where('TrType','=',83)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

$PendingAmount= $request->input('Net_amount')-$PaidAmount;



DB::table('ledgerentry_details')->insert([
'tr_no' => $request->input('fpur_code'),
'date' => $request->input('pur_date'),
'trtype' => 81, 
'ac_code' => $request->input('Ac_code'),
'contra_code' =>  3,
'ac_type' =>'New Ref',
'BillNo' =>  $request->input('fpur_code'),
'BillDate' =>  $request->input('pur_date'),
'Amount' =>  $request->input('pur_namt'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('userId'),
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')
]);


 DB::table('transactions')->insert([
'TrType' => 81,
'TrNo' =>$request->input('fpur_code'),
'Date' =>  $request->input('pur_date'),
'ref_no' => $request->input('pur_code'),
'ref_date' =>$request->input('pur_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('Purchase_Ac_code'),
'CrCode' =>   $request->input('Ac_code'),
'Amount' =>   $request->input('Net_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')
]);  


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('fpur_code'),
'DC' =>'cr',
'Amount' =>  $request->input('pur_gamt'),
'contraId' => $request->input('Purchase_Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  


  return redirect()->route('Fabric_Purchase.index')->with('message', 'Add Record Succesfully');


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricMasterModel  $fabricMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    $master =FabricMasterModel::where('fpur_code',$id)->first();

    $master->delete();

    DB::table('purchase_fabric_details')->where('fpur_code', $id)->delete();
    DB::table('ledgerentry_details')->where('tr_no', $id)->delete();
    DB::table('ledgerentry')->where('TrNo', $id)->delete();
    DB::table('transactions')->where('TrNo', $id)->delete();

      Session::flash('delete', 'Deleted record successfully'); 

    }


 public function PartyShortlist(Request $request)
    {


if($request->cp_id==1)
{
$data = DB::select(DB::raw("select Ac_code, Ac_name from ledger_master where delflag=0 and Group_code=19"));



} else if($request->cp_id==2)
{
  $data = DB::select(DB::raw("select Ac_code, Ac_name from ledger_master where delflag=0 and Group_code=20"));

 

}

  $html = '';

$html .= '<option value="0">Select Party Account</option>';

foreach ($data as $rowledger) {

$html .= '<option value="'.$rowledger->Ac_code.'">'.$rowledger->Ac_name.'</option>';


}

return response()->json(['html' => $html]);       



    }
    
}
