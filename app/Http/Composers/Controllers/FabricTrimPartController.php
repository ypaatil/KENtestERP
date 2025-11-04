<?php

namespace App\Http\Controllers;

use App\Models\FabricTrimPartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class FabricTrimPartController extends Controller
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
->where('form_id', '50')
->first();
        
        $FabricTrimPartList = FabricTrimPartModel::join('usermaster', 'usermaster.userId', '=', 'part_master.userId')
        ->where('part_master.delflag','=', '0')
        ->get(['part_master.*','usermaster.username']);
  
        return view('FabricTrimPartMasterList', compact('FabricTrimPartList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('FabricTrimPartMaster');
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
             
           
            'part_name'=> 'required',
            
           
              
    ]);

    $input = $request->all();

    FabricTrimPartModel::create($input);

    return redirect()->route('FabricTrimPart.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FabricTrimPartModel  $fabricTrimPartModel
     * @return \Illuminate\Http\Response
     */
    public function show(FabricTrimPartModel $fabricTrimPartModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FabricTrimPartModel  $fabricTrimPartModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $FabricTrimPartList = FabricTrimPartModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('FabricTrimPartMaster', compact('FabricTrimPartList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FabricTrimPartModel  $fabricTrimPartModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $FabricTrimPartList = FabricTrimPartModel::findOrFail($id);

        $this->validate($request, [
            
            'part_name'=> 'required',
             
           
            
        ]);

        $input = $request->all();

        $FabricTrimPartList->fill($input)->save();

        return redirect()->route('FabricTrimPart.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FabricTrimPartModel  $fabricTrimPartModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FabricTrimPartModel::where('part_id', $id)->update(array('delflag' => 1));
        return redirect()->route('FabricTrimPart.index')->with('message', 'Delete Record Succesfully');
    }
}
