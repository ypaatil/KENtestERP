<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\MainStyleModel; 
use App\Models\BuyerBrandAuthMasterModel;
use App\Models\BuyerBrandAuthDetailModel;
use Session;
use DataTables;
use DB;
date_default_timezone_set('Asia/Calcutta');


class BuyerBrandAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $chekform = DB::table('form_auth')
        ->where('emp_id', Session::get('userId'))
        ->where('form_id', '268')
        ->first();
        $BuyerBrandAuthMasterList = BuyerBrandAuthMasterModel::select('buyer_brand_auth_master.*','usermaster.username')
                        ->join('usermaster', 'usermaster.userId', '=', 'buyer_brand_auth_master.userId', 'left outer') 
                        ->where('buyer_brand_auth_master.delflag','=', '0')
                        ->get();  
        
        return view('BuyerBrandAuthMasterList', compact('BuyerBrandAuthMasterList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $UserMasterList = DB::table('usermaster')->where('delflag','=', '0')->get();  
        $brandList = DB::table('brand_master')->where('delflag','=', '0')->get();  
        return view('BuyerBrandAuthMaster',compact('UserMasterList','brandList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    
        $data1=array(
            'userId'=>$request->user_Id, 
            'created_at'=>date("Y-m-d H:i:s") 
        );
     
        BuyerBrandAuthMasterModel::insert($data1);
        
        $buyer_brand_auth_id = DB::table('buyer_brand_auth_master')->max('buyer_brand_auth_id');
     
        if(count($request->authId) > 0)
        {
            foreach ($request->authId as $i => $auth_id) 
            {  
                if (in_array($auth_id, $request->brand_id)) {
                    $data2 = array(
                        'buyer_brand_auth_id' => $buyer_brand_auth_id, 
                        'brand_id' => $auth_id,  
                        'auth_id' => 1, 
                        'userId' => $request->user_Id
                    );
            
                    BuyerBrandAuthDetailModel::insert($data2);
                }
            }
        }
        
        return redirect()->route('BuyerBrandAuth.index')->with('message', 'Data Saved Succesfully');  
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = BuyerBrandAuthMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
        ->join('ledger_master', 'ledger_master.Ac_code', '=', 'bom_master.Ac_code', 'left outer')
        ->join('season_master', 'season_master.season_id', '=', 'bom_master.season_id', 'left outer')
        ->join('currency_master', 'currency_master.cur_id', '=', 'bom_master.currency_id', 'left outer')
        ->join('costing_type_master', 'costing_type_master.cost_type_id', '=', 'bom_master.cost_type_id', 'left outer')
         ->join('main_style_master', 'main_style_master.mainstyle_id', '=', 'bom_master.mainstyle_id', 'left outer') 
        ->join('sub_style_master', 'sub_style_master.substyle_id', '=', 'bom_master.substyle_id', 'left outer')  
         ->join('fg_master', 'fg_master.fg_id', '=', 'bom_master.fg_id', 'left outer') 
        ->where('bom_master.delflag','=', '0')
        ->where('bom_master.bom_code','=', $bom_code)
        
        ->get(['bom_master.*','usermaster.username','ledger_master.Ac_name','costing_type_master.cost_type_name','season_master.season_name','currency_master.currency_name'
        ,'main_style_master.mainstyle_name','sub_style_master.substyle_name','fg_master.fg_name']);
   
        return view('budgetPrint', compact('BOMList'));  
      
    }
 
 
    public function edit($id)
    {   
        $BuyerBrandAuthList = BuyerBrandAuthMasterModel::find($id);
        $UserMasterList = DB::table('usermaster')->where('delflag','=', '0')->get();  
        $brandList = DB::table('brand_master')->where('delflag','=', '0')->get();  
        $BuyerBrandAuthDetailList = BuyerBrandAuthDetailModel::where('buyer_brand_auth_id','=', $id)->get();   
        
        return view('BuyerBrandAuthMasterEdit',compact('BuyerBrandAuthList','BuyerBrandAuthDetailList','UserMasterList','brandList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $buyer_brand_auth_id)
    {  
            $data1=array(
                'userId'=>$request->user_Id,
                'delflag'=>'0',
                'updated_at'=>date("Y-m-d H:i:s")
            );
 
            $BuyerBrandAuthList = BuyerBrandAuthMasterModel::findOrFail($buyer_brand_auth_id); 
 
            $BuyerBrandAuthList->fill($data1)->save();
            DB::table('buyer_brand_auth_details')->where('buyer_brand_auth_id', $buyer_brand_auth_id)->delete();  
             
            if(!empty($request->authId)) 
            {
                foreach ($request->authId as $i => $auth_id) 
                {  
                    if (in_array($auth_id, $request->brand_id)) {
                        $data2 = array(
                            'buyer_brand_auth_id' => $buyer_brand_auth_id, 
                            'brand_id' => $auth_id,  
                            'auth_id' => 1, 
                            'userId' => $request->user_Id
                        );
                
                        BuyerBrandAuthDetailModel::insert($data2);
                    }
                }
            }
            return redirect()->route('BuyerBrandAuth.index')->with('message', 'Data Updated Succesfully');  
    }
      
    public function destroy($id)
    { 
        DB::table('buyer_brand_auth_master')->where('buyer_brand_auth_id', $id)->delete(); 
        DB::table('buyer_brand_auth_details')->where('buyer_brand_auth_id', $id)->delete();  
        Session::flash('messagedelete', 'Deleted record successfully');  
    } 
     
}