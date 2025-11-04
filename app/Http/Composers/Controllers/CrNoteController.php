<?php

namespace App\Http\Controllers;

use App\Models\CrNoteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class CrNoteController extends Controller
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
->where('form_id', '40')
->first();

        $data = CrNoteModel::join('ledger_master as LM1','LM1.ac_code', '=', 'cr_note_master.DrCode')
         ->join('ledger_master as LM2', 'LM2.ac_code', '=', 'cr_note_master.CrCode')
        ->join('broker_master', 'broker_master.br_id', '=', 'cr_note_master.br_id')
        ->join('usermaster', 'usermaster.userId', '=', 'cr_note_master.user_id')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'cr_note_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'cr_note_master.firm_id')    
        ->where('cr_note_master.delflag','=', '0')
     ->get(['cr_note_master.*','usermaster.username','LM1.ac_name as drname','LM2.ac_name as crname','firm_master.firm_name','tax_type_master.tax_type_name','broker_master.br_name']);

        return view('CrNoteList', compact('data','chekform'));

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
$brokerlist = DB::table('broker_master')->get();


return view('CrNote',compact('firmlist','ledgerlist','gstlist','brokerlist'));




    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
    $this->validate($request, [
            'date' => 'required',
            'tax_type_id' => 'required',
            'CrCode' => 'required',
            'DrCode' => 'required',
            'gst_no' => 'required',
        ]);


   $firm_id=$request->input('firm_id');      

//DB::enableQueryLog();
  $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','SRETURN-CRNOTE')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;


     $data = array('CrNote_Code'=>$TrNo,
"firm_id"=> $request->input('firm_id'),
"date"=> $request->input('date'),
"tax_type_id"=> $request->input('tax_type_id'),
"DrCode"=> $request->input('DrCode'),
"CrCode"=> $request->input('CrCode'),
"gst_no"=> $request->input('gst_no'),
"party_ref_no"=> $request->input('party_ref_no'),
"ag_bill_no"=> $request->input('ag_bill_no'),
"bill_date"=> $request->input('bill_date'),
"hsn_no"=> $request->input('hsn_no'),
"narration"=> $request->input('narration'),
"basic_amount"=> $request->input('basic_amount'),
"cgst_per"=> $request->input('cgst_per'),
"cgst_amount"=> $request->input('cgst_amount'),
"sgst_per"=> $request->input('sgst_per'),
"sgst_amount"=> $request->input('sgst_amount'),
"igst_per"=> $request->input('igst_per'),
"igst_amount"=> $request->input('igst_amount'),
"gst_amount"=> $request->input('gst_amount'),
"cr_amount"=> $request->input('cr_amount'),
"br_id"=> $request->input('br_id'),
"c_code"=> $codefetch->c_code,
"user_id"=> $request->input('userId'),
"delflag"=> 0
);

// Insert
$value = CrNoteModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='SRETURN-CRNOTE' AND firm_id='".$request->input('firm_id')."'");  



 DB::table('transactions')->insert([
'TrType' => 31,
'TrNo' => $TrNo,
'Date' =>  $request->input('date'),
'ref_no' => '',
'ref_date' => $request->input('date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('DrCode'),
'CrCode' =>  $request->input('CrCode'),
'Amount' =>   $request->input('cr_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' =>$codefetch->c_code
]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('CrCode'),
'TrTyep' => 31,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->cr_amount,
'contraId' => $request->input('DrCode'),
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
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->cgst_amount,
'contraId' => $request->input('DrCode'),
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
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->sgst_amount,
'contraId' => $request->input('DrCode'),
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
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>   $request->igst_amount,
'contraId' => $request->input('DrCode'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('DrCode'),
'TrTyep' => 31,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->input('basic_amount'),
'contraId' => 2,
'Naration' => $request->input('narration'),
'counterid' => $request->input('CrCode'), 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


return redirect()->route('CrNote.index');

}

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DrNoteModel  $drNoteModel
     * @return \Illuminate\Http\Response
     */
    public function show(DrNoteModel $drNoteModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DrNoteModel  $drNoteModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
   $firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$brokerlist = DB::table('broker_master')->get();


    $crnotefetch = CrNoteModel::find($id);

return view('CrNote',compact('firmlist','ledgerlist','gstlist','brokerlist','crnotefetch'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DrNoteModel  $drNoteModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$CrNote_Code)
    {
    


     $data = array('CrNote_Code'=>$request->input('CrNote_Code'),
"firm_id"=> $request->input('firm_id'),
"date"=> $request->input('date'),
"tax_type_id"=> $request->input('tax_type_id'),
"DrCode"=> $request->input('DrCode'),
"CrCode"=> $request->input('CrCode'),
"gst_no"=> $request->input('gst_no'),
"party_ref_no"=> $request->input('party_ref_no'),
"ag_bill_no"=> $request->input('ag_bill_no'),
"bill_date"=> $request->input('bill_date'),
"hsn_no"=> $request->input('hsn_no'),
"narration"=> $request->input('narration'),
"basic_amount"=> $request->input('basic_amount'),
"cgst_per"=> $request->input('cgst_per'),
"cgst_amount"=> $request->input('cgst_amount'),
"sgst_per"=> $request->input('sgst_per'),
"sgst_amount"=> $request->input('sgst_amount'),
"igst_per"=> $request->input('igst_per'),
"igst_amount"=> $request->input('igst_amount'),
"gst_amount"=> $request->input('gst_amount'),
"cr_amount"=> $request->input('cr_amount'),
"br_id"=> $request->input('br_id'),
"c_code"=> $request->input('c_code'),
"user_id"=> $request->input('userId'),
"delflag"=> 0
);


 $crnote= CrNoteModel::findOrFail($CrNote_Code);    

 $crnote->fill($data)->save();



   DB::table('ledgerentry')->where('TrNo', $request->input('CrNote_Code'))->delete();
   DB::table('transactions')->where('TrNo', $request->input('CrNote_Code'))->delete();



 DB::table('transactions')->insert([
'TrType' => 31,
'TrNo' => $request->input('CrNote_Code'),
'Date' =>  $request->input('date'),
'ref_no' => '',
'ref_date' => $request->input('date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('DrCode'),
'CrCode' =>  $request->input('CrCode'),
'Amount' =>   $request->input('cr_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' =>$request->input('c_code')
]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('CrCode'),
'TrTyep' => 31,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('CrNote_Code'),
'DC' =>'cr',
'Amount' =>  $request->cr_amount,
'contraId' => $request->input('DrCode'),
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
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('CrNote_Code'),
'DC' =>'dr',
'Amount' =>  $request->cgst_amount,
'contraId' => $request->input('DrCode'),
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
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('CrNote_Code'),
'DC' =>'dr',
'Amount' =>  $request->sgst_amount,
'contraId' => $request->input('DrCode'),
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
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('CrNote_Code'),
'DC' =>'dr',
'Amount' =>   $request->igst_amount,
'contraId' => $request->input('DrCode'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('DrCode'),
'TrTyep' => 31,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('CrNote_Code'),
'DC' =>'dr',
'Amount' =>  $request->input('basic_amount'),
'contraId' => 2,
'Naration' => $request->input('narration'),
'counterid' => $request->input('CrCode'), 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  
 

return redirect()->route('CrNote.index');


    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrNoteModel  $crNoteModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($crNoteModel)
    {
        
       $master =CrNoteModel::where('CrNote_Code',$crNoteModel)->first();

      $master->delete();

   DB::table('ledgerentry')->where('TrNo', $crNoteModel)->delete();
   DB::table('transactions')->where('TrNo', $crNoteModel)->delete();

        
        return redirect()->route('CrNote.index')->with('message', 'Delete Record Succesfully');


    }

 public function GetData(Request $request)
{
//

$ac_code= $request->id;


$data = DB::select(DB::raw("select gst_no, phone, state_id from ledger_master where ac_code='$ac_code'"));

echo json_encode($data);
/*
DB::enableQueryLog();
$query = DB::getQueryLog();
$query = end($query);
dd($query);*/


}




}
