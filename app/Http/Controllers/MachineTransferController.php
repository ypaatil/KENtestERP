<?php

namespace App\Http\Controllers;

use App\Models\MachineTransferModel;
use App\Models\MachineTransferDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineTransferController extends Controller
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

        $MachineTransfers = MachineTransferModel::join('usermaster', 'usermaster.userId', '=', 'machine_transfer_master.userId')
        ->where('machine_transfer_master.delflag','=', '0')
        ->get(['machine_transfer_master.*','usermaster.username']);

        return view('MachineTransferList', compact('MachineTransfers','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $MachineMakeData = DB::table('machine_make_master')->Select('*')->get();
        $MachineTypeData = DB::table('machine_type_master')->Select('*')->get();
        $MachineNameData = DB::table('machine_master')->Select('*')->get();
        $MachineCodeData = DB::table('inward_rented_machine_detail')->Select('*')->get();
        
        return view('MachineTransferMaster',compact('MachineMakeData','MachineTypeData','MachineNameData','MachineCodeData'));
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
            'transDate' => 'required',
            'fromLocName' => 'required',
            'toLocName' => 'required',
            'vehicleNumber' => 'required',
            'driveName' => 'required',
            'remark' => 'required',
            'userId' => 'required',
        ]);

        $input = $request->all();

        MachineTransferModel::create($input);

        $transId = DB::table('machine_transfer_master')->max('transId'); 
        $modelNumber = $request->modelNumber;
        
        for($x=0; $x<count($modelNumber); $x++) 
        {
            $data1=array(
                'transId'=>$transId,
                'transDate'=>$request->transDate, 
                'fromLocName'=>$request->fromLocName, 
                'toLocName'=>$request->toLocName,
                'vehicleNumber'=>$request->vehicleNumber,  
                'driveName'=>$request->driveName,   
                'remark'=> $request->remark,  
                'purId'=>$request->purId[$x],
                'MachineID'=>$request->MachineID[$x],
                'mc_make_Id'=>$request->mc_make_Id[$x],
                'modelNumber'=>$request->modelNumber[$x],
                'machinetype_id'=>$request->machinetype_id[$x],
                'Qty'=>$request->Qty[$x]
             );
            
             MachineTransferDetailModel::insert($data1);
            }
            
        return redirect()->route('MachineTransfer.index')->with('message', 'New Record Saved Succesfully');



    }
    public function getmachinecode(Request $request)
    {
        
       $purId= $request->purId;
       

      $datafetch=DB::table('inward_rented_machine_detail')->select('MachineID', 'machineCode', 'mc_make_Id', 'machinetype_id')->where('purId',$purId)->first();

        
         return response()->json($datafetch);

    }
    public function getmake(Request $request)
    {
        
       $MachineID= $request->MachineID;
       
      // DB::enableQueryLog();
      $datafetch=DB::table('machine_master')->select('ModelNumber')->where('MachineID',$MachineID)->first();
      //dd(DB::getQueryLog());

        
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
    // public function edit($transId)
    // {
       
    //     $MachineMakeData = DB::table('machine_make_master')->Select('*')->get();
    //     $MachineTypeData = DB::table('machine_type_master')->Select('*')->get();
    //     $MachineNameData = DB::table('machine_master')->Select('*')->get();
    //     $MachineCodeData = DB::table('inward_rented_machine_detail')->Select('*')->get();
    //     $DetailList = DB::table('machine_transfer_detail')->select('*')->get();
    //     $machinetransfer = MachineTransferModel::find($transId);
        
    //     return view('MachineTransferMaster', compact('machinetransfer','MachineMakeData','MachineTypeData','MachineNameData','MachineCodeData','DetailList'));



    // }


    // new 28-11-2024
    public function edit($transId)
{
    $MachineMakeData = DB::table('machine_make_master')->select('*')->get();
    $MachineTypeData = DB::table('machine_type_master')->select('*')->get();
    $MachineNameData = DB::table('machine_master')->select('*')->get();
    $MachineCodeData = DB::table('inward_rented_machine_detail')->select('*')->get();
    $DetailList = DB::table('machine_transfer_detail')
                    ->where('transId', $transId) 
                    ->get();
    $machinetransfer = MachineTransferModel::find($transId);

    return view('MachineTransferMaster', compact('machinetransfer', 'MachineMakeData', 'MachineTypeData', 'MachineNameData', 'MachineCodeData', 'DetailList'));
}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $machinetransfer = MachineTransferModel::findOrFail($id);

    //     $this->validate($request, [
    //         'transDate' => 'required',
    //         'fromLocName' => 'required',
    //         'toLocName' => 'required',
    //         'vehicleNumber' => 'required',
    //         'driveName' => 'required',
    //         'remark' => 'required',
    //         'userId' => 'required',
    //     ]);

    //     $input = $request->all();

    //     $machinetransfer->fill($input)->save();

    //      $transId = DB::table('machine_transfer_master')->max('transId'); 
    //     $modelNumber = $request->modelNumber;
        
    //     DB::table('machine_transfer_detail')->where('transId', $id)->delete();
  
    //     if($modelNumber != "")
    //     {
    //     for($x=0; $x<count($modelNumber); $x++) 
    //     {
    //         $data1=array(
    //             'transId'=>$transId,
    //             'transDate'=>$request->transDate, 
    //             'fromLocName'=>$request->fromLocName, 
    //             'toLocName'=>$request->toLocName,
    //             'vehicleNumber'=>$request->vehicleNumber,  
    //             'driveName'=>$request->driveName,   
    //             'remark'=> $request->remark,  
    //             'purId'=>$request->purId[$x],
    //             'MachineID'=>$request->MachineID[$x],
    //             'mc_make_Id'=>$request->mc_make_Id[$x],
    //             'modelNumber'=>$request->modelNumber[$x],
    //             'machinetype_id'=>$request->machinetype_id[$x],
    //             'Qty'=>$request->Qty[$x]
    //          );
    //         //  DB::table('master_training_planning_detail')->insert($data2);
    //         MachineTransferDetailModel::insert($data1);
         
    //     }
    // }

    //     return redirect()->route('MachineTransfer.index')->with('message', 'Update Record Succesfully');


    // }


    // new 28-11-2024
    public function update(Request $request, $id)
{
    $machinetransfer = MachineTransferModel::findOrFail($id);

    $this->validate($request, [
        'transDate' => 'required',
        'fromLocName' => 'required',
        'toLocName' => 'required',
        'vehicleNumber' => 'required',
        'driveName' => 'required',
        'remark' => 'required',
        'userId' => 'required',
    ]);

    // Update the master record
    $machinetransfer->fill($request->all())->save();

    $modelNumbers = $request->modelNumber;

    if ($modelNumbers) {
        for ($x = 0; $x < count($modelNumbers); $x++) {
            $data = [
                'transId' => $id, 
                'transDate' => $request->transDate,
                'fromLocName' => $request->fromLocName,
                'toLocName' => $request->toLocName,
                'vehicleNumber' => $request->vehicleNumber,
                'driveName' => $request->driveName,
                'remark' => $request->remark,
                'purId' => $request->purId[$x],
                'MachineID' => $request->MachineID[$x],
                'mc_make_Id' => $request->mc_make_Id[$x],
                'modelNumber' => $request->modelNumber[$x],
                'machinetype_id' => $request->machinetype_id[$x],
                'Qty' => $request->Qty[$x],
            ];

           
            $existingDetail = DB::table('machine_transfer_detail')
                ->where('transId', $id)
                ->where('MachineID', $request->MachineID[$x])
                ->first();

            if ($existingDetail) {
                
                DB::table('machine_transfer_detail')
                    ->where('transId', $id)
                    ->where('MachineID', $request->MachineID[$x])
                    ->update($data);
            } else {
               
                MachineTransferDetailModel::create($data);
            }
        }
    }

    return redirect()->route('MachineTransfer.index')->with('message', 'Update Record Successfully');
}


    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($transId)
    {
      
        MachineTransferModel::where('transId', $transId)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}