<?php

namespace App\Http\Controllers;

use App\Models\GeneralSalesReturnMasterModel;
use App\Models\GeneralSalesReturnDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class GeneralSalesReturnController extends Controller
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
->where('form_id', '19')
->first();

        $data = GeneralSalesReturnMasterModel::join('ledger_master','ledger_master.ac_code', '=', 'sale_return_master.Ac_code')
        ->join('pay_mode', 'pay_mode.pay_type', '=', 'sale_return_master.pay_type')
        ->join('usermaster', 'usermaster.userId', '=', 'sale_return_master.user_id')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'sale_return_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'sale_return_master.firm_id')    
        ->where('sale_return_master.delflag','=', '0')
        ->get(['sale_return_master.*','usermaster.username','ledger_master.ac_name','firm_master.firm_name','tax_type_master.tax_type_name','pay_mode.pay_name']);

        return view('General_Sales_Return_List', compact('data','chekform'));

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
$paytypelist = DB::table('pay_mode')->get();

return view('General_sales_Return',compact('firmlist','ledgerlist','gstlist','itemlist','paytypelist'));

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
  ->where('type','=','SRETURN')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;


   $data = array('bill_code'=>$TrNo,
"bill_date"=> $request->input('bill_date'),
"pay_type"=> $request->input('pay_type'),
"tax_type_id"=> $request->input('tax_type_id'),
"Ac_code"=> $request->input('Ac_code'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"add1"=> $request->input('add1'),
"add2"=> $request->input('add2'),
"less1"=> $request->input('less1'),
"less2"=> $request->input('less2'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $codefetch->c_code,
"user_id"=> $request->input('user_id'),
"delflag"=>0
);

// Insert
$value = GeneralSalesReturnMasterModel::insert($data);
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

'bill_code' =>$TrNo,
'bill_date' => $request->input('bill_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->item_codes[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'mrp' => $request->mrps[$x],
'pur_cgst' => $request->pur_cgsts[$x],
'camt' => $request->camts[$x],
'pur_sgst' => $request->pur_sgsts[$x],
'samt' => $request->samts[$x],
'pur_igst' => $request->pur_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'total_amount' => $request->total_amounts[$x]);

GeneralSalesReturnDetailModel::insert($data2);


 // This Base Amount is Dr to Sale A/C

DB::table('ledgerentry')->insert([
'Ac_code' => 2,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->amounts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);     
                    


 //CGST AMOUNT Cr to CGST OUTPUT A/C
DB::table('ledgerentry')->insert([
'Ac_code' => 37,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->camts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  

 //SGST AMOUNT Cr to SGST OUTPUT A/C

DB::table('ledgerentry')->insert([
'Ac_code' => 38,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->samts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);

 //IGST AMOUNT Cr to IGST OUTPUT A/C  

DB::table('ledgerentry')->insert([
'Ac_code' => 39,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
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
  ->where('TrType','=',42)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

$PendingAmount= $request->input('Net_amount')-$PaidAmount;



DB::table('ledgerentry_details')->insert([
'tr_no' => $TrNo,
'date' => $request->input('bill_date'),
'trtype' => 31, 
'ac_code' => $request->input('Ac_code'),
'contra_code' => 2 ,
'ac_type' =>'New Ref',
'BillNo' =>  $TrNo,
'BillDate' =>  $request->input('bill_date'),
'Amount' =>  $request->input('Net_amount'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('user_id'),
'firm_id' => $request->input('firm_id'),
'c_code' => $codefetch->c_code

]);

 DB::table('transactions')->insert([
'TrType' => 31,
'TrNo' => $TrNo,
'Date' =>  $request->input('bill_date'),
'ref_no' => $request->input('bill_code'),
'ref_date' =>$request->input('bill_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('user_id'),
'DrCode' => $request->input('Ac_code'),
'CrCode' =>  2,
'Amount' =>   $request->input('Net_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' =>$codefetch->c_code

]);  




DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->input('Net_amount'),
'contraId' => 2,
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='SRETURN' AND firm_id='".$request->input('firm_id')."'");  



  return redirect()->route('GeneralSalesReturn.index')->with('message', 'Add Record Succesfully');

//DB::table('order_detail')->insert($data2);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GeneralSalesReturnMasterModel  $generalSalesReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(GeneralSalesReturnMasterModel $generalSalesReturnMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GeneralSalesReturnMasterModel  $generalSalesReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    $firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$paytypelist = DB::table('pay_mode')->get();

        $salesreturnfetch = GeneralSalesReturnMasterModel::find($id);

 $detailsalesreturn = GeneralSalesReturnDetailModel::join('item_master','item_master.item_code', '=', 'sale_return_detail.item_code')
  ->where('bill_code','=', $salesreturnfetch->bill_code)->get(['sale_return_detail.*','item_master.item_name']);


        return view('General_Sales_Return_Edit',compact('salesreturnfetch','firmlist','ledgerlist','gstlist','itemlist','detailsalesreturn','paytypelist'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GeneralSalesReturnMasterModel  $generalSalesReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$bill_code)
    {
      

$data = array('bill_code'=>$request->input('bill_code'),
"bill_date"=> $request->input('bill_date'),
"pay_type"=> $request->input('pay_type'),
"tax_type_id"=> $request->input('tax_type_id'),
"Ac_code"=> $request->input('Ac_code'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"narration"=> $request->input('narration'),
"add1"=> $request->input('add1'),
"add2"=> $request->input('add2'),
"less1"=> $request->input('less1'),
"less2"=> $request->input('less2'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"user_id"=> $request->input('user_id'),
"delflag"=>0
);


$sale = GeneralSalesReturnMasterModel::findOrFail($bill_code);  

$sale->fill($data)->save();



  DB::table('sale_return_detail')->where('bill_code', $request->input('bill_code'))->delete();
  DB::table('ledgerentry_details')->where('tr_no', $request->input('bill_code'))->delete();
  DB::table('ledgerentry')->where('TrNo', $request->input('bill_code'))->delete();
  DB::table('transactions')->where('TrNo', $request->input('bill_code'))->delete();


$cnt = $request->input('item_codes');


if($cnt>0)
{

for($x=0; $x<count($request->input('item_codes')); $x++) {
# code...

$data2=array(

'bill_code' =>$request->input('bill_code'),
'bill_date' => $request->input('bill_date'),
'Ac_code' => $request->input('Ac_code'),
'item_code' => $request->item_codes[$x],
'item_qty' => $request->item_qtys[$x],
'item_rate' => $request->item_rates[$x],
'mrp' => $request->mrps[$x],
'pur_cgst' => $request->pur_cgsts[$x],
'camt' => $request->camts[$x],
'pur_sgst' => $request->pur_sgsts[$x],
'samt' => $request->samts[$x],
'pur_igst' => $request->pur_igsts[$x],
'iamt' => $request->iamts[$x],
'amount' => $request->amounts[$x],
'total_amount' => $request->total_amounts[$x]);

GeneralSalesReturnDetailModel::insert($data2);



// This Base Amount is Cr to Sale A/C

DB::table('ledgerentry')->insert([
'Ac_code' => 2,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('bill_code'),
'DC' =>'dr',
'Amount' =>  $request->amounts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);     
                    


 //CGST AMOUNT Cr to CGST OUTPUT A/C
DB::table('ledgerentry')->insert([
'Ac_code' => 37,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('bill_code'),
'DC' =>'dr',
'Amount' =>  $request->camts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  

 //SGST AMOUNT Cr to SGST OUTPUT A/C

DB::table('ledgerentry')->insert([
'Ac_code' => 38,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('bill_code'),
'DC' =>'dr',
'Amount' =>  $request->samts[$x],
'contraId' => $request->input('Ac_code'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);

 //IGST AMOUNT Cr to IGST OUTPUT A/C  

DB::table('ledgerentry')->insert([
'Ac_code' => 39,
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('bill_code'),
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
  ->where('TrNo','=',$request->input('bill_code'))
  ->where('TrType','=',42)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

$PendingAmount= $request->input('Net_amount')-$PaidAmount;



DB::table('ledgerentry_details')->insert([
'tr_no' => $request->input('bill_code'),
'date' => $request->input('bill_date'),
'trtype' => 31, 
'ac_code' => $request->input('Ac_code'),
'contra_code' => 2 ,
'ac_type' =>'New Ref',
'BillNo' =>  $request->input('bill_code'),
'BillDate' =>  $request->input('bill_date'),
'Amount' =>  $request->input('Net_amount'),
'PayingAmount' => 0,
'Pending' => $PendingAmount,
'CounterId' => 1,
'UserId' => $request->input('user_id'),
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')

]);

 DB::table('transactions')->insert([
'TrType' => 31,
'TrNo' =>$request->input('bill_code'),
'Date' =>  $request->input('bill_date'),
'ref_no' => $request->input('bill_code'),
'ref_date' =>$request->input('bill_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('user_id'),
'DrCode' => $request->input('Ac_code'),
'CrCode' =>  2,
'Amount' =>   $request->input('Net_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' =>$request->input('c_code')

]);  




DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('Ac_code'),
'TrTyep' => 31,
'Date' => $request->input('bill_date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('bill_code'),
'DC' =>'cr',
'Amount' =>  $request->input('Net_amount'),
'contraId' => 2,
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  





return redirect()->route('GeneralSalesReturn.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GeneralSalesReturnMasterModel  $generalSalesReturnMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

  $master =GeneralSalesReturnMasterModel::where('bill_code',$id)->first();

  $master->delete();

   DB::table('sale_return_detail')->where('bill_code', $id)->delete();
   DB::table('ledgerentry_details')->where('tr_no', $id)->delete();
   DB::table('ledgerentry')->where('TrNo', $id)->delete();
   DB::table('transactions')->where('TrNo', $id)->delete();
        
return redirect()->route('GeneralSalesReturn.index')->with('delete', 'Delete Record Succesfully');
     

    }
}
