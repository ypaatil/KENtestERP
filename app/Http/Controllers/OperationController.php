<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\MainStyleModel; 
use App\Models\OperationMasterModel;
use App\Models\OperationDetailModel;
use App\Models\BuyerPurchaseOrderMasterModel;
use App\Models\OperationNameMasterModel;
use Session;
use DataTables;
use DB;


class OperationController extends Controller
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
        ->where('form_id', '244')
        ->first();
        $OpertionList = OperationMasterModel::select('operation_master.*','main_style_master.mainstyle_name','usermaster.username')
                        ->join('usermaster', 'usermaster.userId', '=', 'operation_master.userId', 'left outer')
                        ->join('main_style_master','main_style_master.mainstyle_id','operation_master.main_style_id')
                        ->where('operation_master.delflag','=', '0')
                        ->get();  
        
        return view('OperationMasterList', compact('OpertionList','chekform'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();  
        $SalesOrderList = BuyerPurchaseOrderMasterModel::where('delflag','=', '0')->get();  
        $SalesOrderList = DB::SELECT('SELECT * FROM buyer_purchse_order_master WHERE og_id !=4 AND tr_code NOT IN (SELECT sales_order_no FROM operation_master WHERE delflag=0) AND delflag=0');
        return view('OperationMaster',compact('MainStyleList','SalesOrderList'));
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
                'sales_order_no'=>$request->sales_order_no, 
                'main_style_id'=>$request->main_style_id,  
                'userId'=>$request->userId,
                'delflag'=>'0'
            );
         
            OperationMasterModel::insert($data1);
            
            $operationId = DB::table('operation_master')->max('operationId');
            for($i=0;$i<count($request->operationNameId);$i++)
            {
                $data2=array(
                'operationId'=>$operationId, 
                'operationNameId'=>$request->operationNameId[$i],  
                'operation_rate'=>$request->operation_rate[$i], 
                );
                
                OperationDetailModel::insert($data2);   
            }
            
            return redirect()->route('Operation.index')->with('message', 'Data Saved Succesfully');  
      
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SalesOrderCostingMasterModel  $SalesOrderCostingMasterModel
     * @return \Illuminate\Http\Response
     */
    public function show($bom_code)
    {
        
         $BOMList = OperationMasterModel::join('usermaster', 'usermaster.userId', '=', 'bom_master.userId', 'left outer')
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
        $OperationList = OperationMasterModel::find($id);
        $MainStyleList = MainStyleModel::where('main_style_master.delflag','=', '0')->get();   
        $SalesOrderList = BuyerPurchaseOrderMasterModel::where('delflag','=', '0')->get();  
        $OperationDetailList = OperationDetailModel::where('operationId','=', $id)->get();  
        $OperationNameList = OperationNameMasterModel::where('main_style_id','=', $OperationList->main_style_id)->where('delflag','=', '0')->get();  
        
        return view('OperationMasterEdit',compact('OperationList','MainStyleList','SalesOrderList','OperationDetailList','OperationNameList'));
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
                'operationId'=>$request->operationId, 
                'userId'=>$request->userId,
                'delflag'=>'0'
            );
 
            $OperationList = OperationMasterModel::findOrFail($operationId); 
 
            $OperationList->fill($data1)->save();
            DB::table('operation_details')->where('OperationId', $operationId)->delete();  
            
            for($i=0;$i<count($request->operationNameId);$i++)
            {
                $data2=array(
                'operationId'=>$operationId, 
                'operationNameId'=>$request->operationNameId[$i],  
                'operation_rate'=>$request->operation_rate[$i], 
                );
                
                OperationDetailModel::insert($data2);   
            }
            
            return redirect()->route('Operation.index')->with('message', 'Data Updated Succesfully');  
    }
     
     
      
    public function destroy($id)
    { 
        DB::table('operation_master')->where('OperationId', $id)->delete(); 
        DB::table('operation_details')->where('OperationId', $id)->delete();  
        Session::flash('messagedelete', 'Deleted record successfully');  
    }
    
    
        
    public function GetOperationList(Request $request)
    {
        $OperationList = DB::table('operation_name_master')->select('operation_name_master.operationNameId', 'operation_name_master.operation_name') 
                    ->where('main_style_id','=',$request->main_style_id) 
                    ->get();
         
        $html = ''; 
        $sr = 1;
        
        if(count($OperationList) > 0)
        {
            foreach ($OperationList as $row) 
            {
            
           
                $html .= '<tr>
                        <td><input type="srno" step="any" name="srno[]" class="form-control" value="'.$sr++.'"  style="width:80px;"></td>
                        <td> <div class="highlight-container"></div>
                                 <br><select name="operationNameId[]" class="form-select" id="operationNameId"  style="width:300px;" disabled > <option value="">--Select--</option> <option value="'.$row->operationNameId.'" selected readonly >'.$row->operation_name.'</option> </select> <div class="highlight-container"></div></td>
                        <td><input type="number" step="any" name="operation_rate[]" class="form-control" value=""></td>
                        <td>  
                            <a href="javascript:void(0);" class="btn btn-danger" onclick="removeRow(this);" > X </a>
                        </td> 
                    </tr>';
            }
        }
        else
        {
             $html .= '<tr>
                        <th colspan="4" class="text-center"><h2 style="color:red;"><b>Not Avalible Operations</b></h2><a href="../OperationName/create">For Add Operation Then Click Here....!</a></th> 
                      </tr>';
        }
        return response()->json(['html' => $html]);
    }
 
    
    public function GetMainstyleFromKDPL(Request $request)
    {
       
        $salesOrderList = DB::table('main_style_master')
            ->join('buyer_purchse_order_master', 'buyer_purchse_order_master.mainstyle_id', '=', 'main_style_master.mainstyle_id', 'left outer')  
            ->where('buyer_purchse_order_master.tr_code','=', $request->sales_order_no)
            ->get();   
         
        $html = '';
        foreach ($salesOrderList as $row) 
        {
            $html .= '<option value="'.$row->mainstyle_id.'">'.$row->mainstyle_name.'</option>';
        } 
        return response()->json(['html' => $html]);
    }
}