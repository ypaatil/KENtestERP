<?php

namespace App\Http\Controllers;

use App\Models\RackModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;



class RackController extends Controller
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
->where('form_id', '103')
->first();

        
        $RackList = RackModel::join('usermaster', 'usermaster.userId', '=', 'rack_master.userId')
       
        ->where('rack_master.delflag','=', '0')
        ->get(['rack_master.*','usermaster.username']);
  
        return view('RackMasterList', compact('RackList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         
        return view('RackMaster');
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
             
            
            'rack_name'=> 'required',
             
           
              
    ]);

    $input = $request->all();

    RackModel::create($input);

    return redirect()->route('Rack.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RackModel  $RackModel
     * @return \Illuminate\Http\Response
     */
    public function show(RackModel $RackModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RackModel  $RackModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $RackList = RackModel::find($id);
      return view('RackMaster', compact('RackList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RackModel  $RackModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $RackList = RackModel::findOrFail($id);

        $this->validate($request, [
            
            'rack_name'=> 'required',
            
        ]);

        $input = $request->all();

        $RackList->fill($input)->save();

        return redirect()->route('Rack.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RackModel  $RackModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RackModel::where('rack_id', $id)->update(array('delflag' => 1));
          Session::flash('delete', 'Deleted record successfully'); 
    }
}
