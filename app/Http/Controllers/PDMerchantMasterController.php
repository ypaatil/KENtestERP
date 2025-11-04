<?php

namespace App\Http\Controllers;

use App\Models\PDMerchantMasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class PDMerchantMasterController extends Controller
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
->where('form_id', '117')
->first();  
        
        $PDMerchantMasterList = PDMerchantMasterModel::join('usermaster', 'usermaster.userId', '=', 'PDMerchant_master.userId')
        ->where('PDMerchant_master.delflag','=', '0')
        ->get(['PDMerchant_master.*','usermaster.username']);
  
        return view('PDMerchantMasterList', compact('PDMerchantMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('PDMerchantMaster');
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
             
             
            'PDMerchant_name'=> 'required',   
           
              
    ]);

    $input = $request->all();

    PDMerchantMasterModel::create($input);

    return redirect()->route('PDMerchantMaster.index');


   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PDMerchantMasterModel  $PDMerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show(PDMerchantMasterModel $PDMerchantMasterModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PDMerchantMasterModel  $PDMerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $PDMerchantMasterList = PDMerchantMasterModel::find($id);
         
        return view('PDMerchantMaster', compact('PDMerchantMasterList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PDMerchantMasterModel  $PDMerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $PDMerchantMasterList = PDMerchantMasterModel::findOrFail($id);

        $this->validate($request, [
            
            'PDMerchant_name'=> 'required',
           
        ]);

        $input = $request->all();

        $PDMerchantMasterList->fill($input)->save();

        return redirect()->route('PDMerchantMaster.index')->with('message', 'Update Record Succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PDMerchantMasterModel  $PDMerchantMasterModel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PDMerchantMasterModel::where('PDMerchant_id', $id)->update(array('delflag' => 1));
        Session::flash('delete', 'Deleted record successfully'); 
    }
    
    
    public function changePDMerchantStatus(Request $request)
    {    
         DB::table('PDMerchant_master')->where('PDMerchant_id',$request->PDMerchant_id)->update(['status'=>$request->status]);
         return 1;
    }
    
}
