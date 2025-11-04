<?php

namespace App\Http\Controllers;

use App\Models\RequisitionOutwardMasterModel;
use App\Models\RequisitionMasterModel;
use App\Models\RequisitionOutwardDetailModel;
use Illuminate\Http\Request;
use DB;
use Session;

class RequisitionOutwardController extends Controller
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
->where('form_id', '58')
->first();
        
       $ReqOutwardList = RequisitionOutwardMasterModel::join('usermaster', 'usermaster.userId', '=', 'requisition_outward_master.userId')
       ->join('reasonmaster', 'reasonmaster.reasonId', '=', 'requisition_outward_master.reasonId')
  ->join('department_master', 'department_master.dept_id', '=', 'requisition_outward_master.dept_id')
->join('machinemaster', 'machinemaster.machineId', '=', 'requisition_outward_master.machineId')
        ->get(['requisition_outward_master.*','usermaster.username','reasonmaster.reason','department_master.dept_name','machinemaster.machineName']);
    // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
return view('RequisitionOutwardList', compact('ReqOutwardList','chekform'));


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

return view('RequisitionOutward',compact('firmlist','ledgerlist','gstlist','itemlist','locationlist','departmentlist','unitlist','machinelist','reasonlist')); 


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
  ->where('type','=','RequisitionOutward')
   ->where('firm_id','=',$firm_id)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;

     
 $data = array('requisition_outward_no'=>$TrNo,
"requisition_outward_date"=> $request->input('requisitionDate'),
"requisitionNo"=> $request->input('requisitionno'),
"dept_id"=> $request->input('dept_id'),
"machineId"=> $request->input('machineId'),
"issueTo"=> $request->input('issueTo'),
"reasonId"=> $request->input('reasonId'),
"firm_id"=> $request->input('firm_id'),
"userId"=> $request->input('userId'),
"isDeleted"=>0
);

// Insert
$value = RequisitionOutwardMasterModel::insert($data);
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

'requisition_outward_no' =>$TrNo,
'requisition_outward_date' => $request->input('requisitionDate'),
'requisitionNo' => $request->input('requisitionno'),
'item_code' => $request->item_codes[$x],
'unit_id' => $request->unit_id[$x],
'balanceQty' => $request->balanceQty[$x],
'approvedQty' => $request->approvedQty[$x],
'issuedQty' => $request->issuedQty[$x],
'firm_id' => $request->firm_id);

RequisitionOutwardDetailModel::insert($data2);


DB::table('itemtransaction')->insert([
'trNo' => $TrNo,
'trDate' => $request->input('requisitionDate'),
'trType' => 2,
'item_code' => $request->item_codes[$x],
'qtyIn' => 0,
'qtyOut' =>$request->approvedQty[$x],
'isDeleted' =>0,
'isModify' => date('Y-m-d h:i:s')
]); 
                  

}

}



$update = DB::select("update counter_number set tr_no= tr_no + 1   where c_name ='C1' AND type='RequisitionOutward' AND firm_id='".$request->input('firm_id')."'");  


  return redirect()->route('RequisitionOutward.index')->with('message', 'Add Record Succesfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RequisitionOutwardMasterModel  $requisitionOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(RequisitionOutwardMasterModel $requisitionOutwardMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RequisitionOutwardMasterModel  $requisitionOutwardMasterModel
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
    $requisitionoutwardfetch = RequisitionOutwardMasterModel::find($id);

 $detailrequisitionoutward = RequisitionOutwardDetailModel::join('item_master','item_master.item_code', '=', 'requisition_outward_detail.item_code')
  ->where('requisition_outward_detail.requisition_outward_no','=', $requisitionoutwardfetch->requisition_outward_no)->get(['requisition_outward_detail.*','item_master.item_name']);


        return view('RequisitionOutwardEdit',compact('requisitionoutwardfetch','firmlist','ledgerlist','gstlist','itemlist','detailrequisitionoutward','locationlist','departmentlist','unitlist','machinelist','reasonlist'));     



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RequisitionOutwardMasterModel  $requisitionOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {

     
 $data = array('requisition_outward_no'=>$request->requisition_outward_no,
"requisition_outward_date"=> $request->input('requisition_outward_date'),
"requisitionNo"=> $request->input('requisitionno'),
"dept_id"=> $request->input('dept_id'),
"machineId"=> $request->input('machineId'),
"issueTo"=> $request->input('issueTo'),
"reasonId"=> $request->input('reasonId'),
"firm_id"=> $request->input('firm_id'),
"userId"=> $request->input('userId'),
"isDeleted"=>0
);

// update
$value = RequisitionOutwardMasterModel::findOrFail($id);

$value->fill($data)->save();


DB::table('requisition_outward_detail')->where('requisition_outward_no', $request->input('requisition_outward_no'))->delete();


DB::table('itemtransaction')->where('trNo', $request->input('requisition_outward_no'))->delete();




$cnt = $request->input('cnt');


if($cnt>0)
{

for($x=0; $x<$cnt; $x++) {
# code...

$data2=array(

'requisition_outward_no' =>$request->requisition_outward_no,
'requisition_outward_date' => $request->input('requisition_outward_date'),
'requisitionNo' => $request->input('requisitionno'),
'item_code' => $request->item_codes[$x],
'unit_id' => $request->unit_id[$x],
'balanceQty' => $request->balanceQty[$x],
'approvedQty' => $request->approvedQty[$x],
'issuedQty' => $request->issuedQty[$x],
'firm_id' => $request->firm_id);

RequisitionOutwardDetailModel::insert($data2);


DB::table('itemtransaction')->insert([
'trNo' => $TrNo,
'trDate' => $request->input('requisitionDate'),
'trType' => 2,
'item_code' => $request->item_codes[$x],
'qtyIn' => 0,
'qtyOut' =>$request->approvedQty[$x],
'isDeleted' =>0,
'isModify' => date('Y-m-d h:i:s')
]); 
                  

}

}



  return redirect()->route('RequisitionOutward.index')->with('message', 'Updated Record Succesfully');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RequisitionOutwardMasterModel  $requisitionOutwardMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

      $master=RequisitionOutwardDetailModel::where('requisition_outward_no',$id)->delete();

      $detail=RequisitionOutwardMasterModel::where('requisition_outward_no',$id)->delete();

     Session::flash('delete', 'Deleted record successfully'); 
     
    }

   public function getRequitionDetails(Request $request)
    {
       

       $requisitionNo= $request->requisitionNo;

 $itemlist=DB::table('item_master')
   ->get();

    $unitlist=DB::table('unit_master')
   ->get();



   $data=DB::table('requisition_detail')
   ->where('requisitionNo','=',$requisitionNo)
   ->get();

   $html='';

       $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>Request No</th>
<th>Item Name</th>
<th>Unit</th>
<th>BALANCE QTY</th>
<th>APPROVED QTY</th>
<th>ISSUED QTY</th>
<th>Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
      foreach ($data as $value) {
    
   $html .='<tr>';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
<td> <select name="item_codes[]"  id="item_code" style="width:100px;" required>
<option value="">--Select Item--</option>';

foreach($itemlist as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $value->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> 

<td> <select name="unit_id[]"  id="unit_id" style="width:100px;" required>
<option value="">--Part--</option>';
foreach($unitlist as  $rowunit)
{
    $html.='<option value="'.$rowunit->unit_id.'"';

    $rowunit->unit_id == $value->unit_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowunit->unit_name.'</option>';
}
 
$html.='</select></td>';
 
$html.='
<td><input type="text"  name="balanceQty[]"    value="'.$value->stockQty.'" id="balanceQty" style="width:80px;" required/></td>

<td><input type="text"  name="approvedQty[]"    value="'.$value->approvedQty.'" id="approvedQty" style="width:80px;" required/></td>

<td><input type="text" class="METER" name="issuedQty[]"   value="" id="issuedQty" style="width:80px;" required/></td>

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;


      }


return response()->json(['html' => $html]);

    }

  public function getMasterDetails(Request $request)
    {

         $requisitionNo= $request->requisitionNo;

    $data=RequisitionMasterModel::where('requisitionNo','=',$requisitionNo)
   ->get(['requisition_master.*']);

 
  return $data;


    }


}

