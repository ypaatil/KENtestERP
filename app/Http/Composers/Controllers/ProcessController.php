<?php

namespace App\Http\Controllers;

use App\Models\ProcessModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class ProcessController extends Controller
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
->where('form_id', '82')
->first();  
        
        
        $ProcessList = ProcessModel::join('usermaster', 'usermaster.userId', '=', 'process_master.userId')
        ->where('process_master.delflag','=', '0')
        ->get(['process_master.*','usermaster.username']);
  
        return view('ProcessMasterList', compact('ProcessList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ProcessMaster');
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
             
             
            'process_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    ProcessModel::create($input);

    return redirect()->route('Process.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProcessModel  $ProcessModel
     * @return \Illuminate\Http\Response
     */
    public function show(ProcessModel $ProcessModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProcessModel  $ProcessModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ProcessList = ProcessModel::find($id);
         
        return view('ProcessMaster', compact('ProcessList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProcessModel  $ProcessModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $ProcessList = ProcessModel::findOrFail($id);

        $this->validate($request, [
            
            'process_name'=> 'required',
           
        ]);

        $input = $request->all();

        $ProcessList->fill($input)->save();

        return redirect()->route('Process.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProcessModel  $ProcessModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProcessModel::where('process_id', $id)->update(array('delflag' => 1));
       Session::flash('delete', 'Deleted record successfully'); 
    }
}
