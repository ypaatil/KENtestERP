<?php

namespace App\Http\Controllers;

use App\Models\ColorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Imports\ColorImport;
use Maatwebsite\Excel\Facades\Excel;

class ColorController extends Controller
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
        ->where('form_id', '47')
        ->first();
        
        //DB::enableQueryLog();
         $ColorList = ColorModel::join('usermaster', 'usermaster.userId', '=', 'color_master.userId')
        ->where('color_master.delflag','=', '0')
        ->get(['color_master.*','usermaster.username']);
//   $query = DB::getQueryLog();
//         $query = end($query);
//         dd($query);


       // $Countrys = Country::where('delflag','=', '0')->get();   

        return view('ColorMasterList', compact('ColorList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('ColorMaster');
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
            'color_name' => 'required',
        ]);

        $input = $request->all();

        ColorModel::create($input);

        return redirect()->route('Color.index');
    }

        public function importcolor(Request $request)
    {


      Excel::import(new ColorImport,request()->file('colorfile'));


        return redirect()->route('Color.index');

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function show(ColorModel $colorModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      
        $ColorList = ColorModel::find($id);
        
        return view('ColorMaster',compact('ColorList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ColorList = ColorModel::findOrFail($id);

        $this->validate($request, [
            'color_name' => 'required',
        ]);

        $input = $request->all();

        $ColorList->fill($input)->save();

        return redirect()->route('Color.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ColorModel  $colorModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        ColorModel::where('color_id', $id)->update(array('delflag' => 1));
         Session::flash('delete', 'Deleted record successfully'); 
    }
}
