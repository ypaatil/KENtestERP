<?php

namespace App\Http\Controllers;

use App\Models\PurchaseReturnMasterModel;
use App\Models\GeneralPurchaseReturnDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Illuminate\Support\Facades\Input;

class GeneralPurchaseReturnController extends Controller
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
->where('form_id', '18')
->first();

        $data = PurchaseReturnMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'purchase_return_master.Ac_code')
         ->join('ledger_master as lm2', 'lm2.ac_code', '=', 'purchase_return_master.Purchase_Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'purchase_return_master.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'purchase_return_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'purchase_return_master.firm_id')    
        ->where('purchase_return_master.delflag','=', '0')
        ->get(['purchase_return_master.*','usermaster.username','lm1.ac_name as ac_name1','lm2.ac_name as ac_name2','firm_master.firm_name','tax_type_master.tax_type_name']);

        return view('General_Purchase_Return_List', compact('data','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

$firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();

return view('General_Purchase_Return',compact('firmlist','ledgerlist','gstlist','itemlist')); 

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
  ->where('type','=','PRETURN')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;

       

    $data = array('pur_code'=>$TrNo,
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
"userId"=> $request->input('userId'),
"c_code"=> $codefetch->c_code,
"delflag"=>0
);

// Insert
$value = PurchaseReturnMasterModel::insert($data);
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

'pur_code' =>$TrNo,
'pur_date' => $request->input('pur_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->item_codes[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'pur_cgst' => $request->pur_cgsts[$x],
'camt' => $request->camts[$x],
'pur_sgst' => $request->pur_sgsts[$x],
'samt' => $request->samts[$x],
'pur_igst' => $request->pur_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'total_amount' => $request->total_amounts[$x],
'firm_id' => $request->firm_id);

GeneralPurchaseReturnDetailModel::insert($data2);

// Base Amount cr to Purchase Account

DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 51,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->amounts[$x],
'contraId' => $request->input('Purchase_Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);     
                    


 //CGST Amount CR to CGST OUTPUT Account
DB::table('ledgerentry')->insert([
'Ac_code' => 37,
'TrTyep' => 51,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->camts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  

 //SGST Amount CR to SGST OUTPUT Account

DB::table('ledgerentry')->insert([
'Ac_code' => 38,
'TrTyep' => 51,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->samts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);

 // IGST Amount Cr to OUTPUT  Account

DB::table('ledgerentry')->insert([
'Ac_code' => 39,
'TrTyep' => 51,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>   $request->iamts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  




}

}



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
'trtype' => 51, 
'ac_code' => $request->input('Purchase_Ac_code'),
'contra_code' => $request->input('Ac_code'),
'ac_type' =>'New Ref',
'BillNo' =>  $TrNo,
'BillDate' =>  $request->input('pur_date'),
'Amount' =>  $request->input('Net_amount'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('userId'),
'firm_id' => $request->input('firm_id'),
'c_code' => $codefetch->c_code

]);


 DB::table('transactions')->insert([
'TrType' => 51,
'TrNo' =>$TrNo,
'Date' =>  $request->input('pur_date'),
'ref_no' => $TrNo,
'ref_date' =>$request->input('pur_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('Ac_code'),
'CrCode' =>  $request->input('Purchase_Ac_code'),
'Amount' =>   $request->input('Net_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Purchase_Ac_code'),
'TrTyep' => 51,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->input('Net_amount'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  



$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='PRETURN' AND firm_id='".$request->input('firm_id')."'");  



  return redirect()->route('GeneralPurchaseReturn.index')->with('message', 'Add Record Succesfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseReturnMasterModel  $purchaseReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseReturnMasterModel $purchaseReturnMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseReturnMasterModel  $purchaseReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    
    $firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();

        $purchasereturnfetch = PurchaseReturnMasterModel::find($id);

 $detailpurchasereturn = GeneralPurchaseReturnDetailModel::join('item_master','item_master.item_code', '=', 'purchase_return_detail.item_code')
  ->where('pur_code','=', $purchasereturnfetch->pur_code)->get(['purchase_return_detail.*','item_master.item_name']);


        return view('General_Purchase_Return_Edit',compact('purchasereturnfetch','firmlist','ledgerlist','gstlist','itemlist','detailpurchasereturn'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseReturnMasterModel  $purchaseReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $pur_code)
    {
      

       
$data = array('pur_code'=> $request->input('pur_code'),
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
"userId"=> $request->input('userId'),
"c_code"=> $request->input('c_code'),
"delflag"=>0
);

// update
$purchase = PurchaseReturnMasterModel::findOrFail($pur_code);  

$purchase->fill($data)->save();


   DB::table('purchase_return_detail')->where('pur_code', $request->input('pur_code'))->delete();
   DB::table('ledgerentry_details')->where('tr_no', $request->input('pur_code'))->delete();
   DB::table('ledgerentry')->where('TrNo', $request->input('pur_code'))->delete();
   DB::table('transactions')->where('TrNo', $request->input('pur_code'))->delete();




$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0; $x<$cnt; $x++) {
# code...

$data2=array(

'pur_code' => $request->input('pur_code'),
'pur_date' => $request->input('pur_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->item_codes[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'pur_cgst' => $request->pur_cgsts[$x],
'camt' => $request->camts[$x],
'pur_sgst' => $request->pur_sgsts[$x],
'samt' => $request->samts[$x],
'pur_igst' => $request->pur_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'total_amount' => $request->total_amounts[$x],
'firm_id' => $request->firm_id);

GeneralPurchaseReturnDetailModel::insert($data2);

// Base Amount cr to Purchase Account

DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'dr',
'Amount' =>  $request->amounts[$x],
'contraId' => $request->input('Purchase_Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);     
                    


 //CGST Amount CR to CGST OUTPUT Account
DB::table('ledgerentry')->insert([
'Ac_code' => 37,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'cr',
'Amount' =>  $request->camts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  

 //SGST Amount CR to SGST OUTPUT Account

DB::table('ledgerentry')->insert([
'Ac_code' => 38,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'cr',
'Amount' =>  $request->samts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);

 // IGST Amount Cr to OUTPUT  Account

DB::table('ledgerentry')->insert([
'Ac_code' => 39,
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'cr',
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
'ac_code' => $request->input('Purchase_Ac_code'),
'contra_code' => $request->input('Ac_code'),
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
'DrCode' => $request->input('Ac_code'),
'CrCode' =>  $request->input('Purchase_Ac_code'),
'Amount' =>   $request->input('Net_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Purchase_Ac_code'),
'TrTyep' => 81,
'Date' => $request->input('pur_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('pur_code'),
'DC' =>'cr',
'Amount' =>  $request->input('Net_amount'),
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);   



  return redirect()->route('GeneralPurchaseReturn.index')->with('message', 'Add Record Succesfully');




    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseReturnMasterModel  $purchaseReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
     $master =PurchaseReturnMasterModel::where('pur_code',$id)->first();

     $master->delete();

    DB::table('purchase_return_detail')->where('pur_code', $id)->delete();
    DB::table('ledgerentry_details')->where('tr_no', $id)->delete();
    DB::table('ledgerentry')->where('TrNo', $id)->delete();
    DB::table('transactions')->where('TrNo', $id)->delete();

        
return redirect()->route('GeneralPurchaseReturn.index')->with('message', 'Delete Record Succesfully');

    }
}
