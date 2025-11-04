<?php

namespace App\Http\Controllers;
use App\Models\FabricTransactionModel;
use App\Models\FabricCheckingModel;
use App\Models\FabricCheckingDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use  App\Models\FabricInwardModel;
use App\Models\FabricDefectModel;
use App\Models\POTypeModel;
use App\Models\PurchaseOrderModel;
use App\Models\ItemModel;
use App\Models\ShadeModel;
use App\Models\RackModel;
use App\Models\PartModel;
use App\Models\DefectModel;
use App\Models\CounterNumberModel;
use App\Models\FabricCheckStatusModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FabricCheckingReportController;
use Session;


class FabricCheckingController extends Controller
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
        ->where('form_id', '37')
        ->first();  
    
        
          //   DB::enableQueryLog();
          $FabricCheckingList = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
          ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
          ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
          ->where('fabric_checking_master.delflag','=', '0')
          ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
      // $query = DB::getQueryLog();
      //     $query = end($query);
      //     dd($query);
          return view('FabricCheckingMasterList', compact('FabricCheckingList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CHECKING'");
         $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
        $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
         $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
         $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_type_id','=', '1')->get();
        $CPList =  DB::table('cp_master')->get();
          $DefectList =  FabricDefectModel::where('fabric_defect_master.delflag', '=', '0')->get();
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
         $GRNList = FabricInwardModel::select('in_code')->where('inward_master.delflag','=', '0')->get(); 
        return view('FabricCheckingMaster',compact('Ledger','FGList','GRNList','DefectList','POList','RackList',  'CPList', 'ShadeList','counter_number', 'ItemList', 'PartList','FabCheckList','POTypeList','BOMLIST','CPList'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
             
             
               $codefetch = DB::table('counter_number')->select(DB::raw("tr_no + 1 as 'tr_no',c_code,code"))
  ->where('c_name','=','C1')
  ->where('type','=','CHECKING')
  ->where('firm_id','=',1)
  ->first();
/*$query = DB::getQueryLog();
$query = end($query);
dd($query);*/
 $TrNo=$codefetch->code.'-'.$codefetch->tr_no;   
             
$is_opening=isset($request->is_opening) ? 1 : 0;

                $data1=array(

                    'chk_code'=>$TrNo, 'chk_date'=>$request->chk_date,'in_code'=>$request->in_code,'cp_id'=>$request->cp_id, 
                    'Ac_code'=>$request->Ac_code,'po_code'=>$request->po_code,'invoice_date'=>$request->invoice_date, 'invoice_no'=>$request->invoice_no,
                    'po_type_id'=>$request->po_type_id,  
                 'total_meter'=>$request->total_meter,
                    'total_taga_qty'=>$request->total_taga_qty,
                    'total_kg'=>$request->total_kg,
                    'in_narration'=>$request->in_narration,  'c_code' => $codefetch->c_code,
                    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','is_opening' =>$is_opening,
                    
                );

                FabricCheckingModel::insert($data1);

 DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='CHECKING'");


                $item_code = $request->input('item_code');
                if(count($item_code)>0)
                { 
                    
                for($x=0; $x<count($item_code); $x++) 
                {
                    # code...
                    
                                $data2=array(
                                'chk_code' =>$TrNo,
                                'chk_date' => $request->chk_date,
                                'cp_id' =>$request->cp_id,
                                'Ac_code' =>$request->Ac_code,
                                'po_code'=>$request->po_code,
                                'item_code' => $request->item_code[$x],
                                'part_id' =>$request->part_id[$x],
                                'roll_no' => $request->id[$x],
                                'old_meter' => $request->old_meter[$x],
                                'meter' => $request->meter[$x],
                                'width' => $request->width[$x],
                                'kg' => $request->kg[$x],
                                'shade_id' => $request->shade_id[$x],
                                'status_id' => $request->fcs_id[$x],
                                'defect_id' => $request->defect_id[$x],
                                'reject_short_meter' => $request->reject_short_meter[$x],
                                'track_code' => $request->track_code[$x],
                                'item_rate' => $request->item_rate[$x],
                                'rack_id'=>$request->rack_id[$x],
                                'usedflag' => '0',
                            
                                );
                         
                                
                                
                                $short_meter=$request->old_meter[$x]-$request->reject_short_meter[$x]-$request->meter[$x];
                                $data3=array(
                                   'tr_code' =>$TrNo,
                                    'tr_date' => $request->chk_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'job_code'=>0, 
                                    'po_code'=>$request->po_code,
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>0,
                                    'style_no' =>0,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>$request->shade_id[$x],
                                    'track_code' => $request->track_code[$x],
                                    
                                    'old_meter'=>$request->old_meter[$x],
                                    'short_meter'=>$short_meter,
                                    'rejected_meter'=>$request->reject_short_meter[$x],
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '2',
                                    'rack_id'=>$request->rack_id[$x],
                                    'userId'=>$request->userId,
                                );
                                
                           
                            //   DB::enableQueryLog();
                       FabricCheckingDetailModel::insert($data2);
                       FabricTransactionModel::insert($data3);
                    //   $query = DB::getQueryLog();
                    //       $query = end($query);
                    //       dd($query);
                    DB::select("update inward_details set usedflag=1 where track_code='".$request->track_code[$x]."'");
                }  
                        
                        
                
                }

               
                
                
        $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$TrNo)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
        
            //return redirect()->route('FabricChecking.index');
            
           
           return view('rptFabricChecking', compact('fabricChekingMaster'));   
                

    }





  public function FabricCheckPrint($chk_code)
    {
        
         
         $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$TrNo)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
         return view('rptFabricChecking', compact('fabricChekingMaster'));
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricCheckingModel $fabricCheckingModel)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
        $FabricCheckingMasterList = FabricCheckingModel::find($id);
        $POTypeList = POTypeModel::where('po_type_master.delflag','=', '0')->get();
        $BOMLIST = DB::table('bom_master')->select('bom_code')->get();
        $GRNList = FabricInwardModel::select('in_code')->where('inward_master.delflag','=', '0')->get(); 
        $CPList =  DB::table('cp_master')->get();
         $DefectList =  FabricDefectModel::where('fabric_defect_master.delflag', '=', '0')->get();
        $POList = PurchaseOrderModel::where('purchase_order.po_type_id','=', '1')->get();
        $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
        $FabricCheckingDetails = FabricCheckingDetailModel::
        where('fabric_checking_details.chk_code','=', $FabricCheckingMasterList->chk_code)->get(['fabric_checking_details.*']);
  
      
  return view('FabricCheckingMasterEdit',compact('FabricCheckingMasterList','GRNList','DefectList','RackList','POList','Ledger','CPList','FGList', 'ShadeList', 'PartList','ItemList',  'FabricCheckingDetails',
  'FabCheckList','POTypeList','BOMLIST','CPList'));
   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
       
$is_opening=isset($request->is_opening) ? 1 : 0;
$data1=array(
    
     'chk_code'=>$request->chk_code, 'chk_date'=>$request->chk_date,'in_code'=>$request->in_code,'cp_id'=>$request->cp_id, 
    'Ac_code'=>$request->Ac_code,'po_code'=>$request->po_code,'invoice_date'=>$request->invoice_date, 'invoice_no'=>$request->invoice_no,
    'po_type_id'=>$request->po_type_id, 'total_meter'=>$request->total_meter,
    'total_taga_qty'=>$request->total_taga_qty,'is_opening' =>$is_opening,
    'total_kg'=>$request->total_kg,'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','created_at'=>$request->created_at,
);
 
        $FabricCheckingMasterList = FabricCheckingModel::findOrFail($request->input('chk_code'));  
        $FabricCheckingMasterList->fill($data1)->save();
          
        DB::table('fabric_checking_details')->where('chk_code', $request->input('chk_code'))->delete();
        DB::table('fabric_transaction')->where('tr_code', $request->input('chk_code'))->delete();
        // DB::enableQueryLog();
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->first();  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      
         $item_code = $request->input('item_code');
         echo count($item_code);
                if(count($item_code)>0)
                { 
                    
                for($x=0; $x<count($item_code); $x++) 
                {
                         $data2=array(
                                'chk_code' =>$request->chk_code,
                                'chk_date' => $request->chk_date,
                                'cp_id' =>$request->cp_id,
                                'Ac_code' =>$request->Ac_code,
                                'po_code'=>$request->po_code,
                                'item_code' => $request->item_code[$x],
                                'part_id' =>$request->part_id[$x],
                                'roll_no' => $request->id[$x],
                                'old_meter' => $request->old_meter[$x],
                                'meter' => $request->meter[$x],
                                'width' => $request->width[$x],
                                'kg' => $request->kg[$x],
                                'shade_id' => $request->shade_id[$x],
                                'status_id' => $request->fcs_id[$x],
                                'defect_id' => $request->defect_id[$x],
                                'reject_short_meter' => $request->reject_short_meter[$x],
                                'track_code' => $request->track_code[$x],
                                'item_rate' => $request->item_rate[$x],
                                'rack_id'=>$request->rack_id[$x],
                                'usedflag' => '0',
                              );
                                
                                
                        
                                $short_meter=$request->old_meter[$x]-$request->reject_short_meter[$x]-$request->meter[$x];
                                
                                
                               $data3=array(
                                   'tr_code' =>$request->chk_code,
                                    'tr_date' => $request->chk_date,
                                    'Ac_code' =>$request->Ac_code,
                                    'cp_id' =>$request->cp_id,
                                    'job_code'=>0, 
                                    'po_code'=>$request->po_code,
                                    'invoice_no'=>0,
                                    'gp_no' =>0,
                                    'fg_id' =>0,
                                    'style_no' =>0,
                                    'item_code'=>$request->item_code[$x], 
                                    'part_id' =>$request->part_id[$x],
                                    'shade_id' =>$request->shade_id[$x],
                                    'track_code' => $request->track_code[$x],
                                    'old_meter'=>$request->old_meter[$x],
                                    'short_meter'=>$short_meter,
                                    'rejected_meter'=>$request->reject_short_meter[$x],
                                    'meter' => $request->meter[$x],
                                    'tr_type' => '2',
                                    'rack_id'=>$request->rack_id[$x],
                                    'userId'=>$request->userId,
                                );
                                
            
                    FabricCheckingDetailModel::insert($data2);
                    FabricTransactionModel::insert($data3);
                    DB::select("update inward_details set usedflag=1 where track_code='".$request->track_code[$x]."'");
                }
                  }
                  
                  $fabricChekingMaster = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
            ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
        ->where('fabric_checking_master.chk_code',$request->chk_code)
        ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
        
            //return redirect()->route('FabricChecking.index');
            
           
           return view('rptFabricChecking', compact('fabricChekingMaster'));
            
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $InCode =  FabricCheckingModel::select('in_code')->where('fabric_checking_master.chk_code','=', $id)->first();
        DB::select("update inward_details set usedflag=0 where in_code='".$InCode->in_code."'");
        
        DB::table('fabric_checking_master')->where('chk_code', $id)->delete();
        DB::table('fabric_checking_details')->where('chk_code', $id)->delete();
        $detail =FabricTransactionModel::where('tr_code',$id)->delete();
            
      Session::flash('delete', 'Deleted record successfully'); 
        
    }


    

    public function getMasterdata(Request $request)
    { 
        $in_code= $request->input('in_code');
        
        $MasterdataList = DB::select("SELECT `in_code`, `in_date`, `invoice_no`,`invoice_date`, cp_id,  `Ac_code`, `po_code`,  `po_type_id`,  
        `total_meter`, `total_kg`, `total_taga_qty`, `in_narration` from inward_master  where in_code='".$in_code."'");
        return json_encode($MasterdataList);
    
    }
 
 
    public function getDetails(Request $request)
    { 
    $in_code= $request->input('in_code');
    $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
    $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
    $FGList =  FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();
    $PartList =  PartModel::where('part_master.delflag', '=', '0')->get();
    $ShadeList =  ShadeModel::where('shade_master.delflag', '=', '0')->get();
    $FabCheckList =  FabricCheckStatusModel::where('fabric_check_status_master.delflag','=', '0')->get();
    $CPList =  DB::table('cp_master')->get();
    $DefectList =  FabricDefectModel::where('fabric_defect_master.delflag', '=', '0')->get();
    $RackList = RackModel::where('rack_master.delflag','=', '0')->get(); 
    $InwardFabric = DB::select("SELECT inward_master.`in_code`, inward_master.`in_date`,
    inward_details.`item_code`, inward_details.`roll_no`,  
    inward_details.`meter`,inward_details.part_id,inward_details.kg, inward_details.`track_code`,inward_details.`item_rate`, inward_details.`usedflag`, 
    inward_master.`total_meter`, inward_master.`total_taga_qty`
    FROM `inward_details` 
    inner join inward_master on inward_master.in_code=inward_details.in_code where inward_details.usedflag=0 and inward_master.in_code='". $in_code."'");
    $html ='';
    $html .= '<input type="number" value="'.count($InwardFabric).'" name="cntrr" id="cntrr" readonly="" hidden="true"  />';
    $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>Roll No</th>
<th>Item Name</th>
<th>Part</th>
<th>GRN Meter</th>
<th>QC Meter</th>
<th>Width</th>
<th>Shade</th>
<th>Status</th>
<th>Defect</th>
<th>Rejected/Short Meter</th>
<th>Extra Meter</th>
<th>TrackCode</th>
<th>Rack Location</th>
<th>Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
<td> <select name="item_code[]"  id="item_code" style="width:200px; height:30px;" required disabled>
<option value="">--Item--</option>';

foreach($ItemList as  $row1)
{
    $html.='<option value="'.$row1->item_code.'"';

    $row1->item_code == $row->item_code ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->item_name.'</option>';
}
 
$html.='</select></td> 

<td> <select name="part_id[]"  id="part_id" style="width:200px;height:30px;" required disabled>
<option value="">--Part--</option>';
foreach($PartList as  $rowfg)
{
    $html.='<option value="'.$rowfg->part_id.'"';

    $rowfg->part_id == $row->part_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowfg->part_name.'</option>';
}
 
$html.='</select></td>
<td><input type="hidden" class="TAGAQTY" onkeyup="mycalc();"   value="1"  id="taga_qty1" style="width:50px;height:30px;"/>
<input type="text" readOnly name="old_meter[]" onchange="mycalc();"   value="'.$row->meter.'" id="old_meter1" style="width:80px;height:30px;" required/></td>
<td><input type="text" class="METER" name="meter[]" onchange="mycalc();"   value="'.$row->meter.'" id="meter1" style="width:80px;height:30px;" required/></td>
<td><input type="text"  name="width[]"     value="0" id="width" style="width:80px;height:30px;" required/>
 <input type="hidden"   class="KG" name="kg[]" onkeyup="mycalc();" value="'.$row->kg.'" id="kg" style="width:80px;height:30px;" required/> 
</td>
<td> <select name="shade_id[]"  id="shade_id" class="select2" style="width:100px;height:30px;" required>
<option value="">--Shade--</option>';
foreach($ShadeList as  $rowfg)
{
    $html.='<option value="'.$rowfg->shade_id.'"';
    if($rowfg->shade_id==1){  $html.='selected="selected"';}
    $html.='>'.$rowfg->shade_name.'</option>';
}
 
$html.='</select></td> 

<td> <select name="fcs_id[]"  id="fcs_id" class="select2" style="width:100px;height:30px;" required>
<option value="">--Status--</option>';
foreach($FabCheckList as  $rowfg)
{
    $html.='<option value="'.$rowfg->fcs_id.'"';
     if($rowfg->fcs_id==1){  $html.='selected="selected"';}
    $html.='>'.$rowfg->fcs_name.'</option>';
}
$html.='</select></td> 
<td> <select name="defect_id[]"  id="defect_id" class="select2" style="width:100px;height:30px;" required>
<option value="0">--Defect--</option>';
foreach($DefectList as  $rowdef)
{
    $html.='<option value="'.$rowdef->fdef_id.'"';
    
    $html.='>'.$rowdef->fabricdefect_name.'</option>';
}
$html.='</select></td>
<td><input type="text"  name="reject_short_meter[]" onchange="mycalc();"   value="0" id="old_meter1" style="width:80px;height:30px;" required/></td>
<td><input type="text"  name="extra_meter[]" onchange="mycalc();"   value="0" id="extra_meter" style="width:80px;height:30px;" required/></td>
<td><input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;height:30px;" readOnly required/>
<input type="hidden" name="item_rate[]"  value="'.$row->item_rate.'" id="item_rate" style="width:80px;height:30px;" readOnly required/>
</td>

<td> <select name="rack_id[]"  id="rack_id" class="select2" style="width:100px;height:30px;" required>
<option value="">--Fabric Status--</option>';
 foreach($RackList as  $row)
{
    $html.='<option value="'.$row->rack_id.'"';
    $html.='>'.$row->rack_name.'</option>';
}
$html.='</select></td> 

<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;
    }
    
    $html .='</tbody>
    </table>';

    if(count($InwardFabric)!=0)
    {
          return response()->json(['html' => $html]);
    }
  
     
    }



}



 