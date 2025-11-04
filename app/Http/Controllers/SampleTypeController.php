<?php

namespace App\Http\Controllers;

use App\Models\DepartmentTypeModel;
use App\Models\SampleTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SampleTypeController extends Controller
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
        ->where('form_id', '2')
        ->first();


        $data = SampleTypeModel::join('department_type', 'department_type.dept_type_id', '=', 'sample_type_master.dept_type_id')
        ->join('usermaster', 'usermaster.userId', '=', 'sample_type_master.userId')
        ->where('sample_type_master.delflag','=', '0')
        ->get(['sample_type_master.*','usermaster.username','department_type.dept_type_name']);

        return view('SampleTypeMasterList', compact('data','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        return view('SampleTypeMaster',compact('DepartmentTypelist'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $input = $request->all();

        SampleTypeModel::create($input);

        return redirect()->route('SampleType.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SampleType  $SampleType
     * @return \Illuminate\Http\Response
     */
    public function show(SampleType $SampleType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SampleType  $SampleType
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SampleType = SampleTypeModel::find($id);
        $DepartmentTypelist = DB::table('department_type')->where('delflag','=', '0')->get();
        return view('SampleTypeMaster', compact('SampleType', 'DepartmentTypelist'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SampleType  $SampleType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $SampleType = SampleTypeModel::findOrFail($id);
        $input = $request->all();
        $SampleType->fill($input)->save();

        return redirect()->route('SampleType.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SampleType  $SampleType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
        SampleTypeModel::where('sample_type_id', $id)->update(array('delflag' => 1)); 
        return redirect()->route('SampleType.index')->with('message', 'Deleted Record Succesfully');
    }
}
