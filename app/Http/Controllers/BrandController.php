<?php

namespace App\Http\Controllers;

use App\Models\BrandModel;
use App\Models\LedgerModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class BrandController extends Controller
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
        ->where('form_id', '32')
        ->first();  
        
        $BrandList = BrandModel::join('usermaster', 'usermaster.userId', '=', 'brand_master.userId')
        ->join('ledger_master', 'ledger_master.ac_code', '=', 'brand_master.Ac_code')
        ->where('brand_master.delflag','=', '0')
        ->get(['brand_master.*','usermaster.username', 'ledger_master.ac_name']);
  
        return view('BrandMasterList', compact('BrandList','chekform'));
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
        return view('BrandMaster',compact('Ledger'));
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
            'brand_name'=> 'required',
            
           
              
    ]);

    $input = $request->all();

    BrandModel::create($input);

    return redirect()->route('Brand.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function show(BrandModel $brandModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $BrandList = BrandModel::find($id);
        // select * from business_type where Bt_id=$id;

        $Ledger = DB::table('ledger_master')->where('ledger_master.Ac_code','>', '39')->get();
         
        
        return view('BrandMaster', compact('BrandList','Ledger'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $BrandList = BrandModel::findOrFail($id);

        $this->validate($request, [
            'Ac_code'=> 'required',
            'brand_name'=> 'required',
            
        ]);

        $input = $request->all();

        $BrandList->fill($input)->save();

        return redirect()->route('Brand.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BrandModel  $brandModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BrandModel::where('brand_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
