<?php

namespace App\Http\Controllers;

use App\Models\SparesReturnMaterialStatusModel;
use App\Models\LedgerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SparesReturnMaterialStatusController extends Controller
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
        ->where('form_id', '280')
        ->first();  
        
        $SparesReturnList = SparesReturnMaterialStatusModel::join('usermaster', 'usermaster.userId', '=', 'spare_return_material_status.userId')
        ->where('spare_return_material_status.delflag','=', '0')
        ->get(['spare_return_material_status.*','usermaster.username']);
  
        return view('SparesReturnMaterialStatusList', compact('SparesReturnList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('SparesReturnMaterialStatusMaster');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        SparesReturnMaterialStatusModel::create($input);
        return redirect()->route('SparesReturnMaterialStatus.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SparesReturnMaterialStatusModel  $SparesReturnMaterialStatusModel
     * @return \Illuminate\Http\Response
     */
    public function show(SparesReturnMaterialStatusModel $SparesReturnMaterialStatusModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SparesReturnMaterialStatusModel  $SparesReturnMaterialStatusModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SparesReturnList = SparesReturnMaterialStatusModel::find($id);
        
        return view('SparesReturnMaterialStatusMaster', compact('SparesReturnList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SparesReturnMaterialStatusModel  $SparesReturnMaterialStatusModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $SparesReturnList = SparesReturnMaterialStatusModel::findOrFail($id); 
        
        $input = $request->all();

        $SparesReturnList->fill($input)->save();

        return redirect()->route('SparesReturnMaterialStatus.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SparesReturnMaterialStatusModel  $SparesReturnMaterialStatusModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SparesReturnMaterialStatusModel::where('spare_return_material_status_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
