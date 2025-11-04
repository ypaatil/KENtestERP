<?php

namespace App\Http\Controllers;

use App\Models\DepartmentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session; 
 
class DepartmentController extends Controller
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
        ->where('form_id', '25')
        ->first();
        
        $DeptList = DepartmentModel::join('usermaster', 'usermaster.userId', '=', 'department_master.userId')
        ->where('department_master.delflag','=', '0')
        ->get(['department_master.*','usermaster.username']);
  
        return view('DepartmentMasterList', compact('DeptList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('DepartmentMaster');
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
             
            'dept_name'=> 'required',
            'details'=> 'required',
           
              
    ]);

    $input = $request->all();

    DepartmentModel::create($input);

    return redirect()->route('Department.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function show(DepartmentModel $departmentModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $DeptList = DepartmentModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('DepartmentMaster', compact('DeptList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         
        $DeptList = DepartmentModel::findOrFail($id);

        $this->validate($request, [
            'dept_name'=> 'required',
            'details'=> 'required',
        ]);
        ;
        $input =$request->all();  
         
        $DeptList->fill($input)->save();

        return redirect()->route('Department.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepartmentModel  $departmentModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DepartmentModel::where('dept_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
