<?php

namespace App\Http\Controllers;

use App\Models\ShipmentModeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class ShipmentModeController extends Controller
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
->where('form_id', '78')
->first();  
        
        $ShipmentModeList = ShipmentModeModel::join('usermaster', 'usermaster.userId', '=', 'shipment_mode_master.userId')
        ->where('shipment_mode_master.delflag','=', '0')
        ->get(['shipment_mode_master.*','usermaster.username']);
  
        return view('ShipmentModeMasterList', compact('ShipmentModeList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
         
        return view('ShipmentModeMaster');
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
             
             
            'ship_mode_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    ShipmentModeModel::create($input);

    return redirect()->route('ShipmentMode.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShipmentModeModel  $ShipmentModeModel
     * @return \Illuminate\Http\Response
     */
    public function show(ShipmentModeModel $ShipmentModeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShipmentModeModel  $ShipmentModeModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ShipmentModeList = ShipmentModeModel::find($id);
         
        return view('ShipmentModeMaster', compact('ShipmentModeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShipmentModeModel  $ShipmentModeModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $ShipmentModeList = ShipmentModeModel::findOrFail($id);

        $this->validate($request, [
            
            'ship_mode_name'=> 'required',
           
        ]);

        $input = $request->all();

        $ShipmentModeList->fill($input)->save();

        return redirect()->route('ShipmentMode.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShipmentModeModel  $ShipmentModeModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ShipmentModeModel::where('ship_id', $id)->update(array('delflag' => 1));
           Session::flash('delete', 'Deleted record successfully'); 
    }
}
