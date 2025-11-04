<?php

namespace App\Http\Controllers;

use App\Models\MachineTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineTypeController extends Controller
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
->where('form_id', '84')
->first();  
        
        $MachineTypeList = MachineTypeModel::join('usermaster', 'usermaster.userId', '=', 'machine_type_master.userId')
        ->where('machine_type_master.delflag','=', '0')
        ->get(['machine_type_master.*','usermaster.username']);
  
        return view('MachineTypeMasterList', compact('MachineTypeList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('MachineTypeMaster');
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
             
             
            'machinetype_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    MachineTypeModel::create($input);

    return redirect()->route('MachineType.index')->with('message', 'New Record Saved Succesfully');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MachineTypeModel  $MachineTypeModel
     * @return \Illuminate\Http\Response
     */
    public function show(MachineTypeModel $MachineTypeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MachineTypeModel  $MachineTypeModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $MachineTypeList = MachineTypeModel::find($id);
         
        return view('MachineTypeMaster', compact('MachineTypeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MachineTypeModel  $MachineTypeModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $MachineTypeList = MachineTypeModel::findOrFail($id);

        $this->validate($request, [
            
            'machinetype_name'=> 'required',
           
        ]);

        $input = $request->all();

        $MachineTypeList->fill($input)->save();

        return redirect()->route('MachineType.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MachineTypeModel  $MachineTypeModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MachineTypeModel::where('machinetype_id', $id)->update(array('delflag' => 1));
     Session::flash('delete', 'Deleted record successfully'); 
    }
}
