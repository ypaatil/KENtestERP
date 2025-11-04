<?php

namespace App\Http\Controllers;

use App\Models\FabricInwardModel;
use App\Models\FabricInwardDetailModel;
use Illuminate\Http\Request;
use App\Models\LedgerModel;
use App\Models\FinishedGoodModel;
use App\Models\ColorModel;
use App\Models\QualityModel;
use App\Models\PartModel;
use App\Models\CounterNumberModel;
use Image;
use Illuminate\Support\Facades\DB;
 
class FabricInwardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         //   DB::enableQueryLog();
         $FabricInwardList = FabricInwardModel::join('usermaster', 'usermaster.userId', '=', 'inward_master.userId')
         ->join('ledger_master', 'ledger_master.Ac_code', '=', 'inward_master.Ac_code')
         ->join('cp_master', 'cp_master.cp_id', '=', 'inward_master.cp_id')
         ->where('inward_master.delflag','=', '0')
         ->get(['inward_master.*','usermaster.username','ledger_master.Ac_name','cp_master.cp_name']);
     // $query = DB::getQueryLog();
     //     $query = end($query);
     //     dd($query);
         return view('FabricInwardMasterList', compact('FabricInwardList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $counter_number = DB::select("select c_code, tr_no + 1 as 'tr_no' from counter_number where c_name ='C1' AND type='FABRIC_INWARD'");
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  DB::table('fg_master')->get();
        
        $CPList =  DB::table('cp_master')->get();
        return view('FabricInwardMaster',compact('Ledger','QualityList', 'PartList','FGList','CPList','ColorList','counter_number' ));

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
             
                'in_code'=>'required',
                'in_date'=>'required',
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

        'in_code'=>$request->in_code, 'in_date'=>$request->in_date, 'cp_id'=>$request->cp_id, 
        'Ac_code'=>$request->Ac_code, 'job_code'=>$request->job_code,   'gp_no'=>$request->gp_no,
        'fg_id' =>$request->fg_id,   'style_no' => $request->style_no,  
        'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
         'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
        'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1',
        
        
    );
    
    FabricInwardModel::insert($data1);
 
    $color_id = $request->input('color_id');
    if(count($color_id)>0)
    {
       
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->first();  
        $PBarcodes = $track_code->PBarcode;
        $CBarcodes = $track_code->CBarcode;
    for($x=0; $x<count($color_id); $x++) {
        # code...
        if($request->cp_id==1)
        {
                
                    $data2=array(
                    'in_code' =>$request->in_code,
                    'in_date' => $request->in_date,
                    'gp_no' =>$request->gp_no,
                    'cp_id' =>$request->cp_id,
                    'job_code'=>$request->job_code, 
                    'Ac_code' =>$request->Ac_code,
                    'style_no' => $request->style_no,
                    'fg_id' =>$request->fg_id,
                    'part_id' =>$request->part_id[$x],
                    'quality_code' => $request->quality_code[$x],
                    'roll_no' => $request->id[$x],
                    'color_id' => $request->color_id[$x],
                    'width' => $request->width[$x],
                    'meter' => $request->meter[$x],
                    'shade_id' =>'1',
                    'track_code' => 'P'.++$PBarcodes,
                    'usedflag' => '0',
                   
                    );
            }
            else
            {

               
                $data2=array(
                
                    'in_code' =>$request->in_code,
                    'in_date' => $request->in_date,
                    'gp_no' =>$request->gp_no,
                    'cp_id' =>$request->cp_id,
                    'job_code'=>$request->job_code, 
                    'Ac_code' =>$request->Ac_code,
                    'style_no' => $request->style_no,
                    'fg_id' =>$request->fg_id,
                    'part_id' =>$request->part_id[$x],
                    'quality_code' => $request->quality_code[$x],
                    'roll_no' => $request->id[$x],
                    'color_id' => $request->color_id[$x],
                    'width' => $request->width[$x],
                    'meter' => $request->meter[$x],
                    'shade_id' =>'1',
                    'track_code' => 'I'.++$CBarcodes,
                    'usedflag' => '0',
                    );
            }
            
            
             FabricInwardDetailModel::insert($data2);
         
            }
    
      //DB::enableQueryLog(); 
               DB::select("update counter_number set tr_no=tr_no + 1, PBarcode='".$PBarcodes."', CBarcode='".$CBarcodes."'   where c_name ='C1' AND type='FABRIC_INWARD'");
      //  $query = DB::getQueryLog();
            //  $query = end($query);
            //  dd($query);
    
    }
 
   return redirect()->route('FabricInward.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricInwardModel $fabricInwardModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $ColorList = ColorModel::where('color_master.delflag','=', '0')->get();
        
        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->get();
        $FGList =  DB::table('fg_master')->get();
        $CPList =  DB::table('cp_master')->get();
        $PartList = PartModel::where('part_master.delflag','=', '0')->get();
        $QualityList = QualityModel::where('quality_master.delflag','=', '0')->get();

        $FabricInwardMasterList = FabricInwardModel::find($id);
        // DB::enableQueryLog();
        $FabricInwardDetails = FabricInwardDetailModel::join('fg_master','fg_master.fg_id', '=', 'inward_details.fg_id')
        ->join('color_master','color_master.color_id', '=', 'inward_details.color_id')
        
        ->where('inward_details.in_code','=', $FabricInwardMasterList->in_code)->get(['inward_details.*','fg_master.fg_name','color_master.color_name']);
  
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
  return view('FabricInwardMasterEdit',compact('FabricInwardMasterList', 'PartList', 'QualityList',   'Ledger','CPList','FGList','ColorList',  'FabricInwardDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        

        $this->validate($request, [
             
            'in_code'=>'required',
            'in_date'=>'required',
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

    'in_code'=>$request->in_code, 'in_date'=>$request->in_date, 'cp_id'=>$request->cp_id, 
    'Ac_code'=>$request->Ac_code, 'job_code'=>$request->job_code,   'gp_no'=>$request->gp_no,
    'fg_id' =>$request->fg_id,   'style_no' => $request->style_no,  
    'total_meter'=>$request->total_meter,  'total_taga_qty'=>$request->total_taga_qty,
     'in_narration'=>$request->in_narration,  'c_code' => $request->c_code,
    'userId'=>$request->userId, 'delflag'=>'0', 'CounterId'=>'1','created_at'=>$request->created_at,
    
    
);

print_r($data1);
// DB::enableQueryLog();

        $FabricInwardMasterList = FabricInwardModel::findOrFail($request->input('in_code'));  
   
        $FabricInwardMasterList->fill($data1)->save();
        //  $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
 

        DB::table('inward_details')->where('in_code', $request->input('in_code'))->delete();

       
        $track_code = CounterNumberModel::where('c_code','=',$request->c_code )->first();  
       
        $CBarcodes = $track_code->CBarcode;
        $PBarcodes = $track_code->PBarcode;

        $color_id = $request->input('color_id');
        

        if(count($color_id)>0)
        {
                for($x=0; $x<count($color_id); $x++) 
                {

                   
                    if($request->cp_id==1)
                    {

                        if($request->track_code[$x]==''){ $PBarcodeFinal='P'.++$PBarcodes; }else{$PBarcodeFinal=$request->track_code[$x];}
                        $data2=array(
                        'in_code' =>$request->in_code,
                        'in_date' => $request->in_date,
                        'gp_no' =>$request->gp_no,
                        'cp_id' =>$request->cp_id,
                        'job_code'=>$request->job_code, 
                        'Ac_code' =>$request->Ac_code,
                        'style_no' => $request->style_no,
                        'fg_id' =>$request->fg_id,
                        'part_id' =>$request->part_id[$x],
                    'quality_code' => $request->quality_code[$x],
                        'roll_no' => $request->id[$x],
                        'color_id' => $request->color_id[$x],
                        'width' => $request->width[$x],
                        'meter' => $request->meter[$x],
                        'shade_id' =>'1',
                        'track_code' => $PBarcodeFinal,
                        'usedflag' => '0',
                    
                        );
                    }
                    else
                    {
                        if($request->track_code[$x]==''){ $CBarcodeFinal='I'.++$CBarcodes; }else{$CBarcodeFinal=$request->track_code[$x];}
                        $data2=array(
                        
                            'in_code' =>$request->in_code,
                            'in_date' => $request->in_date,
                            'gp_no' =>$request->gp_no,
                            'cp_id' =>$request->cp_id,
                            'job_code'=>$request->job_code, 
                            'Ac_code' =>$request->Ac_code,
                            'style_no' => $request->style_no,
                            'fg_id' =>$request->fg_id,
                            'part_id' =>$request->part_id[$x],
                            'quality_code' => $request->quality_code[$x],
                            'roll_no' => $request->id[$x],
                            'color_id' => $request->color_id[$x],
                            'width' => $request->width[$x],
                            'meter' => $request->meter[$x],
                            'shade_id' =>'1',
                            'track_code' => $CBarcodeFinal,
                            'usedflag' => '0',
                            );
                     }
            
                    FabricInwardDetailModel::insert($data2);
                }
                DB::select("update counter_number set PBarcode='".$PBarcodes."', CBarcode='".$CBarcodes."'   where c_name ='C1' AND type='FABRIC_INWARD'");
        }
            return redirect()->route('FabricInward.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricInwardModel  $fabricInwardModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('inward_master')->where('in_code', $id)->delete();
        DB::table('inward_details')->where('in_code', $id)->delete();
        return redirect()->route('FabricInward.index')->with('message', 'Delete Record Succesfully');
        
    }
}
