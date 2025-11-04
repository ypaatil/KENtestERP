<?php

namespace App\Http\Controllers;

use App\Models\MachineryPreventiveModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineryPreventiveController extends Controller
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

        // $MachineryPreventives = MachineryPreventiveModel::join('usermaster', 'usermaster.userId', '=', 'machinery_preventive_maintance.userId')
        // ->where('machinery_preventive_maintance.delflag','=', '0')
        // ->get(['machinery_preventive_maintance.*','usermaster.username']);
        // new 28-11-2024
        $MachineryPreventives = MachineryPreventiveModel::join('usermaster', 'usermaster.userId', '=', 'machinery_preventive_maintance.userId')
        ->join('preventive_name_master', 'preventive_name_master.preventive_Id', '=', 'machinery_preventive_maintance.preId') 
        ->where('machinery_preventive_maintance.delflag', '=', '0')
        ->get([
            'machinery_preventive_maintance.*', 
            'usermaster.username', 
            'preventive_name_master.preventive_name'  
        ]);

        return view('MachineryPreventiveList', compact('MachineryPreventives','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $MachineCodeData = DB::table('inward_rented_machine_detail')->Select('*')->get();

        // new 28-11-2024
        $PreventiveNameData = DB::table('preventive_name_master')->Select('preventive_name')->where('delflag','=',0)->get();
        
        return view('MachineryPreventiveMaster',compact('MachineCodeData','PreventiveNameData'));
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
            'preventName_ID' => 'required',
            'purId' => 'required',
            'preDate' => 'required',
            'preDuration' => 'required',
            'status' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        MachineryPreventiveModel::create($input);
            
        return redirect()->route('MachineryPreventive.index')->with('message', 'New Record Saved Succesfully');

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
    public function edit($preId)
    {
        $MachineCodeData = DB::table('inward_rented_machine_detail')->Select('*')->get();
        $machinerypreventive = MachineryPreventiveModel::find($preId);
        // $PreventiveNameData = DB::table('preventive_name_master')->Select('preventive_name')->where('delflag','=',0)->get();
        

        $PreventiveNameData = DB::selectOne("
        SELECT preventive_name_master.preventive_name, preventive_name_master.preventive_Id 
        FROM preventive_name_master 
        LEFT JOIN machinery_preventive_maintance mm 
        ON mm.preId = preventive_name_master.preventive_Id 
        WHERE mm.preId = ? 
        LIMIT 1
    ", [$preId]);
    
    
        return view('MachineryPreventiveMaster', compact('machinerypreventive','MachineCodeData','PreventiveNameData'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $machinerypreventive = MachineryPreventiveModel::findOrFail($id);

        $this->validate($request, [
            'preventName_ID' => 'required',
            'purId' => 'required',
            'preDate' => 'required',
            'preDuration' => 'required',
            'status' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        $machinerypreventive->fill($input)->save();
        
        return redirect()->route('MachineryPreventive.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($preId)
    {
      
        MachineryPreventiveModel::where('preId', $preId)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}