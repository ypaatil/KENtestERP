<?php

namespace App\Http\Controllers;

use App\Models\ReturnableOutwardMasterModel;
use App\Models\ReturnableOutwardDetailModel;
use Illuminate\Http\Request;
use DB;
use Session;

class ReturnableOutwardController extends Controller
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
->where('form_id', '59')
->first();

        $data = ReturnableOutwardMasterModel::join('ledger_master as lm1','lm1.ac_code', '=', 'returnableoutwardmaster.Ac_code')
        ->join('usermaster', 'usermaster.userId', '=', 'returnableoutwardmaster.userId')
         ->join('tax_type_master', 'tax_type_master.tax_type_id', '=', 'returnableoutwardmaster.tax_type_id')
         ->join('department_master', 'department_master.dept_id', '=', 'returnableoutwardmaster.dept_id')
           ->join('machinemaster', 'machinemaster.machineId', '=', 'returnableoutwardmaster.machineId')
         ->join('firm_master', 'firm_master.firm_id', '=', 'returnableoutwardmaster.firm_id')    
        ->where('returnableoutwardmaster.delflag','=', '0')
        ->get(['returnableoutwardmaster.*','usermaster.username','lm1.ac_name as ac_name1','firm_master.firm_name','tax_type_master.tax_type_name','department_master.dept_name','machinemaster.machineName']);

        return view('ReturnableOutwardList', compact('data','chekform'));    
    
     
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
$locationlist = DB::table('location_master')->get();
$departmentlist = DB::table('department_master')->get();
$unitlist = DB::table('unit_master')->get();
$machinelist = DB::table('machinemaster')->get();
$reasonlist = DB::table('reasonmaster')->get();

return view('ReturnableOutward',compact('firmlist','ledgerlist','gstlist','itemlist','locationlist','departmentlist','unitlist','machinelist','reasonlist')); 


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
  ->where('type','=','ReturnableOutward')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;


     
 $data = array('RetOutwardcode'=>$TrNo,
"RetOutwardDate"=> $request->input('pur_date'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"dept_id"=> $request->input('dept_id'),
"machineId"=> $request->input('machineId'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $codefetch->c_code,
"loc_id"=> $request->input('loc_id'),
"gstNo"=> $request->input('gstNo'),
"address"=> $request->input('address'),
"remark"=> $request->input('remark'),
"vehicalNo"=> $request->input('vehicalNo'),
"termOfPayment"=> $request->input('termOfPayment'),
"userId"=> $request->input('userId'),
"delflag"=>0
);

// Insert
$value = ReturnableOutwardMasterModel::insert($data);
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

'RetOutwardcode' =>$TrNo,
'RetOutwardDate' => $request->input('pur_date'),
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
'total_amount' => $request->total_amounts[$x],
'return_date'=>$request->return_date[$x],
'firm_id' => $request->firm_id);

ReturnableOutwardDetailModel::insert($data2);
                  

}

}


$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='ReturnableOutward' AND firm_id='".$request->input('firm_id')."'");  


  return redirect()->route('ReturnableOutward.index')->with('message', 'Add Record Succesfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReturnableOutwardMasterModel  $returnableOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(ReturnableOutwardMasterModel $returnableOutwardMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReturnableOutwardMasterModel  $returnableOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
$firmlist = DB::table('firm_master')->get();
$ledgerlist = DB::table('ledger_master')->get();
$gstlist = DB::table('tax_type_master')->get();
$itemlist = DB::table('item_master')->get();
$locationlist = DB::table('location_master')->get();
$departmentlist = DB::table('department_master')->get();
$unitlist = DB::table('unit_master')->get();
$machinelist = DB::table('machinemaster')->get();
$reasonlist = DB::table('reasonmaster')->get();

        $returnableoutwardfetch = ReturnableOutwardMasterModel::find($id);

 $detailoutward = ReturnableOutwardDetailModel::join('item_master','item_master.item_code', '=', 'returnableoutwarddetail.item_code')
  ->where('RetOutwardcode','=', $returnableoutwardfetch->RetOutwardcode)->get(['returnableoutwarddetail.*','item_master.item_name']);


return view('ReturnableOutwardEdit',compact('returnableoutwardfetch','firmlist','ledgerlist','gstlist','itemlist','locationlist','departmentlist','unitlist','machinelist','reasonlist','detailoutward')); 


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReturnableOutwardMasterModel  $returnableOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
      

     
 $data = array('RetOutwardcode'=>$request->input('RetOutwardcode'),
"RetOutwardDate"=> $request->input('RetOutwardDate'),
"Ac_code"=> $request->input('Ac_code'),
"tax_type_id"=> $request->input('tax_type_id'),
"dept_id"=> $request->input('dept_id'),
"machineId"=> $request->input('machineId'),
"Gross_amount"=> $request->input('Gross_amount'),
"Gst_amount"=> $request->input('Gst_amount'),
"Net_amount"=> $request->input('Net_amount'),
"firm_id"=> $request->input('firm_id'),
"c_code"=> $request->input('c_code'),
"loc_id"=> $request->input('loc_id'),
"gstNo"=> $request->input('gstNo'),
"address"=> $request->input('address'),
"remark"=> $request->input('remark'),
"vehicalNo"=> $request->input('vehicalNo'),
"termOfPayment"=> $request->input('termOfPayment'),
"userId"=> $request->input('userId'),
"delflag"=>0
);

// Insert
$value = ReturnableOutwardMasterModel::findOrFail($id);

 $value->fill($data)->save();


  DB::table('returnableoutwarddetail')->where('RetOutwardcode', $request->input('RetOutwardcode'))->delete();


$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0; $x<$cnt; $x++) {
# code...

$data2=array(

'RetOutwardcode' =>$request->input('RetOutwardcode'),
'RetOutwardDate' => $request->input('RetOutwardDate'),
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
'total_amount' => $request->total_amounts[$x],
'return_date'=>$request->return_date[$x],
'firm_id' => $request->firm_id);

ReturnableOutwardDetailModel::insert($data2);
                  

}

}


  return redirect()->route('ReturnableOutward.index')->with('message', 'Updated Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReturnableOutwardMasterModel  $returnableOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
DB::table('returnableoutwardmaster')->where('RetOutwardcode', $id)->delete();

DB::table('returnableoutwarddetail')->where('RetOutwardcode', $id)->delete();

 Session::flash('delete', 'Deleted record successfully'); 

    }
}
