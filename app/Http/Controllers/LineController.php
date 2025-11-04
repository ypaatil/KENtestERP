<?php

namespace App\Http\Controllers;

use App\Models\LineModel;
use App\Models\LedgerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class LineController extends Controller
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
        ->where('form_id', '110')
        ->first();  
        
        $LineList = LineModel::join('usermaster', 'usermaster.userId', '=', 'line_master.userId')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'line_master.Ac_code')
        ->where('line_master.delflag','=', '0')
        ->get(['line_master.*','usermaster.username', 'ledger_master.ac_name']);
  
        return view('LineMasterList', compact('LineList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Ledger = DB::table('ledger_master')->get();
         $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')
                  ->where('ledger_master.Ac_code','>', '39')
                  ->where('ledger_master.bt_id','=', '4')
                  ->get();
        return view('LineMaster',compact('Ledger'));
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
             
            'Ac_code'=> 'required',
            'line_name'=> 'required',
            
           
              
    ]);

    $input = $request->all();

    LineModel::create($input);

    return redirect()->route('Line.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LineModel  $LineModel
     * @return \Illuminate\Http\Response
     */
    public function show(LineModel $LineModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LineModel  $LineModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $LineList = LineModel::find($id);
        // select * from business_type where Bt_id=$id;

        $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')
              ->where('ledger_master.Ac_code','>', '39')
              ->where('ledger_master.bt_id','=', '4')
              ->get();
     
        
        return view('LineMaster', compact('LineList','Ledger'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LineModel  $LineModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $LineList = LineModel::findOrFail($id);

        $this->validate($request, [
            'Ac_code'=> 'required',
            'line_name'=> 'required',
            
        ]);

        $input = $request->all();

        $LineList->fill($input)->save();

        return redirect()->route('Line.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineModel  $LineModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        LineModel::where('line_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
