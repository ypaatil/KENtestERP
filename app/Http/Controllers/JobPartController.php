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
                        ->where('job_part_master.delflag','=', '0')
                        ->get(['job_part_master.*','usermaster.username']);
        
        
  
        return view('JobPartMasterList', compact('JobPartList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('JobPartMaster');
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
          "jpart_name"=> $request->jpart_name,
          "jpart_description"=> $request->jpart_description,
          "userId"=> $request->userId,
       );

      JobPartModel::create($data1);
     
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
        $JobPartList = JobPartModel::find($id);
        
        return view('jobPartEdit', compact('JobPartList'));
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
          "jpart_name"=> $request->jpart_name,
          "jpart_description"=> $request->jpart_description,
          "userId"=> $request->userId,
        );
        
        $JobPartList = JobPartModel::findOrFail($id);
        $JobPartList->fill($data1)->save();
         
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
        
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
