<?php

namespace App\Http\Controllers;

use App\Models\FinishedGoodModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MainStyleModel;
use App\Models\SubStyleModel;

use Session; 

class FinishedGoodController extends Controller
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
        ->where('form_id', '26')
        ->first();  
        
        
        $FGList = FinishedGoodModel::join('usermaster', 'usermaster.userId', '=', 'fg_master.userId')
         ->join('main_style_master', 'main_style_master.mainstyle_id','=','fg_master.mainstyle_id')
          ->join('sub_style_master', 'sub_style_master.substyle_id','=','fg_master.substyle_id')
        ->where('fg_master.delflag','=', '0')
        ->get(['fg_master.*','usermaster.username', 'mainstyle_name','substyle_name']);
  
        return view('FinishedGoodsMasterList', compact('FGList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
         $MainStyleList = MainStyleModel::where('main_style_master.delflag','=','0')->get();
         $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=','0')->get();
        
        return view('FinishedGoodsMaster', compact('MainStyleList','SubStyleList'));
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
             
              'mainstyle_id'=> 'required',
              'substyle_id'=> 'required',
            'fg_name'=> 'required',
            
            
              
    ]);

    $input = $request->all();

    FinishedGoodModel::create($input);

    return redirect()->route('FinishedGood.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function show(FinishedGoodModel $finishedGoodModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=','0')->get();
         $SubStyleList = SubStyleModel::where('sub_style_master.delflag','=','0')->get();
        
        
        $FGList = FinishedGoodModel::find($id);
        // select * from business_type where Bt_id=$id;
        return view('FinishedGoodsMaster', compact('FGList','MainStyleList','SubStyleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $FGList = FinishedGoodModel::findOrFail($id);

        $this->validate($request, [
            'mainstyle_id'=> 'required',
            'substyle_id'=> 'required',
            'fg_name'=> 'required',
          
        ]);

        $input = $request->all();

        $FGList->fill($input)->save();

        return redirect()->route('FinishedGood.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FinishedGoodModel  $finishedGoodModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FinishedGoodModel::where('fg_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
