<?php

namespace App\Http\Controllers;

use App\Models\FabricDefectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class FabricDefectController extends Controller
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
->where('form_id', '85')
->first();   
        
        
        $FabricDefectList = FabricDefectModel::join('usermaster', 'usermaster.userId', '=', 'fabric_defect_master.userId')
        ->where('fabric_defect_master.delflag','=', '0')
        ->get(['fabric_defect_master.*','usermaster.username']);
  
        return view('FabricDefectMasterList', compact('FabricDefectList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('FabricDefectMaster');
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
             
             
            'fabricdefect_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    FabricDefectModel::create($input);

    return redirect()->route('FabricDefect.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricDefectModel  $FabricDefectModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricDefectModel $FabricDefectModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricDefectModel  $FabricDefectModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $FabricDefectList = FabricDefectModel::find($id);
         
        return view('FabricDefectMaster', compact('FabricDefectList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricDefectModel  $FabricDefectModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $FabricDefectList = FabricDefectModel::findOrFail($id);

        $this->validate($request, [
            
            'fabricdefect_name'=> 'required',
           
        ]);

        $input = $request->all();

        $FabricDefectList->fill($input)->save();

        return redirect()->route('FabricDefect.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricDefectModel  $FabricDefectModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FabricDefectModel::where('fdef_id', $id)->update(array('delflag' => 1));
      Session::flash('delete', 'Deleted record successfully'); 
    }
}
