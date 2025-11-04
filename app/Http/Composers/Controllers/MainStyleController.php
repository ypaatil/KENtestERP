<?php

namespace App\Http\Controllers;

use App\Models\MainStyleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MainStyleController extends Controller
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
        ->where('form_id', '86')
        ->first();  
        
        $MainStyleList = MainStyleModel::join('usermaster', 'usermaster.userId', '=', 'main_style_master.userId')
        ->where('main_style_master.delflag','=', '0')
        ->get(['main_style_master.*','usermaster.username']);
  
        return view('MainStyleMasterList', compact('MainStyleList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('MainStyleMaster');
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
             
             
            'mainstyle_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    MainStyleModel::create($input);

    return redirect()->route('MainStyle.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function show(MainStyleModel $MainStyleModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $MainStyleList = MainStyleModel::find($id);
         
        return view('MainStyleMaster', compact('MainStyleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $MainStyleList = MainStyleModel::findOrFail($id);

        $this->validate($request, [
            
            'mainstyle_name'=> 'required',
           
        ]);

        $input = $request->all();

        $MainStyleList->fill($input)->save();

        return redirect()->route('MainStyle.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MainStyleModel  $MainStyleModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MainStyleModel::where('mainstyle_id', $id)->update(array('delflag' => 1));
     Session::flash('delete', 'Deleted record successfully'); 
    }
}
