<?php

namespace App\Http\Controllers;

use App\Models\MachineMakeMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MachineMakeMasterController extends Controller
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
        ->where('form_id', '288')
        ->first();




        $MachineMakes = MachineMakeMasterModel::join('usermaster', 'usermaster.userId', '=', 'machine_make_master.userId')
        ->where('machine_make_master.delflag','=', '0')
        ->get(['machine_make_master.*','usermaster.username']);

        return view('MachineMakeMasterList', compact('MachineMakes','chekform'));


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('MachineMakeMaster');
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
            'machine_make_name' => 'required',
        ]);

        $input = $request->all();

        MachineMakeMasterModel::create($input);

        return redirect()->route('MachineMake.index')->with('message', 'New Record Saved Succesfully');



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
    public function edit($mc_make_Id)
    {
        //

        $machinemake = MachineMakeMasterModel::find($mc_make_Id);
        
        return view('MachineMakeMaster', compact('machinemake'));



    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $machinemake = MachineMakeMasterModel::findOrFail($id);

        $this->validate($request, [
            'machine_make_name' => 'required',
        ]);

        $input = $request->all();

        $machinemake->fill($input)->save();

        return redirect()->route('MachineMake.index')->with('message', 'Update Record Succesfully');


    }

    /**
     * Remove the specified resource from storage.
     *

     * @return \Illuminate\Http\Response
     */
    public function destroy($mc_make_Id)
    {
      
      MachineMakeMasterModel::where('mc_make_Id', $mc_make_Id)->update(array('delflag' => 1));

    Session::flash('delete', 'Deleted record successfully'); 
    }
}
