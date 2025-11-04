<?php

namespace App\Http\Controllers;

use App\Models\JobStatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session; 

class JobStatusController extends Controller
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
        ->where('form_id', '34')
        ->first();
        
        
        $JobStatusList = JobStatusModel::join('usermaster', 'usermaster.userId', '=', 'job_status_master.userId')
        ->where('job_status_master.delflag','=', '0')
        ->get(['job_status_master.*','usermaster.username']);
  
        return view('JobStatusMasterList', compact('JobStatusList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('JobStatusMaster');
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
             
            'job_status_name'=> 'required',
            
           
              
    ]);

    $input = $request->all();

    JobStatusModel::create($input);

    return redirect()->route('JobStatus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobStatusModel  $jobStatusModel
     * @return \Illuminate\Http\Response
     */
    public function show(JobStatusModel $jobStatusModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobStatusModel  $jobStatusModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $JobStatusList = JobStatusModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('JobStatusMaster', compact('JobStatusList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobStatusModel  $jobStatusModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $JobStatusList = JobStatusModel::findOrFail($id);

        $this->validate($request, [
            'job_status_name'=> 'required',
            
        ]);

        $input = $request->all();

        $JobStatusList->fill($input)->save();

        return redirect()->route('JobStatus.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobStatusModel  $jobStatusModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JobStatusModel::where('job_status_id', $id)->update(array('delflag' => 1));
     Session::flash('delete', 'Deleted record successfully'); 
    }
}
