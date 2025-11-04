<?php

namespace App\Http\Controllers;
use App\Models\LedgerModel;
use App\Models\WashTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class WashTypeController extends Controller
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
        ->where('form_id', '33')
        ->first();   
        
        $WashTypeList = WashTypeModel::join('usermaster', 'usermaster.userId', '=', 'wash_type_master.userId')
        ->where('wash_type_master.delflag','=', '0')
        ->get(['wash_type_master.*','usermaster.username']);
  
        return view('WashTypeMasterList', compact('WashTypeList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('WashTypeMaster');
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
    
        WashTypeModel::create($input);
    
        return redirect()->route('WashType.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WashTypeModel  $WashTypeModel
     * @return \Illuminate\Http\Response
     */
    public function show(WashTypeModel $WashTypeModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WashTypeModel  $WashTypeModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $WashTypeList = WashTypeModel::find($id);
        return view('WashTypeMaster', compact('WashTypeList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WashTypeModel  $WashTypeModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $WashTypeList = WashTypeModel::findOrFail($id);

        $input = $request->all();

        $WashTypeList->fill($input)->save();

        return redirect()->route('WashType.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WashTypeModel  $WashTypeModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        WashTypeModel::where('WashTypeId', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
