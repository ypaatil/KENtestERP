<?php

namespace App\Http\Controllers;

use App\Models\OtherPurchaseModel;
use App\Models\OtherPurchasedetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Input;

class OtherPurchaseControlller extends Controller
{
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/
public function index()
{
//

$chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '9')
->first();

        $data = OtherPurchaseModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_master.Ac_code')
         ->join('ledger_master as lm2', 'lm2.ac_code', '=', 'purchase_master.Purchase_Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_master.firm_id')    
        ->where('purchase_master.delflag','=', '0')
        ->get(['purchase_master.*','usermaster.username','lm1.ac_name as ac_name1','lm2.ac_name as ac_name2','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('Other_Purchase_List', compact('data','chekform'));

}

/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create()
{
//

$code = DB::select(DB::raw("select ifnull(tr_no,0)+1 as tr_no,c_code from counter_number where type='Other_Purchase' and c_name='C1'"));

$firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();

return view('Other_Purchase',compact('firmlist','ledgerlist','gstlist','itemlist','code')); 

}

/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{


$data = array('pur_code'=>$request->input('pur_code'),
"pur_date"=> $request->input('pur_date'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"pur_bill_no"=> $request->input('pur_bill_no'),
"pur_bill_date"=> $request->input('pur_bill_date'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"Purchase_Ac_code"=> $request->input('Purchase_Ac_code'),
"tds_per"=> $request->input('tds_per'),
"tds_amt"=> $request->input('tds_amt'),
"payable_amt"=> $request->input('payable_amt'),
"userId"=> $request->input('userId'),
"delflag"=>0
);

// Insert
$value = OtherPurchaseModel::insert($data);
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

'pur_code' =>$request->input('pur_code'),
'pur_date' => $request->input('pur_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->item_codes[$x],
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
'total_amount' => $request->total_amounts[$x],
'firm_id' => $request->firm_id);

OtherPurchasedetailModel::insert($data2);

// Base Amount Dr to Purchase Account

DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Purchase_Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->amounts[$x],
'contraId' => $request->input('Ac_code'),
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
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->camts[$x],
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
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->samts[$x],
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
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>   $request->iamts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  


}

}



$sqlDate = DB::table('transactions')->select(DB::raw("ifnull(sum(Amount),0) as PaidAmount"))
  ->where('TrNo','=',$request->input('pur_code'))
  ->where('TrType','=',83)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

$PendingAmount= $request->input('Net_amount')-$PaidAmount;





DB::table('ledgerentry_details')->insert([
'tr_no' => $request->input('pur_code'),
'date' => $request->input('pur_date'),
'trtype' => 81, 
'ac_code' => $request->input('Ac_code'),
'contra_code' =>  $request->input('Purchase_Ac_code'),
'ac_type' =>'New Ref',
'BillNo' =>  $request->input('pur_code'),
'BillDate' =>  $request->input('pur_date'),
'Amount' =>  $request->input('Net_amount'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('userId'),
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')

]);


 DB::table('transactions')->insert([
'TrType' => 81,
'TrNo' =>$request->input('pur_code'),
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
'c_code' =>$request->input('c_code')

]);  


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'cr',
'Amount' =>  $request->input('Net_amount'),
'contraId' => $request->input('Purchase_Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='Other_Purchase' AND firm_id='".$request->input('firm_id')."'");  



  return redirect()->route('OtherPurchase.index')->with('message', 'Add Record Succesfully');



}

/**
* Display the specified resource.
*
* @param  \App\Models\OtherPurchaseModel  $otherPurchaseModel
* @return \Illuminate\Http\Response
*/
public function show(OtherPurchaseModel $otherPurchaseModel)
{
//
}

/**
* Show the form for editing the specified resource.
*
* @param  \App\Models\OtherPurchaseModel  $otherPurchaseModel
* @return \Illuminate\Http\Response
*/
public function edit($id)
{

 $firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();

        $purchasefetch = OtherPurchaseModel::find($id);

 $detailpurchase = OtherPurchasedetailModel::join('item_master','item_master.item_code', '=', 'purchase_detail.item_code')
  ->where('pur_code','=', $purchasefetch->pur_code)->get(['purchase_detail.*','item_master.item_name']);


        return view('OtherPurchaseEdit',compact('purchasefetch','firmlist','ledgerlist','gstlist','itemlist','detailpurchase'));

}

/**
* Update the specified resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @param  \App\Models\OtherPurchaseModel  $otherPurchaseModel
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $pur_code)
{

 
$data = array('pur_code'=>$request->input('pur_code'),
"pur_date"=> $request->input('pur_date'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"pur_bill_no"=> $request->input('pur_bill_no'),
"pur_bill_date"=> $request->input('pur_bill_date'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"Purchase_Ac_code"=> $request->input('Purchase_Ac_code'),
"tds_per"=> $request->input('tds_per'),
"tds_amt"=> $request->input('tds_amt'),
"payable_amt"=> $request->input('payable_amt'),
"userId"=> $request->input('userId'),
"delflag"=>0
);

// Insert
$purchase = OtherPurchaseModel::findOrFail($pur_code);  

$purchase->fill($data)->save();



   DB::table('purchase_detail')->where('pur_code', $request->input('pur_code'))->delete();
   DB::table('ledgerentry_details')->where('tr_no', $request->input('pur_code'))->delete();
   DB::table('ledgerentry')->where('TrNo', $request->input('pur_code'))->delete();
   DB::table('transactions')->where('TrNo', $request->input('pur_code'))->delete();


$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0;$x<$cnt;$x++) {
# code...

$data2=array(

'pur_code' =>$request->input('pur_code'),
'pur_date' => $request->input('pur_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->input('item_codes')[$x], 
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
'total_amount' => $request->total_amounts[$x],
'firm_id' => $request->firm_id);

OtherPurchasedetailModel::insert($data2);



// Base Amount Dr to Purchase Account

DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Purchase_Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->amounts[$x],
'contraId' => $request->input('Ac_code'),
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
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->camts[$x],
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
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->samts[$x],
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
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>   $request->iamts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



}
}




$sqlDate = DB::table('transactions')->select(DB::raw("ifnull(sum(Amount),0) as PaidAmount"))
  ->where('TrNo','=',$request->input('pur_code'))
  ->where('TrType','=',83)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

$PendingAmount= $request->input('Net_amount')-$PaidAmount;





DB::table('ledgerentry_details')->insert([
'tr_no' => $request->input('pur_code'),
'date' => $request->input('pur_date'),
'trtype' => 81, 
'ac_code' => $request->input('Ac_code'),
'contra_code' =>  $request->input('Purchase_Ac_code'),
'ac_type' =>'New Ref',
'BillNo' =>  $request->input('pur_code'),
'BillDate' =>  $request->input('pur_date'),
'Amount' =>  $request->input('Net_amount'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('userId'),
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')

]);


 DB::table('transactions')->insert([
'TrType' => 81,
'TrNo' =>$request->input('pur_code'),
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
'c_code' =>$request->input('c_code')

]);  


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'cr',
'Amount' =>  $request->input('Net_amount'),
'contraId' => $request->input('Purchase_Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



  return redirect()->route('OtherPurchase.index')->with('message', 'Update Record Succesfully');



}

/**
* Remove the specified resource from storage.
*
* @param  \App\Models\OtherPurchaseModel  $otherPurchaseModel
* @return \Illuminate\Http\Response
*/
public function destroy($id)
{

  $master =OtherPurchaseModel::where('pur_code',$id)->first();

  $master->delete();


   DB::table('purchase_detail')->where('pur_code', $id)->delete();
   DB::table('ledgerentry_details')->where('tr_no', $id)->delete();
   DB::table('ledgerentry')->where('TrNo', $id)->delete();
   DB::table('transactions')->where('TrNo', $id)->delete();

        
return redirect()->route('OtherPurchase.index')->with('delete', 'Delete Record Succesfully');
    
}


public function GetData(Request $request)
{
//

$tax_type_id= $request->tax_type_id;
$item_code= $request->item_code;


if($request->tax_type_id==1)
{
$data = DB::select(DB::raw("SELECT cat_id, class_id, item_code,cgst_per,sgst_per,igst_per=0 as igst_per,item_rate,item_mrp ,moq, hsn_code, unit_id, item_image_path 
from item_master where item_code='$request->item_code'"));
if($data[0]->cat_id==1)
{
 $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$request->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$request->item_code."')
                ) as Stock"));
}
else
{
 $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$request->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$request->item_code."')
                ) as Stock"));
}


$response = array();
$response['data'] = $data;
$response['stock'] = $stock;

echo json_encode($response);

} else if($request->tax_type_id==2)
{

$data = DB::select(DB::raw("SELECT cat_id, class_id,item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per,item_rate,item_mrp ,moq, hsn_code, unit_id, item_image_path 
from item_master where item_code='$request->item_code'"));

if($data[0]->cat_id==1)
{
 $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$request->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$request->item_code."')
                ) as Stock"));
}
else
{
 $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$request->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$request->item_code."')
                ) as Stock"));
}

$response = array();
$response['data'] = $data;
$response['stock'] = $stock;


echo json_encode($response);
} else if($request->tax_type_id==3)
{
$data = DB::select(DB::raw("SELECT cat_id, class_id,item_code,cgst_per=0 as cgst_per,sgst_per=0 as sgst_per,igst_per=0 as igst_per,moq,item_rate,item_mrp , hsn_code, unit_id, item_image_path 
from item_master where item_code='$request->item_code'"));

if($data[0]->cat_id==1)
{
 $stock=DB::select(DB::raw("select ((select ifnull(sum(meter),0) from inward_details where item_code='".$request->item_code."')-
                (select ifnull(sum(meter),0) from fabric_outward_details where item_code='".$request->item_code."')
                ) as Stock"));
}
else
{
 $stock=DB::select(DB::raw("select ((select ifnull(sum(item_qty),0) from trimsInwardDetail where item_code='".$request->item_code."')-
                (select ifnull(sum(item_qty),0) from trimsOutwardDetail where item_code='".$request->item_code."')
                ) as Stock"));
}

 


$response = array();
$response['data'] = $data;
$response['stock'] = $stock;
 

echo json_encode($response);

}

/*
DB::enableQueryLog();
$query = DB::getQueryLog();
$query = end($query);
dd($query);*/




}





}
