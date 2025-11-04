<?php

namespace App\Http\Controllers;

use App\Models\MerchantMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class MerchantMasterController extends Controller
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
->where('form_id', '102')
->first();  
        
        $MerchantMasterList = MerchantMasterModel::join('usermaster', 'usermaster.userId', '=', 'merchant_master.userId')
        ->where('merchant_master.delflag','=', '0')
        ->get(['merchant_master.*','usermaster.username']);
  
        return view('MerchantMasterList', compact('MerchantMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('MerchantMaster');
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
             
             
            'merchant_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    MerchantMasterModel::create($input);

    return redirect()->route('MerchantMaster.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MerchantMasterModel  $MerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantMasterModel $MerchantMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MerchantMasterModel  $MerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $MerchantMasterList = MerchantMasterModel::find($id);
         
        return view('MerchantMaster', compact('MerchantMasterList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MerchantMasterModel  $MerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $MerchantMasterList = MerchantMasterModel::findOrFail($id);

        $this->validate($request, [
            
            'merchant_name'=> 'required',
           
        ]);

        $input = $request->all();

        $MerchantMasterList->fill($input)->save();

        return redirect()->route('MerchantMaster.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MerchantMasterModel  $MerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MerchantMasterModel::where('merchant_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
}
