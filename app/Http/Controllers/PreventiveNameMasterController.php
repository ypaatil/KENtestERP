<?php

namespace App\Http\Controllers;

use App\Models\PreventiveNameMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PreventiveNameMasterController extends Controller
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




        $PreventiveNames = PreventiveNameMasterModel::join('usermaster', 'usermaster.userId', '=', 'preventive_name_master.userId')
        ->where('preventive_name_master.delflag','=', '0')
        ->get(['preventive_name_master.*','usermaster.username']);

        return view('PreventiveNameMasterList', compact('PreventiveNames','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('PreventiveNameMaster');
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
            'preventive_name' => 'required',
        ]);

        $input = $request->all();

        PreventiveNameMasterModel::create($input);

        return redirect()->route('PreventiveName.index')->with('message', 'New Record Saved Succesfully');



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
    public function edit($preventive_Id)
    {
        //

        $preventivename = PreventiveNameMasterModel::find($preventive_Id);
        
        return view('PreventiveNameMaster', compact('preventivename'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $preventivename = PreventiveNameMasterModel::findOrFail($id);

        $this->validate($request, [
            'preventive_name' => 'required',
        ]);

        $input = $request->all();

        $preventivename->fill($input)->save();

        return redirect()->route('PreventiveName.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($preventive_Id)
    {
      
      PreventiveNameMasterModel::where('preventive_Id', $preventive_Id)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
