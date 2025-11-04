<?php

namespace App\Http\Controllers;

use App\Models\MachineMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $MachineMaintData = DB::table('machine_main_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->where('delflag','=',0)->get();

                $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '292')
        ->first();

        $MachineMasters = MachineMasterModel::join('usermaster', 'usermaster.userId', '=', 'machine_master.userId')
        ->join('machine_main_type_master', 'machine_main_type_master.machine_Id', '=', 'machine_master.machine_Id')
        ->join('machine_make_master', 'machine_make_master.mc_make_Id', '=', 'machine_master.mc_make_Id')
        ->join('machine_type_master', 'machine_type_master.machinetype_id', '=', 'machine_master.machinetype_id')
        ->where('machine_master.delflag','=', '0')
        ->get(['machine_master.*','usermaster.username','machine_main_type_master.machine_name','machine_make_master.machine_make_name','machine_type_master.machinetype_name']);

        return view('MachineMasterList', compact('MachineMasters','chekform','MachineMaintData','MachineMakeData','MachineTypeData'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $MachineMaintData = DB::table('machine_main_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->where('delflag','=',0)->get();

        return view('MachineMaster',compact('MachineMaintData','MachineMakeData','MachineTypeData'));
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
            'machine_Id' => 'required',
            'MachineName' => 'required',
            'mc_make_Id' => 'required',
            'ModelNumber' => 'required',
            'machinetype_id' => 'required',
            'McDescription' => 'required',
            'MachineSrNo' => 'required',
        ]);

        $input = $request->all();

        MachineMasterModel::create($input);

        $fileName = "";
        if($file = $request->hasFile('MachinePhoto')) {
        $file = $request->file('MachinePhoto') ;
        $fileName = $file->getClientOriginalName() ;
        $destinationPath = public_path().'/uploads/machinePhoto/';  
        $file->move($destinationPath,$fileName); 
        }
        $MachineID =  DB::table('machine_master')->max('MachineID');
        $t_image = MachineMasterModel::find($MachineID);
        $t_image->MachinePhoto = $fileName;
        $t_image->save();

        return redirect()->route('MacMaster.index')->with('message', 'New Record Saved Succesfully');



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
    public function edit($MachineID)
    {
        $MachineMaintData = DB::table('machine_main_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->where('delflag','=',0)->get();

        $machinemaster = MachineMasterModel::find($MachineID);
        
        return view('MachineMaster', compact('machinemaster','MachineMaintData','MachineMakeData','MachineTypeData'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $machinemaster = MachineMasterModel::findOrFail($id);

        $this->validate($request, [
            'machine_Id' => 'required',
            'MachineName' => 'required',
            'mc_make_Id' => 'required',
            'ModelNumber' => 'required',
            'machinetype_id' => 'required',
            'McDescription' => 'required',
            'MachineSrNo' => 'required',
        ]);

        $input = $request->all();

        $machinemaster->fill($input)->save();

        if($file = $request->hasFile('MachinePhoto')) {
        $file = $request->file('MachinePhoto') ;
        $fileName = $file->getClientOriginalName() ;
        $destinationPath = public_path().'/uploads/machinePhoto/';  
        $file->move($destinationPath,$fileName);  
        $t_image = MachineMasterModel::find($id);
        $t_image->MachinePhoto = $fileName;
        $t_image->save();
        }
        else{
        unset($input['MachinePhoto']);
        }
        return redirect()->route('MacMaster.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($MachineID)
    {
      
      MachineMasterModel::where('MachineID', $MachineID)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
