<?php

namespace App\Http\Controllers;

use App\Models\ReceiptModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class JournalVoucherController extends Controller
{
    
public function index()
{

         $chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '15')
->first();

 // DB::enableQueryLog();
        $data = ReceiptModel::selectRaw('SUBSTRING(transactions.TrNo,4,10) as srno1,LM1.ac_name as Ac_name1,LM2.ac_name as Ac_name2,payment_mode.Pay_mode_name,transactions.*')
        ->join('ledger_master as LM1','LM1.ac_code', '=', 'transactions.DrCode')
        ->join('ledger_master as LM2', 'LM2.ac_code', '=', 'transactions.CrCode')
        ->join('payment_mode', 'payment_mode.pay_mode', '=', 'transactions.Pay_mode')
        ->where('TrType','=',84)
        ->orderBy(DB::raw('SUBSTRING(transactions.TrNo,4,10)'), 'ASC')
        ->get();


// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);


return view('Journal_Voucher_List', compact('data','chekform'));


}

/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create()
{
//

$firmlist = DB::table('firm_master')->get();
$cashbank = DB::table('ledger_master')->select('ac_code','ac_name')
->where('delflag',0)->get();

$ledgerlist = DB::table('ledger_master')
->select('ac_code','ac_name')
->where('delflag',0)->get();


$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$paytypelist = DB::table('pay_mode')->get();

return view('Journal_Voucher',compact('firmlist','cashbank','gstlist','itemlist','paytypelist','ledgerlist'));

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
->where('type','=','JV')
->where('firm_id','=',$firm_id)
->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
$TrNo=$codefetch->code.'-'.$codefetch->tr_no;



$data = array('TrType'=>$request->TrType,
"TrNo"=>$TrNo,
"Date"=> $request->input('Date'),
"ref_no"=> $request->input('ref_no'),
"ref_date"=> $request->input('ref_date'),
"times"=> date('h:i:s'),
"CounterId"=> 1,
"UserId"=> $request->input('userId'),
"DrCode"=> $request->input('DrCode'),
"CrCode"=> $request->input('CrCode'),
"Amount"=> $request->input('Amount'),
"Naration"=> $request->input('Naration'),
"multientry"=> 0,
"Pay_mode"=> $request->input('Pay_mode'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $codefetch->c_code
);

// Insert
$value = ReceiptModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}


DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('DrCode'),
'TrTyep' => $request->TrType,
'Date' => $request->input('Date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('Amount'),
'contraId' => $request->input('CrCode'),
'Naration' => $request->input('Naration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  



DB::table('ledgerentry')->insert([
'Ac_code' =>  $request->input('CrCode'),
'TrTyep' => $request->TrType,
'Date' => $request->input('Date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'cr',
'Amount' => $request->input('Amount'),
'contraId' => $request->input('DrCode'),
'Naration' => $request->input('Naration'),
'CounterId' => 1,
'UserId' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


DB::table('ledgerentry_details')->insert([
'tr_no' => $TrNo,
'date' =>  $request->input('Date'),
'trtype' => $request->TrType, 
'ac_code' => $request->input('DrCode'),
'contra_code' =>$request->input('CrCode'),
'ac_type' =>'Against Ref',
'BillNo' => $request->input('ref_no'),
'BillDate' => $request->input('ref_date'),
'Amount' => 0,
'PayingAmount' => $request->input('Amount'),
'Pending' => 0,
'CounterId' => 1,
'UserId' => $request->userId,
'firm_id' => $request->input('firm_id'),
'c_code' => $codefetch->c_code

]);   


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='JV' AND firm_id='".$request->input('firm_id')."'");  



  return redirect()->route('Journal_Voucher.index')->with('message', 'Add Record Succesfully');                  


}

/**
* Display the specified resource.
*
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
public function show(ReceiptModel $receiptModel)
{
//
}

/**
* Show the form for editing the specified resource.
*
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
public function edit($id)
{


$firmlist = DB::table('firm_master')->get();
$cashbank = DB::table('ledger_master')->select('ac_code','ac_name')
->where('delflag',0)->get();

$ledgerlist = DB::table('ledger_master')
->select('ac_code','ac_name')
->where('delflag',0)->get();


$gstlist   =  DB::table('tax_type_master')->get();
$itemlist  =  DB::table('item_master')->get();
$paytypelist =  DB::table('pay_mode')->get();

 $Jvfetch = ReceiptModel::find($id);


return view('Journal_Voucher',compact('firmlist','cashbank','gstlist','itemlist','paytypelist','ledgerlist','Jvfetch'));



}

/**
* Update the specified resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $SrNo)
{


  DB::table('ledgerentry_details')->where('tr_no', $request->input('TrNo'))->delete();
  DB::table('ledgerentry')->where('TrNo', $request->input('TrNo'))->delete();
  DB::table('transactions')->where('TrNo', $request->input('TrNo'))->delete();



$data = array('TrType'=>$request->TrType,
"TrNo"=>$request->TrNo,
"Date"=> $request->input('Date'),
"ref_no"=> $request->input('ref_no'),
"ref_date"=> $request->input('ref_date'),
"times"=> date('h:i:s'),
"CounterId"=> 1,
"UserId"=> $request->input('userId'),
"DrCode"=> $request->input('DrCode'),
"CrCode"=> $request->input('CrCode'),
"Amount"=> $request->input('Amount'),
"Naration"=> $request->input('Naration'),
"multientry"=> 0,
"Pay_mode"=> $request->input('Pay_mode'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code')
);

// Insert
$value = ReceiptModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}





DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('DrCode'),
'TrTyep' => $request->TrType,
'Date' => $request->input('Date'),
'times' => date('h:i:s'),
'TrNo' =>$request->TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('Amount'),
'contraId' => $request->input('CrCode'),
'Naration' => $request->input('Naration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



DB::table('ledgerentry')->insert([
'Ac_code' =>  $request->input('CrCode'),
'TrTyep' => $request->TrType,
'Date' => $request->input('Date'),
'times' => date('h:i:s'),
'TrNo' =>$request->TrNo,
'DC' =>'cr',
'Amount' => $request->input('Amount'),
'contraId' => $request->input('DrCode'),
'Naration' => $request->input('Naration'),
'CounterId' => 1,
'UserId' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  




DB::table('ledgerentry_details')->insert([
'tr_no' => $request->TrNo,
'date' =>  $request->input('Date'),
'trtype' => $request->TrType, 
'ac_code' => $request->input('DrCode'),
'contra_code' =>$request->input('CrCode'),
'ac_type' =>'Against Ref',
'BillNo' => $request->input('ref_no'),
'BillDate' => $request->input('ref_date'),
'Amount' => 0,
'PayingAmount' => $request->input('Amount'),
'Pending' => 0,
'CounterId' => 1,
'UserId' => $request->userId,
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')

]);   


 return redirect()->route('Journal_Voucher.index')->with('message', 'Update Record Succesfully');


}

/**
* Remove the specified resource from storage.
*
* @param  \App\Models\ReceiptModel  $receiptModel
* @return \Illuminate\Http\Response
*/
public function destroy($TrNo)
{


  DB::table('transactions')->where('TrNo', $TrNo)
  ->where('TrType', 84)->delete();

    DB::table('ledgerentry_details')->where('tr_no', $TrNo)
  ->where('trtype', 84)->delete();

   DB::table('ledgerentry')->where('TrNo', $TrNo)
  ->where('TrType', 84)->delete();

        
return redirect()->route('Journal_Voucher.index')->with('delete', 'Delete Record Succesfully');  







}

}
