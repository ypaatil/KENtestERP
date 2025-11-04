<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\MainStyleModel; 
use App\Models\OperationNameMasterModel;
use Session;
use DataTables;
use DB;


class OperationNameController extends Controller
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
        ->where('form_id', '243')
        ->first();
        $OpertionList = OperationNameMasterModel::select('operation_name_master.*','main_style_master.mainstyle_name','usermaster.username')
                        ->join('usermaster', 'usermaster.userId', '=', 'operation_name_master.userId', 'left outer')
                        ->join('main_style_master','main_style_master.mainstyle_id','operation_name_master.main_style_id')
                        ->where('operation_name_master.delflag','=', '0')
                        ->get();  
        
        return view('OperationNameMasterList', compact('OpertionList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();  
        
        return view('OperationNameMaster',compact('MainStyleList'));
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
                'main_style_id'=>$request->mainstyle_id, 
                'operation_name'=>$request->operation_name, 
                'userId'=>$request->userId,
                'delflag'=>'0'
            );
         
            OperationNameMasterModel::insert($data1);
            return redirect()->route('OperationName.index')->with('message', 'Data Saved Succesfully');  
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = OperationNameMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
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
        $OperationList = OperationNameMasterModel::find($id);
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();
          
        return view('OperationNameMasterEdit',compact('OperationList','MainStyleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $operationId)
    {  
            $data1=array(
                'main_style_id'=>$request->main_style_id, 
                'operation_name'=>$request->operation_name, 
                'userId'=>$request->userId,
                'delflag'=>'0'
            );
 
            $OperationList = OperationNameMasterModel::findOrFail($operationId); 
 
            $OperationList->fill($data1)->save();
 
       
     return redirect()->route('OperationName.index')->with('message', 'Data Saved Succesfully');  
    }
     
     
      
    public function destroy($id)
    { 
        DB::table('operation_name_master')->where('operationNameId', $id)->delete(); 
        Session::flash('messagedelete', 'Deleted record successfully');  
    }
    
 
}