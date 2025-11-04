<?php

namespace App\Http\Controllers;

use App\Models\FabricCheckingModel;
use App\Models\FabricCheckingDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\ShadeModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\CounterNumberModel;
use Illuminate\Support\Facades\DB;


class FabricCheckingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          //   DB::enableQueryLog();
          $FabricCheckingList = FabricCheckingModel::join('usermaster', 'usermaster.userId', '=', 'fabric_checking_master.userId')
          ->join('ledger_master', 'ledger_master.Ac_code', '=', 'fabric_checking_master.Ac_code')
          ->join('cp_master', 'cp_master.cp_id', '=', 'fabric_checking_master.cp_id')
          ->where('fabric_checking_master.delflag','=', '0')
          ->get(['fabric_checking_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
      // $query = DB::getQueryLog();
      //     $query = end($query);
      //     dd($query);
          return view('FabricCheckingMasterList', compact('FabricCheckingList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='CHECKING'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        return view('FabricCheckingMaster',compact('Ledger','FGList','CPList','ColorList','ShadeList','counter_number','QualityList', 'PartList'));

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
             
            'chk_code'=>'required',
            'chk_date'=>'required',
            'cp_id'=>'required',
            'Ac_code'=>'required',
            'job_code'=>'required',
            'gp_no'=>'required',
            'fg_id'=>'required',
            'style_no'=>'required',
            'total_meter'=>'required',
            'total_taga_qty'=>'required',
            'in_narration'=>'required',
            'c_code'=>'required',
             ]);


                $data1=array(

                    'chk_code'=>$request->chk_code, 'chk_date'=>$request->chk_date, 'cp_id'=>$request->cp_id, 
                    'Ac_code'=>$request->Ac_code, 'job_code'=>$request->job_code,   'gp_no'=>$request->gp_no,
                    'fg_id'=>$request->fg_id,  'style_no'=>$request->style_no,
                    'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
                    'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
                    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1',
                    
                    
                );

                FabricCheckingModel::insert($data1);

                $color_id = $request->input('color_id');
                if(count($color_id)>0)
                { 
                    
                for($x=0; $x<count($color_id); $x++) 
                {
                    # code...
                    
                                $data2=array(
                                'chk_code' =>$request->chk_code,
                                'chk_date' => $request->chk_date,
                                'gp_no' =>$request->gp_no,
                                'cp_id' =>$request->cp_id,
                                'job_code'=>$request->job_code,
                                'Ac_code' =>$request->Ac_code,
                                'style_no' => $request->style_no,
                                'fg_id' =>$request->fg_id,
                                'quality_code' => $request->quality_code[$x],
                                'part_id' =>$request->part_id[$x],
                                'roll_no' => $request->id[$x],
                                'color_id' => $request->color_id[$x],
                                'width' => $request->width[$x],
                                'old_meter' => $request->old_meter[$x],
                                'meter' => $request->meter[$x],
                                'shade_id' => $request->shade_id[$x],
                                'track_code' => $request->track_code[$x],
                                'usedflag' => '0',
                            
                                );

                            //   DB::enableQueryLog();
                        FabricCheckingDetailModel::insert($data2);
                    //   $query = DB::getQueryLog();
                    //       $query = end($query);
                    //       dd($query);
                }  
                        
                        DB::select("update counter_number set tr_no=tr_no + 1 where c_name ='C1' AND type='CHECKING'");
                
                }

                return redirect()->route('FabricChecking.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricCheckingModel $fabricCheckingModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $FabricCheckingMasterList = FabricCheckingModel::find($id);
     
        $FabricCheckingDetails = FabricCheckingDetailModel::join('fg_master','fg_master.fg_id', '=', 'fabric_checking_details.fg_id')
        ->join('color_master','color_master.color_id', '=', 'fabric_checking_details.color_id')
       
        ->where('fabric_checking_details.chk_code','=', $FabricCheckingMasterList->chk_code)->get(['fabric_checking_details.*']);
  
      
  return view('FabricCheckingMasterEdit',compact('FabricCheckingMasterList','Ledger','CPList','FGList','ColorList','ShadeList', 'PartList', 'QualityList','FabricCheckingDetails'));
   
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
       
        $this->validate($request, [
             
            'chk_code'=>'required',
            'chk_date'=>'required',
            'cp_id'=>'required',
            'Ac_code'=>'required',
            'job_code'=>'required',
            'gp_no'=>'required',
            'fg_id'=>'required',
            'style_no'=>'required',
            'total_meter'=>'required',
            'total_taga_qty'=>'required',
            'in_narration'=>'required',
            'c_code'=>'required',
             ]);


$data1=array(

    'chk_code'=>$request->chk_code, 'chk_date'=>$request->chk_date, 'cp_id'=>$request->cp_id, 
    'Ac_code'=>$request->Ac_code, 'job_code'=>$request->job_code,   'gp_no'=>$request->gp_no,
    'fg_id'=>$request->fg_id,  'style_no'=>$request->style_no,
    'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
     'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','created_at'=>$request->created_at,
     
);
 
        $FabricCheckingMasterList = FabricCheckingModel::findOrFail($request->input('chk_code'));  
        $FabricCheckingMasterList->fill($data1)->save();
          
        DB::table('fabric_checking_details')->where('chk_code', $request->input('chk_code'))->delete();

        // DB::enableQueryLog();
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->first();  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
      
        $color_id = $request->input('color_id');
      
        if(count($color_id)>0)
        {
                for($x=0; $x<count($color_id); $x++) 
                {

                   
                    
                        $data2=array(
                            'chk_code' =>$request->chk_code,
                            'chk_date' => $request->chk_date,
                            'gp_no' =>$request->gp_no,
                            'cp_id' =>$request->cp_id,
                            'job_code'=>$request->job_code,
                            'Ac_code' =>$request->Ac_code,
                            'style_no' => $request->style_no,
                            'fg_id' =>$request->fg_id,
                            'quality_code' => $request->quality_code[$x],
                            'part_id' =>$request->part_id[$x],
                            'roll_no' => $request->id[$x],
                            'color_id' => $request->color_id[$x],
                            'width' => $request->width[$x],
                            'old_meter' => $request->old_meter[$x],
                            'meter' => $request->meter[$x],
                            'shade_id' => $request->shade_id[$x],
                            'track_code' => $request->track_code[$x],
                            'usedflag' => '0',
                    
                        );
                    
            
                    FabricCheckingDetailModel::insert($data2);
                }
                  }
            return redirect()->route('FabricChecking.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricCheckingModel  $fabricCheckingModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('fabric_checking_master')->where('chk_code', $id)->delete();
        DB::table('fabric_checking_details')->where('chk_code', $id)->delete();
        return redirect()->route('FabricChecking.index')->with('message', 'Delete Record Succesfully');
        
    }


    

    public function getMasterdata(Request $request)
    { 
        $job_code= $request->input('job_code');
        $MasterdataList = DB::select("select cp_id ,gp_no, Ac_code, fg_id, style_no, total_taga_qty,total_meter from inward_master where job_code='".$job_code."'");
        return json_encode($MasterdataList);
    
    }
 
 
    public function getDetails(Request $request)
    { 
        $job_code= $request->input('job_code');
     
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  FinishedGoodModel::where('fg_master.delflag', '=', '0')->get();
        $PartList =  PartModel::where('part_master.delflag', '=', '0')->get();
        $ShadeList =  ShadeModel::where('shade_master.delflag', '=', '0')->get();
        $CPList =  DB::table('cp_master')->get();
    
    $InwardFabric = DB::select("SELECT inward_master.`in_code`, inward_master.`in_date`, inward_master.`gp_no`,inward_details.`part_id`,
     inward_master.`cp_id`, inward_master.`Ac_code`, inward_details.`style_no`, inward_details.`fg_id`, inward_details.shade_id,
     inward_details.`quality_code`, inward_details.`roll_no`, inward_details.`color_id`, inward_details.`width`, 
     inward_details.`meter`, inward_details.`track_code`, inward_details.`usedflag`, inward_master.`job_code`, 
     inward_master.`total_meter`, inward_master.`total_taga_qty`, inward_master.`in_narration` 
    FROM `inward_details` 
    inner join inward_master on inward_master.in_code=inward_details.in_code where inward_master.job_code='". $job_code."'");
    
     $html = '';
   
    
    $html .= '<table id="footable_2" class="table  table-bordered table-striped m-b-0  footable_2">
<thead>
<tr>
<th>Roll No</th>
<th>Color</th>
<th>Part</th>
<th>Quality</th>
<th>Width</th>
<th>Old Meter</th>
<th>Meter</th>
<th> Shade </th>
<th>TrackCode</th>
<th>Remove</th>
</tr>
</thead>
<tbody>';
$no=1;
foreach ($InwardFabric as $row) {
    $html .='<tr>';
    
$html .='
<td><input type="text" name="id[]" value="'.$no.'" id="id" style="width:50px;"/></td>
 
<td> <select name="color_id[]"  id="color_id" style="width:100px;" required>
<option value="">--Select Color--</option>';

foreach($ColorList as  $row1)
{
    $html.='<option value="'.$row1->color_id.'"';

    $row1->color_id == $row->color_id ? $html.='selected="selected"' : ''; 


    $html.='>'.$row1->color_name.'</option>';
}
 
$html.='</select></td> 

<td> <select name="part_id[]"  id="part_id" style="width:100px;" required>
<option value="">--Part--</option>';
foreach($PartList as  $rowfg)
{
    $html.='<option value="'.$rowfg->part_id.'"';

    $rowfg->part_id == $row->part_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowfg->part_name.'</option>';
}
 
$html.='</select></td>';

$html.=' <td><select name="quality_code[]"  id="quality_code" style="width:100px;" required>
<option value="">--Item--</option>';
foreach($QualityList as  $rowitem)
{
    $html.='<option value="'.$rowitem->quality_code.'"';

    $rowitem->quality_code == $row->quality_code ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowitem->quality_name.'</option>';
}
 
$html.='</select></td>';
  
 
$html.='<td>
<input type="text" name="width[]" id="width1" value="'.$row->width.'" style="width:80px;" required/>
<input type="hidden" class="TAGAQTY" onkeyup="mycalc();"   value="1"  id="taga_qty1" style="width:50px;"/>
</td>
<td><input type="text"  name="old_meter[]" onkeyup="mycalc();"   value="'.$row->meter.'" id="old_meter1" style="width:80px;" required/></td>
<td><input type="text" class="METER" name="meter[]" onkeyup="mycalc();"   value="'.$row->meter.'" id="meter1" style="width:80px;" required/></td>

<td> <select name="shade_id[]"  id="shade_id" style="width:100px;" required>
<option value="">--Shade--</option>';
foreach($ShadeList as  $rowfg)
{
    $html.='<option value="'.$rowfg->shade_id.'"';

    $rowfg->shade_id == $row->shade_id ? $html.='selected="selected"' : ''; 
    
    $html.='>'.$rowfg->shade_name.'</option>';
}
 
$html.='</select></td> 

<td><input type="text" name="track_code[]"  value="'.$row->track_code.'" id="track_code" style="width:80px;"  /></td>
<td><input type="button" class="btn btn-danger pull-left" onclick="deleteRowcone(this);" value="X" ></td>
';
  
    $html .='</tr>';
    $no=$no+1;
    }
    
    return response()->json(['html' => $html]);
     
    }



}



 