<?php

namespace App\Http\Controllers;

use App\Models\CommissionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
 

class CommissionController extends Controller
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
        ->where('form_id', '101')
        ->first();
        
        //DB::enableQueryLog();
         $ComissionList = CommissionModel::join('usermaster', 'usermaster.userId', '=', 'commission_master.userId')
        ->where('commission_master.delflag','=', '0')
        ->get(['commission_master.*','usermaster.username']);
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);


       // $Countrys = Country::where('delflag','=', '0')->get();   

        return view('CommissionMasterList', compact('ComissionList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('CommissionMaster');
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
            'coms_name' => 'required',
        ]);

        $input = $request->all();

        CommissionModel::create($input);

        return redirect()->route('Commission.index');
    }

        

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CommissionModel  $CommissionModel
     * @return \Illuminate\Http\Response
     */
    public function show(CommissionModel $CommissionModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommissionModel  $CommissionModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $CommissionList = CommissionModel::find($id);
        
        return view('CommissionMaster',compact('CommissionList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommissionModel  $CommissionModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $CommissionList = CommissionModel::findOrFail($id);

        $this->validate($request, [
            'coms_name' => 'required',
        ]);

        $input = $request->all();

        $CommissionList->fill($input)->save();

        return redirect()->route('Commission.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CommissionModel  $CommissionModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        CommissionModel::where('coms_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
