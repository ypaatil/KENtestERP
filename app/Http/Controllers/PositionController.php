<?php

namespace App\Http\Controllers;

use App\Models\PositionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PositionController extends Controller
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
->where('form_id', '81')
->first();   
        
        
        $PositionList = PositionModel::join('usermaster', 'usermaster.userId', '=', 'position_master.userId')
        ->where('position_master.delflag','=', '0')
        ->get(['position_master.*','usermaster.username']);
  
        return view('PositionMasterList', compact('PositionList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('PositionMaster');
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
             
             
            'position_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    PositionModel::create($input);

    return redirect()->route('Position.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PositionModel  $PositionModel
     * @return \Illuminate\Http\Response
     */
    public function show(PositionModel $PositionModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PositionModel  $PositionModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $PositionList = PositionModel::find($id);
         
        return view('PositionMaster', compact('PositionList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PositionModel  $PositionModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $PositionList = PositionModel::findOrFail($id);

        $this->validate($request, [
            
            'position_name'=> 'required',
           
        ]);

        $input = $request->all();

        $PositionList->fill($input)->save();

        return redirect()->route('Position.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PositionModel  $PositionModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PositionModel::where('pos_id', $id)->update(array('delflag' => 1));
        return redirect()->route('Position.index')->with('message', 'Delete Record Succesfully');
    }
}
