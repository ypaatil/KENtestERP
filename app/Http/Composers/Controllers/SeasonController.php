<?php

namespace App\Http\Controllers;
use App\Models\LedgerModel;
use App\Models\SeasonModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class SeasonController extends Controller
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
        ->where('form_id', '33')
        ->first();   
        
        $SeasonList = SeasonModel::join('usermaster', 'usermaster.userId', '=', 'season_master.userId')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'season_master.Ac_code')
        ->where('season_master.delflag','=', '0')
        ->get(['season_master.*','usermaster.username', 'ledger_master.ac_name']);
  
        return view('SeasonMasterList', compact('SeasonList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $Ledger = DB::table('ledger_master')->get();
         $Ledger = LedgerModel::where('ledger_master.delflag','=', '0')->where('ledger_master.Ac_code','>', '39')->get();
        return view('SeasonMaster',compact('Ledger'));
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
            'season_name'=> 'required',
            
            
    ]);

    $input = $request->all();

    SeasonModel::create($input);

    return redirect()->route('Season.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SeasonModel  $seasonModel
     * @return \Illuminate\Http\Response
     */
    public function show(SeasonModel $seasonModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SeasonModel  $seasonModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $SeasonList = SeasonModel::find($id);
        // select * from SeasonMaster where Bt_id=$id;

        $Ledger = DB::table('ledger_master')->get();
        return view('SeasonMaster', compact('SeasonList','Ledger'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SeasonModel  $seasonModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $SeasonList = SeasonModel::findOrFail($id);

        $this->validate($request, [
            'Ac_code'=> 'required',
            'season_name'=> 'required',
           
        ]);

        $input = $request->all();

        $SeasonList->fill($input)->save();

        return redirect()->route('Season.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SeasonModel  $seasonModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SeasonModel::where('season_id', $id)->update(array('delflag' => 1));
        
    Session::flash('delete', 'Deleted record successfully'); 
    }
}
