<?php

namespace App\Http\Controllers;

use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class WarehouseController extends Controller
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
->where('form_id', '83')
->first();  
        
        
        $WarehouseList = WarehouseModel::join('usermaster', 'usermaster.userId', '=', 'warehouse_master.userId')
        ->where('warehouse_master.delflag','=', '0')
        ->get(['warehouse_master.*','usermaster.username']);
  
        return view('WarehouseMasterList', compact('WarehouseList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('WarehouseMaster');
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
             
             
            'warehouse_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    WarehouseModel::create($input);

    return redirect()->route('Warehouse.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WarehouseModel  $WarehouseModel
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseModel $WarehouseModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WarehouseModel  $WarehouseModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $WarehouseList = WarehouseModel::find($id);
         
        return view('WarehouseMaster', compact('WarehouseList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WarehouseModel  $WarehouseModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $WarehouseList = WarehouseModel::findOrFail($id);

        $this->validate($request, [
            
            'warehouse_name'=> 'required',
           
        ]);

        $input = $request->all();

        $WarehouseList->fill($input)->save();

        return redirect()->route('Warehouse.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WarehouseModel  $WarehouseModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        WarehouseModel::where('warehouse_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
