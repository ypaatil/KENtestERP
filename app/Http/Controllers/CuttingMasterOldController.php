<?php

namespace App\Http\Controllers;
 
use App\Models\CuttingMasterModel;
use Illuminate\Http\Request;
use App\Models\SizeModel;
use App\Models\LedgerModel;
use App\Models\ColorModel;
 use App\Models\TaskMasterModel;
use Illuminate\Support\Facades\DB;
use App\Models\CuttingBalanceDetailModel;
use App\Models\CuttingDetailModel;
class CuttingMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //   DB::enableQueryLog();
        $CuttingMasterList = CuttingMasterModel::join('usermaster', 'usermaster.userId', '=', 'cutting_master.userId')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'cutting_master.Ac_code')
        ->join('ctable_master', 'ctable_master.table_id', '=', 'cutting_master.table_id')
        ->where('cutting_master.delflag','=', '0')
        ->get(['cutting_master.*','usermaster.username','ledger_master.Ac_name','ctable_master.table_name']);
   
     // $query = DB::getQueryLog();
    //     $query = end($query);
    //     dd($query);
        return view('FabricCuttingMasterList', compact('CuttingMasterList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CUTTING'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
      //  $ItemList = ItemModel::where('item_master.delflag','=', '0')->get();
        $SizeList = SizeModel::where('size_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  DB::table('fg_master')->get();
        $TableList =  DB::table('ctable_master')->get();
        return view('CuttingMaster',compact('Ledger','FGList', 'ColorList','TableList','counter_number', 'SizeList'));

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
             
        'cu_code'=>'required',
        'cu_date'=>'required',
        'lot_no'=>'required',
        'job_code'=>'required', 
        'style_no'=>'required', 
        'Ac_code'=>'required', 
        'table_id'=>'required',
        'table_task_code'=>'required',
        'table_avg'=>'required',
        'total_pieces'=>'required',
        'total_layers'=>'required',
        'total_used_meter'=>'required',
        'total_cutpiece_meter'=>'required',
        'total_damage_meter'=>'required',
        'total_short_meter'=>'required',
        'total_extra_meter'=>'required',
        'narration'=>'required', 
        'userId'=>'required', 
        'c_code' =>'required',
  
]);
 
$data1=array(
       
    'cu_code'=>$request->cu_code,
    'cu_date'=>$request->cu_date,
    'lot_no'=>$request->lot_no,
    'job_code'=>$request->job_code, 
    'style_no'=>$request->style_no, 
    'Ac_code'=>$request->Ac_code,
    'table_id'=>$request->table_id,
    'table_task_code'=>$request->table_task_code,
    'table_avg'=>$request->table_avg,
    'total_pieces'=>$request->total_pieces,
    'total_layers'=>$request->total_layers,
    'total_used_meter'=>$request->total_used_meter,
    'total_cutpiece_meter'=>$request->total_cutpiece_meter,
    'total_damage_meter'=>$request->total_damage_meter,
    'total_short_meter'=>$request->total_short_meter,
    'total_extra_meter'=>$request->total_extra_meter,
    'narration'=>$request->narration, 
    'userId'=>$request->userId, 
    'c_code' =>$request->c_code,
    'delflag'=>'0',
    
    
);

CuttingMasterModel::insert($data1);


$color_id = $request->input('color_id');
$track_codes = $request->input('track_codes');
$track_codess = $request->input('track_codess');
if(count($track_codes)>0)
{

for($x=0; $x<count($track_codes); $x++) {
    # code...
   
$data2=array(
           
    'cu_code'=>$request->cu_code, 
    'cu_date'=>$request->cu_date,
    'lot_no'=>$request->lot_no,
    'job_code'=>$request->job_code ,
    'style_no'=>$request->style_no, 
    'Ac_code'=>$request->Ac_code,
    'table_id'=>$request->table_id, 
    'table_avg'=>$request->table_avg,
    'track_code'=>$request->track_codes[$x],
    'color_id'=>$request->color_id[$x], 
    'width'=>$request->width[$x], 
    'meter'=>$request->meter[$x],
    'sz_code'=>$request->sz_code[$x],
    'ratio'=>$request->ratio[$x],
    'layers'=>$request->layers[$x],
    'qty'=>$request->qty[$x],
      
         );
        
         CuttingDetailModel::insert($data2);
         
        }

       
        for($x=0; $x<count($track_codess); $x++) {
            # code...
           
        $data3=array(
                   
            'cu_code'=>$request->cu_code,
            'cu_date'=>$request->cu_date,
            'lot_no'=>$request->lot_no,
            'job_code'=>$request->job_code,
            'style_no'=>$request->style_no,
            'Ac_code'=>$request->Ac_code,
            'table_id'=>$request->table_id,
            'table_avg'=>$request->table_avg,
            'track_code'=>$request->track_codess[$x],
            'color_id'=>$request->color_ids[$x],
            'width'=>$request->widths[$x],
            'meter'=>$request->meters[$x]      ,
            'layers'=>$request->layerss[$x],
            'used_meter'=>$request->used_meters[$x],
            'balance_meter'=>$request->bpiece_meters[$x],
            'cpiece_meter'=>$request->cpiece_meters[$x],       
            'dpiece_meter'=>$request->dpiece_meters[$x],
            'short_meter'=>$request->short_meters[$x],       
            'extra_meter'=>$request->extra_meters[$x],
                 );
                


                 CuttingBalanceDetailModel::insert($data3);
                 


                }
    // DB::enableQueryLog(); 
         DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='CUTTING'");
        //  $query = DB::getQueryLog();
        //  $query = end($query);
        //  dd($query);
         DB::select("update ctable_master set tr_no=tr_no + 1 where table_id='$request->table_id'");
 
}

return redirect()->route('FabricCutting.index')->with('message', 'New Record Saved Succesfully..!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(CuttingMasterModel $cuttingMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $SizeList = SizeModel::where('size_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
       
        $TableList =  DB::table('ctable_master')->get();
        $CuttingMasterList = CuttingMasterModel::find($id);
    //   DB::enableQueryLog();
        $CuttingDetailList = CuttingDetailModel::where('cutting_details.cu_code','=', $CuttingMasterList->cu_code)->get(['cutting_details.*'  ]);
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
        $CuttingBalanceDetailList = CuttingBalanceDetailModel::where('cutting_balance_details.cu_code','=', $CuttingMasterList->cu_code)->get(['cutting_balance_details.*']);
        $TaskList =  TaskMasterModel::where('task_master.table_id','=', $CuttingMasterList->table_id)->get();
        
  return view('CuttingMasterEdit',compact('CuttingMasterList','Ledger','TaskList', 'ColorList','TableList', 'SizeList', 'CuttingDetailList','CuttingBalanceDetailList'));
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CuttingMasterModel $cuttingMasterModel)
    {
        $this->validate($request, [
             
            'cu_code'=>'required',
            'cu_date'=>'required',
            'lot_no'=>'required',
            'job_code'=>'required', 
            'style_no'=>'required', 
            'Ac_code'=>'required', 
            'table_id'=>'required',
            'table_task_code'=>'required',
            'table_avg'=>'required',
            'total_pieces'=>'required',
            'total_layers'=>'required',
            'total_used_meter'=>'required',
            'total_cutpiece_meter'=>'required',
            'total_damage_meter'=>'required',
            'total_short_meter'=>'required',
            'total_extra_meter'=>'required',
            'narration'=>'required', 
            'userId'=>'required', 
            'c_code' =>'required',
      
    ]);
     
    $data1=array(
           
        'cu_code'=>$request->cu_code,
        'cu_date'=>$request->cu_date,
        'lot_no'=>$request->lot_no,
        'job_code'=>$request->job_code, 
        'style_no'=>$request->style_no, 
        'Ac_code'=>$request->Ac_code,
        'table_id'=>$request->table_id,
        'table_task_code'=>$request->table_task_code,
        'table_avg'=>$request->table_avg,
        'total_pieces'=>$request->total_pieces,
        'total_layers'=>$request->total_layers,
        'total_used_meter'=>$request->total_used_meter,
        'total_cutpiece_meter'=>$request->total_cutpiece_meter,
        'total_damage_meter'=>$request->total_damage_meter,
        'total_short_meter'=>$request->total_short_meter,
        'total_extra_meter'=>$request->total_extra_meter,
        'narration'=>$request->narration, 
        'userId'=>$request->userId, 
        'c_code' =>$request->c_code,
        'delflag'=>'0',
        
        
    );
    
    $CuttingMasterList = CuttingMasterModel::findOrFail($request->input('cu_code'));  
    $CuttingMasterList->fill($data1)->save();
   
    DB::table('cutting_details')->where('cu_code', $request->input('cu_code'))->delete();
    DB::table('cutting_balance_details')->where('cu_code', $request->input('cu_code'))->delete();
    
    $color_id = $request->input('color_id');
    $track_codes = $request->input('track_codes');
    $track_codess = $request->input('track_codess');
    if(count($track_codes)>0)
    {
    
    for($x=0; $x<count($track_codes); $x++) {
        # code...
       
    $data2=array(
               
        'cu_code'=>$request->cu_code, 
        'cu_date'=>$request->cu_date,
        'lot_no'=>$request->lot_no,
        'job_code'=>$request->job_code ,
        'style_no'=>$request->style_no, 
        'Ac_code'=>$request->Ac_code,
        'table_id'=>$request->table_id, 
        'table_avg'=>$request->table_avg,
        'track_code'=>$request->track_codes[$x],
        'color_id'=>$request->color_id[$x], 
        'width'=>$request->width[$x], 
        'meter'=>$request->meter[$x],
        'sz_code'=>$request->sz_code[$x],
        'ratio'=>$request->ratio[$x],
        'layers'=>$request->layers[$x],
        'qty'=>$request->qty[$x],
          
             );
            
             CuttingDetailModel::insert($data2);
             
            }
    
           
            for($x=0; $x<count($track_codess); $x++) {
                # code...
               
            $data3=array(
                       
                'cu_code'=>$request->cu_code,
                'cu_date'=>$request->cu_date,
                'lot_no'=>$request->lot_no,
                'job_code'=>$request->job_code,
                'style_no'=>$request->style_no,
                'Ac_code'=>$request->Ac_code,
                'table_id'=>$request->table_id,
                'table_avg'=>$request->table_avg,
                'track_code'=>$request->track_codess[$x],
                'color_id'=>$request->color_ids[$x],
                'width'=>$request->widths[$x],
                'meter'=>$request->meters[$x]      ,
                'layers'=>$request->layerss[$x],
                'used_meter'=>$request->used_meters[$x],
                'balance_meter'=>$request->bpiece_meters[$x],
                'cpiece_meter'=>$request->cpiece_meters[$x],       
                'dpiece_meter'=>$request->dpiece_meters[$x],
                'short_meter'=>$request->short_meters[$x],       
                'extra_meter'=>$request->extra_meters[$x],
                     );
                    
                     CuttingBalanceDetailModel::insert($data3);
                     
                    }
      
    }
    
    return redirect()->route('FabricCutting.index')->with('message', 'Record Updated Succesfully..!');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CuttingMasterModel  $cuttingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy(CuttingMasterModel $cuttingMasterModel)
    {
        //
    }
 
  public function GetTaskList(Request $request)
    { 
      //  $table_id= $request->input('table_id');
         
    if (!$request->table_id) {
        $html = '<option value="">--Task--</option>';
        } else {
       
        $html = '';
       $MasterdataList = TaskMasterModel::where('table_id', $request->table_id)->where('delflag', '0')->get();
           $html .= '<option value="">Task List</option>';
           foreach ($MasterdataList as $row) 
           {
               
                $html .= ' <option value="'.$row->task_id.'">'.$row->task_id.', Job:'.$row->job_code.', Layers:'.$row->layers.', Avg:'.$row->table_avg.'</option>';
              
            }
        }
        
        return response()->json(['html' => $html]);
     
    }
 
 
 
    public function getCheckingMasterdata(Request $request)
    { 
        $table_task_code= $request->input('table_task_code');
        $MasterdataList = DB::select("select Ac_code, task_master.table_id,concat('LOT-',tr_no+1) as 'lot_no', style_no,job_code, table_avg  from task_master inner join ctable_master on ctable_master.table_id=task_master.table_id where task_master.task_id='".$table_task_code."' and task_master.delflag=0");
        return json_encode($MasterdataList);
    
    }
 
    public function getCheckingFabricdata(Request $request)
    { 
        $track_code= $request->input('track_code');
        $table_avg= $request->input('table_avg');
        $MasterdataList = DB::select(" SELECT track_code, color_id, width, meter, ROUND((meter/". $table_avg.")) as Layers from fabric_checking_details where track_code='".$track_code."'");
        return json_encode($MasterdataList);
    
    }
 
    public function getRatioDetails(Request $request)
    { 
        $table_avg= $request->input('table_avg');
        $track_code= $request->input('track_code');
        $job_code= $request->input('job_code');
        $layers= $request->input('layers');
        $task_id= $request->input('task_id');
        $table_task_code= $request->input('table_task_code');
        $table_id=$request->input('table_id');
        $SizeList = SizeModel::where('size_master.delflag','=', '0')->get();
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $Roll = DB::table('fabric_checking_details')->select('track_code', 'color_id', 'width', 'meter')->where('track_code',$track_code)->first();
     
        if($layers==0)
        {
            $meter= $request->input('meter');
            $layers= intval($Roll->meter/$table_avg);
        }
        else{

            $meter=floatval($table_avg*$layers);
          
        }
 
    $CuttingRatio = DB::select(" SELECT `task_id`, `task_date`, `Ac_code`, `job_code`, `table_id`, `style_no`,
     `table_avg`, `sz_code`, `ratio` FROM `task_details`
      where table_id='". $table_id."'   and task_id='".$table_task_code."'");
    
     $html = '';
    
$no=1;
foreach ($CuttingRatio as $row) {
    $html .='<tr class="thisRow">';
   
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
$html.='<td>
<input type="text" name="track_codes[]" class="track_code" id="track_code'.$no.'" value="'.$track_code.'" style="width:80px;" required/> </td> 
 
<td> <select name="color_id[]"  id="color_id'.$no.'" style="width:100px;" required>
<option value="">--Color--</option>';
foreach($ColorList as  $rowcolor)
{
    $html.='<option value="'.$rowcolor->color_id.'"';

    $rowcolor->color_id == $Roll->color_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowcolor->color_name.'</option>';
}
$html.='</select></td>';

$html.='<td>
<input type="text" name="width[]" id="width'.$no.'" value="'.$Roll->width.'" style="width:80px;" required/> </td>';
$html.='<td>
<input type="text" name="meter[]" id="meter'.$no.'" value="'.$Roll->meter.'" style="width:80px;" required/> </td>';
 
$html.='<td>
  <select name="sz_code[]"  id="sz_code'.$no.'" style="width:100px;" required>
<option value="">--Size--</option>';
foreach($SizeList as  $rowfg)
{
    $html.='<option value="'.$rowfg->sz_code.'"';

    $rowfg->sz_code == $row->sz_code ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowfg->sz_name.'</option>';
}
$html.='</select></td>';
$html.='<td>
<input type="text" name="ratio[]" id="ratio'.$no.'" value="'.$row->ratio.'" style="width:80px;" required/> </td>';

$html.='<td>
<input type="text" name="layers[]" id="layers'.$no.'" value="'.$layers.'" style="width:80px;" required/> </td>';

$html.='<td><input type="text" class="QTY" onkeyup="mycalc();"  name="qty[]" id="qty'.$no.'" value="'.intval(($meter/$row->table_avg)*$row->ratio).'" style="width:80px;" required/></td>
<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;
    }
    
    return response()->json(['html' => $html]);
     
    }





    public function getEndDataDetails(Request $request)
    { 
        $table_avg= $request->input('table_avg');
        $track_code= $request->input('track_code');
        $job_code= $request->input('job_code');
        $layers= $request->input('layers');
        
        
        $table_id=$request->input('table_id');
        $SizeList = SizeModel::where('size_master.delflag','=', '0')->get();
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $Roll = DB::table('fabric_checking_details')->select('track_code', 'color_id', 'width', 'meter')->where('track_code',$track_code)->first();
     
        if($layers==0)
        {
            $meter= $request->input('meter');
            $layers= intval($Roll->meter/$table_avg);
        }
        else{

            $meter=floatval($table_avg*$layers);
          
        }
 
     
    
     $html = '';
    
$no=1;
 
    $html .='<tr class="thisRow">';
   
$html .='
<td><input type="text" name="ids[]" value="'.$no.'" id="id" style="width:50px;"/></td>';
$html.='<td>
<input type="text" name="track_codess[]" class="track_code" id="track_codes'.$no.'" value="'.$track_code.'" style="width:80px;" required/> </td> 
 
<td> <select name="color_ids[]"  id="color_ids'.$no.'" style="width:100px;" required>
<option value="">--Color--</option>';
foreach($ColorList as  $rowcolor)
{
    $html.='<option value="'.$rowcolor->color_id.'"';

    $rowcolor->color_id == $Roll->color_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowcolor->color_name.'</option>';
}
$html.='</select></td>';

$html.='<td>
<input type="text" name="widths[]" id="widths'.$no.'" value="'.$Roll->width.'" style="width:80px;" required/> </td>';
$html.='<td>
<input type="text" name="meters[]" id="meters'.$no.'" value="'.$Roll->meter.'" style="width:80px;" required/> </td> ';
$html.='<td>
<input type="text" name="layerss[]" class="Layers" id="layerss'.$no.'" value="'.$layers.'" style="width:80px;" required/> </td>
<td> 
<input type="text" name="used_meters[]" class="UMETER" id="used_meters'.$no.'" value="'.($table_avg*$layers).'" style="width:80px;" required/> </td> ';
if(($Roll->meter -($table_avg*$layers))>$table_avg)
{
    $html.='
    <td><input type="text" onkeyup="mycalc();" name="bpiece_meters[]" id="bpiece_meters'.$no.'" value="'.($Roll->meter -($table_avg*$layers)).'" style="width:80px;" required/> </td>
    <td><input type="text" name="cpiece_meters[]" onkeyup="mycalc();" class="cPiece" id="cpiece_meters'.$no.'" value="0" style="width:80px;" required/> </td>';

}
else
{

    $html.='
    <td><input type="text" name="bpiece_meters[]"  id="bpiece_meters'.$no.'"  value="0" style="width:80px;" required/> </td>
    <td><input type="text" name="cpiece_meters[]" onkeyup="mycalc();"  class="cPiece" id="cpiece_meters'.$no.'"  value="'.($Roll->meter-($table_avg*$layers)).'" style="width:80px;" required/> </td>';

}

 
    $html.=' <td> 
    <input type="text" name="dpiece_meters[]" onkeyup="mycalc();"  class="dPiece" id="dpiece_meters'.$no.'" value="0" style="width:80px;" required/> </td> 
    <td><input type="text" name="short_meters[]"  id="short_meters'.$no.'" class="SPiece"  value="0" style="width:80px;" required/> </td>
    <td><input type="text" name="extra_meters[]" onkeyup="mycalc();"  class="EPiece" id="extra_meters'.$no.'"  value="0" style="width:80px;" required/> </td> 
   <td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
    ';
  
    $html .='</tr>';
    $no=$no+1;
   
    
    return response()->json(['html' => $html]);
     
    }




}
