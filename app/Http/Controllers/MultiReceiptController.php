<?php

namespace App\Http\Controllers;

use App\Models\MultiReceiptMasterModel;
use App\Models\MultiReceiptDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MultiReceiptController extends Controller
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
->where('form_id', '12')
->first();



        $data = MultiReceiptMasterModel::join('ledger_master','ledger_master.ac_code', '=', 'multireceipt_master.dr_code')
        ->join('usermaster', 'usermaster.userId', '=', 'multireceipt_master.userId')
         ->join('payment_mode', 'payment_mode.pay_mode', '=', 'multireceipt_master.pay_mode')
         ->join('firm_master', 'firm_master.firm_id', '=', 'multireceipt_master.firm_id')    
        ->get(['multireceipt_master.*','usermaster.username','ledger_master.ac_name','firm_master.firm_name','payment_mode.Pay_mode_name']);


        return view('Multi_Receipt_Transaction_List', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

$firmlist = DB::table('firm_master')->get();
$cashbank = DB::table('ledger_master')->whereIn('group_main',array(1,3))->get();

$ledgerlist = DB::table('ledger_master')
             ->select('ac_code','ac_name')
->where('delflag',0)->get();

$billlist = DB::table('transactions')
             ->select('TrType','TrNo','ref_no','ref_date','Amount')
->where('TrType',81)->get();


$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$paytypelist = DB::table('pay_mode')->get();

return view('Multi_Receipt_Transaction',compact('firmlist','cashbank','gstlist','itemlist','paytypelist','ledgerlist','billlist'));

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
  ->where('type','=','RECEIPT')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;

       
$data = array('tr_code'=>$TrNo,
"tr_date"=> $request->input('tr_date'),
"firm_id"=> $request->input('firm_id'),
"pay_mode"=> $request->input('pay_mode'),
"dr_code"=> $request->input('dr_code'),
"total_amount"=> $request->input('total_amount'),
"narration"=> $request->input('narration'),
"CounterId"=> 1,
"userId"=> $request->input('userId'),
"narration"=> $request->input('narration'),
"c_code"=> $codefetch->c_code
);

// Insert
$value = MultiReceiptMasterModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}

$cnt = $request->input('cnt');

if($cnt>0)
{
    $PendingAmount=0;
    $Bills='';  
for($x=0; $x<$cnt; $x++) {
# code...
$data2=array(

'tr_code' =>$TrNo,
'tr_date' => $request->input('tr_date'),
'firm_id' => $request->input('firm_id'),
'pay_mode' => $request->input('pay_mode'),
'dr_code' => $request->input('dr_code'),
'Ac_code' => $request->CrCodes[$x],
'tr_nos' => $request->TrNoss[$x],
'bill_amount' => $request->Amounts[$x],
'paying_amount' => $request->PayingAmounts[$x],
'CounterId' => 1,
'UserId' => $request->userId,
'rec_sre_type_id' => 1
);

MultiReceiptDetailModel::insert($data2);

if($request->TrNoss[$x]!='--Select Invoice--')
                            {

 $ResultID = DB::table('transactions')->select(DB::raw("transactions.Date as Date"))
  ->where('TrNo','=',$request->TrNoss[$x])
  ->where('TrType','=',42)
   ->where('firm_id','=',$firm_id)
  ->first();

$TransDate=$ResultID->Date;


    

$sqlDate = DB::table('ledgerentry_details')->select(DB::raw("ifnull(sum(PayingAmount),0) as PaidAmount"))
  ->where('BillNo','=',$request->TrNoss[$x])
  ->where('trtype','=',$request->TrType)
   ->where('firm_id','=',$firm_id)
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

   $PendingAmount= $request->Amounts[$x]-$PaidAmount-$request->PayingAmounts[$x];

                
DB::select("update ledgerentry_details set Pending='".$PendingAmount."' where BillNo='".$request->TrNoss[$x]."' and firm_id='".$firm_id."' and TrType=42");
                    


DB::table('ledgerentry_details')->insert([
'tr_no' => $TrNo,
'date' => $request->input('tr_date'),
'trtype' => $request->input('TrType'), 
'ac_code' => $request->dr_code,
'contra_code' =>$request->CrCodes[$x],
'ac_type' =>'Against Ref',
'BillNo' => $request->TrNoss[$x],
'BillDate' => $TransDate,
'Amount' => $request->Amounts[$x],
'PayingAmount' => $request->PayingAmounts[$x],
'Pending' => $PendingAmount[$x],
'CounterId' => 1,
'UserId' => $request->userId,
'firm_id' => $request->input('firm_id'),
'c_code' => $codefetch->c_code

]);


                        
                        if($request->TrNoss[$x]=='--Select Invoice--' || $request->TrNoss[$x]=='')
                            {


DB::table('ledgerentry_details')->insert([
'tr_no' => $TrNo,
'date' => $request->input('tr_date'),
'trtype' => $request->input('TrType'), 
'ac_code' => $request->dr_code,
'contra_code' =>$request->CrCodes[$x],
'ac_type' =>'Advance',
'BillNo' => 'Advance',
'BillDate' => $TransDate,
'Amount' => $request->Amounts[$x],
'PayingAmount' => $request->PayingAmounts[$x],
'Pending' => $PendingAmount[$x],
'CounterId' => 1,
'UserId' => $request->userId,
'firm_id' => $request->input('firm_id'),
'c_code' => $codefetch->c_code

]);
              
                            }
                            
                            
                        $Bills=$Bills.'/'.$request->TrNoss[$x];                    



 DB::table('ledgerentry')->insert([
'Ac_code' => $request->dr_code,
'TrTyep' => $request->input('TrType'),
'Date' => $request->input('tr_date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'dr',
'Amount' =>  $request->PayingAmounts[$x],
'contraId' => $request->CrCodes[$x],
'Naration' => 'Bill No'.':'.$Bills.'and'.$request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  



 DB::table('ledgerentry')->insert([
'Ac_code' =>  $request->CrCodes[$x],
'TrTyep' => $request->input('TrType'),
'Date' => $request->input('tr_date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'cr',
'Amount' => $request->PayingAmounts[$x],
'contraId' =>$request->dr_code,
'Naration' => 'Bill No'.':'.$Bills.'and'.$request->input('narration'),
'CounterId' => 1,
'UserId' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


 DB::table('transactions')->insert([
'TrType' => $request->input('TrType'),
'TrNo' =>$TrNo,
'Date' => $request->input('tr_date'),
'ref_no' => $Bills,
'ref_date' =>$request->input('tr_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->userId,
'DrCode' => $request->dr_code,
'CrCode' =>  $request->CrCodes[$x],
'Amount' =>  $request->PayingAmounts[$x],
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>$request->input('pay_mode'),
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


}



}

//DB::table('order_detail')->insert($data2);
}

$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='RECEIPT' AND firm_id='".$firm_id."'");



 return redirect()->route('MultiReceipt.index')->with('message', 'Add Record Succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MultiReceiptMasterModel  $multiReceiptMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(MultiReceiptMasterModel $multiReceiptMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MultiReceiptMasterModel  $multiReceiptMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    $firmlist = DB::table('firm_master')->get();
$cashbank = DB::table('ledger_master')->whereIn('group_main',array(1,3))->get();



$ledgerlist = DB::table('ledger_master')
             ->select('ac_code','ac_name')
->where('delflag',0)->get();


// DB::enableQueryLog();


$billlist = DB::table('transactions')
             ->select('TrType','TrNo','ref_no','ref_date','Amount')         
->where('TrType',42)
 ->whereNotIn('TrNo', DB::table('ledgerentry_details')
    ->select('BillNo') 
    ->where('Pending',0))
->get(); 

// $query = DB::getQueryLog();
// $query = end($query);
// dd($query);


$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$paytypelist = DB::table('pay_mode')->get();

 $multireceiptfetch = MultiReceiptMasterModel::find($id);



 $detailmultireceipt = MultiReceiptDetailModel::join('ledger_master','ledger_master.Ac_code', '=', 'multireceipt_detail.Ac_code')
  ->where('tr_code','=', $multireceiptfetch->tr_code)->get(['multireceipt_detail.*','ledger_master.Ac_name']);



return view('Multi_Receipt_Transaction_Edit',compact('firmlist','cashbank','gstlist','itemlist','paytypelist','ledgerlist','billlist','detailmultireceipt','multireceiptfetch'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MultiReceiptMasterModel  $multiReceiptMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $TrNo)
    {
            
$data = array('tr_code'=>$request->input('TrNo'),
"tr_date"=> $request->input('tr_date'),
"firm_id"=> $request->input('firm_id'),
"pay_mode"=> $request->input('pay_mode'),
"dr_code"=> $request->input('dr_code'),
"total_amount"=> $request->input('total_amount'),
"narration"=> $request->input('narration'),
"CounterId"=> 1,
"userId"=> $request->input('userId'),
"narration"=> $request->input('narration'),
"c_code"=> $request->input('c_code')
);

// Update

$multireceipt = MultiReceiptMasterModel::findOrFail($TrNo);  

$multireceipt->fill($data)->save();


  DB::table('multireceipt_detail')->where('tr_code', $request->input('TrNo'))->delete();
  DB::table('ledgerentry_details')->where('tr_no', $request->input('TrNo'))->delete();
  DB::table('ledgerentry')->where('TrNo', $request->input('TrNo'))->delete();
  DB::table('transactions')->where('TrNo', $request->input('TrNo'))->delete();





$cnt = $request->input('CrCodes');

if($cnt>0)
{
    $PendingAmount=0;
    $Bills='';  
for($x=0; $x<count($request->input('CrCodes')); $x++) {
# code...
$data2=array(

'tr_code' =>$request->input('TrNo'),
'tr_date' => $request->input('tr_date'),
'firm_id' => $request->input('firm_id'),
'pay_mode' => $request->input('pay_mode'),
'dr_code' => $request->input('dr_code'),
'Ac_code' => $request->CrCodes[$x],
'tr_nos' => $request->TrNoss[$x],
'bill_amount' => $request->Amounts[$x],
'paying_amount' => $request->PayingAmounts[$x],
'CounterId' => 1,
'UserId' => $request->userId,
'rec_sre_type_id' => 1
);

MultiReceiptDetailModel::insert($data2);

if($request->TrNoss[$x]!='--Select Invoice--')
                            {

 $ResultID = DB::table('transactions')->select(DB::raw("transactions.Date as Date"))
  ->where('TrNo','=',$request->TrNoss[$x])
  ->where('TrType','=',42)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$TransDate=$ResultID->Date;


    

$sqlDate = DB::table('ledgerentry_details')->select(DB::raw("ifnull(sum(PayingAmount),0) as PaidAmount"))
  ->where('BillNo','=',$request->TrNoss[$x])
  ->where('trtype','=',$request->TrType)
   ->where('firm_id','=',$request->input('firm_id'))
  ->first();

$PaidAmount=$sqlDate->PaidAmount;

   $PendingAmount= $request->Amounts[$x]-$PaidAmount-$request->PayingAmounts[$x];

                
DB::select("update ledgerentry_details set Pending='".$PendingAmount."' where BillNo='".$request->TrNoss[$x]."' and firm_id='".$request->input('firm_id')."' and TrType=42");
                    


DB::table('ledgerentry_details')->insert([
'tr_no' => $request->input('TrNo'),
'date' => $request->input('tr_date'),
'trtype' => $request->input('TrType'), 
'ac_code' => $request->dr_code,
'contra_code' =>$request->CrCodes[$x],
'ac_type' =>'Against Ref',
'BillNo' => $request->TrNoss[$x],
'BillDate' => $TransDate,
'Amount' => $request->Amounts[$x],
'PayingAmount' => $request->PayingAmounts[$x],
'Pending' => $PendingAmount[$x],
'CounterId' => 1,
'UserId' => $request->userId,
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')

]);


                        
                        if($request->TrNoss[$x]=='--Select Invoice--' || $request->TrNoss[$x]=='')
                            {


DB::table('ledgerentry_details')->insert([
'tr_no' => $request->input('TrNo'),
'date' => $request->input('tr_date'),
'trtype' => $request->input('TrType'), 
'ac_code' => $request->dr_code,
'contra_code' =>$request->CrCodes[$x],
'ac_type' =>'Advance',
'BillNo' => 'Advance',
'BillDate' => $TransDate,
'Amount' => $request->Amounts[$x],
'PayingAmount' => $request->PayingAmounts[$x],
'Pending' => $PendingAmount[$x],
'CounterId' => 1,
'UserId' => $request->userId,
'firm_id' => $request->input('firm_id'),
'c_code' => $request->input('c_code')

]);
              
                            }
                            
                            
                        $Bills=$Bills.'/'.$request->TrNoss[$x];                    



 DB::table('ledgerentry')->insert([
'Ac_code' => $request->dr_code,
'TrTyep' => $request->input('TrType'),
'Date' => $request->input('tr_date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'dr',
'Amount' =>  $request->PayingAmounts[$x],
'contraId' => $request->CrCodes[$x],
'Naration' => 'Bill No'.':'.$Bills.'and'.$request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



 DB::table('ledgerentry')->insert([
'Ac_code' =>  $request->CrCodes[$x],
'TrTyep' => $request->input('TrType'),
'Date' => $request->input('tr_date'),
'times' => date('h:i:s'),
'TrNo' =>$TrNo,
'DC' =>'cr',
'Amount' => $request->PayingAmounts[$x],
'contraId' =>$request->dr_code,
'Naration' => 'Bill No'.':'.$Bills.'and'.$request->input('narration'),
'CounterId' => 1,
'UserId' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  


 DB::table('transactions')->insert([
'TrType' => $request->input('TrType'),
'TrNo' =>$request->input('TrNo'),
'Date' => $request->input('tr_date'),
'ref_no' => $Bills,
'ref_date' =>$request->input('tr_date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->userId,
'DrCode' => $request->dr_code,
'CrCode' =>  $request->CrCodes[$x],
'Amount' =>  $request->PayingAmounts[$x],
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>$request->input('pay_mode'),
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  


}



}

//DB::table('order_detail')->insert($data2);
}


 return redirect()->route('MultiReceipt.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MultiReceiptMasterModel  $multiReceiptMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($TrNo)
    {
      
       DB::table('transactions')->where('TrNo', $TrNo)
  ->where('TrType', 82)->delete();

    DB::table('ledgerentry_details')->where('tr_no', $TrNo)
  ->where('trtype', 82)->delete();

   DB::table('ledgerentry')->where('TrNo', $TrNo)
  ->where('TrType', 82)->delete();

        
return redirect()->route('MultiReceipt.index')->with('delete', 'Delete Record Succesfully');  
    }


  public function getUnpaidBills(Request $request)
    {

    $Ac_code= $request->input('Ac_code');
    $firm_id=  $request->input('firm_id');


$unpaids = DB::select("SELECT  led_det.ac_code, ledger_master.Ac_name,
led_det.`BillNo`, led_det.`BillDate`, led_det.`Amount`,
 ((select Amount from ledgerentry_details where trtype=42 and ac_code='$Ac_code'  and firm_id='$firm_id'  and `BillNo`=led_det.`BillNo`)-
(select ifnull(sum(`PayingAmount`),0) from ledgerentry_details where trtype =82 and contra_code='$Ac_code'  and firm_id='$firm_id'  and `BillNo`=led_det.`BillNo`)
-(select ifnull(sum(`PayingAmount`),0) from ledgerentry_details where trtype = 31  and ac_code='$Ac_code'  and firm_id='$firm_id'  and `BillNo`=led_det.`BillNo`)

) 'PendingAmount'
FROM `ledgerentry_details` led_det
inner join ledger_master on ledger_master.Ac_code= led_det.ac_code
WHERE led_det.trtype=42 and led_det.ac_code='$Ac_code'   and  ((select Amount from ledgerentry_details where trtype=42   and firm_id='$firm_id' and ac_code='$Ac_code'   and `BillNo`=led_det.`BillNo`)-
(select ifnull(sum(`PayingAmount`),0) from ledgerentry_details where trtype =82 and contra_code='$Ac_code'  and firm_id='$firm_id'  and `BillNo`=led_det.`BillNo`)
-(select ifnull(sum(`PayingAmount`),0) from ledgerentry_details where trtype = 31  and ac_code='$Ac_code'  and firm_id='$firm_id'  and `BillNo`=led_det.`BillNo`)
)!=0  order by led_det.`BillDate`");

 $html = '';
foreach ($unpaids as $unpaid) {

$html .= '<option value="0">Advance</option>';
$html .= '<option value="'.$unpaid->BillNo.'">BillNo:'
.$unpaid->BillNo.'   |   Date:'.date('d-m-Y', strtotime($unpaid->BillDate)).'   |  BillAmt:'.$unpaid->Amount.'  |   PendingAmt:'.$unpaid->PendingAmount.'</option>';




}

return response()->json(['html' => $html]);

    }


  public function getReceiptDetail(Request $request)
    {

    $TrNo= $request->input('id');
    $Ac_code=  $request->input('Ac_code');


$receipt = DB::select("select ifnull(Amount,0) as Amount,
((select Amount from ledgerentry_details where trtype=42 and ac_code='".$Ac_code."'   and `BillNo`='".$TrNo."')-
(select ifnull(sum(`PayingAmount`),0) from ledgerentry_details where trtype =82 and contra_code='".$Ac_code."'   and `BillNo`='$TrNo')
-(select ifnull(sum(`PayingAmount`),0) from ledgerentry_details where trtype = 31  and ac_code='".$Ac_code."'   and `BillNo`='".$TrNo."')

) 'Pending'


  from ledgerentry_details where BillNo='".$TrNo."' and trtype=42  and ac_code='".$Ac_code."'");


echo json_encode($receipt);




    }



}
