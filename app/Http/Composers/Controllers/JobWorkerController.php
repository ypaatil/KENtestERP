<?php

namespace App\Http\Controllers;

use App\Models\JobWorkerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session; 

class JobWorkerController extends Controller
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
        ->where('form_id', '24')
        ->first();
        
        
        // DB::enableQueryLog(); 
        $WorkerList = JobWorkerModel::join('usermaster', 'usermaster.userId', '=', 'job_worker_master.userId')
        ->join('emp_groupmaster', 'emp_groupmaster.egroup_id', '=', 'job_worker_master.egroup_id')
        ->join('salary_type_master', 'salary_type_master.salary_id', '=', 'job_worker_master.salary_id')
        ->join('payment_term', 'payment_term.ptm_id', '=', 'job_worker_master.ptm_id')
        ->join('department_master', 'department_master.dept_id', '=', 'job_worker_master.dept_id')
        ->where('job_worker_master.delflag','=', '0')
        ->get(['job_worker_master.*','usermaster.username','emp_groupmaster.egroup_name', 'salary_type_master.type','payment_term.ptm_name','department_master.dept_name'  ]);
        // $query = DB::getQueryLog();
        // $query = end($query);
        // dd($query);
        return view('JobWorkerMasterList', compact('WorkerList','chekform'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Account_Type = DB::table('account_type_master')->get();
        $DeptList= DB::table('department_master')->get();
        $EmpGroup = DB::table('emp_groupmaster')->get();
        $Salary_Type = DB::table('salary_type_master')->get();
        $PayTerm = DB::table('payment_term')->get();
        return view('JobWorkerMaster',compact('Account_Type','EmpGroup','Salary_Type','PayTerm','DeptList'));
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
             
            'w_name'=> 'required',
            'w_contact'=> 'required',
            'w_particular'=> 'required',
            'egroup_id'=> 'required',
            'salary_id'=> 'required',
            'basic_pay'=> 'required',
            'ptm_id'=> 'required',
            'day_count'=> 'required',
            'dept_id'=> 'required',
            'm_id'=> 'required',
            'bank_name'=> 'required',
            'account_name'=> 'required',
            'ac_id'=> 'required',
            'account_no'=> 'required',
            'ifsc_code'=> 'required',
            'm_id'=> 'required',
            'bank_name'=> 'required',
               ]);

    $input = $request->all();

    JobWorkerModel::create($input);

    return redirect()->route('JobWorker.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\JobWorkerModel  $jobWorkerModel
     * @return \Illuminate\Http\Response
     */
    public function show(JobWorkerModel $jobWorkerModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\JobWorkerModel  $jobWorkerModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Account_Type = DB::table('account_type_master')->get();
        $EmpGroup = DB::table('emp_groupmaster')->get();
        $DeptList= DB::table('department_master')->get();
        $Salary_Type = DB::table('salary_type_master')->get();
        $PayTerm = DB::table('payment_term')->get();
        $WorkerList = JobWorkerModel::find($id);
        // select * from job_worker_master where firm_id=$id;
        return view('JobWorkerMaster',compact('WorkerList','Account_Type','EmpGroup','Salary_Type','PayTerm','DeptList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\JobWorkerModel  $jobWorkerModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $WorkerList = JobWorkerModel::findOrFail($id);

        $this->validate($request, [
            'w_name'=> 'required',
            'w_contact'=> 'required',
            'w_particular'=> 'required',
            'egroup_id'=> 'required',
            'salary_id'=> 'required',
            'basic_pay'=> 'required',
            'ptm_id'=> 'required',
            'day_count'=> 'required',
            'dept_id'=> 'required',
            'm_id'=> 'required',
            'bank_name'=> 'required',
            'account_name'=> 'required',
            'ac_id'=> 'required',
            'account_no'=> 'required',
            'ifsc_code'=> 'required',
            'm_id'=> 'required',
            'bank_name'=> 'required',
        ]);

        $input = $request->all();

        $WorkerList->fill($input)->save();

        return redirect()->route('JobWorker.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\JobWorkerModel  $jobWorkerModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JobWorkerModel::where('w_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
