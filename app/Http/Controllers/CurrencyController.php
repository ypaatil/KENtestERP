<?php

namespace App\Http\Controllers;

use App\Models\CurrencyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;


class CurrencyController extends Controller
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
->where('form_id', '75')
->first();
        
        
        $CurrencyList = CurrencyModel::join('usermaster', 'usermaster.userId', '=', 'currency_master.userId')
        ->where('currency_master.delflag','=', '0')
        ->get(['currency_master.*','usermaster.username']);
  
        return view('CurrencyMasterList', compact('CurrencyList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
         
        return view('CurrencyMaster');
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
             
             
            'currency_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    CurrencyModel::create($input);

    return redirect()->route('Currency.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CurrencyModel  $CurrencyModel
     * @return \Illuminate\Http\Response
     */
    public function show(CurrencyModel $CurrencyModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CurrencyModel  $CurrencyModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $CurrencyList = CurrencyModel::find($id);
         
        return view('CurrencyMaster', compact('CurrencyList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CurrencyModel  $CurrencyModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $CurrencyList = CurrencyModel::findOrFail($id);

        $this->validate($request, [
            
            'currency_name'=> 'required',
           
        ]);

        $input = $request->all();

        $CurrencyList->fill($input)->save();

        return redirect()->route('Currency.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CurrencyModel  $CurrencyModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CurrencyModel::where('cur_id', $id)->update(array('delflag' => 1));
    Session::flash('delete', 'Deleted record successfully'); 
    }
}
