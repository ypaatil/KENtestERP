<?php

namespace App\Http\Controllers;

use App\Models\MachineMainTypeMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineMainTypeMasterController extends Controller
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
        ->where('form_id', '1')
        ->first();




        $MachineMainTypes = MachineMainTypeMasterModel::join('usermaster', 'usermaster.userId', '=', 'machine_main_type_master.userId')
        ->where('machine_main_type_master.delflag','=', '0')
        ->get(['machine_main_type_master.*','usermaster.username']);

        return view('MachineMainTypeMasterList', compact('MachineMainTypes','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('MachineMainTypeMaster');
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
            'machine_name' => 'required',
        ]);

        $input = $request->all();

        MachineMainTypeMasterModel::create($input);

        return redirect()->route('MachineMainType.index')->with('message', 'New Record Saved Succesfully');



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
    public function edit($machine_Id)
    {
        //

        $machinemaintype = MachineMainTypeMasterModel::find($machine_Id);
        
        return view('MachineMainTypeMaster', compact('machinemaintype'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $machinemaintype = MachineMainTypeMasterModel::findOrFail($id);

        $this->validate($request, [
            'machine_name' => 'required',
        ]);

        $input = $request->all();

        $machinemaintype->fill($input)->save();

        return redirect()->route('MachineMainType.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($machine_Id)
    {
      
      MachineMainTypeMasterModel::where('machine_Id', $machine_Id)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
