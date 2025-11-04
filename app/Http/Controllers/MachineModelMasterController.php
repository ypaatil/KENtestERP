<?php

namespace App\Http\Controllers;

use App\Models\MachineModelMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineModelMasterController extends Controller
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
        ->where('form_id', '327')
        ->first();




        $MachineModels = MachineModelMasterModel::join('usermaster', 'usermaster.userId', '=', 'machine_model_master.userId')
        ->where('machine_model_master.delflag','=', '0')
        ->get(['machine_model_master.*','usermaster.username']);

        return view('MachineModelMasterList', compact('MachineModels','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('MachineModelMaster');
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
            'mc_model_name' => 'required',
        ]);

        $input = $request->all();

        MachineModelMasterModel::create($input);

        return redirect()->route('MachineModel.index')->with('message', 'New Record Saved Succesfully');



    }

    /**
     * Display the specified resource.
     *

     * @return \Illuminate\Http\Response
     */
  

    /**
     * Show the form for editing the specified resource.
     *

     * @return \Illuminate\Http\Response
     */
    public function edit($mc_model_id)
    {
        //
        //DB::enableQueryLog();
        $machineModel = MachineModelMasterModel::find($mc_model_id);
        //dd(DB::getQueryLog());
        return view('MachineModelMaster', compact('machineModel'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $MachineModel = MachineModelMasterModel::findOrFail($id);

        $this->validate($request, [
            'mc_model_name' => 'required',
        ]);

        $input = $request->all();

        $MachineModel->fill($input)->save();

        return redirect()->route('MachineModel.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($mc_model_id)
    {
      
       MachineModelMasterModel::where('mc_model_id', $mc_model_id)->update(array('delflag' => 1));
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
