<?php

namespace App\Http\Controllers;

use App\Models\MachineryMaintanceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineryMaintanceController extends Controller
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

        $MachineryMaintances = MachineryMaintanceModel::join('usermaster', 'usermaster.userId', '=', 'machinery_maintance.userId')
        ->join('machine_master', 'machine_master.MachineID', '=', 'machinery_maintance.MachineID')
        ->join('machine_location_master', 'machine_location_master.mc_loc_Id', '=', 'machinery_maintance.mc_loc_Id')
        ->join('rented_master', 'rented_master.rentedId', '=', 'machinery_maintance.rentedId')
        ->where('machinery_maintance.delflag','=', '0')
        ->get(['machinery_maintance.*','usermaster.username','machine_master.MachineName','machine_location_master.machine_location_name','rented_master.rentedName']);

        return view('MachineryMaintanceList', compact('MachineryMaintances','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $RentedData = DB::table('rented_master')->Select('*')->get();
        $MachineLocData = DB::table('machine_location_master')->Select('*')->where('delflag','=',0)->get();
        $MachineNameData = DB::table('machine_master')->Select('*')->where('delflag','=',0)->get();
        $MachineCodeData = DB::table('inward_rented_machine_detail')->Select('*')->get();
        
        return view('MachineryMaintanceMaster',compact('RentedData','MachineLocData','MachineNameData','MachineCodeData'));
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
            'date' => 'required',
            'mc_loc_Id' => 'required',
            'machineCode' => 'required',
            'MachineID' => 'required',
            'purpose' => 'required',
            'proAddress' => 'required',
            'rentedId' => 'required',
            'totalDownTime' => 'required',
            'remark' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        MachineryMaintanceModel::create($input);
            
        return redirect()->route('MachineryMaintance.index')->with('message', 'New Record Saved Succesfully');

    }
    public function getmachinerycode(Request $request)
    {
        
       $machineCode= $request->machineCode;
 
      $datafetch=DB::table('inward_rented_machine_detail')->select('MachineID', 'machineCode', 'mc_loc_Id')->where('machineCode',$machineCode)->first();

      
         return response()->json($datafetch);

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
    public function edit($mcmaintainceId)
    {
       
        $RentedData = DB::table('rented_master')->Select('*')->get();
        $MachineLocData = DB::table('machine_location_master')->Select('*')->where('delflag','=',0)->get();
        $MachineNameData = DB::table('machine_master')->Select('*')->where('delflag','=',0)->get();
       // $MachineCodeData = DB::table('inward_rented_machine_detail')->Select('*')->get();
        // $MachineCodeData = DB::table('inward_rented_machine_detail')->select('machineCode')->where('purId')->get();

      
           
        //   echo ($MachineCodeData);exit;
        $machinerymaintance = MachineryMaintanceModel::find($mcmaintainceId);

        // $MachineCodeData = DB::select(
        //     "SELECT inward_rented_machine_detail.machineCode, inward_rented_machine_detail.purId
        //     FROM inward_rented_machine_detail  
        //     leftjoin machinery_maintance on machinery_maintance.MachineID =  inward_rented_machine_detail.MachineID
        //     WHERE  machinery_maintance.mcmaintainceId = '".$mcmaintainceId."' "
        //   );
        // new 28-11-2024 
        $MachineCodeData = DB::selectOne("
        SELECT irmd.machineCode, irmd.purId
        FROM inward_rented_machine_detail irmd
        LEFT JOIN machinery_maintance mm ON mm.mc_loc_Id = irmd.mc_loc_Id
        WHERE mm.mcmaintainceId = ?
        LIMIT 1
    ", [$mcmaintainceId]);
    
// Debug the data to ensure correctness
// dd($machinerymaintance->machineCode, $MachineCodeData);

        // echo ($MachineCodeData);exit;
        // $machinerymaintance = MachineryMaintanceModel::find($mcmaintainceId);
        
        return view('MachineryMaintanceMaster', compact('machinerymaintance','RentedData','MachineLocData','MachineNameData','MachineCodeData'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $machinerymaintance = MachineryMaintanceModel::findOrFail($id);

        $this->validate($request, [
            'date' => 'required',
            'mc_loc_Id' => 'required',
            'machineCode' => 'required',
            'MachineID' => 'required',
            'purpose' => 'required',
            'proAddress' => 'required',
            'rentedId' => 'required',
            'totalDownTime' => 'required',
            'remark' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        $machinerymaintance->fill($input)->save();
        
        return redirect()->route('MachineryMaintance.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($mcmaintainceId)
    {
      
        MachineryMaintanceModel::where('mcmaintainceId', $mcmaintainceId)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}