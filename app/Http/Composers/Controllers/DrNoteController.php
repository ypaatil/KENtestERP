<?php

namespace App\Http\Controllers;

use App\Models\DrNoteModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class DrNoteController extends Controller
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
->where('form_id', '20')
->first();

        $data = DrNoteModel::join('ledger_master as LM1','LM1.ac_code', '=', 'dr_note_master.DrCode')
         ->join('ledger_master as LM2', 'LM2.ac_code', '=', 'dr_note_master.CrCode')
        ->join('broker_master', 'broker_master.br_id', '=', 'dr_note_master.br_id')
        ->join('usermaster', 'usermaster.userId', '=', 'dr_note_master.user_id')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'dr_note_master.tax_type_id')
         ->join('firm_master', 'firm_master.firm_id', '=', 'dr_note_master.firm_id')    
        ->where('dr_note_master.delflag','=', '0')
     ->get(['dr_note_master.*','usermaster.username','LM1.ac_name as drname','LM2.ac_name as crname','firm_master.firm_name','tax_type_master.tax_type_name','broker_master.br_name']);

        return view('DrNoteList', compact('data','chekform'));

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


return view('DrNote',compact('firmlist','ledgerlist','gstlist','brokerlist'));




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
  ->where('type','=','PRETURN-DRNOTE')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;


     $data = array('DrNote_Code'=>$TrNo,
"firm_id"=> $request->input('firm_id'),
"date"=> $request->input('date'),
"tax_type_id"=> $request->input('tax_type_id'),
"CrCode"=> $request->input('CrCode'),
"DrCode"=> $request->input('DrCode'),
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
"dr_amount"=> $request->input('dr_amount'),
"br_id"=> $request->input('br_id'),
"c_code"=> $codefetch->c_code,
"branch_id"=> 0,
"user_id"=> $request->input('userId'),
"delflag"=> 0
);

// Insert
$value = DrNoteModel::insert($data);
if($value){
Session::flash('message','Insert successfully.');
}else{
Session::flash('message','Username already exists.');
}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='PRETURN-DRNOTE' AND firm_id='".$request->input('firm_id')."'");  



 DB::table('transactions')->insert([
'TrType' => 51,
'TrNo' => $TrNo,
'Date' =>  $request->input('date'),
'ref_no' => '',
'ref_date' => $request->input('date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('DrCode'),
'CrCode' =>  $request->input('CrCode'),
'Amount' =>   $request->input('dr_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' =>$codefetch->c_code
]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('DrCode'),
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'dr',
'Amount' =>  $request->dr_amount,
'contraId' => $request->input('CrCode'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);     
                    

 //CGST AMOUNT Cr to CGST INPUT A/C
DB::table('ledgerentry')->insert([
'Ac_code' => 34,
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
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
'Ac_code' => 35,
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
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
'Ac_code' => 36,
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>   $request->igst_amount,
'contraId' => $request->input('DrCode'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('CrCode'),
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $TrNo,
'DC' =>'cr',
'Amount' =>  $request->input('basic_amount'),
'contraId' => 2,
'Naration' => $request->input('narration'),
'counterid' => $request->input('DrCode'), 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $codefetch->c_code

]);  


return redirect()->route('DrNote.index');

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


    $drnotefetch = DrNoteModel::find($id);

return view('DrNote',compact('firmlist','ledgerlist','gstlist','brokerlist','drnotefetch'));


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DrNoteModel  $drNoteModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$DrNote_Code)
    {
    


     $data = array('DrNote_Code'=>$request->input('DrNote_Code'),
"firm_id"=> $request->input('firm_id'),
"date"=> $request->input('date'),
"tax_type_id"=> $request->input('tax_type_id'),
"CrCode"=> $request->input('CrCode'),
"DrCode"=> $request->input('DrCode'),
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
"dr_amount"=> $request->input('dr_amount'),
"br_id"=> $request->input('br_id'),
"c_code"=> $request->input('c_code'),
"branch_id"=> 0,
"user_id"=> $request->input('userId'),
"delflag"=> 0
);


 $drnote= DrNoteModel::findOrFail($DrNote_Code);    

 $drnote->fill($data)->save();



   DB::table('ledgerentry')->where('TrNo', $request->input('DrNote_Code'))->delete();
   DB::table('transactions')->where('TrNo', $request->input('DrNote_Code'))->delete();



 DB::table('transactions')->insert([
'TrType' => 51,
'TrNo' => $request->input('DrNote_Code'),
'Date' =>  $request->input('date'),
'ref_no' => '',
'ref_date' => $request->input('date'),
'times' => date('h:i:s'),
'CounterId' =>1,
'UserId' => $request->input('userId'),
'DrCode' => $request->input('DrCode'),
'CrCode' =>  $request->input('CrCode'),
'Amount' =>   $request->input('dr_amount'),
'Naration' => $request->input('narration'),
'multientry' => 0,
'Pay_mode' =>0,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')
]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('DrCode'),
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('DrNote_Code'),
'DC' =>'dr',
'Amount' =>  $request->dr_amount,
'contraId' => $request->input('CrCode'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);     
                    

 //CGST AMOUNT Cr to CGST OUTPUT A/C
DB::table('ledgerentry')->insert([
'Ac_code' => 34,
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' =>$request->input('DrNote_Code'),
'DC' =>'cr',
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
'Ac_code' => 35,
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('DrNote_Code'),
'DC' =>'cr',
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
'Ac_code' => 36,
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('DrNote_Code'),
'DC' =>'cr',
'Amount' =>   $request->igst_amount,
'contraId' => $request->input('DrCode'),
'Naration' => $request->input('narration'),
'counterid' => 1, 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  



DB::table('ledgerentry')->insert([
'Ac_code' => $request->input('CrCode'),
'TrTyep' => 51,
'Date' => $request->input('date'),
'times' => date('h:i:s'),
'TrNo' => $request->input('DrNote_Code'),
'DC' =>'cr',
'Amount' =>  $request->input('basic_amount'),
'contraId' => 2,
'Naration' => $request->input('narration'),
'counterid' => $request->input('DrCode'), 
'userid' => 1,
'firm_id' =>$request->input('firm_id'),
'c_code' => $request->input('c_code')

]);  


return redirect()->route('DrNote.index')->with('message', 'Update Record Succesfully');;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DrNoteModel  $drNoteModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($drNoteModel)
    {
       
        $master= DrNoteModel::where('DrNote_Code',$drNoteModel)->first();

       $master->delete();

        DB::table('ledgerentry')->where('TrNo', $drNoteModel)->delete();
        DB::table('transactions')->where('TrNo', $drNoteModel)->delete();

        
        return redirect()->route('DrNote.index')->with('delete', 'Delete Record Succesfully');

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
