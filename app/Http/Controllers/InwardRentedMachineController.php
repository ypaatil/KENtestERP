<?php

namespace App\Http\Controllers;

use App\Models\InwardRentedMachineModel;
use App\Models\InwardRentedMachineDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class InwardRentedMachineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $RentedData = DB::table('inward_type_master')->Select('*')->get();
        $VendorData = DB::table('ledger_master')->Select('ac_code','ac_name')->where('bt_id',[4])->where('delflag','=',0)->get();
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineNameData = DB::table('machine_master')->Select('*')->where('delflag','=',0)->get();
        $MachineLocData = DB::table('machine_location_master')->Select('*')->where('delflag','=',0)->get();




                $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '1')
        ->first();

        $InwardRentedMachines = InwardRentedMachineModel::join('usermaster', 'usermaster.userId', '=', 'inward_rented_machine_master.userId')
        ->join('inward_type_master', 'inward_type_master.inwardtypeId', '=', 'inward_rented_machine_master.inwardtypeId')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'inward_rented_machine_master.ac_code')
        ->where('inward_rented_machine_master.delflag','=', '0')
        ->get(['inward_rented_machine_master.*','usermaster.username','inward_type_master.inwardtypeName','ledger_master.ac_name']);

        return view('InwardRentedMachineList', compact('InwardRentedMachines','chekform','RentedData','VendorData','MachineMakeData','MachineTypeData','MachineNameData','MachineLocData'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $RentedData = DB::table('inward_type_master')->Select('*')->get();
        $VendorData = DB::table('ledger_master')->Select('ac_code','ac_name')->where('bt_id',[4])->where('delflag','=',0)->get();
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineNameData = DB::table('machine_master')->Select('*')->where('delflag','=',0)->get();
        $MachineLocData = DB::table('machine_location_master')->Select('*')->where('delflag','=',0)->get();
        // DB::enableQueryLog();
        $DetailList = DB::table('inward_rented_machine_detail')->select(DB::raw('IFNULL(MAX(machineCode), 1000)+1 as machineCode'))->first();
        // dd(DB::getQueryLog());
        return view('InwardRentedMachineMaster',compact('RentedData','VendorData','MachineMakeData','MachineTypeData','MachineNameData','MachineLocData','DetailList'));
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
            'pureDate' => 'required',
            'inwardtypeId' => 'required',
            'ac_code' => 'required',
            'rentedDate' => 'required',
            'totalAmount' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        InwardRentedMachineModel::create($input);

        $fileName = "";
        if($file = $request->hasFile('machineimage')) {
        $file = $request->file('machineimage') ;
        $fileName = $file->getClientOriginalName() ;
        $destinationPath = public_path().'/uploads/machineImage/';  
        $file->move($destinationPath,$fileName); 
        }
        $purId =  DB::table('inward_rented_machine_master')->max('purId');
        $t_image = InwardRentedMachineModel::find($purId);
        $t_image->machineimage = $fileName;
        $t_image->save();

        $purId = DB::table('inward_rented_machine_master')->max('purId'); 
        $purchaseRate = $request->purchaseRate;
        
        for($x=0; $x<count($purchaseRate); $x++) 
        {
            $data1=array(
                'purId'=>$purId,
                'pureDate'=>$request->pureDate, 
                'inwardtypeId'=>$request->inwardtypeId, 
                'ac_code'=>$request->ac_code,
                'rentedDate'=>$request->rentedDate,  
                'totalAmount'=>$request->totalAmount,   
                'MachineID'=> $request->MachineID[$x],  
                'machineCode'=>$request->machineCode[$x],
                'mc_make_Id'=>$request->mc_make_Id[$x],
                'machinetype_id'=>$request->machinetype_id[$x],
                'purchaseRate'=>$request->purchaseRate[$x],
                'Qty'=>$request->Qty[$x],
                'amount'=>$request->amount[$x],
                'mc_loc_Id'=>$request->mc_loc_Id[$x]
             );
            
            InwardRentedMachineDetailModel::insert($data1);
            }
            
        return redirect()->route('InwardRentedMachine.index')->with('message', 'New Record Saved Succesfully');



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
    public function edit($purId)
    {
        $RentedData = DB::table('inward_type_master')->Select('*')->get();
        $VendorData = DB::table('ledger_master')->Select('ac_code','ac_name')->where('bt_id',[4])->where('delflag','=',0)->get();
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineNameData = DB::table('machine_master')->Select('*')->where('delflag','=',0)->get();

        $MachineLocData = DB::table('machine_location_master')->Select('*')->where('delflag','=',0)->get();
        // DB::enableQueryLog();
        $DetailList = DB::table('inward_rented_machine_detail')->where('purId',$purId)->select('*')->get();
        // dd(DB::getQueryLog());
        $lastCode = DB::table('inward_rented_machine_detail')->select(DB::raw('IFNULL(MAX(machineCode), 1000)+1 as machineCode'))->first();
        $inwardrented = InwardRentedMachineModel::find($purId);
        
        return view('InwardRentedMachineMaster', compact('inwardrented','RentedData','VendorData','MachineMakeData','MachineTypeData','MachineNameData','MachineLocData','DetailList','lastCode'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $inwardrented = InwardRentedMachineModel::findOrFail($id);

        $this->validate($request, [
            'pureDate' => 'required',
            'inwardtypeId' => 'required',
            'ac_code' => 'required',
            'rentedDate' => 'required',
            'totalAmount' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        $inwardrented->fill($input)->save();

        if($file = $request->hasFile('machineimage')) {
        $file = $request->file('machineimage') ;
        $fileName = $file->getClientOriginalName() ;
        $destinationPath = public_path().'/uploads/machineImage/';  
        $file->move($destinationPath,$fileName);  
        $t_image = InwardRentedMachineModel::find($id);
        $t_image->machineimage = $fileName;
        $t_image->save();
        }
        else{
        unset($input['machineimage']);
        }


        //  $purId = DB::table('inward_rented_machine_master')->max('purId'); 
        $purchaseRate = $request->purchaseRate;
        
        DB::table('inward_rented_machine_detail')->where('purId', $id)->delete();
  
        if($purchaseRate != "")
        {
        for($x=0; $x<count($purchaseRate); $x++) 
        {
            $data1=array(
                'purId'=>$id,
                'pureDate'=>$request->pureDate, 
                'inwardtypeId'=>$request->inwardtypeId, 
                'ac_code'=>$request->ac_code,
                'rentedDate'=>$request->rentedDate,  
                'totalAmount'=>$request->totalAmount,   
                'MachineID'=> $request->MachineID[$x],  
                'machineCode'=>$request->machineCode[$x],
                'mc_make_Id'=>$request->mc_make_Id[$x],
                'machinetype_id'=>$request->machinetype_id[$x],
                'purchaseRate'=>$request->purchaseRate[$x],
                'Qty'=>$request->Qty[$x],
                'amount'=>$request->amount[$x],
                'mc_loc_Id'=>$request->mc_loc_Id[$x]
             );
            //  DB::table('master_training_planning_detail')->insert($data2);
            InwardRentedMachineDetailModel::insert($data1);
         
        }
    }

        return redirect()->route('InwardRentedMachine.index')->with('message', 'Update Record Succesfully');


    }
    public function maintance_dashboard(Request $request)
    {
        // DB::enableQueryLog();
        $MachineLocData = DB::table('machine_transfer_master')->select('*')->where('delflag','=',0)->get();
        // dd(DB::getQueryLog());
        $MachineMakeData = DB::table('inward_rented_machine_detail')
        ->select('machine_make_master.machine_make_name')
        ->leftJoin('machine_make_master','machine_make_master.mc_make_Id','=','inward_rented_machine_detail.mc_make_Id')
        ->groupBy('machine_make_master.machine_make_name')
        ->get();
        $MachineMainTypeData = DB::table('machine_main_type_master')->Select('*')->where('delflag','=',0)->get();
        $MachineTypeData = DB::table('inward_rented_machine_detail')
        ->select('machine_type_master.machinetype_name')
        ->leftJoin('machine_type_master','machine_type_master.machinetype_id','=','inward_rented_machine_detail.machinetype_id')
        ->groupBy('machine_type_master.machinetype_name')
        ->get();
        $RentedData = DB::table('inward_type_master')->Select('*')->get();

        $mc_loc_Id = $request->mc_loc_Id;
        $mc_make_Id = $request->mc_make_Id;
        $machine_Id = $request->machine_Id;
        $machinetype_id = $request->machinetype_id;
        $inwardtypeId = $request->inwardtypeId;

            if($mc_loc_Id > 0 && $mc_loc_Id != "")
            {
                 $mloc  = " AND machine_location_master.mc_loc_Id=".$mc_loc_Id;
            }
            else
            {
                 $mloc  = "";

            }

            if($mc_make_Id > 0 && $mc_make_Id != "")
            {
                 $mmake  = " AND machine_make_master.mc_make_Id=".$mc_make_Id;
            }
            else
            {
                 $mmake  = "";

            }

            if($machine_Id > 0 && $machine_Id != "")
            {
                 $mmach  = " AND machine_main_type_master.machine_Id=".$machine_Id;
            }
            else
            {
                 $mmach  = "";

            }

            if($machinetype_id > 0 && $machinetype_id != "")
            {
                 $mmachtype  = " AND machine_type_master.machinetype_id=".$machinetype_id;
            }
            else
            {
                 $mmachtype  = "";

            }

            if($inwardtypeId > 0 && $inwardtypeId != "")
            {
                 $intype  = " AND inward_type_master.inwardtypeId=".$inwardtypeId;
            }
            else
            {
                 $intype  = "";

            }



     return view('MaintainceDashboard',compact('MachineLocData','MachineMakeData','MachineMainTypeData','MachineTypeData','RentedData','mloc','mmake','mmach','mmachtype'));
    
   
}

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($purId)
    {
      
        InwardRentedMachineModel::where('purId', $purId)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}