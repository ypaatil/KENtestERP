<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ActivityMasterModel;
use Illuminate\Support\Facades\DB;
use Session;

class ActivityMasterController extends Controller
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
        ->where('form_id', '150')
        ->first();
         // DB::enableQueryLog();
        $activityMasterList = ActivityMasterModel::join('usermaster', 'usermaster.userId', '=', 'activity_master.userId')
        ->leftjoin('activity_type_master', 'activity_type_master.act_type_id','=','activity_master.act_type_id')
        ->leftjoin('department_master', 'department_master.dept_id','=','activity_master.dept_id')
        ->get(['activity_master.*','usermaster.username','activity_type_master.act_type_name', 'department_master.dept_name']);
        // dd(DB::getQueryLog());

        return view('ActivityMasterList',compact('activityMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $DeptList=DB::table('department_master')->where('department_master.delflag','=',0)->get();
         $act_type_list = DB::table('activity_type_master')->select('act_type_name','act_type_id')->where('delflag','=',0)->get();
        return view('ActivityMaster', compact('act_type_list','DeptList'));    
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
        'act_name' => 'required',
        'act_type_id' => 'required',
        'dept_id' => 'required',
        ]);
        $input = $request->all();
        ActivityMasterModel::create($input);
        return redirect()->route('ActivityMaster.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $DeptList=DB::table('department_master')->where('department_master.delflag','=',0)->get();
        $ActivityMasterEdit= ActivityMasterModel::find($id);
        // dd(DB::getQueryLog());
        // DB::enableQueryLog();
        $act_type_list = DB::table('activity_type_master')->select('act_type_name','act_type_id')->where('delflag','=',0)->get();
        $actTypeMasterList= DB::table('activity_master')->select('activity_master.*','act_type_name')
        ->leftJoin('activity_type_master', 'activity_type_master.act_type_id','=','activity_master.act_type_id')
        ->where('activity_master.act_type_id','=', $id)->get();
        // dd(DB::getQueryLog());
        return view('ActivityMaster', compact('ActivityMasterEdit', 'actTypeMasterList','act_type_list','DeptList'));    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $act_id = ActivityMasterModel::findOrFail($id);

        $this->validate($request, [
            'act_name' => 'required',
            'act_type_id' => 'required',
              'dept_id' => 'required',
        ]);
         
        $input = $request->all();
        $act_id->fill($input)->save();
       

       return redirect()->route('ActivityMaster.index')->with('message', 'Update Record Succesfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('activity_master')->where('act_id', $id)->delete();       
        Session::flash('delete', 'Deleted record successfully');   
    }
}
