<?php

namespace App\Http\Controllers;

use App\Models\EmployeeGroupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class EmployeeGroupController extends Controller
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
        ->where('form_id', '27')
        ->first();
        
        //   DB::enableQueryLog(); 
        $EmpGroupList = EmployeeGroupModel::join('usermaster', 'usermaster.userId', '=', 'emp_groupmaster.userId')
        ->where('emp_groupmaster.delflag','=', '0')
        ->get(['emp_groupmaster.*','usermaster.username']);
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
        return view('EmployeeGroupMasterList', compact('EmpGroupList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('EmployeeGroupMaster');
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
             
            'egroup_name'=> 'required',
            
            
              
    ]);

    $input = $request->all();

    EmployeeGroupModel::create($input);

    return redirect()->route('EmployeeGroup.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeGroupModel  $employeeGroupModel
     * @return \Illuminate\Http\Response
     */
    public function show(EmployeeGroupModel $employeeGroupModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeGroupModel  $employeeGroupModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $EmpGroupList = EmployeeGroupModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('EmployeeGroupMaster', compact('EmpGroupList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeGroupModel  $employeeGroupModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $EmpGroupList = EmployeeGroupModel::findOrFail($id);

        $this->validate($request, [
            'egroup_name'=> 'required',
             
        ]);

        $input = $request->all();

        $EmpGroupList->fill($input)->save();

        return redirect()->route('EmployeeGroup.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeGroupModel  $employeeGroupModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeGroupModel::where('egroup_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
