<?php

namespace App\Http\Controllers;

use App\Models\JobPartModel;
use Illuminate\Http\Request;
use App\Models\FinishedGoodModel;
use DB;
use Session;

class JobPartController extends Controller
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
->where('form_id', '49')
->first();

        
        $JobPartList = JobPartModel::join('usermaster', 'usermaster.userId', '=', 'job_part_master.userId')
        ->join('fg_master', 'fg_master.fg_id', '=', 'job_part_master.fg_id')
        ->where('job_part_master.delflag','=', '0')
        ->get(['job_part_master.*','usermaster.username','fg_master.fg_name']);
        
        
  
        return view('JobPartMasterList', compact('JobPartList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
         $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        return view('JobPartMaster', compact('FGList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


   
   $data1=array(
       "fg_id"=> $request->fg_id,
       "userId"=> $request->userId,
       
       );

    JobPartModel::create($data1);
    
    
    $cnt=$request->cnt;
    
   $jpart_id= JobPartModel::max('jpart_id');
    
  
  for($x=0;$x<$cnt;$x++)  
    {
        
    $data2[]=array(
      "jpart_id"=> $jpart_id,   
     "jpart_name"=> $request->jpart_name[$x],
      "jpart_description"=> $request->jpart_description[$x],
      "delflag"=> 0,
       );  
    }
    
    
    DB::table('job_part_detail')->insert($data2);
    

    return redirect()->route('JobPart.index');
    
    
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobPartModel  $jobPartModel
     * @return \Illuminate\Http\Response
     */
    public function show(JobPartModel $jobPartModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobPartModel  $jobPartModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
          $FGList = FinishedGoodModel::where('fg_master.delflag','=', '0')->get();
        $JobPartList = JobPartModel::find($id);
        
        // select * from business_type where Bt_id=$id;
        
     $detailparts = DB::table('job_part_detail')->where('jpart_id',$JobPartList->jpart_id)->get(['job_part_detail.*']);    
        
        
        return view('jobPartEdit', compact('JobPartList','FGList','detailparts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobPartModel  $jobPartModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
           $data1=array(
       "fg_id"=> $request->fg_id,
       "userId"=> $request->userId,
       
       );
        
        $JobPartList = JobPartModel::findOrFail($id);
        $JobPartList->fill($data1)->save();
        
        
           DB::table('job_part_detail')->where('jpart_id', $request->input('jpart_id'))->delete();
           
           
        
         for($x=0;$x<count($request->jpart_name);$x++)  
    {
        
    $data2[]=array(
      "jpart_id"=> $request->jpart_id,   
     "jpart_name"=> $request->jpart_name[$x],
      "jpart_description"=> $request->jpart_description[$x],
      "delflag"=> 0,
       );  
    }
    
    
    DB::table('job_part_detail')->insert($data2);
        
        

        return redirect()->route('JobPart.index')->with('message', 'Updated Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobPartModel  $jobPartModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JobPartModel::where('jpart_id', $id)->update(array('delflag' => 1));
          DB::table('job_part_detail')->where('jpart_id', $id)->update(array('delflag' => 1));
        
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
