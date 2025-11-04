<?php

namespace App\Http\Controllers;

use App\Models\MachineLocationMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineLocationMasterController extends Controller
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




        $MachineLocations = MachineLocationMasterModel::join('usermaster', 'usermaster.userId', '=', 'machine_location_master.userId')
        ->where('machine_location_master.delflag','=', '0')
        ->get(['machine_location_master.*','usermaster.username']);

        return view('MachineLocationMasterList', compact('MachineLocations','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('MachineLocationMaster');
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
            'machine_location_name' => 'required',
        ]);

        $input = $request->all();

        MachineLocationMasterModel::create($input);

        return redirect()->route('MachineLocation.index')->with('message', 'New Record Saved Succesfully');



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
    public function edit($mc_loc_Id)
    {
        //

        $machinelocation = MachineLocationMasterModel::find($mc_loc_Id);
        
        return view('MachineLocationMaster', compact('machinelocation'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $machinelocation = MachineLocationMasterModel::findOrFail($id);

        $this->validate($request, [
            'machine_location_name' => 'required',
        ]);

        $input = $request->all();

        $machinelocation->fill($input)->save();

        return redirect()->route('MachineLocation.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($mc_loc_Id)
    {
      
      MachineLocationMasterModel::where('mc_loc_Id', $mc_loc_Id)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
