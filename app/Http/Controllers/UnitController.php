<?php

namespace App\Http\Controllers;

use App\Models\UnitModel;
use Illuminate\Http\Request;
use Session;
use DB;

class UnitController extends Controller
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
        ->where('form_id', '28')
        ->first();
        
        
        //   DB::enableQueryLog(); 
        $UnitList = UnitModel::join('usermaster', 'usermaster.userId', '=', 'unit_master.userId')
        ->where('unit_master.delflag','=', '0')
        ->get(['unit_master.*','usermaster.username']);
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);
        return view('UnitMasterList', compact('UnitList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('UnitMaster');
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
             
            'unit_name'=> 'required',
            
            
              
    ]);

    $input = $request->all();

    UnitModel::create($input);

    return redirect()->route('Unit.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UnitModel  $unitModel
     * @return \Illuminate\Http\Response
     */
    public function show(UnitModel $unitModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnitModel  $unitModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $UnitList = UnitModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('UnitMaster', compact('UnitList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnitModel  $unitModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $UnitList = UnitModel::findOrFail($id);

        $this->validate($request, [
            'unit_name'=> 'required',
             
        ]);

        $input = $request->all();

        $UnitList->fill($input)->save();

        return redirect()->route('Unit.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UnitModel  $unitModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UnitModel::where('unit_id', $id)->update(array('delflag' => 1));
      Session::flash('delete', 'Deleted record successfully'); 
    }
}
