<?php

namespace App\Http\Controllers;

use App\Models\RequisitionMasterModel;
use App\Models\RequisitionDetailModel;
use Illuminate\Http\Request;
use DB;
use Session;

class RequisitionController extends Controller
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
->where('form_id', '57')
->first();
        
       $ReqList = RequisitionMasterModel::join('usermaster', 'usermaster.userId', '=', 'requisition_master.userId')
     ->where('requisition_master.requisitionApproveFlag','=', '0') 
        ->get(['requisition_master.*','usermaster.username']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('RequisitionList', compact('ReqList','chekform'));


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
$locationlist = DB::table('location_master')->get();
$departmentlist = DB::table('department_master')->get();
$unitlist = DB::table('unit_master')->get();
$requisitiontypelist = DB::table('requisitiontypemaster')->get();
$machinelist = DB::table('machinemaster')->get();
$reasonlist = DB::table('reasonmaster')->get();

return view('Requisition',compact('firmlist','ledgerlist','gstlist','itemlist','code','locationlist','departmentlist','unitlist','machinelist','requisitiontypelist','reasonlist')); 

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
  ->where('type','=','Requisition')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;

     
 $data = array('requisitionNo'=>$TrNo,
"requisitionDate"=> $request->input('requisitionDate'),
"requisitionTypeId"=> $request->input('requisitionTypeId'),
"dept_id"=> $request->input('dept_id'),
"machineId"=> $request->input('machineId'),
"issueTo"=> $request->input('issueTo'),
"reasonId"=> $request->input('reasonId'),
"firm_id"=> $request->input('firm_id'),
"userId"=> $request->input('userId'),
"requisitionApproveFlag"=> 0,
"isDeleted"=>0
);

// Insert
$value = RequisitionMasterModel::insert($data);
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

'requisitionNo' =>$TrNo,
'requisitionDate' => $request->input('requisitionDate'),
'item_code' => $request->item_codes[$x],
'unit_id' => $request->unit_id[$x],
'requestedQty' => $request->requestedQty[$x],
'stockQty' => $request->stockQty[$x],
'approvedQty' => $request->approvedQty[$x],
'firm_id' => $request->firm_id);

RequisitionDetailModel::insert($data2);
                  

}

}



$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='Requisition' AND firm_id='".$request->input('firm_id')."'");  


  return redirect()->route('Requisition.index')->with('message', 'Add Record Succesfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequisitionMasterModel  $requisitionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
     
       $chekform = DB::table('form_auth')
->where('emp_id', Session::get('userId'))
->where('form_id', '45')
->first();
        
       $ReqList = RequisitionMasterModel::join('usermaster', 'usermaster.userId', '=', 'requisition_master.userId')
     ->where('requisition_master.requisitionApproveFlag','=', '1') 
        ->get(['requisition_master.*','usermaster.username']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('requisitionApprovalList', compact('ReqList','chekform'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequisitionMasterModel  $requisitionMasterModel
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
$requisitiontypelist = DB::table('requisitiontypemaster')->get();
$machinelist = DB::table('machinemaster')->get();
$reasonlist = DB::table('reasonmaster')->get();


    $requisitionfetch = RequisitionMasterModel::find($id);

 $detailrequisition = RequisitionDetailModel::join('item_master','item_master.item_code', '=', 'requisition_detail.item_code')
  ->where('requisitionNo','=', $requisitionfetch->requisitionNo)->get(['requisition_detail.*','item_master.item_name']);


        return view('RequisitionEdit',compact('requisitionfetch','firmlist','ledgerlist','gstlist','itemlist','detailrequisition','locationlist','departmentlist','unitlist','requisitiontypelist','machinelist','reasonlist'));     


        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequisitionMasterModel  $requisitionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $requisitionNo)
    {

      
 $data = array('requisitionNo'=>$request->input('requisitionNo'),
"requisitionDate"=> $request->input('requisitionDate'),
"requisitionTypeId"=> $request->input('requisitionTypeId'),
"dept_id"=> $request->input('dept_id'),
"machineId"=> $request->input('machineId'),
"issueTo"=> $request->input('issueTo'),
"reasonId"=> $request->input('reasonId'),
"firm_id"=> $request->input('firm_id'),
"userId"=> $request->input('userId'),
"requisitionApproveFlag"=> $request->input('requisitionApproveFlag'),
"isDeleted"=>0
);

// update

 $value = RequisitionMasterModel::findOrFail($requisitionNo);  
 $value->fill($data)->save();


DB::table('requisition_detail')->where('requisitionNo', $request->input('requisitionNo'))->delete();


$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0; $x<$cnt; $x++) {
# code...

$data2=array(

'requisitionNo' =>$request->input('requisitionNo'),
'requisitionDate' => $request->input('requisitionDate'),
'item_code' => $request->item_codes[$x],
'unit_id' => $request->unit_id[$x],
'requestedQty' => $request->requestedQty[$x],
'stockQty' => $request->stockQty[$x],
'approvedQty' => $request->approvedQty[$x],
'firm_id' => $request->firm_id);

RequisitionDetailModel::insert($data2);
                  

}

}

return redirect()->route('Requisition.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequisitionMasterModel  $requisitionMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      
$master=RequisitionMasterModel::where('requisitionNo',$id)->delete();

$detail=RequisitionDetailModel::where('requisitionNo',$id)->delete();

     Session::flash('delete', 'Deleted record successfully');   
    }

  public function GETSTOCK(Request $request)
    {
        
        $item_code=$request->item_code;

$data = DB::select(DB::raw("SELECT `srNo`, `trNo`, `trDate`, `trType`, `item_code`, `qtyIn`, `qtyOut`, `isDeleted`, `isModify`,

((select ifnull(sum(itemtransaction.`qtyIn`),0) from itemtransaction where itemtransaction.isDeleted=0 
and itemtransaction.trType=1 and item_code='$item_code') 
-  

(select ifnull(sum(itemtransaction.`qtyOut`),0) from itemtransaction where itemtransaction.isDeleted=0 
and itemtransaction.trType=2 and item_code='$item_code')) as 'Stock'

FROM `itemtransaction`"));

echo json_encode($data);



    }





}
